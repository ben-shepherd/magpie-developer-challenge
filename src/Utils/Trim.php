<?php

namespace App\Utils;

class Trim
{
    /**
     * Trims a string and removes new lines and multiple spaces
     * 
     * @param string|null $text The string to trim
     * @return string|null The trimmed string or null if the input is null
     */
    public static function trimEmptySpacesAndNewLines(string|null $text): string | null
    {
        if($text === null) {
            return null;
        }

        $trimmed = str_replace("\n", "", $text);
        $trimmed = trim(preg_replace('/\s\s+/', ' ', $trimmed));
        
        return $trimmed;
    }
}