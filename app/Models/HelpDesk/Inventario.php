<?php

namespace App\Models\HelpDesk;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Inventario extends Model
{
    public static function ListarEstado(){
        $ListarEstado = DB::Select("SELECT * FROM activo");
        return $ListarEstado;
    }

    public static function ListarEstadoEquipos(){
        $ListarEstado = DB::Select("SELECT * FROM estado_equipo");
        return $ListarEstado;
    }

    public static function EstadoEquipoId($IdEstadoEquipo){
    $EstadoEquipoId    = DB::Select("SELECT * FROM estado_equipo WHERE id = $IdEstadoEquipo");
        return $EstadoEquipoId;
    }

    // EQUIPOS MOVILES

    public static function MobileStock(){
        $Stock = DB::Select("SELECT COUNT(*) AS total FROM equipo_movil WHERE estado_equipo = 1");
        return $Stock;
    }

    public static function MobileAsigned(){
        $Asigned = DB::Select("SELECT COUNT(*) AS total FROM equipo_movil WHERE estado_equipo = 2");
        return $Asigned;
    }

    public static function MobileMaintenance(){
        $Maintenance = DB::Select("SELECT COUNT(*) AS total FROM equipo_movil WHERE estado_equipo = 3");
        return $Maintenance;
    }

    public static function MobileObsolete(){
        $Obsolete = DB::Select("SELECT COUNT(*) AS total FROM equipo_movil WHERE estado_equipo = 4");
        return $Obsolete;
    }

    public static function ListarEquiposMoviles(){
        $ListarEquiposMoviles = DB::Select("SELECT * FROM equipo_movil");
        return $ListarEquiposMoviles;
    }

    public static function EvidenciaEquipoM($IdEquipoMovil){
        $EvidenciaEquipoM = DB::Select("SELECT * FROM evidencia_inventario WHERE id_equipo_movil = $IdEquipoMovil");
        return $EvidenciaEquipoM;
    }

    public static function ListadoTipoEquipoMovil(){
        $ListadoTipoEquipoMovil = DB::Select("SELECT * FROM tipo_equipo WHERE id IN (3,4,5)");
        return $ListadoTipoEquipoMovil;
    }

    public static function BuscarInfoEquipoMovil($Serial){
        $BuscarInfoEquipoMovil = DB::Select("SELECT * FROM equipo_movil WHERE serial LIKE '%$Serial%'");
        return $BuscarInfoEquipoMovil;
    }

    public static function RegistrarEquipoMovil($TipoEquipo,$FechaAdquisicion,$Serial,$Marca,$Modelo,$IMEI,$Capacidad,$LineaMovil,$Area,$NombreAsignado,$EstadoEquipo,$creadoPor){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema  = date('Y-m-d H:i');
        $fechaCreacion  = date('Y-m-d H:i', strtotime($fecha_sistema));
        $RegistrarEquipoMovil = DB::Insert('INSERT INTO equipo_movil (tipo_equipo,fecha_ingreso,serial,marca,modelo,IMEI,capacidad,usuario,area,linea,estado_equipo,created_at,user_id)
                                            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)',
                                            [$TipoEquipo,$FechaAdquisicion,$Serial,$Marca,$Modelo,$IMEI,$Capacidad,$NombreAsignado,$Area,$LineaMovil,$EstadoEquipo,$fechaCreacion,$creadoPor]);

