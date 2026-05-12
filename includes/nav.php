<!-- TOPBAR -->
<div class="topbar">
  <p>Financiación disponible — <strong>Toda la instalación sin coste adicional</strong> <a href="<?php echo $base_url; ?>financiacion">Más info →</a></p>
</div>

<!-- NAV PRINCIPAL -->
<header class="nav" role="banner">
  <div class="nav-inner">

    <!-- LOGO -->
    <a href="<?php echo $base_url; ?>" class="nav-logo" aria-label="CarolTemp — Inicio">
      <img src="<?php echo $base_url; ?>img/logo/logo.svg" alt="CarolTemp — Fontanería residencial" >
    </a>

    <!-- LINKS CENTRO -->
    <nav class="nav-links" aria-label="Navegación principal">

      <!-- FONTANERÍA (desplegable) -->
      <div class="nav-dropdown">
        <a class="nav-drop-trigger">Fontanería <svg width="10" height="6" viewBox="0 0 10 6" fill="none"><path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
        <div class="nav-drop-menu">
          <a href="<?php echo $base_url; ?>fontanero/fontanero-elda">Fontanero en Elda</a>
          <a href="<?php echo $base_url; ?>fontanero/fontanero-petrer">Fontanero en Petrer</a>
          <a href="<?php echo $base_url; ?>fontanero/fontanero-novelda">Fontanero en Novelda</a>
          <a href="<?php echo $base_url; ?>fontanero/fontanero-monovar">Fontanero en Monóvar</a>
          <a href="<?php echo $base_url; ?>fontanero/fontanero-sax">Fontanero en Sax</a>
          <a href="<?php echo $base_url; ?>fontanero/fontanero-pinoso">Fontanero en Pinoso</a>
          <a href="<?php echo $base_url; ?>fontanero/fontanero-monforte">Fontanero en Monforte</a>
          <a href="<?php echo $base_url; ?>fontanero/fontanero-salinas">Fontanero en Salinas</a>
        </div>
      </div>

      <!-- FUGAS (desplegable) -->
      <div class="nav-dropdown">
        <a class="nav-drop-trigger">Fugas <svg width="10" height="6" viewBox="0 0 10 6" fill="none"><path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
        <div class="nav-drop-menu">
          <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-elda">Fugas en Elda</a>
          <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-petrer">Fugas en Petrer</a>
          <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-novelda">Fugas en Novelda</a>
          <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-monovar">Fugas en Monóvar</a>
          <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-sax">Fugas en Sax</a>
          <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-pinoso">Fugas en Pinoso</a>
          <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-monforte">Fugas en Monforte</a>
          <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-salinas">Fugas en Salinas</a>
        </div>
      </div>

      <!-- DESATASCOS (desplegable) -->
      <div class="nav-dropdown">
        <a class="nav-drop-trigger">Desatascos <svg width="10" height="6" viewBox="0 0 10 6" fill="none"><path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
        <div class="nav-drop-menu">
          <a href="<?php echo $base_url; ?>desatascos/desatascos-elda">Desatascos en Elda</a>
          <a href="<?php echo $base_url; ?>desatascos/desatascos-petrer">Desatascos en Petrer</a>
          <a href="<?php echo $base_url; ?>desatascos/desatascos-novelda">Desatascos en Novelda</a>
          <a href="<?php echo $base_url; ?>desatascos/desatascos-monovar">Desatascos en Monóvar</a>
          <a href="<?php echo $base_url; ?>desatascos/desatascos-sax">Desatascos en Sax</a>
          <a href="<?php echo $base_url; ?>desatascos/desatascos-pinoso">Desatascos en Pinoso</a>
          <a href="<?php echo $base_url; ?>desatascos/desatascos-monforte">Desatascos en Monforte</a>
          <a href="<?php echo $base_url; ?>desatascos/desatascos-salinas">Desatascos en Salinas</a>
        </div>
      </div>

      <a href="<?php echo $base_url; ?>servicios">Servicios</a>
      <a href="<?php echo $base_url; ?>zonas">Zonas</a>
      <a href="<?php echo $base_url; ?>proyectos/">Proyectos</a>
      <a href="<?php echo $base_url; ?>blog/">Noticias</a>
    </nav>

    <!-- DERECHA -->
    <div class="nav-right">
      <a href="<?php echo $base_url; ?>contacto" class="nav-cta">Pedir presupuesto</a>
    </div>

    <!-- HAMBURGUESA MÓVIL -->
    <button class="nav-toggle" aria-label="Abrir menú" aria-expanded="false" aria-controls="nav-mobile" onclick="toggleMenu(this)">
      <span></span><span></span><span></span>
    </button>
  </div>

  <!-- MENÚ MÓVIL -->
  <div class="nav-mobile" id="nav-mobile" role="navigation" aria-label="Menú móvil">

    <!-- FONTANERÍA MÓVIL -->
    <div class="nav-mobile-group">
      <a href="#" class="nav-mobile-toggle" onclick="toggleMobileGroup(event, this)">Fontanería <svg width="10" height="6" viewBox="0 0 10 6" fill="none"><path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
      <div class="nav-mobile-sub">
        <a href="<?php echo $base_url; ?>fontanero/fontanero-elda">Fontanero en Elda</a>
        <a href="<?php echo $base_url; ?>fontanero/fontanero-petrer">Fontanero en Petrer</a>
        <a href="<?php echo $base_url; ?>fontanero/fontanero-novelda">Fontanero en Novelda</a>
        <a href="<?php echo $base_url; ?>fontanero/fontanero-monovar">Fontanero en Monóvar</a>
        <a href="<?php echo $base_url; ?>fontanero/fontanero-sax">Fontanero en Sax</a>
        <a href="<?php echo $base_url; ?>fontanero/fontanero-pinoso">Fontanero en Pinoso</a>
        <a href="<?php echo $base_url; ?>fontanero/fontanero-monforte">Fontanero en Monforte</a>
        <a href="<?php echo $base_url; ?>fontanero/fontanero-salinas">Fontanero en Salinas</a>
      </div>
    </div>

    <!-- FUGAS MÓVIL -->
    <div class="nav-mobile-group">
      <a href="#" class="nav-mobile-toggle" onclick="toggleMobileGroup(event, this)">Fugas <svg width="10" height="6" viewBox="0 0 10 6" fill="none"><path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
      <div class="nav-mobile-sub">
        <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-elda">Fugas en Elda</a>
        <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-petrer">Fugas en Petrer</a>
        <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-novelda">Fugas en Novelda</a>
        <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-monovar">Fugas en Monóvar</a>
        <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-sax">Fugas en Sax</a>
        <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-pinoso">Fugas en Pinoso</a>
        <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-monforte">Fugas en Monforte</a>
        <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-salinas">Fugas en Salinas</a>
      </div>
    </div>

    <!-- DESATASCOS MÓVIL -->
    <div class="nav-mobile-group">
      <a href="#" class="nav-mobile-toggle" onclick="toggleMobileGroup(event, this)">Desatascos <svg width="10" height="6" viewBox="0 0 10 6" fill="none"><path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
      <div class="nav-mobile-sub">
        <a href="<?php echo $base_url; ?>desatascos/desatascos-elda">Desatascos en Elda</a>
        <a href="<?php echo $base_url; ?>desatascos/desatascos-petrer">Desatascos en Petrer</a>
        <a href="<?php echo $base_url; ?>desatascos/desatascos-novelda">Desatascos en Novelda</a>
        <a href="<?php echo $base_url; ?>desatascos/desatascos-monovar">Desatascos en Monóvar</a>
        <a href="<?php echo $base_url; ?>desatascos/desatascos-sax">Desatascos en Sax</a>
        <a href="<?php echo $base_url; ?>desatascos/desatascos-pinoso">Desatascos en Pinoso</a>
        <a href="<?php echo $base_url; ?>desatascos/desatascos-monforte">Desatascos en Monforte</a>
        <a href="<?php echo $base_url; ?>desatascos/desatascos-salinas">Desatascos en Salinas</a>
      </div>
    </div>

    <a href="<?php echo $base_url; ?>servicios">Servicios</a>
    <a href="<?php echo $base_url; ?>zonas">Zonas</a>
    <a href="<?php echo $base_url; ?>proyectos/">Proyectos</a>
    <a href="<?php echo $base_url; ?>blog/">Noticias</a>
    <a href="<?php echo $base_url; ?>contacto">Contacto</a>
    <a href="tel:+34613429032" class="nav-tel">613 429 032</a>
    <a href="<?php echo $base_url; ?>contacto" class="nav-cta">Pedir presupuesto</a>
  </div>
</header>