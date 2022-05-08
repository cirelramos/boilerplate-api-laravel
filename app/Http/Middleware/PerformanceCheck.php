<?php

namespace App\Http\Middleware;

use Cirelramos\Logs\Services\SendLogConsoleService;
use Closure;
use GuzzleHttp\Client as ClientHttp;

class PerformanceCheck
{

    public function __construct()
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        return $response;
    }

    public function terminate($request, $response)
    {
    }
}
