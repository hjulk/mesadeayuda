<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Funciones extends Controller{
    public static function BuscarURL($Administrador){
        if($Administrador === 1){
            return 'admin';
        }else{
            return 'user';
        }
    }

    public static function eliminar_tildes_texto($nombrearchivo){

        $cadena = $nombrearchivo;
        $cadena = str_replace(
            array('ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä','Ã¡'),
            array('a', 'a', 'a', 'A', 'A', 'A', 'A','á'),
            $cadena
        );

        $cadena = str_replace(
            array('ë', 'ê', 'É', 'È', 'Ê', 'Ë','Ã©'),
            array('e', 'e', 'E', 'E', 'E', 'E','é'),
            $cadena );

        $cadena = str_replace(
            array('ï', 'î', 'Í', 'Ì', 'Ï', 'Î','Ã­'),
            array('i', 'i', 'I', 'I', 'I', 'I','í'),
            $cadena );

        $cadena = str_replace(
            array('ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô','Ã³','Ã“'),
            array('o', 'o', 'O', 'O', 'O', 'O','ó','Ó'),
            $cadena );

        $cadena = str_replace(
            array('ü', 'û', 'Ú', 'Ù', 'Û', 'Ü','Ãº'),
            array('u', 'u', 'U', 'U', 'U', 'U','ú'),
            $cadena );

        $cadena = str_replace(
            array('ç', 'Ç','Ã±','Ã‘'),
            array('c', 'C','ñ','Ñ'),
            $cadena
        );

        $cadena = str_replace(
            array("'", '‘'),
            array(' ', ' '),
            $cadena
        );

        return $cadena;
    }

    public static function editar_correo($nombrearchivo){

        $cadena = $nombrearchivo;
        $cadena = str_replace(
            array(' ',','),
            array('',';'),
            $cadena
        );

        return $cadena;
    }

    public static function eliminar_tildes($nombrearchivo){

        $cadena = $nombrearchivo;
        $cadena = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä','Ã¡'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A','a'),
            $cadena
        );

        $cadena = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë','Ã©'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E','e'),
            $cadena );

        $cadena = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î','Ã­'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I','i'),
            $cadena );

        $cadena = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô','Ã³'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O','o'),
            $cadena );

        $cadena = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü','Ãº'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U','u'),
            $cadena );

        $cadena = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C'),
            $cadena
        );

        $cadena = str_replace(
            array(' ', '-'),
            array('_', '_'),
            $cadena
        );

        $cadena = str_replace(
            array("'", '‘','a€“'),
            array(' ', ' ','-'),
            $cadena
        );

        return $cadena;
    }
}
