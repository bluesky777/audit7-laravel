<?php namespace App\Repositories;

use App\Models\User;


abstract class RoleTypes {
    const DIVISION = 'DIVISION';
    const UNION = 'UNION';
    const ASOCIACION = 'ASOCIACION';
    const DISTRITO = 'DISTRITO';
    const IGLESIA = 'IGLESIA';
} 


class RoleChecker
{

    private $tipo;

    function __contruct(){
        $user = auth()->user();
        $this->tipo = $user->tipo;
    }

    public function getRole(){
    
        if($this->hasDivisionRole()){
            return RoleTypes::DIVISION;
        }
        if($this->hasUnionRole()){
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
        $tipo = $this->tipo;

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
        $tipo = $this->tipo;
        
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
        $tipo = $this->tipo;
        
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
        $tipo = $this->tipo;
        
        if ($tipo=='Tesorero'
        || $tipo=='Pastor'){

            return true;
        }else{
            return false;
        }
    }


}
