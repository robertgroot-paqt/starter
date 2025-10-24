<?php

namespace App\Data\Responses;

use App\Data\Base\CursorPaginatedDataCollection;
use App\Data\Base\Data;
use App\Data\Base\DataCollection;
use App\Data\Base\PaginatedDataCollection;
use App\Data\Operations\Operation;
use Closure;
use Illuminate\Http\Request;

class AddOperationsPipe
{
    private array $includeOperations;

    private bool $includeAllOperations;

    public function __construct(Request $request)
    {
        $includeOperations = $request->query('includeOperations', '');

        $this->includeOperations = explode(',', $includeOperations);

        $this->includeAllOperations = in_array('*', $this->includeOperations);
    }

    public function handle(array $input, Closure $next)
    {
        [$transformed, $data] = $input;

        if (count($this->includeOperations) === 0) {
            return $next($input);
        }

        if ($data instanceof ResponseData) {
            $transformed['operations'] = $this->getOperations($data::class, $data);
        } elseif (
            $data instanceof DataCollection
            || $data instanceof PaginatedDataCollection
            || $data instanceof CursorPaginatedDataCollection
        ) {
            $transformed['operations'] = $this->getOperations($data->dataClass);
        }

        $input[0] = $transformed;

        return $next($input);
    }

    /**
     * @param  class-string<ResponseData> $dataClass
     * @return array<string,Operation>
     */
    private function getOperations(string $dataClass, ?ResponseData $subject = null): array
    {
        /** @var array<string,Operation> */
        $operations = [];

        foreach ($dataClass::operations() as $operation) {
            if (
                ! $this->includeAllOperations
                && ! in_array($operation->name, $this->includeOperations)
            ) {
                continue;
            }

            if ($operation->applicable($subject)) {
                $operations[$operation->name] = $operation;
            }
        }

        return $operations;
    }
}
