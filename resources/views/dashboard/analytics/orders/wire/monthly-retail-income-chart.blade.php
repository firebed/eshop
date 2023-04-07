<div class="vstack h-100">
    <div class="fw-500 mb-2">Φυσικό κατάστημα ({{ now()->year }})</div>

    <x-bs::card class="flex-grow-1">
        <x-bs::card.body class="vstack gap-3">
            <div class="table-responsive scrollbar">
                <div class="d-flex gap-4 mb-3">
                    @for($year = $min_year; $year <= $max_year; $year++)
                        <x-bs::input.checkbox wire:model="years" :value="$year">{{ $year }}</x-bs::input.checkbox>
                    @endfor
                </div>
                
                <div class="d-flex gap-5 text-nowrap">
                    <div class="vstack">
                        <div class="small text-secondary">Σύνολο</div>
                        <div class="fs-4">
                            {{ format_currency($total_income) }}
                        </div>
                    </div>
    
                    <div class="vstack">
                        <div class="small text-secondary">Μ.Ο.</div>
                        <div class="fs-4">
                            {{ format_currency($avg_income) }}
                        </div>
                    </div>
    
                    <div class="vstack">
                        <div class="small text-secondary">Ελάχιστο</div>
                        <div class="fs-4">
                            {{ format_currency($min_income) }}
                        </div>
                    </div>
    
                    <div class="vstack">
                        <div class="small text-secondary">Μέγιστο</div>
                        <div class="fs-4">
                            {{ format_currency($max_income) }}
                        </div>
                    </div>
                </div>
            </div>

            <div wire:ignore class="graph">
                <canvas id="monthly-retail-income"></canvas>
            </div>
        </x-bs::card.body>
    </x-bs::card>
</div>

@push('footer_scripts')
    <script>
        const monthlyRetailIncome = new Chart(document.getElementById('monthly-retail-income').getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: @json($datasets)
            },
            options: {
                plugins: {
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
                            maxTicksLimit: 6
                        },
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
            }
        })
        
        Livewire.on('update-retail-chart', data => {
            monthlyRetailIncome.data = data;
            monthlyRetailIncome.update();
        });
    </script>
@endpush