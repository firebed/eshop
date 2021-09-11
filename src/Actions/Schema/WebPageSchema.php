<?php

namespace Eshop\Actions\Schema;

class WebPageSchema
{
    public function handle(string $name, string $description = null): string
    {
        $webPage = [
            "@context"    => "http://schema.org",
            "@type"       => "WebPage",
            "name"        => $name,
        ];

        if (!empty($description)) {
            $webPage["description"] = $description;
        }

        return json_encode($webPage);
    }
}