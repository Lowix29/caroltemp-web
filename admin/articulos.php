<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php'); exit;
}
require_once '../includes/db.php';

$mensaje = '';
$error   = '';

// ── ELIMINAR ──────────────────────────────────────────────────────────────────
if (isset($_GET['eliminar']) && is_numeric($_GET['eliminar'])) {
  $pdo->prepare('DELETE FROM articulos WHERE id = ?')->execute([$_GET['eliminar']]);
  $mensaje = '✅ Artículo eliminado correctamente.';
}

// ── BORRADO MASIVO ────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_action']) && $_POST['bulk_action'] === 'delete' && !empty($_POST['ids'])) {
  $ids = array_filter(array_map('intval', (array)$_POST['ids']));
  if ($ids) {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $pdo->prepare("DELETE FROM articulos WHERE id IN ($placeholders)")->execute($ids);
    $mensaje = '✅ ' . count($ids) . ' artículo' . (count($ids) !== 1 ? 's' : '') . ' eliminado' . (count($ids) !== 1 ? 's' : '') . '.';
  }
}

// ── TOGGLE PUBLICADO ─────────────────────────────────────────────────────────
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
  $pdo->prepare('UPDATE articulos SET publicado = NOT publicado WHERE id = ?')->execute([$_GET['toggle']]);
  header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?') . '?' . http_build_query(array_diff_key($_GET, ['toggle' => '']))); exit;
}

// ── FILTROS Y PAGINACIÓN ─────────────────────────────────────────────────────
$q        = trim($_GET['q'] ?? '');
$estado   = $_GET['estado'] ?? 'todos';
$zona_f   = $_GET['zona'] ?? '';
$cat_f    = $_GET['cat'] ?? '';
$por_pag  = 20;
$pag_num  = max(1, (int)($_GET['pag'] ?? 1));

$where  = ['1=1'];
$params = [];
if ($q)      { $where[] = 'titulo LIKE ?'; $params[] = "%$q%"; }
if ($zona_f) { $where[] = 'zona = ?';      $params[] = $zona_f; }
if ($cat_f)  { $where[] = 'categoria = ?'; $params[] = $cat_f; }
if ($estado === 'publicados') { $where[] = 'publicado = 1'; }
if ($estado === 'borradores') { $where[] = 'publicado = 0'; }

$where_sql = implode(' AND ', $where);

$total_todos = (int)$pdo->query('SELECT COUNT(*) FROM articulos')->fetchColumn();
$total_pub   = (int)$pdo->query('SELECT COUNT(*) FROM articulos WHERE publicado=1')->fetchColumn();
$total_bor   = (int)$pdo->query('SELECT COUNT(*) FROM articulos WHERE publicado=0')->fetchColumn();

$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM articulos WHERE $where_sql");
$count_stmt->execute($params);
$total_filtrado = (int)$count_stmt->fetchColumn();
$total_paginas  = max(1, (int)ceil($total_filtrado / $por_pag));
$pag_num        = min($pag_num, $total_paginas);
$offset         = ($pag_num - 1) * $por_pag;

$stmt = $pdo->prepare("SELECT id,titulo,slug,zona,categoria,publicado,fecha FROM articulos WHERE $where_sql ORDER BY fecha DESC LIMIT $por_pag OFFSET $offset");
$stmt->execute($params);
$articulos = $stmt->fetchAll();

$zonas      = $pdo->query('SELECT DISTINCT zona FROM articulos WHERE zona != "" ORDER BY zona')->fetchAll(PDO::FETCH_COLUMN);
$categorias = $pdo->query('SELECT DISTINCT categoria FROM articulos WHERE categoria != "" ORDER BY categoria')->fetchAll(PDO::FETCH_COLUMN);

function build_art_url($extra = []) {
  $base = array_merge([
    'q'      => $_GET['q'] ?? '',
    'estado' => $_GET['estado'] ?? 'todos',
    'zona'   => $_GET['zona'] ?? '',
    'cat'    => $_GET['cat'] ?? '',
    'pag'    => $_GET['pag'] ?? 1,
  ], $extra);
  return 'articulos.php?' . http_build_query(array_filter($base, function($v){ return $v !== '' && $v !== 'todos' && $v !== 1 && $v !== '1'; }));
}

