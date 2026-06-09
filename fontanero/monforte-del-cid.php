<?php
/**
 * Fontanero en Monforte del Cid — Hub
 * CarolTemp
 */
$meta_title  = 'Fontanero en Monforte del Cid | Urgencias, fugas y desatascos — CarolTemp';
$meta_desc   = 'Fontanero en Monforte del Cid para urgencias 24h, detección de fugas, desatascos y reformas. Presupuesto gratuito, instaladores Nubeco certificados.';
$meta_url    = 'https://caroltemp.com/fontanero/monforte-del-cid';
$schema_type = 'local';
$page_css    = 'zona';
$page_js     = 'zona';
$depth       = 1;
$faq_items   = [
  ['q' => '¿Cuánto cuesta un fontanero en Monforte del Cid?',
   'a' => 'No es lo mismo reparar un grifo en el casco que sustituir la instalación completa de una comunidad de vecinos. Visitamos, diagnosticamos y damos el presupuesto gratis antes de tocar nada. Sin compromiso.'],
  ['q' => '¿Atendéis urgencias en festivos y fines de semana en Monforte del Cid?',
   'a' => 'Sí. El servicio de urgencias está disponible todos los días del año, incluidos sábados, domingos y festivos. Hay un recargo por urgencia fuera de horario que se comunica antes de desplazarse. Llama al 611 165 129 o escribe por WhatsApp.'],
  ['q' => '¿Atendéis comunidades de vecinos en Monforte del Cid?',
   'a' => 'Sí. Damos servicio a comunidades de propietarios: reparación de bajantes comunes, fugas en tuberías generales, atascos en arquetas y mantenimiento preventivo de la instalación. Emitimos informe técnico si la comunidad lo necesita para el seguro.'],
  ['q' => '¿Vale la pena instalar un descalcificador en Monforte del Cid?',
   'a' => 'Sí. El agua del Vinalopó tiene dureza alta y el sarro reduce la vida útil de termos, calderas y electrodomésticos. Un descalcificador bien dimensionado puede amortizarse en 2-3 años en ahorro de averías y consumo energético. Asesoramos sin compromiso.'],
  ['q' => '¿Podéis hacer la reforma completa de fontanería de mi vivienda en Monforte del Cid?',
   'a' => 'Sí. Realizamos la sustitución completa de tuberías de suministro y saneamiento, traslado de puntos de agua, adecuación de instalaciones antiguas a normativa y montaje de baños y cocinas. Presupuesto gratuito a domicilio en Monforte del Cid.'],
  ['q' => '¿Cuánto tarda en llegar un fontanero a Monforte del Cid?',
   'a' => 'En urgencias reales —escape de agua, inundación, corte total de suministro— intentamos llegar en el menor tiempo posible. Al llamar te confirmamos la disponibilidad y el tiempo de llegada estimado. Para trabajos programados concertamos la visita en el horario que mejor te venga.'],
];
include '../includes/head.php';
?>

<?php
$_hi     = getHeroImagen('fontanero-monforte-del-cid');
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
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Fontanería &middot; Monforte del Cid &middot; CP 03670</div>
    <h1>Fontanero en Monforte del Cid<br><span class="hl">para reparaciones urgentes y trabajos impecables</span></h1>
    <p class="hz-dark-sub">Urgencias, fugas, desatascos y reformas en Monforte del Cid. Atendemos particulares, comunidades de vecinos y negocios locales.</p>
    <div class="hz-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; 611 165 129</a>
      <a href="https://wa.me/34611165129" class="btn-hz-g">WhatsApp</a>
    </div>
  </div>
</section>

<div class="dif-strip">
  <div class="dif-strip-in">
    <div class="dif-item"><span class="dif-val">Presupuesto gratuito</span><span class="dif-lbl">Sin compromiso</span></div>
    <div class="dif-item"><span class="dif-val">Precio cerrado antes de empezar</span><span class="dif-lbl">Sin sorpresas en la factura</span></div>
    <div class="dif-item"><span class="dif-val">&#9989; Nubeco oficial</span><span class="dif-lbl">Instaladores certificados</span></div>
    <div class="dif-item"><span class="dif-val">Urgencias 24h</span><span class="dif-lbl">Fines de semana y festivos</span></div>
  </div>
</div>

