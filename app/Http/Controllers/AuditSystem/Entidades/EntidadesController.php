<?php namespace App\Http\Controllers\AuditSystem\Entidades;

use Request;
use Hash;
use File;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuditSystem\DatosIniciales;
use App\Http\Controllers\AuditSystem\Sincronizar;
use Carbon\Carbon;
use \Log;
use App\Models\ImagenModel;
use App\Http\Controllers\AuditSystem\Models\DatosDescarga;
use App\Http\Controllers\AuditSystem\Models\AuUser;

use DB;

class EntidadesController extends Controller {
    
    
	public function putDatos(){
        
        $user   = AuUser::identificar();
        $res    = [];
        

        //$user = $user[0]; // Auditor, Pastor, Tesorero, Tesorero asociación, Admin
        if ($user->tipo == 'Auditor') {
            $res['usuarios'] 		= DB::select('SELECT u.* FROM au_users u 
                    WHERE u.tipo="Tesorero" or u.tipo="Pastor" or (u.tipo="Auditor" and u.id=?);', [$user->id]);
        }
        
        if (AuUser::hasAsociacionRole($user->tipo)) {

            $consulta = 'SELECT u.* 
                FROM au_users u 
                WHERE u.asociacion_id=? and (u.tipo="Tesorero iglesia" or u.tipo="Pastor" or u.tipo="Auditor") 
                    or (u.tipo="Tesorero asociación" and u.id=?) ;';

            $res['usuarios'] 		= DB::select($consulta, [$user->asociacion_id, $user->id]);
        }
        
        if ($user->tipo == 'Admin') {
            $res['usuarios'] 		= DB::select('SELECT u.* FROM au_users u ;');
        }
		
        
        
        $res['iglesias'] 		= DatosDescarga::iglesias($user->tipo, $user->asociacion_id);
		$res['distritos'] 		= DatosDescarga::distritos($user->tipo, $user->asociacion_id);
		$res['asociaciones'] 	= DB::select('SELECT * from au_asociaciones;');
		$res['uniones'] 		= DB::select('SELECT * from au_uniones;');
        
        
        return $res;
    }
    
    
	

}