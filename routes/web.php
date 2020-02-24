<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;

Cache::flush();
Session::flush();
Artisan::call('cache:clear');
Route::get('/', 'loginController@index');
Route::post('RecuperarContrasena', 'loginController@RecuperarContrasena')->name('RecuperarContrasena');
Route::post('RecuperarContrasenaUsuario', 'loginController@RecuperarContrasenaUsuario')->name('RecuperarContrasenaUsuario');

Route::get('/CREARSOLICITUD','TicketsController@crearSolicitud')->name('crearSolicitud');
Route::get('/crearsolicitud','TicketsController@crearSolicitud')->name('crearSolicitud');
Route::get('/crearSolicitud','TicketsController@crearSolicitud')->name('crearSolicitud');
Route::get('buscarCategoriaS', 'TicketsController@buscarCategoriaS')->name('buscarCategoriaS');

// Route::auth();


Route::post('Acceso', 'loginController@Acceso')->name('Acceso');
Route::post('AccesoUsuario', 'loginController@AccesoUsuario')->name('AccesoUsuario');
// Route::post('nuevaSolicitud', 'TicketsController@nuevaSolicitud')->name('nuevaSolicitud');
Auth::routes();

Route::group(['middleware' => 'revalidate'], function () {
    Cache::flush();
    Route::group(['middleware' => 'monitoreo'], function () {
        Cache::flush();
        Artisan::call('cache:clear');
        Route::get('dashboardMonitoreo', 'loginController@dashboardMonitoreo')->name('dashboardMonitoreo');
    });
    Route::group(['middleware' => 'admin'], function () {
        Cache::flush();
        Artisan::call('cache:clear');
        Route::get('admin/dashboard', 'Admin\AdministracionController@dashboard')->name('admin/dashboard');
    });
    Route::group(['middleware' => 'user'], function () {
        Cache::flush();
        Artisan::call('cache:clear');
        Route::get('user/dashboard', 'User\UsuariosController@inicio')->name('user/dashboard');
    });
    Route::group(['middleware' => 'usuarioFinal'], function () {
        Cache::flush();
        Artisan::call('cache:clear');
        Route::get('usuario/dashboard', 'Usuario\UsuarioController@inicio')->name('usuario/dashboard');
    });


    // MODULO ADMINISTRACIÓN

    Route::group(['prefix' => 'admin','namespace' => 'Admin','middleware' => 'admin'],function(){
        Cache::flush();
        Artisan::call('cache:clear');
        Route::get('sedes','SedesController@sedes')->name('sedes');
        Route::get('roles','RolesController@roles')->name('roles');
        Route::get('usuarios','UsuarioController@index')->name('usuarios');
        Route::get('usuarioFinal','UsuarioController@usuarioFinal')->name('usuarioFinal');
        Route::post('crearRol','RolesController@crearRol')->name('crearRol');
        Route::post('actualizarRol','RolesController@actualizarRol')->name('actualizarRol');
        Route::post('crearCategoria','RolesController@crearCategoria')->name('crearCategoria');
        Route::post('actualizarCategoria','RolesController@actualizarCategoria')->name('actualizarCategoria');
        Route::post('crearUsuario','UsuarioController@crearUsuario')->name('crearUsuario');
        Route::post('crearUsuarioFinal','UsuarioController@crearUsuarioFinal')->name('crearUsuarioFinal');
        Route::post('actualizarUsuario','UsuarioController@actualizarUsuario')->name('actualizarUsuario');
        Route::post('actualizarUsuarioFinal','UsuarioController@actualizarUsuarioFinal')->name('actualizarUsuarioFinal');
        Route::post('actualizarUsuarioP','UsuarioController@actualizarUsuarioP')->name('actualizarUsuarioP');
        Route::post('actualizarUsuarioAdmin','UsuarioController@actualizarUsuarioAdmin')->name('actualizarUsuarioAdmin');
        Route::post('crearSede','SedesController@crearSede')->name('crearSede');
        Route::post('actualizarSede','SedesController@actualizarSede')->name('actualizarSede');
        Route::post('crearArea','SedesController@crearArea')->name('crearArea');
        Route::post('actualizarArea','SedesController@actualizarArea')->name('actualizarArea');
        Route::get('tickets','TicketsController@tickets')->name('tickets');
        Route::post('reabrirTicket','TicketsController@reabrirTicket')->name('reabrirTicket');
        Route::get('reporteTickets','TicketsController@reporteTickets')->name('reporteTickets');
        Route::post('consultarTicket','TicketsController@consultarTickets')->name('consultarTicket');
        Route::post('consultarxTicket','TicketsController@consultarxTicket')->name('consultarxTicket');
        Route::get('ticketsUsuario','TicketsController@ticketsUsuario')->name('ticketsUsuario');
        Route::get('mobile','InventarioController@mobile')->name('mobile');
        Route::get('lineMobile','InventarioController@lineMobile')->name('lineMobile');


        Route::get('desktops','InventarioController@desktops')->name('desktops');
        Route::get('printers','InventarioController@printers')->name('printers');
        Route::get('periferic','InventarioController@periferic')->name('periferic');
        Route::get('asigneds','InventarioController@asigneds')->name('asigneds');
        Route::get('consumible','InventarioController@consumible')->name('consumible');
        Route::get('calificaciones','AdministracionController@calificaciones')->name('calificaciones');
        Route::get('turnos','TurnosController@turnos')->name('turnos');
        Route::post('crearTurno','TurnosController@crearTurno')->name('crearTurno');
        Route::post('actualizarTurno','TurnosController@actualizarTurno')->name('actualizarTurno');
        Route::get('ticketsRecurrentes','TicketsController@ticketsRecurrentes')->name('ticketsRecurrentes');

        Route::get('/logout', function() {
            Auth::logout();
            Session::flush();
            Artisan::call('cache:clear');
            Cache::flush();
            // Session::destroy();
            return Redirect::to('/')->with('mensaje_login', 'Salida Segura');
        });
    });

    // MODULO USUARIOS

    Route::group(['prefix' => 'user','namespace' => 'User','middleware' => 'user'],function(){
        Cache::flush();
        Artisan::call('cache:clear');
        Route::get('tickets','TicketsUserController@tickets')->name('tickets');
        Route::get('ticketsUsuario','TicketsUserController@ticketsUsuario')->name('ticketsUsuario');
        Route::get('reporteTickets','TicketsUserController@reporteTickets')->name('reporteTickets');
        Route::post('consultarTicket', 'TicketsUserController@consultarTickets')->name('consultarTicket');
        Route::post('consultarxTicket','TicketsUserController@consultarxTicket')->name('consultarxTicket');
        Route::get('profile', 'UsuariosController@profile')->name('profile');
        Route::post('actualizarUsuario', 'UsuariosController@actualizarUsuario')->name('actualizarUsuario');
        Route::get('mobile','InventarioController@mobile')->name('mobile');
        Route::get('lineMobile','InventarioController@lineMobile')->name('lineMobile');


        Route::get('desktops','InventarioController@desktops')->name('desktops');
        Route::get('printers','InventarioController@printers')->name('printers');
        Route::get('periferic','InventarioController@periferic')->name('periferic');
        Route::get('asigneds','InventarioController@asigneds')->name('asigneds');
        Route::get('consumible','InventarioController@consumible')->name('consumible');
        Route::get('turnos','UsuariosController@turnos')->name('turnos');
        Route::get('/logout', function() {
            Auth::logout();
            Session::flush();
            Artisan::call('cache:clear');
            Cache::flush();
            // Session::destroy();
            return Redirect::to('/')->with('mensaje_login', 'Salida Segura');
        });
    });

    Route::group(['prefix' => 'usuario','namespace' => 'Usuario','middleware' => 'usuarioFinal'],function(){
        Cache::flush();
        Artisan::call('cache:clear');
        Route::get('crearTicket','UsuarioController@inicio')->name('crearTicket');
        Route::post('nuevaSolicitud', 'UsuarioController@nuevaSolicitud')->name('nuevaSolicitud');
        Route::get('/logout', function() {
            Auth::logout();
            Session::flush();
            Artisan::call('cache:clear');
            Cache::flush();
            // Session::destroy();
            return Redirect::to('/crearSolicitud')->with('mensaje_login', 'Salida Segura');
        });
    });

    Route::get('/logout', function() {
        Auth::logout();
        Session::flush();
        Artisan::call('cache:clear');
        Cache::flush();
        // Session::destroy();

        return Redirect::to('/')->with('mensaje', 'Salida Segura');
    });

    // MODULO TICKETS

    Route::post('crearTicket', 'TicketsController@crearTicket')->name('crearTicket');
    Route::post('crearTicketUsuario', 'TicketsController@crearTicketUsuario')->name('crearTicketUsuario');
    Route::get('buscarCategoria', 'TicketsController@buscarCategoria')->name('buscarCategoria');
    Route::get('buscarCategoriaRepo','TicketsController@buscarCategoriaRepo')->name('buscarCategoriaRepo');
    Route::get('buscarCategoriaUPD', 'TicketsController@buscarCategoriaUPD')->name('buscarCategoriaUPD');
    Route::get('buscarSede', 'TicketsController@buscarSede')->name('buscarSede');
    Route::get('buscarArea', 'TicketsController@buscarArea')->name('buscarArea');
    Route::post('actualizarTicket', 'TicketsController@actualizarTicket')->name('actualizarTicket');
    Route::post('actualizarTicketUsuario', 'TicketsController@actualizarTicketUsuario')->name('actualizarTicketUsuario');
    Route::any('calificarTicket', 'TicketsController@calificarTicket')->name('calificarTicket');
    Route::post('crearTicketRecurrente','TicketsController@crearTicketRecurrente')->name('crearTicketRecurrente');
    Route::post('actualizarTicketRecurrente','TicketsController@actualizarTicketRecurrente')->name('actualizarTicketRecurrente');

    // MODULO INVENTARIOS

    Route::post('asignacionEquipoMovil', 'InventarioController@asignacionEquipoMovil')->name('asignacionEquipoMovil');
    Route::post('actualizacionEquipoMovil', 'InventarioController@actualizacionEquipoMovil')->name('actualizacionEquipoMovil');
    Route::post('asignacionLineaMovil', 'InventarioController@asignacionLineaMovil')->name('asignacionLineaMovil');
    Route::post('actualizacionLineaMovil', 'InventarioController@actualizacionLineaMovil')->name('actualizacionLineaMovil');
    Route::post('ingresoEquipo', 'InventarioController@ingresoEquipo')->name('ingresoEquipo');
    Route::post('actualizacionEquipo', 'InventarioController@actualizacionEquipo')->name('actualizacionEquipo');
    Route::post('ingresoPeriferico', 'InventarioController@ingresoPeriferico')->name('ingresoPeriferico');
    Route::post('actualizacionPeriferico', 'InventarioController@actualizacionPeriferico')->name('actualizacionPeriferico');
    Route::post('ingresarConsumible', 'InventarioController@ingresarConsumible')->name('ingresarConsumible');
    Route::post('actualizarConsumible', 'InventarioController@actualizarConsumible')->name('actualizarConsumible');
    Route::post('ingresarImpresora', 'InventarioController@ingresarImpresora')->name('ingresarImpresora');
    Route::post('actualizarImpresora', 'InventarioController@actualizarImpresora')->name('actualizarImpresora');
    Route::post('ingresarAsignacion', 'InventarioController@ingresarAsignacion')->name('ingresarAsignacion');
    Route::post('actualizarAsignacion', 'InventarioController@actualizarAsignacion')->name('actualizarAsignacion');
    Route::get('buscarEquipo', 'InventarioController@buscarEquipo')->name('buscarEquipo');


});
