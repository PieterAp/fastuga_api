<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class AuthorizedChef
{

    public function handle($request, Closure $next)
    {

        if (!$this->isChef($request)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        return $next($request);
    }

    protected function isChef($request)
    {
        return $request->user()->type == 'EC';
    }
}
