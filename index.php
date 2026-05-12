<?php
$meta_title  = "Hidrofont — Fontanería industrial y residencial en Alicante";
$meta_desc   = "Hacemos fontanería industrial y residencial en Alicante | Instalaciones, reformas y detección de fugas con geófono y cámara.";
$meta_url    = "https://hidrofont.es/";
$schema_type = "home";
$page_css    = "home";
$page_js     = "home";

include 'includes/head.php';
?>
<?php require_once 'includes/db.php'; ?>



<!-- HERO -->
<section class="hero-dark">
  <div class="hero-dark-bg" style="background:url('/img/contenido/hero-home.png') center/cover no-repeat"></div>
  <div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(10,24,37,.88) 0%,rgba(19,40,64,.82) 100%);z-index:0"></div>
  <div class="hero-dark-glow"></div>
  <div class="hero-dark-con">
    <div class="hero-dark-tag">
      <span class="hero-dark-dot"></span>
      Urgencias 24h fontanería
    </div>
    <h1>Fontanería industrial y residencial en <br><span class="hl">Alicante</span><br></h1>
    <p class="hero-dark-sub">Reparaciones urgentes, detección de fugas con geófono y cámara, instalaciones completas y reformas en viviendas de toda la comarca del Vinalopó. Trabajamos en Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Salinas y Monforte del Cid.</p>
    <div class="hero-dark-btns">
      <a href="tel:+34613429032" class="btn-dark-main">📞 613 429 032</a>
      <a href="<?php echo $base_url; ?>contacto" class="btn-dark-ghost">Urgencia 24h</a>
      <a href="<?php echo $base_url; ?>contacto" class="btn-dark-main">Solicitar presupuesto</a>
    </div>
    <div class="hero-dark-kpis">
      <div class="hero-dark-kpi">
        <span class="hero-dark-kpi-val">Nubeco</span>
        <span class="hero-dark-kpi-lbl">Instalador oficial en el Vinalopó</span>
      </div>
      <div class="hero-dark-kpi">
        <span class="hero-dark-kpi-val">100%</span>
        <span class="hero-dark-kpi-lbl">Precio cerrado siempre</span>
      </div>
      <div class="hero-dark-kpi">
        <span class="hero-dark-kpi-val">0€</span>
        <span class="hero-dark-kpi-lbl">Financiación sin adelantos</span>
      </div>
    </div>
  </div>
</section>

<!-- ZONAS -->
<div class="zonas-bar">
  <div class="zonas-bar-inner">
    <span class="zonas-label">Servicios de fontanería en:</span>
    <div class="zonas-tags">
      <a href="<?php echo $base_url; ?>zonas/elda"     class="zona-tag">Elda</a>
      <a href="<?php echo $base_url; ?>zonas/petrer"   class="zona-tag">Petrer</a>
      <a href="<?php echo $base_url; ?>zonas/novelda"  class="zona-tag">Novelda</a>
      <a href="<?php echo $base_url; ?>zonas/monovar"  class="zona-tag">Monóvar</a>
      <a href="<?php echo $base_url; ?>zonas/sax"      class="zona-tag">Sax</a>
      <a href="<?php echo $base_url; ?>zonas/pinoso"   class="zona-tag">Pinoso</a>
      <a href="<?php echo $base_url; ?>zonas/monforte" class="zona-tag">Monforte del Cid</a>
      <a href="<?php echo $base_url; ?>zonas/salinas"  class="zona-tag">Salinas</a>
    </div>
  </div>
</div>

