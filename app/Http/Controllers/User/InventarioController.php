<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Funciones;
use App\Models\HelpDesk\Inventario;
Use App\Models\Admin\Sedes;
Use App\Models\Admin\Usuarios;
use Illuminate\Support\Facades\Request;

class InventarioController extends Controller
{
    public function TipoIngreso(){
        $ListaTipoIngreso = Inventario::ListarTipoIngreso();
        $TipoIngreso  = array();
        // $TipoIngreso[''] = 'Seleccione: ';
        foreach ($ListaTipoIngreso as $row){
            $TipoIngreso[$row->id] = $row->name;
        }
        return $TipoIngreso;
    }

    public function TipoEstado(){
        $ListarEstado = Inventario::ListarEstadoEquipos();
        $EstadoEquipo  = array();
        // $EstadoEquipo[''] = 'Seleccione: ';
        foreach ($ListarEstado as $row){
            $EstadoEquipo[$row->id] = $row->name;
        }
        return $EstadoEquipo;
    }

    public function mobile(){

        $EquiposStock = Inventario::MobileStock();
        foreach($EquiposStock as $row){
            $TotalStock = (int)$row->total;
        }
        $EquiposAsignados = Inventario::MobileAsigned();
        foreach($EquiposAsignados as $row){
            $TotalAsignados = (int)$row->total;
        }
        $EquiposMantenimiento = Inventario::MobileMaintenance();
        foreach($EquiposMantenimiento as $row){
            $TotalMantenimiento = (int)$row->total;
        }
        $EquiposObsoletos = Inventario::MobileObsolete();
        foreach($EquiposObsoletos as $row){
            $TotalObsoletos = (int)$row->total;
        }
        $ListarEquiposMoviles = Inventario::ListarEquiposMoviles();
        $EquiposMoviles = array();
        $contEM = 0;
        foreach($ListarEquiposMoviles as $value){
            $IdEquipoMovil                              = (int)$value->id;
            $EquiposMoviles[$contEM]['id']              = (int)$value->id;
            $EquiposMoviles[$contEM]['tipo_equipo']     = $value->tipo_equipo;
            $EquiposMoviles[$contEM]['fecha_ingreso']   = date('d/m/Y', strtotime($value->fecha_ingreso));
            $EquiposMoviles[$contEM]['serial']          = $value->serial;
            $EquiposMoviles[$contEM]['marca']           = $value->marca;
            $EquiposMoviles[$contEM]['modelo']          = $value->modelo;
            $EquiposMoviles[$contEM]['IMEI']            = $value->IMEI;
            $EquiposMoviles[$contEM]['capacidad']       = $value->capacidad;
            $EquiposMoviles[$contEM]['usuario']         = Funciones::eliminar_tildes_texto($value->usuario);
            $EquiposMoviles[$contEM]['area']            = Funciones::eliminar_tildes_texto($value->area);
            $EquiposMoviles[$contEM]['linea']           = $value->linea;
            $EquiposMoviles[$contEM]['estado_equipo']   = $value->estado_equipo;
            $EquiposMoviles[$contEM]['created_at']      = date('d/m/Y h:i A', strtotime($value->created_at));
            $EquiposMoviles[$contEM]['user_id']         = $value->user_id;

            $IdTipoEquipo   = (int)$value->tipo_equipo;
            $TipoEquipo     = Inventario::BuscarEquipoId($IdTipoEquipo);
            foreach($TipoEquipo as $row){
                $EquiposMoviles[$contEM]['tipoEquipo']  = Funciones::eliminar_tildes_texto($row->name);
            }

            $IdLinea        = (int)$value->linea;
            if($IdLinea){
                $NroLinea       = Inventario::BuscarNroLinea($IdLinea);
                foreach($NroLinea as $row){
                    $EquiposMoviles[$contEM]['nro_linea']  = $row->nro_linea;
                }
            }else{
                $EquiposMoviles[$contEM]['nro_linea']  = 'SIN Nro. LINEA';
            }

            $IdEstadoEquipo = (int)$value->estado_equipo;
            $EstadoEquipo   = Inventario::EstadoEquipoId($IdEstadoEquipo);
            foreach($EstadoEquipo as $row){
                switch($IdEstadoEquipo){
                    Case 1  :   $EquiposMoviles[$contEM]['estado']  = $row->name;
                                $EquiposMoviles[$contEM]['label']   = 'label label-primary';
                                break;
                    Case 2  :   $EquiposMoviles[$contEM]['estado']  = $row->name;
                                $EquiposMoviles[$contEM]['label']   = 'label label-success';
                                break;
                    Case 3  :   $EquiposMoviles[$contEM]['estado']  = $row->name;
                                $EquiposMoviles[$contEM]['label']   = 'label label-danger';
                                break;
                    Case 4  :   $EquiposMoviles[$contEM]['estado']  = $row->name;
                                $EquiposMoviles[$contEM]['label']   = 'label label-warning';
                                break;
                }
            }

            $EquiposMoviles[$contEM]['evidencia']    = null;
            $evidenciaTicket = Inventario::EvidenciaEquipoM($IdEquipoMovil);
            $contadorEvidencia = count($evidenciaTicket);
            if($contadorEvidencia > 0){
                $contE = 1;
                foreach($evidenciaTicket as $row){
                    $EquiposMoviles[$contEM]['evidencia'] .= "<p><a href='../assets/dist/img/evidencias_inventario/equipo_movil/".$row->nombre."' target='_blank' class='btn btn-info'><i class='fa fa-mobile'></i>&nbsp; Anexo Equipo Movil $IdEquipoMovil Nro. ".$contE."</a></p>";
                    $contE++;
                }
            }else{
                $EquiposMoviles[$contEM]['evidencia'] = null;
            }

            $historialEquipoM = Inventario::BuscarHistorialEM($IdEquipoMovil);
            $contadorHistorial = count($historialEquipoM);
            $EquiposMoviles[$contEM]['historial'] = null;
            if($contadorHistorial > 0){
                foreach($historialEquipoM as $row){
                    $idUsuario  = $row->user_id;
                    $BuscarUsuario = Usuarios::BuscarNombre($idUsuario);
                    foreach($BuscarUsuario as $values){
                        $NombreUser = $values->name;
                    }
                    $EquiposMoviles[$contEM]['historial'] .= "- ".Funciones::eliminar_tildes_texto($row->comentario)." (".$NombreUser." - ".date('d/m/Y h:i a', strtotime($row->created)).")\n";
                }
            }else{
                $EquiposMoviles[$contEM]['historial'] = null;
            }

            $contEM++;
        }

        $ListaTipoEquipo = Inventario::ListadoTipoEquipoMovil();
        $TipoEquipo  = array();
        $TipoEquipo[''] = 'Seleccione: ';
        foreach ($ListaTipoEquipo as $row){
            $TipoEquipo[$row->id] = $row->name;
        }

        $ListaLineaMovil = Inventario::ListadoLineaMovil();
        $LineaMovil  = array();
        $LineaMovil[''] = 'Seleccione: ';
        foreach ($ListaLineaMovil as $row){
            $LineaMovil[$row->id] = $row->nro_linea;
        }

        $ListadoLineaMovilUpd = Inventario::ListadoLineaMovilUpd();
        $LineaMovilUpd  = array();
        $LineaMovilUpd[''] = 'Seleccione: ';
        foreach ($ListadoLineaMovilUpd as $row){
            $LineaMovilUpd[$row->id] = $row->nro_linea;
        }

        $EstadoEquipo   = InventarioController::TipoEstado();

        return view('Inventario.Mobile',['Stock' => $TotalStock,'Asignados' => $TotalAsignados,'Mantenimiento' => $TotalMantenimiento,'Obsoletos' => $TotalObsoletos,
                                        'EquiposMoviles' => $EquiposMoviles,'TipoEquipo' => $TipoEquipo,'LineaMovil' => $LineaMovil,'EstadoEquipo' => $EstadoEquipo,
                                        'FechaAdquisicion' => null,'Serial' => null,'Marca' => null,'Modelo' => null,'IMEI' => null,'Capacidad' => null,'Area' => null,
                                        'NombreAsignado' => null,'LineaMovilUpd' => $LineaMovilUpd]);
    }

