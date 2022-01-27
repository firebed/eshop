<?php

namespace Eshop\Models\Invoice;

enum PaymentMethod: int
{
    case Cash = 1;

    case Credit = 2;

    case Check = 3;

    case POD = 4;

    case CreditCard = 5;

    case WireTransfer = 6;

    case PayPal = 7;

    public function label(): string
    {
        return match ($this) {
            self::Cash => 'Μετρητοίς',
            self::Credit => 'Επι πιστώσει',
            self::Check => 'Επιταγή',
            self::POD => 'Αντικαταβολή',
            self::CreditCard => 'Πιστωτική κάρτα',
            self::WireTransfer => 'Κατάθεση',
            self::PayPal => 'PayPal'
        };
    }
}