<!-- BLOQUE SEO -->
<section class="home-sec">
  <div class="home-con">
    <p class="home-lbl">Fontanería en la comarca</p>
    <h2>Fontanero en Elda, Petrer, Novelda y <span class="hl">toda la comarca del Vinalopó</span></h2>
    <div style="max-width:780px;margin-top:1.25rem">
      <p style="color:#64748b;font-size:15px;line-height:1.85;margin-bottom:1rem">En Hidrofont trabajamos como fontaneros en Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Salinas y Monforte del Cid. Nos especializamos en fontanería residencial completa: reparaciones urgentes, detección de fugas sin obra, instalación de termos eléctricos, ósmosis inversa, descalcificadores y reformas de baño.</p>
      <p style="color:#64748b;font-size:15px;line-height:1.85;margin-bottom:1rem">La comarca combina viviendas antiguas con instalaciones deterioradas y zonas más modernas o urbanizaciones con problemas de presión o suministro. Por eso cada trabajo requiere experiencia real. No improvisamos: analizamos la instalación y damos una solución clara desde el principio.</p>
      <p style="color:#64748b;font-size:15px;line-height:1.85">Si buscas un fontanero en Elda o cerca de ti que trabaje bien, llegue rápido y te diga el precio antes de empezar, estás en el sitio correcto. Trabajamos con precio cerrado y sin sorpresas.</p>
    </div>
  </div>
</section>

<!-- SERVICIOS POR CIUDAD -->
<section class="home-sec home-sec-gray">
  <div class="home-con">
    <p class="home-lbl">Servicios por ciudad</p>
    <h2>Fontanero, fugas y desatascos <span class="hl">en tu municipio</span></h2>
    <p class="home-sub">Accede directamente al servicio que necesitas en tu zona.</p>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1px;background:#e2e8f0;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;margin-top:2rem">
      <a href="<?php echo $base_url; ?>fontanero/fontanero-elda" style="background:#fff;padding:1.25rem 1.5rem;text-decoration:none;transition:background .15s;display:flex;flex-direction:column;gap:4px" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
        <span style="color:#0d1f33;font-size:14px;font-weight:700">Fontanero en Elda</span>
        <span style="color:#64748b;font-size:12.5px">Reparaciones y reformas →</span>
      </a>
      <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-petrer" style="background:#fff;padding:1.25rem 1.5rem;text-decoration:none;transition:background .15s;display:flex;flex-direction:column;gap:4px" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
        <span style="color:#0d1f33;font-size:14px;font-weight:700">Fugas de agua en Petrer</span>
        <span style="color:#64748b;font-size:12.5px">Geófono y cámara →</span>
      </a>
      <a href="<?php echo $base_url; ?>desatascos/desatascos-novelda" style="background:#fff;padding:1.25rem 1.5rem;text-decoration:none;transition:background .15s;display:flex;flex-direction:column;gap:4px" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
        <span style="color:#0d1f33;font-size:14px;font-weight:700">Desatascos en Novelda</span>
        <span style="color:#64748b;font-size:12.5px">Tuberías y bajantes →</span>
      </a>
      <a href="<?php echo $base_url; ?>fontanero/fontanero-monovar" style="background:#fff;padding:1.25rem 1.5rem;text-decoration:none;transition:background .15s;display:flex;flex-direction:column;gap:4px" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
        <span style="color:#0d1f33;font-size:14px;font-weight:700">Fontanero en Monóvar</span>
        <span style="color:#64748b;font-size:12.5px">Viviendas y fincas →</span>
      </a>
      <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-sax" style="background:#fff;padding:1.25rem 1.5rem;text-decoration:none;transition:background .15s;display:flex;flex-direction:column;gap:4px" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
        <span style="color:#0d1f33;font-size:14px;font-weight:700">Fugas de agua en Sax</span>
        <span style="color:#64748b;font-size:12.5px">Detección sin obras →</span>
      </a>
      <a href="<?php echo $base_url; ?>desatascos/desatascos-pinoso" style="background:#fff;padding:1.25rem 1.5rem;text-decoration:none;transition:background .15s;display:flex;flex-direction:column;gap:4px" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
        <span style="color:#0d1f33;font-size:14px;font-weight:700">Desatascos en Pinoso</span>
        <span style="color:#64748b;font-size:12.5px">Viviendas rurales →</span>
      </a>
      <a href="<?php echo $base_url; ?>fontanero/fontanero-monforte" style="background:#fff;padding:1.25rem 1.5rem;text-decoration:none;transition:background .15s;display:flex;flex-direction:column;gap:4px" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
        <span style="color:#0d1f33;font-size:14px;font-weight:700">Fontanero en Monforte del Cid</span>
        <span style="color:#64748b;font-size:12.5px">Urbanizaciones →</span>
      </a>
      <a href="<?php echo $base_url; ?>fugas/deteccion-fugas-salinas" style="background:#fff;padding:1.25rem 1.5rem;text-decoration:none;transition:background .15s;display:flex;flex-direction:column;gap:4px" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
        <span style="color:#0d1f33;font-size:14px;font-weight:700">Fugas de agua en Salinas</span>
        <span style="color:#64748b;font-size:12.5px">Casas antiguas →</span>
      </a>
      <a href="<?php echo $base_url; ?>desatascos/desatascos-elda" style="background:#fff;padding:1.25rem 1.5rem;text-decoration:none;transition:background .15s;display:flex;flex-direction:column;gap:4px" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
        <span style="color:#0d1f33;font-size:14px;font-weight:700">Desatascos en Elda</span>
        <span style="color:#64748b;font-size:12.5px">Urgentes →</span>
      </a>
    </div>
  </div>
