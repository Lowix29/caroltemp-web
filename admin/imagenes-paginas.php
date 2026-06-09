<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php');
  exit;
}
require_once '../includes/db.php';
require_once '../includes/img-sync.php';

$base_url = $is_local ? 'http://localhost/caroltemp/' : 'https://caroltemp.com/';

// Crear tabla si no existe
try {
  $pdo->exec("CREATE TABLE IF NOT EXISTS paginas_config (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL UNIQUE,
    nombre VARCHAR(150) NOT NULL DEFAULT '',
    imagen VARCHAR(255) NOT NULL DEFAULT '',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
} catch (PDOException $e) {}

// Definición de todas las páginas del sitio
$paginas_grupos = [
  'Páginas principales' => [
    ['slug' => 'inicio',         'nombre' => 'Inicio (portada)'],
    ['slug' => 'servicios',      'nombre' => 'Servicios'],
    ['slug' => 'contacto',       'nombre' => 'Contacto'],
    ['slug' => 'financiacion',   'nombre' => 'Financiación'],
    ['slug' => 'sobre-nosotros', 'nombre' => 'Sobre nosotros'],
  ],
  'Fontanero por ciudad' => [
    ['slug' => 'fontanero-elda',             'nombre' => 'Fontanero Elda'],
    ['slug' => 'fontanero-petrer',           'nombre' => 'Fontanero Petrer'],
    ['slug' => 'fontanero-novelda',          'nombre' => 'Fontanero Novelda'],
    ['slug' => 'fontanero-monovar',          'nombre' => 'Fontanero Monóvar'],
    ['slug' => 'fontanero-sax',              'nombre' => 'Fontanero Sax'],
    ['slug' => 'fontanero-pinoso',           'nombre' => 'Fontanero Pinoso'],
    ['slug' => 'fontanero-monforte-del-cid', 'nombre' => 'Fontanero Monforte del Cid'],
    ['slug' => 'fontanero-salinas',          'nombre' => 'Fontanero Salinas'],
    ['slug' => 'fontanero-aspe',             'nombre' => 'Fontanero Aspe'],
  ],
  'Urgencias 24h' => [
    ['slug' => 'urgencias-elda',             'nombre' => 'Urgencias Elda'],
    ['slug' => 'urgencias-petrer',           'nombre' => 'Urgencias Petrer'],
    ['slug' => 'urgencias-novelda',          'nombre' => 'Urgencias Novelda'],
    ['slug' => 'urgencias-monovar',          'nombre' => 'Urgencias Monóvar'],
    ['slug' => 'urgencias-sax',              'nombre' => 'Urgencias Sax'],
    ['slug' => 'urgencias-pinoso',           'nombre' => 'Urgencias Pinoso'],
    ['slug' => 'urgencias-monforte-del-cid', 'nombre' => 'Urgencias Monforte del Cid'],
    ['slug' => 'urgencias-salinas',          'nombre' => 'Urgencias Salinas'],
    ['slug' => 'urgencias-aspe',             'nombre' => 'Urgencias Aspe'],
  ],
  'Desatascos' => [
    ['slug' => 'desatascos-elda',             'nombre' => 'Desatascos Elda'],
    ['slug' => 'desatascos-petrer',           'nombre' => 'Desatascos Petrer'],
    ['slug' => 'desatascos-novelda',          'nombre' => 'Desatascos Novelda'],
    ['slug' => 'desatascos-monovar',          'nombre' => 'Desatascos Monóvar'],
    ['slug' => 'desatascos-sax',              'nombre' => 'Desatascos Sax'],
    ['slug' => 'desatascos-pinoso',           'nombre' => 'Desatascos Pinoso'],
    ['slug' => 'desatascos-monforte-del-cid', 'nombre' => 'Desatascos Monforte del Cid'],
    ['slug' => 'desatascos-salinas',          'nombre' => 'Desatascos Salinas'],
    ['slug' => 'desatascos-aspe',             'nombre' => 'Desatascos Aspe'],
  ],
  'Búsqueda de fugas' => [
    ['slug' => 'fugas-elda',             'nombre' => 'Búsqueda de fugas Elda'],
    ['slug' => 'fugas-petrer',           'nombre' => 'Búsqueda de fugas Petrer'],
    ['slug' => 'fugas-novelda',          'nombre' => 'Búsqueda de fugas Novelda'],
    ['slug' => 'fugas-monovar',          'nombre' => 'Búsqueda de fugas Monóvar'],
    ['slug' => 'fugas-sax',              'nombre' => 'Búsqueda de fugas Sax'],
    ['slug' => 'fugas-pinoso',           'nombre' => 'Búsqueda de fugas Pinoso'],
    ['slug' => 'fugas-monforte-del-cid', 'nombre' => 'Búsqueda de fugas Monforte del Cid'],
    ['slug' => 'fugas-salinas',          'nombre' => 'Búsqueda de fugas Salinas'],
    ['slug' => 'fugas-aspe',             'nombre' => 'Búsqueda de fugas Aspe'],
  ],
];

// Ensure all pages exist in DB (upsert nombre)
foreach ($paginas_grupos as $grupo => $paginas) {
  foreach ($paginas as $p) {
    try {
      $pdo->prepare('INSERT INTO paginas_config (slug, nombre) VALUES (?, ?) ON DUPLICATE KEY UPDATE nombre = VALUES(nombre)')
          ->execute([$p['slug'], $p['nombre']]);
    } catch (PDOException $e) {}
  }
}

$mensaje = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $accion = $_POST['accion'] ?? '';
  $slug   = trim($_POST['slug'] ?? '');

  if ($accion === 'subir' && $slug) {
    if (empty($_FILES['imagen_file']['name'])) {
      $error = 'Selecciona una imagen.';
    } else {
      $file      = $_FILES['imagen_file'];
      $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
      if (!in_array($file['type'], $permitidos)) {
        $error = 'Formato no permitido. Usa JPG, PNG o WebP.';
      } elseif ($file['size'] > 5 * 1024 * 1024) {
        $error = 'La imagen no puede superar 5MB.';
      } else {
        $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $nombre   = $slug . '.' . $ext;
        $dir      = dirname(__DIR__) . '/img/heroes/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $ruta_abs = $dir . $nombre;
        $ruta_web = 'img/heroes/' . $nombre;
        if (move_uploaded_file($file['tmp_name'], $ruta_abs)) {
          $pdo->prepare('UPDATE paginas_config SET imagen = ? WHERE slug = ?')->execute([$ruta_web, $slug]);
          syncImgToProduction($ruta_abs, $ruta_web);
          $mensaje = '✅ Imagen guardada correctamente.';
        } else {
          $error = 'Error al guardar la imagen en el servidor.';
        }
      }
    }
  } elseif ($accion === 'eliminar' && $slug) {
    $pdo->prepare('UPDATE paginas_config SET imagen = "" WHERE slug = ?')->execute([$slug]);
    $mensaje = '✅ Imagen eliminada.';
  }
}

