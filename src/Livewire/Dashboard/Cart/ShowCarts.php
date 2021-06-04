<?php

namespace Ecommerce\Livewire\Dashboard\Cart;

use Ecommerce\Exports\CartsExport;
use Ecommerce\Models\Cart\Cart;
use Ecommerce\Models\Cart\CartStatus;
use Ecommerce\Models\Location\PaymentMethod;
use Ecommerce\Models\Location\ShippingMethod;
use Ecommerce\Repository\Contracts\CartContract;
use Firebed\Livewire\Traits\Datatable\DeletesRows;
use Firebed\Livewire\Traits\Datatable\WithExports;
use Firebed\Livewire\Traits\Datatable\WithSelections;
use Firebed\Livewire\Traits\SendsNotifications;
use Firebed\Livewire\Traits\WithCustomPaginationView;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ShowCarts extends Component
{
    use WithPagination, WithCustomPaginationView {
        WithCustomPaginationView::paginationView insteadof WithPagination;
    }

    use SendsNotifications;
    use WithSelections;
    use DeletesRows;
    use WithExports;

    public const PER_PAGE = 20;

    public $filter;
    public $status             = "";
    public $editing_status     = "";
    public $per_page;
    public $payment_method_id  = "";
    public $shipping_method_id = "";
    public $showStatusModal    = false;

    protected $queryString = [
        'filter'             => ['except' => ''],
        'shipping_method_id' => ['except' => ''],
        'payment_method_id'  => ['except' => ''],
        'status'             => ['except' => ''],
        'per_page'           => ['except' => self::PER_PAGE],
    ];

    protected $listeners = ['cartStatusUpdated' => '$refresh'];

    public function mount(): void
    {
        $this->per_page = session('carts_per_page', self::PER_PAGE);
    }

    public function selectByStatus(int $status_id): void
    {
        $this->selected = $this->filterCarts('status_id', $status_id);
        $this->skipRender();
    }

    public function selectByShippingMethod(int $shipping_method_id): void
    {
        $this->selected = $this->filterCarts('shipping_method_id', $shipping_method_id);
        $this->skipRender();
    }

    public function editStatuses(): void
    {
        if ($this->doesntHaveSelections()) {
            $this->skipRender();
            $this->showWarningToast('No rows selected!');
            return;
        }

        $this->reset('status');
        $this->showStatusModal = true;
    }

    protected function deleteRows(): ?int
    {
        $contract = app(CartContract::class);
        return DB::transaction(fn() => $contract->deleteCarts($this->selected()));
    }

    public function saveStatuses(CartContract $contract): void
    {
        $this->validate([
            'editing_status' => 'required|exists:cart_statuses,id',
        ]);

        $status = CartStatus::find($this->editing_status);

        DB::transaction(fn() => $contract->setBulkCartStatus($status, $this->selected()));

        $this->showStatusModal = false;
    }

    private function filterCarts($key = NULL, $value = NULL)
    {
        return $key === NULL
            ? $this->carts->pluck('id')->map(fn($id) => (string)$id)->all()
            : $this->carts->where($key, $value)->pluck('id')->map(fn($id) => (string)$id)->all();
    }

    public function clearFilters(): void
    {
        $this->reset();
    }

    public function updatedPerPage($value): void
    {
        session()->put('carts_per_page', $value);
    }

    protected function export(): null|BinaryFileResponse
    {
        $export = new CartsExport($this->selected());
        return Excel::download($export, 'carts_' . now()->timestamp . '.xlsx');
    }

    public function getCartsProperty(): LengthAwarePaginator
    {
        return Cart
            ::submitted()
            ->when($this->filter, fn($q, $f) => $q->whereHas('shippingAddress', fn($b) => $b->matchAgainst($f)))
            ->when($this->status, fn($q, $s) => $q->where('status_id', $s))
            ->when($this->shipping_method_id, fn($q, $id) => $q->where('shipping_method_id', $id))
            ->when($this->payment_method_id, fn($q, $id) => $q->where('payment_method_id', $id))
            ->with('shippingAddress', 'status', 'paymentMethod', 'shippingMethod')
            ->latest('submitted_at')
            ->paginate($this->per_page);
    }

    protected function getModels(): Collection
    {
        return $this->carts->getCollection();
    }

    public function render(): Renderable
    {
        return view('com::dashboard.cart.livewire.show-carts', [
            'carts'           => $this->carts,
            'shippingMethods' => ShippingMethod::all(),
            'paymentMethods'  => PaymentMethod::all(),
            'statuses'        => CartStatus::all()
        ]);
    }
}
