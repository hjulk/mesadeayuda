<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HelpDesk\Tickets;
use App\Models\Admin\Sedes;
use App\Models\Admin\Usuarios;
use App\Models\Admin\Roles;
use App\Models\HelpDesk\Inventario;
use Illuminate\Support\Facades\Session;

class AdministracionController extends Controller
{
    public function dashboard()
    {
        $creadoPor     = (int)Session::get('IdUsuario');
        setlocale(LC_ALL, 'es_CO');
        $EnDesarrollo   = Tickets::EnDesarrollo();
        foreach($EnDesarrollo as $valor){
            $desarrolloT = $valor->total;
        }

        $Pendientes     = Tickets::Pendientes();
        foreach($Pendientes as $valor){
            $pendientesT = $valor->total;
        }

        $Cancelados     = Tickets::Cancelados();
        foreach($Cancelados as $valor){
            $canceladosT = $valor->total;
        }

        $Terminados     = Tickets::Terminados();
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

        $Servinte     = Tickets::Servinte();
        foreach($Servinte as $valor){
            $ServinteT = $valor->total;
        }

        $BuscarMInsatisfecho     = Tickets::BuscarMInsatisfecho();
        foreach($BuscarMInsatisfecho as $valor){
            $MuyInsatisfecho = $valor->total;
        }
        $BuscarInsatisfecho     = Tickets::BuscarInsatisfecho();
        foreach($BuscarInsatisfecho as $valor){
            $Insatisfecho = $valor->total;
        }
        $BuscarNeutral     = Tickets::BuscarNeutral();
        foreach($BuscarNeutral as $valor){
            $Neutral = $valor->total;
        }
        $BuscarSatisfecho     = Tickets::BuscarSatisfecho();
        foreach($BuscarSatisfecho as $valor){
            $Satisfecho = $valor->total;
        }
        $BuscarMSatisfecho     = Tickets::BuscarMSatisfecho();
        foreach($BuscarMSatisfecho as $valor){
            $MuySatisfecho = $valor->total;
        }
        $PorcentajeMInsatisfecho     = Tickets::PorcentajeMInsatisfecho();
        foreach($PorcentajeMInsatisfecho as $valor){
            $PMuyInsatisfecho = $valor->porcentaje;
        }
        $PorcentajeInsatisfecho     = Tickets::PorcentajeInsatisfecho();
        foreach($PorcentajeInsatisfecho as $valor){
            $PInsatisfecho = $valor->porcentaje;
        }
        $PorcentajeNeutral     = Tickets::PorcentajeNeutral();
        foreach($PorcentajeNeutral as $valor){
            $PNeutral = $valor->porcentaje;
        }
        $PorcentajeSatisfecho     = Tickets::PorcentajeSatisfecho();
        foreach($PorcentajeSatisfecho as $valor){
            $PSatisfecho = $valor->porcentaje;
        }
        $PorcentajeMSatisfecho     = Tickets::PorcentajeMSatisfecho();
        foreach($PorcentajeMSatisfecho as $valor){
            $PMuySatisfecho = $valor->porcentaje;
        }

        setlocale(LC_ALL, 'es_ES');
        $fechaActual = date('M - Y');
        // $mesActual  = date('M - y', strtotime($fechaActual));
        $mesCreacion = date('M', strtotime($fechaActual));
        $anio = date('y', strtotime($fechaActual));
        $year = date('Y', strtotime($fechaActual));
        $meses_ES = array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");
        $meses_EN = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
        $nombreMes = str_replace($meses_EN, $meses_ES, $mesCreacion);

        $mesActual              = $nombreMes;
        $YearActual             = (int)$anio;
        $guardarMes             = Tickets::GuardarMes($mesActual,$anio);
        Tickets::GuardarMesUsuarioUpd($creadoPor);
        $buscarGestion                  = Tickets::buscarGestion();
        $buscarGestionTotal             = Tickets::buscarGestionTotal();
        $buscarGestionSede              = Tickets::buscarGestionSede();
        $buscarGestionTotalSede         = Tickets::buscarGestionTotalSede();
        $buscarGestionCalificacion      = Tickets::buscarGestionCalificacion();
        $buscarGestionTotalCalificacion = Tickets::buscarGestionTotalCalificacion();
        Tickets::GuardarMesUsuario($mesActual,$creadoPor,$anio);

        foreach($buscarGestionTotal as $row){
            $totalGestion = (int)$row->total;
        }
        foreach($buscarGestionTotalSede as $row){
            $totalGestionSede = (int)$row->total;
        }
        foreach($buscarGestionTotalCalificacion as $row){
            $totalGestionCalificacion = (int)$row->total;
        }

        $resultado_consulta = array();
        $cont = 0;

        if($totalGestion > 0){
            $resultado_gestion = array();
            $contadorGestion = count($buscarGestion);
            $contG = 0;
            foreach($buscarGestion as $consulta){
                    $categoria = (int)$consulta->category_id;
                    switch($categoria){
                        Case 1  :   $color = '#8B103E';
                                    break;
                        Case 2  :   $color = '#FE9129';
                                    break;
                        Case 3  :   $color = '#64D9A8';
                                    break;
                        Case 4  :   $color = '#33B2E3';
                                    break;
                        default :   $color = '#000000';
                                    break;
                    }
                    $resultado_gestion[$contG]['nombre']        = $consulta->nombre_usuario;
                    $resultado_gestion[$contG]['color']         = $color;
                    $resultado_gestion[$contG]['desarrollo']    = $consulta->desarrollo;
                    $resultado_gestion[$contG]['pendientes']    = $consulta->pendientes;
                    $resultado_gestion[$contG]['terminados']    = $consulta->terminados;
                    $resultado_gestion[$contG]['cancelados']    = $consulta->cancelados;

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
        if($totalGestionSede > 0){
            $resultado_gestionS = array();
            $contadorGestionS = count($buscarGestionSede);
            $contGS = 0;
            foreach($buscarGestionSede as $consulta){
                    $resultado_gestionS[$contGS]['nombre']= $consulta->nombre_sede;
                    $resultado_gestionS[$contGS]['incidentes']= $consulta->incidentes;
                    $resultado_gestionS[$contGS]['requerimientos']= $consulta->requerimientos;

                    if($contGS >= ($contadorGestionS-1)){
                        $resultado_gestionS[$contGS]['separador']= '';
                    }else{
                        $resultado_gestionS[$contGS]['separador']= ',';
                    }
                    $contGS++;
            }
        }else{
            $resultado_gestionS = null;
        }
        if($totalGestionCalificacion > 0){
            $resultado_gestionC = array();
            $contadorGestionC = count($buscarGestionCalificacion);
            $contGC = 0;
            foreach($buscarGestionCalificacion as $consulta){
                    $resultado_gestionC[$contGC]['nombre']      = $consulta->nombre;
                    $resultado_gestionC[$contGC]['total']       = $consulta->total;
                    $resultado_gestionC[$contGC]['porcentaje']  = $consulta->porcentaje;
                    $resultado_gestionC[$contGC]['color']       = $consulta->color;
                    if($contGC >= ($contadorGestionC-1)){
                        $resultado_gestionC[$contGC]['separador']= '';
                    }else{
                        $resultado_gestionC[$contGC]['separador']= ',';
                    }
                    $contGC++;
            }
        }else{
            $resultado_gestionC = null;
        }
        $buscarMes = Tickets::BuscarMes($anio);
        if($guardarMes === false){
            $resultado_consulta = null;
        }else{
            // $buscarMes = Tickets::BuscarMes();
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

        return view('admin.index',['EnDesarrollo' => $desarrolloT,'Pendientes' => $pendientesT,
                                   'Terminados' => $terminadosT,'Cancelados' => $canceladosT,
                                   'MesGraficas' => $resultado_consulta,'Infraestructura' => $InfraestructuraT,
                                   'Redes' => $RedesT,'Aplicaciones' => $AplicacionesT,'ServinteT' => $ServinteT,
                                   'Soporte' => $SoporteT,'Gestion' => $resultado_gestion,'GestionS' => $resultado_gestionS,
                                   'GestionC' => $resultado_gestionC,'MuyInsatisfecho' => $MuyInsatisfecho,'Insatisfecho' => $Insatisfecho,
                                   'Neutral' => $Neutral,'Satisfecho' => $Satisfecho,'MuySatisfecho' => $MuySatisfecho,
                                   'PMuyInsatisfecho' => $PMuyInsatisfecho,'PInsatisfecho' => $PInsatisfecho,
                                   'PNeutral' => $PNeutral,'PSatisfecho' => $PSatisfecho,'PMuySatisfecho' => $PMuySatisfecho]);

    }

    public function calificaciones(){
        $BuscarMInsatisfecho     = Tickets::BuscarMInsatisfecho();
        foreach($BuscarMInsatisfecho as $valor){
            $MuyInsatisfecho = $valor->total;
        }
        $BuscarInsatisfecho     = Tickets::BuscarInsatisfecho();
        foreach($BuscarInsatisfecho as $valor){
            $Insatisfecho = $valor->total;
        }
        $BuscarNeutral     = Tickets::BuscarNeutral();
        foreach($BuscarNeutral as $valor){
            $Neutral = $valor->total;
        }
        $BuscarSatisfecho     = Tickets::BuscarSatisfecho();
        foreach($BuscarSatisfecho as $valor){
            $Satisfecho = $valor->total;
        }
        $BuscarMSatisfecho     = Tickets::BuscarMSatisfecho();
        foreach($BuscarMSatisfecho as $valor){
            $MuySatisfecho = $valor->total;
        }

        $buscarGestionCalificacion      = Tickets::buscarGestionCalificacion();
        $buscarGestionTotalCalificacion = Tickets::buscarGestionTotalCalificacion();
        foreach($buscarGestionTotalCalificacion as $row){
            $totalGestionCalificacion = (int)$row->total;
        }
        if($totalGestionCalificacion > 0){
            $GestionC = array();
            $contadorGestionC = count($buscarGestionCalificacion);
            $contGC = 0;
            foreach($buscarGestionCalificacion as $consulta){
                    $GestionC[$contGC]['nombre']      = $consulta->nombre;
                    $GestionC[$contGC]['total']       = $consulta->total;
                    $GestionC[$contGC]['porcentaje']  = $consulta->porcentaje;
                    $GestionC[$contGC]['color']       = $consulta->color;
                    if($contGC >= ($contadorGestionC-1)){
                        $GestionC[$contGC]['separador']= '';
                    }else{
                        $GestionC[$contGC]['separador']= ',';
                    }
                    $contGC++;
            }
        }else{
            $GestionC = null;
        }
        $Calificaciones = Tickets::ListarCalificaciones();
        $ListarCalificaciones = array();
        $cont = 0;
        foreach($Calificaciones as $value){
            $ListarCalificaciones[$cont]['id']              = (int)$value->id;
            $ListarCalificaciones[$cont]['ticket']          = (int)$value->ticket;
            $IdTicket                                       = (int)$value->ticket;
            $BuscarInfoTicket                               = Tickets::BuscarTicket($IdTicket);
            foreach($BuscarInfoTicket as $row){
                $ListarCalificaciones[$cont]['usuario']     = $row->name_user;
                $IdAsignado                                 = $row->asigned_id;
                $ListarCalificaciones[$cont]['titulo']      = $row->title;
                $ListarCalificaciones[$cont]['descripcion'] = $row->description;
            }
            $Puntuacion                                     = (int)$value->puntuacion;
            $buscarTipoCalificacion                         = Tickets::ListarTipoCalificaciones($Puntuacion);
            foreach($buscarTipoCalificacion as $row){
                $ListarCalificaciones[$cont]['puntuacion']  = $row->name;
            }
            $BuscarInfoUsuario                              = Usuarios::BuscarNombre($IdAsignado);
            if($BuscarInfoUsuario){
                foreach($BuscarInfoUsuario as $row){
                    $ListarCalificaciones[$cont]['agente']  = $row->name;
                }
            }else{
                $ListarCalificaciones[$cont]['agente']      = 'Agente Mesa de Ayuda';
            }
            $ListarCalificaciones[$cont]['ip_client']       = $value->ip_client;
            $ListarCalificaciones[$cont]['user_name']       = $value->user_name;
            $ListarCalificaciones[$cont]['update_at']       = date('d/m/Y h:i A', strtotime($value->update_at));
            $cont++;
        }
        return view('admin.Calificaciones',['Gestion' => $GestionC,'MuyInsatisfecho' => $MuyInsatisfecho,'Insatisfecho' => $Insatisfecho,
                                            'Neutral' => $Neutral,'Satisfecho' => $Satisfecho,'MuySatisfecho' => $MuySatisfecho,
                                            'Calificaciones' => $ListarCalificaciones]);
    }



}
