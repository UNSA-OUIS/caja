<?php

namespace App\Http\Controllers;

use App\Models\NotaCredito;
use DateTime;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\Note;
use Greenter\Model\Sale\SaleDetail;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotaCreditoController extends Controller
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
    public function index(Request $request)
    {
        //$this->authorize("viewAny", Comprobante::class);

        $query = NotaCredito::with('detalles')->where('codigo', 'like', '%' . $request->filter . '%');

        $sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {
            $sortdirection = $request->sortdesc == "true" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
        }

        return $query->paginate($request->size);
    }
    public function create(Request $request)
    {
        return Inertia::render('Sunat/NuevoMostrarNotasCredito');
    }
    public function store(Request $request)
    {
        echo 'create de Nota Credito COntroller';
    }
    public function anular(NotaCredito $comprobante)
    {
        echo 'create de Nota Credito COntroller';
    }
    public function enviar(NotaCredito $nota_credito)
    {
        $see = require config_path('Sunat\config.php');

        // Cliente
        $client = (new Client())
            ->setTipoDoc('6')
            ->setNumDoc('10723516108')
            ->setRznSocial('Jesus Ruben Ortiz Chavez');

        $note = new Note();
        $note
            ->setUblVersion('2.1')
            ->setTipoDoc('07')
            ->setSerie('FF01')
            ->setCorrelativo('123')
            ->setFechaEmision(new DateTime())
            ->setTipDocAfectado('01') // Tipo Doc: Factura
            ->setNumDocfectado('F001-111') // Factura: Serie-Correlativo
            ->setCodMotivo('07') // Catalogo. 09
            ->setDesMotivo('DEVOLUCION POR ITEM')
            ->setTipoMoneda('PEN')
            /* Guias (Opcional) */
            /* ->setGuias([
                (new Document())
                    ->setTipoDoc('09')
                    ->setNroDoc('0001-213')
            ])*/
            ->setCompany($this->empresa)
            ->setClient($client)
            ->setMtoOperGravadas(200)
            ->setMtoIGV(36)
            ->setTotalImpuestos(36)
            ->setMtoImpVenta(236);

        $detail1 = new SaleDetail();
        $detail1
            ->setCodProducto('C023')
            ->setUnidad('NIU')
            ->setCantidad(2)
            ->setDescripcion('PROD 1')
            ->setMtoBaseIgv(100)
            ->setPorcentajeIgv(18.00)
            ->setIgv(18)
            ->setTipAfeIgv('10')
            ->setTotalImpuestos(18)
            ->setMtoValorVenta(100)
            ->setMtoValorUnitario(50)
            ->setMtoPrecioUnitario(56);

        $detail2 = new SaleDetail();
        $detail2
            ->setCodProducto('C02')
            ->setUnidad('NIU')
            ->setCantidad(2)
            ->setDescripcion('PROD 2')
            ->setMtoBaseIgv(100)
            ->setPorcentajeIgv(18.00)
            ->setIgv(18)
            ->setTipAfeIgv('10')
            ->setTotalImpuestos(18)
            ->setMtoValorVenta(100)
            ->setMtoValorUnitario(50)
            ->setMtoPrecioUnitario(56);

        $legend = new Legend();
        $legend->setCode('1000')
            ->setValue('SON DOSCIENTOS TREINTA Y SEIS CON 00/100 SOLES');

        $note->setDetails([$detail1, $detail2])
            ->setLegends([$legend]);

        $result = $see->send($note);

        file_put_contents(
            $note->getName() . '.xml',
            $see->getFactory()->getLastXml()
        );

        // Verificamos que la conexión con SUNAT fue exitosa.
        if (!$result->isSuccess()) {
            // Mostrar error al conectarse a SUNAT.
            echo 'Codigo Error: ' . $result->getError()->getCode() . '\n' . 'Mensaje Error: ' . $result->getError()->getMessage();
            exit();
        }

        file_put_contents('R-' . $note->getName() . '.zip', $result->getCdrZip());
        $cdr = $result->getCdrResponse();

        $code = (int)$cdr->getCode();

        if ($code === 0) {
            echo $cdr->getDescription() . PHP_EOL;
            if (count($cdr->getNotes()) > 0) {
                // Corregir estas observaciones en siguientes emisiones.
                foreach ($cdr->getNotes() as $index => $value) {
                    echo json_encode('Observacion #' . $index . '=>' . $value, JSON_UNESCAPED_UNICODE) . "\n";
                }
            }
        } else if ($code >= 2000 && $code <= 3999) {
            echo 'rechazado';
        } else {
            /* Esto no debería darse, pero si ocurre, es un CDR inválido que debería tratarse como un error-excepción. */
            /*code: 0100 a 1999 */
            echo 'rechazado';
        }
    }
}
