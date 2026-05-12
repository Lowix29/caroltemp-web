<!-- TOPBAR -->
<div class="topbar" role="banner">
  <div class="topbar-inner">
    <span class="topbar-badge">
      <span class="topbar-dot"></span>
      Urgencias 24h
    </span>
    <span class="topbar-text">Fontanería, climatización y fugas en el Vinalopó</span>
    <span class="topbar-sep">·</span>
    <a href="tel:+34613429032" class="topbar-tel">613 429 032</a>
    <span class="topbar-sep">·</span>
    <a href="tel:+34611165129" class="topbar-tel">611 165 129</a>
  </div>
</div>

<!-- NAV PRINCIPAL -->
<header class="nav" role="navigation">
  <div class="nav-inner">

    <!-- LOGO -->
    <a href="<?php echo $base_url; ?>" class="nav-logo" aria-label="CarolTemp — Inicio">
      <img src="<?php echo $base_url; ?>img/logo/logo.svg" alt="CarolTemp — Fontanería y climatización en el Vinalopó" width="140" height="50">
    </a>

    <!-- LINKS CENTRO -->
    <nav class="nav-links" aria-label="Navegación principal">

      <!-- FONTANERÍA (desplegable) -->
      <div class="nav-dropdown">
        <span class="nav-drop-trigger">Fontanería <svg width="10" height="6" viewBox="0 0 10 6" fill="none"><path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        <div class="nav-drop-menu">
          <a href="<?php echo $base_url; ?>fontanero/fontanero-elda">Fontanero en Elda</a>
          <a href="<?php echo $base_url; ?>fontanero/fontanero-petrer">Fontanero en Petrer</a>
          <a href="<?php echo $base_url; ?>fontanero/fontanero-novelda">Fontanero en Novelda</a>
          <a href="<?php echo $base_url; ?>fontanero/fontanero-monovar">Fontanero en Monóvar</a>
          <a href="<?php echo $base_url; ?>fontanero/fontanero-sax">Fontanero en Sax</a>
          <a href="<?php echo $base_url; ?>fontanero/fontanero-pinoso">Fontanero en Pinoso</a>
          <a href="<?php echo $base_url; ?>fontanero/fontanero-monforte">Fontanero en Monforte del Cid</a>
          <a href="<?php echo $base_url; ?>fontanero/fontanero-salinas">Fontanero en Salinas</a>
        </div>
      </div>

      <!-- FUGAS (desplegable) -->
      <div class="nav-dropdown">
        <span class="nav-drop-trigger">Fugas <svg width="10" height="6" viewBox="0 0 10 6" fill="none"><path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        <div class="nav-drop-menu">
          <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-elda">Fugas en Elda</a>
          <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-petrer">Fugas en Petrer</a>
          <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-novelda">Fugas en Novelda</a>
          <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-monovar">Fugas en Monóvar</a>
          <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-sax">Fugas en Sax</a>
          <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-pinoso">Fugas en Pinoso</a>
          <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-monforte">Fugas en Monforte del Cid</a>
          <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-salinas">Fugas en Salinas</a>
        </div>
      </div>

      <!-- DESATASCOS (desplegable) -->
      <div class="nav-dropdown">
        <span class="nav-drop-trigger">Desatascos <svg width="10" height="6" viewBox="0 0 10 6" fill="none"><path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        <div class="nav-drop-menu">
          <a href="<?php echo $base_url; ?>desatascos/desatascos-elda">Desatascos en Elda</a>
          <a href="<?php echo $base_url; ?>desatascos/desatascos-petrer">Desatascos en Petrer</a>
          <a href="<?php echo $base_url; ?>desatascos/desatascos-novelda">Desatascos en Novelda</a>
          <a href="<?php echo $base_url; ?>desatascos/desatascos-monovar">Desatascos en Monóvar</a>
          <a href="<?php echo $base_url; ?>desatascos/desatascos-sax">Desatascos en Sax</a>
          <a href="<?php echo $base_url; ?>desatascos/desatascos-pinoso">Desatascos en Pinoso</a>
          <a href="<?php echo $base_url; ?>desatascos/desatascos-monforte">Desatascos en Monforte del Cid</a>
          <a href="<?php echo $base_url; ?>desatascos/desatascos-salinas">Desatascos en Salinas</a>
        </div>
      </div>

      <a href="<?php echo $base_url; ?>servicios">Servicios</a>
      <a href="<?php echo $base_url; ?>zonas">Zonas</a>
      <a href="<?php echo $base_url; ?>blog/">Blog</a>
    </nav>

    <!-- DERECHA DESKTOP -->
    <div class="nav-right">
      <a href="tel:+34613429032" class="nav-tel">613 429 032</a>
      <a href="<?php echo $base_url; ?>contacto" class="nav-cta">Presupuesto gratis</a>
    </div>

    <!-- HAMBURGUESA MÓVIL -->
    <button class="nav-toggle" aria-label="Abrir menú" aria-expanded="false" aria-controls="nav-mobile" onclick="toggleMenu(this)">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>

<!-- OVERLAY OSCURO MÓVIL -->
<div class="nav-overlay" id="nav-overlay" onclick="closeNavMobile()"></div>

