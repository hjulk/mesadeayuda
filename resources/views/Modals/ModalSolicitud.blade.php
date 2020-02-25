<div class="modal fade bd-example-modal-xl" id="modal-solicitud"  tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Crear Ticket</h4>
            </div>
                {!! Form::open(['action' => 'Usuario\UsuarioController@nuevaSolicitud', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}
                    <div class="modal-body">
                        <fieldset>
                            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                            <legend class="subtitle2" style="color: rgba(162, 27, 37, 1);">DATOS USUARIO</legend>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label col-sm-12" for="fname">Nombre Completo:</label>
                                        {!! Form::text('nombre_usuario',null,['class'=>'form-control','id'=>'nombre_usuario','placeholder'=>'Nombres y Apellidos','required']) !!}
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label col-sm-12" for="fname">Extensión o celular coorporativo</label>
                                        {!! Form::text('telefono_usuario',null,['class'=>'form-control','id'=>'telefono_usuario','placeholder'=>'No. de telefóno del coorporativo','required']) !!}
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label col-sm-12" for="fname">Correo Coorporativo:</label>
                                        {!! Form::text('correo_usuario',null,['class'=>'form-control','id'=>'correo_usuario','placeholder'=>'Correo electrónico corporativo','required']) !!}
                                        <div align="right"><small class="text-muted" style="font-size: 2.1vh;">Separar correos por <b>';'</b> y <b>no dejar espacios</b></small> <span id="cntDescripHechos" align="right"> </span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label col-sm-12" for="fname">Sede:</label>
                                        <input type="text" class="form-control" id="sede" name="sede" placeholder="Sede" value="{!! Session::get('NombreSede') !!}" readonly>
                                        <input type="hidden" class="form-control" id="project_id" name="project_id" placeholder="Sede" value="{!! Session::get('Sede') !!}" readonly>
                                        {{--  {!! Form::select('project_id',$Sedes,null,['class'=>'form-control','id'=>'project_id','onchange'=>'Area();','required']) !!}  --}}
                                    </div>
                                    <div class="col-md-5">
                                        <label class="control-label col-sm-12" for="fname">Área / Dependencia:</label>
                                        <input type="text" class="form-control" id="dependencia" name="dependencia" placeholder="Area" value="{!! Session::get('NombreArea') !!}" readonly>
                                        <input type="hidden" class="form-control" id="area" name="area" placeholder="Area" value="{!! Session::get('Area') !!}" readonly>
                                        {{--  {!! Form::text('dependencia',null,['class'=>'form-control','id'=>'dependencia','required','placeholder'=>'Área u oficina del usuario']) !!}  --}}
                                        {{--  {!! Form::select('area',$Areas,null,['class'=>'form-control','id'=>'area','required']) !!}  --}}
                                    </div>

                                </div>
                            </div>
                            <legend class="subtitle2" style="color: rgba(162, 27, 37, 1);">DATOS SOLICITUD</legend>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="control-label col-sm-12" for="email">Tipo Ticket:</label>
                                        {!! Form::select('kind_id',$Tipo,null,['class'=>'form-control','id'=>'kind_id','required']) !!}
                                    </div>
                                    {{-- <div class="col-md-5">
                                        <label class="control-label col-sm-12" for="email">Asunto</label>
                                        {!! Form::select('asunto',$TicketRecurrente,null,['class'=>'form-control','id'=>'asunto','required','onChange'=>'mostrar(this.value);']) !!}

                                    </div> --}}
                                    <div class="col-md-7">
                                        <label class="control-label col-sm-12" for="email">Seleccione el asunto que mas se ajuste a su solicitud</label>
                                        <input list="asuntos" name="title" id="title" class="form-control" required type="text">
                                        {{-- {!! Form::text('title',null,['class'=>'form-control text-input','id'=>'title','placeholder'=>'Asunto del Ticket','list'=>'asuntos']) !!} --}}
                                        <datalist id="asuntos"></datalist>
                                    </div>

                                </div>
                            </div>
                            {{-- <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12" id="titulo" style="display: none;">
                                        <label class="control-label col-sm-12" for="email">Cuál?</label>
                                        {!! Form::text('title',null,['class'=>'form-control','id'=>'title','placeholder'=>'Asunto del Ticket']) !!}
                                    </div>
                                </div>
                            </div> --}}
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label class="control-label col-sm-12" for="comment">Descripción de la Solicitud:</label>
                                        {!! Form::textarea('description',null,['class'=>'form-control','id'=>'description','placeholder'=>'Ingrese la descripción de la solicitud','rows'=>'3','required']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-7">
                                        <label for="exampleInputEmail1" class="col-sm-12 control-label">Anexar Evidencia al Ticket</label>
                                        <input type="file" id="evidencia[]" name="evidencia[]" class="form-control" multiple="multiple" size="5120">
                                        <div align="right"><small class="text-muted" style="font-size: 73%;">Tamaño maximo permitido (5MB), si se supera este tamaño, su archivo no será cargado.</small> <span id="cntDescripHechos" align="right"> </span></div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success">Crear Ticket</button>
                    </div>
                {!!  Form::close() !!}
            </div>
        </div>
    </div>
</div>



    <script>
        function obtener_datos_ticket(id) {
            var tipo                = $("#tipo_ticket" + id).val();
            var categoria           = $("#categoria" + id).val();
            var sede                = $("#sede" + id).val();
            var area                = $("#area" + id).val();
            var prioridad           = $("#prioridad" + id).val();
            var estado              = $("#estado" + id).val();
            var usuario             = $("#asignado_a" + id).val();
            var titulo              = $("#title" + id).val();
            var descripcion         = $("#description" + id).val();
            var evidencias          = $("#evidencia" + id).val();
            var historial           = $("#historial" + id).val();
            var nombre_usuario      = $("#name_user" + id).val();
            var correo_usuario      = $("#user_email" + id).val();
            var telefono_usuario    = $("#tel_user" + id).val();

            $("#mod_idT").val(id);
            $("#mod_tipo").val(tipo);
            $("#mod_categoria").val(categoria);
            $("#mod_id_sede").val(sede);
            $("#mod_dependencia").val(area);
            $("#mod_prioridad").val(prioridad);
            $("#mod_estado").val(estado);
            $("#mod_asignado").val(usuario);
            $("#mod_asunto").val(titulo);
            $("#mod_descripcion").val(descripcion);
            $("#mod_evidencias").val(evidencias);
            $("#mod_historial").val(historial);
            $("#mod_nombre_usuario").val(nombre_usuario);
            $("#mod_correo_usuario").val(correo_usuario);
            $("#mod_telefono_usuario").val(telefono_usuario);
            $("#mod_sede").val(sede);

            $("#VerAnexos").click(function(){
                document.getElementById('anexos').innerHTML = evidencias;
            });
        }
    </script>
<div class="modal fade bs-example-modal-md-udpZ" id="modal-tickets-upd">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Información Ticket</h4>
            </div>

            {!! Form::open(['url' => 'actualizarTicket', 'method' => 'post', 'enctype' => 'multipart/form-data','id'=>'form-ticket-upd','autocomplete'=>'off']) !!}
            <div class="modal-body">
                <input type="hidden" name="idT" id="mod_idT">
                <div class="box-body">
                    <legend class="subtitle2" style="color: rgba(162, 27, 37, 1);">DATOS USUARIO</legend>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Nombre Usuario:</label>
                                {!! Form::text('nombre_usuario_upd',null,['class'=>'form-control','id'=>'mod_nombre_usuario','placeholder'=>'Nombre de quien reporta','readonly']) !!}
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1" class="col-md-12 control-label">Extensión o celular coorporativo:</label>
                                {!! Form::text('telefono_usuario_upd',null,['class'=>'form-control','id'=>'mod_telefono_usuario','placeholder'=>'No. de telefóno del reportante','readonly']) !!}
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Correo Coorporativo:</label>
                                {!! Form::text('correo_usuario_upd',null,['class'=>'form-control','id'=>'mod_correo_usuario','placeholder'=>'Correo(s) del reportante','readonly']) !!}
                            </div>
                        </div>
                    </div>
                    <legend class="subtitle2" style="color: rgba(162, 27, 37, 1);">DATOS SOLICITUD</legend>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-5 control-label">Tipo:</label>
                                {!! Form::text('tipo_upd',null,['class'=>'form-control','id'=>'mod_tipo','placeholder'=>'Tipo Ticket','readonly']) !!}
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputEmail1" class="col-sm-5 control-label">Asunto:</label>
                                {!! Form::text('asunto_upd',null,['class'=>'form-control','id'=>'mod_asunto','placeholder'=>'Asunto del Ticket','readonly']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-5">
                                <label for="exampleInputEmail1" class="col-sm-8 control-label">Descripcion Solicitud:</label>
                                {!! Form::textarea('descripcion_upd',null,['class'=>'form-control','id'=>'mod_descripcion','placeholder'=>'Ingrese la descripción de la solicitud','rows'=>'3','readonly']) !!}
                            </div>
                            <div class="col-md-7">
                                <label for="exampleInputEmail1" class="col-sm-8 control-label">Historial del Ticket:</label>
                        {!! Form::textarea('historial',null,['class'=>'form-control','id'=>'mod_historial','placeholder'=>'Ingrese la descripción de la solicitud','rows'=>'3','readonly']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="exampleInputEmail1" class="col-sm-5 control-label">Sede:</label>
                                {!! Form::text('sede_upd',null,['class'=>'form-control','id'=>'mod_sede','readonly']) !!}
                            </div>
                            <div class="col-md-5">
                                <label for="exampleInputEmail1" class="col-sm-5 control-label">Área:</label>
                                {!! Form::text('dependencia_upd',null,['class'=>'form-control','id'=>'mod_dependencia','readonly']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-5 control-label">Prioridad:</label>
                                {!! Form::text('prioridad_upd',null,['class'=>'form-control','id'=>'mod_prioridad','readonly']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Categoria:</label>
                                {!! Form::text('categoria_upd',null,['class'=>'form-control','id'=>'mod_categoria','readonly']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Asignado A:</label>
                                {!! Form::text('asignado_upd',null,['class'=>'form-control','id'=>'mod_asignado','readonly']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-5 control-label">Estado:</label>
                                {!! Form::text('estado_upd',null,['class'=>'form-control','id'=>'mod_estado','readonly']) !!}
                            </div>
                            <div class="col-md-3">
                                <button type="button" id="VerAnexos" class="btn btn-success" style="margin-top: 15px;">Ver Anexos</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">

                            <div class="col-md-9">
                                <div id="anexos"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
            </div>
            {!!  Form::close() !!}

        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="modal-solicitud-usuario"  tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">REQUERIMIENTO DE USUARIOS Y/O EQUIPOS</h4>
            </div>
            {!! Form::open(['action' => 'Usuario\UsuarioController@crearTicketUsuarioFinal', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}
            <div class="modal-body">
                <div class="box-body">
                    <legend class="subtitle2" style="color: rgba(162, 27, 37, 1);">DATOS USUARIO</legend>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-5">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Nombres y Apellidos</label>
                                {!! Form::text('nombresT',null,['class'=>'form-control','id'=>'nombresT','required','placeholder'=>'Nombre Completo del usuario',]) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Identificación</label>
                                {!! Form::text('identificacionT',null,['class'=>'form-control','id'=>'identificacionT','placeholder'=>'Identificación','required']) !!}
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Cargo</label>
                                {!! Form::text('cargoT',null,['class'=>'form-control','id'=>'cargoT','placeholder'=>'Cargo del Usuario','required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="exampleInputEmail1" class="col-sm-5 control-label">Sede</label>
                                {!! Form::select('sedeT',$Sedes,null,['class'=>'form-control','id'=>'sedeT','onchange'=>'AreaT();','required']) !!}
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Área / Dependencia</label>
                                {{--  {!! Form::text('area',$Area,['class'=>'form-control','id'=>'area','required','placeholder'=>'Area o Dependencia del usuario']) !!}  --}}
                                {!! Form::select('areaT',$Areas,null,['class'=>'form-control','id'=>'areaT','required']) !!}
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Jefe Inmediato</label>
                                {!! Form::text('jefeT',null,['class'=>'form-control','id'=>'jefeT','required','placeholder'=>'Nombre del jefe inmediato']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Fecha Ingreso</label>
                                {!! Form::text('fechaIngresoT',null,['class'=>'form-control','id'=>'fechaIngresoT','required','placeholder'=>'Fecha de Ingreso del Usuario']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Correo Solicitante</label>
                                {!! Form::text('correoS',null,['class'=>'form-control','id'=>'correoS','required','placeholder'=>'Correo(s) del solicitante']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Cargo Nuevo?</label>
                                {!! Form::select('cargo_nuevoT',$Opciones,null,['class'=>'form-control','id'=>'cargo_nuevoT','required']) !!}
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Funcionario A Reemplazar</label>
                                {!! Form::text('funcionarioT',null,['class'=>'form-control','id'=>'funcionarioT','placeholder'=>'Nombre del Funcionario a reemplazar']) !!}
                            </div>
                        </div>
                    </div>
                    <legend class="subtitle2" style="color: rgba(162, 27, 37, 1);">DATOS CONFIGURACIÓN PERFIL</legend>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Servinté?</label>
                                {!! Form::select('app_85T',$Opciones,null,['class'=>'form-control','id'=>'app_85T']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Dinamica?</label>
                                {!! Form::select('app_dinamicaT',$Opciones,null,['class'=>'form-control','id'=>'app_dinamicaT']) !!}
                            </div>
                            <div class="col-md-5">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Otro Aplicativo</label>
                                {!! Form::text('otro_aplicativoT',null,['class'=>'form-control','id'=>'otro_aplicativoT','placeholder'=>'Otro(s) apicativo(s)']) !!}
                            </div>
                        </div>
                    </div>
                    <legend class="subtitle2" style="color: rgba(162, 27, 37, 1);">CAPACITACIÓN</legend>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Capacitación Servinté?</label>
                                {!! Form::select('cap_85T',$Opciones,null,['class'=>'form-control','id'=>'cap_85T']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Capacitación Dinamica?</label>
                                {!! Form::select('cap_dinamicaT',$Opciones,null,['class'=>'form-control','id'=>'cap_dinamicaT']) !!}
                            </div>
                        </div>
                    </div>
                    <legend class="subtitle2" style="color: rgba(162, 27, 37, 1);">SOLICITUD DE ADECUACIÓN PUESTO DE TRABAJO</legend>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Equipo de Computo</label>
                                {!! Form::select('equipo_computoT',$Equipo,null,['class'=>'form-control','id'=>'equipo_computoT']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Correo Electrónico?</label>
                                {!! Form::select('correo_electronicoT',$Opciones,null,['class'=>'form-control','id'=>'correo_electronicoT']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Celular Corporativo?</label>
                                {!! Form::select('celularT',$Opciones,null,['class'=>'form-control','id'=>'celularT']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Datos?</label>
                                {!! Form::select('datosT',$Opciones,null,['class'=>'form-control','id'=>'datosT']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Minutos?</label>
                                {!! Form::select('minutosT',$Opciones,null,['class'=>'form-control','id'=>'minutosT']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Extensión Telefónica?</label>
                                {!! Form::select('extension_telT',$Opciones,null,['class'=>'form-control','id'=>'extension_telT']) !!}
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Observaciones de la Solicitud</label>
                                {!! Form::textarea('observacionesT',null,['class'=>'form-control','id'=>'observacionesT','placeholder'=>'Ingrese la observación sobre la solicitud','rows'=>'2']) !!}
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
    <script>
        function AreaT() {
            var selectBox = document.getElementById("sedeT");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            var tipo = 'post';
            var select = document.getElementById("areaT");

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

