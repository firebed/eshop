<?php

namespace Eshop\Models\Invoice;

enum InvoiceType: int
{
    case TPA = 1;

    case TPY = 2;

    case PT = 3;

    case PRO = 4;

    case PSL = 5;

    case DA = 6;

    public function label(): string
    {
        return match ($this) {
            self::TPA => 'Τιμολόγιο Πώλησης Αγαθών',
            self::TPY => 'Τιμολόγιο Παροχής Υπηρεσιών',
            self::PT  => 'Πιστωτικό Τιμολόγιο',
            self::PRO => 'Προτιμολόγιο',
            self::PSL => 'Πιστωτικό Στοιχείο Λιανικής',
            self::DA  => 'Δελτίο Αποστολής',
        };
    }
}
