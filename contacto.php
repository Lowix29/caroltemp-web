<?php
$meta_title  = "Contacto — CarolTemp | Fontanero en el Vinalopó";
$meta_desc   = "Contacta con CarolTemp para pedir presupuesto de fontanería en Elda, Petrer, Novelda, Monóvar y toda la comarca del Vinalopó. Llamada, WhatsApp o formulario.";
$meta_url    = "https://caroltemp.com/contacto.php";
$schema_type = "contacto";
$page_css    = "contacto";
$page_js     = "contacto";

require_once 'includes/db.php';

$form_ok    = false;
$form_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre     = trim($_POST['nombre']    ?? '');
  $telefono   = trim($_POST['telefono']  ?? '');
  $zona       = trim($_POST['zona']      ?? '');
  $servicio   = trim($_POST['servicio']  ?? '');
  $mensaje    = trim($_POST['mensaje']   ?? '');
  $privacidad = isset($_POST['privacidad']);

  if (!$nombre || !$telefono || !$zona || !$servicio || !$privacidad) {
    $form_error = 'Por favor rellena todos los campos obligatorios.';
  } else {
    try {
      $stmt = $pdo->prepare('INSERT INTO mensajes (nombre, telefono, zona, servicio, mensaje) VALUES (?, ?, ?, ?, ?)');
      $stmt->execute([$nombre, $telefono, $zona, $servicio, $mensaje]);
      $form_ok = true;
    } catch (PDOException $e) {
      $form_error = 'Error al enviar el mensaje. Por favor inténtalo de nuevo.';
    }
  }
}

include 'includes/head.php';
?>

<!-- HERO -->
<section class="hz-dark" style="min-height:340px">
  <div class="hz-dark-bg"></div>
  <div class="hz-dark-glow"></div>
  <div class="hz-dark-con">
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>Contacto</div>
    <h1>¿Hablamos<span class="hl"> sin compromiso?</span></h1>
    <p class="hz-dark-sub">Cuéntanos qué necesitas y te respondemos lo antes posible.</p>
  </div>
</section>

