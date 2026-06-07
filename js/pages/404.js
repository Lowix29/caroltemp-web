/* CarolTemp 404 — olas + parallax + ripple */
(function () {
  'use strict';

  /* ─── CANVAS WAVE (bottom of page) ─── */
  var canvas = document.getElementById('e404Canvas');
  if (canvas) {
    var ctx = canvas.getContext('2d');
    var t = 0;

    function resize() {
      var dpr = window.devicePixelRatio || 1;
      canvas.width  = canvas.offsetWidth  * dpr;
      canvas.height = canvas.offsetHeight * dpr;
      ctx.scale(dpr, dpr);
    }

    function wave(w, h, amp, freq, phase, alpha) {
      ctx.beginPath();
      ctx.moveTo(0, h);
      for (var x = 0; x <= w; x += 2) {
        var y = h * 0.55
              + Math.sin(x * freq       + t + phase)          * amp
              + Math.sin(x * freq * 0.6 + t * 1.4 + phase)    * (amp * 0.4)
              + Math.sin(x * freq * 0.3 + t * 0.7 + phase)    * (amp * 0.2);
        ctx.lineTo(x, y);
      }
      ctx.lineTo(w, h);
      ctx.closePath();
      ctx.fillStyle = 'rgba(25,118,210,' + alpha + ')';
      ctx.fill();
    }

    function draw() {
      var w = canvas.offsetWidth;
      var h = canvas.offsetHeight;
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      wave(w, h, 9,  0.013, 0,    0.13);
      wave(w, h, 7,  0.019, 1.3,  0.10);
      wave(w, h, 5,  0.026, 2.7,  0.07);
      t += 0.022;
      requestAnimationFrame(draw);
    }

    resize();
    window.addEventListener('resize', resize, { passive: true });
    draw();
  }

  /* ─── MOUSE PARALLAX ON ORBS ─── */
  var orbs = document.querySelectorAll('.e404-orb');
  if (orbs.length && window.matchMedia('(pointer: fine)').matches) {
    var cx = window.innerWidth  / 2;
    var cy = window.innerHeight / 2;

    window.addEventListener('resize', function () {
      cx = window.innerWidth  / 2;
      cy = window.innerHeight / 2;
    }, { passive: true });

    document.addEventListener('mousemove', function (e) {
      var dx = (e.clientX - cx) / cx;
      var dy = (e.clientY - cy) / cy;
      orbs.forEach(function (orb, i) {
        var depth = (i + 1) * 20;
        orb.style.transform = 'translate(' + (dx * depth) + 'px,' + (dy * depth) + 'px)';
      });
    }, { passive: true });
  }

  /* ─── CURSOR CLICK RIPPLE ─── */
  var rippleStyle = document.createElement('style');
  rippleStyle.textContent = [
    '@keyframes e404clickRipple{',
    '  0%  { transform:scale(1);   opacity:1; }',
    '  100%{ transform:scale(5);   opacity:0; }',
    '}'
  ].join('');
  document.head.appendChild(rippleStyle);

  document.addEventListener('click', function (e) {
    var el = document.createElement('div');
    var size = 40;
    el.style.cssText = [
      'position:fixed',
      'top:'    + (e.clientY - size / 2) + 'px',
      'left:'   + (e.clientX - size / 2) + 'px',
      'width:'  + size + 'px',
      'height:' + size + 'px',
      'border-radius:50%',
      'border:2px solid rgba(25,118,210,.7)',
      'pointer-events:none',
      'z-index:9999',
      'animation:e404clickRipple .65s ease forwards',
    ].join(';');
    document.body.appendChild(el);
    setTimeout(function () { el.remove(); }, 700);
  }, { passive: true });

})();
