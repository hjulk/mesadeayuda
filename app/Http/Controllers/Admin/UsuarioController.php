<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Funciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Models\Admin\Usuarios;
use App\Models\Admin\Sedes;
use Monolog\Handler\ZendMonitorHandler;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Admin\Activo;
use Illuminate\Support\Facades\Session;


class UsuarioController extends Controller
{
    public function index(){

        $Rol            = Usuarios::Rol();
        $Categoria      = Usuarios::Categoria();
        $Activo         = Usuarios::Activo();
        $NombreRol      = array();
        $NombreRol['']  = 'Seleccione: ';
        foreach ($Rol as $row){
            $NombreRol[$row->rol_id] = Funciones::eliminar_tildes_texto($row->name);
        }
        $NombreCategoria = array();
        $NombreCategoria[''] = 'Seleccione: ';
        foreach ($Categoria as $row){
            $NombreCategoria[$row->id] = Funciones::eliminar_tildes_texto($row->name);
        }
        $NombreActivo       = array();
        $NombreActivo['']   = 'Seleccione: ';
        foreach ($Activo as $row){
            $NombreActivo[$row->id] = Funciones::eliminar_tildes_texto($row->name);
        }

        $RolAdmin       = Session::get('Rol');
        $CategoriaAdmin = Session::get('Categoria');

        $Usuarios       = Usuarios::ListarUsuarios();
        $UsuariosIndex  = array();
        $contU = 0;
        foreach($Usuarios as $value){
            $UsuariosIndex[$contU]['id']                = $value->id;
            $UsuariosIndex[$contU]['nombre']            = Funciones::eliminar_tildes_texto($value->name);
            $UsuariosIndex[$contU]['username']          = $value->username;
            $UsuariosIndex[$contU]['email']             = $value->email;
            $UsuariosIndex[$contU]['profile_pic']       = $value->profile_pic;
            $UsuariosIndex[$contU]['fecha_creacion']    = date('d/m/Y h:i A', strtotime($value->created_at));
            $idrol              = $value->rol_id;
            $UsuariosIndex[$contU]['id_rol']        = $value->rol_id;
            $idcategoria        = $value->category_id;
            $UsuariosIndex[$contU]['id_categoria']  = $value->category_id;
            $idactivo           = $value->is_active;
            $UsuariosIndex[$contU]['activo']        = $value->is_active;
            $nombreRolS         = Usuarios::RolID($idrol);
            foreach($nombreRolS as $valor){
                $UsuariosIndex[$contU]['rol']       = $valor->name;
            }
            $nombreCategoriaS   = Usuarios::CategoriaID($idcategoria);
            foreach($nombreCategoriaS as $valor){
                $UsuariosIndex[$contU]['categoria'] = $valor->name;
            }
            $nombreActivoS      = Usuarios::ActivoID($idactivo);
            foreach($nombreActivoS as $valor){
                $UsuariosIndex[$contU]['estado']    = $valor->name;
            }
            $contU++;
        }

        return view('admin.usuarios',['Rol' => $NombreRol, 'Categoria' => $NombreCategoria, 'Usuarios' => $UsuariosIndex,
                                        'Activo' => $NombreActivo,'NombreUsuario' => null,'UserName' => null,'Correo' => null,
                                        'Contrasena' => null,'RolAdmin' => $RolAdmin,'CategoriaAdmin' => $CategoriaAdmin]);
    }

    public function usuarioFinal(){
        $NombreArea     = array();
        $NombreArea[''] = 'Seleccione: ';
        $NombreSede     = array();
        $NombreSede[''] = 'Seleccione: ';
        $Sedes          = Sedes::Sedes();
        foreach ($Sedes as $row){
            $NombreSede[$row->id] = Funciones::eliminar_tildes_texto($row->name);
        }
        $Activo             = Usuarios::Activo();
        $NombreActivo       = array();
        $NombreActivo['']   = 'Seleccione: ';
        foreach ($Activo as $row){
            $NombreActivo[$row->id] = $row->name;
        }
        $Usuarios       = Usuarios::ListarUsuarioFinal();
        $UsuariosFinal  = array();
        $contUF         = 0;
        foreach($Usuarios as $value){
            $UsuariosFinal[$contUF]['id']               = (int)$value->id;
            $UsuariosFinal[$contUF]['nombre']           = Funciones::eliminar_tildes_texto($value->nombre);
            $UsuariosFinal[$contUF]['username']         = $value->username;
            $UsuariosFinal[$contUF]['email']            = $value->email;
            $UsuariosFinal[$contUF]['cargo']            = $value->cargo;
            $UsuariosFinal[$contUF]['foto']             = $value->foto;
            $UsuariosFinal[$contUF]['fecha_creacion']   = date('d/m/Y h:i A', strtotime($value->fecha_creacion));
            $idSede         = (int)$value->sede;
            $UsuariosFinal[$contUF]['sede']             = (int)$value->sede;
            $idArea         = (int)$value->area;
            $UsuariosFinal[$contUF]['area']             = (int)$value->area;
            $idactivo       = (int)$value->activo;
            $UsuariosFinal[$contUF]['activo']           = $value->activo;
            $NombreSedeU    = Sedes::BuscarSedeID($idSede);
            foreach($NombreSedeU as $valor){
                $UsuariosFinal[$contUF]['nombresede']   = Funciones::eliminar_tildes_texto($valor->name);
            }
            $NombreAreaU    = Sedes::BuscarAreaId($idArea);
            foreach($NombreAreaU as $valor){
                $UsuariosFinal[$contUF]['nombrearea']   = Funciones::eliminar_tildes_texto($valor->name);
            }
            $NombreActivoU  = Usuarios::ActivoID($idactivo);
            foreach($NombreActivoU as $valor){
                $UsuariosFinal[$contUF]['estado']       = $valor->name;
            }
            $contUF++;
        }
        return view('admin.usuarioFinal',['Area' => $NombreArea,'Sede' => $NombreSede, 'UsuarioFinal' => $UsuariosFinal,
                                            'Activo' => $NombreActivo]);
    }

