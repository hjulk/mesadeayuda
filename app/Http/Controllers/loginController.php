<?php

namespace App\Http\Controllers;

use App\Http\Controllers\User\UsuariosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Usuarios;
use App\Models\HelpDesk\Tickets;
use App\Models\Admin\Sedes;
use App\Models\User\ControlCambios;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class loginController extends Controller{

    public function index()
    {
        return view('login');
    }

    public function helpdesk()
    {
        return view('auth.login');
    }

    public function Acceso(Request $request){

        $validator = Validator::make($request->all(), [
            'user' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('/')->withErrors($validator)->withInput();
        }else{

            $Usuario    = $request->user;
            $Password   = $request->password;
            $clave      = hash('sha512', $Password);

            $consultarUsuario = Usuarios::BuscarUser($Usuario);

            if($consultarUsuario){
                $consultarLogin = Usuarios::BuscarPass($Usuario,$clave);
                if($consultarLogin){

                    foreach($consultarLogin as $value){
                        $IdUsuario          = (int)$value->id;
                        $nombreUsuario      = $value->name;
                        $emailUsuario       = $value->email;
                        $userName           = $value->username;
                        $profile_pic        = $value->profile_pic;
                        $estado             = (int)$value->is_active;
                        $idRol              = (int)$value->rol_id;
                        $idCategoria        = (int)$value->category_id;
                        $idSede             = (int)$value->kind;
                        $fechaInicio        = $value->created_at;
                    }

                    If($estado === 1){
                        $Rol    = Usuarios::BuscarNombreRol($idRol);
                        foreach($Rol as $valor){
                            $NombreRol = $valor->name;
                        }
                        $Categoria    = Usuarios::BuscarNombreCategoria($idCategoria);
                        foreach($Categoria as $valor){
                            $NombreCategoria = $valor->name;
                        }
                        $Sede    = Usuarios::BuscarNombreSede($idSede);
                        foreach($Sede as $valor){
                            $NombreSede = $valor->name;
                        }

                        setlocale(LC_ALL, 'es_ES');
                        // $fechaCreacion = date('F, Y', strtotime($fechaInicio));
                        $mesCreacion = date('F', strtotime($fechaInicio));
                        $anio = date('Y', strtotime($fechaInicio));
                        $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                        $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
                        $nombreMes = str_replace($meses_EN, $meses_ES, $mesCreacion);

                        $fechaCreacion = $nombreMes.', '.$anio;

                        $fotoPerfil = "<img src='../assets/dist/img/profiles/$profile_pic' class='img-circle' alt='User Image'>";
                        $fotoMenu   = "<img src='../assets/dist/img/profiles/$profile_pic' class='user-image' alt='User Image'>";
                        $fotoPerfilM = "<img src='./assets/dist/img/profiles/$profile_pic' class='img-circle' alt='User Image'>";
                        $fotoMenuM   = "<img src='./assets/dist/img/profiles/$profile_pic' class='user-image' alt='User Image'>";
                        $fotoUser   = "<img src='../assets/dist/img/profiles/$profile_pic' class='profile-user-img img-responsive img-circle' alt='User profile picture' style='width: 40%;border-radius: 40% !important;'>";

                        $notificaciones = Tickets::Notificaciones($IdUsuario);
                        $contadorNotificacion = count($notificaciones);
                        if($contadorNotificacion > 0){
                            $listarNotificaciones = array();
                        $contN = 0;
                        foreach($notificaciones as $noti){
                            $UserCreador = $noti->usuario1;
                            $usuarios = Usuarios::BuscarNombre($UserCreador);
                            foreach($usuarios as $user){
                                $UsuarioN = $user->name;
                            }
                            $listarNotificaciones[$contN]['creador'] = $UsuarioN;
                            $contN++;
                        }
                        }else{
                            $contadorNotificacion = 0;
                            $listarNotificaciones = null;
                        }

                        Session::put('IdUsuario', $IdUsuario);
                        Session::put('NombreUsuario', $nombreUsuario);
                        Session::put('UserName', $userName);
                        Session::put('Rol', $idRol);
                        Session::put('Sede', $idSede);
                        Session::put('Email', $emailUsuario);
                        Session::put('Activo', $estado);
                        Session::put('NombreSede', $NombreSede);
                        Session::put('NombreCategoria', $NombreCategoria);
                        Session::put('NombreRol', $NombreRol);
                        Session::put('Categoria', $idCategoria);
                        Session::put('ProfilePicMenu', $fotoMenu);
                        Session::put('ProfilePic', $fotoPerfil);
                        Session::put('ProfilePicMenuM', $fotoMenuM);
                        Session::put('ProfilePicM', $fotoPerfilM);
                        Session::put('ProfileUser', $fotoUser);
                        Session::put('FechaCreacion', $fechaCreacion);
                        Session::put('Notificaciones', $contadorNotificacion);
                        Session::put('Notificacion', $listarNotificaciones);
                        Session::save();
                        // return \Response::json(['valido'=>'true','rol'=>$rol]);
                        $usuario = Session::get('NombreUsuario');
                        if(($idRol === 1)){
                            return redirect()->route('admin/dashboard');
                        }else if($idRol === 6){
                            return redirect()->route('dashboardMonitoreo');
                        }else{
                            return redirect()->route('user/dashboard');
                        }
                        // return view('user.index',['NombreUsuario' => $nombreUsuario]);

                    }else{
                        $verrors = array();
                        array_push($verrors, 'Usuario inactivo');
                        // return \Response::json(['valido'=>'false','errors'=>$verrors]);
                        return redirect('/')->withErrors(['errors' => $verrors]);
                    }
                }else{
                    $verrors = array();
                    array_push($verrors, 'Contraseña erronea');
                    // return \Response::json(['valido'=>'false','errors'=>$verrors]);
                    return redirect('/')->withErrors(['errors' => $verrors]);
                }

            }else{
                $verrors = array();
                array_push($verrors, 'Usuario '.$Usuario.' no existe');
                // return \Response::json(['valido'=>'false','errors'=>$verrors]);
                return redirect('/')->withErrors(['errors' => $verrors]);
            }
        }
    }

    public function AccesoUsuario(Request $request){

        $validator = Validator::make($request->all(), [
            'user'                  => 'required',
            'password'              => 'required',
            'g-recaptcha-response' => 'required|captcha'
        ]);

        if ($validator->fails()) {
            return redirect('/crearSolicitud')->withErrors($validator)->withInput();
        }else{

            $Usuario    = $request->user;
            $Password   = $request->password;
            $clave      = hash('sha512', $Password);

            $consultarUsuario = Usuarios::BuscarUserFinal($Usuario);

            if($consultarUsuario){
                $consultarLogin = Usuarios::BuscarPassFinal($Usuario,$clave);
                if($consultarLogin){

                    foreach($consultarLogin as $value){
                        $IdUsuario          = (int)$value->id;
                        $nombreUsuario      = $value->nombre;
                        $emailUsuario       = $value->email;
                        $userName           = $value->username;
                        $foto               = $value->foto;
                        $estado             = (int)$value->activo;
                        $idArea             = (int)$value->area;
                        $idSede             = (int)$value->sede;
                        $fechaInicio        = $value->fecha_creacion;
                    }

                    if($foto){
                        $profile_pic = $foto;
                    }else{
                        $profile_pic = 'default.jpg';
                    }

                    $idRol = 0;

                    If($estado === 1){
                        $Area    = Sedes::BuscarAreaId($idArea);
                        foreach($Area as $valor){
                            $NombreArea = $valor->name;
                        }
                        $Sede    = Usuarios::BuscarNombreSede($idSede);
                        foreach($Sede as $valor){
                            $NombreSede = $valor->name;
                        }

                        setlocale(LC_ALL, 'es_ES');
                        // $fechaCreacion = date('F, Y', strtotime($fechaInicio));
                        $mesCreacion = date('F', strtotime($fechaInicio));
                        $anio = date('Y', strtotime($fechaInicio));
                        $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                        $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
                        $nombreMes = str_replace($meses_EN, $meses_ES, $mesCreacion);

                        $fechaCreacion = $nombreMes.', '.$anio;

                        $fotoPerfil = "<img src='../assets/dist/img/profiles/$profile_pic' class='img-circle' alt='User Image'>";
                        $fotoMenu   = "<img src='../assets/dist/img/profiles/$profile_pic' class='user-image' alt='User Image'>";
                        $fotoPerfilM = "<img src='../assets/dist/img/profiles/$profile_pic' class='img-circle' alt='User Image'>";
                        $fotoMenuM   = "<img src='../assets/dist/img/profiles/$profile_pic' class='user-image' alt='User Image'>";
                        $fotoUser   = "<img src='../assets/dist/img/profiles/$profile_pic' class='profile-user-img img-responsive img-circle' alt='User profile picture' style='width: 40%;border-radius: 40% !important;'>";

                        Session::put('IdUsuario', $IdUsuario);
                        Session::put('NombreUsuario', $nombreUsuario);
                        Session::put('UserName', $userName);
                        Session::put('Area', $idArea);
                        Session::put('Sede', $idSede);
                        Session::put('Rol', $idRol);
                        Session::put('Email', $emailUsuario);
                        Session::put('Activo', $estado);
                        Session::put('NombreSede', $NombreSede);
                        Session::put('NombreArea', $NombreArea);
                        Session::put('ProfilePicMenu', $fotoMenu);
                        Session::put('ProfilePic', $fotoPerfil);
                        Session::put('ProfilePicMenuM', $fotoMenuM);
                        Session::put('ProfilePicM', $fotoPerfilM);
                        Session::put('ProfileUser', $fotoUser);
                        Session::put('FechaCreacion', $fechaCreacion);
                        Session::save();
                        $usuario = Session::get('NombreUsuario');
                        return redirect()->route('usuario/dashboard');

                    }else{
                        $verrors = array();
                        array_push($verrors, 'Usuario inactivo');
                        // return \Response::json(['valido'=>'false','errors'=>$verrors]);
                        return redirect('/crearSolicitud')->withErrors(['errors' => $verrors]);
                    }
                }else{
                    $verrors = array();
                    array_push($verrors, 'Contraseña erronea');
                    // return \Response::json(['valido'=>'false','errors'=>$verrors]);
                    return redirect('/crearSolicitud')->withErrors(['errors' => $verrors]);
                }

            }else{
                $verrors = array();
                array_push($verrors, 'Usuario '.$Usuario.' no existe');
                // return \Response::json(['valido'=>'false','errors'=>$verrors]);
                return redirect('/crearSolicitud')->withErrors(['errors' => $verrors]);
            }
        }
    }

    public function RecuperarContrasena(Request $request){
        $UserName    = $request->username;
        $UserEmail   = $request->correo;

        if(!empty($UserName) || !empty($UserEmail)){

            if(!empty($UserName) && empty($UserEmail)){

                $BuscarUsuario = Usuarios::BuscarUser($UserName);
                if($BuscarUsuario){
                    $cadena = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ()#.$@?';
                    $limite = strlen($cadena) - 1;
                    $b = '';
                    for ($i=0; $i < 8; $i++){
                        $b .= $cadena[rand(0, $limite)];
                    }
                    $nuevaContrasena = Hash('sha512',$b);
                    foreach($BuscarUsuario as $value){
                        $idUser = $value->id;
                        $emailUser = $value->email;
                        $usuario = $value->username;
                    }
                    $UpdatePassword = Usuarios::NuevaContrasena($idUser,$nuevaContrasena);
                    if($UpdatePassword){

                        $for = "$emailUser";
                        $subject = "Recuperación de Contraseña";
                        Mail::send('email/EmailRContrasena',
                                ['Contrasena' => $b,'NombreUser' => $UserName,'Usuario'=>$usuario],
                                function($msj) use($subject,$for){
                                    $msj->from("soporte.sistemas@cruzrojabogota.org.co","Mesa de Ayuda Tics");
                                    $msj->subject($subject);
                                    $msj->to($for);

                        });

                        $verrors = 'Se envio con exito la nueva contraseña al correo del usuario '.$UserName;
                        return redirect('/')->with('mensaje', $verrors);
                    }else{
                        $verrors = array();
                    array_push($verrors, 'Hubo un problema al recuperar la contraseña');
                    return redirect('/')->withErrors(['errors' => $verrors]);
                    }
                }else{
                    $verrors = array();
                    array_push($verrors, 'El usuario '.$UserName.' NO se encuentra en la base de datos');
                    return redirect('/')->withErrors(['errors' => $verrors]);
                }
            }else if(empty($UserName) && !empty($UserEmail)){
                $BuscarUsuario = Usuarios::BuscarUserEmail($UserEmail);
                if($BuscarUsuario){
                    $cadena = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ()#.$@?';
                    $limite = strlen($cadena) - 1;
                    $b = '';
                    for ($i=0; $i < 8; $i++){
                        $b .= $cadena[rand(0, $limite)];
                    }
                    $nuevaContrasena = Hash('sha512',$b);
                    foreach($BuscarUsuario as $value){
                        $idUser = $value->id;
                        $userName = $value->name;
                        $emailUser = $value->email;
                        $usuario = $value->username;
                    }
                    $UpdatePassword = Usuarios::NuevaContrasena($idUser,$nuevaContrasena);
                    if($UpdatePassword){
                        $for = "$emailUser";
                        $subject = "Recuperación de Contraseña";
                        Mail::send('email/EmailRContrasena',
                                ['Contrasena' => $b,'NombreUser' => $userName,'Usuario'=>$usuario],
                                function($msj) use($subject,$for){
                                    $msj->from("soporte.sistemas@cruzrojabogota.org.co","Mesa de Ayuda Tics");
                                    $msj->subject($subject);
                                    $msj->to($for);

                        });
                        $verrors = 'Se envio con exito la nueva contraseña al correo del usuario '.$UserName;
                        return redirect('/')->with('mensaje', $verrors);
                    }else{
                        $verrors = array();
                    array_push($verrors, 'Hubo un problema al recuperar la contraseña');
                    return redirect('/')->withErrors(['errors' => $verrors]);
                    }
                }else{
                    $verrors = array();
                    array_push($verrors, 'El correo '.$UserEmail.' NO se encuentra en la base de datos');
                    return redirect('/')->withErrors(['errors' => $verrors]);
                }
            }else if(!empty($UserName) && !empty($UserEmail)){
                $BuscarUsuario = Usuarios::RestablecerPassword($UserName,$UserEmail);
                if($BuscarUsuario){
                    $cadena = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ()#.$@?';
                    $limite = strlen($cadena) - 1;
                    $b = '';
                    for ($i=0; $i < 8; $i++){
                        $b .= $cadena[rand(0, $limite)];
                    }
                    $nuevaContrasena = Hash('sha512',$b);
                    foreach($BuscarUsuario as $value){
                        $idUser = $value->id;
                        $userName = $value->name;
                        $emailUser = $value->email;
                        $usuario = $value->username;
                    }
                    $UpdatePassword = Usuarios::NuevaContrasena($idUser,$nuevaContrasena);
                    if($UpdatePassword){
                        $for = "$emailUser";
                        $subject = "Recuperación de Contraseña";
                        Mail::send('email/EmailRContrasena',
                                ['Contrasena' => $b,'NombreUser' => $userName,'Usuario'=>$usuario],
                                function($msj) use($subject,$for){
                                    $msj->from("soporte.sistemas@cruzrojabogota.org.co","Mesa de Ayuda Tics");
                                    $msj->subject($subject);
                                    $msj->to($for);

                        });
                        $verrors = 'Se envio con exito la nueva contraseña al correo del usuario '.$UserName;
                        return redirect('/')->with('mensaje', $verrors);
                    }else{
                        $verrors = array();
                    array_push($verrors, 'Hubo un problema al recuperar la contraseña');
                    return redirect('/')->withErrors(['errors' => $verrors]);
                    }
                }else{
                    $verrors = array();
                    array_push($verrors, 'El usuario '.$UserName.' y correo '.$UserEmail.' NO se encuentra en la base de datos');
                    return redirect('/')->withErrors(['errors' => $verrors]);
                }
            }
        }else{
            $verrors = array();
            array_push($verrors, 'Debe diligenciar uno de los dos campos para continuar');
            return redirect('/')->withErrors(['errors' => $verrors]);
        }
    }

    public function RecuperarContrasenaUsuario(Request $request){
        $UserName    = $request->username;
        $UserEmail   = $request->correo;

        if(!empty($UserName) || !empty($UserEmail)){

            if(!empty($UserName) && empty($UserEmail)){

                $BuscarUsuario = Usuarios::BuscarUserFinal($UserName);
                if($BuscarUsuario){
                    $cadena = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ()#.$@?';
                    $limite = strlen($cadena) - 1;
                    $b = '';
                    for ($i=0; $i < 8; $i++){
                        $b .= $cadena[rand(0, $limite)];
                    }
                    $nuevaContrasena = Hash('sha512',$b);
                    foreach($BuscarUsuario as $value){
                        $idUser = $value->id;
                        $emailUser = $value->email;
                        $usuario = $value->username;
                    }
                    $UpdatePassword = Usuarios::NuevaContrasenaFinal($idUser,$nuevaContrasena);
                    if($UpdatePassword){

                        $for = "$emailUser";
                        $subject = "Recuperación de Contraseña";
                        Mail::send('email/EmailRContrasena',
                                ['Contrasena' => $b,'NombreUser' => $UserName,'Usuario'=>$usuario],
                                function($msj) use($subject,$for){
                                    $msj->from("soporte.sistemas@cruzrojabogota.org.co","Mesa de Ayuda Tics");
                                    $msj->subject($subject);
                                    $msj->to($for);

                        });

                        $verrors = 'Se envio con exito la nueva contraseña al correo del usuario '.$UserName;
                        return redirect('/crearSolicitud')->with('mensaje', $verrors);
                    }else{
                        $verrors = array();
                    array_push($verrors, 'Hubo un problema al recuperar la contraseña');
                    return redirect('/crearSolicitud')->withErrors(['errors' => $verrors]);
                    }
                }else{
                    $verrors = array();
                    array_push($verrors, 'El usuario '.$UserName.' NO se encuentra en la base de datos');
                    return redirect('/crearSolicitud')->withErrors(['errors' => $verrors]);
                }
            }else if(empty($UserName) && !empty($UserEmail)){
                $BuscarUsuario = Usuarios::BuscarUserEmailFinal($UserEmail);
                if($BuscarUsuario){
                    $cadena = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ()#.$@?';
                    $limite = strlen($cadena) - 1;
                    $b = '';
                    for ($i=0; $i < 8; $i++){
                        $b .= $cadena[rand(0, $limite)];
                    }
                    $nuevaContrasena = Hash('sha512',$b);
                    foreach($BuscarUsuario as $value){
                        $idUser = $value->id;
                        $userName = $value->nombre;
                        $emailUser = $value->email;
                        $usuario = $value->username;
                    }
                    $UpdatePassword = Usuarios::NuevaContrasenaFinal($idUser,$nuevaContrasena);
                    if($UpdatePassword){
                        $for = "$emailUser";
                        $subject = "Recuperación de Contraseña";
                        Mail::send('email/EmailRContrasena',
                                ['Contrasena' => $b,'NombreUser' => $userName,'Usuario'=>$usuario],
                                function($msj) use($subject,$for){
                                    $msj->from("soporte.sistemas@cruzrojabogota.org.co","Mesa de Ayuda Tics");
                                    $msj->subject($subject);
                                    $msj->to($for);

                        });
                        $verrors = 'Se envio con exito la nueva contraseña al correo del usuario '.$UserName;
                        return redirect('/crearSolicitud')->with('mensaje', $verrors);
                    }else{
                        $verrors = array();
                    array_push($verrors, 'Hubo un problema al recuperar la contraseña');
                    return redirect('/crearSolicitud')->withErrors(['errors' => $verrors]);
                    }
                }else{
                    $verrors = array();
                    array_push($verrors, 'El correo '.$UserEmail.' NO se encuentra en la base de datos');
                    return redirect('/crearSolicitud')->withErrors(['errors' => $verrors]);
                }
            }else if(!empty($UserName) && !empty($UserEmail)){
                $BuscarUsuario = Usuarios::RestablecerPasswordFinal($UserName,$UserEmail);
                if($BuscarUsuario){
                    $cadena = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ()#.$@?';
                    $limite = strlen($cadena) - 1;
                    $b = '';
                    for ($i=0; $i < 8; $i++){
                        $b .= $cadena[rand(0, $limite)];
                    }
                    $nuevaContrasena = Hash('sha512',$b);
                    foreach($BuscarUsuario as $value){
                        $idUser = $value->id;
                        $userName = $value->nombre;
                        $emailUser = $value->email;
                        $usuario = $value->username;
                    }
                    $UpdatePassword = Usuarios::NuevaContrasenaFinal($idUser,$nuevaContrasena);
                    if($UpdatePassword){
                        $for = "$emailUser";
                        $subject = "Recuperación de Contraseña";
                        Mail::send('email/EmailRContrasena',
                                ['Contrasena' => $b,'NombreUser' => $userName,'Usuario'=>$usuario],
                                function($msj) use($subject,$for){
                                    $msj->from("soporte.sistemas@cruzrojabogota.org.co","Mesa de Ayuda Tics");
                                    $msj->subject($subject);
                                    $msj->to($for);

                        });
                        $verrors = 'Se envio con exito la nueva contraseña al correo del usuario '.$UserName;
                        return redirect('/crearSolicitud')->with('mensaje', $verrors);
                    }else{
                        $verrors = array();
                    array_push($verrors, 'Hubo un problema al recuperar la contraseña');
                    return redirect('/crearSolicitud')->withErrors(['errors' => $verrors]);
                    }
                }else{
                    $verrors = array();
                    array_push($verrors, 'El usuario '.$UserName.' y correo '.$UserEmail.' NO se encuentra en la base de datos');
                    return redirect('/crearSolicitud')->withErrors(['errors' => $verrors]);
                }
            }
        }else{
            $verrors = array();
            array_push($verrors, 'Debe diligenciar uno de los dos campos para continuar');
            return redirect('/crearSolicitud')->withErrors(['errors' => $verrors]);
        }
    }

    public function dashboardMonitoreo(){
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

        $buscarGestion                  = Tickets::buscarGestion();
        $buscarGestionTotal             = Tickets::buscarGestionTotal();
        $buscarGestionSede              = Tickets::buscarGestionSede();
        $buscarGestionTotalSede         = Tickets::buscarGestionTotalSede();
        $buscarGestionCalificacion      = Tickets::buscarGestionCalificacion();
        $buscarGestionTotalCalificacion = Tickets::buscarGestionTotalCalificacion();

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

        return view('indexMonitoreo',['EnDesarrollo' => $desarrolloT,'Pendientes' => $pendientesT,
                                   'Terminados' => $terminadosT,'Cancelados' => $canceladosT,
                                   'MesGraficas' => $resultado_consulta,'Infraestructura' => $InfraestructuraT,
                                   'Redes' => $RedesT,'Aplicaciones' => $AplicacionesT,'ServinteT' => $ServinteT,
                                   'Soporte' => $SoporteT,'Gestion' => $resultado_gestion,'GestionS' => $resultado_gestionS,
                                   'GestionC' => $resultado_gestionC,'MuyInsatisfecho' => $MuyInsatisfecho,'Insatisfecho' => $Insatisfecho,
                                   'Neutral' => $Neutral,'Satisfecho' => $Satisfecho,'MuySatisfecho' => $MuySatisfecho,
                                   'PMuyInsatisfecho' => $PMuyInsatisfecho,'PInsatisfecho' => $PInsatisfecho,
                                   'PNeutral' => $PNeutral,'PSatisfecho' => $PSatisfecho,'PMuySatisfecho' => $PMuySatisfecho]);

    }

}
