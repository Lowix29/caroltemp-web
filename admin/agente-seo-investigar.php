<?php
/* =============================================
   CAROLTEMP — Agente SEO: Investigador de competidores
============================================= */
// Modo diagnóstico temporal: ?debug_errors=1
if (isset($_GET['debug_errors'])) {
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
} else {
  error_reporting(0);
  ini_set('display_errors', 0);
}
ob_start();

session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  ob_end_clean();
  http_response_code(401);
  echo json_encode(['error' => 'No autorizado']);
  exit;
}

require_once '../includes/db.php';
require_once '../includes/config.php';

if (!defined('ANTHROPIC_MODEL')) define('ANTHROPIC_MODEL', 'claude-sonnet-4-5');

header('Content-Type: application/json; charset=utf-8');

if (!function_exists('curl_init')) {
  echo json_encode(['error' => 'curl no habilitado. Abre php.ini, descomenta extension=curl y reinicia Apache.']);
  exit;
}

$keyword = trim($_POST['keyword'] ?? '');
$zona    = trim($_POST['zona']    ?? '');

if (!$keyword) {
  echo json_encode(['error' => 'Keyword requerida']);
  exit;
}

// ── Curl con diagnóstico ────────────────────────────────────
function ct_curl($url, $referer = '') {
  $cookieFile = tempnam(sys_get_temp_dir(), 'ct_cook_');

  $ch = curl_init($url);
  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS      => 8,
    CURLOPT_ENCODING       => '',      // auto decompress gzip/br
    CURLOPT_TIMEOUT        => 20,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_COOKIEJAR      => $cookieFile,
    CURLOPT_COOKIEFILE     => $cookieFile,
    CURLOPT_HTTPHEADER     => [
      'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
      'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
      'Accept-Language: es-ES,es;q=0.9',
      'Accept-Encoding: gzip, deflate',
      'DNT: 1',
      'Upgrade-Insecure-Requests: 1',
      $referer ? 'Referer: ' . $referer : 'Sec-Fetch-Site: none',
      'Sec-Fetch-Mode: navigate',
      'Sec-Fetch-Dest: document',
    ],
  ]);

  $html     = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $errno    = curl_errno($ch);
  $errmsg   = curl_error($ch);
  curl_close($ch);

  @unlink($cookieFile);

  if ($errno) {
    // Devolver el error curl para diagnóstico
    return ['_curl_error' => "Error curl #{$errno}: {$errmsg}"];
  }
  if (!$html) {
    return ['_curl_error' => "Respuesta vacía (HTTP {$httpCode})"];
  }
  if ($httpCode >= 400) {
    return ['_curl_error' => "HTTP {$httpCode} para {$url}"];
  }
  return $html;
}

// ── SERP via Serper.dev (Google results, no captcha) ─────────
function ct_serp($keyword, $zona) {
  if (!defined('SERPER_API_KEY') || strlen(SERPER_API_KEY) < 10) {
    return ['error' => 'SERPER_API_KEY no configurada en includes/config.php'];
  }

  $q       = $keyword . ($zona ? ' ' . $zona : '');
  $payload = json_encode(['q' => $q, 'gl' => 'es', 'hl' => 'es', 'num' => 10]);

  $ch = curl_init('https://google.serper.dev/search');
  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_HTTPHEADER     => [
      'X-API-KEY: ' . SERPER_API_KEY,
      'Content-Type: application/json',
    ],
    CURLOPT_TIMEOUT        => 15,
    CURLOPT_SSL_VERIFYPEER => false,
  ]);

  $raw      = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $errno    = curl_errno($ch);
  $errmsg   = curl_error($ch);
  curl_close($ch);

  if ($errno) return ['error' => "Serper curl error: {$errmsg}"];
  if ($httpCode !== 200) {
    $msg = json_decode($raw, true)['message'] ?? $raw;
    return ['error' => "Serper API error {$httpCode}: {$msg}"];
  }

  $data = json_decode($raw, true);
  if (empty($data['organic'])) {
    return ['error' => 'Serper no devolvió resultados orgánicos para esta keyword.'];
  }

  $urls = array_column(array_slice($data['organic'], 0, 6), 'link');
  return ['urls' => $urls, 'fuente' => 'Google (Serper)'];
}

