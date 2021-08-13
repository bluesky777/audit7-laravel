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

class UnionesController extends Controller {


	public function putIndex(){

        $con_asociaciones       = Request::input('con_asociaciones');
        $res                    = [];

        if ($con_asociaciones) {

            $consulta               = "SELECT *, id as rowid FROM au_asociaciones d WHERE d.deleted_at is null";
            $asociaciones           = DB::select($consulta);
            $res['asociaciones']    = $asociaciones;
        }

        $consulta       = "SELECT *, id as rowid FROM au_uniones d WHERE d.deleted_at is null";
        $uniones        = DB::select($consulta);

        $res['uniones'] = $uniones;

        return $res;




    }



}
