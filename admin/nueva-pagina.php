<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php');
  exit;
}
require_once '../includes/db.php';

// Crear tabla si no existe
$pdo->exec("CREATE TABLE IF NOT EXISTS paginas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  filepath VARCHAR(255) NOT NULL UNIQUE,
  contenido LONGTEXT,
  meta_title VARCHAR(255) DEFAULT '',
  meta_desc TEXT DEFAULT '',
  publicado TINYINT(1) DEFAULT 1,
  robots VARCHAR(20) DEFAULT 'index',
  fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
  modificado DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
try { $pdo->exec("ALTER TABLE paginas ADD COLUMN robots VARCHAR(20) DEFAULT 'index'"); } catch (PDOException $e) {}
try { $pdo->exec("ALTER TABLE paginas ADD COLUMN img_destacada VARCHAR(500) DEFAULT ''"); } catch (PDOException $e) {}

$mensaje = '';
$error   = '';
$pag     = [
  'id'         => '',
  'titulo'     => '',
  'slug'       => '',
  'filepath'   => '',
  'contenido'  => '',
  'meta_title' => '',
  'meta_desc'  => '',
  'publicado'     => 1,
  'robots'        => 'index',
  'img_destacada' => '',
];

$editando = false;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
  $stmt = $pdo->prepare('SELECT * FROM paginas WHERE id = ? LIMIT 1');
  $stmt->execute([$_GET['id']]);
  $found = $stmt->fetch();
  if ($found) {
    $pag      = $found;
    $editando = true;
    // Si la BD tiene contenido o metas vacíos, extraerlos del archivo en disco
    if (!empty($pag['filepath'])) {
      $abs_disco = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $pag['filepath']);
      if (file_exists($abs_disco)) {
        $raw_disco = file_get_contents($abs_disco);
        $sync_fields = [];

        // Extraer meta_title del archivo si está vacío en BD
        if (empty(trim($pag['meta_title'] ?? ''))) {
          if (preg_match('/\$meta_title\s*=\s*[\'"](.+?)[\'"]\s*;/', $raw_disco, $mm)) {
            $pag['meta_title'] = stripslashes($mm[1]);
            $sync_fields['meta_title'] = $pag['meta_title'];
          }
        }

        // Extraer meta_desc del archivo si está vacío en BD
        if (empty(trim($pag['meta_desc'] ?? ''))) {
          if (preg_match('/\$meta_desc\s*=\s*[\'"](.+?)[\'"]\s*;/', $raw_disco, $mm)) {
            $pag['meta_desc'] = stripslashes($mm[1]);
            $sync_fields['meta_desc'] = $pag['meta_desc'];
          }
        }

        // Extraer robots si está vacío
        if (empty(trim($pag['robots'] ?? ''))) {
          if (preg_match('/\$robots_meta\s*=\s*[\'"](.+?)[\'"]\s*;/', $raw_disco, $mm)) {
            $pag['robots'] = stripslashes($mm[1]);
            $sync_fields['robots'] = $pag['robots'];
          }
        }

        // Extraer HTML del archivo si contenido está vacío en BD
        if (trim($pag['contenido'] ?? '') === '') {
          $php_end = strpos($raw_disco, '?>');
          if ($php_end !== false) {
            $html_part  = ltrim(substr($raw_disco, $php_end + 2));
            $marker     = '<!-- /editable -->';
            $mpos       = strpos($html_part, $marker);
            if ($mpos !== false) {
              $html_part = rtrim(substr($html_part, 0, $mpos));
            } else {
              $footer_pos = strrpos($html_part, '<?php');
              if ($footer_pos !== false) $html_part = rtrim(substr($html_part, 0, $footer_pos));
            }
            if (trim($html_part) !== '') {
              $pag['contenido'] = $html_part;
              $sync_fields['contenido'] = $html_part;
            }
          }
        }

        // Sincronizar todo a BD de una vez
        if (!empty($sync_fields)) {
          $set_clauses = implode(', ', array_map(fn($k) => "{$k} = :{$k}", array_keys($sync_fields)));
          $sync_fields['id'] = $pag['id'];
          $upd = $pdo->prepare("UPDATE paginas SET {$set_clauses} WHERE id = :id");
          $upd->execute($sync_fields);
        }
      }
    }
  }
}

