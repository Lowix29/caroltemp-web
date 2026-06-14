<?php
/**
 * Galería de trabajos — include SEO
 *
 * Variables esperadas (se pueden definir antes del include):
 *   $ciudad   string|null  — filtra por ciudad  (ej. 'elda')
 *   $servicio string|null  — filtra por servicio (ej. 'fugas')
 *   $base_url string       — URL base absoluta   (ej. 'https://caroltemp.com/')
 *
 * Renderiza NADA si no hay imágenes visibles.
 */

// Garantizar variables
$_gal_ciudad   = isset($ciudad)   && $ciudad   !== '' ? (string)$ciudad   : null;
$_gal_servicio = isset($servicio) && $servicio !== '' ? (string)$servicio : null;
$_gal_base_url = isset($base_url) ? rtrim($base_url, '/') : 'https://caroltemp.com';

// ── QUERY principal: ciudad + servicio ────────────────────────────────────────
try {
  $_gal_stmt = $pdo->prepare(
    'SELECT * FROM galeria
      WHERE visible = 1
        AND (ciudad = ? OR ciudad IS NULL)
        AND (servicio = ? OR servicio IS NULL)
      ORDER BY orden ASC, creado DESC
      LIMIT 12'
  );
  $_gal_stmt->execute([$_gal_ciudad, $_gal_servicio]);
  $_gal_imgs = $_gal_stmt->fetchAll(PDO::FETCH_ASSOC);

  // Fallback: cualquier imagen visible
  if (empty($_gal_imgs)) {
    $_gal_stmt2 = $pdo->prepare('SELECT * FROM galeria WHERE visible = 1 ORDER BY orden ASC, creado DESC LIMIT 12');
    $_gal_stmt2->execute();
    $_gal_imgs = $_gal_stmt2->fetchAll(PDO::FETCH_ASSOC);
  }
} catch (Exception $_gal_e) {
  $_gal_imgs = [];
}

// Renderizar nada si no hay imágenes
if (empty($_gal_imgs)) return;

// ── Título dinámico ───────────────────────────────────────────────────────────
$_gal_titulo_h2 = 'Nuestros trabajos'
  . ($_gal_ciudad ? ' en ' . ucfirst(str_replace('-', ' ', $_gal_ciudad)) : '');

// ── JSON-LD ───────────────────────────────────────────────────────────────────
$_gal_schema_name = 'Trabajos de fontanería'
  . ($_gal_ciudad ? ' en ' . ucfirst(str_replace('-', ' ', $_gal_ciudad)) : '');
$_gal_items = [];
foreach ($_gal_imgs as $_gal_i => $_gal_img) {
  $_gal_ruta = strpos($_gal_img['imagen'], 'img/') === 0 ? $_gal_img['imagen'] : 'img/galeria/' . basename($_gal_img['imagen']);
  $_gal_items[] = [
    '@type'       => 'ImageObject',
    'position'    => $_gal_i + 1,
    'url'         => $_gal_base_url . '/img/galeria/' . rawurlencode(basename($_gal_img['imagen'])),
    'url'         => $_gal_base_url . '/' . rawurlencode($_gal_ruta),
    'name'        => $_gal_img['titulo'],
    'description' => $_gal_img['alt_text'],
    'author'      => ['@type' => 'Organization', 'name' => 'CarolTemp'],
  ];
}
$_gal_jsonld = json_encode([
  '@context'        => 'https://schema.org',
  '@type'           => 'ItemList',
  'name'            => $_gal_schema_name,
  'itemListElement' => $_gal_items,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>
<section class="gal-section">
<style>
.gal-section{margin:3rem 0;padding:0}
.gal-con{max-width:1200px;margin:0 auto;padding:0 1rem}
.gal-label{font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#3b5bdb;margin:0 0 .4rem}
.gal-title{font-size:clamp(1.3rem,3vw,1.9rem);font-weight:800;color:#0f172a;margin:0 0 1.5rem;line-height:1.2}
.gal-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:1rem}
@media(min-width:640px){.gal-grid{grid-template-columns:repeat(3,1fr)}}
@media(min-width:1024px){.gal-grid{grid-template-columns:repeat(4,1fr)}}
.gal-card{display:flex;flex-direction:column;gap:.4rem}
.gal-card-img-wrap{overflow:hidden;border-radius:10px;aspect-ratio:4/3;background:#f1f5f9}
.gal-card-img-wrap img{width:100%;height:100%;object-fit:cover;border-radius:10px;transition:transform .3s ease;display:block}
.gal-card-img-wrap:hover img{transform:scale(1.03)}
.gal-card-cap{font-size:12.5px;color:#374151;line-height:1.35;padding:0 2px}
</style>
  <div class="gal-con">
    <p class="gal-label">Trabajos realizados</p>
    <h2 class="gal-title"><?php echo htmlspecialchars($_gal_titulo_h2); ?></h2>
    <div class="gal-grid">
      <?php foreach ($_gal_imgs as $_gal_img): ?>
      <div class="gal-card">
        <div class="gal-card-img-wrap">
          <img
            src="<?php echo htmlspecialchars($_gal_base_url . '/img/galeria/' . basename($_gal_img['imagen'])); ?>"
            src="<?php $_gal_r = strpos($_gal_img['imagen'],'img/')===0 ? $_gal_img['imagen'] : 'img/galeria/'.basename($_gal_img['imagen']); echo htmlspecialchars($_gal_base_url.'/'.$_gal_r); ?>"
            alt="<?php echo htmlspecialchars($_gal_img['alt_text']); ?>"
            loading="lazy"
            decoding="async"
          >
        </div>
        <?php if ($_gal_img['titulo']): ?>
        <p class="gal-card-cap"><?php echo htmlspecialchars($_gal_img['titulo']); ?></p>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <script type="application/ld+json"><?php echo $_gal_jsonld; ?></script>
</section>
<?php
// Limpiar variables de ámbito para no contaminar la página
unset($_gal_ciudad, $_gal_servicio, $_gal_base_url, $_gal_stmt, $_gal_stmt2,
      $_gal_imgs, $_gal_titulo_h2, $_gal_schema_name, $_gal_items, $_gal_i,
      $_gal_img, $_gal_jsonld, $_gal_e);
?>