    public function lineMobile(){
        $LineasStock = Inventario::LineMobileStock();
        foreach($LineasStock as $row){
            $TotalStock = (int)$row->total;
        }
        $LineasAsignados = Inventario::LineMobileAsigned();
        foreach($LineasAsignados as $row){
            $TotalAsignados = (int)$row->total;
        }
        $LineasMantenimiento = Inventario::LineMobileMaintenance();
        foreach($LineasMantenimiento as $row){
            $TotalMantenimiento = (int)$row->total;
        }
        $LineasObsoletos = Inventario::LineMobileObsolete();
        foreach($LineasObsoletos as $row){
            $TotalObsoletos = (int)$row->total;
        }

        $ListarLineasMoviles = Inventario::ListarLineasMoviles();
        $LineasMoviles = array();
        $contLM = 0;
        foreach($ListarLineasMoviles as $value){
            $IdLineaMovil                               = (int)$value->id;
            $LineasMoviles[$contLM]['id']               = (int)$value->id;
            $LineasMoviles[$contLM]['nro_linea']        = $value->nro_linea;
            $LineasMoviles[$contLM]['activo']           = (int)$value->activo;
            $LineasMoviles[$contLM]['proveedor']        = $value->proveedor;
            $LineasMoviles[$contLM]['plan']             = $value->plan;
            $LineasMoviles[$contLM]['serial']           = $value->serial;
            $LineasMoviles[$contLM]['fecha_ingreso']    = date('d/m/Y', strtotime($value->fecha_ingreso));
            $LineasMoviles[$contLM]['pto_cargo']        = $value->pto_cargo;
            $LineasMoviles[$contLM]['cc']               = $value->cc;
            $LineasMoviles[$contLM]['area']             = Funciones::eliminar_tildes_texto($value->area);
            $LineasMoviles[$contLM]['personal']         = $value->personal;
            $LineasMoviles[$contLM]['estado_equipo']    = $value->estado_equipo;
            $LineasMoviles[$contLM]['created_at']       = date('d/m/Y h:i A', strtotime($value->created_at));
            $LineasMoviles[$contLM]['user_id']          = $value->user_id;

            $IdActivo = (int)$value->activo;
            if($IdActivo === 1){
                $LineasMoviles[$contLM]['estado_activo']= 'Sí';
            }else{
                $LineasMoviles[$contLM]['estado_activo']= 'No';
            }

            $IdEstadoEquipo = (int)$value->estado_equipo;
            $EstadoEquipo   = Inventario::EstadoEquipoId($IdEstadoEquipo);
            foreach($EstadoEquipo as $row){
                switch($IdEstadoEquipo){
                    Case 1  :   $LineasMoviles[$contLM]['estado']  = $row->name;
                                $LineasMoviles[$contLM]['label']   = 'label label-primary';
                                break;
                    Case 2  :   $LineasMoviles[$contLM]['estado']  = $row->name;
                                $LineasMoviles[$contLM]['label']   = 'label label-success';
                                break;
                    Case 3  :   $LineasMoviles[$contLM]['estado']  = $row->name;
                                $LineasMoviles[$contLM]['label']   = 'label label-danger';
                                break;
                    Case 4  :   $LineasMoviles[$contLM]['estado']  = $row->name;
                                $LineasMoviles[$contLM]['label']   = 'label label-warning';
                                break;
                }
            }

            $LineasMoviles[$contLM]['evidencia']    = null;
            $evidenciaTicket = Inventario::EvidenciaLineaM($IdLineaMovil);
            $contadorEvidencia = count($evidenciaTicket);
            if($contadorEvidencia > 0){
                $contE = 1;
                foreach($evidenciaTicket as $row){
                    $LineasMoviles[$contLM]['evidencia'] .= "<p><a href='../assets/dist/img/evidencias_inventario/lineas/".$row->nombre."' target='_blank' class='btn btn-info'><i class='fa fa-phone'></i>&nbsp; Anexo Linea Movil $IdLineaMovil Nro. ".$contE."</a></p>";
                    $contE++;
                }
            }else{
                $LineasMoviles[$contLM]['evidencia'] = null;
            }

            $historialEquipoM = Inventario::BuscarHistorialLM($IdLineaMovil);
            $contadorHistorial = count($historialEquipoM);
            $LineasMoviles[$contLM]['historial'] = null;
            if($contadorHistorial > 0){
                foreach($historialEquipoM as $row){
                    $idUsuario  = $row->user_id;
                    $BuscarUsuario = Usuarios::BuscarNombre($idUsuario);
                    foreach($BuscarUsuario as $values){
                        $NombreUser = $values->name;
                    }
                    $LineasMoviles[$contLM]['historial'] .= "- ".Funciones::eliminar_tildes_texto($row->comentario)." (".$NombreUser." - ".date('d/m/Y h:i a', strtotime($row->created)).")\n";
                }
            }else{
                $LineasMoviles[$contLM]['historial'] = null;
            }

            $contLM++;
        }

        $Activo     = array();
        $Activo[''] = 'Seleccione: ';
        $Activo[1]  = 'Si';
        $Activo[0]  = 'No';

        $ListarProveedores = Inventario::ProveedorLM();
        $Proveedores  = array();
        $Proveedores[''] = 'Seleccione: ';
        foreach ($ListarProveedores as $row){
            $Proveedores[$row->id] = $row->name;
        }

        $EstadoLinea   = InventarioController::TipoEstado();
        return view('Inventario.LineMobile',['Stock' => $TotalStock,'Asignados' => $TotalAsignados,'Mantenimiento' => $TotalMantenimiento,'Obsoletos' => $TotalObsoletos,
                                            'LineasMoviles' => $LineasMoviles,'Activo' => $Activo,'Proveedores' => $Proveedores,'EstadoLinea' => $EstadoLinea,
                                            'FechaAdquisicion' => null,'Serial' => null,'NroLinea' => null,'Plan' => null,'PtoCargo' => null,'CC' => null,'Area' => null,
                                            'Personal' => null]);
    }

