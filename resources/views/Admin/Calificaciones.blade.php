@extends("Template.layout")

@section('titulo')
Calificaciones
@endsection

@section('contenido')
<section class="content-header">
    <h1><i class="fa fa-bar-chart"></i>&nbsp;Calificaciones</h1>
    <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li>Admin</a></li>
        <li class="active">Calificaciones</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-5">
            <div class="box box-warning">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id='Gcalificaciones' style="height:  500px; width: -webkit-fill-available;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="box box-warning">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="calificaciones" class="display responsive hover" style="width: 100%;">
                                <thead style="background: linear-gradient(60deg,rgba(51,101,155,1),rgba(66,132,206,1));color: #ECF0F1;">
                                    <tr>
                                        <th style="text-align: center;">Nro.</th>
                                        <th style="text-align: center;">Ticket</th>
                                        <th style="text-align: center;">Calificación</th>
                                        <th style="text-align: center;">Usuario</th>
                                        <th style="text-align: center;">Fecha</th>
                                        <th style="text-align: center;">IP Client</th>
                                        <th style="text-align: center;">Agente Mesa de Ayuda</th>
                                        <th style="text-align: center;">Titulo Ticket</th>
                                        <th style="text-align: center;">Descripción Ticket</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($Calificaciones as $value)
                                    <tr>
                                        <td>{{$value['id']}}</td>
                                        <td>{{$value['ticket']}}</td>
                                        <td>{{$value['puntuacion']}}</td>
                                        <td>{{$value['user_name']}}</td>
                                        <td>{{$value['update_at']}}</td>
                                        <td>{{$value['ip_client']}}</td>
                                        <td>{{$value['agente']}}</td>
                                        <td>{{$value['titulo']}}</td>
                                        <td>{{$value['descripcion']}}</td>
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
@endsection
@section('scripts')
    <script src="{{asset("assets/dist/js/calificaciones.js")}}"></script>
    <script>
        Highcharts.chart('Gcalificaciones', {
            chart: {
                type: 'column',
            },
            title: {
                text: 'Calificacion Gestión Ticket'
            },

            colors:[
                    @if($Gestion)
                        @foreach($Gestion as $valor)
                            '{{$valor['color']}}' {{$valor['separador']}}
                        @endforeach
                    @endif
                    ],
            xAxis: {
                type: 'category',
                labels: {
                    style: {
                        fontSize: '10px'
                    }
                },
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Total por Calificacion'
                }
            },
            tooltip: {
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'Valoraciones',
                colorByPoint: true,
                data: [
                    ['Muy Insatisfecho', {{ $MuyInsatisfecho }}],
                    ['Insatisfecho', {{ $Insatisfecho }}],
                    ['Neutral', {{ $Neutral }}],
                    ['Satisfecho', {{ $Satisfecho }}],
                    ['Muy Satisfecho', {{ $MuySatisfecho }}]
                ]
            }]
        });
    </script>
@endsection

