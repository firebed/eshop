<?php

namespace Eshop\Services\Courier;

enum Couriers: int
{
    case ACS = 1;

    case SPEEDEX = 2;

    case GENIKI = 3;

    case COURIER_CENTER = 4;

    case DHL = 5;

    case TNT = 6;

    case ELTA_COURIER = 7;

    case TCS_COURIER = 8;

    case FEDEX = 9;

    case UPS = 10;

    case TAS_COURIER = 11;

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
            self::FEDEX          => "FexEx",
            self::UPS            => "UPS",
            self::TAS_COURIER    => "TAS Courier",
        };
    }

    public function services(string $country_code): array
    {
        return match ($this) {
            self::ACS            => (new Courier())->shippingServices(self::ACS, $country_code),
            self::SPEEDEX        => [],
            self::GENIKI         => [],
            self::COURIER_CENTER => [],
            self::DHL            => [],
            self::TNT            => [],
            self::ELTA_COURIER   => [],
            self::TCS_COURIER    => [],
            self::FEDEX          => [],
            self::UPS            => [],
            self::TAS_COURIER    => [],
        };
    }
}
