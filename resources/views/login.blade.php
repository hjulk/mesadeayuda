<!DOCTYPE html>
<html>
<head>
    <title>HelpDesk CRSCB</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="image/x-icon" rel="icon" href="{{asset("assets/dist/img/helpdesk.png")}}">
    <meta name="keywords" content="Slide Login Form template Responsive, Login form web template, Flat Pricing tables, Flat Drop downs Sign up Web Templates, Flat Web Templates, Login sign up Responsive web template, SmartPhone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
        <script>
            addEventListener("load", function () {
                setTimeout(hideURLbar, 0);
            }, false);

            function hideURLbar() {
                window.scrollTo(0, 1);
            }
        </script>
    <link href="{{asset("assets/Login/css/style.css")}}" rel="stylesheet" type="text/css" media="all" />
    <link href="{{asset("assets/Login/css/font-awesome.min.css")}}" rel="stylesheet" type="text/css" media="all" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset("assets/Login/vendor/bootstrap/css/bootstrap.min.css")}}">
    <link rel="stylesheet" type="text/css" href="{{asset("assets/Login/fonts/font-awesome-4.7.0/css/font-awesome.min.css")}}">
    <link rel="stylesheet" type="text/css" href="{{asset("assets/Login/vendor/animate/animate.css")}}">
    <link rel="stylesheet" type="text/css" href="{{asset("assets/Login/vendor/css-hamburgers/hamburgers.min.css")}}">
    <link rel="stylesheet" type="text/css" href="{{asset("assets/Login/vendor/select2/select2.min.css")}}">
    <link rel="stylesheet" type="text/css" href="{{asset("assets/Login/css/util.css")}}">
    <link rel="stylesheet" type="text/css" href="{{asset("assets/Login/css/main.css")}}">
    <link rel="stylesheet" href="{{asset("assets/dist/css/skins/_all-skins.min.css")}}">
    <link rel="stylesheet" href="{{asset("assets/plugins/iCheck/square/blue.css")}}">
    <link rel="stylesheet" href="{{asset("assets/CodeSeven/build/toastr.min.css")}}">
</head>
    <body>

        <div class="w3layouts-main">
            <div class="bg-layer">
                <h1></h1>
                <div class="header-main">
                    <div class="main-icon">
                        {{--  <img src="{{asset("assets/dist/img/images2.jpg")}}" style="border-radius: 50%;">  --}}
                        <img src="{{asset("assets/dist/img/images1.jpg")}}" style="--border-radius: 50%;max-width: 70% !important;filter: opacity(0.7);">
                    </div>
                    <br>
                    <div class="header-left-bottom">
                            {!! Form::open(['action' => 'loginController@Acceso', 'method' => 'post', 'enctype' => 'multipart/form-data','class' => 'login100-form validate-form','autocomplete'=>'off']) !!}
                            <fieldset>
                                <div class="wrap-input100 validate-input" data-validate = "Usuario es requerido">
                                    <input class="input100" type="text" name="user" id="user" placeholder="Usuario">
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                    </span>
                                </div>

                                <div class="wrap-input100 validate-input" data-validate = "Contraseña es requerida">
                                    <input class="input100" type="password" name="password" placeholder="Contraseña">
                                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                    <span class="focus-input100"></span>
                                    <span class="symbol-input100">
                                        <i class="fa fa-lock" aria-hidden="true"></i>
                                    </span>
                                </div>
                                <div class="container-login100-form-btn">

                                </div>
                                <div class="container-login100-form-btn">
                                    <button class="login100-form-btn"  id="btnLogin">
                                        Ingresar
                                    </button>
                                </div>

                                <div class="text-center p-t-12">
                                    <a class="txt2" href="#" data-toggle="modal" data-target="#modal-rcontrasena">
                                    <span class="txt1">
                                        Olvide mi Contraseña
                                    </span>
                                    </a>
                                </div>
                            </fieldset>
                            {!!  Form::close() !!}
                    </div>

                </div>
            </div>
        </div>
        @include('Modals.ModalRContrasena')
        <script src="{{asset("assets/Login/vendor/jquery/jquery-3.2.1.min.js")}}"></script>
        <script src="{{asset("assets/Login/vendor/bootstrap/js/popper.js")}}"></script>
        <script src="{{asset("assets/Login/vendor/bootstrap/js/bootstrap.min.js")}}"></script>
        <script src="{{asset("assets/Login/vendor/select2/select2.min.js")}}"></script>
        <script src="{{asset("assets/Login/vendor/tilt/tilt.jquery.min.js")}}"></script>
        <script >
            $('.js-tilt').tilt({
                scale: 0.8
            });
        </script>
        <script src="{{asset("assets/Login/js/main.js")}}"></script>
        <script src="{{asset("assets/dist/js/adminlte.min.js")}}"></script>
        <script src="{{asset("assets/plugins/iCheck/icheck.min.js")}}"></script>
        <script src="{{asset("assets/CodeSeven/build/toastr.min.js")}}"></script>
        {{-- <script src="{{asset("assets/dist/js/helpdesk/login.js")}}"></script> --}}
        <script>
            $(function () {
                $('input').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red',
                increaseArea: '20%' /* optional */
                });
            });
        </script>
        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        </script>
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
    </body>
</html>
