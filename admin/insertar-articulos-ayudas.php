<?php
/* =========================================================
   CAROLTEMP — Insertar artículos informativos sobre ayudas
   Ejecutar UNA sola vez desde el admin (requiere sesión).
   Los artículos se crean como BORRADORES (publicado=0).
   Publícalos uno a uno desde el panel tras revisarlos.
   ========================================================= */
session_start();
if (!isset($_SESSION['admin_logado']) || $_SESSION['admin_logado'] !== true) {
  die('No autorizado. Inicia sesión en el admin primero.');
}
require_once '../includes/db.php';

// Añadir columna sidebar_tipo si no existe (migración lazy)
try { $pdo->exec("ALTER TABLE articulos ADD COLUMN sidebar_tipo VARCHAR(30) DEFAULT ''"); } catch (Exception $e) {}

// ── ARTÍCULOS ─────────────────────────────────────────────────────────────────

$articulos = [];

// ═══════════════════════════════════════════════════════════════════════════════
// 1. ¿Existen ayudas para cambiar las tuberías de una vivienda?
// ═══════════════════════════════════════════════════════════════════════════════
$articulos[] = [
'titulo'       => '¿Existen ayudas para cambiar las tuberías de una vivienda?',
'slug'         => 'ayudas-cambiar-tuberias-vivienda',
'extracto'     => 'Sí existen ayudas para cambiar las tuberías de una vivienda, aunque dependen del tipo de instalación, la antigüedad del edificio y si se actúa individualmente o como comunidad. Te explicamos todos los programas disponibles en 2026.',
'meta_title'   => 'Ayudas para cambiar tuberías en vivienda 2026 | CarolTemp',
'meta_desc'    => 'Sí existen subvenciones para cambiar tuberías en tu vivienda. Guía 2026 con programas estatales, autonómicos, deducciones IRPF y cómo solicitarlos. Presupuesto gratis.',
'categoria'    => 'fontaneria',
'zona'         => '',
'sidebar_tipo' => '',
'contenido'    => <<<'HTML'
<p>Sí existen ayudas para cambiar las tuberías de una vivienda en España, pero no están todas en un mismo sitio ni son automáticas. <span class='ct-hl'>Depende del tipo de tubería, la antigüedad del edificio y si actúas solo o como comunidad de propietarios.</span> En esta guía te explicamos todos los caminos disponibles en 2026 para reducir el coste de esta obra.</p>

<h2>Lo que nadie te cuenta antes de buscar subvenciones para tuberías</h2>
<p>El primer error que comete la mayoría es buscar "subvención para cambiar tuberías" como si existiera un único programa universal. No lo hay. Las ayudas se organizan por tipo de actuación (rehabilitación energética, accesibilidad, conservación), por quién actúa (particular, comunidad, municipio) y por el nivel de administración que las convoca (estatal, autonómica, local).</p>
<p>Antes de solicitar nada, necesitas saber:</p>
<ul class='ct-tpl-lista'>
  <li>Si las tuberías son <strong>privativas</strong> (solo te afectan a ti) o <strong>elementos comunes</strong> del edificio (bajantes, colectores, acometidas)</li>
  <li>La antigüedad de tu vivienda o edificio — muchos programas exigen más de 20 años de antigüedad</li>
  <li>Si el cambio va asociado a una mejora energética certificable (esto abre la deducción IRPF)</li>
</ul>

<h2>Programas de ayuda disponibles en 2026 para particulares y comunidades</h2>
<p>Estos son los principales mecanismos vigentes:</p>
<ul class='ct-tpl-lista'>
  <li><strong>Plan de Rehabilitación Residencial (Real Decreto 853/2021 — fondos NextGenerationEU):</strong> <span class='ct-hl'>subvención de hasta el 40% del coste</span> de obras de rehabilitación en edificios. Si el edificio tiene deficiencias graves de conservación, puede llegar al 80%. Diseñado especialmente para comunidades de propietarios.</li>
  <li><strong>Deducción en el IRPF por eficiencia energética (Ley 10/2022):</strong> si el cambio de tuberías va asociado a la instalación de un sistema de ACS más eficiente (termosolar, bomba de calor o caldera de condensación), puedes deducirte entre el 20% y el 40% sobre una base de hasta 7.500 € anuales.</li>
  <li><strong>Ayudas de la Generalitat Valenciana — Programa ARRU:</strong> actuaciones de rehabilitación y regeneración urbana. Para edificios en zonas de actuación delimitadas. Más información en <a href='https://habitatge.gva.es' rel='nofollow noopener' target='_blank'>el portal de habitatge de la GVA</a>.</li>
  <li><strong>Plan Renove municipal:</strong> algunos ayuntamientos ofrecen ayudas propias para renovación de instalaciones. Consulta tu ayuntamiento de referencia (Elda, Petrer, Novelda, Novelda, Monóvar…).</li>
</ul>

<h2>Cuánto cuesta cambiar las tuberías y cuánto puedes recuperar</h2>
<p>Los precios orientativos para una vivienda estándar en la zona de Alicante en 2026 son:</p>
<ul class='ct-tpl-lista'>
  <li>Cambio de tuberías interiores en piso de 80 m²: <span class='ct-hl'>entre 2.800 y 6.500 € (materiales + mano de obra + IVA)</span></li>
  <li>Renovación completa en chalet de dos plantas: 5.000–12.000 €</li>
  <li>Renovación de instalación completa en edificio de 6 viviendas: 18.000–40.000 €</li>
</ul>
<p>Con la deducción IRPF del 20% sobre 7.500 €, un particular puede recuperar hasta <span class='ct-hl'>1.500 € al año en la declaración de la renta</span>. Si actúa una comunidad con subvención directa, la recuperación puede ser de varios miles de euros.</p>

<h2>Cómo solicitar las ayudas: los pasos que no puedes saltarte</h2>
<p>El error más habitual es empezar la obra antes de solicitar. Muchos programas exigen que la solicitud sea <strong>previa al inicio de los trabajos</strong>. El proceso habitual es:</p>
<ul class='ct-tpl-lista'>
  <li>Obtener un diagnóstico profesional y presupuesto detallado (imprescindible para la solicitud)</li>
  <li>Identificar el programa aplicable a tu caso</li>
  <li>Presentar solicitud con la documentación requerida antes de iniciar la obra</li>
  <li>Ejecutar la obra con empresa instaladora autorizada y conservar todas las facturas</li>
  <li>Presentar justificación económica y técnica para cobrar la subvención o aplicar la deducción</li>
</ul>
<p>En CarolTemp podemos ayudarte con el diagnóstico previo y la documentación técnica necesaria. Consulta nuestros servicios de <a href='/fontanero/elda'>fontanería en Elda</a> y <a href='/fontanero/petrer'>fontanería en Petrer</a> para más información. El <a href='https://es.wikipedia.org/wiki/Tubería' rel='nofollow noopener' target='_blank'>tipo de tubería y su material</a> determinan tanto el coste de la obra como la ayuda aplicable.</p>

<h2>Preguntas frecuentes sobre ayudas para cambiar tuberías</h2>

<h3>¿Puede un propietario individual pedir subvención para cambiar las tuberías de su piso?</h3>
<p>Sí, pero las ayudas más cuantiosas están orientadas a edificios completos. Para particulares, la deducción en el IRPF es el mecanismo más accesible, siempre que las obras estén asociadas a una mejora energética certificada.</p>

<h3>¿Qué documentación hace falta para solicitar la ayuda?</h3>
<p>Normalmente: presupuesto detallado firmado, informe técnico del estado de la instalación, escritura de propiedad o contrato de arrendamiento, y certificado de empadronamiento. Algunos programas exigen además certificado energético previo a las obras.</p>

<h3>¿Las comunidades de propietarios tienen acceso a más subvención que los particulares?</h3>
<p>Sí, claramente. El Plan de Rehabilitación Residencial puede cubrir hasta el 80% del coste en edificios con problemas graves de conservación. Una comunidad puede recibir decenas de miles de euros de subvención directa.</p>

<h3>¿Hay que cambiar las tuberías antes de pedir la subvención?</h3>
<p>No, al contrario. La mayoría de programas exigen presentar la solicitud antes de iniciar las obras. Empezar sin solicitar es el error más frecuente y puede hacer perder el derecho a la ayuda.</p>

<div class='ct-tpl-cta'><p>¿Necesitas un diagnóstico del estado de tus tuberías para solicitar una ayuda? Te hacemos la inspección y el informe técnico sin compromiso.</p><a href='/contacto' class='ct-tpl-cta-btn'>Solicitar diagnóstico gratis</a></div>
HTML,
];

