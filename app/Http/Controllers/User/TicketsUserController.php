<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response;
use App\Models\HelpDesk\Inventario;
use App\Models\Admin\Sedes;
use App\Models\Admin\Roles;
use App\Models\HelpDesk\Tickets;
use App\Http\Requests\Validaciones;
use Validator;
use Monolog\Handler\ZendMonitorHandler;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Admin\Usuarios;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;


class TicketsUserController extends Controller
{

    public function tickets()
    {
        $creadoPor          = (int)Session::get('IdUsuario');
        $IdRolUSer          = (int)Session::get('Rol');
        $IdCategoriaUSer    = (int)Session::get('Categoria');
        $buscarTickets      = Tickets::TicketsUsuario($creadoPor,$IdRolUSer,$IdCategoriaUSer);
        $tickets = array();
        $cont = 0;
        date_default_timezone_set('America/Bogota');
        foreach($buscarTickets as $value){
            $id_ticket                      = (int)$value->id;
            $tickets[$cont]['id']           = (int)$value->id;
            $tickets[$cont]['title']        = TicketsUserController::eliminar_tildes_texto($value->title);
            $tickets[$cont]['description']  = TicketsUserController::eliminar_tildes_texto($value->description);
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
            $idAsignador    =  (int)$value->user_id;
            $idAsignado     =  (int)$value->asigned_id;

            $Asignador  = Usuarios::BuscarNombre($idAsignador);
            $Asignado   = Usuarios::BuscarNombre($idAsignado);
            if($Asignador){
                foreach($Asignador as $row){
                    $tickets[$cont]['asignado_por'] = strtoupper($row->name);
                }
            }else{
                $tickets[$cont]['asignado_por'] = 'SIN NOMBRE';
            }
            if($Asignado){
                foreach($Asignado as $row){
                    $tickets[$cont]['asignado_a'] = strtoupper($row->name);
                }
            }else{
                $tickets[$cont]['asignado_a'] = 'SIN NOMBRE';
            }


            $tickets[$cont]['project_id']   = (int)$value->project_id;
            $idSede = (int)$value->project_id;
            $BuscarSede = Sedes::BuscarSedeID($idSede);
            foreach($BuscarSede as $row){
                $tickets[$cont]['sede'] = TicketsUserController::eliminar_tildes_texto(strtoupper($row->name));
            }

            $tickets[$cont]['dependencia']   = (int)$value->dependencia;
            $dependencia = $value->dependencia;
            if($dependencia === null){
                $tickets[$cont]['area'] = "SIN ÁREA/DEPENDENCIA";
            }else{
                $tickets[$cont]['area'] = TicketsUserController::eliminar_tildes_texto(strtoupper($dependencia));
            }

            $tickets[$cont]['category_id']   = (int)$value->category_id;
            $IdCategoria    = (int)$value->category_id;
            $Categoria      =  Roles::BuscarCategoriaID($IdCategoria);
            foreach($Categoria as $row){
                $tickets[$cont]['categoria'] = strtoupper($row->name);
            }


            $tickets[$cont]['priority_id']   = (int)$value->priority_id;
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
                    $tickets[$cont]['prioridad'] = 'SIN PRIORIDAD';
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
                $tickets[$cont]['evidencia'] = null;
            }

            $historialTicket = Tickets::HistorialTicket($id_ticket);
            $contadorHistorial = count($historialTicket);
            $tickets[$cont]['historial'] = null;
            if($contadorHistorial > 0){
                foreach($historialTicket as $row){
                    $tickets[$cont]['historial'] .= "- ".$row->observacion." (".$row->user_id." - ".date('d/m/Y h:i a', strtotime($row->created)).")\n";
                }
            }else{
                $tickets[$cont]['historial'] = null;
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
            $NombreCategoria[$row->id] = $row->name;
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
            $NombreSede[$row->id] = TicketsUserController::eliminar_tildes_texto($row->name);
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

        return view('tickets.tickets',['Tickets' => $tickets,'NombreTipo' => $NombreTipo,'NombreCategoria' => $NombreCategoria,
                                    'NombreUsuario' => $NombreUsuario,'NombrePrioridad' => $NombrePrioridad,'NombreEstado' => $NombreEstado,'NombreEstadoUpd' => $NombreEstadoUpd,
                                    'NombreSede' => $NombreSede,'CorreoUsuario' => null,'NombreEstadoA' => $NombreEstadoUpd,'Areas' => $NombreArea,
                                    'Usuario' => null,'Descripcion' => null,'TelefonoUsuario' => null,'Evidencia' => null,'Asunto' => null,'Comentario' => null,
                                    'Dependencia' => null,'NombreCargo' => null,'NombreJefe' => null,'TelefonoJefe' => null]);
    }

    public function ticketsUsuario(){
        $buscarTicketsU = Tickets::ListarTicketsUsuario();
        $ticketsUsuario = array();
        $cont = 0;
        date_default_timezone_set('America/Bogota');
        foreach($buscarTicketsU as $value){
            $ticketsUsuario[$cont]['id']                = $value->id;
            $ticketsUsuario[$cont]['nombres']           = strtoupper($value->nombres);
            $ticketsUsuario[$cont]['identificacion']    = $value->identificacion;
            $ticketsUsuario[$cont]['cargo']             = $value->cargo;

            $ticketsUsuario[$cont]['id_sede']           = $value->id_sede;
            $idSede = (int)$value->id_sede;
            $BuscarSede = Sedes::BuscarSedeID($idSede);
            foreach($BuscarSede as $row){
                $ticketsUsuario[$cont]['nombre_sede']   = strtoupper($row->name);
            }

            $ticketsUsuario[$cont]['area']              = $value->area;
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
            $Prioridad                                  =  Tickets::BuscarPrioridadID($IdPrioridad);
            foreach($Prioridad as $row){
                $NombrePrioridad                        = strtoupper($row->name);
            }
            if($IdPrioridad === 1){
                $ticketsUsuario[$cont]['nombre_prioridad']     = $NombrePrioridad;
                $ticketsUsuario[$cont]['label']         = 'label label-danger';
            }else if($IdPrioridad === 2){
                $ticketsUsuario[$cont]['nombre_prioridad']     = $NombrePrioridad;
                $ticketsUsuario[$cont]['label']         = 'label label-warning';
            }else if($IdPrioridad === 3){
                $ticketsUsuario[$cont]['nombre_prioridad']     = $NombrePrioridad;
                $ticketsUsuario[$cont]['label']         = 'label label-success';
            }else{
                $ticketsUsuario[$cont]['nombre_prioridad']     = 'SIN PRIORIDAD';
                $ticketsUsuario[$cont]['label']         = 'label label-general';
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
            $ticketsUsuario[$cont]['observaciones']     = $value->observaciones;
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
            $NombreSede[$row->id] = $row->name;
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
        $Categoria  = Usuarios::Categoria();
        $NombreCategoria = array();
        $NombreCategoria[''] = 'Seleccione: ';
        foreach ($Categoria as $row){
            $NombreCategoria[$row->id] = $row->name;
        }
        $Tipo  = Tickets::ListarTipo();
        $NombreTipo = array();
        $NombreTipo[''] = 'Seleccione: ';
        foreach ($Tipo as $row){
            $NombreTipo[$row->id] = $row->name;
        }
        $Prioridad  = Tickets::ListarPrioridad();
        $NombrePrioridad = array();
        $NombrePrioridad[''] = 'Seleccione: ';
        foreach ($Prioridad as $row){
            $NombrePrioridad[$row->id] = $row->name;
        }

        $NombreUsuario = array();
        $NombreUsuario[''] = 'Seleccione: ';
        $Usuarios = Usuarios::ListarUsuarios();
        foreach ($Usuarios as $row){
            $NombreUsuario[$row->id] = $row->name;
        }

        $NombreSede = array();
        $NombreSede[''] = 'Seleccione: ';
        $Sedes  = Sedes::Sedes();
        $NombreSedes = array();
        $NombreSedes[''] = 'Seleccione: ';
        foreach ($Sedes as $row){
            $NombreSedes[$row->id] = TicketsUserController::eliminar_tildes_texto($row->name);
        }

        $Estado  = Tickets::ListarEstadoUpd();
        $NombreEstado = array();
        $NombreEstado[0] = 'Seleccione: ';
        foreach ($Estado as $row){
            $NombreEstado[$row->id] = $row->name;
        }

        $Areas  = Sedes::ListarAreas();
        $NombreArea = array();
        $NombreArea[0] = 'Seleccione: ';
        foreach ($Areas as $row){
            $NombreArea[$row->id] = TicketsUserController::eliminar_tildes_texto($row->name);
        }

        $Opcion = array();
        $Opcion[''] = "Seleccione :";
        $Opcion[1] = "Número de Ticket";
        $Opcion[2] = "Fechas y otras opciones";
        return view('tickets.reporte',['Tipo' => $NombreTipo,'Estado' => $NombreEstado,'Categoria' => $NombreCategoria,
                                        'Usuario' => $NombreUsuario,'Prioridad' => $NombrePrioridad,'Opcion' => $Opcion,
                                        'Sede' => $NombreSedes, 'FechaInicio' => null,'FechaFin' => null,'Areas' => $NombreArea]);
    }

    public function crearTicket()
    {
        $data = Input::all();
        $reglas = array(
            'id_tipo'           =>  'required',
            'asunto'            =>  'required',
            'descripcion'       =>  'required',
            'nombre_usuario'    =>  'required',
            'telefono_usuario'  =>  'required',
            'correo_usuario'    =>  'required',
            'id_zona'           =>  'required',
            'id_sede'           =>  'required',
            'id_area'           =>  'required',
            'id_prioridad'      =>  'required',
            'id_categoria'      =>  'required',
            'id_usuario'        =>  'required',
            'id_estado'         =>  'required'
        );
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {

            $idTipo             = (int)Input::get('id_tipo');
            $Asunto             = Input::get('asunto');
            $Descripcion        = Input::get('descripcion');
            $NombreUsuario      = Input::get('nombre_usuario');
            $TelefonoUsuario    = Input::get('telefono_usuario');
            $CorreUsuario       = Input::get('correo_usuario');
            $IdZona             = (int)Input::get('id_zona');
            $IdSede             = (int)Input::get('id_sede');
            $IdArea             = (int)Input::get('id_area');
            $Prioridad          = (int)Input::get('id_prioridad');
            $Categoria          = (int)Input::get('id_categoria');
            $AsignadoA          = (int)Input::get('id_usuario');
            $Estado             = (int)Input::get('id_estado');
            $creadoPor          = (int)Input::get('IdUsuario');

            $nombreCategoria = Tickets::Categoria($Categoria);
            $nombrePrioridad = Tickets::Prioridad($Prioridad);
            $nombreEstado = Tickets::Estado($Estado);
            $nombreAsignado = Usuarios::BuscarNombre($AsignadoA);

            foreach($nombreCategoria as $row){
                $nameCategoria = $row->nombre;
            }
            foreach($nombrePrioridad as $row){
                $namePrioridad = $row->nombre;
            }
            foreach($nombreEstado as $row){
                $nameEstado = $row->name;
            }
            foreach($nombreAsignado as $row){
                $nameAsignado = $row->nombre;
            }

            $CrearTicket = Tickets::CrearTicket($idTipo,$Asunto,$Descripcion,$NombreUsuario,$TelefonoUsuario,$CorreUsuario,$CargoUsuario,
            $IdZona,$IdSede,$IdArea,$Prioridad,$Categoria,$AsignadoA,$Estado,$creadoPor);

            if($CrearTicket){
                $buscarUltimo = Tickets::BuscarLastTicket($creadoPor);
                foreach($buscarUltimo as $row){
                    $ticket = $row->id;
                }
                $destinationPath = null;
                $filename        = null;
                if (Input::hasFile('evidencia')) {
                    $files = Input::file('evidencia');
                    foreach($files as $file){
                        $destinationPath    = public_path().'/aplicativo/evidencias';
                        $extension          = $file->getClientOriginalExtension();
                        $name               = $file->getClientOriginalName();
                        $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                        $filename           = $nombrearchivo.'_Ticket_'.$ticket.'.'.$extension;
                        $uploadSuccess      = $file->move($destinationPath, $filename);
                        $archivofoto        = file_get_contents($uploadSuccess);
                        $NombreFoto         = $filename;
                        $actualizarEvidencia = Tickets::Evidencia($ticket,$NombreFoto);
                    }
                }

                date_default_timezone_set('America/Bogota');
                $fecha_sistema  = date('d-m-Y H:i a');
                $fechaCreacion  = date('d-m-Y H:i a', strtotime($fecha_sistema));

                $subject = "Creación ticket Mesa de ayuda";
                $for = "$CorreUsuario";
                Mail::send('email/EmailCreacion',
                        ['Ticket' => $ticket,'Asunto' => $Asunto,'Categoria' => $nameCategoria,'Prioridad' => $namePrioridad,
                        'Mensaje' => $Descripcion, 'NombreReportante' => $NombreUsuario, 'Telefono' => $TelefonoUsuario,
                        'Correo' => $CorreUsuario,'AsignadoA' => $nameAsignado,'Estado' => $nameEstado,'Fecha' => $fechaCreacion],
                        function($msj) use($subject,$for){
                            $msj->from("desarrolladorsenior.tics@gmail.com","Mesa de Ayuda Tics - Servisalud QCL");
                            $msj->subject($subject);
                            $msj->to($for);
                        });

                $verrors = 'Se creo con éxito el ticket '.$ticket;
                return redirect('user/tickets')->with('mensaje', $verrors);
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al crear el ticket');
                // return redirect('user/usuarios')->withErrors(['errors' => $verrors])->withInput();
                return \Redirect::to('user/tickets')->withErrors(['errors' => $verrors])->withInput();
            }


        }else{
            return \Redirect::to('user/tickets')->withErrors(['errors' => $verrors])->withInput();
        }
    }

    public function buscarCategoria()
    {

        $data = Input::all();
        $id   = Input::get('id_categoria');
        $NombreUsuario = array();
        $buscarUsuario = Usuarios::BuscarXCategoria($id);
        $NombreUsuario[0] = 'Seleccione: ';
        foreach ($buscarUsuario as $row){
            $NombreUsuario[$row->id] = $row->nombre;
        }
        return \Response::json(array('valido'=>'true','Usuario'=>$NombreUsuario));

    }

    public function buscarCategoriaRepo()
    {

        $data = Input::all();
        $id   = Input::get('id_categoria');
        $NombreUsuario = array();
        $buscarUsuario = Usuarios::BuscarXCategoria($id);
        $NombreUsuario[0] = 'Seleccione: ';
        foreach ($buscarUsuario as $row){
            $NombreUsuario[$row->id] = $row->nombre;
        }
        return \Response::json(array('valido'=>'true','Usuario'=>$NombreUsuario));

    }

    public function buscarCategoriaUPD()
    {

        $data = Input::all();
        $id   = Input::get('id_categoria');
        $NombreUsuario = array();
        $buscarUsuario = Usuarios::BuscarXCategoria($id);
        $NombreUsuario[0] = 'Seleccione: ';
        foreach ($buscarUsuario as $row){
            $NombreUsuario[$row->id] = $row->nombre;
        }
        return \Response::json(array('valido'=>'true','Usuario'=>$NombreUsuario));

    }

    public function actualizarTicket()
    {
        $data = Input::all();
        $reglas = array(
            'id_prioridad_upd'      =>  'required',
            'id_categoriaupd'       =>  'required',
            'id_usuarioupd'         =>  'required',
            'id_estado_upd'         =>  'required',
            'comentario'            =>  'required'
        );
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {

            $idTicket           = (int)Input::get('idT');
            $idTipo             = (int)Input::get('id_tipo_upd');
            $Asunto             = Input::get('asunto_upd');
            $Descripcion        = Input::get('descripcion_upd');
            $NombreUsuario      = Input::get('nombre_usuario_upd');
            $TelefonoUsuario    = Input::get('telefono_usuario_upd');
            $CorreUsuario       = Input::get('correo_usuario_upd');
            $IdZona             = (int)Input::get('id_zona_upd');
            $IdSede             = (int)Input::get('id_sede_upd');
            $IdArea             = (int)Input::get('id_area_upd');
            $Prioridad          = (int)Input::get('id_prioridad_upd');
            $Categoria          = (int)Input::get('id_categoriaupd');
            $AsignadoA          = (int)Input::get('id_usuarioupd');
            $Estado             = (int)Input::get('id_estado_upd');
            $creadoPor          = (int)Input::get('IdUsuario');
            $comentario         = Input::get('comentario');

            $nombreCategoria = Tickets::Categoria($Categoria);
            $nombrePrioridad = Tickets::Prioridad($Prioridad);
            $nombreEstado = Tickets::Estado($Estado);
            $nombreAsignado = Usuarios::BuscarNombre($AsignadoA);

            foreach($nombreCategoria as $row){
                $nameCategoria = $row->nombre;
            }
            foreach($nombrePrioridad as $row){
                $namePrioridad = $row->nombre;
            }
            foreach($nombreEstado as $row){
                $nameEstado = $row->name;
            }
            foreach($nombreAsignado as $row){
                $nameAsignado = $row->nombre;
            }

            $actualizarTicket   = Tickets::ActualizarTicket($idTicket,$idTipo,$Asunto,$Descripcion,$NombreUsuario,$TelefonoUsuario,$CorreUsuario,$CargoUsuario,
            $IdZona,$IdSede,$IdArea,$Prioridad,$Categoria,$AsignadoA,$Estado,$creadoPor,$comentario);
            if($actualizarTicket){

                $destinationPath = null;
                $filename        = null;
                if (Input::hasFile('evidencia_upd')) {
                    $files = Input::file('evidencia_upd');
                    foreach($files as $file){
                        $destinationPath    = public_path().'/aplicativo/evidencias';
                        $extension          = $file->getClientOriginalExtension();
                        $name               = $file->getClientOriginalName();
                        $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                        $filename           = $nombrearchivo.'_Ticket_'.$idTicket.'.'.$extension;
                        $uploadSuccess      = $file->move($destinationPath, $filename);
                        $archivofoto        = file_get_contents($uploadSuccess);
                        $NombreFoto         = $filename;
                        $actualizarEvidencia = Tickets::Evidencia($idTicket,$NombreFoto);
                    }
                }

                date_default_timezone_set('America/Bogota');
                $fecha_sistema  = date('d-m-Y H:i a');
                $fechaCreacion  = date('d-m-Y H:i a', strtotime($fecha_sistema));

                $subject = "Actualización del ticket $idTicket Mesa de ayuda";
                $for = "$CorreUsuario";
                Mail::send('email/EmailActualizacion',
                        ['Ticket' => $idTicket,'Asunto' => $Asunto,'Categoria' => $nameCategoria,'Prioridad' => $namePrioridad,
                        'Mensaje' => $comentario, 'NombreReportante' => $NombreUsuario, 'Telefono' => $TelefonoUsuario,
                        'Correo' => $CorreUsuario,'AsignadoA' => $nameAsignado,'Estado' => $nameEstado,'Fecha' => $fechaCreacion],
                        function($msj) use($subject,$for){
                            $msj->from("desarrolladorsenior.tics@gmail.com","Mesa de Ayuda Tics - Servisalud QCL");
                            $msj->subject($subject);
                            $msj->to($for);
                        });

                $verrors = 'Se actualizo con éxito el ticket '.$idTicket;
                return redirect('user/tickets')->with('mensaje', $verrors);
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al crear el ticket');
                // return redirect('user/usuarios')->withErrors(['errors' => $verrors])->withInput();
                return \Redirect::to('user/tickets')->withErrors(['errors' => $verrors])->withInput();
            }
        }else{
            return \Redirect::to('user/tickets')->withErrors(['errors' => $verrors])->withInput();
        }
    }

    public function consultarTickets(){
        $data = Input::all();
        $reglas = array(
            'fechaInicio'   =>  'required',
            'fechaFin'      =>  'required'
        );
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $idTipo         = Input::get('id_tipo');
            $idCategoria    = Input::get('id_categoria');
            $idUsuarioC     = Input::get('id_creado');
            $idUsuarioA     = Input::get('id_asignado');
            $idPrioridad    = Input::get('id_prioridad');
            $idEstado       = Input::get('id_estado');
            $idSede         = Input::get('id_sede');
            $idArea         = Input::get('id_area');
            $finicio        = Input::get('fechaInicio');
            $ffin           = Input::get('fechaFin');
            $consultaReporte = Tickets::Reporte($idTipo,$idCategoria,$idUsuarioC,$idUsuarioA,$idPrioridad,$idEstado,$idSede,$idArea,$finicio,$ffin);

            $resultado = json_decode(json_encode($consultaReporte), true);
            foreach($resultado as &$value) {
                $value['title']             = TicketsUserController::eliminar_tildes_texto($value['title']);
                $value['description']       = TicketsUserController::eliminar_tildes_texto($value['description']);
                $value['dependencia']       = TicketsUserController::eliminar_tildes_texto($value['dependencia']);
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
                    $value['category_id']   =  TicketsUserController::eliminar_tildes_texto($valor->name);
                }
                $id_sede                    = (int)$value['project_id'];
                $nombreSedeS = Sedes::BuscarSedeID($id_sede);
                foreach($nombreSedeS as $valor){
                    $value['project_id']    =  TicketsUserController::eliminar_tildes_texto($valor->name);
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
                $value['name_user']         = TicketsUserController::eliminar_tildes_texto($value['name_user']);
                $id_ticket                  = $value['id'];
                $value['historial']         = null;
                $historialTicket            = Tickets::HistorialTicket($id_ticket);
                $contadorHistorial          = count($historialTicket);
                if($contadorHistorial > 0){
                    foreach($historialTicket as $row){
                        $value['historial'] .= "- ". TicketsUserController::eliminar_tildes_texto($row->observacion)." (".$row->user_id." - ".date('d/m/Y h:i a', strtotime($row->created)).")\n";
                    }
                }else{
                    $value['historial']     = null;
                }
            }

            $aResultado = json_encode($resultado);
            \Session::put('results', $aResultado);

            if($consultaReporte){
                if($aResultado){
                    return \Response::json(['valido'=>'true','results'=>$aResultado]);
                }else{
                    $verrors = array();
                    array_push($verrors, 'No hay datos que mostrar');
                    return \Response::json(['valido'=>'false','errors'=>$verrors]);
                }
            }else{
                $verrors = array();
                array_push($verrors, 'No hay datos que mostrar');
                return \Response::json(['valido'=>'false','errors'=>$verrors]);
            }


            // if(empty($consultaReporte)){
            //     $verrors = array();
            //     array_push($verrors, 'No hay datos que mostrar');
            //     return \Response::json(['valido'=>'false','errors'=>$verrors]);
            // }else if(!empty($aResultado)){
            //     return \Response::json(['valido'=>'true','results'=>$aResultado]);
            // }else{
            //     $verrors = array();
            //     array_push($verrors, 'No hay datos que mostrar');
            //     return \Response::json(['valido'=>'false','errors'=>$verrors]);
            // }
        }else{
            return \Response::json(['valido'=>'false','errors'=>$verrors]);
        }

    }

    public function consultarxTicket(){
        $data = Input::all();
        $reglas = array(
            'ticket'   =>  'required'
        );
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $Ticket         = (int)Input::get('ticket');
            $consultaReporte = Tickets::BuscarTicket($Ticket);

            $resultado = json_decode(json_encode($consultaReporte), true);
            foreach($resultado as &$value) {
                $value['title'] =  TicketsUserController::eliminar_tildes_texto($value['title']);
                $value['description'] =  TicketsUserController::eliminar_tildes_texto($value['description']);
                $value['dependencia'] =  TicketsUserController::eliminar_tildes_texto($value['dependencia']);
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
                    $value['project_id'] = TicketsUserController::eliminar_tildes_texto($valor->name);
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
                        $value['historial'] .= "- ".TicketsUserController::eliminar_tildes_texto($row->observacion)." (".$row->user_id." - ".date('d/m/Y h:i a', strtotime($row->created)).")\n";
                    }
                }else{
                    $value['historial'] = null;
                }
            }

            $aResultado = json_encode($resultado);
            \Session::put('results', $aResultado);
            if(empty($consultaReporte)){
                $verrors = array();
                array_push($verrors, 'No hay datos que mostrar');
                return \Response::json(['valido'=>'false','errors'=>$verrors]);
            }else if(!empty($aResultado)){
                return \Response::json(['valido'=>'true','results'=>$aResultado]);
            }else{
                $verrors = array();
                array_push($verrors, 'No hay datos que mostrar');
                return \Response::json(['valido'=>'false','errors'=>$verrors]);
            }


            // return \Response::json(array('valido'=>'true'));
        }else{
            return \Response::json(['valido'=>'false','errors'=>$verrors]);
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
            array("'", '‘','a€“'),
            array(' ', ' ','-'),
            $cadena
        );

        return $cadena;
    }

}
