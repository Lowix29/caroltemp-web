<?php
/**
 * Desatascos en Aspe
 * CarolTemp
 */
$faq_items = [
  [
    'q' => '¿Cuánto cuesta un desatasco urgente en Aspe?',
    'a' => 'El desatasco básico parte desde 60 € e incluye la primera hora de trabajo. La cámara endoscópica de diagnóstico se presupuesta aparte a 120 € cuando es necesaria. El desplazamiento a Aspe tiene un coste fijo de 25 €. Antes de actuar te damos el presupuesto sin compromiso — si no lo aceptas, no te cobramos nada. Los trabajos en horario nocturno (a partir de las 20:00 h) o en festivos llevan un recargo que comunicamos al contactar.'
  ],
  [
    'q' => '¿En cuánto tiempo llegáis a una urgencia de desatasco en Aspe?',
    'a' => 'El tiempo máximo de llegada desde el contacto es de 3 horas, el mismo día. Trabajamos los 365 días del año, incluidos domingos y festivos, tanto en el casco urbano como en La Serreta, los polígonos industriales y las urbanizaciones y chalets del término. Un atasco puntual en inodoro o fregadero suele resolverse en 30-45 minutos desde que llegamos; una bajante con tapón severo o una arqueta colapsada puede llevar entre una hora y hora y media.'
  ],
  [
    'q' => '¿Qué incluye vuestro servicio de desatasco en Aspe?',
    'a' => 'El servicio incluye inspección inicial con cámara endoscópica (cuando el acceso lo permite), el desatasco con la técnica más adecuada según el caso (hidrojeteado de alta presión, sonda mecánica o aspiración de lodos), y verificación del resultado con agua a caudal real. Somos instaladores certificados Nubeco. Si el mismo punto vuelve a atascarse por la misma causa, volvemos sin cargo adicional.'
  ],
  [
    'q' => '¿Atendéis desatascos en comunidades de vecinos en Aspe?',
    'a' => 'Sí. Atendemos bajantes generales, colectores y arquetas comunitarias en comunidades de vecinos de Aspe. Nos coordinamos directamente con el administrador de fincas o con quien tenga acceso a las zonas comunes. La cámara endoscópica permite determinar con precisión en qué tramo está el bloqueo, lo que es útil tanto para decidir la actuación como para la gestión con el seguro de la comunidad si hay daños asociados.'
  ],
  [
    'q' => '¿Cómo sé si necesito un desatasco profesional o puedo solucionarlo yo?',
    'a' => 'Si el atasco afecta a un solo desagüe (ducha, fregadero) y es la primera vez, puedes intentar un desatascador manual de ventosa. Si el atasco vuelve en el mismo punto más de una vez, si el agua sale por varios desagüe a la vez, si hay malos olores persistentes o si la arqueta exterior está desbordando, es señal de que la causa es estructural. En ese caso los productos químicos del supermercado no resolverán el problema y pueden dañar tuberías antiguas.'
  ],
];
$meta_title  = 'Desatascos urgentes 24h en Aspe | CarolTemp — 611 165 129';
$meta_desc   = 'Desatascos en Aspe 24 horas, 365 días. Tuberías, fosas sépticas, arquetas, polígonos industriales. Cámara endoscópica + hidrojeteado. Desde 60 €. Nubeco.';
$meta_url    = 'https://caroltemp.com/fontanero/aspe/desatascos';
$schema_type = 'local';
$page_css    = 'zona';
$page_js     = 'zona';
include '../../includes/head.php';
?>

<?php
$_hi     = getHeroImagen('desatascos-aspe');
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
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Desatascos &middot; Aspe &middot; CP 03680</div>
    <h1>Desatascos en Aspe<br><span class="hl">eliminamos el atasco desde el origen para evitar que vuelva</span></h1>
    <p class="hz-dark-sub">Hay una diferencia entre desbloquear y limpiar. Desbloqueamos con sonda para que el agua pase hoy. Limpiamos con hidrojeteado para que el conducto no vuelva a atascarse mañana. La cámara confirma que el trabajo está terminado de verdad.</p>
    <div class="hz-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; 611 165 129</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">&#128172; WhatsApp</a>
    </div>
  </div>
</section>

<div class="dif-strip">
  <div class="dif-strip-in">
    <div class="dif-item"><span class="dif-val">&#128176; Desde 60 €</span><span class="dif-lbl">Presupuesto antes de actuar</span></div>
    <div class="dif-item"><span class="dif-val">&#128247; Cámara endoscópica</span><span class="dif-lbl">Diagnóstico visual antes y después</span></div>
    <div class="dif-item"><span class="dif-val">Polígonos &amp; comunidades</span><span class="dif-lbl">Cobertura en todo el término</span></div>
    <div class="dif-item"><span class="dif-val">&#9989; Nubeco oficial</span><span class="dif-lbl">Instalador certificado</span></div>
  </div>
