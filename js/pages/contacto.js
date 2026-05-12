/* ================================
   CAROLTEMP — Contacto JS
================================ */

document.addEventListener('DOMContentLoaded', function() {

  const form = document.getElementById('contacto-form');
  if (!form) return;

  /* Validación antes de enviar */
  form.addEventListener('submit', function(e) {
    let valido = true;

    // Limpiar errores previos
    form.querySelectorAll('.form-error').forEach(el => el.textContent = '');
    form.querySelectorAll('input, select, textarea').forEach(el => el.classList.remove('error'));

    // Nombre
    const nombre = document.getElementById('nombre');
    if (!nombre.value.trim()) {
      document.getElementById('error-nombre').textContent = 'Este campo es obligatorio.';
      nombre.classList.add('error');
      valido = false;
    }

    // Teléfono
    const telefono = document.getElementById('telefono');
    if (!telefono.value.trim()) {
      document.getElementById('error-telefono').textContent = 'Este campo es obligatorio.';
      telefono.classList.add('error');
      valido = false;
    } else {
      const tel = telefono.value.replace(/\s/g, '');
      if (!/^[6789]\d{8}$/.test(tel)) {
        document.getElementById('error-telefono').textContent = 'Introduce un teléfono válido.';
        telefono.classList.add('error');
        valido = false;
      }
    }

    // Zona
    const zona = document.getElementById('zona');
    if (!zona.value) {
      document.getElementById('error-zona').textContent = 'Selecciona tu municipio.';
      zona.classList.add('error');
      valido = false;
    }

    // Servicio
    const servicio = document.getElementById('servicio');
    if (!servicio.value) {
      document.getElementById('error-servicio').textContent = 'Selecciona el servicio.';
      servicio.classList.add('error');
      valido = false;
    }

    // Privacidad
    const privacidad = document.getElementById('privacidad');
    if (!privacidad.checked) {
      document.getElementById('error-privacidad').textContent = 'Debes aceptar la política de privacidad.';
      valido = false;
    }

    // Si no es válido no envía
    if (!valido) {
      e.preventDefault();
    }
  });

});