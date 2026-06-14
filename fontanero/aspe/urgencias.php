<?php
/**
 * Fontanero urgente en Aspe
 * CarolTemp
 */
$faq_items = [
  [
    'q' => '¿Cuánto tarda un fontanero urgente en llegar a Aspe?',
    'a' => 'Nuestro objetivo es llegar en el menor tiempo posible, con un máximo de 3 horas desde el primer contacto. En el casco urbano y el Bulevar los tiempos son habitualmente menores. Para urbanizaciones periféricas y fincas rurales añade entre 10 y 20 minutos. Operamos los 365 días del año, incluidos festivos y madrugadas.',
  ],
  [
    'q' => '¿Qué incluye el presupuesto gratuito de fontanería en Aspe?',
    'a' => 'El desplazamiento hasta tu domicilio, el diagnóstico completo de la avería in situ y una propuesta detallada con el precio total cerrado. Sin letra pequeña ni cargos ocultos. Solo empezamos a trabajar si aceptas el presupuesto — si no lo aceptas, no pagas nada.',
  ],
  [
    'q' => '¿Trabajáis en urgencias los fines de semana y festivos en Aspe?',
    'a' => 'Sí. El servicio es 365 días al año, incluidos sábados, domingos y todos los festivos. Para horario nocturno (desde las 22:00 h) y días festivos se aplica un recargo que se comunica siempre al contactar, antes de desplazarnos. Sin sorpresas en la factura.',
  ],
  [
    'q' => '¿Por qué elegir un fontanero certificado Nubeco en Aspe?',
    'a' => 'La certificación Nubeco habilita a los técnicos para instalar y mantener calderas de gas y aerotermia conforme a normativa. Sin esa certificación, la garantía del fabricante queda anulada automáticamente y la instalación puede no pasar la inspección reglamentaria. Nuestros técnicos están certificados y emiten el certificado de instalación en todos los trabajos que lo requieren.',
  ],
  [
    'q' => '¿Cómo sé si mi urgencia necesita llamada inmediata o puede esperar?',
    'a' => 'Llama de inmediato si: hay agua saliendo y no puedes cerrar la llave de paso, llevas sin agua caliente con personas mayores o dependientes en casa, o el desagüe está completamente colapsado. Puede esperar al día siguiente si: un grifo gotea lentamente, la presión ha bajado sin causa visible, o la cisterna no cierra del todo pero el baño funciona.',
  ],
];

$meta_title  = 'Fontanero urgente en Aspe | Servicio 24 horas — CarolTemp';
$meta_desc   = 'Fontanero urgente en Aspe las 24 horas. Fugas, roturas y averías en el casco urbano, Bulevar, polígono y zonas rurales. Presupuesto gratis, instaladores Nubeco.';
$meta_url    = 'https://caroltemp.com/fontanero/aspe/urgencias';
$schema_type = 'local';
$page_css    = 'zona';
$page_js     = 'zona';
include '../../includes/head.php';
?>

<?php
$_hi     = getHeroImagen('urgencias-aspe');
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
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Fontanero urgente &middot; Aspe &middot; CP 03680</div>
    <h1>Fontanero urgente en Aspe<br><span class="hl">Llegamos cuando más lo necesitas y resolvemos la avería sin demoras</span></h1>
    <p class="hz-dark-sub">Hay averías que no pueden esperar a que haya hueco en la agenda. Cogemos el teléfono, vamos ese día y lo resolvemos — porque una urgencia real necesita a alguien que actúe, no que prometa y no llegue.</p>
    <div class="hz-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; 611 165 129</a>
      <a href="https://wa.me/34611165129" class="btn-hz-g">WhatsApp</a>
    </div>
  </div>
</section>

<div class="dif-strip">
  <div class="dif-strip-in">
    <div class="dif-item"><span class="dif-val">Máx. 3 horas, 365 días</span><span class="dif-lbl">Urgencias reales, no promesas</span></div>
    <div class="dif-item"><span class="dif-val">Precio antes de empezar</span><span class="dif-lbl">Lo apruebas tú, sin sorpresas</span></div>
    <div class="dif-item"><span class="dif-val">Instaladores Nubeco</span><span class="dif-lbl">Calderas y aerotermia con garantía</span></div>
    <div class="dif-item"><span class="dif-val">Resolvemos en la misma visita</span><span class="dif-lbl">Si hay material, sin segunda vuelta</span></div>
  </div>
</div>