<!-- MENÚ MÓVIL -->
<div class="nav-mobile" id="nav-mobile" role="dialog" aria-label="Menú de navegación" aria-modal="true">

  <!-- Cabecera -->
  <div class="nav-mobile-header">
    <a href="<?php echo $base_url; ?>" onclick="closeNavMobile()">
      <img src="<?php echo $base_url; ?>img/logo/logo.svg" alt="CarolTemp" height="40">
    </a>
    <button class="nav-mobile-close" onclick="closeNavMobile()" aria-label="Cerrar menú">&#x2715;</button>
  </div>

  <!-- Cuerpo -->
  <div class="nav-mobile-body">

    <!-- FONTANERÍA MÓVIL -->
    <div class="nav-mobile-group">
      <a href="#" class="nav-mobile-toggle" onclick="toggleMobileGroup(event, this)">
        Fontanería
        <svg width="12" height="7" viewBox="0 0 10 6" fill="none"><path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
      <div class="nav-mobile-sub">
        <a href="<?php echo $base_url; ?>fontanero/fontanero-elda">Fontanero en Elda</a>
        <a href="<?php echo $base_url; ?>fontanero/fontanero-petrer">Fontanero en Petrer</a>
        <a href="<?php echo $base_url; ?>fontanero/fontanero-novelda">Fontanero en Novelda</a>
        <a href="<?php echo $base_url; ?>fontanero/fontanero-monovar">Fontanero en Monóvar</a>
        <a href="<?php echo $base_url; ?>fontanero/fontanero-sax">Fontanero en Sax</a>
        <a href="<?php echo $base_url; ?>fontanero/fontanero-pinoso">Fontanero en Pinoso</a>
        <a href="<?php echo $base_url; ?>fontanero/fontanero-monforte">Fontanero en Monforte del Cid</a>
        <a href="<?php echo $base_url; ?>fontanero/fontanero-salinas">Fontanero en Salinas</a>
      </div>
    </div>

    <!-- FUGAS MÓVIL -->
    <div class="nav-mobile-group">
      <a href="#" class="nav-mobile-toggle" onclick="toggleMobileGroup(event, this)">
        Fugas de agua
        <svg width="12" height="7" viewBox="0 0 10 6" fill="none"><path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
      <div class="nav-mobile-sub">
        <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-elda">Fugas en Elda</a>
        <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-petrer">Fugas en Petrer</a>
        <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-novelda">Fugas en Novelda</a>
        <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-monovar">Fugas en Monóvar</a>
        <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-sax">Fugas en Sax</a>
        <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-pinoso">Fugas en Pinoso</a>
        <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-monforte">Fugas en Monforte del Cid</a>
        <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-salinas">Fugas en Salinas</a>
      </div>
    </div>

    <!-- DESATASCOS MÓVIL -->
    <div class="nav-mobile-group">
      <a href="#" class="nav-mobile-toggle" onclick="toggleMobileGroup(event, this)">
        Desatascos
        <svg width="12" height="7" viewBox="0 0 10 6" fill="none"><path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
      <div class="nav-mobile-sub">
        <a href="<?php echo $base_url; ?>desatascos/desatascos-elda">Desatascos en Elda</a>
        <a href="<?php echo $base_url; ?>desatascos/desatascos-petrer">Desatascos en Petrer</a>
        <a href="<?php echo $base_url; ?>desatascos/desatascos-novelda">Desatascos en Novelda</a>
        <a href="<?php echo $base_url; ?>desatascos/desatascos-monovar">Desatascos en Monóvar</a>
        <a href="<?php echo $base_url; ?>desatascos/desatascos-sax">Desatascos en Sax</a>
        <a href="<?php echo $base_url; ?>desatascos/desatascos-pinoso">Desatascos en Pinoso</a>
        <a href="<?php echo $base_url; ?>desatascos/desatascos-monforte">Desatascos en Monforte del Cid</a>
        <a href="<?php echo $base_url; ?>desatascos/desatascos-salinas">Desatascos en Salinas</a>
      </div>
    </div>

    <a href="<?php echo $base_url; ?>servicios"  class="nav-mobile-link">Servicios</a>
    <a href="<?php echo $base_url; ?>zonas"       class="nav-mobile-link">Zonas</a>
    <a href="<?php echo $base_url; ?>proyectos/"  class="nav-mobile-link">Proyectos</a>
    <a href="<?php echo $base_url; ?>blog/"        class="nav-mobile-link">Blog</a>
    <a href="<?php echo $base_url; ?>contacto"    class="nav-mobile-link">Contacto</a>

  </div><!-- /nav-mobile-body -->

  <!-- CTAs fijos al fondo -->
  <div class="nav-mobile-ctas">
    <a href="tel:+34613429032" class="nav-mobile-call">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 10.8 19.79 19.79 0 01.38 2.18 2 2 0 012.37 0h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.09 6.09l1.27-.71a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
      613 429 032
    </a>
    <a href="https://wa.me/34613429032?text=Hola,%20me%20gustar%C3%ADa%20pedir%20un%20presupuesto" target="_blank" rel="noopener" class="nav-mobile-wa">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.117 1.523 5.847L.057 23.882a.5.5 0 00.614.612l6.094-1.596A11.942 11.942 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.894a9.892 9.892 0 01-5.031-1.371l-.361-.214-3.741.98.999-3.648-.235-.374A9.865 9.865 0 012.106 12C2.106 6.533 6.533 2.106 12 2.106S21.894 6.533 21.894 12 17.467 21.894 12 21.894z"/></svg>
      WhatsApp
    </a>
    <a href="<?php echo $base_url; ?>contacto" class="nav-mobile-presupuesto">Solicitar presupuesto gratis</a>
  </div>

</div>

<script>
function closeNavMobile() {
  const menu    = document.getElementById('nav-mobile');
  const overlay = document.getElementById('nav-overlay');
  const toggle  = document.querySelector('.nav-toggle');
  menu.classList.remove('open');
  overlay.classList.remove('open');
  if (toggle) {
    toggle.setAttribute('aria-expanded', 'false');
    toggle.setAttribute('aria-label', 'Abrir menú');
    toggle.querySelectorAll('span').forEach(s => { s.style.transform = ''; s.style.opacity = ''; });
    toggle.classList.remove('open');
  }
}
</script>
