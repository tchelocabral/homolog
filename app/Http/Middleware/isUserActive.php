<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

class isUserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,  $guard = null)
    {
      
        if (Auth::guard($guard)->check() && $request->user()->ativo != 1) {
          
            $this->guard()->logout();
            $request->session()->invalidate();
            return redirect()->route('login');
        }
        return $next($request);
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
