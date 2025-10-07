<?php

namespace App\Data\Operations;

use App\Data\Base\Data;

/**
 * @template TData of Data
 */
class Operation extends Data
{
    public string $name;

    /** @param ?TData $data */
    public function applicable(?Data $data): bool
    {
        return true;
    }
}
