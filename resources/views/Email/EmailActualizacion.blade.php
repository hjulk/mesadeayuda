<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<link rel="stylesheet" href="{{asset("assets/bower_components/bootstrap/dist/css/bootstrap.min.css")}}">
<link rel="stylesheet" href="{{asset("assets/bower_components/font-awesome/css/font-awesome.min.css")}}">
<link rel="stylesheet" href="{{asset("assets/bower_components/Ionicons/css/ionicons.min.css")}}">
<link rel="stylesheet" href="{{asset("assets/dist/css/AdminLTE.min.css")}}">
<link rel="stylesheet" href="{{asset("assets/dist/css/skins/_all-skins.min.css")}}">
<link rel="stylesheet" href="{{asset("assets/CodeSeven/build/toastr.min.css")}}">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<div class="row">
    <div class="col-md-12">
        <br>
        <table style='max-width: 600px; padding: 10px; margin:0 auto; border-collapse: collapse;font-family:Verdana;font-size:13px;' class='tg' cellpadding='5'>

            <colgroup>
                <col style='width: 200px'>
                <col style='width: 500px'>
            </colgroup>
            <tr>
                <th colspan='2'>Actualización del Ticket {{$Ticket}}</th>
            </tr>
            <tr height='20px'></tr>
            <tr>
                <td><b>No. Ticket:</b></td>
                <td>{{$Ticket}}</td>
            </tr>

            <tr>
                <td><b>Categoria:</b></td>
                <td>{{$Categoria}}</td>
            </tr>

            <tr>
                <td><b>Prioridad:</b></td>
                <td>{{$Prioridad}}</td>
            </tr>

            <tr>
                <td><b>Asunto:</b></td>
                <td>{{$Asunto}}</td>
            </tr>

            <tr>
                <td><b>Mensaje de actualización:</b></td>
                <td style="text-align: justify;">{{$Mensaje}}</td>
            </tr>
            <tr>
                <td><b>Nombre de quien solicita:</b></td>
                <td>{{$NombreReportante}}</td>
            </tr>

            <tr>
                <td><b>Telefono:</b></td>
                <td>{{$Telefono}}</td>
            </tr>

            <tr>
                <td><b>Correo:</b></td>
                <td>{{$Correo}}</td>
            </tr>

            <tr>
                <td><b>Asignado a:</b></td>
                <td>{{$AsignadoA}}</td>
            </tr>

            <tr>
                <td><b>Estado Ticket:</b></td>
                <td>{{$Estado}}</td>
            </tr>

            <tr>
                <td><b>Fecha Actualización:</b></td>
                <td>{{$Fecha}}</td>
            </tr>
        </table>
        <br>
        <br>
        @if($Calificacion === 1)
        <table style='max-width: 600px; padding: 10px; margin:0 auto; border-collapse: collapse;font-family:Verdana;font-size:13px;' class='tg' cellpadding='5'>
            <colgroup>
                <col style='width: 50px'>
                <col style='width: 50px'>

            </colgroup>
            <tbody>
                <tr>
                    <td>{{$Calificacion5}}</td>
                    <td>{{$Calificacion4}}</td>
                    <td>{{$Calificacion3}}</td>
                    <td>{{$Calificacion2}}</td>
                    <td>{{$Calificacion1}}</td>
                </tr>
            </tbody>
        </table>
        @endif
        <br>
        <table style='max-width: 600px; padding: 10px; margin:0 auto; border-collapse: collapse;font-family:Verdana;font-size:13px;' class='tg' cellpadding='5'>
            <tbody>
                <tr>
                    <td colspan="2" style="vertical-align:top;width:60px;">
                        {{-- <img src="http://crcscbmesadeayuda.cruzrojabogota.org.co/assets/dist/img/firma.jpg" usemap="#m_3620493203722104036_SignatureSanitizer_m_-8559001894841881195_SignatureSanitizer_image-map" class="CToWUd"> --}}
                        <img src="http://crcscbmesadeayuda.cruzrojabogota.org.co/images/firma.jpg" usemap="#m_3620493203722104036_SignatureSanitizer_m_-8559001894841881195_SignatureSanitizer_image-map" class="CToWUd">
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

</div>


