<?php namespace App\Http\Controllers;

use Request;
use Hash;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosIniciales;
use App\Http\Sincronizar;
use App\Http\Models\DatosDescarga;
use App\Models\User;
use Carbon\Carbon;
use \Log;

use DB;

class LoginController extends Controller {

    public function getLoadUser() {
        return ["sfv", "9887u ff", "efvdf"];
    }


    public function postLogin()
    {
        $credentials = request(['username', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // return $this->respondWithToken($token);
		return response()->json([
            'token' => $token,
			'user' => auth()->user(),
        ]);
    }

    public function postMe()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postLogout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Crear usuario administrador
     */
    public function getResetUser()
    {
        $user = DB::select('SELECT * FROM au_users WHERE id=1');
		if (count($user) > 0) {
			$user = $user[0];
			$pass = bcrypt('456');
			DB::update('UPDATE au_users SET username=?, password=? WHERE id=1', ['admin', $pass]);
		}


        return response()->json(['message' => 'Successfully reseted']);
    }

	public function postLoguear()
	{
		$username 	= Request::input('username');
		$password 	= Request::input('password');

		$consulta 	= 'SELECT * FROM au_users WHERE username=? and password=?;';
		$usuario 	= DB::select($consulta, [$username, $password]);

		if (count($usuario) > 0) {
            $usuario = $usuario[0];


            if(User::hasDivisionRole($usuario->tipo, true)){

                $consulta 	= 'SELECT * FROM au_uniones WHERE deleted_at is null';
                $usuario->uniones 	= DB::select($consulta);

                if($usuario->union_id > 0){

                    $consulta 	= 'SELECT * FROM au_asociaciones WHERE union_id=? and deleted_at is null';
                    $usuario->asociaciones 	= DB::select($consulta, [$usuario->union_id]);

                }
			}

			if($usuario->iglesia_id > 0){

				$consulta 	= 'SELECT i.*, d.nombre as distrito_nombre, d.alias as distrito_alias,
						t.nombres as tesorero_nombres, t.apellidos as tesorero_apellidos,
                		p.nombres as pastor_nombres, p.apellidos as pastor_apellidos
					FROM au_iglesias i
					LEFT JOIN au_distritos d ON d.id=i.distrito_id AND d.deleted_at is null
					LEFT JOIN au_users t ON t.tipo="Tesorero distrital" and t.id=d.tesorero_id and t.deleted_at is null
					LEFT JOIN au_users p ON p.tipo="Pastor" and p.id=d.pastor_id and p.deleted_at is null
					WHERE i.id=? and i.deleted_at is null';
				$iglesia 	= DB::select($consulta, [$usuario->iglesia_id]);

				if (count($iglesia) > 0) {
					$usuario->iglesia_nombre 	= $iglesia[0]->nombre;
					$usuario->iglesia_alias 	= $iglesia[0]->alias;
					$usuario->iglesia_codigo 	= $iglesia[0]->codigo;
					$usuario->distrito_nombre 	= $iglesia[0]->distrito_nombre;
					$usuario->distrito_alias 	= $iglesia[0]->distrito_alias;
					$usuario->distrito_pastor 	= $iglesia[0]->pastor_nombres . ' ' . $iglesia[0]->pastor_apellidos;
					$usuario->distrito_tesorero = $iglesia[0]->tesorero_nombres . ' ' . $iglesia[0]->tesorero_apellidos;
				}

			}


			return [$usuario];
		}else{
			return abort(401, 'Datos incorrectos.');
		}

	}

/**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

}
