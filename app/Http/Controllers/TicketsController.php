<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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


class TicketsController extends Controller{

    public function crearTicket(Request $request){
        $creadoPor          = (int)Session::get('IdUsuario');
        $buscarUsuario = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = Funciones::BuscarURL($Administrador);
        $seleccionado = (int)$request->id_usuario;
        if($seleccionado === ''){
            $verrors = array();
            array_push($verrors, 'Debe seleccionar un usuario a asignar el ticket');
            return Redirect::to($url.'/tickets')->withErrors(['errors' => $verrors])->withRequest();
        }
        $CorreoIndex   = Funciones::editar_correo($request->correo_usuario);
        $findme   = '@';
        $pos = strpos($CorreoIndex, $findme);
        if ($pos === false) {
            $verrors = array();
            array_push($verrors, 'El correo debe contener la estructura correcta @');
            return Redirect::to($url.'/tickets')->withErrors(['errors' => $verrors])->withRequest();
        }

        $validator = Validator::make($request->all(), [
            'kind_id'           =>  'required',
            'title'             =>  'required',
            'description'       =>  'required',
            'nombre_usuario'    =>  'required',
            'telefono_usuario'  =>  'required',
            'correo_usuario'    =>  'required|regex:/^.+@.+$/i',
            'project_id'        =>  'required',
            // 'dependencia'       =>  'required',
            'priority_id'       =>  'required',
            'id_categoria'      =>  'required',
            'id_usuario'        =>  'required',
            'id_estado'         =>  'required',
            'evidencia'         =>  'max:5120',
            'area'              =>  'required'
        ]);

        if ($validator->fails()) {
            return redirect($url.'/tickets')->withErrors($validator)->withInput();
        }else{

            $idTipo             = (int)$request->kind_id;
            $Asunto             = Funciones::eliminar_tildes_texto($request->title);
            $Descripcion        = Funciones::eliminar_tildes_texto($request->description);
            $NombreUsuario      = Funciones::eliminar_tildes_texto($request->nombre_usuario);
            $TelefonoUsuario    = $request->telefono_usuario;
            $CorreUsuario       = Funciones::editar_correo($request->correo_usuario);
            $IdSede             = (int)$request->project_id;
            $IdArea             = (int)$request->area;
            $BuscarArea         = Sedes::BuscarAreaId($IdArea);
            foreach($BuscarArea as $row){
                $Area           = $row->name;
            }
            // $Area               = $request->dependencia');
            $Prioridad          = (int)$request->priority_id;
            $Categoria          = (int)$request->id_categoria;
            $AsignadoA          = (int)$request->id_usuario;
            $Estado             = (int)$request->id_estado;
            $creadoPor          = (int)$request->IdUsuario;

            $nombreCategoria    = Tickets::Categoria($Categoria);
            $nombrePrioridad    = Tickets::Prioridad($Prioridad);
            $nombreEstado       = Tickets::Estado($Estado);
            $nombreAsignado     = Usuarios::BuscarNombre($AsignadoA);

            foreach($nombreCategoria as $row){
                $nameCategoria = $row->name;
            }
            foreach($nombrePrioridad as $row){
                $namePrioridad = $row->name;
            }
            foreach($nombreEstado as $row){
                $nameEstado = $row->name;
            }
            foreach($nombreAsignado as $row){
                $nameAsignado = $row->name;
                $emailAsignado = $row->email;
            }
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
                $nombreCreador = Session::get('NombreUsuario');
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
                    // $calificacion1 = "<a href='http://192.168.0.125:8080/mesadeayuda/public/calificarTicket?valor=1&idTicket=$ticket'><img src='http://192.168.0.125:8080/helpdesk/public/assets/dist/img/calificacion/excelente.png' width='60' height='60'/></a>";
                    // $calificacion2 = "<a href='http://192.168.0.125:8080/mesadeayuda/public/calificarTicket?valor=2&idTicket=$ticket'><img src='http://192.168.0.125:8080/helpdesk/public/assets/dist/img/calificacion/bueno.png' width='60' height='60'/></a>";
                    // $calificacion3 = "<a href='http://192.168.0.125:8080/mesadeayuda/public/calificarTicket?valor=1&idTicket=$ticket'><img src='http://192.168.0.125:8080/helpdesk/public/assets/dist/img/calificacion/regular.png' width='60' height='60'/></a>";
                    // $calificacion4 = "<a href='http://192.168.0.125:8080/mesadeayuda/public/calificarTicket?valor=1&idTicket=$ticket'><img src='http://192.168.0.125:8080/helpdesk/public/assets/dist/img/calificacion/malo.png' width='60' height='60'/></a>";
                    // $calificacion5 = "<a href='http://192.168.0.125:8080/mesadeayuda/public/calificarTicket?valor=1&idTicket=$ticket'><img src='http://192.168.0.125:8080/helpdesk/public/assets/dist/img/calificacion/pesimo.png' width='60' height='60'/></a>";
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
                if(count(Mail::failures()) === 0){
                    $verrors = 'Se creo con éxito el ticket '.$ticket;
                    return redirect($url.'/tickets')->with('mensaje', $verrors);
                }else{
                    $verrors = 'Se creo con éxito el ticket '.$ticket.', pero no pudo ser enviado el correo al usuario';
                    return redirect($url.'/tickets')->with('precaucion', $verrors);
                }

            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al crear el ticket');
                return Redirect::to($url.'/tickets')->withErrors(['errors' => $verrors])->withRequest();
            }
        }
    }

    public function actualizarTicket(Request $request){
        $creadoPor          = (int)Session::get('IdUsuario');
        $buscarUsuario      = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador  = (int)$value->rol_id;
        }
        $url = Funciones::BuscarURL($Administrador);
        $seleccionado = (int)$request->id_usuarioupd;
        if($seleccionado === 0){
            $verrors = array();
            array_push($verrors, 'Debe seleccionar un usuario a asignar el ticket');
            return Redirect::to($url.'/tickets')->withErrors(['errors' => $verrors])->withRequest();
        }
        $validator = Validator::make($request->all(), [
            'id_prioridad_upd'      =>  'required',
            'id_categoriaupd'       =>  'required',
            'id_usuarioupd'         =>  'required',
            'id_estado_upd'         =>  'required',
            'comentario'            =>  'required',
            'evidencia_upd'         =>  'max:5120',
            'correo_usuario_upd'    =>  'required|regex:/^.+@.+$/i'
        ]);

        if ($validator->fails()) {
            return redirect($url.'/tickets')->withErrors($validator)->withInput();
        }else{

            $idTicket           = (int)$request->idT;
            $idTipo             = (int)$request->id_tipo_upd;
            $Asunto             = Funciones::eliminar_tildes_texto($request->asunto_upd);
            $Descripcion        = Funciones::eliminar_tildes_texto($request->descripcion_upd);
            $NombreUsuario      = Funciones::eliminar_tildes_texto($request->nombre_usuario_upd);
            $TelefonoUsuario    = $request->telefono_usuario_upd;
            $CorreUsuario       = Funciones::editar_correo($request->correo_usuario_upd);
            $IdSede             = (int)$request->id_sede_upd;
            $IdArea             = (int)$request->dependencia_upd;
            $BuscarArea         = Sedes::BuscarAreaId($IdArea);
            foreach($BuscarArea as $row){
                $Area           = $row->name;
            }
            $Prioridad          = (int)$request->id_prioridad_upd;
            $Categoria          = (int)$request->id_categoriaupd;
            $AsignadoA          = (int)$request->id_usuarioupd;
            $Estado             = (int)$request->id_estado_upd;
            $creadoPor          = (int)Session::get('IdUsuario');
            $comentario         = $request->comentario;

            $nombreCategoria    = Tickets::Categoria($Categoria);
            $nombrePrioridad    = Tickets::Prioridad($Prioridad);
            $nombreEstado       = Tickets::Estado($Estado);
            $nombreAsignado     = Usuarios::BuscarNombre($AsignadoA);

            foreach($nombreCategoria as $row){
                $nameCategoria = $row->name;
            }
            foreach($nombrePrioridad as $row){
                $namePrioridad = $row->name;
            }
            foreach($nombreEstado as $row){
                $nameEstado = $row->name;
            }
            foreach($nombreAsignado as $row){
                $nameAsignado = $row->name;
                $emailAsignado = $row->email;
            }

            $actualizarTicket   = Tickets::ActualizarTicket($idTicket,$idTipo,$Asunto,$Descripcion,$NombreUsuario,$TelefonoUsuario,$CorreUsuario,
                                                            $IdSede,$Area,$Prioridad,$Categoria,$AsignadoA,$Estado,$creadoPor,$comentario);
            if($actualizarTicket){

                $destinationPath = null;
                $filename        = null;
                if ($request->hasFile('evidencia_upd')) {
                    $files = $request->file('evidencia_upd');
                    foreach($files as $file){
                        $destinationPath    = public_path().'/assets/dist/img/evidencias';
                        $extension          = $file->getClientOriginalExtension();
                        $name               = $file->getClientOriginalName();
                        $nombrearchivo      = pathinfo($name, PATHINFO_FILENAME);
                        $nombrearchivo      = Funciones::eliminar_tildes($nombrearchivo);
                        $filename           = $nombrearchivo.'_Ticket_'.$idTicket.'.'.$extension;
                        $uploadSuccess      = $file->move($destinationPath, $filename);
                        $archivofoto        = file_get_contents($uploadSuccess);
                        $NombreFoto         = $filename;
                        $actualizarEvidencia = Tickets::Evidencia($idTicket,$NombreFoto);
                    }
                }

                $fecha_sistema  = date('d-m-Y h:i a');
                $fechaCreacion  = date('d-m-Y H:i a', strtotime($fecha_sistema));

                $subject = "Actualización del ticket $idTicket Mesa de ayuda";
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
                    // $calificacion1 = "<a href='http://192.168.0.125:8080/mesadeayuda/public/calificarTicket?valor=5&idTicket=$idTicket'><img src='http://192.168.0.125:8080/helpdesk/public/assets/dist/img/calificacion/excelente.png' width='60' height='60'/></a>";
                    // $calificacion2 = "<a href='http://192.168.0.125:8080/mesadeayuda/public/calificarTicket?valor=4&idTicket=$idTicket'><img src='http://192.168.0.125:8080/helpdesk/public/assets/dist/img/calificacion/bueno.png' width='60' height='60'/></a>";
                    // $calificacion3 = "<a href='http://192.168.0.125:8080/mesadeayuda/public/calificarTicket?valor=3&idTicket=$idTicket'><img src='http://192.168.0.125:8080/helpdesk/public/assets/dist/img/calificacion/regular.png' width='60' height='60'/></a>";
                    // $calificacion4 = "<a href='http://192.168.0.125:8080/mesadeayuda/public/calificarTicket?valor=2&idTicket=$idTicket'><img src='http://192.168.0.125:8080/helpdesk/public/assets/dist/img/calificacion/malo.png' width='60' height='60'/></a>";
                    // $calificacion5 = "<a href='http://192.168.0.125:8080/mesadeayuda/public/calificarTicket?valor=1&idTicket=$idTicket'><img src='http://192.168.0.125:8080/helpdesk/public/assets/dist/img/calificacion/pesimo.png' width='60' height='60'/></a>";
                    $calificacion1 = "<a href='http://crcscbmesadeayuda.cruzrojabogota.org.co/calificarTicket?valor=5&idTicket=$idTicket'><img src='http://crcscbmesadeayuda.cruzrojabogota.org.co/assets/dist/img/calificacion/excelente.png' width='60' height='60'/></a>";
                    $calificacion2 = "<a href='http://crcscbmesadeayuda.cruzrojabogota.org.co/calificarTicket?valor=4&idTicket=$idTicket'><img src='http://crcscbmesadeayuda.cruzrojabogota.org.co/dist/img/calificacion/bueno.png' width='60' height='60'/></a>";
                    $calificacion3 = "<a href='http://crcscbmesadeayuda.cruzrojabogota.org.co/calificarTicket?valor=3&idTicket=$idTicket'><img src='http://crcscbmesadeayuda.cruzrojabogota.org.co/assets/dist/img/calificacion/regular.png' width='60' height='60'/></a>";
                    $calificacion4 = "<a href='http://crcscbmesadeayuda.cruzrojabogota.org.co/calificarTicket?valor=2&idTicket=$idTicket'><img src='http://crcscbmesadeayuda.cruzrojabogota.org.co/assets/dist/img/calificacion/malo.png' width='60' height='60'/></a>";
                    $calificacion5 = "<a href='http://crcscbmesadeayuda.cruzrojabogota.org.co/calificarTicket?valor=1&idTicket=$idTicket'><img src='http://crcscbmesadeayuda.cruzrojabogota.org.co/assets/dist/img/calificacion/pesimo.png' width='60' height='60'/></a>";
                }else{
                    $calificacion = 0;
                    $calificacion1 = null;
                    $calificacion2 = null;
                    $calificacion3 = null;
                    $calificacion4 = null;
                    $calificacion5 = null;
                }

                Mail::send('email/EmailActualizacion',
                        ['Ticket' => $idTicket,'Asunto' => $Asunto,'Categoria' => $nameCategoria,'Prioridad' => $namePrioridad,
                        'Mensaje' => $comentario, 'NombreReportante' => $NombreUsuario, 'Telefono' => $TelefonoUsuario,
                        'Correo' => $CorreUsuario,'AsignadoA' => $nameAsignado,'Estado' => $nameEstado,'Fecha' => $fecha_sistema,'Calificacion' => $calificacion,
                        'Calificacion1' => $calificacion1,'Calificacion2' => $calificacion2,'Calificacion3' => $calificacion3,
                        'Calificacion4' => $calificacion4,'Calificacion5' => $calificacion5],

                        function($msj) use($subject,$for,$cco){
                            $msj->from("soporte.sistemas@cruzrojabogota.org.co","Mesa de Ayuda - Tics");
                            $msj->subject($subject);
                            $msj->to($for);
                            $msj->cc($cco);
                        });

                if(count(Mail::failures()) === 0){
                    $verrors = 'Se actualizo con éxito el ticket '.$idTicket;
                    return redirect($url.'/tickets')->with('mensaje', $verrors);
                }else{
                    $verrors = 'Se actualizo con éxito el ticket '.$idTicket.', pero no pudo ser enviado el correo al usuario';
                    return redirect($url.'/tickets')->with('precaucion', $verrors);
                }
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al actualizar el ticket');
                return Redirect::to($url.'/tickets')->withErrors(['errors' => $verrors])->withRequest();
            }
        }
    }

    public function reporteTickets(){
        $Categoria  = Usuarios::Categoria();
        $NombreCategoria = array();
        $NombreCategoria[''] = 'Seleccione: ';
        foreach ($Categoria as $row){
            $NombreCategoria[$row->id] = $row->nombre;
        }
        $Tipo  = Tickets::ListarTipo();
        $NombreTipo = array();
        $NombreTipo[''] = 'Seleccione: ';
        foreach ($Tipo as $row){
            $NombreTipo[$row->id] = $row->nombre;
        }
        $Prioridad  = Tickets::ListarPrioridad();
        $NombrePrioridad = array();
        $NombrePrioridad[''] = 'Seleccione: ';
        foreach ($Prioridad as $row){
            $NombrePrioridad[$row->id] = $row->nombre;
        }
        $Zonas  = Sedes::Zonas();
        $NombreZona = array();
        $NombreZona[''] = 'Seleccione: ';
        foreach ($Zonas as $row){
            $NombreZona[$row->id] = $row->nombre;
        }

        $NombreUsuario = array();
        $NombreUsuario[''] = 'Seleccione: ';
        $Usuarios = Usuarios::ListarUsuarios();
        foreach ($Usuarios as $row){
            $NombreUsuario[$row->id] = $row->nombre;
        }
        $NombreSede = array();
        $NombreSede[''] = 'Seleccione: ';
        $Sedes  = Sedes::Sedes();
        $NombreSedes = array();
        $NombreSedes[''] = 'Seleccione: ';
        foreach ($Sedes as $row){
            $NombreSedes[$row->id] = $row->nombre;
        }
        $NombreArea = array();
        $NombreArea[''] = 'Seleccione: ';
        $Areas  = Sedes::Areas();
        $NombreAreas = array();
        $NombreAreas[''] = 'Seleccione: ';
        foreach ($Areas as $row){
            $NombreAreas[$row->id] = $row->nombre;
        }

        $Estado  = Tickets::ListarEstadoUpd();
        $NombreEstado = array();
        // $NombreEstado[0] = 'Seleccione: ';
        foreach ($Estado as $row){
            $NombreEstado[$row->id] = $row->name;
        }

        $Zonas = Sedes::Zonas();
        $NombreZona = array();
        $NombreZona[''] = 'Seleccione: ';
        foreach ($Zonas as $row){
            $NombreZona[$row->id] = $row->nombre;
        }
        return view('tickets.reporte',['Tipo' => $NombreTipo,'Estado' => $NombreEstado,'Categoria' => $NombreCategoria,
                                        'Usuario' => $NombreUsuario,'Prioridad' => $NombrePrioridad,'Zona' => $NombreZona,
                                        'Sede' => $NombreSedes, 'Area' =>$NombreAreas,'FechaInicio' => null,'FechaFin' => null]);
    }

    public function consultarTickets(Request $request){
        $validator = Validator::make($request->all(), [
            'fechaInicio'   =>  'required',
            'fechaFin'      =>  'required'
        ]);

        if ($validator->fails()) {
            return redirect('/reporteTickets')->withErrors($validator)->withInput();
        }else{
            $idTipo         = $request->id_tipo;
            $idCategoria    = $request->id_categoria;
            $idUsuarioC     = $request->id_creado;
            $idUsuarioA     = $request->id_asignado;
            $idPrioridad    = $request->id_prioridad;
            if ($request->id_estado != null){
                $idEstado = implode(',', $request->id_estado);
            }else{
                $idEstado = null;
            }
            $idZona         = $request->id_zona;
            $idSede         = $request->id_sede;
            $idArea         = $request->id_area;
            $finicio        = $request->fechaInicio;
            $ffin           = $request->fechaFin;
            $consultaReporte = Tickets::Reporte($idTipo,$idCategoria,$idUsuarioC,$idUsuarioA,$idPrioridad,$idEstado,$idZona,$idSede,$idArea,$finicio,$ffin);

            $resultado = json_decode(json_encode($consultaReporte), true);
            foreach($resultado as &$value) {
                $value['created_at'] = date('d/m/Y h:i A', strtotime($value['created_at']));
                if($value['updated_at']){
                    $value['updated_at'] = date('d/m/Y h:i A', strtotime($value['updated_at']));
                }else{
                    $value['updated_at'] = 'SIN ACTUALIZACIÓN';
                }
                $id_tipo = $value['id_tipo'];
                $nombreTipo = Tickets::Tipo($id_tipo);
                foreach($nombreTipo as $valor){
                    $value['id_tipo'] = strtoupper($valor->nombre);
                }
                $id_categoria = $value['id_categoria'];
                $nombreCategoria = Tickets::Categoria($id_categoria);
                foreach($nombreCategoria as $valor){
                    $value['id_categoria'] = strtoupper($valor->nombre);
                }
                $id_zona = $value['id_zona'];
                $nombreZonaS = Sedes::BuscarZonaID($id_zona);
                foreach($nombreZonaS as $valor){
                    $value['id_zona'] = strtoupper($valor->nombre);
                }
                $id_sede = $value['id_sede'];
                $nombreSedeS = Sedes::BuscarSedeID($id_sede);
                foreach($nombreSedeS as $valor){
                    $value['id_sede'] = strtoupper($valor->nombre);
                }
                $id_area = $value['id_area'];
                $nombreAreaS = Sedes::BuscarAreaID($id_area);
                foreach($nombreAreaS as $valor){
                    $value['id_area'] = strtoupper($valor->nombre);
                }
                $id_prioridad = $value['id_prioridad'];
                $nombrePrioridad = Tickets::Prioridad($id_prioridad);
                foreach($nombrePrioridad as $valor){
                    switch($id_prioridad){
                        Case 1: $value['id_prioridad'] = "<span class='label label-danger' style='font-size:13px;'><b></b>".strtoupper($valor->nombre)."</span>";
                                break;
                        Case 2: $value['id_prioridad'] = "<span class='label label-warning' style='font-size:13px;'><b></b>".strtoupper($valor->nombre)."</span>";
                                break;
                        Case 3: $value['id_prioridad'] = "<span class='label label-success' style='font-size:13px;'><b></b>".strtoupper($valor->nombre)."</span>";
                                break;
                    }
                    // $value['id_prioridad'] = strtoupper($valor->nombre);
                }
                $id_estado = $value['id_estado'];
                $nombreEstado = Tickets::Estado($id_estado);
                foreach($nombreEstado as $valor){
                    $value['id_estado'] = strtoupper($valor->name);
                }
                $creado = $value['creado_por'];
                $buscarUsuario = Usuarios::BuscarNombre($creado);
                foreach($buscarUsuario as $valor){
                    $value['creado_por'] = strtoupper($valor->nombre);
                }
                if($value['asignado_a']){
                    $asignado = $value['asignado_a'];
                    $buscarUsuario = Usuarios::BuscarNombre($asignado);
                    foreach($buscarUsuario as $valor){
                        $value['asignado_a'] = strtoupper($valor->nombre);
                    }
                }else{
                    $value['asignado_a'] = 'SIN AGENTE DE SOPORTE';
                }
                $actualizado = $value['actualizado_por'];
                $buscarUsuario = Usuarios::BuscarNombre($actualizado);
                foreach($buscarUsuario as $valor){
                    $value['actualizado_por'] = strtoupper($valor->nombre);
                }
                $value['nombre_usuario'] = strtoupper($value['nombre_usuario']);
                $id_ticket = $value['id'];
                $value['historial'] = null;
                $historialTicket = Tickets::HistorialTicket($id_ticket);
                $contadorHistorial = count($historialTicket);
                if($contadorHistorial > 0){
                    foreach($historialTicket as $row){
                        $value['historial'] .= "- ".$row->observacion." (".$row->nombre_usuario." - ".date('d/m/Y h:i a', strtotime($row->creado)).")\n";
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

    public function crearTicketUsuario(Request $request){
        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = Funciones::BuscarURL($Administrador);
        $validator = Validator::make($request->all(), [
            'nombres'           =>  'required',
            'identificacion'    =>  'required',
            'cargo'             =>  'required',
            'sede'              =>  'required',
            'area'              =>  'required',
            'jefe'              =>  'required',
            'fechaIngreso'      =>  'required',
            'correoS'           =>  'required|regex:/^.+@.+$/i',
            'cargo_nuevo'       =>  'required',
            'estado'            =>  'required',
            'prioridad'         =>  'required',
            'area'              =>  'required'
        ]);

        if ($validator->fails()) {
            return redirect($url.'/ticketsUsuario')->withErrors($validator)->withInput();
        }else{

            $Redes = 0;
            $Infraestructura = 0;
            $Aplicaciones = 0;
            $Nombres                = Funciones::eliminar_tildes_texto($request->nombres);
            $Identificacion         = $request->identificacion;
            $Cargo                  = Funciones::eliminar_tildes_texto($request->cargo);
            $Sede                   = (int)$request->sede;
            $BuscarSede             = Sedes::BuscarSedeID($Sede);
            foreach($BuscarSede as $value){
                $NombreSede = $value->name;
            }
            $IdArea                 = (int)$request->area;
            $BuscarArea             = Sedes::BuscarAreaId($IdArea);
            foreach($BuscarArea as $row){
                $Area               = $row->name;
            }
            // $Area                   = $request->area;
            $Jefe                   = $request->jefe;
            $FechaIngreso           = date('Y-m-d H:i:s', strtotime($request->fechaIngreso));
            $CorreoS                = $request->correoS;
            $CargoNuevo             = (int)$request->cargo_nuevo;
            if($CargoNuevo === 1){
                $CargoNuevo_desc = 'Sí';
                $Infraestructura++;
            }else{
                $CargoNuevo_desc = 'No';
                $Infraestructura++;
            }
            if($request->funcionario){
                $Funcionario            = $request->funcionario;
            }else{
                $Funcionario = 'SIN FUNCIONARIO';
            }
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
            if($request->correo_electronico){
                $CorreoElectronico   = (int)$request->correo_electronico;
                if($CorreoElectronico === 1){
                    $Infraestructura++;
                    $CorreoElectronico_desc   = 'Sí';
                }
            }else{
                $CorreoElectronico   = 0;
                $CorreoElectronico_desc = 'No';
            }

            if($request->correo_funcionario){
                $CorreoFuncionario      = Funciones::editar_correo($request->correo_funcionario);
            }else{
                $CorreoFuncionario      = 'SIN CORREO';
            }
            if($request->equipo_computo){
                $EquipoComputo      = (int)$request->equipo_computo;
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

            if($request->acceso_carpeta){
                $AccesoCarpeta          = $request->acceso_carpeta;
            }else{
                $AccesoCarpeta          = 'SIN CARPETA';
            }
            if($request->celular){
                $Celular            = (int)$request->celular;
                if($Celular === 1){
                    $Redes++;
                    $Celular_desc = 'Sí';
                }
            }else{
                $Celular            = 0;
                $Celular_desc       = 'No';
            }
            if($request->datos){
                $Datos              = (int)$request->datos;
                if($Datos === 1){
                    $Redes++;
                    $Datos_desc = 'Sí';
                }
            }else{
                $Datos              = 0;
                $Datos_desc         = 'No';
            }
            if($request->minutos){
                $Minutos            = (int)$request->minutos;
                if($Minutos === 1){
                    $Redes++;
                    $Minutos_desc = 'Sí';
                }
            }else{
                $Minutos            = 0;
                $Minutos_desc       = 'No';
            }
            if($request->extension_tel){
                $ExtensionTel       = (int)$request->extension_tel;
                if($ExtensionTel === 1){
                    $Redes++;
                    $ExtensionTel_desc = 'Sí';
                }
            }else{
                $ExtensionTel       = 0;
                $ExtensionTel_desc  = 'No';
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
            if($request->app_85){
                $App85              = (int)$request->app_85;
                if($App85 === 1){
                    $Aplicaciones++;
                    $App85_desc = 'Sí';
                }
            }else{
                $App85              = 0;
                $App85_desc = 'No';
            }
            if($request->app_dinamica){
                $AppDinamica        = (int)$request->app_dinamica;
                if($AppDinamica === 1){
                    $Aplicaciones++;
                    $AppDinamica_desc = 'Sí';
                }
            }else{
                $AppDinamica        = 0;
                $AppDinamica_desc   = 'No';
            }
            if($request->otro_aplicativo){
                $OtroAplicativo     = $request->otro_aplicativo;
                $Aplicaciones++;
            }else{
                $OtroAplicativo     = 'NINGUNO';
            }
            if($request->cap_85){
                $Cap85              = (int)$request->cap_85;
                if($Cap85 === 1){
                    $Aplicaciones++;
                    $Cap85_desc = 'Sí';
                }
            }else{
                $Cap85              = 0;
                $Cap85_desc         = 'No';
            }
            if($request->cap_dinamica){
                $CapDinamica        = (int)$request->cap_dinamica;
                if($CapDinamica === 1){
                    $Aplicaciones++;
                    $CapDinamica_desc = 'Sí';
                }
            }else{
                $CapDinamica        = 0;
                $CapDinamica_desc = 'No';
            }
            $Observaciones          = Funciones::eliminar_tildes_texto($request->observaciones);
            $Estado                 = (int)$request->estado;
            $Prioridad              = (int)$request->prioridad;
            $nombrePrioridad        = Tickets::Prioridad($Prioridad);
            $nombreEstado           = Tickets::Estado($Estado);
            foreach($nombrePrioridad as $row){
                $namePrioridad = $row->name;
            }
            foreach($nombreEstado as $row){
                $nameEstado = $row->name;
            }

            $TicketUsuario = Tickets::CrearTicketUsuario($Nombres,$Identificacion,$Cargo,$Sede,$Area,$Jefe,$FechaIngreso,$CorreoS,$CargoNuevo,$Funcionario,$UsuarioDominio,$CorreoElectronico,$CorreoFuncionario,$EquipoComputo,$AccesoCarpeta,$Celular,$Datos,$Minutos,$ExtensionTel,$Conectividad,$AccesoInternet,$App85,$AppDinamica,$OtroAplicativo,$Cap85,$CapDinamica,$Observaciones,$Estado,$Prioridad,$creadoPor);

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
                                    Jefe Inmediato: $Jefe\n
                                    Fecha Ingreso: $FechaIngreso\n";

                if($Redes > 0){
                    $Categoria = 3;
                    // $BuscarUsuario = Usuarios::UsuarioTicket($Categoria);
                    // if($BuscarUsuario){
                    //     foreach($BuscarUsuario as $row){
                    //         $IdUsuario      = $row->id;
                    //     }
                    // }else{
                    //     $BuscarUsuario = Usuarios::UsuarioTicketBackup($Categoria);
                    //     foreach($BuscarUsuario as $row){
                    //         $IdUsuario = $row->id;
                    //     }
                    // }
                    $IdUsuario = null;
                    $Descripcion .= "Requiere Celular Coorporativo: $Celular_desc\n
                                    Requiere Datos: $Datos_desc\n
                                    Extensión Telefónica: $ExtensionTel_desc\n
                                    Conectividad VPN: $Conectividad_desc\n
                                    Nivel Internet: $AccesoInternet_desc\n
                                    Observación: $Observaciones";
                    $CrearTicket  = Tickets::CrearTicket($idTipo,$Asunto,$Descripcion,$NombreUsuario,$TelefonoUsuario,$CorreoS,
                                                $Sede,$Area,$Prioridad,$Categoria,$IdUsuario,$Estado,$creadoPor,$ticketUser);
                    $buscarUltimo = Tickets::BuscarLastTicket($creadoPor);
                    foreach($buscarUltimo as $row){
                        $idticket = $row->id;
                    }
                    // Tickets::CrearTicketAsignado($idticket,$Asunto,$Descripcion,$creadoPor,$IdUsuario);
                    $Tickets    .= "Ticket Redes y Comunicaciones: $idticket,";
                    // $buscarCorreo = Usuarios::BuscarNombre($IdUsuario);
                    // foreach($buscarCorreo as $rows){
                    //     if($emailAsignado === ""){
                    //         $emailAsignado  .= $rows->email;
                    //     }else{
                    //         $emailAsignado  .= ';'.$rows->email;
                    //     }
                    // }
                    $emailAsignado = 'soporte.sistemas@cruzrojabogota.org.co';
                }
                if($Infraestructura > 0){
                    $Categoria = 2;
                    // $BuscarUsuario = Usuarios::UsuarioTicket($Categoria);
                    // if($BuscarUsuario){
                    //     foreach($BuscarUsuario as $row){
                    //         $IdUsuario      = $row->id;
                    //     }
                    // }else{
                    //     $BuscarUsuario = Usuarios::UsuarioTicketBackup($Categoria);
                    //     foreach($BuscarUsuario as $row){
                    //         $IdUsuario = $row->id;
                    //     }
                    // }
                    $IdUsuario = null;
                    $Descripcion .= "Cargo Nuevo: $CargoNuevo_desc\n
                                    Usuario Dominio: $UsuarioDominio_desc\n
                                    Funcionario que Reemplaza: $Funcionario\n
                                    Correo Funcionario que reemplaza: $CorreoFuncionario\n
                                    Requiere Correo Electronico: $CorreoElectronico_desc\n
                                    Requiere Equipo de Computo: $EquipoComputo_desc\n
                                    Carpeta Compartida: $AccesoCarpeta\n
                                    Observación: $Observaciones";
                    $CrearTicket  = Tickets::CrearTicket($idTipo,$Asunto,$Descripcion,$NombreUsuario,$TelefonoUsuario,$CorreoS,
                                                $Sede,$Area,$Prioridad,$Categoria,$IdUsuario,$Estado,$creadoPor,$ticketUser);
                    $buscarUltimo = Tickets::BuscarLastTicket($creadoPor);
                    foreach($buscarUltimo as $row){
                        $idticket = $row->id;
                    }
                    // Tickets::CrearTicketAsignado($idticket,$Asunto,$Descripcion,$creadoPor,$IdUsuario);
                    $Tickets    .= "Ticket Infraestructura: $idticket,";
                    // $buscarCorreo = Usuarios::BuscarNombre($IdUsuario);
                    // foreach($buscarCorreo as $rows){
                    //     if($emailAsignado === ""){
                    //         $emailAsignado  .= $rows->email;
                    //     }else{
                    //         $emailAsignado  .= ';'.$rows->email;
                    //     }
                    // }
                    $emailAsignado = 'soporte.sistemas@cruzrojabogota.org.co';
                }
                if($Aplicaciones > 0){
                    // $Categoria = 1;
                    $Categoria = 4;
                    // $BuscarUsuario = Usuarios::UsuarioTicket($Categoria);
                    // if($BuscarUsuario){
                    //     foreach($BuscarUsuario as $row){
                    //         $IdUsuario      = $row->id;
                    //     }
                    // }else{
                    //     $BuscarUsuario = Usuarios::UsuarioTicketBackup($Categoria);
                    //     foreach($BuscarUsuario as $row){
                    //         $IdUsuario = $row->id;
                    //     }
                    // }
                    $IdUsuario = null;
                    $Descripcion .= "Servinté: $App85_desc\n
                                    Dínamica: $AppDinamica_desc\n
                                    Otro Aplicativo: $OtroAplicativo\n
                                    Capacitación Servinté: $Cap85_desc\n
                                    Capacitación Dinamica: $CapDinamica_desc\n
                                    Observación: $Observaciones";
                                    $CrearTicket  = Tickets::CrearTicket($idTipo,$Asunto,$Descripcion,$NombreUsuario,$TelefonoUsuario,$CorreoS,
                                                $Sede,$Area,$Prioridad,$Categoria,$IdUsuario,$Estado,$creadoPor,$ticketUser);
                    $buscarUltimo = Tickets::BuscarLastTicket($creadoPor);
                    foreach($buscarUltimo as $row){
                        $idticket = $row->id;
                    }
                    // Tickets::CrearTicketAsignado($idticket,$Asunto,$Descripcion,$creadoPor,$IdUsuario);
                    $Tickets    .= "Ticket Aplicaciones: $idticket,";
                    // $buscarCorreo = Usuarios::BuscarNombre($IdUsuario);
                    // foreach($buscarCorreo as $rows){
                    //     if($emailAsignado === ""){
                    //         $emailAsignado  .= $rows->email;
                    //     }else{
                    //         $emailAsignado  .= ';'.$rows->email;
                    //     }
                    // }
                    $emailAsignado = 'soporte.sistemas@cruzrojabogota.org.co';
                }
                $DescriptionT = "<b>Nombres y Apellidos:</b> $Nombres<br>
                                <b>Identificación:</b> $Identificacion<br>
                                <b>Cargo:</b> $Cargo<br>
                                <b>Sede:</b> $NombreSede<br>
                                <b>Área:</b> $Area<br>
                                <b>Jefe Inmediato:</b> $Jefe<br>
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

                $buscar = strpos($CorreoS,';');
                if($buscar === false){
                    $for = "$CorreoS";
                }else{
                    $for = array();
                    $for = explode(';',$CorreoS);
                }
                $copia = strpos($emailAsignado,';');
                if($copia === false){
                    $cco = "$emailAsignado";
                }else{
                    $cco = array();
                    $cco = explode(';',$emailAsignado);
                }
                // dd($for.' '.$cco);
                // $cco = "$emailAsignado";
                $calificacion = 0;
                $calificacion1 = null;
                $calificacion2 = null;
                $calificacion3 = null;
                $calificacion4 = null;
                $calificacion5 = null;
                $correoEmail = 'seleccion@cruzrojabogota.org.co;'.$CorreoS;
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
                    return redirect($url.'/ticketsUsuario')->with('mensaje', $verrors);
                }else{
                    $verrors = 'Se creo con éxito el ticket '.$idticket.', pero no pudo ser enviado el correo al usuario';
                    return redirect($url.'/ticketsUsuario')->with('precaucion', $verrors);
                }
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al crear el ticket');
                return Redirect::to($url.'/tickets')->withErrors(['errors' => $verrors])->withRequest();
            }
        }
    }

    public function crearTicketRecurrente(Request $request){
        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = Funciones::BuscarURL($Administrador);
        $validator = Validator::make($request->all(), [
            'asunto'        =>  'required',
            'categoria'     =>  'required',
            'prioridad'     =>  'required',
            'tipo_usuario'  =>  'required'
        ]);

        if ($validator->fails()) {
            return redirect($url.'/ticketsRecurrentes')->withErrors($validator)->withInput();
        }else{

            $Asunto     = Funciones::eliminar_tildes_texto($request->asunto);
            $Categoria  = (int)$request->categoria;
            $Prioridad  = (int)$request->prioridad;
            $Tipo       = (int)$request->tipo_usuario;
            $CrearRecurrente    = Tickets::CrearRecurrente($Asunto,$Categoria,$Prioridad,$creadoPor,$Tipo);
            if($CrearRecurrente){
                $verrors = 'Se creo con éxito el asunto';
                return redirect($url.'/ticketsRecurrentes')->with('mensaje', $verrors);
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al crear el asunto');
                return Redirect::to($url.'/ticketsRecurrentes')->withErrors(['errors' => $verrors])->withRequest();
            }
        }
    }

    public function actualizarTicketRecurrente(Request $request){
        $creadoPor      = (int)Session::get('IdUsuario');
        $buscarUsuario  = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = Funciones::BuscarURL($Administrador);
        $validator = Validator::make($request->all(), [
            'asunto_upd'        =>  'required',
            'categoria_upd'     =>  'required',
            'prioridad_upd'     =>  'required',
            'activo'            =>  'required',
            'tipo_usuario_upd'  =>  'required'
        ]);

        if ($validator->fails()) {
            return redirect($url.'/ticketsRecurrentes')->withErrors($validator)->withInput();
        }else{

            $Asunto     = $request->asunto_upd;
            $Categoria  = (int)$request->categoria_upd;
            $Prioridad  = (int)$request->prioridad_upd;
            $IdTicket   = (int)$request->idT;
            $Activo     = (int)$request->activo;
            $Tipo       = (int)$request->tipo_usuario_upd;
            $ActualizarRecurrente   = Tickets::ActualizarRecurrente($Asunto,$Categoria,$Prioridad,$creadoPor,$IdTicket,$Activo,$Tipo);
            if($ActualizarRecurrente){
                $verrors = 'Se actualizó con éxito el asunto';
                return redirect($url.'/ticketsRecurrentes')->with('mensaje', $verrors);
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al actualizar el asunto');
                return Redirect::to($url.'/ticketsRecurrentes')->withErrors(['errors' => $verrors])->withRequest();
            }
        }
    }

    public function calificarTicket(Request $request){
        $puntuacion = (int)$request->valor;
        $idTicket   = (int)$request->idTicket;
        $ipCliente  = $_SERVER['REMOTE_ADDR'];
        $UserName   = gethostbyaddr($_SERVER['REMOTE_ADDR']);

        $buscarCalificacion = Tickets::BuscarCalificacion($idTicket,$ipCliente);
        $contadorBusqueda = count($buscarCalificacion);
        if($contadorBusqueda > 0){
            $Mensaje = 'Su valoración ya fue guardada anteriormente.';
        }else{
            $Calificar = Tickets::Calificar($idTicket,$ipCliente,$UserName,$puntuacion);
            if($Calificar){
                $Mensaje = 'Muchas Gracias por su atención y su valoración del servicio.';
            }else{
                $Mensaje = 'Hubo un problema al realizar su calificación, intente de nuevo';
            }

        }

        return view('Email.CalificacionT',['MENSAJE' => $Mensaje]);

    }



    public function buscarCategoria(Request $request){
        $id   = (int)$request->id_categoria;
        $NombreUsuario = array();
        $buscarUsuario = Usuarios::BuscarXCategoria($id);
        $NombreUsuario[0] = 'Seleccione: ';
        foreach ($buscarUsuario as $row){
            $NombreUsuario[$row->id] = Funciones::eliminar_tildes_texto($row->name);
        }
        return response::json(array('valido'=>'true','Usuario'=>$NombreUsuario));

    }

    public function buscarCategoriaS(Request $request){

        $id   = (int)$request->id_categoria;
        $NombreUsuario = array();
        $buscarUsuario = Usuarios::BuscarXCategoriaSolicitud($id);
        $NombreUsuario[0] = 'Seleccione: ';
        foreach ($buscarUsuario as $row){
            $NombreUsuario[$row->id] = Funciones::eliminar_tildes_texto($row->nombre);
        }
        return Response::json(array('valido'=>'true','Usuario'=>$NombreUsuario));

    }

    public function buscarCategoriaRepo(Request $request){
        $id   = (int)$request->id_categoria;
        $NombreUsuario = array();
        $buscarUsuario = Usuarios::BuscarXCategoriaSolicitud($id);
        $NombreUsuario[0] = 'Seleccione: ';
        foreach ($buscarUsuario as $row){
            $NombreUsuario[$row->id] = Funciones::eliminar_tildes_texto($row->name);
        }
        return Response::json(array('valido'=>'true','Usuario'=>$NombreUsuario));

    }

    public function buscarCategoriaUPD(Request $request){
        $id   = (int)$request->id_categoria;
        $NombreUsuario = array();
        $buscarUsuario = Usuarios::BuscarXCategoria($id);
        $NombreUsuario[0] = 'Seleccione: ';
        foreach ($buscarUsuario as $row){
            $NombreUsuario[$row->id] = Funciones::eliminar_tildes_texto($row->name);
        }
        return Response::json(array('valido'=>'true','Usuario'=>$NombreUsuario));

    }

    public function buscarArea(Request $request){
        $id             = (int)$request->id_sede;
        $NombreUsuario = array();
        $buscarUsuario = Sedes::BuscarAreaIdSede($id);
        $NombreUsuario[0] = 'Seleccione: ';
        foreach ($buscarUsuario as $row){
            $NombreUsuario[$row->id] = Funciones::eliminar_tildes_texto($row->name);
        }
        return Response::json(array('valido'=>'true','Usuario'=>$NombreUsuario));
    }

    public function crearSolicitud(){
        $Sedes  = Tickets::Sedes();
        $NombreSede = array();
        $NombreSede[''] = 'Seleccione: ';
        foreach ($Sedes as $row){
            $NombreSede[$row->id] = $row->name;
        }

        $Areas  = Sedes::Areas();
        $NombreArea = array();
        $NombreArea[''] = 'Seleccione: ';

        $Tipo  = Tickets::ListarTipo();
        $NombreTipo = array();
        $NombreTipo[''] = 'Seleccione: ';
        foreach ($Tipo as $row){
            $NombreTipo[$row->id] = $row->name;
        }
        $Categoria  = Roles::ListarCategorias();
        $NombreCategoria = array();
        $NombreCategoria[''] = 'Seleccione: ';
        foreach ($Categoria as $row){
            $NombreCategoria[$row->id] = $row->name;
        }
        $Recurrente = Tickets::ListarRecurrentes();
        $TicketRecurrente = array();
        $TicketRecurrente[''] = 'Seleccione: ';
        foreach ($Recurrente as $row){
            $TicketRecurrente[$row->id] = $row->nombre;
        }
        $contT = 0;
        $Asuntos = array();
        foreach ($Recurrente as $value){
            $Asuntos[$contT]['id']     = $value->id;
            $Asuntos[$contT]['nombre'] = $value->nombre;
            $contT++;
        }
        return view('CrearSolicitud',['Sedes' => $NombreSede,'Tipo' => $NombreTipo,'TicketRecurrente' => $TicketRecurrente,'Categoria' => $NombreCategoria,
                                        'Areas' => $NombreArea,'Asuntos' => $Asuntos]);
    }

    public function nuevaSolicitud(Request $request){
        $CorreoIndex    = Funciones::editar_correo($request->correo_usuario);
        $findme         = '@';
        $pos            = strpos($CorreoIndex, $findme);
        if ($pos === false) {
            $verrors = array();
            array_push($verrors, 'El correo debe contener la estructura correcta @');
            return Redirect::to('/crearSolicitud')->withErrors(['errors' => $verrors])->withRequest();
        }
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
            return redirect('/crearSolicitud')->withErrors($validator)->withInput();
        }else{

            $idTipo             = (int)$request->kind_id;

            $Descripcion        = Funciones::eliminar_tildes_texto($request->description);
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
            $idAsunto           = (int)$request->asunto;
            if($idAsunto === 1){
                $Prioridad      = 2;
                $Categoria      = 4;
                $Asunto         = Funciones::eliminar_tildes_texto($request->title);
            }else{
                $buscardatos = Tickets::ListarRecurrentesId($idAsunto);
                if($buscardatos){
                    foreach($buscardatos as $row){
                        $Prioridad = (int)$row->priority_id;
                        $Categoria = (int)$row->category_id;
                        $Asunto    = $row->nombre;
                    }
                }else{
                    $Prioridad          = 2;
                    $Categoria          = 4;
                    $Asunto             = Funciones::eliminar_tildes_texto($request->title);
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
                    // $calificacion1 = "<a href='http://192.168.0.125:8080/mesadeayuda/public/calificarTicket?valor=1&idTicket=$ticket'><img src='http://192.168.0.125:8080/helpdesk/public/assets/dist/img/calificacion/excelente.png' width='60' height='60'/></a>";
                    // $calificacion2 = "<a href='http://192.168.0.125:8080/mesadeayuda/public/calificarTicket?valor=2&idTicket=$ticket'><img src='http://192.168.0.125:8080/helpdesk/public/assets/dist/img/calificacion/bueno.png' width='60' height='60'/></a>";
                    // $calificacion3 = "<a href='http://192.168.0.125:8080/mesadeayuda/public/calificarTicket?valor=1&idTicket=$ticket'><img src='http://192.168.0.125:8080/helpdesk/public/assets/dist/img/calificacion/regular.png' width='60' height='60'/></a>";
                    // $calificacion4 = "<a href='http://192.168.0.125:8080/mesadeayuda/public/calificarTicket?valor=1&idTicket=$ticket'><img src='http://192.168.0.125:8080/helpdesk/public/assets/dist/img/calificacion/malo.png' width='60' height='60'/></a>";
                    // $calificacion5 = "<a href='http://192.168.0.125:8080/mesadeayuda/public/calificarTicket?valor=1&idTicket=$ticket'><img src='http://192.168.0.125:8080/helpdesk/public/assets/dist/img/calificacion/pesimo.png' width='60' height='60'/></a>";
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
                //     return view('CrearSolicitudMensaje',['Ticket' => $ticket]);
                // }else{
                //     return view('CrearSolicitudMensaje',['Ticket' => $ticket]);
                // }
                if(count(Mail::failures()) === 0){
                    $verrors = 'Se creo con éxito el ticket '.$ticket.'\n Por favor revise la información del ticket que fue enviada al correo registrado para realizar su respectivo seguimiento.';
                    return redirect('/crearSolicitud')->with('mensaje', $verrors);
                }else{
                    $verrors = 'Se creo con éxito el ticket '.$ticket.', pero no pudo ser enviado el correo al usuario';
                    return redirect('/crearSolicitud')->with('precaucion', $verrors);
                }
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al crear el ticket');
                return Redirect::to('/crearSolicitud')->withErrors(['errors' => $verrors])->withRequest();
            }
        }
    }
}
