<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>HelpDesk | Crear Solicitud</title>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta http-equiv="cache-control" content="max-age=0" />
        <meta http-equiv="cache-control" content="no-cache" />
        <meta http-equiv="cache-control" content="no-store" />
        <meta http-equiv="cache-control" content="must-revalidate" />
        <meta http-equiv="expires" content="0" />
        <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
        <meta http-equiv="pragma" content="no-cache" />
        <link type="image/x-icon" rel="icon" href="{{asset("assets/dist/img/helpdesk.png")}}">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="{{asset("assets/Solicitud/bootstrap.min.css")}}" id="bootstrap-css">
        <link rel="stylesheet" href="{{asset("assets/Solicitud/solicitud.css")}}">
        <link rel="stylesheet" href="{{asset("assets/CodeSeven/build/toastr.min.css")}}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link href="https://fonts.googleapis.com/css?family=Poppins:300,400&display=swap" rel="stylesheet">
    </head>

<div class="container contact">
	<div class="row">
		<div class="col-md-4">
			<div class="contact-info">
                {{--  <img src="{{asset("assets/Solicitud/support.png")}}" alt="image"/>  --}}
                <h2 style="color:white;">Crear Ticket</h2>
                <h4 style="color:white;">Tenga en cuenta:</h4>
                <br>

                <p style="color:white;font-size:2.2vh;text-align:justify;">
                    <b>Incidente: </b>Es cualquier evento que interrumpa el funcionamiento normal de un servicio afectando ya sea a uno, a un grupo o a todos los usuarios de un servicio, un incidente se puede tomar como la reducción en la calidad de un servicio IT.<br>
                </p>
                <p style="color:white;font-size:2.2vh;text-align:justify;">
                    <b>Requerimiento: </b>Se define como una solicitud formal por parte de un usuario para que algo sea provisto, como por ejemplo Instalaciones, movimientos, adiciones o cambios en los elementos o servicios provistos por la Dirección de TIC.
                </p>
			</div>
		</div>
		<div class="col-md-8">
			<div class="contact-info">
                {{--  <img src="{{asset("assets/Solicitud/support.png")}}" alt="image"/>  --}}
                <h1 style="color:black;">Se creo con exito el ticket {{ $Ticket }}</h1>
                <br>
                <p style="color:black;font-size:4vh;text-align:justify;">Por favor revise la información del ticket que fue enviada al correo registrado para realizar su respectivo seguimiento.</p>
                <p style="color:black;font-size:4vh;text-align:justify;">Mesa de Ayuda - Dirección TICS</p>
			</div>
		</div>
	</div>
</div>
    <script src="{{asset("assets/Solicitud/bootstrap.min.js")}}"></script>
    <script src="{{asset("assets/Solicitud/jquery.min.js")}}"></script>

</html>
