<?php

namespace App\Utils\Date;

use DateTime;
use App\Services\LoggerService;

/**
 * Class DateExtractor
 * 
 * Provides functionality to extract dates from various text formats.
 * 
 * @package App\Utils\Date
 */
class DateExctractor
{

    /**
     * Extracts a date from a text string.
     * 
     * @param string $text The text string to extract the date from
     * @return DateTime|false The extracted date or false if no date is found
     */
    static function extractDate(string $text): DateTime|false
    {
        // This is far from perfect - requires a generic catch all that determines the possibilities of each format by checking each value/position and dynamically generating php format string

        if (preg_match('/tomorrow/', $text)) {
            return extractTomorrowDate($text);
        } elseif (preg_match('/Delivery by/', $text)) {
            return extractDeliveryByDate($text);
        } elseif (preg_match('/Available on/', $text)) {
            return extractAvailableOnDate($text);
        } elseif (preg_match('/Delivery from/', $text)) {
            return extractDeliveryFromDate($text);
        } elseif (preg_match('/Delivers/', $text)) {
            return extractDeliversDate($text);
        } elseif (preg_match('/Free Delivery/', $text)) {
            return extractFreeDeliveryDate($text);
        } elseif (preg_match('/Order within/', $text)) {
            return extractOrderWithinDate($text);
        }
        
        return false;
    }
}

function attemptGenericDateExtraction(string|null $date): DateTime|false
{
    
    $logger = new LoggerService();
    $logger->info("Attempting generic date extraction for: $date");

    if($date === null) {
        return false;
    }

    $containsTomorrow = preg_match('/tomorrow/', $date);

    if($containsTomorrow) {
        return extractTomorrowDate($date);
    }

    $dashOccurances = substr_count($date, '-');
    $likelyDashSeperated = $dashOccurances > 0;

    if($likelyDashSeperated) {
        return DateTime::createFromFormat('Y-m-d', $date);
    }

    $firstPart = explode(' ', $date)[0];
    $likelyBeginsWithDayOfWeek = in_array(strtolower($firstPart), ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);

    $likelyContainsSuffix = in_array(strtolower($date), ['st', 'nd', 'rd', 'th']);

    $logger->info("Likely begins with day of week: " . ($likelyBeginsWithDayOfWeek ? "true" : "false"));
    $logger->info("Likely contains suffix: " . ($likelyContainsSuffix ? "true" : "false"));

    if($likelyBeginsWithDayOfWeek) {
        if($likelyContainsSuffix) {
            return DateTime::createFromFormat('l j M Y', $date);
        } else {
            return DateTime::createFromFormat('l jS M Y', $date);
        }
    } else {
        if($likelyContainsSuffix) {
            return DateTime::createFromFormat('j M Y', $date);
        } else {
            return DateTime::createFromFormat('jS M Y', $date);
        }
    }

    return false;
}

/**
 * Extracts a date for "tomorrow" from the given text.
 * 
 * @param string $text The text containing "tomorrow"
 * @return DateTime|false The date for tomorrow or false if extraction fails
 */
function extractTomorrowDate(string $text): DateTime|false
{
    $now = new DateTime();
    $now->modify('+1 day');
    return $now;
}

/**
 * Extracts a date from text containing "Delivery by" followed by a date.
 * 
 * @param string $text The text containing "Delivery by" followed by a date
 * @return DateTime|false The extracted date or false if extraction fails
 */
function extractDeliveryByDate(string $text): DateTime|false
{
    $pattern = '/Delivery by (.*)/';
    $matches = [];
    preg_match($pattern, $text, $matches);
    $date = $matches[1] ?? null;

    return attemptGenericDateExtraction($date);
}

/**
 * Extracts a date from text containing "Available on" followed by a date.
 * 
 * @param string $text The text containing "Available on" followed by a date
 * @return DateTime|false The extracted date or false if extraction fails
 */
function extractAvailableOnDate(string $text): DateTime|false
{   
    $pattern = '/Available on (.*)/';
    $matches = [];
    preg_match($pattern, $text, $matches);
    $date = $matches[1] ?? null;
    
    return attemptGenericDateExtraction($date);
}

/**
 * Extracts a date from text containing "Delivery from" followed by a date.
 * 
 * @param string $text The text containing "Delivery from" followed by a date
 * @return DateTime|false The extracted date or false if extraction fails
 */
function extractDeliveryFromDate(string $text): DateTime|false
{
    $pattern = '/Delivery from (.*)/';
    $matches = [];
    preg_match($pattern, $text, $matches);
    $date = $matches[1] ?? null;

    return attemptGenericDateExtraction($date);
}

/**
 * Extracts a date from text containing "Delivers" followed by a date.
 * 
 * @param string $text The text containing "Delivers" followed by a date
 * @return DateTime|false The extracted date or false if extraction fails
 */
function extractDeliversDate(string $text): DateTime|false
{
    $pattern = '/Delivers (.*)/';
    $matches = [];
    preg_match($pattern, $text, $matches);
    $date = $matches[1] ?? null;

    return attemptGenericDateExtraction($date);
}

/**
 * Extracts a date from text containing "Free Delivery" followed by a date.
 * 
 * @param string $text The text containing "Free Delivery" followed by a date
 * @return DateTime|false The extracted date or false if extraction fails
 */
function extractFreeDeliveryDate(string $text): DateTime|false
{
    $pattern = '/Free Delivery (.*)/';
    $matches = [];
    preg_match($pattern, $text, $matches);
    $date = $matches[1] ?? null;
    
    return attemptGenericDateExtraction($date);
}

/**
 * Extracts a date from text containing "Order within" followed by a date.
 * 
 * @param string $text The text containing "Order within" followed by a date
 * @return DateTime|false The extracted date or false if extraction fails
 */
function extractOrderWithinDate(string $text): DateTime|false
{
    $pattern = '/Order within .* and have it (.*)/';
    $matches = [];
    preg_match($pattern, $text, $matches);
    $date = $matches[1] ?? null;

    return attemptGenericDateExtraction($date);
}