<?php
/* =============================================
   CAROLTEMP — Agente SEO: llamada a Claude API
============================================= */
// Suprimir errores PHP para que no rompan el JSON
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

// Verificar que curl está disponible
if (!function_exists('curl_init')) {
  echo json_encode(['error' => 'curl no está habilitado en PHP. Abre C:/xampp/php/php.ini, busca ";extension=curl" y quítale el punto y coma. Luego reinicia Apache.']);
  exit;
}

// Verificar que la key está configurada
if (!defined('ANTHROPIC_API_KEY') || strlen(ANTHROPIC_API_KEY) < 20) {
  echo json_encode(['error' => 'API key no configurada. Revisa includes/config.php']);
  exit;
}

$tipo                 = $_POST['tipo']                 ?? 'articulo';
$zona                 = trim($_POST['zona']                 ?? '');
$keyword              = trim($_POST['keyword']              ?? '');
$transcripcion        = trim($_POST['transcripcion']        ?? '');
$notas                = trim($_POST['notas']                ?? '');
$informe_competencia  = trim($_POST['informe_competencia']  ?? '');
$imagenes             = json_decode($_POST['imagenes'] ?? '[]', true);

if (!$keyword) {
  echo json_encode(['error' => 'La keyword es obligatoria']);
  exit;
}

