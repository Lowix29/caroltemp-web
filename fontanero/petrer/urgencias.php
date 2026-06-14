<?php
/**
 * Fontanero urgente en Petrer — Urgencias 24h
 * CarolTemp
 */
$meta_title  = 'Fontanero urgente en Petrer 24h | Presupuesto gratis — CarolTemp';
$meta_desc   = 'Fontanero urgente en Petrer disponible 24 horas. Diagnóstico preciso, reparación inmediata y presupuesto gratuito antes de empezar. Festivos incluidos.';
$meta_url    = 'https://caroltemp.com/fontanero/petrer/urgencias';
$schema_type = 'local';
$page_css    = 'zona';
$page_js     = 'zona';

$faq_items = [
  [
    'q' => '¿Cuánto cuesta un fontanero urgente en Petrer fuera de horario?',
    'a' => 'La mano de obra se cobra a desde 60 €/h con mínimo de una hora. El desplazamiento a Petrer desde 25 € según distancia incluidos en el presupuesto. Existe recargo por horario nocturno (desde las 22:00 h) y festivos; te lo informamos exactamente al contactar, antes de salir. El presupuesto es gratuito y se da siempre con la avería vista, sin sorpresas al finalizar.',
  ],
  [
    'q' => '¿En cuánto tiempo puede llegar un fontanero urgente a Petrer?',
    'a' => 'Atendemos en Petrer el mismo día, con un máximo de 3 horas desde el contacto. El servicio es real 24 horas al día, los 365 días del año, incluidos domingos y festivos locales. Para urgencias activas con agua corriendo, priorizamos el desplazamiento.',
  ],
  [
    'q' => '¿Qué hago si tengo una fuga de agua mientras espero al fontanero en Petrer?',
    'a' => '1. Cierra la llave de paso general de tu vivienda (normalmente en el armario del contador, junto a la puerta de entrada). 2. Si el agua está cerca de enchufes o cuadro eléctrico, corta también la luz de esa zona desde el diferencial. 3. Recoge el agua que puedas para evitar daños en suelos y techos del vecino de abajo. 4. Haz fotos del escape antes de actuar — pueden ser útiles si tienes que reclamar al seguro del hogar.',
  ],
  [
    'q' => '¿Puedo conseguir presupuesto gratis antes de contratar la reparación urgente en Petrer?',
    'a' => 'Sí, siempre. El presupuesto es gratuito y se da con la avería vista, antes de tocar nada. Incluso en urgencias nocturnas y festivos. No cobramos la visita de diagnóstico aparte: si aceptas el presupuesto, el desplazamiento ya está incluido. Si decides no contratar, no pagas nada.',
  ],
  [
    'q' => '¿Los fontaneros de CarolTemp están certificados para trabajar en Petrer?',
    'a' => 'Sí. Somos instaladores Nubeco certificados, lo que nos habilita para instalaciones y reparaciones de gas, emisión de boletines oficiales y certificación de instalaciones ante los organismos de la Comunitat Valenciana. Contamos con seguro de responsabilidad civil y todos los trabajos quedan con garantía por escrito.',
  ],
];

include '../../includes/head.php';
?>

<?php
$_hi     = getHeroImagen('urgencias-petrer');
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
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Fontanero urgente &middot; Petrer &middot; CP 03610 &middot; 24h</div>
    <h1>Fontanero urgente en Petrer<br><span class="hl">con diagnóstico preciso y reparación inmediata</span></h1>
    <p class="hz-dark-sub">Presupuesto gratuito con la avería vista, antes de tocar nada. Disponibles 24 horas, domingos y festivos incluidos. Sin formularios — llama directamente.</p>
    <div class="hz-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; 611 165 129</a>
      <a href="https://wa.me/34611165129" class="btn-hz-g">WhatsApp</a>
    </div>
  </div>
</section>

<div class="dif-strip">
  <div class="dif-strip-in">
    <div class="dif-item"><span class="dif-val">Máx. 3h desde el contacto</span><span class="dif-lbl">Mismo día en toda la localidad</span></div>
    <div class="dif-item"><span class="dif-val">Presupuesto gratis</span><span class="dif-lbl">Incluso en urgencias nocturnas</span></div>
    <div class="dif-item"><span class="dif-val">&#9989; Nubeco certificados</span><span class="dif-lbl">Gas, boletines y garantía escrita</span></div>
    <div class="dif-item"><span class="dif-val">24h / 365 días</span><span class="dif-lbl">Festivos sin recargo oculto</span></div>
  </div>
</div>

