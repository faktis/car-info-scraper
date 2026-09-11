<?php

require_once __DIR__ . '/src/HttpClient.php';
require_once __DIR__ . '/src/BilwebScraper.php';

$httpClient = new HttpClient();

$scraper = new BilwebScraper(
    $httpClient
);

$vehicleUrls = $scraper->getVehicleUrls(20);

echo 'Unique vehicle URLs found: '
    . count($vehicleUrls)
    . '<br>';

$vehicleUrl = $vehicleUrls[15];

$vehicleInfo = $scraper
    ->getVehicleInformation(
        $vehicleUrl
    );

echo '<pre>';
print_r($vehicleInfo);
echo '</pre>';