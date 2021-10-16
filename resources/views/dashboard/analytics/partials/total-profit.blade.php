<x-bs::card class="h-100">
    <x-bs::card.body>
        <div class="d-grid gap-3">
            <div class="d-flex justify-content-between">
                <div class="fw-bold">{{ __("eshop::analytics.total_profit") }}</div>
                <a href="#" class="text-decoration-none">{{ __("eshop::analytics.view_report") }}</a>
            </div>

            <div class="d-flex gap-3 align-items-baseline">
                <div class="d-grid">
                    <div class="fw-500 fs-4 text-purple-500">{{ format_currency($totalProfit->sum()) }}</div>
                    
                    @if($totalProfitComparison->isNotEmpty())
                        @php($percent = ($totalProfit->sum() - $totalProfitComparison->sum())/($totalProfitComparison->sum() ?: 1))
                        @if($percent >= 0)
                            <small class="lh-sm fw-500 text-success"><em class="fas fa-arrow-up"></em> {{ format_percent(abs($percent)) }}</small>
                        @else
                            <small class="lh-sm fw-500 text-danger"><em class="fas fa-arrow-down"></em> {{ format_percent(abs($percent)) }}</small>
                        @endif
                    @endif
                </div>
                
                @if($totalProfitComparison->isNotEmpty())
                    &dash;
                    <div class="fs-4 text-secondary">{{ format_currency($totalProfitComparison->sum()) }}</div>
                @endif
            </div>

            <div class="ratio ratio-16x9">
                <canvas id="total-profit"></canvas>
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
        new Chart(document.getElementById('total-profit').getContext('2d'), {
            type: 'line',
            data: {
                labels: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23],
                datasets: [{
                    label: '{{ $date->isoFormat('ll') }}',
                    data: [{{ $totalProfit->map(fn($total, $hour) => "{hour: $hour, total: $total}")->join(', ') }}],
                    parsing: {
                        yAxisKey: 'total',
                        xAxisKey: 'hour'
                    },
                    fill: false,
                    borderColor: 'rgb(177, 136, 225)',
                    backgroundColor: 'rgba(177, 136, 225, 0.3)',
                    borderWidth: 1,
                },
                @if($totalProfitComparison->isNotEmpty())
                    {
                        label: '{{ $dateComparison->isoFormat('ll') }}',
                        data: [{{ $totalProfitComparison->map(fn($total, $hour) => "{hour: $hour, total: $total}")->join(', ') }}],
                        parsing: {
                            yAxisKey: 'total',
                            xAxisKey: 'hour'
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
                    x: {
                        beginAtZero: true,
                        ticks: {
                            maxTicksLimit: 12
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
            }
        })
    </script>
@endpush