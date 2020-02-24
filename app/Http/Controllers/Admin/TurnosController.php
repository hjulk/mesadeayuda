<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Funciones;
use App\Models\Admin\Usuarios;
use App\Models\Admin\Sedes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Monolog\Handler\ZendMonitorHandler;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Session;


class TurnosController extends Controller
{
    public function turnos(){
        $Turnos         = Usuarios::ListarTurnos();
        $ListarTurnos   = array();
        $cont           = 0;
        foreach($Turnos as $row){
            $ListarTurnos[$cont]['id']              = (int)$row->id;
            $ListarTurnos[$cont]['agente1']         = (int)$row->agente1;
            $agente1 = (int)$row->agente1;
            $BuscarNombre1 = Usuarios::BuscarNombre($agente1);
            if($BuscarNombre1){
                foreach($BuscarNombre1 as $value){
                    $ListarTurnos[$cont]['nombre_agente1'] = $value->name;
                }
            }else{
                $ListarTurnos[$cont]['nombre_agente1'] = 'Sin Agente de mesa de ayuda';
            }

            $ListarTurnos[$cont]['agente2']         = (int)$row->agente2;
            $agente2 = (int)$row->agente2;
            $BuscarNombre2 = Usuarios::BuscarNombre($agente2);
            if($BuscarNombre2){
                foreach($BuscarNombre2 as $value){
                    $ListarTurnos[$cont]['nombre_agente2'] = $value->name;
                }
            }else{
                $ListarTurnos[$cont]['nombre_agente2'] = 'Sin Agente de mesa de ayuda';
            }
            $ListarTurnos[$cont]['fecha_inicial']   = date('d-m-Y', strtotime($row->fecha_inicial));
            $ListarTurnos[$cont]['fecha_final']     = $row->fecha_final;
            $ListarTurnos[$cont]['id_sede']         = (int)$row->id_sede;
            $IdSede = (int)$row->id_sede;
            $BuscarSede = Sedes::BuscarSedeID($IdSede);
            foreach($BuscarSede as $value){
                $ListarTurnos[$cont]['sede'] = Funciones::eliminar_tildes_texto($value->name);
            }
            $ListarTurnos[$cont]['id_horario']      = (int)$row->id_horario;
            $IdHorario = (int)$row->id_horario;
            $BuscarHorario = Usuarios::BuscarHorarioID($IdHorario);
            foreach($BuscarHorario as $value){
                $ListarTurnos[$cont]['horario'] = $value->name;
            }
            $ListarTurnos[$cont]['disponible']      = $row->disponible;
            $cont++;
        }

        $ListaHorario     = Usuarios::ListarHorarios();
        $Horario = array();
        $Horario[''] = 'Seleccione: ';
        foreach ($ListaHorario as $row){
            $Horario[$row->id] = $row->name;
        }

        $ListaSede     = Usuarios::ListarSedesTurno();
        $Sede = array();
        $Sede[''] = 'Seleccione: ';
        foreach ($ListaSede as $row){
            $Sede[$row->id] = Funciones::eliminar_tildes_texto($row->name);
        }

        $ListaAgente     = Usuarios::ListarUsuariosTurno();
        $Agente = array();
        $Agente[''] = 'Seleccione: ';
        foreach ($ListaAgente as $row){
            $Agente[$row->id] = $row->name;
        }

        $Disponibilidad = array();
        $Disponibilidad[''] = 'Seleccione: ';
        $Disponibilidad['DISPONIBILIDAD']   = 'DISPONIBILIDAD';
        $Disponibilidad['MESA DE AYUDA']    = 'MESA DE AYUDA';

        return view('Turnos.Turnos',['Turnos' => $ListarTurnos,'Horario' => $Horario,'Sede' => $Sede,'Agente' => $Agente,
                                    'Disponibilidad' => $Disponibilidad]);
    }
    public function crearTurno(Request $request){

        $creadoPor          = (int)Session::get('IdUsuario');
        $buscarUsuario = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = Funciones::BuscarURL($Administrador);
        $validator = Validator::make($request->all(), [
            'agente'            =>  'required',
            'fecha_inicio'      =>  'required',
            'sede'              =>  'required',
            'horario'           =>  'required',
            'disponibilidad'    =>  'required'
        ]);

        if ($validator->fails()) {
            return redirect($url.'/turnos')->withErrors($validator)->withInput();
        }else{

            $Agente         = (int)$request->agente;
            $FechaInicio    = date('Y-m-d', strtotime($request->fecha_inicio));
            $FechaInicioEmail   = date('d-m-Y', strtotime($request->fecha_inicio));
            if($request->fecha_fin){
                $FechaFin   = $request->fecha_fin;
                $FechaFinEmail   = date('d-mY', strtotime($request->fecha_fin));
            }else{
                $FechaFin   = 'INDEFINIDO';
                $FechaFinEmail   = 'INDEFINIDO';
            }
            $Sede           = (int)$request->sede;
            $Horario        = (int)$request->horario;
            $Disponibilidad = $request->disponibilidad;

            $CreacionTurno = Usuarios::CreacionTurno($Agente,$FechaInicio,$FechaFin,$Sede,$Horario,$Disponibilidad);
            if($CreacionTurno){

                $BuscarNombre = Usuarios::BuscarNombre($Agente);
                if($BuscarNombre){
                    foreach($BuscarNombre as $value){
                        $NombreAgente = $value->name;
                        $CorreoAgente = $value->email;
                    }
                }else{
                    $NombreAgente = 'SIN NOMBRE';
                    $CorreoAgente = 'sosporte.sistemas@cruzrojabogota.org.co';
                }
                $BuscarSede = Sedes::BuscarSedeID($Sede);
                foreach($BuscarSede as $value){
                    $NombreSede = $value->name;
                }
                $BuscarHorario = Usuarios::BuscarHorarioID($Horario);
                foreach($BuscarHorario as $value){
                    $NombreHorario = $value->name;
                }
                date_default_timezone_set('America/Bogota');
                $fecha_sistema      = date('Y-m-d H:i');
                $Creacion = date('Y-m-d H:i', strtotime($fecha_sistema));
                $subject = "Creación turno Mesa de ayuda";
                $for = "$CorreoAgente";
                $Correo = 1;
                Mail::send('email/EmailTurno',
                        ['NombreAgente' => $NombreAgente,'FechaInicio' => $FechaInicioEmail,'FechaFin' => $FechaFinEmail,
                        'NombreSede' => $NombreSede,'NombreHorario' => $NombreHorario,'NombreDisponible' => $Disponibilidad,
                        'FechaCreacion' => $Creacion,'Correo' => $Correo],
                        function($msj) use($subject,$for){
                            $msj->from("soporte.sistemas@cruzrojabogota.org.co","Mesa de Ayuda - Tics");
                            $msj->subject($subject);
                            $msj->to($for);

                        });

                if(count(Mail::failures()) === 0){
                    $verrors = 'Se creo con éxito el turno para '.$NombreAgente;
                    return redirect($url.'/turnos')->with('mensaje', $verrors);
                }else{
                    $verrors = 'Se creo con éxito el turno para '.$NombreAgente.', pero no pudo ser enviado el correo al usuario';
                    return redirect($url.'/turnos')->with('precaucion', $verrors);
                }
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al crear el turno');
                return Redirect::to($url.'/turnos')->withErrors(['errors' => $verrors])->withRequest();
            }

        }
    }

