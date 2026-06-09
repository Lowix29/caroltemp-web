<?php
/**
 * Fontanero urgente en Pinoso — urgencias 24h
 * CarolTemp
 */
$faq_items = [
  [
    'q' => '¿Cuánto tarda un fontanero en llegar a Pinoso en caso de urgencia?',
    'a' => 'El objetivo es estar contigo el mismo día, con un máximo de 3 horas desde el contacto en averías activas — fuga sin control, sin agua caliente, grupo de presión parado. Antes de salir confirmamos hora estimada para que no esperes sin saber. Cubrimos el casco urbano, las pedanías y las fincas rurales del término municipal. En festivos y noche el servicio sigue activo.',
  ],
  [
    'q' => '¿Cuánto cuesta una urgencia de fontanería en Pinoso por la noche o en festivo?',
    'a' => 'La tarifa base de mano de obra es desde 60 €/h con mínimo de una hora. El desplazamiento desde 25 € según distancia, incluido en el presupuesto. En horario nocturno (desde las 22:00 h) y festivos se aplica un recargo que informamos exactamente al contactar, antes de salir. El presupuesto es gratuito y se da siempre con la avería vista — sin sorpresas al finalizar.',
  ],
  [
    'q' => '¿Qué hago con una fuga de agua mientras espero al fontanero en Pinoso?',
    'a' => 'Cuatro pasos inmediatos: 1) Cierra la llave de paso general de tu vivienda — suele estar bajo el fregadero, en el armario de contadores o en el exterior de la finca. 2) Desconecta la luz de la zona afectada si hay agua cerca de enchufes o del cuadro eléctrico. 3) Coloca trapos o cubos para limitar daños en suelos y techos, y retira muebles o aparatos eléctricos del área. 4) Llámanos o escríbenos por WhatsApp — evaluamos contigo si es urgencia real y salimos con el material previsible. No improvises reparaciones con cinta o silicona que dificulten la definitiva.',
  ],
  [
    'q' => '¿Los fontaneros de CarolTemp están certificados para instalar calderas de gas en Pinoso?',
    'a' => 'Sí. Somos instaladores homologados Nubeco con habilitación para instalaciones de gas, fontanería y climatización según normativa vigente. Trabajamos con seguro de responsabilidad civil en vigor y emitimos certificado de instalación y factura detallada. No subcontratamos: el equipo que atiende tu llamada es el mismo que realiza el trabajo en tu domicilio o finca.',
  ],
  [
    'q' => '¿Qué tipo de caldera o sistema de calefacción es mejor para las viviendas de Pinoso?',
    'a' => 'Depende del tipo de vivienda y la instalación existente. Para viviendas unifamiliares con espacio exterior, la aerotermia permite ahorros del 60-70 % en energía térmica y es compatible con suelo radiante y fancoils — especialmente interesante dado el clima extremo del interior alicantino. Para pisos o viviendas con caldera de gas existente, la condensación es la opción más eficiente dentro del mismo combustible. El agua dura de la zona aconseja instalar también un descalcificador para prolongar la vida del equipo. Asesoramos sin compromiso en la visita.',
  ],
];

$meta_title  = 'Fontanero urgente en Pinoso 24h | Presupuesto gratis — CarolTemp';
$meta_desc   = 'Fontanero urgente en Pinoso disponible 24 horas. Fugas, termos, calderas, grupos de presión. Instaladores Nubeco certificados. Presupuesto gratis antes de empezar.';
$meta_url    = 'https://caroltemp.com/fontanero/pinoso/urgencias';
$schema_type = 'local';
$page_css    = 'zona';
$page_js     = 'zona';
include '../../includes/head.php';
?>

<?php
$_hi     = getHeroImagen('urgencias-pinoso');
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
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Fontanero urgente &middot; Pinoso &middot; CP 03650</div>
    <h1>Fontanero urgente en Pinoso<br><span class="hl">con experiencia, rapidez y resultados garantizados</span></h1>
    <p class="hz-dark-sub">Una rotura que manda agua al suelo, un grupo de presión parado en la finca, un termo que deja de funcionar a medianoche. Hay averías que no admiten demora — y un fontanero que de verdad coge el teléfono y sale marca la diferencia entre un susto y un desastre.</p>
    <div class="hz-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; 611 165 129</a>
      <a href="https://wa.me/34611165129" class="btn-hz-g">WhatsApp</a>
    </div>
  </div>
</section>