</section>

<!-- SERVICIOS -->
<section class="home-sec home-sec-gray">
  <div class="home-con">
    <div class="sec-header">
      <div>
        <p class="home-lbl">Servicios</p>
        <h2>¿En qué <span class="hl">puedo ayudarte?</span></h2>
      </div>
      <a href="<?php echo $base_url; ?>servicios">Ver todos los servicios →</a>
    </div>
    <div class="servicios-grid">
      <a href="<?php echo $base_url; ?>servicios#reparaciones" class="servicio-card">
        <span class="svc-num">01</span>
        <h3>Reparaciones urgentes</h3>
        <p>Fontanero urgente en Elda, Petrer y toda la comarca. Fugas de agua, cisternas, grifos y averías con solución rápida y precio cerrado.</p>
        <span class="svc-link">Ver servicio →</span>
      </a>
      <a href="<?php echo $base_url; ?>servicios#termos" class="servicio-card">
        <span class="svc-num">02</span>
        <h3>Termos eléctricos Nubeco</h3>
        <p>Instalación de termos eléctricos en Elda, Petrer y alrededores. Instalador oficial Nubeco con garantía del fabricante.</p>
        <span class="svc-link">Ver servicio →</span>
      </a>
      <a href="<?php echo $base_url; ?>servicios#osmosis" class="servicio-card">
        <span class="svc-num">03</span>
        <h3>Ósmosis inversa</h3>
        <p>Instalación de sistemas de ósmosis en viviendas. Agua limpia directamente del grifo con mantenimiento incluido.</p>
        <span class="svc-link">Ver servicio →</span>
      </a>
      <a href="<?php echo $base_url; ?>servicios#descalcificadores" class="servicio-card">
        <span class="svc-num">04</span>
        <h3>Descalcificadores</h3>
        <p>Solución al agua dura en Elda, Petrer y comarca. Instalación y mantenimiento de descalcificadores para proteger tuberías y electrodomésticos.</p>
        <span class="svc-link">Ver servicio →</span>
      </a>
      <a href="<?php echo $base_url; ?>servicios#reformas" class="servicio-card">
        <span class="svc-num">05</span>
        <h3>Reformas de baño</h3>
        <p>Reformas completas o parciales en Elda, Petrer y alrededores. Precio cerrado antes de empezar, sin sorpresas.</p>
        <span class="svc-link">Ver servicio →</span>
      </a>
      <a href="<?php echo $base_url; ?>financiacion" class="servicio-card">
        <span class="svc-num">06</span>
        <h3>Financiación</h3>
        <p>Financia tu instalación o reforma completa. Material y mano de obra sin adelantar dinero.</p>
        <span class="svc-link">Ver más →</span>
      </a>
    </div>
  </div>
