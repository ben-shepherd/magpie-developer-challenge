<?php

namespace App\Scraper;

use App\Services\LoggerService;

abstract class BaseScraper
{
    protected array $products = [];

    protected LoggerService $logger;

    public function __construct()
    {
        $this->logger = new LoggerService();
    }

    abstract protected function scrape(): void;

    public function setProducts(array $products): void
    {
        $this->products = $products;
    }

    public function getProducts(): array
    {
        return $this->products;
    }
}   