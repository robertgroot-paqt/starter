<?php

use Laravel\Sanctum\Sanctum;

/** @see ./vendor/laravel/sanctum/config/sanctum.php */

return [
    /*
    |--------------------------------------------------------------------------
    | Stateful Domains
    |--------------------------------------------------------------------------
    |
    | Requests from the following domains / hosts will receive stateful API
    | authentication cookies. Typically, these should include your local
    | and production domains which access your API via a frontend SPA.
    |
    */
    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
        '%s%s',
        'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
        Sanctum::currentRequestHost(),
    ))),

    /**
     * Prefix for the sanctum.csrf-cookie route.
     */
    'prefix' => 'api/v1/session',
];
