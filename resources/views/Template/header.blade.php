<header class="main-header">

    <!-- Logo -->
    <a href="dashboard" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b><img src="{{asset("assets/dist/img/logo.png")}}"></b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg" style="font-size:16px;"><i class="fa fa-ticket"></i>&nbsp;HelpDesk TICS</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Menú</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->

                <!-- Notifications: style can be found in dropdown.less -->
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell"></i>
                        <span class="label label-warning">{!! Session::get('Notificaciones') !!}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">Usted tiene {!! Session::get('Notificaciones') !!} tickets asignados</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                @if(Session::get('Notificacion'))
                                    @foreach(Session::get('Notificacion') as $valor)
                                        <li>
                                            <a href="tickets">
                                                <i class="fa fa-ticket text-aqua"></i>{{$valor['creador']}} le asigno un ticket
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </li>
                        <li class="footer"><a href="tickets">Ver Todos</a></li>
                    </ul>
                </li>

                <!-- Tasks: style can be found in dropdown.less -->

                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="index" class="dropdown-toggle" data-toggle="dropdown">
                            {!! Session::get('ProfilePicMenu') !!}
                             <span class="hidden-xs">{!! Session::get('NombreUsuario') !!}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            {{--  <img src="{{asset("assets/dist/img/user2-160x160.jpg")}}" class="img-circle" alt="User Image">  --}}
                            {!! Session::get('ProfilePic') !!}
                                 <p>

                                        {!! Session::get('NombreUsuario') !!}
                                <small>Usuario desde {!! Session::get('FechaCreacion') !!}</small>
                            </p>
                        </li>

                        <li class="user-footer">
                            <div class="pull-left">
                                    @if(Session::get('Rol') === 1)
                                <a href="usuarios" class="btn btn-default btn-flat">Perfil</a>
                                @else
                                <a href="profile" class="btn btn-default btn-flat">Perfil</a>
                                @endif
                            </div>
                            <div class="pull-right">
                                <a href="logout" class="btn btn-default btn-flat">Cerrar Sesión</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                {{-- <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li> --}}
            </ul>
        </div>
    </nav>
</header>

