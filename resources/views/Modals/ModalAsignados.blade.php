<div class="modal fade bd-example-modal-xl" id="modal-asignados" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title modal-title-primary">Asignar Equipo</h4>
            </div>
            {!! Form::open(['url' => 'ingresarAsignacion', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}
            <div class="modal-body">
                <legend class="subtitle2" style="color: rgba(162, 27, 37, 1);">DATOS QUIPO</legend>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Tipo Equipo</label>
                            {!! Form::Select('tipo_equipo',$Equipos,null,['class'=>'form-control','id'=>'tipo_equipo','required','onchange'=>'equipoFunc();','required']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Marca - Serial</label>
                            {!! Form::Select('marca_serial',$Marca,null,['class'=>'form-control','id'=>'marca_serial','required']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Mouse</label>
                            {!! Form::Select('mouse',$Mouse,null,['class'=>'form-control','id'=>'mouse']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Pantalla</label>
                            {!! Form::Select('pantalla',$Pantalla,null,['class'=>'form-control','id'=>'pantalla']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Teclado</label>
                            {!! Form::Select('teclado',$Teclado,null,['class'=>'form-control','id'=>'teclado']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Cargador</label>
                            {!! Form::Select('cargador',$Cargador,null,['class'=>'form-control','id'=>'cargador']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Requiere Guaya</label>
                            {!! Form::Select('opcion',$Opcion,null,['class'=>'form-control','id'=>'opcion','onChange'=>'mostrar(this.value);']) !!}
                        </div>
                        <div class="col-md-3" id="guaya_op" style="display: none;">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Guaya</label>
                            {!! Form::Select('guaya',$Guaya,null,['class'=>'form-control','id'=>'guaya']) !!}
                            <div class="row">
                                <div class="col-md-12" id="guaya_op1" style="display: none;">
                                    <input type="radio" id="tipo_guaya" name="tipo_guaya" value="1" onclick="code_guaya.disabled = false;id_guaya.disabled = false"> Con Clave&nbsp;&nbsp;&nbsp;
                                    <input type="radio" id="tipo_guaya" name="tipo_guaya" value="2" onclick="code_guaya.disabled = true;id_guaya.disabled = false"> Con Llave&nbsp;&nbsp;&nbsp;
                                    {!! Form::text('code_guaya',null,['class'=>'form-control','id'=>'code_guaya','placeholder'=>'Clave Guaya','disabled']) !!}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <legend class="subtitle2" style="color: rgba(162, 27, 37, 1);">DATOS USUARIO</legend>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3">
                            <label class="control-label col-sm-12" for="fname">Sede:</label>
                            {!! Form::select('sede',$Sede,null,['class'=>'form-control','id'=>'sede','onchange'=>'Area();','required']) !!}
                        </div>
                        <div class="col-sm-3">
                            <label class="control-label col-sm-12" for="fname">Área / Dependencia:</label>
                            {{--  {!! Form::text('dependencia',null,['class'=>'form-control','id'=>'dependencia','required','placeholder'=>'Área u oficina del usuario']) !!}  --}}
                            {!! Form::select('area',$Areas,null,['class'=>'form-control','id'=>'area','required']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Nombre Asignado</label>
                            {!! Form::text('nombre_asignado',null,['class'=>'form-control','id'=>'nombre_asignado','placeholder'=>'Nombre Usuario','required']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Cargo</label>
                            {!! Form::text('cargo',null,['class'=>'form-control','id'=>'cargo','placeholder'=>'Cargo Usuario','required']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Cédula</label>
                            {!! Form::text('cedula',null,['class'=>'form-control','id'=>'cedula','placeholder'=>'Cedula Usuario','required']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">No. Telefónico</label>
                            {!! Form::text('telefono',null,['class'=>'form-control','id'=>'telefono','placeholder'=>'Telefono Usuario','required']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Correo</label>
                            {!! Form::text('correo',null,['class'=>'form-control','id'=>'correo','placeholder'=>'Correo Usuario']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">No. Ticket</label>
                            {!! Form::text('ticket',null,['class'=>'form-control','id'=>'ticket','placeholder'=>'Ticket Asignacion']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Fecha Asignación</label>
                            {!! Form::text('fecha_asignacion',$FechaAsignacion,['class'=>'form-control','id'=>'fecha_asignacion','placeholder'=>'Fecha de Asignación']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Estado Asignación</label>
                            {!! Form::select('estado',$Estado,null,['class'=>'form-control','id'=>'estado']) !!}
                        </div>
                        <div class="col-md-6">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Evidencia</label>
                            <input type="file" id="evidencia[]" name="evidencia[]" class="form-control" multiple>
                            <div align="right"><small class="text-muted" style="font-size: 73%;">Tamaño maximo permitido (5MB), si se supera este tamaño, su archivo no será cargado.</small> <span id="cntDescripHechos" align="right"> </span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-success">Crear Asignación</button>
            </div>
            {!!  Form::close() !!}
        </div>
    </div>
</div>

<!-- ACTUALIZACIÓN -->

<div class="modal fade bd-example-modal-xl" id="modal-cambios-asignado" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title modal-title-primary">Asignar Equipo</h4>
            </div>
            {!! Form::open(['url' => 'actualizarAsignacion', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}
            <div class="modal-body">
                <input type="hidden" name="idA" id="mod_idA">
                <legend class="subtitle2" style="color: rgba(162, 27, 37, 1);">DATOS EQUIPO</legend>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Tipo Equipo</label>
                            {!! Form::Select('tipo_equipo_upd',$Equipos,null,['class'=>'form-control','id'=>'mod_tipo_equipo','required','onchange'=>'equipoFuncUpd();','required']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Marca - Serial</label>
                            {!! Form::Select('marca_serial_upd',$MarcaUpd,null,['class'=>'form-control','id'=>'mod_marca_serial']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Mouse</label>
                            {!! Form::Select('mouse_upd',$MouseUpd,null,['class'=>'form-control','id'=>'mod_mouse']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Pantalla</label>
                            {!! Form::Select('pantalla_upd',$PantallaUpd,null,['class'=>'form-control','id'=>'mod_pantalla']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Teclado</label>
                            {!! Form::Select('teclado_upd',$TecladoUpd,null,['class'=>'form-control','id'=>'mod_teclado']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Cargador</label>
                            {!! Form::Select('cargador_upd',$CargadorUpd,null,['class'=>'form-control','id'=>'mod_cargador']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Requiere Guaya</label>
                            {!! Form::Select('opcion_upd',$Opcion,null,['class'=>'form-control','id'=>'mod_opcion','onChange'=>'mostrarUpd(this.value);']) !!}
                        </div>
                        <div class="col-md-3" id="guaya_op_upd" style="display: none;">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Guaya</label>
                            {!! Form::Select('guaya_upd',$GuayaUpd,null,['class'=>'form-control','id'=>'mod_guaya']) !!}
                            <div class="row">
                                <div class="col-md-12" id="guaya_op1_upd" style="display: none;">
                                    <input type="radio" id="mod_tipo_guaya" name="tipo_guaya_upd" value="1" onclick="code_guaya_upd.disabled = false;id_guaya.disabled = false"> Con Clave&nbsp;&nbsp;&nbsp;
                                    <input type="radio" id="mod_tipo_guaya" name="tipo_guaya_upd" value="2" onclick="code_guaya_upd.disabled = true;id_guaya.disabled = false"> Con Llave&nbsp;&nbsp;&nbsp;
                                    {!! Form::text('code_guaya_upd',null,['class'=>'form-control','id'=>'mod_code_guaya','placeholder'=>'Clave Guaya','disabled']) !!}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <legend class="subtitle2" style="color: rgba(162, 27, 37, 1);">DATOS USUARIO</legend>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Sede</label>
                            {!! Form::Select('sede_upd',$Sede,null,['class'=>'form-control','id'=>'mod_sede','readonly']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Area</label>
                            {!! Form::text('area_upd',null,['class'=>'form-control','id'=>'mod_area','placeholder'=>'Area Usuario','readonly']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Nombre Asignado</label>
                            {!! Form::text('nombre_asignado_upd',null,['class'=>'form-control','id'=>'mod_nombre_asignado','placeholder'=>'Nombre Usuario']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Cargo</label>
                            {!! Form::text('cargo_upd',null,['class'=>'form-control','id'=>'mod_cargo','placeholder'=>'Cargo Usuario']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Cédula</label>
                            {!! Form::text('cedula_upd',null,['class'=>'form-control','id'=>'mod_cedula','placeholder'=>'Cedula Usuario']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">No. Telefónico</label>
                            {!! Form::text('telefono_upd',null,['class'=>'form-control','id'=>'mod_telefono','placeholder'=>'Telefono Usuario']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Correo</label>
                            {!! Form::text('correo_upd',null,['class'=>'form-control','id'=>'mod_correo','placeholder'=>'Correo Usuario']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">No. Ticket</label>
                            {!! Form::text('ticket_upd',null,['class'=>'form-control','id'=>'mod_ticket','placeholder'=>'Ticket Asignacion']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Fecha Asignación</label>
                            {!! Form::text('fecha_asignacion_upd',$FechaAsignacion,['class'=>'form-control','id'=>'mod_fecha_asignacion','placeholder'=>'Fecha de Asignación']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Estado Asignación</label>
                            {!! Form::select('estado_upd',$Estado,null,['class'=>'form-control','id'=>'mod_estado']) !!}
                        </div>
                        <div class="col-md-6">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Evidencia</label>
                            <input type="file" id="mod_evidencia[]" name="evidencia_upd[]" class="form-control" multiple="multiple" size="5120">
                            <div align="right"><small class="text-muted" style="font-size: 73%;">Tamaño maximo permitido (5MB), si se supera este tamaño, su archivo no será cargado.</small> <span id="cntDescripHechos" align="right"> </span></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Historial Consumible</label>
                            {!! Form::textarea('historial',null,['class'=>'form-control','id'=>'mod_historial','placeholder'=>'Historial del consumible','rows'=>'3','readonly']) !!}
                        </div>
                        <div class="col-md-6">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Agregar Comentario</label>
                            {!! Form::textarea('comentario',null,['class'=>'form-control','id'=>'comentario','placeholder'=>'Ingrese el comentario sobre la gestión del consumible','rows'=>'3']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <button type="button" id="VerAnexosA" class="btn btn-success">Ver Anexos</button>
                        </div>
                        <div class="col-md-5">
                            <div id="anexosA"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-success">Actualizar Asignación</button>
            </div>
            {!!  Form::close() !!}
        </div>
    </div>
</div>

    <script>
        function equipoFunc() {
            var selectBox = document.getElementById("tipo_equipo");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            var tipo = 'post';
            var select = document.getElementById("marca_serial");

            $.ajax({
                url: "{{route('buscarEquipo')}}",
                type: "get",
                data: {_method: tipo, tipo_equipo: selectedValue},
                success: function (data) {
                    var vValido = data['valido'];

                    if (vValido === 'true') {
                        var ListUsuario = data['Equipo'];
                        select.options.length = 0;
                        for (index in ListUsuario) {
                            select.options[select.options.length] = new Option(ListUsuario[index], index);
                        }

                    }

                }
            });
        }
        function equipoFuncUpd() {
            var selectBox = document.getElementById("mod_tipo_equipo");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            var tipo = 'post';
            var select = document.getElementById("mod_marca_serial");

            $.ajax({
                url: "{{route('buscarEquipo')}}",
                type: "get",
                data: {_method: tipo, tipo_equipo: selectedValue},
                success: function (data) {
                    var vValido = data['valido'];

                    if (vValido === 'true') {
                        var ListUsuario = data['Equipo'];
                        select.options.length = 0;
                        for (index in ListUsuario) {
                            select.options[select.options.length] = new Option(ListUsuario[index], index);
                        }
                        document.ready = document.getElementById("mod_marca_serial").value = '';
                    }

                }
            });
        }
    </script>
    <script>
        function mostrar(id) {
            if (id === '1') {
                $("#guaya_op").show();
                $("#guaya_op1").show();
            }else{
                $("#guaya_op").hide();
                $("#guaya_op1").hide();
            }
        }
        function mostrarUpd(id) {
            if (id === '1') {
                $("#guaya_op_upd").show();
                $("#guaya_op1_upd").show();
            }else{
                $("#guaya_op_upd").hide();
                $("#guaya_op1_upd").hide();
            }
        }
    </script>
    <script>
        function obtener_datos_asignado(id){

            var TipoEquipo      = $("#tipo_equipo" + id).val();
            var IdEquipo        = $("#id_equipo" + id).val();
            var IdMouse         = $("#id_mouse" + id).val();
            var IdTeclado       = $("#id_teclado" + id).val();
            var IdPantalla      = $("#id_pantalla" + id).val();
            var IdCargador      = $("#id_cargador" + id).val();
            var IdGuaya         = $("#id_guaya" + id).val();
            var TipoGuaya       = $("#tipo_guaya" + id).val();
            var CodeGuaya       = $("#code_guaya" + id).val();
            var Sede            = $("#sede" + id).val();
            var Area            = $("#area" + id).val();
            var NombreUsuario   = $("#nombre_usuario" + id).val();
            var CargoUsuario    = $("#cargo_usuario" + id).val();
            var IdUsuario       = $("#id_usuario" + id).val();
            var TelUsuario      = $("#tel_usuario" + id).val();
            var Correo          = $("#correo" + id).val();
            var Ticket          = $("#id_ticket" + id).val();
            var FechaAsignacion = $("#fecha_asignacion" + id).val();
            var Estado          = $("#estado_asignado" + id).val();
            var Opcion          = $("#opcion" + id).val();
            var Evidencia       = $("#evidencia" + id).val();
            var Historial       = $("#historial" + id).val();

            $("#mod_idA").val(id);
            $("#mod_tipo_equipo").val(TipoEquipo);
            $("#mod_marca_serial").val(IdEquipo);
            $("#mod_mouse").val(IdMouse);
            $("#mod_pantalla").val(IdPantalla);
            $("#mod_teclado").val(IdTeclado);
            $("#mod_cargador").val(IdCargador);
            $("#mod_opcion").val(Opcion);
            $("#mod_guaya").val(IdGuaya);
            $("#mod_tipo_guaya").val(TipoGuaya);
            $("#mod_code_guaya").val(CodeGuaya);
            $("#mod_sede").val(Sede);
            $("#mod_area").val(Area);
            $("#mod_nombre_asignado").val(NombreUsuario);
            $("#mod_cargo").val(CargoUsuario);
            $("#mod_cedula").val(IdUsuario);
            $("#mod_telefono").val(TelUsuario);
            $("#mod_correo").val(Correo);
            $("#mod_ticket").val(Ticket);
            $("#mod_fecha_asignacion").val(FechaAsignacion);
            $("#mod_estado").val(Estado);
            $("#mod_evidencia").val(Evidencia);
            $("#mod_historial").val(Historial);

            $("#VerAnexosA").click(function(){
                document.getElementById('anexosA').innerHTML = Evidencia;
            });
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
    <script>
        function AreaUpd() {
            var selectBox = document.getElementById("sede_upd");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            var tipo = 'post';
            var select = document.getElementById("area_upd");

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
