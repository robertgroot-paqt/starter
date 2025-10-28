<?php

namespace App\Console\Commands;

use App\Enums\Permissions\UserPermissions;
use App\Enums\RoleEnum;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class SyncRolesAndPermissionsCommand extends Command
{
    protected $signature = 'app:roles-and-permissions:sync';

    protected $description = 'Command description';

    public function handle(): int
    {
        foreach (RoleEnum::values() as $role) {
            Role::findOrCreate($role);
        }

        $allPermissions = [
            ...UserPermissions::values(),
        ];
        foreach ([
            ...UserPermissions::values(),
        ] as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = Role::findByName(RoleEnum::ADMIN->value);
        $adminRole->syncPermissions($allPermissions);

        $admin = User::query()->firstOrFail();
        $admin->syncRoles(RoleEnum::ADMIN);

        return Command::SUCCESS;
    }
}
