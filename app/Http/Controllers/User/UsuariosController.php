<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Funciones;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\HelpDesk\Tickets;
use App\Models\Admin\Sedes;
use App\Models\Admin\Usuarios;
use App\Http\Requests\Validaciones;
use Illuminate\Support\Facades\Validator;
use Monolog\Handler\ZendMonitorHandler;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Admin\Activo;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;

class UsuariosController extends Controller
{

    public function inicio()
    {
        $creadoPor     = (int)Session::get('IdUsuario');
        setlocale(LC_ALL, 'es_CO');
        $EnDesarrollo   = Tickets::EnDesarrolloUsuario($creadoPor);
        foreach($EnDesarrollo as $valor){
            $desarrolloT = $valor->total;
        }
        $Pendientes     = Tickets::PendientesUsuario($creadoPor);
        foreach($Pendientes as $valor){
            $pendientesT = $valor->total;
        }
        $Cancelados     = Tickets::CanceladosUsuario($creadoPor);
        foreach($Cancelados as $valor){
            $canceladosT = $valor->total;
        }
        $Terminados     = Tickets::TerminadosUsuario($creadoPor);
        foreach($Terminados as $valor){
            $terminadosT = $valor->total;
        }
        $Infraestructura     = Tickets::Infraestructura();
        foreach($Infraestructura as $valor){
            $InfraestructuraT = $valor->total;
        }
        $Redes     = Tickets::Redes();
        foreach($Redes as $valor){
            $RedesT = $valor->total;
        }
        $Aplicaciones     = Tickets::Aplicaciones();
        foreach($Aplicaciones as $valor){
            $AplicacionesT = $valor->total;
        }
        $Soporte     = Tickets::Soporte();
        foreach($Soporte as $valor){
            $SoporteT = $valor->total;
        }
        setlocale(LC_ALL, 'es_ES');
        $fechaActual = date('M - Y');
        // $mesActual  = date('M - y', strtotime($fechaActual));
        $mesCreacion = date('M', strtotime($fechaActual));
        $anio = date('y', strtotime($fechaActual));
        $meses_ES = array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
        $meses_EN = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
        $nombreMes = str_replace($meses_EN, $meses_ES, $mesCreacion);

        $mesActual = $nombreMes;
        Tickets::GuardarMesUsuarioUpd($creadoPor);
        $guardarMes = Tickets::GuardarMesUsuario($mesActual,$creadoPor,$anio);
        $buscarGestion  = Tickets::buscarGestion();
        $buscarGestionTotal  = Tickets::buscarGestionTotalUsuario($creadoPor);
        foreach($buscarGestionTotal as $row){
            $totalGestion = (int)$row->total;
        }
        $resultado_consulta = array();
        $cont = 0;
        if($totalGestion > 0){
            $resultado_gestion = array();
            $contadorGestion = count($buscarGestion);
            $contG = 0;
            foreach($buscarGestion as $consulta){
                    $resultado_gestion[$contG]['nombre']= $consulta->nombre_usuario;
                    $resultado_gestion[$contG]['desarrollo']= $consulta->desarrollo;
                    $resultado_gestion[$contG]['pendientes']= $consulta->pendientes;
                    $resultado_gestion[$contG]['terminados']= $consulta->terminados;
                    $resultado_gestion[$contG]['cancelados']= $consulta->cancelados;

                    if($contG >= ($contadorGestion-1)){
                        $resultado_gestion[$contG]['separador']= '';
                    }else{
                        $resultado_gestion[$contG]['separador']= ',';
                    }
                    $contG++;
            }
        }else{
            $resultado_gestion = null;
        }
        $buscarMes = Tickets::BuscarMesUsuario($anio,$creadoPor);
        if($guardarMes === false){
            $resultado_consulta = null;
        }else{
            // $buscarMes = Tickets::BuscarMesUsuario($anio);
            $contadorMes = count($buscarMes);

            foreach($buscarMes as $consulta){
                    $resultado_consulta[$cont]['nombre']            = $consulta->mes.' - '.$consulta->year;
                    $resultado_consulta[$cont]['incidentes']        = $consulta->incidentes;
                    $resultado_consulta[$cont]['requerimientos']    = $consulta->requerimientos;

                    if($cont >= ($contadorMes-1)){
                        $resultado_consulta[$cont]['separador']= '';
                    }else{
                        $resultado_consulta[$cont]['separador']= ',';
                    }
                    $cont++;
            }

        }

        return view('user.index',['EnDesarrollo' => $desarrolloT,'Pendientes' => $pendientesT,'Terminados' => $terminadosT,'Cancelados' => $canceladosT,
                                   'MesGraficas' => $resultado_consulta,'Infraestructura' => $InfraestructuraT,'Redes' => $RedesT,'Aplicaciones' => $AplicacionesT,
                                   'Soporte' => $SoporteT,'Gestion' => $resultado_gestion]);
    }


    public function profile(){
        return view('user.profile',['Contrasena' => null,'RContrasena' => null]);
    }

    public function actualizarUsuario(Request $request){
        $Contrasena = $request->password;
        $RPassword  = $request->repeat_password;
        $idUsuario  = Session::get('IdUsuario');
        $creadoPor  = Session::get('IdUsuario');
        $infoUsario = Usuarios::BuscarNombre($idUsuario);
        foreach($infoUsario as $valor){
            $nombreUsuario  = $valor->nombre;
            $nombrefoto     = $valor->profile_pic;
            $userName       = $valor->username;
            $userPassword   = $valor->password;
        }

        if($Contrasena === $RPassword){
            $destinationPath = null;
            $filename        = null;
            if ($request->hasFile('profile_pic')) {
                $file            = $request->file('profile_pic');
                $destinationPath = public_path().'/assets/dist/img/profiles';
                $extension       = $file->getClientOriginalExtension();
                $nombrearchivo   = str_replace(".", "_", $userName);
                $filename        = $nombrearchivo.'.'.$extension;
                $uploadSuccess   = $file->move($destinationPath, $filename);
                $archivofoto    = file_get_contents($uploadSuccess);
            }else{
                $filename = $nombrefoto;
            }
            $NombreFoto     = $filename;
            if($request->password){
                if($request->repeat_password){
                    $Password     = hash('sha512', $Contrasena);
                }
            }else{
                $Password     = $userPassword;
            }

            $updateProfile = Usuarios::ActualizarProfile($Password,$idUsuario,$NombreFoto,$creadoPor);

            if($updateProfile){
                $verrors = 'Se actualizo con exito la contraseña';
                return redirect('user/profile')->with('mensaje', $verrors);
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al actualizar la contraseña');
                return Redirect::to('user/profile')->withErrors(['errors' => $verrors])->withRequest();
            }

        }else{
            $verrors = array();
            array_push($verrors, 'Las contraseñas no coinciden');
            return Redirect::to('user/profile')->withErrors(['errors' => $verrors])->withRequest();
        }


    }

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


}
