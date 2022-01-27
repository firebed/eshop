<?php

namespace Eshop\Models\Invoice;

enum UnitMeasurement: int
{
    case Pieces = 1;

    case Kilos = 2;

    case Liters = 3;

    case Set = 4;

    case Meters = 5;

    public function label(): string
    {
        return match ($this) {
            self::Pieces => 'Τεμάχια',
            self::Kilos => 'Κιλά',
            self::Liters => 'Λίτρα',
            self::Set => 'Σετ',
            self::Meters => 'Μέτρα',
        };
    }

    public function abbr(): string
    {
        return match ($this) {
            self::Pieces => 'ΤΜΧ',
            self::Kilos => 'ΚΙΛ',
            self::Liters => 'ΛΙΤ',
            self::Set => 'ΣΕΤ',
            self::Meters => 'ΜΕΤ',
        };
    }
}