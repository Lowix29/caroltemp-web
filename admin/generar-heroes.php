<?php
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  header('Location: login.php'); exit;
}

$outDir = dirname(__DIR__) . '/img/heroes';
$W = 1920; $H = 1080;

$paletas = [
  'fugas'      => [[0x04,0x12,0x2E],[0x0A,0x3A,0x6B],[0x0D,0x6E,0x9A]],
  'desatascos' => [[0x0C,0x0F,0x15],[0x1A,0x28,0x3C],[0x24,0x44,0x5E]],
  'fontanero'  => [[0x06,0x16,0x28],[0x0B,0x24,0x47],[0x1A,0x3F,0x6F]],
  'urgencias'  => [[0x08,0x08,0x10],[0x10,0x18,0x30],[0x22,0x1A,0x06]],
];

$ciudades = [
  'elda'             => ['angle'=>135,'ox'=>0.50,'oy'=>0.35],
  'petrer'           => ['angle'=>150,'ox'=>0.65,'oy'=>0.45],
  'novelda'          => ['angle'=>120,'ox'=>0.35,'oy'=>0.55],
  'monovar'          => ['angle'=>165,'ox'=>0.55,'oy'=>0.30],
  'sax'              => ['angle'=>110,'ox'=>0.45,'oy'=>0.60],
  'pinoso'           => ['angle'=>145,'ox'=>0.70,'oy'=>0.40],
  'monforte-del-cid' => ['angle'=>130,'ox'=>0.30,'oy'=>0.50],
  'salinas'          => ['angle'=>155,'ox'=>0.60,'oy'=>0.35],
  'aspe'             => ['angle'=>125,'ox'=>0.40,'oy'=>0.55],
];

$ejecutar = isset($_POST['generar']);
$log = []; $ok = 0; $err = 0;

if ($ejecutar) {
  if (!extension_loaded('gd')) {
    $log[] = ['error', 'PHP GD no está instalado en este servidor.'];
  } else {
    if (!is_dir($outDir)) mkdir($outDir, 0755, true);
    foreach ($paletas as $tipo => $pal) {
      foreach ($ciudades as $ciudad => $cfg) {
        $slug     = "{$tipo}-{$ciudad}";
        $filename = "{$outDir}/{$slug}.jpg";
        try {
          $img = imagecreatetruecolor($W, $H);
          $ang = deg2rad($cfg['angle']);
          $dx = cos($ang); $dy = sin($ang);
          for ($y = 0; $y < $H; $y++) {
            for ($x = 0; $x < $W; $x++) {
              $t = ($x*$dx + $y*$dy) / ($W*abs($dx) + $H*abs($dy));
              $t = max(0, min(1, $t));
              if ($t < 0.5) {
                $tt=$t*2;
                $r=(int)($pal[0][0]+($pal[1][0]-$pal[0][0])*$tt);
                $g=(int)($pal[0][1]+($pal[1][1]-$pal[0][1])*$tt);
                $b=(int)($pal[0][2]+($pal[1][2]-$pal[0][2])*$tt);
              } else {
                $tt=($t-.5)*2;
                $r=(int)($pal[1][0]+($pal[2][0]-$pal[1][0])*$tt);
                $g=(int)($pal[1][1]+($pal[2][1]-$pal[1][1])*$tt);
                $b=(int)($pal[1][2]+($pal[2][2]-$pal[1][2])*$tt);
              }
              imagesetpixel($img,$x,$y,imagecolorallocate($img,$r,$g,$b));
            }
          }
          $bokeh = [
            [$cfg['ox'],     $cfg['oy'],     320,...$pal[2],60],
            [$cfg['ox']-.28, $cfg['oy']+.20, 200,...$pal[1],45],
            [$cfg['ox']+.30, $cfg['oy']-.25, 260,...$pal[2],50],
            [0.15,           0.80,           180,...$pal[1],35],
            [0.85,           0.20,           150,...$pal[2],30],
          ];
          foreach ($bokeh as $bk) {
            [$fx,$fy,$rad,$br,$bg,$bb,$ba] = $bk;
            $cx=(int)($W*$fx); $cy=(int)($H*$fy);
            for ($s=$rad;$s>0;$s-=4) {
              $alph=(int)($ba*(1-$s/$rad)*(1-$s/$rad)*1.5);
              $c=imagecolorallocatealpha($img,$br,$bg,$bb,min(127,max(0,127-$alph)));
              imagefilledellipse($img,$cx,$cy,$s*2,$s*2,$c);
              imagecolordeallocate($img,$c);
            }
          }
          srand(crc32($slug));
          for ($n=0;$n<60000;$n++) {
            $bri=rand(0,30);
            $c=imagecolorallocatealpha($img,$bri,$bri,$bri+5,rand(100,127));
            imagesetpixel($img,rand(0,$W-1),rand(0,$H-1),$c);
            imagecolordeallocate($img,$c);
          }
          for ($v=0;$v<80;$v++) {
            $c=imagecolorallocatealpha($img,0,0,0,min(127,(int)(127-127*($v/80)*($v/80)*.6)));
            $t=(int)($v*1.5);
            imagerectangle($img,$t,$t,$W-1-$t,$H-1-$t,$c);
            imagecolordeallocate($img,$c);
          }
          imagejpeg($img,$filename,85);
          imagedestroy($img);
          $log[] = ['ok', $slug.'.jpg']; $ok++;
        } catch (\Throwable $e) {
          $log[] = ['error', $slug.': '.$e->getMessage()]; $err++;
        }
      }
    }
  }
}

