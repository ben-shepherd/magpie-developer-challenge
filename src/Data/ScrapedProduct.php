<?php

namespace App\Data;

/**
 * Represents raw product data scraped from a source.
 * 
 * This class holds the basic information about a product that has been
 * scraped from a website or other data source.
 */
class ScrapedProduct
{
    /** @var string The title or name of the product */
    public string $title;
    
    /** @var string The price of the product (may include currency symbol) */
    public string $price;
    
    /** @var string URL to the product's image */
    public string $imageUrl;
    
    /** @var string The variant or model of the product */
    public string $variant;
    
    /** @var string|null The capacity or size of the product, if applicable */
    public string|null $capacity;
    
    /** @var string|null Text describing the product's availability status */
    public string|null $availabilityText;
    
    /** @var string|null Text describing shipping information */
    public string|null $shippingText;

    /** @var string The URL of the product's source */
    public string $sourceUrl;

    /**
     * Creates a new ScrapedProduct instance.
     *
     * @param string $title The title or name of the product
     * @param string $price The price of the product
     * @param string $imageUrl URL to the product's image
     * @param string $variant The variant or model of the product
     * @param string|null $capacity The capacity or size of the product, if applicable
     * @param string|null $availabilityText Text describing the product's availability status
     * @param string|null $shippingText Text describing shipping information
     */
    public function __construct(
        string $title,
        string $price,
        string $imageUrl,
        string $variant,
        string|null $capacity,
        string|null $availabilityText,
        string|null $shippingText,
        string $sourceUrl
    ) {
        $this->title = $title;
        $this->price = $price;
        $this->imageUrl = $imageUrl;
        $this->variant = $variant;
        $this->capacity = $capacity;
        $this->availabilityText = $availabilityText;
        $this->shippingText = $shippingText;
        $this->sourceUrl = $sourceUrl;
    }

    /**
     * Converts the ScrapedProduct object to an array.
     * 
     * @return array<int, {title: string, price: string, imageUrl: string, variant: string, capacity: string|null, availabilityText: string|null, shippingText: string|null}> 
     *  The array representation of the ScrapedProduct object
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'price' => $this->price,
            'imageUrl' => $this->imageUrl,
            'variant' => $this->variant,
            'capacity' => $this->capacity,
            'availabilityText' => $this->availabilityText,
            'shippingText' => $this->shippingText,
            'sourceUrl' => $this->sourceUrl,
        ];
    }
}
