<div class="modal fade bd-example-modal-xl" id="modal-consumible" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title modal-title-primary">Crear Ingreso de Consumible</h4>
            </div>
            {!! Form::open(['url' => 'ingresarConsumible', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}
            <div class="modal-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Tipo Consumible</label>
                            {!! Form::Select('tipo_consumible',$TipoConsumible,null,['class'=>'form-control','id'=>'tipo_consumible','required']) !!}
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
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Modelo</label>
                            {!! Form::text('modelo',$Modelo,['class'=>'form-control','id'=>'modelo','placeholder'=>'Modelo']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Compatibilidad Referencia</label>
                            {!! Form::text('compa_ref',$CompaRef,['class'=>'form-control','id'=>'compa_ref','placeholder'=>'Comp. de Referencia']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Compatibilidad Modelo</label>
                            {!! Form::text('compa_mod',$CompaMod,['class'=>'form-control','id'=>'compa_mod','placeholder'=>'Comp. de Modelo']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Estado Consumible</label>
                            {!! Form::select('estado',$Estado,null,['class'=>'form-control','id'=>'estado']) !!}
                        </div>
                        <div class="col-md-6">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Evidencia</label>
                            <input type="file" id="evidencia[]" name="evidencia[]" class="form-control" multiple="multiple" size="5120">
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

<div class="modal fade bd-example-modal-xl" id="modal-cambios-consumible" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title modal-title-primary">Actualizar Ingreso de Consumible</h4>
            </div>
            {!! Form::open(['url' => 'actualizarConsumible', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}
            <div class="modal-body">
                'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Tipo Consumible</label>
                            {!! Form::Select('tipo_consumible_upd',$TipoConsumible,null,['class'=>'form-control','id'=>'mod_tipo_consumible','required']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Tipo Adquisisión</label>
                            {!! Form::Select('tipo_ingreso_upd',$TipoIngreso,null,['class'=>'form-control','id'=>'mod_tipo_ingreso','onChange'=>'mostrar(this.value);','required']) !!}
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
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Modelo</label>
                            {!! Form::text('modelo_upd',$Modelo,['class'=>'form-control','id'=>'mod_modelo','placeholder'=>'Modelo']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Compatibilidad Referencia</label>
                            {!! Form::text('compa_ref_upd',$CompaRef,['class'=>'form-control','id'=>'mod_compa_ref','placeholder'=>'Comp. de Referencia']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Compatibilidad Modelo</label>
                            {!! Form::text('compa_mod_upd',$CompaMod,['class'=>'form-control','id'=>'mod_compa_mod','placeholder'=>'Comp. de Modelo']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Estado Consumible</label>
                            {!! Form::select('estado_upd',$Estado,null,['class'=>'form-control','id'=>'mod_estado']) !!}
                        </div>
                        <div class="col-md-6">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Evidencia</label>
                            <input type="file" id="mod_evidencia[]" name="evidencia_upd[]" class="form-control" multiple>
                            <div align="right"><small class="text-muted" style="font-size: 63%;">Tamaño maximo permitido (5MB), si se supera este tamaño, su archivo no será cargado.</small> <span id="cntDescripHechos" align="right"> </span></div>
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
                            <button type="button" id="VerAnexosC" class="btn btn-success">Ver Anexos</button>
                        </div>
                        <div class="col-md-5">
                            <div id="anexosC"></div>
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
        function obtener_datos_consumible(id) {

            var TipoPeriferico  = $("#tipo_consumible" + id).val();
            var TipoIngreso     = $("#tipo_ingreso" + id).val();
            var EmpRenting      = $("#emp_renting" + id).val();
            var FechaIngreso    = $("#fecha_ingreso" + id).val();
            var Serial          = $("#serial" + id).val();
            var Marca           = $("#marca" + id).val();
            var Modelo          = $("#modelo" + id).val();
            var CompaRef        = $("#compa_ref" + id).val();
            var CompaMod        = $("#compa_mod" + id).val();
            var Estado          = $("#estado_consumible" + id).val();
            var Evidencia       = $("#evidencia" + id).val();
            var Historial       = $("#historial" + id).val();

            $("#mod_idC").val(id);
            $("#mod_tipo_consumible").val(TipoPeriferico);
            $("#mod_tipo_ingreso").val(TipoIngreso);
            $("#mod_emp_renting").val(EmpRenting);
            $("#mod_fecha_adquision").val(FechaIngreso);
            $("#mod_serial").val(Serial);
            $("#mod_marca").val(Marca);
            $("#mod_modelo").val(Modelo);
            $("#mod_compa_ref").val(CompaRef);
            $("#mod_compa_mod").val(CompaMod);
            $("#mod_estado").val(Estado);
            $("#mod_evidencia").val(Evidencia);
            $("#mod_historial").val(Historial);

            $("#VerAnexosC").click(function(){
                document.getElementById('anexosC').innerHTML = Evidencia;
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
