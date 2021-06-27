<x-bs::card class="h-100">
    <x-bs::card.body>
        <div class="d-grid gap-2">
            <div class="d-flex justify-content-between">
                <div class="fw-bold">{{ __("Total sales") }}</div>
                <a href="#" class="text-decoration-none">{{ __("View report") }}</a>
            </div>

            <div class="fw-500 fs-4">{{ format_currency($totalSalesToday->sum()) }}</div>

            <div class="fw-500">{{ __("Sales over time") }}</div>

            <div class="ratio ratio-16x9">
                <canvas id="total-sales"></canvas>
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
        new Chart(document.getElementById('total-sales').getContext('2d'), {
            type: 'bar',
            data: {
                labels: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23],
                datasets: [{
                    data: [{{ $totalSalesToday->map(fn($total, $hour) => "{hour: $hour, total: $total}")->join(', ') }}],
                    parsing: {
                        yAxisKey: 'total',
                        xAxisKey: 'hour'
                    },
                    fill: false,
                    borderColor: 'rgb(177, 136, 225)',
                    backgroundColor: 'rgba(177, 136, 225, 0.3)',
                    borderWidth: 1,
                }, {
                    data: [{{ $totalSalesYesterday->map(fn($total, $hour) => "{hour: $hour, total: $total}")->join(', ') }}],
                    parsing: {
                        yAxisKey: 'total',
                        xAxisKey: 'hour'
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
                    x: {
                        beginAtZero: true,
                        ticks: {
                            maxTicksLimit: 12
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grace: '5%',
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
            }
        })
    </script>
@endpush