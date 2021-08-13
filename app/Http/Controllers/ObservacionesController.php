<?php namespace App\Http\Controllers;

use Request;
use Hash;
use File;

use App\Http\Controllers\Models\AuUser;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosIniciales;
use App\Http\Controllers\Sincronizar;
use Carbon\Carbon;
use \Log;
use App\Models\ImagenModel;

use DB;

class ObservacionesController extends Controller {

	public function putCorrespondientes(){
		$tipo 				= Request::input('tipo_usu');
		$id_usu 			= Request::input('id_usu');
		$observs_por 		= Request::input('observs_por');
		$user 				= AuUser::identificar();
		$recomendaciones 	= [];


		switch ($observs_por) {
			case 'Iglesia':
				return $this->porIglesias($user);
				break;

			case 'Distritos':
				return $this->porDistritos($user);
				break;

			case 'Asociaciones':
				return $this->porAsociaciones($user);
				break;

			default:
				return $this->porIglesias($user);
				break;
		}
	}


	// ******************************************
	// Correspondientes por iglesias
	public function porIglesias($user){

		if ($user->tipo == 'Pastor') {

			$consulta   = "SELECT r.*, i.nombre as iglesia_nombre, i.alias as alias_nombre FROM au_recomendaciones r
				LEFT JOIN au_auditorias a ON a.id=r.auditoria_id and a.deleted_at is null
				LEFT JOIN au_iglesias i ON i.id=a.iglesia_id and i.deleted_at is null
				LEFT JOIN au_distritos d ON d.id=i.distrito_id and d.deleted_at is null
				LEFT JOIN au_users u ON u.id=d.pastor_id and u.deleted_at is null
				WHERE u.id=? and r.deleted_at is null";

			$recomendaciones     = DB::select($consulta, [$user->id]);
		}


		if (AuUser::hasAsociacionRole($user->tipo)) {

			$consulta 			= 'SELECT * FROM au_distritos WHERE asociacion_id=?';
			$distritos 			= DB::select($consulta, [$user->asociacion_id]);
			$cant_dist 			= count($distritos);
			$distritos_res 		= [];

			for ($j=0; $j < $cant_dist; $j++) {

				$distritos[$j]->cantidad = 0;
				$consulta 			= 'SELECT * FROM au_iglesias WHERE distrito_id=?';
				$iglesias 			= DB::select($consulta, [$distritos[$j]->id]);
				$cant 				= count($iglesias);
				$iglesias_res 		= [];

				for ($i=0; $i < $cant; $i++) {

					$consulta   = "SELECT r.*, a.fecha as fecha_auditoria FROM au_recomendaciones r
						LEFT JOIN au_auditorias a ON a.id=r.auditoria_id and a.deleted_at is null
						WHERE a.iglesia_id=? and r.deleted_at is null";

					$iglesias[$i]->recom_auditorias     = DB::select($consulta, [$iglesias[$i]->id]);

					$consulta   = "SELECT r.* FROM au_recomendaciones r
						LEFT JOIN au_iglesias i ON i.id=r.para_id and i.deleted_at is null
						WHERE i.id=? and r.deleted_at is null";

					$iglesias[$i]->recomendaciones     = DB::select($consulta, [$iglesias[$i]->id]);
					$iglesias[$i]->cantidad     		= count($iglesias[$i]->recom_auditorias) + count($iglesias[$i]->recomendaciones);
					$distritos[$j]->cantidad 			+= count($iglesias[$i]->recom_auditorias) + count($iglesias[$i]->recomendaciones);


					if ($iglesias[$i]->cantidad > 0) {
						array_push($iglesias_res, $iglesias[$i]);
					}
				}

				$distritos[$j]->iglesias = $iglesias_res;

				if ($distritos[$j]->cantidad > 0) {
					array_push($distritos_res, $distritos[$j]);
				}
			}
			$recomendaciones['distritos'] = $distritos_res;

		}


		// hasDivisionRole debe ser aparte, por ahora por probar
		if (AuUser::hasUnionRole($user->tipo) || AuUser::hasDivisionRole($user->tipo)) {

			$consulta 			= 'SELECT * FROM au_distritos WHERE asociacion_id=?';
			$distritos 			= DB::select($consulta, [$user->asociacion_id]);
			$cant_dist 			= count($distritos);
			$distritos_res 		= [];

			for ($j=0; $j < $cant_dist; $j++) {

				$distritos[$j]->cantidad = 0;
				$consulta 			= 'SELECT * FROM au_iglesias WHERE distrito_id=?';
				$iglesias 			= DB::select($consulta, [$distritos[$j]->id]);
				$cant 				= count($iglesias);
				$iglesias_res 		= [];

				for ($i=0; $i < $cant; $i++) {

					$consulta   = "SELECT r.*, a.fecha as fecha_auditoria FROM au_recomendaciones r
						LEFT JOIN au_auditorias a ON a.id=r.auditoria_id and a.deleted_at is null
						WHERE a.iglesia_id=? and r.deleted_at is null";

					$iglesias[$i]->recom_auditorias     = DB::select($consulta, [$iglesias[$i]->id]);

					$consulta   = "SELECT r.* FROM au_recomendaciones r
						LEFT JOIN au_iglesias i ON i.id=r.para_id and i.deleted_at is null
						WHERE i.id=? and r.deleted_at is null";

					$iglesias[$i]->recomendaciones     	= DB::select($consulta, [$iglesias[$i]->id]);
					$iglesias[$i]->cantidad     		= count($iglesias[$i]->recom_auditorias) + count($iglesias[$i]->recomendaciones);
					$distritos[$j]->cantidad 			+= count($iglesias[$i]->recom_auditorias) + count($iglesias[$i]->recomendaciones);


					if ($iglesias[$i]->cantidad > 0) {
						array_push($iglesias_res, $iglesias[$i]);
					}

				}

				$distritos[$j]->iglesias = $iglesias_res;

				if ($distritos[$j]->cantidad > 0) {
					array_push($distritos_res, $distritos[$j]);
				}
			}
			$recomendaciones['distritos'] = $distritos_res;

		}



        return $recomendaciones;
	}


