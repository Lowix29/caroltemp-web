/* CarolTemp 404 — animaciones JS: burbujas RAF, olas canvas, parallax, ripple */
(function () {
  'use strict';

  /* ─── BUBBLES via requestAnimationFrame ───────────────────────────
     Creamos los elementos en JS y los movemos con RAF para evitar
     cualquier conflicto de CSS animation-duration / overflow / browser
  ──────────────────────────────────────────────────────────────────── */
  var bubbleWrap = document.querySelector('.e404-bubbles');
  if (bubbleWrap) {
    var SIZES  = [16, 32, 10, 24, 18, 40, 14, 28, 20, 45, 12, 22, 36, 48, 8];
    var SPEEDS = [7, 5, 9, 6, 7, 4, 8, 5.5, 7, 4.5, 8, 6, 5, 4, 10]; /* vh/s */
    var bubbles = [];

    for (var i = 0; i < 15; i++) {
      var el = document.createElement('div');
      var sz = SIZES[i];
      el.style.cssText = [
        'position:absolute',
        'top:0', 'left:0',
        'width:'  + sz + 'px',
        'height:' + sz + 'px',
        'border-radius:50%',
        'background:rgba(25,118,210,.2)',
        'border:1px solid rgba(100,180,255,.4)',
        'will-change:transform,opacity',
        'pointer-events:none',
        'opacity:0',
      ].join(';');
      bubbleWrap.appendChild(el);

      bubbles.push({
        el:    el,
        x:     Math.random() * 94,          /* vw, start position */
        y:     105 + Math.random() * 20,    /* vh, below viewport */
        vy:    -(SPEEDS[i] + Math.random() * 2), /* upward speed vh/s */
        vx:    (Math.random() - 0.5) * 0.4,      /* slight horizontal drift */
        size:  sz,
      });
    }

    /* stagger starts — space them across the screen at begin */
    bubbles.forEach(function (b, i) {
      b.y = 100 - (i / 15) * 120; /* spread from 100vh to -20vh */
    });

    var lastTs = null;

    function rafBubbles(ts) {
      if (!lastTs) lastTs = ts;
      var dt = Math.min((ts - lastTs) / 1000, 0.1);
      lastTs = ts;

      bubbles.forEach(function (b) {
        b.y += b.vy * dt;
        b.x += b.vx * dt;

        /* reset to bottom when off top */
        if (b.y < -15) {
          b.y  = 105 + Math.random() * 15;
          b.x  = Math.random() * 94;
          b.vy = -(SPEEDS[Math.floor(Math.random() * 15)] + Math.random() * 2);
          b.vx = (Math.random() - 0.5) * 0.4;
        }

        /* opacity: fade in from bottom (y>90), steady mid, fade out top (y<5) */
        var op;
        if      (b.y > 95) { op = Math.max(0, (105 - b.y) / 10) * 0.85; }
        else if (b.y < 10) { op = Math.max(0, b.y / 10) * 0.85; }
        else                { op = 0.85; }

        b.el.style.opacity   = op.toFixed(3);
        b.el.style.transform = 'translate(' + b.x.toFixed(2) + 'vw,' + b.y.toFixed(2) + 'vh)';
      });

      requestAnimationFrame(rafBubbles);
    }

    requestAnimationFrame(rafBubbles);
  }

  /* ─── CANVAS WAVE (bottom) ──────────────────────────────────────── */
  var canvas = document.getElementById('e404Canvas');
  if (canvas) {
    var ctx = canvas.getContext('2d');
    var wt  = 0;

    function resizeCanvas() {
      var dpr = window.devicePixelRatio || 1;
      canvas.width  = canvas.offsetWidth  * dpr;
      canvas.height = canvas.offsetHeight * dpr;
      ctx.scale(dpr, dpr);
    }

    function drawWave(cw, ch, amp, freq, phase, alpha) {
      ctx.beginPath();
      ctx.moveTo(0, ch);
      for (var x = 0; x <= cw; x += 2) {
        var y = ch * 0.55
              + Math.sin(x * freq        + wt + phase)         * amp
              + Math.sin(x * freq * 0.6  + wt * 1.4 + phase)   * (amp * 0.4)
              + Math.sin(x * freq * 0.3  + wt * 0.7 + phase)   * (amp * 0.2);
        ctx.lineTo(x, y);
      }
      ctx.lineTo(cw, ch);
      ctx.closePath();
      ctx.fillStyle = 'rgba(25,118,210,' + alpha + ')';
      ctx.fill();
    }

    function rafWave() {
      var cw = canvas.offsetWidth;
      var ch = canvas.offsetHeight;
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      drawWave(cw, ch, 9,  0.013, 0,   0.14);
      drawWave(cw, ch, 7,  0.019, 1.3, 0.10);
      drawWave(cw, ch, 5,  0.026, 2.7, 0.07);
      wt += 0.022;
      requestAnimationFrame(rafWave);
    }

    resizeCanvas();
    window.addEventListener('resize', resizeCanvas, { passive: true });
    rafWave();
  }

  /* ─── MOUSE PARALLAX EN ORBS ────────────────────────────────────── */
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
      orbs.forEach(function (o, i) {
        var d = (i + 1) * 20;
        o.style.transform = 'translate(' + (dx * d) + 'px,' + (dy * d) + 'px)';
      });
    }, { passive: true });
  }

  /* ─── CLICK RIPPLE ──────────────────────────────────────────────── */
  var rippleStyle = document.createElement('style');
  rippleStyle.textContent = '@keyframes e404cr{0%{transform:scale(1);opacity:1}100%{transform:scale(5);opacity:0}}';
  document.head.appendChild(rippleStyle);

  document.addEventListener('click', function (e) {
    var el = document.createElement('div');
    el.style.cssText = [
      'position:fixed',
      'top:'    + (e.clientY - 20) + 'px',
      'left:'   + (e.clientX - 20) + 'px',
      'width:40px', 'height:40px',
      'border-radius:50%',
      'border:2px solid rgba(25,118,210,.7)',
      'pointer-events:none',
      'z-index:9999',
      'animation:e404cr .65s ease forwards',
    ].join(';');
    document.body.appendChild(el);
    setTimeout(function () { el.remove(); }, 700);
  }, { passive: true });

})();
