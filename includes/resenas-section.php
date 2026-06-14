<?php
/**
 * Sección de reseñas de clientes — include reutilizable
 *
 * Variables esperadas (opcionales, establecer antes de incluir):
 *   $ciudad   — string, ej. 'elda'
 *   $servicio — string, ej. 'fugas'
 */

if (!isset($pdo)) return;

$_rc = isset($ciudad)   ? $ciudad   : null;
$_rs = isset($servicio) ? $servicio : null;

// Buscar reseñas con ciudad + servicio
$_resenas = [];
try {
  if ($_rc && $_rs) {
    $_st = $pdo->prepare("SELECT * FROM resenas WHERE visible=1 AND (ciudad=? OR ciudad IS NULL) AND (servicio=? OR servicio IS NULL) ORDER BY RAND() LIMIT 5");
    $_st->execute([$_rc, $_rs]);
  } elseif ($_rc) {
    $_st = $pdo->prepare("SELECT * FROM resenas WHERE visible=1 AND (ciudad=? OR ciudad IS NULL) ORDER BY RAND() LIMIT 5");
    $_st->execute([$_rc]);
  } elseif ($_rs) {
    $_st = $pdo->prepare("SELECT * FROM resenas WHERE visible=1 AND (servicio=? OR servicio IS NULL) ORDER BY RAND() LIMIT 5");
    $_st->execute([$_rs]);
  } else {
    $_st = $pdo->query("SELECT * FROM resenas WHERE visible=1 ORDER BY RAND() LIMIT 5");
  }
  $_resenas = $_st ? $_st->fetchAll(PDO::FETCH_ASSOC) : [];
} catch (\Throwable $_e) {}

// Fallback: cualquier reseña visible
if (empty($_resenas)) {
  try {
    $_st2 = $pdo->query("SELECT * FROM resenas WHERE visible=1 ORDER BY RAND() LIMIT 5");
    $_resenas = $_st2 ? $_st2->fetchAll(PDO::FETCH_ASSOC) : [];
  } catch (\Throwable $_e) {}
}

// Si aún vacío, no renderizar nada
if (empty($_resenas)) return;

// Leer URL de Google Reviews
$_google_rev_url = '';
try {
  $_cfg = $pdo->query("SELECT google_reviews_url FROM resenas_config WHERE id=1 LIMIT 1")->fetch(PDO::FETCH_ASSOC);
  $_google_rev_url = $_cfg['google_reviews_url'] ?? '';
} catch (\Throwable $_e) {}

function _resenas_stars(int $n): string {
  $out = '';
  for ($i = 1; $i <= 5; $i++) {
    $out .= '<span class="rs-star ' . ($i <= $n ? 'rs-star-on' : 'rs-star-off') . '">★</span>';
  }
  return $out;
}

function _resenas_initials(string $nombre): string {
  $words = array_filter(explode(' ', trim($nombre)));
  $initials = '';
  foreach (array_slice($words, 0, 2) as $w) {
    $initials .= mb_strtoupper(mb_substr($w, 0, 1));
  }
  return $initials ?: '?';
}

// Paleta de colores para avatares (estable por nombre)
$_avatar_colors = ['#3b5bdb','#2f9e44','#e67700','#c2255c','#0c8599','#6741d9','#d63939','#0d6efd'];
function _resenas_avatar_color(string $nombre): string {
  global $_avatar_colors;
  return $_avatar_colors[abs(crc32($nombre)) % count($_avatar_colors)];
}
?>