</section>

<!-- DIFERENCIADORES -->
<div class="dif-strip">
  <div class="dif-strip-in">
    <div class="dif-item"><span class="dif-val">Instalador oficial</span><span class="dif-lbl">Termos Nubeco en toda la comarca</span></div>
    <div class="dif-item"><span class="dif-val">Financiación completa</span><span class="dif-lbl">Material e instalación sin adelantos</span></div>
    <div class="dif-item"><span class="dif-val">Precio cerrado</span><span class="dif-lbl">Sabes lo que pagas antes de empezar</span></div>
    <div class="dif-item"><span class="dif-val">Detección profesional</span><span class="dif-lbl">Geófono y cámara sin obra</span></div>
  </div>
</div>

<!-- ÚLTIMOS PROYECTOS -->
<?php
$ultimos_proyectos = $pdo->query('SELECT titulo, slug, descripcion, servicio, zona, imagen FROM proyectos WHERE publicado = 1 ORDER BY fecha DESC LIMIT 3')->fetchAll();
?>
<?php if (!empty($ultimos_proyectos)): ?>
<section class="home-sec">
  <div class="home-con">
    <div class="sec-header">
      <div>
        <p class="home-lbl">Proyectos</p>
        <h2>Últimos <span class="hl">trabajos realizados</span></h2>
      </div>
      <a href="<?php echo $base_url; ?>proyectos/">Ver todos los proyectos →</a>
    </div>
    <div class="resenas-grid">
      <?php foreach ($ultimos_proyectos as $pro): ?>
      <a href="<?php echo $base_url; ?>proyectos/<?php echo urlencode($pro['slug']); ?>" style="text-decoration:none">
        <div class="resena-card" style="transition:border-color .15s;cursor:pointer" onmouseover="this.style.borderColor='#1e3a5f'" onmouseout="this.style.borderColor='#e2e8f0'">
          <?php if ($pro['imagen']): ?>
            <img src="<?php echo htmlspecialchars($pro['imagen']); ?>" alt="<?php echo htmlspecialchars($pro['titulo']); ?>" style="width:100%;height:160px;object-fit:cover;border-radius:8px" loading="lazy">
          <?php else: ?>
            <div style="width:100%;height:160px;background:#f1f5f9;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:28px">🔧</div>
          <?php endif; ?>
          <div style="display:flex;gap:.5rem;margin-top:.75rem;flex-wrap:wrap">
            <?php if ($pro['servicio']): ?><span style="font-size:11px;font-weight:600;color:#3b82f6;background:#eff6ff;padding:3px 10px;border-radius:20px"><?php echo htmlspecialchars($pro['servicio']); ?></span><?php endif; ?>
            <?php if ($pro['zona']): ?><span style="font-size:11px;font-weight:500;color:#64748b;background:#f1f5f9;padding:3px 10px;border-radius:20px">📍 <?php echo htmlspecialchars($pro['zona']); ?></span><?php endif; ?>
          </div>
          <h3 style="color:#0d1f33;font-size:15px;font-weight:700;margin-top:.5rem;line-height:1.3"><?php echo htmlspecialchars($pro['titulo']); ?></h3>
          <p style="color:#64748b;font-size:13px;line-height:1.6;margin-top:.35rem"><?php echo htmlspecialchars(mb_strimwidth($pro['descripcion'], 0, 120, '...')); ?></p>
          <span style="color:#1e3a5f;font-size:13px;font-weight:600;margin-top:.5rem;display:block">Ver proyecto →</span>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- POR QUÉ ELEGIRNOS -->
