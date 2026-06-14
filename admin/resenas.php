<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php'); exit;
}
require_once '../includes/db.php';

$base_url = $is_local ? 'http://localhost/caroltemp/' : 'https://' . $_SERVER['HTTP_HOST'] . '/';
$pagina_actual = 'resenas.php';

$mensaje = '';
$error   = '';

// ── CREAR TABLAS SI NO EXISTEN ────────────────────────────────────────────────
$pdo->exec("CREATE TABLE IF NOT EXISTS resenas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(200) NOT NULL,
  texto TEXT NOT NULL,
  estrellas TINYINT NOT NULL DEFAULT 5,
  fecha DATE,
  ciudad VARCHAR(50) DEFAULT NULL,
  servicio VARCHAR(50) DEFAULT NULL,
  fuente ENUM('google','manual') DEFAULT 'manual',
  google_autor_url VARCHAR(500) DEFAULT NULL,
  foto_autor VARCHAR(500) DEFAULT NULL,
  visible TINYINT DEFAULT 1,
  creado DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$pdo->exec("CREATE TABLE IF NOT EXISTS resenas_config (
  id INT AUTO_INCREMENT PRIMARY KEY,
  google_place_id VARCHAR(200) DEFAULT '',
  google_api_key VARCHAR(200) DEFAULT '',
  google_reviews_url VARCHAR(500) DEFAULT '',
  ultima_sync DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Insertar fila por defecto si está vacía
$cfg_count = (int)$pdo->query('SELECT COUNT(*) FROM resenas_config')->fetchColumn();
if ($cfg_count === 0) {
  $pdo->exec("INSERT INTO resenas_config (google_place_id, google_api_key, google_reviews_url, ultima_sync) VALUES ('ChIJNYRvY6aWGwgRGyPqyX1xfo0', '', '', NULL)");
}

// ── CONSTANTES ────────────────────────────────────────────────────────────────
$ciudades  = ['elda','petrer','novelda','monovar','sax','pinoso','monforte-del-cid','salinas','aspe'];
$servicios = ['fugas','desatascos','fontanero','urgencias'];

// ── GUARDAR CONFIGURACIÓN ─────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_config') {
  $place_id  = trim($_POST['google_place_id'] ?? '');
  $api_key   = trim($_POST['google_api_key'] ?? '');
  $rev_url   = trim($_POST['google_reviews_url'] ?? '');

  // Si el campo api_key viene con asteriscos (enmascarado), no actualizar
  if (strpos($api_key, '****') !== false || $api_key === '') {
    // Solo actualizar place_id y reviews_url
    $pdo->prepare('UPDATE resenas_config SET google_place_id=?, google_reviews_url=? WHERE id=1')
        ->execute([$place_id, $rev_url]);
  } else {
    $pdo->prepare('UPDATE resenas_config SET google_place_id=?, google_api_key=?, google_reviews_url=? WHERE id=1')
        ->execute([$place_id, $api_key, $rev_url]);
  }
  $mensaje = '✅ Configuración guardada correctamente.';
}

// ── TOGGLE VISIBLE ────────────────────────────────────────────────────────────
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
  $pdo->prepare('UPDATE resenas SET visible = IF(visible=1,0,1) WHERE id = ?')->execute([$_GET['toggle']]);
  header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?') . '?' . http_build_query(array_diff_key($_GET, ['toggle' => '']))); exit;
}

// ── ELIMINAR ──────────────────────────────────────────────────────────────────
if (isset($_GET['eliminar']) && is_numeric($_GET['eliminar'])) {
  $pdo->prepare('DELETE FROM resenas WHERE id = ?')->execute([$_GET['eliminar']]);
  $mensaje = '✅ Reseña eliminada correctamente.';
}

