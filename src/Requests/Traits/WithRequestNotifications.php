<?php


namespace Eshop\Requests\Traits;


use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Illuminate\Contracts\Validation\Validator;

trait WithRequestNotifications
{
    use WithNotifications;

    protected function failedValidation(Validator $validator): void
    {
        //TODO Get the messages from the validator and show them to the user
//        $this->showWarningNotification(trans('eshop::notifications.validation_failed'));

        $this->showWarningNotification(implode('<br>', collect($validator->getMessageBag()->getMessages())->flatten()->all()));

        parent::failedValidation($validator);
    }
}