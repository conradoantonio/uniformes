<?php

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

#Root url
Route::get('/', function () {
	return auth()->check() ? redirect()->action('LoginController@load_dashboard') : view('login');
});

#Login view
Route::get('login', function () {
    return view('login');
})->name('login');

#Mail view
Route::get('reset-preview', function () {
    return view('mails.reset_password');
});

#Logout url
Route::get('logout', 'LoginController@logout');

#Recover
Route::get('recuperar-cuenta', 'LoginController@resetView');

#Push url test
Route::get('test', function() {
    event(new App\Events\PusherEvent(['url' => 'endpoint'],'Hi there Pusher!', 'khe'));
    return 'Event sent';
});

#Route url
Route::post('login', 'LoginController@index');

#Admin url
Route::middleware(['auth'])->group(function () {
    #Dashboard
    Route::middleware('role:Administrador,Visualización,Escritura')->prefix('dashboard')->group( function() {
        Route::get('/', 'LoginController@load_dashboard');
        Route::get('excel/export', 'LoginController@export');
        Route::post('filter', 'LoginController@filter');
    });

    #Multitask controller
    Route::middleware(['role:Administrador,Visualización,Escritura'])->prefix('system')->group(function () {
        Route::post('upload-content', 'MultiTaskController@uploadFile');
    });

    #Mi perfil
    Route::middleware(['permission:mi_perfil_ver'])->prefix('mi-perfil')->group(function () {
        Route::get('/', 'MiPerfilController@index');
        Route::post('update', 'MiPerfilController@update');
    });

    #Razones sociales
    Route::middleware(['permission:razones_ver'])->prefix('razones-sociales')->group(function () {
        Route::get('/', 'RazonesSocialesController@index');
        Route::get('form/{id?}', 'RazonesSocialesController@form')->where('id', '[0-9]+')->middleware('permission:razones_editar');
        Route::get('excel/export', 'RazonesSocialesController@export');
        Route::get('excel/export/bills', 'RazonesSocialesController@exportBills');
        Route::post('save', 'RazonesSocialesController@save');
        Route::post('change-status', 'RazonesSocialesController@changeStatus');
        Route::post('update', 'RazonesSocialesController@update');
        Route::post('delete', 'RazonesSocialesController@delete');
    });

    #Empleados
    Route::middleware(['permission:empleados_ver'])->prefix('empleados')->group(function () {
        Route::get('/', 'EmpleadosController@index');
        Route::get('reload', 'EmpleadosController@index');
        Route::get('form/{id?}', 'EmpleadosController@form')->where('id', '[0-9]+')->middleware('permission:empleados_editar');
        Route::get('asignar-uniformes/{id?}', 'EmpleadosController@formUniformes')->where('id', '[0-9]+')->middleware('permission:empleados_editar');
        Route::get('generar-historico', 'EmpleadosController@generarHistorico');
        Route::post('ver-historico', 'EmpleadosController@verHistorico');
        Route::post('filter', 'EmpleadosController@filter');
        Route::post('save', 'EmpleadosController@save');
        Route::post('change-status', 'EmpleadosController@changeStatus');
        Route::post('update', 'EmpleadosController@update');
        Route::post('delete', 'EmpleadosController@delete');
    });

    #Registro de histórico de artículos
    Route::middleware(['permission:empleados_editar'])->prefix('historicos')->group(function () {
        Route::get('/', 'HistoricosController@index');
        Route::post('save', 'HistoricosController@save');
        Route::post('delete', 'HistoricosController@delete');
    });

    #Artículos
    Route::middleware(['permission:articulos_ver'])->prefix('articulos')->group(function () {
        Route::get('/', 'ArticulosController@index');
        Route::get('reload', 'ArticulosController@index');
        Route::get('form/{id?}', 'ArticulosController@form')->where('id', '[0-9]+')->middleware('permission:articulos_editar');
        Route::get('excel/export', 'ArticulosController@export');
        Route::post('filter', 'ArticulosController@filter');
        Route::post('get-details', 'ArticulosController@getDetails');
        Route::post('save', 'ArticulosController@save');
        Route::post('cancell', 'ArticulosController@cancell');
        Route::post('update', 'ArticulosController@update');
        Route::post('delete', 'ArticulosController@delete');
    });

    #Users CRUD
    Route::middleware(['permission:usuarios_ver'])->prefix('usuarios')->group(function () {
        Route::get('/', 'UsersController@index');
        Route::get('form/{id?}', 'UsersController@form')->middleware('permission:usuarios_editar');
        Route::post('save', 'UsersController@save');
        Route::post('update', 'UsersController@update');
        Route::post('delete', 'UsersController@delete');
        Route::post('change-status', 'UsersController@change_status');
    });
});