    public function desktops(){

        $LineasStock = Inventario::EquipoStock();
        foreach($LineasStock as $row){
            $TotalStock = (int)$row->total;
        }
        $LineasAsignados = Inventario::EquipoAsigned();
        foreach($LineasAsignados as $row){
            $TotalAsignados = (int)$row->total;
        }
        $LineasMantenimiento = Inventario::EquipoMaintenance();
        foreach($LineasMantenimiento as $row){
            $TotalMantenimiento = (int)$row->total;
        }
        $LineasObsoletos = Inventario::EquipoObsolete();
        foreach($LineasObsoletos as $row){
            $TotalObsoletos = (int)$row->total;
        }

        $ListarEquipos = Inventario::ListarEquipos();
        $Equipos = array();
        $contD = 0;
        foreach($ListarEquipos as $value){
            $IdEquipo                           = (int)$value->id;
            $Equipos[$contD]['id']              = (int)$value->id;

            $Equipos[$contD]['tipo_equipo']     = (int)$value->tipo_equipo;
            $IdTipoEquipo                       = (int)$value->tipo_equipo;
            $TipoEquipo                         = Inventario::BuscarEquipoId($IdTipoEquipo);
            foreach($TipoEquipo as $row){
                $Equipos[$contD]['tipoEquipo']  = $row->name;
            }

            $Equipos[$contD]['tipo_ingreso']    = (int)$value->tipo_ingreso;
            $IdTipoIngreso                      = (int)$value->tipo_ingreso;
            $TipoIngreso                        = Inventario::BuscarTipoIngresoId($IdTipoIngreso);
            foreach($TipoIngreso as $row){
                $Equipos[$contD]['tipoIngreso'] = $row->name;
            }

            $Equipos[$contD]['emp_renting']     = strtoupper($value->emp_renting);
            $Equipos[$contD]['fecha_ingreso']   = date('d/m/Y', strtotime($value->fecha_ingreso));
            $Equipos[$contD]['serial']          = $value->serial;
            $Equipos[$contD]['marca']           = strtoupper($value->marca);
            $Equipos[$contD]['procesador']      = $value->procesador;
            $Equipos[$contD]['vel_procesador']  = $value->vel_procesador;
            $Equipos[$contD]['disco_duro']      = $value->disco_duro;
            $Equipos[$contD]['memoria_ram']     = $value->memoria_ram;

            $Equipos[$contD]['estado_equipo']   = (int)$value->estado_equipo;
            $IdEstadoEquipo = (int)$value->estado_equipo;
            $EstadoEquipo   = Inventario::EstadoEquipoId($IdEstadoEquipo);
            foreach($EstadoEquipo as $row){
                switch($IdEstadoEquipo){
                    Case 1  :   $Equipos[$contD]['estado']  = $row->name;
                                $Equipos[$contD]['label']   = 'label label-primary';
                                break;
                    Case 2  :   $Equipos[$contD]['estado']  = $row->name;
                                $Equipos[$contD]['label']   = 'label label-success';
                                break;
                    Case 3  :   $Equipos[$contD]['estado']  = $row->name;
                                $Equipos[$contD]['label']   = 'label label-danger';
                                break;
                    Case 4  :   $Equipos[$contD]['estado']  = $row->name;
                                $Equipos[$contD]['label']   = 'label label-warning';
                                break;
                }
            }

            $Equipos[$contD]['evidencia']    = null;
            $evidenciaTicket = Inventario::EvidenciaEquipo($IdEquipo);
            $contadorEvidencia = count($evidenciaTicket);
            if($contadorEvidencia > 0){
                $contE = 1;
                foreach($evidenciaTicket as $row){
                    $Equipos[$contD]['evidencia'] .= "<p><a href='../assets/dist/img/evidencias_inventario/equipos/".$row->nombre."' target='_blank' class='btn btn-info'><i class='fa fa-desktop'></i>&nbsp; Anexo Equipo $IdEquipo Nro. ".$contE."</a></p>";
                    $contE++;
                }
            }else{
                $Equipos[$contD]['evidencia'] = null;
            }

            $historialEquipoM = Inventario::BuscarHistorialE($IdEquipo);
            $contadorHistorial = count($historialEquipoM);
            $Equipos[$contD]['historial'] = null;
            if($contadorHistorial > 0){
                foreach($historialEquipoM as $row){
                    $idUsuario  = $row->user_id;
                    $BuscarUsuario = Usuarios::BuscarNombre($idUsuario);
                    foreach($BuscarUsuario as $values){
                        $NombreUser = $values->name;
                    }
                    $Equipos[$contD]['historial'] .= "- ".Funciones::eliminar_tildes_texto($row->comentario)." (".$NombreUser." - ".date('d/m/Y h:i a', strtotime($row->created)).")\n";
                }
            }else{
                $Equipos[$contD]['historial'] = null;
            }

            $contD++;
        }

        $ListaTipoEquipo = Inventario::ListarEquipoUsuarioC();
        $TipoEquipo  = array();
        $TipoEquipo[''] = 'Seleccione: ';
        foreach ($ListaTipoEquipo as $row){
            $TipoEquipo[$row->id] = $row->name;
        }

        $TipoIngreso    = InventarioController::TipoIngreso();
        $EstadoEquipo   = InventarioController::TipoEstado();

        return view('Inventario.Desktops',['Stock' => $TotalStock,'Asignados' => $TotalAsignados,'Mantenimiento' => $TotalMantenimiento,'Obsoletos' => $TotalObsoletos,
                                            'Equipos' => $Equipos,'TipoEquipo' => $TipoEquipo,'TipoIngreso' => $TipoIngreso,'EstadoEquipo' =>$EstadoEquipo,
                                            'Renting' => null,'FechaAdquisicion' => null,'Serial' => null,'Procesador' => null,'Marca' => null,'VelProcesador' => null,
                                            'DiscoDuro' => null,'MemoriaRam' => null]);
    }

