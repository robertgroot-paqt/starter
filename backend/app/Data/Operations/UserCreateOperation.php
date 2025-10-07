<?php

namespace App\Data\Operations;

use App\Data\Base\Data;

/** @extends Operation<UserData> */
class UserCreateOperation extends Operation
{
    public string $name = 'create';

    public function applicable(?Data $data): bool
    {
        return $data === null;
    }
}
