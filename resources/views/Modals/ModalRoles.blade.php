<div class="modal fade bs-example-modal-md-udpR" id="modal-roles-upd">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Actualizar Rol</h4>
                </div>

                {!! Form::open(['action' => 'Admin\RolesController@actualizarRol', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}
                    <div class="modal-body">
                        <input type="hidden" name="idR" id="mod_idR">
                        <div class="box-body">
                            <div class="form-group">
                                <div class="row">
                                    <label for="inputEmail3" class="col-sm-4 control-label">Nombre Rol</label>
                                    <div class="col-sm-8">
                                        {!! Form::text('nombre_rol_upd',null,['class'=>'form-control','id'=>'mod_nombre_rol_upd','placeholder'=>'Nombre Rol']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <label for="inputEmail3" class="col-sm-4 control-label">Activo</label>
                                    <div class="col-sm-4">
                                        {!! Form::select('id_activoR',$Activo,null,['class'=>'form-control','id'=>'mod_activoR_upd','required']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Actualizar Rol</button>
                    </div>
                {!!  Form::close() !!}
            </div>
        </div>

    </div>
    <div class="modal fade bs-example-modal-md-udpC" id="modal-categorias-upd">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Actualizar Categoria</h4>
                    </div>

                    {!! Form::open(['action' => 'Admin\RolesController@actualizarCategoria', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}
                        <div class="modal-body">
                                <input type="hidden" name="idC" id="mod_idC">
                            <div class="box-body">
                                <div class="form-group">
                                    <div class="row">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Nombre Categoria</label>
                                        <div class="col-sm-8">
                                            {!! Form::text('nombre_categoria_upd',$CategoriaName,['class'=>'form-control','id'=>'mod_nombre_categoria_upd','placeholder'=>'Nombre Sede','required']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label for="inputEmail3" class="col-sm-4 control-label">Activo</label>
                                        <div class="col-sm-4">
                                            {!! Form::select('id_activoC',$Activo,null,['class'=>'form-control','id'=>'mod_activoC_upd','required']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Actualizar Categoria</button>
                        </div>
                    {!!  Form::close() !!}
                </div>
            </div>

        </div>
        <script>
                function obtener_datos_rol(id) {
                    var nombre = $("#name" + id).val();
                    var Activo = $("#activoR" + id).val();

                    $("#mod_idR").val(id);
                    $("#mod_nombre_rol_upd").val(nombre);
                    $("#mod_activoR_upd").val(Activo);
                }
                function obtener_datos_categoria(id) {
                    var nombre = $("#categoria" + id).val();
                    var Activo = $("#activoC" + id).val();

                    $("#mod_idC").val(id);
                    $("#mod_nombre_categoria_upd").val(nombre);
                    $("#mod_activoC_upd").val(Activo);
                }
        </script>
