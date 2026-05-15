<?php
/* =============================================
   CAROLTEMP — Auditor Estratégico: API
   Análisis de arquitectura web y plan de acción SEO
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

$site_root  = dirname(__DIR__);
$plan_file  = dirname(__FILE__) . '/plan-auditor.json';
$accion     = trim($_POST['accion'] ?? '');

// ── Ciudades del sistema ────────────────────────────────────────────
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

// ── Tipos de servicio ────────────────────────────────────────────────
$tipos_servicio = [
  'fugas'      => ['dir' => 'fugas',      'prefijo_archivo' => 'deteccion-fugas-', 'nombre' => 'Detección de fugas'],
  'desatascos' => ['dir' => 'desatascos', 'prefijo_archivo' => 'desatascos-',      'nombre' => 'Desatascos'],
  'fontanero'  => ['dir' => 'fontanero',  'prefijo_archivo' => 'fontanero-',       'nombre' => 'Fontanero'],
];

// ─────────────────────────────────────────────────────────────────────
// Función: construir inventario completo
// ─────────────────────────────────────────────────────────────────────
function construir_inventario($site_root, $ciudades, $tipos_servicio) {
  $paginas = [];

  // Páginas de servicio por ciudad
  foreach ($ciudades as $ciudad_nombre => $info) {
    $slug = $info['slug'];

    foreach ($tipos_servicio as $tipo_key => $tipo_cfg) {
      $filename = $tipo_cfg['prefijo_archivo'] . $slug . '.php';
      $filepath = $site_root . '/' . $tipo_cfg['dir'] . '/' . $filename;
      $rel_path = $tipo_cfg['dir'] . '/' . $filename;

      $existe      = file_exists($filepath);
      $provisional = false;
      if ($existe) {
        $muestra     = file_get_contents($filepath, false, null, 0, 500);
        $provisional = (strpos($muestra, 'CONTENIDO PROVISIONAL') !== false);
      }

      $paginas[] = [
        'tipo'        => 'servicio',
        'servicio'    => $tipo_key,
        'ciudad'      => $ciudad_nombre,
        'filepath'    => $rel_path,
        'existe'      => $existe,
        'provisional' => $provisional,
      ];
    }

    // Página de zona
    $zona_file    = $site_root . '/zonas/' . $slug . '.php';
    $zona_rel     = 'zonas/' . $slug . '.php';
    $zona_existe  = file_exists($zona_file);
    $zona_prov    = false;
    if ($zona_existe) {
      $muestra   = file_get_contents($zona_file, false, null, 0, 500);
      $zona_prov = (strpos($muestra, 'CONTENIDO PROVISIONAL') !== false);
    }

    $paginas[] = [
      'tipo'        => 'zona',
      'servicio'    => 'zona',
      'ciudad'      => $ciudad_nombre,
      'filepath'    => $zona_rel,
      'existe'      => $zona_existe,
      'provisional' => $zona_prov,
    ];
  }

  // Páginas estáticas
  $estaticas = [
    ['nombre' => 'Inicio',    'filepath' => 'index.php'],
    ['nombre' => 'Servicios', 'filepath' => 'servicios/index.php', 'alternativa' => 'servicios.php'],
    ['nombre' => 'Sobre',     'filepath' => 'sobre.php',           'alternativa' => 'sobre/index.php'],
    ['nombre' => 'Contacto',  'filepath' => 'contacto.php'],
  ];

  foreach ($estaticas as $est) {
    $path1   = $site_root . '/' . $est['filepath'];
    $path2   = isset($est['alternativa']) ? ($site_root . '/' . $est['alternativa']) : null;
    $existe  = file_exists($path1) || ($path2 && file_exists($path2));
    $rel     = $est['filepath'];
    if (!file_exists($path1) && $path2 && file_exists($path2)) {
      $rel = $est['alternativa'];
    }

    $provisional = false;
    if ($existe) {
      $fp      = file_exists($path1) ? $path1 : $path2;
      $muestra = file_get_contents($fp, false, null, 0, 500);
      $provisional = (strpos($muestra, 'CONTENIDO PROVISIONAL') !== false);
    }

    $paginas[] = [
      'tipo'        => 'corporativa',
      'servicio'    => strtolower($est['nombre']),
      'ciudad'      => '',
      'filepath'    => $rel,
      'existe'      => $existe,
      'provisional' => $provisional,
    ];
  }

  return $paginas;
}

// ─────────────────────────────────────────────────────────────────────
// Función: construir texto de inventario para Claude
// ─────────────────────────────────────────────────────────────────────
function texto_inventario($paginas) {
  $total       = count($paginas);
  $ok          = 0;
  $provisional = 0;
  $faltantes   = 0;

  $lineas = [];
  foreach ($paginas as $p) {
    if ($p['existe'] && !$p['provisional']) {
      $estado = 'OK';
      $ok++;
    } elseif ($p['existe'] && $p['provisional']) {
      $estado = 'PROVISIONAL';
      $provisional++;
    } else {
      $estado = 'FALTA';
      $faltantes++;
    }

    $desc = $p['filepath'];
    if ($p['ciudad']) {
      $desc .= ' (' . $p['ciudad'] . ')';
    }
    $lineas[] = "[{$estado}] {$desc}";
  }

  $resumen = "Total páginas rastreadas: {$total} | OK: {$ok} | Provisional: {$provisional} | Faltan: {$faltantes}\n\n";
  $resumen .= implode("\n", $lineas);

  return [
    'texto'      => $resumen,
    'total'      => $total,
    'ok'         => $ok,
    'provisional'=> $provisional,
    'faltantes'  => $faltantes,
  ];
}

// ─────────────────────────────────────────────────────────────────────
// ACCIÓN: analizar
// ─────────────────────────────────────────────────────────────────────
if ($accion === 'analizar') {

  if (!function_exists('curl_init')) {
    echo json_encode(['error' => 'curl no está habilitado en PHP.']);
    exit;
  }
  if (!defined('ANTHROPIC_API_KEY') || strlen(ANTHROPIC_API_KEY) < 20) {
    echo json_encode(['error' => 'ANTHROPIC_API_KEY no configurada. Revisa includes/config.php']);
    exit;
  }

  $objetivo   = trim($_POST['objetivo']           ?? '');
  $investigar = intval($_POST['investigar']        ?? 0);
  $kw_raw     = trim($_POST['keywords_investigar'] ?? '');

  if (!$objetivo) {
    echo json_encode(['error' => 'El campo objetivo es obligatorio.']);
    exit;
  }

  // 1. Inventario de páginas
  $paginas    = construir_inventario($site_root, $ciudades, $tipos_servicio);
  $inv        = texto_inventario($paginas);
  $inv_texto  = $inv['texto'];

  // 2. Investigación competitiva con Serper (opcional)
  $serp_contexto = '';
  if ($investigar === 1 && defined('SERPER_API_KEY') && strlen(SERPER_API_KEY) >= 10 && $kw_raw !== '') {
    $keywords = array_slice(
      array_filter(array_map('trim', explode(',', $kw_raw))),
      0, 3
    );

    foreach ($keywords as $kw) {
      $ch = curl_init('https://google.serper.dev/search');
      curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode(['q' => $kw, 'gl' => 'es', 'hl' => 'es', 'num' => 5]),
        CURLOPT_HTTPHEADER     => [
          'X-API-KEY: ' . SERPER_API_KEY,
          'Content-Type: application/json',
        ],
        CURLOPT_TIMEOUT => 15,
      ]);
      $raw_serp  = curl_exec($ch);
      curl_close($ch);

      if ($raw_serp) {
        $serp_data = json_decode($raw_serp, true);
        if (!empty($serp_data['organic'])) {
          $serp_contexto .= "\nKeyword: \"{$kw}\"\n";
          $count = 0;
          foreach ($serp_data['organic'] as $item) {
            if ($count >= 5) break;
            $title = $item['title'] ?? '';
            $link  = $item['link']  ?? '';
            $serp_contexto .= "  - {$title} → {$link}\n";
            $count++;
          }
        }
      }
    }
  }

  // 3. Construir prompt de usuario
  $anyo = date('Y');

  $user_msg  = "BRIEFING DEL CLIENTE:\n{$objetivo}\n\n";
  $user_msg .= "ESTADO ACTUAL DE LA WEB (escaneado automáticamente):\n{$inv_texto}\n";

  if ($serp_contexto) {
    $user_msg .= "\nDATOS SERP (competidores actuales en Google España):\n{$serp_contexto}\n";
  }

  // 4. System prompt
  $system = <<<SYS
Eres el Director de Estrategia SEO de una agencia digital especializada en negocios locales de fontanería y climatización en España. Llevas meses trabajando con CarolTemp y conoces el negocio al detalle. Año actual: {$anyo}.

## CONOCIMIENTO PROFUNDO DEL CLIENTE: CAROLTEMP

**El negocio:**
CarolTemp es una empresa familiar de fontanería y climatización con sede en Elda (Alicante). Cubren toda la comarca del interior de Alicante: Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Monforte del Cid, Salinas y Aspe. No trabajan en Villena ni en la costa (Benidorm, Alicante ciudad, etc.).

**Servicios que ofrecen:**
- Detección de fugas de agua (con geófono acústico y cámara endoscópica — sin abrir paredes ni levantar suelos)
- Desatascos urgentes (fregaderos, lavabos, bajantes, arquetas, colectores)
- Fontanero urgente 24h (reparaciones, grifos, cisternas, tuberías)
- Instalación y sustitución de termos eléctricos y de gas
- Descalcificadores y ósmosis inversa
- Reformas de baño completas e instalaciones
- Aire acondicionado (instalación splits, mantenimiento)
- Bombas de achique

**Diferenciadores reales (SOLO estos, no inventar más):**
- Presupuesto gratuito sin compromiso ANTES de empezar cualquier trabajo
- Urgencias disponibles los 7 días
- Instaladores certificados (Nubeco para climatización)
- Financiación disponible para trabajos grandes

**Contacto:** Tel: 613 429 032 / 611 165 129 | caroltemp.com

**Perfil del cliente de CarolTemp:**
- Particular con fuga de agua urgente que busca en Google desde el móvil
- Comunidad de vecinos con atasco en bajante
- Propietario que necesita cambiar el termo
- Alguien que lleva semanas con humedad sin saber de dónde viene

**Competencia local:** principalmente fontaneros individuales sin web, empresas de mantenimiento generalistas. CarolTemp tiene ventaja en SEO local si trabaja bien la estructura web.

**PROHIBIDO ABSOLUTO:** nunca escribas "Vinalopó" en ningún sitio del plan.

## TU ROL EN ESTA SESIÓN

Actúas como el estratega que lleva la cuenta. El cliente te da un briefing o una pregunta. Tú:
1. Analizas la arquitectura web actual (ya la tienes en el mensaje de usuario)
2. La comparas con lo que debería existir para dominar los primeros resultados de Google
3. Propones un plan de acción concreto y priorizado
4. Si el briefing no especifica algo, tú decides lo mejor para el negocio basándote en tu experiencia

**Criterios de priorización SEO local:**
- Las páginas de servicio específicas por ciudad posicionan MEJOR que páginas genéricas
- Elda y Petrer tienen más población y más búsquedas — van primero
- Las páginas con 'CONTENIDO PROVISIONAL' son riesgo de thin content — priorizar mejora
- Páginas que no existen = oportunidad perdida directa
- Arquitectura ideal: 1 página por combinación servicio×ciudad + 1 página de zona por ciudad + páginas de categoría (índice de fugas, índice de desatascos, etc.)

## REGLAS DEL PLAN

Acciones posibles:
- CREAR — página que no existe y debería existir
- MEJORAR — existe pero tiene contenido provisional o pobre
- REDIRIGIR — URL incorrecta o duplicada, necesita 301
- ELIMINAR — página sin valor, canibalización, duplicado
- MANTENER — está bien, no tocar

Sé muy concreto. Cada acción = una URL específica + un motivo de 1-2 frases que el cliente entienda.

## FORMATO DE RESPUESTA

DEVUELVE ÚNICAMENTE JSON VÁLIDO. Sin markdown, sin texto antes ni después:
{
  "resumen": "2-3 frases del estado actual y diagnóstico principal desde perspectiva de agencia",
  "diagnostico": "Análisis detallado: problemas encontrados, riesgos SEO, oportunidades perdidas (4-6 líneas)",
  "arquitectura_ideal": "Cómo debería ser la arquitectura web completa para dominar Google local en esta comarca (5-8 líneas)",
  "estadisticas": {
    "paginas_ok": N,
    "paginas_provisional": N,
    "paginas_faltantes": N,
    "paginas_total_ideal": N
  },
  "plan": [
    {
      "id": 1,
      "accion": "CREAR|MEJORAR|REDIRIGIR|ELIMINAR|MANTENER",
      "prioridad": "alta|media|baja",
      "impacto": "muy_alto|alto|medio|bajo",
      "tipo": "servicio|zona|corporativa|indice",
      "pagina": "ruta/relativa/al-archivo.php",
      "titulo_sugerido": "Título H1 propuesto o null si MANTENER/ELIMINAR",
      "motivo": "Por qué esta acción, en 1-2 frases directas como hablaría un estratega SEO",
      "desde": null,
      "hacia": null
    }
  ],
  "recomendaciones": "Consejos adicionales: interlinking, schema markup, velocidad, etc. (3-5 líneas)"
}
SYS;

  // 5. Llamada a Claude API (prefill { para JSON puro)
  $payload = [
    'model'      => ANTHROPIC_MODEL,
    'max_tokens' => 4096,
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
    CURLOPT_TIMEOUT => 90,
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
    echo json_encode(['error' => 'La respuesta fue demasiado larga y se cortó. Inténtalo de nuevo con un objetivo más corto.']);
    exit;
  }

  // Reconstruir JSON (prefill era "{")
  $json_str = '{' . $text;
  $plan     = json_decode($json_str, true);

  if (!$plan) {
    if (preg_match('/\{[\s\S]*\}/u', $json_str, $m)) {
      $plan = json_decode($m[0], true);
    }
  }

  if (!$plan) {
    echo json_encode(['error' => 'Claude no devolvió JSON válido. Inténtalo de nuevo.', 'raw' => substr($json_str, 0, 500)]);
    exit;
  }

  // Añadir metadatos al plan e inicializar estado de acciones
  $plan['fecha_generacion'] = date('c');
  $plan['objetivo']         = $objetivo;

  if (!empty($plan['plan']) && is_array($plan['plan'])) {
    foreach ($plan['plan'] as &$item) {
      if (!isset($item['estado'])) {
        $item['estado'] = 'pendiente';
      }
    }
    unset($item);
  }

  // 6. Guardar plan en disco
  file_put_contents($plan_file, json_encode($plan, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

  echo json_encode(['ok' => true, 'plan' => $plan]);
  exit;
}

// ─────────────────────────────────────────────────────────────────────
// ACCIÓN: guardar_plan
// ─────────────────────────────────────────────────────────────────────
if ($accion === 'guardar_plan') {
  $plan_str = $_POST['plan'] ?? '';
  if (!$plan_str) {
    echo json_encode(['error' => 'No se recibió ningún plan.']);
    exit;
  }

  $plan = json_decode($plan_str, true);
  if (!$plan) {
    echo json_encode(['error' => 'El plan recibido no es JSON válido.']);
    exit;
  }

  file_put_contents($plan_file, json_encode($plan, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
  echo json_encode(['ok' => true]);
  exit;
}

// ─────────────────────────────────────────────────────────────────────
// ACCIÓN: cargar_plan
// ─────────────────────────────────────────────────────────────────────
if ($accion === 'cargar_plan') {
  if (!file_exists($plan_file)) {
    echo json_encode(['ok' => false, 'error' => 'No hay plan guardado']);
    exit;
  }

  $contenido = file_get_contents($plan_file);
  $plan      = json_decode($contenido, true);

  if (!$plan) {
    echo json_encode(['ok' => false, 'error' => 'El plan guardado está corrupto']);
    exit;
  }

  echo json_encode(['ok' => true, 'plan' => $plan]);
  exit;
}

// ─────────────────────────────────────────────────────────────────────
// ACCIÓN: actualizar_accion
// ─────────────────────────────────────────────────────────────────────
if ($accion === 'actualizar_accion') {
  $id     = intval($_POST['id']     ?? 0);
  $estado = trim($_POST['estado']   ?? '');

  $estados_validos = ['pendiente', 'completado', 'ignorado'];
  if (!$id || !in_array($estado, $estados_validos, true)) {
    echo json_encode(['error' => 'Parámetros inválidos: id y estado requeridos.']);
    exit;
  }

  if (!file_exists($plan_file)) {
    echo json_encode(['error' => 'No hay plan guardado.']);
    exit;
  }

  $plan = json_decode(file_get_contents($plan_file), true);
  if (!$plan || empty($plan['plan'])) {
    echo json_encode(['error' => 'Plan inválido o vacío.']);
    exit;
  }

  $encontrado = false;
  foreach ($plan['plan'] as &$item) {
    if (intval($item['id'] ?? 0) === $id) {
      $item['estado'] = $estado;
      $encontrado     = true;
      break;
    }
  }
  unset($item);

  if (!$encontrado) {
    echo json_encode(['error' => "Acción con id {$id} no encontrada."]);
    exit;
  }

  file_put_contents($plan_file, json_encode($plan, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
  echo json_encode(['ok' => true]);
  exit;
}

// ─────────────────────────────────────────────────────────────────────
// Acción no reconocida
// ─────────────────────────────────────────────────────────────────────
echo json_encode(['error' => 'Acción no reconocida: ' . htmlspecialchars($accion)]);
exit;
