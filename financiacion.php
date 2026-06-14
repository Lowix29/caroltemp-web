<?php
$meta_title  = "Financiación para reparaciones y reformas | CarolTemp";
$meta_desc   = "Opciones de financiación para instalaciones, reparaciones y reformas de fontanería. Solicitud rápida y gestión sencilla.";
$meta_url    = "https://caroltemp.com/financiacion";
$schema_type = "default";
$page_css    = "financiacion";
$page_js     = "financiacion";

include 'includes/head.php';
?>

<!-- HERO -->
<?php
$_hi     = getHeroImagen('financiacion');
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
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Financiación disponible</div>
    <h1>Que el presupuesto no frene<br><span class="hl">lo que necesitas hacer</span></h1>
    <p class="hz-dark-sub">Aprueba el presupuesto hoy y empieza sin adelantar nada. Cuotas cómodas, aprobación rápida y firma digital desde tu móvil.</p>
    <div class="hz-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">📞 Consultar ahora</a>
      <a href="<?php echo $base_url; ?>contacto" class="btn-hz-g">Pedir información</a>
    </div>
  </div>
</section>

<!-- STRIP -->
<div class="fin-strip">
  <div class="fin-strip-in">
    <div class="fin-strip-item">
      <span class="fin-strip-ico">💶</span>
      <div>
        <strong>Sin adelanto</strong>
        <span>Empezamos sin que pagues nada</span>
      </div>
    </div>
    <div class="fin-strip-sep"></div>
    <div class="fin-strip-item">
      <span class="fin-strip-ico">⚡</span>
      <div>
        <strong>Aprobación rápida</strong>
        <span>Proceso ágil, sin esperas</span>
      </div>
    </div>
    <div class="fin-strip-sep"></div>
    <div class="fin-strip-item">
      <span class="fin-strip-ico">📱</span>
      <div>
        <strong>Firma digital</strong>
        <span>Solo necesitas tu móvil</span>
      </div>
    </div>
    <div class="fin-strip-sep"></div>
    <div class="fin-strip-item">
      <span class="fin-strip-ico">🔧</span>
      <div>
        <strong>Material + mano de obra</strong>
        <span>Todo el proyecto financiado</span>
      </div>
    </div>
  </div>
</div>

<!-- SIMULADOR DE CUOTAS -->
<section class="fin-sim-section">
  <div class="fin-pasos-wrap">
    <p class="zona-lbl" style="text-align:center">Simulador orientativo</p>
    <h2 class="fin-sec-h2" style="text-align:center">¿Cuánto pagarías <span style="color:#3b82f6">al mes?</span></h2>
    <div class="fin-sim-card">
      <div class="fin-sim-top">

        <div class="fin-sim-col">
          <label class="fin-sim-label">Importe del proyecto</label>
          <div class="fin-sim-amount-row">
            <span class="fin-sim-amount-val" id="sim-amount-val">1.500 €</span>
          </div>
          <input type="range" class="fin-sim-range" id="sim-range" min="300" max="8000" step="100" value="1500">
          <div class="fin-sim-range-limits">
            <span>300 €</span><span>8.000 €</span>
          </div>
        </div>

        <div class="fin-sim-col">
          <label class="fin-sim-label">Plazo</label>
          <div class="fin-sim-months" id="sim-months">
            <button class="fin-sim-mth" data-m="12">12 meses</button>
            <button class="fin-sim-mth fin-sim-mth-active" data-m="24">24 meses</button>
            <button class="fin-sim-mth" data-m="36">36 meses</button>
            <button class="fin-sim-mth" data-m="48">48 meses</button>
          </div>
        </div>

      </div>

      <div class="fin-sim-result">
        <div class="fin-sim-result-main">
          <span class="fin-sim-result-num" id="sim-result">62,50 €</span>
          <span class="fin-sim-result-label">/mes</span>
        </div>
        <p class="fin-sim-disclaimer">Cálculo orientativo sin intereses. Las condiciones definitivas se confirman durante el trámite con la entidad financiera.</p>
      </div>

      <div class="fin-sim-cta">
        <a href="tel:+34611165129" class="btn btn-primary">📞 Consultar sin compromiso</a>
        <a href="<?php echo $base_url; ?>contacto" class="btn btn-secondary">Pedir información →</a>
      </div>
    </div>
  </div>
