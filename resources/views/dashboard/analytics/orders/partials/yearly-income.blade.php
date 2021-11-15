<div class="vstack h-100">
    <div class="fw-500 mb-2">{{ __("eshop::analytics.yearly_income") }}</div>

    <x-bs::card class="flex-grow-1">
        <x-bs::card.body class="d-grid gap-3">
            <div class="fw-500 fs-4 text-blue-500">{{ format_currency( $yearly_income->sum() ) }}</div>

            <div class="ratio ratio-16x9 my-auto">
                <canvas id="yearly-income"></canvas>
            </div>
        </x-bs::card.body>
    </x-bs::card>
</div>

@push('footer_scripts')
    <script>
        new Chart(document.getElementById('yearly-income').getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! $yearly_income->keys()->map(fn($key) => !str_contains($key, ' ') ? $key : explode(' ', $key))->toJson() !!},
                datasets: [
                    {
                        label: '{{ __("eshop::analytics.income") }}',
                        data: [{{ $yearly_income->join(', ') }}],
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
                        data: [{{ $yearly_profits->join(', ') }}],
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
                            maxTicksLimit: 5
                        },
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
            }
        })
    </script>
@endpush