<?php

namespace Eshop\Database\Seeders;

use Eshop\Models\Cart\CartStatus;
use Illuminate\Database\Seeder;

class CartStatusSeeder extends Seeder
{
    public function run(): void
    {
        CartStatus::insert([
            $this->make('submitted', true, 'primary', 'fas fa-thumbs-up', 0, true),
            $this->make('approved', false, 'info', 'fas fa-thumbs-up', 1, true),
            $this->make('completed', false, 'info', 'fas fa-check', 1, true),
            $this->make('shipped', true, 'success', 'fas fa-truck', 1, true),
            $this->make('held', false, 'warning', 'fas fa-pause', 2, true),
            $this->make('cancelled', false, 'secondary', 'fas fa-stop', 3, false),
            $this->make('rejected', false, 'secondary', 'fas fa-ban', 3, false),
            $this->make('returned', false, 'secondary', 'fas fa-undo', 3, false),
        ]);
    }

    private function make(string $name, bool $notify, string $color, string $icon, int $group, bool $capture): array
    {
        return [
            'name'            => $name,
            'notify'          => $notify,
            'color'           => $color,
            'icon'            => $icon,
            'group'           => $group,
            'stock_operation' => $capture ? 'Capture' : 'Release'
        ];
    }
}
