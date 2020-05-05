<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Models\Admin\Sedes;
use App\Models\Admin\Usuarios;
use App\Models\Admin\Roles;
use App\Models\Helpdesk\Tickets;
use App\Models\HelpDesk\Inventario;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Funciones;


class TicketsController extends Controller
{
    public function tickets(){
        $buscarTickets  = Tickets::Tickets();
        $creadoPor      = (int)Session::get('IdUsuario');
        $tickets        = array();
        $cont           = 0;
        date_default_timezone_set('America/Bogota');
        foreach($buscarTickets as $value){
            $id_ticket                      = (int)$value->id;
            $tickets[$cont]['id']           = (int)$value->id;
            $tickets[$cont]['title']        = Funciones::eliminar_tildes_texto($value->title);
            $tickets[$cont]['description']  = Funciones::eliminar_tildes_texto($value->description);
            $tickets[$cont]['created_at']   = date('d/m/Y h:i A', strtotime($value->created_at));
            if($value->updated_at){
                $tickets[$cont]['updated_at']   = date('d/m/Y h:i A', strtotime($value->updated_at));
            }else{
                $tickets[$cont]['updated_at']   = "SIN FECHA DE ACTUALIZACIÓN";
            }

            $tickets[$cont]['kind_id']       = (int)$value->kind_id;
            $idTipoTicket = (int)$value->kind_id;
            $TipoTicket = Tickets::Tipo($idTipoTicket);
            foreach($TipoTicket as $row){
                $tickets[$cont]['tipo_ticket'] = $row->name;
            }

            $tickets[$cont]['user_id']      = (int)$value->user_id;
            $tickets[$cont]['asigned_id']   = (int)$value->asigned_id;
            $tickets[$cont]['session_id']   = (int)$value->session_id;
            // $idAsignador    =  (int)$value->user_id;
            $idAsignado     =  (int)$value->asigned_id;
            $BuscarTicketAsignado = Tickets::BuscarAsignador($id_ticket);
            if($BuscarTicketAsignado){
                foreach($BuscarTicketAsignado as $valorB){
                    $idAsignador = (int)$valorB->user_id;
                }
            }else{
                $idAsignador    =  (int)$value->user_id;
            }

            $Asignador  = Usuarios::BuscarNombre($idAsignador);
            $Asignado   = Usuarios::BuscarNombre($idAsignado);
            if($Asignador){
                foreach($Asignador as $row){
                    $tickets[$cont]['asignado_por'] = strtoupper($row->name);
                }
            }else{
                $tickets[$cont]['asignado_por']     = 'SIN NOMBRE';
            }
            if($Asignado){
                foreach($Asignado as $row){
                    $tickets[$cont]['asignado_a']   = strtoupper($row->name);
                }
            }else{
                $tickets[$cont]['asignado_a']       = 'SIN NOMBRE';
            }


            $tickets[$cont]['project_id']           = (int)$value->project_id;
            $idSede = (int)$value->project_id;
            $BuscarSede = Sedes::BuscarSedeID($idSede);
            foreach($BuscarSede as $row){
                $tickets[$cont]['sede']             = Funciones::eliminar_tildes_texto(strtoupper($row->name));
            }

            $tickets[$cont]['dependencia']          = (int)$value->dependencia;
            $dependencia = $value->dependencia;
            if($dependencia === null){
                $tickets[$cont]['area']             = "SIN ÁREA/DEPENDENCIA";
            }else{
                $tickets[$cont]['area']             = Funciones::eliminar_tildes_texto(strtoupper($dependencia));
            }

            $tickets[$cont]['category_id']          = (int)$value->category_id;
            $IdCategoria    = (int)$value->category_id;
            $Categoria      =  Roles::BuscarCategoriaID($IdCategoria);
            foreach($Categoria as $row){
                $tickets[$cont]['categoria'] = strtoupper($row->name);
            }


            $tickets[$cont]['priority_id']          = (int)$value->priority_id;
            $IdPrioridad   = (int)$value->priority_id;
            $Prioridad     =  Tickets::BuscarPrioridadID($IdPrioridad);
            foreach($Prioridad as $row){
                if($IdPrioridad === 1){
                    $tickets[$cont]['prioridad']    = strtoupper($row->name);
                    $tickets[$cont]['label']        = 'label label-danger';
                }else if($IdPrioridad === 2){
                    $tickets[$cont]['prioridad']    = strtoupper($row->name);
                    $tickets[$cont]['label']        = 'label label-warning';
                }else if($IdPrioridad === 3){
                    $tickets[$cont]['prioridad']    = strtoupper($row->name);
                    $tickets[$cont]['label']        = 'label label-success';
                }else{
                    $tickets[$cont]['prioridad']    = 'SIN PRIORIDAD';
                }
            }

            $tickets[$cont]['status_id']   = (int)$value->status_id;
            $IdEstado   = (int)$value->status_id;
            $Estado     =  Tickets::Estado($IdEstado);
            foreach($Estado as $row){
                $tickets[$cont]['estado'] = strtoupper($row->name);
            }

            $tickets[$cont]['name_user']    = strtoupper($value->name_user);
            $tickets[$cont]['tel_user']     = $value->tel_user;
            $tickets[$cont]['user_email']   = $value->user_email;
            $tickets[$cont]['evidencia']    = null;
            $evidenciaTicket = Tickets::EvidenciaTicket($id_ticket);
            $contadorEvidencia = count($evidenciaTicket);
            if($contadorEvidencia > 0){
                $contE = 1;
                foreach($evidenciaTicket as $row){
                    $tickets[$cont]['evidencia'] .= "<p><a href='../assets/dist/img/evidencias/".$row->nombre_evidencia."' target='_blank' class='btn btn-info'><i class='fa fa-file-archive-o'></i>&nbsp;Anexo Ticket  $id_ticket Nro. ".$contE."</a></p>";
                    $contE++;
                }
            }else{
                $tickets[$cont]['evidencia']    = null;
            }

            $historialTicket = Tickets::HistorialTicket($id_ticket);
            $contadorHistorial = count($historialTicket);
            $tickets[$cont]['historial'] = null;
            if($contadorHistorial > 0){
                foreach($historialTicket as $row){
                    $tickets[$cont]['historial'] .= "- ". Funciones::eliminar_tildes_texto($row->observacion)." (".$row->user_id." - ".date('d/m/Y h:i a', strtotime($row->created)).")\n";
                }
            }else{
                $tickets[$cont]['historial']    = null;
            }
            $tickets[$cont]['id_create_user']   = (int)$value->id_create_user;
            $tickets[$cont]['h_asigned_id']     = (int)$value->h_asigned_id;
            $idAsignadoh = (int)$value->h_asigned_id;
            $AsignadoH   = Usuarios::BuscarNombre($idAsignadoh);
            foreach($AsignadoH as $row){
                $tickets[$cont]['asignado_h'] = strtoupper($row->name);
            }

            $cont++;
        }

        $Categoria  = Roles::ListarCategorias();
        $NombreCategoria = array();
        $NombreCategoria[''] = 'Seleccione: ';
        foreach ($Categoria as $row){
            $NombreCategoria[$row->id] = Funciones::eliminar_tildes_texto($row->name);
        }

        $Prioridad  = Tickets::ListarPrioridad();
        $NombrePrioridad = array();
        $NombrePrioridad[''] = 'Seleccione: ';
        foreach ($Prioridad as $row){
            $NombrePrioridad[$row->id] = $row->name;
        }

        $NombreUsuario = array();
        $NombreUsuario[''] = 'Seleccione: ';

        $NombreSede = array();
        $NombreSede[''] = 'Seleccione: ';

        $Sedes  = Tickets::Sedes();

        foreach ($Sedes as $row){
            $NombreSede[$row->id] = Funciones::eliminar_tildes_texto($row->name);
        }

        $Tipo  = Tickets::ListarTipo();
        $NombreTipo = array();
        $NombreTipo[0] = 'Seleccione: ';
        foreach ($Tipo as $row){
            $NombreTipo[$row->id] = $row->name;
        }

        $Estado  = Tickets::ListarEstado();
        $NombreEstado = array();
        $NombreEstado[0] = 'Seleccione: ';
        foreach ($Estado as $row){
            $NombreEstado[$row->id] = $row->name;
        }

        $EstadoUpd  = Tickets::ListarEstadoUpd();
        $NombreEstadoUpd = array();
        $NombreEstadoUpd[0] = 'Seleccione: ';
        foreach ($EstadoUpd as $row){
            $NombreEstadoUpd[$row->id] = $row->name;
        }

        $EstadoA  = Tickets::ListarEstadoA();
        $NombreEstadoA = array();
        $NombreEstadoA[0] = 'Seleccione: ';
        foreach ($EstadoA as $row){
            $NombreEstadoA[$row->id] = $row->name;
        }

        Tickets::UpdateNotificacion($creadoPor);
        $valorCero = 0;
        $valorNull = null;
        Session::put('Notificaciones',$valorCero);
        Session::put('Notificacion',$valorNull);

        $NombreArea = array();
        $NombreArea[''] = 'Seleccione: ';

        return view('tickets.tickets',['Tickets' => $tickets,'NombreCategoria' => $NombreCategoria,
                                    'NombreUsuario' => $NombreUsuario,'NombrePrioridad' => $NombrePrioridad,'NombreEstado' => $NombreEstado,'NombreEstadoUpd' => $NombreEstadoUpd,
                                    'NombreSede' => $NombreSede,'CorreoUsuario' => null,'NombreTipo' => $NombreTipo,
                                    'Usuario' => null,'Descripcion' => null,'TelefonoUsuario' => null,'Evidencia' => null,'Asunto' => null,'Comentario' => null,
                                    'NombreEstadoA' => $NombreEstadoA,'NombreCargo' => null,'Areas' => $NombreArea,
                                    'Dependencia' => null]);
    }

