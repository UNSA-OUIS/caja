<?php

use App\Http\Controllers\AdmisionController;
use App\Http\Controllers\BoletaController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ClasificadorController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AsignacionesController;
use App\Http\Controllers\BancoController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\ParticularController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ComprobanteController;
use App\Http\Controllers\ComunicacionBajaController;
use App\Http\Controllers\LoginWithGoogleController;
use App\Http\Controllers\TipoComprobanteController;
use App\Http\Controllers\TiposConceptoController;
use App\Http\Controllers\UnidadMedidaController;
use App\Http\Controllers\ConceptoController;
use App\Http\Controllers\CuentasCorrientesController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\NotaCreditoController;
use App\Http\Controllers\NotaDebitoController;
use App\Http\Controllers\DependenciaController;
use App\Http\Controllers\Pdf;
use App\Http\Controllers\NumeroComprobanteController;
use App\Http\Controllers\PuntosVentaController;
use App\Http\Controllers\SunatController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\ResumenDiarioController;

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

    /**************************** API RENIEC SUNAT ***********************************/
    Route::get('/api_dni/{dni}', function ($dni) {
        return file_get_contents("https://dniruc.apisperu.com/api/v1/dni/$dni?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6InJzaXphQHVuc2EuZWR1LnBlIn0._33jLRFR1pvHFv0z7Lzh6ZysOUfZSYlu7uxxE5Wagwo");
    });
    Route::get('/api_ruc/{ruc}', function ($ruc) {
        return file_get_contents("https://dniruc.apisperu.com/api/v1/ruc/$ruc?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6InJzaXphQHVuc2EuZWR1LnBlIn0._33jLRFR1pvHFv0z7Lzh6ZysOUfZSYlu7uxxE5Wagwo");
    });
    /*********************************************************************************/

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

    Route::get('/perfil-usuario', [UsuarioController::class, 'showMyUser'])->name('usuarios.perfil');
    Route::post('/perfil-usuario/{usuario}', [UsuarioController::class, 'editMyUser'])->name('usuarios.actualizarPerfil');
    /******************************************************************************/

    /*********************************** ASIGNACIONES ********************************/
    Route::get('/asignar', function () {
        return Inertia::render('Asignar/Listar');
    })->name('usuarios.asignar');
    Route::get('/asignar/listar', [AsignacionesController::class, 'index'])->name('asignar.listar');
    Route::get('/asignar/crear/{usuario}', [AsignacionesController::class, 'create'])->name('asignar.crear');
    Route::post('/asignar', [AsignacionesController::class, 'store'])->name('asignar.registrar');
    Route::post('/asignar/buscar', [AsignacionesController::class, 'search'])->name('asignar.buscar');
    Route::post('/asignar/mostrar', [AsignacionesController::class, 'show'])->name('asignar.mostrar');
    Route::post('/asignar/{usuario}', [AsignacionesController::class, 'update'])->name('asignar.actualizar');
    /******************************************************************************/

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
    Route::get('/buscarCentroCosto', [ConceptoController::class, 'buscarCentroCosto'])->name('conceptos.buscarCentroCosto');
    Route::get('/buscarConcepto', [ConceptoController::class, 'buscarConcepto'])->name('conceptos.buscarConcepto');
    /*******************************************************************/

    /******************************* ALUMNOS *****************************/
    Route::get('/buscarCuiAlumno/{cui}', [AlumnoController::class, 'buscarCuiAlumno'])->name('alumnos.buscarCuiAlumno');
    Route::get('/buscarApnAlumno', [AlumnoController::class, 'buscarApnAlumno'])->name('alumnos.buscarApnAlumno');
    Route::get('/buscarAlumno', [AlumnoController::class, 'buscarAlumno'])->name('alumnos.buscarAlumno');
    /*********************************************************************/

    /******************************* DOCENTES ****************************/
    Route::get('/buscarCodigoDocente/{codigo}', [DocenteController::class, 'buscarCodigoDocente'])->name('docentes.buscarCodigoDocente');
    Route::get('/buscarApnDocente', [DocenteController::class, 'buscarApnDocente'])->name('docentes.buscarApnDocente');
    /*********************************************************************/

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
    Route::get('/buscarDniParticular/{dni}', [ParticularController::class, 'buscarDniParticular'])->name('particulares.buscarDniParticular');
    Route::post('/registrarParticular', [ParticularController::class, 'registrarParticular'])->name('particulares.registrarParticular');
    Route::get('/buscarApnParticular', [ParticularController::class, 'buscarApnParticular'])->name('particulares.buscarApnParticular');
    Route::post('/actualizarParticular', [ParticularController::class, 'actualizarParticular'])->name('particulares.actualizarParticular');
    /*********************************************************************/

    /******************************* EMPRESAS ***************************/
    Route::get('/empresas', function () {
        return Inertia::render('Empresas/Listar');
    })->name('empresas.iniciar');
    Route::get('/empresas/listar', [EmpresaController::class, 'index'])->name('empresas.listar');
    Route::get('/empresas/crear', [EmpresaController::class, 'create'])->name('empresas.crear');
    Route::post('/empresas', [EmpresaController::class, 'store'])->name('empresas.registrar');
    Route::get('/empresas/{empresa}', [EmpresaController::class, 'show'])->name('empresas.mostrar');
    Route::post('/empresas/{empresa}', [EmpresaController::class, 'update'])->name('empresas.actualizar');
    Route::delete('/empresas/{empresa}', [EmpresaController::class, 'destroy'])->name('empresas.eliminar');
    Route::post('/empresas/{empresa}/restaurar', [EmpresaController::class, 'restore'])->name('empresas.restaurar');
    Route::get('/buscarRucEmpresa/{ruc}', [EmpresaController::class, 'buscarRucEmpresa'])->name('empresas.buscarRucEmpresa');
    Route::post('/registrarEmpresa', [EmpresaController::class, 'registrarEmpresa'])->name('empresas.registrarEmpresa');
    Route::get('/buscarRazonSocialEmpresa', [EmpresaController::class, 'buscarRazonSocialEmpresa'])->name('empresas.buscarRazonSocialEmpresa');
    /*********************************************************************/

    /******************************* DEPENDENCIAS ***************************/
    Route::get('/dependencias', function () {
        return Inertia::render('Dependencias/Listar');
    })->name('dependencias.iniciar');
    Route::get('/dependencias/listar', [DependenciaController::class, 'index'])->name('dependencias.listar');
    Route::get('/dependencias/{dependencia}', [DependenciaController::class, 'show'])->name('dependencias.mostrar');
    Route::get('/buscarCodigoDependencia/{codigo}', [DependenciaController::class, 'buscarCodigoDependencia'])->name('dependencias.buscarCodigoDependencia');
    Route::get('/buscarDependencia/{dependencia}', [DependenciaController::class, 'buscarDependencia'])->name('dependencias.buscarDependencia');
    /*********************************************************************/

    /******************************* COBROS *******************************/
    Route::get('/cobros', function () {
        return Inertia::render('Comprobantes/ListarCobros');
    })->name('cobros.iniciar');
    Route::get('/cobros/buscarUsuario', function () {
        return Inertia::render('Comprobantes/Busqueda');
    })->name('cobros.buscarUsuario');
    /**********************************************************************/

    /**************************** COMPROBANTES ***************************/
    Route::get('/comprobantes', function () {
        return Inertia::render('Comprobantes/Listar');
    })->name('comprobantes.iniciar');
    Route::get('/consultas', function () {
        return Inertia::render('Comprobantes/Consultas');
    })->name('comprobantes.consultas');

    Route::get('/comprobantes/listar', [ComprobanteController::class, 'index'])->name('comprobantes.listar');
    Route::get('/comprobantes/consultas/numero_operacion', [ComprobanteController::class, 'consultas_numero_operacion'])->name('comprobantes-consultas.numero_operacion');
    Route::get('/comprobantes/consultas/serie_correlativo', [ComprobanteController::class, 'consultas_serie_correlativo'])->name('comprobantes-consultas.serie_correlativo');
    Route::get('/comprobantes/crear', [ComprobanteController::class, 'create'])->name('comprobantes.crear');
    Route::get('/crear_alumno', [ComprobanteController::class, 'crear_alumno'])->name('comprobantes.crear_alumno');
    Route::get('/crear_docente', [ComprobanteController::class, 'crear_docente'])->name('comprobantes.crear_docente');
    Route::get('/crear_dependencia', [ComprobanteController::class, 'crear_dependencia'])->name('comprobantes.crear_dependencia');
    Route::get('/crear_particular', [ComprobanteController::class, 'crear_particular'])->name('comprobantes.crear_particular');
    Route::get('/crear_empresa', [ComprobanteController::class, 'crear_empresa'])->name('comprobantes.crear_empresa');
    Route::get('/crear_nota', [ComprobanteController::class, 'crear_nota'])->name('comprobantes.crear_nota');
    Route::post('/comprobantes', [ComprobanteController::class, 'store'])->name('comprobantes.registrar');
    Route::post('/notas', [ComprobanteController::class, 'store_nota'])->name('comprobantes.registrar_nota');
    Route::get('/comprobantes/{comprobante}', [ComprobanteController::class, 'show'])->name('comprobantes.mostrar');
    Route::get('/comprobantes/{comprobante}/visualizar', [ComprobanteController::class, 'visualizar'])->name('comprobantes.visualizar');
    Route::get('/comprobantes/mostrar/{comprobante}', [ComprobanteController::class, 'showConsulta'])->name('consulta.mostrar');
    Route::get('/comprobantes/consulta/detalles/{comprobante}', [ComprobanteController::class, 'consulta_comprobante'])->name('consulta.comprobante');
    Route::post('/comprobantes/{comprobante}', [ComprobanteController::class, 'anular'])->name('comprobantes.anular');
    Route::get('/generar_pdf', [ComprobanteController::class, 'generar_pdf'])->name('comprobantes.generar_pdf');
    Route::get('/generar_ticket', [ComprobanteController::class, 'generar_ticket'])->name('comprobantes.generar_ticket');
    Route::get('/sunat/facturaPDF', [ComprobanteController::class, 'descargar_pdf'])->name('facturas.descargar-pdf');
    Route::get('/sunat/facturaCDR', [ComprobanteController::class, 'descargar_cdr'])->name('facturas.descargar-cdr');
    Route::get('/sunat/facturaXML', [ComprobanteController::class, 'descargar_xml'])->name('facturas.descargar-xml');
    Route::get('/sunat/boletaPDF', [ComprobanteController::class, 'descargar_pdf'])->name('boleta.descargar-pdf');
    Route::get('/sunat/boletaCDR', [ComprobanteController::class, 'descargar_cdr'])->name('boleta.descargar-cdr');
    Route::get('/sunat/boletaXML', [ComprobanteController::class, 'descargar_xml'])->name('boleta.descargar-xml');
    Route::get('/verificarNroOperacion', [ComprobanteController::class, 'verificarNroOperacion'])->name('comprobantes.verificarNroOperacion');

    Route::get('/pagarFactura', [ComprobanteController::class, 'pagarFactura'])->name('comprobantes.pagarFactura');
    Route::get('/enviarCorreo', [ComprobanteController::class, 'enviarCorreo'])->name('comprobantes.enviarCorreo');
    Route::get('/buscarUsuario', [ComprobanteController::class, 'buscarUsuario'])->name('comprobantes.buscarUsuario');
    /*******************************************************************/

    /**************************** PROCESOS ***************************/
    Route::get('/banco', function () {
        return Inertia::render('Procesos/Banco/Busqueda');
    })->name('banco.iniciar');

    Route::get('/banco/listar', [BancoController::class, 'index'])->name('banco.listar');
    Route::post('/banco/procesar_pagos', [BancoController::class, 'procesar_pagos'])->name('banco.procesar_pagos');
    /*******************************************************************/

    /**************************** ADMISION ***************************/
    Route::get('/admision', function () {
        return Inertia::render('Admision/Listar');
    })->name('admision.iniciar');
    Route::get('/admision/listar', [AdmisionController::class, 'index'])->name('admision.listar');
    Route::get('/admision/crear', [AdmisionController::class, 'create'])->name('admision.crear');
    Route::post('/admision', [AdmisionController::class, 'store'])->name('admision.registrar');
    Route::get('/admision/{admision}', [AdmisionController::class, 'show'])->name('admision.mostrar');
    Route::post('/admision/{admision}', [AdmisionController::class, 'update'])->name('admision.actualizar');
    /*******************************************************************/

    /**************************** SUNAT ***************************/
    Route::get('/sunat/tablero', SunatController::class)->name('sunat.tablero');
    Route::get('/getEstados', [SunatController::class, 'getEstados'])->name('sunat.getEstados');


    Route::get('/sunat/facturas', function () {
        return Inertia::render('Sunat/Factura/Busqueda');
    })->name('facturas.iniciar');
    Route::get('/sunat/listarFacturas', [FacturaController::class, 'index'])->name('facturas.listar');
    Route::post('/sunat/enviarFacturas', [FacturaController::class, 'enviar_facturas'])->name('facturas.enviar-bloque');
    Route::post('/sunat/enviarFactura/{factura}', [FacturaController::class, 'enviar'])->name('facturas.enviar');
    Route::post('/sunat/anularFactura/{factura}', [FacturaController::class, 'anular'])->name('facturas.anular');

    Route::get('/sunat/comunicacion-baja', function () {
        return Inertia::render('Sunat/ComunicacionBaja/Busqueda');
    })->name('comunicacion-baja.iniciar');
    Route::get('/sunat/comunicacion-baja/listarFacturas', [ComunicacionBajaController::class, 'index'])->name('comunicacion-baja.listar');
    Route::post('/sunat/comunicacion-baja/enviar', [ComunicacionBajaController::class, 'enviar'])->name('comunicacion-baja.enviar');


    Route::get('/sunat/resumenDiario', function () {
        return Inertia::render('Sunat/ListarResumenDiario');
    })->name('resumen-diario.iniciar');
    Route::get('/sunat/listarResumenDiario', [ResumenDiarioController::class, 'index'])->name('resumen-diario.listar');
    Route::get('/sunat/resumenDiario/{resumenDiario}', [ResumenDiarioController::class, 'show'])->name('resumen-diario.mostrar');


    Route::get('/sunat/boletas', function () {
        return Inertia::render('Sunat/Boletas/Busqueda');
    })->name('boletas.iniciar');
    Route::get('/sunat/listarBoletas', [BoletaController::class, 'index'])->name('boletas.listar');
    Route::get('/sunat/listarBoletasActuales', [BoletaController::class, 'index_actual'])->name('boletas_actuales.listar');
    Route::post('/sunat/resumenDiario', [BoletaController::class, 'resumenDiario'])->name('boletas.resumen-diario');
    Route::post('/sunat/anularBoleta/{boleta}', [BoletaController::class, 'anular'])->name('boletas.anular');

    Route::get('/sunat/notas-credito', function () {
        return Inertia::render('Sunat/ListarNotasCredito');
    })->name('notas-credito.iniciar');
    Route::get('/notas-credito/listar', [NotaCreditoController::class, 'index'])->name('notas-credito.listar');
    Route::get('/notas-credito/crear', [NotaCreditoController::class, 'create'])->name('notas-credito.crear');
    Route::get('/notas-credito', [NotaCreditoController::class, 'store'])->name('notas-credito.registrar');
    Route::post('/notas-credito/enviar', [NotaCreditoController::class, 'enviar'])->name('notas-credito.enviar');
    Route::post('/notas-credito/anular', [NotaCreditoController::class, 'anular'])->name('notas-credito.anular');

    Route::get('/sunat/notas-debito', function () {
        return Inertia::render('Sunat/ListarNotasDebito');
    })->name('notas-debito.iniciar');
    Route::get('/notas-debito/listar', [NotadebitoController::class, 'index'])->name('notas-debito.listar');
    Route::get('/notas-debito/crear', [NotaDebitoController::class, 'create'])->name('notas-debito.crear');
    Route::get('/notas-debito', [NotaDebitoController::class, 'store'])->name('notas-debito.registrar');
    Route::post('/notas-debito/enviar', [NotaDebitoController::class, 'enviar'])->name('notas-debito.enviar');
    Route::post('/notas-debito/anular', [NotaDebitoController::class, 'anular'])->name('notas-debito.anular');

    Route::get('/sunat/comunicacion-baja', function () {
        return Inertia::render('Sunat/ComunicacionBaja/Busqueda');
    })->name('comunicacion-baja.iniciar');
    Route::get('/notas-debito/listar', [NotadebitoController::class, 'index'])->name('notas-debito.listar');
    Route::get('/notas-debito/crear', [NotaDebitoController::class, 'create'])->name('notas-debito.crear');
    Route::get('/notas-debito', [NotaDebitoController::class, 'store'])->name('notas-debito.registrar');
    Route::post('/notas-debito/enviar', [NotaDebitoController::class, 'enviar'])->name('notas-debito.enviar');
    Route::post('/notas-debito/anular', [NotaDebitoController::class, 'anular'])->name('notas-debito.anular');
    /*******************************************************************/

    /**************************** Reportes ***************************/
    Route::get('/reportes-periodo/cajero', [ReportesController::class, 'porCajero'])->name('reportes.cajero');
    Route::get('/reportes-periodo/cajero/pdf', [ReportesController::class, 'porCajeroPDF'])->name('reportes.cajeropdf');
    Route::get('/reportes-periodo/filter-reporte/cajeros', [ReportesController::class, 'filtrarCajero'])->name('reportes.filtrarCajero');
    Route::get('/buscarCajero', [ReportesController::class, 'buscarCajero'])->name('reportes.buscarCajero');
    Route::get('/reportes-periodo/descuentos', [ReportesController::class, 'descuentos'])->name('reportes.descuentos');
    Route::get('/reportes-periodo/filter-reporte/descuentos', [ReportesController::class, 'filtrarDescuento'])->name('reportes.filtrarDescuento');
    Route::get('/reportes-periodo/centroDeCosto', [ReportesController::class, 'centroDeCosto'])->name('reportes.centroDeCosto');
    Route::get('/reportes-periodo/filter-reporte/centros', [ReportesController::class, 'filtrarCentroDeCosto'])->name('reportes.filtrarCentroDeCosto');
    Route::get('/reportes-periodo/reciboIngreso', [ReportesController::class, 'reciboIngreso'])->name('reportes.reciboIngreso');
    Route::get('/reportes-periodo/filter-reporte/facturas', [ReportesController::class, 'filtrarFactura'])->name('reportes.filtrarFactura');
    Route::get('/reportes-periodo/facturas', [ReportesController::class, 'facturas'])->name('reportes.facturas');
    Route::get('/reportes-periodo/filter-reporte/notas', [ReportesController::class, 'filtrarNota'])->name('reportes.filtrarNota');
    Route::get('/reportes-periodo/notas', [ReportesController::class, 'notas'])->name('reportes.notas');
    Route::get('/reportes-periodo/consolidado', [ReportesController::class, 'consolidado'])->name('reportes.consolidado');
    Route::get('/reportes-periodo/filter-reporte/consolidado', [ReportesController::class, 'filtrarConsolidado'])->name('reportes.filtrarConsolidado');
    /*******************************************************************/

    /**************************** CUENTAS CORRIENTES ***************************/
    Route::get('/cuentas-corrientes', function () {
        return Inertia::render('Cuentas_Corrientes/Listar');
    })->name('cuentas-corrientes.iniciar');
    Route::get('/cuentas-corrientes/listar', [CuentasCorrientesController::class, 'index'])->name('cuentas-corrientes.listar');
    Route::get('/cuentas-corrientes/crear', [CuentasCorrientesController::class, 'create'])->name('cuentas-corrientes.crear');
    Route::post('/cuentas-corrientes', [CuentasCorrientesController::class, 'store'])->name('cuentas-corrientes.registrar');
    Route::get('/cuentas-corrientes/{cuenta_corriente}', [CuentasCorrientesController::class, 'show'])->name('cuentas-corrientes.mostrar');
    Route::post('/cuentas-corrientes/{cuenta_corriente}', [CuentasCorrientesController::class, 'update'])->name('cuentas-corrientes.actualizar');
    Route::delete('/cuentas-corrientes/{cuenta_corriente}', [CuentasCorrientesController::class, 'destroy'])->name('cuentas-corrientes.eliminar');
    Route::post('/cuentas-corrientes/{cuenta_corriente}/restaurar', [CuentasCorrientesController::class, 'restore'])->name('cuentas-corrientes.restaurar');
    /*******************************************************************/

    /**************************** UNIDADES DE MEDIDA ***************************/
    Route::get('/puntos-venta', function () {
        return Inertia::render('Puntos_Venta/Listar');
    })->name('puntosVenta.iniciar');
    Route::get('/puntos-venta/listar', [PuntosVentaController::class, 'index'])->name('puntosVenta.listar');
    Route::get('/puntos-venta/crear', [PuntosVentaController::class, 'create'])->name('puntosVenta.crear');
    Route::post('/puntos-venta', [PuntosVentaController::class, 'store'])->name('puntosVenta.registrar');
    Route::get('/puntos-venta/{puntoVenta}', [PuntosVentaController::class, 'show'])->name('puntosVenta.mostrar');
    Route::post('/puntos-venta/{puntoVenta}', [PuntosVentaController::class, 'update'])->name('puntosVenta.actualizar');
    Route::delete('/puntos-venta/{puntoVenta}', [PuntosVentaController::class, 'destroy'])->name('puntosVenta.eliminar');
    Route::post('/puntos-venta/{puntoVenta}/restaurar', [PuntosVentaController::class, 'restore'])->name('puntosVenta.restaurar');
    /***************************************************************************/

    /**************************** UNIDADES DE MEDIDA ***************************/
    Route::get('/numeros-operacion', function () {
        return Inertia::render('Numeros_Operacion/Listar');
    })->name('numerosOperacion.iniciar');
    Route::get('/numeros-operacion/listar', [NumeroComprobanteController::class, 'index'])->name('numerosOperacion.listar');
    Route::get('/numeros-operacion/crear', [NumeroComprobanteController::class, 'create'])->name('numerosOperacion.crear');
    Route::post('/numeros-operacion', [NumeroComprobanteController::class, 'store'])->name('numerosOperacion.registrar');
    Route::get('/numeros-operacion/{numeroOperacion}', [NumeroComprobanteController::class, 'show'])->name('numerosOperacion.mostrar');
    Route::post('/numeros-operacion/{numeroOperacion}', [NumeroComprobanteController::class, 'update'])->name('numerosOperacion.actualizar');
    Route::delete('/numeros-operacion/{numeroOperacion}', [NumeroComprobanteController::class, 'destroy'])->name('numerosOperacion.eliminar');
    Route::post('/numeros-operacion/{numeroOperacion}/restaurar', [NumeroComprobanteController::class, 'restore'])->name('numerosOperacion.restaurar');
    /***************************************************************************/
});

Route::get('/google', [LoginWithGoogleController::class, 'redirectToGoogle'])->name('google');;
Route::get('/google/callback', [LoginWithGoogleController::class, 'handleGoogleCallback']);