</section>

<!-- QUIÉN PUEDE SOLICITARLO -->
<section class="fin-quien-section">
  <div class="fin-pasos-wrap">
    <p class="zona-lbl">Disponible para</p>
    <h2 class="fin-sec-h2">¿Quién puede <span style="color:#3b82f6">solicitarlo?</span></h2>
    <div class="fin-quien-grid">
      <div class="fin-quien-card">
        <div class="fin-quien-ico">🏠</div>
        <h3>Particulares</h3>
        <p>Propietarios o inquilinos (con autorización) que necesiten financiar una reforma, instalación o avería de cierta envergadura en su vivienda.</p>
        <ul class="fin-quien-list">
          <li>✔ Reformas de baño</li>
          <li>✔ Ósmosis y descalcificadores</li>
          <li>✔ Termos y aerotermia</li>
        </ul>
      </div>
      <div class="fin-quien-card">
        <div class="fin-quien-ico">🧾</div>
        <h3>Autónomos</h3>
        <p>Profesionales que quieren mejorar sus instalaciones sin descapitalizarse. Financiación en el mismo presupuesto del trabajo.</p>
        <ul class="fin-quien-list">
          <li>✔ Locales y talleres</li>
          <li>✔ Cualquier instalación</li>
          <li>✔ Material + mano de obra</li>
        </ul>
      </div>
      <div class="fin-quien-card">
        <div class="fin-quien-ico">🏢</div>
        <h3>Empresas</h3>
        <p>Comunidades de vecinos, pequeñas empresas o locales comerciales que prefieren gestionar el gasto en cómodos plazos mensuales.</p>
        <ul class="fin-quien-list">
          <li>✔ Comunidades de vecinos</li>
          <li>✔ Locales comerciales</li>
          <li>✔ Proyectos de mayor importe</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- QUÉ PUEDES FINANCIAR -->
<section class="fin-cubre-section">
  <div class="fin-pasos-wrap">
    <div class="fin-cubre-head">
      <div>
        <p class="zona-lbl">¿Qué puedes financiar?</p>
        <h2 class="fin-sec-h2">Material e instalación <span style="color:#3b82f6">incluidos en el mismo presupuesto</span></h2>
        <p class="fin-cubre-sub">La financiación cubre el proyecto completo. No tienes que buscarla por tu cuenta ni gestionar nada. Nosotros lo tramitamos contigo.</p>
      </div>
    </div>
    <div class="fin-cubre-grid">
      <div class="fin-cubre-card">
        <span class="fin-cubre-ico">💧</span>
        <h3>Ósmosis inversa</h3>
        <p>Equipo e instalación completos. Agua de calidad sin desembolso inicial.</p>
      </div>
      <div class="fin-cubre-card">
        <span class="fin-cubre-ico">🔩</span>
        <h3>Descalcificadores</h3>
        <p>Equipo y puesta en marcha incluidos. Protege tu instalación a plazos.</p>
      </div>
      <div class="fin-cubre-card">
        <span class="fin-cubre-ico">🚿</span>
        <h3>Reformas de baño</h3>
        <p>Proyecto completo financiado: materiales, instalación y mano de obra.</p>
      </div>
      <div class="fin-cubre-card">
        <span class="fin-cubre-ico">🏡</span>
        <h3>Reformas integrales de viviendas</h3>
        <p>Toda la reforma, desde fontanería hasta acabados. Un solo presupuesto, un solo pago mensual.</p>
      </div>
      <div class="fin-cubre-card">
        <span class="fin-cubre-ico">❄️</span>
        <h3>Aire acondicionado</h3>
        <p>Equipo e instalación financiados. Climatización en casa sin desembolso inicial.</p>
      </div>
      <div class="fin-cubre-card">
        <span class="fin-cubre-ico">🔧</span>
        <h3>Cualquier instalación</h3>
        <p>Consulta tu proyecto. Si aplica la financiación, te lo gestionamos todo.</p>
      </div>
    </div>
  </div>
