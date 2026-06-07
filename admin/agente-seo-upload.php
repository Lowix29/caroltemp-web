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
require_once '../includes/db.php';
require_once '../includes/img-sync.php';

if (!isset($_FILES['imagen'])) {
  echo json_encode(['error' => 'PHP no recibió el archivo. Verifica que file_uploads=On en php.ini']);
  exit;
}

$err = $_FILES['imagen']['error'];
if ($err !== UPLOAD_ERR_OK) {
  $errores = [
    UPLOAD_ERR_INI_SIZE   => 'El archivo supera upload_max_filesize en php.ini',
    UPLOAD_ERR_FORM_SIZE  => 'El archivo supera el límite del formulario',
    UPLOAD_ERR_PARTIAL    => 'El archivo se subió solo parcialmente',
    UPLOAD_ERR_NO_FILE    => 'No se seleccionó ningún archivo',
    UPLOAD_ERR_NO_TMP_DIR => 'Falta la carpeta temporal en el servidor',
    UPLOAD_ERR_CANT_WRITE => 'No se puede escribir en disco',
    UPLOAD_ERR_EXTENSION  => 'Una extensión PHP bloqueó la subida',
  ];
  echo json_encode(['error' => $errores[$err] ?? 'Error de subida código ' . $err]);
  exit;
}

if (empty($_FILES['imagen']['name'])) {
  echo json_encode(['error' => 'Archivo recibido pero sin nombre']);
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

// Sincronizar a producción automáticamente (solo cuando se trabaja en local)
syncImgToProduction($rutaAbs, ltrim($rutaWeb, '/'));

echo json_encode(['ok' => true, 'ruta' => $rutaWeb]);
