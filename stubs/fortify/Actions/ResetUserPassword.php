<?php

namespace App\Actions\Fortify;

use Eshop\Controllers\Dashboard\Traits\WithNotifications;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;
    use WithNotifications;

    /**
     * Validate and reset the user's forgotten password.
     *
     * @param mixed $user
     * @param array $input
     * @return void
     * @throws ValidationException
     */
    public function reset($user, array $input): void
    {
        Validator::make($input, [
            'password' => $this->passwordRules(),
        ])->validate();

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
        
        $this->showSuccessNotification(__("Your password has been reset"));
    }
}
