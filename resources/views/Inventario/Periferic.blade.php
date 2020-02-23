@extends("Template.layout")

@section('titulo')
Periféricos
@endsection

@section('contenido')

<section class="content-header">
    <h1><i class="fa fa-keyboard-o"></i>&nbsp;Periféricos</h1>
    <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li>Periféricos</a></li>
        <li class="active">Dashboard</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="box box-success">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="info-box bg-blue">
                            <span class="info-box-icon"><i class="fa fa-archive animated"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text"><font style="font-size: 20px;color:white;">En Stock</font></span>
                                <span class="info-box-number"><font style="font-size: 36px;color:white;">{{ $Stock }}</font></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box bg-green">
                            <span class="info-box-icon"><i class="fa fa-check animated"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text"><font style="font-size: 20px;color:white;">Asignados</font></span>
                                <span class="info-box-number"><font style="font-size: 36px;color:white;">{{ $Asignados }}</font></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box bg-red">
                            <span class="info-box-icon"><i class="fa fa-upload faa-pulse animated"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text"><font style="font-size: 20px;color:white;">En Mantenimiento</font></span>
                                <span class="info-box-number"><font style="font-size: 36px;color:white;">{{ $Mantenimiento }}</font></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box bg-yellow">
                            <span class="info-box-icon"><i class="fa fa-download faa-pulse animated"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text"><font style="font-size: 20px;color:white;">Obsoletos</font></span>
                                <span class="info-box-number"><font style="font-size: 36px;color:white;">{{ $Obsoletos }}</font></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modal-perifericos"><i class="fa fa-plus-circle"></i>&nbsp;Ingresar Periférico</button>
                        <br>
                        <br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table id="preriferic" class="display responsive hover" style="width: 100%;">
                            <thead style="background: linear-gradient(60deg,rgba(51,101,155,1),rgba(66,132,206,1));color: #ECF0F1;font-size:12px;text-align: center;">
                                <tr>
                                    <th style="text-align: center;">NRO. ACTIVO</th>
                                    <th style="text-align: center;">TIPO PERIFERICO</th>
                                    <th style="text-align: center;">TIPO ADQUISICIÓN</th>
                                    <th style="text-align: center;">EMPRESA</th>
                                    <th style="text-align: center;">FECHA ADQUISICIÓN</th>
                                    <th style="text-align: center;">SERIAL</th>
                                    <th style="text-align: center;">MARCA</th>
                                    <th style="text-align: center;">PULGADAS</th>
                                    <th style="text-align: center;">ESTADO PERIFERICO</th>
                                    <th style="text-align: center;">EDITAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($Perifericos as $value)
                                    <tr>
                                        <td>{{$value['id']}}</td>
                                        <td>{{$value['tipoPeriferico']}}</td>
                                        <td>{{$value['tipoIngreso']}}</td>
                                        <td>{{$value['emp_renting']}}</td>
                                        <td>{{$value['fecha_ingreso']}}</td>
                                        <td style="text-align: center;">{{$value['serial']}}</td>
                                        <td>{{$value['marca']}}</td>
                                        <td>{{$value['tamano']}}</td>
                                        <td style="text-align: center;"><span class="{{$value['label']}}" style="font-size:13px;"><b>{{$value['estado']}}</b></span></td>
                                        <td style="text-align: center;"><a href="#" class="btn btn-warning" title="Editar" data-toggle="modal" data-target="#modal-cambios-periferico" onclick="obtener_datos_periferico('{{$value['id']}}');"><i class="fa fa-edit"></i></a></td>
                                        <input type="hidden" value="{{$value['id']}}" id="id{{$value['id']}}">
                                        <input type="hidden" value="{{$value['tipo_periferico']}}" id="tipo_periferico{{$value['id']}}">
                                        <input type="hidden" value="{{$value['tipo_ingreso']}}" id="tipo_ingreso{{$value['id']}}">
                                        <input type="hidden" value="{{$value['emp_renting']}}" id="emp_renting{{$value['id']}}">
                                        <input type="hidden" value="{{$value['fecha_ingreso']}}" id="fecha_ingreso{{$value['id']}}">
                                        <input type="hidden" value="{{$value['serial']}}" id="serial{{$value['id']}}">
                                        <input type="hidden" value="{{$value['marca']}}" id="marca{{$value['id']}}">
                                        <input type="hidden" value="{{$value['tamano']}}" id="tamano{{$value['id']}}">
                                        <input type="hidden" value="{{$value['estado_periferico']}}" id="estado_periferico{{$value['id']}}">
                                        <input type="hidden" value="{{$value['evidencia']}}" id="evidencia{{$value['id']}}">
                                        <input type="hidden" value="{{$value['historial']}}" id="historial{{$value['id']}}">
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
@include('Modals.ModalPerifericos')
@endsection
@section('scripts')
    <script src="{{asset("assets/dist/js/inventario.js")}}"></script>
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
            $('#fecha_adquision').datepicker({
                autoclose: true,
                language: 'es',
                todayBtn: true,
                format: 'dd-mm-yyyy',
                orientation: 'bottom auto',
                endDate: '+0d'
            });
            $('#mod_fecha_adquision').datepicker({
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
