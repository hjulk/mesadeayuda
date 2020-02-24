<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Roles;
use App\Models\Admin\Usuarios;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Http\Requests\Validaciones;
use Illuminate\Support\Facades\Validator;
use Monolog\Handler\ZendMonitorHandler;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\Funciones;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function roles()
    {
        $Roles              = Roles::ListarRolesAdmin();
        $Categoria          = Roles::ListarCategoriasAdmin();
        $RolIndex           = array();
        $CategoriaIndex     = array();
        $contR              = 0;
        $contC              = 0;
        $Activo             = Usuarios::Activo();
        $NombreActivo       = array();
        $NombreActivo['']   = 'Seleccione: ';
        foreach ($Activo as $row){
            $NombreActivo[$row->id] = $row->name;
        }

        foreach($Roles as $value){
            $RolIndex[$contR]['id']         = $value->rol_id;
            $RolIndex[$contR]['name']       = Funciones::eliminar_tildes_texto($value->name);
            $RolIndex[$contR]['activoR']    = $value->activo;
            $idactivo                       = $value->activo;
            $nombreActivoS = Usuarios::ActivoID($idactivo);
            foreach($nombreActivoS as $valor){
                $RolIndex[$contR]['activo'] = $valor->name;
            }
            $contR++;
        }
        foreach($Categoria as $value){
            $CategoriaIndex[$contC]['id']           = $value->id;
            $CategoriaIndex[$contC]['name']         = Funciones::eliminar_tildes_texto($value->name);
            $CategoriaIndex[$contC]['activoC']      = $value->activo;
            $idactivo = $value->activo;
            $nombreActivoS = Usuarios::ActivoID($idactivo);
            foreach($nombreActivoS as $valor){
                $CategoriaIndex[$contC]['activo']   = $valor->name;
            }
            $contC++;
        }

        return view('admin.roles',['Roles' => $RolIndex, 'Categorias' => $CategoriaIndex,'Activo' => $NombreActivo,
                                    'RolName' => null,'CategoriaName' => null]);
    }

    public function crearRol(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_rol'    =>  'required'
        ]);

        if ($validator->fails()) {
            return redirect('admin/roles')->withErrors($validator)->withInput();
        }else{
            $nombreRol = $request->nombre_rol;
            $busquedaNombre = Roles::BuscarNombreRol($nombreRol);
            if($busquedaNombre){
                $verrors = array();
                array_push($verrors, 'El nombre de rol '.$nombreRol.', ya se encuentra en la base de datos');
                // return redirect('admin/roles')->withErrors(['errors' => $verrors]);
                return Redirect::to('admin/roles')->withErrors(['errors' => $verrors])->withRequest();
            }else{
                $crearRol = Roles::CrearRol($nombreRol);
                if($crearRol){
                    $verrors = 'Se creo con éxito el rol '.$nombreRol;
                    return redirect('admin/roles')->with('mensaje', $verrors);
                }else{
                    $verrors = array();
                    array_push($verrors, 'Hubo un problema al crear el rol');
                    // return redirect('admin/roles')->withErrors(['errors' => $verrors]);
                    return Redirect::to('admin/roles')->withErrors(['errors' => $verrors])->withRequest();
                }
            }
        }
    }

    public function actualizarRol(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_rol_upd'    =>  'required',
            'id_activoR'        =>  'required'
        ]);

        if ($validator->fails()) {
            return redirect('admin/roles')->withErrors($validator)->withInput();
        }else{

            $id             = (int)$request->idR;
            $nombreR        = $request->nombre_rol_upd;
            $idactivo       = $request->id_activoR;
            $BuscarRol      = Roles::BuscarIDRol($id);
            foreach($BuscarRol as $row){
                $Nombre = $row->name;
                $activo = (int)$row->activo;
            }
            if($nombreR === $Nombre){
                $nombreRol  = $Nombre;
                $ActualizarRol  = Roles::ActualizarRolActivo($id,$idactivo);
            }else{
                $nombreRol  = $request->nombre_rol_upd;
                $ActualizarRol  = Roles::ActualizarRol($id,$nombreRol,$idactivo);
            }

            if($ActualizarRol >= 0){
                $verrors = 'Se actualizo con éxito el rol '.$nombreRol;
                return redirect('admin/roles')->with('mensaje', $verrors);
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al actualizar el rol');
                return redirect('admin/roles')->withErrors(['errors' => $verrors]);
            }
        }
    }

    public function crearCategoria(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_categoria'    =>  'required'
        ]);

        if ($validator->fails()) {
            return redirect('admin/roles')->withErrors($validator)->withInput();
        }else{

            $nombreCategoria = $request->nombre_categoria;
            $busquedaNombre = Roles::BuscarNombreCategoria($nombreCategoria);
            if($busquedaNombre){
                $verrors = array();
                array_push($verrors, 'El nombre de la categoria '.$nombreCategoria.', ya se encuentra en la base de datos');
                // return redirect('admin/roles')->withErrors(['errors' => $verrors]);
                return Redirect::to('admin/roles')->withErrors(['errors' => $verrors])->withRequest();
            }else{
                $crearCategoria = Roles::CrearCategoria($nombreCategoria);
                if($crearCategoria){
                    $verrors = 'Se creo con éxito la categoria '.$nombreCategoria;
                    return redirect('admin/roles')->with('mensaje', $verrors);
                }else{
                    $verrors = array();
                    array_push($verrors, 'Hubo un problema al crear la categoria');
                    // return redirect('admin/roles')->withErrors(['errors' => $verrors]);
                    return Redirect::to('admin/roles')->withErrors(['errors' => $verrors])->withRequest();
                }
            }
        }
    }

    public function actualizarCategoria(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_categoria_upd'  =>  'required',
            'id_activoC'            =>  'required'
        ]);

        if ($validator->fails()) {
            return redirect('admin/roles')->withErrors($validator)->withInput();
        }else{

            $id                 = $request->idC;
            $nombreCategoria    = $request->nombre_categoria_upd;
            $idactivo           = $request->id_activoC;
            $ActualizarCategoria = Roles::ActualizarCategoria($id,$nombreCategoria,$idactivo);
            if($ActualizarCategoria >= 0){
                $verrors = 'Se actualizo con éxito la categoria '.$nombreCategoria;
                return redirect('admin/roles')->with('mensaje', $verrors);
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al actualizar la categoria');
                return redirect('admin/roles')->withErrors(['errors' => $verrors]);
            }

        }
    }



}
