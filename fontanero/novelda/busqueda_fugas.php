<?php
/**
 * Detección de fugas en Novelda
 */
$meta_title = 'Detección de Fugas en Novelda Sin Obras 24h | CarolTemp';
$meta_desc  = 'Detección de fugas en Novelda con geófono, termografía y gas trazador. Localizamos la pérdida sin romper — desde 140 € primera hora incluida. Servicio urgente 24 h.';
$meta_url   = 'https://caroltemp.com/fontanero/novelda/busqueda_fugas';
$schema_type = 'local';
$page_css   = 'zona';
$page_js    = 'zona';

$faq_items = [
  [
    'q' => '¿Cuánto cuesta detectar una fuga de agua en Novelda?',
    'a' => 'La detección con geófono profesional parte desde 140 € con la primera hora incluida. El precio final depende del tipo de instalación (tuberías enterradas, suelo radiante, circuito de jardín), la accesibilidad y la profundidad de la pérdida. El desplazamiento a Novelda son 25 € fijos. Damos presupuesto antes de empezar — sin sorpresas.',
  ],
  [
    'q' => '¿En cuánto tiempo pueden localizar y reparar una fuga en Novelda?',
    'a' => 'La detección lleva entre 1 y 3 horas según la complejidad. Si la tubería afectada es accesible, la reparación puede completarse en la misma jornada. Para obras que requieren abrir suelo o pared, el plazo habitual es de 24 a 48 horas. Atendemos urgencias cualquier día del año.',
  ],
  [
    'q' => '¿Pueden localizar la fuga sin romper paredes ni pavimento?',
    'a' => 'Sí. El geófono escucha el ruido de la pérdida a través de la estructura y marca el punto exacto. La termografía detecta diferencias de temperatura en suelo radiante o falsos techos sin tocar nada. El gas trazador localiza fugas en circuitos de PVC o riego enterrados. Solo se abre justo donde está la rotura — nada más.',
  ],
  [
    'q' => '¿Qué hago si veo humedad en casa pero no sé si es una fuga o condensación?',
    'a' => 'La termografía diferencia ambas situaciones en minutos: la condensación aparece en superficies frías (puentes térmicos), mientras que una fuga genera un punto de humedad con temperatura diferente al entorno. En Novelda, con el calor seco del verano, la condensación es menos común que en zonas costeras — la humedad persistente suele indicar fuga real. Llama y lo verificamos sin coste de diagnóstico previo.',
  ],
  [
    'q' => '¿La reparación tiene garantía después de localizar la fuga en Novelda?',
    'a' => 'Sí. Los instaladores certificados Nubeco que realizan la reparación ofrecen garantía de mano de obra. Si el problema persiste, hacemos una segunda visita sin coste adicional. El informe de localización que emitimos es válido para tramitar con el seguro del hogar si la póliza cubre daños por agua.',
  ],
];

include '../../includes/head.php';
?>

<?php
$_hi     = getHeroImagen('fugas-novelda');
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
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Detección de fugas &middot; Novelda &middot; CP 03660</div>
    <h1>Detección de fugas en Novelda<br><span class="hl">localizamos la pérdida sin romper nada</span></h1>
    <p class="hz-dark-sub">El geófono escucha la tubería y señala el punto exacto de la fuga antes de que el cincel toque la pared. Menos obra, menos polvo, menos tiempo sin agua. Servicio disponible todos los días del año.</p>
    <div class="hz-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; 611 165 129</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">&#128172; WhatsApp</a>
    </div>
  </div>
</section>

<div class="dif-strip">
  <div class="dif-strip-in">
    <div class="dif-item"><span class="dif-val">Geófono profesional</span><span class="dif-lbl">Tuberías empotradas y enterradas</span></div>
    <div class="dif-item"><span class="dif-val">Termografía infrarroja</span><span class="dif-lbl">Suelo radiante, falsos techos y bajantes</span></div>
    <div class="dif-item"><span class="dif-val">Gas trazador</span><span class="dif-lbl">Circuitos de PVC y riego enterrado</span></div>
    <div class="dif-item"><span class="dif-val">Desde 140 €</span><span class="dif-lbl">Primera hora incluida · desplazamiento 25 €</span></div>
  </div>
