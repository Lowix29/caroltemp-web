<?php
/**
 * Detección de fugas en Monforte del Cid
 */
$meta_title = 'Detección de Fugas en Monforte del Cid Sin Obras 24h | CarolTemp';
$meta_desc  = 'Localización de fugas en Monforte del Cid con geófono, termografía y gas trazador. Sin romper hasta confirmar el punto. Desde 140 € · Urgencias 24 h · Instaladores Nubeco.';
$meta_url   = 'https://caroltemp.com/fontanero/monforte-del-cid/busqueda_fugas';
$schema_type = 'local';
$page_css   = 'zona';
$page_js    = 'zona';

$faq_items = [
  [
    'q' => '¿Cuánto cuesta detectar una fuga de agua en Monforte del Cid?',
    'a' => 'La detección con geófono profesional parte desde 140 € con la primera hora incluida. El precio final depende del tipo de instalación (vivienda del casco, bodega o nave agrícola, chalet con jardín, circuito de riego enterrado en finca) y de la accesibilidad de la tubería. El desplazamiento a Monforte del Cid son 25 € fijos. Damos presupuesto confirmado antes de empezar — sin coste oculto ni sorpresas.',
  ],
  [
    'q' => '¿Cuánto tiempo tarda la detección de una fuga sin romper en Monforte del Cid?',
    'a' => 'En una vivienda unifamiliar o piso, la localización con geófono lleva entre 1 y 2 horas. En instalaciones más complejas (finca con circuito de riego largo, bodega con redes de agua fría y caliente, suelo radiante en toda la planta) puede necesitar entre 2 y 4 horas. Si la tubería es accesible, la reparación puede completarse en la misma jornada con los instaladores Nubeco. Atendemos urgencias todos los días del año — máx. 3 horas desde el contacto.',
  ],
  [
    'q' => '¿Cómo sé si tengo una fuga oculta en mi vivienda de Monforte del Cid?',
    'a' => 'Las señales más habituales son: la factura del agua sube mes a mes sin que haya cambiado el consumo; el contador gira con todas las llaves y grifos cerrados; aparece una mancha de humedad en pared o suelo sin lluvia reciente; se escucha un goteo continuo aunque todo esté cerrado; el suelo está tibio en una zona donde no hay suelo radiante; o tu vecino de abajo detecta humedad en su techo. Si aparece alguna de estas señales, merece la pena llamar antes de que el daño avance.',
  ],
  [
    'q' => '¿La detección de fugas daña paredes o suelos?',
    'a' => 'No. El geófono trabaja desde el exterior de la tubería — detecta el sonido de la pérdida a través de la estructura sin picar nada. La termografía registra la temperatura de la superficie sin tocarla. El gas trazador escapa por la fisura y asciende hasta el detector sin abrir el circuito. Solo se interviene en el punto exacto de la rotura, una vez confirmado. La apertura mínima es lo que reduce el coste total de la reparación y de los acabados.',
  ],
  [
    'q' => '¿Qué garantía ofrece CarolTemp tras reparar la fuga detectada?',
    'a' => 'Los instaladores certificados Nubeco que ejecutan la reparación garantizan la mano de obra. Si el problema persiste, hacemos una segunda visita sin coste adicional. El informe técnico de localización que emitimos al terminar detalla el método empleado, el punto exacto y el resultado de las pruebas — es el documento que pide el seguro del hogar para tramitar el siniestro si la póliza cubre daños por agua.',
  ],
];

include '../../includes/head.php';
?>

<?php
$_hi     = getHeroImagen('fugas-monforte-del-cid');
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
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Detección de fugas &middot; Monforte del Cid &middot; CP 03670</div>
    <h1>Detección de fugas en Monforte del Cid<br><span class="hl">localizamos la pérdida sin romper paredes ni suelos</span></h1>
    <p class="hz-dark-sub">Geófono, termografía y gas trazador antes de abrir nada. Solo se interviene en el punto exacto de la rotura — sin excavaciones a ciegas, sin levantar solería de toda la habitación. Servicio urgente todos los días del año.</p>
    <div class="hz-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; 611 165 129</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">&#128172; WhatsApp</a>
    </div>
  </div>
</section>

<div class="dif-strip">
  <div class="dif-strip-in">
    <div class="dif-item"><span class="dif-val">Geófono y correlador</span><span class="dif-lbl">Tuberías empotradas y enterradas</span></div>
    <div class="dif-item"><span class="dif-val">Termografía infrarroja</span><span class="dif-lbl">Suelo radiante, falsos techos y bajantes</span></div>
    <div class="dif-item"><span class="dif-val">Gas trazador</span><span class="dif-lbl">PVC y polietileno en circuitos de riego</span></div>
    <div class="dif-item"><span class="dif-val">Desde 140 €</span><span class="dif-lbl">Primera hora incluida · desplazamiento 25 €</span></div>
  </div>
</div>

