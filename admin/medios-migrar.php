<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php'); exit;
}
require_once '../includes/db.php';

// Crear tabla si no existe
$pdo->exec("CREATE TABLE IF NOT EXISTS medios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ruta VARCHAR(500) NOT NULL,
  nombre_archivo VARCHAR(255) NOT NULL DEFAULT '',
  titulo VARCHAR(255) NOT NULL DEFAULT '',
  alt VARCHAR(255) NOT NULL DEFAULT '',
  descripcion TEXT,
  mime_type VARCHAR(50) DEFAULT '',
  tamano INT DEFAULT 0,
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$mime_map = [
  'jpg'  => 'image/jpeg', 'jpeg' => 'image/jpeg',
  'png'  => 'image/png',  'gif'  => 'image/gif',
  'webp' => 'image/webp', 'svg'  => 'image/svg+xml',
];

// Rutas ya registradas en medios (para evitar duplicados)
$registradas = $pdo->query('SELECT ruta FROM medios')->fetchAll(PDO::FETCH_COLUMN);
$registradas = array_flip($registradas);

// Contexto de imágenes desde otras tablas (ruta → título que ya conocemos)
$contexto = [];
try {
  foreach ($pdo->query('SELECT titulo, imagen FROM articulos WHERE imagen != "" AND imagen IS NOT NULL') as $r) {
    $ruta = ltrim(preg_replace('#^caroltemp/#', '', $r['imagen']), '/');
    if ($ruta && !isset($contexto[$ruta])) $contexto[$ruta] = ['titulo' => $r['titulo'], 'alt' => $r['titulo']];
  }
} catch (PDOException $e) {}
try {
  foreach ($pdo->query('SELECT titulo, imagen FROM proyectos WHERE imagen != "" AND imagen IS NOT NULL') as $r) {
    $ruta = ltrim(preg_replace('#^caroltemp/#', '', $r['imagen']), '/');
    if ($ruta && !isset($contexto[$ruta])) $contexto[$ruta] = ['titulo' => $r['titulo'], 'alt' => $r['titulo']];
  }
} catch (PDOException $e) {}
try {
  foreach ($pdo->query('SELECT slug, imagen FROM paginas_config WHERE imagen != "" AND imagen IS NOT NULL') as $r) {
    $ruta = ltrim(preg_replace('#^caroltemp/#', '', $r['imagen']), '/');
    if ($ruta && !isset($contexto[$ruta])) $contexto[$ruta] = ['titulo' => ucwords(str_replace('-', ' ', $r['slug'])), 'alt' => ''];
  }
} catch (PDOException $e) {}

// Escanear todas las carpetas de imágenes
$raiz    = dirname(__DIR__);
$carpetas = ['img/blog', 'img/contenido', 'img/heroes', 'img/logo', 'img/noticias', 'img/proyectos', 'img/uploads'];
$extensiones = array_keys($mime_map);

$insertados = [];
$omitidos   = [];
$errores    = [];

$stmt = $pdo->prepare('INSERT INTO medios (ruta, nombre_archivo, titulo, alt, mime_type, tamano) VALUES (?, ?, ?, ?, ?, ?)');

foreach ($carpetas as $carpeta) {
  $dir = $raiz . '/' . $carpeta;
  if (!is_dir($dir)) continue;

  // Recorrer recursivamente
  $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));
  foreach ($it as $archivo) {
    if (!$archivo->isFile()) continue;
    $ext = strtolower(pathinfo($archivo->getFilename(), PATHINFO_EXTENSION));
    if (!in_array($ext, $extensiones)) continue;

    $ruta_relativa = str_replace($raiz . '/', '', $archivo->getPathname());
    $ruta_relativa = ltrim($ruta_relativa, '/');

    // Saltar .gitkeep y archivos ocultos
    if ($archivo->getFilename() === '.gitkeep' || str_starts_with($archivo->getFilename(), '.')) continue;

    if (isset($registradas[$ruta_relativa])) {
      $omitidos[] = $ruta_relativa;
      continue;
    }

    $nombre_archivo = $archivo->getFilename();
    $mime           = $mime_map[$ext] ?? 'image/jpeg';
    $tamano         = $archivo->getSize();
    $titulo         = $contexto[$ruta_relativa]['titulo'] ?? pathinfo($nombre_archivo, PATHINFO_FILENAME);
    $alt            = $contexto[$ruta_relativa]['alt']    ?? '';

    try {
      $stmt->execute([$ruta_relativa, $nombre_archivo, $titulo, $alt, $mime, $tamano]);
      $insertados[] = $ruta_relativa;
    } catch (PDOException $e) {
      $errores[] = $ruta_relativa . ' — ' . $e->getMessage();
    }
  }
}