    public function periferic(){
        $LineasStock = Inventario::PerifericStock();
        foreach($LineasStock as $row){
            $TotalStock = (int)$row->total;
        }
        $LineasAsignados = Inventario::PerifericAsigned();
        foreach($LineasAsignados as $row){
            $TotalAsignados = (int)$row->total;
        }
        $LineasMantenimiento = Inventario::PerifericMaintenance();
        foreach($LineasMantenimiento as $row){
            $TotalMantenimiento = (int)$row->total;
        }
        $LineasObsoletos = Inventario::PerifericObsolete();
        foreach($LineasObsoletos as $row){
            $TotalObsoletos = (int)$row->total;
        }

        $ListarPerifericos = Inventario::ListarPerifericos();
        $Perifericos = array();
        $cont = 0;
        foreach($ListarPerifericos as $value){

            $IdPeriferico                           = (int)$value->id;
            $Perifericos[$cont]['id']               = (int)$value->id;
            $Perifericos[$cont]['tipo_periferico']  = (int)$value->tipo_periferico;
            $IdTipoPeriferico                       = (int)$value->tipo_periferico;
            $TipoPeriferico                         = Inventario::ListarTipoPerifericoID($IdTipoPeriferico);
            foreach($TipoPeriferico as $row){
                $Perifericos[$cont]['tipoPeriferico'] = $row->name;
            }
            $Perifericos[$cont]['tipo_ingreso']     = (int)$value->tipo_ingreso;
            $IdTipoIngreso                          = (int)$value->tipo_ingreso;
            $TipoIngreso                            = Inventario::BuscarTipoIngresoId($IdTipoIngreso);
            foreach($TipoIngreso as $row){
                $Perifericos[$cont]['tipoIngreso'] = $row->name;
            }
            $Perifericos[$cont]['emp_renting']      = $value->emp_renting;
            $Perifericos[$cont]['fecha_ingreso']    = date('d/m/Y', strtotime($value->fecha_ingreso));
            $Perifericos[$cont]['serial']           = $value->serial;
            $Perifericos[$cont]['marca']            = $value->marca;
            if($value->tamano){
                $Perifericos[$cont]['tamano']       = $value->tamano;
            }else{
                $Perifericos[$cont]['tamano']       = 'SIN INFORMACIÓN';
            }
            $Perifericos[$cont]['estado_periferico']= (int)$value->estado_periferico;
            $IdEstadoEquipo = (int)$value->estado_periferico;
            $EstadoEquipo   = Inventario::EstadoEquipoId($IdEstadoEquipo);
            foreach($EstadoEquipo as $row){
                switch($IdEstadoEquipo){
                    Case 1  :   $Perifericos[$cont]['estado']  = $row->name;
                                $Perifericos[$cont]['label']   = 'label label-primary';
                                break;
                    Case 2  :   $Perifericos[$cont]['estado']  = $row->name;
                                $Perifericos[$cont]['label']   = 'label label-success';
                                break;
                    Case 3  :   $Perifericos[$cont]['estado']  = $row->name;
                                $Perifericos[$cont]['label']   = 'label label-danger';
                                break;
                    Case 4  :   $Perifericos[$cont]['estado']  = $row->name;
                                $Perifericos[$cont]['label']   = 'label label-warning';
                                break;
                }
            }

            $Perifericos[$cont]['evidencia']    = null;
            $evidenciaTicket = Inventario::EvidenciaPeriferico($IdPeriferico);
            $contadorEvidencia = count($evidenciaTicket);
            if($contadorEvidencia > 0){
                $contE = 1;
                switch($IdTipoPeriferico){
                    Case 1 :    $Carpeta = 'pantallas/';
                                break;
                    Case 2 :    $Carpeta = 'mouse/';
                                break;
                    Case 3 :    $Carpeta = 'teclados/';
                                break;
                    Case 4 :    $Carpeta = 'guaya/';
                                break;
                    Case 5 :    $Carpeta = 'cargador/';
                                break;
                }
                foreach($evidenciaTicket as $row){
                    $Perifericos[$cont]['evidencia'] .= "<p><a href='../assets/dist/img/evidencias_inventario/$Carpeta".$row->nombre."' target='_blank' class='btn btn-info'><i class='fa fa-keyboard-o'></i>&nbsp; Anexo Periferico $IdPeriferico Nro. ".$contE."</a></p>";
                    $contE++;
                }
            }else{
                $Perifericos[$cont]['evidencia'] = null;
            }

            $historialEquipoM = Inventario::BuscarHistorialP($IdPeriferico);
            $contadorHistorial = count($historialEquipoM);
            $Perifericos[$cont]['historial'] = null;
            if($contadorHistorial > 0){
                foreach($historialEquipoM as $row){
                    $idUsuario  = $row->user_id;
                    $BuscarUsuario = Usuarios::BuscarNombre($idUsuario);
                    foreach($BuscarUsuario as $values){
                        $NombreUser = $values->name;
                    }
                    $Perifericos[$cont]['historial'] .= "- ".Funciones::eliminar_tildes_texto($row->comentario)." (".$NombreUser." - ".date('d/m/Y h:i a', strtotime($row->created)).")\n";
                }
            }else{
                $Perifericos[$cont]['historial'] = null;
            }

            $cont++;
        }

        $ListarTipoPeriferico = Inventario::ListarTipoPeriferico();
        $TipoPeriferico  = array();
        $TipoPeriferico[''] = 'Seleccione: ';
        foreach ($ListarTipoPeriferico as $row){
            $TipoPeriferico[$row->id] = $row->name;
        }

        $TipoIngreso    = InventarioController::TipoIngreso();
        $EstadoEquipo   = InventarioController::TipoEstado();
        return view('Inventario.Periferic',['Stock' => $TotalStock,'Asignados' => $TotalAsignados,'Mantenimiento' => $TotalMantenimiento,'Obsoletos' => $TotalObsoletos,
                                            'Perifericos' => $Perifericos,'TipoPeriferico' => $TipoPeriferico,'TipoIngreso' => $TipoIngreso,'EstadoEquipo' =>$EstadoEquipo,
                                            'Renting' => null,'FechaAdquisicion' => null,'Serial' => null,'Marca' => null,'Tamano' => null]);
    }

