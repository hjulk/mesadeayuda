<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Models\Admin\Usuarios;
use App\Models\Admin\Sedes;
use Monolog\Handler\ZendMonitorHandler;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Admin\Activo;
use Illuminate\Support\Facades\Session;


class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Rol        = Usuarios::Rol();
        $Categoria  = Usuarios::Categoria();
        $Activo     = Usuarios::Activo();
        $NombreRol = array();
        $NombreRol[''] = 'Seleccione: ';
        foreach ($Rol as $row){
            $NombreRol[$row->rol_id] = $row->name;
        }
        $NombreCategoria = array();
        $NombreCategoria[''] = 'Seleccione: ';
        foreach ($Categoria as $row){
            $NombreCategoria[$row->id] = $row->name;
        }
        $NombreActivo = array();
        $NombreActivo[''] = 'Seleccione: ';
        foreach ($Activo as $row){
            $NombreActivo[$row->id] = $row->name;
        }

        $RolAdmin       = Session::get('Rol');
        $CategoriaAdmin = Session::get('Categoria');

        $Usuarios = Usuarios::ListarUsuarios();
        $UsuariosIndex = array();
        $contU = 0;
        foreach($Usuarios as $value){
            $UsuariosIndex[$contU]['id'] = $value->id;
            $UsuariosIndex[$contU]['nombre'] = $value->name;
            $UsuariosIndex[$contU]['username'] = $value->username;
            $UsuariosIndex[$contU]['email'] = $value->email;
            $UsuariosIndex[$contU]['profile_pic'] = $value->profile_pic;
            $UsuariosIndex[$contU]['fecha_creacion'] = date('d/m/Y h:i A', strtotime($value->created_at));
            $idrol = $value->rol_id;
            $UsuariosIndex[$contU]['id_rol'] = $value->rol_id;
            $idcategoria = $value->category_id;
            $UsuariosIndex[$contU]['id_categoria'] = $value->category_id;
            $idactivo = $value->is_active;
            $UsuariosIndex[$contU]['activo'] = $value->is_active;
            $nombreRolS = Usuarios::RolID($idrol);
            foreach($nombreRolS as $valor){
                $UsuariosIndex[$contU]['rol'] = $valor->name;
            }
            $nombreCategoriaS = Usuarios::CategoriaID($idcategoria);
            foreach($nombreCategoriaS as $valor){
                $UsuariosIndex[$contU]['categoria'] = $valor->name;
            }
            $nombreActivoS = Usuarios::ActivoID($idactivo);
            foreach($nombreActivoS as $valor){
                $UsuariosIndex[$contU]['estado'] = $valor->name;
            }
            $contU++;
        }

        return view('admin.usuarios',['Rol' => $NombreRol, 'Categoria' => $NombreCategoria, 'Usuarios' => $UsuariosIndex,
                                        'Activo' => $NombreActivo,'NombreUsuario' => null,'UserName' => null,'Correo' => null,
                                        'Contrasena' => null,'RolAdmin' => $RolAdmin,'CategoriaAdmin' => $CategoriaAdmin]);
    }

    public function usuarioFinal(){
        $NombreArea = array();
        $NombreArea[''] = 'Seleccione: ';
        $NombreSede = array();
        $NombreSede[''] = 'Seleccione: ';
        $Sedes  = Sedes::Sedes();
        foreach ($Sedes as $row){
            $NombreSede[$row->id] = $row->name;
        }
        $Activo     = Usuarios::Activo();
        $NombreActivo = array();
        $NombreActivo[''] = 'Seleccione: ';
        foreach ($Activo as $row){
            $NombreActivo[$row->id] = $row->name;
        }
        $Usuarios       = Usuarios::ListarUsuarioFinal();
        $UsuariosFinal  = array();
        $contUF         = 0;
        foreach($Usuarios as $value){
            $UsuariosFinal[$contUF]['id']               = (int)$value->id;
            $UsuariosFinal[$contUF]['nombre']           = $value->nombre;
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
                $UsuariosFinal[$contUF]['nombresede']   = $valor->name;
            }
            $NombreAreaU    = Sedes::BuscarAreaId($idArea);
            foreach($NombreAreaU as $valor){
                $UsuariosFinal[$contUF]['nombrearea']   = $valor->name;
            }
            $NombreActivoU  = Usuarios::ActivoID($idactivo);
            foreach($NombreActivoU as $valor){
                $UsuariosFinal[$contUF]['estado']        = $valor->name;
            }
            $contUF++;
        }
        // dd($UsuariosFinal);
        return view('admin.usuarioFinal',['Area' => $NombreArea,'Sede' => $NombreSede, 'UsuarioFinal' => $UsuariosFinal,
                                            'Activo' => $NombreActivo]);
    }

    public function inicio()
    {
        return view('admin.login');
    }

    public function crearUsuario(){

        $data = Request::all();
        $creadoPor          = (int)Session::get('IdUsuario');
        $reglas = array(
            'nombre_usuario'    =>  'required',
            'username'          =>  'required',
            'email'             =>  'required|email',
            'password'          =>  'required',
            'id_rol'            =>  'required',
            'id_categoria'      =>  'required'
        );
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $nombreUsuario  = Request::get('nombre_usuario');
            $userName       = Request::get('username');
            $email          = Request::get('email');
            $password       = Request::get('password');
            $contrasena     = hash('sha512', $password);
            $idrol          = Request::get('id_rol');
            $idcategoria    = Request::get('id_categoria');
            $destinationPath = null;
            $filename        = null;
            if (Request::hasFile('profile_pic')) {
                $file            = Request::file('profile_pic');
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

        }else{
            return Redirect::to('admin/usuarios')->withErrors(['errors' => $verrors])->withRequest();
        }

    }

    public function actualizarUsuarioAdmin(){
        $data = Request::all();
        $creadoPor          = (int)Session::get('IdUsuario');
        $reglas = array(
            'nombre_usuario_amd'    =>  'required',
            'username_amd'          =>  'required',
            'email_amd'             =>  'required|email',
            'id_rol_amd'            =>  'required',
            'id_categoria_amd'      =>  'required'
        );
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $id             = (int)Session::get('IdUsuario');
            $nombreUsuario  = Request::get('nombre_usuario_amd');
            $userName       = Request::get('username_amd');
            $email          = Request::get('email_amd');
            $password       = Request::get('password_amd');
            $contrasena     = hash('sha512', $password);
            $idrol          = Request::get('id_rol_amd');
            $idcategoria    = Request::get('id_categoria_amd');


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
                if (Request::hasFile('profile_pic')) {
                    $file            = Request::file('profile_pic');
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
        }else{
            return redirect('admin/usuarios')->withErrors(['errors' => $verrors]);
        }
    }

    public function actualizarUsuario(){
        $data = Request::all();
        $creadoPor          = (int)Session::get('IdUsuario');
        $reglas = array(
            'nombre_usuario_upd'    =>  'required',
            'username_upd'          =>  'required',
            'email_upd'             =>  'required|email',
            'id_rol_upd'            =>  'required',
            'id_categoria_upd'      =>  'required',
            'id_activo_upd'         =>  'required'
        );
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $id             = (int)Request::get('idU');
            $nombreUsuario  = Request::get('nombre_usuario_upd');
            $userName       = Request::get('username_upd');
            $email          = Request::get('email_upd');
            $password       = Request::get('password_upd');
            $contrasena     = hash('sha512', $password);
            $idrol          = Request::get('id_rol_upd');
            $idcategoria    = Request::get('id_categoria_upd');
            $idactivo       = (int)Request::get('id_activo_upd');

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
            if (Request::hasFile('profile_pic_upd')) {
                $file            = Request::file('profile_pic_upd');
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
        }else{
            return redirect('admin/usuarios')->withErrors(['errors' => $verrors]);
        }
    }

    public function actualizarUsuarioP(){
        $data = Request::all();
        $reglas = array(
            'nombre_usuario'    =>  'required',
            'username'          =>  'required',
            'email'             =>  'required|email',
            'id_rol'            =>  'required',
            'id_categoria'      =>  'required',
            'id_activo'         =>  'required'
        );
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $id             = (int)Request::get('idUP');
            $nombreUsuario  = Request::get('nombre_usuario');
            $userName       = Request::get('username');
            $email          = Request::get('email');
            $password       = Request::get('password1');
            $contrasena     = hash('sha512', $password);
            $idrol          = (int)Request::get('id_rol');
            $idcategoria    = (int)Request::get('id_categoria');
            $idactivo       = (int)Request::get('id_activo');

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
                if (Request::hasFile('profile_pic')) {
                    $file            = Request::file('profile_pic');
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
        }else{
            return redirect('admin/usuarios')->withErrors(['errors' => $verrors]);
        }
    }

    public function crearUsuarioFinal(){
        $data = Request::all();
        $creadoPor          = (int)Session::get('IdUsuario');
        $reglas = array(
            'nombre_usuario'    =>  'required',
            'username'          =>  'required',
            'email'             =>  'required|email',
            'password'          =>  'required',
            'sede'              =>  'required',
            'area'              =>  'required'
        );
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $nombreUsuario  = Request::get('nombre_usuario');
            $userName       = Request::get('username');
            $email          = Request::get('email');
            $Cargo          = Request::get('cargo');
            $password       = Request::get('password');
            $contrasena     = hash('sha512', $password);
            $Sede           = (int)Request::get('sede');
            $Area           = (int)Request::get('area');
            $destinationPath = null;
            $filename        = null;
            if (Request::hasFile('profile_pic')) {
                $file            = Request::file('profile_pic');
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
        }else{
            return redirect('admin/usuarioFinal')->withErrors(['errors' => $verrors]);
        }
    }

    public function actualizarUsuarioFinal(){
        $data = Request::all();
        $creadoPor          = (int)Session::get('IdUsuario');
        $reglas = array(
            'nombre_usuario_upd'    =>  'required',
            'username_upd'          =>  'required',
            'email_upd'             =>  'required|email',
            'id_activo_upd'         =>  'required'
        );
        $validador = Validator::make($data, $reglas);
        $messages = $validador->messages();
        foreach ($reglas as $key => $value){
            $verrors[$key] = $messages->first($key);
        }
        if($validador->passes()) {
            $id             = (int)Request::get('idUF');
            $nombreUsuario  = Request::get('nombre_usuario_upd');
            $userName       = Request::get('username_upd');
            $email          = Request::get('email_upd');
            $Cargo          = Request::get('cargo_upd');
            $password       = Request::get('password_upd');
            $contrasena     = hash('sha512', $password);
            $idSede         = (int)Request::get('sede_upd');
            $idArea         = (int)Request::get('area_upd');
            $idactivo       = (int)Request::get('id_activo_upd');
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
            if (Request::hasFile('profile_pic_upd')) {
                $file            = Request::file('profile_pic_upd');
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
        }else{
            return redirect('admin/usuarioFinal')->withErrors(['errors' => $verrors]);
        }
    }

}
