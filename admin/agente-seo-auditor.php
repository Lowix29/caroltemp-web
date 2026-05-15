<?php
/* =============================================
   CAROLTEMP — Auditor SEO completo v2
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

if (!function_exists('curl_init')) {
  echo json_encode(['error' => 'curl no está habilitado en PHP.']);
  exit;
}
if (!defined('ANTHROPIC_API_KEY') || strlen(ANTHROPIC_API_KEY) < 20) {
  echo json_encode(['error' => 'API key no configurada en includes/config.php']);
  exit;
}

$root = dirname(__DIR__); // ruta absoluta a la raíz del sitio

// Keywords del usuario (pegadas en el textarea, una por línea)
$keywords_raw = trim($_POST['keywords'] ?? '');
$keywords_usuario = $keywords_raw
  ? array_filter(array_map('trim', preg_split('/[\r\n,;]+/', $keywords_raw)))
  : [];

// ── ZONAS DE SERVICIO (sin Villena) ─────────────────────────────────────────
$zonas_servicio = [
  'Elda'            => 'elda',
  'Petrer'          => 'petrer',
  'Novelda'         => 'novelda',
  'Monóvar'         => 'monovar',
  'Sax'             => 'sax',
  'Pinoso'          => 'pinoso',
  'Monforte del Cid'=> 'monforte',
  'Salinas'         => 'salinas',
  'Aspe'            => 'aspe',
];

// ── SERVICIOS ESTÁTICOS POR ZONA ─────────────────────────────────────────────
// Patrón de archivo para cada tipo de servicio por zona
$servicios_estaticos = [
  'Página zona'   => fn($s) => $root . "/zonas/{$s}.php",
  'Fugas'         => fn($s) => $root . "/fugas/deteccion-fugas-{$s}.php",
  'Desatascos'    => fn($s) => $root . "/desatascos/desatascos-{$s}.php",
  'Fontanero'     => fn($s) => $root . "/fontanero/fontanero-{$s}.php",
];

// ── CONSTRUIR MATRIZ ZONA × SERVICIO ─────────────────────────────────────────
$matriz = [];
foreach ($zonas_servicio as $nombre => $slug) {
  $fila = ['zona' => $nombre, 'slug' => $slug, 'servicios' => []];
  foreach ($servicios_estaticos as $servicio => $pathFn) {
    $fila['servicios'][$servicio] = file_exists($pathFn($slug));
  }
  $matriz[] = $fila;
}

// ── LEER PÁGINAS ESTÁTICAS Y EXTRAER META ────────────────────────────────────
function extraer_meta_php($filepath) {
  if (!file_exists($filepath)) return ['meta_title' => '', 'meta_desc' => '', 'palabras' => 0];
  $src = file_get_contents($filepath);
  $mt = $md = '';
  if (preg_match('/\$meta_title\s*=\s*["\']([^"\']+)["\']/', $src, $m)) $mt = $m[1];
  if (preg_match('/\$meta_desc\s*=\s*["\']([^"\']+)["\']/',  $src, $m)) $md = $m[1];
  $palabras = str_word_count(strip_tags(preg_replace('/<\?php.*?\?>/s', '', $src)));
  return ['meta_title' => $mt, 'meta_desc' => $md, 'palabras' => $palabras];
}

$paginas_estaticas = [];

// Todas las páginas de zona y servicio
foreach ($zonas_servicio as $nombre => $slug) {
  foreach ($servicios_estaticos as $servicio => $pathFn) {
    $path = $pathFn($slug);
    if (!file_exists($path)) continue;
    $meta = extraer_meta_php($path);
    $url  = str_replace($root, '', $path);
    $url  = str_replace('.php', '', $url);
    $paginas_estaticas[] = [
      'url'        => $url,
      'titulo'     => $meta['meta_title'] ?: "{$servicio} en {$nombre}",
      'meta_title' => $meta['meta_title'],
      'meta_desc'  => $meta['meta_desc'],
      'palabras'   => $meta['palabras'],
      'zona'       => $nombre,
      'servicio'   => $servicio,
    ];
  }
}

// Páginas generales
$generales = [
  $root . '/index.php'    => '/',
  $root . '/servicios.php'=> '/servicios',
  $root . '/sobre-nosotros.php' => '/sobre-nosotros',
  $root . '/contacto.php' => '/contacto',
];
foreach ($generales as $path => $url) {
  if (!file_exists($path)) continue;
  $meta = extraer_meta_php($path);
  $paginas_estaticas[] = [
    'url'        => $url,
    'titulo'     => $meta['meta_title'] ?: basename($url),
    'meta_title' => $meta['meta_title'],
    'meta_desc'  => $meta['meta_desc'],
    'palabras'   => $meta['palabras'],
    'zona'       => '',
    'servicio'   => 'general',
  ];
}

// ── ARTÍCULOS Y PROYECTOS DE LA BD ───────────────────────────────────────────
$articulos = $proyectos = [];
try {
  $articulos = $pdo->query(
    'SELECT slug, titulo, zona, categoria, meta_title, meta_desc, contenido, publicado, created_at FROM articulos ORDER BY id'
  )->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}

try {
  $proyectos = $pdo->query(
    'SELECT slug, titulo, zona, servicio, meta_title, meta_desc, contenido, publicado, created_at FROM proyectos ORDER BY id'
  )->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}

function analizar_entrada($row, $tipo, $base_url) {
  $palabras = str_word_count(strip_tags($row['contenido'] ?? ''));
  $mt       = $row['meta_title'] ?? '';
  $md       = $row['meta_desc']  ?? '';
  $problemas = [];
  if (!$mt)                       $problemas[] = 'sin_meta_title';
  if (mb_strlen($mt) > 60)        $problemas[] = 'meta_title_largo('.mb_strlen($mt).'c)';
  if (!$md)                       $problemas[] = 'sin_meta_desc';
  if ($md && mb_strlen($md) < 140)$problemas[] = 'meta_desc_corta('.mb_strlen($md).'c)';
  if ($md && mb_strlen($md) > 165)$problemas[] = 'meta_desc_larga('.mb_strlen($md).'c)';
  if ($palabras < 400)            $problemas[] = 'contenido_delgado('.$palabras.'p)';
  if (!(int)($row['publicado'] ?? 1)) $problemas[] = 'borrador';
  return [
    'tipo'    => $tipo,
    'url'     => $base_url . '/' . $row['slug'],
    'titulo'  => $row['titulo'],
    'zona'    => $row['zona'] ?? '',
    'palabras'=> $palabras,
    'mt_len'  => mb_strlen($mt),
    'md_len'  => mb_strlen($md),
    'problemas'=> $problemas,
  ];
}

$contenido_dinamico = [];
foreach ($articulos as $a) {
  $contenido_dinamico[] = analizar_entrada($a, 'articulo', '/blog');
}
foreach ($proyectos as $p) {
  $contenido_dinamico[] = analizar_entrada($p, 'proyecto', '/proyectos');
}

// Artículos por zona (para el contexto de gaps)
$arts_por_zona = [];
foreach ($articulos as $a) {
  $z = $a['zona'] ?? '';
  if ($z) $arts_por_zona[$z] = ($arts_por_zona[$z] ?? 0) + 1;
}
foreach ($proyectos as $p) {
  $z = $p['zona'] ?? '';
  if ($z) $arts_por_zona[$z] = ($arts_por_zona[$z] ?? 0) + 1;
}

// ── CONSTRUIR CONTEXTO PARA CLAUDE ───────────────────────────────────────────

// 1. Matriz zona × servicio
$matriz_txt = "MATRIZ ZONA × SERVICIO ESTÁTICO\n";
$matriz_txt .= str_pad('Zona', 20) . implode('  ', array_map(fn($s) => str_pad($s, 14), array_keys($servicios_estaticos))) . "  Artículos/Proyectos\n";
foreach ($matriz as $fila) {
  $linea = str_pad($fila['zona'], 20);
  foreach ($fila['servicios'] as $existe) {
    $linea .= str_pad($existe ? '✓' : '✗ FALTA', 16);
  }
  $linea .= ($arts_por_zona[$fila['zona']] ?? 0);
  $matriz_txt .= $linea . "\n";
}

// 2. Contenido dinámico con problemas
$din_txt = "\nCONTENIDO DINÁMICO (artículos y proyectos en BD)\n";
foreach ($contenido_dinamico as $item) {
  $probs = $item['problemas'] ? implode(', ', $item['problemas']) : 'ok';
  $din_txt .= "[{$item['tipo']}] {$item['url']} | \"{$item['titulo']}\" | zona={$item['zona']} | {$item['palabras']}p | {$probs}\n";
}
if (!$contenido_dinamico) $din_txt .= "(ninguno en BD)\n";

// 3. Páginas estáticas con meta
$est_txt = "\nPÁGINAS ESTÁTICAS (zonas, fugas, desatascos, fontanero, generales)\n";
foreach ($paginas_estaticas as $p) {
  $probs = [];
  if (!$p['meta_title'])                  $probs[] = 'sin_meta_title';
  if ($p['meta_title'] && mb_strlen($p['meta_title']) > 60) $probs[] = 'mt_largo';
  if (!$p['meta_desc'])                   $probs[] = 'sin_meta_desc';
  if ($p['meta_desc'] && mb_strlen($p['meta_desc']) < 140)  $probs[] = 'md_corta';
  $est_txt .= "{$p['url']} | \"{$p['titulo']}\" | md={$p['meta_desc']} | " . ($probs ? implode(',', $probs) : 'ok') . "\n";
}

// 4. Keywords del usuario
$kw_txt = '';
if ($keywords_usuario) {
  $kw_txt = "\nKEYWORDS QUE EL CLIENTE QUIERE ATACAR (detecta cuáles tienen página y cuáles no):\n";
  foreach ($keywords_usuario as $kw) {
    $kw_txt .= "- {$kw}\n";
  }
}

$user_prompt = $matriz_txt . $din_txt . $est_txt . $kw_txt;

// ── SYSTEM PROMPT ─────────────────────────────────────────────────────────────
$system_prompt = <<<SYS
Eres un auditor SEO técnico para CarolTemp, empresa de fontanería y climatización en Alicante (España).

CONTEXTO DE NEGOCIO:
- Zonas de servicio: Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Monforte del Cid, Salinas, Aspe. NUNCA Villena.
- Servicios principales: fontanería urgente, detección de fugas, desatascos, instalación de termos, aire acondicionado, calefacción, reformas de baño, ósmosis inversa
- Diferenciadores: presupuesto gratis sin compromiso, urgencias 24h, instaladores certificados
- Estructura URL ideal: /zonas/{ciudad}, /fugas/deteccion-fugas-{ciudad}, /desatascos/desatascos-{ciudad}, /fontanero/fontanero-{ciudad}

INSTRUCCIONES:
- Analiza la matriz zona×servicio para detectar combinaciones que faltan (gaps estructurales)
- Analiza el contenido dinámico para detectar problemas de calidad SEO
- Si hay keywords del usuario, indica cuáles tienen página y cuáles no tienen cobertura
- Sé concreto y accionable. Prioriza por impacto real en SEO local
- NUNCA menciones Villena como zona faltante
- Devuelve ÚNICAMENTE JSON válido, sin markdown ni texto antes/después

Devuelve este JSON exacto:
{
  "resumen": {
    "total_paginas": N,
    "gaps_estructura": N,
    "problemas_contenido": N,
    "keywords_sin_cobertura": N,
    "oportunidades": N
  },
  "matriz_gaps": [
    {"zona": "Aspe", "faltan": ["Página zona", "Fugas", "Desatascos", "Fontanero"]}
  ],
  "problemas_contenido": [
    {"prioridad": "alta|media|baja", "url": "/blog/slug", "titulo": "Título", "problema": "Descripción concreta del fallo", "accion": "Qué hacer exactamente"}
  ],
  "keywords_gaps": [
    {"keyword": "fontanero urgente elda", "estado": "cubierta|parcial|sin_pagina", "url_existente": "/fugas/deteccion-fugas-elda", "accion": "Qué crear o mejorar"}
  ],
  "oportunidades": [
    {"prioridad": "alta|media", "descripcion": "Qué crear o mejorar", "url_sugerida": "/ruta/sugerida", "razon": "Por qué impacta en SEO"}
  ],
  "canibalizacion": [
    {"urls": ["/blog/a", "/blog/b"], "keyword": "término compartido", "accion": "Qué hacer"}
  ],
  "estructura_recomendada": "Descripción en 4-6 líneas de cómo debería ser la arquitectura ideal del sitio para máximo SEO local",
  "seo_notas": "Resumen ejecutivo de 4-5 líneas: estado actual, mayor problema, mayor oportunidad, prioridad inmediata"
}
SYS;

// ── LLAMADA A CLAUDE ──────────────────────────────────────────────────────────
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
  CURLOPT_TIMEOUT => 90,
]);

$raw      = curl_exec($ch);
$curl_err = curl_error($ch);
curl_close($ch);

if ($curl_err) {
  echo json_encode(['error' => 'Error de conexión: ' . $curl_err]);
  exit;
}

$resp = json_decode($raw, true);
if (!isset($resp['content'][0]['text'])) {
  echo json_encode(['error' => 'Respuesta inválida de la API', 'raw' => substr($raw, 0, 300)]);
  exit;
}
if (($resp['stop_reason'] ?? '') === 'max_tokens') {
  echo json_encode(['error' => 'Respuesta demasiado larga — aumentar max_tokens o reducir inventario.']);
  exit;
}

$texto   = '{' . $resp['content'][0]['text'];
$analisis = json_decode($texto, true);
if (!$analisis) {
  // intento de extracción
  if (preg_match('/\{[\s\S]*\}/u', $texto, $m)) $analisis = json_decode($m[0], true);
}
if (!$analisis) {
  echo json_encode(['error' => 'JSON malformado de la IA', 'raw' => substr($texto, 0, 500)]);
  exit;
}

// Añadir datos de contexto al response (para la UI)
$analisis['_meta'] = [
  'total_estaticas'  => count($paginas_estaticas),
  'total_dinamicas'  => count($contenido_dinamico),
  'keywords_usuario' => count($keywords_usuario),
  'matriz'           => $matriz,
];

echo json_encode(['ok' => true, 'analisis' => $analisis]);
