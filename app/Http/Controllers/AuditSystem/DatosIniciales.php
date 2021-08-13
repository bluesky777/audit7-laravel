<?php namespace App\Http\Controllers\AuditSystem;

use Request;
use Hash;

use DB;
use Carbon\Carbon;

class DatosIniciales {

	public function insertarUnionAudit()
	{
		
		$uniones 	= DB::select('SELECT * from au_uniones;');
		
		if (count($uniones) == 0) {
			$now = Carbon::now('America/Bogota');
			
			$consulta = "INSERT INTO au_uniones
					(id, nombre, alias, codigo, created_at, updated_at)
				VALUES
					(1, 'UNION COLOMBIANA DEL NORTE', 'AGC111', 'AGC111', '".$now."', '".$now."')
					;";
					
			DB::insert($consulta);
			
			$consulta = "INSERT INTO au_auditorias
					(fecha, hora, iglesia_id, created_at, updated_at)
				VALUES
					('2018-10-01', '10:10:00am', 1, '".$now."', '".$now."')
					;";
					
			DB::insert($consulta);
		}
		return 'Insertando';
	}


	public function insertarAsociaciones()
	{
		
		$taxis 		= DB::select('SELECT * from au_asociaciones;');
		
		if (count($taxis) == 0) {
			$now = Carbon::now('America/Bogota');
			
			$consulta = "INSERT INTO au_asociaciones
					(id, nombre, alias, codigo, union_id, created_at, updated_at)
				VALUES
					(1, 'ASOCIACIÓN DEL ORIENTE', 'AGC811', 'AGC811', 1, '".$now."', '".$now."'),
					(2, 'ASOCIACIÓN DEL NORESTE', 'AGCN11', 'AGCN11', 1, '".$now."', '".$now."'),
					(3, 'ASOCIACIÓN DEL CENTRO ORIENTE', 'AGCT11', 'AGCT11' , 1, '".$now."', '".$now."'),
					(4, 'ASOCIACIÓN DEL CARIBE', 'AGCC11', 'AGCC11' , 1, '".$now."', '".$now."'),
					(5, 'ASOCIACIÓN DEL ATLANTICO', 'AGC211', 'AGC211' , 1, '".$now."', '".$now."'),
					(6, 'ASOCIACIÓN ISLAS', 'AGC611', 'AGC611' , 1, '".$now."', '".$now."'),
					(7, 'ASOCIACIÓN CENTRO OCCIDENTAL', 'AGCW11', 'AGCW11' , 1, '".$now."', '".$now."'),
					(8, 'ASOCIACIÓN SUR OCCIDENTAL', 'AGCS11', 'AGCS11' , 1, '".$now."', '".$now."')
					;";
					
			DB::insert($consulta);
		}
		return 'Insertando';
	}
	

