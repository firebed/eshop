<?php

namespace Eshop\Actions;

class HighlightText
{
    public function handle(string $searchTerm, string $subject, bool $mark = false): string
    {
        if (blank($searchTerm) || blank($subject)) {
            return $subject;
        }
        
        $searchTerm = preg_quote($searchTerm, '/');
        
        $markup = $mark
            ? '<span class="bg-yellow-400">$1</span>'
            : '<strong>$1</strong>';
        
        $regex = "/\b($searchTerm)/iu"; // All the words starting with the given query, case-insensitive, utf-8
        return preg_replace($regex, $markup, $subject);
    }
}