// Cargar imágenes actuales
$imagenes = [];
try {
  $rows = $pdo->query('SELECT slug, imagen FROM paginas_config')->fetchAll();
  foreach ($rows as $r) $imagenes[$r['slug']] = $r['imagen'];
} catch (PDOException $e) {}

$total_con_imagen = count(array_filter($imagenes));
$total_paginas    = array_sum(array_map('count', $paginas_grupos));
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Imágenes de páginas — CarolTemp Admin</title>
  <meta name="robots" content="noindex, nofollow">
  <?php include '../includes/admin_style.php'; ?>
  <style>
    .pi-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:2rem; flex-wrap:wrap; gap:1rem; }
    .pi-header h1 { font-size:1.6rem; font-weight:800; color:#0B2447; margin:0; }
    .pi-stats { display:flex; gap:.75rem; }
    .pi-stat { background:#f0f4f8; border-radius:8px; padding:.5rem 1rem; font-size:13px; color:#576574; }
    .pi-stat strong { color:#0B2447; }

    .pi-section { margin-bottom:2.5rem; }
    .pi-section-title { font-size:11px; font-weight:700; color:#8FA3B8; text-transform:uppercase; letter-spacing:.08em; padding:.5rem 0; margin-bottom:1rem; border-bottom:1px solid #e8edf3; }

    .pi-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:1rem; }

    .pi-card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; transition:box-shadow .15s; }
    .pi-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.07); }

    .pi-card-img {
      width:100%; height:130px; background:#f0f4f8;
      display:flex; align-items:center; justify-content:center;
      position:relative; overflow:hidden;
    }
    .pi-card-img img { width:100%; height:100%; object-fit:cover; }
    .pi-card-img-empty { font-size:2rem; opacity:.25; }
    .pi-card-img-badge {
      position:absolute; top:6px; right:6px;
      background:rgba(0,0,0,.5); color:#fff;
      font-size:10px; font-weight:700; padding:2px 7px; border-radius:20px;
      text-transform:uppercase; letter-spacing:.05em;
    }
    .pi-card-img-badge.tiene { background:rgba(34,197,94,.85); }

    .pi-card-body { padding:.875rem; }
    .pi-card-name { font-size:13px; font-weight:700; color:#0B2447; margin-bottom:.75rem; line-height:1.3; }

    .pi-card-actions { display:flex; gap:.5rem; flex-wrap:wrap; }
    .pi-btn-upload { flex:1; background:#1e3a5f; color:#fff; border:none; border-radius:7px; padding:.45rem .6rem; font-size:12px; font-weight:600; cursor:pointer; text-align:center; white-space:nowrap; }
    .pi-btn-upload:hover { background:#163050; }
    .pi-btn-remove { background:#fff; color:#dc2626; border:1.5px solid #fca5a5; border-radius:7px; padding:.45rem .6rem; font-size:12px; font-weight:600; cursor:pointer; white-space:nowrap; text-decoration:none; }
    .pi-btn-remove:hover { background:#fef2f2; }

    .pi-form-upload { display:none; margin-top:.75rem; }
    .pi-form-upload.open { display:block; }
    .pi-file-input { display:none; }
    .pi-file-label { display:block; border:2px dashed #dde6f0; border-radius:7px; padding:.75rem; text-align:center; cursor:pointer; font-size:12px; color:#7a95b0; transition:border-color .15s; }
    .pi-file-label:hover { border-color:#1e3a5f; color:#1e3a5f; }
    .pi-preview-mini { max-width:100%; max-height:80px; border-radius:6px; margin-top:.5rem; display:none; object-fit:cover; }
    .pi-btn-save { width:100%; margin-top:.5rem; background:#16a34a; color:#fff; border:none; border-radius:7px; padding:.45rem; font-size:12px; font-weight:700; cursor:pointer; }
    .pi-btn-save:hover { background:#15803d; }

    .msg-ok  { background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:.875rem 1.25rem; border-radius:8px; margin-bottom:1.5rem; font-weight:600; }
    .msg-err { background:#fef2f2; border:1px solid #fca5a5; color:#991b1b; padding:.875rem 1.25rem; border-radius:8px; margin-bottom:1.5rem; font-weight:600; }
  </style>
</head>
<body>
<?php include '../includes/admin_sidebar.php'; ?>

<div class="admin-content">
  <div class="pi-header">
    <h1>Imágenes de páginas</h1>
    <div class="pi-stats">
      <div class="pi-stat"><strong><?php echo $total_con_imagen; ?></strong> / <?php echo $total_paginas; ?> con imagen</div>
      <div class="pi-stat"><strong><?php echo $total_paginas - $total_con_imagen; ?></strong> sin imagen</div>
    </div>
  </div>

  <?php if ($mensaje): ?><div class="msg-ok"><?php echo htmlspecialchars($mensaje); ?></div><?php endif; ?>
  <?php if ($error):   ?><div class="msg-err"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

  <?php foreach ($paginas_grupos as $grupo => $paginas): ?>
  <div class="pi-section">
    <div class="pi-section-title"><?php echo htmlspecialchars($grupo); ?></div>
    <div class="pi-grid">
      <?php foreach ($paginas as $p):
        $img     = $imagenes[$p['slug']] ?? '';
        $img_url = $img ? $base_url . $img : '';
      ?>
      <div class="pi-card">
        <div class="pi-card-img">
          <?php if ($img_url): ?>
            <img src="<?php echo htmlspecialchars($img_url); ?>" alt="">
            <span class="pi-card-img-badge tiene">✓ Imagen</span>
          <?php else: ?>
            <span class="pi-card-img-empty">🖼️</span>
            <span class="pi-card-img-badge">Sin imagen</span>
          <?php endif; ?>
        </div>
        <div class="pi-card-body">
          <div class="pi-card-name"><?php echo htmlspecialchars($p['nombre']); ?></div>
          <div class="pi-card-actions">
            <button type="button" class="pi-btn-upload" onclick="toggleUpload('<?php echo $p['slug']; ?>')">
              <?php echo $img ? '🔄 Cambiar' : '⬆️ Subir imagen'; ?>
            </button>
            <?php if ($img): ?>
            <form method="post" style="margin:0" onsubmit="return confirm('¿Eliminar imagen?')">
              <input type="hidden" name="accion" value="eliminar">
              <input type="hidden" name="slug"   value="<?php echo htmlspecialchars($p['slug']); ?>">
              <button type="submit" class="pi-btn-remove">🗑️</button>
            </form>
            <?php endif; ?>
          </div>

          <div class="pi-form-upload" id="upload-<?php echo $p['slug']; ?>">
            <form method="post" enctype="multipart/form-data">
              <input type="hidden" name="accion" value="subir">
              <input type="hidden" name="slug"   value="<?php echo htmlspecialchars($p['slug']); ?>">
              <label class="pi-file-label" for="file-<?php echo $p['slug']; ?>">
                📁 Elegir imagen (JPG, PNG, WebP · máx 5MB)
              </label>
              <input type="file" id="file-<?php echo $p['slug']; ?>" name="imagen_file" class="pi-file-input" accept="image/jpeg,image/png,image/webp"
                onchange="previewMini(this, '<?php echo $p['slug']; ?>')">
              <img id="prev-<?php echo $p['slug']; ?>" class="pi-preview-mini" alt="Vista previa">
              <button type="submit" class="pi-btn-save">Guardar imagen</button>
            </form>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<script>
function toggleUpload(slug) {
  var el = document.getElementById('upload-' + slug);
  el.classList.toggle('open');
}
function previewMini(input, slug) {
  var prev = document.getElementById('prev-' + slug);
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      prev.src = e.target.result;
      prev.style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
</body>
</html>
