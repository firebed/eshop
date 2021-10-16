<?php

namespace Eshop\Livewire\Dashboard\Cart;

use Eshop\Exports\CartsExport;
use Eshop\Livewire\Dashboard\Cart\Traits\WithCartOperators;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartStatus;
use Eshop\Models\Location\PaymentMethod;
use Eshop\Models\Location\ShippingMethod;
use Eshop\Models\User;
use Eshop\Repository\Contracts\CartContract;
use Firebed\Components\Livewire\Traits\Datatable\DeletesRows;
use Firebed\Components\Livewire\Traits\Datatable\WithCRUD;
use Firebed\Components\Livewire\Traits\Datatable\WithExports;
use Firebed\Components\Livewire\Traits\Datatable\WithSelections;
use Firebed\Components\Livewire\Traits\SendsNotifications;
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
    use WithPagination;
    use SendsNotifications;
    use WithSelections;
    use DeletesRows;
    use WithExports;
    use WithCartOperators;
    use WithCRUD;

    public const PER_PAGE = 20;

    public string $filter                  = "";
    public string $status                  = "";
    public string $editing_status          = "";
    public string $editing_cart_voucher_id = "";
    public string $editing_voucher         = "";
    public int    $per_page                = 0;
    public string $payment_method_id       = "";
    public string $shipping_method_id      = "";
    public bool   $showStatusModal         = false;
    public bool   $showVoucherModal        = false;

    protected $queryString = [
        'filter'             => ['except' => ''],
        'shipping_method_id' => ['except' => ''],
        'payment_method_id'  => ['except' => ''],
        'status'             => ['except' => ''],
        'per_page'           => ['except' => self::PER_PAGE],
    ];

    protected array $rules = [
        'model.shipping_method_id' => [],
        'model.shipping_fee'       => [],
        'model.payment_method_id'  => [],
        'model.payment_fee'        => [],
        'model.document'           => [],
        'model.channel'            => [],
    ];

//    protected $listeners = ['cartStatusUpdated' => '$refresh'];

    public function mount(): void
    {
        $this->per_page = session('carts_per_page', self::PER_PAGE);
    }

    public function updated($key): void
    {
        if (in_array($key, ['filter', 'shipping_method_id', 'payment_method_id'])) {
            $this->resetPage();
        }
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

    public function saveStatuses(CartContract $contract): void
    {
        $this->validate([
            'editing_status' => 'required|exists:cart_statuses,id',
        ]);

        $status = CartStatus::find($this->editing_status);

        DB::transaction(fn() => $contract->setBulkCartStatus($status, $this->selected()));

        $this->showStatusModal = false;
    }

    public function editVoucher(int $cart_id)
    {
        $this->editing_voucher = "";
        $this->editing_cart_voucher_id = $cart_id;
        $this->showVoucherModal = true;
    }

    public function saveVoucher(): void
    {
        $this->showVoucherModal = false;
        
        if (blank($this->editing_cart_voucher_id)) {
            return;
        }
        
        Cart::whereKey($this->editing_cart_voucher_id)->update(['voucher' => blank($this->editing_voucher) ? null : trim($this->editing_voucher)]);
    }
    
    public function clearFilters(): void
    {
        $this->reset();
    }

    public function updatedPerPage($value): void
    {
        session()->put('carts_per_page', $value);
    }

    public function getCartsProperty(): LengthAwarePaginator
    {
        return Cart
            ::submitted()
            ->when($this->filter, function ($q, $f) {
                return $q->where(fn($b) => $b->where('id', 'LIKE', "$f%")->orWhereHas('shippingAddress', fn($b) => $b->matchAgainst($f)));
            })
            ->when(user()?->cannot('Manage orders') && user()?->can('Manage assigned orders'), function ($q) {
                return $q->whereHas('operators', fn($b) => $b->where('user_id', user()->id));
            })
            ->with('shippingAddress', 'status', 'paymentMethod', 'shippingMethod', 'operators')
            ->when($this->status, fn($q, $s) => $q->where('status_id', $s))
            ->when($this->shipping_method_id, fn($q, $id) => $q->where('shipping_method_id', $id))
            ->when($this->payment_method_id, fn($q, $id) => $q->where('payment_method_id', $id))
            ->latest('submitted_at')
            ->paginate($this->per_page);
    }

    public function render(): Renderable
    {
        $employees = User::whereHas('roles', fn($q) => $q->whereName('Employee'))->get();

        return view('eshop::dashboard.cart.wire.show-carts', [
            'carts'           => $this->carts,
            'shippingMethods' => ShippingMethod::all(),
            'paymentMethods'  => PaymentMethod::all(),
            'statuses'        => CartStatus::all(),
            'employees'       => $employees,
        ]);
    }

    protected function deleteRows(): ?int
    {
        $contract = app(CartContract::class);
        $this->emit('cartStatusUpdated');
        return DB::transaction(fn() => $contract->deleteCarts($this->selected()));
    }

    protected function makeEmptyModel(): Cart
    {
        return new Cart([
            'submitted_at' => now()
        ]);
    }

    protected function findModel($id): Cart
    {
        // TODO: Implement findModel() method.
    }

    protected function export(): null|BinaryFileResponse
    {
        $export = new CartsExport($this->selected());
        return Excel::download($export, 'carts_' . now()->timestamp . '.xlsx');
    }

    protected function getModels(): Collection
    {
        return $this->carts->getCollection();
    }

    private function filterCarts($key = null, $value = null)
    {
        return $key === null
            ? $this->carts->pluck('id')->map(fn($id) => (string)$id)->all()
            : $this->carts->where($key, $value)->pluck('id')->map(fn($id) => (string)$id)->all();
    }
}
