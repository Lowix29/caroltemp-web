<?php
/**
 * Detección de fugas en Pinoso / El Pinos
 */
$meta_title = 'Detección de Fugas en Pinoso Sin Obras 24h | CarolTemp';
$meta_desc  = 'Localización de fugas en Pinoso con geófono, termografía y gas trazador. Sin romper hasta confirmar el punto. Desde 140 € · Urgencias 24 h · Instaladores Nubeco.';
$meta_url   = 'https://caroltemp.com/fontanero/pinoso/busqueda_fugas';
$schema_type = 'local';
$page_css   = 'zona';
$page_js    = 'zona';

$faq_items = [
  [
    'q' => '¿Cuánto cuesta detectar una fuga de agua en Pinoso?',
    'a' => 'La detección con geófono parte desde 140 € con la primera hora incluida. El precio final depende del tipo de instalación (vivienda del casco, finca rural, chalet con jardín, comunidad de vecinos), la accesibilidad de la tubería y si es necesario combinar geófono con gas trazador o termografía. El desplazamiento a Pinoso y sus pedanías son 25 € fijos. Damos presupuesto confirmado antes de empezar.',
  ],
  [
    'q' => '¿Cuánto tiempo tardan en localizar una fuga sin romper en Pinoso?',
    'a' => 'En una vivienda unifamiliar estándar la localización lleva entre 1 y 2 horas. En fincas rurales con circuitos de riego enterrados largos o en comunidades de vecinos con redes de bajantes puede necesitar entre 2 y 3 horas. Si la tubería es accesible, la reparación se completa en la misma jornada. Atendemos urgencias todos los días del año — máx. 3 horas desde el contacto a Pinoso y sus pedanías.',
  ],
  [
    'q' => '¿Qué tecnología es mejor para detectar fugas en Pinoso según el tipo de instalación?',
    'a' => 'Depende del material de la tubería y de dónde esté. El geófono es el método principal para tuberías de cobre, hierro o acero empotradas en paredes o acometidas enterradas — capta el sonido de la pérdida bajo presión. Para tuberías de PVC o polietileno de riego (muy habituales en las fincas de Pinoso) usamos gas trazador, que encuentra la fisura aunque el tubo esté a un metro de profundidad. La termografía es el método de confirmación para suelo radiante y falsos techos.',
  ],
  [
    'q' => '¿Las fugas de agua son más frecuentes en viviendas antiguas de Pinoso?',
    'a' => 'Sí. Las viviendas del casco urbano de Pinoso y las fincas rurales con más antigüedad suelen tener tuberías de hierro galvanizado que llevan décadas bajo el efecto del agua dura de la comarca. La cal se deposita en el interior del tubo y la corrosión interna adelgaza las paredes hasta perforarlas. A eso se suma el contraste térmico del clima semiárido — heladas nocturnas en invierno y calor intenso en verano — que agrieta empalmes y juntas año tras año.',
  ],
  [
    'q' => '¿Puedo reclamar el coste de la detección al seguro del hogar en Pinoso?',
    'a' => 'Muchas pólizas cubren los daños materiales causados por una fuga oculta, pero la aseguradora exige documentación técnica que acredite el origen. El informe que emitimos describe el método de detección empleado, el punto exacto localizado y el resultado de las pruebas — es el documento estándar que necesitas para presentar el siniestro con garantías. Sin ese informe, la reclamación puede demorarse o denegarse por falta de prueba técnica.',
  ],
];

include '../../includes/head.php';
?>

<?php
$_hi     = getHeroImagen('fugas-pinoso');
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
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Detección de fugas &middot; Pinoso / El Pinos &middot; CP 03650</div>
    <h1>Detección de fugas en Pinoso<br><span class="hl">localizamos la pérdida sin romper paredes ni suelos</span></h1>
    <p class="hz-dark-sub">El geófono escucha la tubería, el gas trazador encuentra la fisura en PVC enterrado, la termografía visualiza el suelo radiante. Se usa el equipo correcto según el tipo de instalación — y solo se abre donde está la rotura confirmada.</p>
    <div class="hz-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; 611 165 129</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">&#128172; WhatsApp</a>
    </div>
  </div>
</section>

<div class="dif-strip">
  <div class="dif-strip-in">
    <div class="dif-item"><span class="dif-val">Geófono y correlador</span><span class="dif-lbl">Tuberías metálicas y acometidas</span></div>
    <div class="dif-item"><span class="dif-val">Termografía infrarroja</span><span class="dif-lbl">Suelo radiante, falsos techos y bajantes</span></div>
    <div class="dif-item"><span class="dif-val">Gas trazador</span><span class="dif-lbl">PVC y polietileno en riego y fincas</span></div>
    <div class="dif-item"><span class="dif-val">Desde 140 €</span><span class="dif-lbl">Primera hora incluida · desplazamiento 25 €</span></div>
  </div>
</div>

