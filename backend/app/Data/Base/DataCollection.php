<?php

namespace App\Data\Base;

use App\Data\Responses\ResponseTransformationContext;
use Illuminate\Http\JsonResponse;
use IteratorAggregate;
use Spatie\LaravelData\DataCollection as SpatieDataCollection;

/**
 * @template TKey of array-key
 * @template TValue of Data
 *
 * @implements IteratorAggregate<TKey, TValue>
 */
class DataCollection extends SpatieDataCollection
{
    public function toResponse($request): JsonResponse
    {
        return new JsonResponse(
            data: $this->transform(ResponseTransformationContext::create()),
            status: $this->calculateResponseStatus($request),
        );
    }
}
