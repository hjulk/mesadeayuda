@extends("Template.layout")

@section('titulo')
Dahsboard
@endsection

@section('contenido')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><strong>Dashboard Tickets</strong></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box bg-aqua">
                                <span class="info-box-icon"><i class="fa fa-spinner fa-pulse"></i></span>
                                <div class="info-box-content">
                                    <a href="tickets">
                                        <span class="info-box-text"><font style="font-size: 20px;color:white;">En Desarrollo</font></span>
                                        <span class="info-box-number"><font style="font-size: 36px;color:white;">{{ $EnDesarrollo }}</font></span>
                                    </a>
                                </div>
                            </div>
                            <div class="info-box bg-red">
                                <span class="info-box-icon"><i class="fa fa-exclamation-triangle faa-ring animated fa-fw"></i></span>
                                <div class="info-box-content">
                                    <a href="tickets">
                                        <span class="info-box-text"><font style="font-size: 20px;color:white;">Pendientes</font></span>
                                        <span class="info-box-number"><font style="font-size: 36px;color:white;">{{ $Pendientes }}</font></span>
                                    </a>
                                </div>
                            </div>
                            <div class="info-box bg-green">
                                <span class="info-box-icon"><i class="fa fa-trophy faa-burst animated"></i></span>
                                <div class="info-box-content">
                                    <a href="tickets">
                                        <span class="info-box-text"><font style="font-size: 20px;color:white;">Terminados</font></span>
                                        <span class="info-box-number"><font style="font-size: 36px;color:white;">{{ $Terminados }}</font></span>
                                    </a>
                                </div>
                            </div>
                            <div class="info-box bg-yellow">
                                <span class="info-box-icon"><i class="fa fa-ban faa-passing animated"></i></span>
                                <div class="info-box-content">
                                    <a href="tickets">
                                        <span class="info-box-text"><font style="font-size: 20px;color:white;">Cancelados</font></span>
                                        <span class="info-box-number"><font style="font-size: 36px;color:white;">{{ $Cancelados }}</font></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class='panel panel-default'>
                                        <div class='panel-body'>
                                            <div id='barras' style="height: -webkit-fill-available; width: -webkit-fill-available;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class='panel panel-default'>
                                        <div class='panel-body'>
                                            <div id='graficas' style="height: -webkit-fill-available; width: -webkit-fill-available;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection

    @section('scripts')
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/es_ES/sdk.js#xfbml=1&version=v3.3"></script>

    <script asyn src="https://livegap.com/charts/js/webfont.js"></script>
    <script src="https://livegap.com/charts/js/Chart.min.js"></script>
    <script src="{{asset("assets/dist/js/dashboard.js")}}"></script>
    <script>

            Highcharts.chart('barras', {
                chart: {
                    type: 'column',
                },
                title: {
                    text: 'Tickets Gestionados por Mes'
                },

                colors:[
                        '#7cb5ec',
                        '#f7a35c'
                        ],
                xAxis: {
                    categories: [
                        @if($MesGraficas)
                        @foreach($MesGraficas as $valor)
                            '{{$valor['nombre']}}' {{$valor['separador']}}
                        @endforeach
                        @endif
                    ],
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
                        text: 'Numero de Tickets'
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
                    name: 'Incidentes',
                    data: [
                        @if($MesGraficas)
                        @foreach($MesGraficas as $valor)
                            {{$valor['incidentes']}} {{$valor['separador']}}
                        @endforeach
                        @endif
                ]
                }, {
                    name: 'Requerimientos',
                    data: [
                        @if($MesGraficas)
                        @foreach($MesGraficas as $valor)
                            {{$valor['requerimientos']}} {{$valor['separador']}}
                        @endforeach
                        @endif
                ]
                }]
            });

            Highcharts.chart('graficas', {
                chart: {
                    type: 'pie',
                    options3d: {
                        enabled: true,
                        alpha: 45,
                        beta: 0
                    }
                },
                colors:[

                    '#FE9129',
                    '#8B103E',
                    '#64D9A8',
                    '#FF333F'
                ],
                title: {
                    text: 'Tickets Gestionados por Area'
                },

                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        depth: 35,
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}'
                        }
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Tickets',
                    data: [

                        ['Infraestructura', {{ $Infraestructura }}],
                        ['Aplicaciones', {{ $Aplicaciones }}],
                        ['Redes', {{ $Redes }}],
                        ['Soporte', {{ $Soporte }}]
                    ]
                }]
            });
    </script>

    @endsection
