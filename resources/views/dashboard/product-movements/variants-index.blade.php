@extends('eshop::dashboard.layouts.product')

@section('content')
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4 mb-4">
        <div class="col">
            <div class="vstack d-flex border rounded bg-white p-3 h-100">
                <div class="text-secondary small">Συνολικές πωλήσεις</div>
                <div class="fw-500 fs-4">{{ format_number($variants->sum('total_sales_quantity')) }} <span class="fs-6 text-secondary fw-normal">τεμάχια</span></div>
                <div class="small text-secondary">από <span class="fw-500">{{ format_number($variants->sum('submitted_orders_count')) }}</span> παραγγελίες</div>
            </div>
        </div>

        <div class="col">
            <div class="vstack d-flex border rounded bg-white p-3 h-100">
                <span class="text-secondary small">Συνολικός Τζίρος</span>
                <span class="fw-500 fs-4">{{ format_currency($variants->sum('total_revenue')) }}</span>
                <div class="small text-secondary">εκ των οποίων
                    <span class="fw-500">{{ format_currency($total_profits_sum = $variants->sum('total_profits')) }}</span>
                    @if(($sum = $variants->sum('total_revenue_without_vat')) > 0)
                        <span class="fw-normal text-secondary">({{ format_percent(round($total_profits_sum/$sum, 2)) }})</span>
                    @endif
                    είναι κέρδη
                </div>
            </div>
        </div>

        <div class="col flex-grow-1">
            <div class="vstack d-flex border rounded bg-white p-3 h-100">
                <div class="text-secondary small">Συνολικές παραλλαγές</div>
                <div class="fw-500 fs-4">{{ format_number($variants->count()) }}</div>
                @if (($idle = $variants->filter(fn($v) => $v->total_sales_quantity === 0)->count()) > 0)
                    @if($idle === $variants->count())
                        <div class="small text-danger"><em class="fas fa-exclamation-circle"></em> @choice('eshop::product.not_sold', 0)</div>
                    @else
                        <div class="small text-secondary">@choice('eshop::product.not_sold', $idle, ['count' => format_number($idle)])</div>
                    @endif
                @else
                    <div class="small text-success"><em class="fas fa-check-circle"></em> {{ __("eshop::product.all_sold") }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="d-grid gap-4">
        @foreach($variants as $variant)
            <div class="d-grid d-sm-flex border rounded bg-white p-4 gap-2 gap-sm-4">
                <div class="w-7r ratio ratio-1x1 mx-auto mx-sm-0" style="max-height: 7rem">
                    <img src="{{ $variant->image->url('sm') }}" alt="" class="img-middle rounded">
                </div>

                <div class="d-grid gap-3 flex-grow-1">
                    <div class="fw-500">{{ $variant->option_values }}</div>

                    <div class="d-flex justify-content-between gap-5 flex-nowrap overflow-auto text-nowrap scrollbar">
                        <div class="col">
                            <div class="small text-secondary">{{ __("Orders") }}</div>
                            <div class="fw-500 small">{{ format_number($variant->submitted_orders_count) }}</div>
                        </div>

                        <div class="col">
                            <div class="small text-secondary">{{ __("Quantity") }}</div>
                            <div class="fw-500 small">{{ format_number( $variant->total_sales_quantity ) }}</div>
                        </div>

                        <div class="col">
                            <div class="small text-secondary">{{ __("Revenue") }}</div>
                            <div class="fw-500 small">{{ format_currency($variant->total_revenue) }}</div>
                        </div>

                        <div class="col">
                            <div class="small text-secondary">{{ __("Profits") }}</div>
                            <div class="fw-500 small">
                                {{ format_currency($variant->total_profits) }}
                                @if(($sum = $variant->total_revenue_without_vat) > 0)
                                    <span class="fw-normal text-secondary">({{ format_percent(round($variant->total_profits/$sum, 2)) }})</span>
                                @endif
                            </div>
                        </div>

                        <div class="col">
                            <div class="small text-secondary">{{ __("In cart") }}</div>
                            <div class="fw-500 small">{{ format_number($variant->not_submitted_quantity_sum) }}</div>
                        </div>
                    </div>
                </div>

                <div class="ms-auto">
                    <a href="{{ route('products.movements.index', $variant) }}" class="btn btn-outline-primary">{{ __("Movements") }}</a>
                </div>
            </div>
    @endforeach
@endsection
