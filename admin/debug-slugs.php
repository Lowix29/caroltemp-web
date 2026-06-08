<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) die('No autorizado.');
require_once '../includes/db.php';

$rows = $pdo->query('SELECT id, slug, imagen, publicado FROM articulos ORDER BY id DESC')->fetchAll();
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Debug slugs</title>
<style>body{font-family:monospace;max-width:1000px;margin:2rem auto;padding:0 1rem;background:#f8fafc}
table{width:100%;border-collapse:collapse;font-size:13px}th,td{padding:6px 10px;border:1px solid #e2e8f0;text-align:left}
th{background:#1e3a5f;color:#fff}tr:nth-child(even){background:#f8fafc}</style></head><body>
<h2>Artículos en BD (<?php echo count($rows); ?> total)</h2>
<table>
<tr><th>ID</th><th>slug</th><th>imagen</th><th>publicado</th></tr>
<?php foreach ($rows as $r): ?>
<tr>
  <td><?php echo $r['id']; ?></td>
  <td><?php echo htmlspecialchars($r['slug']); ?></td>
  <td><?php echo htmlspecialchars($r['imagen'] ?: '—'); ?></td>
  <td><?php echo $r['publicado'] ? '✅' : '🟡 borrador'; ?></td>
</tr>
<?php endforeach; ?>
</table>
</body></html>
