<?php

namespace App\Data\Operations;

use App\Data\Responses\ResponseData;

/** @extends Operation<UserData> */
class UserCreateOperation extends Operation
{
    public string $name = 'create';

    public function applicable(?ResponseData $data): bool
    {
        return $data === null;
    }
}
