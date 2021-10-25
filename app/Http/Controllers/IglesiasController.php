<?php namespace App\Http\Controllers;

use Request;
use Hash;
use File;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosIniciales;
use App\Http\Controllers\Sincronizar;
use Carbon\Carbon;
use \Log;
use App\Models\ImagenModel;
use App\Models\User;
use App\Repositories\RoleChecker;
use App\Repositories\RoleTypes;


use DB;

class IglesiasController extends Controller {


	public function putDeAsociacion()
    {
        $user                   = auth()->user();
        $tipo_usu               = $user->tipo;
        $usu_id                 = $user->usu_id;
        $asociacion_id          = Request::input('asociacion_id');
        $cambiando_asociacion   = Request::input('cambiando_asociacion');

        if ($cambiando_asociacion) {
            DB::update('UPDATE au_users SET asociacion_id=? WHERE id=?', [$asociacion_id, $usu_id]);
        }


        if ($tipo_usu == 'Pastor' || $tipo_usu == 'Tesorero') {

            $consulta       = "SELECT *, id as rowid FROM au_distritos d WHERE d.pastor_id=? AND d.deleted_at is null";
            $distritos      = DB::select($consulta, [$usu_id]);

            for ($i=0; $i < count($distritos); $i++) {
                $consulta                   = "SELECT i.*, i.id as rowid, d.nombre as nombre_distrito, d.alias as alias_distrito, d.codigo as codigo_alias, CONCAT(u.nombres, ' ', u.apellidos ) as nombre_pastor, CONCAT(t.nombres, ' ', t.apellidos ) as nombre_tesorero
                    FROM au_iglesias i
                    LEFT JOIN au_distritos d ON d.id=i.distrito_id and d.deleted_at is null
                    LEFT JOIN au_users u ON u.id=d.pastor_id and u.deleted_at is null
                    LEFT JOIN au_users t ON t.id=i.tesorero_id and t.deleted_at is null
                    WHERE i.distrito_id=? AND i.deleted_at is null";
                $distritos[$i]->iglesias    = DB::select($consulta, [ $distritos[$i]->id ]);

            }


            return $distritos;
        }

        $checker = new RoleChecker();
        $role = $checker->getRole();
        // TODO: isAuthored('Asociacion')

        if (RoleTypes::UNION || RoleTypes::ASOCIACION || RoleTypes::IGLESIA) {

            $consulta       = "SELECT *, id as rowid FROM au_distritos d WHERE d.asociacion_id=? AND d.deleted_at is null";
            $distritos      = DB::select($consulta, [$asociacion_id]);

            for ($i=0; $i < count($distritos); $i++) {
                $consulta                   = "SELECT i.*, i.id as rowid, d.nombre as nombre_distrito, d.alias as alias_distrito, d.codigo as codigo_alias, CONCAT(u.nombres, ' ', u.apellidos ) as nombre_pastor, CONCAT(t.nombres, ' ', t.apellidos ) as nombre_tesorero
                    FROM au_iglesias i
                    LEFT JOIN au_distritos d ON d.id=i.distrito_id and d.deleted_at is null
                    LEFT JOIN au_users u ON u.id=d.pastor_id and u.deleted_at is null
                    LEFT JOIN au_users t ON t.id=i.tesorero_id and t.deleted_at is null
                    WHERE i.distrito_id=? AND i.deleted_at is null";
                $distritos[$i]->iglesias    = DB::select($consulta, [ $distritos[$i]->id ]);

            }


            return $distritos;
        }

        return 'Datos inválidos.';

    }