    public function ticketsUsuario(){
        $buscarTicketsU     = Tickets::ListarTicketsUsuario();
        $ticketsUsuario     = array();
        $cont               = 0;
        date_default_timezone_set('America/Bogota');
        foreach($buscarTicketsU as $value){
            $ticketsUsuario[$cont]['id']                = $value->id;
            $ticketsUsuario[$cont]['nombres']           = Funciones::eliminar_tildes_texto(strtoupper($value->nombres));
            $ticketsUsuario[$cont]['identificacion']    = $value->identificacion;
            $ticketsUsuario[$cont]['cargo']             = Funciones::eliminar_tildes_texto($value->cargo);

            $ticketsUsuario[$cont]['id_sede']           = $value->id_sede;
            $idSede = (int)$value->id_sede;
            $BuscarSede = Sedes::BuscarSedeID($idSede);
            foreach($BuscarSede as $row){
                $ticketsUsuario[$cont]['nombre_sede']   = Funciones::eliminar_tildes_texto(strtoupper($row->name));
            }

            $ticketsUsuario[$cont]['area']              = Funciones::eliminar_tildes_texto($value->area);
            $ticketsUsuario[$cont]['jefe']              = $value->jefe;
            $ticketsUsuario[$cont]['fecha_ingreso']     = date('d/m/Y', strtotime($value->fecha_ingreso));
            $ticketsUsuario[$cont]['email']             = $value->email;
            $ticketsUsuario[$cont]['new_cargo']         = $value->new_cargo;
            $ticketsUsuario[$cont]['funcionario_rem']   = $value->funcionario_rem;
            $ticketsUsuario[$cont]['correo_fun']        = $value->correo_fun;
            $ticketsUsuario[$cont]['new_email']         = $value->new_email;
            $ticketsUsuario[$cont]['celular']           = $value->celular;
            $ticketsUsuario[$cont]['datos']             = $value->datos;
            $ticketsUsuario[$cont]['minutos']           = $value->minutos;
            $ticketsUsuario[$cont]['equipo']            = $value->equipo;
            $ticketsUsuario[$cont]['extension']         = $value->extension;
            $ticketsUsuario[$cont]['app85']             = $value->app85;
            $ticketsUsuario[$cont]['dinamica']          = $value->dinamica;
            $ticketsUsuario[$cont]['other_app']         = $value->other_app;
            $ticketsUsuario[$cont]['carpeta']           = $value->carpeta;
            $ticketsUsuario[$cont]['vpn']               = $value->vpn;
            $ticketsUsuario[$cont]['internet']          = $value->internet;
            $ticketsUsuario[$cont]['cap85']             = $value->cap85;
            $ticketsUsuario[$cont]['capdinamica']       = $value->capdinamica;

            $ticketsUsuario[$cont]['prioridad']         = (int)$value->prioridad;
            $IdPrioridad                                = (int)$value->prioridad;
            $Prioridad                                  = Tickets::BuscarPrioridadID($IdPrioridad);
            foreach($Prioridad as $row){
                $NombrePrioridad                        = strtoupper($row->name);
            }
            if($IdPrioridad === 1){
                $ticketsUsuario[$cont]['nombre_prioridad']  = $NombrePrioridad;
                $ticketsUsuario[$cont]['label']             = 'label label-danger';
            }else if($IdPrioridad === 2){
                $ticketsUsuario[$cont]['nombre_prioridad']  = $NombrePrioridad;
                $ticketsUsuario[$cont]['label']             = 'label label-warning';
            }else if($IdPrioridad === 3){
                $ticketsUsuario[$cont]['nombre_prioridad']  = $NombrePrioridad;
                $ticketsUsuario[$cont]['label']             = 'label label-success';
            }else{
                $ticketsUsuario[$cont]['nombre_prioridad']  = 'SIN PRIORIDAD';
                $ticketsUsuario[$cont]['label']             = 'label label-general';
            }

            $ticketsUsuario[$cont]['estado']            = (int)$value->estado;
            $IdEstado   = (int)$value->estado;
            $Estado     =  Tickets::Estado($IdEstado);
            foreach($Estado as $row){
                $ticketsUsuario[$cont]['nombre_estado'] = strtoupper($row->name);
            }

            $ticketsUsuario[$cont]['estado_rc']         = $value->estado_rc;
            $ticketsUsuario[$cont]['estado_app']        = $value->estado_app;
            $ticketsUsuario[$cont]['estado_it']         = $value->estado_it;
            $EstadoRc       = (int)$value->estado_rc;
            $EstadoApp      = (int)$value->estado_app;
            $EstadoIt       = (int)$value->estado_it;
            if($EstadoRc === 1){
                $ticketsUsuario[$cont]['estadorc']      = 'CREADO';
            }else{
                $ticketsUsuario[$cont]['estadorc']      = 'NO CREADO';
            }
            if($EstadoApp === 1){
                $ticketsUsuario[$cont]['estadoapp']     = 'CREADO';
            }else{
                $ticketsUsuario[$cont]['estadoapp']     = 'NO CREADO';
            }
            if($EstadoIt === 1){
                $ticketsUsuario[$cont]['estadoit']      = 'CREADO';
            }else{
                $ticketsUsuario[$cont]['estadoit']      = 'NO CREADO';
            }

            $ticketsUsuario[$cont]['id_user']           = $value->id_user;
            $ticketsUsuario[$cont]['observaciones']     = Funciones::eliminar_tildes_texto($value->observaciones);
            $ticketsUsuario[$cont]['user_dominio']      = $value->user_dominio;

            $cont++;
        }

        $Prioridad  = Tickets::ListarPrioridadA();
        $NombrePrioridad = array();
        $NombrePrioridad[''] = 'Seleccione: ';
        foreach ($Prioridad as $row){
            $NombrePrioridad[$row->id] = $row->name;
        }

        $EstadoA  = Tickets::ListarEstadoA();
        $NombreEstadoA = array();
        $NombreEstadoA[0] = 'Seleccione: ';
        foreach ($EstadoA as $row){
            $NombreEstadoA[$row->id] = $row->name;
        }

        $Opciones       = array();
        $Opciones['']   = 'Seleccione: ';
        $Opciones[1]    = 'SI';
        $Opciones[0]    = 'NO';

        $NombreEquipo       = array();
        $NombreEquipo[0]   = 'Seleccione: ';
        $Equipo             = Inventario::ListarEquipoUsuarioC();
        foreach ($Equipo as $row){
            $NombreEquipo[$row->id] = $row->name;
        }

        $NombreSede = array();
        $NombreSede[''] = 'Seleccione: ';
        $Sedes  = Sedes::Sedes();
        foreach ($Sedes as $row){
            $NombreSede[$row->id] = Funciones::eliminar_tildes_texto($row->name);
        }

        $Acceso       = array();
        $Acceso['']   = 'Seleccione: ';
        $Acceso[1]    = 'Básico';
        $Acceso[2]    = 'Medio';
        $Acceso[3]    = 'VIP';
        $Acceso[4]    = 'Bloqueo';
        $Acceso[0]    = 'NO';

        $NombreArea = array();
        $NombreArea[''] = 'Seleccione: ';

        return view('tickets.ticketsUsuario',['Opciones' => $Opciones,'Prioridad' => $NombrePrioridad,'Estado' => $NombreEstadoA,
                                              'TicketUsuario' => $ticketsUsuario,'NombresCompletos' => null,'Identificacion' =>null,
                                              'Cargo' => null,'Sede' => $NombreSede,'Area' => null,'Jefe' => null,'FechaIngreso' => null,
                                              'CorreoSolicitante' => null,'Funcionario' => null,'CorreoFuncionario' => null,'Equipo' => $NombreEquipo,
                                              'Aplicativo' => null,'Carpeta' => null,'Acceso' => $Acceso,'Observaciones' => null,'Areas' => $NombreArea]);
    }

