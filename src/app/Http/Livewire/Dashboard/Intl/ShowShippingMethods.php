<?php


namespace App\Http\Livewire\Dashboard\Intl;


use App\Http\Livewire\Traits\TrimStrings;
use App\Models\Location\Country;
use App\Models\Location\CountryShippingMethod;
use App\Models\Location\ShippingMethod;
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
 * Class ShippingMethodsDashboard
 * @package App\Http\Livewire\Intl
 *
 * @property Collection shippingMethods
 */
class ShowShippingMethods extends Component
{
    use WithSorting {
        queryString as sortingQueryString;
    }
    use SendsNotifications;
    use WithSelections;
    use TrimStrings;
    use DeletesRows;
    use TogglesVisibility;
    use WithCRUD {
        save as crudSave;
        edit as crudEdit;
    }

    public string $visibility  = '';
    public string $method      = '';
    public string $country     = '';
    public string $description = '';

    protected array $rules = [
        'model.country_id'            => ['required', 'integer', 'exists:countries,id'],
        'model.shipping_method_id'    => ['required', 'integer', 'exists:shipping_methods,id'],
        'model.fee'                   => ['required', 'numeric', 'min:0'],
        'model.cart_total'            => ['required', 'numeric', 'min:0'],
        'model.weight_limit'          => ['required', 'numeric', 'min:0'],
        'model.weight_excess_fee'     => ['required', 'numeric', 'min:0'],
        'model.inaccessible_area_fee' => ['required', 'numeric', 'min:0'],
        'model.position'              => ['required', 'integer', 'min:0'],
        'model.visible'               => ['required', 'boolean'],
        'model.description_for_edit'  => ['nullable', 'string'],
    ];

    public function getQueryString(): array
    {
        return array_merge([
            'country'    => ['except' => ''],
            'method'     => ['except' => ''],
            'visibility' => ['except' => ''],
        ], $this->sortingQueryString());
    }

    protected function makeEmptyModel(): CountryShippingMethod
    {
        return new CountryShippingMethod([
            'country_id'            => $this->country,
            'shipping_method_id'    => '',
            'fee'                   => 0,
            'cart_total'            => 0,
            'weight_limit'          => 0,
            'weight_excess_fee'     => 0,
            'inaccessible_area_fee' => 0,
            'visible'               => TRUE,
        ]);
    }

    protected function findModel($id): CountryShippingMethod
    {
        return CountryShippingMethod::find($id);
    }

    protected function deleteRows(): int
    {
        return CountryShippingMethod::query()->whereKey($this->selected)->delete();
    }

    protected function updateVisibility($visible): int
    {
        return CountryShippingMethod::query()->whereKey($this->selected)->update(['visible' => $visible]);
    }

    public function getShippingMethodsProperty(): Collection
    {
        return CountryShippingMethod
            ::when($this->visibility !== '', fn($q) => $q->where('visible', $this->visibility))
            ->when($this->method, fn($q, $v) => $q->where('shipping_method_id', $v))
            ->when($this->country, fn($q, $v) => $q->where('country_id', $v))
            ->when($this->sortField, function ($q, $s) {
                if (!in_array($s, ['method', 'country'])) {
                    $q->orderBy($s, $this->sortDirection);
                }
            })
            ->with('shippingMethod', 'country', 'translations')
            ->get()
            ->when($this->sortField === 'method', fn(Collection $q) => $q->sortBy('shippingMethod.name', SORT_REGULAR, $this->sortDirection === 'desc'))
            ->when($this->sortField === 'country', fn(Collection $q) => $q->sortBy('country.name', SORT_REGULAR, $this->sortDirection === 'desc'));
    }

    protected function getModels(): Collection
    {
        return $this->shippingMethods;
    }

    public function edit(int $id): void
    {
        $this->crudEdit($id);
        $this->description = $this->model->description;
    }

    public function save(): void
    {
        $this->model->description = blank($this->description) ? NULL : trim($this->description);
        $this->crudSave();
    }

    public function render(): Renderable
    {
        return view('dashboard.intl.livewire.show-shipping-methods', [
            'shippingMethods' => $this->shippingMethods,
            'countries'       => Country::orderBy('name')->get(),
            'methods'         => ShippingMethod::all()
        ]);
    }
}
