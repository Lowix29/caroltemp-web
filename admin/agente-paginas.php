<?php
/* =============================================
   CAROLTEMP — Agente de Páginas
   Gestión de páginas estructurales del sitio
============================================= */
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php');
  exit;
}
require_once '../includes/db.php';

$base_url = 'http://localhost/';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agente de Páginas — CarolTemp Admin</title>
  <meta name="robots" content="noindex, nofollow">
  <?php include '../includes/admin_style.php'; ?>
  <style>
    /* ── Layout ── */
    .ap-wrap {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.5rem;
      align-items: start;
    }
    @media (max-width: 1100px) {
      .ap-wrap { grid-template-columns: 1fr; }
    }

    /* ── Paneles ── */
    .ap-panel {
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      overflow: hidden;
    }
    .ap-panel-head {
      padding: 1.25rem 1.5rem;
      border-bottom: 1px solid #f1f5f9;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
    }
    .ap-panel-title {
      font-size: 12px;
      font-weight: 700;
      color: #8FA3B8;
      letter-spacing: .07em;
      text-transform: uppercase;
    }
    .ap-panel-body { padding: 1.5rem; }

    /* ── Tabla matriz ── */
    .matriz-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }
    .matriz-table th {
      background: #F8FAFC;
      color: #576574;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .06em;
      padding: .625rem .875rem;
      text-align: center;
      border: 1px solid #E8EFF8;
      white-space: nowrap;
    }
    .matriz-table th:first-child { text-align: left; }
    .matriz-table td {
      padding: .5rem .875rem;
      border: 1px solid #F1F5F9;
      text-align: center;
      vertical-align: middle;
    }
    .matriz-table td:first-child {
      text-align: left;
      font-weight: 600;
      color: #0B2447;
      white-space: nowrap;
    }
    .matriz-table tr:hover td { background: #FAFBFC; }

    /* ── Estado celdas ── */
    .cell-ok {
      display: inline-flex;
      align-items: center;
      gap: .25rem;
      color: #16a34a;
      font-size: 12px;
      font-weight: 600;
    }
    .cell-ok-icon { font-size: 14px; }

    .cell-prov {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: .3rem;
    }
    .cell-prov-lbl {
      font-size: 11px;
      color: #D97706;
      font-weight: 700;
    }

    .cell-falta {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: .3rem;
    }
    .cell-falta-lbl {
      font-size: 11px;
      color: #DC2626;
      font-weight: 700;
    }

    .btn-accion {
      display: inline-block;
      font-size: 11px;
      font-weight: 600;
      padding: 3px 10px;
      border-radius: 100px;
      border: none;
      cursor: pointer;
      white-space: nowrap;
      transition: opacity .15s;
      line-height: 1.5;
    }
    .btn-accion:hover { opacity: .78; }
    .btn-mejorar {
      background: #FEF3C7;
      color: #92400E;
    }
    .btn-crear {
      background: #FEE2E2;
      color: #B91C1C;
    }

    /* ── Loading spinner ── */
    .ap-loading {
      display: none;
      padding: 3.5rem 1.5rem;
      text-align: center;
    }
    .spinner {
      width: 40px;
      height: 40px;
      border: 3px solid #E8EFF8;
      border-top-color: #1976D2;
      border-radius: 50%;
      animation: spin .75s linear infinite;
      margin: 0 auto 1.25rem;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .ap-loading p { color: #576574; font-size: 14px; font-weight: 600; margin: 0 0 .375rem; }
    .ap-loading small { font-size: 12px; color: #8FA3B8; }

    /* ── Empty state ── */
    .ap-empty {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 320px;
      color: #8FA3B8;
      text-align: center;
      gap: .625rem;
      padding: 2rem;
    }
    .ap-empty-icon { font-size: 3rem; opacity: .3; }
    .ap-empty p { font-size: 14px; line-height: 1.6; }
    .ap-empty strong { color: #576574; }

    /* ── Resultado ── */
    .ap-result { display: none; padding: 1.5rem; }

    .result-context {
      background: #EEF4FF;
      border: 1px solid #BFDBFE;
      border-radius: 10px;
      padding: .875rem 1.125rem;
      margin-bottom: 1.25rem;
      font-size: 13px;
      color: #1E3A5F;
      line-height: 1.5;
    }
    .result-context strong { color: #1976D2; }

    /* ── Campos de preview ── */
    .pv-field { margin-bottom: 1rem; }
    .pv-field label {
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 11px;
      font-weight: 700;
      color: #8FA3B8;
      text-transform: uppercase;
      letter-spacing: .06em;
      margin-bottom: .375rem;
    }
    .pv-field input, .pv-field textarea {
      width: 100%;
      padding: .625rem .875rem;
      border: 1.5px solid #D6E2F0;
      border-radius: 8px;
      font-size: 13.5px;
      color: #0B2447;
      font-family: inherit;
      box-sizing: border-box;
    }
    .pv-field input:focus, .pv-field textarea:focus {
      outline: none;
      border-color: #1976D2;
    }
    .pv-field textarea {
      resize: vertical;
      font-family: monospace;
      font-size: 12.5px;
      line-height: 1.55;
    }
    .char-info { font-size: 11px; font-weight: 600; }
    .char-info.ok   { color: #16a34a; }
    .char-info.warn { color: #DC2626; }
    .char-info.neu  { color: #8FA3B8; }

    /* ── Botón guardar ── */
    .btn-guardar-disco {
      width: 100%;
      padding: .875rem;
      background: #16a34a;
      color: #fff;
      border: none;
      border-radius: 10px;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: .5rem;
      transition: background .15s;
      margin-top: .75rem;
    }
    .btn-guardar-disco:hover:not(:disabled) { background: #15803d; }
    .btn-guardar-disco:disabled { opacity: .55; cursor: not-allowed; }

    /* ── Notificación flash ── */
    .ap-flash {
      display: none;
      padding: .75rem 1.125rem;
      border-radius: 8px;
      font-size: 13.5px;
      font-weight: 600;
      margin-bottom: 1rem;
      align-items: center;
      gap: .5rem;
    }
    .ap-flash.ok  { background: #D1FAE5; border: 1px solid #A7F3D0; color: #065F46; display: flex; }
    .ap-flash.err { background: #FEE2E2; border: 1px solid #FCA5A5; color: #991B1B; display: flex; }

    /* ── Loading overlay tabla ── */
    .matriz-loading {
      padding: 3rem 1.5rem;
      text-align: center;
      color: #8FA3B8;
      font-size: 14px;
    }

    /* ── Leyenda ── */
    .leyenda {
      display: flex;
      gap: 1.25rem;
      flex-wrap: wrap;
      padding: .875rem 1.5rem;
      border-top: 1px solid #F1F5F9;
      font-size: 12px;
      color: #576574;
    }
    .leyenda-item { display: flex; align-items: center; gap: .375rem; }

    /* ── Error matriz ── */
    .matriz-error {
      background: #FEF2F2;
      border: 1px solid #fecaca;
      border-radius: 10px;
      padding: 1rem 1.25rem;
      color: #dc2626;
      font-size: 13.5px;
      margin: 1rem 1.5rem;
    }

    /* ── Botón recargar ── */
    .btn-reload {
      background: none;
      border: 1.5px solid #D6E2F0;
      border-radius: 7px;
      padding: .375rem .875rem;
      font-size: 12px;
      color: #576574;
      cursor: pointer;
      font-weight: 600;
      transition: all .15s;
    }
    .btn-reload:hover { border-color: #8FA3B8; color: #0B2447; }
  </style>
</head>
<body>

<?php include '../includes/admin_sidebar.php'; ?>

<main class="main">

  <div class="topbar">
    <div>
      <h1 style="font-size:20px;font-weight:700;color:#0f172a;letter-spacing:-.01em">🗂️ Agente de Páginas</h1>
      <p style="color:#64748b;font-size:13.5px;margin-top:.25rem">Gestión y mejora de páginas estructurales del sitio</p>
    </div>
  </div>

  <div class="ap-wrap">

    <!-- ══════════════════════════════════════════════════════ -->
    <!-- PANEL IZQUIERDO: MATRIZ                               -->
    <!-- ══════════════════════════════════════════════════════ -->
    <div class="ap-panel">
      <div class="ap-panel-head">
        <span class="ap-panel-title">🗺️ Mapa de páginas</span>
        <button class="btn-reload" onclick="cargarInventario()">↻ Actualizar</button>
      </div>

      <div id="matriz-wrap">
        <div class="matriz-loading">
          <div class="spinner" style="margin:0 auto .875rem"></div>
          Cargando inventario de páginas...
        </div>
      </div>

      <div class="leyenda">
        <div class="leyenda-item">
          <span style="color:#16a34a;font-size:14px">✓</span>
          <span>Contenido OK</span>
        </div>
        <div class="leyenda-item">
          <span style="background:#FEF3C7;color:#92400E;font-size:10px;font-weight:700;padding:2px 6px;border-radius:4px">⚠</span>
          <span>Provisional</span>
        </div>
        <div class="leyenda-item">
          <span style="background:#FEE2E2;color:#B91C1C;font-size:10px;font-weight:700;padding:2px 6px;border-radius:4px">✗</span>
          <span>No existe</span>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════════════ -->
    <!-- PANEL DERECHO: EDITOR                                 -->
    <!-- ══════════════════════════════════════════════════════ -->
    <div class="ap-panel">
      <div class="ap-panel-head">
        <span class="ap-panel-title">✨ Editor IA</span>
        <span id="editor-ctx-badge" style="display:none;font-size:11px;background:#EEF4FF;color:#1976D2;font-weight:700;padding:3px 10px;border-radius:100px"></span>
      </div>

      <!-- Loading -->
      <div class="ap-loading" id="ap-loading">
        <div class="spinner"></div>
        <p>El agente está generando el contenido...</p>
        <small id="ap-loading-tip">Esto puede tardar 20-30 segundos</small>
      </div>

      <!-- Empty state -->
      <div class="ap-empty" id="ap-empty">
        <div class="ap-empty-icon">📄</div>
        <p><strong>Selecciona una página del mapa</strong><br>para mejorarla o crearla con IA</p>
        <p style="font-size:12px">Pulsa "Mejorar" en las páginas provisionales<br>o "Crear" en las que no existen todavía</p>
      </div>

      <!-- Resultado -->
      <div class="ap-result" id="ap-result">

        <div id="ap-flash" class="ap-flash"></div>

        <div class="result-context" id="result-context"></div>

        <div class="pv-field">
          <label>
            Meta Title
            <span class="char-info neu" id="mt-info"></span>
          </label>
          <input type="text" id="pv-meta-title" maxlength="80"
                 oninput="contarChars(this,'mt-info',60)">
        </div>

        <div class="pv-field">
          <label>
            Meta Description
            <span class="char-info neu" id="md-info"></span>
          </label>
          <input type="text" id="pv-meta-desc" maxlength="200"
                 oninput="contarChars(this,'md-info',160)">
        </div>

        <div class="pv-field">
          <label>Contenido PHP generado <span style="font-weight:400;color:#B0C4D8;text-transform:none;letter-spacing:0">(editable antes de guardar)</span></label>
          <textarea id="pv-php-content" rows="22"></textarea>
        </div>

        <input type="hidden" id="pv-filepath">

        <button class="btn-guardar-disco" id="btn-guardar" onclick="guardar()">
          💾 Guardar en disco
        </button>

      </div>
    </div>

  </div>

</main>

<script>
// ── Estado ───────────────────────────────────────────────────────────────────
let filepathActual = '';

// ── Carga automática al arrancar ─────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', cargarInventario);

// ── Cargar inventario de páginas ─────────────────────────────────────────────
async function cargarInventario() {
  const wrap = document.getElementById('matriz-wrap');
  wrap.innerHTML = '<div class="matriz-loading"><div class="spinner" style="margin:0 auto .875rem"></div>Cargando inventario...</div>';

  try {
    const fd = new FormData();
    fd.append('accion', 'inventario');
    const r    = await fetch('agente-paginas-api', { method: 'POST', body: fd });
    const data = await r.json();

    if (!data.ok) {
      wrap.innerHTML = '<div class="matriz-error">⚠️ ' + escp(data.error || 'Error desconocido') + '</div>';
      return;
    }

    renderMatriz(data.matriz);
  } catch (e) {
    wrap.innerHTML = '<div class="matriz-error">⚠️ Error de conexión al cargar el inventario.</div>';
  }
}

// ── Renderizar matriz ─────────────────────────────────────────────────────────
function renderMatriz(matriz) {
  const cols = [
    { key: 'zona',       label: 'Zona'       },
    { key: 'fugas',      label: 'Fugas'      },
    { key: 'desatascos', label: 'Desatascos' },
    { key: 'fontanero',  label: 'Fontanero'  },
  ];

  let html = '<div style="overflow-x:auto"><table class="matriz-table"><thead><tr>';
  html += '<th>Ciudad</th>';
  cols.forEach(c => { html += '<th>' + escp(c.label) + '</th>'; });
  html += '</tr></thead><tbody>';

  matriz.forEach(fila => {
    html += '<tr>';
    html += '<td>' + escp(fila.ciudad) + '</td>';
    cols.forEach(col => {
      const svc = fila.servicios[col.key];
      if (!svc) { html += '<td>—</td>'; return; }

      if (svc.existe && !svc.provisional) {
        html += '<td><span class="cell-ok"><span class="cell-ok-icon">✓</span> OK</span></td>';
      } else if (svc.existe && svc.provisional) {
        const onClick = col.key === 'zona'
          ? '' // zonas no tienen IA por ahora
          : `onclick="lanzarAccion('mejorar','${escp(col.key)}','${escp(fila.ciudad)}','${escp(fila.slug)}','${escp(fila.cp)}','${escp(svc.filepath)}')"`;
        html += '<td><div class="cell-prov">';
        html += '<span class="cell-prov-lbl">⚠ Provisional</span>';
        if (col.key !== 'zona') {
          html += `<button class="btn-accion btn-mejorar" ${onClick}>Mejorar</button>`;
        }
        html += '</div></td>';
      } else {
        const onClick = col.key === 'zona'
          ? '' // zonas no tienen IA por ahora
          : `onclick="lanzarAccion('crear','${escp(col.key)}','${escp(fila.ciudad)}','${escp(fila.slug)}','${escp(fila.cp)}','${escp(svc.filepath)}')"`;
        html += '<td><div class="cell-falta">';
        html += '<span class="cell-falta-lbl">✗ Falta</span>';
        if (col.key !== 'zona') {
          html += `<button class="btn-accion btn-crear" ${onClick}>Crear</button>`;
        }
        html += '</div></td>';
      }
    });
    html += '</tr>';
  });

  html += '</tbody></table></div>';
  document.getElementById('matriz-wrap').innerHTML = html;
}

// ── Lanzar acción IA ──────────────────────────────────────────────────────────
async function lanzarAccion(accion, tipo, ciudad, ciudadSlug, ciudadCp, filepath) {
  // Mostrar panel de carga
  mostrarEstadoEditor('loading');
  filepathActual = filepath;

  // Badge de contexto
  const badge = document.getElementById('editor-ctx-badge');
  const accionLabel = accion === 'mejorar' ? 'Mejorando' : 'Creando';
  const tipoLabel   = { fugas: 'Fugas', desatascos: 'Desatascos', fontanero: 'Fontanero' }[tipo] || tipo;
  badge.textContent = accionLabel + ' · ' + tipoLabel + ' · ' + ciudad;
  badge.style.display = '';

  // Tip rotativo
  const tips = [
    'Analizando el contenido actual...',
    'Investigando keywords locales...',
    'Redactando contenido optimizado para SEO...',
    'Generando FAQs específicas para la zona...',
    'Revisando meta title y description...',
    'Casi listo...',
  ];
  let tipIdx = 0;
  const tipEl = document.getElementById('ap-loading-tip');
  tipEl.textContent = tips[0];
  const tipInterval = setInterval(() => {
    tipIdx = Math.min(tipIdx + 1, tips.length - 1);
    tipEl.textContent = tips[tipIdx];
  }, 4500);

  try {
    const fd = new FormData();
    fd.append('accion',      accion);
    fd.append('tipo',        tipo);
    fd.append('ciudad',      ciudad);
    fd.append('ciudad_slug', ciudadSlug);
    fd.append('ciudad_cp',   ciudadCp);

    const r    = await fetch('agente-paginas-api', { method: 'POST', body: fd });
    const data = await r.json();
    clearInterval(tipInterval);

    if (!data.ok) {
      mostrarEstadoEditor('empty');
      badge.style.display = 'none';
      mostrarFlash('err', '⚠️ ' + (data.error || 'Error desconocido del agente'));
      // Mostrar el resultado vacío con error
      mostrarEstadoEditor('result');
      document.getElementById('result-context').innerHTML = '';
      document.getElementById('pv-meta-title').value  = '';
      document.getElementById('pv-meta-desc').value   = '';
      document.getElementById('pv-php-content').value = '';
      document.getElementById('pv-filepath').value    = '';
      mostrarFlash('err', '⚠️ ' + (data.error || 'Error desconocido del agente'));
      return;
    }

    mostrarResultado(data, accion, tipoLabel, ciudad, filepath);

  } catch (e) {
    clearInterval(tipInterval);
    mostrarEstadoEditor('empty');
    badge.style.display = 'none';
    alert('Error de conexión. Comprueba que el servidor responde correctamente.');
  }
}

// ── Mostrar resultado en el panel ─────────────────────────────────────────────
function mostrarResultado(data, accion, tipoLabel, ciudad, filepath) {
  mostrarEstadoEditor('result');
  ocultarFlash();

  // Contexto
  const accionLabel = accion === 'mejorar' ? 'Página mejorada' : 'Página creada';
  document.getElementById('result-context').innerHTML =
    '<strong>' + accionLabel + ':</strong> ' + escp(tipoLabel) + ' en ' + escp(ciudad) +
    (filepath ? ' <span style="font-size:11px;color:#8FA3B8;font-family:monospace">→ ' + escp(filepath) + '</span>' : '');

  // Campos
  const metaTitle = data.data ? (data.data.meta_title || '') : (data.meta_title || '');
  const metaDesc  = data.data ? (data.data.meta_desc  || '') : (data.meta_desc  || '');

  document.getElementById('pv-meta-title').value  = metaTitle;
  document.getElementById('pv-meta-desc').value   = metaDesc;
  document.getElementById('pv-php-content').value = data.php_contenido || '';
  document.getElementById('pv-filepath').value    = filepath;
  filepathActual = filepath;

  contarChars(document.getElementById('pv-meta-title'), 'mt-info', 60);
  contarChars(document.getElementById('pv-meta-desc'),  'md-info', 160);
}

// ── Guardar en disco ──────────────────────────────────────────────────────────
async function guardar() {
  const contenido = document.getElementById('pv-php-content').value.trim();
  const filepath  = document.getElementById('pv-filepath').value.trim() || filepathActual;

  if (!contenido) {
    mostrarFlash('err', '⚠️ El contenido PHP está vacío');
    return;
  }
  if (!filepath) {
    mostrarFlash('err', '⚠️ No hay ruta de archivo definida');
    return;
  }

  const btn = document.getElementById('btn-guardar');
  btn.disabled    = true;
  btn.textContent = '⏳ Guardando...';

  try {
    const fd = new FormData();
    fd.append('accion',    'guardar');
    fd.append('filepath',  filepath);
    fd.append('contenido', contenido);

    const r    = await fetch('agente-paginas-api', { method: 'POST', body: fd });
    const data = await r.json();

    btn.disabled    = false;
    btn.innerHTML   = '💾 Guardar en disco';

    if (data.ok) {
      mostrarFlash('ok', '✓ Guardado correctamente en ' + escp(data.filepath || filepath));
      // Refrescar el inventario para actualizar los estados
      setTimeout(cargarInventario, 800);
    } else {
      mostrarFlash('err', '⚠️ ' + (data.error || 'Error al guardar'));
    }
  } catch (e) {
    btn.disabled   = false;
    btn.innerHTML  = '💾 Guardar en disco';
    mostrarFlash('err', '⚠️ Error de conexión al guardar');
  }
}

// ── Helpers de estado del panel editor ───────────────────────────────────────
function mostrarEstadoEditor(estado) {
  document.getElementById('ap-loading').style.display = estado === 'loading' ? 'block'  : 'none';
  document.getElementById('ap-empty').style.display   = estado === 'empty'   ? 'flex'   : 'none';
  document.getElementById('ap-result').style.display  = estado === 'result'  ? 'block'  : 'none';
}

// ── Helpers de flash ──────────────────────────────────────────────────────────
function mostrarFlash(tipo, msg) {
  const el = document.getElementById('ap-flash');
  el.className  = 'ap-flash ' + tipo;
  el.innerHTML  = msg;
  el.style.display = 'flex';
}
function ocultarFlash() {
  const el = document.getElementById('ap-flash');
  el.style.display = 'none';
}

// ── Contador de caracteres ────────────────────────────────────────────────────
function contarChars(el, infoId, limite) {
  const n    = el.value.length;
  const span = document.getElementById(infoId);
  if (!span) return;
  span.textContent = n + '/' + limite;
  if (n > limite) {
    span.className = 'char-info warn';
  } else if (n >= limite - 8) {
    span.className = 'char-info ok';
  } else {
    span.className = 'char-info neu';
  }
}

// ── Escape HTML helper ────────────────────────────────────────────────────────
function escp(str) {
  return String(str || '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}
</script>
</body>
</html>
