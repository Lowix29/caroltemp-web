<?php
/* =============================================
   CAROLTEMP — Agente de Páginas: API
   Gestión y mejora de páginas estructurales
============================================= */
ob_start();
error_reporting(0);
ini_set('display_errors', 0);

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

ob_end_clean();
header('Content-Type: application/json; charset=utf-8');

// ── Ciudades del sistema (sin Villena) ──────────────────────────────
$ciudades = [
  'Elda'             => ['slug' => 'elda',     'cp' => '03600'],
  'Petrer'           => ['slug' => 'petrer',   'cp' => '03610'],
  'Novelda'          => ['slug' => 'novelda',  'cp' => '03660'],
  'Monóvar'          => ['slug' => 'monovar',  'cp' => '03640'],
  'Sax'              => ['slug' => 'sax',      'cp' => '03630'],
  'Pinoso'           => ['slug' => 'pinoso',   'cp' => '03650'],
  'Monforte del Cid' => ['slug' => 'monforte', 'cp' => '03670'],
  'Salinas'          => ['slug' => 'salinas',  'cp' => '03688'],
  'Aspe'             => ['slug' => 'aspe',     'cp' => '03680'],
];

// ── Perfiles por ciudad para diferenciación de contenido ───────────
$ciudad_perfiles = [
  'Elda'             => 'Ciudad industrial del calzado, con muchos edificios de los años 60-80 y instalaciones antiguas. Agua con dureza media-alta.',
  'Petrer'           => 'Zona residencial y urbanizaciones bien consolidadas. Agua muy dura y calcárea — problemas frecuentes de cal en conducciones y electrodomésticos.',
  'Novelda'          => 'Capital del mármol, alta actividad industrial. Agua durísima, la más calcárea de la comarca. Fincas y naves industriales mezcladas con residencial.',
  'Monóvar'          => 'Municipio rural y vinícola. Casas antiguas en el pueblo y chalets en campo. Muchas fincas con pozos y depósitos privados.',
  'Sax'              => 'Pueblo tranquilo con predominio de viviendas unifamiliares y chalets. Centro histórico con instalaciones antiguas, extrarradio más moderno.',
  'Pinoso'           => 'Municipio rural extenso con muchas casas de campo dispersas. Muchas propiedades con depósitos y pozos. Urgencias en zonas rurales con acceso difícil.',
  'Monforte del Cid' => 'Cerca del aeropuerto de Alicante. Urbanizaciones nuevas y segunda residencia. Mezcla de viviendas modernas y fincas rurales en las afueras.',
  'Salinas'          => 'Pueblo pequeño y tranquilo. Casco urbano con casas antiguas y alguna urbanización exterior. Acceso rápido por carretera.',
  'Aspe'             => 'Ciudad de tamaño medio con barrios variados. Edificios de los 70-80 en el centro y zonas residenciales nuevas. Agua con cal moderada.',
];

// ── Tipos de servicio y sus patrones de archivo ─────────────────────
$tipos_servicio = [
  'fugas'      => ['dir' => 'fugas',      'prefijo_archivo' => 'deteccion-fugas-', 'prefijo_url' => 'deteccion-fugas', 'nombre' => 'Detección de fugas'],
  'desatascos' => ['dir' => 'desatascos', 'prefijo_archivo' => 'desatascos-',      'prefijo_url' => 'desatascos',      'nombre' => 'Desatascos'],
  'fontanero'  => ['dir' => 'fontanero',  'prefijo_archivo' => 'fontanero-',       'prefijo_url' => 'fontanero',       'nombre' => 'Fontanero'],
];

$site_root = dirname(__DIR__);
$accion    = trim($_POST['accion'] ?? '');
$plan_file = dirname(__FILE__) . '/plan-auditor.json';

// ── Allowed dirs for guardar/eliminar (security) ────────────────────
$allowed_dirs = ['fugas', 'desatascos', 'fontanero', 'zonas'];

// ─────────────────────────────────────────────────────────────────────
// ACCIÓN: inventario
// ─────────────────────────────────────────────────────────────────────
if ($accion === 'inventario') {
  $matriz = [];

  // Silo: las páginas que debe tener cada ciudad en la arquitectura nueva
  $silo_cols = [
    'hub'            => ['label' => 'Hub Ciudad',       'filepath' => fn($s) => "fontanero/{$s}.php",                   'ruta_web' => fn($s) => "/fontanero/{$s}",                   'tipo' => 'hub_ciudad'],
    'urgencias'      => ['label' => 'Urgencias',        'filepath' => fn($s) => "fontanero/{$s}/urgencias.php",         'ruta_web' => fn($s) => "/fontanero/{$s}/urgencias",         'tipo' => 'urgencias'],
    'desatascos'     => ['label' => 'Desatascos',       'filepath' => fn($s) => "fontanero/{$s}/desatascos.php",        'ruta_web' => fn($s) => "/fontanero/{$s}/desatascos",        'tipo' => 'desatascos'],
    'busqueda_fugas' => ['label' => 'Búsqueda de fugas','filepath' => fn($s) => "fontanero/{$s}/busqueda_fugas.php",    'ruta_web' => fn($s) => "/fontanero/{$s}/busqueda_fugas",    'tipo' => 'busqueda_fugas'],
  ];

  foreach ($ciudades as $ciudad_nombre => $info) {
    $slug = $info['slug'];
    $cp   = $info['cp'];
    $row  = [
      'ciudad'   => $ciudad_nombre,
      'slug'     => $slug,
      'cp'       => $cp,
      'servicios' => [],
    ];

    foreach ($silo_cols as $col_key => $col_cfg) {
      $filepath_rel = ($col_cfg['filepath'])($slug);
      $abs          = $site_root . '/' . $filepath_rel;
      $existe       = file_exists($abs);
      $provisional  = false;
      if ($existe) {
        $cont        = file_get_contents($abs);
        $provisional = (strpos($cont, 'CONTENIDO PROVISIONAL') !== false);
      }
      $row['servicios'][$col_key] = [
        'existe'      => $existe,
        'provisional' => $provisional,
        'ruta_web'    => ($col_cfg['ruta_web'])($slug),
        'filepath'    => $filepath_rel,
        'tipo'        => $col_cfg['tipo'],
        'label'       => $col_cfg['label'],
      ];
    }

    $matriz[] = $row;
  }

  // Devolver también los labels de columnas para que el JS los renderice dinámicamente
  $cols = array_map(fn($k, $c) => ['key' => $k, 'label' => $c['label']], array_keys($silo_cols), $silo_cols);

  // Páginas corporativas (base + extras guardados)
  $corporativas_cfg = [
    ['label' => 'Home',           'filepath' => 'index.php',          'ruta_web' => '/'],
    ['label' => 'Contacto',       'filepath' => 'contacto.php',       'ruta_web' => '/contacto'],
    ['label' => 'Sobre nosotros', 'filepath' => 'sobre-nosotros.php', 'ruta_web' => '/sobre-nosotros'],
    ['label' => 'Financiación',   'filepath' => 'financiacion.php',   'ruta_web' => '/financiacion'],
  ];
  $extra_file_inv = __DIR__ . '/corporativas-extra.json';
  if (file_exists($extra_file_inv)) {
    $extras_inv = json_decode(file_get_contents($extra_file_inv), true);
    if (is_array($extras_inv)) {
      $existing_fps = array_column($corporativas_cfg, 'filepath');
      foreach ($extras_inv as $ex) {
        if (!in_array($ex['filepath'], $existing_fps, true)) {
          $corporativas_cfg[] = $ex + ['ruta_web' => '/' . ltrim(str_replace('.php','', $ex['filepath']), '/')];
        }
      }
    }
  }
  $corporativas = [];
  $base_fps     = ['index.php','contacto.php','sobre-nosotros.php','financiacion.php'];
  foreach ($corporativas_cfg as $corp) {
    $abs    = $site_root . '/' . $corp['filepath'];
    $existe = file_exists($abs);
    $corporativas[] = [
      'label'    => $corp['label'],
      'filepath' => $corp['filepath'],
      'ruta_web' => $corp['ruta_web'],
      'existe'   => $existe,
      'tipo'     => 'corporativa',
      'custom'   => !in_array($corp['filepath'], $base_fps, true),
    ];
  }

  echo json_encode(['ok' => true, 'matriz' => $matriz, 'cols' => $cols, 'corporativas' => $corporativas]);
  exit;
}

