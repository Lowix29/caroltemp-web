<?php
/* =========================================================
   CAROLTEMP — Asignar imágenes a los artículos de ayudas
   Ejecutar UNA sola vez desde el admin (requiere sesión).
   ========================================================= */
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  die('No autorizado.');
}
require_once '../includes/db.php';
require_once '../includes/img-sync.php';

$mapa = [
  'ayudas-cambiar-tuberias-vivienda'           => 'img/noticias/ayudas-cambiar-tuberias-vivienda.jpg',
  'subvenciones-bajantes-comunitarias'          => 'img/noticias/subvenciones-bajantes-comunitarias.jpg',
  'ayudas-sustituir-tuberias-uralita-amianto'  => 'img/noticias/ayudas-sustituir-tuberias-uralita-amianto.jpg',
  'ayudas-cambiar-banera-plato-ducha'           => 'img/noticias/ayudas-cambiar-banera-plato-ducha.jpg',
  'reformas-fontaneria-desgravacion-renta-irpf' => 'img/noticias/reformas-fontaneria-desgravacion-renta-irpf.jpg',
  'quien-paga-fuga-agua-comunidad-vecinos'      => 'img/noticias/quien-paga-fuga-agua-comunidad-vecinos.jpg',
  'quien-paga-bajante-rota-comunidad'           => 'img/noticias/quien-paga-bajante-rota-comunidad.jpg',
  'cuando-cambiar-tuberias-vivienda'            => 'img/noticias/cuando-cambiar-tuberias-vivienda.jpg',
  'ayudas-rehabilitacion-edificios-fontaneria'  => 'img/noticias/ayudas-rehabilitacion-edificios-fontaneria.jpg',
  'ayudas-renovar-instalaciones-agua-saneamiento' => 'img/noticias/ayudas-renovar-instalaciones-agua-saneamiento.jpg',
];

$ok      = [];
$errores = [];

$stmt_upd = $pdo->prepare('UPDATE articulos SET imagen = ? WHERE slug = ?');

foreach ($mapa as $slug => $ruta_rel) {
  $ruta_abs = dirname(__DIR__) . '/' . $ruta_rel;

  if (!file_exists($ruta_abs)) {
    $errores[] = "❌ Archivo no encontrado: <code>{$ruta_rel}</code>";
    continue;
  }

  // Actualizar BD
  $stmt_upd->execute([$ruta_rel, $slug]);
  if ($stmt_upd->rowCount() === 0) {
    $errores[] = "⚠️ Artículo no encontrado en BD (slug): <code>{$slug}</code>";
    continue;
  }

  // Sincronizar imagen a producción
  $sinc = syncImgToProduction($ruta_abs, $ruta_rel);

  $ok[] = ($sinc ? '✅' : '⚠️ (sin sync)') . " <code>{$slug}</code>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Asignar imágenes — CarolTemp</title>
<style>
  body { font-family: system-ui, sans-serif; max-width: 780px; margin: 3rem auto; padding: 0 1.5rem; background:#f8fafc; color:#1e293b; }
  h1 { font-size: 1.4rem; font-weight: 800; color: #0d1f33; border-bottom: 2px solid #e2e8f0; padding-bottom: .75rem; }
  .ok   { background: #dcfce7; border: 1px solid #86efac; border-radius: 10px; padding: 1rem 1.5rem; margin: 1rem 0; }
  .warn { background: #fef9c3; border: 1px solid #fde047; border-radius: 10px; padding: 1rem 1.5rem; margin: 1rem 0; }
  ul { padding-left: 1.4rem; margin: .5rem 0; } li { margin: .3rem 0; }
  a.btn { display: inline-block; background: #1e3a5f; color: #fff; padding: .65rem 1.4rem; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 700; margin-top: 1rem; }
  code { background:#f1f5f9; padding:1px 5px; border-radius:4px; font-size:13px; }
</style>
</head>
<body>
<h1>Asignación de imágenes — artículos de ayudas</h1>

<?php if (!empty($ok)): ?>
<div class="ok">
  <strong>✅ <?php echo count($ok); ?> imagen(es) asignadas correctamente:</strong>
  <ul><?php foreach ($ok as $m) echo "<li>{$m}</li>"; ?></ul>
</div>
<?php endif; ?>

<?php if (!empty($errores)): ?>
<div class="warn">
  <strong>Problemas encontrados:</strong>
  <ul><?php foreach ($errores as $e) echo "<li>{$e}</li>"; ?></ul>
</div>
<?php endif; ?>

<a href="nuevo-articulo.php" class="btn">Ver artículos en el admin →</a>
</body>
</html>
