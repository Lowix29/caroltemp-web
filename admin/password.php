<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php');
  exit;
}
require_once '../includes/db.php';

$mensaje = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $actual    = $_POST['actual']    ?? '';
  $nueva     = $_POST['nueva']     ?? '';
  $confirmar = $_POST['confirmar'] ?? '';

  if (!$actual || !$nueva || !$confirmar) {
    $error = 'Todos los campos son obligatorios.';
  } elseif (strlen($nueva) < 8) {
    $error = 'La nueva contraseña debe tener al menos 8 caracteres.';
  } elseif ($nueva !== $confirmar) {
    $error = 'La nueva contraseña y la confirmación no coinciden.';
  } else {
    // Verificar contraseña actual
    $stmt = $pdo->prepare('SELECT password FROM admin_usuarios WHERE usuario = ? LIMIT 1');
    $stmt->execute([$_SESSION['admin_usuario']]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($actual, $user['password'])) {
      $error = 'La contraseña actual no es correcta.';
    } else {
      // Actualizar contraseña
      $hash = password_hash($nueva, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare('UPDATE admin_usuarios SET password = ? WHERE usuario = ?');
      $stmt->execute([$hash, $_SESSION['admin_usuario']]);
      $mensaje = '✅ Contraseña actualizada correctamente.';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cambiar contraseña — CarolTemp Admin</title>
  <meta name="robots" content="noindex, nofollow">
  <?php include '../includes/admin_style.php'; ?>
  <style>
    .password-wrap { max-width: 480px; }
    .requisitos { background: #f4f7fb; border: 1px solid #dde6f0; border-radius: 8px; padding: 1rem 1.25rem; margin-bottom: 1.5rem; }
    .requisitos h3 { color: #1e3a5f; font-size: 13px; font-weight: 600; margin-bottom: 0.75rem; }
    .requisitos ul { display: flex; flex-direction: column; gap: 0.4rem; padding-left: 1.25rem; }
    .requisitos ul li { color: #5a7a95; font-size: 13px; }
    .strength-bar { height: 4px; border-radius: 2px; background: #eaeff4; margin-top: 8px; overflow: hidden; }
    .strength-fill { height: 100%; border-radius: 2px; transition: width 0.3s, background 0.3s; width: 0; }
    .strength-label { font-size: 11px; color: #7a95b0; margin-top: 4px; }
  </style>
</head>
<body>

<?php include '../includes/admin_sidebar.php'; ?>

<main class="main">

  <div class="topbar">
    <h1>Cambiar contraseña</h1>
  </div>

  <?php if ($mensaje): ?>
    <div class="mensaje"><?php echo $mensaje; ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <div class="password-wrap">
    <div class="card">
      <div class="card-header">
        <h2>Nueva contraseña</h2>
      </div>
      <div class="card-body">

        <div class="requisitos">
          <h3>Requisitos</h3>
          <ul>
            <li>Mínimo 8 caracteres</li>
            <li>Recomendado: combina letras, números y símbolos</li>
            <li>Evita contraseñas obvias como "password" o "123456"</li>
          </ul>
        </div>

        <form method="POST" action="">
          <div class="form-group" style="margin-bottom:1rem">
            <label for="actual">Contraseña actual</label>
            <input
              type="password"
              id="actual"
              name="actual"
              required
              autocomplete="current-password"
            >
          </div>

          <div class="form-group" style="margin-bottom:0.5rem">
            <label for="nueva">Nueva contraseña</label>
            <input
              type="password"
              id="nueva"
              name="nueva"
              required
              autocomplete="new-password"
              oninput="medirFuerza(this.value)"
            >
            <div class="strength-bar">
              <div class="strength-fill" id="strength-fill"></div>
            </div>
            <span class="strength-label" id="strength-label"></span>
          </div>

          <div class="form-group" style="margin-bottom:1.5rem">
            <label for="confirmar">Confirmar nueva contraseña</label>
            <input
              type="password"
              id="confirmar"
              name="confirmar"
              required
              autocomplete="new-password"
              oninput="comprobarCoincidencia()"
            >
            <span class="strength-label" id="match-label"></span>
          </div>

          <button type="submit" class="btn-save">
            🔒 Cambiar contraseña
          </button>
        </form>

      </div>
    </div>
  </div>

</main>

<script>
function medirFuerza(pass) {
  const fill  = document.getElementById('strength-fill');
  const label = document.getElementById('strength-label');

  let puntos = 0;
  if (pass.length >= 8)  puntos++;
  if (pass.length >= 12) puntos++;
  if (/[A-Z]/.test(pass)) puntos++;
  if (/[0-9]/.test(pass)) puntos++;
  if (/[^A-Za-z0-9]/.test(pass)) puntos++;

  const niveles = [
    { pct: '0%',   color: '#eaeff4', texto: '' },
    { pct: '25%',  color: '#e74c3c', texto: 'Muy débil' },
    { pct: '50%',  color: '#e67e22', texto: 'Débil' },
    { pct: '70%',  color: '#f1c40f', texto: 'Aceptable' },
    { pct: '85%',  color: '#2ecc71', texto: 'Fuerte' },
    { pct: '100%', color: '#27ae60', texto: 'Muy fuerte' },
  ];

  const nivel = niveles[Math.min(puntos, 5)];
  fill.style.width      = nivel.pct;
  fill.style.background = nivel.color;
  label.textContent     = nivel.texto;
  label.style.color     = nivel.color;
}

function comprobarCoincidencia() {
  const nueva     = document.getElementById('nueva').value;
  const confirmar = document.getElementById('confirmar').value;
  const label     = document.getElementById('match-label');

  if (!confirmar) {
    label.textContent = '';
    return;
  }

  if (nueva === confirmar) {
    label.textContent = '✓ Las contraseñas coinciden';
    label.style.color = '#27ae60';
  } else {
    label.textContent = '✗ Las contraseñas no coinciden';
    label.style.color = '#e74c3c';
  }
}
</script>

</body>
</html>