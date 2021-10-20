<?php namespace App\Repositories;

use App\Models\User;
use \Log;

abstract class RoleTypes {
    const DIVISION = 'DIVISION';
    const UNION = 'UNION';
    const ASOCIACION = 'ASOCIACION';
    const DISTRITO = 'DISTRITO';
    const IGLESIA = 'IGLESIA';
} 


class RoleChecker
{

    private $user;

    public function __construct(){
        $this->user = auth()->user();
    }

    public function getRole(){
        // Para más adelante cuando trabajemos con las divisiones
        // if($this->hasDivisionRole()){
        //     return RoleTypes::DIVISION;
        // }
        if($this->hasUnionRole(true)){
            return RoleTypes::UNION;
        }
        if($this->hasAsociacionRole()){
            return RoleTypes::ASOCIACION;
        }
        if($this->hasIglesiaRole()){
            return RoleTypes::IGLESIA;
        }
        /*
        // TODO
        if(this->hasDistritoRole()){
            return RoleTypes::ASOCIACION;
        }
        */

    }

    
    public function hasDivisionRole()
    {
        $tipo = $this->user->tipo;
        Log::info('hasDivisionRole: '.$tipo);

        if ($tipo == 'Admin'
            || $tipo=='Tesorero de unión'
            || $tipo=='Coordinador de unión'
            || $tipo=='Tesorero de división'
            || $tipo=='Coordinador de división'){

            return true;
        }else{
            return false;
        }

    }


    public function hasUnionRole($incluir_admin=false)
    {
        $tipo = $this->user->tipo;
        
        if ($tipo=='Tesorero de unión'
            || $tipo=='Coordinador de unión'){

            return true;
        }else{
            if ($incluir_admin) {
                if ($tipo == 'Admin'){
                    return true;
                }else{
                    return false;
                }
            }
        }

    }

    public function hasAsociacionRole($incluir_admin=false)
    {
        $tipo = $this->user->tipo;
        
        if ($tipo=='Auditor'
        || $tipo=='Tesorero asociación'
        || $tipo=='Cajero de asociación'){

            return true;
        }else{
            if ($incluir_admin) {
                if ($tipo == 'Admin'){
                    return true;
                }else{
                    return false;
                }
            }
        }

    }

    public function hasIglesiaRole()
    {
        $tipo = $this->user->tipo;
        
        if ($tipo=='Tesorero'
        || $tipo=='Pastor'){

            return true;
        }else{
            return false;
        }
    }


}
