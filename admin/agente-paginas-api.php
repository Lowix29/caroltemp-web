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
  'Elda'             => ['slug' => 'elda',     'cp' => '03600', 'lat' => '38.4766', 'lng' => '-0.7952'],
  'Petrer'           => ['slug' => 'petrer',   'cp' => '03610', 'lat' => '38.4697', 'lng' => '-0.7742'],
  'Novelda'          => ['slug' => 'novelda',  'cp' => '03660', 'lat' => '38.3857', 'lng' => '-0.7682'],
  'Monóvar'          => ['slug' => 'monovar',  'cp' => '03640', 'lat' => '38.4311', 'lng' => '-0.8361'],
  'Sax'              => ['slug' => 'sax',      'cp' => '03630', 'lat' => '38.5352', 'lng' => '-0.8156'],
  'Pinoso'           => ['slug' => 'pinoso',   'cp' => '03650', 'lat' => '38.4054', 'lng' => '-1.0397'],
  'Monforte del Cid' => ['slug' => 'monforte', 'cp' => '03670', 'lat' => '38.3745', 'lng' => '-0.6645'],
  'Salinas'          => ['slug' => 'salinas',  'cp' => '03688', 'lat' => '38.5053', 'lng' => '-0.9667'],
  'Aspe'             => ['slug' => 'aspe',     'cp' => '03680', 'lat' => '38.3454', 'lng' => '-0.7698'],
];

// ── Perfiles por ciudad — datos verificados (INE padrón 2024-2025, SINAC agua) ─
// NO inventar nombres de calles, barrios ni datos de infraestructura no contrastados.
// Solo usar los hechos concretos de este array para diferenciar el contenido por ciudad.
$ciudad_perfiles = [
  'Elda' => 'Ciudad industrial del calzado, ~55.000 hab (padrón 2025). Stock de vivienda mayoritariamente de los años 70-90 con bloques de pisos en altura. Agua dura (zona Vinalopó Medio). Tejido industrial con talleres y naves del sector calzado. Problemas típicos de fontanería: instalaciones envejecidas en bloques de los 70-80, cal que deteriora termos y grifos, desatascos en bajantes de edificios plurifamiliares, llaves de paso antiguas que no cierran, atascos de grasa en arquetas de naves industriales.',
  'Petrer' => 'Municipio colindante con Elda, ~34.000 hab (padrón 2025). Fuerte tradición en marroquinería y calzado. Urbanizaciones de los 80-90 con adosados y bloques. Agua dura (zona Vinalopó Medio). Problemas típicos: termos y calentadores con acumulación de cal, grifos y juntas deterioradas por la dureza del agua, desatascos en comunidades de vecinos, alta demanda de descalcificadores.',
  'Novelda' => 'Capital del mármol, ~27.000 hab. Economía basada en extracción y elaboración de piedra natural (mármol, piedra caliza). Stock de vivienda variado: centro histórico antiguo y expansión industrial del siglo XX. Fincas agrícolas en el término municipal con instalaciones propias. Agua dura. Problemas típicos: instalaciones en naves industriales de mármol, pozos y depósitos en fincas, cal en termos y calentadores, tuberías antiguas del centro histórico.',
  'Monóvar' => 'Municipio de interior, ~12.000 hab. Economía entre calzado y agricultura (vino, cultivos de secano). Casco urbano con edificación antigua y extrarradio con viviendas unifamiliares y chalets. Fincas rurales dispersas con instalaciones propias (pozos, depósitos, grupos de presión). Agua dura. Problemas típicos: instalaciones rurales con pozos y grupos de presión, tuberías antiguas del casco histórico, viviendas de campo con mantenimiento poco frecuente.',
  'Sax' => 'Municipio de ~10.000 hab. Casco histórico compacto con edificación de los años 60-70 y extrarradio residencial más moderno con chalets y adosados. Agua dura (zona Vinalopó). Problemas típicos: tuberías antiguas en el casco histórico, diferencias de presión entre zonas altas y bajas del pueblo, jardines con riego en viviendas unifamiliares del extrarradio, bajantes envejecidas en edificios más antiguos.',
  'Pinoso' => 'Municipio rural extenso, ~8.800 hab (padrón 2024). Economía agrícola y vitivinícola. Núcleo urbano pequeño con mucha vivienda dispersa en el campo. Alto porcentaje de propiedades rurales con instalaciones propias: depósitos, pozos, grupos de presión. Agua dura. Problemas típicos: grupos de presión en casas rurales, depósitos con sedimentos, tuberías exteriores expuestas a cambios de temperatura, acceso a fincas alejadas del núcleo.',
  'Monforte del Cid' => 'Municipio de ~7.700 hab situado entre el corredor del Vinalopó y el área metropolitana de Alicante. Urbanizaciones residenciales con presencia de segundas residencias y propietarios que pasan temporadas fuera. Fincas agrícolas en el término. Agua dura. Problemas típicos: instalaciones de segunda residencia que permanecen inactivas meses y fallan al reactivarse, termos y llaves de paso que no han funcionado en temporadas, fugas en comunidades de urbanización, viviendas de campo con depósito propio.',
  'Salinas' => 'Municipio pequeño, ~1.800 hab (padrón 2025). Antiguo núcleo ligado a la extracción de sal, hoy agrícola. Edificación predominantemente antigua. Escasa oferta local de servicios especializados. Agua dura (zona Vinalopó). Problemas típicos: instalaciones muy antiguas sin actualizar, baja presión en algunos puntos de la red, tuberías con años de servicio en viviendas del casco antiguo, necesidad de traer profesionales de municipios vecinos.',
  'Aspe' => 'Ciudad de ~22.000 hab (padrón 2024). Economía mixta: industria, agricultura y servicios. Centro urbano con edificios de los años 70-80 y zonas de expansión residencial más recientes con chalets y adosados en el extrarradio. Agua dura (zona Vinalopó Medio). Problemas típicos: instalaciones envejecidas en bloques del centro, jardines con riego en viviendas unifamiliares del extrarradio, desatascos en edificios de los 70-80, termos y calentadores con cal acumulada.',
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
    'busqueda_fugas' => ['label' => 'Fugas','filepath' => fn($s) => "fontanero/{$s}/busqueda_fugas.php",    'ruta_web' => fn($s) => "/fontanero/{$s}/busqueda_fugas",    'tipo' => 'busqueda_fugas'],
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
  // Merge páginas from DB
  try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS paginas (id INT AUTO_INCREMENT PRIMARY KEY, titulo VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL UNIQUE, filepath VARCHAR(255) NOT NULL UNIQUE, contenido LONGTEXT, meta_title VARCHAR(255) DEFAULT '', meta_desc TEXT DEFAULT '', publicado TINYINT(1) DEFAULT 1, fecha DATETIME DEFAULT CURRENT_TIMESTAMP, modificado DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $db_pages = $pdo->query("SELECT titulo, filepath FROM paginas WHERE publicado = 1 AND filepath NOT LIKE '%/%' ORDER BY fecha ASC")->fetchAll(PDO::FETCH_ASSOC);
    $existing_fps_in_cfg = array_column($corporativas_cfg, 'filepath');
    foreach ($db_pages as $dp) {
      if (!in_array($dp['filepath'], $existing_fps_in_cfg, true)) {
        $slug_p = str_replace('.php', '', $dp['filepath']);
        $corporativas_cfg[] = ['label' => $dp['titulo'], 'filepath' => $dp['filepath'], 'ruta_web' => '/' . $slug_p, 'custom' => true];
        $existing_fps_in_cfg[] = $dp['filepath'];
      }
    }
  } catch (Exception $e) { /* tabla no existe aún */ }

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
// HELPER: llamada unificada a la API de Claude
// ─────────────────────────────────────────────────────────────────────
function carol_curl_json(array $payload, int $timeout = 90): array {
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
    CURLOPT_TIMEOUT        => $timeout,
    CURLOPT_CONNECTTIMEOUT => 15,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
  ]);
  $raw  = curl_exec($ch);
  $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  if (!$raw) return ['error' => 'No se pudo conectar con la API de Claude.'];
  $resp = json_decode($raw, true);
  if ($code !== 200 || empty($resp['content'][0]['text'])) {
    return ['error' => 'Error API Claude (' . $code . '): ' . ($resp['error']['message'] ?? 'Error desconocido')];
  }
  if (($resp['stop_reason'] ?? '') === 'max_tokens') {
    return ['error' => 'Respuesta demasiado larga. Inténtalo de nuevo.'];
  }

  $text = $resp['content'][0]['text'];
  $json_str = '{' . $text;
  $data = json_decode($json_str, true);
  if (!$data && preg_match('/\{[\s\S]*\}/u', $json_str, $m)) {
    $data = json_decode($m[0], true);
  }
  if (!$data) {
    return ['error' => 'Claude no devolvió JSON válido.', 'raw' => substr($json_str, 0, 400)];
  }
  return ['data' => $data];
}

