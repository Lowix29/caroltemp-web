<?php
$zona_nombre  = "Pinoso";
$zona_slug    = "pinoso";
$zona_cp      = "03650";

$meta_title   = "Fontanería en Pinoso — Hidrofont";
$meta_desc    = "Servicios de fontanería en Pinoso. Consulta todos los trabajos disponibles: reparaciones, fugas, desatascos, instalaciones, pozos, depósitos y reformas.";
$meta_url     = "https://hidrofont.es/zonas/pinoso";
$schema_type  = "zona";
$page_css     = "zona";
$page_js      = "zona";

include '../includes/head.php';
?>

<!-- HERO -->
<section class="hz-dark">
  <div class="hz-dark-bg"></div>
  <div class="hz-dark-glow"></div>
  <div class="hz-dark-con">
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Servicio en <?php echo $zona_nombre; ?> · CP <?php echo $zona_cp; ?></div>
    <h1>Fontanería en <span class="hl"><?php echo $zona_nombre; ?>.</span></h1>
    <p class="hz-dark-sub">Trabajamos en <?php echo $zona_nombre; ?> realizando todo tipo de servicios de fontanería, especialmente en viviendas rurales, fincas y casas de campo. Accede a cada servicio para ver el detalle completo en tu zona.</p>
    <div class="hz-dark-btns">
      <a href="tel:+34613429032" class="btn-hz-w">📞 613 429 032</a>
      <a href="<?php echo $base_url; ?>contacto" class="btn-hz-g">Solicitar visita</a>
    </div>
    <div class="hero-dark-kpis" style="margin-top:2rem">
      <div class="hero-dark-kpi"><span class="hero-dark-kpi-val">Nubeco</span><span class="hero-dark-kpi-lbl">Instalador oficial en <?php echo $zona_nombre; ?></span></div>
      <div class="hero-dark-kpi"><span class="hero-dark-kpi-val">100%</span><span class="hero-dark-kpi-lbl">Precio cerrado siempre</span></div>
      <div class="hero-dark-kpi"><span class="hero-dark-kpi-val">0€</span><span class="hero-dark-kpi-lbl">Sin adelantos con financiación</span></div>
    </div>
  </div>
</section>

<!-- STRIP -->
<div class="dif-strip">
  <div class="dif-strip-in">
    <div class="dif-item"><span class="dif-val">⚡ Urgencias</span><span class="dif-lbl">Atención rápida en <?php echo $zona_nombre; ?></span></div>
    <div class="dif-item"><span class="dif-val">🔍 Sin obras</span><span class="dif-lbl">Geófono y cámara</span></div>
    <div class="dif-item"><span class="dif-val">💰 Precio cerrado</span><span class="dif-lbl">Antes de empezar</span></div>
    <div class="dif-item"><span class="dif-val">📍 Comarca</span><span class="dif-lbl">Somos de aquí</span></div>
  </div>
</div>

<!-- TEXTO CENTRAL -->
<section class="zona-sec">
  <div class="cta-dark-con">
    <div class="zona-tcol">
      <div>
        <p class="zona-lbl">Fontanería en <?php echo $zona_nombre; ?></p>
        <h2>Servicios de fontanería en <span class="hl"><?php echo $zona_nombre; ?></span></h2>
        <div class="zona-prose">
          <p>Trabajamos en <?php echo $zona_nombre; ?> realizando todo tipo de servicios de fontanería en viviendas, fincas y casas de campo. Es una zona donde predominan las instalaciones independientes, con depósitos, pozos y sistemas de presión propios.</p>
          <p>En esta página puedes acceder a todos los servicios disponibles en <?php echo $zona_nombre; ?> y ver en detalle cómo trabajamos en cada uno de ellos. Desde reparaciones hasta instalaciones completas, siempre con un precio claro antes de empezar.</p>
        </div>
        <ul class="zona-chk">
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Servicios de fontanería en <?php echo $zona_nombre; ?> para viviendas y fincas</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Reparaciones, instalaciones y mantenimiento</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Detección de fugas y desatascos en <?php echo $zona_nombre; ?></li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Instalaciones de pozos, depósitos y grupos de presión</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Soluciones adaptadas a instalaciones rurales</li>
        </ul>
      </div>
      <div>
        <div class="zona-icard">
          <div class="zona-icard-h"><strong>Hidrofont · <?php echo $zona_nombre; ?></strong><span>Fontanería residencial</span></div>
          <div class="zona-ir"><span class="zona-ir-l">Zona</span><span class="zona-ir-v"><?php echo $zona_nombre; ?> · CP <?php echo $zona_cp; ?></span></div>
          <div class="zona-ir"><span class="zona-ir-l">Teléfono</span><span class="zona-ir-v"><a href="tel:+34613429032">613 429 032</a></span></div>
          <div class="zona-ir"><span class="zona-ir-l">WhatsApp</span><span class="zona-ir-v"><a href="https://wa.me/34613429032">Escribir ahora →</a></span></div>
          <div class="zona-ir"><span class="zona-ir-l">Horario</span><span class="zona-ir-v">Lun–Vie 8–20h · Sáb 9–14h</span></div>
          <div class="zona-ir"><span class="zona-ir-l">Financiación</span><span class="zona-ir-v">Disponible para proyectos grandes</span></div>
          <a href="tel:+34613429032" class="zona-icard-btn">📞 Llamar ahora</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- SERVICIOS DISPONIBLES -->
