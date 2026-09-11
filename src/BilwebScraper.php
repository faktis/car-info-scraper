<?php

class BilwebScraper
{
    private const BASE_URL = 'https://bilweb.se';

    public function __construct(
        private HttpClient $httpClient
    ) {
    }

    public function getVehicleUrlsFromPage(
        int $page
    ): array {
        $url = self::BASE_URL
            . '/sok?page='
            . $page;

        $html = $this->httpClient->get($url);

        $document = new DOMDocument();

        libxml_use_internal_errors(true);

        $document->loadHTML($html);

        libxml_clear_errors();

        $xpath = new DOMXPath($document);

        $vehicles = $xpath->query(
            '//*[@id="vehicle-grid"]//*[@data-vehicle-id]'
        );

        $vehicleUrls = [];

        foreach ($vehicles as $vehicle) {
            $vehicleId = $vehicle->getAttribute(
                'data-vehicle-id'
            );

            if ($vehicleId === '') {
                continue;
            }

            $links = $xpath->query(
                './/a[@href]',
                $vehicle
            );

            foreach ($links as $link) {
                $href = $link->getAttribute(
                    'href'
                );

                if (!str_contains(
                    $href,
                    $vehicleId
                )) {
                    continue;
                }

                $vehicleUrls[] =
                    self::BASE_URL . $href;

                break;
            }
        }

        return $vehicleUrls;
    }
}