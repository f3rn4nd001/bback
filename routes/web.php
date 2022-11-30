<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\operacion\viaje;
use App\Http\Controllers\catalogo\usuario;
use App\Http\Controllers\Login\loginController;
use App\Http\Controllers\catalogo\menuSubmenuControPermisos;
use App\Http\Controllers\catalogo\ciuddesEntidades;
use App\Http\Controllers\catalogo\tiff;
use App\Http\Controllers\sistemas\permisosController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Logins -------------------
*/
Route::post('Login', [loginController::class,'posLogin']);
Route::post('getMenu', [menuSubmenuControPermisos::class,'getMenu']);
Route::get('Sistemas/uausrios/asignaciones', [menuSubmenuControPermisos::class,'getMenuSubmenus']);
Route::post('Sistemas/Usuarios/detallesPermisos', [permisosController::class,'getDetallesPermisos']);
Route::post('Sistemas/usuario/postregistro', [permisosController::class,'postregistro']);


Route::post('p1file', [menuSubmenuControPermisos::class,'p1file']);
Route::post('contras', [loginController::class,'poscontras']);




/*
viajes -------------------
*/
Route::post('Operaciones/viaje/consulta', [viaje::class,'getRegistro']);
Route::post('Operaciones/viaje/detalles', [viaje::class,'getDetalles']);
Route::post('Operaciones/viaje/registro', [viaje::class,'postRegistro']);
Route::get('Operaciones/viaje/registro/compremento', [usuario::class,'getCompremento']);
Route::post('Operaciones/viaje/Monitoreo/Mensaje', [viaje::class,'postMonitoreoMensaje']);
Route::post('Operaciones/viaje/Monitoreo/getDatosMonitor', [viaje::class,'getDatosMonitor']);
Route::post('Operaciones/viaje/Monitoreo/postMonitoreoMasivo', [viaje::class,'postMonitoreoMasivo']);

/*
usuarios -------------------
*/

Route::get('Catalogo/usuario/consulta', [usuario::class,'getRegistro']);
Route::post('Catalogo/usuario/registro', [usuario::class,'postRegistro']);
Route::post('Catalogo/usuario/detalles', [usuario::class,'getDetalles']);
Route::post('Catalogo/usuario/getRFC', [usuario::class,'getRFC']);
Route::post('Catalogo/usuario/delete', [usuario::class,'postEliminar']);

Route::post('Catalogo/tiff/detalles', [tiff::class,'getDetalles']);
Route::post('Catalogo/tiff/consulta', [tiff::class,'getRegistro']);
Route::post('Catalogo/tiff/registro', [tiff::class,'postRegistro']);
Route::post('Catalogo/ciudadmunicipios/consulta', [ciuddesEntidades::class,'getRegistrociudadmunicipio']);


Auth::routes();
Route::get('/', function () {
    return view('welcome');
});

Route::get('/Operaciones/viaje/consulta', function () {
    return view('welcome');
});
Route::get('/Catalogo/usuarios/Consulta', function () {
    return view('welcome');
});
Route::get('/Catalogo/TIFF/Consulta', function () {
    return view('welcome');
});

Route::get('Sistemas/usuarios/Consulta', function () {
    return view('welcome');
});