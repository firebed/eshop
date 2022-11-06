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
}
