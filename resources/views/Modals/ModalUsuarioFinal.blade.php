<div class="modal fade bd-example-modal-xl" id="usuario" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Crear Usuario</h4>
            </div>
            {!! Form::open(['action' => 'Admin\UsuarioController@crearUsuarioFinal', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off','id'=>'form-user']) !!}
                <div class="modal-body">

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1">Nombre Completo</label>
                            {!! Form::text('nombre_usuario',null,['class'=>'form-control','id'=>'nombre_usuario','placeholder'=>'Nombre Completo']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1">Usuario</label>
                            {!! Form::text('username',null,['class'=>'form-control','id'=>'username','placeholder'=>'Usuario']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1">Correo Electrónico</label>
                            {!! Form::email('email',null,['class'=>'form-control','id'=>'email','placeholder'=>'Correo Electrónico']) !!}

                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1">Cargo</label>
                            {!! Form::text('cargo',null,['class'=>'form-control','id'=>'cargo','placeholder'=>'Cargo']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1">Contraseña</label>
                            {!! Form::input('password','password',null,['class'=>'form-control','id'=>'password','placeholder'=>'Contraseña','type'=>'password']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1">Sede</label>
                            {!! Form::select('sede',$Sede,null,['class'=>'form-control','id'=>'sede','onchange'=>'Area();']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1">Area</label>
                            {!! Form::select('area',$Area,null,['class'=>'form-control','id'=>'area']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputFile">Foto</label>
                            <input type="file" id="profile_pic" name="profile_pic" accept="image/*" size="5120" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Crear Usuario</button>
                </div>
                {!!  Form::close() !!}

        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-xl" id="usuario-final" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Actualizar Usuario</h4>
            </div>
            {!! Form::open(['action' => 'Admin\UsuarioController@actualizarUsuarioFinal', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off','id'=>'form-upd_user']) !!}
                <div class="modal-body">
                    <input type="hidden" name="idUF" id="mod_idUF">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1">Nombre Completo</label>
                                {!! Form::text('nombre_usuario_upd',null,['class'=>'form-control','id'=>'mod_nombre_usuario_upd','placeholder'=>'Asunto del Ticket']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1">Usuario</label>
                                {!! Form::text('username_upd',null,['class'=>'form-control','id'=>'mod_username_upd','placeholder'=>'Asunto del Ticket']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1">Correo Electrónico</label>
                                {!! Form::email('email_upd',null,['class'=>'form-control','id'=>'mod_email_upd','placeholder'=>'Asunto del Ticket']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1">Cargo</label>
                                {!! Form::text('cargo_upd',null,['class'=>'form-control','id'=>'mod_cargo','placeholder'=>'Asunto del Ticket']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1">Contraseña</label>
                                {!! Form::input('password','password_upd',null,['class'=>'form-control','id'=>'mod_password_upd','placeholder'=>'Contraseña','type'=>'password']) !!}
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1">Sede</label>
                                {!! Form::select('sede_upd',$Sede,null,['class'=>'form-control','id'=>'mod_sede','onchange'=>'AreaUpd();']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1">Area</label>
                                {!! Form::select('area_upd',$Area,null,['class'=>'form-control','id'=>'mod_area']) !!}
                            </div>
                            <div class="col-md-2">
                                <label for="exampleInputEmail1">Activo</label>
                                {!! Form::select('id_activo_upd',$Activo,null,['class'=>'form-control','id'=>'mod_activo_upd']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-5">
                                <label for="exampleInputFile">Foto</label>
                                <input type="file" id="profile_pic_upd" name="profile_pic_upd" accept="image/*" size="5120" class="form-control">
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
    <script>
        function AreaUpd() {
            var selectBox = document.getElementById("mod_sede");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            var tipo = 'post';
            var select = document.getElementById("mod_area");

            $.ajax({
                url: "{{route('buscarArea')}}",
                type: "get",
                data: {_method: tipo, id_sede: selectedValue},
                success: function (data) {
                    var vValido = data['valido'];

                    if (vValido === 'true') {
                        var ListUsuario = data['Usuario'];
                        select.options.length = 0;
                        for (index in ListUsuario) {
                            select.options[select.options.length] = new Option(ListUsuario[index], index);
                        }

                    }

                }
            });
        }
    </script>
    <script>

        function obtener_datos_usuario_final(id) {
            var Nombre      = $("#nombre" + id).val();
            var Username    = $("#username" + id).val();
            var Email       = $("#email" + id).val();
            var Sede        = $("#sede" + id).val();
            var Area        = $("#area" + id).val();
            var Cargo       = $("#cargo" + id).val();
            var Activo      = $("#activo" + id).val();

            $("#mod_idUF").val(id);
            $("#mod_nombre_usuario_upd").val(Nombre);
            $("#mod_username_upd").val(Username);
            $("#mod_email_upd").val(Email);
            $("#mod_sede").val(Sede);
            $("#mod_area").val(Area);
            $("#mod_cargo").val(Cargo);
            $("#mod_activo_upd").val(Activo);

        }


    </script>