</div>

<section class="zona-sec">
  <div class="cta-dark-con">
    <div class="zona-tcol">
      <div>
        <p class="zona-lbl">Detección de fugas en Novelda</p>
        <h2>Detección de fugas urgente <span class="hl">24 horas en Novelda sin obras</span></h2>
        <div class="zona-prose">
          <p>El agua dura del Vinalopó es uno de los factores que más daño hace a las tuberías de Novelda: el carbonato cálcico se deposita en el interior de los tubos, fragiliza las juntas de PVC y cobre y favorece microfisuras que tardan meses en hacerse visibles. A eso se añade la antigüedad de las instalaciones en el centro histórico — muchas viviendas tienen tuberías de los años setenta y ochenta que nunca se han sustituido — y la vibración de la maquinaria en la zona marmolera, que acelera el desgaste de los empalmes en naves y polígonos.</p>
          <p>Con geófono acústico, cámara termográfica y gas trazador localizamos la pérdida exacta antes de abrir nada. Solo se interviene en el punto de la rotura — no en toda la pared ni en todo el pavimento. Si detectamos la fuga y usted quiere reparar en la misma visita, los instaladores certificados Nubeco cierran el trabajo sin necesidad de contratar una segunda empresa.</p>
        </div>
        <ul class="zona-chk">
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en circuitos de suelo radiante bajo el pavimento</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en tuberías de jardín enterradas en chalets y unifamiliares</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en bajantes interiores y columnas de edificios de pisos</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en calentadores y juntas deterioradas por incrustaciones de cal</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en tuberías comunes y arquetas de comunidades de vecinos</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en circuitos enterrados de naves y pabellones industriales</li>
        </ul>
      </div>
      <div>
        <div class="icard">
          <div class="icard-head"><strong>CarolTemp &middot; Novelda</strong><span>Detección de fugas</span></div>
          <div class="icard-body">
            <ul>
              <li>Factura de agua sube sin explicación</li>
              <li>Humedad en suelo, pared o techo</li>
              <li>Ruido de agua con todo cerrado</li>
              <li>Manchas de eflorescencias en fachada</li>
              <li>Barro o encharcamiento en jardín</li>
              <li>Presión que baja de forma progresiva</li>
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
    <p class="zona-lbl">Causas locales</p>
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem">Por qu&eacute; aparecen fugas con frecuencia en Novelda</h3>
    <div class="zona-svc">
      <div class="zona-sc">
        <h3>Agua calcárea del Vinalopó</h3>
        <p>El agua de la comarca tiene una dureza elevada. El carbonato cálcico se deposita en el interior de las tuberías de cobre y en las juntas de PVC, adelgazando la pared del tubo y fragilizando los empalmes. Lo que en otra zona duraría quince años, aquí puede fallar en siete u ocho. Las viviendas sin descalcificador acusan este problema antes que las demás.</p>
      </div>
      <div class="zona-sc">
        <h3>Instalaciones antiguas en el centro histórico</h3>
        <p>Muchas viviendas del centro de Novelda tienen tuberías de hierro galvanizado o cobre original de los años setenta y ochenta. Estos materiales, combinados con el agua dura, muestran corrosión interna acelerada. Una tubería de hierro galvanizado de cuarenta años en Novelda está al límite de su vida útil — cualquier golpe de presión puede abrir una fisura.</p>
      </div>
      <div class="zona-sc">
        <h3>Vibración de maquinaria en la zona marmolera</h3>
        <p>El polígono industrial de Novelda concentra serradoras, pulidoras y maquinaria pesada de la industria del mármol. La vibración transmitida al suelo y a las edificaciones colindantes acelera el desgaste de los empalmes roscados y las soldaduras de tuberías en naves industriales y viviendas próximas al polígono. Las fugas en esta zona suelen aparecer en empalmes, no en tramos rectos.</p>
      </div>
      <div class="zona-sc">
        <h3>Chalets con jardín en La Mola y urbanizaciones</h3>
        <p>Las viviendas unifamiliares con jardín tienen circuitos de riego enterrados que pueden recorrer decenas de metros bajo tierra y césped. Cuando hay una pérdida, el terreno se encharca pero el punto exacto es invisible. A esto se suma la presión diferencial entre las zonas altas y bajas de la ciudad, que somete a mayor estrés las tuberías de las cotas más elevadas.</p>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Tecnología de detección</p>
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem">C&oacute;mo detectamos fugas sin obras en Novelda</h3>
    <div class="zona-svc">
      <div class="zona-sc">
        <h3>Geófono — tuberías metálicas y de hormigón</h3>
        <p>El geófono acústico capta el ruido de rozamiento que genera el agua al escapar por una fisura y lo amplifica. El técnico recorre el trazado de la tubería midiendo la intensidad de la señal hasta localizar el punto máximo — ahí está la fuga. Es el método más eficaz para tuberías de cobre, hierro y acero en el interior de las viviendas y en acometidas enterradas.</p>
      </div>
      <div class="zona-sc">
        <h3>Gas trazador — PVC, polietileno y riego</h3>
        <p>Para tuberías de PVC o polietileno que no conducen bien el sonido, inyectamos una mezcla de nitrógeno e hidrógeno inerte que escapa por la fisura y asciende hasta la superficie. El detector de gas localiza el punto exacto aunque la tubería esté a un metro de profundidad bajo tierra o bajo una solera de hormigón. Es el método habitual para circuitos de jardín y riego en chalets de La Mola.</p>
      </div>
      <div class="zona-sc">
        <h3>Termografía — suelo radiante y falsos techos</h3>
        <p>La cámara termográfica registra la temperatura superficial del suelo o del techo y detecta la zona donde el agua fría (o caliente si es calefacción) altera la temperatura del entorno. En un circuito de suelo radiante, la pérdida aparece como una mancha de temperatura diferente en el pavimento — señalamos el metro cuadrado exacto sin levantar nada. Tras la localización, solo se abre ese punto.</p>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Tarifas orientativas</p>
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem">Precio de detección de fugas en Novelda</h3>
    <div class="zona-precios">
      <div class="zona-precio-head">
        <span>Servicio</span><span>Precio orientativo</span>
      </div>
      <div class="zona-precio-row">
        <span>Detección con geófono</span><span>Desde 140 € (primera hora incluida)</span>
      </div>
      <div class="zona-precio-row">
        <span>Desplazamiento a Novelda</span><span>25 €</span>
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
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1rem;color:#fff">Detectamos fugas en toda Novelda</h3>
    <div class="zona-ztags">
      <span class="zona-ztag-plain">Centro histórico (CP 03660)</span>
      <span class="zona-ztag-plain">La Mola</span>
      <span class="zona-ztag-plain">Polígono industrial marmolero</span>
      <span class="zona-ztag-plain">Urbanizaciones periféricas</span>
      <span class="zona-ztag-plain">Chalets y viviendas unifamiliares</span>
      <span class="zona-ztag-plain">Comunidades de vecinos</span>
      <span class="zona-ztag-plain">Naves y pabellones industriales</span>
    </div>
  </div>
