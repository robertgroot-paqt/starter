<?php

namespace App\Data\Operations;

use App\Data\Responses\ResponseData;
use Illuminate\Support\Facades\Gate;

/** @extends Operation<UserData> */
class UserDeleteOperation extends Operation
{
    public string $name = 'delete';

    public function applicable(?ResponseData $data): bool
    {
        if ($data === null) {
            return false;
        }

        return Gate::allows('delete', [$data->fromModel]);
    }
}
