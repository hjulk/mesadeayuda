@extends("Template.layout")

@section('titulo')
Tickets
@endsection

@section('contenido')

<section class="content-header">
    <h1><i class="fa fa-ticket"></i>&nbsp;Tickets</h1>
    <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li>Tickets</a></li>
        <li class="active">Dashboard</li>
    </ol>
</section>

<section class="content">

    <div class="row">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modal-tickets"><i class="fa fa-plus-circle"></i>&nbsp;Crear Ticket</button>
                        @if(Session::get('Rol') === 1)
                            <button class="btn btn-success" data-toggle="modal" data-target="#modal-reabrir-tickets">Reabrir Ticket</button>
                        @endif
                        <br>
                        <br>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table id="ticketsPrincipal" class="display responsive hover" style="width: 100%;">
                            <thead style="background: linear-gradient(60deg,rgba(51,101,155,1),rgba(66,132,206,1));color: #ECF0F1;">
                                <tr>
                                    <th style="text-align: center;">Nro</th>
                                    <th style="text-align: center;">Tipo</th>
                                    <th style="text-align: center;">Asunto</th>
                                    <th style="text-align: center;">Sede</th>
                                    <th style="text-align: center;">Area</th>
                                    <th style="text-align: center;">Prioridad</th>
                                    <th style="text-align: center;">Estado</th>
                                    <th style="text-align: center;">Fecha Creación</th>
                                    <th style="text-align: center;">Creador</th>
                                    <th style="text-align: center;">Asignado A</th>
                                    <th style="text-align: center;">Fecha Actualización</th>
                                    <th style="text-align: center;">Editar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($Tickets as $value)
                                <tr>
                                    <td style="font-size:2vh;">{{$value['id']}}</td>
                                    <td style="text-align:center;font-size:2vh;">{{$value['tipo_ticket']}}</td>
                                    <td style="font-size:2vh;">{{$value['title']}}</td>
                                    <td style="font-size:2vh;">{{$value['sede']}}</td>
                                    <td style="font-size:2vh;">{{$value['area']}}</td>
                                    <td style="text-align:center;font-size:2vh;"><span class="{{$value['label']}}" style="font-size:13px;"><b>{{$value['prioridad']}}</b></span></td>
                                    <td style="font-size:2vh;">{{$value['estado']}}</td>
                                    <td style="font-size:2vh;">{{$value['created_at']}}</td>
                                    <td style="font-size:2vh;">{{$value['asignado_por']}}</td>
                                    <td style="font-size:2vh;">{{$value['asignado_a']}}</td>
                                    <td style="font-size:2vh;">{{$value['updated_at']}}</td>
                                    <td style="font-size:2vh;"><a href="#" class="btn btn-warning" title="Editar" data-toggle="modal" data-target="#modal-tickets-upd" onclick="obtener_datos_ticket('{{$value['id']}}');"><i class="glyphicon glyphicon-edit"></i></a></td>
                                    <input type="hidden" value="{{$value['id']}}" id="id{{$value['id']}}">
                                    <input type="hidden" value="{{$value['kind_id']}}" id="kind_id{{$value['id']}}">
                                    <input type="hidden" value="{{$value['category_id']}}" id="category_id{{$value['id']}}">
                                    <input type="hidden" value="{{$value['project_id']}}" id="project_id{{$value['id']}}">
                                    <input type="hidden" value="{{$value['priority_id']}}" id="priority_id{{$value['id']}}">
                                    <input type="hidden" value="{{$value['status_id']}}" id="status_id{{$value['id']}}">
                                    <input type="hidden" value="{{$value['user_id']}}" id="user_id{{$value['id']}}">
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

</section>
@include('Modals.ModalTickets')
@include('Modals.ModalUpdTicket')
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
        $('#form-ticket').submit(function() {
            var fileSize = $('#evidencia')[0].files[0].size;
            var sizekiloBytes = parseInt(fileSize / 1024);
            if (sizekiloBytes >  $('#evidencia').attr('size')) {
                alert('El tamaño supera el limite permitido de 5mb');
                return false;
            }
        });
        $('#form-ticket-upd').submit(function() {
            var fileSize = $('#evidencia_upd')[0].files[0].size;
            var sizekiloBytes = parseInt(fileSize / 1024);
            if (sizekiloBytes >  $('#evidencia_upd').attr('size')) {
                alert('El tamaño supera el limite permitido de 5mb');
                return false;
            }
        });

    </script>

    {{-- <script>
        $("#upd").submit(function (event) {

            var opcion = confirm("¿Esta de acuerdo con actualizar el ticket con la información suministrada?");
            if (opcion === true) {
                $('#update_data').attr("disabled", true);
                var tipo = 'post';
                $.ajax({
                    type: "post",
                    url: "{{route('actualizarTicket')}}",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (datos) {

                        location.reload();
                    }
                });
            }
            event.preventDefault();
        });
    </script> --}}

@endsection
