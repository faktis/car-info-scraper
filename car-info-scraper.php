<?php

set_time_limit(600); // 10 minutes

require_once __DIR__ . '/src/HttpClient.php';
require_once __DIR__ . '/src/BilwebScraper.php';
require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/CarRepository.php';

$httpClient = new HttpClient();

$scraper = new BilwebScraper( $httpClient );

$database = new Database();

$repository = new CarRepository( $database->getConnection() );

$vehicleUrls = $scraper->getVehicleUrls( 500 );

echo 'Unique vehicle URLs found: ' . count($vehicleUrls) . '<br>';

$testUrls = array_slice( $vehicleUrls, 0, 500 );

$insertedCount = 0;

foreach ( $testUrls as $vehicleUrl ) 
{
    $car = $scraper->getCar( $vehicleUrl );

    if ( $repository->insert( $car )) 
    {
        $insertedCount++;
    }

    usleep( 100000 ); // Sleep for 100 milliseconds to avoid overwhelming the server
}

echo 'Inserted: ' . $insertedCount . '<br>';

echo 'Tested URLs: ' . count($testUrls) . '<br>';