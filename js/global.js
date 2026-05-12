/* ================================
   CAROLTEMP — Global JS
================================ */

/* --------------------------------
   MENÚ MÓVIL
-------------------------------- */
function toggleMenu(btn) {
  const menu    = document.getElementById('nav-mobile');
  const overlay = document.getElementById('nav-overlay');
  const isOpen  = menu.classList.contains('open');

  menu.classList.toggle('open');
  if (overlay) overlay.classList.toggle('open');
  btn.classList.toggle('open');
  btn.setAttribute('aria-expanded', String(!isOpen));
  btn.setAttribute('aria-label', isOpen ? 'Abrir menú' : 'Cerrar menú');

  // Bloquear scroll del body cuando menú está abierto
  document.body.style.overflow = isOpen ? '' : 'hidden';
}

// Cerrar menú al hacer clic fuera (fallback)
document.addEventListener('click', function(e) {
  const nav    = document.querySelector('.nav');
  const menu   = document.getElementById('nav-mobile');
  const overlay = document.getElementById('nav-overlay');

  if (nav && !nav.contains(e.target) && overlay && !overlay.contains(e.target) && menu && menu.classList.contains('open')) {
    if (typeof closeNavMobile === 'function') closeNavMobile();
    document.body.style.overflow = '';
  }
});

// Cerrar menú al cambiar tamaño de ventana
window.addEventListener('resize', function() {
  if (window.innerWidth > 768) {
    const menu   = document.getElementById('nav-mobile');
    const toggle = document.querySelector('.nav-toggle');
    if (menu && menu.classList.contains('open')) {
      menu.classList.remove('open');
      toggle.setAttribute('aria-expanded', 'false');
      toggle.querySelectorAll('span').forEach(s => {
        s.style.transform = '';
        s.style.opacity   = '';
      });
    }
  }
});

/* --------------------------------
   NAV — clase scrolled al bajar
-------------------------------- */
(function() {
  const nav = document.querySelector('.nav');
  if (!nav) return;
  function onScroll() {
    nav.classList.toggle('scrolled', window.scrollY > 10);
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
})();

/* --------------------------------
   NAV — marcar enlace activo
-------------------------------- */
document.addEventListener('DOMContentLoaded', function() {
  const links = document.querySelectorAll('.nav-links a, .nav-mobile a');
  const path  = window.location.pathname;

  links.forEach(link => {
    const href = link.getAttribute('href');
    if (href && href !== '/' && path.startsWith(href)) {
      link.classList.add('active');
    } else if (href === '/' && path === '/') {
      link.classList.add('active');
    }
  });
});

/* --------------------------------
   SMOOTH SCROLL — anclas internas
-------------------------------- */
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const target = document.querySelector(this.getAttribute('href'));
      if (!target) return;
      e.preventDefault();
      const navHeight = document.querySelector('.nav')?.offsetHeight || 0;
      const top = target.getBoundingClientRect().top + window.scrollY - navHeight - 20;
      window.scrollTo({ top, behavior: 'smooth' });
    });
  });
});

/* --------------------------------
   WHATSAPP — botón flotante
-------------------------------- */
document.addEventListener('DOMContentLoaded', function() {
  const wa = document.createElement('a');
  wa.href      = 'https://wa.me/34613429032?text=Hola,%20me%20gustaría%20pedir%20un%20presupuesto';
  wa.target    = '_blank';
  wa.rel       = 'noopener noreferrer';
  wa.className = 'wa-float';
  wa.setAttribute('aria-label', 'Contactar por WhatsApp');
  wa.innerHTML = `
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="26" height="26">
      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
      <path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.117 1.523 5.847L.057 23.882a.5.5 0 0 0 .614.612l6.094-1.596A11.942 11.942 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.894a9.892 9.892 0 0 1-5.031-1.371l-.361-.214-3.741.98.999-3.648-.235-.374A9.865 9.865 0 0 1 2.106 12C2.106 6.533 6.533 2.106 12 2.106S21.894 6.533 21.894 12 17.467 21.894 12 21.894z"/>
    </svg>
  `;
  document.body.appendChild(wa);
});

/* Toggle mobile dropdown groups */
function toggleMobileGroup(e, el) {
  e.preventDefault();
  var group = el.parentElement;
  group.classList.toggle('open');
}