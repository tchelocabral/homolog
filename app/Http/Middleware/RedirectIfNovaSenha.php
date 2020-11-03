<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Route;

/**
 * #deprecated version 200616
 * Movido para função estática do controller
 * Undocumented class
 */
class RedirectIfNovaSenha
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        
        $rotas_liberadas = [
            'user.nova.senha',
            'logout',
            'user.gravar.senha',
            'login',
            'efetua.login'
        ];

        $route = Route::getRoutes()->match($request);
        $current_route = $route->getName();
        // dd($guard); 
        // dd(\Auth::check()); 
        // dd($next($request));
        // dd(\Auth::user()->nova_senha);

        $rota_liberada = in_array($current_route, $rotas_liberadas)  ;
        // dd(!$rota_liberada);
        if (!$rota_liberada && Auth::guard($guard)->check() && Auth::user()->nova_senha) {
            return redirect()->route('user.nova.senha');
        }

        return $next($request);
    }
}
