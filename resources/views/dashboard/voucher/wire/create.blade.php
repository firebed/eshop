<form wire:submit.prevent="purchaseVoucher()">
    <x-bs::modal size="xl" wire:model.defer="showModal">
        <x-bs::modal.header>Αγορά Voucher</x-bs::modal.header>

        <x-bs::modal.body class="bg-gray-100">
            @error('error')
            <div class="alert bg-red-500 fw-500 small">{{ $message }}</div>
            @enderror

            <div class="row g-4 align-items-start">
                <div class="col-8 d-grid gap-2 border-end">
                    <x-bs::input.group label="Μεταφορική" for="couriers" inline>
                        <x-bs::input.select wire:model="courier_id" id="couriers">
                            @foreach($couriers as $courier)
                                <option value="{{ $courier->value }}">{{ $courier->label() }}</option>
                            @endforeach
                        </x-bs::input.select>
                    </x-bs::input.group>
                    
                    <x-bs::input.group label="Αριθμός τεμαχίων" for="items" inline>
                        <x-bs::input.integer wire:model.defer="voucher.number_of_packages" id="items" class="w-25"/>
                    </x-bs::input.group>

                    <x-bs::input.group label="Βάρος (kg)" for="weight" inline>
                        <div x-data="{value: @entangle('voucher.weight').defer, round(v) { return Math.round((v + Number.EPSILON) * 100) / 100 }}" class="d-flex gap-1">
                            <input type="number" step="0.01" x-model="value" id="weight" class="form-control w-25"/>
                            <x-bs::button.white @click="value = round(value + 0.5)"><span class="small fw-500">+ 0.5</span></x-bs::button.white>
                            <x-bs::button.white @click="if ((nv = value = round(value - 0.5)) >= 0) value = nv; else value = 0"><span class="small fw-500">- 0.5</span></x-bs::button.white>
                        </div>
                    </x-bs::input.group>

                    <x-bs::input.group label="Αντικαταβολή" for="cod" inline>
                        <input type="text" step="0.01" wire:model.defer="voucher.cod_amount" id="cod" class="form-control w-25" readonly @unless($cod) disabled @endunless/>
                    </x-bs::input.group>

                    <x-bs::input.group label="Αποστολέας" for="sender" inline>
                        <x-bs::input.text wire:model.defer="voucher.sender_name" id="sender"/>
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
                        <input type="date" wire:model.defer="voucher.pickup_date" id="pickup-date" class="form-control w-50"/>
                    </x-bs::input.group>

                    <x-bs::input.group label="Περιεχόμενο αποστολής" for="content-type" inline>
                        <x-bs::input.select wire:model.defer="voucher.content_type" id="content-type">
                            <option value="">Επιλέξτε περιεχόμενο αποστολής</option>
                            @foreach($contentTypes as $type)
                                <option value="{{ $type->value }}">{{ $type->label() }}</option>
                            @endforeach
                        </x-bs::input.select>
                    </x-bs::input.group>
                </div>

                <div class="col-4">
                    <div wire:loading.class="opacity-50" class="d-flex flex-column gap-1">
                        <div class="d-flex align-items-center justify-content-center img-thumbnail" style="height: 40px">
                            @if(filled($icon))
                                <img src="{{ $icon }}" alt="" style="max-height: 35px; max-width: 100px">
                            @endif
                        </div>

                        <div class="fw-bold mb-2">Υπηρεσίες</div>
                        <div class="scrollbar overflow-auto ps-1" style="max-height: 440px">
                            @forelse($services as $groups)
                                @foreach($groups as $id => $name)
                                    <x-bs::input.checkbox id="service-{{ $id }}" wire:model.defer="voucher.services.{{ $id }}" value="{{ $id }}">{{ $name }}</x-bs::input.checkbox>
                                @endforeach

                                @if(!$loop->last)
                                    <hr>
                                @endif
                            @empty
                                <div class="text-secondary py-5 text-center">Δεν βρέθηκαν υπηρεσίες.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </x-bs::modal.body>

        <x-bs::modal.footer>
            <x-bs::modal.close-button>Άκυρο</x-bs::modal.close-button>
            <x-bs::button.primary type="submit">
                <em wire:loading.remove wire:target="purchaseVoucher" class="fa fa-shopping-bag"></em> 
                <em wire:loading wire:target="purchaseVoucher" class="fa fa-spinner fa-spin"></em> 
                <span class="ms-1">Έκδοση</span>
            </x-bs::button.primary>
        </x-bs::modal.footer>
    </x-bs::modal>
</form>