    public function putStore()
	{
        $now 		                = Carbon::now('America/Bogota');
        $nombre                     = Request::input('nombre');
        $alias                      = Request::input('alias');
        $codigo                     = Request::input('codigo');
        $distrito_id                = Request::input('distrito_id');
        $tipo                       = Request::input('tipo');
        $zona                       = Request::input('zona');
        $estado_propiedad           = Request::input('estado_propiedad');
        $estado_propiedad_pastor    = Request::input('estado_propiedad_pastor');
        $tipo_doc_propiedad         = Request::input('tipo_doc_propiedad');
        $tipo_doc_propiedad_pastor  = Request::input('tipo_doc_propiedad_pastor');
        $tipo_doc_propiedad         = Request::input('tipo_doc_propiedad');
        $anombre_propiedad          = Request::input('anombre_propiedad');
        $anombre_propiedad_pastor   = Request::input('anombre_propiedad_pastor');
        $num_matricula              = Request::input('num_matricula');
        $predial                    = Request::input('predial');
        $municipio                  = Request::input('municipio');
        $direccion                  = Request::input('direccion');
        $observaciones              = Request::input('observaciones');
        $secretario_id              = Request::input('secretario_id');
        $tesorero_id                = Request::input('tesorero_id');
        $created_by                 = Request::input('created_by');


        $consulta 	= 'INSERT au_iglesias(nombre, alias, codigo, distrito_id, tipo, zona, estado_propiedad, estado_propiedad_pastor, tipo_doc_propiedad, tipo_doc_propiedad_pastor,
            anombre_propiedad, anombre_propiedad_pastor, num_matricula, predial, municipio, direccion, observaciones, tesorero_id, secretario_id, created_by, created_at, updated_at)
            VALUES(:nombre, :alias, :codigo, :distrito_id, :tipo, :zona, :estado_propiedad, :estado_propiedad_pastor, :tipo_doc_propiedad, :tipo_doc_propiedad_pastor,
            :anombre_propiedad, :anombre_propiedad_pastor, :num_matricula, :predial, :municipio, :direccion, :observaciones, :tesorero_id, :secretario_id, :created_by, :created_at, :updated_at)';

        $datos 		= [
            ':nombre' => $nombre,
            ':alias' => $alias,
            ':codigo' => $codigo,
            ':distrito_id' => $distrito_id,
            ':tipo' => $tipo,
            ':zona' => $zona,
            ':estado_propiedad' => $estado_propiedad,
            ':estado_propiedad_pastor' => $estado_propiedad_pastor,
            ':tipo_doc_propiedad' => $tipo_doc_propiedad,
            ':tipo_doc_propiedad_pastor' => $tipo_doc_propiedad_pastor,
            ':anombre_propiedad' => $anombre_propiedad,
            ':anombre_propiedad_pastor' => $anombre_propiedad_pastor,
            ':num_matricula' => $num_matricula,
            ':predial' => $predial,
            ':municipio' => $municipio,
            ':direccion' => $direccion,
            ':observaciones' => $observaciones,
            ':secretario_id' => $secretario_id,
            ':tesorero_id' => $tesorero_id,
            ':created_by' => $created_by,
            ':created_at' => $now,
            ':updated_at' => $now
        ];

        DB::insert($consulta, $datos);

        $last_id = DB::getPdo()->lastInsertId();

        $consulta = 'SELECT i.*
            FROM au_iglesias i
            where i.deleted_at is null and i.id=?';

        $iglesia = DB::select($consulta, [$last_id])[0];


		return (array)$iglesia;


    }




	/*************************************************************
	 * Guardar por VALOR
	 *************************************************************/
	public function putGuardarValor()
	{
        $now 		= Carbon::now('America/Bogota');
        $id 		= Request::input('iglesia_id');
        $valor 		= Request::input('valor');
        $user_id 	= Request::input('user_id');
        $tipo_usu   = Request::input('tipo_usu');
        $propiedad  = Request::input('propiedad');


		if (User::hasUnionRole($tipo_usu, true) || User::hasAsociacionRole($tipo_usu) || User::hasUnionRole($tipo_usu)) {
            $consulta 	= 'UPDATE au_iglesias SET '.$propiedad.'=:valor, updated_by=:modificador, updated_at=:fecha WHERE id=:id';
            $datos 		= [
                ':valor'		=> $valor,
                ':modificador'	=> $user_id,
                ':fecha' 		=> $now,
                ':id'	=> $id
            ];
			$alumno 	= DB::select($consulta, $datos);

            return 'Guardado';
		} else {
			return abort('400', 'No tiene permisos');
		}

	}




}
