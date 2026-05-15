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
$plan_file = dirname(__FILE__) . '/plan-auditor.json';

// ── Allowed dirs for guardar/eliminar (security) ────────────────────
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

  $user_msg  = "Ciudad: {$ciudad} (CP: {$ciudad_cp}, slug: {$ciudad_slug})\n";
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
    CURLOPT_TIMEOUT => 60,
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
  $php_contenido = generar_php_servicio($data, $tipo_cfg, $tipo, $ciudad, $ciudad_slug, $ciudad_cp, $otras_ciudades);

  echo json_encode([
    'ok'            => true,
    'php_contenido' => $php_contenido,
    'filepath'      => $tipo_cfg['dir'] . '/' . $filename,
    'meta_title'    => $data['meta_title'] ?? '',
    'meta_desc'     => $data['meta_desc']  ?? '',
  ]);
  exit;
}

// ═════════════════════════════════════════════════════════════════════
// Genera el archivo PHP: variables + include plantilla-servicio.php
// ═════════════════════════════════════════════════════════════════════
function generar_php_servicio($data, $tipo_cfg, $tipo, $ciudad, $ciudad_slug, $ciudad_cp, $otras_ciudades) {
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
  $php .= "include __DIR__ . '/../includes/plantilla-servicio.php';\n";

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
// Acción no reconocida
// ─────────────────────────────────────────────────────────────────────
echo json_encode(['error' => 'Acción no reconocida: ' . htmlspecialchars($accion)]);
exit;
