<?php

namespace Eshop\Exports;

use Eshop\Models\Cart\Cart;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CartsExport implements FromCollection, WithMapping, WithHeadings
{
    public const ACCENTS = [
        'Ά' => 'Α',
        'Έ' => 'Ε',
        'Ή' => 'Η',
        'Ί' => 'Ι',
        'Ό' => 'Ο',
        'Ύ' => 'Υ',
        'Ώ' => 'Ω',
    ];

    private array $ids;

    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return Cart::with('paymentMethod', 'shippingAddress')->findMany($this->ids);
    }

    public function map($row): array
    {
        return [
            $this->removeAccents($row->shippingAddress->fullName), #ΟΝΟΜΑ ΠΑΡΑΛΗΠΤΗ
            '', #ΕΠΩΝΥΜΙΑ ΕΤΑΙΡΙΑΣ
            $this->removeAccents($row->shippingAddress->region . (!empty($row->shippingAddress->region) ? ' - ' : '') . $row->shippingAddress->city), #ΠΕΡΙΟΧΗ
            $this->removeAccents($row->shippingAddress->street), #ΟΔΟΣ
            $this->removeAccents($row->shippingAddress->street_no), #ΑΡΙΘΜΟΣ
            $row->floor ?? '', #ΟΡΟΦΟΣ
            $this->removeAccents($row->shippingAddress->postcode), #ΤΚ
            $row->email, #EMAIL ΠΑΡΑΛΗΠΤΗ
            '', #ΤΗΛΕΦΩΝΟ
            $this->removeAccents($row->shippingAddress->phone), #ΚΙΝΗΤΟ
            '', #ΥΠΟΚΑΤΑΣΤΗΜΑ
            $this->removeAccents($row->details), #ΠΑΡΑΤΗΡΗΣΕΙΣ
            "2", #ΧΡΕΩΣΗ
            1, #ΤΕΜΑΧΙΑ
            $row->parcel_weight / 1000, #ΒΑΡΟΣ
            $row->paymentMethod->show_total_on_order_form ? $row->total : 0, #ΑΝΤΙΚΑΤΑΒΟΛΗ
            'Μ', #ΤΡΟΠΟΣ ΠΛΗΡΩΜΗΣ
            '', #ΑΣΦΑΛΕΙΑ
            '', #ΚΕΝΤΡΟ ΚΟΣΤΟΥΣ
            '', #ΣΧΕΤΙΚΟ1
            '', #ΣΧΕΤΙΚΟ2
            '', #ΩΡΑ ΠΑΡΑΔΟΣΗΣ
            '', #ΠΡΟΙΟΝΤΑ
        ];
    }

    public function headings(): array
    {
        return [
            'ΟΝΟΜΑ ΠΑΡΑΛΗΠΤΗ',
            'ΕΠΩΝΥΜΙΑ ΕΤΑΙΡΙΑΣ',
            'ΠΕΡΙΟΧΗ',
            'ΟΔΟΣ',
            'ΑΡΙΘΜΟΣ',
            'ΟΡΟΦΟΣ',
            'ΤΚ',
            'EMAIL ΠΑΡΑΛΗΠΤΗ',
            'ΤΗΛΕΦΩΝΟ',
            'ΚΙΝΗΤΟ',
            'ΥΠΟΚΑΤΑΣΤΗΜΑ',
            'ΠΑΡΑΤΗΡΗΣΕΙΣ',
            'ΧΡΕΩΣΗ',
            'ΤΕΜΑΧΙΑ',
            'ΒΑΡΟΣ',
            'ΑΝΤΙΚΑΤΑΒΟΛΗ',
            'ΤΡΟΠΟΣ ΠΛΗΡΩΜΗΣ',
            'ΑΣΦΑΛΕΙΑ',
            'ΚΕΝΤΡΟ ΚΟΣΤΟΥΣ',
            'ΣΧΕΤΙΚΟ1',
            'ΣΧΕΤΙΚΟ2',
            'ΩΡΑ ΠΑΡΑΔΟΣΗΣ',
            'ΠΡΟΙΟΝΤΑ',
        ];
    }

    private function removeAccents($string)
    {
        return str_replace(array_keys(self::ACCENTS), array_values(self::ACCENTS), mb_strtoupper($string));
    }
}
