<?php
/**
 * Detección de fugas en Aspe
 */
$meta_title = 'Detección de Fugas en Aspe Sin Obras 24h | CarolTemp';
$meta_desc  = 'Localización de fugas en Aspe con geófono, termografía y gas trazador. Sin romper hasta confirmar el punto exacto. Desde 140 € · Urgencias 24 h · Instaladores Nubeco.';
$meta_url   = 'https://caroltemp.com/fontanero/aspe/busqueda_fugas';
$schema_type = 'local';
$page_css   = 'zona';
$page_js    = 'zona';

$faq_items = [
  [
    'q' => '¿Cuánto cuesta detectar una fuga de agua en Aspe?',
    'a' => 'La detección con geófono parte desde 140 € con la primera hora incluida. El precio final depende del tipo de instalación (vivienda del casco, chalet con jardín, comunidad de vecinos, nave del polígono), la accesibilidad de la tubería y si es necesario combinar geófono con termografía o gas trazador. El desplazamiento a Aspe son 25 € fijos. Damos presupuesto antes de empezar — sin coste oculto.',
  ],
  [
    'q' => '¿Cuánto tiempo tarda la detección de una fuga en una vivienda de Aspe?',
    'a' => 'En una vivienda unifamiliar estándar la localización lleva entre 1 y 2 horas. En instalaciones más complejas (suelo radiante en toda la planta, circuito de riego largo enterrado en jardín, red de bajantes en comunidad de vecinos) puede necesitar entre 2 y 3 horas. Si la tubería es accesible, la reparación puede completarse en la misma jornada. Atendemos urgencias todos los días del año — máx. 3 horas desde el contacto.',
  ],
  [
    'q' => '¿La detección sin romper funciona en casas antiguas del centro de Aspe?',
    'a' => 'Sí. El geófono capta el sonido de la pérdida a través de muros de obra antigua o tabiques de ladrillo sin necesidad de picar. En viviendas con tuberías de hierro galvanizado de los años sesenta o setenta, el sonido de la fuga se propaga bien y el geófono lo localiza con precisión. Para fisuras muy pequeñas en circuitos de plástico o PVC usamos gas trazador, que funciona igual de bien en instalaciones antiguas que en las nuevas.',
  ],
  [
    'q' => '¿El seguro del hogar cubre la detección y reparación de fugas en Aspe?',
    'a' => 'Muchas pólizas cubren los daños materiales causados por una fuga oculta (humedades, materiales afectados, reparación del conducto), pero la aseguradora exige documentación técnica que acredite el origen. El informe que emitimos describe la fuga, el método de detección y el punto exacto localizado — es el documento estándar que piden las compañías para tramitar el siniestro. Sin ese informe, la reclamación puede retrasarse indefinidamente.',
  ],
  [
    'q' => '¿Qué hago mientras espero al técnico si tengo una fuga urgente en Aspe?',
    'a' => 'Cierra la llave de paso general de la vivienda para cortar el suministro y detener la pérdida. Si hay agua acumulada cerca de instalaciones eléctricas (enchufes, cuadro, electrodomésticos), corta también el diferencial de la zona afectada. No intentes localizar la fuga perforando paredes por tu cuenta — sin equipo de detección abrirás donde no es y dañarás más estructura. Anota la lectura del contador antes de cerrar la llave: ese dato ayuda al técnico a estimar el caudal de la pérdida.',
  ],
];

include '../../includes/head.php';
?>

<?php
$_hi     = getHeroImagen('fugas-aspe');
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
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Detección de fugas &middot; Aspe &middot; CP 03680</div>
    <h1>Detección de fugas en Aspe<br><span class="hl">localizamos la pérdida sin romper paredes ni suelos</span></h1>
    <p class="hz-dark-sub">Geófono, termografía y gas trazador antes de abrir nada. Solo se interviene en el punto exacto de la rotura — sin excavaciones a ciegas, sin levantar solería de toda la habitación. Servicio urgente todos los días del año.</p>
    <div class="hz-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; 611 165 129</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">&#128172; WhatsApp</a>
    </div>
  </div>
</section>

