<?php

namespace App\Http\Controllers\Api;

use App\Data\UserData;
use App\Http\Controllers\ApiController;
use App\Models\User;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Put;

/**
 * @extends ApiController<User, UserData>
 */
class UserController extends ApiController
{
    public function data(): string
    {
        return UserData::class;
    }

    public function model(): string
    {
        return User::class;
    }

    public function authorizeParameter(): string
    {
        return 'user';
    }

    /** @return PaginatedDataCollection<array-key,UserData> */
    #[Get('users')]
    public function index(): PaginatedDataCollection
    {
        return $this->fetchIndex();
    }

    #[Post('users')]
    public function store(UserData $userData): UserData
    {
        $user = $this->upsertAction->upsert(new User, $userData);

        return $this->fetchShow($user);
    }

    #[Get('users/{user}')]
    public function show(User $user): UserData
    {
        return $this->fetchShow($user);
    }

    #[Put('users/{user}')]
    public function update(User $user, UserData $userData): UserData
    {
        $user = $this->upsertAction->upsert($user, $userData);

        return $this->fetchShow($user);
    }

    #[Delete('users/{user}')]
    public function destroy(User $user): void
    {
        $this->deleteAction->delete($user);
    }
}
