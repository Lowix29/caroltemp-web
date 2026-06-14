<?php
/**
 * Fontanero urgente en Elda — Urgencias 24h
 * CarolTemp
 */
$meta_title  = 'Fontanero urgente en Elda 24h | Presupuesto gratis — CarolTemp';
$meta_desc   = 'Fontanero urgente en Elda disponible 24 horas. Atendemos el mismo día, en un máximo de 3 horas desde el contacto. Presupuesto gratuito antes de empezar.';
$meta_url    = 'https://caroltemp.com/fontanero/elda/urgencias';
$schema_type = 'local';
$page_css    = 'zona';
$page_js     = 'zona';

$faq_items = [
  [
    'q' => '¿Cuánto tarda un fontanero urgente en llegar a Elda?',
    'a' => 'Atendemos en Elda el mismo día, con un máximo de 3 horas desde el contacto. Cubrimos 24 horas al día, 365 días al año, incluidos domingos y festivos. Llegamos tanto al casco histórico y al Centro como a barrios como El Chopo, Sector Oeste, Las 400 Viviendas o los polígonos industriales. Para urgencias activas con agua corriendo libre, priorizamos el desplazamiento.',
  ],
  [
    'q' => '¿Cuánto cuesta cambiar un termo eléctrico de 80 litros en Elda?',
    'a' => 'El precio depende de la marca del equipo, la capacidad y la accesibilidad de la instalación. El presupuesto es gratuito y se da siempre antes de empezar: incluye equipo y mano de obra, sin sorpresas al finalizar. El agua dura del Vinalopó deteriora las resistencias antes de lo previsto, por eso en Elda es habitual sustituir termos cada 8-10 años en lugar de los 12-15 de zonas con agua blanda.',
  ],
  [
    'q' => '¿Los fontaneros de CarolTemp tienen certificación de gas en Elda?',
    'a' => 'Sí. Somos instaladores Nubeco certificados, lo que nos habilita para realizar instalaciones y reparaciones de gas, emitir boletines oficiales y certificar instalaciones ante los organismos competentes de la Comunitat Valenciana. Esto es especialmente importante en Elda para cambios de caldera, instalaciones nuevas o revisiones obligatorias.',
  ],
  [
    'q' => '¿Atienden emergencias de fontanería en Elda los domingos y festivos?',
    'a' => 'Sí, el servicio es 24/7/365. Domingos, festivos locales de Elda y noches incluidos. Si la urgencia es activa (agua corriendo, presión cero en el edificio), llama directamente al teléfono — no hace falta formulario ni esperar respuesta de email. Existe recargo por horario nocturno (desde las 22:00 h) y festivo; te lo informamos al contactar antes de salir.',
  ],
  [
    'q' => '¿Qué garantía tienen las reparaciones urgentes en Elda?',
    'a' => 'Todas las reparaciones quedan garantizadas. En materiales nuevos instalados (termos, calderas, grupos de presión) aplica la garantía del fabricante. El trabajo de mano de obra tiene garantía propia. Si algo falla en lo que hemos reparado, volvemos sin coste adicional. El precio que acordamos antes de empezar es el que pagas — sin añadidos ni letra pequeña.',
  ],
];

include '../../includes/head.php';
?>

<?php
$_hi     = getHeroImagen('urgencias-elda');
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
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Fontanero urgente &middot; Elda &middot; CP 03600 &middot; 24h</div>
    <h1>Fontanero urgente en Elda<br><span class="hl">con soluciones profesionales desde la primera visita</span></h1>
    <p class="hz-dark-sub">Atendemos el mismo día en Elda, con un máximo de 3 horas desde el contacto. Precio cerrado con la avería vista, antes de tocar nada. Sin formularios — llama directamente.</p>
    <div class="hz-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; 611 165 129</a>
      <a href="https://wa.me/34611165129" class="btn-hz-g">WhatsApp</a>
    </div>
  </div>
</section>

<div class="dif-strip">
  <div class="dif-strip-in">
    <div class="dif-item"><span class="dif-val">Máx. 3h desde el contacto</span><span class="dif-lbl">Prioridad en urgencias activas</span></div>
    <div class="dif-item"><span class="dif-val">Precio cerrado</span><span class="dif-lbl">Se da antes de empezar, siempre</span></div>
    <div class="dif-item"><span class="dif-val">&#9989; Nubeco certificados</span><span class="dif-lbl">Instaladores oficiales de gas</span></div>
    <div class="dif-item"><span class="dif-val">24h / 365 días</span><span class="dif-lbl">Domingos y festivos incluidos</span></div>
  </div>
</div>

