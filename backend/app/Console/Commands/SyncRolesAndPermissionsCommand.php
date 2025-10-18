<?php

namespace App\Console\Commands;

use App\Enums\Permissions\UserPermissions;
use App\Enums\RoleEnum;
use App\Models\Permission;
use App\Models\Role;
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

        foreach ([
            ...UserPermissions::values(),
        ] as $permission) {
            Permission::findOrCreate($permission);
        }

        return Command::SUCCESS;
    }
}
