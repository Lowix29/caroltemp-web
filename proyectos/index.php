<?php
$meta_title  = "Trabajos realizados de fontanería y reformas | CarolTemp";
$meta_desc   = "Descubre algunos de nuestros trabajos de fontanería, detección de fugas, desatascos, reformas y mantenimiento realizados en el Vinalopó.";
$meta_url    = "https://caroltemp.com/proyectos/";
$schema_type = "default";
$page_css    = "proyectos";
$page_js     = "";

require_once '../includes/db.php';

$zona_filtro     = $_GET['zona']     ?? '';
$servicio_filtro = $_GET['servicio'] ?? '';

$where  = ['publicado = 1'];
$params = [];

if ($zona_filtro)     { $where[] = 'zona = ?';     $params[] = $zona_filtro; }
if ($servicio_filtro) { $where[] = 'servicio = ?'; $params[] = $servicio_filtro; }

$sql  = 'SELECT id, titulo, slug, descripcion, imagen, zona, servicio, fecha FROM proyectos';
$sql .= ' WHERE ' . implode(' AND ', $where);
$sql .= ' ORDER BY fecha DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$proyectos = $stmt->fetchAll();

$zonas     = $pdo->query('SELECT DISTINCT zona FROM proyectos WHERE publicado=1 AND zona != "" ORDER BY zona')->fetchAll(PDO::FETCH_COLUMN);
$servicios = $pdo->query('SELECT DISTINCT servicio FROM proyectos WHERE publicado=1 AND servicio != "" ORDER BY servicio')->fetchAll(PDO::FETCH_COLUMN);

include '../includes/head.php';
?>

<!-- HERO -->
<section class="hz-dark" style="min-height:340px">
  <div class="hz-dark-bg"></div>
  <div class="hz-dark-glow"></div>
  <div class="hz-dark-con">
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Trabajos realizados</div>
    <h1>Proyectos de fontanería<br><span class="hl">en el Vinalopó.</span></h1>
    <p class="hz-dark-sub">Trabajos reales realizados en la comarca. Cada proyecto con su zona, servicio y descripción detallada.</p>
  </div>
</section>

<!-- PROYECTOS -->
<section style="padding:5rem 0">
  <div style="max-width:1100px;margin:0 auto;padding:0 var(--space-md)">

    <!-- FILTROS -->
    <?php if (!empty($zonas) || !empty($servicios)): ?>
    <div class="blog-filtros">
      <a href="/proyectos/" class="filtro-tag <?php echo (!$zona_filtro && !$servicio_filtro) ? 'active' : ''; ?>">Todos</a>
      <?php foreach ($servicios as $serv): ?>
        <a href="/proyectos/servicio/<?php echo urlencode($serv); ?>" class="filtro-tag <?php echo $servicio_filtro === $serv ? 'active' : ''; ?>"><?php echo htmlspecialchars($serv); ?></a>
      <?php endforeach; ?>
      <?php foreach ($zonas as $zona): ?>
        <a href="/proyectos/zona/<?php echo urlencode($zona); ?>" class="filtro-tag <?php echo $zona_filtro === $zona ? 'active' : ''; ?>">📍 <?php echo htmlspecialchars($zona); ?></a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (empty($proyectos)): ?>
      <div class="blog-empty">
        <p>Todavía no hay proyectos publicados. Vuelve pronto.</p>
        <a href="/contacto.php" style="display:inline-flex;align-items:center;gap:8px;background:#1e3a5f;color:#fff;padding:12px 24px;border-radius:8px;font-size:14px;font-weight:700;text-decoration:none;margin-top:1rem">Contactar</a>
      </div>
    <?php else: ?>
      <div class="blog-grid">
        <?php foreach ($proyectos as $pro): ?>
          <a href="/proyectos/<?php echo urlencode($pro['slug']); ?>" class="blog-card">
            <?php if ($pro['imagen']): ?>
              <div class="blog-card-img"><img src="<?php echo htmlspecialchars($pro['imagen']); ?>" alt="<?php echo htmlspecialchars($pro['titulo']); ?>" loading="lazy"></div>
            <?php else: ?>
              <div class="blog-card-img blog-card-img-placeholder"><span>🔧</span></div>
            <?php endif; ?>
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
    <?php endif; ?>

  </div>
</section>

<!-- CTA -->
<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>¿Quieres un <span>trabajo así?</span></h2>
    <p>Pídenos presupuesto sin compromiso.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34613429032" class="btn-hz-w">📞 Llamar ahora</a>
      <a href="https://wa.me/34613429032" target="_blank" rel="noopener" class="btn-hz-g">💬 WhatsApp</a>
    </div>
    <div class="cta-dark-tel">Teléfono directo<strong>613 429 032</strong></div>
  </div>
</section>

<?php include '../includes/footer.php'; ?>