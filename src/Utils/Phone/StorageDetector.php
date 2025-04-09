<?php

namespace App\Utils\Phone;

class StorageDetector
{
    public static function extractStorageString(string $text): string | null
    {
        $pattern = "/([\d]+\s?(?:GB|MB))/";

        if (preg_match($pattern, $text, $matches)) {
            return $matches[1];
        }

        return null;
    }

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