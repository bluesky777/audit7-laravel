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

AdvancedRoute::controller('login', 'LoginController');

AdvancedRoute::controller('au_imagenes', 'ImagesController');
AdvancedRoute::controller('au_propiedad', 'PropiedadController');
AdvancedRoute::controller('iglesias', 'IglesiasController');
AdvancedRoute::controller('au_asociaciones', 'AsociacionesController');
AdvancedRoute::controller('au_uniones', 'UnionesController');
AdvancedRoute::controller('auditorias', 'AuditoriasController');
AdvancedRoute::controller('remesas', 'RemesasController');
AdvancedRoute::controller('au_usuario', 'AuUsuarioController');
AdvancedRoute::controller('au_usuarios', 'AuUsuariosController');
AdvancedRoute::controller('au_observaciones', 'ObservacionesController');
AdvancedRoute::controller('au_login', 'LoginController');
AdvancedRoute::controller('au_comparar', 'Informes\CompararController');
AdvancedRoute::controller('au_entidades', 'Entidades\EntidadesController');
