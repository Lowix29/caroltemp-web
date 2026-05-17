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
// Helpers: parseo de Excel Semrush (.xlsx)
// ─────────────────────────────────────────────────────────────────────
function col_letra_a_idx($col) {
  $col = strtoupper(preg_replace('/[^A-Za-z]/', '', $col));
  $idx = 0;
  for ($i = 0; $i < strlen($col); $i++) {
    $idx = $idx * 26 + (ord($col[$i]) - 64);
  }
  return $idx - 1;
}

function parse_xlsx_semrush($filepath) {
  if (!class_exists('ZipArchive')) return ['error' => 'ZipArchive no disponible en PHP'];
  $zip = new ZipArchive();
  if ($zip->open($filepath) !== true) return ['error' => 'No se pudo abrir el archivo xlsx'];

  $ss    = [];
  $ssRaw = $zip->getFromName('xl/sharedStrings.xml');
  if ($ssRaw) {
    $ssXml = @simplexml_load_string($ssRaw);
    if ($ssXml) {
      foreach ($ssXml->si as $si) {
        if (isset($si->t)) {
          $ss[] = (string)$si->t;
        } else {
          $txt = '';
          foreach ($si->r as $r) $txt .= (string)$r->t;
          $ss[] = $txt;
        }
      }
    }
  }

  $sheetRaw = $zip->getFromName('xl/worksheets/sheet1.xml');
  $zip->close();
  if (!$sheetRaw) return ['error' => 'No se encontró sheet1.xml en el xlsx'];

  $sheet = @simplexml_load_string($sheetRaw);
  if (!$sheet) return ['error' => 'XML de la hoja no válido'];

  $filas = [];
  foreach ($sheet->sheetData->row as $row) {
    $fila = [];
    foreach ($row->c as $cell) {
      preg_match('/^([A-Z]+)/', (string)$cell['r'], $m);
      $colIdx = col_letra_a_idx($m[1] ?? 'A');
      $tipo   = (string)$cell['t'];
      $val    = isset($cell->v) ? (string)$cell->v : '';
      if ($tipo === 's')       $val = $ss[(int)$val] ?? '';
      elseif ($tipo !== 'str') $val = is_numeric($val) ? $val + 0 : $val;
      $fila[$colIdx] = $val;
    }
    $filas[] = $fila;
  }
  return $filas;
}

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
// Función: escanear archivos reales del sitio (más allá de los tipos conocidos)
// ─────────────────────────────────────────────────────────────────────
function escanear_sitio($site_root, $paginas_conocidas) {
  $conocidos = [];
  foreach ($paginas_conocidas as $p) {
    $conocidos[$p['filepath']] = true;
  }

  $ignorar_dirs  = ['admin', 'includes', 'css', 'js', 'img', 'vendor', 'node_modules', '.git'];
  $ignorar_files = ['404.php', 'cambiar-password.php', 'aviso-legal.php', 'privacidad.php', 'cookies.php'];

  $otros = [];
  $iter  = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($site_root, FilesystemIterator::SKIP_DOTS));

  foreach ($iter as $file) {
    if ($file->getExtension() !== 'php') continue;

    $rel = ltrim(str_replace($site_root, '', $file->getPathname()), '/');

    // Ignorar dirs y archivos administrativos
    $parts   = explode('/', $rel);
    $top_dir = $parts[0] ?? '';
    if (in_array($top_dir, $ignorar_dirs, true)) continue;
    if (in_array(basename($rel), $ignorar_files, true)) continue;
    if (isset($conocidos[$rel])) continue;

    $otros[] = $rel;
  }

  sort($otros);
  return $otros;
}

// ─────────────────────────────────────────────────────────────────────
// Función: construir texto de inventario para Claude
// ─────────────────────────────────────────────────────────────────────
function texto_inventario($paginas, $otros_archivos = []) {
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

  $resumen = "=== PÁGINAS DE SERVICIO Y ZONA (arquitectura conocida) ===\n";
  $resumen .= "Total: {$total} | OK: {$ok} | Provisional: {$provisional} | Faltan: {$faltantes}\n\n";
  $resumen .= implode("\n", $lineas);

  if (!empty($otros_archivos)) {
    $resumen .= "\n\n=== OTRAS PÁGINAS EXISTENTES EN EL SITIO ===\n";
    $resumen .= implode("\n", array_map(fn($f) => "[EXISTE] {$f}", $otros_archivos));
  }

  return [
    'texto'      => $resumen,
    'total'      => $total,
    'ok'         => $ok,
    'provisional'=> $provisional,
    'faltantes'  => $faltantes,
  ];
}

