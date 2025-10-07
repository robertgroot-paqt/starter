<?php

/** @see ../vendor/spatie/laravel-typescript-transformer/config/typescript-transformer.php */

return [
    'auto_discover_types' => [
        app_path(),
    ],

    'collectors' => [
        Spatie\TypeScriptTransformer\Collectors\DefaultCollector::class,
    ],

    'output_file' => resource_path('types/generated.d.ts'),
];
