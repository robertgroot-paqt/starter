<?php

/** @see ../vendor/spatie/laravel-typescript-transformer/config/typescript-transformer.php */

return [
    'auto_discover_types' => [
        app_path(),
    ],

    'collectors' => [
        Spatie\TypeScriptTransformer\Collectors\DefaultCollector::class,
        App\Data\Typescript\ApiControllerCollector::class,
        App\Data\Typescript\ApiControllerDataCollector::class,
    ],

    'transformers' => [
        Spatie\TypeScriptTransformer\Transformers\EnumTransformer::class,
        Spatie\LaravelData\Support\TypeScriptTransformer\DataTypeScriptTransformer::class,
    ],

    'output_file' => resource_path('types/generated.ts'),

    'writer' => Spatie\TypeScriptTransformer\Writers\ModuleWriter::class,
];