// ═══════════════════════════════════════════════════════════════════════════════
// 2. Subvenciones para cambiar bajantes comunitarias
// ═══════════════════════════════════════════════════════════════════════════════
$articulos[] = [
'titulo'       => 'Subvenciones para cambiar bajantes comunitarias en 2026',
'slug'         => 'subvenciones-bajantes-comunitarias',
'extracto'     => 'Las bajantes son elementos comunes y su renovación puede financiarse con subvenciones de hasta el 80% del coste a través de los programas de rehabilitación residencial. Te contamos cómo funcionan y qué necesita aprobar tu comunidad.',
'meta_title'   => 'Subvenciones bajantes comunitarias 2026 | CarolTemp',
'meta_desc'    => 'Guía completa de subvenciones para cambiar bajantes en comunidades de propietarios en 2026. Hasta el 80% de subvención directa, plazos, votos necesarios y cómo pedirla.',
'categoria'    => 'fontaneria',
'zona'         => '',
'sidebar_tipo' => '',
'contenido'    => <<<'HTML'
<p>Cambiar las bajantes comunitarias puede costar entre 8.000 y 30.000 euros en un edificio estándar, pero con los programas de rehabilitación residencial vigentes en 2026 <span class='ct-hl'>la comunidad puede recibir una subvención directa de hasta el 80% del coste</span>. El truco está en conocer exactamente qué programa aplica a tu edificio y cómo presentar la solicitud.</p>

<h2>Por qué las bajantes tienen más subvención que otras tuberías</h2>
<p>Las <strong>bajantes</strong> son las tuberías verticales que recogen las aguas residuales de los baños, cocinas y terrazas de cada planta y las conducen al colector general del edificio. La <a href='https://es.wikipedia.org/wiki/Bajante' rel='nofollow noopener' target='_blank'>bajante es un elemento de evacuación de aguas</a> considerado legalmente como elemento común según el artículo 396 del Código Civil y la Ley de Propiedad Horizontal.</p>
<p>Al ser elementos comunes, su renovación entra dentro de los programas de rehabilitación de edificios, que tienen dotaciones presupuestarias muy superiores a las ayudas para viviendas individuales. Esta es la razón por la que una comunidad puede acceder a subvenciones que un propietario individual nunca obtendría.</p>

<h2>Programas de subvención para bajantes en comunidades: cuáles funcionan en 2026</h2>
<p>Los tres programas principales son:</p>
<ul class='ct-tpl-lista'>
  <li><strong>Programa de ayuda a la rehabilitación residencial (Real Decreto 853/2021):</strong> financia hasta el <span class='ct-hl'>40% del presupuesto protegido</span> en edificios con más de 10 años. Si el informe de evaluación del edificio (IEE) detecta deficiencias significativas en las instalaciones, la cuantía puede llegar al 80%.</li>
  <li><strong>Programa de barrios en situación de vulnerabilidad:</strong> para edificios en zonas delimitadas por los ayuntamientos. Puede complementarse con la subvención estatal, alcanzando en algunos casos el 100% de la obra.</li>
  <li><strong>Deducción en el IRPF (colectiva):</strong> si la renovación de bajantes forma parte de una rehabilitación más amplia del edificio con mejora energética, cada propietario puede deducirse hasta el <span class='ct-hl'>60% de su parte proporcional del coste</span> en la declaración de la renta.</li>
</ul>

<h2>Cuántos votos necesita la comunidad para aprobar el cambio de bajantes</h2>
<p>Aquí está uno de los cuellos de botella habituales. Según la Ley de Propiedad Horizontal:</p>
<ul class='ct-tpl-lista'>
  <li><strong>Obras de conservación obligatoria</strong> (cuando el IEE las declara necesarias): basta con la mayoría simple de la Junta de propietarios. El propietario que vote en contra sigue obligado a pagar su parte.</li>
  <li><strong>Obras de mejora no obligatorias:</strong> se requiere mayoría de 3/5 de propietarios y cuotas de participación.</li>
  <li><strong>Derrama extraordinaria:</strong> si el importe supera tres mensualidades de cuota ordinaria, cada propietario puede optar por no pagar su parte si votó en contra, aunque pierde el beneficio de la mejora.</li>
</ul>
<p>Si las bajantes están en mal estado y causan problemas (filtraciones, malos olores, atascos frecuentes), el administrador de fincas puede instar su reparación como obra necesaria, lo que simplifica la aprobación.</p>

<h2>Cuánto cuesta cambiar las bajantes de un edificio en 2026</h2>
<p>Los precios varían según el número de plantas, el material actual (fibrocemento, hierro fundido o PVC) y el acceso a las tuberías. Estimaciones orientativas:</p>
<ul class='ct-tpl-lista'>
  <li>Edificio de 4 plantas, bajante simple: <span class='ct-hl'>entre 1.500 y 3.500 € por bajante</span></li>
  <li>Edificio de 6 plantas con varias bajantes (aguas grises y negras separadas): 8.000–18.000 €</li>
  <li>Renovación completa de saneamiento en edificio de 10 viviendas: 20.000–45.000 €</li>
</ul>
<p>Con una subvención del 40%, una comunidad de 10 vecinos ahorraría entre 8.000 y 18.000 € en el escenario completo. Y si el edificio cumple los criterios para el 80%, el ahorro se duplica.</p>

<h2>Cómo gestiona la comunidad la solicitud de subvención</h2>
<p>El proceso habitual que recomendamos a las comunidades de Elda, Petrer y Novelda es:</p>
<ul class='ct-tpl-lista'>
  <li>Encargar el Informe de Evaluación del Edificio (IEE) a un técnico certificado</li>
  <li>Convocar Junta extraordinaria para aprobar las obras y autorizar al presidente a solicitar la subvención</li>
  <li>Presentar la solicitud en la sede de la Generalitat Valenciana o ayuntamiento antes de iniciar la obra</li>
  <li>Contratar empresa instaladora autorizada para ejecutar la obra</li>
  <li>Presentar justificación con facturas y certificados de obra terminada</li>
</ul>
<p>Muchas gestorías de comunidades de propietarios gestionan el trámite. En CarolTemp nos encargamos de la parte técnica: diagnóstico, presupuesto detallado y certificado de obra terminada. Consulta nuestro servicio de <a href='/fontanero/novelda'>fontanero en Novelda</a> o <a href='/fontanero/elda'>fontanero en Elda</a> para iniciar el proceso.</p>

<h2>Preguntas frecuentes sobre subvenciones para bajantes comunitarias</h2>

<h3>¿Las bajantes son siempre un elemento común?</h3>
<p>Sí. Las bajantes, colectores generales y arquetas son elementos comunes según el artículo 396 del Código Civil. Su mantenimiento y renovación corresponde a la comunidad, independientemente de por dónde pasen (incluso si cruzan por dentro de un piso privativo).</p>

<h3>¿La subvención cubre también la reparación de daños en los pisos?</h3>
<p>No directamente. La subvención cubre la obra de renovación de las bajantes. Los daños en el interior de los pisos (pintura, solados) que sean consecuencia de la obra se gestionan aparte, normalmente con el seguro de la comunidad.</p>

<h3>¿Cuánto tarda en concederse la subvención?</h3>
<p>Dependiendo del programa y la Comunidad Autónoma, entre 3 y 9 meses desde la solicitud. En muchos casos se puede adelantar la ejecución de la obra y recibir la subvención posteriormente, siempre que la solicitud sea previa a las obras.</p>

<h3>¿Se puede financiar la parte que no cubre la subvención?</h3>
<p>Sí. Existen líneas de financiación del ICO y de algunas entidades bancarias específicamente para comunidades de propietarios que realizan obras de rehabilitación. La cuota mensual por propietario puede ser muy reducida.</p>

<div class='ct-tpl-cta'><p>¿Quieres saber el estado de las bajantes de tu edificio? Te hacemos la inspección y el presupuesto para que puedas presentar la solicitud de subvención.</p><a href='/contacto' class='ct-tpl-cta-btn'>Pedir presupuesto sin compromiso</a></div>
HTML,
];

// ═══════════════════════════════════════════════════════════════════════════════
// 3. Ayudas para sustituir tuberías de uralita
// ═══════════════════════════════════════════════════════════════════════════════
$articulos[] = [
'titulo'       => 'Ayudas para sustituir tuberías de uralita o fibrocemento con amianto',
'slug'         => 'ayudas-sustituir-tuberias-uralita-amianto',
'extracto'     => 'Las tuberías de uralita o fibrocemento con amianto suponen un riesgo para la salud cuando se deterioran. En 2026 existen ayudas específicas para su retirada y sustitución, tanto para viviendas como para comunidades de propietarios.',
'meta_title'   => 'Ayudas tuberías de uralita y amianto 2026 | CarolTemp',
'meta_desc'    => 'Guía 2026 sobre ayudas para retirar tuberías de uralita o fibrocemento con amianto. Riesgos, empresas autorizadas RERA, subvenciones disponibles y precios orientativos.',
'categoria'    => 'fontaneria',
'zona'         => '',
'sidebar_tipo' => '',
'contenido'    => <<<'HTML'
<p>Las tuberías de uralita (fibrocemento con amianto) instaladas antes de los años 90 pueden liberar fibras de <a href='https://es.wikipedia.org/wiki/Amianto' rel='nofollow noopener' target='_blank'>amianto, una sustancia cancerígena clasificada en el grupo 1 por la OMS</a>, cuando se deterioran o manipulan de forma incorrecta. <span class='ct-hl'>Su retirada no es solo conveniente: en muchos casos es obligatoria y existen ayudas para financiarla.</span></p>

<h2>El riesgo silencioso de las tuberías de uralita que llevan décadas en tu edificio</h2>
<p>El fibrocemento con amianto fue un material muy utilizado en España hasta su prohibición definitiva en 2002. Se empleó en tuberías de bajante, tuberías de suministro de agua fría y en elementos de cubierta. En buen estado y sin manipular, el riesgo es bajo. El peligro aparece cuando la tubería:</p>
<ul class='ct-tpl-lista'>
  <li>Presenta corrosión, grietas o fisuras</li>
  <li>Se somete a trabajos de corte, perforación o lijado sin protección</li>
  <li>Se deteriora por antigüedad (más de 40-50 años)</li>
  <li>Está en contacto con agua caliente de forma continuada</li>
</ul>
<p>En esas situaciones, las fibras de amianto pueden liberarse al agua o al aire y constituyen un riesgo real para la salud de los residentes.</p>

<h2>¿Hay ayudas específicas para tuberías de uralita en 2026?</h2>
<p>No existe un programa nacional exclusivo para tuberías de amianto domésticas, pero la retirada puede financiarse a través de varios programas:</p>
<ul class='ct-tpl-lista'>
  <li><strong>Plan de Rehabilitación Residencial (RD 853/2021):</strong> la sustitución de tuberías de fibrocemento es una actuación de conservación reconocida. Como parte de una rehabilitación integral del edificio, puede recibir <span class='ct-hl'>hasta el 40-80% de subvención directa</span>.</li>
  <li><strong>Ayudas de la Generalitat Valenciana:</strong> la Conselleria de Medi Ambient ha gestionado fondos para retirada de amianto en edificios públicos y privados. Conviene consultar convocatorias abiertas en cada ejercicio.</li>
  <li><strong>Ayudas municipales:</strong> algunos ayuntamientos del interior de Alicante han tenido programas propios de retirada de uralita en cubiertas y tuberías. Consulta en tu ayuntamiento.</li>
  <li><strong>Deducción IRPF:</strong> si la sustitución va unida a una mejora energética certificada, la obra puede incluirse en la base de deducción por eficiencia energética.</li>
</ul>

<h2>Cómo saber si tus tuberías son de uralita</h2>
<p>Si tu edificio tiene más de 30-35 años, es muy posible que las bajantes o las tuberías de conducción general sean de fibrocemento. Las características visuales son:</p>
<ul class='ct-tpl-lista'>
  <li>Color grisáceo uniforme, sin la textura brillante del PVC ni el color rojizo del cobre</li>
  <li>Textura rugosa y aspecto similar al cemento</li>
  <li>Uniones mediante manguitos de material similar, sin soldaduras</li>
  <li>Rotulado de marca o año de fabricación en la tubería (a veces legible)</li>
</ul>
<p>Ante la duda, un técnico especializado puede tomar una muestra y analizarla. No manipules la tubería sin protección si sospechas que puede contener amianto.</p>

<h2>Cuánto cuesta retirar tuberías de uralita</h2>
<p>La retirada de amianto requiere empresa inscrita en el <strong>RERA (Registro de Empresas con Riesgo de Amianto)</strong> y cumplir el Real Decreto 396/2006. Los precios incluyen equipos de protección, gestión de residuos peligrosos y documentación:</p>
<ul class='ct-tpl-lista'>
  <li>Retirada de tubería de uralita (por metro lineal): <span class='ct-hl'>entre 35 y 75 € según accesibilidad</span></li>
  <li>Bajante completa de 10 m en edificio con acceso fácil: 600–1.200 €</li>
  <li>Renovación completa de bajantes en edificio de 4 plantas: 4.000–9.000 €</li>
  <li>Gestión y transporte de residuos peligrosos (amianto): incluido en el precio anterior</li>
</ul>
<p>A estos costes hay que sumar la instalación de las nuevas tuberías (PVC o polietileno), que oscila entre 800 y 2.500 € adicionales según el tramo.</p>

<h2>Qué empresa puede retirar tuberías de amianto: el RERA es obligatorio</h2>
<p>Solo pueden manipular y retirar amianto las empresas inscritas en el RERA, el registro oficial de empresas con riesgo de amianto. Contratar a una empresa no inscrita es ilegal, anula cualquier seguro y puede generar responsabilidad civil. Verifica siempre la inscripción antes de firmar el contrato.</p>
<p>CarolTemp trabaja con empresas de retirada de amianto homologadas para la gestión del material peligroso, y nos encargamos de la instalación de las tuberías nuevas. Consulta nuestros servicios de <a href='/fontanero/monovar'>fontanería en Monóvar</a> y <a href='/fontanero/sax'>fontanería en Sax</a>.</p>

<h2>Preguntas frecuentes sobre tuberías de uralita</h2>

<h3>¿Es obligatorio retirar las tuberías de uralita?</h3>
<p>No existe una obligación general de retirada inmediata, pero sí está prohibido manipularlas sin medidas de protección. Si el informe técnico del edificio (IEE) detecta que están deterioradas, la comunidad sí está obligada a actuar. Además, cualquier obra que las afecte requiere protocolo de amianto.</p>

<h3>¿Quién paga la retirada de tuberías de uralita en una comunidad?</h3>
<p>La comunidad de propietarios, ya que son elementos comunes. Si hay subvención, la cubren parcialmente. El resto se reparte entre los propietarios según su cuota de participación.</p>

<h3>¿Se puede instalar directamente PVC sobre la tubería de uralita existente?</h3>
<p>No. La tubería de uralita debe retirarse correctamente por empresa RERA antes de instalar la nueva. Forrar o tapar la tubería antigua sin retirarla no es legal y genera problemas a largo plazo.</p>

<h3>¿Dónde se depositan los residuos de amianto?</h3>
<p>En vertederos autorizados para residuos peligrosos, según la legislación vigente. La empresa RERA gestiona el transporte y la documentación de trazabilidad del residuo, lo que protege al propietario de cualquier responsabilidad futura.</p>

<div class='ct-tpl-cta'><p>¿Sospechas que tu edificio tiene tuberías de uralita? Te ayudamos con el diagnóstico y coordinamos la retirada y sustitución con empresa homologada.</p><a href='/contacto' class='ct-tpl-cta-btn'>Solicitar inspección gratuita</a></div>
HTML,
];

