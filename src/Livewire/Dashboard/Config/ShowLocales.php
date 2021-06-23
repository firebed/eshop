<?php


namespace Eshop\Livewire\Dashboard\Config;


use Eshop\Models\Lang\Locale;
use Firebed\Components\Livewire\Traits\Datatable\DeletesRows;
use Firebed\Components\Livewire\Traits\Datatable\WithCRUD;
use Firebed\Components\Livewire\Traits\Datatable\WithSelections;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Livewire\Component;

/**
 * Class ShowLocales
 * @package Eshop\Livewire\Dashboard\Config
 *
 * @property Collection locales
 */
class ShowLocales extends Component
{
    use SendsNotifications;
    use WithSelections;
    use DeletesRows;
    use WithCRUD;

    protected array $rules = [
        'model.name' => ['required', 'string', 'size:2'],
        'model.lang' => ['required', 'string'],
    ];

    protected function makeEmptyModel(): Locale
    {
        return new Locale();
    }

    protected function findModel($id): Locale
    {
        return Locale::find($id);
    }

    protected function deleteRows(): int
    {
        return Locale::query()->whereKey($this->selected())->delete();
    }

    public function getLocalesProperty(): Collection
    {
        return Locale::all();
    }

    protected function getModels(): Collection
    {
        return $this->locales;
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.config.wire.show-locales', [
            'locales' => $this->locales
        ]);
    }
}
