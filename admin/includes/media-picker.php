<?php
/**
 * Bloque "Imagen destacada" estilo WordPress.
 *
 * Uso:
 *   $mp_campo  = 'imagen';           // name del input hidden
 *   $mp_valor  = $art['imagen'];     // valor actual (ruta relativa o '')
 *   $mp_base   = $base_url;          // base URL para previsualizar
 *   include 'includes/media-picker.php';
 */
$mp_tiene = !empty($mp_valor);
$mp_thumb = $mp_tiene ? htmlspecialchars($mp_base . $mp_valor) : '';
?>
<div class="mp-wrap" id="mp-wrap-<?php echo $mp_campo; ?>">

  <!-- Imagen actual -->
  <div class="mp-preview" id="mp-preview-<?php echo $mp_campo; ?>"
       style="display:<?php echo $mp_tiene ? 'block' : 'none'; ?>">
    <div class="mp-preview-inner">
      <img id="mp-thumb-<?php echo $mp_campo; ?>" src="<?php echo $mp_thumb; ?>" alt="">
      <div class="mp-preview-info">
        <span id="mp-titulo-<?php echo $mp_campo; ?>" class="mp-file-name"></span>
        <span id="mp-alt-<?php echo $mp_campo; ?>" class="mp-alt-txt"></span>
      </div>
    </div>
    <button type="button" class="mp-remove-btn"
      onclick="mpQuitar('<?php echo $mp_campo; ?>')">Eliminar imagen destacada</button>
  </div>

  <!-- Sin imagen -->
  <div class="mp-empty" id="mp-empty-<?php echo $mp_campo; ?>"
       style="display:<?php echo $mp_tiene ? 'none' : 'flex'; ?>">
    <span class="mp-empty-ico">🖼️</span>
    <span class="mp-empty-txt">No hay imagen destacada</span>
  </div>

  <!-- Botón principal -->
  <button type="button" class="mp-open-btn" id="mp-btn-<?php echo $mp_campo; ?>"
    onclick="mpAbrir('<?php echo $mp_campo; ?>')">
    <?php echo $mp_tiene ? '🔄 Cambiar imagen destacada' : '📷 Establecer imagen destacada'; ?>
  </button>

  <!-- Input oculto con la ruta -->
  <input type="hidden" name="<?php echo htmlspecialchars($mp_campo); ?>"
         id="mp-val-<?php echo $mp_campo; ?>"
         value="<?php echo htmlspecialchars($mp_valor ?? ''); ?>">
</div>
