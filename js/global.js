/* CAROLTEMP — Global JS */

/* NAV — clase scrolled al bajar */
(function() {
  var nav = document.querySelector('.nav');
  if (!nav) return;
  function onScroll() { nav.classList.toggle('scrolled', window.scrollY > 10); }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
})();

/* Smooth scroll — anclas internas */
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
    anchor.addEventListener('click', function(e) {
      var target = document.querySelector(this.getAttribute('href'));
      if (!target) return;
      e.preventDefault();
      var navH = (document.querySelector('.nav') || {}).offsetHeight || 0;
      window.scrollTo({ top: target.getBoundingClientRect().top + window.scrollY - navH - 20, behavior: 'smooth' });
    });
  });
});
