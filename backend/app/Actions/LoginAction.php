<?php

namespace App\Actions;

use App\Data\SessionCreateData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginAction
{
    public function __construct(
        protected Request $request,
    ) {}

    public function login(SessionCreateData $data): User
    {
        $credentials = $data->toCredentialsArray();

        $authProvider = Auth::getProvider();

        /** @var User|null $user */
        $user = $authProvider->retrieveByCredentials($credentials);

        if (! $user || ! $authProvider->validateCredentials($user, $credentials)) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        Auth::login($user, $data->remember);

        if ($this->request->hasSession()) {
            $this->request->session()->regenerate();
        }

        return $user;
    }
}
