<?php namespace App\Http\Controllers;

use Request;
use Hash;
use Excel;

use App\Http\Models\AuUser;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosIniciales;
use App\Http\Controllers\Sincronizar;
use Carbon\Carbon;
use \Log;

use DB;

class RemesasController extends Controller {



	public function putAll()
	{
		$user   	= AuUser::identificar();

		if (AuUser::hasAsociacionRole($user->tipo, true) || AuUser::hasUnionRole($user->tipo)) {
			$consulta 	= 'SELECT * FROM au_remesas WHERE asociacion_id=?';
			$rem 		= DB::select($consulta, [$user->asociacion_id]);

			return ['remesas' => $rem];
		}
		abort(404, 'No tiene permisos');
	}




	public function postUpload()
	{
		//Log::info('asociacion_id: '. Request::input('asociacion_id'));
		if(Request::hasFile('file')){
			$path = Request::file('file')->getRealPath();

			$rr = Excel::load($path, function($reader){


				$results 	= $reader->all();

				if (count($results) > 0) {

					if (isset($results[0]->orgid)) {
						$this->insertarRemesas($results);
					}else{
						for ($i=0; $i < count($results); $i++) {

							if (count($results[$i]) > 0) {

								if (isset($results[$i][0]->orgid)) {
									$this->insertarRemesas($results[$i]);
								}
							}

						}
					}

				}

			});
		}

		return DB::select('SELECT * FROM au_remesas WHERE asociacion_id=?', [Request::input('asociacion_id')]);

		//return $rr->parsed;

	}


	public function insertarRemesas($results){

		DB::delete('DELETE FROM au_remesas WHERE asociacion_id=' . Request::input('asociacion_id') );

		$now 		= Carbon::now('America/Bogota');


		for ($i=0; $i < count($results); $i++) {
			$remesa = $results[$i];

			$fecha_format = null;

			if ($remesa->fecha) {
				$fecha_format = Carbon::parse($remesa->fecha);
			}

			if (!$remesa->periodo) {
				return;
			}


			$consulta 	= 'INSERT INTO au_remesas(num_diario, linea, tipo_diario, num_secuencia, periodo, fecha, referencia, cod_cuenta, nombre_cuenta, descripcion_transaccion, cantidad,
					iva, moneda, recurso, funcion, restr, org_id, empleados, concepto, asociacion_id, created_at, updated_at)
				VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);';

			$datos = [ $remesa->num_diario, $remesa->linea, $remesa->tipo_diario, $remesa->num_secuencia, $remesa->periodo, $remesa->fecha, $remesa->referencia, $remesa->cod_cuenta, $remesa->nombre_de_cuenta, $remesa->descripcion_transaccion, $remesa->cantidad,
				$remesa->iva, $remesa->moneda, $remesa->recurso, $remesa->funcion, $remesa->restr, $remesa->orgid, $remesa->empleados, $remesa->concepto, Request::input('asociacion_id'), $now, $now ];


			DB::insert($consulta, $datos);


		}
	}




	public function postStore()
	{
        $user   = AuUser::identificar();
        $now 	= Carbon::now('America/Bogota');
		$data   = Request::input('data');

        if (AuUser::hasAsociacionRole($user->tipo, true) || AuUser::hasUnionRole($user->tipo)) {

			$data['linea'] = isset($data['linea']) ? $data['linea'] :  null;
			$data['tipo_diario'] = isset($data['tipo_diario']) ? $data['tipo_diario'] :  null;
			$data['num_secuencia'] = isset($data['num_secuencia']) ? $data['num_secuencia'] :  null;
			$data['fecha'] = isset($data['fecha']) ? $data['fecha'] :  null;
			$data['referencia'] = isset($data['referencia']) ? $data['referencia'] :  null;
			$data['nombre_cuenta'] = isset($data['nombre_cuenta']) ? $data['nombre_cuenta'] :  null;
			$data['descripcion_transaccion'] = isset($data['descripcion_transaccion']) ? $data['descripcion_transaccion'] :  null;
			$data['cantidad'] = isset($data['cantidad']) ? $data['cantidad'] :  null;
			$data['iva'] = isset($data['iva']) ? $data['iva'] :  null;
			$data['moneda'] = isset($data['moneda']) ? $data['moneda'] :  null;
			$data['recurso'] = isset($data['recurso']) ? $data['recurso'] :  null;
			$data['restr'] = isset($data['restr']) ? $data['restr'] :  null;
			$data['empleados'] = isset($data['empleados']) ? $data['empleados'] :  null;
			$data['concepto'] = isset($data['concepto']) ? $data['concepto'] :  null;

			$consulta = 'INSERT INTO au_remesas(num_diario, linea, tipo_diario, num_secuencia, periodo, fecha, referencia, cod_cuenta, nombre_cuenta, descripcion_transaccion, cantidad,
					iva, moneda, recurso, funcion, restr, org_id, empleados, concepto, asociacion_id, created_at, updated_at)
				VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';


			DB::insert($consulta, [$data['num_diario'], $data['linea'], $data['tipo_diario'], $data['num_secuencia'], $data['periodo'], $data['fecha'],
				$data['referencia'], $data['cod_cuenta'], $data['nombre_cuenta'], $data['descripcion_transaccion'], $data['cantidad'], $data['iva'],
				$data['moneda'], $data['recurso'], $data['funcion'], $data['restr'], $data['org_id'], $data['empleados'], $data['concepto'], $user->asociacion_id, $now, $now]);

			$last_id = DB::getPdo()->lastInsertId();

			$consulta = 'SELECT r.*, null as password
				FROM au_remesas r
				where r.id=?';

			$usuario = DB::select($consulta, [$last_id])[0];


			return (array)$usuario;

		}else{
			return abort(404, 'No puede crear un tipo mayor que usted');
		}
	}

	public function putUpdateField()
	{
        $user   = AuUser::identificar();
        $now 	= Carbon::now('America/Bogota');
        $data   = Request::input('data');

        $consulta = 'UPDATE au_remesas SET '.$data['field'].'=?, updated_by=?, updated_at=? WHERE id=?';
		DB::update($consulta, [$data['valor'], $user->id, $now, $data['id']]);

		return 'Cambiado';

	}


	public function putDestroy()
	{
        $user   = AuUser::identificar();
        $now 	= Carbon::now('America/Bogota');
        $data   = Request::input('data');

        $consulta = 'DELETE FROM au_remesas WHERE id=?';
		DB::update($consulta, [$data['id']]);

		return 'Eliminada';

	}




}
