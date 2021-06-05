<?php


namespace Eshop\Livewire\Dashboard\Intl;


use Eshop\Livewire\Traits\TrimStrings;
use Eshop\Models\Location\Country;
use Eshop\Models\Location\CountryPaymentMethod;
use Eshop\Models\Location\PaymentMethod;
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
 * @property Collection paymentMethods
 */
class ShowPaymentMethods extends Component
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

    public string $visibility = '';
    public string $method     = '';
    public string $country    = '';

    public $description = "";

    protected array $rules = [
        'model.id'                => ['nullable', 'integer'],
        'model.country_id'        => ['required', 'integer', 'exists:countries,id'],
        'model.payment_method_id' => ['required', 'integer', 'exists:payment_methods,id'],
        'model.fee'               => ['required', 'numeric', 'min:0'],
        'model.cart_total'        => ['required', 'numeric', 'min:0'],
        'model.position'          => ['required', 'integer', 'min:0'],
        'model.visible'           => ['required', 'boolean'],
        'description'             => ['nullable', 'string'],
    ];

    public function getQueryString(): array
    {
        return array_merge([
            'country'    => ['except' => ''],
            'method'     => ['except' => ''],
            'visibility' => ['except' => ''],
        ], $this->sortingQueryString());
    }

    protected function makeEmptyModel(): CountryPaymentMethod
    {
        return new CountryPaymentMethod([
            'country_id'        => $this->country,
            'payment_method_id' => '',
            'fee'               => 0,
            'cart_total'        => 0,
            'visible'           => TRUE,
        ]);
    }

    protected function findModel($id): CountryPaymentMethod
    {
        return CountryPaymentMethod::find($id);
    }

    protected function deleteRows(): int
    {
        return CountryPaymentMethod::query()->whereKey($this->selected)->delete();
    }

    protected function updateVisibility($visible): int
    {
        return CountryPaymentMethod::query()->whereKey($this->selected)->update(['visible' => $visible]);
    }

    public function getPaymentMethodsProperty(): Collection
    {
        return CountryPaymentMethod
            ::when($this->visibility !== '', fn($q) => $q->where('visible', $this->visibility))
            ->when($this->method, fn($q, $v) => $q->where('payment_method_id', $v))
            ->when($this->country, fn($q, $v) => $q->where('country_id', $v))
            ->when($this->sortField, function ($q, $s) {
                if (!in_array($s, ['method', 'country'])) {
                    $q->orderBy($s, $this->sortDirection);
                }
            })
            ->with('paymentMethod', 'country', 'translations')
            ->get()
            ->when($this->sortField === 'method', fn(Collection $q) => $q->sortBy('paymentMethod.name', SORT_REGULAR, $this->sortDirection === 'desc'))
            ->when($this->sortField === 'country', fn(Collection $q) => $q->sortBy('country.name', SORT_REGULAR, $this->sortDirection === 'desc'));
    }

    protected function getModels(): Collection
    {
        return $this->paymentMethods;
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
        return view('eshop::dashboard.intl.wire.show-payment-methods', [
            'paymentMethods' => $this->paymentMethods,
            'countries'      => Country::orderBy('name')->get(),
            'methods'        => PaymentMethod::all()
        ]);
    }
}
