<?php

namespace App\Data\Typescript;

use App\Data\Base\Data;
use App\Data\Base\PaginatedDataCollection;
use App\Data\Operations\Operation;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Closure;
use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route as RouteFacade;
use ReflectionClass;
use ReflectionIntersectionType;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;
use Spatie\LaravelData\Support\Annotations\DataIterableAnnotationReader;
use Spatie\LaravelData\Support\DataClass;
use Spatie\LaravelData\Support\Factories\DataClassFactory;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\TypeScriptTransformer\Collectors\Collector;
use Spatie\TypeScriptTransformer\Structures\MissingSymbolsCollection;
use Spatie\TypeScriptTransformer\Structures\TransformedType;

class ApiControllerCollector extends Collector
{
    public static array $visitedClasses = [];

    public function getTransformedType(ReflectionClass $class): ?TransformedType
    {
        if (
            ! is_a($class->getName(), Controller::class, true)
            || $class->isAbstract()
        ) {
            return null;
        }

        $missingSymbols = new MissingSymbolsCollection;
        $result = '';

        foreach ($this->findRoutesFor($class) as $route) {
            $classMethod = $class->getMethod($route->getActionMethod());

            $dataParameter = $this->findDataParameter($classMethod);

            $input = null;

            if ($inputRegistered = $this->registerType($dataParameter?->getType())) {
                $input = $missingSymbols->add($inputRegistered);
            }

            $output = null;

            // dump($class->getName());
            // $annotation = App::make(DataIterableAnnotationReader::class)->getForMethod($classMethod);
            // dump($annotation);

            if ($outputRegisterd = $this->registerType($classMethod->getReturnType())) {
                $output = $missingSymbols->add($outputRegisterd);
            }

            $result .= $this->getTypescript($route, $input, $output);
        }

        return new TransformedConst(
            class: $class,
            name: $this->getTransformedName($class),
            transformed: '{'.$result.'}',
            missingSymbols: $missingSymbols,
            isInline: false,
            keyword: 'const',
        );
    }

    /** @return Collection<array-key,Route>  */
    private function findRoutesFor(ReflectionClass $class): Collection
    {
        return collect(RouteFacade::getRoutes())
            ->filter(
                fn (Route $route) => $class->getName() === $route->getControllerClass()
            );
    }

    private function findDataParameter(ReflectionMethod $controllerMethod): ?ReflectionParameter
    {
        return array_find(
            $controllerMethod->getParameters(),
            function (ReflectionParameter $parameter) {
                $type = $parameter->getType();

                return $type instanceof ReflectionNamedType
                    && is_a($type->getName(), Data::class, true);
            }
        );
    }

    private function getTransformedName(ReflectionClass $class): string
    {
        return str_replace('Controller', 'Api', $class->getShortName());
    }

    private function getTypescript(Route $route, ?string $input, ?string $output): string
    {
        $parameters = '';
        $parameters2 = '';

        if ($routeParameters = $route->parameterNames()) {
            $parameters = 'parameters: {';

            foreach ($routeParameters as $routeParameter) {
                $parameters .= " {$routeParameter}: string, ";
            }

            $parameters .= '}, ';
            $parameters2 = 'urlParameters: parameters,';
        }

        $input = $input ? "input: {$input}, " : '';
        $input2 = $input ? 'data: input,' : '';

        $controller = $route->getController();

        $query = '';
        $query2 = '';
        if ($controller instanceof ApiController) {
            $query = $this->getQueryType($controller).', ';
            $query2 = 'query: query,';
        }

        return <<<TS

            {$route->getActionMethod()}: ({$parameters}{$input}{$query}) =>
                useApi<{$output}>({
                    url: "{$route->uri}",
                    method: "{$route->methods[0]}",{$input2}{$parameters2}{$query2}
                }),
        TS;
    }

    private function registerType(ReflectionNamedType|ReflectionUnionType|ReflectionIntersectionType|null $type): ?string
    {
        if (! $type instanceof ReflectionNamedType) {
            return null;
        }

        $this->registerDataClasses($type->getName());

        return $type->getName();
    }

    private function registerDataClasses(string $class): void
    {
        if (
            ! is_a($class, Data::class, true)
            || (self::$visitedClasses[$class] ?? false)
        ) {
            return;
        }

        self::$visitedClasses[$class] = true;

        /** @var DataClass $dataClass */
        $dataClass = App::make(DataClassFactory::class)->build(new ReflectionClass($class));

        foreach ($dataClass->properties as $property) {
            if ($property->type->dataClass) {
                $this->registerDataClasses($property->type->dataClass);
            }
        }
    }

    private function getQueryType(ApiController $controller): string
    {
        $filters = $this->valuesToType(
            $controller->allowedFilters(),
            fn (string|AllowedFilter $filter) => $filter instanceof AllowedFilter ? $filter->getName() : $filter,
        );
        $filters = $filters ? "filters?: Filter<{$filters}>[]," : '';

        $includes = $this->valuesToType(
            $controller->allowedIncludes(),
            fn (string|AllowedInclude $include) => $include instanceof AllowedInclude ? $include->getName() : $include,
        );
        $includes = $includes ? "include?: ({$includes})[]," : '';

        $sortStringValue = fn (string|AllowedSort $sort) => $sort instanceof AllowedSort ? $sort->getName() : $sort;
        $sorts = $controller->allowedSorts();
        foreach ($sorts as $sort) {
            $sorts[] = '-'.$sortStringValue($sort);
        }
        $sorts = $this->valuesToType(
            $sorts,
            $sortStringValue,
        );
        $sorts = $sorts ? "sorts?: ({$sorts})[]," : '';

        $operations = $this->valuesToType(
            $controller->data()::operations(),
            fn (Operation $operation) => $operation->name,
        );
        $operations .= '|"*"';
        $operations = $operations ? "includeOperations?: ({$operations})[]," : '';

        return <<<TS
            query: undefined | {{$includes}{$sorts}{$filters}{$operations}} = undefined
        TS;
    }

    private function valuesToType(array $items, Closure $getName): string
    {
        $items = array_map(
            static function (mixed $item) use ($getName) {
                return '"'.$getName($item).'"';
            },
            $items
        );

        return implode('|', $items);
    }
}
