<?php namespace App\Repositories;

use App\Models\User;


class EntidadesRepository
{
    public function base(&$object){

        $checker = new RoleChecker();
        $role = $checker->getRole();
        Log::info('$role '. $role);
        switch ($role) {
            case RoleTypes::DIVISION:
                $object->uniones = $this->uniones();
                Log::info('auiiiiiii');
                break;

            case RoleTypes::UNION:
                $object->asociaciones = $this->asociaciones();
                break;

            case RoleTypes::ASOCIACION:
                $object->distritos = $this->distritos();
                break;
            
            case RoleTypes::DISTRITO:
                $object->iglesias = $this->iglesias();
                break;
            
            default:
                # nothing...
                break;
        }

    }

    public function uniones($division_id){
        $consulta       = "SELECT *, id as rowid FROM au_uniones d WHERE d.division_id=? AND d.deleted_at is null";
        $asociaciones   = DB::select($consulta, [$division_id]);
        return $asociaciones;
    }

    public function asociaciones($union_id){
        $consulta       = "SELECT *, id as rowid FROM au_asociaciones d WHERE d.union_id=? AND d.deleted_at is null";
        $asociaciones   = DB::select($consulta, [$union_id]);
        return $asociaciones;
    }

    public function distritos($asociacion_id){
        $consulta       = "SELECT *, id as rowid FROM au_distritos d WHERE d.asociacion_id=? AND d.deleted_at is null";
        $asociaciones   = DB::select($consulta, [$asociacion_id]);
        return $asociaciones;
    }

    public function iglesias($distrito_id){
        $consulta       = "SELECT *, id as rowid FROM au_iglesias d WHERE d.distrito_id=? AND d.deleted_at is null";
        $asociaciones   = DB::select($consulta, [$distrito_id]);
        return $asociaciones;
    }

}
