<?php


namespace Eshop\Livewire\Dashboard\Intl;


use Eshop\Models\Location\Country;
use Firebed\Livewire\Traits\Datatable\DeletesRows;
use Firebed\Livewire\Traits\Datatable\TogglesVisibility;
use Firebed\Livewire\Traits\Datatable\WithCRUD;
use Firebed\Livewire\Traits\Datatable\WithSelections;
use Firebed\Livewire\Traits\Datatable\WithSorting;
use Firebed\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Class CountriesDashboard
 * @package Eshop\Livewire\Dashboard\Intl
 *
 * @property LengthAwarePaginator countries
 */
class ShowCountries extends Component
{
    use WithPagination;
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

    public function getCountriesProperty()
    {
        return Country
            ::when($this->search, fn($q, $s) => $q->where('name', 'LIKE', "$s%"))
            ->when($this->visibility !== '', fn($q) => $q->where('visible', $this->visibility))
            ->when($this->sortField, fn($q, $s) => $q->orderBy($s, $this->sortDirection))
            ->paginate();
    }

    protected function getModels(): Collection
    {
        return $this->countries->getCollection();
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.intl.wire.show-countries', [
            'countries' => $this->countries
        ]);
    }
}
