<?php
/**
 * Fontanero en Salinas — Hub
 * CarolTemp
 */
$meta_title  = 'Fontanero en Salinas (Alicante) | Urgencias 24h · Presupuesto Gratis — CarolTemp';
$meta_desc   = 'Fontanero en Salinas (Alicante, CP 03668). Urgencias, fugas, desatascos, aerotermia y reformas. Presupuesto gratuito y precio cerrado antes de empezar.';
$meta_url    = 'https://caroltemp.com/fontanero/salinas';
$schema_type = 'local';
$page_css    = 'zona';
$page_js     = 'zona';
$depth       = 1;

$faq_items = [
  [
    'q' => '¿Cuánto cuesta un fontanero en Salinas (Alicante)?',
    'a' => 'El presupuesto es gratuito y se da siempre con la avería vista, antes de tocar nada. En Salinas muchas viviendas tienen instalaciones antiguas de hierro galvanizado que pueden complicar el trabajo, por eso no damos precios genéricos: el diagnóstico in situ determina el coste real. Llama o escríbenos y te decimos exactamente qué costaría en tu caso.',
  ],
  [
    'q' => '¿Qué es la aerotermia y merece la pena instalarla en Salinas?',
    'a' => 'La aerotermia es un sistema que extrae energía del aire exterior para calentar el agua sanitaria y la vivienda. En Salinas, con clima continental de interior y veranos calurosos e inviernos fríos, es una de las mejores opciones para sustituir una caldera de gas antigua. Reduce el consumo energético hasta un 70 % y tiene acceso a subvenciones del programa MOVES. Somos instaladores certificados y hacemos el estudio de viabilidad gratis.',
  ],
  [
    'q' => '¿Hacéis cambio de caldera a aerotermia en Salinas?',
    'a' => 'Sí. Realizamos la sustitución completa: retirada de la caldera de gas, instalación de la bomba de calor aerotérmica, conexión al circuito de agua sanitaria y, si procede, al suelo radiante o fan-coils. Gestionamos también las ayudas y subvenciones disponibles para que la inversión inicial sea menor. Presupuesto gratuito a domicilio en Salinas.',
  ],
  [
    'q' => '¿Vale la pena instalar un descalcificador en Salinas?',
    'a' => 'Sí, especialmente si tienes termo eléctrico, caldera o suelo radiante. El agua de la comarca alicantina tiene dureza alta y el sarro reduce significativamente la vida útil de estos equipos. Un descalcificador bien dimensionado amortiza su coste en 2-3 años en ahorro de averías y consumo. Os asesoramos sin compromiso.',
  ],
  [
    'q' => '¿Atendéis urgencias de fontanería el mismo día en Salinas?',
    'a' => 'Sí. Cubrimos urgencias en Salinas (Alicante) el mismo día: roturas, escapes, calentador sin agua caliente y grupos de presión. Precio dado con la avería vista antes de empezar. Sin desplazamiento sorpresa ni coste oculto.',
  ],
  [
    'q' => '¿Cubríis el Polígono Industrial de Salinas y las viviendas del acceso CV-855?',
    'a' => 'Sí. Atendemos todo el término municipal de Salinas: casco urbano, Polígono Industrial, urbanizaciones, chalets y viviendas a lo largo de la CV-855. Para instalaciones industriales o con particularidades especiales, indicadlo al contactar para llevar el material adecuado.',
  ],
];

include '../includes/head.php';
?>

<?php
$_hi     = getHeroImagen('fontanero-salinas');
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
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Fontanería &middot; Salinas (Alicante) &middot; CP 03668</div>
    <h1>Fontanero en Salinas<br><span class="hl">con respuesta rápida y máxima profesionalidad</span></h1>
    <p class="hz-dark-sub">Urgencias, fugas, desatascos, aerotermia y reformas en Salinas (Alicante). Viviendas unifamiliares, chalets y comunidades. Presupuesto gratuito antes de empezar.</p>
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
    <div class="dif-item"><span class="dif-val">Fontanero local</span><span class="dif-lbl">Conocemos Salinas (Alicante)</span></div>
  </div>
</div>

