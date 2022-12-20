<?php

namespace App\Http\Middleware;

use Closure;

class AuthorizedExceptCustomers{

    public function handle($request, Closure $next)
    {

        if (!$this->isNotCustomer($request)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        return $next($request);
    }

    protected function isNotCustomer($request)
    {
        return $request->user()->type != 'C';
    }
}