<!-- Servicios principales enlazados -->
<section class="zona-sec-dark">
  <div class="cta-dark-con">
    <p class="zona-lbl">Servicios de fontanería en Monforte del Cid</p>
    <h2>¿Qué necesitas? <span class="hl">Te cubrimos.</span></h2>
    <p style="color:#fff;max-width:640px;margin:1rem 0 2rem">
      Atendemos <a href="/fontanero/monforte-del-cid/urgencias" style="color:#fff;text-decoration:underline;text-underline-offset:3px">urgencias 24h</a>,
      <a href="/fontanero/monforte-del-cid/busqueda-fugas" style="color:#fff;text-decoration:underline;text-underline-offset:3px">detección de fugas</a>
      y <a href="/fontanero/monforte-del-cid/desatascos" style="color:#fff;text-decoration:underline;text-underline-offset:3px">desatascos</a>
      en todo Monforte del Cid. Presupuesto previo antes de tocar nada.
    </p>
    <div class="zona-srv3">
      <a href="/fontanero/monforte-del-cid/urgencias" class="zona-srv3-card">
        <h3>Urgencias 24h</h3>
        <p>Roturas, escapes y averías que no pueden esperar. Llegamos a Monforte del Cid y damos precio antes de empezar.</p>
        <span style="font-size:.85rem;color:#7eb3e8;margin-top:auto">Ver urgencias &rarr;</span>
      </a>
      <a href="/fontanero/monforte-del-cid/busqueda-fugas" class="zona-srv3-card">
        <h3>Detección de fugas</h3>
        <p>Localizamos fugas ocultas en viviendas y comunidades sin romper paredes. Geófono y cámara termográfica para marcar el punto exacto.</p>
        <span style="font-size:.85rem;color:#7eb3e8;margin-top:auto">Ver fugas &rarr;</span>
      </a>
      <a href="/fontanero/monforte-del-cid/desatascos" class="zona-srv3-card">
        <h3>Desatascos</h3>
        <p>Bajantes, arquetas e inodoros con cámara endoscópica. El agua dura del Vinalopó agrava las incrustaciones — diagnóstico antes de actuar.</p>
        <span style="font-size:.85rem;color:#7eb3e8;margin-top:auto">Ver desatascos &rarr;</span>
      </a>
    </div>
  </div>
</section>

<!-- Todos los servicios -->
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Todo lo que hacemos en Monforte del Cid</p>
    <h2>Fontanería completa <span class="hl">para Monforte del Cid</span></h2>
    <div class="zona-svc" style="margin-top:2rem">

      <div class="zona-sc">
        <span class="zona-sc-n">TERMOS</span>
        <h3>Termos y calentadores</h3>
        <p>El agua dura del Vinalopó destruye la resistencia del termo antes de lo previsto. Reparación o sustitución con presupuesto previo y sin visita extra.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">DESCALCIFICADORES</span>
        <h3>Descalcificadores</h3>
        <p>Protege termos, calderas y electrodomésticos del sarro. Asesoramiento y montaje en Monforte del Cid según el consumo y el tipo de instalación.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">CALDERAS</span>
        <h3>Calderas y calefacción</h3>
        <p>Instalación, revisión anual obligatoria y reparación de calderas de gas. Mantenimiento preventivo antes del invierno para garantizar el suministro.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">REFORMAS</span>
        <h3>Reformas de baño y cocina</h3>
        <p>Sustitución completa de tuberías, traslado de puntos de agua, instalación de sanitarios y adecuación a normativa. Presupuesto a domicilio.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">GRIFERÍA</span>
        <h3>Grifería y sanitarios</h3>
        <p>Cambio de grifo, inodoro, plato de ducha o bañera. Presupuesto rápido a domicilio en todo Monforte del Cid.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">COMUNIDADES</span>
        <h3>Comunidades de vecinos</h3>
        <p>Reparación de bajantes comunes, fugas en tuberías generales, atascos en arquetas y mantenimiento preventivo. Informe técnico para seguros.</p>
      </div>

    </div>
  </div>
</section>

