<?php

namespace Eshop\Livewire\Dashboard\Invoice;

use Eshop\Models\Invoice\Invoice;
use Eshop\Models\Invoice\UnitMeasurement;
use Eshop\Models\Product\Product;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class InvoiceRows extends Component
{
    public bool $showEditingModal = false;
    public bool $showVatModal     = false;

    public array $rows = [];

    public ?int  $editing_index = null;
    public array $editing_row   = [];

    public string $country;
    public float  $total_net_value  = 0;
    public float  $total_vat_amount = 0;
    public float  $total            = 0;
    public float  $vat              = 0;

    protected array $rules = [
        'editing_row.code'        => ['required', 'string'],
        'editing_row.description' => ['required', 'string'],
        'editing_row.unit'        => ['required', 'integer'],
        'editing_row.quantity'    => ['required', 'numeric', 'min:1'],
        'editing_row.price'       => ['required', 'numeric', 'min:0'],
        'editing_row.discount'    => ['required', 'numeric', 'min:0', 'max:1'],
        'editing_row.vat_percent' => ['required', 'numeric', 'min:0', 'max:1']
    ];

    protected $listeners = ['addProduct', 'setVatPercent', 'editVat'];

    public function mount(Invoice $invoice): void
    {
        $rows = $invoice->rows->sortBy('position')->toArray();

        $this->rows = old('rows', $rows);

        $this->updateTotals();
        $this->resetEditingRow();
    }

    public function addProduct(Product $product): void
    {
        $this->rows[] = [
            'id'          => '',
            'product_id'  => $product->id,
            'code'        => $product->sku,
            'description' => $product->trademark,
            'unit'        => match ($product->unit->name) {
                'piece'    => UnitMeasurement::Pieces,
                'meter'    => UnitMeasurement::Meters,
                'liter'    => UnitMeasurement::Liters,
                'kilogram' => UnitMeasurement::Kilos,
                'set'      => UnitMeasurement::Set
            },
            'quantity'    => 1,
            'price'       => round($product->price / (1 + $product->vat), 4),
            'discount'    => $product->discount,
            'vat_percent' => (isset($this->country) && $this->country !== 'GR') ? 0 : $product->vat
        ];

        $this->updateTotals();

        $this->editRow(count($this->rows) - 1);
    }

    public function setVatPercent(string $country): void
    {
        $this->country = $country;

        if ($country !== 'GR') {
            array_walk($this->rows, static fn(&$r) => $r['vat_percent'] = 0);
        }

        $this->updateTotals();
    }

    public function editRow(?int $index = null): void
    {
        $this->showEditingModal = true;

        $this->editing_index = $index;
        if ($index !== null) {
            $this->editing_row = $this->rows[$index];
        } else {
            $this->resetEditingRow();
        }

        $this->skipRender();
    }

    public function updateRow(): void
    {
        $this->validate();

        if ($this->editing_index === null) {
            $this->rows [] = $this->editing_row;
        } else {
            $this->rows[$this->editing_index] = array_merge($this->rows[$this->editing_index], $this->editing_row);
        }

        $this->editing_index = null;
        $this->resetEditingRow();
        $this->showEditingModal = false;

        $this->updateTotals();
    }

    public function deleteRow(int $index): void
    {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows);

        $this->updateTotals();
    }

    public function editVat(): void
    {
        $this->showVatModal = true;
    }

    public function updateVat(): void
    {
        $this->showVatModal = false;
        array_walk($this->rows, fn(&$r) => $r['vat_percent'] = round($this->vat, 2));
        $this->updateTotals();
    }

    public function render(): Renderable
    {
        return view('eshop::dashboard.invoice.wire.invoice-rows');
    }

    private function resetEditingRow(): void
    {
        $this->editing_row = [
            'id'          => '',
            'code'        => '',
            'unit'        => 1,
            'quantity'    => 1,
            'price'       => 0,
            'discount'    => 0,
            'vat_percent' => 0.24
        ];
    }

    private function updateTotals(): void
    {
        $rows = collect($this->rows);

        $values = $rows->groupBy(fn($r) => (string)$r['vat_percent'])
            ->map(fn($g) => round($g->sum(fn($r) => $r['quantity'] * round($r['price'] * (1 - $r['discount']), 4)), 2));

        $this->total_net_value = $values->sum();

        $this->total_vat_amount = $values->map(fn($v, $k) => round($v * $k, 2))->sum();
        $this->total = $this->total_net_value + $this->total_vat_amount;
    }
}