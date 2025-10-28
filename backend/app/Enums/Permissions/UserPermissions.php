<?php

namespace App\Enums\Permissions;

use App\Enums\HasEnumFunctions;

enum UserPermissions: string
{
    use HasEnumFunctions;

    case VIEW = 'view';
    case VIEW_ANY = 'view any';
    case UPDATE = 'update';
}