    public function actualizarTurno(Request $request){

        $creadoPor          = (int)Session::get('IdUsuario');
        $buscarUsuario = Usuarios::BuscarNombre($creadoPor);
        foreach($buscarUsuario as $value){
            $Administrador = (int)$value->rol_id;
        }
        $url = Funciones::BuscarURL($Administrador);
        $validator = Validator::make($request->all(), [
            'agente_upd'            =>  'required',
            'fecha_inicio_upd'      =>  'required',
            'sede_upd'              =>  'required',
            'horario_upd'           =>  'required',
            'disponibilidad_upd'    =>  'required'
        ]);

        if ($validator->fails()) {
            return redirect($url.'/turnos')->withErrors($validator)->withInput();
        }else{

            $Agente             = $request->agente_upd;
            $FechaInicio        = date('Y-m-d', strtotime($request->fecha_inicio_upd));
            $FechaInicioEmail   = date('d-m-Y', strtotime($request->fecha_inicio_upd));
            if($request->fecha_fin_upd){
                $FechaFin       = $request->fecha_fin_upd;
                $FechaFinEmail  = date('d-m-Y', strtotime($request->fecha_fin_upd));
            }else{
                $FechaFin   = 'INDEFINIDO';
                $FechaFinEmail   = 'INDEFINIDO';
            }
            $Sede           = $request->sede_upd;
            $Horario        = $request->horario_upd;
            $Disponibilidad = $request->disponibilidad_upd;
            $IdTurno        = $request->idTu;


            $ActualizacionTurno = Usuarios::ActualizacionTurno($Agente,$FechaInicio,$FechaFin,$Sede,$Horario,$Disponibilidad,$IdTurno);
            if($ActualizacionTurno){

                $BuscarNombre = Usuarios::BuscarNombre($Agente);
                if($BuscarNombre){
                    foreach($BuscarNombre as $value){
                        $NombreAgente = $value->name;
                        $CorreoAgente = $value->email;
                    }
                }else{
                    $NombreAgente = 'SIN NOMBRE';
                    $CorreoAgente = 'sosporte.sistemas@cruzrojabogota.org.co';
                }
                $BuscarSede = Sedes::BuscarSedeID($Sede);
                foreach($BuscarSede as $value){
                    $NombreSede = $value->name;
                }
                $BuscarHorario = Usuarios::BuscarHorarioID($Horario);
                foreach($BuscarHorario as $value){
                    $NombreHorario = $value->name;
                }
                date_default_timezone_set('America/Bogota');
                $fecha_sistema      = date('Y-m-d H:i');
                $Creacion = date('Y-m-d H:i', strtotime($fecha_sistema));

                $subject = "Actualización turno Mesa de ayuda";
                $for = "$CorreoAgente";
                $Correo = 2;
                Mail::send('email/EmailTurno',
                        ['NombreAgente' => $NombreAgente,'FechaInicio' => $FechaInicioEmail,'FechaFin' => $FechaFinEmail,
                        'NombreSede' => $NombreSede,'NombreHorario' => $NombreHorario,'NombreDisponible' => $Disponibilidad,
                        'FechaCreacion' => $Creacion,'Correo' => $Correo],
                        function($msj) use($subject,$for){
                            $msj->from("soporte.sistemas@cruzrojabogota.org.co","Mesa de Ayuda - Tics");
                            $msj->subject($subject);
                            $msj->to($for);

                        });

                if(count(Mail::failures()) === 0){
                    $verrors = 'Se actualizo con éxito el turno para '.$NombreAgente;
                    return redirect($url.'/turnos')->with('mensaje', $verrors);
                }else{
                    $verrors = 'Se actualizo con éxito el turno para '.$NombreAgente.', pero no pudo ser enviado el correo al usuario';
                    return redirect($url.'/turnos')->with('precaucion', $verrors);
                }
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al actualizar el turno');
                return Redirect::to($url.'/turnos')->withErrors(['errors' => $verrors])->withRequest();
            }
        }
    }

}
