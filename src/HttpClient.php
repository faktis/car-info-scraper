<?php

class HttpClient
{
    public function get(string $url): string
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        $html = curl_exec($curl);

        if ($html === false) {
            $error = curl_error($curl);

            curl_close($curl);

            throw new RuntimeException(
                'HTTP request failed: ' . $error
            );
        }

        $statusCode = curl_getinfo(
            $curl,
            CURLINFO_HTTP_CODE
        );

        curl_close($curl);

        if ($statusCode < 200 || $statusCode >= 300) {
            throw new RuntimeException(
                'HTTP request returned status code '
                . $statusCode
            );
        }

        return $html;
    }
}