<section class="zona-sec">
  <div class="cta-dark-con">
    <div class="zona-tcol">
      <div>
        <p class="zona-lbl">Cuándo llamar</p>
        <h2>Servicio de fontaner&iacute;a urgente <span class="hl">24 horas en Aspe</span></h2>
        <div class="zona-prose">
          <p>Una rotura de tubería gestionada en la primera hora es un problema de fontanería. La misma rotura gestionada al día siguiente es un problema de fontanería más humedades en la pared, más daños en el suelo, más posibles afectaciones al vecino de abajo. El agua dura del Vinalopó deteriora juntas y válvulas con los años: las instalaciones antiguas del centro de Aspe son especialmente vulnerables cuando una junta cede de golpe.</p>
          <p>Atendemos urgencias en toda la localidad — casco urbano, Bulevar, polígono industrial y casas de campo del término municipal. Las averías más habituales:</p>
        </div>
        <ul class="zona-chk">
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Rotura en tubería de vivienda unifamiliar en Aspe</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fuga activa en tubería empotrada o vista</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Calentador sin agua caliente en piso o chalet</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Grupo de presión parado en comunidad o finca</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Llave de paso agarrotada que no cierra el suministro</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Inundación en local o garaje por rotura en planta superior</li>
        </ul>
      </div>
      <div>
        <div class="icard">
          <div class="icard-head">¿Es una urgencia? Lo es si…</div>
          <div class="icard-body">
            <div class="icard-row"><span class="icard-icon">💧</span><span>Hay agua saliendo y no puedes cerrar la llave de paso</span></div>
            <div class="icard-row"><span class="icard-icon">💦</span><span>Agua saliendo por el techo del vecino de abajo</span></div>
            <div class="icard-row"><span class="icard-icon">🔥</span><span>Sin agua caliente con personas dependientes en casa</span></div>
            <div class="icard-row"><span class="icard-icon">🚰</span><span>Llave de paso que no cierra el suministro</span></div>
            <div class="icard-row"><span class="icard-icon">⚡</span><span>Grupo de presión parado en pleno verano</span></div>
            <div class="icard-row"><span class="icard-icon">🏚️</span><span>Humedad apareciendo rápidamente en una pared</span></div>
          </div>
          <a href="tel:+34611165129" class="zona-icard-btn">&#128222; Llamar ahora</a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec zona-sec-alt">
  <div class="cta-dark-con">
    <p class="zona-lbl">Qué reparamos</p>
    <h2>Aver&iacute;as urgentes <span class="hl">en Aspe</span></h2>
    <div class="zona-grid-6">
      <div class="zona-tipo">
        <div class="zona-tipo-ico">💧</div>
        <h3>Fugas de agua urgentes</h3>
        <p>Fugas en tuberías de viviendas unifamiliares, pisos y comunidades. El agua dura del Vinalopó deteriora juntas con los años. Localizamos y cerramos el escape antes de que cause daños en estructura o acabados.</p>
      </div>
      <div class="zona-tipo">
        <div class="zona-tipo-ico">⚙️</div>
        <h3>Grupos de presión</h3>
        <p>Fallo de grupo de presión en chalets, comunidades y naves del polígono industrial de Aspe. Revisamos presostato, membrana y circuito eléctrico en la misma visita, sin segunda cita.</p>
      </div>
      <div class="zona-tipo">
        <div class="zona-tipo-ico">🔥</div>
        <h3>Calderas y calentadores</h3>
        <p>Termo sin agua caliente, calentador que no enciende o caldera que falla. Somos instaladores certificados Nubeco: reparamos e instalamos con garantía de fabricante y certificado reglamentario.</p>
      </div>
      <div class="zona-tipo">
        <div class="zona-tipo-ico">🔩</div>
        <h3>Roturas de tubería</h3>
        <p>Rotura de tubería empotrada o vista con agua a presión. Cortamos el paso, evaluamos el daño y presupuestamos la reparación antes de abrir ninguna pared en la vivienda.</p>
      </div>
      <div class="zona-tipo">
        <div class="zona-tipo-ico">🚰</div>
        <h3>Llaves de paso y válvulas</h3>
        <p>Llave de paso que no cierra, válvula de corte atascada o rotura de contador. Situaciones que impiden cortar el suministro en una emergencia en cualquier vivienda de Aspe.</p>
      </div>
      <div class="zona-tipo">
        <div class="zona-tipo-ico">🏢</div>
        <h3>Comunidades y locales</h3>
        <p>Urgencias en zonas comunes, locales comerciales y naves del polígono de Aspe. Mismo proceso: presupuesto antes de empezar, sin tarifas distintas por tipo de inmueble.</p>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Mientras llega el fontanero</p>
    <h2>Qu&eacute; hacer <span class="hl">mientras esperas al fontanero</span></h2>
    <div class="zona-steps">
      <div class="zona-step">
        <div class="zona-step-n">1</div>
        <div class="zona-step-txt">
          <strong>Corta el agua en la llave de paso general</strong>
          <p>Suele estar bajo el fregadero de cocina, en el armario del baño o en la caja del contador (rellano o fachada). Gírala en el sentido de las agujas del reloj. Si no la encuentras o está atascada, te guiamos por teléfono.</p>
        </div>
      </div>
      <div class="zona-step">
        <div class="zona-step-n">2</div>
        <div class="zona-step-txt">
          <strong>Aleja objetos de valor y protege el suelo</strong>
          <p>Retira electrónica, documentación y objetos frágiles del área afectada. Pon toallas o cubos para frenar la extensión del agua. Cada metro cuadrado húmedo que evitas es tiempo y dinero de secado que te ahorras después.</p>
        </div>
      </div>
      <div class="zona-step">
        <div class="zona-step-n">3</div>
        <div class="zona-step-txt">
          <strong>No uses enchufes ni aparatos eléctricos cerca del agua</strong>
          <p>El agua y la electricidad son una combinación peligrosa. Si la fuga ha llegado a zonas con instalación eléctrica visible, desconecta el diferencial de la zona afectada desde el cuadro eléctrico.</p>
        </div>
      </div>
      <div class="zona-step">
        <div class="zona-step-n">4</div>
        <div class="zona-step-txt">
          <strong>Llámanos — te guiamos mientras nos desplazamos</strong>
          <p>Cuéntanos la situación por teléfono o WhatsApp. Te damos instrucciones concretas para minimizar el daño y salimos hacia Aspe de inmediato. Máx. 3 horas desde el contacto, 365 días al año.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Sin sorpresas</p>
    <h2>C&oacute;mo actuamos <span class="hl">cuando nos llamas</span></h2>
    <div class="zona-steps">
      <div class="zona-step">
        <div class="zona-step-n">1</div>
        <div class="zona-step-txt">
          <strong>Nos cuentas la avería</strong>
          <p>Describes el problema por teléfono o WhatsApp. Te damos un precio orientativo según el tipo de avería y acordamos la visita. Si es una urgencia real — rotura activa, sin agua caliente, grupo de presión parado — priorizamos el desplazamiento a Aspe.</p>
        </div>
      </div>
      <div class="zona-step">
        <div class="zona-step-n">2</div>
        <div class="zona-step-txt">
          <strong>Vemos y presupuestamos en persona</strong>
          <p>Llegamos, inspeccionamos la avería en persona y te damos el precio definitivo cerrado. Sin tocar nada hasta que lo apruebes. Para fugas en tuberías empotradas usamos geófono si la localización no es evidente.</p>
        </div>
      </div>
      <div class="zona-step">
        <div class="zona-step-n">3</div>
        <div class="zona-step-txt">
          <strong>Reparamos en la misma visita</strong>
          <p>Si hay material en la furgoneta, resolvemos ese mismo día. El precio final es el que se acordó, sin añadidos. En calderas y aerotermia emitimos certificado Nubeco cuando la normativa lo exige.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Precios claros</p>
    <h2>Tarifas fontanero urgente <span class="hl">Aspe</span></h2>
    <p style="color:#576574;margin-bottom:.5rem">Tarifas base orientativas. El presupuesto definitivo se da siempre antes de empezar el trabajo.</p>
    <div class="zona-precios">
      <div class="zona-precio-head">
        <span>Concepto</span><span>Precio</span><span>Condiciones</span>
      </div>
      <div class="zona-precio-row">
        <span class="zona-precio-concepto">Mano de obra</span>
        <span class="zona-precio-val">desde 60 €/h</span>
        <span class="zona-precio-nota">Mínimo 1 hora, según trabajo</span>
      </div>
      <div class="zona-precio-row">
        <span class="zona-precio-concepto">Desplazamiento a Aspe</span>
        <span class="zona-precio-val">desde 25 €</span>
        <span class="zona-precio-nota">Según distancia, incluido en presupuesto</span>
      </div>
      <div class="zona-precio-row">
        <span class="zona-precio-concepto">Horario nocturno (desde las 22:00 h)</span>
        <span class="zona-precio-val" style="font-size:14px;color:#576574">Recargo</span>
        <span class="zona-precio-nota">Se informa al contactar</span>
      </div>
      <div class="zona-precio-row">
        <span class="zona-precio-concepto">Fines de semana y festivos</span>
        <span class="zona-precio-val" style="font-size:14px;color:#576574">Recargo</span>
        <span class="zona-precio-nota">Se informa al contactar</span>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec zona-sec-dark">
  <div class="cta-dark-con">
    <p class="zona-lbl">Cobertura local</p>
    <h2>D&oacute;nde atendemos <span class="hl">en Aspe</span></h2>
    <p style="color:#94a3b8;margin-bottom:1.75rem;max-width:640px">Cubrimos toda la localidad de Aspe — el mismo servicio en el centro que en las urbanizaciones periféricas y las casas de campo del término municipal.</p>
    <div class="zona-ztags">
      <span class="zona-ztag-plain">Centro urbano</span>
      <span class="zona-ztag-plain">El Bulevar</span>
      <span class="zona-ztag-plain">Polígono industrial</span>
      <span class="zona-ztag-plain">Urbanizaciones periféricas</span>
      <span class="zona-ztag-plain">Viviendas unifamiliares y chalets</span>
      <span class="zona-ztag-plain">Casas de campo y fincas rurales</span>
    </div>
  </div>