// ─────────────────────────────────────────────────────────────────────
// ACCIÓN: mejorar / crear (llamada a Claude)
// ─────────────────────────────────────────────────────────────────────
if ($accion === 'mejorar' || $accion === 'crear') {
  set_time_limit(120);
  // Verificar curl
  if (!function_exists('curl_init')) {
    echo json_encode(['error' => 'curl no está habilitado en PHP.']);
    exit;
  }
  // Verificar API key
  if (!defined('ANTHROPIC_API_KEY') || strlen(ANTHROPIC_API_KEY) < 20) {
    echo json_encode(['error' => 'API key no configurada. Revisa includes/config.php']);
    exit;
  }

  $tipo        = trim($_POST['tipo']        ?? '');
  $ciudad      = trim($_POST['ciudad']      ?? '');
  $ciudad_slug = trim($_POST['ciudad_slug'] ?? '');
  $ciudad_cp   = trim($_POST['ciudad_cp']   ?? '');
  $filepath_in = trim($_POST['filepath']    ?? '');
  $label_in    = trim($_POST['label']       ?? $tipo);

  // ── Generación para páginas corporativas (sin ciudad) ────────────
  if ($tipo === 'corporativa') {
    if (!$filepath_in) {
      echo json_encode(['error' => 'Falta filepath para página corporativa']);
      exit;
    }
    $basename  = basename($filepath_in, '.php');
    $page_types_labels = [
      'index'          => 'Home — página principal',
      'contacto'       => 'Contacto',
      'sobre-nosotros' => 'Sobre nosotros / Quiénes somos',
      'financiacion'   => 'Financiación',
    ];
    $desc_pagina = $page_types_labels[$basename] ?? $label_in ?: $basename;

    $system_corp = <<<SYS
Eres redactor web para CarolTemp, empresa de fontanería y climatización en la comarca interior de Alicante (Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Monforte del Cid, Salinas, Aspe).

REGLAS:
- No inventes estadísticas ni porcentajes
- Texto corto, directo y útil
- Teléfono de contacto: 613 429 032
- Sin "Vinalopó"

Devuelve SOLO JSON válido:
{
  "meta_title": "máx 60 chars",
  "meta_desc": "150-160 chars",
  "h1": "título principal de la página",
  "intro": "párrafo de introducción (2-3 frases)",
  "secciones": [
    {"titulo": "...", "texto": "..."},
    {"titulo": "...", "texto": "..."}
  ]
}

CRÍTICO: comillas dobles para todo el JSON, sin comillas dobles dentro de los valores.
SYS;

    $payload_corp = [
      'model'      => ANTHROPIC_MODEL,
      'max_tokens' => 1200,
      'system'     => $system_corp,
      'messages'   => [
        ['role' => 'user',      'content' => "Genera el contenido para la página: {$desc_pagina}\nEmpresa: CarolTemp — fontanería y climatización en Alicante interior."],
        ['role' => 'assistant', 'content' => '{'],
      ],
    ];

    $ch = curl_init('https://api.anthropic.com/v1/messages');
    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST           => true,
      CURLOPT_POSTFIELDS     => json_encode($payload_corp),
      CURLOPT_HTTPHEADER     => [
        'x-api-key: '         . ANTHROPIC_API_KEY,
        'anthropic-version: 2023-06-01',
        'content-type: application/json',
      ],
      CURLOPT_TIMEOUT        => 90,
      CURLOPT_CONNECTTIMEOUT => 15,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_SSL_VERIFYHOST => false,
    ]);

    $raw_corp  = curl_exec($ch);
    $http_corp = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!$raw_corp) { echo json_encode(['error' => 'No se pudo conectar con la API de Claude.']); exit; }
    $resp_corp = json_decode($raw_corp, true);
    if ($http_corp !== 200 || empty($resp_corp['content'][0]['text'])) {
      $msg = $resp_corp['error']['message'] ?? 'Error desconocido';
      echo json_encode(['error' => "Error API Claude ({$http_corp}): {$msg}"]); exit;
    }
    $json_corp = '{' . $resp_corp['content'][0]['text'];
    $data_corp = json_decode($json_corp, true);
    if (!$data_corp) {
      if (preg_match('/\{[\s\S]*\}/u', $json_corp, $m)) $data_corp = json_decode($m[0], true);
    }
    if (!$data_corp) { echo json_encode(['error' => 'Claude no devolvió JSON válido.']); exit; }

    $e   = fn($v) => var_export($v, true);
    $meta_title = $data_corp['meta_title'] ?? $desc_pagina . ' — CarolTemp';
    $meta_desc  = $data_corp['meta_desc']  ?? '';
    $h1         = $data_corp['h1']         ?? $desc_pagina;
    $intro      = $data_corp['intro']      ?? '';
    $secciones  = $data_corp['secciones']  ?? [];
    $meta_url   = 'https://caroltemp.com/' . ltrim($filepath_in, '/');

    $secs_html = '';
    foreach ($secciones as $sec) {
      $secs_html .= '<section class="page-section"><div class="container"><h2>' . htmlspecialchars($sec['titulo'] ?? '') . '</h2><p>' . htmlspecialchars($sec['texto'] ?? '') . '</p></div></section>' . "\n";
    }

    $php_corp  = "<?php\n";
    $php_corp .= "/**\n * {$desc_pagina}\n * Generado por Agente de Páginas\n */\n";
    $php_corp .= "\$meta_title  = {$e($meta_title)};\n";
    $php_corp .= "\$meta_desc   = {$e($meta_desc)};\n";
    $php_corp .= "\$meta_url    = {$e($meta_url)};\n";
    $php_corp .= "\$schema_type = 'local';\n";
    $php_corp .= "\$page_css    = 'default';\n";
    $php_corp .= "\$page_js     = '';\n";
    $php_corp .= "include 'includes/head.php';\n";
    $php_corp .= "?>\n\n";
    $php_corp .= "<section class=\"page-hero\">\n  <div class=\"container\">\n    <h1>{$h1}</h1>\n    <p class=\"hero-sub\">{$intro}</p>\n  </div>\n</section>\n\n";
    $php_corp .= $secs_html;
    $php_corp .= "\n<?php include 'includes/footer.php'; ?>\n";

    echo json_encode([
      'ok'            => true,
      'php_contenido' => $php_corp,
      'filepath'      => $filepath_in,
      'meta_title'    => $meta_title,
      'meta_desc'     => $meta_desc,
    ]);
    exit;
  }

  if (!$tipo || !$ciudad || !$ciudad_slug) {
    echo json_encode(['error' => 'Faltan parámetros requeridos: tipo, ciudad, ciudad_slug']);
    exit;
  }
  if (!isset($ciudades[$ciudad])) {
    echo json_encode(['error' => 'Ciudad no válida: ' . $ciudad]);
    exit;
  }

  // ── Generación especial para páginas de zona ──────────────────────
  if ($tipo === 'zona') {
    $perfil_ciudad = $ciudad_perfiles[$ciudad] ?? 'Municipio de la comarca interior de Alicante.';
    $otras_zonas   = [];
    foreach ($ciudades as $c_nombre => $c_info) {
      if ($c_info['slug'] === $ciudad_slug) continue;
      $otras_zonas[] = ['nombre' => $c_nombre, 'slug' => $c_info['slug']];
    }
    $zona_filename = $ciudad_slug . '.php';

    $system_zona = <<<SYS
Eres un experto en SEO local para CarolTemp, empresa de fontanería en la comarca interior de Alicante.

REGLAS ABSOLUTAS:
- NUNCA escribas "Vinalopó" en ningún sitio
- NUNCA inventes estadísticas, años de experiencia, número de clientes ni porcentajes
- Texto CORTO y directo — cada frase debe aportar algo útil al cliente
- Año actual: {$anyo}

SOBRE CAROLTEMP:
- Zona: Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Monforte del Cid, Salinas, Aspe
- Diferenciadores: presupuesto gratuito sin compromiso, urgencias, instaladores certificados Nubeco
- Servicios: fontanería urgente, fugas (geófono+cámara), desatascos, termos, descalcificadores, reformas

DIFERENCIACIÓN: El contenido DEBE reflejar las características concretas de la ciudad. NO copies texto genérico.

DEVUELVE ÚNICAMENTE JSON VÁLIDO con esta estructura:
{
  "meta_title": "máx 60 chars — 'Fontanería en [Ciudad] — CarolTemp'",
  "meta_desc": "150-160 chars — qué + dónde + por qué CarolTemp",
  "hero_sub": "1 frase de 10-15 palabras describiendo el servicio en la ciudad",
  "intro_p1": "2-3 frases sobre las características concretas de la ciudad y cómo afectan a la fontanería",
  "intro_p2": "1-2 frases sobre qué cubre CarolTemp en esta ciudad",
  "checklist": ["ítem específico 1", "ítem específico 2", "ítem específico 3", "ítem específico 4", "ítem específico 5"],
  "faq": [
    {"pregunta": "¿Cuánto cuesta un fontanero en [Ciudad]?", "respuesta": "Precio cerrado antes de empezar. Una reparación sencilla desde 60-80€."},
    {"pregunta": "¿Tenéis servicio urgente en [Ciudad]?", "respuesta": "Sí, atendemos urgencias en [Ciudad] dentro del horario de servicio."},
    {"pregunta": "¿Cómo detectáis fugas sin romper en [Ciudad]?", "respuesta": "Con geófono y cámara localizamos el punto exacto antes de abrir."},
    {"pregunta": "¿Hacéis desatascos en [Ciudad]?", "respuesta": "Sí, fregaderos, lavabos, bajantes y arquetas en [Ciudad]."},
    {"pregunta": "¿Ofrecéis financiación en [Ciudad]?", "respuesta": "Sí, para reformas, descalcificadores y proyectos grandes."}
  ]
}

CRÍTICO: Usa comillas dobles para todo el JSON. No uses comillas dobles DENTRO de los valores.
SYS;

    $user_zona  = "Ciudad: {$ciudad} (CP: {$ciudad_cp}, slug: {$ciudad_slug})\n";
    $user_zona .= "Características de {$ciudad}: {$perfil_ciudad}\n\n";
    $user_zona .= "Genera la página de zona. intro_p1 y checklist deben reflejar las características específicas de {$ciudad}, no texto genérico.";

    $payload_zona = [
      'model'      => ANTHROPIC_MODEL,
      'max_tokens' => 2048,
      'system'     => $system_zona,
      'messages'   => [
        ['role' => 'user',      'content' => $user_zona],
        ['role' => 'assistant', 'content' => '{'],
      ],
    ];

    $ch = curl_init('https://api.anthropic.com/v1/messages');
    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST           => true,
      CURLOPT_POSTFIELDS     => json_encode($payload_zona),
      CURLOPT_HTTPHEADER     => [
        'x-api-key: '         . ANTHROPIC_API_KEY,
        'anthropic-version: 2023-06-01',
        'content-type: application/json',
      ],
      CURLOPT_TIMEOUT         => 90,
      CURLOPT_CONNECTTIMEOUT  => 15,
      CURLOPT_SSL_VERIFYPEER  => false,
      CURLOPT_SSL_VERIFYHOST  => false,
    ]);

    $raw_zona  = curl_exec($ch);
    $http_zona = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!$raw_zona) {
      echo json_encode(['error' => 'No se pudo conectar con la API de Claude.']);
      exit;
    }

    $resp_zona = json_decode($raw_zona, true);

    if ($http_zona !== 200 || empty($resp_zona['content'][0]['text'])) {
      $msg = $resp_zona['error']['message'] ?? 'Error desconocido';
      echo json_encode(['error' => "Error API Claude ({$http_zona}): {$msg}"]);
      exit;
    }

    $text_zona   = $resp_zona['content'][0]['text'];
    $stop_zona   = $resp_zona['stop_reason'] ?? '';

    if ($stop_zona === 'max_tokens') {
      echo json_encode(['error' => 'Respuesta demasiado larga. Inténtalo de nuevo.']);
      exit;
    }

    $json_zona = '{' . $text_zona;
    $data_zona = json_decode($json_zona, true);
    if (!$data_zona) {
      if (preg_match('/\{[\s\S]*\}/u', $json_zona, $m)) {
        $data_zona = json_decode($m[0], true);
      }
    }
    if (!$data_zona) {
      echo json_encode(['error' => 'Claude no devolvió JSON válido. Inténtalo de nuevo.', 'raw' => substr($json_zona, 0, 500)]);
      exit;
    }

    $php_zona = generar_php_zona($data_zona, $ciudad, $ciudad_slug, $ciudad_cp, $otras_zonas);

    echo json_encode([
      'ok'            => true,
      'php_contenido' => $php_zona,
      'filepath'      => 'zonas/' . $zona_filename,
      'meta_title'    => $data_zona['meta_title'] ?? '',
      'meta_desc'     => $data_zona['meta_desc']  ?? '',
    ]);
    exit;
  }

  // ── Hub ciudad (fontanero/{slug}.php) ────────────────────────────────────
  if ($tipo === 'hub_ciudad') {
    $perfil_ciudad = $ciudad_perfiles[$ciudad] ?? 'Municipio de la comarca interior de Alicante.';
    $otras_zonas   = [];
    foreach ($ciudades as $c_nombre => $c_info) {
      if ($c_info['slug'] === $ciudad_slug) continue;
      $otras_zonas[] = ['nombre' => $c_nombre, 'slug' => $c_info['slug']];
    }
    $hub_filename = $ciudad_slug . '.php';

    $system_hub = <<<SYS
Eres un experto en SEO local para CarolTemp, empresa de fontanería en la comarca interior de Alicante.

REGLAS ABSOLUTAS:
- NUNCA escribas "Vinalopó" en ningún sitio
- NUNCA inventes estadísticas, años de experiencia, número de clientes ni porcentajes
- Texto CORTO y directo — cada frase debe aportar algo útil al cliente
- Año actual: {$anyo}

SOBRE CAROLTEMP:
- Zona: Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Monforte del Cid, Salinas, Aspe
- Diferenciadores: presupuesto gratuito sin compromiso, urgencias, instaladores certificados Nubeco
- Servicios: fontanería urgente, fugas (geófono+cámara), desatascos, termos, descalcificadores, reformas

Esta es la página PRINCIPAL de fontanería de la ciudad. Habla de todos los servicios disponibles.
Las sub-páginas más importantes son: urgencias, fugas, desatascos.

DEVUELVE ÚNICAMENTE JSON VÁLIDO con esta estructura:
{
  "meta_title": "máx 60 chars — 'Fontanería en [Ciudad] — CarolTemp'",
  "meta_desc": "150-160 chars — qué + dónde + por qué CarolTemp",
  "hero_sub": "1 frase de 10-15 palabras describiendo el servicio en la ciudad",
  "intro_p1": "2-3 frases sobre las características concretas de la ciudad y cómo afectan a la fontanería",
  "intro_p2": "1-2 frases sobre qué cubre CarolTemp en esta ciudad",
  "checklist": ["ítem específico 1", "ítem específico 2", "ítem específico 3", "ítem específico 4", "ítem específico 5"],
  "faq": [
    {"pregunta": "¿Cuánto cuesta un fontanero en [Ciudad]?", "respuesta": "Precio cerrado antes de empezar. Una reparación sencilla desde 60-80€."},
    {"pregunta": "¿Tenéis servicio urgente en [Ciudad]?", "respuesta": "Sí, atendemos urgencias en [Ciudad] dentro del horario de servicio."},
    {"pregunta": "¿Cómo detectáis fugas sin romper en [Ciudad]?", "respuesta": "Con geófono y cámara localizamos el punto exacto antes de abrir."},
    {"pregunta": "¿Hacéis desatascos en [Ciudad]?", "respuesta": "Sí, fregaderos, lavabos, bajantes y arquetas en [Ciudad]."},
    {"pregunta": "¿Ofrecéis financiación en [Ciudad]?", "respuesta": "Sí, para reformas, descalcificadores y proyectos grandes."}
  ]
}

CRÍTICO: Usa comillas dobles para todo el JSON. No uses comillas dobles DENTRO de los valores.
SYS;

    $user_hub  = "Ciudad: {$ciudad} (CP: {$ciudad_cp}, slug: {$ciudad_slug})\n";
    $user_hub .= "Características de {$ciudad}: {$perfil_ciudad}\n\n";
    $user_hub .= "Genera la página principal de fontanería de {$ciudad}. intro_p1 y checklist deben reflejar las características específicas de {$ciudad}.";

    $payload_hub = [
      'model'      => ANTHROPIC_MODEL,
      'max_tokens' => 2048,
      'system'     => $system_hub,
      'messages'   => [
        ['role' => 'user',      'content' => $user_hub],
        ['role' => 'assistant', 'content' => '{'],
      ],
    ];

    $ch = curl_init('https://api.anthropic.com/v1/messages');
    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST           => true,
      CURLOPT_POSTFIELDS     => json_encode($payload_hub),
      CURLOPT_HTTPHEADER     => [
        'x-api-key: '         . ANTHROPIC_API_KEY,
        'anthropic-version: 2023-06-01',
        'content-type: application/json',
      ],
      CURLOPT_TIMEOUT         => 90,
      CURLOPT_CONNECTTIMEOUT  => 15,
      CURLOPT_SSL_VERIFYPEER  => false,
      CURLOPT_SSL_VERIFYHOST  => false,
    ]);

    $raw_hub  = curl_exec($ch);
    $http_hub = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!$raw_hub) { echo json_encode(['error' => 'No se pudo conectar con la API de Claude.']); exit; }

    $resp_hub = json_decode($raw_hub, true);
    if ($http_hub !== 200 || empty($resp_hub['content'][0]['text'])) {
      $msg = $resp_hub['error']['message'] ?? 'Error desconocido';
      echo json_encode(['error' => "Error API Claude ({$http_hub}): {$msg}"]);
      exit;
    }

    $text_hub = $resp_hub['content'][0]['text'];
    if (($resp_hub['stop_reason'] ?? '') === 'max_tokens') {
      echo json_encode(['error' => 'Respuesta demasiado larga. Inténtalo de nuevo.']);
      exit;
    }

    $json_hub  = '{' . $text_hub;
    $data_hub  = json_decode($json_hub, true);
    if (!$data_hub && preg_match('/\{[\s\S]*\}/u', $json_hub, $m)) $data_hub = json_decode($m[0], true);
    if (!$data_hub) {
      echo json_encode(['error' => 'Claude no devolvió JSON válido.', 'raw' => substr($json_hub, 0, 500)]);
      exit;
    }

    $php_hub = generar_php_hub_ciudad($data_hub, $ciudad, $ciudad_slug, $ciudad_cp, $otras_zonas);

    echo json_encode([
      'ok'            => true,
      'php_contenido' => $php_hub,
      'filepath'      => 'fontanero/' . $hub_filename,
      'meta_title'    => $data_hub['meta_title'] ?? '',
      'meta_desc'     => $data_hub['meta_desc']  ?? '',
    ]);
    exit;
  }

  // ── Búsqueda de fugas silo (fontanero/{slug}/busqueda_fugas.php) ────────────
  if ($tipo === 'busqueda_fugas') {
    $perfil_ciudad = $ciudad_perfiles[$ciudad] ?? '';
    $tipo_cfg_fugas = [
      'dir'             => 'fontanero/' . $ciudad_slug,
      'prefijo_archivo' => '',
      'prefijo_url'     => 'fontanero/' . $ciudad_slug,
      'nombre'          => 'Búsqueda de fugas',
    ];
    $otras_ciudades = [];
    foreach ($ciudades as $c_nombre => $c_info) {
      if ($c_info['slug'] === $ciudad_slug) continue;
      $otras_ciudades[] = ['nombre' => $c_nombre, 'slug' => $c_info['slug'], 'prefijo' => 'fontanero'];
    }

    $system_fugas = <<<SYS
Eres un experto en SEO local para CarolTemp, empresa de fontanería en la comarca interior de Alicante.

REGLAS ABSOLUTAS:
- NUNCA escribas "Vinalopó" en ningún sitio
- NUNCA inventes estadísticas ni porcentajes
- Texto CORTO y directo
- Año actual: {$anyo}

SOBRE CAROLTEMP:
- Diferenciadores: geófono profesional, cámara de inspección, sin romper paredes innecesariamente
- Tipos de fugas que detectan: empotradas, suelo radiante, piscinas, bajantes, comunidades, exterior

DEVUELVE ÚNICAMENTE JSON VÁLIDO:
{
  "meta_title": "máx 60 chars — 'Búsqueda de fugas en [Ciudad] — CarolTemp'",
  "meta_desc": "150-160 chars — geófono + cámara + ciudad + sin obras",
  "hero_titulo": "Búsqueda de fugas en {$ciudad}<br><span class=\"hl\">sin romper paredes.</span>",
  "hero_sub": "1 frase de 10-15 palabras sobre detección de fugas en la ciudad",
  "contenido_intro": "<p>2 frases sobre por qué las fugas son un problema en {$ciudad} y cómo CarolTemp las localiza.</p>",
  "servicios_lista": ["Fugas en tuberías empotradas", "Fugas en suelo radiante", "Fugas en piscinas", "Fugas en comunidades de vecinos", "Fugas en bajantes y arquetas", "Fugas en instalaciones exteriores"],
  "problemas_zona": [
    {"titulo": "Problema de fugas típico 1 en {$ciudad}", "texto": "Explicación en 1-2 frases específica para esta ciudad."},
    {"titulo": "Problema típico 2", "texto": "Explicación."},
    {"titulo": "Problema típico 3", "texto": "Explicación."}
  ],
  "faq": [
    {"pregunta": "¿Cómo detectáis fugas sin romper en {$ciudad}?", "respuesta": "Usamos geófono acústico y cámara. Localizamos el punto exacto antes de actuar."},
    {"pregunta": "¿Cuánto cuesta la búsqueda de fugas en {$ciudad}?", "respuesta": "Precio cerrado antes de empezar. Sin sorpresas."},
    {"pregunta": "¿También reparáis la fuga una vez localizada?", "respuesta": "Sí, detectamos y reparamos. No necesitas llamar a otra empresa."},
    {"pregunta": "¿Cuánto tardáis en llegar a {$ciudad}?", "respuesta": "Atendemos {$ciudad} y trabajamos por cita o urgencia según disponibilidad."}
  ]
}

CRÍTICO: Usa comillas dobles para todo el JSON. No uses comillas dobles DENTRO de los valores.
SYS;

    $user_fugas = "Ciudad: {$ciudad} (CP: {$ciudad_cp}, slug: {$ciudad_slug})\n";
    if ($perfil_ciudad) $user_fugas .= "Características de {$ciudad}: {$perfil_ciudad}\n";
    $user_fugas .= "Genera la página de búsqueda de fugas para {$ciudad}.";

    $payload_fugas = [
      'model'      => ANTHROPIC_MODEL,
      'max_tokens' => 2048,
      'system'     => $system_fugas,
      'messages'   => [
        ['role' => 'user',      'content' => $user_fugas],
        ['role' => 'assistant', 'content' => '{'],
      ],
    ];

    $ch = curl_init('https://api.anthropic.com/v1/messages');
    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST           => true,
      CURLOPT_POSTFIELDS     => json_encode($payload_fugas),
      CURLOPT_HTTPHEADER     => ['x-api-key: ' . ANTHROPIC_API_KEY, 'anthropic-version: 2023-06-01', 'content-type: application/json'],
      CURLOPT_TIMEOUT         => 90,
      CURLOPT_CONNECTTIMEOUT  => 15,
      CURLOPT_SSL_VERIFYPEER  => false,
      CURLOPT_SSL_VERIFYHOST  => false,
    ]);
    $raw_fugas  = curl_exec($ch);
    $http_fugas = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!$raw_fugas) { echo json_encode(['error' => 'No se pudo conectar con la API de Claude.']); exit; }
    $resp_fugas = json_decode($raw_fugas, true);
    if ($http_fugas !== 200 || empty($resp_fugas['content'][0]['text'])) {
      echo json_encode(['error' => 'Error API Claude (' . $http_fugas . '): ' . ($resp_fugas['error']['message'] ?? 'Error')]); exit;
    }
    $text_fugas = $resp_fugas['content'][0]['text'];
    if (($resp_fugas['stop_reason'] ?? '') === 'max_tokens') { echo json_encode(['error' => 'Respuesta demasiado larga.']); exit; }

    $json_fugas = '{' . $text_fugas;
    $data_fugas = json_decode($json_fugas, true);
    if (!$data_fugas && preg_match('/\{[\s\S]*\}/u', $json_fugas, $m)) $data_fugas = json_decode($m[0], true);
    if (!$data_fugas) { echo json_encode(['error' => 'Claude no devolvió JSON válido.']); exit; }

    $filepath_fugas = 'fontanero/' . $ciudad_slug . '/busqueda_fugas.php';
    $php_fugas = generar_php_servicio($data_fugas, $tipo_cfg_fugas, 'busqueda_fugas', $ciudad, $ciudad_slug, $ciudad_cp, $otras_ciudades, 2);

    echo json_encode([
      'ok'            => true,
      'php_contenido' => $php_fugas,
      'filepath'      => $filepath_fugas,
      'meta_title'    => $data_fugas['meta_title'] ?? '',
      'meta_desc'     => $data_fugas['meta_desc']  ?? '',
    ]);
    exit;
  }

  // ── Urgencias silo (fontanero/{slug}/urgencias.php) ──────────────────────
  if ($tipo === 'urgencias') {
    $perfil_ciudad = $ciudad_perfiles[$ciudad] ?? '';
    $tipo_cfg_urg  = [
      'dir'             => 'fontanero/' . $ciudad_slug,
      'prefijo_archivo' => '',
      'prefijo_url'     => 'fontanero/' . $ciudad_slug,
      'nombre'          => 'Fontanero urgente',
    ];
    $otras_ciudades = [];
    foreach ($ciudades as $c_nombre => $c_info) {
      if ($c_info['slug'] === $ciudad_slug) continue;
      $otras_ciudades[] = ['nombre' => $c_nombre, 'slug' => $c_info['slug'], 'prefijo' => 'fontanero'];
    }

    $system_urg = <<<SYS
Eres un experto en SEO local para CarolTemp, empresa de fontanería en la comarca interior de Alicante.

REGLAS ABSOLUTAS:
- NUNCA escribas "Vinalopó" en ningún sitio
- NUNCA inventes estadísticas ni porcentajes
- Texto CORTO y directo
- Año actual: {$anyo}

SOBRE CAROLTEMP:
- Zona: Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Monforte del Cid, Salinas, Aspe
- Diferenciadores: presupuesto gratuito, urgencias, instaladores certificados Nubeco
- Servicios urgentes: roturas de tuberías, grifos, cisternas, pérdidas de agua

DEVUELVE ÚNICAMENTE JSON VÁLIDO:
{
  "meta_title": "máx 60 chars — 'Fontanero urgente en [Ciudad] — CarolTemp'",
  "meta_desc": "150-160 chars — urgencias + ciudad + CarolTemp",
  "hero_titulo": "Fontanero urgente en {$ciudad}<br><span class=\"hl\">precio cerrado.</span>",
  "hero_sub": "1 frase de 10-15 palabras sobre urgencias en la ciudad",
  "contenido_intro": "<p>2 frases sobre urgencias de fontanería en {$ciudad}.</p>",
  "servicios_lista": ["Roturas de tuberías", "Grifos y cisternas", "Pérdidas de agua", "Válvulas y llaves de paso", "Termos averiados", "Bajantes urgentes"],
  "problemas_zona": [
    {"titulo": "Problema urgente típico 1", "texto": "Explicación en 1-2 frases para {$ciudad}."},
    {"titulo": "Problema urgente típico 2", "texto": "Explicación en 1-2 frases."},
    {"titulo": "Problema urgente típico 3", "texto": "Explicación en 1-2 frases."}
  ],
  "faq": [
    {"pregunta": "¿Cuánto cuesta una urgencia de fontanería en {$ciudad}?", "respuesta": "Presupuesto cerrado antes de empezar. Sin sorpresas."},
    {"pregunta": "¿Atendéis urgencias en {$ciudad}?", "respuesta": "Sí, atendemos urgencias en {$ciudad} y toda la comarca."},
    {"pregunta": "¿Cuánto tardan en llegar a {$ciudad}?", "respuesta": "Dependiendo de la carga de trabajo, intentamos atender el mismo día."},
    {"pregunta": "¿Qué pasa si la avería es muy grave?", "respuesta": "Cierra la llave de paso y llámanos. Atendemos la urgencia cuanto antes."}
  ]
}

CRÍTICO: Usa comillas dobles para todo el JSON. No uses comillas dobles DENTRO de los valores.
SYS;

    $user_urg = "Ciudad: {$ciudad} (CP: {$ciudad_cp}, slug: {$ciudad_slug})\n";
    if ($perfil_ciudad) $user_urg .= "Características de {$ciudad}: {$perfil_ciudad}\n";
    $user_urg .= "Genera la página de fontanero urgente para {$ciudad}.";

    $payload_urg = [
      'model'      => ANTHROPIC_MODEL,
      'max_tokens' => 2048,
      'system'     => $system_urg,
      'messages'   => [
        ['role' => 'user',      'content' => $user_urg],
        ['role' => 'assistant', 'content' => '{'],
      ],
    ];

    $ch = curl_init('https://api.anthropic.com/v1/messages');
    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POST           => true,
      CURLOPT_POSTFIELDS     => json_encode($payload_urg),
      CURLOPT_HTTPHEADER     => [
        'x-api-key: '         . ANTHROPIC_API_KEY,
        'anthropic-version: 2023-06-01',
        'content-type: application/json',
      ],
      CURLOPT_TIMEOUT         => 90,
      CURLOPT_CONNECTTIMEOUT  => 15,
      CURLOPT_SSL_VERIFYPEER  => false,
      CURLOPT_SSL_VERIFYHOST  => false,
    ]);

    $raw_urg  = curl_exec($ch);
    $http_urg = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (!$raw_urg) { echo json_encode(['error' => 'No se pudo conectar con la API de Claude.']); exit; }

    $resp_urg = json_decode($raw_urg, true);
    if ($http_urg !== 200 || empty($resp_urg['content'][0]['text'])) {
      $msg = $resp_urg['error']['message'] ?? 'Error desconocido';
      echo json_encode(['error' => "Error API Claude ({$http_urg}): {$msg}"]);
      exit;
    }

    $text_urg = $resp_urg['content'][0]['text'];
    if (($resp_urg['stop_reason'] ?? '') === 'max_tokens') {
      echo json_encode(['error' => 'Respuesta demasiado larga. Inténtalo de nuevo.']);
      exit;
    }

    $json_urg = '{' . $text_urg;
    $data_urg = json_decode($json_urg, true);
    if (!$data_urg && preg_match('/\{[\s\S]*\}/u', $json_urg, $m)) $data_urg = json_decode($m[0], true);
    if (!$data_urg) {
      echo json_encode(['error' => 'Claude no devolvió JSON válido.', 'raw' => substr($json_urg, 0, 500)]);
      exit;
    }

    $filepath_urg = 'fontanero/' . $ciudad_slug . '/urgencias.php';
    $php_urg = generar_php_servicio($data_urg, $tipo_cfg_urg, 'urgencias', $ciudad, $ciudad_slug, $ciudad_cp, $otras_ciudades, 2);

    echo json_encode([
      'ok'            => true,
      'php_contenido' => $php_urg,
      'filepath'      => $filepath_urg,
      'meta_title'    => $data_urg['meta_title'] ?? '',
      'meta_desc'     => $data_urg['meta_desc']  ?? '',
    ]);
    exit;
  }

  if (!isset($tipos_servicio[$tipo])) {
    echo json_encode(['error' => 'Tipo de servicio no válido: ' . $tipo]);
    exit;
  }

  // Detect if this is a silo sub-page (depth 2: fontanero/{slug}/{tipo}.php)
  $filepath_requested = trim($_POST['filepath'] ?? '');
  $silo_depth = (substr_count($filepath_requested, '/') >= 2) ? 2 : 1;

  $tipo_cfg  = $tipos_servicio[$tipo];
  $filename  = $tipo_cfg['prefijo_archivo'] . $ciudad_slug . '.php';
  $filepath  = $site_root . '/' . $tipo_cfg['dir'] . '/' . $filename;

  // Leer el contenido actual si existe
  $contenido_actual = '';
  if (file_exists($filepath)) {
    $contenido_actual = file_get_contents($filepath);
  }

  // ── Construir array de ciudades cercanas ────────────────────────────
  $otras_ciudades = [];
  foreach ($ciudades as $c_nombre => $c_info) {
    if ($c_info['slug'] === $ciudad_slug) continue;
    $otras_ciudades[] = [
      'nombre'  => $c_nombre,
      'slug'    => $c_info['slug'],
      'prefijo' => $tipo_cfg['prefijo_url'],
    ];
  }

  // ── System prompt ─────────────────────────────────────────────────
  $anyo = date('Y');
  $servicio_nombre = $tipo_cfg['nombre'];

  $system = <<<SYS
