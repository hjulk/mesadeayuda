<div class="modal fade" id="modal-tickets">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Crear Ticket</h4>
            </div>

            {!! Form::open(['url' => 'crearTicket', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off','id'=>'form-ticket']) !!}
            <div class="modal-body">
                <div class="box-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-5 control-label">Tipo</label>
                                {!! Form::select('kind_id',$NombreTipo,null,['class'=>'form-control','id'=>'kind_id','required']) !!}
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputEmail1" class="col-sm-5 control-label">Asunto</label>
                                {!! Form::text('title',$Asunto,['class'=>'form-control','id'=>'title','placeholder'=>'Asunto del Ticket','required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1" class="col-sm-12 control-label">Descripcion Solicitud</label>
                        {!! Form::textarea('description',$Descripcion,['class'=>'form-control','id'=>'description','placeholder'=>'Ingrese la descripción de la solicitud','rows'=>'3','required']) !!}
                        <div align="right"><small class="text-muted" style="font-size: 2.5vh;">Por favor copiar texto sin <b>íconos</b> que vienen en el correo. Gracias</small> <span id="cntDescripHechos" align="right"> </span></div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-5">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Quien solicita</label>
                                {!! Form::text('nombre_usuario',$Usuario,['class'=>'form-control','id'=>'nombre_usuario','placeholder'=>'Nombre de quien reporta','required']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-md-5 control-label">Telefóno</label>
                                {!! Form::text('telefono_usuario',$TelefonoUsuario,['class'=>'form-control','id'=>'telefono_usuario','placeholder'=>'No. de telefóno del reportante']) !!}
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1" class="col-sm-5 control-label">Correo</label>
                                {!! Form::text('correo_usuario',$CorreoUsuario,['class'=>'form-control','id'=>'correo_usuario','placeholder'=>'Correo(s) del reportante','required']) !!}
                                <div align="right"><small class="text-muted" style="font-size: 2.1vh;">Separar correos por <b>';'</b> y <b>no dejar espacios</b></small> <span id="cntDescripHechos" align="right"> </span></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="exampleInputEmail1" class="col-sm-5 control-label">Sede</label>
                                {!! Form::select('project_id',$NombreSede,null,['class'=>'form-control','id'=>'project_id','onchange'=>'Area();','required']) !!}
                            </div>
                            <div class="col-md-5">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Área / Dependencia</label>
                                {{--  {!! Form::text('dependencia',$Dependencia,['class'=>'form-control','id'=>'dependencia','required']) !!}  --}}
                                {!! Form::select('area',$Areas,null,['class'=>'form-control','id'=>'area','required']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-5 control-label">Prioridad</label>
                                {!! Form::select('priority_id',$NombrePrioridad,null,['class'=>'form-control','id'=>'priority_id','required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-5 control-label">Categoria</label>
                                {!! Form::select('id_categoria',$NombreCategoria,null,['class'=>'form-control','id'=>'id_categoria','onchange'=>'categoriaFunc();','required']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-5 control-label">Asignado</label>
                                {!! Form::select('id_usuario',$NombreUsuario,null,['class'=>'form-control','id'=>'id_usuario']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-5 control-label">Estado</label>
                                {!! Form::select('id_estado',$NombreEstado,null,['class'=>'form-control','id'=>'id_estado','required']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Anexar Evidencia</label>
                                <input type="file" id="evidencia[]" name="evidencia[]" class="form-control" multiple="multiple" size="5120">
                                <div align="right"><small class="text-muted" style="font-size: 63%;">Tamaño maximo permitido (5MB), si se supera este tamaño, su archivo no será cargado.</small> <span id="cntDescripHechos" align="right"> </span></div>
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
<div class="modal fade" id="modal-reabrir-tickets">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Reabrir Ticket</h4>
                </div>

                {!! Form::open(['action' => 'Admin\TicketsController@reabrirTicket', 'method' => 'post', 'enctype' => 'multipart/form-data','class' => 'form-horizontal','autocomplete'=>'off']) !!}
                <div class="modal-body">

                    <div class="box-body">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">No. de Ticket</label>
                            <div class="col-md-3">
                                {!! Form::number('id_ticket',$Asunto,['class'=>'form-control','id'=>'id_ticket','placeholder'=>'Nro. del Ticket']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Descripcion Apertura</label>
                            <div class="col-md-8">
                                {!! Form::textarea('descripcion_ticket',$Descripcion,['class'=>'form-control','id'=>'descripcion_ticket','placeholder'=>'Ingrese la descripción de la apertura','rows'=>'3']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Categoria</label>
                            <div class="col-md-5">
                                {!! Form::select('id_categoriaT',$NombreCategoria,null,['class'=>'form-control','id'=>'id_categoriaT','onchange'=>'categoriaTFunc();']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Asignado</label>
                            <div class="col-md-5">
                                {!! Form::select('id_usuarioT',$NombreUsuario,null,['class'=>'form-control','id'=>'id_usuarioT']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Estado</label>
                            <div class="col-md-5">
                                {!! Form::select('id_estadoT',$NombreEstadoA,null,['class'=>'form-control','id'=>'id_estadoT']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-4 control-label">Prioridad</label>
                            <div class="col-md-5">
                                {!! Form::select('id_prioridadT',$NombrePrioridad,null,['class'=>'form-control','id'=>'id_prioridadT']) !!}
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Abrir Ticket</button>
                </div>
                {!!  Form::close() !!}

            </div>
        </div>
    </div>
    <script type="text/javascript">

        function categoriaFunc() {
            var selectBox = document.getElementById("id_categoria");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            var tipo = 'post';
            var select = document.getElementById("id_usuario");

            $.ajax({
                url: "{{route('buscarCategoria')}}",
                type: "get",
                data: {_method: tipo, id_categoria: selectedValue},
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

        function categoriaTFunc() {
            var selectBox = document.getElementById("id_categoriaT");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            var tipo = 'post';
            var select = document.getElementById("id_usuarioT");

            $.ajax({
                url: "{{route('buscarCategoria')}}",
                type: "get",
                data: {_method: tipo, id_categoria: selectedValue},
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
        function Area() {
            var selectBox = document.getElementById("project_id");
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
