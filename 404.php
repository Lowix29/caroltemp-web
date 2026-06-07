<?php
http_response_code(404);
$meta_title  = "Página no encontrada | CarolTemp";
$meta_desc   = "La página que buscas no existe o ha sido movida. Vuelve al inicio o llámanos al 611 165 129 — fontanero 24 horas.";
$meta_url    = "https://caroltemp.com/404.php";
$schema_type = "default";
$page_css    = "404";
$page_js     = "404";

include 'includes/head.php';
?>

<div class="e404">

  <!-- Orb glows de fondo -->
  <div class="e404-orb e404-orb-1" aria-hidden="true"></div>
  <div class="e404-orb e404-orb-2" aria-hidden="true"></div>
  <div class="e404-orb e404-orb-3" aria-hidden="true"></div>

  <!-- Dot grid overlay -->
  <div class="e404-grid" aria-hidden="true"></div>

  <!-- Burbujas flotantes -->
  <div class="e404-bubbles" aria-hidden="true">
    <span></span><span></span><span></span><span></span><span></span>
    <span></span><span></span><span></span><span></span><span></span>
    <span></span><span></span><span></span><span></span><span></span>
  </div>

  <!-- Tubería rota animada -->
  <div class="e404-pipe-wrap" aria-hidden="true">
    <svg class="e404-pipe-svg" viewBox="0 0 520 112" xmlns="http://www.w3.org/2000/svg" role="presentation">
      <defs>
        <linearGradient id="e404pg" x1="0" y1="0" x2="0" y2="1">
          <stop offset="0%"   stop-color="#2a5fa0"/>
          <stop offset="22%"  stop-color="#1565c0"/>
          <stop offset="52%"  stop-color="#1976D2"/>
          <stop offset="82%"  stop-color="#0d3d80"/>
          <stop offset="100%" stop-color="#07204a"/>
        </linearGradient>
        <linearGradient id="e404fg" x1="0" y1="0" x2="0" y2="1">
          <stop offset="0%"   stop-color="#2a5fa0"/>
          <stop offset="100%" stop-color="#081a38"/>
        </linearGradient>
        <radialGradient id="e404bg" cx="50%" cy="50%" r="50%">
          <stop offset="0%"   stop-color="#030d1f"/>
          <stop offset="100%" stop-color="#061628"/>
        </radialGradient>
        <filter id="e404wg" x="-60%" y="-60%" width="220%" height="220%">
          <feGaussianBlur stdDeviation="4" result="blur"/>
          <feMerge><feMergeNode in="blur"/><feMergeNode in="SourceGraphic"/></feMerge>
        </filter>
        <filter id="e404dg" x="-80%" y="-20%" width="260%" height="200%">
          <feGaussianBlur stdDeviation="2.5" result="blur"/>
          <feMerge><feMergeNode in="blur"/><feMergeNode in="SourceGraphic"/></feMerge>
        </filter>
      </defs>

      <g class="e404-pipe-body">
        <!-- Tubo principal -->
        <rect x="32" y="28" width="456" height="55" rx="7" fill="url(#e404pg)"/>
        <!-- Brillo metálico superior -->
        <rect x="38" y="31" width="444" height="10" rx="3" fill="rgba(255,255,255,.13)"/>
        <!-- Sombra inferior -->
        <rect x="32" y="68" width="456" height="15" rx="0" fill="rgba(0,0,0,.28)"/>
        <!-- Bandas de sección (detalle técnico) -->
        <rect x="165" y="28" width="4" height="55" fill="rgba(255,255,255,.07)"/>
        <rect x="351" y="28" width="4" height="55" fill="rgba(255,255,255,.07)"/>

        <!-- Brida izquierda -->
        <rect x="16" y="20" width="30" height="71" rx="6" fill="url(#e404fg)"/>
        <circle cx="31" cy="31" r="5.5" fill="#061628" stroke="#1976D2" stroke-width="1.5"/>
        <circle cx="31" cy="80" r="5.5" fill="#061628" stroke="#1976D2" stroke-width="1.5"/>
        <ellipse cx="17" cy="55" rx="9" ry="23" fill="url(#e404bg)" stroke="#1565c0" stroke-width="1.5"/>
        <ellipse cx="17" cy="55" rx="5" ry="14" fill="#030d1f"/>

        <!-- Brida derecha -->
        <rect x="474" y="20" width="30" height="71" rx="6" fill="url(#e404fg)"/>
        <circle cx="489" cy="31" r="5.5" fill="#061628" stroke="#1976D2" stroke-width="1.5"/>
        <circle cx="489" cy="80" r="5.5" fill="#061628" stroke="#1976D2" stroke-width="1.5"/>
        <ellipse cx="503" cy="55" rx="9" ry="23" fill="url(#e404bg)" stroke="#1565c0" stroke-width="1.5"/>
        <ellipse cx="503" cy="55" rx="5" ry="14" fill="#030d1f"/>

        <!-- Fisura / grieta -->
        <path d="M248 28 L257 42 L263 37 L271 55 L263 59 L270 83 L260 83 L252 60 L244 64 L237 44 Z"
              fill="#061628" stroke="rgba(0,0,0,.55)" stroke-width=".8"/>
        <!-- Agua rezumando (glow estático) -->
        <ellipse cx="254" cy="56" rx="10" ry="5"   fill="rgba(25,118,210,.55)" filter="url(#e404wg)"/>
        <ellipse cx="254" cy="60" rx="7"  ry="3.5" fill="rgba(100,180,255,.65)" filter="url(#e404wg)"/>
      </g>

      <!-- Gotas cayendo (animadas con CSS) -->
      <ellipse class="e404-drip e404-drip-1" cx="249" cy="86" rx="3.5" ry="7"   fill="#1976D2" filter="url(#e404dg)"/>
      <ellipse class="e404-drip e404-drip-2" cx="259" cy="86" rx="2.5" ry="5.5" fill="#64b4ff" filter="url(#e404dg)"/>
      <ellipse class="e404-drip e404-drip-3" cx="254" cy="86" rx="2"   ry="4.5" fill="#1976D2" filter="url(#e404dg)"/>
    </svg>
  </div>

  <!-- Número 404 gigante con relleno de agua -->
  <div class="e404-num-wrap" aria-hidden="true">
    <div class="e404-num-glow"></div>
    <span class="e404-num-base">404</span>
    <div class="e404-num-water-clip">
      <span class="e404-num-fill">404</span>
    </div>
  </div>

  <!-- Contenido -->
  <div class="e404-content">

    <p class="e404-badge">
      <span class="e404-badge-dot" aria-hidden="true"></span>
      Error &middot; Tubería rota
    </p>

    <h1 class="e404-titulo">Esta página se fue por el desagüe</h1>

    <p class="e404-sub">
      La URL que buscas no existe o ha sido movida.<br>
      Pero nosotros seguimos aquí, <strong style="color:rgba(255,255,255,.78)">24 horas</strong>, para lo que necesites.
    </p>

    <div class="e404-btns">
      <a href="<?php echo $base_url; ?>" class="e404-btn-home">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg>
        Volver al inicio
      </a>
      <a href="tel:+34611165129" class="e404-btn-phone">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.72a19.8 19.8 0 01-3.07-8.72A2 2 0 012 .99h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
        611 165 129
      </a>
    </div>

    <nav class="e404-nav" aria-label="Páginas principales">
      <a href="<?php echo $base_url; ?>fontanero/elda">Elda</a>
      <a href="<?php echo $base_url; ?>fontanero/petrer">Petrer</a>
      <a href="<?php echo $base_url; ?>fontanero/novelda">Novelda</a>
      <a href="<?php echo $base_url; ?>fontanero/monovar">Monóvar</a>
      <a href="<?php echo $base_url; ?>fontanero/sax">Sax</a>
      <a href="<?php echo $base_url; ?>fontanero/aspe">Aspe</a>
      <a href="<?php echo $base_url; ?>fontanero/pinoso">Pinoso</a>
      <a href="<?php echo $base_url; ?>fontanero/monforte-del-cid">Monforte</a>
      <a href="<?php echo $base_url; ?>fontanero/salinas">Salinas</a>
      <a href="<?php echo $base_url; ?>noticias/">Noticias</a>
      <a href="<?php echo $base_url; ?>contacto">Contacto</a>
    </nav>

  </div>

  <!-- Charco con ondas -->
  <div class="e404-puddle" aria-hidden="true">
    <div class="e404-ripple"></div>
    <div class="e404-ripple e404-ripple-2"></div>
  </div>

  <!-- Ola canvas (bottom) -->
  <canvas id="e404Canvas" class="e404-canvas" aria-hidden="true"></canvas>

</div>

<?php include 'includes/footer.php'; ?>
