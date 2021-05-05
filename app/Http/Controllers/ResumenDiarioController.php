<?php

namespace App\Http\Controllers;


use App\Models\Comprobante;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Illuminate\Http\Request;

class ResumenDiarioController extends Controller
{
    private $empresa;

    function __construct()
    {
        $direccion_empresa = (new Address())
            ->setUbigueo(config('caja.direccion.ubigeo'))
            ->setDepartamento(config('caja.direccion.departamento'))
            ->setProvincia(config('caja.direccion.provincia'))
            ->setDistrito(config('caja.direccion.distrito'))
            ->setUrbanizacion(config('caja.direccion.urbanizacion'))
            ->setDireccion(config('caja.direccion.direccion'))
            ->setCodLocal(config('caja.direccion.codigo_local')); // Codigo de establecimiento asignado por SUNAT, 0000 por defecto.

        $this->empresa = (new Company())
            ->setRuc(config('caja.empresa.ruc'))
            ->setRazonSocial(config('caja.empresa.razon_social'))
            ->setNombreComercial(config('caja.empresa.nombre_comercial'))
            ->setAddress($direccion_empresa);
    }


    public function filtrar(Request $request)
    {
        $comprobantes = Comprobante::with('detalles')
            ->whereDate('created_at', '>=', $request->fechaInicio)
            ->whereDate(
                'created_at',
                '<=',
                $request->fechaFin
            )->where('serie', 'like', 'B' . '%')->get();
        return ['comprobantes' => $comprobantes];
    }
}
