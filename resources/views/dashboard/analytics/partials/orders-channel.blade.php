<x-bs::card class="h-100">
    <x-bs::card.body>
        <div class="d-grid gap-2">
            <div class="d-flex justify-content-between">
                <div class="fw-bold">{{ __("Orders channel") }}</div>
                <a href="#" class="text-decoration-none">{{ __("View report") }}</a>
            </div>

            <div class="fw-500">{{ __("Orders by channel over time") }}</div>

            <div class="ratio ratio-16x9">
                <canvas id="orders-channel"></canvas>
            </div>

            <div class="d-flex gap-3 justify-content-end small">
                <div class="text-gray-500"><em class="fas fa-square me-2"></em> {{ \Illuminate\Support\Carbon::yesterday()->isoFormat('ll') }}</div>
                <div class="text-purple-400"><em class="fas fa-square me-2"></em> {{ today()->isoFormat('ll') }}</div>
            </div>
        </div>
    </x-bs::card.body>
</x-bs::card>

@push('footer_scripts')
    <script>
        new Chart(document.getElementById('orders-channel').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['{!! $ordersChannelToday->keys()->join("', '") !!}'],
                datasets: [{
                    data: [{!! $ordersChannelToday->map(fn($count, $channel) => "{channel: '$channel', count: $count}")->join(', ') !!}],
                    parsing: {
                        yAxisKey: 'count',
                        xAxisKey: 'channel'
                    },
                    fill: false,
                    borderColor: 'rgb(177, 136, 225)',
                    backgroundColor: 'rgba(177, 136, 225, 0.3)',
                    borderWidth: 1,
                }, {
                    data: [{!! $ordersChannelYesterday->map(fn($count, $channel) => "{channel: '$channel', count: $count}")->join(', ') !!}],
                    parsing: {
                        yAxisKey: 'count',
                        xAxisKey: 'channel'
                    },
                    fill: false,
                    borderColor: 'rgb(215,215,215)',
                    borderWidth: 1,
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grace: '5%',
                        ticks: {
                            precision:0
                        }
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
            }
        })
    </script>
@endpush