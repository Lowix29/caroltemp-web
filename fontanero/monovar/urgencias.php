<?php
/**
 * Fontanero urgente en Monóvar — Urgencias 24h
 * CarolTemp
 */
$meta_title  = 'Fontanero urgente en Monóvar 24h | Presupuesto gratis — CarolTemp';
$meta_desc   = 'Fontanero urgente en Monóvar disponible 24 horas. Fugas, desatascos, calderas, grupos de presión y viviendas rurales. Instaladores Nubeco certificados.';
$meta_url    = 'https://caroltemp.com/fontanero/monovar/urgencias';
$schema_type = 'local';
$page_css    = 'zona';
$page_js     = 'zona';

$faq_items = [
  [
    'q' => '¿Cuánto cuesta un fontanero de urgencia en Monóvar un domingo o festivo?',
    'a' => 'La mano de obra se cobra a desde 60 €/h con mínimo de una hora, más desde 25 € de desplazamiento a Monóvar incluidos en el presupuesto. Existe recargo por horario nocturno (desde las 22:00 h) y festivos — te lo informamos exactamente al contactar, antes de salir. El presupuesto es siempre gratuito y se da con la avería vista. Sin sorpresas al finalizar: el precio acordado es el que pagas.',
  ],
  [
    'q' => '¿Por qué se estropean tan rápido los grifos y electrodomésticos en Monóvar?',
    'a' => 'El agua de la comarca del Vinalopó tiene una dureza elevada. La cal se deposita en resistencias, juntas y válvulas acelerando su deterioro. En Monóvar es habitual sustituir termos cada 8-10 años en lugar de los 12-15 previstos, y ver grifos que gotean con menos de 5 años de uso. Un descalcificador bien dimensionado reduce ese deterioro significativamente. Asesoramos sin compromiso.',
  ],
  [
    'q' => '¿Qué hago si tengo una fuga de agua en plena noche en Monóvar?',
    'a' => '1. Cierra la llave de paso general de tu vivienda (en el armario del contador junto a la entrada, o en la llave de corte general si es vivienda con depósito propio). 2. Si el agua está cerca de enchufes o del cuadro eléctrico, corta la luz de esa zona. 3. Recoge el agua y avisa al vecino de abajo si vives en un piso. 4. Llámanos — atendemos 24 horas, el mismo día en Monóvar con máximo 3 horas desde el contacto para urgencias activas.',
  ],
  [
    'q' => '¿Atendéis viviendas sin conexión a red municipal en Monóvar?',
    'a' => 'Sí, es una parte importante de nuestro trabajo en Monóvar. Cubrimos viviendas con depósito propio, aljibe, pozo y grupo de presión, tanto en el casco urbano como en parcelas y casas de campo fuera de la red. El desplazamiento se incluye en el precio de la visita. Cuando llames, indícanos la ubicación aproximada para organizar bien la ruta y llevar el material adecuado.',
  ],
  [
    'q' => '¿Los fontaneros de CarolTemp están certificados para trabajar en Monóvar?',
    'a' => 'Sí. Somos instaladores Nubeco certificados, lo que nos habilita para instalaciones y reparaciones de gas, emisión de boletines oficiales y certificación de instalaciones ante los organismos de la Comunitat Valenciana. Contamos con seguro de responsabilidad civil. A diferencia de agregadores o plataformas, trabajamos con equipo propio — no subcontratamos.',
  ],
];

include '../../includes/head.php';
?>

<?php
$_hi     = getHeroImagen('urgencias-monovar');
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
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Fontanero urgente &middot; Mon&oacute;var &middot; CP 03640 &middot; 24h</div>
    <h1>Fontanero urgente en Mon&oacute;var<br><span class="hl">disponibles 24 horas para resolver cualquier aver&iacute;a</span></h1>
    <p class="hz-dark-sub">Casco urbano y viviendas rurales con dep&oacute;sito propio, aljibe o grupo de presi&oacute;n. Presupuesto gratuito con la aver&iacute;a vista. Sin formularios &mdash; llama directamente.</p>
    <div class="hz-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; 611 165 129</a>
      <a href="https://wa.me/34611165129" class="btn-hz-g">WhatsApp</a>
    </div>
  </div>
</section>

