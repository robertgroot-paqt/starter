<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Support\Str;

trait HasPostFixedUlids
{
    use HasUlids {
        newUniqueId as originalNewUniqueId;
        isValidUniqueId as originalIsValidUniqueId;
    }

    protected const ID_SEPARATOR = '_';

    protected static function getUlidPostFix(): string
    {
        return Str::snake(class_basename(static::class));
    }

    public function newUniqueId(): string
    {
        return $this->originalNewUniqueId()
            .static::ID_SEPARATOR
            .static::getUlidPostFix();
    }

    protected function isValidUniqueId($value): bool
    {
        if (! is_string($value)) {
            return false;
        }

        [$id, $postfix] = explode(static::ID_SEPARATOR, $value);

        return $this->originalIsValidUniqueId($id) && $postfix === static::getUlidPostFix();
    }
}
