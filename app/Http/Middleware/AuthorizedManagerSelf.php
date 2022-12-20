<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class AuthorizedManagerSelf{

    public function handle($request, Closure $next,User $user)
    {

        if (!$this->isManagerSelf($request,$user)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        return $next($request);
    }

    protected function isManagerSelf($request,User $user)
    {
        if($request->user()->id == $user->id || $request->user()->type == "EM"){
            return true;
        }else{
            return false;
        }

       
    }
}
