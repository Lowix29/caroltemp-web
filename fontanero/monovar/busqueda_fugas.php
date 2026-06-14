<?php
/**
 * Detección de fugas en Monóvar
 */
$meta_title = 'Detección de Fugas en Monóvar Sin Obras 24h | CarolTemp';
$meta_desc  = 'Localización de fugas en Monóvar con geófono, termografía y gas trazador. Sin romper hasta confirmar el punto exacto. Desde 140 € · Urgencias 24 h · Instaladores Nubeco.';
$meta_url   = 'https://caroltemp.com/fontanero/monovar/busqueda_fugas';
$schema_type = 'local';
$page_css   = 'zona';
$page_js    = 'zona';

$faq_items = [
  [
    'q' => '¿Cuánto cuesta detectar una fuga de agua en Monóvar?',
    'a' => 'La detección con geófono profesional parte desde 140 € con la primera hora incluida. El precio final depende del tipo de instalación (vivienda urbana, finca rural con grupo de presión, circuito de riego enterrado, suelo radiante) y de la accesibilidad de la tubería. El desplazamiento a Monóvar y sus pedanías son 25 € fijos. Damos presupuesto cerrado antes de empezar — sin costes ocultos.',
  ],
  [
    'q' => '¿Cuánto tiempo tarda la detección y la reparación en Monóvar?',
    'a' => 'La localización con geófono lleva entre 1 y 3 horas según la longitud del circuito y el tipo de tubería. En fincas con tuberías enterradas largas usamos también el correlador, que puede añadir media hora más. Si la tubería afectada es accesible, la reparación se completa en la misma jornada. Para obras que requieren abrir suelo o pared el plazo habitual es de 24 a 48 horas. Atendemos urgencias cualquier día del año — máx. 3 horas desde el contacto.',
  ],
  [
    'q' => '¿Qué pasa si no encontráis la fuga en mi vivienda de Monóvar?',
    'a' => 'Es una situación infrecuente con el equipo que usamos, pero si ocurre lo comunicamos de inmediato y no cobramos el servicio de localización. Antes de cerrar la visita siempre comprobamos la presión del circuito para confirmar que la fuga sigue activa y no se ha cerrado sola (por ejemplo, en fugas intermitentes por dilatación térmica). Si la fuga es intermitente pactamos una segunda visita sin coste adicional.',
  ],
  [
    'q' => '¿Pueden detectar la fuga sin romper paredes ni pavimento?',
    'a' => 'Sí, en la gran mayoría de casos. El geófono capta el sonido de la pérdida a través de la estructura y marca el punto exacto. La termografía detecta la zona húmeda en suelo radiante o falsos techos sin tocar nada. El gas trazador localiza fugas en PVC o polietileno enterrado aunque estén a un metro de profundidad bajo tierra o bajo una solera. Solo se abre donde está la rotura confirmada.',
  ],
  [
    'q' => '¿Cómo sé si tengo una fuga oculta en mi casa o finca de Monóvar?',
    'a' => 'Las señales más claras son: la factura de agua sube sin explicación, el contador de red gira con todas las llaves cerradas, el grupo de presión arranca con demasiada frecuencia o no para, aparece humedad en paredes o suelos sin lluvia reciente, o se escucha un goteo continuo con todo cerrado. En fincas sin contador de red, el indicador más fiable es el grupo de presión trabajando en ciclos cortos o sin llegar a cortar.',
  ],
];

include '../../includes/head.php';
?>

<?php
$_hi     = getHeroImagen('fugas-monovar');
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
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Detección de fugas &middot; Mon&oacute;var &middot; CP 03640</div>
    <h1>Detección de fugas en Monóvar<br><span class="hl">localizamos la pérdida antes de abrir nada</span></h1>
    <p class="hz-dark-sub">El geófono escucha la tubería y el correlador calcula el punto exacto — en fincas con instalación propia, viviendas del casco histórico y circuitos de riego enterrados. Solo se excava donde está la rotura confirmada.</p>
    <div class="hz-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; 611 165 129</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">&#128172; WhatsApp</a>
    </div>
  </div>
</section>

<div class="dif-strip">
  <div class="dif-strip-in">
    <div class="dif-item"><span class="dif-val">Geófono y correlador</span><span class="dif-lbl">Tuberías enterradas en fincas y parcelas</span></div>
    <div class="dif-item"><span class="dif-val">Termografía infrarroja</span><span class="dif-lbl">Suelo radiante, falsos techos y bajantes</span></div>
    <div class="dif-item"><span class="dif-val">Gas trazador</span><span class="dif-lbl">PVC y polietileno en circuitos de riego</span></div>
    <div class="dif-item"><span class="dif-val">Desde 140 €</span><span class="dif-lbl">Primera hora incluida · desplazamiento 25 €</span></div>
  </div>
</div>

