<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php'); exit;
}
require_once '../includes/db.php';

$base_url = $is_local ? 'http://localhost/caroltemp/' : 'https://caroltemp.com/';
$modo     = $_GET['modo'] ?? 'galeria'; // galeria | selector

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

$medios = [];
try { $medios = $pdo->query('SELECT * FROM medios ORDER BY fecha DESC')->fetchAll(); }
catch (PDOException $e) {}

function fmtBytes($b) {
  if ($b >= 1048576) return round($b/1048576,1).' MB';
  if ($b >= 1024)    return round($b/1024,1).' KB';
  return $b.' B';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $modo==='selector' ? 'Seleccionar imagen' : 'Biblioteca de medios'; ?> — CarolTemp</title>
  <meta name="robots" content="noindex, nofollow">
  <?php if ($modo === 'galeria') include '../includes/admin_style.php'; ?>
  <style>
    /* ── Reset popup ── */
    <?php if ($modo==='selector'): ?>
    * { box-sizing:border-box; margin:0; padding:0; }
    body { font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif; background:#f0f4f8; color:#0B2447; }
    <?php endif; ?>

    /* ── Toolbar ── */
    .med-toolbar {
      display:flex; align-items:center; gap:.75rem; flex-wrap:wrap;
      padding:<?php echo $modo==='selector'?'1rem 1.25rem':'1.25rem 1.5rem'; ?>;
      background:#fff; border-bottom:1px solid #e2e8f0;
      <?php if($modo==='selector') echo 'position:sticky;top:0;z-index:10;'; ?>
    }
    .med-toolbar h1 { font-size:<?php echo $modo==='selector'?'15px':'1.25rem'; ?>; font-weight:800; color:#0B2447; flex:1; margin:0; }
    .med-toolbar input[type="search"] {
      border:1.5px solid #dde6f0; border-radius:20px; padding:.4rem .9rem;
      font-size:13px; width:180px; outline:none;
    }
    .med-toolbar input[type="search"]:focus { border-color:#1e3a5f; }
    .med-upload-btn {
      background:#1e3a5f; color:#fff; border:none; border-radius:8px;
      padding:.5rem 1.1rem; font-size:13px; font-weight:700; cursor:pointer;
      display:flex; align-items:center; gap:.4rem; white-space:nowrap;
    }
    .med-upload-btn:hover { background:#163050; }
    .med-count { font-size:12px; color:#8FA3B8; white-space:nowrap; }

    /* ── Drop zone ── */
    .med-dropzone {
      margin:<?php echo $modo==='selector'?'.75rem 1.25rem':'1rem 1.5rem'; ?>;
      border:2px dashed #dde6f0; border-radius:10px; padding:<?php echo $modo==='selector'?'1rem':'1.5rem'; ?>;
      text-align:center; transition:all .15s; display:none;
    }
    .med-dropzone.open, .med-dropzone.drag { display:block; border-color:#1e3a5f; background:#f4f8ff; }
    .med-dropzone.drag { background:#eaf1ff; }
    .med-dropzone p { font-size:13px; color:#576574; }
    .med-dropzone p strong { color:#1e3a5f; }
    .med-dropzone input { display:none; }
    .med-progress-bar { height:4px; background:#e2e8f0; border-radius:2px; margin-top:.75rem; overflow:hidden; display:none; }
    .med-progress-fill { height:100%; background:#1e3a5f; border-radius:2px; transition:width .2s; }

    /* ── Layout galeria ── */
    .med-layout {
      display:flex;
      height:<?php echo $modo==='selector'?'calc(100vh - 130px)':'calc(100vh - 60px)'; ?>;
      overflow:hidden;
    }
    <?php if($modo==='galeria'): ?>
    .admin-content { padding:0; display:flex; flex-direction:column; flex:1; overflow:hidden; }
    <?php endif; ?>

    /* ── Grid ── */
    .med-grid-wrap { flex:1; overflow-y:auto; padding:1rem 1.5rem; }
    .med-grid {
      display:grid;
      grid-template-columns:repeat(auto-fill, minmax(<?php echo $modo==='selector'?'130px':'150px'; ?>, 1fr));
      gap:8px;
    }
    .med-item {
      border:2.5px solid transparent; border-radius:8px; overflow:hidden;
      cursor:pointer; background:#fff; transition:all .15s; position:relative;
    }
    .med-item:hover { border-color:#94a3b8; box-shadow:0 2px 8px rgba(0,0,0,.08); }
    .med-item.selected { border-color:#1e3a5f; box-shadow:0 0 0 3px rgba(30,58,95,.15); }
    .med-item-thumb {
      width:100%; aspect-ratio:1; overflow:hidden; background:#f0f4f8;
      display:flex; align-items:center; justify-content:center;
    }
    .med-item-thumb img { width:100%; height:100%; object-fit:cover; }
    .med-item-info { padding:.4rem .5rem; }
    .med-item-name { font-size:11px; color:#576574; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block; }
    <?php if($modo==='selector'): ?>
    .med-item-select-overlay {
      position:absolute; inset:0; background:rgba(30,58,95,.85);
      display:flex; align-items:center; justify-content:center;
      opacity:0; transition:opacity .15s;
    }
    .med-item:hover .med-item-select-overlay { opacity:1; }
    .med-item-select-overlay span { color:#fff; font-size:13px; font-weight:700; }
    <?php endif; ?>
    .med-empty { text-align:center; padding:4rem 1rem; color:#8FA3B8; font-size:14px; }
    .med-hidden { display:none !important; }

    /* ── Detail panel (galeria only) ── */
    .med-detail {
      width:0; overflow:hidden; transition:width .2s;
      background:#fff; border-left:1px solid #e2e8f0; display:flex; flex-direction:column;
    }
    .med-detail.open { width:300px; }
    .med-detail-inner { padding:1.25rem; overflow-y:auto; flex:1; }
    .med-detail-close { position:absolute; top:.75rem; right:.75rem; background:none; border:none; font-size:18px; cursor:pointer; color:#8FA3B8; }
    .med-detail-close:hover { color:#0B2447; }
    .med-detail-thumb { width:100%; max-height:180px; object-fit:contain; border-radius:6px; border:1px solid #e2e8f0; margin-bottom:1.25rem; }
    .med-detail h3 { font-size:13px; font-weight:700; color:#0B2447; margin-bottom:1rem; word-break:break-all; }
    .med-detail-info { font-size:12px; color:#8FA3B8; margin-bottom:1.25rem; display:flex; flex-direction:column; gap:.25rem; }
    .med-detail-info span strong { color:#576574; }
    .med-detail label { font-size:12px; font-weight:700; color:#576574; display:block; margin-bottom:.3rem; }
    .med-detail input, .med-detail textarea {
      width:100%; border:1.5px solid #dde6f0; border-radius:6px; padding:.45rem .65rem;
      font-size:13px; color:#0B2447; font-family:inherit; outline:none; margin-bottom:.75rem;
    }
    .med-detail input:focus, .med-detail textarea:focus { border-color:#1e3a5f; }
    .med-detail textarea { resize:vertical; min-height:70px; }
    .med-detail-rename { display:flex; gap:.4rem; margin-bottom:.75rem; }
    .med-detail-rename input { margin-bottom:0; flex:1; }
    .med-detail-rename button { background:#1e3a5f; color:#fff; border:none; border-radius:6px; padding:.45rem .75rem; font-size:12px; font-weight:700; cursor:pointer; white-space:nowrap; }
    .med-detail-rename button:hover { background:#163050; }
    .med-detail-url { display:flex; gap:.4rem; align-items:center; margin-bottom:1rem; }
    .med-detail-url input { flex:1; background:#f8fafc; color:#576574; font-size:12px; margin-bottom:0; }
    .med-detail-url button { background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:6px; padding:.4rem .6rem; font-size:12px; cursor:pointer; white-space:nowrap; }
    .med-detail-url button:hover { background:#e2e8f0; }
    .med-btn-save { width:100%; background:#16a34a; color:#fff; border:none; border-radius:7px; padding:.55rem; font-size:13px; font-weight:700; cursor:pointer; margin-bottom:.75rem; }
    .med-btn-save:hover { background:#15803d; }
    .med-btn-delete { width:100%; background:#fff; color:#dc2626; border:1.5px solid #fca5a5; border-radius:7px; padding:.55rem; font-size:13px; font-weight:600; cursor:pointer; }
    .med-btn-delete:hover { background:#fef2f2; }
    .med-saved-msg { font-size:12px; color:#16a34a; text-align:center; margin-top:.5rem; display:none; }

    /* ── Toast ── */
    .med-toast { position:fixed; bottom:1.5rem; right:1.5rem; background:#0B2447; color:#fff; padding:.75rem 1.25rem; border-radius:8px; font-size:13px; font-weight:600; box-shadow:0 4px 20px rgba(0,0,0,.15); z-index:999; transform:translateY(100px); opacity:0; transition:all .3s; }
    .med-toast.show { transform:translateY(0); opacity:1; }
    .med-toast.ok { background:#16a34a; }
    .med-toast.err { background:#dc2626; }
  </style>
</head>
<body>
<?php if ($modo === 'galeria') include '../includes/admin_sidebar.php'; ?>

<div class="<?php echo $modo==='galeria'?'admin-content':''; ?>">

  <!-- TOOLBAR -->
  <div class="med-toolbar">
    <h1><?php echo $modo==='selector' ? '🖼️ Seleccionar imagen' : 'Biblioteca de medios'; ?></h1>
    <span class="med-count" id="med-count"><?php echo count($medios); ?> archivo<?php echo count($medios)!==1?'s':''; ?></span>
    <input type="search" id="med-search" placeholder="Buscar..." oninput="filtrarGrid(this.value)">
    <button class="med-upload-btn" onclick="toggleDropzone()">⬆️ Subir imagen</button>
    <?php if ($modo==='selector'): ?>
      <button onclick="window.close()" style="background:#f0f4f8;color:#576574;border:none;border-radius:8px;padding:.5rem 1rem;font-size:13px;font-weight:600;cursor:pointer;">✕ Cerrar</button>
    <?php endif; ?>
  </div>

  <!-- DROP ZONE -->
  <div class="med-dropzone" id="med-dropzone">
    <p>📁 <strong>Arrastra imágenes aquí</strong> o haz clic para seleccionar</p>
    <p style="font-size:12px;margin-top:.4rem">JPG, PNG, WebP · Máx. 5 MB por archivo</p>
    <input type="file" id="med-file-input" accept="image/*" multiple>
    <div class="med-progress-bar" id="med-progress-bar">
      <div class="med-progress-fill" id="med-progress-fill" style="width:0%"></div>
    </div>
  </div>

  <!-- LAYOUT -->
  <div class="med-layout" id="med-layout">

    <!-- GRID -->
    <div class="med-grid-wrap" id="med-grid-wrap">
      <div class="med-grid" id="med-grid">
        <?php if (empty($medios)): ?>
          <div class="med-empty" style="grid-column:1/-1">
            <p>📭 Todavía no hay imágenes. Sube la primera usando el botón de arriba.</p>
          </div>
        <?php else: ?>
          <?php foreach ($medios as $m): ?>
          <div class="med-item"
            data-id="<?php echo $m['id']; ?>"
            data-ruta="<?php echo htmlspecialchars($m['ruta']); ?>"
            data-nombre="<?php echo htmlspecialchars($m['nombre_archivo']); ?>"
            data-titulo="<?php echo htmlspecialchars($m['titulo']); ?>"
            data-alt="<?php echo htmlspecialchars($m['alt']); ?>"
            data-desc="<?php echo htmlspecialchars($m['descripcion'] ?? ''); ?>"
            data-tipo="<?php echo htmlspecialchars($m['mime_type']); ?>"
            data-tamano="<?php echo fmtBytes($m['tamano']); ?>"
            data-fecha="<?php echo date('d/m/Y', strtotime($m['fecha'])); ?>"
            onclick="clickItem(this)"
          >
            <div class="med-item-thumb">
              <img src="<?php echo htmlspecialchars($base_url . $m['ruta']); ?>" alt="<?php echo htmlspecialchars($m['alt']); ?>" loading="lazy">
            </div>
            <?php if ($modo==='selector'): ?>
            <div class="med-item-select-overlay"><span>✓ Seleccionar</span></div>
            <?php endif; ?>
            <div class="med-item-info">
              <span class="med-item-name"><?php echo htmlspecialchars($m['titulo'] ?: $m['nombre_archivo']); ?></span>
            </div>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <?php if ($modo==='galeria'): ?>
    <!-- DETAIL PANEL -->
    <div class="med-detail" id="med-detail" style="position:relative">
      <button class="med-detail-close" onclick="cerrarDetalle()" title="Cerrar">✕</button>
      <div class="med-detail-inner">
        <img class="med-detail-thumb" id="det-thumb" src="" alt="">
        <h3 id="det-nombre"></h3>
        <div class="med-detail-info" id="det-info"></div>

        <label>Título</label>
        <input type="text" id="det-titulo" placeholder="Título descriptivo">

        <label>Texto alternativo (alt) <span style="color:#8FA3B8;font-weight:400">— importante para SEO y accesibilidad</span></label>
        <input type="text" id="det-alt" placeholder="Describe la imagen">

        <label>Descripción</label>
        <textarea id="det-desc" placeholder="Descripción opcional"></textarea>

        <label>Nombre del archivo</label>
        <div class="med-detail-rename">
          <input type="text" id="det-nombre-archivo" placeholder="nombre-del-archivo.jpg">
          <button onclick="renombrarArchivo()">Renombrar</button>
        </div>

        <label>URL de la imagen</label>
        <div class="med-detail-url">
          <input type="text" id="det-url" readonly>
          <button onclick="copiarUrl()">Copiar</button>
        </div>

        <button class="med-btn-save" onclick="guardarCambios()">Guardar cambios</button>
        <div class="med-saved-msg" id="det-saved">✓ Guardado</div>
        <button class="med-btn-delete" onclick="borrarMedio()">🗑️ Borrar permanentemente</button>
      </div>
    </div>
    <?php endif; ?>

  </div><!-- /med-layout -->
</div><!-- /admin-content -->

<div class="med-toast" id="med-toast"></div>

<script>
var BASE_URL = '<?php echo $base_url; ?>';
var MODO     = '<?php echo $modo; ?>';
var selectedId   = null;
var selectedItem = null;

/* ── DROP ZONE ── */
function toggleDropzone() {
  var dz = document.getElementById('med-dropzone');
  dz.classList.toggle('open');
}
var dropzone = document.getElementById('med-dropzone');
var fileInput = document.getElementById('med-file-input');

dropzone.addEventListener('click', function(e) {
  if (e.target !== fileInput) fileInput.click();
});
fileInput.addEventListener('change', function() {
  uploadFiles(Array.from(this.files));
  this.value = '';
});
dropzone.addEventListener('dragover', function(e) { e.preventDefault(); this.classList.add('drag'); });
dropzone.addEventListener('dragleave', function() { this.classList.remove('drag'); });
dropzone.addEventListener('drop', function(e) {
  e.preventDefault(); this.classList.remove('drag');
  uploadFiles(Array.from(e.dataTransfer.files));
});

/* ── UPLOAD ── */
function uploadFiles(files) {
  var bar = document.getElementById('med-progress-bar');
  var fill = document.getElementById('med-progress-fill');
  bar.style.display = 'block';
  var total = files.length, done = 0;

  files.forEach(function(file) {
    var fd = new FormData();
    fd.append('action', 'upload');
    fd.append('imagen', file);
    fetch('medios-ajax.php', { method:'POST', body: fd })
      .then(function(r) { return r.json(); })
      .then(function(data) {
        done++;
        fill.style.width = Math.round(done/total*100) + '%';
        if (data.error) { toast(data.error, 'err'); return; }
        prepend(data);
        actualizarContador(1);
        toast('Imagen subida: ' + (data.titulo || data.nombre_archivo), 'ok');
        if (done === total) setTimeout(function(){ bar.style.display='none'; fill.style.width='0%'; },600);
      })
      .catch(function(){ toast('Error de red al subir','err'); });
  });
}

function prepend(m) {
  var grid = document.getElementById('med-grid');
  var empty = grid.querySelector('.med-empty');
  if (empty) empty.remove();

  var div = document.createElement('div');
  div.className = 'med-item';
  div.dataset.id     = m.id;
  div.dataset.ruta   = m.ruta;
  div.dataset.nombre = m.nombre_archivo;
  div.dataset.titulo = m.titulo;
  div.dataset.alt    = m.alt;
  div.dataset.desc   = m.descripcion || '';
  div.dataset.tipo   = m.mime_type;
  div.dataset.tamano = fmtBytes(m.tamano);
  div.dataset.fecha  = fmtFecha(m.fecha);
  div.onclick = function() { clickItem(this); };
  div.innerHTML = '<div class="med-item-thumb"><img src="' + BASE_URL + m.ruta + '" alt="" loading="lazy"></div>'
    + (MODO==='selector' ? '<div class="med-item-select-overlay"><span>✓ Seleccionar</span></div>' : '')
    + '<div class="med-item-info"><span class="med-item-name">' + esc(m.titulo || m.nombre_archivo) + '</span></div>';
  grid.insertBefore(div, grid.firstChild);
}

/* ── CLICK ITEM ── */
function clickItem(el) {
  if (MODO === 'selector') {
    seleccionarImagen(el);
    return;
  }
  // Galeria: open detail
  if (selectedItem) selectedItem.classList.remove('selected');
  selectedItem = el;
  selectedId   = parseInt(el.dataset.id);
  el.classList.add('selected');
  abrirDetalle(el);
}

/* ── SELECTOR MODE ── */
function seleccionarImagen(el) {
  var data = {
    tipo:           'media_select',
    id:             parseInt(el.dataset.id),
    ruta:           el.dataset.ruta,
    nombre_archivo: el.dataset.nombre,
    titulo:         el.dataset.titulo,
    alt:            el.dataset.alt
  };
  if (window.opener) {
    window.opener.postMessage(data, '*');
    window.close();
  } else if (window.parent !== window) {
    window.parent.postMessage(data, '*');
  }
}

/* ── DETAIL PANEL ── */
function abrirDetalle(el) {
  document.getElementById('med-detail').classList.add('open');
  document.getElementById('det-thumb').src = BASE_URL + el.dataset.ruta;
  document.getElementById('det-nombre').textContent = el.dataset.nombre;
  document.getElementById('det-titulo').value = el.dataset.titulo;
  document.getElementById('det-alt').value    = el.dataset.alt;
  document.getElementById('det-desc').value   = el.dataset.desc;
  document.getElementById('det-nombre-archivo').value = el.dataset.nombre;
  document.getElementById('det-url').value = BASE_URL + el.dataset.ruta;
  document.getElementById('det-info').innerHTML =
    '<span><strong>Tipo:</strong> ' + esc(el.dataset.tipo) + '</span>' +
    '<span><strong>Tamaño:</strong> ' + esc(el.dataset.tamano) + '</span>' +
    '<span><strong>Fecha:</strong> ' + esc(el.dataset.fecha) + '</span>';
  document.getElementById('det-saved').style.display = 'none';
}

function cerrarDetalle() {
  document.getElementById('med-detail').classList.remove('open');
  if (selectedItem) selectedItem.classList.remove('selected');
  selectedItem = null; selectedId = null;
}

/* ── SAVE ── */
function guardarCambios() {
  if (!selectedId) return;
  var fd = new FormData();
  fd.append('action',      'update');
  fd.append('id',          selectedId);
  fd.append('titulo',      document.getElementById('det-titulo').value);
  fd.append('alt',         document.getElementById('det-alt').value);
  fd.append('descripcion', document.getElementById('det-desc').value);
  fetch('medios-ajax.php', { method:'POST', body:fd })
    .then(function(r){ return r.json(); })
    .then(function(d) {
      if (d.ok) {
        // Update DOM
        selectedItem.dataset.titulo = document.getElementById('det-titulo').value;
        selectedItem.dataset.alt    = document.getElementById('det-alt').value;
        selectedItem.dataset.desc   = document.getElementById('det-desc').value;
        selectedItem.querySelector('.med-item-name').textContent =
          document.getElementById('det-titulo').value || selectedItem.dataset.nombre;
        document.getElementById('det-saved').style.display = 'block';
        toast('Cambios guardados', 'ok');
      }
    });
}

/* ── RENAME ── */
function renombrarArchivo() {
  if (!selectedId) return;
  var nuevo = document.getElementById('det-nombre-archivo').value.trim();
  if (!nuevo) return;
  if (!confirm('¿Renombrar el archivo a "' + nuevo + '"? Los enlaces que ya usen la URL anterior dejarán de funcionar.')) return;
  var fd = new FormData();
  fd.append('action','rename'); fd.append('id',selectedId); fd.append('nombre_archivo',nuevo);
  fetch('medios-ajax.php',{method:'POST',body:fd})
    .then(function(r){return r.json();})
    .then(function(d) {
      if (d.ok) {
        selectedItem.dataset.ruta   = d.ruta;
        selectedItem.dataset.nombre = d.nombre_archivo;
        selectedItem.querySelector('img').src = BASE_URL + d.ruta;
        document.getElementById('det-nombre').textContent = d.nombre_archivo;
        document.getElementById('det-nombre-archivo').value = d.nombre_archivo;
        document.getElementById('det-url').value = BASE_URL + d.ruta;
        document.getElementById('det-thumb').src = BASE_URL + d.ruta;
        toast('Archivo renombrado','ok');
      } else {
        toast(d.error || 'Error al renombrar','err');
      }
    });
}

/* ── DELETE ── */
function borrarMedio() {
  if (!selectedId) return;
  if (!confirm('¿Borrar permanentemente esta imagen? Esta acción no se puede deshacer.')) return;
  var fd = new FormData();
  fd.append('action','delete'); fd.append('id',selectedId);
  fetch('medios-ajax.php',{method:'POST',body:fd})
    .then(function(r){return r.json();})
    .then(function(d) {
      if (d.ok) {
        selectedItem.remove();
        cerrarDetalle();
        actualizarContador(-1);
        toast('Imagen eliminada','ok');
        if (!document.querySelector('.med-item')) {
          document.getElementById('med-grid').innerHTML = '<div class="med-empty" style="grid-column:1/-1"><p>📭 No hay imágenes. Sube la primera.</p></div>';
        }
      }
    });
}

/* ── COPY URL ── */
function copiarUrl() {
  var url = document.getElementById('det-url').value;
  navigator.clipboard.writeText(url).then(function(){ toast('URL copiada','ok'); });
}

/* ── SEARCH ── */
function filtrarGrid(q) {
  q = q.toLowerCase();
  document.querySelectorAll('.med-item').forEach(function(el) {
    var haystack = (el.dataset.nombre+' '+el.dataset.titulo+' '+el.dataset.alt).toLowerCase();
    el.classList.toggle('med-hidden', q && haystack.indexOf(q) === -1);
  });
}

/* ── HELPERS ── */
function actualizarContador(delta) {
  var el = document.getElementById('med-count');
  var n  = parseInt(el.textContent) + delta;
  el.textContent = n + ' archivo' + (n!==1?'s':'');
}
function fmtBytes(b) {
  b = parseInt(b)||0;
  if (b >= 1048576) return (b/1048576).toFixed(1)+' MB';
  if (b >= 1024)    return (b/1024).toFixed(1)+' KB';
  return b+' B';
}
function fmtFecha(s) {
  if (!s) return '';
  var d = new Date(s.replace(' ','T'));
  return d.getDate().toString().padStart(2,'0')+'/'+
         (d.getMonth()+1).toString().padStart(2,'0')+'/'+
         d.getFullYear();
}
function esc(s) {
  return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function toast(msg, tipo) {
  var t = document.getElementById('med-toast');
  t.textContent = msg;
  t.className = 'med-toast ' + (tipo||'');
  t.classList.add('show');
  setTimeout(function(){ t.classList.remove('show'); }, 2800);
}
</script>
</body>
</html>
