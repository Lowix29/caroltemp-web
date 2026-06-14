<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php'); exit;
}
require_once '../includes/db.php';

$base_url = $is_local ? 'http://localhost/caroltemp/' : 'https://caroltemp.com/';
$pagina_actual = 'galeria.php';

$mensaje = '';
$error   = '';

// ── CREAR TABLA SI NO EXISTE ──────────────────────────────────────────────────
$pdo->exec("CREATE TABLE IF NOT EXISTS galeria (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(300) NOT NULL,
  alt_text VARCHAR(300) NOT NULL,
  imagen VARCHAR(500) NOT NULL,
  ciudad VARCHAR(50) DEFAULT NULL,
  servicio VARCHAR(50) DEFAULT NULL,
  visible TINYINT DEFAULT 1,
  orden INT DEFAULT 0,
  creado DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// ── CONSTANTES ────────────────────────────────────────────────────────────────
$ciudades  = ['elda','petrer','novelda','monovar','sax','pinoso','monforte-del-cid','salinas','aspe'];
$servicios = ['fugas','desatascos','fontanero','urgencias'];

// ── TOGGLE VISIBLE ────────────────────────────────────────────────────────────
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
  $pdo->prepare('UPDATE galeria SET visible = IF(visible=1,0,1) WHERE id = ?')->execute([$_GET['toggle']]);
  $params_keep = array_intersect_key($_GET, array_flip(['ciudad','servicio','visible']));
  header('Location: galeria.php' . ($params_keep ? '?' . http_build_query($params_keep) : '')); exit;
}

// ── ELIMINAR ──────────────────────────────────────────────────────────────────
if (isset($_GET['eliminar']) && is_numeric($_GET['eliminar'])) {
  $row = $pdo->prepare('SELECT imagen FROM galeria WHERE id = ?');
  $row->execute([$_GET['eliminar']]);
  $img_row = $row->fetch();
  // Solo eliminar el archivo si está en img/galeria/ (las de medios se gestionan desde medios.php)
  if ($img_row && $img_row['imagen'] && strpos($img_row['imagen'], 'img/galeria/') === 0) {
    $img_path = dirname(__DIR__) . '/' . $img_row['imagen'];
    if (file_exists($img_path)) @unlink($img_path);
  }
  $pdo->prepare('DELETE FROM galeria WHERE id = ?')->execute([$_GET['eliminar']]);
  $mensaje = '✅ Imagen eliminada de la galería.';
}

// ── EDITAR IMAGEN ────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'editar') {
  $id       = (int)($_POST['id'] ?? 0);
  $titulo   = trim($_POST['titulo'] ?? '');
  $alt_text = trim($_POST['alt_text'] ?? '');
  $ciudad   = in_array($_POST['ciudad'] ?? '', $ciudades) ? $_POST['ciudad'] : null;
  $servicio = in_array($_POST['servicio'] ?? '', $servicios) ? $_POST['servicio'] : null;
  if ($id && $titulo !== '') {
    $pdo->prepare('UPDATE galeria SET titulo=?, alt_text=?, ciudad=?, servicio=? WHERE id=?')
        ->execute([$titulo, $alt_text, $ciudad, $servicio, $id]);
    $mensaje = '✅ Imagen actualizada correctamente.';
  } else {
    $error = 'El título es obligatorio.';
  }
}