<div class="dif-strip">
  <div class="dif-strip-in">
    <div class="dif-item"><span class="dif-val">M&aacute;x. 3h desde el contacto</span><span class="dif-lbl">El mismo d&iacute;a, siempre</span></div>
    <div class="dif-item"><span class="dif-val">Presupuesto gratis</span><span class="dif-lbl">Con la aver&iacute;a vista, sin compromiso</span></div>
    <div class="dif-item"><span class="dif-val">&#9989; Nubeco certificados</span><span class="dif-lbl">Gas, calderas y equipo propio</span></div>
    <div class="dif-item"><span class="dif-val">Casco urbano y zona rural</span><span class="dif-lbl">Viviendas con dep&oacute;sito y aljibe</span></div>
  </div>
</div>

<!-- El ÚNICO H2 con "24 horas" -->
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Disponibilidad</p>
    <h2>Fontanero de urgencia en Mon&oacute;var <span class="hl">las 24 horas</span></h2>
    <div class="zona-prose" style="max-width:760px;margin:0 auto 2rem">
      <p>En Mon&oacute;var una avería puede ser especialmente crítica: si el grupo de presión de tu vivienda rural falla en agosto a las 3 de la madrugada, no tienes red municipal de respaldo. La vivienda se queda sin agua completamente. Para eso existe el servicio 24 horas real — no un contestador o un formulario que alguien revisará mañana.</p>
      <p>Atendemos Mon&oacute;var los 365 días del año, incluidos los festivos locales de Moros y Cristianos y Navidad. El recargo nocturno y festivo lo informamos antes de salir — el precio acordado es el que pagas.</p>
    </div>
    <div class="zona-svc" style="margin-top:0">
      <div class="zona-sc">
        <span class="zona-sc-n">NOCHES</span>
        <h3>Urgencias nocturnas en Mon&oacute;var</h3>
        <p>De 22:00 a 8:00 h. Recargo nocturno informado antes de salir. Priorizamos viviendas sin agua total, fugas activas y grupos de presión parados en verano.</p>
      </div>
      <div class="zona-sc">
        <span class="zona-sc-n">FESTIVOS</span>
        <h3>Domingos y festivos locales</h3>
        <p>Moros y Cristianos, festivos de agosto, Navidad. Misma calidad de servicio. Presupuesto gratuito con la avería vista antes de empezar, también en festivo.</p>
      </div>
      <div class="zona-sc">
        <span class="zona-sc-n">RURAL</span>
        <h3>Viviendas rurales y fincas</h3>
        <p>Casas de campo, chalets y fincas fuera de la red municipal. Grupos de presión, aljibes, depósitos y fosas sépticas. Desplazamiento incluido en el presupuesto.</p>
      </div>
    </div>
  </div>
</section>

<!-- Servicios -->
<section class="zona-sec zona-sec-alt">
  <div class="cta-dark-con">
    <p class="zona-lbl">Qu&eacute; reparamos</p>
    <h2>Servicios de fontan&eacute;r&iacute;a <span class="hl">en Mon&oacute;var</span></h2>
    <div class="zona-svc" style="margin-top:2rem">

      <div class="zona-sc">
        <span class="zona-sc-n">FUGAS</span>
        <h3>Reparaci&oacute;n de fugas en viviendas antiguas de Mon&oacute;var</h3>
        <p>Casco hist&oacute;rico con edificios de los a&ntilde;os 60-70 y tuber&iacute;as de hierro galvanizado que ceden sin aviso. Localizaci&oacute;n con ge&oacute;fono sin romper paredes. Presupuesto antes de abrir.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">DESATASCOS</span>
        <h3>Desatascos urgentes con maquinaria profesional</h3>
        <p>WC, fregaderos, lavabos, lavadoras, lavavajillas y bajantes. C&aacute;mara endosc&oacute;pica e hidrojetting. El agua dura del Vinalop&oacute; acelera el sarro en tuber&iacute;as de peque&ntilde;o di&aacute;metro.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">PRESIÓN</span>
        <h3>Grupos de presi&oacute;n y dep&oacute;sitos</h3>
        <p>La aver&iacute;a m&aacute;s frecuente en viviendas rurales de Mon&oacute;var. Presostato, membrana, v&aacute;lvula de boya, aljibe y dep&oacute;sito. Diagn&oacute;stico y presupuesto en la misma visita.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">CALDERAS</span>
        <h3>Calderas, termos y climatizaci&oacute;n</h3>
        <p>Caldera averiada, termo sin agua caliente o fuga en el circuito de calefacci&oacute;n. En verano con +38&deg;C en Mon&oacute;var, tambi&eacute;n atendemos desag&uuml;es de splits y fugas en tubos de condensaci&oacute;n.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">CISTERNAS</span>
        <h3>Cisternas y grifer&iacute;a</h3>
        <p>Cisterna que no retiene agua, grifo que no cierra o llave de paso agarrotada. Instalaci&oacute;n y cambio de grifer&iacute;a con presupuesto previo y sin visita de diagn&oacute;stico cobrada aparte.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">COMUNIDADES</span>
        <h3>Comunidades y locales comerciales</h3>
        <p>Urgencias en zonas comunes, locales y naves en Mon&oacute;var. Factura a nombre de la comunidad y documentaci&oacute;n para reclamaci&oacute;n al seguro del edificio si procede.</p>
      </div>

    </div>
  </div>
