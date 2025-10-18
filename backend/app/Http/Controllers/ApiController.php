<?php

namespace App\Http\Controllers;

use App\Actions\GenericDeleteAction;
use App\Actions\GenericUpsertAction;
use App\Data\Base\Data;
use App\Models\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @template TModel of Model
 * @template TData of Data
 */
abstract class ApiController extends Controller
{
    public function __construct(
        protected GenericUpsertAction $upsertAction,
        protected GenericDeleteAction $deleteAction,
    ) {}

    /**
     * @return class-string<TModel>
     */
    abstract public function model(): string;

    /**
     * @return class-string<TData>
     */
    abstract public function data(): string;

    abstract public function authorizeParameter(): string;

    /**
     * @return PaginatedDataCollection<array-key,TData>
     */
    protected function fetchIndex()
    {
        return $this->data()::dataCollection(
            $this->queryBuilder()->paginate()
        );
    }

    protected function fetchShow(Model $model): Data
    {
        return $this->data()::from(
            $this->queryBuilder()->findOrFail($model->getKey())
        );
    }

    /**
     * @return QueryBuilder<TModel>
     */
    protected function queryBuilder(): QueryBuilder
    {
        return QueryBuilder::for($this->baseQuery())
            ->allowedIncludes($this->allowedIncludes())
            ->allowedFilters($this->allowedFilters())
            ->allowedSorts($this->allowedSorts());
    }

    /** @return Builder<TModel> */
    protected function baseQuery(): Builder
    {
        $model = $this->model();

        return new $model()->query();
    }

    /** @return list<string|AllowedInclude> */
    public function allowedIncludes(): array
    {
        $model = $this->model();

        return new $model()->allowedIncludes();
    }

    /** @return list<string|AllowedFilter> */
    public function allowedFilters(): array
    {
        $model = $this->model();

        return new $model()->allowedFilters();
    }

    /** @return list<string|AllowedSort> */
    public function allowedSorts(): array
    {
        $model = $this->model();

        return new $model()->allowedSorts();
    }
}
