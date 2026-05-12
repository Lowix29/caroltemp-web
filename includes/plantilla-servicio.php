<?php
/**
 * PLANTILLA DE SERVICIO POR CIUDAD
 * 
 * Variables requeridas antes de incluir:
 * $servicio_nombre, $servicio_slug, $ciudad, $ciudad_slug, $ciudad_cp
 * $meta_title, $meta_desc, $hero_titulo, $hero_sub
 * $contenido_intro, $servicios_lista, $problemas_zona, $faq
 * $contenido_extra (opcional), $ciudades_cercanas
 */

$meta_url    = $meta_url ?? 'https://hidrofont.es/' . $servicio_slug . '/' . $servicio_slug . '-' . $ciudad_slug;
$schema_type = 'zona';
$zona_nombre = $ciudad;
$page_css    = 'servicio-ciudad';
$page_js     = 'zona';

include __DIR__ . '/head.php';
?>

<!-- HERO -->
<section class="hz-dark" style="min-height:440px">
  <div class="hz-dark-bg"></div>
  <div class="hz-dark-glow"></div>
  <div class="hz-dark-con">
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span><?php echo $servicio_nombre; ?> · <?php echo $ciudad; ?> · CP <?php echo $ciudad_cp; ?></div>
    <h1><?php echo $hero_titulo; ?></h1>
    <p class="hz-dark-sub"><?php echo $hero_sub; ?></p>
    <div class="hz-dark-btns">
      <a href="tel:+34613429032" class="btn-hz-w">📞 613 429 032</a>
      <a href="<?php echo $base_url; ?>contacto" class="btn-hz-g">Pedir presupuesto</a>
    </div>
    <div class="hero-dark-kpis" style="margin-top:2rem">
      <div class="hero-dark-kpi"><span class="hero-dark-kpi-val">24h</span><span class="hero-dark-kpi-lbl">Urgencias</span></div>
      <div class="hero-dark-kpi"><span class="hero-dark-kpi-val">100%</span><span class="hero-dark-kpi-lbl">Precio cerrado</span></div>
      <div class="hero-dark-kpi"><span class="hero-dark-kpi-val">0€</span><span class="hero-dark-kpi-lbl">Sin adelantos</span></div>
    </div>
  </div>
</section>

<!-- STRIP -->
<div class="dif-strip">
  <div class="dif-strip-in">
    <div class="dif-item"><span class="dif-val">⚡ Urgencias</span><span class="dif-lbl">Atención rápida en <?php echo $ciudad; ?></span></div>
    <div class="dif-item"><span class="dif-val">🔍 Sin obras</span><span class="dif-lbl">Geófono y cámara</span></div>
    <div class="dif-item"><span class="dif-val">💰 Precio cerrado</span><span class="dif-lbl">Antes de empezar</span></div>
    <div class="dif-item"><span class="dif-val">📍 Comarca</span><span class="dif-lbl">Somos de aquí</span></div>
  </div>
</div>

<!-- INTRODUCCIÓN -->
<section class="sc-sec">
  <div class="sc-con">
    <div class="sc-grid">
      <div>
        <p class="zona-lbl"><?php echo $servicio_nombre; ?> en <?php echo $ciudad; ?></p>
        <h2 class="sc-h2"><?php echo $servicio_nombre; ?> en <span class="hl"><?php echo $ciudad; ?></span></h2>
        <div class="sc-prose">
          <?php echo $contenido_intro; ?>
        </div>
        <?php if (!empty($servicios_lista)): ?>
        <ul class="sc-chk">
          <?php foreach ($servicios_lista as $item): ?>
          <li><span class="sc-chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span><?php echo $item; ?></li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>
        <a href="tel:+34613429032" class="sc-btn">📞 Llamar ahora</a>
      </div>
      <div>
        <div class="zona-icard">
          <div class="zona-icard-h"><strong>Hidrofont · <?php echo $ciudad; ?></strong><span><?php echo $servicio_nombre; ?></span></div>
          <div class="zona-ir"><span class="zona-ir-l">Zona</span><span class="zona-ir-v"><?php echo $ciudad; ?> · CP <?php echo $ciudad_cp; ?></span></div>
          <div class="zona-ir"><span class="zona-ir-l">Teléfono</span><span class="zona-ir-v"><a href="tel:+34613429032">613 429 032</a></span></div>
          <div class="zona-ir"><span class="zona-ir-l">WhatsApp</span><span class="zona-ir-v"><a href="https://wa.me/34613429032">Escribir ahora →</a></span></div>
          <div class="zona-ir"><span class="zona-ir-l">Horario</span><span class="zona-ir-v">Lun–Vie 8–20h · Sáb 9–14h</span></div>
          <a href="tel:+34613429032" class="zona-icard-btn">📞 Llamar ahora</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- PROBLEMAS TÍPICOS -->
