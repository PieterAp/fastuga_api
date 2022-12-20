<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthorizedManagerSelfCustomer{

    public function handle($request, Closure $next)
    {

        if (!$this->isManagerSelfCustomer($request)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',            
            ], 401);
        }

        return $next($request);
    }

    protected function isManagerSelfCustomer($request)
    {
        if(Auth::user()->id == $request->route()->parameter('customer')->id || Auth::user()->type == 'EM'){
            return true;
        }else{
            return false;
        }

       
    }
}
