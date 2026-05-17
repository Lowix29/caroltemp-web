<?php
/* =============================================
   CAROLTEMP — Auditor Estratégico
   Debate estratégico SEO + generación de plan
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
    .au-field textarea {
      width: 100%;
      padding: .75rem 1rem;
      border: 1.5px solid #D6E2F0;
      border-radius: 10px;
      font-size: 14px;
      color: #0B2447;
      font-family: inherit;
      box-sizing: border-box;
      transition: border-color .15s;
      resize: vertical;
    }
    .au-field textarea:focus {
      outline: none;
      border-color: #1976D2;
      box-shadow: 0 0 0 3px rgba(25,118,210,.08);
    }

    /* ── File upload ── */
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

    /* ── Botones ── */
    .btn-iniciar {
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
      transition: opacity .15s;
      margin-top: .25rem;
    }
    .btn-iniciar:hover:not(:disabled) { opacity: .9; }
    .btn-iniciar:disabled { opacity: .55; cursor: not-allowed; }

    /* ── Chat interface ── */
    #au-chat-section { display: none; flex-direction: column; height: 100%; }

    .chat-header {
      padding: 1rem 1.5rem;
      border-bottom: 1px solid #f1f5f9;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: .75rem;
      flex-shrink: 0;
    }
    .chat-header-info {
      font-size: 12px;
      color: #8FA3B8;
      font-weight: 600;
    }
    .btn-nueva-conv {
      font-size: 11.5px;
      font-weight: 600;
      padding: 4px 12px;
      border-radius: 100px;
      border: 1.5px solid #D6E2F0;
      background: #F8FAFC;
      color: #576574;
      cursor: pointer;
      transition: all .15s;
      white-space: nowrap;
    }
    .btn-nueva-conv:hover { border-color: #8FA3B8; color: #0B2447; }

    .chat-messages {
      flex: 1;
      overflow-y: auto;
      padding: 1.25rem 1.5rem;
      display: flex;
      flex-direction: column;
      gap: 1rem;
      min-height: 320px;
      max-height: 480px;
    }

    .au-msg {
      display: flex;
      gap: .75rem;
      max-width: 92%;
      animation: fadeInMsg .2s ease;
    }
    @keyframes fadeInMsg { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:none; } }
    .au-msg.user { align-self: flex-end; flex-direction: row-reverse; }
    .au-msg.ai   { align-self: flex-start; }

    .au-msg-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      flex-shrink: 0;
    }
    .au-msg.user .au-msg-avatar { background: #EEF4FF; }
    .au-msg.ai   .au-msg-avatar { background: linear-gradient(135deg,#1565C0,#1976D2); }

    .au-msg-bubble {
      padding: .75rem 1rem;
      border-radius: 12px;
      font-size: 13.5px;
      line-height: 1.65;
      white-space: pre-wrap;
      word-break: break-word;
    }
    .au-msg.user .au-msg-bubble {
      background: #1976D2;
      color: #fff;
      border-bottom-right-radius: 4px;
    }
    .au-msg.ai .au-msg-bubble {
      background: #F8FAFC;
      border: 1px solid #E2E8F0;
      color: #0B2447;
      border-bottom-left-radius: 4px;
    }

    .au-msg-typing {
      align-self: flex-start;
      display: none;
      gap: .75rem;
    }
    .au-msg-typing .au-msg-avatar { background: linear-gradient(135deg,#1565C0,#1976D2); }
    .typing-dots {
      display: flex;
      align-items: center;
      gap: 4px;
      padding: .75rem 1rem;
      background: #F8FAFC;
      border: 1px solid #E2E8F0;
      border-radius: 12px;
      border-bottom-left-radius: 4px;
    }
    .typing-dots span {
      width: 7px;
      height: 7px;
      background: #94A3B8;
      border-radius: 50%;
      animation: typing .9s infinite;
    }
    .typing-dots span:nth-child(2) { animation-delay: .2s; }
    .typing-dots span:nth-child(3) { animation-delay: .4s; }
    @keyframes typing { 0%,60%,100% { transform:translateY(0); opacity:.4; } 30% { transform:translateY(-5px); opacity:1; } }

    .chat-input-area {
      padding: 1rem 1.5rem;
      border-top: 1px solid #F1F5F9;
      flex-shrink: 0;
    }
    .chat-input-row {
      display: flex;
      gap: .625rem;
      align-items: flex-end;
    }
    .chat-textarea {
      flex: 1;
      padding: .625rem .875rem;
      border: 1.5px solid #D6E2F0;
      border-radius: 10px;
      font-size: 13.5px;
      font-family: inherit;
      color: #0B2447;
      resize: none;
      min-height: 42px;
      max-height: 120px;
      overflow-y: auto;
      transition: border-color .15s;
      line-height: 1.5;
      box-sizing: border-box;
    }
    .chat-textarea:focus {
      outline: none;
      border-color: #1976D2;
      box-shadow: 0 0 0 3px rgba(25,118,210,.08);
    }
    .btn-enviar {
      padding: .625rem 1rem;
      background: #1976D2;
      color: #fff;
      border: none;
      border-radius: 10px;
      font-size: 13px;
      font-weight: 700;
      cursor: pointer;
      transition: opacity .15s;
      flex-shrink: 0;
      height: 42px;
    }
    .btn-enviar:hover:not(:disabled) { opacity: .88; }
    .btn-enviar:disabled { opacity: .5; cursor: not-allowed; }

    .btn-generar-plan {
      width: 100%;
      margin-top: .75rem;
      padding: .8rem;
      background: linear-gradient(135deg, #7C3AED 0%, #6D28D9 100%);
      color: #fff;
      border: none;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: .5rem;
      transition: opacity .15s;
    }
    .btn-generar-plan:hover:not(:disabled) { opacity: .9; }
    .btn-generar-plan:disabled { opacity: .55; cursor: not-allowed; }
    .chat-hint {
      font-size: 11px;
      color: #94A3B8;
      text-align: center;
      margin-top: .5rem;
    }

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

    /* ── Right panel states ── */
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

    .au-result { display: none; }

    /* ── Summary ── */
    .au-summary {
      background: linear-gradient(135deg, #6D28D9 0%, #7C3AED 60%, #8B5CF6 100%);
      color: #fff;
      padding: 1.5rem;
      border-bottom: 1px solid rgba(255,255,255,.12);
    }
    .au-summary-text { font-size: 14px; line-height: 1.65; margin-bottom: 1.25rem; opacity: .95; }
    .au-stats-row { display: flex; gap: .75rem; flex-wrap: wrap; }
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
    .au-stat-n { font-size: 22px; font-weight: 800; line-height: 1; }
    .au-stat-lbl { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; opacity: .8; margin-top: 2px; }
    .au-stat.ok   { background: rgba(22,163,74,.25);  border-color: rgba(22,163,74,.4); }
    .au-stat.warn { background: rgba(217,119,6,.25);  border-color: rgba(217,119,6,.4); }
    .au-stat.bad  { background: rgba(220,38,38,.25);  border-color: rgba(220,38,38,.4); }
    .au-stat.neu  { background: rgba(255,255,255,.12); }

    /* ── Info cards ── */
    .au-info-card { padding: 1.25rem 1.5rem; border-bottom: 1px solid #F1F5F9; }
    .au-section-title {
      font-size: 11px; font-weight: 700; color: #8FA3B8;
      text-transform: uppercase; letter-spacing: .07em; margin-bottom: .625rem;
    }
    .au-info-text { font-size: 13.5px; color: #334155; line-height: 1.7; }

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
    .au-plan-title { font-size: 13px; font-weight: 700; color: #0B2447; }
    .au-filter-row { display: flex; gap: .375rem; flex-wrap: wrap; }
    .au-filter-btn {
      font-size: 11px; font-weight: 700; padding: 4px 12px;
      border-radius: 100px; border: 1.5px solid #D6E2F0;
      background: #F8FAFC; color: #576574; cursor: pointer; transition: all .15s;
    }
    .au-filter-btn:hover { border-color: #8FA3B8; color: #0B2447; }
    .au-filter-btn.active { background: #7C3AED; border-color: #7C3AED; color: #fff; }

    /* ── Action cards ── */
    .au-cards-list { padding: 1rem 1.5rem; display: flex; flex-direction: column; gap: .75rem; }
    .au-card {
      background: #fff; border: 1.5px solid #E2E8F0; border-left-width: 4px;
      border-radius: 10px; padding: .875rem 1rem;
      display: flex; align-items: flex-start; gap: 1rem; transition: box-shadow .15s;
    }
    .au-card:hover { box-shadow: 0 2px 8px rgba(0,0,0,.06); }
    .au-card.prio-alta  { border-left-color: #DC2626; }
    .au-card.prio-media { border-left-color: #D97706; }
    .au-card.prio-baja  { border-left-color: #94A3B8; }

    .au-card-left { flex: 1; min-width: 0; }
    .au-card-badges { display: flex; align-items: center; gap: .375rem; flex-wrap: wrap; margin-bottom: .5rem; }
    .au-accion-badge {
      font-size: 10px; font-weight: 800; padding: 2px 8px;
      border-radius: 4px; text-transform: uppercase; letter-spacing: .06em;
    }
    .au-accion-badge.crear    { background: #D1FAE5; color: #065F46; }
    .au-accion-badge.mejorar  { background: #FEF3C7; color: #92400E; }
    .au-accion-badge.redirigir{ background: #DBEAFE; color: #1E40AF; }
    .au-accion-badge.eliminar { background: #FEE2E2; color: #991B1B; }
    .au-accion-badge.mantener { background: #F1F5F9; color: #475569; }
    .au-prio { display: flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 600; color: #576574; }
    .au-prio-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
    .au-prio-dot.alta  { background: #DC2626; }
    .au-prio-dot.media { background: #D97706; }
    .au-prio-dot.baja  { background: #94A3B8; }
    .au-impacto { font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 4px; background: #EEF4FF; color: #7C3AED; }
    .au-tipo-badge { font-size: 10px; font-weight: 600; color: #94A3B8; padding: 2px 6px; border: 1px solid #E2E8F0; border-radius: 4px; }
    .au-card-filepath { font-family: monospace; font-size: 12.5px; color: #0B2447; font-weight: 600; margin-bottom: .375rem; word-break: break-all; }
    .au-card-motivo { font-size: 13px; color: #475569; line-height: 1.55; }
    .au-card-redirect { font-size: 12px; color: #1976D2; font-family: monospace; margin-top: .375rem; word-break: break-all; }

    /* ── Bottom bar ── */
    .au-action-bar {
      padding: 1rem 1.5rem; border-top: 1px solid #F1F5F9;
      display: flex; justify-content: flex-end; gap: .75rem;
    }
    .btn-guardar-plan {
      padding: .65rem 1.5rem; background: #7C3AED; color: #fff;
      border: none; border-radius: 9px; font-size: 13.5px; font-weight: 700;
      cursor: pointer; transition: opacity .15s;
    }
    .btn-guardar-plan:hover:not(:disabled) { opacity: .88; }
    .btn-guardar-plan:disabled { opacity: .55; cursor: not-allowed; }

    /* ── Flash ── */
    .au-flash {
      display: none; padding: .75rem 1.125rem; border-radius: 8px;
      font-size: 13.5px; font-weight: 600; margin: 1rem 1.5rem 0;
      align-items: center; gap: .5rem;
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
      <p style="color:#64748b;font-size:13.5px;margin-top:.25rem">Debate la estrategia SEO con el auditor antes de generar el plan</p>
    </div>
  </div>

  <div class="au-wrap">

    <!-- ══════════════════════════════════ -->
    <!-- PANEL IZQUIERDO                   -->
    <!-- ══════════════════════════════════ -->
    <div class="au-panel" id="panel-izquierdo">
      <div class="au-panel-head">
        <span class="au-panel-title" id="panel-izq-title">⚙️ Configurar análisis</span>
      </div>

      <!-- FASE 1: Formulario inicial -->
      <div id="au-form-section">
        <div class="au-panel-body">

          <div class="au-field">
            <label for="objetivo">Briefing para el auditor</label>
            <p style="font-size:12px;color:#8FA3B8;margin:-.25rem 0 .75rem">El auditor ya conoce tu negocio. Cuéntale qué quieres conseguir, qué te preocupa, o simplemente pídele que analice la web.</p>
            <textarea id="objetivo" rows="5"
              placeholder="Ej: Revisa la web y dime qué mejorarías. / Quiero ser el primero en Google en toda la comarca. / Me parece que hay páginas que se están comiendo entre ellas."></textarea>
          </div>

          <div class="au-field">
            <label for="keywords_xlsx">Export Semrush <span style="font-weight:400;color:#94A3B8">(opcional)</span></label>
            <p style="font-size:12px;color:#8FA3B8;margin:-.25rem 0 .75rem">El auditor procesa el Excel automáticamente y decide qué investigar.</p>
            <label class="au-file-label" id="au-file-label" for="keywords_xlsx">
              <span id="au-file-icon">📂</span>
              <span id="au-file-text">Seleccionar archivo .xlsx</span>
            </label>
            <input type="file" id="keywords_xlsx" name="keywords_xlsx" accept=".xlsx"
              style="display:none" onchange="onXlsxChange(this)">
          </div>

          <button class="btn-iniciar" id="btn-iniciar" onclick="iniciarDebate()">
            🧠 Iniciar debate estratégico
          </button>

          <!-- Saved plan card -->
          <div class="au-saved-card" id="au-saved-card">
            <p id="au-saved-fecha">Plan guardado</p>
            <button onclick="cargarPlan()">↩ Cargar plan guardado</button>
          </div>

        </div>
      </div>

      <!-- FASE 2: Chat de debate -->
      <div id="au-chat-section" style="display:none;flex-direction:column;">

        <div class="chat-header">
          <span class="chat-header-info" id="chat-turno-info">Debatiendo estrategia...</span>
          <button class="btn-nueva-conv" onclick="nuevaConversacion()">↩ Nueva</button>
        </div>

        <div class="chat-messages" id="chat-messages">
          <!-- Los mensajes se renderizan aquí -->
          <div class="au-msg-typing" id="msg-typing">
            <div class="au-msg-avatar">🧠</div>
            <div class="typing-dots">
              <span></span><span></span><span></span>
            </div>
          </div>
        </div>

        <div class="chat-input-area">
          <div class="chat-input-row">
            <textarea class="chat-textarea" id="chat-input"
              placeholder="Escribe tu respuesta... (Ctrl+Enter para enviar)"
              rows="1" oninput="autoResize(this)"
              onkeydown="onChatKeydown(event)"></textarea>
            <button class="btn-enviar" id="btn-enviar" onclick="enviarMensaje()">↑</button>
          </div>
          <button class="btn-generar-plan" id="btn-generar-plan" onclick="generarPlan()" style="display:none">
            📋 Generar plan definitivo
          </button>
          <button class="btn-exportar-conv" id="btn-exportar-conv" onclick="exportarConversacion()" style="display:none;margin-top:6px;width:100%;padding:7px;background:#f1f5f9;border:1px solid #cbd5e1;border-radius:8px;color:#475569;font-size:13px;cursor:pointer;">
            💬 Exportar conversación (para revisión)
          </button>
          <p class="chat-hint">Cuando estés de acuerdo con la estrategia, genera el plan</p>
        </div>

      </div>

    </div><!-- /panel-izquierdo -->

    <!-- ══════════════════════════════════ -->
    <!-- PANEL DERECHO: PLAN               -->
    <!-- ══════════════════════════════════ -->
    <div class="au-panel" id="panel-resultado">

      <div class="au-panel-head">
        <span class="au-panel-title">📋 Plan estratégico</span>
      </div>

      <div class="au-flash" id="au-flash"></div>

      <div class="au-loading" id="au-loading">
        <div class="spinner"></div>
        <p>Generando plan de acción...</p>
        <small>Convirtiendo el debate en acciones concretas...</small>
      </div>

      <div class="au-empty" id="au-empty">
        <div class="au-empty-icon">📋</div>
        <p><strong>El plan aparece aquí</strong><br>Debate con el auditor y cuando<br>estés de acuerdo, genera el plan</p>
      </div>

      <div class="au-result" id="au-result">

        <div class="au-summary" id="au-summary">
          <div class="au-summary-text" id="au-resumen"></div>
          <div class="au-stats-row" id="au-stats"></div>
        </div>

        <div class="au-info-card">
          <div class="au-section-title">🔍 Diagnóstico</div>
          <div class="au-info-text" id="au-diagnostico"></div>
        </div>

        <div class="au-info-card">
          <div class="au-section-title">🏗️ Arquitectura ideal</div>
          <div class="au-info-text" id="au-arquitectura"></div>
        </div>

        <div class="au-plan-head">
          <span class="au-plan-title" id="au-plan-count">Plan de acción</span>
          <div class="au-filter-row">
            <button class="au-filter-btn active" onclick="filtrarPrioridad('todas', this)">Todas</button>
            <button class="au-filter-btn" onclick="filtrarPrioridad('alta', this)">🔴 Alta</button>
            <button class="au-filter-btn" onclick="filtrarPrioridad('media', this)">🟡 Media</button>
            <button class="au-filter-btn" onclick="filtrarPrioridad('baja', this)">⚪ Baja</button>
          </div>
        </div>

        <div class="au-cards-list" id="au-cards"></div>

        <div class="au-info-card" id="au-recom-wrap">
          <div class="au-section-title">💡 Recomendaciones adicionales</div>
          <div class="au-info-text" id="au-recomendaciones"></div>
        </div>

        <div class="au-action-bar">
          <button class="btn-guardar-plan" id="btn-guardar" onclick="guardarPlan()">
            💾 Guardar plan
          </button>
        </div>

      </div>

    </div><!-- /panel-resultado -->

  </div>

</main>

<script>
// ── Estado global ─────────────────────────────────────────────────────────────
let historial  = [];
let planActual = null;
let enviando   = false;
let turnoCount = 0;

// ── Al cargar ─────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function() {
  verificarPlanGuardado();
});

// ── xlsx change ───────────────────────────────────────────────────────────────
function onXlsxChange(input) {
  var label  = document.getElementById('au-file-label');
  var textEl = document.getElementById('au-file-text');
  var iconEl = document.getElementById('au-file-icon');
  if (input.files && input.files[0]) {
    label.classList.add('tiene-archivo');
    iconEl.textContent = '✅';
    textEl.textContent = input.files[0].name;
  } else {
    label.classList.remove('tiene-archivo');
    iconEl.textContent = '📂';
    textEl.textContent = 'Seleccionar archivo .xlsx';
  }
}

// ── Verificar plan guardado ───────────────────────────────────────────────────
async function verificarPlanGuardado() {
  try {
    var fd = new FormData();
    fd.append('accion', 'cargar_plan');
    var r    = await fetch('auditor-estrategico-api.php', { method: 'POST', body: fd });
    var data = await r.json();
    if (data.ok && data.plan) {
      var card  = document.getElementById('au-saved-card');
      var fecha = data.plan.fecha_generacion
        ? new Date(data.plan.fecha_generacion).toLocaleString('es-ES', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' })
        : 'fecha desconocida';
      document.getElementById('au-saved-fecha').textContent = '📄 Plan guardado el ' + fecha;
      card.style.display = 'block';
    }
  } catch(e) {}
}

// ── Leer archivo como base64 (chunked para archivos grandes) ─────────────────
function leerArchivoBase64(file) {
  return new Promise(function(resolve, reject) {
    var reader = new FileReader();
    reader.onload = function(e) {
      try {
        var bytes     = new Uint8Array(e.target.result);
        var binary    = '';
        var chunkSize = 8192;
        for (var i = 0; i < bytes.length; i += chunkSize) {
          binary += String.fromCharCode.apply(null, bytes.subarray(i, i + chunkSize));
        }
        resolve(btoa(binary));
      } catch(err) {
        reject('Error convirtiendo a base64: ' + err.message);
      }
    };
    reader.onerror = function() { reject('Error leyendo archivo'); };
    reader.readAsArrayBuffer(file);
  });
}

// ── Iniciar debate ────────────────────────────────────────────────────────────
async function iniciarDebate() {
  var objetivo  = document.getElementById('objetivo').value.trim();
  var xlsxInput = document.getElementById('keywords_xlsx');
  var tieneXlsx = xlsxInput && xlsxInput.files && xlsxInput.files[0];

  if (!objetivo) {
    alert('Escribe el briefing antes de iniciar el debate.');
    return;
  }

  var btn = document.getElementById('btn-iniciar');
  btn.disabled    = true;
  btn.textContent = '⏳ Conectando con el auditor...';

  // Transición a chat
  mostrarChat();
  mostrarTyping(true);

  try {
    var fd = new FormData();
    fd.append('accion',    'debatir');
    fd.append('mensaje',   objetivo);
    fd.append('historial', '[]');

    // Enviar Excel como base64 (evita problemas de file upload con mod_rewrite)
    if (tieneXlsx) {
      try {
        var b64 = await leerArchivoBase64(xlsxInput.files[0]);
        fd.append('xlsx_b64', b64);
      } catch(e) {
        mostrarTyping(false);
        btn.disabled    = false;
        btn.textContent = '🧠 Iniciar debate estratégico';
        volverAForm();
        alert('No se pudo leer el archivo Excel: ' + e + '\nPrueba con un archivo más pequeño o en formato .xlsx');
        return;
      }
    }

    var r    = await fetch('auditor-estrategico-api.php', { method: 'POST', body: fd });
    var data = await r.json();

    mostrarTyping(false);
    btn.disabled    = false;
    btn.textContent = '🧠 Iniciar debate estratégico';

    if (!data.ok) {
      volverAForm();
      alert('Error: ' + (data.error || 'Error desconocido'));
      return;
    }

    historial  = data.historial;
    turnoCount = 1;

    // Mostrar mensaje del usuario (primer turno)
    agregarMensaje('user', objetivo);
    agregarMensaje('ai',   data.respuesta);

    // Badge de estado Excel
    if (tieneXlsx) {
      var xd = data.xlsx_debug || {};
      var badge = document.createElement('div');
      badge.style.cssText = 'margin:6px 0 0 48px;font-size:12px;';
      if (xd.procesado) {
        badge.innerHTML = '<span style="background:#d1fae5;color:#065f46;padding:2px 8px;border-radius:8px;">📊 Semrush procesado — modo ' + (xd.modo||'?') + ', ' + xd.filas + ' filas</span>';
      } else if (xd.recibido && xd.parse_error) {
        badge.innerHTML = '<span style="background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:8px;">⚠️ Excel recibido pero no se pudo parsear: ' + xd.parse_error + '</span>';
      } else {
        badge.innerHTML = '<span style="background:#fee2e2;color:#991b1b;padding:2px 8px;border-radius:8px;">❌ Excel NO recibido por PHP</span>';
      }
      var chat = document.getElementById('au-chat-messages');
      if (chat) chat.appendChild(badge);
    }

    actualizarBtnGenerar();

  } catch(e) {
    mostrarTyping(false);
    btn.disabled    = false;
    btn.textContent = '🧠 Iniciar debate estratégico';
    volverAForm();
    alert('Error de conexión.');
  }
}

// ── Enviar mensaje de seguimiento ─────────────────────────────────────────────
async function enviarMensaje() {
  if (enviando) return;
  var input   = document.getElementById('chat-input');
  var mensaje = input.value.trim();
  if (!mensaje) return;

  enviando = true;
  input.value = '';
  autoResize(input);
  document.getElementById('btn-enviar').disabled = true;

  agregarMensaje('user', mensaje);
  mostrarTyping(true);
  scrollChat();

  try {
    var fd = new FormData();
    fd.append('accion',    'debatir');
    fd.append('mensaje',   mensaje);
    fd.append('historial', JSON.stringify(historial));

    var r    = await fetch('auditor-estrategico-api.php', { method: 'POST', body: fd });
    var data = await r.json();

    mostrarTyping(false);

    if (!data.ok) {
      agregarMensaje('ai', '⚠️ Error: ' + (data.error || 'Error desconocido'));
    } else {
      historial = data.historial;
      turnoCount++;
      agregarMensaje('ai', data.respuesta);
      actualizarBtnGenerar();
    }
  } catch(e) {
    mostrarTyping(false);
    agregarMensaje('ai', '⚠️ Error de conexión. Inténtalo de nuevo.');
  }

  enviando = false;
  document.getElementById('btn-enviar').disabled = false;
  scrollChat();
}

// ── Generar plan definitivo ───────────────────────────────────────────────────
async function generarPlan() {
  if (historial.length === 0) return;

  document.getElementById('btn-generar-plan').disabled    = true;
  document.getElementById('btn-generar-plan').textContent = '⏳ Generando plan...';
  mostrarEstadoDerecho('loading');

  try {
    var fd = new FormData();
    fd.append('accion',    'finalizar_plan');
    fd.append('historial', JSON.stringify(historial));

    var r    = await fetch('auditor-estrategico-api.php', { method: 'POST', body: fd });
    var data = await r.json();

    document.getElementById('btn-generar-plan').disabled = false;
    document.getElementById('btn-generar-plan').innerHTML = '📋 Generar plan definitivo';

    if (!data.ok) {
      mostrarEstadoDerecho('empty');
      mostrarFlash('err', '⚠️ ' + escp(data.error || 'Error desconocido'));
      return;
    }

    planActual = data.plan;
    renderPlan(planActual);
    verificarPlanGuardado();

  } catch(e) {
    document.getElementById('btn-generar-plan').disabled = false;
    document.getElementById('btn-generar-plan').innerHTML = '📋 Generar plan definitivo';
    mostrarEstadoDerecho('empty');
    mostrarFlash('err', '⚠️ Error de conexión al generar el plan.');
  }
}

// ── Cargar plan guardado ──────────────────────────────────────────────────────
async function cargarPlan() {
  mostrarEstadoDerecho('loading');
  try {
    var fd = new FormData();
    fd.append('accion', 'cargar_plan');
    var r    = await fetch('auditor-estrategico-api.php', { method: 'POST', body: fd });
    var data = await r.json();
    if (!data.ok || !data.plan) {
      mostrarEstadoDerecho('empty');
      mostrarFlash('err', '⚠️ ' + escp(data.error || 'No se pudo cargar el plan'));
      return;
    }
    planActual = data.plan;
    renderPlan(planActual);
  } catch(e) {
    mostrarEstadoDerecho('empty');
    mostrarFlash('err', '⚠️ Error de conexión.');
  }
}

// ── Guardar plan ──────────────────────────────────────────────────────────────
async function guardarPlan() {
  if (!planActual) return;
  var btn = document.getElementById('btn-guardar');
  btn.disabled    = true;
  btn.textContent = '⏳ Guardando...';
  try {
    var fd = new FormData();
    fd.append('accion', 'guardar_plan');
    fd.append('plan',   JSON.stringify(planActual));
    var r    = await fetch('auditor-estrategico-api.php', { method: 'POST', body: fd });
    var data = await r.json();
    btn.disabled    = false;
    btn.textContent = '💾 Guardar plan';
    if (data.ok) {
      mostrarFlash('ok', '✓ Plan guardado. Ya puedes ejecutarlo desde el Agente de Páginas.');
      verificarPlanGuardado();
    } else {
      mostrarFlash('err', '⚠️ ' + escp(data.error || 'Error al guardar'));
    }
  } catch(e) {
    btn.disabled    = false;
    btn.textContent = '💾 Guardar plan';
    mostrarFlash('err', '⚠️ Error de conexión.');
  }
}

// ── Nueva conversación ────────────────────────────────────────────────────────
function nuevaConversacion() {
  historial  = [];
  turnoCount = 0;
  planActual = null;
  volverAForm();
  mostrarEstadoDerecho('empty');
  ocultarFlash();
}

// ── UI helpers ────────────────────────────────────────────────────────────────
function mostrarChat() {
  document.getElementById('au-form-section').style.display = 'none';
  var chat = document.getElementById('au-chat-section');
  chat.style.display = 'flex';
  document.getElementById('panel-izq-title').textContent = '💬 Debate estratégico';
}

function volverAForm() {
  document.getElementById('au-form-section').style.display = 'block';
  document.getElementById('au-chat-section').style.display = 'none';
  document.getElementById('panel-izq-title').textContent   = '⚙️ Configurar análisis';
  document.getElementById('chat-messages').innerHTML =
    '<div class="au-msg-typing" id="msg-typing"><div class="au-msg-avatar">🧠</div><div class="typing-dots"><span></span><span></span><span></span></div></div>';
  document.getElementById('btn-generar-plan').style.display = 'none';
}

function agregarMensaje(rol, texto) {
  var wrap   = document.getElementById('chat-messages');
  var typing = document.getElementById('msg-typing');

  var div = document.createElement('div');
  div.className = 'au-msg ' + rol;
  div.innerHTML =
    '<div class="au-msg-avatar">' + (rol === 'user' ? '👤' : '🧠') + '</div>' +
    '<div class="au-msg-bubble">' + escp(texto) + '</div>';

  wrap.insertBefore(div, typing);
  scrollChat();
}

function mostrarTyping(show) {
  var el = document.getElementById('msg-typing');
  if (el) el.style.display = show ? 'flex' : 'none';
}

function scrollChat() {
  var wrap = document.getElementById('chat-messages');
  if (wrap) wrap.scrollTop = wrap.scrollHeight;
}

function actualizarBtnGenerar() {
  var btn = document.getElementById('btn-generar-plan');
  var btnExp = document.getElementById('btn-exportar-conv');
  if (turnoCount >= 1) {
    btn.style.display = 'flex';
    if (btnExp) btnExp.style.display = 'block';
  }
}

function exportarConversacion() {
  if (!historial || historial.length === 0) return;

  var lineas = ['=== CONVERSACIÓN AUDITOR ESTRATÉGICO ===', ''];
  historial.forEach(function(msg, i) {
    var rol = msg.role === 'user' ? '👤 USUARIO' : '🧠 AUDITOR';
    lineas.push('--- ' + rol + ' ---');
    lineas.push(msg.content);
    lineas.push('');
  });

  var txt  = lineas.join('\n');
  var blob = new Blob([txt], { type: 'text/plain; charset=utf-8' });
  var url  = URL.createObjectURL(blob);
  var a    = document.createElement('a');
  a.href     = url;
  a.download = 'conversacion-auditor.txt';
  a.click();
  URL.revokeObjectURL(url);
}

function autoResize(el) {
  el.style.height = 'auto';
  el.style.height = Math.min(el.scrollHeight, 120) + 'px';
}

function onChatKeydown(e) {
  if (e.key === 'Enter' && e.ctrlKey) {
    e.preventDefault();
    enviarMensaje();
  }
}

// ── Filtrar por prioridad ─────────────────────────────────────────────────────
function filtrarPrioridad(prioridad, btn) {
  document.querySelectorAll('.au-filter-btn').forEach(function(b) { b.classList.remove('active'); });
  btn.classList.add('active');
  document.querySelectorAll('.au-card').forEach(function(card) {
    card.style.display = prioridad === 'todas' || card.dataset.prioridad === prioridad ? '' : 'none';
  });
}

// ── Renderizar plan ───────────────────────────────────────────────────────────
function renderPlan(plan) {
  mostrarEstadoDerecho('result');
  ocultarFlash();

  document.getElementById('au-resumen').textContent = plan.resumen || '';

  var stats = plan.estadisticas || {};
  var statsData = [
    { n: stats.paginas_ok          != null ? stats.paginas_ok          : '?', lbl: 'OK',          cls: 'ok'   },
    { n: stats.paginas_provisional != null ? stats.paginas_provisional : '?', lbl: 'Provisional', cls: 'warn' },
    { n: stats.paginas_faltantes   != null ? stats.paginas_faltantes   : '?', lbl: 'Faltan',      cls: 'bad'  },
    { n: stats.paginas_total_ideal != null ? stats.paginas_total_ideal : '?', lbl: 'Total ideal', cls: 'neu'  },
  ];
  document.getElementById('au-stats').innerHTML = statsData.map(function(s) {
    return '<div class="au-stat ' + s.cls + '"><span class="au-stat-n">' + escp(String(s.n)) + '</span><span class="au-stat-lbl">' + s.lbl + '</span></div>';
  }).join('');

  document.getElementById('au-diagnostico').textContent   = plan.diagnostico        || '';
  document.getElementById('au-arquitectura').textContent  = plan.arquitectura_ideal  || '';
  document.getElementById('au-recomendaciones').textContent = plan.recomendaciones  || '';

  var acciones = plan.plan || [];
  document.getElementById('au-plan-count').textContent = 'Plan de acción — ' + acciones.length + ' acciones';

  var cardsHtml = '';
  acciones.forEach(function(item) {
    var ak   = (item.accion || '').toLowerCase();
    var pc   = (item.prioridad || 'baja').toLowerCase();
    var redir = (ak === 'redirigir' && (item.desde || item.hacia))
      ? '<div class="au-card-redirect">' + escp(item.desde || '') + ' → ' + escp(item.hacia || '') + '</div>'
      : '';
    cardsHtml += '<div class="au-card prio-' + escp(pc) + '" data-id="' + escp(String(item.id != null ? item.id : '')) + '" data-prioridad="' + escp(pc) + '">' +
      '<div class="au-card-left">' +
        '<div class="au-card-badges">' +
          '<span class="au-accion-badge ' + ak + '">' + escp(item.accion || '') + '</span>' +
          '<span class="au-prio"><span class="au-prio-dot ' + escp(pc) + '"></span>' + escp(item.prioridad || '') + '</span>' +
          (item.impacto ? '<span class="au-impacto">' + escp(item.impacto.replace('_',' ')) + '</span>' : '') +
          (item.tipo    ? '<span class="au-tipo-badge">' + escp(item.tipo) + '</span>' : '') +
        '</div>' +
        '<div class="au-card-filepath">' + escp(item.pagina || '') + '</div>' +
        '<div class="au-card-motivo">' + escp(item.motivo || '') + '</div>' +
        redir +
      '</div>' +
    '</div>';
  });

  document.getElementById('au-cards').innerHTML = cardsHtml || '<p style="color:#8FA3B8;font-size:13.5px;padding:.5rem 0">No hay acciones en el plan.</p>';

  document.querySelectorAll('.au-filter-btn').forEach(function(b) { b.classList.remove('active'); });
  var primera = document.querySelector('.au-filter-btn');
  if (primera) primera.classList.add('active');
}

// ── Estado panel derecho ──────────────────────────────────────────────────────
function mostrarEstadoDerecho(estado) {
  document.getElementById('au-loading').style.display = estado === 'loading' ? 'block' : 'none';
  document.getElementById('au-empty').style.display   = estado === 'empty'   ? 'flex'  : 'none';
  document.getElementById('au-result').style.display  = estado === 'result'  ? 'block' : 'none';
}

// ── Flash ─────────────────────────────────────────────────────────────────────
function mostrarFlash(tipo, msg) {
  var el = document.getElementById('au-flash');
  el.className = 'au-flash ' + tipo;
  el.innerHTML = msg;
}
function ocultarFlash() {
  document.getElementById('au-flash').className = 'au-flash';
}

// ── Escape ────────────────────────────────────────────────────────────────────
function escp(str) {
  return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}
</script>
</body>
</html>
