<?php
/**
 * Renders the schema.org LocalBusiness JSON-LD for the homepage.
 *
 * The block is generated from data/clinic.json so the structured data cannot
 * drift from the prices and details the site actually publishes. Edit that
 * file, not this one; run /clinic-sync to check it still matches pricing.php.
 *
 * Renders server-side, so crawlers receive the finished markup in the HTML.
 * If the data file is missing or malformed, nothing is emitted rather than a
 * broken block.
 */

$clinic = json_decode(@file_get_contents(__DIR__ . '/../data/clinic.json'), true);
if (!is_array($clinic) || empty($clinic['services'])) {
  return;
}

$catalogs = [];
foreach ($clinic['services'] as $service) {
  $offers = [];
  foreach ($service['items'] as $item) {
    $spec = [
      '@type'         => 'PriceSpecification',
      'price'         => $item['price'],
      'priceCurrency' => $item['priceCurrency'],
    ];
    // Only assert the tax position where pricing.php states one ("+ HST");
    // the remaining services are HST-exempt health care, so stay silent.
    if (!empty($item['taxNote'])) {
      $spec['valueAddedTaxIncluded'] = false;
    }
    $offers[] = [
      '@type' => 'Offer',
      'itemOffered' => ['@type' => 'Service', 'name' => $item['name']],
      'priceSpecification' => $spec,
    ];
  }
  $catalogs[] = [
    '@type'           => 'OfferCatalog',
    'name'            => $service['category'],
    'url'             => rtrim($clinic['business']['url'], '/') . $service['url'],
    'itemListElement' => $offers,
  ];
}

$hoursSpec = [];
foreach (['weekday', 'weekend'] as $block) {
  $hoursSpec[] = [
    '@type'     => 'OpeningHoursSpecification',
    'dayOfWeek' => $clinic['hours'][$block]['days'],
    'opens'     => $clinic['hours'][$block]['opens'],
    'closes'    => $clinic['hours'][$block]['closes'],
  ];
}

$schema = [
  '@context'    => 'https://schema.org',
  '@type'       => 'LocalBusiness',
  '@id'         => $clinic['business']['id'],
  'name'        => $clinic['business']['name'],
  'url'         => $clinic['business']['url'],
  'telephone'   => $clinic['business']['telephone'],
  'email'       => $clinic['business']['email'],
  'priceRange'  => $clinic['business']['priceRange'],
  'currenciesAccepted' => 'CAD',
  'logo'        => $clinic['logo'],
  'image'       => $clinic['images'],
  'address'     => [
    '@type'           => 'PostalAddress',
    'streetAddress'   => $clinic['address']['street'],
    'addressLocality' => $clinic['address']['locality'],
    'addressRegion'   => $clinic['address']['region'],
    'postalCode'      => $clinic['address']['postalCode'],
    'addressCountry'  => $clinic['address']['country'],
  ],
  'geo' => [
    '@type'     => 'GeoCoordinates',
    'latitude'  => $clinic['geo']['latitude'],
    'longitude' => $clinic['geo']['longitude'],
  ],
  'openingHoursSpecification' => $hoursSpec,
  'aggregateRating' => [
    '@type'       => 'AggregateRating',
    'ratingValue' => $clinic['rating']['ratingValue'],
    'reviewCount' => $clinic['rating']['reviewCount'],
    'bestRating'  => $clinic['rating']['bestRating'],
  ],
  'sameAs'         => $clinic['sameAs'],
  'hasOfferCatalog' => [
    '@type'           => 'OfferCatalog',
    'name'            => 'Health services',
    'itemListElement' => $catalogs,
  ],
  'potentialAction' => [
    '@type'  => 'ReserveAction',
    'target' => [
      '@type'          => 'EntryPoint',
      'urlTemplate'    => $clinic['business']['bookingUrl'],
      'actionPlatform' => [
        'https://schema.org/DesktopWebPlatform',
        'https://schema.org/IOSPlatform',
        'https://schema.org/AndroidPlatform',
      ],
    ],
    'result' => ['@type' => 'Reservation', 'name' => 'Book Now'],
  ],
];
?>
  <script type="application/ld+json">
<?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>

  </script>