Eres un experto en SEO local para CarolTemp, empresa de fontanería en la comarca interior de Alicante.

REGLAS ABSOLUTAS:
- NUNCA escribas "Vinalopó" en ningún sitio
- NUNCA inventes estadísticas, años de experiencia, número de clientes ni porcentajes
- Texto CORTO y directo — cada frase debe aportar algo útil al cliente
- Año actual: {$anyo}

SOBRE CAROLTEMP:
- Zona: Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Monforte del Cid, Salinas, Aspe
- Diferenciadores: presupuesto gratuito sin compromiso, urgencias 24h, instaladores certificados
- Servicios: fontanería urgente, fugas (geófono+cámara), desatascos, termos, aire acondicionado, reformas

DIFERENCIACIÓN POR CIUDAD: El contenido DEBE ser específico para esa ciudad. En problemas_zona describe 3 problemas REALES y distintos de esa ciudad concreta (agua dura en Petrer/Novelda, tuberías antiguas en Elda, instalaciones rurales en Pinoso/Monóvar, etc.). Las FAQs deben mencionar la ciudad por nombre y reflejar sus características. PROHIBIDO copiar el mismo texto cambiando solo el nombre de ciudad.

DEVUELVE ÚNICAMENTE JSON VÁLIDO con esta estructura exacta:
{
  "meta_title": "máx 60 chars con keyword + ciudad + | CarolTemp",
  "meta_desc": "150-160 chars exactos — qué + dónde + por qué CarolTemp + CTA",
  "hero_titulo": "Servicio en Ciudad<br><span class=\"hl\">gancho corto.</span>",
  "hero_sub": "Una frase de 10-15 palabras con dato diferenciador",
  "contenido_intro": "<p>Máximo 2 frases. Responder la intención de búsqueda.</p>",
  "servicios_lista": ["item 1", "item 2", "item 3", "item 4", "item 5", "item 6"],
  "problemas_zona": [
    {"titulo": "Problema concreto", "texto": "Explicación en 1-2 frases sobre por qué pasa en esta ciudad específicamente."},
    {"titulo": "...", "texto": "..."},
    {"titulo": "...", "texto": "..."}
  ],
  "faq": [
    {"pregunta": "¿Pregunta real de búsqueda en {$ciudad}?", "respuesta": "Respuesta directa en 1-2 frases."},
    {"pregunta": "...", "respuesta": "..."},
    {"pregunta": "...", "respuesta": "..."},
    {"pregunta": "...", "respuesta": "..."}
  ]
}

