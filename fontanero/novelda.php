<?php
$meta_title  = 'Fontanero en Novelda | Servicio profesional y urgente — CarolTemp';
$meta_desc   = 'Fontanero en Novelda para averías, fugas, desatascos y reformas. Atención rápida, diagnóstico preciso y presupuesto antes de empezar.';
$meta_url    = 'https://caroltemp.com/fontanero/novelda';
$schema_type = 'local';
$page_css    = 'zona';
$page_js     = 'zona';
$depth       = 1;
$faq_items   = [
  ['q' => '¿Cuánto cuesta un fontanero en Novelda?',
   'a' => 'No hay una tarifa única: una reparación rápida de grifería no tiene el mismo precio que cambiar la instalación completa de una comunidad o localizar una fuga oculta. Visitamos, diagnosticamos y te damos el presupuesto gratis antes de tocar nada.'],
  ['q' => '¿Atendéis urgencias en festivos y fines de semana en Novelda?',
   'a' => 'Sí. El servicio de urgencias está disponible todos los días del año, incluidos sábados, domingos y festivos. Hay un recargo por urgencia fuera de horario que se comunica antes de desplazarse.'],
  ['q' => '¿Atendéis comunidades de vecinos en Novelda?',
   'a' => 'Sí. Damos servicio a comunidades de propietarios en Novelda: reparación de bajantes comunes, atascos en arquetas, fugas en tuberías generales y mantenimiento preventivo. Presupuesto específico para comunidades.'],
  ['q' => '¿Vale la pena instalar un descalcificador en Novelda?',
   'a' => 'Sí. El agua en Novelda tiene dureza alta. Un descalcificador bien dimensionado alarga la vida de termos, calderas y electrodomésticos y puede amortizarse en 2-3 años. Te asesoramos sin compromiso sobre el equipo adecuado.'],
  ['q' => '¿Hacéis reformas completas de fontanería en Novelda?',
   'a' => 'Sí. Realizamos la sustitución completa de tuberías de suministro y saneamiento, traslado de puntos de agua y adecuación a normativa vigente. Presupuesto gratuito a domicilio en Novelda.'],
  ['q' => '¿Instaláis sistemas de ósmosis inversa en Novelda?',
   'a' => 'Sí. Instalamos sistemas de ósmosis inversa bajo fregadero para agua de consumo. En Novelda, con agua de alta dureza, el ahorro en agua embotellada amortiza el equipo en poco tiempo. Asesoramiento y montaje sin compromiso.'],
];
include '../includes/head.php';
?>

<?php
$_hi     = getHeroImagen('fontanero-novelda');
$_hi_url = $_hi ? $base_url . $_hi : '';
?>
<section class="hz-dark"<?php if ($_hi_url): ?> style="background-image:url('<?php echo htmlspecialchars($_hi_url); ?>');background-size:cover;background-position:center top;"<?php endif; ?>>
  <?php if ($_hi_url): ?>
    <div class="hz-dark-bg" style="background:rgba(5,15,30,.75)"></div>
  <?php else: ?>
    <div class="hz-dark-bg"></div>
    <div class="hz-dark-glow"></div>
  <?php endif; ?>
  <div class="hz-dark-con">
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Fontanería · Novelda · CP 03660</div>
    <h1>Fontanero en Novelda<br><span class="hl">de confianza para averías, reformas e instalaciones</span></h1>
    <p class="hz-dark-sub">Urgencias, fugas, desatascos y reformas en Novelda. Atendemos viviendas, comunidades y locales.</p>
    <div class="hz-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">📞 611 165 129</a>
      <a href="https://wa.me/34611165129" class="btn-hz-g">WhatsApp</a>
    </div>
  </div>
</section>

<div class="dif-strip">
  <div class="dif-strip-in">
    <div class="dif-item"><span class="dif-val">Presupuesto previo</span><span class="dif-lbl">Sin compromiso</span></div>
    <div class="dif-item"><span class="dif-val">Precio cerrado</span><span class="dif-lbl">Sin sorpresas en la factura</span></div>
    <div class="dif-item"><span class="dif-val">✅ Nubeco oficial</span><span class="dif-lbl">Instaladores certificados</span></div>
    <div class="dif-item"><span class="dif-val">Urgencias 24h</span><span class="dif-lbl">Todos los días</span></div>
  </div>