</section>

<!-- CÓMO FUNCIONA -->
<section class="fin-pasos-section">
  <div class="fin-pasos-wrap">
    <p class="zona-lbl" style="text-align:center">Proceso</p>
    <h2 class="fin-sec-h2" style="text-align:center">Cuatro pasos, <span style="color:#3b82f6">todo desde el móvil</span></h2>
    <div class="fin-pasos-grid">
      <div class="fin-paso">
        <div class="fin-paso-ico">📋</div>
        <div class="fin-paso-num">01</div>
        <h3>Aceptas el presupuesto</h3>
        <p>Te presentamos el presupuesto con la opción de pagarlo a plazos. Sin compromiso hasta que tú decidas.</p>
      </div>
      <div class="fin-paso-sep">→</div>
      <div class="fin-paso">
        <div class="fin-paso-ico">📞</div>
        <div class="fin-paso-num">02</div>
        <h3>Tramitamos contigo</h3>
        <p>Se pone en contacto contigo para pedirte la documentación necesaria. El trámite es sencillo y rápido.</p>
      </div>
      <div class="fin-paso-sep">→</div>
      <div class="fin-paso">
        <div class="fin-paso-ico">📱</div>
        <div class="fin-paso-num">03</div>
        <h3>Firmas con un SMS</h3>
        <p>Recibes las condiciones por email y un código por SMS. Firmas digitalmente desde el móvil. Sin ir a ningún sitio.</p>
      </div>
      <div class="fin-paso-sep">→</div>
      <div class="fin-paso fin-paso-last">
        <div class="fin-paso-ico">🔧</div>
        <div class="fin-paso-num">04</div>
        <h3>Empezamos el trabajo</h3>
        <p>Aprobada la financiación, comenzamos. Tú pagas cómodamente a plazos mientras el trabajo avanza.</p>
      </div>
    </div>
  </div>
</section>

<!-- ¿ES SEGURO? BLOQUE DE CONFIANZA -->
<section class="fin-seguro-section">
  <div class="fin-pasos-wrap">
    <div class="fin-seguro-inner">
      <div class="fin-seguro-text">
        <p class="zona-lbl">Tranquilidad total</p>
        <h2 class="fin-sec-h2" style="margin-bottom:1.5rem">¿Es seguro <span style="color:#3b82f6">contratar la financiación?</span></h2>
        <p class="fin-seguro-sub">Trabajamos con entidades financieras reguladas. El proceso está supervisado y tú decides en todo momento si continuar o no.</p>
        <div class="fin-seguro-items">
          <div class="fin-seguro-item">
            <span class="fin-seguro-check">✓</span>
            <div>
              <strong>Entidad financiera regulada</strong>
              <span>Sujeta a normativa española y europea de crédito al consumo.</span>
            </div>
          </div>
          <div class="fin-seguro-item">
            <span class="fin-seguro-check">✓</span>
            <div>
              <strong>Sin letra pequeña sorpresa</strong>
              <span>Recibes las condiciones completas por email antes de firmar. Sin presiones.</span>
            </div>
          </div>
          <div class="fin-seguro-item">
            <span class="fin-seguro-check">✓</span>
            <div>
              <strong>Firma digital con código SMS</strong>
              <span>Solo tú puedes firmar. Nada se activa sin tu confirmación explícita.</span>
            </div>
          </div>
          <div class="fin-seguro-item">
            <span class="fin-seguro-check">✓</span>
            <div>
              <strong>Desistimiento 14 días</strong>
              <span>Derecho legal a cancelar el contrato en los primeros 14 días sin coste.</span>
            </div>
          </div>
        </div>
      </div>
      <div class="fin-seguro-badge">
        <div class="fin-seguro-badge-inner">
          <div class="fin-seguro-shield">🔒</div>
          <strong>Proceso 100% seguro</strong>
          <span>Regulado por la Ley 16/2011 de contratos de crédito al consumo</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- VENTAJAS -->
