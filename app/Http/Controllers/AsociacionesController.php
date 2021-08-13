<?php namespace App\Http\Controllers;

use Request;
use Hash;
use File;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosIniciales;
use App\Http\Controllers\Sincronizar;
use Carbon\Carbon;
use \Log;
use App\Models\ImagenModel;

use DB;

class AsociacionesController extends Controller {


	public function putDeUnion(){

        $tipo_usu               = Request::input('tipo_usu');
        $usu_id                 = Request::input('usu_id');
        $union_id               = Request::input('union_id');
        $cambiando_union        = Request::input('cambiando_union');

        if ($cambiando_union) {
            DB::update('UPDATE au_users SET union_id=? WHERE id=?', [$union_id, $usu_id]);
        }


        $consulta       = "SELECT *, id as rowid FROM au_asociaciones d WHERE d.union_id=? AND d.deleted_at is null";
        $asociaciones   = DB::select($consulta, [$union_id]);


        return $asociaciones;
    }


	public function putAsociacionConDistritos(){

        $tipo_usu               = Request::input('tipo_usu');
        $usu_id                 = Request::input('usu_id');
        $distrito_id            = Request::input('distrito_id');
        $asociacion_id          = Request::input('asociacion_id');
        $asociacion             = [];


        if ($distrito_id) {

            $consulta       = "SELECT a.id as rowid, a.* FROM au_asociaciones a
                INNER JOIN au_distritos d ON a.id = d.asociacion_id and d.deleted_at is null
                WHERE d.id=?";
            $asociacion   = DB::select($consulta, [$distrito_id]);

            if (count($asociacion) > 0) {
                $asociacion = $asociacion[0];
            }

        }else {

            $consulta       = "SELECT a.id as rowid, a.* FROM au_asociaciones a
                WHERE a.id=?";
            $asociacion   = DB::select($consulta, [$asociacion_id]);

            if (count($asociacion) > 0) {
                $asociacion = $asociacion[0];
            }

        }

        $consulta       = "SELECT d.id as rowid, d.* FROM au_distritos d
            WHERE d.asociacion_id=? and d.deleted_at is null";

        $asociacion->distritos   = DB::select($consulta, [$asociacion->id]);



        return ["asociacion" => $asociacion];

    }



}
