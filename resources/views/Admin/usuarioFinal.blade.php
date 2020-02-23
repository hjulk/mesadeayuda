@extends("Template.layout")

@section('titulo')
Usuario Final
@endsection

@section('contenido')

<section class="content-header">
    <h1><i class="fa fa-users"></i> Usuario Final</h1>
    <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li>Admin</a></li>
        <li class="active">Usuario Final</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#usuario"><i class="fa fa-user-plus"></i>&nbsp;Crear Usuario</button>
                        <br>
                        <br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table id="usuarioFinal" class="display responsive hover" style="width: 100%;">
                            <thead style="background: linear-gradient(60deg,rgba(51,101,155,1),rgba(66,132,206,1));color: #ECF0F1;">
                                <tr>
                                    <th style="text-align: center;">Nombre Completo</th>
                                    <th style="text-align: center;">Usuario</th>
                                    <th style="text-align: center;">Correo</th>
                                    <th style="text-align: center;">Sede</th>
                                    <th style="text-align: center;">Area</th>
                                    <th style="text-align: center;">Fecha Creacion</th>
                                    <th style="text-align: center;">Estado</th>
                                    <th style="text-align: center;">Editar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($UsuarioFinal as $usuario)
                                <tr>
                                    <td>{{$usuario['nombre']}}</td>
                                    <td>{{$usuario['username']}}</td>
                                    <td>{{$usuario['email']}}</td>
                                    <td>{{$usuario['nombresede']}}</td>
                                    <td>{{$usuario['nombrearea']}}</td>
                                    <td>{{$usuario['fecha_creacion']}}</td>
                                    <td>{{$usuario['estado']}}</td>
                                    <td style="text-align: center;"><a href="#" class="btn btn-warning" title="Editar" onclick="obtener_datos_usuario_final('{{$usuario['id']}}');" data-toggle="modal" data-target="#usuario-final"><i class="glyphicon glyphicon-edit"></i></a></td>
                                    <input type="hidden" value="{{$usuario['id']}}" id="id{{$usuario['id']}}">
                                    <input type="hidden" value="{{$usuario['nombre']}}" id="nombre{{$usuario['id']}}">
                                    <input type="hidden" value="{{$usuario['username']}}" id="username{{$usuario['id']}}">
                                    <input type="hidden" value="{{$usuario['email']}}" id="email{{$usuario['id']}}">
                                    <input type="hidden" value="{{$usuario['cargo']}}" id="cargo{{$usuario['id']}}">
                                    <input type="hidden" value="{{$usuario['activo']}}" id="activo{{$usuario['id']}}">
                                    <input type="hidden" value="{{$usuario['sede']}}" id="sede{{$usuario['id']}}">
                                    <input type="hidden" value="{{$usuario['area']}}" id="area{{$usuario['id']}}">
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
@include('Modals.ModalUsuarioFinal')
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
    <script>
        function Area() {
            var selectBox = document.getElementById("sede");
            var selectedValue = selectBox.options[selectBox.selectedIndex].value;
            var tipo = 'post';
            var select = document.getElementById("area");

            $.ajax({
                url: "{{route('buscarArea')}}",
                type: "get",
                data: {_method: tipo, id_sede: selectedValue},
                success: function (data) {
                    var vValido = data['valido'];

                    if (vValido === 'true') {
                        var ListUsuario = data['Usuario'];
                        select.options.length = 0;
                        for (index in ListUsuario) {
                            select.options[select.options.length] = new Option(ListUsuario[index], index);
                        }
                        document.ready = document.getElementById("area").value = '';
                    }

                }
            });
        }
    </script>
@endsection