// ═══════════════════════════════════════════════════════════════════════════════
// 4. Ayudas para cambiar una bañera por plato de ducha
// ═══════════════════════════════════════════════════════════════════════════════
$articulos[] = [
'titulo'       => 'Ayudas para cambiar una bañera por plato de ducha en 2026',
'slug'         => 'ayudas-cambiar-banera-plato-ducha',
'extracto'     => 'Cambiar la bañera por un plato de ducha puede tener ayudas de hasta el 80% del coste si hay personas mayores o con discapacidad en la vivienda. Te explicamos todos los programas disponibles en 2026 y cómo acceder a ellos.',
'meta_title'   => 'Ayudas cambiar bañera por ducha 2026 | CarolTemp',
'meta_desc'    => 'Guía 2026 de subvenciones y deducciones para cambiar bañera por plato de ducha. Accesibilidad, IRPF, programas autonómicos y precios. Presupuesto gratis sin compromiso.',
'categoria'    => 'reformas',
'zona'         => '',
'sidebar_tipo' => '',
'contenido'    => <<<'HTML'
<p>Cambiar una bañera por un plato de ducha es una de las reformas de baño más solicitadas y también una de las que más ayudas puede recibir. <span class='ct-hl'>Si en la vivienda hay personas mayores de 65 años o con alguna discapacidad, las subvenciones pueden cubrir hasta el 80% del coste</span> a través de los programas de accesibilidad. Y aunque no haya discapacidad, sigue habiendo deducciones fiscales aplicables.</p>

<h2>Más dinero del que imaginas: las ayudas que casi nadie conoce para este cambio</h2>
<p>La mayoría de las personas que quieren cambiar su bañera por una ducha lo ven como una reforma estética o de comodidad. Pero desde el punto de vista de las ayudas, lo que importa es el ángulo de la accesibilidad: una ducha sin barreras (plato enrasado con el suelo o ducha de obra a nivel) facilita el acceso a personas con movilidad reducida, y eso abre la puerta a múltiples programas de subvención.</p>

<h2>Programas de ayuda disponibles para cambiar bañera por ducha en 2026</h2>
<ul class='ct-tpl-lista'>
  <li><strong>Deducción IRPF por obras de accesibilidad:</strong> las obras que mejoran la accesibilidad de la vivienda habitual para personas con discapacidad o mayores de 65 años tienen una deducción del <span class='ct-hl'>15% sobre un máximo de 15.000 €</span> en la cuota íntegra del IRPF. La deducción es de 9.040 € anuales de base para adaptaciones de baño.</li>
  <li><strong>Plan Estatal de Vivienda — Programa de mejora de la accesibilidad:</strong> subvenciones directas para la adaptación de viviendas de personas mayores o con discapacidad. El importe puede cubrir <span class='ct-hl'>hasta el 50% del coste de la adaptación</span>, con un máximo de 8.000 €.</li>
  <li><strong>Ayudas de servicios sociales municipales:</strong> muchos ayuntamientos del interior de Alicante (Elda, Petrer, Novelda) tienen programas propios de ayuda para la adaptación del hogar a personas mayores con pocos recursos. Consulta en el servicio de atención social de tu municipio.</li>
  <li><strong>ONCE e IMSERSO:</strong> para personas con discapacidad reconocida oficialmente, existen convocatorias específicas de ayudas a la adaptación del hogar. Consulta con el trabajador social de referencia.</li>
  <li><strong>Comunidades de propietarios:</strong> si el cambio implica obras en elementos comunes (suelo, bajante), la comunidad puede solicitar ayudas del Plan de Rehabilitación, que pueden complementar la ayuda individual.</li>
</ul>

<h2>¿Qué tipo de ducha tiene más opciones de subvención?</h2>
<p>Las subvenciones de accesibilidad están orientadas a eliminar barreras. Por eso, el tipo de ducha más favorecido es:</p>
<ul class='ct-tpl-lista'>
  <li><strong>Plato de ducha a nivel del suelo</strong> (ducha italiana o de obra sin escalón): máxima accesibilidad, más subvención</li>
  <li><strong>Plato de ducha bajo</strong> (menos de 3 cm de escalón) con silla plegable anclada a la pared</li>
  <li>Con asideros o barras de apoyo certificadas</li>
  <li>Con grifería monomando ergonómica o termostática de fácil manejo</li>
</ul>
<p>Un simple plato de ducha estético sin adaptaciones de accesibilidad no da acceso a las subvenciones de discapacidad, aunque sí puede incluirse en la deducción IRPF general si va asociada a obras de rehabilitación energética.</p>

<h2>Cuánto cuesta cambiar una bañera por un plato de ducha en 2026</h2>
<p>Los precios orientativos en la comarca de Alicante para 2026:</p>
<ul class='ct-tpl-lista'>
  <li>Kit de conversión de bañera a ducha (sin obra): <span class='ct-hl'>desde 250 € (solución rápida, sin obras)</span></li>
  <li>Cambio de bañera por plato de ducha estándar (con obra básica): 700–1.500 €</li>
  <li>Ducha de obra a nivel del suelo con azulejos nuevos: 1.500–3.500 €</li>
  <li>Baño accesible completo (ducha a nivel + barras + grifo termostático): 2.500–5.500 €</li>
</ul>
<p>Con las ayudas de accesibilidad, una persona mayor podría pagar solo entre 500 y 1.100 € por un baño completamente adaptado.</p>

<h2>Cómo gestionar las ayudas: lo que tienes que hacer antes de empezar la obra</h2>
<p>El proceso más habitual para las ayudas de accesibilidad es:</p>
<ul class='ct-tpl-lista'>
  <li>Obtener certificado de discapacidad o acreditar la edad (mayores de 65 años)</li>
  <li>Solicitar informe del médico de cabecera o trabajador social recomendando la adaptación</li>
  <li>Pedir presupuesto detallado a empresa de fontanería y reformas</li>
  <li>Presentar solicitud en el ayuntamiento o CCAA antes de iniciar la obra</li>
  <li>Ejecutar la obra y guardar facturas para la justificación</li>
</ul>
<p>En CarolTemp realizamos reformas completas de baño con toda la documentación técnica necesaria para tramitar las ayudas. Consulta nuestros servicios de <a href='/fontanero/elda'>reformas de baño en Elda</a> y zona. El cambio de bañera implica también la renovación de la <a href='https://es.wikipedia.org/wiki/Fontanería' rel='nofollow noopener' target='_blank'>instalación de fontanería y desagüe</a>, que CarolTemp gestiona de inicio a fin.</p>

<h2>Preguntas frecuentes sobre ayudas para cambiar bañera por ducha</h2>

<h3>¿Se necesita certificado de discapacidad para pedir la subvención de accesibilidad?</h3>
<p>Para las ayudas específicas de accesibilidad para personas con discapacidad, sí. Para las ayudas para mayores de 65 años, basta con acreditar la edad. Para la deducción IRPF general, no hace falta ningún certificado especial.</p>

<h3>¿La comunidad de propietarios tiene que aprobar la obra?</h3>
<p>Solo si la obra afecta a elementos comunes (suelo estructural, bajante general). Para el cambio de bañera dentro del piso privativo, el propietario puede hacerlo sin permiso de la comunidad, aunque es recomendable comunicarlo al administrador de fincas.</p>

<h3>¿Puedo pedir ayuda aunque no tenga discapacidad reconocida?</h3>
<p>Sí. La deducción IRPF por obras de accesibilidad en vivienda habitual no requiere certificado de discapacidad cuando el beneficiario es mayor de 65 años. Para personas menores de 65 sin discapacidad, el cambio puede incluirse en la deducción por eficiencia energética si la reforma es más amplia.</p>

<h3>¿La subvención paga también los azulejos y la pintura del baño?</h3>
<p>Depende del programa. Las ayudas de accesibilidad cubren las partidas directamente relacionadas con la accesibilidad (plato, barras, grifo). La renovación estética del baño (azulejos, pintura) suele quedar fuera, aunque en una obra integral muchas veces se hace de forma simultánea.</p>

<div class='ct-tpl-cta'><p>¿Quieres cambiar tu bañera por una ducha accesible? Te asesoramos sobre ayudas disponibles y hacemos el presupuesto sin compromiso.</p><a href='/contacto' class='ct-tpl-cta-btn'>Pedir presupuesto de reforma de baño</a></div>
HTML,
];

