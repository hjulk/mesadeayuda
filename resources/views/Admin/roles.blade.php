@extends("Template.layout")

@section('titulo')
Roles
@endsection

@section('contenido')
<section class="content-header">
    <h1><i class="fa fa-user-secret"></i> Roles y Categorías</h1>
    <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li>Admin</a></li>
        <li class="active">Roles y Categorías</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title"><strong>Roles</strong></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-4">
                                <span class="info-box-icon bg-aqua"><i class="ion ion-ios-people-outline"></i></span>
                            </div>
                            <div class="col-md-8">
                                {!! Form::open(['action' => 'Admin\RolesController@crearRol', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off']) !!}
                                <div class="form-group">
                                    <div class="row">
                                        <label for="exampleInputEmail1">Nombre</label>
                                        {!! Form::text('nombre_rol',null,['class'=>'form-control','id'=>'nombre_rol','placeholder'=>'Nombre Rol','required']) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <button type="submit" class="btn btn-primary pull-right">Crear Rol</button>
                                    </div>
                                </div>
                                {!!  Form::close() !!}
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <table id="roles" class="display responsive hover" style="width: 100%;">
                                <thead style="background: linear-gradient(60deg,rgba(51,101,155,1),rgba(66,132,206,1));color: #ECF0F1;">
                                    <tr>
                                        <th style="text-align: center;">Id</th>
                                        <th style="text-align: center;">Nombre</th>
                                        <th style="text-align: center;">Activo</th>
                                        <th style="text-align: center;">Editar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($Roles as $rol)
                                    <tr>
                                        <td>{{$rol['id']}}</td>
                                        <td>{{$rol['name']}}</td>
                                        <td>{{$rol['activo']}}</td>
                                        <td style="text-align: center;"><a href="#" class="btn btn-warning" title="Editar" onclick="obtener_datos_rol('{{$rol['id']}}');" data-toggle="modal" data-target=".bs-example-modal-md-udpR"><i class="glyphicon glyphicon-edit"></i></a></td>
                                        <input type="hidden" value="{{$rol['id']}}" id="id{{$rol['id']}}">
                                        <input type="hidden" value="{{$rol['name']}}" id="name{{$rol['id']}}">
                                        <input type="hidden" value="{{$rol['activoR']}}" id="activoR{{$rol['id']}}">
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title"><strong>Categorias</strong></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                            <div class="col-md-4">
                                    <span class="info-box-icon bg-aqua"><i class="ion ion-ios-pricetag-outline"></i></span>
                            </div>
                        <div class="col-md-8">
                            {!! Form::open(['action' => 'Admin\RolesController@crearCategoria', 'method' => 'post', 'enctype' => 'multipart/form-data','role' => 'form','autocomplete'=>'off']) !!}
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nombre</label>
                                    {!! Form::text('nombre_categoria',null,['class'=>'form-control','id'=>'nombre_categoria','placeholder'=>'Nombre Categoria','required']) !!}
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary pull-right">Crear Categoria</button>
                                </div>
                            {!!  Form::close() !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table id="categorias" class="display responsive hover" style="width: 100%;">
                                <thead style="background: linear-gradient(60deg,rgba(51,101,155,1),rgba(66,132,206,1));color: #ECF0F1;">
                                    <tr>
                                        <th style="text-align: center;">Id</th>
                                        <th style="text-align: center;">Nombre</th>
                                        <th style="text-align: center;">Activo</th>
                                        <th style="text-align: center;">Editar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($Categorias as $categoria)
                                    <tr>
                                        <td>{{$categoria['id']}}</td>
                                        <td>{{$categoria['name']}}</td>
                                        <td>{{$categoria['activo']}}</td>
                                        <td style="text-align: center;"><a href="#" class="btn btn-warning" title="Editar" onclick="obtener_datos_categoria('{{$categoria['id']}}');" data-toggle="modal" data-target=".bs-example-modal-md-udpC"><i class="glyphicon glyphicon-edit"></i></a></td>
                                        <input type="hidden" value="{{$categoria['id']}}" id="id{{$categoria['id']}}">
                                        <input type="hidden" value="{{$categoria['name']}}" id="categoria{{$categoria['id']}}">
                                        <input type="hidden" value="{{$categoria['activoC']}}" id="activoC{{$categoria['id']}}">
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('Modals.ModalRoles')

@endsection

@section('scripts')

    <script src="{{asset("assets/dist/js/roles.js")}}"></script>
    <script>
        @if (session("mensaje"))
                toastr.success("{{ session("mensaje") }}");
        @endif

        @if (count($errors) > 0)
            @foreach($errors -> all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>


@endsection
