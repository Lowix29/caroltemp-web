<?php
/**
 * Detección de fugas en Sax
 */
$meta_title = 'Detección de Fugas en Sax Sin Obras 24h | CarolTemp';
$meta_desc  = 'Localización de fugas en Sax con geófono, termografía y gas trazador. Sin romper hasta confirmar el punto exacto. Desde 140 € · Urgencias 24 h · Casas antiguas y chalets.';
$meta_url   = 'https://caroltemp.com/fontanero/sax/busqueda_fugas';
$schema_type = 'local';
$page_css   = 'zona';
$page_js    = 'zona';

$faq_items = [
  [
    'q' => '¿Cuánto cuesta detectar una fuga de agua en Sax sin romper?',
    'a' => 'La detección con geófono parte desde 140 € con la primera hora incluida. El precio final depende del tipo de instalación (vivienda unifamiliar, piso en comunidad, chalet con jardín), la accesibilidad de la tubería y si es necesario usar también termografía o gas trazador. El desplazamiento a Sax son 25 € fijos. Damos presupuesto antes de empezar — sin sorpresas.',
  ],
  [
    'q' => '¿En cuánto tiempo podéis localizar una fuga en mi casa de Sax?',
    'a' => 'Nos desplazamos a Sax en el plazo máximo de 3 horas desde el contacto. La localización en sí lleva entre 1 y 2 horas en una vivienda unifamiliar estándar, y entre 2 y 4 horas en instalaciones más complejas (finca con circuito de riego largo, suelo radiante en toda la planta). Si la tubería es accesible, la reparación puede completarse en la misma jornada.',
  ],
  [
    'q' => '¿Funcionan estas técnicas en casas antiguas del centro de Sax?',
    'a' => 'Sí, y el geófono es especialmente útil en casas de piedra con muros gruesos: el sonido de la fuga se propaga a través de la mampostería y el técnico lo detecta desde el exterior sin necesidad de acceder a la tubería. En muros muy gruesos o con cámaras de aire el proceso puede tardar un poco más, pero la localización es igual de precisa. La termografía complementa el geófono cuando la humedad ya ha saturado una zona de la pared.',
  ],
  [
    'q' => '¿Qué hago si detecto humedad pero no sé si es una fuga o condensación?',
    'a' => 'La cámara termográfica diferencia ambas situaciones en minutos: la condensación aparece en puntos fríos (puentes térmicos, esquinas sin aislamiento), mientras que una fuga activa genera un foco de humedad con temperatura diferente al entorno circundante. En Sax, la proximidad al río Vinalopó puede favorecer algo de humedad ambiental en invierno — la termografía descarta esa causa antes de buscar ninguna tubería. Si hay duda, lo verificamos sin coste de diagnóstico previo.',
  ],
  [
    'q' => '¿La detección con gas trazador es segura para mi familia y mascotas?',
    'a' => 'Sí. El gas trazador que usamos es una mezcla de nitrógeno (95%) e hidrógeno (5%), completamente inerte y no tóxico. No es inflamable a esa concentración, no deja residuo y se disipa en minutos una vez localizada la fuga. Es el método estándar en detección no invasiva y no requiere ninguna precaución especial para personas, animales de compañía ni plantas.',
  ],
];

include '../../includes/head.php';
?>

