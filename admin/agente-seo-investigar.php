<?php
/* =============================================
   CAROLTEMP — Agente SEO: Investigador de competidores
   Raspa Google top 4 → analiza cada página → informe IA
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
  echo json_encode(['error' => 'curl no habilitado en PHP.']);
  exit;
}

$keyword = trim($_POST['keyword'] ?? '');
$zona    = trim($_POST['zona']    ?? '');

if (!$keyword) {
  echo json_encode(['error' => 'Keyword requerida']);
  exit;
}

// ── Curl genérico ───────────────────────────────────────────
function ct_curl($url, $referer = '') {
  $ch = curl_init($url);
  $headers = [
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    'Accept-Language: es-ES,es;q=0.9',
    'Accept-Encoding: gzip, deflate, br',
    'Cache-Control: no-cache',
    'Pragma: no-cache',
  ];
  if ($referer) $headers[] = 'Referer: ' . $referer;

  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS      => 5,
    CURLOPT_HTTPHEADER     => $headers,
    CURLOPT_ENCODING       => '',
    CURLOPT_TIMEOUT        => 18,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_COOKIEJAR      => tempnam(sys_get_temp_dir(), 'ct_'),
    CURLOPT_COOKIEFILE     => '',
  ]);
  $html = curl_exec($ch);
  $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
  if (!$html || $code >= 400) return false;
  return $html;
}

// ── Scraping de Google ───────────────────────────────────────
function ct_serp($keyword, $zona) {
  $q   = $keyword . ($zona ? ' ' . $zona : '');
  $url = 'https://www.google.com/search?q=' . urlencode($q) . '&hl=es&gl=es&num=10&pws=0';
  $html = ct_curl($url, 'https://www.google.com/');
  if (!$html) return ['error' => 'No se pudo conectar con Google.'];

  if (stripos($html, 'g-recaptcha') !== false || stripos($html, 'unusual traffic') !== false) {
    return ['error' => 'Google ha bloqueado la búsqueda (CAPTCHA). Espera unos minutos e inténtalo de nuevo desde el navegador.'];
  }

  $excluir = '/google\.|youtube\.|facebook\.|twitter\.com|instagram\.|wikipedia\.|gstatic\.|schema\.org|w3\.org|tripadvisor\.|amazon\.|ebay\./';
  $urls = [];

  // Patrón principal: /url?q= (redirección Google)
  if (preg_match_all('/href="\/url\?q=(https?:\/\/[^&"]+)/i', $html, $m)) {
    foreach ($m[1] as $u) {
      $u = urldecode($u);
      if (!preg_match($excluir, $u) && !in_array($u, $urls)) $urls[] = $u;
    }
  }

  // Patrón fallback: links directos https
  if (count($urls) < 2) {
    if (preg_match_all('/<a[^>]+href="(https?:\/\/(?!www\.google)[^"#?]+)"[^>]*>/i', $html, $m2)) {
      foreach ($m2[1] as $u) {
        if (!preg_match($excluir, $u) && !in_array($u, $urls)) $urls[] = $u;
      }
    }
  }

  $urls = array_values(array_unique($urls));
  if (empty($urls)) return ['error' => 'No se encontraron resultados orgánicos. Prueba otra keyword.'];
  return ['urls' => array_slice($urls, 0, 6)];
}

// ── Análisis de página rival ─────────────────────────────────
function ct_analizar($url, $pos) {
  $html = ct_curl($url, 'https://www.google.com/');
  if (!$html) return null;

  // Limpiar scripts/estilos para contar palabras
  $texto_limpio = preg_replace(['/<script\b[^>]*>.*?<\/script>/si', '/<style\b[^>]*>.*?<\/style>/si'], '', $html);

  $dom = new DOMDocument('1.0', 'UTF-8');
  libxml_use_internal_errors(true);
  $dom->loadHTML('<?xml encoding="UTF-8">' . $html);
  libxml_clear_errors();
  $xp = new DOMXPath($dom);

  $get = function($query) use ($xp) {
    $n = $xp->query($query);
    return $n->length ? trim(preg_replace('/\s+/', ' ', $n->item(0)->textContent)) : '';
  };

  $getAll = function($query) use ($xp) {
    $result = [];
    foreach ($xp->query($query) as $n) {
      $t = trim(preg_replace('/\s+/', ' ', $n->textContent));
      if ($t && strlen($t) < 250) $result[] = $t;
    }
    return $result;
  };

  // Contar palabras del body limpio
  $dom2 = new DOMDocument();
  @$dom2->loadHTML($texto_limpio);
  $xp2      = new DOMXPath($dom2);
  $bodyNode = $xp2->query('//body');
  $bodyTxt  = $bodyNode->length ? preg_replace('/\s+/', ' ', trim($bodyNode->item(0)->textContent)) : '';
  $palabras = str_word_count($bodyTxt);

  // FAQs via schema.org
  $faqs = [];
  if (preg_match_all('/"name"\s*:\s*"([^"]{20,200})"/', $html, $mfaq)) {
    foreach ($mfaq[1] as $f) {
      if (strpos($f, '?') !== false || stripos($f, 'cuánto') !== false || stripos($f, 'cómo') !== false) {
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
  echo json_encode(['error' => 'No se pudo analizar ninguna página rival. Los sitios pueden estar bloqueando scrapers.']);
  exit;
}

// ── 3. Informe Claude ────────────────────────────────────────
$contexto  = "Keyword: \"{$keyword}\"" . ($zona ? " · Zona: {$zona}" : '') . "\n\n";
$contexto .= "DATOS EXTRAÍDOS DE LOS " . count($analisis) . " PRIMEROS RESULTADOS EN GOOGLE ESPAÑA:\n\n";
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
Eres un analista SEO de élite especializado en negocios locales de fontanería y climatización en España. Recibes datos extraídos de los competidores que aparecen en Google para una keyword concreta.

Genera un informe de inteligencia competitiva estructurado en Markdown. Sé directo, concreto y accionable. Este informe lo leerá otro agente de IA que va a redactar el artículo — debe poder seguirlo como un briefing.

Incluye estos apartados:

## 1. Resumen de competidores
Para cada posición: 1-2 frases sobre qué hace bien y qué hace mal.

## 2. Temas y secciones que cubre la competencia
Lista de los temas recurrentes en los H2s/H3s de los rivales.

## 3. Gaps y oportunidades
Qué temas importantes NO cubre nadie bien → ahí debe atacar CarolTemp.

## 4. Estructura ganadora recomendada
H1 sugerido + lista de H2s ordenados que superarán a los rivales.

## 5. Keywords y variaciones long-tail detectadas
Términos exactos que usan los competidores y que deberíamos incluir.

## 6. Objetivo de palabras
Cuántas palabras debe tener el artículo (rivales + 20% mínimo).

## 7. FAQs prioritarias
Las 4-5 preguntas más relevantes que debe responder el artículo.

## 8. Ángulo diferenciador de CarolTemp
Cómo usar "precio cerrado antes de empezar", "urgencias 24h", "sin obra" u otros diferenciales para ganar en este tema concreto frente a los competidores analizados.
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
  CURLOPT_TIMEOUT => 60,
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
