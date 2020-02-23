<div class="modal fade bs-example-modal-md-udpR" id="modal-turnos">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Crear Turno</h4>
            </div>
            {!! Form::open(['action' => 'Admin\TurnosController@crearTurno', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Nombre Agente</label>
                                {!! Form::select('agente',$Agente,null,['class'=>'form-control','id'=>'agente','required']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Fecha Inicio</label>
                                {!! Form::text('fecha_inicio',null,['class'=>'form-control','id'=>'fecha_inicio','placeholder'=>'Fecha Inicio','required']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Fecha Fin</label>
                                {!! Form::text('fecha_fin',null,['class'=>'form-control','id'=>'fecha_fin','placeholder'=>'Fecha Fin','required']) !!}
                                <div align="right"><small class="text-muted" style="font-size: 73%;">En blanco, turno indefinido</small> <span id="cntDescripHechos" align="right"> </span></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Sede</label>
                                {!! Form::select('sede',$Sede,null,['class'=>'form-control','id'=>'sede','required']) !!}
                            </div>
                            <div class="col-md-5">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Horario</label>
                                {!! Form::select('horario',$Horario,null,['class'=>'form-control','id'=>'horario','required']) !!}
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Disponibilidad</label>
                                {!! Form::select('disponibilidad',$Disponibilidad,null,['class'=>'form-control','id'=>'disponibilidad','required']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Crear Turno</button>
                </div>
            {!!  Form::close() !!}
        </div>
    </div>
</div>
<!-- ACTUALIZACIÓN -->
<div class="modal fade bs-example-modal-md-udpR" id="modal-turnos-upd">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Actualizar Turno</h4>
            </div>
            {!! Form::open(['action' => 'Admin\TurnosController@actualizarTurno', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}
                <div class="modal-body">
                    <input type="hidden" name="idTu" id="mod_idTu">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Nombre Agente</label>
                                {!! Form::select('agente_upd',$Agente,null,['class'=>'form-control','id'=>'mod_agente']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Fecha Inicio</label>
                                {!! Form::text('fecha_inicio_upd',null,['class'=>'form-control','id'=>'mod_fecha_inicio','placeholder'=>'Fecha Inicio']) !!}
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Fecha Fin</label>
                                {!! Form::text('fecha_fin_upd',null,['class'=>'form-control','id'=>'mod_fecha_fin','placeholder'=>'Fecha Fin']) !!}
                                <div align="right"><small class="text-muted" style="font-size: 73%;">En blanco, turno indefinido</small> <span id="cntDescripHechos" align="right"> </span></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Sede</label>
                                {!! Form::select('sede_upd',$Sede,null,['class'=>'form-control','id'=>'mod_sede']) !!}
                            </div>
                            <div class="col-md-5">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Horario</label>
                                {!! Form::select('horario_upd',$Horario,null,['class'=>'form-control','id'=>'mod_horario']) !!}
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Disponibilidad</label>
                                {!! Form::select('disponibilidad_upd',$Disponibilidad,null,['class'=>'form-control','id'=>'mod_disponibilidad']) !!}
                            </div>
                        </div>
                    </div>
                    </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Actualizar Turno</button>
                </div>
            {!!  Form::close() !!}
        </div>
    </div>
</div>

    <script>
        function obtener_datos_turno(id) {
            var Agente          = $("#agente1" + id).val();
            var FechaInicial    = $("#fecha_inicial" + id).val();
            var FechaFinal      = $("#fecha_final" + id).val();
            var Sede            = $("#id_sede" + id).val();
            var Horario         = $("#id_horario" + id).val();
            var Disponibilidad  = $("#disponible" + id).val();

            $("#mod_idTu").val(id);
            $("#mod_agente").val(Agente);
            $("#mod_fecha_inicio").val(FechaInicial);
            $("#mod_fecha_fin").val(FechaFinal);
            $("#mod_sede").val(Sede);
            $("#mod_horario").val(Horario);
            $("#mod_disponibilidad").val(Disponibilidad);
        }
    </script>
