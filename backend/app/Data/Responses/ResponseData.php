<?php

namespace App\Data\Responses;

use App\Data\Base\CursorPaginatedDataCollection;
use App\Data\Base\Data;
use App\Data\Base\DataCollection;
use App\Data\Base\PaginatedDataCollection;
use App\Data\Operations\Operation;
use App\Models\Model;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use LogicException;
use Spatie\LaravelData\Support\Transformation\TransformationContext;

abstract class ResponseData extends Data
{
    public protected(set) Model $fromModel {
        get {
            if (! isset($this->fromModel)) {
                throw new LogicException('`fromModel` needs to be set. Usually this is done in the `fromModel` function.');
            }

            return $this->fromModel;
        }
    }

    public function setFromModel(Model $model): static
    {
        $this->fromModel = $model;

        return $this;
    }

    /**
     * @return ($items is AbstractPaginator ? PaginatedDataCollection : ($items is Paginator ? PaginatedDataCollection : ($items is AbstractCursorPaginator ? CursorPaginatedDataCollection : ($items is CursorPaginator ? CursorPaginatedDataCollection<TCollectKey, TData> : DataCollection<TCollectKey, TData>))))
     */
    public static function dataCollection(mixed $items): DataCollection|PaginatedDataCollection|CursorPaginatedDataCollection
    {
        // Use Laravel Data paginator classes instead of Laravel's default
        // so we can add additional data in responses.
        if ($items instanceof Paginator || $items instanceof AbstractPaginator) {
            return self::collect(
                $items,
                PaginatedDataCollection::class
            );
        }

        if ($items instanceof AbstractCursorPaginator || $items instanceof CursorPaginator) {
            return self::collect(
                $items,
                CursorPaginatedDataCollection::class
            );
        }

        return self::collect($items, DataCollection::class);
    }

    /** @return list<Operation> */
    public static function operations(): array
    {
        return [];
    }

    public function toResponse($request): JsonResponse
    {
        return new JsonResponse(
            data: $this->transform(self::getResponseTransformationContext()),
            status: $this->calculateResponseStatus($request),
        );
    }

    protected static function getResponseTransformationContext(): ?TransformationContext
    {
        return ResponseTransformationContext::create();
    }
}