</div>

<section class="zona-sec">
  <div class="cta-dark-con">
    <div class="zona-tcol">
      <div>
        <p class="zona-lbl">Desatascos en Aspe</p>
        <h2>Desatascos urgentes 24 horas en Aspe</h2>
        <div class="zona-prose">
          <p>Un atasco recurrente en el mismo punto no es mala suerte — es una señal de que la causa real no se eliminó. La sonda perfora el tapón y deja pasar el agua, pero las paredes del conducto siguen cubiertas de grasa, cal y residuos orgánicos. En semanas o meses, esa capa acumula de nuevo. El hidrojeteado lanza agua a alta presión en todas las direcciones y arrastra todo ese depósito de las paredes del tubo, no solo el núcleo del tapón.</p>
          <p>Aspe tiene una mezcla particular de instalaciones: el casco histórico con bajantes de hierro de los años 50-60 que llevan décadas acumulando sarro y óxido, la zona de La Serreta con viviendas de distinta antigüedad, y los polígonos industriales del sector del calzado donde las bajantes de vestuarios y las arquetas exteriores de naves necesitan mantenimiento regular. También hay una parte importante del término con chalets y casas de campo que gestionan sus aguas con fosas sépticas propias. Cada caso exige un método distinto; la cámara endoscópica decide cuál antes de actuar.</p>
        </div>
        <ul class="zona-chk">
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Atasco de inodoro en Aspe que no desagua</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Bajante bloqueada en piso, chalet o comunidad de vecinos</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Arqueta exterior desbordada en vivienda unifamiliar o nave</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Desagüe de cocina atascado por grasa y sarro</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Vaciado y limpieza de fosas sépticas en casas de campo</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Raíces en bajantes exteriores o colectores de jardín</li>
        </ul>
      </div>
      <div>
        <div class="icard">
          <div class="icard-head"><strong>CarolTemp &middot; Aspe</strong><span>Desatascos 24h</span></div>
          <div class="zona-ir"><span class="zona-ir-l">Zona</span><span class="zona-ir-v">Aspe &middot; CP 03680</span></div>
          <div class="zona-ir"><span class="zona-ir-l">Teléfono</span><span class="zona-ir-v"><a href="tel:+34611165129">611 165 129</a></span></div>
          <div class="zona-ir"><span class="zona-ir-l">WhatsApp</span><span class="zona-ir-v"><a href="https://wa.me/34611165129">Escribir ahora &rarr;</a></span></div>
          <div class="zona-ir"><span class="zona-ir-l">Todos los servicios</span><span class="zona-ir-v"><a href="/fontanero/aspe">Fontanería en Aspe &rarr;</a></span></div>
          <a href="tel:+34611165129" class="zona-icard-btn">&#128222; Llamar ahora</a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec zona-sec-alt">
  <div class="cta-dark-con">
    <p class="zona-lbl">Qué desbloqueamos</p>
    <h2>Servicios de desatasco <span class="hl">en Aspe</span></h2>
    <div class="zona-grid-6">
      <div class="zona-tipo">
        <div class="zona-tipo-ico">🚽</div>
        <h3>Desatasco de inodoro</h3>
        <p>Sólidos, toallitas húmedas o sarro que bloquean el sifón o la salida. Resolvemos sin desmontar la taza cuando no es necesario.</p>
      </div>
      <div class="zona-tipo">
        <div class="zona-tipo-ico">🏗️</div>
        <h3>Desatasco de bajantes</h3>
        <p>Bajante vertical bloqueada por grasa, raíces o residuos sólidos. Hidrojeteado a presión para limpiar el conducto completo desde la cámara hasta la arqueta general.</p>
      </div>
      <div class="zona-tipo">
        <div class="zona-tipo-ico">🔲</div>
        <h3>Limpieza de arquetas</h3>
        <p>Arquetas colapsadas por grasa endurecida, lodos o raíces. Limpieza con bomba de presión y revisión de paredes para detectar grietas que causen problemas futuros.</p>
      </div>
      <div class="zona-tipo">
        <div class="zona-tipo-ico">🍳</div>
        <h3>Desatasco de cocina</h3>
        <p>Fregadero atascado por grasa, restos orgánicos o sarro. La combinación de grasa y agua calcárea forma tapones compactos que solo el hidrojeteado elimina de forma duradera.</p>
      </div>
      <div class="zona-tipo">
        <div class="zona-tipo-ico">🌿</div>
        <h3>Fosas sépticas y fincas</h3>
        <p>Vaciado y limpieza de fosas sépticas en casas de campo y chalets del término de Aspe. Inspeccionamos el estado de la fosa e informamos si el campo de absorción muestra signos de saturación.</p>
      </div>
      <div class="zona-tipo">
        <div class="zona-tipo-ico">🏢</div>
        <h3>Comunidades y polígonos</h3>
        <p>Desatascos para comunidades de vecinos y naves industriales en Aspe: bajante general, colectores, sumideros de nave y arquetas comunitarias. Coordinamos con el administrador o responsable de instalaciones.</p>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Cómo trabajamos</p>
    <h2>Proceso de desatasco <span class="hl">en Aspe</span></h2>
    <ol class="zona-steps">
      <li><strong>Diagnóstico con cámara endoscópica</strong> — Introducimos la cámara por el desagüe o la arqueta para ver en directo el tipo de obstrucción y su posición exacta. El presupuesto es concreto: sabes qué se va a hacer y cuánto cuesta antes de empezar.</li>
      <li><strong>Técnica adecuada según el atasco</strong> — Hidrojeteado a alta presión para tapones de grasa compacta o raíces, sonda mecánica para atascos puntuales, aspiración para arquetas con lodos. Cuando el sarro se mezcla con grasa combinamos técnicas para un resultado duradero.</li>
      <li><strong>Verificación y trabajo limpio</strong> — Al terminar verificamos con agua a caudal real. Si el caso lo requiere, una segunda pasada de cámara confirma que el conducto queda completamente libre. Recogemos restos y dejamos la zona como estaba. El precio acordado es el final.</li>
    </ol>
  </div>