<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Servicios en <?php echo $zona_nombre; ?></p>
    <h2>Servicios disponibles <span class="hl">en <?php echo $zona_nombre; ?></span></h2>
    <p class="zona-sub">Selecciona el servicio que necesitas para ver el detalle completo en <?php echo $zona_nombre; ?>.</p>
    <div class="zona-svc">
      <a href="<?php echo $base_url; ?>fontanero/fontanero-pinoso" class="zona-sc"><span class="zona-sc-n">01</span><h3>Fontanero en <?php echo $zona_nombre; ?></h3><p>Fontanero en <?php echo $zona_nombre; ?> para reparaciones, instalaciones y trabajos habituales en viviendas y fincas.</p><span class="zona-sc-a">Ver servicio →</span></a>
      <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-pinoso" class="zona-sc"><span class="zona-sc-n">02</span><h3>Detección de fugas en <?php echo $zona_nombre; ?></h3><p>Detección de fugas en <?php echo $zona_nombre; ?> con herramientas específicas y sin obras innecesarias.</p><span class="zona-sc-a">Ver servicio →</span></a>
      <a href="<?php echo $base_url; ?>desatascos/desatascos-pinoso" class="zona-sc"><span class="zona-sc-n">03</span><h3>Desatascos en <?php echo $zona_nombre; ?></h3><p>Desatascos en <?php echo $zona_nombre; ?> y limpieza de tuberías en viviendas y fincas.</p><span class="zona-sc-a">Ver servicio →</span></a>
      <a href="<?php echo $base_url; ?>servicios#bombas" class="zona-sc"><span class="zona-sc-n">04</span><h3>Pozos y grupos de presión en <?php echo $zona_nombre; ?></h3><p>Instalación de pozos, depósitos y grupos de presión en <?php echo $zona_nombre; ?> para viviendas rurales.</p><span class="zona-sc-a">Ver servicio →</span></a>
      <a href="<?php echo $base_url; ?>servicios#descalcificadores" class="zona-sc"><span class="zona-sc-n">05</span><h3>Descalcificadores en <?php echo $zona_nombre; ?></h3><p>Descalcificadores en <?php echo $zona_nombre; ?> para proteger la instalación y reducir la cal.</p><span class="zona-sc-a">Ver servicio →</span></a>
      <a href="<?php echo $base_url; ?>servicios#osmosis" class="zona-sc"><span class="zona-sc-n">06</span><h3>Ósmosis inversa en <?php echo $zona_nombre; ?></h3><p>Ósmosis inversa en <?php echo $zona_nombre; ?> para mejorar la calidad del agua en viviendas.</p><span class="zona-sc-a">Ver servicio →</span></a>
      <a href="<?php echo $base_url; ?>servicios#reformas" class="zona-sc"><span class="zona-sc-n">07</span><h3>Reformas de baño en <?php echo $zona_nombre; ?></h3><p>Reformas de baño en <?php echo $zona_nombre; ?> con precio cerrado antes de empezar.</p><span class="zona-sc-a">Ver servicio →</span></a>
    </div>
  </div>
</section>

