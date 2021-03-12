<?php

use App\Http\Controllers\ClasificadorController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\LoginWithGoogleController;

use App\Http\Controllers\UnidadMedidaController;


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

Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::group(["middleware" => ['auth:sanctum', 'verified']], function () {
    /**************************** CRUD ROLES ***********************************/    
    Route::get('/roles', [RolController::class, 'index'])->name('roles.listar');    
    Route::get('/roles/create', [RolController::class, 'create'])->name('roles.crear');    
    Route::post('/roles', [RolController::class, 'store'])->name('roles.registrar');    
    /***************************************************************************/
 
    /**************************** UNIDADES DE MEDIDA ***************************/    
    Route::get('/unidades-medida', function () {
        return Inertia::render('Unidades_Medida/Listar');
    })->name('unidades-medida.iniciar');
    Route::get('/unidades-medida/listar', [UnidadMedidaController::class, 'index'])->name('unidades-medida.listar');
    Route::get('/unidades-medida/crear', [UnidadMedidaController::class, 'create'])->name('unidades-medida.crear'); 
    Route::post('/unidades-medida', [UnidadMedidaController::class, 'store'])->name('unidades-medida.registrar');
    Route::get('/unidades-medida/{unidad_medida}', [UnidadMedidaController::class, 'show'])->name('unidades-medida.mostrar');    
    Route::post('/unidades-medida/{unidad_medida}', [UnidadMedidaController::class, 'update'])->name('unidades-medida.actualizar');
    Route::delete('/unidades-medida/{unidad_medida}', [UnidadMedidaController::class, 'destroy'])->name('unidades-medida.eliminar');
    Route::post('/unidades-medida/{unidad_medida}/restaurar', [UnidadMedidaController::class, 'restore'])->name('unidades-medida.restaurar');
    /***************************************************************************/
 
    /**************************** CRUD CONCEPTOS ***********************************/    
    Route::get('/conceptos', [ConceptoController::class, 'index'])->name('conceptos.listar');        
    Route::get('/conceptos/{concepto}', [ConceptoController::class, 'show'])->name('conceptos.mostrar');
    Route::delete('/conceptos/{concepto}', [ConceptoController::class, 'destroy'])->name('conceptos.eliminar');
    /*******************************************************************************/    

    /**************************** CLASIFICADORES ***************************/    
    Route::get('/clasificadores', function () {
        return Inertia::render('Clasificadores/Listar');
    })->name('clasificadores.iniciar');
    Route::get('/clasificadores/listar', [ClasificadorController::class, 'index'])->name('clasificadores.listar');
    Route::get('/clasificadores/crear', [ClasificadorController::class, 'create'])->name('clasificadores.crear'); 
    Route::post('/clasificadores', [ClasificadorController::class, 'store'])->name('clasificadores.registrar');
    Route::get('/clasificadores/{clasificador}', [ClasificadorController::class, 'show'])->name('clasificadores.mostrar');    
    Route::post('/clasificadores/{clasificador}', [ClasificadorController::class, 'update'])->name('clasificadores.actualizar');
    Route::delete('/clasificadores/{clasificador}', [ClasificadorController::class, 'destroy'])->name('clasificadores.eliminar');
    Route::post('/clasificadores/{clasificador}/restaurar', [ClasificadorController::class, 'restore'])->name('clasificadores.restaurar');
    /***************************************************************************/
});

Route::get('/google', [LoginWithGoogleController::class, 'redirectToGoogle'])->name('google');;
Route::get('/google/callback', [LoginWithGoogleController::class, 'handleGoogleCallback']);