<section class="zona-sec">
  <div class="cta-dark-con">
    <div class="zona-tcol">
      <div>
        <p class="zona-lbl">Detección de fugas en Monforte del Cid</p>
        <h2>Detección de fugas urgente <span class="hl">24 horas en Monforte del Cid sin obras</span></h2>
        <div class="zona-prose">
          <p>Monforte del Cid tiene una doble realidad que complica la detección de fugas: el casco urbano con viviendas antiguas de tuberías de hierro galvanizado que llevan décadas bajo la cal del agua del Vinalopó, y el entorno agrícola — viñedos, almendros, bodegas — donde las redes de riego de polietileno pueden recorrer cientos de metros enterradas bajo tierra. En ambos casos la fuga es invisible desde fuera hasta que el daño ya es importante.</p>
          <p>Con geófono acústico localizamos el punto de la pérdida en tuberías metálicas y acometidas. Con gas trazador encontramos fisuras en circuitos de PVC o polietileno de riego aunque estén enterrados a un metro de profundidad. La termografía confirma el punto en suelo radiante o falsos techos sin levantar nada. Solo se abre donde está la rotura confirmada — y la reparación la ejecutan instaladores certificados Nubeco en la misma visita si la tubería es accesible.</p>
        </div>
        <ul class="zona-chk">
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en tuberías empotradas en viviendas del casco urbano</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en circuitos de riego enterrados en viñedos y fincas</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en circuitos de suelo radiante bajo el pavimento</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en bajantes y columnas de comunidades de vecinos</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en instalaciones de agua de bodegas y naves agrícolas</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en acometidas y arquetas exteriores de chalets y unifamiliares</li>
        </ul>
      </div>
      <div>
        <div class="icard">
          <div class="icard-head"><strong>CarolTemp &middot; Monforte del Cid</strong><span>Detección de fugas</span></div>
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
    <p class="zona-lbl">Contexto local</p>
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem">Fugas habituales en Monforte del Cid por tipo de inmueble</h3>
    <div class="zona-svc">
      <div class="zona-sc">
        <h3>Viviendas del casco urbano con tuberías antiguas</h3>
        <p>El centro de Monforte del Cid tiene muchas viviendas con instalaciones de hierro galvanizado de los años sesenta y setenta. La cal del agua del Vinalopó se deposita en el interior del tubo y la corrosión interna perfora el metal en fisuras que el agua tarda meses en atravesar hasta aparecer como mancha en la pared. El geófono detecta el punto exacto de la pérdida desde el exterior sin tener que picar el revoco.</p>
      </div>
      <div class="zona-sc">
        <h3>Chalets y urbanizaciones con fosa séptica o jardín</h3>
        <p>Las urbanizaciones de Monforte del Cid — incluyendo zonas como Font del Llop y Orito — tienen chalets con circuitos de riego enterrados y, en algunos casos, fosas sépticas que generan filtraciones en el suelo circundante. Para los circuitos de polietileno de riego usamos gas trazador: el nitrógeno con trazador de hidrógeno asciende por el punto de la fisura y el detector lo localiza en superficie sin excavar a ciegas.</p>
      </div>
      <div class="zona-sc">
        <h3>Bodegas y explotaciones agrícolas</h3>
        <p>Monforte del Cid es zona vitivinícola con bodegas que tienen redes de agua fría, agua caliente de limpieza y vapor. Las pérdidas en estas instalaciones pueden ser importantes en volumen y no siempre generan señales visibles en el exterior de la nave. El geófono y el correlador localizan el tramo exacto en redes largas sin necesidad de vaciar el circuito completo.</p>
      </div>
      <div class="zona-sc">
        <h3>Viviendas con suelo radiante y calefacción</h3>
        <p>Las construcciones más recientes de Monforte del Cid incorporan suelo radiante como sistema de calefacción principal. Una microfisura en el circuito pierde presión de forma progresiva — la caldera trabaja más, el suelo no alcanza temperatura y la factura del agua sube sin explicación. La cámara termográfica detecta la diferencia de temperatura exactamente en el metro cuadrado de la rotura sin levantar ninguna baldosa.</p>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Tecnología de detección</p>
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem">C&oacute;mo detectamos fugas sin obras en Monforte del Cid</h3>
    <div class="zona-steps">
      <div class="zona-step">
        <div class="zona-step-n">1</div>
        <div class="zona-step-txt">
          <strong>Geófono — escuchamos la tubería</strong>
          <p>El geófono acústico amplifica el sonido del agua escapando bajo presión. El técnico recorre el trazado de la tubería metro a metro, midiendo la intensidad de la señal. El punto donde el sonido es máximo indica la localización de la fuga. Para circuitos enterrados largos en fincas y bodegas, el correlador compara la señal en dos puntos del circuito y calcula matemáticamente el metro exacto de la pérdida.</p>
        </div>
      </div>
      <div class="zona-step">
        <div class="zona-step-n">2</div>
        <div class="zona-step-txt">
          <strong>Termografía o gas trazador — confirmamos el punto</strong>
          <p>La termografía detecta la diferencia de temperatura que genera la humedad en el suelo radiante o en falsos techos. Para circuitos de PVC o polietileno de riego que no conducen bien el sonido, el gas trazador (nitrógeno con hidrógeno inerte) asciende hasta la superficie por el punto de la fisura y el detector lo localiza. Cuando geófono y método de confirmación coinciden, la localización es definitiva.</p>
        </div>
      </div>
      <div class="zona-step">
        <div class="zona-step-n">3</div>
        <div class="zona-step-txt">
          <strong>Presupuesto antes de abrir</strong>
          <p>Marcamos el punto exacto en el suelo o la pared y damos presupuesto de reparación antes de tocar nada — tú decides si seguimos en la misma visita o en otro momento. Los instaladores Nubeco certificados pueden ejecutar la reparación y emitimos el informe técnico de localización válido para el seguro del hogar.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Tarifas orientativas</p>
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem">Precio de detección de fugas en Monforte del Cid</h3>
    <div class="zona-precios">
      <div class="zona-precio-head">
        <span>Servicio</span><span>Precio orientativo</span>
      </div>
      <div class="zona-precio-row">
        <span>Detección con geófono</span><span>Desde 140 € (primera hora incluida)</span>
      </div>
      <div class="zona-precio-row">
        <span>Desplazamiento a Monforte del Cid</span><span>25 €</span>
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
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1rem;color:#fff">Detectamos fugas en todo el término de Monforte del Cid</h3>
    <div class="zona-ztags">
      <span class="zona-ztag-plain">Casco urbano (CP 03670)</span>
      <span class="zona-ztag-plain">Font del Llop</span>
      <span class="zona-ztag-plain">Orito</span>
      <span class="zona-ztag-plain">Zona industrial</span>
      <span class="zona-ztag-plain">Urbanizaciones periféricas</span>
      <span class="zona-ztag-plain">Chalets y viviendas unifamiliares</span>
      <span class="zona-ztag-plain">Bodegas y explotaciones agr&iacute;colas</span>
    </div>
  </div>
