<?php
/* =============================================
   CAROLTEMP — Auditor SEO completo
   Backend API — devuelve JSON
============================================= */
ob_start();
error_reporting(0);
ini_set('display_errors', 0);

session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  ob_end_clean();
  http_response_code(401);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(['error' => 'No autorizado']);
  exit;
}

require_once '../includes/db.php';
require_once '../includes/config.php';

if (!defined('ANTHROPIC_MODEL')) define('ANTHROPIC_MODEL', 'claude-sonnet-4-5');

ob_end_clean();
header('Content-Type: application/json; charset=utf-8');

// ── Verificaciones básicas ──────────────────────────────────────────────────
if (!function_exists('curl_init')) {
  echo json_encode(['error' => 'curl no está habilitado en PHP.']);
  exit;
}
if (!defined('ANTHROPIC_API_KEY') || strlen(ANTHROPIC_API_KEY) < 20) {
  echo json_encode(['error' => 'API key no configurada. Revisa includes/config.php']);
  exit;
}

// ── Helpers ─────────────────────────────────────────────────────────────────
function palabras_contenido($html) {
  return str_word_count(strip_tags($html ?? ''));
}

function flags_meta($meta_title, $meta_desc, $publicado = 1) {
  $mt_len = mb_strlen($meta_title ?? '');
  $md_len = mb_strlen($meta_desc  ?? '');
  return [
    'meta_title_len'   => $mt_len,
    'meta_desc_len'    => $md_len,
    'sin_meta_title'   => $mt_len === 0,
    'sin_meta_desc'    => $md_len === 0,
    'meta_title_largo' => $mt_len > 60,
    'meta_desc_corta'  => $md_len < 140 && $md_len > 0,
    'meta_desc_larga'  => $md_len > 165,
    'no_publicado'     => (int)$publicado === 0,
  ];
}

function build_item($slug, $titulo, $url, $meta_title, $meta_desc, $contenido, $publicado, $tipo) {
  $palabras = palabras_contenido($contenido);
  $flags    = flags_meta($meta_title, $meta_desc, $publicado);
  $flags['contenido_delgado'] = $palabras < 300;
  $flags['palabras'] = $palabras;
  return array_merge([
    'tipo'       => $tipo,
    'slug'       => $slug,
    'titulo'     => $titulo,
    'url'        => $url,
    'meta_title' => $meta_title ?? '',
    'meta_desc'  => $meta_desc  ?? '',
  ], $flags);
}

// ── PASO 1: Inventario desde BD ─────────────────────────────────────────────
$inventario = [];

// Artículos
try {
  $arts = $pdo->query(
    'SELECT slug, titulo, meta_title, meta_desc, contenido, publicado FROM articulos ORDER BY id'
  )->fetchAll(PDO::FETCH_ASSOC);
  foreach ($arts as $a) {
    $inventario[] = build_item(
      $a['slug'], $a['titulo'],
      '/blog/' . $a['slug'],
      $a['meta_title'], $a['meta_desc'],
      $a['contenido'], $a['publicado'],
      'articulo'
    );
  }
} catch (Exception $e) {}

// Proyectos
try {
  $projs = $pdo->query(
    'SELECT slug, titulo, meta_title, meta_desc, contenido, publicado FROM proyectos ORDER BY id'
  )->fetchAll(PDO::FETCH_ASSOC);
  foreach ($projs as $p) {
    $inventario[] = build_item(
      $p['slug'], $p['titulo'],
      '/proyectos/' . $p['slug'],
      $p['meta_title'], $p['meta_desc'],
      $p['contenido'], $p['publicado'],
      'proyecto'
    );
  }
} catch (Exception $e) {}

// ── PASO 2: Escanear páginas estáticas PHP ───────────────────────────────────
$dirs_estaticos = [
  '/tmp/caroltemp-web/zonas'       => '/zonas',
  '/tmp/caroltemp-web/fugas'       => '/fugas',
  '/tmp/caroltemp-web/desatascos'  => '/desatascos',
  '/tmp/caroltemp-web/fontanero'   => '/fontanero',
];

