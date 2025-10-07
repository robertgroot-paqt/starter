<?php

namespace App\Actions;

use App\Data\Base\Data;
use App\Models\Model;

class GenericUpsertAction
{
    public function upsert(Model $model, Data $data): Model
    {
        $model = $model->fill($data->toArray());

        $model->save();

        return $model;
    }
}
