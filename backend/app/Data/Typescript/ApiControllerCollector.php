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
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use Spatie\LaravelData\Support\DataClass;
use Spatie\LaravelData\Support\Factories\DataClassFactory;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\TypeScriptTransformer\Collectors\Collector;
use Spatie\TypeScriptTransformer\Structures\MissingSymbolsCollection;
use Spatie\TypeScriptTransformer\Structures\TransformedType;
use Spatie\TypeScriptTransformer\Transformers\TransformsTypes;

class ApiControllerCollector extends Collector
{
    use TransformsTypes;

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

            $input = null;

            if ($dataParameter = $this->findDataParameter($classMethod)) {
                $input = $this->reflectionToTypeScript($dataParameter, $missingSymbols);
            }

            $output = $this->reflectionToTypeScript($classMethod, $missingSymbols);

            $result .= $this->getTypescript($route, $classMethod, $input, $output);
        }

        foreach ($missingSymbols->all() as $missingSymbol) {
            $this->registerDataClasses($missingSymbol);
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

    private function registerDataClasses(string $class): void
    {
        self::$visitedClasses[$class] = true;

        /** @var DataClass $dataClass */
        $dataClass = App::make(DataClassFactory::class)->build(new ReflectionClass($class));

        foreach ($dataClass->properties as $property) {
            if ($property->type->dataClass) {
                $this->registerDataClasses($property->type->dataClass);
            }
        }
    }

    private function getTransformedName(ReflectionClass $class): string
    {
        return str_replace('Controller', 'Api', $class->getShortName());
    }

    private function getTypescript(Route $route, ReflectionMethod $controllerMethod, ?string $input, ?string $output): string
    {
        $output ??= 'never';

        if ($output === 'void') {
            $output = 'never';
        } else {
            $output = $this->wrapOutput($controllerMethod, $output);
        }

        [$parametersDecl, $parametersUsage] = $this->buildParameters($route);
        [$inputDecl, $inputUsage] = $this->buildInput($input);
        [$queryDecl, $queryUsage] = $this->buildQuery($route);

        return <<<TS
            {$route->getActionMethod()}: ({$parametersDecl}{$inputDecl}{$queryDecl}) =>
                useApi<{$output}>({
                    url: "{$route->uri}",
                    method: "{$route->methods[0]}",{$inputUsage}{$parametersUsage}{$queryUsage}
                }),
        TS;
    }

    private function wrapOutput(ReflectionMethod $controllerMethod, string $type): string
    {
        $returnType = $controllerMethod->getReturnType();

        $collectionWrap = 'Array';

        if ($returnType instanceof ReflectionNamedType) {
            $collectionWrap = match ($returnType->getName()) {
                PaginatedDataCollection::class => 'PaginatedCollection',
                default => $collectionWrap,
            };
        }

        if (preg_match('/^Array<(.+)>$/', $type, $matches) || preg_match('/^(.+)\[\]$/', $type, $matches)) {
            $innerType = $matches[1];

            return "{$collectionWrap}<ApiWrap<{$innerType}>>";
        }

        return "ApiWrap<{$type}>";
    }

    private function buildParameters(Route $route): array
    {
        $routeParameters = $route->parameterNames();

        if (empty($routeParameters)) {
            return ['', ''];
        }

        $params = array_map(fn ($param) => "{$param}: string", $routeParameters);
        $declaration = 'parameters: {'.implode(', ', $params).'}, ';
        $usage = 'urlParameters: parameters,';

        return [$declaration, $usage];
    }

    private function buildInput(?string $input): array
    {
        if (! $input) {
            return ['', ''];
        }

        return ["input: {$input}, ", 'data: input,'];
    }

    private function buildQuery(Route $route): array
    {
        $controller = $route->getController();

        if (! $controller instanceof ApiController) {
            return ['', ''];
        }

        return [$this->getQueryType($controller).', ', 'query: query,'];
    }

    private function getQueryType(ApiController $controller): string
    {
        $filters = $this->buildNestedTypeProperty(
            'filters',
            'Filter',
            $controller->allowedFilters(),
            fn ($filter) => $filter instanceof AllowedFilter ? $filter->getName() : $filter
        );

        $includes = $this->buildTypeProperty(
            'include',
            $controller->allowedIncludes(),
            fn ($include) => $include instanceof AllowedInclude ? $include->getName() : $include
        );

        $sorts = $this->buildSorts($controller->allowedSorts());
        $operations = $this->buildOperations($controller->data()::operations());

        return sprintf('query: undefined | {%s%s%s%s} = undefined', $includes, $sorts, $filters, $operations);
    }

    private function buildSorts(array $sorts): string
    {
        $getName = fn ($sort) => $sort instanceof AllowedSort ? $sort->getName() : $sort;

        foreach ($sorts as $sort) {
            $sorts[] = '-'.$getName($sort);
        }

        return $this->buildTypeProperty('sorts', $sorts, $getName);
    }

    private function buildOperations(array $operations): string
    {
        $types = $this->valuesToType($operations, fn (Operation $op) => $op->name);

        return $types ? "includeOperations?: ({$types}|\"*\")[]," : '';
    }

    private function buildTypeProperty(string $name, array $items, Closure $getName): string
    {
        $types = $this->valuesToType($items, $getName);

        return $types ? "{$name}?: ({$types})[]," : '';
    }

    private function buildNestedTypeProperty(string $name, string $subType, array $items, Closure $getName): string
    {
        $types = $this->valuesToType($items, $getName);

        return $types ? "{$name}?: {$subType}<{$types}>," : '';
    }

    private function valuesToType(array $items, Closure $getName): string
    {
        $quoted = array_map(fn ($item) => '"'.$getName($item).'"', $items);

        return implode('|', $quoted);
    }
}