<!-- Por qué CarolTemp -->
<section class="zona-sec zona-sec-alt">
  <div class="cta-dark-con">
    <div class="zona-tcol">
      <div>
        <p class="zona-lbl">Por qué elegirnos en Monforte del Cid</p>
        <h2>Fontanería local <span class="hl">con garantía de trabajo</span></h2>
        <div class="zona-prose">
          <p>Monforte del Cid es un municipio pequeño donde el servicio de fontanería municipal tiene sus límites y los tiempos de espera pueden ser largos en una avería real. Muchos vecinos optan por fontanero privado precisamente por eso: llegada rápida, presupuesto claro antes de empezar y sin depender de turnos ni listas. En CarolTemp damos precio antes de tocar nada — sin letra pequeña, sin llamadas posteriores para añadir coste.</p>
          <p>El agua del Vinalopó tiene dureza alta. El sarro deteriora termos, calentadores y electrodomésticos antes de lo que debería y agrava los atascos con incrustaciones en las paredes de la tubería. En el casco antiguo, muchas viviendas conservan instalaciones que no se han sustituido en décadas. Conocer bien estos problemas nos permite acudir con el material adecuado desde la primera visita.</p>
        </div>
        <ul class="zona-chk">
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Agua dura del Vinalopó: termos y calderas que duran menos</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Instalaciones antiguas en el casco urbano sin renovar</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Problemas de presión en urbanizaciones elevadas</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Instaladores Nubeco certificados, con seguro de responsabilidad civil</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Presupuesto previo por escrito — sin sorpresas en la factura</li>
        </ul>
      </div>
      <div>
        <div class="zona-icard">
          <div class="zona-icard-h"><strong>CarolTemp &middot; Monforte del Cid</strong><span>Fontanería local</span></div>
          <div class="zona-ir"><span class="zona-ir-l">Zona</span><span class="zona-ir-v">Monforte del Cid &middot; CP 03670</span></div>
          <div class="zona-ir"><span class="zona-ir-l">Teléfono</span><span class="zona-ir-v"><a href="tel:+34611165129">611 165 129</a></span></div>
          <div class="zona-ir"><span class="zona-ir-l">WhatsApp</span><span class="zona-ir-v"><a href="https://wa.me/34611165129">Escribir ahora &rarr;</a></span></div>
          <div class="zona-ir"><span class="zona-ir-l">Certificación</span><span class="zona-ir-v">Instaladores Nubeco oficiales</span></div>
          <div class="zona-ir"><span class="zona-ir-l">Cobertura</span><span class="zona-ir-v">Casco urbano, urbanizaciones y polígono</span></div>
          <div class="zona-ir"><span class="zona-ir-l">Financiación</span><span class="zona-ir-v">Consulta condiciones</span></div>
          <a href="tel:+34611165129" class="zona-icard-btn">&#128222; Llamar ahora</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Proyectos -->
