<?php

namespace App\Models;

use App\Models\Traits\HasPostFixedUlids;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasPostFixedUlids;
}
