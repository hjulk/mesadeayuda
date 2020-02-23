<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>HelpDesk QCL | Login</title>
        <link type="image/x-icon" rel="icon" href="{{asset("assets/dist/img/logo.png")}}">
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="{{asset("assets/bower_components/bootstrap/dist/css/bootstrap.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/bower_components/font-awesome/css/font-awesome.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/bower_components/Ionicons/css/ionicons.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/dist/css/AdminLTE.min.css")}}">
        <link rel="stylesheet" href="{{asset("assets/plugins/iCheck/square/blue.css")}}">

        {!! NoCaptcha::renderJs() !!}

        <style>
            .login-page{
                background-image: url('{{asset("assets/dist/img/fondo.jpg")}}');
                background-position: center center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                background-size: cover;
                background-color: white;
            }
            .login-box-body{
                background-color: transparent !important;
            }
        </style>

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">

            <div class="login-logo">
                <img src="{{asset("assets/dist/img/logo.png")}}" style="height: 50%;width: 50%;filter: brightness(50.5) !important;">
            </div>

            <div class="login-box-body">

                <form action="login" method="post">
                    <div class="form-group has-feedback">
                        <input type="email" class="form-control" placeholder="Usuario">
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" placeholder="Contraseña">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        {!! NoCaptcha::display() !!}
                    </div>
                    <div class="row">
                        <div class="col-xs-8"></div>

                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Login</button>
                        </div>

                    </div>
                </form>
                <a href="login"><font style="color:white;">Recordar Contraseña</font></a><br>
            </div>
        </div>

        <script>
            $(function () {
                $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' /* optional */
                });
            });
        </script>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

        <script src="{{asset("assets/bower_components/jquery/dist/jquery.min.js")}}"></script>

        <script src="{{asset("assets/bower_components/bootstrap/dist/js/bootstrap.min.js")}}"></script>

        <script src="{{asset("assets/plugins/iCheck/icheck.min.js")}}"></script>
    </body>
</html>

