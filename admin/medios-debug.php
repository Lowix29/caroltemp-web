<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Debug Medios</title>
<style>
body{font-family:monospace;padding:2rem;background:#f1f5f9}
h2{color:#1e3a5f;margin:1.5rem 0 .5rem}
pre{background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:1rem;overflow-x:auto;font-size:13px}
.ok{color:#16a34a;font-weight:bold}.err{color:#dc2626;font-weight:bold}
button{background:#1e3a5f;color:#fff;border:none;border-radius:6px;padding:.5rem 1.2rem;cursor:pointer;font-size:14px;margin:.25rem}
.result{margin-top:1rem;padding:1rem;background:#fff;border:1px solid #e2e8f0;border-radius:8px;white-space:pre-wrap;font-size:13px}
</style>
</head>
<body>

<h1>🔧 Debug — medios-ajax.php</h1>

<h2>1. Sesión</h2>
<pre><?php
echo 'admin_logado = ' . var_export($_SESSION['admin_logado'] ?? null, true) . "\n";
echo 'admin_usuario = ' . var_export($_SESSION['admin_usuario'] ?? null, true) . "\n";
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  echo '<span class="err">⚠️  SESIÓN NO VÁLIDA — no podrás hacer llamadas AJAX</span>';
} else {
  echo '<span class="ok">✓ Sesión OK</span>';
}
?>
</pre>

<h2>2. Tests AJAX directos</h2>
<p>Pulsa cada botón — el resultado aparece debajo.</p>

<button onclick="test('list')">Test: list</button>
<button onclick="test('delete_cero')">Test: delete id=0</button>
<button onclick="test('delete_real')">Test: delete id=99999</button>
<button onclick="testFormData()">Test: FormData delete</button>
<button onclick="testURL()">Test: URLSearchParams delete</button>
<button onclick="testRaw()">Test: RAW body delete</button>

<div id="result" class="result">— pulsa un botón —</div>

<h2>3. PHP info relevante</h2>
<pre><?php
echo 'PHP version:        ' . phpversion() . "\n";
echo 'post_max_size:      ' . ini_get('post_max_size') . "\n";
echo 'request_order:      ' . ini_get('request_order') . "\n";
echo 'variables_order:    ' . ini_get('variables_order') . "\n";
echo 'output_buffering:   ' . ini_get('output_buffering') . "\n";
echo 'display_errors:     ' . ini_get('display_errors') . "\n";
echo '__DIR__ admin:      ' . __DIR__ . "\n";
echo 'dirname(__DIR__):   ' . dirname(__DIR__) . "\n";
echo 'img/uploads exists: ' . (is_dir(dirname(__DIR__).'/img/uploads') ? 'SI' : 'NO') . "\n";
?>
</pre>

<h2>4. ¿Existe medios-ajax.php?</h2>
<pre><?php
$ajaxFile = __DIR__ . '/medios-ajax.php';
if (file_exists($ajaxFile)) {
  echo '<span class="ok">✓ Existe: ' . $ajaxFile . '</span>' . "\n";
  echo 'Tamaño: ' . filesize($ajaxFile) . " bytes\n";
  echo 'Permisos: ' . substr(sprintf('%o', fileperms($ajaxFile)), -4) . "\n";
} else {
  echo '<span class="err">✗ NO EXISTE: ' . $ajaxFile . '</span>';
}
?>
</pre>

<script>
var AJAX = 'medios-ajax.php';
var res  = document.getElementById('result');

function mostrar(label, data) {
  res.textContent = '=== ' + label + ' ===\n' + JSON.stringify(data, null, 2);
}
function mostrarRaw(label, text) {
  res.textContent = '=== ' + label + ' (raw) ===\n' + text;
}

function test(tipo) {
  var body, label;
  if (tipo === 'list') {
    body  = new URLSearchParams({action:'list'});
    label = 'list';
  } else if (tipo === 'delete_cero') {
    body  = new URLSearchParams({action:'delete', id:'0'});
    label = 'delete id=0';
  } else if (tipo === 'delete_real') {
    body  = new URLSearchParams({action:'delete', id:'99999'});
    label = 'delete id=99999 (no existe → ok:true esperado)';
  }

  res.textContent = 'Enviando ' + label + '...';
  fetch(AJAX, {method:'POST', body:body})
    .then(function(r) {
      res.textContent += '\nHTTP status: ' + r.status + ' ' + r.statusText;
      return r.text();
    })
    .then(function(t) {
      mostrarRaw(label, t);
      try { mostrar(label + ' (parsed)', JSON.parse(t)); }
      catch(e) { res.textContent += '\n\n⚠️  No es JSON válido!'; }
    })
    .catch(function(e) { res.textContent = 'Fetch error: ' + e.message; });
}

function testFormData() {
  var fd = new FormData();
  fd.append('action','delete'); fd.append('id','99999');
  res.textContent = 'Enviando FormData delete...';
  fetch(AJAX, {method:'POST', body:fd})
    .then(function(r) {
      res.textContent += '\nHTTP ' + r.status;
      return r.text();
    })
    .then(function(t) { mostrarRaw('FormData delete', t); })
    .catch(function(e) { res.textContent = 'Error: ' + e.message; });
}

function testURL() {
  var fd = new URLSearchParams();
  fd.append('action','delete'); fd.append('id','99999');
  res.textContent = 'Enviando URLSearchParams delete...';
  fetch(AJAX, {method:'POST', body:fd})
    .then(function(r) {
      res.textContent += '\nHTTP ' + r.status;
      return r.text();
    })
    .then(function(t) { mostrarRaw('URLSearchParams delete', t); })
    .catch(function(e) { res.textContent = 'Error: ' + e.message; });
}

function testRaw() {
  res.textContent = 'Enviando raw body...';
  fetch(AJAX, {
    method:'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: 'action=delete&id=99999'
  })
    .then(function(r) {
      res.textContent += '\nHTTP ' + r.status;
      return r.text();
    })
    .then(function(t) { mostrarRaw('Raw body', t); })
    .catch(function(e) { res.textContent = 'Error: ' + e.message; });
}
</script>
</body>
</html>