<section class="zona-sec">
  <div class="cta-dark-con">
    <div class="zona-tcol">
      <div>
        <p class="zona-lbl">Detección de fugas en Pinoso · El Pinos</p>
        <h2>Detección de fugas urgente <span class="hl">24 horas en Pinoso sin obras</span></h2>
        <div class="zona-prose">
          <p>Pinoso combina el casco urbano con una extensión rural considerable: viñedos, almendros y fincas con instalaciones de riego que pueden recorrer centenares de metros enterradas bajo tierra. El agua dura de la comarca y el clima semiárido del altiplano — heladas nocturnas en invierno, más de 38 °C en verano — someten a las tuberías a ciclos de contracción y dilatación que agrietan empalmes y juntas antes de lo esperado. Las instalaciones de hierro galvanizado del casco histórico, con décadas de cal acumulada en su interior, son especialmente vulnerables.</p>
          <p>Para cada tipo de fuga hay un método: el <strong>geófono</strong> localiza el punto de pérdida en tuberías metálicas empotradas o en acometidas; el <strong>correlador</strong> calcula el metro exacto en tramos enterrados largos sin excavar; el <strong>gas trazador</strong> encuentra fisuras en circuitos de PVC o polietileno de riego aunque estén a un metro de profundidad bajo tierra; la <strong>termografía</strong> visualiza suelo radiante y falsos techos sin levantar nada. Después de la localización, los instaladores certificados Nubeco pueden ejecutar la reparación en la misma visita si la tubería es accesible.</p>
        </div>
        <ul class="zona-chk">
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en tuberías empotradas de viviendas del casco</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en circuitos de riego enterrados en fincas y viñedos</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en circuitos de suelo radiante bajo el pavimento</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en bajantes y columnas de comunidades de vecinos</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en acometidas exteriores de chalets y unifamiliares</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en instalaciones de naves y locales comerciales</li>
        </ul>
      </div>
      <div>
        <div class="icard">
          <div class="icard-head"><strong>CarolTemp &middot; Pinoso</strong><span>Detección de fugas</span></div>
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
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem">Por qué aparecen fugas con frecuencia en Pinoso</h3>
    <div class="zona-svc">
      <div class="zona-sc">
        <h3>Clima semiárido y contraste térmico</h3>
        <p>El altiplano de Pinoso registra heladas en invierno y temperaturas superiores a 38 °C en verano. Ese contraste somete a las tuberías a ciclos continuos de dilatación y contracción que agrietan empalmes roscados y juntas de soldadura. Las tuberías más expuestas — las exteriores en jardín y las acometidas enterradas a poca profundidad — son las primeras en ceder, especialmente después de inviernos con heladas repetidas.</p>
      </div>
      <div class="zona-sc">
        <h3>Agua dura y tuberías de hierro antiguas</h3>
        <p>Las viviendas del casco de Pinoso y de las pedanías más antiguas tienen instalaciones de hierro galvanizado de los años sesenta y setenta. El agua dura de la comarca deposita cal en el interior del tubo y la corrosión interna adelgaza las paredes hasta perforarlas. Lo que en una zona con agua blanda duraría veinte años, aquí puede fallar a los diez o doce. La señal habitual es el contador que no para de noche o una mancha que reaparece en el mismo sitio cada pocos meses.</p>
      </div>
      <div class="zona-sc">
        <h3>Fincas rurales con riego enterrado</h3>
        <p>El término de Pinoso tiene una extensión agrícola importante: viñedos, almendros, olivares con redes de riego de polietileno enterradas bajo tierra. El paso de maquinaria agrícola, los movimientos de terreno en época seca y los roedores pueden perforar o desconectar uniones en estos circuitos de plástico. Para estas fugas el geófono no es suficiente — usamos gas trazador, que asciende exactamente por el punto de la fisura aunque la tubería esté a un metro de profundidad.</p>
      </div>
      <div class="zona-sc">
        <h3>Pedanías con instalaciones más antiguas</h3>
        <p>Las pedanías del término de Pinoso — El Lel, Ubeda, Culebrón, Las Encebras — tienen en muchos casos instalaciones de suministro más antiguas que el casco urbano y, en algunos casos, depósitos propios con grupo de presión. Cuando hay una fuga en el circuito de distribución de una finca con depósito propio, no aparece en ninguna factura de red: solo el grupo que arranca sin parar o el nivel del depósito que baja más deprisa de lo normal son la señal de alarma.</p>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Tecnología y proceso</p>
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem">Cómo detectamos fugas sin obras en Pinoso</h3>
    <div class="zona-steps">
      <div class="zona-step">
        <div class="zona-step-n">1</div>
        <div class="zona-step-txt">
          <strong>Geófono o correlador — escuchamos la tubería</strong>
          <p>El geófono amplifica el sonido del agua escapando bajo presión y el técnico recorre el trazado metro a metro. Para fincas con tramos enterrados largos, el correlador compara la señal en dos puntos y calcula el metro exacto de la pérdida matemáticamente. Funciona en tuberías de cobre, hierro y acero.</p>
        </div>
      </div>
      <div class="zona-step">
        <div class="zona-step-n">2</div>
        <div class="zona-step-txt">
          <strong>Gas trazador o termografía — confirmamos el punto</strong>
          <p>Para PVC y polietileno de riego, el gas trazador (nitrógeno con hidrógeno inerte) asciende por el punto de la fisura y el detector lo localiza en superficie. Para suelo radiante y falsos techos, la termografía visualiza la diferencia de temperatura exactamente en el metro cuadrado de la rotura.</p>
        </div>
      </div>
      <div class="zona-step">
        <div class="zona-step-n">3</div>
        <div class="zona-step-txt">
          <strong>Presupuesto antes de abrir</strong>
          <p>Marcamos el punto exacto y damos presupuesto de reparación antes de tocar nada. Los instaladores Nubeco certificados pueden ejecutar la reparación en la misma visita si la tubería es accesible. Emitimos el informe técnico de localización válido para el seguro del hogar.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Tarifas orientativas</p>
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem">Precio de detección de fugas en Pinoso</h3>
    <div class="zona-precios">
      <div class="zona-precio-head">
        <span>Servicio</span><span>Precio orientativo</span>
      </div>
      <div class="zona-precio-row">
        <span>Detección con geófono</span><span>Desde 140 € (primera hora incluida)</span>
      </div>
      <div class="zona-precio-row">
        <span>Desplazamiento a Pinoso y pedanías</span><span>25 €</span>
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
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1rem;color:#fff">Detectamos fugas en todo el término de Pinoso</h3>
    <div class="zona-ztags">
      <span class="zona-ztag-plain">Casco urbano / El Pinos (CP 03650)</span>
      <span class="zona-ztag-plain">El Lel</span>
      <span class="zona-ztag-plain">Ubeda</span>
      <span class="zona-ztag-plain">Culeb&oacute;n</span>
      <span class="zona-ztag-plain">Las Encebras</span>
      <span class="zona-ztag-plain">Fincas y parcelas rurales</span>
      <span class="zona-ztag-plain">Urbanizaciones perif&eacute;ricas</span>
    </div>
  </div>
