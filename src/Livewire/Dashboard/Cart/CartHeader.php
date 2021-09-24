<?php


namespace Eshop\Livewire\Dashboard\Cart;


use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Eshop\Livewire\Dashboard\Cart\Traits\ManagesVoucher;
use Eshop\Livewire\Dashboard\Cart\Traits\UpdatesCartStatus;
use Eshop\Livewire\Traits\TrimStrings;
use Eshop\Models\Cart\Cart;
use Eshop\Models\Cart\CartStatus;
use Eshop\Repository\Contracts\CartContract;
use Dompdf\Dompdf;
use Firebed\Components\Livewire\Traits\SendsNotifications;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CartHeader extends Component
{
    use SendsNotifications;
    use TrimStrings;
    use ManagesVoucher;
    use UpdatesCartStatus;
    use WithNotifications;

    public Cart $cart;
    public bool $showConfirmDelete = false;

    public function exportToPdf(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $pdf = new Dompdf(['enable_remote' => true]);
            $pdf->loadHtml(view('order-printer.print', ['cart' => $this->cart]));

            $pdf->render();
            $this->showSuccessToast('Εκτύπωση επιτυχής!');
            echo $pdf->output();
        }, 'order-' . $this->cart->id . '.pdf');
    }

    public function confirmDelete(): void
    {
        $this->showConfirmDelete = true;
        $this->skipRender();
    }

    public function deleteCart(CartContract $contract): void
    {
        DB::transaction(fn() => $contract->deleteCart($this->cart));
        $this->redirectRoute('carts.index');
        $this->showSuccessNotification('Η παραγγελία διαγράφηκε!');
    }

    public function render(): Renderable
    {
        $statuses = [];

        if ($this->cart->status_id) {
            $statuses = CartStatus::orderBy('group')->get();
            $status = $statuses->find($this->cart->status_id);
            $statuses = $statuses->except([CartStatus::SUBMITTED, $status?->id])->groupBy('group');
        }

        return view('eshop::dashboard.cart.wire.cart-header', [
            'statuses' => $statuses,
            'status'   => $status ?? NULL,
        ]);
    }
}
