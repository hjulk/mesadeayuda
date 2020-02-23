@extends("Template.layout")

@section('titulo')
Dahsboard
@endsection

@section('contenido')
@include('Modals.ModalGraficas')
<section class="content-header">
    <h1><i class="fa fa-home"></i>&nbsp;Mesa de Ayuda</h1>
    <ol class="breadcrumb">
        <li><a href="dashboard"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Mesa de Ayuda</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="info-box bg-blue">
                            <span class="info-box-icon"><i class="fa fa-spinner fa-pulse"></i></span>
                            <div class="info-box-content">
                                <a data-toggle="modal" href="#modal-desarrollo">
                                    <span class="info-box-text"><font style="font-size: 20px;color:white;">En Desarrollo</font></span>
                                    <span class="info-box-number"><font style="font-size: 36px;color:white;">{{ $EnDesarrollo }}</font></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box bg-red">
                            <span class="info-box-icon"><i class="fa fa-exclamation-triangle faa-ring animated fa-fw"></i></span>
                            <div class="info-box-content">
                                <a data-toggle="modal" href="#modal-pendientes">
                                    <span class="info-box-text"><font style="font-size: 20px;color:white;">Pendientes</font></span>
                                    <span class="info-box-number"><font style="font-size: 36px;color:white;">{{ $Pendientes }}</font></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box bg-green">
                            <span class="info-box-icon"><i class="fa fa-trophy faa-burst animated"></i></span>
                            <div class="info-box-content">
                                <a data-toggle="modal" href="#modal-terminados">
                                    <span class="info-box-text"><font style="font-size: 20px;color:white;">Terminados</font></span>
                                    <span class="info-box-number"><font style="font-size: 36px;color:white;">{{ $Terminados }}</font></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box bg-yellow">
                            <span class="info-box-icon"><i class="fa fa-ban faa-passing animated"></i></span>
                            <div class="info-box-content">
                                <a data-toggle="modal" href="#modal-cancelados">
                                    <span class="info-box-text"><font style="font-size: 20px;color:white;">Cancelados</font></span>
                                    <span class="info-box-number"><font style="font-size: 36px;color:white;">{{ $Cancelados }}</font></span>
                                </a>
                            </div>
                        </div>
                    </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class='panel panel-default'>
                                <div class='panel-body'>
                                    <div id='graficas' style="height: -webkit-fill-available; width: -webkit-fill-available;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class='panel panel-default'>
                                <div class='panel-body'>
                                    <div id='barras' style="height: -webkit-fill-available; width: -webkit-fill-available;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class='panel panel-default'>
                                <div class='panel-body'>
                                    <div id='gestionTics' style="height:  500px; width: -webkit-fill-available;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class='panel panel-default'>
                                <div class='panel-body'>
                                    <div id='gestionSedes' style="height:  500px; width: -webkit-fill-available;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class='panel panel-default'>
                                <div class='panel-body'>
                                    <div id='porcentaje' style="height: -webkit-fill-available; width: -webkit-fill-available;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class='panel panel-default'>
                                <div class='panel-body'>
                                    <div id='calificacion' style="height: -webkit-fill-available; width: -webkit-fill-available;"></div>
                                </div>
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

                    '#8B103E',
                    '#FE9129',
                    '#64D9A8',
                    '#33B2E3'
                ],
                title: {
                    text: 'Tickets Gestionados por Area'
                },

                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        depth: 45,
                        innerSize: 100,
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

                        ['Aplicaciones', {{ $Aplicaciones }}],
                        ['Infraestructura', {{ $Infraestructura }}],
                        ['Redes', {{ $Redes }}],
                        ['Mesa de Ayuda', {{ $Soporte }}]
                    ]
                }]
            });
    </script>

    <script>

            Highcharts.chart('terminados', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Tickets Terminados por Usuario'
            },
            colors:[
                '#00a65a'
                ],
            xAxis: {
                categories: [
                    @if($Gestion)
                    @foreach($Gestion as $valor)
                        '{{$valor['nombre']}}' {{$valor['separador']}}
                    @endforeach
                    @endif
                    ]


            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Nro de Tickets'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y} Tickets</b></td></tr>',
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
                name: 'Terminados',
                data: [
                    @if($Gestion)
                    @foreach($Gestion as $valor)
                        {{$valor['terminados']}} {{$valor['separador']}}
                    @endforeach
                    @endif
                ]

            }]
        });
    Highcharts.chart('desarrollo', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Tickets En Desarrollo por Usuario'
        },
        colors:[
            '#00c0ef'
            ],
        xAxis: {
            categories: [
                    @if($Gestion)
                    @foreach($Gestion as $valor)
                        '{{$valor['nombre']}}' {{$valor['separador']}}
                    @endforeach
                    @endif
                    ]

        },
        yAxis: {
            min: 0,
            title: {
                text: 'Nro de Tickets'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y} Tickets</b></td></tr>',
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
            name: 'En Desarrollo',
            data: [
                @if($Gestion)
                @foreach($Gestion as $valor)
                    {{$valor['desarrollo']}} {{$valor['separador']}}
                @endforeach
                @endif
            ]

        }]
    });
    Highcharts.chart('pendientes', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Tickets Pendientes por Usuario'
        },
        colors:[
            '#f56954'
            ],
        xAxis: {
            categories: [
                    @if($Gestion)
                    @foreach($Gestion as $valor)
                        '{{$valor['nombre']}}' {{$valor['separador']}}
                    @endforeach
                    @endif
                    ]

        },
        yAxis: {
            min: 0,
            title: {
                text: 'Nro de Tickets'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y} Tickets</b></td></tr>',
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
            name: 'Pendientes',
            data: [
                    @if($Gestion)
                    @foreach($Gestion as $valor)
                        {{$valor['pendientes']}} {{$valor['separador']}}
                    @endforeach
                    @endif
                ]

        }]
    });
    Highcharts.chart('cancelados', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Tickets Cancelados por Usuario'
        },
        colors:[
            '#f39c12'
            ],
        xAxis: {
            categories: [
                @if($Gestion)
                @foreach($Gestion as $valor)
                    '{{$valor['nombre']}}' {{$valor['separador']}}
                @endforeach
                @endif
                ]

        },
        yAxis: {
            min: 0,
            title: {
                text: 'Nro de Tickets'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y} Tickets</b></td></tr>',
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
            name: 'Cancelados',
            data: [
                    @if($Gestion)
                    @foreach($Gestion as $valor)
                        {{$valor['cancelados']}} {{$valor['separador']}}
                    @endforeach
                    @endif
                ]

        }]
    });

    </script>
    <script>
        Highcharts.chart('gestionTics', {
            chart: {
                type: 'bar',
            },
            title: {
                text: 'Tickets Actuales'
            },

            colors:[
                    '#7cb5ec',
                    '#f7a35c'
                    {{--  ,
                    'green'  --}}
                    ],
            xAxis: {
                categories: [
                    @if($Gestion)
                    @foreach($Gestion as $valor)
                    '<span style=\"fill: {{$valor['color']}};\">'+'{{$valor['nombre']}}'+'</span>' {{$valor['separador']}}
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
                name: 'En Desarrollo',
                data: [
                    @if($Gestion)
                    @foreach($Gestion as $valor)
                        {{$valor['desarrollo']}} {{$valor['separador']}}
                    @endforeach
                    @endif
            ]
            }, {
                name: 'Pendientes',
                data: [
                    @if($Gestion)
                    @foreach($Gestion as $valor)
                        {{$valor['pendientes']}} {{$valor['separador']}}
                    @endforeach
                    @endif
            ]
            }
            {{--  ,{
                name: 'Terminados',
                data: [
                    @if($Gestion)
                    @foreach($Gestion as $valor)
                        {{$valor['terminados']}} {{$valor['separador']}}
                    @endforeach
                    @endif
            ]
            }  --}}
            ]
        });
        Highcharts.chart('gestionSedes', {
            chart: {
                type: 'bar',
            },
            title: {
                text: 'Tickets Gestionados por Sede'
            },

            colors:[
                    '#7cb5ec',
                    '#f7a35c'
                    ],
            xAxis: {
                categories: [
                    @if($GestionS)
                    @foreach($GestionS as $valor)
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
                    text: 'Numero de Tickets Gestionados'
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
                    @if($GestionS)
                    @foreach($GestionS as $valor)
                        {{$valor['incidentes']}} {{$valor['separador']}}
                    @endforeach
                    @endif
            ]
            }, {
                name: 'Requerimientos',
                data: [
                    @if($GestionS)
                    @foreach($GestionS as $valor)
                        {{$valor['requerimientos']}} {{$valor['separador']}}
                    @endforeach
                    @endif
            ]
            }]
        });
    </script>
    <script>
        Highcharts.chart('calificacion', {
            chart: {
                type: 'column',
            },
            title: {
                text: 'Calificacion Gestión Ticket'
            },

            colors:[
                    @if($GestionC)
                        @foreach($GestionC as $valor)
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
        Highcharts.chart('porcentaje', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            colors:[
                @if($GestionC)
                    @foreach($GestionC as $valor)
                        '{{$valor['color']}}' {{$valor['separador']}}
                    @endforeach
                    @endif
            ],
            title: {
                text: 'Porcentaje Calificación'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{
                name: 'Porcentaje',
                colorByPoint: true,
                data: [
                    ['Muy Insatisfecho', {{ $PMuyInsatisfecho }}],
                    ['Insatisfecho', {{ $PInsatisfecho }}],
                    ['Neutral', {{ $PNeutral }}],
                    ['Satisfecho', {{ $PSatisfecho }}],
                    ['Muy Satisfecho', {{ $PMuySatisfecho }}]
                ]
            }]
        });
    </script>

    @endsection