<!-- Por qué CarolTemp en Elda -->
<section class="zona-sec">
  <div class="cta-dark-con">
    <div class="zona-tcol">
      <div>
        <p class="zona-lbl">Por qué elegir CarolTemp en Elda</p>
        <h2>Urgencias sin formularios <span class="hl">ni esperas innecesarias</span></h2>
        <div class="zona-prose">
          <p>Cuando hay agua corriendo en Elda, cada minuto importa. La diferencia entre un fontanero urgente que atiende el mismo día y uno que te pide que rellenes un formulario y espera a que "alguien te contacte" puede ser la diferencia entre un trabajo puntual y daños que se extienden al piso de abajo.</p>
          <p>Trabajamos con la furgoneta equipada para las averías más frecuentes en Elda: llaves de corte, tuberías de cobre y multicapa, válvulas, elementos de calentador, repuestos de grupo de presión. La mayoría de urgencias quedan resueltas en la primera visita, sin segunda vuelta y sin dejar la instalación a medias.</p>
        </div>
        <ul class="zona-chk">
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Atendemos el mismo día — máx. 3 horas desde el contacto</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Instaladores Nubeco certificados — emitimos boletines de gas</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Precio cerrado antes de empezar — sin añadidos al finalizar</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Domingos y festivos sin excusas — misma rapidez de respuesta</li>
          <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Reparación en la misma visita si hay material disponible</li>
        </ul>
      </div>
      <div>
        <div class="icard">
          <div class="icard-head">¿Es urgencia en Elda? Lo es si...</div>
          <div class="icard-body">
            <div class="icard-row"><span class="icard-icon">💧</span><span>Hay agua corriendo libre que no puedes cortar</span></div>
            <div class="icard-row"><span class="icard-icon">🔩</span><span>Tubería de hierro reventada en bloque antiguo</span></div>
            <div class="icard-row"><span class="icard-icon">🏢</span><span>Presión cero en todo el edificio o la nave</span></div>
            <div class="icard-row"><span class="icard-icon">🔥</span><span>Caldera comunitaria averiada sin agua caliente</span></div>
            <div class="icard-row"><span class="icard-icon">💦</span><span>Agua saliendo por el techo del local de abajo</span></div>
            <div class="icard-row"><span class="icard-icon">🚰</span><span>La llave de paso no corta y hay fuga activa</span></div>
          </div>
          <a href="tel:+34611165129" class="zona-icard-btn">&#128222; Llamar ahora</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Servicios urgentes en Elda -->
<section class="zona-sec zona-sec-alt">
  <div class="cta-dark-con">
    <p class="zona-lbl">Qué reparamos</p>
    <h2>Servicios de fontanería urgente <span class="hl">en Elda</span></h2>
    <div class="zona-svc" style="margin-top:2rem">

      <div class="zona-sc">
        <span class="zona-sc-n">DESATASCOS</span>
        <h3>Desatascos urgentes en Elda</h3>
        <p>Bajantes, arquetas, inodoros y fregaderos. El agua dura del Vinalopó acelera la acumulación de sarro en tuberías de pequeño diámetro. Usamos cámara endoscópica e hidrojetting — diagnóstico antes de actuar, precio cerrado antes de empezar.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">CALDERAS</span>
        <h3>Reparación de calderas urgente en Elda</h3>
        <p>Caldera individual o comunitaria sin agua caliente o que no enciende. En Elda la cal del agua deteriora las válvulas y el intercambiador antes de lo previsto. Diagnóstico en la visita, presupuesto cerrado, reparación el mismo día si hay material.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">TERMOS</span>
        <h3>Cambio de termos eléctricos en Elda</h3>
        <p>El agua dura reduce la vida útil del termo en Elda. Sustitución completa con retirada del equipo anterior, instalación del nuevo y comprobación de funcionamiento. Presupuesto gratuito con visita incluida.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">FUGAS</span>
        <h3>Localización y reparación de fugas</h3>
        <p>Tuberías de hierro galvanizado reventadas en bloques de los años 70-80 del casco histórico o El Plá. Localización con geófono y termografía sin romper paredes. Marcamos el punto exacto antes de abrir.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">PRESIÓN</span>
        <h3>Grupos de presión en Elda</h3>
        <p>Grupo de presión parado o que no arranca: presión cero en todo el edificio. Habitual en bloques de los años 70-80 con equipos originales. Diagnóstico y presupuesto en la misma visita. Reparación o sustitución.</p>
      </div>

      <div class="zona-sc">
        <span class="zona-sc-n">COMUNIDADES</span>
        <h3>Urgencias en comunidades de vecinos</h3>
        <p>Averías en instalaciones comunes: tuberías generales, bajantes, cuartos de contadores y patios de luces. Coordinamos con el administrador, damos precio por escrito y emitimos factura. Disponible 24h.</p>
      </div>

    </div>
  </div>
