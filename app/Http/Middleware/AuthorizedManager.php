<?php

namespace App\Http\Middleware;

use Closure;

class AuthorizedManager
{

    public function handle($request, Closure $next)
    {

        if (!$this->isManager($request)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        return $next($request);
    }

    protected function isManager($request)
    {
        return $request->user()->type == 'EM';
    }
}
