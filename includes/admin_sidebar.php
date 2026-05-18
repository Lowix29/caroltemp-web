<?php
// Detectar página activa
$pagina_actual = basename($_SERVER['PHP_SELF']);
$total_nuevos_msg = isset($pdo) ? $pdo->query('SELECT COUNT(*) FROM mensajes WHERE leido = 0')->fetchColumn() : 0;
?>
<aside class="sidebar">
  <div class="sidebar-logo">
    <img src="<?php echo $base_url; ?>caroltemp/img/logo/logo.svg" alt="CarolTemp" onerror="this.style.display='none'">
    <span>Panel admin</span>
  </div>
  <nav class="sidebar-nav">
    <span class="sidebar-label">General</span>
    <a href="index.php" class="<?php echo $pagina_actual === 'index.php' ? 'active' : ''; ?>">
      <span>📊</span> Inicio
    </a>
    <a href="<?php echo $base_url; ?>/" target="_blank">
      <span>🌐</span> Ver web
    </a>
    <a href="mensajes.php" class="<?php echo $pagina_actual === 'mensajes.php' ? 'active' : ''; ?>">
  <span>✉️</span> Mensajes
  <?php if ($total_nuevos_msg > 0): ?>
    <span style="background:#dc2626;color:#fff;font-size:10px;font-weight:700;padding:1px 7px;border-radius:20px;margin-left:auto"><?php echo $total_nuevos_msg; ?></span>
  <?php endif; ?>
</a>
    <span class="sidebar-label">Páginas</span>
    <a href="nueva-pagina.php" class="<?php echo $pagina_actual === 'nueva-pagina.php' ? 'active' : ''; ?>">
      <span>📄</span> Nueva página
    </a>
    <a href="paginas.php" class="<?php echo $pagina_actual === 'paginas.php' ? 'active' : ''; ?>">
      <span>📋</span> Todas las páginas
    </a>
    <span class="sidebar-label">Artículos</span>
    <a href="nuevo-articulo.php" class="<?php echo $pagina_actual === 'nuevo-articulo.php' ? 'active' : ''; ?>">
      <span>✏️</span> Nuevo artículo
    </a>
    <a href="articulos.php" class="<?php echo $pagina_actual === 'articulos.php' ? 'active' : ''; ?>">
      <span>📋</span> Todos los artículos
    </a>
    <span class="sidebar-label">Proyectos</span>
    <a href="nuevo-proyecto.php" class="<?php echo $pagina_actual === 'nuevo-proyecto.php' ? 'active' : ''; ?>">
      <span>🔧</span> Nuevo proyecto
    </a>
    <a href="proyectos.php" class="<?php echo $pagina_actual === 'proyectos.php' ? 'active' : ''; ?>">
      <span>📋</span> Todos los proyectos
    </a>
    <span class="sidebar-label">Agente IA</span>
    <a href="auditor-estrategico.php" class="<?php echo $pagina_actual === 'auditor-estrategico.php' ? 'active' : ''; ?>" style="background:<?php echo $pagina_actual === 'auditor-estrategico.php' ? '' : 'linear-gradient(135deg,rgba(11,36,71,.04),rgba(168,85,247,.08))'; ?>">
      <span>🧠</span> Auditor Estratégico
    </a>
    <a href="agente-paginas.php" class="<?php echo $pagina_actual === 'agente-paginas.php' ? 'active' : ''; ?>" style="background:<?php echo $pagina_actual === 'agente-paginas.php' ? '' : 'linear-gradient(135deg,rgba(11,36,71,.04),rgba(25,118,210,.06))'; ?>">
      <span>🗂️</span> Agente Páginas
    </a>
    <a href="agente-seo.php" class="<?php echo $pagina_actual === 'agente-seo.php' ? 'active' : ''; ?>" style="background:<?php echo $pagina_actual === 'agente-seo.php' ? '' : 'linear-gradient(135deg,rgba(11,36,71,.04),rgba(25,118,210,.06))'; ?>">
      <span>✍️</span> Agente SEO (Contenido)
    </a>
    <span class="sidebar-label">Herramientas</span>
<a href="categorias.php" class="<?php echo $pagina_actual === 'categorias.php' ? 'active' : ''; ?>">
  <span>🏷️</span> Categorías y servicios
</a>
<a href="sitemap.php" class="<?php echo $pagina_actual === 'sitemap.php' ? 'active' : ''; ?>">
  <span>🗺️</span> Sitemap
</a>
<a href="password.php" class="<?php echo $pagina_actual === 'password.php' ? 'active' : ''; ?>">
  <span>🔒</span> Contraseña
</a>
  </nav>
  <div class="sidebar-bottom">
    <a href="logout.php"><span>👋</span> Cerrar sesión — <?php echo htmlspecialchars($_SESSION['admin_usuario'] ?? ''); ?></a>
  </div>
</aside>