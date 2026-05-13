<?php
$meta_title  = "Fontanero en Alicante — CarolTemp | Urgencias 24h Vinalopó";
$meta_desc   = "Fontanero urgente en Elda, Petrer, Novelda y toda la comarca del Vinalopó. Detección de fugas, desatascos, climatización y reformas. Precio cerrado. Llama ya: 613 429 032.";
$meta_url    = "https://caroltemp.com/";
$schema_type = "home";
$page_css    = "home";
$page_js     = "home";

include 'includes/head.php';
?>
<?php require_once 'includes/db.php'; ?>

<!-- ============================
     HERO — 2 columnas estilo ContaSimple
============================= -->
<section class="hero" aria-label="Cabecera principal">
  <div class="hero-inner">

    <!-- COL IZQUIERDA: Texto -->
    <div class="hero-text">

      <div class="hero-badge">
        <span class="hero-badge-dot"></span>
        Urgencias 24h &mdash; Respuesta inmediata
      </div>

      <h1>El fontanero de confianza en <span class="hl">Elda, Petrer y el Vinalopó</span></h1>

      <p class="hero-sub">Reparaciones urgentes, detección de fugas, desatascos, climatización y reformas. Precio cerrado antes de empezar — sin sorpresas.</p>

      <div class="hero-btns">
        <a href="tel:+34613429032" class="hero-btn-primary">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 10.8 19.79 19.79 0 01.38 2.18 2 2 0 012.37 0h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.09 6.09l1.27-.71a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
          Llamar ahora
        </a>
        <a href="https://wa.me/34613429032?text=Hola,%20me%20gustar%C3%ADa%20pedir%20un%20presupuesto" target="_blank" rel="noopener" class="hero-btn-wa">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.117 1.523 5.847L.057 23.882a.5.5 0 00.614.612l6.094-1.596A11.942 11.942 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.894a9.892 9.892 0 01-5.031-1.371l-.361-.214-3.741.98.999-3.648-.235-.374A9.865 9.865 0 012.106 12C2.106 6.533 6.533 2.106 12 2.106S21.894 6.533 21.894 12 17.467 21.894 12 21.894z"/></svg>
          WhatsApp
        </a>
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

    <!-- COL DERECHA: Foto -->
    <div class="hero-photo">
      <img
        src="<?php echo $base_url; ?>img/contenido/hero-home.png"
        alt="Fontanero profesional trabajando en Elda"
        width="720"
        height="480"
        loading="eager"
      >
    </div>

  </div>
</section>

<!-- ============================
     STRIP DE ZONAS
============================= -->
<div class="zonas-bar" aria-label="Municipios donde trabajamos">
  <div class="zonas-bar-inner">
    <span class="zonas-label">Trabajamos en:</span>
    <div class="zonas-tags">
      <a href="<?php echo $base_url; ?>zonas/elda"     class="zona-tag">Elda</a>
      <a href="<?php echo $base_url; ?>zonas/petrer"   class="zona-tag">Petrer</a>
      <a href="<?php echo $base_url; ?>zonas/novelda"  class="zona-tag">Novelda</a>
      <a href="<?php echo $base_url; ?>zonas/monovar"  class="zona-tag">Monóvar</a>
      <a href="<?php echo $base_url; ?>zonas/sax"      class="zona-tag">Sax</a>
      <a href="<?php echo $base_url; ?>zonas/pinoso"   class="zona-tag">Pinoso</a>
      <a href="<?php echo $base_url; ?>zonas/monforte" class="zona-tag">Monforte del Cid</a>
      <a href="<?php echo $base_url; ?>zonas/salinas"  class="zona-tag">Salinas</a>
    </div>
  </div>
</div>

<!-- ============================
     SERVICIOS — 6 cards con icono
