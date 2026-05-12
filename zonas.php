<?php
$meta_title  = "Zonas de servicio — CarolTemp";
$meta_desc   = "Trabajamos en Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Monforte del Cid, Salinas y toda la comarca. Consulta los servicios disponibles en tu municipio.";
$meta_url    = "https://caroltemp.com/zonas";
$schema_type = "default";
$page_css    = "zonas";
$page_js     = "";

include 'includes/head.php';
?>

<!-- HERO -->
<section class="hz-dark">
  <div class="hz-dark-bg"></div>
  <div class="hz-dark-glow"></div>
  <div class="hz-dark-con">
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Zona de servicio</div>
    <h1>Trabajamos en toda<br><span class="hl">la comarca del Vinalopó.</span></h1>
    <p class="hz-dark-sub">Cubrimos Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Monforte del Cid, Salinas y todas sus zonas cercanas. Consulta tu municipio para ver los servicios disponibles.</p>
    <div class="hz-dark-btns">
      <a href="tel:+34613429032" class="btn-hz-w">📞 613 429 032</a>
      <a href="<?php echo $base_url; ?>contacto" class="btn-hz-g">Solicitar visita</a>
    </div>
  </div>
</section>

<!-- GRID ZONAS -->
<section style="padding:5rem 0">
  <div style="max-width:1100px;margin:0 auto;padding:0 var(--space-md)">
    <p class="zona-lbl">¿Dónde trabajamos?</p>
    <h2 style="font-size:clamp(1.7rem,3.5vw,2.5rem);font-weight:800;color:#0d1f33;letter-spacing:-.025em;line-height:1.15;margin-bottom:.75rem">Selecciona <span style="color:#3b82f6">tu municipio</span></h2>
    <p style="color:#64748b;font-size:15px;margin-bottom:2.5rem">Accede a tu zona para ver los servicios disponibles y cómo trabajamos en tu municipio.</p>

    <div class="zonas-grid">
      <?php
      $zonas = [
        ['nombre'=>'Elda',            'cp'=>'03600','slug'=>'elda',     'servicios'=>['Fontanería','Fugas','Desatascos']],
        ['nombre'=>'Petrer',          'cp'=>'03610','slug'=>'petrer',   'servicios'=>['Fontanería','Fugas','Desatascos']],
        ['nombre'=>'Novelda',         'cp'=>'03660','slug'=>'novelda',  'servicios'=>['Fontanería','Fugas','Desatascos']],
        ['nombre'=>'Monóvar',         'cp'=>'03640','slug'=>'monovar',  'servicios'=>['Fontanería','Fugas','Desatascos']],
        ['nombre'=>'Sax',             'cp'=>'03630','slug'=>'sax',      'servicios'=>['Fontanería','Fugas','Desatascos']],
        ['nombre'=>'Pinoso',          'cp'=>'03650','slug'=>'pinoso',   'servicios'=>['Fontanería','Fugas','Desatascos']],
        ['nombre'=>'Monforte del Cid','cp'=>'03670','slug'=>'monforte', 'servicios'=>['Fontanería','Fugas','Desatascos']],
        ['nombre'=>'Salinas',         'cp'=>'03638','slug'=>'salinas',  'servicios'=>['Fontanería','Fugas','Desatascos']],
      ];
      foreach ($zonas as $z):
      ?>
      <a href="<?php echo $base_url; ?>zonas/<?php echo $z['slug']; ?>" class="zona-card">
        <div class="zona-card-header">
          <span class="zona-card-ico">📍</span>
          <div>
            <span class="zona-card-nombre"><?php echo $z['nombre']; ?></span>
            <span class="zona-card-cp">CP <?php echo $z['cp']; ?></span>
          </div>
        </div>
        <div class="zona-card-servicios">
          <?php foreach ($z['servicios'] as $s): ?>
            <span><?php echo $s; ?></span>
          <?php endforeach; ?>
        </div>
        <span class="zona-card-link">Ver servicios en <?php echo $z['nombre']; ?> →</span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- STRIP -->
<div class="dif-strip">
  <div class="dif-strip-in">
    <div class="dif-item"><span class="dif-val">8 municipios</span><span class="dif-lbl">y todas sus zonas cercanas</span></div>
    <div class="dif-item"><span class="dif-val">Geófono y cámara</span><span class="dif-lbl">Detección de fugas sin obras</span></div>
    <div class="dif-item"><span class="dif-val">Precio cerrado</span><span class="dif-lbl">Sin sorpresas al final</span></div>
    <div class="dif-item"><span class="dif-val">Atención directa</span><span class="dif-lbl">En toda la zona de servicio</span></div>
  </div>
</div>

<!-- PEDANÍAS -->
<section style="padding:5rem 0;background:#f8fafc;border-top:1px solid #f1f5f9">
  <div style="max-width:1100px;margin:0 auto;padding:0 var(--space-md)">
    <p class="zona-lbl">¿No encuentras tu municipio?</p>
    <h2 style="font-size:clamp(1.5rem,3vw,2rem);font-weight:800;color:#0d1f33;letter-spacing:-.025em;line-height:1.15;margin-bottom:.75rem">También cubrimos <span style="color:#3b82f6">partidas y urbanizaciones</span></h2>
    <p style="color:#64748b;font-size:15px;line-height:1.75;margin-bottom:1.5rem;max-width:580px">Si vives en una partida rural o urbanización de la comarca y no ves tu municipio en la lista, llámanos. Trabajamos en toda la zona y te confirmamos rápidamente si cubrimos tu ubicación.</p>
    <a href="tel:+34613429032" style="display:inline-flex;align-items:center;gap:8px;background:#1e3a5f;color:#fff;padding:13px 26px;border-radius:8px;font-size:15px;font-weight:700;text-decoration:none">📞 Consultar mi zona</a>
  </div>
</section>

<!-- CTA -->
<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>¿Estás en <span>la comarca?</span></h2>
    <p>Llámanos o escríbenos y te atendemos hoy.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34613429032" class="btn-hz-w">📞 Llamar ahora</a>
      <a href="https://wa.me/34613429032" target="_blank" rel="noopener" class="btn-hz-g">💬 WhatsApp</a>
    </div>
    <div class="cta-dark-tel">Teléfono directo<strong>613 429 032</strong></div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>