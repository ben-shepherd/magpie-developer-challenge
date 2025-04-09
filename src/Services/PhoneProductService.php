<?php

namespace App\Services;

/**
 * Service class for processing and converting scraped product data into structured phone product data.
 * 
 * This class provides methods to extract and standardize phone information from scraped product titles,
 * including model detection, version identification, and storage capacity extraction.
 */
class PhoneProductService
{

    /**
     * Detects the phone model from the product title.
     * 
     * Uses a predefined list of model keywords to identify the phone manufacturer/model.
     *
     * @param string $title The product title to analyze
     * @return string|null The detected model name or null if not found
     */
    static function detectModel(string $title): string | null
    {
        // Define keywords associated with each phone model
        $modelKeywords = [
            'iphone' => ['iphone', 'apple'],
            'samsung' => ['samsung galaxy', 'samsung', 'galaxy'],
            'huawei' => ['huawei p', 'huawei', 'hau'],
            'nokia' => ['nokia'],
            'google pixel' => ['google pixel', 'google', 'pixel'],
            'sony' => ['sony xperia', 'sony', 'xperia'],
            'oppo' => ['oppo reno', 'oppo', 'reno'],
            'lg' => ['lg g', 'lg k', 'lg'],
        ];

        // Check if any of the model keywords are present in the title
        foreach ($modelKeywords as $model => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos(strtolower($title), $keyword) !== false) {
                    return $model;
                }
            }
        }

        return null;
    }

    /**
     * Detects the specific version/model number of a phone from its title.
     * 
     * Uses a predefined list of version keywords specific to each phone model.
     *
     * @param string $title The product title to analyze
     * @param string $model The detected phone model
     * @return string|null The detected version or null if not found
     */
    static function detectVersion(string $title, string $model): string | null
    {
        // Unfinished list of versions that should come from an external source
        $versionKeywords = [
            'iphone' => [
                '11',
                '11 Pro',
                '12',
                '12 Pro',
                '12 Pro Max',
                '13',
                '13 Pro',
                '13 Pro Max',
            ],
            'nokia' => [
                '3310',
                '3310 4G',
                '3310 Dual SIM',
                '3310 4G Dual SIM',
            ],
            'huawei' => [
                'P30',
                'P30 Pro',
                'P40',
                'P40 Pro',
                'P40 Pro+',
                'P50',
                'P50 Pro',
            ],
            'samsung' => [
                'Galaxy S20',
                'Galaxy S20+',
                'Galaxy S20 Ultra',
                'Galaxy S21',
                'Galaxy S21+',
                'Galaxy Flip'
            ],
            'google pixel' => [
                'Pixel 4',
                'Pixel 4 XL',
                'Pixel 5',
                'Pixel 5 Pro',
                'Pixel 6',
                'Pixel 6 Pro',
                'Pixel 7',
                'Pixel 7 Pro',
                'Pixel 8',
                'Pixel 8 Pro',
            ],
            'sony' => [
                'Xperia 1',
                'Xperia 1 II',
                'Xperia 1 III',
                'Xperia 5',
                'Xperia 5 II',
                'Xperia 10',
                'Xperia 10 II',
                'Xperia 10 III',
                'Xperia 10 IV',
                'Xperia 10 V',
                'Xperia 10 VI',
                'Xperia 10 VII',
            ],
            'oppo' => [
                'Reno 10',
                'Reno 10 Pro',
                'Reno 10 Pro+',
                'Reno 10 Pro+',
            ],
            'lg' => [
                'G8',
                'G8 ThinQ',
                'G8X',
                'G8X ThinQ',
                'G9',
                'G9 ThinQ',
                'G9X',
                'G9X ThinQ',
                'G10',
                'G10 Plus',
                'G10 Plus',
                'K42',
                'K42 Plus',
                'K52',
                'K52 Plus',
                'K62',
                'K62 Plus',
                
            ],
        ];

        // Get the version keywords for the detected model, or empty array if model not found
        $versionKeywordsRelatedModel = $versionKeywords[$model] ?? [];

        // Check if any of the version keywords are present in the title
        foreach ($versionKeywordsRelatedModel as $version) {
            if (strpos(strtolower($title), strtolower($version)) !== false) {
                return $version;
            }
        }

        return null;
    }
}
