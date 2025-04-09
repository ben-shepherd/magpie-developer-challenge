<?php

namespace App\Data;

/**
 * Data class representing a phone product with all its attributes.
 * This class encapsulates all the information about a specific phone model.
 */
class PhoneProduct
{
    /** @var string The title of the phone product */
    public string $title;
    
    /** @var string The model identifier of the phone */
    public string $model;
    
    /** @var string The version of the phone */
    public string $version;
    
    /** @var string URL to the product image */
    public string $imageUrl;
    
    /** @var int Storage capacity in megabytes */
    public int $capacityMb;
    
    /** @var string Color of the phone */
    public string $color;
    
    /** @var float Price of the phone */
    public float $price;
    
    /** @var string|null Text describing product availability */
    public string|null $availabilityText;
    
    /** @var bool Whether the product is currently available */
    public bool $isAvailable;
    
    /** @var string|null Text describing shipping information */
    public string|null $shippingText;
    
    /** @var string|null Expected shipping date */
    public string|null $shippingDate;

    /** @var string The URL of the product's source */
    public string $sourceUrl;

    /**
     * Constructor for creating a new PhoneProduct instance.
     *
     * @param string $title Product title
     * @param string $model Product model
     * @param string $version Product version
     * @param int $capacityMb Storage capacity in MB
     * @param string $color Product color
     * @param string $imageUrl URL to product image
     * @param float $price Product price
     * @param string|null $availabilityText Availability description
     * @param bool $isAvailable Availability status
     * @param string|null $shippingText Shipping information
     * @param string|null $shippingDate Expected shipping date
     */
    public function __construct(
        string $title,
        string $model,
        string $version,
        int $capacityMb,
        string $color,
        string $imageUrl,
        float $price,
        string|null $availabilityText,
        bool $isAvailable,
        string|null $shippingText,
        string|null $shippingDate,
        string $sourceUrl
    ) {
        $this->title = $title;
        $this->model = $model;
        $this->version = $version;
        $this->imageUrl = $imageUrl;
        $this->capacityMb = $capacityMb;
        $this->color = $color;
        $this->price = $price;
        $this->availabilityText = $availabilityText;
        $this->isAvailable = $isAvailable;
        $this->shippingText = $shippingText;
        $this->shippingDate = $shippingDate;
        $this->sourceUrl = $sourceUrl;
    }

    /**
     * Creates a PhoneProduct instance from an array of data.
     *
     * @param array $data Array containing phone product data
     * @return PhoneProduct New instance of PhoneProduct
     */
    public static function fromArray(array $data): PhoneProduct
    {
        return new PhoneProduct(
            $data['title'],
            $data['model'],
            $data['version'],
            $data['capacityMb'],
            $data['color'],
            $data['imageUrl'],
            $data['price'],
            $data['availabilityText'],
            $data['isAvailable'],
            $data['shippingText'],
            $data['shippingDate'],
            $data['sourceUrl']
        );
    }

    /**
     * Gets the model identifier of the phone.
     *
     * @return string The model identifier
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * Gets the version of the phone.
     *
     * @return string The version
     */
    public function getVersion(): string
    {
        return $this->version;
    }
    
    /**
     * Gets the color of the phone.
     *
     * @return string The color
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * Gets the storage capacity in gigabytes.
     *
     * @return float The capacity in GB
     */
    public function getCapacityGb(): float
    {
        return $this->capacityMb / 1024;
    }

    /**
     * Gets the storage capacity in megabytes.
     *
     * @return int The capacity in MB
     */
    public function getCapacityMb(): int
    {
        return $this->capacityMb;
    }

    /**
     * Converts the phone product to an array representation.
     *
     * @return array Array representation of the phone product
     */
    public function toArray(): array
    {
        return [
            'model' => $this->model,
            'version' => $this->version,
            'capacityMb' => $this->capacityMb,
            'color' => $this->color,
            'imageUrl' => $this->imageUrl,
            'price' => $this->price,
            'availabilityText' => $this->availabilityText,
            'isAvailable' => $this->isAvailable,
            'shippingText' => $this->shippingText,
            'shippingDate' => $this->shippingDate,
        ];
    }
}