$extra_files = [
  '/tmp/caroltemp-web/servicios.php' => '/servicios',
  '/tmp/caroltemp-web/index.php'     => '/',
];

$archivos_estaticos = [];

foreach ($dirs_estaticos as $dir_fs => $dir_web) {
  $files = glob($dir_fs . '/*.php');
  if ($files) {
    foreach ($files as $f) {
      $archivos_estaticos[$f] = $dir_web;
    }
  }
}

foreach ($extra_files as $f => $url_base) {
  $archivos_estaticos[$f] = $url_base;
}

foreach ($archivos_estaticos as $filepath => $url_base) {
  if (!file_exists($filepath)) continue;

  $contenido_php = file_get_contents($filepath);

  // Extraer meta_title
  $mt = '';
  if (preg_match('/\$meta_title\s*=\s*["\']([^"\']+)["\']/', $contenido_php, $m)) {
    $mt = $m[1];
  }
  // Extraer meta_desc
  $md = '';
  if (preg_match('/\$meta_desc\s*=\s*["\']([^"\']+)["\']/', $contenido_php, $m)) {
    $md = $m[1];
  }

  // Construir URL
  $basename = basename($filepath, '.php');
  if ($url_base === '/zonas' || $url_base === '/fugas' || $url_base === '/desatascos' || $url_base === '/fontanero') {
    if ($basename === 'index') {
      $url = $url_base;
    } else {
      $url = $url_base . '/' . $basename;
    }
  } elseif ($url_base === '/servicios' || $url_base === '/') {
    $url = $url_base;
  } else {
    $url = $url_base . '/' . $basename;
  }

  // El contenido textual de las páginas estáticas es el PHP completo (excluimos tags)
  $texto_plano = strip_tags($contenido_php);
  $palabras    = str_word_count($texto_plano);
  $flags       = flags_meta($mt, $md, 1);
  $flags['contenido_delgado'] = $palabras < 300;
  $flags['palabras']          = $palabras;

  $inventario[] = array_merge([
    'tipo'       => 'estatica',
    'slug'       => $basename,
    'titulo'     => $mt ?: $basename,
    'url'        => $url,
    'meta_title' => $mt,
    'meta_desc'  => $md,
  ], $flags);
}

// ── PASO 3: Construir resumen compacto para Claude ───────────────────────────
$lineas = [];
foreach ($inventario as $item) {
  $flags_activos = [];
  foreach (['sin_meta_title','sin_meta_desc','meta_title_largo','meta_desc_corta','meta_desc_larga','contenido_delgado','no_publicado'] as $f) {
    if (!empty($item[$f])) $flags_activos[] = $f;
  }
  $flags_str = $flags_activos ? implode(',', $flags_activos) : 'ok';
  $lineas[] = sprintf(
    '[%s] url=%s | mt=%d | md=%d | words=%d | flags=%s',
    $item['tipo'],
    $item['url'],
    $item['meta_title_len'],
    $item['meta_desc_len'],
    $item['palabras'],
    $flags_str
  );
}

$resumen_inventario = implode("\n", $lineas);

// Recolectar zonas presentes en páginas estáticas y artículos
$zonas_conocidas  = ['Elda','Petrer','Novelda','Monóvar','Sax','Pinoso','Monforte del Cid','Salinas','Aspe','Villena'];
$zonas_con_pagina = [];
foreach ($inventario as $item) {
  foreach ($zonas_conocidas as $z) {
    $z_slug = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $z));
    if (strpos($item['url'], $z_slug) !== false || stripos($item['titulo'], $z) !== false) {
      $zonas_con_pagina[] = $z;
    }
  }
}
$zonas_con_pagina = array_unique($zonas_con_pagina);

