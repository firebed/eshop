<?php

namespace Eshop\Services\Courier;

enum ContentType: int
{
    case SPARE_PARTS = 1;

    case BOOKS = 2;

    case VITAMINS_PARAPHARMACEUTICALS = 3;

    case DECORATION_ITEMS = 4;

    case BEAUTIFICATION_ITEMS = 5;

    case ELECTRONICS = 6;

    case COSMETICS = 7;

    case CELL_PHONE = 8;

    case FAUX_BIJOUX_JEWELRY = 9;

    case DRY_FOOD = 10;

    case OPTICAL = 11;

    case TOYS_GAMES = 12;

    case PERSONAL_ITEMS = 13;

    case CLOTHING = 14;

    case SHOES = 15;

    case PHARMACEUTICALS = 16;

    case DOCUMENTS_SECURITIES = 17;

    case OTHER = 18;

    public function label(): string
    {
        return match ($this) {
            self::SPARE_PARTS                  => "Ανταλλακτικά",
            self::BOOKS                        => "Βιβλία",
            self::VITAMINS_PARAPHARMACEUTICALS => "Βιταμίνες - Παραφαρμακευτικά",
            self::DECORATION_ITEMS             => "Είδη Διακόσμησης",
            self::BEAUTIFICATION_ITEMS         => "Είδη Ομορφιάς",
            self::ELECTRONICS                  => "Ηλεκτρικά",
            self::COSMETICS                    => "Καλλυντικά",
            self::CELL_PHONE                   => "Κινητά Τηλέφωνα",
            self::FAUX_BIJOUX_JEWELRY          => "Κοσμήματα Faux Bijoux",
            self::DRY_FOOD                     => "Ξηρές Τροφές",
            self::OPTICAL                      => "Οπτικά",
            self::TOYS_GAMES                   => "Παιχνίδια",
            self::PERSONAL_ITEMS               => "Προσωπικά Είδη",
            self::CLOTHING                     => "Είδη Ένδυσης",
            self::SHOES                        => "Υποδήματα",
            self::PHARMACEUTICALS              => "Φαρμακευτικά",
            self::DOCUMENTS_SECURITIES         => "Έγγραφα",
            self::OTHER                        => "Άλλο",

        };
    }
}
