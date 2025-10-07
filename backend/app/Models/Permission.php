<?php

namespace App\Models;

use App\Models\Traits\HasPostFixedUlids;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasPostFixedUlids;
}