</section>

<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Tarifas orientativas</p>
    <h2>Precio desatasco <span class="hl">Aspe</span></h2>
    <p style="color:#576574;margin-bottom:.5rem">El presupuesto exacto se da tras el diagnóstico con cámara, antes de empezar el desbloqueo.</p>
    <div class="zona-precios">
      <div class="zona-precio-head">
        <span>Concepto</span><span>Precio</span><span>Notas</span>
      </div>
      <div class="zona-precio-row">
        <span class="zona-precio-concepto">Desatasco</span>
        <span class="zona-precio-val">desde 60 €</span>
        <span class="zona-precio-nota">1 hora incluida. Dependiendo del desatasco.</span>
      </div>
      <div class="zona-precio-row">
        <span class="zona-precio-concepto">Cámara endoscópica</span>
        <span class="zona-precio-val">120 €</span>
        <span class="zona-precio-nota">Diagnóstico previo al trabajo.</span>
      </div>
      <div class="zona-precio-row">
        <span class="zona-precio-concepto">Desplazamiento</span>
        <span class="zona-precio-val">desde 25 €</span>
        <span class="zona-precio-nota">Coste fijo a Aspe y su término.</span>
      </div>
      <div class="zona-precio-row">
        <span class="zona-precio-concepto">Horario nocturno</span>
        <span class="zona-precio-val">recargo</span>
        <span class="zona-precio-nota">A partir de las 20:00 h.</span>
      </div>
      <div class="zona-precio-row">
        <span class="zona-precio-concepto">Fines de semana y festivos</span>
        <span class="zona-precio-val">recargo</span>
        <span class="zona-precio-nota">Se informa al contactar.</span>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec zona-sec-dark">
  <div class="cta-dark-con">
    <p class="zona-lbl">Cobertura completa</p>
    <h2>Desatascos en todo Aspe <span class="hl">y su término municipal</span></h2>
    <p style="margin-bottom:1.5rem;color:#94a3b8">Atendemos el casco urbano, las urbanizaciones, los polígonos industriales y las casas de campo del término.</p>
    <div class="zona-ztags">
      <span class="zona-ztag-plain">Centro / Casco Antiguo (CP 03680)</span>
      <span class="zona-ztag-plain">La Serreta</span>
      <span class="zona-ztag-plain">Polígono industrial</span>
      <span class="zona-ztag-plain">Urbanizaciones periféricas</span>
      <span class="zona-ztag-plain">Chalets y viviendas unifamiliares</span>
      <span class="zona-ztag-plain">Casas de campo y fincas</span>
    </div>
  </div>
</section>

