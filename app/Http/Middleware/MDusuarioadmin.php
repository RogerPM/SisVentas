<?php

namespace sisVentas\Http;
use Closure;

class MDusuarioadmin
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
        $usuario_actual=\Auth::user();
        if ($usuario_actual->tipousuario!=1) {
          return response('no tiene permisos para ingresar', 401);
        }else{
            return redirect()->guest('home');
        }
        return $next($request);
    }
}
