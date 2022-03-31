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

    #Clientes
    Route::middleware(['permission:clientes_ver'])->prefix('clientes')->group(function () {
        Route::get('/', 'ClientesController@index');
        Route::get('reload', 'ClientesController@index');
        Route::get('form/{id?}', 'ClientesController@form')->where('id', '[0-9]+')->middleware('permission:clientes_editar');
        Route::get('generar-estado-cuenta', 'ClientesController@generarEstadoCuenta');
        Route::get('{cliente_id}/exportar/pdf/{vista}', 'ClientesController@generarPDF');
        Route::post('filter', 'ClientesController@filter');
        Route::post('show', 'ClientesController@show');
        Route::post('save', 'ClientesController@save');
        Route::post('change-status', 'ClientesController@changeStatus');
        Route::post('update', 'ClientesController@update');
        Route::post('delete', 'ClientesController@delete');
    });

    #Facturas
    Route::middleware(['permission:facturas_ver'])->prefix('facturas')->group(function () {
        Route::get('/', 'FacturasController@index');
        Route::get('reload', 'FacturasController@index');
        Route::get('form/{id?}', 'FacturasController@form')->where('id', '[0-9]+')->middleware('permission:facturas_editar');
        Route::get('excel/export', 'FacturasController@export');
        Route::post('filter', 'FacturasController@filter');
        Route::post('get-details', 'FacturasController@getDetails');
        Route::post('save', 'FacturasController@save');
        Route::post('cancell', 'FacturasController@cancell');
        Route::post('update', 'FacturasController@update');
        Route::post('delete', 'FacturasController@delete');
    });

    #Cobranza
    Route::middleware(['permission:cobranza_ver'])->prefix('cobranza')->group(function () {
        Route::get('/', 'CobranzasController@index');
        Route::get('reload', 'CobranzasController@index');
        Route::get('excel/export', 'CobranzasController@export');
        Route::get('comentarios/excel/export', 'CobranzasController@exportComments');
        Route::post('filter', 'CobranzasController@filter');
        Route::post('comentarios', 'CobranzasController@getComments');
        Route::post('comentarios/save', 'CobranzasController@save');
        Route::post('comentarios/delete', 'CobranzasController@delete');
    });

    #Facturas canceladas
    Route::middleware(['permission:facturas_canceladas_ver'])->prefix('facturas/canceladas')->group(function () {
        Route::get('/', 'FacturasCanceladasController@index');
        Route::get('form/{id?}', 'FacturasCanceladasController@form')->where('id', '[0-9]+')->middleware('permission:facturas_canceladas_editar');
        Route::post('filter', 'FacturasCanceladasController@filter');
        Route::post('delete', 'FacturasCanceladasController@delete');
    });

    #Notas de crédito
    Route::middleware(['permission:notas_de_credito_ver'])->prefix('notas-de-credito')->group(function () {
        Route::get('/', 'NotasDeCreditoController@index');
        Route::get('form/{id?}', 'NotasDeCreditoController@form')->where('id', '[0-9]+')->middleware('permission:notas_de_credito_editar');
        Route::get('excel/export', 'NotasDeCreditoController@export');
        Route::post('filter', 'NotasDeCreditoController@filter');
        Route::post('save', 'NotasDeCreditoController@save');
        Route::post('change-status', 'NotasDeCreditoController@changeStatus');
        Route::post('update', 'NotasDeCreditoController@update');
        Route::post('delete', 'NotasDeCreditoController@delete');
    });

    #Pagos
    Route::middleware(['permission:pagos_ver'])->prefix('pagos')->group(function () {
        Route::get('/', 'PagosController@index');
        Route::get('form/{id?}', 'PagosController@form')->where('id', '[0-9]+')->middleware('permission:pagos_ver');
        Route::get('excel/export', 'PagosController@export');
        Route::post('filter', 'PagosController@filter');
        Route::post('save', 'PagosController@save');
        Route::post('change-status', 'PagosController@changeStatus');
        Route::post('update', 'PagosController@update');
        Route::post('delete', 'PagosController@delete');
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