    public function crearUsuario(Request $request){

        $creadoPor          = (int)Session::get('IdUsuario');
        $validator = Validator::make($request->all(), [
            'nombre_usuario'    =>  'required',
            'username'          =>  'required',
            'email'             =>  'required|email',
            'password'          =>  'required',
            'id_rol'            =>  'required',
            'id_categoria'      =>  'required'
        ]);

        if ($validator->fails()) {
            return redirect('admin/usuarios')->withErrors($validator)->withInput();
        }else{

            $nombreUsuario  = $request->nombre_usuario;
            $userName       = $request->username;
            $email          = $request->email;
            $password       = $request->password;
            $contrasena     = hash('sha512', $password);
            $idrol          = $request->id_rol;
            $idcategoria    = $request->id_categoria;
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

            }

            $NombreFoto     = $filename;
            $consultarUsuario = Usuarios::BuscarUser($userName);
            if($consultarUsuario){
                $verrors = array();
                array_push($verrors, 'El usuario '.$userName.' ya se encuentra creado');
                // return redirect('admin/usuarios')->withErrors(['errors' => $verrors])->withRequest();
                return Redirect::to('admin/usuarios')->withErrors(['errors' => $verrors])->withRequest();
            }else{
                $crearUsuario = Usuarios::CrearUsuario($nombreUsuario,$userName,$email,$contrasena,$idrol,$idcategoria,$NombreFoto,$creadoPor);
                if($crearUsuario){
                    $verrors = 'Se creo con éxito el usuario '.$userName;
                    return redirect('admin/usuarios')->with('mensaje', $verrors);
                }else{
                    $verrors = array();
                    array_push($verrors, 'Hubo un problema al crear el usuario');
                    // return redirect('admin/usuarios')->withErrors(['errors' => $verrors])->withRequest();
                    return Redirect::to('admin/usuarios')->withErrors(['errors' => $verrors])->withRequest();
                }
            }

        }
    }

    public function actualizarUsuarioAdmin(Request $request){

        $creadoPor          = (int)Session::get('IdUsuario');
        $validator = Validator::make($request->all(), [
            'nombre_usuario_amd'    =>  'required',
            'username_amd'          =>  'required',
            'email_amd'             =>  'required|email',
            'id_rol_amd'            =>  'required',
            'id_categoria_amd'      =>  'required'
        ]);

        if ($validator->fails()) {
            return redirect('admin/usuarios')->withErrors($validator)->withInput();
        }else{

            $id             = (int)Session::get('IdUsuario');
            $nombreUsuario  = $request->nombre_usuario_amd;
            $userName       = $request->username_amd;
            $email          = $request->email_amd;
            $password       = $request->password_amd;
            $contrasena     = hash('sha512', $password);
            $idrol          = $request->id_rol_amd;
            $idcategoria    = $request->id_categoria_amd;


            if($password){

                $clave = $contrasena;
            }else{
                $consultarLogin = Usuarios::BuscarUser($userName);

                foreach($consultarLogin as $value){
                    $clave = $value->password;
                }
            }

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
                }

                $NombreFoto         = $filename;
                $ActualizarUsuario = Usuarios::ActualizarUsuarioAdmin($id,$nombreUsuario,$userName,$email,$clave,$NombreFoto,$creadoPor,$idrol,$idcategoria);

                if($ActualizarUsuario){
                    $verrors = 'Se actualizo con éxito el usuario '.$userName;
                    return redirect('admin/usuarios')->with('mensaje', $verrors);
                }else{
                    $verrors = array();
                    array_push($verrors, 'Hubo un problema al actualizar el usuario');
                    return redirect('admin/usuarios')->withErrors(['errors' => $verrors]);
                }
        }
    }

    public function actualizarUsuario(Request $request){

        $creadoPor          = (int)Session::get('IdUsuario');
        $validator = Validator::make($request->all(), [
            'nombre_usuario_upd'    =>  'required',
            'username_upd'          =>  'required',
            'email_upd'             =>  'required|email',
            'id_rol_upd'            =>  'required',
            'id_categoria_upd'      =>  'required',
            'id_activo_upd'         =>  'required'
        ]);

        if ($validator->fails()) {
            return redirect('admin/usuarios')->withErrors($validator)->withInput();
        }else{

            $id             = (int)$request->idU;
            $nombreUsuario  = $request->nombre_usuario_upd;
            $userName       = $request->username_upd;
            $email          = $request->email_upd;
            $password       = $request->password_upd;
            $contrasena     = hash('sha512', $password);
            $idrol          = $request->id_rol_upd;
            $idcategoria    = $request->id_categoria_upd;
            $idactivo       = (int)$request->id_activo_upd;

            if($password){

                $clave = $contrasena;
            }else{
                $consultarLogin = Usuarios::BuscarUser($userName);

                foreach($consultarLogin as $value){
                    $clave = $value->password;
                }
            }
            $destinationPath = null;
            $filename        = null;
            if ($request->hasFile('profile_pic_upd')) {
                $file            = $request->file('profile_pic_upd');
                $destinationPath = public_path().'/assets/dist/img/profiles';
                $extension       = $file->getClientOriginalExtension();
                $nombrearchivo   = str_replace(".", "_", $userName);
                $filename        = $nombrearchivo.'.'.$extension;
                $uploadSuccess   = $file->move($destinationPath, $filename);
                $archivofoto    = file_get_contents($uploadSuccess);
            }

            $NombreFoto         = $filename;
            $ActualizarUsuario = Usuarios::ActualizarUsuario($id,$nombreUsuario,$userName,$email,$clave,$idactivo,$idrol,$idcategoria,$NombreFoto,$creadoPor);

            if($ActualizarUsuario){
                $verrors = 'Se actualizo con éxito el usuario '.$userName;
                return redirect('admin/usuarios')->with('mensaje', $verrors);
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al actualizar el usuario');
                return redirect('admin/usuarios')->withErrors(['errors' => $verrors]);
            }
        }
    }

    public function actualizarUsuarioP(Request $request){
        $validator = Validator::make($request->all(), [
            'nombre_usuario'    =>  'required',
            'username'          =>  'required',
            'email'             =>  'required|email',
            'id_rol'            =>  'required',
            'id_categoria'      =>  'required',
            'id_activo'         =>  'required'
        ]);

        if ($validator->fails()) {
            return redirect('admin/usuarios')->withErrors($validator)->withInput();
        }else{

            $id             = (int)$request->idUP;
            $nombreUsuario  = $request->nombre_usuario;
            $userName       = $request->username;
            $email          = $request->email;
            $password       = $request->password1;
            $contrasena     = hash('sha512', $password);
            $idrol          = (int)$request->id_rol;
            $idcategoria    = (int)$request->id_categoria;
            $idactivo       = (int)$request->id_activo;

            if($password){

                $clave = $contrasena;
            }else{
                $consultarLogin = Usuarios::BuscarUser($userName);

                foreach($consultarLogin as $value){
                    $clave = $value->password;
                }
            }

                $destinationPath = null;
                $filename        = null;
                if ($request->hasFile('profile_pic')) {
                    $file            = $request->file('profile_pic');
                    $destinationPath = public_path().'/aplicativo/profile_pics';
                    $extension       = $file->getClientOriginalExtension();
                    $nombrearchivo   = str_replace(".", "_", $userName);
                    $filename        = $nombrearchivo.'.'.$extension;
                    $uploadSuccess   = $file->move($destinationPath, $filename);
                    $archivofoto    = file_get_contents($uploadSuccess);
                }

                $NombreFoto         = $filename;
                $ActualizarUsuario = Usuarios::ActualizarUsuarioP($id,$nombreUsuario,$userName,$email,$clave,$idactivo,$idrol,$idcategoria,$NombreFoto);

                if($ActualizarUsuario){
                    $verrors = 'Se actualizo con éxito el usuario '.$userName;
                    return redirect('admin/usuarios')->with('mensaje', $verrors);
                }else{
                    $verrors = array();
                    array_push($verrors, 'Hubo un problema al actualizar el usuario');
                    return redirect('admin/usuarios')->withErrors(['errors' => $verrors]);
                }
        }
    }

    public function crearUsuarioFinal(Request $request){

        $creadoPor          = (int)Session::get('IdUsuario');
        $validator = Validator::make($request->all(), [
            'nombre_usuario'    =>  'required',
            'username'          =>  'required',
            'email'             =>  'required|email',
            'password'          =>  'required',
            'sede'              =>  'required',
            'area'              =>  'required'
        ]);

        if ($validator->fails()) {
            return redirect('admin/usuarioFinal')->withErrors($validator)->withInput();
        }else{

            $nombreUsuario  = $request->nombre_usuario;
            $userName       = $request->username;
            $email          = $request->email;
            $Cargo          = $request->cargo;
            $password       = $request->password;
            $contrasena     = hash('sha512', $password);
            $Sede           = (int)$request->sede;
            $Area           = (int)$request->area;
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

            }
            $NombreFoto     = $filename;
            $consultarUsuario = Usuarios::BuscarUserFinal($userName);
            if($consultarUsuario){
                $verrors = array();
                array_push($verrors, 'El usuario '.$userName.' ya se encuentra creado');
                return Redirect::to('admin/usuarioFinal')->withErrors(['errors' => $verrors])->withRequest();
            }else{
                $crearUsuario = Usuarios::CrearUsuarioFinal($nombreUsuario,$userName,$email,$contrasena,$Sede,$Area,$Cargo,$NombreFoto,$creadoPor);
                if($crearUsuario){
                    $verrors = 'Se creo con éxito el usuario '.$userName;
                    return redirect('admin/usuarioFinal')->with('mensaje', $verrors);
                }else{
                    $verrors = array();
                    array_push($verrors, 'Hubo un problema al crear el usuario');
                    // return redirect('admin/usuarios')->withErrors(['errors' => $verrors])->withRequest();
                    return Redirect::to('admin/usuarioFinal')->withErrors(['errors' => $verrors])->withRequest();
                }
            }
        }
    }

    public function actualizarUsuarioFinal(Request $request){

        $creadoPor          = (int)Session::get('IdUsuario');
        $validator = Validator::make($request->all(), [
            'nombre_usuario_upd'    =>  'required',
            'username_upd'          =>  'required',
            'email_upd'             =>  'required|email',
            'id_activo_upd'         =>  'required'
        ]);

        if ($validator->fails()) {
            return redirect('admin/usuarioFinal')->withErrors($validator)->withInput();
        }else{

            $id             = (int)$request->idUF;
            $nombreUsuario  = $request->nombre_usuario_upd;
            $userName       = $request->username_upd;
            $email          = $request->email_upd;
            $Cargo          = $request->cargo_upd;
            $password       = $request->password_upd;
            $contrasena     = hash('sha512', $password);
            $idSede         = (int)$request->sede_upd;
            $idArea         = (int)$request->area_upd;
            $idactivo       = (int)$request->id_activo_upd;
            if($password){
                $clave = $contrasena;
            }else{
                $consultarLogin = Usuarios::BuscarUsuarioFinal($id);
                foreach($consultarLogin as $value){
                    $clave = $value->password;
                }
            }
            if($idArea){
                $Area = $idArea;
            }else{
                $consultarLogin = Usuarios::BuscarUsuarioFinal($id);
                foreach($consultarLogin as $value){
                    $Area = $value->area;
                }
            }
            if($idSede){
                $Sede = $idSede;
            }else{
                $consultarLogin = Usuarios::BuscarUsuarioFinal($id);
                foreach($consultarLogin as $value){
                    $Sede = $value->sede;
                }
            }
            $destinationPath = null;
            $filename        = null;
            if ($request->hasFile('profile_pic_upd')) {
                $file            = $request->file('profile_pic_upd');
                $destinationPath = public_path().'/assets/dist/img/profiles';
                $extension       = $file->getClientOriginalExtension();
                $nombrearchivo   = str_replace(".", "_", $userName);
                $filename        = $nombrearchivo.'.'.$extension;
                $uploadSuccess   = $file->move($destinationPath, $filename);
                $archivofoto    = file_get_contents($uploadSuccess);
            }

            $NombreFoto         = $filename;
            $ActualizarUsuarioFinal = Usuarios::ActualizarUsuarioFinal($id,$nombreUsuario,$userName,$email,$clave,$Sede,$Area,$Cargo,$idactivo,$NombreFoto,$creadoPor);
            if($ActualizarUsuarioFinal){
                $verrors = 'Se actualizo con éxito el usuario '.$userName;
                return redirect('admin/usuarioFinal')->with('mensaje', $verrors);
            }else{
                $verrors = array();
                array_push($verrors, 'Hubo un problema al actualizar el usuario');
                return redirect('admin/usuarioFinal')->withErrors(['errors' => $verrors]);
            }
        }
    }

}
