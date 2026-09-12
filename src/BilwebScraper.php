<?php

require_once __DIR__ . '/Car.php';

class BilwebScraper
{
    private const BASE_URL = 'https://bilweb.se';

    public function __construct( private HttpClient $httpClient ) 
    {
    }

    public function getVehicleUrlsFromPage( int $page ): array 
    {
        $url = self::BASE_URL . '/sok?page=' . $page;

        $html = $this->httpClient->get( $url );

        $xpath = $this->createXpath( $html );

        $vehicles = $xpath->query( '//*[@id="vehicle-grid"]//*[@data-vehicle-id]' );

        $vehicleUrls = [];

        foreach( $vehicles as $vehicle ) 
        {
            $vehicleId = $vehicle->getAttribute( 'data-vehicle-id' );

            if ( $vehicleId === '' ) 
            {
                continue;
            }

            $links = $xpath->query( './/a[@href]', $vehicle );

            foreach( $links as $link ) 
            {
                $href = $link->getAttribute( 'href' );

                if( !str_contains( $href, $vehicleId )) 
                {
                    continue;
                }

                $vehicleUrls[] = self::BASE_URL . $href;

                break;
            }
        }

        return $vehicleUrls;
    }

    public function getVehicleUrls( int $minimumCount ): array
    {
        $vehicleUrls = [];
        $page = 1;

        while( count( $vehicleUrls ) < $minimumCount ) 
        {
            $countBefore = count( $vehicleUrls ); // Store the count before fetching new URLs to detect if no new URLs are found

            $pageUrls = $this->getVehicleUrlsFromPage( $page );

            if( count( $pageUrls ) === 0) 
            {
                break;
            }

            $vehicleUrls = array_merge( $vehicleUrls, $pageUrls );

            $vehicleUrls = array_values( array_unique( $vehicleUrls ) );
            
            $countAfter = count( $vehicleUrls ); // Store the count after fetching new URLs
            if( $countAfter === $countBefore ) 
            {
                break; // Break the loop if no new URLs were found
            }

            $page++;

            usleep( 200000 ); // Sleep for 200 milliseconds to avoid overwhelming the server
        }

        return $vehicleUrls;
    }

    public function getVehicleInformation( string $vehicleUrl ): array 
    {
        $html = $this->httpClient->get( $vehicleUrl );

        $xpath = $this->createXpath( $html );

        return $this->parseVehicleInformation( $xpath );
    }

    private function parseVehicleInformation( DOMXPath $xpath ): array 
    {
        $vehicleInfoGrids = $xpath->query( '//h4[normalize-space()="Fordonsinformation"]/parent::button/following-sibling::div[1]//div[contains(@class, "grid")][1]' );

        $vehicleInfoGrid = $vehicleInfoGrids->item( 0 );

        if ( $vehicleInfoGrid === null ) 
        {
            throw new RuntimeException( 'Vehicle information grid not found' );
        }

        $infoItems = $xpath->query( './div', $vehicleInfoGrid );

        $vehicleInfo = [];

        foreach ($infoItems as $infoItem) 
        {
            $elements = [];

            foreach ( $infoItem->childNodes as $childNode ) 
            {
                if ( $childNode instanceof DOMElement ) 
                {
                    $elements[] = $childNode;
                }
            }

            if ( count( $elements ) < 2 ) 
            {
                continue;
            }

            $label = trim( $elements[0]->textContent );
            $value = trim( $elements[1]->textContent );

            if ( $label === '' || $value === '') 
            {
                continue;
            }

            $vehicleInfo[$label] = $value;
        }

        return $vehicleInfo;
    }

    private function mapVehicleInformationToCar( array $vehicleInfo, string $sourceUrl ): Car 
    {
        $make = $vehicleInfo['Märke']  ?? '';
        $model = $vehicleInfo['Modell']  ?? '';
        $modelYear = isset( $vehicleInfo['Årsmodell'] ) ? (int) $vehicleInfo['Årsmodell'] : null;
        $registrationNumber = $vehicleInfo['Reg.nr'] ?? null;
        
        return new Car( $make, $model, $modelYear, $registrationNumber, $sourceUrl );
    }

    public function getCar( string $vehicleUrl ): Car 
    {
        $html = $this->httpClient->get( $vehicleUrl );

        $xpath = $this->createXpath( $html );

        $vehicleInfo = $this->parseVehicleInformation( $xpath );

        return $this->mapVehicleInformationToCar( $vehicleInfo, $vehicleUrl);
    }

    private function createXpath( string $html ): DOMXPath 
    {
        $document = new DOMDocument();

        libxml_use_internal_errors( true );

        $document->loadHTML( $html );

        libxml_clear_errors();

        return new DOMXPath( $document );
    }
}