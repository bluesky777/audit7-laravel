<?php namespace App\Http\Controllers;

use Request;
use Hash;
use Excel;

use App\Http\Controllers\Models\AuUser;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosIniciales;
use App\Http\Controllers\Sincronizar;
use Carbon\Carbon;
use \Log;

use DB;

class AuUsuariosController extends Controller {




	public function postStore()
	{
        $user   = AuUser::identificar();
        $now 	= Carbon::now('America/Bogota');
        $data   = Request::input('data');

        if (($data['tipo'] == 'Tesorero asociación' || $data['tipo'] == 'Admin') && ($user->tipo == 'Pastor' || $user->tipo == 'Tesorero' || $user->tipo == 'Auditor')) {
            return abort(404, 'No puede crear un tipo mayor que usted');
        }

        $consulta = 'INSERT INTO au_users(nombres, apellidos, sexo, username, password, email, fecha, tipo, celular, union_id, asociacion_id, created_by, created_at, updated_at)
            VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)';

        DB::insert($consulta, [$data['nombres'], $data['apellidos'], $data['sexo'], $data['username'], $data['password'], $data['email'],
            $data['email'], $data['tipo'], $data['celular'], $data['union_id'], $data['asociacion_id'], $user->id, $now, $now]);

        $last_id = DB::getPdo()->lastInsertId();

        $consulta = 'SELECT u.*, null as password
            FROM au_users u
            where u.deleted_at is null and u.id=?';

        $usuario = DB::select($consulta, [$last_id])[0];


		return (array)$usuario;

	}


	public function putAll()
	{
        $user   = AuUser::identificar();
        $tipo   = $user->tipo;
        $datos 	= [];
        $consulta = '';

        Log::info('asociacion_id ' . $user->asociacion_id);

        if (AuUser::hasAsociacionRole($tipo)) {

            $consulta 	= "SELECT *, null as password FROM au_users
                WHERE (tipo='Pastor' or tipo='Tesorero iglesia'
                    or tipo=?) and asociacion_id=? and deleted_at is null ORDER BY id DESC";

            $datos = [$tipo, $user->asociacion_id];

        }else if (AuUser::hasUnionRole($tipo)) {
            $consulta 	= "SELECT *, null as password FROM au_users
                WHERE (tipo=? or tipo='Tesorero asociación' or tipo='Cajero de asociación') and union_id=? and deleted_at is null ORDER BY id DESC";

            $datos = [$tipo, $user->union_id];

        }else if (AuUser::hasDivisionRole($tipo)) {
            $consulta 	= "SELECT *, null as password FROM au_users
                WHERE (tipo=? or tipo='Tesorero de unión' or tipo='Coordinador de unión') and deleted_at is null ORDER BY id DESC";

            $datos = [$tipo];

        }else if ($tipo=='Admin') {
            $consulta 	= "SELECT *, null as password FROM au_users
                WHERE deleted_at is null ORDER BY id DESC";

        }else if($tipo == 'Tesorero iglesia' || $tipo == 'Pastor'){
            //$consulta 	= "SELECT *, null as password FROM au_users WHERE deleted_at is null and (tipo=? or tipo=?) ORDER BY id DESC";
            //$datos 		= ['Tesorero', 'Pastor'];
            abort(404, 'No puede ver usuarios');
        }

		$usuarios = DB::select($consulta, $datos);

		return ['usuarios'=> $usuarios];

	}


	public function putUpdate()
	{
        $user = AuUser::identificar();
        $now 	= Carbon::now('America/Bogota');
        $data   = Request::input('data');

        if ($data['password']) {

            $consulta = 'UPDATE au_users SET nombres=?, apellidos=?, sexo=?, email=?, username=?, password=?,
                fecha=?, tipo=?, celular=?, updated_by=?, updated_at=? WHERE id=?';

            DB::update($consulta, [$data['nombres'], $data['apellidos'], $data['sexo'], $data['email'], $data['username'], $data['password'],
                $data['fecha'], $data['tipo'], $data['celular'], $user->id, $now, $data['id']]);
        }else{

            $consulta = 'UPDATE au_users SET nombres=?, apellidos=?, sexo=?, email=?, username=?,
                fecha=?, tipo=?, celular=?, updated_by=?, updated_at=? WHERE id=?';

            DB::update($consulta, [$data['nombres'], $data['apellidos'], $data['sexo'], $data['email'], $data['username'],
                $data['fecha'], $data['tipo'], $data['celular'], $user->id, $now, $data['id']]);
        }


		return 'Cambiado';

	}


	public function putUpdateField()
	{
        $user   = AuUser::identificar();
        $now 	= Carbon::now('America/Bogota');
        $data   = Request::input('data');

        $consulta = 'UPDATE au_users SET '.$data['field'].'=?, updated_by=?, updated_at=? WHERE id=?';
		DB::update($consulta, [$data['valor'], $user->id, $now, $data['id']]);

		return 'Cambiado';

	}


	public function putDestroy()
	{
        $user   = AuUser::identificar();
        $now 	= Carbon::now('America/Bogota');
        $data   = Request::input('data');

        $consulta = 'UPDATE au_users SET deleted_by=?, deleted_at=? WHERE id=?';
		DB::update($consulta, [$user->id, $now, $data['id']]);

		return 'Enviado a papelera';

	}





}
