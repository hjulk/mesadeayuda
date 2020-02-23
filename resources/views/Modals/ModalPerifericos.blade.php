<div class="modal fade bd-example-modal-xl" id="modal-perifericos" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title modal-title-primary">Crear Ingreso de Periferico</h4>
            </div>
            {!! Form::open(['url' => 'ingresoPeriferico', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}
            <div class="modal-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Tipo Periferico</label>
                            {!! Form::Select('tipo_periferico',$TipoPeriferico,null,['class'=>'form-control','id'=>'tipo_periferico','required','onChange'=>'mostrarT(this.value);']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Tipo Adquisisión</label>
                            {!! Form::Select('tipo_ingreso',$TipoIngreso,null,['class'=>'form-control','id'=>'tipo_ingreso','onChange'=>'mostrar(this.value);','required']) !!}
                        </div>
                        <div class="col-md-3" id="renting" style="display: none;">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Nombre Empresa</label>
                            {!! Form::text('emp_renting',$Renting,['class'=>'form-control','id'=>'emp_renting','placeholder'=>'Nombre Empresa']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Fecha Asignación</label>
                            {!! Form::text('fecha_adquision',$FechaAdquisicion,['class'=>'form-control','id'=>'fecha_adquision','placeholder'=>'Fecha de Asignación']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Serial</label>
                            {!! Form::text('serial',$Serial,['class'=>'form-control','id'=>'serial','placeholder'=>'S/N','required']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Marca</label>
                            {!! Form::text('marca',$Marca,['class'=>'form-control','id'=>'marca','placeholder'=>'Marca','required']) !!}
                        </div>
                        <div class="col-md-3" id="tamano" style="display: none;">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Tamaño Pulgadas</label>
                            {!! Form::text('tamano',$Tamano,['class'=>'form-control','id'=>'tamano','placeholder'=>'Tamaño en Pulgadas']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Estado Periferico</label>
                            {!! Form::select('estado',$EstadoEquipo,null,['class'=>'form-control','id'=>'estado']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">

                        <div class="col-md-5">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Evidencia</label>
                            <input type="file" id="evidencia[]" name="evidencia[]" class="form-control" multiple>
                            <div align="right"><small class="text-muted" style="font-size: 63%;">Tamaño maximo permitido (5MB), si se supera este tamaño, su archivo no será cargado.</small> <span id="cntDescripHechos" align="right"> </span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-success">Crear Ingreso</button>
            </div>
            {!!  Form::close() !!}
        </div>
    </div>
</div>

{{-- ACTUALIZACION --}}

<div class="modal fade bd-example-modal-xl" id="modal-cambios-periferico" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title modal-title-primary">Actualizar Ingreso de Periferico</h4>
            </div>
            {!! Form::open(['url' => 'actualizacionPeriferico', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}
            <div class="modal-body">
                <input type="hidden" name="idP" id="mod_idP">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Tipo Periferico</label>
                            {!! Form::Select('tipo_periferico_upd',$TipoPeriferico,null,['class'=>'form-control','id'=>'mod_tipo_periferico','required','onChange'=>'mostrarTUpd(this.value);']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Tipo Adquisisión</label>
                            {!! Form::Select('tipo_ingreso_upd',$TipoIngreso,null,['class'=>'form-control','id'=>'mod_tipo_ingreso','onChange'=>'mostrarUpd(this.value);','required']) !!}
                        </div>
                        <div class="col-md-3" id="renting_upd" style="display: none;">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Nombre Empresa</label>
                            {!! Form::text('emp_renting_upd',$Renting,['class'=>'form-control','id'=>'mod_emp_renting','placeholder'=>'Nombre Empresa']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Fecha Asignación</label>
                            {!! Form::text('fecha_adquision_upd',$FechaAdquisicion,['class'=>'form-control','id'=>'mod_fecha_adquision','placeholder'=>'Fecha de Asignación']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Serial</label>
                            {!! Form::text('serial_upd',$Serial,['class'=>'form-control','id'=>'mod_serial','placeholder'=>'S/N','required']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Marca</label>
                            {!! Form::text('marca_upd',$Marca,['class'=>'form-control','id'=>'mod_marca','placeholder'=>'Marca','required']) !!}
                        </div>
                        <div class="col-md-3" id="tamano_upd" style="display: none;">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Tamaño Pulgadas</label>
                            {!! Form::text('tamano_upd',$Tamano,['class'=>'form-control','id'=>'mod_tamano','placeholder'=>'Tamaño en Pulgadas']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Estado Periferico</label>
                            {!! Form::select('estado_upd',$EstadoEquipo,null,['class'=>'form-control','id'=>'mod_estado']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Historial Periferico</label>
                            {!! Form::textarea('historial',null,['class'=>'form-control','id'=>'mod_historial','placeholder'=>'Historial del periferic','rows'=>'3','readonly']) !!}
                        </div>
                        <div class="col-md-6">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Agregar Comentario</label>
                            {!! Form::textarea('comentario',null,['class'=>'form-control','id'=>'comentario','placeholder'=>'Ingrese el comentario sobre la gestión del periferico','rows'=>'3']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Evidencia</label>
                            <input type="file" id="mod_evidencia[]" name="evidencia_upd[]" class="form-control" multiple="multiple" size="5120">
                            <div align="right"><small class="text-muted" style="font-size: 63%;">Tamaño maximo permitido (5MB), si se supera este tamaño, su archivo no será cargado.</small> <span id="cntDescripHechos" align="right"> </span></div>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="VerAnexosP" class="btn btn-success">Ver Anexos</button>
                        </div>
                        <div class="col-md-5">
                            <div id="anexosP"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-success">Actualizar Ingreso</button>
            </div>
            {!!  Form::close() !!}

        </div>
    </div>
</div>
    <script>
        function obtener_datos_periferico(id) {

            var TipoPeriferico  = $("#tipo_periferico" + id).val();
            var TipoIngreso     = $("#tipo_ingreso" + id).val();
            var EmpRenting      = $("#emp_renting" + id).val();
            var FechaIngreso    = $("#fecha_ingreso" + id).val();
            var Serial          = $("#serial" + id).val();
            var Marca           = $("#marca" + id).val();
            var Tamano          = $("#tamano" + id).val();
            var Estado          = $("#estado_periferico" + id).val();
            var Evidencia       = $("#evidencia" + id).val();
            var Historial       = $("#historial" + id).val();

            $("#mod_idP").val(id);
            $("#mod_tipo_periferico").val(TipoPeriferico);
            $("#mod_tipo_ingreso").val(TipoIngreso);
            $("#mod_emp_renting").val(EmpRenting);
            $("#mod_fecha_adquision").val(FechaIngreso);
            $("#mod_serial").val(Serial);
            $("#mod_marca").val(Marca);
            $("#mod_tamano").val(Tamano);
            $("#mod_estado").val(Estado);
            $("#mod_evidencia").val(Evidencia);
            $("#mod_historial").val(Historial);

            $("#VerAnexosP").click(function(){
                document.getElementById('anexosP').innerHTML = Evidencia;
            });
        }
    </script>

    <script>
        function mostrar(id) {
            if (id === '1') {
                $("#renting").show();
            }else{
                $("#renting").hide();
            }
        }
        function mostrarUpd(id) {
            if (id === '1') {
                $("#renting_upd").show();
            }else{
                $("#renting_upd").hide();
            }
        }
    </script>

    <script>
        function mostrarT(id) {
            if (id === '1') {
                $("#tamano").show();
            }else{
                $("#tamano").hide();
            }
        }
        function mostrarTUpd(id) {
            if (id === '1') {
                $("#tamano_upd").show();
            }else{
                $("#tamano_upd").hide();
            }
        }
    </script>
