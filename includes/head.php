<?php
$is_local = in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1']);



require_once __DIR__ . '/db.php';

$url_actual = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($url_actual !== '/') {
    $url_actual = rtrim($url_actual, '/');
}

$stmt = $pdo->prepare("
    SELECT destino, tipo
    FROM redirecciones
    WHERE origen = ?
    LIMIT 1
");

$stmt->execute([$url_actual]);

$redireccion = $stmt->fetch(PDO::FETCH_ASSOC);

if ($redireccion) {

    header(
        'Location: ' . $redireccion['destino'],
        true,
        (int)$redireccion['tipo']
    );

    exit;
}

if ($is_local) {
  $base_url = 'http://localhost/';
} else {
  $base_url = 'https://' . $_SERVER['HTTP_HOST'] . '/';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- SEO básico -->
  <title><?php echo htmlspecialchars($meta_title); ?></title>
  <meta name="description" content="<?php echo htmlspecialchars($meta_desc); ?>">
  <link rel="canonical" href="<?php echo htmlspecialchars($meta_url); ?>">

  <!-- Open Graph -->
  <meta property="og:type"        content="website">
  <meta property="og:title"       content="<?php echo htmlspecialchars($meta_title); ?>">
  <meta property="og:description" content="<?php echo htmlspecialchars($meta_desc); ?>">
  <meta property="og:url"         content="<?php echo htmlspecialchars($meta_url); ?>">
  <meta property="og:image"       content="https://hidrofont.es/img/logo/logo.webp">
  <meta property="og:locale"      content="es_ES">
  <meta property="og:site_name"   content="Hidrofont">

  <!-- Twitter Card -->
  <meta name="twitter:card"        content="summary_large_image">
  <meta name="twitter:title"       content="<?php echo htmlspecialchars($meta_title); ?>">
  <meta name="twitter:description" content="<?php echo htmlspecialchars($meta_desc); ?>">

  <!-- Geo — SEO local -->
  <meta name="geo.region"       content="ES-VC">
  <meta name="geo.placename"    content="Vinalopó Medio, Alicante">
  <meta name="geo.position"     content="38.4778;-0.7957">
  <meta name="ICBM"             content="38.4778, -0.7957">

  <!-- Robots -->
  <meta name="robots" content="index, follow">
  <?php
$noindex_pages = [
  'aviso-legal',
  'privacidad',
  'cookies',
  'contacto',
  'sobre-nosotros',
];

$current_page = basename($_SERVER['SCRIPT_NAME'], '.php');
if (in_array($current_page, $noindex_pages)):
?>
<meta name="robots" content="noindex, follow">
<?php endif; ?>

  <!-- CSS Global siempre -->
  <link rel="stylesheet" href="<?php echo $base_url; ?>css/global.css">
  <link rel="stylesheet" href="<?php echo $base_url; ?>css/nav.css">
  <link rel="stylesheet" href="<?php echo $base_url; ?>css/footer.css">

  <!-- CSS específico de página -->
  <?php if (!empty($page_css)): ?>
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/pages/<?php echo $page_css; ?>.css">
  <?php endif; ?>

  <!-- Fuente Inter -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Favicon -->
<link rel="icon" type="image/png" href="<?php echo $base_url; ?>favicon-96x96.png" sizes="96x96">
<link rel="icon" type="image/svg+xml" href="<?php echo $base_url; ?>favicon.svg">
<link rel="shortcut icon" href="<?php echo $base_url; ?>favicon.ico">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo $base_url; ?>apple-touch-icon.png">
<meta name="apple-mobile-web-app-title" content="Hidrofont">
<link rel="manifest" href="<?php echo $base_url; ?>site.webmanifest">

  <!-- Schema Markup -->
  <script type="application/ld+json">
<?php
// Coordenadas por zona
$zonas_geo = [
  'Elda'            => ['lat' => 38.4778, 'lng' => -0.7957, 'cp' => '03600'],
  'Petrer'          => ['lat' => 38.4769, 'lng' => -0.7739, 'cp' => '03610'],
  'Novelda'         => ['lat' => 38.3849, 'lng' => -0.7667, 'cp' => '03660'],
  'Monóvar'         => ['lat' => 38.4369, 'lng' => -0.8339, 'cp' => '03640'],
  'Sax'             => ['lat' => 38.5369, 'lng' => -0.8139, 'cp' => '03630'],
  'Pinoso'          => ['lat' => 38.4039, 'lng' => -1.0339, 'cp' => '03650'],
  'Monforte del Cid'=> ['lat' => 38.3769, 'lng' => -0.6739, 'cp' => '03670'],
  'Salinas'         => ['lat' => 38.5039, 'lng' => -0.8539, 'cp' => '03638'],
];

$horario = [
  [
    "@type"     => "OpeningHoursSpecification",
    "dayOfWeek" => ["Monday","Tuesday","Wednesday","Thursday","Friday"],
    "opens"     => "08:00",
    "closes"    => "20:00"
  ],
  [
    "@type"     => "OpeningHoursSpecification",
    "dayOfWeek" => ["Saturday"],
    "opens"     => "09:00",
    "closes"    => "14:00"
  ]
];

$servicios_offered = [
  ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Reparaciones de fontanería urgentes"]],
  ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Detección de fugas de agua con geófono y cámara"]],
  ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Instalación de termos Nubeco"]],
  ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Instalación de ósmosis inversa"]],
  ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Instalación de descalcificadores"]],
  ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Reformas de baño"]],
  ["@type" => "Offer", "itemOffered" => ["@type" => "Service", "name" => "Instalación de bombas de achique"]],
];

switch ($schema_type ?? 'default') {

  case 'zona':
    $zn  = $zona_nombre ?? 'Elda';
    $geo = $zonas_geo[$zn] ?? ['lat' => 38.4778, 'lng' => -0.7957, 'cp' => '03600'];
    $schema = [
      "@context"    => "https://schema.org",
      "@type"       => ["LocalBusiness", "Plumber"],
      "name"        => "Hidrofont — Fontanero en " . $zn,
      "description" => "Fontanería residencial e industrial en " . $zn . ". Reparaciones urgentes, detección de fugas con geófono y cámara, instalaciones y reformas.",
      "url"         => $meta_url,
      "logo"        => "https://hidrofont.es/img/logo/logo.webp",
      "image"       => "https://hidrofont.es/img/logo/logo.webp",
      "telephone"   => "+34613429032",
      "priceRange"  => "€€",
      "currenciesAccepted" => "EUR",
      "paymentAccepted"    => "Cash, Credit Card, Financing",
      "address"     => [
        "@type"           => "PostalAddress",
        "streetAddress"   => $zn,
        "addressLocality" => $zn,
        "postalCode"      => $geo['cp'],
        "addressRegion"   => "Alicante",
        "addressCountry"  => "ES"
      ],
      "geo" => [
        "@type"     => "GeoCoordinates",
        "latitude"  => $geo['lat'],
        "longitude" => $geo['lng']
      ],
      "areaServed" => [
        [
          "@type" => "City",
          "name"  => $zn,
          "containedInPlace" => [
            "@type" => "AdministrativeArea",
            "name"  => "Vinalopó Medio, Alicante"
          ]
        ]
      ],
      "hasOfferCatalog" => [
        "@type"       => "OfferCatalog",
        "name"        => "Servicios de fontanería en " . $zn,
        "itemListElement" => $servicios_offered
      ],
      "openingHoursSpecification" => $horario,
      "sameAs" => []
    ];
    break;

  case 'servicio':
    $schema = [
      "@context"    => "https://schema.org",
      "@type"       => "Service",
      "name"        => $meta_title  ?? "Servicios de fontanería — Hidrofont",
      "description" => $meta_desc   ?? "Fontanería en el Vinalopó",
      "provider"    => [
        "@type"     => "LocalBusiness",
        "name"      => "Hidrofont",
        "url"       => "https://hidrofont.es",
        "telephone" => "+34613429032",
        "areaServed"=> "Vinalopó Medio, Alicante"
      ],
      "areaServed"  => "Vinalopó Medio, Alicante",
      "hasOfferCatalog" => [
        "@type"           => "OfferCatalog",
        "name"            => "Servicios de fontanería",
        "itemListElement" => $servicios_offered
      ]
    ];
    break;

  case 'contacto':
    $schema = [
      "@context" => "https://schema.org",
      "@type"    => "ContactPage",
      "name"     => "Contacto — Hidrofont",
      "url"      => $meta_url ?? "https://hidrofont.es/contacto.php",
      "mainEntity" => [
        "@type"     => ["LocalBusiness", "Plumber"],
        "name"      => "Hidrofont",
        "telephone" => "+34613429032",
        "address"   => [
          "@type"           => "PostalAddress",
          "addressLocality" => "Monóvar",
          "addressRegion"   => "Alicante",
          "addressCountry"  => "ES"
        ]
      ]
    ];
    break;

  case 'home':
    $schema = [
      "@context"    => "https://schema.org",
      "@type"       => ["LocalBusiness", "Plumber"],
      "name"        => "Hidrofont",
      "description" => "Fontanería industrial y residencial en el Vinalopó. Elda, Petrer, Novelda, Monóvar, Sax y comarca.",
      "url"         => "https://hidrofont.es",
      "logo"        => "https://hidrofont.es/img/logo/logo.webp",
      "image"       => "https://hidrofont.es/img/logo/logo.webp",
      "telephone"   => "+34613429032",
      "priceRange"  => "€€",
      "currenciesAccepted" => "EUR",
      "paymentAccepted"    => "Cash, Credit Card, Financing",
      "address"     => [
        "@type"           => "PostalAddress",
        "addressLocality" => "Monóvar",
        "postalCode"      => "03640",
        "addressRegion"   => "Alicante",
        "addressCountry"  => "ES"
      ],
      "geo" => [
        "@type"     => "GeoCoordinates",
        "latitude"  => 38.4369,
        "longitude" => -0.8339
      ],
      "areaServed" => [
        ["@type" => "City", "name" => "Elda"],
        ["@type" => "City", "name" => "Petrer"],
        ["@type" => "City", "name" => "Novelda"],
        ["@type" => "City", "name" => "Monóvar"],
        ["@type" => "City", "name" => "Sax"],
        ["@type" => "City", "name" => "Pinoso"],
        ["@type" => "City", "name" => "Monforte del Cid"],
        ["@type" => "City", "name" => "Salinas"]
      ],
      "hasOfferCatalog" => [
        "@type"           => "OfferCatalog",
        "name"            => "Servicios de fontanería en el Vinalopó",
        "itemListElement" => $servicios_offered
      ],
      "openingHoursSpecification" => $horario,
      "sameAs" => []
    ];
    break;

    case 'articulo':
    $schema = [
      "@context"        => "https://schema.org",
      "@type"           => "Article",
      "headline"        => $meta_title ?? '',
      "description"     => $meta_desc  ?? '',
      "url"             => $meta_url   ?? '',
      "image"           => !empty($art['imagen']) ? 'https://hidrofont.es' . $art['imagen'] : 'https://hidrofont.es/img/logo/logo.webp',
      "datePublished"   => !empty($art['fecha']) ? date('Y-m-d', strtotime($art['fecha'])) : '',
      "dateModified"    => !empty($art['fecha']) ? date('Y-m-d', strtotime($art['fecha'])) : '',
      "author"          => [
        "@type" => "Organization",
        "name"  => "Hidrofont",
        "url"   => "https://hidrofont.es"
      ],
      "publisher"       => [
        "@type" => "Organization",
        "name"  => "Hidrofont",
        "logo"  => [
          "@type" => "ImageObject",
          "url"   => "https://hidrofont.es/img/logo/logo.webp"
        ]
      ],
      "mainEntityOfPage" => [
        "@type" => "WebPage",
        "@id"   => $meta_url ?? ''
      ],
      "about" => [
        "@type" => "LocalBusiness",
        "name"  => "Hidrofont",
        "url"   => "https://hidrofont.es"
      ],
      "mentions" => !empty($art['zona']) ? [
        "@type" => "City",
        "name"  => $art['zona']
      ] : null
    ];
    // Eliminar nulls
    $schema = array_filter($schema, fn($v) => $v !== null);
    break;

  case 'proyecto':
    $schema = [
      "@context"    => "https://schema.org",
      "@type"       => "Service",
      "name"        => $meta_title ?? '',
      "description" => $meta_desc  ?? '',
      "url"         => $meta_url   ?? '',
      "image"       => !empty($pro['imagen']) ? 'https://hidrofont.es' . $pro['imagen'] : 'https://hidrofont.es/img/logo/logo.webp',
      "provider"    => [
        "@type"     => "LocalBusiness",
        "name"      => "Hidrofont",
        "url"       => "https://hidrofont.es",
        "telephone" => "+34613429032"
      ],
      "areaServed" => !empty($pro['zona']) ? [
        "@type" => "City",
        "name"  => $pro['zona']
      ] : "Vinalopó Medio, Alicante",
      "serviceType" => $pro['servicio'] ?? "Fontanería",
      "offers"      => [
        "@type"         => "Offer",
        "priceCurrency" => "EUR",
        "priceRange"    => "€€",
        "availability"  => "https://schema.org/InStock"
      ]
    ];
    break;

  default:
    $schema = [
      "@context"    => "https://schema.org",
      "@type"       => ["LocalBusiness", "Plumber"],
      "name"        => "Hidrofont",
      "url"         => "https://hidrofont.es",
      "telephone"   => "+34613429032",
      "address"     => [
        "@type"           => "PostalAddress",
        "addressLocality" => "Monóvar",
        "addressRegion"   => "Alicante",
        "addressCountry"  => "ES"
      ],
      "openingHoursSpecification" => $horario
    ];
    break;
}

echo json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>
</script>
<!-- GMB -->
<meta name="google-site-verification" content="bP8pCvUrdL8beCS8jZP_CDgePIbmqUAsy2x8wDmyeDw" />
</head>
<body>
<?php include __DIR__ . '/nav.php'; ?>