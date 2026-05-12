<?php
$meta_title  = "Política de cookies — Hidrofont";
$meta_desc   = "Política de cookies de Hidrofont. Información sobre los tipos de cookies utilizadas en el sitio web y cómo gestionarlas.";
$meta_url    = "https://hidrofont.es/cookies.php";
$schema_type = "default";
$page_css    = "legal";
$page_js     = "";

include 'includes/head.php';
?>

<section class="zona-hero">
  <div class="container">
    <div class="badge">
      <span class="badge-dot"></span>
      Legal
    </div>
    <h1>Política de cookies</h1>
    <p>Información sobre las cookies utilizadas en este sitio web y cómo gestionarlas.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="legal-wrap">

      <div class="legal-bloque">
        <h2>1. ¿Qué son las cookies?</h2>
        <p>Las cookies son pequeños archivos de texto que los sitios web almacenan en tu dispositivo cuando los visitas. Sirven para que el sitio web recuerde información sobre tu visita, como tu idioma preferido y otras opciones, lo que puede facilitar tu próxima visita.</p>
      </div>

      <div class="legal-bloque">
        <h2>2. Tipos de cookies que utilizamos</h2>

        <div class="cookies-tabla">
          <div class="cookies-fila cookies-cabecera">
            <span>Nombre</span>
            <span>Tipo</span>
            <span>Duración</span>
            <span>Finalidad</span>
          </div>
          <div class="cookies-fila">
            <span>PHPSESSID</span>
            <span>Técnica</span>
            <span>Sesión</span>
            <span>Mantiene la sesión del usuario durante la navegación</span>
          </div>
          <div class="cookies-fila">
            <span>_ga</span>
            <span>Analítica</span>
            <span>2 años</span>
            <span>Google Analytics — distingue usuarios únicos</span>
          </div>
          <div class="cookies-fila">
            <span>_gid</span>
            <span>Analítica</span>
            <span>24 horas</span>
            <span>Google Analytics — distingue usuarios</span>
          </div>
          <div class="cookies-fila">
            <span>_gat</span>
            <span>Analítica</span>
            <span>1 minuto</span>
            <span>Google Analytics — limita la tasa de solicitudes</span>
          </div>
        </div>
      </div>

      <div class="legal-bloque">
        <h2>3. Cookies de terceros</h2>
        <p>Este sitio web puede utilizar servicios de terceros que instalan sus propias cookies. En particular:</p>
        <ul>
          <li><strong>Google Analytics:</strong> servicio de análisis web de Google Inc. Puedes consultar su política de privacidad en <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">policies.google.com/privacy</a>.</li>
          <li><strong>Google Maps:</strong> servicio de mapas de Google Inc. utilizado para mostrar nuestra ubicación.</li>
          <li><strong>WhatsApp:</strong> el botón de contacto por WhatsApp puede instalar cookies de Meta Platforms Inc.</li>
        </ul>
      </div>

      <div class="legal-bloque">
        <h2>4. Cómo gestionar las cookies</h2>
        <p>Puedes permitir, bloquear o eliminar las cookies instaladas en tu dispositivo desde la configuración de tu navegador:</p>
        <ul>
          <li><a href="https://support.google.com/chrome/answer/95647" target="_blank" rel="noopener">Google Chrome</a></li>
          <li><a href="https://support.mozilla.org/es/kb/habilitar-y-deshabilitar-cookies" target="_blank" rel="noopener">Mozilla Firefox</a></li>
          <li><a href="https://support.apple.com/es-es/guide/safari/sfri11471/mac" target="_blank" rel="noopener">Safari</a></li>
          <li><a href="https://support.microsoft.com/es-es/microsoft-edge/eliminar-las-cookies-en-microsoft-edge" target="_blank" rel="noopener">Microsoft Edge</a></li>
        </ul>
        <p>Ten en cuenta que deshabilitar las cookies puede afectar al correcto funcionamiento del sitio web.</p>
      </div>

      <div class="legal-bloque">
        <h2>5. Modificaciones</h2>
        <p>Nos reservamos el derecho a modificar esta política de cookies para adaptarla a cambios legislativos o del servicio. Última actualización: <?php echo date('d/m/Y'); ?>.</p>
      </div>

    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>