	// ******************************************
	// Correspondientes por Distritos
	public function porDistritos($user){


		if (AuUser::hasAsociacionRole($user->tipo)) {

			$consulta 			= 'SELECT * FROM au_distritos WHERE asociacion_id=?';
			$distritos 			= DB::select($consulta, [$user->asociacion_id]);
			$cant_dist 			= count($distritos);
			$distritos_res 		= [];

			for ($j=0; $j < $cant_dist; $j++) {

				$consulta   = "SELECT count(r.id) as cantidad FROM au_recomendaciones r
					LEFT JOIN au_auditorias a ON a.id=r.auditoria_id and a.deleted_at is null
					LEFT JOIN au_iglesias i ON i.id=a.iglesia_id and i.deleted_at is null
					WHERE i.distrito_id=? and r.deleted_at is null";

				$distritos[$j]->recom_auditorias     = (DB::select($consulta, [$distritos[$j]->id])[0] )->cantidad;


				$consulta   = "SELECT count(r.id) cantidad FROM au_recomendaciones r
					LEFT JOIN au_iglesias i ON i.id=r.para_id and i.deleted_at is null
					WHERE i.id=? and r.deleted_at is null";

				$distritos[$j]->recomendaciones 		= (DB::select($consulta, [$distritos[$j]->id])[0] )->cantidad;
				$distritos[$j]->cantidad 				= $distritos[$j]->recom_auditorias + $distritos[$j]->recomendaciones;

				if ($distritos[$j]->cantidad > 0) {
					array_push($distritos_res, $distritos[$j]);
				}
			}

			$recomendaciones['distritos'] = $distritos_res;
		}


		// hasDivisionRole debe ser aparte, por ahora por probar
		if (AuUser::hasUnionRole($user->tipo) || AuUser::hasDivisionRole($user->tipo)) {

			$consulta 			= 'SELECT * FROM au_distritos WHERE asociacion_id=?';
			$distritos 			= DB::select($consulta, [$user->asociacion_id]);
			$cant_dist 			= count($distritos);
			$distritos_res 		= [];

			for ($j=0; $j < $cant_dist; $j++) {

				$consulta   = "SELECT count(r.id) as cantidad FROM au_recomendaciones r
					LEFT JOIN au_auditorias a ON a.id=r.auditoria_id and a.deleted_at is null
					LEFT JOIN au_iglesias i ON i.id=a.iglesia_id and i.deleted_at is null
					WHERE i.distrito_id=? and r.para='Auditoria' and r.deleted_at is null";

				$distritos[$j]->recom_auditorias     = (DB::select($consulta, [$distritos[$j]->id])[0] )->cantidad;


				$consulta   = "SELECT count(r.id) as cantidad FROM au_recomendaciones r
					LEFT JOIN au_iglesias i ON i.id=r.para_id and r.para='Iglesia' and i.deleted_at is null
					WHERE i.distrito_id=? and r.deleted_at is null";

				$distritos[$j]->recom_iglesias 		= (DB::select($consulta, [$distritos[$j]->id])[0] )->cantidad;
				$distritos[$j]->cantidad 			= $distritos[$j]->recom_auditorias + $distritos[$j]->recom_iglesias;

				$consulta   = "SELECT r.* FROM au_recomendaciones r
					WHERE r.para_id=? and r.para='Distrito' and r.deleted_at is null";

				$distritos[$j]->recomendaciones 		= DB::select($consulta, [$distritos[$j]->id]);


				if (count($distritos[$j]->recomendaciones) > 0) {
					array_push($distritos_res, $distritos[$j]);
				}
			}

			$recomendaciones['distritos'] = $distritos_res;

		}



        return $recomendaciones;
	}




