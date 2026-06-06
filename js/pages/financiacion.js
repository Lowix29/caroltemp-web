document.addEventListener('DOMContentLoaded', function () {
  var range   = document.getElementById('sim-range');
  var amtVal  = document.getElementById('sim-amount-val');
  var result  = document.getElementById('sim-result');
  var months  = 24;

  function fmt(n) {
    return n.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
  }

  function fmtAmt(n) {
    return n.toLocaleString('es-ES') + ' €';
  }

  function update() {
    var amount = parseInt(range.value, 10);
    amtVal.textContent = fmtAmt(amount);
    result.textContent = fmt(amount / months);
  }

  range.addEventListener('input', update);

  document.getElementById('sim-months').addEventListener('click', function (e) {
    var btn = e.target.closest('.fin-sim-mth');
    if (!btn) return;
    document.querySelectorAll('.fin-sim-mth').forEach(function (b) {
      b.classList.remove('fin-sim-mth-active');
    });
    btn.classList.add('fin-sim-mth-active');
    months = parseInt(btn.dataset.m, 10);
    update();
  });

  update();
});
