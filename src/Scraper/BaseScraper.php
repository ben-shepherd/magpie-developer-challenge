<?php

namespace App\Scraper;

use App\Services\LoggerService;

/**
 * Base abstract class for all scrapers in the application.
 * Provides common functionality and structure for scraping operations.
 */
abstract class BaseScraper
{
    /** @var array Array to store scraped products */
    protected array $products = [];

    /** @var LoggerService Logger service instance for logging operations */
    protected LoggerService $logger;

    /**
     * Constructor initializes the logger service.
     */
    public function __construct()
    {
        $this->logger = new LoggerService();
    }

    /**
     * Abstract method to be implemented by concrete scraper classes.
     * Contains the main scraping logic for specific implementations.
     *
     * @return void
     */
    abstract protected function scrape(): void;

    /**
     * Sets the products array with scraped data.
     *
     * @param array $products Array of scraped products
     * @return void
     */
    public function setProducts(array $products): void
    {
        $this->products = $products;
    }

    /**
     * Retrieves the array of scraped products.
     *
     * @return array Array of scraped products
     */
    public function getProducts(): array
    {
        return $this->products;
    }
}   