// ═══════════════════════════════════════════════════════════════════════════════
// 5. Qué reformas de fontanería desgravan en la renta
// ═══════════════════════════════════════════════════════════════════════════════
$articulos[] = [
'titulo'       => 'Qué reformas de fontanería desgravan en la renta en 2026',
'slug'         => 'reformas-fontaneria-desgravacion-renta-irpf',
'extracto'     => 'No todas las reformas de fontanería desgravan en la declaración de la renta, pero muchas sí. En 2026 existen tres tipos de deducción IRPF que pueden aplicarse a obras de fontanería: eficiencia energética, accesibilidad y rehabilitación. Te explicamos cuáles son y cómo aplicarlas.',
'meta_title'   => 'Reformas fontanería que desgravan en renta 2026 | CarolTemp',
'meta_desc'    => 'Descubre qué obras de fontanería desgravan en el IRPF 2026: cambio de termo, caldera, tuberías y adaptación de baño. Porcentajes, bases y cómo declararlo correctamente.',
'categoria'    => 'fontaneria',
'zona'         => '',
'sidebar_tipo' => '',
'contenido'    => <<<'HTML'
<p>No todas las reformas de fontanería te permiten deducirte algo en la declaración de la renta, pero más de las que imaginas sí lo hacen. <span class='ct-hl'>En 2026 hay tres vías de deducción IRPF que pueden aplicarse a obras de fontanería: eficiencia energética, accesibilidad y el régimen general de rehabilitación.</span> El truco está en encuadrarlas correctamente.</p>

<h2>Lo que Hacienda no te explica sobre deducir reformas de fontanería</h2>
<p>La <a href='https://www.agenciatributaria.es' rel='nofollow noopener' target='_blank'>Agencia Tributaria</a> no tiene una categoría llamada "obras de fontanería". Las deducciones se agrupan por el tipo de mejora que produce la obra, no por el gremio que la ejecuta. Por eso, un cambio de termo puede ser deducible si mejora la eficiencia energética de la vivienda, pero no si simplemente sustituyes uno roto por otro igual.</p>
<p>La clave es que la obra <strong>mejore objetivamente el inmueble</strong> en términos de eficiencia energética, accesibilidad o habitabilidad, y que puedas acreditarlo con la documentación adecuada.</p>

<h2>Deducción por eficiencia energética: la más generosa para reformas de fontanería</h2>
<p>Regulada por el Real Decreto-ley 19/2021, permite deducir obras que mejoren la calificación energética de la vivienda. Estas son las situaciones de fontanería que pueden encajar:</p>
<ul class='ct-tpl-lista'>
  <li><strong>Instalación de caldera de condensación o biomasa:</strong> si sustituyes una caldera antigua por una de condensación, <span class='ct-hl'>puedes deducirte el 20% sobre hasta 7.500 € anuales</span> siempre que mejore la letra energética.</li>
  <li><strong>Sistema de producción de ACS por energía solar o bomba de calor:</strong> deducción del 20-40% si mejora la demanda energética.</li>
  <li><strong>Instalación de termo de bomba de calor (aerotermia):</strong> sustituir un termo eléctrico convencional por uno de bomba de calor puede generar una deducción del 20%.</li>
  <li><strong>Renovación de tuberías con aislamiento mejorado:</strong> si las tuberías nuevas llevan un aislamiento térmico superior al anterior y eso se refleja en el certificado energético, puede incluirse en la base de deducción.</li>
</ul>
<p>Requisito imprescindible: certificado energético antes y después de la obra emitido por técnico habilitado. Sin ese certificado, no hay deducción.</p>

<h2>Deducción por accesibilidad: para baños adaptados y grifería especial</h2>
<p>Las obras que facilitan el acceso y uso de la vivienda a personas con discapacidad o mayores de 65 años tienen su propia deducción: el <span class='ct-hl'>15% de las cantidades satisfechas, con una base máxima de 9.040 €</span>. En fontanería, aplica a:</p>
<ul class='ct-tpl-lista'>
  <li>Sustitución de bañera por ducha accesible (plato a nivel o con escalón mínimo)</li>
  <li>Instalación de grifo termostático o monomando de fácil manejo</li>
  <li>Barras de apoyo y asideros en baño y ducha</li>
  <li>Adaptación de la altura de lavabos e inodoros</li>
</ul>
<p>Para aplicar esta deducción no es obligatorio tener un certificado de discapacidad si el beneficiario tiene más de 65 años. Con un informe del médico de cabecera que justifique la necesidad de la adaptación es suficiente en la mayoría de los casos.</p>

<h2>Qué gastos de fontanería SÍ desgravan y cuáles NO</h2>
<p><strong>SÍ desgravan (con la justificación adecuada):</strong></p>
<ul class='ct-tpl-lista'>
  <li>Cambio de caldera por modelo de mayor eficiencia + certificado energético</li>
  <li>Instalación de termo de bomba de calor en sustitución de termo eléctrico</li>
  <li>Plato de ducha accesible para persona mayor o con movilidad reducida</li>
  <li>Grifería termostática + barras de apoyo en baño adaptado</li>
  <li>Renovación de tuberías como parte de una rehabilitación energética certificada</li>
</ul>
<p><strong>NO desgravan (obras de reparación o sustitución simple):</strong></p>
<ul class='ct-tpl-lista'>
  <li>Reparación de una fuga o avería (no mejora el inmueble)</li>
  <li>Cambio de un grifo roto por otro igual</li>
  <li>Sustitución de un termo por otro de las mismas características</li>
  <li>Reparación de un desagüe atascado</li>
</ul>

<h2>Cómo declarar las obras de fontanería en la renta: documentación necesaria</h2>
<p>Para justificar la deducción ante Hacienda necesitas:</p>
<ul class='ct-tpl-lista'>
  <li><span class='ct-hl'>Factura completa con IVA</span> desglosado, NIF del instalador y descripción de la obra</li>
  <li>Certificado energético antes y después (para deducciones de eficiencia energética)</li>
  <li>Justificante de pago (transferencia bancaria, recibo; nunca en efectivo si supera 1.000 €)</li>
  <li>Para accesibilidad: informe médico o certificado de discapacidad o acreditación de edad</li>
</ul>
<p>En CarolTemp emitimos facturas desglosadas que facilitan la declaración y podemos asesorarte sobre la documentación adicional que necesites. Consulta nuestros servicios en <a href='/fontanero/petrer'>Petrer</a>, <a href='/fontanero/elda'>Elda</a> y comarca. El <a href='https://es.wikipedia.org/wiki/Impuesto_sobre_la_Renta_de_las_Personas_F%C3%ADsicas' rel='nofollow noopener' target='_blank'>IRPF y sus deducciones</a> varían cada año, por lo que conviene consultar con un asesor fiscal antes de aplicarlas.</p>

<h2>Preguntas frecuentes sobre desgravación de reformas de fontanería en la renta</h2>

<h3>¿Hace falta pagar con tarjeta o transferencia para que la obra sea deducible?</h3>
<p>Sí, para importes superiores a 1.000 € la ley exige que el pago no sea en efectivo. Para obras menores, el pago en efectivo es legal pero Hacienda puede cuestionarlo si no hay otros justificantes. Siempre mejor pagar con medios rastreables.</p>

<h3>¿Puedo deducirme una reforma en un piso que tengo alquilado?</h3>
<p>Las deducciones de eficiencia energética y accesibilidad aplican a la vivienda habitual del contribuyente, no a inmuebles en alquiler. Para pisos alquilados, los gastos de reparación y mantenimiento son deducibles como gasto del rendimiento de capital inmobiliario.</p>

<h3>¿Cuánto tiempo tengo para aplicar la deducción después de hacer la obra?</h3>
<p>La deducción se aplica en el año fiscal en que se pagan las cantidades. Si la obra abarca varios años, puede distribuirse en varios ejercicios hasta agotar la base máxima permitida por el programa.</p>

<h3>¿Las obras en elementos comunes de la comunidad también desgravan individualmente?</h3>
<p>Sí. Si la comunidad hace obras de rehabilitación energética o accesibilidad, cada propietario puede aplicar la deducción sobre su cuota proporcional del coste, con los mismos porcentajes y bases que en obras individuales. La comunidad debe emitir certificación a cada propietario.</p>

<div class='ct-tpl-cta'><p>¿Quieres hacer una reforma de fontanería y aprovechar la desgravación? Te asesoramos y emitimos la documentación necesaria para que puedas aplicarla correctamente.</p><a href='/contacto' class='ct-tpl-cta-btn'>Consultar mi caso concreto</a></div>
HTML,
];

