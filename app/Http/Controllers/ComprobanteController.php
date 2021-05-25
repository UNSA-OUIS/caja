<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Alumno;
use App\Models\Particular;
use App\Models\Empresa;
use App\Models\Docente;
use App\Models\Comprobante;
use App\Models\Dependencia;
use App\Jobs\EnviarCorreosJob;
use App\Models\Concepto;
use App\Models\Departamento;
use App\Models\DetallesComprobante;
use App\Models\NumeroOperacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;

class ComprobanteController extends Controller
{

    public function index(Request $request)
    {
        //$this->authorize("viewAny", Comprobante::class);

        $query = Comprobante::with('comprobanteable')->with('tipo_comprobante')
            ->with('detalles')->where('codi_usuario', 'like', '%' . $request->filter . '%')
            ->latest();

        $sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {
            $sortdirection = $request->sortdesc == "true" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
        }

        return $query->paginate($request->size);
    }

    public function crear_alumno(Request $request)
    {
        //return $request;
        $ultima_boleta = Comprobante::where('tipo_usuario', '<>', 'empresa')->latest()->first();

        $comprobante = new Comprobante();

        $comprobante->tipo_usuario = "alumno";
        $comprobante->codi_usuario = $request->alumno['cui'];
        $comprobante->nues_espe = $request->matricula['nues'];
        $comprobante->tipo_comprobante_id = config('caja.tipo_comprobante.BOLETA');

        $usuario = Auth::user();
        $numeroOpe = $usuario->puntoVenta->numerosOperacion->where('tipo_comprobante_id', config('caja.tipo_comprobante.BOLETA'))->first();
        $comprobante->serie = $numeroOpe->serie;
        $comprobante->correlativo = $numeroOpe->correlativo;

        /*$comprobante->serie = "B001";

        if (!$ultima_boleta) {
            $comprobante->correlativo = '00000001';
        } else {
            $ultima_boleta->correlativo += 1;
            $comprobante->correlativo = str_pad($ultima_boleta->correlativo, 8, "0", STR_PAD_LEFT);
        }*/

        $comprobante->total_descuento = "";
        $comprobante->total_impuesto = "";
        $comprobante->total = "";
        $comprobante->detalles = array();
        $tipo_de_documento = '';
        $numero_de_documento = '';
        if (strlen($request->alumno['dic']) > 8) {
            switch ($request->alumno['dic'][0]) {
                case 'D':
                    $tipo_de_documento = 'DNI';
                    $numero_de_documento = substr($request->alumno['dic'], 1, -1);
                    break;
                case 'P':
                    $tipo_de_documento = 'Pasaporte';
                    $numero_de_documento = substr($request->alumno['dic'], 1, -1);
                    break;
                case 'E':
                    $tipo_de_documento = 'Carnet Extra.';
                    $numero_de_documento = substr($request->alumno['dic'], 1, -1);
                    break;

                default:
                    # code...
                    break;
            }
        } else if (strlen($request->alumno['dic']) == 8) {
            $tipo_de_documento = 'DNI';
            $numero_de_documento = $request->alumno['dic'];
        }
        // **************************** Relationship ****************
        $alumno = Alumno::where('cui', '=', $request->alumno['cui'])->first();
        $email = $alumno->email != null ? $alumno->email : '';
        // **************************** Relationship ****************
        $data = [
            'tipo_comprobante' => 'BOLETA',
            'tipo_doc' => $tipo_de_documento,
            'ndoc' => $numero_de_documento,
            'escuela' => $request->matricula['escuela']['nesc'],
            'alumno' => $request->alumno['apn'],
            'email' => $email->mail != '' ? $email->mail . '@unsa.edu.pe': '',
            'fecha_actual' => Carbon::now('America/Lima')->format('Y-m-d')
        ];

        return Inertia::render('Comprobantes/Cabecera', compact('comprobante', 'data'));
    }

