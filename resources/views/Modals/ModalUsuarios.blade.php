<div class="modal fade bs-example-modal-md-udpU" id="modal-usuarios-upd">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Actualizar Usuario</h4>
            </div>
            {!! Form::open(['action' => 'Admin\UsuarioController@actualizarUsuario', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off','id'=>'form-upd_user']) !!}

                <div class="modal-body">
                    <div class="box-body">
                <input type="hidden" name="idU" id="mod_idU">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="exampleInputEmail1">Nombre Completo</label>
                            {!! Form::text('nombre_usuario_upd',$NombreUsuario,['class'=>'form-control','id'=>'mod_nombre_usuario_upd','placeholder'=>'Nombre usuario']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1">Usuario</label>
                            {!! Form::text('username_upd',$UserName,['class'=>'form-control','id'=>'mod_username_upd','placeholder'=>'Usuario']) !!}
                        </div>
                        <div class="col-md-5">
                            <label for="exampleInputEmail1">Correo Electrónico</label>
                            {!! Form::email('email_upd',$Correo,['class'=>'form-control','id'=>'mod_email_upd','placeholder'=>'Correo elctrónico']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="exampleInputEmail1">Contraseña</label>
                            {!! Form::input('password','password_upd',$Contrasena,['class'=>'form-control','id'=>'mod_password_upd','placeholder'=>'Contraseña','type'=>'password']) !!}
                        </div>
                        <div class="col-md-4">
                            <label for="exampleInputEmail1">Rol</label>
                            {!! Form::select('id_rol_upd',$Rol,null,['class'=>'form-control','id'=>'mod_id_rol_upd']) !!}
                        </div>
                        <div class="col-md-4">
                            <label for="exampleInputEmail1">Categoria</label>
                            {!! Form::select('id_categoria_upd',$Categoria,null,['class'=>'form-control','id'=>'mod_id_categoria_upd']) !!}
                        </div>

                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-5">
                            <label for="exampleInputFile">Foto</label>
                            <input type="file" id="profile_pic_upd" name="profile_pic_upd" accept="image/*" size="5120">
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1">Activo</label>
                            {!! Form::select('id_activo_upd',$Activo,null,['class'=>'form-control','id'=>'mod_activo_upd']) !!}
                        </div>
                    </div>
                </div>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Actualizar Usuario</button>
                </div>
                {!!  Form::close() !!}
            </div>
        </div>
    </div>

</div>
<div class="modal fade bs-example-modal-md-updAdmin" id="modal-usuarios-upd">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Actualizar Usuario Admin</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['action' => 'Admin\UsuarioController@actualizarUsuarioAdmin', 'method' => 'post', 'enctype' => 'multipart/form-data','id'=>'form-admin-upd']) !!}
                <input type="hidden" name="idU" id="mod_idU">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="exampleInputEmail1">Nombre Completo</label>
                            <input type="text" class="form-control" id="nombre_usuario_amd" name="nombre_usuario_amd" placeholder="Nombre Completo" value="{!! Session::get('NombreUsuario') !!}">
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1">Usuario</label>
                            <input type="text" class="form-control" id="username_amd" name="username_amd" placeholder="Usuario" value="{!! Session::get('UserName') !!}">
                        </div>
                        <div class="col-md-5">
                            <label for="exampleInputEmail1">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email_amd" name="email_amd" placeholder="Correo Electrónico" value="{!! Session::get('Email') !!}">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1">Contraseña</label>
                            <input type="password" class="form-control" id="password_amd" name="password_amd" placeholder="Contraseña">
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1">Rol</label>
                            {!! Form::select('id_rol_amd',$Rol,$RolAdmin,['class'=>'form-control','id'=>'id_rol_amd','readonly']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1">Area</label>
                            {!! Form::select('id_categoria_amd',$Categoria,$CategoriaAdmin,['class'=>'form-control','id'=>'id_categoria_amd']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputFile">Foto</label>
                            <input type="file" id="profile_pic_amd" name="profile_pic_amd" accept="image/*" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-success pull-right">Actualizar Usuario</button>
                </div>
                {!!  Form::close() !!}
            </div>
        </div>
    </div>

</div>
    <script>

        function obtener_datos_usuario(id) {
            var nombre      = $("#nombre" + id).val();
            var username    = $("#username" + id).val();
            var email       = $("#email" + id).val();
            var rol         = $("#id_rol" + id).val();
            var categoria   = $("#id_categoria" + id).val();
            var activo      = $("#activo" + id).val();

            $("#mod_idU").val(id);
            $("#mod_nombre_usuario_upd").val(nombre);
            $("#mod_username_upd").val(username);
            $("#mod_email_upd").val(email);
            $("#mod_id_rol_upd").val(rol);
            $("#mod_id_categoria_upd").val(categoria);
            $("#mod_activo_upd").val(activo);

        }


    </script>
