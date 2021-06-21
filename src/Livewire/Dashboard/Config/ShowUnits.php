<?php


namespace Eshop\Livewire\Dashboard\Config;


use Eshop\Models\Product\Unit;
use Firebed\Components\Livewire\Traits\Datatable\DeletesRows;
use Firebed\Components\Livewire\Traits\Datatable\WithCRUD;
use Firebed\Components\Livewire\Traits\Datatable\WithSelections;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Livewire\Component;

/**
 * Class ShowUnits
 * @package Eshop\Livewire\Dashboard\Config
 *
 * @property Collection units
 */
class ShowUnits extends Component
{
    use SendsNotifications;
    use WithSelections;
    use DeletesRows;
    use WithCRUD;

    protected array $rules = [
        'model.name' => ['required', 'string'],
    ];

    protected function makeEmptyModel(): Unit
    {
        return new Unit();
    }

    protected function findModel($id): Unit
    {
        return Unit::find($id);
    }

    protected function deleteRows(): int
    {
        return Unit::query()->whereKey($this->selected())->delete();
    }

    public function getUnitsProperty(): Collection
    {
        return Unit::all();
    }

    protected function getModels(): Collection
    {
        return $this->units;
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.config.wire.show-units', [
            'units' => $this->units
        ]);
    }
}
