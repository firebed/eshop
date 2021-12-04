<?php

namespace Eshop\Actions;

class HighlightText
{
    public function handle(string $searchTerm, string $subject): string
    {
        if (blank($searchTerm) || blank($subject)) {
            return $subject;
        }
        
        $searchTerm = preg_quote($searchTerm, '/');
        
        $regex = "/\b($searchTerm)/iu"; // All the words starting with the given query, case-insensitive, utf-8
        return preg_replace($regex, '<strong>$1</strong>', $subject);
    }
}