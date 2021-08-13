<?php namespace App\Http\Controllers;

use Request;
use Hash;
use Excel;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosIniciales;
use App\Http\Controllers\Sincronizar;
use Carbon\Carbon;
use \Log;

use DB;

class AuUsuarioController extends Controller {




	public function putCambiarTema()
	{
        $consulta = 'UPDATE au_users SET tema=? WHERE id=?';
		DB::update($consulta, [Request::input('tema'), Request::input('user_id')]);

		return 'Cambiado';

	}


	public function putCambiarIdioma()
	{
        $consulta = 'UPDATE au_users SET idioma=? WHERE id=?';
		DB::update($consulta, [Request::input('idioma'), Request::input('user_id')]);

		return 'Cambiado';

	}

	public function putCambiarIglesia()
	{
		$distrito_id = Request::input('distrito_id');

		if ($distrito_id) {

			$consulta = 'UPDATE au_users SET iglesia_id=?, distrito_id=? WHERE id=?';
			DB::update($consulta, [Request::input('iglesia_id'), $distrito_id, Request::input('user_id')]);

		}else{

			$consulta = 'UPDATE au_users SET iglesia_id=? WHERE id=?';
			DB::update($consulta, [Request::input('iglesia_id'), Request::input('user_id')]);

		}

		return 'Cambiado';

	}





}