$existentes = [];
foreach ($paletas as $tipo => $pal) {
  foreach ($ciudades as $ciudad => $cfg) {
    $slug = "{$tipo}-{$ciudad}";
    $existentes[$slug] = file_exists("{$outDir}/{$slug}.jpg");
  }
}
$total_ok  = count(array_filter($existentes));
$total_all = count($existentes);
$pagina_actual = 'generar-heroes.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Generar imágenes hero — Admin</title>
  <meta name="robots" content="noindex,nofollow">
  <?php include '../includes/admin_style.php'; ?>
</head>
<body>
<?php include '../includes/admin_sidebar.php'; ?>
<div class="main">
  <div class="topbar">
    <h1>Generar imágenes hero</h1>
    <a href="index.php">← Volver al panel</a>
  </div>

  <?php if (!$ejecutar): ?>
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.75rem">
    <div class="card" style="padding:1.25rem 1.5rem">
      <div style="font-size:28px;font-weight:800;color:<?php echo $total_ok===$total_all?'#16a34a':($total_ok>0?'#ea580c':'#dc2626'); ?>"><?php echo $total_ok; ?>/<?php echo $total_all; ?></div>
      <div style="font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.06em;margin-top:4px">Imágenes generadas</div>
    </div>
    <div class="card" style="padding:1.25rem 1.5rem">
      <div style="font-size:28px;font-weight:800;color:#0f172a">4</div>
      <div style="font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.06em;margin-top:4px">Tipos de página</div>
    </div>
    <div class="card" style="padding:1.25rem 1.5rem">
      <div style="font-size:28px;font-weight:800;color:#3b5bdb">1920×1080</div>
      <div style="font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:.06em;margin-top:4px">Resolución JPG</div>
    </div>
  </div>

  <?php if (!extension_loaded('gd')): ?>
    <div class="error-msg">⚠️ PHP GD no está instalado. Instálalo con <code>sudo apt install php-gd</code> y reinicia Apache.</div>
  <?php elseif ($total_ok === $total_all): ?>
    <div class="mensaje">✅ Todas las imágenes ya están generadas.</div>
  <?php else: ?>
    <div class="mensaje" style="background:#fef9c3;border-color:#fde047;color:#78350f">
      ⚠️ Faltan <?php echo $total_all - $total_ok; ?> imágenes. Pulsa el botón para generarlas.
    </div>
  <?php endif; ?>

  <div class="card" style="padding:2rem;margin-top:1.25rem">
    <h2 style="margin-bottom:.75rem">¿Qué hace este generador?</h2>
    <p style="font-size:14px;color:#576574;line-height:1.7;margin-bottom:1.25rem">
      Crea 36 imágenes JPG únicas en <code>img/heroes/</code>, una por cada combinación de tipo de página y ciudad.
      Cada imagen lleva un gradiente diferenciado por color y ángulo, bokeh multicapa y textura de ruido.
      Después ejecuta <strong>"Activar heroes (BD)"</strong> para asignarlas en la base de datos.
    </p>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:.75rem;margin-bottom:1.75rem">
      <div style="background:linear-gradient(135deg,#04122E,#0A3A6B,#0D6E9A);border-radius:10px;padding:1rem;color:#fff;font-size:13px;font-weight:600">🔵 Fugas<br><span style="font-weight:400;opacity:.8">Azul agua/cian</span></div>
      <div style="background:linear-gradient(150deg,#0C0F15,#1A283C,#24445E);border-radius:10px;padding:1rem;color:#fff;font-size:13px;font-weight:600">⚙️ Desatascos<br><span style="font-weight:400;opacity:.8">Gris acero industrial</span></div>
      <div style="background:linear-gradient(120deg,#061628,#0B2447,#1A3F6F);border-radius:10px;padding:1rem;color:#fff;font-size:13px;font-weight:600">🔧 Fontanero<br><span style="font-weight:400;opacity:.8">Azul marino</span></div>
      <div style="background:linear-gradient(165deg,#080810,#101830,#221A06);border-radius:10px;padding:1rem;color:#fff;font-size:13px;font-weight:600">🚨 Urgencias<br><span style="font-weight:400;opacity:.8">Negro/ámbar oscuro</span></div>
    </div>
    <form method="POST">
      <button type="submit" name="generar" class="btn-save" style="font-size:15px;padding:12px 28px">
        🎨 Generar las 36 imágenes ahora
      </button>
    </form>
  </div>

  <div class="card" style="margin-top:1.25rem;overflow:hidden">
    <div class="card-header"><h2>Estado por tipo</h2></div>
    <table style="width:100%;border-collapse:collapse">
      <thead><tr>
        <th style="background:#f8fafc;color:#64748b;font-size:11px;font-weight:700;text-transform:uppercase;padding:.6rem 1rem;text-align:left;border-bottom:1px solid #e2e8f0">Slug</th>
        <th style="background:#f8fafc;color:#64748b;font-size:11px;font-weight:700;text-transform:uppercase;padding:.6rem 1rem;text-align:left;border-bottom:1px solid #e2e8f0;width:100px">Estado</th>
      </tr></thead>
      <tbody>
      <?php foreach ($existentes as $slug => $existe): ?>
        <tr style="border-bottom:1px solid #f1f5f9">
          <td style="padding:.55rem 1rem;font-family:monospace;font-size:12.5px;color:#0f172a"><?php echo htmlspecialchars($slug); ?>.jpg</td>
          <td style="padding:.55rem 1rem">
            <?php if ($existe): ?>
              <span style="background:#dcfce7;color:#16a34a;font-size:11px;font-weight:700;padding:2px 8px;border-radius:20px">✓ OK</span>
            <?php else: ?>
              <span style="background:#fee2e2;color:#dc2626;font-size:11px;font-weight:700;padding:2px 8px;border-radius:20px">✗ Falta</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <?php else: ?>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem">
    <div class="card" style="padding:1.5rem;text-align:center">
      <div style="font-size:3rem;font-weight:800;color:#16a34a"><?php echo $ok; ?></div>
      <div style="font-size:13px;color:#64748b;font-weight:600;margin-top:4px">Generadas correctamente</div>
    </div>
    <div class="card" style="padding:1.5rem;text-align:center">
      <div style="font-size:3rem;font-weight:800;color:<?php echo $err>0?'#dc2626':'#94a3b8'; ?>"><?php echo $err; ?></div>
      <div style="font-size:13px;color:#64748b;font-weight:600;margin-top:4px">Errores</div>
    </div>
  </div>
  <?php if ($ok > 0): ?>
    <div class="mensaje">✅ <?php echo $ok; ?> imágenes generadas en <code>img/heroes/</code>. Ahora ve a <strong><a href="setup-heroes.php">Activar heroes (BD)</a></strong>.</div>
  <?php endif; ?>
  <div class="card" style="overflow:hidden;margin-top:1.25rem">
    <div class="card-header"><h2>Log de generación</h2></div>
    <div style="padding:.75rem 1rem;max-height:400px;overflow-y:auto">
      <?php foreach ($log as [$tipo_log, $msg]): ?>
        <div style="font-family:monospace;font-size:12px;padding:3px 0;color:<?php echo $tipo_log==='ok'?'#16a34a':'#dc2626'; ?>">
          <?php echo $tipo_log==='ok'?'✓':'✗'; ?> <?php echo htmlspecialchars($msg); ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
  <div style="margin-top:1.25rem;display:flex;gap:.75rem">
    <a href="setup-heroes.php" class="btn-save">→ Activar en base de datos</a>
    <a href="generar-heroes.php" class="btn-preview">Regenerar</a>
  </div>
  <?php endif; ?>
</div>
</body>
</html>