<!-- Servicios principales destacados -->
<section class="zona-sec-dark">
  <div class="cta-dark-con">
    <p class="zona-lbl" style="color:#7ecfff">Lo más urgente</p>
    <h2 style="color:#fff;margin-bottom:2rem">Servicio inmediato <span class="hl">en Salinas</span></h2>
    <div class="zona-srv3">
      <a href="/fontanero/salinas/urgencias" class="zona-srv3-card">
        <span class="zona-srv3-ico">&#128680;</span>
        <h3>Fontanero urgente</h3>
        <p>Mismo día. Precio cerrado con la avería vista.</p>
        <span class="zona-srv3-a">Ver urgencias &rarr;</span>
      </a>
      <a href="/fontanero/salinas/busqueda_fugas" class="zona-srv3-card">
        <span class="zona-srv3-ico">&#128269;</span>
        <h3>Detección de fugas</h3>
        <p>Sin romper paredes. Geófono y termografía.</p>
        <span class="zona-srv3-a">Ver fugas &rarr;</span>
      </a>
      <a href="/fontanero/salinas/desatascos" class="zona-srv3-card">
        <span class="zona-srv3-ico">&#128688;</span>
        <h3>Desatascos</h3>
        <p>Cámara endoscópica e hidrojetting profesional.</p>
        <span class="zona-srv3-a">Ver desatascos &rarr;</span>
      </a>
    </div>
  </div>
</section>

<!-- Todos los servicios -->
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Servicios de fontanería en Salinas (Alicante)</p>
    <h2>¿Qué necesitas? <span class="hl">Te cubrimos.</span></h2>
    <div class="zona-svc" style="margin-top:2rem">

      <a href="/fontanero/salinas/urgencias" class="zona-sc">
        <span class="zona-sc-n">URGENCIAS</span>
        <h3>Fontanero urgente Salinas</h3>
        <p>Avería vista, precio dado. Atendemos el mismo día roturas, escapes, grupos de presión y calentadores en Salinas. Nada se toca sin que sepas cuánto cuesta.</p>
        <span class="zona-sc-a">Ver urgencias &rarr;</span>
      </a>

      <a href="/fontanero/salinas/busqueda_fugas" class="zona-sc">
        <span class="zona-sc-n">FUGAS</span>
        <h3>Detección de fugas en Salinas</h3>
        <p>Localización de fugas sin romper paredes: geófono y cámara termográfica. Frecuentes en casas antiguas con tuberías de hierro galvanizado. Marcamos el punto exacto antes de abrir.</p>
        <span class="zona-sc-a">Ver fugas &rarr;</span>
      </a>

      <a href="/fontanero/salinas/desatascos" class="zona-sc">
        <span class="zona-sc-n">DESATASCOS</span>
        <h3>Desatascos en Salinas</h3>
        <p>Desatascos de bajantes, arquetas e inodoros con cámara endoscópica e hidrojetting. El agua dura de la comarca acelera el sarro. Diagnóstico antes de actuar.</p>
        <span class="zona-sc-a">Ver desatascos &rarr;</span>
      </a>

      <div class="zona-sc">
        <span class="zona-sc-n">AEROTERMIA</span>
        <h3>Aerotermia e instalación de bomba de calor</h3>
        <p>Sustituye tu caldera de gas por aerotermia y reduce el consumo energético hasta un 70&nbsp;%. Somos instaladores Nubeco certificados. Tramitamos las subvenciones MOVES disponibles.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">DESCALCIFICADORES</span>
        <h3>Descalcificadores</h3>
        <p>El agua de la comarca alicantina tiene dureza elevada. Un descalcificador bien dimensionado protege termos, calentadores y electrodomésticos y se amortiza en 2-3 años.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">REFORMAS</span>
        <h3>Reformas de baño y cocina</h3>
        <p>Reforma completa o parcial de fontanería en Salinas. Sustitución de tuberías en casas antiguas, traslado de puntos de agua y adecuación a normativa. Presupuesto gratuito.</p>
      </div>

    </div>
  </div>
</section>

