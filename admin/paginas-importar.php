<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php'); exit;
}
require_once '../includes/db.php';

// Archivos a ignorar (sistema, blog, proyectos, admin)
$ignorar = [
  'index.php', '404.php', 'robots.php', 'sitemap.php', 'cambiar-password.php',
];
$ignorar_dirs = ['admin', 'includes', 'noticias', 'proyectos', 'blog'];

$raiz = dirname(__DIR__);

function extraerMeta(string $contenido, string $campo): string {
  if (preg_match('/\$' . $campo . '\s*=\s*[\'"](.+?)[\'"]\s*;/', $contenido, $m)) {
    return stripslashes($m[1]);
  }
  return '';
}

function extraerTitulo(string $contenido, string $filepath): string {
  // Intentar <h1>, <title>, $meta_title, $titulo, nombre del archivo
  if (preg_match('/<h1[^>]*>(.+?)<\/h1>/is', $contenido, $m)) return trim(strip_tags($m[1]));
  $t = extraerMeta($contenido, 'meta_title') ?: extraerMeta($contenido, 'titulo');
  if ($t) return $t;
  $base = basename($filepath, '.php');
  return ucwords(str_replace(['-','_'], ' ', $base));
}

function slugDesdeFilepath(string $fp): string {
  $s = preg_replace('/\.php$/', '', $fp);
  $s = str_replace('/', '-', $s);
  return $s;
}

// Escanear archivos
function escanear(string $dir, string $raiz, array $ignorar, array $ignorar_dirs): array {
  $archivos = [];
  $it = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)
  );
  foreach ($it as $f) {
    if (!$f->isFile() || $f->getExtension() !== 'php') continue;
    $rel = str_replace($raiz . DIRECTORY_SEPARATOR, '', $f->getPathname());
    $rel = str_replace('\\', '/', $rel);
    // Ignorar carpetas excluidas
    $partes = explode('/', $rel);
    $skip = false;
    foreach ($ignorar_dirs as $d) {
      if (in_array($d, $partes)) { $skip = true; break; }
    }
    if ($skip) continue;
    if (in_array(basename($rel), $ignorar)) continue;
    // Solo index.php en raíz se ignora
    if ($rel === 'index.php') continue;
    $archivos[] = $rel;
  }
  sort($archivos);
  return $archivos;
}

$archivos = escanear($raiz, $raiz, $ignorar, $ignorar_dirs);

// Páginas ya registradas
$ya_registradas = $pdo->query('SELECT filepath FROM paginas')->fetchAll(PDO::FETCH_COLUMN);
$ya_registradas = array_flip($ya_registradas);

// Procesar importación
$importados = [];
$omitidos   = [];
$errores    = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['importar'])) {
  $seleccionados = $_POST['archivos'] ?? [];

  foreach ($seleccionados as $filepath) {
    if (isset($ya_registradas[$filepath])) {
      $omitidos[] = $filepath;
      continue;
    }
    $abs = $raiz . '/' . $filepath;
    if (!file_exists($abs)) {
      $errores[] = $filepath . ' — archivo no encontrado';
      continue;
    }
    $contenido_raw = file_get_contents($abs);
    $titulo     = extraerTitulo($contenido_raw, $filepath);
    $meta_title = extraerMeta($contenido_raw, 'meta_title');
    $meta_desc  = extraerMeta($contenido_raw, 'meta_desc');
    $robots     = extraerMeta($contenido_raw, 'robots_meta') ?: 'index';
    $slug       = slugDesdeFilepath($filepath);

    // Extraer HTML del body
    $html = '';
    $php_end = strpos($contenido_raw, '?>');
    if ($php_end !== false) {
      $html_part = ltrim(substr($contenido_raw, $php_end + 2));
      $marker    = '<!-- /editable -->';
      $mpos      = strpos($html_part, $marker);
      if ($mpos !== false) {
        $html = rtrim(substr($html_part, 0, $mpos));
      } else {
        $footer = strrpos($html_part, '<?php');
        if ($footer !== false) $html = rtrim(substr($html_part, 0, $footer));
        else $html = trim($html_part);
      }
    }

    try {
      $pdo->prepare('INSERT INTO paginas (titulo, slug, filepath, contenido, meta_title, meta_desc, publicado, robots) VALUES (?,?,?,?,?,?,1,?)')
          ->execute([$titulo, $slug, $filepath, $html, $meta_title, $meta_desc, $robots]);
      $importados[] = ['filepath' => $filepath, 'titulo' => $titulo];
      $ya_registradas[$filepath] = true;
    } catch (PDOException $e) {
      $errores[] = $filepath . ' — ' . $e->getMessage();
    }
  }
}

// Agrupar archivos para mostrar
$por_grupo = [];
foreach ($archivos as $f) {
  $partes = explode('/', $f);
  $grupo  = count($partes) > 1 ? $partes[0] : 'raíz';
  $por_grupo[$grupo][] = $f;
}