        if($RegistrarEquipoMovil){
            if($LineaMovil){
                DB::Update("UPDATE linea SET estado_equipo = 2 WHERE id = $LineaMovil");
            }

        }
        return $RegistrarEquipoMovil;
    }

    public static function ActualizarEquipoMovil($TipoEquipo,$FechaAdquisicion,$Serial,$Marca,$Modelo,$IMEI,$Capacidad,$LineaMovil,$Area,$NombreAsignado,$EstadoEquipo,$creadoPor,$idEquipoMovil,$Desvincular){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema      = date('Y-m-d H:i');
        $fechaActualizacion = date('Y-m-d H:i', strtotime($fecha_sistema));
        if($Desvincular === 1){
            $IdLineaMovil = 0;
            DB::Update("UPDATE linea UPDATE linea SET estado_equipo = 1 WHERE id = $LineaMovil");
        }else{
            $IdLineaMovil = $LineaMovil;
        }
        if($LineaMovil){
            if(($EstadoEquipo === 3) || ($EstadoEquipo === 4) || ($EstadoEquipo === 1)){
                $IdLineaMovil = 0;
                DB::Update("UPDATE linea SET estado_equipo = 1,cc = null,personal = null,area = null WHERE id = $LineaMovil");
                DB::Update("UPDATE asignados SET id_linea = null,estado_asignado = $EstadoEquipo,update_at = '$fechaActualizacion' WHERE id_linea = $LineaMovil");
                DB::Update("UPDATE asignados SET id_movil = null,estado_asignado = $EstadoEquipo,update_at = '$fechaActualizacion' WHERE id_movil = $idEquipoMovil");
            }else{
                DB::Update("UPDATE linea SET estado_equipo = 2 WHERE id = $LineaMovil");
            }
        }
        if(($EstadoEquipo === 3) || ($EstadoEquipo === 4) || ($EstadoEquipo === 1)){
            $NombreAsignado = null;
            $Area = null;
        }
        $ActualizarEquipoMovil = DB::Update("UPDATE equipo_movil SET
                                                tipo_equipo     = $TipoEquipo,
                                                fecha_ingreso   = '$FechaAdquisicion',
                                                serial          = '$Serial',
                                                marca           = '$Marca',
                                                modelo          = '$Modelo',
                                                IMEI            = '$IMEI',
                                                capacidad       = '$Capacidad',
                                                usuario         = '$NombreAsignado',
                                                area            = '$Area',
                                                linea           = $IdLineaMovil,
                                                estado_equipo   = $EstadoEquipo,
                                                update_at       = '$fechaActualizacion',
                                                user_id         = $creadoPor
                                                WHERE id = $idEquipoMovil");
        return $ActualizarEquipoMovil;
    }

    public static function BuscarLastEquipoMovil($creadoPor){
        $buscarUltimo = DB::Select("SELECT max(id) as id FROM equipo_movil WHERE user_id = $creadoPor");
        return $buscarUltimo;
    }

    public static function EvidenciaEM($idEquipoMovil,$NombreFoto){
        $Evidencia = DB::Insert('INSERT INTO evidencia_inventario (nombre,id_equipo_movil) VALUES (?, ?)', [$NombreFoto,$idEquipoMovil]);
        return $Evidencia;
    }

    public static function HistorialEM($idEquipoMovil,$Comentario,$EstadoEquipo,$creadoPor){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema      = date('Y-m-d H:i');
        $fechaCreacion = date('Y-m-d H:i', strtotime($fecha_sistema));
        DB::insert('INSERT INTO historial_inventario (id_movil,comentario,status_id,user_id,created)
                    VALUES (?,?,?,?,?)',
                    [$idEquipoMovil,$Comentario,$EstadoEquipo,$creadoPor,$fechaCreacion]);
    }

    public static function BuscarHistorialEM($IdEquipoMovil){
        $historial = DB::Select("SELECT * FROM historial_inventario WHERE id_movil = $IdEquipoMovil");
        return $historial;
    }

    public static function BuscarLineaMovilID($LineaMovil,$idEquipoMovil){
        $BuscarConsumibleID = DB::Select("SELECT * FROM equipo_movil WHERE id = $idEquipoMovil AND linea = $LineaMovil");
        return $BuscarConsumibleID;
    }

    // LINEA MOVIL

    public static function BuscarNroLinea($IdLinea){
        $BuscarNroLinea = DB::Select("SELECT * FROM linea WHERE id = $IdLinea");
        return $BuscarNroLinea;
    }

    public static function ListadoLineaMovil(){
        $ListadoLineaMovil = DB::Select("SELECT * FROM linea WHERE estado_equipo = 1 ORDER BY nro_linea");
        return $ListadoLineaMovil;
    }

    public static function ListadoLineaMovilUpd(){
        $ListadoLineaMovil = DB::Select("SELECT * FROM linea ORDER BY nro_linea AND estado_equipo IN (1,2)");
        return $ListadoLineaMovil;
    }

    public static function LineMobileStock(){
        $Stock = DB::Select("SELECT COUNT(*) AS total FROM linea WHERE estado_equipo = 1");
        return $Stock;
    }

    public static function LineMobileAsigned(){
        $Asigned = DB::Select("SELECT COUNT(*) AS total FROM linea WHERE estado_equipo = 2");
        return $Asigned;
    }

    public static function LineMobileMaintenance(){
        $Maintenance = DB::Select("SELECT COUNT(*) AS total FROM linea WHERE estado_equipo = 3");
        return $Maintenance;
    }

    public static function LineMobileObsolete(){
        $Obsolete = DB::Select("SELECT COUNT(*) AS total FROM linea WHERE estado_equipo = 4");
        return $Obsolete;
    }

    public static function ListarLineasMoviles(){
        $ListarEquiposMoviles = DB::Select("SELECT * FROM linea");
        return $ListarEquiposMoviles;
    }

    public static function EvidenciaLineaM($IdLineaMovil){
        $EvidenciaEquipoM = DB::Select("SELECT * FROM evidencia_inventario WHERE id_linea = $IdLineaMovil");
        return $EvidenciaEquipoM;
    }

    public static function EvidenciaLM($idEquipoMovil,$NombreFoto){
        $Evidencia = DB::Insert('INSERT INTO evidencia_inventario (nombre,id_linea) VALUES (?,?)', [$NombreFoto,$idEquipoMovil]);
        return $Evidencia;
    }

    public static function ProveedorLM(){
        $ListarProveedores = DB::Select("SELECT * FROM proveedor_linea");
        return $ListarProveedores;
    }

    public static function BuscarInfoLineaMovil($Serial){
        $BuscarInfoEquipoMovil = DB::Select("SELECT * FROM linea WHERE serial LIKE '%$Serial%'");
        return $BuscarInfoEquipoMovil;
    }

    public static function RegistrarLineaMovil($NroLinea,$FechaAdquisicion,$Serial,$Activo,$Proveedor,$Plan,$PtoCargo,$Cc,$Area,$Personal,$Estado,$creadoPor){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema      = date('Y-m-d H:i');
        $fechaCreacion = date('Y-m-d H:i', strtotime($fecha_sistema));
        $RegistrarLineaMovil = DB::insert('INSERT INTO linea (nro_linea,activo,proveedor,plan,serial,fecha_ingreso,pto_cargo,cc,area,personal,estado_equipo,created_at,user_id)
                                            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)',
                                            [$NroLinea,$Activo,$Proveedor,$Plan,$Serial,$FechaAdquisicion,$PtoCargo,$Cc,$Area,$Personal,$Estado,$fechaCreacion,$creadoPor]);
        return $RegistrarLineaMovil;
    }

    public static function ActualizarLineaMovil($NroLinea,$FechaAdquisicion,$Serial,$Activo,$Proveedor,$Plan,$PtoCargo,$Cc,$Area,$Personal,$Estado,$creadoPor,$IdLineaMovil){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema      = date('Y-m-d H:i');
        $fechaActualizacion = date('Y-m-d H:i', strtotime($fecha_sistema));
        if(($Estado === 3) || ($Estado === 4) || ($Estado === 1)){
            DB::Update("UPDATE asignados SET id_linea = null,estado_asignado = $Estado,update_at = '$fechaActualizacion' WHERE id_linea = $IdLineaMovil");
            $Area = null;
            $Cc = null;
            $Personal = null;
        }
        $ActualizarLineaMovil = DB::Update("UPDATE linea SET
                                                nro_linea       = '$NroLinea',
                                                fecha_ingreso   = '$FechaAdquisicion',
                                                serial          = '$Serial',
                                                activo          = $Activo,
                                                serial          = '$Serial',
                                                proveedor       = '$Proveedor',
                                                plan            = '$Plan',
                                                pto_cargo       = '$PtoCargo',
                                                area            = '$Area',
                                                cc              = '$Cc',
                                                personal        = '$Personal',
                                                estado_equipo   = $Estado,
                                                updated_at       = '$fechaActualizacion',
                                                actualizado_por = $creadoPor
                                                WHERE id = $IdLineaMovil");
        return $ActualizarLineaMovil;

    }

    public static function BuscarLastLineaMovil($creadoPor){
        $buscarUltimo = DB::Select("SELECT max(id) as id FROM linea WHERE user_id = $creadoPor");
        return $buscarUltimo;
    }

    public static function HistorialLM($idEquipoMovil,$Comentario,$EstadoEquipo,$creadoPor){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema      = date('Y-m-d H:i');
        $fechaCreacion = date('Y-m-d H:i', strtotime($fecha_sistema));
        DB::insert('INSERT INTO historial_inventario (id_linea,comentario,status_id,user_id,created)
                    VALUES (?,?,?,?,?)',
                    [$idEquipoMovil,$Comentario,$EstadoEquipo,$creadoPor,$fechaCreacion]);
    }

    public static function BuscarHistorialLM($IdLineaMovil){
        $historial = DB::Select("SELECT * FROM historial_inventario WHERE id_linea = $IdLineaMovil");
        return $historial;
    }

    // EQUIPOS

    public static function ListarEquipoUsuarioC(){
        $ListarEquipo = DB::Select("SELECT * FROM tipo_equipo WHERE id IN (1,2)");
        return $ListarEquipo;
    }

    public static function ListarTipoEquipos(){
        $ListarEquipo = DB::Select("SELECT * FROM tipo_equipo");
        return $ListarEquipo;
    }



    public static function BuscarEquipoId($IdTipoEquipo){
        $BuscarEquipoId = DB::Select("SELECT * FROM tipo_equipo WHERE id = $IdTipoEquipo");
        return $BuscarEquipoId;
    }

    public static function ListarMarcaActivo(){
        $Stock = DB::Select("SELECT * FROM equipo WHERE estado_equipo = 1");
        return $Stock;
    }

    public static function ListarMarcaActivoUpd(){
        $Stock = DB::Select("SELECT * FROM equipo WHERE estado_equipo IN (1,2)");
        return $Stock;
    }

    public static function BuscarXTipoEquipo($id){
        $BuscarEquipoId = DB::Select("SELECT * FROM equipo WHERE tipo_equipo = $id AND estado_equipo = 1");
        return $BuscarEquipoId;
    }

    public static function BuscarIdEquipo($IdEquipo){
        $BuscarEquipoId = DB::Select("SELECT * FROM equipo WHERE id = $IdEquipo");
        return $BuscarEquipoId;
    }

    public static function EquipoStock(){
        $Stock = DB::Select("SELECT COUNT(*) AS total FROM equipo WHERE estado_equipo = 1");
        return $Stock;
    }

    public static function EquipoAsigned(){
        $Asigned = DB::Select("SELECT COUNT(*) AS total FROM equipo WHERE estado_equipo = 2");
        return $Asigned;
    }

    public static function EquipoMaintenance(){
        $Maintenance = DB::Select("SELECT COUNT(*) AS total FROM equipo WHERE estado_equipo = 3");
        return $Maintenance;
    }

    public static function EquipoObsolete(){
        $Obsolete = DB::Select("SELECT COUNT(*) AS total FROM equipo WHERE estado_equipo = 4");
        return $Obsolete;
    }

    public static function ListarEquipos(){
        $ListarEquiposMoviles = DB::Select("SELECT * FROM equipo");
        return $ListarEquiposMoviles;
    }

    public static function BuscarTipoIngresoId($IdTipoIngreso){
        $BuscarTipoIngresoId = DB::Select("SELECT * FROM adquisision WHERE id = $IdTipoIngreso");
        return $BuscarTipoIngresoId;
    }

    public static function EvidenciaEquipo($IdEquipo){
        $EvidenciaEquipo = DB::Select("SELECT * FROM evidencia_inventario WHERE id_equipo = $IdEquipo");
        return $EvidenciaEquipo;
    }

    public static function ListarTipoIngreso(){
        $ListarTipoIngreso = DB::Select("SELECT * FROM adquisision");
        return $ListarTipoIngreso;
    }

    public static function IngresarEquipo($TipoEquipo,$TipoIngreso,$EmpresaRenting,$FechaAdquisicion,$Serial,$Marca,$Procesador,$VelProcesador,$DiscoDuro,$MemoriaRam,$EstadoEquipo,$creadoPor){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema      = date('Y-m-d H:i');
        $fechaCreacion = date('Y-m-d H:i', strtotime($fecha_sistema));
        $IngresarEquipo = DB::Insert('INSERT INTO equipo (tipo_equipo,tipo_ingreso,emp_renting,fecha_ingreso,serial,marca,procesador,vel_procesador,disco_duro,memoria_ram,estado_equipo,user_id,created_at)
                                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)',
                                        [$TipoEquipo,$TipoIngreso,$EmpresaRenting,$FechaAdquisicion,$Serial,$Marca,$Procesador,$VelProcesador,$DiscoDuro,$MemoriaRam,$EstadoEquipo,$creadoPor,$fechaCreacion]);
        return $IngresarEquipo;
    }

    public static function BuscarLastEquipo($creadoPor){
        $BuscarLastEquipo = DB::Select("SELECT  max(id) as id FROM equipo WHERE user_id = $creadoPor");
        return $BuscarLastEquipo;
    }

    public static function BuscarSerialEquipo($Serial){
        $BuscarSerialEquipo = DB::Select("SELECT * FROM equipo WHERE serial LIKE '%$Serial%'");
        return $BuscarSerialEquipo;
    }

    public static function EvidenciaIE($idEquipo,$NombreFoto){
        $Evidencia = DB::Insert('INSERT INTO evidencia_inventario (nombre,id_equipo) VALUES (?, ?)', [$NombreFoto,$idEquipo]);
        return $Evidencia;
    }

    public static function BuscarHistorialE($IdEquipo){
        $historial = DB::Select("SELECT * FROM historial_inventario WHERE id_equipo = $IdEquipo");
        return $historial;
    }

    public static function HistorialE($idEquipo,$Comentario,$EstadoEquipo,$creadoPor){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema      = date('Y-m-d H:i');
        $fechaCreacion = date('Y-m-d H:i', strtotime($fecha_sistema));
        DB::insert('INSERT INTO historial_inventario (id_equipo,comentario,status_id,user_id,created)
                    VALUES (?,?,?,?,?)',
                    [$idEquipo,$Comentario,$EstadoEquipo,$creadoPor,$fechaCreacion]);
    }

    public static function ActualizarEquipo($TipoEquipo,$TipoIngreso,$EmpresaRenting,$FechaAdquisicion,$Serial,$Marca,$Procesador,$VelProcesador,$DiscoDuro,$MemoriaRam,$EstadoEquipo,$creadoPor,$IdEquipo){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema      = date('Y-m-d H:i');
        $fechaActualizacion = date('Y-m-d H:i', strtotime($fecha_sistema));
        if(($EstadoEquipo === 3) || ($EstadoEquipo === 4) || ($EstadoEquipo === 1)){
            DB::Update("UPDATE asignados SET id_equipo = null,estado_asignado = $EstadoEquipo,update_at = '$fechaActualizacion' WHERE id_equipo = $IdEquipo");
        }
        if(($TipoIngreso === 3) || ($TipoIngreso === 4) || ($TipoIngreso === 2)){
            $EmpRenting = 'SIN EMPRESA';
        }else{
            $EmpRenting = $EmpresaRenting;
        }
        $ActualizarEquipo = DB::Update("UPDATE equipo SET
                                        tipo_equipo = $TipoEquipo,
                                        tipo_ingreso = $TipoIngreso,
                                        emp_renting = '$EmpRenting',
                                        fecha_ingreso = '$FechaAdquisicion',
                                        serial = '$Serial',
                                        marca = '$Marca',
                                        procesador = '$Procesador',
                                        vel_procesador = '$VelProcesador',
                                        disco_duro = '$DiscoDuro',
                                        memoria_ram = '$MemoriaRam',
                                        estado_equipo = $EstadoEquipo,
                                        updated_at = '$fechaActualizacion',
                                        actualizado_por = $creadoPor
                                        WHERE id = $IdEquipo");
        return $ActualizarEquipo;
    }
    // PERIFERICOS
    public static function PerifericStock(){
        $Stock = DB::Select("SELECT COUNT(*) AS total FROM perifericos WHERE estado_periferico = 1");
        return $Stock;
    }

    public static function PerifericAsigned(){
        $Asigned = DB::Select("SELECT COUNT(*) AS total FROM perifericos WHERE estado_periferico = 2");
        return $Asigned;
    }

    public static function PerifericMaintenance(){
        $Maintenance = DB::Select("SELECT COUNT(*) AS total FROM perifericos WHERE estado_periferico = 3");
        return $Maintenance;
    }

    public static function PerifericObsolete(){
        $Obsolete = DB::Select("SELECT COUNT(*) AS total FROM perifericos WHERE estado_periferico = 4");
        return $Obsolete;
    }

    public static function ListarPerifericos(){
        $ListarPerifericos = DB::Select("SELECT * FROM perifericos");
        return $ListarPerifericos;
    }

    public static function ListarPerifericosID($idPeriferico){
        $ListarPerifericos = DB::Select("SELECT * FROM perifericos WHERE id = $idPeriferico");
        return $ListarPerifericos;
    }

    public static function ListarTipoPeriferico(){
        $ListarPerifericos = DB::Select("SELECT * FROM tipo_periferico");
        return $ListarPerifericos;
    }

    public static function ListarTipoPerifericoID($IdTipoPeriferico){
        $ListarPerifericos = DB::Select("SELECT * FROM tipo_periferico WHERE id = $IdTipoPeriferico");
        return $ListarPerifericos;
    }

    public static function EvidenciaPeriferico($idPeriferico){
        $EvidenciaEquipo = DB::Select("SELECT * FROM evidencia_inventario WHERE id_periferico = $idPeriferico");
        return $EvidenciaEquipo;
    }

    public static function BuscarHistorialP($idPeriferico){
        $historial = DB::Select("SELECT * FROM historial_inventario WHERE id_periferico = $idPeriferico");
        return $historial;
    }

    public static function BuscarLastPeriferico($creadoPor){
        $BuscarLastEquipo = DB::Select("SELECT max(id) as id FROM perifericos WHERE user_id = $creadoPor");
        return $BuscarLastEquipo;
    }

    public static function CrearPeriferico($TipoPeriferico,$TipoIngreso,$EmpresaRent,$FechaAdquisicion,$Serial,$Marca,$Tamano,$Estado,$creadoPor){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema  = date('Y-m-d H:i');
        $fechaCreacion  = date('Y-m-d H:i', strtotime($fecha_sistema));
        $CrearPeriferico = DB::Insert('INSERT INTO perifericos (tipo_periferico,tipo_ingreso,emp_renting,fecha_ingreso,serial,marca,tamano,estado_periferico,created_at,user_id)
                                        VALUES (?,?,?,?,?,?,?,?,?,?)',
                                        [$TipoPeriferico,$TipoIngreso,$EmpresaRent,$FechaAdquisicion,$Serial,$Marca,$Tamano,$Estado,$fechaCreacion,$creadoPor]);
        return $CrearPeriferico;
    }

    public static function EvidenciaIP($IdPeriferico,$NombreFoto){
        $Evidencia = DB::Insert('INSERT INTO evidencia_inventario (nombre,id_periferico) VALUES (?,?)', [$NombreFoto,$IdPeriferico]);
        return $Evidencia;
    }

    public static function InsertarPeriferico($TipoPeriferico,$Serial,$Marca,$Tamano,$Estado,$FechaAdquisicion,$idPeriferico){
        switch($TipoPeriferico){
            Case 1 :    DB::Insert('INSERT INTO pantalla (marca,serial,tamano_pulgadas,fecha_ingreso,estado_pantalla,id_periferico) VALUES (?,?,?,?,?,?)',
                                    [$Marca,$Serial,$Tamano,$FechaAdquisicion,$Estado,$idPeriferico]);
                        break;
            Case 2 :    DB::Insert('INSERT INTO mouse (marca,serial,fecha_ingreso,estado_mouse,id_periferico) VALUES (?,?,?,?,?)',
                                    [$Marca,$Serial,$FechaAdquisicion,$Estado,$idPeriferico]);
                        break;
            Case 3 :    DB::Insert('INSERT INTO teclado (marca,serial,fecha_ingreso,estado_teclado,id_periferico) VALUES (?,?,?,?,?)',
                                    [$Marca,$Serial,$FechaAdquisicion,$Estado,$idPeriferico]);
                        break;
            Case 4 :    DB::Insert('INSERT INTO guaya (marca,serial,fecha_ingreso,estado_guaya,id_periferico) VALUES (?,?,?,?,?)',
                                    [$Marca,$Serial,$FechaAdquisicion,$Estado,$idPeriferico]);
                        break;
            Case 5 :    DB::Insert('INSERT INTO cargador (marca,serial,fecha_ingreso,estado_cargador,id_periferico) VALUES (?,?,?,?,?)',
                                    [$Marca,$Serial,$FechaAdquisicion,$Estado,$idPeriferico]);
                        break;
        }
    }

    public static function ActualizarTPeriferico($TipoPeriferico,$Serial,$Marca,$Tamano,$Estado,$FechaAdquisicion,$IdPeriferico){
        switch($TipoPeriferico){
            Case 1 :    DB::Update("UPDATE pantalla SET marca = '$Marca',serial = '$Serial',tamano_pulgadas = '$Tamano',fecha_ingreso = '$FechaAdquisicion',estado_pantalla = $Estado WHERE id_periferico = $IdPeriferico");
                        break;
            Case 2 :    DB::Update("UPDATE mouse SET marca = '$Marca',serial = '$Serial',fecha_ingreso = '$FechaAdquisicion',estado_mouse = $Estado WHERE id_periferico = $IdPeriferico");
                        break;
            Case 3 :    DB::Update("UPDATE teclado SET marca = '$Marca',serial = '$Serial',fecha_ingreso = '$FechaAdquisicion',estado_teclado = $Estado WHERE id_periferico = $IdPeriferico");
                        break;
            Case 4 :    DB::Update("UPDATE guaya SET marca = '$Marca',serial = '$Serial',fecha_ingreso = '$FechaAdquisicion',estado_guaya = $Estado WHERE id_periferico = $IdPeriferico");
                        break;
            Case 5 :    DB::Update("UPDATE cargador SET marca = '$Marca',serial = '$Serial',fecha_ingreso = '$FechaAdquisicion',estado_cargador = $Estado WHERE id_periferico = $IdPeriferico");
                        break;
        }
    }

    public static function HistorialP($idPeriferico,$Comentario,$Estado,$creadoPor){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema      = date('Y-m-d H:i');
        $fechaCreacion = date('Y-m-d H:i', strtotime($fecha_sistema));
        DB::insert('INSERT INTO historial_inventario (id_periferico,comentario,status_id,user_id,created)
                    VALUES (?,?,?,?,?)',
                    [$idPeriferico,$Comentario,$Estado,$creadoPor,$fechaCreacion]);
    }

    public static function ActualizarPeriferico($TipoPeriferico,$TipoIngreso,$EmpresaRent,$FechaAdquisicion,$Serial,$Marca,$Tamano,$Estado,$creadoPor,$IdPeriferico){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema      = date('Y-m-d H:i');
        $fechaActualizacion = date('Y-m-d H:i', strtotime($fecha_sistema));
        if(($Estado === 3) || ($Estado === 4) || ($Estado === 1)){
            switch($TipoPeriferico){
                Case 1 :    $BuscarPeriferico = DB::Select("SELECT * FROM pantalla WHERE id_periferico = $IdPeriferico");
                            if($BuscarPeriferico){
                                foreach($BuscarPeriferico as $row){
                                    $IdTPeriferico = $row->id;
                                }
                                DB::Update("UPDATE asignados SET id_pantalla = null,estado_asignado = $Estado,update_at = '$fechaActualizacion' WHERE id_pantalla = $IdTPeriferico");
                            }
                            break;
                Case 2 :    $BuscarPeriferico = DB::Select("SELECT * FROM mouse WHERE id_periferico = $IdPeriferico");
                            if($BuscarPeriferico){
                                foreach($BuscarPeriferico as $row){
                                    $IdTPeriferico = $row->id;
                                }
                                DB::Update("UPDATE asignados SET id_mouse = null,estado_asignado = $Estado,update_at = '$fechaActualizacion' WHERE id_mouse = $IdTPeriferico");
                            }
                            break;
                Case 3 :    $BuscarPeriferico = DB::Select("SELECT * FROM teclado WHERE id_periferico = $IdPeriferico");
                            if($BuscarPeriferico){
                                foreach($BuscarPeriferico as $row){
                                    $IdTPeriferico = $row->id;
                                }
                                DB::Update("UPDATE asignados SET id_teclado = null,estado_asignado = $Estado,update_at = '$fechaActualizacion' WHERE id_teclado = $IdTPeriferico");
                            }
                            break;
                Case 4 :    $BuscarPeriferico = DB::Select("SELECT * FROM guaya WHERE id_periferico = $IdPeriferico");
                            if($BuscarPeriferico){
                                foreach($BuscarPeriferico as $row){
                                    $IdTPeriferico = $row->id;
                                }
                                DB::Update("UPDATE asignados SET id_guaya = null,estado_asignado = $Estado,update_at = '$fechaActualizacion' WHERE id_guaya = $IdTPeriferico");
                            }
                            break;
                Case 5 :    $BuscarPeriferico = DB::Select("SELECT * FROM cargador WHERE id_periferico = $IdPeriferico");
                            if($BuscarPeriferico){
                                foreach($BuscarPeriferico as $row){
                                    $IdTPeriferico = $row->id;
                                }
                                DB::Update("UPDATE asignados SET id_cargador = null,estado_asignado = $Estado,update_at = '$fechaActualizacion' WHERE id_cargador = $IdTPeriferico");
                            }
                            break;
            }
        }
        if(($TipoIngreso === 3) || ($TipoIngreso === 4) || ($TipoIngreso === 2)){
            $EmpRenting = 'SIN EMPRESA';
        }else{
            $EmpRenting = $EmpresaRent;
        }
        $ActualizarPeriferico    = DB::Update("UPDATE perifericos SET
                                                        tipo_periferico     = $TipoPeriferico,
                                                        tipo_ingreso        = $TipoIngreso,
                                                        emp_renting         = '$EmpRenting',
                                                        fecha_ingreso       = '$FechaAdquisicion',
                                                        serial              = '$Serial',
                                                        marca               = '$Marca',
                                                        tamano              = '$Tamano',
                                                        estado_periferico   = $Estado,
                                                        updated_at          = '$fechaActualizacion',
                                                        actualizado_por     = $creadoPor
                                                        WHERE id = $IdPeriferico");
        return $ActualizarPeriferico;
    }

    public static function BuscarLastConsumible($creadoPor){
        $BuscarLastEquipo = DB::Select("SELECT max(id) as id FROM consumible WHERE user_id = $creadoPor");
        return $BuscarLastEquipo;
    }

    public static function BuscarSerialPeriferico($Serial){
        $BuscarSerialEquipo = DB::Select("SELECT * FROM perifericos WHERE serial LIKE '%$Serial%'");
        return $BuscarSerialEquipo;
    }

    public static function BuscarMouseId($IdMouse){
        $BuscarMouse = DB::Select("SELECT * FROM perifericos WHERE id = $IdMouse");
        return $BuscarMouse;
    }

    public static function BuscarPantallaId($IdPantalla){
        $BuscarPantalla = DB::Select("SELECT * FROM perifericos WHERE id = $IdPantalla");
        return $BuscarPantalla;
    }

    public static function BuscarTecladoId($IdTeclado){
        $BuscarTeclado = DB::Select("SELECT * FROM perifericos WHERE id = $IdTeclado");
        return $BuscarTeclado;
    }

    public static function BuscarCargadorId($IdCargador){
        $BuscarCargador = DB::Select("SELECT * FROM perifericos WHERE id = $IdCargador");
        return $BuscarCargador;
    }

    public static function BuscarGuayaId($IdGuaya){
        $BuscarGuaya = DB::Select("SELECT * FROM perifericos WHERE id = $IdGuaya");
        return $BuscarGuaya;
    }

    public static function BuscarTipoGuayaId($IdTipoGuaya){
        $BuscarGuaya = DB::Select("SELECT * FROM tipo_guaya WHERE id = $IdTipoGuaya");
        return $BuscarGuaya;
    }

    public static function ListarMouseActivo(){
        $ListarMouse = DB::Select("SELECT * FROM perifericos WHERE tipo_periferico = 2 AND estado_periferico = 1");
        return $ListarMouse;
    }

    public static function ListarMouseActivoUpd(){
        $ListarMouse = DB::Select("SELECT * FROM perifericos WHERE tipo_periferico = 2 AND estado_periferico IN (1,2)");
        return $ListarMouse;
    }

    public static function ListarPantallaActivo(){
        $ListarPantalla = DB::Select("SELECT * FROM perifericos WHERE tipo_periferico = 1 AND estado_periferico = 1");
        return $ListarPantalla;
    }

    public static function ListarPantallaActivoUpd(){
        $ListarPantalla = DB::Select("SELECT * FROM perifericos WHERE tipo_periferico = 1 AND estado_periferico IN (1,2)");
        return $ListarPantalla;
    }

    public static function ListarTecladoActivo(){
        $ListarTeclado = DB::Select("SELECT * FROM perifericos WHERE tipo_periferico = 3 AND estado_periferico = 1");
        return $ListarTeclado;
    }

    public static function ListarTecladoActivoUpd(){
        $ListarTeclado = DB::Select("SELECT * FROM perifericos WHERE tipo_periferico = 3 AND estado_periferico IN (1,2)");
        return $ListarTeclado;
    }

    public static function ListarCargadorActivo(){
        $ListarCargador = DB::Select("SELECT * FROM perifericos WHERE tipo_periferico = 5 AND estado_periferico = 1");
        return $ListarCargador;
    }

    public static function ListarCargadorActivoUpd(){
        $ListarCargador = DB::Select("SELECT * FROM perifericos WHERE tipo_periferico = 5 AND estado_periferico IN (1,2)");
        return $ListarCargador;
    }

    public static function ListarGuayaActivo(){
        $ListarGuaya = DB::Select("SELECT * FROM perifericos WHERE tipo_periferico = 4 AND estado_periferico = 1");
        return $ListarGuaya;
    }

    public static function ListarGuayaActivoUpd(){
        $ListarGuaya = DB::Select("SELECT * FROM perifericos WHERE tipo_periferico = 4 AND estado_periferico IN (1,2)");
        return $ListarGuaya;
    }

    // CONSUMIBLES
    public static function ConsumibleStock(){
        $Stock = DB::Select("SELECT COUNT(*) AS total FROM consumible WHERE estado_consumible = 1");
        return $Stock;
    }

    public static function ConsumibleAsigned(){
        $Asigned = DB::Select("SELECT COUNT(*) AS total FROM consumible WHERE estado_consumible = 2");
        return $Asigned;
    }

    public static function ConsumibleMaintenance(){
        $Maintenance = DB::Select("SELECT COUNT(*) AS total FROM consumible WHERE estado_consumible = 3");
        return $Maintenance;
    }

    public static function ConsumibleObsolete(){
        $Obsolete = DB::Select("SELECT COUNT(*) AS total FROM consumible WHERE estado_consumible = 4");
        return $Obsolete;
    }

    public static function ListarConsumibles(){
        $ListarConsumibles = DB::Select("SELECT * FROM consumible");
        return $ListarConsumibles;
    }

    public static function ListarConsumiblesUpd(){
        $ListarConsumibles = DB::Select("SELECT * FROM consumible WHERE estado_consumible IN (1,2)");
        return $ListarConsumibles;
    }

    public static function ListarConsumiblesID($idConsumible){
        $ListarConsumibles = DB::Select("SELECT * FROM consumible WHERE id = $idConsumible");
        return $ListarConsumibles;
    }

    public static function ListarTipoConsumible(){
        $ListarConsumibles = DB::Select("SELECT * FROM tipo_consumible");
        return $ListarConsumibles;
    }

    public static function ListarTipoConsumibleID($IdTipoConsumible){
        $ListarConsumibles = DB::Select("SELECT * FROM tipo_consumible WHERE id = $IdTipoConsumible");
        return $ListarConsumibles;
    }

    public static function BuscarHistorialC($idConsumible){
        $historial = DB::Select("SELECT * FROM historial_inventario WHERE id_consumible = $idConsumible");
        return $historial;
    }

    public static function EvidenciaConsumible($idConsumible){
        $EvidenciaEquipo = DB::Select("SELECT * FROM evidencia_inventario WHERE id_consumible = $idConsumible");
        return $EvidenciaEquipo;
    }

    public static function CrearConsumible($TipoConsumible,$TipoIngreso,$EmpresaRent,$FechaAdquisicion,$Serial,$Marca,$Modelo,$CompaRef,$CompaMod,$Estado,$creadoPor){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema      = date('Y-m-d H:i');
        $fechaCreacion      = date('Y-m-d H:i', strtotime($fecha_sistema));
        $CrearConsumible    = DB::insert('INSERT INTO consumible (tipo_consumible,tipo_ingreso,emp_renting,fecha_ingreso,serial,marca,modelo,compa_ref,compa_mod,estado_consumible,created_at,user_id)
                                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?)',
                                        [$TipoConsumible,$TipoIngreso,$EmpresaRent,$FechaAdquisicion,$Serial,$Marca,$Modelo,$CompaRef,$CompaMod,$Estado,$fechaCreacion,$creadoPor]);
        return $CrearConsumible;
    }

    public static function HistorialC($idConsumible,$Comentario,$Estado,$creadoPor){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema      = date('Y-m-d H:i');
        $fechaCreacion = date('Y-m-d H:i', strtotime($fecha_sistema));
        DB::insert('INSERT INTO historial_inventario (id_consumible,comentario,status_id,user_id,created)
                    VALUES (?,?,?,?,?)',
                    [$idConsumible,$Comentario,$Estado,$creadoPor,$fechaCreacion]);
    }

    public static function EvidenciaIC($IdConsumible,$NombreFoto){
        $Evidencia = DB::Insert('INSERT INTO evidencia_inventario (nombre,id_consumible) VALUES (?,?)', [$NombreFoto,$IdConsumible]);
        return $Evidencia;
    }

    public static function ActualizarConsumible($TipoConsumible,$TipoIngreso,$EmpresaRent,$FechaAdquisicion,$Serial,$Marca,$Modelo,$CompaRef,$CompaMod,$Estado,$creadoPor,$IdConsumible){
        if(($TipoIngreso === 3) || ($TipoIngreso === 4) || ($TipoIngreso === 2)){
            $EmpRenting = 'SIN EMPRESA';
        }else{
            $EmpRenting = $EmpresaRent;
        }
        date_default_timezone_set('America/Bogota');
        $fecha_sistema      = date('Y-m-d H:i');
        $fechaActualizacion = date('Y-m-d H:i', strtotime($fecha_sistema));
        // if(($Estado === 3) || ($Estado === 4) || ($Estado === 1)){
        //     DB::Update("UPDATE asignados SET id_consumible = null,update_at = '$fechaActualizacion' WHERE id_consumible = $IdConsumible");
        // }
        $ActualizarConsumible = DB::Update("UPDATE consumible SET
                                            tipo_consumible = $TipoConsumible,
                                            tipo_ingreso = $TipoIngreso,
                                            emp_renting = '$EmpRenting',
                                            fecha_ingreso = '$FechaAdquisicion',
                                            serial = '$Serial',
                                            marca = '$Marca',
                                            modelo = '$Modelo',
                                            compa_ref = '$CompaRef',
                                            compa_mod = '$CompaMod',
                                            estado_consumible = $Estado,
                                            updated_at = '$fechaActualizacion',
                                            actualizado_por = $creadoPor
                                            WHERE id = $IdConsumible");
        return $ActualizarConsumible;
    }

    public static function BuscarSerialConsumible($Serial){
        $BuscarSerialEquipo = DB::Select("SELECT * FROM consumible WHERE serial LIKE '%$Serial%'");
        return $BuscarSerialEquipo;
    }

    public static function EstadoConsumible($IdConsumible){
        $EstadoConsumible = DB::Select("SELECT * FROM consumible WHERE id = $IdConsumible");
        return $EstadoConsumible;
    }

    // IMPRESORAS
    public static function ImpresoraStock(){
        $Stock = DB::Select("SELECT COUNT(*) AS total FROM impresoras WHERE estado_impresora = 1");
        return $Stock;
    }

    public static function ImpresoraAsigned(){
        $Asigned = DB::Select("SELECT COUNT(*) AS total FROM impresoras WHERE estado_impresora = 2");
        return $Asigned;
    }

    public static function ImpresoraMaintenance(){
        $Maintenance = DB::Select("SELECT COUNT(*) AS total FROM impresoras WHERE estado_impresora = 3");
        return $Maintenance;
    }

    public static function ImpresoraObsolete(){
        $Obsolete = DB::Select("SELECT COUNT(*) AS total FROM impresoras WHERE estado_impresora = 4");
        return $Obsolete;
    }

    public static function ListarImpresoras(){
        $ListarImpresoras = DB::Select("SELECT * FROM impresoras");
        return $ListarImpresoras;
    }

    public static function ListarImpresorasID($idImpresora){
        $ListarImpresoras = DB::Select("SELECT * FROM impresoras WHERE id = $idImpresora");
        return $ListarImpresoras;
    }

    public static function ListarTipoImpresora(){
        $ListarImpresoras = DB::Select("SELECT * FROM tipo_impresora");
        return $ListarImpresoras;
    }

    public static function ListarTipoImpresoraID($IdTipoImpresora){
        $ListarImpresoras = DB::Select("SELECT * FROM tipo_impresora WHERE id = $IdTipoImpresora");
        return $ListarImpresoras;
    }

    public static function ListarConsumiblesPrint(){
        $ListarConsumibles = DB::Select("SELECT * FROM consumible WHERE estado_consumible = 1");
        return $ListarConsumibles;
    }

    public static function EvidenciaImpresora($idImpresora){
        $EvidenciaEquipo = DB::Select("SELECT * FROM evidencia_inventario WHERE id_impresora = $idImpresora");
        return $EvidenciaEquipo;
    }

    public static function BuscarHistorialI($idImpresora){
        $historial = DB::Select("SELECT * FROM historial_inventario WHERE id_impresora = $idImpresora");
        return $historial;
    }

    public static function BuscarLastImpresora($creadoPor){
        $BuscarLastEquipo = DB::Select("SELECT max(id) as id FROM impresoras WHERE user_id = $creadoPor");
        return $BuscarLastEquipo;
    }

    public static function CrearImpresora($TipoImpresora,$TipoIngreso,$EmpresaRent,$FechaAdquisicion,$Serial,$Marca,$Ip,$IdConsumible,$Estado,$creadoPor){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema  = date('Y-m-d H:i');
        $fechaCreacion  = date('Y-m-d H:i', strtotime($fecha_sistema));
        $CrearImpresora = DB::insert('INSERT INTO impresoras (tipo_impresora,tipo_ingreso,emp_renting,fecha_ingreso,serial,marca,IP,id_consumible,estado_impresora,created_at,user_id)
                                        VALUES (?,?,?,?,?,?,?,?,?,?,?)',
                                        [$TipoImpresora,$TipoIngreso,$EmpresaRent,$FechaAdquisicion,$Serial,$Marca,$Ip,$IdConsumible,$Estado,$fechaCreacion,$creadoPor]);
        DB::update("UPDATE consumible SET estado_consumible = 2 WHERE ID = $IdConsumible");
        return $CrearImpresora;
    }

    public static function BuscarSerialImpresora($Serial){
        $BuscarSerialEquipo = DB::Select("SELECT * FROM impresoras WHERE serial LIKE '%$Serial%'");
        return $BuscarSerialEquipo;
    }

    public static function HistorialI($idImpresora,$Comentario,$Estado,$creadoPor){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema      = date('Y-m-d H:i');
        $fechaCreacion = date('Y-m-d H:i', strtotime($fecha_sistema));
        DB::insert('INSERT INTO historial_inventario (id_impresora,comentario,status_id,user_id,created)
                    VALUES (?,?,?,?,?)',
                    [$idImpresora,$Comentario,$Estado,$creadoPor,$fechaCreacion]);
    }

    public static function EvidenciaI($IdImpresora,$NombreFoto){
        $Evidencia = DB::Insert('INSERT INTO evidencia_inventario (nombre,id_impresora) VALUES (?,?)', [$NombreFoto,$IdImpresora]);
        return $Evidencia;
    }

    public static function ActualizarImpresora($TipoImpresora,$TipoIngreso,$EmpresaRent,$FechaAdquisicion,$Serial,$Marca,$Ip,$IdConsumible,$Estado,$creadoPor,$Comentario,$IdImpresora){
        if(($TipoIngreso === 3) || ($TipoIngreso === 4) || ($TipoIngreso === 2)){
            $EmpRenting = 'SIN EMPRESA';
            $BuscarConsumible = DB::Select("SELECT * FROM impresoras WHERE id_consumible = $IdConsumible");
            if($BuscarConsumible){
                DB::update("UPDATE consumible SET estado_consumible = 1 WHERE ID = $IdConsumible");
            }else{
                $BuscarConsumible = DB::Select("SELECT * FROM impresoras WHERE id = $IdImpresora");
                foreach($BuscarConsumible as $row){
                    $consumible = (int)$row->id_consumible;
                    DB::update("UPDATE consumible SET estado_consumible = 1 WHERE ID = $consumible");
                }
                DB::update("UPDATE consumible SET estado_consumible = 2 WHERE ID = $IdConsumible");
            }

        }else{
            $EmpRenting = $EmpresaRent;
        }
        date_default_timezone_set('America/Bogota');
        $fecha_sistema      = date('Y-m-d H:i');
        $fechaActualizacion = date('Y-m-d H:i', strtotime($fecha_sistema));
        $ActualizarImpresora = DB::Update("UPDATE impresoras SET
                                            tipo_impresora = $TipoImpresora,
                                            tipo_ingreso = $TipoIngreso,
                                            emp_renting = '$EmpRenting',
                                            fecha_ingreso = '$FechaAdquisicion',
                                            serial = '$Serial',
                                            marca = '$Marca',
                                            IP = '$Ip',
                                            id_consumible = $IdConsumible,
                                            estado_impresora = $Estado,
                                            updated_at = '$fechaActualizacion ',
                                            actualizado_por = $creadoPor
                                            WHERE id = $IdImpresora");
        return $ActualizarImpresora;
    }

    public static function BuscarConsumibleID($IdConsumible,$IdImpresora){
        $BuscarConsumibleID = DB::Select("SELECT * FROM impresoras WHERE id = $IdImpresora AND id_consumible = $IdConsumible");
        return $BuscarConsumibleID;
    }

    // ASIGNACIONES
    public static function RegistrarAsignadoEM($TipoEquipo,$idEquipoMovil,$Area,$NombreAsignado,$EstadoEquipo,$creadoPor){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema  = date('Y-m-d H:i');
        $fechaCreacion  = date('Y-m-d H:i', strtotime($fecha_sistema));
        $BuscarAsignado = DB::Select("SELECT * FROM asignados WHERE nombre_usuario LIKE '%$NombreAsignado%' AND id IS NOT NULL");
        foreach($BuscarAsignado as $row){
            $IdAsignado = (int)$row->id;
        }
        $TotalBusqueda  = (int)count($BuscarAsignado);
        if($TotalBusqueda === 0){
            DB::Insert('INSERT INTO asignados (tipo_equipo,id_movil,area,nombre_usuario,estado_asignado,created_at,user_id,id_ticket)
                        VALUES (?,?,?,?,?,?,?,?)',
                        [$TipoEquipo,$idEquipoMovil,$Area,$NombreAsignado,$EstadoEquipo,$fechaCreacion,$creadoPor,0]);
        }else{
            if((int)$EstadoEquipo === 1){
                DB::Update("UPDATE asignados SET id_movil = null,update_at = '$fechaCreacion' WHERE id = $IdAsignado");
            }else{
                DB::Update("UPDATE asignados SET id_movil = $idEquipoMovil, estado_asignado = $EstadoEquipo, update_at = '$fechaCreacion' WHERE id = $IdAsignado");
            }
        }
    }

    public static function RegistrarAsignadoLM($idEquipoMovil,$Area,$NombreAsignado,$EstadoEquipo,$creadoPor){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema  = date('Y-m-d H:i');
        $fechaCreacion  = date('Y-m-d H:i', strtotime($fecha_sistema));
        $BuscarAsignado = DB::Select("SELECT * FROM asignados WHERE nombre_usuario LIKE '%$NombreAsignado%' AND id IS NOT NULL");
        foreach($BuscarAsignado as $row){
            $IdAsignado = (int)$row->id;
        }
        $TotalBusqueda  = (int)count($BuscarAsignado);
        if($TotalBusqueda === 0){
            DB::Insert('INSERT INTO asignados (id_linea,area,nombre_usuario,estado_asignado,created_at,user_id,id_ticket)
                        VALUES (?,?,?,?,?,?,?)',
                        [$idEquipoMovil,$Area,$NombreAsignado,$EstadoEquipo,$fechaCreacion,$creadoPor,0]);
        }else{
            if((int)$EstadoEquipo === 1){
                DB::Update("UPDATE asignados SET id_linea = null,update_at = '$fechaCreacion' WHERE id = $IdAsignado");
            }else{
                DB::Update("UPDATE asignados SET id_linea = $idEquipoMovil, estado_asignado = $EstadoEquipo, update_at = '$fechaCreacion' WHERE id = $IdAsignado");
            }
        }
    }

    public static function ListarAsignados(){
        $ListarAsignados = DB::Select("SELECT * FROM asignados WHERE tipo_equipo IN (1,2)");
        return $ListarAsignados;
    }

    public static function EvidenciaAsignado($idAsignado){
        $EvidenciaEquipo = DB::Select("SELECT * FROM evidencia_inventario WHERE id_asignado = $idAsignado");
        return $EvidenciaEquipo;
    }

    public static function BuscarHistorialA($idAsignado){
        $historial = DB::Select("SELECT * FROM historial_inventario WHERE id_asignado = $idAsignado");
        return $historial;
    }

    public static function IngresarAsignado($TipoEquipo,$IdEquipo,$Mouse,$Pantalla,$Teclado,$Cargador,$TipoGuaya,$IdGuaya,$CodeGuaya,
                                            $Sede,$Area,$NombreAsignado,$Cargo,$Cedula,$Telefono,$Correo,$Ticket,$FechaAsignacion,$EstadoAsignado,$creadoPor){

        date_default_timezone_set('America/Bogota');
        $fecha_sistema  = date('Y-m-d H:i');
        $fechaCreacion  = date('Y-m-d H:i', strtotime($fecha_sistema));

        $IngresarAsignado = DB::Insert('INSERT INTO asignados (tipo_equipo,id_equipo,id_mouse,id_pantalla,id_teclado,id_cargador,tipo_guaya,id_guaya,code_guaya,sede,area,nombre_usuario,cargo_usuario,id_usuario,tel_usuario,correo,id_ticket,fecha_asignacion,estado_asignado,created_at,user_id)
                                        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
                                        [$TipoEquipo,$IdEquipo,$Mouse,$Pantalla,$Teclado,$Cargador,$TipoGuaya,$IdGuaya,$CodeGuaya,$Sede,$Area,$NombreAsignado,$Cargo,$Cedula,$Telefono,$Correo,$Ticket,$FechaAsignacion,$EstadoAsignado,$fechaCreacion,$creadoPor]);
        If($IngresarAsignado){
            DB::Update("UPDATE equipo SET estado_equipo = 2 WHERE id = $IdEquipo");
            if($Mouse != null){
                DB::Update("UPDATE mouse SET estado_mouse = 2 WHERE id_periferico = $Mouse");
                DB::Update("UPDATE perifericos SET estado_periferico = 2 WHERE id = $Mouse");
            }
            if($Pantalla != null){
                DB::Update("UPDATE pantalla SET estado_pantalla = 2 WHERE id_periferico = $Pantalla");
                DB::Update("UPDATE perifericos SET estado_periferico = 2 WHERE id = $Pantalla");
            }
            if($Teclado != null){
                DB::Update("UPDATE teclado SET estado_teclado = 2 WHERE id_periferico = $Teclado");
                DB::Update("UPDATE perifericos SET estado_periferico = 2 WHERE id = $Teclado");
            }
            if($Cargador != null){
                DB::Update("UPDATE cargador SET estado_cargador = 2 WHERE id_periferico = $Cargador");
                DB::Update("UPDATE perifericos SET estado_periferico = 2 WHERE id = $Cargador");
            }
            if($IdGuaya != null){
                DB::Update("UPDATE guaya SET estado_guaya = 2 WHERE id_periferico = $IdGuaya");
                DB::Update("UPDATE perifericos SET estado_periferico = 2 WHERE id = $IdGuaya");
            }
        }
        return $IngresarAsignado;
    }

    public static function BuscarLastAsignado($creadoPor){
        $buscarUltimo = DB::Select("SELECT max(id) as id FROM asignados WHERE user_id = $creadoPor");
        return $buscarUltimo;
    }

    public static function HistorialA($idAsignado,$Comentario,$Estado,$creadoPor){
        date_default_timezone_set('America/Bogota');
        $fecha_sistema      = date('Y-m-d H:i');
        $fechaCreacion = date('Y-m-d H:i', strtotime($fecha_sistema));
        DB::insert('INSERT INTO historial_inventario (id_asignado,comentario,status_id,user_id,created)
                    VALUES (?,?,?,?,?)',
                    [$idAsignado,$Comentario,$Estado,$creadoPor,$fechaCreacion]);
    }

    public static function ActualizarAsignado($TipoEquipo,$IdEquipo,$Mouse,$Pantalla,$Teclado,$Cargador,$TipoGuaya,$IdGuaya,$CodeGuaya,$Sede,$Area,
                            $NombreAsignado,$Cargo,$Cedula,$Telefono,$Correo,$Ticket,$FechaAsignacion,$EstadoAsignado,$creadoPor,$IdAsignado){

        date_default_timezone_set('America/Bogota');
        $fecha_sistema          = date('Y-m-d H:i');
        $fechaActualizacion     = date('Y-m-d H:i', strtotime($fecha_sistema));

        $ActualizarAsignacion = DB::Update("UPDATE asignados SET
                                            tipo_equipo = $TipoEquipo,
                                            id_equipo = $IdEquipo,
                                            id_mouse = $Mouse,
                                            id_pantalla = $Pantalla,
                                            id_teclado = $Teclado,
                                            id_cargador = $Cargador,
                                            tipo_guaya = $TipoGuaya,
                                            id_guaya = $IdGuaya,
                                            code_guaya = '$CodeGuaya',
                                            sede = $Sede,
                                            area = '$Area',
                                            nombre_usuario = '$NombreAsignado',
                                            cargo_usuario = '$Cargo',
                                            id_usuario = '$Cedula',
                                            tel_usuario = '$Telefono',
                                            correo = '$Correo',
                                            id_ticket = $Ticket,
                                            fecha_asignacion = '$FechaAsignacion',
                                            estado_asignado = $EstadoAsignado,
                                            update_at = '$fechaActualizacion',
                                            actualizado_por = $creadoPor
                                            WHERE id = $IdAsignado");
        If($ActualizarAsignacion){
            DB::Update("UPDATE equipo SET estado_equipo = $EstadoAsignado WHERE id = $IdEquipo");
            if($Mouse != null){
                DB::Update("UPDATE mouse SET estado_mouse = $EstadoAsignado WHERE id_periferico = $Mouse");
                DB::Update("UPDATE perifericos SET estado_periferico = $EstadoAsignado WHERE id = $Mouse");
            }
            if($Pantalla != null){
                DB::Update("UPDATE pantalla SET estado_pantalla = $EstadoAsignado WHERE id_periferico = $Pantalla");
                DB::Update("UPDATE perifericos SET estado_periferico = $EstadoAsignado WHERE id = $Pantalla");
            }
            if($Teclado != null){
                DB::Update("UPDATE teclado SET estado_teclado = $EstadoAsignado WHERE id_periferico = $Teclado");
                DB::Update("UPDATE perifericos SET estado_periferico = $EstadoAsignado WHERE id = $Teclado");
            }
            if($Cargador != null){
                DB::Update("UPDATE cargador SET estado_cargador = $EstadoAsignado WHERE id_periferico = $Cargador");
                DB::Update("UPDATE perifericos SET estado_periferico = $EstadoAsignado WHERE id = $Cargador");
            }
            if($IdGuaya != null){
                DB::Update("UPDATE guaya SET estado_guaya = $EstadoAsignado WHERE id_periferico = $IdGuaya");
                DB::Update("UPDATE perifericos SET estado_periferico = $EstadoAsignado WHERE id = $IdGuaya");
            }
        }
        return $ActualizarAsignacion;
    }

}