</div>

<section class="zona-sec zona-sec-dark zona-srv-main">
  <div class="cta-dark-con">
    <p class="zona-lbl">Servicios principales en Novelda</p>
    <h2>Lo que más nos piden <span class="hl">en Novelda</span></h2>
    <div class="zona-srv3" style="margin-top:2rem">

      <a href="/fontanero/novelda/urgencias" class="zona-srv3-card">
        <div class="zona-srv3-ico">🔧</div>
        <h3>Urgencias en Novelda</h3>
        <p>Fontanero urgente en Novelda el mismo día. Roturas, escapes y averías con presupuesto antes de empezar.</p>
        <span class="zona-sc-a">Ver urgencias →</span>
      </a>

      <a href="/fontanero/novelda/busqueda_fugas" class="zona-srv3-card">
        <div class="zona-srv3-ico">💧</div>
        <h3>Detección de fugas en Novelda</h3>
        <p>Localización sin obras con geófono y cámara termográfica. Solo abrimos donde es estrictamente necesario.</p>
        <span class="zona-sc-a">Ver fugas →</span>
      </a>

      <a href="/fontanero/novelda/desatascos" class="zona-srv3-card">
        <div class="zona-srv3-ico">🚿</div>
        <h3>Desatascos en Novelda</h3>
        <p>Bajantes, arquetas e inodoros con diagnóstico por cámara. Atendemos viviendas, comunidades y locales.</p>
        <span class="zona-sc-a">Ver desatascos →</span>
      </a>

    </div>
  </div>
</section>

<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Todos los servicios en Novelda</p>
    <h2>¿Qué necesitas? <span class="hl">Te cubrimos.</span></h2>
    <div class="zona-svc" style="margin-top:2rem">

      <a href="/fontanero/novelda/urgencias" class="zona-sc">
        <span class="zona-sc-n">URGENCIAS</span>
        <h3>Fontanero urgente en Novelda</h3>
        <p>Avería vista, presupuesto dado. Atendemos roturas, escapes y grupos de presión en viviendas, comunidades y locales comerciales.</p>
        <span class="zona-sc-a">Ver urgencias →</span>
      </a>

      <a href="/fontanero/novelda/busqueda_fugas" class="zona-sc">
        <span class="zona-sc-n">FUGAS</span>
        <h3>Detección de fugas en Novelda</h3>
        <p>Geófono y cámara termográfica para localizar la fuga exacta. Sin romper paredes hasta saber dónde está.</p>
        <span class="zona-sc-a">Ver fugas →</span>
      </a>

      <a href="/fontanero/novelda/desatascos" class="zona-sc">
        <span class="zona-sc-n">DESATASCOS</span>
        <h3>Desatascos en Novelda</h3>
        <p>Bajantes, arquetas e inodoros con cámara endoscópica. Diagnóstico antes de actuar.</p>
        <span class="zona-sc-a">Ver desatascos →</span>
      </a>

      <div class="zona-sc">
        <span class="zona-sc-n">TERMOS</span>
        <h3>Termos y calentadores</h3>
        <p>El agua dura del Vinalopó deteriora la resistencia del termo en pocos años. Reparación o sustitución con presupuesto previo.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">REFORMAS</span>
        <h3>Reformas de baño y cocina</h3>
        <p>Reforma completa o parcial de la instalación. Sustitución de tuberías antiguas, traslado de puntos de agua y adecuación a normativa.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">DESCALCIFICADORES</span>
        <h3>Descalcificadores</h3>
        <p>El agua del Vinalopó Medio tiene dureza elevada. Un descalcificador bien dimensionado protege termos, calderas y electrodomésticos.</p>
      </div>

    </div>
  </div>
</section>

