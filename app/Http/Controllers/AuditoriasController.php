<?php namespace App\Http\Controllers;

use Request;
use Hash;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosIniciales;
use App\Http\Controllers\Sincronizar;
use App\Models\DatosDescarga;
use App\Models\AuUser;
use Carbon\Carbon;
use \Log;

use DB;

class AuditoriasController extends Controller {


	public function getAll()
	{
		$res 		= [];
		$username 	= Request::input('username');
		$password 	= Request::input('password');


		$user 		= DB::select('SELECT * FROM au_users WHERE username=? and password=?;', [$username, $password]);

		if (count($user) > 0) {
			$user = $user[0]; // Auditor, Pastor, Tesorero, Tesorero asociación, Admin
			if ($user->tipo == 'Auditor') {
				$res['usuarios'] 		= DB::select('SELECT u.* FROM au_users u WHERE u.tipo="Tesorero" or u.tipo="Pastor" or (u.tipo="Auditor" and u.id=?);', [$user->id]);
			}

			if ($user->tipo == 'Tesorero asociación') {
				$res['usuarios'] 		= DB::select('SELECT u.* FROM au_users u WHERE u.tipo="Tesorero" or u.tipo="Pastor" or u.tipo="Auditor" or (u.tipo="Tesorero asociación" and u.id=?);', [$user->id]);
			}

			if ($user->tipo == 'Admin') {
				$res['usuarios'] 		= DB::select('SELECT u.* FROM au_users u ;');
			}

		}else{
			// Debería mandar error, pero por probar...
			// return abort(401, 'Datos incorrectos.');
			$res['usuarios'] 		= DB::select('SELECT * FROM au_users WHERE username=? and password=?;', [$username, $password]);
		}


		$res['respuestas'] 		= DB::select('SELECT * from au_respuestas;');
		$res['preguntas'] 		= DB::select('SELECT * from au_preguntas;');
		$res['gastos_mes'] 		= DB::select('SELECT * from au_gastos_mes;');
		$res['destinos_pagos'] 	= DB::select('SELECT * from au_destinos_pagos;');
		$res['destinos'] 		= DB::select('SELECT * from au_destinos;');
		$res['lib_semanales'] 	= DB::select('SELECT * from au_lib_semanales;');
		$res['lib_mensuales'] 	= DB::select('SELECT * from au_lib_mensuales;');
		$res['auditorias'] 		= DB::select('SELECT * from au_auditorias;');
		$res['iglesias'] 		= DatosDescarga::iglesias($user->tipo, $user->asociacion_id);
		$res['distritos'] 		= DatosDescarga::distritos($user->tipo, $user->asociacion_id);
		$res['asociaciones'] 	= DB::select('SELECT * from au_asociaciones;');
		$res['uniones'] 		= DB::select('SELECT * from au_uniones;');
		$res['recomendaciones'] = DB::select('SELECT * from au_recomendaciones;');
		$res['dinero_efectivo'] = DB::select('SELECT * from au_dinero_efectivo;');
		$res['remesas'] 		= DB::select('SELECT * from au_remesas;');


		return $res;
	}

	// /feryz_server/public/auditorias/insertar-datos-iniciales
	public function getInsertarDatosIniciales()
	{
		$datos 		= new DatosIniciales;

		//$datos->insertarUnionAudit();
		//$datos->insertarAsociaciones();
		Log::info('Vamo a insertar');
		$datos->insertarDistritos();
		$datos->insertarIglesias();

		//$datos->insertarUsuarios();
		return 'Insertados';
	}


	// /feryz_server/public/auditorias/borrar-datos-iniciales
	// Función muy peligrosa. Debo borrarla!!!
	public function getBorrarDatosIniciales()
	{
		DB::delete('DELETE FROM au_users;');
		DB::delete('DELETE FROM au_uniones;');
		DB::delete('DELETE FROM au_asociaciones;');
		DB::delete('DELETE FROM au_distritos;');
		DB::delete('DELETE FROM au_iglesias;');
		DB::delete('DELETE FROM au_auditorias;');
		DB::delete('DELETE FROM au_lib_mensuales;');
		DB::delete('DELETE FROM au_lib_semanales;');
		DB::delete('DELETE FROM au_destinos;');
		DB::delete('DELETE FROM au_destinos_pagos;');
		DB::delete('DELETE FROM au_gastos_mes;');
		DB::delete('DELETE FROM au_preguntas;');
		DB::delete('DELETE FROM au_respuestas;');
		DB::delete('DELETE FROM au_recomendaciones;');
		DB::delete('DELETE FROM au_dinero_efectivo;');
		return 'BORRADOS';
	}



