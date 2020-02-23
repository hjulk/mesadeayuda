<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>HelpDesk CRCSCB | Calificacion</title>
        <link type="image/x-icon" rel="icon" href="{{asset("assets/dist/img/helpdesk.png")}}">
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
        <style>
            div.fondo{
                position: static;
                z-index: 5;
                border-radius: 10px;
                width: 96%;
                height: 575px;
                padding-top: 60px;
                padding-left: 39px;
            }
            .imagen{
                position: static;
                float: left;
                box-sizing: content-box;
                height: 98%;
                width: 50%;
                border-radius: 25px;
                padding: 10;
            }
            .imagen #mesa{
                width: 102%;;
                height: 97%;
                padding: 10px;
            }
            div.logo{
                position: static;
                float: right;
                width: 46%;
                height: 100%;
                border-radius: 29px;
            }
            .logo #UTS{
                width: 100%;
                height: 100%;
                display: block;
                margin: auto;
            }
            h1{
                font-family: verdana;
                font-size: xx-large;
                font-weight: unset;
            }
            .text{
                overflow: hidden;
                position: absolute;
                height: 200px%;
                width: 50%;
                top: 50%;
                margin-top: -100px;

            }
        </style>

    </head>

    <body>

        <div class="fondo">
            <div class="imagen">
                <div class="text">
                    <h1>{{$MENSAJE}}</h1>
                </div>
            </div>
            <div class="logo">
                <img id="UTS" src="{{asset("assets/dist/img/support.png")}}">

            </div>
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

    </body>

</html>