</section>

<!-- Por qué CarolTemp -->
<section class="zona-sec">
  <div class="cta-dark-con">
    <div class="zona-tcol">
      <div>
        <p class="zona-lbl">Por qu&eacute; elegirnos en Mon&oacute;var</p>
        <h2>Instaladores certificados <span class="hl">con equipo propio en Mon&oacute;var</span></h2>
        <div class="zona-prose">
          <p>Mon&oacute;var tiene dos realidades muy distintas: el casco hist&oacute;rico con edificios de los a&ntilde;os 60-70 que nunca han renovado la instalaci&oacute;n, y la zona rural con viviendas que funcionan completamente al margen de la red municipal. La mayor&iacute;a de fontaneros gestionan bien la primera situaci&oacute;n pero no la segunda — no llevan material para grupos de presi&oacute;n, no conocen el sistema de aljibe y dep&oacute;sito propio, y no est&aacute;n habituados a trabajar en fincas fuera del n&uacute;cleo urbano.</p>
          <p>Somos instaladores Nubeco certificados y trabajamos con equipo propio — no somos una plataforma que deriva a terceros. Eso significa que el t&eacute;cnico que llega es el mismo que da el presupuesto, hace el trabajo y firma la garant&iacute;a.</p>
        </div>
        <ul class="zona-chk">
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Equipo propio &mdash; no subcontratamos</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Instaladores Nubeco certificados &mdash; gas y calderas</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Presupuesto gratuito &mdash; incluso en nocturno y festivo</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Viviendas rurales con dep&oacute;sito, aljibe y fosa s&eacute;ptica</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Seguro RC y garant&iacute;a escrita en todos los trabajos</li>
        </ul>
      </div>
      <div>
        <div class="icard">
          <div class="icard-head">Urgencia en Mon&oacute;var: lo es si...</div>
          <div class="icard-body">
            <div class="icard-row"><span class="icard-icon">☀️</span><span>Grupo de presi&oacute;n parado en verano, vivienda sin agua</span></div>
            <div class="icard-row"><span class="icard-icon">💧</span><span>Agua corriendo libre que no puedes cortar</span></div>
            <div class="icard-row"><span class="icard-icon">🚿</span><span>Fosa s&eacute;ptica colapsada, desag&uuml;es sin salida</span></div>
            <div class="icard-row"><span class="icard-icon">🔥</span><span>Caldera o calentador averiado sin agua caliente</span></div>
            <div class="icard-row"><span class="icard-icon">💦</span><span>Fuga entre pisos afectando al vecino de abajo</span></div>
            <div class="icard-row"><span class="icard-icon">🚰</span><span>Llave de paso agarrotada que no corta el suministro</span></div>
          </div>
          <a href="tel:+34611165129" class="zona-icard-btn">&#128222; Llamar ahora</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Problemas frecuentes en Monóvar -->
