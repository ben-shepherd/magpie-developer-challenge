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
            'iphone' => ['iphone'],
            'samsung' => ['samsung', 'galaxy'],
            'huawei' => ['hua', 'huawei'],
            'nokia' => ['nokia'],
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
        // Define version keywords for each phone model
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
            ]
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
