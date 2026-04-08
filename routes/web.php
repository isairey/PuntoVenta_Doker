<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
//use App\Http\Controllers\CategoriaController;

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

Route::get('/main', function () {
    return view('contenido/contenido');
})->name('main');

Route::middleware(['guest'])->group(function () {
    Route::get('/','App\Http\Controllers\Auth\LoginController@showLoginForm');
    Route::post('/login','App\Http\Controllers\Auth\LoginController@login')->name('login');
});

Route::middleware(['auth'])->group(function () {

    Route::post('/logout','App\Http\Controllers\Auth\LoginController@logout')->name('logout');

    Route::middleware(['Almacenista'])->group(function(){
        Route::get('/categoria','App\Http\Controllers\CategoriaController@index');
        Route::post('/categoria/registrar','App\Http\Controllers\CategoriaController@store');
        Route::put('/categoria/actualizar','App\Http\Controllers\CategoriaController@update');
        Route::put('/categoria/activar','App\Http\Controllers\CategoriaController@activar');
        Route::put('/categoria/desactivar','App\Http\Controllers\CategoriaController@desactivar');
        Route::get('/categoria/selectCategoria','App\Http\Controllers\CategoriaController@selectCategoria');

        Route::get('/articulo','App\Http\Controllers\ArticuloController@index');
        Route::post('/articulo/registrar','App\Http\Controllers\ArticuloController@store');
        Route::put('/articulo/actualizar','App\Http\Controllers\ArticuloController@update');
        Route::put('/articulo/activar','App\Http\Controllers\ArticuloController@activar');
        Route::put('/articulo/desactivar','App\Http\Controllers\ArticuloController@desactivar');
        Route::get('/articulo/buscarArticulo','App\Http\Controllers\ArticuloController@buscarArticulo');


        Route::get('/proveedor','App\Http\Controllers\ProveedorController@index');
        Route::post('/proveedor/registrar','App\Http\Controllers\ProveedorController@store');
        Route::put('/proveedor/actualizar','App\Http\Controllers\ProveedorController@update');
        Route::get('/proveedor/selectProveedor','App\Http\Controllers\ProveedorController@selectProveedor');
        
        Route::get('/ingreso','App\Http\Controllers\ingresoController@index');
        Route::post('/ingreso/registrar','App\Http\Controllers\ingresoController@store');
        Route::put('/ingreso/actualizar','App\Http\Controllers\ingresoController@desactivar');
    });

    Route::middleware(['Vendedor'])->group(function(){
        Route::get('/cliente','App\Http\Controllers\ClienteController@index');
        Route::post('/cliente/registrar','App\Http\Controllers\ClienteController@store');
        Route::put('/cliente/actualizar','App\Http\Controllers\ClienteController@update');
    });
    
    Route::middleware(['Administrador'])->group(function(){
        Route::get('/categoria','App\Http\Controllers\CategoriaController@index');
        Route::post('/categoria/registrar','App\Http\Controllers\CategoriaController@store');
        Route::put('/categoria/actualizar','App\Http\Controllers\CategoriaController@update');
        Route::put('/categoria/activar','App\Http\Controllers\CategoriaController@activar');
        Route::put('/categoria/desactivar','App\Http\Controllers\CategoriaController@desactivar');
        Route::get('/categoria/selectCategoria','App\Http\Controllers\CategoriaController@selectCategoria');

        Route::get('/articulo','App\Http\Controllers\ArticuloController@index');
        Route::post('/articulo/registrar','App\Http\Controllers\ArticuloController@store');
        Route::put('/articulo/actualizar','App\Http\Controllers\ArticuloController@update');
        Route::put('/articulo/activar','App\Http\Controllers\ArticuloController@activar');
        Route::put('/articulo/desactivar','App\Http\Controllers\ArticuloController@desactivar');
        Route::get('/articulo/buscarArticulo','App\Http\Controllers\ArticuloController@buscarArticulo');

        Route::get('/proveedor','App\Http\Controllers\ProveedorController@index');
        Route::post('/proveedor/registrar','App\Http\Controllers\ProveedorController@store');
        Route::put('/proveedor/actualizar','App\Http\Controllers\ProveedorController@update');
        Route::get('/proveedor/selectProveedor','App\Http\Controllers\ProveedorController@selectProveedor');


        Route::get('/cliente','App\Http\Controllers\ClienteController@index');
        Route::post('/cliente/registrar','App\Http\Controllers\ClienteController@store');
        Route::put('/cliente/actualizar','App\Http\Controllers\ClienteController@update');

        Route::get('/rol','App\Http\Controllers\RolController@index');
        Route::get('/rol/selectRol','App\Http\Controllers\RolController@selectRol');
    
        Route::get('/user','App\Http\Controllers\UserController@index');
        Route::post('/user/registrar','App\Http\Controllers\UserController@store');
        Route::put('/user/actualizar','App\Http\Controllers\UserController@update');
        Route::put('/user/activar','App\Http\Controllers\UserController@activar');
        Route::put('/user/desactivar','App\Http\Controllers\UserController@desactivar');

        Route::get('/ingreso','App\Http\Controllers\ingresoController@index');
        Route::post('/ingreso/registrar','App\Http\Controllers\ingresoController@store');
        Route::put('/ingreso/actualizar','App\Http\Controllers\ingresoController@desactivar');

    });
   

    

   
});



//Auth::routes();
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

