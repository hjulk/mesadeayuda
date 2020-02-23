<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Sedes extends Model
{
    protected $table = "sedes";
    public $timestamps = false;

    public static function Sedes(){
        $Sedes = DB::Select("SELECT * FROM project ORDER BY name");
        return $Sedes;
    }

    public static function SedesA(){
        $Sedes = DB::Select("SELECT * FROM project WHERE activo = 1 ORDER BY name");
        return $Sedes;
    }

    public static function Areas(){
        $Sedes = DB::Select("SELECT * FROM areas ORDER BY name");
        return $Sedes;
    }

    public static function BuscarSedeID($idsede){
        $consultaSedeId = DB::Select("SELECT * FROM project WHERE id = $idsede");
        return $consultaSedeId;
    }

    public static function BuscarAreaIdSede($idsede){
        $BuscarAreaIdSede = DB::Select("SELECT * FROM areas WHERE project_id = $idsede ORDER BY name");
        return $BuscarAreaIdSede;
    }

    public static function BuscarSede($Sede){
        $consultaSede = DB::Select("SELECT * FROM project WHERE name LIKE '%$Sede%'");
        return $consultaSede;
    }

    public static function BuscarArea($Area,$Sede){
        $consultaArea = DB::Select("SELECT * FROM areas WHERE name LIKE '%$Area%' AND project_id = $Sede");
        return $consultaArea;
    }

    public static function BuscarAreaId($Area){
        $consultaArea = DB::Select("SELECT * FROM areas WHERE id = $Area");
        return $consultaArea;
    }

    public static function CrearSede($Sede,$Descripcion){
        $CrearSedes = DB::Insert('INSERT INTO project (name,description,activo)
                                    VALUES (?,?,?)', [$Sede,$Descripcion,1]);
        return $CrearSedes;
    }

    public static function ActualizarSede($id,$Sede,$Descripcion,$idActivo){
        $ActualizarSede = DB::Update("UPDATE project SET
                                            name = '$Sede',
                                            description = '$Descripcion',
                                            activo = $idActivo
                                            WHERE id = $id");
        return $ActualizarSede;
    }

    public static function CrearArea($Area,$Sede){
        $CrearArea = DB::Insert('INSERT INTO areas (name,project_id,activo)
                                    VALUES (?,?,?)', [$Area,$Sede,1]);
        return $CrearArea;
    }

    public static function ActualizarArea($id,$Area,$Sede,$idActivo){
        $ActualizarArea = DB::Update("UPDATE areas SET name = '$Area', project_id = $Sede, activo = $idActivo WHERE id = $id");
        return $ActualizarArea;
    }
}
