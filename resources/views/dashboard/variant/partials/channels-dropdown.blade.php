<x-bs::dropdown wire:ignore>
    <x-bs::dropdown.button class="btn-white" id="channels">
        <em class="fas fa-podcast"></em>
    </x-bs::dropdown.button>

    <x-bs::dropdown.menu class="shadow" button="bulk-actions" alignment="right" style="max-height: 350px; overflow-y: auto; overflow-x: hidden">
        @foreach($channels as $channel)
            <x-bs::dropdown.item x-on:click.prevent="$wire.toggleChannel({{ $channel->id }}, [...document.querySelectorAll('#variants-table tbody input:checked')].map(i => i.value), true)">
                <em class="fa fa-plus me-2 w-1r fa-sm text-secondary"></em>
                {{ $channel->name }}
            </x-bs::dropdown.item>

            <x-bs::dropdown.item x-on:click.prevent="$wire.toggleChannel({{ $channel->id }}, [...document.querySelectorAll('#variants-table tbody input:checked')].map(i => i.value), false)">
                <em class="fa fa-minus me-2 w-1r fa-sm text-secondary"></em>
                {{ $channel->name }}
            </x-bs::dropdown.item>

            <x-bs::dropdown.item x-on:click.prevent="$dispatch('edit-channel-prices', { channel_id: {{ $channel->id }}, channel_name:'{{ $channel->name }}', product_ids: [...document.querySelectorAll('#variants-table tbody input:checked')].map(i => i.value) })">
                <em class="fa fa-euro-sign me-2 w-1r fa-sm text-secondary"></em>
                {{ $channel->name }} τιμές
            </x-bs::dropdown.item>

            @unless($loop->last)
                <x-bs::dropdown.divider/>
            @endunless
        @endforeach
    </x-bs::dropdown.menu>
</x-bs::dropdown>

<x-bs::modal
    x-data="{
        channel_id: '',
        channel_name: '',
        product_ids: [],
        price: '',
        discount: '',
        submitting: false,
        submit() {
            this.submitting = true;
            // I don't know why this.product_ids doesn't work but poduct_ids works.
            $wire.saveChannelPrices(this.channel_id, product_ids, this.price, this.discount)
                .then(() => {
                    this.submitting = false;
                    bootstrap.Modal.getInstance($el).hide();
                });
        }
    }"
    x-init="new bootstrap.Modal($el)"
    x-on:edit-channel-prices.window="
        channel_id = $event.detail.channel_id;
        channel_name = $event.detail.channel_name;
        this.product_ids = $event.detail.product_ids;
        bootstrap.Modal.getInstance($el).show();
    ">
    <x-bs::modal.header>Αλλαγή τιμών <span x-text="channel_name"></span></x-bs::modal.header>

    <x-bs::modal.body>
        <div x-data="{ distinct: true }">
            <div class="form-check mb-0">
                <input x-model="distinct"
                       type="checkbox"
                       class="form-check-input"
                       autocomplete="off"
                       id="distinct">

                <label for="distinct" class="form-check-label">Διακριτές τιμές</label>
            </div>

            <div class="d-flex mt-3 gap-3">
                <div>
                    <label for="edit-channel-price" class="form-label">Τιμή</label>
                    <input x-bind:disabled="!distinct"
                           x-model="price"
                           id="edit-channel-price"
                           type="text"
                           pattern="[0-9]*\.?[0-9]+"
                           class="form-control form-control-sm"
                           oninput="this.value = this.value.replace(/[^0-9,.]/g, '').replace(/([,.])/g, '.').replace(/(\..*)\./g, '$1')"/>
                </div>

                <div>
                    <label for="edit-channel-discount" class="form-label">Έκπτωση %</label>
                    <input x-bind:disabled="!distinct"
                           x-model="discount"
                           id="edit-channel-discount"
                           type="text"
                           pattern="[0-9]*\.?[0-9]+"
                           class="form-control form-control-sm"
                           oninput="this.value = this.value.replace(/[^0-9,.]/g, '').replace(/([,.])/g, '.').replace(/(\..*)\./g, '$1')"/>
                </div>
            </div>
        </div>
    </x-bs::modal.body>

    <x-bs::modal.footer>
        <x-bs::modal.close-button>{{ __("Cancel") }}</x-bs::modal.close-button>
        <x-bs::button.primary x-bind:disabled="submitting" @click.prevent="submit()" type="submit">{{ __("Save") }}</x-bs::button.primary>
    </x-bs::modal.footer>
</x-bs::modal>
