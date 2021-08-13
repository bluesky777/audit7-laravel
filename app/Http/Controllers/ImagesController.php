<?php namespace App\Http\Controllers;

use Request;
use Hash;
use File;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatosIniciales;
use App\Http\Controllers\Sincronizar;
use Carbon\Carbon;
use \Log;
use App\Models\ImagenModel;

use DB;

class ImagesController extends Controller {

	public function getIndex(){
        $consulta   = "SELECT * FROM au_images WHERE deleted_at is null";
        $images     = DB::select($consulta);
        return $images;
    }


	public function putQuitarIglesia(){
        $id             = Request::input('imagen_id');

        $consulta   = "UPDATE au_images SET iglesia_id=NULL WHERE id=?";
        $images     = DB::update($consulta, [$id]);
        return 'Quitada';
    }


	public function putUpdate(){
        $id             = Request::input('id');
        $descripcion    = Request::input('descripcion');

        $consulta   = "UPDATE au_images SET descripcion=? WHERE id=?";
        $images     = DB::update($consulta, [$descripcion, $id]);
        return 'Guardado';
    }


	public function putAddToIglesia(){
        $imagen_id 		= Request::input('imagen_id');
        $iglesia_id 	= Request::input('iglesia_id');

        $consulta   = "UPDATE au_images SET iglesia_id=? WHERE id=?";
        $images     = DB::update($consulta, [$iglesia_id, $imagen_id]);
        return 'Asignado';
    }



	public function postStore()
	{
        //$user = User::fromToken();
        $now 				= Carbon::now('America/Bogota');
        $user_id    		= Request::input("user_id");
        $asociacion_id 		= Request::input("asociacion_id");

		$folderName = 'user_'.$user_id;
		$folder = 'images/perfil/'.$folderName;

		if (!File::exists($folder)) {
			File::makeDirectory($folder, $mode = 0777, true, true);
		}

		$file = Request::file("file");
		//separamos el nombre de la img y la extensión
		$info = explode(".", $file->getClientOriginalName());
		//asignamos de nuevo el nombre de la imagen completo
		$miImg = $file->getClientOriginalName();

		//return Request::file('file')->getMimeType(); // Puedo borrarlo
		//mientras el nombre exista iteramos y aumentamos i
		$i = 0;
		while(file_exists($folder.'/'. $miImg)){
			$i++;
			$miImg = $info[0]."(".$i.")".".".$info[1];
		}

		//guardamos la imagen con otro nombre ej foto(1).jpg || foto(2).jpg etc
		$file->move($folder, $miImg);

		$newImg 			    	= new ImagenModel;
		$newImg->nombre 	    	= $folderName.'/'.$miImg;
		$newImg->user_id 	    	= $user_id;
		$newImg->created_by     	= $user_id;
		$newImg->asociacion_id 		= $asociacion_id;
		$newImg->fecha_documento 	= $now;
		$newImg->save();

		return $newImg;
	}






	public function deleteDestroy($id)
	{
        $img 	    = ImagenModel::findOrFail($id);
        $now 		= Carbon::now('America/Bogota');
        //$user_id    = Request::input("user_id");
        $filename 	= 'images/perfil/'.$img->nombre;

        $consulta 	= 'UPDATE au_images SET deleted_at=? WHERE id=?';
        DB::update($consulta, [ $now, $id ]);

        if (File::exists($filename)) {
			File::delete($filename);
		}else{
			Log::info($id . ' -- Al parecer NO existe imagen: ' . $filename);
		}

		//ImagenModel::eliminar_imagen_y_enlaces($id);



		return 'Eliminada';
	}


}
