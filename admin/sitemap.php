<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php'); exit;
}
require_once '../includes/db.php';

$mensaje = '';
$error   = '';

// Asegurar tabla redirecciones
try {
  $pdo->exec("CREATE TABLE IF NOT EXISTS redirecciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    origen VARCHAR(500) NOT NULL,
    destino VARCHAR(500) NOT NULL,
    tipo INT NOT NULL DEFAULT 301,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
} catch (PDOException $e) {}

// BORRAR TODAS LAS REDIRECCIONES
if (isset($_POST['borrar_todas'])) {
  $pdo->exec('DELETE FROM redirecciones');
  $mensaje = '✅ Todas las redirecciones eliminadas.';
}

// ELIMINAR UNA REDIRECCIÓN
if (isset($_GET['eliminar_redireccion']) && is_numeric($_GET['eliminar_redireccion'])) {
  $pdo->prepare('DELETE FROM redirecciones WHERE id = ?')->execute([$_GET['eliminar_redireccion']]);
  $mensaje = '✅ Redirección eliminada.';
}

// CREAR REDIRECCIÓN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_redireccion'])) {
  $origen  = trim($_POST['origen']);
  $destino = trim($_POST['destino']);
  $tipo    = (int)$_POST['tipo'];
  if ($origen !== '/' && substr($origen, -1) === '/') $origen = rtrim($origen, '/');
  if ($destino !== '/' && substr($destino, -1) === '/') $destino = rtrim($destino, '/');
  if (substr($origen, 0, 1) !== '/') $origen = '/' . $origen;
  if (substr($destino, 0, 1) !== '/') $destino = '/' . $destino;
  $pdo->prepare('INSERT INTO redirecciones (origen, destino, tipo) VALUES (?, ?, ?)')->execute([$origen, $destino, $tipo]);
  $mensaje = '✅ Redirección creada correctamente.';
}

// GENERAR SITEMAP
if (isset($_POST['generar'])) {
  $base = 'https://caroltemp.com';
  $urls = [];

  // 1. Home siempre
  $urls[] = ['loc' => $base . '/', 'priority' => '1.0', 'changefreq' => 'weekly'];

  // 2. Páginas de la tabla paginas (publicadas)
  try {
    $pags = $pdo->query("SELECT filepath, modificado FROM paginas WHERE publicado = 1 ORDER BY id ASC")->fetchAll();
    foreach ($pags as $p) {
      $url_path = '/' . preg_replace('/\.php$/', '', $p['filepath']);
      $lastmod  = $p['modificado'] ? date('Y-m-d', strtotime($p['modificado'])) : date('Y-m-d');
      // Determinar prioridad por profundidad de ruta
      $depth    = substr_count(trim($url_path, '/'), '/');
      $priority = $depth === 0 ? '0.9' : ($depth === 1 ? '0.8' : '0.7');
      $urls[] = [
        'loc'        => $base . $url_path,
        'lastmod'    => $lastmod,
        'priority'   => $priority,
        'changefreq' => 'monthly',
      ];
    }
  } catch (PDOException $e) {}

  // 3. Índice noticias y proyectos
  $urls[] = ['loc' => $base . '/noticias',  'priority' => '0.8', 'changefreq' => 'weekly'];
  $urls[] = ['loc' => $base . '/proyectos', 'priority' => '0.8', 'changefreq' => 'weekly'];

  // 4. Artículos publicados
  try {
    $arts = $pdo->query("SELECT slug, modificado FROM articulos WHERE publicado = 1 ORDER BY fecha DESC")->fetchAll();
    foreach ($arts as $a) {
      $lastmod = !empty($a['modificado']) ? date('Y-m-d', strtotime($a['modificado'])) : date('Y-m-d');
      $urls[] = ['loc' => $base . '/noticias/' . $a['slug'], 'lastmod' => $lastmod, 'priority' => '0.7', 'changefreq' => 'monthly'];
    }
  } catch (PDOException $e) {
    // Si no existe columna modificado, intentar con fecha
    try {
      $arts = $pdo->query("SELECT slug, fecha FROM articulos WHERE publicado = 1 ORDER BY fecha DESC")->fetchAll();
      foreach ($arts as $a) {
        $urls[] = ['loc' => $base . '/noticias/' . $a['slug'], 'lastmod' => date('Y-m-d', strtotime($a['fecha'])), 'priority' => '0.7', 'changefreq' => 'monthly'];
      }
    } catch (PDOException $e2) {}
  }

  // 5. Proyectos publicados
  try {
    $pros = $pdo->query("SELECT slug, modificado FROM proyectos WHERE publicado = 1 ORDER BY fecha DESC")->fetchAll();
    foreach ($pros as $p) {
      $lastmod = !empty($p['modificado']) ? date('Y-m-d', strtotime($p['modificado'])) : date('Y-m-d');
      $urls[] = ['loc' => $base . '/proyectos/' . $p['slug'], 'lastmod' => $lastmod, 'priority' => '0.7', 'changefreq' => 'monthly'];
    }
  } catch (PDOException $e) {
    try {
      $pros = $pdo->query("SELECT slug, fecha FROM proyectos WHERE publicado = 1 ORDER BY fecha DESC")->fetchAll();
      foreach ($pros as $p) {
        $urls[] = ['loc' => $base . '/proyectos/' . $p['slug'], 'lastmod' => date('Y-m-d', strtotime($p['fecha'])), 'priority' => '0.7', 'changefreq' => 'monthly'];
      }
    } catch (PDOException $e2) {}
  }

  // Generar XML
  $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
  $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
  foreach ($urls as $url) {
    $xml .= '  <url>' . "\n";
    $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . "\n";
    if (!empty($url['lastmod'])) $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . "\n";
    $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
    $xml .= '    <priority>' . $url['priority'] . '</priority>' . "\n";
    $xml .= '  </url>' . "\n";
  }
  $xml .= '</urlset>';

  $ruta = dirname(__DIR__) . '/sitemap.xml';
  if (file_put_contents($ruta, $xml) !== false) {
    $mensaje = '✅ Sitemap generado con ' . count($urls) . ' URLs reales. Disponible en: https://caroltemp.com/sitemap.xml';
  } else {
    $error = '❌ Error al guardar sitemap.xml. Comprueba permisos de escritura.';
  }
}

