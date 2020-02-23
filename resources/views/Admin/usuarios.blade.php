@extends("Template.layout")

@section('titulo')
Usuarios
@endsection

@section('contenido')

<section class="content-header">
    <h1><i class="fa fa-users"></i> Usuarios</h1>
    <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li>Admin</a></li>
        <li class="active">Usuarios</li>
    </ol>
</section>

<section class="content">

    <div class="row">
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-body box-profile">
                    {!! Session::get('ProfileUser') !!}
                    <h3 class="profile-username text-center">{!! Session::get('NombreUsuario') !!}</h3>

                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>Usuario desde</b> <a class="pull-right">{!! Session::get('FechaCreacion') !!}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Área</b> <a class="pull-right">{!! Session::get('NombreCategoria') !!}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Rol</b> <a class="pull-right">{!! Session::get('NombreRol') !!}</a>
                        </li>
                    </ul>
                    <a href="#" class="btn btn-primary btn-block" title="Editar" data-toggle="modal" data-target=".bs-example-modal-md-updAdmin"><b>Editar</b></a>
                </div>
            </div>
        </div>
        <div class="col-md-9">

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title"><strong>Crear Usuario</strong></h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>

                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    {!! Form::open(['action' => 'Admin\UsuarioController@crearUsuario', 'method' => 'post', 'enctype' => 'multipart/form-data','autocomplete'=>'off','id'=>'form-create_user']) !!}

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="exampleInputEmail1">Nombre Completo</label>
                                                {!! Form::text('nombre_usuario',$NombreUsuario,['class'=>'form-control','id'=>'nombre_usuario','placeholder'=>'Nombre Completo','required']) !!}
                                            </div>
                                            <div class="col-md-3">
                                                <label for="exampleInputEmail1">Usuario</label>
                                                {!! Form::text('username',$UserName,['class'=>'form-control','id'=>'username','placeholder'=>'Usuario','required']) !!}
                                            </div>
                                            <div class="col-md-5">
                                                <label for="exampleInputEmail1">Correo Electrónico</label>
                                                {!! Form::email('email',$Correo,['class'=>'form-control','id'=>'email','placeholder'=>'Correo Electrónico','required']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="exampleInputEmail1">Contraseña</label>
                                                {!! Form::input('password','password',$Contrasena,['class'=>'form-control','id'=>'password','placeholder'=>'Contraseña','type'=>'password','required']) !!}
                                            </div>
                                            <div class="col-md-3">
                                                <label for="exampleInputEmail1">Rol</label>
                                                {!! Form::select('id_rol',$Rol,null,['class'=>'form-control','id'=>'id_rol','required']) !!}
                                            </div>
                                            <div class="col-md-3">
                                                <label for="exampleInputEmail1">Area</label>
                                                {!! Form::select('id_categoria',$Categoria,null,['class'=>'form-control','id'=>'id_categoria','required']) !!}
                                            </div>
                                            <div class="col-md-3">
                                                <label for="exampleInputFile">Foto</label>
                                                <input type="file" id="profile_pic" name="profile_pic" class="form-control" accept="image/*" size="5120">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary pull-right">Crear Usuario</button>
                                    </div>
                                    {!!  Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title"><strong>Listado Usuarios</strong></h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>

                            </div>
                        </div>
                        <div class="box-body">


                            <table id="usuarios" class="display responsive hover" style="width: 100%;">
                                    <thead style="background: linear-gradient(60deg,rgba(51,101,155,1),rgba(66,132,206,1));color: #ECF0F1;">
                                    <tr>
                                        <th style="text-align: center;">Nombre Completo</th>
                                        <th style="text-align: center;">Usuario</th>
                                        <th style="text-align: center;">Correo</th>
                                        <th style="text-align: center;">Rol</th>
                                        <th style="text-align: center;">Area</th>
                                        <th style="text-align: center;">Fecha Creacion</th>
                                        <th style="text-align: center;">Estado</th>
                                        <th style="text-align: center;">Editar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($Usuarios as $usuario)
                                    <tr>
                                        <td>{{$usuario['nombre']}}</td>
                                        <td>{{$usuario['username']}}</td>
                                        <td>{{$usuario['email']}}</td>
                                        <td>{{$usuario['rol']}}</td>
                                        <td>{{$usuario['categoria']}}</td>
                                        <td>{{$usuario['fecha_creacion']}}</td>
                                        <td>{{$usuario['estado']}}</td>
                                        <td style="text-align: center;"><a href="#" class="btn btn-warning" title="Editar" onclick="obtener_datos_usuario('{{$usuario['id']}}');" data-toggle="modal" data-target=".bs-example-modal-md-udpU"><i class="glyphicon glyphicon-edit"></i></a></td>
                                        <input type="hidden" value="{{$usuario['id']}}" id="id{{$usuario['id']}}">
                                        <input type="hidden" value="{{$usuario['nombre']}}" id="nombre{{$usuario['id']}}">
                                        <input type="hidden" value="{{$usuario['username']}}" id="username{{$usuario['id']}}">
                                        <input type="hidden" value="{{$usuario['email']}}" id="email{{$usuario['id']}}">
                                        <input type="hidden" value="{{$usuario['activo']}}" id="activo{{$usuario['id']}}">
                                        <input type="hidden" value="{{$usuario['id_rol']}}" id="id_rol{{$usuario['id']}}">
                                        <input type="hidden" value="{{$usuario['id_categoria']}}" id="id_categoria{{$usuario['id']}}">
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
@include('Modals.ModalUsuarios')
@endsection

@section('scripts')

    <script src="{{asset("assets/dist/js/usuarios.js")}}"></script>
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
    <script>
        $('#form-create_user').submit(function() {
            var fileSize = $('#profile_pic')[0].files[0].size;
            var sizekiloBytes = parseInt(fileSize / 1024);
            if (sizekiloBytes >  $('#profile_pic').attr('size')) {
                alert('El tamaño supera el limite permitido de 5mb');
                return false;
            }
        });
        $('#form-admin-upd').submit(function() {
            var fileSize = $('#profile_pic_amd')[0].files[0].size;
            var sizekiloBytes = parseInt(fileSize / 1024);
            if (sizekiloBytes >  $('#profile_pic_amd').attr('size')) {
                alert('El tamaño supera el limite permitido de 5mb');
                return false;
            }
        });
        $('#form-upd_user').submit(function() {
            var fileSize = $('#profile_pic_upd')[0].files[0].size;
            var sizekiloBytes = parseInt(fileSize / 1024);
            if (sizekiloBytes >  $('#profile_pic_upd').attr('size')) {
                alert('El tamaño supera el limite permitido de 5mb');
                return false;
            }
        });
    </script>
@endsection
