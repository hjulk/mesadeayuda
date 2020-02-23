<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<link rel="stylesheet" href="{{asset("assets/DataTables/bower_components/bootstrap/dist/css/bootstrap.min.css")}}">
<link rel="stylesheet" href="{{asset("assets/DataTables/bower_components/font-awesome/css/font-awesome.min.css")}}">
<link rel="stylesheet" href="{{asset("assets/DataTables/bower_components/Ionicons/css/ionicons.min.css")}}">
<link rel="stylesheet" href="{{asset("assets/DataTables/dist/css/AdminLTE.min.css")}}">
<link rel="stylesheet" href="{{asset("assets/DataTables/dist/css/skins/_all-skins.min.css")}}">
<link rel="stylesheet" href="{{asset("assets/DataTables/CodeSeven/build/toastr.min.css")}}">
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
    @if($Correo === 1)
        <th colspan='2'><b>Asignación de Turno</b></th>
    @endif
    @if($Correo === 2)
        <th colspan='2'><b>Actualización de Turno</b></th>
    @endif
</tr>
<tr height='20px'></tr>
<tr>
    <td><b>Nombre Agente:</b></td>
    <td>{{$NombreAgente}}</td>
</tr>

<tr>
    <td><b>Fecha Inicio Turno:</b></td>
    <td>{{$FechaInicio}}</td>
</tr>

<tr>
    <td><b>Fecha Fin Turno:</b></td>
    <td>{{$FechaFin}}</td>
</tr>

<tr>
    <td><b>Sede:</b></td>
    <td>{{$NombreSede}}</td>
</tr>

<tr>
    <td><b>Horario:</b></td>
    <td>{{ $NombreHorario }}</td>
</tr>
<tr>
    <td><b>Tipo Turno:</b></td>
    <td>{{$NombreDisponible}}</td>
</tr>
<tr>
    <td><b>Fecha Asignación Turno:</b></td>
    <td>{{$FechaCreacion}}</td>
</tr>

</table>
<br>
<br>

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
