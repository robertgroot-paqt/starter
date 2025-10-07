<?php

namespace App\Data\Responses;

use Spatie\LaravelData\Contracts\BaseData;
use Spatie\LaravelData\Contracts\TransformableData;
use Spatie\LaravelData\Resolvers\TransformedDataResolver;
use Spatie\LaravelData\Support\Transformation\TransformationContext;

class ResponseDataResolver extends TransformedDataResolver
{
    public function execute(BaseData&TransformableData $data, TransformationContext $context): array
    {
        $transformed = parent::execute($data, $context);

        if (
            $context instanceof ResponseTransformationContext
            && $context->transformToResponse
        ) {
            [$transformed] = ResponseDataPipeline::create()
                ->send([$transformed, $data, $context])
                ->thenReturn();
        }

        return $transformed;
    }
}
