<?php

namespace Eshop\Services\Courier;

enum Courier: int
{
    case ACS = 1;
    case SPEEDEX = 2;
    case COURIER_CENTER = 3;
    case GENIKI = 4;
    case TCS_COURIER = 5;
    case TAS_COURIER = 6;    
    case ELTA_COURIER = 7;
    case DHL = 8;
    case TNT = 9;
    case FEDEX = 10;
    case UPS = 11;

    public function icon(): string|null
    {
        return match ($this) {
            self::ACS            => "ACS.png",
            self::SPEEDEX        => "SpeedEx.png",
            self::GENIKI         => "geniki.jpg",
            self::COURIER_CENTER => "courier-center.jpeg",
            self::DHL            => "dhl.svg",
            self::TNT            => "tnt.svg",
            self::ELTA_COURIER   => "elta-courier.png",
            self::TCS_COURIER    => "tcs.png",
            self::FEDEX          => "fedex.svg",
            self::UPS            => "ups.svg",
            self::TAS_COURIER    => "tas-courier.svg",
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
            self::TNT            => "TNT",
            self::ELTA_COURIER   => "ΕΛΤΑ Courier",
            self::TCS_COURIER    => "TCS Courier",
            self::FEDEX          => "FedEx",
            self::UPS            => "UPS",
            self::TAS_COURIER    => "TAS Courier",
        };
    }

    public function services(string $country_code): array
    {
        return (new CourierService())->shippingServices($this, $country_code);
    }
}
