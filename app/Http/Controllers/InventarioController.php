<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\HelpDesk\Inventario;
use App\Models\Admin\Sedes;
use App\Models\Admin\Usuarios;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class InventarioController extends Controller
{

    public function BuscarURL($Administrador){
        if($Administrador === 1){
            return 'admin';
        }else{
            return 'user';
        }
    }

    public function asignacionEquipoMovil(Request $request){
        $creadoPor          = (int)Session::get('IdUsuario');
        $buscarUsuario      = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador  = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $EstadoEquipo       = (int)$request->estado;
        if($EstadoEquipo === 2){
            $validator = Validator::make($request->all(), [
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
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'tipo_equipo'       =>  'required',
                'fecha_adquision'   =>  'required',
                'serial'            =>  'required',
                'marca'             =>  'required',
                'modelo'            =>  'required',
                'imei'              =>  'required',
                'capacidad'         =>  'required',
                'estado'            =>  'required'
            ]);
        }

        if ($validator->fails()) {
            return redirect($url.'/mobile')->withErrors($validator)->withInput();
        }else{
            $TipoEquipo         = $request->tipo_equipo;
            $FechaAdquisicion   = date('Y-m-d H:i:s', strtotime($request->fecha_adquision));
            $Serial             = $request->serial;
            $Marca              = $request->marca;
            $Modelo             = $request->modelo;
            $IMEI               = $request->imei;
            $Capacidad          = $request->capacidad;
            $LineaMovil         = $request->linea_movil;
            $Area               = $request->area;
            $NombreAsignado     = $request->nombre_asignado;
            $EstadoEquipo       = $request->estado;
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
                    if ($request->hasFile('evidencia')) {
                        $files = $request->file('evidencia');
                        foreach($files as $file){
                            $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/equipo_movil';
                            $extension          = $file->getClientOriginalExtension();
                            $name               = $file->getClientOriginalName();
                            $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                            $nombrearchivo      = Funciones::eliminar_tildes($nombrearchivo);
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
        }
    }

    public function actualizacionEquipoMovil(Request $request){

        $creadoPor          = (int)Session::get('IdUsuario');
        $buscarUsuario = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $EstadoEquipo       = (int)$request->estado_upd;
        if($EstadoEquipo === 2){
            $validator = Validator::make($request->all(), [
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
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'tipo_equipo_upd'       =>  'required',
                'fecha_adquision_upd'   =>  'required',
                'serial_upd'            =>  'required',
                'marca_upd'             =>  'required',
                'modelo_upd'            =>  'required',
                'imei_upd'              =>  'required',
                'capacidad_upd'         =>  'required',
                'estado_upd'            =>  'required',
                'comentario'            =>  'required'
            ]);
        }

        if ($validator->fails()) {
            return redirect($url.'/mobile')->withErrors($validator)->withInput();
        }else{
            $TipoEquipo         = $request->tipo_equipo_upd;
            $FechaAdquisicion   = date('Y-m-d H:i:s', strtotime($request->fecha_adquision_upd));
            $Serial             = $request->serial_upd;
            $Marca              = $request->marca_upd;
            $Modelo             = $request->modelo_upd;
            $IMEI               = $request->imei_upd;
            $Capacidad          = $request->capacidad_upd;
            $LineaMovil         = $request->linea_movil_upd;
            $Area               = $request->area_upd;
            $NombreAsignado     = $request->nombre_asignado_upd;
            $EstadoEquipo       = $request->estado_upd;
            $idEquipoMovil      = $request->idEM;
            $Comentario         = Funciones::eliminar_tildes_texto($request->comentario);
            if($request->desvincular){
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
                if ($request->hasFile('evidencia_upd')) {
                    $files = $request->file('evidencia_upd');
                    foreach($files as $file){
                        $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/equipo_movil';
                        $extension          = $file->getClientOriginalExtension();
                        $name               = $file->getClientOriginalName();
                        $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                        $nombrearchivo      = Funciones::eliminar_tildes($nombrearchivo);
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
        }
    }

    public function asignacionLineaMovil(Request $request){

        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $EstadoEquipo             = (int)$request->estado;
        if($EstadoEquipo === 2){
            $validator = Validator::make($request->all(), [
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
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'nro_linea'         =>  'required',
                'fecha_adquision'   =>  'required',
                'serial'            =>  'required',
                'activo'            =>  'required',
                'proveedores'       =>  'required',
                'plan'              =>  'required',
                'pto_cargo'         =>  'required',
                'estado'            =>  'required'
            ]);
        }

        if ($validator->fails()) {
            return redirect($url.'/lineMobile')->withErrors($validator)->withInput();
        }else{

            $NroLinea           = $request->nro_linea;
            $FechaAdquisicion   = date('Y-m-d H:i:s', strtotime($request->fecha_adquision));
            $Serial             = $request->serial;
            $Activo             = $request->activo;
            $Proveedor          = $request->proveedores;
            $Plan               = $request->plan;
            $PtoCargo           = $request->pto_cargo;
            $Cc                 = $request->cc;
            $Area               = $request->area;
            $Personal           = $request->personal;
            $Estado             = $request->estado;
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
                    if ($request->hasFile('evidencia')) {
                        $files = $request->file('evidencia');
                        foreach($files as $file){
                            $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/lineas/';
                            $extension          = $file->getClientOriginalExtension();
                            $name               = $file->getClientOriginalName();
                            $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                            $nombrearchivo      = Funciones::eliminar_tildes($nombrearchivo);
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
        }
    }

    public function actualizacionLineaMovil(Request $request){
        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $EstadoEquipo             = (int)$request->estado_upd;
        if($EstadoEquipo === 2){
            $validator = Validator::make($request->all(), [
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
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'nro_linea_upd'         =>  'required',
                'fecha_adquision_upd'   =>  'required',
                'serial_upd'            =>  'required',
                'activo_upd'            =>  'required',
                'proveedores_upd'       =>  'required',
                'plan_upd'              =>  'required',
                'pto_cargo_upd'         =>  'required',
                'estado_upd'            =>  'required',
                'comentario'            =>  'required'
            ]);
        }

        if ($validator->fails()) {
            return redirect($url.'/lineMobile')->withErrors($validator)->withInput();
        }else{

            $NroLinea           = $request->nro_linea_upd;
            $FechaAdquisicion   = date('Y-m-d H:i:s', strtotime($request->fecha_adquision_upd));
            $Serial             = $request->serial_upd;
            $Activo             = $request->activo_upd;
            $Proveedor          = $request->proveedores_upd;
            $Plan               = $request->plan_upd;
            $PtoCargo           = $request->pto_cargo_upd;
            $Cc                 = $request->cc_upd;
            $Area               = $request->area_upd;
            $Personal           = $request->personal_upd;
            $Estado             = $request->estado_upd;
            $IdLineaMovil       = $request->idLM;
            $Comentario         = Funciones::eliminar_tildes_texto($request->comentario);

            $ActualizacionLineaMovil = Inventario::ActualizarLineaMovil($NroLinea,$FechaAdquisicion,$Serial,$Activo,$Proveedor,$Plan,$PtoCargo,$Cc,$Area,$Personal,$Estado,$creadoPor,$IdLineaMovil);

            if($ActualizacionLineaMovil){

                Inventario::RegistrarAsignadoLM($IdLineaMovil,$Area,$Personal,$Estado,$creadoPor);

                $destinationPath = null;
                $filename        = null;
                if ($request->hasFile('evidencia_upd')) {
                    $files = $request->file('evidencia_upd');
                    foreach($files as $file){
                        $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/lineas/';
                        $extension          = $file->getClientOriginalExtension();
                        $name               = $file->getClientOriginalName();
                        $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                        $nombrearchivo      = Funciones::eliminar_tildes($nombrearchivo);
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
        }
    }

    public function ingresoEquipo(Request $request){

        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $validator = Validator::make($request->all(), [
            'tipo_equipo'       =>  'required',
            'tipo_ingreso'      =>  'required',
            'fecha_adquision'   =>  'required',
            'serial'            =>  'required',
            'marca'             =>  'required',
            'estado'            =>  'required'
        ]);
        if ($validator->fails()) {
            return redirect($url.'/desktops')->withErrors($validator)->withInput();
        }else{
            $TipoEquipo         = $request->tipo_equipo;
            $TipoIngreso        = $request->tipo_ingreso;
            if($request->emp_renting){
                $EmpresaRenting = $request->emp_renting;
            }else{
                $EmpresaRenting = 'SIN EMPRESA';
            }
            $FechaAdquisicion   = date('Y-m-d H:i:s', strtotime($request->fecha_adquision));
            $Serial             = $request->serial;
            $Marca              = $request->marca;
            $Procesador         = $request->procesador;
            $VelProcesador      = $request->vel_procesador;
            $DiscoDuro          = $request->disco_duro;
            $MemoriaRam         = $request->memoria_ram;
            $EstadoEquipo       = $request->estado;
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
                    if ($request->hasFile('evidencia')) {
                        $files = $request->file('evidencia');
                        foreach($files as $file){
                            $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/equipos/';
                            $extension          = $file->getClientOriginalExtension();
                            $name               = $file->getClientOriginalName();
                            $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                            $nombrearchivo      = Funciones::eliminar_tildes($nombrearchivo);
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
        }
    }

    public function actualizacionEquipo(Request $request){

        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $validator = Validator::make($request->all(), [
            'tipo_equipo_upd'       =>  'required',
            'tipo_ingreso_upd'      =>  'required',
            'fecha_adquision_upd'   =>  'required',
            'serial_upd'            =>  'required',
            'marca_upd'             =>  'required',
            'comentario'            =>  'required',
            'estado_upd'            =>  'required'
        ]);
        if ($validator->fails()) {
            return redirect($url.'/desktops')->withErrors($validator)->withInput();
        }else{

            $TipoEquipo         = $request->tipo_equipo_upd;
            $TipoIngreso        = $request->tipo_ingreso_upd;
            if($request->emp_renting_upd){
                $EmpresaRenting = $request->emp_renting_upd;
            }else{
                $EmpresaRenting = 'SIN EMPRESA';
            }
            $FechaAdquisicion   = date('Y-m-d H:i:s', strtotime($request->fecha_adquision_upd));
            $Serial             = $request->serial_upd;
            $Marca              = $request->marca_upd;
            $Procesador         = $request->procesador_upd;
            $VelProcesador      = $request->vel_procesador_upd;
            $DiscoDuro          = $request->disco_duro_upd;
            $MemoriaRam         = $request->memoria_ram_upd;
            $EstadoEquipo       = $request->estado_upd;
            $Comentario         = Funciones::eliminar_tildes_texto($request->comentario);
            $IdEquipo           = $request->idE;

            $ActualizarEquipo   = Inventario::ActualizarEquipo($TipoEquipo,$TipoIngreso,$EmpresaRenting,$FechaAdquisicion,$Serial,$Marca,$Procesador,$VelProcesador,$DiscoDuro,$MemoriaRam,$EstadoEquipo,$creadoPor,$IdEquipo);

            if($ActualizarEquipo){
                $destinationPath = null;
                $filename        = null;
                if ($request->hasFile('evidencia_upd')) {
                    $files = $request->file('evidencia_upd');
                    foreach($files as $file){
                        $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/equipos/';
                        $extension          = $file->getClientOriginalExtension();
                        $name               = $file->getClientOriginalName();
                        $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                        $nombrearchivo      = Funciones::eliminar_tildes($nombrearchivo);
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
        }
    }

    public function ingresoPeriferico(Request $request){

        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $validator = Validator::make($request->all(), [
            'tipo_periferico'   =>  'required',
            'tipo_ingreso'      =>  'required',
            'fecha_adquision'   =>  'required',
            'serial'            =>  'required',
            'marca'             =>  'required',
            'estado'            =>  'required'
        ]);
        if ($validator->fails()) {
            return redirect($url.'/periferic')->withErrors($validator)->withInput();
        }else{

            $TipoPeriferico     = (int)$request->tipo_periferico;
            $TipoIngreso        = (int)$request->tipo_ingreso;
            if($request->emp_renting){
                $EmpresaRent    = $request->emp_renting;
            }else{
                $EmpresaRent    = 'SIN EMPRESA';
            }
            $FechaAdquisicion   = date('Y-m-d H:i:s', strtotime($request->fecha_adquision));
            $Serial             = $request->serial;
            $Marca              = $request->marca;
            $Tamano             = $request->tamano;
            $Estado             = (int)$request->estado;
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
                        if ($request->hasFile('evidencia')) {
                            $files = $request->file('evidencia');
                            foreach($files as $file){
                                $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/'.$Carpeta;
                                $extension          = $file->getClientOriginalExtension();
                                $name               = $file->getClientOriginalName();
                                $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                                $nombrearchivo      = Funciones::eliminar_tildes($nombrearchivo);
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
        }
    }

    public function actualizacionPeriferico(Request $request){

        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $validator = Validator::make($request->all(), [
            'tipo_periferico_upd'   =>  'required',
            'tipo_ingreso_upd'      =>  'required',
            'fecha_adquision_upd'   =>  'required',
            'serial_upd'            =>  'required',
            'marca_upd'             =>  'required',
            'estado_upd'            =>  'reduired',
            'comentario'            =>  'required'
        ]);
        if ($validator->fails()) {
            return redirect($url.'/periferic')->withErrors($validator)->withInput();
        }else{
            $TipoPeriferico     = (int)$request->tipo_periferico_upd;
            $TipoIngreso        = (int)$request->tipo_ingreso_upd;
            if($request->emp_renting_upd){
                $EmpresaRent    = $request->emp_renting_upd;
            }else{
                $EmpresaRent    = 'SIN EMPRESA';
            }
            $FechaAdquisicion   = date('Y-m-d H:i:s', strtotime($request->fecha_adquision_upd));
            $Serial             = $request->serial_upd;
            $Marca              = $request->marca_upd;
            $Tamano             = $request->tamano_upd;
            $Estado             = (int)$request->estado_upd;
            $Comentario         = Funciones::eliminar_tildes_texto($request->comentario);
            $IdPeriferico       = (int)$request->idP;

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
                if ($request->hasFile('evidencia_upd')) {
                    $files = $request->file('evidencia_upd');
                    foreach($files as $file){
                        $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/'.$Carpeta;
                        $extension          = $file->getClientOriginalExtension();
                        $name               = $file->getClientOriginalName();
                        $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                        $nombrearchivo      = Funciones::eliminar_tildes($nombrearchivo);
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

        }
    }

    public function ingresarConsumible(Request $request){

        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $validator = Validator::make($request->all(), [
            'tipo_consumible'   =>  'required',
            'tipo_ingreso'      =>  'required',
            'fecha_adquision'   =>  'required',
            'serial'            =>  'required',
            'marca'             =>  'required',
            'estado'            =>  'required'
        ]);
        if ($validator->fails()) {
            return redirect($url.'/consumible')->withErrors($validator)->withInput();
        }else{

            $TipoConsumible     = (int)$request->tipo_consumible;
            $TipoIngreso        = (int)$request->tipo_ingreso;
            if($request->emp_renting){
                $EmpresaRent    = $request->emp_renting;
            }else{
                $EmpresaRent    = 'SIN EMPRESA';
            }
            $FechaAdquisicion   = date('Y-m-d H:i:s', strtotime($request->fecha_adquision));
            $Serial             = $request->serial;
            $Marca              = $request->marca;
            $Modelo             = $request->modelo;
            $CompaRef           = $request->compa_ref;
            $CompaMod           = $request->compa_ref;
            $Estado             = (int)$request->estado;
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
                    if ($request->hasFile('evidencia')) {
                        $files = $request->file('evidencia');
                        foreach($files as $file){
                            $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/consumibles/';
                            $extension          = $file->getClientOriginalExtension();
                            $name               = $file->getClientOriginalName();
                            $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                            $nombrearchivo      = Funciones::eliminar_tildes($nombrearchivo);
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
        }
    }

    public function actualizarConsumible(Request $request){

        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $validator = Validator::make($request->all(), [
            'tipo_consumible_upd'   =>  'required',
            'tipo_ingreso_upd'      =>  'required',
            'fecha_adquision_upd'   =>  'required',
            'serial_upd'            =>  'required',
            'marca_upd'             =>  'required',
            'estado_upd'            =>  'required',
            'comentario'            =>  'required'
        ]);
        if ($validator->fails()) {
            return redirect($url.'/consumible')->withErrors($validator)->withInput();
        }else{

            $TipoConsumible         = (int)$request->tipo_consumible_upd;
            $TipoIngreso            = (int)$request->tipo_ingreso_upd;
            if($request->emp_renting_upd){
                $EmpresaRent        = $request->emp_renting_upd;
            }else{
                $EmpresaRent        = 'SIN EMPRESA';
            }
            $FechaAdquisicion       = date('Y-m-d H:i:s', strtotime($request->fecha_adquision_upd));
            $Serial                 = $request->serial_upd;
            $Marca                  = $request->marca_upd;
            $Modelo                 = $request->modelo_upd;
            $CompaRef               = $request->compa_ref_upd;
            $CompaMod               = $request->compa_ref_upd;
            $Estado                 = (int)$request->estado_upd;
            $Comentario             = Funciones::eliminar_tildes_texto($request->comentario);
            $IdConsumible           = (int)$request->idC;
            $ActualizarConsumible   = Inventario::ActualizarConsumible($TipoConsumible,$TipoIngreso,$EmpresaRent,$FechaAdquisicion,$Serial,$Marca,$Modelo,$CompaRef,$CompaMod,$Estado,$creadoPor,$IdConsumible);
            if($ActualizarConsumible){
                $destinationPath = null;
                $filename        = null;
                if ($request->hasFile('evidencia')) {
                    $files = $request->file('evidencia');
                    foreach($files as $file){
                        $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/consumibles/';
                        $extension          = $file->getClientOriginalExtension();
                        $name               = $file->getClientOriginalName();
                        $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                        $nombrearchivo      = Funciones::eliminar_tildes($nombrearchivo);
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
        }
    }

    public function ingresarImpresora(Request $request){

        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $validator = Validator::make($request->all(), [
            'tipo_impresora'    =>  'required',
            'tipo_ingreso'      =>  'required',
            'fecha_adquision'   =>  'required',
            'serial'            =>  'required',
            'marca'             =>  'required',
            'estado'            =>  'required'
        ]);
        if ($validator->fails()) {
            return redirect($url.'/printers')->withErrors($validator)->withInput();
        }else{

            $TipoImpresora      = $request->tipo_impresora;
            $TipoIngreso        = (int)$request->tipo_ingreso;
            if($request->emp_renting){
                $EmpresaRent    = $request->emp_renting;
            }else{
                $EmpresaRent    = 'SIN EMPRESA';
            }
            $FechaAdquisicion   = date('Y-m-d H:i:s', strtotime($request->fecha_adquision));
            $Serial             = $request->serial;
            $Marca              = $request->marca;
            $Ip                 = $request->ip;
            $IdConsumible       = (int)$request->id_consumible;
            $Estado             = (int)$request->estado;
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
                    if ($request->hasFile('evidencia')) {
                        $files = $request->file('evidencia');
                        foreach($files as $file){
                            $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/impresoras/';
                            $extension          = $file->getClientOriginalExtension();
                            $name               = $file->getClientOriginalName();
                            $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                            $nombrearchivo      = Funciones::eliminar_tildes($nombrearchivo);
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
        }
    }

    public function actualizarImpresora(Request $request){

        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $validator = Validator::make($request->all(), [
            'tipo_impresora_upd'    =>  'required',
            'tipo_ingreso_upd'      =>  'required',
            'fecha_adquision_upd'   =>  'required',
            'serial_upd'            =>  'required',
            'marca_upd'             =>  'required',
            'estado_upd'            =>  'required',
            'comentario'            =>  'required'
        ]);
        if ($validator->fails()) {
            return redirect($url.'/printers')->withErrors($validator)->withInput();
        }else{

            $TipoImpresora      = $request->tipo_impresora_upd;
            $TipoIngreso        = (int)$request->tipo_ingreso_upd;
            if($request->emp_renting_upd){
                $EmpresaRent    = $request->emp_renting_upd;
            }else{
                $EmpresaRent    = 'SIN EMPRESA';
            }
            $FechaAdquisicion   = date('Y-m-d H:i:s', strtotime($request->fecha_adquision_upd));
            $Serial             = $request->serial_upd;
            $Marca              = $request->marca_upd;
            $Ip                 = $request->ip_upd;
            $IdConsumible       = (int)$request->id_consumible_upd;
            $Estado             = (int)$request->estado_upd;
            $Comentario         = Funciones::eliminar_tildes_texto($request->comentario);
            $IdImpresora        = (int)$request->idI;
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
                if ($request->hasFile('evidencia')) {
                    $files = $request->file('evidencia');
                    foreach($files as $file){
                        $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/impresoras/';
                        $extension          = $file->getClientOriginalExtension();
                        $name               = $file->getClientOriginalName();
                        $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                        $nombrearchivo      = Funciones::eliminar_tildes($nombrearchivo);
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

        }
    }

    public function buscarEquipo(Request $request){
        $id                 = (int)$request->tipo_equipo;
        $MarcaSerial        = array();
        $buscarUsuario      = Inventario::BuscarXTipoEquipo($id);
        // $MarcaSerial[0]     = 'Seleccione: ';
        foreach ($buscarUsuario as $row){
            $MarcaSerial[$row->id] = $row->marca.' - '.$row->serial;
        }
        return Response::json(array('valido'=>'true','Equipo'=>$MarcaSerial));
    }

    public function ingresarAsignacion(Request $request){

        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $validator = Validator::make($request->all(), [
            'tipo_equipo'       =>  'required',
            'marca_serial'      =>  'required',
            'nombre_asignado'   =>  'required',
            'cargo'             =>  'required',
            'cedula'            =>  'required',
            'telefono'          =>  'required'
        ]);
        if ($validator->fails()) {
            return redirect($url.'/asigneds')->withErrors($validator)->withInput();
        }else{

            $TipoEquipo         = (int)$request->tipo_equipo;
            $IdEquipo           = (int)$request->marca_serial;
            if($request->mouse != ''){
                $Mouse          = (int)$request->mouse;
            }else{
                $Mouse          = null;
            }
            if($request->pantalla != ''){
                $Pantalla       = (int)$request->pantalla;
            }else{
                $Pantalla       = null;
            }
            if($request->teclado != ''){
                $Teclado        = (int)$request->teclado;
            }else{
                $Teclado        = null;
            }
            if($request->cargador != ''){
                $Cargador       = (int)$request->cargador;
            }else{
                $Cargador       = null;
            }
            $Opcion             = (int)$request->opcion;
            if($Opcion === 1){
                $TipoGuaya      = (int)$request->tipo_guaya;
                $IdGuaya        = (int)$request->guaya;
                switch($TipoGuaya){
                    Case 1: $CodeGuaya = $request->code_guaya;
                            break;
                    Case 2: $CodeGuaya = null;
                            break;
                }
            }else{
                $TipoGuaya      = null;
                $IdGuaya        = null;
                $CodeGuaya      = null;
            }
            $Sede               = (int)$request->sede;
            $IdArea             = (int)$request->area;
            $BuscarArea         = Sedes::BuscarAreaId($IdArea);
            foreach($BuscarArea as $row){
                $Area           = $row->name;
            }
            // if($request->area')){
            //     $Area           = $request->area');
            // }else{
            //     $Area           = 'SIN AREA';
            // }
            $NombreAsignado     = Funciones::eliminar_tildes_texto($request->nombre_asignado);
            $Cargo              = Funciones::eliminar_tildes_texto($request->cargo);
            $Cedula             = $request->cedula;
            $Telefono           = $request->telefono;
            $Correo             = Funciones::editar_correo($request->correo);
            if($request->ticket){
                $Ticket         = (int)$request->ticket;
            }else{
                $Ticket         = 0;
            }
            $FechaAsignacion    = date('Y-m-d H:i:s', strtotime($request->fecha_asignacion));
            $EstadoAsignado     = (int)$request->estado;

            $CrearAsignado = Inventario::IngresarAsignado($TipoEquipo,$IdEquipo,$Mouse,$Pantalla,$Teclado,$Cargador,$TipoGuaya,$IdGuaya,$CodeGuaya,
                                        $Sede,$Area,$NombreAsignado,$Cargo,$Cedula,$Telefono,$Correo,$Ticket,$FechaAsignacion,$EstadoAsignado,$creadoPor);
            if($CrearAsignado){

                $BuscarUltimo = Inventario::BuscarLastAsignado($creadoPor);
                foreach($BuscarUltimo as $row){
                    $IdAsignado = $row->id;
                }
                $destinationPath = null;
                $filename        = null;
                if ($request->hasFile('evidencia')) {
                    $files = $request->file('evidencia');
                    foreach($files as $file){
                        $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/actas_entrega/';
                        $extension          = $file->getClientOriginalExtension();
                        $name               = $file->getClientOriginalName();
                        $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                        $nombrearchivo      = Funciones::eliminar_tildes($nombrearchivo);
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
        }
    }

    public function actualizarAsignacion(Request $request){

        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = InventarioController::BuscarURL($Administrador);
        $validator = Validator::make($request->all(), [
            'tipo_equipo_upd'       =>  'required',
            'marca_serial_upd'      =>  'required',
            'nombre_asignado_upd'   =>  'required',
            'cargo_upd'             =>  'required',
            'cedula_upd'            =>  'required',
            'telefono_upd'          =>  'required'
        ]);
        if ($validator->fails()) {
            return redirect($url.'/asigneds')->withErrors($validator)->withInput();
        }else{

            $TipoEquipo         = (int)$request->tipo_equipo_upd;
            $IdEquipo           = (int)$request->marca_serial_upd;
            if($request->mouse_upd != ''){
                $Mouse          = (int)$request->mouse_upd;
            }else{
                $Mouse          = 'null';
            }
            if($request->pantalla_upd != ''){
                $Pantalla       = (int)$request->pantalla_upd;
            }else{
                $Pantalla       = 'null';
            }
            if($request->teclado_upd != ''){
                $Teclado        = (int)$request->teclado_upd;
            }else{
                $Teclado        = 'null';
            }
            if($request->cargador_upd != ''){
                $Cargador       = (int)$request->cargador_upd;
            }else{
                $Cargador       = 'null';
            }
            $Opcion             = (int)$request->opcion_upd;
            if($Opcion === 1){
                $TipoGuaya      = (int)$request->tipo_guaya_upd;
                $IdGuaya        = (int)$request->guaya_upd;
                switch($TipoGuaya){
                    Case 1: $CodeGuaya = $request->code_guaya_upd;
                            break;
                    Case 2: $CodeGuaya = null;
                            break;
                }
            }else{
                $TipoGuaya      = 'null';
                $IdGuaya        = 'null';
                $CodeGuaya      = null;
            }
            $Sede               = (int)$request->sede_upd;
            if($request->area_upd){
                $Area           = $request->area_upd;
            }else{
                $Area           = 'SIN AREA';
            }
            $NombreAsignado     = Funciones::eliminar_tildes_texto($request->nombre_asignado_upd);
            $Cargo              = Funciones::eliminar_tildes_texto($request->cargo_upd);
            $Cedula             = $request->cedula_upd;
            $Telefono           = $request->telefono_upd;
            $Correo             = Funciones::editar_correo($request->correo_upd);
            if($request->ticket_upd){
                $Ticket         = (int)$request->ticket_upd;
            }else{
                $Ticket         = 0;
            }
            $FechaAsignacion    = date('Y-m-d H:i:s', strtotime($request->fecha_asignacion_upd));
            $EstadoAsignado     = (int)$request->estado_upd;
            $Comentario         = Funciones::eliminar_tildes_texto($request->comentario);
            $IdAsignado         = (int)$request->idA;

            $ActualizarAsignado = Inventario::ActualizarAsignado($TipoEquipo,$IdEquipo,$Mouse,$Pantalla,$Teclado,$Cargador,$TipoGuaya,$IdGuaya,$CodeGuaya,
                                    $Sede,$Area,$NombreAsignado,$Cargo,$Cedula,$Telefono,$Correo,$Ticket,$FechaAsignacion,$EstadoAsignado,$creadoPor,$IdAsignado);
            if($ActualizarAsignado){
                $destinationPath = null;
                $filename        = null;
                if ($request->hasFile('evidencia_upd')) {
                    $files = $request->file('evidencia_upd');
                    foreach($files as $file){
                        $destinationPath    = public_path().'/assets/dist/img/evidencias_inventario/actas_entrega/';
                        $extension          = $file->getClientOriginalExtension();
                        $name               = $file->getClientOriginalName();
                        $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                        $nombrearchivo      = Funciones::eliminar_tildes($nombrearchivo);
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
        }
    }


}
