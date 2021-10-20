<?php namespace App\Repositories;

use App\Models\User;
use \Log;
use \DB;


class EntidadesRepository
{
    public function base(&$object){

        $checker = new RoleChecker();
        $role = $checker->getRole();

        switch ($role) {
            case RoleTypes::DIVISION:
                $object->uniones = $this->uniones($object->division_id);
                break;

            case RoleTypes::UNION:
                $object->asociaciones = $this->asociaciones($object->union_id);
                break;

            case RoleTypes::ASOCIACION:
                $object->distritos = $this->distritos($object->asociacion_id);
                break;
            
            case RoleTypes::DISTRITO:
                $object->iglesias = $this->iglesias($object->distrito_id);
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