<section class="rs-section">
  <style>
    .rs-section {
      padding: 4rem 1.5rem;
      background: #f8fafc;
    }
    .rs-con {
      max-width: 1120px;
      margin: 0 auto;
    }
    .rs-label {
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .1em;
      color: #3b5bdb;
      margin-bottom: .5rem;
    }
    .rs-title {
      font-size: clamp(1.5rem, 3vw, 2rem);
      font-weight: 800;
      color: #0f172a;
      margin: 0 0 .4rem;
    }
    .rs-subtitle {
      font-size: 15px;
      color: #64748b;
      margin: 0 0 2.5rem;
    }
    .rs-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 1.25rem;
    }
    @media (max-width: 640px) {
      .rs-grid {
        display: flex;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        gap: 1rem;
        padding-bottom: .75rem;
        -webkit-overflow-scrolling: touch;
      }
      .rs-card { min-width: 270px; scroll-snap-align: start; }
    }
    .rs-card {
      background: #fff;
      border: 1.5px solid #e2e8f0;
      border-radius: 14px;
      padding: 1.4rem;
      cursor: pointer;
      text-decoration: none;
      display: flex;
      flex-direction: column;
      gap: .75rem;
      transition: box-shadow .2s, border-color .2s, transform .18s;
      color: inherit;
    }
    .rs-card:hover {
      box-shadow: 0 8px 32px rgba(0,0,0,.10);
      border-color: #c7d2fe;
      transform: translateY(-2px);
    }
    .rs-stars { display: flex; gap: 1px; line-height: 1; }
    .rs-star { font-size: 1rem; }
    .rs-star-on  { color: #f59e0b; }
    .rs-star-off { color: #e2e8f0; }
    .rs-text {
      font-size: 14px;
      line-height: 1.65;
      color: #374151;
      flex: 1;
    }
    .rs-author {
      display: flex;
      align-items: center;
      gap: .7rem;
      margin-top: auto;
    }
    .rs-avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 13px;
      font-weight: 800;
      color: #fff;
      flex-shrink: 0;
      overflow: hidden;
    }
    .rs-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .rs-name { font-size: 13px; font-weight: 700; color: #0f172a; }
    .rs-source {
      font-size: 11px;
      color: #94a3b8;
      display: flex;
      align-items: center;
      gap: 3px;
      margin-top: 1px;
    }
    .rs-g-logo {
      width: 12px;
      height: 12px;
    }
    .rs-google-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      margin-top: auto;
    }
  </style>

  <div class="rs-con">
    <p class="rs-label">Reseñas de clientes</p>
    <h2 class="rs-title">Lo que dicen nuestros clientes</h2>
    <p class="rs-subtitle">Reseñas verificadas de clientes reales</p>

    <div class="rs-grid">
      <?php foreach ($_resenas as $_r):
        $_is_google = ($_r['fuente'] === 'google');
        $_link = $_is_google ? ($_r['google_autor_url'] ?? $_google_rev_url) : $_google_rev_url;
        $_link = $_link ?: '#';
        $_texto = mb_strlen($_r['texto']) > 180 ? mb_substr($_r['texto'], 0, 177) . '…' : $_r['texto'];
        $_initials = _resenas_initials($_r['nombre']);
        $_color    = _resenas_avatar_color($_r['nombre']);
      ?>
      <a class="rs-card" href="<?php echo htmlspecialchars($_link); ?>" <?php echo $_link !== '#' ? 'target="_blank" rel="noopener"' : ''; ?> title="Ver reseña de <?php echo htmlspecialchars($_r['nombre']); ?>">

        <div class="rs-stars"><?php echo _resenas_stars((int)$_r['estrellas']); ?></div>

        <p class="rs-text"><?php echo htmlspecialchars($_texto); ?></p>

        <div class="rs-author">
          <div class="rs-avatar" style="background:<?php echo $_color; ?>">
            <?php if (!empty($_r['foto_autor']) && $_is_google): ?>
              <img src="<?php echo htmlspecialchars($_r['foto_autor']); ?>" alt="<?php echo htmlspecialchars($_r['nombre']); ?>" loading="lazy" onerror="this.parentElement.innerHTML='<?php echo htmlspecialchars($_initials); ?>'">
            <?php else: ?>
              <?php echo htmlspecialchars($_initials); ?>
            <?php endif; ?>
          </div>
          <div>
            <div class="rs-name"><?php echo htmlspecialchars($_r['nombre']); ?></div>
            <div class="rs-source">
              <?php if ($_is_google): ?>
                <svg class="rs-g-logo" viewBox="0 0 48 48">
                  <path fill="#EA4335" d="M24 9.5c3.5 0 6.6 1.2 9.1 3.2l6.8-6.8C35.8 2.1 30.3 0 24 0 14.6 0 6.6 5.4 2.6 13.3l7.9 6.1C12.4 13.1 17.7 9.5 24 9.5z"/>
                  <path fill="#4285F4" d="M46.5 24.5c0-1.6-.1-3.1-.4-4.5H24v8.5h12.7c-.6 3-2.3 5.5-4.9 7.2l7.6 5.9c4.5-4.1 7.1-10.2 7.1-17.1z"/>
                  <path fill="#FBBC05" d="M10.5 28.6A14.8 14.8 0 0 1 9.5 24c0-1.6.3-3.1.7-4.6l-7.9-6.1A24 24 0 0 0 0 24c0 3.8.9 7.4 2.5 10.6l8-6z"/>
                  <path fill="#34A853" d="M24 48c6.5 0 11.9-2.1 15.9-5.8l-7.6-5.9c-2.2 1.5-5 2.3-8.3 2.3-6.3 0-11.6-3.6-13.5-9.1l-8 6C6.6 42.6 14.6 48 24 48z"/>
                </svg>
                Google
              <?php else: ?>
                ✓ Verificado
              <?php endif; ?>
            </div>
          </div>
        </div>

      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
