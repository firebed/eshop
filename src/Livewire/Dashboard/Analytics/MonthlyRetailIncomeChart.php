<?php

namespace Eshop\Livewire\Dashboard\Analytics;

use Eshop\Actions\Analytics\OrderAnalytics;
use Eshop\Models\Cart\Cart;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MonthlyRetailIncomeChart extends Component
{
    private const COLORS = [
        '26,115,232',
        '255,99,132',
        '75,192,192',
        '255,159,64',
        '153,102,255',
        '255,205,86',
    ];

    public array $years = [];
    public int   $min_year;

    public function mount(): void
    {
        $this->years = [today()->year];
        $this->min_year = Cart::submitted()
            ->where('status_id', '<', 6)
            ->where('channel', 'pos')
            ->whereExists(function($q) {
                $q->from('addresses')
                    ->whereRaw('addresses.addressable_id = carts.id')
                    ->where('addresses.addressable_type', 'cart')
                    ->where('addresses.cluster', 'shipping');
            })
            ->min(DB::raw('YEAR(submitted_at)'));
    }

    public function updatedYears(): void
    {
        $this->emit('update-retail-chart', ['datasets' => $this->getDatasets()->toArray()]);
    }

    private function dataset(string $label, array $data, $color): array
    {
        return [
            'label'                 => $label,
            'data'                  => $data,
            'borderColor'           => "rgb($color)",
            'backgroundColor'       => "rgba($color,0.3)",
            'borderWidth'           => 2,
            'pointHoverRadius'      => 6,
            'pointRadius'           => 5,
            'fill'                  => false,
            'pointBackgroundColor'  => 'white',
            'pointHoverBorderColor' => '#ff6384',
            'pointHoverBorderWidth' => 2,
        ];
    }

    private function getDatasets(): Collection
    {
        $analytics = new OrderAnalytics;

        $datasets = collect();

        $years = collect($this->years)->map(fn($y) => (int)$y)->sort()->toArray();
        foreach ($years as $i => $year) {
            $start = Carbon::create($year);
            $end = $start->copy()->endOfYear();

            $data = $analytics->totalRetailIncome($start, $end, '1 month');
            $data = $data->mapWithKeys(fn($v, $k) => [(string)str($k)->before(' ') => $v]);

            $label = count($this->years) === 1 ? "Έσοδα" : $year;

            $datasets->push($this->dataset($label, $data->toArray(), self::COLORS[$i] ?? '201,203,207'));

            if (count($this->years) === 1) {
                $data = $analytics->retailProfits($start, $end, '1 month');
                $data = $data->mapWithKeys(fn($v, $k) => [(string)str($k)->before(' ') => $v]);
                $datasets->push($this->dataset(__("Profits"), $data->toArray(), self::COLORS[2]));
            }
        }

        return $datasets;
    }

    public function render(): Renderable
    {
        $datasets = $this->getDatasets();
        $data = $datasets->pluck('data')->flatten();

        return view('eshop::dashboard.analytics.orders.wire.monthly-retail-income-chart', [
            'labels'       => [],
            'datasets'     => $datasets,
            'max_year'     => today()->year,
            'total_income' => $data->sum(),
            'avg_income'   => $data->avg(),
            'min_income'   => $data->filter()->min(),
            'max_income'   => $data->max(),
        ]);
    }
}