    public function consumible(){
        $LineasStock = Inventario::ConsumibleStock();
        foreach($LineasStock as $row){
            $TotalStock = (int)$row->total;
        }
        $LineasAsignados = Inventario::ConsumibleAsigned();
        foreach($LineasAsignados as $row){
            $TotalAsignados = (int)$row->total;
        }
        $LineasMantenimiento = Inventario::ConsumibleMaintenance();
        foreach($LineasMantenimiento as $row){
            $TotalMantenimiento = (int)$row->total;
        }
        $LineasObsoletos = Inventario::ConsumibleObsolete();
        foreach($LineasObsoletos as $row){
            $TotalObsoletos = (int)$row->total;
        }

        $ListarTipoConsumible = Inventario::ListarTipoConsumible();
        $TipoConsumible  = array();
        $TipoConsumible[''] = 'Seleccione: ';
        foreach ($ListarTipoConsumible as $row){
            $TipoConsumible[$row->id] = $row->name;
        }

        $ListarConsumibles = Inventario::ListarConsumibles();
        $Consumibles = array();
        $cont = 0;
        foreach($ListarConsumibles as $value){
            $IdConsumible                               = $value->id;
            $Consumibles[$cont]['id']                   = $value->id;
            $Consumibles[$cont]['tipo_consumible']      = $value->tipo_consumible;
            $IdTipoConsumible                           = $value->tipo_consumible;
            $listTipoConsumible = Inventario::ListarTipoConsumibleID($IdTipoConsumible);
            foreach($listTipoConsumible as $row){
                $Consumibles[$cont]['tipoConsumible']   = $row->name;
            }
            $Consumibles[$cont]['tipo_ingreso']         = (int)$value->tipo_ingreso;
            $IdTipoIngreso                              = (int)$value->tipo_ingreso;
            $ListTipoIngreso                                = Inventario::BuscarTipoIngresoId($IdTipoIngreso);
            foreach($ListTipoIngreso as $row){
                $Consumibles[$cont]['tipoIngreso']      = $row->name;
            }
            $Consumibles[$cont]['emp_renting']          = $value->emp_renting;
            $Consumibles[$cont]['fecha_ingreso']        = date('d/m/Y', strtotime($value->fecha_ingreso));
            $Consumibles[$cont]['serial']               = $value->serial;
            $Consumibles[$cont]['marca']                = $value->marca;
            $Consumibles[$cont]['modelo']               = $value->modelo;
            $Consumibles[$cont]['compa_ref']            = $value->compa_ref;
            $Consumibles[$cont]['compa_mod']            = $value->compa_mod;
            $Consumibles[$cont]['estado_consumible']    = (int)$value->estado_consumible;
            $IdEstadoEquipo = (int)$value->estado_consumible;
            $EstadoEquipo   = Inventario::EstadoEquipoId($IdEstadoEquipo);
            foreach($EstadoEquipo as $row){
                switch($IdEstadoEquipo){
                    Case 1  :   $Consumibles[$cont]['estado']  = $row->name;
                                $Consumibles[$cont]['label']   = 'label label-primary';
                                break;
                    Case 2  :   $Consumibles[$cont]['estado']  = $row->name;
                                $Consumibles[$cont]['label']   = 'label label-success';
                                break;
                    Case 3  :   $Consumibles[$cont]['estado']  = $row->name;
                                $Consumibles[$cont]['label']   = 'label label-danger';
                                break;
                    Case 4  :   $Consumibles[$cont]['estado']  = $row->name;
                                $Consumibles[$cont]['label']   = 'label label-warning';
                                break;
                }
            }
            $Consumibles[$cont]['evidencia']    = null;
            $evidenciaTicket = Inventario::EvidenciaConsumible($IdConsumible);
            $contadorEvidencia = count($evidenciaTicket);
            if($contadorEvidencia > 0){
                $contE = 1;
                foreach($evidenciaTicket as $row){
                    $Consumibles[$cont]['evidencia'] .= "<p><a href='../assets/dist/img/evidencias_inventario/consumibles/".$row->nombre."' target='_blank' class='btn btn-info'><i class='fa fa-tint'></i>&nbsp; Anexo Consumible $IdConsumible Nro. ".$contE."</a></p>";
                    $contE++;
                }
            }else{
                $Consumibles[$cont]['evidencia'] = null;
            }
            $historialEquipoM = Inventario::BuscarHistorialC($IdConsumible);
            $contadorHistorial = count($historialEquipoM);
            $Consumibles[$cont]['historial'] = null;
            if($contadorHistorial > 0){
                foreach($historialEquipoM as $row){
                    $idUsuario  = $row->user_id;
                    $BuscarUsuario = Usuarios::BuscarNombre($idUsuario);
                    foreach($BuscarUsuario as $values){
                        $NombreUser = $values->name;
                    }
                    $Consumibles[$cont]['historial'] .= "- ".Funciones::eliminar_tildes_texto($row->comentario)." (".$NombreUser." - ".date('d/m/Y h:i a', strtotime($row->created)).")\n";
                }
            }else{
                $Consumibles[$cont]['historial'] = null;
            }
            $cont++;
        }

        $TipoIngreso    = InventarioController::TipoIngreso();
        $EstadoEquipo   = InventarioController::TipoEstado();
        return view('Inventario.Consumible',['Stock' => $TotalStock,'Asignados' => $TotalAsignados,'Mantenimiento' => $TotalMantenimiento,'Obsoletos' => $TotalObsoletos,
                                             'TipoConsumible' => $TipoConsumible,'TipoIngreso' => $TipoIngreso,'Estado' => $EstadoEquipo,'Consumibles' => $Consumibles,
                                             'Renting' => null,'FechaAdquisicion' => null,'Serial' => null,'Marca' => null,'CompaRef' => null,'CompaMod' => null,'Modelo' => null]);
    }

