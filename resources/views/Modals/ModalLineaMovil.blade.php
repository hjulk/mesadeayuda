<div class="modal fade bd-example-modal-xl" id="modal-linea-movil" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title modal-title-primary">Crear Ingreso de Linea Movil</h4>
            </div>
            {!! Form::open(['url' => 'asignacionLineaMovil', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}
            <div class="modal-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Fecha Asignación</label>
                            {!! Form::text('fecha_adquision',$FechaAdquisicion,['class'=>'form-control','id'=>'fecha_adquision','placeholder'=>'Fecha de Asignación de la linea']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Serial SIM CARD</label>
                            {!! Form::text('serial',$Serial,['class'=>'form-control','id'=>'serial','placeholder'=>'S/N','required']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Nro Linea</label>
                            {!! Form::text('nro_linea',$NroLinea,['class'=>'form-control','id'=>'nro_linea','placeholder'=>'Nro de Linea']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Activo</label>
                            {!! Form::Select('activo',$Activo,null,['class'=>'form-control','id'=>'activo']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Proveedor</label>
                            {!! Form::Select('proveedores',$Proveedores,null,['class'=>'form-control','id'=>'proveedores']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Plan</label>
                            {!! Form::text('plan',$Plan,['class'=>'form-control','id'=>'plan','placeholder'=>'Plan','required']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Punto o Cargo</label>
                            {!! Form::text('pto_cargo',$PtoCargo,['class'=>'form-control','id'=>'pto_cargo','placeholder'=>'Punto o Cargo']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Cédula Ciudadanía</label>
                            {!! Form::text('cc',$CC,['class'=>'form-control','id'=>'cc','placeholder'=>'Cédula de Ciudadanía']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Area</label>
                            {!! Form::text('area',$Area,['class'=>'form-control','id'=>'area','placeholder'=>'Area del usuario']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Nombre Asignado</label>
                            {!! Form::text('personal',$Personal,['class'=>'form-control','id'=>'personal','placeholder'=>'Nombre del Usuario']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Estado Linea</label>
                            {!! Form::select('estado',$EstadoLinea,null,['class'=>'form-control','id'=>'estado']) !!}
                        </div>
                        <div class="col-md-3">
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

{{--  ACTUALIZACION  --}}
<div class="modal fade bd-example-modal-xl" id="modal-cambios-linea-movil" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title modal-title-primary">Actualizar Ingreso de Linea Movil</h4>
            </div>
            {!! Form::open(['url' => 'actualizacionLineaMovil', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}
            <div class="modal-body">
                <input type="hidden" name="idLM" id="mod_idLM">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Fecha Asignación</label>
                            {!! Form::text('fecha_adquision_upd',$FechaAdquisicion,['class'=>'form-control','id'=>'mod_fecha_adquision','placeholder'=>'Fecha de Asignación de la linea']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Serial SIM CARD</label>
                            {!! Form::text('serial_upd',$Serial,['class'=>'form-control','id'=>'mod_serial','placeholder'=>'S/N','required']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Nro Linea</label>
                            {!! Form::text('nro_linea_upd',$NroLinea,['class'=>'form-control','id'=>'mod_nro_linea','placeholder'=>'Nro de Linea']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Activo</label>
                            {!! Form::Select('activo_upd',$Activo,null,['class'=>'form-control','id'=>'mod_activo']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Proveedor</label>
                            {!! Form::Select('proveedores_upd',$Proveedores,null,['class'=>'form-control','id'=>'mod_proveedores']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Plan</label>
                            {!! Form::text('plan_upd',$Plan,['class'=>'form-control','id'=>'mod_plan','placeholder'=>'Plan','required']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Punto o Cargo</label>
                            {!! Form::text('pto_cargo_upd',$PtoCargo,['class'=>'form-control','id'=>'mod_pto_cargo','placeholder'=>'Punto o Cargo']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Cédula Ciudadanía</label>
                            {!! Form::text('cc_upd',$CC,['class'=>'form-control','id'=>'mod_cc','placeholder'=>'Cédula de Ciudadanía']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Area</label>
                            {!! Form::text('area_upd',$Area,['class'=>'form-control','id'=>'mod_area','placeholder'=>'Area del usuario']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Nombre Asignado</label>
                            {!! Form::text('personal_upd',$Personal,['class'=>'form-control','id'=>'mod_personal','placeholder'=>'Nombre del Usuario']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Estado Linea</label>
                            {!! Form::select('estado_upd',$EstadoLinea,null,['class'=>'form-control','id'=>'mod_estado']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Evidencia</label>
                            <input type="file" id="evidencia_upd[]" name="mod_evidencia[]" class="form-control" multiple="multiple" size="5120">
                            <div align="right"><small class="text-muted" style="font-size: 63%;">Tamaño maximo permitido (5MB), si se supera este tamaño, su archivo no será cargado.</small> <span id="cntDescripHechos" align="right"> </span></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Historial Linea Movil</label>
                            {!! Form::textarea('historial',null,['class'=>'form-control','id'=>'mod_historial','placeholder'=>'Historial de la linea movil','rows'=>'3','readonly']) !!}
                        </div>
                        <div class="col-md-6">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Agregar Comentario</label>
                            {!! Form::textarea('comentario',null,['class'=>'form-control','id'=>'comentario','placeholder'=>'Ingrese el comentario sobre la gestión de la linea movil','rows'=>'3']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <button type="button" id="VerAnexosLM" class="btn btn-success">Ver Anexos</button>
                        </div>
                        <div class="col-md-9">
                            <div id="anexosLM"></div>
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

        function obtener_datos_linea_movil(id) {

            var NroLinea        = $("#nro_linea" + id).val();
            var Activo          = $("#activo" + id).val();
            var Proveedor       = $("#proveedor" + id).val();
            var Plan            = $("#plan" + id).val();
            var Serial          = $("#serial" + id).val();
            var FechaIngreso    = $("#fecha_ingreso" + id).val();
            var PtoCargo        = $("#pto_cargo" + id).val();
            var CC              = $("#cc" + id).val();
            var Area            = $("#area" + id).val();
            var Personal        = $("#personal" + id).val();
            var EstadoEquipo    = $("#estado_equipo" + id).val();
            var Evidencia       = $("#evidencia" + id).val();
            var Historial       = $("#historial" + id).val();

            $("#mod_idLM").val(id);
            $("#mod_fecha_adquision").val(FechaIngreso);
            $("#mod_serial").val(Serial);
            $("#mod_nro_linea").val(NroLinea);
            $("#mod_activo").val(Activo);
            $("#mod_proveedores").val(Proveedor);
            $("#mod_plan").val(Plan);
            $("#mod_pto_cargo").val(PtoCargo);
            $("#mod_cc").val(CC);
            $("#mod_area").val(Area);
            $("#mod_personal").val(Personal);
            $("#mod_estado").val(EstadoEquipo);
            $("#mod_historial").val(Historial);

            $("#VerAnexosLM").click(function(){
                document.getElementById('anexosLM').innerHTML = Evidencia;
            });

        }
    </script>
