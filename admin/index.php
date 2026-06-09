<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php'); exit;
}
require_once '../includes/db.php';

$hora = (int)date('H');
$saludo = $hora < 13 ? 'Buenos días' : ($hora < 20 ? 'Buenas tardes' : 'Buenas noches');

// ── KPIs principales ──────────────────────────────────────────────────────────
function qs($pdo, $sql, $default = 0) {
  try { return $pdo->query($sql)->fetchColumn() ?? $default; }
  catch (PDOException $e) { return $default; }
}
function qa($pdo, $sql) {
  try { return $pdo->query($sql)->fetchAll() ?: []; }
  catch (PDOException $e) { return []; }
}

$total_arts   = qs($pdo, 'SELECT COUNT(*) FROM articulos');
$pub_arts     = qs($pdo, 'SELECT COUNT(*) FROM articulos WHERE publicado = 1');
$total_pros   = qs($pdo, 'SELECT COUNT(*) FROM proyectos');
$pub_pros     = qs($pdo, 'SELECT COUNT(*) FROM proyectos WHERE publicado = 1');
$total_pags   = qs($pdo, 'SELECT COUNT(*) FROM paginas');
$pub_pags     = qs($pdo, 'SELECT COUNT(*) FROM paginas WHERE publicado = 1');
$total_medios = qs($pdo, 'SELECT COUNT(*) FROM medios');
$msg_nuevos   = qs($pdo, 'SELECT COUNT(*) FROM mensajes WHERE leido = 0');
$msg_total    = qs($pdo, 'SELECT COUNT(*) FROM mensajes');
$redirs       = qs($pdo, 'SELECT COUNT(*) FROM redirecciones');

// Sitemap
$sitemap_path  = dirname(__DIR__) . '/sitemap.xml';
$sitemap_ok    = file_exists($sitemap_path);
$sitemap_fecha = $sitemap_ok ? date('d/m/Y H:i', filemtime($sitemap_path)) : null;
$sitemap_urls  = 0;
if ($sitemap_ok) {
  $sitemap_urls = substr_count(file_get_contents($sitemap_path), '<url>');
}

// ── Contenido reciente ────────────────────────────────────────────────────────
$ultimos_arts  = qa($pdo, 'SELECT id, titulo, slug, publicado, fecha FROM articulos ORDER BY fecha DESC LIMIT 6');
$ultimos_pros  = qa($pdo, 'SELECT id, titulo, slug, publicado, fecha FROM proyectos ORDER BY fecha DESC LIMIT 6');
$ultimas_pags  = qa($pdo, 'SELECT id, titulo, filepath, publicado FROM paginas ORDER BY id DESC LIMIT 5');
$ultimos_msgs  = qa($pdo, 'SELECT id, nombre, email, asunto, leido, fecha FROM mensajes ORDER BY fecha DESC LIMIT 5');

// ── Salud del contenido ───────────────────────────────────────────────────────
$arts_sin_meta = qs($pdo, "SELECT COUNT(*) FROM articulos WHERE publicado=1 AND (meta_desc='' OR meta_desc IS NULL)");
$arts_sin_img  = qs($pdo, "SELECT COUNT(*) FROM articulos WHERE publicado=1 AND (imagen='' OR imagen IS NULL)");
$pros_sin_img  = qs($pdo, "SELECT COUNT(*) FROM proyectos WHERE publicado=1 AND (imagen='' OR imagen IS NULL)");
$pags_sin_meta = qs($pdo, "SELECT COUNT(*) FROM paginas WHERE publicado=1 AND (meta_desc='' OR meta_desc IS NULL)");

