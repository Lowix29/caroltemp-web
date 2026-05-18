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
  $stmt = $pdo->prepare('DELETE FROM paginas WHERE id = ?');
  $stmt->execute([$_GET['eliminar']]);
  $mensaje = '✅ Página eliminada correctamente.';
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

// Auto-importar páginas raíz creadas por el agente (tienen $meta_title definido)
$site_root_scan = dirname(__DIR__);
$system_files   = ['404.php','sitemap.php','index.php','login.php','logout.php','cambiar-password.php'];
$root_phps_all  = glob($site_root_scan . DIRECTORY_SEPARATOR . '*.php') ?: [];
$db_filepaths   = array_column($paginas, 'filepath');

foreach ($root_phps_all as $f) {
  $fn = basename($f);
  if (in_array($fn, $system_files, true)) continue;
  if (!preg_match('/^[a-z0-9_\-]+\.php$/', $fn)) continue;
  if (in_array($fn, $db_filepaths, true)) continue; // ya está en BD
  // Sólo auto-importar si tiene $meta_title (página de contenido real)
  $raw_check = @file_get_contents($f);
  if (!$raw_check || strpos($raw_check, '$meta_title') === false) continue;
  // Extraer datos
  $slug_ai  = basename($fn, '.php');
  $title_ai = ucwords(str_replace(['-','_'], ' ', $slug_ai));
  if (preg_match('/\$meta_title\s*=\s*[\'"](.+?)[\'"]\s*;/', $raw_check, $mm)) {
    $title_ai = stripslashes($mm[1]) ?: $title_ai;
  }
  $html_ai = '';
  $pe = strpos($raw_check, '?>');
  if ($pe !== false) {
    $hp = ltrim(substr($raw_check, $pe + 2));
    $fp = strrpos($hp, '<?php');
    if ($fp !== false) $hp = rtrim(substr($hp, 0, $fp));
    if (substr_count($hp, '<?php') <= 4) $html_ai = $hp;
  }
  try {
    $ai = $pdo->prepare('INSERT INTO paginas (titulo, slug, filepath, contenido, publicado) VALUES (?, ?, ?, ?, 1)');
    $ai->execute([$title_ai, $slug_ai, $fn, $html_ai]);
    $db_filepaths[] = $fn; // evitar duplicados en esta misma carga
  } catch (PDOException $e) { /* slug/filepath duplicado — ignorar */ }
}

// Re-leer la lista de páginas actualizada
$stmt = $pdo->prepare('SELECT id, titulo, slug, filepath, publicado, modificado FROM paginas WHERE ' . implode(' AND ', $where) . ' ORDER BY modificado DESC');
$stmt->execute($params);
$paginas = $stmt->fetchAll();
$db_filepaths = array_column($paginas, 'filepath');

// Páginas en disco que aún no están en BD (no tienen $meta_title = archivos del sistema)
$paginas_disco = [];
foreach ($root_phps_all as $f) {
  $fn = basename($f);
  if (!in_array($fn, $db_filepaths, true) && !in_array($fn, $system_files, true)) {
    $paginas_disco[] = $fn;
  }
}
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
    <h1>Páginas</h1>
    <a href="nueva-pagina.php" class="btn-new">+ Nueva página</a>
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

  <?php if (!empty($paginas_disco)): ?>
  <div class="card" style="margin-top:1.5rem">
    <div class="card-header">
      <h2>Páginas en disco sin registrar en BD</h2>
      <span style="color:#7a95b0;font-size:13px"><?php echo count($paginas_disco); ?> archivo<?php echo count($paginas_disco) !== 1 ? 's' : ''; ?> encontrado<?php echo count($paginas_disco) !== 1 ? 's' : ''; ?></span>
    </div>
    <div style="padding:1rem 1.5rem">
      <p style="font-size:13px;color:#576574;margin-bottom:1rem">Estos archivos .php existen en la raíz del sitio pero no están en la base de datos. Puedes importarlos para gestionarlos desde el panel.</p>
      <div style="display:flex;flex-wrap:wrap;gap:.75rem">
        <?php foreach ($paginas_disco as $fn): ?>
          <div style="display:flex;align-items:center;gap:.5rem;background:#f4f7fb;border:1px solid #dde6f0;border-radius:6px;padding:.5rem 1rem">
            <code style="font-family:monospace;font-size:12px;color:#1e3a5f"><?php echo htmlspecialchars($fn); ?></code>
            <a href="paginas.php?importar=<?php echo urlencode(basename($fn, '.php')); ?>" style="background:#1e3a5f;color:#fff;font-size:12px;padding:3px 10px;border-radius:4px;text-decoration:none;font-weight:600">Importar</a>
          </div>
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