CRÍTICO: Usa comillas dobles para todo el JSON. No uses comillas dobles DENTRO de los valores — usa comillas simples en HTML y apóstrofes escapados.
SYS;

  // ── Instrucciones por tipo de servicio ────────────────────────────
  $instrucciones = [
    'fugas' => "Página de DETECCIÓN DE FUGAS en {$ciudad}.\n- meta_title: incluye 'detección fugas' + ciudad\n- hero_titulo gancho: '...sin obras.' o '...sin abrir paredes.'\n- servicios_lista: 6 tipos de fugas que detectan (empotradas, suelo radiante, piscinas, comunidades, calefacción, exterior)\n- problemas_zona: 3 problemas típicos de fugas en {$ciudad} específicamente\n- faq: 4 preguntas sobre detección de fugas en {$ciudad} (precio, cómo detectan, si reparan también, tiempo respuesta)",
    'desatascos' => "Página de DESATASCOS en {$ciudad}.\n- meta_title: incluye 'desatascos' + ciudad\n- hero_titulo gancho: '...urgentes.' o '...hoy mismo.'\n- servicios_lista: 6 tipos de atascos (fregadero, lavabo, bañera, bajante, arqueta, comunidades)\n- problemas_zona: 3 causas típicas de atascos en {$ciudad}\n- faq: 4 preguntas sobre desatascos en {$ciudad} (precio, urgencias, qué incluye, tiempo)",
    'fontanero' => "Página de FONTANERO en {$ciudad}.\n- meta_title: incluye 'fontanero' + ciudad\n- hero_titulo gancho: '...urgencias 24h.' o '...precio cerrado.'\n- servicios_lista: 6 trabajos (reparaciones urgentes, fugas, desatascos, termos, descalcificadores, reformas de baño)\n- problemas_zona: 3 situaciones típicas de fontanería en {$ciudad} donde llaman a CarolTemp\n- faq: 4 preguntas sobre fontanero en {$ciudad} (precio visita, urgencias, garantía, qué hacen)",
  ];

  $perfil_ciudad = $ciudad_perfiles[$ciudad] ?? '';
  $user_msg  = "Ciudad: {$ciudad} (CP: {$ciudad_cp}, slug: {$ciudad_slug})\n";
  if ($perfil_ciudad) {
    $user_msg .= "Características de {$ciudad}: {$perfil_ciudad}\n";
  }
  $user_msg .= "Servicio: {$servicio_nombre}\n\n";
  $user_msg .= $instrucciones[$tipo] ?? '';

  // ── Llamada a Claude (prefill { para garantizar JSON puro) ────────
  $payload = [
    'model'      => ANTHROPIC_MODEL,
    'max_tokens' => 2048,
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
    CURLOPT_TIMEOUT        => 90,
    CURLOPT_CONNECTTIMEOUT => 15,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
  ]);

  $raw      = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  if (!$raw) {
    echo json_encode(['error' => 'No se pudo conectar con la API de Claude.']);
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

  if ($stop_reason === 'max_tokens') {
    echo json_encode(['error' => 'Respuesta demasiado larga. Inténtalo de nuevo.']);
    exit;
  }

  // Prefill era "{" — recomponer JSON
  $json_str = '{' . $text;
  $data     = json_decode($json_str, true);

  if (!$data) {
    // Intento fallback: extraer JSON del string
    if (preg_match('/\{[\s\S]*\}/u', $json_str, $m)) {
      $data = json_decode($m[0], true);
    }
  }

  if (!$data) {
    echo json_encode(['error' => 'Claude no devolvió JSON válido. Inténtalo de nuevo.', 'raw' => substr($json_str, 0, 500)]);
    exit;
  }

  // ── Generar el archivo PHP con variables + include de plantilla ───
  // For silo sub-pages (e.g. fontanero/elda/desatascos.php), use the requested filepath
  $filepath_out = ($silo_depth === 2 && $filepath_requested)
    ? $filepath_requested
    : $tipo_cfg['dir'] . '/' . $filename;

  $php_contenido = generar_php_servicio($data, $tipo_cfg, $tipo, $ciudad, $ciudad_slug, $ciudad_cp, $otras_ciudades, $silo_depth);

  echo json_encode([
    'ok'            => true,
    'php_contenido' => $php_contenido,
    'filepath'      => $filepath_out,
    'meta_title'    => $data['meta_title'] ?? '',
    'meta_desc'     => $data['meta_desc']  ?? '',
  ]);
  exit;
}