</section>

<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Preguntas frecuentes</p>
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem">Dudas habituales sobre fugas en Novelda</h3>
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
  $_ps->execute(['%Novelda%']);
  $_proy = $_ps->fetchAll(PDO::FETCH_ASSOC);
} catch (\Throwable $_e) {}
$_arts = [];
try {
  $_as = $pdo->prepare('SELECT titulo, slug, extracto, categoria, imagen FROM articulos WHERE publicado=1 AND (zona LIKE ? OR categoria LIKE ?) ORDER BY fecha DESC LIMIT 3');
  $_as->execute(['%Novelda%', '%fontan%']);
  $_arts = $_as->fetchAll(PDO::FETCH_ASSOC);
  if (empty($_arts)) {
    $_as2 = $pdo->query('SELECT titulo, slug, extracto, categoria, imagen FROM articulos WHERE publicado=1 ORDER BY fecha DESC LIMIT 3');
    $_arts = $_as2 ? $_as2->fetchAll(PDO::FETCH_ASSOC) : [];
  }
} catch (\Throwable $_e) {}
if (!empty($_proy)): ?>
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Trabajos realizados</p>
    <h2>Proyectos de detección de fugas <span class="hl">en Novelda</span></h2>
    <div class="zona-svc" style="margin-top:2rem">
      <?php foreach ($_proy as $_p): ?>
      <a href="/proyectos/<?php echo urlencode($_p['slug']); ?>" class="zona-sc">
        <?php if (!empty($_p['imagen'])): ?><img src="<?php echo htmlspecialchars($_p['imagen']); ?>" alt="<?php echo htmlspecialchars($_p['titulo']); ?>" loading="lazy" style="width:100%;height:160px;object-fit:cover;border-radius:8px;margin-bottom:.75rem"><?php endif; ?>
        <?php if ($_p['servicio']): ?><span class="zona-lbl" style="font-size:11px"><?php echo htmlspecialchars($_p['servicio']); ?></span><?php endif; ?>
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
<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Consejos &uacute;tiles</p>
    <h2>Art&iacute;culos sobre <span class="hl">detección de fugas</span></h2>
    <div class="zona-svc" style="margin-top:2rem">
      <?php foreach ($_arts as $_a): ?>
      <a href="/noticias/<?php echo urlencode($_a['slug']); ?>" class="zona-sc">
        <?php if (!empty($_a['imagen'])): ?><img src="<?php echo htmlspecialchars($_a['imagen']); ?>" alt="<?php echo htmlspecialchars($_a['titulo']); ?>" loading="lazy" style="width:100%;height:160px;object-fit:cover;border-radius:8px;margin-bottom:.75rem"><?php endif; ?>
        <?php if ($_a['categoria']): ?><span class="zona-lbl" style="font-size:11px"><?php echo htmlspecialchars($_a['categoria']); ?></span><?php endif; ?>
        <h3><?php echo htmlspecialchars($_a['titulo']); ?></h3>
        <p><?php echo htmlspecialchars(mb_substr($_a['extracto'] ?? '', 0, 100)); ?>...</p>
        <span class="zona-sc-a">Leer art&iacute;culo &rarr;</span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Zona de cobertura</p>
    <h2>Detección de fugas <span class="hl">en Novelda</span></h2>
    <p style="margin-bottom:1.5rem;color:#576574">Atendemos toda la localidad de Novelda (CP 03660).</p>
    <div style="border-radius:12px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.12)">
      <iframe src="https://maps.google.com/maps?q=38.3857,-0.7682&z=14&output=embed" width="100%" height="380" style="border:0;display:block" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Detección de fugas en Novelda"></iframe>
    </div>
  </div>
