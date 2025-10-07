<?php

namespace App\Data\Operations;

use App\Data\Base\Data;
use Illuminate\Support\Facades\Gate;

/** @extends Operation<UserData> */
class UserUpdateOperation extends Operation
{
    public string $name = 'update';

    public function applicable(?Data $data): bool
    {
        if ($data === null) {
            return false;
        }

        return Gate::allows('update', [$data->getFromModel()]);
    }
}