// ─────────────────────────────────────────────────────────────────────
// ACCIÓN: mejorar / crear (llamada a Claude)
// ─────────────────────────────────────────────────────────────────────
if ($accion === 'mejorar' || $accion === 'crear') {
  set_time_limit(240);
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

    // Contexto específico según tipo de página
    $page_ctx = [
      'financiacion'   => "Página de FINANCIACIÓN. CarolTemp ofrece financiación a plazos (sin adelanto) SOLO para sus servicios de fontanería: reparaciones de tuberías, detección y reparación de fugas, desatascos, instalación de termos eléctricos, descalcificadores y reformas de baño/cocina. NO hacen climatización ni aire acondicionado. Trabajan con entidades financieras especializadas. Proceso ágil. Teléfono: 611 165 129.",
      'contacto'       => "Página de CONTACTO. Tel: 611 165 129. WhatsApp. Atienden toda la comarca: Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Monforte, Salinas, Aspe. Horario: Lun-Vie 8-20h, Sáb 9-14h. Presupuesto gratuito.",
      'sobre-nosotros' => "Página SOBRE NOSOTROS. CarolTemp: empresa local de fontanería y climatización en la comarca interior de Alicante. Instaladores Nubeco certificados. Geófono y cámara para fugas. precio sin sorpresas siempre. Sin inventar 'años de experiencia'.",
      'index'          => "Página HOME. Presentar CarolTemp: fontanería y climatización en la comarca. Diferenciadores: precio sin sorpresas, geófono+cámara sin obras, Nubeco oficial. Zonas: Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Monforte, Salinas, Aspe.",
    ];
    $ctx_extra = $page_ctx[$basename] ?? "Página: {$desc_pagina}. Empresa CarolTemp — fontanería y climatización en la comarca interior de Alicante.";

    $system_corp = <<<'SYSC'
Eres un maquetador web SEO experto. Generas el HTML completo del <body> de páginas web para CarolTemp usando el sistema de componentes disponible.

DATOS DE CAROLTEMP:
- Empresa: fontanería en la comarca interior de Alicante (NO hacen climatización, NO hacen aire acondicionado)
- Servicios: fontanería urgente, detección de fugas (geófono+cámara), desatascos, instalación de termos eléctricos, descalcificadores, reformas de baño
- Teléfono: 611 165 129 | WhatsApp: https://wa.me/34611165129
- Zonas: Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Monforte del Cid, Salinas, Aspe
- Diferenciadores REALES: precio sin sorpresas antes de empezar, geófono+cámara para fugas sin romper, instaladores Nubeco certificados

REGLAS:
- NUNCA escribas "Vinalopó"
- NUNCA inventes estadísticas, porcentajes ni años de experiencia
- NUNCA frases vacías: "de confianza", "calidad garantizada", "expertos en"
- NUNCA menciones aire acondicionado, climatización ni aerotermia — CarolTemp NO ofrece esos servicios
- <?php ... ?> dentro del HTML: usa SOLO estas variables PHP disponibles: $base_url

COMPONENTES DISPONIBLES — usa los que más convengan para esta página:

[HERO OSCURO — siempre incluir]
<section class="hz-dark">
  <div class="hz-dark-bg"></div><div class="hz-dark-glow"></div>
  <div class="hz-dark-con">
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>ETIQUETA PEQUEÑA</div>
    <h1>TÍTULO PRINCIPAL</h1>
    <p class="hz-dark-sub">SUBTÍTULO</p>
    <div class="hz-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">📞 611 165 129</a>
      <a href="<?php echo $base_url; ?>contacto" class="btn-hz-g">CTA SECUNDARIO</a>
    </div>
  </div>
</section>

[SECCIÓN CON FONDO BLANCO]
<section class="zona-sec">
  <div class="cta-dark-con">
    <p class="zona-lbl">ETIQUETA PEQUEÑA</p>
    <h2>TÍTULO <span class="hl">PARTE DESTACADA</span></h2>
    CONTENIDO
  </div>
</section>

[SECCIÓN CON FONDO GRIS]
<section class="zona-sec zona-sec-gray">...</section>

[DOS COLUMNAS — texto + tarjeta lateral]
<div class="zona-tcol">
  <div>COLUMNA IZQUIERDA (texto, checklist...)</div>
  <div>COLUMNA DERECHA (tarjeta, imagen, info...)</div>
</div>

[CHECKLIST]
<ul class="zona-chk">
  <li><span class="chk-ico"><svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>TEXTO ÍTEM</li>
</ul>

[TARJETA DE CONTACTO]
<div class="zona-icard">
  <div class="zona-icard-h"><strong>CarolTemp</strong><span>SUBTÍTULO</span></div>
  <div class="zona-ir"><span class="zona-ir-l">LABEL</span><span class="zona-ir-v">VALOR</span></div>
  <a href="tel:+34611165129" class="zona-icard-btn">📞 Llamar ahora</a>
</div>

[CARDS EN GRID — para opciones, servicios, ventajas, pasos]
<div class="zona-svc">
  <div class="zona-sc">
    <span class="zona-sc-n">01</span>  <!-- opcional: numeración -->
    <h3>TÍTULO CARD</h3>
    <p>TEXTO BREVE</p>
  </div>
</div>

[FAQ ACORDEÓN]
<div class="zona-faq">
  <div class="zona-fi open">
    <div class="zona-fiq" onclick="togFaq(this)"><span>PREGUNTA</span><span class="zona-fiq-i"><svg viewBox="0 0 10 10" fill="none"><path d="M5 1v8M1 5h8" stroke-width="1.5" stroke-linecap="round"/></svg></span></div>
    <div class="zona-fia">RESPUESTA</div>
  </div>
  <div class="zona-fi">...</div>
</div>

[TEXTO LARGO — prosa]
<div class="zona-prose"><p>PÁRRAFO</p><p>PÁRRAFO</p></div>

[CTA FINAL OSCURO — siempre incluir al final]
<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>PREGUNTA/CTA PRINCIPAL <span>PARTE SECUNDARIA</span></h2>
    <p>TEXTO BREVE</p>
    <div class="cta-dark-btns">
      <a href="tel:+34611165129" class="btn-hz-w">📞 Llamar ahora</a>
      <a href="https://wa.me/34611165129" target="_blank" rel="noopener" class="btn-hz-g">💬 WhatsApp</a>
    </div>
  </div>
</section>

DEVUELVE SOLO JSON CON ESTA ESTRUCTURA:
{
  "meta_title": "máx 60 chars",
  "meta_desc": "150-160 chars exactos",
  "html": "TODO EL HTML DEL BODY usando los componentes anteriores — sin <html>, sin <head>, sin <body>"
}

El campo "html" debe ser HTML completo y bien estructurado. Decide tú qué secciones y en qué orden convienen para esta página concreta. No sigas un patrón fijo — adapta la estructura al contenido.

CRÍTICO JSON: comillas dobles en claves. El valor de "html" va entre comillas dobles — escapa las comillas dobles internas con \". Sin comas finales.
SYSC;

    $payload_corp = [
      'model'      => ANTHROPIC_MODEL,
      'max_tokens' => 4000,
      'system'     => $system_corp,
      'messages'   => [
        ['role' => 'user',      'content' => "Genera la página: {$desc_pagina}\n\nContexto: {$ctx_extra}\n\nDecide la mejor estructura para esta página y genera el HTML completo usando los componentes disponibles."],
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
      CURLOPT_TIMEOUT        => 120,
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
    if (($resp_corp['stop_reason'] ?? '') === 'max_tokens') { echo json_encode(['error' => 'Respuesta demasiado larga. Inténtalo de nuevo.']); exit; }

    $json_corp = '{' . $resp_corp['content'][0]['text'];
    $data_corp = json_decode($json_corp, true);
    if (!$data_corp && preg_match('/\{[\s\S]*\}/u', $json_corp, $m)) $data_corp = json_decode($m[0], true);
    if (!$data_corp) { echo json_encode(['error' => 'Claude no devolvió JSON válido.', 'raw' => substr($json_corp, 0, 600)]); exit; }

    $e          = fn($v) => var_export($v, true);
    $meta_title = $data_corp['meta_title'] ?? $desc_pagina . ' — CarolTemp';
    $meta_desc  = $data_corp['meta_desc']  ?? '';
    $html_body  = $data_corp['html']       ?? '';
    $meta_url   = 'https://caroltemp.com/' . ltrim(str_replace('.php','', $filepath_in), '/');

    $php_corp  = "<?php\n";
    $php_corp .= "\$meta_title  = {$e($meta_title)};\n";
    $php_corp .= "\$meta_desc   = {$e($meta_desc)};\n";
    $php_corp .= "\$meta_url    = {$e($meta_url)};\n";
    $php_corp .= "\$schema_type = 'local';\n";
    $php_corp .= "\$page_css    = 'zona';\n";
    $php_corp .= "\$page_js     = 'zona';\n";
    $php_corp .= "\$robots_meta = 'index';\n";
    $php_corp .= "include 'includes/head.php';\n";
    $php_corp .= "?>\n\n";
    $php_corp .= $html_body . "\n";
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
- Servicios: fontanería urgente, fugas (geófono+cámara), desatascos, termos, descalcificadores, reformas de baño
- PROHIBIDO mencionar: camión cuba, camiones cuba, fosas sépticas, pocería — CarolTemp NO hace esos servicios

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
    {"pregunta": "¿Cuánto cuesta un fontanero en [Ciudad]?", "respuesta": "precio sin sorpresas antes de empezar. Una reparación sencilla desde 60-80€."},
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
      'max_tokens' => 3000,
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

  // ── DOS PASOS: estrategia + contenido libre para todas las páginas de ciudad ───
  // Tipos: hub_ciudad, busqueda_fugas, urgencias, desatascos, fontanero
  $tipos_dos_pasos = ['hub_ciudad', 'busqueda_fugas', 'urgencias', 'desatascos', 'fontanero'];
  if (in_array($tipo, $tipos_dos_pasos, true)) {

    if (!$ciudad || !$ciudad_slug) {
      echo json_encode(['error' => 'Faltan parámetros: ciudad, ciudad_slug']);
      exit;
    }
    if (!isset($ciudades[$ciudad])) {
      echo json_encode(['error' => 'Ciudad no válida: ' . $ciudad]);
      exit;
    }

    $perfil_ciudad = $ciudad_perfiles[$ciudad] ?? 'Municipio de la comarca interior de Alicante.';
    $otras_ciudades_v2 = [];
    foreach ($ciudades as $c_nombre => $c_info) {
      if ($c_info['slug'] === $ciudad_slug) continue;
      $otras_ciudades_v2[] = ['nombre' => $c_nombre, 'slug' => $c_info['slug']];
    }

    $tipo_labels_v2 = [
      'hub_ciudad'     => 'fontanería general — hub ciudad (todos los servicios)',
      'busqueda_fugas' => 'detección y búsqueda de fugas de agua',
      'urgencias'      => 'fontanero urgente — emergencias y averías',
      'desatascos'     => 'desatascos de tuberías, bajantes y arquetas',
      'fontanero'      => 'fontanero — reparaciones, instalaciones y mantenimiento general',
    ];
    $servicio_etiqueta = $tipo_labels_v2[$tipo] ?? $tipo;

    // ════ PASO 1 — ESTRATEGIA ════════════════════════════════════════
    $system_p1 = <<<SYS
Eres un estratega SEO local especializado en fontanería. Tu tarea es definir la estrategia editorial ganadora para una página web.

CAROLTEMP — datos clave:
- Zona: Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Monforte del Cid, Salinas, Aspe (interior Alicante)
- Diferenciadores REALES: geófono+cámara detectan fugas sin romper paredes, presupuesto cerrado antes de empezar, instaladores Nubeco oficiales
- PROHIBIDO mencionar: camión cuba, fosas sépticas, pocería, climatización, aires acondicionados
- NUNCA "Vinalopó", NUNCA estadísticas ni años inventados

Tu misión: analiza el SERVICIO y la CIUDAD. Piensa como el usuario que hace esa búsqueda en Google ahora mismo. Define:
1. Qué quiere saber/hacer exactamente (intención real, no genérica)
2. Qué tiene la competencia que no diferencia — qué es lo típico y aburrido
3. Qué ángulo usa ESTA ciudad concreta (basado en sus características)
4. Qué preguntas reales haría alguien en esta situación
5. Qué 3 secciones concretas (además del hero) necesita esta página — propón ángulos creativos: proceso paso a paso, señales de alarma, coste orientativo, comparativa, casos concretos. Máximo 3 secciones.

DEVUELVE SOLO JSON VÁLIDO:
{
  "intencion": "qué quiere hacer/saber el usuario (1-2 frases muy concretas)",
  "angulo": "qué hace única esta página para {$ciudad} — diferenciador real (1 frase)",
  "secciones": [
    "Sección 1: nombre + qué contenido aporta exactamente",
    "Sección 2: ...",
    "Sección 3: ..."
  ],
  "preguntas_reales": [
    "pregunta 1 que haría alguien con este problema en {$ciudad}",
    "pregunta 2",
    "pregunta 3",
    "pregunta 4"
  ],
  "info_clave": [
    "dato o contexto imprescindible para {$ciudad} + este servicio",
    "dato 2"
  ]
}

CRÍTICO: comillas dobles en todo el JSON. Sin comas finales.
SYS;

    $res_p1 = carol_curl_json([
      'model'      => ANTHROPIC_MODEL,
      'max_tokens' => 1500,
      'system'     => $system_p1,
      'messages'   => [
        ['role' => 'user', 'content' => "Servicio: {$servicio_etiqueta}\nCiudad: {$ciudad} (CP: {$ciudad_cp})\nDatos específicos de {$ciudad}: {$perfil_ciudad}"],
        ['role' => 'assistant', 'content' => '{'],
      ],
    ], 60);

    if (isset($res_p1['error'])) {
      echo json_encode(['error' => 'Paso 1 (estrategia): ' . $res_p1['error']]);
      exit;
    }
    $estrategia = $res_p1['data'];

    // ════ PASO 2 — CONTENIDO HTML LIBRE ══════════════════════════════
    $estrategia_txt = "Intención: " . ($estrategia['intencion'] ?? '') . "\n";
    $estrategia_txt .= "Ángulo: " . ($estrategia['angulo'] ?? '') . "\n";
    $estrategia_txt .= "Secciones propuestas:\n";
    foreach ($estrategia['secciones'] ?? [] as $s) $estrategia_txt .= "  • {$s}\n";
    $estrategia_txt .= "Preguntas reales de usuarios:\n";
    foreach ($estrategia['preguntas_reales'] ?? [] as $q) $estrategia_txt .= "  • {$q}\n";
    $estrategia_txt .= "Info clave:\n";
    foreach ($estrategia['info_clave'] ?? [] as $i) $estrategia_txt .= "  • {$i}\n";

    $svg_chk = '<svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    $svg_faq = '<svg viewBox="0 0 10 10" fill="none"><path d="M5 1v8M1 5h8" stroke-width="1.5" stroke-linecap="round"/></svg>';

    $reglas_comunes = <<<SYSRULES
NORMAS INAMOVIBLES:
- NUNCA pongas el teléfono en meta_title (ni como "| 611 165 129" ni de ninguna forma)
- meta_title: keyword primero, máx 58 chars. BUENO: "Fontanero en Sax — CarolTemp". MALO: "CarolTemp · 611 165 129"
- meta_desc: 140-155 chars exactos, sin teléfono, con diferenciador real de la ciudad
- PROHIBIDO inventar tiempos de respuesta, horarios de guardia o disponibilidad 24h
- PROHIBIDO: climatización, camión cuba, fosas sépticas, Vinalopó, estadísticas inventadas
- NUNCA frases vacías: "expertos en", "de confianza", "calidad garantizada"
- NO uses variables PHP — todo hardcodeado
- NO añadas mapa, zona-ztags ni CTA final (se añaden automáticamente)
SYSRULES;

    // Hub ciudad: estructura definida pero con contenido rico — sin canibalizar sub-páginas
    if ($tipo === 'hub_ciudad') {
      $system_p2 = <<<SYS
Eres un redactor web SEO especializado en fontanería local. Generas la página HUB de ciudad para CarolTemp (interior de Alicante).

{$reglas_comunes}

════ PROPÓSITO DEL HUB — MUY IMPORTANTE ════
Esta página es el paraguas de todos los servicios en {$ciudad}.
Las sub-páginas /fontanero/{$ciudad_slug}/urgencias, /busqueda_fugas y /desatascos ya tienen su propio contenido profundo.
El hub NO debe entrar en detalle de esos 3 servicios — solo los menciona brevemente y enlaza.
El hub SÍ debe desarrollar los servicios que NO tienen sub-página: termos eléctricos, descalcificadores, reformas de baño, instalaciones nuevas, grupos de presión, mantenimiento.
Así el hub aporta contenido único, no duplica las sub-páginas.

════ H1 — REGLA CRÍTICA ════
El H1 DEBE contener las palabras "fontanero en {$ciudad}" o "fontanería en {$ciudad}" como base.
Ejemplo correcto: "Fontanero en {$ciudad} — <span class=\"hl\">termos, reformas y descalcificadores</span>"
Ejemplo INCORRECTO: "Tuberías antiguas del casco sin romper paredes" (sin keyword principal)

════ ESTRUCTURA — 6 BLOQUES ════

BLOQUE 1: Hero hz-dark
- H1: "Fontanero en {$ciudad}" + ángulo diferenciador en <span class="hl">
- Subtítulo: 1 frase concreta sobre los servicios más demandados en {$ciudad}, sin inventar horarios
- Botones: tel:+34611165129 y /contacto

BLOQUE 2: dif-strip — 4 diferenciadores REALES (geófono+cámara, presupuesto cerrado, Nubeco oficial + 1 dato específico de {$ciudad})

BLOQUE 3: Sección blanca — zona-tcol
- Izquierda: intro 2 frases sobre el tipo de vivienda/agua/infraestructura de {$ciudad} + checklist 5 ítems de servicios que hace CarolTemp ahí (mezcla: termos, descalcificadores, reformas, fugas, instalaciones)
- Derecha: zona-icard (tel, wa, horario Lun-Vie 8-20h Sáb 9-14h, financiación disponible)

BLOQUE 4: Sección gris — grid 6 servicios (3 con link sub-página + 3 del hub)
- 01 → /fontanero/{$ciudad_slug}/urgencias — "Fontanero urgente en {$ciudad}" — 1 frase del tipo de avería, sin detalle
- 02 → /fontanero/{$ciudad_slug}/busqueda_fugas — "Detección de fugas en {$ciudad}" — 1 frase, sin detalle
- 03 → /fontanero/{$ciudad_slug}/desatascos — "Desatascos en {$ciudad}" — 1 frase, sin detalle
- 04 → /servicios#termos — "Termos eléctricos en {$ciudad}" — específico: por qué el agua dura de {$ciudad} destruye termos
- 05 → /servicios#descalcificadores — "Descalcificadores en {$ciudad}" — específico: qué tipo de agua tiene {$ciudad} y solución
- 06 → /servicios#reformas — "Reformas de baño en {$ciudad}" — presupuesto cerrado sin sorpresas

BLOQUE 5: Sección blanca — desarrollo de termos + descalcificadores (contenido exclusivo del hub, no en sub-páginas)
- Por qué el agua de {$ciudad} afecta específicamente a termos y calentadores
- Qué descalcificador encaja con el tipo de vivienda más común en {$ciudad}
- 1 párrafo, concreto, sin frases vacías

BLOQUE 6: Sección gris — FAQ 4 preguntas que mezclan servicios del hub (NO urgencias/fugas/desatascos en profundidad)
- Preguntas sobre: presupuestos, termos, reformas, descalcificadores, instalaciones nuevas en {$ciudad}
- Respuestas directas y honestas — sin inventar precios exactos ni tiempos de respuesta

════ COMPONENTES ════
Hero: <section class="hz-dark"><div class="hz-dark-bg"></div><div class="hz-dark-glow"></div><div class="hz-dark-con"><div class="hz-dark-tag"><span class="hz-dark-dot"></span>TAG</div><h1>TÍT <span class="hl">HL</span></h1><p class="hz-dark-sub">SUB</p><div class="hz-dark-btns"><a href="tel:+34611165129" class="btn-hz-w">📞 611 165 129</a><a href="/contacto" class="btn-hz-g">Pedir presupuesto</a></div></div></section>
Strip: <div class="dif-strip"><div class="dif-strip-in"><div class="dif-item"><span class="dif-val">VAL</span><span class="dif-lbl">LBL</span></div></div></div>
Sección blanca: <section class="zona-sec"><div class="cta-dark-con"><p class="zona-lbl">LBL</p><h2>TÍT <span class="hl">HL</span></h2>CONT</div></section>
Sección gris: <section class="zona-sec zona-sec-gray">...</section>
2 columnas: <div class="zona-tcol"><div>IZQ</div><div>DER</div></div>
Checklist: <ul class="zona-chk"><li><span class="chk-ico">{$svg_chk}</span>TEXTO</li></ul>
iCard: <div class="zona-icard"><div class="zona-icard-h"><strong>CarolTemp · {$ciudad}</strong><span>Fontanería</span></div><div class="zona-ir"><span class="zona-ir-l">Teléfono</span><span class="zona-ir-v"><a href="tel:+34611165129">611 165 129</a></span></div><div class="zona-ir"><span class="zona-ir-l">WhatsApp</span><span class="zona-ir-v"><a href="https://wa.me/34611165129">Escribir ahora →</a></span></div><div class="zona-ir"><span class="zona-ir-l">Horario</span><span class="zona-ir-v">Lun-Vie 8-20h · Sáb 9-14h</span></div><div class="zona-ir"><span class="zona-ir-l">Financiación</span><span class="zona-ir-v">Disponible para reformas</span></div><a href="tel:+34611165129" class="zona-icard-btn">📞 Llamar ahora</a></div>
Grid servicios: <div class="zona-svc"><a href="URL" class="zona-sc"><span class="zona-sc-n">01</span><h3>TÍTULO</h3><p>TEXTO</p><span class="zona-sc-a">Ver servicio →</span></a></div>
FAQ: <div class="zona-faq"><div class="zona-fi open"><div class="zona-fiq" onclick="togFaq(this)"><span>PREGUNTA</span><span class="zona-fiq-i">{$svg_faq}</span></div><div class="zona-fia">RESPUESTA</div></div><div class="zona-fi"><div class="zona-fiq" onclick="togFaq(this)"><span>PREGUNTA</span><span class="zona-fiq-i">{$svg_faq}</span></div><div class="zona-fia">RESPUESTA</div></div></div>
Prosa: <div class="zona-prose"><p>TEXTO</p></div>

DEVUELVE SOLO JSON:
{"meta_title":"...","meta_desc":"...","html":"..."}
Escapa las comillas dobles dentro de html con \".
SYS;

      $user_p2 = "Ciudad: {$ciudad} (CP {$ciudad_cp}, slug: {$ciudad_slug})\nPerfil: {$perfil_ciudad}\n\nContexto estratégico del Paso 1:\n{$estrategia_txt}\n\nGenera los 6 bloques. H1 debe contener 'fontanero en {$ciudad}'. El hub desarrolla termos, descalcificadores y reformas — no entra en detalle de urgencias, fugas ni desatascos (esas tienen sub-páginas).";

    } else {
      // ── Plantilla fija por tipo de servicio ──────────────────────────

      // Diferenciadores del strip — distintos por servicio (NUNCA mezclar)
      $strip_por_tipo = [
        'desatascos'     => "Diagnóstico con cámara endoscópica|Precio cerrado antes de empezar|Sin suciedad ni daños|Bajantes, arquetas e inodoros",
        'busqueda_fugas' => "Geófono + cámara termográfica|Localización sin romper paredes|Presupuesto antes de actuar|Instaladores certificados Nubeco",
        'urgencias'      => "Avería vista, precio dado|Reparación en la misma visita|Sin letra pequeña|Fontanero local — conoce tu zona",
        'fontanero'      => "Presupuesto gratuito sin compromiso|Instaladores certificados Nubeco|Precio cerrado antes de empezar|Conocemos cada barrio de {$ciudad}",
      ];
      $strip_items_raw = $strip_por_tipo[$tipo] ?? "Presupuesto gratuito|Precio cerrado|Instaladores Nubeco|Zona interior Alicante";
      $strip_items = explode('|', str_replace('{$ciudad}', $ciudad, $strip_items_raw));

      // H1 completo hardcodeado por tipo — el complemento NO lo decide Claude
      // (Claude tendía a meter características de la ciudad que quedan raras)
      $h1_completo_por_tipo = [
        'desatascos'     => "Desatascos en {$ciudad} <span class=\"hl\">bajantes, arquetas e inodoros</span>",
        'busqueda_fugas' => "Detección de fugas en {$ciudad} <span class=\"hl\">sin romper paredes</span>",
        'urgencias'      => "Fontanero urgente en {$ciudad} <span class=\"hl\">precio antes de empezar</span>",
        'fontanero'      => "Fontanero en {$ciudad} <span class=\"hl\">presupuesto gratuito</span>",
      ];
      $h1_base = $h1_completo_por_tipo[$tipo] ?? "Fontanería en {$ciudad}";

      // Checklist label por tipo
      $chk_label_por_tipo = [
        'desatascos'     => "¿Cuándo llamar?",
        'busqueda_fugas' => "Señales de fuga",
        'urgencias'      => "Situaciones urgentes",
        'fontanero'      => "Lo que hacemos",
      ];
      $chk_label = $chk_label_por_tipo[$tipo] ?? "Servicios";

      // Grid de tarjetas por tipo
      $cards_por_tipo = [
        'desatascos'     => "Bajantes y tuberías|Arquetas y registros|Inodoros y lavabos",
        'busqueda_fugas' => "Fugas en tuberías empotradas|Fugas bajo solería|Fugas en contadores",
        'urgencias'      => "Rotura de tubería|Inundación y agua detenida|Calentador sin agua caliente",
        'fontanero'      => "Termos y calentadores|Instalaciones nuevas|Reparaciones y mantenimiento",
      ];
      $cards_raw = $cards_por_tipo[$tipo] ?? "Servicio 1|Servicio 2|Servicio 3";
      $cards = explode('|', $cards_raw);

      // Sección de proceso — título e instrucción por tipo
      $proceso_por_tipo = [
        'desatascos'     => ["Cómo hacemos el desatasco", "Explica en 3 pasos: 1) diagnóstico con cámara para ver qué y dónde está el tapón, 2) desatasco con el método adecuado según lo que muestre la cámara, 3) verificación final. Concreto, sin adornos."],
        'busqueda_fugas' => ["Cómo localizamos la fuga", "Explica en 3 pasos: 1) escucha con geófono para acotar zona, 2) confirmación con cámara termográfica, 3) marcado exacto del punto antes de abrir nada. Sin inventar tecnología adicional."],
        'urgencias'      => ["Qué pasa cuando llamas", "Explica en 3 pasos: 1) llamada — nos cuentas la avería y damos precio orientativo, 2) llegamos a {$ciudad} y confirmamos precio exacto antes de tocar nada, 3) reparamos en la misma visita siempre que sea posible."],
        'fontanero'      => ["Cómo trabajamos en {$ciudad}", "Explica en 3 pasos: 1) presupuesto gratuito con precio cerrado, 2) trabajo con materiales de calidad y sin sorpresas en la factura, 3) garantía en mano de obra. Concreto."],
      ];
      [$proceso_titulo, $proceso_instruccion] = $proceso_por_tipo[$tipo] ?? ["Cómo trabajamos", "Describe el proceso en 3 pasos."];

      $system_p2 = <<<SYS
Eres un maquetador SEO experto en fontanería local. Generas HTML para CarolTemp (interior de Alicante).

{$reglas_comunes}

════ CONTEXTO DE CIUDAD (úsalo para personalizar) ════
{$estrategia_txt}

════ ESTRUCTURA — 5 BLOQUES EXACTOS, NI UNO MÁS ════

BLOQUE 1 — Hero hz-dark
- H1 FIJO — cópialo exactamente sin cambiar nada: {$h1_base}
- Subtítulo: 1 frase directa (máx 15 palabras) sobre el problema o síntoma más habitual en {$ciudad}. Sin inventar disponibilidad 24h ni tiempos exactos. Usa el perfil de ciudad del contexto.
- Botones: tel:+34611165129 y /contacto

BLOQUE 2 — dif-strip
Usa EXACTAMENTE estos 4 diferenciadores (ya están elegidos para este servicio):
{$strip_items[0]} | {$strip_items[1]} | {$strip_items[2]} | {$strip_items[3]}

BLOQUE 3 — Sección blanca, 2 columnas (zona-tcol)
- Izquierda: H2 "{$chk_label}" + checklist de exactamente 5 ítems MUY CONCRETOS para {$ciudad} y este servicio.
  Usa el perfil de la ciudad del contexto. Sin ítems genéricos ni inventados.
- Derecha: zona-icard (tel, wa, horario Lun-Vie 8-20h Sáb 9-14h, financiación disponible)

BLOQUE 4 — Sección gris — proceso en 3 tarjetas
H2: "{$proceso_titulo}"
{$proceso_instruccion}
Usa grid zona-svc con 3 zona-sc (01, 02, 03). Texto de cada tarjeta: máx 2 frases, directo.

BLOQUE 5 — Sección blanca — FAQ exactamente 3 preguntas
Preguntas reales de alguien en {$ciudad} buscando este servicio. Respuestas directas, sin rodeos, sin inventar precios exactos.

════ COMPONENTES ════
Hero: <section class="hz-dark"><div class="hz-dark-bg"></div><div class="hz-dark-glow"></div><div class="hz-dark-con"><div class="hz-dark-tag"><span class="hz-dark-dot"></span>TAG</div><h1>TÍT <span class="hl">HL</span></h1><p class="hz-dark-sub">SUB</p><div class="hz-dark-btns"><a href="tel:+34611165129" class="btn-hz-w">📞 611 165 129</a><a href="/contacto" class="btn-hz-g">Pedir presupuesto</a></div></div></section>
Strip: <div class="dif-strip"><div class="dif-strip-in"><div class="dif-item"><span class="dif-val">VAL</span><span class="dif-lbl">LBL</span></div></div></div>
Sección blanca: <section class="zona-sec"><div class="cta-dark-con"><p class="zona-lbl">LBL</p><h2>TÍT <span class="hl">HL</span></h2>CONT</div></section>
Sección gris: <section class="zona-sec zona-sec-gray">...</section>
2col: <div class="zona-tcol"><div>IZQ</div><div>DER</div></div>
Checklist: <ul class="zona-chk"><li><span class="chk-ico">{$svg_chk}</span>ÍTEM</li></ul>
iCard: <div class="zona-icard"><div class="zona-icard-h"><strong>CarolTemp · {$ciudad}</strong><span>Fontanería</span></div><div class="zona-ir"><span class="zona-ir-l">Teléfono</span><span class="zona-ir-v"><a href="tel:+34611165129">611 165 129</a></span></div><div class="zona-ir"><span class="zona-ir-l">WhatsApp</span><span class="zona-ir-v"><a href="https://wa.me/34611165129">Escribir ahora →</a></span></div><div class="zona-ir"><span class="zona-ir-l">Horario</span><span class="zona-ir-v">Lun-Vie 8-20h · Sáb 9-14h</span></div><div class="zona-ir"><span class="zona-ir-l">Financiación</span><span class="zona-ir-v">Disponible para reformas</span></div><a href="tel:+34611165129" class="zona-icard-btn">📞 Llamar ahora</a></div>
Cards: <div class="zona-svc"><a href="#" class="zona-sc"><span class="zona-sc-n">01</span><h3>TÍT</h3><p>TXT</p></a></div>
FAQ: <div class="zona-faq"><div class="zona-fi open"><div class="zona-fiq" onclick="togFaq(this)"><span>P</span><span class="zona-fiq-i">{$svg_faq}</span></div><div class="zona-fia">R</div></div><div class="zona-fi"><div class="zona-fiq" onclick="togFaq(this)"><span>P</span><span class="zona-fiq-i">{$svg_faq}</span></div><div class="zona-fia">R</div></div></div>

DEVUELVE SOLO JSON:
{"meta_title":"...","meta_desc":"...","html":"..."}
Escapa las comillas dobles dentro de html con \".
SYS;

      $user_p2 = "Ciudad: {$ciudad} (CP {$ciudad_cp})\nPerfil ciudad: {$perfil_ciudad}\n\nGenera los 5 bloques. El H1 ya está dado — cópialo exactamente. La personalización de ciudad va en el subtítulo del hero y en el checklist del bloque 3.";
    }

    $res_p2 = carol_curl_json([
      'model'      => ANTHROPIC_MODEL,
      'max_tokens' => 6000,
      'system'     => $system_p2,
      'messages'   => [
        ['role' => 'user',      'content' => $user_p2],
        ['role' => 'assistant', 'content' => '{'],
      ],
    ], 120);

    if (isset($res_p2['error'])) {
      echo json_encode(['error' => 'Paso 2 (contenido): ' . $res_p2['error']]);
      exit;
    }
    $data_v2 = $res_p2['data'];

    $meta_title_v2 = $data_v2['meta_title'] ?? "{$servicio_etiqueta} en {$ciudad} — CarolTemp";
    $meta_desc_v2  = $data_v2['meta_desc']  ?? '';
    $html_body_v2  = $data_v2['html']       ?? '';

    // Sanitizar: quitar teléfono del meta_title si Claude lo añadió
    $meta_title_v2 = preg_replace('/\s*[·|\-]\s*6\d[\d\s]{7,}.*$/u', '', $meta_title_v2);
    $meta_title_v2 = trim($meta_title_v2);

    $lat_v2 = $ciudades[$ciudad]['lat'] ?? '38.4766';
    $lng_v2 = $ciudades[$ciudad]['lng'] ?? '-0.7952';

    // Determinar depth y filepath de salida
    if ($tipo === 'hub_ciudad') {
      $depth_v2    = 1;
      $filepath_v2 = 'fontanero/' . $ciudad_slug . '.php';
    } else {
      $depth_v2    = 2;
      $filepath_v2 = 'fontanero/' . $ciudad_slug . '/' . $tipo . '.php';
    }

    $php_v2 = generar_php_libre(
      $html_body_v2, $meta_title_v2, $meta_desc_v2,
      $ciudad, $ciudad_slug, $ciudad_cp, $tipo,
      $otras_ciudades_v2, $depth_v2, $lat_v2, $lng_v2
    );

    echo json_encode([
      'ok'            => true,
      'php_contenido' => $php_v2,
      'filepath'      => $filepath_v2,
      'meta_title'    => $meta_title_v2,
      'meta_desc'     => $meta_desc_v2,
    ]);
    exit;
  }
  // ── FIN DOS PASOS ─────────────────────────────────────────────────

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
Eres un redactor SEO local especializado en fontanería. Generas contenido para CarolTemp en la comarca interior de Alicante.

════ REGLAS META_TITLE — LEE ESTO PRIMERO ════
- Formato OBLIGATORIO: [Keyword] [Ciudad] [diferenciador] — CarolTemp
- La keyword principal va PRIMERO, antes que ciudad y marca
- NUNCA pongas el teléfono en el título
- NUNCA superes 58 caracteres (cuenta antes de escribir)
- Ejemplos BUENOS: "Fontanero en Monóvar 24h — CarolTemp" / "Fontanería Petrer cal y fugas — CarolTemp"
- Ejemplos MALOS: "Fontanería en Petrer — CarolTemp | 611 165 129" / "CarolTemp fontanero Petrer"

════ REGLAS META_DESC — LEE ESTO PRIMERO ════
- EXACTAMENTE 140-155 caracteres (cuenta antes de escribir, ajusta si te pasas)
- Formato: [Servicio] en [Ciudad]. [Diferenciador REAL de esa ciudad, no genérico]. [CTA sin teléfono].
- NUNCA incluyas el teléfono (no es clickable en Google y ocupa chars útiles)
- Ejemplo BUENO (148 chars): "Fontanería en Petrer. Especialistas en problemas de cal: juntas fundidas, termos incrustados y tuberías obstruidas. Presupuesto gratuito. Llámanos."
- Ejemplo MALO (genérico): "Fontanero en Petrer con amplia experiencia. Servicios de calidad garantizados. Llama al 611 165 129 para más información."

════ REGLAS DE DIFERENCIACIÓN — LO MÁS IMPORTANTE ════
- Cada ciudad tiene una CAUSA RAÍZ diferente para los problemas de fontanería. Úsala.
- Petrer / Novelda → agua extremadamente dura (>600 mg/L cal): juntas destruidas, termos con piedra, tuberías obstruidas
- Elda → edificios de los años 60-70 con tuberías de hierro oxidadas en bloques de viviendas
- Pinoso / Monóvar → zona rural: pozos propios, grupos de presión, tuberías enterradas en fincas
- Sax → mezcla urbano/industrial: viviendas antiguas + naves con instalaciones mixtas
- Monforte del Cid → urbanizaciones con segunda residencia: instalaciones paradas meses, presión variable
- Salinas / Aspe → municipios más pequeños, edificios unifamiliares, instalaciones de los 80-90
- PROHIBIDO copiar estructura de otra ciudad cambiando solo el nombre

SOBRE CAROLTEMP:
- Zona: Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Monforte del Cid, Salinas, Aspe
- Diferenciadores REALES: presupuesto gratuito sin compromiso, geófono+cámara para fugas sin obras, instaladores certificados Nubeco
- Servicios: fontanería urgente, detección de fugas, desatascos, termos, descalcificadores, reformas de baño
- PROHIBIDO: camión cuba, fosas sépticas, pocería, climatización, aires acondicionados
- NUNCA inventes estadísticas, años de experiencia, número de clientes ni porcentajes
- NUNCA uses: "expertos en", "somos tu fontanero de confianza", "calidad y profesionalidad", "Vinalopó"

DEVUELVE SOLO ESTE JSON VÁLIDO:
{
  "meta_title": "ver reglas arriba — máx 58 chars, keyword primero, sin teléfono",
  "meta_desc": "ver reglas arriba — 140-155 chars exactos, sin teléfono",
  "hero_sub": "Una frase de 10-15 palabras con el problema o característica más específica de {$ciudad} — NO genérica",
  "intro_p1": "2-3 frases sobre los problemas de fontanería más habituales en {$ciudad}, usando los datos CONCRETOS de esa ciudad",
  "intro_p2": "1-2 frases sobre cómo CarolTemp los resuelve con sus diferenciadores reales",
  "checklist": ["servicio concreto para {$ciudad} 1", "servicio concreto 2", "servicio concreto 3", "servicio concreto 4", "servicio concreto 5"],
  "faq": [
    {"pregunta": "pregunta real que busca alguien en {$ciudad} — NO genérica", "respuesta": "respuesta directa y honesta, 1-2 frases, sin frases vacías"},
    {"pregunta": "segunda pregunta específica de {$ciudad}", "respuesta": "respuesta directa"},
    {"pregunta": "tercera pregunta", "respuesta": "respuesta"},
    {"pregunta": "cuarta pregunta", "respuesta": "respuesta"}
  ]
}

CRÍTICO JSON: Usa comillas dobles para claves y valores. Comillas simples dentro de valores si hace falta. Sin comas finales.
SYS;

    $user_hub  = "Ciudad: {$ciudad} (CP: {$ciudad_cp}, slug: {$ciudad_slug})\n\n";
    $user_hub .= "DATOS ESPECÍFICOS DE {$ciudad}:\n{$perfil_ciudad}\n\n";
    $user_hub .= "Genera la página PRINCIPAL de fontanería de {$ciudad}. ";
    $user_hub .= "intro_p1 y checklist deben reflejar los problemas CONCRETOS de {$ciudad} descritos arriba. ";
    $user_hub .= "Las FAQ deben hacer referencia a situaciones reales de {$ciudad} — NO uses respuestas copiadas de otras ciudades.";

    $payload_hub = [
      'model'      => ANTHROPIC_MODEL,
      'max_tokens' => 3000,
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

    $ciudad_lat = $ciudades[$ciudad]['lat'] ?? '38.4766';
    $ciudad_lng = $ciudades[$ciudad]['lng'] ?? '-0.7952';
    $php_hub = generar_php_hub_ciudad($data_hub, $ciudad, $ciudad_slug, $ciudad_cp, $otras_zonas, $ciudad_lat, $ciudad_lng);

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
Eres un redactor SEO local especializado en fontanería. Generas contenido para CarolTemp en Alicante interior.

════ REGLAS META_TITLE ════
- Formato: [Keyword] [Ciudad] [diferenciador corto] — CarolTemp
- Keyword para fugas: "detección fugas" o "buscar fuga"
- NUNCA el teléfono, NUNCA superes 58 chars
- BUENO: "Detección fugas Novelda sin obras — CarolTemp" (46 chars)
- MALO: "Detección de fugas en Novelda — CarolTemp | 611 165 129"

════ REGLAS META_DESC ════
- 140-155 caracteres EXACTOS (cuenta antes de escribir)
- Incluye: qué hacemos + ciudad + causa real de fugas en esa ciudad + CTA sin teléfono
- BUENO (151 chars): "Detectamos fugas en Novelda con geófono y cámara sin romper paredes. El agua muy dura destruye juntas y provoca fugas invisibles. Pide presupuesto gratis."
- MALO: genérico, con teléfono, más de 155 chars

════ DIFERENCIACIÓN POR CIUDAD ════
{$ciudad} tiene una causa raíz CONCRETA de fugas — úsala en meta_desc, hero_sub y contenido_intro:
- Petrer / Novelda → agua >600 mg/L cal: juntas de goma fundidas, fugas en calefacción, incrustaciones en válvulas
- Elda → tuberías de hierro de los 60-70: corrosión interna, fugas por picaduras en paredes
- Pinoso / Monóvar → instalaciones rurales: fugas en tuberías enterradas de fincas, pozos con presión irregular
- Sax → naves industriales + viviendas antiguas: fugas en instalaciones mixtas
- Monforte del Cid → segunda residencia: tuberías que se dilatan/contraen por meses sin uso
- Salinas / Aspe → casas unifamiliares años 80-90: juntas y racores envejecidos

CAROLTEMP PARA FUGAS:
- Geófono acústico profesional: detecta el sonido de la fuga sin abrir paredes
- Cámara de inspección endoscópica: visualiza el interior de tuberías y bajantes
- Detectan Y reparan en la misma visita: no hace falta llamar a otra empresa
- Presupuesto sin sorpresas antes de empezar
- PROHIBIDO: camión cuba, fosas sépticas, pocería, climatización
- NUNCA inventes estadísticas ni porcentajes, NUNCA escribas "Vinalopó"

DEVUELVE SOLO ESTE JSON VÁLIDO:
{
  "meta_title": "ver reglas arriba — keyword primero, sin teléfono, máx 58 chars",
  "meta_desc": "ver reglas arriba — 140-155 chars, sin teléfono, causa real de {$ciudad}",
  "hero_titulo": "Detección de fugas en {$ciudad}<br><span class=\"hl\">sin romper paredes.</span>",
  "hero_sub": "frase de 10-15 palabras con la causa CONCRETA de fugas en {$ciudad} — NO genérica",
  "contenido_intro": "<p>2 frases sobre POR QUÉ hay fugas en {$ciudad} — causa raíz real de esa ciudad — y cómo CarolTemp las localiza.</p>",
  "servicios_lista": ["tipo fuga concreto para {$ciudad} 1", "tipo 2", "tipo 3", "tipo 4", "tipo 5", "tipo 6"],
  "problemas_zona": [
    {"titulo": "causa real y concreta de fuga en {$ciudad}", "texto": "explicación de por qué ocurre en esta ciudad, 1-2 frases directas"},
    {"titulo": "segunda causa real", "texto": "explicación"},
    {"titulo": "tercera causa real", "texto": "explicación"}
  ],
  "faq": [
    {"pregunta": "pregunta real de alguien con fuga en {$ciudad} ahora mismo", "respuesta": "respuesta directa, honesta, sin frases vacías"},
    {"pregunta": "segunda pregunta específica de {$ciudad}", "respuesta": "respuesta"},
    {"pregunta": "tercera pregunta", "respuesta": "respuesta"},
    {"pregunta": "cuarta pregunta", "respuesta": "respuesta"}
  ]
}

CRÍTICO JSON: comillas dobles en claves y valores. Comillas simples dentro de valores si hace falta. Sin comas finales.
SYS;

    $user_fugas  = "Ciudad: {$ciudad} (CP: {$ciudad_cp}, slug: {$ciudad_slug})\n\n";
    $user_fugas .= "DATOS ESPECÍFICOS DE {$ciudad}:\n{$perfil_ciudad}\n\n";
    $user_fugas .= "Genera la página de detección de fugas para {$ciudad}. ";
    $user_fugas .= "Los problemas_zona deben describir causas REALES de fugas en {$ciudad} usando los datos de arriba. ";
    $user_fugas .= "Las FAQ deben ser preguntas que haría alguien con una fuga en {$ciudad} — NO copies preguntas de otras ciudades.";

    $payload_fugas = [
      'model'      => ANTHROPIC_MODEL,
      'max_tokens' => 3000,
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
    $ciudad_lat_f = $ciudades[$ciudad]['lat'] ?? '38.4766';
    $ciudad_lng_f = $ciudades[$ciudad]['lng'] ?? '-0.7952';
    $php_fugas = generar_php_servicio($data_fugas, $tipo_cfg_fugas, 'busqueda_fugas', $ciudad, $ciudad_slug, $ciudad_cp, $otras_ciudades, 2, $ciudad_lat_f, $ciudad_lng_f);

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
Eres un redactor SEO local especializado en fontanería. Generas contenido para CarolTemp en Alicante interior.

════ REGLAS META_TITLE ════
- Formato: [Keyword urgencias] [Ciudad] [diferenciador] — CarolTemp
- Keyword principal: "fontanero urgente" o "urgencias fontanería"
- NUNCA el teléfono, NUNCA superes 58 chars
- BUENO: "Fontanero urgente Elda 24h — CarolTemp" (38 chars)
- MALO: "Fontanero urgente en Elda — CarolTemp | 611 165 129"

════ REGLAS META_DESC ════
- 140-155 caracteres EXACTOS (cuenta antes de escribir)
- Incluye: urgencias + ciudad + avería típica de ESA ciudad + CTA sin teléfono
- BUENO (149 chars): "Fontanero urgente en Elda disponible hoy. Reparamos roturas en tuberías de hierro de edificios años 70. Actuamos rápido, presupuesto antes de empezar."
- MALO: teléfono incluido, genérico, más de 155 chars

════ DIFERENCIACIÓN POR CIUDAD — AVERÍA URGENTE TÍPICA ════
Usa la avería urgente MÁS PROBABLE en {$ciudad} según sus características reales:
- Elda → roturas en tuberías de hierro oxidadas de bloques años 60-70: averías repentinas sin aviso
- Petrer / Novelda → termos y calentadores que explotan o pierden por acumulación de cal; grifos que dejan de pasar agua
- Pinoso / Monóvar → grupos de presión averiados en fincas rurales; fugas en tuberías enterradas de campo
- Sax → roturas en viviendas antiguas del casco + incidencias en naves del polígono
- Monforte del Cid → llaves de paso agarrotadas en residencias secundarias tras meses sin uso; cañerías que revientan al reabrirse
- Salinas / Aspe → averías en casas unifamiliares de los 80-90: cisternas, llaves de paso y racores envejecidos

CAROLTEMP URGENCIAS:
- Atienden toda la comarca: Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Monforte, Salinas, Aspe
- Presupuesto sin sorpresas antes de empezar — el cliente aprueba el precio antes
- PROHIBIDO: camión cuba, fosas sépticas, pocería, climatización
- NUNCA inventes estadísticas ni tiempos de respuesta exactos, NUNCA escribas "Vinalopó"
- NUNCA uses: "expertos en", "somos tu fontanero de confianza", "calidad garantizada"

DEVUELVE SOLO ESTE JSON VÁLIDO:
{
  "meta_title": "ver reglas arriba — keyword primero, sin teléfono, máx 58 chars",
  "meta_desc": "ver reglas arriba — 140-155 chars, sin teléfono, avería típica de {$ciudad}",
  "hero_titulo": "Fontanero urgente en {$ciudad}<br><span class=\"hl\">precio antes de empezar.</span>",
  "hero_sub": "frase de 10-15 palabras con la avería urgente MÁS PROBABLE en {$ciudad} — NO genérica",
  "contenido_intro": "<p>2 frases sobre LAS AVERÍAS URGENTES más habituales en {$ciudad} usando los datos reales de esa ciudad.</p>",
  "servicios_lista": ["avería urgente concreta 1 para {$ciudad}", "urgencia 2", "urgencia 3", "urgencia 4", "urgencia 5", "urgencia 6"],
  "problemas_zona": [
    {"titulo": "avería urgente típica y concreta en {$ciudad}", "texto": "por qué ocurre en {$ciudad} — causa raíz real, 1-2 frases directas"},
    {"titulo": "segunda avería urgente concreta", "texto": "explicación"},
    {"titulo": "tercera avería urgente concreta", "texto": "explicación"}
  ],
  "faq": [
    {"pregunta": "pregunta real de alguien con urgencia en {$ciudad} ahora mismo", "respuesta": "respuesta directa y honesta sin frases vacías"},
    {"pregunta": "segunda pregunta específica de {$ciudad}", "respuesta": "respuesta"},
    {"pregunta": "tercera pregunta", "respuesta": "respuesta"},
    {"pregunta": "cuarta pregunta", "respuesta": "respuesta"}
  ]
}

CRÍTICO JSON: comillas dobles en claves y valores. Comillas simples dentro de valores si hace falta. Sin comas finales.
SYS;

    $user_urg  = "Ciudad: {$ciudad} (CP: {$ciudad_cp}, slug: {$ciudad_slug})\n\n";
    $user_urg .= "DATOS ESPECÍFICOS DE {$ciudad}:\n{$perfil_ciudad}\n\n";
    $user_urg .= "Genera la página de fontanero urgente para {$ciudad}. ";
    $user_urg .= "Los problemas_zona deben ser averías urgentes REALES en {$ciudad} usando los datos de arriba. ";
    $user_urg .= "Las FAQ deben ser preguntas de alguien con una urgencia en {$ciudad} ahora mismo — respuestas honestas, sin frases vacías.";

    $payload_urg = [
      'model'      => ANTHROPIC_MODEL,
      'max_tokens' => 3000,
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
    $ciudad_lat_u = $ciudades[$ciudad]['lat'] ?? '38.4766';
    $ciudad_lng_u = $ciudades[$ciudad]['lng'] ?? '-0.7952';
    $php_urg = generar_php_servicio($data_urg, $tipo_cfg_urg, 'urgencias', $ciudad, $ciudad_slug, $ciudad_cp, $otras_ciudades, 2, $ciudad_lat_u, $ciudad_lng_u);

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
Eres un redactor SEO local especializado en fontanería. Generas contenido para CarolTemp en Alicante interior.

════ REGLAS META_TITLE — CRÍTICO ════
- Formato OBLIGATORIO: [Keyword principal] [Ciudad] [diferenciador corto] — CarolTemp
- La keyword va PRIMERO, antes que ciudad y marca
- NUNCA pongas el teléfono (611 165 129) en el meta_title
- NUNCA superes 58 caracteres — cuenta antes de escribir
- BUENO: "Desatascos urgentes Novelda — CarolTemp" (39 chars)
- MALO: "Desatascos Novelda — CarolTemp | 611 165 129" (demasiado largo, teléfono inútil)

════ REGLAS META_DESC — CRÍTICO ════
- EXACTAMENTE 140-155 caracteres — cuenta los chars antes de escribir y ajusta
- Formato: [Servicio] en [Ciudad]. [Diferenciador REAL y concreto de esa ciudad, no genérico]. [CTA activo sin teléfono].
- NUNCA incluyas el teléfono (no es clickable en Google y ocupa chars útiles)
- NUNCA superes 155 chars — Google lo corta y se pierde el CTA
- BUENO (147 chars): "Desatascos en Novelda para fregaderos, bajantes y comunidades. La cal del agua obstruye las tuberías más rápido. Servicio el mismo día. Pide cita."
- MALO (165 chars, genérico, con tel): "Desatascamos tuberías en Novelda hoy mismo: viviendas, naves de mármol, fincas. Cámara de inspección incluida. Presupuesto claro antes de empezar. Llama 611 165 129."

════ DIFERENCIACIÓN POR CIUDAD — OBLIGATORIO ════
Cada ciudad tiene una causa raíz DISTINTA. Úsala en meta_desc, hero_sub, contenido_intro y problemas_zona:
- Petrer / Novelda → agua muy dura (>500 mg/L cal): tuberías obstruidas, desatascos más frecuentes, juntas destruidas
- Elda → edificios bloques años 60-70 con bajantes de hierro: atascos recurrentes en columnas verticales
- Sax → mezcla urbano + polígono industrial: atascos en viviendas antiguas y naves con grasas/residuos
- Pinoso / Monóvar → zona rural: fosas de decantación, arquetas enterradas, tuberías de campo sin mantenimiento
- Monforte del Cid → urbanizaciones segunda residencia: bajantes que se secan y agrietan por meses sin uso
- Salinas / Aspe → casas unifamiliares años 80-90: desagües sin sifón de agua, atascos en el primer codo
- PROHIBIDO copiar la misma estructura cambiando solo el nombre de ciudad

SOBRE CAROLTEMP:
- Zona: Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Monforte del Cid, Salinas, Aspe
- Diferenciadores REALES: presupuesto gratuito sin compromiso, geófono+cámara para fugas, instaladores certificados Nubeco
- PROHIBIDO: camión cuba, fosas sépticas, pocería, climatización, aires acondicionados
- NUNCA inventes estadísticas, años de experiencia, número de clientes ni porcentajes
- NUNCA uses: "expertos en", "somos tu fontanero de confianza", "calidad y profesionalidad", "Vinalopó"

DEVUELVE SOLO ESTE JSON VÁLIDO:
{
  "meta_title": "ver reglas arriba — keyword primero, sin teléfono, máx 58 chars",
  "meta_desc": "ver reglas arriba — 140-155 chars exactos, sin teléfono, diferenciador real de {$ciudad}",
  "hero_titulo": "[Servicio] en {$ciudad}<br><span class=\"hl\">gancho específico y concreto de {$ciudad}.</span>",
  "hero_sub": "frase de 10-15 palabras con problema o característica REAL de {$ciudad} — NO genérica",
  "contenido_intro": "<p>2 frases sobre el problema concreto de este servicio en {$ciudad} usando los datos reales de esa ciudad.</p>",
  "servicios_lista": ["servicio concreto 1 para {$ciudad}", "servicio 2", "servicio 3", "servicio 4", "servicio 5", "servicio 6"],
  "problemas_zona": [
    {"titulo": "problema real y concreto en {$ciudad}", "texto": "por qué ocurre en {$ciudad} — causa raíz, 1-2 frases directas sin frases vacías"},
    {"titulo": "segundo problema real", "texto": "explicación concreta"},
    {"titulo": "tercer problema real", "texto": "explicación concreta"}
  ],
  "faq": [
    {"pregunta": "pregunta real y concreta de alguien en {$ciudad}", "respuesta": "respuesta directa, honesta, sin frases vacías ni genéricas"},
    {"pregunta": "segunda pregunta específica de {$ciudad}", "respuesta": "respuesta directa"},
    {"pregunta": "tercera pregunta", "respuesta": "respuesta"},
    {"pregunta": "cuarta pregunta", "respuesta": "respuesta"}
  ]
}

CRÍTICO JSON: comillas dobles en claves y valores. Comillas simples dentro de valores si hace falta. Sin comas finales.
SYS;

  // ── Instrucciones por tipo de servicio ────────────────────────────
  $instrucciones = [
    'fugas'      => "Tipo: DETECCIÓN DE FUGAS. Keyword: 'detección fugas {$ciudad}' o 'buscar fuga {$ciudad}'.\nmeta_title empieza por: 'Detección fugas {$ciudad}' + diferenciador corto (sin romper / geófono / sin obras).\nhero_titulo gancho: '...sin romper paredes.' o '...sin obras innecesarias.'\nproblemas_zona: 3 causas REALES de fugas en {$ciudad} usando los datos de arriba — NO pongas 'Problema típico 1'.\nFAQ: precio, cómo funciona el geófono, si reparan en la misma visita, tiempo de respuesta — respuestas honestas.",
    'desatascos' => "Tipo: DESATASCOS. Keyword: 'desatascos {$ciudad}' o 'desatascar urgente {$ciudad}'.\nmeta_title empieza por: 'Desatascos {$ciudad}' + diferenciador (urgente / hoy mismo / cal).\nhero_titulo gancho: '...hoy mismo.' o '...sin esperas.' o algo específico de {$ciudad}.\nproblemas_zona: 3 causas REALES de atascos en {$ciudad} según sus datos (tipo edificios, agua dura, zona rural, etc.).\nFAQ: precio desatasco, qué incluye, si tienen urgencias, cómo evitar atascos futuros en {$ciudad} — honesto y concreto.",
    'fontanero'  => "Tipo: FONTANERO GENERAL. Keyword: 'fontanero {$ciudad}' o 'fontanero urgente {$ciudad}'.\nmeta_title empieza por: 'Fontanero {$ciudad}' + diferenciador (urgencias / presupuesto gratis / 24h).\nhero_titulo gancho: '...presupuesto sin sorpresas.' o '...sin sorpresas.' o específico de {$ciudad}.\nproblemas_zona: 3 situaciones REALES de {$ciudad} en las que la gente llama al fontanero — usando los datos de la ciudad.\nFAQ: precio visita, qué tipos de avería hacen, urgencias, garantía del trabajo — respuestas concretas.",
  ];

  $perfil_ciudad = $ciudad_perfiles[$ciudad] ?? 'Municipio de la comarca interior de Alicante.';
  $user_msg  = "Ciudad: {$ciudad} (CP: {$ciudad_cp}, slug: {$ciudad_slug})\n\n";
  $user_msg .= "DATOS ESPECÍFICOS DE {$ciudad}:\n{$perfil_ciudad}\n\n";
  $user_msg .= $instrucciones[$tipo] ?? "Servicio: {$servicio_nombre} en {$ciudad}.";
  $user_msg .= "\n\nIMPORTANTE: usa los datos de {$ciudad} de arriba para diferenciar el contenido. No copies texto de otras ciudades.";

  // ── Llamada a Claude (prefill { para garantizar JSON puro) ────────
  $payload = [
    'model'      => ANTHROPIC_MODEL,
    'max_tokens' => 3000,
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

  $ciudad_lat_g = $ciudades[$ciudad]['lat'] ?? '38.4766';
  $ciudad_lng_g = $ciudades[$ciudad]['lng'] ?? '-0.7952';
  $php_contenido = generar_php_servicio($data, $tipo_cfg, $tipo, $ciudad, $ciudad_slug, $ciudad_cp, $otras_ciudades, $silo_depth, $ciudad_lat_g, $ciudad_lng_g);

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
function generar_php_servicio($data, $tipo_cfg, $tipo, $ciudad, $ciudad_slug, $ciudad_cp, $otras_ciudades, $depth = 1, $lat = '38.4766', $lng = '-0.7952') {
  $servicio_nombre = $tipo_cfg['nombre'];
  $meta_title      = $data['meta_title']      ?? "{$servicio_nombre} en {$ciudad} — CarolTemp";
  $meta_desc       = $data['meta_desc']       ?? "Servicio de {$servicio_nombre} en {$ciudad}. presupuesto sin sorpresas. Llama al 611 165 129.";
  $hero_titulo     = $data['hero_titulo']     ?? "{$servicio_nombre} en {$ciudad}<br><span class=\"hl\">precio sin sorpresas.</span>";
  $hero_sub        = $data['hero_sub']        ?? "Atendemos en {$ciudad}. Presupuesto sin sorpresas antes de empezar.";
  $contenido_intro = $data['contenido_intro'] ?? "<p>Servicio de {$servicio_nombre} en {$ciudad}.</p>";
  $servicios_lista = $data['servicios_lista'] ?? [];
  $problemas_zona  = $data['problemas_zona']  ?? [];
  $faq             = $data['faq']             ?? [];

  // URL correcta: /fontanero/{ciudad_slug}/{tipo}
  $meta_url = "https://caroltemp.com/fontanero/{$ciudad_slug}/{$tipo}";
  $back     = str_repeat('../', $depth);
  $e        = fn($v) => var_export($v, true);

  // ── Checklist de servicios ────────────────────────────────────────
  $svg_chk  = '<svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
  $svc_html = '';
  foreach ($servicios_lista as $svc) {
    $s = htmlspecialchars($svc, ENT_QUOTES, 'UTF-8');
    $svc_html .= "          <li><span class=\"chk-ico\">{$svg_chk}</span>{$s}</li>\n";
  }

  // ── Tarjetas de problemas ─────────────────────────────────────────
  $prob_html = '';
  foreach ($problemas_zona as $prob) {
    $t  = htmlspecialchars($prob['titulo'] ?? '', ENT_QUOTES, 'UTF-8');
    $tx = htmlspecialchars($prob['texto']  ?? '', ENT_QUOTES, 'UTF-8');
    $prob_html .= "      <div class=\"zona-sc\">\n";
    $prob_html .= "        <h3>{$t}</h3>\n";
    $prob_html .= "        <p>{$tx}</p>\n";
    $prob_html .= "      </div>\n";
  }

  // ── FAQ ───────────────────────────────────────────────────────────
  $svg_faq  = '<svg viewBox="0 0 10 10" fill="none"><path d="M5 1v8M1 5h8" stroke-width="1.5" stroke-linecap="round"/></svg>';
  $faq_html = '';
  $first    = true;
  foreach ($faq as $f) {
    $p    = htmlspecialchars($f['pregunta']  ?? '', ENT_QUOTES, 'UTF-8');
    $r    = htmlspecialchars($f['respuesta'] ?? '', ENT_QUOTES, 'UTF-8');
    $open = $first ? ' open' : '';
    $faq_html .= "      <div class=\"zona-fi{$open}\">\n";
    $faq_html .= "        <div class=\"zona-fiq\" onclick=\"togFaq(this)\"><span>{$p}</span><span class=\"zona-fiq-i\">{$svg_faq}</span></div>\n";
    $faq_html .= "        <div class=\"zona-fia\">{$r}</div>\n";
    $faq_html .= "      </div>\n";
    $first = false;
  }

  // ── Tags ciudades cercanas — URLs absolutas hardcodeadas ──────────
  $ztags = "      <a href=\"/fontanero/{$ciudad_slug}\" class=\"zona-ztag\" style=\"background:#1e3a5f;color:#fff\">&#8592; Todos los servicios en {$ciudad}</a>\n";
  foreach ($otras_ciudades as $otra) {
    $n = htmlspecialchars($otra['nombre'], ENT_QUOTES, 'UTF-8');
    $s = htmlspecialchars($otra['slug'],   ENT_QUOTES, 'UTF-8');
    $ztags .= "      <a href=\"/fontanero/{$s}/{$tipo}\" class=\"zona-ztag\">{$n}</a>\n";
  }

  $hero_titulo_raw = $hero_titulo;
  $hero_sub_h      = htmlspecialchars($hero_sub, ENT_QUOTES, 'UTF-8');

  // ── PHP header ────────────────────────────────────────────────────
  $php  = "<?php\n";
  $php .= "/**\n * {$servicio_nombre} en {$ciudad}\n * Generado por Agente de P&aacute;ginas — CarolTemp\n */\n";
  $php .= "\$meta_title  = {$e($meta_title)};\n";
  $php .= "\$meta_desc   = {$e($meta_desc)};\n";
  $php .= "\$meta_url    = {$e($meta_url)};\n";
  $php .= "\$schema_type = 'local';\n";
  $php .= "\$page_css    = 'zona';\n";
  $php .= "\$page_js     = 'zona';\n";
  $php .= "include '{$back}includes/head.php';\n";
  $php .= "?>\n\n";

  // ── Hero ──────────────────────────────────────────────────────────
  $php .= "<section class=\"hz-dark\">\n";
  $php .= "  <div class=\"hz-dark-bg\"></div>\n";
  $php .= "  <div class=\"hz-dark-glow\"></div>\n";
  $php .= "  <div class=\"hz-dark-con\">\n";
  $php .= "    <div class=\"hz-dark-tag\"><span class=\"hz-dark-dot\"></span>{$servicio_nombre} &middot; {$ciudad} &middot; CP {$ciudad_cp}</div>\n";
  $php .= "    <h1>{$hero_titulo_raw}</h1>\n";
  $php .= "    <p class=\"hz-dark-sub\">{$hero_sub_h}</p>\n";
  $php .= "    <div class=\"hz-dark-btns\">\n";
  $php .= "      <a href=\"tel:+34611165129\" class=\"btn-hz-w\">&#128222; 611 165 129</a>\n";
  $php .= "      <a href=\"/contacto\" class=\"btn-hz-g\">Solicitar presupuesto</a>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n\n";

  // ── Strip diferenciadores ─────────────────────────────────────────
  $php .= "<div class=\"dif-strip\">\n";
  $php .= "  <div class=\"dif-strip-in\">\n";
  $php .= "    <div class=\"dif-item\"><span class=\"dif-val\">&#128176; precio sin sorpresas</span><span class=\"dif-lbl\">Antes de empezar</span></div>\n";
  $php .= "    <div class=\"dif-item\"><span class=\"dif-val\">&#128269; Ge&oacute;fono + c&aacute;mara</span><span class=\"dif-lbl\">Sin obras innecesarias</span></div>\n";
  $php .= "    <div class=\"dif-item\"><span class=\"dif-val\">&#9989; Nubeco oficial</span><span class=\"dif-lbl\">Instalador certificado</span></div>\n";
  $php .= "    <div class=\"dif-item\"><span class=\"dif-val\">&#128205; {$ciudad}</span><span class=\"dif-lbl\">Atenci&oacute;n local</span></div>\n";
  $php .= "  </div>\n";
  $php .= "</div>\n\n";

  // ── Intro + checklist + tarjeta contacto ─────────────────────────
  $php .= "<section class=\"zona-sec\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <div class=\"zona-tcol\">\n";
  $php .= "      <div>\n";
  $php .= "        <p class=\"zona-lbl\">{$servicio_nombre} en {$ciudad}</p>\n";
  $php .= "        <h2>{$servicio_nombre} en <span class=\"hl\">{$ciudad}</span></h2>\n";
  $php .= "        <div class=\"zona-prose\">{$contenido_intro}</div>\n";
  $php .= "        <ul class=\"zona-chk\">\n{$svc_html}        </ul>\n";
  $php .= "      </div>\n";
  $php .= "      <div>\n";
  $php .= "        <div class=\"zona-icard\">\n";
  $php .= "          <div class=\"zona-icard-h\"><strong>CarolTemp &middot; {$ciudad}</strong><span>{$servicio_nombre}</span></div>\n";
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">Zona</span><span class=\"zona-ir-v\">{$ciudad} &middot; CP {$ciudad_cp}</span></div>\n";
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">Tel&eacute;fono</span><span class=\"zona-ir-v\"><a href=\"tel:+34611165129\">611 165 129</a></span></div>\n";
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">WhatsApp</span><span class=\"zona-ir-v\"><a href=\"https://wa.me/34611165129\">Escribir ahora &rarr;</a></span></div>\n";
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">Todos los servicios</span><span class=\"zona-ir-v\"><a href=\"/fontanero/{$ciudad_slug}\">Fontaner&iacute;a en {$ciudad} &rarr;</a></span></div>\n";
  $php .= "          <a href=\"tel:+34611165129\" class=\"zona-icard-btn\">&#128222; Llamar ahora</a>\n";
  $php .= "        </div>\n";
  $php .= "      </div>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n\n";

  // ── Problemas en la zona ──────────────────────────────────────────
  if ($prob_html) {
    $php .= "<section class=\"zona-sec zona-sec-gray\">\n";
    $php .= "  <div class=\"cta-dark-con\">\n";
    $php .= "    <p class=\"zona-lbl\">Casos habituales en {$ciudad}</p>\n";
    $php .= "    <h2>Por qu&eacute; nos llaman por {$servicio_nombre} <span class=\"hl\">en {$ciudad}</span></h2>\n";
    $php .= "    <div class=\"zona-svc\">\n{$prob_html}    </div>\n";
    $php .= "  </div>\n";
    $php .= "</section>\n\n";
  }

  // ── FAQ ───────────────────────────────────────────────────────────
  if ($faq_html) {
    $php .= "<section class=\"zona-sec\">\n";
    $php .= "  <div class=\"cta-dark-con\">\n";
    $php .= "    <p class=\"zona-lbl\">Preguntas frecuentes</p>\n";
    $php .= "    <h2>{$servicio_nombre} en {$ciudad} &mdash; <span class=\"hl\">dudas habituales</span></h2>\n";
    $php .= "    <div class=\"zona-faq\" style=\"margin-top:2rem\">\n{$faq_html}    </div>\n";
    $php .= "  </div>\n";
    $php .= "</section>\n\n";
  }

  // ── MARCADOR: fin de la zona editable en TinyMCE ─────────────────
  $php .= "<!-- /editable -->\n";

  // ── SECCIÓN DINÁMICA: proyectos + artículos ────────────────────────
  $ciudad_q = addslashes($ciudad);
  $php .= "<?php\n";
  $php .= "\$_proy = [];\n";
  $php .= "try {\n";
  $php .= "  \$_ps = \$pdo->prepare('SELECT titulo, slug, descripcion, servicio, imagen FROM proyectos WHERE publicado=1 AND zona LIKE ? ORDER BY fecha DESC LIMIT 3');\n";
  $php .= "  \$_ps->execute(['%{$ciudad_q}%']);\n";
  $php .= "  \$_proy = \$_ps->fetchAll(PDO::FETCH_ASSOC);\n";
  $php .= "} catch (\\Throwable \$_e) {}\n";
  $php .= "\$_arts = [];\n";
  $php .= "try {\n";
  $php .= "  \$_as = \$pdo->prepare('SELECT titulo, slug, extracto, categoria, imagen FROM articulos WHERE publicado=1 AND (zona LIKE ? OR categoria LIKE ?) ORDER BY fecha DESC LIMIT 3');\n";
  $php .= "  \$_as->execute(['%{$ciudad_q}%', '%fontan%']);\n";
  $php .= "  \$_arts = \$_as->fetchAll(PDO::FETCH_ASSOC);\n";
  $php .= "  if (empty(\$_arts)) {\n";
  $php .= "    \$_as2 = \$pdo->query('SELECT titulo, slug, extracto, categoria, imagen FROM articulos WHERE publicado=1 ORDER BY fecha DESC LIMIT 3');\n";
  $php .= "    \$_arts = \$_as2 ? \$_as2->fetchAll(PDO::FETCH_ASSOC) : [];\n";
  $php .= "  }\n";
  $php .= "} catch (\\Throwable \$_e) {}\n";
  $php .= "if (!empty(\$_proy)): ?>\n";
  $php .= "<section class=\"zona-sec\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <p class=\"zona-lbl\">Trabajos realizados</p>\n";
  $php .= "    <h2>Proyectos de {$servicio_nombre} <span class=\"hl\">en {$ciudad}</span></h2>\n";
  $php .= "    <div class=\"zona-svc\" style=\"margin-top:2rem\">\n";
  $php .= "      <?php foreach (\$_proy as \$_p): ?>\n";
  $php .= "      <a href=\"/proyectos/<?php echo urlencode(\$_p['slug']); ?>\" class=\"zona-sc\">\n";
  $php .= "        <?php if (!empty(\$_p['imagen'])): ?><img src=\"<?php echo htmlspecialchars(\$_p['imagen']); ?>\" alt=\"<?php echo htmlspecialchars(\$_p['titulo']); ?>\" loading=\"lazy\" style=\"width:100%;height:160px;object-fit:cover;border-radius:8px;margin-bottom:.75rem\"><?php endif; ?>\n";
  $php .= "        <?php if (\$_p['servicio']): ?><span class=\"zona-lbl\" style=\"font-size:11px\"><?php echo htmlspecialchars(\$_p['servicio']); ?></span><?php endif; ?>\n";
  $php .= "        <h3><?php echo htmlspecialchars(\$_p['titulo']); ?></h3>\n";
  $php .= "        <p><?php echo htmlspecialchars(mb_substr(\$_p['descripcion'] ?? '', 0, 100)); ?>...</p>\n";
  $php .= "        <span class=\"zona-sc-a\">Ver proyecto &rarr;</span>\n";
  $php .= "      </a>\n";
  $php .= "      <?php endforeach; ?>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n";
  $php .= "<?php endif; ?>\n";
  $php .= "<?php if (!empty(\$_arts)): ?>\n";
  $php .= "<section class=\"zona-sec zona-sec-gray\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <p class=\"zona-lbl\">Consejos &uacute;tiles</p>\n";
  $php .= "    <h2>Art&iacute;culos sobre <span class=\"hl\">{$servicio_nombre}</span></h2>\n";
  $php .= "    <div class=\"zona-svc\" style=\"margin-top:2rem\">\n";
  $php .= "      <?php foreach (\$_arts as \$_a): ?>\n";
  $php .= "      <a href=\"/noticias/<?php echo urlencode(\$_a['slug']); ?>\" class=\"zona-sc\">\n";
  $php .= "        <?php if (!empty(\$_a['imagen'])): ?><img src=\"<?php echo htmlspecialchars(\$_a['imagen']); ?>\" alt=\"<?php echo htmlspecialchars(\$_a['titulo']); ?>\" loading=\"lazy\" style=\"width:100%;height:160px;object-fit:cover;border-radius:8px;margin-bottom:.75rem\"><?php endif; ?>\n";
  $php .= "        <?php if (\$_a['categoria']): ?><span class=\"zona-lbl\" style=\"font-size:11px\"><?php echo htmlspecialchars(\$_a['categoria']); ?></span><?php endif; ?>\n";
  $php .= "        <h3><?php echo htmlspecialchars(\$_a['titulo']); ?></h3>\n";
  $php .= "        <p><?php echo htmlspecialchars(mb_substr(\$_a['extracto'] ?? '', 0, 100)); ?>...</p>\n";
  $php .= "        <span class=\"zona-sc-a\">Leer art&iacute;culo &rarr;</span>\n";
  $php .= "      </a>\n";
  $php .= "      <?php endforeach; ?>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n";
  $php .= "<?php endif; ?>\n";

  // ── Mapa + ciudades + CTA — hardcodeados, sin PHP vars ────────────
  $php .= "<section class=\"zona-sec\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <p class=\"zona-lbl\">Zona de cobertura</p>\n";
  $php .= "    <h2>{$servicio_nombre} <span class=\"hl\">en {$ciudad}</span></h2>\n";
  $php .= "    <p style=\"margin-bottom:1.5rem;color:#576574\">Atendemos toda la localidad de {$ciudad} (CP {$ciudad_cp}) y municipios lim&iacute;trofes.</p>\n";
  $php .= "    <div style=\"border-radius:12px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.12)\">\n";
  $php .= "      <iframe src=\"https://maps.google.com/maps?q={$lat},{$lng}&z=14&output=embed\" width=\"100%\" height=\"380\" style=\"border:0;display:block\" allowfullscreen loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\" title=\"{$servicio_nombre} en {$ciudad}\"></iframe>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n";
  $php .= "<section class=\"zona-sec zona-sec-gray\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <p class=\"zona-lbl\">Mismo servicio en otras zonas</p>\n";
  $php .= "    <h2>Tambi&eacute;n hacemos {$servicio_nombre} <span class=\"hl\">en otros municipios</span></h2>\n";
  $php .= "    <div class=\"zona-ztags\">\n{$ztags}    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n";
  $php .= "<section class=\"cta-dark\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <h2>&iquest;Necesitas {$servicio_nombre} <span>en {$ciudad}?</span></h2>\n";
  $php .= "    <p>Ll&aacute;menos o escr&iacute;benos. Te atendemos hoy.</p>\n";
  $php .= "    <div class=\"cta-dark-btns\">\n";
  $php .= "      <a href=\"tel:+34611165129\" class=\"btn-hz-w\">&#128222; Llamar ahora</a>\n";
  $php .= "      <a href=\"https://wa.me/34611165129\" target=\"_blank\" rel=\"noopener\" class=\"btn-hz-g\">&#128172; WhatsApp</a>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n";
  $php .= "<?php include '{$back}includes/footer.php'; ?>\n";

  return $php;
}

// ═════════════════════════════════════════════════════════════════════
// Genera el archivo PHP hub ciudad (fontanero/{slug}.php)
// Estructura silo: cubre todos los servicios, enlaza a sub-páginas
// ═════════════════════════════════════════════════════════════════════
function generar_php_hub_ciudad($data, $ciudad, $ciudad_slug, $ciudad_cp, $otras_ciudades, $lat = '38.4766', $lng = '-0.7952') {
  $meta_title = $data['meta_title'] ?? "Fontanería en {$ciudad} — CarolTemp";
  $meta_desc  = $data['meta_desc']  ?? "Servicios de fontanería en {$ciudad}. Urgencias, fugas, desatascos e instalaciones.";
  $hero_sub   = htmlspecialchars($data['hero_sub']  ?? "Trabajamos en {$ciudad} realizando todo tipo de servicios de fontanería.", ENT_QUOTES, 'UTF-8');
  $intro_p1   = htmlspecialchars($data['intro_p1']  ?? "Trabajamos en toda la localidad de {$ciudad}, cubriendo tanto viviendas como comunidades.", ENT_QUOTES, 'UTF-8');
  $intro_p2   = htmlspecialchars($data['intro_p2']  ?? "Presupuesto gratuito sin compromiso. precio sin sorpresas antes de empezar.", ENT_QUOTES, 'UTF-8');
  $checklist  = $data['checklist']  ?? ["Urgencias de fontanería", "Detección de fugas", "Desatascos", "Termos y descalcificadores", "Reformas de baño"];
  $faq        = $data['faq']        ?? [];

  $meta_url = "https://caroltemp.com/fontanero/{$ciudad_slug}";
  $e        = fn($v) => var_export($v, true);
  $ch       = htmlspecialchars($ciudad,      ENT_QUOTES, 'UTF-8');
  $cp_h     = htmlspecialchars($ciudad_cp,   ENT_QUOTES, 'UTF-8');
  $slug_h   = htmlspecialchars($ciudad_slug, ENT_QUOTES, 'UTF-8');

  $svg_chk   = '<svg viewBox="0 0 10 10" fill="none" width="10" height="10"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
  $chk_items = '';
  foreach ($checklist as $item) {
    $chk_items .= "          <li><span class=\"chk-ico\">{$svg_chk}</span>" . htmlspecialchars($item, ENT_QUOTES, 'UTF-8') . "</li>\n";
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

  // Ciudades cercanas — hardcoded absolute URLs (sin PHP vars)
  $ztags = '';
  foreach ($otras_ciudades as $otra) {
    $n_h = htmlspecialchars($otra['nombre'], ENT_QUOTES, 'UTF-8');
    $s_h = htmlspecialchars($otra['slug'],   ENT_QUOTES, 'UTF-8');
    $ztags .= "      <a href=\"/fontanero/{$s_h}\" class=\"zona-ztag\">{$n_h}</a>\n";
  }

  // ── PHP header ────────────────────────────────────────────────────
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

  // ── HERO — sin PHP vars, todo hardcodeado ─────────────────────────
  $php .= "<!-- HERO -->\n";
  $php .= "<section class=\"hz-dark\">\n";
  $php .= "  <div class=\"hz-dark-bg\"></div>\n";
  $php .= "  <div class=\"hz-dark-glow\"></div>\n";
  $php .= "  <div class=\"hz-dark-con\">\n";
  $php .= "    <div class=\"hz-dark-tag\"><span class=\"hz-dark-dot\"></span>Fontaner&iacute;a en {$ch} &middot; CP {$cp_h}</div>\n";
  $php .= "    <h1>Fontaner&iacute;a en <span class=\"hl\">{$ch}.</span></h1>\n";
  $php .= "    <p class=\"hz-dark-sub\">{$hero_sub}</p>\n";
  $php .= "    <div class=\"hz-dark-btns\">\n";
  $php .= "      <a href=\"tel:+34611165129\" class=\"btn-hz-w\">&#128222; 611 165 129</a>\n";
  $php .= "      <a href=\"/contacto\" class=\"btn-hz-g\">Solicitar visita</a>\n";
  $php .= "    </div>\n";
  $php .= "    <div class=\"hero-dark-kpis\" style=\"margin-top:2rem\">\n";
  $php .= "      <div class=\"hero-dark-kpi\"><span class=\"hero-dark-kpi-val\">Nubeco</span><span class=\"hero-dark-kpi-lbl\">Instalador oficial en {$ch}</span></div>\n";
  $php .= "      <div class=\"hero-dark-kpi\"><span class=\"hero-dark-kpi-val\">100%</span><span class=\"hero-dark-kpi-lbl\">precio sin sorpresas siempre</span></div>\n";
  $php .= "      <div class=\"hero-dark-kpi\"><span class=\"hero-dark-kpi-val\">0&euro;</span><span class=\"hero-dark-kpi-lbl\">Sin adelantos con financiaci&oacute;n</span></div>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n\n";

  // ── STRIP ─────────────────────────────────────────────────────────
  $php .= "<!-- STRIP -->\n";
  $php .= "<div class=\"dif-strip\">\n";
  $php .= "  <div class=\"dif-strip-in\">\n";
  $php .= "    <div class=\"dif-item\"><span class=\"dif-val\">&#9889; Urgencias</span><span class=\"dif-lbl\">Atenci&oacute;n r&aacute;pida en {$ch}</span></div>\n";
  $php .= "    <div class=\"dif-item\"><span class=\"dif-val\">&#128269; Sin obras</span><span class=\"dif-lbl\">Ge&oacute;fono y c&aacute;mara</span></div>\n";
  $php .= "    <div class=\"dif-item\"><span class=\"dif-val\">&#128176; precio sin sorpresas</span><span class=\"dif-lbl\">Antes de empezar</span></div>\n";
  $php .= "    <div class=\"dif-item\"><span class=\"dif-val\">&#128205; Comarca</span><span class=\"dif-lbl\">Somos de aqu&iacute;</span></div>\n";
  $php .= "  </div>\n";
  $php .= "</div>\n\n";

  // ── TEXTO CENTRAL ─────────────────────────────────────────────────
  $php .= "<!-- TEXTO CENTRAL -->\n";
  $php .= "<section class=\"zona-sec\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <div class=\"zona-tcol\">\n";
  $php .= "      <div>\n";
  $php .= "        <p class=\"zona-lbl\">Fontaner&iacute;a en {$ch}</p>\n";
  $php .= "        <h2>Servicios de fontaner&iacute;a en <span class=\"hl\">{$ch}</span></h2>\n";
  $php .= "        <div class=\"zona-prose\">\n";
  $php .= "          <p>{$intro_p1}</p>\n";
  $php .= "          <p>{$intro_p2}</p>\n";
  $php .= "        </div>\n";
  $php .= "        <ul class=\"zona-chk\">\n{$chk_items}        </ul>\n";
  $php .= "      </div>\n";
  $php .= "      <div>\n";
  $php .= "        <div class=\"zona-icard\">\n";
  $php .= "          <div class=\"zona-icard-h\"><strong>CarolTemp &middot; {$ch}</strong><span>Fontaner&iacute;a residencial</span></div>\n";
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">Zona</span><span class=\"zona-ir-v\">{$ch} &middot; CP {$cp_h}</span></div>\n";
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">Tel&eacute;fono</span><span class=\"zona-ir-v\"><a href=\"tel:+34611165129\">611 165 129</a></span></div>\n";
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">WhatsApp</span><span class=\"zona-ir-v\"><a href=\"https://wa.me/34611165129\">Escribir ahora &rarr;</a></span></div>\n";
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">Horario</span><span class=\"zona-ir-v\">Lun&ndash;Vie 8&ndash;20h &middot; S&aacute;b 9&ndash;14h</span></div>\n";
  $php .= "          <a href=\"tel:+34611165129\" class=\"zona-icard-btn\">&#128222; Llamar ahora</a>\n";
  $php .= "        </div>\n";
  $php .= "      </div>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n\n";

  // ── SERVICIOS — URLs absolutas hardcodeadas ────────────────────────
  $php .= "<!-- SERVICIOS EN EL SILO -->\n";
  $php .= "<section class=\"zona-sec zona-sec-gray\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <p class=\"zona-lbl\">Servicios en {$ch}</p>\n";
  $php .= "    <h2>Todo lo que hacemos <span class=\"hl\">en {$ch}</span></h2>\n";
  $php .= "    <div class=\"zona-svc\">\n";
  $php .= "      <a href=\"/fontanero/{$slug_h}/urgencias\" class=\"zona-sc\"><span class=\"zona-sc-n\">01</span><h3>Fontanero urgente en {$ch}</h3><p>Roturas de tuber&iacute;as, grifos, cisternas y p&eacute;rdidas de agua con soluci&oacute;n r&aacute;pida y precio sin sorpresas.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "      <a href=\"/fontanero/{$slug_h}/busqueda_fugas\" class=\"zona-sc\"><span class=\"zona-sc-n\">02</span><h3>B&uacute;squeda de fugas en {$ch}</h3><p>Localizaci&oacute;n de fugas con ge&oacute;fono y c&aacute;mara sin romper innecesariamente.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "      <a href=\"/fontanero/{$slug_h}/desatascos\" class=\"zona-sc\"><span class=\"zona-sc-n\">03</span><h3>Desatascos en {$ch}</h3><p>Desatascos de fregaderos, bajantes y arquetas para recuperar el funcionamiento normal.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "      <a href=\"/servicios#termos\" class=\"zona-sc\"><span class=\"zona-sc-n\">04</span><h3>Termos el&eacute;ctricos en {$ch}</h3><p>Instalaci&oacute;n de termos el&eacute;ctricos con asesoramiento y puesta en marcha.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "      <a href=\"/servicios#descalcificadores\" class=\"zona-sc\"><span class=\"zona-sc-n\">05</span><h3>Descalcificadores en {$ch}</h3><p>Soluci&oacute;n para el agua dura con instalaci&oacute;n y mantenimiento de descalcificadores.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "      <a href=\"/servicios#reformas\" class=\"zona-sc\"><span class=\"zona-sc-n\">06</span><h3>Reformas de ba&ntilde;o en {$ch}</h3><p>Reformas completas o parciales con precio sin sorpresas antes de empezar.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n\n";

  // ── FAQ ───────────────────────────────────────────────────────────
  if ($faq_items) {
    $php .= "<!-- FAQ -->\n";
    $php .= "<section class=\"zona-sec\">\n";
    $php .= "  <div class=\"cta-dark-con\">\n";
    $php .= "    <p class=\"zona-lbl\">Preguntas frecuentes</p>\n";
    $php .= "    <h2>Fontaner&iacute;a en {$ch} &mdash; <span class=\"hl\">dudas habituales</span></h2>\n";
    $php .= "    <div class=\"zona-faq\" style=\"margin-top:2rem\">\n{$faq_items}    </div>\n";
    $php .= "  </div>\n";
    $php .= "</section>\n\n";
  }

  // ── MARCADOR: fin de la zona editable en TinyMCE ─────────────────
  $php .= "<!-- /editable -->\n";

  // ── SECCIÓN DINÁMICA: proyectos + artículos (PHP real, fuera de TinyMCE) ──
  $ciudad_q = addslashes($ciudad);
  $php .= "<?php\n";
  $php .= "\$_proy = [];\n";
  $php .= "try {\n";
  $php .= "  \$_ps = \$pdo->prepare('SELECT titulo, slug, descripcion, servicio, imagen FROM proyectos WHERE publicado=1 AND zona LIKE ? ORDER BY fecha DESC LIMIT 3');\n";
  $php .= "  \$_ps->execute(['%{$ciudad_q}%']);\n";
  $php .= "  \$_proy = \$_ps->fetchAll(PDO::FETCH_ASSOC);\n";
  $php .= "} catch (\\Throwable \$_e) {}\n";
  $php .= "\$_arts = [];\n";
  $php .= "try {\n";
  $php .= "  \$_as = \$pdo->prepare('SELECT titulo, slug, extracto, categoria, imagen FROM articulos WHERE publicado=1 AND (zona LIKE ? OR categoria LIKE ?) ORDER BY fecha DESC LIMIT 3');\n";
  $php .= "  \$_as->execute(['%{$ciudad_q}%', '%fontan%']);\n";
  $php .= "  \$_arts = \$_as->fetchAll(PDO::FETCH_ASSOC);\n";
  $php .= "  if (empty(\$_arts)) {\n";
  $php .= "    \$_as2 = \$pdo->query('SELECT titulo, slug, extracto, categoria, imagen FROM articulos WHERE publicado=1 ORDER BY fecha DESC LIMIT 3');\n";
  $php .= "    \$_arts = \$_as2 ? \$_as2->fetchAll(PDO::FETCH_ASSOC) : [];\n";
  $php .= "  }\n";
  $php .= "} catch (\\Throwable \$_e) {}\n";
  $php .= "if (!empty(\$_proy)): ?>\n";
  $php .= "<section class=\"zona-sec zona-sec-gray\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <p class=\"zona-lbl\">Trabajos realizados</p>\n";
  $php .= "    <h2>Proyectos de fontaner&iacute;a <span class=\"hl\">en {$ch}</span></h2>\n";
  $php .= "    <div class=\"zona-svc\" style=\"margin-top:2rem\">\n";
  $php .= "      <?php foreach (\$_proy as \$_p): ?>\n";
  $php .= "      <a href=\"/proyectos/<?php echo urlencode(\$_p['slug']); ?>\" class=\"zona-sc\">\n";
  $php .= "        <?php if (!empty(\$_p['imagen'])): ?><img src=\"<?php echo htmlspecialchars(\$_p['imagen']); ?>\" alt=\"<?php echo htmlspecialchars(\$_p['titulo']); ?>\" loading=\"lazy\" style=\"width:100%;height:160px;object-fit:cover;border-radius:8px;margin-bottom:.75rem\"><?php endif; ?>\n";
  $php .= "        <?php if (\$_p['servicio']): ?><span class=\"zona-lbl\" style=\"font-size:11px\"><?php echo htmlspecialchars(\$_p['servicio']); ?></span><?php endif; ?>\n";
  $php .= "        <h3><?php echo htmlspecialchars(\$_p['titulo']); ?></h3>\n";
  $php .= "        <p><?php echo htmlspecialchars(mb_substr(\$_p['descripcion'] ?? '', 0, 100)); ?>...</p>\n";
  $php .= "        <span class=\"zona-sc-a\">Ver proyecto &rarr;</span>\n";
  $php .= "      </a>\n";
  $php .= "      <?php endforeach; ?>\n";
  $php .= "    </div>\n";
  $php .= "    <div style=\"text-align:center;margin-top:1.5rem\"><a href=\"/proyectos/zona/" . urlencode($ciudad) . "\" class=\"btn-hz-g\" style=\"display:inline-flex\">Ver todos los proyectos en {$ch} &rarr;</a></div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n";
  $php .= "<?php endif; ?>\n";
  $php .= "<?php if (!empty(\$_arts)): ?>\n";
  $php .= "<section class=\"zona-sec\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <p class=\"zona-lbl\">Consejos &uacute;tiles</p>\n";
  $php .= "    <h2>Art&iacute;culos sobre <span class=\"hl\">fontaner&iacute;a</span></h2>\n";
  $php .= "    <div class=\"zona-svc\" style=\"margin-top:2rem\">\n";
  $php .= "      <?php foreach (\$_arts as \$_a): ?>\n";
  $php .= "      <a href=\"/noticias/<?php echo urlencode(\$_a['slug']); ?>\" class=\"zona-sc\">\n";
  $php .= "        <?php if (!empty(\$_a['imagen'])): ?><img src=\"<?php echo htmlspecialchars(\$_a['imagen']); ?>\" alt=\"<?php echo htmlspecialchars(\$_a['titulo']); ?>\" loading=\"lazy\" style=\"width:100%;height:160px;object-fit:cover;border-radius:8px;margin-bottom:.75rem\"><?php endif; ?>\n";
  $php .= "        <?php if (\$_a['categoria']): ?><span class=\"zona-lbl\" style=\"font-size:11px\"><?php echo htmlspecialchars(\$_a['categoria']); ?></span><?php endif; ?>\n";
  $php .= "        <h3><?php echo htmlspecialchars(\$_a['titulo']); ?></h3>\n";
  $php .= "        <p><?php echo htmlspecialchars(mb_substr(\$_a['extracto'] ?? '', 0, 100)); ?>...</p>\n";
  $php .= "        <span class=\"zona-sc-a\">Leer art&iacute;culo &rarr;</span>\n";
  $php .= "      </a>\n";
  $php .= "      <?php endforeach; ?>\n";
  $php .= "    </div>\n";
  $php .= "    <div style=\"text-align:center;margin-top:1.5rem\"><a href=\"/noticias\" class=\"btn-hz-g\" style=\"display:inline-flex\">Ver todos los art&iacute;culos &rarr;</a></div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n";
  $php .= "<?php endif; ?>\n";

  // ── MAPA — todo hardcodeado, sin PHP vars ─────────────────────────
  $php .= "<section class=\"zona-sec zona-sec-gray\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <p class=\"zona-lbl\">Zona de cobertura</p>\n";
  $php .= "    <h2>Fontaner&iacute;a a domicilio <span class=\"hl\">en {$ch}</span></h2>\n";
  $php .= "    <p style=\"margin-bottom:1.5rem;color:#576574\">Atendemos toda la localidad de {$ch} (CP {$cp_h}) y municipios lim&iacute;trofes. Desplazamiento incluido en el presupuesto.</p>\n";
  $php .= "    <div style=\"border-radius:12px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.12)\">\n";
  $php .= "      <iframe src=\"https://maps.google.com/maps?q={$lat},{$lng}&z=14&output=embed\" width=\"100%\" height=\"420\" style=\"border:0;display:block\" allowfullscreen loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\" title=\"Fontaner&iacute;a en {$ch}\"></iframe>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n";
  $php .= "<section class=\"zona-sec\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <p class=\"zona-lbl\">Otras zonas donde trabajamos</p>\n";
  $php .= "    <h2>Tambi&eacute;n trabajamos en <span class=\"hl\">zonas cercanas</span></h2>\n";
  $php .= "    <div class=\"zona-ztags\">\n{$ztags}    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n";
  $php .= "<section class=\"cta-dark\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <h2>&iquest;Necesitas fontaner&iacute;a <span>en {$ch}?</span></h2>\n";
  $php .= "    <p>Ll&aacute;menos o escr&iacute;benos y te atendemos hoy.</p>\n";
  $php .= "    <div class=\"cta-dark-btns\">\n";
  $php .= "      <a href=\"tel:+34611165129\" class=\"btn-hz-w\">&#128222; Llamar ahora</a>\n";
  $php .= "      <a href=\"https://wa.me/34611165129\" target=\"_blank\" rel=\"noopener\" class=\"btn-hz-g\">&#128172; WhatsApp</a>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n";
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
  $php .= "      <a href=\"tel:+34611165129\" class=\"btn-hz-w\">&#128222; 611 165 129</a>\n";
  $php .= "      <a href=\"<?php echo \$base_url; ?>contacto\" class=\"btn-hz-g\">Solicitar visita</a>\n";
  $php .= "    </div>\n";
  $php .= "    <div class=\"hero-dark-kpis\" style=\"margin-top:2rem\">\n";
  $php .= "      <div class=\"hero-dark-kpi\"><span class=\"hero-dark-kpi-val\">Nubeco</span><span class=\"hero-dark-kpi-lbl\">Instalador oficial en <?php echo \$zona_nombre; ?></span></div>\n";
  $php .= "      <div class=\"hero-dark-kpi\"><span class=\"hero-dark-kpi-val\">100%</span><span class=\"hero-dark-kpi-lbl\">precio sin sorpresas siempre</span></div>\n";
  $php .= "      <div class=\"hero-dark-kpi\"><span class=\"hero-dark-kpi-val\">0&euro;</span><span class=\"hero-dark-kpi-lbl\">Sin adelantos con financiaci&oacute;n</span></div>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n\n";

  $php .= "<!-- STRIP -->\n";
  $php .= "<div class=\"dif-strip\">\n";
  $php .= "  <div class=\"dif-strip-in\">\n";
  $php .= "    <div class=\"dif-item\"><span class=\"dif-val\">&#9889; Urgencias</span><span class=\"dif-lbl\">Atenci&oacute;n r&aacute;pida en <?php echo \$zona_nombre; ?></span></div>\n";
  $php .= "    <div class=\"dif-item\"><span class=\"dif-val\">&#128269; Sin obras</span><span class=\"dif-lbl\">Ge&oacute;fono y c&aacute;mara</span></div>\n";
  $php .= "    <div class=\"dif-item\"><span class=\"dif-val\">&#128176; precio sin sorpresas</span><span class=\"dif-lbl\">Antes de empezar</span></div>\n";
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
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">Tel&eacute;fono</span><span class=\"zona-ir-v\"><a href=\"tel:+34611165129\">611 165 129</a></span></div>\n";
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">WhatsApp</span><span class=\"zona-ir-v\"><a href=\"https://wa.me/34611165129\">Escribir ahora &rarr;</a></span></div>\n";
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">Horario</span><span class=\"zona-ir-v\">Lun&ndash;Vie 8&ndash;20h &middot; S&aacute;b 9&ndash;14h</span></div>\n";
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">Financiaci&oacute;n</span><span class=\"zona-ir-v\">Disponible para proyectos grandes</span></div>\n";
  $php .= "          <a href=\"tel:+34611165129\" class=\"zona-icard-btn\">&#128222; Llamar ahora</a>\n";
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
  $php .= "      <a href=\"<?php echo \$base_url; ?>fontanero/fontanero-<?php echo \$zona_slug; ?>\" class=\"zona-sc\"><span class=\"zona-sc-n\">01</span><h3>Reparaciones urgentes en <?php echo \$zona_nombre; ?></h3><p>Fugas de agua, grifos, cisternas y tuber&iacute;as con soluci&oacute;n r&aacute;pida y precio sin sorpresas.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "      <a href=\"<?php echo \$base_url; ?>fugas/deteccion-fugas-<?php echo \$zona_slug; ?>\" class=\"zona-sc\"><span class=\"zona-sc-n\">02</span><h3>Detecci&oacute;n de fugas en <?php echo \$zona_nombre; ?></h3><p>Localizaci&oacute;n de fugas con ge&oacute;fono y c&aacute;mara sin romper innecesariamente.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "      <a href=\"<?php echo \$base_url; ?>desatascos/desatascos-<?php echo \$zona_slug; ?>\" class=\"zona-sc\"><span class=\"zona-sc-n\">03</span><h3>Desatascos en <?php echo \$zona_nombre; ?></h3><p>Desatascos de fregaderos, bajantes y arquetas para recuperar el funcionamiento normal.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "      <a href=\"<?php echo \$base_url; ?>servicios#termos\" class=\"zona-sc\"><span class=\"zona-sc-n\">04</span><h3>Termos el&eacute;ctricos en <?php echo \$zona_nombre; ?></h3><p>Instalaci&oacute;n de termos el&eacute;ctricos con asesoramiento y puesta en marcha.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "      <a href=\"<?php echo \$base_url; ?>servicios#descalcificadores\" class=\"zona-sc\"><span class=\"zona-sc-n\">05</span><h3>Descalcificadores en <?php echo \$zona_nombre; ?></h3><p>Soluci&oacute;n para el agua dura con instalaci&oacute;n y mantenimiento de descalcificadores.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "      <a href=\"<?php echo \$base_url; ?>servicios#reformas\" class=\"zona-sc\"><span class=\"zona-sc-n\">06</span><h3>Reformas de ba&ntilde;o en <?php echo \$zona_nombre; ?></h3><p>Reformas completas o parciales con precio sin sorpresas antes de empezar.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
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
  $php .= "      <a href=\"tel:+34611165129\" class=\"btn-hz-w\">&#128222; Llamar ahora</a>\n";
  $php .= "      <a href=\"https://wa.me/34611165129\" target=\"_blank\" rel=\"noopener\" class=\"btn-hz-g\">&#128172; WhatsApp</a>\n";
  $php .= "    </div>\n";
  $php .= "    <div class=\"cta-dark-tel\">Tel&eacute;fono directo<strong>611 165 129</strong></div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n\n";

  $php .= "<?php include '../includes/footer.php'; ?>\n";

  return $php;
}

// ═════════════════════════════════════════════════════════════════════
// generar_php_libre — envuelve HTML libre de Claude con cabecera PHP y cola fija
// (mapa, zona-tags, CTA oscuro, proyectos/artículos dinámicos)
// ═════════════════════════════════════════════════════════════════════
function generar_php_libre($html_body, $meta_title, $meta_desc, $ciudad, $ciudad_slug, $ciudad_cp, $tipo, $otras_ciudades, $depth = 2, $lat = '38.4766', $lng = '-0.7952') {
  $e       = fn($v) => var_export($v, true);
  $back    = str_repeat('../', $depth);
  $ciudad_q = addslashes($ciudad);

  $servicio_nombres = [
    'hub_ciudad'     => 'Fontaner&iacute;a',
    'busqueda_fugas' => 'Detecci&oacute;n de fugas',
    'urgencias'      => 'Fontanero urgente',
    'desatascos'     => 'Desatascos',
    'fontanero'      => 'Fontaner&iacute;a',
  ];
  $svc_nombre = $servicio_nombres[$tipo] ?? 'Fontaner&iacute;a';

  if ($tipo === 'hub_ciudad') {
    $meta_url = "https://caroltemp.com/fontanero/{$ciudad_slug}";
  } else {
    $meta_url = "https://caroltemp.com/fontanero/{$ciudad_slug}/{$tipo}";
  }

  // Zone tags: para hub el back link no existe (ya estamos en el hub)
  if ($tipo === 'hub_ciudad') {
    $ztags = '';
    foreach ($otras_ciudades as $otra) {
      $n = htmlspecialchars($otra['nombre'], ENT_QUOTES, 'UTF-8');
      $s = htmlspecialchars($otra['slug'],   ENT_QUOTES, 'UTF-8');
      $ztags .= "      <a href=\"/fontanero/{$s}\" class=\"zona-ztag\">{$n}</a>\n";
    }
    $zonas_titulo = 'Tambi&eacute;n trabajamos en <span class="hl">zonas cercanas</span>';
  } else {
    $ztags = "      <a href=\"/fontanero/{$ciudad_slug}\" class=\"zona-ztag\" style=\"background:#1e3a5f;color:#fff\">&#8592; Todos los servicios en {$ciudad}</a>\n";
    foreach ($otras_ciudades as $otra) {
      $n = htmlspecialchars($otra['nombre'], ENT_QUOTES, 'UTF-8');
      $s = htmlspecialchars($otra['slug'],   ENT_QUOTES, 'UTF-8');
      $ztags .= "      <a href=\"/fontanero/{$s}/{$tipo}\" class=\"zona-ztag\">{$n}</a>\n";
    }
    $zonas_titulo = 'Mismo servicio en <span class="hl">otros municipios</span>';
  }

  // PHP header
  $php  = "<?php\n";
  $php .= "/**\n * {$svc_nombre} en {$ciudad}\n * Generado con arquitectura dos pasos — CarolTemp\n */\n";
  $php .= "\$meta_title  = {$e($meta_title)};\n";
  $php .= "\$meta_desc   = {$e($meta_desc)};\n";
  $php .= "\$meta_url    = {$e($meta_url)};\n";
  $php .= "\$schema_type = 'local';\n";
  $php .= "\$page_css    = 'zona';\n";
  $php .= "\$page_js     = 'zona';\n";
  $php .= "include '{$back}includes/head.php';\n";
  $php .= "?>\n\n";

  // Editorial HTML from Claude
  $php .= $html_body . "\n\n";

  // End-of-editable marker
  $php .= "<!-- /editable -->\n\n";

  // Dynamic: proyectos + artículos
  $php .= "<?php\n";
  $php .= "\$_proy = [];\n";
  $php .= "try {\n";
  $php .= "  \$_ps = \$pdo->prepare('SELECT titulo, slug, descripcion, servicio, imagen FROM proyectos WHERE publicado=1 AND zona LIKE ? ORDER BY fecha DESC LIMIT 3');\n";
  $php .= "  \$_ps->execute(['%{$ciudad_q}%']);\n";
  $php .= "  \$_proy = \$_ps->fetchAll(PDO::FETCH_ASSOC);\n";
  $php .= "} catch (\\Throwable \$_e) {}\n";
  $php .= "\$_arts = [];\n";
  $php .= "try {\n";
  $php .= "  \$_as = \$pdo->prepare('SELECT titulo, slug, extracto, categoria, imagen FROM articulos WHERE publicado=1 AND (zona LIKE ? OR categoria LIKE ?) ORDER BY fecha DESC LIMIT 3');\n";
  $php .= "  \$_as->execute(['%{$ciudad_q}%', '%fontan%']);\n";
  $php .= "  \$_arts = \$_as->fetchAll(PDO::FETCH_ASSOC);\n";
  $php .= "  if (empty(\$_arts)) {\n";
  $php .= "    \$_as2 = \$pdo->query('SELECT titulo, slug, extracto, categoria, imagen FROM articulos WHERE publicado=1 ORDER BY fecha DESC LIMIT 3');\n";
  $php .= "    \$_arts = \$_as2 ? \$_as2->fetchAll(PDO::FETCH_ASSOC) : [];\n";
  $php .= "  }\n";
  $php .= "} catch (\\Throwable \$_e) {}\n";
  $php .= "if (!empty(\$_proy)): ?>\n";
  $php .= "<section class=\"zona-sec\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <p class=\"zona-lbl\">Trabajos realizados</p>\n";
  $php .= "    <h2>Proyectos en <span class=\"hl\">{$ciudad}</span></h2>\n";
  $php .= "    <div class=\"zona-svc\" style=\"margin-top:2rem\">\n";
  $php .= "      <?php foreach (\$_proy as \$_p): ?>\n";
  $php .= "      <a href=\"/proyectos/<?php echo urlencode(\$_p['slug']); ?>\" class=\"zona-sc\">\n";
  $php .= "        <?php if (!empty(\$_p['imagen'])): ?><img src=\"<?php echo htmlspecialchars(\$_p['imagen']); ?>\" alt=\"<?php echo htmlspecialchars(\$_p['titulo']); ?>\" loading=\"lazy\" style=\"width:100%;height:160px;object-fit:cover;border-radius:8px;margin-bottom:.75rem\"><?php endif; ?>\n";
  $php .= "        <h3><?php echo htmlspecialchars(\$_p['titulo']); ?></h3>\n";
  $php .= "        <p><?php echo htmlspecialchars(mb_substr(\$_p['descripcion'] ?? '', 0, 100)); ?>...</p>\n";
  $php .= "        <span class=\"zona-sc-a\">Ver proyecto &rarr;</span>\n";
  $php .= "      </a>\n";
  $php .= "      <?php endforeach; ?>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n";
  $php .= "<?php endif; ?>\n";
  $php .= "<?php if (!empty(\$_arts)): ?>\n";
  $php .= "<section class=\"zona-sec zona-sec-gray\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <p class=\"zona-lbl\">Consejos &uacute;tiles</p>\n";
  $php .= "    <h2>Art&iacute;culos <span class=\"hl\">relacionados</span></h2>\n";
  $php .= "    <div class=\"zona-svc\" style=\"margin-top:2rem\">\n";
  $php .= "      <?php foreach (\$_arts as \$_a): ?>\n";
  $php .= "      <a href=\"/noticias/<?php echo urlencode(\$_a['slug']); ?>\" class=\"zona-sc\">\n";
  $php .= "        <h3><?php echo htmlspecialchars(\$_a['titulo']); ?></h3>\n";
  $php .= "        <p><?php echo htmlspecialchars(mb_substr(\$_a['extracto'] ?? '', 0, 100)); ?>...</p>\n";
  $php .= "        <span class=\"zona-sc-a\">Leer art&iacute;culo &rarr;</span>\n";
  $php .= "      </a>\n";
  $php .= "      <?php endforeach; ?>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n";
  $php .= "<?php endif; ?>\n\n";

  // Mapa fijo
  $php .= "<section class=\"zona-sec\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <p class=\"zona-lbl\">Zona de cobertura</p>\n";
  $php .= "    <h2>{$svc_nombre} <span class=\"hl\">en {$ciudad}</span></h2>\n";
  $php .= "    <p style=\"margin-bottom:1.5rem;color:#576574\">Atendemos toda la localidad de {$ciudad} (CP {$ciudad_cp}) y municipios lim&iacute;trofes.</p>\n";
  $php .= "    <div style=\"border-radius:12px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.12)\">\n";
  $php .= "      <iframe src=\"https://maps.google.com/maps?q={$lat},{$lng}&z=14&output=embed\" width=\"100%\" height=\"380\" style=\"border:0;display:block\" allowfullscreen loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\" title=\"{$svc_nombre} en {$ciudad}\"></iframe>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n";

  // Zona tags
  $php .= "<section class=\"zona-sec zona-sec-gray\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <p class=\"zona-lbl\">Servicio en otros municipios</p>\n";
  $php .= "    <h2>{$zonas_titulo}</h2>\n";
  $php .= "    <div class=\"zona-ztags\">\n{$ztags}    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n";

  // CTA final oscuro
  $php .= "<section class=\"cta-dark\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <h2>&iquest;Necesitas {$svc_nombre} <span>en {$ciudad}?</span></h2>\n";
  $php .= "    <p>Ll&aacute;menos o escr&iacute;benos. Te atendemos hoy.</p>\n";
  $php .= "    <div class=\"cta-dark-btns\">\n";
  $php .= "      <a href=\"tel:+34611165129\" class=\"btn-hz-w\">&#128222; Llamar ahora</a>\n";
  $php .= "      <a href=\"https://wa.me/34611165129\" target=\"_blank\" rel=\"noopener\" class=\"btn-hz-g\">&#128172; WhatsApp</a>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n";

  $php .= "<?php include '{$back}includes/footer.php'; ?>\n";

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
  $real_site_root = rtrim(str_replace('\\', '/', realpath($site_root)), '/');
  // Reconstruir la ruta normalizada sin depender de realpath en dirs nuevos
  $norm_path = str_replace(['\\', '/./'], ['/', '/'], $abs_path);
  if (strpos($norm_path, $real_site_root . '/') !== 0 && $norm_path !== $real_site_root) {
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

  // ── Registrar en tabla paginas del CMS ───────────────────────────
  // Derivar slug y título desde el filepath (sin extraer contenido del archivo)
  $slug_cms   = preg_replace('/-+/', '-', preg_replace('/[^a-z0-9\-]/', '-',
                  str_replace(['.php', '/'], ['', '-'], $filepath_rel)));
  $slug_cms   = trim($slug_cms, '-');
  $titulo_cms = ucwords(str_replace(['-','_'], ' ', basename($filepath_rel, '.php')));
  try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS paginas (
      id INT AUTO_INCREMENT PRIMARY KEY,
      titulo VARCHAR(255) NOT NULL,
      slug VARCHAR(255) NOT NULL UNIQUE,
      filepath VARCHAR(255) NOT NULL UNIQUE,
      contenido LONGTEXT,
      meta_title VARCHAR(255) DEFAULT '',
      meta_desc TEXT DEFAULT '',
      publicado TINYINT(1) DEFAULT 1,
      fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
      modificado DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    // Extraer meta_title y meta_desc del contenido PHP para guardarlos en DB
    $meta_title_db = '';
    $meta_desc_db  = '';
    if (preg_match('/\$meta_title\s*=\s*[\'"](.+?)[\'"]\s*;/', $contenido, $mm)) {
      $meta_title_db = stripslashes($mm[1]);
    }
    if (preg_match('/\$meta_desc\s*=\s*[\'"](.+?)[\'"]\s*;/', $contenido, $mm)) {
      $meta_desc_db = stripslashes($mm[1]);
    }

    $ck = $pdo->prepare('SELECT id FROM paginas WHERE filepath = ? LIMIT 1');
    $ck->execute([$filepath_rel]);
    $existing_id = $ck->fetchColumn();
    if (!$existing_id) {
      $ins = $pdo->prepare('INSERT INTO paginas (titulo, slug, filepath, contenido, meta_title, meta_desc, publicado) VALUES (?, ?, ?, ?, ?, ?, 1)');
      $ins->execute([$titulo_cms, $slug_cms, $filepath_rel, '', $meta_title_db, $meta_desc_db]);
    } else {
      // Actualizar metas si estaban vacías
      $upd = $pdo->prepare('UPDATE paginas SET meta_title = ?, meta_desc = ? WHERE id = ? AND (meta_title = \'\' OR meta_title IS NULL)');
      $upd->execute([$meta_title_db, $meta_desc_db, $existing_id]);
    }
  } catch (\Throwable $e) { /* ignorar — sync opcional */ }

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
  $real_site_root = rtrim(str_replace('\\', '/', realpath($site_root)), '/');
  $real_dir_raw   = realpath(dirname($abs_path));
  $real_dir       = $real_dir_raw !== false ? rtrim(str_replace('\\', '/', $real_dir_raw), '/') : false;
  if ($real_dir === false || (strpos($real_dir . '/', $real_site_root . '/') !== 0)) {
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
// Refinar página — chat para ajustar HTML ya generado
// ─────────────────────────────────────────────────────────────────────
if ($accion === 'refinar') {
  $html_actual  = trim($_POST['html_actual']  ?? '');
  $instruccion  = trim($_POST['instruccion']  ?? '');
  $filepath_ref = trim($_POST['filepath']     ?? '');

  if (!$html_actual || !$instruccion) {
    echo json_encode(['error' => 'Falta html_actual o instruccion']);
    exit;
  }

  $system_ref = <<<'SYSREF'
Eres un editor web experto. El usuario te dará el HTML actual de una página de CarolTemp (empresa de fontanería en Alicante) y una instrucción de cambio.

DATOS DE CAROLTEMP:
- Servicios: fontanería urgente, detección de fugas (geófono+cámara), desatascos, termos eléctricos, descalcificadores, reformas de baño
- NO hacen climatización, NO hacen aire acondicionado
- Teléfono: 611 165 129

REGLAS:
- Aplica SOLO los cambios que pide el usuario, no cambies el resto
- Devuelve el HTML completo corregido, sin explicaciones, sin markdown, sin bloques de código
- Mantén las clases CSS (zona-sec, hz-dark, zona-svc, etc.) y la estructura general
- NUNCA menciones climatización ni aire acondicionado
- PHP solo: $base_url como variable disponible
SYSREF;

  $messages_ref = [
    [
      'role'    => 'user',
      'content' => "HTML ACTUAL:\n\n{$html_actual}\n\n---\nINSTRUCCIÓN: {$instruccion}"
    ],
    [
      'role'    => 'assistant',
      'content' => '<'
    ]
  ];

  $payload_ref = json_encode([
    'model'      => ANTHROPIC_MODEL,
    'max_tokens' => 8000,
    'system'     => $system_ref,
    'messages'   => $messages_ref,
  ], JSON_UNESCAPED_UNICODE);

  $ch_ref = curl_init('https://api.anthropic.com/v1/messages');
  curl_setopt_array($ch_ref, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload_ref,
    CURLOPT_HTTPHEADER     => [
      'Content-Type: application/json',
      'x-api-key: ' . ANTHROPIC_API_KEY,
      'anthropic-version: 2023-06-01',
    ],
    CURLOPT_TIMEOUT        => 120,
  ]);
  $res_ref  = curl_exec($ch_ref);
  $err_ref  = curl_error($ch_ref);
  curl_close($ch_ref);

  if ($err_ref) { echo json_encode(['error' => 'cURL: ' . $err_ref]); exit; }

  $dec_ref = json_decode($res_ref, true);
  if (!isset($dec_ref['content'][0]['text'])) {
    echo json_encode(['error' => 'Sin respuesta de IA', 'raw' => $res_ref]);
    exit;
  }

  $html_nuevo = '<' . ltrim($dec_ref['content'][0]['text']);
  echo json_encode(['ok' => true, 'html' => $html_nuevo]);
  exit;
}

// ─────────────────────────────────────────────────────────────────────
// Acción: preview — renderiza HTML con el head/footer real del sitio
// ─────────────────────────────────────────────────────────────────────
if ($accion === 'preview') {
  $html_prev = $_POST['html']     ?? '';
  $page_css  = preg_replace('/[^a-z0-9\-]/', '', $_POST['page_css'] ?? 'zona');

  // Limpiar buffer y cambiar a HTML
  ob_end_clean();
  header('Content-Type: text/html; charset=utf-8');

  $meta_title  = 'Preview';
  $meta_desc   = '';
  $meta_url    = '';
  $robots      = 'noindex';
  $schema_type = '';

  include '../includes/head.php';
  echo '<style>nav,.site-nav,header.site-header{display:none!important}body{padding-top:0!important}</style>';
  echo $html_prev;
  include '../includes/footer.php';
  exit;
}

// ─────────────────────────────────────────────────────────────────────
// Acción no reconocida
// ─────────────────────────────────────────────────────────────────────
echo json_encode(['error' => 'Acción no reconocida: ' . htmlspecialchars($accion)]);
exit;
