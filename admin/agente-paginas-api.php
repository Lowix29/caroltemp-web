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

// ── Tipos de servicio y sus patrones de archivo ─────────────────────
$tipos_servicio = [
  'fugas'      => ['dir' => 'fugas',      'prefijo_archivo' => 'deteccion-fugas-', 'prefijo_url' => 'deteccion-fugas', 'nombre' => 'Detección de fugas'],
  'desatascos' => ['dir' => 'desatascos', 'prefijo_archivo' => 'desatascos-',      'prefijo_url' => 'desatascos',      'nombre' => 'Desatascos'],
  'fontanero'  => ['dir' => 'fontanero',  'prefijo_archivo' => 'fontanero-',       'prefijo_url' => 'fontanero',       'nombre' => 'Fontanero'],
];

$site_root = dirname(__DIR__);
$accion    = trim($_POST['accion'] ?? '');

// ── Allowed dirs for guardar (security) ─────────────────────────────
$allowed_dirs = ['fugas', 'desatascos', 'fontanero', 'zonas'];

// ─────────────────────────────────────────────────────────────────────
// ACCIÓN: inventario
// ─────────────────────────────────────────────────────────────────────
if ($accion === 'inventario') {
  $matriz = [];

  foreach ($ciudades as $ciudad_nombre => $info) {
    $slug = $info['slug'];
    $cp   = $info['cp'];
    $row  = [
      'ciudad'   => $ciudad_nombre,
      'slug'     => $slug,
      'cp'       => $cp,
      'servicios' => [],
    ];

    // Comprobar páginas de servicio (fugas, desatascos, fontanero)
    foreach ($tipos_servicio as $tipo_key => $tipo_cfg) {
      $filename  = $tipo_cfg['prefijo_archivo'] . $slug . '.php';
      $filepath  = $site_root . '/' . $tipo_cfg['dir'] . '/' . $filename;
      $ruta_web  = '/' . $tipo_cfg['dir'] . '/' . $tipo_cfg['prefijo_url'] . '-' . $slug;

      $existe      = file_exists($filepath);
      $provisional = false;

      if ($existe) {
        $contenido   = file_get_contents($filepath);
        $provisional = (strpos($contenido, 'CONTENIDO PROVISIONAL') !== false);
      }

      $row['servicios'][$tipo_key] = [
        'existe'      => $existe,
        'provisional' => $provisional,
        'ruta_web'    => $ruta_web,
        'filepath'    => $tipo_cfg['dir'] . '/' . $filename,
      ];
    }

    // Comprobar página de zona
    $zona_filename = $slug . '.php';
    $zona_filepath = $site_root . '/zonas/' . $zona_filename;
    $zona_ruta_web = '/zonas/' . $slug;

    $zona_existe      = file_exists($zona_filepath);
    $zona_provisional = false;
    if ($zona_existe) {
      $zona_contenido   = file_get_contents($zona_filepath);
      $zona_provisional = (strpos($zona_contenido, 'CONTENIDO PROVISIONAL') !== false);
    }

    $row['servicios']['zona'] = [
      'existe'      => $zona_existe,
      'provisional' => $zona_provisional,
      'ruta_web'    => $zona_ruta_web,
      'filepath'    => 'zonas/' . $zona_filename,
    ];

    $matriz[] = $row;
  }

  echo json_encode(['ok' => true, 'matriz' => $matriz]);
  exit;
}

