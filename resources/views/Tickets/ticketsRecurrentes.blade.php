@extends("Template.layout")
@section('titulo')
Tickets Recurrentes
@endsection
@section('contenido')
<section class="content-header">
    <h1><i class="fa fa-ticket"></i>Tickets Recurrentes</h1>
    <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li>Admin</a></li>
        <li class="active">Tickets Recurrentes</li>
    </ol>
</section>
<section class="content">
    <div class="box box-warning">
        <div class="box-body">
            <div class="row">
                <div class="col-md-3">
                    {!! Form::open(['url' => 'crearTicketRecurrente', 'method' => 'post', 'enctype' => 'multipart/form-data','role' => 'form']) !!}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="inputEmail3" class="col-sm-12 control-label">Asunto Ticket</label>
                                    {!! Form::textarea('asunto',null,['class'=>'form-control','id'=>'asunto','placeholder'=>'Ingrese el asunto','rows'=>'2','required']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-9">
                                    <label for="inputEmail3" class="col-sm-12 control-label">Categoria</label>
                                    {!! Form::select('categoria',$Categoria,null,['class'=>'form-control','id'=>'categoria','required']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="inputEmail3" class="col-sm-12 control-label">Prioridad</label>
                                    {!! Form::select('prioridad',$Prioridad,null,['class'=>'form-control','id'=>'prioridad','required']) !!}
                                </div>
                                <div class="col-md-6">
                                    <label for="inputEmail3" class="col-sm-12 control-label">Tipo Usuario</label>
                                    {!! Form::select('tipo_usuario',$Tipo,null,['class'=>'form-control','id'=>'tipo_usuario','required']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success pull-right">Crear Asunto</button>
                        </div>
                    {!!  Form::close() !!}
                </div>
                <div class="col-md-9">
                    <table id="recurrentes" class="display responsive hover" style="width: 100%;">
                        <thead style="background: linear-gradient(60deg,rgba(51,101,155,1),rgba(66,132,206,1));color: #ECF0F1;">
                            <tr>
                                <th style="text-align: center;">Asunto</th>
                                <th style="text-align: center;">Categoria</th>
                                <th style="text-align: center;">Prioridad</th>
                                <th style="text-align: center;">Activo</th>
                                <th style="text-align: center;">Tipo Usuario</th>
                                <th style="text-align: center;">Editar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($TicketsRecurrentes as $value)
                            <tr>
                                <td style="font-size:13px;">{{$value['nombre']}}</td>
                                <td style="font-size:13px;text-align:center;">{{$value['categoria']}}</td>
                                <td style="font-size:13px;text-align:center;"><span class="{{$value['label']}}"><b>{{$value['prioridad']}}</b></span></td>
                                <td style="font-size:13px;text-align:center;">{{$value['activo']}}</td>
                                <td style="font-size:13px;text-align:center;">{{$value['usuario']}}</td>
                                <td style="font-size:13px;text-align: center;"><a href="#" class="btn btn-warning" title="Editar" onclick="obtener_datos_recurrente('{{$value['id']}}');" data-toggle="modal" data-target="#modal-recurrente"><i class="glyphicon glyphicon-edit"></i></a></td>
                                <input type="hidden" value="{{$value['id']}}" id="id{{$value['id']}}">
                                <input type="hidden" value="{{$value['nombre']}}" id="nombre{{$value['id']}}">
                                <input type="hidden" value="{{$value['category_id']}}" id="category_id{{$value['id']}}">
                                <input type="hidden" value="{{$value['priority_id']}}" id="priority_id{{$value['id']}}">
                                <input type="hidden" value="{{$value['id_activo']}}" id="id_activo{{$value['id']}}">
                                <input type="hidden" value="{{$value['tipo']}}" id="tipo{{$value['id']}}">
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@include('Modals.ModalRecurrente')

@endsection

@section('scripts')

    <script src="{{asset("assets/dist/js/tickets.js")}}"></script>
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