============================= -->
<section class="home-sec" id="servicios" aria-labelledby="svc-title">
  <div class="home-con">
    <p class="home-lbl">Nuestros servicios</p>
    <h2 id="svc-title">Todo lo que necesitas <span class="hl">para tu hogar</span></h2>
    <p class="home-sub">Fontanería, climatización y reformas en el Vinalopó. Un solo equipo, precio cerrado, trabajo garantizado.</p>

    <div class="svc-grid">

      <!-- Fontanería -->
      <a href="<?php echo $base_url; ?>servicios#reparaciones" class="svc-card">
        <div class="svc-icon-wrap">
          <svg class="svc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/>
          </svg>
        </div>
        <h3>Fontanería general</h3>
        <p>Reparaciones urgentes, instalaciones completas, cisternas, grifos y averías en viviendas de toda la comarca.</p>
        <span class="svc-more">Ver más &rarr;</span>
      </a>

      <!-- Fugas -->
      <a href="<?php echo $base_url; ?>fugas/" class="svc-card">
        <div class="svc-icon-wrap">
          <svg class="svc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
            <circle cx="18" cy="18" r="3"/>
            <line x1="20.12" y1="20.12" x2="23" y2="23"/>
            <path d="M12 6v6l3 3"/>
          </svg>
        </div>
        <h3>Detección de fugas</h3>
        <p>Localización sin obras con geófono y cámara endoscópica. Encontramos la fuga sin destrozar tu vivienda.</p>
        <span class="svc-more">Ver más &rarr;</span>
      </a>

      <!-- Desatascos -->
      <a href="<?php echo $base_url; ?>desatascos/" class="svc-card">
        <div class="svc-icon-wrap">
          <svg class="svc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="18" height="18" rx="2"/>
            <path d="M9 9h6M9 12h6M9 15h4"/>
            <circle cx="17" cy="17" r="0"/>
            <path d="M12 3v4M12 17v4"/>
          </svg>
        </div>
        <h3>Desatascos</h3>
        <p>Desatasco urgente de tuberías, bajantes, inodoros y arquetas. Servicio rápido y limpio en toda la zona.</p>
        <span class="svc-more">Ver más &rarr;</span>
      </a>

      <!-- Termos -->
      <a href="<?php echo $base_url; ?>servicios#termos" class="svc-card">
        <div class="svc-icon-wrap">
          <svg class="svc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="7" y="2" width="10" height="20" rx="2"/>
            <path d="M12 6v4M10 18h4"/>
            <path d="M7 8H5a2 2 0 000 4h2M17 8h2a2 2 0 010 4h-2"/>
          </svg>
        </div>
        <h3>Termos y calentadores</h3>
        <p>Instalación de termos eléctricos Nubeco. Instalador oficial con garantía de fabricante en el Vinalopó.</p>
        <span class="svc-more">Ver más &rarr;</span>
      </a>

      <!-- Climatización -->
      <a href="<?php echo $base_url; ?>servicios" class="svc-card">
        <div class="svc-icon-wrap">
          <svg class="svc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="2" x2="12" y2="22"/>
            <path d="M2 12h20"/>
            <path d="M12 2L8 6M12 2l4 4M12 22l-4-4M12 22l4-4M2 12l4-4M2 12l4 4M22 12l-4-4M22 12l-4 4"/>
          </svg>
        </div>
        <h3>Climatización</h3>
        <p>Instalación y mantenimiento de aires acondicionados y sistemas de climatización para tu vivienda o negocio.</p>
        <span class="svc-more">Ver más &rarr;</span>
      </a>

      <!-- Reformas -->
      <a href="<?php echo $base_url; ?>servicios#reformas" class="svc-card">
        <div class="svc-icon-wrap">
          <svg class="svc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
            <polyline points="9 22 9 12 15 12 15 22"/>
          </svg>
        </div>
        <h3>Reformas de baño</h3>
        <p>Reformas completas o parciales. Precio cerrado antes de empezar, acabados de calidad, plazo cumplido.</p>
        <span class="svc-more">Ver más &rarr;</span>
      </a>

    </div><!-- /svc-grid -->
  </div>
</section>

<!-- ============================
     CÓMO TRABAJAMOS — 3 pasos
============================= -->
<section class="home-sec home-sec-dark" aria-labelledby="como-title">
  <div class="home-con">
    <p class="home-lbl home-lbl-light">Proceso de trabajo</p>
    <h2 id="como-title">Así de fácil es <span class="hl">trabajar con nosotros</span></h2>
    <p class="home-sub">Sin burocracia, sin esperas. Tres pasos y tu problema está resuelto.</p>

    <div class="pasos-grid">
      <div class="paso-card">
        <div class="paso-num">1</div>
        <div class="paso-content">
          <h3>Nos llamas o escribes</h3>
          <p>Cuéntanos el problema. Respondemos rápido y acordamos una visita en tu horario, incluidos fines de semana en urgencias.</p>
        </div>
      </div>
      <div class="paso-sep" aria-hidden="true">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="rgba(11,36,71,.25)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
      </div>
      <div class="paso-card">
        <div class="paso-num">2</div>
        <div class="paso-content">
          <h3>Presupuesto cerrado</h3>
          <p>Evaluamos in situ y te damos un precio total y definitivo. Sin extras ni sorpresas al finalizar. Sin compromiso.</p>
        </div>
      </div>
      <div class="paso-sep" aria-hidden="true">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="rgba(11,36,71,.25)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
      </div>
      <div class="paso-card">
        <div class="paso-num">3</div>
        <div class="paso-content">
          <h3>Trabajo garantizado</h3>
          <p>Hacemos el trabajo con cuidado y dejamos la zona limpia. Si algo no queda bien, volvemos sin coste adicional.</p>
        </div>
      </div>
    </div>

    <div class="pasos-cta">
      <a href="tel:+34613429032" class="btn-dark-main">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 10.8 19.79 19.79 0 01.38 2.18 2 2 0 012.37 0h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.09 6.09l1.27-.71a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
        Llamar ahora
      </a>
      <a href="<?php echo $base_url; ?>contacto" class="btn-dark-ghost">Solicitar presupuesto</a>
    </div>
  </div>
