@extends("Template.layoutMonitoreo")
@section('titulo')
Dahsboard
@endsection
@section('styles')
    <style>
        .nav-tabs-custom>.nav-tabs>li.active>a{
            color: brown;
        }
        .nav-tabs-custom>.nav-tabs>li.active {
            border-top-color: rgb(162, 27, 37) !important;
        }
    </style>
@endsection
@section('contenido')
<section class="content">
    <div class="row">
        <div class="box box-danger">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-3">
                            <div class="box-body">
                                <div class="callout callout-danger" style="background-color: #dd4b3978 !important;">
                                    <img src="{{asset("assets/Solicitud/support.png")}}" alt="image" style="width: 50%;margin: 10px 55px 10px 55px;"/>
                                    <h3 style="color:black;">Tenga en cuenta:</h3>
                                    <p style="color:black;font-size:2.2vh;text-align:justify;">
                                        <b>Incidente: </b>cualquier evento que INTERRUMPA el funcionamiento normal de un servicio. Ejemplos: falla de la red, falla de un pc, falla de un aplicativo, entre otros.<br>
                                    </p>
                                    <p style="color:black;font-size:2.2vh;text-align:justify;">
                                        <b>Requerimiento: </b>Se define como una solicitud formal para que algo sea PROVISTO. Ejemplos: creación de usuarios, ajuste de perfil de usuarios, creación de VPN, solicitud de nuevas lineas celulares, solicitud de movimientos.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            {{-- <button class="btn btn-primary" data-toggle="modal" data-target="#modal-solicitud"><i class="fa fa-plus-circle"></i>&nbsp;Crear Ticket</button>
                            <br> --}}
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs pull-right ui-sortable-handle">
                                    <li class=""><a href="#finalizados" data-toggle="tab" aria-expanded="false"><b>Tickets Finalizados</b></a></li>
                                    <li class="active"><a href="#actuales" data-toggle="tab" aria-expanded="true"><b>Tickets Actuales</b></a></li>
                                    <li class="pull-left header"><button class="btn btn-primary" data-toggle="modal" data-target="#modal-solicitud"><i class="fa fa-plus-circle"></i>&nbsp;Crear Ticket</button></li>
                                </ul>
                            </div>
                            <div class="tab-content no-padding">
                                <div class="tab-pane active" id="actuales">
                                    <table id="solicitudes" class="display responsive hover" style="width: 100%;">
                                        <thead style="background: linear-gradient(60deg,rgba(51,101,155,1),rgba(66,132,206,1));color: #ECF0F1;">
                                            <tr>
                                                <th style="text-align: center;font-size:2vh;">Nro</th>
                                                <th style="text-align: center;font-size:2vh;">Tipo</th>
                                                <th style="text-align: center;font-size:2vh;">Asunto</th>
                                                <th style="text-align: center;font-size:2vh;">Sede</th>
                                                <th style="text-align: center;font-size:2vh;">Area</th>
                                                <th style="text-align: center;font-size:2vh;">Prioridad</th>
                                                <th style="text-align: center;font-size:2vh;">Estado</th>
                                                <th style="text-align: center;font-size:2vh;">Fecha Creación</th>
                                                <th style="text-align: center;font-size:2vh;">Revisar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($Tickets as $value)
                                                <tr>
                                                    <td style="text-align: center;font-size:2vh;">{{$value['id']}}</td>
                                                    <td style="text-align:center;font-size:2vh;">{{$value['tipo_ticket']}}</td>
                                                    <td style="font-size:2vh;">{{$value['title']}}</td>
                                                    <td style="font-size:2vh;">{{$value['sede']}}</td>
                                                    <td style="font-size:2vh;">{{$value['area']}}</td>
                                                    <td style="text-align:center;"><span class="{{$value['label']}}" style="font-size:13px;"><b>{{$value['prioridad']}}</b></span></td>
                                                    <td style="font-size:2vh;">{{$value['estado']}}</td>
                                                    <td style="font-size:2vh;">{{$value['created_at']}}</td>
                                                    <td style="text-align:center;"><a href="#" class="btn btn-info" title="Editar" data-toggle="modal" data-target="#modal-tickets-upd" onclick="obtener_datos_ticket('{{$value['id']}}');"><i class="glyphicon glyphicon-search"></i></a></td>
                                                    <input type="hidden" value="{{$value['id']}}" id="id{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['tipo_ticket']}}" id="tipo_ticket{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['sede']}}" id="sede{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['prioridad']}}" id="prioridad{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['categoria']}}" id="categoria{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['asignado_a']}}" id="asignado_a{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['estado']}}" id="estado{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['asigned_id']}}" id="asigned_id{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['title']}}" id="title{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['description']}}" id="description{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['area']}}" id="area{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['evidencia']}}" id="evidencia{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['historial']}}" id="historial{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['name_user']}}" id="name_user{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['user_email']}}" id="user_email{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['tel_user']}}" id="tel_user{{$value['id']}}">
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="finalizados">
                                    <table id="solicitudesF" class="display responsive hover" style="width: 100%;">
                                        <thead style="background: linear-gradient(60deg,rgba(51,101,155,1),rgba(66,132,206,1));color: #ECF0F1;">
                                            <tr>
                                                <th style="text-align: center;font-size:2vh;">Nro</th>
                                                <th style="text-align: center;font-size:2vh;">Tipo</th>
                                                <th style="text-align: center;font-size:2vh;">Asunto</th>
                                                <th style="text-align: center;font-size:2vh;">Sede</th>
                                                <th style="text-align: center;font-size:2vh;">Area</th>
                                                <th style="text-align: center;font-size:2vh;">Prioridad</th>
                                                <th style="text-align: center;font-size:2vh;">Estado</th>
                                                <th style="text-align: center;font-size:2vh;">Fecha Actualización</th>
                                                <th style="text-align: center;font-size:2vh;">Revisar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($TicketsF as $value)
                                                <tr>
                                                    <td style="text-align: center;font-size:2vh;">{{$value['id']}}</td>
                                                    <td style="text-align:center;font-size:2vh;">{{$value['tipo_ticket']}}</td>
                                                    <td style="font-size:2vh;">{{$value['title']}}</td>
                                                    <td style="font-size:2vh;">{{$value['sede']}}</td>
                                                    <td style="font-size:2vh;">{{$value['area']}}</td>
                                                    <td style="text-align:center;"><span class="{{$value['label']}}" style="font-size:13px;"><b>{{$value['prioridad']}}</b></span></td>
                                                    <td style="font-size:2vh;">{{$value['estado']}}</td>
                                                    <td style="font-size:2vh;">{{$value['updated_at']}}</td>
                                                    <td style="text-align:center;"><a href="#" class="btn btn-info" title="Editar" data-toggle="modal" data-target="#modal-tickets-upd" onclick="obtener_datos_ticket('{{$value['id']}}');"><i class="glyphicon glyphicon-search"></i></a></td>
                                                    <input type="hidden" value="{{$value['id']}}" id="id{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['tipo_ticket']}}" id="tipo_ticket{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['sede']}}" id="sede{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['prioridad']}}" id="prioridad{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['categoria']}}" id="categoria{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['asignado_a']}}" id="asignado_a{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['estado']}}" id="estado{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['asigned_id']}}" id="asigned_id{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['title']}}" id="title{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['description']}}" id="description{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['area']}}" id="area{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['evidencia']}}" id="evidencia{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['historial']}}" id="historial{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['name_user']}}" id="name_user{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['user_email']}}" id="user_email{{$value['id']}}">
                                                    <input type="hidden" value="{{$value['tel_user']}}" id="tel_user{{$value['id']}}">
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@include('Modals.ModalSolicitud')
@section('scripts')
    <script src="{{asset("assets/dist/js/dashboard.js")}}"></script>
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
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    </script>
    <script>
        function mostrar(id) {
            if (id === '1') {
                $("#titulo").show();
                document.getElementById("title").required = true;
            }else{
                $("#titulo").hide();
                document.getElementById("title").required = false;
            }
        }
    </script>

    <script>
        function Area() {
            var selectBox = document.getElementById("project_id");
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
    <script type="text/javascript">
        var Asunto = new Array();
        @if($Asuntos)
            @foreach($Asuntos as $valor)
                Asunto[{{$valor['id']}}] = '{{$valor['nombre']}}';
            @endforeach
        @endif
        var options = '';
        for(var i = 1; i < Asunto.length; i++)
        options += '<option value="'+Asunto[i]+'" />';
        document.getElementById('asuntos').innerHTML = options;
    </script>

    @endsection