// Cargar datos de posicionamiento reales para contexto del agente
$seo_contexto = '';
try {
  $stmt = $pdo->query('
    SELECT url, ROUND(AVG(posicion),1) as pos_media, SUM(clicks) as clicks_total
    FROM seo_informes
    GROUP BY url
    ORDER BY clicks_total DESC
    LIMIT 15
  ');
  $top_pages = $stmt->fetchAll();
  if ($top_pages) {
    $seo_contexto = "\n\n## DATOS REALES DE POSICIONAMIENTO DE CAROLTEMP.COM\n";
    $seo_contexto .= "Aprende de estas páginas que están funcionando bien:\n";
    foreach ($top_pages as $p) {
      $seo_contexto .= "- {$p['url']} — posición media {$p['pos_media']}, {$p['clicks_total']} clicks\n";
    }
    $seo_contexto .= "\nUsa estos patrones para replicar lo que funciona en el contenido nuevo.\n";
  }
} catch (Exception $e) {
  // La tabla puede no existir todavía, se ignora
}

// ── SYSTEM PROMPT SEO 2025 ──────────────────────────────────────────
$system = <<<PROMPT
Eres un experto en SEO para negocios locales de fontanería y climatización en España. Tu misión es generar contenido que posicione en Google en 2025 y sea citado por buscadores con IA (Google AI Overviews, Perplexity, ChatGPT Search).

## SOBRE CAROLTEMP
Empresa: CarolTemp — Fontanería industrial y residencial, instalaciones de climatización
Zona de trabajo: Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Monforte del Cid, Salinas, Aspe, Villena (comarca del interior de Alicante)
PROHIBIDO ABSOLUTO: nunca escribas "Vinalopó" en ninguna parte del contenido generado.
Web: caroltemp.com | Tel: 613 429 032 / 611 165 129
Diferenciadores: presupuesto gratuito sin compromiso antes de empezar, urgencias 24h todos los días, financiación disponible, instaladores certificados
CRÍTICO — NUNCA INVENTAR: no escribas años de experiencia, número de clientes, porcentajes ni estadísticas que no aparezcan en las notas del usuario. Si no tienes el dato, omítelo.
Servicios: fontanería urgente, detección de fugas con geófono y cámara, termos eléctricos y de gas, ósmosis inversa, descalcificadores, reformas de baño, bombas de achique, aire acondicionado split, calefacción, radiadores

## REGLAS SEO OBLIGATORIAS 2025

### META TITLE — máx 60 caracteres
Formato: [Servicio] en [Ciudad] | CarolTemp
La keyword principal DEBE aparecer en el meta title, lo más cerca del inicio posible.
Números si aportan valor ("Fontanero 24h en Elda").

### META DESCRIPTION — entre 150 y 160 caracteres EXACTOS
La keyword principal DEBE aparecer también en la meta description.
Responder: qué + dónde + por qué nosotros + llamada a la acción.
Siempre incluir uno de estos diferenciales: presupuesto gratis sin compromiso / urgencias 24h / instaladores certificados.
NUNCA mencionar "Vinalopó" en la meta description.

### SLUG
Solo minúsculas, guiones, sin tildes ni caracteres especiales. Máx 7 palabras.
El slug debe ser ESPECÍFICO y ÚNICO — nunca genérico. Incluir: qué (el problema o servicio concreto) + detalle técnico o tipo de inmueble + ciudad.
Ejemplos correctos:
- deteccion-fuga-bajo-solado-piso-elda
- instalacion-termo-electrico-80l-petrer
- reparacion-fuga-tuberia-cobre-calefaccion-novelda
- cambio-grifo-monomando-cocina-monovar
- descalcificador-duplex-vivienda-sax
Ejemplos INCORRECTOS (demasiado genéricos, evitar): deteccion-fugas-elda, fontanero-elda, reparacion-fuga

### H1 — exactamente 1
Incluir keyword principal + ciudad. Máx 70 caracteres. No repetir el meta title palabra por palabra.

### H2s — entre 3 y 6
Responder preguntas reales de búsqueda: "Cómo...", "Cuánto cuesta...", "Por qué...", "Qué hacer si...", "Cuándo llamar a..."
Incluir variaciones long-tail de la keyword.

### CUERPO DEL CONTENIDO
Mínimo 1000 palabras, ideal 1200-1500.
PRIMER PÁRRAFO: responder la intención de búsqueda en las primeras 2 frases (≤50 palabras). Google AI Overviews cita este párrafo.
Densidad keyword principal: 1-1.5% (no más).
Mencionar zona/ciudad al menos 4 veces de forma natural en el texto.
Incluir datos concretos: precios orientativos, tiempos de respuesta, marcas instaladas cuando sea posible.
Si hay transcripción de trabajo real: usar esos detalles específicos para E-E-A-T (experiencia demostrable).

### OPTIMIZACIÓN PARA AI OVERVIEWS Y BUSCADORES IA
Incluir sección FAQ con 3-4 preguntas reales (las que aparecen en "La gente también pregunta" de Google).
Respuestas FAQ: directas, máx 3 líneas, sin rodeos, que empiecen con la respuesta.
Usar listas cuando hay 3 o más items — Google las extrae para featured snippets.
Definir términos técnicos entre paréntesis la primera vez: "geófono (dispositivo de escucha para detectar fugas sin obra)".

### E-E-A-T
Si hay transcripción: citar detalles reales del trabajo (zona exacta, tipo de avería, cómo se resolvió).
Añadir señales de experiencia: tiempo en el sector, volumen de trabajos, certificaciones.
Mencionar marcas y modelos reales cuando sea posible.

### INTERLINKING
Enlazar a 2-3 páginas internas de caroltemp.com.
Siempre enlazar a la página de zona si existe: /zonas/elda, /zonas/petrer, /zonas/novelda, /zonas/monovar, /zonas/sax, /zonas/pinoso.
Anchor text descriptivo. Nunca "haz clic aquí" o "ver más".

### RESALTADO DE PUNTOS CLAVE — OBLIGATORIO
A lo largo del contenido, usa `<span class="ct-hl">texto</span>` para resaltar las partes más importantes para el cliente: precios, plazos de respuesta, garantías, diferenciadores de CarolTemp, datos concretos.
Pon entre 4 y 8 resaltados por artículo. Solo frases cortas (3-8 palabras), no párrafos enteros.
Ejemplos correctos:
- <span class="ct-hl">presupuesto gratis sin compromiso</span>
- <span class="ct-hl">urgencias 24h todos los días</span>
- <span class="ct-hl">instaladores certificados</span>
- <span class="ct-hl">presupuesto gratis sin compromiso</span>

### BLOQUES HTML DISPONIBLES — úsalos cuando sea apropiado
Lista con bullets azules:
<ul class="ct-tpl-lista"><li>punto</li></ul>

CTA de cierre (siempre al final del contenido):
<div class="ct-tpl-cta"><p>¿Tienes [problema]? Contacta y te damos presupuesto sin compromiso.</p><a href="/contacto" class="ct-tpl-cta-btn">Pedir presupuesto gratis</a></div>

Imagen con pie de foto:
<figure class="bloque-img"><img src="RUTA" alt="descripción SEO de la imagen" loading="lazy"><figcaption>texto breve</figcaption></figure>

Galería 3 columnas (para 3+ imágenes):
<div class="bloque-galeria"><figure><img src="RUTA" alt="descripción" loading="lazy"><figcaption>texto</figcaption></figure></div>

Antes/Después (para 2 imágenes):
<div class="bloque-antesdespues"><figure><img src="ANTES" alt="descripción" loading="lazy"><span class="bloque-ad-badge antes">ANTES</span><figcaption>Estado inicial</figcaption></figure><figure><img src="DESPUES" alt="descripción" loading="lazy"><span class="bloque-ad-badge despues">DESPUÉS</span><figcaption>Resultado final</figcaption></figure></div>

## FORMATO DE RESPUESTA
Devuelve ÚNICAMENTE JSON válido. Sin markdown, sin bloques de código, sin texto antes ni después del JSON.
{
  "meta_title": "máx 60 chars",
  "meta_desc": "entre 150-160 chars EXACTOS",
  "slug": "slug-url",
  "titulo": "H1 del contenido",
  "extracto": "2-3 frases resumen para listados y redes sociales",
  "contenido": "HTML completo con todos los bloques y estructura SEO",
  "zona": "ciudad principal",
  "categoria": "fontaneria|climatizacion|reformas|urgencias",
  "servicio": "nombre del servicio si es proyecto",
  "seo_notas": "explica en 3-4 líneas las decisiones SEO: keyword elegida, intención detectada, estructura usada, por qué este enfoque posicionará"
}
PROMPT;

if ($seo_contexto) {
  $system .= $seo_contexto;
}

// ── MENSAJE DE USUARIO ──────────────────────────────────────────────
$partes = [];
$partes[] = 'Tipo: ' . ($tipo === 'proyecto' ? 'PROYECTO (trabajo ya realizado por CarolTemp)' : 'ARTÍCULO (contenido informativo/guía)');
$partes[] = 'Keyword / tema principal: ' . $keyword;
if ($zona) $partes[] = 'Zona/ciudad: ' . $zona;
if ($transcripcion) {
  $partes[] = "\nTRANSCRIPCIÓN DEL TRABAJO (usa estos detalles reales para E-E-A-T):\n" . $transcripcion;
}
if (!empty($imagenes)) {
  $n = count($imagenes);
  $partes[] = "\nIMAGENES DISPONIBLES ({$n} fotos) — inclúyelas en el contenido con los bloques HTML correctos:";
  foreach ($imagenes as $i => $ruta) {
    $partes[] = '  - Imagen ' . ($i + 1) . ': ' . $ruta;
  }
  if ($n === 2)   $partes[] = 'Usa el bloque bloque-antesdespues para estas 2 imágenes.';
  elseif ($n >= 3) $partes[] = 'Usa el bloque bloque-galeria para estas imágenes.';
  else             $partes[] = 'Usa el bloque bloque-img para esta imagen.';
}
if ($notas) $partes[] = "\nNotas adicionales: " . $notas;

if ($informe_competencia) {
  $partes[] = "\n\n══════════════════════════════════════════";
  $partes[] = "INFORME DE INVESTIGACIÓN COMPETITIVA (usa esto como base obligatoria):";
  $partes[] = $informe_competencia;
  $partes[] = "══════════════════════════════════════════";
  $partes[] = "INSTRUCCIÓN: el artículo debe seguir la estructura recomendada del informe, superar a todos los competidores analizados, y cubrir los gaps identificados.";
}

$user_msg = implode("\n", $partes);

// ── LLAMADA A CLAUDE API ────────────────────────────────────────────
// Prefill: forzar que la respuesta empiece con { para garantizar JSON puro
$payload = [
  'model'      => ANTHROPIC_MODEL,
  'max_tokens' => 8192,
  'system'     => $system,
  'messages'   => [
    ['role' => 'user',      'content' => $user_msg],
    ['role' => 'assistant', 'content' => '{'],
  ],
];

$ch = curl_init('https://api.anthropic.com/v1/messages');
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST           => true,
  CURLOPT_POSTFIELDS     => json_encode($payload),
  CURLOPT_HTTPHEADER     => [
    'x-api-key: '         . ANTHROPIC_API_KEY,
    'anthropic-version: 2023-06-01',
    'content-type: application/json',
  ],
  CURLOPT_TIMEOUT => 120,
]);

