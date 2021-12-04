@extends('eshop::dashboard.layouts.product', ['product' => $product->isVariant() ? $product->parent : $product])

@section('content')
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4 mb-4">
        <div class="col">
            <div class="vstack d-flex border rounded bg-white p-3 h-100">
                <div class="text-secondary small">Συνολικές πωλήσεις</div>
                <div class="fw-500 fs-4">{{ format_number($totals->sum('total_quantity')) }} <span class="fs-6 text-secondary fw-normal">τεμάχια</span></div>
                <div class="small text-secondary">από <span class="fw-500">{{ format_number($totals->sum('submitted_orders_count')) }}</span> παραγγελίες</div>
            </div>
        </div>

        <div class="col">
            <div class="vstack d-flex border rounded bg-white p-3 h-100">
                <span class="text-secondary small">Συνολικός Τζίρος</span>
                <span class="fw-500 fs-4">{{ format_currency($totals->sum('total_revenue')) }}</span>
                <div class="small text-secondary">εκ των οποίων
                    <span class="fw-500">{{ format_currency($total_profits_sum = $totals->sum('total_profits')) }}</span>
                    @if(($sum = $totals->sum('total_revenue_without_vat')) > 0)
                        <span class="fw-normal text-secondary">({{ format_percent(round($total_profits_sum/$sum, 2)) }})</span>
                    @endif
                    είναι κέρδη
                </div>
            </div>
        </div>

        <div class="col">
            <div class="vstack d-flex border rounded bg-white p-3 h-100">
                <div class="text-secondary small">Στο καλάθι</div>
                <div class="fw-500 fs-4">{{ format_number($product->not_submitted_quantity_sum) }} <span class="fs-6 text-secondary fw-normal">τεμάχια</span></div>
                <div class="small text-secondary">σε <span class="fw-500">{{ format_number($product->not_submitted_orders_count) }}</span> παραγγελίες</div>
            </div>
        </div>
    </div>

    <div class="table-responsive bg-white border rounded">
        <table class="table table-hover mb-0 rounded">
            <thead>
            <tr>
                <th>{{ __("Date") }}</th>
                <th>#{{ __("Παραγγελία") }}</th>
                <th>{{ __("Customer") }}</th>
                <th class="text-end">{{ __("Quantity") }}</th>
                <th class="text-end">{{ __("Price") }}</th>
                <th class="text-center">{{ __("Κατάσταση") }}</th>
            </tr>
            </thead>
            <tobdy>
                @foreach($movements as $movement)
                    <tr>
                        <td>{{ $movement->cart->submitted_at->format('d/m/Y') }} <span class="small text-secondary">{{ $movement->cart->submitted_at->format('H:i') }}</span></td>
                        <td><a href="{{ route('carts.show', $movement->cart) }}">#{{ $movement->cart->id }}</a></td>
                        <td>{{ $movement->cart->shippingAddress->full_name }}</td>
                        <td class="text-end">{{ format_number($movement->quantity) }}</td>
                        <td @class(['text-end', 'text-danger fw-bold' => $movement->net_value < $movement->compare_price])>{{ format_currency($movement->net_value) }}</td>
                        <td class="text-center"><span class="badge rounded-pill bg-{{ $movement->cart->status->color }} px-3" style="padding-bottom: 0.4rem">{{ __("eshop::cart.status.action.{$movement->cart->status->name}") }}</span></td>
                    </tr>
                @endforeach
            </tobdy>

            <caption>
                <x-eshop::pagination :paginator="$movements"/>
            </caption>
        </table>
    </div>
@endsection
