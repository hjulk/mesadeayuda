<!DOCTYPE html>
<html>
    <head>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-158693845-1"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-158693845-1');
        </script>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>HelpDesk | @yield('titulo','Dashboard')</title>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link type="image/x-icon" rel="icon" href="{{asset("assets/dist/img/logo.png")}}">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="{{asset("assets/bower_components/bootstrap/dist/css/bootstrap.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/bower_components/font-awesome/css/font-awesome.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/bower_components/Ionicons/css/ionicons.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/bower_components/bootstrap-daterangepicker/daterangepicker.css")}}">
        <link rel="stylesheet" href="{{asset("assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/bower_components/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/dist/css/AdminLTE.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/dist/css/skins/_all-skins.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/CodeSeven/build/toastr.min.css")}}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link rel="stylesheet" href="{{asset("assets/DataTables/dist/css/jquery.dataTables.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/DataTables/dist/css/dataTables.bootstrap4.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/DataTables/Responsive/css/responsive.dataTables.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/DataTables/Buttons/css/buttons.dataTables.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/DataTables/AutoFill/css/autofill.dataTables.min.css")}}">

        @yield("styles")

    </head>

    <body class="skin-blue layout-top-nav fixed" style="height: auto; min-height: 100%;">
        <!-- Site wrapper -->
        <div class="wrapper">
            <!--Inicio Header-->
            @include("Template/headerMonitoreo")
            <!--Fin Header-->

            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content">
                    @yield('contenido')
                </section>
            </div>
            <!--Inicio Footer-->
            @include("Template/footer")
            <!--Fin Footer-->
        </div>
        <!-- jQuery-->
        <script src="{{asset("assets/bower_components/jquery/dist/jquery.min.js")}}"></script>
        <script src="{{asset("assets/dist/js/jquery-3.3.1.js")}}"></script>
        <script src="{{asset("assets/bower_components/bootstrap/dist/js/bootstrap.min.js")}}"></script>
        <script src="{{asset("assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js")}}"></script>
        <script src="{{asset("assets/bower_components/fastclick/lib/fastclick.js")}}"></script>
        <script src="{{asset("assets/dist/js/adminlte.min.js")}}"></script>
        <script src="{{asset("assets/dist/js/demo.js")}}"></script>
        <script src="{{asset("assets/CodeSeven/build/toastr.min.js")}}"></script>
        <script src="{{asset("assets/DataTables/dist/js/jquery.dataTables.min.js")}}"></script>
        <script src="{{asset("assets/DataTables/dist/js/dataTables.bootstrap4.min.js")}}"></script>
        <script src="{{asset("assets/DataTables/Responsive/js/dataTables.responsive.min.js")}}"></script>
        <script src="{{asset("assets/DataTables/Buttons/js/dataTables.buttons.min.js")}}"></script>
        <script src="{{asset("assets/DataTables/Buttons/js/buttons.flash.min.js")}}"></script>
        <script src="{{asset("assets/DataTables/Buttons/js/buttons.html5.min.js")}}"></script>
        <script src="{{asset("assets/DataTables/Buttons/js/buttons.print.min.js")}}"></script>
        <script src="{{asset("assets/DataTables/JsZip/js/jszip.min.js")}}"></script>
        <script src="{{asset("assets/DataTables/PdfMake/js/pdfmake.min.js")}}"></script>
        <script src="{{asset("assets/DataTables/PdfMake/js/vfs_fonts.js")}}"></script>
        <script src="{{asset("assets/DataTables/AutoFill/js/dataTables.autoFill.min.js")}}"></script>
        <script src="{{asset("assets/bower_components/bootstrap-daterangepicker/daterangepicker.js")}}"></script>
        <script src="{{asset("assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js")}}"></script>
        <script src="{{asset("assets/bower_components/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js")}}"></script>
        <script src="{{asset("assets/bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min.js")}}"></script>
        <script src="{{asset("assets/bower_components/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.es.js")}}"></script>
        <script src="{{asset("assets/Highcharts/code/modules/exporting.js")}}" type="text/javascript"></script>
        <script src="{{asset("assets/Highcharts/code/highcharts.js")}}" type="text/javascript"></script>
        <script src="{{asset("assets/Highcharts/code/highcharts-more.js")}}" type="text/javascript"></script>
        <script src="{{asset("assets/Highcharts/code/highcharts.js")}}" type="text/javascript"></script>
        <script src="{{asset("assets/Highcharts/code/highcharts-3d.js")}}" type="text/javascript"></script>
        <script src="{{asset("assets/Highcharts/code/modules/exporting.js")}}" type="text/javascript"></script>
        <script src="{{asset("assets/Highcharts/code/modules/export-data.js")}}" type="text/javascript"></script>
        <script src="{{asset("assets/plugins/input-mask/jquery.inputmask.js")}}"></script>
        <script src="{{asset("assets/plugins/input-mask/jquery.inputmask.date.extensions.js")}}"></script>
        <script src="{{asset("assets/plugins/input-mask/jquery.inputmask.extensions.js")}}"></script>
        @yield("scripts")

    </body>
</html>