// ── SUBIR IMAGEN (va también a medios) ───────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'upload') {
  $titulo   = trim($_POST['titulo'] ?? '');
  $alt_text = trim($_POST['alt_text'] ?? '');
  $ciudad   = in_array($_POST['ciudad'] ?? '', $ciudades) ? $_POST['ciudad'] : null;
  $servicio = in_array($_POST['servicio'] ?? '', $servicios) ? $_POST['servicio'] : null;

  if ($titulo === '') {
    $error = 'El título es obligatorio.';
  } elseif (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
    $error = 'Debes seleccionar una imagen válida.';
  } else {
    $allowed_types = ['image/jpeg','image/png','image/webp','image/gif','image/avif'];
    $ftype = mime_content_type($_FILES['imagen']['tmp_name']);
    if (!in_array($ftype, $allowed_types)) {
      $error = 'Tipo de archivo no permitido. Solo se aceptan imágenes (jpg, png, webp, gif, avif).';
    } elseif ($_FILES['imagen']['size'] > 20 * 1024 * 1024) {
      $error = 'La imagen supera el límite de 20 MB.';
    } else {
      // Guardar en img/uploads/ (biblioteca de medios)
      $ext      = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION)) ?: 'jpg';
      $basename = preg_replace('/[^a-z0-9\-]/', '', strtolower(str_replace(' ', '-', pathinfo($_FILES['imagen']['name'], PATHINFO_FILENAME)))) ?: 'imagen';
      $dir      = dirname(__DIR__) . '/img/uploads/';
      if (!is_dir($dir)) mkdir($dir, 0755, true);

      $filename = $basename . '.' . $ext;
      $i = 1;
      while (file_exists($dir . $filename)) { $filename = $basename . '-' . $i++ . '.' . $ext; }

      if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $dir . $filename)) {
        $error = 'Error al guardar el archivo. Comprueba permisos de img/uploads/.';
      } else {
        $ruta_web = 'img/uploads/' . $filename;

        if ($alt_text === '') {
          $alt_text = 'Trabajo de ' . ($servicio ?? 'fontanería') . ' en ' . ($ciudad ?? 'la zona') . ' — CarolTemp';
        }

        // Insertar en biblioteca de medios
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
          $pdo->prepare('INSERT INTO medios (ruta, nombre_archivo, titulo, alt, mime_type, tamano) VALUES (?,?,?,?,?,?)')
              ->execute([$ruta_web, $filename, $titulo, $alt_text, $ftype, $_FILES['imagen']['size']]);
        } catch (Exception $e) { /* medios es opcional */ }

        // Insertar en galería
        $pdo->prepare('INSERT INTO galeria (titulo, alt_text, imagen, ciudad, servicio, visible, orden) VALUES (?,?,?,?,?,1,0)')
            ->execute([$titulo, $alt_text, $ruta_web, $ciudad, $servicio]);
        $mensaje = '✅ Imagen subida y añadida a la galería y a la biblioteca de medios.';
      }
    }
  }
}

// ── AÑADIR DESDE MEDIOS ───────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'desde_medios') {
  $titulo   = trim($_POST['titulo'] ?? '');
  $alt_text = trim($_POST['alt_text'] ?? '');
  $ruta_web = trim($_POST['ruta_web'] ?? '');
  $ciudad   = in_array($_POST['ciudad'] ?? '', $ciudades) ? $_POST['ciudad'] : null;
  $servicio = in_array($_POST['servicio'] ?? '', $servicios) ? $_POST['servicio'] : null;

  if ($titulo === '' || $ruta_web === '') {
    $error = 'Faltan datos. Selecciona una imagen y añade un título.';
  } else {
    if ($alt_text === '') {
      $alt_text = 'Trabajo de ' . ($servicio ?? 'fontanería') . ' en ' . ($ciudad ?? 'la zona') . ' — CarolTemp';
    }
    $pdo->prepare('INSERT INTO galeria (titulo, alt_text, imagen, ciudad, servicio, visible, orden) VALUES (?,?,?,?,?,1,0)')
        ->execute([$titulo, $alt_text, $ruta_web, $ciudad, $servicio]);
    $mensaje = '✅ Imagen de la biblioteca añadida a la galería.';
  }
}

// ── STATS ─────────────────────────────────────────────────────────────────────
$total_imgs   = (int)$pdo->query('SELECT COUNT(*) FROM galeria')->fetchColumn();
$total_vis    = (int)$pdo->query('SELECT COUNT(*) FROM galeria WHERE visible=1')->fetchColumn();
$stats_ciudad = $pdo->query("SELECT ciudad, COUNT(*) as n FROM galeria WHERE ciudad IS NOT NULL GROUP BY ciudad ORDER BY n DESC LIMIT 5")->fetchAll();

