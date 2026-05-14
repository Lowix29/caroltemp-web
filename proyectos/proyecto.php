<?php
require_once '../includes/db.php';

$slug = $_GET['slug'] ?? '';
if (!$slug) {
  header('Location: /proyectos/');
  exit;
}

$stmt = $pdo->prepare('SELECT * FROM proyectos WHERE slug = ? AND publicado = 1 LIMIT 1');
$stmt->execute([$slug]);
$pro = $stmt->fetch();

if (!$pro) {
  header('HTTP/1.0 404 Not Found');
  $meta_title  = 'Proyecto no encontrado — CarolTemp';
  $meta_desc   = '';
  $meta_url    = '';
  $schema_type = 'default';
  $page_css    = 'proyectos';
  $page_js     = '';
  include '../includes/head.php';
  echo '<div style="max-width:1100px;margin:0 auto;padding:8rem var(--space-md);text-align:center"><h1 style="color:#0d1f33;font-size:2rem;font-weight:800;margin-bottom:1rem">Proyecto no encontrado</h1><a href="/proyectos/" style="display:inline-flex;align-items:center;gap:8px;background:#1e3a5f;color:#fff;padding:12px 24px;border-radius:8px;font-size:14px;font-weight:700;text-decoration:none">Ver proyectos</a></div>';
  include '../includes/footer.php';
  exit;
}

$stmt_rel = $pdo->prepare('SELECT id, titulo, slug, descripcion, zona, servicio FROM proyectos WHERE publicado = 1 AND id != ? AND (zona = ? OR servicio = ?) ORDER BY fecha DESC LIMIT 3');
$stmt_rel->execute([$pro['id'], $pro['zona'], $pro['servicio']]);
$relacionados = $stmt_rel->fetchAll();

$stmt_arts = $pdo->prepare('SELECT id, titulo, slug, extracto, categoria FROM articulos WHERE publicado = 1 AND (zona = ? OR categoria IS NOT NULL) ORDER BY fecha DESC LIMIT 3');
$stmt_arts->execute([$pro['zona']]);
$articulos_rel = $stmt_arts->fetchAll();

$meta_title  = $pro['meta_title'] ?: $pro['titulo'] . ' — CarolTemp';
$meta_desc   = $pro['meta_desc']  ?: $pro['descripcion'];
$meta_url    = 'https://caroltemp.com/proyectos/' . $pro['slug'];
$schema_type = 'proyecto';
$page_css    = 'proyectos';
$page_js     = '';

include '../includes/head.php';
?>

<!-- HERO -->
<section class="hz-dark" style="min-height:320px">
  <div class="hz-dark-bg"></div>
  <div class="hz-dark-glow"></div>
  <div class="hz-dark-con">
    <div class="articulo-meta">
      <?php if ($pro['servicio']): ?>
        <a href="/proyectos/servicio/<?php echo urlencode($pro['servicio']); ?>" class="blog-cat"><?php echo htmlspecialchars($pro['servicio']); ?></a>
      <?php endif; ?>
      <?php if ($pro['zona']): ?>
        <span class="blog-zona">📍 <?php echo htmlspecialchars($pro['zona']); ?></span>
      <?php endif; ?>
      <span class="articulo-fecha"><?php echo date('d/m/Y', strtotime($pro['fecha'])); ?></span>
    </div>
    <h1 style="font-size:clamp(1.8rem,4vw,3rem);font-weight:900;color:#fff;line-height:1.1;letter-spacing:-.025em;max-width:700px;margin-top:.75rem"><?php echo htmlspecialchars($pro['titulo']); ?></h1>
    <p style="color:#94a3b8;font-size:15px;line-height:1.75;max-width:580px;margin-top:.75rem"><?php echo htmlspecialchars($pro['descripcion']); ?></p>
  </div>
  <?php if ($pro['imagen']): ?>
  <div style="max-width:1100px;margin:2rem auto 0;padding:0 var(--space-md) 3rem">
    <img src="<?php echo htmlspecialchars($pro['imagen']); ?>" alt="<?php echo htmlspecialchars($pro['titulo']); ?>" loading="eager" style="width:100%;max-height:500px;object-fit:cover;border-radius:16px;box-shadow:0 8px 40px rgba(0,0,0,.35)">
  </div>
  <?php endif; ?>