// ═════════════════════════════════════════════════════════════════════
// Genera el archivo PHP: variables + include plantilla-servicio.php
// ═════════════════════════════════════════════════════════════════════
function generar_php_servicio($data, $tipo_cfg, $tipo, $ciudad, $ciudad_slug, $ciudad_cp, $otras_ciudades, $depth = 1) {
  $servicio_nombre = $tipo_cfg['nombre'];
  $meta_title      = $data['meta_title']      ?? '';
  $meta_desc       = $data['meta_desc']       ?? '';
  $hero_titulo     = $data['hero_titulo']     ?? $servicio_nombre . ' en ' . $ciudad . '<br><span class=\'hl\'>rápido.</span>';
  $hero_sub        = $data['hero_sub']        ?? 'Servicio en ' . $ciudad . '. Presupuesto gratis sin compromiso.';
  $contenido_intro = $data['contenido_intro'] ?? '<p>Servicios de ' . strtolower($servicio_nombre) . ' en ' . $ciudad . '.</p>';
  $servicios_lista = $data['servicios_lista'] ?? [];
  $problemas_zona  = $data['problemas_zona']  ?? [];
  $faq             = $data['faq']             ?? [];

  $meta_url = 'https://caroltemp.com/' . $tipo_cfg['dir'] . '/' . $tipo_cfg['prefijo_url'] . '-' . $ciudad_slug;

  // Usar var_export para blindar los valores contra problemas de comillas
  $e = fn($v) => var_export($v, true);

  $php  = "<?php\n";
  $php .= "/**\n * {$servicio_nombre} en {$ciudad}\n * {$meta_url}\n * Generado por Agente de Páginas\n */\n\n";
  $php .= "\$servicio_nombre = {$e($servicio_nombre)};\n";
  $php .= "\$servicio_slug   = {$e($tipo)};\n";
  $php .= "\$ciudad          = {$e($ciudad)};\n";
  $php .= "\$ciudad_slug     = {$e($ciudad_slug)};\n";
  $php .= "\$ciudad_cp       = {$e($ciudad_cp)};\n\n";
  $php .= "\$meta_title = {$e($meta_title)};\n";
  $php .= "\$meta_desc  = {$e($meta_desc)};\n\n";
  $php .= "\$hero_titulo = {$e($hero_titulo)};\n";
  $php .= "\$hero_sub    = {$e($hero_sub)};\n\n";
  $php .= "\$contenido_intro = {$e($contenido_intro)};\n\n";
  $php .= "\$servicios_lista = {$e($servicios_lista)};\n\n";
  $php .= "\$problemas_zona  = {$e($problemas_zona)};\n\n";
  $php .= "\$faq             = {$e($faq)};\n\n";
  $php .= "\$contenido_extra  = '';\n\n";
  $php .= "\$ciudades_cercanas = {$e($otras_ciudades)};\n\n";
  $back = str_repeat('../', $depth);
  $php .= "include __DIR__ . '/{$back}includes/plantilla-servicio.php';\n";

  return $php;
}

// ═════════════════════════════════════════════════════════════════════
// Genera el archivo PHP hub ciudad (fontanero/{slug}.php)
// Estructura silo: cubre todos los servicios, enlaza a sub-páginas
// ═════════════════════════════════════════════════════════════════════
function generar_php_hub_ciudad($data, $ciudad, $ciudad_slug, $ciudad_cp, $otras_ciudades) {
  $meta_title = $data['meta_title'] ?? "Fontanería en {$ciudad} — CarolTemp";
  $meta_desc  = $data['meta_desc']  ?? "Servicios de fontanería en {$ciudad}. Urgencias, fugas, desatascos e instalaciones.";
  $hero_sub   = $data['hero_sub']   ?? "Trabajamos en {$ciudad} realizando todo tipo de servicios de fontanería.";
  $intro_p1   = $data['intro_p1']   ?? "Trabajamos en toda la localidad de {$ciudad}, cubriendo tanto viviendas como comunidades.";
  $intro_p2   = $data['intro_p2']   ?? "Presupuesto gratuito sin compromiso. Precio cerrado antes de empezar.";
  $checklist  = $data['checklist']  ?? ["Urgencias de fontanería", "Detección de fugas", "Desatascos", "Termos y descalcificadores", "Reformas de baño"];
  $faq        = $data['faq']        ?? [];

  $meta_url = "https://caroltemp.com/fontanero/{$ciudad_slug}";
  $e        = fn($v) => var_export($v, true);

  $svg_chk = '<svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
  $chk_items = '';
  foreach ($checklist as $item) {
    $item_h = htmlspecialchars($item, ENT_QUOTES, 'UTF-8');
    $chk_items .= "          <li><span class=\"chk-ico\">{$svg_chk}</span>{$item_h}</li>\n";
  }

  $svg_faq   = '<svg viewBox="0 0 10 10" fill="none"><path d="M5 1v8M1 5h8" stroke-width="1.5" stroke-linecap="round"/></svg>';
  $faq_items = '';
  $first     = true;
  foreach ($faq as $f) {
    $preg_h = htmlspecialchars($f['pregunta']  ?? '', ENT_QUOTES, 'UTF-8');
    $resp_h = htmlspecialchars($f['respuesta'] ?? '', ENT_QUOTES, 'UTF-8');
    $open   = $first ? ' open' : '';
    $faq_items .= "      <div class=\"zona-fi{$open}\">\n";
    $faq_items .= "        <div class=\"zona-fiq\" onclick=\"togFaq(this)\"><span>{$preg_h}</span><span class=\"zona-fiq-i\">{$svg_faq}</span></div>\n";
    $faq_items .= "        <div class=\"zona-fia\">{$resp_h}</div>\n";
    $faq_items .= "      </div>\n";
    $first = false;
  }

  $ztags = '';
  foreach ($otras_ciudades as $otra) {
    $n_h = htmlspecialchars($otra['nombre'], ENT_QUOTES, 'UTF-8');
    $s_h = htmlspecialchars($otra['slug'],   ENT_QUOTES, 'UTF-8');
    $ztags .= "      <a href=\"<?php echo \$base_url; ?>fontanero/{$s_h}\" class=\"zona-ztag\">{$n_h}</a>\n";
  }

  $hero_sub_h = htmlspecialchars($hero_sub, ENT_QUOTES, 'UTF-8');
  $intro_p1_h = htmlspecialchars($intro_p1, ENT_QUOTES, 'UTF-8');
  $intro_p2_h = htmlspecialchars($intro_p2, ENT_QUOTES, 'UTF-8');

  $php  = "<?php\n";
  $php .= "\$zona_nombre = {$e($ciudad)};\n";
  $php .= "\$zona_slug   = {$e($ciudad_slug)};\n";
  $php .= "\$zona_cp     = {$e($ciudad_cp)};\n\n";
  $php .= "\$meta_title  = {$e($meta_title)};\n";
  $php .= "\$meta_desc   = {$e($meta_desc)};\n";
  $php .= "\$meta_url    = {$e($meta_url)};\n";
  $php .= "\$schema_type = 'zona';\n";
  $php .= "\$page_css    = 'zona';\n";
  $php .= "\$page_js     = 'zona';\n\n";
  $php .= "include '../includes/head.php';\n";
  $php .= "?>\n\n";

  $php .= "<!-- HERO -->\n";
  $php .= "<section class=\"hz-dark\">\n";
  $php .= "  <div class=\"hz-dark-bg\"></div>\n";
  $php .= "  <div class=\"hz-dark-glow\"></div>\n";
  $php .= "  <div class=\"hz-dark-con\">\n";
  $php .= "    <div class=\"hz-dark-tag\"><span class=\"hz-dark-dot\"></span>Fontaner&iacute;a en <?php echo \$zona_nombre; ?> &middot; CP <?php echo \$zona_cp; ?></div>\n";
  $php .= "    <h1>Fontaner&iacute;a en <span class=\"hl\"><?php echo \$zona_nombre; ?>.</span></h1>\n";
  $php .= "    <p class=\"hz-dark-sub\">{$hero_sub_h}</p>\n";
  $php .= "    <div class=\"hz-dark-btns\">\n";
  $php .= "      <a href=\"tel:+34613429032\" class=\"btn-hz-w\">&#128222; 613 429 032</a>\n";
  $php .= "      <a href=\"<?php echo \$base_url; ?>contacto\" class=\"btn-hz-g\">Solicitar visita</a>\n";
  $php .= "    </div>\n";
  $php .= "    <div class=\"hero-dark-kpis\" style=\"margin-top:2rem\">\n";
  $php .= "      <div class=\"hero-dark-kpi\"><span class=\"hero-dark-kpi-val\">Nubeco</span><span class=\"hero-dark-kpi-lbl\">Instalador oficial en <?php echo \$zona_nombre; ?></span></div>\n";
  $php .= "      <div class=\"hero-dark-kpi\"><span class=\"hero-dark-kpi-val\">100%</span><span class=\"hero-dark-kpi-lbl\">Precio cerrado siempre</span></div>\n";
  $php .= "      <div class=\"hero-dark-kpi\"><span class=\"hero-dark-kpi-val\">0&euro;</span><span class=\"hero-dark-kpi-lbl\">Sin adelantos con financiaci&oacute;n</span></div>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n\n";

  $php .= "<!-- STRIP -->\n";
  $php .= "<div class=\"dif-strip\">\n";
  $php .= "  <div class=\"dif-strip-in\">\n";
  $php .= "    <div class=\"dif-item\"><span class=\"dif-val\">&#9889; Urgencias</span><span class=\"dif-lbl\">Atenci&oacute;n r&aacute;pida en <?php echo \$zona_nombre; ?></span></div>\n";
  $php .= "    <div class=\"dif-item\"><span class=\"dif-val\">&#128269; Sin obras</span><span class=\"dif-lbl\">Ge&oacute;fono y c&aacute;mara</span></div>\n";
  $php .= "    <div class=\"dif-item\"><span class=\"dif-val\">&#128176; Precio cerrado</span><span class=\"dif-lbl\">Antes de empezar</span></div>\n";
  $php .= "    <div class=\"dif-item\"><span class=\"dif-val\">&#128205; Comarca</span><span class=\"dif-lbl\">Somos de aqu&iacute;</span></div>\n";
  $php .= "  </div>\n";
  $php .= "</div>\n\n";

  $php .= "<!-- TEXTO CENTRAL -->\n";
  $php .= "<section class=\"zona-sec\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <div class=\"zona-tcol\">\n";
  $php .= "      <div>\n";
  $php .= "        <p class=\"zona-lbl\">Fontan&eacute;r&iacute;a en <?php echo \$zona_nombre; ?></p>\n";
  $php .= "        <h2>Servicios de fontaner&iacute;a en <span class=\"hl\"><?php echo \$zona_nombre; ?></span></h2>\n";
  $php .= "        <div class=\"zona-prose\">\n";
  $php .= "          <p>{$intro_p1_h}</p>\n";
  $php .= "          <p>{$intro_p2_h}</p>\n";
  $php .= "        </div>\n";
  $php .= "        <ul class=\"zona-chk\">\n";
  $php .= $chk_items;
  $php .= "        </ul>\n";
  $php .= "      </div>\n";
  $php .= "      <div>\n";
  $php .= "        <div class=\"zona-icard\">\n";
  $php .= "          <div class=\"zona-icard-h\"><strong>CarolTemp &middot; <?php echo \$zona_nombre; ?></strong><span>Fontaner&iacute;a residencial</span></div>\n";
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">Zona</span><span class=\"zona-ir-v\"><?php echo \$zona_nombre; ?> &middot; CP <?php echo \$zona_cp; ?></span></div>\n";
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">Tel&eacute;fono</span><span class=\"zona-ir-v\"><a href=\"tel:+34613429032\">613 429 032</a></span></div>\n";
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">WhatsApp</span><span class=\"zona-ir-v\"><a href=\"https://wa.me/34613429032\">Escribir ahora &rarr;</a></span></div>\n";
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">Horario</span><span class=\"zona-ir-v\">Lun&ndash;Vie 8&ndash;20h &middot; S&aacute;b 9&ndash;14h</span></div>\n";
  $php .= "          <a href=\"tel:+34613429032\" class=\"zona-icard-btn\">&#128222; Llamar ahora</a>\n";
  $php .= "        </div>\n";
  $php .= "      </div>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n\n";

  $php .= "<!-- SERVICIOS EN EL SILO -->\n";
  $php .= "<section class=\"zona-sec zona-sec-gray\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <p class=\"zona-lbl\">Servicios en <?php echo \$zona_nombre; ?></p>\n";
  $php .= "    <h2>Todo lo que hacemos <span class=\"hl\">en <?php echo \$zona_nombre; ?></span></h2>\n";
  $php .= "    <div class=\"zona-svc\">\n";
  $php .= "      <a href=\"<?php echo \$base_url; ?>fontanero/<?php echo \$zona_slug; ?>/urgencias\" class=\"zona-sc\"><span class=\"zona-sc-n\">01</span><h3>Fontanero urgente en <?php echo \$zona_nombre; ?></h3><p>Roturas de tuber&iacute;as, grifos, cisternas y p&eacute;rdidas de agua con soluci&oacute;n r&aacute;pida y precio cerrado.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "      <a href=\"<?php echo \$base_url; ?>fontanero/<?php echo \$zona_slug; ?>/busqueda_fugas\" class=\"zona-sc\"><span class=\"zona-sc-n\">02</span><h3>B&uacute;squeda de fugas en <?php echo \$zona_nombre; ?></h3><p>Localizaci&oacute;n de fugas con ge&oacute;fono y c&aacute;mara sin romper innecesariamente.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "      <a href=\"<?php echo \$base_url; ?>fontanero/<?php echo \$zona_slug; ?>/desatascos\" class=\"zona-sc\"><span class=\"zona-sc-n\">03</span><h3>Desatascos en <?php echo \$zona_nombre; ?></h3><p>Desatascos de fregaderos, bajantes y arquetas para recuperar el funcionamiento normal.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "      <a href=\"<?php echo \$base_url; ?>servicios#termos\" class=\"zona-sc\"><span class=\"zona-sc-n\">04</span><h3>Termos el&eacute;ctricos en <?php echo \$zona_nombre; ?></h3><p>Instalaci&oacute;n de termos el&eacute;ctricos con asesoramiento y puesta en marcha.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "      <a href=\"<?php echo \$base_url; ?>servicios#descalcificadores\" class=\"zona-sc\"><span class=\"zona-sc-n\">05</span><h3>Descalcificadores en <?php echo \$zona_nombre; ?></h3><p>Soluci&oacute;n para el agua dura con instalaci&oacute;n y mantenimiento de descalcificadores.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "      <a href=\"<?php echo \$base_url; ?>servicios#reformas\" class=\"zona-sc\"><span class=\"zona-sc-n\">06</span><h3>Reformas de ba&ntilde;o en <?php echo \$zona_nombre; ?></h3><p>Reformas completas o parciales con precio cerrado antes de empezar.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n\n";

  $php .= "<!-- FAQ -->\n";
  $php .= "<section class=\"zona-sec\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <p class=\"zona-lbl\">Preguntas frecuentes</p>\n";
  $php .= "    <h2>Fontaner&iacute;a en <?php echo \$zona_nombre; ?> &mdash; <span class=\"hl\">dudas habituales</span></h2>\n";
  $php .= "    <div class=\"zona-faq\" style=\"margin-top:2rem\">\n";
  $php .= $faq_items;
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n\n";

  $php .= "<!-- CIUDADES CERCANAS -->\n";
  $php .= "<section class=\"zona-sec zona-sec-gray\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <p class=\"zona-lbl\">Otras zonas donde trabajamos</p>\n";
  $php .= "    <h2>Tambi&eacute;n trabajamos en <span class=\"hl\">zonas cercanas</span></h2>\n";
  $php .= "    <div class=\"zona-ztags\">\n";
  $php .= $ztags;
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n\n";

  $php .= "<!-- CTA FINAL -->\n";
  $php .= "<section class=\"cta-dark\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <h2>&iquest;Necesitas fontaner&iacute;a <span>en <?php echo \$zona_nombre; ?>?</span></h2>\n";
  $php .= "    <p>Ll&aacute;menos o escr&iacute;benos y te atendemos hoy.</p>\n";
  $php .= "    <div class=\"cta-dark-btns\">\n";
  $php .= "      <a href=\"tel:+34613429032\" class=\"btn-hz-w\">&#128222; Llamar ahora</a>\n";
  $php .= "      <a href=\"https://wa.me/34613429032\" target=\"_blank\" rel=\"noopener\" class=\"btn-hz-g\">&#128172; WhatsApp</a>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n\n";

  $php .= "<?php include '../includes/footer.php'; ?>\n";

  return $php;
}

