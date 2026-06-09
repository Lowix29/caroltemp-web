<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php');
  exit;
}
require_once '../includes/db.php';

// Crear tabla si no existe (con robots incluido)
$pdo->exec("CREATE TABLE IF NOT EXISTS paginas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  filepath VARCHAR(255) NOT NULL UNIQUE,
  contenido LONGTEXT,
  meta_title VARCHAR(255) DEFAULT '',
  meta_desc TEXT DEFAULT '',
  robots VARCHAR(20) DEFAULT 'index',
  publicado TINYINT(1) DEFAULT 1,
  fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
  modificado DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Añadir columna robots si falta (compatible MySQL 5.7 — ignorar error si ya existe)
try { $pdo->exec("ALTER TABLE paginas ADD COLUMN robots VARCHAR(20) DEFAULT 'index'"); } catch (PDOException $e) {}

$mensaje = '';
$error   = '';

// IMPORTAR página del disco — guarda en BD y redirige al editor
if (isset($_GET['importar'])) {
  // El .htaccess quita .php de la query string, así que aceptamos con y sin extensión
  $fn = basename($_GET['importar']);
  if (substr($fn, -4) !== '.php') $fn .= '.php';
  if (!preg_match('/^[a-z0-9_\-]+\.php$/', $fn)) {
    $error = 'Nombre de archivo no válido: ' . htmlspecialchars($fn);
  } else {
    // Si ya está en BD, ir directo al editor
    $chk = $pdo->prepare('SELECT id FROM paginas WHERE filepath = ? LIMIT 1');
    $chk->execute([$fn]);
    $existente = $chk->fetchColumn();
    if ($existente) {
      header('Location: nueva-pagina.php?id=' . $existente);
      exit;
    }
    // Extraer contenido HTML del archivo (entre primer cierre PHP y ultimo include)
    $contenido = '';
    $abs_imp   = dirname(__DIR__) . DIRECTORY_SEPARATOR . $fn;
    if (file_exists($abs_imp)) {
      $raw     = file_get_contents($abs_imp);
      // Buscar primer cierre de bloque PHP
      $php_end = strpos($raw, '?>');
      if ($php_end !== false) {
        $html_part  = ltrim(substr($raw, $php_end + 2));
        $footer_pos = strrpos($html_part, '<?php');
        if ($footer_pos !== false) {
          $html_part = rtrim(substr($html_part, 0, $footer_pos));
        }
        if (substr_count($html_part, '<?php') <= 4) {
          $contenido = $html_part;
        }
      }
    }
    $slug   = str_replace('.php', '', $fn);
    $titulo = ucwords(str_replace(['-', '_'], ' ', $slug));
    try {
      $ins = $pdo->prepare('INSERT INTO paginas (titulo, slug, filepath, contenido, publicado) VALUES (?, ?, ?, ?, 1)');
      $ins->execute([$titulo, $slug, $fn, $contenido]);
      $newId = (int)$pdo->lastInsertId();
      if ($newId > 0) {
        header('Location: nueva-pagina.php?id=' . $newId . '&importado=1');
        exit;
      } else {
        $error = 'El INSERT no devolvió un ID válido.';
      }
    } catch (PDOException $e) {
      $error = 'Error al importar "' . htmlspecialchars($fn) . '": ' . htmlspecialchars($e->getMessage());
    }
  }
}

// ELIMINAR
if (isset($_GET['eliminar']) && is_numeric($_GET['eliminar'])) {
  // Obtener filepath antes de borrar para poder eliminar el archivo
  $sel = $pdo->prepare('SELECT filepath FROM paginas WHERE id = ?');
  $sel->execute([$_GET['eliminar']]);
  $fp = $sel->fetchColumn();

  $stmt = $pdo->prepare('DELETE FROM paginas WHERE id = ?');
  $stmt->execute([$_GET['eliminar']]);

  // Eliminar archivo en disco si existe y está dentro del directorio del sitio
  if ($fp) {
    $site_root = dirname(__DIR__);
    $abs = $site_root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, ltrim($fp, '/'));
    $real_root = realpath($site_root);
    $real_abs  = realpath($abs);
    if ($real_abs && $real_root && strpos($real_abs, $real_root . DIRECTORY_SEPARATOR) === 0) {
      if (file_exists($abs)) {
        copy($abs, $abs . '.bak'); // backup antes de borrar
        unlink($abs);
      }
    }
  }

  $mensaje = '✅ Página eliminada del listado y del disco.';
}

