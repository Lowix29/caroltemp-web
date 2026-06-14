<?php
$meta_title  = "Blog de fontanería | Consejos, averías y mantenimiento — CarolTemp";
$meta_desc   = "Guías prácticas, consejos profesionales y soluciones para fugas, desatascos, termos, instalaciones y mantenimiento del hogar.";
$meta_url    = "https://caroltemp.com/noticias/";
$schema_type = "default";
$page_css    = "blog";
$page_js     = "";

require_once '../includes/db.php';

$zona_filtro = $_GET['zona']      ?? '';
$cat_filtro  = $_GET['categoria'] ?? '';

$where  = ['publicado = 1'];
$params = [];

if ($zona_filtro) { $where[] = 'zona = ?';      $params[] = $zona_filtro; }
if ($cat_filtro)  { $where[] = 'categoria = ?'; $params[] = $cat_filtro; }

// Paginación
$por_pagina    = 9;
$pagina_actual = max(1, (int)($_GET['pagina'] ?? 1));

$sql_count  = 'SELECT COUNT(*) FROM articulos WHERE ' . implode(' AND ', $where);
$stmt_count = $pdo->prepare($sql_count);
$stmt_count->execute($params);
$total_items = (int)$stmt_count->fetchColumn();
$total_pags  = max(1, (int)ceil($total_items / $por_pagina));
$pagina_actual = min($pagina_actual, $total_pags);
$offset      = ($pagina_actual - 1) * $por_pagina;

$sql  = 'SELECT id, titulo, slug, extracto, imagen, zona, categoria, fecha FROM articulos';
$sql .= ' WHERE ' . implode(' AND ', $where);
$sql .= ' ORDER BY fecha DESC';
$sql .= ' LIMIT ' . $por_pagina . ' OFFSET ' . $offset;

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$articulos = $stmt->fetchAll();

$zonas      = $pdo->query('SELECT DISTINCT zona FROM articulos WHERE publicado=1 AND zona != "" ORDER BY zona')->fetchAll(PDO::FETCH_COLUMN);
$categorias = $pdo->query('SELECT DISTINCT categoria FROM articulos WHERE publicado=1 AND categoria != "" ORDER BY categoria')->fetchAll(PDO::FETCH_COLUMN);

// Helper URL paginación (preserva filtros activos)
function noticiaPagUrl(int $pag): string {
  $get = $_GET;
  unset($get['pagina']);
  if ($pag > 1) $get['pagina'] = $pag;
  $qs = http_build_query($get);
  return '/noticias/' . ($qs ? '?' . $qs : '');
}

include '../includes/head.php';
?>

<!-- HERO -->
<section class="hz-dark" style="min-height:340px">
  <div class="hz-dark-bg"></div>
  <div class="hz-dark-glow"></div>
  <div class="hz-dark-con">
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Noticias de fontanería</div>
    <h1>Consejos, guías y<br><span class="hl">preguntas frecuentes.</span></h1>
    <p class="hz-dark-sub">Todo lo que necesitas saber sobre fontanería en el hogar. Resolvemos las dudas más habituales.</p>
  </div>
</section>

