@extends("Template.layout")

@section('titulo')
Profile
@endsection

@section('contenido')
<section class="content-header">
    <h1>Perfil Usuario</h1>
    <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Profile</a></li>

    </ol>
</section>

<section class="content">

        <div class="row">
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        {{--  <img class="profile-user-img img-responsive img-circle" src="../../dist/img/user4-128x128.jpg" alt="User profile picture">  --}}
                        {!! Session::get('ProfileUser') !!}
                        <h3 class="profile-username text-center">{!! Session::get('NombreUsuario') !!}</h3>

                        <p class="text-muted text-center">{!! Session::get('NombreRol') !!}</p>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Usuario desde</b> <a class="pull-right">{!! Session::get('FechaCreacion') !!}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Categoría</b> <a class="pull-right">{!! Session::get('NombreCategoria') !!}</a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <div class="col-md-8">

                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-success">
                            <div class="box-header with-border">
                                <h3 class="box-title"><strong>Editar Usuario</strong></h3>

                            </div>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        {!! Form::open(['action' => 'User\UsuariosController@actualizarUsuario', 'method' => 'post', 'enctype' => 'multipart/form-data','role' => 'form']) !!}

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="exampleInputEmail1">Contraseña</label>
                                                    {!! Form::input('password','password',$Contrasena,['class'=>'form-control','id'=>'password','placeholder'=>'Contraseña','type'=>'password']) !!}
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="exampleInputEmail1">Repetir Contraseña</label>
                                                    {!! Form::input('password','repeat_password',$Contrasena,['class'=>'form-control','id'=>'repeat_password','placeholder'=>'Confirmar Contraseña','type'=>'password']) !!}
                                                </div>
                                                <div class="col-md-4">
                                                        <label for="exampleInputFile">Foto</label>
                                                        <input type="file" id="profile_pic" name="profile_pic" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-footer">
                                            <button type="submit" class="btn btn-primary pull-right">Editar Usuario</button>
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

    </section>

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



@endsection
