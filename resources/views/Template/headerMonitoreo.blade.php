<header class="main-header">
    <nav class="navbar navbar-static-top" style="background-color: rgb(162, 27, 37) !important;">
        <div class="navbar navbar-header">
            <a href="dashboardMonitoreo" class="navbar-brand" style="background-color: rgb(162, 27, 37) !important;">
                <b><i class="fa fa-dashboard"></i>&nbsp;&nbsp;Help Desk TICS</b>
            </a>
        </div>
            <div class="navbar-custom-menu">

                <ul class="nav navbar-nav">

                    <li class="dropdown user user-menu">
                        <a href="index" class="dropdown-toggle" data-toggle="dropdown">
                                {!! Session::get('ProfilePicMenuM') !!}
                                 <span class="hidden-xs">{!! Session::get('NombreUsuario') !!}</span>
                        </a>
                        <ul class="dropdown-menu" style="background-color: rgb(162, 27, 37) !important;">
                            <!-- User image -->
                            <li class="user-header" style="background-color: rgb(162, 27, 37) !important;">
                                {{--  <img src="{{asset("assets/dist/img/{!! Session::get('ProfilePicM') !!}")}}" class="img-circle" alt="User Image">  --}}
                                {!! Session::get('ProfilePicM') !!}
                                     <p>

                                            {!! Session::get('NombreUsuario') !!}
                                    <small>Usuario desde {!! Session::get('FechaCreacion') !!}</small>
                                </p>
                            </li>

                            <li class="user-footer" style="background-color: rgb(162, 27, 37) !important;">
                                <div class="pull-right">
                                    <a href="logout" class="btn btn-default btn-flat">Cerrar Sesión</a>
                                </div>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </nav>
    </header>