</section>

<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Preguntas frecuentes</p>
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem">Dudas habituales sobre fugas en Pinoso</h3>
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
  $_ps->execute(['%Pinoso%']);
  $_proy = $_ps->fetchAll(PDO::FETCH_ASSOC);
  if (empty($_proy)) {
    $_ps2 = $pdo->query('SELECT titulo, slug, descripcion, servicio, imagen FROM proyectos WHERE publicado=1 ORDER BY fecha DESC LIMIT 3');
    $_proy = $_ps2 ? $_ps2->fetchAll(PDO::FETCH_ASSOC) : [];
  }
} catch (\Throwable $_e) {}
$_arts = [];
try {
  $_as = $pdo->prepare('SELECT titulo, slug, extracto, categoria, imagen FROM articulos WHERE publicado=1 AND (zona LIKE ? OR categoria LIKE ?) ORDER BY fecha DESC LIMIT 3');
  $_as->execute(['%Pinoso%', '%fontan%']);
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
    <h2>Proyectos de detección de fugas <span class="hl">en Pinoso</span></h2>
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
    <h2>Detección de fugas <span class="hl">en Pinoso</span></h2>
    <p style="margin-bottom:1.5rem;color:#576574">Atendemos toda la localidad de Pinoso (CP 03650) y sus ped&aacute;n&iacute;as.</p>
    <div style="border-radius:12px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.12)">
      <iframe src="https://maps.google.com/maps?q=38.4054,-1.0397&z=14&output=embed" width="100%" height="380" style="border:0;display:block" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Detección de fugas en Pinoso"></iframe>
    </div>
  </div>
</section>
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Mismo servicio en otras zonas</p>
    <h2>Tambi&eacute;n detectamos fugas <span class="hl">en otros municipios</span></h2>
    <div class="zona-ztags">
      <a href="/fontanero/pinoso" class="zona-ztag" style="background:#1e3a5f;color:#fff">&#8592; Todos los servicios en Pinoso</a>
      <a href="/fontanero/elda/busqueda_fugas" class="zona-ztag">Elda</a>
      <a href="/fontanero/novelda/busqueda_fugas" class="zona-ztag">Novelda</a>
      <a href="/fontanero/monovar/busqueda_fugas" class="zona-ztag">Mon&oacute;var</a>
      <a href="/fontanero/sax/busqueda_fugas" class="zona-ztag">Sax</a>
      <a href="/fontanero/aspe/busqueda_fugas" class="zona-ztag">Aspe</a>
      <a href="/fontanero/monforte-del-cid/busqueda_fugas" class="zona-ztag">Monforte del Cid</a>
      <a href="/fontanero/salinas/busqueda_fugas" class="zona-ztag">Salinas</a>
      <a href="/fontanero/petrer/busqueda_fugas" class="zona-ztag">Petrer</a>
    </div>
  </div>
</section>
<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>&iquest;Tienes una fuga en <span>Pinoso?</span></h2>
    <p>Llamamos antes de abrir. Localizamos, presupuestamos y reparamos en la misma visita.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; 611 165 129</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">&#128172; WhatsApp</a>
    </div>
  </div>
</section>
<?php
$ciudad = 'pinoso';
$servicio = 'fugas';
include '../../includes/resenas-section.php';
?>
<?php include '../../includes/galeria-section.php'; ?>
<?php include '../../includes/footer.php'; ?>
