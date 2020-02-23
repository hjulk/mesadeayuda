<?php

namespace App\Http\Middleware;

use App\Models\Admin\Usuarios;
use Closure;
use Illuminate\Support\Facades\Session;


class UserMiddleware
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
            // $Usuario = Usuarios::BuscarNombre($idUsuario);
            // foreach($Usuario as $value){
            //     $rol = (int)$value->rol_id;
            // }
            $rol = (int)Session::get('Rol');
            if(($rol === 2) || ($rol === 3) || ($rol === 4))
                return $next($request);
            if($rol === 6)
                return redirect('dashboardMonitoreo');
            if($rol === 1)
                return redirect('/admin/dashboard');
            if($rol === 0)
                return redirect('/usuario/dashboard');
        }else{
            return redirect('/');
        }

    }
}