// Datos para la vista
$sitemap_actual = '';
$sitemap_fecha  = '';
$ruta_sitemap   = dirname(__DIR__) . '/sitemap.xml';
if (file_exists($ruta_sitemap)) {
  $sitemap_actual = file_get_contents($ruta_sitemap);
  $sitemap_fecha  = date('d/m/Y H:i', filemtime($ruta_sitemap));
}

$total_pags = 0;
$total_arts = 0;
$total_pros = 0;
try { $total_pags = (int)$pdo->query('SELECT COUNT(*) FROM paginas WHERE publicado = 1')->fetchColumn(); } catch (PDOException $e) {}
try { $total_arts = (int)$pdo->query('SELECT COUNT(*) FROM articulos WHERE publicado = 1')->fetchColumn(); } catch (PDOException $e) {}
try { $total_pros = (int)$pdo->query('SELECT COUNT(*) FROM proyectos WHERE publicado = 1')->fetchColumn(); } catch (PDOException $e) {}
$total_urls = 1 + $total_pags + 2 + $total_arts + $total_pros; // home + páginas + noticias+proyectos índices + artículos + proyectos

$redirecciones = [];
try { $redirecciones = $pdo->query('SELECT * FROM redirecciones ORDER BY id DESC')->fetchAll(); } catch (PDOException $e) {}