    public function reporteTickets(){
        $Categoria              = Usuarios::Categoria();
        $NombreCategoria        = array();
        $NombreCategoria['']    = 'Seleccione: ';
        foreach ($Categoria as $row){
            $NombreCategoria[$row->id] = $row->name;
        }
        $Tipo               = Tickets::ListarTipo();
        $NombreTipo         = array();
        $NombreTipo['']     = 'Seleccione: ';
        foreach ($Tipo as $row){
            $NombreTipo[$row->id] = $row->name;
        }
        $Prioridad              = Tickets::ListarPrioridad();
        $NombrePrioridad        = array();
        $NombrePrioridad['']    = 'Seleccione: ';
        foreach ($Prioridad as $row){
            $NombrePrioridad[$row->id] = $row->name;
        }

        $NombreUsuario      = array();
        $NombreUsuario['']  = 'Seleccione: ';
        $Usuarios = Usuarios::ListarUsuarios();
        foreach ($Usuarios as $row){
            $NombreUsuario[$row->id] = $row->name;
        }
        $NombreSede         = array();
        $NombreSede['']     = 'Seleccione: ';
        $Sedes  = Sedes::Sedes();
        $NombreSedes        = array();
        $NombreSedes['']    = 'Seleccione: ';
        foreach ($Sedes as $row){
            $NombreSedes[$row->id] = Funciones::eliminar_tildes_texto($row->name);
        }

        $Estado             = Tickets::ListarEstadoUpd();
        $NombreEstado       = array();
        // $NombreEstado['']   = 'Seleccione: ';
        foreach ($Estado as $row){
            $NombreEstado[$row->id] = $row->name;
        }
        $Areas          = Sedes::ListarAreas();
        $NombreArea     = array();
        $NombreArea[0]  = 'Seleccione: ';
        foreach ($Areas as $row){
            $NombreArea[$row->id] = Funciones::eliminar_tildes_texto($row->name);
        }

        $Opcion     = array();
        $Opcion[''] = "Seleccione :";
        $Opcion[1]  = "Número de Ticket";
        $Opcion[2]  = "Fechas y otras opciones";
        return view('tickets.reporte',['Tipo' => $NombreTipo,'Estado' => $NombreEstado,'Categoria' => $NombreCategoria,
                                        'Usuario' => $NombreUsuario,'Prioridad' => $NombrePrioridad,'Opcion' => $Opcion,
                                        'Sede' => $NombreSedes, 'FechaInicio' => null,'FechaFin' => null,'Areas' => $NombreArea]);
    }

