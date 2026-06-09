<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php'); exit;
}
require_once '../includes/db.php';

$pdo->exec("CREATE TABLE IF NOT EXISTS paginas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  filepath VARCHAR(255) NOT NULL UNIQUE,
  contenido LONGTEXT,
  meta_title VARCHAR(255) DEFAULT '',
  meta_desc TEXT DEFAULT '',
  robots VARCHAR(20) DEFAULT 'index',
  publicado TINYINT(1) DEFAULT 1,
  fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
  modificado DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
try { $pdo->exec("ALTER TABLE paginas ADD COLUMN robots VARCHAR(20) DEFAULT 'index'"); } catch (PDOException $e) {}
try { $pdo->exec("ALTER TABLE paginas ADD COLUMN img_destacada VARCHAR(500) DEFAULT ''"); } catch (PDOException $e) {}

$mensaje = '';
$error   = '';
$raiz    = dirname(__DIR__);

// ── SINCRONIZAR TÍTULOS DESDE DISCO ──────────────────────────────────────────
if (isset($_POST['sincronizar_titulos'])) {
  $todos = $pdo->query('SELECT id, titulo, filepath FROM paginas')->fetchAll();
  $actualizados = 0;
  foreach ($todos as $p) {
    $abs = $raiz . '/' . $p['filepath'];
    if (!file_exists($abs)) continue;
    $raw = file_get_contents($abs);
    $nuevo_titulo = '';
    if (preg_match('/\$meta_title\s*=\s*[\'"](.+?)[\'"]\s*;/', $raw, $m)) {
      $nuevo_titulo = trim(stripslashes($m[1]));
    }
    if ($nuevo_titulo && $nuevo_titulo !== $p['titulo']) {
      $pdo->prepare('UPDATE paginas SET titulo = ? WHERE id = ?')->execute([$nuevo_titulo, $p['id']]);
      $actualizados++;
    }
  }
  $mensaje = '✅ ' . $actualizados . ' título' . ($actualizados !== 1 ? 's' : '') . ' actualizados desde los archivos.';
}

// ── IMPORTAR UNA PÁGINA INDIVIDUAL ───────────────────────────────────────────
if (isset($_GET['importar'])) {
  $fn = basename($_GET['importar']);
  if (substr($fn, -4) !== '.php') $fn .= '.php';
  if (!preg_match('/^[a-z0-9_\-]+\.php$/', $fn)) {
    $error = 'Nombre no válido.';
  } else {
    $chk = $pdo->prepare('SELECT id FROM paginas WHERE filepath = ? LIMIT 1');
    $chk->execute([$fn]);
    $existente = $chk->fetchColumn();
    if ($existente) { header('Location: nueva-pagina.php?id=' . $existente); exit; }
    $abs_imp = $raiz . DIRECTORY_SEPARATOR . $fn;
    $contenido = ''; $titulo = ucwords(str_replace(['-','_'], ' ', basename($fn, '.php')));
    if (file_exists($abs_imp)) {
      $raw = file_get_contents($abs_imp);
      if (preg_match('/\$meta_title\s*=\s*[\'"](.+?)[\'"]\s*;/', $raw, $mm)) $titulo = stripslashes($mm[1]);
      $pe = strpos($raw, '?>');
      if ($pe !== false) {
        $hp = ltrim(substr($raw, $pe + 2));
        $fp2 = strrpos($hp, '<?php');
        if ($fp2 !== false) $hp = rtrim(substr($hp, 0, $fp2));
        if (substr_count($hp, '<?php') <= 4) $contenido = $hp;
      }
    }
    $slug = str_replace('.php', '', $fn);
    try {
      $pdo->prepare('INSERT INTO paginas (titulo,slug,filepath,contenido,publicado) VALUES (?,?,?,?,1)')->execute([$titulo,$slug,$fn,$contenido]);
      header('Location: nueva-pagina.php?id=' . $pdo->lastInsertId() . '&importado=1'); exit;
    } catch (PDOException $e) { $error = $e->getMessage(); }
  }
}

// ── ELIMINAR ─────────────────────────────────────────────────────────────────
if (isset($_GET['eliminar']) && is_numeric($_GET['eliminar'])) {
  $sel = $pdo->prepare('SELECT filepath FROM paginas WHERE id = ?');
  $sel->execute([$_GET['eliminar']]);
  $fp = $sel->fetchColumn();
  $pdo->prepare('DELETE FROM paginas WHERE id = ?')->execute([$_GET['eliminar']]);
  if ($fp) {
    $abs = $raiz . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, ltrim($fp, '/'));
    $real_root = realpath($raiz);
    $real_abs  = realpath($abs);
    if ($real_abs && $real_root && strpos($real_abs, $real_root . DIRECTORY_SEPARATOR) === 0 && file_exists($abs)) {
      copy($abs, $abs . '.bak');
      unlink($abs);
    }
  }
  $mensaje = '✅ Página eliminada.';
}

