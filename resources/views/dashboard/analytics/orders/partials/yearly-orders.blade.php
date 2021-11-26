<div class="vstack h-100">
    <div class="fw-500 mb-2">{{ __("eshop::analytics.yearly_orders") }}</div>

    <x-bs::card class="flex-grow-1">
        <x-bs::card.body class="vstack gap-3">
            <div class="table-responsive scrollbar">
                <div class="d-flex gap-5 text-nowrap">
                    <div class="vstack">
                        <div class="small text-secondary">Σύνολο</div>
                        <div class="fs-4">
                            {{ $yearly_orders->sum() }}
                        </div>
                    </div>

                    <div class="vstack">
                        <div class="small text-secondary">Μ.Ο.</div>
                        <div class="fs-4">
                            {{ round($yearly_orders->avg()) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="graph">
                <canvas id="yearly-orders"></canvas>
            </div>
        </x-bs::card.body>
    </x-bs::card>
</div>

@push('footer_scripts')
    <script>
        new Chart(document.getElementById('yearly-orders').getContext('2d'), {
            data: {
                labels: {!! $yearly_orders->keys()->map(fn($key) => !str_contains($key, ' ') ? $key : explode(' ', $key))->toJson() !!},
                datasets: [
                    {
                        type: 'line',
                        label: '{{ __("eshop::analytics.orders") }}',
                        data: [{{ $yearly_orders->join(', ') }}],
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