    public function consultarTickets(Request $request){
        $validator = Validator::make($request->all(), [
            'fechaInicio'   =>  'required',
            'fechaFin'      =>  'required'
        ]);

        if ($validator->fails()) {
            $verrors = $validator->errors();
            return Response::json(['valido'=>'false','errors'=>$verrors]);
        }else{

            $idTipo         = $request->id_tipo;
            $idCategoria    = $request->id_categoria;
            $idUsuarioC     = $request->id_creado;
            $idUsuarioA     = $request->id_asignado;
            $idPrioridad    = $request->id_prioridad;
            $idEstado       = $request->id_estado;
            $idSede         = $request->id_sede;
            $idArea         = $request->id_area;
            $finicio        = $request->fechaInicio;
            $ffin           = $request->fechaFin;
            $consultaReporte = Tickets::Reporte($idTipo,$idCategoria,$idUsuarioC,$idUsuarioA,$idPrioridad,$idEstado,$idSede,$idArea,$finicio,$ffin);

            $resultado = json_decode(json_encode($consultaReporte), true);
            foreach($resultado as &$value) {
                $value['title']             = Funciones::eliminar_tildes_texto($value['title']);
                $value['description']       = Funciones::eliminar_tildes_texto($value['description']);
                $value['dependencia']       = Funciones::eliminar_tildes_texto($value['dependencia']);
                $value['created_at']        = date('d/m/Y h:i A', strtotime($value['created_at']));
                if($value['updated_at']){
                    $value['updated_at']    = date('d/m/Y h:i A', strtotime($value['updated_at']));
                }else{
                    $value['updated_at']    = 'SIN ACTUALIZACIÓN';
                }
                $id_tipo                    = (int)$value['kind_id'];
                $nombreTipo                 = Tickets::Tipo($id_tipo);
                foreach($nombreTipo as $valor){
                    $value['kind_id']       = $valor->name;
                }
                $id_categoria               = (int)$value['category_id'];
                $nombreCategoria            = Tickets::Categoria($id_categoria);
                foreach($nombreCategoria as $valor){
                    $value['category_id']   =  Funciones::eliminar_tildes_texto($valor->name);
                }
                $id_sede                    = (int)$value['project_id'];
                $nombreSedeS = Sedes::BuscarSedeID($id_sede);
                foreach($nombreSedeS as $valor){
                    $value['project_id']    =  Funciones::eliminar_tildes_texto($valor->name);
                }
                $id_prioridad               = (int)$value['priority_id'];
                $nombrePrioridad            = Tickets::Prioridad($id_prioridad);
                foreach($nombrePrioridad as $valor){
                    switch($id_prioridad){
                        Case 1: $value['priority_id'] = "<span class='label label-danger' style='font-size:13px;'><b></b>".$valor->name."</span>";
                                break;
                        Case 2: $value['priority_id'] = "<span class='label label-warning' style='font-size:13px;'><b></b>".$valor->name."</span>";
                                break;
                        Case 3: $value['priority_id'] = "<span class='label label-success' style='font-size:13px;'><b></b>".$valor->name."</span>";
                                break;
                    }

                }
                $id_estado                  = (int) $value['status_id'];
                $nombreEstado               = Tickets::Estado($id_estado);
                foreach($nombreEstado as $valor){
                    $value['status_id']     = $valor->name;
                }
                $creado                     = (int)$value['user_id'];
                $buscarUsuario              = Usuarios::BuscarNombre($creado);
                foreach($buscarUsuario as $valor){
                    $value['user_id'] = $valor->name;
                }
                $asignado                   = (int)$value['asigned_id'];
                $buscarUsuario              = Usuarios::BuscarNombre($asignado);
                foreach($buscarUsuario as $valor){
                    $value['asigned_id'] = $valor->name;
                }
                $value['name_user']         = Funciones::eliminar_tildes_texto($value['name_user']);
                $id_ticket                  = $value['id'];
                $value['historial']         = null;
                $historialTicket            = Tickets::HistorialTicket($id_ticket);
                $contadorHistorial          = count($historialTicket);
                if($contadorHistorial > 0){
                    foreach($historialTicket as $row){
                        $value['historial'] .= "- ". Funciones::eliminar_tildes_texto($row->observacion)." (".$row->user_id." - ".date('d/m/Y h:i a', strtotime($row->created)).")\n";
                    }
                }else{
                    $value['historial']     = null;
                }
            }

            $aResultado = json_encode($resultado);
            Session::put('results', $aResultado);

            if($consultaReporte){
                if($aResultado){
                    return Response::json(['valido'=>'true','results'=>$aResultado]);
                }else{
                    $verrors = array();
                    array_push($verrors, 'No hay datos que mostrar');
                    return Response::json(['valido'=>'false','errors'=>$verrors]);
                }
            }else{
                $verrors = array();
                array_push($verrors, 'No hay datos que mostrar');
                return Response::json(['valido'=>'false','errors'=>$verrors]);
            }
        }

    }

