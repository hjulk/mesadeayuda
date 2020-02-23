<div class="modal fade bd-example-modal-xl" id="modal-equipo-movil" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title modal-title-primary">Crear Ingreso de Equipo'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}</h4>
            </div>
            {!! Form::open(['url' => 'asignacionEquipoMovil', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
            <div class="modal-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Tipo Equipo</label>
                            {!! Form::select('tipo_equipo',$TipoEquipo,null,['class'=>'form-control','id'=>'tipo_equipo']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Fecha Asignación</label>
                            {!! Form::text('fecha_adquision',$FechaAdquisicion,['class'=>'form-control','id'=>'fecha_adquision','placeholder'=>'Fecha de Asignación del Equipo']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Serial</label>
                            {!! Form::text('serial',$Serial,['class'=>'form-control','id'=>'serial','placeholder'=>'S/N','required']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Marca</label>
                            {!! Form::text('marca',$Marca,['class'=>'form-control','id'=>'marca','placeholder'=>'Marca Equipo']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Modelo</label>
                            {!! Form::text('modelo',$Modelo,['class'=>'form-control','id'=>'modelo','placeholder'=>'Modelo del Equipo']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">IMEI</label>
                            {!! Form::text('imei',$IMEI,['class'=>'form-control','id'=>'imei','placeholder'=>'IMEI','required']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Capacidad GB</label>
                            {!! Form::text('capacidad',$Capacidad,['class'=>'form-control','id'=>'capacidad','placeholder'=>'Capacidad de Memoria en GB']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Nro. Linea</label>
                            {!! Form::Select('linea_movil',$LineaMovil,null,['class'=>'form-control','id'=>'linea_movil']) !!}
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
                            {!! Form::text('nombre_asignado',$NombreAsignado,['class'=>'form-control','id'=>'nombre_asignado','placeholder'=>'Nombre del Usuario']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Estado Equipo</label>
                            {!! Form::select('estado',$EstadoEquipo,null,['class'=>'form-control','id'=>'estado']) !!}
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

<!-- ACTUALIZACION -->

<div class="modal fade bd-example-modal-xl" id="modal-cambios-equipo-movil" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title modal-title-primary">Actualizar Ingreso de Equipo'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}</h4>
            </div>
            {!! Form::open(['url' => 'actualizacionEquipoMovil', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
            <div class="modal-body">
                <input type="hidden" name="idEM" id="mod_idEM">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Tipo Equipo</label>
                            {!! Form::select('tipo_equipo_upd',$TipoEquipo,null,['class'=>'form-control','id'=>'mod_tipo_equipo_upd']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Fecha Asignación</label>
                            {!! Form::text('fecha_adquision_upd',$FechaAdquisicion,['class'=>'form-control','id'=>'mod_fecha_adquision_upd','placeholder'=>'Fecha de Asignación del Equipo']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Serial</label>
                            {!! Form::text('serial_upd',$Serial,['class'=>'form-control','id'=>'mod_serial_upd','placeholder'=>'S/N','required']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Marca</label>
                            {!! Form::text('marca_upd',$Marca,['class'=>'form-control','id'=>'mod_marca_upd','placeholder'=>'Marca Equipo']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Modelo</label>
                            {!! Form::text('modelo_upd',$Modelo,['class'=>'form-control','id'=>'mod_modelo_upd','placeholder'=>'Modelo del Equipo']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">IMEI</label>
                            {!! Form::text('imei_upd',$IMEI,['class'=>'form-control','id'=>'mod_imei_upd','placeholder'=>'IMEI','required']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Capacidad GB</label>
                            {!! Form::text('capacidad_upd',$Capacidad,['class'=>'form-control','id'=>'mod_capacidad_upd','placeholder'=>'Capacidad de Memoria en GB']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Nro. Linea</label>
                            {!! Form::Select('linea_movil_upd',$LineaMovilUpd,null,['class'=>'form-control','id'=>'mod_linea_movil_upd']) !!}
                            {!! Form::checkbox('desvincular',null,null,['id'=>'desvincular']) !!}&nbsp;Desvincular Linea
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Area</label>
                            {!! Form::text('area_upd',$Area,['class'=>'form-control','id'=>'mod_area_upd','placeholder'=>'Area del usuario']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Nombre Asignado</label>
                            {!! Form::text('nombre_asignado_upd',$NombreAsignado,['class'=>'form-control','id'=>'mod_nombre_asignado_upd','placeholder'=>'Nombre del Usuario']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Estado Equipo</label>
                            {!! Form::select('estado_upd',$EstadoEquipo,null,['class'=>'form-control','id'=>'mod_estado_upd']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Evidencia</label>
                            <input type="file" id="mod_evidencia_upd[]" name="evidencia_upd[]" class="form-control" multiple="multiple" size="5120">
                            <div align="right"><small class="text-muted" style="font-size: 63%;">Tamaño maximo permitido (5MB), si se supera este tamaño, su archivo no será cargado.</small> <span id="cntDescripHechos" align="right"> </span></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Historial Equipo'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}</label>
                            {!! Form::textarea('historial',null,['class'=>'form-control','id'=>'mod_historial','placeholder'=>'Historial del equipo','rows'=>'3']) !!}
                        </div>
                        <div class="col-md-6">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Agregar Comentario</label>
                            {!! Form::textarea('comentario',null,['class'=>'form-control','id'=>'comentario','placeholder'=>'Ingrese el comentario sobre la gestión del equipo','rows'=>'3']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <button type="button" id="VerAnexosEM" class="btn btn-success">Ver Anexos</button>
                        </div>
                        <div class="col-md-9">
                            <div id="anexosEM"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Actualizar Ingreso</button>
            </div>
            {!!  Form::close() !!}

        </div>
    </div>
</div>

    <script>

        function obtener_datos_equipo_movil(id) {

            var TipoEquipo      = $("#tipo_equipo" + id).val();
            var FechaIngreso    = $("#fecha_ingreso" + id).val();
            var Serial          = $("#serial" + id).val();
            var Marca           = $("#marca" + id).val();
            var Modelo          = $("#modelo" + id).val();
            var IMEI            = $("#IMEI" + id).val();
            var Capacidad       = $("#capacidad" + id).val();
            var Usuario         = $("#usuario" + id).val();
            var Area            = $("#area" + id).val();
            var Linea           = $("#linea" + id).val();
            var EstadoEquipo    = $("#estado_equipo" + id).val();
            var Evidencia       = $("#evidencia" + id).val();
            var Historial       = $("#historial" + id).val();

            $("#mod_idEM").val(id);
            $("#mod_tipo_equipo_upd").val(TipoEquipo);
            $("#mod_fecha_adquision_upd").val(FechaIngreso);
            $("#mod_serial_upd").val(Serial);
            $("#mod_marca_upd").val(Marca);
            $("#mod_modelo_upd").val(Modelo);
            $("#mod_imei_upd").val(IMEI);
            $("#mod_capacidad_upd").val(Capacidad);
            $("#mod_linea_movil_upd").val(Linea);
            $("#mod_area_upd").val(Area);
            $("#mod_nombre_asignado_upd").val(Usuario);
            $("#mod_estado_upd").val(EstadoEquipo);
            $("#mod_historial").val(Historial);

            $("#VerAnexosEM").click(function(){
                document.getElementById('anexosEM').innerHTML = Evidencia;
            });

        }
    </script>
