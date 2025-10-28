<?php

namespace App\Data\Operations;

use App\Data\Base\Data;
use App\Data\Responses\ResponseData;

/**
 * @template TData of ResponseData
 */
class Operation extends Data
{
    public string $name;

    /** @param ?TData $data */
    public function applicable(?ResponseData $data): bool
    {
        return true;
    }
}