<div class="dif-strip">
  <div class="dif-strip-in">
    <div class="dif-item"><span class="dif-val">Mismo día</span><span class="dif-lbl">Máx. 3h desde el contacto en urgencias</span></div>
    <div class="dif-item"><span class="dif-val">Presupuesto gratis</span><span class="dif-lbl">Con la avería vista, antes de tocar nada</span></div>
    <div class="dif-item"><span class="dif-val">Instaladores Nubeco</span><span class="dif-lbl">Certificados, con seguro en vigor</span></div>
    <div class="dif-item"><span class="dif-val">Equipo propio</span><span class="dif-lbl">Sin subcontratas — casco y pedanías</span></div>
  </div>
</div>

<section class="zona-sec">
  <div class="cta-dark-con">
    <div class="zona-tcol">
      <div>
        <p class="zona-lbl">Disponibles 24 horas</p>
        <h2>Fontan&eacute;r&iacute;a urgente <span class="hl">24h en Pinoso</span></h2>
        <div class="zona-prose">
          <p>Pinoso tiene unas condiciones que hacen las averías especialmente impredecibles: el agua dura del interior alicantino deteriora resistencias de termos y válvulas con rapidez, los cambios bruscos de temperatura entre estaciones dañan juntas y tuberías expuestas, y las fincas rurales con grupo de presión propio quedan sin suministro cuando el equipo falla. Nuestro equipo atiende el término municipal de Pinoso los 365 días del año — casco urbano, pedanías y fincas rurales con acceso por camino. El fontanero que coge el teléfono es el mismo que va a tu casa: conoce el problema desde el primer momento y lleva el material adecuado para resolver en la misma visita.</p>
        </div>
        <ul class="zona-chk">
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Fuga activa en tuber&iacute;a empotrada o exterior de finca</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Grupo de presi&oacute;n parado — sin agua en toda la vivienda</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Termo o calentador sin agua caliente</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Caldera de gas que no arranca o pierde presi&oacute;n</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Llave de paso agarrotada que no cierra el suministro</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Rotura en comunidad o local comercial de Pinoso</li>
        </ul>
      </div>
      <div>
        <div class="icard">
          <div class="icard-head">&iquest;Es urgencia? Lo es si&hellip;</div>
          <div class="icard-body">
            <div class="icard-row"><span class="icard-icon">💧</span><span>Sale agua sin poder cortarla y la llave de paso no cierra</span></div>
            <div class="icard-row"><span class="icard-icon">⚙️</span><span>Grupo de presi&oacute;n de la finca parado — sin suministro</span></div>
            <div class="icard-row"><span class="icard-icon">🔥</span><span>Sin agua caliente con personas mayores o ni&ntilde;os</span></div>
            <div class="icard-row"><span class="icard-icon">🌡️</span><span>Caldera parada en invierno con temperaturas bajo cero previstas</span></div>
            <div class="icard-row"><span class="icard-icon">💦</span><span>Mancha de humedad que crece en techo o pared</span></div>
          </div>
          <a href="tel:+34611165129" class="zona-icard-btn">&#128222; Llamar ahora</a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec zona-sec-alt">
  <div class="cta-dark-con">
    <p class="zona-lbl">Qu&eacute; reparamos</p>
    <h2>Averías urgentes <span class="hl">en Pinoso</span></h2>
    <div class="zona-grid-6">
      <div class="zona-tipo">
        <div class="zona-tipo-ico">💧</div>
        <h3>Fugas en tuber&iacute;as y viviendas</h3>
        <p>Fugas activas en tuberías de viviendas, fincas y comunidades de Pinoso. Localizamos el punto exacto — con geófono en tuberías empotradas — y cerramos el escape antes de que cause daños en estructura o acabados.</p>
      </div>
      <div class="zona-tipo">
        <div class="zona-tipo-ico">⚙️</div>
        <h3>Grupos de presi&oacute;n</h3>
        <p>El grupo de presión es la avería urgente más frecuente en las fincas de Pinoso. Revisamos presostato, membrana y circuito eléctrico en la misma visita. Si la pieza está en la furgoneta, lo dejamos funcionando ese mismo día.</p>
      </div>
      <div class="zona-tipo">
        <div class="zona-tipo-ico">🔥</div>
        <h3>Termos y calderas de gas</h3>
        <p>Termo sin agua caliente, calentador que no enciende o caldera que pierde presión. El agua dura del interior alicantino acelera el deterioro de resistencias y válvulas de seguridad. Somos instaladores Nubeco autorizados para gas.</p>
      </div>
      <div class="zona-tipo">
        <div class="zona-tipo-ico">🌿</div>
        <h3>Aerotermia y climatizaci&oacute;n eficiente</h3>
        <p>Al sustituir una caldera antigua es el momento de valorar aerotermia: ahorra entre un 60 y 70 % en energía térmica y es ideal para el clima extremo del interior alicantino. Hacemos instalación completa como empresa homologada.</p>
      </div>
      <div class="zona-tipo">
        <div class="zona-tipo-ico">🔩</div>
        <h3>Roturas y v&aacute;lvulas</h3>
        <p>Rotura de tubería con agua a presión, llave de paso que no cierra, válvula de corte atascada. Situaciones que impiden cortar el suministro en una emergencia — las resolvemos en la primera visita si hay material.</p>
      </div>
      <div class="zona-tipo">
        <div class="zona-tipo-ico">🏡</div>
        <h3>Fincas rurales y peda&ntilde;&iacute;as</h3>
        <p>Atendemos las pedanías de Pinoso y fincas rurales con acceso por camino: grupos de presión, aljibes, depósitos propios, bajantes y instalaciones de viviendas aisladas. Mismo precio y mismo proceso que en el casco urbano.</p>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Sin sorpresas</p>
    <h2>Qu&eacute; pasa <span class="hl">cuando llamas</span></h2>
    <div class="zona-steps">
      <div class="zona-step">
        <div class="zona-step-n">1</div>
        <div class="zona-step-txt">
          <strong>Nos cuentas la aver&iacute;a</strong>
          <p>Describes el problema por teléfono o WhatsApp. Te damos una estimación orientativa según el tipo de avería y acordamos la visita. Si es urgencia activa — fuga sin control, sin suministro, caldera parada — priorizamos el desplazamiento ese mismo día.</p>
        </div>
      </div>
      <div class="zona-step">
        <div class="zona-step-n">2</div>
        <div class="zona-step-txt">
          <strong>Vemos y presupuestamos en persona</strong>
          <p>Llegamos, inspeccionamos la avería y te damos el precio definitivo cerrado. Sin tocar nada hasta que lo apruebes. Para fugas en tuberías empotradas usamos geófono para localizar el punto exacto antes de abrir — especialmente importante en las viviendas antiguas del casco de Pinoso.</p>
        </div>
      </div>
      <div class="zona-step">
        <div class="zona-step-n">3</div>
        <div class="zona-step-txt">
          <strong>Reparamos en la misma visita</strong>
          <p>Llevamos la furgoneta equipada con las piezas más habituales: racores, válvulas, resistencias de termo, piezas de grupo de presión. Si el material está a bordo, resolvemos ese día. El precio final es exactamente el acordado — sin añadidos ni sorpresas en la factura.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Precios claros</p>
    <h2>Tarifas fontanero urgente <span class="hl">Pinoso</span></h2>
    <p style="color:#576574;margin-bottom:.5rem">Tarifas base orientativas. El presupuesto definitivo se da siempre antes de empezar el trabajo.</p>
    <div class="zona-precios">
      <div class="zona-precio-head">
        <span>Concepto</span><span>Precio</span><span>Condiciones</span>
      </div>
      <div class="zona-precio-row">
        <span class="zona-precio-concepto">Mano de obra</span>
        <span class="zona-precio-val">desde 60 €/h</span>
        <span class="zona-precio-nota">M&iacute;nimo 1 hora, seg&uacute;n trabajo</span>
      </div>
      <div class="zona-precio-row">
        <span class="zona-precio-concepto">Desplazamiento a Pinoso</span>
        <span class="zona-precio-val">desde 25 €</span>
        <span class="zona-precio-nota">Seg&uacute;n distancia, incluido en presupuesto</span>
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