$alertas = [];
if ($msg_nuevos > 0)    $alertas[] = ['tipo'=>'warn',  'texto'=>$msg_nuevos . ' mensaje' . ($msg_nuevos>1?'s':'') . ' sin leer', 'url'=>'mensajes.php'];
if ($arts_sin_meta > 0) $alertas[] = ['tipo'=>'info',  'texto'=>$arts_sin_meta . ' artículo' . ($arts_sin_meta>1?'s':'') . ' sin meta descripción', 'url'=>'articulos.php'];
if ($arts_sin_img > 0)  $alertas[] = ['tipo'=>'info',  'texto'=>$arts_sin_img . ' artículo' . ($arts_sin_img>1?'s':'') . ' sin imagen destacada', 'url'=>'articulos.php'];
if ($pros_sin_img > 0)  $alertas[] = ['tipo'=>'info',  'texto'=>$pros_sin_img . ' proyecto' . ($pros_sin_img>1?'s':'') . ' sin imagen destacada', 'url'=>'proyectos.php'];
if ($pags_sin_meta > 0) $alertas[] = ['tipo'=>'info',  'texto'=>$pags_sin_meta . ' página' . ($pags_sin_meta>1?'s':'') . ' sin meta descripción', 'url'=>'paginas.php'];
if (!$sitemap_ok)       $alertas[] = ['tipo'=>'warn',  'texto'=>'Sitemap no generado todavía', 'url'=>'sitemap.php'];