    public function crear_docente(Request $request)
    {
        $ultima_boleta = Comprobante::where('tipo_usuario', '<>', 'empresa')->latest()->first();

        $comprobante = new Comprobante();

        $comprobante->tipo_usuario = "docente";
        $comprobante->codi_usuario = $request->docente['codper'];
        $comprobante->nues_espe = "";
        $comprobante->tipo_comprobante_id = config('caja.tipo_comprobante.BOLETA');

        $usuario = Auth::user();
        $numeroOpe = $usuario->puntoVenta->numerosOperacion->where('tipo_comprobante_id', config('caja.tipo_comprobante.BOLETA'))->first();
        $comprobante->serie = $numeroOpe->serie;
        $comprobante->correlativo = $numeroOpe->correlativo;

        /*$comprobante->serie = "B001";

        if (!$ultima_boleta) {
            $comprobante->correlativo = '00000001';
        } else {
            $ultima_boleta->correlativo += 1;
            $comprobante->correlativo = str_pad($ultima_boleta->correlativo, 8, "0", STR_PAD_LEFT);
        }*/

        $comprobante->total_descuento = "";
        $comprobante->total_impuesto = "";
        $comprobante->total = "";
        $comprobante->detalles = array();

        $depa = Departamento::where('depa', '=', $request->docente['depend'])->first();
        $ndep = $depa != null ? $depa->ndep : '';

        $data = [
            'tipo_comprobante' => 'BOLETA',
            'dni' => $request->docente['dic'],
            'docente' => str_replace("/", " ", $request->docente['apn']),
            'email' => $request->docente['correo'] != '' ? $request->docente['correo'] . '@unsa.edu.pe': '',
            'departamento' => $ndep,
            'fecha_actual' => Carbon::now('America/Lima')->format('Y-m-d')
        ];

        return Inertia::render('Comprobantes/Cabecera', compact('comprobante', 'data'));
    }

    public function crear_dependencia(Request $request)
    {
        $ultima_boleta = Comprobante::where('tipo_usuario', '<>', 'empresa')->latest()->first();

        $comprobante = new Comprobante();

        $comprobante->tipo_usuario = "dependencia";
        $comprobante->codi_usuario = $request->dependencia['codi_depe'];
        $comprobante->nues_espe = "";
        $comprobante->tipo_comprobante_id = config('caja.tipo_comprobante.BOLETA');

        $usuario = Auth::user();
        $numeroOpe = $usuario->puntoVenta->numerosOperacion->where('tipo_comprobante_id', config('caja.tipo_comprobante.BOLETA'))->first();
        $comprobante->serie = $numeroOpe->serie;
        $comprobante->correlativo = $numeroOpe->correlativo;

        /*$comprobante->serie = "B001";

        if (!$ultima_boleta) {
            $comprobante->correlativo = '00000001';
        } else {
            $ultima_boleta->correlativo += 1;
            $comprobante->correlativo = str_pad($ultima_boleta->correlativo, 8, "0", STR_PAD_LEFT);
        }*/

        $comprobante->total_descuento = "";
        $comprobante->total_impuesto = "";
        $comprobante->total = "";
        $comprobante->detalles = array();

        $data = [
            'tipo_comprobante' => 'BOLETA',
            'dependencia' => $request->dependencia['nomb_depe'],
            'email' => 'sizaisi@unsa.edu.pe',
            'fecha_actual' => Carbon::now('America/Lima')->format('Y-m-d')
        ];

        return Inertia::render('Comprobantes/Cabecera', compact('comprobante', 'data'));
    }

    public function crear_particular(Request $request)
    {
        $ultima_boleta = Comprobante::where('tipo_usuario', '<>', 'empresa')->latest()->first();

        $comprobante = new Comprobante();

        $comprobante->tipo_usuario = "particular";
        $comprobante->codi_usuario = $request->particular['dni'];
        $comprobante->nues_espe = "";
        $comprobante->tipo_comprobante_id = config('caja.tipo_comprobante.BOLETA');
        
        $usuario = Auth::user();
        $numeroOpe = $usuario->puntoVenta->numerosOperacion->where('tipo_comprobante_id', config('caja.tipo_comprobante.BOLETA'))->first();
        $comprobante->serie = $numeroOpe->serie;
        $comprobante->correlativo = $numeroOpe->correlativo;
        
        /*$comprobante->serie = "B001";

        if (!$ultima_boleta) {
            $comprobante->correlativo = '00000001';
        } else {
            $ultima_boleta->correlativo += 1;
            $comprobante->correlativo = str_pad($ultima_boleta->correlativo, 8, "0", STR_PAD_LEFT);
        }*/

        $comprobante->total_descuento = "";
        $comprobante->total_impuesto = "";
        $comprobante->total = "";
        $comprobante->detalles = array();

        $data = [
            'tipo_comprobante' => 'BOLETA',
            'particular' => $request->particular['apellidos'] . ", " . $request->particular['nombres'],
            'email' => $request->particular['email'],
            'fecha_actual' => Carbon::now('America/Lima')->format('Y-m-d')
        ];

        return Inertia::render('Comprobantes/Cabecera', compact('comprobante', 'data'));
    }