<!-- Por qué CarolTemp -->
<section class="zona-sec">
  <div class="cta-dark-con">
    <div class="zona-tcol">
      <div>
        <p class="zona-lbl">Por qué elegirnos</p>
        <h2>Fontanero urgente 24h en Petrer <span class="hl">sin formularios ni esperas</span></h2>
        <div class="zona-prose">
          <p>Petrer tiene una geografía que complica las urgencias: urbanizaciones en ladera con diferencias de presión entre zonas altas y bajas, casco histórico con tuberías antiguas de hierro galvanizado y naves industriales en el polígono que necesitan respuesta rápida para no parar la producción. Cada tipo de avería requiere material diferente — y llegar con lo correcto es la diferencia entre resolver en la primera visita o volver otro día.</p>
          <p>Trabajamos con la furgoneta equipada para lo más frecuente en Petrer: válvulas reductoras de presión, grupos de presión, tuberías de cobre y multicapa, llaves de paso, repuestos de calentador. Presupuesto cerrado con la avería vista — sin tocar nada hasta que lo apruebes.</p>
        </div>
        <ul class="zona-chk">
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Atendemos el mismo día — máximo 3 horas desde el contacto</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Instaladores Nubeco certificados — emitimos boletines de gas</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Precio cerrado antes de empezar — incluso en urgencias nocturnas</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Reparación en la misma visita si hay material disponible</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Garantía escrita en todos los trabajos</li>
        </ul>
      </div>
      <div>
        <div class="icard">
          <div class="icard-head">¿Urgencia en Petrer? Lo es si...</div>
          <div class="icard-body">
            <div class="icard-row"><span class="icard-icon">💧</span><span>Hay agua corriendo libre que no puedes cortar</span></div>
            <div class="icard-row"><span class="icard-icon">⚙️</span><span>Presión cero en zona alta pero no en la baja</span></div>
            <div class="icard-row"><span class="icard-icon">🏢</span><span>Bloque en ladera sin agua en todos los pisos</span></div>
            <div class="icard-row"><span class="icard-icon">🔥</span><span>Caldera o calentador averiado sin agua caliente</span></div>
            <div class="icard-row"><span class="icard-icon">💦</span><span>Agua saliendo por el techo del local de abajo</span></div>
            <div class="icard-row"><span class="icard-icon">🚰</span><span>La llave de paso no corta y hay fuga activa</span></div>
          </div>
          <a href="tel:+34611165129" class="zona-icard-btn">&#128222; Llamar ahora</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Urgencias más frecuentes -->
<section class="zona-sec zona-sec-alt">
  <div class="cta-dark-con">
    <p class="zona-lbl">Qué reparamos</p>
    <h2>Urgencias de fontanería 24h <span class="hl">más frecuentes en Petrer</span></h2>
    <div class="zona-svc" style="margin-top:2rem">

      <div class="zona-sc">
        <span class="zona-sc-n">FUGAS</span>
        <h3>Reparación urgente de fugas y tuberías reventadas</h3>
        <p>Tuberías de hierro galvanizado en el casco histórico que rompen sin avisar. Localizamos el escape con geófono si está empotrado, marcamos el punto exacto y presupuestamos antes de abrir ninguna pared. Precio cerrado.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">DESATASCOS</span>
        <h3>Desatascos de WC, fregaderos y desagües</h3>
        <p>Bajantes, arquetas e inodoros bloqueados. El agua dura del Vinalopó acelera la acumulación de sarro en tuberías de pequeño diámetro en Petrer. Cámara endoscópica e hidrojetting — diagnóstico antes de actuar.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">PRESIÓN</span>
        <h3>Grupos de presión en urbanizaciones de ladera</h3>
        <p>El grupo de presión es la avería urgente más frecuente en El Monastil y otras urbanizaciones altas de Petrer. Presostato, membrana y circuito eléctrico revisados en la misma visita. Presupuesto antes de empezar.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">CALDERAS</span>
        <h3>Calentadores, termos y calderas</h3>
        <p>Termo sin agua caliente, calentador que no enciende o caldera averiada. El agua dura deteriora resistencias y válvulas antes de lo previsto en Petrer. Reparación o sustitución en la misma visita.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">CISTERNAS</span>
        <h3>Cisternas rotas y grifería</h3>
        <p>Cisterna que no para de correr, que no retiene agua o flotador roto. También instalación y cambio de grifería y sanitarios con presupuesto previo y sin visita de diagnóstico cobrada aparte.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">COMUNIDADES</span>
        <h3>Fontanería para comunidades y locales comerciales</h3>
        <p>Urgencias en zonas comunes, locales del polígono industrial y naves de Petrer. Factura a nombre de la comunidad o empresa. Documentación para reclamación al seguro del edificio si procede.</p>
      </div>

    </div>
  </div>
</section>