</section>

<!-- ============================
     ZONAS — chips clicables
============================= -->
<section class="home-sec" aria-labelledby="zonas-title">
  <div class="home-con">
    <p class="home-lbl">Cobertura geográfica</p>
    <h2 id="zonas-title">Fontanero en tu municipio, <span class="hl">hoy mismo</span></h2>
    <p class="home-sub">Cubrimos toda la comarca del Vinalopó Medio y alrededores. Tiempo de respuesta en urgencias: menos de 60 minutos.</p>

    <div class="zonas-chips">
      <a href="<?php echo $base_url; ?>zonas/elda" class="zona-chip">
        <svg class="zona-chip-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
        Elda
      </a>
      <a href="<?php echo $base_url; ?>zonas/petrer" class="zona-chip">
        <svg class="zona-chip-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
        Petrer
      </a>
      <a href="<?php echo $base_url; ?>zonas/novelda" class="zona-chip">
        <svg class="zona-chip-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
        Novelda
      </a>
      <a href="<?php echo $base_url; ?>zonas/monovar" class="zona-chip">
        <svg class="zona-chip-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
        Monóvar
      </a>
      <a href="<?php echo $base_url; ?>zonas/sax" class="zona-chip">
        <svg class="zona-chip-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
        Sax
      </a>
      <a href="<?php echo $base_url; ?>zonas/pinoso" class="zona-chip">
        <svg class="zona-chip-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
        Pinoso
      </a>
      <a href="<?php echo $base_url; ?>zonas/monforte" class="zona-chip">
        <svg class="zona-chip-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
        Monforte del Cid
      </a>
      <a href="<?php echo $base_url; ?>zonas/salinas" class="zona-chip">
        <svg class="zona-chip-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
        Salinas
      </a>
    </div>

    <div style="margin-top:2rem;text-align:center">
      <a href="<?php echo $base_url; ?>zonas" style="color:var(--color-accent);font-size:14px;font-weight:600;text-decoration:none">Ver todas las zonas y servicios por municipio &rarr;</a>
    </div>
  </div>
</section>

<!-- ============================
     POR QUÉ ELEGIRNOS — 4 items
============================= -->
<section class="home-sec home-sec-gray" aria-labelledby="porque-title">
  <div class="home-con">
    <p class="home-lbl">Por qué CarolTemp</p>
    <h2 id="porque-title">Lo que nos diferencia <span class="hl">del resto</span></h2>
    <p class="home-sub">No competimos por ser los más baratos. Competimos por hacer el trabajo mejor.</p>

    <div class="porque-grid">

      <div class="porque-card">
        <div class="porque-icon-wrap">
          <svg class="porque-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
        </div>
        <div class="porque-val">100%</div>
        <h3>Precio cerrado</h3>
        <p>Antes de empezar cualquier trabajo te damos un presupuesto real y definitivo. Lo que acordamos es lo que pagas.</p>
      </div>

      <div class="porque-card">
        <div class="porque-icon-wrap">
          <svg class="porque-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <div class="porque-val">Garantía</div>
        <h3>Trabajo garantizado</h3>
        <p>Si algo no queda como debe, volvemos y lo solucionamos sin coste adicional. Trabajamos para que dure.</p>
      </div>

      <div class="porque-card">
        <div class="porque-icon-wrap">
          <svg class="porque-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="porque-val">24h</div>
        <h3>Urgencias sin horario</h3>
        <p>Fugas, roturas y averías graves no esperan. Atendemos emergencias de fontanería a cualquier hora del día.</p>
      </div>

      <div class="porque-card">
        <div class="porque-icon-wrap">
          <svg class="porque-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
        </div>
        <div class="porque-val">Local</div>
        <h3>Somos de aquí</h3>
        <p>Conocemos las instalaciones del Vinalopó y sus peculiaridades. No somos una central que manda a quien sea.</p>
      </div>

    </div>
  </div>
