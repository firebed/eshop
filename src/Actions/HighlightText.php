<?php

namespace Eshop\Actions;

class HighlightText
{
    public function handle(string $text, string $searchTerm): string
    {
        if (blank($searchTerm) || blank($text)) {
            return $text;
        }
        
        $regex = "/\b($searchTerm)/iu"; // All the words starting with the given query, case-insensitive, utf-8
        return preg_replace($regex, '<strong>$1</strong>', $text);
    }
}