/* ================================
   CAROLTEMP — Cookie Banner
================================ */

(function() {

  const COOKIE_NAME = 'caroltemp_cookies';
  const COOKIE_DAYS = 365;

  function getCookie(name) {
    const cookies = document.cookie.split(';');
    for (let c of cookies) {
      const [k, v] = c.trim().split('=');
      if (k === name) return v;
    }
    return null;
  }

  function setCookie(name, value, days) {
    const d = new Date();
    d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
    document.cookie = name + '=' + value + ';expires=' + d.toUTCString() + ';path=/;SameSite=Lax';
  }

  function hideBanner() {
    const banner  = document.getElementById('cookie-banner');
    const overlay = document.getElementById('cookie-overlay');
    if (banner)  { banner.classList.add('cookie-hide');  setTimeout(() => banner.remove(),  400); }
    if (overlay) { overlay.classList.add('cookie-hide'); setTimeout(() => overlay.remove(), 400); }
    document.body.style.overflow = '';
  }

  function showPreferences() {
    document.getElementById('cookie-banner-main').style.display  = 'none';
    document.getElementById('cookie-banner-prefs').style.display = 'block';
  }

  function hidePreferences() {
    document.getElementById('cookie-banner-main').style.display  = 'block';
    document.getElementById('cookie-banner-prefs').style.display = 'none';
  }

  function acceptAll() {
    setCookie(COOKIE_NAME, 'all', COOKIE_DAYS);
    hideBanner();
  }

  function rejectAll() {
    setCookie(COOKIE_NAME, 'necessary', COOKIE_DAYS);
    hideBanner();
  }

  function savePreferences() {
    const analytics = document.getElementById('cookie-analytics').checked;
    setCookie(COOKIE_NAME, analytics ? 'all' : 'necessary', COOKIE_DAYS);
    hideBanner();
  }

  function createBanner() {

    // OVERLAY
    const overlay = document.createElement('div');
    overlay.id = 'cookie-overlay';
    document.body.appendChild(overlay);

    // BANNER
    const banner = document.createElement('div');
    banner.id = 'cookie-banner';
    banner.innerHTML = `
      <div class="cb-wrap">
        <div id="cookie-banner-main">
          <div class="cb-top">
            <div class="cb-logo">
              <span class="cb-logo-ico">🍪</span>
              <span class="cb-logo-txt">CarolTemp · Cookies</span>
            </div>
          </div>
          <div class="cb-body">
            <p class="cb-title">Respetamos tu privacidad</p>
            <p class="cb-desc">Usamos cookies propias para el funcionamiento del sitio y cookies analíticas para entender cómo lo usas. Puedes aceptar todas, rechazar las opcionales o personalizar tu elección.</p>
            <a href="/cookies.php" class="cb-link" target="_blank">Política de cookies →</a>
          </div>
          <div class="cb-actions">
            <button class="cb-btn cb-btn-prefs" onclick="showPreferences()">Personalizar</button>
            <button class="cb-btn cb-btn-reject" onclick="rejectAll()">Rechazar</button>
            <button class="cb-btn cb-btn-reject" onclick="rejectAll()">Solo necesarias</button>
            <button class="cb-btn cb-btn-accept" onclick="acceptAll()">Aceptar todas</button>
          </div>
        </div>
        <div id="cookie-banner-prefs" style="display:none">
          <div class="cb-top">
            <button class="cb-back" onclick="hidePreferences()">← Volver</button>
            <span class="cb-logo-txt">Personalizar cookies</span>
          </div>
          <div class="cb-prefs-list">
            <div class="cb-pref-item">
              <div class="cb-pref-info">
                <span class="cb-pref-nombre">Cookies necesarias</span>
                <span class="cb-pref-desc">Imprescindibles para el funcionamiento del sitio. No se pueden desactivar.</span>
              </div>
              <div class="cb-toggle cb-toggle-on cb-toggle-disabled"><span></span></div>
            </div>
            <div class="cb-pref-item">
              <div class="cb-pref-info">
                <span class="cb-pref-nombre">Cookies analíticas</span>
                <span class="cb-pref-desc">Nos ayudan a entender cómo usas el sitio para mejorarlo. Google Analytics.</span>
              </div>
              <label class="cb-toggle-wrap">
                <input type="checkbox" id="cookie-analytics" checked>
                <span class="cb-toggle"><span></span></span>
              </label>
            </div>
          </div>
          <div class="cb-actions">
            <button class="cb-btn cb-btn-reject" onclick="rejectAll()">Solo necesarias</button>
            <button class="cb-btn cb-btn-accept" onclick="savePreferences()">Guardar preferencias</button>
          </div>
        </div>
      </div>
    `;

    document.body.appendChild(banner);
    document.body.style.overflow = 'hidden';

    window.showPreferences = showPreferences;
    window.hidePreferences = hidePreferences;
    window.acceptAll       = acceptAll;
    window.rejectAll       = rejectAll;
    window.savePreferences = savePreferences;

    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        overlay.classList.add('cookie-show');
        banner.classList.add('cookie-show');
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function() {
    if (!getCookie(COOKIE_NAME)) {
      createBanner();
    }
  });

})();