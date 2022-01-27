<?php

namespace Eshop\Livewire\Dashboard\Invoice;

use Eshop\Livewire\Traits\TrimStrings;
use Eshop\Models\Invoice\Client;
use Firebed\Components\Livewire\Traits\Datatable\DeletesRows;
use Firebed\Components\Livewire\Traits\Datatable\WithCRUD;
use Firebed\Components\Livewire\Traits\Datatable\WithSelections;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Livewire\Component;

class ShowClients extends Component
{
    use SendsNotifications;
    use WithSelections;
    use TrimStrings;
    use DeletesRows;
    use WithCRUD;

    public string $search = '';

    public function getClientsProperty(): LengthAwarePaginator
    {
        return Client
            ::when($this->search !== '', fn($q) => $q->where('name', 'LIKE', "%$this->search%")->orWhere('vat_number', 'LIKE', "$this->search%"))
            ->orderBy('name')
            ->paginate(30);
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.client.wire.show-clients', [
            'clients' => $this->clients,
        ]);
    }

    protected function rules(): array
    {
        return [
            'model.name'          => ['required', 'string'],
            'model.vat_number'    => ['required', 'string', 'regex:/^[A-Z]{0,2}[0-9]+$/', 'unique:clients,vat_number,' . $this->model->id],
            'model.tax_authority' => ['nullable', 'string'],
            'model.job'           => ['required', 'string'],
            'model.country'       => ['required', 'string', 'regex:/^[A-Z]{2}$/'],
            'model.city'          => ['required', 'string'],
            'model.street'        => ['required', 'string'],
            'model.street_number' => ['nullable', 'string'],
            'model.postcode'      => ['required', 'string'],
            'model.phone_number'  => ['required', 'string'],
        ];
    }

    protected function makeEmptyModel(): Client
    {
        return new Client([
            'country' => 'GR'
        ]);
    }

    protected function findModel($id): Client
    {
        return Client::find($id);
    }

    protected function deleteRows(): int
    {
        return Client::query()->whereKey($this->selected)->delete();
    }

    protected function getModels(): Collection
    {
        return $this->clients->getCollection();
    }
}
