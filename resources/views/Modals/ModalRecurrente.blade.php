<div class="modal fade" id="modal-recurrente">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Actualizar Asunto</h4>
            </div>
            {!! Form::open(['url' => 'actualizarTicketRecurrente', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off','id'=>'form-ticket']) !!}
            <div class="modal-body">
                <input type="hidden" name="idT" id="mod_idT">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="inputEmail3" class="col-sm-12 control-label">Asunto Ticket</label>
                            {!! Form::text('asunto_upd',null,['class'=>'form-control','id'=>'mod_asunto','placeholder'=>'Ingrese el asunto']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-5">
                            <label for="inputEmail3" class="col-sm-12 control-label">Categoria</label>
                            {!! Form::select('categoria_upd',$Categoria,null,['class'=>'form-control','id'=>'mod_categoria']) !!}
                        </div>
                        <div class="col-md-3">
                            <label for="inputEmail3" class="col-sm-12 control-label">Prioridad</label>
                            {!! Form::select('prioridad_upd',$Prioridad,null,['class'=>'form-control','id'=>'mod_prioridad']) !!}
                        </div>
                        <div class="col-md-4">
                            <label for="inputEmail3" class="col-sm-12 control-label">Tipo Usuario</label>
                            {!! Form::select('tipo_usuario_upd',$Tipo,null,['class'=>'form-control','id'=>'mod_tipo_usuario']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="inputEmail3" class="col-sm-12 control-label">Activo</label>
                            {!! Form::select('activo',$Activo,null,['class'=>'form-control','id'=>'mod_activo']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-success">Actualizar Asunto</button>
            </div>
            {!!  Form::close() !!}

        </div>
    </div>
</div>
    <script>
        function obtener_datos_recurrente(id) {
            var Nombre      = $("#nombre" + id).val();
            var Categoria   = $("#category_id" + id).val();
            var Prioridad   = $("#priority_id" + id).val();
            var Activo      = $("#id_activo" + id).val();
            var Tipo        = $("#tipo" + id).val();

            $("#mod_idT").val(id);
            $("#mod_asunto").val(Nombre);
            $("#mod_categoria").val(Categoria);
            $("#mod_prioridad").val(Prioridad);
            $("#mod_activo").val(Activo);
            $("#mod_tipo_usuario").val(Tipo);
        }
    </script>