<section class="zona-sec zona-sec-alt">
  <div class="cta-dark-con">
    <p class="zona-lbl">Problemas espec&iacute;ficos de Mon&oacute;var</p>
    <h2>Agua dura y viviendas antiguas <span class="hl">los dos retos de la fontaner&iacute;a en Mon&oacute;var</span></h2>
    <div class="zona-prose" style="max-width:760px;margin:0 auto 2rem">
      <p>La dureza del agua en la comarca del Vinalop&oacute; es una de las causas m&aacute;s frecuentes de aver&iacute;a en Mon&oacute;var. La cal se deposita en la resistencia del termo, en las juntas de los grifos, en el interior de las calderas y en las tuber&iacute;as de peque&ntilde;o di&aacute;metro. El resultado: equipos que duran menos, grifos que gotean a los 4-5 a&ntilde;os y consumos de agua que suben sin explicaci&oacute;n visible.</p>
      <p>El casco hist&oacute;rico de Mon&oacute;var a&ntilde;ade otra capa de complejidad: edificios de los a&ntilde;os 60-70 con instalaciones de hierro galvanizado que nunca se han renovado. Estas tuber&iacute;as acumulan corrosión interna durante a&ntilde;os — el primer s&iacute;ntoma suele ser una mancha de humedad en la pared, pero el da&ntilde;o lleva meses activo antes de aparecer.</p>
    </div>
    <div class="zona-svc" style="margin-top:0">
      <div class="zona-sc">
        <span class="zona-sc-n">AGUA DURA</span>
        <h3>Efectos de la cal en instalaciones de Mon&oacute;var</h3>
        <p>Termos que duran 8-10 a&ntilde;os en lugar de 15. Grifos que gotean con menos de 5 a&ntilde;os. Calderas con rendimiento reducido. Un descalcificador bien dimensionado reduce ese desgaste y se amortiza en 2-3 a&ntilde;os.</p>
      </div>
      <div class="zona-sc">
        <span class="zona-sc-n">CASAS ANTIGUAS</span>
        <h3>Tuber&iacute;as antiguas en el casco hist&oacute;rico</h3>
        <p>Hierro galvanizado corroído que cede sin avisar. Microfugas que saturan la pared desde dentro antes de que aparezca la mancha. Localizamos el tramo afectado con ge&oacute;fono y sustituimos sin destrozos innecesarios.</p>
      </div>
      <div class="zona-sc">
        <span class="zona-sc-n">VERANO</span>
        <h3>Averías en &eacute;poca de calor</h3>
        <p>Con +38&deg;C en verano en Mon&oacute;var, los splits trabajan al l&iacute;mite y sus desag&uuml;es se atascan. Tambi&eacute;n aumentan las roturas en tuber&iacute;as exteriores expuestas al sol directo. Atendemos fontaner&iacute;a y climatizaci&oacute;n bajo un mismo servicio.</p>
      </div>
    </div>
  </div>
</section>

<!-- Zonas de cobertura -->
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Cobertura en Mon&oacute;var</p>
    <h2>Atendemos <span class="hl">todo el t&eacute;rmino municipal</span></h2>
    <p style="margin-bottom:1.5rem;color:#576574">Casco urbano, zona rural y diseminado. Mismo servicio sin recargo por distancia dentro del municipio.</p>
    <div class="zona-ztags">
      <span class="zona-ztag-plain">Casco hist&oacute;rico</span>
      <span class="zona-ztag-plain">Centro urbano</span>
      <span class="zona-ztag-plain">Zona residencial</span>
      <span class="zona-ztag-plain">Casas de campo</span>
      <span class="zona-ztag-plain">Fincas y parcelas rurales</span>
      <span class="zona-ztag-plain">Viviendas con dep&oacute;sito propio</span>
      <span class="zona-ztag-plain">Urbanizaciones</span>
    </div>
  </div>
</section>

