<?php
/* =============================================
   CAROLTEMP — Modal catálogo de bloques
   Incluir antes de </body> en los editores
============================================= */

$bloques = [

  /* ── HERO ─────────────────────────────── */
  [
    'cat'    => 'hero',
    'label'  => 'Hero',
    'nombre' => 'Hero oscuro',
    'desc'   => 'Cabecera azul oscura con tag, título, subtítulo y dos botones',
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

  [
    'cat'    => 'hero',
    'label'  => 'Hero',
    'nombre' => 'Hero oscuro con KPIs',
    'desc'   => 'Hero azul con 3 métricas destacadas debajo de los botones',
    'html'   => '<section class="hz-dark">
  <div class="hz-dark-bg"></div>
  <div class="hz-dark-glow"></div>
  <div class="hz-dark-con">
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Servicio disponible 24h</div>
    <h1>Título principal aquí<br><span class="hl">texto destacado.</span></h1>
    <p class="hz-dark-sub">Descripción del servicio o página. Explica qué ofreces en 1-2 líneas claras y directas.</p>
    <div class="hz-dark-btns">
      <a href="tel:+34613429032" class="btn-hz-w">📞 Llamar ahora</a>
      <a href="/contacto" class="btn-hz-g">Pedir presupuesto</a>
    </div>
    <div class="hero-dark-kpis" style="margin-top:2rem">
      <div class="hero-dark-kpi"><span class="hero-dark-kpi-val">100%</span><span class="hero-dark-kpi-lbl">Precio cerrado siempre</span></div>
      <div class="hero-dark-kpi"><span class="hero-dark-kpi-val">24h</span><span class="hero-dark-kpi-lbl">Urgencias todos los días</span></div>
      <div class="hero-dark-kpi"><span class="hero-dark-kpi-val">0€</span><span class="hero-dark-kpi-lbl">Sin adelantos con financiación</span></div>
    </div>
  </div>
</section>',
  ],

  [
    'cat'    => 'hero',
    'label'  => 'Hero',
    'nombre' => 'Hero con imagen de fondo',
    'desc'   => 'Hero tipo home con imagen, overlay, trust pills y botones',
    'html'   => '<section class="hero" aria-label="Cabecera principal">
  <div class="hero-card" style="background:linear-gradient(135deg,#0B2447,#1565C0)">
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <h1>Título principal aquí<br><span class="hl">texto destacado</span></h1>
      <p class="hero-sub">Descripción breve del servicio. Precio cerrado antes de empezar, sin sorpresas.</p>
      <div class="hero-btns">
        <a href="tel:+34613429032" class="hero-btn-primary">📞 Llamar ahora</a>
        <a href="https://wa.me/34613429032" target="_blank" rel="noopener" class="hero-btn-wa">💬 WhatsApp gratis</a>
      </div>
      <div class="hero-trust">
        <div class="hero-trust-item">
          <svg class="hero-trust-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
          <span>Precio cerrado siempre</span>
        </div>
        <div class="hero-trust-item">
          <svg class="hero-trust-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          <span>Urgencias 24h</span>
        </div>
        <div class="hero-trust-item">
          <svg class="hero-trust-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          <span>Trabajo garantizado</span>
        </div>
      </div>
    </div>
  </div>
</section>',
  ],

  /* ── SECCIONES ─────────────────────────── */
  [
    'cat'    => 'seccion',
    'label'  => 'Secciones',
    'nombre' => 'Grid de servicios numerados',
    'desc'   => '6 tarjetas con número, título y descripción (3 col desktop)',
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
    'cat'    => 'seccion',
    'label'  => 'Secciones',
    'nombre' => 'Grid servicios con iconos',
    'desc'   => '6 tarjetas con icono SVG, título, descripción y enlace "Ver más"',
    'html'   => '<section class="home-sec" id="servicios">
  <div class="home-con">
    <p class="home-lbl">Nuestros servicios</p>
    <h2>Todo lo que necesitas <span class="hl">para tu hogar</span></h2>
    <p class="home-sub">Fontanería, climatización y reformas. Un equipo, precio cerrado, trabajo garantizado.</p>
    <div class="svc-grid">
      <a href="#" class="svc-card">
        <div class="svc-icon-wrap"><svg class="svc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg></div>
        <h3>Servicio uno</h3>
        <p>Descripción del primer servicio. Reparaciones, instalaciones y trabajos habituales.</p>
        <span class="svc-more">Ver más &rarr;</span>
      </a>
      <a href="#" class="svc-card">
        <div class="svc-icon-wrap"><svg class="svc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 12l2 2 4-4"/></svg></div>
        <h3>Servicio dos</h3>
        <p>Descripción del segundo servicio. Localización y reparación con mínima intervención.</p>
        <span class="svc-more">Ver más &rarr;</span>
      </a>
      <a href="#" class="svc-card">
        <div class="svc-icon-wrap"><svg class="svc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
        <h3>Servicio tres</h3>
        <p>Descripción del tercer servicio. Instalación completa con garantía de resultado.</p>
        <span class="svc-more">Ver más &rarr;</span>
      </a>
      <a href="#" class="svc-card">
        <div class="svc-icon-wrap"><svg class="svc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg></div>
        <h3>Servicio cuatro</h3>
        <p>Descripción del cuarto servicio. Equipo propio, precio cerrado, sin subcontratas.</p>
        <span class="svc-more">Ver más &rarr;</span>
      </a>
      <a href="#" class="svc-card">
        <div class="svc-icon-wrap"><svg class="svc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
        <h3>Servicio cinco</h3>
        <p>Descripción del quinto servicio. Reformas integrales y trabajos de larga duración.</p>
        <span class="svc-more">Ver más &rarr;</span>
      </a>
      <a href="#" class="svc-card">
        <div class="svc-icon-wrap"><svg class="svc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></div>
        <h3>Servicio seis</h3>
        <p>Descripción del sexto servicio. Financiación disponible para instalaciones completas.</p>
        <span class="svc-more">Ver más &rarr;</span>
      </a>
    </div>
  </div>
</section>',
  ],

  [
    'cat'    => 'seccion',
    'label'  => 'Secciones',
    'nombre' => 'Proceso 3 pasos',
    'desc'   => '3 pasos con número, flechas separadoras y botones CTA al final',
    'html'   => '<section class="home-sec home-sec-dark">
  <div class="home-con">
    <p class="home-lbl home-lbl-light">Proceso de trabajo</p>
    <h2>Así de fácil es <span class="hl">trabajar con nosotros</span></h2>
    <p class="home-sub">Sin burocracia, sin esperas. Tres pasos y tu problema está resuelto.</p>
    <div class="pasos-grid">
      <div class="paso-card">
        <div class="paso-num">1</div>
        <div class="paso-content">
          <h3>Primer paso</h3>
          <p>Descripción del primer paso del proceso. Explica qué hace el cliente o qué ocurre en esta fase.</p>
        </div>
      </div>
      <div class="paso-sep" aria-hidden="true">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="rgba(11,36,71,.25)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
      </div>
      <div class="paso-card">
        <div class="paso-num">2</div>
        <div class="paso-content">
          <h3>Segundo paso</h3>
          <p>Descripción del segundo paso del proceso. Explica qué ocurre a continuación.</p>
        </div>
      </div>
      <div class="paso-sep" aria-hidden="true">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="rgba(11,36,71,.25)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
      </div>
      <div class="paso-card">
        <div class="paso-num">3</div>
        <div class="paso-content">
          <h3>Tercer paso</h3>
          <p>Descripción del tercer paso. Explica el resultado final o la entrega del servicio.</p>
        </div>
      </div>
    </div>
    <div class="pasos-cta">
      <a href="tel:+34613429032" class="btn-dark-main">📞 Llamar ahora</a>
      <a href="/contacto" class="btn-dark-ghost">Solicitar presupuesto</a>
    </div>
  </div>
</section>',
  ],

  [
    'cat'    => 'seccion',
    'label'  => 'Secciones',
    'nombre' => 'Cómo funciona (4 pasos)',
    'desc'   => '4 pasos numerados en grid con líneas separadoras',
    'html'   => '<section class="fin-sec fin-sec-gray">
  <div class="fin-con">
    <p class="zona-lbl">Cómo funciona</p>
    <h2 class="fin-h2 fin-h2-mb">Cuatro pasos, <span class="fin-hl">sin complicaciones</span></h2>
    <div class="fin-pasos-grid">
      <div class="fin-paso"><span class="fin-paso-num">01</span><h3 class="fin-paso-h">Primer paso</h3><p class="fin-paso-p">Descripción de lo que ocurre en este paso del proceso.</p></div>
      <div class="fin-paso"><span class="fin-paso-num">02</span><h3 class="fin-paso-h">Segundo paso</h3><p class="fin-paso-p">Descripción de lo que ocurre en este paso del proceso.</p></div>
      <div class="fin-paso"><span class="fin-paso-num">03</span><h3 class="fin-paso-h">Tercer paso</h3><p class="fin-paso-p">Descripción de lo que ocurre en este paso del proceso.</p></div>
      <div class="fin-paso"><span class="fin-paso-num">04</span><h3 class="fin-paso-h">Cuarto paso</h3><p class="fin-paso-p">Descripción de lo que ocurre en este paso del proceso.</p></div>
    </div>
  </div>
</section>',
  ],

  [
    'cat'    => 'seccion',
    'label'  => 'Secciones',
    'nombre' => 'Por qué nosotros (4 cards)',
    'desc'   => '4 tarjetas con icono, valor estadístico, título y descripción',
    'html'   => '<section class="home-sec home-sec-gray">
  <div class="home-con">
    <p class="home-lbl">Por qué elegirnos</p>
    <h2>Lo que nos diferencia <span class="hl">del resto</span></h2>
    <p class="home-sub">No competimos por ser los más baratos. Competimos por hacer el trabajo mejor.</p>
    <div class="porque-grid">
      <div class="porque-card">
        <div class="porque-icon-wrap"><svg class="porque-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></div>
        <div class="porque-val">100%</div>
        <h3>Precio cerrado</h3>
        <p>Antes de empezar cualquier trabajo te damos un presupuesto real y definitivo. Sin cambios.</p>
      </div>
      <div class="porque-card">
        <div class="porque-icon-wrap"><svg class="porque-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
        <div class="porque-val">24h</div>
        <h3>Urgencias 24 horas</h3>
        <p>Disponibles todos los días para resolver emergencias en el menor tiempo posible.</p>
      </div>
      <div class="porque-card">
        <div class="porque-icon-wrap"><svg class="porque-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
        <div class="porque-val">2 años</div>
        <h3>Garantía de trabajo</h3>
        <p>Todos nuestros trabajos tienen garantía. Si algo falla, volvemos y lo arreglamos.</p>
      </div>
      <div class="porque-card">
        <div class="porque-icon-wrap"><svg class="porque-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg></div>
        <div class="porque-val">Local</div>
        <h3>Equipo propio</h3>
        <p>Sin subcontratas. El técnico que viene es nuestro y conoce la zona perfectamente.</p>
      </div>
    </div>
  </div>
</section>',
  ],

  [
    'cat'    => 'seccion',
    'label'  => 'Secciones',
    'nombre' => 'Testimonios (3 cards)',
    'desc'   => '3 tarjetas con estrellas, cita textual, nombre y localidad',
    'html'   => '<section class="home-sec">
  <div class="home-con">
    <p class="home-lbl">Testimonios</p>
    <h2>Lo que dicen nuestros <span class="hl">clientes</span></h2>
    <p class="home-sub">Trabajos reales, clientes reales.</p>
    <div class="testimonios-grid">
      <div class="testimonio-card">
        <div class="testimonio-estrellas" aria-label="5 de 5 estrellas">
          <svg viewBox="0 0 24 24" fill="#F59E0B" width="16" height="16"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          <svg viewBox="0 0 24 24" fill="#F59E0B" width="16" height="16"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          <svg viewBox="0 0 24 24" fill="#F59E0B" width="16" height="16"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          <svg viewBox="0 0 24 24" fill="#F59E0B" width="16" height="16"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          <svg viewBox="0 0 24 24" fill="#F59E0B" width="16" height="16"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        </div>
        <blockquote class="testimonio-texto">"Vinieron a cambiar el termo y dejaron todo perfecto. El precio fue exactamente el del presupuesto, sin sorpresas. Muy recomendables."</blockquote>
        <div class="testimonio-autor">
          <div class="testimonio-avatar">M</div>
          <div><strong>María G.</strong><span>Cliente en Elda</span></div>
        </div>
      </div>
      <div class="testimonio-card">
        <div class="testimonio-estrellas" aria-label="5 de 5 estrellas">
          <svg viewBox="0 0 24 24" fill="#F59E0B" width="16" height="16"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          <svg viewBox="0 0 24 24" fill="#F59E0B" width="16" height="16"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          <svg viewBox="0 0 24 24" fill="#F59E0B" width="16" height="16"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          <svg viewBox="0 0 24 24" fill="#F59E0B" width="16" height="16"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          <svg viewBox="0 0 24 24" fill="#F59E0B" width="16" height="16"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        </div>
        <blockquote class="testimonio-texto">"Tuvimos una fuga en casa y vinieron en menos de una hora. Encontraron el problema rápido y lo repararon sin destrozos. Muy contentos."</blockquote>
        <div class="testimonio-autor">
          <div class="testimonio-avatar">J</div>
          <div><strong>Juan R.</strong><span>Cliente en Petrer</span></div>
        </div>
      </div>
      <div class="testimonio-card">
        <div class="testimonio-estrellas" aria-label="5 de 5 estrellas">
          <svg viewBox="0 0 24 24" fill="#F59E0B" width="16" height="16"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          <svg viewBox="0 0 24 24" fill="#F59E0B" width="16" height="16"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          <svg viewBox="0 0 24 24" fill="#F59E0B" width="16" height="16"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          <svg viewBox="0 0 24 24" fill="#F59E0B" width="16" height="16"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          <svg viewBox="0 0 24 24" fill="#F59E0B" width="16" height="16"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        </div>
        <blockquote class="testimonio-texto">"Instalaron una ósmosis y un descalcificador. Trabajo limpio, explicaron todo bien y el precio fue el que dijeron desde el principio."</blockquote>
        <div class="testimonio-autor">
          <div class="testimonio-avatar">A</div>
          <div><strong>Ana M.</strong><span>Cliente en Novelda</span></div>
        </div>
      </div>
    </div>
  </div>
</section>',
  ],

  [
    'cat'    => 'seccion',
    'label'  => 'Secciones',
    'nombre' => 'Ventajas (2 columnas)',
    'desc'   => 'Lista de checks a la izquierda + tarjetas de detalle a la derecha',
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
    'cat'    => 'seccion',
    'label'  => 'Secciones',
    'nombre' => 'Tabla 3 columnas',
    'desc'   => 'Grid de 3 celdas con borde, título y descripción por columna',
    'html'   => '<section class="sc-sec sc-sec-gray">
  <div class="sc-con">
    <p class="zona-lbl">Por qué elegirnos</p>
    <h2 class="sc-h2">Cómo <span class="hl">trabajamos</span></h2>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:#e2e8f0;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;margin-top:2rem">
      <div style="background:#fff;padding:1.75rem 1.5rem;display:flex;flex-direction:column;gap:.75rem">
        <h3 style="color:#0d1f33;font-size:14.5px;font-weight:700">Columna uno</h3>
        <p style="color:#64748b;font-size:13px;line-height:1.65">Descripción del primer punto. Explica el valor o característica en 2-3 líneas.</p>
      </div>
      <div style="background:#fff;padding:1.75rem 1.5rem;display:flex;flex-direction:column;gap:.75rem">
        <h3 style="color:#0d1f33;font-size:14.5px;font-weight:700">Columna dos</h3>
        <p style="color:#64748b;font-size:13px;line-height:1.65">Descripción del segundo punto. Explica el valor o característica en 2-3 líneas.</p>
      </div>
      <div style="background:#fff;padding:1.75rem 1.5rem;display:flex;flex-direction:column;gap:.75rem">
        <h3 style="color:#0d1f33;font-size:14.5px;font-weight:700">Columna tres</h3>
        <p style="color:#64748b;font-size:13px;line-height:1.65">Descripción del tercer punto. Explica el valor o característica en 2-3 líneas.</p>
      </div>
    </div>
  </div>
</section>',
  ],

  [
    'cat'    => 'seccion',
    'label'  => 'Secciones',
    'nombre' => 'Tabla 4 columnas',
    'desc'   => 'Grid de 4 celdas numeradas con borde, título y descripción',
    'html'   => '<section style="padding:5rem 0;background:#f8fafc;border-top:1px solid #f1f5f9">
  <div style="max-width:1100px;margin:0 auto;padding:0 var(--space-md)">
    <p class="zona-lbl">Proceso</p>
    <h2 style="font-size:clamp(1.7rem,3.5vw,2.5rem);font-weight:800;color:#0d1f33;letter-spacing:-.025em;line-height:1.15;margin-bottom:2.5rem">Cuatro pasos, <span style="color:#3b82f6">sin complicaciones</span></h2>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:#e2e8f0;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden">
      <div style="background:#fff;padding:1.75rem 1.5rem;display:flex;flex-direction:column;gap:.75rem">
        <span style="font-size:11px;font-weight:700;color:#cbd5e1;letter-spacing:.1em">01</span>
        <h3 style="color:#0d1f33;font-size:14.5px;font-weight:700;line-height:1.3">Primer paso</h3>
        <p style="color:#64748b;font-size:13px;line-height:1.6">Descripción del primer paso del proceso.</p>
      </div>
      <div style="background:#fff;padding:1.75rem 1.5rem;display:flex;flex-direction:column;gap:.75rem">
        <span style="font-size:11px;font-weight:700;color:#cbd5e1;letter-spacing:.1em">02</span>
        <h3 style="color:#0d1f33;font-size:14.5px;font-weight:700;line-height:1.3">Segundo paso</h3>
        <p style="color:#64748b;font-size:13px;line-height:1.6">Descripción del segundo paso del proceso.</p>
      </div>
      <div style="background:#fff;padding:1.75rem 1.5rem;display:flex;flex-direction:column;gap:.75rem">
        <span style="font-size:11px;font-weight:700;color:#cbd5e1;letter-spacing:.1em">03</span>
        <h3 style="color:#0d1f33;font-size:14.5px;font-weight:700;line-height:1.3">Tercer paso</h3>
        <p style="color:#64748b;font-size:13px;line-height:1.6">Descripción del tercer paso del proceso.</p>
      </div>
      <div style="background:#fff;padding:1.75rem 1.5rem;display:flex;flex-direction:column;gap:.75rem">
        <span style="font-size:11px;font-weight:700;color:#cbd5e1;letter-spacing:.1em">04</span>
        <h3 style="color:#0d1f33;font-size:14.5px;font-weight:700;line-height:1.3">Cuarto paso</h3>
        <p style="color:#64748b;font-size:13px;line-height:1.6">Descripción del cuarto paso del proceso.</p>
      </div>
    </div>
  </div>
</section>',
  ],

  [
    'cat'    => 'seccion',
    'label'  => 'Secciones',
    'nombre' => 'Detalle de servicio',
    'desc'   => 'Texto + lista de checks a la izquierda, panel visual a la derecha',
    'html'   => '<section class="servicio-detalle" id="servicio">
  <div class="servicio-con">
    <div class="servicio-detalle-inner">
      <div class="servicio-detalle-texto">
        <p class="servicio-lbl">Servicio</p>
        <h2>Nombre del servicio</h2>
        <p>Descripción detallada del servicio. Explica en qué consiste, cuándo se necesita y cómo se resuelve. Usa frases claras y directas orientadas al cliente.</p>
        <ul class="servicio-chk">
          <li><span class="servicio-chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Primer punto del servicio</li>
          <li><span class="servicio-chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Segundo punto del servicio</li>
          <li><span class="servicio-chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Tercer punto del servicio</li>
          <li><span class="servicio-chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Cuarto punto del servicio</li>
        </ul>
        <a href="tel:+34613429032" class="btn-servicio">📞 Llamar ahora</a>
      </div>
      <div class="servicio-visual">
        <span class="servicio-visual-badge">Urgencias · Precio cerrado</span>
        <div class="servicio-ico-grande">🔧</div>
        <p class="servicio-visual-txt">Precio cerrado antes de empezar. Sin sorpresas al final.</p>
      </div>
    </div>
  </div>
</section>',
  ],

  [
    'cat'    => 'seccion',
    'label'  => 'Secciones',
    'nombre' => 'Sección con título centrado',
    'desc'   => 'Label + H2 + subtítulo centrados como cabecera de sección',
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

  /* ── SERVICIOS ─────────────────────────── */
  [
    'cat'    => 'servicios',
    'label'  => 'Servicios',
    'nombre' => 'Lista servicios por municipio',
    'desc'   => 'Links de servicio por ciudad con código postal y flecha',
    'html'   => '<section class="sc-sec">
  <div class="sc-con">
    <p class="zona-lbl">¿Dónde necesitas el servicio?</p>
    <h2 class="sc-h2">Selecciona <span class="hl">tu municipio</span></h2>
    <div class="sc-enlaces" style="margin-top:2rem">
      <a href="#" class="sc-enlace"><strong>Servicio en Elda</strong><span>CP 03600 · Ver servicio →</span></a>
      <a href="#" class="sc-enlace"><strong>Servicio en Petrer</strong><span>CP 03610 · Ver servicio →</span></a>
      <a href="#" class="sc-enlace"><strong>Servicio en Novelda</strong><span>CP 03660 · Ver servicio →</span></a>
      <a href="#" class="sc-enlace"><strong>Servicio en Monóvar</strong><span>CP 03640 · Ver servicio →</span></a>
      <a href="#" class="sc-enlace"><strong>Servicio en Sax</strong><span>CP 03630 · Ver servicio →</span></a>
      <a href="#" class="sc-enlace"><strong>Servicio en Pinoso</strong><span>CP 03650 · Ver servicio →</span></a>
      <a href="#" class="sc-enlace"><strong>Servicio en Monforte del Cid</strong><span>CP 03670 · Ver servicio →</span></a>
      <a href="#" class="sc-enlace"><strong>Servicio en Salinas</strong><span>CP 03688 · Ver servicio →</span></a>
    </div>
  </div>
</section>',
  ],

  [
    'cat'    => 'servicios',
    'label'  => 'Servicios',
    'nombre' => 'Trabajos numerados (6 items)',
    'desc'   => '6 bloques numerados con título y descripción en grid',
    'html'   => '<section class="sc-sec">
  <div class="sc-con">
    <p class="zona-lbl">Servicios</p>
    <h2 class="sc-h2">Qué trabajos <span class="hl">realizamos</span></h2>
    <div class="sc-problemas" style="margin-top:2rem">
      <div class="sc-prob"><span class="sc-prob-num">01</span><h3>Primer trabajo</h3><p>Descripción del primer tipo de trabajo o servicio ofrecido.</p></div>
      <div class="sc-prob"><span class="sc-prob-num">02</span><h3>Segundo trabajo</h3><p>Descripción del segundo tipo de trabajo o servicio ofrecido.</p></div>
      <div class="sc-prob"><span class="sc-prob-num">03</span><h3>Tercer trabajo</h3><p>Descripción del tercer tipo de trabajo o servicio ofrecido.</p></div>
      <div class="sc-prob"><span class="sc-prob-num">04</span><h3>Cuarto trabajo</h3><p>Descripción del cuarto tipo de trabajo o servicio ofrecido.</p></div>
      <div class="sc-prob"><span class="sc-prob-num">05</span><h3>Quinto trabajo</h3><p>Descripción del quinto tipo de trabajo o servicio ofrecido.</p></div>
      <div class="sc-prob"><span class="sc-prob-num">06</span><h3>Sexto trabajo</h3><p>Descripción del sexto tipo de trabajo o servicio ofrecido.</p></div>
    </div>
  </div>
</section>',
  ],

  /* ── ZONAS ──────────────────────────────── */
  [
    'cat'    => 'zonas',
    'label'  => 'Zonas',
    'nombre' => 'Chips de municipios',
    'desc'   => 'Grid de píldoras enlazables con pin y nombre de cada zona',
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

  [
    'cat'    => 'zonas',
    'label'  => 'Zonas',
    'nombre' => 'Tarjetas de municipios',
    'desc'   => 'Grid de cards por municipio con CP, servicios disponibles y enlace',
    'html'   => '<section style="padding:5rem 0">
  <div style="max-width:1100px;margin:0 auto;padding:0 var(--space-md)">
    <p class="zona-lbl">¿Dónde trabajamos?</p>
    <h2 style="font-size:clamp(1.7rem,3.5vw,2.5rem);font-weight:800;color:#0d1f33;letter-spacing:-.025em;line-height:1.15;margin-bottom:.75rem">Selecciona <span style="color:#3b82f6">tu municipio</span></h2>
    <p style="color:#64748b;font-size:15px;margin-bottom:2.5rem">Accede a tu zona para ver los servicios disponibles.</p>
    <div class="zonas-grid">
      <a href="/zonas/elda" class="zona-card">
        <div class="zona-card-header"><span class="zona-card-ico">📍</span><div><span class="zona-card-nombre">Elda</span><span class="zona-card-cp">CP 03600</span></div></div>
        <div class="zona-card-servicios"><span>Fontanero</span><span>Fugas</span><span>Desatascos</span></div>
        <span class="zona-card-link">Ver servicios en Elda →</span>
      </a>
      <a href="/zonas/petrer" class="zona-card">
        <div class="zona-card-header"><span class="zona-card-ico">📍</span><div><span class="zona-card-nombre">Petrer</span><span class="zona-card-cp">CP 03610</span></div></div>
        <div class="zona-card-servicios"><span>Fontanero</span><span>Fugas</span><span>Desatascos</span></div>
        <span class="zona-card-link">Ver servicios en Petrer →</span>
      </a>
      <a href="/zonas/novelda" class="zona-card">
        <div class="zona-card-header"><span class="zona-card-ico">📍</span><div><span class="zona-card-nombre">Novelda</span><span class="zona-card-cp">CP 03660</span></div></div>
        <div class="zona-card-servicios"><span>Fontanero</span><span>Fugas</span><span>Desatascos</span></div>
        <span class="zona-card-link">Ver servicios en Novelda →</span>
      </a>
      <a href="/zonas/monovar" class="zona-card">
        <div class="zona-card-header"><span class="zona-card-ico">📍</span><div><span class="zona-card-nombre">Monóvar</span><span class="zona-card-cp">CP 03640</span></div></div>
        <div class="zona-card-servicios"><span>Fontanero</span><span>Fugas</span><span>Desatascos</span></div>
        <span class="zona-card-link">Ver servicios en Monóvar →</span>
      </a>
    </div>
  </div>
</section>',
  ],

  [
    'cat'    => 'zonas',
    'label'  => 'Zonas',
    'nombre' => 'Info card de empresa',
    'desc'   => 'Tarjeta lateral con datos de contacto, horario y botón de llamada',
    'html'   => '<section style="padding:3rem 0">
  <div style="max-width:480px;margin:0 auto;padding:0 var(--space-md)">
    <div class="zona-icard">
      <div class="zona-icard-h"><strong>Nombre de empresa</strong><span>Descripción del negocio</span></div>
      <div class="zona-ir"><span class="zona-ir-l">Ubicación</span><span class="zona-ir-v">Ciudad, Provincia</span></div>
      <div class="zona-ir"><span class="zona-ir-l">Teléfono</span><span class="zona-ir-v"><a href="tel:+34613429032">613 429 032</a></span></div>
      <div class="zona-ir"><span class="zona-ir-l">WhatsApp</span><span class="zona-ir-v"><a href="https://wa.me/34613429032">Escribir ahora →</a></span></div>
      <div class="zona-ir"><span class="zona-ir-l">Horario</span><span class="zona-ir-v">Lun–Vie 8:00–20:00 · Sáb 9:00–14:00</span></div>
      <div class="zona-ir"><span class="zona-ir-l">Financiación</span><span class="zona-ir-v">Material + instalación incluidos</span></div>
      <a href="tel:+34613429032" class="zona-icard-btn">📞 Llamar ahora</a>
    </div>
  </div>
</section>',
  ],

  [
    'cat'    => 'zonas',
    'label'  => 'Zonas',
    'nombre' => 'Strip diferenciadores',
    'desc'   => 'Barra horizontal con 4 métricas o valores destacados',
    'html'   => '<div class="dif-strip">
  <div class="dif-strip-in">
    <div class="dif-item"><span class="dif-val">8 municipios</span><span class="dif-lbl">Y todas sus zonas cercanas</span></div>
    <div class="dif-item"><span class="dif-val">Precio cerrado</span><span class="dif-lbl">Sin sorpresas al final</span></div>
    <div class="dif-item"><span class="dif-val">24h</span><span class="dif-lbl">Urgencias todos los días</span></div>
    <div class="dif-item"><span class="dif-val">Garantía</span><span class="dif-lbl">En todos los trabajos realizados</span></div>
  </div>
</div>',
  ],

  [
    'cat'    => 'zonas',
    'label'  => 'Zonas',
    'nombre' => 'Tags zonas cercanas',
    'desc'   => 'Sección gris con pills enlazables a municipios de la comarca',
    'html'   => '<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Comarca del Vinalopó</p>
    <h2>También trabajamos en <span class="hl">zonas cercanas</span></h2>
    <div class="zona-ztags" style="margin-top:1.5rem">
      <a href="/zonas/elda"     class="zona-ztag">Elda</a>
      <a href="/zonas/petrer"   class="zona-ztag">Petrer</a>
      <a href="/zonas/novelda"  class="zona-ztag">Novelda</a>
      <a href="/zonas/monovar"  class="zona-ztag">Monóvar</a>
      <a href="/zonas/sax"      class="zona-ztag">Sax</a>
      <a href="/zonas/pinoso"   class="zona-ztag">Pinoso</a>
      <a href="/zonas/monforte" class="zona-ztag">Monforte del Cid</a>
      <a href="/zonas/salinas"  class="zona-ztag">Salinas</a>
    </div>
  </div>
</section>',
  ],

  /* ── CTA ────────────────────────────────── */
  [
    'cat'    => 'cta',
    'label'  => 'CTA',
    'nombre' => 'CTA oscuro',
    'desc'   => 'Bloque final azul marino con título, texto, teléfono y botones',
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
    'cat'    => 'cta',
    'label'  => 'CTA',
    'nombre' => 'CTA final con teléfonos',
    'desc'   => 'Sección final oscura con badge, dos teléfonos grandes y botones',
    'html'   => '<section class="cta-final">
  <div class="cta-final-grid"></div>
  <div class="cta-final-glow"></div>
  <div class="home-con cta-final-con">
    <div class="cta-final-badge"><span class="cta-final-dot"></span>Disponibles ahora</div>
    <h2>Llama y te atendemos <span class="hl">hoy mismo</span></h2>
    <p>Sin colas, sin formularios. Habla directamente con nosotros.</p>
    <div class="cta-final-tel-wrap">
      <a href="tel:+34613429032" class="cta-final-tel">613 429 032</a>
      <span class="cta-final-tel-sep">&middot;</span>
      <a href="tel:+34611165129" class="cta-final-tel cta-final-tel-2">611 165 129</a>
    </div>
    <div class="cta-final-btns">
      <a href="tel:+34613429032" class="hero-btn-primary">📞 Llamar ahora</a>
      <a href="https://wa.me/34613429032" target="_blank" rel="noopener" class="hero-btn-wa">💬 WhatsApp</a>
    </div>
  </div>
</section>',
  ],

  [
    'cat'    => 'cta',
    'label'  => 'CTA',
    'nombre' => 'Banner de contacto',
    'desc'   => 'Sección azul claro con H2, texto y botón de presupuesto',
    'html'   => '<section class="home-sec home-sec-dark">
  <div class="home-con" style="text-align:center">
    <p class="home-lbl home-lbl-light">Contacto</p>
    <h2>¿Hablamos de tu <span class="hl">proyecto?</span></h2>
    <p class="home-sub" style="margin:0 auto 2rem">Cuéntanos qué necesitas y te damos un precio cerrado sin compromiso.</p>
    <a href="/contacto" class="btn-dark-main">Pedir presupuesto gratis</a>
  </div>
</section>',
  ],

  /* ── CONTACTO ───────────────────────────── */
  [
    'cat'    => 'contacto',
    'label'  => 'Contacto',
    'nombre' => 'Métodos de contacto',
    'desc'   => 'Cards de teléfono, WhatsApp y email con horario incluido',
    'html'   => '<section style="padding:3rem 0">
  <div style="max-width:640px;margin:0 auto;padding:0 var(--space-md)">
    <p class="zona-lbl">Otras formas de contacto</p>
    <h2 style="font-size:clamp(1.5rem,3vw,2rem);font-weight:800;color:#0d1f33;letter-spacing:-.025em;margin-bottom:.5rem">Contacta <span style="color:#3b82f6">directamente</span></h2>
    <p style="color:#64748b;font-size:14px;margin-bottom:1.75rem">Si lo prefieres, llámanos o escríbenos por WhatsApp.</p>
    <div class="contacto-metodos">
      <a href="tel:+34613429032" class="contacto-metodo">
        <div class="contacto-metodo-ico">📞</div>
        <div class="contacto-metodo-texto">
          <strong>Llamada</strong>
          <span>613 429 032</span>
          <small>Lun–Vie 8:00–20:00 · Sáb 9:00–14:00</small>
        </div>
      </a>
      <a href="https://wa.me/34613429032" class="contacto-metodo" target="_blank" rel="noopener">
        <div class="contacto-metodo-ico">💬</div>
        <div class="contacto-metodo-texto">
          <strong>WhatsApp</strong>
          <span>Respuesta rápida</span>
          <small>Disponible todos los días</small>
        </div>
      </a>
      <a href="mailto:info@caroltemp.com" class="contacto-metodo">
        <div class="contacto-metodo-ico">✉️</div>
        <div class="contacto-metodo-texto">
          <strong>Email</strong>
          <span>info@caroltemp.com</span>
          <small>Respondemos en menos de 24h</small>
        </div>
      </a>
    </div>
  </div>
</section>',
  ],

  /* ── TEXTO ──────────────────────────────── */
  [
    'cat'    => 'texto',
    'label'  => 'Texto',
    'nombre' => 'Texto enriquecido',
    'desc'   => 'Bloque de contenido con H2, párrafos, lista y enlace',
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
    'cat'    => 'texto',
    'label'  => 'Texto',
    'nombre' => 'Bloque de texto largo (prose)',
    'desc'   => 'Sección gris con label, H2 y varios párrafos de texto continuo',
    'html'   => '<section class="sc-sec sc-sec-gray">
  <div class="sc-con">
    <p class="zona-lbl">Información</p>
    <h2 class="sc-h2">Título de la sección <span class="hl">de texto</span></h2>
    <div class="sc-prose" style="max-width:780px;margin-top:1.25rem">
      <p>Primer párrafo introductorio. Explica el tema de forma clara y directa. Usa frases cortas para facilitar la lectura en móvil y no superes las 4-5 líneas por párrafo.</p>
      <p>Segundo párrafo con más detalle. Puedes añadir tantos párrafos como necesites. El texto debe responder a la pregunta del usuario y ofrecer información útil y relevante.</p>
      <p>Tercer párrafo de cierre. Resume los puntos clave y refuerza la llamada a la acción al final de la sección con un enlace o número de teléfono.</p>
    </div>
  </div>
</section>',
  ],

  [
    'cat'    => 'texto',
    'label'  => 'Texto',
    'nombre' => 'FAQ — Preguntas frecuentes',
    'desc'   => 'Lista de preguntas y respuestas en acordeón visual',
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
      <div style="background:#fff;border:1.5px solid #e8eff8;border-radius:12px;padding:1.25rem 1.5rem">
        <strong style="color:#0B2447;font-size:15px;display:block;margin-bottom:.5rem">¿Ofrecéis financiación?</strong>
        <p style="color:#64748b;font-size:14px;line-height:1.65;margin:0">Sí, financiamos instalaciones de ósmosis, descalcificadores, termos y reformas de baño. Material y mano de obra incluidos, sin adelanto.</p>
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

document.getElementById('modal-bloques').addEventListener('click', function(e) {
  if (e.target === this) cerrarModalBloques();
});
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
