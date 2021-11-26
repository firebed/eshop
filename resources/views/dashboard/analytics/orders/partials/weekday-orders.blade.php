<div class="vstack h-100">
    <div class="fw-500 mb-2">{{ __("eshop::analytics.weekday_orders") }}</div>

    <x-bs::card class="flex-grow-1">
        <x-bs::card.body class="d-grid">
            <div class="graph my-auto">
                <canvas id="weekday-orders"></canvas>
            </div>
        </x-bs::card.body>
    </x-bs::card>
</div>

@push('footer_scripts')
    <script>
        new Chart(document.getElementById('weekday-orders').getContext('2d'), {
            data: {
                labels: {!! $weekday_orders->keys()->map(fn($key) => !str_contains($key, ' ') ? $key : explode(' ', $key))->toJson() !!},
                datasets: [
                    {
                        type: 'line',
                        label: '{{ __("eshop::analytics.orders") }}',
                        data: [{{ $weekday_orders->join(', ') }}],
                        pointHoverRadius: 6,
                        pointRadius: 5,
                        fill: false,
                        pointBackgroundColor: 'white',
                        pointHoverBorderColor: '#ff6384',
                        pointHoverBorderWidth: 2,
                        borderColor: 'rgb(26,115,232)',
                        borderWidth: 2,
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
                        beginAtZero: true,
                        grid: {
                            display: false
                        }
                    },
                    y: {
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