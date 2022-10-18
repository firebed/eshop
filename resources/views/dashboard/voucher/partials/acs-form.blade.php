@php($key = "carts.$cart_id")

<div class="d-flex flex-column gap-4">
    <div class="vstack gap-1">
        <div class="fw-bold mb-3">Στοιχεία χρέωσης</div>

        <x-bs::input.group for="cart-{{ $cart_id }}-courier" label="Courier" inline>
            <x-bs::input.text id="cart-{{ $cart_id }}-courier" value="ACS" readonly/>
        </x-bs::input.group>

        <x-bs::input.group for="cart-{{ $cart_id }}-billing-code" label="Κωδικός Χρέωσης" inline>
            <x-bs::input.select name="carts[{{ $cart_id }}][billing_code]" error="{{ $key }}.billing_code" placeholder="Κωδικός Χρέωσης" id="cart-{{ $cart_id }}-billing-code">
                @foreach($billingCodes as $country_code => $billing_code)
                    <option value="{{ $billing_code }}" {{ (old("$key.billing_code") === $billing_code || (isset($billingCodes[$cart->shippingAddress->country->code]) && $billingCodes[$cart->shippingAddress->country->code] === $country_code)) ? 'selected' : '' }}>{{ $billing_code }}
                        ({{ $country_code }})
                    </option>
                @endforeach
            </x-bs::input.select>
        </x-bs::input.group>

        <x-bs::input.group for="cart-{{ $cart_id }}-charge-type" label="Τύπος χρέωσης" inline>
            <x-bs::input.select name="carts[{{ $cart_id }}][charge_type]" error="{{ $key }}.charge_type" id="cart-{{ $cart_id }}-charge-type">
                <option value="2" {{ old("$key.charge_type") == 2 ? 'selected' : '' }}>Χρέωση αποστολέα</option>
                <option value="4" {{ old("$key.charge_type") == 4 ? 'selected' : '' }}>Χρέωση παραλήπτη</option>
            </x-bs::input.select>
        </x-bs::input.group>

        <x-bs::input.group for="cart-{{ $cart_id }}-sender" label="Αποστολέας" inline>
            <x-bs::input.text :value="old($key.'.sender', config('app.name'))" name="carts[{{ $cart_id }}][sender]" error="{{ $key }}.sender" placeholder="Αποστολέας" id="cart-{{ $cart_id }}-sender"/>
        </x-bs::input.group>
    </div>

    <div class="vstack gap-1">
        <div class="fw-bold mb-3">Στοιχεία παραλαβής</div>

        <x-bs::input.group for="cart-{{ $cart_id }}-pickup-date" label="Ημερομηνία παραλαβής" inline>
            <x-bs::input.date :value="old($key.'pickup_date', today())->format('d/m/Y')" name="carts[{{ $cart_id }}][pickup_date]" error="{{ $key }}.pickup_date" placeholder="Ημερομηνία παραλαβής" id="cart-{{ $cart_id }}-pickup-date"/>
        </x-bs::input.group>

        <x-bs::input.group for="cart-{{ $cart_id }}-station" label="Κωδικός καταστήματος" inline>
            <div class="d-flex gap-2">
                <x-bs::input.text :value="old($key.'.station_id')" name="carts[{{ $cart_id }}][station_id]" error="{{ $key }}.station_id" id="cart-{{ $cart_id }}-station-id" readonly required style="width: 3rem"/>
                <x-bs::input.text :value="old($key.'.station')" name="carts[{{ $cart_id }}][station]" error="{{ $key }}.station" id="cart-{{ $cart_id }}-station" readonly required/>
                <x-bs::button.primary data-bs-toggle="modal" data-bs-target="#search-area" data-cart="{{ $cart_id }}" data-postcode="{{ old($key.'.postcode', $cart->shippingAddress->postcode) }}" class="shadow-none text-dark"><i class="fas fa-search text-light"></i></x-bs::button.primary>
            </div>
        </x-bs::input.group>

        <x-bs::input.group for="cart-{{ $cart_id }}-weight" label="Βάρος" inline>
            <x-bs::input.text :value="old($key.'.weight', max($cart->parcel_weight / 1000, 0.5))" name="carts[{{ $cart_id }}][weight]" error="{{ $key }}.weight" placeholder="Βάρος" id="cart-{{ $cart_id }}-weight"/>
        </x-bs::input.group>

        <x-bs::input.group for="cart-{{ $cart_id }}-cod-amount" label="Ποσό αντικαταβολής" inline>
            <x-bs::input.text :value="old($key.'.cod_amount', $cart->total)" name="carts[{{ $cart_id }}][cod_amount]" error="{{ $key }}.cod_amount" placeholder="Ποσό αντικαταβολής" id="cart-{{ $cart_id }}-cod-amount" class="form-control" readonly/>
        </x-bs::input.group>
    </div>
    
    <div class="vstack gap-1">
        <div class="fw-bold mb-3">Στοιχεία παραλήπτη</div>

        <x-bs::input.group for="cart-{{ $cart_id }}-recipient" label="Παραλήπτης" inline>
            <x-bs::input.text :value="old($key.'.recipient_name', $cart->shippingAddress->fullname)" name="carts[{{ $cart_id }}][recipient_name]" error="{{ $key }}.recipient_name" placeholder="Παραλήπτης" id="cart-{{ $cart_id }}-recipient"/>
        </x-bs::input.group>

        <x-bs::input.group for="cart-{{ $cart_id }}-recipient-email" label="Email" inline>
            <x-bs::input.text :value="old($key.'.recipient_email', $cart->email)" name="carts[{{ $cart_id }}][recipient_email]" error="{{ $key }}.recipient_email" readonly id="cart-{{ $cart_id }}-recipient-email"/>
        </x-bs::input.group>

        <x-bs::input.group for="cart-{{ $cart_id }}-recipient-mobile" label="Κινητό τηλέφωνο" inline>
            <x-bs::input.text :value="old($key.'.recipient_mobile', $cart->shippingAddress->phone)" name="carts[{{ $cart_id }}][recipient_mobile]" error="{{ $key }}.recipient_mobile" placeholder="Κινητό τηλέφωνο" id="cart-{{ $cart_id }}-recipient-mobile"/>
        </x-bs::input.group>

        <x-bs::input.group for="cart-{{ $cart_id }}-recipient-phone" label="Σταθερό τηλέφωνο" inline>
            <x-bs::input.text :value="old($key.'.recipient_phone')" name="carts[{{ $cart_id }}][recipient_phone]" error="{{ $key }}.recipient_phone" placeholder="Σταθερό τηλέφωνο" id="cart-{{ $cart_id }}-recipient-phone"/>
        </x-bs::input.group>

        <x-bs::input.group for="cart-{{ $cart_id }}-notes" label="Σημειώσεις" inline>
            <x-bs::input.textarea name="carts[{{ $cart_id }}][notes]" error="{{ $key }}.notes" placeholder="Σημειώσεις" id="cart-{{ $cart_id }}-delivery-notes" rows="3">{{ old("$key.notes", $cart->details) }}</x-bs::input.textarea>
        </x-bs::input.group>
    </div>

    <div class="vstack gap-1">
        <div class="fw-bold mb-3">Διεύθυνση παράδοσης</div>

        <x-bs::input.group for="cart-{{ $cart_id }}-street" label="Οδός" inline>
            <x-bs::input.text :value="old($key.'.street', $cart->shippingAddress->street)" name="carts[{{ $cart_id }}][street]" error="{{ $key }}.street" placeholder="Οδός" id="cart-{{ $cart_id }}-street"/>
        </x-bs::input.group>

        <x-bs::input.group for="cart-{{ $cart_id }}-street_no" label="Αριθμός" inline>
            <x-bs::input.text :value="old($key.'.street_no', $cart->shippingAddress->street_no)" name="carts[{{ $cart_id }}][street_no]" error="{{ $key }}.street_no" placeholder="Αριθμός" id="cart-{{ $cart_id }}-recipient-street_no"/>
        </x-bs::input.group>

        <x-bs::input.group for="cart-{{ $cart_id }}-floor" label="Όροφος" inline>
            <x-bs::input.text :value="old($key.'.floor')" name="carts[{{ $cart_id }}][floor]" error="{{ $key }}.floor" placeholder="Όροφος" id="cart-{{ $cart_id }}-floor"/>
        </x-bs::input.group>

        <x-bs::input.group for="cart-{{ $cart_id }}-postcode" label="Ταχυδρομικός κώδικας" inline>
            <x-bs::input.text :value="old($key.'.postcode', $cart->shippingAddress->postcode)" name="carts[{{ $cart_id }}][postcode]" error="{{ $key }}.postcode" placeholder="Ταχυδρομικός κώδικας" id="cart-{{ $cart_id }}-postcode"/>
        </x-bs::input.group>

        <x-bs::input.group for="cart-{{ $cart_id }}-region" label="Περιοχή" inline>
            <x-bs::input.text :value="old($key.'.region', $cart->shippingAddress->region)" name="carts[{{ $cart_id }}][region]" error="{{ $key }}.region" placeholder="Περιοχή" id="cart-{{ $cart_id }}-region"/>
        </x-bs::input.group>

        <x-bs::input.group for="cart-{{ $cart_id }}-country" label="Χώρα" inline>
            <x-bs::input.text :value="old($key.'.country', $cart->shippingAddress->country->code)" name="carts[{{ $cart_id }}][country]" error="{{ $key }}.country" readonly id="cart-{{ $cart_id }}-country"/>
        </x-bs::input.group>
    </div>
</div>
