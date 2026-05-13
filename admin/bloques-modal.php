<?php
/* =============================================
   CAROLTEMP — Modal catálogo de bloques
   Incluir antes de </body> en los editores
============================================= */

$bloques = [

  /* ── HERO ─────────────────────────────── */
  [
    'cat'  => 'hero',
    'label'=> 'Hero',
    'nombre' => 'Hero oscuro',
    'desc'   => 'Cabecera azul oscura con tag, título, subtítulo y dos botones',
    'bg'     => 'linear-gradient(135deg,#071A36,#1A4480)',
    'color'  => '#fff',
    'html'   => '<section class="hz-dark">
  <div class="hz-dark-bg"></div>
  <div class="hz-dark-glow"></div>
  <div class="hz-dark-con">
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Etiqueta del servicio</div>
    <h1>Título principal de la sección<br><span class="hl">destacado aquí.</span></h1>
    <p class="hz-dark-sub">Descripción breve del servicio o página. Explica qué ofreces en 1-2 líneas.</p>
    <div class="hz-dark-btns">
      <a href="tel:+34613429032" class="btn-hz-w">📞 613 429 032</a>
      <a href="/contacto" class="btn-hz-g">Pedir presupuesto</a>
    </div>
  </div>
</section>',
  ],

  /* ── SECCIONES ─────────────────────────── */
  [
    'cat'  => 'seccion',
    'label'=> 'Secciones',
    'nombre' => 'Grid de servicios',
    'desc'   => '6 tarjetas con número, título y descripción (3 col desktop)',
    'bg'     => '#EEF4FF',
    'color'  => '#0B2447',
    'html'   => '<section class="fin-sec">
  <div class="fin-con">
    <p class="zona-lbl">Nuestros servicios</p>
    <h2 class="fin-h2">Soluciones para <span class="fin-hl">cada necesidad</span></h2>
    <p class="fin-sub fin-sub-mb">Descripción introductoria de la sección. Máximo 2 líneas.</p>
    <div class="zona-svc">
      <div class="zona-sc"><span class="zona-sc-n">01</span><h3>Servicio uno</h3><p>Descripción breve del primer servicio ofrecido.</p></div>
      <div class="zona-sc"><span class="zona-sc-n">02</span><h3>Servicio dos</h3><p>Descripción breve del segundo servicio ofrecido.</p></div>
      <div class="zona-sc"><span class="zona-sc-n">03</span><h3>Servicio tres</h3><p>Descripción breve del tercer servicio ofrecido.</p></div>
      <div class="zona-sc"><span class="zona-sc-n">04</span><h3>Servicio cuatro</h3><p>Descripción breve del cuarto servicio ofrecido.</p></div>
      <div class="zona-sc"><span class="zona-sc-n">05</span><h3>Servicio cinco</h3><p>Descripción breve del quinto servicio ofrecido.</p></div>
      <div class="zona-sc"><span class="zona-sc-n">06</span><h3>Servicio seis</h3><p>Descripción breve del sexto servicio ofrecido.</p></div>
    </div>
  </div>
</section>',
  ],

  [
    'cat'  => 'seccion',
    'label'=> 'Secciones',
    'nombre' => 'Cómo funciona (4 pasos)',
    'desc'   => '4 pasos numerados en grid con líneas separadoras',
    'bg'     => '#f8fafc',
    'color'  => '#0d1f33',
    'html'   => '<section class="fin-sec fin-sec-gray">
  <div class="fin-con">
    <p class="zona-lbl">Cómo funciona</p>
    <h2 class="fin-h2 fin-h2-mb">Cuatro pasos, <span class="fin-hl">sin complicaciones</span></h2>
    <div class="fin-pasos-grid">
      <div class="fin-paso">
        <span class="fin-paso-num">01</span>
        <h3 class="fin-paso-h">Primer paso</h3>
        <p class="fin-paso-p">Descripción de lo que ocurre en este paso del proceso.</p>
      </div>
      <div class="fin-paso">
        <span class="fin-paso-num">02</span>
        <h3 class="fin-paso-h">Segundo paso</h3>
        <p class="fin-paso-p">Descripción de lo que ocurre en este paso del proceso.</p>
      </div>
      <div class="fin-paso">
        <span class="fin-paso-num">03</span>
        <h3 class="fin-paso-h">Tercer paso</h3>
        <p class="fin-paso-p">Descripción de lo que ocurre en este paso del proceso.</p>
      </div>
      <div class="fin-paso">
        <span class="fin-paso-num">04</span>
        <h3 class="fin-paso-h">Cuarto paso</h3>
        <p class="fin-paso-p">Descripción de lo que ocurre en este paso del proceso.</p>
      </div>
    </div>
  </div>
</section>',
  ],

  [
    'cat'  => 'seccion',
    'label'=> 'Secciones',
    'nombre' => 'Ventajas (2 columnas)',
    'desc'   => 'Lista de checks a la izquierda + tarjetas de detalle a la derecha',
    'bg'     => '#fff',
    'color'  => '#0d1f33',
    'html'   => '<section class="fin-sec">
  <div class="fin-con">
    <div class="fin-ventajas-grid">
      <div>
        <p class="zona-lbl">Por qué elegirnos</p>
        <h2 class="fin-h2 fin-h2-mb2">Sin sorpresas <span class="fin-hl">ni letra pequeña</span></h2>
        <p class="fin-sub fin-sub-mb2">Descripción introductoria de esta sección. Explica el valor diferencial en 2-3 frases.</p>
        <ul class="fin-list">
          <li class="fin-list-item"><span class="fin-check"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Ventaja número uno</li>
          <li class="fin-list-item"><span class="fin-check"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Ventaja número dos</li>
          <li class="fin-list-item"><span class="fin-check"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Ventaja número tres</li>
          <li class="fin-list-item"><span class="fin-check"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Ventaja número cuatro</li>
        </ul>
      </div>
      <div class="fin-cards">
        <div class="fin-card"><span class="fin-card-icon">✓</span><div class="fin-card-body"><strong class="fin-card-title">Punto clave uno</strong><span class="fin-card-desc">Descripción breve de este beneficio.</span></div></div>
        <div class="fin-card"><span class="fin-card-icon">✓</span><div class="fin-card-body"><strong class="fin-card-title">Punto clave dos</strong><span class="fin-card-desc">Descripción breve de este beneficio.</span></div></div>
        <div class="fin-card"><span class="fin-card-icon">✓</span><div class="fin-card-body"><strong class="fin-card-title">Punto clave tres</strong><span class="fin-card-desc">Descripción breve de este beneficio.</span></div></div>
      </div>
    </div>
  </div>
</section>',
  ],

  [
    'cat'  => 'seccion',
    'label'=> 'Secciones',
    'nombre' => 'Sección con título centrado',
    'desc'   => 'Label + H2 + subtítulo centrados como cabecera de sección',
    'bg'     => '#fff',
    'color'  => '#0B2447',
    'html'   => '<section class="home-sec">
  <div class="home-con">
    <div class="sec-header">
      <p class="home-lbl">Etiqueta de sección</p>
      <h2>Título de la sección <span class="hl">con énfasis</span></h2>
      <p class="home-sub">Descripción introductoria de esta sección. Máximo 2-3 líneas explicando de qué trata.</p>
    </div>
  </div>
</section>',
  ],

  /* ── ZONAS ──────────────────────────────── */
  [
    'cat'  => 'zonas',
    'label'=> 'Zonas',
    'nombre' => 'Chips de municipios',
    'desc'   => 'Grid de píldoras enlazables con pin y nombre de cada zona',
    'bg'     => '#EEF4FF',
    'color'  => '#0B2447',
    'html'   => '<section class="home-sec">
  <div class="home-con">
    <div class="sec-header">
      <p class="home-lbl">Dónde trabajamos</p>
      <h2>Servicio en toda <span class="hl">la comarca</span></h2>
    </div>
    <div class="zonas-chips">
      <a href="/zonas/elda"     class="zona-chip"><svg class="zona-chip-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>Elda</a>
      <a href="/zonas/petrer"   class="zona-chip"><svg class="zona-chip-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>Petrer</a>
      <a href="/zonas/novelda"  class="zona-chip"><svg class="zona-chip-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>Novelda</a>
      <a href="/zonas/monovar"  class="zona-chip"><svg class="zona-chip-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>Monóvar</a>
      <a href="/zonas/sax"      class="zona-chip"><svg class="zona-chip-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>Sax</a>
      <a href="/zonas/pinoso"   class="zona-chip"><svg class="zona-chip-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>Pinoso</a>
      <a href="/zonas/monforte" class="zona-chip"><svg class="zona-chip-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>Monforte del Cid</a>
      <a href="/zonas/salinas"  class="zona-chip"><svg class="zona-chip-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>Salinas</a>
    </div>
  </div>
</section>',
  ],

  /* ── CTA ────────────────────────────────── */
  [
    'cat'  => 'cta',
    'label'=> 'CTA',
    'nombre' => 'CTA oscuro',
    'desc'   => 'Bloque final azul marino con título, texto, teléfono y botones',
    'bg'     => 'linear-gradient(135deg,#0B2447,#1565C0)',
    'color'  => '#fff',
    'html'   => '<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>¿Necesitas <span>ayuda urgente?</span></h2>
    <p>Llámanos y te atendemos de inmediato. Sin compromiso.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34613429032" class="btn-hz-w">📞 Llamar ahora</a>
      <a href="https://wa.me/34613429032" target="_blank" rel="noopener" class="btn-hz-g">💬 WhatsApp</a>
    </div>
    <div class="cta-dark-tel">Teléfono directo<strong>613 429 032</strong></div>
  </div>
</section>',
  ],

  [
    'cat'  => 'cta',
    'label'=> 'CTA',
    'nombre' => 'Banner de contacto',
    'desc'   => 'Sección azul claro con H2, texto y botón de presupuesto',
    'bg'     => '#EEF4FF',
    'color'  => '#0B2447',
    'html'   => '<section class="home-sec home-sec-dark">
  <div class="home-con" style="text-align:center">
    <p class="home-lbl home-lbl-light">Contacto</p>
    <h2>¿Hablamos de tu <span class="hl">proyecto?</span></h2>
    <p class="home-sub" style="margin:0 auto 2rem">Cuéntanos qué necesitas y te damos un precio cerrado sin compromiso.</p>
    <a href="/contacto" class="btn-dark-main">Pedir presupuesto gratis</a>
  </div>
</section>',
  ],

  /* ── TEXTO ──────────────────────────────── */
  [
    'cat'  => 'texto',
    'label'=> 'Texto',
    'nombre' => 'Texto enriquecido',
    'desc'   => 'Bloque de contenido con H2, párrafos, lista y enlace',
    'bg'     => '#fff',
    'color'  => '#0B2447',
    'html'   => '<section class="fin-sec">
  <div class="fin-con" style="max-width:780px">
    <h2 class="fin-h2">Título del artículo o sección</h2>
    <p>Párrafo introductorio. Explica el tema de forma clara y directa. Usa frases cortas para facilitar la lectura en móvil.</p>
    <h3 style="color:#0B2447;font-size:1.2rem;font-weight:700;margin:1.5rem 0 .5rem">Subtítulo de apartado</h3>
    <p>Segundo párrafo con más detalle. Puedes añadir tantos párrafos como necesites. El texto debe responder a la pregunta del usuario.</p>
    <ul style="margin:1rem 0;padding-left:1.25rem;display:flex;flex-direction:column;gap:.4rem">
      <li style="color:#475569;font-size:14px;line-height:1.6">Primer punto de la lista</li>
      <li style="color:#475569;font-size:14px;line-height:1.6">Segundo punto de la lista</li>
      <li style="color:#475569;font-size:14px;line-height:1.6">Tercer punto de la lista</li>
    </ul>
    <p>Párrafo de cierre con llamada a la acción. <a href="/contacto" style="color:#1976D2;font-weight:600">Contáctanos</a> para más información.</p>
  </div>
</section>',
  ],

  [
    'cat'  => 'texto',
    'label'=> 'Texto',
    'nombre' => 'FAQ — Preguntas frecuentes',
    'desc'   => 'Lista de preguntas y respuestas en acordeón visual',
    'bg'     => '#f8fafc',
    'color'  => '#0B2447',
    'html'   => '<section class="fin-sec fin-sec-gray">
  <div class="fin-con" style="max-width:780px">
    <p class="zona-lbl">FAQ</p>
    <h2 class="fin-h2 fin-h2-mb">Preguntas <span class="fin-hl">frecuentes</span></h2>
    <div style="display:flex;flex-direction:column;gap:.75rem">
      <div style="background:#fff;border:1.5px solid #e8eff8;border-radius:12px;padding:1.25rem 1.5rem">
        <strong style="color:#0B2447;font-size:15px;display:block;margin-bottom:.5rem">¿Cuánto tarda en llegar un técnico?</strong>
        <p style="color:#64748b;font-size:14px;line-height:1.65;margin:0">En urgencias solemos estar en menos de 60 minutos en Elda, Petrer y Novelda. En el resto de la comarca, en 1-2 horas.</p>
      </div>
      <div style="background:#fff;border:1.5px solid #e8eff8;border-radius:12px;padding:1.25rem 1.5rem">
        <strong style="color:#0B2447;font-size:15px;display:block;margin-bottom:.5rem">¿El precio es cerrado o puede variar?</strong>
        <p style="color:#64748b;font-size:14px;line-height:1.65;margin:0">Siempre damos el precio antes de empezar. Si al abrir hay algo imprevisto, te avisamos antes de continuar. Sin sorpresas.</p>
      </div>
      <div style="background:#fff;border:1.5px solid #e8eff8;border-radius:12px;padding:1.25rem 1.5rem">
        <strong style="color:#0B2447;font-size:15px;display:block;margin-bottom:.5rem">¿Trabajáis en fin de semana?</strong>
        <p style="color:#64748b;font-size:14px;line-height:1.65;margin:0">Sí. Las urgencias las atendemos 24 horas, 7 días a la semana. Llama al 613 429 032.</p>
      </div>
    </div>
  </div>
</section>',
  ],

];