<?php
$_hi     = getHeroImagen('fugas-sax');
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
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Detección de fugas &middot; Sax &middot; CP 03630</div>
    <h1>Detección de fugas en Sax<br><span class="hl">localizamos la pérdida sin romper paredes ni suelos</span></h1>
    <p class="hz-dark-sub">Primero el geófono escucha la tubería. Luego la termografía confirma el punto. Solo cuando ambas técnicas coinciden marcamos dónde abrir — y no antes. Servicio urgente disponible todos los días del año.</p>
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
        <p class="zona-lbl">Detección de fugas en Sax</p>
        <h2>Detección de fugas urgente <span class="hl">24 horas en Sax sin obras</span></h2>
        <div class="zona-prose">
          <p>Una fuga de tres litros por hora pasa desapercibida en la vida diaria — no hay mancha, no hay ruido apreciable — pero en un mes son más de dos mil litros perdidos bajo el suelo o dentro de la pared. El casco antiguo de Sax concentra viviendas con tuberías de hierro galvanizado de los años sesenta y setenta: la corrosión interna perfora el metal sin señal exterior visible hasta que la humedad lleva semanas saturando los muros de piedra. En las urbanizaciones con chalé, el problema habitual son los circuitos de riego enterrados en jardín que se rompen con las heladas invernales o con el paso de maquinaria de jardinería.</p>
          <p>Con geófono acústico y cámara termográfica localizamos la pérdida exacta antes de abrir nada. En circuitos de PVC o polietileno de riego donde el geófono no es suficiente usamos gas trazador, que asciende hasta la superficie por el punto de la fisura aunque la tubería esté enterrada a un metro de profundidad. Solo se interviene donde está la rotura confirmada — sin excavaciones a ciegas, sin levantar solería en toda la habitación.</p>
        </div>
        <ul class="zona-chk">
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en viviendas del casco antiguo con tuberías de hierro</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en tuberías enterradas y circuitos de riego en chalets</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en circuitos de suelo radiante bajo el pavimento</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en bajantes y columnas de comunidades de vecinos</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en calentadores y juntas con incrustaciones de cal</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fugas en acometidas exteriores y arquetas en fincas</li>
        </ul>
      </div>
      <div>
        <div class="icard">
          <div class="icard-head"><strong>CarolTemp &middot; Sax</strong><span>Detección de fugas</span></div>
          <div class="icard-body">
            <ul>
              <li>Factura de agua sube sin explicación</li>
              <li>Contador gira con todas las llaves cerradas</li>
              <li>Humedad en pared, suelo o techo sin lluvia</li>
              <li>Ruido de agua con todo cerrado</li>
              <li>Suelo tibio en zona sin suelo radiante</li>
              <li>Tu vecino tiene humedad en su techo</li>
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
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem">Fugas más comunes por tipo de vivienda en Sax</h3>
    <div class="zona-svc">
      <div class="zona-sc">
        <h3>Casas de piedra del casco antiguo</h3>
        <p>El centro histórico de Sax concentra viviendas con muros de mampostería y tuberías de hierro galvanizado de los años sesenta y setenta. La corrosión interna crea fisuras que el agua tarda meses en atravesar hasta la superficie — cuando la mancha aparece, el muro lleva tiempo húmedo. El geófono detecta el punto exacto a través de la piedra sin necesidad de picar la pared entera. En muros con cámara de aire el proceso tarda un poco más, pero la precisión es la misma.</p>
      </div>
      <div class="zona-sc">
        <h3>Chalets y unifamiliares con jardín</h3>
        <p>Las urbanizaciones de Sax con chalets tienen circuitos de riego de polietileno enterrados que pueden recorrer decenas de metros bajo tierra y césped. Las heladas invernales del altiplano del Vinalopó provocan expansión de las juntas y aperturas en los empalmes. Cuando hay pérdida, el terreno se encharca semanas antes de que nadie lo note. Para estos circuitos de plástico usamos gas trazador: el nitrógeno con trazador de hidrógeno asciende exactamente por el punto de la fisura y el detector lo capta en superficie.</p>
      </div>
      <div class="zona-sc">
        <h3>Viviendas con suelo radiante</h3>
        <p>El suelo radiante recorre toda la planta bajo el pavimento. Una microfisura en el circuito pierde presión de forma gradual — la calefacción pierde eficiencia, la caldera trabaja más de lo normal y la factura del agua sube poco a poco. La cámara termográfica visualiza la diferencia de temperatura en el punto exacto de la pérdida sin levantar ningún metro de solería. Tras localizar la zona, solo se abre ese punto concreto para acceder al tubo afectado.</p>
      </div>
      <div class="zona-sc">
        <h3>Comunidades de vecinos</h3>
        <p>En bloques de pisos, las bajantes recorren el interior de las paredes compartidas entre vecinos. Una fuga en una bajante empotrada genera humedad en el cuarto de baño del piso inferior. Con geófono y cámara de inspección localizamos el tramo exacto sin abrir paredes de varios pisos — solo se interviene en el punto de la rotura, lo que reduce enormemente el coste de obra en zonas comunes.</p>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Tecnología y proceso</p>
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem">Cómo detectamos fugas sin obras en Sax</h3>
    <div class="zona-steps">
      <div class="zona-step">
        <div class="zona-step-n">1</div>
        <div class="zona-step-txt">
          <strong>Geófono — escuchamos la tubería</strong>
          <p>El geófono acústico amplifica el sonido del agua escapando bajo presión. El técnico recorre el trazado de la tubería metro a metro, midiendo la intensidad de la señal. El punto donde el sonido es máximo indica la localización de la fuga. Funciona en tuberías de cobre, hierro, acero y acometidas empotradas en paredes o bajo solado.</p>
        </div>
      </div>
      <div class="zona-step">
        <div class="zona-step-n">2</div>
        <div class="zona-step-txt">
          <strong>Termografía o gas trazador — confirmamos el punto</strong>
          <p>La cámara termográfica detecta la diferencia de temperatura que genera la humedad en suelos y paredes — especialmente útil en suelo radiante y falsos techos. Para tuberías de PVC o polietileno de riego, el gas trazador (nitrógeno con hidrógeno) asciende hasta la superficie por el punto exacto de la fisura. Cuando geófono y confirmación coinciden, la localización es definitiva.</p>
        </div>
      </div>
      <div class="zona-step">
        <div class="zona-step-n">3</div>
        <div class="zona-step-txt">
          <strong>Presupuesto antes de abrir</strong>
          <p>Marcamos el punto exacto en el suelo o la pared. Damos presupuesto de reparación antes de tocar nada — tú decides si seguimos en la misma visita o en otro momento. Los instaladores certificados Nubeco pueden realizar la reparación en la misma jornada si la tubería es accesible.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Tarifas orientativas</p>
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem">Precio de detección de fugas en Sax</h3>
    <div class="zona-precios">
      <div class="zona-precio-head">
        <span>Servicio</span><span>Precio orientativo</span>
      </div>
      <div class="zona-precio-row">
        <span>Detección con geófono</span><span>Desde 140 € (primera hora incluida)</span>
      </div>
      <div class="zona-precio-row">
        <span>Desplazamiento a Sax</span><span>25 €</span>
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
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1rem;color:#fff">Detectamos fugas en toda Sax</h3>
    <div class="zona-ztags">
      <span class="zona-ztag-plain">Casco antiguo (CP 03630)</span>
      <span class="zona-ztag-plain">El Kety</span>
      <span class="zona-ztag-plain">Zona del Polideportivo</span>
      <span class="zona-ztag-plain">Urbanizaciones periféricas</span>
      <span class="zona-ztag-plain">Chalets y viviendas unifamiliares</span>
      <span class="zona-ztag-plain">Comunidades de vecinos</span>
      <span class="zona-ztag-plain">Fincas periféricas</span>
    </div>
  </div>
