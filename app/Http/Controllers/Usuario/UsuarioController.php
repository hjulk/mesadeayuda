<?php

namespace App\Http\Controllers\Usuario;

use Illuminate\Http\Request;
use App\Http\Controllers\Funciones;
use App\Http\Controllers\Controller;
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
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

class UsuarioController extends Controller
{

    public function inicio(){
        $Sede           = (int)Session::get('Sede');
        $Area           = Session::get('NombreArea');
        $Sedes          = Sedes::SedesA();
        $NombreSede     = array();
        $NombreSede[''] = 'Seleccione: ';
        foreach ($Sedes as $row){
            $NombreSede[$row->id] = Funciones::eliminar_tildes_texto($row->name);
        }

        $Areas          = Sedes::AreasA();
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
            $ListadoTickets[$cont]['title']         = Funciones::eliminar_tildes_texto($value->title);
            $ListadoTickets[$cont]['description']   = Funciones::eliminar_tildes_texto($value->description);
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
                    $ListadoTickets[$cont]['sede'] = Funciones::eliminar_tildes_texto(strtoupper($row->name));
                }
            }else{
                $ListadoTickets[$cont]['sede'] = 'SEDE POR DETERMINAR';
            }

            $ListadoTickets[$cont]['dependencia']   = (int)$value->dependencia;
            $dependencia = $value->dependencia;
            if($dependencia === null){
                $ListadoTickets[$cont]['area'] = "SIN ÁREA/DEPENDENCIA";
            }else{
                $ListadoTickets[$cont]['area'] = Funciones::eliminar_tildes_texto(strtoupper($dependencia));
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
                    $ListadoTickets[$cont]['historial'] .= "- ".Funciones::eliminar_tildes_texto($row->observacion)." (".$row->user_id." - ".date('d/m/Y h:i a', strtotime($row->created)).")\n";
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
            $ListadoTicketsF[$contF]['title']         = Funciones::eliminar_tildes_texto($value->title);
            $ListadoTicketsF[$contF]['description']   = Funciones::eliminar_tildes_texto($value->description);
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
                    $ListadoTicketsF[$contF]['sede'] = Funciones::eliminar_tildes_texto(strtoupper($row->name));
                }
            }else{
                $ListadoTicketsF[$contF]['sede'] = 'SEDE POR DETERMINAR';
            }

            $ListadoTicketsF[$contF]['dependencia']   = (int)$value->dependencia;
            $dependencia = $value->dependencia;
            if($dependencia === null){
                $ListadoTicketsF[$contF]['area'] = "SIN ÁREA/DEPENDENCIA";
            }else{
                $ListadoTicketsF[$contF]['area'] = Funciones::eliminar_tildes_texto(strtoupper($dependencia));
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
                    $ListadoTicketsF[$contF]['historial'] .= "- ".Funciones::eliminar_tildes_texto($row->observacion)." (".$row->user_id." - ".date('d/m/Y h:i a', strtotime($row->created)).")\n";
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

        $Acceso       = array();
        $Acceso['']   = 'Seleccione: ';
        $Acceso[1]    = 'Básico';
        $Acceso[2]    = 'Medio';
        $Acceso[3]    = 'VIP';
        $Acceso[4]    = 'Bloqueo';
        $Acceso[0]    = 'NO';

        return view('UsuarioFinal.crearTicket',['Sedes' => $NombreSede,'Tipo' => $NombreTipo,'TicketRecurrente' => $TicketRecurrente,'Categoria' => $NombreCategoria,
                                                'Areas' => $NombreArea,'Tickets' => $ListadoTickets,'TicketsF' => $ListadoTicketsF,'Asuntos' => $Asuntos,
                                                'Opciones' => $Opciones,'Equipo' => $NombreEquipo,'Acceso' => $Acceso]);
    }

    public function nuevaSolicitud(Request $request){

        $validator = Validator::make($request->all(), [
            'kind_id'           => 'required',
            'nombre_usuario'    => 'required',
            'description'       => 'required',
            'telefono_usuario'  => 'required',
            'correo_usuario'    => 'required|regex:/^.+@.+$/i',
            'project_id'        => 'required',
            'area'              => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('usuario/crearTicket')->withErrors($validator)->withInput();
        }else{
            $CorreoIndex    = Funciones::editar_correo($request->correo_usuario);
            $findme         = '@';
            $pos            = strpos($CorreoIndex, $findme);
            if ($pos === false) {
                $verrors = array();
                array_push($verrors, 'El correo debe contener la estructura correcta @');
                return Redirect::to('usuario/crearTicket')->withErrors(['errors' => $verrors])->withRequest();
            }
            $idTipo             = (int)$request->kind_id;

            $Descripcion        = $request->description;
            $NombreUsuario      = $request->nombre_usuario;
            $TelefonoUsuario    = $request->telefono_usuario;
            $CorreUsuario       = $request->correo_usuario;
            $IdSede             = (int)$request->project_id;
            $IdArea             = (int)$request->area;
            $BuscarArea         = Sedes::BuscarAreaId($IdArea);
            foreach($BuscarArea as $row){
                $Area           = $row->name;
            }
            // $Area               = $request->dependencia');
            $idAsunto           = $request->asunto;
            $NombreAsunto       = $request->title;
            if($idAsunto === 1){
                $Prioridad      = 2;
                $Categoria      = 4;
                $Asunto         = $request->title;
            }else{
                // $buscardatos = Tickets::ListarRecurrentesId($idAsunto);
                $buscardatos = Tickets::ListarRecurrentesName($NombreAsunto);
                if($buscardatos){
                    foreach($buscardatos as $row){
                        $Prioridad = (int)$row->priority_id;
                        $Categoria = (int)$row->category_id;
                        $Asunto    = $request->title;
                    }
                }else{
                    $Prioridad          = 2;
                    $Categoria          = 4;
                    $Asunto             = $request->title;
                }
            }

            $AsignadoA          = 47;
            $AsignadoA1         = null;
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
                                                $IdSede,$Area,$Prioridad,$Categoria,$AsignadoA1,$Estado,$creadoPor,$ticketUser);

            if($CrearTicket){
                $buscarUltimo = Tickets::BuscarLastTicket($creadoPor);
                foreach($buscarUltimo as $row){
                    $ticket = $row->id;
                }
                Tickets::CrearTicketAsignado($ticket,$Asunto,$Descripcion,$creadoPor,$AsignadoA);
                $destinationPath = null;
                $filename        = null;
                if ($request->hasFile('evidencia')) {
                    $files = $request->file('evidencia');
                    foreach($files as $file){
                        $destinationPath    = public_path().'/assets/dist/img/evidencias';
                        $extension          = $file->getClientOriginalExtension();
                        $name               = $file->getClientOriginalName();
                        $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                        $nombrearchivo      = Funciones::eliminar_tildes($nombrearchivo);
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
                    // $calificacion1 = "<a href='http://192.168.0.125:8080/helpdeskcrcscb/public/calificarTicket?valor=1&idTicket=$ticket'><img src='http://192.168.0.125:8080/helpdesk/public/assets/dist/img/calificacion/excelente.png' width='60' height='60'/></a>";
                    // $calificacion2 = "<a href='http://192.168.0.125:8080/helpdeskcrcscb/public/calificarTicket?valor=2&idTicket=$ticket'><img src='http://192.168.0.125:8080/helpdesk/public/assets/dist/img/calificacion/bueno.png' width='60' height='60'/></a>";
                    // $calificacion3 = "<a href='http://192.168.0.125:8080/helpdeskcrcscb/public/calificarTicket?valor=1&idTicket=$ticket'><img src='http://192.168.0.125:8080/helpdesk/public/assets/dist/img/calificacion/regular.png' width='60' height='60'/></a>";
                    // $calificacion4 = "<a href='http://192.168.0.125:8080/helpdeskcrcscb/public/calificarTicket?valor=1&idTicket=$ticket'><img src='http://192.168.0.125:8080/helpdesk/public/assets/dist/img/calificacion/malo.png' width='60' height='60'/></a>";
                    // $calificacion5 = "<a href='http://192.168.0.125:8080/helpdeskcrcscb/public/calificarTicket?valor=1&idTicket=$ticket'><img src='http://192.168.0.125:8080/helpdesk/public/assets/dist/img/calificacion/pesimo.png' width='60' height='60'/></a>";
                    $calificacion1 = "<a href='http://crcscbmesadeayuda.cruzrojabogota.org.co/calificarTicket?valor=5&idTicket=$ticket'><img src='http://crcscbmesadeayuda.cruzrojabogota.org.co/assets/dist/img/calificacion/excelente.png' width='60' height='60'/></a>";
                    $calificacion2 = "<a href='http://crcscbmesadeayuda.cruzrojabogota.org.co/calificarTicket?valor=4&idTicket=$ticket'><img src='http://crcscbmesadeayuda.cruzrojabogota.org.co/dist/img/calificacion/bueno.png' width='60' height='60'/></a>";
                    $calificacion3 = "<a href='http://crcscbmesadeayuda.cruzrojabogota.org.co/calificarTicket?valor=3&idTicket=$ticket'><img src='http://crcscbmesadeayuda.cruzrojabogota.org.co/assets/dist/img/calificacion/regular.png' width='60' height='60'/></a>";
                    $calificacion4 = "<a href='http://crcscbmesadeayuda.cruzrojabogota.org.co/calificarTicket?valor=2&idTicket=$ticket'><img src='http://crcscbmesadeayuda.cruzrojabogota.org.co/assets/dist/img/calificacion/malo.png' width='60' height='60'/></a>";
                    $calificacion5 = "<a href='http://crcscbmesadeayuda.cruzrojabogota.org.co/calificarTicket?valor=1&idTicket=$ticket'><img src='http://crcscbmesadeayuda.cruzrojabogota.org.co/assets/dist/img/calificacion/pesimo.png' width='60' height='60'/></a>";
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
        }
    }

    public static function crearTicketUsuarioFinal(Request $request){
        $validator = Validator::make($request->all(), [
            'nombresT'          => 'required',
            'identificacionT'   => 'required',
            'cargoT'            => 'required',
            'sedeT'             => 'required',
            'correoS'           => 'required|regex:/^.+@.+$/i',
            'jefeT'             => 'required',
            'areaT'             => 'required',
            'fechaIngresoT'     => 'required',
            'cargo_nuevoT'      => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('usuario/crearTicket')->withErrors($validator)->withInput();
        }else{
            $EmailUsuario       = (int)Session::get('Email');
            $Redes              = 0;
            $Infraestructura    = 0;
            $Aplicaciones       = 0;
            $Nombres            = Funciones::eliminar_tildes_texto($request->nombresT);
            $Identificacion     = $request->identificacionT;
            $Cargo              = Funciones::eliminar_tildes_texto($request->cargoT);
            $Sede               = (int)$request->sedeT;
            $BuscarSede         = Sedes::BuscarSedeID($Sede);
            foreach($BuscarSede as $value){
                $NombreSede     = $value->name;
            }
            $Area               = (int)$request->areaT;
            $BuscarArea         = Sedes::BuscarAreaId($Area);
            foreach($BuscarArea as $row){
                $Area           = $row->name;
            }
            $JefeInmediato      = Funciones::eliminar_tildes_texto($request->jefeT);
            $FechaIngreso       = date('Y-m-d H:i:s', strtotime($request->fechaIngresoT));
            $CorreoUsuario      = Funciones::editar_correo($request->correoS);
            $CargoNuevo         = (int)$request->cargo_nuevoT;
            if($CargoNuevo === 1){
                $CargoNuevo_desc = 'Sí';
                $Infraestructura++;
            }else{
                $CargoNuevo_desc = 'No';
                $Infraestructura++;
            }
            if($request->funcionarioT){
                $Funcionario    = $request->funcionarioT;
            }else{
                $Funcionario    = 'SIN FUNCIONARIO';
            }
            if($request->app_85T){
                $App85          = (int)$request->app_85T;
                if($App85 === 1){
                    $Aplicaciones++;
                    $App85_desc = 'Sí';
                }
            }else{
                $App85          = 0;
                $App85_desc     = 'No';
            }
            if($request->app_dinamicaT){
                $AppDinamica    = (int)$request->app_dinamicaT;
                if($AppDinamica === 1){
                    $Aplicaciones++;
                    $AppDinamica_desc = 'Sí';
                }
            }else{
                $AppDinamica        = 0;
                $AppDinamica_desc   = 'No';
            }
            if($request->otro_aplicativoT){
                $OtroAplicativo = $request->otro_aplicativoT;
                $Aplicaciones++;
            }else{
                $OtroAplicativo = 'NINGUNO';
            }
            if($request->cap_85T){
                $Cap85          = (int)$request->cap_85T;
                if($Cap85 === 1){
                    $Aplicaciones++;
                    $Cap85_desc = 'Sí';
                }
            }else{
                $Cap85          = 0;
                $Cap85_desc     = 'No';
            }
            if($request->cap_dinamicaT){
                $CapDinamica    = (int)$request->cap_dinamicaT;
                if($CapDinamica === 1){
                    $Aplicaciones++;
                    $CapDinamica_desc = 'Sí';
                }
            }else{
                $CapDinamica        = 0;
                $CapDinamica_desc = 'No';
            }
            if($request->correo_electronicoT){
                $CorreoElectronico   = (int)$request->correo_electronicoT;
                if($CorreoElectronico === 1){
                    $Infraestructura++;
                    $CorreoElectronico_desc = 'Sí';
                }
            }else{
                $CorreoElectronico   = 0;
                $CorreoElectronico_desc = 'No';
            }
            if($request->equipo_computoT){
                $EquipoComputo      = (int)$request->equipo_computoT;
                if($EquipoComputo === 1){
                    $Infraestructura++;
                    $EquipoComputo_desc = 'Portatil';
                }else if($EquipoComputo === 2){
                    $Infraestructura++;
                    $EquipoComputo_desc = 'Escritorio';
                }
            }else{
                $EquipoComputo      = 0;
                $EquipoComputo_desc = 'No';
            }
            if($request->celularT){
                $Celular            = (int)$request->celularT;
                if($Celular === 1){
                    $Redes++;
                    $Celular_desc = 'Sí';
                }
            }else{
                $Celular            = 0;
                $Celular_desc       = 'No';
            }
            if($request->datosT){
                $Datos              = (int)$request->datosT;
                if($Datos === 1){
                    $Redes++;
                    $Datos_desc = 'Sí';
                }
            }else{
                $Datos              = 0;
                $Datos_desc         = 'No';
            }
            if($request->minutosT){
                $Minutos            = (int)$request->minutosT;
                if($Minutos === 1){
                    $Redes++;
                    $Minutos_desc = 'Sí';
                }
            }else{
                $Minutos            = 0;
                $Minutos_desc       = 'No';
            }
            if($request->extension_telT){
                $ExtensionTel       = (int)$request->extension_telT;
                if($ExtensionTel === 1){
                    $Redes++;
                    $ExtensionTel_desc = 'Sí';
                }
            }else{
                $ExtensionTel       = 0;
                $ExtensionTel_desc  = 'No';
            }
            $Observaciones          = Funciones::eliminar_tildes_texto($request->observacionesT);
            $Estado                 = 1;
            $Prioridad              = 2;
            $nombrePrioridad        = Tickets::Prioridad($Prioridad);
            $nombreEstado           = Tickets::Estado($Estado);
            foreach($nombrePrioridad as $row){
                $namePrioridad = $row->name;
            }
            foreach($nombreEstado as $row){
                $nameEstado = $row->name;
            }
            $creadoPor          = 47;
            if($request->usuario_dominio){
                $UsuarioDominio     = (int)$request->usuario_dominio;
                if($UsuarioDominio === 1){
                    $Infraestructura++;
                    $UsuarioDominio_desc = 'Sí';
                }
            }else{
                $UsuarioDominio     = 0;
                $UsuarioDominio_desc = 'No';
            }
            if($request->correo_funcionario){
                $CorreoFuncionario      = Funciones::editar_correo($request->correo_funcionario);
            }else{
                $CorreoFuncionario      = 'SIN CORREO';
            }
            if($request->acceso_carpeta){
                $AccesoCarpeta          = $request->acceso_carpeta;
            }else{
                $AccesoCarpeta          = 'SIN CARPETA';
            }
            if($request->conectividad){
                $Conectividad       = (int)$request->conectividad;
                if($Conectividad === 1){
                    $Redes++;
                    $Conectividad_desc = 'Sí';
                }
            }else{
                $Conectividad       = 0;
                $Conectividad_desc  = 'No';
            }
            if($request->acceso_internet){
                $AccesoInternet     = (int)$request->acceso_internet;
                switch($AccesoInternet){
                    Case 1  :   $AccesoInternet_desc = 'Básico';
                                $Redes++;
                                break;
                    Case 2  :   $AccesoInternet_desc = 'Medio';
                                $Redes++;
                                break;
                    Case 3  :   $AccesoInternet_desc = 'VIP';
                                $Redes++;
                                break;
                    Case 4  :   $AccesoInternet_desc = 'Bloqueo';
                                $Redes++;
                                break;
                    Case 0  :   $AccesoInternet_desc = 'NO';
                                $Redes++;
                                break;
                }
            }else{
                $AccesoInternet         = 0;
                $AccesoInternet_desc    = 'No';
            }
            $TicketUsuario = Tickets::CrearTicketUsuario($Nombres,$Identificacion,$Cargo,$Sede,$Area,$JefeInmediato,$FechaIngreso,$CorreoUsuario,$CargoNuevo,$Funcionario,$UsuarioDominio,$CorreoElectronico,$CorreoFuncionario,$EquipoComputo,$AccesoCarpeta,$Celular,$Datos,$Minutos,$ExtensionTel,$Conectividad,$AccesoInternet,$App85,$AppDinamica,$OtroAplicativo,$Cap85,$CapDinamica,$Observaciones,$Estado,$Prioridad,$creadoPor);
            if($TicketUsuario){
                $buscarUltimo = Tickets::BuscarLastTicketUsuario($creadoPor);
                foreach($buscarUltimo as $row){
                    $ticketUser = $row->id;
                }
                $Asunto             = 'Creación de Usuario '.$Nombres;
                $NombreUsuario      = 'Gestión Humana';
                $TelefonoUsuario    = 'Ext. 619';
                $Tickets            = "Creación";
                $emailAsignado      = "";
                $idTipo             = 2;

                $Descripcion        = "Nombres y Apellidos: $Nombres\n
                                    Identificación: $Identificacion\n
                                    Cargo: $Cargo\n
                                    Sede: $NombreSede\n
                                    Área: $Area\n
                                    Jefe Inmediato: $JefeInmediato\n
                                    Fecha Ingreso: $FechaIngreso\n";

                if($Redes > 0){
                    $Categoria = 3;
                    $IdUsuario = null;
                    $Descripcion .= "Requiere Celular Coorporativo: $Celular_desc\n
                                    Requiere Datos: $Datos_desc\n
                                    Extensión Telefónica: $ExtensionTel_desc\n
                                    Conectividad VPN: $Conectividad_desc\n
                                    Nivel Internet: $AccesoInternet_desc\n
                                    Observación: $Observaciones";
                    $CrearTicket  = Tickets::CrearTicket($idTipo,$Asunto,$Descripcion,$NombreUsuario,$TelefonoUsuario,$CorreoUsuario,
                                                $Sede,$Area,$Prioridad,$Categoria,$IdUsuario,$Estado,$creadoPor,$ticketUser);
                    $buscarUltimo = Tickets::BuscarLastTicket($creadoPor);
                    foreach($buscarUltimo as $row){
                        $idticket = $row->id;
                    }
                    $Tickets    .= "Ticket Redes y Comunicaciones: $idticket,";
                    $emailAsignado = 'soporte.sistemas@cruzrojabogota.org.co';
                }
                if($Infraestructura > 0){
                    $Categoria = 2;
                    $IdUsuario = null;
                    $Descripcion .= "Cargo Nuevo: $CargoNuevo_desc\n
                                    Usuario Dominio: $UsuarioDominio_desc\n
                                    Funcionario que Reemplaza: $Funcionario\n
                                    Correo Funcionario que reemplaza: $CorreoFuncionario\n
                                    Requiere Correo Electronico: $CorreoElectronico_desc\n
                                    Requiere Equipo de Computo: $EquipoComputo_desc\n
                                    Carpeta Compartida: $AccesoCarpeta\n
                                    Observación: $Observaciones";
                    $CrearTicket  = Tickets::CrearTicket($idTipo,$Asunto,$Descripcion,$NombreUsuario,$TelefonoUsuario,$CorreoUsuario,
                                                $Sede,$Area,$Prioridad,$Categoria,$IdUsuario,$Estado,$creadoPor,$ticketUser);
                    $buscarUltimo = Tickets::BuscarLastTicket($creadoPor);
                    foreach($buscarUltimo as $row){
                        $idticket = $row->id;
                    }
                    $Tickets    .= "Ticket Infraestructura: $idticket,";
                    $emailAsignado = 'soporte.sistemas@cruzrojabogota.org.co';
                }
                if($Aplicaciones > 0){
                    $Categoria = 4;
                    $IdUsuario = null;
                    $Descripcion .= "Servinté: $App85_desc\n
                                    Dínamica: $AppDinamica_desc\n
                                    Otro Aplicativo: $OtroAplicativo\n
                                    Capacitación Servinté: $Cap85_desc\n
                                    Capacitación Dinamica: $CapDinamica_desc\n
                                    Observación: $Observaciones";
                                    $CrearTicket  = Tickets::CrearTicket($idTipo,$Asunto,$Descripcion,$NombreUsuario,$TelefonoUsuario,$CorreoUsuario,
                                                $Sede,$Area,$Prioridad,$Categoria,$IdUsuario,$Estado,$creadoPor,$ticketUser);
                    $buscarUltimo = Tickets::BuscarLastTicket($creadoPor);
                    foreach($buscarUltimo as $row){
                        $idticket = $row->id;
                    }
                    $Tickets    .= "Ticket Aplicaciones: $idticket,";
                    $emailAsignado = 'soporte.sistemas@cruzrojabogota.org.co';
                }
                $DescriptionT = "<b>Nombres y Apellidos:</b> $Nombres<br>
                                <b>Identificación:</b> $Identificacion<br>
                                <b>Cargo:</b> $Cargo<br>
                                <b>Sede:</b> $NombreSede<br>
                                <b>Área:</b> $Area<br>
                                <b>Jefe Inmediato:</b> $JefeInmediato<br>
                                <b>Fecha Ingreso:</b> $FechaIngreso<br>
                                <b>Cargo Nuevo:</b> $CargoNuevo_desc<br>
                                <b>Funcionario que Reemplaza:</b> $Funcionario<br>
                                <b>Correo Funcionario que reemplaza:</b> $CorreoFuncionario<br>
                                <b>Requiere Correo Electronico:</b> $CorreoElectronico_desc<br>
                                <b>Requiere Equipo de Computo:</b> $EquipoComputo_desc<br>
                                <b>Requiere Celular Coorporativo:</b> $Celular_desc<br>
                                <b>Requiere Datos:</b> $Datos_desc<br>
                                <b>Extensión Telefónica:</b> $ExtensionTel_desc<br>
                                <b>Conectividad VPN:</b> $Conectividad_desc<br>
                                <b>Nivel Internet:</b> $AccesoInternet_desc<br>
                                <b>Servinté:</b> $App85_desc<br>
                                <b>Dínamica:</b> $AppDinamica_desc<br>
                                <b>Otro Aplicativo:</b> $OtroAplicativo<br>
                                <b>Capacitación 8.5:</b> $Cap85_desc<br>
                                <b>Capacitación Dinamica:</b> $CapDinamica_desc";
                $fecha_sistema  = date('d-m-Y h:i a');
                $fechaCreacion  = date('d-m-Y h:i a', strtotime($fecha_sistema));

                $subject = "Creación ticket Mesa de ayuda";

                $buscar = strpos($CorreoUsuario,';');
                if($buscar === false){
                    $for = "$CorreoUsuario";
                }else{
                    $for = array();
                    $for = explode(';',$CorreoUsuario);
                }
                $copia = strpos($emailAsignado,';');
                if($copia === false){
                    $cco = "$emailAsignado";
                }else{
                    $cco = array();
                    $cco = explode(';',$emailAsignado);
                }
                $calificacion = 0;
                $calificacion1 = null;
                $calificacion2 = null;
                $calificacion3 = null;
                $calificacion4 = null;
                $calificacion5 = null;
                $correoEmail = 'seleccion@cruzrojabogota.org.co;'.$CorreoUsuario;
                Mail::send('email/EmailCreacion',
                        ['Ticket' => $Tickets,'Asunto' => $Asunto,'Categoria' => 'Mesa de Ayuda','Prioridad' => $namePrioridad,
                        'Mensaje' => $DescriptionT, 'NombreReportante' => $NombreUsuario, 'Telefono' => $TelefonoUsuario,
                        'Correo' => $correoEmail,'AsignadoA' => 'Mesa de Ayuda','Estado' => $nameEstado,'Fecha' => $fecha_sistema,'Calificacion' => $calificacion,
                        'Calificacion1' => $calificacion1,'Calificacion2' => $calificacion2,'Calificacion3' => $calificacion3,
                        'Calificacion4' => $calificacion4,'Calificacion5' => $calificacion5],
                        function($msj) use($subject,$for,$cco){
                            $msj->from("soporte.sistemas@cruzrojabogota.org.co","Mesa de Ayuda - Tics");
                            $msj->subject($subject);
                            $msj->to($for);
                            $msj->cc($cco);
                        });
                if(count(Mail::failures()) === 0){
                    $verrors = 'Se creo con éxito el ticket '.$idticket;
                    return redirect('usuario/crearTicket')->with('mensaje', $verrors);
                }else{
                    $verrors = 'Se creo con éxito el ticket '.$idticket.', pero no pudo ser enviado el correo al usuario';
                    return redirect('usuario/crearTicket')->with('precaucion', $verrors);
                }
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al crear el ticket');
                return Redirect::to('usuario/crearTicket')->withErrors(['errors' => $verrors])->withRequest();
            }
        }
    }

}
