<?php

namespace Eshop\Livewire\Dashboard\Cart\Traits;

trait AssignsCartToUsers
{
    public bool  $showAssignmentsModal = false;
    public array $employee_ids         = [];

    public function showAssignmentsModal(): void
    {
        $this->showAssignmentsModal = true;
        $this->skipRender();
    }

    public function saveAssignments(): void
    {
        $this->cart->assignedUsers()->sync($this->employee_ids);
        
        $this->showAssignmentsModal = false;
        $this->showSuccessToast('Assignments saved!');
    }
}