// ═══════════════════════════════════════════════════════════════════════════════
// 6. Quién paga una fuga de agua en una comunidad
// ═══════════════════════════════════════════════════════════════════════════════
$articulos[] = [
'titulo'       => 'Quién paga una fuga de agua en una comunidad de propietarios',
'slug'         => 'quien-paga-fuga-agua-comunidad-vecinos',
'extracto'     => 'Determinar quién paga una fuga de agua en una comunidad depende de dónde está la rotura y si la tubería es privativa o elemento común. La Ley de Propiedad Horizontal establece las reglas, pero el seguro de la comunidad suele ser la solución más rápida.',
'meta_title'   => 'Quién paga fuga de agua en comunidad 2026 | CarolTemp',
'meta_desc'    => 'Guía clara sobre quién paga una fuga de agua en una comunidad de vecinos. Ley de Propiedad Horizontal, elementos comunes vs privativos, seguro y plazos de reclamación.',
'categoria'    => 'fontaneria',
'zona'         => '',
'sidebar_tipo' => '',
'contenido'    => <<<'HTML'
<p>Cuando aparece una fuga de agua en un edificio, la primera pregunta es siempre la misma: ¿quién paga? La respuesta depende de un único criterio clave: <span class='ct-hl'>si la tubería que falla es un elemento privativo del propietario o un elemento común del edificio.</span> La Ley de Propiedad Horizontal lo regula con claridad, aunque en la práctica surgen muchas dudas.</p>

<h2>La fuga que nadie quiere pagar: por qué la responsabilidad genera conflictos</h2>
<p>Las fugas de agua son una de las principales causas de conflicto entre vecinos y comunidades. El agua no distingue fronteras: una rotura en la tubería del piso de arriba puede causar daños en el piso de abajo, y viceversa. Y si la fuga está en un elemento común (bajante, colector, instalación general), los daños pueden afectar a varios pisos a la vez.</p>
<p>El primer paso siempre debe ser <span class='ct-hl'>localizar el origen de la fuga con precisión</span> antes de discutir quién paga. Sin esa información, cualquier reclamación está en el aire. En CarolTemp utilizamos geófono (dispositivo de escucha para detectar fugas sin obra) y cámaras de inspección para localizar el punto exacto sin destrozar.</p>

<h2>Qué dice la Ley de Propiedad Horizontal sobre las fugas de agua</h2>
<p>La <a href='https://es.wikipedia.org/wiki/Ley_de_Propiedad_Horizontal_(España)' rel='nofollow noopener' target='_blank'>Ley de Propiedad Horizontal (Ley 49/1960)</a> establece la distinción fundamental:</p>
<ul class='ct-tpl-lista'>
  <li><strong>Elementos privativos</strong> (responsabilidad del propietario): instalación de fontanería desde la llave de paso del piso hacia adelante, griferías, sanitarios, tuberías interiores del piso</li>
  <li><strong>Elementos comunes</strong> (responsabilidad de la comunidad): bajantes, colectores generales, arquetas, tuberías de suministro hasta el contador de cada vivienda, instalación de agua general del edificio</li>
</ul>
<p>Si la fuga está en la instalación privativa del propietario, él paga la reparación y los daños que cause a terceros. Si la fuga está en un elemento común, la comunidad paga la reparación. Los daños en elementos privativos causados por una avería en un elemento común los paga la comunidad (o el seguro comunitario).</p>

<h2>Cuándo paga el propietario y cuándo paga la comunidad</h2>
<p>Casos habituales y su resolución:</p>
<ul class='ct-tpl-lista'>
  <li><strong>Fuga en el grifo o tubería interior del piso:</strong> paga el propietario. Es instalación privativa.</li>
  <li><strong>Fuga en la bajante (tubería vertical de evacuación):</strong> paga la comunidad. Es elemento común aunque pase por dentro de un piso privativo.</li>
  <li><strong>Fuga en la tubería de suministro entre el contador y la acometida:</strong> paga la comunidad.</li>
  <li><strong>Fuga en el calentador o termo de un piso:</strong> paga el propietario. Es instalación privativa.</li>
  <li><strong>Daños en el piso de abajo por fuga del piso de arriba:</strong> paga el propietario del piso donde está la avería (o su seguro de hogar).</li>
</ul>
<p>La regla práctica: <span class='ct-hl'>todo lo que esté antes del contador (contando desde la red de suministro) es responsabilidad de la comunidad. Todo lo que esté después del contador es responsabilidad del propietario.</span></p>

<h2>El papel del seguro de la comunidad en una fuga de agua</h2>
<p>La mayoría de comunidades tienen contratado un seguro multirriesgo que cubre, entre otras cosas, los daños por agua. Este seguro suele cubrir:</p>
<ul class='ct-tpl-lista'>
  <li>Reparación de la avería en elementos comunes</li>
  <li>Daños materiales en elementos privativos causados por la avería común</li>
  <li>Gastos de búsqueda y localización de la fuga</li>
</ul>
<p>Si la comunidad tiene buen seguro, <span class='ct-hl'>el vecino afectado puede reclamar al seguro de la comunidad directamente</span> sin necesidad de pleito entre particulares. Es la vía más rápida y menos conflictiva. Contacta con el administrador de fincas para activar la cobertura.</p>

<h2>Qué hacer si la comunidad se niega a reparar la fuga</h2>
<p>Si el presidente o la comunidad no actúa con la urgencia necesaria ante una fuga en elementos comunes, el propietario afectado puede:</p>
<ul class='ct-tpl-lista'>
  <li>Exigir por escrito (burofax) a la comunidad que actúe con plazo concreto</li>
  <li>En casos urgentes, reparar por su cuenta y reclamar el importe a la comunidad posteriormente</li>
  <li>Recurrir al Juzgado de Primera Instancia si hay negativa manifiesta a actuar</li>
</ul>
<p>En CarolTemp atendemos <span class='ct-hl'>urgencias 24h todos los días</span>. Si hay una fuga activa, no esperes. Llama y paramos el daño primero; los aspectos legales se resuelven después. Ofrecemos servicio de detección de fugas en <a href='/fontanero/elda'>Elda</a>, <a href='/fontanero/novelda'>Novelda</a> y toda la comarca.</p>

<h2>Preguntas frecuentes sobre quién paga una fuga en comunidad</h2>

<h3>¿Qué es exactamente un elemento común en fontanería?</h3>
<p>Son todos los conductos e instalaciones de suministro y saneamiento que sirven a más de un propietario o que van desde la red exterior hasta el contador de cada piso. Incluyen bajantes, colectores, acometidas, tuberías de la fachada y las instalaciones generales del edificio.</p>

<h3>¿El seguro de hogar cubre los daños causados por fugas del vecino de arriba?</h3>
<p>Depende de la póliza. Muchos seguros de hogar incluyen cobertura de daños por agua de terceros. Si el causante es el vecino (avería en su instalación privativa), normalmente se reclama a su seguro de hogar o directamente a él si no tiene seguro.</p>

<h3>¿Hay un plazo para reclamar daños por agua?</h3>
<p>El plazo general de prescripción para reclamar daños es de 1 año (acciones extracontractuales, artículo 1.968 Código Civil) desde que se conoció el daño. Conviene actuar rápido y documentar bien los daños con fotografías y peritaje.</p>

<h3>¿Puede el propietario negar el acceso al fontanero cuando la fuga está en su piso pero es un elemento común?</h3>
<p>No. La Ley de Propiedad Horizontal obliga a los propietarios a permitir el acceso a su vivienda para reparar elementos comunes, aunque pasen por dentro de su piso. La negativa puede impugnarse judicialmente y generar responsabilidad civil por los daños que cause el retraso.</p>

<div class='ct-tpl-cta'><p>¿Tienes una fuga y no sabes de dónde viene? Localizamos el origen sin obra y te decimos quién es el responsable antes de empezar nada.</p><a href='/contacto' class='ct-tpl-cta-btn'>Llamar a CarolTemp ahora</a></div>
HTML,
];

// ═══════════════════════════════════════════════════════════════════════════════
// 7. Quién paga una bajante rota
// ═══════════════════════════════════════════════════════════════════════════════
$articulos[] = [
'titulo'       => 'Quién paga una bajante rota: comunidad, propietario o seguro',
'slug'         => 'quien-paga-bajante-rota-comunidad',
'extracto'     => 'Una bajante rota siempre es responsabilidad de la comunidad de propietarios, ya que son elementos comunes del edificio según la Ley de Propiedad Horizontal. El seguro de la comunidad es quien habitualmente asume el coste de la reparación y los daños colaterales.',
'meta_title'   => 'Quién paga una bajante rota: comunidad o propietario | CarolTemp',
'meta_desc'    => 'Una bajante rota es responsabilidad de la comunidad de propietarios. Guía completa: Ley de Propiedad Horizontal, seguro, daños en pisos y cómo actuar si la comunidad no repara.',
'categoria'    => 'fontaneria',
'zona'         => '',
'sidebar_tipo' => '',
'contenido'    => <<<'HTML'
<p>Las bajantes son siempre responsabilidad de la comunidad de propietarios. No importa si la rotura está dentro de tu piso o en el patio interior: <span class='ct-hl'>la bajante es un elemento común y su reparación corresponde a la comunidad, no al propietario por cuya vivienda pasa.</span> Esto lo establece con claridad la Ley de Propiedad Horizontal y el Código Civil.</p>

<h2>Las bajantes son de todos: lo que la ley dice y muchos desconocen</h2>
<p>Una <strong>bajante</strong> es la tubería vertical que recoge las aguas residuales de los baños y cocinas de cada planta y las conduce al colector general del edificio. El artículo 396 del Código Civil incluye expresamente las "cañerías y conducciones" entre los elementos comunes de un edificio en régimen de propiedad horizontal.</p>
<p>La <a href='https://es.wikipedia.org/wiki/Ley_de_Propiedad_Horizontal_(España)' rel='nofollow noopener' target='_blank'>Ley de Propiedad Horizontal</a> refuerza este principio: los elementos comunes son de titularidad compartida y su mantenimiento y reparación corresponde a la comunidad con cargo al fondo de reserva o mediante derrama extraordinaria.</p>
<p>Esto significa que aunque la bajante rota pase literalmente por dentro de tu piso, <span class='ct-hl'>la comunidad es la responsable de repararla y de costear los daños que esa rotura provoque</span> en el interior de tu vivienda.</p>

<h2>Cuándo paga la comunidad y cuándo puede pagar el propietario</h2>
<p>La regla general es clara: la comunidad paga. Pero hay matices importantes:</p>
<ul class='ct-tpl-lista'>
  <li><strong>Rotura en la bajante principal (elemento común):</strong> paga la comunidad siempre, independientemente de dónde esté físicamente la rotura.</li>
  <li><strong>Rotura en el ramal de conexión entre el sanitario y la bajante:</strong> puede ser discutible. Si el ramal es exclusivo de esa vivienda, puede considerarse privativo y responsabilidad del propietario. Si sirve a varios, es común.</li>
  <li><strong>Daños en el piso derivados de la bajante rota:</strong> si la bajante (elemento común) causa daños en el interior del piso (manchas de humedad, deterioro de paredes o suelos), la comunidad es responsable de esos daños, generalmente a través del seguro comunitario.</li>
  <li><strong>Negligencia del propietario:</strong> si el propietario provocó la rotura (por ejemplo, perforando la bajante durante una obra en su piso), él asume la responsabilidad.</li>
</ul>

<h2>Qué daños cubre el seguro de la comunidad por bajante rota</h2>
<p>La mayoría de pólizas de seguro comunitario incluyen cobertura de daños por agua con estas garantías habituales:</p>
<ul class='ct-tpl-lista'>
  <li>Localización y reparación de la avería en la bajante</li>
  <li>Daños materiales en el interior de los pisos afectados (pintura, solados, mobiliario)</li>
  <li>Gastos de alojamiento temporal si el piso queda inhabitable</li>
  <li>Responsabilidad civil frente a terceros por daños causados</li>
</ul>
<p>El límite de cobertura depende de la póliza contratada. Si los daños son elevados, conviene que un perito independiente valore los daños antes de aceptar la oferta de la aseguradora.</p>

<h2>Qué hacer paso a paso cuando se rompe una bajante</h2>
<p>Actuar rápido reduce los daños. El protocolo correcto:</p>
<ul class='ct-tpl-lista'>
  <li><span class='ct-hl'>Paso 1: Llama a un fontanero urgente</span> para detener la avería. No esperes a la reunión de la comunidad.</li>
  <li>Paso 2: Fotografía y documenta todos los daños visibles antes de limpiar</li>
  <li>Paso 3: Notifica al presidente de la comunidad o al administrador de fincas</li>
  <li>Paso 4: El administrador activa el seguro de la comunidad (parte de daños por agua)</li>
  <li>Paso 5: El perito del seguro visita y valora los daños</li>
  <li>Paso 6: Reparación y indemnización</li>
</ul>
<p>CarolTemp ofrece <span class='ct-hl'>urgencias 24h todos los días</span> para reparaciones de bajantes en Elda, Petrer, Novelda y toda la comarca. Llamar cuanto antes siempre reduce los daños totales.</p>

<h2>Cómo actuar si la comunidad no quiere reparar la bajante rota</h2>
<p>Si el presidente o la comunidad no actúa con urgencia, el propietario afectado tiene varias opciones:</p>
<ul class='ct-tpl-lista'>
  <li>Notificación fehaciente (burofax) exigiendo reparación con plazo</li>
  <li>Reparación por cuenta propia en caso de urgencia extrema, con derecho a reclamar el coste a la comunidad</li>
  <li>Acudir al Juzgado de Primera Instancia para exigir la reparación y daños y perjuicios</li>
  <li>Denuncia ante el ayuntamiento si el estado del edificio supone riesgo para la salud o la seguridad</li>
</ul>
<p>Consulta también la <a href='https://es.wikipedia.org/wiki/Bajante' rel='nofollow noopener' target='_blank'>función y tipos de bajantes en edificios</a> para entender mejor qué parte de la instalación está afectada. Y si necesitas un informe técnico para la reclamación, en CarolTemp lo elaboramos y te asesoramos sobre el proceso. Consulta nuestro servicio de <a href='/fontanero/elda'>fontanería urgente en Elda</a> y <a href='/fontanero/petrer'>Petrer</a>.</p>

<h2>Preguntas frecuentes sobre quién paga una bajante rota</h2>

<h3>¿Las bajantes son siempre elementos comunes aunque pasen por dentro de mi piso?</h3>
<p>Sí. La naturaleza jurídica de la bajante como elemento común no cambia por su ubicación física. Aunque atraviese tu piso, sigue siendo responsabilidad de la comunidad su mantenimiento y reparación.</p>

<h3>¿Puedo exigir que la comunidad repare la bajante con carácter urgente?</h3>
<p>Sí. Las obras de conservación urgentes no requieren aprobación en Junta previa. El presidente puede ordenar la reparación de urgencia y comunicarlo posteriormente a la comunidad. Si se niega, el propietario puede actuar y reclamar el coste.</p>

<h3>¿Qué plazo tiene la comunidad para reparar una bajante rota?</h3>
<p>No existe un plazo legal fijo, pero la jurisprudencia es clara: si la avería causa daños activos (goteras, malos olores, riesgo sanitario), la reparación debe ser inmediata. Cualquier demora injustificada genera responsabilidad por los daños adicionales que cause.</p>

<h3>¿La comunidad puede obligarme a pagar mi parte de una derrama para reparar la bajante?</h3>
<p>Sí. Si el coste de reparación supera el fondo de reserva, la comunidad puede aprobar una derrama extraordinaria. Todos los propietarios están obligados a pagarla según su cuota de participación, independientemente de si votaron a favor o en contra de la obra.</p>

<div class='ct-tpl-cta'><p>¿Tienes una bajante rota o con problemas? Atendemos urgencias 24h y elaboramos el informe técnico que necesitas para reclamar al seguro de la comunidad.</p><a href='/contacto' class='ct-tpl-cta-btn'>Llamar ahora — 611 165 129</a></div>
HTML,
];

