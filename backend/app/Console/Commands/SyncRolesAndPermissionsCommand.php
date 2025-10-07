<?php

namespace App\Console\Commands;

use App\Enums\RoleEnum;
use App\Models\Role;
use Illuminate\Console\Command;

class SyncRolesAndPermissionsCommand extends Command
{
    protected $signature = 'app:roles-and-permissions:sync';

    protected $description = 'Command description';

    public function handle(): int
    {
        $roles = RoleEnum::values();

        foreach ($roles as $role) {
            Role::findOrCreate($role);
        }

        // TODO permissions. From config?

        return Command::SUCCESS;
    }
}
