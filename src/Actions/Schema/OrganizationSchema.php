<?php

namespace Eshop\Actions\Schema;

class OrganizationSchema
{
    public function handle(): string
    {
        $organization = [
            "@context"     => "https://schema.org",
            "type"         => "Organization",
            "legalName"    => __('company.name'),
            "url"          => __('company.website'),
            "logo"         => asset(config('eshop.logo')),
            "founders"     => [
                "@type" => "Person",
                "name"  => config('company.name')
            ],
            "address"      => [
                "@type"           => "PostalAddress",
                "addressLocality" => __('company.addressLocality'),
                "postalCode"      => __('company.postalCode'),
                "streetAddress"   => __('company.streetAddress')
            ],
            "contactPoint" => [
                "@type"       => "ContactPoint",
                "contactType" => "Support",
                "telephone"   => telephone(__('company.phone')[0]),
                "email"       => __('company.email')
            ]
        ];

        if (($sameAs = config('company.social')) && is_array($sameAs)) {
            $organization["sameAs"] = $sameAs;            
        }

        return json_encode($organization);
    }
}