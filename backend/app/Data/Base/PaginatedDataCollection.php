<?php

namespace App\Data\Base;

use App\Data\Responses\ResponseTransformationContext;
use Illuminate\Http\JsonResponse;
use IteratorAggregate;
use Spatie\LaravelData\PaginatedDataCollection as SpatieDataPaginatedDataCollection;

/**
 * @template TKey of array-key
 * @template TValue of Data
 *
 * @implements IteratorAggregate<TKey, TValue>
 */
class PaginatedDataCollection extends SpatieDataPaginatedDataCollection
{
    public function toResponse($request): JsonResponse
    {
        return new JsonResponse(
            data: $this->transform(ResponseTransformationContext::create()),
            status: $this->calculateResponseStatus($request),
        );
    }
}