/* ── Agrupa bloques por categoría ── */
$cats = [];
foreach ($bloques as $b) {
  if (!isset($cats[$b['cat']])) $cats[$b['cat']] = $b['label'];
}

?>

<!-- ============================================================
     MODAL BLOQUES
============================================================ -->
<div id="modal-bloques" role="dialog" aria-modal="true" aria-label="Catálogo de bloques">
  <div class="mb-wrap">

    <!-- Cabecera -->
    <div class="mb-head">
      <div>
        <h2 class="mb-title">🧱 Catálogo de bloques</h2>
        <p class="mb-subtitle">Haz clic en un bloque para insertarlo en el editor</p>
      </div>
      <button class="mb-close" onclick="cerrarModalBloques()" aria-label="Cerrar">✕</button>
    </div>

    <!-- Filtros por categoría -->
    <div class="mb-cats">
      <button class="mb-cat active" data-cat="todos" onclick="filtrarBloques('todos', this)">Todos</button>
      <?php foreach ($cats as $key => $label): ?>
        <button class="mb-cat" data-cat="<?php echo $key; ?>" onclick="filtrarBloques('<?php echo $key; ?>', this)">
          <?php echo $label; ?>
        </button>
      <?php endforeach; ?>
    </div>

    <!-- Grid de bloques -->
    <div class="mb-grid">
      <?php foreach ($bloques as $i => $b): ?>
        <div class="mb-card" data-cat="<?php echo $b['cat']; ?>" onclick="insertarBloque(<?php echo $i; ?>)">
          <div class="mb-preview">
            <div class="mb-scale-wrap"><?php echo $b['html']; ?></div>
          </div>
          <div class="mb-card-body">
            <span class="mb-cat-badge"><?php echo $b['label']; ?></span>
            <p class="mb-card-name"><?php echo htmlspecialchars($b['nombre']); ?></p>
            <p class="mb-card-desc"><?php echo htmlspecialchars($b['desc']); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</div>