// ── BORRADO MASIVO ────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_action']) && $_POST['bulk_action'] === 'delete' && !empty($_POST['ids'])) {
  $ids = array_filter(array_map('intval', (array)$_POST['ids']));
  foreach ($ids as $bid) {
    $sel = $pdo->prepare('SELECT filepath FROM paginas WHERE id = ?');
    $sel->execute([$bid]);
    $fp = $sel->fetchColumn();
    $pdo->prepare('DELETE FROM paginas WHERE id = ?')->execute([$bid]);
    if ($fp) {
      $abs = $raiz . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, ltrim($fp, '/'));
      if (realpath($abs) && file_exists($abs)) { copy($abs, $abs . '.bak'); unlink($abs); }
    }
  }
  $mensaje = '✅ ' . count($ids) . ' páginas eliminadas.';
}

// ── TOGGLE PUBLICADO ─────────────────────────────────────────────────────────
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
  $pdo->prepare('UPDATE paginas SET publicado = NOT publicado WHERE id = ?')->execute([$_GET['toggle']]);
  header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?') . '?' . http_build_query(array_diff_key($_GET, ['toggle'=>'']))); exit;
}

// ── ESCANEO DISCO RECURSIVO ───────────────────────────────────────────────────
$ignorar_arch = ['404.php','sitemap.php','index.php','login.php','logout.php','cambiar-password.php','robots.php'];
$ignorar_dirs = ['admin','includes','noticias','proyectos','blog'];
$todos_disco  = [];
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($raiz, FilesystemIterator::SKIP_DOTS));
foreach ($it as $fi) {
  if (!$fi->isFile() || $fi->getExtension() !== 'php') continue;
  $rel = str_replace($raiz . DIRECTORY_SEPARATOR, '', $fi->getPathname());
  $rel = str_replace('\\', '/', $rel);
  $partes = explode('/', $rel); $skip = false;
  foreach ($ignorar_dirs as $d) { if (in_array($d, $partes)) { $skip = true; break; } }
  if ($skip || in_array(basename($rel), $ignorar_arch) || $rel === 'index.php') continue;
  $todos_disco[] = $rel;
}
sort($todos_disco);

// ── FILTROS Y PAGINACIÓN ─────────────────────────────────────────────────────
$q        = trim($_GET['q'] ?? '');
$estado   = $_GET['estado'] ?? 'todos';
$por_pag  = 20;
$pag_num  = max(1, (int)($_GET['pag'] ?? 1));

$where  = ['1=1'];
$params = [];
if ($q)              { $where[] = '(titulo LIKE ? OR filepath LIKE ?)'; $params[] = "%$q%"; $params[] = "%$q%"; }
if ($estado === 'publicadas') { $where[] = 'publicado = 1'; }
if ($estado === 'borradores') { $where[] = 'publicado = 0'; }

$where_sql = implode(' AND ', $where);
$total_todos  = (int)$pdo->query('SELECT COUNT(*) FROM paginas')->fetchColumn();
$total_pub    = (int)$pdo->query('SELECT COUNT(*) FROM paginas WHERE publicado=1')->fetchColumn();
$total_bor    = (int)$pdo->query('SELECT COUNT(*) FROM paginas WHERE publicado=0')->fetchColumn();
$total_disco  = count($todos_disco);

