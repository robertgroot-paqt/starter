<?php

namespace App\Data\Responses;

use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Support\Wrapping\WrapExecutionType;

class ResponseTransformationContext extends TransformationContext
{
    public bool $transformToResponse = true;

    public static function create(): self
    {
        $instance = new self;
        $instance->setWrapExecutionType();

        return $instance;
    }

    public function setWrapExecutionType(?WrapExecutionType $wrapExecutionType = null): TransformationContext
    {
        // Responses are always wrapped for this context
        parent::setWrapExecutionType(WrapExecutionType::Enabled);

        return $this;
    }
}
