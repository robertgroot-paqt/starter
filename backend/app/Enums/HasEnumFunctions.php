<?php

namespace App\Enums;

use BackedEnum;

/**
 * @template TValue of int|string
 *
 * @mixin BackedEnum<TValue>
 */
trait HasEnumFunctions
{
    /** @return list<string> */
    public static function values(): array
    {
        return array_column(static::cases(), 'value');
    }
}
