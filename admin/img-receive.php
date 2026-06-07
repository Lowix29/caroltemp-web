<?php
/* ============================================================
   CAROLTEMP — Receptor seguro de imágenes desde local
   Solo acepta peticiones con el token correcto.
   No requiere sesión — es llamado por PHP vía cURL, no por browser.
============================================================ */
header('Content-Type: application/json; charset=utf-8');

define('SYNC_TOKEN', 'ct_sync_K7mxP9_caroltemp');

// En local no tiene sentido recibir desde local
$serverName = $_SERVER['SERVER_NAME'] ?? '';
if (in_array($serverName, ['localhost', '127.0.0.1'])) {
  echo json_encode(['error' => 'Solo disponible en producción']);
  exit;
}

// Validar token
if (($_POST['token'] ?? '') !== SYNC_TOKEN) {
  http_response_code(403);
  echo json_encode(['error' => 'No autorizado']);
  exit;
}

$rutaRel = trim($_POST['ruta'] ?? '');

// Validar ruta: debe empezar por img/ y sin path traversal
if (!preg_match('#^img/[a-zA-Z0-9/_\-\.]+\.(jpg|jpeg|png|webp|gif)$#i', $rutaRel)) {
  http_response_code(400);
  echo json_encode(['error' => 'Ruta inválida: ' . htmlspecialchars($rutaRel)]);
  exit;
}

// Doble check anti path traversal
if (strpos($rutaRel, '..') !== false) {
  http_response_code(400);
  echo json_encode(['error' => 'Path traversal detectado']);
  exit;
}

if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
  echo json_encode(['error' => 'Sin archivo o error de subida: ' . ($_FILES['imagen']['error'] ?? 'sin archivo')]);
  exit;
}

// Validar tipo MIME
$permitidos = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
$mime = mime_content_type($_FILES['imagen']['tmp_name']);
if (!in_array($mime, $permitidos)) {
  http_response_code(400);
  echo json_encode(['error' => 'Tipo MIME no permitido: ' . $mime]);
  exit;
}

$destino = dirname(__DIR__) . '/' . $rutaRel;
$dir     = dirname($destino);

if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
  echo json_encode(['error' => 'No se pudo crear el directorio']);
  exit;
}

if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
  echo json_encode(['ok' => true, 'ruta' => $rutaRel]);
} else {
  echo json_encode(['error' => 'Error al mover el archivo a destino']);
}
