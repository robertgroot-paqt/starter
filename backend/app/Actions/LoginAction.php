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

        /** @var User|null $user */
        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        if (! $user || ! Auth::getProvider()->validateCredentials($user, $credentials)) {
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
