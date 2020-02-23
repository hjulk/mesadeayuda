@extends("Template.layout")

@section('titulo')
Turnos
@endsection

@section('contenido')
<section class="content-header">
    <h1><i class="fa fa-calendar-o"></i>&nbsp;Turnos</h1>
    <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li>Admin</a></li>
        <li class="active">Turnos</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="box box-primary">
            <div class="box-body">
                @if(Session::get('Rol') === 1)
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modal-turnos"><i class="fa fa-plus-circle"></i>&nbsp;Crear Turno</button>
                            <br>
                            <br>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <table id="turnos" class="display responsive hover" style="width: 100%;">
                            <thead style="background: linear-gradient(60deg,rgba(51,101,155,1),rgba(66,132,206,1));color: #ECF0F1;">
                                <tr>
                                    <th style="text-align: center;">Nro</th>
                                    <th style="text-align: center;">Agente Mesa de Ayuda</th>
                                    <th style="text-align: center;">Fecha Inicio</th>
                                    <th style="text-align: center;">Fecha Fin</th>
                                    <th style="text-align: center;">Horario Turno</th>
                                    <th style="text-align: center;">Sede</th>
                                    <th style="text-align: center;">Disponible</th>
                                    <th style="text-align: center;">Editar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($Turnos as $value)
                                <tr>
                                    <td>{{$value['id']}}</td>
                                    <td>{{$value['nombre_agente1']}}</td>
                                    <td>{{$value['fecha_inicial']}}</td>
                                    <td>{{$value['fecha_final']}}</td>
                                    <td>{{$value['horario']}}</td>
                                    <td>{{$value['sede']}}</td>
                                    <td>{{$value['disponible']}}</td>
                                    <td><a href="#" class="btn btn-warning" title="Editar" data-toggle="modal" data-target="#modal-turnos-upd" onclick="obtener_datos_turno('{{$value['id']}}');"><i class="glyphicon glyphicon-edit"></i></a></td>
                                    <input type="hidden" value="{{$value['id']}}" id="id{{$value['id']}}">
                                    <input type="hidden" value="{{$value['agente1']}}" id="agente1{{$value['id']}}">
                                    <input type="hidden" value="{{$value['fecha_inicial']}}" id="fecha_inicial{{$value['id']}}">
                                    <input type="hidden" value="{{$value['fecha_final']}}" id="fecha_final{{$value['id']}}">
                                    <input type="hidden" value="{{$value['id_sede']}}" id="id_sede{{$value['id']}}">
                                    <input type="hidden" value="{{$value['id_horario']}}" id="id_horario{{$value['id']}}">
                                    <input type="hidden" value="{{$value['disponible']}}" id="disponible{{$value['id']}}">
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
@include('Modals.ModalTurnos')
@endsection
@section('scripts')
    <script src="{{asset("assets/dist/js/usuarios.js")}}"></script>
    <script>
        @if (session("mensaje"))
            toastr.success("{{ session("mensaje") }}");
        @endif

        @if (session("precaucion"))
            toastr.warning("{{ session("precaucion") }}");
        @endif
        @if (count($errors) > 0)
            @foreach($errors -> all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>
    <script>
        $(function () {
            var today = new Date();
            var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
            var time = today.getHours() + ":" + today.getMinutes();
            var dateTime = date+' '+time;
            $('#fecha_inicio').datepicker({
                autoclose: true,
                language: 'es',
                todayBtn: true,
                format: 'dd-mm-yyyy',
                orientation: 'bottom auto',
                startDate: '-0d'
            });
            $('#mod_fecha_inicio').datepicker({
                autoclose: true,
                language: 'es',
                todayBtn: true,
                format: 'dd-mm-yyyy',
                orientation: 'bottom auto',
                startDate: '-0d'
            });
            $('#fecha_fin').datepicker({
                autoclose: true,
                language: 'es',
                todayBtn: true,
                format: 'dd-mm-yyyy',
                orientation: 'bottom auto',
                startDate: '-0d'
            });
            $('#mod_fecha_fin').datepicker({
                autoclose: true,
                language: 'es',
                todayBtn: true,
                format: 'dd-mm-yyyy',
                orientation: 'bottom auto',
                startDate: '-0d'
            });
        });
    </script>
@endsection
