<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use App\Models\Admin\Sedes;
use App\Http\Requests\Validaciones;
use Validator;
use App\Models\Admin\Usuarios;
use Monolog\Handler\ZendMonitorHandler;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Admin\Activo;
use Illuminate\Support\Facades\Redirect;

class SedesController extends Controller
{

    public function sedes()
    {
        $Sedes      = Sedes::Sedes();
        $SedesA     = Sedes::SedesA();
        $SedesIndex = array();
        $contS = 0;
        foreach($Sedes as $value){
            $SedesIndex[$contS]['id'] = $value->id;
            $SedesIndex[$contS]['name'] = $value->name;
            $SedesIndex[$contS]['description'] = $value->description;
            $SedesIndex[$contS]['activo'] = $value->activo;
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
            $AreasIndex[$contA]['nombre']       = SedesController::eliminar_tildes_texto($value->name);
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
                $AreasIndex[$contA]['sede'] = SedesController::eliminar_tildes_texto($rowS->name);
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
        foreach($Sedes as $row){
            $NombreSede[$row->id] = $row->name;
        }

        return view('admin.sedes',['Sedes' => $SedesIndex,'NombreSede' => $NombreSede,'Sede' => null,
                                    'Descripcion' => null,'Activo' => $NombreActivo,'Areas' => $AreasIndex]);
    }

    public function crearSede(){
        $data = Request::all();
        $reglas = array(
            'nombre'        =>  'required',
            'descripcion'   =>  'required'
        );
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $Sede           = SedesController::eliminar_tildes_texto(Request::get('nombre'));
            $Descripcion    = SedesController::eliminar_tildes_texto(Request::get('descripcion'));
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
        }else{
            // return redirect('admin/sedes')->withErrors(['errors' => $verrors]);
            return Redirect::to('admin/sedes')->withErrors(['errors' => $verrors])->withRequest();
        }
    }

    public function actualizarSede(){

        $data = Request::all();
        $reglas = array(
            'nombre_upd'        =>  'required',
            'descripcion_upd'   =>  'required',
            'activo'            =>  'required'
        );
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $id             = (int)Request::get('idS');
            $Sede           = SedesController::eliminar_tildes_texto(Request::get('nombre_upd'));
            $Descripcion    = SedesController::eliminar_tildes_texto(Request::get('descripcion_upd'));
            $idActivo       = Request::get('activo');
            $actualizarSede = Sedes::ActualizarSede($id,$Sede,$Descripcion,$idActivo);
            if($actualizarSede >= 0){
                $verrors = 'Se actualizo con éxito la sede '.$Sede;
                return redirect('admin/sedes')->with('mensaje', $verrors);
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al actualizar la sede');
                return redirect('admin/sedes')->withErrors(['errors' => $verrors]);
            }
        }else{
            return redirect('admin/sedes')->withErrors(['errors' => $verrors]);
        }

    }

    public function crearArea(){
        $data = Request::all();
        $reglas = array(
            'nombre_area'   =>  'required',
            'sede'          =>  'required'
        );
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $Area           = SedesController::eliminar_tildes_texto(Request::get('nombre_area'));
            $Sede           = (int)Request::get('sede');
            $consultarArea  = Sedes::BuscarArea($Area,$Sede);

            if($consultarArea){
                $verrors = array();
                array_push($verrors, 'Nombre del área ya se encuentra creada');
                return Redirect::to('admin/sedes')->withErrors(['errors' => $verrors])->withRequest();
            }else{

                $InsertarArea = Sedes::CrearArea($Area,$Sede);
                if($InsertarArea){
                    $verrors = 'Se creo con éxito el(la) área '.$Area;
                    return redirect('admin/sedes')->with('mensaje', $verrors);
                }else{
                    $verrors = array();
                    array_push($verrors, 'Hubo un problema al crear el(la) área');
                    // return redirect('admin/sedes')->withErrors(['errors' => $verrors]);
                    return Redirect::to('admin/sedes')->withErrors(['errors' => $verrors])->withRequest();
                }
            }
        }else{
            // return redirect('admin/sedes')->withErrors(['errors' => $verrors]);
            return Redirect::to('admin/sedes')->withErrors(['errors' => $verrors])->withRequest();
        }
    }

    public function actualizarArea(){
        $data = Request::all();
        $reglas = array(
            'nombre_area_upd'   =>  'required',
            'sede_upd'          =>  'required',
            'activo_area'       =>  'required'
        );
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $id             = (int)Request::get('idA');
            $Area           = SedesController::eliminar_tildes_texto(Request::get('nombre_area_upd'));
            $Sede           = (int)Request::get('sede_upd');
            $idActivo       = (int)Request::get('activo_area');
            $ActualizarArea = Sedes::ActualizarArea($id,$Area,$Sede,$idActivo);
            if($ActualizarArea >= 0){
                $verrors = 'Se actualizo con éxito el(la) área '.$Area;
                return redirect('admin/sedes')->with('mensaje', $verrors);
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al actualizar el(la) área');
                return redirect('admin/sedes')->withErrors(['errors' => $verrors]);
            }
        }else{
            return redirect('admin/sedes')->withErrors(['errors' => $verrors]);
        }
    }

    public static function eliminar_tildes_texto($nombrearchivo){

        //Codificamos la cadena en formato utf8 en caso de que nos de errores
        // $cadena = utf8_encode($nombrearchivo);
        $cadena = $nombrearchivo;
        //Ahora reemplazamos las letras
        $cadena = str_replace(
            array('ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä','Ã¡'),
            array('a', 'a', 'a', 'A', 'A', 'A', 'A','á'),
            $cadena
        );

        $cadena = str_replace(
            array('ë', 'ê', 'É', 'È', 'Ê', 'Ë','Ã©'),
            array('e', 'e', 'E', 'E', 'E', 'E','é'),
            $cadena );

        $cadena = str_replace(
            array('ï', 'î', 'Í', 'Ì', 'Ï', 'Î','Ã­'),
            array('i', 'i', 'I', 'I', 'I', 'I','í'),
            $cadena );

        $cadena = str_replace(
            array('ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô','Ã³','Ã“'),
            array('o', 'o', 'O', 'O', 'O', 'O','ó','Ó'),
            $cadena );

        $cadena = str_replace(
            array('ü', 'û', 'Ú', 'Ù', 'Û', 'Ü','Ãº'),
            array('u', 'u', 'U', 'U', 'U', 'U','ú'),
            $cadena );

        $cadena = str_replace(
            array('ç', 'Ç','Ã±','Ã‘'),
            array('c', 'C','ñ','Ñ'),
            $cadena
        );

        $cadena = str_replace(
            array("'", '‘'),
            array(' ', ' '),
            $cadena
        );

        return $cadena;
    }

}
