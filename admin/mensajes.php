<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php');
  exit;
}
require_once '../includes/db.php';

// MARCAR COMO LEÍDO
if (isset($_GET['leido']) && is_numeric($_GET['leido'])) {
  $pdo->prepare('UPDATE mensajes SET leido = 1 WHERE id = ?')->execute([$_GET['leido']]);
  header('Location: mensajes.php');
  exit;
}

// MARCAR COMO NO LEÍDO
if (isset($_GET['noleido']) && is_numeric($_GET['noleido'])) {
  $pdo->prepare('UPDATE mensajes SET leido = 0 WHERE id = ?')->execute([$_GET['noleido']]);
  header('Location: mensajes.php');
  exit;
}

// GUARDAR NOTA
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nota_id'])) {
  $pdo->prepare('UPDATE mensajes SET notas = ? WHERE id = ?')
      ->execute([trim($_POST['notas']), $_POST['nota_id']]);
  header('Location: mensajes.php?ok=1');
  exit;
}

// ELIMINAR
if (isset($_GET['eliminar']) && is_numeric($_GET['eliminar'])) {
  $pdo->prepare('DELETE FROM mensajes WHERE id = ?')->execute([$_GET['eliminar']]);
  header('Location: mensajes.php');
  exit;
}

// FILTRO
$filtro  = $_GET['filtro'] ?? 'todos';
$where   = $filtro === 'nuevos' ? 'WHERE leido = 0' : ($filtro === 'leidos' ? 'WHERE leido = 1' : '');
$mensajes = $pdo->query('SELECT * FROM mensajes ' . $where . ' ORDER BY fecha DESC')->fetchAll();
$total_nuevos = $pdo->query('SELECT COUNT(*) FROM mensajes WHERE leido = 0')->fetchColumn();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mensajes — Hidrofont Admin</title>
  <meta name="robots" content="noindex, nofollow">
  <?php include '../includes/admin_style.php'; ?>
  <style>
    .msg-card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;margin-bottom:1rem;transition:border-color .15s;box-shadow:0 1px 3px rgba(0,0,0,.04)}
    .msg-card.nuevo{border-left:4px solid #3b5bdb}
    .msg-card.leido{border-left:4px solid #e2e8f0;opacity:.85}
    .msg-header{display:flex;align-items:center;justify-content:space-between;padding:1rem 1.5rem;border-bottom:1px solid #f1f5f9;flex-wrap:wrap;gap:.75rem}
    .msg-header-left{display:flex;align-items:center;gap:1rem;flex-wrap:wrap}
    .msg-avatar{width:40px;height:40px;background:#eff6ff;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#1d4ed8;font-size:16px;font-weight:700;flex-shrink:0}
    .msg-nombre{color:#0f172a;font-size:15px;font-weight:600}
    .msg-fecha{color:#94a3b8;font-size:12px;margin-top:2px}
    .msg-badges{display:flex;gap:.5rem;flex-wrap:wrap}
    .msg-badge{font-size:11.5px;font-weight:500;padding:4px 12px;border-radius:20px}
    .msg-badge-zona{background:#eff6ff;color:#1d4ed8}
    .msg-badge-serv{background:#f5f3ff;color:#6d28d9}
    .msg-badge-new{background:#dcfce7;color:#15803d}
    .msg-badge-read{background:#f1f5f9;color:#64748b}
    .msg-acciones{display:flex;gap:.5rem;flex-wrap:wrap}
    .msg-acciones a{font-size:12.5px;font-weight:500;text-decoration:none;padding:6px 14px;border-radius:6px;transition:opacity .15s;white-space:nowrap}
    .msg-acciones a:hover{opacity:.75}
    .btn-wa{background:#dcfce7;color:#15803d}
    .btn-tel{background:#eff6ff;color:#1d4ed8}
    .btn-leido{background:#f1f5f9;color:#64748b}
    .btn-del{background:#fff1f2;color:#dc2626}
    .msg-body{padding:1.25rem 1.5rem}
    .msg-tel-big{font-size:16px;font-weight:700;color:#0f172a;margin-bottom:.75rem;display:flex;align-items:center;gap:8px}
    .msg-texto{color:#475569;font-size:14px;line-height:1.7;background:#f8fafc;border-radius:8px;padding:1rem;margin-bottom:1rem}
    .msg-nota-wrap{margin-top:.75rem}
    .msg-nota-wrap label{font-size:12px;font-weight:500;color:#64748b;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.4rem;display:block}
    .msg-nota-wrap textarea{width:100%;border:1.5px solid #e2e8f0;border-radius:8px;padding:.75rem 1rem;font-size:13.5px;font-family:inherit;resize:vertical;min-height:70px;transition:border-color .15s}
    .msg-nota-wrap textarea:focus{outline:none;border-color:#3b5bdb}
    .btn-nota{background:#3b5bdb;color:#fff;border:none;border-radius:6px;padding:7px 18px;font-size:13px;font-weight:500;cursor:pointer;margin-top:.5rem}
    .filtros-tabs{display:flex;gap:.5rem;margin-bottom:1.5rem;flex-wrap:wrap}
    .ftab{padding:8px 20px;border-radius:8px;font-size:13.5px;font-weight:500;text-decoration:none;border:1.5px solid #e2e8f0;color:#64748b;background:#fff;transition:all .15s}
    .ftab:hover{border-color:#3b5bdb;color:#3b5bdb}
    .ftab.active{background:#3b5bdb;border-color:#3b5bdb;color:#fff}
    .badge-count{background:#dc2626;color:#fff;font-size:11px;font-weight:700;padding:1px 7px;border-radius:20px;margin-left:6px}
    .empty{text-align:center;padding:4rem;color:#94a3b8;font-size:15px}
  </style>
</head>
<body>

<?php include '../includes/admin_sidebar.php'; ?>

<main class="main">

  <div class="topbar">
    <h1>Mensajes <?php if ($total_nuevos > 0): ?><span class="badge-count"><?php echo $total_nuevos; ?></span><?php endif; ?></h1>
  </div>

  <?php if (isset($_GET['ok'])): ?>
    <div class="mensaje">✅ Nota guardada correctamente.</div>
  <?php endif; ?>

  <!-- FILTROS -->
  <div class="filtros-tabs">
    <a href="?filtro=todos"   class="ftab <?php echo $filtro==='todos'  ?'active':''; ?>">Todos (<?php echo count($mensajes); ?>)</a>
    <a href="?filtro=nuevos"  class="ftab <?php echo $filtro==='nuevos' ?'active':''; ?>">Nuevos <?php if ($total_nuevos > 0): ?><span class="badge-count"><?php echo $total_nuevos; ?></span><?php endif; ?></a>
    <a href="?filtro=leidos"  class="ftab <?php echo $filtro==='leidos' ?'active':''; ?>">Leídos</a>
  </div>

  <!-- MENSAJES -->
  <?php if (empty($mensajes)): ?>
    <div class="empty">No hay mensajes todavía</div>
  <?php else: ?>
    <?php foreach ($mensajes as $msg): ?>
      <div class="msg-card <?php echo $msg['leido'] ? 'leido' : 'nuevo'; ?>">

        <div class="msg-header">
          <div class="msg-header-left">
            <div class="msg-avatar"><?php echo strtoupper(substr($msg['nombre'], 0, 1)); ?></div>
            <div>
              <div class="msg-nombre"><?php echo htmlspecialchars($msg['nombre']); ?></div>
              <div class="msg-fecha"><?php echo date('d/m/Y H:i', strtotime($msg['fecha'])); ?></div>
            </div>
            <div class="msg-badges">
              <?php if ($msg['zona']): ?><span class="msg-badge msg-badge-zona">📍 <?php echo htmlspecialchars($msg['zona']); ?></span><?php endif; ?>
              <?php if ($msg['servicio']): ?><span class="msg-badge msg-badge-serv"><?php echo htmlspecialchars($msg['servicio']); ?></span><?php endif; ?>
              <span class="msg-badge <?php echo $msg['leido'] ? 'msg-badge-read' : 'msg-badge-new'; ?>">
                <?php echo $msg['leido'] ? 'Leído' : '● Nuevo'; ?>
              </span>
            </div>
          </div>
          <div class="msg-acciones">
            <a href="tel:+34<?php echo preg_replace('/\D/', '', $msg['telefono']); ?>" class="btn-tel">📞 Llamar</a>
            <a href="https://wa.me/34<?php echo preg_replace('/\D/', '', $msg['telefono']); ?>?text=Hola+<?php echo urlencode($msg['nombre']); ?>+te+contacto+de+Hidrofont+sobre+tu+solicitud" target="_blank" rel="noopener" class="btn-wa">💬 WhatsApp</a>
            <?php if (!$msg['leido']): ?>
              <a href="?leido=<?php echo $msg['id']; ?>&filtro=<?php echo $filtro; ?>" class="btn-leido">✓ Marcar leído</a>
            <?php else: ?>
              <a href="?noleido=<?php echo $msg['id']; ?>&filtro=<?php echo $filtro; ?>" class="btn-leido">↩ Sin leer</a>
            <?php endif; ?>
            <a href="#" class="btn-del" onclick="return confirm('¿Eliminar este mensaje?') && (window.location='?eliminar=<?php echo $msg['id']; ?>')">Eliminar</a>
          </div>
        </div>

        <div class="msg-body">
          <div class="msg-tel-big">📞 <?php echo htmlspecialchars($msg['telefono']); ?></div>
          <?php if ($msg['mensaje']): ?>
            <div class="msg-texto"><?php echo nl2br(htmlspecialchars($msg['mensaje'])); ?></div>
          <?php endif; ?>
          <form method="POST" action="" class="msg-nota-wrap">
            <input type="hidden" name="nota_id" value="<?php echo $msg['id']; ?>">
            <label>Notas internas</label>
            <textarea name="notas" placeholder="Añade notas sobre este cliente..."><?php echo htmlspecialchars($msg['notas'] ?? ''); ?></textarea>
            <button type="submit" class="btn-nota">Guardar nota</button>
          </form>
        </div>

      </div>
    <?php endforeach; ?>
  <?php endif; ?>

</main>

</body>
</html>