if (isset($_GET['importado']) && $editando) {
  $mensaje = '✅ Página importada correctamente. Edita el contenido y guarda los cambios.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $titulo     = trim($_POST['titulo']     ?? '');
  $slug       = trim($_POST['slug']       ?? '');
  $filepath   = trim($_POST['filepath']   ?? '');
  $contenido  = trim($_POST['contenido']  ?? '');
  $meta_title = trim($_POST['meta_title'] ?? '');
  $meta_desc  = trim($_POST['meta_desc']  ?? '');
  $publicado      = isset($_POST['publicado']) ? 1 : 0;
  $robots         = trim($_POST['robots']        ?? 'index');
  $img_destacada  = trim($_POST['img_destacada'] ?? '');

  // Auto-generar slug desde título si no existe
  if (!$slug && $titulo) {
    $slug = strtolower($titulo);
    $slug = str_replace(
      ['á','é','í','ó','ú','ñ','ü',' '],
      ['a','e','i','o','u','n','u','-'],
      $slug
    );
    $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = trim($slug, '-');
  }

  // Auto-generar filepath desde slug si no existe
  if (!$filepath && $slug) {
    $filepath = $slug . '.php';
  }

  // Validar filepath: letras minúsculas, números, guiones y guiones bajos.
  // Permite subdirectorios separados por / (ej: fontanero/elda.php, fontanero/elda/urgencias.php)
  if ($filepath && !preg_match('/^[a-z0-9_\-]+(\/[a-z0-9_\-]+)*\.php$/', $filepath)) {
    $error = 'El filepath solo puede contener letras minúsculas, números y guiones, con extensión .php. Subdirectorios permitidos con / (ej: fontanero/elda.php).';
  }

  if (!$error) {
    if (!$titulo || !$slug || !$filepath) {
      $error = 'Título, slug y filepath son obligatorios.';
    } else {
      try {
        if ($editando) {
          $stmt = $pdo->prepare('
            UPDATE paginas SET
              titulo=?, slug=?, filepath=?, contenido=?,
              meta_title=?, meta_desc=?, publicado=?, robots=?, img_destacada=?
            WHERE id=?
          ');
          $stmt->execute([
            $titulo, $slug, $filepath, $contenido,
            $meta_title, $meta_desc, $publicado, $robots, $img_destacada,
            $pag['id']
          ]);
          // Escribir archivo al disco (crear subdirectorio si hace falta)
          $abs_path = dirname(__DIR__) . '/' . str_replace('/', DIRECTORY_SEPARATOR, $filepath);
          $abs_dir  = dirname($abs_path);
          if (!is_dir($abs_dir)) mkdir($abs_dir, 0755, true);
          file_put_contents($abs_path, escribirArchivoPhp($abs_path, $titulo, $slug, $contenido, $meta_title, $meta_desc, $robots));
          $mensaje = '✅ Página actualizada correctamente.';
          $stmt = $pdo->prepare('SELECT * FROM paginas WHERE id = ? LIMIT 1');
          $stmt->execute([$pag['id']]);
          $pag = $stmt->fetch();
        } else {
          $stmt = $pdo->prepare('
            INSERT INTO paginas
              (titulo, slug, filepath, contenido, meta_title, meta_desc, publicado, robots, img_destacada)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
          ');
          $stmt->execute([
            $titulo, $slug, $filepath, $contenido,
            $meta_title, $meta_desc, $publicado, $robots, $img_destacada
          ]);
          $newId = $pdo->lastInsertId();
          // Escribir archivo al disco (crear subdirectorio si hace falta)
          $abs_path = dirname(__DIR__) . '/' . str_replace('/', DIRECTORY_SEPARATOR, $filepath);
          $abs_dir  = dirname($abs_path);
          if (!is_dir($abs_dir)) mkdir($abs_dir, 0755, true);
          file_put_contents($abs_path, escribirArchivoPhp($abs_path, $titulo, $slug, $contenido, $meta_title, $meta_desc, $robots));
          header('Location: nueva-pagina.php?id=' . $newId . '&ok=1');
          exit;
        }
      } catch (PDOException $e) {
        $error = 'Error al guardar. El slug o filepath puede estar duplicado.';
      }
    }
  }

  if ($error) {
    $pag = array_merge($pag, [
      'titulo'     => $titulo,
      'slug'       => $slug,
      'filepath'   => $filepath,
      'contenido'  => $contenido,
      'meta_title' => $meta_title,
      'meta_desc'  => $meta_desc,
      'publicado'  => $publicado,
      'robots'     => $robots,
    ]);
  }
}

if (isset($_GET['ok']) && !$mensaje) $mensaje = '✅ Página creada correctamente.';

// Escribe el archivo PHP en disco, preservando la estructura existente si el archivo ya existe.
// Para páginas nuevas usa un template limpio. Para páginas importadas sólo actualiza
// las variables de cabecera y el bloque HTML, manteniendo el código del agente intacto.
function escribirArchivoPhp($abs_path, $titulo, $slug, $contenido, $meta_title, $meta_desc, $robots) {
  if (!file_exists($abs_path)) {
    return generarContenidoPHP($titulo, $slug, $contenido, $meta_title, $meta_desc, $robots);
  }

  $raw        = file_get_contents($abs_path);
  $robots_val = ($robots === 'noindex') ? 'noindex' : 'index';

  // Actualizar variables PHP del bloque de cabecera
  $vars = [
    'meta_title'  => $meta_title,
    'meta_desc'   => $meta_desc,
    'robots_meta' => $robots_val,
  ];
  foreach ($vars as $var => $valor) {
    $pattern = '/(\$' . preg_quote($var, '/') . '\s*=\s*)[\'"][^\'\"]*[\'"]\s*;/';
    if (preg_match($pattern, $raw)) {
      $raw = preg_replace_callback($pattern, function() use ($var, $valor) {
        return '$' . $var . ' = \'' . addslashes($valor) . '\';';
      }, $raw, 1);
    }
  }

  // Reemplazar bloque HTML sólo si el editor tiene contenido
  if ($contenido !== '') {
    $php_end = strpos($raw, '?>');
    if ($php_end !== false) {
      $before    = substr($raw, 0, $php_end + 2);
      $after_php = substr($raw, $php_end + 2);
      $marker    = '<!-- /editable -->';
      $mpos      = strpos($after_php, $marker);
      if ($mpos !== false) {
        // Preservar todo desde el marcador en adelante (dinámico + footer)
        $raw = $before . "\n" . $contenido . "\n" . substr($after_php, $mpos);
      } else {
        // Fallback legacy: usar último <?php como delimitador
        $footer_pos = strrpos($after_php, '<?php');
        if ($footer_pos !== false) {
          $raw = $before . "\n" . $contenido . "\n" . substr($after_php, $footer_pos);
        }
      }
    }
  }

  return $raw;
}

function generarContenidoPHP($titulo, $slug, $contenido, $meta_title, $meta_desc, $robots = 'index') {
  $meta_title_esc = addslashes($meta_title ?: $titulo);
  $meta_desc_esc  = addslashes($meta_desc);
  $titulo_esc     = htmlspecialchars($titulo, ENT_QUOTES);
  $robots_val     = ($robots === 'noindex') ? 'noindex' : 'index';
  return <<<PHP
<?php
/**
 * {$titulo}
 * Generado por Panel Admin — CarolTemp
 */
\$meta_title  = '{$meta_title_esc}';
\$meta_desc   = '{$meta_desc_esc}';
\$meta_url    = 'https://caroltemp.com/{$slug}';
\$schema_type = 'local';
\$page_css    = 'default';
\$page_js     = '';
\$robots_meta = '{$robots_val}';
include 'includes/head.php';
?>

<main class="page-main">
  <section class="page-hero">
    <div class="container">
      <h1>{$titulo_esc}</h1>
    </div>
  </section>

  <section class="page-content">
    <div class="container">
      {$contenido}
    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>
PHP;
}
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $editando ? 'Editar página' : 'Nueva página'; ?> — CarolTemp Admin</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="../css/bloques-modal.css">
  <?php include '../includes/admin_style.php'; ?>
  <style>
    .google-preview { background: #fff; border: 1px solid #dde6f0; border-radius: 8px; padding: 1.25rem 1.5rem; margin-top: 1.25rem; }
    .google-preview-label { font-size: 11px; color: #7a95b0; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.75rem; }
    .gp-url { color: #006621; font-size: 13px; margin-bottom: 3px; }
    .gp-title { color: #1a0dab; font-size: 18px; font-weight: 400; line-height: 1.3; margin-bottom: 4px; }
    .gp-title:hover { text-decoration: underline; }
    .gp-desc { color: #545454; font-size: 13px; line-height: 1.55; }
    .gp-title.warn { color: #e74c3c; }
    .gp-desc.warn  { color: #e74c3c; }
    .filepath-hint { font-size: 12px; color: #7a95b0; margin-top: 4px; }
    .filepath-hint code { font-family: monospace; color: #1e3a5f; }
    .schema-info-box { border:1px solid #e2e8f0; border-radius:8px; overflow:hidden; }
    .schema-info-head { display:flex;justify-content:space-between;align-items:center;padding:.625rem 1rem;background:#f8fafc;cursor:pointer;font-size:12px;font-weight:600;color:#576574; }
    .schema-info-body { display:none;padding:1rem;border-top:1px solid #e2e8f0; }
    .schema-info-box.open .schema-info-body { display:block; }
    .schema-info-box.open .schema-info-chevron { transform:rotate(90deg); }
    .schema-info-chevron { transition:transform .2s; }

  </style>
  <script src="https://cdn.tiny.cloud/1/3eywuy73k0uzt30wiafptfr0tdx5iudt46gbkusw5kr5mjk2/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
  tinymce.init({
    selector: '#contenido',
    language: 'es',
    height: 500,
    menubar: false,
    plugins: 'lists link image media table code wordcount',
    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | link image | table | code | bloques',
    setup: function(editor) {
      editor.ui.registry.addButton('bloques', {
        text: '🧱 Bloques',
        tooltip: 'Insertar bloque de diseño',
        onAction: function() { abrirModalBloques(); }
      });
    },
    images_upload_handler: function(blobInfo, progress) {
      return new Promise(function(resolve, reject) {
        var fd = new FormData();
        fd.append('image', blobInfo.blob(), blobInfo.filename());
        fetch('/admin/upload-imagen.php', {
          method: 'POST',
          body: fd
        })
        .then(r => r.json())
        .then(data => {
          if (data.success) resolve(data.file.url);
          else reject(data.error);
        })
        .catch(() => reject('Error al subir la imagen'));
      });
    },
    content_style: 'body { font-family: Outfit, sans-serif; font-size: 15px; line-height: 1.8; color: #475569; max-width: 100%; }',
    block_formats: 'Párrafo=p; Título H2=h2; Título H3=h3; Título H4=h4',
    convert_urls: false,
    relative_urls: false
  });
  </script>
</head>
<body>

<?php include '../includes/admin_sidebar.php'; ?>

<main class="main">

  <div class="topbar">
    <h1><?php echo $editando ? 'Editar página' : 'Nueva página'; ?></h1>
    <a href="paginas.php">← Volver a páginas</a>
  </div>

  <?php if ($mensaje): ?>
    <div class="mensaje"><?php echo $mensaje; ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <div class="card">

      <!-- CONTENIDO -->
      <div class="form-section">
        <h2>Contenido</h2>
        <div class="form-grid">

          <div class="form-group full">
            <label for="titulo">Título de la página</label>
            <input type="text" id="titulo" name="titulo"
              value="<?php echo htmlspecialchars($pag['titulo']); ?>"
              placeholder="Ej: Política de privacidad"
              required oninput="generarSlugYFilepath(this.value); actualizarPreview()">
          </div>

          <div class="form-group full">
            <label for="slug">Slug (URL) <span class="label-hint">— se genera automáticamente</span></label>
            <input type="text" id="slug" name="slug"
              value="<?php echo htmlspecialchars($pag['slug']); ?>"
              placeholder="politica-privacidad"
              oninput="actualizarFilepathDesdeSlug(); actualizarPreview()">
            <span class="slug-preview" id="slug-preview">
              caroltemp.com/<?php echo htmlspecialchars($pag['slug'] ?: 'tu-slug-aqui'); ?>
            </span>
          </div>

          <div class="form-group full">
            <label for="filepath">Filepath <span class="label-hint">— nombre del archivo PHP en la raíz del sitio</span></label>
            <input type="text" id="filepath" name="filepath"
              value="<?php echo htmlspecialchars($pag['filepath']); ?>"
              placeholder="politica-privacidad.php"
              oninput="actualizarPreview()">
            <p class="filepath-hint">Ruta completa en disco: <code id="filepath-full">/var/www/html/<?php echo htmlspecialchars($pag['filepath'] ?: 'tu-pagina.php'); ?></code></p>
          </div>

          <div class="form-group full">
            <label for="contenido">Contenido <span class="label-hint">— editor visual</span></label>
            <textarea id="contenido" name="contenido" class="grande"
              placeholder="Escribe el contenido de la página aquí..."
            ><?php echo htmlspecialchars($pag['contenido']); ?></textarea>
          </div>

        </div>
      </div>

      <!-- SEO -->
      <div class="form-section">
        <h2>SEO</h2>
        <div class="form-grid">

          <div class="form-group full">
            <label for="meta_title">Meta title <span class="label-hint">— 50-60 caracteres recomendado</span></label>
            <input type="text" id="meta_title" name="meta_title"
              value="<?php echo htmlspecialchars($pag['meta_title']); ?>"
              placeholder="Ej: Política de privacidad — CarolTemp"
              oninput="contarChars(this, 'count-title', 60); actualizarPreview()">
            <span class="char-count" id="count-title"><?php echo strlen($pag['meta_title']); ?>/60</span>
          </div>

          <div class="form-group full">
            <label for="meta_desc">Meta description <span class="label-hint">— 140-160 caracteres recomendado</span></label>
            <textarea id="meta_desc" name="meta_desc" rows="3"
              placeholder="Descripción para Google."
              oninput="contarChars(this, 'count-desc', 160); actualizarPreview()"
            ><?php echo htmlspecialchars($pag['meta_desc']); ?></textarea>
            <span class="char-count" id="count-desc"><?php echo strlen($pag['meta_desc']); ?>/160</span>
          </div>

          <!-- SCHEMA.ORG INFO -->
          <div class="form-group full">
            <div class="schema-info-box">
              <div class="schema-info-head" onclick="this.parentElement.classList.toggle('open')">
                <span>📋 Schema.org automático</span>
                <span class="schema-info-chevron">▸</span>
              </div>
              <div class="schema-info-body">
                <p style="font-size:12px;color:#576574;margin:0 0 .5rem">
                  Tipo: <code>local</code> — generado automáticamente en head.php
                </p>
                <p style="font-size:12px;color:#576574;margin:0">
                  Para verificar el schema generado, visita la página en el navegador y usa
                  <a href="https://search.google.com/test/rich-results" target="_blank" rel="noopener">Google Rich Results Test</a>.
                </p>
              </div>
            </div>
          </div>

          <!-- PREVIEW GOOGLE -->
          <div class="form-group full">
            <div class="google-preview">
              <p class="google-preview-label">Vista previa en Google</p>
              <p class="gp-url">caroltemp.com › <span id="gp-slug"><?php echo htmlspecialchars($pag['slug'] ?: 'tu-slug'); ?></span></p>
              <p class="gp-title" id="gp-title"><?php echo htmlspecialchars($pag['meta_title'] ?: $pag['titulo'] ?: 'Título de la página'); ?></p>
              <p class="gp-desc" id="gp-desc"><?php echo htmlspecialchars($pag['meta_desc'] ?: 'Descripción de la página que aparecerá en los resultados de búsqueda de Google...'); ?></p>
            </div>
          </div>

        </div>
      </div>

      <!-- PUBLICAR -->
      <div class="form-section">
        <h2>Publicación</h2>
        <div class="form-grid">
          <div class="form-group">
            <label>Indexación en buscadores</label>
            <select name="robots">
              <option value="index" <?php echo ($pag['robots'] ?? 'index') === 'index' ? 'selected' : ''; ?>>index, follow — Visible en Google</option>
              <option value="noindex" <?php echo ($pag['robots'] ?? 'index') === 'noindex' ? 'selected' : ''; ?>>noindex — No indexar</option>
            </select>
          </div>
        </div>
        <div class="check-wrap" style="margin-bottom:1.5rem">
          <input type="checkbox" id="publicado" name="publicado" <?php echo $pag['publicado'] ? 'checked' : ''; ?>>
          <label for="publicado">Publicar página (visible en la web)</label>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn-save">
            <?php echo $editando ? 'Guardar cambios' : 'Crear página'; ?>
          </button>
          <?php if ($editando && $pag['slug']): ?>
            <a href="../<?php echo urlencode($pag['filepath']); ?>" target="_blank" class="btn-preview">
              Ver página →
            </a>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </form>

</main>

<?php include 'bloques-modal.php'; ?>

<script>
function slugify(texto) {
  const map = {'á':'a','é':'e','í':'i','ó':'o','ú':'u','ñ':'n','ü':'u','Á':'a','É':'e','Í':'i','Ó':'o','Ú':'u','Ñ':'n'};
  return texto.toLowerCase()
    .replace(/[áéíóúñüÁÉÍÓÚÑ]/g, m => map[m] || m)
    .replace(/[^a-z0-9\s\-]/g, '')
    .trim().replace(/\s+/g, '-').replace(/-+/g, '-');
}

function generarSlugYFilepath(titulo) {
  const slug = slugify(titulo);
  document.getElementById('slug').value = slug;
  document.getElementById('slug-preview').textContent = 'caroltemp.com/' + (slug || 'tu-slug-aqui');
  if (slug) {
    const fp = slug + '.php';
    document.getElementById('filepath').value = fp;
    document.getElementById('filepath-full').textContent = '/var/www/html/' + fp;
  }
  actualizarPreview();
}

function actualizarFilepathDesdeSlug() {
  const slug = document.getElementById('slug').value;
  document.getElementById('slug-preview').textContent = 'caroltemp.com/' + (slug || 'tu-slug-aqui');
  if (slug) {
    const fp = slug + '.php';
    document.getElementById('filepath').value = fp;
    document.getElementById('filepath-full').textContent = '/var/www/html/' + fp;
  }
}

document.getElementById('filepath').addEventListener('input', function() {
  document.getElementById('filepath-full').textContent = '/var/www/html/' + (this.value || 'tu-pagina.php');
});

function contarChars(el, countId, max) {
  const len  = el.value.length;
  const span = document.getElementById(countId);
  span.textContent = len + '/' + max;
  span.className   = 'char-count ' + (len > max ? 'warn' : 'ok');
}

function actualizarPreview() {
  const titulo = document.getElementById('meta_title').value ||
                 document.getElementById('titulo').value ||
                 'Título de la página';
  const desc   = document.getElementById('meta_desc').value ||
                 'Descripción de la página que aparecerá en los resultados de búsqueda de Google...';
  const slug   = document.getElementById('slug').value || 'tu-slug';

  const gpTitle = document.getElementById('gp-title');
  const gpDesc  = document.getElementById('gp-desc');
  const gpSlug  = document.getElementById('gp-slug');

  gpTitle.textContent = titulo.length > 60 ? titulo.substring(0, 60) + '...' : titulo;
  gpDesc.textContent  = desc.length > 160  ? desc.substring(0, 160)  + '...' : desc;
  gpSlug.textContent  = slug;

  gpTitle.className = 'gp-title' + (document.getElementById('meta_title').value.length > 60 ? ' warn' : '');
  gpDesc.className  = 'gp-desc'  + (document.getElementById('meta_desc').value.length  > 160 ? ' warn' : '');
}

document.addEventListener('DOMContentLoaded', function() {
  const title = document.getElementById('meta_title');
  const desc  = document.getElementById('meta_desc');
  if (title.value) contarChars(title, 'count-title', 60);
  if (desc.value)  contarChars(desc,  'count-desc',  160);
  actualizarPreview();

  // Sincronizar TinyMCE → textarea justo antes de enviar el formulario
  const form = document.querySelector('form');
  if (form) {
    form.addEventListener('submit', function() {
      if (typeof tinymce !== 'undefined' && tinymce.get('contenido')) {
        tinymce.get('contenido').save();
      }
    });
  }
});

// Preview de imagen destacada sin tocar TinyMCE
</script>

</body>
</html>