	public function insertarDistritos()
	{
		
		$elems 		= DB::select('SELECT * from au_distritos;');
		
		//if (count($elems) == 0) {
		if (true) {
			$now = Carbon::now('America/Bogota');
			/*
			$consulta = "INSERT INTO au_distritos
					(id, nombre, alias, codigo, asociacion_id, pastor_id, created_at, updated_at)
				VALUES
					(1, 'Arauca Central', 'DSARAUC01', 'DSARAUC01', 2, 1, '".$now."', '".$now."'),
					(2, 'Arauca Betania', 'DSARABE01', 'DSARABE01', 2,  1, '".$now."', '".$now."'),
					(3, 'Arauquita', 'DSARAUQ01', 'DSARAUQ01', 2,  1, '".$now."', '".$now."'),
					(4, 'Arauquita Maranatha', 'DSARAUQ02', 'DSARAUQ02', 2,  1, '".$now."', '".$now."'),
					(5, 'Bethel', 'DSBETHE01', 'DSBETHE01', 2,  1, '".$now."', '".$now."'),
					(6, 'Cúcuta Central', 'DSCUCUC01', 'DSCUCUC01', 2,  1, '".$now."', '".$now."'),
					(7, 'Canaán', 'DSCANAA01', 'DSCANAA01', 2,  1, '".$now."', '".$now."'),
					(8, 'Fortul', 'DSFORTU01', 'DSFORTU01', 2,  1, '".$now."', '".$now."'),
					(9, 'Juan Atalaya', 'DSJUANA01', 'DSJUANA01', 2,  1, '".$now."', '".$now."'),
					(10, 'Libertad', 'DSLIBER01', 'DSLIBER01', 2,  1, '".$now."', '".$now."'),
					(11, 'Nuevo Caranal', 'DSCARAN01', 'DSCARAN01', 2,  1, '".$now."', '".$now."'),
					(12, 'Ocaña', 'DSOCAÑA01', 'DSOCAÑA01', 2,  1, '".$now."', '".$now."'),
					(13, 'Palestina', 'DSPALES01', 'DSPALES01', 2,  1, '".$now."', '".$now."'),
					(14, 'Pamplona', 'DSPAMPL01', 'DSPAMPL01', 2,  1, '".$now."', '".$now."'),
					(15, 'Patios', 'DSPATIO01', 'DSPATIO01', 2,  1, '".$now."', '".$now."'),
					(16, 'Pueblo Nuevo', 'DSPUEBL01', 'DSPUEBL01', 2,  1, '".$now."', '".$now."'),
					(17, 'Redención', 'DSREDEN01', 'DSREDEN01', 2,  1, '".$now."', '".$now."'),
					(18, 'Renacer', 'DSRENAC01', 'DSRENAC01', 2,  1, '".$now."', '".$now."'),
					(19, 'Cubará', 'DSREDCU01', 'DSREDCU01', 2,  1, '".$now."', '".$now."'),
					(20, 'Saravena Central', 'DSSARAV01', 'DSSARAV01', 2,  1, '".$now."', '".$now."'),
					(21, 'Tame Central', 'DSTAMEA01', 'DSTAMEA01', 2,  1, '".$now."', '".$now."'),
					(22, 'Tame Oriental', 'DSTAMET01', 'DSTAMET01', 2,  1, '".$now."', '".$now."'),
					(23, 'Tibú', 'DSTIBUN01', 'DSTIBUN01', 2,  1, '".$now."', '".$now."'),
					(24, 'Cúcuta Sión', 'DSSIONA01', 'DSSIONA01', 2,  1, '".$now."', '".$now."'),
					(25, 'Vichada', 'DSVICHA01', 'DSVICHA01', 2,  1, '".$now."', '".$now."'),
					(26, 'Getsemani', 'DSVILGE01', 'DSVILGE01', 2,  1, '".$now."', '".$now."'),
					(27, 'Villa del Rosario', 'DSVILLA01', 'DSVILLA01', 2,  1, '".$now."', '".$now."'),
					(28, 'Tame Enmanuel', 'DSTAMEE01', 'DSTAMEE01', 2,  1, '".$now."', '".$now."')
				
					;";
			*/
			$consulta = "INSERT INTO au_distritos
					(id, nombre, alias, codigo, asociacion_id, pastor_id, created_at, updated_at)
				VALUES
					(29, 'Arauca Central', 'DSARAUC01', 'DSARAUC01', 2, 1, '".$now."', '".$now."'),
					(30, 'Arauca Betania', 'DSARABE01', 'DSARABE01', 2,  1, '".$now."', '".$now."'),
					(31, 'Arauquita', 'DSARAUQ01', 'DSARAUQ01', 2,  1, '".$now."', '".$now."'),
					(32, 'Arauquita Maranatha', 'DSARAUQ02', 'DSARAUQ02', 2,  1, '".$now."', '".$now."'),
					(33, 'Bethel', 'DSBETHE01', 'DSBETHE01', 2,  1, '".$now."', '".$now."'),
					(34, 'Cúcuta Central', 'DSCUCUC01', 'DSCUCUC01', 2,  1, '".$now."', '".$now."'),
					(35, 'Canaán', 'DSCANAA01', 'DSCANAA01', 2,  1, '".$now."', '".$now."'),
					(36, 'Fortul', 'DSFORTU01', 'DSFORTU01', 2,  1, '".$now."', '".$now."'),
					(37, 'Juan Atalaya', 'DSJUANA01', 'DSJUANA01', 2,  1, '".$now."', '".$now."'),
					(38, 'Libertad', 'DSLIBER01', 'DSLIBER01', 2,  1, '".$now."', '".$now."'),
					(39, 'Nuevo Caranal', 'DSCARAN01', 'DSCARAN01', 2,  1, '".$now."', '".$now."'),
					(40, 'Ocaña', 'DSOCAÑA01', 'DSOCAÑA01', 2,  1, '".$now."', '".$now."'),
					(41, 'Palestina', 'DSPALES01', 'DSPALES01', 2,  1, '".$now."', '".$now."'),
					(42, 'Pamplona', 'DSPAMPL01', 'DSPAMPL01', 2,  1, '".$now."', '".$now."'),
					(43, 'Patios', 'DSPATIO01', 'DSPATIO01', 2,  1, '".$now."', '".$now."'),
					(44, 'Pueblo Nuevo', 'DSPUEBL01', 'DSPUEBL01', 2,  1, '".$now."', '".$now."'),
					(45, 'Redención', 'DSREDEN01', 'DSREDEN01', 2,  1, '".$now."', '".$now."'),
					(46, 'Renacer', 'DSRENAC01', 'DSRENAC01', 2,  1, '".$now."', '".$now."'),
					(47, 'Cubará', 'DSREDCU01', 'DSREDCU01', 2,  1, '".$now."', '".$now."'),
					(48, 'Saravena Central', 'DSSARAV01', 'DSSARAV01', 2,  1, '".$now."', '".$now."'),
					(49, 'Tame Central', 'DSTAMEA01', 'DSTAMEA01', 2,  1, '".$now."', '".$now."'),
					(50, 'Tame Oriental', 'DSTAMET01', 'DSTAMET01', 2,  1, '".$now."', '".$now."'),
					(51, 'Tibú', 'DSTIBUN01', 'DSTIBUN01', 2,  1, '".$now."', '".$now."'),
					(52, 'Cúcuta Sión', 'DSSIONA01', 'DSSIONA01', 2,  1, '".$now."', '".$now."'),
					(53, 'Vichada', 'DSVICHA01', 'DSVICHA01', 2,  1, '".$now."', '".$now."'),
					(54, 'Getsemani', 'DSVILGE01', 'DSVILGE01', 2,  1, '".$now."', '".$now."'),
					(55, 'Villa del Rosario', 'DSVILLA01', 'DSVILLA01', 2,  1, '".$now."', '".$now."'),
					(56, 'Tame Enmanuel', 'DSTAMEE01', 'DSTAMEE01', 2,  1, '".$now."', '".$now."')
				
					;";
			DB::insert($consulta);
			
		}
		return 'Insertando';
	}