<section class="zona-sec zona-sec-alt">
  <div class="cta-dark-con">
    <div class="zona-tcol">
      <div>
        <p class="zona-lbl">Por qué CarolTemp en Novelda</p>
        <h2>Fontanería local <span class="hl">con garantía</span></h2>
        <div class="zona-prose">
          <p>Trabajamos en Novelda atendiendo <a href="/fontanero/novelda/urgencias">urgencias 24h</a>, <a href="/fontanero/novelda/busqueda_fugas">detección de fugas</a> y <a href="/fontanero/novelda/desatascos">desatascos</a> en viviendas, comunidades y locales comerciales. Presupuesto previo y trabajo garantizado.</p>
        </div>
        <ul class="zona-chk">
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Urgencias todos los días, sin horario</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Presupuesto previo antes de empezar cualquier trabajo</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Instaladores Nubeco certificados</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Financiación disponible para reformas e instalaciones</li>
        </ul>
      </div>
      <div>
        <div class="zona-icard">
          <div class="zona-icard-h"><strong>CarolTemp · Novelda</strong><span>Fontanería local</span></div>
          <div class="zona-ir"><span class="zona-ir-l">Zona</span><span class="zona-ir-v">Novelda · CP 03660</span></div>
          <div class="zona-ir"><span class="zona-ir-l">Teléfono</span><span class="zona-ir-v"><a href="tel:+34611165129">611 165 129</a></span></div>
          <div class="zona-ir"><span class="zona-ir-l">WhatsApp</span><span class="zona-ir-v"><a href="https://wa.me/34611165129">Escribir ahora →</a></span></div>
          <div class="zona-ir"><span class="zona-ir-l">Certificación</span><span class="zona-ir-v">Instaladores Nubeco oficiales</span></div>
          <div class="zona-ir"><span class="zona-ir-l">Financiación</span><span class="zona-ir-v">Disponible para proyectos</span></div>
          <a href="tel:+34611165129" class="zona-icard-btn">📞 Llamar ahora</a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Lo que vemos a diario en Novelda</p>
    <h2>Problemas de fontanería <span class="hl">más frecuentes</span></h2>
    <div class="zona-svc" style="margin-top:2rem">

      <div class="zona-sc">
        <span class="zona-sc-n">AGUA DURA</span>
        <h3>Cal e incrustaciones</h3>
        <p>El agua del Vinalopó Medio tiene alta dureza. La cal deteriora termos, calderas y grifería. Un descalcificador o sistema de ósmosis lo resuelve a largo plazo.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">CASCO HISTÓRICO</span>
        <h3>Tuberías antiguas en el centro</h3>
        <p>Las viviendas del centro histórico de Novelda tienen instalaciones con décadas de antigüedad. Sustituimos tuberías viejas por cobre o multicapa con mínima obra.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">COMUNIDADES</span>
        <h3>Fugas y atascos en zonas comunes</h3>
        <p>Bajantes generales, tuberías de fachada y contadores averiados. Reparamos y emitimos informe técnico si lo necesita el seguro de la comunidad.</p>
      </div>

    </div>
  </div>
</section>

<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Cómo trabajamos</p>
    <h2>De la llamada <span class="hl">a la solución</span></h2>
    <div class="zona-proceso">
      <div class="zona-paso">
        <span class="zona-paso-n">01</span>
        <h3>Llamas o escribes</h3>
        <p>Teléfono o WhatsApp. Te atendemos en el momento, sin bots ni centralitas.</p>
      </div>
      <div class="zona-paso">
        <span class="zona-paso-n">02</span>
        <h3>Vemos la avería</h3>
        <p>El técnico acude a Novelda, inspecciona y diagnostica el problema in situ.</p>
      </div>
      <div class="zona-paso">
        <span class="zona-paso-n">03</span>
        <h3>Presupuesto previo</h3>
        <p>Antes de tocar nada te damos el desglose completo: mano de obra y materiales.</p>
      </div>
      <div class="zona-paso">
        <span class="zona-paso-n">04</span>
        <h3>Reparación y garantía</h3>
        <p>Ejecutamos el trabajo y lo dejamos revisado. Con garantía por escrito.</p>
      </div>
    </div>
  </div>