<section class="home-sec">
  <div class="home-con">
    <p class="home-lbl">Por qué elegirnos</p>
    <h2>Trabajo bien hecho, <span class="hl">sin atajos</span></h2>
    <p class="home-sub">Lo que nos diferencia no es el precio, es cómo hacemos el trabajo.</p>
    <div class="porque-grid">
      <div class="porque-item">
        <span class="porque-num">01</span>
        <h3>Precio cerrado siempre</h3>
        <p>Antes de empezar cualquier trabajo te damos un precio cerrado real, sin extras ni sorpresas.</p>
      </div>
      <div class="porque-item">
        <span class="porque-num">02</span>
        <h3>Trabajo garantizado</h3>
        <p>Si algo no queda bien, volvemos y lo solucionamos sin coste adicional.</p>
      </div>
      <div class="porque-item">
        <span class="porque-num">03</span>
        <h3>Dejamos todo limpio</h3>
        <p>Terminamos el trabajo y dejamos la zona como estaba.</p>
      </div>
      <div class="porque-item">
        <span class="porque-num">04</span>
        <h3>Somos de la zona</h3>
        <p>Trabajamos en Elda, Petrer y toda la comarca. Conocemos las instalaciones y sus problemas.</p>
      </div>
      <div class="porque-item">
        <span class="porque-num">05</span>
        <h3>Te explicamos todo</h3>
        <p>Sin tecnicismos. Sabes qué hacemos y por qué.</p>
      </div>
      <div class="porque-item">
        <span class="porque-num">06</span>
        <h3>Financiación disponible</h3>
        <p>Para trabajos grandes puedes financiar todo sin adelantar dinero.</p>
      </div>
    </div>
  </div>
</section>

<!-- FINANCIACIÓN -->
<section class="home-sec home-sec-gray">
  <div class="home-con">
    <div class="fin-wrap">
      <div class="fin-texto">
        <p class="home-lbl">Financiación</p>
        <h2>Financia tu proyecto <span class="hl">sin complicaciones</span></h2>
        <p>¿Quieres instalar una ósmosis, cambiar un termo o reformar el baño y no pagar todo de golpe? Puedes financiar el trabajo completo.</p>
        <ul class="fin-lista">
          <li><span class="fin-check"><svg viewBox="0 0 10 10" fill="none"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Cubre material e instalación completa</li>
          <li><span class="fin-check"><svg viewBox="0 0 10 10" fill="none"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Sin intereses ni gastos ocultos</li>
          <li><span class="fin-check"><svg viewBox="0 0 10 10" fill="none"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Gestión rápida</li>
          <li><span class="fin-check"><svg viewBox="0 0 10 10" fill="none"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Sin adelantar dinero</li>
        </ul>
        <a href="<?php echo $base_url; ?>financiacion" class="btn-fin">Más información →</a>
      </div>
      <div class="fin-visual">
        <div class="fin-paso">
          <div class="fin-num">1</div>
          <div class="fin-paso-txt"><strong>Nos cuentas qué necesitas</strong><span>Valoramos el trabajo sin compromiso</span></div>
        </div>
        <div class="fin-paso">
          <div class="fin-num">2</div>
          <div class="fin-paso-txt"><strong>Te damos precio cerrado</strong><span>Sabes lo que cuesta antes de empezar</span></div>
        </div>
        <div class="fin-paso">
          <div class="fin-num">3</div>
          <div class="fin-paso-txt"><strong>Solicitas financiación</strong><span>Aprobación rápida</span></div>
        </div>
        <div class="fin-paso">
          <div class="fin-num">4</div>
          <div class="fin-paso-txt"><strong>Hacemos el trabajo</strong><span>Sin que tengas que adelantar nada</span></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- RESEÑAS -->
