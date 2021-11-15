<div class="vstack h-100">
    <div class="fw-500 mb-2">{{ __("eshop::analytics.total_orders") }} ({{ now()->isoFormat('MMMM') }})</div>

    <x-bs::card class="flex-grow-1">
        <x-bs::card.body>
            <div class="d-grid gap-3">
                <div class="fw-500 fs-4 text-blue-500">{{ $orders->sum() }}</div>

                <div class="ratio" style="--bs-aspect-ratio: 30%">
                    <canvas id="total-orders"></canvas>
                </div>
            </div>
        </x-bs::card.body>
    </x-bs::card>
</div>

@push('footer_scripts')
    <script>
        new Chart(document.getElementById('total-orders').getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! $orders->keys()->map(fn($key) => !str_contains($key, ' ') ? $key : explode(' ', $key))->toJson() !!},
                datasets: [{
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
                        ticks: {
                            maxTicksLimit: 8
                        },
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
            }
        })
    </script>
@endpush