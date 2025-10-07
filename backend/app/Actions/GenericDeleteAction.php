<?php

namespace App\Actions;

use App\Models\Model;

class GenericDeleteAction
{
    public function delete(Model $model): bool
    {
        return $model->delete();
    }
}
