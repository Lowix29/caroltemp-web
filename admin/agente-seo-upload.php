<?php
/* =============================================
   CAROLTEMP — Agente SEO: subida de imágenes
============================================= */
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  http_response_code(401);
  echo json_encode(['error' => 'No autorizado']);
  exit;
}

header('Content-Type: application/json; charset=utf-8');

if (empty($_FILES['imagen']['name'])) {
  echo json_encode(['error' => 'No se recibió ningún archivo']);
  exit;
}

$permitidos = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
if (!in_array($_FILES['imagen']['type'], $permitidos)) {
  echo json_encode(['error' => 'Formato no permitido. Usa JPG, PNG o WebP.']);
  exit;
}

if ($_FILES['imagen']['size'] > 5 * 1024 * 1024) {
  echo json_encode(['error' => 'La imagen supera 5MB.']);
  exit;
}

$tmpDir = dirname(__DIR__) . '/img/contenido/tmp/';
if (!is_dir($tmpDir)) {
  mkdir($tmpDir, 0755, true);
}

$ext    = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
$nombre = 'agente_' . uniqid() . '.' . $ext;
$rutaAbs = $tmpDir . $nombre;
$rutaWeb = '/img/contenido/tmp/' . $nombre;

if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaAbs)) {
  echo json_encode(['error' => 'Error al guardar la imagen en el servidor.']);
  exit;
}

echo json_encode(['ok' => true, 'ruta' => $rutaWeb]);