// ─────────────────────────────────────────────────────────────────────
// ACCIÓN: mejorar / crear (llamada a Claude)
// ─────────────────────────────────────────────────────────────────────
if ($accion === 'mejorar' || $accion === 'crear') {
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

  if (!$tipo || !$ciudad || !$ciudad_slug) {
    echo json_encode(['error' => 'Faltan parámetros requeridos: tipo, ciudad, ciudad_slug']);
    exit;
  }
  if (!isset($tipos_servicio[$tipo])) {
    echo json_encode(['error' => 'Tipo de servicio no válido: ' . $tipo]);
    exit;
  }
  if (!isset($ciudades[$ciudad])) {
    echo json_encode(['error' => 'Ciudad no válida: ' . $ciudad]);
    exit;
  }

  $tipo_cfg  = $tipos_servicio[$tipo];
  $filename  = $tipo_cfg['prefijo_archivo'] . $ciudad_slug . '.php';
  $filepath  = $site_root . '/' . $tipo_cfg['dir'] . '/' . $filename;

  // Leer el contenido actual si existe
  $contenido_actual = '';
  if (file_exists($filepath)) {
    $contenido_actual = file_get_contents($filepath);
  }

  // ── Construir ciudades cercanas para el footer ───────────────────
  $otras_ciudades_links = '';
  foreach ($ciudades as $c_nombre => $c_info) {
    if ($c_info['slug'] === $ciudad_slug) continue;
    $url_ciudad = $tipo_cfg['prefijo_url'] . '-' . $c_info['slug'];
    $otras_ciudades_links .= "      <a href=\"<?php echo \$base_url; ?>{$tipo_cfg['dir']}/{$url_ciudad}\" class=\"zona-ztag\">{$c_nombre}</a>\n";
  }

  // KPIs y strip específicos por tipo de servicio
  $kpis_hero = [
    'fugas'      => "<div class='hero-dark-kpi'><span class='hero-dark-kpi-val'>Sin obras</span><span class='hero-dark-kpi-lbl'>Geófono y cámara</span></div><div class='hero-dark-kpi'><span class='hero-dark-kpi-val'>24h</span><span class='hero-dark-kpi-lbl'>Urgencias</span></div><div class='hero-dark-kpi'><span class='hero-dark-kpi-val'>0€</span><span class='hero-dark-kpi-lbl'>Presupuesto gratis</span></div>",
    'desatascos' => "<div class='hero-dark-kpi'><span class='hero-dark-kpi-val'>Urgente</span><span class='hero-dark-kpi-lbl'>Hoy mismo</span></div><div class='hero-dark-kpi'><span class='hero-dark-kpi-val'>Cámara</span><span class='hero-dark-kpi-lbl'>Inspección incluida</span></div><div class='hero-dark-kpi'><span class='hero-dark-kpi-val'>0€</span><span class='hero-dark-kpi-lbl'>Presupuesto gratis</span></div>",
    'fontanero'  => "<div class='hero-dark-kpi'><span class='hero-dark-kpi-val'>Rápido</span><span class='hero-dark-kpi-lbl'>Mismo día</span></div><div class='hero-dark-kpi'><span class='hero-dark-kpi-val'>100%</span><span class='hero-dark-kpi-lbl'>Precio cerrado</span></div><div class='hero-dark-kpi'><span class='hero-dark-kpi-val'>0€</span><span class='hero-dark-kpi-lbl'>Presupuesto gratis</span></div>",
  ];
  $strip_items = [
    'fugas'      => "<div class='dif-item'><span class='dif-val'>🔍 Sin obras</span><span class='dif-lbl'>Geófono y cámara</span></div><div class='dif-item'><span class='dif-val'>⚡ Urgencias</span><span class='dif-lbl'>24h disponible</span></div><div class='dif-item'><span class='dif-val'>💰 Precio cerrado</span><span class='dif-lbl'>Antes de empezar</span></div><div class='dif-item'><span class='dif-val'>📍 Comarca</span><span class='dif-lbl'>Somos de aquí</span></div>",
    'desatascos' => "<div class='dif-item'><span class='dif-val'>⚡ Urgente</span><span class='dif-lbl'>Atención el mismo día</span></div><div class='dif-item'><span class='dif-val'>🎥 Cámara</span><span class='dif-lbl'>Diagnóstico visual</span></div><div class='dif-item'><span class='dif-val'>💰 Precio cerrado</span><span class='dif-lbl'>Antes de empezar</span></div><div class='dif-item'><span class='dif-val'>📍 Comarca</span><span class='dif-lbl'>Somos de aquí</span></div>",
    'fontanero'  => "<div class='dif-item'><span class='dif-val'>⚡ Rápido</span><span class='dif-lbl'>Mismo día disponible</span></div><div class='dif-item'><span class='dif-val'>🔧 Todo tipo</span><span class='dif-lbl'>Reparaciones e instalaciones</span></div><div class='dif-item'><span class='dif-val'>💰 Precio cerrado</span><span class='dif-lbl'>Antes de empezar</span></div><div class='dif-item'><span class='dif-val'>📍 Comarca</span><span class='dif-lbl'>Somos de aquí</span></div>",
  ];

  // ── System prompt ─────────────────────────────────────────────────
  $anyo = date('Y');
  $system = <<<SYS
Eres un especialista en landing pages para CarolTemp, empresa de fontanería en Alicante (España).
Tu trabajo es generar páginas de aterrizaje que CAPTUREN CLIENTES, no artículos informativos.

REGLAS ABSOLUTAS:
- NUNCA escribas "Vinalopó" en ningún sitio
- NUNCA inventes estadísticas, años de experiencia, número de clientes ni precios fijos
- NO hagas paredes de texto — las landing pages capturan con visual, no con texto
- El teléfono visible es más importante que cualquier párrafo
- Año actual: {$anyo}

SOBRE CAROLTEMP:
- Fontanería y climatización en la comarca interior de Alicante
- Diferenciadores reales: presupuesto gratuito sin compromiso, urgencias 24h, instaladores certificados
- Tel: 613 429 032 / WhatsApp: 34613429032
- Servicios: fontanería urgente, fugas, desatascos, termos, aire acondicionado, reformas de baño

DISEÑO: El sitio usa estas clases CSS (ÚSALAS, están ya definidas):
- hz-dark / hz-dark-con / hz-dark-tag / hz-dark-sub / hz-dark-btns / btn-hz-w / btn-hz-g — sección hero oscura
- hero-dark-kpis / hero-dark-kpi / hero-dark-kpi-val / hero-dark-kpi-lbl — KPIs en hero
- dif-strip / dif-strip-in / dif-item / dif-val / dif-lbl — franja de diferenciadores
- zona-sec / zona-sec-gray / cta-dark-con — secciones de contenido
- zona-tcol — layout 2 columnas (texto + tarjeta contacto)
- zona-lbl / zona-prose / zona-chk — etiqueta sección, texto principal, checklist con ticks
- zona-icard / zona-icard-h / zona-ir / zona-ir-l / zona-ir-v / zona-icard-btn — tarjeta de contacto
- fg-cards / fg-card / fg-badge / fg-top / fg-chk / fg-btn / fg-lbl — sección de fugas con cards
- zona-ig / zona-ip — galería de imágenes en grid
- zona-fi / zona-fiq / zona-fia — acordeón FAQ (zona-fi open = abierto por defecto)
- zona-ztags / zona-ztag — links a otras zonas
- cta-dark / cta-dark-con / cta-dark-btns / cta-dark-tel — CTA final oscuro
- hl — texto resaltado azul (dentro de h1/h2)

FORMATO DE SALIDA: Devuelve ÚNICAMENTE el contenido PHP del archivo, listo para escribir en disco.
Sin markdown, sin bloques de código, sin explicaciones. Empieza directamente con "<?php"
SYS;

  // ── User prompt con plantilla ─────────────────────────────────────
  $servicio_nombre = $tipo_cfg['nombre'];

  // Instrucciones específicas por tipo de servicio
  $instrucciones_tipo = [
    'fugas' => "SECCIONES para página de DETECCIÓN DE FUGAS:
1. Hero oscuro: H1 con 'Detección de fugas en {$ciudad}' + '<span class=\"hl\">sin obras.</span>'. Subhead mencionando geófono y cámara.
2. Franja dif-strip: los 4 items ya están definidos en el template
3. Sección zona-tcol: intro en 2 frases cortas + lista zona-chk (6 items tipo de fugas que detectan) + zona-icard con contacto
4. Sección fg-cards con 3 cards: 'Geófono acústico' / 'Cámara endoscópica' / 'Sin obras innecesarias'
5. Sección lista de casuísticas: zona-chk con 6-8 tipos de fugas en {$ciudad} (tuberías, suelo, calefacción, piscina...)
6. FAQ con 4 preguntas de búsqueda real sobre fugas en {$ciudad}
7. Otras ciudades (zona-ztags)
8. CTA final fuerte",
    'desatascos' => "SECCIONES para página de DESATASCOS:
1. Hero: H1 'Desatascos en {$ciudad}' + '<span class=\"hl\">urgentes.</span>'
2. Franja dif-strip
3. Sección zona-tcol: intro en 2 frases + zona-chk con 6 tipos de desatascos (fregadero, bañera, bajante, arqueta...) + zona-icard
4. Sección fg-cards con 3 cards: 'Fregaderos y cocina' / 'Bajantes y tuberías' / 'Arquetas y saneamiento'
5. Sección casuísticas con zona-chk
6. FAQ 4 preguntas sobre desatascos en {$ciudad}
7. Otras ciudades
8. CTA final",
    'fontanero' => "SECCIONES para página de FONTANERO:
1. Hero: H1 'Fontanero en {$ciudad}' + '<span class=\"hl\">urgencias 24h.</span>'
2. Franja dif-strip
3. Sección zona-tcol: intro 2 frases + zona-chk con 6 tipos de trabajos + zona-icard
4. Sección zona-svc con 4-6 cards de servicios: Reparaciones / Fugas / Termos / Reformas / Desatascos / Climatización (usando las URLs correctas del sitio)
5. FAQ 4 preguntas sobre fontanero en {$ciudad}
6. Otras ciudades
7. CTA final",
  ];

  $user_msg = "Genera el archivo PHP completo para: {$servicio_nombre} en {$ciudad} (CP: {$ciudad_cp})\n\n";
  $user_msg .= $instrucciones_tipo[$tipo] ?? '';
  $user_msg .= "\n\nDATOS DEL ARCHIVO:\n";
  $user_msg .= "- ciudad_slug: {$ciudad_slug}\n";
  $user_msg .= "- URL canónica: https://caroltemp.com/{$tipo_cfg['dir']}/{$tipo_cfg['prefijo_url']}-{$ciudad_slug}\n";
  $user_msg .= "- PHP var disponible en todo el archivo: \$base_url (incluye la barra final)\n\n";
  $user_msg .= "ESTRUCTURA PHP DEL ARCHIVO (sigue este esquema exacto):\n";
  $user_msg .= <<<'TMPL'
<?php
$meta_title  = "..."; // máx 60 chars — keyword + ciudad + CarolTemp
$meta_desc   = "..."; // 150-160 chars exactos
$meta_url    = "https://caroltemp.com/TIPO/PREFIJO-CIUDAD_SLUG";
$schema_type = "zona";
$zona_nombre = "CIUDAD";
$page_css    = "servicio-ciudad";
$page_js     = "zona";
include __DIR__ . '/../includes/head.php';
?>

<!-- HERO -->
<section class="hz-dark" style="min-height:460px">
  <div class="hz-dark-bg"></div>
  <div class="hz-dark-glow"></div>
  <div class="hz-dark-con">
    <div class="hz-dark-tag"><span class="hz-dark-dot"></span>SERVICIO · CIUDAD · CP XXXXX</div>
    <h1>... <span class="hl">...</span></h1>
    <p class="hz-dark-sub">...</p>
    <div class="hz-dark-btns">
      <a href="tel:+34613429032" class="btn-hz-w">📞 613 429 032</a>
      <a href="<?php echo $base_url; ?>contacto" class="btn-hz-g">Pedir presupuesto gratis</a>
    </div>
    <div class="hero-dark-kpis" style="margin-top:2rem">
      [KPI_ITEMS]
    </div>
  </div>
</section>

<!-- STRIP -->
<div class="dif-strip"><div class="dif-strip-in">[STRIP_ITEMS]</div></div>

[SECCIONES DE CONTENIDO VISUAL]

<!-- OTRAS CIUDADES -->
<section class="zona-sec zona-sec-gray">
  <div class="cta-dark-con">
    <p class="zona-lbl">Comarca interior de Alicante</p>
    <h2>También hacemos SERVICIO en <span class="hl">otras zonas</span></h2>
    <div class="zona-ztags">
[LINKS_OTRAS_CIUDADES]    </div>
  </div>
</section>

<!-- CTA FINAL -->
<section class="cta-dark">
  <div class="cta-dark-con">
    <h2>¿Necesitas SERVICIO <span>en CIUDAD?</span></h2>
    <p>Llámanos o escríbenos. Presupuesto gratis sin compromiso.</p>
    <div class="cta-dark-btns">
      <a href="tel:+34613429032" class="btn-hz-w">📞 Llamar ahora</a>
      <a href="https://wa.me/34613429032" target="_blank" rel="noopener" class="btn-hz-g">💬 WhatsApp</a>
    </div>
    <div class="cta-dark-tel">Teléfono directo<strong>613 429 032</strong></div>
  </div>
</section>

<?php include '../includes/footer.php'; ?>
TMPL;

  $user_msg .= "\n\nEn el template sustituye [KPI_ITEMS] con: " . $kpis_hero[$tipo] . "\n";
  $user_msg .= "Sustituye [STRIP_ITEMS] con: " . $strip_items[$tipo] . "\n";
  $user_msg .= "Sustituye [LINKS_OTRAS_CIUDADES] con:\n" . $otras_ciudades_links;
  $user_msg .= "\nRecuerda: landing page visual, no artículo. Texto mínimo. Cards y listas. CTAs claros. Teléfono visible.";

  // ── Llamada a Claude ──────────────────────────────────────────────
  $payload = [
    'model'      => ANTHROPIC_MODEL,
    'max_tokens' => 8192,
    'system'     => $system,
    'messages'   => [
      ['role' => 'user',      'content' => $user_msg],
      ['role' => 'assistant', 'content' => '<?php'],
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

  // El prefill era "<?php" — Claude continúa desde ahí
  $php_contenido = '<?php' . $text;

  // Limpiar bloques markdown si Claude los añadió
  $php_contenido = preg_replace('/^```php\s*/m', '', $php_contenido);
  $php_contenido = preg_replace('/^```\s*$/m', '', $php_contenido);

  echo json_encode([
    'ok'           => true,
    'php_contenido' => $php_contenido,
    'filepath'     => $tipo_cfg['dir'] . '/' . $filename,
    'meta_title'   => '', // se extrae en el frontend del PHP generado
  ]);
  exit;
}

// ─────────────────────────────────────────────────────────────────────
// ACCIÓN: guardar
// ─────────────────────────────────────────────────────────────────────
if ($accion === 'guardar') {
  $filepath_rel = trim($_POST['filepath'] ?? '');
  $contenido    = $_POST['contenido'] ?? '';

  if (!$filepath_rel || !$contenido) {
    echo json_encode(['error' => 'Faltan parámetros: filepath y contenido']);
    exit;
  }

  // ── Validación de seguridad ───────────────────────────────────────
  // Asegurar que la ruta es relativa y dentro de un directorio permitido
  $filepath_rel = ltrim($filepath_rel, '/');

  // Extraer el directorio raíz de la ruta
  $parts       = explode('/', $filepath_rel);
  $top_dir     = $parts[0] ?? '';

  if (!in_array($top_dir, $allowed_dirs, true)) {
    echo json_encode(['error' => 'Directorio no permitido: ' . $top_dir]);
    exit;
  }

  // Solo .php y sin traversal
  if (!preg_match('/^[a-z0-9\-\/]+\.php$/', $filepath_rel)) {
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

  echo json_encode(['ok' => true, 'bytes' => $bytes, 'filepath' => $filepath_rel]);
  exit;
}

// ─────────────────────────────────────────────────────────────────────
// Acción no reconocida
// ─────────────────────────────────────────────────────────────────────
echo json_encode(['error' => 'Acción no reconocida: ' . htmlspecialchars($accion)]);
exit;
