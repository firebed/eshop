<?php

namespace Database\Seeders\Live;

use App\Models\Location\Country;
use App\Models\Location\PaymentMethod;
use App\Models\Location\ShippingMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $data = DB::connection('plexoudes')->table('countries')->get()->map(fn($t) => (array)$t);
        Country::insert($data->toArray());

//        $this->call(Greece::class);

        $this->seedPaymentMethods();
        $this->seedShippingMethods();

        $greece = Country::find(1);
        $greece->paymentMethods()->sync([
            1 => ['visible' => FALSE],
            2 => ['visible' => FALSE],
            3 => ['visible' => FALSE],
            4 => ['fee' => 2],
            5 => ['visible' => FALSE]
        ]);

        $greece->shippingMethods()->attach([1 => ['fee' => 2, 'cart_total' => 0, 'position' => 1, 'visible' => FALSE]]);
        $greece->shippingMethods()->attach([1 => ['fee' => 0, 'cart_total' => 50, 'position' => 2, 'visible' => FALSE]]);
        $greece->shippingMethods()->attach([2 => ['fee' => 2, 'cart_total' => 0, 'position' => 3, 'visible' => FALSE]]);
        $greece->shippingMethods()->attach([2 => ['fee' => 0, 'cart_total' => 50, 'position' => 4, 'visible' => FALSE]]);
        $greece->shippingMethods()->attach([3 => ['fee' => 2, 'cart_total' => 0, 'position' => 5]]);
        $greece->shippingMethods()->attach([3 => ['fee' => 1, 'cart_total' => 30, 'position' => 6]]);
        $greece->shippingMethods()->attach([3 => ['fee' => 0, 'cart_total' => 50, 'position' => 7]]);
    }

    private function seedPaymentMethods(): void
    {
        PaymentMethod::insert([
            ['name' => 'PayPal', 'show_total_on_order_form' => FALSE],
            ['name' => 'Credit Card', 'show_total_on_order_form' => FALSE],
            ['name' => 'Bank Transfer', 'show_total_on_order_form' => FALSE],
            ['name' => 'Pay on Delivery', 'show_total_on_order_form' => TRUE],
            ['name' => 'Payment in our store', 'show_total_on_order_form' => FALSE],
        ]);
    }

    private function seedShippingMethods(): void
    {
        ShippingMethod::insert([
            ['name' => 'ACS Courier', 'tracking_url' => 'https://www.acscourier.net/el/web/greece/track-and-trace?p_p_id=ACSCustomersAreaTrackTrace_WAR_ACSCustomersAreaportlet&action=getTracking&cid=2%CE%9E%CE%9D946573&generalCode={$tracking}'],
            ['name' => 'SpeedEx', 'tracking_url' => 'http://www.speedex.gr/isapohi.asp?voucher_code={$tracking}&pointgo=go'],
            ['name' => 'Geniki Taxydromiki', 'tracking_url' => 'https://www.taxydromiki.com/en/track/{$tracking}'],
            ['name' => 'Collect from our store', 'tracking_url' => NULL],
            ['name' => 'ELTA', 'tracking_url' => 'https://www.elta.gr/el-gr/tabid/93/?qc={$tracking}'],
        ]);
    }
}