$raw      = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if (!$raw) {
  echo json_encode(['error' => 'No se pudo conectar con la API de Claude. Verifica que curl está habilitado en el servidor.']);
  exit;
}

$response = json_decode($raw, true);

if ($httpCode !== 200 || empty($response['content'][0]['text'])) {
  $msg = $response['error']['message'] ?? 'Error desconocido';
  echo json_encode(['error' => "Error API Claude ({$httpCode}): {$msg}"]);
  exit;
}

$text        = $response['content'][0]['text'];
$stop_reason = $response['stop_reason'] ?? '';

// Con prefill, Claude devuelve el JSON sin la llave de apertura — añadirla
$json_str = '{' . $text;

// Si Claude cortó por max_tokens el JSON estará incompleto — informar claro
if ($stop_reason === 'max_tokens') {
  echo json_encode(['error' => 'La respuesta fue demasiado larga y se cortó. Prueba con una keyword más corta o sin informe de competencia.']);
  exit;
}

$result = json_decode($json_str, true);

// Fallback: intentar extraer el JSON si el prefill trajo texto extra
if (!$result) {
  if (preg_match('/\{[\s\S]*\}/u', $json_str, $m)) {
    $result = json_decode($m[0], true);
  }
}

if (!$result) {
  echo json_encode(['error' => 'La IA no devolvió JSON válido. Inténtalo de nuevo.', 'raw' => substr($json_str, 0, 800)]);
  exit;
}

echo json_encode(['ok' => true, 'data' => $result]);