// ═════════════════════════════════════════════════════════════════════
// Genera el archivo PHP de zona (standalone, como zonas/petrer.php)
// ═════════════════════════════════════════════════════════════════════
function generar_php_zona($data, $ciudad, $ciudad_slug, $ciudad_cp, $otras_ciudades) {
  $meta_title = $data['meta_title'] ?? "Fontanería en {$ciudad} — CarolTemp";
  $meta_desc  = $data['meta_desc']  ?? "Servicios de fontanería en {$ciudad}. Reparaciones, fugas, desatascos e instalaciones.";
  $hero_sub   = $data['hero_sub']   ?? "Trabajamos en {$ciudad} realizando todo tipo de servicios de fontanería.";
  $intro_p1   = $data['intro_p1']   ?? "Trabajamos en toda la localidad de {$ciudad}, cubriendo tanto viviendas como comunidades.";
  $intro_p2   = $data['intro_p2']   ?? "En esta página puedes ver todos los servicios disponibles.";
  $checklist  = $data['checklist']  ?? ["Servicios para todo tipo de viviendas", "Reparaciones, instalaciones y mantenimiento", "Atención rápida", "Precio claro antes de empezar", "Presupuesto gratuito sin compromiso"];
  $faq        = $data['faq']        ?? [];

  $meta_url = "https://caroltemp.com/zonas/{$ciudad_slug}";
  $e        = fn($v) => var_export($v, true);

  // Build checklist items HTML
  $svg_chk = '<svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
  $chk_items = '';
  foreach ($checklist as $item) {
    $item_h = htmlspecialchars($item, ENT_QUOTES, 'UTF-8');
    $chk_items .= "          <li><span class=\"chk-ico\">{$svg_chk}</span>{$item_h}</li>\n";
  }

  // Build FAQ HTML
  $svg_faq   = '<svg viewBox="0 0 10 10" fill="none"><path d="M5 1v8M1 5h8" stroke-width="1.5" stroke-linecap="round"/></svg>';
  $faq_items = '';
  $first     = true;
  foreach ($faq as $f) {
    $preg_h = htmlspecialchars($f['pregunta']  ?? '', ENT_QUOTES, 'UTF-8');
    $resp_h = htmlspecialchars($f['respuesta'] ?? '', ENT_QUOTES, 'UTF-8');
    $open   = $first ? ' open' : '';
    $faq_items .= "      <div class=\"zona-fi{$open}\">\n";
    $faq_items .= "        <div class=\"zona-fiq\" onclick=\"togFaq(this)\"><span>{$preg_h}</span><span class=\"zona-fiq-i\">{$svg_faq}</span></div>\n";
    $faq_items .= "        <div class=\"zona-fia\">{$resp_h}</div>\n";
    $faq_items .= "      </div>\n";
    $first = false;
  }

  // Build nearby zones tags
  $ztags = '';
  foreach ($otras_ciudades as $otra) {
    $n_h = htmlspecialchars($otra['nombre'], ENT_QUOTES, 'UTF-8');
    $s_h = htmlspecialchars($otra['slug'],   ENT_QUOTES, 'UTF-8');
    $ztags .= "      <a href=\"<?php echo \$base_url; ?>zonas/{$s_h}\" class=\"zona-ztag\">{$n_h}</a>\n";
  }

  $hero_sub_h = htmlspecialchars($hero_sub, ENT_QUOTES, 'UTF-8');
  $intro_p1_h = htmlspecialchars($intro_p1, ENT_QUOTES, 'UTF-8');
  $intro_p2_h = htmlspecialchars($intro_p2, ENT_QUOTES, 'UTF-8');

  $php  = "<?php\n";
  $php .= "\$zona_nombre = {$e($ciudad)};\n";
  $php .= "\$zona_slug   = {$e($ciudad_slug)};\n";
  $php .= "\$zona_cp     = {$e($ciudad_cp)};\n\n";
  $php .= "\$meta_title  = {$e($meta_title)};\n";
  $php .= "\$meta_desc   = {$e($meta_desc)};\n";
  $php .= "\$meta_url    = {$e($meta_url)};\n";
  $php .= "\$schema_type = 'zona';\n";
  $php .= "\$page_css    = 'zona';\n";
  $php .= "\$page_js     = 'zona';\n\n";
  $php .= "include '../includes/head.php';\n";
  $php .= "?>\n\n";

  $php .= "<!-- HERO -->\n";
  $php .= "<section class=\"hz-dark\">\n";
  $php .= "  <div class=\"hz-dark-bg\"></div>\n";
  $php .= "  <div class=\"hz-dark-glow\"></div>\n";
  $php .= "  <div class=\"hz-dark-con\">\n";
  $php .= "    <div class=\"hz-dark-tag\"><span class=\"hz-dark-dot\"></span>Servicio en <?php echo \$zona_nombre; ?> &middot; CP <?php echo \$zona_cp; ?></div>\n";
  $php .= "    <h1>Fontanería en <span class=\"hl\"><?php echo \$zona_nombre; ?>.</span></h1>\n";
  $php .= "    <p class=\"hz-dark-sub\">{$hero_sub_h}</p>\n";
  $php .= "    <div class=\"hz-dark-btns\">\n";
  $php .= "      <a href=\"tel:+34613429032\" class=\"btn-hz-w\">&#128222; 613 429 032</a>\n";
  $php .= "      <a href=\"<?php echo \$base_url; ?>contacto\" class=\"btn-hz-g\">Solicitar visita</a>\n";
  $php .= "    </div>\n";
  $php .= "    <div class=\"hero-dark-kpis\" style=\"margin-top:2rem\">\n";
  $php .= "      <div class=\"hero-dark-kpi\"><span class=\"hero-dark-kpi-val\">Nubeco</span><span class=\"hero-dark-kpi-lbl\">Instalador oficial en <?php echo \$zona_nombre; ?></span></div>\n";
  $php .= "      <div class=\"hero-dark-kpi\"><span class=\"hero-dark-kpi-val\">100%</span><span class=\"hero-dark-kpi-lbl\">Precio cerrado siempre</span></div>\n";
  $php .= "      <div class=\"hero-dark-kpi\"><span class=\"hero-dark-kpi-val\">0&euro;</span><span class=\"hero-dark-kpi-lbl\">Sin adelantos con financiaci&oacute;n</span></div>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n\n";

  $php .= "<!-- STRIP -->\n";
  $php .= "<div class=\"dif-strip\">\n";
  $php .= "  <div class=\"dif-strip-in\">\n";
  $php .= "    <div class=\"dif-item\"><span class=\"dif-val\">&#9889; Urgencias</span><span class=\"dif-lbl\">Atenci&oacute;n r&aacute;pida en <?php echo \$zona_nombre; ?></span></div>\n";
  $php .= "    <div class=\"dif-item\"><span class=\"dif-val\">&#128269; Sin obras</span><span class=\"dif-lbl\">Ge&oacute;fono y c&aacute;mara</span></div>\n";
  $php .= "    <div class=\"dif-item\"><span class=\"dif-val\">&#128176; Precio cerrado</span><span class=\"dif-lbl\">Antes de empezar</span></div>\n";
  $php .= "    <div class=\"dif-item\"><span class=\"dif-val\">&#128205; Comarca</span><span class=\"dif-lbl\">Somos de aqu&iacute;</span></div>\n";
  $php .= "  </div>\n";
  $php .= "</div>\n\n";

  $php .= "<!-- TEXTO CENTRAL -->\n";
  $php .= "<section class=\"zona-sec\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <div class=\"zona-tcol\">\n";
  $php .= "      <div>\n";
  $php .= "        <p class=\"zona-lbl\">Fontan&eacute;r&iacute;a en <?php echo \$zona_nombre; ?></p>\n";
  $php .= "        <h2>Servicios de fontaner&iacute;a en <span class=\"hl\"><?php echo \$zona_nombre; ?></span></h2>\n";
  $php .= "        <div class=\"zona-prose\">\n";
  $php .= "          <p>{$intro_p1_h}</p>\n";
  $php .= "          <p>{$intro_p2_h}</p>\n";
  $php .= "        </div>\n";
  $php .= "        <ul class=\"zona-chk\">\n";
  $php .= $chk_items;
  $php .= "        </ul>\n";
  $php .= "      </div>\n";
  $php .= "      <div>\n";
  $php .= "        <div class=\"zona-icard\">\n";
  $php .= "          <div class=\"zona-icard-h\"><strong>CarolTemp &middot; <?php echo \$zona_nombre; ?></strong><span>Fontaner&iacute;a residencial</span></div>\n";
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">Zona</span><span class=\"zona-ir-v\"><?php echo \$zona_nombre; ?> &middot; CP <?php echo \$zona_cp; ?></span></div>\n";
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">Tel&eacute;fono</span><span class=\"zona-ir-v\"><a href=\"tel:+34613429032\">613 429 032</a></span></div>\n";
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">WhatsApp</span><span class=\"zona-ir-v\"><a href=\"https://wa.me/34613429032\">Escribir ahora &rarr;</a></span></div>\n";
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">Horario</span><span class=\"zona-ir-v\">Lun&ndash;Vie 8&ndash;20h &middot; S&aacute;b 9&ndash;14h</span></div>\n";
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">Financiaci&oacute;n</span><span class=\"zona-ir-v\">Disponible para proyectos grandes</span></div>\n";
  $php .= "          <a href=\"tel:+34613429032\" class=\"zona-icard-btn\">&#128222; Llamar ahora</a>\n";
  $php .= "        </div>\n";
  $php .= "      </div>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n\n";

  $php .= "<!-- SERVICIOS DISPONIBLES -->\n";
  $php .= "<section class=\"zona-sec zona-sec-gray\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <p class=\"zona-lbl\">Servicios en <?php echo \$zona_nombre; ?></p>\n";
  $php .= "    <h2>Servicios disponibles <span class=\"hl\">en <?php echo \$zona_nombre; ?></span></h2>\n";
  $php .= "    <p class=\"zona-sub\">Selecciona el servicio que necesitas para ver el detalle completo.</p>\n";
  $php .= "    <div class=\"zona-svc\">\n";
  $php .= "      <a href=\"<?php echo \$base_url; ?>fontanero/fontanero-<?php echo \$zona_slug; ?>\" class=\"zona-sc\"><span class=\"zona-sc-n\">01</span><h3>Reparaciones urgentes en <?php echo \$zona_nombre; ?></h3><p>Fugas de agua, grifos, cisternas y tuber&iacute;as con soluci&oacute;n r&aacute;pida y precio cerrado.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "      <a href=\"<?php echo \$base_url; ?>fugas/deteccion-fugas-<?php echo \$zona_slug; ?>\" class=\"zona-sc\"><span class=\"zona-sc-n\">02</span><h3>Detecci&oacute;n de fugas en <?php echo \$zona_nombre; ?></h3><p>Localizaci&oacute;n de fugas con ge&oacute;fono y c&aacute;mara sin romper innecesariamente.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "      <a href=\"<?php echo \$base_url; ?>desatascos/desatascos-<?php echo \$zona_slug; ?>\" class=\"zona-sc\"><span class=\"zona-sc-n\">03</span><h3>Desatascos en <?php echo \$zona_nombre; ?></h3><p>Desatascos de fregaderos, bajantes y arquetas para recuperar el funcionamiento normal.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "      <a href=\"<?php echo \$base_url; ?>servicios#termos\" class=\"zona-sc\"><span class=\"zona-sc-n\">04</span><h3>Termos el&eacute;ctricos en <?php echo \$zona_nombre; ?></h3><p>Instalaci&oacute;n de termos el&eacute;ctricos con asesoramiento y puesta en marcha.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "      <a href=\"<?php echo \$base_url; ?>servicios#descalcificadores\" class=\"zona-sc\"><span class=\"zona-sc-n\">05</span><h3>Descalcificadores en <?php echo \$zona_nombre; ?></h3><p>Soluci&oacute;n para el agua dura con instalaci&oacute;n y mantenimiento de descalcificadores.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "      <a href=\"<?php echo \$base_url; ?>servicios#reformas\" class=\"zona-sc\"><span class=\"zona-sc-n\">06</span><h3>Reformas de ba&ntilde;o en <?php echo \$zona_nombre; ?></h3><p>Reformas completas o parciales con precio cerrado antes de empezar.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n\n";

  $php .= "<!-- FUGAS CON GEÓFONO Y CÁMARA -->\n";
  $php .= "<section class=\"zona-sec\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <div class=\"zona-fugas\">\n";
  $php .= "      <div class=\"fg-top\">\n";
  $php .= "        <div>\n";
  $php .= "          <p class=\"fg-lbl\">Detecci&oacute;n de fugas en <?php echo \$zona_nombre; ?></p>\n";
  $php .= "          <h2>B&uacute;squeda de fugas con<br><span>c&aacute;mara y ge&oacute;fono en <?php echo \$zona_nombre; ?></span></h2>\n";
  $php .= "          <p>Localizamos el origen exacto de cualquier fuga en <?php echo \$zona_nombre; ?> sin necesidad de romper paredes ni levantar suelos.</p>\n";
  $php .= "        </div>\n";
  $php .= "        <div>\n";
  $php .= "          <ul class=\"fg-chk\">\n";
  $php .= "            <li>Detecci&oacute;n de fugas de agua en viviendas en <?php echo \$zona_nombre; ?></li>\n";
  $php .= "            <li>Fugas de agua urgentes en <?php echo \$zona_nombre; ?></li>\n";
  $php .= "            <li>Fugas en tuber&iacute;as y bajantes</li>\n";
  $php .= "            <li>Fugas en comunidades de vecinos</li>\n";
  $php .= "            <li>Detecci&oacute;n de fugas en piscinas</li>\n";
  $php .= "            <li>Localizaci&oacute;n precisa sin obras innecesarias</li>\n";
  $php .= "          </ul>\n";
  $php .= "          <a href=\"<?php echo \$base_url; ?>fugas/deteccion-fugas-<?php echo \$zona_slug; ?>\" class=\"fg-btn\">Ver detalle completo &rarr;</a>\n";
  $php .= "        </div>\n";
  $php .= "      </div>\n";
  $php .= "      <div class=\"fg-cards\">\n";
  $php .= "        <div class=\"fg-card\">\n";
  $php .= "          <div class=\"fg-card-img\"><div class=\"fg-card-img-ph\"><svg width=\"28\" height=\"28\" viewBox=\"0 0 24 24\" fill=\"none\"><rect x=\"2\" y=\"4\" width=\"20\" height=\"16\" rx=\"2\" stroke=\"rgba(255,255,255,.5)\" stroke-width=\"1.5\"/><circle cx=\"8\" cy=\"10\" r=\"2\" stroke=\"rgba(255,255,255,.5)\" stroke-width=\"1.5\"/><path d=\"M2 17l5-5 4 4 3-3 5 5\" stroke=\"rgba(255,255,255,.5)\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\"/></svg><span>Foto del ge&oacute;fono</span></div></div>\n";
  $php .= "          <span class=\"fg-badge\">Ge&oacute;fono</span>\n";
  $php .= "          <h4>Detecci&oacute;n ac&uacute;stica de fugas</h4>\n";
  $php .= "          <p>El ge&oacute;fono detecta el sonido del agua al escapar por fisuras en tuber&iacute;as. Permite localizar la fuga exacta sin necesidad de abrir.</p>\n";
  $php .= "        </div>\n";
  $php .= "        <div class=\"fg-card\">\n";
  $php .= "          <div class=\"fg-card-img\"><div class=\"fg-card-img-ph\"><svg width=\"28\" height=\"28\" viewBox=\"0 0 24 24\" fill=\"none\"><rect x=\"2\" y=\"4\" width=\"20\" height=\"16\" rx=\"2\" stroke=\"rgba(255,255,255,.5)\" stroke-width=\"1.5\"/><circle cx=\"8\" cy=\"10\" r=\"2\" stroke=\"rgba(255,255,255,.5)\" stroke-width=\"1.5\"/><path d=\"M2 17l5-5 4 4 3-3 5 5\" stroke=\"rgba(255,255,255,.5)\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\"/></svg><span>Foto de la c&aacute;mara</span></div></div>\n";
  $php .= "          <span class=\"fg-badge\">C&aacute;mara</span>\n";
  $php .= "          <h4>Inspecci&oacute;n visual de tuber&iacute;as</h4>\n";
  $php .= "          <p>La c&aacute;mara inspecciona el interior de tuber&iacute;as y bajantes. Permite detectar roturas, obstrucciones o fisuras antes de intervenir.</p>\n";
  $php .= "        </div>\n";
  $php .= "      </div>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n\n";

  $php .= "<!-- IMÁGENES -->\n";
  $php .= "<section class=\"zona-sec zona-sec-gray\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <p class=\"zona-lbl\">Trabajos en <?php echo \$zona_nombre; ?></p>\n";
  $php .= "    <h2>Fontaner&iacute;a en <?php echo \$zona_nombre; ?> &mdash; <span class=\"hl\">proyectos realizados</span></h2>\n";
  $php .= "    <p class=\"zona-sub\">Trabajos reales de fontaner&iacute;a en <?php echo \$zona_nombre; ?>.</p>\n";
  $php .= "    <div class=\"zona-ig\">\n";
  $php .= "      <?php\n";
  $php .= "      \$imgs = [\n";
  $php .= "        ['src' => '', 'alt' => 'Fontanero en ' . \$zona_nombre . ' — trabajo 1'],\n";
  $php .= "        ['src' => '', 'alt' => 'Fontanería en ' . \$zona_nombre . ' — trabajo 2'],\n";
  $php .= "        ['src' => '', 'alt' => 'Reparación en ' . \$zona_nombre . ' — trabajo 3'],\n";
  $php .= "        ['src' => '', 'alt' => 'Instalación en ' . \$zona_nombre . ' — trabajo 4'],\n";
  $php .= "        ['src' => '', 'alt' => 'Reforma de baño en ' . \$zona_nombre . ' — trabajo 5'],\n";
  $php .= "        ['src' => '', 'alt' => 'Detección de fugas en ' . \$zona_nombre . ' — trabajo 6'],\n";
  $php .= "      ];\n";
  $php .= "      foreach (\$imgs as \$img):\n";
  $php .= "      ?>\n";
  $php .= "        <div class=\"zona-ip\">\n";
  $php .= "          <?php if (\$img['src']): ?>\n";
  $php .= "            <img src=\"<?php echo \$base_url . \$img['src']; ?>\" alt=\"<?php echo htmlspecialchars(\$img['alt']); ?>\" loading=\"lazy\">\n";
  $php .= "          <?php else: ?>\n";
  $php .= "            <svg width=\"28\" height=\"28\" viewBox=\"0 0 24 24\" fill=\"none\"><rect x=\"2\" y=\"4\" width=\"20\" height=\"16\" rx=\"2\" stroke=\"#94a3b8\" stroke-width=\"1.5\"/><circle cx=\"8\" cy=\"10\" r=\"2\" stroke=\"#94a3b8\" stroke-width=\"1.5\"/><path d=\"M2 17l5-5 4 4 3-3 5 5\" stroke=\"#94a3b8\" stroke-width=\"1.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\"/></svg>\n";
  $php .= "            <span><?php echo htmlspecialchars(\$img['alt']); ?></span>\n";
  $php .= "          <?php endif; ?>\n";
  $php .= "        </div>\n";
  $php .= "      <?php endforeach; ?>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n\n";

  $php .= "<!-- FAQ -->\n";
  $php .= "<section class=\"zona-sec\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <p class=\"zona-lbl\">Preguntas frecuentes</p>\n";
  $php .= "    <h2>Fontaner&iacute;a en <?php echo \$zona_nombre; ?> &mdash; <span class=\"hl\">dudas habituales</span></h2>\n";
  $php .= "    <div class=\"zona-faq\" style=\"margin-top:2rem\">\n";
  $php .= $faq_items;
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n\n";

  $php .= "<!-- ZONAS CERCANAS -->\n";
  $php .= "<section class=\"zona-sec zona-sec-gray\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <p class=\"zona-lbl\">Zonas donde trabajamos</p>\n";
  $php .= "    <h2>Tambi&eacute;n trabajamos en <span class=\"hl\">zonas cercanas</span></h2>\n";
  $php .= "    <div class=\"zona-ztags\">\n";
  $php .= $ztags;
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n\n";

  $php .= "<!-- CTA FINAL -->\n";
  $php .= "<section class=\"cta-dark\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <h2>&iquest;Necesitas fontaner&iacute;a <span>en <?php echo \$zona_nombre; ?>?</span></h2>\n";
  $php .= "    <p>Ll&aacute;menos o escr&iacute;benos y te atendemos hoy.</p>\n";
  $php .= "    <div class=\"cta-dark-btns\">\n";
  $php .= "      <a href=\"tel:+34613429032\" class=\"btn-hz-w\">&#128222; Llamar ahora</a>\n";
  $php .= "      <a href=\"https://wa.me/34613429032\" target=\"_blank\" rel=\"noopener\" class=\"btn-hz-g\">&#128172; WhatsApp</a>\n";
  $php .= "    </div>\n";
  $php .= "    <div class=\"cta-dark-tel\">Tel&eacute;fono directo<strong>613 429 032</strong></div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n\n";

  $php .= "<?php include '../includes/footer.php'; ?>\n";

  return $php;
}

