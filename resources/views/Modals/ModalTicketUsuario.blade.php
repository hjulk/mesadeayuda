<div class="modal fade" id="modal-ticket-usuario">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Crear Ticket de Creación de Usuario</h4>
            </div>

            {!! Form::open(['url' => 'crearTicketUsuario', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}
            <div class="modal-body">
                <div class="box-body">
                    <legend class="subtitle2" style="color: rgba(162, 27, 37, 1);">DATOS USUARIO</legend>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-5">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Nombres y Apellidos</label>
                                {!! Form::text('nombres',$NombresCompletos,['class'=>'form-control','id'=>'nombres','required','placeholder'=>'Nombre Completo del usuario',]) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Identificación</label>
                                {!! Form::text('identificacion',$Identificacion,['class'=>'form-control','id'=>'identificacion','placeholder'=>'Identificación','required']) !!}
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Cargo</label>
                                {!! Form::text('cargo',$Cargo,['class'=>'form-control','id'=>'cargo','placeholder'=>'Cargo del Usuario','required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="exampleInputEmail1" class="col-sm-5 control-label">Sede</label>
                                {!! Form::select('sede',$Sede,null,['class'=>'form-control','id'=>'sede','onchange'=>'Area();','required']) !!}
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Área / Dependencia</label>
                                {{--  {!! Form::text('area',$Area,['class'=>'form-control','id'=>'area','required','placeholder'=>'Area o Dependencia del usuario']) !!}  --}}
                                {!! Form::select('area',$Areas,null,['class'=>'form-control','id'=>'area','required']) !!}
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Jefe Inmediato</label>
                                {!! Form::text('jefe',$Jefe,['class'=>'form-control','id'=>'jefe','required','placeholder'=>'Nombre del jefe inmediato']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Fecha Ingreso</label>
                                {!! Form::text('fechaIngreso',$FechaIngreso,['class'=>'form-control','id'=>'fechaIngreso','required','placeholder'=>'Fecha de Ingreso del Usuario']) !!}
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Correo Solicitante</label>
                                {!! Form::text('correoS',$CorreoFuncionario,['class'=>'form-control','id'=>'correoS','required','placeholder'=>'Correo(s) del solicitante']) !!}
                            </div>
                        </div>
                    </div>
                    <legend class="subtitle2" style="color: rgba(162, 27, 37, 1);">DATOS INGRESO INFRAESTRUCTURA</legend>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Cargo Nuevo?</label>
                                {!! Form::select('cargo_nuevo',$Opciones,null,['class'=>'form-control','id'=>'cargo_nuevo','required']) !!}
                            </div>
                            <div class="col-md-5">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Funcionario A Reemplazar</label>
                                {!! Form::text('funcionario',$Funcionario,['class'=>'form-control','id'=>'funcionario','placeholder'=>'Nombre del Funcionario a reemplazar']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Usuario en Dominio?</label>
                                {!! Form::select('usuario_dominio',$Opciones,null,['class'=>'form-control','id'=>'usuario_dominio']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Correo Electrónico?</label>
                                {!! Form::select('correo_electronico',$Opciones,null,['class'=>'form-control','id'=>'correo_electronico']) !!}
                            </div>
                            <div class="col-md-5">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Correo funcionario de reemplazar</label>
                                {!! Form::text('correo_funcionario',$Funcionario,['class'=>'form-control','id'=>'correo_funcionario','placeholder'=>'Correo del Funcionario a reemplazar']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Equipo de Computo?</label>
                                {!! Form::select('equipo_computo',$Equipo,null,['class'=>'form-control','id'=>'equipo_computo']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Acceso Carpeta Compartida</label>
                                {!! Form::text('acceso_carpeta',$Carpeta,['class'=>'form-control','id'=>'acceso_carpeta','placeholder'=>'URL o nombre carpeta compartida']) !!}
                            </div>
                        </div>
                    </div>
                    <legend class="subtitle2" style="color: rgba(162, 27, 37, 1);">DATOS INGRESO REDES Y COMUNICACIONES</legend>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Celular Corporativo?</label>
                                {!! Form::select('celular',$Opciones,null,['class'=>'form-control','id'=>'celular']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Datos?</label>
                                {!! Form::select('datos',$Opciones,null,['class'=>'form-control','id'=>'datos']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Minutos?</label>
                                {!! Form::select('minutos',$Opciones,null,['class'=>'form-control','id'=>'minutos']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Extensión Telefónica?</label>
                                {!! Form::select('extension_tel',$Opciones,null,['class'=>'form-control','id'=>'extension_tel']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Conectividad VPN?</label>
                                {!! Form::select('conectividad',$Opciones,null,['class'=>'form-control','id'=>'conectividad']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Nivel de Acceso a Internet</label>
                                {!! Form::select('acceso_internet',$Acceso,null,['class'=>'form-control','id'=>'acceso_internet']) !!}
                            </div>
                        </div>
                    </div>
                    <legend class="subtitle2" style="color: rgba(162, 27, 37, 1);">DATOS INGRESO APLICACIONES Y DESARROLLO</legend>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Servinté?</label>
                                {!! Form::select('app_85',$Opciones,null,['class'=>'form-control','id'=>'app_85']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Dinamica?</label>
                                {!! Form::select('app_dinamica',$Opciones,null,['class'=>'form-control','id'=>'app_dinamica']) !!}
                            </div>
                            <div class="col-md-5">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Otro Aplicativo</label>
                                {!! Form::text('otro_aplicativo',$Aplicativo,['class'=>'form-control','id'=>'otro_aplicativo','placeholder'=>'Otro(s) apicativo(s)']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Capacitación Servinté?</label>
                                {!! Form::select('cap_85',$Opciones,null,['class'=>'form-control','id'=>'cap_85']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Capacitación Dinamica?</label>
                                {!! Form::select('cap_dinamica',$Opciones,null,['class'=>'form-control','id'=>'cap_dinamica']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Observaciones de la Solicitud</label>
                                {!! Form::textarea('observaciones',$Observaciones,['class'=>'form-control','id'=>'observaciones','placeholder'=>'Ingrese la observación sobre la solicitud','rows'=>'3']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Prioridad</label>
                                {!! Form::select('prioridad',$Prioridad,null,['class'=>'form-control','id'=>'prioridad']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Estado</label>
                                {!! Form::select('estado',$Estado,null,['class'=>'form-control','id'=>'estado']) !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-success">Crear Ticket</button>
            </div>
            {!!  Form::close() !!}

        </div>
    </div>
</div>
<!-- REVISIÓN -->
<div class="modal fade" id="modal-ticket-usuario-upd">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Crear Ticket de Creación de Usuario</h4>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <legend class="subtitle2" style="color: rgba(162, 27, 37, 1);">DATOS USUARIO</legend>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-5">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Nombres y Apellidos</label>
                                {!! Form::text('nombres_upd',$NombresCompletos,['class'=>'form-control','readonly','id'=>'mod_nombres_upd','placeholder'=>'Nombre Completo del usuario',]) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Identificación</label>
                                {!! Form::text('identificacion_upd',$Identificacion,['class'=>'form-control','readonly','id'=>'mod_identificacion_upd','placeholder'=>'Identificación','required']) !!}
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Cargo</label>
                                {!! Form::text('cargo_upd',$Cargo,['class'=>'form-control','readonly','id'=>'mod_cargo_upd','placeholder'=>'Cargo del Usuario','required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="exampleInputEmail1" class="col-sm-5 control-label">Sede</label>
                                {!! Form::select('sede_upd',$Sede,null,['class'=>'form-control','readonly','id'=>'mod_sede_upd','onchange'=>'sedeFunc();','required']) !!}
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Área / Dependencia</label>
                                {!! Form::text('area_upd',$Area,['class'=>'form-control','readonly','id'=>'mod_area_upd','placeholder'=>'Area o Dependencia del usuario']) !!}
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Jefe Inmediato</label>
                                {!! Form::text('jefe_upd',$Jefe,['class'=>'form-control','readonly','id'=>'mod_jefe_upd','placeholder'=>'Nombre del jefe inmediato']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Fecha Ingreso</label>
                                {!! Form::text('fechaIngreso_upd',$FechaIngreso,['class'=>'form-control','readonly','id'=>'mod_fechaIngreso_upd','placeholder'=>'Fecha de Ingreso del Usuario']) !!}
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Correo Solicitante</label>
                                {!! Form::text('correoS_upd',$CorreoFuncionario,['class'=>'form-control','readonly','id'=>'mod_correoS_upd','placeholder'=>'Correo(s) del solicitante']) !!}
                            </div>
                        </div>
                    </div>
                    <legend class="subtitle2" style="color: rgba(162, 27, 37, 1);">DATOS INGRESO INFRAESTRUCTURA</legend>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Cargo Nuevo?</label>
                                {!! Form::select('cargo_nuevo_upd',$Opciones,null,['class'=>'form-control','readonly','id'=>'mod_cargo_nuevo_upd','required']) !!}
                            </div>
                            <div class="col-md-5">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Funcionario A Reemplazar</label>
                                {!! Form::text('funcionario_upd',$Funcionario,['class'=>'form-control','readonly','id'=>'mod_funcionario_upd','placeholder'=>'Nombre del Funcionario a reemplazar']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Usuario en Dominio?</label>
                                {!! Form::select('usuario_dominio_upd',$Opciones,null,['class'=>'form-control','readonly','id'=>'mod_usuario_dominio_upd']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Correo Electrónico?</label>
                                {!! Form::select('correo_electronico_upd',$Opciones,null,['class'=>'form-control','readonly','id'=>'mod_correo_electronico_upd']) !!}
                            </div>
                            <div class="col-md-5">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Correo funcionario de reemplazar</label>
                                {!! Form::text('correo_funcionario_upd',$Funcionario,['class'=>'form-control','readonly','id'=>'mod_correo_funcionario_upd','placeholder'=>'Correo del Funcionario a reemplazar']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Equipo de Computo?</label>
                                {!! Form::select('equipo_computo_upd',$Equipo,null,['class'=>'form-control','readonly','id'=>'mod_equipo_computo_upd']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Acceso Carpeta Compartida</label>
                                {!! Form::text('acceso_carpeta_upd',$Carpeta,['class'=>'form-control','readonly','id'=>'mod_acceso_carpeta_upd','placeholder'=>'URL o nombre carpeta compartida']) !!}
                            </div>
                        </div>
                    </div>
                    <legend class="subtitle2" style="color: rgba(162, 27, 37, 1);">DATOS INGRESO REDES Y COMUNICACIONES</legend>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Celular Corporativo?</label>
                                {!! Form::select('celular_upd',$Opciones,null,['class'=>'form-control','readonly','id'=>'mod_celular_upd']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Datos?</label>
                                {!! Form::select('datos_upd',$Opciones,null,['class'=>'form-control','readonly','id'=>'mod_datos_upd']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Minutos?</label>
                                {!! Form::select('minutos_upd',$Opciones,null,['class'=>'form-control','readonly','id'=>'mod_minutos_upd']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Extensión Telefónica?</label>
                                {!! Form::select('extension_tel_upd',$Opciones,null,['class'=>'form-control','readonly','id'=>'mod_extension_tel_upd']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Conectividad VPN?</label>
                                {!! Form::select('conectividad_upd',$Opciones,null,['class'=>'form-control','readonly','id'=>'mod_conectividad_upd']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Nivel de Acceso a Internet</label>
                                {!! Form::select('acceso_internet_upd',$Acceso,null,['class'=>'form-control','readonly','id'=>'mod_acceso_internet_upd']) !!}
                            </div>
                        </div>
                    </div>
                    <legend class="subtitle2" style="color: rgba(162, 27, 37, 1);">DATOS INGRESO APLICACIONES Y DESARROLLO</legend>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Servinté?</label>
                                {!! Form::select('app_85_upd',$Opciones,null,['class'=>'form-control','readonly','id'=>'mod_app_85_upd']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Dinamica?</label>
                                {!! Form::select('app_dinamica_upd',$Opciones,null,['class'=>'form-control','readonly','id'=>'mod_app_dinamica_upd']) !!}
                            </div>
                            <div class="col-md-5">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Otro Aplicativo</label>
                                {!! Form::text('otro_aplicativo_upd',$Aplicativo,['class'=>'form-control','readonly','id'=>'mod_otro_aplicativo_upd','placeholder'=>'Otro(s) apicativo(s)']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Capacitación Servinté?</label>
                                {!! Form::select('cap_85_upd',$Opciones,null,['class'=>'form-control','readonly','id'=>'mod_cap_85_upd']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Capacitación Dinamica?</label>
                                {!! Form::select('cap_dinamica_upd',$Opciones,null,['class'=>'form-control','readonly','id'=>'mod_cap_dinamica_upd']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Observaciones de la Solicitud</label>
                                {!! Form::textarea('observaciones_upd',$Observaciones,['class'=>'form-control','readonly','id'=>'mod_observaciones_upd','placeholder'=>'Ingrese la observación sobre la solicitud','rows'=>'3']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Prioridad</label>
                                {!! Form::select('prioridad_upd',$Prioridad,null,['class'=>'form-control','readonly','id'=>'mod_prioridad_upd']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Estado</label>
                                {!! Form::select('estado_upd',$Estado,null,['class'=>'form-control','readonly','id'=>'mod_estado_upd']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Estado Creación R. y C.</label>
                                {!! Form::text('estadorc_upd',$Aplicativo,['class'=>'form-control','readonly','id'=>'mod_estadorc_upd','placeholder'=>'Estado RC']) !!}
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Estado Creación Infraestructura</label>
                                {!! Form::text('estadoit_upd',$Aplicativo,['class'=>'form-control','readonly','id'=>'mod_estadoit_upd','placeholder'=>'Estado It']) !!}
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Estado Creación Aplicaciones</label>
                                {!! Form::text('estadoapp_upd',$Aplicativo,['class'=>'form-control','readonly','id'=>'mod_estadoapp_upd','placeholder'=>'Estado APP']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                {{--  <button type="submit" class="btn btn-primary">Crear Ticket</button>  --}}
            </div>
        </div>
    </div>
</div>

    <script>
        function obtener_datos_ticket_usuario(id) {
            var Nombres                 = $("#nombres" + id).val();
            var Identificacion          = $("#identificacion" + id).val();
            var Cargo                   = $("#cargo" + id).val();
            var Sede                    = $("#id_sede" + id).val();
            var Area                    = $("#area" + id).val();
            var Jefe                    = $("#jefe" + id).val();
            var FechaIngreso            = $("#fecha_ingreso" + id).val();
            var Email                   = $("#email" + id).val();
            var NewCargo                = $("#new_cargo" + id).val();
            var FuncionarioReemplazo    = $("#funcionario_rem" + id).val();
            var CorreoFuncionario       = $("#correo_fun" + id).val();
            var NewEmail                = $("#new_email" + id).val();
            var Celular                 = $("#celular" + id).val();
            var Datos                   = $("#datos" + id).val();
            var Minutos                 = $("#minutos" + id).val();
            var Equipo                  = $("#equipo" + id).val();
            var Extension               = $("#extension" + id).val();
            var App85                   = $("#app85" + id).val();
            var AppDinamica             = $("#dinamica" + id).val();
            var OtraApp                 = $("#other_app" + id).val();
            var Carpeta                 = $("#carpeta" + id).val();
            var VPN                     = $("#vpn" + id).val();
            var Internet                = $("#internet" + id).val();
            var Cap85                   = $("#cap85" + id).val();
            var CapDinamica             = $("#capdinamica" + id).val();
            var Prioridad               = $("#prioridad" + id).val();
            var Estado                  = $("#estado" + id).val();
            var Observaciones           = $("#observaciones" + id).val();
            var UsuarioDominio          = $("#user_dominio" + id).val();
            var EstadoRC                = $("#estadorc" + id).val();
            var EstadoIT                = $("#estadoit" + id).val();
            var EstadoAPP               = $("#estadoapp" + id).val();

            $("#mod_idT").val(id);
            $("#mod_nombres_upd").val(Nombres);
            $("#mod_identificacion_upd").val(Identificacion);
            $("#mod_cargo_upd").val(Cargo);
            $("#mod_sede_upd").val(Sede);
            $("#mod_area_upd").val(Area);
            $("#mod_jefe_upd").val(Jefe);
            $("#mod_fechaIngreso_upd").val(FechaIngreso);
            $("#mod_correoS_upd").val(Email);
            $("#mod_cargo_nuevo_upd").val(NewCargo);
            $("#mod_funcionario_upd").val(FuncionarioReemplazo);
            $("#mod_usuario_dominio_upd").val(UsuarioDominio);
            $("#mod_correo_funcionario_upd").val(CorreoFuncionario);
            $("#mod_correo_electronico_upd").val(NewEmail);
            $("#mod_equipo_computo_upd").val(Equipo);
            $("#mod_acceso_carpeta_upd").val(Carpeta);
            $("#mod_celular_upd").val(Celular);
            $("#mod_datos_upd").val(Datos);
            $("#mod_minutos_upd").val(Minutos);
            $("#mod_extension_tel_upd").val(Extension);
            $("#mod_conectividad_upd").val(VPN);
            $("#mod_acceso_internet_upd").val(Internet);
            $("#mod_app_85_upd").val(App85);
            $("#mod_app_dinamica_upd").val(AppDinamica);
            $("#mod_otro_aplicativo_upd").val(OtraApp);
            $("#mod_cap_85_upd").val(Cap85);
            $("#mod_cap_dinamica_upd").val(CapDinamica);
            $("#mod_observaciones_upd").val(Observaciones);
            $("#mod_prioridad_upd").val(Prioridad);
            $("#mod_estado_upd").val(Estado);
            $("#mod_estadorc_upd").val(EstadoRC);
            $("#mod_estadoit_upd").val(EstadoIT);
            $("#mod_estadoapp_upd").val(EstadoAPP);
        }
    </script>
    <script>
        function Area() {
            var selectBox = document.getElementById("sede");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            var tipo = 'post';
            var select = document.getElementById("area");

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