// ── FILTROS ───────────────────────────────────────────────────────────────────
$f_ciudad   = $_GET['ciudad']   ?? '';
$f_servicio = $_GET['servicio'] ?? '';
$f_visible  = $_GET['visible']  ?? '';

$where  = ['1=1'];
$params = [];
if ($f_ciudad)            { $where[] = 'ciudad = ?';   $params[] = $f_ciudad; }
if ($f_servicio)          { $where[] = 'servicio = ?'; $params[] = $f_servicio; }
if ($f_visible !== '')    { $where[] = 'visible = ?';  $params[] = (int)$f_visible; }
$where_sql = implode(' AND ', $where);

$stmt = $pdo->prepare("SELECT * FROM galeria WHERE $where_sql ORDER BY orden ASC, creado DESC");
$stmt->execute($params);
$imagenes = $stmt->fetchAll();

function gal_url($extra = []) {
  global $f_ciudad, $f_servicio, $f_visible;
  $base = ['ciudad' => $f_ciudad, 'servicio' => $f_servicio, 'visible' => $f_visible];
  $merged = array_merge($base, $extra);
  $q = http_build_query(array_filter($merged, function($v){ return $v !== ''; }));
  return 'galeria.php' . ($q ? '?' . $q : '');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Galería de trabajos — CarolTemp Admin</title>
  <meta name="robots" content="noindex,nofollow">
  <?php include '../includes/admin_style.php'; ?>
  <style>
    .stats-bar { display:grid; grid-template-columns:repeat(auto-fit,minmax(140px,1fr)); gap:1rem; margin-bottom:1.5rem; }
    .stat-box { background:#fff; border:1.5px solid #e2e8f0; border-radius:10px; padding:1rem 1.25rem; }
    .stat-box .sv { font-size:1.6rem; font-weight:800; color:#0f172a; line-height:1; }
    .stat-box .sl { font-size:12px; color:#64748b; margin-top:3px; }

    .tabs { display:flex; gap:0; margin-bottom:0; border-bottom:2px solid #e2e8f0; }
    .tab-btn { background:none; border:none; padding:.75rem 1.25rem; font-size:13px; font-weight:700; color:#64748b; cursor:pointer; border-bottom:2px solid transparent; margin-bottom:-2px; transition:all .15s; }
    .tab-btn.active { color:#3b5bdb; border-bottom-color:#3b5bdb; }
    .tab-btn:hover:not(.active) { color:#374151; }

    .upload-card { background:#fff; border:1.5px solid #e2e8f0; border-radius:12px; overflow:hidden; margin-bottom:1.5rem; }
    .upload-card-head { padding:.9rem 1.25rem; font-weight:700; font-size:14px; color:#0f172a; border-bottom:1px solid #f1f5f9; }
    .tab-panel { display:none; }
    .tab-panel.active { display:block; }
    .upload-body { padding:1.25rem; }
    .form-row { display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem; }
    .form-row-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; margin-bottom:1rem; }
    .form-group { display:flex; flex-direction:column; gap:5px; }
    .form-group label { font-size:12px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.05em; }
    .form-group input, .form-group select, .form-group textarea { border:1.5px solid #e2e8f0; border-radius:7px; padding:8px 11px; font-size:13px; font-family:inherit; }
    .form-group input:focus, .form-group select:focus { outline:none; border-color:#3b5bdb; }
    .file-drop { border:2px dashed #cbd5e1; border-radius:10px; padding:2rem; text-align:center; color:#94a3b8; font-size:13px; transition:border-color .2s; cursor:pointer; }
    .file-drop:hover { border-color:#3b5bdb; color:#3b5bdb; }
    .file-drop input[type=file] { display:none; }

    .medios-preview-wrap { border:2px dashed #cbd5e1; border-radius:10px; padding:1.5rem; text-align:center; cursor:pointer; transition:border-color .2s; min-height:100px; display:flex; align-items:center; justify-content:center; flex-direction:column; gap:.5rem; }
    .medios-preview-wrap:hover { border-color:#3b5bdb; }
    .medios-preview-img { max-width:180px; max-height:120px; border-radius:8px; object-fit:cover; display:none; }
    .medios-preview-label { font-size:13px; color:#94a3b8; }
    .medios-selected-name { font-size:12px; color:#374151; font-weight:600; margin-top:.25rem; display:none; }

    .filter-bar { display:flex; gap:.5rem; align-items:center; flex-wrap:wrap; margin-bottom:1rem; }
    .filter-bar select { border:1.5px solid #e2e8f0; border-radius:6px; padding:6px 10px; font-size:13px; font-family:inherit; }
    .filter-bar select:focus { outline:none; border-color:#3b5bdb; }

    .gal-admin-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:1rem; }
    .gal-item { background:#fff; border:1.5px solid #e2e8f0; border-radius:10px; overflow:hidden; }
    .gal-item img { width:100%; height:130px; object-fit:cover; display:block; }
    .gal-item-body { padding:.6rem .75rem; }
    .gal-item-title { font-size:12.5px; font-weight:700; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-bottom:3px; }
    .gal-item-alt { font-size:11px; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-bottom:6px; }
    .gal-item-tags { display:flex; gap:4px; flex-wrap:wrap; margin-bottom:6px; }
    .tag-ciudad { background:#f1f5f9; color:#475569; font-size:10.5px; padding:2px 7px; border-radius:10px; }
    .tag-serv   { background:#ede9fe; color:#6d28d9; font-size:10.5px; padding:2px 7px; border-radius:10px; }
    .gal-item-actions { display:flex; gap:.5rem; align-items:center; }
    .badge-vis { display:inline-block; padding:2px 9px; border-radius:20px; font-size:11px; font-weight:700; cursor:pointer; text-decoration:none; }
    .badge-vis.si { background:#dcfce7; color:#166534; }
    .badge-vis.no { background:#fee2e2; color:#991b1b; }
    .btn-edit { background:none; border:none; font-size:11px; color:#3b5bdb; cursor:pointer; padding:0; font-family:inherit; font-weight:600; }
    .btn-edit:hover { text-decoration:underline; }
    .btn-del { background:none; border:none; font-size:11px; color:#dc2626; cursor:pointer; padding:0; font-family:inherit; font-weight:600; }
    .btn-del:hover { text-decoration:underline; }
    .gal-empty { text-align:center; color:#94a3b8; padding:3rem; font-size:14px; }
    .preview-thumb { max-width:100%; max-height:120px; border-radius:7px; margin-top:.5rem; display:none; }

    .confirm-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:1000; align-items:center; justify-content:center; }
    .confirm-overlay.open { display:flex; }
    .confirm-box { background:#fff; border-radius:14px; padding:2rem; width:400px; max-width:95vw; box-shadow:0 20px 60px rgba(0,0,0,.3); text-align:center; }
    .confirm-box h3 { font-size:17px; font-weight:800; color:#0f172a; margin:0 0 .5rem; }
    .confirm-box p { font-size:13px; color:#64748b; margin:0 0 1.5rem; }
    .confirm-btns { display:flex; gap:.75rem; justify-content:center; }
    .btn-cancelar { background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:7px; padding:8px 20px; font-size:13px; font-weight:600; cursor:pointer; text-decoration:none; color:#374151; }
    .btn-confirmar { background:#dc2626; border:none; border-radius:7px; padding:8px 20px; font-size:13px; font-weight:700; cursor:pointer; text-decoration:none; color:#fff; }
    .btn-cancelar:hover { border-color:#94a3b8; }
    .btn-confirmar:hover { background:#b91c1c; }
  </style>
</head>
<body>
<?php include '../includes/admin_sidebar.php'; ?>
<main class="main">

  <div class="topbar">
    <h1>📸 Galería de trabajos</h1>
  </div>

  <?php if ($mensaje): ?><div class="mensaje"><?php echo $mensaje; ?></div><?php endif; ?>
  <?php if ($error):   ?><div class="error-msg"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

  <!-- STATS BAR -->
  <div class="stats-bar">
    <div class="stat-box">
      <div class="sv"><?php echo $total_imgs; ?></div>
      <div class="sl">Total imágenes</div>
    </div>
    <div class="stat-box">
      <div class="sv"><?php echo $total_vis; ?></div>
      <div class="sl">Visibles</div>
    </div>
    <div class="stat-box">
      <div class="sv"><?php echo $total_imgs - $total_vis; ?></div>
      <div class="sl">Ocultas</div>
    </div>
    <?php foreach ($stats_ciudad as $sc): ?>
    <div class="stat-box">
      <div class="sv" style="font-size:1.1rem"><?php echo $sc['n']; ?></div>
      <div class="sl"><?php echo htmlspecialchars(ucfirst(str_replace('-',' ',$sc['ciudad']))); ?></div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- AÑADIR IMAGEN -->
  <div class="upload-card">
    <div class="upload-card-head">
      <div class="tabs">
        <button class="tab-btn active" onclick="switchTab('subir',this)">⬆️ Subir nueva imagen</button>
        <button class="tab-btn" onclick="switchTab('medios',this)">🖼️ Elegir de la biblioteca</button>
      </div>
    </div>

    <!-- TAB: SUBIR -->
    <div class="tab-panel active upload-body" id="tab-subir">
      <form method="POST" enctype="multipart/form-data" id="form-upload">
        <input type="hidden" name="action" value="upload">

        <div class="form-group" style="margin-bottom:1rem">
          <label>Imagen *</label>
          <div class="file-drop" onclick="document.getElementById('file-input').click()">
            <div id="file-placeholder">📷 Haz clic para seleccionar imagen (jpg, png, webp — máx. 20 MB)<br><small style="color:#b0bec5">La imagen se guardará también en la Biblioteca de medios</small></div>
            <input type="file" id="file-input" name="imagen" accept="image/*" onchange="previewFile(this)">
            <img id="preview-thumb" class="preview-thumb" src="" alt="Vista previa">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Título (caption) *</label>
            <input type="text" name="titulo" id="f_titulo" required placeholder="Ej: Reparación de fuga en cocina">
          </div>
          <div class="form-group">
            <label>Alt text (SEO)</label>
            <input type="text" name="alt_text" id="f_alt_text" placeholder="Se genera automáticamente si lo dejas vacío">
          </div>
        </div>

        <div class="form-row-3">
          <div class="form-group">
            <label>Ciudad</label>
            <select name="ciudad" id="f_ciudad" onchange="actualizarAlt()">
              <option value="">— Sin ciudad —</option>
              <?php foreach ($ciudades as $c): ?>
                <option value="<?php echo $c; ?>"><?php echo ucfirst(str_replace('-',' ',$c)); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Servicio</label>
            <select name="servicio" id="f_servicio" onchange="actualizarAlt()">
              <option value="">— Sin servicio —</option>
              <?php foreach ($servicios as $s): ?>
                <option value="<?php echo $s; ?>"><?php echo ucfirst($s); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group" style="justify-content:flex-end">
            <label>&nbsp;</label>
            <button type="submit" class="btn-new" style="font-size:13px;padding:9px 20px">📤 Subir imagen</button>
          </div>
        </div>
      </form>
    </div>

    <!-- TAB: DESDE MEDIOS -->
    <div class="tab-panel upload-body" id="tab-medios">
      <form method="POST" id="form-medios">
        <input type="hidden" name="action" value="desde_medios">
        <input type="hidden" name="ruta_web" id="m_ruta_web">

        <div class="form-group" style="margin-bottom:1rem">
          <label>Imagen de la biblioteca *</label>
          <div class="medios-preview-wrap" onclick="abrirSelectorMedios()">
            <img id="m-preview-img" class="medios-preview-img" src="" alt="">
            <p class="medios-preview-label" id="m-preview-label">🖼️ Haz clic para elegir de la Biblioteca de medios</p>
            <p class="medios-selected-name" id="m-selected-name"></p>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Título (caption) *</label>
            <input type="text" name="titulo" id="m_titulo" required placeholder="Ej: Cambio de tuberías en baño">
          </div>
          <div class="form-group">
            <label>Alt text (SEO)</label>
            <input type="text" name="alt_text" id="m_alt_text" placeholder="Se genera automáticamente si lo dejas vacío">
          </div>
        </div>

        <div class="form-row-3">
          <div class="form-group">
            <label>Ciudad</label>
            <select name="ciudad" id="m_ciudad" onchange="actualizarAltMedios()">
              <option value="">— Sin ciudad —</option>
              <?php foreach ($ciudades as $c): ?>
                <option value="<?php echo $c; ?>"><?php echo ucfirst(str_replace('-',' ',$c)); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Servicio</label>
            <select name="servicio" id="m_servicio" onchange="actualizarAltMedios()">
              <option value="">— Sin servicio —</option>
              <?php foreach ($servicios as $s): ?>
                <option value="<?php echo $s; ?>"><?php echo ucfirst($s); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group" style="justify-content:flex-end">
            <label>&nbsp;</label>
            <button type="submit" class="btn-new" style="font-size:13px;padding:9px 20px">➕ Añadir a galería</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- FILTROS -->
  <div class="filter-bar">
    <form method="GET" style="display:contents">
      <select name="ciudad" onchange="this.form.submit()">
        <option value="">Todas las ciudades</option>
        <?php foreach ($ciudades as $c): ?>
          <option value="<?php echo $c; ?>" <?php echo $f_ciudad===$c?'selected':''; ?>><?php echo ucfirst(str_replace('-',' ',$c)); ?></option>
        <?php endforeach; ?>
      </select>
      <select name="servicio" onchange="this.form.submit()">
        <option value="">Todos los servicios</option>
        <?php foreach ($servicios as $s): ?>
          <option value="<?php echo $s; ?>" <?php echo $f_servicio===$s?'selected':''; ?>><?php echo ucfirst($s); ?></option>
        <?php endforeach; ?>
      </select>
      <select name="visible" onchange="this.form.submit()">
        <option value="">Visibilidad</option>
        <option value="1" <?php echo $f_visible==='1'?'selected':''; ?>>Visible</option>
        <option value="0" <?php echo $f_visible==='0'?'selected':''; ?>>Oculta</option>
      </select>
      <?php if ($f_ciudad || $f_servicio || $f_visible !== ''): ?>
        <a href="galeria.php" style="font-size:13px;color:#64748b;text-decoration:none;padding:6px 4px">✕ Limpiar</a>
      <?php endif; ?>
    </form>
    <span style="font-size:12.5px;color:#94a3b8;margin-left:auto"><?php echo count($imagenes); ?> imagen<?php echo count($imagenes)!==1?'es':''; ?></span>
  </div>

  <!-- GRID DE IMÁGENES -->
  <div class="card" style="padding:1.25rem">
    <?php if (empty($imagenes)): ?>
      <div class="gal-empty">No hay imágenes<?php echo ($f_ciudad||$f_servicio||$f_visible!=='') ? ' con los filtros seleccionados' : ' en la galería aún'; ?>.</div>
    <?php else: ?>
    <div class="gal-admin-grid">
      <?php foreach ($imagenes as $img):
        // Compatibilidad: si imagen empieza por img/ es ruta completa, si no es legacy img/galeria/
        $img_url = $base_url . (strpos($img['imagen'], 'img/') === 0 ? $img['imagen'] : 'img/galeria/' . basename($img['imagen']));
      ?>
      <div class="gal-item">
        <img src="<?php echo htmlspecialchars($img_url); ?>"
             alt="<?php echo htmlspecialchars($img['alt_text']); ?>"
             loading="lazy">
        <div class="gal-item-body">
          <div class="gal-item-title" title="<?php echo htmlspecialchars($img['titulo']); ?>"><?php echo htmlspecialchars($img['titulo']); ?></div>
          <div class="gal-item-alt" title="<?php echo htmlspecialchars($img['alt_text']); ?>"><?php echo htmlspecialchars(mb_substr($img['alt_text'],0,55)) . (mb_strlen($img['alt_text'])>55?'…':''); ?></div>
          <div class="gal-item-tags">
            <?php if ($img['ciudad']): ?><span class="tag-ciudad"><?php echo htmlspecialchars($img['ciudad']); ?></span><?php endif; ?>
            <?php if ($img['servicio']): ?><span class="tag-serv"><?php echo htmlspecialchars($img['servicio']); ?></span><?php endif; ?>
          </div>
          <div class="gal-item-actions">
            <a href="<?php echo gal_url(['toggle'=>$img['id']]); ?>" class="badge-vis <?php echo $img['visible']?'si':'no'; ?>">
              <?php echo $img['visible'] ? 'Visible' : 'Oculta'; ?>
            </a>
            <button class="btn-edit" onclick="abrirEditar(<?php echo $img['id']; ?>,'<?php echo htmlspecialchars(addslashes($img['titulo'])); ?>','<?php echo htmlspecialchars(addslashes($img['alt_text'])); ?>','<?php echo $img['ciudad'] ?? ''; ?>','<?php echo $img['servicio'] ?? ''; ?>')">Editar</button>
            <button class="btn-del" onclick="confirmarEliminar(<?php echo $img['id']; ?>,'<?php echo htmlspecialchars(addslashes($img['titulo'])); ?>')">Eliminar</button>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>

</main>

<!-- EDIT OVERLAY -->
<div class="confirm-overlay" id="edit-overlay">
  <div class="confirm-box" style="text-align:left;width:500px">
    <h3 style="margin-bottom:1.25rem">✏️ Editar imagen</h3>
    <form method="POST" id="form-editar">
      <input type="hidden" name="action" value="editar">
      <input type="hidden" name="id" id="edit-id">
      <div class="form-group" style="margin-bottom:.85rem">
        <label>Título *</label>
        <input type="text" name="titulo" id="edit-titulo" required>
      </div>
      <div class="form-group" style="margin-bottom:.85rem">
        <label>Alt text (SEO)</label>
        <input type="text" name="alt_text" id="edit-alt_text">
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem">
        <div class="form-group">
          <label>Ciudad</label>
          <select name="ciudad" id="edit-ciudad">
            <option value="">— Sin ciudad —</option>
            <?php foreach ($ciudades as $c): ?>
              <option value="<?php echo $c; ?>"><?php echo ucfirst(str_replace('-',' ',$c)); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Servicio</label>
          <select name="servicio" id="edit-servicio">
            <option value="">— Sin servicio —</option>
            <?php foreach ($servicios as $s): ?>
              <option value="<?php echo $s; ?>"><?php echo ucfirst($s); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="confirm-btns">
        <a href="#" class="btn-cancelar" onclick="cerrarEditar();return false">Cancelar</a>
        <button type="submit" class="btn-confirmar" style="background:#3b5bdb">Guardar cambios</button>
      </div>
    </form>
  </div>
</div>

<!-- CONFIRM OVERLAY -->
<div class="confirm-overlay" id="confirm-overlay">
  <div class="confirm-box">
    <h3>¿Eliminar de la galería?</h3>
    <p id="confirm-texto"></p>
    <div class="confirm-btns">
      <a href="#" class="btn-cancelar" onclick="cerrarConfirm();return false">Cancelar</a>
      <a href="#" class="btn-confirmar" id="confirm-link">Eliminar</a>
    </div>
  </div>
</div>

<script>
var BASE_URL = '<?php echo rtrim($base_url, '/'); ?>';

/* ── TABS ── */
function switchTab(name, btn) {
  document.querySelectorAll('.tab-panel').forEach(function(p){ p.classList.remove('active'); });
  document.querySelectorAll('.tab-btn').forEach(function(b){ b.classList.remove('active'); });
  document.getElementById('tab-' + name).classList.add('active');
  btn.classList.add('active');
}

/* ── SUBIR: PREVIEW ── */
function previewFile(input) {
  var thumb = document.getElementById('preview-thumb');
  var placeholder = document.getElementById('file-placeholder');
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      thumb.src = e.target.result;
      thumb.style.display = 'block';
      placeholder.style.display = 'none';
    };
    reader.readAsDataURL(input.files[0]);
  }
}

function actualizarAlt() {
  var ciudad   = document.getElementById('f_ciudad').value;
  var servicio = document.getElementById('f_servicio').value;
  var alt      = document.getElementById('f_alt_text');
  if (alt.value === '' || alt.dataset.auto === '1') {
    alt.value = 'Trabajo de ' + (servicio||'fontanería') + ' en ' + (ciudad||'la zona') + ' — CarolTemp';
    alt.dataset.auto = '1';
  }
}
document.getElementById('f_alt_text').addEventListener('input', function() { this.dataset.auto = '0'; });

/* ── MEDIOS SELECTOR ── */
function actualizarAltMedios() {
  var ciudad   = document.getElementById('m_ciudad').value;
  var servicio = document.getElementById('m_servicio').value;
  var alt      = document.getElementById('m_alt_text');
  if (alt.value === '' || alt.dataset.auto === '1') {
    alt.value = 'Trabajo de ' + (servicio||'fontanería') + ' en ' + (ciudad||'la zona') + ' — CarolTemp';
    alt.dataset.auto = '1';
  }
}
document.getElementById('m_alt_text').addEventListener('input', function() { this.dataset.auto = '0'; });

function abrirSelectorMedios() {
  var popup = window.open('medios.php?modo=selector', 'selector_medios', 'width=900,height=620,scrollbars=yes,resizable=yes');
  window.addEventListener('message', function handler(e) {
    if (!e.data || e.data.tipo !== 'media_select') return;
    window.removeEventListener('message', handler);
    var data = e.data;
    document.getElementById('m_ruta_web').value = data.ruta;

    var img = document.getElementById('m-preview-img');
    img.src = BASE_URL + '/' + data.ruta;
    img.style.display = 'block';
    document.getElementById('m-preview-label').style.display = 'none';
    document.getElementById('m-selected-name').textContent = data.titulo || data.nombre_archivo;
    document.getElementById('m-selected-name').style.display = 'block';

    if (!document.getElementById('m_titulo').value && data.titulo) {
  var t = data.titulo.replace(/[-_]/g, ' ');
  t = t.charAt(0).toUpperCase() + t.slice(1);
  document.getElementById('m_titulo').value = t;
}
    if (!document.getElementById('m_alt_text').value && data.alt) {
      document.getElementById('m_alt_text').value = data.alt;
    }
  });
}

/* ── EDIT ── */
function abrirEditar(id, titulo, alt, ciudad, servicio) {
  document.getElementById('edit-id').value        = id;
  document.getElementById('edit-titulo').value    = titulo;
  document.getElementById('edit-alt_text').value  = alt;
  document.getElementById('edit-ciudad').value    = ciudad;
  document.getElementById('edit-servicio').value  = servicio;
  document.getElementById('edit-overlay').classList.add('open');
}
function cerrarEditar() { document.getElementById('edit-overlay').classList.remove('open'); }
document.getElementById('edit-overlay').addEventListener('click', function(e){ if(e.target===this) cerrarEditar(); });

/* ── CONFIRM DELETE ── */
function confirmarEliminar(id, titulo) {
  document.getElementById('confirm-texto').textContent = '¿Seguro que quieres quitar "' + titulo + '" de la galería?';
  var base = '<?php echo gal_url(); ?>';
  var sep  = base.indexOf('?') !== -1 ? '&' : '?';
  document.getElementById('confirm-link').href = base + sep + 'eliminar=' + id;
  document.getElementById('confirm-overlay').classList.add('open');
}
function cerrarConfirm() { document.getElementById('confirm-overlay').classList.remove('open'); }
document.getElementById('confirm-overlay').addEventListener('click', function(e){ if(e.target===this) cerrarConfirm(); });
</script>
</body>
</html>
