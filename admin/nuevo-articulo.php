<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php');
  exit;
}
require_once '../includes/db.php';
require_once '../includes/img-sync.php';

try { $pdo->exec("ALTER TABLE articulos ADD COLUMN robots VARCHAR(20) DEFAULT 'index'"); } catch (PDOException $e) {}
try { $pdo->exec("ALTER TABLE articulos ADD COLUMN sidebar_tipo VARCHAR(30) DEFAULT ''"); } catch (PDOException $e) {}

$mensaje = '';
$error   = '';
$art     = [
  'id'        => '',
  'titulo'    => '',
  'slug'      => '',
  'extracto'  => '',
  'contenido' => '',
  'imagen'    => '',
  'zona'      => '',
  'categoria' => '',
  'meta_title'=> '',
  'meta_desc' => '',
  'publicado'    => 0,
  'robots'       => 'index',
  'sidebar_tipo' => '',
];

$editando = false;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
  $stmt = $pdo->prepare('SELECT * FROM articulos WHERE id = ? LIMIT 1');
  $stmt->execute([$_GET['id']]);
  $found = $stmt->fetch();
  if ($found) {
    $art      = $found;
    $editando = true;
  }
}

// SUBIDA DE IMAGEN
function subirImagen($file, $carpeta) {
  $permitidos = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
  if (!in_array($file['type'], $permitidos)) {
    return ['error' => 'Formato no permitido. Usa JPG, PNG, WebP o GIF.'];
  }
  if ($file['size'] > 3 * 1024 * 1024) {
    return ['error' => 'La imagen no puede superar 3MB.'];
  }
  $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
  $nombre   = uniqid('img_') . '.' . strtolower($ext);
  $dir      = dirname(__DIR__) . '/img/' . $carpeta . '/';
  if (!is_dir($dir)) mkdir($dir, 0755, true);
  $ruta_abs = $dir . $nombre;
  $ruta_web = 'img/' . $carpeta . '/' . $nombre;
  if (!move_uploaded_file($file['tmp_name'], $ruta_abs)) {
    return ['error' => 'Error al guardar la imagen.'];
  }
  return ['ruta' => $ruta_web];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $titulo     = trim($_POST['titulo']     ?? '');
  $slug       = trim($_POST['slug']       ?? '');
  $extracto   = trim($_POST['extracto']   ?? '');
  $contenido  = trim($_POST['contenido']  ?? '');
  $imagen     = trim($_POST['imagen']     ?? '');
  $zona       = trim($_POST['zona']       ?? '');
  $categoria  = trim($_POST['categoria']  ?? '');
  $meta_title = trim($_POST['meta_title'] ?? '');
  $meta_desc  = trim($_POST['meta_desc']  ?? '');
  $publicado     = isset($_POST['publicado']) ? 1 : 0;
  $robots        = trim($_POST['robots'] ?? 'index');
  $sidebar_tipo  = trim($_POST['sidebar_tipo'] ?? '');

  // Subida de imagen si hay archivo
  if (!empty($_FILES['imagen_file']['name'])) {
    $resultado = subirImagen($_FILES['imagen_file'], 'blog');
    if (isset($resultado['error'])) {
      $error = $resultado['error'];
    } else {
      $imagen = $resultado['ruta'];
      // Sincronizar a producción automáticamente
      syncImgToProduction(dirname(__DIR__) . '/' . $imagen, $imagen);
    }
  }

  // Generar slug automático
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

  if (!$error) {
    if (!$titulo || !$slug || !$extracto || !$contenido) {
      $error = 'Título, slug, extracto y contenido son obligatorios.';
    } else {
      try {
        if ($editando) {
          $stmt = $pdo->prepare('
            UPDATE articulos SET
              titulo=?, slug=?, extracto=?, contenido=?,
              imagen=?, zona=?, categoria=?,
              meta_title=?, meta_desc=?, publicado=?, robots=?, sidebar_tipo=?
            WHERE id=?
          ');
          $stmt->execute([
            $titulo, $slug, $extracto, $contenido,
            $imagen, $zona, $categoria,
            $meta_title, $meta_desc, $publicado, $robots, $sidebar_tipo,
            $art['id']
          ]);
          $mensaje = '✅ Artículo actualizado correctamente.';
          $stmt = $pdo->prepare('SELECT * FROM articulos WHERE id = ? LIMIT 1');
          $stmt->execute([$art['id']]);
          $art = $stmt->fetch();
        } else {
          $stmt = $pdo->prepare('
            INSERT INTO articulos
              (titulo, slug, extracto, contenido, imagen, zona, categoria, meta_title, meta_desc, publicado, robots, sidebar_tipo)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
          ');
          $stmt->execute([
            $titulo, $slug, $extracto, $contenido,
            $imagen, $zona, $categoria,
            $meta_title, $meta_desc, $publicado, $robots, $sidebar_tipo
          ]);
          $newId = $pdo->lastInsertId();
          header('Location: nuevo-articulo.php?id=' . $newId . '&ok=1');
          exit;
        }
      } catch (PDOException $e) {
        $error = 'Error al guardar. El slug puede estar duplicado.';
      }
    }
  }

  // Mantener valores en el form si hay error
  if ($error) {
    $art = array_merge($art, [
      'titulo'    => $titulo,
      'slug'      => $slug,
      'extracto'  => $extracto,
      'contenido' => $contenido,
      'imagen'    => $imagen,
      'zona'      => $zona,
      'categoria' => $categoria,
      'meta_title'=> $meta_title,
      'meta_desc' => $meta_desc,
      'publicado'    => $publicado,
      'robots'       => $robots,
      'sidebar_tipo' => $sidebar_tipo,
    ]);
  }
}

if (isset($_GET['ok'])) $mensaje = '✅ Artículo creado correctamente.';

$zonas      = ['Elda','Petrer','Novelda','Monóvar','Sax','Pinoso','Monforte del Cid','Salinas','General'];
$categorias = $pdo->query('SELECT nombre FROM categorias_blog ORDER BY orden ASC')->fetchAll(PDO::FETCH_COLUMN);
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $editando ? 'Editar artículo' : 'Nuevo artículo'; ?> — CarolTemp Admin</title>
  <meta name="robots" content="noindex, nofollow">
  <!-- CSS web completo para preview de bloques -->
  <link rel="stylesheet" href="../css/global.css">
  <link rel="stylesheet" href="../css/nav.css">
  <link rel="stylesheet" href="../css/footer.css">
  <link rel="stylesheet" href="../css/pages/home.css">
  <link rel="stylesheet" href="../css/pages/blog.css">
  <link rel="stylesheet" href="../css/pages/proyectos.css">
  <link rel="stylesheet" href="../css/pages/servicios.css">
  <link rel="stylesheet" href="../css/pages/financiacion.css">
  <link rel="stylesheet" href="../css/pages/zonas.css">
  <link rel="stylesheet" href="../css/pages/zona.css">
  <link rel="stylesheet" href="../css/pages/servicio-ciudad.css">
  <link rel="stylesheet" href="../css/pages/contacto.css">
  <link rel="stylesheet" href="../css/pages/sobre.css">
  <link rel="stylesheet" href="../css/pages/legal.css">
  <link rel="stylesheet" href="../css/bloques-modal.css">
  <?php include '../includes/admin_style.php'; ?>
  <style>
    .google-preview { background: #fff; border: 1px solid #dde6f0; border-radius: 8px; padding: 1.25rem 1.5rem; margin-top: 1.25rem; }
    .google-preview-label { font-size: 11px; color: #7a95b0; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.75rem; }
    .gp-url { color: #006621; font-size: 13px; margin-bottom: 3px; }
    .gp-title { color: #1a0dab; font-size: 18px; font-weight: 400; line-height: 1.3; margin-bottom: 4px; cursor: pointer; }
    .gp-title:hover { text-decoration: underline; }
    .gp-desc { color: #545454; font-size: 13px; line-height: 1.55; }
    .gp-title.warn { color: #e74c3c; }
    .gp-desc.warn  { color: #e74c3c; }
    .upload-area { border: 2px dashed #dde6f0; border-radius: 8px; padding: 2rem; text-align: center; cursor: pointer; transition: border-color 0.15s, background 0.15s; }
    .upload-area:hover, .upload-area.drag { border-color: #1e3a5f; background: #f4f7fb; }
    .upload-area input[type="file"] { display: none; }
    .upload-area-ico { font-size: 32px; margin-bottom: 0.5rem; }
    .upload-area-txt { color: #7a95b0; font-size: 13.5px; }
    .upload-area-txt strong { color: #1e3a5f; }
    .imagen-preview { margin-top: 1rem; display: none; }
    .imagen-preview img { max-width: 100%; max-height: 200px; border-radius: 8px; border: 1px solid #dde6f0; object-fit: cover; }
    .imagen-preview-actual { margin-top: 0.5rem; }
    .imagen-preview-actual img { max-width: 100%; max-height: 160px; border-radius: 8px; border: 1px solid #dde6f0; object-fit: cover; }
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
  images_upload_url: '/admin/upload-imagen.php',
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
  skin: 'oxide',
  content_css: 'default',
  convert_urls: false,
  relative_urls: false
});
</script>
</head>
<body>

<?php include '../includes/admin_sidebar.php'; ?>

<main class="main">

  <div class="topbar">
    <h1><?php echo $editando ? 'Editar artículo' : 'Nuevo artículo'; ?></h1>
    <a href="articulos.php">← Volver a artículos</a>
  </div>

  <?php if ($mensaje): ?>
    <div class="mensaje"><?php echo $mensaje; ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <form method="POST" action="" enctype="multipart/form-data">
    <div class="card">

      <!-- CONTENIDO -->
      <div class="form-section">
        <h2>Contenido</h2>
        <div class="form-grid">

          <div class="form-group full">
            <label for="titulo">Título</label>
            <input type="text" id="titulo" name="titulo"
              value="<?php echo htmlspecialchars($art['titulo']); ?>"
              placeholder="Ej: Cuánto cuesta cambiar un grifo en Elda"
              required oninput="generarSlug(this.value); actualizarPreview()">
          </div>

          <div class="form-group full">
            <label for="slug">Slug (URL) <span class="label-hint">— se genera automáticamente</span></label>
            <input type="text" id="slug" name="slug"
              value="<?php echo htmlspecialchars($art['slug']); ?>"
              placeholder="cuanto-cuesta-cambiar-grifo-elda"
              oninput="actualizarPreview()">
            <span class="slug-preview" id="slug-preview">
              caroltemp.com/blog/<?php echo htmlspecialchars($art['slug'] ?: 'tu-slug-aqui'); ?>
            </span>
          </div>

          <div class="form-group full">
            <label for="extracto">Extracto</label>
            <textarea id="extracto" name="extracto" rows="3"
              placeholder="Breve descripción del artículo (2-3 frases)..."
            ><?php echo htmlspecialchars($art['extracto']); ?></textarea>
          </div>

          <div class="form-group full">
            <label for="contenido">Contenido <span class="label-hint">— puedes usar HTML básico</span></label>
            <textarea id="contenido" name="contenido" class="grande"
              placeholder="Contenido completo. Puedes usar: <h2>, <p>, <ul>, <li>, <strong>..."
            ><?php echo htmlspecialchars($art['contenido']); ?></textarea>
          </div>

        </div>
      </div>

      <!-- IMAGEN -->
      <div class="form-section">
        <h2>Imagen destacada</h2>

        <?php if ($art['imagen']): ?>
          <div class="imagen-preview-actual">
            <p style="font-size:12px;color:#7a95b0;margin-bottom:0.5rem">Imagen actual:</p>
            <img src="<?php echo htmlspecialchars($art['imagen']); ?>" alt="Imagen actual">
          </div>
          <p style="font-size:12px;color:#7a95b0;margin:0.75rem 0">Sube una nueva imagen para reemplazarla, o mantén la actual.</p>
        <?php endif; ?>

        <div class="upload-area" id="upload-area" onclick="document.getElementById('imagen_file').click()">
          <input type="file" id="imagen_file" name="imagen_file" accept="image/*" onchange="previsualizarImagen(this)">
          <div class="upload-area-ico">🖼️</div>
          <p class="upload-area-txt"><strong>Haz clic para subir</strong> o arrastra una imagen aquí</p>
          <p class="upload-area-txt" style="font-size:12px;margin-top:4px">JPG, PNG, WebP · Máx. 3MB</p>
        </div>

        <div class="imagen-preview" id="imagen-preview">
          <img id="imagen-preview-img" src="" alt="Preview">
        </div>

        <div class="form-group" style="margin-top:1rem">
          <label for="imagen">O introduce la URL de la imagen manualmente</label>
          <input type="text" id="imagen" name="imagen"
            value="<?php echo htmlspecialchars($art['imagen']); ?>"
            placeholder="/img/blog/nombre-imagen.webp">
        </div>
      </div>

      <!-- CLASIFICACIÓN -->
      <div class="form-section">
        <h2>Clasificación</h2>
        <div class="form-grid">
          <div class="form-group">
            <label for="zona">Zona</label>
            <select id="zona" name="zona">
              <option value="">Sin zona específica</option>
              <?php foreach ($zonas as $z): ?>
                <option value="<?php echo $z; ?>" <?php echo $art['zona'] === $z ? 'selected' : ''; ?>><?php echo $z; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="categoria">Categoría</label>
            <select id="categoria" name="categoria">
              <option value="">Sin categoría</option>
              <?php foreach ($categorias as $c): ?>
                <option value="<?php echo $c; ?>" <?php echo $art['categoria'] === $c ? 'selected' : ''; ?>><?php echo $c; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>

      <!-- WIDGET DE ZONA -->
      <div class="form-section">
        <h2>Widget de zona (sidebar)</h2>
        <p style="font-size:13px;color:#7a95b0;margin:-.5rem 0 1.25rem">El sidebar del artículo muestra una tarjeta de enlace. Elige a qué página dirige y el texto cambia automáticamente según la zona seleccionada.</p>
        <div class="form-grid">
          <div class="form-group full">
            <label for="sidebar_tipo">Tipo de enlace en el sidebar</label>
            <select id="sidebar_tipo" name="sidebar_tipo" onchange="actualizarWidgetPreview()">
              <option value="" <?php echo ($art['sidebar_tipo']??'') === '' ? 'selected' : ''; ?>>— Genérico (hub ciudad por defecto) —</option>
              <option value="hub" <?php echo ($art['sidebar_tipo']??'') === 'hub' ? 'selected' : ''; ?>>Hub ciudad — "Fontanero en {Ciudad}" → /fontanero/{ciudad}</option>
              <option value="urgencias" <?php echo ($art['sidebar_tipo']??'') === 'urgencias' ? 'selected' : ''; ?>>Urgencias 24h — "Urgencias 24h en {Ciudad}" → /fontanero/{ciudad}/urgencias</option>
              <option value="desatascos" <?php echo ($art['sidebar_tipo']??'') === 'desatascos' ? 'selected' : ''; ?>>Desatascos — "Desatascos en {Ciudad}" → /fontanero/{ciudad}/desatascos</option>
              <option value="fugas" <?php echo ($art['sidebar_tipo']??'') === 'fugas' ? 'selected' : ''; ?>>Fugas — "Reparación de fugas en {Ciudad}" → /fontanero/{ciudad}/fugas</option>
            </select>
          </div>
          <div class="form-group full" id="widget-preview-wrap" style="display:<?php echo ($art['sidebar_tipo']??'') ? 'block' : 'none'; ?>">
            <div style="background:#f8fafc;border:1.5px solid #dde6f0;border-radius:8px;padding:1rem 1.25rem">
              <p style="font-size:11px;color:#7a95b0;font-weight:600;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.75rem">Vista previa del widget</p>
              <strong id="wp-titulo" style="display:block;color:#0d1f33;font-size:14px;margin-bottom:.4rem"><?php
                $prev_zona = $art['zona'] ?: '{Ciudad}';
                $prev_tipo = $art['sidebar_tipo'] ?? '';
                $prev_configs = [
                  'hub'         => "Fontanero en {$prev_zona}",
                  'urgencias'   => "Urgencias 24h en {$prev_zona}",
                  'desatascos'  => "Desatascos en {$prev_zona}",
                  'fugas'       => "Reparación de fugas en {$prev_zona}",
                ];
                echo htmlspecialchars($prev_configs[$prev_tipo] ?? '');
              ?></strong>
              <p id="wp-desc" style="color:#576574;font-size:13px;margin-bottom:.75rem"><?php
                $prev_descs = [
                  'hub'        => 'La calidad, rapidez y eficacia es nuestra seña de identidad.',
                  'urgencias'  => 'Presupuesto gratuito con la avería vista.',
                  'desatascos' => 'Soluciones rápidas para atascos y obstrucciones.',
                  'fugas'      => 'Detectamos y reparamos fugas con garantía.',
                ];
                echo htmlspecialchars($prev_descs[$prev_tipo] ?? '');
              ?></p>
              <span id="wp-btn" style="display:inline-block;background:#fff;color:#1e3a5f;padding:7px 12px;border-radius:6px;font-size:13px;font-weight:600;border:1.5px solid #e2e8f0"><?php
                $prev_btns = [
                  'hub'        => "Ver servicios en {$prev_zona} →",
                  'urgencias'  => "Urgencias en {$prev_zona} →",
                  'desatascos' => "Desatascos en {$prev_zona} →",
                  'fugas'      => "Fugas en {$prev_zona} →",
                ];
                echo htmlspecialchars($prev_btns[$prev_tipo] ?? '');
              ?></span>
            </div>
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
              value="<?php echo htmlspecialchars($art['meta_title']); ?>"
              placeholder="Ej: Cuánto cuesta cambiar un grifo en Elda — CarolTemp"
              oninput="contarChars(this, 'count-title', 60); actualizarPreview()">
            <span class="char-count" id="count-title"><?php echo strlen($art['meta_title']); ?>/60</span>
          </div>

          <div class="form-group full">
            <label for="meta_desc">Meta description <span class="label-hint">— 140-160 caracteres recomendado</span></label>
            <textarea id="meta_desc" name="meta_desc" rows="3"
              placeholder="Descripción que aparece en Google. Incluye zona y servicio."
              oninput="contarChars(this, 'count-desc', 160); actualizarPreview()"
            ><?php echo htmlspecialchars($art['meta_desc']); ?></textarea>
            <span class="char-count" id="count-desc"><?php echo strlen($art['meta_desc']); ?>/160</span>
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
                  Tipo: <code>articulo</code> — generado automáticamente en head.php
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
              <p class="gp-url">caroltemp.com › blog › <span id="gp-slug"><?php echo htmlspecialchars($art['slug'] ?: 'tu-slug'); ?></span></p>
              <p class="gp-title" id="gp-title"><?php echo htmlspecialchars($art['meta_title'] ?: $art['titulo'] ?: 'Título del artículo'); ?></p>
              <p class="gp-desc" id="gp-desc"><?php echo htmlspecialchars($art['meta_desc'] ?: 'Descripción del artículo que aparecerá en los resultados de búsqueda de Google...'); ?></p>
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
              <option value="index" <?php echo ($art['robots'] ?? 'index') === 'index' ? 'selected' : ''; ?>>index, follow — Visible en Google</option>
              <option value="noindex" <?php echo ($art['robots'] ?? 'index') === 'noindex' ? 'selected' : ''; ?>>noindex — No indexar</option>
            </select>
          </div>
        </div>
        <div class="check-wrap" style="margin-bottom:1.5rem">
          <input type="checkbox" id="publicado" name="publicado" <?php echo $art['publicado'] ? 'checked' : ''; ?>>
          <label for="publicado">Publicar artículo (visible en la web)</label>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn-save">
            <?php echo $editando ? 'Guardar cambios' : 'Crear artículo'; ?>
          </button>
          <?php if ($editando && $art['slug']): ?>
            <a href="../blog/articulo.php?slug=<?php echo urlencode($art['slug']); ?>" target="_blank" class="btn-preview">
              Ver artículo →
            </a>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </form>

</main>

<?php include 'bloques-modal.php'; ?>

<script>
// Slug automático
function generarSlug(titulo) {
  const map = {'á':'a','é':'e','í':'i','ó':'o','ú':'u','ñ':'n','ü':'u','Á':'a','É':'e','Í':'i','Ó':'o','Ú':'u','Ñ':'n'};
  let slug = titulo.toLowerCase()
    .replace(/[áéíóúñüÁÉÍÓÚÑ]/g, m => map[m] || m)
    .replace(/[^a-z0-9\s\-]/g, '')
    .trim().replace(/\s+/g, '-').replace(/-+/g, '-');
  document.getElementById('slug').value = slug;
  document.getElementById('slug-preview').textContent = 'caroltemp.com/blog/' + (slug || 'tu-slug-aqui');
}

document.getElementById('slug').addEventListener('input', function() {
  document.getElementById('slug-preview').textContent = 'caroltemp.com/blog/' + (this.value || 'tu-slug-aqui');
  actualizarPreview();
});

// Contador caracteres
function contarChars(el, countId, max) {
  const len  = el.value.length;
  const span = document.getElementById(countId);
  span.textContent  = len + '/' + max;
  span.className    = 'char-count ' + (len > max ? 'warn' : 'ok');
}

// Preview Google
function actualizarPreview() {
  const titulo = document.getElementById('meta_title').value ||
                 document.getElementById('titulo').value ||
                 'Título del artículo';
  const desc   = document.getElementById('meta_desc').value ||
                 'Descripción del artículo que aparecerá en los resultados de búsqueda de Google...';
  const slug   = document.getElementById('slug').value || 'tu-slug';

  const gpTitle = document.getElementById('gp-title');
  const gpDesc  = document.getElementById('gp-desc');
  const gpSlug  = document.getElementById('gp-slug');

  gpTitle.textContent = titulo.length > 60 ? titulo.substring(0, 60) + '...' : titulo;
  gpDesc.textContent  = desc.length > 160  ? desc.substring(0, 160) + '...'  : desc;
  gpSlug.textContent  = slug;

  gpTitle.className = 'gp-title' + (document.getElementById('meta_title').value.length > 60 ? ' warn' : '');
  gpDesc.className  = 'gp-desc'  + (document.getElementById('meta_desc').value.length  > 160 ? ' warn' : '');
}

// Subida imagen — preview
function previsualizarImagen(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      const preview = document.getElementById('imagen-preview');
      const img     = document.getElementById('imagen-preview-img');
      img.src = e.target.result;
      preview.style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
  }
}

// Drag & drop
const uploadArea = document.getElementById('upload-area');
uploadArea.addEventListener('dragover', e => { e.preventDefault(); uploadArea.classList.add('drag'); });
uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('drag'));
uploadArea.addEventListener('drop', e => {
  e.preventDefault();
  uploadArea.classList.remove('drag');
  const file = e.dataTransfer.files[0];
  if (file && file.type.startsWith('image/')) {
    const input = document.getElementById('imagen_file');
    const dt    = new DataTransfer();
    dt.items.add(file);
    input.files = dt.files;
    previsualizarImagen(input);
  }
});

// Widget de zona — preview
const widgetConfigs = {
  '':           null,
  'hub':        { titulo: 'Fontanero en {zona}',             desc: 'La calidad, rapidez y eficacia es nuestra seña de identidad.', btn: 'Ver servicios en {zona} →' },
  'urgencias':  { titulo: 'Urgencias 24h en {zona}',         desc: 'Presupuesto gratuito con la avería vista.',                    btn: 'Urgencias en {zona} →' },
  'desatascos': { titulo: 'Desatascos en {zona}',            desc: 'Soluciones rápidas para atascos y obstrucciones.',             btn: 'Desatascos en {zona} →' },
  'fugas':      { titulo: 'Reparación de fugas en {zona}',   desc: 'Detectamos y reparamos fugas con garantía.',                  btn: 'Fugas en {zona} →' },
};

function actualizarWidgetPreview() {
  const tipo = document.getElementById('sidebar_tipo').value;
  const zona = document.getElementById('zona').value || '{Ciudad}';
  const wrap = document.getElementById('widget-preview-wrap');
  if (!tipo || !widgetConfigs[tipo]) { wrap.style.display = 'none'; return; }
  const cfg = widgetConfigs[tipo];
  document.getElementById('wp-titulo').textContent = cfg.titulo.replace('{zona}', zona);
  document.getElementById('wp-desc').textContent   = cfg.desc;
  document.getElementById('wp-btn').textContent    = cfg.btn.replace('{zona}', zona);
  wrap.style.display = 'block';
}
document.getElementById('zona').addEventListener('change', actualizarWidgetPreview);

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
  const title = document.getElementById('meta_title');
  const desc  = document.getElementById('meta_desc');
  if (title.value) contarChars(title, 'count-title', 60);
  if (desc.value)  contarChars(desc,  'count-desc',  160);
  actualizarPreview();
  actualizarWidgetPreview();
});
</script>

</body>
</html>