<!-- Proceso -->
<section class="zona-sec zona-sec-alt">
  <div class="cta-dark-con">
    <p class="zona-lbl">Sin sorpresas</p>
    <h2>Qu&eacute; pasa <span class="hl">cuando llamas a CarolTemp en Mon&oacute;var</span></h2>
    <div class="zona-steps">
      <div class="zona-step">
        <div class="zona-step-n">1</div>
        <div class="zona-step-txt">
          <strong>Llamas y nos describes la aver&iacute;a</strong>
          <p>Tel&eacute;fono o WhatsApp, sin formularios. Nos cuentas qu&eacute; pasa. Si la vivienda es rural o tiene instalaci&oacute;n propia, ind&iacute;calo para llevar el material adecuado. Para urgencias activas en Mon&oacute;var priorizamos la salida &mdash; m&aacute;ximo 3 horas desde el contacto.</p>
        </div>
      </div>
      <div class="zona-step">
        <div class="zona-step-n">2</div>
        <div class="zona-step-txt">
          <strong>Llegamos y presupuestamos con la aver&iacute;a vista</strong>
          <p>Inspeccionamos en persona y damos el precio definitivo cerrado. Sin tocar nada hasta que lo apruebes. Para fugas en tuber&iacute;as empotradas usamos ge&oacute;fono &mdash; el punto exacto marcado antes de abrir.</p>
        </div>
      </div>
      <div class="zona-step">
        <div class="zona-step-n">3</div>
        <div class="zona-step-txt">
          <strong>Reparamos y dejamos la instalaci&oacute;n funcionando</strong>
          <p>Si hay material en la furgoneta, terminamos en la primera visita. El precio final es el acordado &mdash; sin a&ntilde;adidos. Emitimos factura y garant&iacute;a por escrito.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Tarifas -->
<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Precios transparentes</p>
    <h2>Tarifas fontanero urgente <span class="hl">Mon&oacute;var</span></h2>
    <p style="color:#576574;margin-bottom:.5rem">El presupuesto definitivo se da siempre antes de empezar, con la aver&iacute;a vista. Sin letra peque&ntilde;a.</p>
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
        <span class="zona-precio-concepto">Desplazamiento a Mon&oacute;var</span>
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

<!-- FAQs -->
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Preguntas frecuentes</p>
    <h2>Fontanero urgente Mon&oacute;var <span class="hl">&mdash; dudas habituales</span></h2>
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
    </div>
  </div>
</section>

<!-- Proyectos -->
<?php
$_proy = [];
try {
  $_ps = $pdo->prepare('SELECT titulo, slug, descripcion, servicio, imagen FROM proyectos WHERE publicado=1 AND (zona LIKE ? OR zona LIKE ?) ORDER BY fecha DESC LIMIT 3');
  $_ps->execute(['%Mon_var%', '%Monovar%']);
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
    <h2>Proyectos de fontaner&iacute;a urgente <span class="hl">en Mon&oacute;var</span></h2>
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

<!-- Mapa -->
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Zona de cobertura</p>
    <h2>Fontanero urgente <span class="hl">en Mon&oacute;var</span></h2>
    <p style="margin-bottom:1.5rem;color:#576574">Atendemos toda la localidad de Mon&oacute;var (CP 03640) y municipios lim&iacute;trofes.</p>
    <div style="border-radius:12px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.12)">
      <iframe src="https://maps.google.com/maps?q=38.3756,-0.8314&z=14&output=embed" width="100%" height="380" style="border:0;display:block" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Fontanero urgente en Monóvar"></iframe>
    </div>
  </div>
</section>

<!-- Zona tags -->
<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Mismo servicio en otras zonas</p>
    <h2>Fontanero urgente <span class="hl">en otros municipios</span></h2>
    <div class="zona-ztags">
      <a href="/fontanero/monovar" class="zona-ztag" style="background:#1e3a5f;color:#fff">&#8592; Todos los servicios en Mon&oacute;var</a>
      <a href="/fontanero/elda/urgencias" class="zona-ztag">Elda</a>
      <a href="/fontanero/petrer/urgencias" class="zona-ztag">Petrer</a>
      <a href="/fontanero/novelda/urgencias" class="zona-ztag">Novelda</a>
      <a href="/fontanero/aspe/urgencias" class="zona-ztag">Aspe</a>
      <a href="/fontanero/pinoso/urgencias" class="zona-ztag">Pinoso</a>
      <a href="/fontanero/sax/urgencias" class="zona-ztag">Sax</a>
      <a href="/fontanero/salinas/urgencias" class="zona-ztag">Salinas</a>
      <a href="/fontanero/monforte-del-cid/urgencias" class="zona-ztag">Monforte del Cid</a>
    </div>
  </div>
</section>

<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>&iquest;Tienes una aver&iacute;a urgente <span>en Mon&oacute;var?</span></h2>
    <p>Atendemos el mismo d&iacute;a. Presupuesto gratuito antes de empezar.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; Llamar ahora</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">&#128172; WhatsApp</a>
    </div>
  </div>
</section>

<?php include '../../includes/footer.php'; ?>