<section class="zona-sec">
  <div class="cta-dark-con">
    <div class="zona-tcol">
      <div>
        <p class="zona-lbl">Mientras esperas</p>
        <h2>Qué hacer mientras esperas al fontanero <span class="hl">en Aspe</span></h2>
        <ol class="zona-steps">
          <li><strong>Cierra el grifo o la llave de paso más cercana</strong> para evitar que el agua siga llenando el desagüe bloqueado.</li>
          <li><strong>No uses más desagüe de la vivienda</strong> — en fosas sépticas y bajantes colapsadas, todos los desagüe comparten la misma salida.</li>
          <li><strong>No eches productos químicos</strong> — pueden dañar tuberías antiguas y dificultan el trabajo con cámara e hidrojeteado.</li>
          <li><strong>Anota en qué punto empezó y si hay malos olores</strong> — esa información acelera el diagnóstico cuando llegamos.</li>
        </ol>
      </div>
      <div>
        <div class="icard">
          <div class="icard-head"><strong>¿Es una urgencia?</strong><span>Lo es si…</span></div>
          <div class="icard-body">
            <ul>
              <li>Ningún desagüe de la vivienda funciona a la vez</li>
              <li>El retrete desborda o hace burbujas al usar otro grifo</li>
              <li>Aparecen malos olores en varios puntos de la vivienda</li>
              <li>Hay agua estancada en el suelo o en la ducha</li>
              <li>La fosa séptica lleva más de 4 años sin vaciar</li>
            </ul>
          </div>
          <a href="tel:+34611165129" class="zona-icard-btn">&#128222; Llamar ahora — 611 165 129</a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Preguntas frecuentes</p>
    <h2>Desatascos en Aspe <span class="hl">— dudas habituales</span></h2>
    <div class="zona-faq" style="margin-top:2rem">
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
  $_as = $pdo->query('SELECT titulo, slug, extracto, categoria, imagen FROM articulos WHERE publicado=1 ORDER BY fecha DESC LIMIT 3');
  $_arts = $_as ? $_as->fetchAll(PDO::FETCH_ASSOC) : [];
} catch (\Throwable $_e) {}
if (!empty($_proy)): ?>
<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Trabajos realizados</p>
    <h2>Proyectos de desatascos <span class="hl">en Aspe</span></h2>
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
    <p class="zona-lbl">Consejos útiles</p>
    <h2>Artículos sobre <span class="hl">desatascos y saneamiento</span></h2>
    <div class="zona-svc" style="margin-top:2rem">
      <?php foreach ($_arts as $_a): ?>
      <a href="/noticias/<?php echo urlencode($_a['slug']); ?>" class="zona-sc">
        <?php if (!empty($_a['imagen'])): ?><img src="<?php echo htmlspecialchars($_a['imagen']); ?>" alt="<?php echo htmlspecialchars($_a['titulo']); ?>" loading="lazy" style="width:100%;height:160px;object-fit:cover;border-radius:8px;margin-bottom:.75rem"><?php endif; ?>
        <?php if (!empty($_a['categoria'])): ?><span class="zona-lbl" style="font-size:11px"><?php echo htmlspecialchars($_a['categoria']); ?></span><?php endif; ?>
        <h3><?php echo htmlspecialchars($_a['titulo']); ?></h3>
        <p><?php echo htmlspecialchars(mb_substr($_a['extracto'] ?? '', 0, 100)); ?>...</p>
        <span class="zona-sc-a">Leer artículo &rarr;</span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Zona de cobertura</p>
    <h2>Desatascos <span class="hl">en Aspe</span></h2>
    <div style="border-radius:12px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.12)">
      <iframe src="https://maps.google.com/maps?q=38.3442,-0.7704&z=14&output=embed" width="100%" height="380" style="border:0;display:block" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Desatascos en Aspe"></iframe>
    </div>
  </div>
</section>
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Mismo servicio en otras zonas</p>
    <h2>También hacemos desatascos <span class="hl">en otros municipios</span></h2>
    <div class="zona-ztags">
      <a href="/fontanero/aspe" class="zona-ztag" style="background:#1e3a5f;color:#fff">&#8592; Todos los servicios en Aspe</a>
      <a href="/fontanero/elda/desatascos" class="zona-ztag">Elda</a>
      <a href="/fontanero/novelda/desatascos" class="zona-ztag">Novelda</a>
      <a href="/fontanero/sax/desatascos" class="zona-ztag">Sax</a>
      <a href="/fontanero/petrer/desatascos" class="zona-ztag">Petrer</a>
      <a href="/fontanero/monovar/desatascos" class="zona-ztag">Monóvar</a>
      <a href="/fontanero/pinoso/desatascos" class="zona-ztag">Pinoso</a>
      <a href="/fontanero/monforte-del-cid/desatascos" class="zona-ztag">Monforte del Cid</a>
    </div>
  </div>
</section>
<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>&iquest;Tienes un atasco <span>en Aspe?</span></h2>
    <p>Llámenos o escríbenos. Atendemos los 365 días del año.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; Llamar ahora</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">&#128172; WhatsApp</a>
    </div>
  </div>
</section>
<?php
$ciudad = 'aspe';
$servicio = 'desatascos';
include '../../includes/resenas-section.php';
?>
<?php include '../../includes/galeria-section.php'; ?>
<?php include '../../includes/footer.php'; ?>