// ═══════════════════════════════════════════════════════════════════════════════
// 8. Cuándo hay que cambiar las tuberías de una vivienda
// ═══════════════════════════════════════════════════════════════════════════════
$articulos[] = [
'titulo'       => 'Cuándo hay que cambiar las tuberías de una vivienda: señales y plazos',
'slug'         => 'cuando-cambiar-tuberias-vivienda',
'extracto'     => 'Las tuberías no duran para siempre. Dependiendo del material, tienen una vida útil de entre 20 y 70 años. Te explicamos cuándo hay que cambiarlas, qué señales de alarma debes conocer y cuánto cuesta la renovación completa.',
'meta_title'   => 'Cuándo cambiar las tuberías de una vivienda | CarolTemp',
'meta_desc'    => 'Señales de alarma y vida útil de las tuberías según su material. Cuándo es obligatorio cambiarlas, cuánto cuesta y qué riesgos tiene esperar demasiado. Diagnóstico gratis.',
'categoria'    => 'fontaneria',
'zona'         => '',
'sidebar_tipo' => '',
'contenido'    => <<<'HTML'
<p>Las tuberías de una vivienda tienen una vida útil finita, pero muchos propietarios no saben cuándo han llegado a su límite. <span class='ct-hl'>Esperar demasiado puede resultar en una avería grave, daños por agua en toda la vivienda y una factura varias veces superior a una sustitución planificada.</span> Aquí te explicamos cuándo actuar.</p>

<h2>Las señales que tu instalación lleva tiempo dándote (y tú ignorando)</h2>
<p>Las tuberías no fallan de golpe sin avisar. Antes de la rotura, hay señales que indican que la instalación está al límite:</p>
<ul class='ct-tpl-lista'>
  <li><strong>Agua de color marrón o rojiza al abrir el grifo:</strong> señal de corrosión interna en tuberías de hierro o acero galvanizado</li>
  <li><strong>Presión de agua irregular o débil:</strong> puede indicar incrustaciones de cal o corrosión que estrecha la sección de la tubería</li>
  <li><strong>Manchas de humedad en paredes sin causa aparente:</strong> microfugas que llevan tiempo sin detectarse</li>
  <li><strong>Olores desagradables en el agua o en los desagües:</strong> posible deterioro del interior de las tuberías o acumulación de bacterias</li>
  <li><strong>Ruidos en las tuberías (golpes de ariete, crujidos):</strong> pueden indicar deterioro de las uniones o dilataciones anómalas</li>
  <li><strong>Factura del agua más alta sin cambio de hábitos:</strong> puede haber una microfuga activa</li>
</ul>
<p>Si reconoces dos o más de estas señales en tu vivienda, es el momento de hacer un diagnóstico profesional.</p>

<h2>Vida útil de las tuberías según el material: las cifras que importan</h2>
<p>Cada material tiene un ciclo de vida diferente:</p>
<ul class='ct-tpl-lista'>
  <li><strong>Hierro fundido (tuberías de saneamiento):</strong> <span class='ct-hl'>50–80 años</span>, pero la corrosión puede acelerar mucho su deterioro</li>
  <li><strong>Acero galvanizado (suministro de agua):</strong> <span class='ct-hl'>25–40 años</span>. El galvanizado se agota y empieza la corrosión interior. Muy habitual en edificios de los años 60–80.</li>
  <li><strong>Cobre (suministro):</strong> <span class='ct-hl'>50–70 años</span> en condiciones normales. El agua muy dura (alta cal) puede reducir su vida útil significativamente.</li>
  <li><strong>PVC y PVC-C (saneamiento y suministro frio):</strong> <span class='ct-hl'>40–60 años</span>. Muy resistente si no está expuesto al sol ni a temperaturas extremas.</li>
  <li><strong>Polietileno reticulado PEX (instalaciones modernas):</strong> <span class='ct-hl'>más de 50 años</span> en condiciones normales.</li>
  <li><strong>Fibrocemento/uralita:</strong> usar con precaución desde el primer día. Prohibido desde 2002. Debe retirarse por empresa RERA autorizada.</li>
</ul>

<h2>Cuándo es obligatorio cambiar las tuberías aunque no haya avería</h2>
<p>En algunos casos, la sustitución es obligatoria aunque no haya habido ninguna rotura:</p>
<ul class='ct-tpl-lista'>
  <li>Cuando el Informe de Evaluación del Edificio (IEE) detecta deficiencias graves en la instalación</li>
  <li>Cuando las tuberías son de fibrocemento con amianto y hay obras que las van a afectar</li>
  <li>Cuando la inspección técnica detecta que la instalación no cumple el Código Técnico de la Edificación en vigor</li>
  <li>Cuando hay una venta del inmueble y el comprador exige la renovación como condición</li>
</ul>

<h2>Cuánto cuesta renovar la instalación de tuberías de una vivienda</h2>
<p>Los precios orientativos para 2026 en la comarca de Alicante:</p>
<ul class='ct-tpl-lista'>
  <li>Sustitución de tuberías de suministro en piso de 80 m²: <span class='ct-hl'>2.500–5.500 €</span></li>
  <li>Renovación completa (suministro + saneamiento) en piso de 90 m²: 4.500–9.000 €</li>
  <li>Cambio de tuberías de acero galvanizado por cobre o PEX en vivienda unifamiliar: 6.000–15.000 €</li>
  <li>Solo cambio de bajantes de hierro fundido en edificio de 4 plantas: 2.000–5.000 €</li>
</ul>
<p>El momento óptimo para actuar es cuando las señales de alarma aparecen pero antes de que se produzca la avería. <span class='ct-hl'>Una renovación planificada cuesta entre 2 y 4 veces menos que una reparación de emergencia con daños colaterales.</span></p>

<h2>Qué pasa si no cambias las tuberías a tiempo</h2>
<p>El riesgo de esperar más de lo debido incluye:</p>
<ul class='ct-tpl-lista'>
  <li>Rotura súbita con inundación parcial o total de la vivienda</li>
  <li>Daños en el piso de abajo y posible reclamación judicial del vecino</li>
  <li>Contaminación del agua de consumo por corrosión interior</li>
  <li>Pérdida de presión que inutiliza electrodomésticos (lavavajillas, lavadora)</li>
  <li>Costes de rehabilitación muy superiores a una sustitución preventiva</li>
</ul>
<p>La <a href='https://es.wikipedia.org/wiki/Tubería' rel='nofollow noopener' target='_blank'>tubería es la infraestructura invisible de la vivienda</a>: cuando falla, todo lo demás se para. En CarolTemp hacemos diagnósticos de instalación en <a href='/fontanero/elda'>Elda</a>, <a href='/fontanero/petrer'>Petrer</a>, <a href='/fontanero/novelda'>Novelda</a> y toda la comarca con <span class='ct-hl'>presupuesto gratis sin compromiso</span>.</p>

<h2>Preguntas frecuentes sobre cuándo cambiar las tuberías</h2>

<h3>¿Cuánto duran las tuberías de cobre?</h3>
<p>En condiciones normales, entre 50 y 70 años. Sin embargo, el agua muy calcárea (habitual en Alicante) puede reducir su vida útil a 30-40 años por incrustaciones. El agua ácida también acelera su corrosión exterior.</p>

<h3>¿Las tuberías de PVC duran para siempre?</h3>
<p>No, aunque son muy duraderas. El PVC expuesto al sol se degrada en 15-20 años. En instalaciones interiores protegidas puede durar 50-60 años sin problemas. El problema habitual es la rigidez de las uniones con el tiempo.</p>

<h3>¿Hay que cambiar todas las tuberías a la vez o se puede hacer por tramos?</h3>
<p>Se puede hacer por tramos, pero en viviendas con instalaciones de más de 30-40 años, lo más económico a largo plazo es hacer la renovación completa de una vez. Cambiar tramos parciales con una instalación envejecida suele generar nuevas averías en otras zonas en poco tiempo.</p>

<h3>¿Un fontanero puede decirme si necesito cambiar las tuberías sin hacer obras?</h3>
<p>Sí. Con una inspección visual, medición de presión, análisis de la instalación y en algunos casos una cámara de inspección, es posible determinar el estado de la instalación sin abrir paredes. En CarolTemp ofrecemos este diagnóstico previo gratuitamente.</p>

<div class='ct-tpl-cta'><p>¿Tienes una instalación antigua y quieres saber si es hora de cambiarla? Te hacemos el diagnóstico completo sin coste y sin compromiso.</p><a href='/contacto' class='ct-tpl-cta-btn'>Pedir diagnóstico gratuito</a></div>
HTML,
];

