<div class="table-responsive">
    <x-bs::table size="sm" class="small table-bordered">
        <thead>
        <tr>
            <th style="width: 2rem;">&nbsp;</th>
            <th>Κωδικός παραγγελίας</th>
            <th>Πελάτης</th>
            <th class="text-end">Σύνολο πληρωμής</th>
            <th class="text-end">Προμήθεια</th>
            <th class="text-end">Σύνολο παραγγελίας</th>
        </tr>
        </thead>

        <tbody>
        @foreach($payments as $cartId => $payment)
            <tr>
                <td>
                    @if(isset($payment['error']))
                        <em class="fas fa-exclamation-circle text-danger" title="{{ $payment['error'] }}"></em>
                    @elseif(isset($payment['warning']))
                        <em class="fas fa-minus-circle text-warning" title="{{ $payment['warning'] }}"></em>
                    @else
                        <em class="fas fa-check-circle text-success"></em>
                    @endisset
                </td>

                <td>
                    @if($payment['cartId'])
                        <a href="{{ route('carts.show', $payment['cartId']) }}">
                            {{ $payment['reference_id'] }}
                        </a>
                    @else
                        {{ $payment['reference_id'] }}
                    @endif
                </td>

                <td>{{ $payment['customer'] }}</td>

                <td class="text-end">{{ format_currency($payment['payoutTotal']) }}</td>

                <td class="text-end">{{ format_currency($payment['fees']) }}</td>

                <td class="text-end">
                    @if($payment['cartTotal'] !== null)
                        {{ format_currency($payment['cartTotal']) }}
                    @else
                        -
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>

        <tfoot>
        <tr>
            <td class="text-end" colspan="5">Αποδόθηκαν</td>
            <td class="text-end">{{ $passed }}/{{ $payments->count() }}</td>
        </tr>
        <tr>
            <td class="text-end" colspan="5">Σύνολο</td>
            <td class="text-end">{{ format_currency($payments->sum('cartTotal')) }}</td>
        </tr>
        <tr>
            <td class="text-end" colspan="5">Προμήθεια</td>
            <td class="text-end">{{ format_currency($payments->sum('fees')) }}</td>
        </tr>
        <tr>
            <td class="text-end fw-bold" colspan="5">Τελικό ποσό</td>
            <td class="text-end fw-bold">{{ format_currency($payments->sum('payoutTotal')) }}</td>
        </tr>
        </tfoot>
    </x-bs::table>
</div>