<section class="fin-ventajas-section">
  <div class="fin-pasos-wrap">
    <p class="zona-lbl">Ventajas</p>
    <h2 class="fin-sec-h2">Sin adelantos, <span style="color:#3b82f6">sin complicaciones</span></h2>
    <div class="fin-ventajas-grid">
      <div class="fin-ventaja">
        <div class="fin-ventaja-ico">💶</div>
        <h3>Sin pago inicial</h3>
        <p>El trabajo empieza sin que tengas que poner dinero por adelantado.</p>
      </div>
      <div class="fin-ventaja">
        <div class="fin-ventaja-ico">📱</div>
        <h3>Firma desde el móvil</h3>
        <p>El contrato se firma digitalmente con un código SMS. Sin desplazarte.</p>
      </div>
      <div class="fin-ventaja">
        <div class="fin-ventaja-ico">⚡</div>
        <h3>Aprobación ágil</h3>
        <p>Proceso rápido. Sin largas esperas ni burocracia innecesaria.</p>
      </div>
      <div class="fin-ventaja">
        <div class="fin-ventaja-ico">📋</div>
        <h3>Documentación mínima</h3>
        <p>Solo lo esencial. Sin montones de papeles ni trámites complicados.</p>
      </div>
      <div class="fin-ventaja">
        <div class="fin-ventaja-ico">🗓️</div>
        <h3>Plazo flexible</h3>
        <p>Elige las cuotas y el plazo que mejor se adapta a tu situación.</p>
      </div>
      <div class="fin-ventaja">
        <div class="fin-ventaja-ico">🏠</div>
        <h3>Viviendas y locales</h3>
        <p>Disponible para particulares, autónomos y empresas.</p>
      </div>
    </div>
  </div>
</section>

<!-- BANNER URGENCIA -->
<div class="fin-urgbanner">
  <div class="fin-pasos-wrap">
    <div class="fin-urgbanner-inner">
      <div class="fin-urgbanner-text">
        <strong>¿Tienes una avería urgente y te preocupa el coste?</strong>
        <span>Llámanos ahora. Si el presupuesto es elevado, te explicamos en el momento cómo pagarlo a plazos sin adelantar nada.</span>
      </div>
      <div class="fin-urgbanner-btns">
        <a href="tel:+34611165129" class="btn-hz-w fin-urgbanner-btn">📞 611 165 129</a>
      </div>
    </div>
  </div>
</div>

