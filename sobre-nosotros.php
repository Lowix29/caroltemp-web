<?php
$meta_title  = "CarolTemp | Fontaneros profesionales en Elda y Vinalopó";
$meta_desc   = "Conoce a CarolTemp, especialistas en fontanería, fugas, desatascos y reformas. Experiencia, profesionalidad y atención cercana.";
$meta_url    = "https://caroltemp.com/sobre-nosotros.php";
$schema_type = "default";
$page_css    = "sobre";
$page_js     = "";

include 'includes/head.php';
?>

<!-- HERO -->
<section class="hz-dark">
  <div class="hz-dark-bg"></div>
  <div class="hz-dark-glow"></div>
  <div class="hz-dark-con">
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Quiénes somos</div>
    <h1>Fontanería en el Vinalopó<br><span class="hl">con nombre y apellidos.</span></h1>
    <p class="hz-dark-sub">Somos CarolTemp. Una empresa de fontanería residencial comprometida con hacer las cosas bien, explicar lo que hacemos y cumplir con lo que prometemos.</p>
    <div class="hz-dark-btns">
      <a href="tel:+34613429032" class="btn-hz-w">📞 613 429 032</a>
      <a href="<?php echo $base_url; ?>contacto.php" class="btn-hz-g">Contactar</a>
    </div>
  </div>
</section>

<!-- QUIÉNES SOMOS -->
<section style="padding:5rem 0">
  <div style="max-width:1100px;margin:0 auto;padding:0 var(--space-md)">
    <div style="display:grid;grid-template-columns:1.15fr .85fr;gap:4rem;align-items:start">
      <div>
        <p class="zona-lbl">Quiénes somos</p>
        <h2 style="font-size:clamp(1.7rem,3.5vw,2.5rem);font-weight:800;color:#0d1f33;letter-spacing:-.025em;line-height:1.15;margin-bottom:1rem">Especialistas en <span style="color:#3b82f6">instalaciones y reformas</span> en el Vinalopó</h2>
        <p style="color:#64748b;font-size:15px;line-height:1.85;margin-bottom:1rem">CarolTemp es una empresa de fontanería residencial ubicada en el Vinalopó Medio. Trabajamos en Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Monforte del Cid, Salinas y todas sus partidas.</p>
        <p style="color:#64748b;font-size:15px;line-height:1.85;margin-bottom:1rem">Nos dedicamos a hacer las cosas bien. Eso significa llegar a la hora, explicar lo que vamos a hacer antes de hacerlo, dejar todo recogido cuando terminamos y cumplir con el precio acordado sin añadir extras al final.</p>
        <p style="color:#64748b;font-size:15px;line-height:1.85">Somos instaladores oficiales de termos Nubeco en la comarca y ofrecemos financiación completa — material e instalación incluidos — para proyectos grandes.</p>
      </div>
      <div>
        <div class="zona-icard">
          <div class="zona-icard-h"><strong>CarolTemp</strong><span>Fontanería industrial y residencial</span></div>
          <div class="zona-ir"><span class="zona-ir-l">Ubicación</span><span class="zona-ir-v">Vinalopó Medio, Alicante</span></div>
          <div class="zona-ir"><span class="zona-ir-l">Teléfono</span><span class="zona-ir-v"><a href="tel:+34613429032">613 429 032</a></span></div>
          <div class="zona-ir"><span class="zona-ir-l">WhatsApp</span><span class="zona-ir-v"><a href="https://wa.me/34613429032">Escribir ahora →</a></span></div>
          <div class="zona-ir"><span class="zona-ir-l">Instalador oficial</span><span class="zona-ir-v">Termos Nubeco</span></div>
          <div class="zona-ir"><span class="zona-ir-l">Financiación</span><span class="zona-ir-v">Material + instalación incluidos</span></div>
          <a href="tel:+34613429032" class="zona-icard-btn">📞 Llamar ahora</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- DIFERENCIADORES -->
<div class="dif-strip">
  <div class="dif-strip-in">
    <div class="dif-item"><span class="dif-val">Instalador oficial</span><span class="dif-lbl">Termos Nubeco en el Vinalopó</span></div>
    <div class="dif-item"><span class="dif-val">Financiación completa</span><span class="dif-lbl">Material + instalación incluidos</span></div>
    <div class="dif-item"><span class="dif-val">precio sin sorpresas</span><span class="dif-lbl">Sin sorpresas ni extras</span></div>
    <div class="dif-item"><span class="dif-val">Toda la comarca</span><span class="dif-lbl">Vinalopó Medio</span></div>
  </div>