    public function consultarxTicket(Request $request){
        $validator = Validator::make($request->all(), [
            'ticket'   =>  'required'
        ]);

        if ($validator->fails()) {
            $verrors = $validator->errors();
            return Response::json(['valido'=>'false','errors'=>$verrors]);
        }else{

            $Ticket         = (int)$request->ticket;
            $consultaReporte = Tickets::BuscarTicket($Ticket);

            $resultado = json_decode(json_encode($consultaReporte), true);
            foreach($resultado as &$value) {
                $value['title'] =  Funciones::eliminar_tildes_texto($value['title']);
                $value['description'] =  Funciones::eliminar_tildes_texto($value['description']);
                $value['dependencia'] =  Funciones::eliminar_tildes_texto($value['dependencia']);
                $value['created_at'] = date('d/m/Y h:i A', strtotime($value['created_at']));
                if($value['updated_at']){
                    $value['updated_at'] = date('d/m/Y h:i A', strtotime($value['updated_at']));
                }else{
                    $value['updated_at'] = 'SIN ACTUALIZACIÓN';
                }
                $id_tipo = (int)$value['kind_id'];
                $nombreTipo = Tickets::Tipo($id_tipo);
                foreach($nombreTipo as $valor){
                    $value['kind_id'] = $valor->name;
                }
                $id_categoria = (int)$value['category_id'];
                $nombreCategoria = Tickets::Categoria($id_categoria);
                foreach($nombreCategoria as $valor){
                    $value['category_id'] = $valor->name;
                }
                $id_sede = (int)$value['project_id'];
                $nombreSedeS = Sedes::BuscarSedeID($id_sede);
                foreach($nombreSedeS as $valor){
                    $value['project_id'] = Funciones::eliminar_tildes_texto($valor->name);
                }
                $id_prioridad = (int)$value['priority_id'];
                $nombrePrioridad = Tickets::Prioridad($id_prioridad);
                foreach($nombrePrioridad as $valor){
                    switch($id_prioridad){
                        Case 1: $value['priority_id'] = "<span class='label label-danger' style='font-size:13px;'><b></b>".$valor->name."</span>";
                                break;
                        Case 2: $value['priority_id'] = "<span class='label label-warning' style='font-size:13px;'><b></b>".$valor->name."</span>";
                                break;
                        Case 3: $value['priority_id'] = "<span class='label label-success' style='font-size:13px;'><b></b>".$valor->name."</span>";
                                break;
                    }

                }
                $id_estado = (int)$value['status_id'];
                $nombreEstado = Tickets::Estado($id_estado);
                foreach($nombreEstado as $valor){
                    $value['status_id'] = $valor->name;
                }
                $creado = (int)$value['user_id'];
                $buscarUsuario = Usuarios::BuscarNombre($creado);
                foreach($buscarUsuario as $valor){
                    $value['user_id'] = $valor->name;
                }
                $asignado = (int)$value['asigned_id'];
                $buscarUsuario = Usuarios::BuscarNombre($asignado);
                foreach($buscarUsuario as $valor){
                    $value['asigned_id'] = $valor->name;
                }
                $value['name_user'] = strtoupper($value['name_user']);
                $id_ticket = (int)$value['id'];
                $value['historial'] = null;
                $historialTicket = Tickets::HistorialTicket($id_ticket);
                $contadorHistorial = count($historialTicket);
                if($contadorHistorial > 0){
                    foreach($historialTicket as $row){
                        $value['historial'] .= "- ".Funciones::eliminar_tildes_texto($row->observacion)." (".$row->user_id." - ".date('d/m/Y h:i a', strtotime($row->created)).")\n";
                    }
                }else{
                    $value['historial'] = null;
                }
            }

            $aResultado = json_encode($resultado);
            Session::put('results', $aResultado);
            if(empty($consultaReporte)){
                $verrors = array();
                array_push($verrors, 'No hay datos que mostrar');
                return Response::json(['valido'=>'false','errors'=>$verrors]);
            }else if(!empty($aResultado)){
                return Response::json(['valido'=>'true','results'=>$aResultado]);
            }else{
                $verrors = array();
                array_push($verrors, 'No hay datos que mostrar');
                return Response::json(['valido'=>'false','errors'=>$verrors]);
            }
        }
    }

