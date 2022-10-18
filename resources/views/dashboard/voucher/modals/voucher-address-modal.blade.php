<div x-data="{
    loading: false,
    stations: [],
    cart: null,
    index: '',
    close: function() {
        bootstrap.Modal.getInstance($el).hide()
    },
    load: function(e) {
        this.index = ''
        this.cart = e.relatedTarget.dataset.cart
        postcode = e.relatedTarget.dataset.postcode
        this.loading = true
        axios.post(@js(route('vouchers.search-areas')), { postcode })
        .then(r => this.stations = r.data)
        .catch(err => {})
        .finally(() => this.loading = false)
    },
    select: function() {
        station = this.stations[this.index]
        $dispatch('set-station', { cart: this.cart, id:station.Station_ID, name: station.Area, type: station.Inaccessible_Area_Kind })
        this.close()
    }
}"
 x-init="$el.addEventListener('show.bs.modal', e => load(e))" id="search-area" class="modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content shadow">
            <x-bs::modal.header>{{ __('Search address') }}</x-bs::modal.header>
            <x-bs::modal.body class="p-0">
                <div class="d-grid gap-3">
                    <div x-show="loading" x-cloak class="py-5 text-center bg-gray-100"><em class="fa fa-spinner fa-spin"></em></div>
                    <div x-show="!loading">
                        <x-bs::table hover>
                            <thead>
                            <tr>
                                <th></th>
                                <th>Περιοχή</th>
                                <th>ΤΚ</th>
                                <th>Νομός</th>
                                <th>Κατάστημα</th>
                                <th>Υποκατάστημα</th>
                                <th>Είδος</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template x-for="(s, i) in stations" :key="i">
                                <tr>
                                    <td>
                                        <div class="form-check mb-0">
                                            <input :id="`station-${i}`" :value="i" x-model="index" type="radio" class="form-check-input" name="station">
                                        </div>
                                    </td>
                                    <td><label class="d-block" :for="`station-${i}`" x-text="s['Area']"></label></td>
                                    <td><label class="d-block" :for="`station-${i}`" x-text="s['Zip_Code']"></label></td>
                                    <td><label class="d-block" :for="`station-${i}`" x-text="s['Prefecture']"></label></td>
                                    <td><label class="d-block" :for="`station-${i}`" x-text="s['Station_ID']"></label></td>
                                    <td><label class="d-block" :for="`station-${i}`" x-text="s['Branch_ID']"></label></td>
                                    <td><label class="d-block" :for="`station-${i}`" x-text="s['Inaccessible_Area_Kind']"></label></td>
                                </tr>
                            </template>
                            <tr x-show="stations.length === 0" x-cloak><td colspan="7" class="text-center">Δεν βρέθηκαν αποτελέσματα</td></tr>
                            </tbody>
                        </x-bs::table>
                    </div>
                </div>
            </x-bs::modal.body>
            <x-bs::modal.footer>
                <x-bs::modal.close-button>{{ __("Cancel") }}</x-bs::modal.close-button>
                <x-bs::button.primary ::disabled="index === ''" @click.prevent="select()">Επιλογή</x-bs::button.primary>
            </x-bs::modal.footer>
        </div>
    </div>
</div>