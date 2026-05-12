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
  $stmt = $pdo->prepare('DELETE FROM articulos WHERE id = ?');
  $stmt->execute([$_GET['eliminar']]);
  $mensaje = '✅ Artículo eliminado correctamente.';
}

// TOGGLE PUBLICADO
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
  $stmt = $pdo->prepare('UPDATE articulos SET publicado = NOT publicado WHERE id = ?');
  $stmt->execute([$_GET['toggle']]);
  header('Location: articulos.php');
  exit;
}

// FILTROS
$zona_filtro = $_GET['zona'] ?? '';
$cat_filtro  = $_GET['cat']  ?? '';
$bus_filtro  = $_GET['q']    ?? '';

$where  = ['1=1'];
$params = [];

if ($zona_filtro) { $where[] = 'zona = ?';      $params[] = $zona_filtro; }
if ($cat_filtro)  { $where[] = 'categoria = ?'; $params[] = $cat_filtro; }
if ($bus_filtro)  { $where[] = 'titulo LIKE ?'; $params[] = '%' . $bus_filtro . '%'; }

$sql  = 'SELECT id, titulo, slug, zona, categoria, publicado, fecha FROM articulos';
$sql .= ' WHERE ' . implode(' AND ', $where);
$sql .= ' ORDER BY fecha DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$articulos = $stmt->fetchAll();

$zonas      = $pdo->query('SELECT DISTINCT zona FROM articulos WHERE zona != "" ORDER BY zona')->fetchAll(PDO::FETCH_COLUMN);
$categorias = $pdo->query('SELECT DISTINCT categoria FROM articulos WHERE categoria != "" ORDER BY categoria')->fetchAll(PDO::FETCH_COLUMN);
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Artículos — Hidrofont Admin</title>
  <meta name="robots" content="noindex, nofollow">
  <?php include '../includes/admin_style.php'; ?>
</head>
<body>

<?php include '../includes/admin_sidebar.php'; ?>

<main class="main">

  <div class="topbar">
    <h1>Artículos</h1>
    <a href="nuevo-articulo.php" class="btn-new">+ Nuevo artículo</a>
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
        <label>Categoría</label>
        <select name="cat">
          <option value="">Todas</option>
          <?php foreach ($categorias as $c): ?>
            <option value="<?php echo $c; ?>" <?php echo $cat_filtro === $c ? 'selected' : ''; ?>><?php echo $c; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn-filtrar">Filtrar</button>
      <a href="articulos.php" class="btn-limpiar">Limpiar</a>
    </div>
  </form>

  <!-- TABLA -->
  <div class="card">
    <div class="card-header">
      <h2>Todos los artículos</h2>
      <span style="color:#7a95b0;font-size:13px"><?php echo count($articulos); ?> resultado<?php echo count($articulos) !== 1 ? 's' : ''; ?></span>
    </div>
    <table>
      <thead>
        <tr>
          <th>Título</th>
          <th>Zona</th>
          <th>Categoría</th>
          <th>Estado</th>
          <th>Fecha</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($articulos)): ?>
          <tr class="empty-row"><td colspan="6">No hay artículos que coincidan</td></tr>
        <?php else: ?>
          <?php foreach ($articulos as $art): ?>
            <tr>
              <td class="td-titulo">
                <?php echo htmlspecialchars($art['titulo']); ?>
                <small><?php echo htmlspecialchars($art['slug']); ?></small>
              </td>
              <td>
                <?php if ($art['zona']): ?>
                  <span class="badge-zona"><?php echo htmlspecialchars($art['zona']); ?></span>
                <?php else: ?>
                  <span style="color:#ccc">—</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($art['categoria']): ?>
                  <span class="badge-cat"><?php echo htmlspecialchars($art['categoria']); ?></span>
                <?php else: ?>
                  <span style="color:#ccc">—</span>
                <?php endif; ?>
              </td>
              <td>
                <a href="?toggle=<?php echo $art['id']; ?>&q=<?php echo urlencode($bus_filtro); ?>&zona=<?php echo urlencode($zona_filtro); ?>&cat=<?php echo urlencode($cat_filtro); ?>"
                   class="badge-pub <?php echo $art['publicado'] ? 'si' : 'no'; ?>"
                   title="Clic para cambiar estado">
                  <?php echo $art['publicado'] ? '✓ Publicado' : '✗ Borrador'; ?>
                </a>
              </td>
              <td style="white-space:nowrap;color:#7a95b0;font-size:12.5px">
                <?php echo date('d/m/Y', strtotime($art['fecha'])); ?>
              </td>
              <td>
                <div class="td-acciones">
                  <a href="nuevo-articulo.php?id=<?php echo $art['id']; ?>" class="btn-editar">Editar</a>
                  <a href="../blog/<?php echo urlencode($art['slug']); ?>" target="_blank" class="btn-ver">Ver</a>
                  <a href="#" class="btn-eliminar" onclick="confirmarEliminar(<?php echo $art['id']; ?>, '<?php echo htmlspecialchars(addslashes($art['titulo'])); ?>')">Eliminar</a>
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
    <h3>¿Eliminar artículo?</h3>
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
  document.getElementById('confirm-link').href = 'articulos.php?eliminar=' + id;
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