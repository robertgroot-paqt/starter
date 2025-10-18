<?php

namespace App\Data\Typescript;

use Spatie\TypeScriptTransformer\Structures\TransformedType;

class TransformedConst extends TransformedType
{
    public function toString(): string
    {
        if ($this->keyword === 'const') {
            return "const {$this->name} = {$this->transformed}";
        }

        return parent::toString();
    }
}
