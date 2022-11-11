<?php
//TODO
namespace App\Http\Middleware;

use App\Models\User;

use Closure;

class CheckType
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $type
     * @return mixed
     */
    public function handle($request, Closure $next, $type)
    {
        if (!$request->user()->hasType($type)) {
            // Redirect...
        }

        return $next($request);
    }
}
