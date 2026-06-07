<?php
/* ============================================================
   CAROLTEMP — Upload de imágenes para TinyMCE
   Guarda en img/editor/ y sincroniza a producción si es local.
============================================================ */
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  http_response_code(401);
  echo json_encode(['error' => 'No autorizado']);
  exit;
}

header('Content-Type: application/json; charset=utf-8');
require_once '../includes/db.php';
require_once '../includes/img-sync.php';

if (!isset($_FILES['image'])) {
  echo json_encode(['error' => 'No se recibió ningún archivo']);
  exit;
}

$file = $_FILES['image'];
if ($file['error'] !== UPLOAD_ERR_OK) {
  echo json_encode(['error' => 'Error de subida: ' . $file['error']]);
  exit;
}

$permitidos = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
if (!in_array($file['type'], $permitidos)) {
  echo json_encode(['error' => 'Formato no permitido']);
  exit;
}

if ($file['size'] > 5 * 1024 * 1024) {
  echo json_encode(['error' => 'La imagen supera 5MB']);
  exit;
}

$ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$nombre  = 'editor_' . uniqid() . '.' . $ext;
$dir     = dirname(__DIR__) . '/img/editor/';
if (!is_dir($dir)) mkdir($dir, 0755, true);

$rutaAbs = $dir . $nombre;
$rutaRel = 'img/editor/' . $nombre;
$rutaUrl = ($is_local ? '/caroltemp/' : '/') . $rutaRel;

if (!move_uploaded_file($file['tmp_name'], $rutaAbs)) {
  echo json_encode(['error' => 'Error al guardar la imagen']);
  exit;
}

// Sincronizar a producción automáticamente
syncImgToProduction($rutaAbs, $rutaRel);

// Formato que espera TinyMCE (images_upload_handler)
echo json_encode([
  'success' => true,
  'file'    => ['url' => $rutaUrl],
  'location' => $rutaUrl,
]);
