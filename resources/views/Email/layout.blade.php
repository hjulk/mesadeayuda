<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>HelpDesk QCL | @yield('titulo','Dashboard')</title>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link type="image/x-icon" rel="icon" href="{{asset("assets/$theme/dist/img/logo.png")}}">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="{{asset("assets/$theme/bower_components/bootstrap/dist/css/bootstrap.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/$theme/bower_components/font-awesome/css/font-awesome.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/$theme/bower_components/Ionicons/css/ionicons.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/$theme/bower_components/bootstrap-daterangepicker/daterangepicker.css")}}">
        <link rel="stylesheet" href="{{asset("assets/$theme/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/$theme/bower_components/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/$theme/dist/css/AdminLTE.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/$theme/dist/css/skins/_all-skins.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/$theme/CodeSeven/build/toastr.min.css")}}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link rel="stylesheet" href="{{asset("assets/$theme/$datatable/dist/css/jquery.dataTables.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/$theme/$datatable/dist/css/dataTables.bootstrap4.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/$theme/$datatable/Responsive/css/responsive.dataTables.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/$theme/$datatable/Buttons/css/buttons.dataTables.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/$theme/$datatable/AutoFill/css/autofill.dataTables.min.css")}}">

        @yield("styles")

    </head>

    <body class="skin-blue fixed sidebar-mini sidebar-mini-expand-feature sidebar-collapse skin-blue-light">
        <!-- Site wrapper -->
        <div class="wrapper">
            <!--Inicio Header-->
            @include("theme/$theme/header")
            <!--Fin Header-->
            <!--Inicio Aside-->
            @include("theme/$theme/aside")
            <!--Fin Aside-->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content">
                    @yield('contenido')
                </section>
            </div>
            <!--Inicio Footer-->
            @include("theme/$theme/footer")
            <!--Fin Footer-->
        </div>
        <!-- jQuery-->
        <script src="{{asset("assets/$theme/bower_components/jquery/dist/jquery.min.js")}}"></script>
        <script src="{{asset("assets/$theme/dist/js/jquery-3.3.1.js")}}"></script>
        <script src="{{asset("assets/$theme/bower_components/bootstrap/dist/js/bootstrap.min.js")}}"></script>
        <script src="{{asset("assets/$theme/bower_components/jquery-slimscroll/jquery.slimscroll.min.js")}}"></script>
        <script src="{{asset("assets/$theme/bower_components/fastclick/lib/fastclick.js")}}"></script>
        <script src="{{asset("assets/$theme/dist/js/adminlte.min.js")}}"></script>
        <script src="{{asset("assets/$theme/dist/js/demo.js")}}"></script>
        <script src="{{asset("assets/$theme/CodeSeven/build/toastr.min.js")}}"></script>
        <script src="{{asset("assets/$theme/$datatable/dist/js/jquery.dataTables.min.js")}}"></script>
        <script src="{{asset("assets/$theme/$datatable/dist/js/dataTables.bootstrap4.min.js")}}"></script>
        <script src="{{asset("assets/$theme/$datatable/Responsive/js/dataTables.responsive.min.js")}}"></script>
        <script src="{{asset("assets/$theme/$datatable/Buttons/js/dataTables.buttons.min.js")}}"></script>
        <script src="{{asset("assets/$theme/$datatable/Buttons/js/buttons.flash.min.js")}}"></script>
        <script src="{{asset("assets/$theme/$datatable/Buttons/js/buttons.html5.min.js")}}"></script>
        <script src="{{asset("assets/$theme/$datatable/Buttons/js/buttons.print.min.js")}}"></script>
        <script src="{{asset("assets/$theme/$datatable/JsZip/js/jszip.min.js")}}"></script>
        <script src="{{asset("assets/$theme/$datatable/PdfMake/js/pdfmake.min.js")}}"></script>
        <script src="{{asset("assets/$theme/$datatable/PdfMake/js/vfs_fonts.js")}}"></script>
        <script src="{{asset("assets/$theme/$datatable/AutoFill/js/dataTables.autoFill.min.js")}}"></script>
        <script src="{{asset("assets/$theme/bower_components/bootstrap-daterangepicker/daterangepicker.js")}}"></script>
        <script src="{{asset("assets/$theme/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js")}}"></script>
        <script src="{{asset("assets/$theme/bower_components/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js")}}"></script>
        <script src="{{asset("assets/$theme/bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min.js")}}"></script>
        <script src="{{asset("assets/$theme/bower_components/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.es.js")}}"></script>
        @yield("scripts")

    </body>
</html>