</section>
<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Mismo servicio en otras zonas</p>
    <h2>Tambi&eacute;n detectamos fugas <span class="hl">en otros municipios</span></h2>
    <div class="zona-ztags">
      <a href="/fontanero/novelda" class="zona-ztag" style="background:#1e3a5f;color:#fff">&#8592; Todos los servicios en Novelda</a>
      <a href="/fontanero/elda/busqueda_fugas" class="zona-ztag">Elda</a>
      <a href="/fontanero/petrer/busqueda_fugas" class="zona-ztag">Petrer</a>
      <a href="/fontanero/monovar/busqueda_fugas" class="zona-ztag">Mon&oacute;var</a>
      <a href="/fontanero/sax/busqueda_fugas" class="zona-ztag">Sax</a>
      <a href="/fontanero/pinoso/busqueda_fugas" class="zona-ztag">Pinoso</a>
      <a href="/fontanero/monforte-del-cid/busqueda_fugas" class="zona-ztag">Monforte del Cid</a>
      <a href="/fontanero/salinas/busqueda_fugas" class="zona-ztag">Salinas</a>
      <a href="/fontanero/aspe/busqueda_fugas" class="zona-ztag">Aspe</a>
    </div>
  </div>
</section>
<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>&iquest;Tienes una fuga en <span>Novelda?</span></h2>
    <p>Llamamos antes de abrir. Localizamos, presupuestamos y reparamos en la misma visita.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; 611 165 129</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">&#128172; WhatsApp</a>
    </div>
  </div>
</section>
<?php
$ciudad = 'novelda';
$servicio = 'fugas';
include '../../includes/resenas-section.php';
?>
<?php include '../../includes/galeria-section.php'; ?>
<?php include '../../includes/footer.php'; ?>
