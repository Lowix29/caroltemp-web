<?php
/**
 * FONTANERO EN NOVELDA
 * /fontanero/fontanero-novelda
 * CONTENIDO PROVISIONAL — sustituir con texto final optimizado
 */

$servicio_nombre = 'Fontanero';
$servicio_slug   = 'fontanero';
$ciudad          = 'Novelda';
$ciudad_slug     = 'novelda';
$ciudad_cp       = '03660';

$meta_title = 'Fontanero en Novelda urgente | 24h | Precio cerrado | Hidrofont';
$meta_desc  = 'Fontanero en Novelda urgente 24h. Precio cerrado sin sorpresas. Llama ahora al 613 429 032.';

$hero_titulo = 'Fontanero en Novelda<br><span class="hl">urgente 24h.</span>';
$hero_sub    = 'Fontaneros en Novelda con precio cerrado. Reparaciones urgentes, detección de fugas sin obras e instalaciones.';

// ================================
// CONTENIDO — SUSTITUIR CON TEXTO FINAL
// ================================
$contenido_intro = '
<p>Si necesitas un <strong>fontanero en Novelda</strong> de confianza, en Hidrofont te damos precio cerrado antes de empezar. Conocemos Novelda y sus instalaciones.</p>
<p>Contenido provisional pendiente de redacción final optimizada para SEO. Este texto debe sustituirse con contenido único sobre fontanero en Novelda.</p>
';

$servicios_lista = [
  'Fontanero urgente en Novelda',
  'Reparación de fugas y grifos en Novelda',
  'Cambio de tuberías y bajantes en Novelda',
  'Instalación de termos Nubeco en Novelda',
  'Reformas de baño en Novelda con precio cerrado',
  'Ósmosis inversa y descalcificadores en Novelda',
];

$problemas_zona = [
  ['titulo' => 'Tuberías antiguas', 'texto' => 'Muchas viviendas en Novelda tienen instalaciones de hierro galvanizado de los años 60-80. Pierden presión y generan fugas ocultas.'],
  ['titulo' => 'Agua dura', 'texto' => 'El agua en Novelda tiene un nivel de cal alto que deteriora griferías, calentadores y electrodomésticos.'],
  ['titulo' => 'Fugas ocultas', 'texto' => 'Las fugas en tuberías empotradas son muy habituales en Novelda. Usamos geófono y cámara para localizarlas sin abrir paredes.'],
];

$faq = [
  ['pregunta' => '¿Cuánto cuesta un fontanero en Novelda?', 'respuesta' => 'Depende del trabajo. Siempre damos precio cerrado antes de empezar. Una reparación sencilla parte desde 60-80€.'],
  ['pregunta' => '¿Tenéis fontanero urgente en Novelda?', 'respuesta' => 'Sí. Atendemos urgencias en Novelda en horario de servicio. Somos de la comarca y llegamos rápido. Llámanos al 613 429 032.'],
  ['pregunta' => '¿Cómo detectáis fugas sin abrir paredes?', 'respuesta' => 'Usamos geófono y cámara endoscópica. Localizamos la fuga exacta antes de abrir nada.'],
  ['pregunta' => '¿Ofrecéis financiación en Novelda?', 'respuesta' => 'Sí. Para reformas, ósmosis, descalcificadores y otros trabajos grandes. Material y mano de obra sin adelantar nada.'],
];

$contenido_extra = '';

$ciudades_cercanas = [
  ['nombre' => 'Elda', 'slug' => 'elda', 'prefijo' => 'fontanero'],
  ['nombre' => 'Petrer', 'slug' => 'petrer', 'prefijo' => 'fontanero'],
  ['nombre' => 'Monóvar', 'slug' => 'monovar', 'prefijo' => 'fontanero'],
  ['nombre' => 'Sax', 'slug' => 'sax', 'prefijo' => 'fontanero'],
  ['nombre' => 'Pinoso', 'slug' => 'pinoso', 'prefijo' => 'fontanero'],
  ['nombre' => 'Monforte del Cid', 'slug' => 'monforte', 'prefijo' => 'fontanero'],
  ['nombre' => 'Salinas', 'slug' => 'salinas', 'prefijo' => 'fontanero'],
];

include __DIR__ . '/../includes/plantilla-servicio.php';
