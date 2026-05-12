<?php
require_once 'includes/db.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nueva = trim($_POST['password'] ?? '');
  if (strlen($nueva) < 6) {
    $mensaje = 'La contraseña debe tener al menos 6 caracteres.';
  } else {
    $hash = password_hash($nueva, PASSWORD_BCRYPT);
    $pdo->prepare('UPDATE admin_usuarios SET password = ? WHERE usuario = ?')
        ->execute([$hash, 'admin']);
    $mensaje = '✅ Contraseña cambiada correctamente. Borra este archivo ahora.';
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cambiar contraseña</title>
  <style>
    body { font-family: sans-serif; max-width: 400px; margin: 4rem auto; padding: 0 1rem; }
    input { width: 100%; padding: 10px; margin: .5rem 0 1rem; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 15px; }
    button { background: #1e3a5f; color: #fff; border: none; padding: 12px 24px; border-radius: 6px; font-size: 15px; cursor: pointer; width: 100%; }
    .msg { padding: 1rem; border-radius: 6px; margin-bottom: 1rem; background: #dcfce7; color: #15803d; }
    .err { background: #fff1f2; color: #dc2626; }
  </style>
</head>
<body>
  <h2>Cambiar contraseña admin</h2>
  <?php if ($mensaje): ?>
    <div class="msg <?php echo strpos($mensaje,'✅')===false ? 'err' : ''; ?>"><?php echo $mensaje; ?></div>
  <?php endif; ?>
  <form method="POST">
    <label>Nueva contraseña</label>
    <input type="password" name="password" placeholder="Mínimo 6 caracteres" required>
    <button type="submit">Cambiar contraseña</button>
  </form>
</body>
</html>