<form x-data="{ icons: @js($icons), courier: @entangle('voucher.courier') }" wire:submit.prevent="purchaseVoucher()">
    <x-bs::modal size="xl" wire:model.defer="showBuyVoucherModal">
        <x-bs::modal.header>Αγορά Voucher</x-bs::modal.header>

        <x-bs::modal.body class="d-flex bg-gray-100 gap-3">
            <div class="col-8 d-grid gap-2">
                <x-bs::input.group label="Μεταφορική" for="couriers" inline>
                    <x-bs::input.select x-model="courier" id="couriers">
                        @foreach(\Eshop\Services\Courier\Couriers::cases() as $c)
                            <option value="{{ $c->value }}">{{ $c->label() }}</option>
                        @endforeach
                    </x-bs::input.select>
                </x-bs::input.group>

                <x-bs::input.group label="Αριθμός τεμαχίων" for="items" inline>
                    <x-bs::input.integer wire:model.defer="voucher.number_of_packages" id="items" class="w-25"/>
                </x-bs::input.group>

                <x-bs::input.group label="Βάρος" for="weight" inline>
                    <x-bs::input.weight wire:model.defer="voucher.weight" id="weight" unit="kg" decimal-places="2" class="w-25"/>
                </x-bs::input.group>

                <x-bs::input.group label="Αντικαταβολή" for="cod" inline>
                    <x-bs::input.money wire:model.defer="voucher.cod_amount" id="cod" class="w-25"/>
                </x-bs::input.group>

                <x-bs::input.group label="Αποστολέας" for="sender" inline>
                    <x-bs::input.text wire:model.defer="voucher.sender" id="sender"/>
                </x-bs::input.group>

                <x-bs::input.group label="Παραλήπτης" for="customer-name" inline>
                    <x-bs::input.text wire:model.defer="voucher.customer_name" id="customer-name"/>
                </x-bs::input.group>

                <x-bs::input.group label="Τηλέφωνο" for="phone" inline>
                    <x-bs::input.text wire:model.defer="voucher.cellphone" id="phone" class="w-50"/>
                </x-bs::input.group>

                <x-bs::input.group label="Οδός" for="address" inline>
                    <div class="d-flex gap-3 align-items-center">
                        <x-bs::input.text wire:model.defer="voucher.address" id="address"/>
                        <span>Αριθμός</span>
                        <x-bs::input.text wire:model.defer="voucher.address_number" class="w-25"/>
                    </div>
                </x-bs::input.group>

                <x-bs::input.group label="ΤΚ" for="postcode" inline>
                    <div class="d-flex gap-3 align-items-center">
                        <x-bs::input.text wire:model.defer="voucher.postcode" id="postcode" class="w-25"/>
                        <span>Περιοχή</span>
                        <x-bs::input.text wire:model.defer="voucher.region"/>
                    </div>
                </x-bs::input.group>

                <x-bs::input.group label="Χώρα" for="country" inline>
                    <x-bs::input.select wire:model="voucher.country" id="country">
                        <option value="GR">Ελλάδα</option>
                        <option value="CY">Κύπρος</option>
                    </x-bs::input.select>
                </x-bs::input.group>

                <x-bs::input.group label="Ημερομηνία παραλαβής" for="pickup-date" inline>
                    <x-bs::input.date wire:model.defer="voucher.pickup_date" id="pickup-date" class="w-50"/>
                </x-bs::input.group>
            </div>

            <div class="col-4 border-start">
                <div wire:loading.class="opacity-50" class="d-flex flex-column px-3 gap-1">
                    <div class="d-flex align-items-center justify-content-center img-thumbnail" style="height: 60px">
                        <img :src="icons[courier]" alt="" style="max-height: 55px" class="img-fluid">
                    </div>

                    @if(filled($options))
                        @foreach($options as $id => $value)
                            @if(is_array($value))
                                <x-bs::input.group label="{{ $id }}" for="service-{{ $id }}">
                                    <x-bs::input.select id="service-{{ $id }}">
                                        <option value="">{{ $id }}</option>
                                        @foreach($value as $k => $v)
                                            <option value="{{ $k }}">{{ $v }}</option>
                                        @endforeach
                                    </x-bs::input.select>
                                </x-bs::input.group>
                            @else
                                <x-bs::input.checkbox id="service-{{ $id }}" wire:model.defer="voucher.services.{{ $id }}" value="{{ $id }}">{{ $value }}</x-bs::input.checkbox>
                            @endif
                        @endforeach
                    @else
                        <div class="text-secondary py-5 text-center">Δεν βρέθηκαν υπηρεσίες.</div>
                    @endif
                    {{--                    <x-bs::input.checkbox id="cd" wire:model.defer="voucher.services.0" value="5">Αντικαταβολή</x-bs::input.checkbox>--}}
                    {{--                    --}}
                    {{--                    <x-bs::input.checkbox id="ia" wire:model.defer="voucher.services.1" value="7">Δυσπρόσιτη περιοχή</x-bs::input.checkbox>--}}
                    {{--                    <x-bs::input.checkbox id="sd" wire:model.defer="voucher.services.2" value="2">Παράδοση Σάββατο</x-bs::input.checkbox>--}}
                    {{--                    <x-bs::input.checkbox id="md" wire:model.defer="voucher.services.3" value="3">Πρωινή παράδοση</x-bs::input.checkbox>--}}

                    {{--                    <div>--}}
                    {{--                        @if($voucher['country'] === 'CY')--}}
                    {{--                            <div class="fw-500 mb-1 mt-3">Υπηρεσίες για Κύπρο</div>--}}

                    {{--                            <x-bs::input.group label="Περιεχόμενο αποστολής" for="content-type" class="mb-2">--}}
                    {{--                                <x-bs::input.select wire:model.defer="voucher.content_type" id="content-type">--}}
                    {{--                                    <option value="">Επιλέξτε περιεχόμενο αποστολής</option>--}}
                    {{--                                    @foreach($contentTypes as $id => $name)--}}
                    {{--                                        <option value="{{ $id }}">{{ $name }}</option>--}}
                    {{--                                    @endforeach--}}
                    {{--                                </x-bs::input.select>--}}
                    {{--                            </x-bs::input.group>--}}

                    {{--                            <x-bs::input.checkbox id="ce" wire:model.defer="voucher.services.4" value="10">Cyprus Economy</x-bs::input.checkbox>--}}
                    {{--                            <x-bs::input.checkbox id="pp" wire:model.defer="voucher.services.5" value="11">Point - Point</x-bs::input.checkbox>--}}
                    {{--                            <x-bs::input.checkbox id="dp" wire:model.defer="voucher.services.6" value="12">Door - Point</x-bs::input.checkbox>--}}
                    {{--                            <x-bs::input.checkbox id="pd" wire:model.defer="voucher.services.7" value="13">Point - Door</x-bs::input.checkbox>--}}
                    {{--                        @endif--}}
                    {{--                    </div>--}}
                </div>
            </div>
        </x-bs::modal.body>

        <x-bs::modal.footer>
            <x-bs::modal.close-button>Άκυρο</x-bs::modal.close-button>
            <x-bs::button.primary type="submit">Αγορά</x-bs::button.primary>
        </x-bs::modal.footer>
    </x-bs::modal>
</form>