    public function crear_empresa(Request $request)
    {
        $ultima_factura = Comprobante::where('tipo_usuario', 'empresa')->latest()->first();

        $comprobante = new Comprobante();

        $comprobante->tipo_usuario = "empresa";
        $comprobante->codi_usuario = $request->empresa['ruc'];
        $comprobante->nues_espe = "";
        $comprobante->tipo_comprobante_id = config('caja.tipo_comprobante.FACTURA');
        
        $usuario = Auth::user();
        $numeroOpe = $usuario->puntoVenta->numerosOperacion->where('tipo_comprobante_id', config('caja.tipo_comprobante.FACTURA'))->first();
        $comprobante->serie = $numeroOpe->serie;
        $comprobante->correlativo = $numeroOpe->correlativo;
        
        /*$comprobante->serie = "F001";

        if (!$ultima_factura) {
            $comprobante->correlativo = '00000001';
        } else {
            $ultima_factura->correlativo += 1;
            $comprobante->correlativo = str_pad($ultima_factura->correlativo, 8, "0", STR_PAD_LEFT);
        }*/

        $comprobante->total_descuento = "";
        $comprobante->total_impuesto = "";
        $comprobante->total = "";
        $comprobante->detalles = array();

        $data = [
            'tipo_comprobante' => 'FACTURA',
            'razon_social' => $request->empresa['razon_social'],
            'email' => $request->empresa['email'],
            'direccion' => $request->empresa['direccion'],
            'fecha_actual' => Carbon::now('America/Lima')->format('Y-m-d')
        ];

        return Inertia::render('Comprobantes/Cabecera', compact('comprobante', 'data'));
    }

