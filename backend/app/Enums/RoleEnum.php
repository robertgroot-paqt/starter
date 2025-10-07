<?php

namespace App\Enums;

enum RoleEnum: string
{
    /** @use HasEnumFunctions<string> */
    use HasEnumFunctions;

    case ADMIN = 'admin';
}
