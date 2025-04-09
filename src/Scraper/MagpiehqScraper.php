<?php

namespace App\Scraper;

use App\Helper\ScrapeHelper;
use DOMNode;
use Symfony\Component\DomCrawler\Crawler;
use App\Data\ScrapedProduct;
use App\Formatter\ScrapedProduct\ScrapedProductTransformer;
use App\Utils\Phone\StorageDetector;
use App\Data\PageData;

/**
 * Class MagpiehqScraper
 * 
 * Scraper for extracting smartphone data from MagpieHQ website
 */
class MagpiehqScraper extends BaseScraper
{

    // In reality, this would likely come from a database or a config file
    public static $baseUrl = "https://www.magpiehq.com/developer-challenge/smartphones";
    public static $imageBaseUrl = "https://www.magpiehq.com/developer-challenge";

    /**
     * Main scraping method that orchestrates the scraping process
     * - Extracts all pages from the base URL
     * - Scrapes each page
     * - Transforms the scraped products
     * - Sets the transformed products
     * 
     * @return void
     */
    public function scrape(): void
    {
        $this->logger->info('Extracting pages from ' . self::$baseUrl);

        $documentCrawler = ScrapeHelper::fetchDocument(self::$baseUrl);

        $pages = $this->extractPageData($documentCrawler);

        $this->logger->info('Found ' . count($pages) . ' pages');
        $this->logger->info(json_encode($pages, JSON_PRETTY_PRINT));

        foreach($pages as $page) {
            $this->scrapePage($page);
        }
    }

    /**
     * Scrapes a specific page
     * 
     * @param PageData $page The page data to scrape
     * @return void
     */
    protected function scrapePage(PageData $page): void
    {
        $this->logger->info('Scraping page (' . $page->getPage() . '): ' . $page->getUrl());

        // Fetch the document from the target URL
        $documentCrawler = ScrapeHelper::fetchDocument($page->getUrl());

        // Products in an array of ScrapedProduct objects (raw data)
        $scrapedProducts = $this->scrapeDocument($documentCrawler, $page);

        $this->logger->info("Scraped " . count($scrapedProducts) . " products");
        $this->logger->jsonPrettyPrint($scrapedProducts);

        // Transforms scraped products into PhoneProduct data objects (more refined data)
        // Duplicates are removed
        $transformedProducts = $this->transform($scrapedProducts);

        $this->logger->info("Transformed:");
        $this->logger->jsonPrettyPrint($transformedProducts);

        // Append the transformed products
        $this->appendProducts($transformedProducts);
    }

    /**
     * Removes duplicate products from the collection
     * 
     * @param ScrapedProduct[] $scrapedProducts
     * @return PhoneProduct[]
     */
    protected function transform(array $scrapedProducts): array
    {
        return ScrapedProductTransformer::transform($scrapedProducts);
    }

    /**
     * Scrapes the document for product information
     * 
     * @param Crawler $documentCrawler The crawler instance with the document
     * @param PageData $page The page data to scrape
     * @return ScrapedProduct[]
     */
    protected function scrapeDocument(Crawler $documentCrawler, PageData $page): array
    {
        // Final array of products
        $allProducts = [];

        // Find all product nodes in the document
        $divProductNodes = $documentCrawler->filter('div.product');

        // Process each product node
        foreach ($divProductNodes as $productNode) {
            $scrapedProducts = $this->createScrapedProductData($productNode, $page);

            $allProducts = array_merge($allProducts, $scrapedProducts);
        }

        return $allProducts;
    }

    /**
     * Processes a single product node and extracts its information
     * 
     * @param DOMNode $product The product DOM node
     * @return ScrapedProduct[]
     */
    protected function createScrapedProductData(DOMNode $productNode, PageData $page): array
    {
        $crawler = new Crawler($productNode);
        $title = $crawler->filter('h3')->text();
        $price = $this->handlePrice($productNode);
        $imgUrl = $this->handleImageUrl($productNode);
        $availabilityText = $this->handleAvailabilityText($productNode);
        $variants = $this->handleVariant($productNode);
        $capacity = $this->handleCapacity($title);
        $shippingText = $this->handleShippingText($productNode);
        $sourceUrl = $page->getUrl();

        // Create a ScrapedProduct for each variant
        return array_map(function (string $variant) use ($title, $price, $imgUrl, $availabilityText, $capacity, $shippingText, $sourceUrl) {
            return new ScrapedProduct(
                $title,
                $price,
                $imgUrl,
                $variant,
                $capacity,
                $availabilityText,
                $shippingText,
                $sourceUrl
            );
        }, $variants);
    }

