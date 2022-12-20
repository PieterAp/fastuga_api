<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

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
        return  Auth::user()->type == 'EM';
    }
}
