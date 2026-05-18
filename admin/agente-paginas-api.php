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

// ── Perfiles por ciudad — datos reales para diferenciación de contenido ─
$ciudad_perfiles = [
  'Elda' => 'Ciudad industrial del calzado (~55.000 hab). Barrios densos como El Chapi, El Toscar y La Empredrada con bloques de pisos de los años 60-80 que tienen tuberías de hierro oxidadas o plomo. Agua con dureza media-alta (20-25 °fH): la cal deteriora juntas, grifos y termos gradualmente. Problemas más frecuentes: roturas en tuberías empotradas de hierro, desatascos en bajantes comunitarias de edificios antiguos, termos eléctricos desgastados que pierden agua, llaves de paso comunitarias que ya no cierran. El tejido industrial (talleres, naves calzado) genera atascos de grasa en arquetas.',
  'Petrer' => 'Ciudad residencial colindante con Elda (~35.000 hab). Urbanizaciones de los 80-90 con adosados y bloques de pisos. Agua DURÍSIMA (30-35 °fH), la más calcárea de la zona: la cal destruye termos, calentadores y grifos en pocos años. Alta demanda de descalcificadores (Nubeco). Problemas más frecuentes: termos calcificados que dejan de calentar, grifos bloqueados por sarro, fugas en juntas deterioradas por la dureza, manchas de cal imposibles de eliminar en sanitarios. Los calentadores de gas también sufren obstrucciones por cal en el intercambiador.',
  'Novelda' => 'Capital del mármol (~28.000 hab). Mezcla de industria pesada (naves de mármol, canteras, almacenes) y residencial. Agua EXTREMADAMENTE DURA (35+ °fH), la más calcárea de toda la comarca. El centro histórico tiene tuberías de hierro de décadas. Fincas agrícolas con pozos y depósitos propios. Problemas más frecuentes: obstrucciones por sarro en calentadores y duchas, fugas en instalaciones industriales de naves, grupos de presión en pozos privados averiados, tuberías de hierro del centro que revientan.',
  'Monóvar' => 'Municipio vinícola y rural (~12.000 hab). Pueblo con casco histórico de casas antiguas de los años 50-70 y chalets dispersos en campo. Muchas propiedades rurales con pozos, depósitos y grupos de presión propios. Agua de dureza moderada. Problemas más frecuentes: grupos de presión averiados en fincas rurales, pozos que pierden caudal en verano, tuberías de campo dañadas por raíces o heladas invernales, instalaciones muy antiguas del casco viejo sin actualizar.',
  'Sax' => 'Pueblo tranquilo (~10.000 hab) con casco histórico compacto y extrarradio más moderno. Centro con casas antiguas de piedra y tuberías de hierro de los años 60-70. Extrarradio con chalets y adosados de los 90-2000 con instalaciones más modernas. Agua de dureza media. Problemas más frecuentes: tuberías de hierro del casco viejo que se rompen o dan agua herrumbrosa, problemas de presión en viviendas altas del pueblo, instalaciones de riego en jardines de chalets, bajantes antiguas obstruidas.',
  'Pinoso' => 'Municipio rural extenso (~7.500 hab) con núcleo pequeño y muchas casas de campo muy dispersas por el territorio. Alta proporción de propiedades con depósitos, pozos y grupos de presión. Agua de dureza variable según zona. Problemas más frecuentes: grupos de presión averiados en casas rurales, depósitos sin mantenimiento con sedimentos, tuberías exteriores al aire libre que revientan con las heladas, urgencias en zonas de difícil acceso por caminos de tierra, pozos secos en verano.',
  'Monforte del Cid' => 'Municipio cercano al aeropuerto de Alicante (~6.500 hab). Urbanizaciones relativamente nuevas con segundas residencias, muchas de propietarios extranjeros que pasan meses fuera. Fincas rurales en las afueras. Agua de dureza media-alta. Problemas más frecuentes: instalaciones que llevan meses paradas y fallan al reactivarse (termos, llaves de paso), tuberías que revientan por presión acumulada en viviendas cerradas, demanda en temporada verano por parte de propietarios de segunda residencia, fugas en comunidades de urbanización.',
  'Salinas' => 'Pueblo muy pequeño (~3.000 hab). Casco urbano compacto con casas antiguas de los años 50-70, baja presión en la red municipal. Alguna pequeña urbanización exterior. Escasa oferta local de fontaneros, lo que hace urgente contar con servicio de la comarca. Problemas más frecuentes: baja presión de agua en viviendas altas, tuberías domésticas muy antiguas sin actualizar, instalaciones de los años 60-70 con juntas y conexiones desgastadas, escaso mantenimiento preventivo.',
  'Aspe' => 'Ciudad de tamaño medio (~20.000 hab). Barrios variados: edificios de los años 70-80 en el centro con tuberías envejecidas y zonas residenciales nuevas en el extrarradio. Industria ligera y comercio. Agua con cal moderada (18-22 °fH). Problemas más frecuentes: desatascos en edificios antiguos del centro, instalaciones de riego y jardín en chalets del extrarradio, termos y calentadores de los 80 que fallan, fugas en tuberías comunitarias de hierro de bloques antiguos.',
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

    // Contexto específico según tipo de página
    $page_ctx = [
      'financiacion'   => "Página de FINANCIACIÓN. CarolTemp ofrece planes a plazos (sin adelanto) para climatización, aerotermia, calderas, descalcificadores y reformas. Trabajan con entidades especializadas. Proceso ágil. Teléfono: 613 429 032.",
      'contacto'       => "Página de CONTACTO. Tel: 613 429 032. WhatsApp. Atienden toda la comarca: Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Monforte, Salinas, Aspe. Horario: Lun-Vie 8-20h, Sáb 9-14h. Presupuesto gratuito.",
      'sobre-nosotros' => "Página SOBRE NOSOTROS. CarolTemp: empresa local de fontanería y climatización en la comarca interior de Alicante. Instaladores Nubeco certificados. Geófono y cámara para fugas. Precio cerrado siempre. Sin inventar 'años de experiencia'.",
      'index'          => "Página HOME. Presentar CarolTemp: fontanería y climatización en la comarca. Diferenciadores: precio cerrado, geófono+cámara sin obras, Nubeco oficial. Zonas: Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Monforte, Salinas, Aspe.",
    ];
    $ctx_extra = $page_ctx[$basename] ?? "Página: {$desc_pagina}. Empresa CarolTemp — fontanería y climatización en la comarca interior de Alicante.";

    $system_corp = <<<'SYSC'
Eres un maquetador web SEO experto. Generas el HTML completo del <body> de páginas web para CarolTemp usando el sistema de componentes disponible.

DATOS DE CAROLTEMP:
- Empresa: fontanería y climatización en la comarca interior de Alicante
- Teléfono: 613 429 032 | WhatsApp: https://wa.me/34613429032
- Zonas: Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Monforte del Cid, Salinas, Aspe
- Diferenciadores REALES: precio cerrado antes de empezar, geófono+cámara para fugas sin romper, instaladores Nubeco certificados

REGLAS:
- NUNCA escribas "Vinalopó"
- NUNCA inventes estadísticas, porcentajes ni años de experiencia
- NUNCA frases vacías: "de confianza", "calidad garantizada", "expertos en"
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
      <a href="tel:+34613429032" class="btn-hz-w">📞 613 429 032</a>
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
  <a href="tel:+34613429032" class="zona-icard-btn">📞 Llamar ahora</a>
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
      <a href="tel:+34613429032" class="btn-hz-w">📞 Llamar ahora</a>
      <a href="https://wa.me/34613429032" target="_blank" rel="noopener" class="btn-hz-g">💬 WhatsApp</a>
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
Eres un redactor SEO local especializado en fontanería para CarolTemp (Alicante interior).

REGLAS ABSOLUTAS:
- NUNCA escribas "Vinalopó" — usa "comarca interior de Alicante" si necesitas referirte a la zona
- NUNCA inventes estadísticas, años de experiencia, número de clientes ni porcentajes
- NUNCA uses frases genéricas como "somos tu fontanero de confianza", "expertos en fontanería", "calidad y profesionalidad"
- Año actual: {$anyo}

DATOS DE CAROLTEMP:
- Teléfono: 613 429 032
- Zona: Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Monforte del Cid, Salinas, Aspe
- Diferenciadores REALES: presupuesto cerrado antes de empezar (sin sorpresas), instaladores certificados Nubeco (descalcificadores), geófono y cámara para fugas sin obras
- Servicios: fontanería urgente, detección de fugas, desatascos, termos, descalcificadores, reformas de baño

DIFERENCIACIÓN OBLIGATORIA — MÁS IMPORTANTE:
El texto para {$ciudad} debe ser COMPLETAMENTE DISTINTO al de cualquier otra ciudad del listado.
Usa los datos concretos de {$ciudad} que te doy en el mensaje del usuario.
Menciona problemas REALES de esa ciudad (tipo de edificios, dureza del agua, zona rural/urbana).
Si es Petrer o Novelda → habla del problema grave de cal. Si es Pinoso o Monóvar → habla de pozos y campo. Si es Elda → tuberías antiguas de hierro en bloques. Etc.

ESTRUCTURA JSON — devuelve SOLO este JSON válido:
{
  "meta_title": "Fontanero en [Ciudad] — CarolTemp | máx 60 chars",
  "meta_desc": "Entre 150-160 chars exactos: qué servicio + en [ciudad] + diferenciador concreto + llamada a acción",
  "hero_sub": "Una frase de 10-15 palabras con el problema o característica más específica de [ciudad]",
  "intro_p1": "2-3 frases sobre los problemas de fontanería más habituales en [ciudad], usando los datos concretos de esa ciudad",
  "intro_p2": "1-2 frases sobre cómo CarolTemp los resuelve con sus diferenciadores reales",
  "checklist": ["servicio concreto para [ciudad] 1", "servicio concreto 2", "servicio concreto 3", "servicio concreto 4", "servicio concreto 5"],
  "faq": [
    {"pregunta": "pregunta real que busca alguien en [ciudad]", "respuesta": "respuesta directa y honesta, 1-2 frases"},
    {"pregunta": "segunda pregunta específica de [ciudad]", "respuesta": "respuesta directa"},
    {"pregunta": "tercera pregunta", "respuesta": "respuesta"},
    {"pregunta": "cuarta pregunta", "respuesta": "respuesta"}
  ]
}

SOBRE LAS FAQ: NO uses respuestas genéricas. Cada respuesta debe ser honesta y concreta. Si no sabes el precio exacto, di "presupuesto cerrado antes de empezar, sin sorpresas" pero añade contexto específico de [ciudad].

CRÍTICO JSON: Usa comillas dobles para claves y valores. Dentro de los valores usa comillas simples si necesitas citar algo. Sin comas finales.
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
Eres un redactor SEO local especializado en fontanería para CarolTemp (Alicante interior).

REGLAS ABSOLUTAS:
- NUNCA escribas "Vinalopó"
- NUNCA inventes estadísticas ni porcentajes
- NUNCA uses frases genéricas vacías
- Año actual: {$anyo}

DIFERENCIADORES DE CAROLTEMP PARA FUGAS:
- Geófono acústico profesional: detecta el sonido de la fuga sin abrir paredes
- Cámara de inspección: visualiza el interior de tuberías y bajantes
- Detectan Y reparan: no hace falta llamar a otra empresa
- Precio cerrado antes de empezar

DIFERENCIACIÓN OBLIGATORIA:
Cada ciudad tiene causas distintas de fugas. Usa los datos de {$ciudad} para describir problemas REALES:
- Si hay tuberías antiguas de hierro → fugas por corrosión
- Si el agua es muy dura (Petrer, Novelda) → juntas deterioradas por cal, fugas en calefacción
- Si hay fincas con pozos (Pinoso, Monóvar) → fugas en instalaciones exteriores/enterradas
- Si hay edificios de comunidad → fugas en bajantes o instalaciones comunes

DEVUELVE SOLO ESTE JSON VÁLIDO:
{
  "meta_title": "Detección de fugas en [Ciudad] — CarolTemp | máx 60 chars",
  "meta_desc": "150-160 chars: geófono + cámara + sin romper + [ciudad] + precio cerrado",
  "hero_titulo": "Detección de fugas en {$ciudad}<br><span class=\"hl\">sin romper paredes.</span>",
  "hero_sub": "frase de 10-15 palabras sobre el problema de fugas concreto en {$ciudad}",
  "contenido_intro": "<p>2 frases sobre por qué hay fugas en {$ciudad} — causas reales de esa ciudad específica — y cómo CarolTemp las localiza.</p>",
  "servicios_lista": ["tipo de fuga 1 relevante para {$ciudad}", "tipo 2", "tipo 3", "tipo 4", "tipo 5", "tipo 6"],
  "problemas_zona": [
    {"titulo": "causa real de fuga en {$ciudad}", "texto": "explicación concreta de por qué ocurre en esta ciudad, 1-2 frases"},
    {"titulo": "segunda causa real", "texto": "explicación"},
    {"titulo": "tercera causa real", "texto": "explicación"}
  ],
  "faq": [
    {"pregunta": "pregunta real que busca alguien con fuga en {$ciudad}", "respuesta": "respuesta directa y honesta"},
    {"pregunta": "segunda pregunta", "respuesta": "respuesta"},
    {"pregunta": "tercera pregunta", "respuesta": "respuesta"},
    {"pregunta": "cuarta pregunta", "respuesta": "respuesta"}
  ]
}

CRÍTICO JSON: comillas dobles en claves y valores. Comillas simples dentro de valores si hace falta.
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
Eres un redactor SEO local especializado en fontanería para CarolTemp (Alicante interior).

REGLAS ABSOLUTAS:
- NUNCA escribas "Vinalopó"
- NUNCA inventes estadísticas ni porcentajes
- NUNCA uses frases vacías como "somos tu fontanero de confianza" o "calidad garantizada"
- Año actual: {$anyo}

DATOS DE CAROLTEMP PARA URGENCIAS:
- Teléfono urgencias: 613 429 032
- Presupuesto cerrado antes de empezar — el cliente sabe el precio antes de que se empiece
- Atienden toda la comarca: Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Monforte, Salinas, Aspe
- Urgencias más comunes: roturas de tubería, pérdidas de agua, grifos, cisternas, llaves de paso que no cierran, termos que pierden agua

DIFERENCIACIÓN OBLIGATORIA:
Las urgencias de fontanería no son iguales en todas las ciudades. Usa los datos de {$ciudad}:
- Si tiene edificios antiguos (Elda, Aspe, centro Sax) → roturas de tuberías de hierro envejecidas
- Si el agua es muy dura (Petrer, Novelda) → termos que explotan o pierden por cal
- Si es rural (Pinoso, Monóvar) → grupos de presión averiados, pozos, tuberías de campo
- Si hay segunda residencia (Monforte) → instalaciones que llevan meses paradas y fallan

DEVUELVE SOLO ESTE JSON VÁLIDO:
{
  "meta_title": "Fontanero urgente en [Ciudad] — CarolTemp | máx 60 chars",
  "meta_desc": "150-160 chars: urgencias [ciudad] + diferenciador real + teléfono o CTA",
  "hero_titulo": "Fontanero urgente en {$ciudad}<br><span class=\"hl\">precio cerrado.</span>",
  "hero_sub": "frase de 10-15 palabras sobre la urgencia más habitual en {$ciudad}",
  "contenido_intro": "<p>2 frases sobre las urgencias de fontanería más habituales en {$ciudad} — usando datos reales de esa ciudad.</p>",
  "servicios_lista": ["urgencia concreta 1 para {$ciudad}", "urgencia 2", "urgencia 3", "urgencia 4", "urgencia 5", "urgencia 6"],
  "problemas_zona": [
    {"titulo": "avería urgente típica en {$ciudad}", "texto": "por qué ocurre en {$ciudad} concretamente, 1-2 frases"},
    {"titulo": "segunda avería urgente", "texto": "explicación"},
    {"titulo": "tercera avería urgente", "texto": "explicación"}
  ],
  "faq": [
    {"pregunta": "pregunta real sobre urgencias en {$ciudad}", "respuesta": "respuesta directa y honesta"},
    {"pregunta": "segunda pregunta", "respuesta": "respuesta"},
    {"pregunta": "tercera pregunta", "respuesta": "respuesta"},
    {"pregunta": "cuarta pregunta", "respuesta": "respuesta"}
  ]
}

CRÍTICO JSON: comillas dobles en claves y valores. Comillas simples dentro de valores si hace falta.
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
Eres un redactor SEO local especializado en fontanería para CarolTemp (Alicante interior).

REGLAS ABSOLUTAS:
- NUNCA escribas "Vinalopó"
- NUNCA inventes estadísticas, años de experiencia, número de clientes ni porcentajes
- NUNCA uses frases genéricas vacías: "expertos en", "somos tu fontanero de confianza", "calidad y profesionalidad"
- Año actual: {$anyo}

DATOS DE CAROLTEMP:
- Teléfono: 613 429 032
- Zona: Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Monforte del Cid, Salinas, Aspe
- Diferenciadores REALES: presupuesto cerrado antes de empezar, geófono+cámara para fugas, instaladores certificados Nubeco (descalcificadores)
- Servicios: fontanería urgente, detección de fugas, desatascos, termos, descalcificadores, reformas de baño

DIFERENCIACIÓN OBLIGATORIA — LO MÁS IMPORTANTE:
El contenido de {$ciudad} debe ser DISTINTO al de cualquier otra ciudad. Para lograrlo:
1. Lee los datos específicos de {$ciudad} que te doy en el mensaje
2. Los problemas_zona deben describir causas REALES de esa ciudad (tipo de edificios, dureza del agua, rural/urbano)
3. Las FAQ deben ser preguntas que haría alguien EN {$ciudad}, con respuestas honestas y concretas
4. PROHIBIDO: copiar la misma estructura cambiando solo el nombre de ciudad

DEVUELVE SOLO ESTE JSON VÁLIDO:
{
  "meta_title": "Keyword + [Ciudad] — CarolTemp | máx 60 chars",
  "meta_desc": "150-160 chars exactos: servicio + [ciudad] + diferenciador + CTA",
  "hero_titulo": "[Servicio] en {$ciudad}<br><span class=\"hl\">gancho específico de {$ciudad}.</span>",
  "hero_sub": "frase de 10-15 palabras con problema o característica real de {$ciudad}",
  "contenido_intro": "<p>2 frases sobre el problema concreto de este servicio en {$ciudad} y cómo CarolTemp lo resuelve.</p>",
  "servicios_lista": ["servicio concreto 1", "servicio 2", "servicio 3", "servicio 4", "servicio 5", "servicio 6"],
  "problemas_zona": [
    {"titulo": "problema real en {$ciudad}", "texto": "por qué ocurre en {$ciudad} concretamente, 1-2 frases"},
    {"titulo": "segundo problema real", "texto": "explicación"},
    {"titulo": "tercer problema real", "texto": "explicación"}
  ],
  "faq": [
    {"pregunta": "pregunta real que busca alguien en {$ciudad}", "respuesta": "respuesta directa y honesta, 1-2 frases"},
    {"pregunta": "segunda pregunta real", "respuesta": "respuesta directa"},
    {"pregunta": "tercera pregunta", "respuesta": "respuesta"},
    {"pregunta": "cuarta pregunta", "respuesta": "respuesta"}
  ]
}

CRÍTICO JSON: comillas dobles en claves y valores. Comillas simples dentro de valores si hace falta. Sin comas finales.
SYS;

  // ── Instrucciones por tipo de servicio ────────────────────────────
  $instrucciones = [
    'fugas'      => "Tipo de página: DETECCIÓN DE FUGAS. Keyword principal: 'detección fugas {$ciudad}' o 'buscar fugas {$ciudad}'.\nhero_titulo gancho: algo como '...sin romper paredes.' o '...sin obras innecesarias.'\nproblemas_zona: 3 causas REALES de fugas en {$ciudad} según los datos de arriba (no pongas 'Problema típico 1').\nFAQ: 4 preguntas reales sobre fugas en {$ciudad} — con respuestas honestas (precio, método de detección, si reparan, tiempo de respuesta).",
    'desatascos' => "Tipo de página: DESATASCOS. Keyword principal: 'desatascos {$ciudad}' o 'desatascar {$ciudad}'.\nhero_titulo gancho: algo como '...hoy mismo.' o '...sin esperas.'\nproblemas_zona: 3 causas REALES de atascos en {$ciudad} según los datos (tipo de edificios, uso industrial, comunidades, etc.).\nFAQ: 4 preguntas reales sobre desatascos en {$ciudad} — precio, tiempo, qué incluye, urgencias.",
    'fontanero'  => "Tipo de página: FONTANERO GENERAL. Keyword principal: 'fontanero {$ciudad}'.\nhero_titulo gancho: algo como '...precio cerrado.' o '...sin sorpresas.'\nproblemas_zona: 3 situaciones REALES de {$ciudad} en las que la gente llama a un fontanero, usando los datos de la ciudad.\nFAQ: 4 preguntas reales sobre fontanero en {$ciudad} — precio visita, qué hacen, urgencias, garantía.",
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
  $meta_desc       = $data['meta_desc']       ?? "Servicio de {$servicio_nombre} en {$ciudad}. Presupuesto cerrado. Llama al 613 429 032.";
  $hero_titulo     = $data['hero_titulo']     ?? "{$servicio_nombre} en {$ciudad}<br><span class=\"hl\">precio cerrado.</span>";
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
  $php .= "      <a href=\"tel:+34613429032\" class=\"btn-hz-w\">&#128222; 613 429 032</a>\n";
  $php .= "      <a href=\"/contacto\" class=\"btn-hz-g\">Solicitar presupuesto</a>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n\n";

  // ── Strip diferenciadores ─────────────────────────────────────────
  $php .= "<div class=\"dif-strip\">\n";
  $php .= "  <div class=\"dif-strip-in\">\n";
  $php .= "    <div class=\"dif-item\"><span class=\"dif-val\">&#128176; Precio cerrado</span><span class=\"dif-lbl\">Antes de empezar</span></div>\n";
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
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">Tel&eacute;fono</span><span class=\"zona-ir-v\"><a href=\"tel:+34613429032\">613 429 032</a></span></div>\n";
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">WhatsApp</span><span class=\"zona-ir-v\"><a href=\"https://wa.me/34613429032\">Escribir ahora &rarr;</a></span></div>\n";
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">Todos los servicios</span><span class=\"zona-ir-v\"><a href=\"/fontanero/{$ciudad_slug}\">Fontaner&iacute;a en {$ciudad} &rarr;</a></span></div>\n";
  $php .= "          <a href=\"tel:+34613429032\" class=\"zona-icard-btn\">&#128222; Llamar ahora</a>\n";
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

  // ── Proyectos recientes en la ciudad ─────────────────────────────
  $php .= "<?php\n";
  $php .= "\$_proy = [];\n";
  $php .= "try {\n";
  $php .= "  \$_ps = \$pdo->prepare('SELECT titulo, slug, descripcion, servicio, imagen FROM proyectos WHERE publicado=1 AND zona LIKE ? ORDER BY fecha DESC LIMIT 3');\n";
  $php .= "  \$_ps->execute(['%{$ciudad}%']);\n";
  $php .= "  \$_proy = \$_ps->fetchAll(PDO::FETCH_ASSOC);\n";
  $php .= "} catch (\\Throwable \$_e) {}\n";
  $php .= "?>\n";
  $php .= "<?php if (!empty(\$_proy)): ?>\n";
  $php .= "<section class=\"zona-sec\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <p class=\"zona-lbl\">Trabajos realizados</p>\n";
  $php .= "    <h2>Proyectos de {$servicio_nombre} <span class=\"hl\">en {$ciudad}</span></h2>\n";
  $php .= "    <div class=\"blog-grid\" style=\"margin-top:2rem\">\n";
  $php .= "      <?php foreach (\$_proy as \$_p): ?>\n";
  $php .= "      <a href=\"<?php echo \$base_url; ?>proyectos/<?php echo urlencode(\$_p['slug']); ?>\" class=\"blog-card\">\n";
  $php .= "        <?php if (\$_p['imagen']): ?><img src=\"<?php echo htmlspecialchars(\$_p['imagen']); ?>\" alt=\"<?php echo htmlspecialchars(\$_p['titulo']); ?>\" loading=\"lazy\"><?php endif; ?>\n";
  $php .= "        <div class=\"blog-card-body\">\n";
  $php .= "          <?php if (\$_p['servicio']): ?><span class=\"blog-cat\"><?php echo htmlspecialchars(\$_p['servicio']); ?></span><?php endif; ?>\n";
  $php .= "          <h3><?php echo htmlspecialchars(\$_p['titulo']); ?></h3>\n";
  $php .= "          <p><?php echo htmlspecialchars(mb_substr(\$_p['descripcion'] ?? '', 0, 120)); ?>...</p>\n";
  $php .= "        </div>\n";
  $php .= "      </a>\n";
  $php .= "      <?php endforeach; ?>\n";
  $php .= "    </div>\n";
  $php .= "    <div style=\"text-align:center;margin-top:1.5rem\"><a href=\"<?php echo \$base_url; ?>proyectos/zona/<?php echo urlencode('{$ciudad}'); ?>\" class=\"btn-hz-g\" style=\"display:inline-flex\">Ver todos los proyectos en {$ciudad} &rarr;</a></div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n";
  $php .= "<?php endif; ?>\n\n";

  // ── Artículos relacionados ────────────────────────────────────────
  $php .= "<?php\n";
  $php .= "\$_arts = [];\n";
  $php .= "try {\n";
  $php .= "  \$_as = \$pdo->prepare('SELECT titulo, slug, extracto, categoria, imagen FROM articulos WHERE publicado=1 AND (zona LIKE ? OR categoria LIKE ?) ORDER BY fecha DESC LIMIT 3');\n";
  $php .= "  \$_as->execute(['%{$ciudad}%', '%fontan%']);\n";
  $php .= "  \$_arts = \$_as->fetchAll(PDO::FETCH_ASSOC);\n";
  $php .= "  if (empty(\$_arts)) {\n";
  $php .= "    \$_as2 = \$pdo->query('SELECT titulo, slug, extracto, categoria, imagen FROM articulos WHERE publicado=1 ORDER BY fecha DESC LIMIT 3');\n";
  $php .= "    \$_arts = \$_as2->fetchAll(PDO::FETCH_ASSOC);\n";
  $php .= "  }\n";
  $php .= "} catch (\\Throwable \$_e) {}\n";
  $php .= "?>\n";
  $php .= "<?php if (!empty(\$_arts)): ?>\n";
  $php .= "<section class=\"zona-sec zona-sec-gray\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <p class=\"zona-lbl\">Consejos y noticias</p>\n";
  $php .= "    <h2>Art&iacute;culos sobre <span class=\"hl\">{$servicio_nombre}</span></h2>\n";
  $php .= "    <div class=\"blog-grid\" style=\"margin-top:2rem\">\n";
  $php .= "      <?php foreach (\$_arts as \$_a): ?>\n";
  $php .= "      <a href=\"<?php echo \$base_url; ?>noticias/<?php echo urlencode(\$_a['slug']); ?>\" class=\"blog-card\">\n";
  $php .= "        <?php if (\$_a['imagen']): ?><img src=\"<?php echo htmlspecialchars(\$_a['imagen']); ?>\" alt=\"<?php echo htmlspecialchars(\$_a['titulo']); ?>\" loading=\"lazy\"><?php endif; ?>\n";
  $php .= "        <div class=\"blog-card-body\">\n";
  $php .= "          <?php if (\$_a['categoria']): ?><span class=\"blog-cat\"><?php echo htmlspecialchars(\$_a['categoria']); ?></span><?php endif; ?>\n";
  $php .= "          <h3><?php echo htmlspecialchars(\$_a['titulo']); ?></h3>\n";
  $php .= "          <p><?php echo htmlspecialchars(mb_substr(\$_a['extracto'] ?? '', 0, 120)); ?>...</p>\n";
  $php .= "        </div>\n";
  $php .= "      </a>\n";
  $php .= "      <?php endforeach; ?>\n";
  $php .= "    </div>\n";
  $php .= "    <div style=\"text-align:center;margin-top:1.5rem\"><a href=\"<?php echo \$base_url; ?>noticias\" class=\"btn-hz-g\" style=\"display:inline-flex\">Ver todos los art&iacute;culos &rarr;</a></div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n";
  $php .= "<?php endif; ?>\n\n";

  // ── Mapa de cobertura ──────────────────────────────────────────────
  $php .= "<section class=\"zona-sec\">\n";
  $php .= "  <div class=\"cta-dark-con\">\n";
  $php .= "    <p class=\"zona-lbl\">Zona de cobertura</p>\n";
  $php .= "    <h2>CarolTemp en <span class=\"hl\">{$ciudad}</span></h2>\n";
  $php .= "    <p style=\"margin-bottom:1.5rem;color:#576574\">Atendemos toda la localidad de {$ciudad} (CP {$ciudad_cp}) y municipios limítrofes. Presupuesto gratuito sin compromiso.</p>\n";
  $php .= "    <div style=\"border-radius:12px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.12)\">\n";
  $php .= "      <iframe\n";
  $php .= "        src=\"https://maps.google.com/maps?q={$lat},{$lng}&z=14&output=embed\"\n";
  $php .= "        width=\"100%\" height=\"400\" style=\"border:0;display:block\" allowfullscreen\n";
  $php .= "        loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"\n";
  $php .= "        title=\"Fontaner&iacute;a en {$ciudad} — CarolTemp\">\n";
  $php .= "      </iframe>\n";
  $php .= "    </div>\n";
  $php .= "  </div>\n";
  $php .= "</section>\n\n";

  // ── MARCADOR: fin de la zona editable en TinyMCE ─────────────────
  $php .= "<!-- /editable -->\n";

  // ── SECCIÓN DINÁMICA: proyectos + artículos ────────────────────────
  $ciudad_q = addslashes($ciudad);
  $php .= "<?php\n";
  $php .= "\$_proy = [];\n";
  $php .= "try {\n";
  $php .= "  \$_ps = \$pdo->prepare('SELECT titulo, slug, descripcion, servicio FROM proyectos WHERE publicado=1 AND zona LIKE ? ORDER BY fecha DESC LIMIT 3');\n";
  $php .= "  \$_ps->execute(['%{$ciudad_q}%']);\n";
  $php .= "  \$_proy = \$_ps->fetchAll(PDO::FETCH_ASSOC);\n";
  $php .= "} catch (\\Throwable \$_e) {}\n";
  $php .= "\$_arts = [];\n";
  $php .= "try {\n";
  $php .= "  \$_as = \$pdo->prepare('SELECT titulo, slug, extracto, categoria FROM articulos WHERE publicado=1 AND (zona LIKE ? OR categoria LIKE ?) ORDER BY fecha DESC LIMIT 3');\n";
  $php .= "  \$_as->execute(['%{$ciudad_q}%', '%fontan%']);\n";
  $php .= "  \$_arts = \$_as->fetchAll(PDO::FETCH_ASSOC);\n";
  $php .= "  if (empty(\$_arts)) {\n";
  $php .= "    \$_as2 = \$pdo->query('SELECT titulo, slug, extracto, categoria FROM articulos WHERE publicado=1 ORDER BY fecha DESC LIMIT 3');\n";
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
  $php .= "      <a href=\"tel:+34613429032\" class=\"btn-hz-w\">&#128222; Llamar ahora</a>\n";
  $php .= "      <a href=\"https://wa.me/34613429032\" target=\"_blank\" rel=\"noopener\" class=\"btn-hz-g\">&#128172; WhatsApp</a>\n";
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
  $intro_p2   = htmlspecialchars($data['intro_p2']  ?? "Presupuesto gratuito sin compromiso. Precio cerrado antes de empezar.", ENT_QUOTES, 'UTF-8');
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
  $php .= "      <a href=\"tel:+34613429032\" class=\"btn-hz-w\">&#128222; 613 429 032</a>\n";
  $php .= "      <a href=\"/contacto\" class=\"btn-hz-g\">Solicitar visita</a>\n";
  $php .= "    </div>\n";
  $php .= "    <div class=\"hero-dark-kpis\" style=\"margin-top:2rem\">\n";
  $php .= "      <div class=\"hero-dark-kpi\"><span class=\"hero-dark-kpi-val\">Nubeco</span><span class=\"hero-dark-kpi-lbl\">Instalador oficial en {$ch}</span></div>\n";
  $php .= "      <div class=\"hero-dark-kpi\"><span class=\"hero-dark-kpi-val\">100%</span><span class=\"hero-dark-kpi-lbl\">Precio cerrado siempre</span></div>\n";
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
  $php .= "    <div class=\"dif-item\"><span class=\"dif-val\">&#128176; Precio cerrado</span><span class=\"dif-lbl\">Antes de empezar</span></div>\n";
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
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">Tel&eacute;fono</span><span class=\"zona-ir-v\"><a href=\"tel:+34613429032\">613 429 032</a></span></div>\n";
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">WhatsApp</span><span class=\"zona-ir-v\"><a href=\"https://wa.me/34613429032\">Escribir ahora &rarr;</a></span></div>\n";
  $php .= "          <div class=\"zona-ir\"><span class=\"zona-ir-l\">Horario</span><span class=\"zona-ir-v\">Lun&ndash;Vie 8&ndash;20h &middot; S&aacute;b 9&ndash;14h</span></div>\n";
  $php .= "          <a href=\"tel:+34613429032\" class=\"zona-icard-btn\">&#128222; Llamar ahora</a>\n";
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
  $php .= "      <a href=\"/fontanero/{$slug_h}/urgencias\" class=\"zona-sc\"><span class=\"zona-sc-n\">01</span><h3>Fontanero urgente en {$ch}</h3><p>Roturas de tuber&iacute;as, grifos, cisternas y p&eacute;rdidas de agua con soluci&oacute;n r&aacute;pida y precio cerrado.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "      <a href=\"/fontanero/{$slug_h}/busqueda_fugas\" class=\"zona-sc\"><span class=\"zona-sc-n\">02</span><h3>B&uacute;squeda de fugas en {$ch}</h3><p>Localizaci&oacute;n de fugas con ge&oacute;fono y c&aacute;mara sin romper innecesariamente.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "      <a href=\"/fontanero/{$slug_h}/desatascos\" class=\"zona-sc\"><span class=\"zona-sc-n\">03</span><h3>Desatascos en {$ch}</h3><p>Desatascos de fregaderos, bajantes y arquetas para recuperar el funcionamiento normal.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "      <a href=\"/servicios#termos\" class=\"zona-sc\"><span class=\"zona-sc-n\">04</span><h3>Termos el&eacute;ctricos en {$ch}</h3><p>Instalaci&oacute;n de termos el&eacute;ctricos con asesoramiento y puesta en marcha.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "      <a href=\"/servicios#descalcificadores\" class=\"zona-sc\"><span class=\"zona-sc-n\">05</span><h3>Descalcificadores en {$ch}</h3><p>Soluci&oacute;n para el agua dura con instalaci&oacute;n y mantenimiento de descalcificadores.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
  $php .= "      <a href=\"/servicios#reformas\" class=\"zona-sc\"><span class=\"zona-sc-n\">06</span><h3>Reformas de ba&ntilde;o en {$ch}</h3><p>Reformas completas o parciales con precio cerrado antes de empezar.</p><span class=\"zona-sc-a\">Ver servicio &rarr;</span></a>\n";
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
  $php .= "  \$_ps = \$pdo->prepare('SELECT titulo, slug, descripcion, servicio FROM proyectos WHERE publicado=1 AND zona LIKE ? ORDER BY fecha DESC LIMIT 3');\n";
  $php .= "  \$_ps->execute(['%{$ciudad_q}%']);\n";
  $php .= "  \$_proy = \$_ps->fetchAll(PDO::FETCH_ASSOC);\n";
  $php .= "} catch (\\Throwable \$_e) {}\n";
  $php .= "\$_arts = [];\n";
  $php .= "try {\n";
  $php .= "  \$_as = \$pdo->prepare('SELECT titulo, slug, extracto, categoria FROM articulos WHERE publicado=1 AND (zona LIKE ? OR categoria LIKE ?) ORDER BY fecha DESC LIMIT 3');\n";
  $php .= "  \$_as->execute(['%{$ciudad_q}%', '%fontan%']);\n";
  $php .= "  \$_arts = \$_as->fetchAll(PDO::FETCH_ASSOC);\n";
  $php .= "  if (empty(\$_arts)) {\n";
  $php .= "    \$_as2 = \$pdo->query('SELECT titulo, slug, extracto, categoria FROM articulos WHERE publicado=1 ORDER BY fecha DESC LIMIT 3');\n";
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
  $php .= "      <a href=\"tel:+34613429032\" class=\"btn-hz-w\">&#128222; Llamar ahora</a>\n";
  $php .= "      <a href=\"https://wa.me/34613429032\" target=\"_blank\" rel=\"noopener\" class=\"btn-hz-g\">&#128172; WhatsApp</a>\n";
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
    $ck = $pdo->prepare('SELECT id FROM paginas WHERE filepath = ? LIMIT 1');
    $ck->execute([$filepath_rel]);
    if (!$ck->fetchColumn()) {
      $ins = $pdo->prepare('INSERT INTO paginas (titulo, slug, filepath, contenido, publicado) VALUES (?, ?, ?, ?, 1)');
      $ins->execute([$titulo_cms, $slug_cms, $filepath_rel, '']);
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
// Acción no reconocida
// ─────────────────────────────────────────────────────────────────────
echo json_encode(['error' => 'Acción no reconocida: ' . htmlspecialchars($accion)]);
exit;