<!-- ARTÍCULOS -->
<section style="padding:5rem 0">
  <div style="max-width:1100px;margin:0 auto;padding:0 var(--space-md)">

    <!-- FILTROS -->
    <?php if (!empty($zonas) || !empty($categorias)): ?>
    <div class="blog-filtros">
      <a href="/noticias/" class="filtro-tag <?php echo (!$zona_filtro && !$cat_filtro) ? 'active' : ''; ?>">Todos</a>
      <?php foreach ($categorias as $cat): ?>
        <a href="/noticias/categoria/<?php echo urlencode($cat); ?>" class="filtro-tag <?php echo $cat_filtro === $cat ? 'active' : ''; ?>"><?php echo htmlspecialchars($cat); ?></a>
      <?php endforeach; ?>
      <?php foreach ($zonas as $zona): ?>
        <a href="/noticias/zona/<?php echo urlencode($zona); ?>" class="filtro-tag <?php echo $zona_filtro === $zona ? 'active' : ''; ?>">📍 <?php echo htmlspecialchars($zona); ?></a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (empty($articulos)): ?>
      <div class="blog-empty">
        <p>Todavía no hay artículos publicados. Vuelve pronto.</p>
        <a href="/contacto.php" style="display:inline-flex;align-items:center;gap:8px;background:#1e3a5f;color:#fff;padding:12px 24px;border-radius:8px;font-size:14px;font-weight:700;text-decoration:none;margin-top:1rem">Contactar</a>
      </div>
    <?php else: ?>
      <div class="blog-grid">
        <?php foreach ($articulos as $art): ?>
          <a href="/noticias/<?php echo urlencode($art['slug']); ?>" class="blog-card">
            <?php if ($art['imagen']): ?>
              <?php $img_raw = ltrim($art['imagen'], '/'); if (str_starts_with($img_raw, 'caroltemp/')) $img_raw = substr($img_raw, 10); ?>
              <div class="blog-card-img"><img src="<?php echo htmlspecialchars($base_url . $img_raw); ?>" alt="<?php echo htmlspecialchars($art['titulo']); ?>" loading="lazy"></div>
            <?php else: ?>
              <div class="blog-card-img blog-card-img-placeholder"><span>✍️</span></div>
            <?php endif; ?>
            <div class="blog-card-body">
              <div class="blog-card-meta">
                <?php if ($art['categoria']): ?><span class="blog-cat"><?php echo htmlspecialchars($art['categoria']); ?></span><?php endif; ?>
                <?php if ($art['zona']): ?><span class="blog-zona">📍 <?php echo htmlspecialchars($art['zona']); ?></span><?php endif; ?>
              </div>
              <h2><?php echo htmlspecialchars($art['titulo']); ?></h2>
              <p><?php echo htmlspecialchars($art['extracto']); ?></p>
              <span class="blog-card-link">Leer más →</span>
            </div>
          </a>
        <?php endforeach; ?>
      </div>

      <!-- PAGINACIÓN -->
      <?php if ($total_pags > 1): ?>
      <div class="paginacion">
        <p class="pag-info">
          Mostrando <?php echo $offset + 1; ?>–<?php echo min($offset + $por_pagina, $total_items); ?> de <?php echo $total_items; ?> artículos
        </p>

        <?php if ($pagina_actual > 1): ?>
          <a href="<?php echo noticiaPagUrl($pagina_actual - 1); ?>" class="pag-btn">← Anterior</a>
        <?php else: ?>
          <span class="pag-btn pag-disabled">← Anterior</span>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pags; $i++):
          if ($i === 1 || $i === $total_pags || abs($i - $pagina_actual) <= 2): ?>
            <a href="<?php echo noticiaPagUrl($i); ?>" class="pag-num <?php echo $i === $pagina_actual ? 'active' : ''; ?>"><?php echo $i; ?></a>
          <?php elseif (abs($i - $pagina_actual) === 3): ?>
            <span class="pag-ellipsis">…</span>
          <?php endif;
        endfor; ?>

        <?php if ($pagina_actual < $total_pags): ?>
          <a href="<?php echo noticiaPagUrl($pagina_actual + 1); ?>" class="pag-btn">Siguiente →</a>
        <?php else: ?>
          <span class="pag-btn pag-disabled">Siguiente →</span>
        <?php endif; ?>
      </div>
      <?php endif; ?>

    <?php endif; ?>

  </div>
</section>

<!-- CTA -->
<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>¿Tienes una <span>duda concreta?</span></h2>
    <p>Llámanos y te resolvemos cualquier pregunta.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">📞 Llamar ahora</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">💬 WhatsApp</a>
    </div>
    <div class="cta-dark-tel">Teléfono directo<strong>611 165 129</strong></div>
  </div>
</section>

<?php
include '../includes/resenas-section.php';
?>
<?php include '../includes/galeria-section.php'; ?>
<?php include '../includes/footer.php'; ?>