<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Preguntas frecuentes</p>
    <h2>Urgencias fontanero <span class="hl">Pinoso &mdash; dudas habituales</span></h2>
    <div class="zona-faqs">
      <details class="zona-faq-item" open>
        <summary>&iquest;Cu&aacute;nto tarda un fontanero en llegar a Pinoso en caso de urgencia?</summary>
        <div class="faq-ans">El objetivo es estar contigo el mismo d&iacute;a, con un m&aacute;ximo de 3 horas desde el contacto en aver&iacute;as activas. Antes de salir confirmamos hora estimada. Cubrimos el casco urbano, las peda&ntilde;&iacute;as y las fincas rurales del t&eacute;rmino municipal. En festivos y noche el servicio sigue activo.</div>
      </details>
      <details class="zona-faq-item">
        <summary>&iquest;Cu&aacute;nto cuesta una urgencia de fontan&eacute;r&iacute;a en Pinoso por la noche o en festivo?</summary>
        <div class="faq-ans">La tarifa base es desde 60 &euro;/h con m&iacute;nimo de una hora, m&aacute;s desplazamiento desde 25 &euro; seg&uacute;n distancia. En horario nocturno (desde las 22:00 h) y festivos se aplica un recargo que informamos exactamente al contactar, antes de salir. El presupuesto es gratuito y se da siempre con la aver&iacute;a vista &mdash; sin sorpresas al finalizar.</div>
      </details>
      <details class="zona-faq-item">
        <summary>&iquest;Qu&eacute; hago con una fuga de agua mientras espero al fontanero en Pinoso?</summary>
        <div class="faq-ans">Cuatro pasos inmediatos: cierra la llave de paso general, desconecta la luz de la zona si hay agua cerca de enchufes, coloca trapos o cubos para limitar el da&ntilde;o y ll&aacute;manos por tel&eacute;fono o WhatsApp. Evaluamos contigo si es urgencia real y salimos con el material previsible. No improvises reparaciones provisionales que dificulten la definitiva.</div>
      </details>
      <details class="zona-faq-item">
        <summary>&iquest;Est&aacute;is certificados para instalar calderas de gas en Pinoso?</summary>
        <div class="faq-ans">S&iacute;. Somos instaladores homologados Nubeco con habilitaci&oacute;n para gas, fontan&eacute;r&iacute;a y climatizaci&oacute;n. Trabajamos con seguro de responsabilidad civil en vigor y emitimos certificado de instalaci&oacute;n y factura detallada. No subcontratamos: el equipo que atiende tu llamada es el mismo que realiza el trabajo.</div>
      </details>
      <details class="zona-faq-item">
        <summary>&iquest;Qu&eacute; caldera o sistema de calefacci&oacute;n es mejor para las viviendas de Pinoso?</summary>
        <div class="faq-ans">Depende del tipo de vivienda. Para unifamiliares con espacio exterior, la aerotermia ahorra un 60-70 % en energ&iacute;a t&eacute;rmica y es ideal para el clima extremo del interior alicantino. Para pisos con caldera de gas existente, la condensaci&oacute;n es la opci&oacute;n m&aacute;s eficiente. El agua dura de la zona aconseja instalar tambi&eacute;n un descalcificador para prolongar la vida del equipo. Asesoramos sin compromiso en la visita.</div>
      </details>
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
if (!empty($_proy)): ?>
<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Trabajos realizados</p>
    <h2>Proyectos de fontaner&iacute;a <span class="hl">en Pinoso</span></h2>
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
    <p class="zona-lbl">Cobertura en Pinoso</p>
    <h2>Fontanero urgente <span class="hl">en todo el t&eacute;rmino de Pinoso</span></h2>
    <p style="margin-bottom:1.5rem;color:#576574">Atendemos el casco urbano, todas las peda&ntilde;&iacute;as y las fincas rurales del t&eacute;rmino municipal de Pinoso (CP 03650).</p>
    <div class="zona-ztags" style="margin-bottom:2rem">
      <span class="zona-ztag-plain">Casco urbano</span>
      <span class="zona-ztag-plain">Casa de la Morera</span>
      <span class="zona-ztag-plain">Rodriguillo</span>
      <span class="zona-ztag-plain">Lel</span>
      <span class="zona-ztag-plain">Cañada del Trigo</span>
      <span class="zona-ztag-plain">Urbanizaciones periféricas</span>
      <span class="zona-ztag-plain">Fincas y diseminado rural</span>
    </div>
    <div style="border-radius:12px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.12)">
      <iframe src="https://maps.google.com/maps?q=38.4054,-1.0397&z=13&output=embed" width="100%" height="340" style="border:0;display:block" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Fontanero urgente en Pinoso"></iframe>
    </div>
  </div>
