<?php

use App\Http\Controllers\AccesoGoogleController;
use App\Http\Controllers\ClasificadorController;
use App\Http\Controllers\ParticularController;
use App\Http\Controllers\ComprobanteController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\LoginWithGoogleController;
use App\Http\Controllers\SunatController;
use App\Http\Controllers\TipoComprobanteController;
use App\Http\Controllers\TiposConceptoController;
use App\Http\Controllers\UnidadMedidaController;
use App\Http\Controllers\ConceptoController;
use App\Http\Controllers\ReportesController;
use App\Models\Concepto;

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

Route::middleware(['auth:sanctum', 'verified'])->get('/', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

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

    /**************************** TIPOS DE CONCEPTO ***************************/
    Route::get('/tipos-concepto', function () {
        return Inertia::render('Tipos_Concepto/Listar');
    })->name('tipos-concepto.iniciar');
    Route::get('/tipos-concepto/listar', [TiposConceptoController::class, 'index'])->name('tipos-concepto.listar');
    Route::get('/tipos-concepto/crear', [TiposConceptoController::class, 'create'])->name('tipos-concepto.crear');
    Route::post('/tipos-concepto', [TiposConceptoController::class, 'store'])->name('tipos-concepto.registrar');
    Route::get('/tipos-concepto/{tipos_concepto}', [TiposConceptoController::class, 'show'])->name('tipos-concepto.mostrar');
    Route::post('/tipos-concepto/{tipos_concepto}', [TiposConceptoController::class, 'update'])->name('tipos-concepto.actualizar');
    Route::delete('/tipos-concepto/{tipos_concepto}', [TiposConceptoController::class, 'destroy'])->name('tipos-concepto.eliminar');
    Route::post('/tipos-concepto/{tipos_concepto}/restaurar', [TiposConceptoController::class, 'restore'])->name('tipos-concepto.restaurar');
    /***************************************************************************/

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

    /**************************** TIPOS DE COMPROBANTE ***************************/
    Route::get('/tipo-comprobante', function () {
        return Inertia::render('Tipo_Comprobante/Listar');
    })->name('tipo-comprobante.iniciar');
    Route::get('/tipo-comprobante/listar', [TipoComprobanteController::class, 'index'])->name('tipo-comprobante.listar');
    Route::get('/tipo-comprobante/crear', [TipoComprobanteController::class, 'create'])->name('tipo-comprobante.crear');
    Route::post('/tipo-comprobante', [TipoComprobanteController::class, 'store'])->name('tipo-comprobante.registrar');
    Route::get('/tipo-comprobante/{tipo_comprobante}', [TipoComprobanteController::class, 'show'])->name('tipo-comprobante.mostrar');
    Route::post('/tipo-comprobante/{tipo_comprobante}', [TipoComprobanteController::class, 'update'])->name('tipo-comprobante.actualizar');
    Route::delete('/tipo-comprobante/{tipo_comprobante}', [TipoComprobanteController::class, 'destroy'])->name('tipo-comprobante.eliminar');
    Route::post('/tipo-comprobante/{tipo_comprobante}/restaurar', [TipoComprobanteController::class, 'restore'])->name('tipo-comprobante.restaurar');
    /***************************************************************************/

    /*********************************** ROLES ********************************/
    Route::get('/roles', function () {
        return Inertia::render('Roles/Listar');
    })->name('roles.iniciar');
    Route::get('/roles/listar', [RolController::class, 'index'])->name('roles.listar');
    Route::get('/roles/crear', [RolController::class, 'create'])->name('roles.crear');
    Route::post('/roles', [RolController::class, 'store'])->name('roles.registrar');
    Route::get('/roles/{rol}', [RolController::class, 'show'])->name('roles.mostrar');
    Route::post('/roles/{rol}', [RolController::class, 'update'])->name('roles.actualizar');
    Route::delete('/roles/{rol}', [RolController::class, 'destroy'])->name('roles.eliminar');
    Route::post('/roles/{rol}/restaurar', [RolController::class, 'restore'])->name('roles.restaurar');
    /***************************************************************************/

    /*********************************** USUARIOS ********************************/
    Route::get('/usuarios', function () {
        return Inertia::render('Usuarios/Listar');
    })->name('usuarios.iniciar');
    Route::get('/usuarios/listar', [UsuarioController::class, 'index'])->name('usuarios.listar');
    Route::get('/usuarios/crear', [UsuarioController::class, 'create'])->name('usuarios.crear');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.registrar');
    Route::get('/usuarios/{usuario}', [UsuarioController::class, 'show'])->name('usuarios.mostrar');
    Route::post('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.actualizar');
    Route::delete('/usuarios/{usuario}', [UsuarioController::class, 'destroy'])->name('usuarios.eliminar');
    Route::post('/usuarios/{usuario}/restaurar', [UsuarioController::class, 'restore'])->name('usuarios.restaurar');

    Route::get('/perfil-usuario', function () {
        return Inertia::render('Usuarios/Perfil');
    })->name('usuarios.perfil');
    /******************************************************************************/

    /************************* ACCESOS GOOGLE********************/
    Route::get('/accesos-google', function () {
        return Inertia::render('AccesosGoogle/Listar');
    })->name('accesos-google.iniciar');
    Route::get('/accesos-google/listar', [AccesoGoogleController::class, 'index'])->name('accesos-google.listar');
    Route::get('/accesos-google/crear', [AccesoGoogleController::class, 'create'])->name('accesos-google.crear');
    Route::post('/accesos-google', [AccesoGoogleController::class, 'store'])->name('accesos-google.registrar');
    Route::get('/accesos-google/{acceso_google}', [AccesoGoogleController::class, 'show'])->name('accesos-google.mostrar');
    Route::post('/accesos-google/{acceso_google}', [AccesoGoogleController::class, 'update'])->name('accesos-google.actualizar');
    Route::delete('/accesos-google/{acceso_google}', [AccesoGoogleController::class, 'destroy'])->name('accesos-google.eliminar');
    Route::post('/accesos-google/{acceso_google}/restaurar', [AccesoGoogleController::class, 'restore'])->name('accesos-google.restaurar');
    /***************************************************************************/

    /**************************** CONCEPTOS ***************************/
    Route::get('/conceptos', function () {
        return Inertia::render('Conceptos/Listar');
    })->name('conceptos.iniciar');
    Route::get('/conceptos/listar', [ConceptoController::class, 'index'])->name('conceptos.listar');
    Route::get('/conceptos/crear', [ConceptoController::class, 'create'])->name('conceptos.crear');
    Route::post('/conceptos', [ConceptoController::class, 'store'])->name('conceptos.registrar');
    Route::get('/conceptos/{concepto}', [ConceptoController::class, 'show'])->name('conceptos.mostrar');
    Route::post('/conceptos/{concepto}', [ConceptoController::class, 'update'])->name('conceptos.actualizar');
    Route::delete('/conceptos/{concepto}', [ConceptoController::class, 'destroy'])->name('conceptos.eliminar');
    Route::post('/conceptos/{concepto}/restaurar', [ConceptoController::class, 'restore'])->name('conceptos.restaurar');
    /*******************************************************************/

    /**************************** PARTICULARES ***************************/
    Route::get('/particulares', function () {
        return Inertia::render('Particulares/Listar');
    })->name('particulares.iniciar');
    Route::get('/particulares/listar', [ParticularController::class, 'index'])->name('particulares.listar');
    Route::get('/particulares/crear', [ParticularController::class, 'create'])->name('particulares.crear');
    Route::post('/particulares', [ParticularController::class, 'store'])->name('particulares.registrar');
    Route::get('/particulares/{particular}', [ParticularController::class, 'show'])->name('particulares.mostrar');
    Route::post('/particulares/{particular}', [ParticularController::class, 'update'])->name('particulares.actualizar');
    Route::delete('/particulares/{particular}', [ParticularController::class, 'destroy'])->name('particulares.eliminar');
    Route::post('/particulares/{particular}/restaurar', [ParticularController::class, 'restore'])->name('particulares.restaurar');
    /*********************************************************************/


    /**************************** COMPROBANTES ***************************/
    Route::get('/comprobantes', function () {
        //return Inertia::render('Comprobantes/Listar');
        return Inertia::render('Comprobantes/Busqueda');
    })->name('comprobantes.iniciar');

    Route::get('/comprobantes/listar', [ComprobanteController::class, 'index'])->name('comprobantes.listar');
    Route::post('/comprobantes/crear', [ComprobanteController::class, 'create'])->name('comprobantes.crear');
    Route::post('/comprobantes', [ComprobanteController::class, 'store'])->name('comprobantes.registrar');
    Route::get('/comprobantes/{comprobante}', [ComprobanteController::class, 'show'])->name('comprobantes.mostrar');
    Route::post('/comprobantes/{comprobante}', [ComprobanteController::class, 'anular'])->name('comprobantes.anular');

    Route::get('/buscarCuiAlumno/{cui}', [ComprobanteController::class, 'buscarCuiAlumno'])->name('comprobantes.buscarCuiAlumno');
    Route::get('/buscarApnAlumno', [ComprobanteController::class, 'buscarApnAlumno'])->name('comprobantes.buscarApnAlumno');
    Route::get('/buscarCodigoDocente/{codigo}', [ComprobanteController::class, 'buscarCodigoDocente'])->name('comprobantes.buscarCodigoDocente');
    Route::get('/buscarApnDocente', [ComprobanteController::class, 'buscarApnDocente'])->name('comprobantes.buscarApnDocente');
    Route::get('/buscarCodigoDependencia/{codigo}', [ComprobanteController::class, 'buscarCodigoDependencia'])->name('comprobantes.buscarCodigoDependencia');
    Route::get('/buscarDependencia/{dependencia}', [ComprobanteController::class, 'buscarDependencia'])->name('comprobantes.buscarDependencia');    
    Route::get('/buscarDniParticular/{dni}', [ComprobanteController::class, 'buscarDniParticular'])->name('comprobantes.buscarDniParticular');
    Route::post('/registrarParticular', [ComprobanteController::class, 'registrarParticular'])->name('comprobantes.registrarParticular');
    Route::get('/buscarApnParticular', [ComprobanteController::class, 'buscarApnParticular'])->name('comprobantes.buscarApnParticular');    
    /*******************************************************************/


    /**************************** Sunat ***************************/
    Route::get('/sunat/tablero', SunatController::class)->name('sunat.tablero');
    Route::get('/getEstados', SunatController::class, 'getEstados')->name('sunat.getEstados');

    Route::get('/sunat/facturas', function () {
        return Inertia::render('Sunat/ListarFacturas');
    })->name('sunat.iniciarFacturas');
    Route::get('/sunat/listarFacturas', [SunatController::class, 'indexFactura'])->name('sunat.listarFacturas');
    Route::post('/sunat/enviarFactura/{factura}', [SunatController::class, 'enviarFactura'])->name('sunat.enviarFactura');
    Route::post('/sunat/anularFactura/{factura}', [SunatController::class, 'anularFactura'])->name('sunat.anularFactura');

    Route::get('/sunat/boletas', function () {
        return Inertia::render('Sunat/ListarBoletas');
    })->name('sunat.iniciarBoletas');
    Route::get('/sunat/listarBoletas', [SunatController::class, 'indexBoleta'])->name('sunat.listarBoletas');
    Route::post('/sunat/enviarBoleta/{boleta}', [SunatController::class, 'enviarBoleta'])->name('sunat.enviarBoleta');
    Route::post('/sunat/anularBoleta/{boleta}', [SunatController::class, 'anularBoleta'])->name('sunat.anularBoleta');
    Route::get('/sunat/resumenDiario', [SunatController::class, 'resumenDiario'])->name('sunat.resumenDiario');
    /*******************************************************************/

    /**************************** Reportes ***************************/
    Route::get('/reportes-periodo/cajero', [ReportesController::class, 'porCajero'])->name('reportes.cajero');
    Route::get('/reportes-periodo/cajero/pdf', [ReportesController::class, 'porCajeroPDF'])->name('reportes.cajeropdf');
    Route::get('/reportes-periodo/filter-reporte', [ReportesController::class, 'filtrarCajero'])->name('reportes.filtrarCajero');
    Route::get('/reportes-periodo/descuentos', [ReportesController::class, 'descuentos'])->name('reportes.descuentos');
    Route::get('/reportes-periodo/centroDeCosto', [ReportesController::class, 'centroDeCosto'])->name('reportes.centroDeCosto');
    Route::get('/reportes-periodo/reciboIngreso', [ReportesController::class, 'reciboIngreso'])->name('reportes.reciboIngreso');
    Route::get('/reportes-periodo/facturas', [ReportesController::class, 'facturas'])->name('reportes.facturas');
    Route::get('/reportes-periodo/notas', [ReportesController::class, 'notas'])->name('reportes.notas');
    Route::get('/reportes-periodo/consolidado', [ReportesController::class, 'consolidado'])->name('reportes.consolidado');
    /*******************************************************************/
});

Route::get('/google', [LoginWithGoogleController::class, 'redirectToGoogle'])->name('google');;
Route::get('/google/callback', [LoginWithGoogleController::class, 'handleGoogleCallback']);
