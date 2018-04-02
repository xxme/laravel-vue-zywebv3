<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->guard('web')->check()){
            if(Auth::user()->group_id > 2){
                return redirect('/admin/login');
            }
            return $next($request);
        }
        return redirect('/admin/login');
    }
}
