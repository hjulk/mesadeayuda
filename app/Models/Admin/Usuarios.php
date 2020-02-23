<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Usuarios extends Model
{
    protected $table = "user";
    public $timestamps = false;
    protected $fillable = array('id_user','username','name','email','password','profile_pic','active','rol_id','category_id','created_at','update_at');

    public static function BuscarUser($Usuario){
        $consulta = DB::Select("SELECT * FROM user WHERE username = '$Usuario'");
        // $consulta = DB::table('user')->where('username',$Usuario)->get();
        return $consulta;
    }

    public static function BuscarUserFinal($Usuario){
        $consulta = DB::Select("SELECT * FROM usuario_final WHERE username = '$Usuario'");
        // $consulta = DB::table('user')->where('username',$Usuario)->get();
        return $consulta;
    }

    public static function BuscarUsuarioFinal($Usuario){
        $consulta = DB::Select("SELECT * FROM usuario_final WHERE id = $Usuario");
        return $consulta;
    }

    public static function BuscarUserEmail($UserEmail){
        $consulta = DB::Select("SELECT * FROM user WHERE email = '$UserEmail'");
        // $consulta = DB::table('user')->where('username',$Usuario)->get();
        return $consulta;
    }

    public static function BuscarUserEmailFinal($UserEmail){
        $consulta = DB::Select("SELECT * FROM usuario_final WHERE email = '$UserEmail'");
        return $consulta;
    }

    public static function BuscarPass($Usuario,$clave){

        $consulta = DB::Select("SELECT * FROM user WHERE username = '$Usuario' AND password = '$clave'");
        // $consulta = DB::table('user')->where('username',$Usuario)->where('password',$clave)->get();
        return $consulta;
    }

    public static function BuscarPassFinal($Usuario,$clave){

        $consulta = DB::Select("SELECT * FROM usuario_final WHERE username = '$Usuario' AND password = '$clave'");
        // $consulta = DB::table('user')->where('username',$Usuario)->where('password',$clave)->get();
        return $consulta;
    }

    public static function CrearUsuario($nombreUsuario,$userName,$email,$contrasena,$idrol,$idcategoria,$NombreFoto,$creadoPor){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema = date('Y-m-d H:i');
        $fechaCreacion = date('Y-m-d H:i', strtotime($fecha_sistema));
        $crearUsuario = DB::insert('INSERT INTO user (username,name,email,password,profile_pic,is_active,kind,rol_id,category_id,created_at,creado_por)
                                    VALUES (?,?,?,?,?,?,?,?,?,?,?)',
                                    [$userName,$nombreUsuario,$email,$contrasena,$NombreFoto,1,1,$idrol,$idcategoria,$fechaCreacion,$creadoPor]);
        return $crearUsuario;
    }

    public static function CrearUsuarioFinal($nombreUsuario,$userName,$email,$contrasena,$Sede,$Area,$Cargo,$NombreFoto,$creadoPor){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema = date('Y-m-d H:i');
        $fechaCreacion = date('Y-m-d H:i', strtotime($fecha_sistema));
        $crearUsuario = DB::Insert('INSERT INTO usuario_final (username,nombre,email,password,cargo,foto,activo,sede,area,fecha_creacion,creado_por)
                                    VALUES (?,?,?,?,?,?,?,?,?,?,?)',
                                    [$userName,$nombreUsuario,$email,$contrasena,$Cargo,$NombreFoto,1,$Sede,$Area,$fechaCreacion,$creadoPor]);
        return $crearUsuario;
    }

    public static function ActualizarUsuario($id,$nombreUsuario,$userName,$email,$contrasena,$idactivo,$idrol,$idcategoria,$NombreFoto,$creadoPor){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema      = date('Y-m-d H:i');
        $fechaActualizacion = date('Y-m-d H:i', strtotime($fecha_sistema));
        DB::Update("UPDATE gestion SET activo = $idactivo WHERE id_user = $id");
        if($NombreFoto){
            $actualizarUsuario = DB::Update("UPDATE user SET username       = '$userName',
                                                            name            = '$nombreUsuario',
                                                            email           = '$email',
                                                            password        = '$contrasena',
                                                            profile_pic     = '$NombreFoto',
                                                            is_active       = $idactivo,
                                                            category_id     = $idcategoria,
                                                            rol_id          = $idrol,
                                                            updated_at      = '$fechaActualizacion',
                                                            actualizado_por = $creadoPor
                                                            WHERE id = $id");

        }else{
            $actualizarUsuario = DB::Update("UPDATE user SET username           = '$userName',
                                                                name            = '$nombreUsuario',
                                                                email           = '$email',
                                                                password        = '$contrasena',
                                                                is_active       = $idactivo,
                                                                category_id     = $idcategoria,
                                                                rol_id          = $idrol,
                                                                updated_at      = '$fechaActualizacion',
                                                                actualizado_por = $creadoPor
                                                                WHERE id = $id");
        }
        return $actualizarUsuario;
    }

    public static function ActualizarUsuarioFinal($id,$nombreUsuario,$userName,$email,$contrasena,$Sede,$Area,$Cargo,$idactivo,$NombreFoto,$creadoPor){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema      = date('Y-m-d H:i');
        $fechaActualizacion = date('Y-m-d H:i', strtotime($fecha_sistema));

        if($NombreFoto){
            $actualizarUsuario = DB::Update("UPDATE usuario_final SET username              = '$userName',
                                                                        nombre              = '$nombreUsuario',
                                                                        email               = '$email',
                                                                        password            = '$contrasena',
                                                                        foto                = '$NombreFoto',
                                                                        activo              = $idactivo,
                                                                        sede                = $Sede,
                                                                        area                = $Area,
                                                                        cargo               = '$Cargo',
                                                                        fecha_actualizacion = '$fechaActualizacion',
                                                                        actualizado_por     = $creadoPor
                                                                        WHERE id = $id");

        }else{
            $actualizarUsuario = DB::Update("UPDATE usuario_final SET username              = '$userName',
                                                                        nombre              = '$nombreUsuario',
                                                                        email               = '$email',
                                                                        password            = '$contrasena',
                                                                        activo              = $idactivo,
                                                                        sede                = $Sede,
                                                                        area                = $Area,
                                                                        cargo               = '$Cargo',
                                                                        fecha_actualizacion = '$fechaActualizacion',
                                                                        actualizado_por     = $creadoPor
                                                                        WHERE id = $id");
        }
        return $actualizarUsuario;
    }

    public static function ActualizarUsuarioP($id,$nombreUsuario,$userName,$email,$contrasena,$idactivo,$idrol,$idcategoria,$NombreFoto){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema      = date('Y-m-d H:i');
        $fechaActualizacion = date('Y-m-d H:i', strtotime($fecha_sistema));

        DB::Update("UPDATE gestion SET activo = $idactivo WHERE id_user = $id");

        if($NombreFoto){
            $actualizarUsuario = DB::Update("UPDATE user SET username = '$userName',
                                                            nombre = '$nombreUsuario',
                                                            email = '$email',
                                                            password = '$contrasena',
                                                            profile_pic = '$NombreFoto',
                                                            is_active = $idactivo,
                                                            id_categoria = $idcategoria,
                                                            id_rol = $idrol,
                                                            updated_at = '$fechaActualizacion'
                                                            WHERE id = $id");

        }else{
            $actualizarUsuario = DB::Update("UPDATE user SET username = '$userName',
                                                            nombre = '$nombreUsuario',
                                                            email = '$email',
                                                            password = '$contrasena',
                                                            is_active = $idactivo,
                                                            id_categoria = $idcategoria,
                                                            id_rol = $idrol,
                                                            updated_at = '$fechaActualizacion'
                                                            WHERE id = $id");
        }
        return $actualizarUsuario;
    }

    public static function ActualizarUsuarioAdmin($id,$nombreUsuario,$userName,$email,$contrasena,$NombreFoto,$creadoPor,$idrol,$idcategoria){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema      = date('Y-m-d H:i');
        $fechaActualizacion = date('Y-m-d H:i', strtotime($fecha_sistema));

        if($NombreFoto){
            $actualizarUsuario = DB::Update("UPDATE user SET username = '$userName',
                                                            name = '$nombreUsuario',
                                                            email = '$email',
                                                            password = '$contrasena',
                                                            profile_pic = '$NombreFoto',
                                                            updated_at = '$fechaActualizacion',
                                                            actualizado_por = $creadoPor,
                                                            rol_id = $idrol,
                                                            category_id = $idcategoria
                                                            WHERE id = $id");

        }else{
            $actualizarUsuario = DB::Update("UPDATE user SET username = '$userName',
                                                            name = '$nombreUsuario',
                                                            email = '$email',
                                                            password = '$contrasena',
                                                            updated_at = '$fechaActualizacion',
                                                            actualizado_por = $creadoPor,
                                                            rol_id = $idrol,
                                                            category_id = $idcategoria
                                                            WHERE id = $id");
        }
        return $actualizarUsuario;
    }

    public static function BuscarNombre($id_usuario){
        $consulta = DB::Select("SELECT * FROM user WHERE id = $id_usuario");
        return $consulta;
    }

    public static function BuscarNombreFinal($id_usuario){
        $consulta = DB::Select("SELECT * FROM usuario_final WHERE id = $id_usuario");
        return $consulta;
    }

    public static function BuscarNombreRol($id_rol){
        $consulta = DB::Select("SELECT name FROM rol WHERE rol_id = $id_rol");
        // $consulta = DB::table('user')->where('username',$Usuario)->get();
        return $consulta;
    }

    public static function BuscarNombreCategoria($id_categoria){
        $consulta = DB::Select("SELECT name FROM category WHERE id = $id_categoria");
        // $consulta = DB::table('user')->where('username',$Usuario)->get();
        return $consulta;
    }

    public static function BuscarNombreSede($id_sede){
        $consulta = DB::Select("SELECT name FROM project WHERE id = $id_sede");
        // $consulta = DB::table('user')->where('username',$Usuario)->get();
        return $consulta;
    }

    public static function Rol(){
        $Rol = DB::Select("SELECT * FROM rol WHERE activo = 1");
        return $Rol;
    }

    public static function Categoria(){
        $Categoria = DB::Select("SELECT * FROM category WHERE activo = 1");
        return $Categoria;
    }

    public static function RolID($id_rol){
        $Rol = DB::Select("SELECT * FROM rol WHERE rol_id = $id_rol");
        return $Rol;
    }

    public static function CategoriaID($id_categoria){
        $Categoria = DB::Select("SELECT * FROM category WHERE id = $id_categoria");
        return $Categoria;
    }

    public static function Activo(){
        $activo = DB::Select("SELECT * FROM activo");
        return $activo;
    }


    public static function ActivoID($id_activo){
        $activo = DB::Select("SELECT * FROM activo WHERE id = $id_activo");
        return $activo;
    }

    public static function ListarUsuarios(){
        $Usuarios = DB::Select("SELECT * FROM user ORDER BY name");
        return $Usuarios;
    }

    public static function ListarUsuarioFinal(){
        $Usuarios = DB::Select("SELECT * FROM usuario_final ORDER BY nombre");
        return $Usuarios;
    }

    public static function BuscarXCategoria($id_categoria){
        $activo = DB::Select("SELECT * FROM user WHERE category_id = $id_categoria AND is_active = 1 ORDER BY name");
        return $activo;
    }

    public static function BuscarXCategoriaSolicitud($id_categoria){
        $activo = DB::Select("SELECT * FROM tickets_recurrentes WHERE category_id = $id_categoria ORDER BY nombre");
        return $activo;
    }

    public static function BuscarXSede($id_sede){
        $activo = DB::Select("SELECT * FROM areas WHERE id_sede = $id_sede");
        return $activo;
    }

    public static function ActualizarProfile($Password,$idUsuario,$NombreFoto,$creadoPor){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema  = date('Y-m-d H:i');
        $fechaActualizacion  = date('Y-m-d H:i', strtotime($fecha_sistema));
        $updateProfile = DB::Update("UPDATE user SET password = '$Password',
                                            profile_pic = '$NombreFoto',
                                            updated_at = '$fechaActualizacion',
                                            actualizado_por = $creadoPor
                                            WHERE id = $idUsuario");
        return $updateProfile;
    }

    public static function RestablecerPassword($UserName,$UserEmail){
        $contrasena= DB::Select("SELECT * FROM user WHERE username = '$UserName' AND email = '$UserEmail'");
        return $contrasena;
    }

    public static function RestablecerPasswordFinal($UserName,$UserEmail){
        $contrasena= DB::Select("SELECT * FROM usuario_final WHERE username = '$UserName' AND email = '$UserEmail'");
        return $contrasena;
    }

    public static function NuevaContrasena($idUser,$nuevaContrasena){

        $contrasena = DB::Update("UPDATE user SET password = '$nuevaContrasena' WHERE id = $idUser");
        return $contrasena;
    }

    public static function NuevaContrasenaFinal($idUser,$nuevaContrasena){

        $contrasena = DB::Update("UPDATE usuario_final SET password = '$nuevaContrasena' WHERE id = $idUser");
        return $contrasena;
    }

    public static function UsuarioTicket($Categoria){
        $BuscarUsuario = DB::Select("SELECT * FROM user WHERE category_id = $Categoria AND rol_id = 3 AND is_active = 1");
        return $BuscarUsuario;
    }

    public static function UsuarioTicketBackup($Categoria){
        $BuscarUsuario = DB::Select("SELECT * FROM user WHERE category_id = $Categoria AND is_active = 1");
        return $BuscarUsuario;
    }

    public static function ListarTurnos(){
        $ListarTurnos = DB::Select("SELECT * FROM turnos");
        return $ListarTurnos;
    }

    public static function BuscarHorarioID($IdHorario){
        $BuscarHorarioID = DB::Select("SELECT * FROM horario WHERE id = $IdHorario");
        return $BuscarHorarioID;
    }

    public static function ListarHorarios(){
        $ListarHorarios = DB::Select("SELECT * FROM horario");
        return $ListarHorarios;
    }

    public static function ListarSedesTurno(){
        $ListarHorarios = DB::Select("SELECT * FROM project WHERE id IN (1,2,3) ORDER BY name ASC");
        return $ListarHorarios;
    }

    public static function ListarUsuariosTurno(){
        $ListarHorarios = DB::Select("SELECT * FROM user WHERE rol_id IN (3,4) ORDER BY name ASC");
        return $ListarHorarios;
    }

    public static function CreacionTurno($Agente,$FechaInicio,$FechaFin,$Sede,$Horario,$Disponibilidad){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema      = date('Y-m-d H:i');
        $fechaCreacion = date('Y-m-d H:i', strtotime($fecha_sistema));

        $CreacionTurno = DB::insert('INSERT INTO turnos (agente1,fecha_inicial,fecha_final,id_sede,id_horario,disponible,create_at)
                                        VALUES (?,?,?,?,?,?,?)',
                                        [$Agente,$FechaInicio,$FechaFin,$Sede,$Horario,$Disponibilidad,$fechaCreacion]);

        return $CreacionTurno;
    }

    public static function ActualizacionTurno($Agente,$FechaInicio,$FechaFin,$Sede,$Horario,$Disponibilidad,$IdTurno){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema      = date('Y-m-d H:i');
        $fechaActualizacion = date('Y-m-d H:i', strtotime($fecha_sistema));

        $ActualizacionTurno = DB::Update("UPDATE turnos SET
                                            agente1         = $Agente,
                                            fecha_inicial   = '$FechaInicio',
                                            fecha_final     = '$FechaFin',
                                            id_sede         = $Sede,
                                            id_horario      = $Horario,
                                            disponible      = '$Disponibilidad',
                                            update_at       = '$fechaActualizacion'
                                            WHERE id        = $IdTurno");

        return $ActualizacionTurno;
    }
}
