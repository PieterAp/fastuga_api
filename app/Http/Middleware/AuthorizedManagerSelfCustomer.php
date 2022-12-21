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
                'auth' => Auth::user() ,                        
                'request' => $request->route()->parameter('customer'),     
            ], 401);
        }

        return $next($request);
    }

    protected function isManagerSelfCustomer($request)
    {
        if(Auth::user()->id == $request->route()->parameter('customer')->user_id || Auth::user()->type == 'EM'){
            return true;
        }else{
            return false;
        }

       
    }
}
