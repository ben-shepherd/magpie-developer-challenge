<?php

namespace App;

use App\Scraper\MagpiehqScraper;
use App\Services\LoggerService;

class Scrape
{
    public function run(): void
    {
        // Initialize the scraper
        $magpiehqScraper = new MagpiehqScraper();

        // Scrape the data
        $magpiehqScraper->scrape();

        // Save products to file
        file_put_contents('output.json', json_encode($magpiehqScraper->getProducts(), JSON_PRETTY_PRINT));

        (new LoggerService())->info("Scraped data saved to output.json");
    }
}