@extends("Template.layout")

@section('titulo')
Tickets
@endsection

@section('contenido')

<section class="content-header">
    <h1><i class="fa fa-user-plus"></i> Tickets Creación de Usuario</h1>
    <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li>Tickets</a></li>
        <li class="active">Dashboard</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="box box-success">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modal-ticket-usuario"><i class="fa fa-plus-circle"></i>&nbsp;Crear Ticket de Creación de Usuario</button>
                        <br>
                        <br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table id="ticketsUsuario" class="display responsive hover" style="width: 100%;">
                            <thead style="background: linear-gradient(60deg,rgba(51,101,155,1),rgba(66,132,206,1));color: #ECF0F1;">
                                <tr>
                                    <th style="text-align: center;font-size: 13px;">Nro</th>
                                    <th style="text-align: center;font-size: 13px;">Nombre Usuario</th>
                                    <th style="text-align: center;font-size: 13px;">Nro. Identificación</th>
                                    <th style="text-align: center;font-size: 13px;">Cargo</th>
                                    <th style="text-align: center;font-size: 13px;">Sede</th>
                                    <th style="text-align: center;font-size: 13px;">Área</th>
                                    <th style="text-align: center;font-size: 13px;">Jefe Inmediato</th>
                                    <th style="text-align: center;font-size: 13px;">Fecha Ingreso</th>
                                    <th style="text-align: center;font-size: 13px;">Prioridad</th>
                                    <th style="text-align: center;font-size: 13px;">Estado</th>
                                    <th style="text-align: center;font-size: 13px;">RC</th>
                                    <th style="text-align: center;font-size: 13px;">IT</th>
                                    <th style="text-align: center;font-size: 13px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($TicketUsuario as $value)
                                <tr style="font-size: 13px;">
                                    <td>{{$value['id']}}</td>
                                    <td>{{$value['nombres']}}</td>
                                    <td>{{$value['identificacion']}}</td>
                                    <td>{{$value['cargo']}}</td>
                                    <td>{{$value['nombre_sede']}}</td>
                                    <td>{{$value['area']}}</td>
                                    <td>{{$value['jefe']}}</td>
                                    <td>{{$value['fecha_ingreso']}}</td>
                                    <td style="text-align:center;"><span class="{{$value['label']}}" style="font-size:13px;"><b>{{$value['nombre_prioridad']}}</b></span></td>
                                    <td>{{$value['nombre_estado']}}</td>
                                    <td>{{$value['estadorc']}}</td>
                                    <td>{{$value['estadoit']}}</td>
                                    <td style="text-align: center;"><a href="#" class="btn btn-success" title="Revisar" data-toggle="modal" data-target="#modal-ticket-usuario-upd" onclick="obtener_datos_ticket_usuario('{{$value['id']}}');"><i class="glyphicon glyphicon-search"></i></a></td>
                                    <input type="hidden" value="{{$value['id']}}" id="id{{$value['id']}}">
                                    <input type="hidden" value="{{$value['nombres']}}" id="nombres{{$value['id']}}">
                                    <input type="hidden" value="{{$value['identificacion']}}" id="identificacion{{$value['id']}}">
                                    <input type="hidden" value="{{$value['cargo']}}" id="cargo{{$value['id']}}">
                                    <input type="hidden" value="{{$value['id_sede']}}" id="id_sede{{$value['id']}}">
                                    <input type="hidden" value="{{$value['area']}}" id="area{{$value['id']}}">
                                    <input type="hidden" value="{{$value['jefe']}}" id="jefe{{$value['id']}}">
                                    <input type="hidden" value="{{$value['fecha_ingreso']}}" id="fecha_ingreso{{$value['id']}}">
                                    <input type="hidden" value="{{$value['email']}}" id="email{{$value['id']}}">
                                    <input type="hidden" value="{{$value['new_cargo']}}" id="new_cargo{{$value['id']}}">
                                    <input type="hidden" value="{{$value['funcionario_rem']}}" id="funcionario_rem{{$value['id']}}">
                                    <input type="hidden" value="{{$value['correo_fun']}}" id="correo_fun{{$value['id']}}">
                                    <input type="hidden" value="{{$value['new_email']}}" id="new_email{{$value['id']}}">
                                    <input type="hidden" value="{{$value['celular']}}" id="celular{{$value['id']}}">
                                    <input type="hidden" value="{{$value['datos']}}" id="datos{{$value['id']}}">
                                    <input type="hidden" value="{{$value['minutos']}}" id="minutos{{$value['id']}}">
                                    <input type="hidden" value="{{$value['equipo']}}" id="equipo{{$value['id']}}">
                                    <input type="hidden" value="{{$value['extension']}}" id="extension{{$value['id']}}">
                                    <input type="hidden" value="{{$value['app85']}}" id="app85{{$value['id']}}">
                                    <input type="hidden" value="{{$value['dinamica']}}" id="dinamica{{$value['id']}}">
                                    <input type="hidden" value="{{$value['other_app']}}" id="other_app{{$value['id']}}">
                                    <input type="hidden" value="{{$value['carpeta']}}" id="carpeta{{$value['id']}}">
                                    <input type="hidden" value="{{$value['vpn']}}" id="vpn{{$value['id']}}">
                                    <input type="hidden" value="{{$value['internet']}}" id="internet{{$value['id']}}">
                                    <input type="hidden" value="{{$value['cap85']}}" id="cap85{{$value['id']}}">
                                    <input type="hidden" value="{{$value['capdinamica']}}" id="capdinamica{{$value['id']}}">
                                    <input type="hidden" value="{{$value['prioridad']}}" id="prioridad{{$value['id']}}">
                                    <input type="hidden" value="{{$value['estado']}}" id="estado{{$value['id']}}">
                                    <input type="hidden" value="{{$value['observaciones']}}" id="observaciones{{$value['id']}}">
                                    <input type="hidden" value="{{$value['user_dominio']}}" id="user_dominio{{$value['id']}}">
                                    <input type="hidden" value="{{$value['estadorc']}}" id="estadorc{{$value['id']}}">
                                    <input type="hidden" value="{{$value['estadoit']}}" id="estadoit{{$value['id']}}">
                                    <input type="hidden" value="{{$value['estadoapp']}}" id="estadoapp{{$value['id']}}">
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
@include('Modals.ModalTicketUsuario')
@endsection

@section('scripts')

    <script src="{{asset("assets/dist/js/tickets.js")}}"></script>
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
            $('#fechaIngreso').datepicker({
                autoclose: true,
                language: 'es',
                todayBtn: true,
                format: 'dd-mm-yyyy',
                orientation: 'bottom auto',
                endDate: '+0d'
            });
        });
</script>


@endsection
