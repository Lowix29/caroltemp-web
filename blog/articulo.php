<?php
require_once '../includes/db.php';

$slug = $_GET['slug'] ?? '';
if (!$slug) {
  header('Location: /blog/');
  exit;
}

$stmt = $pdo->prepare('SELECT * FROM articulos WHERE slug = ? AND publicado = 1 LIMIT 1');
$stmt->execute([$slug]);
$art = $stmt->fetch();

if (!$art) {
  header('HTTP/1.0 404 Not Found');
  $meta_title  = 'Artículo no encontrado — CarolTemp';
  $meta_desc   = '';
  $meta_url    = '';
  $schema_type = 'default';
  $page_css    = 'blog';
  $page_js     = '';
  include '../includes/head.php';
  echo '<div style="max-width:1100px;margin:0 auto;padding:8rem var(--space-md);text-align:center"><h1 style="color:#0d1f33;font-size:2rem;font-weight:800;margin-bottom:1rem">Artículo no encontrado</h1><a href="/blog/" style="display:inline-flex;align-items:center;gap:8px;background:#1e3a5f;color:#fff;padding:12px 24px;border-radius:8px;font-size:14px;font-weight:700;text-decoration:none">Volver al blog</a></div>';
  include '../includes/footer.php';
  exit;
}

$stmt_rel = $pdo->prepare('SELECT id, titulo, slug, extracto, zona, categoria FROM articulos WHERE publicado = 1 AND id != ? AND (zona = ? OR categoria = ?) ORDER BY fecha DESC LIMIT 3');
$stmt_rel->execute([$art['id'], $art['zona'], $art['categoria']]);
$relacionados = $stmt_rel->fetchAll();

$meta_title  = $art['meta_title'] ?: $art['titulo'] . ' — CarolTemp';
$meta_desc   = $art['meta_desc']  ?: $art['extracto'];
$meta_url    = 'https://caroltemp.com/blog/' . $art['slug'];
$schema_type = 'articulo';
$page_css    = 'blog';
$page_js     = '';

include '../includes/head.php';
?>

<!-- HERO -->
<section class="hz-dark" style="min-height:320px">
  <div class="hz-dark-bg"></div>
  <div class="hz-dark-glow"></div>
  <div class="hz-dark-con">
    <div class="articulo-meta">
      <?php if ($art['categoria']): ?>
        <a href="/blog/categoria/<?php echo urlencode($art['categoria']); ?>" class="blog-cat"><?php echo htmlspecialchars($art['categoria']); ?></a>
      <?php endif; ?>
      <?php if ($art['zona']): ?>
        <span class="blog-zona">📍 <?php echo htmlspecialchars($art['zona']); ?></span>
      <?php endif; ?>
      <span class="articulo-fecha"><?php echo date('d/m/Y', strtotime($art['fecha'])); ?></span>
    </div>
    <h1 style="font-size:clamp(1.8rem,4vw,3rem);font-weight:900;color:#fff;line-height:1.1;letter-spacing:-.025em;max-width:700px;margin-top:.75rem"><?php echo htmlspecialchars($art['titulo']); ?></h1>
    <p style="color:#94a3b8;font-size:15px;line-height:1.75;max-width:580px;margin-top:.75rem"><?php echo htmlspecialchars($art['extracto']); ?></p>
  </div>
  <?php if ($art['imagen']): ?>
  <div style="max-width:1100px;margin:2rem auto 0;padding:0 var(--space-md) 3rem">
    <img src="<?php echo htmlspecialchars($art['imagen']); ?>" alt="<?php echo htmlspecialchars($art['titulo']); ?>" loading="eager" style="width:100%;max-height:500px;object-fit:cover;border-radius:16px;box-shadow:0 8px 40px rgba(0,0,0,.35)">
  </div>
  <?php endif; ?>
</section>

