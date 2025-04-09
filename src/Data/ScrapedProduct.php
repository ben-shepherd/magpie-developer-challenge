<?php

namespace App\Data;

class ScrapedProduct
{
    public string $title;
    public string $price;
    public string $imageUrl;
    public string $variant;
    public string|null $capacity;
    public string|null $availabilityText;
    public string|null $shippingText;

    public function __construct(
        string $title,
        string $price,
        string $imageUrl,
        string $variant,
        string|null $capacity,
        string|null $availabilityText,
        string|null $shippingText
    ) {
        $this->title = $title;
        $this->price = $price;
        $this->imageUrl = $imageUrl;
        $this->variant = $variant;
        $this->capacity = $capacity;
        $this->availabilityText = $availabilityText;
        $this->shippingText = $shippingText;
    }
}