<div class="dif-strip">
  <div class="dif-strip-in">
    <div class="dif-item"><span class="dif-val">Geófono acústico</span><span class="dif-lbl">Tuberías empotradas y enterradas</span></div>
    <div class="dif-item"><span class="dif-val">Termografía infrarroja</span><span class="dif-lbl">Suelo radiante, falsos techos y bajantes</span></div>
    <div class="dif-item"><span class="dif-val">Gas trazador</span><span class="dif-lbl">PVC y polietileno en circuitos de riego</span></div>
    <div class="dif-item"><span class="dif-val">Desde 140 €</span><span class="dif-lbl">Primera hora incluida · desplazamiento 25 €</span></div>
  </div>
</div>

<section class="zona-sec">
  <div class="cta-dark-con">
    <div class="zona-tcol">
      <div>
        <p class="zona-lbl">Detección de fugas en Aspe</p>
        <h2>Detección de fugas urgente <span class="hl">24 horas en Aspe sin obras</span></h2>
        <div class="zona-prose">
          <p>Las fugas más peligrosas son las que no se ven. Una mancha que aparece en el techo y desaparece. Un olor a húmedo que persiste aunque se ventile. Una factura del agua que sube mes a mes sin que nadie haya cambiado sus hábitos. Estas son las señales de una fuga oculta — activa, haciendo daño, pero sin dejar rastro claro de dónde viene.</p>
          <p>Aspe tiene una mezcla de viviendas antiguas del centro histórico — con tuberías de hierro galvanizado de los años sesenta y setenta — y urbanizaciones y chalets más recientes con suelo radiante y circuitos de riego enterrados en jardín. En ambos casos el método cambia: geófono para tuberías metálicas, gas trazador para PVC y polietileno de riego, termografía para suelo radiante y falsos techos. Solo se abre el punto exacto de la rotura confirmada, y la reparación la ejecutan instaladores certificados Nubeco en la misma visita si la tubería es accesible.</p>
        </div>
        <ul class="zona-chk">
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en tuberías de hierro del centro y casco antiguo</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en circuitos de riego enterrados en chalets y jardines</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en circuitos de suelo radiante bajo el pavimento</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en bajantes y columnas de comunidades de vecinos</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en acometidas exteriores y arquetas de parcelas</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en instalaciones de agua de naves del polígono</li>
        </ul>
      </div>
      <div>
        <div class="icard">
          <div class="icard-head"><strong>CarolTemp &middot; Aspe</strong><span>Detección de fugas</span></div>
          <div class="icard-body">
            <ul>
              <li>Factura de agua sube sin explicación</li>
              <li>Contador gira con todas las llaves cerradas</li>
              <li>Humedad en pared, suelo o techo sin lluvia</li>
              <li>Ruido de agua con todo cerrado</li>
              <li>Suelo tibio en zona sin suelo radiante</li>
              <li>Tu vecino detecta humedad en su techo</li>
            </ul>
          </div>
          <a href="tel:+34611165129" class="zona-icard-btn">&#128222; 611 165 129 — Llamar ahora</a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Tipología local</p>
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem">Fugas habituales por tipo de vivienda en Aspe</h3>
    <div class="zona-svc">
      <div class="zona-sc">
        <h3>Casas del centro histórico con tuberías antiguas</h3>
        <p>El casco histórico de Aspe concentra viviendas con instalaciones de hierro galvanizado de los años sesenta y setenta. La cal del agua del Vinalopó se deposita en el interior del tubo y la corrosión interna perfora el metal con fisuras que el agua tarda meses en atravesar hasta aparecer como mancha en la pared. El geófono detecta el punto exacto de la pérdida desde el exterior sin necesidad de picar el revoco.</p>
      </div>
      <div class="zona-sc">
        <h3>Chalets y unifamiliares con jardín</h3>
        <p>Las viviendas con jardín tienen circuitos de riego de polietileno enterrados que pueden recorrer decenas de metros bajo tierra y césped. Una fisura en estos circuitos de plástico no produce ruido apreciable con el geófono — para estos casos usamos gas trazador: nitrógeno con trazador de hidrógeno inerte que asciende exactamente por el punto de la fisura y el detector lo localiza en superficie, sin excavar a ciegas.</p>
      </div>
      <div class="zona-sc">
        <h3>Viviendas con suelo radiante</h3>
        <p>Las construcciones más recientes de Aspe incorporan suelo radiante como calefacción principal. Una microfisura en el circuito pierde presión de forma gradual — la caldera trabaja más, el suelo no alcanza temperatura y la factura del agua sube sin explicación. La cámara termográfica detecta la diferencia de temperatura exactamente en el metro cuadrado de la rotura sin levantar ninguna baldosa.</p>
      </div>
      <div class="zona-sc">
        <h3>Comunidades de vecinos y polígono industrial</h3>
        <p>En bloques de pisos, las bajantes recorren el interior de las paredes compartidas entre vecinos. Una fuga en una bajante empotrada genera humedad en el techo del piso inferior sin que el propietario de arriba lo sepa. En naves del polígono calzadero, los circuitos de agua fría tienen tramos largos enterrados bajo la solera — el geófono y el correlador localizan el tramo exacto sin vaciar el circuito completo.</p>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Tecnología y proceso</p>
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem">Cómo detectamos fugas sin obras en Aspe</h3>
    <div class="zona-steps">
      <div class="zona-step">
        <div class="zona-step-n">1</div>
        <div class="zona-step-txt">
          <strong>Geófono — escuchamos la tubería</strong>
          <p>El geófono acústico amplifica el sonido del agua escapando bajo presión. El técnico recorre el trazado de la tubería metro a metro midiendo la señal. El punto donde es máxima es la localización de la fuga. Funciona en tuberías de cobre, hierro, acero y acometidas empotradas. Las tuberías de plástico conducen peor el sonido — ahí entra el gas trazador.</p>
        </div>
      </div>
      <div class="zona-step">
        <div class="zona-step-n">2</div>
        <div class="zona-step-txt">
          <strong>Gas trazador o termografía — confirmamos el punto</strong>
          <p>Para PVC y polietileno de riego inyectamos nitrógeno con trazador de hidrógeno inerte: asciende hasta la superficie por el punto de la fisura y el detector lo localiza aunque la tubería esté enterrada a un metro. La termografía confirma suelo radiante y falsos techos detectando la diferencia de temperatura que genera la humedad.</p>
        </div>
      </div>
      <div class="zona-step">
        <div class="zona-step-n">3</div>
        <div class="zona-step-txt">
          <strong>Presupuesto antes de abrir</strong>
          <p>Marcamos el punto exacto en el suelo o la pared y damos presupuesto de reparación antes de tocar nada — tú decides si seguimos. Los instaladores Nubeco certificados pueden ejecutar la reparación en la misma visita si la tubería es accesible, y emitimos el informe técnico válido para el seguro del hogar.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Tarifas orientativas</p>
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem">Precio de detección de fugas en Aspe</h3>
    <div class="zona-precios">
      <div class="zona-precio-head">
        <span>Servicio</span><span>Precio orientativo</span>
      </div>
      <div class="zona-precio-row">
        <span>Detección con geófono</span><span>Desde 140 € (primera hora incluida)</span>
      </div>
      <div class="zona-precio-row">
        <span>Desplazamiento a Aspe</span><span>25 €</span>
      </div>
      <div class="zona-precio-row">
        <span>Recargo nocturno</span><span>Desde 20:00 h</span>
      </div>
      <div class="zona-precio-row">
        <span>Festivos y domingos</span><span>Recargo aplicable</span>
      </div>
    </div>
    <p style="margin-top:1rem;color:#576574;font-size:.9rem">Presupuesto confirmado antes de empezar. El informe de localización es válido para tramitar con el seguro del hogar.</p>
  </div>
