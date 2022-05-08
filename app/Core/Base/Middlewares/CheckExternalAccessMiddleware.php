<?php

namespace App\Core\Base\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 *
 */
class CheckExternalAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $environment = env('HOST_KEY_EXTERNAL_ACCESS', null);
        $keyAccess  = $request->header('key-access');
        $hostAccess = $request->header('host');

        if ($environment === null) {
            abort(Response::HTTP_UNAUTHORIZED, 'not exist environment external service');
        }

        $array = [];
        if($request->header('id_user')){
            $request[ 'id_user_external' ] = $request->header('id_user');
        }

        $sendConsoleLog = new \Cirelramos\Logs\Services\SendLogConsoleService();
        $sendConsoleLog->execute("external-request", $array);

        $validSecrets = explode('|', $environment);
        $validSecrets = collect($validSecrets);

        $validSecrets = $validSecrets->map($this->mapReplaceGetEnvHostAndKey());

        $countAccess = $validSecrets->where('host_access', $hostAccess)
            ->where('key_access', $keyAccess)
            ->count();

        if ($keyAccess === null || $hostAccess === null) {
            abort(Response::HTTP_UNAUTHORIZED, 'unauthenticated external service');
        }

        if ($countAccess === 0) {
            abort(Response::HTTP_UNAUTHORIZED, 'unauthenticated external service 2');
        }

        return $next($request);
    }

    private function mapReplaceGetEnvHostAndKey(): callable
    {
        return function ($validSecret, $key) {
            $hostAndKey = explode(',', $validSecret);
            $newValidSecret    = (object) [];

            $newValidSecret->host_access = $hostAndKey[ 0 ];
            $newValidSecret->key_access  = $hostAndKey[ 1 ];

            return $newValidSecret;
        };
    }

}
