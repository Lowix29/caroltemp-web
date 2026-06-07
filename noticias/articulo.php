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
<section class="hz-dark" style="min-height:520px">
  <?php if ($art['imagen']):
    // Normalizar ruta: quitar prefijo /caroltemp/ si existe, luego quitar barra inicial
    $img_raw  = ltrim($art['imagen'], '/');
    if (str_starts_with($img_raw, 'caroltemp/')) $img_raw = substr($img_raw, 10);
    $img_hero = $base_url . $img_raw;
  ?>
    <img src="<?php echo htmlspecialchars($img_hero); ?>" alt="<?php echo htmlspecialchars($art['titulo']); ?>" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;z-index:0">
    <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(3,12,26,.82) 0%,rgba(5,15,30,.55) 55%,rgba(5,15,30,.18) 100%);z-index:1"></div>
  <?php else: ?>
    <div class="hz-dark-bg"></div>
  <?php endif; ?>
  <div class="hz-dark-con" style="position:relative;z-index:2;align-self:flex-end;width:100%;padding-bottom:3rem">
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
    <p style="color:#cbd5e1;font-size:15px;line-height:1.75;max-width:580px;margin-top:.75rem"><?php echo htmlspecialchars($art['extracto']); ?></p>
  </div>
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
          <a href="tel:+34611165129" style="display:block;background:#1e3a5f;color:#fff;padding:12px;border-radius:8px;font-size:14.5px;font-weight:700;text-decoration:none;text-align:center;margin-top:1rem">📞 611 165 129</a>
          <a href="https://wa.me/34611165129" target="_blank" rel="noopener" style="display:block;background:#f8fafc;color:#1e3a5f;padding:12px;border-radius:8px;font-size:14.5px;font-weight:600;text-decoration:none;text-align:center;margin-top:.75rem;border:1.5px solid #e2e8f0">💬 WhatsApp</a>
        </div>

        <!-- ZONA -->
        <?php if ($art['zona']):
          $cts_slugs = ['Elda'=>'elda','Petrer'=>'petrer','Novelda'=>'novelda','Monóvar'=>'monovar','Sax'=>'sax','Pinoso'=>'pinoso','Monforte del Cid'=>'monforte-del-cid','Salinas'=>'salinas'];
          $cts_slug  = $cts_slugs[$art['zona']] ?? strtolower(str_replace([' ','ó','é','á','í','ú'],['-','o','e','a','i','u'],$art['zona']));
          $zona      = $art['zona'];
          $s_tipo    = $art['sidebar_tipo'] ?? '';
          $s_configs = [
            'hub'        => ['titulo'=>"Fontanero en {$zona}",           'desc'=>'La calidad, rapidez y eficacia es nuestra seña de identidad.','url'=>"/fontanero/{$cts_slug}",            'btn'=>"Ver servicios en {$zona} →"],
            'urgencias'  => ['titulo'=>"Urgencias 24h en {$zona}",       'desc'=>'Presupuesto gratuito con la avería vista.',                   'url'=>"/fontanero/{$cts_slug}/urgencias",   'btn'=>"Urgencias en {$zona} →"],
            'desatascos' => ['titulo'=>"Desatascos en {$zona}",          'desc'=>'Soluciones rápidas para atascos y obstrucciones.',            'url'=>"/fontanero/{$cts_slug}/desatascos",  'btn'=>"Desatascos en {$zona} →"],
            'fugas'      => ['titulo'=>"Reparación de fugas en {$zona}", 'desc'=>'Detectamos y reparamos fugas con garantía.',                  'url'=>"/fontanero/{$cts_slug}/fugas",       'btn'=>"Fugas en {$zona} →"],
          ];
          $s_cfg = $s_configs[$s_tipo] ?? ['titulo'=>"Fontanero en {$zona}",'desc'=>'La calidad, rapidez y eficacia es nuestra seña de identidad.','url'=>"/fontanero/{$cts_slug}",'btn'=>"Ver servicios en {$zona} →"];
        ?>
        <div class="sidebar-card" style="margin-top:1rem">
          <h3><?php echo htmlspecialchars($s_cfg['titulo']); ?></h3>
          <p><?php echo htmlspecialchars($s_cfg['desc']); ?></p>
          <a href="<?php echo $s_cfg['url']; ?>" style="display:block;background:#f8fafc;color:#1e3a5f;padding:11px;border-radius:8px;font-size:14px;font-weight:600;text-decoration:none;text-align:center;margin-top:1rem;border:1.5px solid #e2e8f0"><?php echo htmlspecialchars($s_cfg['btn']); ?></a>
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

<!-- OTRAS NOTICIAS DE INTERÉS -->
<?php
$stmt_otras = $pdo->prepare('SELECT id, titulo, slug, extracto, zona, categoria FROM articulos WHERE publicado = 1 AND id != ? ORDER BY fecha DESC LIMIT 3');
$stmt_otras->execute([$art['id']]);
$otras = $stmt_otras->fetchAll();
?>
<?php if (!empty($otras)): ?>
<section style="padding:4rem 0;background:#f8fafc;border-top:1px solid #f1f5f9">
  <div style="max-width:1100px;margin:0 auto;padding:0 var(--space-md)">
    <p class="zona-lbl">Seguir leyendo</p>
    <h2 style="font-size:clamp(1.4rem,2.5vw,1.8rem);font-weight:800;color:#0d1f33;letter-spacing:-.02em;margin-bottom:1.75rem">Otras noticias <span style="color:#3b82f6">de interés</span></h2>
    <div class="blog-grid blog-grid-3">
      <?php foreach ($otras as $otra): ?>
        <a href="/noticias/<?php echo urlencode($otra['slug']); ?>" class="blog-card">
          <div class="blog-card-img blog-card-img-placeholder"><span>✍️</span></div>
          <div class="blog-card-body">
            <div class="blog-card-meta">
              <?php if ($otra['categoria']): ?><span class="blog-cat"><?php echo htmlspecialchars($otra['categoria']); ?></span><?php endif; ?>
              <?php if ($otra['zona']): ?><span class="blog-zona">📍 <?php echo htmlspecialchars($otra['zona']); ?></span><?php endif; ?>
            </div>
            <h2><?php echo htmlspecialchars($otra['titulo']); ?></h2>
            <p><?php echo htmlspecialchars($otra['extracto']); ?></p>
            <span class="blog-card-link">Leer más →</span>
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
      <a href="tel:+34611165129" class="btn-hz-w">📞 Llamar ahora</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">💬 WhatsApp</a>
    </div>
    <div class="cta-dark-tel">Teléfono directo<strong>611 165 129</strong></div>
  </div>
</section>

<?php include '../includes/footer.php'; ?>