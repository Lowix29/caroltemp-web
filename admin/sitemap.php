<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php');
  exit;
}
require_once '../includes/db.php';

$mensaje = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_redireccion'])) {

  $origen  = trim($_POST['origen']);
  $destino = trim($_POST['destino']);
  $tipo    = (int)$_POST['tipo'];

  // NORMALIZAR
  if ($origen !== '/' && substr($origen, -1) === '/') {
    $origen = rtrim($origen, '/');
  }

  if ($destino !== '/' && substr($destino, -1) === '/') {
    $destino = rtrim($destino, '/');
  }

  // AÑADIR / SI FALTA
  if (substr($origen, 0, 1) !== '/') {
    $origen = '/' . $origen;
  }

  if (substr($destino, 0, 1) !== '/') {
    $destino = '/' . $destino;
  }

  $stmt = $pdo->prepare("
    INSERT INTO redirecciones
    (origen, destino, tipo)
    VALUES (?, ?, ?)
  ");

  $stmt->execute([
    $origen,
    $destino,
    $tipo
  ]);

  $mensaje = '✅ Redirección creada correctamente.';
}

// GENERAR SITEMAP
if (isset($_POST['generar'])) {

  $base = 'https://hidrofont.es';

  $urls = [];
  
  // Páginas estáticas
  $estaticas = [
    ['loc' => $base . '/',                  'priority' => '1.0', 'changefreq' => 'weekly'],
    ['loc' => $base . '/servicios',         'priority' => '0.9', 'changefreq' => 'monthly'],
    ['loc' => $base . '/zonas',             'priority' => '0.8', 'changefreq' => 'monthly'],
    ['loc' => $base . '/financiacion',      'priority' => '0.8', 'changefreq' => 'monthly'],
    ['loc' => $base . '/sobre-nosotros',    'priority' => '0.7', 'changefreq' => 'monthly'],
    ['loc' => $base . '/contacto',          'priority' => '0.7', 'changefreq' => 'monthly'],
    ['loc' => $base . '/blog/',             'priority' => '0.8', 'changefreq' => 'weekly'],
    ['loc' => $base . '/proyectos/',        'priority' => '0.8', 'changefreq' => 'weekly'],
    // Zonas
    ['loc' => $base . '/zonas/elda',        'priority' => '0.9', 'changefreq' => 'monthly'],
    ['loc' => $base . '/zonas/petrer',      'priority' => '0.9', 'changefreq' => 'monthly'],
    ['loc' => $base . '/zonas/novelda',     'priority' => '0.8', 'changefreq' => 'monthly'],
    ['loc' => $base . '/zonas/monovar',     'priority' => '0.8', 'changefreq' => 'monthly'],
    ['loc' => $base . '/zonas/sax',         'priority' => '0.8', 'changefreq' => 'monthly'],
    ['loc' => $base . '/zonas/pinoso',      'priority' => '0.7', 'changefreq' => 'monthly'],
    ['loc' => $base . '/zonas/monforte',    'priority' => '0.7', 'changefreq' => 'monthly'],
    ['loc' => $base . '/zonas/salinas',     'priority' => '0.7', 'changefreq' => 'monthly'],
  ];

  foreach ($estaticas as $url) {
    $urls[] = $url;
  }

  // Artículos publicados
  $articulos = $pdo->query('SELECT slug, fecha FROM articulos WHERE publicado = 1 ORDER BY fecha DESC')->fetchAll();
  foreach ($articulos as $art) {
    $urls[] = [
      'loc'        => $base . '/blog/' . $art['slug'],
      'lastmod'    => date('Y-m-d', strtotime($art['fecha'])),
      'priority'   => '0.7',
      'changefreq' => 'monthly',
    ];
  }

  // Proyectos publicados
  $proyectos = $pdo->query('SELECT slug, fecha FROM proyectos WHERE publicado = 1 ORDER BY fecha DESC')->fetchAll();
  foreach ($proyectos as $pro) {
    $urls[] = [
      'loc'        => $base . '/proyectos/' . $pro['slug'],
      'lastmod'    => date('Y-m-d', strtotime($pro['fecha'])),
      'priority'   => '0.7',
      'changefreq' => 'monthly',
    ];
  }

  // Generar XML
  $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
  $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

  foreach ($urls as $url) {
    $xml .= '  <url>' . "\n";
    $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . "\n";
    if (!empty($url['lastmod'])) {
      $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . "\n";
    }
    $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
    $xml .= '    <priority>' . $url['priority'] . '</priority>' . "\n";
    $xml .= '  </url>' . "\n";
  }

  $xml .= '</urlset>';

  // Guardar en raíz
  $ruta = dirname(__DIR__) . '/sitemap.xml';
  if (file_put_contents($ruta, $xml) !== false) {
    $mensaje = '✅ Sitemap generado correctamente con ' . count($urls) . ' URLs. Disponible en: https://hidrofont.es/sitemap.xml';
  } else {
    $error = '❌ Error al guardar el sitemap. Comprueba los permisos de escritura en la carpeta raíz.';
  }
}

