<div class="vstack h-100">
    <div class="fw-500 mb-2">{{ __("eshop::analytics.total_orders") }} ({{ now()->isoFormat('MMMM') }})</div>

    <x-bs::card class="flex-grow-1">
        <x-bs::card.body>
            <div class="d-grid gap-3">
                <div class="fw-500 fs-4 text-blue-500">{{ $orders->sum() }}</div>

                <div class="graph">
                    <canvas id="total-orders"></canvas>
                </div>
            </div>
        </x-bs::card.body>
    </x-bs::card>
</div>

@push('footer_scripts')
    <script>
        const canvas = document.getElementById("total-orders");
        const ctx = canvas.getContext("2d");

        const horizontalLinePlugin = {
            id: 'horizontalLine',
            beforeDraw: function (chartInstance) {
                const yScale = chartInstance.scales["y"];

                let index;
                let line;
                let style;
                let yValue;

                if (chartInstance.options.horizontalLine) {
                    for (index = 0; index < chartInstance.options.horizontalLine.length; index++) {
                        line = chartInstance.options.horizontalLine[index];

                        if (!line.style) {
                            style = "#1ab8e8";
                        } else {
                            style = line.style;
                        }

                        if (line.y) {
                            yValue = yScale.getPixelForValue(line.y);
                        } else {
                            yValue = 0;
                        }

                        ctx.lineWidth = 1;

                        if (yValue) {
                            ctx.beginPath();
                            ctx.moveTo(0, yValue);
                            ctx.lineTo(canvas.width, yValue);
                            ctx.strokeStyle = style;
                            ctx.stroke();
                        }

                        if (line.text) {
                            ctx.fillStyle = style;
                            ctx.fillText(line.text, 0, yValue + ctx.lineWidth - 3);
                        }
                    }
                }
            }
        };
        Chart.register(horizontalLinePlugin);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! $orders->keys()->map(fn($key) => !str_contains($key, ' ') ? $key : explode(' ', $key))->toJson() !!},
                datasets: [
                    {
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
                "horizontalLine": [{
                    "y": {{ $orders->avg() }},
                    "style": "#ff6384",
                    "text": "ΜΟ"
                }],
                
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
        });
        
    </script>
@endpush