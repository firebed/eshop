<div class="vstack h-100">
    <div class="fw-500 mb-2">{{ __("eshop::analytics.monthly_income") }} ({{ now()->year }})</div>

    <x-bs::card class="flex-grow-1">
        <x-bs::card.body class="vstack gap-3">
                <div class="fw-500 fs-4 text-blue-500">{{ format_currency( $monthly_income->sum() ) }}</div>

                <div class="ratio" style="--bs-aspect-ratio: 30%">
                    <canvas id="monthly-income"></canvas>
                </div>
        </x-bs::card.body>
    </x-bs::card>
</div>

@push('footer_scripts')
    <script>
        new Chart(document.getElementById('monthly-income').getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! $monthly_income->keys()->map(fn($key) => !str_contains($key, ' ') ? $key : explode(' ', $key))->toJson() !!},
                datasets: [
                    {
                        label: '{{ __("eshop::analytics.income") }}',
                        data: [{{ $monthly_income->join(', ') }}],
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
                    {
                        label: '{{ __("eshop::analytics.profits") }}',
                        data: [{{ $monthly_profits->join(', ') }}],
                        borderColor: 'rgb(26,184,232)',
                        backgroundColor: 'rgba(26,184,232, 0.3)',
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
                        beginAtZero: true,
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
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