<?php

require_once __DIR__ . '/src/HttpClient.php';
require_once __DIR__ . '/src/BilwebScraper.php';
require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/CarRepository.php';

$httpClient = new HttpClient();

$scraper = new BilwebScraper(
    $httpClient
);

$database = new Database();

$repository = new CarRepository(
    $database->getConnection()
);

$vehicleUrls = $scraper
    ->getVehicleUrls(20);

$car = $scraper->getCar(
    $vehicleUrls[0]
);

$inserted = $repository->insert(
    $car
);

echo $inserted
    ? 'Car inserted'
    : 'Car already existed';
