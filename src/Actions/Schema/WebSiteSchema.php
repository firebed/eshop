<?php

namespace Eshop\Actions\Schema;

class WebSiteSchema
{
    public function toArray($id): array
    {
        return [
            "@context"        => "https://schema.org",
            "@type"           => "WebSite",
            "@id"             => config('app.url') . "/#$id",
            "url"             => config('app.url'),
            "sameAs"          => __('company.social'),
            "potentialAction" => [
                "@type"       => "SearchAction",
                "target"      => url(app()->getLocale() . '/search?search_term={search_term_string}'),
                "query-input" => "required name=search_term_string"
            ]
        ];
    }

    public function handle($id): string
    {
        return json_encode($this->toArray($id));
    }
}