<div class="vstack h-100">
    <div class="fw-500 mb-2">{{ __("eshop::analytics.weekday_orders") }}</div>

    <x-bs::card class="flex-grow-1">
        <x-bs::card.body class="vstack gap-3">
            <div class="fw-500 fs-4 text-blue-500">{{ format_number($weekday_orders->sum()) }}</div>

            <div class="ratio ratio-16x9 my-auto">
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
                        type: 'bar',
                        label: '{{ __("eshop::analytics.orders") }}',
                        data: [{{ $weekday_orders->join(', ') }}],
                        backgroundColor: 'rgba(75, 192, 192, .7)',
                    },
                ]
            },
            options: {
                indexAxis: 'y',
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