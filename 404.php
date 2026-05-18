<?php
http_response_code(404);
$meta_title  = "Página no encontrada — CarolTemp";
$meta_desc   = "La página que buscas no existe. Vuelve al inicio o contacta con CarolTemp, tu fontanero en el Vinalopó.";
$meta_url    = "https://caroltemp.com/404.php";
$schema_type = "default";
$page_css    = "";
$page_js     = "";

include 'includes/head.php';
?>

<section class="error-404">
  <div class="error-404-inner">
    <div class="error-404-num">404</div>
    <h1 class="error-404-titulo">Página <span>no encontrada</span></h1>
    <p class="error-404-desc">La página que buscas no existe o ha sido movida. Prueba desde el inicio o contacta con nosotros directamente.</p>
    <div class="error-404-btns">
      <a href="<?php echo $base_url; ?>" class="btn-hz-w">← Volver al inicio</a>
      <a href="tel:+34611165129" class="btn-hz-g">📞 611 165 129</a>
    </div>
    <div class="error-404-links">
      <a href="<?php echo $base_url; ?>servicios.php">Servicios</a>
      <a href="<?php echo $base_url; ?>zonas.php">Zonas</a>
      <a href="<?php echo $base_url; ?>blog/">Blog</a>
      <a href="<?php echo $base_url; ?>contacto.php">Contacto</a>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>