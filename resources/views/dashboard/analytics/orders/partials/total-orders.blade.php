<div class="vstack h-100">
    <div class="fw-500 mb-2">{{ __("eshop::analytics.total_orders") }} ({{ now()->isoFormat('MMMM') }})</div>

    <x-bs::card class="flex-grow-1">
        <x-bs::card.body class="vstack gap-3">
            <div class="d-flex table-responsive scrollbar">
                <div class="d-flex gap-5 text-nowrap">
                    <div class="vstack">
                        <div class="small text-secondary">Σύνολο</div>
                        <div class="fs-4">{{ $orders->sum() }}</div>
                    </div>
    
                    <div class="vstack">
                        <div class="small text-secondary">Μ. Ο.</div>
                        <div class="fs-4">{{ round($orders->avg()) }}</div>
                    </div>
    
                    <div class="vstack">
                        <div class="small text-secondary">Ελάχιστο</div>
                        <div class="fs-4">{{ $orders->min() }}</div>
                    </div>
    
                    <div class="vstack">
                        <div class="small text-secondary">Μέγιστο</div>
                        <div class="fs-4">{{ $orders->max() }}</div>
                    </div>
                </div>
            </div>

            <div class="graph">
                <canvas id="total-orders"></canvas>
            </div>
        </x-bs::card.body>
    </x-bs::card>
</div>

@push('footer_scripts')
    <script>
        new Chart(document.getElementById("total-orders").getContext("2d"), {
            type: 'line',
            data: {
                labels: {!! $orders->keys()->map(fn($key) => !str_contains($key, ' ') ? $key : explode(' ', $key))->toJson() !!},
                datasets: [
                    {
                        label: '{{ __("eshop::analytics.orders") }}',
                        data: [{{ $orders->join(', ') }}],
                        borderColor: 'rgb(26,115,232)',
                        backgroundColor: 'rgba(26,115,232, 0.3)',
                        borderWidth: 2,
                        pointHoverRadius: 6,
                        pointRadius: 5,
                        fill: false,
                        pointBackgroundColor: 'white',
                        pointHoverBorderColor: '#ff6384',
                        pointHoverBorderWidth: 2,
                    },
                ]
            },
            options: {
                "horizontalLine": [{
                    "y": {{ round($orders->avg()) }},
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
                        ticks: {

                            beginAtZero: true,
                            maxTicksLimit: 5,
                            stepSize: 1,
                            max: 5,
                        },
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
        });

    </script>
@endpush