</section>

<!-- CONTENIDO -->
<section style="padding:4rem 0">
  <div style="max-width:1100px;margin:0 auto;padding:0 var(--space-md)">
    <div class="articulo-layout">

      <article class="articulo-body">
        <?php echo $pro['contenido']; ?>
      </article>

      <aside class="articulo-sidebar">

        <!-- CONTACTO -->
        <div class="sidebar-card">
          <h3>¿Necesitas este servicio?</h3>
          <p>Pídenos presupuesto sin compromiso para tu instalación.</p>
          <a href="tel:+34613429032" style="display:block;background:#1e3a5f;color:#fff;padding:12px;border-radius:8px;font-size:14.5px;font-weight:700;text-decoration:none;text-align:center;margin-top:1rem">📞 613 429 032</a>
          <a href="https://wa.me/34613429032" target="_blank" rel="noopener" style="display:block;background:#f8fafc;color:#1e3a5f;padding:12px;border-radius:8px;font-size:14.5px;font-weight:600;text-decoration:none;text-align:center;margin-top:.75rem;border:1.5px solid #e2e8f0">💬 WhatsApp</a>
        </div>

        <!-- ZONA -->
        <?php if ($pro['zona']): ?>
        <div class="sidebar-card" style="margin-top:1rem">
          <h3>Fontanero en <?php echo htmlspecialchars($pro['zona']); ?></h3>
          <p>Trabajamos en <?php echo htmlspecialchars($pro['zona']); ?> y toda la comarca.</p>
          <a href="/zonas/<?php echo strtolower(str_replace([' ','ó','é'],['-','o','e'],$pro['zona'])); ?>.php" style="display:block;background:#f8fafc;color:#1e3a5f;padding:11px;border-radius:8px;font-size:14px;font-weight:600;text-decoration:none;text-align:center;margin-top:1rem;border:1.5px solid #e2e8f0">Ver servicios en <?php echo htmlspecialchars($pro['zona']); ?> →</a>
        </div>
        <?php endif; ?>

        <!-- SERVICIOS -->
        <div class="sidebar-card" style="margin-top:1rem">
          <h3>Nuestros servicios</h3>
          <div style="display:flex;flex-direction:column;gap:.5rem;margin-top:1rem">
            <?php
            $servicios_sidebar = [
              ['Reparaciones urgentes','servicios.php#reparaciones'],
              ['Termos Nubeco','servicios.php#termos'],
              ['Ósmosis inversa','servicios.php#osmosis'],
              ['Descalcificadores','servicios.php#descalcificadores'],
              ['Reformas de baño','servicios.php#reformas'],
              ['Financiación','financiacion.php'],
            ];
            foreach ($servicios_sidebar as $srv):
            ?>
            <a href="/<?php echo $srv[1]; ?>" style="display:flex;align-items:center;justify-content:space-between;padding:.65rem .75rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;text-decoration:none;color:#1e3a5f;font-size:13.5px;font-weight:500" onmouseover="this.style.background='#1e3a5f';this.style.color='#fff'" onmouseout="this.style.background='#f8fafc';this.style.color='#1e3a5f'">
              <?php echo $srv[0]; ?><span style="font-size:12px">→</span>
            </a>
            <?php endforeach; ?>
          </div>
        </div>

      </aside>
    </div>
  </div>
</section>

  <!-- PROYECTOS RELACIONADOS ABAJO -->
