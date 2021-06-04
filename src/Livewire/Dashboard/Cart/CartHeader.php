<?php


namespace Ecommerce\Livewire\Dashboard\Cart;


use Ecommerce\Livewire\Dashboard\Cart\Traits\ManagesVoucher;
use Ecommerce\Livewire\Dashboard\Cart\Traits\UpdatesCartStatus;
use Ecommerce\Livewire\Traits\TrimStrings;
use Ecommerce\Models\Cart\Cart;
use Ecommerce\Models\Cart\CartStatus;
use Ecommerce\Repository\Contracts\CartContract;
use Dompdf\Dompdf;
use Firebed\Livewire\Traits\SendsNotifications;
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

    public Cart $cart;
    public bool $showConfirmDelete = false;

    public function exportToPdf(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $pdf = new Dompdf(['enable_remote' => true]);
            $pdf->loadHtml(view('dashboard.cart.printer.print', ['cart' => $this->cart]));

            $pdf->render();
            $this->showSuccessToast('Download successful!');
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
    }

    public function render(): Renderable
    {
        $statuses = [];

        if ($this->cart->status_id) {
            $statuses = CartStatus::orderBy('group')->get();
            $status = $statuses->find($this->cart->status_id);
            $statuses = $statuses->except([CartStatus::SUBMITTED, $status->id])->groupBy('group');
        }

        return view('com::dashboard.cart.livewire.cart-header', [
            'statuses' => $statuses,
            'status'   => $status ?? NULL,
        ]);
    }
}