<!-- Por qué elegir CarolTemp -->
<section class="zona-sec zona-sec-alt">
  <div class="cta-dark-con">
    <div class="zona-tcol">
      <div>
        <p class="zona-lbl">Por qué Salinas (Alicante) es exigente con la fontanería</p>
        <h2>El agua en Salinas <span class="hl">y los problemas más frecuentes</span></h2>
        <div class="zona-prose">
          <p>Salinas, en el interior de Alicante, tiene un clima continental marcado: veranos calurosos e inviernos fríos con heladas ocasionales. Ese contraste térmico, unido a la dureza del agua comarcal, deteriora más rápido las instalaciones que en la costa. El sarro destruye termos y calentadores, reduce el caudal en tuberías de pequeño diámetro y provoca que las juntas fallen antes de lo previsto. En casas antiguas es habitual encontrar instalaciones de hierro galvanizado con microfugas que solo se detectan cuando ya hay daño visible.</p>
          <p>Muchos vecinos de Salinas buscan fontanero privado precisamente porque necesitan respuesta rápida y precio claro, sin depender de listas de espera. Llegamos con herramienta, damos precio y empezamos. Sin vuelta previa de diagnóstico cobrada aparte.</p>
        </div>
        <ul class="zona-chk">
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Termos que duran menos por el agua dura comarcal</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Tuberías antiguas de hierro galvanizado en casas de más de 30 años</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Heladas invernales que revientan tuberías mal protegidas</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Grupos de presión en chalets y viviendas con depósito propio</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Desagüe y bajantes que se colapsan antes por el sarro acumulado</li>
        </ul>
      </div>
      <div>
        <div class="zona-icard">
          <div class="zona-icard-h"><strong>CarolTemp &middot; Salinas</strong><span>Fontanería local</span></div>
          <div class="zona-ir"><span class="zona-ir-l">Zona</span><span class="zona-ir-v">Salinas (Alicante) &middot; CP 03668</span></div>
          <div class="zona-ir"><span class="zona-ir-l">Teléfono</span><span class="zona-ir-v"><a href="tel:+34611165129">611 165 129</a></span></div>
          <div class="zona-ir"><span class="zona-ir-l">WhatsApp</span><span class="zona-ir-v"><a href="https://wa.me/34611165129">Escribir ahora &rarr;</a></span></div>
          <div class="zona-ir"><span class="zona-ir-l">Certificación</span><span class="zona-ir-v">Instaladores Nubeco oficiales</span></div>
          <div class="zona-ir"><span class="zona-ir-l">Cobertura</span><span class="zona-ir-v">Casco urbano, polígono y viviendas unifamiliares</span></div>
          <a href="tel:+34611165129" class="zona-icard-btn">&#128222; Llamar ahora</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Aerotermia — sección diferenciadora -->
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Ahorro energético en Salinas</p>
    <h2>Aerotermia en Salinas: <span class="hl">deja atrás el gas y los gastos fijos</span></h2>
    <div class="zona-prose" style="max-width:760px;margin:0 auto 2rem">
      <p>El clima continental de Salinas —con inviernos fríos y veranos calurosos— hace que la calefacción sea un gasto importante para muchas familias. La aerotermia extrae energía del aire exterior incluso a temperaturas bajo cero y la transforma en calor para el agua sanitaria y la vivienda. Resultado: hasta un 70&nbsp;% menos de consumo eléctrico frente a un termo o una caldera convencional.</p>
      <p>Como instaladores Nubeco certificados, realizamos el cambio completo —retirada de caldera, instalación de la bomba de calor, conexión al circuito existente— y gestionamos las subvenciones del programa MOVES para reducir la inversión inicial. Presupuesto gratuito a domicilio en Salinas.</p>
    </div>
    <div class="zona-svc" style="margin-top:0">
      <div class="zona-sc">
        <span class="zona-sc-n">BOMBA DE CALOR</span>
        <h3>Instalación de aerotermia</h3>
        <p>Instalación completa con estudio previo de viabilidad. Compatible con suelo radiante, fan-coils y radiadores de baja temperatura.</p>
      </div>
      <div class="zona-sc">
        <span class="zona-sc-n">CAMBIO DE CALDERA</span>
        <h3>De caldera de gas a aerotermia</h3>
        <p>Sustitución total: retirada de caldera, instalación de la unidad exterior e interior, conexión y puesta en marcha. Tramitamos subvenciones MOVES.</p>
      </div>
      <div class="zona-sc">
        <span class="zona-sc-n">MANTENIMIENTO</span>
        <h3>Mantenimiento de bomba de calor</h3>
        <p>Revisión anual, limpieza de filtros y verificación del circuito frigorífico para mantener el rendimiento y la garantía del fabricante.</p>
      </div>
    </div>
  </div>
</section>

<!-- Proceso de trabajo -->
<section class="zona-sec zona-sec-alt">
  <div class="cta-dark-con">
    <p class="zona-lbl">Cómo trabajamos</p>
    <h2>Del aviso al <span class="hl">trabajo terminado</span></h2>
    <div class="zona-svc" style="margin-top:2rem">
      <div class="zona-sc">
        <span class="zona-sc-n">01</span>
        <h3>Llamas o escribes</h3>
        <p>Teléfono o WhatsApp. Nos dices qué pasa y quedamos en un horario que te venga bien.</p>
      </div>
      <div class="zona-sc">
        <span class="zona-sc-n">02</span>
        <h3>Diagnóstico en tu vivienda</h3>
        <p>Llegamos a Salinas con herramienta. Vemos la avería, te explicamos qué hay y te damos precio cerrado. Sin cobrar visita.</p>
      </div>
      <div class="zona-sc">
        <span class="zona-sc-n">03</span>
        <h3>Empezamos con tu ok</span></h3>
        <p>Solo tocamos algo cuando has aceptado el presupuesto. El precio que acordamos es el que pagas. Sin letra pequeña.</p>
      </div>
    </div>
  </div>
</section>

