<?php
$meta_title  = "Contacto — CarolTemp | Fontanero en el Vinalopó";
$meta_desc   = "Contacta con CarolTemp para pedir presupuesto de fontanería en Elda, Petrer, Novelda, Monóvar y toda la comarca del Vinalopó. Llamada, WhatsApp o formulario.";
$meta_url    = "https://caroltemp.com/contacto";
$schema_type = "contacto";
$page_css    = "contacto";
$page_js     = "contacto";

require_once 'includes/db.php';

$form_ok    = false;
$form_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre     = trim($_POST['nombre']    ?? '');
  $telefono   = trim($_POST['telefono']  ?? '');
  $servicio   = trim($_POST['servicio']  ?? '');
  $mensaje    = trim($_POST['mensaje']   ?? '');
  $privacidad = isset($_POST['privacidad']);

  if (!$nombre || !$telefono || !$servicio || !$privacidad) {
    $form_error = 'Por favor rellena todos los campos obligatorios.';
  } else {
    try {
      $stmt = $pdo->prepare('INSERT INTO mensajes (nombre, telefono, zona, servicio, mensaje) VALUES (?, ?, ?, ?, ?)');
      $stmt->execute([$nombre, $telefono, '', $servicio, $mensaje]);
      $form_ok = true;
    } catch (PDOException $e) {
      $form_error = 'Error al enviar el mensaje. Por favor inténtalo de nuevo.';
    }
  }
}

include 'includes/head.php';
?>

<!-- HERO -->
<section class="hz-dark" style="min-height:360px">
  <div class="hz-dark-bg"></div>
  <div class="hz-dark-glow"></div>
  <div class="hz-dark-con">
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Contacto · CarolTemp</div>
    <h1>Cuéntanos qué necesitas<br><span class="hl">y te respondemos hoy</span></h1>
    <p class="hz-dark-sub">Llámanos, escríbenos por WhatsApp o rellena el formulario. Respondemos en menos de 24 horas.</p>
  </div>
</section>

<!-- TRUST STRIP -->
<div class="ctrust-strip">
  <div class="ctrust-strip-in">
    <div class="ctrust-item"><span class="ctrust-ico">⚡</span><span>Respuesta el mismo día</span></div>
    <div class="ctrust-item"><span class="ctrust-ico">📍</span><span>Toda la comarca del Vinalopó</span></div>
    <div class="ctrust-item"><span class="ctrust-ico">🔧</span><span>Urgencias 24h</span></div>
    <div class="ctrust-item"><span class="ctrust-ico">✅</span><span>Trabajo garantizado</span></div>
  </div>
</div>