	public function insertarUsuarios()
	{
		
		$users 		= DB::select('SELECT * from au_users;');
		
		if (count($users) == 0) {
		
			$now = Carbon::now('America/Bogota');
			
			$consulta = "INSERT INTO au_users
					(id, nombres, apellidos, email, username, password, tipo, sexo, union_id, asociacion_id, created_at, updated_at)
				VALUES
					(1, 'Joseth D', 'Guerrero', 'davidguerrero777@gmail.com', 'joseth', '456', 'Admin', 'M', 1, 2, '".$now."', '".$now."'),
					(2, 'Gustavo', 'Pérez', null, 'gustavo',  '123', 'Auditor', 'M', null, 2, '".$now."', '".$now."'),
					(3, 'Daniel', 'Grandas', null, 'daniel',  '123', 'Pastor', 'M', null, 2, '".$now."', '".$now."'),
					(4, 'Edilson', 'Marquez', null, 'edilson',  '123', 'Tesorero', 'M', null, 2, '".$now."', '".$now."'),
					(5, 'Cesar', 'Saldariaga', null, 'cesar',  '123', 'Tesorero asociación', 'M', null, 2, '".$now."', '".$now."'),
					(6, 'Felix', 'Díaz', null, 'felix',  '123', 'Pastor', 'M', null, 2, '".$now."', '".$now."')
					;";
					
			DB::insert($consulta);
		}
		return 'Insertando';
	}



