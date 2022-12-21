<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthorizedManagerSelf{

    public function handle($request, Closure $next)
    {

        if (!$this->isManagerSelf($request)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',                
            ], 401);
        }

        return $next($request);
    }

    protected function isManagerSelf($request)
    {
        if(Auth::user()->id == $request->route()->parameter('user')->id || Auth::user()->type == 'EM'){
            return true;
        }else{
            return false;
        }

       
    }
}
