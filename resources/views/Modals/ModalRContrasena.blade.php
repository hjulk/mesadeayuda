<div class="modal fade bs-example-modal-md-udpR" id="modal-rcontrasena">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Recuperar Contraseña</h4>
            </div>

            {!! Form::open(['action' => 'loginController@RecuperarContrasena', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}
                <div class="modal-body">
                        <input type="hidden" name="idR" id="mod_idR">

                        <div class="wrap-input100" data-validate = "Usuario es requerido">
                                <input class="input100" type="text" name="username" id="username" placeholder="Usuario Login">
                                <span class="focus-input100"></span>
                                <span class="symbol-input100">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                </span>
                            </div>
                            <div class="wrap-input100" data-validate = "Usuario es requerido">
                                    <input class="input100" type="email" name="correo" id="correo" placeholder="Correo Usuario">
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                    </span>
                                </div>

                </div>
                <div class="modal-footer">

                    <button type="submit" class="btn btn-primary">Recuperar Contraseña</button>
                </div>
            {!!  Form::close() !!}
        </div>
    </div>

</div>
<div class="modal fade bs-example-modal-md-udpR" id="modal-rcontrasena-final">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Recuperar Contraseña</h4>
            </div>

            {!! Form::open(['action' => 'loginController@RecuperarContrasenaUsuario', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}
                <div class="modal-body">
                        <input type="hidden" name="idR" id="mod_idR">

                        <div class="wrap-input100" data-validate = "Usuario es requerido">
                                <input class="input100" type="text" name="username" id="username" placeholder="Usuario Login">
                                <span class="focus-input100"></span>
                                <span class="symbol-input100">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                </span>
                            </div>
                            <div class="wrap-input100" data-validate = "Usuario es requerido">
                                    <input class="input100" type="email" name="correo" id="correo" placeholder="Correo Usuario">
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                    </span>
                                </div>

                </div>
                <div class="modal-footer">

                    <button type="submit" class="btn btn-primary">Recuperar Contraseña</button>
                </div>
            {!!  Form::close() !!}
        </div>
    </div>

</div>
