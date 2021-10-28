<div class="card shadow-sm p-3 h-100">
    <div class="fw-500 mb-3"><em class="fas fa-grip-vertical text-gray-500 me-2"></em>{{ __("Totals") }}</div>

    <div class="d-flex gap-3">
        <div class="col-3 d-grid">
            <div class="ratio ratio-1x1">
                <canvas id="totals-chart"></canvas>
            </div>
        </div>

        <div class="col d-grid gap-1">
            <div class="d-flex justify-content-between fw-bold">
                <div><em class="fas fa-square me-2" style="color: rgb(255, 99, 132)"></em>{{ __("Total") }}</div>
                <div>{{ format_currency($cart->total) }}</div>
            </div>
            
            <hr class="my-0 text-light">
            
            <div class="d-flex justify-content-between">
                <div><em class="fas fa-square me-2" style="color: rgb(255, 205, 86)"></em>{{ __("Total fees") }}</div>
                <div>{{ format_currency($cart->total_fees) }}</div>
            </div>
            
            <hr class="my-0 text-light">
            
            <div class="d-flex justify-content-between">
                <div><em class="fas fa-square me-2" style="color: rgb(54, 162, 235)"></em>{{ __("Profits") }}</div>
                <div>{{ format_currency($profits) }}</div>
            </div>
        </div>
    </div>
</div>

@push('footer_scripts')
    <script>
        const ctx = document.getElementById('totals-chart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [
                    '{{ __("Total") }}',
                    '{{ __("Total fees") }}',
                    '{{ __("Profits") }}'
                ],
                datasets: [{
                    data: [{{ $cart->total }}, {{ $cart->total_fees }}, {{ number_format($profits, 2) }}],
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 205, 86)',
                        'rgb(54, 162, 235)'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                cutout: '60%',
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
            }
        });
    </script>
@endpush