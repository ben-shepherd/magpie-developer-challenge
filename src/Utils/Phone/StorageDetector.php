<?php

namespace App\Utils\Phone;

/**
 * Class StorageDetector
 * 
 * Utility class for detecting and extracting storage information from text.
 */
class StorageDetector
{
    /**
     * Extracts a storage string (e.g., "128GB" or "512MB") from the given text.
     *
     * @param string $text The text to extract storage information from
     * @return string|null The extracted storage string or null if not found
     */
    public static function extractStorageString(string $text): string | null
    {
        $pattern = "/([\d]+\s?(?:GB|MB))/";

        if (preg_match($pattern, $text, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Extracts storage information from text and converts it to megabytes.
     *
     * @param string $text The text to extract storage information from
     * @return int|null The storage value in megabytes or null if not found
     */
    public static function extractStorageMegabytes(string $text): int | null
    {
        $storageString = self::extractStorageString($text);

        if ($storageString === null) {
            return null;
        }

        // Assumed it's already in MB
        $storageMbInt = (int) $storageString;

        // If it's GB, convert to MB
        if(strpos(strtolower($storageString), 'gb') !== false) {
            return $storageMbInt * 1024;
        }

        return floor($storageMbInt * 1024);
    }
}