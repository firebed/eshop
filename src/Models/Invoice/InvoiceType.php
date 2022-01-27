<?php

namespace Eshop\Models\Invoice;

enum InvoiceType: int
{
    case TPA = 1;
    
    case TPY = 2;
    
    case PT = 3;

    public function label(): string
    {
        return match ($this) {
            self::TPA => 'Τιμολόγιο Πώλησης Αγαθών',
            self::TPY => 'Τιμολόγιο Παροχής Υπηρεσιών',
            self::PT => 'Πιστωτικό Τιμολόγιο',
        };
    }
}