<!-- CONTACTO PRINCIPAL -->
<section style="padding:5rem 0">
  <div style="max-width:1100px;margin:0 auto;padding:0 var(--space-md)">
    <div class="contacto-inner">

      <!-- FORMULARIO -->
      <div>
        <p class="zona-lbl">Formulario</p>
        <h2 style="font-size:clamp(1.5rem,3vw,2rem);font-weight:800;color:#0d1f33;letter-spacing:-.025em;margin-bottom:.5rem">Pide tu <span style="color:#3b82f6">presupuesto</span></h2>
        <p style="color:#64748b;font-size:14px;margin-bottom:1.75rem">Te respondemos en menos de 24 horas.</p>

        <?php if ($form_ok): ?>
          <div class="form-success visible">
            ✅ Mensaje enviado correctamente. Te contactamos pronto.
          </div>
        <?php else: ?>

          <?php if ($form_error): ?>
            <div style="background:#fff1f2;border:1px solid #fecdd3;border-radius:8px;padding:.85rem 1.25rem;color:#dc2626;font-size:14px;margin-bottom:1.25rem">
              <?php echo htmlspecialchars($form_error); ?>
            </div>
          <?php endif; ?>

          <form class="contacto-form" id="contacto-form" method="POST" action="" novalidate>

            <div class="form-group">
              <label for="nombre">Nombre</label>
              <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required autocomplete="name">
              <span class="form-error" id="error-nombre"></span>
            </div>

            <div class="form-group">
              <label for="telefono">Teléfono</label>
              <input type="tel" id="telefono" name="telefono" placeholder="Tu teléfono de contacto" required autocomplete="tel">
              <span class="form-error" id="error-telefono"></span>
            </div>

            <div class="form-group">
              <label for="zona">¿Dónde estás?</label>
              <select id="zona" name="zona" required>
                <option value="">Selecciona tu municipio</option>
                <option value="Elda">Elda</option>
                <option value="Petrer">Petrer</option>
                <option value="Novelda">Novelda</option>
                <option value="Monóvar">Monóvar</option>
                <option value="Sax">Sax</option>
                <option value="Pinoso">Pinoso</option>
                <option value="Monforte del Cid">Monforte del Cid</option>
                <option value="Salinas">Salinas</option>
                <option value="Otra">Otra localidad</option>
              </select>
              <span class="form-error" id="error-zona"></span>
            </div>

            <div class="form-group">
              <label for="servicio">¿Qué necesitas?</label>
              <select id="servicio" name="servicio" required>
                <option value="">Selecciona el servicio</option>
                <option value="Reparación urgente">Reparación urgente</option>
                <option value="Cambio de grifo">Cambio de grifo</option>
                <option value="Instalación de termo Nubeco">Instalación de termo Nubeco</option>
                <option value="Ósmosis inversa">Ósmosis inversa</option>
                <option value="Descalcificador">Descalcificador</option>
                <option value="Reforma de baño">Reforma de baño</option>
                <option value="Bomba de achique">Bomba de achique</option>
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

            <button type="submit" class="btn btn-primary contacto-submit">Enviar solicitud</button>

          </form>
        <?php endif; ?>
      </div>

      <!-- INFO -->
      <div>
        <p class="zona-lbl">Otras formas de contacto</p>
        <h2 style="font-size:clamp(1.5rem,3vw,2rem);font-weight:800;color:#0d1f33;letter-spacing:-.025em;margin-bottom:.5rem">Contacta <span style="color:#3b82f6">directamente</span></h2>
        <p style="color:#64748b;font-size:14px;margin-bottom:1.75rem">Si lo prefieres, llámanos o escríbenos por WhatsApp.</p>

        <div class="contacto-metodos">
          <a href="tel:+34611165129" class="contacto-metodo">
            <div class="contacto-metodo-ico">📞</div>
            <div class="contacto-metodo-texto">
              <strong>Llamada</strong>
              <span>611 165 129</span>
              <small>Lun–Vie 8:00–20:00 · Sáb 9:00–14:00</small>
            </div>
          </a>
          <a href="https://wa.me/34611165129?text=Hola,%20me%20gustaría%20pedir%20un%20presupuesto" target="_blank" rel="noopener" class="contacto-metodo">
            <div class="contacto-metodo-ico">💬</div>
            <div class="contacto-metodo-texto">
              <strong>WhatsApp</strong>
              <span>611 165 129</span>
              <small>Te respondemos lo antes posible</small>
            </div>
          </a>
          <a href="mailto:info@caroltemp.es" class="contacto-metodo">
            <div class="contacto-metodo-ico">✉️</div>
            <div class="contacto-metodo-texto">
              <strong>Email</strong>
              <span>info@caroltemp.es</span>
              <small>Respuesta en menos de 24 horas</small>
            </div>
          </a>
        </div>

        <div style="margin-top:2rem">
          <p class="zona-lbl" style="margin-bottom:1rem">Zona de servicio</p>
          <div class="zona-ztags" style="margin-top:0">
            <a href="<?php echo $base_url; ?>zonas/elda.php"     class="zona-ztag">Elda</a>
            <a href="<?php echo $base_url; ?>zonas/petrer.php"   class="zona-ztag">Petrer</a>
            <a href="<?php echo $base_url; ?>zonas/novelda.php"  class="zona-ztag">Novelda</a>
            <a href="<?php echo $base_url; ?>zonas/monovar.php"  class="zona-ztag">Monóvar</a>
            <a href="<?php echo $base_url; ?>zonas/sax.php"      class="zona-ztag">Sax</a>
            <a href="<?php echo $base_url; ?>zonas/pinoso.php"   class="zona-ztag">Pinoso</a>
            <a href="<?php echo $base_url; ?>zonas/monforte.php" class="zona-ztag">Monforte del Cid</a>
            <a href="<?php echo $base_url; ?>zonas/salinas.php"  class="zona-ztag">Salinas</a>
          </div>
        </div>

        <div style="margin-top:2rem">
          <p class="zona-lbl" style="margin-bottom:1rem">Horario</p>
          <div class="horario-grid">
            <div class="horario-item">
              <span class="horario-dia">Lunes — Viernes</span>
              <span class="horario-hora">8:00 — 20:00</span>
            </div>
            <div class="horario-item">
              <span class="horario-dia">Sábado</span>
              <span class="horario-hora">9:00 — 14:00</span>
            </div>
            <div class="horario-item">
              <span class="horario-dia">Domingo</span>
              <span class="horario-hora">Cerrado</span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>Presupuesto <span>sin compromiso</span></h2>
    <p>Cuéntanos qué necesitas. Te respondemos enseguida.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">📞 Llamar ahora</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">💬 WhatsApp</a>
    </div>
    <div class="cta-dark-tel">Teléfono directo<strong>611 165 129</strong></div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>