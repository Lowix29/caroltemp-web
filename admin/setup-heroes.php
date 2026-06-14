<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php'); exit;
}
require_once '../includes/db.php';

$pdo->exec("CREATE TABLE IF NOT EXISTS paginas_config (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(100) NOT NULL UNIQUE,
  imagen VARCHAR(500) NOT NULL DEFAULT '',
  modificado DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$tipos    = ['fugas', 'desatascos', 'fontanero', 'urgencias'];
$ciudades = ['elda','petrer','novelda','monovar','sax','pinoso','monforte-del-cid','salinas','aspe'];

$insertados = 0;
$ya_existian = 0;

foreach ($tipos as $tipo) {
  foreach ($ciudades as $ciudad) {
    $slug   = "{$tipo}-{$ciudad}";
    $imagen = "img/heroes/{$slug}.jpg";
    $abs    = dirname(__DIR__) . '/' . $imagen;
    if (!file_exists($abs)) continue;
    try {
      $chk = $pdo->prepare('SELECT imagen FROM paginas_config WHERE slug = ? LIMIT 1');
      $chk->execute([$slug]);
      if ($chk->fetchColumn() === false) {
        $pdo->prepare('INSERT INTO paginas_config (slug, imagen) VALUES (?,?)')->execute([$slug, $imagen]);
        $insertados++;
      } else {
        $ya_existian++;
      }
    } catch (PDOException $e) {
      echo "<p style='color:red'>Error en $slug: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Setup Heroes — Admin</title>
  <?php include '../includes/admin_style.php'; ?>
</head>
<body>
<?php include '../includes/admin_sidebar.php'; ?>
<div class="main">
  <div class="topbar">
    <h1>Setup imágenes hero</h1>
    <a href="index.php">← Volver al panel</a>
  </div>
  <div class="card" style="padding:2rem">
    <h2 style="margin-bottom:1.5rem">Resultado</h2>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:2rem">
      <div style="background:#dcfce7;border-radius:10px;padding:1.5rem;text-align:center">
        <div style="font-size:3rem;font-weight:800;color:#16a34a"><?php echo $insertados; ?></div>
        <div style="color:#15803d;font-weight:600;margin-top:4px">Imágenes insertadas en BD</div>
      </div>
      <div style="background:#f1f5f9;border-radius:10px;padding:1.5rem;text-align:center">
        <div style="font-size:3rem;font-weight:800;color:#94a3b8"><?php echo $ya_existian; ?></div>
        <div style="color:#64748b;font-weight:600;margin-top:4px">Ya configuradas (sin tocar)</div>
      </div>
    </div>
    <?php if ($insertados > 0): ?>
      <div class="mensaje">✅ Se han asignado <?php echo $insertados; ?> imágenes hero a las páginas del sitio.</div>
    <?php else: ?>
      <div class="mensaje" style="background:#fef9c3">⚠️ No se insertó ninguna imagen nueva. Puede que ya estuvieran configuradas.</div>
    <?php endif; ?>
    <div style="margin-top:2rem;padding-top:1.5rem;border-top:1px solid #f1f5f9">
      <p style="font-size:13px;color:#64748b">Para sustituir cualquier imagen por una foto real, ve a <strong>Biblioteca de medios</strong>, sube la foto y usa el slug correcto en la tabla <code>paginas_config</code>.</p>
    </div>
  </div>
</div>
</body>
</html>