// ── Análisis de página rival ─────────────────────────────────
function ct_analizar($url, $pos) {
  $res = ct_curl($url, 'https://www.google.es/');
  if (!is_string($res)) return null;
  $html = $res;

  $texto_limpio = preg_replace(
    ['/<script\b[^>]*>.*?<\/script>/si', '/<style\b[^>]*>.*?<\/style>/si'],
    '', $html
  );

  $dom = new DOMDocument('1.0', 'UTF-8');
  libxml_use_internal_errors(true);
  $dom->loadHTML('<?xml encoding="UTF-8">' . $html);
  libxml_clear_errors();
  $xp = new DOMXPath($dom);

  $get = function($q) use ($xp) {
    $n = $xp->query($q);
    return $n->length ? trim(preg_replace('/\s+/', ' ', $n->item(0)->textContent)) : '';
  };
  $getAll = function($q) use ($xp) {
    $r = [];
    foreach ($xp->query($q) as $n) {
      $t = trim(preg_replace('/\s+/', ' ', $n->textContent));
      if ($t && strlen($t) < 250) $r[] = $t;
    }
    return $r;
  };

  // Contar palabras del body limpio
  $dom2 = new DOMDocument();
  libxml_use_internal_errors(true);
  @$dom2->loadHTML($texto_limpio);
  libxml_clear_errors();
  $xp2  = new DOMXPath($dom2);
  $body = $xp2->query('//body');
  $txt  = $body->length ? preg_replace('/\s+/', ' ', trim($body->item(0)->textContent)) : '';
  $palabras = str_word_count($txt);

  // FAQs via schema.org
  $faqs = [];
  if (preg_match_all('/"name"\s*:\s*"([^"]{20,200})"/', $html, $mfaq)) {
    foreach ($mfaq[1] as $f) {
      if (strpos($f, '?') !== false || preg_match('/^(cu[aá]nto|c[oó]mo|qu[eé]|cu[aá]ndo|por qu[eé])/i', $f)) {
        $faqs[] = $f;
      }
    }
  }

  return [
    'posicion' => $pos,
    'url'      => $url,
    'titulo'   => $get('//title'),
    'meta_desc'=> $get('//meta[@name="description"]/@content'),
    'h1'       => $get('//h1'),
    'h2s'      => array_slice($getAll('//h2'), 0, 8),
    'h3s'      => array_slice($getAll('//h3'), 0, 6),
    'palabras' => $palabras,
    'imagenes' => $xp->query('//img')->length,
    'faqs'     => array_slice(array_unique($faqs), 0, 5),
  ];
}