<!-- FUGAS CON GEÓFONO Y CÁMARA -->
<section class="zona-sec">
  <div class="cta-dark-con">
    <div class="zona-fugas">
      <div class="fg-top">
        <div>
          <p class="fg-lbl">Detección de fugas en <?php echo $zona_nombre; ?></p>
          <h2>Búsqueda de fugas con<br><span>cámara y geófono en <?php echo $zona_nombre; ?></span></h2>
          <p>Localizamos fugas de agua en <?php echo $zona_nombre; ?> sin obras innecesarias. Especialmente útil en viviendas rurales con tuberías enterradas o instalaciones propias.</p>
        </div>
        <div>
          <ul class="fg-chk">
            <li>Detección de fugas de agua en viviendas en <?php echo $zona_nombre; ?></li>
            <li>Fugas de agua urgentes en <?php echo $zona_nombre; ?></li>
            <li>Fugas en tuberías enterradas en <?php echo $zona_nombre; ?></li>
            <li>Fugas en instalaciones rurales en <?php echo $zona_nombre; ?></li>
            <li>Detección de fugas en piscinas en <?php echo $zona_nombre; ?></li>
            <li>Localización de fugas sin obras innecesarias</li>
          </ul>
          <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-pinoso" class="fg-btn">Ver detalle completo →</a>
        </div>
      </div>
      <div class="fg-cards">
        <div class="fg-card">
          <div class="fg-card-img"><div class="fg-card-img-ph"><svg width="28" height="28" viewBox="0 0 24 24" fill="none"><rect x="2" y="4" width="20" height="16" rx="2" stroke="rgba(255,255,255,.5)" stroke-width="1.5"/><circle cx="8" cy="10" r="2" stroke="rgba(255,255,255,.5)" stroke-width="1.5"/><path d="M2 17l5-5 4 4 3-3 5 5" stroke="rgba(255,255,255,.5)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg><span>Foto del geófono</span></div></div>
          <span class="fg-badge">Geófono</span>
          <h4>Detección acústica de fugas</h4>
          <p>El geófono detecta el sonido del agua al escapar por fisuras. Perfecto para tuberías enterradas en fincas y viviendas rurales de <?php echo $zona_nombre; ?>.</p>
        </div>
        <div class="fg-card">
          <div class="fg-card-img"><div class="fg-card-img-ph"><svg width="28" height="28" viewBox="0 0 24 24" fill="none"><rect x="2" y="4" width="20" height="16" rx="2" stroke="rgba(255,255,255,.5)" stroke-width="1.5"/><circle cx="8" cy="10" r="2" stroke="rgba(255,255,255,.5)" stroke-width="1.5"/><path d="M2 17l5-5 4 4 3-3 5 5" stroke="rgba(255,255,255,.5)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg><span>Foto de la cámara</span></div></div>
          <span class="fg-badge">Cámara</span>
          <h4>Inspección visual de tuberías</h4>
          <p>La cámara endoscópica inspecciona el interior de tuberías y bajantes con total precisión antes de actuar.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- IMÁGENES -->
