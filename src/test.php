<?php

require 'vendor/autoload.php';

use App\Utils\Date\DateExctractor;
use App\Data\ScrapedProduct;
use App\Formatter\ScrapedProduct\ScrapedProductTransformer;

$json = <<<EOF
[
    {
        "title": "iPhone 12 Pro Max 128GB",
        "price": "1099.99",
        "imageUrl": "../images/iphone-12-pro.png",
        "variant": "Sky Blue",
        "capacity": "128GB",
        "availabilityText": "                            Availability: In Stock Online                        ",
        "shippingText": "                                Delivery by Thursday 10th Apr 2025                            "
    }
]
EOF;

$data = json_decode($json, true);

if(!$data) {
    echo 'Error: ' . json_last_error_msg() . "\n\n";
    exit;
}

echo 'Data: ' . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";

$scrapedProduct = new ScrapedProduct(
    $data[0]['title'],
    $data[0]['price'],
    $data[0]['imageUrl'],
    $data[0]['variant'],
    $data[0]['capacity'],
    $data[0]['availabilityText'],
    $data[0]['shippingText']
);

echo 'Scraped Product: ' . json_encode($scrapedProduct, JSON_PRETTY_PRINT) . "\n\n";

$transformer = new ScrapedProductTransformer();

$phoneProduct = $transformer->transform([$scrapedProduct]);

echo 'Phone Product: ' . json_encode($phoneProduct, JSON_PRETTY_PRINT) . "\n\n";