</section>

<!-- ============================
     TESTIMONIOS — 3 cards
============================= -->
<section class="home-sec" aria-labelledby="testimonios-title">
  <div class="home-con">
    <p class="home-lbl">Testimonios</p>
    <h2 id="testimonios-title">Lo que dicen nuestros <span class="hl">clientes</span></h2>
    <p class="home-sub">Trabajos reales, clientes reales en el Vinalopó.</p>

    <div class="testimonios-grid">

      <div class="testimonio-card">
        <div class="testimonio-estrellas" aria-label="5 de 5 estrellas">
          <svg viewBox="0 0 24 24" fill="#F59E0B"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          <svg viewBox="0 0 24 24" fill="#F59E0B"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          <svg viewBox="0 0 24 24" fill="#F59E0B"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          <svg viewBox="0 0 24 24" fill="#F59E0B"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          <svg viewBox="0 0 24 24" fill="#F59E0B"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        </div>
        <blockquote class="testimonio-texto">"Vinieron a cambiar el termo y dejaron todo perfecto. El precio fue exactamente el del presupuesto, sin sorpresas. Muy recomendables, profesionales de verdad."</blockquote>
        <div class="testimonio-autor">
          <div class="testimonio-avatar">M</div>
          <div>
            <strong>María G.</strong>
            <span>Cliente en Elda</span>
          </div>
        </div>
      </div>

      <div class="testimonio-card">
        <div class="testimonio-estrellas" aria-label="5 de 5 estrellas">
          <svg viewBox="0 0 24 24" fill="#F59E0B"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          <svg viewBox="0 0 24 24" fill="#F59E0B"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          <svg viewBox="0 0 24 24" fill="#F59E0B"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          <svg viewBox="0 0 24 24" fill="#F59E0B"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          <svg viewBox="0 0 24 24" fill="#F59E0B"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        </div>
        <blockquote class="testimonio-texto">"Instalaron una ósmosis en casa y nos explicaron todo el proceso con detalle. Trabajo limpio, bien hecho y el precio se ajustó al presupuesto. Volveré a llamarles."</blockquote>
        <div class="testimonio-autor">
          <div class="testimonio-avatar">J</div>
          <div>
            <strong>Javier M.</strong>
            <span>Cliente en Petrer</span>
          </div>
        </div>
      </div>

      <div class="testimonio-card">
        <div class="testimonio-estrellas" aria-label="5 de 5 estrellas">
          <svg viewBox="0 0 24 24" fill="#F59E0B"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          <svg viewBox="0 0 24 24" fill="#F59E0B"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          <svg viewBox="0 0 24 24" fill="#F59E0B"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          <svg viewBox="0 0 24 24" fill="#F59E0B"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
          <svg viewBox="0 0 24 24" fill="#F59E0B"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        </div>
        <blockquote class="testimonio-texto">"Reforma completa del baño con financiación. Todo muy fácil y sin complicaciones. Acabado perfecto y terminaron en el plazo que dijeron. Los recomiendo sin duda."</blockquote>
        <div class="testimonio-autor">
          <div class="testimonio-avatar">A</div>
          <div>
            <strong>Ana P.</strong>
            <span>Cliente en Novelda</span>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ============================
     CTA FINAL — navy con teléfono
============================= -->
<section class="cta-final" aria-labelledby="cta-title">
  <div class="cta-final-grid"></div>
  <div class="cta-final-glow"></div>
  <div class="home-con cta-final-con">
    <div class="cta-final-badge">
      <span class="cta-final-dot"></span>
      Disponibles ahora
    </div>
    <h2 id="cta-title">Llama y te atendemos <span class="hl">hoy mismo</span></h2>
    <p>Sin colas, sin formularios. Habla directamente con nosotros y te damos solución.</p>

    <div class="cta-final-tel-wrap">
      <a href="tel:+34613429032" class="cta-final-tel">613 429 032</a>
      <span class="cta-final-tel-sep">&middot;</span>
      <a href="tel:+34611165129" class="cta-final-tel cta-final-tel-2">611 165 129</a>
    </div>

    <div class="cta-final-btns">
      <a href="tel:+34613429032" class="hero-btn-primary">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 10.8 19.79 19.79 0 01.38 2.18 2 2 0 012.37 0h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.09 6.09l1.27-.71a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
        Llamar ahora
      </a>
      <a href="https://wa.me/34613429032?text=Hola,%20me%20gustar%C3%ADa%20pedir%20un%20presupuesto" target="_blank" rel="noopener" class="hero-btn-wa">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.117 1.523 5.847L.057 23.882a.5.5 0 00.614.612l6.094-1.596A11.942 11.942 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.894a9.892 9.892 0 01-5.031-1.371l-.361-.214-3.741.98.999-3.648-.235-.374A9.865 9.865 0 012.106 12C2.106 6.533 6.533 2.106 12 2.106S21.894 6.533 21.894 12 17.467 21.894 12 21.894z"/></svg>
        WhatsApp
      </a>
    </div>
  </div>
