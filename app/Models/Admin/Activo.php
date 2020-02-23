<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Activo extends Model
{
    protected $table = "activo";
    public $timestamps = false;

    public static function Activo(){
        $Zonas = DB::Select("SELECT * FROM activo");
        return $Zonas;
    }
}