<?php if (!empty($problemas_zona)): ?>
<section class="sc-sec sc-sec-gray">
  <div class="sc-con">
    <p class="zona-lbl">Problemas habituales en <?php echo $ciudad; ?></p>
    <h2 class="sc-h2">¿Por qué necesitas un <span class="hl"><?php echo strtolower($servicio_nombre); ?> en <?php echo $ciudad; ?>?</span></h2>
    <div class="sc-problemas">
      <?php $n = 1; foreach ($problemas_zona as $prob): ?>
      <div class="sc-prob">
        <span class="sc-prob-num"><?php echo str_pad($n++, 2, '0', STR_PAD_LEFT); ?></span>
        <h3><?php echo $prob['titulo']; ?></h3>
        <p><?php echo $prob['texto']; ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- CONTENIDO EXTRA -->
<?php if (!empty($contenido_extra)): ?>
<section class="sc-sec">
  <div class="sc-con">
    <?php echo $contenido_extra; ?>
  </div>
</section>
<?php endif; ?>

<!-- FAQ -->
<?php if (!empty($faq)): ?>
<section class="sc-sec sc-sec-gray">
  <div class="sc-con">
    <p class="zona-lbl">Preguntas frecuentes</p>
    <h2 class="sc-h2"><?php echo $servicio_nombre; ?> en <?php echo $ciudad; ?> — <span class="hl">dudas habituales</span></h2>
    <div class="sc-faq">
      <?php $first = true; foreach ($faq as $f): ?>
      <div class="zona-fi<?php echo $first ? ' open' : ''; ?>">
        <div class="zona-fiq" onclick="togFaq(this)"><span><?php echo $f['pregunta']; ?></span><span class="zona-fiq-i"><svg viewBox="0 0 10 10" fill="none"><path d="M5 1v8M1 5h8" stroke-width="1.5" stroke-linecap="round"/></svg></span></div>
        <div class="zona-fia"><?php echo $f['respuesta']; ?></div>
      </div>
      <?php $first = false; endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- OTROS SERVICIOS EN ESTA CIUDAD -->
<section class="sc-sec">
  <div class="sc-con">
    <p class="zona-lbl">Más servicios en <?php echo $ciudad; ?></p>
    <h2 class="sc-h2">También trabajamos en <span class="hl"><?php echo $ciudad; ?></span></h2>
    <div class="sc-enlaces">
      <?php
      $todos_servicios = [
        'fontanero'  => ['nombre' => 'Fontanero',          'prefijo' => 'fontanero'],
        'fugas'      => ['nombre' => 'Detección de fugas', 'prefijo' => 'deteccion-fugas'],
        'desatascos' => ['nombre' => 'Desatascos',         'prefijo' => 'desatascos'],
      ];
      foreach ($todos_servicios as $slug => $info):
        if ($slug === $servicio_slug) continue;
      ?>
      <a href="<?php echo $base_url . $slug . '/' . $info['prefijo'] . '-' . $ciudad_slug; ?>" class="sc-enlace">
        <strong><?php echo $info['nombre']; ?> en <?php echo $ciudad; ?></strong>
        <span>Ver servicio →</span>
      </a>
      <?php endforeach; ?>
      <a href="<?php echo $base_url; ?>zonas/<?php echo $ciudad_slug; ?>" class="sc-enlace">
        <strong>Todos los servicios en <?php echo $ciudad; ?></strong>
        <span>Ver zona →</span>
      </a>
    </div>
  </div>
</section>

<!-- CIUDADES CERCANAS -->
<?php if (!empty($ciudades_cercanas)): ?>
<section class="sc-sec sc-sec-gray">
  <div class="sc-con">
    <p class="zona-lbl"><?php echo $servicio_nombre; ?> en la comarca</p>
    <h2 class="sc-h2">También somos <span class="hl"><?php echo strtolower($servicio_nombre); ?></span> en</h2>
    <div class="zona-ztags">
      <?php foreach ($ciudades_cercanas as $cc): ?>
      <a href="<?php echo $base_url . $servicio_slug . '/' . $cc['prefijo'] . '-' . $cc['slug']; ?>" class="zona-ztag"><?php echo $cc['nombre']; ?></a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- CTA -->
<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>¿Necesitas un <?php echo strtolower($servicio_nombre); ?> <span>en <?php echo $ciudad; ?>?</span></h2>
    <p>Llámanos o escríbenos. Te atendemos hoy.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34613429032" class="btn-hz-w">📞 Llamar ahora</a>
      <a href="https://wa.me/34613429032" target="_blank" rel="noopener" class="btn-hz-g">💬 WhatsApp</a>
    </div>
    <div class="cta-dark-tel">Teléfono directo<strong>613 429 032</strong></div>
  </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>
