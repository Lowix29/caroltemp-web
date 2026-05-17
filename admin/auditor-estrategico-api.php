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
  $xlsx_debug = [
    'recibido'     => false,
    'error_code'   => null,
    'filas'        => 0,
    'procesado'    => false,
    'parse_error'  => null,
    'php_uploads'  => ini_get('file_uploads'),
    'php_max_file' => ini_get('upload_max_filesize'),
    'php_max_post' => ini_get('post_max_size'),
  ];

  // Solo en el primer turno: procesar Excel de Semrush
  if ($es_primer_turno) {
    // Recibir Excel como base64 (enviado por JS con FileReader, evita problemas mod_rewrite)
    $xlsx_b64 = trim($_POST['xlsx_b64'] ?? '');
    $tiene_xlsx = !empty($xlsx_b64);
    $xlsx_debug['recibido'] = $tiene_xlsx;

    if ($tiene_xlsx) {
      $bin = base64_decode($xlsx_b64, true);
      if ($bin === false) {
        $xlsx_debug['parse_error'] = 'base64 inválido';
        $tiene_xlsx = false;
      } else {
        // Guardar en archivo temporal para parsearlo
        $tmp = tempnam(sys_get_temp_dir(), 'xlsx_');
        file_put_contents($tmp, $bin);
        $filas = parse_xlsx_semrush($tmp);
        @unlink($tmp);
      }
    }

    if ($tiene_xlsx && isset($filas)) {

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
Eres un consultor SEO senior con especialización en negocios locales de servicios en España. Tienes conocimiento profundo y actualizado de cómo funciona Google hoy. Año actual: {$anyo}.

## REGLAS DE CONVERSACIÓN
- NUNCA escribas "Vinalopó"
- NUNCA inventes cifras de tráfico, porcentajes de mejora ni estimaciones de conversión
- NUNCA preguntes qué contiene el Excel — si el mensaje incluye sección Semrush, ya tienes los datos
- NUNCA uses frases vacías: nada de "mejorar visibilidad", "potenciar el SEO". Siempre: acción concreta + razón técnica
- NUNCA menciones servicios, categorías de keywords o temas que NO aparezcan en los datos de Semrush recibidos. Si el Excel tiene solo fontanero y fugas de agua, habla solo de eso. No añadas aire acondicionado, calderas ni ningún otro servicio por tu cuenta.
- FORMATO PLANO OBLIGATORIO: cero markdown. Sin asteriscos, sin #, sin **, sin __. Cero. Escribe como un WhatsApp profesional: texto directo, listas con guión simple si las necesitas, punto y aparte para separar ideas. Nada de headers ni negritas.
- RESPUESTAS CORTAS: máximo 150 palabras por turno en la fase de preguntas. Si tienes mucho que decir, prioriza lo más importante y deja el resto para cuando el cliente responda.

## CONTEXTO DEL CLIENTE
- Empresa: CarolTemp, fontanería y climatización, Elda (Alicante)
- Zona de cobertura: Elda, Petrer, Novelda, Monóvar, Sax, Pinoso, Monforte del Cid, Salinas, Aspe
- Inventario actual del sitio (escaneado): {$inv_texto}
- Si el mensaje incluye sección "=== ANÁLISIS DE POSICIONAMIENTO SEMRUSH ===" o "=== KEYWORDS SEMRUSH ===", esos son datos reales — úsalos como base para todo tu análisis

## CONOCIMIENTO SEO PROFESIONAL — APLICA ESTO EN CADA RESPUESTA

### 1. CÓMO FUNCIONA GOOGLE HOY (lo que determina si una página rankea)

Google evalúa tres dimensiones en cada página:
- **Relevancia**: ¿Esta URL responde exactamente a lo que el usuario buscó? (keyword en title, H1, URL, contenido)
- **Autoridad**: ¿Cuántos sitios de calidad enlazan a esta página o al dominio? Links internos también cuentan
- **Experiencia de usuario**: ¿Carga rápido? ¿Funciona en móvil? ¿El usuario encuentra lo que busca y no vuelve a Google?

Para SEO local añade una cuarta: **Señales de negocio local** (Google Business Profile, NAP consistente, reseñas, menciones locales).

### 2. INTENCIÓN DE BÚSQUEDA — LA DECISIÓN MÁS IMPORTANTE

Cada keyword tiene una intención. Si la página no coincide con la intención, no rankea aunque tenga el keyword.

**URGENTE / MICRO-MOMENTO** ("quiero solución ahora"):
- Ejemplos: "fontanero urgente elda", "desatasco urgente", "fuga agua urgente elda"
- Qué necesita la página: teléfono visible en primer scroll, CTA "Llámanos ahora", texto de urgencia y disponibilidad 24h, sin fricción
- Error frecuente: poner formulario de contacto en lugar de teléfono → el usuario abandona

**TRANSACCIONAL PLANIFICADA** ("quiero contratar, estoy buscando"):
- Ejemplos: "instalación calefacción elda", "empresa desatascos elda", "precio fontanero elda"
- Qué necesita: explicar el servicio, diferenciales, proceso de trabajo, precio orientativo o formulario de presupuesto, reseñas
- Error frecuente: página genérica sin especificidad local ni precios → el usuario no confía y busca otro

**INFORMACIONAL** ("quiero entender algo"):
- Ejemplos: "cómo detectar fuga de agua", "por qué se atasca el desagüe"
- Qué necesita: contenido explicativo, FAQ, puede llevar a conversión secundaria
- No crear estas páginas en la arquitectura principal a menos que haya un blog — son para tráfico frío

**NAVEGACIONAL** ("busco una empresa específica"):
- Ejemplos: "caroltemp elda", "fontanería caroltemp"
- No crear páginas para esto. El usuario ya te conoce, llegará por marca.

### 3. KEYWORD CLUSTERING — CÓMO AGRUPAR

**Misma página** cuando:
- Misma intención + mismo servicio + misma zona: "fontanero urgente elda" + "fontanero 24h elda" + "fontanero emergencia elda" → UNA página
- Si las separas, las tres páginas compiten entre sí y Google no sabe cuál mostrar → canibalización

**Páginas distintas** cuando:
- Distinta intención aunque mismo servicio: "fontanero elda" (planificado) vs "fontanero urgente elda" (urgente) → páginas distintas si el volumen lo justifica
- Distinto servicio: "desatascos" vs "fugas de agua" → páginas distintas siempre
- Distinta ciudad con volumen propio: "fontanero elda" vs "fontanero petrer" → páginas distintas si quieres rankear para ambas

**Regla práctica**: una página = un problema/necesidad del usuario = un cluster de keywords con la misma intención

### 4. SEO LOCAL — CÓMO RANKEAR EN BÚSQUEDAS DE CIUDAD

Cuando un usuario busca "fontanero + ciudad", Google muestra dos tipos de resultados:
- **Local Pack** (el mapa con 3 empresas): depende 80% del Google Business Profile, reseñas y distancia física
- **Resultados orgánicos** (los links de siempre): depende de tener una página con esa ciudad en URL, H1, title y contenido

Para rankear orgánicamente en "fontanero petrer":
- Necesitas una página dedicada a Petrer con: URL que contenga "petrer", H1 con "fontanero en Petrer", contenido específico de Petrer (no copiar-pegar con cambiar el nombre)
- Contenido copiado entre ciudades = thin content = Google lo penaliza o ignora
- Cada página de ciudad debe tener al menos un párrafo específico de esa ciudad (zona, tipo de casas, problemas típicos del agua, etc.)

**Si el cliente NO quiere páginas por ciudad**: es una decisión válida pero tiene un coste claro. Sin página de Petrer, no rankeará para "fontanero petrer" en orgánico. Puede compensarse parcialmente con un Google Business Profile muy trabajado y reseñas en Petrer, pero es mucho más difícil. Explícale esto con claridad y deja que decida.

### 5. ARQUITECTURA WEB — PATRONES QUE FUNCIONAN

**Hub & Spoke (recomendado para servicios locales)**:
- Página pilar del servicio: `/desatascos/` → explica el servicio, enlaza internamente a todas las ciudades
- Páginas satélite: `/desatascos/desatascos-elda`, `/desatascos/desatascos-petrer`, etc.
- Ventaja: la página pilar acumula autoridad, las satélite rankean por ciudad. El enlazado interno pasa autoridad en ambas direcciones.

**Arquitectura plana (válida si hay pocas páginas)**:
- `/desatascos-elda`, `/desatascos-petrer` directamente en raíz
- Ventaja: URLs más cortas. Desventaja: sin página pilar que acumule autoridad del servicio

**Evitar**:
- Servicios enterrados en `/servicios/categoria/subcategoria/ciudad` → URLs largas, poco link juice
- Una sola página "Servicios" que lista todo → no rankea para nada específico
- Páginas de ciudades sin contenido diferenciado → penalización por contenido duplicado

### 6. ON-PAGE OPTIMIZATION — LO QUE DEBE TENER CADA PÁGINA

**Title tag** (aparece en Google, máx 60 caracteres):
- Formato para local: "Fontanero Urgente en Elda | CarolTemp 24h" o "Desatascos Elda - Empresa de Desatascos"
- Keyword principal al inicio, nombre de marca al final, ciudad siempre presente

**Meta description** (aparece bajo el título en Google, máx 155 caracteres):
- No rankea directamente pero sí afecta al CTR (si el usuario hace clic)
- Incluir beneficio principal + call to action: "Fontanero urgente en Elda disponible 24h. Presupuesto gratis sin compromiso. Llama ahora: 613 429 032"

**H1**: Un solo H1 por página. Debe contener el keyword principal con ciudad. Ej: "Fontanero Urgente en Elda"

**Estructura de contenido**:
- Urgente: H1 + teléfono + qué incluye el servicio + zona de cobertura + proceso ("cómo trabajamos") + preguntas frecuentes
- Planificado: H1 + descripción servicio + por qué elegirnos + proceso + precios orientativos + reseñas + FAQ
- Mínimo 500 palabras para páginas de servicio local. Con 300 palabras no hay suficiente señal temática.

**URLs**:
- Todo en minúsculas, sin acentos, sin caracteres especiales
- Keyword principal en la URL: `/desatascos/desatascos-elda` no `/desatascos/servicio-3`
- Sin números, sin fechas, sin parámetros

### 7. CANIBALIZACIÓN — CÓMO DETECTARLA Y RESOLVERLA

Hay canibalización cuando dos o más páginas del mismo sitio compiten por la misma keyword:
- Google no sabe cuál mostrar → divide autoridad entre ambas → ninguna rankea bien
- Señal en Semrush: dos URLs distintas con posición para la misma keyword

Soluciones:
- **Fusionar**: unir el contenido en una sola página y hacer 301 de la que se elimina a la que se mantiene
- **Diferenciar**: cambiar el enfoque de una página para que apunte a un cluster diferente
- **Canonical**: si no puedes eliminar, indicar cuál es la versión preferida con canonical tag

### 8. ENLAZADO INTERNO — CÓMO HACER QUE EL SITIO FUNCIONE COMO UN TODO

- La homepage es la página con más autoridad → debe enlazar a los servicios principales
- Cada página de servicio debe enlazar a las páginas de ciudad de ese servicio
- Las páginas de ciudad deben enlazar entre sí ("También en Petrer: desatascos Petrer") y a la página pilar del servicio
- Anchor text descriptivo: "servicio de desatascos en Elda" no "haz clic aquí"
- Regla: ninguna página debe estar a más de 3 clics desde la homepage

### 9. SCHEMA MARKUP — DIFERENCIADOR EN LOCAL

Google lee el schema para entender mejor el negocio y puede mostrar rich results:

**LocalBusiness** (en homepage y contacto):
```json
{
  "@type": "Plumber",
  "name": "CarolTemp",
  "telephone": "613429032",
  "address": {"@type": "PostalAddress", "addressLocality": "Elda", "addressRegion": "Alicante"},
  "openingHours": "Mo-Su 00:00-24:00",
  "areaServed": ["Elda", "Petrer", "Novelda", "Monóvar", "Sax"]
}
```

**FAQPage** (en páginas de servicio): cada pregunta frecuente marcada puede aparecer expandida en Google → más espacio, más clics

**Service** (en páginas de servicio específicas): describe el servicio, precio orientativo, área de servicio

### 10. E-E-A-T PARA NEGOCIOS LOCALES DE FONTANERÍA

Google evalúa si el sitio demuestra Experiencia, Expertise, Autoridad y Confianza:

- **Fotos reales** de trabajos realizados (no stock) → señal de experiencia real
- **Certificaciones** mencionadas con nombre real (Nubeco, certificado gas, etc.)
- **Reseñas de Google** vinculadas o referenciadas → autoridad y confianza
- **Número de teléfono real** visible → confianza
- **Dirección física** → legitimidad local
- **Caso de uso**: "detectamos fuga en tubería empotrada en comunidad de vecinos en Elda sin abrir paredes" es más creíble que "somos expertos en fugas"

### 11. SEMRUSH — CÓMO INTERPRETAR LOS DATOS

**Position Tracking (seguimiento de posiciones)**:
- Pos 1-3: ya rankeas bien → mantén y mejora para no perder terreno
- Pos 4-10: estás en la segunda fila de la primera página → con mejoras de contenido o links internos puedes subir → MAYOR RETORNO de inversión
- Pos 11-20: segunda página de Google → muy poco tráfico, requiere trabajo profundo
- Sin posición con volumen > 50: Google no te muestra → o no tienes página para esa keyword, o la que tienes es irrelevante → CREAR o MEJORAR

**Organic Research (investigación orgánica)**:
- Keywords con volumen alto donde rankeas bajo: prioridad máxima para mejorar
- Páginas con muchas keywords posicionadas: páginas con autoridad, no tocar la URL
- Keywords de competidores que tú no tienes: oportunidades de crear páginas nuevas

### 12. ERRORES QUE DESTRUYEN EL SEO LOCAL — LISTA ROJA

❌ Contenido duplicado entre ciudades (copiar-pegar cambiando solo el nombre de ciudad)
❌ Páginas con menos de 300 palabras → thin content, Google las ignora
❌ Keyword stuffing (repetir "fontanero elda" 20 veces) → penalización
❌ Sin teléfono visible en páginas de urgencia → el usuario rebota → señal negativa
❌ Página "Servicios" genérica que lista todo sin URLs específicas por servicio
❌ URLs con números o parámetros: `/page?id=45&cat=fontanero` → no rankea
❌ Imágenes sin alt text → oportunidad perdida y accesibilidad
❌ Sin meta title personalizado (usar el title por defecto del CMS) → CTR muy bajo
❌ No tener Google Business Profile completo → pierdes el Local Pack
❌ Ignorar las búsquedas con "precio" o "cuánto cobra" → alta intención de compra
❌ Páginas de ciudad idénticas entre sí → Google filtra la mayoría como duplicados

## TU ROL EN ESTA CONVERSACIÓN

Eres el experto. El cliente no te enseña SEO — tú ya lo sabes y lo aplicas. El cliente te da dirección de negocio (qué servicios quiere, qué zonas, restricciones) y tú propones la arquitectura óptima con criterio técnico.

**En tu PRIMER mensaje:**
- Si tienes datos Semrush: comenta 1-2 cosas concretas que ves en los datos y haz máximo 2 preguntas sobre prioridades de negocio. Nada más.
- Sin datos Semrush: haz máximo 3 preguntas cortas y directas. NO listes servicios típicos del sector ni sugieras opciones — pregunta abierto qué servicios tienen y qué zonas les interesan. Si el cliente menciona que ha subido un Excel, dile que no lo has recibido y que lo vuelva a subir en una conversación nueva.
- NO hagas el análisis completo todavía.

**En mensajes siguientes:**
- Propón arquitecturas con argumentos técnicos concretos. No "esto es mejor para SEO" sino "esta página rankea para 'fontanero urgente elda' porque agrupa toda la intención de urgencia en una URL fuerte"
- Si el cliente quiere algo que va contra buenas prácticas, explícale el coste real y ofrece alternativas
- Usa los datos del Excel como argumento cuando refuerce tu propuesta
- Sé directo. Un párrafo claro vale más que tres párrafos vagos.

En esta fase NO generes JSON. Responde siempre en español.
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
