<?php

namespace App\Data\Responses;

use Spatie\LaravelData\Resolvers\TransformedDataCollectableResolver;
use Spatie\LaravelData\Support\Transformation\TransformationContext;

class ResponseDataCollectableResolver extends TransformedDataCollectableResolver
{
    public function execute(
        iterable $items,
        TransformationContext $context,
    ): array {
        $transformed = parent::execute($items, $context);

        if (
            $context instanceof ResponseTransformationContext
            && $context->transformToResponse
        ) {
            [$transformed] = ResponseDataPipeline::create()
                ->send([$transformed, $items, $context])
                ->thenReturn();
        }

        return $transformed;
    }
}
