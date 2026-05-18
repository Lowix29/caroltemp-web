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
  fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
  modificado DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$mensaje = '';

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
                  <a href="../<?php echo urlencode($pag['filepath']); ?>" target="_blank" class="btn-ver">Ver</a>
                  <a href="#" class="btn-eliminar" onclick="confirmarEliminar(<?php echo $pag['id']; ?>, '<?php echo htmlspecialchars(addslashes($pag['titulo'])); ?>')">Eliminar</a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

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
