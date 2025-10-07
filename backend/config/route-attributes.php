<?php

/** @see ../vendor/spatie/laravel-route-attributes/config/route-attributes.php */

return [
    /*
     * Controllers in these directories that have routing attributes
     * will automatically be registered.
     *
     * Optionally, you can specify group configuration by using key/values
     */
    'directories' => [
        app_path('Http/Controllers/Api') => [
            'prefix' => 'api/v1',
            'middleware' => 'api',
        ],
    ],
];
