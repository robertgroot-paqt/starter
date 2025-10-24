<?php

namespace App\Data\Operations;

use App\Data\Responses\ResponseData;
use Illuminate\Support\Facades\Gate;

/** @extends Operation<UserData> */
class UserUpdateOperation extends Operation
{
    public string $name = 'update';

    public function applicable(?ResponseData $data): bool
    {
        if ($data === null) {
            return false;
        }

        return Gate::allows('update', [$data->fromModel]);
    }
}
