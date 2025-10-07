<?php

namespace App\Models;

use App\Models\Traits\HasPostFixedUlids;
use App\Models\Traits\HasQueryBuilder;
use Illuminate\Database\Eloquent\Model as EloquentModel;

abstract class Model extends EloquentModel
{
    use HasPostFixedUlids;
    use HasQueryBuilder;
}
