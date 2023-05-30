<?php

namespace Eshop\Services\Courier;

enum Courier: string
{
    case ACS            = "acs";
    case SPEEDEX        = "speedex";
    case COURIER_CENTER = "courier_center";
    case GENIKI         = "geniki";
    case DHL            = "dhl";

    public function icon(): string|null
    {
        return match ($this) {
            self::ACS            => "ACS.png",
            self::SPEEDEX        => "SpeedEx.png",
            self::GENIKI         => "geniki.jpg",
            self::COURIER_CENTER => "courier-center.jpeg",
            self::DHL            => "dhl.svg",
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::ACS            => "ACS Courier",
            self::SPEEDEX        => "SpeedEx",
            self::GENIKI         => "Γενική Ταχυδρομική",
            self::COURIER_CENTER => "Courier Center",
            self::DHL            => "DHL",
        };
    }

    public function services(string $country_code): array
    {
        return (new CourierService())->shippingServices($this, $country_code);
    }
}
