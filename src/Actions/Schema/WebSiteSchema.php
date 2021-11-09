<?php

namespace Eshop\Actions\Schema;

class WebSiteSchema
{
    public function toArray($id): array
    {
        $data = [
            "@context"        => "https://schema.org",
            "@type"           => "WebSite",
            "@id"             => config('app.url') . "/#$id",
            "url"             => config('app.url'),
            "potentialAction" => [
                "@type"       => "SearchAction",
                "target"      => url(app()->getLocale() . '/search?search_term={search_term_string}'),
                "query-input" => "required name=search_term_string"
            ]
        ];

        if (($sameAs = eshop('social')) && is_array($sameAs) && count($sameAs) > 0) {
            $data["sameAs"] = eshop('social');
        }

        return $data;
    }

    public function handle($id): string
    {
        return json_encode($this->toArray($id));
    }
}