<?php

namespace Eshop\Controllers\Dashboard\Analytics;

use Carbon\CarbonPeriod;
use Eshop\Actions\Analytics\CourierExpenses;
use Eshop\Controllers\Dashboard\Controller;
use Eshop\Models\Cart\Cart;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class CourierAnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:View analytics');
    }

    public function __invoke(Request $request, CourierExpenses $analytics): Renderable
    {
        if (panicking()) {
            abort(404);
        }
        
        $request->validate(['year' => ['nullable', 'integer']]);
        $year = $request->input('year', today()->year);

        $from = today()->setYear($year)->startOfYear();
        $to = today()->setYear($year)->endOfYear();

        $startingYear = Carbon::createFromTimeString(Cart::submitted()->min('submitted_at'))->year;
        $endingYear = now()->year;

        $couriers = $analytics->handle($from, $to);

        $interval = CarbonPeriod::create($from, '1 month', $to);
        $months = collect([]);
        foreach ($interval as $i) {
            $months->push($i->isoFormat('MMM'));

            foreach ($couriers as $name => $values) {
                if (!$values->has($i->month)) {
                    $couriers[$name][$i->month] = ['expenses' => 0];
                }
            }
        }

        $couriers = $couriers->map(fn(Collection $g) => $g->sortKeys());

        return $this->view('analytics.courier.index', [
            'startingYear' => $startingYear,
            'endingYear'   => $endingYear,
            'year'         => $year,
            'months'       => $months,
            'couriers'     => $couriers,
        ]);
    }
}