$total_insertados = count($insertados);
$total_omitidos   = count($omitidos);
$total_errores    = count($errores);
$total_medios     = $pdo->query('SELECT COUNT(*) FROM medios')->fetchColumn();

$pagina_actual = 'medios-migrar.php';
require_once '../includes/admin_style.php';
require_once '../includes/admin_sidebar.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Migrar imágenes — Admin</title>
<?php include '../includes/admin_style.php'; ?>
</head>
<body>
<?php include '../includes/admin_sidebar.php'; ?>
<div class="main">
  <div class="topbar">
    <h1>Migración de imágenes a biblioteca de medios</h1>
    <a href="medios.php">← Ver biblioteca de medios</a>
  </div>

  <div class="card" style="margin-bottom:1.5rem">
    <div class="card-body">
      <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;text-align:center">
        <div style="padding:1.25rem;background:#dcfce7;border-radius:10px">
          <div style="font-size:2rem;font-weight:800;color:#16a34a"><?php echo $total_insertados; ?></div>
          <div style="font-size:13px;color:#15803d;margin-top:4px">Imágenes importadas</div>
        </div>
        <div style="padding:1.25rem;background:#fef9c3;border-radius:10px">
          <div style="font-size:2rem;font-weight:800;color:#854d0e"><?php echo $total_omitidos; ?></div>
          <div style="font-size:13px;color:#92400e;margin-top:4px">Ya existían (omitidas)</div>
        </div>
        <div style="padding:1.25rem;background:<?php echo $total_errores ? '#fff1f2' : '#f8fafc'; ?>;border-radius:10px">
          <div style="font-size:2rem;font-weight:800;color:<?php echo $total_errores ? '#dc2626' : '#94a3b8'; ?>"><?php echo $total_errores; ?></div>
          <div style="font-size:13px;color:<?php echo $total_errores ? '#dc2626' : '#94a3b8'; ?>;margin-top:4px">Errores</div>
        </div>
        <div style="padding:1.25rem;background:#eff6ff;border-radius:10px">
          <div style="font-size:2rem;font-weight:800;color:#1d4ed8"><?php echo $total_medios; ?></div>
          <div style="font-size:13px;color:#1d4ed8;margin-top:4px">Total en biblioteca</div>
        </div>
      </div>
    </div>
  </div>

  <?php if ($total_insertados > 0): ?>
  <div class="card" style="margin-bottom:1.5rem">
    <div class="card-header"><h2>✅ Imágenes importadas (<?php echo $total_insertados; ?>)</h2></div>
    <div class="card-body" style="max-height:320px;overflow-y:auto;padding:0">
      <table>
        <thead><tr><th>Ruta</th><th>Título detectado</th></tr></thead>
        <tbody>
          <?php foreach ($insertados as $r): ?>
          <tr>
            <td><small style="font-family:monospace"><?php echo htmlspecialchars($r); ?></small></td>
            <td style="color:#374151"><?php echo htmlspecialchars($contexto[$r]['titulo'] ?? pathinfo($r, PATHINFO_FILENAME)); ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

  <?php if ($total_omitidos > 0): ?>
  <div class="card" style="margin-bottom:1.5rem">
    <div class="card-header"><h2>⏭️ Omitidas — ya estaban en la biblioteca (<?php echo $total_omitidos; ?>)</h2></div>
    <div class="card-body" style="max-height:240px;overflow-y:auto;padding:0">
      <table>
        <tbody>
          <?php foreach ($omitidos as $r): ?>
          <tr><td><small style="font-family:monospace"><?php echo htmlspecialchars($r); ?></small></td></tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

  <?php if ($total_errores > 0): ?>
  <div class="error-msg">
    <strong>Errores:</strong><br>
    <?php foreach ($errores as $e): ?>
      <small><?php echo htmlspecialchars($e); ?></small><br>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <?php if ($total_insertados === 0 && $total_errores === 0): ?>
  <div class="mensaje">Todas las imágenes ya estaban registradas en la biblioteca. Nada nuevo que importar.</div>
  <?php endif; ?>

  <div style="text-align:center;margin-top:2rem">
    <a href="medios.php" class="btn-save" style="text-decoration:none">Ver biblioteca de medios →</a>
    <a href="medios-migrar.php" style="margin-left:1rem" class="btn-preview">Volver a ejecutar</a>
  </div>
</div>
</body>
</html>