// ─────────────────────────────────────────────────────────────────────
// ACCIÓN: debatir (conversación multi-turno)
// ─────────────────────────────────────────────────────────────────────
if ($accion === 'debatir') {
  if (!function_exists('curl_init')) {
    echo json_encode(['error' => 'curl no está habilitado en PHP.']);
    exit;
  }
  if (!defined('ANTHROPIC_API_KEY') || strlen(ANTHROPIC_API_KEY) < 20) {
    echo json_encode(['error' => 'API key no configurada.']);
    exit;
  }

  $mensaje       = trim($_POST['mensaje']   ?? '');
  $historial_raw = trim($_POST['historial'] ?? '[]');
  $historial     = json_decode($historial_raw, true);
  if (!is_array($historial)) $historial = [];

  if (!$mensaje) {
    echo json_encode(['error' => 'El mensaje no puede estar vacío.']);
    exit;
  }

  // Inventario siempre actualizado
  $paginas        = construir_inventario($site_root, $ciudades, $tipos_servicio);
  $otros_archivos = escanear_sitio($site_root, $paginas);
  $inv            = texto_inventario($paginas, $otros_archivos);
  $inv_texto      = $inv['texto'];

  $anyo              = date('Y');
  $es_primer_turno   = empty($historial);
  $clusters_contexto = '';
  $serp_contexto     = '';
  $xlsx_debug        = ['recibido' => false, 'error_code' => null, 'filas' => 0, 'procesado' => false, 'parse_error' => null];

  // Solo en el primer turno: procesar Excel de Semrush
  if ($es_primer_turno) {
    $xlsx_upload_error = $_FILES['keywords_xlsx']['error'] ?? UPLOAD_ERR_NO_FILE;
    $xlsx_debug['error_code'] = $xlsx_upload_error;
    $tiene_xlsx = !empty($_FILES['keywords_xlsx']['tmp_name'])
                  && $xlsx_upload_error === UPLOAD_ERR_OK;
    $xlsx_debug['recibido'] = $tiene_xlsx;

    if ($tiene_xlsx) {
      $filas = parse_xlsx_semrush($_FILES['keywords_xlsx']['tmp_name']);

      if (isset($filas['error'])) {
        $xlsx_debug['parse_error'] = $filas['error'];
      } elseif (count($filas) <= 1) {
        $xlsx_debug['parse_error'] = 'Solo ' . count($filas) . ' fila(s) — ¿archivo vacío?';
      }

      $xlsx_debug['filas'] = count($filas);

      if (!isset($filas['error']) && count($filas) > 1) {

        // ── Detectar columnas por cabecera ────────────────────────────
        $cabecera   = $filas[0];
        $col_kw     = null; $col_pos  = null; $col_vol  = null;
        $col_url    = null; $col_prev = null; $col_dif  = null;

        $mapeo = [
          'keyword'         => &$col_kw,  'palabra clave'   => &$col_kw,
          'position'        => &$col_pos, 'posición'        => &$col_pos, 'pos'  => &$col_pos,
          'search volume'   => &$col_vol, 'volumen'         => &$col_vol, 'volume' => &$col_vol,
          'url'             => &$col_url, 'landing page'    => &$col_url, 'landing' => &$col_url,
          'previous position' => &$col_prev, 'posición anterior' => &$col_prev,
          'keyword difficulty' => &$col_dif, 'dificultad'   => &$col_dif, 'kd'  => &$col_dif,
        ];
        foreach ($cabecera as $idx => $nombre) {
          $n = mb_strtolower(trim((string)$nombre));
          foreach ($mapeo as $patron => &$ref) {
            if ($ref === null && strpos($n, $patron) !== false) {
              $ref = $idx;
              break;
            }
          }
          unset($ref);
        }

        // Si no detectamos posición ni keyword, fallback a índices 0,2 (formato cluster antiguo)
        if ($col_kw === null)  $col_kw  = 0;
        if ($col_vol === null) $col_vol = 2;

        // ── Procesar filas ────────────────────────────────────────────
        $tiene_posiciones = ($col_pos !== null);
        $filas_datos      = array_slice($filas, 1); // sin cabecera

        if ($tiene_posiciones) {
          // ── Modo análisis de posiciones (Position Tracking / Organic Research) ──
          $por_pagina   = []; // URL → [keywords con posición]
          $top3         = []; // pos 1-3
          $oportunidad  = []; // pos 4-10
          $seguimiento  = []; // pos 11-20
          $sin_posicion = []; // vol > 0 pero sin ranking

          foreach ($filas_datos as $fila) {
            $kw  = trim((string)($fila[$col_kw]  ?? ''));
            $pos = $col_pos !== null ? intval($fila[$col_pos] ?? 0) : 0;
            $vol = intval($fila[$col_vol] ?? 0);
            $url = $col_url !== null ? trim((string)($fila[$col_url] ?? '')) : '';
            $dif = $col_dif !== null ? intval($fila[$col_dif] ?? 0) : 0;

            if (!$kw) continue;

            $entrada = ['kw' => $kw, 'pos' => $pos, 'vol' => $vol, 'url' => $url, 'dif' => $dif];

            if ($pos > 0 && $pos <= 3)  $top3[]        = $entrada;
            elseif ($pos > 3 && $pos <= 10) $oportunidad[] = $entrada;
            elseif ($pos > 10 && $pos <= 20) $seguimiento[] = $entrada;
            elseif ($pos === 0 && $vol > 0) $sin_posicion[] = $entrada;

            if ($url) {
              // Extraer path relativo (quitar dominio)
              $path = preg_replace('/^https?:\/\/[^\/]+/', '', $url) ?: $url;
              if (!isset($por_pagina[$path])) $por_pagina[$path] = [];
              $por_pagina[$path][] = $entrada;
            }
          }

          // Ordenar oportunidades por volumen desc
          usort($oportunidad,  fn($a,$b) => $b['vol'] - $a['vol']);
          usort($sin_posicion, fn($a,$b) => $b['vol'] - $a['vol']);

          // Ordenar páginas por número de keywords
          uasort($por_pagina, fn($a,$b) => count($b) - count($a));

          // ── Construir contexto para Claude ────────────────────────
          $clusters_contexto  = "\n=== ANÁLISIS DE POSICIONAMIENTO SEMRUSH ===\n";
          $clusters_contexto .= "Total keywords con ranking: " . (count($top3) + count($oportunidad) + count($seguimiento)) . "\n";
          $clusters_contexto .= "Top 3: " . count($top3) . " | Pos 4-10 (oportunidad): " . count($oportunidad) . " | Pos 11-20: " . count($seguimiento) . " | Sin ranking: " . count($sin_posicion) . "\n";

          if (!empty($top3)) {
            $clusters_contexto .= "\n--- POSICIONES TOP 3 (defender) ---\n";
            foreach (array_slice($top3, 0, 10) as $r) {
              $clusters_contexto .= "  Pos.{$r['pos']} · \"{$r['kw']}\" (vol:{$r['vol']})";
              if ($r['url']) $clusters_contexto .= " → {$r['url']}";
              $clusters_contexto .= "\n";
            }
          }

          if (!empty($oportunidad)) {
            $clusters_contexto .= "\n--- POSICIONES 4-10 (oportunidad de subir a top 3) ---\n";
            foreach (array_slice($oportunidad, 0, 15) as $r) {
              $clusters_contexto .= "  Pos.{$r['pos']} · \"{$r['kw']}\" (vol:{$r['vol']})";
              if ($r['url']) $clusters_contexto .= " → {$r['url']}";
              $clusters_contexto .= "\n";
            }
          }

          if (!empty($sin_posicion)) {
            $clusters_contexto .= "\n--- KEYWORDS CON VOLUMEN SIN RANKING (gaps) ---\n";
            foreach (array_slice($sin_posicion, 0, 12) as $r) {
              $clusters_contexto .= "  \"{$r['kw']}\" (vol:{$r['vol']}";
              if ($r['dif']) $clusters_contexto .= ", dif:{$r['dif']}";
              $clusters_contexto .= ")\n";
            }
          }

          if (!empty($por_pagina)) {
            $clusters_contexto .= "\n--- PÁGINAS CON MÁS KEYWORDS POSICIONADAS ---\n";
            $n = 0;
            foreach ($por_pagina as $path => $kws) {
              if ($n++ >= 8) break;
              $vol_total = array_sum(array_column($kws, 'vol'));
              $clusters_contexto .= "  {$path} → " . count($kws) . " keywords (vol.total:{$vol_total})\n";
            }
          }

          $xlsx_debug['procesado'] = true;
          $xlsx_debug['modo']      = 'posiciones';

          // Serper: consultar las keywords de mayor oportunidad
          $kws_serper = array_column(array_slice($oportunidad, 0, 3), 'kw');
          if (empty($kws_serper) && !empty($top3)) {
            $kws_serper = array_column(array_slice($top3, 0, 2), 'kw');
          }

        } else {
          // ── Modo cluster de keywords (sin datos de posición) ─────────
          $clusters = [];
          foreach ($filas_datos as $fila) {
            $kw   = trim((string)($fila[$col_kw]  ?? ''));
            $seed = trim((string)($fila[1]         ?? $kw));
            $vol  = intval($fila[$col_vol] ?? 0);
            if (!$kw) continue;
            if (!isset($clusters[$seed])) $clusters[$seed] = ['vol_total' => 0, 'keywords' => []];
            $clusters[$seed]['vol_total'] += $vol;
            $clusters[$seed]['keywords'][] = ['kw' => $kw, 'vol' => $vol];
          }
          uasort($clusters, fn($a,$b) => $b['vol_total'] - $a['vol_total']);
          $top_clusters = array_slice($clusters, 0, 8, true);
          $kws_serper   = [];

          $clusters_contexto = "\n=== KEYWORDS SEMRUSH (por clusters de volumen) ===\n";
          foreach ($top_clusters as $seed => $data) {
            $clusters_contexto .= "Cluster '{$seed}' — vol.total {$data['vol_total']}\n";
            foreach (array_slice($data['keywords'], 0, 4) as $k) {
              $clusters_contexto .= "  · {$k['kw']} (vol:{$k['vol']})\n";
            }
            usort($data['keywords'], fn($a,$b) => $b['vol'] - $a['vol']);
            if (!empty($data['keywords'][0]['kw'])) $kws_serper[] = $data['keywords'][0]['kw'];
          }
          $kws_serper = array_slice($kws_serper, 0, 5);
          $xlsx_debug['procesado'] = true;
          $xlsx_debug['modo']      = 'clusters';
        }

        // ── Serper: consultar competidores para keywords clave ────────
        $tiene_serper = defined('SERPER_API_KEY') && strlen(SERPER_API_KEY) >= 10;
        if ($tiene_serper && !empty($kws_serper)) {
          $serp_contexto = "\n=== COMPETIDORES EN GOOGLE ESPAÑA (top resultados) ===\n";
          foreach ($kws_serper as $kw) {
            $ch = curl_init('https://google.serper.dev/search');
            curl_setopt_array($ch, [
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_POST           => true,
              CURLOPT_POSTFIELDS     => json_encode(['q' => $kw, 'gl' => 'es', 'hl' => 'es', 'num' => 5]),
              CURLOPT_HTTPHEADER     => ['X-API-KEY: ' . SERPER_API_KEY, 'Content-Type: application/json'],
              CURLOPT_TIMEOUT        => 15,
            ]);
            $raw_serp = curl_exec($ch);
            curl_close($ch);
            if ($raw_serp) {
              $serp_data = json_decode($raw_serp, true);
              if (!empty($serp_data['organic'])) {
                $serp_contexto .= "\nKeyword: \"{$kw}\"\n";
                $count = 0;
                foreach ($serp_data['organic'] as $item) {
                  if ($count++ >= 5) break;
                  $serp_contexto .= "  - " . ($item['title'] ?? '') . " → " . ($item['link'] ?? '') . "\n";
                }
              }
            }
          }
        }
      }
    }

    // Construir primer mensaje con todo el contexto
    $contenido_primer_msg = "BRIEFING DEL CLIENTE:\n{$mensaje}";
    if ($clusters_contexto) $contenido_primer_msg .= "\n\n{$clusters_contexto}";
    if ($serp_contexto)     $contenido_primer_msg .= "\n\n{$serp_contexto}";
    $historial[] = ['role' => 'user', 'content' => $contenido_primer_msg];
  } else {
    $historial[] = ['role' => 'user', 'content' => $mensaje];
  }

  $system_debate = <<<SYS
Eres el Director de Estrategia SEO de una agencia digital. Año actual: {$anyo}.

## PROHIBICIONES ABSOLUTAS
- NUNCA escribas "Vinalopó"
- NUNCA inventes estadísticas, porcentajes, estimaciones de tráfico ni ratios de conversión. Nada de "+30% tráfico", "800 visitas/mes", "+25% conversión". No tienes datos reales para eso.
- NUNCA asumas qué servicios ofrece o quiere potenciar el cliente. Pregúntalo.

## LO ÚNICO QUE SABES
- El cliente se llama CarolTemp, está en Elda (Alicante)
- Tiene una web con algunas páginas ya creadas (inventario abajo)
- Ha escrito un briefing que te va a llegar ahora

## ESTADO ACTUAL DEL SITIO (escaneado automáticamente)
{$inv_texto}

## TU ROL

Eres un estratega que escucha antes de proponer. La arquitectura actual del sitio es solo un punto de partida — no asumas que refleja lo que el cliente quiere hacer.

**En tu PRIMER mensaje:**
- Haz entre 3 y 5 preguntas concretas para entender qué quiere conseguir realmente
- No hagas un análisis completo todavía — primero necesitas entender la dirección
- Las preguntas deben ayudarte a entender: prioridades de negocio, servicios que quiere potenciar, qué no funciona según él, hacia dónde quiere ir

**En mensajes siguientes:**
- Propón basándote en lo que el cliente te ha dicho, no en lo que ya existe
- Si hay algo en el inventario relevante para lo que dice el cliente, menciónalo
- Defiende tus propuestas con argumentos. Si el cliente te cuestiona, razona
- Sé directo y estratégico, sin relleno

En esta fase NO generes JSON. Es una conversación. Responde siempre en español.
SYS;

  $payload = [
    'model'      => ANTHROPIC_MODEL,
    'max_tokens' => 2048,
    'system'     => $system_debate,
    'messages'   => $historial,
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

  $respuesta_text = $response['content'][0]['text'];

  if (($response['stop_reason'] ?? '') === 'max_tokens') {
    // Truncated but still usable — return what we have
  }

  // Añadir respuesta al historial
  $historial[] = ['role' => 'assistant', 'content' => $respuesta_text];

  echo json_encode([
    'ok'          => true,
    'respuesta'   => $respuesta_text,
    'historial'   => $historial,
    'xlsx_debug'  => $xlsx_debug,
  ]);
  exit;
}

// ─────────────────────────────────────────────────────────────────────
// ACCIÓN: finalizar_plan (genera JSON a partir de la conversación)
// ─────────────────────────────────────────────────────────────────────
if ($accion === 'finalizar_plan') {
  if (!function_exists('curl_init')) {
    echo json_encode(['error' => 'curl no está habilitado en PHP.']);
    exit;
  }
  if (!defined('ANTHROPIC_API_KEY') || strlen(ANTHROPIC_API_KEY) < 20) {
    echo json_encode(['error' => 'API key no configurada.']);
    exit;
  }

  $historial_raw = trim($_POST['historial'] ?? '[]');
  $historial     = json_decode($historial_raw, true);
  if (!is_array($historial) || empty($historial)) {
    echo json_encode(['error' => 'No hay conversación para generar el plan.']);
    exit;
  }

  $anyo = date('Y');

  $system_plan = <<<SYS
Eres el Director de Estrategia SEO de una agencia digital especializada en fontanería local. Año actual: {$anyo}.

Basándote en la conversación de debate estratégico que has mantenido con el cliente, genera ahora el plan de acción definitivo.

**PROHIBIDO:** nunca escribas "Vinalopó".
**NUNCA** inventes estadísticas, años de experiencia ni porcentajes.

Acciones posibles en el plan:
- CREAR — página que no existe y debería existir
- MEJORAR — existe pero tiene contenido provisional o pobre
- REDIRIGIR — URL incorrecta o duplicada, necesita 301
- ELIMINAR — página sin valor o canibalización
- MANTENER — está bien, no tocar

Para las rutas de archivo: usa la convención actual de la web (`directorio/nombre-servicio-ciudad.php`). Si propones nuevos directorios, sé coherente con la estructura existente.

DEVUELVE ÚNICAMENTE JSON VÁLIDO. Sin markdown, sin texto antes ni después.

{
  "resumen": "2-3 frases del diagnóstico principal",
  "diagnostico": "Problemas y riesgos SEO concretos (3-4 líneas)",
  "arquitectura_ideal": "Arquitectura web ideal propuesta (4-5 líneas)",
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
      "titulo_sugerido": "Título H1 propuesto o null",
      "motivo": "Por qué esta acción en 1 frase directa",
      "desde": null,
      "hacia": null
    }
  ],
  "recomendaciones": "Consejos adicionales: interlinking, schema, velocidad (3-5 líneas)"
}
SYS;

  $messages = array_merge($historial, [
    ['role' => 'user',      'content' => 'Perfecto. Genera ahora el plan de acción definitivo en JSON basándote en todo lo que hemos debatido.'],
    ['role' => 'assistant', 'content' => '{'],
  ]);

  $payload = [
    'model'      => ANTHROPIC_MODEL,
    'max_tokens' => 8192,
    'system'     => $system_plan,
    'messages'   => $messages,
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

  $json_str = '{' . $text;
  $plan     = json_decode($json_str, true);

  if (!$plan) {
    if (preg_match('/\{[\s\S]*\}/u', $json_str, $m)) {
      $plan = json_decode($m[0], true);
    }
  }

  if (!$plan) {
    echo json_encode(['error' => 'Claude no devolvió JSON válido.', 'raw' => substr($json_str, 0, 500)]);
    exit;
  }

  // Añadir estados y metadatos
  $plan['fecha_generacion'] = date('c');
  if (isset($plan['plan']) && is_array($plan['plan'])) {
    foreach ($plan['plan'] as &$item) {
      if (!isset($item['estado'])) $item['estado'] = 'pendiente';
      if (!isset($item['id']))     $item['id']     = rand(1000, 9999);
    }
    unset($item);
  }

  echo json_encode(['ok' => true, 'plan' => $plan]);
  exit;
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

  $objetivo = trim($_POST['objetivo'] ?? '');

  if (!$objetivo) {
    echo json_encode(['error' => 'El campo objetivo es obligatorio.']);
    exit;
  }

  // 1. Inventario de páginas
  $paginas        = construir_inventario($site_root, $ciudades, $tipos_servicio);
  $otros_archivos = escanear_sitio($site_root, $paginas);
  $inv            = texto_inventario($paginas, $otros_archivos);
  $inv_texto      = $inv['texto'];

  // 2. Procesar Excel de keywords (opcional) — el auditor elige qué investigar
  $clusters_contexto = '';
  $keywords_para_serper = [];

  $tiene_xlsx = !empty($_FILES['keywords_xlsx']['tmp_name'])
                && $_FILES['keywords_xlsx']['error'] === UPLOAD_ERR_OK;

  if ($tiene_xlsx) {
    $filas = parse_xlsx_semrush($_FILES['keywords_xlsx']['tmp_name']);

    if (!isset($filas['error']) && count($filas) > 1) {
      // Estructura: col0=Keyword, col1=Seed keyword, col2=Volume, col3=Dificultad
      $clusters = [];
      $header   = true;
      foreach ($filas as $fila) {
        if ($header) { $header = false; continue; }
        $kw   = trim($fila[0] ?? '');
        $seed = trim($fila[1] ?? $kw);
        $vol  = intval($fila[2] ?? 0);
        if (!$kw) continue;
        if (!isset($clusters[$seed])) {
          $clusters[$seed] = ['vol_total' => 0, 'keywords' => []];
        }
        $clusters[$seed]['vol_total'] += $vol;
        $clusters[$seed]['keywords'][] = ['kw' => $kw, 'vol' => $vol];
      }

      // Ordenar clusters por volumen total desc, coger top 8
      uasort($clusters, fn($a, $b) => $b['vol_total'] - $a['vol_total']);
      $top_clusters = array_slice($clusters, 0, 8, true);

      // Construir contexto de clusters para Claude
      $clusters_contexto = "\nCLUSTERS DE KEYWORDS (Export Semrush):\n";
      foreach ($top_clusters as $seed => $data) {
        $clusters_contexto .= "Cluster '{$seed}' — vol. total {$data['vol_total']}\n";
        $top_kws = array_slice($data['keywords'], 0, 4);
        foreach ($top_kws as $k) {
          $clusters_contexto .= "  · {$k['kw']} ({$k['vol']})\n";
        }
        // La keyword con más volumen del cluster va a Serper
        usort($data['keywords'], fn($a, $b) => $b['vol'] - $a['vol']);
        if (!empty($data['keywords'][0]['kw'])) {
          $keywords_para_serper[] = $data['keywords'][0]['kw'];
        }
      }
      $keywords_para_serper = array_slice($keywords_para_serper, 0, 5);
    }
  }

  // 3. Serper: el auditor investiga automáticamente las keywords más relevantes del Excel
  $serp_contexto = '';
  $tiene_serper  = defined('SERPER_API_KEY') && strlen(SERPER_API_KEY) >= 10;

  if ($tiene_serper && !empty($keywords_para_serper)) {
    foreach ($keywords_para_serper as $kw) {
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
      $raw_serp = curl_exec($ch);
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

  // 4. Construir mensaje de usuario
  $anyo = date('Y');

  $user_msg  = "BRIEFING DEL CLIENTE:\n{$objetivo}\n\n";
  $user_msg .= "ESTADO ACTUAL DE LA WEB (escaneado automáticamente):\n{$inv_texto}\n";

  if ($clusters_contexto) {
    $user_msg .= "\n{$clusters_contexto}";
  }
  if ($serp_contexto) {
    $user_msg .= "\nDATOS SERP — competidores que posicionan ahora mismo en Google España (investigado automáticamente desde el Excel):\n{$serp_contexto}\n";
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

**TU TRABAJO ES DISEÑAR LA ARQUITECTURA IDEAL COMPLETA, no solo rellenar huecos de lo que ya existe.**

La arquitectura actual (fugas/desatascos/fontanero × 9 ciudades + zonas) es solo el punto de partida. Si ves oportunidades de negocio real que no están cubiertas, PROPONLAS:
- Páginas de aire acondicionado por ciudad (`aire-acondicionado/aire-acondicionado-elda.php`, etc.)
- Páginas de termos por ciudad (`termos/termos-elda.php`, etc.)
- Páginas de descalcificadores por ciudad
- Páginas de reformas de baño por ciudad
- Páginas de índice de categoría (`/aire-acondicionado/`, `/termos/`) si tienen volumen
- Páginas de servicios generales que faltan o están mal enfocadas
- Cualquier otra estructura que un fontanero en España necesitaría para dominar Google local

Si el briefing o el Excel de keywords apunta a oportunidades concretas, prioriza esas.
Si no hay datos externos, usa tu criterio como estratega SEO senior.

**Coherencia de rutas:** usa la misma convención de la web actual: `/directorio/nombre-servicio-ciudad.php`

## REGLAS DEL PLAN

Acciones posibles:
- CREAR — página que no existe y debería existir (puede ser en directorios nuevos)
- MEJORAR — existe pero tiene contenido provisional o pobre
- REDIRIGIR — URL incorrecta o duplicada, necesita 301
- ELIMINAR — página sin valor, canibalización, duplicado
- MANTENER — está bien, no tocar

Sé muy concreto. Cada acción = una URL específica + un motivo de 1-2 frases que el cliente entienda.
Para CREAR de nuevas categorías: indica la ruta exacta del archivo y la estructura de directorio sugerida.

## FORMATO DE RESPUESTA

DEVUELVE ÚNICAMENTE JSON VÁLIDO. Sin markdown, sin texto antes ni después.
IMPORTANTE: sé conciso en los textos de cada campo — frases cortas y directas. El campo "motivo" de cada acción: máximo 1 frase. No repitas información entre campos.

{
  "resumen": "2-3 frases cortas del diagnóstico principal",
  "diagnostico": "Problemas y riesgos SEO concretos (3-4 líneas máx)",
  "arquitectura_ideal": "Arquitectura web ideal para posicionar #1 (4-5 líneas máx)",
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