<!-- FAQs -->
<section class="fin-faqs-section">
  <div class="fin-faqs-wrap">
    <p class="zona-lbl" style="text-align:center">Preguntas frecuentes</p>
    <h2 class="fin-sec-h2" style="text-align:center">Lo que suelen <span style="color:#3b82f6">preguntarnos</span></h2>
    <div class="fin-faqs">
      <details class="fin-faq">
        <summary>¿Puedo financiar cualquier trabajo de fontanería?</summary>
        <div class="fin-faq-ans">La financiación está disponible para la mayoría de instalaciones y reformas: ósmosis, descalcificadores, termos, reformas de baño, aerotermia y otros proyectos. Consulta tu caso concreto y te decimos si aplica.</div>
      </details>
      <details class="fin-faq">
        <summary>¿Cuánto tarda en aprobarse la financiación?</summary>
        <div class="fin-faq-ans">El proceso es rápido. Una vez que aceptas el presupuesto con la opción de financiarlo, el trámite se inicia de inmediato. La firma es digital con un código SMS.</div>
      </details>
      <details class="fin-faq">
        <summary>¿Qué documentación necesito?</summary>
        <div class="fin-faq-ans">La documentación es mínima. Te la solicitan directamente una vez iniciado el proceso. No necesitas preparar nada por adelantado para consultar o pedir presupuesto.</div>
      </details>
      <details class="fin-faq">
        <summary>¿Funciona también para autónomos o locales comerciales?</summary>
        <div class="fin-faq-ans">Sí. La financiación está disponible para particulares, autónomos y empresas que quieran mejorar sus instalaciones en vivienda o local sin un desembolso inicial grande.</div>
      </details>
      <details class="fin-faq">
        <summary>¿Puedo pedir el presupuesto sin compromiso?</summary>
        <div class="fin-faq-ans">Por supuesto. Llámanos o escríbenos y te valoramos el proyecto sin ningún compromiso. Si decides financiarlo, lo tramitamos todo en ese momento.</div>
      </details>
      <details class="fin-faq">
        <summary>¿Hay que tener nómina para que lo aprueben?</summary>
        <div class="fin-faq-ans">No es imprescindible. La entidad financiera valora cada caso de forma individual. Autónomos y pensionistas también pueden solicitarlo. Consulta sin compromiso y te orientamos.</div>
      </details>
      <details class="fin-faq">
        <summary>¿Puedo cancelar si cambio de opinión antes de firmar?</summary>
        <div class="fin-faq-ans">Sí, en cualquier momento antes de la firma. Y una vez firmado, la ley te da 14 días de desistimiento sin penalización. Tienes el control en todo momento.</div>
      </details>
    </div>
  </div>
</section>

<!-- PRUEBA SOCIAL -->
<section class="fin-social-section">
  <div class="fin-pasos-wrap">
    <p class="zona-lbl" style="text-align:center">En números</p>
    <h2 class="fin-sec-h2" style="text-align:center">Clientes que ya <span style="color:#3b82f6">confían en esta opción</span></h2>
    <div class="fin-social-grid">
      <div class="fin-social-stat">
        <span class="fin-social-num">+200</span>
        <span class="fin-social-label">proyectos financiados</span>
      </div>
      <div class="fin-social-stat">
        <span class="fin-social-num">48h</span>
        <span class="fin-social-label">tiempo medio de aprobación</span>
      </div>
      <div class="fin-social-stat">
        <span class="fin-social-num">0 €</span>
        <span class="fin-social-label">adelanto para empezar</span>
      </div>
      <div class="fin-social-stat">
        <span class="fin-social-num">100%</span>
        <span class="fin-social-label">proceso desde el móvil</span>
      </div>
    </div>
    <div class="fin-social-reviews">
      <div class="fin-social-review">
        <div class="fin-social-stars">★★★★★</div>
        <p>"Necesitaba cambiar el sistema completo de agua y me daba miedo el coste. Lo financié en 24 meses y empezaron al día siguiente. Sin adelantar nada."</p>
        <span>María J. — Elda</span>
      </div>
      <div class="fin-social-review">
        <div class="fin-social-stars">★★★★★</div>
        <p>"La reforma del baño era necesaria pero no podía pagarlo de golpe. Todo el proceso fue por el móvil, muy sencillo. Lo recomiendo."</p>
        <span>Pedro M. — Novelda</span>
      </div>
      <div class="fin-social-review">
        <div class="fin-social-stars">★★★★★</div>
        <p>"Pensé que la financiación sería complicada pero fue lo más fácil. Firmé con un SMS y a los dos días tenía la ósmosis instalada."</p>
        <span>Ana R. — Petrer</span>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>¿Te interesa <span>financiar tu proyecto?</span></h2>
    <p>Llámanos y te explicamos cómo funciona sin ningún compromiso.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">📞 611 165 129</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">💬 WhatsApp</a>
    </div>
    <div class="cta-dark-tel">Teléfono directo<strong>611 165 129</strong></div>
  </div>
</section>

<?php
include 'includes/resenas-section.php';
?>
<?php include 'includes/footer.php'; ?>
