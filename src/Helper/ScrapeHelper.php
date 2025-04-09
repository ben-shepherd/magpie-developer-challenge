<?php

namespace App\Helper;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeHelper
{
    public static function fetchDocument(string $url, int | null $page = null): Crawler
    {
        $client = new Client();

        $url = self::getUrl($url, $page);

        $response = $client->get($url);

        if($response->getStatusCode() !== 200) {
            throw new \Exception('Client returned status code ' . $response->getStatusCode());
        }

        return new Crawler($response->getBody()->getContents(), $url);
    }

    /**
     * Get the URL with the optional page parameter
     * 
     * @param string $url The base URL
     * @param int|null $page The page number to append to the URL
     * @return string The complete URL
     */
    protected static function getUrl(string $url, int | null $page = null): string
    {
        if($page) {
            return $url . '?page=' . $page;
        }

        return $url;
    }
}