</section>

<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Preguntas frecuentes</p>
    <h2>Urgencias fontanero <span class="hl">Aspe &mdash; dudas habituales</span></h2>
    <div class="zona-faqs">
      <?php foreach ($faq_items as $i => $faq): ?>
      <details class="zona-faq-item"<?php echo $i === 0 ? ' open' : ''; ?>>
        <summary><?php echo htmlspecialchars($faq['q']); ?></summary>
        <div class="faq-ans"><?php echo htmlspecialchars($faq['a']); ?></div>
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
if (!empty($_proy)): ?>
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Trabajos realizados</p>
    <h2>Proyectos de fontaner&iacute;a urgente <span class="hl">en Aspe</span></h2>
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
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Zona de cobertura</p>
    <h2>Fontanero urgente <span class="hl">en Aspe</span></h2>
    <p style="margin-bottom:1.5rem;color:#576574">Atendemos toda la localidad de Aspe (CP 03680) y el t&eacute;rmino municipal completo.</p>
    <div style="border-radius:12px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.12)">
      <iframe src="https://maps.google.com/maps?q=38.3442,-0.7704&z=14&output=embed" width="100%" height="380" style="border:0;display:block" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Fontanero urgente en Aspe"></iframe>
    </div>
  </div>
</section>
<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Mismo servicio en otras zonas</p>
    <h2>Fontanero urgente <span class="hl">en otros municipios</span></h2>
    <div class="zona-ztags">
      <a href="/fontanero/aspe" class="zona-ztag" style="background:#1e3a5f;color:#fff">&#8592; Todos los servicios en Aspe</a>
      <a href="/fontanero/elda/urgencias" class="zona-ztag">Elda</a>
      <a href="/fontanero/novelda/urgencias" class="zona-ztag">Novelda</a>
      <a href="/fontanero/sax/urgencias" class="zona-ztag">Sax</a>
      <a href="/fontanero/pinoso/urgencias" class="zona-ztag">Pinoso</a>
      <a href="/fontanero/monovar/urgencias" class="zona-ztag">Mon&oacute;var</a>
      <a href="/fontanero/petrer/urgencias" class="zona-ztag">Petrer</a>
      <a href="/fontanero/monforte-del-cid/urgencias" class="zona-ztag">Monforte del Cid</a>
    </div>
  </div>
</section>
<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>&iquest;Tienes una aver&iacute;a <span>en Aspe?</span></h2>
    <p>Ll&aacute;menos. Te damos precio antes de empezar. M&aacute;x. 3 horas, 365 d&iacute;as.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; Llamar ahora</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">&#128172; WhatsApp</a>
    </div>
  </div>
</section>
<?php
$ciudad = 'aspe';
$servicio = 'urgencias';
include '../../includes/resenas-section.php';
?>
<?php include '../../includes/galeria-section.php'; ?>
<?php include '../../includes/footer.php'; ?>
