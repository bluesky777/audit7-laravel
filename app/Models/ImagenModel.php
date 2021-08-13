<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use DB;
use File;


class ImagenModel extends Model {
	protected $fillable = [];

	protected $table = 'au_images';

	use SoftDeletes;
	protected $softDelete = true;




	public static function DatosImagen($imagen_id, $user_id)
	{
		$datos_imagen = null;


		$consulta = 'SELECT u.nombres, i.nombre, u.apellidos, u.sexo, i.id  FROM images i 
				inner join users u on u.imagen_id=i.id and i.id=:imagen_id';

		$oficiales = DB::select($consulta, [':imagen_id'	=> $imagen_id]);


		/*$consulta = 'SELECT u.nombres, u.apellidos, i.nombre, u.sexo, i.id FROM images i 
				inner join users u on u.firma_id=i.id  and i.id=:imagen_id';

		$firmas = DB::select($consulta, [':imagen_id'	=> $imagen_id] );*/

		$datos_imagen = array('oficiales' => $oficiales);

		return $datos_imagen;
	}




	
	public static function imagen_de_usuario($sexo='M', $imagen_id=false)
	{

		if ($imagen_id) {

			$img = ImagenModel::find($imagen_id);

			if ($img) {

				return 'perfil/' . $img->nombre;
					
			}else{
				if ($sexo == 'F') {
					return 'default_female.png';
				}else{
					return 'default_male.png';
				}
			}
			
		}else{
			if ($sexo == 'F') {
				return 'default_female.png';
			}else{
				return 'default_male.png';
			}
		}
	}

	

	public static function default_image_id($sexo)
	{
		if ($sexo == 'F') {
			return 2;
		}else{
			return 1; // ID de la imagen masculina
		}
	}
	public static function default_image_name($sexo)
	{
		if ($sexo == 'F') {
			return 'system/avatars/female1.jpg';
		}else{
			return 'system/avatars/male1.jpg';
		}
	}


	
	public static function ruta_imagen($imagen_id=false)
	{
		if ($imagen_id) {

			$img = ImagenModel::find($imagen_id);

			if ($img) {

				if ($img->publica) {
					return 'publics/' . $img->nombre;
				}else{
					return 'perfil/' . $img->nombre;
				}

			}else{
				return 'system/avatars/no-photo.jpg';
			}


		}else{

			return 'system/avatars/no-photo.jpg';

		}
	}

	
	public static function eliminar_imagen_y_enlaces($imagen_id)
	{
		$img 		= ImageModel::findOrFail($imagen_id);
		$filename 	= 'images/perfil/'.$img->nombre;
		
		if (File::exists($filename)) {
			File::delete($filename);
		}else{
			Log::info($imagen_id . ' -- Al parecer NO existe imagen: ' . $filename);
		}
		
		$img->delete();

		// Elimino cualquier referencia que otros tengan a esa imagen borrada.
		/*
		$consulta 	= 'UPDATE au_users SET foto_id=NULL WHERE foto_id=?;';
		DB::update($consulta, [$imagen_id]);
		*/
		
		
	}

	


}