<?php

namespace App\Scraper;

use App\Helper\ScrapeHelper;
use DOMNode;
use Symfony\Component\DomCrawler\Crawler;
use App\Data\ScrapedProduct;
use App\Formatter\ScrapedProduct\ScrapedProductTransformer;
use App\Utils\Phone\StorageDetector;

/**
 * Class MagpiehqScraper
 * 
 * Scraper for extracting smartphone data from MagpieHQ website
 */
class MagpiehqScraper extends BaseScraper
{
    public static $baseUrl = "https://www.magpiehq.com/developer-challenge/smartphones";

    public static $imageBaseUrl = "https://www.magpiehq.com/developer-challenge";

    /**
     * Main scraping method that orchestrates the scraping process
     * 
     * @return void
     */
    public function scrape(): void
    {
        // Fetch the document from the target URL
        $documentCrawler = ScrapeHelper::fetchDocument(self::$baseUrl);

        // Scrape products
        $this->scrapeDocument($documentCrawler);
        $this->logger->info("Scraped products:\n" . json_encode($this->products, JSON_PRETTY_PRINT));

        // Transforms scraped products into PhoneProduct objects (more refined data)
        // Duplicates are removed
        $transformedProducts = $this->transform();

        $this->logger->info('Transformed ' . count($transformedProducts) . ' products');
        $this->logger->info(json_encode($transformedProducts, JSON_PRETTY_PRINT));
        
        // Set the transformed products
        $this->setProducts($transformedProducts);
    }

    /**
     * Removes duplicate products from the collection
     * 
     * @return array<PhoneProduct>
     */
    protected function transform(): array
    {
        return ScrapedProductTransformer::transform($this->products);
    }

    /**
     * Scrapes the document for product information
     * 
     * @param Crawler $documentCrawler The crawler instance with the document
     * @return void
     */
    protected function scrapeDocument(Crawler $documentCrawler): void
    {
        // Find all product nodes in the document
        $productNodes = $documentCrawler->filter('div.product');

        $this->logger->info('Scraping ' . $productNodes->count() . ' products');

        $allProducts = [];

        // Process each product node
        foreach ($productNodes as $productNode) {
            $allProducts = array_merge($allProducts, $this->handleProduct($productNode));
        }

        $this->setProducts($allProducts);
    }

    /**
     * Processes a single product node and extracts its information
     * 
     * @param DOMNode $product The product DOM node
     * @return ScrapedProduct[]
     */
    protected function handleProduct(DOMNode $product): array
    {
        $crawler = new Crawler($product);
        $title = $crawler->filter('h3')->text();
        $price = $this->handlePrice($product);
        $imgUrl = $this->handleImageUrl($product);
        $availabilityText = $this->handleAvailabilityText($product);
        $variants = $this->handleVariant($product);
        $capacity = $this->handleCapacity($title);
        $shippingText = $this->handleShippingText($product);

        $this->logger->info('Scraping product: ' . $title . ' with price: ' . $price . ' and variants: ' . implode(', ', $variants) . ' and capacity: ' . $capacity);

        // Create a ScrapedProduct for each variant
        return array_map(function (string $variant) use ($title, $price, $imgUrl, $availabilityText, $capacity, $shippingText) {
            return new ScrapedProduct(
                $title,
                $price,
                $imgUrl,
                $variant,
                $capacity,
                $availabilityText,
                $shippingText
            );
        }, $variants);
    }

    /**
     * Extracts shipping text from the product node
     * 
     * @param DOMNode $product The product DOM node
     * @return string|null The extracted shipping text or null if not found
     */
    protected function handleShippingText(DOMNode $product): string | null
    {
        $crawler = new Crawler($product);
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
    protected function handleImageUrl(DOMNode $product): string | null
    {
        $crawler = new Crawler($product);
        $imgUrl = $crawler->filter('img')->attr('src');

        return $imgUrl;
    }

    /**
     * Extracts availability text from the product node
     * 
     * @param DOMNode $product The product DOM node
     * @return string|null The extracted availability text or null if not found
     */
    protected function handleAvailabilityText(DOMNode $product): string | null
    {
        $crawler = new Crawler($product);
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
    protected function handleVariant(DOMNode $product): array
    {
        $crawler = new Crawler($product);
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
     * @param DOMNode $product The product DOM node
     * @return string|null The extracted price or null if not found
     */
    protected function handlePrice(DOMNode $product): string | null
    {
        $crawler = new Crawler($product);
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
}