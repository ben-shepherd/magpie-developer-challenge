<?php

namespace App\Formatter\ScrapedProduct;

use App\Data\PhoneProduct;
use App\Data\ScrapedProduct;
use App\Services\LoggerService;
use App\Services\PhoneProductService;
use App\Utils\Phone\StorageDetector;

/**
 * Transformer class that removes duplicate phone products from scraped data.
 * 
 * This class processes an array of ScrapedProduct objects, transforms them into
 * PhoneProduct objects, and removes duplicates based on a unique identifier
 * composed of model, version, color, and storage capacity.
 */
class ScrapedProductTransformer
{
    public LoggerService $logger;

    public function __construct()
    {
        $this->logger = new LoggerService();
    }

    /**
     * Transforms an array of ScrapedProduct objects into a deduplicated array of PhoneProduct objects.
     * 
     * The deduplication process works by:
     * 1. Converting all ScrapedProduct objects to PhoneProduct objects
     * 2. Creating a unique identifier for each PhoneProduct
     * 3. Keeping only the first occurrence of each unique product
     * 
     * @param ScrapedProduct[] $scrapedProducts Array of scraped product data
     * @return PhoneProduct[] Deduplicated array of phone products
     */
    public static function transform(array $scrapedProducts): array
    {
        $transformer = new self();

        // Transform all scraped products to PhoneProduct objects
        $dtoArray = $transformer->transformToHardwareProduct($scrapedProducts);
        $transformer->logger->info("transformToHardwareProduct result:\n" . json_encode($dtoArray, JSON_PRETTY_PRINT));
        
        // Remove white spaces and new lines from the array
        $dtoArray = $transformer->trimLines($dtoArray);
        $transformer->logger->info("trimLines result:\n" . json_encode($dtoArray, JSON_PRETTY_PRINT));

        // Process each PhoneProduct and keep only the first occurrence of each unique product
        // $dtoArray = $transformer->transformRemoveDuplicates($dtoArray);



        // Return only the values (PhoneProduct objects) without the keys
        return $dtoArray;
    }

    /**
     * Removes white spaces and new lines from the array
     * 
     * @param PhoneProduct[] $dtoArray
     * @return PhoneProduct[]
     */
    protected function trimLines(array $dtoArray): array
    {
        $requiresTrim = ['shippingText', 'availabilityText', 'shippingDate'];

        return array_map(function (PhoneProduct $dto) use ($requiresTrim) {
            $data = $dto->toArray();
            $result = [];

            foreach($data as $key => $value) {

                if(in_array($key, $requiresTrim) && is_string($value)) {
                    $trimmed = str_replace("\n", "", $value);
                    $trimmed = trim(preg_replace('/\s\s+/', ' ', $trimmed));
                    $result[$key] = $trimmed;
                    continue;
                }

                $result[$key] = $value;
            }

            $this->logger->info("Trimming lines: " . json_encode($result));
            return PhoneProduct::fromArray($result);
        }, $dtoArray);
    }

    /**
     * Transforms an array of PhoneProduct objects into a deduplicated array of PhoneProduct objects.
     * 
     * The deduplication process works by:
     * 1. Creating a unique identifier for each PhoneProduct
     * 2. Keeping only the first occurrence of each unique product
     * 
     * @param PhoneProduct[] $dtoArray Array of PhoneProduct objects
     * @return PhoneProduct[] Deduplicated array of phone products
     */
    protected function transformRemoveDuplicates(array $dtoArray): array
    {
        $transformer = new self();

        /**
         * Associative array to store unique phone products
         * Key: Unique identifier string
         * Value: PhoneProduct object
         * 
         * @var array<string,PhoneProduct>
         */
        $unqiuePhoneProduct = [];

        // Process each PhoneProduct and keep only the first occurrence of each unique product
        foreach ($dtoArray as $data) {
            // Generate a unique identifier for this product
            $PhoneProductId = $transformer->getUniquePhoneProductId($data->toArray());

            // Only add the product if we haven't seen this unique combination before
            if (!isset($unqiuePhoneProduct[$PhoneProductId])) {
                $unqiuePhoneProduct[$PhoneProductId] = $data;
            }
        }

        // Return only the values (PhoneProduct objects) without the keys
        return array_values($unqiuePhoneProduct);
    }

    /**
     * Transforms an array of ScrapedProduct objects into PhoneProduct objects.
     * 
     * For each scraped product, this method:
     * 1. Extracts the model and version from the title
     * 2. Extracts the storage capacity from the title
     * 3. Creates a new PhoneProduct with the extracted data
     * 
     * @param ScrapedProduct[] $scrapedProducts Array of scraped product data
     * @return PhoneProduct[] Array of transformed phone products
     */
    protected function transformToHardwareProduct(array $scrapedProducts): array
    {
        $this->logger->info('transformToHardwareProduct');

        return array_map(function (ScrapedProduct $scrapedProduct) {

            $title = $scrapedProduct->title;
            $price = $scrapedProduct->price;
            $imageUrl = $scrapedProduct->imageUrl;
            $model = PhoneProductService::detectModel($title);
            $version = PhoneProductService::detectVersion($title, $model);
            $colour = $scrapedProduct->variant;
            $capacityMb = StorageDetector::extractStorageMegabytes($title);
            $isAvailable = $this->extractAvailabilityBoolean($scrapedProduct->availabilityText);
            $shippingDate = $this->extractShippingDate($scrapedProduct->shippingText);

            // Create and return a new PhoneProduct with    the extracted data
            $hardwareProduct = new PhoneProduct(
                $model,
                $version,
                $capacityMb,
                $colour,
                $imageUrl,
                $price,
                $scrapedProduct->availabilityText,
                $isAvailable,
                $scrapedProduct->shippingText,
                $shippingDate,
            );

            return $hardwareProduct;
        }, $scrapedProducts);
    }

    protected function extractShippingDate(string|null $shippingText): string
    {
        if($shippingText === null) {
            return "";
        }

        // TODO: Implement this
        return $shippingText;
    }

    protected function extractAvailabilityBoolean(string $availabilityText): bool
    {
        return str_contains(strtolower($availabilityText), 'in stock');
    }

    /**
     * Generates a unique identifier for a phone product based on its key attributes.
     * 
     * The unique identifier is a concatenation of:
     * - model
     * - version
     * - color
     * - storage capacity in megabytes
     * 
     * This identifier is used to detect and remove duplicate products.
     * 
     * @param array $PhoneProduct Array representation of a PhoneProduct
     * @return string Unique identifier string in the format "model:version:color:capacityMb"
     */
    protected function getUniquePhoneProductId(array $PhoneProduct): string
    {
        return $PhoneProduct['model'] . ':' . $PhoneProduct['version'] . ':' . $PhoneProduct['color'] . ':' . $PhoneProduct['capacityMb'];
    }
}