<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Trabajos en <?php echo $zona_nombre; ?></p>
    <h2>Fontanería en <?php echo $zona_nombre; ?> — <span class="hl">proyectos realizados</span></h2>
    <p class="zona-sub">Trabajos reales de fontanería en <?php echo $zona_nombre; ?>.</p>
    <div class="zona-ig">
      <?php
      $imgs = [
        ['src' => '', 'alt' => 'Fontanero en ' . $zona_nombre . ' — trabajo 1'],
        ['src' => '', 'alt' => 'Fontanería en ' . $zona_nombre . ' — trabajo 2'],
        ['src' => '', 'alt' => 'Reparación en ' . $zona_nombre . ' — trabajo 3'],
        ['src' => '', 'alt' => 'Instalación en ' . $zona_nombre . ' — trabajo 4'],
        ['src' => '', 'alt' => 'Reforma de baño en ' . $zona_nombre . ' — trabajo 5'],
        ['src' => '', 'alt' => 'Detección de fugas en ' . $zona_nombre . ' — trabajo 6'],
      ];
      foreach ($imgs as $img):
      ?>
        <div class="zona-ip">
          <?php if ($img['src']): ?>
            <img src="<?php echo $base_url . $img['src']; ?>" alt="<?php echo htmlspecialchars($img['alt']); ?>" loading="lazy">
          <?php else: ?>
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none"><rect x="2" y="4" width="20" height="16" rx="2" stroke="#94a3b8" stroke-width="1.5"/><circle cx="8" cy="10" r="2" stroke="#94a3b8" stroke-width="1.5"/><path d="M2 17l5-5 4 4 3-3 5 5" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <span><?php echo htmlspecialchars($img['alt']); ?></span>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Preguntas frecuentes</p>
    <h2>Fontanería en <?php echo $zona_nombre; ?> — <span class="hl">dudas habituales</span></h2>
    <div class="zona-faq" style="margin-top:2rem">
      <div class="zona-fi open">
        <div class="zona-fiq" onclick="togFaq(this)"><span>¿Cuánto cuesta un servicio de fontanería en <?php echo $zona_nombre; ?>?</span><span class="zona-fiq-i"><svg viewBox="0 0 10 10" fill="none"><path d="M5 1v8M1 5h8" stroke-width="1.5" stroke-linecap="round"/></svg></span></div>
        <div class="zona-fia">En Hidrofont siempre damos precio cerrado antes de empezar. Una reparación sencilla suele resolverse desde 60–80€. Para trabajos mayores el precio se cierra tras ver el trabajo.</div>
      </div>
      <div class="zona-fi">
        <div class="zona-fiq" onclick="togFaq(this)"><span>¿Trabajáis en viviendas rurales y fincas de <?php echo $zona_nombre; ?>?</span><span class="zona-fiq-i"><svg viewBox="0 0 10 10" fill="none"><path d="M5 1v8M1 5h8" stroke-width="1.5" stroke-linecap="round"/></svg></span></div>
        <div class="zona-fia">Sí. Tenemos experiencia en instalaciones rurales de <?php echo $zona_nombre; ?> — depósitos, pozos, grupos de presión y sistemas propios de agua. Cubrimos tanto el núcleo urbano como todas las fincas y viñedos de los alrededores.</div>
      </div>
      <div class="zona-fi">
        <div class="zona-fiq" onclick="togFaq(this)"><span>¿Cómo detectáis fugas sin abrir paredes en <?php echo $zona_nombre; ?>?</span><span class="zona-fiq-i"><svg viewBox="0 0 10 10" fill="none"><path d="M5 1v8M1 5h8" stroke-width="1.5" stroke-linecap="round"/></svg></span></div>
        <div class="zona-fia">Usamos geófono y cámara endoscópica. El geófono detecta el sonido del agua al escapar por fisuras en tuberías enterradas. La cámara inspecciona el interior de tuberías. Localizamos la fuga exacta antes de abrir nada. <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-pinoso">Ver detalle completo →</a></div>
      </div>
      <div class="zona-fi">
        <div class="zona-fiq" onclick="togFaq(this)"><span>¿Hacéis desatascos en <?php echo $zona_nombre; ?>?</span><span class="zona-fiq-i"><svg viewBox="0 0 10 10" fill="none"><path d="M5 1v8M1 5h8" stroke-width="1.5" stroke-linecap="round"/></svg></span></div>
        <div class="zona-fia">Sí. Realizamos desatascos en <?php echo $zona_nombre; ?> de fregaderos, lavabos, bajantes y arquetas. <a href="<?php echo $base_url; ?>desatascos/desatascos-pinoso">Ver detalle completo →</a></div>
      </div>
      <div class="zona-fi">
        <div class="zona-fiq" onclick="togFaq(this)"><span>¿Por qué hay tanta cal en el agua de <?php echo $zona_nombre; ?>?</span><span class="zona-fiq-i"><svg viewBox="0 0 10 10" fill="none"><path d="M5 1v8M1 5h8" stroke-width="1.5" stroke-linecap="round"/></svg></span></div>
        <div class="zona-fia">El agua de <?php echo $zona_nombre; ?> y la comarca tiene una dureza alta que daña electrodomésticos y tuberías. La solución más efectiva es un descalcificador o un sistema de ósmosis inversa.</div>
      </div>
      <div class="zona-fi">
        <div class="zona-fiq" onclick="togFaq(this)"><span>¿Ofrecéis financiación en <?php echo $zona_nombre; ?>?</span><span class="zona-fiq-i"><svg viewBox="0 0 10 10" fill="none"><path d="M5 1v8M1 5h8" stroke-width="1.5" stroke-linecap="round"/></svg></span></div>
        <div class="zona-fia">Sí. Ofrecemos financiación para reformas, ósmosis, descalcificadores y otros trabajos en <?php echo $zona_nombre; ?>. Material y mano de obra sin adelantar nada.</div>
      </div>
    </div>
  </div>
</section>

<!-- ZONAS CERCANAS -->
<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Comarca del Vinalopó</p>
    <h2>También trabajamos en <span class="hl">zonas cercanas</span></h2>
    <div class="zona-ztags">
      <a href="<?php echo $base_url; ?>zonas/elda"     class="zona-ztag">Elda</a>
      <a href="<?php echo $base_url; ?>zonas/petrer"   class="zona-ztag">Petrer</a>
      <a href="<?php echo $base_url; ?>zonas/novelda"  class="zona-ztag">Novelda</a>
      <a href="<?php echo $base_url; ?>zonas/monovar"  class="zona-ztag">Monóvar</a>
      <a href="<?php echo $base_url; ?>zonas/sax"      class="zona-ztag">Sax</a>
      <a href="<?php echo $base_url; ?>zonas/monforte" class="zona-ztag">Monforte del Cid</a>
      <a href="<?php echo $base_url; ?>zonas/salinas"  class="zona-ztag">Salinas</a>
    </div>
  </div>
</section>

<!-- CTA FINAL -->
<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>¿Necesitas fontanería <span>en <?php echo $zona_nombre; ?>?</span></h2>
    <p>Llámanos o escríbenos. Te atendemos hoy.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34613429032" class="btn-hz-w">📞 Llamar ahora</a>
      <a href="https://wa.me/34613429032" target="_blank" rel="noopener" class="btn-hz-g">💬 WhatsApp</a>
    </div>
    <div class="cta-dark-tel">Teléfono directo<strong>613 429 032</strong></div>
  </div>
</section>

<?php include '../includes/footer.php'; ?>