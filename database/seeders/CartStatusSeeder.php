<?php

namespace Eshop\Database\Seeders;

use Eshop\Models\Cart\CartStatus;
use Illuminate\Database\Seeder;

class CartStatusSeeder extends Seeder
{
    public function run(): void
    {
        CartStatus::factory()->name('submitted')->notify()->color('primary')->icon('fas fa-thumbs-up')->group(0)->capture();
        CartStatus::factory()->name('approved')->notify(FALSE)->color('info')->icon('fas fa-thumbs-up')->group(1)->capture();
        CartStatus::factory()->name('completed')->notify(FALSE)->color('info')->icon('fas fa-check')->group(1)->capture();
        CartStatus::factory()->name('shipped')->notify()->color('success')->icon('fas fa-truck')->group(1)->capture();
        CartStatus::factory()->name('held')->notify(FALSE)->color('warning')->icon('fas fa-pause')->group(2)->capture();
        CartStatus::factory()->name('cancelled')->notify(FALSE)->color('secondary')->icon('fas fa-stop')->group(3)->release();
        CartStatus::factory()->name('rejected')->notify(FALSE)->color('secondary')->icon('fas fa-ban')->group(3)->release();
        CartStatus::factory()->name('returned')->notify(FALSE)->color('secondary')->icon('fas fa-undo')->group(3)->release();
    }
}
