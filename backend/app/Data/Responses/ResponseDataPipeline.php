<?php

namespace App\Data\Responses;

use Illuminate\Contracts\Container\Container;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\App;

class ResponseDataPipeline extends Pipeline
{
    protected $pipes = [
        AddOperationsPipe::class,
    ];

    public static function create(): self
    {
        return new self(App::make(Container::class));
    }
}