</section>

<section class="zona-sec zona-sec-dark">
  <div class="cta-dark-con">
    <p class="zona-lbl">Zona de cobertura</p>
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1rem;color:#fff">Detectamos fugas en toda Aspe</h3>
    <div class="zona-ztags">
      <span class="zona-ztag-plain">Centro hist&oacute;rico (CP 03680)</span>
      <span class="zona-ztag-plain">La Serreta</span>
      <span class="zona-ztag-plain">La Rabosa</span>
      <span class="zona-ztag-plain">Pol&iacute;gono industrial calzado</span>
      <span class="zona-ztag-plain">Urbanizaciones perif&eacute;ricas</span>
      <span class="zona-ztag-plain">Chalets y viviendas unifamiliares</span>
      <span class="zona-ztag-plain">Casas de campo y fincas</span>
    </div>
  </div>
</section>

<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Preguntas frecuentes</p>
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem">Dudas habituales sobre fugas en Aspe</h3>
    <div class="zona-faq" style="margin-top:1.5rem">
      <?php foreach ($faq_items as $fi): ?>
      <details class="zona-faq-item">
        <summary><?php echo htmlspecialchars($fi['q']); ?></summary>
        <p><?php echo htmlspecialchars($fi['a']); ?></p>
      </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- /editable -->
