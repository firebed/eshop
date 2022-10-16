<div class="table-responsive bg-white">
    <x-bs::table size="sm" class="small table-bordered">
        <thead>
        <tr>
            <th style="width: 2rem;">&nbsp;</th>
            <th>Αναφορά</th>
            <th>Πελάτης</th>
            <th class="text-end">Σύνολο παραγγελίας</th>
            <th class="text-end">Προμήθεια</th>
            <th class="text-end">Πληρωμή</th>
        </tr>
        </thead>

        <tbody>
        @foreach($payouts as $payout)
            @php($cart = $carts->get($payout['reference']))

            <tr>
                <td class="text-center">
                    @if(isset($payout['error']))
                        <em class="fas fa-exclamation-circle text-danger" title="{{ $payout['error'] }}"></em>
                    @else
                        <em class="fas fa-check-circle text-success"></em>
                    @endisset
                </td>

                <td>
                    @if($cart !== null)
                        <a href="{{ route('carts.show', $cart->id) }}">
                            {{ $payout['reference'] }}
                        </a>
                    @else
                        {{ $payout['reference'] }}
                    @endif
                </td>

                <td>
                    <a href="{{ route('carts.index', ['filter' => $payout['customer_name']]) }}" class="flex items-center">
                        <em class="fas fa-search me-1"></em><span>{{ $payout['customer_name'] }}</span>
                    </a>
                </td>

                <td class="text-end">
                    @if($cart !== null)
                        {{ format_currency($cart->total) }}
                    @else
                        -
                    @endif
                </td>

                <td class="text-end">{{ format_currency($payout['fees']) }}</td>

                <td @class(["text-end", "text-danger fw-bold" => $cart !== null && !floats_equal($payout['total'] + $payout['fees'], $cart->total)])>{{ format_currency($payout['total']) }}</td>
            </tr>
        @endforeach
        </tbody>

        <tfoot>
        {{--        <tr>--}}
        {{--            <td class="text-end" colspan="5">Αποδόθηκαν</td>--}}
        {{--            <td class="text-end">{{ 0 }}/{{ $payouts->count() }}</td>--}}
        {{--        </tr>--}}
        <tr>
            <td class="text-end" colspan="5">Σύνολο παραγγελιών</td>
            <td class="text-end">{{ format_currency($carts->sum('total')) }}</td>
        </tr>
        <tr>
            <td class="text-end" colspan="5">Σύνολο προμήθειας</td>
            <td class="text-end">{{ format_currency(-$payouts->sum('fees')) }}</td>
        </tr>
        <tr>
            <td class="text-end fw-bold" colspan="5">Σύνολο πληρωμής</td>
            <td class="text-end fw-bold">{{ format_currency($payouts->sum('total')) }}</td>
        </tr>
        </tfoot>
    </x-bs::table>
</div>