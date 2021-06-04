<?php

namespace Database\Seeders\Live;

use App\Models\Cart\Cart;
use App\Models\Cart\CartStatus;
use App\Models\Cart\DocumentType;
use App\Models\Invoice\Invoice;
use App\Models\Location\Address;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->seedStatuses();
        $map = $this->seedCartsTable();
        $this->seedShippingAddress($map);
        $this->seedInvoices($map);
        $this->seedCartProducts($map);
    }

    private function seedCartsTable(): array
    {
        $users = User::all()->keyBy('old_id');
        $contacts = DB::connection('live')->table('dlvr_contact')->get()->keyBy('cart');

        $data = DB::connection('live')->table('dlvr_cart')
            ->whereNotIn('id', [8670, 27914, 7058, 24078, 16678])
            ->where(function ($q) {
                $q->whereNotNull('submitted');
                $q->orWhere(function ($b) {
                    $b->whereNull('submitted');
                    $b->where('user', '!=', 0);
                });
            })
            ->get();

        $invoices = DB::connection('live')->table('dlvr_invoice')
            ->where('invoice', 1)
            ->get()
            ->keyBy('cart');

        $carts = [];
        $map = [];
        foreach ($data as $i => $cart) {

            // Ignore carts that don't have contact
            if (!isset($contacts[$cart->id])) {
                continue;
            }

            $contact = $contacts[$cart->id];

            $carts[] = [
                'user_id'            => empty($cart->user) ? NULL : $users[$cart->user]->id,
                'status_id'          => $this->getStatus($cart->state),
                'payment_method_id'  => empty($cart->payment) ? NULL : ($cart->payment == 1 ? 4 : ($cart->payment == 2 ? 1 : 3)),
                'shipping_method_id' => empty($cart->shipment) ? NULL : (in_array($cart->shipment, [1, 2]) ? 1 : (in_array($cart->shipment, [3, 4]) ? 2 : 3)),
                'payment_fee'        => $cart->payment == 1 ? 2 : 0,
                'shipping_fee'       => in_array($cart->shipment, [1, 3]) ? 2 : ($cart->shipment == 5 ? 1.7 : 0),
                'document_type'      => isset($invoices[$cart->id]) ? DocumentType::INVOICE : DocumentType::RECEIPT,
                'total'              => $cart->total,
                'details'            => empty(trim($cart->details)) ? NULL : trim($cart->details),
                'created_at'         => $cart->initialized,
                'email'              => empty(trim($contact->email)) ? "crochetarian79@gmail.com" : trim($contact->email),
                'submitted_at'       => $cart->submitted,
                'viewed_at'          => empty($cart->submitted) ? NULL : now()
            ];

            $map[$cart->id] = count($carts);
        }

        collect($carts)->chunk(3500)->each(fn($chunk) => Cart::insert($chunk->toArray()));
        return $map;
    }

    private function seedInvoices($map): void
    {
        $data = DB::connection('live')->table('dlvr_invoice')
            ->where('invoice', 1)
            ->get();

        $invoices = [];
        $addresses = [];
        foreach ($data as $i => $invoice) {
            if (isset($map[$invoice->cart])) {
                $invoices[] = [
                    'billable_id'   => $map[$invoice->cart],
                    'billable_type' => 'cart',
                    'name'          => trim($invoice->name),
                    'job'           => trim($invoice->job),
                    'vat_number'    => trim($invoice->vat_number),
                    'tax_authority' => trim($invoice->tax_office),
                ];

                $addresses[] = [
                    'addressable_id'   => count($invoices),
                    'addressable_type' => 'invoice',
                    'country_id'       => 1,
                    'province'         => empty(trim($invoice->region)) ? NULL : trim($invoice->region),
                    'city'             => empty(trim($invoice->city)) ? NULL : trim($invoice->city),
                    'street'           => trim($invoice->street),
                    'street_no'        => trim($invoice->street_no),
                    'postcode'         => trim($invoice->postal_code),
                ];
            }
        }
        Invoice::insert($invoices);
        Address::insert($addresses);
    }

    private function seedCartProducts($map)
    {
        $product_ids = DB::table('products')->get(['id', 'old_id'])->keyBy('old_id');
        $data = DB::connection('live')->table('dlvr_basket')
            ->whereIn('cart', array_keys($map))
            ->oldest('placement_time')
            ->get();

        $items = [];
        foreach ($data as $item) {
            if (!isset($map[$item->cart])) {
                continue;
            }

            if (!empty($item->product) && isset($product_ids[$item->product])) {
                $items[] = [
                    'cart_id'    => $map[$item->cart],
                    'product_id' => $product_ids[$item->product]->id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->price,
                    'vat'        => 0.24,
                    'discount'   => $item->discount / 100,
                    'created_at' => $item->placement_time,
                    'updated_at' => $item->placement_time,
                    'deleted_at' => $item->include ? NULL : now(),
                ];
            }
        }
        $chunks = array_chunk($items, 5000);
        foreach ($chunks as $chunk) {
            DB::table('cart_product')->insert($chunk);
        }
    }

    private function seedShippingAddress($map): void
    {
        $contacts = DB::connection('live')->table('dlvr_contact')
            ->whereIn('cart', array_keys($map))
            ->get()
            ->keyBy('cart');

        $data = DB::connection('live')->table('dlvr_address')
            ->whereIn('cart', array_keys($map))
            ->get();

        $addresses = [];
        foreach ($data as $address) {
            if (!isset($map[$address->cart])) {
                continue;
            }

            $contact = $contacts[$address->cart];
            if (!empty(trim($address->street))) {
                $addresses[] = [
                    'addressable_id'   => $map[$address->cart],
                    'addressable_type' => 'cart',
                    'cluster'          => 'shipping',
                    'country_id'       => 1,
                    'first_name'       => $contact->first_name,
                    'last_name'        => $contact->last_name,
                    'phone'            => $contact->phone,
                    'province'         => empty(trim($address->region)) ? NULL : trim($address->region),
                    'city'             => empty(trim($address->city)) ? NULL : trim($address->city),
                    'street'           => trim($address->street),
                    'street_no'        => trim($address->street_no),
                    'postcode'         => trim($address->postal_code),
                ];
            }
        }
        collect($addresses)->chunk(3000)->each(fn($chunk) => Address::insert($chunk->toArray()));
    }

    private function getStatus($state): ?int
    {
        switch ($state) {
            case 0:
                return NULL;
            case 1:
            case 2:
                return $state;
            default:
                return $state - 1;
        }
    }

    private function seedStatuses(): void
    {
        $data = DB::connection('plexoudes')->table('cart_statuses')->get()->map(function ($status, $i) {
            $status->stock_operation = $i < 5 ? CartStatus::CAPTURE : CartStatus::RELEASE;
            $status->color = $i >= 5 ? 'secondary' : $status->color;
            return (array)$status;
        });

        CartStatus::insert($data->toArray());
    }
}
