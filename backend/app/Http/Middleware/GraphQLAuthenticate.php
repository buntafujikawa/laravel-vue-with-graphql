<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate;

class GraphQLAuthenticate extends Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public function handle($request)
    {
        if (!$request->expectsJson()) {
            return false;
        }
    }
}