    public function printers(){
        $ImpresorasStock = Inventario::ImpresoraStock();
        foreach($ImpresorasStock as $row){
            $TotalStock = (int)$row->total;
        }
        $ImpresorasAsignados = Inventario::ImpresoraAsigned();
        foreach($ImpresorasAsignados as $row){
            $TotalAsignados = (int)$row->total;
        }
        $ImpresorasMantenimiento = Inventario::ImpresoraMaintenance();
        foreach($ImpresorasMantenimiento as $row){
            $TotalMantenimiento = (int)$row->total;
        }
        $ImpresorasObsoletos = Inventario::ImpresoraObsolete();
        foreach($ImpresorasObsoletos as $row){
            $TotalObsoletos = (int)$row->total;
        }

        $ListarTipoImpresora = Inventario::ListarTipoImpresora();
        $TipoImpresora  = array();
        $TipoImpresora[''] = 'Seleccione: ';
        foreach ($ListarTipoImpresora as $row){
            $TipoImpresora[$row->id] = $row->name;
        }

        $ListarConsumibles = Inventario::ListarConsumiblesPrint();
        $Consumible = Array();
        $Consumible[''] = 'Seleccione: ';
        foreach($ListarConsumibles as $row){
            $Consumible[$row->id] = $row->marca.' - '.$row->modelo;
        }

        $ListarConsumiblesUpd = Inventario::ListarConsumiblesUpd();
        $ConsumibleUpd = Array();
        $ConsumibleUpd[''] = 'Seleccione: ';
        foreach($ListarConsumiblesUpd as $row){
            $EstadoConsumible = (int)$row->estado_consumible;
            if($EstadoConsumible === 1){
                $estadoC = 'Disponible';
            }else{
                $estadoC = 'Asignado';
            }
            $ConsumibleUpd[$row->id] = $row->marca.' - '.$row->modelo.' / '.$estadoC;
        }

        $ListarImpresoras = Inventario::ListarImpresoras();
        $Impresoras = array();
        $cont = 0;
        foreach($ListarImpresoras as $value){
            $IdImpresora                            = (int)$value->id;
            $Impresoras[$cont]['id']                = (int)$value->id;
            $Impresoras[$cont]['tipo_impresora']    = $value->tipo_impresora;
            $IdTipoImpresora                        = $value->tipo_impresora;
            $BuscarTIpoImpresora                    = Inventario::ListarTipoImpresoraID($IdTipoImpresora);
            foreach($BuscarTIpoImpresora as $row){
                $Impresoras[$cont]['tipoImpresora'] = $row->name;
            }
            $IdTipoIngreso                          = (int)$value->tipo_ingreso;
            $Impresoras[$cont]['tipo_ingreso']      = (int)$value->tipo_ingreso;
            $ListTipoIngreso                        = Inventario::BuscarTipoIngresoId($IdTipoIngreso);
            foreach($ListTipoIngreso as $row){
                $Impresoras[$cont]['tipoIngreso']   = $row->name;
            }
            $Impresoras[$cont]['emp_renting']       = $value->emp_renting;
            $Impresoras[$cont]['fecha_ingreso']     = date('d/m/Y', strtotime($value->fecha_ingreso));
            $Impresoras[$cont]['serial']            = $value->serial;
            $Impresoras[$cont]['marca']             = $value->marca;
            $Impresoras[$cont]['ip']                = $value->IP;
            $Impresoras[$cont]['id_consumible']     = (int)$value->id_consumible;
            $IdConsumible                           = (int)$value->id_consumible;
            $BuscarConsumible                       = Inventario::ListarConsumiblesID($IdConsumible);
            foreach($BuscarConsumible as $row){
                $Impresoras[$cont]['consumible']    = $row->marca.' - '.$row->modelo;
            }
            $Impresoras[$cont]['estado_impresora']  = (int)$value->estado_impresora;
            $IdEstadoEquipo                         = (int)$value->estado_impresora;
            $EstadoEquipo                           = Inventario::EstadoEquipoId($IdEstadoEquipo);
            foreach($EstadoEquipo as $row){
                switch($IdEstadoEquipo){
                    Case 1  :   $Impresoras[$cont]['estado']  = $row->name;
                                $Impresoras[$cont]['label']   = 'label label-primary';
                                break;
                    Case 2  :   $Impresoras[$cont]['estado']  = $row->name;
                                $Impresoras[$cont]['label']   = 'label label-success';
                                break;
                    Case 3  :   $Impresoras[$cont]['estado']  = $row->name;
                                $Impresoras[$cont]['label']   = 'label label-danger';
                                break;
                    Case 4  :   $Impresoras[$cont]['estado']  = $row->name;
                                $Impresoras[$cont]['label']   = 'label label-warning';
                                break;
                }
            }
            $Impresoras[$cont]['evidencia']         = null;
            $evidenciaTicket                        = Inventario::EvidenciaImpresora($IdImpresora);
            $contadorEvidencia = count($evidenciaTicket);
            if($contadorEvidencia > 0){
                $contE = 1;
                foreach($evidenciaTicket as $row){
                    $Impresoras[$cont]['evidencia'] .= "<p><a href='../assets/dist/img/evidencias_inventario/impresoras/".$row->nombre."' target='_blank' class='btn btn-info'><i class='fa fa-print'></i>&nbsp; Anexo Impresora $IdImpresora Nro. ".$contE."</a></p>";
                    $contE++;
                }
            }else{
                $Impresoras[$cont]['evidencia'] = null;
            }
            $historialEquipoM = Inventario::BuscarHistorialI($IdImpresora);
            $contadorHistorial = count($historialEquipoM);
            $Impresoras[$cont]['historial'] = null;
            if($contadorHistorial > 0){
                foreach($historialEquipoM as $row){
                    $idUsuario  = $row->user_id;
                    $BuscarUsuario = Usuarios::BuscarNombre($idUsuario);
                    foreach($BuscarUsuario as $values){
                        $NombreUser = $values->name;
                    }
                    $Impresoras[$cont]['historial'] .= "- ".Funciones::eliminar_tildes_texto($row->comentario)." (".$NombreUser." - ".date('d/m/Y h:i a', strtotime($row->created)).")\n";
                }
            }else{
                $Impresoras[$cont]['historial'] = null;
            }
            $cont++;
        }

        $TipoIngreso    = InventarioController::TipoIngreso();
        $Estado   = InventarioController::TipoEstado();
        return view('Inventario.Printers',['Stock' => $TotalStock,'Asignados' => $TotalAsignados,'Mantenimiento' => $TotalMantenimiento,'Obsoletos' => $TotalObsoletos,
                    'Renting' => null,'FechaAdquisicion' => null,'Serial' => null,'Marca' => null,'Ip' => null,'Consumible' => $Consumible,'TipoImpresora' => $TipoImpresora,
                    'Estado' => $Estado,'TipoIngreso' => $TipoIngreso,'Impresoras' => $Impresoras,'ConsumibleUpd' => $ConsumibleUpd]);
    }

