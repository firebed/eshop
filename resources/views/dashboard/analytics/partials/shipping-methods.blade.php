<x-bs::card class="h-100">
    <x-bs::card.body>
        <div class="d-grid gap-3">
            <div class="d-flex justify-content-between">
                <div class="fw-bold">{{ __("eshop::analytics.shipping_methods") }}</div>
{{--                <a href="#" class="text-decoration-none">{{ __("eshop::analytics.view_report") }}</a>--}}
            </div>

            <div class="ratio ratio-16x9">
                <canvas id="shipping-methods"></canvas>
            </div>

            <div class="d-flex gap-3 justify-content-end small">
                @isset($dateComparison)
                    <div class="text-gray-500"><em class="fas fa-square me-2"></em> {{ $dateComparison->isoFormat('ll') }}</div>
                @endisset
                <div class="text-purple-400"><em class="fas fa-square me-2"></em> {{ $date->isoFormat('ll') }}</div>
            </div>
        </div>
    </x-bs::card.body>
</x-bs::card>

@push('footer_scripts')
    <script>
        new Chart(document.getElementById('shipping-methods').getContext('2d'), {
            type: 'bar',
            data: {
                datasets: [{
                    label: '{{ $date->isoFormat('ll') }}',
                    data: [{!! $shippingMethods->map(fn($count, $sm) => "{shippingMethod: '" . __("eshop::shipping.abbr.$sm") . "', count: $count}")->join(', ') !!}],
                    parsing: {
                        yAxisKey: 'count',
                        xAxisKey: 'shippingMethod'
                    },
                    fill: false,
                    borderColor: 'rgb(177, 136, 225)',
                    backgroundColor: 'rgba(177, 136, 225, 0.3)',
                    borderWidth: 1,
                }, 
                @if($shippingMethodsComparison->isNotEmpty())
                {
                    label: '{{ $dateComparison->isoFormat('ll') }}',
                    data: [{!! $shippingMethodsComparison->map(fn($count, $sm) => "{shippingMethod: '" . __("eshop::shipping.abbr.$sm") . "', count: $count}")->join(', ') !!}],
                    parsing: {
                        yAxisKey: 'count',
                        xAxisKey: 'shippingMethod'
                    },
                    fill: false,
                    borderColor: 'rgb(215,215,215)',
                    borderWidth: 1,
                }
                @endif
                ]
            },
            options: {
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grace: '5%',
                        ticks: {
                            precision:0
                        }
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
            }
        })
    </script>
@endpush