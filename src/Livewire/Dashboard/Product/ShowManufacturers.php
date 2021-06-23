<?php


namespace Eshop\Livewire\Dashboard\Product;


use Eshop\Models\Product\Manufacturer;
use Firebed\Components\Livewire\Traits\Datatable\DeletesRows;
use Firebed\Components\Livewire\Traits\Datatable\WithCRUD;
use Firebed\Components\Livewire\Traits\Datatable\WithSelections;
use Firebed\Components\Livewire\Traits\Datatable\WithSorting;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

/**
 * Class ShowManufacturers
 * @package Eshop\Livewire\Dashboard\Product
 *
 * @property LengthAwarePaginator manufacturers
 */
class ShowManufacturers extends Component
{
    use AuthorizesRequests;
    use WithPagination;
    use WithSorting {
        queryString as sortingQueryString;
    }
    use SendsNotifications;
    use WithFileUploads;
    use WithSelections;
    use DeletesRows;
    use WithCRUD;

    public string $search = "";
    public        $image;

    protected function rules(): array
    {
        return [
            'model.name' => ['required', 'string'],
            'model.slug' => $this->model->id
                ? ['required', 'string', 'max:70', Rule::unique('manufacturers', 'slug')->ignore($this->model)]
                : ['required', 'string', 'max:70', Rule::unique('manufacturers', 'slug')]
        ];
    }

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
        Manufacturer::findMany($this->selected())->each->delete();
        return $this->countSelected();
    }

    public function getManufacturersProperty()
    {
        return Manufacturer
            ::when($this->search, fn($q, $s) => $q->where('name', 'LIKE', "$s%"))
            ->when($this->sortField, fn($q, $s) => $q->orderBy($s, $this->sortDirection))
            ->paginate();
    }

    public function save(): void
    {
        $this->validate();

        DB::transaction(function () {
            if ($this->model->save()) {
                $this->saveImage();
            }
        });

        $this->showSuccessToast('Model saved!');
        $this->showEditingModal = FALSE;
    }

    public function saveImage(): void
    {
        if (!is_null($this->image)) {
            $model = $this->model;

            $image = $model->image;
            if ($image) {
                $image->delete();
            }

            $model->saveImage($this->image);
        }
    }

    public function updatedModelName(): void
    {
        if ($this->model->id === NULL) {
            $this->model->slug = slugify($this->model->name, '_');
        }
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
