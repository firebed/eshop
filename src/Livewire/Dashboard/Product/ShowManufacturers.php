<?php


namespace Eshop\Livewire\Dashboard\Product;


use Eshop\Models\Product\Manufacturer;
use Firebed\Livewire\Traits\Datatable\DeletesRows;
use Firebed\Livewire\Traits\Datatable\WithCRUD;
use Firebed\Livewire\Traits\Datatable\WithSelections;
use Firebed\Livewire\Traits\Datatable\WithSorting;
use Firebed\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

/**
 * Class ShowManufacturers
 * @package Eshop\Livewire\Dashboard\Product;
 *
 * @property LengthAwarePaginator manufacturers
 */
class ShowManufacturers extends Component
{
    use WithPagination;
    use WithSorting {
        queryString as sortingQueryString;
    }
    use SendsNotifications;
    use WithFileUploads;
    use WithSelections;
    use DeletesRows;
    use WithCRUD;

    public string $search     = "";
    public $image;

    protected array $rules = [
        'model.name' => ['required', 'string'],
        'model.slug' => ['required', 'string', 'unique:manufacturers,slug'],
    ];

    public function queryString(): array
    {
        return array_merge([
            'search' => ['except' => ''],
        ], $this->sortingQueryString());
    }

    protected function makeEmptyModel(): Manufacturer
    {
        return new Manufacturer();
    }

    protected function findModel($id): Manufacturer
    {
        return Manufacturer::find($id);
    }

    protected function deleteRows(): int
    {
        return Manufacturer::query()->whereKey($this->selected())->delete();
    }

    public function getManufacturersProperty()
    {
        return Manufacturer
            ::when($this->search, fn($q, $s) => $q->where('name', 'LIKE', "$s%"))
            ->when($this->sortField, fn($q, $s) => $q->orderBy($s, $this->sortDirection))
            ->paginate();
    }

    protected function getModels(): Collection
    {
        return $this->manufacturers->getCollection();
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.manufacturer.wire.show-manufacturers', [
            'manufacturers' => $this->manufacturers
        ]);
    }
}
