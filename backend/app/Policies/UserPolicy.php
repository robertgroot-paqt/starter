<?php

namespace App\Policies;

use App\Enums\Permissions\UserPermissions;
use App\Models\User;

class UserPolicy
{
    public function index(?User $user): bool
    {
        return $user?->hasPermissionTo(UserPermissions::VIEW_ANY) ?? true;
    }

    public function show(?User $user, User $model): bool
    {
        return $user?->hasPermissionTo(UserPermissions::VIEW) ?? true;
    }

    public function update(?User $user, User $model): bool
    {
        return $user?->hasPermissionTo(UserPermissions::UPDATE) ?? true;
    }

    public function delete(?User $user, User $model): bool
    {
        return $user?->hasPermissionTo(UserPermissions::UPDATE) ?? true;
    }
}
