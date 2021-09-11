<?php


namespace Eshop\Livewire\Dashboard\Intl;


use Eshop\Livewire\Traits\TrimStrings;
use Eshop\Models\Location\Country;
use Eshop\Models\Location\InaccessibleArea;
use Firebed\Components\Livewire\Traits\Datatable\DeletesRows;
use Firebed\Components\Livewire\Traits\Datatable\WithCRUD;
use Firebed\Components\Livewire\Traits\Datatable\WithSelections;
use Firebed\Components\Livewire\Traits\Datatable\WithSorting;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Class CountriesDashboard
 * @package App\Http\Livewire\Intl
 *
 * @property LengthAwarePaginator inaccessibleAreas
 */
class InaccessibleAreas extends Component
{
    use WithSorting {
        queryString as sortingQueryString;
    }
    use WithPagination;
    use SendsNotifications;
    use WithSelections;
    use TrimStrings;
    use DeletesRows;
    use WithCRUD;

    public int    $shipping_method_id;
    public string $visibility = '';
    public string $method     = '';
    public string $country    = '';
    public string $postcode   = '';

    protected array $rules = [
        'model.shipping_method_id' => ['required', 'integer', 'exists:shipping_methods,id'],
        'model.country_id'         => ['nullable', 'integer', 'exists:countries,id'],
        'model.region'             => ['nullable', 'string'],
        'model.type'               => ['required', 'string', 'in:ΔΠ,ΔΧ,D1,D2,D3'],
        'model.courier_store'      => ['nullable', 'string'],
        'model.courier_county'     => ['nullable', 'string'],
        'model.courier_address'    => ['nullable', 'string'],
        'model.courier_phone'      => ['nullable', 'string'],
        'model.postcode'           => ['required', 'string'],
    ];

    public function getQueryString(): array
    {
        return array_merge([
            'country'    => ['except' => ''],
            'postcode'   => ['except' => ''],
            'visibility' => ['except' => ''],
        ], $this->sortingQueryString());
    }

    public function getInaccessibleAreasProperty(): LengthAwarePaginator
    {
        return InaccessibleArea
            ::when($this->visibility !== '', fn($q) => $q->where('shippable', $this->visibility))
            ->where('shipping_method_id', $this->shipping_method_id)
            ->when($this->country, fn($q, $v) => $q->where('country_id', $v))
            ->when($this->postcode, fn($q, $v) => $q->where('postcode', $v))
            ->when($this->sortField, fn($q, $s) => $q->orderBy($s, $this->sortDirection))
            ->with('country')
            ->paginate(20);
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.shipping-methods.wire.inaccessible-areas', [
            'inaccessibleAreas' => $this->inaccessibleAreas,
            'countries'         => Country::orderBy('name')->get(),
        ]);
    }

    protected function makeEmptyModel(): InaccessibleArea
    {
        return new InaccessibleArea([
            'country_id'         => 1,
            'shipping_method_id' => $this->shipping_method_id,
            'type'               => 'ΔΠ'
        ]);
    }

    protected function findModel($id): InaccessibleArea
    {
        return InaccessibleArea::find($id);
    }

    protected function deleteRows(): int
    {
        return InaccessibleArea::query()->whereKey($this->selected)->delete();
    }

    protected function getModels(): Collection
    {
        return $this->inaccessibleAreas->getCollection();
    }
}