	public function putSubirDatos()
	{
		$uniones 		= Request::input('uniones');
		$asociaciones 	= Request::input('asociaciones');
		$distritos 		= Request::input('distritos');
		$iglesias 		= Request::input('iglesias');
		$usuarios 		= Request::input('usuarios');
		$auditorias 	= Request::input('auditorias');
		$lib_mensuales 	= Request::input('lib_mensuales');
		$lib_semanales 	= Request::input('lib_semanales');
		$destinos 		= Request::input('destinos');
		$destinos_pagos = Request::input('destinos_pagos');
		$gastos_mes 	= Request::input('gastos_mes');
		$preguntas 		= Request::input('preguntas');
		$respuestas 	= Request::input('respuestas');
		$recomendas 	= Request::input('recomendaciones');
		$dinero 		= Request::input('dinero_efectivo');

		$now 		= Carbon::now('America/Bogota');

		$rUniones 			= [];
		$rAsociaciones 		= [];
		$rDistritos 		= [];
		$rIglesias 			= [];
		$rUsuarios 			= [];
		$rAuditorias 		= [];
		$rLib_mensuales 	= [];
		$rLib_semanales 	= [];
		$rDestinos 			= [];
		$rDestinos_pagos 	= [];
		$rGastos_mes 		= [];
		$rPreguntas 		= [];
		$rRespuestas 		= [];
		$rRecomendas 		= [];
		$rDinero 			= [];

		$sqlUniones 			= '';
		$sqlAsociaciones 		= '';
		$sqlDistritos 			= '';
		$sqlIglesias 			= '';
		$sqlUsuarios 			= '';
		$sqlAuditorias 			= '';
		$sqlLib_mensuales 		= '';
		$sqlLib_semanales 		= '';
		$sqlDestinos 			= '';
		$sqlDestinos_pagos 		= '';
		$sqlGastos_mes 			= '';
		$sqlPreguntas 			= '';
		$sqlRespuestas 			= '';
		$sqlRecomendas 			= '';
		$sqlDinero 				= '';


		for ($i=0; $i < count($uniones); $i++) {
			$elem 	= $uniones[$i];

			$sincro 	= new Sincronizar();
			$sincro->syncUniones($elem, $now);

			if (!isset($elem['id'])) {
				if (strlen($sqlUniones) > 0) {
					$sqlUniones .= ' OR id=' . DB::getPdo()->lastInsertId();
				}else{
					$sqlUniones .= ' id=' . DB::getPdo()->lastInsertId();
				}
			}
		}


		for ($i=0; $i < count($asociaciones); $i++) {
			$elem 	= $asociaciones[$i];

			$sincro 	= new Sincronizar();
			$sincro->syncAsociaciones($elem, $now);

			if (!isset($elem['id'])) {
				if (strlen($sqlAsociaciones) > 0) {
					$sqlAsociaciones .= ' OR id=' . DB::getPdo()->lastInsertId();
				}else{
					$sqlAsociaciones .= ' id=' . DB::getPdo()->lastInsertId();
				}
			}
		}


		for ($i=0; $i < count($distritos); $i++) {
			$elem 	= $distritos[$i];

			$sincro 	= new Sincronizar();
			$sincro->syncDistritos($elem, $now);

			if (!isset($elem['id'])) {
				if (strlen($sqlDistritos) > 0) {
					$sqlDistritos .= ' OR id=' . DB::getPdo()->lastInsertId();
				}else{
					$sqlDistritos .= ' id=' . DB::getPdo()->lastInsertId();
				}
			}
		}

		for ($i=0; $i < count($iglesias); $i++) {
			$elem 	= $iglesias[$i];

			$sincro 	= new Sincronizar();
			$sincro->syncIglesias($elem, $now);

			if (!isset($elem['id'])) {
				if (strlen($sqlIglesias) > 0) {
					$sqlIglesias .= ' OR id=' . DB::getPdo()->lastInsertId();
				}else{
					$sqlIglesias .= ' id=' . DB::getPdo()->lastInsertId();
				}
			}
		}


		for ($i=0; $i < count($usuarios); $i++) {
			$elem 	= $usuarios[$i];

			$sincro 	= new Sincronizar();
			$sincro->syncUsuarios($elem, $now);

			if (!isset($elem['id'])) {
				if (strlen($sqlUsuarios) > 0) {
					$sqlUsuarios .= ' OR id=' . DB::getPdo()->lastInsertId();
				}else{
					$sqlUsuarios .= ' id=' . DB::getPdo()->lastInsertId();
				}
			}
		}



		$new_ids_auditorias = [];

		for ($i=0; $i < count($auditorias); $i++) {
			$elem 	= $auditorias[$i];

			$sincro 	= new Sincronizar();
			$sincro->syncAuditorias($elem, $now);

			if (!isset($elem['id'])) {
				$new_id = DB::getPdo()->lastInsertId();
				array_push($new_ids_auditorias, [ 'new_id' => $new_id, 'row_id' => $elem['id'] ]);

				if (strlen($sqlAuditorias) > 0) {
					$sqlAuditorias .= ' OR id=' . DB::getPdo()->lastInsertId();
				}else{
					$sqlAuditorias .= ' id=' . DB::getPdo()->lastInsertId();
				}
			}
		}
		if (strlen($sqlAuditorias) > 0) {
			$consulta = 'SELECT * FROM au_auditorias WHERE ' . $sqlAuditorias;
			$rAuditorias = DB::select($consulta);
		}



		for ($i=0; $i < count($lib_mensuales); $i++) {
			$elem 	= $lib_mensuales[$i];

			$sincro 	= new Sincronizar();
			$sincro->syncLib_mensuales($elem, $now, $new_ids_auditorias);

			if (!isset($elem['id'])) {
				if (strlen($sqlLib_mensuales) > 0) {
					$sqlLib_mensuales .= ' OR id=' . DB::getPdo()->lastInsertId();
				}else{
					$sqlLib_mensuales .= ' id=' . DB::getPdo()->lastInsertId();
				}
			}
		}

		for ($i=0; $i < count($lib_semanales); $i++) {
			$elem 	= $lib_semanales[$i];

			$sincro 	= new Sincronizar();
			$sincro->syncLib_semanales($elem, $now);

			if (!isset($elem['id'])) {
				if (strlen($sqlLib_semanales) > 0) {
					$sqlLib_semanales .= ' OR id=' . DB::getPdo()->lastInsertId();
				}else{
					$sqlLib_semanales .= ' id=' . DB::getPdo()->lastInsertId();
				}
			}
		}


		for ($i=0; $i < count($destinos); $i++) {
			$elem 	= $destinos[$i];

			$sincro 	= new Sincronizar();
			$sincro->syncDestinos($elem, $now);

			if (!isset($elem['id'])) {
				if (strlen($sqlDestinos) > 0) {
					$sqlDestinos .= ' OR id=' . DB::getPdo()->lastInsertId();
				}else{
					$sqlDestinos .= ' id=' . DB::getPdo()->lastInsertId();
				}
			}
		}


		for ($i=0; $i < count($destinos_pagos); $i++) {
			$elem 	= $destinos_pagos[$i];

			$sincro 	= new Sincronizar();
			$sincro->syncDestinos_pagos($elem, $now);

			if (!isset($elem['id'])) {
				if (strlen($sqlDestinos_pagos) > 0) {
					$sqlDestinos_pagos .= ' OR id=' . DB::getPdo()->lastInsertId();
				}else{
					$sqlDestinos_pagos .= ' id=' . DB::getPdo()->lastInsertId();
				}
			}
		}


		for ($i=0; $i < count($gastos_mes); $i++) {
			$elem 	= $gastos_mes[$i];

			$sincro 	= new Sincronizar();
			$sincro->syncGastos_mes($elem, $now, $new_ids_auditorias);

			if (!isset($elem['id'])) {
				if (strlen($sqlGastos_mes) > 0) {
					$sqlGastos_mes .= ' OR id=' . DB::getPdo()->lastInsertId();
				}else{
					$sqlGastos_mes .= ' id=' . DB::getPdo()->lastInsertId();
				}
			}
		}


		for ($i=0; $i < count($preguntas); $i++) {
			$elem 	= $preguntas[$i];

			$sincro 	= new Sincronizar();
			$sincro->syncPreguntas($elem, $now);

			if (!isset($elem['id'])) {
				if (strlen($sqlPreguntas) > 0) {
					$sqlPreguntas .= ' OR id=' . DB::getPdo()->lastInsertId();
				}else{
					$sqlPreguntas .= ' id=' .DB::getPdo()->lastInsertId();
				}
			}
		}


		for ($i=0; $i < count($respuestas); $i++) {
			$elem 	= $respuestas[$i];

			$sincro 	= new Sincronizar();
			$sincro->syncRespuestas($elem, $now, $new_ids_auditorias);

			if (!isset($elem['id'])) {
				if (strlen($sqlRespuestas) > 0) {
					$sqlRespuestas .= ' OR id=' . DB::getPdo()->lastInsertId();
				}else{
					$sqlRespuestas .= ' id=' . DB::getPdo()->lastInsertId();
				}
			}
		}



		for ($i=0; $i < count($recomendas); $i++) {
			$elem 	= $recomendas[$i];

			$sincro 	= new Sincronizar();
			$sincro->syncRecomendas($elem, $now, $new_ids_auditorias);

			if (!isset($elem['id'])) {
				$new_id = DB::getPdo()->lastInsertId();

				if (strlen($sqlRecomendas) > 0) {
					$sqlRecomendas .= ' OR id=' . $new_id;
				}else{
					$sqlRecomendas .= ' id=' . $new_id;
				}
			}
		}


		for ($i=0; $i < count($dinero); $i++) {
			$elem 	= $dinero[$i];

			$sincro 	= new Sincronizar();
			$sincro->syncDinero_efectivo($elem, $now);

			if (!isset($elem['id'])) {
				if (strlen($sqlDinero) > 0) {
					$sqlDinero .= ' OR id=' . DB::getPdo()->lastInsertId();
				}else{
					$sqlDinero .= ' id=' . DB::getPdo()->lastInsertId();
				}
			}
		}




		if (strlen($sqlUniones) > 0) {
			$consulta = 'SELECT * FROM au_uniones WHERE ' . $sqlUniones;
			$rUniones = DB::select($consulta);
		}

		if (strlen($sqlAsociaciones) > 0) {
			$consulta = 'SELECT * FROM au_asociaciones WHERE ' . $sqlAsociaciones;
			//Log::info($consulta);
			$rAsociaciones = DB::select($consulta);
		}

		if (strlen($sqlDistritos) > 0) {
			$consulta = 'SELECT * FROM au_distritos WHERE ' . $sqlDistritos;
			$rDistritos = DB::select($consulta);
		}

		if (strlen($sqlIglesias) > 0) {
			$consulta = 'SELECT * FROM au_iglesias WHERE ' . $sqlIglesias;
			$rIglesias = DB::select($consulta);
		}
		if (strlen($sqlUsuarios) > 0) {
			$consulta = 'SELECT * FROM au_users WHERE ' . $sqlUsuarios;
			$rUsuarios = DB::select($consulta);
		}
		if (strlen($sqlLib_mensuales) > 0) {
			$consulta = 'SELECT * FROM au_lib_mensuales WHERE ' . $sqlLib_mensuales;
			$rLib_mensuales = DB::select($consulta);
		}
		if (strlen($sqlDestinos) > 0) {
			$consulta = 'SELECT * FROM au_destinos WHERE ' . $sqlDestinos;
			$rDestinos = DB::select($consulta);
		}
		if (strlen($sqlDestinos_pagos) > 0) {
			$consulta = 'SELECT * FROM au_destinos_pagos WHERE ' . $sqlDestinos_pagos;
			$rDestinos_pagos = DB::select($consulta);
		}
		if (strlen($sqlLib_semanales) > 0) {
			$consulta = 'SELECT * FROM au_lib_semanales WHERE ' . $sqlLib_semanales;
			$rLib_semanales = DB::select($consulta);
		}
		if (strlen($sqlGastos_mes) > 0) {
			$consulta = 'SELECT * FROM au_gastos_mes WHERE ' . $sqlGastos_mes;
			$rGastos_mes = DB::select($consulta);
		}
		if (strlen($sqlPreguntas) > 0) {
			$consulta = 'SELECT * FROM au_preguntas WHERE ' . $sqlPreguntas;
			$rPreguntas = DB::select($consulta);
		}
		if (strlen($sqlRespuestas) > 0) {
			$consulta = 'SELECT * FROM au_respuestas WHERE ' . $sqlRespuestas;
			$rRespuestas = DB::select($consulta);
		}
		if (strlen($sqlRecomendas) > 0) {
			$consulta = 'SELECT * FROM au_recomendaciones WHERE ' . $sqlRecomendas;
			$rRecomendas= DB::select($consulta);
		}
		if (strlen($sqlDinero) > 0) {
			$consulta = 'SELECT * FROM au_dinero_efectivo WHERE ' . $sqlDinero;
			$rDinero= DB::select($consulta);
		}


		return [
			'uniones' 				=> $rUniones,
			'asociaciones' 			=> $rAsociaciones,
			'distritos' 			=> $rDistritos,
			'iglesias' 				=> $rIglesias,
			'usuarios' 				=> $rUsuarios,
			'auditorias' 			=> $rAuditorias,
			'lib_mensuales' 		=> $rLib_mensuales,
			'lib_semanales' 		=> $rLib_semanales,
			'destinos' 				=> $rDestinos,
			'destinos_pagos' 		=> $rDestinos_pagos,
			'gastos_mes' 			=> $rGastos_mes,
			'preguntas' 			=> $rPreguntas,
			'respuestas' 			=> $rRespuestas,
			'recomendaciones' 		=> $rRecomendas,
			'dinero_efectivo' 		=> $rDinero
		];
	}