<!-- CONTENIDO -->
<section style="padding:4rem 0">
  <div style="max-width:1100px;margin:0 auto;padding:0 var(--space-md)">
    <div class="articulo-layout">

      <article class="articulo-body">
        <?php echo $art['contenido']; ?>
      </article>

      <aside class="articulo-sidebar">

        <!-- CONTACTO -->
        <div class="sidebar-card">
          <h3>¿Necesitas ayuda?</h3>
          <p>Llámanos o escríbenos y te resolvemos cualquier duda.</p>
          <a href="tel:+34613429032" style="display:block;background:#1e3a5f;color:#fff;padding:12px;border-radius:8px;font-size:14.5px;font-weight:700;text-decoration:none;text-align:center;margin-top:1rem">📞 613 429 032</a>
          <a href="https://wa.me/34613429032" target="_blank" rel="noopener" style="display:block;background:#f8fafc;color:#1e3a5f;padding:12px;border-radius:8px;font-size:14.5px;font-weight:600;text-decoration:none;text-align:center;margin-top:.75rem;border:1.5px solid #e2e8f0">💬 WhatsApp</a>
        </div>

        <!-- ZONA -->
        <?php if ($art['zona']): ?>
        <div class="sidebar-card" style="margin-top:1rem">
          <h3>Fontanero en <?php echo htmlspecialchars($art['zona']); ?></h3>
          <p>Trabajamos en <?php echo htmlspecialchars($art['zona']); ?> y toda la comarca del Vinalopó.</p>
          <a href="/zonas/<?php echo strtolower(str_replace([' ','ó','é'],['-','o','e'],$art['zona'])); ?>.php" style="display:block;background:#f8fafc;color:#1e3a5f;padding:11px;border-radius:8px;font-size:14px;font-weight:600;text-decoration:none;text-align:center;margin-top:1rem;border:1.5px solid #e2e8f0">Ver servicios en <?php echo htmlspecialchars($art['zona']); ?> →</a>
        </div>
        <?php endif; ?>

        <!-- ARTÍCULOS RELACIONADOS SIDEBAR -->
        <?php if (!empty($relacionados)): ?>
        <div class="sidebar-card" style="margin-top:1rem">
          <h3>Artículos relacionados</h3>
          <div style="display:flex;flex-direction:column;gap:.75rem;margin-top:1rem">
            <?php foreach ($relacionados as $rel): ?>
            <a href="/blog/<?php echo urlencode($rel['slug']); ?>" style="display:flex;flex-direction:column;gap:4px;text-decoration:none;padding:.75rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;transition:border-color .15s" onmouseover="this.style.borderColor='#1e3a5f'" onmouseout="this.style.borderColor='#e2e8f0'">
              <?php if ($rel['categoria']): ?>
                <span style="font-size:11px;font-weight:600;color:#3b82f6;text-transform:uppercase;letter-spacing:.06em"><?php echo htmlspecialchars($rel['categoria']); ?></span>
              <?php endif; ?>
              <span style="color:#0d1f33;font-size:13.5px;font-weight:600;line-height:1.35"><?php echo htmlspecialchars($rel['titulo']); ?></span>
              <span style="color:#94a3b8;font-size:12px">Leer más →</span>
            </a>
            <?php endforeach; ?>
          </div>
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
            <a href="/<?php echo $srv[1]; ?>" style="display:flex;align-items:center;justify-content:space-between;padding:.65rem .75rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:7px;text-decoration:none;color:#1e3a5f;font-size:13.5px;font-weight:500;transition:all .15s" onmouseover="this.style.background='#1e3a5f';this.style.color='#fff'" onmouseout="this.style.background='#f8fafc';this.style.color='#1e3a5f'">
              <?php echo $srv[0]; ?><span style="font-size:12px">→</span>
            </a>
            <?php endforeach; ?>
          </div>
        </div>

      </aside>
    </div>
  </div>
</section>

<!-- PROYECTOS RELACIONADOS -->
<?php
$proyectos_rel = [];
if ($art['zona']) {
  $stmt_pro = $pdo->prepare('SELECT id, titulo, slug, descripcion, servicio, zona FROM proyectos WHERE publicado = 1 AND zona = ? ORDER BY fecha DESC LIMIT 3');
  $stmt_pro->execute([$art['zona']]);
  $proyectos_rel = $stmt_pro->fetchAll();
}
if (empty($proyectos_rel)) {
  $proyectos_rel = $pdo->query('SELECT id, titulo, slug, descripcion, servicio, zona FROM proyectos WHERE publicado = 1 ORDER BY fecha DESC LIMIT 3')->fetchAll();
}
?>
<?php if (!empty($proyectos_rel)): ?>
<section style="padding:4rem 0;background:#f8fafc;border-top:1px solid #f1f5f9">
  <div style="max-width:1100px;margin:0 auto;padding:0 var(--space-md)">
    <p class="zona-lbl">Trabajos realizados<?php echo $art['zona'] ? ' en ' . htmlspecialchars($art['zona']) : ''; ?></p>
    <h2 style="font-size:clamp(1.4rem,2.5vw,1.8rem);font-weight:800;color:#0d1f33;letter-spacing:-.02em;margin-bottom:1.75rem">Proyectos <span style="color:#3b82f6">relacionados</span></h2>
    <div class="blog-grid blog-grid-3">
      <?php foreach ($proyectos_rel as $pro): ?>
        <a href="/proyectos/<?php echo urlencode($pro['slug']); ?>" class="blog-card">
          <div class="blog-card-img blog-card-img-placeholder"><span>🔧</span></div>
          <div class="blog-card-body">
            <div class="blog-card-meta">
              <?php if ($pro['servicio']): ?><span class="blog-cat"><?php echo htmlspecialchars($pro['servicio']); ?></span><?php endif; ?>
              <?php if ($pro['zona']): ?><span class="blog-zona">📍 <?php echo htmlspecialchars($pro['zona']); ?></span><?php endif; ?>
            </div>
            <h2><?php echo htmlspecialchars($pro['titulo']); ?></h2>
            <p><?php echo htmlspecialchars($pro['descripcion']); ?></p>
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
    <h2>¿Te ha sido <span>útil este artículo?</span></h2>
    <p>Si necesitas ayuda con tu instalación, llámanos.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34613429032" class="btn-hz-w">📞 Llamar ahora</a>
      <a href="https://wa.me/34613429032" target="_blank" rel="noopener" class="btn-hz-g">💬 WhatsApp</a>
    </div>
    <div class="cta-dark-tel">Teléfono directo<strong>613 429 032</strong></div>
  </div>
</section>

<?php include '../includes/footer.php'; ?>