    public function reabrirTicket(Request $request){

        $validator = Validator::make($request->all(), [
            'id_ticket'             =>  'required',
            'descripcion_ticket'    =>  'required',
            'id_categoriaT'         => 'required',
            'id_usuarioT'           => 'required',
            'id_prioridadT'         => 'required',
            'id_estadoT'            => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('admin/tickets')->withErrors($validator)->withInput();
        }else{

            $idTicket = $request->id_ticket;
            $busqueda = Tickets::BuscarTicket($idTicket);
            if($busqueda){
                $desrcipcion = $request->descripcion_ticket;
                $idCategoria = $request->id_categoriaT;
                $idUsuario = $request->id_usuarioT;
                $idPrioridad = $request->id_prioridadT;
                $idEstado = $request->id_estadoT;
                $User = Session::get('IdUsuario');

                $aperturaTicket = Tickets::Apertura($idTicket,$idCategoria,$idUsuario,$idPrioridad,$idEstado,$User,$desrcipcion);

                if($aperturaTicket){
                    $verrors = 'Se actualizo con éxito el ticket '.$idTicket;
                    return redirect('admin/tickets')->with('mensaje', $verrors);
                }else{
                    $verrors = array();
                    array_push($verrors, 'Hubo un problema al reabrir el ticket');
                    return Redirect::to('admin/tickets')->withErrors(['errors' => $verrors])->withInput();
                }

            }else{

                $verrors = array();
                array_push($verrors, 'No se encontro información del ticket '.$idTicket);
                return Redirect::to('admin/tickets')->withErrors(['errors' => $verrors])->withInput();

            }

        }
    }

    public function ticketsRecurrentes(){
        $Categoria          = Roles::ListarCategorias();
        $NombreCategoria    = array();
        $NombreCategoria[''] = 'Seleccione: ';
        foreach ($Categoria as $row){
            $NombreCategoria[$row->id] = $row->name;
        }
        $Prioridad          = Tickets::ListarPrioridad();
        $NombrePrioridad    = array();
        $NombrePrioridad[''] = 'Seleccione: ';
        foreach ($Prioridad as $row){
            $NombrePrioridad[$row->id] = $row->name;
        }
        $Activo    = array();
        $Activo[''] = 'Seleccione: ';
        $Activo[1] = 'Sí';
        $Activo[0] = 'No';

        $Tipo    = array();
        $Tipo[''] = 'Seleccione: ';
        $Tipo[1] = 'Soporte';
        $Tipo[2] = 'Usuario Final';
        $Tipo[3] = 'General';

        $Tickets            = Tickets::TicketsRecurrentes();
        $TicketsRecurrentes = array();
        $cont               = 0;
        foreach($Tickets as $row){
            $TicketsRecurrentes[$cont]['id']            = (int)$row->id;
            $TicketsRecurrentes[$cont]['nombre']        = $row->nombre;

            $TicketsRecurrentes[$cont]['category_id']   = (int)$row->category_id;
            $IdCategoria    = (int)$row->category_id;
            $Categoria      =  Roles::BuscarCategoriaID($IdCategoria);
            foreach($Categoria as $value){
                $TicketsRecurrentes[$cont]['categoria'] = strtoupper($value->name);
            }

            $TicketsRecurrentes[$cont]['priority_id']   = (int)$row->priority_id;
            $IdPrioridad   = (int)$row->priority_id;
            $Prioridad     =  Tickets::BuscarPrioridadID($IdPrioridad);
            foreach($Prioridad as $value){
                if($IdPrioridad === 1){
                    $TicketsRecurrentes[$cont]['prioridad']     = strtoupper($value->name);
                    $TicketsRecurrentes[$cont]['label']         = 'label label-danger';
                }else if($IdPrioridad === 2){
                    $TicketsRecurrentes[$cont]['prioridad']     = strtoupper($value->name);
                    $TicketsRecurrentes[$cont]['label']         = 'label label-warning';
                }else if($IdPrioridad === 3){
                    $TicketsRecurrentes[$cont]['prioridad']     = strtoupper($value->name);
                    $TicketsRecurrentes[$cont]['label']         = 'label label-success';
                }else{
                    $TicketsRecurrentes[$cont]['prioridad']     = 'SIN PRIORIDAD';
                }
            }

            $TicketsRecurrentes[$cont]['id_activo']     = (int)$row->activo;
            $ActivoT                                    = (int)$row->activo;
            if($ActivoT === 1){
                $TicketsRecurrentes[$cont]['activo']    = 'Sí';
            }else{
                $TicketsRecurrentes[$cont]['activo']    = 'No';
            }
            $TicketsRecurrentes[$cont]['tipo']          = (int)$row->tipo;
            switch((int)$row->tipo){
                case 1:     $TicketsRecurrentes[$cont]['usuario'] = 'Soporte';
                            break;
                case 2:     $TicketsRecurrentes[$cont]['usuario'] = 'Usuario Final';
                            break;
                case 3:     $TicketsRecurrentes[$cont]['usuario'] = 'General';
                            break;
            }
            $cont++;
        }
        return view('Tickets.ticketsRecurrentes',['Categoria' => $NombreCategoria,'Prioridad' => $NombrePrioridad,'TicketsRecurrentes' => $TicketsRecurrentes,
                                                    'Activo' => $Activo,'Tipo' => $Tipo]);
    }


}
