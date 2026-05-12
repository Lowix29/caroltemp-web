<?php
session_start();

// Si ya está logado va al panel
if (isset($_SESSION['admin_logado']) && $_SESSION['admin_logado'] === true) {
  header('Location: index.php');
  exit;
}

require_once '../includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $usuario  = trim($_POST['usuario'] ?? '');
  $password = trim($_POST['password'] ?? '');

  if ($usuario && $password) {
    $stmt = $pdo->prepare('SELECT * FROM admin_usuarios WHERE usuario = ? LIMIT 1');
    $stmt->execute([$usuario]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
      $_SESSION['admin_logado']  = true;
      $_SESSION['admin_usuario'] = $user['usuario'];
      header('Location: index.php');
      exit;
    } else {
      $error = 'Usuario o contraseña incorrectos.';
    }
  } else {
    $error = 'Rellena todos los campos.';
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin — CarolTemp</title>
  <meta name="robots" content="noindex, nofollow">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      background: #f0f4f8;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }
    .login-wrap {
      background: #fff;
      border: 1px solid #dde6f0;
      border-radius: 12px;
      padding: 2.5rem 2rem;
      width: 100%;
      max-width: 380px;
      box-shadow: 0 4px 24px rgba(0,0,0,0.07);
    }
    .login-logo {
      text-align: center;
      margin-bottom: 2rem;
    }
    .login-logo img {
      height: 48px;
      width: auto;
    }
    .login-logo span {
      display: block;
      color: #1e3a5f;
      font-size: 13px;
      font-weight: 500;
      margin-top: 8px;
      letter-spacing: 0.05em;
      text-transform: uppercase;
    }
    h1 {
      color: #1e3a5f;
      font-size: 20px;
      font-weight: 600;
      margin-bottom: 1.5rem;
      text-align: center;
    }
    .form-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
      margin-bottom: 1rem;
    }
    label {
      color: #3a4a5c;
      font-size: 13.5px;
      font-weight: 500;
    }
    input {
      border: 1px solid #dde6f0;
      border-radius: 6px;
      padding: 11px 14px;
      font-size: 14px;
      color: #0d1f33;
      width: 100%;
      transition: border-color 0.15s;
    }
    input:focus {
      outline: none;
      border-color: #1e3a5f;
    }
    .error {
      background: #fdf0f0;
      border: 1px solid #f0c0c0;
      border-radius: 6px;
      padding: 0.75rem 1rem;
      color: #c0392b;
      font-size: 13.5px;
      margin-bottom: 1rem;
      text-align: center;
    }
    button {
      width: 100%;
      background: #1e3a5f;
      color: #fff;
      border: none;
      border-radius: 6px;
      padding: 13px;
      font-size: 15px;
      font-weight: 500;
      cursor: pointer;
      margin-top: 0.5rem;
      transition: opacity 0.15s;
    }
    button:hover { opacity: 0.88; }
  </style>
</head>
<body>

<div class="login-wrap">
  <div class="login-logo">
    <img src="../img/logo/logo.webp" alt="CarolTemp" onerror="this.style.display='none'">
    <span>Panel de administración</span>
  </div>

  <h1>Acceder</h1>

  <?php if ($error): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <div class="form-group">
      <label for="usuario">Usuario</label>
      <input
        type="text"
        id="usuario"
        name="usuario"
        required
        autocomplete="username"
        value="<?php echo htmlspecialchars($_POST['usuario'] ?? ''); ?>"
      >
    </div>
    <div class="form-group">
      <label for="password">Contraseña</label>
      <input
        type="password"
        id="password"
        name="password"
        required
        autocomplete="current-password"
      >
    </div>
    <button type="submit">Entrar</button>
  </form>
</div>

</body>
</html>