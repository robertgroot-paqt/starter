<?php

namespace App\Data;

use App\Data\Base\Data;
use App\Models\Role;

class RoleData extends Data
{
    public function __construct(
        public string $name,
    ) {}

    public static function fromModel(Role $user): self
    {
        return new self(
            name: $user->name,
        );
    }
}
