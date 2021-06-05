<?php

namespace Eshop\Exports;

use Eshop\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithMapping, WithHeadings
{
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
        return User::whereKey($this->ids)
            ->withCount(['carts' => fn($q) => $q->submitted()])
            ->withSum(['carts' => fn($q) => $q->submitted()], 'total')
            ->get();
    }

    /**
     * @param User $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->first_name,
            $row->last_name,
            $row->email,
            $row->created_at->format('d/m/y H:i:s'),
            optional($row->last_login_at)->format('d/m/y H:i:s'),
            $row->carts_count,
            $row->carts_sum_total,
        ];
    }

    public function headings(): array
    {
        return [
            __('ID'),
            __('First name'),
            __('Last name'),
            __('Email'),
            __('Created at'),
            __('Last login at'),
            __('Orders count'),
            __('Orders total'),
        ];
    }
}
