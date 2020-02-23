<?php

namespace App\Http\Middleware;

use App\Models\Admin\Usuarios;
use Closure;
use Illuminate\Support\Facades\Session;


class AdminMiddleware
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
        $idUsuario = Session::get('IdUsuario');
        if($idUsuario){
            $rol = (int)Session::get('Rol');
            if ($rol === 1)
                return $next($request);
            if($rol === 5)
                return redirect('dashboardMonitoreo');
            if($rol === 0)
                return redirect('/usuario/dashboard');
            return redirect('/user/dashboard');
        }else{
            return redirect('/');
        }

    }
}