// ── GUARDAR RESEÑA MANUAL (nueva o edición) ───────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_resena') {
  $edit_id  = isset($_POST['edit_id']) && is_numeric($_POST['edit_id']) ? (int)$_POST['edit_id'] : 0;
  $nombre   = trim($_POST['nombre'] ?? '');
  $texto    = trim($_POST['texto'] ?? '');
  $estrellas= max(1, min(5, (int)($_POST['estrellas'] ?? 5)));
  $fecha    = $_POST['fecha'] ?? '';
  $ciudad   = in_array($_POST['ciudad'] ?? '', $ciudades) ? $_POST['ciudad'] : null;
  $servicio = in_array($_POST['servicio'] ?? '', $servicios) ? $_POST['servicio'] : null;

  if ($nombre === '' || $texto === '') {
    $error = 'El nombre y el texto son obligatorios.';
  } else {
    if ($edit_id > 0) {
      $pdo->prepare('UPDATE resenas SET nombre=?, texto=?, estrellas=?, fecha=?, ciudad=?, servicio=? WHERE id=?')
          ->execute([$nombre, $texto, $estrellas, $fecha ?: null, $ciudad, $servicio, $edit_id]);
      $mensaje = '✅ Reseña actualizada correctamente.';
    } else {
      $pdo->prepare('INSERT INTO resenas (nombre, texto, estrellas, fecha, ciudad, servicio, fuente, visible) VALUES (?,?,?,?,?,?,\'manual\',1)')
          ->execute([$nombre, $texto, $estrellas, $fecha ?: null, $ciudad, $servicio]);
      $mensaje = '✅ Reseña manual añadida correctamente.';
    }
  }
}

// ── SINCRONIZAR CON GOOGLE ────────────────────────────────────────────────────
$sync_result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'sync_google') {
  $cfg = $pdo->query('SELECT * FROM resenas_config WHERE id=1')->fetch();
  $place_id = $cfg['google_place_id'] ?? '';
  $api_key  = $cfg['google_api_key'] ?? '';

  if (!$place_id || !$api_key) {
    $error = 'Configura el Place ID y la API Key antes de sincronizar.';
  } else {
    $url = 'https://maps.googleapis.com/maps/api/place/details/json?place_id=' . urlencode($place_id) . '&fields=reviews,url&language=es&key=' . urlencode($api_key);
    $ctx = stream_context_create(['http' => ['timeout' => 15]]);
    $resp = @file_get_contents($url, false, $ctx);

    if ($resp === false) {
      $error = 'Error de conexión con Google Places API.';
    } else {
      $data = json_decode($resp, true);
      if (($data['status'] ?? '') !== 'OK') {
        $error = 'Error de Google API: ' . htmlspecialchars($data['status'] ?? 'desconocido') . ' — ' . htmlspecialchars($data['error_message'] ?? '');
      } else {
        $reviews = $data['result']['reviews'] ?? [];
        $inserted = 0;
        foreach ($reviews as $rv) {
          $nombre_g = $rv['author_name'] ?? '';
          $texto_g  = $rv['text'] ?? '';
          $stars_g  = (int)($rv['rating'] ?? 5);
          $fecha_g  = isset($rv['time']) ? date('Y-m-d', $rv['time']) : null;
          $url_g    = $rv['author_url'] ?? null;
          $foto_g   = $rv['profile_photo_url'] ?? null;

          // Comprobar si ya existe (match por nombre+fecha)
          $exists_stmt = $pdo->prepare('SELECT id FROM resenas WHERE nombre=? AND fecha=? AND fuente=\'google\'');
          $exists_stmt->execute([$nombre_g, $fecha_g]);
          if (!$exists_stmt->fetch()) {
            $pdo->prepare('INSERT INTO resenas (nombre, texto, estrellas, fecha, fuente, google_autor_url, foto_autor, visible) VALUES (?,?,?,?,\'google\',?,?,1)')
                ->execute([$nombre_g, $texto_g, $stars_g, $fecha_g, $url_g, $foto_g]);
            $inserted++;
          }
        }
        $pdo->exec("UPDATE resenas_config SET ultima_sync=NOW() WHERE id=1");
        $sync_result = $inserted;
        $mensaje = '✅ Sincronización completada. ' . $inserted . ' nueva' . ($inserted !== 1 ? 's' : '') . ' reseña' . ($inserted !== 1 ? 's' : '') . ' importada' . ($inserted !== 1 ? 's' : '') . '.';
      }
    }
  }
}

// ── CARGAR CONFIGURACIÓN ──────────────────────────────────────────────────────
$cfg = $pdo->query('SELECT * FROM resenas_config WHERE id=1')->fetch();

// ── STATS ─────────────────────────────────────────────────────────────────────
$total_resenas  = (int)$pdo->query('SELECT COUNT(*) FROM resenas')->fetchColumn();
$total_google   = (int)$pdo->query("SELECT COUNT(*) FROM resenas WHERE fuente='google'")->fetchColumn();
$total_manual   = (int)$pdo->query("SELECT COUNT(*) FROM resenas WHERE fuente='manual'")->fetchColumn();
$avg_raw        = $pdo->query('SELECT AVG(estrellas) FROM resenas WHERE visible=1')->fetchColumn();
$avg_estrellas  = $avg_raw ? round((float)$avg_raw, 1) : 0;

