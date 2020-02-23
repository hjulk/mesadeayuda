<div class="modal fade bd-example-modal-xl" id="modal-equipos" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title modal-title-primary">Crear Ingreso de Equipo</h4>
            </div>
            {!! Form::open(['url' => 'ingresoEquipo', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}
            <div class="modal-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Tipo Equipo</label>
                            {!! Form::Select('tipo_equipo',$TipoEquipo,null,['class'=>'form-control','id'=>'tipo_equipo','required']) !!}
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
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Procesador</label>
                            {!! Form::text('procesador',$Procesador,['class'=>'form-control','id'=>'procesador','placeholder'=>'Intel / AMD']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Velocidad Procesador</label>
                            {!! Form::text('vel_procesador',$VelProcesador,['class'=>'form-control','id'=>'vel_procesador','placeholder'=>'X.XGhz']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-2">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Disco Duro</label>
                            {!! Form::text('disco_duro',$DiscoDuro,['class'=>'form-control','id'=>'disco_duro','placeholder'=>'GB / TB']) !!}
                        </div>
                        <div class="col-md-2">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Memoria RAM</label>
                            {!! Form::text('memoria_ram',$MemoriaRam,['class'=>'form-control','id'=>'memoria_ram','placeholder'=>'GB']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Estado Equipo</label>
                            {!! Form::select('estado',$EstadoEquipo,null,['class'=>'form-control','id'=>'estado']) !!}
                        </div>
                        <div class="col-md-5">
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

<div class="modal fade bd-example-modal-xl" id="modal-cambios-equipo" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title modal-title-primary">Actualizar Ingreso de Equipo</h4>
            </div>
            {!! Form::open(['url' => 'actualizacionEquipo', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}
            <div class="modal-body">
                <input type="hidden" name="idE" id="mod_idE">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Tipo Equipo</label>
                            {!! Form::Select('tipo_equipo_upd',$TipoEquipo,null,['class'=>'form-control','id'=>'mod_tipo_equipo','required']) !!}
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
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Procesador</label>
                            {!! Form::text('procesador_upd',$Procesador,['class'=>'form-control','id'=>'mod_procesador','placeholder'=>'Intel / AMD']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Velocidad Procesador</label>
                            {!! Form::text('vel_procesador_upd',$VelProcesador,['class'=>'form-control','id'=>'mod_vel_procesador','placeholder'=>'X.XGhz']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-2">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Disco Duro</label>
                            {!! Form::text('disco_duro_upd',$DiscoDuro,['class'=>'form-control','id'=>'mod_disco_duro','placeholder'=>'GB / TB']) !!}
                        </div>
                        <div class="col-md-2">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Memoria RAM</label>
                            {!! Form::text('memoria_ram_upd',$MemoriaRam,['class'=>'form-control','id'=>'mod_memoria_ram','placeholder'=>'GB']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Estado Equipo</label>
                            {!! Form::select('estado_upd',$EstadoEquipo,null,['class'=>'form-control','id'=>'mod_estado']) !!}
                        </div>
                        <div class="col-md-5">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Evidencia</label>
                            <input type="file" id="mod_evidencia[]" name="evidencia_upd[]" class="form-control" multiple>
                            <div align="right"><small class="text-muted" style="font-size: 63%;">Tamaño maximo permitido (5MB), si se supera este tamaño, su archivo no será cargado.</small> <span id="cntDescripHechos" align="right"> </span></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="exampleInputEmail1" class="col-sm-12 control-label">Historial Equipo</label>
                            {!! Form::textarea('historial',null,['class'=>'form-control','id'=>'mod_historial','placeholder'=>'Historial del equipo','rows'=>'3','readonly']) !!}
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
                            <button type="button" id="VerAnexosE" class="btn btn-success">Ver Anexos</button>
                        </div>
                        <div class="col-md-9">
                            <div id="anexosE"></div>
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

    <script>
        function obtener_datos_equipo(id) {

            var TipoEquipo      = $("#tipo_equipo" + id).val();
            var TipoIngreso     = $("#tipo_ingreso" + id).val();
            var EmpRenting      = $("#emp_renting" + id).val();
            var FechaIngreso    = $("#fecha_ingreso" + id).val();
            var Serial          = $("#serial" + id).val();
            var Marca           = $("#marca" + id).val();
            var Procesador      = $("#procesador" + id).val();
            var VelProcesador   = $("#vel_procesador" + id).val();
            var DiscoDuro       = $("#disco_duro" + id).val();
            var MemoriaRam      = $("#memoria_ram" + id).val();
            var Estado          = $("#estado_equipo" + id).val();
            var Evidencia       = $("#evidencia" + id).val();
            var Historial       = $("#historial" + id).val();

            $("#mod_idE").val(id);
            $("#mod_tipo_equipo").val(TipoEquipo);
            $("#mod_tipo_ingreso").val(TipoIngreso);
            $("#mod_emp_renting").val(EmpRenting);
            $("#mod_fecha_adquision").val(FechaIngreso);
            $("#mod_serial").val(Serial);
            $("#mod_marca").val(Marca);
            $("#mod_procesador").val(Procesador);
            $("#mod_vel_procesador").val(VelProcesador);
            $("#mod_disco_duro").val(DiscoDuro);
            $("#mod_memoria_ram").val(MemoriaRam);
            $("#mod_estado").val(Estado);
            $("#mod_evidencia").val(Evidencia);
            $("#mod_historial").val(Historial);

            $("#VerAnexosE").click(function(){
                document.getElementById('anexosE').innerHTML = Evidencia;
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
