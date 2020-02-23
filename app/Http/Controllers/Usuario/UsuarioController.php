<?php

namespace App\Http\Controllers\Usuario;

// use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use App\Models\Admin\Sedes;
use App\Models\Helpdesk\Tickets;
use App\Models\Admin\Roles;
use Illuminate\Support\Facades\Validator;
use App\Models\HelpDesk\Inventario;
use App\Models\Admin\Usuarios;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Illuminate\Support\Facades\Redirect;

class UsuarioController extends Controller
{

    public function inicio()
    {
        $Sede           = (int)Session::get('Sede');
        $Area           = Session::get('NombreArea');
        $Sedes          = Tickets::Sedes();
        $NombreSede     = array();
        $NombreSede[''] = 'Seleccione: ';
        foreach ($Sedes as $row){
            $NombreSede[$row->id] = $row->name;
        }

        $Areas          = Sedes::Areas();
        $NombreArea     = array();
        $NombreArea[''] = 'Seleccione: ';

        $Tipo           = Tickets::ListarTipo();
        $NombreTipo     = array();
        $NombreTipo[''] = 'Seleccione: ';
        foreach ($Tipo as $row){
            $NombreTipo[$row->id] = $row->name;
        }
        $Categoria              = Roles::ListarCategorias();
        $NombreCategoria        = array();
        $NombreCategoria['']    = 'Seleccione: ';
        foreach ($Categoria as $row){
            $NombreCategoria[$row->id] = $row->name;
        }
        $Recurrente             = Tickets::ListarRecurrentes();
        $TicketRecurrente       = array();
        $TicketRecurrente['']   = 'Seleccione: ';
        foreach ($Recurrente as $row){
            $TicketRecurrente[$row->id] = $row->nombre;
        }

        $Tickets        = Tickets::TicketsUsuarioFinal($Sede,$Area);
        $ListadoTickets = array();
        $cont           = 0;
        foreach($Tickets as $value){
            $id_ticket                              = (int)$value->id;
            $ListadoTickets[$cont]['id']            = (int)$value->id;
            $ListadoTickets[$cont]['title']         = $value->title;
            $ListadoTickets[$cont]['description']   = $value->description;
            $ListadoTickets[$cont]['created_at']    = date('d/m/Y h:i A', strtotime($value->created_at));
            if($value->updated_at){
                $ListadoTickets[$cont]['updated_at']   = date('d/m/Y h:i A', strtotime($value->updated_at));
            }else{
                $ListadoTickets[$cont]['updated_at']   = "SIN FECHA DE ACTUALIZACIÓN";
            }
            $ListadoTickets[$cont]['kind_id']       = (int)$value->kind_id;
            $idTipoTicket = (int)$value->kind_id;
            $TipoTicket = Tickets::Tipo($idTipoTicket);
            foreach($TipoTicket as $row){
                $ListadoTickets[$cont]['tipo_ticket'] = $row->name;
            }
            $ListadoTickets[$cont]['user_id']      = (int)$value->user_id;
            $ListadoTickets[$cont]['asigned_id']   = (int)$value->asigned_id;
            $ListadoTickets[$cont]['session_id']   = (int)$value->session_id;
            $idAsignador    =  (int)$value->user_id;
            $idAsignado     =  (int)$value->asigned_id;
            $Asignador  = Usuarios::BuscarNombre($idAsignador);
            $Asignado   = Usuarios::BuscarNombre($idAsignado);
            if($Asignador){
                foreach($Asignador as $row){
                    $ListadoTickets[$cont]['asignado_por'] = strtoupper($row->name);
                }
            }else{
                $ListadoTickets[$cont]['asignado_por'] = 'SIN NOMBRE';
            }
            if($Asignado){
                foreach($Asignado as $row){
                    $ListadoTickets[$cont]['asignado_a'] = strtoupper($row->name);
                }
            }else{
                $ListadoTickets[$cont]['asignado_a'] = 'SIN NOMBRE';
            }
            $ListadoTickets[$cont]['project_id']   = (int)$value->project_id;
            $idSede = (int)$value->project_id;
            $BuscarSede = Sedes::BuscarSedeID($idSede);
            if($BuscarSede){
                foreach($BuscarSede as $row){
                    $ListadoTickets[$cont]['sede'] = strtoupper($row->name);
                }
            }else{
                $ListadoTickets[$cont]['sede'] = 'SEDE POR DETERMINAR';
            }

            $ListadoTickets[$cont]['dependencia']   = (int)$value->dependencia;
            $dependencia = $value->dependencia;
            if($dependencia === null){
                $ListadoTickets[$cont]['area'] = "SIN ÁREA/DEPENDENCIA";
            }else{
                $ListadoTickets[$cont]['area'] = strtoupper($dependencia);
            }
            $ListadoTickets[$cont]['category_id']   = (int)$value->category_id;
            $IdCategoria    = (int)$value->category_id;
            $Categoria      =  Roles::BuscarCategoriaID($IdCategoria);
            foreach($Categoria as $row){
                $ListadoTickets[$cont]['categoria'] = strtoupper($row->name);
            }
            $ListadoTickets[$cont]['priority_id']   = (int)$value->priority_id;
            $IdPrioridad   = (int)$value->priority_id;
            $Prioridad     =  Tickets::BuscarPrioridadID($IdPrioridad);
            foreach($Prioridad as $row){
                if($IdPrioridad === 1){
                    $ListadoTickets[$cont]['prioridad']    = strtoupper($row->name);
                    $ListadoTickets[$cont]['label']        = 'label label-danger';
                }else if($IdPrioridad === 2){
                    $ListadoTickets[$cont]['prioridad']    = strtoupper($row->name);
                    $ListadoTickets[$cont]['label']        = 'label label-warning';
                }else if($IdPrioridad === 3){
                    $ListadoTickets[$cont]['prioridad']    = strtoupper($row->name);
                    $ListadoTickets[$cont]['label']        = 'label label-success';
                }else{
                    $ListadoTickets[$cont]['prioridad'] = 'SIN PRIORIDAD';
                }
            }
            $ListadoTickets[$cont]['status_id']   = (int)$value->status_id;
            $IdEstado   = (int)$value->status_id;
            $Estado     =  Tickets::Estado($IdEstado);
            foreach($Estado as $row){
                $ListadoTickets[$cont]['estado'] = strtoupper($row->name);
            }
            $ListadoTickets[$cont]['name_user']    = strtoupper($value->name_user);
            $ListadoTickets[$cont]['tel_user']     = $value->tel_user;
            $ListadoTickets[$cont]['user_email']   = $value->user_email;
            $ListadoTickets[$cont]['evidencia']    = null;
            $evidenciaTicket = Tickets::EvidenciaTicket($id_ticket);
            $contadorEvidencia = count($evidenciaTicket);
            if($contadorEvidencia > 0){
                $contE = 1;
                foreach($evidenciaTicket as $row){
                    $ListadoTickets[$cont]['evidencia'] .= "<p><a href='../assets/dist/img/evidencias/".$row->nombre_evidencia."' target='_blank' class='btn btn-info'><i class='fa fa-file-archive-o'></i>&nbsp;Anexo Ticket  $id_ticket Nro. ".$contE."</a></p>";
                    $contE++;
                }
            }else{
                $ListadoTickets[$cont]['evidencia'] = null;
            }

            $historialTicket = Tickets::HistorialTicket($id_ticket);
            $contadorHistorial = count($historialTicket);
            $ListadoTickets[$cont]['historial'] = null;
            if($contadorHistorial > 0){
                foreach($historialTicket as $row){
                    $ListadoTickets[$cont]['historial'] .= "- ".$row->observacion." (".$row->user_id." - ".date('d/m/Y h:i a', strtotime($row->created)).")\n";
                }
            }else{
                $ListadoTickets[$cont]['historial'] = null;
            }
            $ListadoTickets[$cont]['id_create_user']   = (int)$value->id_create_user;
            $ListadoTickets[$cont]['h_asigned_id']     = (int)$value->h_asigned_id;
            $idAsignadoh = (int)$value->h_asigned_id;
            $AsignadoH   = Usuarios::BuscarNombre($idAsignadoh);
            foreach($AsignadoH as $row){
                $ListadoTickets[$cont]['asignado_h'] = strtoupper($row->name);
            }
            $cont++;
        }
        $TicketsF        = Tickets::TicketsUsuarioFinalTerminados($Sede,$Area);
        $ListadoTicketsF = array();
        $contF           = 0;
        foreach($TicketsF as $value){
            $id_ticket                              = (int)$value->id;
            $ListadoTicketsF[$contF]['id']            = (int)$value->id;
            $ListadoTicketsF[$contF]['title']         = $value->title;
            $ListadoTicketsF[$contF]['description']   = $value->description;
            $ListadoTicketsF[$contF]['created_at']    = date('d/m/Y h:i A', strtotime($value->created_at));
            if($value->updated_at){
                $ListadoTicketsF[$contF]['updated_at']   = date('d/m/Y h:i A', strtotime($value->updated_at));
            }else{
                $ListadoTicketsF[$contF]['updated_at']   = "SIN FECHA DE ACTUALIZACIÓN";
            }
            $ListadoTicketsF[$contF]['kind_id']       = (int)$value->kind_id;
            $idTipoTicket = (int)$value->kind_id;
            $TipoTicket = Tickets::Tipo($idTipoTicket);
            foreach($TipoTicket as $row){
                $ListadoTicketsF[$contF]['tipo_ticket'] = $row->name;
            }
            $ListadoTicketsF[$contF]['user_id']      = (int)$value->user_id;
            $ListadoTicketsF[$contF]['asigned_id']   = (int)$value->asigned_id;
            $ListadoTicketsF[$contF]['session_id']   = (int)$value->session_id;
            $idAsignador    =  (int)$value->user_id;
            $idAsignado     =  (int)$value->asigned_id;
            $Asignador  = Usuarios::BuscarNombre($idAsignador);
            $Asignado   = Usuarios::BuscarNombre($idAsignado);
            if($Asignador){
                foreach($Asignador as $row){
                    $ListadoTicketsF[$contF]['asignado_por'] = strtoupper($row->name);
                }
            }else{
                $ListadoTicketsF[$contF]['asignado_por'] = 'SIN NOMBRE';
            }
            if($Asignado){
                foreach($Asignado as $row){
                    $ListadoTicketsF[$contF]['asignado_a'] = strtoupper($row->name);
                }
            }else{
                $ListadoTicketsF[$contF]['asignado_a'] = 'SIN NOMBRE';
            }
            $ListadoTicketsF[$contF]['project_id']   = (int)$value->project_id;
            $idSede = (int)$value->project_id;
            $BuscarSede = Sedes::BuscarSedeID($idSede);
            if($BuscarSede){
                foreach($BuscarSede as $row){
                    $ListadoTicketsF[$contF]['sede'] = strtoupper($row->name);
                }
            }else{
                $ListadoTicketsF[$contF]['sede'] = 'SEDE POR DETERMINAR';
            }

            $ListadoTicketsF[$contF]['dependencia']   = (int)$value->dependencia;
            $dependencia = $value->dependencia;
            if($dependencia === null){
                $ListadoTicketsF[$contF]['area'] = "SIN ÁREA/DEPENDENCIA";
            }else{
                $ListadoTicketsF[$contF]['area'] = strtoupper($dependencia);
            }
            $ListadoTicketsF[$contF]['category_id']   = (int)$value->category_id;
            $IdCategoria    = (int)$value->category_id;
            $Categoria      =  Roles::BuscarCategoriaID($IdCategoria);
            foreach($Categoria as $row){
                $ListadoTicketsF[$contF]['categoria'] = strtoupper($row->name);
            }
            $ListadoTicketsF[$contF]['priority_id']   = (int)$value->priority_id;
            $IdPrioridad   = (int)$value->priority_id;
            $Prioridad     =  Tickets::BuscarPrioridadID($IdPrioridad);
            foreach($Prioridad as $row){
                if($IdPrioridad === 1){
                    $ListadoTicketsF[$contF]['prioridad']    = strtoupper($row->name);
                    $ListadoTicketsF[$contF]['label']        = 'label label-danger';
                }else if($IdPrioridad === 2){
                    $ListadoTicketsF[$contF]['prioridad']    = strtoupper($row->name);
                    $ListadoTicketsF[$contF]['label']        = 'label label-warning';
                }else if($IdPrioridad === 3){
                    $ListadoTicketsF[$contF]['prioridad']    = strtoupper($row->name);
                    $ListadoTicketsF[$contF]['label']        = 'label label-success';
                }else{
                    $ListadoTicketsF[$contF]['prioridad'] = 'SIN PRIORIDAD';
                }
            }
            $ListadoTicketsF[$contF]['status_id']   = (int)$value->status_id;
            $IdEstado   = (int)$value->status_id;
            $Estado     =  Tickets::Estado($IdEstado);
            foreach($Estado as $row){
                $ListadoTicketsF[$contF]['estado'] = strtoupper($row->name);
            }
            $ListadoTicketsF[$contF]['name_user']    = strtoupper($value->name_user);
            $ListadoTicketsF[$contF]['tel_user']     = $value->tel_user;
            $ListadoTicketsF[$contF]['user_email']   = $value->user_email;
            $ListadoTicketsF[$contF]['evidencia']    = null;
            $evidenciaTicket = Tickets::EvidenciaTicket($id_ticket);
            $contFadorEvidencia = count($evidenciaTicket);
            if($contFadorEvidencia > 0){
                $contFE = 1;
                foreach($evidenciaTicket as $row){
                    $ListadoTicketsF[$contF]['evidencia'] .= "<p><a href='../assets/dist/img/evidencias/".$row->nombre_evidencia."' target='_blank' class='btn btn-info'><i class='fa fa-file-archive-o'></i>&nbsp;Anexo Ticket  $id_ticket Nro. ".$contFE."</a></p>";
                    $contFE++;
                }
            }else{
                $ListadoTicketsF[$contF]['evidencia'] = null;
            }

            $historialTicket = Tickets::HistorialTicket($id_ticket);
            $contFadorHistorial = count($historialTicket);
            $ListadoTicketsF[$contF]['historial'] = null;
            if($contFadorHistorial > 0){
                foreach($historialTicket as $row){
                    $ListadoTicketsF[$contF]['historial'] .= "- ".$row->observacion." (".$row->user_id." - ".date('d/m/Y h:i a', strtotime($row->created)).")\n";
                }
            }else{
                $ListadoTicketsF[$contF]['historial'] = null;
            }
            $ListadoTicketsF[$contF]['id_create_user']   = (int)$value->id_create_user;
            $ListadoTicketsF[$contF]['h_asigned_id']     = (int)$value->h_asigned_id;
            $idAsignadoh = (int)$value->h_asigned_id;
            $AsignadoH   = Usuarios::BuscarNombre($idAsignadoh);
            foreach($AsignadoH as $row){
                $ListadoTicketsF[$contF]['asignado_h'] = strtoupper($row->name);
            }
            $contF++;
        }
        $contT = 0;
        foreach ($Recurrente as $value){
            $Asuntos[$contT]['id']     = $value->id;
            $Asuntos[$contT]['nombre'] = $value->nombre;
            $contT++;
        }
        return view('UsuarioFinal.crearTicket',['Sedes' => $NombreSede,'Tipo' => $NombreTipo,'TicketRecurrente' => $TicketRecurrente,'Categoria' => $NombreCategoria,
                                                'Areas' => $NombreArea,'Tickets' => $ListadoTickets,'TicketsF' => $ListadoTicketsF,'Asuntos' => $Asuntos]);
    }