<section class="home-sec">
  <div class="home-con">
    <p class="home-lbl">Clientes</p>
    <h2>Lo que dicen <span class="hl">nuestros clientes</span> en el Vinalopó</h2>
    <p class="home-sub">Trabajos reales, clientes reales.</p>
    <div class="resenas-grid">
      <div class="resena-card">
        <span class="resena-estrellas">★★★★★</span>
        <p class="resena-texto">Vinieron a cambiar el termo y dejaron todo perfecto. Precio exacto al presupuesto, sin sorpresas. Muy recomendables.</p>
        <span class="resena-autor">— Cliente en Elda</span>
      </div>
      <div class="resena-card">
        <span class="resena-estrellas">★★★★★</span>
        <p class="resena-texto">Instalaron una ósmosis en casa y explicaron todo el proceso. Trabajo limpio y bien hecho.</p>
        <span class="resena-autor">— Cliente en Petrer</span>
      </div>
      <div class="resena-card">
        <span class="resena-estrellas">★★★★★</span>
        <p class="resena-texto">Reforma completa del baño con financiación. Todo fácil y sin complicaciones. Resultado perfecto.</p>
        <span class="resena-autor">— Cliente en Novelda</span>
      </div>
    </div>
  </div>
</section>

<!-- CTA FINAL -->
<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>¿Necesitas un fontanero <span> cerca de ti?</span></h2>
    <p>Llámanos o escríbenos. Te atendemos hoy mismo.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34613429032" class="btn-hz-w">📞 Llamar ahora</a>
      <a href="https://wa.me/34613429032" target="_blank" rel="noopener" class="btn-hz-g">💬 WhatsApp</a>
    </div>
    <div class="cta-dark-tel">Teléfono directo<strong>613 429 032</strong></div>
  </div>
</section>
<!-- ÚLTIMOS ARTÍCULOS -->
<?php
$ultimos_articulos = $pdo->query('SELECT titulo, slug, extracto, categoria, zona, imagen FROM articulos WHERE publicado = 1 ORDER BY fecha DESC LIMIT 3')->fetchAll();
?>
<?php if (!empty($ultimos_articulos)): ?>
<section class="home-sec home-sec-gray">
  <div class="home-con">
    <div class="sec-header">
      <div>
        <p class="home-lbl">Blog</p>
        <h2>Últimos <span class="hl">artículos publicados</span></h2>
      </div>
      <a href="<?php echo $base_url; ?>blog/">Ver todo el blog →</a>
    </div>
    <div class="resenas-grid">
      <?php foreach ($ultimos_articulos as $art): ?>
      <a href="<?php echo $base_url; ?>blog/<?php echo urlencode($art['slug']); ?>" style="text-decoration:none">
        <div class="resena-card" style="transition:border-color .15s;cursor:pointer" onmouseover="this.style.borderColor='#1e3a5f'" onmouseout="this.style.borderColor='#e2e8f0'">
          <?php if ($art['imagen']): ?>
            <img src="<?php echo htmlspecialchars($art['imagen']); ?>" alt="<?php echo htmlspecialchars($art['titulo']); ?>" style="width:100%;height:160px;object-fit:cover;border-radius:8px" loading="lazy">
          <?php else: ?>
            <div style="width:100%;height:160px;background:#f1f5f9;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:28px">✍️</div>
          <?php endif; ?>
          <div style="display:flex;gap:.5rem;margin-top:.75rem;flex-wrap:wrap">
            <?php if ($art['categoria']): ?><span style="font-size:11px;font-weight:600;color:#6d28d9;background:#f5f3ff;padding:3px 10px;border-radius:20px"><?php echo htmlspecialchars($art['categoria']); ?></span><?php endif; ?>
            <?php if ($art['zona']): ?><span style="font-size:11px;font-weight:500;color:#64748b;background:#f1f5f9;padding:3px 10px;border-radius:20px">📍 <?php echo htmlspecialchars($art['zona']); ?></span><?php endif; ?>
          </div>
          <h3 style="color:#0d1f33;font-size:15px;font-weight:700;margin-top:.5rem;line-height:1.3"><?php echo htmlspecialchars($art['titulo']); ?></h3>
          <p style="color:#64748b;font-size:13px;line-height:1.6;margin-top:.35rem"><?php echo htmlspecialchars(mb_strimwidth($art['extracto'], 0, 120, '...')); ?></p>
          <span style="color:#1e3a5f;font-size:13px;font-weight:600;margin-top:.5rem;display:block">Leer artículo →</span>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>