// Leer sitemap actual si existe
$sitemap_actual  = '';
$sitemap_fecha   = '';
$ruta_sitemap    = dirname(__DIR__) . '/sitemap.xml';
if (file_exists($ruta_sitemap)) {
  $sitemap_actual = file_get_contents($ruta_sitemap);
  $sitemap_fecha  = date('d/m/Y H:i', filemtime($ruta_sitemap));
}

// Contar URLs
$total_arts = $pdo->query('SELECT COUNT(*) FROM articulos WHERE publicado = 1')->fetchColumn();
$total_pros = $pdo->query('SELECT COUNT(*) FROM proyectos WHERE publicado = 1')->fetchColumn();
$total_urls = 16 + $total_arts + $total_pros;
$redirecciones = $pdo->query("
    SELECT *
    FROM redirecciones
    ORDER BY id DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sitemap — Hidrofont Admin</title>
  <meta name="robots" content="noindex, nofollow">
  <?php include '../includes/admin_style.php'; ?>
  <style>
    .sitemap-info { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
    .sitemap-stat { background: #fff; border: 1px solid #dde6f0; border-radius: 10px; padding: 1.25rem 1.5rem; display: flex; flex-direction: column; gap: 6px; }
    .sitemap-stat-val { color: #1e3a5f; font-size: 26px; font-weight: 700; line-height: 1; }
    .sitemap-stat-label { color: #7a95b0; font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.06em; }
    .sitemap-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    .btn-generar { background: #1e3a5f; color: #fff; border: none; border-radius: 7px; padding: 13px 32px; font-size: 15px; font-weight: 500; cursor: pointer; transition: opacity 0.15s; }
    .btn-generar:hover { opacity: 0.88; }
    .sitemap-preview { background: #f4f7fb; border: 1px solid #dde6f0; border-radius: 8px; padding: 1.25rem; font-family: monospace; font-size: 12px; line-height: 1.6; color: #3a4a5c; overflow-x: auto; max-height: 400px; overflow-y: auto; white-space: pre; }
    .sitemap-empty { color: #a0b5c8; font-size: 14px; text-align: center; padding: 3rem; }
    .url-list { display: flex; flex-direction: column; gap: 0; }
    .url-item { display: flex; align-items: center; justify-content: space-between; padding: 0.65rem 0; border-bottom: 1px solid #eaeff4; }
    .url-item:last-child { border-bottom: none; }
    .url-item-loc { font-size: 12.5px; color: #1e3a5f; font-family: monospace; }
    .url-item-pri { font-size: 11px; background: #e8f0fa; color: #1e3a5f; padding: 2px 8px; border-radius: 10px; font-weight: 500; }
  </style>
</head>
<body>

<?php include '../includes/admin_sidebar.php'; ?>

<main class="main">

  <div class="topbar">
    <h1>Generador de Sitemap</h1>
  </div>

  <?php if ($mensaje): ?>
    <div class="mensaje"><?php echo $mensaje; ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="error-msg"><?php echo $error; ?></div>
  <?php endif; ?>

  <!-- STATS -->
  <div class="sitemap-info">
    <div class="sitemap-stat">
      <span class="sitemap-stat-val">16</span>
      <span class="sitemap-stat-label">Páginas estáticas</span>
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
      <span class="sitemap-stat-label">Total URLs</span>
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
          El sitemap incluye todas las páginas estáticas, las 8 zonas, y todos los artículos y proyectos publicados. Se guarda automáticamente en la raíz como <strong>sitemap.xml</strong>.
        </p>

        <div class="url-list" style="margin-bottom:1.5rem">
          <div class="url-item">
            <span class="url-item-loc">hidrofont.es/</span>
            <span class="url-item-pri">1.0</span>
          </div>
          <div class="url-item">
            <span class="url-item-loc">hidrofont.es/servicios</span>
            <span class="url-item-pri">0.9</span>
          </div>
          <div class="url-item">
            <span class="url-item-loc">hidrofont.es/zonas/elda</span>
            <span class="url-item-pri">0.9</span>
          </div>
          <div class="url-item">
            <span class="url-item-loc">hidrofont.es/blog/tu-articulo</span>
            <span class="url-item-pri">0.7</span>
          </div>
          <div class="url-item">
            <span class="url-item-loc">hidrofont.es/proyectos/tu-proyecto</span>
            <span class="url-item-pri">0.7</span>
          </div>
        </div>

        <form method="POST" action="">
          <button type="submit" name="generar" class="btn-generar">
            🗺️ Generar sitemap.xml
          </button>
        </form>

        <?php if ($sitemap_fecha): ?>
          <p style="margin-top:1rem;font-size:13px;color:#7a95b0">
            Añade esta URL a Google Search Console:<br>
            <strong style="color:#1e3a5f">https://hidrofont.es/sitemap.xml</strong>
          </p>
        <?php endif; ?>
      </div>
    </div>

    <!-- PREVIEW -->
    <div class="card">
      <div class="card-header">
        <h2>Sitemap actual</h2>
      </div>
      <div class="card-body" style="padding:0">
        <?php if ($sitemap_actual): ?>
          <div class="sitemap-preview"><?php echo htmlspecialchars($sitemap_actual); ?></div>
        <?php else: ?>
          <div class="sitemap-empty">
            No hay sitemap generado todavía.<br>
            Pulsa el botón para generarlo.
          </div>
        <?php endif; ?>
      </div>
    </div>

  </div>
  
  <!-- REDIRECCIONES -->
<div class="card" style="margin-top:2rem">

  <div class="card-header">
    <h2>Redirecciones</h2>
  </div>

  <div class="card-body">

    <form method="POST" action="" style="margin-bottom:2rem">

      <div class="form-grid">

        <div class="form-group">
          <label>URL antigua</label>
          <input
            type="text"
            name="origen"
            placeholder="/url-antigua"
            required
          >
        </div>

        <div class="form-group">
          <label>URL nueva</label>
          <input
            type="text"
            name="destino"
            placeholder="/url-nueva"
            required
          >
        </div>

        <div class="form-group">
          <label>Tipo</label>

          <select name="tipo">
            <option value="301">301 Permanente</option>
            <option value="302">302 Temporal</option>
          </select>
        </div>

      </div>

      <button
        type="submit"
        name="guardar_redireccion"
        class="btn-generar"
        style="margin-top:1rem"
      >
        + Crear redirección
      </button>

    </form>

  </div>
<?php if (!empty($redirecciones)): ?>

  <table style="margin-top:2rem">

    <thead>
      <tr>
        <th>Origen</th>
        <th>Destino</th>
        <th>Tipo</th>
        <th>Acción</th>
      </tr>
    </thead>

    <tbody>

      <?php foreach ($redirecciones as $red): ?>

        <tr>

          <td>
            <code><?php echo htmlspecialchars($red['origen']); ?></code>
          </td>

          <td>
            <code><?php echo htmlspecialchars($red['destino']); ?></code>
          </td>

          <td>
            <span class="badge-cat">
              <?php echo (int)$red['tipo']; ?>
            </span>
          </td>

          <td>

            <a
              href="?eliminar_redireccion=<?php echo $red['id']; ?>"
              onclick="return confirm('¿Eliminar redirección?')"
              style="
                background:#fdf0f0;
                color:#c0392b;
                padding:6px 12px;
                border-radius:6px;
                text-decoration:none;
                font-size:12px;
                font-weight:600;
              "
            >
              Eliminar
            </a>

          </td>

        </tr>

      <?php endforeach; ?>

    </tbody>

  </table>

<?php else: ?>

  <p style="color:#7a95b0">
    No hay redirecciones creadas todavía.
  </p>

<?php endif; ?>
</div>

</main>

</body>
</html>