// ── PASO 4: Llamar a Claude ──────────────────────────────────────────────────
$system_prompt = 'Eres un auditor SEO experto para CarolTemp, empresa de fontanería y climatización en Alicante (España). '
  . 'Analiza el inventario de páginas del sitio web y devuelve ÚNICAMENTE JSON válido (sin markdown, sin bloques de código, sin texto antes ni después del JSON). '
  . 'El JSON debe comenzar directamente con { y terminar con }. '
  . 'Zonas de servicio conocidas: ' . implode(', ', $zonas_conocidas) . '. '
  . 'Zonas con página detectadas: ' . (implode(', ', $zonas_con_pagina) ?: 'ninguna') . '.';

$user_prompt = "Inventario completo del sitio CarolTemp (" . count($inventario) . " páginas):\n\n"
  . "Formato: [tipo] url | mt=longitud_meta_title | md=longitud_meta_desc | words=palabras | flags=problemas\n\n"
  . $resumen_inventario . "\n\n"
  . "Analiza y devuelve JSON con esta estructura exacta:\n"
  . '{"resumen":{"total_paginas":N,"problemas_criticos":N,"problemas_medios":N,"oportunidades":N},'
  . '"problemas":[{"prioridad":"alta|media|baja","tipo":"meta_ausente|contenido_delgado|canibalizacion|duplicado|meta_largo|no_publicado|estructura","url":"/ruta","titulo":"Título","descripcion":"Qué falla","accion":"Qué hacer"}],'
  . '"oportunidades":[{"tipo":"pagina_zona_faltante|servicio_faltante|cluster_contenido|interlinking","descripcion":"Qué falta","url_sugerida":"/zonas/aspe","impacto":"alto|medio"}],'
  . '"canibalizacion":[{"urls":["/blog/slug-1","/blog/slug-2"],"keyword_compartida":"término","recomendacion":"qué hacer"}],'
  . '"estado_zonas":{"tienen_pagina":["Elda"],"faltan":["Aspe"]},'
  . '"seo_notas":"Resumen ejecutivo de 3-5 líneas del estado SEO del sitio"}';

$payload = [
  'model'      => ANTHROPIC_MODEL,
  'max_tokens' => 4000,
  'system'     => $system_prompt,
  'messages'   => [
    ['role' => 'user',      'content' => $user_prompt],
    ['role' => 'assistant', 'content' => '{'],
  ],
];

$ch = curl_init('https://api.anthropic.com/v1/messages');
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST           => true,
  CURLOPT_POSTFIELDS     => json_encode($payload),
  CURLOPT_HTTPHEADER     => [
    'Content-Type: application/json',
    'x-api-key: ' . ANTHROPIC_API_KEY,
    'anthropic-version: 2023-06-01',
  ],
  CURLOPT_TIMEOUT        => 90,
]);

$resp_raw = curl_exec($ch);
$curl_err  = curl_error($ch);
curl_close($ch);

if ($curl_err) {
  echo json_encode(['error' => 'Error de conexión con la API: ' . $curl_err]);
  exit;
}

$resp = json_decode($resp_raw, true);
if (!$resp || !isset($resp['content'][0]['text'])) {
  echo json_encode(['error' => 'Respuesta inválida de la API', 'raw' => substr($resp_raw, 0, 500)]);
  exit;
}

// Verificar max_tokens
if (isset($resp['stop_reason']) && $resp['stop_reason'] === 'max_tokens') {
  echo json_encode(['error' => 'La auditoría es demasiado extensa y se cortó. Reduce el inventario o aumenta max_tokens.']);
  exit;
}

// Reconstruir JSON (prefill trick: Claude empezó con "{")
$texto_respuesta = '{' . $resp['content'][0]['text'];

$analisis = json_decode($texto_respuesta, true);
if (!$analisis) {
  echo json_encode(['error' => 'La IA devolvió JSON malformado', 'raw' => substr($texto_respuesta, 0, 800)]);
  exit;
}

// ── Respuesta final ──────────────────────────────────────────────────────────
echo json_encode([
  'ok'               => true,
  'inventario_count' => count($inventario),
  'analisis'         => $analisis,
]);
