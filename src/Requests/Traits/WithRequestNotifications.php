<?php


namespace Eshop\Requests\Traits;


use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Illuminate\Contracts\Validation\Validator;

trait WithRequestNotifications
{
    use WithNotifications;

    protected function failedValidation(Validator $validator): void
    {
        $this->showWarningNotification(trans('eshop::notifications.validation_failed'));

        parent::failedValidation($validator);
    }
}