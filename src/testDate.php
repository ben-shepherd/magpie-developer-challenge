<?php

require 'vendor/autoload.php';

use App\Utils\Date\DateExctractor;
use App\Data\ScrapedProduct;
use App\Formatter\ScrapedProduct\ScrapedProductTransformer;

$str = "                                Delivery from 9 May 2025                            ";

function trimmer(string $text): string
{
    $trimmed = str_replace("\n", "", $text);
    $trimmed = trim(preg_replace('/\s\s+/', ' ', $trimmed));
    return $trimmed;
}

$date = DateExctractor::extractDate(trimmer($str));

if(!$date) {
    echo "No date found";
} else {
    echo $date->format('Y-m-d');
}