<!-- Qué hacer mientras espera -->
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Antes de que lleguemos</p>
    <h2>Qué hacer en una fuga <span class="hl">mientras esperas al fontanero</span></h2>
    <div class="zona-steps">
      <div class="zona-step">
        <div class="zona-step-n">1</div>
        <div class="zona-step-txt">
          <strong>Cierra la llave de paso general</strong>
          <p>Está en el armario del contador, normalmente junto a la puerta de entrada de la vivienda. Si no la encuentras, llámanos — te guiamos por teléfono. Cortar el suministro frena el daño inmediatamente.</p>
        </div>
      </div>
      <div class="zona-step">
        <div class="zona-step-n">2</div>
        <div class="zona-step-txt">
          <strong>Corta la electricidad si el agua está cerca</strong>
          <p>Si el escape está cerca de enchufes, cuadro eléctrico o electrodomésticos, actúa el diferencial de esa zona desde el cuadro. Agua y electricidad juntas son el principal riesgo en una urgencia doméstica.</p>
        </div>
      </div>
      <div class="zona-step">
        <div class="zona-step-n">3</div>
        <div class="zona-step-txt">
          <strong>Recoge el agua y avisa al vecino de abajo</strong>
          <p>Usa cubos, toallas o lo que tengas a mano para contener el agua. Si vives en un piso, avisa al vecino de abajo — puede que el agua ya esté llegando a su techo sin que lo sepa todavía.</p>
        </div>
      </div>
      <div class="zona-step">
        <div class="zona-step-n">4</div>
        <div class="zona-step-txt">
          <strong>Haz fotos del escape antes de actuar</strong>
          <p>Documentar el estado inicial puede ser útil si después hay que reclamar al seguro del hogar. Las fotos con fecha y hora son la mejor prueba del alcance del daño en el momento del siniestro.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Barrios de Petrer -->
<section class="zona-sec zona-sec-alt">
  <div class="cta-dark-con">
    <p class="zona-lbl">Zonas de Petrer donde atendemos urgencias</p>
    <h2>Cubrimos <span class="hl">todo Petrer sin excepción</span></h2>
    <p style="margin-bottom:1.5rem;color:#576574">Casco histórico, urbanizaciones en ladera y polígono industrial. Misma rapidez en toda la localidad.</p>
    <div class="zona-ztags">
      <span class="zona-ztag-plain">Casco histórico</span>
      <span class="zona-ztag-plain">El Monastil</span>
      <span class="zona-ztag-plain">Zona alta del castillo</span>
      <span class="zona-ztag-plain">Urbanizaciones de ladera</span>
      <span class="zona-ztag-plain">Sector Oeste</span>
      <span class="zona-ztag-plain">Polígono Industrial</span>
      <span class="zona-ztag-plain">Zona escuelas</span>
    </div>
  </div>
</section>

<!-- Tarifas -->
<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Precios transparentes</p>
    <h2>Tarifas fontanero urgente <span class="hl">Petrer</span></h2>
    <p style="color:#576574;margin-bottom:.5rem">El presupuesto definitivo se da siempre antes de empezar, con la avería vista. Sin letra pequeña.</p>
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
        <span class="zona-precio-concepto">Desplazamiento a Petrer</span>
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
    <h2>Fontanero urgente Petrer <span class="hl">— dudas habituales</span></h2>
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
  $_ps = $pdo->prepare('SELECT titulo, slug, descripcion, servicio, imagen FROM proyectos WHERE publicado=1 AND zona LIKE ? ORDER BY fecha DESC LIMIT 3');
  $_ps->execute(['%Petrer%']);
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
    <h2>Proyectos de fontaner&iacute;a urgente <span class="hl">en Petrer</span></h2>
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
    <h2>Fontanero urgente <span class="hl">en Petrer</span></h2>
    <p style="margin-bottom:1.5rem;color:#576574">Atendemos toda la localidad de Petrer (CP 03610) y municipios lim&iacute;trofes del Valle del Vinalop&oacute;.</p>
    <div style="border-radius:12px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.12)">
      <iframe src="https://maps.google.com/maps?q=38.4762,-0.7784&z=14&output=embed" width="100%" height="380" style="border:0;display:block" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Fontanero urgente en Petrer"></iframe>
    </div>
  </div>
</section>

<!-- Zona tags -->
<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Mismo servicio en otras zonas</p>
    <h2>Fontanero urgente <span class="hl">en otros municipios</span></h2>
    <div class="zona-ztags">
      <a href="/fontanero/petrer" class="zona-ztag" style="background:#1e3a5f;color:#fff">&#8592; Todos los servicios en Petrer</a>
      <a href="/fontanero/elda/urgencias" class="zona-ztag">Elda</a>
      <a href="/fontanero/novelda/urgencias" class="zona-ztag">Novelda</a>
      <a href="/fontanero/aspe/urgencias" class="zona-ztag">Aspe</a>
      <a href="/fontanero/pinoso/urgencias" class="zona-ztag">Pinoso</a>
      <a href="/fontanero/monovar/urgencias" class="zona-ztag">Mon&oacute;var</a>
      <a href="/fontanero/sax/urgencias" class="zona-ztag">Sax</a>
      <a href="/fontanero/monforte-del-cid/urgencias" class="zona-ztag">Monforte del Cid</a>
    </div>
  </div>
</section>

<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>&iquest;Tienes una aver&iacute;a urgente <span>en Petrer?</span></h2>
    <p>Ll&aacute;menos. Atendemos el mismo d&iacute;a. Precio antes de empezar.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; Llamar ahora</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">&#128172; WhatsApp</a>
    </div>
  </div>
</section>

<?php
$ciudad = 'petrer';
$servicio = 'urgencias';
include '../../includes/resenas-section.php';
?>
<?php include '../../includes/galeria-section.php'; ?>
<?php include '../../includes/footer.php'; ?>