</section>

<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Mismo servicio en otras zonas</p>
    <h2>Fontanero urgente <span class="hl">en otros municipios</span></h2>
    <div class="zona-ztags">
      <a href="/fontanero/pinoso" class="zona-ztag" style="background:#1e3a5f;color:#fff">&larr; Todos los servicios en Pinoso</a>
      <a href="/fontanero/elda/urgencias" class="zona-ztag">Elda</a>
      <a href="/fontanero/petrer/urgencias" class="zona-ztag">Petrer</a>
      <a href="/fontanero/novelda/urgencias" class="zona-ztag">Novelda</a>
      <a href="/fontanero/monovar/urgencias" class="zona-ztag">Mon&oacute;var</a>
      <a href="/fontanero/sax/urgencias" class="zona-ztag">Sax</a>
      <a href="/fontanero/monforte-del-cid/urgencias" class="zona-ztag">Monforte del Cid</a>
      <a href="/fontanero/salinas/urgencias" class="zona-ztag">Salinas</a>
      <a href="/fontanero/aspe/urgencias" class="zona-ztag">Aspe</a>
    </div>
  </div>
</section>

<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>&iquest;Tienes una aver&iacute;a <span>en Pinoso?</span></h2>
    <p>Ll&aacute;manos. Presupuesto gratis antes de empezar, llegamos el mismo d&iacute;a.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; Llamar ahora</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">&#128172; WhatsApp</a>
    </div>
  </div>
</section>

<?php include '../../includes/footer.php'; ?>
