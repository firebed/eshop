<?php


namespace App\Http\Livewire\Dashboard\Intl;


use App\Models\Location\Country;
use Firebed\Livewire\Traits\Datatable\DeletesRows;
use Firebed\Livewire\Traits\Datatable\TogglesVisibility;
use Firebed\Livewire\Traits\Datatable\WithCRUD;
use Firebed\Livewire\Traits\Datatable\WithSelections;
use Firebed\Livewire\Traits\Datatable\WithSorting;
use Firebed\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Livewire\Component;

/**
 * Class CountriesDashboard
 * @package App\Http\Livewire\Intl
 *
 * @property Collection countries
 */
class ShowCountries extends Component
{
    use WithSorting {
        queryString as sortingQueryString;
    }
    use SendsNotifications;
    use WithSelections;
    use DeletesRows;
    use TogglesVisibility;
    use WithCRUD;

    public string $search     = "";
    public string $visibility = '';

    protected array $rules = [
        'model.name'     => ['required', 'string'],
        'model.code'     => ['required', 'string', 'size:2'],
        'model.timezone' => ['nullable', 'string'],
        'model.visible'  => ['required', 'boolean'],
    ];

    public function queryString(): array
    {
        return array_merge([
            'search'     => ['except' => ''],
            'visibility' => ['except' => ''],
        ], $this->sortingQueryString());
    }

    protected function makeEmptyModel(): Country
    {
        return new Country(['visible' => true]);
    }

    protected function findModel($id): Country
    {
        return Country::find($id);
    }

    protected function deleteRows(): int
    {
        return Country::query()->whereKey($this->selected())->delete();
    }

    protected function updateVisibility($visible): int
    {
        return Country::query()->whereKey($this->selected)->update(['visible' => $visible]);
    }

    public function getCountriesProperty(): Collection
    {
        return Country
            ::when($this->search, fn($q, $s) => $q->where('name', 'LIKE', "$s%"))
            ->when($this->visibility !== '', fn($q) => $q->where('visible', $this->visibility))
            ->when($this->sortField, fn($q, $s) => $q->orderBy($s, $this->sortDirection))
            ->get();
    }

    protected function getModels(): Collection
    {
        return $this->countries;
    }

    public function render(): Renderable
    {
        return view('dashboard.intl.livewire.show-countries', [
            'countries' => $this->countries
        ]);
    }
}
