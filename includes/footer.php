<footer class="footer" role="contentinfo">
 <div class="footer-mapa">

  <iframe
    src="https://www.openstreetmap.org/export/embed.html?bbox=-0.848468542098999%2C38.43419455969426%2C-0.8439195156097413%2C38.43723679423637&amp;layer=mapnik"
    loading="lazy"
    allowfullscreen>
  </iframe>

</div>
    <div class="footer-mapa">

  <!-- ZONA SUPERIOR -->
  <div class="footer-top">
    <div class="container footer-grid">

      <!-- COLUMNA 1 — Marca -->
      <div class="footer-col footer-brand">
        <a href="/" aria-label="CarolTemp — Inicio">
          <img
            src="<?php echo $base_url; ?>img/logo/logo.svg"
            alt="CarolTemp"
            width="140"
            height="80"
            loading="lazy"
          >
        </a>
        <p>Fontanería industrial y residencial en el Vinalopó. Trabajo bien hecho, sin atajos.</p>
        <a href="tel:+34613429032" class="footer-tel">613 429 032</a>
        <a href="tel:+34611165129" class="footer-tel" style="font-size:13px;opacity:.8">611 165 129</a>
      </div>

      <!-- COLUMNA 2 — Servicios -->
      <div class="footer-col">
        <h3>Servicios</h3>
        <ul>
          <li><a href="/servicios.php#reparaciones">Reparaciones</a></li>
          <li><a href="/servicios.php#termos">Termos Nubeco</a></li>
          <li><a href="/servicios.php#osmosis">Ósmosis inversa</a></li>
          <li><a href="/servicios.php#descalcificadores">Descalcificadores</a></li>
          <li><a href="/servicios.php#reformas">Reformas de baño</a></li>
          <li><a href="/financiacion.php">Financiación</a></li>
        </ul>
      </div>

      <!-- COLUMNA 3 — Zonas -->
      <div class="footer-col">
        <h3>Zonas</h3>
        <ul>
          <li><a href="/zonas/elda.php">Fontanero en Elda</a></li>
          <li><a href="/zonas/petrer.php">Fontanero en Petrer</a></li>
          <li><a href="/zonas/novelda.php">Fontanero en Novelda</a></li>
          <li><a href="/zonas/monovar.php">Fontanero en Monóvar</a></li>
          <li><a href="/zonas/sax.php">Fontanero en Sax</a></li>
          <li><a href="/zonas/pinoso.php">Fontanero en Pinoso</a></li>
          <li><a href="/zonas/monforte.php">Fontanero en Monforte del Cid</a></li>
          <li><a href="/zonas/salinas.php">Fontanero en Salinas</a></li>
        </ul>
      </div>
      
      <!-- COLUMNA 4 — Fugas y Desatascos -->
      <div class="footer-col">
        <h3>Fugas y Desatascos</h3>
        <ul>
          <li><a href="/fugas/">Detección de fugas</a></li>
          <li><a href="/fugas/deteccion-fugas-elda.php">Fugas en Elda</a></li>
          <li><a href="/fugas/deteccion-fugas-petrer.php">Fugas en Petrer</a></li>
          <li><a href="/fugas/deteccion-fugas-novelda.php">Fugas en Novelda</a></li>
          <li><a href="/desatascos/">Desatascos</a></li>
          <li><a href="/desatascos/desatascos-elda.php">Desatascos en Elda</a></li>
          <li><a href="/desatascos/desatascos-petrer.php">Desatascos en Petrer</a></li>
          <li><a href="/desatascos/desatascos-novelda.php">Desatascos en Novelda</a></li>
        </ul>
      </div>

      <!-- COLUMNA 5 — Contacto -->
      <div class="footer-col">
        <h3>Contacto</h3>
        <ul>
          <li>
            <a href="tel:+34613429032">📞 613 429 032</a>
          </li>
          <li>
            <a href="tel:+34611165129">📞 611 165 129</a>
          </li>
          <li>
            <a href="https://wa.me/34613429032" target="_blank" rel="noopener">💬 WhatsApp</a>
          </li>
          <li>
            <a href="mailto:info@caroltemp.es">✉️ info@caroltemp.es</a>
          </li>
        </ul>
        <a href="/contacto.php" class="btn btn-primary footer-cta">
          Pedir presupuesto
        </a>
      </div>

    </div>
  </div>

  <!-- ZONA INFERIOR -->
  <div class="footer-bottom">
    <div class="container footer-bottom-inner">
      <p>&copy; <?php echo date('Y'); ?> CarolTemp — Fontanería industrial y residencial. Todos los derechos reservados.</p>
      <div class="footer-legal">
        <a href="/aviso-legal.php">Aviso legal</a>
        <a href="/privacidad.php">Política de privacidad</a>
        <a href="/cookies.php">Cookies</a>
      </div>
    </div>
  </div>

</footer>

<!-- CSS específico de página -->
<?php if (!empty($page_js)): ?>
  <script src="<?php echo $base_url; ?>js/pages/<?php echo $page_js; ?>.js" defer></script>
<?php endif; ?>

<!-- JS Global -->
<script src="<?php echo $base_url; ?>js/global.js" defer></script>
<script src="<?php echo $base_url; ?>js/cookies.js"></script>
</body>
</html>