<?php
$_proy = [];
try {
  $_ps = $pdo->prepare('SELECT titulo, slug, descripcion, servicio, imagen FROM proyectos WHERE publicado=1 AND zona LIKE ? ORDER BY fecha DESC LIMIT 3');
  $_ps->execute(['%Aspe%']);
  $_proy = $_ps->fetchAll(PDO::FETCH_ASSOC);
  if (empty($_proy)) {
    $_ps2 = $pdo->query('SELECT titulo, slug, descripcion, servicio, imagen FROM proyectos WHERE publicado=1 ORDER BY fecha DESC LIMIT 3');
    $_proy = $_ps2 ? $_ps2->fetchAll(PDO::FETCH_ASSOC) : [];
  }
} catch (\Throwable $_e) {}
$_arts = [];
try {
  $_as = $pdo->prepare('SELECT titulo, slug, extracto, categoria, imagen FROM articulos WHERE publicado=1 AND (zona LIKE ? OR categoria LIKE ?) ORDER BY fecha DESC LIMIT 3');
  $_as->execute(['%Aspe%', '%fontan%']);
  $_arts = $_as->fetchAll(PDO::FETCH_ASSOC);
  if (empty($_arts)) {
    $_as2 = $pdo->query('SELECT titulo, slug, extracto, categoria, imagen FROM articulos WHERE publicado=1 ORDER BY fecha DESC LIMIT 3');
    $_arts = $_as2 ? $_as2->fetchAll(PDO::FETCH_ASSOC) : [];
  }
} catch (\Throwable $_e) {}
if (!empty($_proy)): ?>
<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Trabajos realizados</p>
    <h2>Proyectos de detección de fugas <span class="hl">en Aspe</span></h2>
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
<?php if (!empty($_arts)): ?>
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Consejos &uacute;tiles</p>
    <h2>Art&iacute;culos sobre <span class="hl">detección de fugas</span></h2>
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
<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Zona de cobertura</p>
    <h2>Detección de fugas <span class="hl">en Aspe</span></h2>
    <p style="margin-bottom:1.5rem;color:#576574">Atendemos toda la localidad de Aspe (CP 03680).</p>
    <div style="border-radius:12px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.12)">
      <iframe src="https://maps.google.com/maps?q=38.3442,-0.7704&z=14&output=embed" width="100%" height="380" style="border:0;display:block" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Detección de fugas en Aspe"></iframe>
    </div>
  </div>
</section>
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Mismo servicio en otras zonas</p>
    <h2>Tambi&eacute;n detectamos fugas <span class="hl">en otros municipios</span></h2>
    <div class="zona-ztags">
      <a href="/fontanero/aspe" class="zona-ztag" style="background:#1e3a5f;color:#fff">&#8592; Todos los servicios en Aspe</a>
      <a href="/fontanero/elda/busqueda_fugas" class="zona-ztag">Elda</a>
      <a href="/fontanero/novelda/busqueda_fugas" class="zona-ztag">Novelda</a>
      <a href="/fontanero/petrer/busqueda_fugas" class="zona-ztag">Petrer</a>
      <a href="/fontanero/monovar/busqueda_fugas" class="zona-ztag">Mon&oacute;var</a>
      <a href="/fontanero/sax/busqueda_fugas" class="zona-ztag">Sax</a>
      <a href="/fontanero/pinoso/busqueda_fugas" class="zona-ztag">Pinoso</a>
      <a href="/fontanero/monforte-del-cid/busqueda_fugas" class="zona-ztag">Monforte del Cid</a>
      <a href="/fontanero/salinas/busqueda_fugas" class="zona-ztag">Salinas</a>
    </div>
  </div>
</section>
<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>&iquest;Tienes una fuga en <span>Aspe?</span></h2>
    <p>Llamamos antes de abrir. Localizamos, presupuestamos y reparamos en la misma visita.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; 611 165 129</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">&#128172; WhatsApp</a>
    </div>
  </div>
</section>
<?php
$ciudad = 'aspe';
$servicio = 'fugas';
include '../../includes/resenas-section.php';
?>
<?php include '../../includes/galeria-section.php'; ?>
<?php include '../../includes/footer.php'; ?>