// ─────────────────────────────────────────────────────────────────────
// ACCIÓN: guardar
// ─────────────────────────────────────────────────────────────────────
if ($accion === 'guardar') {
  $filepath_rel   = trim($_POST['filepath']        ?? '');
  $contenido      = $_POST['contenido']             ?? '';
  $plan_accion_id = intval($_POST['plan_accion_id'] ?? 0);

  if (!$filepath_rel || !$contenido) {
    echo json_encode(['error' => 'Faltan parámetros: filepath y contenido']);
    exit;
  }

  // ── Validación de seguridad ───────────────────────────────────────
  // Asegurar que la ruta es relativa y dentro de un directorio permitido
  $filepath_rel = ltrim($filepath_rel, '/');

  // Extraer el directorio raíz de la ruta
  $parts         = explode('/', $filepath_rel);
  $top_dir       = $parts[0] ?? '';
  $is_root_level = (count($parts) === 1); // e.g. index.php, contacto.php

  if (!$is_root_level && !in_array($top_dir, $allowed_dirs, true)) {
    echo json_encode(['error' => 'Directorio no permitido: ' . $top_dir]);
    exit;
  }

  // Solo .php y sin traversal
  if (!preg_match('/^[a-z0-9\-_\/]+\.php$/', $filepath_rel)) {
    echo json_encode(['error' => 'Nombre de archivo no válido']);
    exit;
  }

  $abs_path = $site_root . '/' . $filepath_rel;

  // Verificar que la ruta resultante está dentro del site_root.
  // Usamos realpath solo en el site_root (siempre existe). Para el
  // directorio del archivo puede no existir aún (subdirs nuevos del silo).
  $real_site_root = realpath($site_root);
  // Reconstruir la ruta normalizada sin depender de realpath en dirs nuevos
  $norm_path = str_replace(['\\', '/./'], ['/', '/'], $abs_path);
  if (strpos($norm_path, $real_site_root) !== 0) {
    echo json_encode(['error' => 'Ruta fuera del directorio permitido']);
    exit;
  }

  // ── Crear directorio si no existe (para rutas con subdirectorios) ─
  $dir_path = dirname($abs_path);
  if (!is_dir($dir_path)) {
    if (!mkdir($dir_path, 0755, true)) {
      echo json_encode(['error' => 'No se pudo crear el directorio: ' . dirname($filepath_rel)]);
      exit;
    }
  }

  // ── Backup si existe ──────────────────────────────────────────────
  if (file_exists($abs_path)) {
    copy($abs_path, $abs_path . '.bak');
  }

  // ── Escribir el archivo ───────────────────────────────────────────
  $bytes = file_put_contents($abs_path, $contenido);
  if ($bytes === false) {
    echo json_encode(['error' => 'No se pudo escribir el archivo. Verifica permisos en ' . $filepath_rel]);
    exit;
  }

  // ── Si viene plan_accion_id, marcar como completado ──────────────
  if ($plan_accion_id > 0 && file_exists($plan_file)) {
    $plan_raw = file_get_contents($plan_file);
    $plan     = json_decode($plan_raw, true);
    if ($plan && isset($plan['plan']) && is_array($plan['plan'])) {
      foreach ($plan['plan'] as &$item) {
        if (isset($item['id']) && intval($item['id']) === $plan_accion_id) {
          $item['estado'] = 'completado';
          break;
        }
      }
      unset($item);
      file_put_contents($plan_file, json_encode($plan, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
  }

  echo json_encode(['ok' => true, 'bytes' => $bytes, 'filepath' => $filepath_rel]);
  exit;
}

// ─────────────────────────────────────────────────────────────────────
// ACCIÓN: cargar_plan
// ─────────────────────────────────────────────────────────────────────
if ($accion === 'cargar_plan') {
  if (!file_exists($plan_file)) {
    echo json_encode(['ok' => false, 'error' => 'No hay plan del auditor. Genera uno primero.']);
    exit;
  }

  $plan_raw = file_get_contents($plan_file);
  $plan     = json_decode($plan_raw, true);

  if (!$plan) {
    echo json_encode(['ok' => false, 'error' => 'El archivo del plan está corrupto o no es JSON válido.']);
    exit;
  }

  echo json_encode(['ok' => true, 'plan' => $plan]);
  exit;
}

// ─────────────────────────────────────────────────────────────────────
// ACCIÓN: actualizar_estado_accion
// ─────────────────────────────────────────────────────────────────────
if ($accion === 'actualizar_estado_accion') {
  $plan_id = intval($_POST['plan_id'] ?? 0);
  $estado  = trim($_POST['estado']   ?? '');

  if ($plan_id <= 0) {
    echo json_encode(['error' => 'plan_id inválido']);
    exit;
  }

  $estados_validos = ['completado', 'ignorado', 'pendiente'];
  if (!in_array($estado, $estados_validos, true)) {
    echo json_encode(['error' => 'Estado no válido. Use: completado, ignorado o pendiente']);
    exit;
  }

  if (!file_exists($plan_file)) {
    echo json_encode(['error' => 'No hay plan del auditor.']);
    exit;
  }

  $plan_raw = file_get_contents($plan_file);
  $plan     = json_decode($plan_raw, true);

  if (!$plan || !isset($plan['plan']) || !is_array($plan['plan'])) {
    echo json_encode(['error' => 'El archivo del plan está corrupto.']);
    exit;
  }

  $encontrado = false;
  foreach ($plan['plan'] as &$item) {
    if (isset($item['id']) && intval($item['id']) === $plan_id) {
      $item['estado'] = $estado;
      $encontrado = true;
      break;
    }
  }
  unset($item);

  if (!$encontrado) {
    echo json_encode(['error' => 'No se encontró la acción con id ' . $plan_id]);
    exit;
  }

  $ok = file_put_contents($plan_file, json_encode($plan, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
  if ($ok === false) {
    echo json_encode(['error' => 'No se pudo guardar el plan. Verifica permisos.']);
    exit;
  }

  echo json_encode(['ok' => true]);
  exit;
}

// ─────────────────────────────────────────────────────────────────────
// ACCIÓN: eliminar_pagina
// ─────────────────────────────────────────────────────────────────────
if ($accion === 'eliminar_pagina') {
  $filepath_rel = trim($_POST['filepath'] ?? '');

  if (!$filepath_rel) {
    echo json_encode(['error' => 'Falta el parámetro filepath']);
    exit;
  }

  $filepath_rel = ltrim($filepath_rel, '/');

  // Validar directorio permitido
  $parts   = explode('/', $filepath_rel);
  $top_dir = $parts[0] ?? '';

  if (!in_array($top_dir, $allowed_dirs, true)) {
    echo json_encode(['error' => 'Directorio no permitido: ' . $top_dir]);
    exit;
  }

  // Solo .php y sin traversal
  if (!preg_match('/^[a-z0-9\-_\/]+\.php$/', $filepath_rel)) {
    echo json_encode(['error' => 'Nombre de archivo no válido']);
    exit;
  }

  $abs_path = $site_root . '/' . $filepath_rel;

  // Verificar que la ruta resultante está dentro del site_root
  $real_site_root = realpath($site_root);
  $real_dir       = realpath(dirname($abs_path));
  if ($real_dir === false || strpos($real_dir, $real_site_root) !== 0) {
    echo json_encode(['error' => 'Ruta fuera del directorio permitido']);
    exit;
  }

  if (!file_exists($abs_path)) {
    echo json_encode(['error' => 'El archivo no existe: ' . $filepath_rel]);
    exit;
  }

  // ── Backup antes de eliminar ──────────────────────────────────────
  $bak_path = $abs_path . '.bak';
  if (!copy($abs_path, $bak_path)) {
    echo json_encode(['error' => 'No se pudo crear la copia de seguridad. Abortando.']);
    exit;
  }

  // ── Eliminar el archivo ───────────────────────────────────────────
  if (!unlink($abs_path)) {
    echo json_encode(['error' => 'No se pudo eliminar el archivo. Verifica permisos.']);
    exit;
  }

  echo json_encode(['ok' => true, 'backup' => $filepath_rel . '.bak']);
  exit;
}

// ─────────────────────────────────────────────────────────────────────
// ACCIÓN: redireccion_htaccess
// ─────────────────────────────────────────────────────────────────────
if ($accion === 'redireccion_htaccess') {
  $desde = trim($_POST['desde'] ?? '');
  $hacia = trim($_POST['hacia'] ?? '');

  if (!$desde || !$hacia) {
    echo json_encode(['error' => 'Faltan parámetros: desde y hacia']);
    exit;
  }

  // Asegurar que empiezan con /
  if ($desde[0] !== '/') $desde = '/' . $desde;
  if ($hacia[0] !== '/') $hacia = '/' . $hacia;

  $regla = 'Redirect 301 ' . $desde . ' ' . $hacia;

  echo json_encode([
    'ok'         => true,
    'regla'      => $regla,
    'instruccion' => 'Añade esta línea al archivo .htaccess en la raíz del sitio, antes de las reglas de rewrite existentes.',
  ]);
  exit;
}

// ─────────────────────────────────────────────────────────────────────
// ACCIÓN: add_corporativa / remove_corporativa
// ─────────────────────────────────────────────────────────────────────
$extra_file = __DIR__ . '/corporativas-extra.json';

if ($accion === 'add_corporativa') {
  $label    = trim($_POST['label']    ?? '');
  $filepath = trim($_POST['filepath'] ?? '');
  $ruta_web = trim($_POST['ruta_web'] ?? '');

  if (!$label || !$filepath) {
    echo json_encode(['error' => 'Faltan label y/o filepath']); exit;
  }
  if (!preg_match('/^[a-z0-9\-_\/]+\.php$/', $filepath)) {
    echo json_encode(['error' => 'filepath no válido']); exit;
  }

  $extras = file_exists($extra_file) ? json_decode(file_get_contents($extra_file), true) : [];
  if (!is_array($extras)) $extras = [];

  // Evitar duplicados
  foreach ($extras as $e) {
    if ($e['filepath'] === $filepath) {
      echo json_encode(['ok' => true, 'msg' => 'Ya existe']); exit;
    }
  }

  if (!$ruta_web) $ruta_web = '/' . ltrim(str_replace('.php', '', $filepath), '/');
  $extras[] = ['label' => $label, 'filepath' => $filepath, 'ruta_web' => $ruta_web];
  file_put_contents($extra_file, json_encode($extras, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
  echo json_encode(['ok' => true]);
  exit;
}

if ($accion === 'remove_corporativa') {
  $filepath = trim($_POST['filepath'] ?? '');
  if (!$filepath) { echo json_encode(['error' => 'Falta filepath']); exit; }

  $extras = file_exists($extra_file) ? json_decode(file_get_contents($extra_file), true) : [];
  if (!is_array($extras)) $extras = [];

  $extras = array_values(array_filter($extras, fn($e) => $e['filepath'] !== $filepath));
  file_put_contents($extra_file, json_encode($extras, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
  echo json_encode(['ok' => true]);
  exit;
}

// ─────────────────────────────────────────────────────────────────────
// Acción no reconocida
// ─────────────────────────────────────────────────────────────────────
echo json_encode(['error' => 'Acción no reconocida: ' . htmlspecialchars($accion)]);
exit;
