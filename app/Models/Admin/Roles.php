<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Roles extends Model
{
    protected $table = "rol";
    public $timestamps = false;
    protected $fillable = array('id','nombre');

    public static function ListarRoles(){
        $roles = DB::Select("SELECT * FROM rol WHERE activo = 1");
        return $roles;
    }

    public static function ListarRolesAdmin(){
        $roles = DB::Select("SELECT * FROM rol");
        return $roles;
    }

    public static function ListarCategorias(){
        $categorias = DB::Select("SELECT * FROM category WHERE activo = 1");
        return $categorias;
    }

    public static function ListarCategoriasAdmin(){
        $categorias = DB::Select("SELECT * FROM category");
        return $categorias;
    }

    public static function BuscarCategoriaID($IdCategoria){
        $categorias = DB::Select("SELECT * FROM category WHERE id = $IdCategoria");
        return $categorias;
    }

    public static function BuscarNombreRol($nombreRol){
        $consultaRol = DB::Select("SELECT * FROM rol WHERE name like '%$nombreRol%'");
        return $consultaRol;
    }

    public static function BuscarIDRol($id){
        $consultaRol = DB::Select("SELECT * FROM rol WHERE rol_id = $id");
        return $consultaRol;
    }

    public static function CrearRol($nombreRol){
        $crearRol = DB::insert('INSERT INTO rol (name,activo) values (?,?)', [$nombreRol,1]);
        return $crearRol;
    }

    public static function ActualizarRol($id,$nombreRol,$idactivo){
        $actualizarRol = DB::Update('UPDATE rol SET name = ?,activo = ? WHERE rol_id = ?', [$nombreRol,$idactivo,$id]);
        return $actualizarRol;
    }

    public static function ActualizarRolActivo($id,$idactivo){
        $actualizarRol = DB::Update('UPDATE rol SET activo = ? WHERE rol_id = ?', [$idactivo,$id]);
        return $actualizarRol;
    }

    public static function BuscarNombreCategoria($nombreCategoria){
        $consultaCategoria = DB::Select("SELECT * FROM category WHERE name like '%$nombreCategoria%'");
        // dd("SELECT * FROM categoria WHERE nombre like '%$nombreCategoria%'");
        return $consultaCategoria;
    }

    public static function CrearCategoria($nombreCategoria){
        $crearCategoria = DB::insert('INSERT INTO category (name,activo) values (?,?)', [$nombreCategoria,1]);
        return $crearCategoria;
    }

    public static function ActualizarCategoria($id,$nombreCategoria,$idactivo){
        $actualizarCategoria = DB::Update("UPDATE category SET name = '$nombreCategoria', activo = $idactivo WHERE id = $id");
        return $actualizarCategoria;
    }
}
