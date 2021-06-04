<?php


namespace App\Http\Livewire\Dashboard\Cart\Traits;


use App\Http\Livewire\Traits\TrimStrings;
use App\Models\Cart\CartStatus;
use App\Repository\Contracts\CartContract;
use Illuminate\Support\Facades\DB;

trait UpdatesCartStatus
{
    use TrimStrings;

    public $new_status_id;
    public $notify_customer   = false;
    public $notes_to_customer = "";
    public $showStatusModal   = false;

    public $new_status;

    public function editCartStatus(CartStatus $status): void
    {
        $this->reset('notes_to_customer');

        $this->new_status = $status;
        $this->new_status_id = $status->id;
        $this->notify_customer = $status->notify;
        $this->showStatusModal = true;
    }

    public function resetStatus(CartContract $contract): void
    {
        $contract->resetStatus($this->cart);
        $this->showSuccessToast('Cart status reset successfully!');
    }

    public function saveStatus(CartContract $contract): void
    {
        $this->validate([
            'notify_customer'   => 'required|boolean',
            'notes_to_customer' => 'nullable|string'
        ]);

        DB::transaction(fn() => $contract->updateCartStatus($this->cart, CartStatus::find($this->new_status_id), $this->notify_customer, $this->trim($this->notes_to_customer)));

        $this->emit('cart-status-updated');
        $this->showStatusModal = false;
        $this->showSuccessToast('Cart status updated!');
    }
}
