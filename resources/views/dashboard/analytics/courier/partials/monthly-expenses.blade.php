<div class="vstack h-100">
    <div class="fw-500 mb-2 d-flex justify-content-between">
        <div>{{ __("eshop::analytics.courier") }} ({{ $year }})</div>
        <div class="d-flex gap-3">
            @for($i=$endingYear; $i>=$startingYear; $i--)
                @if($i === (int)$year)
                    <div class="fw-500">{{ $i }}</div>
                @else
                    <a href="{{ route('analytics.couriers.index', ['year' => $i]) }}">{{ $i }}</a>
                @endif
            @endfor
        </div>
    </div>

    <div class="graph">
        <canvas id="monthly-expenses"></canvas>
    </div>
</div>

@push('footer_scripts')
    <script>
        const colors = [
            'rgb(54, 162, 235)',
            'rgb(153, 102, 255)',
            'rgb(75, 192, 192)',
            'rgb(255, 99, 132)',
            'rgb(255, 159, 64)',
            'rgb(201, 203, 207)'
        ]

        const transparent = [
            'rgba(54, 162, 235, 0.3)',
            'rgba(153, 102, 255, 0.3)',
            'rgba(75, 192, 192, 0.3)',
            'rgba(255, 99, 132, 0.3)',
            'rgba(255, 159, 64, 0.3)',
            'rgba(201, 203, 207, 0.3)'
        ]

        new Chart(document.getElementById('monthly-expenses').getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! $months->toJson() !!},
                datasets: [
                    @foreach($couriers as $name => $months)
                    {
                        label: '{{ $name }}',
                        data: {!! $months->pluck('expenses')->map(fn($e) => sprintf("%.2f", $e))->toJson() !!},
                        borderColor: colors[{{ $loop->index }}],
                        backgroundColor: transparent[{{ $loop->index }}],
                        borderWidth: 2,
                        pointHoverRadius: 6,
                        pointRadius: 5,
                        fill: false,
                        pointBackgroundColor: 'white',
                        pointHoverBorderWidth: 2,
                    },
                    @endforeach
                ]
            },
            options: {
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