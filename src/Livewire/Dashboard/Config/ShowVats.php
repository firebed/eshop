<?php


namespace Eshop\Livewire\Dashboard\Config;


use Eshop\Models\Product\Vat;
use Firebed\Components\Livewire\Traits\Datatable\DeletesRows;
use Firebed\Components\Livewire\Traits\Datatable\WithCRUD;
use Firebed\Components\Livewire\Traits\Datatable\WithSelections;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Livewire\Component;

/**
 * Class ShowVats
 * @package Eshop\Livewire\Dashboard\Config
 *
 * @property Collection vats
 */
class ShowVats extends Component
{
    use SendsNotifications;
    use WithSelections;
    use DeletesRows;
    use WithCRUD;

    protected array $rules = [
        'model.name'   => ['required', 'string'],
        'model.regime' => ['required', 'numeric', 'min:0', 'max:1'],
    ];

    protected function makeEmptyModel(): Vat
    {
        return new Vat();
    }

    protected function findModel($id): Vat
    {
        return Vat::find($id);
    }

    protected function deleteRows(): int
    {
        return Vat::query()->whereKey($this->selected())->delete();
    }

    public function getVatsProperty(): Collection
    {
        return Vat::all();
    }

    protected function getModels(): Collection
    {
        return $this->vats;
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.config.wire.show-vats', [
            'vats' => $this->vats
        ]);
    }
}
