<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php');
  exit;
}
require_once '../includes/db.php';

// Estadísticas generales
$total_articulos = $pdo->query('SELECT COUNT(*) FROM articulos')->fetchColumn();
$total_proyectos = $pdo->query('SELECT COUNT(*) FROM proyectos')->fetchColumn();
$pub_articulos   = $pdo->query('SELECT COUNT(*) FROM articulos WHERE publicado = 1')->fetchColumn();
$pub_proyectos   = $pdo->query('SELECT COUNT(*) FROM proyectos WHERE publicado = 1')->fetchColumn();

// Estadísticas por zona
$arts_por_zona = $pdo->query('SELECT zona, COUNT(*) as total FROM articulos WHERE publicado=1 AND zona != "" GROUP BY zona ORDER BY total DESC')->fetchAll();
$pros_por_zona = $pdo->query('SELECT zona, COUNT(*) as total FROM proyectos WHERE publicado=1 AND zona != "" GROUP BY zona ORDER BY total DESC')->fetchAll();

// Estadísticas por categoría
$arts_por_cat = $pdo->query('SELECT categoria, COUNT(*) as total FROM articulos WHERE publicado=1 AND categoria != "" GROUP BY categoria ORDER BY total DESC')->fetchAll();

// Estadísticas por servicio
$pros_por_serv = $pdo->query('SELECT servicio, COUNT(*) as total FROM proyectos WHERE publicado=1 AND servicio != "" GROUP BY servicio ORDER BY total DESC')->fetchAll();

// Últimos artículos
$ultimos_articulos = $pdo->query('SELECT id, titulo, slug, zona, publicado, fecha FROM articulos ORDER BY fecha DESC LIMIT 5')->fetchAll();

