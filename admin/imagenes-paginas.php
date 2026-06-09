<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php'); exit;
}
require_once '../includes/db.php';

$base_url = $is_local ? 'http://localhost/caroltemp/' : 'https://caroltemp.com/';

try {
  $pdo->exec("CREATE TABLE IF NOT EXISTS paginas_config (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL UNIQUE,
    nombre VARCHAR(150) NOT NULL DEFAULT '',
    imagen VARCHAR(255) NOT NULL DEFAULT '',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
} catch (PDOException $e) {}

// ── AJAX handlers (JSON) ──────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $accion = $_POST['accion'] ?? '';
  if (in_array($accion, ['asignar-ajax', 'eliminar-ajax'])) {
    header('Content-Type: application/json; charset=utf-8');
    $slug = trim($_POST['slug'] ?? '');
    if (!$slug) { echo json_encode(['error' => 'Slug inválido']); exit; }
    if ($accion === 'asignar-ajax') {
      $ruta = trim($_POST['ruta'] ?? '');
      $pdo->prepare('UPDATE paginas_config SET imagen = ? WHERE slug = ?')->execute([$ruta, $slug]);
    } else {
      $pdo->prepare('UPDATE paginas_config SET imagen = "" WHERE slug = ?')->execute([$slug]);
    }
    echo json_encode(['ok' => true]); exit;
  }
}

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

foreach ($paginas_grupos as $paginas) {
  foreach ($paginas as $p) {
    try {
      $pdo->prepare('INSERT INTO paginas_config (slug, nombre) VALUES (?, ?) ON DUPLICATE KEY UPDATE nombre = VALUES(nombre)')
          ->execute([$p['slug'], $p['nombre']]);
    } catch (PDOException $e) {}
  }
}