	public function putRevision()
	{
		$user 				= AuUser::identificar();
		$r 					= [];

		$anio 	= date("Y");

		if (Request::has('anio')) {
			$anio = Request::input('anio');
		}else{
			$consulta = "SELECT distinct LEFT(a.fecha , 4) as anio FROM au_auditorias a
				INNER JOIN au_iglesias i ON i.id=a.iglesia_id and i.deleted_at is null
				INNER JOIN au_distritos d ON d.id=i.distrito_id and d.deleted_at is null
				WHERE d.asociacion_id=? and a.deleted_at is null
				ORDER BY anio";

			$r['anios']     = DB::select($consulta, [$user->asociacion_id]);
		}

		$r['anio']     = $anio;


		$consulta = "SELECT * FROM au_asociaciones a
			WHERE a.id=? and a.deleted_at is null";

		$r['asociacion']     = DB::select($consulta, [$user->asociacion_id]);

		if (count($r['asociacion']) > 0) {
			$r['asociacion'] = $r['asociacion'][0];
		}


		if (AuUser::hasAsociacionRole($user->tipo, true) || AuUser::hasUnionRole($user->tipo)) {

			$consulta 			= 'SELECT * FROM au_distritos WHERE asociacion_id=?';
			$distritos 			= DB::select($consulta, [$user->asociacion_id]);
			$cant_dist 			= count($distritos);
			$cant_iglesias 		= 0;
			$cant_auditadas 	= 0;


			for ($j=0; $j < $cant_dist; $j++) {

				$consulta   = "SELECT COUNT(a.id) as cantidad FROM au_auditorias a
					INNER JOIN au_iglesias i ON i.id=a.iglesia_id and i.deleted_at is null
					WHERE i.distrito_id=? and fecha like '".$anio."%' and cerrada=1 and a.deleted_at is null";

				$distritos[$j]->cant_auditorias_cerradas     = (DB::select($consulta, [$distritos[$j]->id])[0] )->cantidad;
				$cant_auditadas 	+= $distritos[$j]->cant_auditorias_cerradas;


				$consulta   = "SELECT a.*, i.nombre, i.codigo
					FROM au_iglesias i
					LEFT JOIN au_auditorias a ON i.id=a.iglesia_id and a.deleted_at is null and fecha like '".$anio."%'
					WHERE i.distrito_id=? and i.deleted_at is null";

				$distritos[$j]->auditorias     	= DB::select($consulta, [$distritos[$j]->id]);
				$cant_iglesias 					+= count($distritos[$j]->auditorias);

			}

			$r['distritos'] = $distritos;
		}

		$r['cant_iglesias'] 	= $cant_iglesias;
		$r['cant_auditadas'] 	= $cant_auditadas;

		return $r;
	}

}