	// ******************************************
	// Correspondientes por Asociaciones
	public function porAsociaciones($user){

		// hasDivisionRole debe ser aparte, por ahora por probar
		if (AuUser::hasUnionRole($user->tipo) || AuUser::hasDivisionRole($user->tipo)) {

			$consulta 			= 'SELECT * FROM au_asociaciones WHERE union_id=?';
			$asociaciones 		= DB::select($consulta, [$user->union_id]);
			$cant_dist 			= count($asociaciones);
			$asociaciones_res 	= [];

			for ($j=0; $j < $cant_dist; $j++) {

				$consulta   = "SELECT count(r.id) as cantidad FROM au_recomendaciones r
					LEFT JOIN au_iglesias i ON i.id=r.para_id and r.para='Iglesia' and i.deleted_at is null
					LEFT JOIN au_distritos d ON d.id=i.distrito_id and d.deleted_at is null
					WHERE d.asociacion_id=? and r.deleted_at is null";

				$asociaciones[$j]->recom_iglesias 		= (DB::select($consulta, [$asociaciones[$j]->id])[0] )->cantidad;

				$consulta   = "SELECT count(r.id) as cantidad FROM au_recomendaciones r
					LEFT JOIN au_distritos d ON d.id=r.para_id and r.para='Distrito' and d.deleted_at is null
					WHERE d.asociacion_id=? and r.deleted_at is null";

				$asociaciones[$j]->recom_distritos 		= (DB::select($consulta, [$asociaciones[$j]->id])[0] )->cantidad;
				$asociaciones[$j]->cantidad 			= $asociaciones[$j]->recom_iglesias + $asociaciones[$j]->recom_distritos;

				$consulta   = "SELECT r.* FROM au_recomendaciones r
					WHERE r.para_id=? and r.para='Asociacion' and r.deleted_at is null";

				$asociaciones[$j]->recomendaciones 		= DB::select($consulta, [$asociaciones[$j]->id]);


				if (count($asociaciones[$j]->recomendaciones) > 0) {
					array_push($asociaciones_res, $asociaciones[$j]);
				}
			}

			$recomendaciones['asociaciones'] = $asociaciones_res;

		}



        return $recomendaciones;
	}





