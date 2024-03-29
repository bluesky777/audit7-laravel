<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class User extends Authenticatable implements JWTSubject
{

    public static $default_female = 'system/avatars/female1.jpg';
    public static $default_male = 'system/avatars/male1.jpg';
    public static $perfil_path = 'perfil/';


    protected $table = 'au_users';

    public static function identificar()
    {
        $usuario    = [];
        $auth       = Request::input('auth');

        $cons = 'SELECT u.*, null as password
            FROM au_users u
            where u.deleted_at is null and u.username=? and u.password=?';

        $user = DB::select($cons, [ $auth['username'], $auth['password'] ]);

        if (count($user)>0) {
            $user = $user[0];
        }else{
            abort(401, 'Usuario inválido');
        }

        return $user;
    }



    use HasFactory, Notifiable;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