// ═══════════════════════════════════════════════════════════════════════════════
// 9. Ayudas para rehabilitación de edificios con problemas de fontanería
// ═══════════════════════════════════════════════════════════════════════════════
$articulos[] = [
'titulo'       => 'Ayudas para rehabilitación de edificios con problemas de fontanería en 2026',
'slug'         => 'ayudas-rehabilitacion-edificios-fontaneria',
'extracto'     => 'Los edificios con más de 20 años que presenten deficiencias en sus instalaciones de fontanería pueden acceder a subvenciones de hasta el 80% del coste de rehabilitación a través de los fondos Next Generation EU. Te explicamos cómo funciona y qué gestiones tiene que hacer la comunidad.',
'meta_title'   => 'Ayudas rehabilitación edificios fontanería 2026 | CarolTemp',
'meta_desc'    => 'Subvenciones de hasta el 80% para rehabilitar edificios con problemas de fontanería en 2026. Fondos NextGenEU, IEE, gestión comunitaria y plazos. Presupuesto gratis.',
'categoria'    => 'fontaneria',
'zona'         => '',
'sidebar_tipo' => '',
'contenido'    => <<<'HTML'
<p>Si vives en un edificio con instalaciones de fontanería deterioradas, hay buenas noticias: los fondos europeos Next Generation EU han puesto en marcha el programa de rehabilitación residencial más ambicioso de la historia en España, y <span class='ct-hl'>las instalaciones de agua y saneamiento son una de las actuaciones subvencionables</span> cuando se integran en un proyecto de rehabilitación integral del edificio.</p>

<h2>Por qué ahora es el mejor momento para rehabilitar las instalaciones de un edificio</h2>
<p>El <strong>Plan de Recuperación, Transformación y Resiliencia</strong> (financiado con fondos NextGenerationEU) tiene una dotación de más de 6.800 millones de euros para rehabilitación residencial en España, distribuidos hasta 2026. Nunca antes habían existido subvenciones de estas dimensiones para renovar los edificios residenciales.</p>
<p>La ventana temporal es estrecha: las convocatorias están activas y el dinero se agota por orden de solicitud. <span class='ct-hl'>Las comunidades que actúen antes tienen más probabilidades de obtener la máxima subvención.</span> Las que esperen podrían quedarse sin fondos o con porcentajes menores.</p>

<h2>El programa estrella: cómo funciona la ayuda a la rehabilitación residencial</h2>
<p>El Real Decreto 853/2021 regula el programa de rehabilitación residencial, que tiene varias líneas de actuación. Para edificios con problemas de fontanería, las más relevantes son:</p>
<ul class='ct-tpl-lista'>
  <li><strong>Programa de mejora de la eficiencia energética y sostenibilidad:</strong> cubre instalaciones de agua caliente sanitaria, sistemas de climatización y renovación de tuberías cuando forma parte de la envolvente energética del edificio. <span class='ct-hl'>Subvención de hasta el 40% del presupuesto protegido.</span></li>
  <li><strong>Programa de mejora de la seguridad, salubridad y accesibilidad:</strong> orientado específicamente a edificios con deficiencias graves detectadas en el IEE. Aquí entran instalaciones deterioradas de agua y saneamiento. Subvención de hasta el <span class='ct-hl'>80% del coste para edificios con deficiencias graves.</span></li>
  <li><strong>Programa de barrios de actuación preferente:</strong> en zonas delimitadas por los ayuntamientos, puede complementar las anteriores hasta el 100% del coste en casos extremos.</li>
</ul>

<h2>Qué instalaciones de fontanería cubre la subvención de rehabilitación</h2>
<p>Dentro de un proyecto de rehabilitación de edificio, pueden subvencionarse las siguientes actuaciones de fontanería:</p>
<ul class='ct-tpl-lista'>
  <li>Sustitución de bajantes de hierro fundido, fibrocemento (uralita) o material deteriorado</li>
  <li>Renovación de colectores y arquetas de saneamiento del edificio</li>
  <li>Sustitución de tuberías de suministro de agua fría y caliente hasta los contadores</li>
  <li>Instalación de sistema centralizado de producción de ACS (agua caliente sanitaria) de alta eficiencia</li>
  <li>Implantación de sistema de captación solar térmica comunitaria</li>
  <li>Mejora del aislamiento de tuberías y conductos</li>
</ul>
<p>La condición es que estas actuaciones formen parte de un proyecto integral que incluya también mejoras en la envolvente del edificio o la eficiencia energética global. Las obras de fontanería de forma aislada, sin ese marco rehabilitador, no acceden a las cuantías máximas.</p>

<h2>Cuánto se puede recibir: ejemplos reales de subvención</h2>
<p>Para un edificio de 8 viviendas con una rehabilitación integral que incluya renovación completa del saneamiento y las instalaciones de ACS:</p>
<ul class='ct-tpl-lista'>
  <li>Presupuesto total de rehabilitación: 120.000 €</li>
  <li>Con subvención del 40%: <span class='ct-hl'>48.000 € de subvención directa</span> (6.000 € por piso)</li>
  <li>Con subvención del 80% (edificio con deficiencias graves): <span class='ct-hl'>96.000 € de subvención directa</span> (12.000 € por piso)</li>
</ul>
<p>El coste restante puede financiarse con líneas ICO o créditos bancarios específicos para comunidades de propietarios, con cuotas mensuales razonables por propietario.</p>

<h2>Cómo solicitar las ayudas: la hoja de ruta para la comunidad</h2>
<p>El proceso para una comunidad de propietarios:</p>
<ul class='ct-tpl-lista'>
  <li>Encargar el <strong>Informe de Evaluación del Edificio (IEE)</strong> — documento clave que certifica las deficiencias</li>
  <li>Aprobar en Junta la intención de solicitar la subvención y autorizar al presidente</li>
  <li>Contratar a un agente rehabilitador o gestor técnico (obligatorio en muchos programas)</li>
  <li>Presentar solicitud en la Oficina de Rehabilitación de la Generalitat Valenciana antes de iniciar obras</li>
  <li>Obtener resolución favorable y firmar el convenio de subvención</li>
  <li>Ejecutar las obras con empresa instaladora autorizada</li>
  <li>Presentar justificación técnica y económica para cobrar la subvención</li>
</ul>
<p>En CarolTemp elaboramos los informes técnicos del estado de las instalaciones de fontanería que los IEE y los agentes rehabilitadores necesitan. Consulta nuestros servicios de <a href='/fontanero/novelda'>fontanería en Novelda</a>, <a href='/fontanero/monovar'>Monóvar</a> y toda la comarca. La <a href='https://es.wikipedia.org/wiki/Rehabilitación_arquitectónica' rel='nofollow noopener' target='_blank'>rehabilitación de edificios</a> es cada vez más necesaria para el parque inmobiliario español envejecido.</p>

<h2>Preguntas frecuentes sobre ayudas de rehabilitación para fontanería</h2>

<h3>¿Qué antigüedad mínima tiene que tener el edificio para acceder a la subvención?</h3>
<p>El programa general exige que el edificio esté construido antes de 2007 y que al menos el 50% de su superficie sobre rasante esté destinada a uso residencial. Para los programas de deficiencias graves, la antigüedad mínima suele ser 20 años.</p>

<h3>¿La comunidad necesita el IEE aprobado antes de solicitar?</h3>
<p>Sí, en la mayoría de los programas el IEE es un requisito previo a la solicitud. Sin él no se puede acreditar las deficiencias que justifican la subvención. El coste del IEE es de entre 500 y 2.000 € según el tamaño del edificio y también puede subvencionarse.</p>

<h3>¿Cuánto tarda en concederse la subvención de rehabilitación?</h3>
<p>La resolución de la solicitud tarda entre 4 y 9 meses. Muchos programas permiten iniciar las obras antes de la resolución bajo responsabilidad del solicitante, aunque con el riesgo de que no se conceda. Lo más prudente es esperar la resolución favorable.</p>

<h3>¿Hay que devolver la subvención si se vende el piso?</h3>
<p>Sí, en algunos programas existe un plazo de afección de la subvención (generalmente 10 años) durante el cual la vivienda no puede venderse sin autorización administrativa, o debe devolverse parte proporcional de la ayuda si se vende.</p>

<div class='ct-tpl-cta'><p>¿Tu edificio tiene más de 20 años y las bajantes o las tuberías dan problemas? Te elaboramos el informe técnico de las instalaciones para iniciar la solicitud de subvención.</p><a href='/contacto' class='ct-tpl-cta-btn'>Solicitar informe técnico</a></div>
HTML,
];