	public function insertarIglesias()
	{
		
		$iglesias 		= DB::select('SELECT * from au_iglesias;');
		
		//if (count($iglesias) == 0) {
		if (true) {
			$now = Carbon::now('America/Bogota');
			
			$consulta = "INSERT INTO au_iglesias
					(nombre, alias, codigo, distrito_id, created_at, updated_at)
				VALUES
					('Alfa y Omega -  Pueblo Nuevo', 'CALFAY01', 'CALFAY01', 44,'".$now."', '".$now."'),
					('Alfa y Omega -  Redencion', 'CALFAY02', 'CALFAY02',  45,'".$now."', '".$now."'),
					('Alfa y Omega - Tame Central', 'CALFAY03', 'CALFAY03',  49, '".$now."', '".$now."'),
					('Alfa y Omega - Tame Central', 'CALFAY03', 'CALFAY03',  37,'".$now."', '".$now."'),
					('Alfa y Omega - D. Atalaya', 'CALFAY04', 'CALFAY04',  31,'".$now."', '".$now."'),
					('Alfa y Omega - Arauquita Central', 'CALFAY05', 'CALFAY05',  29, '".$now."', '".$now."'),
					('Arauca -  Arauca Central', 'CARAUC01', 'CARAUC01',  31,'".$now."', '".$now."'),
					('Arauquita -  Arauquita', 'CARAUQ01', 'CARAUQ01',  31,'".$now."', '".$now."'),
					('Bendición- Reiner -  Arauquita ', 'CBENDI01', 'CBENDI01',  31,'".$now."', '".$now."'),
					( 'Berea -  Vichada', 'CBEREA01', 'CBEREA01',  53, '".$now."', '".$now."'),
					( 'Betania Malvinas -  Nuevo Caranal', 'CBETAN01', 'CBETAN01',  39,'".$now."', '".$now."'),
					( 'Betania -  Arauca Betania', 'CBETAN02', 'CBETAN02',  30,'".$now."', '".$now."'),
					( 'Betania -  Cubará', 'CBETAN03', 'CBETAN03',  47,'".$now."', '".$now."'),
					( 'Betenia -  Juan Atalaya', 'CBETAN04', 'CBETAN04',  37,'".$now."', '".$now."'),
					( 'Betania - D. Fortul', 'CBETAN05', 'CBETAN05',  36,'".$now."', '".$now."'),
					( 'Betania - Palestina', 'CBETAN06', 'CBETAN06',  41,'".$now."', '".$now."'),
					( 'Bethel -  Bethel', 'CBETHE01', 'CBETHE01',  33,'".$now."', '".$now."'),
					( 'Bethel -  Cucuta Sión', 'CBETHE02', 'CBETHE02',  52, '".$now."', '".$now."'),
					( 'Bethel - Abrego D. Ocaña', 'CBETHE03', 'CBETHE03',  40,'".$now."', '".$now."'),
					( 'Brasilia -  Arauquita Maranatha', 'CBRASI01', 'CBRASI01',  32,'".$now."', '".$now."'),
					( 'Buenos Aires -  Pueblo Nuevo', 'CBUENO01', 'CBUENO01',  44,'".$now."', '".$now."'),
					( 'El Buen Pastor -  Pueblo Nuevo', 'CBUENP01', 'CBUENP01',  44,'".$now."', '".$now."'),
					( 'Cabssel -  D. Tibú', 'CCABSS01', 'CCABSS01',  50, '".$now."', '".$now."'),
					( 'Caled -  Juan Atalaya', 'CCALED01', 'CCALED01',  37,'".$now."', '".$now."'),
					( 'Caleb - Caranal', 'CCALEB01', 'CCALEB01',  39,'".$now."', '".$now."'),
					( 'Canaan -  Saravena Bethel', 'CCANAA01', 'CCANAA01',  33,'".$now."', '".$now."'),
					( 'Canaan -  Cubará', 'CCANAA02', 'CCANAA02',  47,'".$now."', '".$now."'),
					( 'Canaan -  D. Patios', 'CCANAA03', 'CCANAA03',  43,'".$now."', '".$now."'),
					('Canaan -  D. Canaan', 'CCANAA04', 'CCANAA04',  35,'".$now."', '".$now."'),
					('Canaan - D. Tame Central', 'CCANAA05', 'CCANAA05',  49, '".$now."', '".$now."'),
					('Canaan - D. Ocaña', 'CCANAA06', 'CCANAA06',  40,'".$now."', '".$now."'),
					('Caño Cristal -  Fortul', 'CCAÑOC01', 'CCAÑOC01',  36,'".$now."', '".$now."'),
					('Central de Cúcuta -  Cucuta Central', 'CCENTR01', 'CCENTR01',  34,'".$now."', '".$now."'),
					('Casa de Oración - D. Renacer', 'CCASAO01', 'CCASAO01',  46,'".$now."', '".$now."'),
					('Costa Hermosa -  Arauca Betania', 'CCOSTA01', 'CCOSTA01',  30,'".$now."', '".$now."'),
					('Cravonorte -  Arauca Betania', 'CCRAVO01', 'CCRAVO01',  30,'".$now."', '".$now."'),
					('Cristo la Esperanza -  Getsemani', 'CCRIST01', 'CCRIST01',  54, '".$now."', '".$now."'),
					('Cristo Redentor - Patios', 'CCRIRE01', 'CCRIRE01',  43,'".$now."', '".$now."'),
					('Ebenezer -  Tame Central', 'CEBENE01', 'CEBENE01',  49, '".$now."', '".$now."'),
					('Ebenezer-Oasis -  Arauquita Maranatha', 'CEBENE02', 'CEBENE02',  32,'".$now."', '".$now."'),
					('Ebenezer -  Palestina', 'CEBENE03', 'CEBENE03',  41,'".$now."', '".$now."'),
					('Ebenezer -  Redencion', 'CEBENE04', 'CEBENE04',  45,'".$now."', '".$now."'),
					( 'Ebenezer Chitaga - D Pamplona', 'CEBENE05', 'CEBENE05',  42,'".$now."', '".$now."'),
					( 'Eden -  Arauquita Maranatha', 'CEDENI01', 'CEDENI01',  32,'".$now."', '".$now."'),
					( 'Eden -  Saravena Central', 'CEDENI02', 'CEDENI02',  48, '".$now."', '".$now."'),
					( 'Eden -  Villa Del Rosario', 'CEDENI03', 'CEDENI03',  55, '".$now."', '".$now."'),
					( 'Eden -  Ocaña', 'CEDENI04', 'CEDENI04',  40,'".$now."', '".$now."'),
					( 'Eden Brisas - Tame Central', 'CEDENT01', 'CEDENT01',  49, '".$now."', '".$now."'),
					( 'Efeso -  Tame Oriental', 'CEFESO01', 'CEFESO01',  50, '".$now."', '".$now."'),
					( 'Emaus -  Pamplona', 'CEMAUS01', 'CEMAUS01',  42,'".$now."', '".$now."'),
					( 'Emaus -  Patios', 'CEMAUS02', 'CEMAUS02',  43,'".$now."', '".$now."'),
					( 'Emaus -  D. Cubará', 'CEMAUS03', 'CEMAUS03',  47,'".$now."', '".$now."'),
					( 'Emaus - D. Bethel', 'CEMAUS04', 'CEMAUS04',  33,'".$now."', '".$now."'),
					( 'Embajadores - Cúcuta Central', 'CEMBAJ01', 'CEMBAJ01',  34,'".$now."', '".$now."'),
					( 'Enacore - Tame central', 'CENACO01', 'CENACO01',  49, '".$now."', '".$now."'),
					( 'Enmanuel -  Tame Enmanuel', 'CENMAN01', 'CENMAN01',  56, '".$now."', '".$now."'),
					( 'Enmanuel-Pesquera -  Arauquita', 'CENMAN02', 'CENMAN02',  31,'".$now."', '".$now."'),
					( 'Enmanuel -  Redencion', 'CENMAN03', 'CENMAN03',  45,'".$now."', '".$now."'),
					( 'Enmanuel -  Juan Atalaya', 'CENMAN04', 'CENMAN04',  37,'".$now."', '".$now."'),
					( 'Enmanuel - Arauquita  Maranatha', 'CENMAN06', 'CENMAN06',  32,'".$now."', '".$now."'),
					( 'Enmanuel - Pueblo Nuevo', 'CENMAN07', 'CENMAN07',  44,'".$now."', '".$now."'),
					( 'Esmirna - Tame Oriental', 'CESMIR01', 'CESMIR01',  50, '".$now."', '".$now."'),
					( 'Estrella del Amanecer -  Cucuta Central', 'CESTRE01', 'CESTRE01',  34,'".$now."', '".$now."'),
					( 'Estrella de Jacob - Arauca Central', 'CESTRE02', 'CESTRE02',  29, '".$now."', '".$now."'),
					( 'Filadelfia -  Cubará', 'CFILAD02', 'CFILAD02',  47,'".$now."', '".$now."'),
					( 'Filadelfia -  D. Patios', 'CFILAD03', 'CFILAD03',  43,'".$now."', '".$now."'),
					( 'Filadelfia -  Juan Atalaya', 'CFILAD04', 'CFILAD04',  37,'".$now."', '".$now."'),
					( 'Filadefia -  Ocaña', 'CFILAD05', 'CFILAD05',  40,'".$now."', '".$now."'),
					( 'Filadelfia - Redención', 'CFILAD06', 'CFILAD06',  45,'".$now."', '".$now."'),
					( 'Fortul -  Fortul', 'CFORTU01', 'CFORTU01',  36,'".$now."', '".$now."'),
					( 'Fuerte Pregon -  Villa Del Rosario', 'CFUERT01', 'CFUERT01',  55, '".$now."', '".$now."'),
					( 'Galaad -  Patios', 'CGALAA01', 'CGALAA01',  43,'".$now."', '".$now."'),
					( 'Galilea -  Pamplona', 'CGALIL01', 'CGALIL01',  42,'".$now."', '".$now."'),
					( 'Genezareth -  Bethel', 'CGENEZ01', 'CGENEZ01',  33,'".$now."', '".$now."'),
					( 'Genezareth -  Villa Del Rosario', 'CGENEZ02', 'CGENEZ02',  55, '".$now."', '".$now."'),
					( 'Gerisim - Villa del Rosario', 'CGERIS01', 'CGERIS01',  55, '".$now."', '".$now."'),
					( 'Gerizin - Palestina', 'CGERIZ01', 'CGERIZ01',  41,'".$now."', '".$now."'),
					( 'Getsemany -  Nuevo Caranal', 'CGETSE01', 'CGETSE01',  39,'".$now."', '".$now."'),
					( 'Getsemany -  Bethel', 'CGETSE02', 'CGETSE02',  33,'".$now."', '".$now."'),
					( 'Getsemany -  Cubará', 'CGETSE03', 'CGETSE03',  47,'".$now."', '".$now."'),
					( 'Getsemani -  Getsemani', 'CGETSE04', 'CGETSE04',  54, '".$now."', '".$now."'),
					( 'Getsemany -  Juan Atalaya', 'CGETSE05', 'CGETSE05',  37,'".$now."', '".$now."'),
					( 'Getsemani - Ocaña', 'CGETSE06', 'CGETSE06',  40,'".$now."', '".$now."'),
					( 'Genezaret - Atalaya', 'CGENEZ03', 'CGENEZ03',  37,'".$now."', '".$now."'),
					( 'Glondrinas -  Palestina', 'CGLOND01', 'CGLOND01',  41,'".$now."', '".$now."'),
					( 'Gran Faro Toledo - Pamplona', 'CGRAFA01', 'CGRAFA01',  42,'".$now."', '".$now."'),
					( 'Hashen-Peralonso -  Arauquita Maranatha', 'CHASHE01', 'CHASHE01',  32,'".$now."', '".$now."'),
					('Heraldo -  Bethel', 'CHERAL0', 'CHERAL0',  33,'".$now."', '".$now."'),
					('Hebron - Tame Central', 'CHEBRO01', 'CHEBRO01',  49, '".$now."', '".$now."'),
					('Hebron - Bethel', 'CHEBRO02', 'CHEBRO02',  33,'".$now."', '".$now."'),
					('Horeb, La Hermosa -  Oriental', 'CHOREB01', 'CHOREB01',  50, '".$now."', '".$now."'),
					('Horeb- San Luis -  Arauquita Maranatha', 'CHOREB02', 'CHOREB02',  32,'".$now."', '".$now."'),
					('Horeb -  Saravena Bethel', 'CHOREB03', 'CHOREB03',  33,'".$now."', '".$now."'),
					('Horeb -  Patios', 'CHOREB04', 'CHOREB04',  43,'".$now."', '".$now."'),
					('Gr. Horeb - D. Libertad', 'CHOREB05', 'CHOREB05',  38,'".$now."', '".$now."'),
					('Grupo Horeb - Cubará', 'CHOREB06', 'CHOREB06',  47,'".$now."', '".$now."'),
					('Horeb - Palestina', 'CHOREB08', 'CHOREB08',  41,'".$now."', '".$now."'),
					('Jehova Jireth - Vichada', 'CJEHOV01', 'CJEHOV01',  53, '".$now."', '".$now."'),
					('Jerico -  D. Renacer', 'CJERIC01', 'CJERIC01',  46,'".$now."', '".$now."'),
					( 'Jerusalen -  Tame Central', 'CJERUS01', 'CJERUS01',  49, '".$now."', '".$now."'),
					( 'Jerusalen -  Palestina', 'CJERUS02', 'CJERUS02',  41,'".$now."', '".$now."'),
					( 'Jerusalen -  Cucuta Sión', 'CJERUS03', 'CJERUS03',  52, '".$now."', '".$now."'),
					( 'Jerusalen - Pueblo Nuevo', 'CJERUS06', 'CJERUS06',  44,'".$now."', '".$now."'),
					( 'Jerusalen -  Patios', 'CJERUS04', 'CJERUS04',  43,'".$now."', '".$now."'),
					( 'Jerusalen Holanda - Tame Central', 'CJERHO01', 'CJERHO01',  49, '".$now."', '".$now."'),
					( 'Jezreel - Libertad', 'CJEZRE01', 'CJEZRE01',  38,'".$now."', '".$now."'),
					( 'Jerusalen - Cubará', 'CJERUS07', 'CJERUS07',  47,'".$now."', '".$now."'),
					( 'Jireh - Arauquita', 'CJIREH01', 'CJIREH01',  31,'".$now."', '".$now."'),
					( 'La Esperanza - Getsemani', 'CJESUS05', 'CJESUS05',  54, '".$now."', '".$now."'),
					( 'Jehova Nisi - D. Pueblo Nuevo', 'CJOHOV01', 'CJOHOV01',  44,'".$now."', '".$now."'),
					( 'Jordan -  Pueblo Nuevo', 'CJORDA01', 'CJORDA01',  44,'".$now."', '".$now."'),
					( 'La Roca -  Libertad', 'CLAROC01', 'CLAROC01',  38,'".$now."', '".$now."'),
					( 'Legado -  Cucuta Sión', 'CLEGAD01', 'CLEGAD01',  52, '".$now."', '".$now."'),
					( 'La Hermosa - Fortul', 'CLAHER01', 'CLAHER01',  36,'".$now."', '".$now."'),
					( 'La Hermosa - Cúcuta Sión', 'CLAHER02', 'CLAHER02',  52, '".$now."', '".$now."'),
					( 'Mahanain -  Arauquita Maranatha', 'CMAHAN01', 'CMAHAN01',  32,'".$now."', '".$now."'),
					( 'Mahanain -  Cubará', 'CMAHAN02', 'CMAHAN02',  47,'".$now."', '".$now."'),
					( 'Mahanain -  Pamplona', 'CMAHAN03', 'CMAHAN03',  42,'".$now."', '".$now."'),
					( 'Manantial - D. Cubará', 'CMANAN02', 'CMANAN02',  47,'".$now."', '".$now."'),
					( 'Maranatha -  Arauquita Maranatha', 'CMARAN01', 'CMARAN01',  32,'".$now."', '".$now."'),
					( 'Maranatha -  Palestina', 'CMARAN02', 'CMARAN02',  41,'".$now."', '".$now."'),
					( 'Maranatha -  Juan Atalaya', 'CMARAN03', 'CMARAN03',  37,'".$now."', '".$now."'),
					( 'Maranatha -  Vichada', 'CMARAN04', 'CMARAN04',  53, '".$now."', '".$now."'),
					( 'Maranatha -  Ocaña', 'CMARAN05', 'CMARAN05',  40,'".$now."', '".$now."'),
					( 'Orion - Tame Oriental', 'CMARAN06', 'CMARAN06',  50, '".$now."', '".$now."'),
					( 'Maranatha - Arauca Betenia', 'CMARAN07', 'CMARAN07',  30,'".$now."', '".$now."'),
					( 'Mies -  Nuevo Caranal', 'CMIESI01', 'CMIESI01',  39,'".$now."', '".$now."'),
					( 'Monte de los Olivos -  Palestina', 'CMONTE01', 'CMONTE01',  41,'".$now."', '".$now."'),
					( 'Monte Carmelo -  Cubará', 'CMONTE02', 'CMONTE02',  47,'".$now."', '".$now."'),
					( 'Monte Alto -  D. Tibú', 'CMONTE03', 'CMONTE03',  50, '".$now."', '".$now."'),
					( 'Moriach - Pueblo Nuevo', 'CMORIA01', 'CMORIA01',  44,'".$now."', '".$now."'),
					( 'Nueva Jerusalen  -  Pamplona', 'CNUEVA01', 'CNUEVA01',  42,'".$now."', '".$now."'),
					( 'Nueva Jerusalen -  Getsemani ', 'CNUEVA02', 'CNUEVA02',  54, '".$now."', '".$now."'),
					( 'Nueva Jerusalen - Atalaya ', 'CNUEJE01', 'CNUEJE01',  37,'".$now."', '".$now."'),
					( 'Nueva Esperanza -  Libertad ', 'CNUEVA03', 'CNUEVA03',  38,'".$now."', '".$now."'),
					( 'Nueva Galilea - Cúcuta Sión ', 'CNVAGA01', 'CNVAGA01',  52, '".$now."', '".$now."'),
					( 'Nueva Esperanza - Ocaña ', 'CNUEVA04', 'CNUEVA04',  40,'".$now."', '".$now."'),
					( 'Nuevo Eden -  Nuevo Caranal ', 'CNUEVO01', 'CNUEVO01',  39,'".$now."', '".$now."'),
					( 'Ocaña -  Ocaña ', 'COCAÑA01', 'COCAÑA01',  40,'".$now."', '".$now."'),
					( 'Olivares -  Pueblo Nuevo ', 'COLIVA01', 'COLIVA01',  44,'".$now."', '".$now."'),
					( 'Orión -  Fortul ', 'CORION01', 'CORION01',  36,'".$now."', '".$now."'),
					( 'Orion -  Cúbará ', 'CORION02', 'CORION02',  47,'".$now."', '".$now."'),
					( 'Ovejas -  Libertad ', 'COVEJA01', 'COVEJA01',  38,'".$now."', '".$now."'),
					( 'Paraiso Pachelly -  D. Tibú ', 'CPACHE01', 'CPACHE01',  50, '".$now."', '".$now."'),
					( 'Palestina -  Palestina ', 'CPALES01', 'CPALES01',  41,'".$now."', '".$now."'),
					( 'Palestina -  Libertad ', 'CPALES02', 'CPALES02',  38,'".$now."', '".$now."'),
					( 'Paraiso -  Arauca Betania', 'CPARAI01', 'CPARAI01',  30,'".$now."', '".$now."'),
					( 'Paraiso -  Palestina', 'CPARAI02', 'CPARAI02',  41,'".$now."', '".$now."'),
					( 'Paraiso Bochalema -  Patios', 'CPARAI03', 'CPARAI03',  43,'".$now."', '".$now."'),
					( 'Paraiso -  Getsemani', 'CPARAI04', 'CPARAI04',  54, '".$now."', '".$now."'),
					( 'Paraiso - D. Bethel', 'CPARAI05', 'CPARAI05',  33,'".$now."', '".$now."'),
					( 'Peniel -  Bethel', 'CPENIE01', 'CPENIE01',  33,'".$now."', '".$now."'),
					( 'Peniel -  Cúbará', 'CPENIE02', 'CPENIE02',  47,'".$now."', '".$now."'),
					( 'Peniel -  Libertad', 'CPENIE03', 'CPENIE03',  38,'".$now."', '".$now."'),
					( 'Peniel Simon - Pamplona', 'CPENIEL04', 'CPENIEL04',  42,'".$now."', '".$now."'),
					( 'Peniel - Arauquita central', 'CPENIE05', 'CPENIE05',  31,'".$now."', '".$now."'),
					( 'Peniel - Tame Oriental', 'CPENIE06', 'CPENIE06',  50, '".$now."', '".$now."'),
					( 'Puerto Rico - Redencion', 'CPTORI01', 'CPTORI01',  45,'".$now."', '".$now."'),
					( 'Puerto Rondon -  Tame Central', 'CPUERT01', 'CPUERT01',  49, '".$now."', '".$now."'),
					( 'Ragonvalia - Villa del Rosario', 'CRAGON01', 'CRAGON01',  55, '".$now."', '".$now."'),
					( 'Redención -  Redencion', 'CREDEN01', 'CREDEN01',  45,'".$now."', '".$now."'),
					( 'Redención -  Ocaña', 'CREDEN02', 'CREDEN02',  40,'".$now."', '".$now."'),
					( 'Redención - Cúcuta Sión', 'CREDEN03', 'CREDEN03',  52, '".$now."', '".$now."'),
					( 'Refugio -  D. Atalaya', 'CREFUG01', 'CREFUG01',  37,'".$now."', '".$now."'),
					( 'Remanente -  Nuevo Caranal', 'CREMAN01', 'CREMAN01',  39,'".$now."', '".$now."'),
					( 'Filial Remanente - D. Arauquita', 'CREMAN02', 'CREMAN02',  31,'".$now."', '".$now."'),
					( 'Renacer-Maporita -  Arauquita', 'CRENAC01', 'CRENAC01',  31,'".$now."', '".$now."'),
					( 'Renacer -  Redención', 'CRENAC02', 'CRENAC02',  45,'".$now."', '".$now."'),
					( 'Renacer -  D. Renacer', 'CRENAC03', 'CRENAC03',  46,'".$now."', '".$now."'),
					( 'Renacer -  Vichada', 'CRENAC04', 'CRENAC04',  53, '".$now."', '".$now."'),
					( 'Renacer -  Ocaña', 'CRENAC05', 'CRENAC05',  40,'".$now."', '".$now."'),
					( 'Renacer - Nuevo Caranal', 'CRENAC07', 'CRENAC07',  39,'".$now."', '".$now."'),
					( 'Renacer - Tame Oriental', 'CRENAC06', 'CRENAC06',  50, '".$now."', '".$now."'),
					( 'Renacer - D. Arauca Central', 'CRENAC08', 'CRENAC08',  29, '".$now."', '".$now."'),
					( 'Renacer - D. Libertad', 'CRENAC09', 'CRENAC09',  38,'".$now."', '".$now."'),
					( 'Renacer - Saravena Central', 'CRENAC10', 'CRENAC10',  48, '".$now."', '".$now."'),
					( 'Renacer - Arauquita Maranatha', 'CRENAC011', 'CRENAC011',  32,'".$now."', '".$now."'),
					( 'Salen -  Tame Oriental', 'CSALEN01', 'CSALEN01',  50, '".$now."', '".$now."'),
					( 'Sama -  Nuevo Caranal', 'CSAMAI01', 'CSAMAI01',  39,'".$now."', '".$now."'),
					( 'Siloe -  Pamplona', 'CSAMAR01', 'CSAMAR01',  42,'".$now."', '".$now."'),
					( 'Samaritana -  Ocaña', 'CSAMAR02', 'CSAMAR02',  40,'".$now."', '".$now."'),
					( 'Horeb - San francisco  - D. Nuevo Caranal', 'CHOREB07', 'CHOREB07',  39,'".$now."', '".$now."'),
					( 'Saravena Central -  Saravena Central', 'CSARAV01', 'CSARAV01',  48, '".$now."', '".$now."'),
					( 'Salvación -  Ocaña', 'CSALVA01', 'CSALVA01',  40,'".$now."', '".$now."'),
					( 'Sardis - D. Sión', 'CZARDI01', 'CZARDI01',  52, '".$now."', '".$now."'),
					( 'Sarón -  Arauca Central', 'CSARON01', 'CSARON01',  29, '".$now."', '".$now."'),
					( 'Saron -  Palestina', 'CSARON02', 'CSARON02',  41,'".$now."', '".$now."'),
					( 'Saron -  Bethel', 'CSARON03', 'CSARON03',  33,'".$now."', '".$now."'),
					( 'Sarón - Pamplona', 'CSARON04', 'CSARON04',  42,'".$now."', '".$now."'),
					( 'Shaday - Bethel', 'CSHADA01', 'CSHADA01',  33,'".$now."', '".$now."'),
					( 'Shalon, Panama -  Pueblo Nuevo', 'CSHALO01', 'CSHALO01',  44,'".$now."', '".$now."'),
					( 'Senderos - Villa del Rosario', 'CSENDE01', 'CSENDE01',  55, '".$now."', '".$now."'),
					( 'Shalon -  Bethel', 'CSHALO02', 'CSHALO02',  33,'".$now."', '".$now."'),
					( 'Valle de Sarón - D. Cucuta Central', 'CSHALO03', 'CSHALO03',  34,'".$now."', '".$now."'),
					( 'Siloe -  Tame Central', 'CSILOE01', 'CSILOE01',  49, '".$now."', '".$now."'),
					( 'Siloe -  Arauquita Maranatha', 'CSILOE02', 'CSILOE02',  32,'".$now."', '".$now."'),
					( 'Siloe -  Bethel', 'CSILOE03', 'CSILOE03',  33,'".$now."', '".$now."'),
					( 'Siloe - Ocaña', 'CSILOE04', 'CSILOE04',  40,'".$now."', '".$now."'),
					( 'Sinai -  Cubará', 'CSINAI01', 'CSINAI01',  47,'".$now."', '".$now."'),
					( 'Sinai -  Patios', 'CSINAI02', 'CSINAI02',  43,'".$now."', '".$now."'),
					( 'Sinai - D. Pueblo Nuevo', 'CSINAI03', 'CSINAI03',  44,'".$now."', '".$now."'),
					( 'Sinai - Vichada', 'CSINAI04', 'CSINAI04',  53, '".$now."', '".$now."'),
					( 'Grupo Sion - Cubará', 'CSIONG01', 'CSIONG01',  47,'".$now."', '".$now."'),
					( 'Sion -  Bethel', 'CSIONI01', 'CSIONI01',  33,'".$now."', '".$now."'),
					( 'Sion -  Cucuta Sión', 'CSIONI02', 'CSIONI02',  52, '".$now."', '".$now."'),
					( 'Sión -  Ocaña', 'CSIONI03', 'CSIONI03',  40,'".$now."', '".$now."'),
					( 'Smirna -  Cucuta Central', 'CSMIRN01', 'CSMIRN01',  34,'".$now."', '".$now."'),
					('Soledad -  D. Tibú', 'CSOLED01', 'CSOLED01',  50, '".$now."', '".$now."'),
					('Sucot  -  Fortul', 'CSUCOT01', 'CSUCOT01',  36,'".$now."', '".$now."'),
					('El Caucho - D. Arauquita', 'CCAUCH01', 'CCAUCH01',  31,'".$now."', '".$now."'),
					('Amanecer - Nuevo Caranal', 'CAMANE01', 'CAMANE01',  39,'".$now."', '".$now."'),
					( 'Grupo Tabiro - D. Cúcuta Central', 'CTABIR01', 'CTABIR01',  34,'".$now."', '".$now."'),
					( 'Grupo Torcoroma 3 - D. Libertad', 'CTORCO03', 'CTORCO03',  38,'".$now."', '".$now."'),
					( 'Tres Angeles -  Villa Del Rosario', 'CTRESA01', 'CTRESA01',  55, '".$now."', '".$now."'),
					('El Vergel -  Pueblo Nuevo', 'CVERGE01', 'CVERGE01',  44,'".$now."', '".$now."'),
					('Villa de Emaus -  Tame Central', 'CVILLA01', 'CVILLA01',  49, '".$now."', '".$now."'),
					('Voz de Salvación -  Palestina', 'CVOZDE01', 'CVOZDE01',  41,'".$now."', '".$now."'),
					('Zulia  -  D. Atalaya', 'CZULIA01', 'CZULIA01',  37,'".$now."', '".$now."')
					;";
					
			DB::insert($consulta);
		}
		return 'Insertando';
	}


}