$pagina_actual = 'index.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Admin — CarolTemp</title>
  <meta name="robots" content="noindex, nofollow">
  <?php include '../includes/admin_style.php'; ?>
  <style>
    /* ── KPI grid ── */
    .kpi-grid {
      display: grid;
      grid-template-columns: repeat(6, 1fr);
      gap: .875rem;
      margin-bottom: 1.75rem;
    }
    .kpi {
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      padding: 1.1rem 1.25rem 1rem;
      display: flex;
      flex-direction: column;
      gap: 4px;
      position: relative;
      overflow: hidden;
      transition: box-shadow .15s;
    }
    .kpi:hover { box-shadow: 0 4px 16px rgba(0,0,0,.07); }
    .kpi-icon {
      font-size: 22px;
      line-height: 1;
      margin-bottom: 6px;
    }
    .kpi-val {
      font-size: 28px;
      font-weight: 800;
      color: #0f172a;
      line-height: 1;
      letter-spacing: -.02em;
    }
    .kpi-label {
      font-size: 11.5px;
      font-weight: 600;
      color: #64748b;
      text-transform: uppercase;
      letter-spacing: .06em;
    }
    .kpi-sub {
      font-size: 11.5px;
      color: #94a3b8;
      margin-top: 2px;
    }
    .kpi-accent { position:absolute; top:0; left:0; width:3px; height:100%; border-radius:12px 0 0 12px; }
    .kpi-blue .kpi-accent   { background: #3b5bdb; }
    .kpi-green .kpi-accent  { background: #16a34a; }
    .kpi-purple .kpi-accent { background: #7c3aed; }
    .kpi-orange .kpi-accent { background: #ea580c; }
    .kpi-pink .kpi-accent   { background: #db2777; }
    .kpi-teal .kpi-accent   { background: #0891b2; }
    .kpi-badge {
      position: absolute; top: .75rem; right: .75rem;
      background: #dc2626; color: #fff;
      font-size: 10px; font-weight: 700;
      padding: 2px 6px; border-radius: 20px;
    }

    /* ── Acciones rápidas ── */
    .quick-actions {
      display: flex; gap: .625rem; flex-wrap: wrap;
      margin-bottom: 1.75rem;
    }
    .qa-btn {
      display: inline-flex; align-items: center; gap: 6px;
      background: #fff; border: 1.5px solid #e2e8f0;
      border-radius: 8px; padding: .55rem 1rem;
      font-size: 13px; font-weight: 600; color: #374151;
      text-decoration: none; transition: all .15s;
      white-space: nowrap;
    }
    .qa-btn:hover { border-color: #3b5bdb; color: #3b5bdb; background: #f5f7ff; }
    .qa-btn span { font-size: 15px; }

    /* ── Alertas ── */
    .alertas { display: flex; flex-direction: column; gap: .5rem; margin-bottom: 1.75rem; }
    .alerta {
      display: flex; align-items: center; gap: .75rem;
      padding: .7rem 1rem; border-radius: 8px;
      font-size: 13px; text-decoration: none;
      transition: opacity .15s;
    }
    .alerta:hover { opacity: .8; }
    .alerta.warn { background: #fef9c3; color: #854d0e; border: 1px solid #fde68a; }
    .alerta.info { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .alerta-ico { font-size: 15px; flex-shrink: 0; }
    .alerta-txt { flex: 1; font-weight: 500; }
    .alerta-arrow { font-size: 13px; opacity: .6; }

    /* ── Layout principal ── */
    .dash-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem; }
    .dash-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem; }
    .dash-wide { grid-column: 1 / -1; }

    /* ── Filas de tabla compactas ── */
    .dash-table td { padding: .7rem 1rem; font-size: 13px; }
    .dash-table th { padding: .5rem 1rem; }

    /* ── Barra de progreso ── */
    .prog-wrap { margin-top: 4px; }
    .prog-bar { height: 4px; background: #f1f5f9; border-radius: 2px; overflow: hidden; }
    .prog-fill { height: 100%; border-radius: 2px; background: #3b5bdb; transition: width .4s; }

    /* ── Sitemap card ── */
    .sitemap-status { display: flex; align-items: center; gap: .75rem; padding: 1rem 1.25rem; background: #f8fafc; border-radius: 8px; }
    .sitemap-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
    .sitemap-dot.ok   { background: #16a34a; box-shadow: 0 0 0 3px #dcfce7; }
    .sitemap-dot.bad  { background: #dc2626; box-shadow: 0 0 0 3px #fecdd3; }

    /* ── Section label ── */
    .section-label {
      font-size: 11px; font-weight: 700; color: #94a3b8;
      text-transform: uppercase; letter-spacing: .1em;
      margin-bottom: .75rem; margin-top: .25rem;
    }
  </style>
</head>
<body>

<?php include '../includes/admin_sidebar.php'; ?>

<main class="main">

  <!-- TOPBAR -->
  <div class="topbar" style="margin-bottom:1.5rem">
    <div>
      <h1><?php echo $saludo ?>, <strong><?php echo htmlspecialchars($_SESSION['admin_usuario']); ?></strong></h1>
      <div style="color:#64748b;font-size:13px;margin-top:3px"><?php echo date('l, j \d\e F \d\e Y'); ?></div>
    </div>
    <a href="<?php echo $is_local ? 'http://localhost/caroltemp/' : 'https://caroltemp.com/'; ?>" target="_blank" class="btn-new">
      <span>🌐</span> Ver web
    </a>
  </div>

  <!-- ALERTAS -->
  <?php if (!empty($alertas)): ?>
  <div class="alertas">
    <?php foreach ($alertas as $al): ?>
    <a href="<?php echo $al['url']; ?>" class="alerta <?php echo $al['tipo']; ?>">
      <span class="alerta-ico"><?php echo $al['tipo']==='warn' ? '⚠️' : 'ℹ️'; ?></span>
      <span class="alerta-txt"><?php echo $al['texto']; ?></span>
      <span class="alerta-arrow">→</span>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <!-- KPIs -->
  <div class="section-label">Resumen</div>
  <div class="kpi-grid">

    <div class="kpi kpi-blue">
      <div class="kpi-accent"></div>
      <div class="kpi-icon">📝</div>
      <div class="kpi-val"><?php echo $total_arts; ?></div>
      <div class="kpi-label">Artículos</div>
      <div class="kpi-sub"><?php echo $pub_arts; ?> pub · <?php echo $total_arts - $pub_arts; ?> borrador</div>
    </div>

    <div class="kpi kpi-green">
      <div class="kpi-accent"></div>
      <div class="kpi-icon">🔧</div>
      <div class="kpi-val"><?php echo $total_pros; ?></div>
      <div class="kpi-label">Proyectos</div>
      <div class="kpi-sub"><?php echo $pub_pros; ?> pub · <?php echo $total_pros - $pub_pros; ?> borrador</div>
    </div>

    <div class="kpi kpi-purple">
      <div class="kpi-accent"></div>
      <div class="kpi-icon">📄</div>
      <div class="kpi-val"><?php echo $total_pags; ?></div>
      <div class="kpi-label">Páginas</div>
      <div class="kpi-sub"><?php echo $pub_pags; ?> pub · <?php echo $total_pags - $pub_pags; ?> borrador</div>
    </div>

    <div class="kpi kpi-orange" style="cursor:pointer" onclick="location.href='mensajes.php'">
      <div class="kpi-accent"></div>
      <?php if ($msg_nuevos > 0): ?>
        <div class="kpi-badge"><?php echo $msg_nuevos; ?></div>
      <?php endif; ?>
      <div class="kpi-icon">✉️</div>
      <div class="kpi-val"><?php echo $msg_total; ?></div>
      <div class="kpi-label">Mensajes</div>
      <div class="kpi-sub"><?php echo $msg_nuevos > 0 ? $msg_nuevos . ' sin leer' : 'Todos leídos'; ?></div>
    </div>

    <div class="kpi kpi-pink">
      <div class="kpi-accent"></div>
      <div class="kpi-icon">🖼️</div>
      <div class="kpi-val"><?php echo $total_medios; ?></div>
      <div class="kpi-label">Imágenes</div>
      <div class="kpi-sub">Biblioteca de medios</div>
    </div>

    <div class="kpi kpi-teal" style="cursor:pointer" onclick="location.href='sitemap.php'">
      <div class="kpi-accent"></div>
      <div class="kpi-icon">🗺️</div>
      <div class="kpi-val"><?php echo $sitemap_urls; ?></div>
      <div class="kpi-label">URLs sitemap</div>
      <div class="kpi-sub"><?php echo $sitemap_ok ? 'Generado '.$sitemap_fecha : 'Sin generar'; ?></div>
    </div>

  </div>

  <!-- ACCIONES RÁPIDAS -->
  <div class="section-label">Acciones rápidas</div>
  <div class="quick-actions" style="margin-bottom:1.75rem">
    <a href="nuevo-articulo.php" class="qa-btn"><span>✏️</span> Nuevo artículo</a>
    <a href="nuevo-proyecto.php" class="qa-btn"><span>🔧</span> Nuevo proyecto</a>
    <a href="nueva-pagina.php"   class="qa-btn"><span>📄</span> Nueva página</a>
    <a href="medios.php"         class="qa-btn"><span>🖼️</span> Subir imagen</a>
    <a href="mensajes.php"       class="qa-btn <?php echo $msg_nuevos>0?'style="border-color:#f97316;color:#ea580c"':''; ?>">
      <span>✉️</span> Mensajes<?php echo $msg_nuevos > 0 ? ' <span style="background:#ea580c;color:#fff;font-size:10px;padding:1px 6px;border-radius:20px;font-weight:700">'.$msg_nuevos.'</span>' : ''; ?>
    </a>
    <a href="sitemap.php"        class="qa-btn"><span>🗺️</span> Sitemap</a>
    <a href="categorias.php"     class="qa-btn"><span>🏷️</span> Categorías</a>
  </div>

  <!-- ÚLTIMOS ARTÍCULOS + PROYECTOS -->
  <div class="dash-row">

    <div class="card">
      <div class="card-header">
        <h2>Últimos artículos</h2>
        <a href="nuevo-articulo.php" class="btn-new">+ Nuevo</a>
      </div>
      <table class="dash-table">
        <thead><tr><th>Título</th><th>Estado</th><th></th></tr></thead>
        <tbody>
          <?php if (empty($ultimos_arts)): ?>
            <tr class="empty-row"><td colspan="3">No hay artículos todavía</td></tr>
          <?php else: foreach ($ultimos_arts as $a): ?>
            <tr>
              <td class="td-titulo">
                <?php echo htmlspecialchars($a['titulo']); ?>
                <small><?php echo date('d/m/Y', strtotime($a['fecha'])); ?></small>
              </td>
              <td><span class="badge-pub <?php echo $a['publicado']?'si':'no'; ?>"><?php echo $a['publicado']?'✓ Pub':'Borrador'; ?></span></td>
              <td><div class="td-acciones"><a href="nuevo-articulo.php?id=<?php echo $a['id']; ?>" class="btn-editar">Editar</a></div></td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
      <div style="padding:.6rem 1rem;border-top:1px solid #f1f5f9">
        <a href="articulos.php" style="color:#3b5bdb;font-size:12.5px;font-weight:600">Ver todos (<?php echo $total_arts; ?>) →</a>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h2>Últimos proyectos</h2>
        <a href="nuevo-proyecto.php" class="btn-new">+ Nuevo</a>
      </div>
      <table class="dash-table">
        <thead><tr><th>Título</th><th>Estado</th><th></th></tr></thead>
        <tbody>
          <?php if (empty($ultimos_pros)): ?>
            <tr class="empty-row"><td colspan="3">No hay proyectos todavía</td></tr>
          <?php else: foreach ($ultimos_pros as $p): ?>
            <tr>
              <td class="td-titulo">
                <?php echo htmlspecialchars($p['titulo']); ?>
                <small><?php echo date('d/m/Y', strtotime($p['fecha'])); ?></small>
              </td>
              <td><span class="badge-pub <?php echo $p['publicado']?'si':'no'; ?>"><?php echo $p['publicado']?'✓ Pub':'Borrador'; ?></span></td>
              <td><div class="td-acciones"><a href="nuevo-proyecto.php?id=<?php echo $p['id']; ?>" class="btn-editar">Editar</a></div></td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
      <div style="padding:.6rem 1rem;border-top:1px solid #f1f5f9">
        <a href="proyectos.php" style="color:#3b5bdb;font-size:12.5px;font-weight:600">Ver todos (<?php echo $total_pros; ?>) →</a>
      </div>
    </div>

  </div>

  <!-- PÁGINAS + MENSAJES + ESTADO SEO -->
  <div class="dash-row-3">

    <!-- Últimas páginas -->
    <div class="card">
      <div class="card-header">
        <h2>Páginas recientes</h2>
        <a href="nueva-pagina.php" class="btn-new">+ Nueva</a>
      </div>
      <table class="dash-table">
        <thead><tr><th>Página</th><th></th></tr></thead>
        <tbody>
          <?php if (empty($ultimas_pags)): ?>
            <tr class="empty-row"><td colspan="2">Sin páginas</td></tr>
          <?php else: foreach ($ultimas_pags as $p): ?>
            <tr>
              <td class="td-titulo">
                <?php echo htmlspecialchars($p['titulo']); ?>
                <small><?php echo $p['filepath']; ?></small>
              </td>
              <td><div class="td-acciones"><a href="nueva-pagina.php?id=<?php echo $p['id']; ?>" class="btn-editar">Editar</a></div></td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
      <div style="padding:.6rem 1rem;border-top:1px solid #f1f5f9">
        <a href="paginas.php" style="color:#3b5bdb;font-size:12.5px;font-weight:600">Ver todas (<?php echo $total_pags; ?>) →</a>
      </div>
    </div>

    <!-- Últimos mensajes -->
    <div class="card">
      <div class="card-header">
        <h2>Mensajes recientes</h2>
        <?php if ($msg_nuevos > 0): ?>
          <span style="background:#fef9c3;color:#854d0e;font-size:12px;font-weight:600;padding:4px 10px;border-radius:20px"><?php echo $msg_nuevos; ?> nuevo<?php echo $msg_nuevos>1?'s':''; ?></span>
        <?php endif; ?>
      </div>
      <?php if (empty($ultimos_msgs)): ?>
        <div class="card-body"><p style="color:#94a3b8;font-size:14px;text-align:center;padding:1.5rem 0">No hay mensajes todavía</p></div>
      <?php else: ?>
        <table class="dash-table">
          <tbody>
            <?php foreach ($ultimos_msgs as $m): ?>
            <tr style="<?php echo !$m['leido'] ? 'background:#fffbeb' : ''; ?>">
              <td>
                <div style="font-size:13px;font-weight:<?php echo !$m['leido']?'700':'500'; ?>;color:#0f172a">
                  <?php echo htmlspecialchars($m['nombre']); ?>
                  <?php if (!$m['leido']): ?><span style="background:#f97316;color:#fff;font-size:9px;font-weight:700;padding:1px 5px;border-radius:10px;margin-left:4px">NEW</span><?php endif; ?>
                </div>
                <div style="font-size:11.5px;color:#94a3b8"><?php echo htmlspecialchars(mb_strimwidth($m['asunto'] ?? '', 0, 40, '…')); ?></div>
              </td>
              <td style="white-space:nowrap">
                <a href="mensajes.php" class="btn-editar" style="font-size:11.5px;padding:4px 10px">Ver</a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <div style="padding:.6rem 1rem;border-top:1px solid #f1f5f9">
          <a href="mensajes.php" style="color:#3b5bdb;font-size:12.5px;font-weight:600">Ver todos (<?php echo $msg_total; ?>) →</a>
        </div>
      <?php endif; ?>
    </div>

    <!-- Estado SEO / Contenido -->
    <div class="card">
      <div class="card-header"><h2>Estado del contenido</h2></div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:1.1rem">

        <?php
        $checks = [
          ['label'=>'Artículos con imagen',   'ok'=>$total_arts-$arts_sin_img,   'total'=>$total_arts,   'color'=>'#3b5bdb'],
          ['label'=>'Artículos con meta',     'ok'=>$pub_arts-$arts_sin_meta,    'total'=>$pub_arts,     'color'=>'#7c3aed'],
          ['label'=>'Proyectos con imagen',   'ok'=>$total_pros-$pros_sin_img,   'total'=>$total_pros,   'color'=>'#16a34a'],
          ['label'=>'Páginas con meta',       'ok'=>$pub_pags-$pags_sin_meta,    'total'=>$pub_pags,     'color'=>'#0891b2'],
        ];
        foreach ($checks as $c):
          $pct = $c['total'] > 0 ? round($c['ok']/$c['total']*100) : 100;
          $color = $pct >= 80 ? $c['color'] : ($pct >= 50 ? '#f59e0b' : '#dc2626');
        ?>
        <div>
          <div style="display:flex;justify-content:space-between;font-size:12.5px;margin-bottom:5px">
            <span style="color:#374151;font-weight:500"><?php echo $c['label']; ?></span>
            <span style="color:#64748b"><?php echo $c['ok']; ?>/<?php echo $c['total']; ?> <strong style="color:<?php echo $color; ?>">(<?php echo $pct; ?>%)</strong></span>
          </div>
          <div class="prog-bar"><div class="prog-fill" style="width:<?php echo $pct; ?>%;background:<?php echo $color; ?>"></div></div>
        </div>
        <?php endforeach; ?>

        <div style="border-top:1px solid #f1f5f9;padding-top:1rem;display:flex;align-items:center;gap:.75rem">
          <div class="sitemap-dot <?php echo $sitemap_ok?'ok':'bad'; ?>"></div>
          <div>
            <div style="font-size:13px;font-weight:600;color:#0f172a">Sitemap <?php echo $sitemap_ok?'generado':'no generado'; ?></div>
            <div style="font-size:11.5px;color:#94a3b8"><?php echo $sitemap_ok ? $sitemap_urls.' URLs · '.$sitemap_fecha : 'Ve a Sitemap para generarlo'; ?></div>
          </div>
          <?php if (!$sitemap_ok): ?>
            <a href="sitemap.php" style="margin-left:auto;font-size:12px;color:#3b5bdb;font-weight:600">Generar →</a>
          <?php endif; ?>
        </div>

      </div>
    </div>

  </div>

</main>
</body>
</html>
