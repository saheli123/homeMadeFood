<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        header("Access-Control-Allow-Origin: *");

        header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');

        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With, access-control-allow-origin');

        header('Access-Control-Allow-Credentials: true');

        if ("OPTIONS" === $request->getMethod()) {

            return response(200);

        }
        return $next($request);
}
}
