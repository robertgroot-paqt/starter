<?php

namespace App\Enums\Permissions;

enum UserPermissions: string
{
    case VIEW = 'view';
    case VIEW_ANY = 'view any';
    case UPDATE = 'update';
}
