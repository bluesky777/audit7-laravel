<?php namespace App\Http\Models;


use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Database\Eloquent\SoftDeletes;

use DB;
use Request;


class DatosDescarga
{

    public static function distritos($tipo_usu, $asociacion_id=false)
    {
        if ($tipo_usu == 'Auditor' || $tipo_usu == 'Admin' || $tipo_usu == 'Tesorero asociación') {
            if ($asociacion_id) {
                $consulta = 'SELECT d.* FROM au_distritos d
                    WHERE d.asociacion_id=? and d.deleted_at is null;';
                return DB::select($consulta, [$asociacion_id]);
            }else{
                $consulta = 'SELECT * FROM au_distritos d
                    WHERE d.deleted_at is null;';
                return DB::select($consulta);
            }
        }

        return [];
    }


    public static function iglesias($tipo_usu, $asociacion_id=false)
    {
        if ($tipo_usu == 'Auditor' || $tipo_usu == 'Admin' || $tipo_usu == 'Tesorero asociación') {
            if ($asociacion_id) {
                $consulta = 'SELECT i.*, d.asociacion_id FROM au_iglesias i
                    INNER JOIN au_distritos d ON d.id=i.distrito_id and d.deleted_at is null
                    WHERE d.asociacion_id=? and i.deleted_at is null;';
                return DB::select($consulta, [$asociacion_id]);
            }else{
                $consulta = 'SELECT * FROM au_iglesias i
                    WHERE i.deleted_at is null;';
                return DB::select($consulta);
            }
        }

        return [];
    }

}
