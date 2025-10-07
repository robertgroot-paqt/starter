<?php

namespace App\Http\Middleware;

use App\Http\Controllers\ApiController;
use App\Models\Model;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthorizeMiddleware
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->checkAuthorization($request);

        return $next($request);
    }

    /**
     * Checks default policies for controller action.
     */
    private function checkAuthorization(Request $request): void
    {
        $route = $request->route();

        if (! $route) {
            return;
        }

        $controller = $route->getController();

        if (! $controller instanceof ApiController) {
            return;
        }

        $parameter = $route->parameters()[$controller->authorizeParameter()] ?? null;

        if ($parameter instanceof Model) {
            // Check policy for model instance
            Gate::authorize($route->getActionMethod(), [$parameter]);
        } else {
            // Check policy for model class
            Gate::authorize($route->getActionMethod(), [$controller->model()]);
        }
    }
}