// ═══════════════════════════════════════════════════════════════════════════════
// 10. Ayudas para renovar instalaciones de agua y saneamiento
// ═══════════════════════════════════════════════════════════════════════════════
$articulos[] = [
'titulo'       => 'Ayudas para renovar instalaciones de agua y saneamiento en 2026',
'slug'         => 'ayudas-renovar-instalaciones-agua-saneamiento',
'extracto'     => 'Renovar las instalaciones de agua y saneamiento puede tener apoyo de varios programas de ayuda en 2026: fondos de rehabilitación residencial, programas de la Generalitat Valenciana y deducciones fiscales. Te explicamos qué cubren, a quién aplica y cómo solicitarlos.',
'meta_title'   => 'Ayudas renovar instalaciones agua y saneamiento 2026 | CarolTemp',
'meta_desc'    => 'Guía 2026 de ayudas para renovar instalaciones de agua potable y saneamiento. Programas de rehabilitación, Generalitat Valenciana, descalcificadores, ósmosis y precios.',
'categoria'    => 'fontaneria',
'zona'         => '',
'sidebar_tipo' => '',
'contenido'    => <<<'HTML'
<p>Renovar las instalaciones de agua y saneamiento de una vivienda o edificio no tiene que costar una fortuna. En 2026 existen varios programas de ayuda, tanto para la red de distribución de agua potable como para las instalaciones de saneamiento y evacuación. <span class='ct-hl'>La clave es saber qué programa aplica a tu situación concreta y presentar la solicitud antes de empezar la obra.</span></p>

<h2>Las instalaciones de agua tienen programas propios que muy pocos conocen</h2>
<p>Cuando se habla de subvenciones para rehabilitación, la mayoría piensa en fachadas o ventanas. Pero las instalaciones de suministro de agua y saneamiento tienen su propio marco de ayudas, y en muchos casos son las primeras en necesitar renovación en edificios envejecidos.</p>
<p>En la comarca de Alicante, la dureza del agua (agua muy calcárea) acelera el deterioro de las tuberías de cobre y acero galvanizado, lo que hace que edificios de 30-40 años ya tengan instalaciones que necesitan renovarse. <span class='ct-hl'>Una instalación nueva con materiales actuales dura el doble y consume entre un 15 y un 30% menos de agua.</span></p>

<h2>Programas de ayuda para renovar instalaciones de agua en 2026</h2>
<ul class='ct-tpl-lista'>
  <li><strong>Plan de Rehabilitación Residencial (RD 853/2021 — fondos NextGenEU):</strong> cubre la renovación de instalaciones de suministro de agua y saneamiento cuando forma parte de un proyecto de rehabilitación integral del edificio. Subvención de <span class='ct-hl'>hasta el 40-80% del coste</span> según el grado de deficiencias.</li>
  <li><strong>Programa de mejora de eficiencia en el consumo de agua (MITECO):</strong> el Ministerio para la Transición Ecológica tiene líneas de ayuda para instalaciones que reduzcan el consumo de agua: grifería eficiente, sistemas de recuperación de aguas grises, contadores inteligentes. Consulta el portal del <a href='https://www.miteco.gob.es' rel='nofollow noopener' target='_blank'>Ministerio para la Transición Ecológica y el Reto Demográfico</a>.</li>
  <li><strong>Ayudas de la Generalitat Valenciana — IVACE Agua:</strong> convocatorias para instalaciones que mejoren la eficiencia hídrica en edificios residenciales. Incluyen descalcificadores, sistemas de filtración y renovación de redes interiores.</li>
  <li><strong>Deducciones IRPF:</strong> si la renovación va asociada a una mejora energética certificada (ACS solar, aerotermia), el coste de las tuberías y la instalación puede incluirse en la base de deducción.</li>
</ul>

<h2>Qué instalaciones cubren las ayudas y cuáles quedan fuera</h2>
<p><strong>Instalaciones que sí pueden recibir ayuda:</strong></p>
<ul class='ct-tpl-lista'>
  <li>Red de distribución de agua fría y caliente hasta los contadores individuales (elemento común)</li>
  <li>Bajantes y colectores de saneamiento (elementos comunes)</li>
  <li>Sistema centralizado de producción de ACS eficiente (solar, bomba de calor)</li>
  <li>Contadores individuales inteligentes (smart metering)</li>
  <li>Grifería de bajo consumo en zonas comunes</li>
  <li>Sistemas de recuperación de aguas pluviales para riego o WC</li>
</ul>
<p><strong>Instalaciones que generalmente quedan fuera de las ayudas directas:</strong></p>
<ul class='ct-tpl-lista'>
  <li>Tuberías interiores de cada piso privativo (sin marco de rehabilitación integral)</li>
  <li>Descalcificadores individuales en pisos (aunque pueden tener deducción IRPF si mejoran eficiencia)</li>
  <li>Reparaciones puntuales o de emergencia (no son obras de mejora)</li>
</ul>

<h2>¿Hay ayuda para instalar un descalcificador o un sistema de ósmosis?</h2>
<p>Esta es una de las preguntas más frecuentes en la comarca de Alicante, donde el agua de red es especialmente dura. La respuesta es matizada:</p>
<ul class='ct-tpl-lista'>
  <li><strong>Descalcificadores comunitarios:</strong> si se instala un descalcificador en la entrada general del edificio y reduce el consumo de agua y energía de toda la comunidad, puede encajar en las ayudas del IVACE para eficiencia hídrica.</li>
  <li><strong>Ósmosis inversa individual:</strong> no tiene subvención directa propia, pero el coste puede incluirse en la deducción IRPF si se considera una mejora de habitabilidad dentro de una reforma más amplia.</li>
  <li><strong>Deducción por calidad del agua:</strong> no existe como categoría propia, pero muchos asesores fiscales la incluyen dentro de "mejora de habitabilidad" cuando va en un contexto de reforma integral.</li>
</ul>
<p>En CarolTemp instalamos <a href='/servicios#osmosis'>sistemas de ósmosis inversa</a> y <a href='/servicios#descalcificadores'>descalcificadores</a> con garantía fabricante. El <a href='https://es.wikipedia.org/wiki/Saneamiento' rel='nofollow noopener' target='_blank'>saneamiento de agua</a> adecuado prolonga la vida de todas las instalaciones de la vivienda. Consulta nuestros servicios en <a href='/fontanero/elda'>Elda</a>, <a href='/fontanero/petrer'>Petrer</a> y comarca.</p>

<h2>Cuánto cuesta una renovación completa de instalaciones de agua y saneamiento</h2>
<p>Estimaciones para 2026 en la comarca de Alicante:</p>
<ul class='ct-tpl-lista'>
  <li>Renovación de red de suministro en edificio de 6 viviendas: <span class='ct-hl'>12.000–25.000 €</span></li>
  <li>Sustitución de bajantes y colectores en edificio de 4 plantas: 8.000–18.000 €</li>
  <li>Renovación completa de agua + saneamiento en edificio de 8 viviendas: 30.000–60.000 €</li>
  <li>Descalcificador comunitario para edificio de 6 pisos: 2.500–5.000 € instalado</li>
  <li>Sistema ACS solar comunitario (6 viviendas): 8.000–15.000 €</li>
</ul>
<p>Con una subvención del 40%, una comunidad de 6 vecinos ahorraría entre 5.000 y 24.000 € dependiendo del alcance de la renovación. <span class='ct-hl'>Presupuesto gratis sin compromiso</span> para empezar a calcular el coste real de tu proyecto.</p>

<h2>Preguntas frecuentes sobre ayudas para instalaciones de agua y saneamiento</h2>

<h3>¿Las comunidades pueden renovar el saneamiento sin renovar también el suministro?</h3>
<p>Sí, técnicamente es posible renovar solo el saneamiento. Sin embargo, para acceder a las máximas cuantías de subvención suele necesitarse un proyecto de rehabilitación integral. Renovaciones parciales solo de saneamiento pueden acceder a subvenciones menores o programas locales.</p>

<h3>¿Hay ayuda para instalar un sistema de ósmosis inversa centralizado en el edificio?</h3>
<p>No de forma directa, pero si se integra en un proyecto de mejora de la calidad del agua y eficiencia hídrica, puede incluirse en algunas convocatorias del IVACE. Conviene consultar con el organismo gestor antes de invertir.</p>

<h3>¿Se puede renovar solo parte de la instalación (solo agua fría, por ejemplo)?</h3>
<p>Sí, las renovaciones parciales son posibles. Sin embargo, en instalaciones antiguas suele ser más eficiente renovar el conjunto, ya que la parte antigua que queda puede dar problemas poco después. El fontanero debe evaluar el estado global antes de recomendar una renovación parcial.</p>

<h3>¿La renovación de la red de agua reduce la factura del agua?</h3>
<p>Sí, especialmente si había microfugas o pérdidas por corrosión. Además, tuberías más anchas (sin incrustaciones) mejoran la presión y reducen el consumo de agua caliente por pérdidas térmicas. En algunos casos el ahorro puede amortizar la inversión en 8-12 años.</p>

<div class='ct-tpl-cta'><p>¿Quieres renovar las instalaciones de agua de tu vivienda o edificio y aprovechar las ayudas disponibles? Te asesoramos sin compromiso sobre el programa que mejor se adapta a tu caso.</p><a href='/contacto' class='ct-tpl-cta-btn'>Solicitar asesoramiento gratuito</a></div>
HTML,
];

// ═══════════════════════════════════════════════════════════════════════════════
// INSERCIÓN EN BASE DE DATOS
// ═══════════════════════════════════════════════════════════════════════════════

$insertados = 0;
$saltados   = 0;
$errores    = [];

$stmt = $pdo->prepare('
  INSERT INTO articulos (titulo, slug, extracto, contenido, imagen, zona, categoria, meta_title, meta_desc, publicado, sidebar_tipo, fecha)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?, NOW())
');

foreach ($articulos as $art) {
  // Verificar si el slug ya existe
  $check = $pdo->prepare('SELECT id FROM articulos WHERE slug = ? LIMIT 1');
  $check->execute([$art['slug']]);
  if ($check->fetch()) {
    $saltados++;
    $errores[] = '⚠️ Slug ya existente (saltado): <strong>' . htmlspecialchars($art['slug']) . '</strong>';
    continue;
  }

  try {
    $stmt->execute([
      $art['titulo'],
      $art['slug'],
      $art['extracto'],
      $art['contenido'],
      '', // sin imagen principal por ahora
      $art['zona'],
      $art['categoria'],
      $art['meta_title'],
      $art['meta_desc'],
      $art['sidebar_tipo'],
    ]);
    $insertados++;
  } catch (PDOException $e) {
    $errores[] = '❌ Error en <strong>' . htmlspecialchars($art['slug']) . '</strong>: ' . htmlspecialchars($e->getMessage());
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Importación de artículos — CarolTemp</title>
<style>
  body { font-family: system-ui, sans-serif; max-width: 780px; margin: 3rem auto; padding: 0 1.5rem; background:#f8fafc; color:#1e293b; }
  h1 { font-size: 1.5rem; font-weight: 800; color: #0d1f33; border-bottom: 2px solid #e2e8f0; padding-bottom: .75rem; }
  .ok  { background: #dcfce7; border: 1px solid #86efac; border-radius: 10px; padding: 1rem 1.5rem; margin: 1rem 0; }
  .warn { background: #fef9c3; border: 1px solid #fde047; border-radius: 10px; padding: 1rem 1.5rem; margin: 1rem 0; }
  .info { background: #eff6ff; border: 1px solid #93c5fd; border-radius: 10px; padding: 1rem 1.5rem; margin: 1rem 0; }
  a.btn { display: inline-block; background: #1e3a5f; color: #fff; padding: .65rem 1.4rem; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 700; margin-top: 1rem; margin-right: .5rem; }
  a.btn-g { background: #16a34a; }
  ul { padding-left: 1.4rem; }
  li { margin: .35rem 0; }
</style>
</head>
<body>
<h1>Importación de artículos informativos — CarolTemp</h1>

<?php if ($insertados > 0): ?>
<div class="ok">
  <strong>✅ <?php echo $insertados; ?> artículo(s) creados como borrador.</strong>
  Publícalos uno a uno desde el panel de administración tras revisarlos.
</div>
<?php endif; ?>

<?php if ($saltados > 0): ?>
<div class="warn">
  <strong>⚠️ <?php echo $saltados; ?> artículo(s) saltados</strong> por tener el slug ya existente en la base de datos.
</div>
<?php endif; ?>

<?php if (!empty($errores)): ?>
<div class="warn">
  <strong>Detalles:</strong>
  <ul><?php foreach ($errores as $e) echo '<li>' . $e . '</li>'; ?></ul>
</div>
<?php endif; ?>

<div class="info">
  <strong>Siguientes pasos:</strong>
  <ol>
    <li>Ve a <a href='nuevo-articulo.php'>nuevo-articulo.php</a> y verás los borradores en el listado</li>
    <li>Añade una imagen de portada a cada artículo (opcional pero recomendado para SEO)</li>
    <li>Revisa el contenido y ajusta lo que necesites</li>
    <li>Publica cada artículo cuando esté listo</li>
  </ol>
</div>

<a href="nuevo-articulo.php" class="btn">Ver artículos en el admin →</a>
<a href="agente-seo.php" class="btn btn-g">Crear nuevo artículo con el agente →</a>
</body>
</html>
