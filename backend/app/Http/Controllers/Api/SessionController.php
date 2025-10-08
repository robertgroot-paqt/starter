<?php

namespace App\Http\Controllers\Api;

use App\Actions\LoginAction;
use App\Actions\LogoutAction;
use App\Data\SessionCreateData;
use App\Data\UserData;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;

class SessionController extends Controller
{
    #[Get('session', middleware: ['auth:api'])]
    public function show(
        #[CurrentUser()]
        User $user,
    ): UserData {
        return UserData::from($user);
    }

    #[Post('session')]
    public function store(
        SessionCreateData $data,
        LoginAction $loginAction,
    ): UserData {
        $user = $loginAction->login($data);

        return $this->show($user);
    }

    #[Post('session/logout')]
    public function destroy(LogoutAction $logoutAction): void
    {
        $logoutAction->logout();
    }
}
