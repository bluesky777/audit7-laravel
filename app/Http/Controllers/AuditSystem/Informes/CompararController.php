<?php namespace App\Http\Controllers\AuditSystem\Informes;

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

class CompararController extends Controller {
    
    
	public function putIglesiasMesesYear(){
        
        $distrito_id            = Request::input('distrito_id');
        $codigo_cuenta          = Request::input('codigo_cuenta');
        $years                  = Request::input('years');
        $resp                   = [];
        
        $resp['years']      = $years;
        
    
        $consulta       = "SELECT i.id as rowid, i.*, dis.codigo as cod_distrito 
            FROM au_iglesias i 
            INNER JOIN au_distritos dis ON i.distrito_id = dis.id 
            WHERE i.distrito_id=?";
            
        $iglesias      = DB::select($consulta, [$distrito_id]);
        
        
        for ($i = 0; $i < count($iglesias); $i++) {
            $iglesia            = $iglesias[$i];
            $iglesia->anios     = [];
            
            // Primer Año:
            $anio1 = $resp['years'][0];
            
            $consulta = "SELECT r.id as rowid, r.*, abs(r.cantidad) as cantidad 
                FROM au_remesas r 
                WHERE r.org_id=? and cod_cuenta=? and r.periodo like '". $anio1 ."%' order by periodo"; 
                      
            $remesasRes = DB::select($consulta, [$iglesia->codigo, $codigo_cuenta]);

            $res     = [];
            $total   = 0;
            
            for ($j = 0; $j < count($remesasRes); $j++) {
                $remesa = $remesasRes[$j];
                $total = $total + $remesa->cantidad;
                
                if ($remesa->periodo == $anio1.'/001') {
                    $res[$anio1.'/001'] = $remesa;
                }
                if ($remesa->periodo == $anio1.'/002') {
                    $res[$anio1.'/002'] = $remesa;
                }
                if ($remesa->periodo == $anio1.'/003') {
                    $res[$anio1.'/003'] = $remesa;
                }
                if ($remesa->periodo == $anio1.'/004') {
                    $res[$anio1.'/004'] = $remesa;
                }
                if ($remesa->periodo == $anio1.'/005') {
                    $res[$anio1.'/005'] = $remesa;
                }
                if ($remesa->periodo == $anio1.'/006') {
                    $res[$anio1.'/006'] = $remesa;
                }
                if ($remesa->periodo == $anio1.'/007') {
                    $res[$anio1.'/007'] = $remesa;
                }
                if ($remesa->periodo == $anio1.'/008') {
                    $res[$anio1.'/008'] = $remesa;
                }
                if ($remesa->periodo == $anio1.'/009') {
                    $res[$anio1.'/009'] = $remesa;
                }
                if ($remesa->periodo == $anio1.'/010') {
                    $res[$anio1.'/010'] = $remesa;
                }
                if ($remesa->periodo == $anio1.'/011') {
                    $res[$anio1.'/011'] = $remesa;
                }
                if ($remesa->periodo == $anio1.'/012') {
                    $res[$anio1.'/012'] = $remesa;
                }
                
            }
            $anio = [
                "anio" => $anio1,
                "remesas" => $res,
                "total" => $total
            ];
            $iglesia->anios[1] = $anio;
            
            
            // Último Año:
            $anio2 = $resp['years'][1];
            
            $consulta = "SELECT r.id as rowid, r.*, abs(r.cantidad) as cantidad 
                FROM au_remesas r 
                WHERE r.org_id=? and cod_cuenta=? and r.periodo like '". $anio2 ."%' order by periodo"; 
                      
            $remesasRes = DB::select($consulta, [$iglesia->codigo, $codigo_cuenta]);

            $res     = [];
            $total   = 0;
            
            for ($j = 0; $j < count($remesasRes); $j++) {
                $remesa = $remesasRes[$j];
                $total = $total + $remesa->cantidad;
                
                if ($remesa->periodo == $anio2.'/001') {
                    $res[$anio2.'/001'] = $remesa;
                }
                if ($remesa->periodo == $anio2.'/002') {
                    $res[$anio2.'/002'] = $remesa;
                }
                if ($remesa->periodo == $anio2.'/003') {
                    $res[$anio2.'/003'] = $remesa;
                }
                if ($remesa->periodo == $anio2.'/004') {
                    $res[$anio2.'/004'] = $remesa;
                }
                if ($remesa->periodo == $anio2.'/005') {
                    $res[$anio2.'/005'] = $remesa;
                }
                if ($remesa->periodo == $anio2.'/006') {
                    $res[$anio2.'/006'] = $remesa;
                }
                if ($remesa->periodo == $anio2.'/007') {
                    $res[$anio2.'/007'] = $remesa;
                }
                if ($remesa->periodo == $anio2.'/008') {
                    $res[$anio2.'/008'] = $remesa;
                }
                if ($remesa->periodo == $anio2.'/009') {
                    $res[$anio2.'/009'] = $remesa;
                }
                if ($remesa->periodo == $anio2.'/010') {
                    $res[$anio2.'/010'] = $remesa;
                }
                if ($remesa->periodo == $anio2.'/011') {
                    $res[$anio2.'/011'] = $remesa;
                }
                if ($remesa->periodo == $anio2.'/012') {
                    $res[$anio2.'/012'] = $remesa;
                }
                
            }
            $anio = [
                "anio" => $anio2,
                "remesas" => $res,
                "total" => $total
            ];
            $iglesia->anios[1] = $anio;
            
        }
        
        
        $resp['iglesias'] = $iglesias;
        
        
        return $resp;
    }
    
    
	

}