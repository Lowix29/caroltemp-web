<?php
$meta_title  = "Financiación para fontanería — CarolTemp | Sin intereses en el Vinalopó";
$meta_desc   = "Financia tu reforma, instalación de ósmosis, descalcificador o cualquier trabajo de fontanería. Material e instalación incluidos sin intereses en el Vinalopó.";
$meta_url    = "https://caroltemp.com/financiacion.php";
$schema_type = "default";
$page_css    = "financiacion";
$page_js     = "";

include 'includes/head.php';
?>

<!-- HERO -->
<section class="hz-dark">
  <div class="hz-dark-bg"></div>
  <div class="hz-dark-glow"></div>
  <div class="hz-dark-con">
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Financiación disponible</div>
    <h1>Financia tu proyecto<br><span class="hl">sin complicaciones.</span></h1>
    <p class="hz-dark-sub">Material e instalación completa financiados. Sin intereses, sin gastos ocultos y sin necesidad de adelantar nada.</p>
    <div class="hz-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">📞 611 165 129</a>
      <a href="<?php echo $base_url; ?>contacto.php" class="btn-hz-g">Pedir información</a>
    </div>
  </div>
</section>

<!-- QUÉ CUBRE -->
<section style="padding:5rem 0">
  <div style="max-width:1100px;margin:0 auto;padding:0 var(--space-md)">
    <p class="zona-lbl">¿Qué cubre?</p>
    <h2 style="font-size:clamp(1.7rem,3.5vw,2.5rem);font-weight:800;color:#0d1f33;letter-spacing:-.025em;line-height:1.15;margin-bottom:.75rem">Financia <span style="color:#3b82f6">cualquier instalación</span></h2>
    <p style="color:#64748b;font-size:15px;line-height:1.75;margin-bottom:2.5rem;max-width:580px">La financiación cubre tanto el material como la mano de obra. No tienes que buscarla por tu cuenta.</p>
    <div class="zona-svc">
      <div class="zona-sc"><span class="zona-sc-n">01</span><h3>Ósmosis inversa</h3><p>Equipo completo e instalación financiados. Agua de calidad sin desembolso inicial.</p></div>
      <div class="zona-sc"><span class="zona-sc-n">02</span><h3>Descalcificadores</h3><p>Equipo y puesta en marcha incluidos. Protege tu instalación sin pagar todo de golpe.</p></div>
      <div class="zona-sc"><span class="zona-sc-n">03</span><h3>Reformas de baño</h3><p>Reforma completa financiada. Material, instalación y mano de obra incluidos.</p></div>
      <div class="zona-sc"><span class="zona-sc-n">04</span><h3>Termos Nubeco</h3><p>Termo nuevo con instalación financiado. Sin adelantar nada.</p></div>
      <div class="zona-sc"><span class="zona-sc-n">05</span><h3>Aerotermia</h3><p>Sistema completo financiado. La alternativa eficiente a la caldera tradicional.</p></div>
      <div class="zona-sc"><span class="zona-sc-n">06</span><h3>Cualquier instalación</h3><p>Consulta tu proyecto. Si el presupuesto lo permite, buscamos la solución adecuada.</p></div>
    </div>
  </div>
</section>

<!-- CÓMO FUNCIONA -->
<section style="padding:5rem 0;background:#f8fafc;border-top:1px solid #f1f5f9">
  <div style="max-width:1100px;margin:0 auto;padding:0 var(--space-md)">
    <p class="zona-lbl">Cómo funciona</p>
    <h2 style="font-size:clamp(1.7rem,3.5vw,2.5rem);font-weight:800;color:#0d1f33;letter-spacing:-.025em;line-height:1.15;margin-bottom:2.5rem">Cuatro pasos, <span style="color:#3b82f6">sin complicaciones</span></h2>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:#e2e8f0;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden">
      <?php
      $pasos = [
        ['01','Nos cuentas qué necesitas','Llámanos o escríbenos. Valoramos tu proyecto sin compromiso.'],
        ['02','precio sin sorpresas','Te damos el precio total antes de empezar. Sin sorpresas ni extras.'],
        ['03','Solicitas la financiación','Trámite sencillo y rápido. Aprobación en pocos días.'],
        ['04','Hacemos el trabajo','Sin que tengas que adelantar nada. Pagas cómodamente a plazos.'],
      ];
      foreach ($pasos as $p):
      ?>
      <div style="background:#fff;padding:1.75rem 1.5rem;display:flex;flex-direction:column;gap:.75rem">
        <span style="font-size:11px;font-weight:700;color:#cbd5e1;letter-spacing:.1em"><?php echo $p[0]; ?></span>
        <h3 style="color:#0d1f33;font-size:14.5px;font-weight:700;line-height:1.3"><?php echo $p[1]; ?></h3>
        <p style="color:#64748b;font-size:13px;line-height:1.6"><?php echo $p[2]; ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- VENTAJAS -->
<section style="padding:5rem 0">
  <div style="max-width:1100px;margin:0 auto;padding:0 var(--space-md)">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center">
      <div>
        <p class="zona-lbl">Sin letra pequeña</p>
        <h2 style="font-size:clamp(1.7rem,3.5vw,2.5rem);font-weight:800;color:#0d1f33;letter-spacing:-.025em;line-height:1.15;margin-bottom:1rem">Sin intereses <span style="color:#3b82f6">ni letra pequeña</span></h2>
        <p style="color:#64748b;font-size:15px;line-height:1.75;margin-bottom:1.5rem">La financiación cubre tanto el material como la mano de obra. No tienes que buscar financiación por tu cuenta ni adelantar nada. Nosotros lo gestionamos todo.</p>
        <ul style="list-style:none;display:flex;flex-direction:column;gap:.7rem">
          <?php
          $ventajas = [
            'Material e instalación incluidos',
            'Sin intereses ni gastos ocultos',
            'Gestión rápida y sin papeleos',
            'Tú eliges el plazo que mejor te viene',
            'Sin necesidad de adelantar nada',
            'Disponible para particulares',
          ];
          foreach ($ventajas as $v):
          ?>
          <li style="display:flex;align-items:flex-start;gap:10px;color:#1e293b;font-size:14px;line-height:1.5">
            <span style="width:20px;height:20px;background:#1e3a5f;border-radius:5px;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px">
              <svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            <?php echo $v; ?>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div style="display:flex;flex-direction:column;gap:1rem">
        <?php
        $items = [
          ['Sin adelanto inicial','No tienes que pagar nada por adelantado'],
          ['Gestión incluida','Nosotros hacemos todos los trámites por ti'],
          ['Plazo flexible','Elige el plazo que mejor se adapta a ti'],
          ['Aprobación rápida','Respuesta en pocos días hábiles'],
        ];
        foreach ($items as $item):
        ?>
        <div style="display:flex;align-items:flex-start;gap:1rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:1rem 1.25rem">
          <span style="width:28px;height:28px;background:#1e3a5f;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#fff;font-size:13px;font-weight:700">✓</span>
          <div>
            <strong style="color:#0d1f33;font-size:14px;font-weight:600;display:block;margin-bottom:3px"><?php echo $item[0]; ?></strong>
            <span style="color:#64748b;font-size:13px"><?php echo $item[1]; ?></span>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>¿Te interesa <span>financiar tu proyecto?</span></h2>
    <p>Llámanos y te explicamos sin compromiso cómo funciona.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">📞 Llamar ahora</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">💬 WhatsApp</a>
    </div>
    <div class="cta-dark-tel">Teléfono directo<strong>611 165 129</strong></div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>