</section>

<?php
$_proy = [];
try {
  $_ps = $pdo->prepare('SELECT titulo, slug, descripcion, servicio, imagen FROM proyectos WHERE publicado=1 AND zona LIKE ? ORDER BY fecha DESC LIMIT 3');
  $_ps->execute(['%Novelda%']);
  $_proy = $_ps->fetchAll(PDO::FETCH_ASSOC);
  if (empty($_proy)) {
    $_ps2 = $pdo->query('SELECT titulo, slug, descripcion, servicio, imagen FROM proyectos WHERE publicado=1 ORDER BY fecha DESC LIMIT 3');
    $_proy = $_ps2 ? $_ps2->fetchAll(PDO::FETCH_ASSOC) : [];
  }
} catch (\Throwable $_e) {}
if (!empty($_proy)): ?>
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Trabajos realizados</p>
    <h2>Proyectos de fontanería <span class="hl">en Novelda</span></h2>
    <div class="zona-svc" style="margin-top:2rem">
      <?php foreach ($_proy as $_p): ?>
      <a href="/proyectos/<?php echo urlencode($_p['slug']); ?>" class="zona-sc">
        <?php if (!empty($_p['imagen'])): ?><img src="<?php echo htmlspecialchars($_p['imagen']); ?>" alt="<?php echo htmlspecialchars($_p['titulo']); ?>" loading="lazy" style="width:100%;height:160px;object-fit:cover;border-radius:8px;margin-bottom:.75rem"><?php endif; ?>
        <?php if (!empty($_p['servicio'])): ?><span class="zona-lbl" style="font-size:11px"><?php echo htmlspecialchars($_p['servicio']); ?></span><?php endif; ?>
        <h3><?php echo htmlspecialchars($_p['titulo']); ?></h3>
        <p><?php echo htmlspecialchars(mb_substr($_p['descripcion'] ?? '', 0, 100)); ?>...</p>
        <span class="zona-sc-a">Ver proyecto →</span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Preguntas frecuentes</p>
    <h2>Fontanería en Novelda <span class="hl">— dudas habituales</span></h2>
    <div class="zona-faqs">
      <details class="zona-faq-item" open>
        <summary>¿Cuánto cuesta un fontanero en Novelda?</summary>
        <div class="faq-ans">No hay una tarifa única: una reparación rápida de grifería no tiene el mismo precio que cambiar la instalación completa de una comunidad o localizar una fuga oculta en Novelda. Visitamos, diagnosticamos y te damos el presupuesto gratis antes de tocar nada.</div>
      </details>
      <details class="zona-faq-item">
        <summary>¿Atendéis urgencias en Novelda los fines de semana y festivos?</summary>
        <div class="faq-ans">Sí, los 365 días del año, también festivos y noches. Somos fontaneros locales, no una central que deriva llamadas. Llama al 611 165 129 y te atendemos al momento.</div>
      </details>
      <details class="zona-faq-item">
        <summary>¿Trabajáis en comunidades de vecinos en Novelda?</summary>
        <div class="faq-ans">Sí. Atendemos comunidades para reparar bajantes, fugas en zonas comunes y tuberías generales. Emitimos informe técnico si la comunidad lo necesita para el seguro.</div>
      </details>
      <details class="zona-faq-item">
        <summary>¿Vale la pena instalar un descalcificador en Novelda?</summary>
        <div class="faq-ans">Sí. El agua del Vinalopó Medio tiene dureza alta y el sarro reduce la vida útil de termos, calderas y electrodomésticos. Os asesoramos sobre el equipo más adecuado sin compromiso.</div>
      </details>
      <details class="zona-faq-item">
        <summary>¿Podéis reformar la instalación completa de fontanería en Novelda?</summary>
        <div class="faq-ans">Sí. Realizamos la sustitución completa de tuberías de suministro y saneamiento, traslado de puntos de agua y montaje de baños y cocinas. Presupuesto previo a domicilio.</div>
      </details>
      <details class="zona-faq-item">
        <summary>¿Instaláis descalcificadores y sistemas de ósmosis en Novelda?</summary>
        <div class="faq-ans">Sí. Instalamos y mantenemos descalcificadores y ósmosis inversa con puesta en marcha incluida. Ideal para proteger la instalación del agua dura del Vinalopó.</div>
      </details>
    </div>
  </div>