</section>

<!-- Barrios de Elda -->
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">Zonas de Elda donde trabajamos</p>
    <h2>Cubrimos <span class="hl">toda la ciudad sin excepción</span></h2>
    <p style="margin-bottom:1.5rem;color:#576574">Atendemos todo Elda el mismo día, en un máximo de 3 horas desde el contacto.</p>
    <div class="zona-ztags">
      <span class="zona-ztag-plain">Centro</span>
      <span class="zona-ztag-plain">El Chopo</span>
      <span class="zona-ztag-plain">Sector Oeste</span>
      <span class="zona-ztag-plain">Las 400 Viviendas</span>
      <span class="zona-ztag-plain">El Plá</span>
      <span class="zona-ztag-plain">La Canal</span>
      <span class="zona-ztag-plain">Casco histórico</span>
      <span class="zona-ztag-plain">Polígono Industrial</span>
      <span class="zona-ztag-plain">Urbanizaciones periféricas</span>
    </div>
  </div>
</section>

<!-- Proceso -->
<section class="zona-sec zona-sec-alt">
  <div class="cta-dark-con">
    <p class="zona-lbl">Sin sorpresas</p>
    <h2>Qué pasa <span class="hl">cuando llamas en Elda</span></h2>
    <div class="zona-steps">
      <div class="zona-step">
        <div class="zona-step-n">1</div>
        <div class="zona-step-txt">
          <strong>Llamas y nos cuentas la avería</strong>
          <p>Teléfono o WhatsApp — sin formularios ni esperas. Nos describes el problema y acordamos la visita. Si es una urgencia activa en Elda (agua corriendo, presión cero), priorizamos el desplazamiento. Para urgencias activas priorizamos la salida — en el mismo día.</p>
        </div>
      </div>
      <div class="zona-step">
        <div class="zona-step-n">2</div>
        <div class="zona-step-txt">
          <strong>Inspeccionamos y presupuestamos en persona</strong>
          <p>Llegamos a Elda, vemos la avería y damos el precio definitivo cerrado. Sin tocar nada hasta que lo apruebes. Para fugas en tuberías empotradas usamos geófono si la localización no es evidente: marcamos el punto exacto antes de abrir.</p>
        </div>
      </div>
      <div class="zona-step">
        <div class="zona-step-n">3</div>
        <div class="zona-step-txt">
          <strong>Reparamos en la misma visita</strong>
          <p>Si hay material en la furgoneta, lo resolvemos ese mismo día. El precio final es el que acordamos — sin añadidos. Emitimos factura y garantía por escrito. Si el equipo necesita pieza específica, dejamos la instalación estabilizada y volvemos sin cobrar segundo desplazamiento.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Tarifas -->
<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Precios transparentes</p>
    <h2>Tarifas orientativas <span class="hl">fontanero urgente Elda</span></h2>
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
        <span class="zona-precio-concepto">Desplazamiento a Elda</span>
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
    <h2>Urgencias fontanero <span class="hl">Elda — dudas habituales</span></h2>
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
  $_ps->execute(['%Elda%']);
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
    <h2>Proyectos de fontaner&iacute;a urgente <span class="hl">en Elda</span></h2>
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
    <h2>Fontanero urgente <span class="hl">en Elda</span></h2>
    <p style="margin-bottom:1.5rem;color:#576574">Atendemos toda la localidad de Elda (CP 03600) y municipios lim&iacute;trofes del Valle del Vinalopó.</p>
    <div style="border-radius:12px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.12)">
      <iframe src="https://maps.google.com/maps?q=38.4774,-0.7882&z=14&output=embed" width="100%" height="380" style="border:0;display:block" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Fontanero urgente en Elda"></iframe>
    </div>
  </div>
</section>

<!-- Zona tags -->
<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Mismo servicio en otras zonas</p>
    <h2>Fontanero urgente <span class="hl">en otros municipios</span></h2>
    <div class="zona-ztags">
      <a href="/fontanero/elda" class="zona-ztag" style="background:#1e3a5f;color:#fff">&#8592; Todos los servicios en Elda</a>
      <a href="/fontanero/petrer/urgencias" class="zona-ztag">Petrer</a>
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
    <h2>&iquest;Tienes una aver&iacute;a <span>urgente en Elda?</span></h2>
    <p>Ll&aacute;menos. Atendemos el mismo d&iacute;a. Precio antes de empezar.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">&#128222; Llamar ahora</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">&#128172; WhatsApp</a>
    </div>
  </div>
</section>

<?php
$ciudad = 'elda';
$servicio = 'urgencias';
include '../../includes/resenas-section.php';
?>
<?php include '../../includes/galeria-section.php'; ?>
<?php include '../../includes/footer.php'; ?>