<section class="zona-sec">
  <div class="cta-dark-con">
    <div class="zona-tcol">
      <div>
        <p class="zona-lbl">Detección de fugas en Mon&oacute;var</p>
        <h2>Detección de fugas urgente <span class="hl">24 horas en Monóvar sin obras</span></h2>
        <div class="zona-prose">
          <p>Monóvar combina dos realidades que complican la detección de fugas: el casco histórico, con viviendas de piedra y tuberías de hierro galvanizado de los años sesenta y setenta, y el extrarradio rural, donde cientos de fincas funcionan con depósito propio, grupo de presión y tuberías de polietileno enterradas en parcelas de varios miles de metros cuadrados. En ambos casos la fuga es invisible desde fuera — en el casco, el agua se pierde en la oquedad de muros gruesos de mampostería; en las fincas, desaparece en el subsuelo sin dejar rastro salvo que el terreno empiece a encharcarse o el grupo de presión arranque sin parar.</p>
          <p>El clima semiárido del Vinalopó añade otro factor: el contraste térmico entre inviernos con heladas nocturnas y veranos de 40 °C provoca ciclos de dilatación y contracción que agrietan empalmes y juntas año tras año. Lo que en una zona más templada duraría veinte años, aquí puede empezar a fallar a los doce o quince. Con geófono acústico, correlador de fugas, termografía y gas trazador localizamos la pérdida sin abrir nada — y solo se interviene donde está la rotura confirmada.</p>
        </div>
        <ul class="zona-chk">
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en fincas con depósito propio y grupo de presión</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en tuberías enterradas de riego y acometidas en parcelas</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en viviendas del casco histórico con tuberías de hierro</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en circuitos de suelo radiante y calefacción</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en bajantes y columnas de comunidades de vecinos</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en bodegas, naves agrícolas y explotaciones del término</li>
        </ul>
      </div>
      <div>
        <div class="icard">
          <div class="icard-head"><strong>CarolTemp &middot; Mon&oacute;var</strong><span>Detección de fugas</span></div>
          <div class="icard-body">
            <ul>
              <li>Factura de agua sube sin explicación</li>
              <li>Grupo de presión arranca sin parar</li>
              <li>Contador gira con llaves cerradas</li>
              <li>Humedad en muro o suelo sin lluvia</li>
              <li>Ruido de agua con todo cerrado</li>
              <li>Terreno encharcado en parcela o jardín</li>
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
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem">Por qu&eacute; aparecen fugas con frecuencia en Mon&oacute;var</h3>
    <div class="zona-svc">
      <div class="zona-sc">
        <h3>Clima semiárido: ciclos térmicos que agrietan empalmes</h3>
        <p>El altiplano del Vinalopó registra heladas en invierno y temperaturas de 40 °C en verano. Ese contraste somete a las tuberías a ciclos continuos de dilatación y contracción que agrietan empalmes roscados, juntas de cobre y puntos de soldadura. Las tuberías más expuestas — las exteriores de jardín y las acometidas enterradas a poca profundidad — son las primeras en ceder.</p>
      </div>
      <div class="zona-sc">
        <h3>Instalaciones rurales con agua de pozo</h3>
        <p>Muchas fincas del término de Monóvar y sus pedanías (La Romaneta, El Raspay, Salse) no están conectadas a la red municipal o solo lo están parcialmente. Funcionan con pozo o depósito propio y grupo de presión. Cuando hay una fuga en el circuito de distribución enterrado, no aparece en ninguna factura — solo en el grupo que no para de arrancar o en el nivel del depósito que baja más deprisa de lo esperado.</p>
      </div>
      <div class="zona-sc">
        <h3>Casco histórico: hierro galvanizado de los años 70</h3>
        <p>El centro de Monóvar tiene viviendas con tuberías de hierro galvanizado originales de los años sesenta y setenta. La corrosión interna adelgaza la pared del tubo y genera óxido que acaba perforando el metal. Estas fugas aparecen como manchas oscuras en paredes de piedra o como bajadas repentinas de presión en toda la planta — señal de que el tramo ya ha perdido sección.</p>
      </div>
      <div class="zona-sc">
        <h3>Viñedos y cultivos: tuberías de riego enterradas</h3>
        <p>Las explotaciones agrícolas del término — viñedos, almendros, olivos — tienen redes de riego de polietileno enterradas a poca profundidad. El paso de tractores y maquinaria agrícola, los movimientos de terreno en época seca y los roedores pueden perforar o desconectar uniones. Una fuga en estos circuitos puede desperdiciarse durante semanas sin que nadie lo note hasta que el consumo de agua del pozo sube de forma llamativa.</p>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Tecnología de detección</p>
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem">C&oacute;mo detectamos fugas sin obras en Mon&oacute;var</h3>
    <div class="zona-svc">
      <div class="zona-sc">
        <h3>Geófono y correlador — tuberías metálicas y acometidas</h3>
        <p>El geófono acústico capta el sonido de la pérdida a través del suelo o de la estructura y lo amplifica. El técnico recorre el trazado de la tubería midiendo la señal hasta localizar el punto máximo — ahí está la fuga. Para fincas con tramos enterrados de decenas de metros, el correlador compara la señal en dos puntos del circuito y calcula matemáticamente el metro exacto de la pérdida sin abrir ninguna zanja. Es el método principal para acometidas de hierro o cobre en el casco y para grupos de presión en fincas rurales.</p>
      </div>
      <div class="zona-sc">
        <h3>Gas trazador — PVC y polietileno en circuitos de riego</h3>
        <p>Las tuberías de plástico no conducen el sonido con la misma eficacia que el metal. Para circuitos de PVC o polietileno de riego inyectamos nitrógeno con trazador de hidrógeno: la mezcla asciende hasta la superficie por el punto de la fisura y el detector la capta aunque la tubería esté enterrada a un metro. Ideal para redes de riego en viñedos, huertos y jardines de chalets en urbanizaciones del término.</p>
      </div>
      <div class="zona-sc">
        <h3>Termografía — suelo radiante y falsos techos</h3>
        <p>La cámara termográfica registra diferencias de temperatura en la superficie del suelo o del techo. En un circuito de suelo radiante con pérdida, el agua altera la temperatura en el metro cuadrado exacto de la fisura — la cámara lo visualiza sin levantar nada. También es eficaz para detectar filtraciones en falsos techos de viviendas de comunidad en el centro de Monóvar, donde la fuga de un piso superior llega al techo del inferior.</p>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Tarifas orientativas</p>
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem">Precio de detección de fugas en Mon&oacute;var</h3>
    <div class="zona-precios">
      <div class="zona-precio-head">
        <span>Servicio</span><span>Precio orientativo</span>
      </div>
      <div class="zona-precio-row">
        <span>Detección con geófono</span><span>Desde 140 € (primera hora incluida)</span>
      </div>
      <div class="zona-precio-row">
        <span>Desplazamiento a Monóvar y pedanías</span><span>25 €</span>
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
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1rem;color:#fff">Detectamos fugas en todo el término de Mon&oacute;var</h3>
    <div class="zona-ztags">
      <span class="zona-ztag-plain">Casco hist&oacute;rico (CP 03640)</span>
      <span class="zona-ztag-plain">La Romaneta</span>
      <span class="zona-ztag-plain">El Raspay</span>
      <span class="zona-ztag-plain">Salse</span>
      <span class="zona-ztag-plain">Fincas y parcelas rurales</span>
      <span class="zona-ztag-plain">Urbanizaciones</span>
      <span class="zona-ztag-plain">Chalets y viviendas unifamiliares</span>
      <span class="zona-ztag-plain">Bodegas y explotaciones agr&iacute;colas</span>
    </div>
  </div>
