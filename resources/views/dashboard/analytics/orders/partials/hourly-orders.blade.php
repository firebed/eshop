<div class="vstack h-100">
    <div class="fw-500 mb-2">{{ __("eshop::analytics.hourly_orders") }}</div>

    <x-bs::card class="flex-grow-1">
        <x-bs::card.body class="d-grid">
            <div class="graph my-auto">
                <canvas id="hourly-orders"></canvas>
            </div>
        </x-bs::card.body>
    </x-bs::card>
</div>

@push('footer_scripts')
    <script>
        new Chart(document.getElementById('hourly-orders').getContext('2d'), {
            data: {
                labels: {!! $hourly_orders->keys()->map(fn($key) => !str_contains($key, ' ') ? $key : explode(' ', $key))->toJson() !!},
                datasets: [
                    {
                        type: 'radar',
                        label: '{{ __("eshop::analytics.orders") }}',
                        data: [{{ $hourly_orders->join(', ') }}],
                        // pointHoverRadius: 6,
                        // pointRadius: 5,
                        // fill: false,
                        // pointBackgroundColor: 'white',
                        // pointHoverBorderColor: '#ff6384',
                        // pointHoverBorderWidth: 2,
                        borderColor: 'rgba(0,0,0,0.6)',
                        borderWidth: 1,
                    },
                ]
            },
            options: {
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                // scales: {
                //     x: {
                //         beginAtZero: true,
                //         grid: {
                //             display: false
                //         }
                //     },
                //     y: {
                //         ticks: {
                //             maxTicksLimit: 6
                //         },
                //     }
                // },
                responsive: true,
                maintainAspectRatio: false,
            }
        })
    </script>
@endpush