    public function crear_nota(Request $request)
    {
        $comprobante = Comprobante::with('detalles')->where('serie', $request->serie)
                                    ->where('correlativo', $request->correlativo)
                                    ->first();
        
        $usuario = Auth::user();
        $numeroOpe = $usuario->puntoVenta->numerosOperacion->where('tipo_comprobante_id', config('caja.tipo_comprobante.' . $request->tipo_comprobante))->first();
        
        $data = [
            'tipo_comprobante' => $request->tipo_comprobante,
            'serie' => $numeroOpe->serie,
            'correlativo' => $numeroOpe->correlativo,
            'fecha_actual' => Carbon::now('America/Lima')->format('Y-m-d')
        ];

        return Inertia::render('Comprobantes/Nota', compact('comprobante', 'data'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $comprobante = new Comprobante();

            $comprobante->tipo_usuario = $request->tipo_usuario;
            $comprobante->codi_usuario = $request->codi_usuario;
            $comprobante->nues_espe = $request->nues_espe;
            $comprobante->tipo_comprobante_id = $request->tipo_comprobante_id;
            $comprobante->serie = $request->serie;
            $comprobante->correlativo = $request->correlativo;
            $comprobante->total_descuento = 10.00;
            $comprobante->total_impuesto = 100.00;
            $comprobante->total = $request->total;
            $comprobante->estado = 'noEnviado';
            $comprobante->cajero_id = Auth::user()->id;
            $comprobante->save();

            foreach ($request->detalles as $index => $detalle) {
                $detalle_comprobante = new DetallesComprobante();
                $detalle_comprobante->cantidad = $detalle['cantidad'];
                $detalle_comprobante->valor_unitario =  $detalle['precio'];
                $detalle_comprobante->descuento =  $detalle['descuento'];
                $detalle_comprobante->concepto_id =  $detalle['concepto_id'];
                $detalle_comprobante->comprobante_id =  $comprobante->id;
                $detalle_comprobante->save();
            }

            $result = [
                'successMessage' => 'Comprobante Registrado con exito',
                'comprobante_id' => $comprobante->id,
                'error' => false
            ];

            $numeroComp = NumeroOperacion::where('serie', $comprobante->serie)->first();
            $numeroComp->correlativo = str_pad($numeroComp->correlativo + 1, 8, "0", STR_PAD_LEFT);
            $numeroComp->update();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $result = ['errorMessage' => 'No se pudo registrar el comprobante', 'error' => true];
            Log::error('ComprobanteController@store, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }
        return $result;
    }


    public function show(Comprobante $comprobante)
    {
        $comprobante = Comprobante::with('detalles')->where('id', 'like', $comprobante->id)->first();

        return Inertia::render('Comprobantes/Detalle', compact('comprobante'));
    }

    public function showConsulta(Comprobante $comprobante)
    {
        $comprobante = Comprobante::with('comprobanteable')->with('tipo_comprobante')->with('detalles.concepto')->where('id', 'like', $comprobante->id)->first();
        $matricula = Alumno::with('matriculas.escuela')->where('cui', $comprobante->codi_usuario)->select('cui', 'dic', 'apn')->first();

        if ($comprobante->tipo_usuario == 'empresa') {
            $data = [
                'tipo_comprobante' => 'FACTURA',
                'razon_social' => $comprobante->comprobanteable['razon_social'],
                'email' => $comprobante->comprobanteable['email'],
                'direccion' => $comprobante->comprobanteable['direccion'],
                'fecha_actual' => Carbon::now('America/Lima')->format('Y-m-d')
            ];
            return Inertia::render('Comprobantes/Cabecera', compact('comprobante', 'data'));
        } else if ($comprobante->tipo_usuario == 'alumno') {
            $tipo_de_documento = '';
            $numero_de_documento = '';
            if (strlen($comprobante->comprobanteable['dic']) > 8) {
                switch ($comprobante->comprobanteable['dic'][0]) {
                    case 'D':
                        $tipo_de_documento = 'DNI';
                        $numero_de_documento = substr($comprobante->comprobanteable['dic'], 1, -1);
                        break;
                    case 'P':
                        $tipo_de_documento = 'Pasaporte';
                        $numero_de_documento = substr($comprobante->comprobanteable['dic'], 1, -1);
                        break;
                    case 'E':
                        $tipo_de_documento = 'Carnet Extra.';
                        $numero_de_documento = substr($comprobante->comprobanteable['dic'], 1, -1);
                        break;

                    default:
                        # code...
                        break;
                }
            } else if (strlen($comprobante->comprobanteable['dic']) == 8) {
                $tipo_de_documento = 'DNI';
                $numero_de_documento = $comprobante->comprobanteable['dic'];
            }
            $data = [
                'tipo_comprobante' => 'BOLETA',
                'tipo_doc' => $tipo_de_documento,
                'ndoc' => $numero_de_documento,
                'escuela' => $matricula->matriculas[0]->escuela['nesc'],
                'alumno' => $comprobante->comprobanteable['apn'],
                //'email' => $email->mail . '@unsa.edu.pe',
                'fecha_actual' => Carbon::now('America/Lima')->format('Y-m-d')
            ];
            return Inertia::render('Comprobantes/Cabecera', compact('comprobante', 'data'));
        } else if ($comprobante->tipo_usuario == 'docente') {
            $depa = Departamento::where('depa', '=',  $comprobante->comprobanteable['depend'])->first();

            $data = [
                'tipo_comprobante' => 'BOLETA',
                'dni' =>   $comprobante->comprobanteable['dic'],
                'docente' => str_replace("/", " ",   $comprobante->comprobanteable['apn']),
                'email' =>   $comprobante->comprobanteable['correo'] . '@unsa.edu.pe',
                'departamento' => $depa->ndep,
                'fecha_actual' => Carbon::now('America/Lima')->format('Y-m-d')
            ];
            return Inertia::render('Comprobantes/Cabecera', compact('comprobante', 'data'));
        } else if ($comprobante->tipo_usuario == 'dependencia') {
            $data = [
                'tipo_comprobante' => 'BOLETA',
                'dependencia' => $comprobante->comprobanteable['nomb_depe'],
                'email' => 'sizaisi@unsa.edu.pe',
                'fecha_actual' => Carbon::now('America/Lima')->format('Y-m-d')
            ];
            return Inertia::render('Comprobantes/Cabecera', compact('comprobante', 'data'));
        } else if ($comprobante->tipo_usuario == 'particular') {
            $data = [
                'tipo_comprobante' => 'BOLETA',
                'particular' => $comprobante->comprobanteable['apellidos'] . ", " . $comprobante->comprobanteable['nombres'],
                'email' => $comprobante->comprobanteable['email'],
                'fecha_actual' => Carbon::now('America/Lima')->format('Y-m-d')
            ];
            return Inertia::render('Comprobantes/Cabecera', compact('comprobante', 'data'));
        }
    }

    public function anular(Comprobante $comprobante)
    {
        try {
            $comprobante->estado = 'anulado';
            $comprobante->update();
            $result = ['successMessage' => 'Comprobante anulado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo anular el comprobante'];
            Log::error('ComprobanteController@anular, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->route('comprobantes.iniciar')->with($result);
    }

    public function enviarCorreo(Request $request)
    {
        EnviarCorreosJob::dispatch($request->to);

        //Mail::to($request->to)->queue(new CobroRealizadoMailable($request->to));
        //$result = ['successMessage' => 'Particular registrado con éxito', 'error' => false];
        //return $result;
    }
    public function consultas(Request $request)
    {
        //$this->authorize("viewAny", Comprobante::class);

        $query = Comprobante::with('comprobanteable')->with('tipo_comprobante')->with('detalles')
            ->where('codi_usuario', 'like', '%' . $request->filter . '%')
            ->where('serie', 'ILIKE', '%' . $request->serie . '%')
            ->where('correlativo', 'ILIKE', '%' . $request->correlativo . '%')
            ->latest();

        $sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {
            $sortdirection = $request->sortdesc == "true" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
        }

        return $query->paginate($request->size);
    }

    public function buscarUsuario(Request $request)
    {
        if ($request->tipo_usuario == 'ALUMNO') {
            if ($request->opcion_busqueda == 'CUI') {
                $query = Alumno::with('matriculas.escuela')
                    ->where('cui', $request->filtro)->select('cui', 'dic', 'apn');
            } else if ($request->opcion_busqueda == 'APN') {
                $query = Alumno::with('matriculas.escuela')
                    ->whereRaw("REPLACE(apn, '/', ' ') like ?", [$request->filtro . '%'])
                    ->select('cui', 'dic', 'apn')
                    ->orderBy('apn', 'asc');
            }
        } else if ($request->tipo_usuario == 'PARTICULAR') {
            if ($request->opcion_busqueda == 'DNI') {
                $query = Particular::where('dni', $request->filtro);
            } else if ($request->opcion_busqueda == 'APN') {
                $query = Particular::whereRaw("CONCAT(apellidos, ' ', nombres) ilike ? ", [$request->filtro . '%'])
                    ->orderBy('apellidos', 'asc');
            }
        } else if ($request->tipo_usuario == 'EMPRESA') {
            if ($request->opcion_busqueda == 'RUC') {
                $query = Empresa::where('ruc', $request->filtro);
            } else if ($request->opcion_busqueda == 'RAZON_SOCIAL') {
                $query = Empresa::where('razon_social', 'ilike', '%' . $request->filtro . '%')
                    ->orderBy('razon_social', 'asc');
            }
        } else if ($request->tipo_usuario == 'DOCENTE') {
            if ($request->opcion_busqueda == 'CODIGO') {
                $query = Docente::where('codper', $request->filtro)
                    ->where('esta_doc', 'A')
                    ->select('depend', 'codper', 'dic', 'apn', 'correo');
            } else if ($request->opcion_busqueda == 'APN') {
                $query = Docente::whereRaw("REPLACE(apn, '/', ' ') like ?", [$request->filtro . '%'])
                    ->where('esta_doc', 'A')
                    ->select('depend', 'codper', 'dic', 'apn', 'correo')
                    ->orderBy('apn', 'asc');
            }
        } else if ($request->tipo_usuario == 'DEPENDENCIA') {
            if ($request->opcion_busqueda == 'CODIGO') {
                $query = Dependencia::where('codi_depe', $request->filtro)
                    ->select('codi_depe', 'nomb_depe', 'mail_depe');
            } else if ($request->opcion_busqueda == 'NOMBRE') {
                $query = Dependencia::where('nomb_depe', 'like', '%' . $request->filtro . '%')
                    ->select('codi_depe', 'nomb_depe', 'mail_depe')
                    ->orderBy('nomb_depe', 'asc');
            }
        }

        return $query->paginate($request->size);
    }

    public function generar_pdf(Request $request)
    {

        $comprobante = new Comprobante();

        $comprobante = Comprobante::with('comprobanteable')->with('tipo_comprobante')->with('detalles.concepto')->where('id', 'like', $request->comprobanteId)->first();

        $pdf = PDF::loadView('pdf.comprobante', compact('comprobante'));
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'portrait');
        $pdfGuardado = $pdf->output();

        $guardado = file_put_contents(storage_path('app/public/Sunat/PDF/' . $comprobante->serie . '-' . $comprobante->correlativo . '.pdf'), $pdfGuardado);
        if ($guardado) {
            $comprobante->url_pdf = $comprobante->serie . '-' . $comprobante->correlativo . '.pdf';
            $comprobante->update();
        }

        return $pdf->stream($comprobante->serie . '-' . $comprobante->correlativo . '.pdf');
    }
    public function generar_ticket(Request $request)
    {

        $comprobante = new Comprobante();

        $comprobante = Comprobante::with('comprobanteable')->with('tipo_comprobante')->with('detalles.concepto')->where('id', 'like', $request->comprobanteId)->first();

        $pdf = PDF::loadView('pdf.comprobanteTicket', compact('comprobante'));
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('b5', 'portrait');
        $pdfGuardado = $pdf->output();

        $guardado = file_put_contents(storage_path('app/public/Sunat/PDF/' . $comprobante->serie . '-' . $comprobante->correlativo . '-ticket' . '.pdf'), $pdfGuardado);
        if ($guardado) {
            $comprobante->url_ticket = $comprobante->serie . '-' . $comprobante->correlativo . '-ticket' . '.pdf';
            $comprobante->update();
        }

        return $pdf->stream($comprobante->serie . '-' . $comprobante->correlativo . '.pdf');
    }

    /*public function visualizar(Comprobante $comprobante)
    {
        $direccion_empresa = (new Address())
            ->setUbigueo(config('caja.direccion.ubigeo'))
            ->setDepartamento(config('caja.direccion.departamento'))
            ->setProvincia(config('caja.direccion.provincia'))
            ->setDistrito(config('caja.direccion.distrito'))
            ->setUrbanizacion(config('caja.direccion.urbanizacion'))
            ->setDireccion(config('caja.direccion.direccion'))
            ->setCodLocal(config('caja.direccion.codigo_local')); // Codigo de establecimiento asignado por SUNAT, 0000 por defecto.

        $empresa = (new Company())
            ->setRuc(config('caja.empresa.ruc'))
            ->setRazonSocial(config('caja.empresa.razon_social'))
            ->setNombreComercial(config('caja.empresa.nombre_comercial'))
            ->setAddress($direccion_empresa);

        $cobro = Comprobante::with('comprobanteable')->with('tipo_comprobante')
            ->with('detalles')->where('id', 'like', $comprobante->id)->first();

        try {

            // Cliente
            $client = (new Client())
                ->setTipoDoc('6')
                ->setNumDoc($cobro->comprobanteable->ruc)
                ->setRznSocial($cobro->comprobanteable->razon_social);

            // Venta
            $invoice = new Invoice();

            $total_gravadas = 0;
            $total_exonerados = 0;
            $total_inafectos = 0;
            $subtotal = 0;
            $igv = 0;

            $detalle = $cobro->detalles;
            foreach ($detalle as $index => $value) {
                $concepto = Concepto::with('tipo_concepto')
                    ->with('clasificador')
                    ->with('unidad_medida')
                    ->where('id', 'like', $cobro->detalles[$index]->concepto_id)->first();
                $items[$index] = (new SaleDetail())
                    ->setCodProducto($concepto->id)
                    ->setUnidad('NIU') // Unidad - Catalog. 03
                    ->setCantidad($value['cantidad'])
                    ->setMtoValorUnitario($value['valor_unitario'])
                    ->setDescripcion($concepto->descripcion)
                    ->setMtoBaseIgv(100.00)
                    ->setPorcentajeIgv(18.00) // 18%
                    ->setIgv(18.00 / $value['cantidad'])
                    ->setTipAfeIgv('10') // Gravado Op. Onerosa - Catalog. 07
                    ->setTotalImpuestos(18.00) // Suma de impuestos en el detalle
                    ->setMtoValorVenta($value['valor_unitario'] * $value['cantidad'])
                    ->setMtoPrecioUnitario($value['valor_unitario'] + 18.00 / $value['cantidad']);
                if ($concepto->tipo_afectacion == 10) {
                    $total_gravadas += $value['valor_unitario'] * $value['cantidad'];
                } elseif ($concepto->tipo_afectacion == 20) {
                    $total_exonerados += $value['valor_unitario'] * $value['cantidad'];
                } elseif ($concepto->tipo_afectacion == 30) {
                    $total_inafectos += $value['valor_unitario'] * $value['cantidad'];
                }
                $igv += 18.00 / $value['cantidad'];
            }

            $formatter = new NumeroALetras();
            $montoLetras = $formatter->toInvoice($cobro->total, 2, 'soles');

            $legend = (new Legend())
                ->setCode('1000') // Monto en letras - Catalog. 52
                ->setValue($montoLetras);

            $invoice->setDetails($items)->setLegends([$legend]);

            $invoice
                ->setUblVersion('2.1')
                ->setTipoOperacion('0101') // Venta - Catalog. 51
                ->setTipoDoc('01') // Factura - Catalog. 01
                ->setSerie($cobro->serie)
                ->setCorrelativo($cobro->correlativo)
                ->setFechaEmision(new DateTime(now())) // Zona horaria: Lima
                ->setFormaPago(new FormaPagoContado()) // FormaPago: Contado
                ->setTipoMoneda('PEN') // Sol - Catalog. 02
                ->setCompany($empresa)
                ->setClient($client)
                ->setMtoIGV($igv)
                ->setTotalImpuestos($igv);
            if ($total_gravadas) {
                $invoice
                    ->setValorVenta($total_gravadas)
                    ->setSubTotal($total_gravadas + $igv)
                    ->setMtoImpVenta($total_gravadas + $igv)
                    ->setMtoOperGravadas($total_gravadas);
            } else if ($total_exonerados) {
                $invoice
                    ->setValorVenta($total_exonerados)
                    ->setSubTotal($total_exonerados + $igv)
                    ->setMtoImpVenta($total_exonerados + $igv)
                    ->setMtoOperGravadas($total_exonerados);
            } else if ($total_inafectos) {
                $invoice
                    ->setValorVenta($total_inafectos)
                    ->setSubTotal($total_inafectos + $igv)
                    ->setMtoImpVenta($total_inafectos + $igv)
                    ->setMtoOperGravadas($total_inafectos);
            }

            if ($concepto->detraccion) {
                $invoice->setDetraccion(
                    // MONEDA SIEMPRE EN SOLES
                    (new Detraction())
                        // Carnes y despojos comestibles
                        ->setCodBienDetraccion('014') // catalog. 54
                        // Deposito en cuenta
                        ->setCodMedioPago('001') // catalog. 59
                        ->setCtaBanco('0004-3342343243')
                        ->setPercent(4.00)
                        ->setMount(37.76)
                );
            }

            $html = new HtmlReport();
            $html->setTemplate('invoice.html.twig');

            $report = new PdfReport($html);

            $report->setOptions([
                'no-outline',
                'viewport-size' => '1280x1024',
                'page-width' => '21cm',
                'page-height' => '29.7cm',
            ]);

            $report->setBinPath('C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe'); // Ruta relativa o absoluta de wkhtmltopdf para Windows
            //$report->setBinPath('config\Sunat\wkhtmltopdf-amd64'); // Ruta relativa o absoluta de wkhtmltopdf para Linux

            $params = [
                'system' => [
                    'logo' => file_get_contents(public_path() . '\img\siscaja_blanco.png'), // Logo de Empresa
                    'hash' => 'qqnr2dN4p/HmaEA/CJuVGo7dv5g=', // Valor Resumen
                ],
                'user' => [
                    'header'     => 'Telf: <b>(01) 123375</b>', // Texto que se ubica debajo de la dirección de empresa
                    'extras'     => [
                        // Leyendas adicionales
                        ['name' => 'CONDICION DE PAGO', 'value' => 'Efectivo'],
                        ['name' => 'VENDEDOR', 'value' => 'CAJA UNSA'],
                    ],
                    'footer' => '<p>Nro Resolucion: <b>3232323</b></p>'
                ]
            ];

            $pdf = $report->render($invoice, $params);

            if ($pdf === null) {
                $error = $report->getExporter()->getError();
                echo 'Error: ' . $error;
                return;
            }

            $pdfGuardado = file_put_contents(storage_path('app/public/Sunat/PDF/' . $cobro->serie . '-' . $cobro->correlativo . '.pdf'), $pdf);
            if ($pdfGuardado) {
                $cobro->url_pdf = $cobro->serie . '-' . $cobro->correlativo . '.pdf';
                $cobro->update();
            }
            return 1;
        } catch (\Exception $e) {
            echo $e;
            return 0;
        }
    }*/
}
