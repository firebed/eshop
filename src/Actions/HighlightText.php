<?php

namespace Eshop\Actions;

class HighlightText
{
    public function handle(string $searchTerm, string $subject, bool $mark = false): string
    {
        if (blank($searchTerm) || blank($subject)) {
            return $subject;
        }

        $keywords = array_filter(explode(' ', $searchTerm));
        array_walk($keywords, static fn(&$k) => $k = "/\b(" . preg_quote($k, '/') . ")/iu");

        $markup = $mark
            ? '<span class="bg-amber-400">$1</span>'
            : '<strong>$1</strong>';

        return preg_replace($keywords, $markup, $subject);
    }
}