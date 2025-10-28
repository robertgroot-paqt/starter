<?php

namespace App\Data;

use App\Data\Responses\ResponseData;
use App\Models\Role;

class RoleData extends ResponseData
{
    public function __construct(
        public string $name,
    ) {}

    public static function fromModel(Role $role): self
    {
        return new self(
            name: $role->name,
        );
        // ->setFromModel($role);
    }
}