// TOGGLE PUBLICADO
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
  $stmt = $pdo->prepare('UPDATE paginas SET publicado = NOT publicado WHERE id = ?');
  $stmt->execute([$_GET['toggle']]);
  header('Location: paginas.php');
  exit;
}

// FILTROS
$bus_filtro = $_GET['q'] ?? '';

$where  = ['1=1'];
$params = [];

if ($bus_filtro) { $where[] = 'titulo LIKE ?'; $params[] = '%' . $bus_filtro . '%'; }

$sql  = 'SELECT id, titulo, slug, filepath, publicado, modificado FROM paginas';
$sql .= ' WHERE ' . implode(' AND ', $where);
$sql .= ' ORDER BY modificado DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$paginas = $stmt->fetchAll();

// Escaneo recursivo de todas las páginas en disco
$site_root_scan = dirname(__DIR__);
$ignorar_archivos = ['404.php','sitemap.php','index.php','login.php','logout.php','cambiar-password.php','robots.php','cambiar-password.php'];
$ignorar_carpetas = ['admin','includes','noticias','proyectos','blog'];

$db_filepaths = array_column($paginas, 'filepath');

// Escanear disco recursivamente
$todos_disco = [];
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($site_root_scan, FilesystemIterator::SKIP_DOTS));
foreach ($it as $fileinfo) {
  if (!$fileinfo->isFile() || $fileinfo->getExtension() !== 'php') continue;
  $rel = str_replace($site_root_scan . DIRECTORY_SEPARATOR, '', $fileinfo->getPathname());
  $rel = str_replace('\\', '/', $rel);
  $partes = explode('/', $rel);
  $skip = false;
  foreach ($ignorar_carpetas as $d) { if (in_array($d, $partes)) { $skip = true; break; } }
  if ($skip) continue;
  if (in_array(basename($rel), $ignorar_archivos)) continue;
  if ($rel === 'index.php') continue;
  $todos_disco[] = $rel;
}
sort($todos_disco);

// Páginas en disco que NO están en BD
$no_registradas = array_filter($todos_disco, function($f) use ($db_filepaths) {
  return !in_array($f, $db_filepaths);
});
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Páginas — CarolTemp Admin</title>
  <meta name="robots" content="noindex, nofollow">
  <?php include '../includes/admin_style.php'; ?>
</head>
<body>

<?php include '../includes/admin_sidebar.php'; ?>

