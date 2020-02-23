@extends("Template.layout")

@section('titulo')
Tickets
@endsection

@section('contenido')

<section class="content-header">
        <h1>Tickets</h1>
        <ol class="breadcrumb">
          <li><a href="dashboard"><i class="fa fa-dashboard"></i> Inicio</a></li>
          <li>Tickets</a></li>
          <li class="active">Dashboard</li>
        </ol>
      </section>

<section class="content">

        <div class="row">
                <div class="col-lg-12">
                        <div class="box box-success">
                                <div class="box-body">

                                    <button class="btn btn-primary" data-toggle="modal" data-target="#modal-sedes">Crear Ticket</button>
                                        <br>
                                        <br>
                                        <table id="tickets" class="display responsive hover" style="width: 100%;">
                                            <thead style="background-color:rgba(52, 73, 94, 0.94);color: #ECF0F1;">
                                                    <tr>
                                                        <th>Id</th>
                                                        <th>Nombre</th>
                                                        <th>Dirección</th>
                                                        <th>Zona</th>
                                                        <th>Activa</th>
                                                        <th>Editar</th>
                                                    </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                            </div>

                </div>

            </div>

</section>
    <script src="{{asset("assets/dist/js/tickets.js")}}"></script>
@endsection