    /**
     * Extracts shipping text from the product node
     * 
     * @param DOMNode $product The product DOM node
     * @return string|null The extracted shipping text or null if not found
     */
    protected function handleShippingText(DOMNode $productNode): string | null
    {
        $crawler = new Crawler($productNode);
        $divs = $crawler->filter('div');
        $occurances = [];

        foreach ($divs as $div) {
            if (str_contains(strtolower($div->textContent), 'delivery')) {
                $occurances[] = $div->textContent;
            }
        }

        if(count($occurances) === 0) {
            return null;
        }

        $lastOccurance = end($occurances);

        return $lastOccurance;
    }

    /**
     * Extracts image URL from the product node
     * 
     * @param DOMNode $product The product DOM node
     * @return string|null The extracted image URL or null if not found
     */
    protected function handleImageUrl(DOMNode $productNode): string | null
    {
        $crawler = new Crawler($productNode);
        $imgUrl = $crawler->filter('img')->attr('src');

        return $imgUrl;
    }

    /**
     * Extracts availability text from the product node
     * 
     * @param DOMNode $product The product DOM node
     * @return string|null The extracted availability text or null if not found
     */
    protected function handleAvailabilityText(DOMNode $productNode): string | null
    {
        $crawler = new Crawler($productNode);
        $divs = $crawler->filter('div');
        $occurances = [];

        foreach ($divs as $div) {
            if (str_contains(strtolower($div->textContent), 'availability')) {
                $occurances[] = $div->textContent;
            }
        }

        if(count($occurances) === 0) {
            return null;
        }

        $lastOccurance = end($occurances);

        return $lastOccurance;
    }

    /**
     * Extracts storage capacity information from the product title
     * 
     * @param string $title The product title
     * @return string|null The extracted storage capacity or null if not found
     */
    protected function handleCapacity(string $title): string | null
    {
        return StorageDetector::extractStorageString($title);
    }

    /**
     * Extracts color variants from the product node
     * 
     * @param DOMNode $product The product DOM node
     * @return array Array of color variants
     */
    protected function handleVariant(DOMNode $productNode): array
    {
        $crawler = new Crawler($productNode);
        $variantNodes = $crawler->filter('span[data-colour]');
        $variants = [];

        // Extract each color variant
        foreach ($variantNodes as $variantNode) {
            $variantNodeCrawler = new Crawler($variantNode);
            $variant = $variantNodeCrawler->attr('data-colour');

            $variants[] = $variant;
        }

        return $variants;
    }

    /**
     * Extracts price information from the product node
     * 
     * @param DOMNode $productNode The product DOM node
     * @return string|null The extracted price or null if not found
     */
    protected function handlePrice(DOMNode $productNode): string | null
    {
        $crawler = new Crawler($productNode);
        $divs = $crawler->filter('div');

        $pattern = "/£([\d]+\.[\d]*)/";

        // Search for price pattern in each div
        foreach ($divs as $div) {
            if (preg_match($pattern, $div->textContent, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Extracts page data from the document crawler
     * 
     * @param Crawler $documentCrawler The crawler instance with the document
     * @return array<PageData> The extracted page data
     */
    protected function extractPageData(Crawler $documentCrawler): array
    {
        $divPageNodes = $documentCrawler->filter('div#pages');
        $aNodes = $divPageNodes->filter('a');

        for($i = 0; $i < $aNodes->count(); $i++) {
            $pageNumber = intval($aNodes->eq($i)->text());

            $url = $this->formatUrl(
                $aNodes->eq($i)->attr('href')
            );

            $pages[] = new PageData($pageNumber, $url);
        }

        return $pages;
    }

    /**
     * Formats the URL
     * 
     * @param string $url The URL to format
     * @return string The formatted URL
     */
    protected function formatUrl(string $url): string
    {
        if(str_starts_with($url, '..')) { 
            $url = substr($url, 2);
        }

        if(str_starts_with($url, '/smartphones')) {
            $url = substr($url, strlen('/smartphones'));
        }

        if(!str_starts_with($url, static::$baseUrl)) {
            $url = static::$baseUrl . $url;
        }

        return  $url;
    }
}