</section>

<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Preguntas frecuentes</p>
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem">Dudas habituales sobre fugas en Monforte del Cid</h3>
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
  $_ps->execute(['%Monforte%']);
  $_proy = $_ps->fetchAll(PDO::FETCH_ASSOC);
  if (empty($_proy)) {
    $_ps2 = $pdo->query('SELECT titulo, slug, descripcion, servicio, imagen FROM proyectos WHERE publicado=1 ORDER BY fecha DESC LIMIT 3');
    $_proy = $_ps2 ? $_ps2->fetchAll(PDO::FETCH_ASSOC) : [];
  }
} catch (\Throwable $_e) {}
$_arts = [];
try {
  $_as = $pdo->prepare('SELECT titulo, slug, extracto, categoria, imagen FROM articulos WHERE publicado=1 AND (zona LIKE ? OR categoria LIKE ?) ORDER BY fecha DESC LIMIT 3');
  $_as->execute(['%Monforte%', '%fontan%']);
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
    <h2>Proyectos de detección de fugas <span class="hl">en Monforte del Cid</span></h2>
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
    <h2>Detección de fugas <span class="hl">en Monforte del Cid</span></h2>
    <p style="margin-bottom:1.5rem;color:#576574">Atendemos toda la localidad de Monforte del Cid (CP 03670).</p>
    <div style="border-radius:12px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.12)">
      <iframe src="https://maps.google.com/maps?q=38.3735,-0.6632&z=14&output=embed" width="100%" height="380" style="border:0;display:block" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Detección de fugas en Monforte del Cid"></iframe>
    </div>
  </div>
</section>
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Mismo servicio en otras zonas</p>
    <h2>Tambi&eacute;n detectamos fugas <span class="hl">en otros municipios</span></h2>
    <div class="zona-ztags">
      <a href="/fontanero/monforte-del-cid" class="zona-ztag" style="background:#1e3a5f;color:#fff">&#8592; Todos los servicios en Monforte del Cid</a>
      <a href="/fontanero/elda/busqueda_fugas" class="zona-ztag">Elda</a>
      <a href="/fontanero/novelda/busqueda_fugas" class="zona-ztag">Novelda</a>
      <a href="/fontanero/aspe/busqueda_fugas" class="zona-ztag">Aspe</a>
      <a href="/fontanero/pinoso/busqueda_fugas" class="zona-ztag">Pinoso</a>
      <a href="/fontanero/monovar/busqueda_fugas" class="zona-ztag">Mon&oacute;var</a>
      <a href="/fontanero/petrer/busqueda_fugas" class="zona-ztag">Petrer</a>
      <a href="/fontanero/sax/busqueda_fugas" class="zona-ztag">Sax</a>
      <a href="/fontanero/salinas/busqueda_fugas" class="zona-ztag">Salinas</a>
    </div>
  </div>
</section>
<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>&iquest;Tienes una fuga en <span>Monforte del Cid?</span></h2>
    <p>Llamamos antes de abrir. Localizamos, presupuestamos y reparamos en la misma visita.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; 611 165 129</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">&#128172; WhatsApp</a>
    </div>
  </div>
</section>
<?php include '../../includes/footer.php'; ?>