// ── FILTROS Y PAGINACIÓN ──────────────────────────────────────────────────────
$f_fuente   = $_GET['fuente']   ?? '';
$f_ciudad   = $_GET['ciudad']   ?? '';
$f_servicio = $_GET['servicio'] ?? '';
$f_visible  = $_GET['visible']  ?? '';
$por_pag    = 20;
$pag_num    = max(1, (int)($_GET['pag'] ?? 1));

$where  = ['1=1'];
$params = [];
if ($f_fuente)   { $where[] = 'fuente = ?';   $params[] = $f_fuente; }
if ($f_ciudad)   { $where[] = 'ciudad = ?';   $params[] = $f_ciudad; }
if ($f_servicio) { $where[] = 'servicio = ?'; $params[] = $f_servicio; }
if ($f_visible !== '') { $where[] = 'visible = ?'; $params[] = (int)$f_visible; }

$where_sql = implode(' AND ', $where);

$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM resenas WHERE $where_sql");
$count_stmt->execute($params);
$total_filtrado = (int)$count_stmt->fetchColumn();
$total_paginas  = max(1, (int)ceil($total_filtrado / $por_pag));
$pag_num        = min($pag_num, $total_paginas);
$offset         = ($pag_num - 1) * $por_pag;

$stmt = $pdo->prepare("SELECT * FROM resenas WHERE $where_sql ORDER BY creado DESC LIMIT $por_pag OFFSET $offset");
$stmt->execute($params);
$resenas = $stmt->fetchAll();

// Reseña a editar (si viene de ?editar=ID)
$edit_resena = null;
if (isset($_GET['editar']) && is_numeric($_GET['editar'])) {
  $edit_resena = $pdo->prepare('SELECT * FROM resenas WHERE id=?');
  $edit_resena->execute([$_GET['editar']]);
  $edit_resena = $edit_resena->fetch();
}

function build_res_url($extra = []) {
  $base = array_merge([
    'fuente'   => $_GET['fuente']   ?? '',
    'ciudad'   => $_GET['ciudad']   ?? '',
    'servicio' => $_GET['servicio'] ?? '',
    'visible'  => $_GET['visible']  ?? '',
    'pag'      => $_GET['pag']      ?? 1,
  ], $extra);
  return 'resenas.php?' . http_build_query(array_filter($base, function($v){ return $v !== '' && $v !== 1 && $v !== '1'; }));
}

