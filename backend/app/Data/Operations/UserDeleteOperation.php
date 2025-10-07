<?php

namespace App\Data\Operations;

use App\Data\Base\Data;
use Illuminate\Support\Facades\Gate;

/** @extends Operation<UserData> */
class UserDeleteOperation extends Operation
{
    public string $name = 'delete';

    public function applicable(?Data $data): bool
    {
        if ($data === null) {
            return false;
        }

        return Gate::allows('delete', [$data->getFromModel()]);
    }
}