// Últimos proyectos
$ultimos_proyectos = $pdo->query('SELECT id, titulo, slug, zona, servicio, publicado, fecha FROM proyectos ORDER BY fecha DESC LIMIT 5')->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Admin — Hidrofont</title>
  <meta name="robots" content="noindex, nofollow">
  <?php include '../includes/admin_style.php'; ?>
  <style>
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
    .stat-card { background: #fff; border: 1px solid #dde6f0; border-radius: 10px; padding: 1.25rem 1.5rem; display: flex; flex-direction: column; gap: 6px; }
    .stat-card-label { color: #7a95b0; font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.06em; }
    .stat-card-val { color: #1e3a5f; font-size: 28px; font-weight: 700; line-height: 1; }
    .stat-card-sub { color: #a0b5c8; font-size: 12px; }
    .tables-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }
    .stats-row { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1.5rem; }
    .mini-stat { display: flex; justify-content: space-between; align-items: center; padding: 0.65rem 0; border-bottom: 1px solid #eaeff4; }
    .mini-stat:last-child { border-bottom: none; }
    .mini-stat-label { color: #3a4a5c; font-size: 13.5px; }
    .mini-stat-val { background: #e8f0fa; color: #1e3a5f; font-size: 12px; font-weight: 600; padding: 3px 10px; border-radius: 20px; }
    .empty-stats { color: #a0b5c8; font-size: 13.5px; padding: 1rem 0; text-align: center; }
  </style>
</head>
<body>

<?php include '../includes/admin_sidebar.php'; ?>

<main class="main">

  <div class="topbar">
    <h1>Panel de administración</h1>
    <span style="color:#7a95b0;font-size:13.5px">Hola, <strong style="color:#1e3a5f"><?php echo htmlspecialchars($_SESSION['admin_usuario']); ?></strong></span>
  </div>

  <!-- STATS PRINCIPALES -->
  <div class="stats-grid">
    <div class="stat-card">
      <span class="stat-card-label">Artículos totales</span>
      <span class="stat-card-val"><?php echo $total_articulos; ?></span>
      <span class="stat-card-sub"><?php echo $pub_articulos; ?> publicados · <?php echo $total_articulos - $pub_articulos; ?> borradores</span>
    </div>
    <div class="stat-card">
      <span class="stat-card-label">Proyectos totales</span>
      <span class="stat-card-val"><?php echo $total_proyectos; ?></span>
      <span class="stat-card-sub"><?php echo $pub_proyectos; ?> publicados · <?php echo $total_proyectos - $pub_proyectos; ?> borradores</span>
    </div>
    <div class="stat-card">
      <span class="stat-card-label">Zonas cubiertas</span>
      <span class="stat-card-val">8</span>
      <span class="stat-card-sub">Vinalopó Medio y pedanías</span>
    </div>
    <div class="stat-card">
      <span class="stat-card-label">Servicios</span>
      <span class="stat-card-val">6</span>
      <span class="stat-card-sub">Fontanería residencial</span>
    </div>
  </div>

  <!-- ÚLTIMOS ARTÍCULOS Y PROYECTOS -->
  <div class="tables-grid">

    <div class="card">
      <div class="card-header">
        <h2>Últimos artículos</h2>
        <a href="nuevo-articulo.php" class="btn-new">+ Nuevo</a>
      </div>
      <table>
        <thead>
          <tr>
            <th>Título</th>
            <th>Estado</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($ultimos_articulos)): ?>
            <tr class="empty-row"><td colspan="3">No hay artículos todavía</td></tr>
          <?php else: ?>
            <?php foreach ($ultimos_articulos as $a): ?>
              <tr>
                <td class="td-titulo">
                  <?php echo htmlspecialchars($a['titulo']); ?>
                  <?php if ($a['zona']): ?><small>📍 <?php echo $a['zona']; ?></small><?php endif; ?>
                </td>
                <td><span class="badge-pub <?php echo $a['publicado'] ? 'si' : 'no'; ?>"><?php echo $a['publicado'] ? 'Publicado' : 'Borrador'; ?></span></td>
                <td><div class="td-acciones"><a href="nuevo-articulo.php?id=<?php echo $a['id']; ?>" class="btn-editar">Editar</a></div></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
      <div style="padding:0.75rem 1.25rem;border-top:1px solid #eaeff4">
        <a href="articulos.php" style="color:#1e3a5f;font-size:13px;font-weight:500">Ver todos los artículos →</a>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h2>Últimos proyectos</h2>
        <a href="nuevo-proyecto.php" class="btn-new">+ Nuevo</a>
      </div>
      <table>
        <thead>
          <tr>
            <th>Título</th>
            <th>Estado</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($ultimos_proyectos)): ?>
            <tr class="empty-row"><td colspan="3">No hay proyectos todavía</td></tr>
          <?php else: ?>
            <?php foreach ($ultimos_proyectos as $p): ?>
              <tr>
                <td class="td-titulo">
                  <?php echo htmlspecialchars($p['titulo']); ?>
                  <?php if ($p['zona']): ?><small>📍 <?php echo $p['zona']; ?></small><?php endif; ?>
                </td>
                <td><span class="badge-pub <?php echo $p['publicado'] ? 'si' : 'no'; ?>"><?php echo $p['publicado'] ? 'Publicado' : 'Borrador'; ?></span></td>
                <td><div class="td-acciones"><a href="nuevo-proyecto.php?id=<?php echo $p['id']; ?>" class="btn-editar">Editar</a></div></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
      <div style="padding:0.75rem 1.25rem;border-top:1px solid #eaeff4">
        <a href="proyectos.php" style="color:#1e3a5f;font-size:13px;font-weight:500">Ver todos los proyectos →</a>
      </div>
    </div>

  </div>

  <!-- ESTADÍSTICAS DETALLADAS -->
  <div class="stats-row">

    <div class="card">
      <div class="card-header"><h2>Artículos por zona</h2></div>
      <div class="card-body">
        <?php if (empty($arts_por_zona)): ?>
          <p class="empty-stats">Sin datos todavía</p>
        <?php else: ?>
          <?php foreach ($arts_por_zona as $row): ?>
            <div class="mini-stat">
              <span class="mini-stat-label"><?php echo htmlspecialchars($row['zona']); ?></span>
              <span class="mini-stat-val"><?php echo $row['total']; ?></span>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><h2>Artículos por categoría</h2></div>
      <div class="card-body">
        <?php if (empty($arts_por_cat)): ?>
          <p class="empty-stats">Sin datos todavía</p>
        <?php else: ?>
          <?php foreach ($arts_por_cat as $row): ?>
            <div class="mini-stat">
              <span class="mini-stat-label"><?php echo htmlspecialchars($row['categoria']); ?></span>
              <span class="mini-stat-val"><?php echo $row['total']; ?></span>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><h2>Proyectos por zona</h2></div>
      <div class="card-body">
        <?php if (empty($pros_por_zona)): ?>
          <p class="empty-stats">Sin datos todavía</p>
        <?php else: ?>
          <?php foreach ($pros_por_zona as $row): ?>
            <div class="mini-stat">
              <span class="mini-stat-label"><?php echo htmlspecialchars($row['zona']); ?></span>
              <span class="mini-stat-val"><?php echo $row['total']; ?></span>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><h2>Proyectos por servicio</h2></div>
      <div class="card-body">
        <?php if (empty($pros_por_serv)): ?>
          <p class="empty-stats">Sin datos todavía</p>
        <?php else: ?>
          <?php foreach ($pros_por_serv as $row): ?>
            <div class="mini-stat">
              <span class="mini-stat-label"><?php echo htmlspecialchars($row['servicio']); ?></span>
              <span class="mini-stat-val"><?php echo $row['total']; ?></span>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

  </div>

</main>

</body>
</html>