</section>

<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Preguntas frecuentes</p>
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem">Dudas habituales sobre fugas en Mon&oacute;var</h3>
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
  $_ps->execute(['%Mon%var%']);
  $_proy = $_ps->fetchAll(PDO::FETCH_ASSOC);
} catch (\Throwable $_e) {}
$_arts = [];
try {
  $_as = $pdo->prepare('SELECT titulo, slug, extracto, categoria, imagen FROM articulos WHERE publicado=1 AND (zona LIKE ? OR categoria LIKE ?) ORDER BY fecha DESC LIMIT 3');
  $_as->execute(['%Mon%var%', '%fontan%']);
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
    <h2>Proyectos de detección de fugas <span class="hl">en Mon&oacute;var</span></h2>
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
    <h2>Detección de fugas <span class="hl">en Mon&oacute;var</span></h2>
    <p style="margin-bottom:1.5rem;color:#576574">Atendemos toda la localidad de Mon&oacute;var (CP 03640) y sus ped&aacute;n&iacute;as.</p>
    <div style="border-radius:12px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.12)">
      <iframe src="https://maps.google.com/maps?q=38.4350,-0.8452&z=14&output=embed" width="100%" height="380" style="border:0;display:block" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Detección de fugas en Monóvar"></iframe>
    </div>
  </div>
</section>
<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Mismo servicio en otras zonas</p>
    <h2>Tambi&eacute;n detectamos fugas <span class="hl">en otros municipios</span></h2>
    <div class="zona-ztags">
      <a href="/fontanero/monovar" class="zona-ztag" style="background:#1e3a5f;color:#fff">&#8592; Todos los servicios en Mon&oacute;var</a>
      <a href="/fontanero/elda/busqueda_fugas" class="zona-ztag">Elda</a>
      <a href="/fontanero/petrer/busqueda_fugas" class="zona-ztag">Petrer</a>
      <a href="/fontanero/novelda/busqueda_fugas" class="zona-ztag">Novelda</a>
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
    <h2>&iquest;Tienes una fuga en <span>Mon&oacute;var?</span></h2>
    <p>Llamamos antes de abrir. Localizamos, presupuestamos y reparamos en la misma visita.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; 611 165 129</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">&#128172; WhatsApp</a>
    </div>
  </div>
</section>
<?php
$ciudad = 'monovar';
$servicio = 'fugas';
include '../../includes/resenas-section.php';
?>
<?php include '../../includes/footer.php'; ?>
