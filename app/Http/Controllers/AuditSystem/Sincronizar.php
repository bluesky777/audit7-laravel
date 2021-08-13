<?php namespace App\Http\Controllers\AuditSystem;

use Request;
use Hash;

use DB;
use Carbon\Carbon;
use \Log;

class Sincronizar {

	public function syncUniones($elem, $now)
	{
		if (!isset($elem['id'])) {
            $consulta = 'INSERT INTO au_uniones(nombre, alias, codigo, presidente, pais, division_id, created_at, updated_at) 
                VALUES(?,?,?,?,?,?,?,?);';
            DB::insert($consulta, [$elem['nombre'], $elem['alias'], $elem['codigo'], $elem['presidente'], $elem['pais'], $elem['division_id'], $now, $now ]);
        }
        elseif($elem['modificado']){
            $consulta = 'UPDATE au_uniones SET 
                nombre=?, alias=?, codigo=?, presidente=?, pais=?, division_id=?, updated_at=? 
                WHERE id=?;';
            DB::update($consulta, [$elem['nombre'], $elem['alias'], $elem['codigo'], $elem['presidente'], $elem['pais'], $elem['division_id'], $now, $elem['id'] ]);
        }
        elseif($elem['eliminado']){
            DB::delete('DELETE FROM au_uniones WHERE id=?;', [$elem['id']]);
        }
		
		
		return 'Sync';
	}


	public function syncAsociaciones($elem, $now)
	{
		if (!isset($elem['id'])) {
            $consulta = 'INSERT INTO au_asociaciones(nombre, alias, codigo, zona, union_id, tesorero_id, created_at, updated_at) 
                VALUES(?,?,?,?,?,?,?,?);';
            DB::insert($consulta, [$elem['nombre'], $elem['alias'], $elem['codigo'], $elem['zona'], $elem['union_id'], $elem['tesorero_id'], $now, $now ]);
        }
        elseif($elem['modificado']){
            $consulta = 'UPDATE au_asociaciones SET 
                nombre=?, alias=?, codigo=?, zona=?, union_id=?, tesorero_id=?, updated_at=? 
                WHERE id=?;';
            DB::update($consulta, [$elem['nombre'], $elem['alias'], $elem['codigo'], $elem['zona'], $elem['union_id'], $elem['tesorero_id'], $now, $elem['id'] ]);
        }
        elseif($elem['eliminado']){
            DB::delete('DELETE FROM au_asociaciones WHERE id=?;', [$elem['id']]);
        }
		
		
		return 'Sync';
	}


	public function syncDistritos($elem, $now)
	{
		if (!isset($elem['id'])) {
            $consulta = 'INSERT INTO au_distritos(nombre, alias, codigo, zona, asociacion_id, pastor_id, tesorero_id, created_at, updated_at) 
                VALUES(?,?,?,?,?,?,?,?,?);';
            DB::insert($consulta, [$elem['nombre'], $elem['alias'], $elem['codigo'], $elem['zona'], $elem['asociacion_id'], $elem['pastor_id'], $elem['tesorero_id'], $now, $now ]);
        }
        elseif($elem['modificado']){
            $consulta = 'UPDATE au_distritos SET 
                nombre=?, alias=?, codigo=?, zona=?, asociacion_id=?, pastor_id=?, tesorero_id=?, updated_at=? 
                WHERE id=?;';
            DB::update($consulta, [$elem['nombre'], $elem['alias'], $elem['codigo'], $elem['zona'], $elem['asociacion_id'], $elem['pastor_id'], $elem['tesorero_id'], $now, $elem['id'] ]);
        }
        elseif($elem['eliminado']){
            DB::delete('DELETE FROM au_distritos WHERE id=?;', [$elem['id']]);
        }
		
		
		return 'Sync';
	}