    public function nuevaSolicitud(){
        $data = Request::all();
        // dd(Request::get('title'));
        $reglas = array(
            'kind_id'           => 'required',
            'nombre_usuario'    => 'required',
            'description'       => 'required',
            'telefono_usuario'  => 'required',
            'correo_usuario'    => 'required',
            'project_id'        => 'required',
            'area'              => 'required'

        );
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $idTipo             = (int)Request::get('kind_id');

            $Descripcion        = Request::get('description');
            $NombreUsuario      = Request::get('nombre_usuario');
            $TelefonoUsuario    = Request::get('telefono_usuario');
            $CorreUsuario       = Request::get('correo_usuario');
            $IdSede             = (int)Request::get('project_id');
            $IdArea             = (int)Request::get('area');
            $BuscarArea         = Sedes::BuscarAreaId($IdArea);
            foreach($BuscarArea as $row){
                $Area           = $row->name;
            }
            // $Area               = Request::get('dependencia');
            $idAsunto           = Request::get('asunto');
            $NombreAsunto       = Request::get('title');
            if($idAsunto === 1){
                $Prioridad      = 2;
                $Categoria      = 4;
                $Asunto         = Request::get('title');
            }else{
                // $buscardatos = Tickets::ListarRecurrentesId($idAsunto);
                $buscardatos = Tickets::ListarRecurrentesName($NombreAsunto);
                if($buscardatos){
                    foreach($buscardatos as $row){
                        $Prioridad = (int)$row->priority_id;
                        $Categoria = (int)$row->category_id;
                        $Asunto    = Request::get('title');
                    }
                }else{
                    $Prioridad          = 2;
                    $Categoria          = 4;
                    $Asunto             = Request::get('title');
                }
            }

            $AsignadoA          = 44;
            $Estado             = 2;
            $creadoPor          = 31;
            // dd($Prioridad,$Categoria);
            $nameCategoria = 'Mesa de Ayuda';
            $namePrioridad = 'Media';
            $nameEstado = 'Pendiente';
            $nameAsignado = 'Soporte Mesa de Ayuda';
            $emailAsignado = 'soporte.sistemas@cruzrojabogota.org.co';
            $ticketUser = 0;

            $CrearTicket = Tickets::CrearTicket($idTipo,$Asunto,$Descripcion,$NombreUsuario,$TelefonoUsuario,$CorreUsuario,
                                                $IdSede,$Area,$Prioridad,$Categoria,$AsignadoA,$Estado,$creadoPor,$ticketUser);

            if($CrearTicket){
                $buscarUltimo = Tickets::BuscarLastTicket($creadoPor);
                foreach($buscarUltimo as $row){
                    $ticket = $row->id;
                }
                Tickets::CrearTicketAsignado($ticket,$Asunto,$Descripcion,$creadoPor,$AsignadoA);
                $destinationPath = null;
                $filename        = null;
                if (Request::hasFile('evidencia')) {
                    $files = Request::file('evidencia');
                    foreach($files as $file){
                        $destinationPath    = public_path().'/assets/dist/img/evidencias';
                        $extension          = $file->getClientOriginalExtension();
                        $name               = $file->getClientOriginalName();
                        $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                        $nombrearchivo      = UsuarioController::eliminar_tildes($nombrearchivo);
                        $filename           = $nombrearchivo.'_Ticket_'.$ticket.'.'.$extension;
                        $uploadSuccess      = $file->move($destinationPath, $filename);
                        $archivofoto        = file_get_contents($uploadSuccess);
                        $NombreFoto         = $filename;
                        $actualizarEvidencia = Tickets::Evidencia($ticket,$NombreFoto);
                    }
                }

                $BuscarInfoUsuario = Usuarios::BuscarNombre($AsignadoA);
                foreach($BuscarInfoUsuario as $row){
                    $NombreAsignado = $row->name;
                }
                $nombreCreador = 'Soporte';
                $Comentario = "Creación de Ticket y asignado a $NombreAsignado";
                Tickets::HistorialCreacion($ticket,$Comentario,$Estado,$creadoPor,$nombreCreador);
                $fecha_sistema  = date('d-m-Y h:i a');
                $fechaCreacion  = date('d-m-Y h:i a', strtotime($fecha_sistema));

                $subject = "Creación ticket Mesa de ayuda";

                $buscar = strpos($CorreUsuario,';');
                if($buscar === false){
                    $for = "$CorreUsuario";
                }else{
                    $for = array();
                    $for = explode(';',$CorreUsuario);
                }
                // $for = "$CorreUsuario";
                $cco = "$emailAsignado";
                $calificacion = 1;
                if($Estado === 3){
                    $calificacion1 = "<a href='http://192.168.0.125:8080/helpdeskcrcscb/public/calificarTicket?valor=1&idTicket=$ticket'><img src='http://192.168.0.125:8080/helpdesk/public/assets/dist/img/calificacion/excelente.png' width='60' height='60'/></a>";
                    $calificacion2 = "<a href='http://192.168.0.125:8080/helpdeskcrcscb/public/calificarTicket?valor=2&idTicket=$ticket'><img src='http://192.168.0.125:8080/helpdesk/public/assets/dist/img/calificacion/bueno.png' width='60' height='60'/></a>";
                    $calificacion3 = "<a href='http://192.168.0.125:8080/helpdeskcrcscb/public/calificarTicket?valor=1&idTicket=$ticket'><img src='http://192.168.0.125:8080/helpdesk/public/assets/dist/img/calificacion/regular.png' width='60' height='60'/></a>";
                    $calificacion4 = "<a href='http://192.168.0.125:8080/helpdeskcrcscb/public/calificarTicket?valor=1&idTicket=$ticket'><img src='http://192.168.0.125:8080/helpdesk/public/assets/dist/img/calificacion/malo.png' width='60' height='60'/></a>";
                    $calificacion5 = "<a href='http://192.168.0.125:8080/helpdeskcrcscb/public/calificarTicket?valor=1&idTicket=$ticket'><img src='http://192.168.0.125:8080/helpdesk/public/assets/dist/img/calificacion/pesimo.png' width='60' height='60'/></a>";
                    // $calificacion1 = "<a href='http://crcscbmesadeayuda.cruzrojabogota.org.co/calificarTicket?valor=1&idTicket=$ticket'><img src='http://crcscbmesadeayuda.cruzrojabogota.org.co/assets/dist/img/calificacion/excelente.png' width='60' height='60'/></a>";
                    // $calificacion2 = "<a href='http://crcscbmesadeayuda.cruzrojabogota.org.co/calificarTicket?valor=2&idTicket=$ticket'><img src='http://crcscbmesadeayuda.cruzrojabogota.org.co/dist/img/calificacion/bueno.png' width='60' height='60'/></a>";
                    // $calificacion3 = "<a href='http://crcscbmesadeayuda.cruzrojabogota.org.co/calificarTicket?valor=1&idTicket=$ticket'><img src='http://crcscbmesadeayuda.cruzrojabogota.org.co/assets/dist/img/calificacion/regular.png' width='60' height='60'/></a>";
                    // $calificacion4 = "<a href='http://crcscbmesadeayuda.cruzrojabogota.org.co/calificarTicket?valor=1&idTicket=$ticket'><img src='http://crcscbmesadeayuda.cruzrojabogota.org.co/assets/dist/img/calificacion/malo.png' width='60' height='60'/></a>";
                    // $calificacion5 = "<a href='http://crcscbmesadeayuda.cruzrojabogota.org.co/calificarTicket?valor=1&idTicket=$ticket'><img src='http://crcscbmesadeayuda.cruzrojabogota.org.co/assets/dist/img/calificacion/pesimo.png' width='60' height='60'/></a>";
                 }else{
                    $calificacion = 0;
                    $calificacion1 = null;
                    $calificacion2 = null;
                    $calificacion3 = null;
                    $calificacion4 = null;
                    $calificacion5 = null;
                }
                Mail::send('email/EmailCreacion',
                        ['Ticket' => $ticket,'Asunto' => $Asunto,'Categoria' => $nameCategoria,'Prioridad' => $namePrioridad,
                        'Mensaje' => $Descripcion, 'NombreReportante' => $NombreUsuario, 'Telefono' => $TelefonoUsuario,
                        'Correo' => $CorreUsuario,'AsignadoA' => $nameAsignado,'Estado' => $nameEstado,'Fecha' => $fecha_sistema,'Calificacion' => $calificacion,
                        'Calificacion1' => $calificacion1,'Calificacion2' => $calificacion2,'Calificacion3' => $calificacion3,
                        'Calificacion4' => $calificacion4,'Calificacion5' => $calificacion5],
                        function($msj) use($subject,$for,$cco){
                            $msj->from("soporte.sistemas@cruzrojabogota.org.co","Mesa de Ayuda - Tics");
                            $msj->subject($subject);
                            $msj->to($for);
                            $msj->cc($cco);
                        });
                // if(count(Mail::failures()) === 0){
                //     return view('crearTicketMensaje',['Ticket' => $ticket]);
                // }else{
                //     return view('crearTicketMensaje',['Ticket' => $ticket]);
                // }
                if(count(Mail::failures()) === 0){
                    $verrors = 'Se creo con éxito el ticket '.$ticket.'\n Por favor revise la información del ticket que fue enviada al correo registrado para realizar su respectivo seguimiento.';
                    return redirect('usuario/crearTicket')->with('mensaje', $verrors);
                }else{
                    $verrors = 'Se creo con éxito el ticket '.$ticket.', pero no pudo ser enviado el correo al usuario';
                    return redirect('usuario/crearTicket')->with('precaucion', $verrors);
                }
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al crear el ticket');
                return Redirect::to('usuario/crearTicket')->withErrors(['errors' => $verrors])->withRequest();
            }
        }else{
            return Redirect::to('usuario/crearTicket')->withErrors(['errors' => $verrors])->withRequest();
            // return redirect('usuario/crearTicket')->withErrors(['errors' => $verrors])->withRequest();
        }
    }

    public static function eliminar_tildes($nombrearchivo){

        //Codificamos la cadena en formato utf8 en caso de que nos de errores
        // $cadena = utf8_encode($nombrearchivo);
        $cadena = $nombrearchivo;
        //Ahora reemplazamos las letras
        $cadena = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $cadena
        );

        $cadena = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $cadena );

        $cadena = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $cadena );

        $cadena = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $cadena );

        $cadena = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $cadena );

        $cadena = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C'),
            $cadena
        );

        $cadena = str_replace(
            array(' ', '-'),
            array('_', '_'),
            $cadena
        );

        return $cadena;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