// ── Modo debug ────────────────────────────────────────────────
if (!empty($_POST['debug'])) {
  $serp = ct_serp($keyword, $zona);
  echo json_encode(['debug' => true, 'serp' => $serp], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
  exit;
}

// ── 1. SERP ──────────────────────────────────────────────────
$serp = ct_serp($keyword, $zona);
if (isset($serp['error'])) {
  ob_end_clean();
  echo json_encode(['error' => $serp['error']]);
  exit;
}

// ── 2. Analizar top 4 páginas ────────────────────────────────
$analisis = [];
foreach (array_slice($serp['urls'], 0, 4) as $idx => $url) {
  $pag = ct_analizar($url, $idx + 1);
  if ($pag) $analisis[] = $pag;
}

if (empty($analisis)) {
  ob_end_clean();
  echo json_encode(['error' => 'Serper devolvió URLs pero no se pudo analizar ninguna página rival. Las webs pueden estar bloqueando scrapers. El informe se generará solo con las URLs.']);
  exit;
}

// ── 3. Informe Claude ────────────────────────────────────────
$fuente    = $serp['fuente'] ?? 'Bing';
$contexto  = "Keyword: \"{$keyword}\"" . ($zona ? " · Zona: {$zona}" : '') . "\n\n";
$contexto .= "DATOS DE LOS " . count($analisis) . " PRIMEROS RESULTADOS EN {$fuente} ESPAÑA:\n\n";
foreach ($analisis as $p) {
  $contexto .= "═══ #{$p['posicion']} — {$p['url']} ═══\n";
  if ($p['titulo'])    $contexto .= "Title: {$p['titulo']}\n";
  if ($p['meta_desc']) $contexto .= "Meta: {$p['meta_desc']}\n";
  if ($p['h1'])        $contexto .= "H1: {$p['h1']}\n";
  if ($p['h2s'])       $contexto .= "H2s: " . implode(' | ', $p['h2s']) . "\n";
  if ($p['h3s'])       $contexto .= "H3s: " . implode(' | ', $p['h3s']) . "\n";
  if ($p['faqs'])      $contexto .= "FAQs: " . implode(' | ', $p['faqs']) . "\n";
  $contexto .= "Palabras: {$p['palabras']} | Imágenes: {$p['imagenes']}\n\n";
}

$system_inv = <<<SYS
Eres un analista SEO de élite especializado en negocios locales de fontanería y climatización en España. Recibes datos extraídos de los competidores en Google para una keyword concreta.

Genera un informe de inteligencia competitiva en Markdown. Directo, concreto, accionable. Lo leerá otro agente de IA para redactar el artículo — debe poder seguirlo como briefing.

## 1. Resumen de competidores
Para cada posición: 1-2 frases sobre qué hace bien y qué hace mal.

## 2. Temas y secciones que cubre la competencia
Lista de temas recurrentes en los H2s/H3s de los rivales.

## 3. Gaps y oportunidades
Qué temas importantes NO cubre nadie bien → ahí ataca CarolTemp.

## 4. Estructura ganadora recomendada
H1 sugerido + lista de H2s ordenados para superar a los rivales.

## 5. Keywords y variaciones long-tail detectadas
Términos exactos que usan los competidores que debemos incluir.

## 6. Objetivo de palabras
Cuántas palabras necesita el artículo (rivales + 20% mínimo).

## 7. FAQs prioritarias
Las 4-5 preguntas más relevantes que debe responder el artículo.

## 8. Ángulo diferenciador de CarolTemp
Cómo usar "precio cerrado antes de empezar", "urgencias 24h", "sin obra" para ganar en este tema frente a los competidores analizados.
SYS;

$payload_inv = [
  'model'      => ANTHROPIC_MODEL,
  'max_tokens' => 2000,
  'system'     => $system_inv,
  'messages'   => [['role' => 'user', 'content' => $contexto]],
];

$ch = curl_init('https://api.anthropic.com/v1/messages');
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST           => true,
  CURLOPT_POSTFIELDS     => json_encode($payload_inv),
  CURLOPT_HTTPHEADER     => [
    'x-api-key: '         . ANTHROPIC_API_KEY,
    'anthropic-version: 2023-06-01',
    'content-type: application/json',
  ],
  CURLOPT_TIMEOUT        => 60,
  CURLOPT_SSL_VERIFYPEER => false,
]);
$raw      = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$informe = '';
if ($httpCode === 200) {
  $resp    = json_decode($raw, true);
  $informe = $resp['content'][0]['text'] ?? '';
}

// ── 4. Guardar en BD ─────────────────────────────────────────
$inv_id = 0;
try {
  $pdo->prepare('
    INSERT INTO seo_investigaciones (keyword, zona, urls_encontradas, analisis_json, informe)
    VALUES (?, ?, ?, ?, ?)
  ')->execute([$keyword, $zona, json_encode($serp['urls']), json_encode($analisis), $informe]);
  $inv_id = $pdo->lastInsertId();
} catch (Exception $e) {}

ob_end_clean(); // descarta cualquier output espurio antes del JSON
echo json_encode([
  'ok'               => true,
  'urls'             => $serp['urls'],
  'analisis'         => $analisis,
  'informe'          => $informe,
  'investigacion_id' => $inv_id,
]);
