<?php

require 'vendor/autoload.php';

use App\Utils\Date\DateExctractor;
use App\Data\ScrapedProduct;
use App\Formatter\ScrapedProduct\ScrapedProductTransformer;

$json = <<<EOF
[
    {
        "title": "Nokia 3310 100MB",
        "price": "99.99",
        "imageUrl": "../images/nokia-3310.png",
        "variant": "Orange",
        "capacity": "100MB",
        "availabilityText": "                            Availability: Out of Stock                        ",
        "shippingText": "                                Delivery from Friday 9th May 2025                            ",
        "sourceUrl": "https:\/\/www.magpiehq.com\/developer-challenge\/smartphones?page=1"
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
    $data[0]['shippingText'],
    $data[0]['sourceUrl']
);

echo 'Scraped Product: ' . json_encode($scrapedProduct, JSON_PRETTY_PRINT) . "\n\n";

$transformer = new ScrapedProductTransformer();

$phoneProduct = $transformer->transform([$scrapedProduct]);

echo 'Phone Product: ' . json_encode($phoneProduct, JSON_PRETTY_PRINT) . "\n\n";