$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM paginas WHERE $where_sql");
$count_stmt->execute($params);
$total_filtrado = (int)$count_stmt->fetchColumn();
$total_paginas  = max(1, (int)ceil($total_filtrado / $por_pag));
$pag_num        = min($pag_num, $total_paginas);
$offset         = ($pag_num - 1) * $por_pag;

$stmt = $pdo->prepare("SELECT id,titulo,slug,filepath,publicado,modificado FROM paginas WHERE $where_sql ORDER BY modificado DESC LIMIT $por_pag OFFSET $offset");
$stmt->execute($params);
$paginas = $stmt->fetchAll();

$db_filepaths  = $pdo->query('SELECT filepath FROM paginas')->fetchAll(PDO::FETCH_COLUMN);
$no_registradas = array_filter($todos_disco, function($f) use ($db_filepaths) { return !in_array($f, $db_filepaths); });

function build_url($extra = []) {
  $base = array_merge(['q'=>$_GET['q']??'','estado'=>$_GET['estado']??'todos','pag'=>$_GET['pag']??1], $extra);
  return 'paginas.php?' . http_build_query(array_filter($base, function($v){ return $v !== '' && $v !== 'todos' && $v !== 1 && $v !== '1'; }));
}

$pagina_actual = 'paginas.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Páginas — CarolTemp Admin</title>
  <meta name="robots" content="noindex,nofollow">
  <?php include '../includes/admin_style.php'; ?>
  <style>
    /* ── Estado tabs estilo WP ── */
    .wp-tabs { display:flex; gap:0; margin-bottom:1rem; border-bottom:2px solid #e2e8f0; }
    .wp-tab { padding:.5rem 1rem; font-size:13px; color:#64748b; text-decoration:none; border-bottom:2px solid transparent; margin-bottom:-2px; font-weight:500; transition:color .15s; }
    .wp-tab:hover { color:#0f172a; }
    .wp-tab.active { color:#3b5bdb; border-bottom-color:#3b5bdb; font-weight:700; }
    .wp-tab .cnt { color:#94a3b8; font-size:12px; }
    .wp-tab.active .cnt { color:#3b5bdb; }

    /* ── Tabla estilo WP ── */
    .wp-table { width:100%; border-collapse:collapse; }
    .wp-table th { background:#f8fafc; color:#64748b; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; padding:.65rem 1rem; text-align:left; border-bottom:1px solid #e2e8f0; }
    .wp-table th.col-cb { width:32px; }
    .wp-table th.col-date { width:120px; }
    .wp-table th.col-status { width:100px; }
    .wp-table td { padding:.7rem 1rem; border-bottom:1px solid #f1f5f9; vertical-align:middle; }
    .wp-table tr:last-child td { border-bottom:none; }
    .wp-table tr:hover td { background:#fafbfc; }

    /* ── Título con row actions ── */
    .row-title { font-size:14px; font-weight:700; color:#0f172a; display:block; margin-bottom:3px; text-decoration:none; }
    .row-title:hover { color:#3b5bdb; }
    .row-filepath { font-family:monospace; font-size:11.5px; color:#94a3b8; display:block; margin-bottom:4px; }
    .row-actions { display:none; gap:.25rem; align-items:center; }
    tr:hover .row-actions { display:flex; }
    .row-actions a, .row-actions button { font-size:12px; color:#3b5bdb; text-decoration:none; background:none; border:none; cursor:pointer; padding:0; font-family:inherit; }
    .row-actions a:hover { text-decoration:underline; }
    .row-actions .sep { color:#d1d5db; margin:0 3px; }
    .row-actions .del { color:#dc2626; }

    /* ── Bulk actions bar ── */
    .bulk-bar { display:flex; align-items:center; gap:.75rem; padding:.65rem 1rem; background:#f8fafc; border-bottom:1px solid #e2e8f0; }
    .bulk-select { border:1.5px solid #e2e8f0; border-radius:6px; padding:5px 10px; font-size:13px; font-family:inherit; }
    .bulk-btn { background:#fff; border:1.5px solid #e2e8f0; border-radius:6px; padding:5px 14px; font-size:13px; font-weight:600; cursor:pointer; transition:all .15s; }
    .bulk-btn:hover { border-color:#3b5bdb; color:#3b5bdb; }
    .bulk-count { font-size:12.5px; color:#94a3b8; margin-left:auto; }

    /* ── Paginación estilo WP ── */
    .wp-pag { display:flex; align-items:center; gap:.4rem; }
    .wp-pag a, .wp-pag span { display:inline-flex; align-items:center; justify-content:center; min-width:30px; height:28px; padding:0 6px; border:1.5px solid #e2e8f0; border-radius:5px; font-size:13px; text-decoration:none; color:#374151; }
    .wp-pag a:hover { border-color:#3b5bdb; color:#3b5bdb; }
    .wp-pag .current { background:#3b5bdb; color:#fff; border-color:#3b5bdb; font-weight:700; }
    .wp-pag .dots { border:none; color:#94a3b8; }

    /* ── Banner sin importar ── */
    .disco-banner { background:#fff7ed; border:1px solid #fed7aa; border-radius:10px; padding:1rem 1.25rem; margin-bottom:1.25rem; display:flex; align-items:center; gap:1rem; }
    .disco-banner-txt { flex:1; font-size:13.5px; color:#92400e; }
    .disco-banner-txt strong { font-weight:700; }

    /* ── Barra superior ── */
    .top-actions { display:flex; align-items:center; justify-content:space-between; margin-bottom:.75rem; flex-wrap:wrap; gap:.5rem; }
    .search-form { display:flex; gap:.4rem; }
    .search-form input { border:1.5px solid #e2e8f0; border-radius:6px; padding:6px 12px; font-size:13px; font-family:inherit; width:200px; }
    .search-form input:focus { outline:none; border-color:#3b5bdb; }
    .search-form button { background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:6px; padding:6px 14px; font-size:13px; font-weight:600; cursor:pointer; }
    .search-form button:hover { border-color:#3b5bdb; color:#3b5bdb; }
  </style>
</head>
<body>
<?php include '../includes/admin_sidebar.php'; ?>
<main class="main">

  <!-- TOPBAR -->
  <div class="topbar">
    <h1>Páginas</h1>
    <div style="display:flex;gap:.625rem;align-items:center">
      <?php if (!empty($no_registradas)): ?>
        <a href="paginas-importar.php" style="background:#f97316;color:#fff;padding:8px 16px;border-radius:7px;font-size:13px;font-weight:600;text-decoration:none">
          ⚠️ <?php echo count($no_registradas); ?> sin importar
        </a>
      <?php endif; ?>
      <form method="POST" style="margin:0">
        <button name="sincronizar_titulos" class="btn-preview" title="Releer meta_title de cada archivo PHP y actualizar el título en BD">🔄 Sincronizar títulos</button>
      </form>
      <a href="paginas-importar.php" class="btn-preview">📥 Importar</a>
      <a href="nueva-pagina.php" class="btn-new">+ Nueva página</a>
    </div>
  </div>

  <?php if ($mensaje): ?><div class="mensaje"><?php echo $mensaje; ?></div><?php endif; ?>
  <?php if ($error):   ?><div class="error-msg"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

  <!-- BANNER DISCO -->
  <?php if (!empty($no_registradas)): ?>
  <div class="disco-banner">
    <span style="font-size:22px">⚠️</span>
    <div class="disco-banner-txt">
      <strong><?php echo count($no_registradas); ?> páginas en disco</strong> no están registradas en la base de datos — no aparecen en el sitemap ni puedes editarlas desde el panel.
      <div style="margin-top:4px;font-size:12px;color:#a16207"><?php echo implode(' · ', array_map('htmlspecialchars', array_slice((array)$no_registradas, 0, 6))); ?><?php if (count($no_registradas) > 6) echo ' · …'; ?></div>
    </div>
    <a href="paginas-importar.php" style="background:#ea580c;color:#fff;padding:8px 18px;border-radius:7px;font-size:13px;font-weight:700;text-decoration:none;white-space:nowrap">Importar →</a>
  </div>
  <?php endif; ?>

  <!-- TABS ESTADO -->
  <div class="top-actions">
    <div class="wp-tabs">
      <a href="paginas.php?q=<?php echo urlencode($q); ?>" class="wp-tab <?php echo $estado==='todos'?'active':''; ?>">Todos <span class="cnt">(<?php echo $total_todos; ?>)</span></a>
      <a href="paginas.php?estado=publicadas&q=<?php echo urlencode($q); ?>" class="wp-tab <?php echo $estado==='publicadas'?'active':''; ?>">Publicadas <span class="cnt">(<?php echo $total_pub; ?>)</span></a>
      <a href="paginas.php?estado=borradores&q=<?php echo urlencode($q); ?>" class="wp-tab <?php echo $estado==='borradores'?'active':''; ?>">Borradores <span class="cnt">(<?php echo $total_bor; ?>)</span></a>
    </div>
    <form class="search-form" method="GET">
      <?php if ($estado !== 'todos'): ?><input type="hidden" name="estado" value="<?php echo htmlspecialchars($estado); ?>"><?php endif; ?>
      <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Buscar páginas…">
      <button type="submit">Buscar</button>
      <?php if ($q): ?><a href="paginas.php" style="font-size:13px;color:#64748b;text-decoration:none;padding:6px 4px">✕</a><?php endif; ?>
    </form>
  </div>

  <!-- TABLA -->
  <form method="POST" id="bulk-form">
  <div class="card" style="overflow:hidden">

    <!-- BULK BAR -->
    <div class="bulk-bar">
      <select name="bulk_action" class="bulk-select">
        <option value="">Acciones en bloque</option>
        <option value="delete">Mover a la papelera</option>
      </select>
      <button type="submit" class="bulk-btn" onclick="return confirmarBulk()">Aplicar</button>
      <!-- PAGINACIÓN arriba -->
      <?php if ($total_paginas > 1): ?>
      <div class="wp-pag" style="margin-left:auto">
        <?php if ($pag_num > 1): ?><a href="<?php echo build_url(['pag'=>$pag_num-1]); ?>">‹</a><?php endif; ?>
        <?php
        for ($i = 1; $i <= $total_paginas; $i++) {
          if ($i === 1 || $i === $total_paginas || abs($i - $pag_num) <= 1) {
            echo '<a href="' . build_url(['pag'=>$i]) . '" class="' . ($i===$pag_num?'current':'') . '">' . $i . '</a>';
          } elseif (abs($i - $pag_num) === 2) {
            echo '<span class="dots">…</span>';
          }
        }
        ?>
        <?php if ($pag_num < $total_paginas): ?><a href="<?php echo build_url(['pag'=>$pag_num+1]); ?>">›</a><?php endif; ?>
      </div>
      <?php endif; ?>
      <span class="bulk-count" style="<?php echo $total_paginas > 1 ? '' : 'margin-left:auto'; ?>"><?php echo $total_filtrado; ?> página<?php echo $total_filtrado!==1?'s':''; ?></span>
    </div>

    <table class="wp-table">
      <thead>
        <tr>
          <th class="col-cb"><input type="checkbox" id="cb-all" onchange="toggleAll(this)"></th>
          <th>Título</th>
          <th class="col-status">Estado</th>
          <th class="col-date">Fecha</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($paginas)): ?>
          <tr><td colspan="4" style="text-align:center;color:#94a3b8;padding:3rem">No se encontraron páginas.</td></tr>
        <?php else: foreach ($paginas as $pag):
          $url_ver = '../' . htmlspecialchars($pag['filepath']);
        ?>
        <tr>
          <td><input type="checkbox" name="ids[]" value="<?php echo $pag['id']; ?>" class="cb-row"></td>
          <td>
            <a href="nueva-pagina.php?id=<?php echo $pag['id']; ?>" class="row-title"><?php echo htmlspecialchars($pag['titulo']); ?></a>
            <span class="row-filepath"><?php echo htmlspecialchars($pag['filepath']); ?></span>
            <div class="row-actions">
              <a href="nueva-pagina.php?id=<?php echo $pag['id']; ?>">Editar</a>
              <span class="sep">|</span>
              <a href="?toggle=<?php echo $pag['id']; ?>&estado=<?php echo urlencode($estado); ?>&q=<?php echo urlencode($q); ?>&pag=<?php echo $pag_num; ?>">
                <?php echo $pag['publicado'] ? 'Despublicar' : 'Publicar'; ?>
              </a>
              <span class="sep">|</span>
              <a href="<?php echo $url_ver; ?>" target="_blank">Ver</a>
              <span class="sep">|</span>
              <a href="#" class="del" onclick="confirmarEliminar(<?php echo $pag['id']; ?>,'<?php echo htmlspecialchars(addslashes($pag['titulo'])); ?>');return false">Papelera</a>
            </div>
          </td>
          <td>
            <span class="badge-pub <?php echo $pag['publicado']?'si':'no'; ?>" style="cursor:default">
              <?php echo $pag['publicado'] ? 'Publicado' : 'Borrador'; ?>
            </span>
          </td>
          <td style="font-size:12.5px;color:#64748b;white-space:nowrap">
            <?php
              $ts = strtotime($pag['modificado']);
              echo date('d/m/Y', $ts) . '<br><span style="color:#94a3b8">' . date('H:i', $ts) . '</span>';
            ?>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>

    <!-- PAGINACIÓN abajo -->
    <?php if ($total_paginas > 1): ?>
    <div style="padding:.75rem 1rem;border-top:1px solid #f1f5f9;display:flex;justify-content:flex-end">
      <div class="wp-pag">
        <?php if ($pag_num > 1): ?><a href="<?php echo build_url(['pag'=>$pag_num-1]); ?>">‹ Anterior</a><?php endif; ?>
        <?php
        for ($i = 1; $i <= $total_paginas; $i++) {
          if ($i === 1 || $i === $total_paginas || abs($i - $pag_num) <= 1) {
            echo '<a href="' . build_url(['pag'=>$i]) . '" class="' . ($i===$pag_num?'current':'') . '">' . $i . '</a>';
          } elseif (abs($i - $pag_num) === 2) {
            echo '<span class="dots">…</span>';
          }
        }
        ?>
        <?php if ($pag_num < $total_paginas): ?><a href="<?php echo build_url(['pag'=>$pag_num+1]); ?>">Siguiente ›</a><?php endif; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
  </form>

</main>

<!-- CONFIRMAR ELIMINAR -->
<div class="confirm-overlay" id="confirm-overlay">
  <div class="confirm-box">
    <h3>¿Mover a la papelera?</h3>
    <p id="confirm-texto"></p>
    <div class="confirm-btns">
      <a href="#" class="btn-cancelar" onclick="cerrarConfirm();return false">Cancelar</a>
      <a href="#" class="btn-confirmar" id="confirm-link">Eliminar</a>
    </div>
  </div>
</div>

<script>
function confirmarEliminar(id, titulo) {
  document.getElementById('confirm-texto').textContent = '¿Seguro que quieres eliminar "' + titulo + '"?';
  document.getElementById('confirm-link').href = 'paginas.php?eliminar=' + id + '&estado=<?php echo urlencode($estado); ?>&q=<?php echo urlencode($q); ?>&pag=<?php echo $pag_num; ?>';
  document.getElementById('confirm-overlay').classList.add('open');
}
function cerrarConfirm() { document.getElementById('confirm-overlay').classList.remove('open'); }
document.getElementById('confirm-overlay').addEventListener('click', function(e){ if(e.target===this) cerrarConfirm(); });

function toggleAll(cb) { document.querySelectorAll('.cb-row').forEach(function(c){ c.checked = cb.checked; }); }

function confirmarBulk() {
  var sel = document.querySelectorAll('.cb-row:checked').length;
  var accion = document.querySelector('[name="bulk_action"]').value;
  if (!accion) { alert('Selecciona una acción.'); return false; }
  if (sel === 0) { alert('Selecciona al menos una página.'); return false; }
  return confirm('¿' + (accion==='delete'?'Eliminar':'Acción sobre') + ' ' + sel + ' página' + (sel!==1?'s':'') + '?');
}
</script>
</body>
</html>
