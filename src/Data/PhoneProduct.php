<?php

namespace App\Data;

class PhoneProduct
{
    public string $model;
    public string $version;
    public string $imageUrl;
    public int $capacityMb;
    public string $color;
    public float $price;
    public string|null $availabilityText;
    public bool $isAvailable;
    public string|null $shippingText;
    public string|null $shippingDate;
    
    public function __construct(
        string $model,
        string $version,
        int $capacityMb,
        string $color,
        string $imageUrl,
        float $price,
        string|null $availabilityText,
        bool $isAvailable,
        string|null $shippingText,
        string|null $shippingDate
    ) {
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
    }

    public static function fromArray(array $data): PhoneProduct
    {
        return new PhoneProduct(
            $data['model'],
            $data['version'],
            $data['capacityMb'],
            $data['color'],
            $data['imageUrl'],
            $data['price'],
            $data['availabilityText'],
            $data['isAvailable'],
            $data['shippingText'],
            $data['shippingDate']
        );
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getVersion(): string
    {
        return $this->version;
    }
    
    public function getColor(): string
    {
        return $this->color;
    }

    public function getCapacityGb(): float
    {
        return $this->capacityMb / 1024;
    }

    public function getCapacityMb(): int
    {
        return $this->capacityMb;
    }

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
