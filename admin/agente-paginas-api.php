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

  // ── System prompt ─────────────────────────────────────────────────
  $system = 'Eres un experto en SEO local para CarolTemp (fontanería y climatización en Alicante, España). ' .
    'Genera contenido optimizado para páginas PHP de servicio. ' .
    'NUNCA inventes estadísticas, años de experiencia ni precios específicos. ' .
    'NUNCA menciones Villena. ' .
    'Devuelve ÚNICAMENTE JSON válido, sin markdown, sin bloques de código, sin texto extra. ' .
    'Usa comillas simples para atributos HTML dentro de los valores JSON.';

  // ── User prompt ───────────────────────────────────────────────────
  $servicio_nombre = $tipo_cfg['nombre'];
  $accion_texto    = ($accion === 'mejorar' && $contenido_actual)
    ? 'MEJORAR la página existente (el contenido actual es provisional, genera contenido SEO real y optimizado)'
    : 'CREAR desde cero la página';

  $user_msg  = "Acción: {$accion_texto}\n";
  $user_msg .= "Servicio: {$servicio_nombre}\n";
  $user_msg .= "Ciudad: {$ciudad}\n";
  $user_msg .= "CP: {$ciudad_cp}\n";
  $user_msg .= "Slug ciudad: {$ciudad_slug}\n\n";

  if ($contenido_actual) {
    // Limitar a 3000 chars para no saturar el contexto
    $contenido_truncado = mb_substr($contenido_actual, 0, 3000, 'UTF-8');
    $user_msg .= "Contenido actual del archivo (para referencia de estructura):\n";
    $user_msg .= "---\n{$contenido_truncado}\n---\n\n";
  }

  $user_msg .= "Genera el JSON con estos campos exactos:\n";
  $user_msg .= "- meta_title: máx 60 caracteres, keyword + ciudad + CarolTemp\n";
  $user_msg .= "- meta_desc: 150-160 caracteres exactos\n";
  $user_msg .= "- hero_titulo: título hero con HTML, usa <br> y <span class='hl'>diferenciador.</span>\n";
  $user_msg .= "- hero_sub: subtítulo hero (1-2 frases)\n";
  $user_msg .= "- contenido_intro: 2 párrafos HTML con <p>...</p>, contenido real sin provisional\n";
  $user_msg .= "- servicios_lista: array de 6 strings con el nombre del servicio específico en {$ciudad}\n";
  $user_msg .= "- problemas_zona: array de 3 objetos {titulo, texto} específicos para {$ciudad}\n";
  $user_msg .= "- faq: array de 4 objetos {pregunta, respuesta} — preguntas reales de búsqueda\n";
  $user_msg .= "- contenido_extra: string HTML adicional (puede ser vacío)\n";

  // ── Llamada a Claude ──────────────────────────────────────────────
  $payload = [
    'model'      => ANTHROPIC_MODEL,
    'max_tokens' => 6000,
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
    echo json_encode(['error' => 'La respuesta fue demasiado larga y se cortó. Inténtalo de nuevo.']);
    exit;
  }

  // Prepend { porque usamos prefill
  $json_str = '{' . $text;
  $data     = json_decode($json_str, true);

  // Fallback: extraer JSON si hay texto extra
  if (!$data) {
    if (preg_match('/\{[\s\S]*\}/u', $json_str, $m)) {
      $data = json_decode($m[0], true);
    }
  }

  if (!$data) {
    echo json_encode(['error' => 'La IA no devolvió JSON válido. Inténtalo de nuevo.', 'raw' => substr($json_str, 0, 500)]);
    exit;
  }

  // Construir ciudades_cercanas (todas las demás ciudades)
  $otras_ciudades = [];
  foreach ($ciudades as $c_nombre => $c_info) {
    if ($c_info['slug'] === $ciudad_slug) continue;
    $otras_ciudades[] = [
      'nombre'  => $c_nombre,
      'slug'    => $c_info['slug'],
      'prefijo' => $tipo_cfg['prefijo_url'],
    ];
  }

  // Generar el PHP file como string
  $php_contenido = generar_php_servicio($data, $tipo_cfg, $tipo, $ciudad, $ciudad_slug, $ciudad_cp, $otras_ciudades);

  echo json_encode([
    'ok'           => true,
    'data'         => $data,
    'php_contenido' => $php_contenido,
    'filepath'     => $tipo_cfg['dir'] . '/' . $filename,
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

// ═════════════════════════════════════════════════════════════════════
// FUNCIÓN: generar_php_servicio
// Convierte el JSON de Claude en un archivo PHP completo
// ═════════════════════════════════════════════════════════════════════
function generar_php_servicio($data, $tipo_cfg, $tipo, $ciudad, $ciudad_slug, $ciudad_cp, $otras_ciudades) {
  // Extraer valores con fallbacks
  $servicio_nombre = $tipo_cfg['nombre'];
  $servicio_slug   = $tipo;

  $meta_title      = $data['meta_title']      ?? '';
  $meta_desc       = $data['meta_desc']       ?? '';
  $hero_titulo     = $data['hero_titulo']     ?? $servicio_nombre . ' en ' . $ciudad . '<br><span class=\'hl\'>sin obras.</span>';
  $hero_sub        = $data['hero_sub']        ?? '';
  $contenido_intro = $data['contenido_intro'] ?? '<p>Contenido en preparación.</p>';
  $contenido_extra = $data['contenido_extra'] ?? '';

  $servicios_lista = $data['servicios_lista'] ?? [];
  $problemas_zona  = $data['problemas_zona']  ?? [];
  $faq             = $data['faq']             ?? [];

  // Usar var_export para todos los valores (evita problemas con comillas)
  $servicio_nombre_exp = var_export($servicio_nombre, true);
  $servicio_slug_exp   = var_export($servicio_slug,   true);
  $ciudad_exp          = var_export($ciudad,          true);
  $ciudad_slug_exp     = var_export($ciudad_slug,     true);
  $ciudad_cp_exp       = var_export($ciudad_cp,       true);
  $meta_title_exp      = var_export($meta_title,      true);
  $meta_desc_exp       = var_export($meta_desc,       true);
  $hero_titulo_exp     = var_export($hero_titulo,     true);
  $hero_sub_exp        = var_export($hero_sub,        true);
  $contenido_intro_exp = var_export($contenido_intro, true);
  $contenido_extra_exp = var_export($contenido_extra, true);
  $servicios_export    = var_export($servicios_lista, true);
  $problemas_export    = var_export($problemas_zona,  true);
  $faq_export          = var_export($faq,             true);
  $ciudades_export     = var_export($otras_ciudades,  true);

  // Nombre del archivo de comentario
  $file_comment = strtoupper($servicio_nombre) . ' EN ' . strtoupper($ciudad);
  $url_comment  = '/' . $tipo_cfg['dir'] . '/' . $tipo_cfg['prefijo_url'] . '-' . $ciudad_slug;

  $php  = "<?php\n";
  $php .= "/**\n";
  $php .= " * {$file_comment}\n";
  $php .= " * {$url_comment}\n";
  $php .= " * Generado por Agente de Páginas — CarolTemp Admin\n";
  $php .= " */\n\n";
  $php .= "\$servicio_nombre = {$servicio_nombre_exp};\n";
  $php .= "\$servicio_slug   = {$servicio_slug_exp};\n";
  $php .= "\$ciudad          = {$ciudad_exp};\n";
  $php .= "\$ciudad_slug     = {$ciudad_slug_exp};\n";
  $php .= "\$ciudad_cp       = {$ciudad_cp_exp};\n\n";
  $php .= "\$meta_title = {$meta_title_exp};\n";
  $php .= "\$meta_desc  = {$meta_desc_exp};\n\n";
  $php .= "\$hero_titulo = {$hero_titulo_exp};\n";
  $php .= "\$hero_sub    = {$hero_sub_exp};\n\n";
  $php .= "\$contenido_intro = {$contenido_intro_exp};\n\n";
  $php .= "\$servicios_lista = {$servicios_export};\n\n";
  $php .= "\$problemas_zona = {$problemas_export};\n\n";
  $php .= "\$faq = {$faq_export};\n\n";
  $php .= "\$contenido_extra = {$contenido_extra_exp};\n\n";
  $php .= "\$ciudades_cercanas = {$ciudades_export};\n\n";
  $php .= "include __DIR__ . '/../includes/plantilla-servicio.php';\n";

  return $php;
}