function stars_html(int $n, int $total = 5): string {
  $out = '';
  for ($i = 1; $i <= $total; $i++) {
    $out .= $i <= $n ? '<span style="color:#f59e0b">★</span>' : '<span style="color:#d1d5db">★</span>';
  }
  return $out;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Reseñas — CarolTemp Admin</title>
  <meta name="robots" content="noindex,nofollow">
  <?php include '../includes/admin_style.php'; ?>
  <style>
    /* ─── tabs ─── */
    .wp-tabs { display:flex; gap:0; margin-bottom:1rem; border-bottom:2px solid #e2e8f0; }
    .wp-tab { padding:.5rem 1rem; font-size:13px; color:#64748b; text-decoration:none; border-bottom:2px solid transparent; margin-bottom:-2px; font-weight:500; transition:color .15s; }
    .wp-tab:hover { color:#0f172a; }
    .wp-tab.active { color:#3b5bdb; border-bottom-color:#3b5bdb; font-weight:700; }

    /* ─── table ─── */
    .wp-table { width:100%; border-collapse:collapse; }
    .wp-table th { background:#f8fafc; color:#64748b; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; padding:.65rem 1rem; text-align:left; border-bottom:1px solid #e2e8f0; }
    .wp-table td { padding:.7rem 1rem; border-bottom:1px solid #f1f5f9; vertical-align:middle; font-size:13px; }
    .wp-table tr:last-child td { border-bottom:none; }
    .wp-table tr:hover td { background:#fafbfc; }
    .row-actions { display:none; gap:.25rem; align-items:center; margin-top:4px; }
    tr:hover .row-actions { display:flex; }
    .row-actions a, .row-actions button { font-size:12px; color:#3b5bdb; text-decoration:none; background:none; border:none; cursor:pointer; padding:0; font-family:inherit; }
    .row-actions a:hover { text-decoration:underline; }
    .row-actions .sep { color:#d1d5db; margin:0 3px; }
    .row-actions .del { color:#dc2626; }

    /* ─── pagination ─── */
    .wp-pag { display:flex; align-items:center; gap:.4rem; }
    .wp-pag a, .wp-pag span { display:inline-flex; align-items:center; justify-content:center; min-width:30px; height:28px; padding:0 6px; border:1.5px solid #e2e8f0; border-radius:5px; font-size:13px; text-decoration:none; color:#374151; }
    .wp-pag a:hover { border-color:#3b5bdb; color:#3b5bdb; }
    .wp-pag .current { background:#3b5bdb; color:#fff; border-color:#3b5bdb; font-weight:700; }
    .wp-pag .dots { border:none; color:#94a3b8; }

    /* ─── filter bar ─── */
    .filter-bar { display:flex; gap:.5rem; align-items:center; flex-wrap:wrap; }
    .filter-bar select, .filter-bar input { border:1.5px solid #e2e8f0; border-radius:6px; padding:6px 10px; font-size:13px; font-family:inherit; }
    .filter-bar select:focus, .filter-bar input:focus { outline:none; border-color:#3b5bdb; }
    .filter-bar button { background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:6px; padding:6px 14px; font-size:13px; font-weight:600; cursor:pointer; }
    .filter-bar button:hover { border-color:#3b5bdb; color:#3b5bdb; }

    /* ─── stats bar ─── */
    .stats-bar { display:grid; grid-template-columns:repeat(auto-fit,minmax(140px,1fr)); gap:1rem; margin-bottom:1.5rem; }
    .stat-box { background:#fff; border:1.5px solid #e2e8f0; border-radius:10px; padding:1rem 1.25rem; }
    .stat-box .sv { font-size:1.6rem; font-weight:800; color:#0f172a; line-height:1; }
    .stat-box .sl { font-size:12px; color:#64748b; margin-top:3px; }

    /* ─── source badge ─── */
    .badge-google { display:inline-flex; align-items:center; gap:4px; background:#fff; border:1.5px solid #e2e8f0; border-radius:20px; padding:2px 8px; font-size:11.5px; font-weight:700; color:#374151; }
    .badge-manual { display:inline-flex; align-items:center; gap:4px; background:#f0f7ff; border:1.5px solid #bfdbfe; border-radius:20px; padding:2px 8px; font-size:11.5px; font-weight:700; color:#1d4ed8; }
    .g-logo { width:13px; height:13px; }

    /* ─── visible badge ─── */
    .badge-vis { display:inline-block; padding:2px 9px; border-radius:20px; font-size:11.5px; font-weight:700; cursor:pointer; text-decoration:none; }
    .badge-vis.si { background:#dcfce7; color:#166534; }
    .badge-vis.no { background:#fee2e2; color:#991b1b; }

    /* ─── tags ─── */
    .tag-ciudad { background:#f1f5f9; color:#475569; font-size:11px; padding:2px 7px; border-radius:10px; }
    .tag-serv   { background:#ede9fe; color:#6d28d9; font-size:11px; padding:2px 7px; border-radius:10px; }

    /* ─── config card ─── */
    .config-card { background:#fff; border:1.5px solid #e2e8f0; border-radius:12px; overflow:hidden; margin-bottom:1.5rem; }
    .config-card summary { padding:.9rem 1.25rem; font-weight:700; font-size:14px; color:#0f172a; cursor:pointer; list-style:none; display:flex; align-items:center; gap:.5rem; }
    .config-card summary::after { content:'▼'; font-size:10px; color:#94a3b8; margin-left:auto; }
    details[open] .config-card summary::after { content:'▲'; }
    .config-body { padding:1.25rem; border-top:1px solid #f1f5f9; }
    .form-row { display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem; }
    .form-group { display:flex; flex-direction:column; gap:5px; }
    .form-group label { font-size:12px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.05em; }
    .form-group input, .form-group textarea, .form-group select { border:1.5px solid #e2e8f0; border-radius:7px; padding:8px 11px; font-size:13px; font-family:inherit; }
    .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline:none; border-color:#3b5bdb; }

    /* ─── modal ─── */
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:1000; align-items:center; justify-content:center; }
    .modal-overlay.open { display:flex; }
    .modal-box { background:#fff; border-radius:14px; width:580px; max-width:95vw; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,.3); }
    .modal-head { padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; }
    .modal-head h3 { font-size:16px; font-weight:800; color:#0f172a; margin:0; }
    .modal-head button { background:none; border:none; font-size:18px; cursor:pointer; color:#94a3b8; }
    .modal-body { padding:1.5rem; }
    .star-selector { display:flex; gap:4px; flex-direction:row-reverse; justify-content:flex-end; }
    .star-selector input { display:none; }
    .star-selector label { font-size:1.6rem; color:#d1d5db; cursor:pointer; transition:color .1s; }
    .star-selector input:checked ~ label,
    .star-selector label:hover,
    .star-selector label:hover ~ label { color:#f59e0b; }

    /* ─── sync btn ─── */
    .btn-sync { background:#fff; border:1.5px solid #e2e8f0; border-radius:7px; padding:7px 16px; font-size:13px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:6px; transition:all .15s; }
    .btn-sync:hover { border-color:#4ade80; color:#166534; background:#f0fdf4; }
    .top-actions { display:flex; align-items:center; justify-content:space-between; margin-bottom:.75rem; flex-wrap:wrap; gap:.75rem; }
    .bulk-bar { display:flex; align-items:center; gap:.75rem; padding:.65rem 1rem; background:#f8fafc; border-bottom:1px solid #e2e8f0; }
    .resena-text { color:#374151; max-width:340px; }
    .resena-nombre { font-weight:700; color:#0f172a; }
  </style>
</head>
<body>
<?php include '../includes/admin_sidebar.php'; ?>
<main class="main">

  <div class="topbar">
    <h1>Reseñas</h1>
    <div style="display:flex;gap:.5rem;align-items:center">
      <form method="POST" style="display:inline">
        <input type="hidden" name="action" value="sync_google">
        <button type="submit" class="btn-sync">🔄 Sincronizar con Google</button>
      </form>
      <button class="btn-new" onclick="abrirModal()">+ Nueva reseña</button>
    </div>
  </div>

  <?php if ($mensaje): ?><div class="mensaje"><?php echo $mensaje; ?></div><?php endif; ?>
  <?php if ($error):   ?><div class="error-msg"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

  <!-- STATS BAR -->
  <div class="stats-bar">
    <div class="stat-box">
      <div class="sv"><?php echo $total_resenas; ?></div>
      <div class="sl">Total reseñas</div>
    </div>
    <div class="stat-box">
      <div class="sv"><?php echo $total_google; ?></div>
      <div class="sl">Google</div>
    </div>
    <div class="stat-box">
      <div class="sv"><?php echo $total_manual; ?></div>
      <div class="sl">Manuales</div>
    </div>
    <div class="stat-box">
      <div class="sv"><?php echo $avg_estrellas; ?> ★</div>
      <div class="sl">Media visible</div>
    </div>
    <div class="stat-box">
      <div class="sv" style="font-size:12px;color:#64748b;font-weight:600;margin-top:4px">
        <?php echo $cfg['ultima_sync'] ? date('d/m/Y H:i', strtotime($cfg['ultima_sync'])) : '—'; ?>
      </div>
      <div class="sl">Última sync</div>
    </div>
  </div>

  <!-- CONFIGURACIÓN -->
  <details>
    <div class="config-card">
      <summary>⚙️ Configuración de Google Reviews</summary>
      <div class="config-body">
        <form method="POST">
          <input type="hidden" name="action" value="save_config">
          <div class="form-row">
            <div class="form-group">
              <label>Google Place ID</label>
              <input type="text" name="google_place_id" value="<?php echo htmlspecialchars($cfg['google_place_id'] ?? 'ChIJNYRvY6aWGwgRGyPqyX1xfo0'); ?>" placeholder="ChIJ...">
            </div>
            <div class="form-group">
              <label>API Key (Google Places)</label>
              <input type="password" name="google_api_key" value="" placeholder="<?php echo $cfg['google_api_key'] ? '••••' . substr($cfg['google_api_key'], -4) : 'AIzaSy...'; ?>" autocomplete="new-password">
              <span style="font-size:11px;color:#94a3b8">Deja en blanco para no cambiar la clave actual</span>
            </div>
          </div>
          <div class="form-group" style="margin-bottom:1rem">
            <label>URL pública de reseñas Google</label>
            <input type="url" name="google_reviews_url" value="<?php echo htmlspecialchars($cfg['google_reviews_url'] ?? ''); ?>" placeholder="https://g.page/r/...">
            <span style="font-size:11px;color:#94a3b8">Se usa como destino al pulsar en reseñas manuales del frontend</span>
          </div>
          <button type="submit" class="btn-new" style="font-size:13px">💾 Guardar configuración</button>
        </form>
      </div>
    </div>
  </details>

  <!-- FILTROS -->
  <div class="top-actions" style="margin-top:1.25rem">
    <form class="filter-bar" method="GET">
      <select name="fuente" onchange="this.form.submit()">
        <option value="">Todas las fuentes</option>
        <option value="google"  <?php echo $f_fuente==='google'?'selected':''; ?>>Google</option>
        <option value="manual"  <?php echo $f_fuente==='manual'?'selected':''; ?>>Manual</option>
      </select>
      <select name="ciudad" onchange="this.form.submit()">
        <option value="">Todas las ciudades</option>
        <?php foreach ($ciudades as $c): ?>
          <option value="<?php echo $c; ?>" <?php echo $f_ciudad===$c?'selected':''; ?>><?php echo ucfirst($c); ?></option>
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
      <?php if ($f_fuente || $f_ciudad || $f_servicio || $f_visible !== ''): ?>
        <a href="resenas.php" style="font-size:13px;color:#64748b;text-decoration:none;padding:6px 4px">✕ Limpiar</a>
      <?php endif; ?>
    </form>
    <span style="font-size:12.5px;color:#94a3b8"><?php echo $total_filtrado; ?> reseña<?php echo $total_filtrado!==1?'s':''; ?></span>
  </div>

  <!-- TABLA -->
  <div class="card" style="overflow:hidden">
    <?php if ($total_paginas > 1): ?>
    <div class="bulk-bar">
      <div class="wp-pag" style="margin-left:auto">
        <?php if ($pag_num > 1): ?><a href="<?php echo build_res_url(['pag'=>$pag_num-1]); ?>">‹</a><?php endif; ?>
        <?php
        for ($i = 1; $i <= $total_paginas; $i++) {
          if ($i === 1 || $i === $total_paginas || abs($i - $pag_num) <= 1) {
            echo '<a href="' . build_res_url(['pag'=>$i]) . '" class="' . ($i===$pag_num?'current':'') . '">' . $i . '</a>';
          } elseif (abs($i - $pag_num) === 2) {
            echo '<span class="dots">…</span>';
          }
        }
        ?>
        <?php if ($pag_num < $total_paginas): ?><a href="<?php echo build_res_url(['pag'=>$pag_num+1]); ?>">›</a><?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

    <table class="wp-table">
      <thead>
        <tr>
          <th style="width:80px">Fuente</th>
          <th style="width:100px">Estrellas</th>
          <th>Autor / Reseña</th>
          <th style="width:120px">Ciudad / Serv.</th>
          <th style="width:100px">Fecha</th>
          <th style="width:80px">Visible</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($resenas)): ?>
          <tr><td colspan="6" style="text-align:center;color:#94a3b8;padding:3rem">No se encontraron reseñas.</td></tr>
        <?php else: foreach ($resenas as $r): ?>
        <tr>
          <td>
            <?php if ($r['fuente'] === 'google'): ?>
              <span class="badge-google">
                <svg class="g-logo" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.5 0 6.6 1.2 9.1 3.2l6.8-6.8C35.8 2.1 30.3 0 24 0 14.6 0 6.6 5.4 2.6 13.3l7.9 6.1C12.4 13.1 17.7 9.5 24 9.5z"/><path fill="#4285F4" d="M46.5 24.5c0-1.6-.1-3.1-.4-4.5H24v8.5h12.7c-.6 3-2.3 5.5-4.9 7.2l7.6 5.9c4.5-4.1 7.1-10.2 7.1-17.1z"/><path fill="#FBBC05" d="M10.5 28.6A14.8 14.8 0 0 1 9.5 24c0-1.6.3-3.1.7-4.6l-7.9-6.1A24 24 0 0 0 0 24c0 3.8.9 7.4 2.5 10.6l8-6z"/><path fill="#34A853" d="M24 48c6.5 0 11.9-2.1 15.9-5.8l-7.6-5.9c-2.2 1.5-5 2.3-8.3 2.3-6.3 0-11.6-3.6-13.5-9.1l-8 6C6.6 42.6 14.6 48 24 48z"/></svg>
                Google
              </span>
            <?php else: ?>
              <span class="badge-manual">✏️ Manual</span>
            <?php endif; ?>
          </td>
          <td style="font-size:14px"><?php echo stars_html((int)$r['estrellas']); ?></td>
          <td>
            <div class="resena-nombre"><?php echo htmlspecialchars($r['nombre']); ?></div>
            <div class="resena-text" style="margin-top:2px"><?php echo htmlspecialchars(mb_substr($r['texto'], 0, 100)) . (mb_strlen($r['texto']) > 100 ? '…' : ''); ?></div>
            <div class="row-actions">
              <a href="#" onclick="abrirEditar(<?php echo $r['id']; ?>);return false">Editar</a>
              <span class="sep">|</span>
              <a href="#" class="del" onclick="confirmarEliminar(<?php echo $r['id']; ?>,'<?php echo htmlspecialchars(addslashes($r['nombre'])); ?>');return false">Eliminar</a>
            </div>
          </td>
          <td>
            <?php if ($r['ciudad']): ?><span class="tag-ciudad"><?php echo htmlspecialchars($r['ciudad']); ?></span><?php endif; ?>
            <?php if ($r['servicio']): ?><span class="tag-serv"><?php echo htmlspecialchars($r['servicio']); ?></span><?php endif; ?>
            <?php if (!$r['ciudad'] && !$r['servicio']): ?><span style="color:#d1d5db">—</span><?php endif; ?>
          </td>
          <td style="font-size:12px;color:#64748b;white-space:nowrap">
            <?php echo $r['fecha'] ? date('d/m/Y', strtotime($r['fecha'])) : '—'; ?>
          </td>
          <td>
            <a href="<?php echo build_res_url(['toggle'=>$r['id']]); ?>" class="badge-vis <?php echo $r['visible']?'si':'no'; ?>">
              <?php echo $r['visible'] ? 'Visible' : 'Oculta'; ?>
            </a>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>

    <?php if ($total_paginas > 1): ?>
    <div style="padding:.75rem 1rem;border-top:1px solid #f1f5f9;display:flex;justify-content:flex-end">
      <div class="wp-pag">
        <?php if ($pag_num > 1): ?><a href="<?php echo build_res_url(['pag'=>$pag_num-1]); ?>">‹ Anterior</a><?php endif; ?>
        <?php
        for ($i = 1; $i <= $total_paginas; $i++) {
          if ($i === 1 || $i === $total_paginas || abs($i - $pag_num) <= 1) {
            echo '<a href="' . build_res_url(['pag'=>$i]) . '" class="' . ($i===$pag_num?'current':'') . '">' . $i . '</a>';
          } elseif (abs($i - $pag_num) === 2) {
            echo '<span class="dots">…</span>';
          }
        }
        ?>
        <?php if ($pag_num < $total_paginas): ?><a href="<?php echo build_res_url(['pag'=>$pag_num+1]); ?>">Siguiente ›</a><?php endif; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>

</main>

<!-- MODAL NUEVA / EDITAR RESEÑA -->
<div class="modal-overlay" id="modal-resena">
  <div class="modal-box">
    <div class="modal-head">
      <h3 id="modal-titulo">Nueva reseña manual</h3>
      <button onclick="cerrarModal()" title="Cerrar">✕</button>
    </div>
    <div class="modal-body">
      <form method="POST" id="form-resena">
        <input type="hidden" name="action" value="save_resena">
        <input type="hidden" name="edit_id" id="edit_id" value="0">

        <div class="form-group" style="margin-bottom:1rem">
          <label>Nombre del autor *</label>
          <input type="text" name="nombre" id="r_nombre" required placeholder="Ej: María García">
        </div>

        <div class="form-group" style="margin-bottom:1rem">
          <label>Texto de la reseña *</label>
          <textarea name="texto" id="r_texto" rows="4" required placeholder="Escribe el contenido de la reseña…" style="resize:vertical"></textarea>
        </div>

        <div class="form-group" style="margin-bottom:1rem">
          <label>Puntuación</label>
          <div class="star-selector" id="star-selector">
            <input type="radio" name="estrellas" id="s5" value="5" checked><label for="s5">★</label>
            <input type="radio" name="estrellas" id="s4" value="4"><label for="s4">★</label>
            <input type="radio" name="estrellas" id="s3" value="3"><label for="s3">★</label>
            <input type="radio" name="estrellas" id="s2" value="2"><label for="s2">★</label>
            <input type="radio" name="estrellas" id="s1" value="1"><label for="s1">★</label>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Fecha</label>
            <input type="date" name="fecha" id="r_fecha">
          </div>
          <div class="form-group">
            <label>Ciudad</label>
            <select name="ciudad" id="r_ciudad">
              <option value="">— Sin ciudad —</option>
              <?php foreach ($ciudades as $c): ?>
                <option value="<?php echo $c; ?>"><?php echo ucfirst(str_replace('-',' ',$c)); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-group" style="margin-bottom:1.5rem">
          <label>Servicio</label>
          <select name="servicio" id="r_servicio">
            <option value="">— Sin servicio —</option>
            <?php foreach ($servicios as $s): ?>
              <option value="<?php echo $s; ?>"><?php echo ucfirst($s); ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div style="display:flex;gap:.75rem;justify-content:flex-end">
          <button type="button" onclick="cerrarModal()" style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:7px;padding:8px 18px;font-size:13px;font-weight:600;cursor:pointer">Cancelar</button>
          <button type="submit" class="btn-new" style="font-size:13px">💾 Guardar reseña</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- CONFIRM OVERLAY -->
<div class="confirm-overlay" id="confirm-overlay">
  <div class="confirm-box">
    <h3>¿Eliminar reseña?</h3>
    <p id="confirm-texto"></p>
    <div class="confirm-btns">
      <a href="#" class="btn-cancelar" onclick="cerrarConfirm();return false">Cancelar</a>
      <a href="#" class="btn-confirmar" id="confirm-link">Eliminar</a>
    </div>
  </div>
</div>

<!-- DATOS PARA EDICIÓN -->
<script>
var resenasData = <?php
  $r_data = [];
  foreach ($resenas as $r) {
    $r_data[$r['id']] = [
      'nombre'   => $r['nombre'],
      'texto'    => $r['texto'],
      'estrellas'=> (int)$r['estrellas'],
      'fecha'    => $r['fecha'] ?? '',
      'ciudad'   => $r['ciudad'] ?? '',
      'servicio' => $r['servicio'] ?? '',
    ];
  }
  echo json_encode($r_data);
?>;

function abrirModal() {
  document.getElementById('modal-titulo').textContent = 'Nueva reseña manual';
  document.getElementById('edit_id').value = '0';
  document.getElementById('r_nombre').value = '';
  document.getElementById('r_texto').value = '';
  document.getElementById('r_fecha').value = '';
  document.getElementById('r_ciudad').value = '';
  document.getElementById('r_servicio').value = '';
  document.getElementById('s5').checked = true;
  document.getElementById('modal-resena').classList.add('open');
}

function abrirEditar(id) {
  var d = resenasData[id];
  if (!d) return;
  document.getElementById('modal-titulo').textContent = 'Editar reseña';
  document.getElementById('edit_id').value = id;
  document.getElementById('r_nombre').value = d.nombre;
  document.getElementById('r_texto').value = d.texto;
  document.getElementById('r_fecha').value = d.fecha;
  document.getElementById('r_ciudad').value = d.ciudad;
  document.getElementById('r_servicio').value = d.servicio;
  var sr = document.getElementById('s' + d.estrellas);
  if (sr) sr.checked = true;
  document.getElementById('modal-resena').classList.add('open');
}

function cerrarModal() {
  document.getElementById('modal-resena').classList.remove('open');
}

document.getElementById('modal-resena').addEventListener('click', function(e) {
  if (e.target === this) cerrarModal();
});

function confirmarEliminar(id, nombre) {
  document.getElementById('confirm-texto').textContent = '¿Seguro que quieres eliminar la reseña de "' + nombre + '"? Esta acción no se puede deshacer.';
  document.getElementById('confirm-link').href = 'resenas.php?eliminar=' + id + '<?php echo $f_fuente?"&fuente=".urlencode($f_fuente):""; ?><?php echo $f_ciudad?"&ciudad=".urlencode($f_ciudad):""; ?><?php echo $f_servicio?"&servicio=".urlencode($f_servicio):""; ?>&pag=<?php echo $pag_num; ?>';
  document.getElementById('confirm-overlay').classList.add('open');
}
function cerrarConfirm() { document.getElementById('confirm-overlay').classList.remove('open'); }
document.getElementById('confirm-overlay').addEventListener('click', function(e){ if(e.target===this) cerrarConfirm(); });
</script>
</body>
</html>
