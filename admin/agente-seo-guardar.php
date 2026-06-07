<?php
/* =============================================
   CAROLTEMP — Agente SEO: guardar en base de datos
============================================= */
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php');
  exit;
}

require_once '../includes/db.php';

$tipo        = $_POST['tipo']        ?? 'articulo';
$slug        = trim($_POST['slug']        ?? '');
$titulo      = trim($_POST['titulo']      ?? '');
$extracto    = trim($_POST['extracto']    ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$contenido   = trim($_POST['contenido']   ?? '');
$zona        = trim($_POST['zona']        ?? '');
$categoria   = trim($_POST['categoria']   ?? '');
$servicio    = trim($_POST['servicio']    ?? '');
$meta_title   = trim($_POST['meta_title']   ?? '');
$meta_desc    = trim($_POST['meta_desc']    ?? '');
$sidebar_tipo = trim($_POST['sidebar_tipo'] ?? '');
$imagenes     = json_decode($_POST['imagenes'] ?? '[]', true);

if (!$slug || !$titulo || !$contenido) {
  $_SESSION['agente_error'] = 'Faltan campos obligatorios (título, slug o contenido).';
  header('Location: agente-seo.php');
  exit;
}

// Mover imágenes de /tmp/ a /img/contenido/[slug]/ con nombre descriptivo
if (!empty($imagenes)) {
  $carpeta = dirname(__DIR__) . '/img/contenido/' . $slug . '/';
  if (!is_dir($carpeta)) {
    mkdir($carpeta, 0755, true);
  }
  foreach ($imagenes as $idx => &$rutaActual) {
    $ext         = strtolower(pathinfo($rutaActual, PATHINFO_EXTENSION));
    $n           = $idx + 1;
    $nombreSeo   = $slug . '-foto-' . $n . '.' . $ext;
    $origen      = dirname(__DIR__) . $rutaActual;
    $destino     = $carpeta . $nombreSeo;
    $rutaNueva   = '/img/contenido/' . $slug . '/' . $nombreSeo;
    if (file_exists($origen)) {
      rename($origen, $destino);
    }
    $contenido   = str_replace($rutaActual, $rutaNueva, $contenido);
    $rutaActual  = $rutaNueva;
  }
  unset($rutaActual);
}

// Imagen principal: la primera subida o la que venía en el form
$imagen_principal = trim($_POST['imagen'] ?? '');
if (empty($imagen_principal) && !empty($imagenes)) {
  $imagen_principal = $imagenes[0];
}

// Auto-commit y push de las imágenes nuevas al repo
if (!empty($imagenes)) {
  $repo = dirname(__DIR__);
  $carpetaRel = 'img/contenido/' . $slug;
  shell_exec("cd " . escapeshellarg($repo) . " && git add " . escapeshellarg($carpetaRel) . " 2>&1");
  shell_exec("cd " . escapeshellarg($repo) . " && git -c commit.gpgsign=false commit -m " . escapeshellarg("img: fotos artículo " . $slug) . " 2>&1");
  shell_exec("cd " . escapeshellarg($repo) . " && git push origin main 2>&1");
}

// Guardar como borrador para revisión previa a publicar
$publicado = 0;

try {
  if ($tipo === 'proyecto') {
    $stmt = $pdo->prepare('
      INSERT INTO proyectos
        (titulo, slug, descripcion, contenido, imagen, zona, servicio, meta_title, meta_desc, publicado)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ');
    $stmt->execute([
      $titulo, $slug,
      $descripcion ?: $extracto,
      $contenido, $imagen_principal, $zona,
      $servicio, $meta_title, $meta_desc, $publicado
    ]);
    $id = $pdo->lastInsertId();
    header('Location: nuevo-proyecto.php?id=' . $id . '&ok=1');
  } else {
    $stmt = $pdo->prepare('
      INSERT INTO articulos
        (titulo, slug, extracto, contenido, imagen, zona, categoria, meta_title, meta_desc, publicado, sidebar_tipo)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ');
    $stmt->execute([
      $titulo, $slug, $extracto,
      $contenido, $imagen_principal, $zona,
      $categoria, $meta_title, $meta_desc, $publicado, $sidebar_tipo
    ]);
    $id = $pdo->lastInsertId();
    header('Location: nuevo-articulo.php?id=' . $id . '&ok=1');
  }
  exit;

} catch (PDOException $e) {
  $_SESSION['agente_error'] = 'Error al guardar. El slug "' . htmlspecialchars($slug) . '" puede estar duplicado. Cámbialo e inténtalo de nuevo.';
  header('Location: agente-seo.php');
  exit;
}
