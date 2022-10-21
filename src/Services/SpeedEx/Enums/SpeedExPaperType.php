<?php

namespace Eshop\Services\SpeedEx\Enums;

enum SpeedExPaperType: int
{
    case A4 = 1;
    case A5 = 2;
    case ENVELOPE = 3; // 10x21cm
    case A6 = 4;
}