$pagina_actual = 'paginas-importar.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Importar páginas — Admin</title>
  <meta name="robots" content="noindex,nofollow">
  <?php include '../includes/admin_style.php'; ?>
  <style>
    .imp-group { margin-bottom: 1.5rem; }
    .imp-group-label { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .1em; padding: .5rem 0 .5rem 1.25rem; }
    .imp-row { display: flex; align-items: center; gap: .75rem; padding: .6rem 1.25rem; border-bottom: 1px solid #f1f5f9; transition: background .1s; }
    .imp-row:hover { background: #f8fafc; }
    .imp-row:last-child { border-bottom: none; }
    .imp-check { width: 16px; height: 16px; accent-color: #3b5bdb; flex-shrink: 0; cursor: pointer; }
    .imp-path { font-family: monospace; font-size: 12.5px; color: #0f172a; flex: 1; }
    .imp-badge-ya { font-size: 11px; background: #f1f5f9; color: #94a3b8; padding: 2px 8px; border-radius: 20px; font-weight: 500; }
    .imp-badge-ok { font-size: 11px; background: #dcfce7; color: #16a34a; padding: 2px 8px; border-radius: 20px; font-weight: 600; }
    .sel-actions { display: flex; gap: .5rem; align-items: center; margin-bottom: 1rem; }
    .btn-sel { background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 6px; padding: 6px 14px; font-size: 12.5px; font-weight: 600; color: #374151; cursor: pointer; }
    .btn-sel:hover { border-color: #3b5bdb; color: #3b5bdb; }
  </style>
</head>
<body>
<?php include '../includes/admin_sidebar.php'; ?>
<div class="main">

  <div class="topbar">
    <h1>Importar páginas del disco</h1>
    <a href="paginas.php">← Volver a páginas</a>
  </div>

  <?php if (!empty($importados)): ?>
  <div class="mensaje">✅ <?php echo count($importados); ?> página<?php echo count($importados)>1?'s':''; ?> importada<?php echo count($importados)>1?'s':''; ?> correctamente.</div>
  <?php endif; ?>
  <?php if (!empty($errores)): ?>
  <div class="error-msg"><?php foreach ($errores as $e) echo htmlspecialchars($e) . '<br>'; ?></div>
  <?php endif; ?>

  <!-- RESUMEN -->
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.75rem">
    <div class="card" style="padding:1.25rem 1.5rem">
      <div style="font-size:26px;font-weight:800;color:#0f172a"><?php echo count($archivos); ?></div>
      <div style="font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.06em;margin-top:4px">Archivos en disco</div>
    </div>
    <div class="card" style="padding:1.25rem 1.5rem">
      <div style="font-size:26px;font-weight:800;color:#16a34a"><?php echo count($ya_registradas); ?></div>
      <div style="font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.06em;margin-top:4px">Ya registradas en BD</div>
    </div>
    <div class="card" style="padding:1.25rem 1.5rem">
      <?php $pendientes = count(array_filter($archivos, function($f) use ($ya_registradas) { return !isset($ya_registradas[$f]); })); ?>
      <div style="font-size:26px;font-weight:800;color:<?php echo $pendientes>0?'#ea580c':'#94a3b8'; ?>"><?php echo $pendientes; ?></div>
      <div style="font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.06em;margin-top:4px">Pendientes de importar</div>
    </div>
  </div>

  <?php if ($pendientes === 0): ?>
    <div class="mensaje">✅ Todas las páginas del disco ya están registradas en la base de datos.</div>
  <?php else: ?>

  <form method="POST">
    <div class="card" style="margin-bottom:1.25rem">
      <div class="card-header">
        <h2>Selecciona las páginas a importar</h2>
      </div>
      <div style="padding:.75rem 1.25rem;border-bottom:1px solid #f1f5f9;display:flex;gap:.5rem;align-items:center">
        <button type="button" class="btn-sel" onclick="toggleTodos(true)">Seleccionar pendientes</button>
        <button type="button" class="btn-sel" onclick="toggleTodos(false)">Deseleccionar todo</button>
        <span style="font-size:12.5px;color:#94a3b8;margin-left:.5rem" id="sel-count">0 seleccionadas</span>
      </div>

      <?php foreach ($por_grupo as $grupo => $files): ?>
      <div class="imp-group">
        <div class="imp-group-label">📁 <?php echo htmlspecialchars($grupo); ?></div>
        <?php foreach ($files as $f): ?>
          <?php $registrada = isset($ya_registradas[$f]); ?>
          <div class="imp-row">
            <input type="checkbox" class="imp-check chk-archivo"
              name="archivos[]" value="<?php echo htmlspecialchars($f); ?>"
              id="chk-<?php echo md5($f); ?>"
              <?php echo $registrada ? 'disabled' : ''; ?>
              <?php echo !$registrada ? 'checked' : ''; ?>
              onchange="actualizarContador()">
            <label for="chk-<?php echo md5($f); ?>" class="imp-path"><?php echo htmlspecialchars($f); ?></label>
            <?php if ($registrada): ?>
              <span class="imp-badge-ya">Ya importada</span>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
      <?php endforeach; ?>
    </div>

    <div style="display:flex;gap:1rem;align-items:center">
      <button type="submit" name="importar" class="btn-save">⬇️ Importar seleccionadas</button>
      <a href="paginas.php" class="btn-preview">Cancelar</a>
    </div>
  </form>

  <?php endif; ?>

</div>

<script>
function actualizarContador() {
  var n = document.querySelectorAll('.chk-archivo:checked:not(:disabled)').length;
  document.getElementById('sel-count').textContent = n + ' seleccionada' + (n !== 1 ? 's' : '');
}
function toggleTodos(sel) {
  document.querySelectorAll('.chk-archivo:not(:disabled)').forEach(function(c) { c.checked = sel; });
  actualizarContador();
}
document.addEventListener('DOMContentLoaded', actualizarContador);
</script>
</body>
</html>