    public function asigneds(){
        $LineasStock = Inventario::PerifericStock();
        foreach($LineasStock as $row){
            $TotalStock = (int)$row->total;
        }
        $LineasAsignados = Inventario::PerifericAsigned();
        foreach($LineasAsignados as $row){
            $TotalAsignados = (int)$row->total;
        }
        $LineasMantenimiento = Inventario::PerifericMaintenance();
        foreach($LineasMantenimiento as $row){
            $TotalMantenimiento = (int)$row->total;
        }
        $LineasObsoletos = Inventario::PerifericObsolete();
        foreach($LineasObsoletos as $row){
            $TotalObsoletos = (int)$row->total;
        }

        $ListarAsignados = Inventario::ListarAsignados();
        $Asignados = array();
        $cont = 0;
        foreach($ListarAsignados as $value){
            $idAsignado                             = $value->id;
            $Asignados[$cont]['id']                 = $value->id;
            $Asignados[$cont]['tipo_equipo']        = (int)$value->tipo_equipo;
            $IdTipoEquipo                           = (int)$value->tipo_equipo;
            $TipoEquipo                             = Inventario::BuscarEquipoId($IdTipoEquipo);
            foreach($TipoEquipo as $row){
                $Asignados[$cont]['tipoEquipo']     = $row->name;
            }
            $Asignados[$cont]['id_equipo']          = (int)$value->id_equipo;
            $IdEquipo                               = (int)$value->id_equipo;
            $Equipo                                 = Inventario::BuscarIdEquipo($IdEquipo);
            if($Equipo){
                foreach($Equipo as $row){
                    $Asignados[$cont]['equipo']     = $row->marca.' / '.$row->serial;
                }
            }else{
                $Asignados[$cont]['equipo']         = 'SIN EQUIPO';
            }

            $Asignados[$cont]['id_mouse']           = (int)$value->id_mouse;
            $IdMouse                                = (int)$value->id_mouse;
            $Mouse                                  = Inventario::BuscarMouseId($IdMouse);
            if($Mouse){
                foreach($Mouse as $row){
                    $Asignados[$cont]['mouse']      = $row->marca.' / '.$row->serial;
                }
            }else{
                $Asignados[$cont]['mouse']          = 'SIN MOUSE';
            }

            $Asignados[$cont]['id_pantalla']        = (int)$value->id_pantalla;
            $IdPantalla                             = (int)$value->id_pantalla;
            $Pantalla                               = Inventario::BuscarPantallaId($IdPantalla);
            if($Pantalla){
                foreach($Pantalla as $row){
                    $Asignados[$cont]['pantalla']   = $row->marca.' / '.$row->serial;
                }
            }else{
                $Asignados[$cont]['pantalla']       = 'SIN PANTALLA';
            }
            $Asignados[$cont]['id_teclado']         = (int)$value->id_teclado;
            $IdTeclado                              = (int)$value->id_teclado;
            $Teclado                                = Inventario::BuscarTecladoId($IdTeclado);
            if($Teclado){
                foreach($Teclado as $row){
                    $Asignados[$cont]['teclado']    = $row->marca.' / '.$row->serial;
                }
            }else{
                $Asignados[$cont]['teclado']        = 'SIN PANTALLA';
            }

            $Asignados[$cont]['id_cargador']        = (int)$value->id_cargador;
            $IdCargador                             = (int)$value->id_cargador;
            $Cargador                               = Inventario::BuscarCargadorId($IdCargador);
            if($Cargador ){
                foreach($Cargador as $row){
                    $Asignados[$cont]['cargador']   = $row->marca.' / '.$row->serial;
                }
            }else{
                $Asignados[$cont]['cargador']       = 'SIN CARGADOR';
            }
            $Asignados[$cont]['id_guaya']           = (int)$value->id_guaya;
            $IdGuaya                                = (int)$value->id_guaya;
            $Guaya                                  = Inventario::BuscarGuayaId($IdGuaya);
            if($Guaya){
                foreach($Guaya as $row){
                    $Asignados[$cont]['guaya']      = $row->marca.' / '.$row->serial;
                }
                $Asignados[$cont]['opcion']         = '1';
            }else{
                $Asignados[$cont]['guaya']          = 'SIN GUAYA';
                $Asignados[$cont]['opcion']         = '0';
            }
            $Asignados[$cont]['tipo_guaya']         = (int)$value->tipo_guaya;
            $IdTipoGuaya                            = (int)$value->tipo_guaya;
            $TipoGuaya                              = Inventario::BuscarTipoGuayaId($IdTipoGuaya);
            foreach($TipoGuaya as $row){
                $Asignados[$cont]['tipoGuaya']      = $row->name;
            }
            $Asignados[$cont]['code_guaya']         = $value->code_guaya;
            $Asignados[$cont]['sede']               = (int)$value->sede;
            $Asignados[$cont]['area']               = Funciones::eliminar_tildes_texto($value->area);
            $Asignados[$cont]['nombre_usuario']     = Funciones::eliminar_tildes_texto($value->nombre_usuario);
            $Asignados[$cont]['cargo_usuario']      = Funciones::eliminar_tildes_texto($value->cargo_usuario);
            $Asignados[$cont]['id_usuario']         = $value->id_usuario;
            $Asignados[$cont]['tel_usuario']        = $value->tel_usuario;
            $Asignados[$cont]['correo']             = $value->correo;
            $Asignados[$cont]['id_ticket']          = (int)$value->id_ticket;
            $Asignados[$cont]['fecha_asignacion']   = date('d/m/Y', strtotime($value->fecha_asignacion));
            $Asignados[$cont]['estado_asignado']    = (int)$value->estado_asignado;
            $IdEstadoEquipo                         = (int)$value->estado_asignado;
            $EstadoEquipo                           = Inventario::EstadoEquipoId($IdEstadoEquipo);
            foreach($EstadoEquipo as $row){
                switch($IdEstadoEquipo){
                    Case 1  :   $Asignados[$cont]['estado']  = $row->name;
                                $Asignados[$cont]['label']   = 'label label-primary';
                                break;
                    Case 2  :   $Asignados[$cont]['estado']  = $row->name;
                                $Asignados[$cont]['label']   = 'label label-success';
                                break;
                    Case 3  :   $Asignados[$cont]['estado']  = $row->name;
                                $Asignados[$cont]['label']   = 'label label-danger';
                                break;
                    Case 4  :   $Asignados[$cont]['estado']  = $row->name;
                                $Asignados[$cont]['label']   = 'label label-warning';
                                break;
                }
            }
            $Asignados[$cont]['evidencia']         = null;
            $evidenciaTicket                       = Inventario::EvidenciaImpresora($idAsignado);
            $contadorEvidencia = count($evidenciaTicket);
            if($contadorEvidencia > 0){
                $contE = 1;
                foreach($evidenciaTicket as $row){
                    $Asignados[$cont]['evidencia'] .= "<p><a href='../assets/dist/img/evidencias_inventario/asignados/".$row->nombre."' target='_blank' class='btn btn-info'><i class='fa fa-print'></i>&nbsp; Anexo Asignado $idAsignado Nro. ".$contE."</a></p>";
                    $contE++;
                }
            }else{
                $Asignados[$cont]['evidencia']      = null;
            }
            $historialEquipoM = Inventario::BuscarHistorialA($idAsignado);
            $contadorHistorial = count($historialEquipoM);
            $Asignados[$cont]['historial'] = null;
            if($contadorHistorial > 0){
                foreach($historialEquipoM as $row){
                    $idUsuario  = $row->user_id;
                    $BuscarUsuario = Usuarios::BuscarNombre($idUsuario);
                    foreach($BuscarUsuario as $values){
                        $NombreUser = $values->name;
                    }
                    $Asignados[$cont]['historial'] .= "- ".Funciones::eliminar_tildes_texto($row->comentario)." (".$NombreUser." - ".date('d/m/Y h:i a', strtotime($row->created)).")\n";
                }
            }else{
                $Asignados[$cont]['historial']      = null;
            }
            $cont++;
        }
        $Estado   = InventarioController::TipoEstado();
        $ListarEquipos = Inventario::ListarEquipoUsuarioC();
        $Equipos  = array();
        $Equipos[''] = 'Seleccione: ';
        foreach ($ListarEquipos as $row){
            $Equipos[$row->id] = $row->name;
        }
        $ListarMouse = Inventario::ListarMouseActivo();
        $Mouse  = array();
        $Mouse[''] = 'Seleccione: ';
        foreach ($ListarMouse as $row){
            $Mouse[$row->id] = $row->marca.' - '.$row->serial;
        }
        $ListarMouseUpd = Inventario::ListarMouseActivoUpd();
        $MouseUpd  = array();
        $MouseUpd[''] = 'Seleccione: ';
        foreach ($ListarMouseUpd as $row){
            $MouseUpd[$row->id] = $row->marca.' - '.$row->serial;
        }
        $ListarPantalla = Inventario::ListarPantallaActivo();
        $Pantalla  = array();
        $Pantalla[''] = 'Seleccione: ';
        foreach ($ListarPantalla as $row){
            $Pantalla[$row->id] = $row->marca.' - '.$row->serial;
        }
        $ListarPantallaUpd = Inventario::ListarPantallaActivoUpd();
        $PantallaUpd  = array();
        $PantallaUpd[''] = 'Seleccione: ';
        foreach ($ListarPantallaUpd as $row){
            $PantallaUpd[$row->id] = $row->marca.' - '.$row->serial;
        }
        $ListarTeclado = Inventario::ListarTecladoActivo();
        $Teclado  = array();
        $Teclado[''] = 'Seleccione: ';
        foreach ($ListarTeclado as $row){
            $Teclado[$row->id] = $row->marca.' - '.$row->serial;
        }
        $ListarTecladoUpd = Inventario::ListarTecladoActivoUpd();
        $TecladoUpd  = array();
        $TecladoUpd[''] = 'Seleccione: ';
        foreach ($ListarTecladoUpd as $row){
            $TecladoUpd[$row->id] = $row->marca.' - '.$row->serial;
        }
        $ListarCargador = Inventario::ListarCargadorActivo();
        $Cargador  = array();
        $Cargador[''] = 'Seleccione: ';
        foreach ($ListarCargador as $row){
            $Cargador[$row->id] = $row->marca.' - '.$row->serial;
        }
        $ListarCargadorUpd = Inventario::ListarCargadorActivoUpd();
        $CargadorUpd  = array();
        $CargadorUpd[''] = 'Seleccione: ';
        foreach ($ListarCargadorUpd as $row){
            $CargadorUpd[$row->id] = $row->marca.' - '.$row->serial;
        }
        $ListarGuaya = Inventario::ListarGuayaActivo();
        $Guaya  = array();
        $Guaya[''] = 'Seleccione: ';
        foreach ($ListarGuaya as $row){
            $Guaya[$row->id] = $row->marca.' - '.$row->serial;
        }
        $ListarGuayaUpd = Inventario::ListarGuayaActivoUpd();
        $GuayaUpd  = array();
        $GuayaUpd[''] = 'Seleccione: ';
        foreach ($ListarGuayaUpd as $row){
            $GuayaUpd[$row->id] = $row->marca.' - '.$row->serial;
        }
        $NombreSede = array();
        $NombreSede[''] = 'Seleccione: ';
        $Sedes  = Sedes::Sedes();
        foreach ($Sedes as $row){
            $NombreSede[$row->id] = Funciones::eliminar_tildes_texto($row->name);
        }
        $Marca  = array();
        $Marca[''] = 'Seleccione: ';
        $Opcion  = array();
        $Opcion[''] = 'Seleccione: ';
        $Opcion[1] = 'Sí';
        $Opcion[0] = 'No';
        $ListarMarca = Inventario::ListarMarcaActivoUpd();
        $MarcaUpd  = array();
        $MarcaUpd[''] = 'Seleccione: ';
        foreach ($ListarMarca as $row){
            $MarcaUpd[$row->id] = $row->marca.' - '.$row->serial;
        }

        $NombreArea = array();
        $NombreArea[''] = 'Seleccione: ';
        return view('Inventario.Asignados',['Stock' => $TotalStock,'TAsignados' => $TotalAsignados,'Mantenimiento' => $TotalMantenimiento,'Obsoletos' => $TotalObsoletos,'Areas' => $NombreArea,
                                            'Estado' => $Estado,'Asignados' => $Asignados,'Equipos' => $Equipos,'Mouse' => $Mouse,'Pantalla' => $Pantalla,'Teclado' => $Teclado,
                                            'Cargador' => $Cargador,'Guaya' => $Guaya,'Sede' => $NombreSede,'Area' => null,'NombreAsignado' => null,'Cargo' => null,'Cedula' => null,
                                            'Telefono' => null,'Correo' => null,'Ticket' => null,'FechaAsignacion' => null,'Marca' => $Marca,'Opcion' => $Opcion,'MarcaUpd' => $MarcaUpd,
                                            'MouseUpd' => $MouseUpd,'PantallaUpd' => $PantallaUpd,'TecladoUpd' => $TecladoUpd,'CargadorUpd' => $CargadorUpd,'GuayaUpd' => $GuayaUpd]);
    }
}
