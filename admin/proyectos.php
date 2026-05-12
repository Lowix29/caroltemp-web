<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php');
  exit;
}
require_once '../includes/db.php';

$mensaje = '';

// ELIMINAR
if (isset($_GET['eliminar']) && is_numeric($_GET['eliminar'])) {
  $stmt = $pdo->prepare('DELETE FROM proyectos WHERE id = ?');
  $stmt->execute([$_GET['eliminar']]);
  $mensaje = '✅ Proyecto eliminado correctamente.';
}

// TOGGLE PUBLICADO
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
  $stmt = $pdo->prepare('UPDATE proyectos SET publicado = NOT publicado WHERE id = ?');
  $stmt->execute([$_GET['toggle']]);
  header('Location: proyectos.php');
  exit;
}

// FILTROS
$zona_filtro = $_GET['zona']     ?? '';
$serv_filtro = $_GET['servicio'] ?? '';
$bus_filtro  = $_GET['q']        ?? '';

$where  = ['1=1'];
$params = [];

if ($zona_filtro) { $where[] = 'zona = ?';     $params[] = $zona_filtro; }
if ($serv_filtro) { $where[] = 'servicio = ?'; $params[] = $serv_filtro; }
if ($bus_filtro)  { $where[] = 'titulo LIKE ?'; $params[] = '%' . $bus_filtro . '%'; }

$sql  = 'SELECT id, titulo, slug, zona, servicio, publicado, fecha FROM proyectos';
$sql .= ' WHERE ' . implode(' AND ', $where);
$sql .= ' ORDER BY fecha DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$proyectos = $stmt->fetchAll();

$zonas     = $pdo->query('SELECT DISTINCT zona FROM proyectos WHERE zona != "" ORDER BY zona')->fetchAll(PDO::FETCH_COLUMN);
$servicios = $pdo->query('SELECT DISTINCT servicio FROM proyectos WHERE servicio != "" ORDER BY servicio')->fetchAll(PDO::FETCH_COLUMN);
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Proyectos — Hidrofont Admin</title>
  <meta name="robots" content="noindex, nofollow">
  <?php include '../includes/admin_style.php'; ?>
</head>
<body>

<?php include '../includes/admin_sidebar.php'; ?>

<main class="main">

  <div class="topbar">
    <h1>Proyectos</h1>
    <a href="nuevo-proyecto.php" class="btn-new">+ Nuevo proyecto</a>
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
      <div class="filtro-group">
        <label>Zona</label>
        <select name="zona">
          <option value="">Todas</option>
          <?php foreach ($zonas as $z): ?>
            <option value="<?php echo $z; ?>" <?php echo $zona_filtro === $z ? 'selected' : ''; ?>><?php echo $z; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="filtro-group">
        <label>Servicio</label>
        <select name="servicio">
          <option value="">Todos</option>
          <?php foreach ($servicios as $s): ?>
            <option value="<?php echo $s; ?>" <?php echo $serv_filtro === $s ? 'selected' : ''; ?>><?php echo $s; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn-filtrar">Filtrar</button>
      <a href="proyectos.php" class="btn-limpiar">Limpiar</a>
    </div>
  </form>

  <!-- TABLA -->
  <div class="card">
    <div class="card-header">
      <h2>Todos los proyectos</h2>
      <span style="color:#7a95b0;font-size:13px"><?php echo count($proyectos); ?> resultado<?php echo count($proyectos) !== 1 ? 's' : ''; ?></span>
    </div>
    <table>
      <thead>
        <tr>
          <th>Título</th>
          <th>Zona</th>
          <th>Servicio</th>
          <th>Estado</th>
          <th>Fecha</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($proyectos)): ?>
          <tr class="empty-row"><td colspan="6">No hay proyectos que coincidan</td></tr>
        <?php else: ?>
          <?php foreach ($proyectos as $pro): ?>
            <tr>
              <td class="td-titulo">
                <?php echo htmlspecialchars($pro['titulo']); ?>
                <small><?php echo htmlspecialchars($pro['slug']); ?></small>
              </td>
              <td>
                <?php if ($pro['zona']): ?>
                  <span class="badge-zona"><?php echo htmlspecialchars($pro['zona']); ?></span>
                <?php else: ?>
                  <span style="color:#ccc">—</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($pro['servicio']): ?>
                  <span class="badge-cat"><?php echo htmlspecialchars($pro['servicio']); ?></span>
                <?php else: ?>
                  <span style="color:#ccc">—</span>
                <?php endif; ?>
              </td>
              <td>
                <a href="?toggle=<?php echo $pro['id']; ?>&q=<?php echo urlencode($bus_filtro); ?>&zona=<?php echo urlencode($zona_filtro); ?>&servicio=<?php echo urlencode($serv_filtro); ?>"
                   class="badge-pub <?php echo $pro['publicado'] ? 'si' : 'no'; ?>"
                   title="Clic para cambiar estado">
                  <?php echo $pro['publicado'] ? '✓ Publicado' : '✗ Borrador'; ?>
                </a>
              </td>
              <td style="white-space:nowrap;color:#7a95b0;font-size:12.5px">
                <?php echo date('d/m/Y', strtotime($pro['fecha'])); ?>
              </td>
              <td>
                <div class="td-acciones">
                  <a href="nuevo-proyecto.php?id=<?php echo $pro['id']; ?>" class="btn-editar">Editar</a>
                  <a href="../blog/<?php echo urlencode($art['slug']); ?>" target="_blank" class="btn-ver">Ver</a>
                  <a href="#" class="btn-eliminar" onclick="confirmarEliminar(<?php echo $pro['id']; ?>, '<?php echo htmlspecialchars(addslashes($pro['titulo'])); ?>')">Eliminar</a>
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
    <h3>¿Eliminar proyecto?</h3>
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
  document.getElementById('confirm-link').href = 'proyectos.php?eliminar=' + id;
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