</section>

<!-- ============================
     ÚLTIMOS PROYECTOS (si hay en BD)
============================= -->
<?php
$ultimos_proyectos = $pdo->query('SELECT titulo, slug, descripcion, servicio, zona, imagen FROM proyectos WHERE publicado = 1 ORDER BY fecha DESC LIMIT 3')->fetchAll();
?>
<?php if (!empty($ultimos_proyectos)): ?>
<section class="home-sec home-sec-gray" aria-labelledby="proyectos-title">
  <div class="home-con">
    <div class="sec-header">
      <div>
        <p class="home-lbl">Proyectos</p>
        <h2 id="proyectos-title">Últimos <span class="hl">trabajos realizados</span></h2>
      </div>
      <a href="<?php echo $base_url; ?>proyectos/" class="sec-link">Ver todos los proyectos &rarr;</a>
    </div>
    <div class="cards-grid">
      <?php foreach ($ultimos_proyectos as $pro): ?>
      <a href="<?php echo $base_url; ?>proyectos/<?php echo urlencode($pro['slug']); ?>" class="content-card">
        <div class="content-card-img">
          <?php if ($pro['imagen']): ?>
            <img src="<?php echo htmlspecialchars($pro['imagen']); ?>" alt="<?php echo htmlspecialchars($pro['titulo']); ?>" loading="lazy">
          <?php else: ?>
            <div class="content-card-placeholder">
              <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/></svg>
            </div>
          <?php endif; ?>
        </div>
        <div class="content-card-body">
          <div class="content-card-tags">
            <?php if ($pro['servicio']): ?><span class="content-tag content-tag-blue"><?php echo htmlspecialchars($pro['servicio']); ?></span><?php endif; ?>
            <?php if ($pro['zona']): ?><span class="content-tag content-tag-gray"><?php echo htmlspecialchars($pro['zona']); ?></span><?php endif; ?>
          </div>
          <h3><?php echo htmlspecialchars($pro['titulo']); ?></h3>
          <p><?php echo htmlspecialchars(mb_strimwidth($pro['descripcion'], 0, 120, '...')); ?></p>
          <span class="content-card-cta">Ver proyecto &rarr;</span>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ============================
     ÚLTIMAS NOTICIAS (si hay en BD)
============================= -->
<?php
$ultimos_articulos = $pdo->query('SELECT titulo, slug, extracto, categoria, zona, imagen FROM articulos WHERE publicado = 1 ORDER BY fecha DESC LIMIT 3')->fetchAll();
?>
<?php if (!empty($ultimos_articulos)): ?>
<section class="home-sec" aria-labelledby="blog-title">
  <div class="home-con">
    <div class="sec-header">
      <div>
        <p class="home-lbl">Blog</p>
        <h2 id="blog-title">Últimas <span class="hl">noticias y consejos</span></h2>
      </div>
      <a href="<?php echo $base_url; ?>blog/" class="sec-link">Ver todo el blog &rarr;</a>
    </div>
    <div class="cards-grid">
      <?php foreach ($ultimos_articulos as $art): ?>
      <a href="<?php echo $base_url; ?>blog/<?php echo urlencode($art['slug']); ?>" class="content-card">
        <div class="content-card-img">
          <?php if ($art['imagen']): ?>
            <img src="<?php echo htmlspecialchars($art['imagen']); ?>" alt="<?php echo htmlspecialchars($art['titulo']); ?>" loading="lazy">
          <?php else: ?>
            <div class="content-card-placeholder">
              <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            </div>
          <?php endif; ?>
        </div>
        <div class="content-card-body">
          <div class="content-card-tags">
            <?php if ($art['categoria']): ?><span class="content-tag content-tag-purple"><?php echo htmlspecialchars($art['categoria']); ?></span><?php endif; ?>
            <?php if ($art['zona']): ?><span class="content-tag content-tag-gray"><?php echo htmlspecialchars($art['zona']); ?></span><?php endif; ?>
          </div>
          <h3><?php echo htmlspecialchars($art['titulo']); ?></h3>
          <p><?php echo htmlspecialchars(mb_strimwidth($art['extracto'], 0, 120, '...')); ?></p>
          <span class="content-card-cta">Leer artículo &rarr;</span>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