$imagenes = [];
try {
  foreach ($pdo->query('SELECT slug, imagen FROM paginas_config')->fetchAll() as $r)
    $imagenes[$r['slug']] = $r['imagen'];
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
    .pi-card-img { width:100%; height:130px; background:#f0f4f8; display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden; }
    .pi-card-img img { width:100%; height:100%; object-fit:cover; }
    .pi-card-img-empty { font-size:2rem; opacity:.25; }
    .pi-card-img-badge { position:absolute; top:6px; right:6px; background:rgba(0,0,0,.5); color:#fff; font-size:10px; font-weight:700; padding:2px 7px; border-radius:20px; text-transform:uppercase; letter-spacing:.05em; }
    .pi-card-img-badge.tiene { background:rgba(34,197,94,.85); }
    .pi-card-body { padding:.875rem; }
    .pi-card-name { font-size:13px; font-weight:700; color:#0B2447; margin-bottom:.75rem; line-height:1.3; }
    .pi-card-actions { display:flex; gap:.5rem; flex-wrap:wrap; }
    .pi-btn-set { flex:1; background:#1e3a5f; color:#fff; border:none; border-radius:7px; padding:.45rem .6rem; font-size:12px; font-weight:600; cursor:pointer; text-align:center; white-space:nowrap; }
    .pi-btn-set:hover { background:#163050; }
    .pi-btn-del { background:#fff; color:#dc2626; border:1.5px solid #fca5a5; border-radius:7px; padding:.45rem .6rem; font-size:12px; font-weight:600; cursor:pointer; white-space:nowrap; }
    .pi-btn-del:hover { background:#fef2f2; }
    .pi-toast { position:fixed; bottom:1.5rem; right:1.5rem; background:#16a34a; color:#fff; padding:.75rem 1.25rem; border-radius:8px; font-size:13px; font-weight:600; box-shadow:0 4px 20px rgba(0,0,0,.15); z-index:999; transform:translateY(100px); opacity:0; transition:all .3s; }
    .pi-toast.show { transform:translateY(0); opacity:1; }
    .pi-toast.err { background:#dc2626; }
  </style>
</head>
<body>
<?php include '../includes/admin_sidebar.php'; ?>

<div class="admin-content">
  <div class="pi-header">
    <h1>🖼️ Imágenes de páginas</h1>
    <div class="pi-stats">
      <div class="pi-stat" id="pi-stat-con"><strong id="pi-n-con"><?php echo $total_con_imagen; ?></strong> / <?php echo $total_paginas; ?> con imagen</div>
      <div class="pi-stat"><strong id="pi-n-sin"><?php echo $total_paginas - $total_con_imagen; ?></strong> sin imagen</div>
    </div>
  </div>
  <p style="font-size:13px;color:#8FA3B8;margin-top:-.5rem;margin-bottom:2rem">
    Las imágenes se asignan desde la <a href="medios.php" style="color:#1e3a5f;font-weight:600">Biblioteca de medios</a>. Sube primero la imagen allí y luego asígnala a cada página.
  </p>

  <?php foreach ($paginas_grupos as $grupo => $paginas): ?>
  <div class="pi-section">
    <div class="pi-section-title"><?php echo htmlspecialchars($grupo); ?></div>
    <div class="pi-grid">
      <?php foreach ($paginas as $p):
        $img     = $imagenes[$p['slug']] ?? '';
        $img_url = $img ? $base_url . $img : '';
        $tiene   = (bool)$img;
        $sid     = 'c-' . preg_replace('/[^a-z0-9]/', '-', $p['slug']);
      ?>
      <div class="pi-card" id="card-<?php echo $sid; ?>">
        <div class="pi-card-img">
          <img id="img-<?php echo $sid; ?>" src="<?php echo htmlspecialchars($img_url ?: ''); ?>" alt=""
               style="display:<?php echo $tiene ? 'block' : 'none'; ?>">
          <span class="pi-card-img-empty" id="empty-<?php echo $sid; ?>"
                style="display:<?php echo $tiene ? 'none' : 'flex'; ?>">🖼️</span>
          <span class="pi-card-img-badge <?php echo $tiene ? 'tiene' : ''; ?>" id="badge-<?php echo $sid; ?>">
            <?php echo $tiene ? '✓ Imagen' : 'Sin imagen'; ?>
          </span>
        </div>
        <div class="pi-card-body">
          <div class="pi-card-name"><?php echo htmlspecialchars($p['nombre']); ?></div>
          <div class="pi-card-actions">
            <button class="pi-btn-set"
              onclick="piAbrir('<?php echo htmlspecialchars($p['slug']); ?>','<?php echo $sid; ?>')"
              id="btn-<?php echo $sid; ?>">
              <?php echo $tiene ? '🔄 Cambiar' : '📷 Establecer'; ?>
            </button>
            <button class="pi-btn-del" id="del-<?php echo $sid; ?>"
              style="display:<?php echo $tiene ? 'inline-flex' : 'none'; ?>"
              onclick="piEliminar('<?php echo htmlspecialchars($p['slug']); ?>','<?php echo $sid; ?>')">🗑️</button>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<div class="pi-toast" id="pi-toast"></div>

<script>
var BASE_URL     = '<?php echo $base_url; ?>';
var _piSlug      = null;
var _piSid       = null;
var _piConImagen = <?php echo $total_con_imagen; ?>;
var _piTotal     = <?php echo $total_paginas; ?>;

function piAbrir(slug, sid) {
  _piSlug = slug;
  _piSid  = sid;
  var popup = window.open('medios.php?modo=selector', 'pi_media_' + sid,
    'width=980,height=680,scrollbars=yes,resizable=yes');
  if (!popup) alert('Permite las ventanas emergentes del navegador para usar el selector de imágenes.');
}

window.addEventListener('message', function(e) {
  if (!e.data || e.data.tipo !== 'media_select' || !_piSlug) return;
  var slug = _piSlug, sid = _piSid;
  _piSlug = null; _piSid = null;

  var fd = new FormData();
  fd.append('accion', 'asignar-ajax');
  fd.append('slug', slug);
  fd.append('ruta', e.data.ruta);
  fetch(location.pathname, { method:'POST', body: fd })
    .then(function(r){ return r.json(); })
    .then(function(d) {
      if (!d.ok) { piToast('Error al guardar', 'err'); return; }
      var hadImg = document.getElementById('img-'+sid).style.display !== 'none';
      document.getElementById('img-'+sid).src  = BASE_URL + e.data.ruta;
      document.getElementById('img-'+sid).style.display = 'block';
      document.getElementById('empty-'+sid).style.display = 'none';
      document.getElementById('badge-'+sid).textContent = '✓ Imagen';
      document.getElementById('badge-'+sid).className = 'pi-card-img-badge tiene';
      document.getElementById('btn-'+sid).textContent = '🔄 Cambiar';
      document.getElementById('del-'+sid).style.display = 'inline-flex';
      if (!hadImg) { _piConImagen++; actualizarStats(); }
      piToast('✅ Imagen asignada');
    });
});

function piEliminar(slug, sid) {
  if (!confirm('¿Quitar imagen de esta página?')) return;
  var fd = new FormData();
  fd.append('accion', 'eliminar-ajax');
  fd.append('slug', slug);
  fetch(location.pathname, { method:'POST', body: fd })
    .then(function(r){ return r.json(); })
    .then(function(d) {
      if (!d.ok) { piToast('Error al eliminar', 'err'); return; }
      document.getElementById('img-'+sid).style.display = 'none';
      document.getElementById('empty-'+sid).style.display = 'flex';
      document.getElementById('badge-'+sid).textContent = 'Sin imagen';
      document.getElementById('badge-'+sid).className = 'pi-card-img-badge';
      document.getElementById('btn-'+sid).textContent = '📷 Establecer';
      document.getElementById('del-'+sid).style.display = 'none';
      _piConImagen--; actualizarStats();
      piToast('Imagen eliminada');
    });
}

function actualizarStats() {
  document.getElementById('pi-n-con').textContent = _piConImagen;
  document.getElementById('pi-n-sin').textContent = _piTotal - _piConImagen;
}

function piToast(msg, tipo) {
  var t = document.getElementById('pi-toast');
  t.textContent = msg;
  t.className = 'pi-toast ' + (tipo||'');
  t.classList.add('show');
  setTimeout(function(){ t.classList.remove('show'); }, 2800);
}
</script>
</body>
</html>
