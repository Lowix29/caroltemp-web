<?php
ob_start();
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  ob_end_clean();
  http_response_code(401); echo json_encode(['error'=>'No autorizado']); exit;
}
require_once '../includes/db.php';
require_once '../includes/img-sync.php';

ob_end_clean();
header('Content-Type: application/json; charset=utf-8');

try {
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
} catch (PDOException $e) {}

$action = $_REQUEST['action'] ?? '';

switch ($action) {
  case 'upload':   upload();  break;
  case 'update':   update();  break;
  case 'rename':   renameFile(); break;
  case 'delete':   borrar();  break;
  case 'list':     lista();   break;
  default: echo json_encode(['error'=>'Acción no válida']);
}

function slugify(string $s): string {
  $s = strtolower($s);
  $s = str_replace(['á','é','í','ó','ú','ñ','ü',' '],['a','e','i','o','u','n','u','-'],$s);
  $s = preg_replace('/[^a-z0-9\-]/','', $s);
  $s = preg_replace('/-+/','-', trim($s,'-'));
  return $s ?: 'imagen';
}

function upload() {
  global $pdo;
  if (empty($_FILES['imagen']['name'])) { echo json_encode(['error'=>'Sin archivo']); return; }
  $f = $_FILES['imagen'];
  $ok_types = ['image/jpeg','image/png','image/webp','image/gif'];
  if (!in_array($f['type'], $ok_types)) { echo json_encode(['error'=>'Formato no permitido']); return; }
  if ($f['size'] > 20*1024*1024) { echo json_encode(['error'=>'Supera 20 MB']); return; }

  $ext   = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
  $base  = slugify(pathinfo($f['name'], PATHINFO_FILENAME));
  $dir   = dirname(__DIR__) . '/img/uploads/';
  if (!is_dir($dir)) mkdir($dir, 0755, true);

  $nombre = $base . '.' . $ext;
  $i = 1;
  while (file_exists($dir . $nombre)) { $nombre = $base . '-' . $i++ . '.' . $ext; }

  $ruta_abs = $dir . $nombre;
  $ruta_web = 'img/uploads/' . $nombre;
  if (!move_uploaded_file($f['tmp_name'], $ruta_abs)) { echo json_encode(['error'=>'Error al guardar']); return; }

  syncImgToProduction($ruta_abs, $ruta_web);

  $titulo = pathinfo($f['name'], PATHINFO_FILENAME);
  $pdo->prepare('INSERT INTO medios (ruta,nombre_archivo,titulo,alt,descripcion,mime_type,tamano) VALUES(?,?,?,?,?,?,?)')
      ->execute([$ruta_web, $nombre, $titulo, '', '', $f['type'], $f['size']]);
  $id = (int)$pdo->lastInsertId();

  $row = $pdo->prepare('SELECT * FROM medios WHERE id=?');
  $row->execute([$id]);
  echo json_encode($row->fetch(PDO::FETCH_ASSOC));
}

function update() {
  global $pdo;
  $id   = (int)($_POST['id'] ?? 0);
  if (!$id) { echo json_encode(['error'=>'ID inválido']); return; }
  $pdo->prepare('UPDATE medios SET titulo=?,alt=?,descripcion=? WHERE id=?')
      ->execute([
        trim($_POST['titulo']      ?? ''),
        trim($_POST['alt']         ?? ''),
        trim($_POST['descripcion'] ?? ''),
        $id
      ]);
  echo json_encode(['ok'=>true]);
}

function renameFile() {
  global $pdo;
  $id    = (int)($_POST['id'] ?? 0);
  $nuevo = trim($_POST['nombre_archivo'] ?? '');
  if (!$id || !$nuevo) { echo json_encode(['error'=>'Datos inválidos']); return; }

  $stmt = $pdo->prepare('SELECT * FROM medios WHERE id=?');
  $stmt->execute([$id]);
  $medio = $stmt->fetch();
  if (!$medio) { echo json_encode(['error'=>'No encontrado']); return; }

  $ext = strtolower(pathinfo($medio['nombre_archivo'], PATHINFO_EXTENSION));
  $nuevo = slugify(pathinfo($nuevo, PATHINFO_FILENAME)) . '.' . $ext;

  $dir         = dirname(__DIR__) . '/img/uploads/';
  $ruta_actual = dirname(__DIR__) . '/' . $medio['ruta'];
  $nueva_abs   = $dir . $nuevo;
  $nueva_web   = 'img/uploads/' . $nuevo;

  if ($nuevo !== $medio['nombre_archivo'] && file_exists($nueva_abs)) {
    echo json_encode(['error'=>'Ya existe un archivo con ese nombre']); return;
  }
  if (file_exists($ruta_actual)) rename($ruta_actual, $nueva_abs);

  $pdo->prepare('UPDATE medios SET nombre_archivo=?,ruta=? WHERE id=?')->execute([$nuevo,$nueva_web,$id]);
  echo json_encode(['ok'=>true,'nombre_archivo'=>$nuevo,'ruta'=>$nueva_web]);
}

function borrar() {
  global $pdo;
  $id = (int)($_POST['id'] ?? 0);
  if (!$id) { echo json_encode(['error'=>'ID inválido']); return; }
  try {
    $stmt = $pdo->prepare('SELECT ruta FROM medios WHERE id=?');
    $stmt->execute([$id]);
    $m = $stmt->fetch();
    if ($m) {
      $abs = dirname(__DIR__) . '/' . ltrim($m['ruta'], '/');
      if (file_exists($abs)) @unlink($abs);
    }
    $pdo->prepare('DELETE FROM medios WHERE id=?')->execute([$id]);
    echo json_encode(['ok'=>true]);
  } catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
  }
}

function lista() {
  global $pdo;
  try { echo json_encode($pdo->query('SELECT * FROM medios ORDER BY fecha DESC')->fetchAll(PDO::FETCH_ASSOC)); }
  catch (PDOException $e) { echo json_encode([]); }
}
