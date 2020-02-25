<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Sedes;
use App\Http\Requests\Validaciones;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Usuarios;
use Monolog\Handler\ZendMonitorHandler;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Admin\Activo;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Funciones;

class SedesController extends Controller
{

    public function sedes()
    {
        $Sedes      = Sedes::Sedes();
        $SedesA     = Sedes::SedesA();
        $SedesIndex = array();
        $contS = 0;
        foreach($Sedes as $value){
            $SedesIndex[$contS]['id']           = $value->id;
            $SedesIndex[$contS]['name']         = Funciones::eliminar_tildes_texto($value->name);
            $SedesIndex[$contS]['description']  = Funciones::eliminar_tildes_texto($value->description);
            $SedesIndex[$contS]['activo']       = $value->activo;
            $idactivo = $value->activo;
            $nombreActivoS = Usuarios::ActivoID($idactivo);
            foreach($nombreActivoS as $valor){
                $SedesIndex[$contS]['name_activo'] = $valor->name;
            }
            $contS++;
        }

        $Areas = Sedes::Areas();
        $AreasIndex = array();
        $contA = 0;
        foreach($Areas as $value){
            $AreasIndex[$contA]['id']           = (int)$value->id;
            $AreasIndex[$contA]['nombre']       = Funciones::eliminar_tildes_texto($value->name);
            $AreasIndex[$contA]['project_id']   = (int)$value->project_id;
            $AreasIndex[$contA]['activo']       = (int)$value->activo;
            $idactivo                           = (int)$value->activo;
            $nombreActivoS = Usuarios::ActivoID($idactivo);
            foreach($nombreActivoS as $rowA){
                $AreasIndex[$contA]['name_activo'] = $rowA->name;
            }
            $idsede                             = (int)$value->project_id;
            $Sede           = Sedes::BuscarSedeID($idsede);
            foreach($Sede as $rowS){
                $AreasIndex[$contA]['sede'] = Funciones::eliminar_tildes_texto($rowS->name);
            }
            $contA++;
        }

        $Activo     = Usuarios::Activo();
        $NombreActivo = array();
        $NombreActivo[''] = 'Seleccione: ';
        foreach ($Activo as $row){
            $NombreActivo[$row->id] = $row->name;
        }

        $NombreSede = array();
        $NombreSede[''] = 'Seleccione: ';
        foreach($SedesA as $row){
            $NombreSede[$row->id] = Funciones::eliminar_tildes_texto($row->name);
        }

        return view('admin.sedes',['Sedes' => $SedesIndex,'NombreSede' => $NombreSede,'Sede' => null,
                                    'Descripcion' => null,'Activo' => $NombreActivo,'Areas' => $AreasIndex]);
    }

    public function crearSede(Request $request){
        $validator = Validator::make($request->all(), [
            'nombre'        =>  'required',
            'descripcion'   =>  'required'
        ]);

        if ($validator->fails()) {
            return redirect('admin/sedes')->withErrors($validator)->withInput();
        }else{

            $Sede           = Funciones::eliminar_tildes_texto($request->nombre);
            $Descripcion    = Funciones::eliminar_tildes_texto($request->descripcion);
            $consultarSede  = Sedes::BuscarSede($Sede);

            if($consultarSede){
                $verrors = array();
                array_push($verrors, 'Nombre de la sede ya se encuentra creada');
                return Redirect::to('admin/sedes')->withErrors(['errors' => $verrors])->withRequest();
            }else{

                $InsertarSede = Sedes::CrearSede($Sede,$Descripcion);
                if($InsertarSede){
                    $verrors = 'Se creo con éxito la sede '.$Sede;
                    return redirect('admin/sedes')->with('mensaje', $verrors);
                }else{
                    $verrors = array();
                    array_push($verrors, 'Hubo un problema al crear la sede');
                    // return redirect('admin/sedes')->withErrors(['errors' => $verrors]);
                    return Redirect::to('admin/sedes')->withErrors(['errors' => $verrors])->withRequest();
                }
            }
        }
    }

    public function actualizarSede(Request $request){

        $validator = Validator::make($request->all(), [
            'nombre_upd'        =>  'required',
            'descripcion_upd'   =>  'required',
            'activo'            =>  'required'
        ]);

        if ($validator->fails()) {
            return redirect('admin/sedes')->withErrors($validator)->withInput();
        }else{

            $id             = (int)$request->idS;
            $Sede           = Funciones::eliminar_tildes_texto($request->nombre_upd);
            $Descripcion    = Funciones::eliminar_tildes_texto($request->descripcion_upd);
            $idActivo       = $request->activo;
            $actualizarSede = Sedes::ActualizarSede($id,$Sede,$Descripcion,$idActivo);
            if($actualizarSede >= 0){
                $verrors = 'Se actualizo con éxito la sede '.$Sede;
                return redirect('admin/sedes')->with('mensaje', $verrors);
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al actualizar la sede');
                return redirect('admin/sedes')->withErrors(['errors' => $verrors]);
            }
        }
    }

    public function crearArea(Request $request){
        $validator = Validator::make($request->all(), [
            'nombre_area'   =>  'required',
            'sede'          =>  'required'
        ]);

        if ($validator->fails()) {
            return redirect('admin/sedes')->withErrors($validator)->withInput();
        }else{

            $Area           = Funciones::eliminar_tildes_texto($request->nombre_area);
            $Sede           = (int)$request->sede;
            $consultarArea  = Sedes::BuscarArea($Area,$Sede);

            if($consultarArea){
                $verrors = array();
                array_push($verrors, 'Nombre del área ya se encuentra creada');
                return Redirect::to('admin/sedes')->withErrors(['errors' => $verrors])->withRequest();
            }else{

                $InsertarArea = Sedes::CrearArea($Area,$Sede);
                if($InsertarArea){
                    $verrors = 'Se creo con éxito el área '.$Area;
                    return redirect('admin/sedes')->with('mensaje', $verrors);
                }else{
                    $verrors = array();
                    array_push($verrors, 'Hubo un problema al crear el área');
                    // return redirect('admin/sedes')->withErrors(['errors' => $verrors]);
                    return Redirect::to('admin/sedes')->withErrors(['errors' => $verrors])->withRequest();
                }
            }
        }
    }

    public function actualizarArea(Request $request){
        $validator = Validator::make($request->all(), [
            'nombre_area_upd'   =>  'required',
            'sede_upd'          =>  'required',
            'activo_area'       =>  'required'
        ]);

        if ($validator->fails()) {
            return redirect('admin/sedes')->withErrors($validator)->withInput();
        }else{

            $id             = (int)$request->idA;
            $Area           = Funciones::eliminar_tildes_texto($request->nombre_area_upd);
            $Sede           = (int)$request->sede_upd;
            $idActivo       = (int)$request->activo_area;
            $ActualizarArea = Sedes::ActualizarArea($id,$Area,$Sede,$idActivo);
            if($ActualizarArea >= 0){
                $verrors = 'Se actualizo con éxito el área '.$Area;
                return redirect('admin/sedes')->with('mensaje', $verrors);
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al actualizar el área');
                return redirect('admin/sedes')->withErrors(['errors' => $verrors]);
            }
        }
    }


}