</div>

<!-- COMPROMISOS -->
<section style="padding:5rem 0;background:#f8fafc;border-top:1px solid #f1f5f9">
  <div style="max-width:1100px;margin:0 auto;padding:0 var(--space-md)">
    <p class="zona-lbl">Nuestros compromisos</p>
    <h2 style="font-size:clamp(1.7rem,3.5vw,2.5rem);font-weight:800;color:#0d1f33;letter-spacing:-.025em;line-height:1.15;margin-bottom:2.5rem">Lo que puedes <span style="color:#3b82f6">esperar de nosotros</span></h2>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:#e2e8f0;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden">
      <?php
      $compromisos = [
        ['01','precio sin sorpresas siempre','Antes de empezar cualquier trabajo te damos un precio sin sorpresas. Sin sorpresas ni extras al final.'],
        ['02','Puntualidad','Llegamos a la hora acordada. Tu tiempo es importante y lo respetamos.'],
        ['03','Transparencia total','Antes de empezar te explicamos qué vamos a hacer y por qué. Sin tecnicismos ni letra pequeña.'],
        ['04','Limpieza y orden','Trabajamos con cuidado y recogemos todo al terminar. Tu casa queda como la encontramos.'],
        ['05','Garantía en todo','Todos nuestros trabajos tienen garantía. Si algo no funciona bien, volvemos sin coste adicional.'],
        ['06','Sin sorpresas en el precio','El precio acordado es el precio final. Nunca añadimos extras no comunicados previamente.'],
      ];
      foreach ($compromisos as $c):
      ?>
      <div style="background:#fff;padding:1.75rem 1.5rem;display:flex;flex-direction:column;gap:.75rem">
        <span style="font-size:11px;font-weight:700;color:#cbd5e1;letter-spacing:.1em"><?php echo $c[0]; ?></span>
        <h3 style="color:#0d1f33;font-size:14.5px;font-weight:700"><?php echo $c[1]; ?></h3>
        <p style="color:#64748b;font-size:13px;line-height:1.65"><?php echo $c[2]; ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ZONAS -->
<section style="padding:5rem 0">
  <div style="max-width:1100px;margin:0 auto;padding:0 var(--space-md)">
    <p class="zona-lbl">Dónde trabajamos</p>
    <h2 style="font-size:clamp(1.7rem,3.5vw,2.5rem);font-weight:800;color:#0d1f33;letter-spacing:-.025em;line-height:1.15;margin-bottom:.75rem">Toda la comarca del <span style="color:#3b82f6">Vinalopó Medio</span></h2>
    <p style="color:#64748b;font-size:15px;margin-bottom:1.5rem">Cubrimos toda la comarca y sus partidas rurales.</p>
    <div class="zona-ztags">
      <a href="<?php echo $base_url; ?>zonas/elda.php"     class="zona-ztag">Elda</a>
      <a href="<?php echo $base_url; ?>zonas/petrer.php"   class="zona-ztag">Petrer</a>
      <a href="<?php echo $base_url; ?>zonas/novelda.php"  class="zona-ztag">Novelda</a>
      <a href="<?php echo $base_url; ?>zonas/monovar.php"  class="zona-ztag">Monóvar</a>
      <a href="<?php echo $base_url; ?>zonas/sax.php"      class="zona-ztag">Sax</a>
      <a href="<?php echo $base_url; ?>zonas/pinoso.php"   class="zona-ztag">Pinoso</a>
      <a href="<?php echo $base_url; ?>zonas/monforte.php" class="zona-ztag">Monforte del Cid</a>
      <a href="<?php echo $base_url; ?>zonas/salinas.php"  class="zona-ztag">Salinas</a>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>¿Hablamos <span>sin compromiso?</span></h2>
    <p>Cuéntanos qué necesitas y te atendemos hoy.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34613429032" class="btn-hz-w">📞 Llamar ahora</a>
      <a href="https://wa.me/34613429032" target="_blank" rel="noopener" class="btn-hz-g">💬 WhatsApp</a>
    </div>
    <div class="cta-dark-tel">Teléfono directo<strong>613 429 032</strong></div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>