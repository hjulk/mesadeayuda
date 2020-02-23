<div class="modal fade bd-example-modal-xl" id="modal-sedes"  tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Crear Sede</h4>
            </div>
            {!! Form::open(['action' => 'Admin\SedesController@crearSede', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Nombre Sede</label>
                                {!! Form::text('nombre',$Sede,['class'=>'form-control','id'=>'nombre','placeholder'=>'Nombre Sede','required']) !!}
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Descripción</label>
                                {!! Form::text('descripcion',$Descripcion,['class'=>'form-control','id'=>'descripcion','placeholder'=>'Descripción de la Sede']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Crear Sede</button>
                </div>
            {!!  Form::close() !!}
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-xl" id="modal-sedes-upd"  tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Actualizar Sede</h4>
            </div>
            {!! Form::open(['action' => 'Admin\SedesController@actualizarSede', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}
                <div class="modal-body">
                    <input type="hidden" name="idS" id="mod_idS">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Nombre Sede</label>
                                {!! Form::text('nombre_upd',$Sede,['class'=>'form-control','id'=>'mod_nombre_upd','placeholder'=>'Nombre Sede','required']) !!}
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Descripción</label>
                                {!! Form::text('descripcion_upd',$Descripcion,['class'=>'form-control','id'=>'mod_descripcion_upd','placeholder'=>'Descripción de la Sede']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Activo</label>
                                {!! Form::select('activo',$Activo,null,['class'=>'form-control','id'=>'mod_activo_upd','required']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Actualizar Sede</button>
                </div>
            {!!  Form::close() !!}
        </div>
    </div>
</div>

    <script>
        function obtener_datos_sede(id) {
            var Nombre      = $("#name" + id).val();
            var Descripcion = $("#description" + id).val();
            var Activo      = $("#activo" + id).val();

            $("#mod_idS").val(id);
            $("#mod_nombre_upd").val(Nombre);
            $("#mod_descripcion_upd").val(Descripcion);
            $("#mod_activo_upd").val(Activo);
        }
    </script>

    <div class="modal fade bd-example-modal-xl" id="modal-areas"  tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Crear Área</h4>
            </div>
            {!! Form::open(['action' => 'Admin\SedesController@crearArea', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Nombre Área</label>
                                {!! Form::text('nombre_area',null,['class'=>'form-control','id'=>'nombre_area','placeholder'=>'Nombre Área','required']) !!}
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Sede</label>
                                {!! Form::select('sede',$NombreSede,null,['class'=>'form-control','id'=>'sede','required']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Crear Área</button>
                </div>
            {!!  Form::close() !!}
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-xl" id="modal-areas-upd"  tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="color: white;background-color: rgba(162, 27, 37, 1);">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Actualizar Área</h4>
            </div>
            {!! Form::open(['action' => 'Admin\SedesController@actualizarArea', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}
                <div class="modal-body">
                    <input type="hidden" name="idA" id="mod_idA">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Nombre Área</label>
                                {!! Form::text('nombre_area_upd',null,['class'=>'form-control','id'=>'mod_nombre_area','placeholder'=>'Nombre Área','required']) !!}
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Sede</label>
                                {!! Form::select('sede_upd',$NombreSede,null,['class'=>'form-control','id'=>'mod_sede','required']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1" class="col-sm-12 control-label">Activo</label>
                                {!! Form::select('activo_area',$Activo,null,['class'=>'form-control','id'=>'mod_activo_area','required']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Actualizar Área</button>
                </div>
            {!!  Form::close() !!}
        </div>
    </div>
</div>

    <script>
        function obtener_datos_area(id) {
            var Nombre      = $("#nombre" + id).val();
            var Activo      = $("#activo" + id).val();
            var Sede        = $("#project_id" + id).val();

            $("#mod_idA").val(id);
            $("#mod_nombre_area").val(Nombre);
            $("#mod_sede").val(Sede);
            $("#mod_activo_area").val(Activo);
        }
    </script>