<?php
$proyectos_abajo = $pdo->prepare('SELECT id, titulo, slug, descripcion, servicio, zona, imagen FROM proyectos WHERE publicado = 1 AND id != ? ORDER BY fecha DESC LIMIT 3');
$proyectos_abajo->execute([$pro['id']]);
$proyectos_abajo = $proyectos_abajo->fetchAll();
?>
<?php if (!empty($proyectos_abajo)): ?>
<section style="padding:4rem 0;background:#f8fafc;border-top:1px solid #f1f5f9">
  <div style="max-width:1100px;margin:0 auto;padding:0 var(--space-md)">
    <p class="zona-lbl">Más trabajos</p>
    <h2 style="font-size:clamp(1.4rem,2.5vw,1.8rem);font-weight:800;color:#0d1f33;letter-spacing:-.02em;margin-bottom:1.75rem">Otros <span style="color:#3b82f6">proyectos realizados</span></h2>
    <div class="blog-grid blog-grid-3">
      <?php foreach ($proyectos_abajo as $pb): ?>
        <a href="/proyectos/<?php echo urlencode($pb['slug']); ?>" class="blog-card">
          <?php if ($pb['imagen']): ?>
            <div class="blog-card-img"><img src="<?php echo htmlspecialchars($pb['imagen']); ?>" alt="<?php echo htmlspecialchars($pb['titulo']); ?>" loading="lazy"></div>
          <?php else: ?>
            <div class="blog-card-img blog-card-img-placeholder"><span>🔧</span></div>
          <?php endif; ?>
          <div class="blog-card-body">
            <div class="blog-card-meta">
              <?php if ($pb['servicio']): ?><span class="blog-cat"><?php echo htmlspecialchars($pb['servicio']); ?></span><?php endif; ?>
              <?php if ($pb['zona']): ?><span class="blog-zona">📍 <?php echo htmlspecialchars($pb['zona']); ?></span><?php endif; ?>
            </div>
            <h2><?php echo htmlspecialchars($pb['titulo']); ?></h2>
            <p><?php echo htmlspecialchars($pb['descripcion']); ?></p>
            <span class="blog-card-link">Ver proyecto →</span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>



<!-- CTA -->
<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>¿Quieres un <span>resultado así?</span></h2>
    <p>Pídenos presupuesto sin compromiso.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34613429032" class="btn-hz-w">📞 Llamar ahora</a>
      <a href="https://wa.me/34613429032" target="_blank" rel="noopener" class="btn-hz-g">💬 WhatsApp</a>
    </div>
    <div class="cta-dark-tel">Teléfono directo<strong>613 429 032</strong></div>
  </div>
</section>

<!-- ARTÍCULOS RELACIONADOS ABAJO -->
<?php if (!empty($articulos_rel)): ?>
<section style="padding:4rem 0;background:#f8fafc;border-top:1px solid #f1f5f9">
  <div style="max-width:1100px;margin:0 auto;padding:0 var(--space-md)">
    <p class="zona-lbl">Del blog</p>
    <h2 style="font-size:clamp(1.4rem,2.5vw,1.8rem);font-weight:800;color:#0d1f33;letter-spacing:-.02em;margin-bottom:1.75rem">Artículos <span style="color:#3b82f6">que pueden interesarte</span></h2>
    <div class="blog-grid blog-grid-3">
      <?php foreach ($articulos_rel as $art): ?>
        <a href="/blog/<?php echo urlencode($art['slug']); ?>" class="blog-card">
          <div class="blog-card-img blog-card-img-placeholder"><span>✍️</span></div>
          <div class="blog-card-body">
            <div class="blog-card-meta">
              <?php if ($art['categoria']): ?><span class="blog-cat"><?php echo htmlspecialchars($art['categoria']); ?></span><?php endif; ?>
            </div>
            <h2><?php echo htmlspecialchars($art['titulo']); ?></h2>
            <p><?php echo htmlspecialchars($art['extracto']); ?></p>
            <span class="blog-card-link">Leer más →</span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>