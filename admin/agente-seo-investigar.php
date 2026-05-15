<?php
/* =============================================
   CAROLTEMP — Agente SEO: Investigador de competidores
============================================= */
error_reporting(0);
ini_set('display_errors', 0);

session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  http_response_code(401);
  echo json_encode(['error' => 'No autorizado']);
  exit;
}

require_once '../includes/db.php';
require_once '../includes/config.php';

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

// ── Scraping de Bing (principal) + Google fallback ───────────
function ct_serp($keyword, $zona) {
  $q       = $keyword . ($zona ? ' ' . $zona : '');
  $excluir = '/\b(bing|microsoft|youtube|facebook|twitter|instagram|wikipedia|gstatic|msn|linkedin|amazon|ebay|tripadvisor|w3\.org|schema\.org)\b/i';

  // ── Bing (sin bloqueo EU, resultados similares a Google en local ES) ──
  $bingUrl = 'https://www.bing.com/search?q=' . urlencode($q) . '&mkt=es-ES&count=10&setlang=es';
  $res     = ct_curl($bingUrl, 'https://www.bing.com/');

  if (is_string($res)) {
    $urls = [];
    // Resultados orgánicos Bing: <h2><a href="https://...">
    if (preg_match_all('/<h2[^>]*>\s*<a[^>]+href="(https?:\/\/[^"]+)"/i', $res, $m)) {
      foreach ($m[1] as $u) {
        if (!preg_match($excluir, $u) && !in_array($u, $urls)) $urls[] = $u;
      }
    }
    // Fallback Bing: cualquier cite o enlace de resultado
    if (count($urls) < 2 && preg_match_all('/<cite[^>]*>(https?:\/\/[^<]+)<\/cite>/i', $res, $mc)) {
      foreach ($mc[1] as $u) {
        $u = 'https://' . trim(strip_tags($u));
        if (!preg_match($excluir, $u) && !in_array($u, $urls)) $urls[] = $u;
      }
    }
    if (!empty($urls)) {
      return ['urls' => array_values(array_slice($urls, 0, 6)), 'fuente' => 'Bing'];
    }
  }

  // ── Google fallback: acepta cookie de consentimiento EU ──────
  // Primera petición para obtener cookies de sesión
  $cookieFile = tempnam(sys_get_temp_dir(), 'ct_g_');
  $chConsent  = curl_init('https://www.google.es/');
  curl_setopt_array($chConsent, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_ENCODING       => '',
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_COOKIEJAR      => $cookieFile,
    CURLOPT_COOKIE         => 'SOCS=CAESEwgDEgk2MDc4MDEwMTcaAmVzIAE; CONSENT=YES+ES',
    CURLOPT_HTTPHEADER     => ['User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/124 Safari/537.36', 'Accept-Language: es-ES,es;q=0.9'],
  ]);
  curl_exec($chConsent);
  curl_close($chConsent);

  // Segunda petición: búsqueda real con cookie de consentimiento ya establecida
  $gUrl = 'https://www.google.es/search?q=' . urlencode($q) . '&hl=es&num=10&pws=0';
  $chG  = curl_init($gUrl);
  curl_setopt_array($chG, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_ENCODING       => '',
    CURLOPT_TIMEOUT        => 18,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_COOKIEJAR      => $cookieFile,
    CURLOPT_COOKIEFILE     => $cookieFile,
    CURLOPT_COOKIE         => 'SOCS=CAESEwgDEgk2MDc4MDEwMTcaAmVzIAE; CONSENT=YES+ES',
    CURLOPT_HTTPHEADER     => [
      'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/124 Safari/537.36',
      'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
      'Accept-Language: es-ES,es;q=0.9',
      'Accept-Encoding: gzip, deflate',
    ],
  ]);
  $gHtml = curl_exec($chG);
  $gCode = curl_getinfo($chG, CURLINFO_HTTP_CODE);
  curl_close($chG);
  @unlink($cookieFile);

  if ($gHtml && $gCode < 400 && stripos($gHtml, 'redirecciona') === false) {
    $urls = [];
    if (preg_match_all('/href="\/url\?q=(https?:\/\/[^&"]+)/i', $gHtml, $mg)) {
      foreach ($mg[1] as $u) {
        $u = urldecode($u);
        if (!preg_match($excluir, $u) && !in_array($u, $urls)) $urls[] = $u;
      }
    }
    if (!empty($urls)) return ['urls' => array_slice($urls, 0, 6), 'fuente' => 'Google'];
  }

  return ['error' => 'No se pudieron obtener resultados de Bing ni Google. Comprueba que XAMPP tiene acceso a internet (prueba abrir bing.com desde el servidor).'];
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

// ── Modo debug: devuelve HTML crudo de Google ────────────────
if (!empty($_POST['debug'])) {
  $q    = $keyword . ($zona ? ' ' . $zona : '');
  $url  = 'https://www.google.es/search?q=' . urlencode($q) . '&hl=es&num=10&pws=0';
  $res  = ct_curl($url, 'https://www.google.es/');
  $html = is_string($res) ? $res : '';
  // Extraer todos los href https para ver qué hay
  preg_match_all('/href="(https?:\/\/[^"]{10,}?)"/i', $html, $todosLinks);
  echo json_encode([
    'debug'       => true,
    'html_inicio' => substr($html, 0, 3000),
    'todos_hrefs' => array_slice(array_unique($todosLinks[1] ?? []), 0, 40),
    'longitud'    => strlen($html),
    'curl_error'  => is_array($res) ? $res : null,
  ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
  exit;
}

// ── 1. SERP ──────────────────────────────────────────────────
$serp = ct_serp($keyword, $zona);
if (isset($serp['error'])) {
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
  echo json_encode(['error' => 'Se encontraron URLs en Google pero no se pudo analizar ninguna página rival (pueden estar bloqueando scrapers). Prueba de nuevo.']);
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

echo json_encode([
  'ok'               => true,
  'urls'             => $serp['urls'],
  'analisis'         => $analisis,
  'informe'          => $informe,
  'investigacion_id' => $inv_id,
]);