	// ************************************************
	public function putGuardarJustificacion(){
		$user 				= AuUser::identificar();
		$tipo 				= $user->tipo;
		$id_usu 			= $user->id;
		$recomendacion 		= Request::input('recomendacion');
		$para 				= $recomendacion['para'];


		function updateRecomendacion($recomendacion)
		{

			$consulta   = "UPDATE au_recomendaciones SET justificacion=? WHERE id=?";
			DB::update($consulta, [$recomendacion['justificacion'], $recomendacion['id']]);
			return 'Guardada';
		}


		// Comprobamos si en verdad puede guardar
		if ($para == 'Auditoria' || $para == 'Iglesia') {
			if ($tipo == 'Pastor' || $tipo == 'Tesorero iglesia') {
				return updateRecomendacion($recomendacion);
			}

		}else if ($para == 'Distrito') {
			if ($tipo == 'Pastor' || $tipo == 'Tesorero distrital') {
				return updateRecomendacion($recomendacion);
			}

		}else if ($para == 'Asociacion') {
			if (AuUser::hasAsociacionRole($tipo)){
				return updateRecomendacion($recomendacion);
			}

		}else if ($para == 'Union') {
			if (AuUser::hasUnionRole($tipo)){
				return updateRecomendacion($recomendacion);
			}
		}


		abort(404, 'No puede responder a esta recomendación');


    }



	public function putGuardarRecomendacion(){
		$user 				= AuUser::identificar();
		$tipo 				= $user->tipo;
		$id_usu 			= $user->id;
		$recomendacion 		= Request::input('recomendacion');
		$now 				= Carbon::now('America/Bogota');
		$fecha 				= $now;
		$tipo_re 			= $recomendacion['tipo'];

		if ($recomendacion['fecha']) {
			$fecha = Carbon::parse($recomendacion['fecha']);
		}

		if (array_key_exists('tipo', $recomendacion['tipo']) ) {
			$tipo_re = $recomendacion['tipo']['tipo'];
		}

		if (array_key_exists('auditoria_id', $recomendacion) ) {

			$consulta   = "INSERT INTO au_recomendaciones(auditoria_id, hallazgo, recomendacion, justificacion, superada, fecha, para, tipo, created_by, created_at, updated_at)
				VALUES (?,?,?,?,?,?,?,?,?,?,?)";

			DB::insert($consulta, [ $recomendacion['auditoria_id'], $recomendacion['hallazgo'], $recomendacion['recomendacion'], $recomendacion['justificacion'], $recomendacion['superada'],
				$fecha, $recomendacion['para'], $tipo_re, $id_usu, $now, $now ]);

		}else {

			$consulta   = "INSERT INTO au_recomendaciones(hallazgo, recomendacion, justificacion, superada, fecha, para, para_id, tipo, created_by, created_at, updated_at)
				VALUES (?,?,?,?,?,?,?,?,?,?,?)";

			DB::insert($consulta, [ $recomendacion['hallazgo'], $recomendacion['recomendacion'], $recomendacion['justificacion'], $recomendacion['superada'],
				$fecha, $recomendacion['para'], $recomendacion['para_id'], $tipo_re, $id_usu, $now, $now ]);

		}

		$last_id 	= DB::getPdo()->lastInsertId();
		$reco 		= DB::select('SELECT * FROM au_recomendaciones WHERE id=?', [$last_id]);

		Log::info($last_id);

		if (count($reco) > 0) {
			$reco = $reco[0];
		}


        return ['recomendacion' => $reco];
    }



	public function putQuitarIglesia(){
        $id             = Request::input('imagen_id');

        $consulta   = "UPDATE au_images SET iglesia_id=NULL WHERE id=?";
        $images     = DB::update($consulta, [$id]);
        return 'Quitada';
    }




}
