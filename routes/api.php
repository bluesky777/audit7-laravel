<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller('au_imagenes', 'AuditSystem\ImagesController');
Route::controller('au_propiedad', 'AuditSystem\PropiedadController');
Route::controller('au_iglesias', 'AuditSystem\IglesiasController');
Route::controller('au_asociaciones', 'AuditSystem\AsociacionesController');
Route::controller('au_uniones', 'AuditSystem\UnionesController');
Route::controller('auditorias', 'AuditSystem\AuditoriasController');
Route::controller('remesas', 'AuditSystem\RemesasController');
Route::controller('au_usuario', 'AuditSystem\AuUsuarioController');
Route::controller('au_usuarios', 'AuditSystem\AuUsuariosController');
Route::controller('au_observaciones', 'AuditSystem\ObservacionesController');
Route::controller('au_login', 'AuditSystem\LoginController');
Route::controller('au_comparar', 'AuditSystem\Informes\CompararController');
Route::controller('au_entidades', 'AuditSystem\Entidades\EntidadesController');