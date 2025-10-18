<?php

namespace App\Data\Typescript;

use ReflectionClass;
use Spatie\TypeScriptTransformer\Collectors\DefaultCollector;
use Spatie\TypeScriptTransformer\Structures\TransformedType;
use Spatie\TypeScriptTransformer\TypeReflectors\ClassTypeReflector;

class ApiControllerDataCollector extends DefaultCollector
{
    public function getTransformedType(ReflectionClass $class): ?TransformedType
    {
        if (! (ApiControllerCollector::$visitedClasses[$class->getName()] ?? false)
        ) {
            return null;
        }

        $reflector = ClassTypeReflector::create($class);

        $transformedType = $reflector->getType()
            ? $this->resolveAlreadyTransformedType($reflector)
            : $this->resolveTypeViaTransformer($reflector);

        if ($reflector->isInline()) {
            $transformedType->name = null;
            $transformedType->isInline = true;
        }

        return $transformedType;
    }
}
