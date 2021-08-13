<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entrada extends Model {
	use SoftDeletes;

	protected $softDelete = true;
	
	protected $table = 'entradas';
	protected $dates = ['deleted_at', 'created_at', 'updated_at'];

}