<style>
#modal-bloques {
  position: fixed;
  inset: 0;
  z-index: 99999;
  background: rgba(11,36,71,.55);
  backdrop-filter: blur(4px);
  display: none;
  align-items: center;
  justify-content: center;
  padding: 1.5rem;
}
#modal-bloques.abierto { display: flex; }

.mb-wrap {
  background: #fff;
  border-radius: 18px;
  width: 100%;
  max-width: 1060px;
  height: 85vh;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  box-shadow: 0 24px 64px rgba(11,36,71,.22);
}

.mb-head {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  padding: 1.25rem 1.5rem 1rem;
  border-bottom: 1px solid #e8eff8;
  flex-shrink: 0;
}
.mb-title    { font-size: 17px; font-weight: 700; color: #0B2447; margin: 0 0 3px; }
.mb-subtitle { font-size: 12.5px; color: #94a3b8; margin: 0; }
.mb-close {
  background: #f1f5f9;
  border: none;
  border-radius: 8px;
  width: 32px;
  height: 32px;
  cursor: pointer;
  font-size: 14px;
  color: #64748b;
  flex-shrink: 0;
  transition: background .15s;
}
.mb-close:hover { background: #e2e8f0; color: #0B2447; }

.mb-cats {
  display: flex;
  gap: .4rem;
  padding: .85rem 1.5rem;
  border-bottom: 1px solid #e8eff8;
  flex-shrink: 0;
  overflow-x: auto;
  scrollbar-width: none;
}
.mb-cats::-webkit-scrollbar { display: none; }
.mb-cat {
  padding: 6px 16px;
  border-radius: 100px;
  border: 1.5px solid #e2e8f0;
  background: #fff;
  cursor: pointer;
  font-size: 13px;
  font-weight: 500;
  color: #475569;
  white-space: nowrap;
  transition: all .15s;
}
.mb-cat:hover  { border-color: #1976D2; color: #1976D2; }
.mb-cat.active { background: #0B2447; border-color: #0B2447; color: #fff; }

.mb-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
  gap: 1rem;
  padding: 1.25rem 1.5rem;
  overflow-y: auto;
  flex: 1;
}

.mb-card {
  border: 1.5px solid #e8eff8;
  border-radius: 12px;
  overflow: hidden;
  cursor: pointer;
  transition: border-color .15s, transform .15s, box-shadow .15s;
}
.mb-card:hover {
  border-color: #1976D2;
  transform: translateY(-3px);
  box-shadow: 0 8px 24px rgba(25,118,210,.14);
}
.mb-card.oculto { display: none; }

.mb-preview {
  height: 160px;
  overflow: hidden;
  position: relative;
  background: #f1f5f9;
  flex-shrink: 0;
}
/* div 1200px escalado al 13.3% → cabe en 160px de alto aprox */
.mb-scale-wrap {
  width: 1200px;
  transform: scale(0.2);
  transform-origin: top left;
  pointer-events: none;
}

.mb-card-body {
  padding: .75rem 1rem;
  border-top: 1px solid #e8eff8;
}
.mb-cat-badge {
  display: inline-block;
  font-size: 10px;
  font-weight: 700;
  color: #1976D2;
  background: #EEF4FF;
  border-radius: 4px;
  padding: 2px 7px;
  letter-spacing: .04em;
  margin-bottom: .35rem;
}
.mb-card-name { font-size: 13.5px; font-weight: 600; color: #0B2447; margin: 0 0 3px; }
.mb-card-desc { font-size: 11.5px; color: #94a3b8; line-height: 1.45; margin: 0; }
</style>

<script>
/* ── Datos de bloques ── */
const CT_BLOQUES = <?php
  $js = array_map(function($b) {
    return ['html' => $b['html']];
  }, $bloques);
  echo json_encode($js, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
?>;

function abrirModalBloques() {
  document.getElementById('modal-bloques').classList.add('abierto');
  document.body.style.overflow = 'hidden';
}
function cerrarModalBloques() {
  document.getElementById('modal-bloques').classList.remove('abierto');
  document.body.style.overflow = '';
}

/* Cerrar al clic en fondo */
document.getElementById('modal-bloques').addEventListener('click', function(e) {
  if (e.target === this) cerrarModalBloques();
});
/* Cerrar con Escape */
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') cerrarModalBloques();
});

function filtrarBloques(cat, btn) {
  document.querySelectorAll('.mb-cat').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.querySelectorAll('.mb-card').forEach(card => {
    const mostrar = cat === 'todos' || card.dataset.cat === cat;
    card.classList.toggle('oculto', !mostrar);
  });
}

function insertarBloque(idx) {
  const html = CT_BLOQUES[idx].html;
  if (typeof tinymce !== 'undefined' && tinymce.activeEditor) {
    tinymce.activeEditor.insertContent(html);
  }
  cerrarModalBloques();
}
</script>
