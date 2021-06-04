<?php

namespace Database\Seeders\Live;

use App\Models\Invoice\Company;
use App\Models\Location\Address;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->seedUsers();
        $this->seedShippingAddresses();
        $this->seedCompanies();

        $me = User::find(1);
        $me->assignRole(Role::create(['name' => 'Super Admin']));
    }

    private function seedUsers(): void
    {
        $data = DB::connection('live')->table('users')->get();
        $users = [];
        foreach ($data as $user) {
            $u = [
                'old_id'     => $user->id,
                'first_name' => trim($user->first_name),
                'last_name'  => trim($user->last_name),
                'email'      => trim($user->email),
                'created_at' => $user->registration
            ];

            $u['password'] = $user->email === 'okan.giritli@gmail.com'
                ? Hash::make('Ly6adywe')
                : trim($user->password);

            $users[] = $u;
        }
        User::insert($users);
    }

    private function seedShippingAddresses(): void
    {
        $data = DB::connection('live')->table('user_addresses')->get();
        $live_users = DB::connection('live')->table('users')->get()->keyBy('id');
        $users = User::all()->keyBy('old_id');
        $addresses = [];
        foreach ($data as $address) {
            if (empty($address->city)) {
                continue;
            }

            $addresses[] = [
                'addressable_id'   => $users[$address->user]->id,
                'addressable_type' => 'user',
                'cluster'          => 'shipping',
                'country_id'       => 1,
                'first_name'       => $users[$address->user]->first_name,
                'last_name'        => $users[$address->user]->last_name,
                'phone'            => $live_users[$address->user]->phone,
                'province'         => (empty(trim($address->region)) ? null : trim($address->region)),
                'city'             => (empty(trim($address->city)) ? null : trim($address->city)),
                'street'           => trim($address->street),
                'street_no'        => trim($address->street_no),
                'postcode'         => trim($address->postal_code),
            ];
        }
        Address::insert($addresses);
    }

    private function seedCompanies(): void
    {
        $data = DB::connection('live')->table('user_invoices')->get()->unique('vat_number');
        $users = User::all()->keyBy('old_id');
        $companies = [];
        $addresses = [];
        foreach ($data as $company) {
            if (empty($company->vat_number) || empty($company->job) || empty($company->name)) {
                continue;
            }

            $companies[] = [
                'user_id'       => $users[$company->user]->id,
                'name'          => $company->name,
                'job'           => $company->job,
                'vat_number'    => $company->vat_number,
                'tax_authority' => $company->tax_office,
            ];

            $addresses[] = [
                'addressable_id'   => count($companies),
                'addressable_type' => 'company',
                'province'         => (empty(trim($company->region)) ? null : trim($company->region)),
                'city'             => (empty(trim($company->city)) ? null : trim($company->city)),
                'street'           => trim($company->street),
                'street_no'        => trim($company->street_no),
                'postcode'         => trim($company->postal_code),
            ];
        }

        Company::insert($companies);
        Address::insert($addresses);
    }
}
