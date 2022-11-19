<?php

namespace Eshop\Services\Courier;

enum AcsServices: string
{
    case INS = 'Ασφάλεια Αποστολής *';
    case SAT = 'Παράδοση Σάββατο *';
    case MDD = 'Πρωινή Παράδοση *';
    case TDD = '2ωρη Δέσμευση Ώρας *';
    case COD = 'Αντικαταβολή';
    case RDO = 'Επιστροφή Δικαιολογητικών';
    case REM = 'Δυσπρόσιτη Περιοχή *';
    case PRO = 'Παραλαβή Πρωτοκόλλου *';
    case REC = 'Παράδοση Reception';
    case CEC = "Cyprus Economy";
    case P2P = "Point to Point";
    case D2P = "Door to Point";
    case P2D = "Point to Door";
}