	public function syncIglesias($elem, $now)
	{
		if (!isset($elem['id'])) {
            $consulta = 'INSERT INTO au_iglesias(nombre, alias, codigo, zona, distrito_id, tesorero_id, secretario_id, created_at, updated_at, estado_propiedad, estado_propiedad_pastor, tipo_doc_propiedad, tipo_doc_propiedad_pastor, anombre_propiedad, anombre_propiedad_pastor, num_matricula, predial, municipio, direccion, observaciones) 
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);';
            DB::insert($consulta, [$elem['nombre'], $elem['alias'], $elem['codigo'], $elem['zona'], $elem['distrito_id'], $elem['tesorero_id'], $elem['secretario_id'], $now, $now, $elem['estado_propiedad'], $elem['estado_propiedad_pastor'], $elem['tipo_doc_propiedad'], $elem['tipo_doc_propiedad_pastor'], $elem['anombre_propiedad'], $elem['anombre_propiedad_pastor'], $elem['num_matricula'], $elem['predial'], $elem['municipio'], $elem['direccion'], $elem['observaciones'] ]);
        }
        elseif($elem['modificado']){
            $consulta = 'UPDATE au_iglesias SET 
                nombre=?, alias=?, codigo=?, zona=?, distrito_id=?, tesorero_id=?, secretario_id=?, updated_at=?, estado_propiedad=?, estado_propiedad_pastor=?, tipo_doc_propiedad=?, tipo_doc_propiedad_pastor=?, anombre_propiedad=?, anombre_propiedad_pastor=?, num_matricula=?, predial=?, municipio=?, direccion=?, observaciones=? 
                WHERE id=?;';
            DB::update($consulta, [$elem['nombre'], $elem['alias'], $elem['codigo'], $elem['zona'], $elem['distrito_id'], $elem['tesorero_id'], $elem['secretario_id'], $now, $elem['estado_propiedad'], $elem['estado_propiedad_pastor'], $elem['tipo_doc_propiedad'], $elem['tipo_doc_propiedad_pastor'], $elem['anombre_propiedad'], $elem['anombre_propiedad_pastor'], $elem['num_matricula'], $elem['predial'], $elem['municipio'], $elem['direccion'], $elem['observaciones'], $elem['id'] ]);
        }
        elseif($elem['eliminado']){
            DB::delete('DELETE FROM au_iglesias WHERE id=?;', [$elem['id']]);
        }
		
		
		return 'Sync';
	}


	public function syncUsuarios($elem, $now)
	{
		if (!isset($elem['id'])) {
            $consulta = 'INSERT INTO au_users(nombres, apellidos, email, sexo, fecha, tipo, is_active, created_at, updated_at, distrito_id, iglesia_id, auditoria_id, celular, username, password) 
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);';
            DB::insert($consulta, [$elem['nombres'], $elem['apellidos'], $elem['email'], $elem['sexo'], $elem['fecha'], $elem['tipo'], $elem['is_active'], $now, $now, $elem['distrito_id'], $elem['iglesia_id'], $elem['auditoria_id'], $elem['celular'], $elem['username'], $elem['password'] ]);
        }
        elseif($elem['modificado']){
            $consulta = 'UPDATE au_users SET 
                nombres=?, apellidos=?, email=?, sexo=?, fecha=?, tipo=?, is_active=?, updated_at=?, distrito_id=?, iglesia_id=?, auditoria_id=?, celular=?, username=?, password=? 
                WHERE id=?;';
            DB::update($consulta, [$elem['nombres'], $elem['apellidos'], $elem['email'], $elem['sexo'], $elem['fecha'], $elem['tipo'], $elem['is_active'], $now, $elem['distrito_id'], $elem['iglesia_id'], $elem['auditoria_id'], $elem['celular'], $elem['username'], $elem['password'] ]);
        }
        elseif($elem['eliminado']){
            DB::delete('DELETE FROM au_users WHERE id=?;', [$elem['id']]);
        }
		
		
		return 'Sync';
	}


	public function syncAuditorias($elem, $now)
	{
        if (!isset($elem['saldo_ant_descripcion'])) {
            $elem['saldo_ant_descripcion'] = '';
        }
        if (!isset($elem['saldo_final'])) {
            $elem['saldo_final'] = 0;
        }
        if (!isset($elem['cerrada'])) {
            $elem['cerrada'] = 0;
        }
        if (!isset($elem['cerrada_fecha'])) {
            $elem['cerrada_fecha'] = 0;
        }
        
		if (!isset($elem['id'])) {
            $consulta = 'INSERT INTO au_auditorias(fecha, hora, auditor_id, saldo_ant, saldo_ant_descripcion, saldo_final, ingre_por_registrar, ingre_sabados, cta_por_pagar, ajuste_por_enviar, saldo_banco, consig_fondos_confia, gastos_mes_por_regis, dinero_efectivo, cta_por_cobrar, iglesia_id, cerrada, cerrada_fecha, created_at, updated_at) 
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);';
            DB::insert($consulta, [$elem['fecha'], $elem['hora'], $elem['auditor_id'], $elem['saldo_ant'], $elem['saldo_ant_descripcion'], $elem['saldo_final'], $elem['ingre_por_registrar'], $elem['ingre_sabados'], $elem['cta_por_pagar'], $elem['ajuste_por_enviar'], $elem['saldo_banco'], $elem['consig_fondos_confia'], $elem['gastos_mes_por_regis'], $elem['dinero_efectivo'], $elem['cta_por_cobrar'], $elem['iglesia_id'], $elem['cerrada'], $elem['cerrada_fecha'], $now, $now ]);
        }
        elseif($elem['modificado']){
            $consulta = 'UPDATE au_auditorias SET 
                fecha=?, hora=?, auditor_id=?, saldo_ant=?, saldo_ant_descripcion=?, saldo_final=?, ingre_por_registrar=?, ingre_sabados=?, cta_por_pagar=?, ajuste_por_enviar=?, saldo_banco=?, consig_fondos_confia=?, gastos_mes_por_regis=?, dinero_efectivo=?, cta_por_cobrar=?, iglesia_id=?, cerrada=?, cerrada_fecha=?, updated_at=? 
                WHERE id=?;';
            DB::update($consulta, [$elem['fecha'], $elem['hora'], $elem['auditor_id'], $elem['saldo_ant'], $elem['saldo_ant_descripcion'], $elem['saldo_final'], $elem['ingre_por_registrar'], $elem['ingre_sabados'], $elem['cta_por_pagar'], $elem['ajuste_por_enviar'], $elem['saldo_banco'], $elem['consig_fondos_confia'], $elem['gastos_mes_por_regis'], $elem['dinero_efectivo'], $elem['cta_por_cobrar'], $elem['iglesia_id'], $elem['cerrada'], $elem['cerrada_fecha'], $now, $elem['id'] ]);
        }
        elseif($elem['eliminado']){
            DB::delete('DELETE FROM au_auditorias WHERE id=?;', [$elem['id']]);
        }
		
		
		return 'Sync';
	}


	public function syncLib_mensuales($elem, $now, $new_ids_auditorias)
	{
		if (!isset($elem['id'])) {
            $this->cambiar_foranea_id($elem, $new_ids_auditorias, 'auditoria_id');
            
            $consulta = 'INSERT INTO au_lib_mensuales(year, mes, periodo, orden, auditoria_id, diezmos, ofrendas, especiales, gastos, gastos_soportados, remesa_enviada, created_at, updated_at) 
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?);';
            DB::insert($consulta, [$elem['year'], $elem['mes'], $elem['periodo'], $elem['orden'], $elem['auditoria_id'], $elem['diezmos'], $elem['ofrendas'], $elem['especiales'], $elem['gastos'], $elem['gastos_soportados'], $elem['remesa_enviada'], $now, $now ]);
        }
        elseif($elem['modificado']){
            $consulta = 'UPDATE au_lib_mensuales SET 
                year=?, mes=?, periodo=?, orden=?, auditoria_id=?, diezmos=?, ofrendas=?, especiales=?, gastos=?, gastos_soportados=?, remesa_enviada=?, updated_at=? 
                WHERE id=?;';
            DB::update($consulta, [$elem['year'], $elem['mes'], $elem['periodo'], $elem['orden'], $elem['auditoria_id'], $elem['diezmos'], $elem['ofrendas'], $elem['especiales'], $elem['gastos'], $elem['gastos_soportados'], $elem['remesa_enviada'], $now, $elem['id'] ]);
        }
        elseif($elem['eliminado']){
            DB::delete('DELETE FROM au_lib_mensuales WHERE id=?;', [$elem['id']]);
        }
		
		
		return 'Sync';
	}


	public function syncLib_semanales($elem, $now)
	{
		if (!isset($elem['id'])) {
            //$this->cambiar_foranea_id($elem, $new_ids_meses, 'libro_mes_id');

            $consulta = 'INSERT INTO au_lib_semanales(libro_mes_id, diezmo_1, ofrenda_1, especial_1, diezmo_2, ofrenda_2, especial_2, diezmo_3, ofrenda_3, especial_3, diezmo_4, ofrenda_4, especial_4, diezmo_5, ofrenda_5, especial_5, diaconos_1, diaconos_2, diaconos_3, diaconos_4, diaconos_5, total_diezmos, total_ofrendas, total_especiales, por_total, created_at, updated_at)  
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);';
            DB::insert($consulta, [$elem['libro_mes_id'], $elem['diezmo_1'], $elem['ofrenda_1'], $elem['especial_1'], $elem['diezmo_2'], $elem['ofrenda_2'], $elem['especial_2'], $elem['diezmo_3'], $elem['ofrenda_3'], $elem['especial_3'], $elem['diezmo_4'], $elem['ofrenda_4'], $elem['especial_4'], $elem['diezmo_5'], $elem['ofrenda_5'], $elem['especial_5'], $elem['diaconos_1'], $elem['diaconos_2'], $elem['diaconos_3'], $elem['diaconos_4'], $elem['diaconos_5'], $elem['total_diezmos'], $elem['total_ofrendas'], $elem['total_especiales'], $elem['por_total'], $now, $now ]);
        }
        elseif($elem['modificado']){
            $consulta = 'UPDATE au_lib_semanales SET 
                libro_mes_id=?, diezmo_1=?, ofrenda_1=?, especial_1=?, diezmo_2=?, ofrenda_2=?, especial_2=?, diezmo_3=?, ofrenda_3=?, especial_3=?, diezmo_4=?, ofrenda_4=?, especial_4=?, diezmo_5=?, ofrenda_5=?, especial_5=?, diaconos_1=?, diaconos_2=?, diaconos_3=?, diaconos_4=?, diaconos_5=?, total_diezmos=?, total_ofrendas=?, total_especiales=?, por_total=?, updated_at=? 
                WHERE id=?;';
            DB::update($consulta, [$elem['libro_mes_id'], $elem['diezmo_1'], $elem['ofrenda_1'], $elem['especial_1'], $elem['diezmo_2'], $elem['ofrenda_2'], $elem['especial_2'], $elem['diezmo_3'], $elem['ofrenda_3'], $elem['especial_3'], $elem['diezmo_4'], $elem['ofrenda_4'], $elem['especial_4'], $elem['diezmo_5'], $elem['ofrenda_5'], $elem['especial_5'], $elem['diaconos_1'], $elem['diaconos_2'], $elem['diaconos_3'], $elem['diaconos_4'], $elem['diaconos_5'], $elem['total_diezmos'], $elem['total_ofrendas'], $elem['total_especiales'], $elem['por_total'], $now, $elem['id'] ]);
        }
        elseif($elem['eliminado']){
            DB::delete('DELETE FROM au_lib_semanales WHERE id=?;', [$elem['id']]);
        }
		
		
		return 'Sync';
	}


	public function syncDestinos($elem, $now)
	{
		if (!isset($elem['id'])) {
            $consulta = 'INSERT INTO au_destinos(iglesia_id, nombre, descripcion, created_at, updated_at) 
                VALUES(?,?,?,?,?,?,?,?,?,?,?,?);';
            DB::insert($consulta, [$elem['iglesia_id'], $elem['nombre'], $elem['descripcion'], $now, $now ]);
        }
        elseif($elem['modificado']){
            $consulta = 'UPDATE au_destinos SET 
                iglesia_id=?, nombre=?, descripcion=?, updated_at=? 
                WHERE id=?;';
            DB::update($consulta, [$elem['iglesia_id'], $elem['nombre'], $elem['descripcion'], $now ]);
        }
        elseif($elem['eliminado']){
            DB::delete('DELETE FROM au_destinos WHERE id=?;', [$elem['id']]);
        }
		
		
		return 'Sync';
	}


	public function syncDestinos_pagos($elem, $now)
	{
		if (!isset($elem['id'])) {
            $consulta = 'INSERT INTO au_destinos_pagos(destino_id, libro_mes_id, pago, fecha, descripcion, created_at, updated_at) 
                VALUES(?,?,?,?,?,?,?);';
            DB::insert($consulta, [$elem['destino_id'], $elem['libro_mes_id'], $elem['pago'], $elem['fecha'], $elem['descripcion'], $now, $now ]);
        }
        elseif($elem['modificado']){
            $consulta = 'UPDATE au_destinos_pagos SET 
                destino_id=?, libro_mes_id=?, pago=?, fecha=?, descripcion=?, updated_at=? 
                WHERE id=?;';
            DB::update($consulta, [$elem['destino_id'], $elem['libro_mes_id'], $elem['pago'], $elem['fecha'], $elem['descripcion'], $now ]);
        }
        elseif($elem['eliminado']){
            DB::delete('DELETE FROM au_destinos_pagos WHERE id=?;', [$elem['id']]);
        }
		
		
		return 'Sync';
	}

    
	public function syncGastos_mes($elem, $now, $new_ids_auditorias)
	{
		if (!isset($elem['id'])) {
            $this->cambiar_foranea_id($elem, $new_ids_auditorias, 'auditoria_id');
            
            $consulta = 'INSERT INTO au_gastos_mes(libro_mes_id, auditoria_id, valor, descripcion, created_at, updated_at) 
                VALUES(?,?,?,?,?,?);';

            DB::insert($consulta, [$elem['libro_mes_id'], $elem['auditoria_id'], $elem['valor'], $elem['descripcion'], $now, $now ]);
        }
        elseif($elem['modificado']){
            $consulta = 'UPDATE au_gastos_mes SET 
                libro_mes_id=?, auditoria_id=?, valor=?, descripcion=?, updated_at=? 
                WHERE id=?;';
            DB::update($consulta, [$elem['libro_mes_id'], $elem['auditoria_id'], $elem['valor'], $elem['descripcion'], $now, $elem['id'] ]);
        }
        elseif($elem['eliminado']){
            DB::delete('DELETE FROM au_gastos_mes WHERE id=?;', [$elem['id']]);
        }
		return 'Sync';
	}


	public function syncDinero_efectivo($elem, $now)
	{
		if (!isset($elem['id'])) {
            $this->cambiar_foranea_id($elem, $new_ids_auditorias, 'auditoria_id');
            
            $consulta = 'INSERT INTO au_dinero_efectivo(auditoria_id, valor, descripcion, created_at, updated_at) 
                VALUES(?,?,?,?,?,?);';
            DB::insert($consulta, [$elem['auditoria_id'], $elem['valor'], $elem['descripcion'], $now, $now ]);
        }
        elseif($elem['modificado']){
            $consulta = 'UPDATE au_dinero_efectivo SET 
                auditoria_id=?, valor=?, descripcion=?, updated_at=? 
                WHERE id=?;';
            DB::update($consulta, [$elem['auditoria_id'], $elem['valor'], $elem['descripcion'], $now, $elem['id'] ]);
        }
        elseif($elem['eliminado']){
            DB::delete('DELETE FROM au_dinero_efectivo WHERE id=?;', [$elem['id']]);
        }
		return 'Sync';
	}


    
	public function syncPreguntas($elem, $now)
	{
		if (!isset($elem['id'])) {
            $consulta = 'INSERT INTO au_preguntas(definition, tipo, option1, option2, option3, option4, created_at, updated_at) 
                VALUES(?,?,?,?,?,?,?,?);';
            DB::insert($consulta, [$elem['definition'], $elem['tipo'], $elem['option1'], $elem['option2'], $elem['option3'], $elem['option4'], $now, $now ]);
        }
        elseif($elem['modificado']){
            $consulta = 'UPDATE au_preguntas SET 
                destino_id=?, definition=?, tipo=?, option1=?, option2=?, option3=?, option4=?, updated_at=? 
                WHERE id=?;';
            DB::update($consulta, [$elem['destino_id'], $elem['definition'], $elem['option1'], $elem['option2'], $elem['option3'], $elem['option4'], $now, $elem['id'] ]);
        }
        elseif($elem['eliminado']){
            DB::delete('DELETE FROM au_preguntas WHERE id=?;', [$elem['id']]);
        }
		return 'Sync';
	}


	public function syncRespuestas($elem, $now, $new_ids_auditorias)
	{
		if (!isset($elem['id'])) {
            $this->cambiar_foranea_id($elem, $new_ids_auditorias, 'auditoria_id');
            
            $consulta = 'INSERT INTO au_respuestas(pregunta_id, auditoria_id, respuestas, created_at, updated_at) 
                VALUES(?,?,?,?,?);';
            DB::insert($consulta, [$elem['pregunta_id'], $elem['auditoria_id'], $elem['respuestas'], $now, $now ]);
        }
        elseif($elem['modificado']){
            $consulta = 'UPDATE au_respuestas SET 
                pregunta_id=?, auditoria_id=?, tipo=?, respuestas=?, updated_at=? 
                WHERE id=?;';
            DB::update($consulta, [$elem['pregunta_id'], $elem['auditoria_id'], $elem['respuestas'], $now, $elem['id'] ]);
        }
        elseif($elem['eliminado']){
            DB::delete('DELETE FROM au_respuestas WHERE id=?;', [$elem['id']]);
        }
		return 'Sync';
	}


	public function syncRecomendas($elem, $now, $new_ids_auditorias)
	{
		if (!isset($elem['id'])) {
            $this->cambiar_foranea_id($elem, $new_ids_auditorias, 'auditoria_id');

            $consulta = 'INSERT INTO au_recomendaciones(auditoria_id, hallazgo, recomendacion, justificacion, superada, fecha, fecha_respuesta, tipo, created_at, updated_at) 
                VALUES(?,?,?,?,?,?,?,?,?,?);';
            DB::insert($consulta, [$elem['auditoria_id'], $elem['hallazgo'], $elem['recomendacion'], $elem['justificacion'], $elem['superada'], $elem['fecha'], $elem['fecha_respuesta'], $elem['tipo'], $now, $now ]);
        }
        elseif($elem['modificado']){
            $consulta = 'UPDATE au_recomendaciones SET 
                auditoria_id=?, hallazgo=?, recomendacion=?, justificacion=?, superada=?, fecha=?, fecha_respuesta=?, tipo=?, updated_at=? 
                WHERE id=?;';
            DB::update($consulta, [$elem['auditoria_id'], $elem['hallazgo'], $elem['recomendacion'], $elem['justificacion'], $elem['superada'], $elem['fecha'], $elem['fecha_respuesta'], $elem['tipo'], $now, $elem['id'] ]);
        }
        elseif($elem['eliminado']){
            DB::delete('DELETE FROM au_recomendaciones WHERE id=?;', [$elem['id']]);
        }
		return 'Sync';
	}


    
    private function cambiar_foranea_id(&$elem, $new_ids, $campo){

        for ($i=0; $i < count($new_ids); $i++) { 
            if ($new_ids[$i]['row_id'] == $elem[$campo]) {
                $elem[$campo] = $new_ids[$i]['new_id'];
            }
        }
    }



}