</section>

<?php
$_arts = [];
try {
  $_as = $pdo->query('SELECT titulo, slug, extracto, categoria, imagen FROM articulos WHERE publicado=1 ORDER BY fecha DESC LIMIT 3');
  $_arts = $_as ? $_as->fetchAll(PDO::FETCH_ASSOC) : [];
} catch (\Throwable $_e) {}
if (!empty($_arts)): ?>
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Consejos útiles</p>
    <h2>Artículos de fontanería</h2>
    <div class="zona-svc" style="margin-top:2rem">
      <?php foreach ($_arts as $_a): ?>
      <a href="/noticias/<?php echo urlencode($_a['slug']); ?>" class="zona-sc">
        <?php if (!empty($_a['imagen'])): ?><img src="<?php echo htmlspecialchars($_a['imagen']); ?>" alt="<?php echo htmlspecialchars($_a['titulo']); ?>" loading="lazy" style="width:100%;height:160px;object-fit:cover;border-radius:8px;margin-bottom:.75rem"><?php endif; ?>
        <?php if (!empty($_a['categoria'])): ?><span class="zona-lbl" style="font-size:11px"><?php echo htmlspecialchars($_a['categoria']); ?></span><?php endif; ?>
        <h3><?php echo htmlspecialchars($_a['titulo']); ?></h3>
        <p><?php echo htmlspecialchars(mb_substr($_a['extracto'] ?? '', 0, 100)); ?>...</p>
        <span class="zona-sc-a">Leer artículo →</span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Zona de cobertura</p>
    <h2>Fontanería <span class="hl">en Novelda</span></h2>
    <p style="margin-bottom:1rem;color:#576574">Atendemos toda la localidad de Novelda (CP 03660): casco urbano, urbanizaciones y polígono industrial.</p>
    <div class="zona-ztags" style="margin-bottom:1.5rem">
      <span class="zona-ztag-plain">Centro</span>
      <span class="zona-ztag-plain">El Prado</span>
      <span class="zona-ztag-plain">La Magdalena</span>
      <span class="zona-ztag-plain">Polígono Industrial</span>
    </div>
    <div style="border-radius:12px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.12)">
      <iframe src="https://maps.google.com/maps?q=38.3857,-0.7682&z=14&output=embed" width="100%" height="380" style="border:0;display:block" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Fontanero en Novelda"></iframe>
    </div>
  </div>
</section>

<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Otros municipios</p>
    <h2>También trabajamos <span class="hl">cerca de Novelda</span></h2>
    <div class="zona-ztags">
      <a href="/fontanero/elda" class="zona-ztag">Elda</a>
      <a href="/fontanero/aspe" class="zona-ztag">Aspe</a>
      <a href="/fontanero/monforte-del-cid" class="zona-ztag">Monforte del Cid</a>
      <a href="/fontanero/petrer" class="zona-ztag">Petrer</a>
      <a href="/fontanero/pinoso" class="zona-ztag">Pinoso</a>
      <a href="/fontanero/monovar" class="zona-ztag">Monóvar</a>
      <a href="/fontanero/sax" class="zona-ztag">Sax</a>
      <a href="/fontanero/salinas" class="zona-ztag">Salinas</a>
    </div>
  </div>
</section>

<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>¿Necesitas fontanería <span>en Novelda?</span></h2>
    <p>Atendemos toda Novelda. Presupuesto previo, sin sorpresas.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">📞 Llamar ahora</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">💬 WhatsApp</a>
    </div>
  </div>
</section>

<?php
$ciudad = 'novelda';
$servicio = 'fontanero';
include '../includes/resenas-section.php';
?>
<?php include '../includes/galeria-section.php'; ?>
<?php include '../includes/footer.php'; ?>
