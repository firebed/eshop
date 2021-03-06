<div class="vstack h-100">
    <div class="fw-500 mb-2">{{ __("eshop::analytics.monthly_orders") }} ({{ now()->year }})</div>

    <x-bs::card class="flex-grow-1">
        <x-bs::card.body class="vstack gap-3">
            <div class="d-flex table-responsive scrollbar">
                <div class="d-flex gap-5 text-nowrap">
                    <div class="vstack">
                        <div class="small text-secondary">Σύνολο</div>
                        <div class="fs-4">{{ $monthly_orders->sum() }}</div>
                    </div>

                    <div class="vstack">
                        <div class="small text-secondary">Μ. Ο.</div>
                        <div class="fs-4">{{ round($monthly_orders->avg()) }}</div>
                    </div>

                    <div class="vstack">
                        <div class="small text-secondary">Ελάχιστο</div>
                        <div class="fs-4">{{ $monthly_orders->min() }}</div>
                    </div>

                    <div class="vstack">
                        <div class="small text-secondary">Μέγιστο</div>
                        <div class="fs-4">{{ $monthly_orders->max() }}</div>
                    </div>
                </div>
            </div>

            <div class="graph">
                <canvas id="monthly-orders"></canvas>
            </div>
        </x-bs::card.body>
    </x-bs::card>
</div>

@push('footer_scripts')
    <script>
        new Chart(document.getElementById('monthly-orders').getContext('2d'), {
            data: {
                labels: {!! $monthly_orders->keys()->map(fn($key) => !str_contains($key, ' ') ? $key : explode(' ', $key))->toJson() !!},
                datasets: [
                    {
                        type: 'bar',
                        label: '{{ __("eshop::analytics.orders") }}',
                        data: [{{ $monthly_orders->join(', ') }}],
                        backgroundColor: 'rgba(26,115,232, 0.65)',
                    },
                ]
            },
            options: {
                "horizontalLine": [{
                    "y": {{ round($monthly_orders->avg()) }},
                    "text": "ΜΟ"
                }],
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        callbacks: {
                            title: function (context) {
                                return context[0].label.replace(',', ' ')
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            maxTicksLimit: 6
                        },
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
            }
        })
    </script>
@endpush