<?php
$_proy = [];
try {
  $_ps = $pdo->prepare('SELECT titulo, slug, descripcion, servicio, imagen FROM proyectos WHERE publicado=1 AND zona LIKE ? ORDER BY fecha DESC LIMIT 3');
  $_ps->execute(['%Monforte%']);
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
    <h2>Proyectos de fontaner&iacute;a <span class="hl">en Monforte del Cid</span></h2>
    <div class="zona-svc" style="margin-top:2rem">
      <?php foreach ($_proy as $_p): ?>
      <a href="/proyectos/<?php echo urlencode($_p['slug']); ?>" class="zona-sc">
        <?php if (!empty($_p['imagen'])): ?><img src="<?php echo htmlspecialchars($_p['imagen']); ?>" alt="<?php echo htmlspecialchars($_p['titulo']); ?>" loading="lazy" style="width:100%;height:160px;object-fit:cover;border-radius:8px;margin-bottom:.75rem"><?php endif; ?>
        <?php if (!empty($_p['servicio'])): ?><span class="zona-lbl" style="font-size:11px"><?php echo htmlspecialchars($_p['servicio']); ?></span><?php endif; ?>
        <h3><?php echo htmlspecialchars($_p['titulo']); ?></h3>
        <p><?php echo htmlspecialchars(mb_substr($_p['descripcion'] ?? '', 0, 100)); ?>...</p>
        <span class="zona-sc-a">Ver proyecto &rarr;</span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Problemas frecuentes -->
<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Lo que más vemos en Monforte del Cid</p>
    <h2>Averías frecuentes <span class="hl">de fontanería en Monforte del Cid</span></h2>
    <div class="zona-svc" style="margin-top:2rem">

      <div class="zona-sc">
        <span class="zona-sc-n">AGUA DURA</span>
        <h3>Cal en tuberías, termos y calderas</h3>
        <p>El agua del Vinalopó tiene dureza alta que acumula sarro en resistencias, interiores de caldera y tuberías de pequeño diámetro. Lo que en zonas con agua blanda dura 15 años, aquí puede requerir atención a los 8-10. Un descalcificador bien dimensionado alarga significativamente la vida de toda la instalación.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">PRESIÓN</span>
        <h3>Problemas de presión en urbanizaciones</h3>
        <p>Las urbanizaciones elevadas de Monforte del Cid pueden tener presión insuficiente en las plantas altas, especialmente en verano cuando el consumo aumenta. La instalación o revisión de un grupo de presión resuelve el problema de forma definitiva y sin afectar al resto de la instalación.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">CASCO ANTIGUO</span>
        <h3>Instalaciones sin renovar en el casco</h3>
        <p>Muchas viviendas del casco urbano de Monforte del Cid conservan tuberías de hierro que no se han sustituido desde la construcción. Las juntas fallan, aparecen humedades y el caudal disminuye. La sustitución planificada por tramos evita cortes prolongados y averías mayores.</p>
      </div>

    </div>
  </div>
</section>

<!-- Proceso -->
<section class="zona-sec-gray" style="background:#fff">
  <div class="cta-dark-con">
    <p class="zona-lbl">Cómo trabajamos</p>
    <h2>Proceso <span class="hl">claro y sin sorpresas</span></h2>
    <div class="zona-proceso">
      <div class="zona-paso">
        <div class="zona-paso-n">01</div>
        <h3>Contacto</h3>
        <p>Llama o escribe por WhatsApp. Cuéntanos la avería o el trabajo. Concretamos hora de visita en Monforte del Cid.</p>
      </div>
      <div class="zona-paso">
        <div class="zona-paso-n">02</div>
        <h3>Diagnóstico</h3>
        <p>El fontanero revisa la instalación, localiza el problema y explica qué hay que hacer y por qué.</p>
      </div>
      <div class="zona-paso">
        <div class="zona-paso-n">03</div>
        <h3>Presupuesto</h3>
        <p>Precio cerrado antes de empezar. No se toca nada sin tu aprobación. Sin llamadas para añadir coste después.</p>
      </div>
      <div class="zona-paso">
        <div class="zona-paso-n">04</div>
        <h3>Trabajo y garantía</h3>
        <p>Ejecutamos el trabajo, limpiamos la zona y entregamos garantía escrita por los materiales y la mano de obra.</p>
      </div>
    </div>
  </div>
</section>

<!-- Artículos -->
<?php
$_arts = [];
try {
  $_as = $pdo->query('SELECT titulo, slug, extracto, categoria, imagen FROM articulos WHERE publicado=1 ORDER BY fecha DESC LIMIT 3');
  $_arts = $_as ? $_as->fetchAll(PDO::FETCH_ASSOC) : [];
} catch (\Throwable $_e) {}
if (!empty($_arts)): ?>
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Consejos &uacute;tiles</p>
    <h2>Art&iacute;culos de fontaner&iacute;a</h2>
    <div class="zona-svc" style="margin-top:2rem">
      <?php foreach ($_arts as $_a): ?>
      <a href="/noticias/<?php echo urlencode($_a['slug']); ?>" class="zona-sc">
        <?php if (!empty($_a['imagen'])): ?><img src="<?php echo htmlspecialchars($_a['imagen']); ?>" alt="<?php echo htmlspecialchars($_a['titulo']); ?>" loading="lazy" style="width:100%;height:160px;object-fit:cover;border-radius:8px;margin-bottom:.75rem"><?php endif; ?>
        <?php if (!empty($_a['categoria'])): ?><span class="zona-lbl" style="font-size:11px"><?php echo htmlspecialchars($_a['categoria']); ?></span><?php endif; ?>
        <h3><?php echo htmlspecialchars($_a['titulo']); ?></h3>
        <p><?php echo htmlspecialchars(mb_substr($_a['extracto'] ?? '', 0, 100)); ?>...</p>
        <span class="zona-sc-a">Leer art&iacute;culo &rarr;</span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- Mapa -->
<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Zona de cobertura</p>
    <h2>Fontaner&iacute;a <span class="hl">en Monforte del Cid</span></h2>
    <p style="margin-bottom:1.5rem;color:#576574">Atendemos toda la localidad de Monforte del Cid (CP 03670) y municipios lim&iacute;trofes.</p>
    <div style="border-radius:12px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.12)">
      <iframe src="https://maps.google.com/maps?q=38.3735,-0.6632&z=14&output=embed" width="100%" height="380" style="border:0;display:block" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Fontanero en Monforte del Cid"></iframe>
    </div>
  </div>
</section>

<!-- Barrios y zonas -->
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Zonas donde trabajamos en Monforte del Cid</p>
    <h2>Cobertura <span class="hl">en todo Monforte del Cid</span></h2>
    <div class="zona-ztags" style="margin-top:1.5rem">
      <span class="zona-ztag-plain">Casco urbano</span>
      <span class="zona-ztag-plain">Urbanizaciones</span>
      <span class="zona-ztag-plain">Polígono Industrial</span>
      <span class="zona-ztag-plain">Zona residencial</span>
      <span class="zona-ztag-plain">Zona agrícola</span>
    </div>
  </div>
</section>

<!-- Municipios cercanos -->
<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Fontanería en otras zonas</p>
    <h2>También trabajamos <span class="hl">en municipios cercanos</span></h2>
    <div class="zona-ztags">
      <a href="/fontanero/elda" class="zona-ztag">Elda</a>
      <a href="/fontanero/novelda" class="zona-ztag">Novelda</a>
      <a href="/fontanero/aspe" class="zona-ztag">Aspe</a>
      <a href="/fontanero/pinoso" class="zona-ztag">Pinoso</a>
      <a href="/fontanero/monovar" class="zona-ztag">Mon&oacute;var</a>
      <a href="/fontanero/petrer" class="zona-ztag">Petrer</a>
      <a href="/fontanero/sax" class="zona-ztag">Sax</a>
      <a href="/fontanero/salinas" class="zona-ztag">Salinas</a>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Preguntas frecuentes</p>
    <h2>Fontanería en Monforte del Cid <span class="hl">— dudas habituales</span></h2>
    <div class="zona-faqs">
      <details class="zona-faq-item" open>
        <summary>¿Cuánto cuesta un fontanero en Monforte del Cid?</summary>
        <div class="faq-ans">No es lo mismo reparar un grifo en el casco que sustituir la instalación completa de una comunidad de vecinos. Visitamos, diagnosticamos y damos el presupuesto gratis antes de tocar nada. Sin compromiso.</div>
      </details>
      <details class="zona-faq-item">
        <summary>¿Atendéis urgencias en festivos y fines de semana en Monforte del Cid?</summary>
        <div class="faq-ans">Sí. El servicio de urgencias está disponible todos los días del año, incluidos sábados, domingos y festivos. Hay un recargo por urgencia fuera de horario que se comunica antes de desplazarse. Llama al 611 165 129 o escribe por WhatsApp y te confirmamos disponibilidad y tiempo de llegada estimado.</div>
      </details>
      <details class="zona-faq-item">
        <summary>¿Atendéis comunidades de vecinos en Monforte del Cid?</summary>
        <div class="faq-ans">Sí. Damos servicio a comunidades de propietarios: reparación de bajantes comunes, fugas en tuberías generales, atascos en arquetas y mantenimiento preventivo de la instalación. Emitimos informe técnico si la comunidad lo necesita para el seguro.</div>
      </details>
      <details class="zona-faq-item">
        <summary>¿Vale la pena instalar un descalcificador en Monforte del Cid?</summary>
        <div class="faq-ans">Sí. El agua del Vinalopó tiene dureza alta y el sarro reduce la vida útil de termos, calderas y electrodomésticos. Un descalcificador bien dimensionado puede amortizarse en 2-3 años en ahorro de averías y consumo energético. Te asesoramos sin compromiso sobre el equipo adecuado para tu vivienda.</div>
      </details>
      <details class="zona-faq-item">
        <summary>¿Podéis hacer la reforma completa de fontanería de mi vivienda en Monforte?</summary>
        <div class="faq-ans">Sí. Realizamos la sustitución completa de tuberías de suministro y saneamiento, traslado de puntos de agua, adecuación de instalaciones antiguas a normativa y montaje de baños y cocinas. Presupuesto gratuito a domicilio en Monforte del Cid.</div>
      </details>
      <details class="zona-faq-item">
        <summary>¿Cuánto tarda en llegar un fontanero a Monforte del Cid?</summary>
        <div class="faq-ans">En urgencias reales —escape de agua, inundación, corte total de suministro— intentamos llegar en el menor tiempo posible. Al llamar te confirmamos la disponibilidad y el tiempo de llegada estimado. Para trabajos programados concertamos la visita en el horario que mejor te venga.</div>
      </details>
    </div>
  </div>
</section>

<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>&iquest;Necesitas fontaner&iacute;a <span>en Monforte del Cid?</span></h2>
    <p>Atendemos toda Monforte del Cid. Presupuesto previo, sin sorpresas.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; Llamar ahora</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">&#128172; WhatsApp</a>
    </div>
  </div>
</section>

<?php include '../includes/footer.php'; ?>
