<?php
/**
 * FONTANERO EN ELDA
 * /fontanero/fontanero-elda
 * CONTENIDO PROVISIONAL — sustituir con texto final optimizado
 */

$servicio_nombre = 'Fontanero';
$servicio_slug   = 'fontanero';
$ciudad          = 'Elda';
$ciudad_slug     = 'elda';
$ciudad_cp       = '03600';

$meta_title = 'Fontanero en Elda urgente | 24h | Precio cerrado | CarolTemp';
$meta_desc  = 'Fontanero en Elda urgente 24h. Precio cerrado sin sorpresas. Llama ahora al 613 429 032.';

$hero_titulo = 'Fontanero en Elda<br><span class="hl">urgente 24h.</span>';
$hero_sub    = 'Fontaneros en Elda con precio cerrado. Reparaciones urgentes, detección de fugas sin obras e instalaciones.';

// ================================
// CONTENIDO — SUSTITUIR CON TEXTO FINAL
// ================================
$contenido_intro = '
<p>Si necesitas un <strong>fontanero en Elda</strong> de confianza, en CarolTemp te damos precio cerrado antes de empezar. Conocemos Elda y sus instalaciones.</p>
<p>Contenido provisional pendiente de redacción final optimizada para SEO. Este texto debe sustituirse con contenido único sobre fontanero en Elda.</p>
';

$servicios_lista = [
  'Fontanero urgente en Elda',
  'Reparación de fugas y grifos en Elda',
  'Cambio de tuberías y bajantes en Elda',
  'Instalación de termos Nubeco en Elda',
  'Reformas de baño en Elda con precio cerrado',
  'Ósmosis inversa y descalcificadores en Elda',
];

$problemas_zona = [
  ['titulo' => 'Tuberías antiguas', 'texto' => 'Muchas viviendas en Elda tienen instalaciones de hierro galvanizado de los años 60-80. Pierden presión y generan fugas ocultas.'],
  ['titulo' => 'Agua dura', 'texto' => 'El agua en Elda tiene un nivel de cal alto que deteriora griferías, calentadores y electrodomésticos.'],
  ['titulo' => 'Fugas ocultas', 'texto' => 'Las fugas en tuberías empotradas son muy habituales en Elda. Usamos geófono y cámara para localizarlas sin abrir paredes.'],
];

$faq = [
  ['pregunta' => '¿Cuánto cuesta un fontanero en Elda?', 'respuesta' => 'Depende del trabajo. Siempre damos precio cerrado antes de empezar. Una reparación sencilla parte desde 60-80€.'],
  ['pregunta' => '¿Tenéis fontanero urgente en Elda?', 'respuesta' => 'Sí. Atendemos urgencias en Elda en horario de servicio. Somos de la comarca y llegamos rápido. Llámanos al 613 429 032.'],
  ['pregunta' => '¿Cómo detectáis fugas sin abrir paredes?', 'respuesta' => 'Usamos geófono y cámara endoscópica. Localizamos la fuga exacta antes de abrir nada.'],
  ['pregunta' => '¿Ofrecéis financiación en Elda?', 'respuesta' => 'Sí. Para reformas, ósmosis, descalcificadores y otros trabajos grandes. Material y mano de obra sin adelantar nada.'],
];

$contenido_extra = '';

$ciudades_cercanas = [
  ['nombre' => 'Petrer', 'slug' => 'petrer', 'prefijo' => 'fontanero'],
  ['nombre' => 'Novelda', 'slug' => 'novelda', 'prefijo' => 'fontanero'],
  ['nombre' => 'Monóvar', 'slug' => 'monovar', 'prefijo' => 'fontanero'],
  ['nombre' => 'Sax', 'slug' => 'sax', 'prefijo' => 'fontanero'],
  ['nombre' => 'Pinoso', 'slug' => 'pinoso', 'prefijo' => 'fontanero'],
  ['nombre' => 'Monforte del Cid', 'slug' => 'monforte', 'prefijo' => 'fontanero'],
  ['nombre' => 'Salinas', 'slug' => 'salinas', 'prefijo' => 'fontanero'],
];

include __DIR__ . '/../includes/plantilla-servicio.php';