</section>

<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Preguntas frecuentes</p>
    <h3 style="font-size:1.5rem;font-weight:700;margin-bottom:1.5rem">Dudas habituales sobre fugas en Sax</h3>
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
  $_ps->execute(['%Sax%']);
  $_proy = $_ps->fetchAll(PDO::FETCH_ASSOC);
  if (empty($_proy)) {
    $_ps2 = $pdo->query('SELECT titulo, slug, descripcion, servicio, imagen FROM proyectos WHERE publicado=1 ORDER BY fecha DESC LIMIT 3');
    $_proy = $_ps2 ? $_ps2->fetchAll(PDO::FETCH_ASSOC) : [];
  }
} catch (\Throwable $_e) {}
$_arts = [];
try {
  $_as = $pdo->prepare('SELECT titulo, slug, extracto, categoria, imagen FROM articulos WHERE publicado=1 AND (zona LIKE ? OR categoria LIKE ?) ORDER BY fecha DESC LIMIT 3');
  $_as->execute(['%Sax%', '%fontan%']);
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
    <h2>Proyectos de detección de fugas <span class="hl">en Sax</span></h2>
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
    <h2>Detección de fugas <span class="hl">en Sax</span></h2>
    <p style="margin-bottom:1.5rem;color:#576574">Atendemos toda la localidad de Sax (CP 03630).</p>
    <div style="border-radius:12px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.12)">
      <iframe src="https://maps.google.com/maps?q=38.5417,-0.8146&z=14&output=embed" width="100%" height="380" style="border:0;display:block" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Detección de fugas en Sax"></iframe>
    </div>
  </div>
</section>
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Mismo servicio en otras zonas</p>
    <h2>Tambi&eacute;n detectamos fugas <span class="hl">en otros municipios</span></h2>
    <div class="zona-ztags">
      <a href="/fontanero/sax" class="zona-ztag" style="background:#1e3a5f;color:#fff">&#8592; Todos los servicios en Sax</a>
      <a href="/fontanero/elda/busqueda_fugas" class="zona-ztag">Elda</a>
      <a href="/fontanero/petrer/busqueda_fugas" class="zona-ztag">Petrer</a>
      <a href="/fontanero/novelda/busqueda_fugas" class="zona-ztag">Novelda</a>
      <a href="/fontanero/monovar/busqueda_fugas" class="zona-ztag">Mon&oacute;var</a>
      <a href="/fontanero/pinoso/busqueda_fugas" class="zona-ztag">Pinoso</a>
      <a href="/fontanero/monforte-del-cid/busqueda_fugas" class="zona-ztag">Monforte del Cid</a>
      <a href="/fontanero/salinas/busqueda_fugas" class="zona-ztag">Salinas</a>
      <a href="/fontanero/aspe/busqueda_fugas" class="zona-ztag">Aspe</a>
    </div>
  </div>
</section>
<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>&iquest;Tienes una fuga en <span>Sax?</span></h2>
    <p>Cuanto antes la localizamos, menos da&ntilde;o y menos coste. Llamamos antes de abrir.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; 611 165 129</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">&#128172; WhatsApp</a>
    </div>
  </div>
</section>
<?php
$ciudad = 'sax';
$servicio = 'fugas';
include '../../includes/resenas-section.php';
?>
<?php include '../../includes/galeria-section.php'; ?>
<?php include '../../includes/footer.php'; ?>
