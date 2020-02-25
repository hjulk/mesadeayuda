@extends("template.layout")

@section('titulo')
Sedes
@endsection

@section('contenido')
<section class="content-header">
        <h1><i class="fa fa-map"></i> Sedes</h1>
        <ol class="breadcrumb">
          <li><a href="dashboard"><i class="fa fa-dashboard"></i> Inicio</a></li>
          <li>Admin</a></li>
          <li class="active">Sedes</li>
        </ol>
      </section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="box-header">
                                <h3 class="box-title"><strong>Sedes</strong></h3>
                            </div>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modal-sedes">Crear Sede</button>
                            <br>
                            <br>
                            <table id="sedes" class="display responsive hover" style="width: 100%;">
                                    <thead style="background: linear-gradient(60deg,rgba(51,101,155,1),rgba(66,132,206,1));color: #ECF0F1;">
                                        <tr>
                                            <th style="text-align: center;font-size:1.9vh;">Id</th>
                                            <th style="text-align: center;font-size:1.9vh;">Nombre</th>
                                            <th style="text-align: center;font-size:1.9vh;">Descripción</th>
                                            <th style="text-align: center;font-size:1.9vh;">Activo</th>
                                            <th style="text-align: center;font-size:1.9vh;">Editar</th>
                                        </tr>
                                </thead>
                                <tbody>
                                    @foreach($Sedes as $sede)
                                        <tr>
                                            <td style="font-size:1.9vh;">{{$sede['id']}}</td>
                                            <td style="font-size:1.9vh;">{{$sede['name']}}</td>
                                            <td style="font-size:1.9vh;">{{$sede['description']}}</td>
                                            <td style="font-size:1.9vh;">{{$sede['name_activo']}}</td>
                                            <td style="text-align: center;text-align: center;"><a href="#" class="btn btn-warning" title="Editar" onclick="obtener_datos_sede('{{$sede['id']}}');" data-toggle="modal" data-target="#modal-sedes-upd"><i class="glyphicon glyphicon-edit"></i></a></td>
                                            <input type="hidden" value="{{$sede['id']}}" id="id{{$sede['id']}}">
                                            <input type="hidden" value="{{$sede['name']}}" id="name{{$sede['id']}}">
                                            <input type="hidden" value="{{$sede['description']}}" id="description{{$sede['id']}}">
                                            <input type="hidden" value="{{$sede['activo']}}" id="activo{{$sede['id']}}">
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="box-header">
                                <h3 class="box-title"><strong>Áreas</strong></h3>
                            </div>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modal-areas">Crear Área</button>
                            <br>
                            <br>
                            <table id="areas" class="display responsive hover" style="width: 100%;">
                                <thead style="background: linear-gradient(60deg,rgba(51,101,155,1),rgba(66,132,206,1));color: #ECF0F1;">
                                    <tr>
                                        <th style="text-align: center;font-size:1.9vh;">Id</th>
                                        <th style="text-align: center;font-size:1.9vh;">Nombre</th>
                                        <th style="text-align: center;font-size:1.9vh;">Sede</th>
                                        <th style="text-align: center;font-size:1.9vh;">Activo</th>
                                        <th style="text-align: center;font-size:1.9vh;">Editar</th>
                                    </tr>
                            </thead>
                            <tbody>
                                @foreach($Areas as $area)
                                    <tr>
                                        <td style="font-size:1.9vh;">{{$area['id']}}</td>
                                        <td style="font-size:1.9vh;">{{$area['nombre']}}</td>
                                        <td style="font-size:1.9vh;">{{$area['sede']}}</td>
                                        <td style="font-size:1.9vh;">{{$area['name_activo']}}</td>
                                        <td style="text-align: center;text-align: center;"><a href="#" class="btn btn-warning" title="Editar" onclick="obtener_datos_area('{{$area['id']}}');" data-toggle="modal" data-target="#modal-areas-upd"><i class="glyphicon glyphicon-edit"></i></a></td>
                                        <input type="hidden" value="{{$area['id']}}" id="id{{$area['id']}}">
                                        <input type="hidden" value="{{$area['nombre']}}" id="nombre{{$area['id']}}">
                                        <input type="hidden" value="{{$area['project_id']}}" id="project_id{{$area['id']}}">
                                        <input type="hidden" value="{{$area['activo']}}" id="activo_area{{$area['id']}}">
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
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title"><strong></strong></h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-header">
                                <h3 class="box-title"><strong>Ubicación</strong></h3>
                            </div>
                            <iframe src="https://www.google.com/maps/d/embed?mid=1HMvwR7VKuf3WaAMajLb2UX1klC1X6hVt" style="border:0;width: -webkit-fill-available;height: -webkit-fill-available;" allowfullscreen=""></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</section>

@include('Modals.ModalSedes')

@endsection

@section('scripts')

    <script src="{{asset("assets/dist/js/sedes.js")}}"></script>
    <script>
        @if (session("mensaje"))
            toastr.success("{{ session("mensaje") }}");
        @endif

        @if(count($errors) > 0)
            @foreach($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>

@endsection
