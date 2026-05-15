<?php
/* =============================================
   CAROLTEMP — Auditor Estratégico
   Análisis de arquitectura web y plan de acción SEO
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
  <title>Auditor Estratégico — CarolTemp Admin</title>
  <meta name="robots" content="noindex, nofollow">
  <?php include '../includes/admin_style.php'; ?>
  <style>
    /* ── Layout ── */
    .au-wrap {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.5rem;
      align-items: start;
    }
    @media (max-width: 1100px) {
      .au-wrap { grid-template-columns: 1fr; }
    }

    /* ── Paneles ── */
    .au-panel {
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      overflow: hidden;
    }
    .au-panel-head {
      padding: 1.25rem 1.5rem;
      border-bottom: 1px solid #f1f5f9;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
    }
    .au-panel-title {
      font-size: 12px;
      font-weight: 700;
      color: #8FA3B8;
      letter-spacing: .07em;
      text-transform: uppercase;
    }
    .au-panel-body { padding: 1.5rem; }

    /* ── Form fields ── */
    .au-field { margin-bottom: 1.25rem; }
    .au-field label {
      display: block;
      font-size: 12px;
      font-weight: 700;
      color: #576574;
      text-transform: uppercase;
      letter-spacing: .06em;
      margin-bottom: .5rem;
    }
    .au-field textarea,
    .au-field input[type="text"] {
      width: 100%;
      padding: .75rem 1rem;
      border: 1.5px solid #D6E2F0;
      border-radius: 10px;
      font-size: 14px;
      color: #0B2447;
      font-family: inherit;
      box-sizing: border-box;
      transition: border-color .15s;
    }
    .au-field textarea:focus,
    .au-field input[type="text"]:focus {
      outline: none;
      border-color: #1976D2;
      box-shadow: 0 0 0 3px rgba(25,118,210,.08);
    }
    .au-field textarea { resize: vertical; }

    /* ── File upload label ── */
    .au-file-label {
      display: flex;
      align-items: center;
      gap: .75rem;
      padding: .875rem 1rem;
      background: #F8FAFC;
      border: 1.5px dashed #CBD5E1;
      border-radius: 10px;
      cursor: pointer;
      transition: border-color .15s, background .15s;
      font-size: 13.5px;
      color: #475569;
      font-weight: 500;
    }
    .au-file-label:hover { border-color: #1976D2; background: #EEF4FF; color: #1976D2; }
    .au-file-label.tiene-archivo {
      border-style: solid;
      border-color: #16a34a;
      background: #F0FDF4;
      color: #15803d;
    }

    /* ── dead CSS (kept to avoid removing something else) ── */
    #kw-wrap {
      display: none;
      margin-top: .75rem;
      padding: .875rem 1rem;
      background: #EEF4FF;
      border: 1.5px solid #BFDBFE;
      border-radius: 10px;
    }
    #kw-wrap label {
      font-size: 11px;
      font-weight: 700;
      color: #1976D2;
      text-transform: uppercase;
      letter-spacing: .06em;
      display: block;
      margin-bottom: .5rem;
    }
    #kw-wrap input {
      width: 100%;
      padding: .5rem .875rem;
      border: 1.5px solid #BFDBFE;
      border-radius: 8px;
      font-size: 13.5px;
      color: #0B2447;
      font-family: inherit;
      background: #fff;
      box-sizing: border-box;
    }
    #kw-wrap input:focus {
      outline: none;
      border-color: #1976D2;
    }

    /* ── Primary action button ── */
    .btn-analizar {
      width: 100%;
      padding: .9rem;
      background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
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
      transition: opacity .15s, transform .1s;
      margin-top: .25rem;
    }
    .btn-analizar:hover:not(:disabled) { opacity: .9; }
    .btn-analizar:active:not(:disabled) { transform: scale(.99); }
    .btn-analizar:disabled { opacity: .55; cursor: not-allowed; }

    /* ── Saved plan card ── */
    .au-saved-card {
      display: none;
      margin-top: 1rem;
      padding: .875rem 1rem;
      background: #F0FDF4;
      border: 1.5px solid #A7F3D0;
      border-radius: 10px;
    }
    .au-saved-card p {
      font-size: 12.5px;
      color: #065F46;
      font-weight: 600;
      margin-bottom: .5rem;
    }
    .au-saved-card button {
      font-size: 12px;
      font-weight: 600;
      padding: 5px 14px;
      border-radius: 100px;
      background: #D1FAE5;
      border: 1px solid #6EE7B7;
      color: #065F46;
      cursor: pointer;
      transition: background .15s;
    }
    .au-saved-card button:hover { background: #A7F3D0; }

    /* ── Loading state ── */
    .au-loading {
      display: none;
      padding: 4rem 1.5rem;
      text-align: center;
    }
    .spinner {
      width: 42px;
      height: 42px;
      border: 3px solid #E8EFF8;
      border-top-color: #1976D2;
      border-radius: 50%;
      animation: spin .75s linear infinite;
      margin: 0 auto 1.25rem;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .au-loading p { color: #576574; font-size: 14px; font-weight: 600; margin: 0 0 .375rem; }
    .au-loading small { font-size: 12px; color: #8FA3B8; }

    /* ── Empty state ── */
    .au-empty {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 380px;
      color: #8FA3B8;
      text-align: center;
      gap: .625rem;
      padding: 2rem;
    }
    .au-empty-icon { font-size: 3rem; opacity: .3; }
    .au-empty p { font-size: 14px; line-height: 1.6; }
    .au-empty strong { color: #576574; }

    /* ── Results wrapper ── */
    .au-result { display: none; }

    /* ── Summary card ── */
    .au-summary {
      background: linear-gradient(135deg, #1565C0 0%, #1976D2 60%, #1E88E5 100%);
      color: #fff;
      padding: 1.5rem;
      border-bottom: 1px solid rgba(255,255,255,.12);
    }
    .au-summary-text {
      font-size: 14px;
      line-height: 1.65;
      margin-bottom: 1.25rem;
      opacity: .95;
    }
    .au-stats-row {
      display: flex;
      gap: .75rem;
      flex-wrap: wrap;
    }
    .au-stat {
      background: rgba(255,255,255,.15);
      border: 1px solid rgba(255,255,255,.22);
      border-radius: 8px;
      padding: .5rem .875rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      min-width: 70px;
    }
    .au-stat-n {
      font-size: 22px;
      font-weight: 800;
      line-height: 1;
    }
    .au-stat-lbl {
      font-size: 10px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .06em;
      opacity: .8;
      margin-top: 2px;
    }
    .au-stat.ok   { background: rgba(22,163,74,.25);  border-color: rgba(22,163,74,.4); }
    .au-stat.warn { background: rgba(217,119,6,.25);  border-color: rgba(217,119,6,.4); }
    .au-stat.bad  { background: rgba(220,38,38,.25);  border-color: rgba(220,38,38,.4); }
    .au-stat.neu  { background: rgba(255,255,255,.12); }

    /* ── Info cards ── */
    .au-info-card {
      padding: 1.25rem 1.5rem;
      border-bottom: 1px solid #F1F5F9;
    }
    .au-section-title {
      font-size: 11px;
      font-weight: 700;
      color: #8FA3B8;
      text-transform: uppercase;
      letter-spacing: .07em;
      margin-bottom: .625rem;
    }
    .au-info-text {
      font-size: 13.5px;
      color: #334155;
      line-height: 1.7;
    }

    /* ── Plan section ── */
    .au-plan-head {
      padding: 1rem 1.5rem;
      border-bottom: 1px solid #F1F5F9;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      flex-wrap: wrap;
    }
    .au-plan-title {
      font-size: 13px;
      font-weight: 700;
      color: #0B2447;
    }
    .au-filter-row {
      display: flex;
      gap: .375rem;
      flex-wrap: wrap;
    }
    .au-filter-btn {
      font-size: 11px;
      font-weight: 700;
      padding: 4px 12px;
      border-radius: 100px;
      border: 1.5px solid #D6E2F0;
      background: #F8FAFC;
      color: #576574;
      cursor: pointer;
      transition: all .15s;
    }
    .au-filter-btn:hover { border-color: #8FA3B8; color: #0B2447; }
    .au-filter-btn.active {
      background: #1976D2;
      border-color: #1976D2;
      color: #fff;
    }

    /* ── Action cards ── */
    .au-cards-list {
      padding: 1rem 1.5rem;
      display: flex;
      flex-direction: column;
      gap: .75rem;
    }
    .au-card {
      background: #fff;
      border: 1.5px solid #E2E8F0;
      border-left-width: 4px;
      border-radius: 10px;
      padding: .875rem 1rem;
      display: flex;
      align-items: flex-start;
      gap: 1rem;
      transition: box-shadow .15s;
    }
    .au-card:hover { box-shadow: 0 2px 8px rgba(0,0,0,.06); }
    .au-card.prio-alta   { border-left-color: #DC2626; }
    .au-card.prio-media  { border-left-color: #D97706; }
    .au-card.prio-baja   { border-left-color: #94A3B8; }
    .au-card.completado  { opacity: .55; }
    .au-card.completado .au-card-filepath { text-decoration: line-through; color: #94A3B8; }
    .au-card.ignorado    { opacity: .4; }

    .au-card-left { flex: 1; min-width: 0; }
    .au-card-badges {
      display: flex;
      align-items: center;
      gap: .375rem;
      flex-wrap: wrap;
      margin-bottom: .5rem;
    }

    .au-accion-badge {
      font-size: 10px;
      font-weight: 800;
      padding: 2px 8px;
      border-radius: 4px;
      text-transform: uppercase;
      letter-spacing: .06em;
    }
    .au-accion-badge.crear    { background: #D1FAE5; color: #065F46; }
    .au-accion-badge.mejorar  { background: #FEF3C7; color: #92400E; }
    .au-accion-badge.redirigir{ background: #DBEAFE; color: #1E40AF; }
    .au-accion-badge.eliminar { background: #FEE2E2; color: #991B1B; }
    .au-accion-badge.mantener { background: #F1F5F9; color: #475569; }

    .au-prio {
      display: flex;
      align-items: center;
      gap: 4px;
      font-size: 11px;
      font-weight: 600;
      color: #576574;
    }
    .au-prio-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      flex-shrink: 0;
    }
    .au-prio-dot.alta   { background: #DC2626; }
    .au-prio-dot.media  { background: #D97706; }
    .au-prio-dot.baja   { background: #94A3B8; }

    .au-impacto {
      font-size: 10px;
      font-weight: 700;
      padding: 2px 7px;
      border-radius: 4px;
      background: #EEF4FF;
      color: #1976D2;
    }
    .au-tipo-badge {
      font-size: 10px;
      font-weight: 600;
      color: #94A3B8;
      padding: 2px 6px;
      border: 1px solid #E2E8F0;
      border-radius: 4px;
    }

    .au-card-filepath {
      font-family: monospace;
      font-size: 12.5px;
      color: #0B2447;
      font-weight: 600;
      margin-bottom: .375rem;
      word-break: break-all;
    }
    .au-card-motivo {
      font-size: 13px;
      color: #475569;
      line-height: 1.55;
    }
    .au-card-redirect {
      font-size: 12px;
      color: #1976D2;
      font-family: monospace;
      margin-top: .375rem;
      word-break: break-all;
    }

    /* ── Card right (status controls) ── */
    .au-card-right {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: .5rem;
      flex-shrink: 0;
    }
    .au-card-right select {
      font-size: 11.5px;
      font-weight: 600;
      padding: 4px 8px;
      border: 1.5px solid #D6E2F0;
      border-radius: 7px;
      background: #F8FAFC;
      color: #334155;
      cursor: pointer;
      font-family: inherit;
    }
    .au-card-right select:focus {
      outline: none;
      border-color: #1976D2;
    }

    /* ── Bottom action bar ── */
    .au-action-bar {
      padding: 1rem 1.5rem;
      border-top: 1px solid #F1F5F9;
      display: flex;
      justify-content: flex-end;
      gap: .75rem;
    }
    .btn-guardar-plan {
      padding: .65rem 1.5rem;
      background: #1976D2;
      color: #fff;
      border: none;
      border-radius: 9px;
      font-size: 13.5px;
      font-weight: 700;
      cursor: pointer;
      transition: opacity .15s;
    }
    .btn-guardar-plan:hover:not(:disabled) { opacity: .88; }
    .btn-guardar-plan:disabled { opacity: .55; cursor: not-allowed; }

    /* ── Flash messages ── */
    .au-flash {
      display: none;
      padding: .75rem 1.125rem;
      border-radius: 8px;
      font-size: 13.5px;
      font-weight: 600;
      margin: 1rem 1.5rem 0;
      align-items: center;
      gap: .5rem;
    }
    .au-flash.ok  { background: #D1FAE5; border: 1px solid #A7F3D0; color: #065F46; display: flex; }
    .au-flash.err { background: #FEE2E2; border: 1px solid #FCA5A5; color: #991B1B; display: flex; }
  </style>
</head>
<body>

<?php include '../includes/admin_sidebar.php'; ?>

<main class="main">

  <div class="topbar">
    <div>
      <h1 style="font-size:20px;font-weight:700;color:#0f172a;letter-spacing:-.01em">🧠 Auditor Estratégico</h1>
      <p style="color:#64748b;font-size:13.5px;margin-top:.25rem">Analiza tu arquitectura web y genera un plan de acción SEO</p>
    </div>
  </div>

  <div class="au-wrap">

    <!-- ══════════════════════════════════════════════════════ -->
    <!-- PANEL IZQUIERDO: CONFIGURAR ANÁLISIS                  -->
    <!-- ══════════════════════════════════════════════════════ -->
    <div class="au-panel">
      <div class="au-panel-head">
        <span class="au-panel-title">⚙️ Configurar análisis</span>
      </div>
      <div class="au-panel-body">

        <div class="au-field">
          <label for="objetivo">Briefing para la agencia</label>
          <p style="font-size:12px;color:#8FA3B8;margin:-.25rem 0 .75rem">La agencia ya conoce tu negocio. Solo dinos qué quieres conseguir o qué te preocupa.</p>
          <textarea id="objetivo" rows="5"
            placeholder="Ej: Quiero ser el primero en Google para fugas de agua en toda la comarca. / Revisa mi web y dime qué está mal y por dónde empezar. / Me preocupa que no aparezco en desatascos en Novelda."></textarea>
        </div>

        <div class="au-field">
          <label for="keywords_xlsx">Export de keywords Semrush <span style="font-weight:400;color:#94A3B8">(opcional)</span></label>
          <p style="font-size:12px;color:#8FA3B8;margin:-.25rem 0 .75rem">Si tienes un Excel de Semrush con tus keywords, el auditor lo procesa automáticamente, decide qué investigar y consulta Google sin que tengas que indicar nada.</p>
          <label class="au-file-label" id="au-file-label" for="keywords_xlsx">
            <span id="au-file-icon">📂</span>
            <span id="au-file-text">Seleccionar archivo .xlsx</span>
          </label>
          <input type="file" id="keywords_xlsx" name="keywords_xlsx" accept=".xlsx"
            style="display:none" onchange="onXlsxChange(this)">
        </div>

        <button class="btn-analizar" id="btn-analizar" onclick="analizar()">
          🧠 Analizar y generar plan
        </button>

        <!-- Saved plan card -->
        <div class="au-saved-card" id="au-saved-card">
          <p id="au-saved-fecha">Plan guardado</p>
          <button onclick="cargarPlan()">↩ Cargar plan guardado</button>
        </div>

      </div>
    </div>

    <!-- ══════════════════════════════════════════════════════ -->
    <!-- PANEL DERECHO: PLAN ESTRATÉGICO                       -->
    <!-- ══════════════════════════════════════════════════════ -->
    <div class="au-panel" id="panel-resultado">

      <div class="au-panel-head">
        <span class="au-panel-title">📋 Plan estratégico</span>
      </div>

      <!-- Flash -->
      <div class="au-flash" id="au-flash"></div>

      <!-- Loading -->
      <div class="au-loading" id="au-loading">
        <div class="spinner"></div>
        <p>El auditor está analizando la arquitectura...</p>
        <small id="au-loading-tip">Escaneando páginas y consultando a Claude...</small>
      </div>

      <!-- Empty state -->
      <div class="au-empty" id="au-empty">
        <div class="au-empty-icon">🧠</div>
        <p><strong>Configura el análisis y pulsa Analizar</strong><br>El auditor escaneará todas tus páginas<br>y generará un plan de acción priorizado</p>
      </div>

      <!-- Results -->
      <div class="au-result" id="au-result">

        <!-- 1. Summary -->
        <div class="au-summary" id="au-summary">
          <div class="au-summary-text" id="au-resumen"></div>
          <div class="au-stats-row" id="au-stats"></div>
        </div>

        <!-- 2. Diagnostic -->
        <div class="au-info-card">
          <div class="au-section-title">🔍 Diagnóstico</div>
          <div class="au-info-text" id="au-diagnostico"></div>
        </div>

        <!-- 3. Ideal architecture -->
        <div class="au-info-card">
          <div class="au-section-title">🏗️ Arquitectura ideal</div>
          <div class="au-info-text" id="au-arquitectura"></div>
        </div>

        <!-- 4. Plan de acción -->
        <div class="au-plan-head">
          <span class="au-plan-title" id="au-plan-count">Plan de acción</span>
          <div class="au-filter-row" id="au-filters">
            <button class="au-filter-btn active" onclick="filtrarPrioridad('todas', this)">Todas</button>
            <button class="au-filter-btn" onclick="filtrarPrioridad('alta', this)">🔴 Alta</button>
            <button class="au-filter-btn" onclick="filtrarPrioridad('media', this)">🟡 Media</button>
            <button class="au-filter-btn" onclick="filtrarPrioridad('baja', this)">⚪ Baja</button>
          </div>
        </div>

        <div class="au-cards-list" id="au-cards"></div>

        <!-- 5. Recommendations -->
        <div class="au-info-card" id="au-recom-wrap">
          <div class="au-section-title">💡 Recomendaciones adicionales</div>
          <div class="au-info-text" id="au-recomendaciones"></div>
        </div>

        <!-- Bottom bar -->
        <div class="au-action-bar">
          <button class="btn-guardar-plan" id="btn-guardar" onclick="guardarPlan()">
            💾 Guardar plan
          </button>
        </div>

      </div><!-- /au-result -->

    </div><!-- /panel-resultado -->

  </div><!-- /au-wrap -->

</main>

<script>
// ── Estado global ─────────────────────────────────────────────────────────────
let planActual = null;

// ── Al cargar la página ───────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  // Intentar cargar plan guardado automáticamente
  verificarPlanGuardado();
});

// ── Toggle keywords field ─────────────────────────────────────────────────────
// ── Verificar si hay plan guardado (para mostrar el botón) ────────────────────
async function verificarPlanGuardado() {
  try {
    const fd = new FormData();
    fd.append('accion', 'cargar_plan');
    const r    = await fetch('auditor-estrategico-api', { method: 'POST', body: fd });
    const data = await r.json();

    if (data.ok && data.plan) {
      const card = document.getElementById('au-saved-card');
      const fecha = data.plan.fecha_generacion
        ? new Date(data.plan.fecha_generacion).toLocaleString('es-ES', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' })
        : 'fecha desconocida';
      document.getElementById('au-saved-fecha').textContent = '📄 Plan guardado el ' + fecha;
      card.style.display = 'block';
    }
  } catch (e) {
    // silencioso
  }
}

// ── Analizar ──────────────────────────────────────────────────────────────────
function onXlsxChange(input) {
  const label    = document.getElementById('au-file-label');
  const textEl   = document.getElementById('au-file-text');
  const iconEl   = document.getElementById('au-file-icon');
  if (input.files && input.files[0]) {
    label.classList.add('tiene-archivo');
    iconEl.textContent  = '✅';
    textEl.textContent  = input.files[0].name;
  } else {
    label.classList.remove('tiene-archivo');
    iconEl.textContent  = '📂';
    textEl.textContent  = 'Seleccionar archivo .xlsx';
  }
}

async function analizar() {
  const objetivo  = document.getElementById('objetivo').value.trim();
  const xlsxInput = document.getElementById('keywords_xlsx');
  const tieneXlsx = xlsxInput && xlsxInput.files && xlsxInput.files[0];

  if (!objetivo) {
    mostrarFlash('err', '⚠️ Escribe el briefing antes de analizar.');
    return;
  }

  mostrarEstado('loading');
  ocultarFlash();

  const btn = document.getElementById('btn-analizar');
  btn.disabled = true;

  const tips = [
    'Escaneando páginas del sitio...',
    tieneXlsx ? 'Procesando keywords del Excel...' : 'Construyendo el inventario...',
    tieneXlsx ? 'Investigando competidores en Google...' : 'Enviando contexto a Claude...',
    'Generando plan estratégico...',
    'Analizando prioridades SEO...',
    'Casi listo...',
  ];
  let tipIdx = 0;
  const tipEl = document.getElementById('au-loading-tip');
  tipEl.textContent = tips[0];
  const tipInterval = setInterval(() => {
    tipIdx = Math.min(tipIdx + 1, tips.length - 1);
    tipEl.textContent = tips[tipIdx];
  }, 4000);

  try {
    const fd = new FormData();
    fd.append('accion',   'analizar');
    fd.append('objetivo', objetivo);
    if (tieneXlsx) fd.append('keywords_xlsx', xlsxInput.files[0]);

    const r    = await fetch('auditor-estrategico-api', { method: 'POST', body: fd });
    const data = await r.json();
    clearInterval(tipInterval);
    btn.disabled = false;

    if (!data.ok) {
      mostrarEstado('empty');
      mostrarFlash('err', '⚠️ ' + escp(data.error || 'Error desconocido'));
      return;
    }

    planActual = data.plan;
    renderPlan(planActual);
    verificarPlanGuardado();

  } catch (e) {
    clearInterval(tipInterval);
    btn.disabled = false;
    mostrarEstado('empty');
    mostrarFlash('err', '⚠️ Error de conexión. Comprueba que el servidor responde.');
  }
}

// ── Cargar plan guardado ──────────────────────────────────────────────────────
async function cargarPlan() {
  mostrarEstado('loading');
  ocultarFlash();

  try {
    const fd = new FormData();
    fd.append('accion', 'cargar_plan');
    const r    = await fetch('auditor-estrategico-api', { method: 'POST', body: fd });
    const data = await r.json();

    if (!data.ok || !data.plan) {
      mostrarEstado('empty');
      mostrarFlash('err', '⚠️ ' + escp(data.error || 'No se pudo cargar el plan'));
      return;
    }

    planActual = data.plan;
    renderPlan(planActual);

    // Rellenar el textarea de objetivo si existe
    if (data.plan.objetivo) {
      document.getElementById('objetivo').value = data.plan.objetivo;
    }

  } catch (e) {
    mostrarEstado('empty');
    mostrarFlash('err', '⚠️ Error de conexión al cargar el plan.');
  }
}

// ── Guardar plan ──────────────────────────────────────────────────────────────
async function guardarPlan() {
  if (!planActual) {
    mostrarFlash('err', '⚠️ No hay ningún plan para guardar.');
    return;
  }

  const btn = document.getElementById('btn-guardar');
  btn.disabled    = true;
  btn.textContent = '⏳ Guardando...';

  try {
    const fd = new FormData();
    fd.append('accion', 'guardar_plan');
    fd.append('plan',   JSON.stringify(planActual));

    const r    = await fetch('auditor-estrategico-api', { method: 'POST', body: fd });
    const data = await r.json();

    btn.disabled    = false;
    btn.textContent = '💾 Guardar plan';

    if (data.ok) {
      mostrarFlash('ok', '✓ Plan guardado correctamente.');
      verificarPlanGuardado();
    } else {
      mostrarFlash('err', '⚠️ ' + escp(data.error || 'Error al guardar'));
    }
  } catch (e) {
    btn.disabled    = false;
    btn.textContent = '💾 Guardar plan';
    mostrarFlash('err', '⚠️ Error de conexión al guardar.');
  }
}

// ── Marcar acción como completada / cambiar estado ────────────────────────────
async function marcarCompletado(id, estado) {
  if (!planActual) return;

  // Actualizar localmente
  if (planActual.plan) {
    planActual.plan.forEach(item => {
      if (parseInt(item.id) === parseInt(id)) {
        item.estado = estado;
      }
    });
  }

  // Actualizar visual de la card
  const card = document.querySelector('[data-id="' + id + '"]');
  if (card) {
    card.classList.remove('completado', 'ignorado');
    if (estado === 'completado') card.classList.add('completado');
    if (estado === 'ignorado')   card.classList.add('ignorado');
  }

  // Persistir en servidor
  try {
    const fd = new FormData();
    fd.append('accion', 'actualizar_accion');
    fd.append('id',     id);
    fd.append('estado', estado);
    await fetch('auditor-estrategico-api', { method: 'POST', body: fd });
  } catch (e) {
    // silencioso
  }
}

// ── Filtrar por prioridad ─────────────────────────────────────────────────────
function filtrarPrioridad(prioridad, btn) {
  // Actualizar botones activos
  document.querySelectorAll('.au-filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');

  // Mostrar / ocultar cards
  document.querySelectorAll('.au-card').forEach(card => {
    if (prioridad === 'todas') {
      card.style.display = '';
    } else {
      card.style.display = card.dataset.prioridad === prioridad ? '' : 'none';
    }
  });
}

// ── Renderizar plan ───────────────────────────────────────────────────────────
function renderPlan(plan) {
  mostrarEstado('result');
  ocultarFlash();

  // 1. Resumen
  document.getElementById('au-resumen').textContent = plan.resumen || '';

  // 2. Stats
  const stats = plan.estadisticas || {};
  const statsHtml = [
    { n: stats.paginas_ok          ?? '?', lbl: 'OK',          cls: 'ok'   },
    { n: stats.paginas_provisional ?? '?', lbl: 'Provisional', cls: 'warn' },
    { n: stats.paginas_faltantes   ?? '?', lbl: 'Faltan',      cls: 'bad'  },
    { n: stats.paginas_total_ideal ?? '?', lbl: 'Total ideal', cls: 'neu'  },
  ].map(s =>
    `<div class="au-stat ${s.cls}"><span class="au-stat-n">${escp(String(s.n))}</span><span class="au-stat-lbl">${s.lbl}</span></div>`
  ).join('');
  document.getElementById('au-stats').innerHTML = statsHtml;

  // 3. Diagnóstico
  document.getElementById('au-diagnostico').textContent = plan.diagnostico || '';

  // 4. Arquitectura ideal
  document.getElementById('au-arquitectura').textContent = plan.arquitectura_ideal || '';

  // 5. Plan de acción
  const acciones = plan.plan || [];
  document.getElementById('au-plan-count').textContent = 'Plan de acción — ' + acciones.length + ' acciones';

  const accionColors = {
    crear:     'crear',
    mejorar:   'mejorar',
    redirigir: 'redirigir',
    eliminar:  'eliminar',
    mantener:  'mantener',
  };

  let cardsHtml = '';
  acciones.forEach(item => {
    const accionKey    = (item.accion || '').toLowerCase();
    const accionClass  = accionColors[accionKey] || '';
    const prioClass    = (item.prioridad || 'baja').toLowerCase();
    const estadoClass  = item.estado && item.estado !== 'pendiente' ? item.estado : '';
    const redirectLine = (accionKey === 'redirigir' && (item.desde || item.hacia))
      ? `<div class="au-card-redirect">${escp(item.desde || '')} → ${escp(item.hacia || '')}</div>`
      : '';

    cardsHtml += `
      <div class="au-card prio-${escp(prioClass)} ${escp(estadoClass)}"
           data-id="${escp(String(item.id ?? ''))}"
           data-prioridad="${escp(prioClass)}">
        <div class="au-card-left">
          <div class="au-card-badges">
            <span class="au-accion-badge ${accionClass}">${escp(item.accion || '')}</span>
            <span class="au-prio">
              <span class="au-prio-dot ${escp(prioClass)}"></span>
              ${escp(item.prioridad || '')}
            </span>
            ${item.impacto ? `<span class="au-impacto">${escp(item.impacto.replace('_',' '))}</span>` : ''}
            ${item.tipo    ? `<span class="au-tipo-badge">${escp(item.tipo)}</span>` : ''}
          </div>
          <div class="au-card-filepath">${escp(item.pagina || '')}</div>
          <div class="au-card-motivo">${escp(item.motivo || '')}</div>
          ${redirectLine}
        </div>
        <div class="au-card-right">
          <select onchange="marcarCompletado(${parseInt(item.id ?? 0)}, this.value)"
                  title="Estado de esta acción">
            <option value="pendiente"  ${(item.estado === 'pendiente'  || !item.estado) ? 'selected' : ''}>⏳ Pendiente</option>
            <option value="completado" ${item.estado === 'completado'  ? 'selected' : ''}>✅ Completado</option>
            <option value="ignorado"   ${item.estado === 'ignorado'    ? 'selected' : ''}>🚫 Ignorado</option>
          </select>
        </div>
      </div>`;
  });

  document.getElementById('au-cards').innerHTML = cardsHtml || '<p style="color:#8FA3B8;font-size:13.5px;padding:.5rem 0">No hay acciones en el plan.</p>';

  // 6. Recomendaciones
  document.getElementById('au-recomendaciones').textContent = plan.recomendaciones || '';

  // Reset filtros
  document.querySelectorAll('.au-filter-btn').forEach(b => b.classList.remove('active'));
  const todasBtn = document.querySelector('.au-filter-btn');
  if (todasBtn) todasBtn.classList.add('active');
}

// ── Estado de los paneles ─────────────────────────────────────────────────────
function mostrarEstado(estado) {
  document.getElementById('au-loading').style.display = estado === 'loading' ? 'block' : 'none';
  document.getElementById('au-empty').style.display   = estado === 'empty'   ? 'flex'  : 'none';
  document.getElementById('au-result').style.display  = estado === 'result'  ? 'block' : 'none';
}

// ── Flash ─────────────────────────────────────────────────────────────────────
function mostrarFlash(tipo, msg) {
  const el = document.getElementById('au-flash');
  el.className = 'au-flash ' + tipo;
  el.innerHTML = msg;
  el.style.display = 'flex';
}
function ocultarFlash() {
  const el = document.getElementById('au-flash');
  el.style.display = 'none';
}

// ── Escape HTML ───────────────────────────────────────────────────────────────
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
