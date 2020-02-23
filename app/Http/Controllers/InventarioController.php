<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\HelpDesk\Inventario;
use App\Models\Admin\Sedes;
use App\Models\Admin\Usuarios;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class InventarioController extends Controller
{

    public function BuscarURL($Administrador){
        if($Administrador === 1){
            return 'admin';
        }else{
            return 'user';
        }
    }

    public function asignacionEquipoMovil(){
        $data = Request::all();
        $creadoPor          = (int)Session::get('IdUsuario');
        $buscarUsuario = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $EstadoEquipo       = (int)Request::get('estado');
        if($EstadoEquipo === 2){
            $reglas = array(
                'tipo_equipo'       =>  'required',
                'fecha_adquision'   =>  'required',
                'serial'            =>  'required',
                'marca'             =>  'required',
                'modelo'            =>  'required',
                'imei'              =>  'required',
                'capacidad'         =>  'required',
                'estado'            =>  'required',
                'area'              =>  'required',
                'nombre_asignado'   =>  'required'
            );
        }else{
            $reglas = array(
                'tipo_equipo'       =>  'required',
                'fecha_adquision'   =>  'required',
                'serial'            =>  'required',
                'marca'             =>  'required',
                'modelo'            =>  'required',
                'imei'              =>  'required',
                'capacidad'         =>  'required',
                'estado'            =>  'required'
            );
        }

        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $TipoEquipo         = Request::get('tipo_equipo');
            $FechaAdquisicion   = date('Y-m-d H:i:s', strtotime(Request::get('fecha_adquision')));
            $Serial             = Request::get('serial');
            $Marca              = Request::get('marca');
            $Modelo             = Request::get('modelo');
            $IMEI               = Request::get('imei');
            $Capacidad          = Request::get('capacidad');
            $LineaMovil         = Request::get('linea_movil');
            $Area               = Request::get('area');
            $NombreAsignado     = Request::get('nombre_asignado');
            $EstadoEquipo       = Request::get('estado');
            $BuscarInfoEquipo   = Inventario::BuscarInfoEquipoMovil($Serial);
            $TicketsBusqueda    = (int)count($BuscarInfoEquipo);
            foreach($BuscarInfoEquipo as $row){
                $NombreResponsable = $row->usuario;
            }
            if($TicketsBusqueda > 0){
                $verrors = array();
                array_push($verrors, 'El equipo con serial '.$Serial.' ya se ecuentra asignado a '.$NombreResponsable);
                return Redirect::to($url.'/mobile')->withErrors(['errors' => $verrors])->withRequest();
            }else{
                $RegistrarEquipo    = Inventario::RegistrarEquipoMovil($TipoEquipo,$FechaAdquisicion,$Serial,$Marca,$Modelo,$IMEI,
                                                                        $Capacidad,$LineaMovil,$Area,$NombreAsignado,$EstadoEquipo,$creadoPor);
                if($RegistrarEquipo){
                    $BuscarUltimo = Inventario::BuscarLastEquipoMovil($creadoPor);
                    foreach($BuscarUltimo as $row){
                        $idEquipoMovil = $row->id;
                    }
                    if($NombreAsignado){
                        Inventario::RegistrarAsignadoEM($TipoEquipo,$idEquipoMovil,$Area,$NombreAsignado,$EstadoEquipo,$creadoPor);
                    }
                    $destinationPath = null;
                    $filename        = null;
                    if (Request::hasFile('evidencia')) {
                        $files = Request::file('evidencia');
                        foreach($files as $file){
                            $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/equipo_movil';
                            $extension          = $file->getClientOriginalExtension();
                            $name               = $file->getClientOriginalName();
                            $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                            $nombrearchivo      = TicketsController::eliminar_tildes($nombrearchivo);
                            $filename           = 'Evidencia Equipo Movil No. '.$idEquipoMovil.'.'.$extension;
                            $uploadSuccess      = $file->move($destinationPath, $filename);
                            $archivofoto        = file_get_contents($uploadSuccess);
                            $NombreFoto         = $filename;
                            $actualizarEvidencia = Inventario::EvidenciaEM($idEquipoMovil,$NombreFoto);
                        }
                    }
                    $Comentario = 'Creación asignación de equipo movil';
                    Inventario::HistorialEM($idEquipoMovil,$Comentario,$EstadoEquipo,$creadoPor);
                    $verrors = 'Se registro con éxito el equipo movil '.$Marca.' - '.$Modelo;
                    return redirect($url.'/mobile')->with('mensaje', $verrors);
                }else{
                    $verrors = array();
                    array_push($verrors, 'Hubo un problema al registrar el equipo movil');
                    return Redirect::to($url.'/mobile')->withErrors(['errors' => $verrors])->withRequest();
                }
            }
        }else{
            return Redirect::to($url.'/mobile')->withErrors(['errors' => $verrors])->withRequest();
        }
    }

    public function actualizacionEquipoMovil(){
        $data = Request::all();
        $creadoPor          = (int)Session::get('IdUsuario');
        $buscarUsuario = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $EstadoEquipo       = (int)Request::get('estado_upd');
        if($EstadoEquipo === 2){
            $reglas = array(
                'tipo_equipo_upd'       =>  'required',
                'fecha_adquision_upd'   =>  'required',
                'serial_upd'            =>  'required',
                'marca_upd'             =>  'required',
                'modelo_upd'            =>  'required',
                'imei_upd'              =>  'required',
                'capacidad_upd'         =>  'required',
                'estado_upd'            =>  'required',
                'comentario'            =>  'required',
                'area_upd'              =>  'required',
                'nombre_asignado_upd'   =>  'required'
            );
        }else{
            $reglas = array(
                'tipo_equipo_upd'       =>  'required',
                'fecha_adquision_upd'   =>  'required',
                'serial_upd'            =>  'required',
                'marca_upd'             =>  'required',
                'modelo_upd'            =>  'required',
                'imei_upd'              =>  'required',
                'capacidad_upd'         =>  'required',
                'estado_upd'            =>  'required',
                'comentario'            =>  'required'
            );
        }

        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $TipoEquipo         = Request::get('tipo_equipo_upd');
            $FechaAdquisicion   = date('Y-m-d H:i:s', strtotime(Request::get('fecha_adquision_upd')));
            $Serial             = Request::get('serial_upd');
            $Marca              = Request::get('marca_upd');
            $Modelo             = Request::get('modelo_upd');
            $IMEI               = Request::get('imei_upd');
            $Capacidad          = Request::get('capacidad_upd');
            $LineaMovil         = Request::get('linea_movil_upd');
            $Area               = Request::get('area_upd');
            $NombreAsignado     = Request::get('nombre_asignado_upd');
            $EstadoEquipo       = Request::get('estado_upd');
            $idEquipoMovil      = Request::get('idEM');
            $Comentario         = Request::get('comentario');
            if(Request::get('desvincular')){
                $Desvincular = 1;
            }else{
                $Desvincular = 0;
            }
            $BuscarLineaMovilID = Inventario::BuscarLineaMovilID($LineaMovil,$idEquipoMovil);
            $countbusqueda = count($BuscarLineaMovilID);
            $BuscarNroLinea     = Inventario::BuscarNroLinea($LineaMovil);
            if($countbusqueda > 0){
                $ActualizarEquipoMovil = Inventario::ActualizarEquipoMovil($TipoEquipo,$FechaAdquisicion,$Serial,$Marca,$Modelo,$IMEI,$Capacidad,
                                        $LineaMovil,$Area,$NombreAsignado,$EstadoEquipo,$creadoPor,$idEquipoMovil,$Desvincular);
            }else{
                if($BuscarNroLinea){
                    foreach($BuscarNroLinea as $row){
                        $EstadoLinea = (int)$row->estado_equipo;
                        $NombreLinea = $row->nro_linea;
                    }
                    if($EstadoLinea === 1){
                        $ActualizarEquipoMovil = Inventario::ActualizarEquipoMovil($TipoEquipo,$FechaAdquisicion,$Serial,$Marca,$Modelo,$IMEI,$Capacidad,
                                                $LineaMovil,$Area,$NombreAsignado,$EstadoEquipo,$creadoPor,$idEquipoMovil,$Desvincular);
                    }else{
                        $verrors = array();
                        array_push($verrors, 'La linea '.$NombreLinea.' ya se encuentra asignada, por favor escoger otra');
                        return Redirect::to($url.'/mobile')->withErrors(['errors' => $verrors])->withRequest();
                    }
                }else{
                    $verrors = array();
                    array_push($verrors, 'Hubo un problema al actualizar el equipo movil');
                    return Redirect::to($url.'/mobile')->withErrors(['errors' => $verrors])->withRequest();
                }
            }


            if($ActualizarEquipoMovil){

                Inventario::RegistrarAsignadoEM($TipoEquipo,$idEquipoMovil,$Area,$NombreAsignado,$EstadoEquipo,$creadoPor);

                $destinationPath = null;
                $filename        = null;
                if (Request::hasFile('evidencia_upd')) {
                    $files = Request::file('evidencia_upd');
                    foreach($files as $file){
                        $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/equipo_movil';
                        $extension          = $file->getClientOriginalExtension();
                        $name               = $file->getClientOriginalName();
                        $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                        $nombrearchivo      = TicketsController::eliminar_tildes($nombrearchivo);
                        $filename           = 'Evidencia Equipo Movil No. '.$idEquipoMovil.'.'.$extension;
                        $uploadSuccess      = $file->move($destinationPath, $filename);
                        $archivofoto        = file_get_contents($uploadSuccess);
                        $NombreFoto         = $filename;
                        $actualizarEvidencia = Inventario::EvidenciaEM($idEquipoMovil,$NombreFoto);
                    }
                }
                Inventario::HistorialEM($idEquipoMovil,$Comentario,$EstadoEquipo,$creadoPor);
                $verrors = 'Se actualizo con éxito el equipo movil '.$Marca.' - '.$Modelo;
                return redirect($url.'/mobile')->with('mensaje', $verrors);
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al actualizar el equipo movil');
                return Redirect::to($url.'/mobile')->withErrors(['errors' => $verrors])->withRequest();
            }
        }else{
            return Redirect::to($url.'/mobile')->withErrors(['errors' => $verrors])->withRequest();
        }
    }

    public function asignacionLineaMovil(){
        $data           = Request::all();
        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $Estado             = (int)Request::get('estado');
        if($Estado === 2){
            $reglas = array(
                'nro_linea'         =>  'required',
                'fecha_adquision'   =>  'required',
                'serial'            =>  'required',
                'activo'            =>  'required',
                'proveedores'       =>  'required',
                'plan'              =>  'required',
                'pto_cargo'         =>  'required',
                'cc'                =>  'required',
                'area'              =>  'required',
                'personal'          =>  'required',
                'estado'            =>  'required'
            );
        }else{
            $reglas = array(
                'nro_linea'         =>  'required',
                'fecha_adquision'   =>  'required',
                'serial'            =>  'required',
                'activo'            =>  'required',
                'proveedores'       =>  'required',
                'plan'              =>  'required',
                'pto_cargo'         =>  'required',
                'estado'            =>  'required'
            );
        }

        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $NroLinea           = Request::get('nro_linea');
            $FechaAdquisicion   = date('Y-m-d H:i:s', strtotime(Request::get('fecha_adquision')));
            $Serial             = Request::get('serial');
            $Activo             = Request::get('activo');
            $Proveedor          = Request::get('proveedores');
            $Plan               = Request::get('plan');
            $PtoCargo           = Request::get('pto_cargo');
            $Cc                 = Request::get('cc');
            $Area               = Request::get('area');
            $Personal           = Request::get('personal');
            $Estado             = Request::get('estado');
            $BuscarInfoEquipo   = Inventario::BuscarInfoLineaMovil($Serial);
            $TicketsBusqueda    = (int)count($BuscarInfoEquipo);
            foreach($BuscarInfoEquipo as $row){
                $NombreResponsable = $row->personal;
            }
            if($TicketsBusqueda > 0){
                $verrors = array();
                array_push($verrors, 'El equipo con serial '.$Serial.' ya se ecuentra asignado a '.$NombreResponsable);
                return Redirect::to($url.'/lineMobile')->withErrors(['errors' => $verrors])->withRequest();
            }else{
                $RegistrarLineaMovil = Inventario::RegistrarLineaMovil($NroLinea,$FechaAdquisicion,$Serial,$Activo,$Proveedor,$Plan,$PtoCargo,$Cc,$Area,$Personal,$Estado,$creadoPor);
                if($RegistrarLineaMovil){
                    $BuscarUltimo = Inventario::BuscarLastLineaMovil($creadoPor);
                    foreach($BuscarUltimo as $row){
                        $idEquipoMovil = $row->id;
                    }
                    if($Personal){
                        Inventario::RegistrarAsignadoLM($idEquipoMovil,$Area,$Personal,$Estado,$creadoPor);
                    }
                    $destinationPath = null;
                    $filename        = null;
                    if (Request::hasFile('evidencia')) {
                        $files = Request::file('evidencia');
                        foreach($files as $file){
                            $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/lineas/';
                            $extension          = $file->getClientOriginalExtension();
                            $name               = $file->getClientOriginalName();
                            $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                            $nombrearchivo      = TicketsController::eliminar_tildes($nombrearchivo);
                            $filename           = 'Evidencia Linea No. '.$idEquipoMovil.'.'.$extension;
                            $uploadSuccess      = $file->move($destinationPath, $filename);
                            $archivofoto        = file_get_contents($uploadSuccess);
                            $NombreFoto         = $filename;
                            $actualizarEvidencia = Inventario::EvidenciaLM($idEquipoMovil,$NombreFoto);
                        }
                    }
                    $Comentario = 'Creación asignación de linea movil Nro. '.$NroLinea;
                    Inventario::HistorialLM($idEquipoMovil,$Comentario,$Estado,$creadoPor);
                    $verrors = 'Se registro con éxito la linea movil Nro. '.$NroLinea;
                    return redirect($url.'/lineMobile')->with('mensaje', $verrors);
                }else{
                    $verrors = array();
                    array_push($verrors, 'Hubo un problema al registrar la linea movil');
                    return Redirect::to($url.'/lineMobile')->withErrors(['errors' => $verrors])->withRequest();
                }
            }
        }else{
            return Redirect::to($url.'/lineMobile')->withErrors(['errors' => $verrors])->withRequest();
        }
    }

    public function actualizacionLineaMovil(){
        $data           = Request::all();
        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $Estado             = (int)Request::get('estado_upd');
        if($Estado === 2){
            $reglas = array(
                'nro_linea_upd'         =>  'required',
                'fecha_adquision_upd'   =>  'required',
                'serial_upd'            =>  'required',
                'activo_upd'            =>  'required',
                'proveedores_upd'       =>  'required',
                'plan_upd'              =>  'required',
                'pto_cargo_upd'         =>  'required',
                'cc_upd'                =>  'required',
                'area_upd'              =>  'required',
                'personal_upd'          =>  'required',
                'estado_upd'            =>  'required',
                'comentario'            =>  'required'
            );
        }else{
            $reglas = array(
                'nro_linea_upd'         =>  'required',
                'fecha_adquision_upd'   =>  'required',
                'serial_upd'            =>  'required',
                'activo_upd'            =>  'required',
                'proveedores_upd'       =>  'required',
                'plan_upd'              =>  'required',
                'pto_cargo_upd'         =>  'required',
                'estado_upd'            =>  'required',
                'comentario'            =>  'required'
            );
        }
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $NroLinea           = Request::get('nro_linea_upd');
            $FechaAdquisicion   = date('Y-m-d H:i:s', strtotime(Request::get('fecha_adquision_upd')));
            $Serial             = Request::get('serial_upd');
            $Activo             = Request::get('activo_upd');
            $Proveedor          = Request::get('proveedores_upd');
            $Plan               = Request::get('plan_upd');
            $PtoCargo           = Request::get('pto_cargo_upd');
            $Cc                 = Request::get('cc_upd');
            $Area               = Request::get('area_upd');
            $Personal           = Request::get('personal_upd');
            $Estado             = Request::get('estado_upd');
            $IdLineaMovil       = Request::get('idLM');
            $Comentario         = Request::get('comentario');

            $ActualizacionLineaMovil = Inventario::ActualizarLineaMovil($NroLinea,$FechaAdquisicion,$Serial,$Activo,$Proveedor,$Plan,$PtoCargo,$Cc,$Area,$Personal,$Estado,$creadoPor,$IdLineaMovil);

            if($ActualizacionLineaMovil){

                Inventario::RegistrarAsignadoLM($IdLineaMovil,$Area,$Personal,$Estado,$creadoPor);

                $destinationPath = null;
                $filename        = null;
                if (Request::hasFile('evidencia_upd')) {
                    $files = Request::file('evidencia_upd');
                    foreach($files as $file){
                        $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/lineas/';
                        $extension          = $file->getClientOriginalExtension();
                        $name               = $file->getClientOriginalName();
                        $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                        $nombrearchivo      = TicketsController::eliminar_tildes($nombrearchivo);
                        $filename           = 'Evidencia Linea No. '.$IdLineaMovil.'.'.$extension;
                        $uploadSuccess      = $file->move($destinationPath, $filename);
                        $archivofoto        = file_get_contents($uploadSuccess);
                        $NombreFoto         = $filename;
                        $actualizarEvidencia = Inventario::EvidenciaLM($IdLineaMovil,$NombreFoto);
                    }
                }
                Inventario::HistorialLM($IdLineaMovil,$Comentario,$Estado,$creadoPor);
                $verrors = 'Se actualizo con éxito la linea movil '.$NroLinea;
                return redirect($url.'/lineMobile')->with('mensaje', $verrors);
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al actualizar la linea movil');
                return Redirect::to($url.'/lineMobile')->withErrors(['errors' => $verrors])->withRequest();
            }

        }else{
            return Redirect::to($url.'/lineMobile')->withErrors(['errors' => $verrors])->withRequest();
        }
    }

    public function ingresoEquipo(){
        $data           = Request::all();
        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $reglas = array(
            'tipo_equipo'       =>  'required',
            'tipo_ingreso'      =>  'required',
            'fecha_adquision'   =>  'required',
            'serial'            =>  'required',
            'marca'             =>  'required',
            'estado'            =>  'required'
        );
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $TipoEquipo         = Request::get('tipo_equipo');
            $TipoIngreso        = Request::get('tipo_ingreso');
            if(Request::get('emp_renting')){
                $EmpresaRenting = Request::get('emp_renting');
            }else{
                $EmpresaRenting = 'SIN EMPRESA';
            }
            $FechaAdquisicion   = date('Y-m-d H:i:s', strtotime(Request::get('fecha_adquision')));
            $Serial             = Request::get('serial');
            $Marca              = Request::get('marca');
            $Procesador         = Request::get('procesador');
            $VelProcesador      = Request::get('vel_procesador');
            $DiscoDuro          = Request::get('disco_duro');
            $MemoriaRam         = Request::get('memoria_ram');
            $EstadoEquipo       = Request::get('estado');
            $BuscarSerial       = Inventario::BuscarSerialEquipo($Serial);
            $TotalBusqueda      = (int)count($BuscarSerial);
            if($TotalBusqueda > 0){
                $verrors = array();
                array_push($verrors, 'Ya existe un equipo con el serial '.$Serial);
                return Redirect::to($url.'/desktops')->withErrors(['errors' => $verrors])->withRequest();
            }else{

                $IngresarEquipo = Inventario::IngresarEquipo($TipoEquipo,$TipoIngreso,$EmpresaRenting,$FechaAdquisicion,$Serial,$Marca,$Procesador,$VelProcesador,$DiscoDuro,$MemoriaRam,$EstadoEquipo,$creadoPor);
                if($IngresarEquipo){
                    $BuscarUltimo = Inventario::BuscarLastEquipo($creadoPor);
                    foreach($BuscarUltimo as $row){
                        $idEquipo = $row->id;
                    }
                    $destinationPath = null;
                    $filename        = null;
                    if (Request::hasFile('evidencia')) {
                        $files = Request::file('evidencia');
                        foreach($files as $file){
                            $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/equipos/';
                            $extension          = $file->getClientOriginalExtension();
                            $name               = $file->getClientOriginalName();
                            $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                            $nombrearchivo      = TicketsController::eliminar_tildes($nombrearchivo);
                            $filename           = 'Evidencia Equipo No. '.$idEquipo.'.'.$extension;
                            $uploadSuccess      = $file->move($destinationPath, $filename);
                            $archivofoto        = file_get_contents($uploadSuccess);
                            $NombreFoto         = $filename;
                            $actualizarEvidencia = Inventario::EvidenciaIE($idEquipo,$NombreFoto);
                        }
                    }
                    $Comentario = 'Creación de equipo Nro. '.$idEquipo.' en el sistema';
                    Inventario::HistorialE($idEquipo,$Comentario,$EstadoEquipo,$creadoPor);
                    $verrors = 'Se ingreso satisfactoriamente el equipo No. de Activo '.$idEquipo;
                    return redirect($url.'/desktops')->with('mensaje', $verrors);
                }else{
                    $verrors = array();
                    array_push($verrors, 'Hubo un problema al ingresar el equipo');
                    return Redirect::to($url.'/desktops')->withErrors(['errors' => $verrors])->withRequest();
                }
            }
        }else{
            return Redirect::to($url.'/desktops')->withErrors(['errors' => $verrors])->withRequest();
        }
    }

    public function actualizacionEquipo(){
        $data           = Request::all();
        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $reglas = array(
            'tipo_equipo_upd'       =>  'required',
            'tipo_ingreso_upd'      =>  'required',
            'fecha_adquision_upd'   =>  'required',
            'serial_upd'            =>  'required',
            'marca_upd'             =>  'required',
            'comentario'            =>  'required',
            'estado_upd'            =>  'required'
        );
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $TipoEquipo         = Request::get('tipo_equipo_upd');
            $TipoIngreso        = Request::get('tipo_ingreso_upd');
            if(Request::get('emp_renting_upd')){
                $EmpresaRenting = Request::get('emp_renting_upd');
            }else{
                $EmpresaRenting = 'SIN EMPRESA';
            }
            $FechaAdquisicion   = date('Y-m-d H:i:s', strtotime(Request::get('fecha_adquision_upd')));
            $Serial             = Request::get('serial_upd');
            $Marca              = Request::get('marca_upd');
            $Procesador         = Request::get('procesador_upd');
            $VelProcesador      = Request::get('vel_procesador_upd');
            $DiscoDuro          = Request::get('disco_duro_upd');
            $MemoriaRam         = Request::get('memoria_ram_upd');
            $EstadoEquipo       = Request::get('estado_upd');
            $Comentario         = Request::get('comentario');
            $IdEquipo           = Request::get('idE');

            $ActualizarEquipo   = Inventario::ActualizarEquipo($TipoEquipo,$TipoIngreso,$EmpresaRenting,$FechaAdquisicion,$Serial,$Marca,$Procesador,$VelProcesador,$DiscoDuro,$MemoriaRam,$EstadoEquipo,$creadoPor,$IdEquipo);

            if($ActualizarEquipo){
                $destinationPath = null;
                $filename        = null;
                if (Request::hasFile('evidencia_upd')) {
                    $files = Request::file('evidencia_upd');
                    foreach($files as $file){
                        $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/equipos/';
                        $extension          = $file->getClientOriginalExtension();
                        $name               = $file->getClientOriginalName();
                        $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                        $nombrearchivo      = TicketsController::eliminar_tildes($nombrearchivo);
                        $filename           = 'Evidencia Equipo No. '.$IdEquipo.'.'.$extension;
                        $uploadSuccess      = $file->move($destinationPath, $filename);
                        $archivofoto        = file_get_contents($uploadSuccess);
                        $NombreFoto         = $filename;
                        $actualizarEvidencia = Inventario::EvidenciaIE($IdEquipo,$NombreFoto);
                    }
                }
                Inventario::HistorialE($IdEquipo,$Comentario,$EstadoEquipo,$creadoPor);
                $verrors = 'Se actualizo satisfactoriamente el equipo No. de Activo '.$IdEquipo;
                return redirect($url.'/desktops')->with('mensaje', $verrors);
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al actualizar el equipo');
                return Redirect::to($url.'/desktops')->withErrors(['errors' => $verrors])->withRequest();
            }
        }else{
            return Redirect::to($url.'/desktops')->withErrors(['errors' => $verrors])->withRequest();
        }
    }

    public function ingresoPeriferico(){
        $data           = Request::all();
        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $reglas = array(
            'tipo_periferico'   =>  'required',
            'tipo_ingreso'      =>  'required',
            'fecha_adquision'   =>  'required',
            'serial'            =>  'required',
            'marca'             =>  'required',
            'estado'            =>  'required'
        );
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $TipoPeriferico     = (int)Request::get('tipo_periferico');
            $TipoIngreso        = (int)Request::get('tipo_ingreso');
            if(Request::get('emp_renting')){
                $EmpresaRent    = Request::get('emp_renting');
            }else{
                $EmpresaRent    = 'SIN EMPRESA';
            }
            $FechaAdquisicion   = date('Y-m-d H:i:s', strtotime(Request::get('fecha_adquision')));
            $Serial             = Request::get('serial');
            $Marca              = Request::get('marca');
            $Tamano             = Request::get('tamano');
            $Estado             = (int)Request::get('estado');
            $BuscarSerial       = Inventario::BuscarSerialEquipo($Serial);
            $TotalBusqueda      = (int)count($BuscarSerial);
            if($TotalBusqueda > 0){
                $verrors = array();
                array_push($verrors, 'Ya existe un periferico con el serial '.$Serial);
                return Redirect::to($url.'/periferic')->withErrors(['errors' => $verrors])->withRequest();
            }else{
                $CrearPeriferico    = Inventario::CrearPeriferico($TipoPeriferico,$TipoIngreso,$EmpresaRent,$FechaAdquisicion,$Serial,$Marca,$Tamano,$Estado,$creadoPor);
                if($CrearPeriferico){
                    $BuscarUltimo = Inventario::BuscarLastPeriferico($creadoPor);
                        foreach($BuscarUltimo as $row){
                            $idPeriferico = $row->id;
                        }
                        switch($TipoPeriferico){
                            Case 1 :    $Carpeta    = 'pantallas/';
                                        $evidencia  = 'Pantalla';
                                        break;
                            Case 2 :    $Carpeta    = 'mouse/';
                                        $evidencia  = 'Mouse';
                                        break;
                            Case 3 :    $Carpeta    = 'teclados/';
                                        $evidencia  = 'Teclado';
                                        break;
                            Case 4 :    $Carpeta    = 'guaya/';
                                        $evidencia  = 'Guaya';
                                        break;
                            Case 5 :    $Carpeta    = 'cargador/';
                                        $evidencia  = 'Cargador';
                                        break;
                        }
                        Inventario::InsertarPeriferico($TipoPeriferico,$Serial,$Marca,$Tamano,$Estado,$FechaAdquisicion,$idPeriferico);
                        $destinationPath = null;
                        $filename        = null;
                        if (Request::hasFile('evidencia')) {
                            $files = Request::file('evidencia');
                            foreach($files as $file){
                                $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/'.$Carpeta;
                                $extension          = $file->getClientOriginalExtension();
                                $name               = $file->getClientOriginalName();
                                $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                                $nombrearchivo      = TicketsController::eliminar_tildes($nombrearchivo);
                                $filename           = 'Evidencia '.$evidencia.' No. '.$idPeriferico.'.'.$extension;
                                $uploadSuccess      = $file->move($destinationPath, $filename);
                                $archivofoto        = file_get_contents($uploadSuccess);
                                $NombreFoto         = $filename;
                                $actualizarEvidencia = Inventario::EvidenciaIP($idPeriferico,$NombreFoto);
                            }
                        }
                        $Comentario = 'Ingreso de '.$evidencia.' Nro. '.$idPeriferico.' en el sistema';
                        Inventario::HistorialP($idPeriferico,$Comentario,$Estado,$creadoPor);
                        $verrors = 'Se ingreso satisfactoriamente el/la '.$evidencia.' No. de Activo '.$idPeriferico;
                        return redirect($url.'/periferic')->with('mensaje', $verrors);
                }else{
                    $verrors = array();
                    array_push($verrors, 'Hubo un problema al ingresar el/la '.$evidencia);
                    return Redirect::to($url.'/periferic')->withErrors(['errors' => $verrors])->withRequest();
                }
            }
        }else{
            return Redirect::to($url.'/periferic')->withErrors(['errors' => $verrors])->withRequest();
        }
    }

    public function actualizacionPeriferico(){
        $data           = Request::all();
        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $reglas = array(
            'tipo_periferico_upd'   =>  'required',
            'tipo_ingreso_upd'      =>  'required',
            'fecha_adquision_upd'   =>  'required',
            'serial_upd'            =>  'required',
            'marca_upd'             =>  'required',
            'estado_upd'            =>  'reduired',
            'comentario'            =>  'required'
        );
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $TipoPeriferico     = (int)Request::get('tipo_periferico_upd');
            $TipoIngreso        = (int)Request::get('tipo_ingreso_upd');
            if(Request::get('emp_renting_upd')){
                $EmpresaRent    = Request::get('emp_renting_upd');
            }else{
                $EmpresaRent    = 'SIN EMPRESA';
            }
            $FechaAdquisicion   = date('Y-m-d H:i:s', strtotime(Request::get('fecha_adquision_upd')));
            $Serial             = Request::get('serial_upd');
            $Marca              = Request::get('marca_upd');
            $Tamano             = Request::get('tamano_upd');
            $Estado             = (int)Request::get('estado_upd');
            $Comentario         = Request::get('comentario');
            $IdPeriferico       = (int)Request::get('idP');

            $ActualizarPeriferico = Inventario::ActualizarPeriferico($TipoPeriferico,$TipoIngreso,$EmpresaRent,$FechaAdquisicion,$Serial,$Marca,$Tamano,$Estado,$creadoPor,$IdPeriferico);

            if($ActualizarPeriferico){
                switch($TipoPeriferico){
                    Case 1 :    $Carpeta    = 'pantallas/';
                                $evidencia  = 'Pantalla';
                                break;
                    Case 2 :    $Carpeta    = 'mouse/';
                                $evidencia  = 'Mouse';
                                break;
                    Case 3 :    $Carpeta    = 'teclados/';
                                $evidencia  = 'Teclado';
                                break;
                    Case 4 :    $Carpeta    = 'guaya/';
                                $evidencia  = 'Guaya';
                                break;
                    Case 5 :    $Carpeta    = 'cargador/';
                                $evidencia  = 'Cargador';
                                break;
                }
                Inventario::ActualizarTPeriferico($TipoPeriferico,$Serial,$Marca,$Tamano,$Estado,$FechaAdquisicion,$IdPeriferico);
                $destinationPath = null;
                $filename        = null;
                if (Request::hasFile('evidencia_upd')) {
                    $files = Request::file('evidencia_upd');
                    foreach($files as $file){
                        $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/'.$Carpeta;
                        $extension          = $file->getClientOriginalExtension();
                        $name               = $file->getClientOriginalName();
                        $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                        $nombrearchivo      = TicketsController::eliminar_tildes($nombrearchivo);
                        $filename           = 'Evidencia '.$evidencia.' No. '.$IdPeriferico.'.'.$extension;
                        $uploadSuccess      = $file->move($destinationPath, $filename);
                        $archivofoto        = file_get_contents($uploadSuccess);
                        $NombreFoto         = $filename;
                        $actualizarEvidencia = Inventario::EvidenciaIP($IdPeriferico,$NombreFoto);
                    }
                }
                Inventario::HistorialP($IdPeriferico,$Comentario,$Estado,$creadoPor);
                $verrors = 'Se actualizo satisfactoriamente el/la '.$evidencia.' No. de Activo '.$IdPeriferico;
                return redirect($url.'/periferic')->with('mensaje', $verrors);
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al actualizar el/la '.$evidencia);
                return Redirect::to($url.'/periferic')->withErrors(['errors' => $verrors])->withRequest();
            }

        }else{
            return Redirect::to($url.'/periferic')->withErrors(['errors' => $verrors])->withRequest();
        }
    }

    public function ingresarConsumible(){
        $data           = Request::all();
        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $reglas = array(
            'tipo_consumible'   =>  'required',
            'tipo_ingreso'      =>  'required',
            'fecha_adquision'   =>  'required',
            'serial'            =>  'required',
            'marca'             =>  'required',
            'estado'            =>  'required'
        );
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $TipoConsumible     = (int)Request::get('tipo_consumible');
            $TipoIngreso        = (int)Request::get('tipo_ingreso');
            if(Request::get('emp_renting')){
                $EmpresaRent    = Request::get('emp_renting');
            }else{
                $EmpresaRent    = 'SIN EMPRESA';
            }
            $FechaAdquisicion   = date('Y-m-d H:i:s', strtotime(Request::get('fecha_adquision')));
            $Serial             = Request::get('serial');
            $Marca              = Request::get('marca');
            $Modelo             = Request::get('modelo');
            $CompaRef           = Request::get('compa_ref');
            $CompaMod           = Request::get('compa_ref');
            $Estado             = (int)Request::get('estado');
            $BuscarSerial       = Inventario::BuscarSerialConsumible($Serial);
            $TotalBusqueda      = (int)count($BuscarSerial);
            if($TotalBusqueda > 0){
                $verrors = array();
                array_push($verrors, 'Ya existe un consumible con el serial '.$Serial);
                return Redirect::to($url.'/consumible')->withErrors(['errors' => $verrors])->withRequest();
            }else{
                $CrearConsumible = Inventario::CrearConsumible($TipoConsumible,$TipoIngreso,$EmpresaRent,$FechaAdquisicion,$Serial,$Marca,$Modelo,$CompaRef,$CompaMod,$Estado,$creadoPor);
                if($CrearConsumible){
                    $BuscarUltimo = Inventario::BuscarLastConsumible($creadoPor);
                    foreach($BuscarUltimo as $row){
                        $idConsumible = $row->id;
                    }
                    $destinationPath = null;
                    $filename        = null;
                    if (Request::hasFile('evidencia')) {
                        $files = Request::file('evidencia');
                        foreach($files as $file){
                            $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/consumibles/';
                            $extension          = $file->getClientOriginalExtension();
                            $name               = $file->getClientOriginalName();
                            $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                            $nombrearchivo      = TicketsController::eliminar_tildes($nombrearchivo);
                            $filename           = 'Evidencia Consumible No. '.$idConsumible.'.'.$extension;
                            $uploadSuccess      = $file->move($destinationPath, $filename);
                            $archivofoto        = file_get_contents($uploadSuccess);
                            $NombreFoto         = $filename;
                            $actualizarEvidencia = Inventario::EvidenciaIC($idConsumible,$NombreFoto);
                        }
                    }
                    $Comentario = 'Ingreso de Consumible Nro. '.$idConsumible.' en el sistema';
                    Inventario::HistorialC($idConsumible,$Comentario,$Estado,$creadoPor);
                    $verrors = 'Se ingreso satisfactoriamente el consumible No. de Activo '.$idConsumible;
                    return redirect($url.'/consumible')->with('mensaje', $verrors);
                }else{
                    $verrors = array();
                    array_push($verrors, 'Hubo un problema al crear el Consumible');
                    return Redirect::to($url.'/consumible')->withErrors(['errors' => $verrors])->withRequest();
                }
            }
        }else{
            return Redirect::to($url.'/consumible')->withErrors(['errors' => $verrors])->withRequest();
        }
    }

    public function actualizarConsumible(){
        $data           = Request::all();
        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $reglas = array(
            'tipo_consumible_upd'   =>  'required',
            'tipo_ingreso_upd'      =>  'required',
            'fecha_adquision_upd'   =>  'required',
            'serial_upd'            =>  'required',
            'marca_upd'             =>  'required',
            'estado_upd'            =>  'required',
            'comentario'            =>  'required'
        );
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $TipoConsumible         = (int)Request::get('tipo_consumible_upd');
            $TipoIngreso            = (int)Request::get('tipo_ingreso_upd');
            if(Request::get('emp_renting_upd')){
                $EmpresaRent        = Request::get('emp_renting_upd');
            }else{
                $EmpresaRent        = 'SIN EMPRESA';
            }
            $FechaAdquisicion       = date('Y-m-d H:i:s', strtotime(Request::get('fecha_adquision_upd')));
            $Serial                 = Request::get('serial_upd');
            $Marca                  = Request::get('marca_upd');
            $Modelo                 = Request::get('modelo_upd');
            $CompaRef               = Request::get('compa_ref_upd');
            $CompaMod               = Request::get('compa_ref_upd');
            $Estado                 = (int)Request::get('estado_upd');
            $Comentario             = Request::get('comentario');
            $IdConsumible           = (int)Request::get('idC');
            $ActualizarConsumible   = Inventario::ActualizarConsumible($TipoConsumible,$TipoIngreso,$EmpresaRent,$FechaAdquisicion,$Serial,$Marca,$Modelo,$CompaRef,$CompaMod,$Estado,$creadoPor,$IdConsumible);
            if($ActualizarConsumible){
                $destinationPath = null;
                $filename        = null;
                if (Request::hasFile('evidencia')) {
                    $files = Request::file('evidencia');
                    foreach($files as $file){
                        $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/consumibles/';
                        $extension          = $file->getClientOriginalExtension();
                        $name               = $file->getClientOriginalName();
                        $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                        $nombrearchivo      = TicketsController::eliminar_tildes($nombrearchivo);
                        $filename           = 'Evidencia Consumible No. '.$IdConsumible.'.'.$extension;
                        $uploadSuccess      = $file->move($destinationPath, $filename);
                        $archivofoto        = file_get_contents($uploadSuccess);
                        $NombreFoto         = $filename;
                        $actualizarEvidencia = Inventario::EvidenciaIC($IdConsumible,$NombreFoto);
                    }
                }
                Inventario::HistorialC($IdConsumible,$Comentario,$Estado,$creadoPor);
                $verrors = 'Se actualizo satisfactoriamente el consumible No. de Activo '.$IdConsumible;
                return redirect($url.'/consumible')->with('mensaje', $verrors);
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al actualizar el Consumible');
                return Redirect::to($url.'/consumible')->withErrors(['errors' => $verrors])->withRequest();
            }
        }else{
            return Redirect::to($url.'/consumible')->withErrors(['errors' => $verrors])->withRequest();
        }
    }

    public function ingresarImpresora(){
        $data           = Request::all();
        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $reglas = array(
            'tipo_impresora'    =>  'required',
            'tipo_ingreso'      =>  'required',
            'fecha_adquision'   =>  'required',
            'serial'            =>  'required',
            'marca'             =>  'required',
            'estado'            =>  'required'
        );
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $TipoImpresora      = Request::get('tipo_impresora');
            $TipoIngreso        = (int)Request::get('tipo_ingreso');
            if(Request::get('emp_renting')){
                $EmpresaRent    = Request::get('emp_renting');
            }else{
                $EmpresaRent    = 'SIN EMPRESA';
            }
            $FechaAdquisicion   = date('Y-m-d H:i:s', strtotime(Request::get('fecha_adquision')));
            $Serial             = Request::get('serial');
            $Marca              = Request::get('marca');
            $Ip                 = Request::get('ip');
            $IdConsumible       = (int)Request::get('id_consumible');
            $Estado             = (int)Request::get('estado');
            $BuscarSerial       = Inventario::BuscarSerialImpresora($Serial);
            $TotalBusqueda      = (int)count($BuscarSerial);
            if($TotalBusqueda > 0){
                $verrors = array();
                array_push($verrors, 'Ya existe una impresora con el serial '.$Serial);
                return Redirect::to($url.'/printers')->withErrors(['errors' => $verrors])->withRequest();
            }else{
                $CrearImpresora = Inventario::CrearImpresora($TipoImpresora,$TipoIngreso,$EmpresaRent,$FechaAdquisicion,$Serial,$Marca,$Ip,$IdConsumible,$Estado,$creadoPor);
                if($CrearImpresora){
                    $BuscarUltimo = Inventario::BuscarLastImpresora($creadoPor);
                    foreach($BuscarUltimo as $row){
                        $idImpresora = $row->id;
                    }
                    $destinationPath = null;
                    $filename        = null;
                    if (Request::hasFile('evidencia')) {
                        $files = Request::file('evidencia');
                        foreach($files as $file){
                            $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/impresoras/';
                            $extension          = $file->getClientOriginalExtension();
                            $name               = $file->getClientOriginalName();
                            $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                            $nombrearchivo      = TicketsController::eliminar_tildes($nombrearchivo);
                            $filename           = 'Evidencia Impresora No. '.$idImpresora.'.'.$extension;
                            $uploadSuccess      = $file->move($destinationPath, $filename);
                            $archivofoto        = file_get_contents($uploadSuccess);
                            $NombreFoto         = $filename;
                            $actualizarEvidencia = Inventario::EvidenciaI($idImpresora,$NombreFoto);
                        }
                    }
                    $Comentario = 'Ingreso de Impresora Nro. '.$idImpresora.' en el sistema';
                    Inventario::HistorialI($idImpresora,$Comentario,$Estado,$creadoPor);
                    $verrors = 'Se ingreso satisfactoriamente la impresora No. de Activo '.$idImpresora;
                    return redirect($url.'/printers')->with('mensaje', $verrors);
                }else{
                    $verrors = array();
                    array_push($verrors, 'Hubo un problema al ingresar la impresora');
                    return Redirect::to($url.'/printers')->withErrors(['errors' => $verrors])->withRequest();
                }
            }
        }else{
            return Redirect::to($url.'/printers')->withErrors(['errors' => $verrors])->withRequest();
        }
    }

    public function actualizarImpresora(){
        $data           = Request::all();
        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $reglas = array(
            'tipo_impresora_upd'    =>  'required',
            'tipo_ingreso_upd'      =>  'required',
            'fecha_adquision_upd'   =>  'required',
            'serial_upd'            =>  'required',
            'marca_upd'             =>  'required',
            'estado_upd'            =>  'required',
            'comentario'            =>  'required'
        );
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $TipoImpresora      = Request::get('tipo_impresora_upd');
            $TipoIngreso        = (int)Request::get('tipo_ingreso_upd');
            if(Request::get('emp_renting_upd')){
                $EmpresaRent    = Request::get('emp_renting_upd');
            }else{
                $EmpresaRent    = 'SIN EMPRESA';
            }
            $FechaAdquisicion   = date('Y-m-d H:i:s', strtotime(Request::get('fecha_adquision_upd')));
            $Serial             = Request::get('serial_upd');
            $Marca              = Request::get('marca_upd');
            $Ip                 = Request::get('ip_upd');
            $IdConsumible       = (int)Request::get('id_consumible_upd');
            $Estado             = (int)Request::get('estado_upd');
            $Comentario         = Request::get('comentario');
            $IdImpresora        = (int)Request::get('idI');
            $EstadoConsumible   = Inventario::EstadoConsumible($IdConsumible);
            $BuscarConsumible   = Inventario::BuscarConsumibleID($IdConsumible,$IdImpresora);
            $countBusqueda      = count($BuscarConsumible);
            if($countBusqueda > 0){
                $ActualizarImpresora = Inventario::ActualizarImpresora($TipoImpresora,$TipoIngreso,$EmpresaRent,$FechaAdquisicion,$Serial,$Marca,$Ip,$IdConsumible,$Estado,$creadoPor,$Comentario,$IdImpresora);
            }else{
                if($EstadoConsumible){
                    foreach($EstadoConsumible as $row){
                        $EstadoConsumible = (int)$row->estado_consumible;
                        $NombreConsumible = $row->marca.' - '.$row->serial;
                    }
                    if($EstadoConsumible === 1){
                        $ActualizarImpresora = Inventario::ActualizarImpresora($TipoImpresora,$TipoIngreso,$EmpresaRent,$FechaAdquisicion,$Serial,$Marca,$Ip,$IdConsumible,$Estado,$creadoPor,$Comentario,$IdImpresora);
                    }else{
                        $verrors = array();
                        array_push($verrors, 'El consumible '.$NombreConsumible.' ya se encuentra asignado, por favor escoger otro');
                        return Redirect::to($url.'/printers')->withErrors(['errors' => $verrors])->withRequest();
                    }
                }else{
                    $verrors = array();
                    array_push($verrors, 'Hubo un problema al actualizar la impresora');
                    return Redirect::to($url.'/printers')->withErrors(['errors' => $verrors])->withRequest();
                }
            }
            if($ActualizarImpresora){

                $destinationPath = null;
                $filename        = null;
                if (Request::hasFile('evidencia')) {
                    $files = Request::file('evidencia');
                    foreach($files as $file){
                        $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/impresoras/';
                        $extension          = $file->getClientOriginalExtension();
                        $name               = $file->getClientOriginalName();
                        $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                        $nombrearchivo      = TicketsController::eliminar_tildes($nombrearchivo);
                        $filename           = 'Evidencia Impresora No. '.$IdImpresora.'.'.$extension;
                        $uploadSuccess      = $file->move($destinationPath, $filename);
                        $archivofoto        = file_get_contents($uploadSuccess);
                        $NombreFoto         = $filename;
                        $actualizarEvidencia = Inventario::EvidenciaI($IdImpresora,$NombreFoto);
                    }
                }

                Inventario::HistorialI($IdImpresora,$Comentario,$Estado,$creadoPor);
                $verrors = 'Se actualizo satisfactoriamente la impresora No. de Activo '.$IdImpresora;
                return redirect($url.'/printers')->with('mensaje', $verrors);
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al actualizar la impresora');
                return Redirect::to($url.'/printers')->withErrors(['errors' => $verrors])->withRequest();
            }

        }else{
            return Redirect::to($url.'/printers')->withErrors(['errors' => $verrors])->withRequest();
        }
    }

    public function buscarEquipo(){
        $data               = Request::all();
        $id                 = (int)Request::get('tipo_equipo');
        $MarcaSerial        = array();
        $buscarUsuario      = Inventario::BuscarXTipoEquipo($id);
        // $MarcaSerial[0]     = 'Seleccione: ';
        foreach ($buscarUsuario as $row){
            $MarcaSerial[$row->id] = $row->marca.' - '.$row->serial;
        }
        return \Response::json(array('valido'=>'true','Equipo'=>$MarcaSerial));
    }

    public function ingresarAsignacion(){
        $data           = Request::all();
        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $reglas = array(
            'tipo_equipo'       =>  'required',
            'marca_serial'      =>  'required',
            'nombre_asignado'   =>  'required',
            'cargo'             =>  'required',
            'cedula'            =>  'required',
            'telefono'          =>  'required'
        );
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $TipoEquipo         = (int)Request::get('tipo_equipo');
            $IdEquipo           = (int)Request::get('marca_serial');
            if(Request::get('mouse') != ''){
                $Mouse          = (int)Request::get('mouse');
            }else{
                $Mouse          = null;
            }
            if(Request::get('pantalla') != ''){
                $Pantalla       = (int)Request::get('pantalla');
            }else{
                $Pantalla       = null;
            }
            if(Request::get('teclado') != ''){
                $Teclado        = (int)Request::get('teclado');
            }else{
                $Teclado        = null;
            }
            if(Request::get('cargador') != ''){
                $Cargador       = (int)Request::get('cargador');
            }else{
                $Cargador       = null;
            }
            $Opcion             = (int)Request::get('opcion');
            if($Opcion === 1){
                $TipoGuaya      = (int)Request::get('tipo_guaya');
                $IdGuaya        = (int)Request::get('guaya');
                switch($TipoGuaya){
                    Case 1: $CodeGuaya = Request::get('code_guaya');
                            break;
                    Case 2: $CodeGuaya = null;
                            break;
                }
            }else{
                $TipoGuaya      = null;
                $IdGuaya        = null;
                $CodeGuaya      = null;
            }
            $Sede               = (int)Request::get('sede');
            $IdArea             = (int)Request::get('area');
            $BuscarArea         = Sedes::BuscarAreaId($IdArea);
            foreach($BuscarArea as $row){
                $Area           = $row->name;
            }
            // if(Request::get('area')){
            //     $Area           = Request::get('area');
            // }else{
            //     $Area           = 'SIN AREA';
            // }
            $NombreAsignado     = Request::get('nombre_asignado');
            $Cargo              = Request::get('cargo');
            $Cedula             = Request::get('cedula');
            $Telefono           = Request::get('telefono');
            $Correo             = Request::get('correo');
            if(Request::get('ticket')){
                $Ticket         = (int)Request::get('ticket');
            }else{
                $Ticket         = 0;
            }
            $FechaAsignacion    = date('Y-m-d H:i:s', strtotime(Request::get('fecha_asignacion')));
            $EstadoAsignado     = (int)Request::get('estado');

            $CrearAsignado = Inventario::IngresarAsignado($TipoEquipo,$IdEquipo,$Mouse,$Pantalla,$Teclado,$Cargador,$TipoGuaya,$IdGuaya,$CodeGuaya,
                                        $Sede,$Area,$NombreAsignado,$Cargo,$Cedula,$Telefono,$Correo,$Ticket,$FechaAsignacion,$EstadoAsignado,$creadoPor);
            if($CrearAsignado){

                $BuscarUltimo = Inventario::BuscarLastAsignado($creadoPor);
                foreach($BuscarUltimo as $row){
                    $IdAsignado = $row->id;
                }
                $destinationPath = null;
                $filename        = null;
                if (Request::hasFile('evidencia')) {
                    $files = Request::file('evidencia');
                    foreach($files as $file){
                        $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/actas_entrega/';
                        $extension          = $file->getClientOriginalExtension();
                        $name               = $file->getClientOriginalName();
                        $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                        $nombrearchivo      = TicketsController::eliminar_tildes($nombrearchivo);
                        $filename           = 'Evidencia Asignacion No. '.$IdAsignado.'.'.$extension;
                        $uploadSuccess      = $file->move($destinationPath, $filename);
                        $archivofoto        = file_get_contents($uploadSuccess);
                        $NombreFoto         = $filename;
                        $actualizarEvidencia = Inventario::EvidenciaI($IdAsignado,$NombreFoto);
                    }
                }
                $Comentario = 'Creación de asignación Nro. '.$IdAsignado.' en el sistema';
                Inventario::HistorialA($IdAsignado,$Comentario,$EstadoAsignado,$creadoPor);
                $verrors = 'Se creo el registro de asignación Nro. '.$IdAsignado;
                return redirect($url.'/asigneds')->with('mensaje', $verrors);
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al crear el asignado');
                return Redirect::to($url.'/asigneds')->withErrors(['errors' => $verrors])->withRequest();
            }
        }else{
            return Redirect::to($url.'/asigneds')->withErrors(['errors' => $verrors])->withRequest();
        }
    }

    public function actualizarAsignacion(){
        $data           = Request::all();
        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $reglas = array(
            'tipo_equipo_upd'       =>  'required',
            'marca_serial_upd'      =>  'required',
            'nombre_asignado_upd'   =>  'required',
            'cargo_upd'             =>  'required',
            'cedula_upd'            =>  'required',
            'telefono_upd'          =>  'required'
        );
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $TipoEquipo         = (int)Request::get('tipo_equipo_upd');
            $IdEquipo           = (int)Request::get('marca_serial_upd');
            if(Request::get('mouse_upd') != ''){
                $Mouse          = (int)Request::get('mouse_upd');
            }else{
                $Mouse          = 'null';
            }
            if(Request::get('pantalla_upd') != ''){
                $Pantalla       = (int)Request::get('pantalla_upd');
            }else{
                $Pantalla       = 'null';
            }
            if(Request::get('teclado_upd') != ''){
                $Teclado        = (int)Request::get('teclado_upd');
            }else{
                $Teclado        = 'null';
            }
            if(Request::get('cargador_upd') != ''){
                $Cargador       = (int)Request::get('cargador_upd');
            }else{
                $Cargador       = 'null';
            }
            $Opcion             = (int)Request::get('opcion_upd');
            if($Opcion === 1){
                $TipoGuaya      = (int)Request::get('tipo_guaya_upd');
                $IdGuaya        = (int)Request::get('guaya_upd');
                switch($TipoGuaya){
                    Case 1: $CodeGuaya = Request::get('code_guaya_upd');
                            break;
                    Case 2: $CodeGuaya = null;
                            break;
                }
            }else{
                $TipoGuaya      = 'null';
                $IdGuaya        = 'null';
                $CodeGuaya      = null;
            }
            $Sede               = (int)Request::get('sede_upd');
            if(Request::get('area_upd')){
                $Area           = Request::get('area_upd');
            }else{
                $Area           = 'SIN AREA';
            }
            $NombreAsignado     = Request::get('nombre_asignado_upd');
            $Cargo              = Request::get('cargo_upd');
            $Cedula             = Request::get('cedula_upd');
            $Telefono           = Request::get('telefono_upd');
            $Correo             = Request::get('correo_upd');
            if(Request::get('ticket_upd')){
                $Ticket         = (int)Request::get('ticket_upd');
            }else{
                $Ticket         = 0;
            }
            $FechaAsignacion    = date('Y-m-d H:i:s', strtotime(Request::get('fecha_asignacion_upd')));
            $EstadoAsignado     = (int)Request::get('estado_upd');
            $Comentario         = Request::get('comentario');
            $IdAsignado         = (int)Request::get('idA');

            $ActualizarAsignado = Inventario::ActualizarAsignado($TipoEquipo,$IdEquipo,$Mouse,$Pantalla,$Teclado,$Cargador,$TipoGuaya,$IdGuaya,$CodeGuaya,
                                    $Sede,$Area,$NombreAsignado,$Cargo,$Cedula,$Telefono,$Correo,$Ticket,$FechaAsignacion,$EstadoAsignado,$creadoPor,$IdAsignado);
            if($ActualizarAsignado){
                $destinationPath = null;
                $filename        = null;
                if (Request::hasFile('evidencia_upd')) {
                    $files = Request::file('evidencia_upd');
                    foreach($files as $file){
                        $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/actas_entrega/';
                        $extension          = $file->getClientOriginalExtension();
                        $name               = $file->getClientOriginalName();
                        $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                        $nombrearchivo      = TicketsController::eliminar_tildes($nombrearchivo);
                        $filename           = 'Evidencia Asignacion No. '.$IdAsignado.'.'.$extension;
                        $uploadSuccess      = $file->move($destinationPath, $filename);
                        $archivofoto        = file_get_contents($uploadSuccess);
                        $NombreFoto         = $filename;
                        $actualizarEvidencia = Inventario::EvidenciaI($IdAsignado,$NombreFoto);
                    }
                }

                Inventario::HistorialA($IdAsignado,$Comentario,$EstadoAsignado,$creadoPor);
                $verrors = 'Se actualizó el registro de asignación Nro. '.$IdAsignado;
                return redirect($url.'/asigneds')->with('mensaje', $verrors);
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al actualizar el asignado');
                return Redirect::to($url.'/asigneds')->withErrors(['errors' => $verrors])->withRequest();
            }
        }else{
            return Redirect::to($url.'/asigneds')->withErrors(['errors' => $verrors])->withRequest();
        }
    }
}
