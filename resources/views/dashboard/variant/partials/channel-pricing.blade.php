<div class="card shadow-sm">
    <div class="card-body">
        <div class="fw-500 mb-3">Τιμές καναλιών πώλησης</div>

        <p class="text-secondary small">Ενεργοποιήστε την επιλογή "Διακριτή" όταν θέλετε να έχετε διαφορετική τιμή πώλησης ή έκτπωση για το συγκεκριμένο κανάλι.</p>

        <table class="table table-sm">
            <thead>
            <tr>
                <th>Κανάλι</th>
                <th class="text-center">Διακριτή</th>
                <th>Τιμή</th>
                <th>Έκπτωση %</th>
            </tr>
            </thead>

            <tbody>
            @foreach($variant->channels as $channel)
                <tr x-data="{ distinct: @js(old("channel_pricing.$channel->id.distinct", $channel->pivot->price !== null ? 'on' : '') === 'on') }">
                    <td class="fw-bold align-middle">{{ $channel->name }}</td>

                    <td class="align-middle">
                        <div class="d-flex justify-content-center">
                            <input x-model="distinct"
                                   name="channel_pricing[{{ $channel->id }}][distinct]"
                                   type="checkbox"
                                   class="form-check-input"
                                   autocomplete="off">
                        </div>
                    </td>

                    <td>
                        <input x-bind:disabled="!distinct"
                               name="channel_pricing[{{ $channel->id }}][price]"
                               type="text"
                               value="{{ old("channel_pricing.$channel->id.price", $channel->pivot->price ?? '') }}"
                               pattern="[0-9]*\.?[0-9]+"
                               class="form-control form-control-sm"
                               oninput="this.value = this.value.replace(/[^0-9,.]/g, '').replace(/([,.])/g, '.').replace(/(\..*)\./g, '$1')"/>
                    </td>

                    <td>
                        <input x-bind:disabled="!distinct"
                               name="channel_pricing[{{ $channel->id }}][discount]"
                               type="text"
                               value="{{ old("channel_pricing.$channel->id.discount", $channel->pivot->discount ?? '') }}"
                               pattern="[0-9]*\.?[0-9]+"
                               class="form-control form-control-sm"
                               oninput="this.value = this.value.replace(/[^0-9,.]/g, '').replace(/([,.])/g, '.').replace(/(\..*)\./g, '$1')"/>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
