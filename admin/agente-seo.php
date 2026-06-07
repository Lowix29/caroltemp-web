<?php
/* =============================================
   CAROLTEMP — Agente SEO
============================================= */
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php');
  exit;
}

require_once '../includes/db.php';

$error_guardado = '';
if (isset($_SESSION['agente_error'])) {
  $error_guardado = $_SESSION['agente_error'];
  unset($_SESSION['agente_error']);
}

$zonas = ['Elda','Petrer','Novelda','Monóvar','Sax','Pinoso','Monforte del Cid','Salinas','Aspe','Villena'];
$categorias = [
  'fontaneria'   => 'Fontanería',
  'climatizacion'=> 'Climatización',
  'reformas'     => 'Reformas',
  'urgencias'    => 'Urgencias',
];
try {
  $servicios = $pdo->query('SELECT nombre FROM servicios_proyectos ORDER BY orden ASC')->fetchAll(PDO::FETCH_COLUMN);
} catch (Exception $e) {
  $servicios = ['Fontanería','Climatización','Reformas','Instalaciones','Urgencias'];
}

// Stats de informes si existen
$tiene_informes = false;
try {
  $n = $pdo->query('SELECT COUNT(*) FROM seo_informes')->fetchColumn();
  $tiene_informes = $n > 0;
} catch (Exception $e) {}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agente SEO — CarolTemp Admin</title>
  <meta name="robots" content="noindex, nofollow">
  <?php
    $base_url = 'http://localhost/';
    include '../includes/admin_style.php';
  ?>
  <style>
    /* ── Layout ── */
    .agente-wrap { display: grid; grid-template-columns: 400px 1fr; gap: 1.5rem; align-items: start; }
    .agente-panel { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 1.75rem; }
    .agente-panel-title { font-size: 12px; font-weight: 700; color: #8FA3B8; letter-spacing: .07em;
      text-transform: uppercase; margin-bottom: 1.25rem; display: flex; align-items: center; gap: .5rem; }

    /* ── Campos ── */
    .ag-field { margin-bottom: 1rem; }
    .ag-field label { display: block; font-size: 13px; font-weight: 600; color: #0B2447; margin-bottom: .375rem; }
    .ag-field input, .ag-field select, .ag-field textarea {
      width: 100%; padding: .625rem .875rem; border: 1.5px solid #D6E2F0; border-radius: 8px;
      font-size: 14px; color: #0B2447; background: #fff; font-family: inherit; box-sizing: border-box;
      transition: border-color .15s;
    }
    .ag-field input:focus, .ag-field select:focus, .ag-field textarea:focus {
      outline: none; border-color: #1976D2;
    }
    .ag-field textarea { resize: vertical; min-height: 85px; line-height: 1.55; }
    .ag-field small { font-size: 12px; color: #8FA3B8; display: block; margin-top: .25rem; }

    /* ── Selector tipo ── */
    .tipo-btns { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem; }
    .tipo-btn { padding: .625rem .5rem; border: 1.5px solid #D6E2F0; border-radius: 8px; background: #fff;
      font-size: 13px; font-weight: 600; color: #576574; cursor: pointer; text-align: center;
      transition: all .15s; }
    .tipo-btn.active { border-color: #1976D2; background: #EEF4FF; color: #1976D2; }
    .tipo-btn:hover:not(.active) { border-color: #8FA3B8; }

    /* ── Audio ── */
    .audio-box { border: 1.5px dashed #D6E2F0; border-radius: 10px; padding: 1rem 1.25rem;
      display: flex; align-items: center; gap: 1rem; }
    .btn-mic { display: inline-flex; align-items: center; gap: .4rem; background: #0B2447; color: #fff;
      border: none; padding: .5rem 1.1rem; border-radius: 100px; font-size: 13px; font-weight: 600;
      cursor: pointer; white-space: nowrap; transition: background .15s; flex-shrink: 0; }
    .btn-mic:hover { background: #1976D2; }
    .btn-mic.grabando { background: #dc2626; animation: pulse 1s infinite; }
    @keyframes pulse { 0%,100%{opacity:1}50%{opacity:.65} }
    .audio-status { font-size: 12px; color: #8FA3B8; line-height: 1.4; }

    /* ── Upload imágenes ── */
    .upload-drop { border: 1.5px dashed #D6E2F0; border-radius: 10px; padding: .875rem;
      text-align: center; cursor: pointer; transition: border-color .15s, background .15s; }
    .upload-drop:hover { border-color: #1976D2; background: #F5F8FC; }
    .upload-drop p { font-size: 13px; color: #8FA3B8; margin: 0; }
    .upload-drop span { font-size: 11px; color: #B0C4D8; }
    .thumbs-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: .4rem; margin-top: .625rem; }
    .thumb-item { position: relative; aspect-ratio: 1; border-radius: 6px; overflow: hidden; background: #f1f5f9; }
    .thumb-item img { width: 100%; height: 100%; object-fit: cover; }
    .thumb-remove { position: absolute; top: 3px; right: 3px; background: rgba(0,0,0,.6); color: #fff;
      border: none; border-radius: 50%; width: 18px; height: 18px; font-size: 10px; cursor: pointer;
      line-height: 1; display: flex; align-items: center; justify-content: center; }

    /* ── Botón generar ── */
    .btn-generar { width: 100%; padding: .875rem; margin-top: 1.25rem;
      background: linear-gradient(135deg, #0B2447 0%, #1976D2 100%);
      color: #fff; border: none; border-radius: 10px; font-size: 15px; font-weight: 700;
      cursor: pointer; display: flex; align-items: center; justify-content: center; gap: .5rem;
      transition: opacity .15s; }
    .btn-generar:hover:not(:disabled) { opacity: .88; }
    .btn-generar:disabled { opacity: .55; cursor: not-allowed; }

    /* ── Loading ── */
    .ag-loading { display: none; padding: 3.5rem 1rem; text-align: center; }
    .spinner { width: 42px; height: 42px; border: 3px solid #E8EFF8; border-top-color: #1976D2;
      border-radius: 50%; animation: spin .75s linear infinite; margin: 0 auto 1.25rem; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .ag-loading p { color: #576574; font-size: 14px; font-weight: 600; margin: 0 0 .375rem; }
    .loading-tip { font-size: 12px; color: #8FA3B8; }

    /* ── Empty state ── */
    .ag-empty { display: flex; flex-direction: column; align-items: center; justify-content: center;
      min-height: 380px; color: #8FA3B8; text-align: center; gap: .5rem; }
    .ag-empty-icon { font-size: 3.5rem; opacity: .35; }

    /* ── Preview resultado ── */
    .ag-preview { display: none; }
    .seo-notes { background: #F0FDF4; border: 1px solid #bbf7d0; border-radius: 10px;
      padding: 1rem 1.25rem; margin-bottom: 1.5rem; font-size: 13px; color: #166534; line-height: 1.6; }
    .seo-notes strong { display: block; font-size: 11px; letter-spacing: .06em; text-transform: uppercase;
      margin-bottom: .375rem; color: #15803d; }

    .pv-field { margin-bottom: 1rem; }
    .pv-field label { display: block; font-size: 11px; font-weight: 700; color: #8FA3B8;
      text-transform: uppercase; letter-spacing: .06em; margin-bottom: .375rem; }
    .pv-field input, .pv-field select, .pv-field textarea {
      width: 100%; padding: .625rem .875rem; border: 1.5px solid #D6E2F0; border-radius: 8px;
      font-size: 14px; color: #0B2447; font-family: inherit; box-sizing: border-box;
    }
    .pv-field input:focus, .pv-field select:focus, .pv-field textarea:focus {
      outline: none; border-color: #1976D2;
    }
    .pv-field textarea { resize: vertical; }
    .char-info { font-size: 11px; text-align: right; margin-top: .2rem; color: #8FA3B8; }
    .char-info.ok   { color: #16a34a; font-weight: 600; }
    .char-info.warn { color: #dc2626; font-weight: 600; }

    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

    .btn-guardar { width: 100%; padding: .875rem; background: #16a34a; color: #fff; border: none;
      border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer; margin-top: .5rem;
      display: flex; align-items: center; justify-content: center; gap: .5rem;
      transition: background .15s; }
    .btn-guardar:hover { background: #15803d; }

    /* ── Informes tab ── */
    .tabs { display: flex; gap: .375rem; margin-bottom: 1.5rem; border-bottom: 2px solid #E8EFF8; padding-bottom: 0; }
    .tab-btn { padding: .625rem 1rem; font-size: 13px; font-weight: 600; color: #576574; background: none;
      border: none; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -2px;
      transition: color .15s; }
    .tab-btn.active { color: #1976D2; border-bottom-color: #1976D2; }
    .tab-pane { display: none; }
    .tab-pane.active { display: block; }

    .informe-box { border: 1.5px dashed #D6E2F0; border-radius: 10px; padding: 1.5rem; text-align: center; }
    .informe-box p { font-size: 13px; color: #576574; margin: .5rem 0; line-height: 1.6; }
    .btn-subir-csv { display: inline-flex; align-items: center; gap: .5rem; background: #0B2447;
      color: #fff; border: none; padding: .625rem 1.25rem; border-radius: 100px; font-size: 13px;
      font-weight: 600; cursor: pointer; margin-top: .75rem; }

    /* ── Botón investigar ── */
    .btn-investigar { width: 100%; padding: .7rem; margin-top: .5rem; margin-bottom: .25rem;
      background: #fff; color: #1976D2; border: 2px solid #1976D2; border-radius: 10px;
      font-size: 14px; font-weight: 700; cursor: pointer; display: flex; align-items: center;
      justify-content: center; gap: .4rem; transition: all .15s; }
    .btn-investigar:hover:not(:disabled) { background: #EEF4FF; }
    .btn-investigar:disabled { opacity: .5; cursor: not-allowed; }
    .btn-investigar.hecho { background: #EEF4FF; border-color: #93c5fd; color: #1d4ed8; }

    /* ── Informe de investigación ── */
    .ag-inv { display: none; }
    .inv-header { display: flex; align-items: center; justify-content: space-between;
      margin-bottom: 1rem; flex-wrap: wrap; gap: .5rem; }
    .inv-urls { display: flex; flex-wrap: wrap; gap: .375rem; margin-bottom: 1rem; }
    .inv-url-chip { font-size: 11px; background: #F0F9FF; border: 1px solid #BAE6FD;
      color: #0369A1; padding: 3px 8px; border-radius: 100px; max-width: 220px;
      overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .inv-url-chip span { font-weight: 700; }
    .inv-report-box { background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 10px;
      padding: 1.25rem 1.5rem; font-size: 13px; color: #334155; line-height: 1.7;
      max-height: 420px; overflow-y: auto; margin-bottom: 1.25rem; }
    .inv-report-box h2 { font-size: 13.5px; font-weight: 700; color: #0B2447;
      margin: 1rem 0 .375rem; border-bottom: 1px solid #E2E8F0; padding-bottom: .25rem; }
    .inv-report-box h3 { font-size: 13px; font-weight: 700; color: #1976D2; margin: .75rem 0 .25rem; }
    .inv-report-box ul { margin: .375rem 0 .5rem 1.25rem; }
    .inv-report-box li { margin-bottom: .2rem; }
    .inv-report-box strong { color: #0B2447; }
    .btn-generar-con-inv { width: 100%; padding: .875rem; background: linear-gradient(135deg,#15803d,#16a34a);
      color: #fff; border: none; border-radius: 10px; font-size: 15px; font-weight: 700;
      cursor: pointer; display: flex; align-items: center; justify-content: center; gap: .5rem;
      transition: opacity .15s; }
    .btn-generar-con-inv:hover { opacity: .88; }

    /* ── Responsive ── */
    @media (max-width: 1024px) {
      .agente-wrap { grid-template-columns: 1fr; }
    }

    /* ── Auditor SEO ── */
    .audit-cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
    .audit-card { background: #fff; border: 1px solid #E8EFF8; border-radius: 12px; padding: 1.25rem; text-align: center; }
    .audit-card-val { font-size: 2rem; font-weight: 800; line-height: 1; }
    .audit-card-lbl { font-size: 12px; color: #8FA3B8; margin-top: .375rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; }
    .audit-card.red .audit-card-val { color: #E53E3E; }
    .audit-card.amber .audit-card-val { color: #D97706; }
    .audit-card.green .audit-card-val { color: #059669; }
    .audit-card.blue .audit-card-val { color: #1976D2; }

    .audit-issue { background: #fff; border: 1px solid #E8EFF8; border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: .75rem; display: flex; gap: 1rem; align-items: flex-start; }
    .audit-badge { font-size: 10px; font-weight: 700; padding: 3px 10px; border-radius: 100px; white-space: nowrap; text-transform: uppercase; letter-spacing: .05em; flex-shrink: 0; margin-top: 2px; }
    .audit-badge.alta { background: #FEE2E2; color: #B91C1C; }
    .audit-badge.media { background: #FEF3C7; color: #92400E; }
    .audit-badge.baja { background: #F1F5F9; color: #64748B; }
    .audit-issue-url { font-size: 12px; color: #1976D2; font-family: monospace; margin-bottom: .25rem; }
    .audit-issue-desc { font-size: 13px; color: #576574; margin-bottom: .375rem; line-height: 1.5; }
    .audit-issue-accion { font-size: 12px; color: #059669; font-weight: 600; }

    .audit-opp { background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: .625rem; }
    .audit-opp-url { font-size: 12px; color: #059669; font-family: monospace; margin-bottom: .25rem; }
    .audit-cani { background: #FFF7ED; border: 1px solid #FED7AA; border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: .625rem; }
    .audit-notas { background: #EEF4FF; border: 1px solid #BFDBFE; border-radius: 12px; padding: 1.25rem 1.5rem; margin-bottom: 2rem; font-size: 14px; color: #1E3A5F; line-height: 1.7; }

    @media (max-width: 768px) {
      .audit-cards { grid-template-columns: repeat(2, 1fr); }
    }
  </style>
</head>
<body>
<?php include '../includes/admin_sidebar.php'; ?>

<main class="main">

  <div class="main-header">
    <div>
      <h1 class="main-title">🤖 Agente SEO</h1>
      <p class="main-sub">Genera artículos y proyectos optimizados para Google 2026 y buscadores con IA</p>
    </div>
    <?php if ($tiene_informes): ?>
      <div style="background:#EEF4FF;border:1px solid #BFDBFE;border-radius:8px;padding:.5rem 1rem;font-size:13px;color:#1976D2;font-weight:600">
        📊 Aprendiendo de tus datos de posicionamiento
      </div>
    <?php endif; ?>
  </div>

  <?php if ($error_guardado): ?>
    <div style="background:#FEF2F2;border:1px solid #fecaca;border-radius:10px;padding:1rem 1.25rem;margin-bottom:1.5rem;color:#dc2626;font-size:14px;">
      ⚠️ <?= htmlspecialchars($error_guardado) ?>
    </div>
  <?php endif; ?>

  <!-- Tabs -->
  <div class="tabs">
    <button class="tab-btn active" onclick="cambiarTab('crear', this)">✨ Crear contenido</button>
    <button class="tab-btn" onclick="cambiarTab('informes', this)">📊 Informes de posicionamiento</button>
    <button class="tab-btn" onclick="cambiarTab('auditor', this)">🔍 Auditor SEO</button>
  </div>

  <!-- ── TAB: CREAR ── -->
  <div class="tab-pane active" id="tab-crear">
    <div class="agente-wrap">

      <!-- Columna izquierda: formulario -->
      <div class="agente-panel">
        <div class="agente-panel-title">⚙️ Configurar generación</div>

        <!-- Tipo -->
        <div class="ag-field">
          <label>Tipo de contenido</label>
          <div class="tipo-btns">
            <button type="button" class="tipo-btn active" onclick="setTipo('articulo', this)">📄 Artículo</button>
            <button type="button" class="tipo-btn" onclick="setTipo('proyecto', this)">🔧 Proyecto</button>
          </div>
        </div>

        <!-- Keyword -->
        <div class="ag-field">
          <label for="keyword">Keyword o tema principal *</label>
          <input type="text" id="keyword" placeholder="Ej: instalación termo eléctrico, detección de fugas...">
          <small>La keyword más importante que quieres posicionar</small>
        </div>

        <!-- Zona -->
        <div class="ag-field">
          <label for="zona">Zona / Ciudad</label>
          <select id="zona">
            <option value="">Sin zona específica</option>
            <?php foreach ($zonas as $z): ?>
              <option value="<?= htmlspecialchars($z) ?>"><?= htmlspecialchars($z) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Widget sidebar -->
        <div class="ag-field">
          <label for="sidebar-tipo-input">Widget del sidebar <span style="font-weight:400;color:#8FA3B8">(no vinculante)</span></label>
          <select id="sidebar-tipo-input" onchange="actualizarSidebarPreview()">
            <option value="">— Genérico (hub ciudad) —</option>
            <option value="hub">Hub ciudad — "Fontanero en {Ciudad}"</option>
            <option value="urgencias">Urgencias 24h — "Urgencias 24h en {Ciudad}"</option>
            <option value="desatascos">Desatascos — "Desatascos en {Ciudad}"</option>
            <option value="fugas">Fugas — "Reparación de fugas en {Ciudad}"</option>
          </select>
          <small>El enlace del sidebar apuntará a la página elegida de la ciudad</small>
        </div>

        <!-- Notas manuales -->
        <div class="ag-field">
          <label for="notas-investigar">Indicaciones manuales (opcional)</label>
          <textarea id="notas-investigar" rows="2" placeholder="Ej: Salinas es en Alicante, no San Miguel de Salinas. Evitar referencias a turismo."></textarea>
          <small>El agente las tendrá en cuenta al analizar y generar el informe</small>
        </div>

        <!-- Botón investigar -->
        <button class="btn-investigar" id="btn-investigar" onclick="investigar()">
          🔍 Investigar competencia en Google
        </button>
        <p style="font-size:11.5px;color:#8FA3B8;text-align:center;margin:-.125rem 0 .75rem">
          Analiza los top 4 rivales antes de escribir el artículo
        </p>

        <!-- Audio -->
        <div class="ag-field">
          <label>Audio del trabajo (opcional)</label>
          <div class="audio-box">
            <button type="button" class="btn-mic" id="btn-mic" onclick="toggleAudio()">
              🎙️ <span id="mic-txt">Grabar</span>
            </button>
            <p class="audio-status" id="audio-status">Graba contando qué hiciste, dónde y cómo quedó. El agente lo usa para dar autenticidad al artículo.</p>
          </div>
        </div>

        <!-- Transcripción -->
        <div class="ag-field">
          <label for="transcripcion">Descripción del trabajo</label>
          <textarea id="transcripcion" rows="4" placeholder="Se rellena al grabar, o escribe directamente: qué avería era, cómo la resolviste, qué material usaste, cuánto tardaste..."></textarea>
        </div>

        <!-- Imágenes -->
        <div class="ag-field">
          <label>Fotos del trabajo</label>
          <div class="upload-drop" id="upload-drop" onclick="document.getElementById('file-input').click()">
            <p>📷 Haz clic o arrastra fotos aquí</p>
            <span>JPG, PNG, WebP — máx 5MB por foto</span>
          </div>
          <input type="file" id="file-input" accept="image/*" multiple style="display:none" onchange="subirImagenes(this.files)">
          <div class="thumbs-grid" id="thumbs-grid"></div>
        </div>

        <!-- Notas -->
        <div class="ag-field">
          <label for="notas">Notas adicionales (opcional)</label>
          <textarea id="notas" rows="2" placeholder="Marca del equipo, precio aproximado, detalle especial del trabajo..."></textarea>
        </div>

        <button class="btn-generar" id="btn-generar" onclick="generar()">
          ✨ Generar contenido SEO
        </button>
      </div>

      <!-- Columna derecha: resultado -->
      <div class="agente-panel">
        <div class="agente-panel-title">👁️ Resultado generado</div>

        <!-- Investigación loading -->
        <div class="ag-loading" id="inv-loading" style="display:none">
          <div class="spinner"></div>
          <p>Investigando competidores...</p>
          <p class="loading-tip" id="inv-tip">Buscando en Google...</p>
        </div>

        <!-- Resultado de investigación -->
        <div class="ag-inv" id="ag-inv">
          <div class="inv-header">
            <div class="agente-panel-title" style="margin-bottom:0">🔍 Investigación competitiva</div>
            <button onclick="investigar()" style="font-size:12px;background:none;border:1px solid #D6E2F0;border-radius:6px;padding:4px 10px;cursor:pointer;color:#576574">↻ Repetir</button>
          </div>
          <div class="inv-urls" id="inv-urls"></div>
          <div class="inv-report-box" id="inv-report-box"></div>
          <button class="btn-generar-con-inv" onclick="generar()">
            ✨ Generar artículo con esta investigación
          </button>
        </div>

        <!-- Loading -->
        <div class="ag-loading" id="ag-loading">
          <div class="spinner"></div>
          <p>El agente SEO está trabajando...</p>
          <p class="loading-tip" id="loading-tip">Analizando keyword y estructura óptima</p>
        </div>

        <!-- Empty -->
        <div class="ag-empty" id="ag-empty">
          <div class="ag-empty-icon">🤖</div>
          <p><strong>Rellena el formulario</strong> y pulsa<br>Generar contenido SEO</p>
        </div>

        <!-- Preview -->
        <div class="ag-preview" id="ag-preview">

          <div class="seo-notes" id="seo-notes-box">
            <strong>🧠 Decisiones SEO del agente</strong>
            <span id="seo-notes-txt"></span>
          </div>

          <form method="POST" action="agente-seo-guardar" id="form-guardar">
            <input type="hidden" name="tipo"      id="f-tipo">
            <input type="hidden" name="imagenes"  id="f-imagenes">
            <input type="hidden" name="imagen"    id="f-imagen">

            <div class="grid-2">
              <div class="pv-field">
                <label>Meta Title <span id="mt-info" class="char-info"></span></label>
                <input type="text" name="meta_title" id="f-meta-title" maxlength="70"
                       oninput="contarChars(this,'mt-info',60)">
              </div>
              <div class="pv-field">
                <label>Slug (URL)</label>
                <input type="text" name="slug" id="f-slug">
              </div>
            </div>

            <div class="pv-field">
              <label>Meta Description <span id="md-info" class="char-info"></span></label>
              <textarea name="meta_desc" id="f-meta-desc" rows="2" maxlength="180"
                        oninput="contarChars(this,'md-info',160)"></textarea>
            </div>

            <div class="pv-field">
              <label>Título H1</label>
              <input type="text" name="titulo" id="f-titulo">
            </div>

            <div class="pv-field">
              <label id="extracto-lbl">Extracto</label>
              <textarea name="extracto" id="f-extracto" rows="2"></textarea>
              <input type="hidden" name="descripcion" id="f-descripcion">
            </div>

            <div class="grid-2">
              <div class="pv-field">
                <label>Zona</label>
                <input type="text" name="zona" id="f-zona" oninput="actualizarSidebarPreview()">
              </div>
              <div class="pv-field" id="pv-cat">
                <label>Categoría</label>
                <select name="categoria" id="f-categoria">
                  <?php foreach ($categorias as $v => $l): ?>
                    <option value="<?= $v ?>"><?= $l ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="pv-field" id="pv-svc" style="display:none">
                <label>Servicio</label>
                <select name="servicio" id="f-servicio">
                  <?php foreach ($servicios as $s): ?>
                    <option value="<?= htmlspecialchars($s) ?>"><?= htmlspecialchars($s) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="pv-field" id="pv-sidebar">
              <label>Widget del sidebar <span style="font-weight:400;color:#B0C4D8">(no vinculante — puedes cambiarlo al revisar el borrador)</span></label>
              <select name="sidebar_tipo" id="f-sidebar-tipo" onchange="actualizarSidebarPreview()">
                <option value="">— Genérico (hub ciudad por defecto) —</option>
                <option value="hub">Hub ciudad — "Fontanero en {Ciudad}" → /fontanero/{ciudad}</option>
                <option value="urgencias">Urgencias 24h — "Urgencias 24h en {Ciudad}" → /fontanero/{ciudad}/urgencias</option>
                <option value="desatascos">Desatascos — "Desatascos en {Ciudad}" → /fontanero/{ciudad}/desatascos</option>
                <option value="fugas">Fugas — "Reparación de fugas en {Ciudad}" → /fontanero/{ciudad}/fugas</option>
              </select>
              <span id="sidebar-preview-txt" style="display:none;font-size:11.5px;color:#16a34a;margin-top:5px"></span>
            </div>

            <div class="pv-field">
              <label>Contenido HTML <span style="font-weight:400;color:#B0C4D8">(editable antes de guardar)</span></label>
              <textarea name="contenido" id="f-contenido" rows="16"
                style="font-family:monospace;font-size:12px;line-height:1.5"></textarea>
            </div>

            <button type="submit" class="btn-guardar">
              💾 Guardar como borrador y revisar
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- ── TAB: INFORMES ── -->
  <div class="tab-pane" id="tab-informes">
    <div style="max-width:680px">
      <div class="agente-panel">
        <div class="agente-panel-title">📊 Informes de posicionamiento</div>

        <p style="font-size:14px;color:#576574;line-height:1.7;margin-bottom:1.5rem">
          Sube informes de <strong>Google Search Console</strong> para que el agente aprenda qué páginas posicionan mejor y replique esos patrones en el contenido nuevo.
        </p>

        <div class="informe-box">
          <p>📥 <strong>Cómo exportar desde Google Search Console:</strong></p>
          <p>Search Console → Resultados de búsqueda → Exportar → Descargar CSV<br>
          Filtra por los últimos 3 meses para datos relevantes.</p>
          <form method="POST" action="agente-seo-importar.php" enctype="multipart/form-data" style="margin-top:1rem">
            <input type="file" name="csv_gsc" accept=".csv" style="font-size:13px;margin-bottom:.75rem;display:block">
            <button type="submit" class="btn-subir-csv">📊 Importar informe GSC</button>
          </form>
        </div>

        <?php if ($tiene_informes): ?>
          <div style="margin-top:1.5rem">
            <p style="font-size:13px;font-weight:700;color:#0B2447;margin-bottom:.75rem">Top páginas posicionando:</p>
            <?php
              try {
                $rows = $pdo->query('
                  SELECT url, ROUND(AVG(posicion),1) as pos, SUM(clicks) as clicks
                  FROM seo_informes GROUP BY url ORDER BY clicks DESC LIMIT 10
                ')->fetchAll();
                foreach ($rows as $row):
            ?>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:.625rem .875rem;border:1px solid #E8EFF8;border-radius:8px;margin-bottom:.375rem;font-size:13px">
              <span style="color:#0B2447;font-weight:500"><?= htmlspecialchars($row['url']) ?></span>
              <div style="display:flex;gap:1rem;flex-shrink:0">
                <span style="color:#8FA3B8">Pos. <?= $row['pos'] ?></span>
                <span style="color:#1976D2;font-weight:600"><?= number_format($row['clicks']) ?> clicks</span>
              </div>
            </div>
            <?php endforeach; } catch (Exception $e) {} ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- ── TAB: AUDITOR SEO ── -->
  <div class="tab-pane" id="tab-auditor">
    <div style="max-width:960px">

      <!-- Formulario de inicio -->
      <div id="audit-inicio" style="padding:2rem 0">
        <div style="background:#fff;border:1px solid #E8EFF8;border-radius:14px;padding:2rem;margin-bottom:1.5rem">
          <div style="font-size:12px;font-weight:700;color:#8FA3B8;letter-spacing:.07em;text-transform:uppercase;margin-bottom:1rem">⚙️ Configurar auditoría</div>

          <!-- Upload Excel -->
          <label style="display:block;font-size:13px;font-weight:600;color:#0B2447;margin-bottom:.375rem">
            Excel de Semrush <span style="font-weight:400;color:#8FA3B8">(opcional — .xlsx con columnas Keyword, Seed keyword, Volume, Difficulty)</span>
          </label>
          <div id="audit-drop-zone" onclick="document.getElementById('audit-xlsx-input').click()"
               style="border:2px dashed #D6E2F0;border-radius:10px;padding:1.5rem;text-align:center;cursor:pointer;transition:border-color .2s;background:#FAFCFF">
            <div style="font-size:1.5rem;margin-bottom:.5rem">📊</div>
            <div id="audit-file-lbl" style="font-size:13px;color:#576574;font-weight:600">Arrastra el .xlsx aquí o haz clic para seleccionar</div>
            <div style="font-size:11px;color:#8FA3B8;margin-top:.25rem">Exporta desde Semrush → Keyword Overview → Export</div>
          </div>
          <input type="file" id="audit-xlsx-input" accept=".xlsx,.csv" style="display:none" onchange="auditArchivoSeleccionado(this)">

          <div style="margin-top:.75rem;display:flex;align-items:center;gap:.75rem">
            <div style="flex:1;height:1px;background:#E8EFF8"></div>
            <span style="font-size:11px;color:#8FA3B8;font-weight:600">O PEGA KEYWORDS MANUALMENTE</span>
            <div style="flex:1;height:1px;background:#E8EFF8"></div>
          </div>
          <textarea id="audit-keywords" rows="3" placeholder="fontanero urgente elda&#10;detectar fuga agua petrer&#10;cambiar termo monovar" style="width:100%;margin-top:.75rem;padding:.625rem .875rem;border:1.5px solid #D6E2F0;border-radius:8px;font-size:13px;color:#0B2447;font-family:inherit;box-sizing:border-box;resize:vertical;line-height:1.6"></textarea>
        </div>

        <div style="text-align:center">
          <button onclick="ejecutarAuditoria()" style="display:inline-flex;align-items:center;gap:.5rem;background:linear-gradient(135deg,#0B2447,#1976D2);color:#fff;border:none;padding:.875rem 2.25rem;border-radius:10px;font-size:15px;font-weight:700;cursor:pointer">
            🚀 Ejecutar auditoría completa
          </button>
          <p style="font-size:12px;color:#8FA3B8;margin-top:.75rem">Analiza toda la estructura del sitio + BD + keywords. Tarda 20-40 segundos.</p>
        </div>
      </div>
        <p style="font-size:12px;color:#8FA3B8;margin-top:.875rem">La auditoría tarda entre 20 y 40 segundos</p>
      </div>

      <!-- Loading -->
      <div id="audit-loading" style="display:none;text-align:center;padding:4rem 1rem">
        <div class="spinner" style="margin:0 auto 1.25rem"></div>
        <p style="color:#576574;font-size:14px;font-weight:600;margin:0 0 .375rem">Auditando el sitio con IA...</p>
        <p id="audit-tip" style="font-size:12px;color:#8FA3B8">Construyendo inventario de páginas...</p>
      </div>

      <!-- Resultados -->
      <div id="audit-resultados" style="display:none">

        <!-- Cabecera -->
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
          <h2 style="font-size:1.1rem;font-weight:700;color:#0B2447;margin:0" id="audit-count-lbl"></h2>
          <button onclick="ejecutarAuditoria()" style="font-size:13px;background:#fff;border:1.5px solid #D6E2F0;border-radius:8px;padding:.5rem 1rem;cursor:pointer;color:#576574;font-weight:600">
            ↻ Nueva auditoría
          </button>
        </div>

        <!-- Notas ejecutivas -->
        <div id="audit-notas-box" class="audit-notas" style="display:none;margin-bottom:1.5rem">
          <strong style="display:block;font-size:11px;font-weight:700;color:#1976D2;letter-spacing:.07em;text-transform:uppercase;margin-bottom:.5rem">📋 Resumen ejecutivo</strong>
          <span id="audit-notas-txt"></span>
        </div>

        <!-- Tarjetas resumen -->
        <div class="audit-cards" id="audit-cards" style="margin-bottom:2rem"></div>

        <!-- Matriz zona × servicio -->
        <div id="audit-matriz-section" style="display:none;margin-bottom:2rem">
          <h3 style="font-size:13px;font-weight:700;color:#0B2447;margin-bottom:.75rem;text-transform:uppercase;letter-spacing:.05em">🗺️ Cobertura zona × servicio</h3>
          <div style="overflow-x:auto">
            <table id="audit-matriz-tabla" style="width:100%;border-collapse:collapse;font-size:13px"></table>
          </div>
        </div>

        <!-- Estructura recomendada -->
        <div id="audit-estructura-section" style="display:none;margin-bottom:2rem">
          <h3 style="font-size:13px;font-weight:700;color:#0B2447;margin-bottom:.75rem;text-transform:uppercase;letter-spacing:.05em">🏗️ Arquitectura recomendada</h3>
          <div class="audit-notas" id="audit-estructura-txt" style="background:#F8FFF9;border-color:#BBF7D0;color:#166534"></div>
        </div>

        <!-- Clústeres de intención -->
        <div id="audit-clusters-section" style="display:none;margin-bottom:2rem">
          <h3 style="font-size:13px;font-weight:700;color:#0B2447;margin-bottom:.75rem;text-transform:uppercase;letter-spacing:.05em">🔑 Clústeres de intención (Semrush)</h3>
          <div id="audit-clusters-list"></div>
        </div>

        <!-- Keywords gaps (modo manual) -->
        <div id="audit-kw-section" style="display:none;margin-bottom:2rem">
          <h3 style="font-size:13px;font-weight:700;color:#0B2447;margin-bottom:.75rem;text-transform:uppercase;letter-spacing:.05em">🔑 Cobertura de keywords</h3>
          <div id="audit-kw-list"></div>
        </div>

        <!-- Canibalización -->
        <div id="audit-cani-section" style="display:none;margin-bottom:2rem">
          <h3 style="font-size:13px;font-weight:700;color:#0B2447;margin-bottom:.75rem;text-transform:uppercase;letter-spacing:.05em">⚠️ Posible canibalización</h3>
          <div id="audit-cani-list"></div>
        </div>

        <!-- Problemas de contenido -->
        <div id="audit-problemas-section" style="display:none;margin-bottom:2rem">
          <h3 style="font-size:13px;font-weight:700;color:#0B2447;margin-bottom:.75rem;text-transform:uppercase;letter-spacing:.05em">🐛 Problemas de contenido</h3>
          <div id="audit-problemas-list"></div>
        </div>

        <!-- Oportunidades -->
        <div id="audit-opps-section" style="display:none;margin-bottom:2rem">
          <h3 style="font-size:13px;font-weight:700;color:#0B2447;margin-bottom:.75rem;text-transform:uppercase;letter-spacing:.05em">🚀 Oportunidades de crecimiento</h3>
          <div id="audit-opps-list"></div>
        </div>

      </div>

      <!-- Error -->
      <div id="audit-error" style="display:none;background:#FEF2F2;border:1px solid #fecaca;border-radius:10px;padding:1rem 1.25rem;color:#dc2626;font-size:14px;margin-top:1rem"></div>

    </div>
  </div>

</main>

<script>
// ── Estado ──
let tipoActual        = 'articulo';
let imagenesSubidas   = [];
let recognizing       = false;
let recognition       = null;
let tipInterval       = null;
let informeCompetencia = '';  // informe de investigación competitiva
const tips = [
  'Leyendo informe de competidores...',
  'Analizando la intención de búsqueda...',
  'Calculando densidad de keyword óptima...',
  'Estructurando H1 y H2s para superar a la competencia...',
  'Cubriendo los gaps detectados en rivales...',
  'Optimizando para Google AI Overviews...',
  'Generando FAQs con preguntas reales de búsqueda...',
  'Aplicando señales E-E-A-T...',
  'Añadiendo interlinking interno...',
  'Revisando meta title y description...',
  'Casi listo...',
];
let tipIdx = 0;

// ── Tabs ──
function cambiarTab(id, btn) {
  document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  document.getElementById('tab-' + id).classList.add('active');
  btn.classList.add('active');
}

// ── Tipo artículo/proyecto ──
function setTipo(t, btn) {
  tipoActual = t;
  document.querySelectorAll('.tipo-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('pv-cat').style.display = t === 'articulo' ? '' : 'none';
  document.getElementById('pv-svc').style.display = t === 'proyecto' ? '' : 'none';
  document.getElementById('extracto-lbl').textContent = t === 'proyecto' ? 'Descripción' : 'Extracto';
}

// ── Grabación de audio ──
function toggleAudio() {
  const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
  if (!SpeechRecognition) {
    alert('Tu navegador no soporta grabación de audio.\nUsa Chrome en Android o escribe la descripción directamente.');
    return;
  }
  if (recognizing) {
    recognition.stop();
    return;
  }
  recognition = new SpeechRecognition();
  recognition.lang = 'es-ES';
  recognition.continuous = true;
  recognition.interimResults = true;

  recognition.onstart = () => {
    recognizing = true;
    document.getElementById('btn-mic').classList.add('grabando');
    document.getElementById('mic-txt').textContent = 'Detener';
    document.getElementById('audio-status').textContent = '🔴 Grabando... habla sobre el trabajo';
  };
  recognition.onend = () => {
    recognizing = false;
    document.getElementById('btn-mic').classList.remove('grabando');
    document.getElementById('mic-txt').textContent = 'Grabar';
    document.getElementById('audio-status').textContent = '✅ Grabación guardada';
  };
  recognition.onresult = (e) => {
    let texto = '';
    for (let i = 0; i < e.results.length; i++) {
      texto += e.results[i][0].transcript + ' ';
    }
    document.getElementById('transcripcion').value = texto.trim();
  };
  recognition.onerror = (e) => {
    recognizing = false;
    document.getElementById('btn-mic').classList.remove('grabando');
    document.getElementById('mic-txt').textContent = 'Grabar';
    document.getElementById('audio-status').textContent = 'Error: ' + e.error + '. Escribe manualmente.';
  };
  recognition.start();
}

// ── Subida de imágenes ──
async function subirImagenes(files) {
  for (const file of files) {
    const fd = new FormData();
    fd.append('imagen', file);
    try {
      const r    = await fetch('agente-seo-upload', { method: 'POST', body: fd });
      const data = await r.json();
      if (data.ok) {
        imagenesSubidas.push(data.ruta);
        renderThumb(data.ruta);
      } else {
        alert('Error subiendo imagen: ' + (data.error || 'desconocido'));
      }
    } catch (e) {
      alert('Error de red al subir imagen.');
    }
  }
}

function renderThumb(ruta) {
  const grid = document.getElementById('thumbs-grid');
  const div  = document.createElement('div');
  div.className    = 'thumb-item';
  div.dataset.ruta = ruta;
  div.innerHTML    = `<img src="${ruta}" alt=""><button type="button" class="thumb-remove" onclick="quitarImg('${ruta}',this.parentNode)">✕</button>`;
  grid.appendChild(div);
}

function quitarImg(ruta, el) {
  imagenesSubidas = imagenesSubidas.filter(r => r !== ruta);
  el.remove();
}

// Drag & drop
const dropArea = document.getElementById('upload-drop');
dropArea.addEventListener('dragover', e => { e.preventDefault(); dropArea.style.borderColor = '#1976D2'; });
dropArea.addEventListener('dragleave', () => { dropArea.style.borderColor = ''; });
dropArea.addEventListener('drop', e => {
  e.preventDefault();
  dropArea.style.borderColor = '';
  subirImagenes(e.dataTransfer.files);
});

// ── Investigar competencia ──
const invTips = [
  'Buscando en Google...',
  'Analizando página #1...',
  'Analizando página #2...',
  'Analizando página #3...',
  'Analizando página #4...',
  'Generando informe con IA...',
];
let invTipIdx = 0, invTipInt = null;

async function investigar() {
  const keyword = document.getElementById('keyword').value.trim();
  if (!keyword) {
    alert('Escribe la keyword antes de investigar.');
    document.getElementById('keyword').focus();
    return;
  }

  // Ocultar estados previos
  document.getElementById('ag-empty').style.display   = 'none';
  document.getElementById('ag-inv').style.display     = 'none';
  document.getElementById('ag-preview').style.display = 'none';
  document.getElementById('ag-loading').style.display = 'none';
  document.getElementById('inv-loading').style.display = 'block';
  document.getElementById('btn-investigar').disabled   = true;
  document.getElementById('btn-generar').disabled      = true;

  invTipIdx = 0;
  document.getElementById('inv-tip').textContent = invTips[0];
  invTipInt = setInterval(() => {
    invTipIdx = Math.min(invTipIdx + 1, invTips.length - 1);
    document.getElementById('inv-tip').textContent = invTips[invTipIdx];
  }, 4000);

  const fd = new FormData();
  fd.append('keyword', keyword);
  fd.append('zona', document.getElementById('zona').value);
  fd.append('notas_investigar', (document.getElementById('notas-investigar')?.value || '').trim());

  try {
    const r    = await fetch('agente-seo-investigar', { method: 'POST', body: fd });
    const data = await r.json();
    clearInterval(invTipInt);
    document.getElementById('inv-loading').style.display  = 'none';
    document.getElementById('btn-investigar').disabled     = false;
    document.getElementById('btn-generar').disabled        = false;

    if (data.error) {
      alert('Error en investigación: ' + data.error);
      document.getElementById('ag-empty').style.display = 'flex';
      return;
    }
    mostrarInvestigacion(data);
  } catch (e) {
    clearInterval(invTipInt);
    document.getElementById('inv-loading').style.display  = 'none';
    document.getElementById('btn-investigar').disabled     = false;
    document.getElementById('btn-generar').disabled        = false;
    document.getElementById('ag-empty').style.display     = 'flex';
    alert('Error de conexión durante la investigación.');
  }
}

function mostrarInvestigacion(data) {
  informeCompetencia = data.informe || '';

  // Chips de URLs
  const urlsEl = document.getElementById('inv-urls');
  urlsEl.innerHTML = '';
  (data.analisis || []).forEach(p => {
    const chip = document.createElement('div');
    chip.className = 'inv-url-chip';
    chip.title = p.url;
    chip.innerHTML = `<span>#${p.posicion}</span> ${(new URL(p.url)).hostname.replace('www.','')}`;
    urlsEl.appendChild(chip);
  });

  // Informe markdown → HTML básico
  const box = document.getElementById('inv-report-box');
  box.innerHTML = markdownSimple(informeCompetencia);

  document.getElementById('ag-inv').style.display = 'block';
  document.getElementById('btn-investigar').classList.add('hecho');
  document.getElementById('btn-investigar').textContent = '✅ Investigación completada — repetir';
  document.getElementById('ag-inv').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function markdownSimple(md) {
  if (!md) return '';
  return md
    .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
    .replace(/^## (.+)$/gm, '<h2>$1</h2>')
    .replace(/^### (.+)$/gm, '<h3>$1</h3>')
    .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
    .replace(/^- (.+)$/gm, '<li>$1</li>')
    .replace(/(<li>.*<\/li>)/gs, '<ul>$1</ul>')
    .replace(/\n{2,}/g, '<br>')
    .replace(/\n/g, ' ');
}

// ── Loading tips ──
function startTips() {
  tipIdx = 0;
  document.getElementById('loading-tip').textContent = tips[0];
  tipInterval = setInterval(() => {
    tipIdx = (tipIdx + 1) % tips.length;
    document.getElementById('loading-tip').textContent = tips[tipIdx];
  }, 2800);
}
function stopTips() { clearInterval(tipInterval); }

// ── Generar ──
async function generar() {
  const keyword = document.getElementById('keyword').value.trim();
  if (!keyword) {
    alert('Escribe la keyword o tema principal antes de generar.');
    document.getElementById('keyword').focus();
    return;
  }

  // Mostrar loading
  document.getElementById('ag-empty').style.display    = 'none';
  document.getElementById('ag-preview').style.display  = 'none';
  document.getElementById('ag-inv').style.display      = 'none';
  document.getElementById('ag-loading').style.display  = 'block';
  document.getElementById('btn-generar').disabled      = true;
  startTips();

  const fd = new FormData();
  fd.append('tipo',          tipoActual);
  fd.append('keyword',       keyword);
  fd.append('zona',          document.getElementById('zona').value);
  fd.append('transcripcion', document.getElementById('transcripcion').value);
  fd.append('notas',         document.getElementById('notas').value);
  fd.append('imagenes',           JSON.stringify(imagenesSubidas));
  fd.append('informe_competencia', informeCompetencia);

  try {
    const r    = await fetch('agente-seo-api', { method: 'POST', body: fd });
    const resp = await r.json();

    stopTips();
    document.getElementById('ag-loading').style.display = 'none';
    document.getElementById('btn-generar').disabled     = false;

    if (resp.error) {
      alert('Error del agente: ' + resp.error);
      document.getElementById('ag-empty').style.display = 'flex';
      return;
    }
    mostrarPreview(resp.data);

  } catch (e) {
    stopTips();
    document.getElementById('ag-loading').style.display = 'none';
    document.getElementById('btn-generar').disabled     = false;
    document.getElementById('ag-empty').style.display   = 'flex';
    alert('Error de conexión. Comprueba que el servidor tiene acceso a internet.');
  }
}

function mostrarPreview(d) {
  // Notas SEO
  if (d.seo_notas) {
    document.getElementById('seo-notes-txt').textContent = d.seo_notas;
    document.getElementById('seo-notes-box').style.display = '';
  } else {
    document.getElementById('seo-notes-box').style.display = 'none';
  }

  // Campos
  document.getElementById('f-tipo').value        = tipoActual;
  document.getElementById('f-meta-title').value  = d.meta_title  || '';
  document.getElementById('f-meta-desc').value   = d.meta_desc   || '';
  document.getElementById('f-slug').value        = d.slug        || '';
  document.getElementById('f-titulo').value      = d.titulo      || '';
  document.getElementById('f-extracto').value    = d.extracto    || '';
  document.getElementById('f-descripcion').value = d.extracto    || '';
  document.getElementById('f-zona').value        = d.zona        || document.getElementById('zona').value;
  document.getElementById('f-contenido').value   = d.contenido   || '';
  document.getElementById('f-imagenes').value    = JSON.stringify(imagenesSubidas);
  document.getElementById('f-imagen').value      = imagenesSubidas[0] || '';

  // Selects
  seleccionar('f-categoria', d.categoria);
  seleccionar('f-servicio',  d.servicio);
  seleccionar('f-sidebar-tipo', document.getElementById('sidebar-tipo-input').value);

  // Contadores
  contarChars(document.getElementById('f-meta-title'), 'mt-info', 60);
  contarChars(document.getElementById('f-meta-desc'),  'md-info', 160);

  document.getElementById('ag-preview').style.display = 'block';
  document.getElementById('ag-preview').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function seleccionar(selectId, valor) {
  if (!valor) return;
  const sel = document.getElementById(selectId);
  if (!sel) return;
  [...sel.options].forEach(o => { o.selected = o.value === valor; });
}

function actualizarSidebarPreview() {
  const tipoInput = document.getElementById('sidebar-tipo-input');
  const tipoForm  = document.getElementById('f-sidebar-tipo');
  const tipo = tipoInput ? tipoInput.value : (tipoForm ? tipoForm.value : '');
  // Sincronizar ambos selects
  if (tipoInput && tipoForm) seleccionar('f-sidebar-tipo', tipo);
  const zona = (document.getElementById('f-zona') ? document.getElementById('f-zona').value.trim() : '') ||
               (document.getElementById('zona')   ? document.getElementById('zona').value : '') || '{Ciudad}';
  const span = document.getElementById('sidebar-preview-txt');
  if (!span) return;
  const labels = {
    '':           null,
    'hub':        `"Fontanero en ${zona}" → /fontanero/{slug}`,
    'urgencias':  `"Urgencias 24h en ${zona}" → /fontanero/{slug}/urgencias`,
    'desatascos': `"Desatascos en ${zona}" → /fontanero/{slug}/desatascos`,
    'fugas':      `"Reparación de fugas en ${zona}" → /fontanero/{slug}/fugas`,
  };
  if (labels[tipo]) { span.textContent = '↳ ' + labels[tipo]; span.style.display = 'block'; }
  else { span.style.display = 'none'; }
}

function contarChars(el, infoId, limite) {
  const n    = el.value.length;
  const span = document.getElementById(infoId);
  span.textContent = n + '/' + limite;
  span.className   = 'char-info ' + (n > limite ? 'warn' : (n >= limite - 8 ? 'ok' : ''));
}

// ── Auditor SEO ──────────────────────────────────────────────────────────────
const auditTips = [
  'Construyendo inventario de páginas...',
  'Leyendo artículos y proyectos de la base de datos...',
  'Escaneando páginas de zona y servicio...',
  'Calculando métricas SEO de cada página...',
  'Enviando inventario al auditor IA...',
  'Analizando canibalizaciones de keywords...',
  'Detectando oportunidades de contenido...',
  'Revisando cobertura de zonas geográficas...',
  'Generando informe ejecutivo...',
  'Casi listo...',
];
let auditTipIdx = 0, auditTipInt = null;

async function ejecutarAuditoria() {
  document.getElementById('audit-inicio').style.display     = 'none';
  document.getElementById('audit-resultados').style.display = 'none';
  document.getElementById('audit-error').style.display      = 'none';

  document.getElementById('audit-loading').style.display = 'block';
  auditTipIdx = 0;
  document.getElementById('audit-tip').textContent = auditTips[0];
  auditTipInt = setInterval(() => {
    auditTipIdx = Math.min(auditTipIdx + 1, auditTips.length - 1);
    document.getElementById('audit-tip').textContent = auditTips[auditTipIdx];
  }, 3500);

  try {
    const keywords  = document.getElementById('audit-keywords')?.value || '';
    const xlsxInput = document.getElementById('audit-xlsx-input');
    const fd = new FormData();
    fd.append('keywords', keywords);
    if (xlsxInput?.files[0]) fd.append('keywords_xlsx', xlsxInput.files[0]);

    const r = await fetch('agente-seo-auditor', { method: 'POST', body: fd });
    const data = await r.json();

    clearInterval(auditTipInt);
    document.getElementById('audit-loading').style.display = 'none';

    if (!data.ok || data.error) {
      const errEl = document.getElementById('audit-error');
      errEl.textContent = '⚠️ ' + (data.error || 'Error desconocido en la auditoría');
      errEl.style.display = 'block';
      document.getElementById('audit-inicio').style.display = 'block';
      return;
    }

    renderAuditoria(data);

  } catch (e) {
    clearInterval(auditTipInt);
    document.getElementById('audit-loading').style.display = 'none';
    const errEl = document.getElementById('audit-error');
    errEl.textContent = '⚠️ Error de conexión. Comprueba que el servidor responde correctamente.';
    errEl.style.display = 'block';
    document.getElementById('audit-inicio').style.display = 'block';
  }
}

function renderAuditoria(data) {
  const a    = data.analisis || {};
  const r    = a.resumen    || {};
  const meta = a._meta      || {};

  // ── Contador cabecera ──
  const totalPags = (meta.total_estaticas || 0) + (meta.total_dinamicas || 0);
  document.getElementById('audit-count-lbl').textContent =
    `Auditoría completada — ${totalPags} páginas analizadas`;

  // ── Notas ejecutivas ──
  if (a.seo_notas) {
    document.getElementById('audit-notas-txt').textContent = a.seo_notas;
    document.getElementById('audit-notas-box').style.display = '';
  } else {
    document.getElementById('audit-notas-box').style.display = 'none';
  }

  // ── Tarjetas resumen ──
  const cards = [
    { lbl: 'Páginas analizadas',     val: totalPags || r.total_paginas || 0,                                            cls: 'blue'  },
    { lbl: 'Gaps de estructura',     val: r.gaps_estructura ?? 0,                                                       cls: 'red'   },
    { lbl: 'Problemas contenido',    val: r.problemas_contenido ?? 0,                                                   cls: 'amber' },
    { lbl: meta.clusters_count ? 'Sin cobertura (clusters)' : 'Oportunidades',
      val: meta.clusters_count ? (r.clusters_sin_cobertura ?? 0) : (r.oportunidades ?? 0),                             cls: 'green' },
  ];
  document.getElementById('audit-cards').innerHTML = cards.map(c =>
    `<div class="audit-card ${c.cls}">
       <div class="audit-card-val">${c.val}</div>
       <div class="audit-card-lbl">${c.lbl}</div>
     </div>`
  ).join('');

  // ── Arquitectura recomendada ──
  if (a.estructura_recomendada) {
    document.getElementById('audit-estructura-txt').textContent = a.estructura_recomendada;
    document.getElementById('audit-estructura-section').style.display = '';
  } else {
    document.getElementById('audit-estructura-section').style.display = 'none';
  }

  // ── Matriz zona × servicio (datos del backend PHP) ──
  const matrizData = meta.matriz || [];
  if (matrizData.length) {
    const servicios = Object.keys(matrizData[0]?.servicios || {});
    let html = `<table style="width:100%;border-collapse:collapse;font-size:12px">`;
    // Cabecera
    html += `<tr style="background:#F8FAFC">
      <th style="text-align:left;padding:.5rem .75rem;border:1px solid #E8EFF8;font-weight:700;color:#0B2447">Zona</th>`;
    servicios.forEach(s => {
      html += `<th style="padding:.5rem .75rem;border:1px solid #E8EFF8;font-weight:700;color:#0B2447;text-align:center">${escp(s)}</th>`;
    });
    html += `</tr>`;
    // Filas
    matrizData.forEach(fila => {
      const completa = Object.values(fila.servicios).every(v => v);
      html += `<tr style="background:${completa ? '#fff' : '#FFFBEB'}">
        <td style="padding:.5rem .75rem;border:1px solid #E8EFF8;font-weight:600;color:#0B2447">${escp(fila.zona)}</td>`;
      servicios.forEach(s => {
        const ok = fila.servicios[s];
        html += `<td style="padding:.5rem .75rem;border:1px solid #E8EFF8;text-align:center">
          ${ok
            ? '<span style="color:#059669;font-size:16px">✓</span>'
            : '<span style="color:#DC2626;font-weight:700;font-size:11px">FALTA</span>'}
        </td>`;
      });
      html += `</tr>`;
    });
    html += `</table>`;
    document.getElementById('audit-matriz-tabla').innerHTML = html;
    document.getElementById('audit-matriz-section').style.display = '';
  } else {
    document.getElementById('audit-matriz-section').style.display = 'none';
  }

  // ── Clústeres de intención (Excel Semrush) ──
  renderClusters(a.clusters_analisis || []);

  // ── Keywords gaps (manual) ──
  const kwGaps = a.keywords_gaps || [];
  if (kwGaps.length) {
    document.getElementById('audit-kw-list').innerHTML = kwGaps.map(k => {
      const col = k.estado === 'cubierta' ? '#059669' : k.estado === 'parcial' ? '#D97706' : '#DC2626';
      const bg  = k.estado === 'cubierta' ? '#D1FAE5' : k.estado === 'parcial' ? '#FEF3C7' : '#FEE2E2';
      const lbl = k.estado === 'cubierta' ? '✓ Cubierta' : k.estado === 'parcial' ? '⚠ Parcial' : '✗ Sin página';
      return `<div style="background:#fff;border:1px solid #E8EFF8;border-radius:10px;padding:.875rem 1.125rem;margin-bottom:.625rem;display:flex;align-items:flex-start;gap:1rem">
        <span style="background:${bg};color:${col};font-size:11px;font-weight:700;padding:3px 10px;border-radius:100px;white-space:nowrap;flex-shrink:0;margin-top:2px">${lbl}</span>
        <div style="flex:1;min-width:0">
          <div style="font-size:13px;font-weight:700;color:#0B2447;margin-bottom:.25rem">${escp(k.keyword)}</div>
          ${k.url_existente ? `<div style="font-size:12px;color:#1976D2;font-family:monospace;margin-bottom:.25rem">${escp(k.url_existente)}</div>` : ''}
          ${k.accion ? `<div style="font-size:12px;color:#059669;font-weight:600">→ ${escp(k.accion)}</div>` : ''}
        </div>
      </div>`;
    }).join('');
    document.getElementById('audit-kw-section').style.display = '';
  } else {
    document.getElementById('audit-kw-section').style.display = 'none';
  }

  // ── Canibalización ──
  const canis = a.canibalizacion || [];
  if (canis.length) {
    document.getElementById('audit-cani-list').innerHTML = canis.map(c =>
      `<div class="audit-cani">
         <div style="font-size:12px;font-weight:700;color:#92400E;margin-bottom:.375rem">
           ⚠️ Keyword: <em>${escp(c.keyword || c.keyword_compartida || '')}</em>
         </div>
         <div style="display:flex;flex-wrap:wrap;gap:.375rem;margin-bottom:.5rem">
           ${(c.urls || []).map(u => `<code style="font-size:11px;background:#FED7AA;color:#7C2D12;padding:2px 8px;border-radius:4px">${escp(u)}</code>`).join('')}
         </div>
         <div style="font-size:13px;color:#576574">${escp(c.accion || c.recomendacion || '')}</div>
       </div>`
    ).join('');
    document.getElementById('audit-cani-section').style.display = '';
  } else {
    document.getElementById('audit-cani-section').style.display = 'none';
  }

  // ── Problemas de contenido ──
  const problemas = (a.problemas_contenido || a.problemas || []).slice().sort((x, y) => {
    const ord = { alta: 0, media: 1, baja: 2 };
    return (ord[x.prioridad] ?? 3) - (ord[y.prioridad] ?? 3);
  });
  if (problemas.length) {
    document.getElementById('audit-problemas-list').innerHTML = problemas.map(p =>
      `<div class="audit-issue">
         <span class="audit-badge ${escp(p.prioridad || 'baja')}">${(p.prioridad || 'baja').toUpperCase()}</span>
         <div style="flex:1;min-width:0">
           <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;margin-bottom:.25rem">
             <span class="audit-issue-url">${escp(p.url || '')}</span>
           </div>
           ${p.titulo ? `<div style="font-size:13px;font-weight:600;color:#0B2447;margin-bottom:.25rem">${escp(p.titulo)}</div>` : ''}
           <div class="audit-issue-desc">${escp(p.problema || p.descripcion || '')}</div>
           ${p.accion ? `<div class="audit-issue-accion">→ ${escp(p.accion)}</div>` : ''}
         </div>
       </div>`
    ).join('');
    document.getElementById('audit-problemas-section').style.display = '';
  } else {
    document.getElementById('audit-problemas-section').style.display = 'none';
  }

  // ── Oportunidades ──
  const opps = a.oportunidades || [];
  if (opps.length) {
    document.getElementById('audit-opps-list').innerHTML = opps.map(o =>
      `<div class="audit-opp">
         <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;margin-bottom:.375rem">
           ${o.url_sugerida ? `<span class="audit-opp-url">${escp(o.url_sugerida)}</span>` : ''}
           <span style="font-size:11px;font-weight:700;color:${(o.prioridad||o.impacto) === 'alta' || (o.prioridad||o.impacto) === 'alto' ? '#B45309' : '#0369A1'}">${(o.prioridad||o.impacto) === 'alta' || (o.prioridad||o.impacto) === 'alto' ? '🔥 ALTA' : '📈 MEDIA'}</span>
         </div>
         <div style="font-size:13px;color:#576574;margin-bottom:.25rem">${escp(o.descripcion || '')}</div>
         ${o.razon ? `<div style="font-size:12px;color:#059669;font-weight:600">${escp(o.razon)}</div>` : ''}
       </div>`
    ).join('');
    document.getElementById('audit-opps-section').style.display = '';
  } else {
    document.getElementById('audit-opps-section').style.display = 'none';
  }

  document.getElementById('audit-resultados').style.display = 'block';
  document.getElementById('audit-resultados').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// ── Archivo xlsx seleccionado ──
function auditArchivoSeleccionado(input) {
  const file = input.files[0];
  if (!file) return;
  const lbl = document.getElementById('audit-file-lbl');
  lbl.textContent = '✓ ' + file.name + ' (' + Math.round(file.size / 1024) + ' KB)';
  lbl.style.color = '#059669';
  document.getElementById('audit-drop-zone').style.borderColor = '#059669';
  document.getElementById('audit-drop-zone').style.background  = '#F0FDF4';
}

// ── Render clústeres de intención ──
function renderClusters(clusters) {
  if (!clusters || !clusters.length) {
    document.getElementById('audit-clusters-section').style.display = 'none';
    return;
  }
  const colMap = {
    cubierta:   { bg: '#D1FAE5', col: '#065F46', lbl: '✓ Cubierta'  },
    parcial:    { bg: '#FEF3C7', col: '#92400E', lbl: '⚠ Parcial'   },
    sin_pagina: { bg: '#FEE2E2', col: '#B91C1C', lbl: '✗ Sin página' },
  };
  document.getElementById('audit-clusters-list').innerHTML = clusters.map(c => {
    const s = colMap[c.estado] || colMap['sin_pagina'];
    return `<div style='background:#fff;border:1px solid #E8EFF8;border-radius:10px;padding:1rem 1.25rem;margin-bottom:.625rem'>
      <div style='display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;margin-bottom:.375rem'>
        <span style='background:${s.bg};color:${s.col};font-size:11px;font-weight:700;padding:3px 10px;border-radius:100px;white-space:nowrap'>${s.lbl}</span>
        <strong style='font-size:14px;color:#0B2447'>${escp(c.seed)}</strong>
        ${c.vol_total ? `<span style='font-size:11px;color:#8FA3B8;background:#F1F5F9;padding:2px 8px;border-radius:100px'>${c.vol_total} búsq/mes</span>` : ''}
      </div>
      ${c.url_existente ? `<div style='font-size:12px;color:#1976D2;font-family:monospace;margin-bottom:.25rem'>${escp(c.url_existente)}</div>` : ''}
      ${c.nota   ? `<div style='font-size:13px;color:#576574;margin-bottom:.25rem'>${escp(c.nota)}</div>` : ''}
      ${c.accion && c.accion !== 'null' ? `<div style='font-size:12px;color:#059669;font-weight:600'>→ ${escp(c.accion)}</div>` : ''}
    </div>`;
  }).join('');
  document.getElementById('audit-clusters-section').style.display = '';
}

// Escape HTML helper
function escp(str) {
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}
</script>
</body>
</html>