<main class="main">

  <div class="topbar">
    <h1>Páginas <span style="font-size:15px;font-weight:400;color:#64748b">(<?php echo count($paginas); ?> en BD · <?php echo count($todos_disco); ?> en disco)</span></h1>
    <div style="display:flex;gap:.75rem">
      <?php if (!empty($no_registradas)): ?>
        <a href="paginas-importar.php" style="background:#f97316;color:#fff;padding:9px 18px;border-radius:7px;font-size:13.5px;font-weight:600;text-decoration:none;display:flex;align-items:center;gap:6px">
          ⚠️ <?php echo count($no_registradas); ?> sin importar
        </a>
      <?php endif; ?>
      <a href="nueva-pagina.php" class="btn-new">+ Nueva página</a>
    </div>
  </div>

  <?php if ($mensaje): ?>
    <div class="mensaje"><?php echo $mensaje; ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="error-msg" style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:.875rem 1.25rem;border-radius:8px;margin-bottom:1rem;font-size:14px"><?php echo $error; ?></div>
  <?php endif; ?>

  <!-- FILTROS -->
  <form method="GET" action="">
    <div class="filtros">
      <div class="filtro-group">
        <label>Buscar</label>
        <input type="text" name="q" value="<?php echo htmlspecialchars($bus_filtro); ?>" placeholder="Título...">
      </div>
      <button type="submit" class="btn-filtrar">Filtrar</button>
      <a href="paginas.php" class="btn-limpiar">Limpiar</a>
    </div>
  </form>

  <!-- TABLA -->
  <div class="card">
    <div class="card-header">
      <h2>Todas las páginas</h2>
      <span style="color:#7a95b0;font-size:13px"><?php echo count($paginas); ?> resultado<?php echo count($paginas) !== 1 ? 's' : ''; ?></span>
    </div>
    <table>
      <thead>
        <tr>
          <th>Título</th>
          <th>Filepath</th>
          <th>Estado</th>
          <th>Modificado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($paginas)): ?>
          <tr class="empty-row"><td colspan="5">No hay páginas que coincidan</td></tr>
        <?php else: ?>
          <?php foreach ($paginas as $pag): ?>
            <tr>
              <td class="td-titulo">
                <?php echo htmlspecialchars($pag['titulo']); ?>
                <small><?php echo htmlspecialchars($pag['slug']); ?></small>
              </td>
              <td>
                <code style="font-family:monospace;font-size:12px;color:#1e3a5f;background:#f4f7fb;padding:2px 6px;border-radius:4px"><?php echo htmlspecialchars($pag['filepath']); ?></code>
              </td>
              <td>
                <a href="?toggle=<?php echo $pag['id']; ?>&q=<?php echo urlencode($bus_filtro); ?>"
                   class="badge-pub <?php echo $pag['publicado'] ? 'si' : 'no'; ?>"
                   title="Clic para cambiar estado">
                  <?php echo $pag['publicado'] ? '✓ Publicado' : '✗ Borrador'; ?>
                </a>
              </td>
              <td style="white-space:nowrap;color:#7a95b0;font-size:12.5px">
                <?php echo date('d/m/Y H:i', strtotime($pag['modificado'])); ?>
              </td>
              <td>
                <div class="td-acciones">
                  <a href="nueva-pagina.php?id=<?php echo $pag['id']; ?>" class="btn-editar">Editar</a>
                  <a href="../<?php echo htmlspecialchars($pag['filepath']); ?>" target="_blank" class="btn-ver">Ver</a>
                  <a href="#" class="btn-eliminar" onclick="confirmarEliminar(<?php echo $pag['id']; ?>, '<?php echo htmlspecialchars(addslashes($pag['titulo'])); ?>')">Eliminar</a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <?php if (!empty($no_registradas)): ?>
  <div class="card" style="margin-top:1.5rem;border-color:#fed7aa">
    <div class="card-header" style="background:#fff7ed">
      <h2 style="color:#ea580c">⚠️ <?php echo count($no_registradas); ?> páginas en disco sin importar</h2>
      <a href="paginas-importar.php" class="btn-new" style="background:#ea580c!important">Importar todas →</a>
    </div>
    <div style="padding:1rem 1.5rem">
      <p style="font-size:13px;color:#576574;margin-bottom:1rem">Estos archivos .php existen en disco pero no están registrados en la base de datos. Sin importarlos no aparecen en el sitemap ni los puedes editar desde el panel.</p>
      <div style="display:flex;flex-wrap:wrap;gap:.5rem">
        <?php foreach ($no_registradas as $f): ?>
          <span style="background:#f4f7fb;border:1px solid #dde6f0;border-radius:5px;padding:3px 10px;font-family:monospace;font-size:12px;color:#1e3a5f"><?php echo htmlspecialchars($f); ?></span>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>

</main>

<!-- CONFIRMAR ELIMINAR -->
<div class="confirm-overlay" id="confirm-overlay">
  <div class="confirm-box">
    <h3>¿Eliminar página?</h3>
    <p id="confirm-texto">Esta acción no se puede deshacer.</p>
    <div class="confirm-btns">
      <a href="#" class="btn-cancelar" onclick="cerrarConfirm()">Cancelar</a>
      <a href="#" class="btn-confirmar" id="confirm-link">Eliminar</a>
    </div>
  </div>
</div>

<script>
function confirmarEliminar(id, titulo) {
  document.getElementById('confirm-texto').textContent = '¿Seguro que quieres eliminar "' + titulo + '"? Esta acción no se puede deshacer.';
  document.getElementById('confirm-link').href = 'paginas.php?eliminar=' + id;
  document.getElementById('confirm-overlay').classList.add('open');
}
function cerrarConfirm() {
  document.getElementById('confirm-overlay').classList.remove('open');
  return false;
}
document.getElementById('confirm-overlay').addEventListener('click', function(e) {
  if (e.target === this) cerrarConfirm();
});
</script>

</body>
</html>