<!-- CONTACTO PRINCIPAL -->
<section class="ctact-section">
  <div class="ctact-wrap">

    <!-- COLUMNA IZQUIERDA: MÉTODOS DIRECTOS -->
    <div class="ctact-col-info">

      <p class="zona-lbl">Contacto directo</p>
      <h2 class="ctact-h2">Hablamos <span style="color:#3b82f6">ahora mismo</span></h2>
      <p class="ctact-sub">Sin esperas, sin formularios. Llama o escríbenos y te atendemos al momento.</p>

      <div class="ctact-cards">
        <a href="tel:+34611165129" class="ctact-card ctact-card-phone">
          <div class="ctact-card-ico">📞</div>
          <div class="ctact-card-body">
            <strong>Llamar ahora</strong>
            <span>611 165 129</span>
            <small>Lun–Vie 8:00–20:00 · Sáb 9:00–14:00</small>
          </div>
          <div class="ctact-card-arrow">→</div>
        </a>
        <a href="https://wa.me/34611165129?text=Hola,%20me%20gustaría%20pedir%20información" target="_blank" rel="noopener" class="ctact-card ctact-card-wa">
          <div class="ctact-card-ico">💬</div>
          <div class="ctact-card-body">
            <strong>WhatsApp</strong>
            <span>611 165 129</span>
            <small>Respondemos lo antes posible</small>
          </div>
          <div class="ctact-card-arrow">→</div>
        </a>
        <a href="mailto:info@caroltemp.es" class="ctact-card ctact-card-email">
          <div class="ctact-card-ico">✉️</div>
          <div class="ctact-card-body">
            <strong>Email</strong>
            <span>info@caroltemp.es</span>
            <small>Respuesta en menos de 24 horas</small>
          </div>
          <div class="ctact-card-arrow">→</div>
        </a>
      </div>

      <!-- HORARIO -->
      <div class="ctact-horario">
        <p class="zona-lbl" style="margin-bottom:.85rem">Horario de atención</p>
        <div class="ctact-horario-grid">
          <div class="ctact-horario-row">
            <span>Lunes — Viernes</span>
            <span class="ctact-horario-val">8:00 — 20:00</span>
          </div>
          <div class="ctact-horario-row">
            <span>Sábado</span>
            <span class="ctact-horario-val">9:00 — 14:00</span>
          </div>
          <div class="ctact-horario-row">
            <span>Urgencias</span>
            <span class="ctact-horario-val ctact-horario-urg">24h · 7 días</span>
          </div>
        </div>
      </div>

    </div>

    <!-- COLUMNA DERECHA: FORMULARIO -->
    <div class="ctact-col-form">

      <div class="ctact-form-card">
        <p class="zona-lbl">Formulario</p>
        <h2 class="ctact-h2">Solicita <span style="color:#3b82f6">información</span></h2>
        <p class="ctact-sub">Te respondemos en menos de 24 horas.</p>

        <?php if ($form_ok): ?>
          <div class="form-success visible">
            ✅ Mensaje enviado correctamente. Te contactamos pronto.
          </div>
        <?php else: ?>

          <?php if ($form_error): ?>
            <div class="form-error-box">
              <?php echo htmlspecialchars($form_error); ?>
            </div>
          <?php endif; ?>

          <form class="contacto-form" id="contacto-form" method="POST" action="" novalidate>

            <div class="form-row-2">
              <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required autocomplete="name">
                <span class="form-error" id="error-nombre"></span>
              </div>
              <div class="form-group">
                <label for="telefono">Teléfono *</label>
                <input type="tel" id="telefono" name="telefono" placeholder="Tu teléfono" required autocomplete="tel">
                <span class="form-error" id="error-telefono"></span>
              </div>
            </div>

            <div class="form-group">
              <label for="servicio">¿Qué necesitas? *</label>
              <select id="servicio" name="servicio" required>
                <option value="">Selecciona el servicio</option>
                <option value="Reparación urgente">Reparación urgente</option>
                <option value="Detección de fugas">Detección de fugas</option>
                <option value="Desatasco">Desatasco</option>
                <option value="Cambio de grifo">Cambio de grifo</option>
                <option value="Instalación de termo Nubeco">Instalación de termo Nubeco</option>
                <option value="Ósmosis inversa">Ósmosis inversa</option>
                <option value="Descalcificador">Descalcificador</option>
                <option value="Reforma de baño">Reforma de baño</option>
                <option value="Financiación">Información sobre financiación</option>
                <option value="Otro">Otro</option>
              </select>
              <span class="form-error" id="error-servicio"></span>
            </div>

            <div class="form-group">
              <label for="mensaje">Cuéntanos más <span class="form-opcional">(opcional)</span></label>
              <textarea id="mensaje" name="mensaje" rows="4" placeholder="Describe brevemente lo que necesitas..."></textarea>
            </div>

            <div class="form-check">
              <input type="checkbox" id="privacidad" name="privacidad" required>
              <label for="privacidad">He leído y acepto la <a href="<?php echo $base_url; ?>privacidad.php" target="_blank">política de privacidad</a></label>
              <span class="form-error" id="error-privacidad"></span>
            </div>

            <button type="submit" class="btn btn-primary contacto-submit">Enviar solicitud →</button>

          </form>
        <?php endif; ?>
      </div>

    </div>

  </div>
</section>

<!-- CTA -->
<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>¿Tienes una avería <span>ahora mismo?</span></h2>
    <p>Llámanos directamente. Atendemos urgencias todos los días.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">📞 611 165 129</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">💬 WhatsApp</a>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