<!-- Zonas que cubrimos -->
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Cobertura en Salinas (Alicante)</p>
    <h2>Atendemos <span class="hl">todo el término municipal</span></h2>
    <p style="margin-bottom:1.5rem;color:#576574">Cubrimos todas las zonas de Salinas (CP 03668), no solo el casco urbano.</p>
    <div class="zona-ztags">
      <span class="zona-ztag-plain">Casco urbano</span>
      <span class="zona-ztag-plain">Polígono Industrial Salinas</span>
      <span class="zona-ztag-plain">Urbanizaciones</span>
      <span class="zona-ztag-plain">Chalets y viviendas unifamiliares</span>
      <span class="zona-ztag-plain">Acceso CV-855</span>
      <span class="zona-ztag-plain">Fincas y diseminado rural</span>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Preguntas frecuentes</p>
    <h2>Fontanería en Salinas (Alicante) <span class="hl">— dudas habituales</span></h2>
    <div class="zona-faqs">
      <details class="zona-faq-item" open>
        <summary><?php echo htmlspecialchars($faq_items[0]['q']); ?></summary>
        <div class="faq-ans"><?php echo htmlspecialchars($faq_items[0]['a']); ?></div>
      </details>
      <details class="zona-faq-item">
        <summary><?php echo htmlspecialchars($faq_items[1]['q']); ?></summary>
        <div class="faq-ans"><?php echo htmlspecialchars($faq_items[1]['a']); ?></div>
      </details>
      <details class="zona-faq-item">
        <summary><?php echo htmlspecialchars($faq_items[2]['q']); ?></summary>
        <div class="faq-ans"><?php echo htmlspecialchars($faq_items[2]['a']); ?></div>
      </details>
      <details class="zona-faq-item">
        <summary><?php echo htmlspecialchars($faq_items[3]['q']); ?></summary>
        <div class="faq-ans"><?php echo htmlspecialchars($faq_items[3]['a']); ?></div>
      </details>
      <details class="zona-faq-item">
        <summary><?php echo htmlspecialchars($faq_items[4]['q']); ?></summary>
        <div class="faq-ans"><?php echo htmlspecialchars($faq_items[4]['a']); ?></div>
      </details>
      <details class="zona-faq-item">
        <summary><?php echo htmlspecialchars($faq_items[5]['q']); ?></summary>
        <div class="faq-ans"><?php echo htmlspecialchars($faq_items[5]['a']); ?></div>
      </details>
    </div>
  </div>
</section>

<!-- Proyectos -->
<?php
$_proy = [];
try {
  $_ps = $pdo->prepare('SELECT titulo, slug, descripcion, servicio, imagen FROM proyectos WHERE publicado=1 AND zona LIKE ? ORDER BY fecha DESC LIMIT 3');
  $_ps->execute(['%Salinas%']);
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
    <h2>Proyectos de fontaner&iacute;a <span class="hl">en Salinas</span></h2>
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
    <h2>Fontaner&iacute;a <span class="hl">en Salinas (Alicante)</span></h2>
    <p style="margin-bottom:1.5rem;color:#576574">Atendemos toda la localidad de Salinas (CP 03668) y municipios lim&iacute;trofes del interior alicantino.</p>
    <div style="border-radius:12px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.12)">
      <iframe src="https://maps.google.com/maps?q=38.5009,-0.8536&z=14&output=embed" width="100%" height="380" style="border:0;display:block" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Fontanero en Salinas Alicante"></iframe>
    </div>
  </div>
</section>

<!-- Municipios cercanos -->
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Fontaner&iacute;a en otras zonas</p>
    <h2>Tambi&eacute;n trabajamos <span class="hl">en municipios cercanos</span></h2>
    <div class="zona-ztags">
      <a href="/fontanero/elda" class="zona-ztag">Elda</a>
      <a href="/fontanero/novelda" class="zona-ztag">Novelda</a>
      <a href="/fontanero/aspe" class="zona-ztag">Aspe</a>
      <a href="/fontanero/pinoso" class="zona-ztag">Pinoso</a>
      <a href="/fontanero/monovar" class="zona-ztag">Mon&oacute;var</a>
      <a href="/fontanero/petrer" class="zona-ztag">Petrer</a>
      <a href="/fontanero/sax" class="zona-ztag">Sax</a>
      <a href="/fontanero/monforte-del-cid" class="zona-ztag">Monforte del Cid</a>
    </div>
  </div>
</section>

<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>&iquest;Necesitas fontaner&iacute;a <span>en Salinas (Alicante)?</span></h2>
    <p>Atendemos toda Salinas. Presupuesto gratuito, sin sorpresas.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; Llamar ahora</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">&#128172; WhatsApp</a>
    </div>
  </div>
</section>

<?php
$ciudad = 'salinas';
$servicio = 'fontanero';
include '../includes/resenas-section.php';
?>
<?php include '../includes/galeria-section.php'; ?>
<?php include '../includes/footer.php'; ?>