$pagina_actual = 'articulos.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Artículos — CarolTemp Admin</title>
  <meta name="robots" content="noindex,nofollow">
  <?php include '../includes/admin_style.php'; ?>
  <style>
    .wp-tabs { display:flex; gap:0; margin-bottom:1rem; border-bottom:2px solid #e2e8f0; }
    .wp-tab { padding:.5rem 1rem; font-size:13px; color:#64748b; text-decoration:none; border-bottom:2px solid transparent; margin-bottom:-2px; font-weight:500; transition:color .15s; }
    .wp-tab:hover { color:#0f172a; }
    .wp-tab.active { color:#3b5bdb; border-bottom-color:#3b5bdb; font-weight:700; }
    .wp-tab .cnt { color:#94a3b8; font-size:12px; }
    .wp-tab.active .cnt { color:#3b5bdb; }

    .wp-table { width:100%; border-collapse:collapse; }
    .wp-table th { background:#f8fafc; color:#64748b; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; padding:.65rem 1rem; text-align:left; border-bottom:1px solid #e2e8f0; }
    .wp-table th.col-cb { width:32px; }
    .wp-table th.col-zone { width:130px; }
    .wp-table th.col-cat { width:130px; }
    .wp-table th.col-status { width:100px; }
    .wp-table th.col-date { width:110px; }
    .wp-table td { padding:.7rem 1rem; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
    .wp-table tr:last-child td { border-bottom:none; }
    .wp-table tr:hover td { background:#fafbfc; }

    .row-title { font-size:14px; font-weight:700; color:#0f172a; display:block; margin-bottom:3px; text-decoration:none; }
    .row-title:hover { color:#3b5bdb; }
    .row-slug { font-family:monospace; font-size:11.5px; color:#94a3b8; display:block; margin-bottom:4px; }
    .row-actions { display:none; gap:.25rem; align-items:center; }
    tr:hover .row-actions { display:flex; }
    .row-actions a, .row-actions button { font-size:12px; color:#3b5bdb; text-decoration:none; background:none; border:none; cursor:pointer; padding:0; font-family:inherit; }
    .row-actions a:hover { text-decoration:underline; }
    .row-actions .sep { color:#d1d5db; margin:0 3px; }
    .row-actions .del { color:#dc2626; }

    .bulk-bar { display:flex; align-items:center; gap:.75rem; padding:.65rem 1rem; background:#f8fafc; border-bottom:1px solid #e2e8f0; }
    .bulk-select { border:1.5px solid #e2e8f0; border-radius:6px; padding:5px 10px; font-size:13px; font-family:inherit; }
    .bulk-btn { background:#fff; border:1.5px solid #e2e8f0; border-radius:6px; padding:5px 14px; font-size:13px; font-weight:600; cursor:pointer; transition:all .15s; }
    .bulk-btn:hover { border-color:#3b5bdb; color:#3b5bdb; }

    .wp-pag { display:flex; align-items:center; gap:.4rem; }
    .wp-pag a, .wp-pag span { display:inline-flex; align-items:center; justify-content:center; min-width:30px; height:28px; padding:0 6px; border:1.5px solid #e2e8f0; border-radius:5px; font-size:13px; text-decoration:none; color:#374151; }
    .wp-pag a:hover { border-color:#3b5bdb; color:#3b5bdb; }
    .wp-pag .current { background:#3b5bdb; color:#fff; border-color:#3b5bdb; font-weight:700; }
    .wp-pag .dots { border:none; color:#94a3b8; }

    .top-actions { display:flex; align-items:center; justify-content:space-between; margin-bottom:.75rem; flex-wrap:wrap; gap:.5rem; }
    .search-form { display:flex; gap:.4rem; align-items:center; }
    .search-form input, .search-form select { border:1.5px solid #e2e8f0; border-radius:6px; padding:6px 10px; font-size:13px; font-family:inherit; }
    .search-form input { width:180px; }
    .search-form input:focus, .search-form select:focus { outline:none; border-color:#3b5bdb; }
    .search-form button { background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:6px; padding:6px 14px; font-size:13px; font-weight:600; cursor:pointer; }
    .search-form button:hover { border-color:#3b5bdb; color:#3b5bdb; }
  </style>
</head>
<body>
<?php include '../includes/admin_sidebar.php'; ?>
<main class="main">

  <div class="topbar">
    <h1>Artículos</h1>
    <a href="nuevo-articulo.php" class="btn-new">+ Nuevo artículo</a>
  </div>

  <?php if ($mensaje): ?><div class="mensaje"><?php echo $mensaje; ?></div><?php endif; ?>
  <?php if ($error):   ?><div class="error-msg"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

  <!-- TABS + FILTROS -->
  <div class="top-actions">
    <div class="wp-tabs">
      <a href="articulos.php?q=<?php echo urlencode($q); ?>" class="wp-tab <?php echo $estado==='todos'?'active':''; ?>">Todos <span class="cnt">(<?php echo $total_todos; ?>)</span></a>
      <a href="articulos.php?estado=publicados&q=<?php echo urlencode($q); ?>" class="wp-tab <?php echo $estado==='publicados'?'active':''; ?>">Publicados <span class="cnt">(<?php echo $total_pub; ?>)</span></a>
      <a href="articulos.php?estado=borradores&q=<?php echo urlencode($q); ?>" class="wp-tab <?php echo $estado==='borradores'?'active':''; ?>">Borradores <span class="cnt">(<?php echo $total_bor; ?>)</span></a>
    </div>
    <form class="search-form" method="GET">
      <?php if ($estado !== 'todos'): ?><input type="hidden" name="estado" value="<?php echo htmlspecialchars($estado); ?>"><?php endif; ?>
      <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Buscar artículos…">
      <?php if (!empty($zonas)): ?>
      <select name="zona">
        <option value="">Todas las zonas</option>
        <?php foreach ($zonas as $z): ?>
          <option value="<?php echo htmlspecialchars($z); ?>" <?php echo $zona_f===$z?'selected':''; ?>><?php echo htmlspecialchars($z); ?></option>
        <?php endforeach; ?>
      </select>
      <?php endif; ?>
      <?php if (!empty($categorias)): ?>
      <select name="cat">
        <option value="">Todas las categorías</option>
        <?php foreach ($categorias as $c): ?>
          <option value="<?php echo htmlspecialchars($c); ?>" <?php echo $cat_f===$c?'selected':''; ?>><?php echo htmlspecialchars($c); ?></option>
        <?php endforeach; ?>
      </select>
      <?php endif; ?>
      <button type="submit">Filtrar</button>
      <?php if ($q || $zona_f || $cat_f): ?><a href="articulos.php" style="font-size:13px;color:#64748b;text-decoration:none;padding:6px 4px">✕</a><?php endif; ?>
    </form>
  </div>

  <!-- TABLA -->
  <form method="POST" id="bulk-form">
  <div class="card" style="overflow:hidden">

    <div class="bulk-bar">
      <select name="bulk_action" class="bulk-select">
        <option value="">Acciones en bloque</option>
        <option value="delete">Eliminar</option>
      </select>
      <button type="submit" class="bulk-btn" onclick="return confirmarBulk()">Aplicar</button>
      <?php if ($total_paginas > 1): ?>
      <div class="wp-pag" style="margin-left:auto">
        <?php if ($pag_num > 1): ?><a href="<?php echo build_art_url(['pag'=>$pag_num-1]); ?>">‹</a><?php endif; ?>
        <?php
        for ($i = 1; $i <= $total_paginas; $i++) {
          if ($i === 1 || $i === $total_paginas || abs($i - $pag_num) <= 1) {
            echo '<a href="' . build_art_url(['pag'=>$i]) . '" class="' . ($i===$pag_num?'current':'') . '">' . $i . '</a>';
          } elseif (abs($i - $pag_num) === 2) {
            echo '<span class="dots">…</span>';
          }
        }
        ?>
        <?php if ($pag_num < $total_paginas): ?><a href="<?php echo build_art_url(['pag'=>$pag_num+1]); ?>">›</a><?php endif; ?>
      </div>
      <?php endif; ?>
      <span style="font-size:12.5px;color:#94a3b8;<?php echo $total_paginas<=1?'margin-left:auto':''; ?>"><?php echo $total_filtrado; ?> artículo<?php echo $total_filtrado!==1?'s':''; ?></span>
    </div>

    <table class="wp-table">
      <thead>
        <tr>
          <th class="col-cb"><input type="checkbox" id="cb-all" onchange="toggleAll(this)"></th>
          <th>Título</th>
          <th class="col-zone">Zona</th>
          <th class="col-cat">Categoría</th>
          <th class="col-status">Estado</th>
          <th class="col-date">Fecha</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($articulos)): ?>
          <tr><td colspan="6" style="text-align:center;color:#94a3b8;padding:3rem">No se encontraron artículos.</td></tr>
        <?php else: foreach ($articulos as $art): ?>
        <tr>
          <td><input type="checkbox" name="ids[]" value="<?php echo $art['id']; ?>" class="cb-row"></td>
          <td>
            <a href="nuevo-articulo.php?id=<?php echo $art['id']; ?>" class="row-title"><?php echo htmlspecialchars($art['titulo']); ?></a>
            <span class="row-slug"><?php echo htmlspecialchars($art['slug']); ?></span>
            <div class="row-actions">
              <a href="nuevo-articulo.php?id=<?php echo $art['id']; ?>">Editar</a>
              <span class="sep">|</span>
              <a href="<?php echo build_art_url(['toggle'=>$art['id']]); ?>">
                <?php echo $art['publicado'] ? 'Despublicar' : 'Publicar'; ?>
              </a>
              <span class="sep">|</span>
              <a href="../noticias/<?php echo urlencode($art['slug']); ?>" target="_blank">Ver</a>
              <span class="sep">|</span>
              <a href="#" class="del" onclick="confirmarEliminar(<?php echo $art['id']; ?>,'<?php echo htmlspecialchars(addslashes($art['titulo'])); ?>');return false">Papelera</a>
            </div>
          </td>
          <td>
            <?php if ($art['zona']): ?>
              <span class="badge-zona"><?php echo htmlspecialchars($art['zona']); ?></span>
            <?php else: ?>
              <span style="color:#d1d5db">—</span>
            <?php endif; ?>
          </td>
          <td>
            <?php if ($art['categoria']): ?>
              <span class="badge-cat"><?php echo htmlspecialchars($art['categoria']); ?></span>
            <?php else: ?>
              <span style="color:#d1d5db">—</span>
            <?php endif; ?>
          </td>
          <td>
            <span class="badge-pub <?php echo $art['publicado']?'si':'no'; ?>" style="cursor:default">
              <?php echo $art['publicado'] ? 'Publicado' : 'Borrador'; ?>
            </span>
          </td>
          <td style="font-size:12.5px;color:#64748b;white-space:nowrap">
            <?php
              $ts = strtotime($art['fecha']);
              echo date('d/m/Y', $ts) . '<br><span style="color:#94a3b8">' . date('H:i', $ts) . '</span>';
            ?>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>

    <?php if ($total_paginas > 1): ?>
    <div style="padding:.75rem 1rem;border-top:1px solid #f1f5f9;display:flex;justify-content:flex-end">
      <div class="wp-pag">
        <?php if ($pag_num > 1): ?><a href="<?php echo build_art_url(['pag'=>$pag_num-1]); ?>">‹ Anterior</a><?php endif; ?>
        <?php
        for ($i = 1; $i <= $total_paginas; $i++) {
          if ($i === 1 || $i === $total_paginas || abs($i - $pag_num) <= 1) {
            echo '<a href="' . build_art_url(['pag'=>$i]) . '" class="' . ($i===$pag_num?'current':'') . '">' . $i . '</a>';
          } elseif (abs($i - $pag_num) === 2) {
            echo '<span class="dots">…</span>';
          }
        }
        ?>
        <?php if ($pag_num < $total_paginas): ?><a href="<?php echo build_art_url(['pag'=>$pag_num+1]); ?>">Siguiente ›</a><?php endif; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
  </form>

</main>

<div class="confirm-overlay" id="confirm-overlay">
  <div class="confirm-box">
    <h3>¿Eliminar artículo?</h3>
    <p id="confirm-texto"></p>
    <div class="confirm-btns">
      <a href="#" class="btn-cancelar" onclick="cerrarConfirm();return false">Cancelar</a>
      <a href="#" class="btn-confirmar" id="confirm-link">Eliminar</a>
    </div>
  </div>
</div>

<script>
function confirmarEliminar(id, titulo) {
  document.getElementById('confirm-texto').textContent = '¿Seguro que quieres eliminar "' + titulo + '"? Esta acción no se puede deshacer.';
  document.getElementById('confirm-link').href = 'articulos.php?eliminar=' + id + '&estado=<?php echo urlencode($estado); ?>&q=<?php echo urlencode($q); ?>&zona=<?php echo urlencode($zona_f); ?>&cat=<?php echo urlencode($cat_f); ?>&pag=<?php echo $pag_num; ?>';
  document.getElementById('confirm-overlay').classList.add('open');
}
function cerrarConfirm() { document.getElementById('confirm-overlay').classList.remove('open'); }
document.getElementById('confirm-overlay').addEventListener('click', function(e){ if(e.target===this) cerrarConfirm(); });
function toggleAll(cb) { document.querySelectorAll('.cb-row').forEach(function(c){ c.checked = cb.checked; }); }
function confirmarBulk() {
  var sel = document.querySelectorAll('.cb-row:checked').length;
  var accion = document.querySelector('[name="bulk_action"]').value;
  if (!accion) { alert('Selecciona una acción.'); return false; }
  if (sel === 0) { alert('Selecciona al menos un artículo.'); return false; }
  return confirm('¿Eliminar ' + sel + ' artículo' + (sel!==1?'s':'') + '? Esta acción no se puede deshacer.');
}
</script>
</body>
</html>
