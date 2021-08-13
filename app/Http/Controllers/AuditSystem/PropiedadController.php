<?php namespace App\Http\Controllers\AuditSystem;

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

use DB;

class PropiedadController extends Controller {
	
	
	public function putIndex(){
        
        $tipo_usu   = Request::input('tipo_usu');
        $usu_id     = Request::input('usu_id');
        $iglesia_id = Request::input('iglesia_id');
        
        $res = [];
        
        
        $consulta   = "SELECT i.*, d.asociacion_id, d.nombre as nombre_distrito, d.asociacion_id, u.nombres as nombres_tesorero, u.apellidos as apellidos_tesorero
            FROM au_iglesias i 
            LEFT JOIN au_distritos d ON d.id=i.distrito_id AND d.deleted_at is null
            LEFT JOIN au_users u ON u.id=i.tesorero_id AND u.deleted_at is null
            WHERE i.id=? and i.deleted_at is null";
            
        $iglesia    = DB::select($consulta, [$iglesia_id]);
        
        if (count($iglesia) > 0) {
            $iglesia = $iglesia[0];
            
            
            if ($tipo_usu == 'Auditor' || $tipo_usu == 'Admin' || $tipo_usu == 'Tesorero asociación') {
                $consulta   = "SELECT * FROM au_distritos WHERE asociacion_id=? and deleted_at is null";
                $distritos  = DB::select($consulta, [$iglesia->asociacion_id]);
                
                $res['distritos'] = $distritos;
                
                $consulta   = "SELECT * FROM au_users WHERE asociacion_id=? and tipo='Tesorero' and deleted_at is null";
                $usuarios   = DB::select($consulta, [$iglesia->asociacion_id]);
                
                $res['usuarios'] = $usuarios;
            }
            
        }
        
        $consulta   = "SELECT * FROM au_images WHERE deleted_at is null";
        $imagenes   = DB::select($consulta);
        $res['imagenes'] = $imagenes;
        
        $consulta   = "SELECT * FROM au_images WHERE iglesia_id=? and deleted_at is null";
        $documentos = DB::select($consulta, [$iglesia_id]);
        $res['documentos'] = $documentos;
        
        $res['iglesia'] = $iglesia;
    
        return $res;
    }
    
	

}