$pagina_actual = 'sitemap.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sitemap — CarolTemp Admin</title>
  <meta name="robots" content="noindex, nofollow">
  <?php include '../includes/admin_style.php'; ?>
  <style>
    .sitemap-info { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; margin-bottom:2rem; }
    .sitemap-stat { background:#fff; border:1px solid #dde6f0; border-radius:10px; padding:1.25rem 1.5rem; display:flex; flex-direction:column; gap:6px; }
    .sitemap-stat-val { color:#1e3a5f; font-size:26px; font-weight:700; line-height:1; }
    .sitemap-stat-label { color:#7a95b0; font-size:12px; font-weight:500; text-transform:uppercase; letter-spacing:.06em; }
    .sitemap-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; }
    .btn-generar { background:#1e3a5f; color:#fff; border:none; border-radius:7px; padding:13px 32px; font-size:15px; font-weight:500; cursor:pointer; transition:opacity .15s; }
    .btn-generar:hover { opacity:.88; }
    .btn-danger { background:#fff1f2; color:#dc2626; border:1.5px solid #fca5a5; border-radius:7px; padding:9px 20px; font-size:13.5px; font-weight:600; cursor:pointer; }
    .btn-danger:hover { background:#fef2f2; }
    .sitemap-preview { background:#f4f7fb; border:1px solid #dde6f0; border-radius:8px; padding:1.25rem; font-family:monospace; font-size:12px; line-height:1.6; color:#3a4a5c; overflow-x:auto; max-height:400px; overflow-y:auto; white-space:pre; }
    .sitemap-empty { color:#a0b5c8; font-size:14px; text-align:center; padding:3rem; }
  </style>
</head>
<body>

<?php include '../includes/admin_sidebar.php'; ?>

<main class="main">

  <div class="topbar">
    <h1>Sitemap</h1>
  </div>

  <?php if ($mensaje): ?><div class="mensaje"><?php echo $mensaje; ?></div><?php endif; ?>
  <?php if ($error): ?><div class="error-msg"><?php echo $error; ?></div><?php endif; ?>

  <!-- STATS -->
  <div class="sitemap-info">
    <div class="sitemap-stat">
      <span class="sitemap-stat-val"><?php echo $total_pags; ?></span>
      <span class="sitemap-stat-label">Páginas publicadas</span>
    </div>
    <div class="sitemap-stat">
      <span class="sitemap-stat-val"><?php echo $total_arts; ?></span>
      <span class="sitemap-stat-label">Artículos publicados</span>
    </div>
    <div class="sitemap-stat">
      <span class="sitemap-stat-val"><?php echo $total_pros; ?></span>
      <span class="sitemap-stat-label">Proyectos publicados</span>
    </div>
    <div class="sitemap-stat">
      <span class="sitemap-stat-val"><?php echo $total_urls; ?></span>
      <span class="sitemap-stat-label">Total URLs estimado</span>
    </div>
  </div>

  <div class="sitemap-grid">

    <!-- GENERAR -->
    <div class="card">
      <div class="card-header">
        <h2>Generar sitemap</h2>
        <?php if ($sitemap_fecha): ?>
          <span style="color:#7a95b0;font-size:12px">Último: <?php echo $sitemap_fecha; ?></span>
        <?php endif; ?>
      </div>
      <div class="card-body">
        <p style="color:#5a7a95;font-size:14px;line-height:1.7;margin-bottom:1.5rem">
          Genera el sitemap a partir de las <strong>páginas reales</strong> en la base de datos: home, todas las páginas publicadas, índices de noticias y proyectos, y cada artículo y proyecto publicado.
        </p>
        <form method="POST">
          <button type="submit" name="generar" class="btn-generar">🗺️ Generar sitemap.xml</button>
        </form>
        <?php if ($sitemap_fecha): ?>
          <p style="margin-top:1rem;font-size:13px;color:#7a95b0">
            URL para Google Search Console:<br>
            <strong style="color:#1e3a5f">https://caroltemp.com/sitemap.xml</strong>
          </p>
        <?php endif; ?>
      </div>
    </div>

    <!-- PREVIEW -->
    <div class="card">
      <div class="card-header"><h2>Sitemap actual</h2></div>
      <div class="card-body" style="padding:0">
        <?php if ($sitemap_actual): ?>
          <div class="sitemap-preview"><?php echo htmlspecialchars($sitemap_actual); ?></div>
        <?php else: ?>
          <div class="sitemap-empty">No hay sitemap generado todavía.<br>Pulsa el botón para generarlo.</div>
        <?php endif; ?>
      </div>
    </div>

  </div>

  <!-- REDIRECCIONES -->
  <div class="card" style="margin-top:2rem">
    <div class="card-header">
      <h2>Redirecciones (<?php echo count($redirecciones); ?>)</h2>
      <?php if (!empty($redirecciones)): ?>
        <form method="POST" onsubmit="return confirm('¿Borrar TODAS las redirecciones?')">
          <button type="submit" name="borrar_todas" class="btn-danger">🗑️ Borrar todas</button>
        </form>
      <?php endif; ?>
    </div>
    <div class="card-body">
      <form method="POST" style="margin-bottom:<?php echo empty($redirecciones)?'0':'2rem'; ?>">
        <div class="form-grid">
          <div class="form-group">
            <label>URL antigua</label>
            <input type="text" name="origen" placeholder="/url-antigua" required>
          </div>
          <div class="form-group">
            <label>URL nueva</label>
            <input type="text" name="destino" placeholder="/url-nueva" required>
          </div>
          <div class="form-group">
            <label>Tipo</label>
            <select name="tipo">
              <option value="301">301 Permanente</option>
              <option value="302">302 Temporal</option>
            </select>
          </div>
        </div>
        <button type="submit" name="guardar_redireccion" class="btn-generar" style="margin-top:1rem">+ Crear redirección</button>
      </form>

      <?php if (!empty($redirecciones)): ?>
        <table>
          <thead><tr><th>Origen</th><th>Destino</th><th>Tipo</th><th>Acción</th></tr></thead>
          <tbody>
            <?php foreach ($redirecciones as $red): ?>
            <tr>
              <td><code><?php echo htmlspecialchars($red['origen']); ?></code></td>
              <td><code><?php echo htmlspecialchars($red['destino']); ?></code></td>
              <td><span class="badge-cat"><?php echo (int)$red['tipo']; ?></span></td>
              <td>
                <a href="?eliminar_redireccion=<?php echo $red['id']; ?>"
                   onclick="return confirm('¿Eliminar esta redirección?')"
                   class="td-acciones"><span class="btn-eliminar" style="padding:5px 12px;border-radius:6px">Eliminar</span></a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p style="color:#7a95b0;font-size:14px">No hay redirecciones. La tabla está limpia.</p>
      <?php endif; ?>
    </div>
  </div>

</main>
</body>
</html>
