<div class="vstack h-100">
    <div class="fw-500 mb-2">{{ __("eshop::analytics.order_statuses") }} ({{ now()->isoFormat('MMMM') }})</div>

    <x-bs::card class="flex-grow-1">
        <x-bs::card.body class="h-100 vstack gap-3">
            <div class="d-flex gap-4 text-nowrap table-responsive text-secondary scrollbar">
                @foreach($statuses as $status => $count)
                    <div class="vstack">
                        <div class="small">{{ __("eshop::cart.status.$status") }}</div>
                        <div class="fw-500 fs-4">{{ $count }}</div>
                    </div>
                @endforeach
            </div>

            <div class="graph">
                <canvas id="order-statuses"></canvas>
            </div>
        </x-bs::card.body>
    </x-bs::card>
</div>
@push('footer_scripts')
    <script>
        new Chart(document.getElementById('order-statuses').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: {!! $statuses->keys()->map(fn($k) => __("eshop::cart.status.$k"))->toJson() !!},
                datasets: [{
                    data: [{{ $statuses->join(', ') }}],
                    backgroundColor: [
                        @foreach($statuses->keys() as $status)
                            @if(in_array($status, ['approved', 'completed']))
                            '#4bc0c0',
                        @elseif($status === 'shipped')
                            '#36a2eb',
                        @elseif($status === 'held')
                            '#ffcd56',
                        @elseif(in_array($status, ['cancelled', 'rejected', 'returned']))
                            '#ff6384',
                        @endif
                        @endforeach
                    ],
                    hoverOffset: 4,
                },
                ]
            },
            options: {
                plugins: {
                    legend: {
                        // display: false,
                        position: 'right',
                    }
                },
                maintainAspectRatio: false,
            }
        })
    </script>
@endpush