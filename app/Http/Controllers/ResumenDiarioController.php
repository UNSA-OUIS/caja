<?php

namespace App\Http\Controllers;


use App\Models\Comprobante;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\Document;
use Greenter\Model\Summary\Summary;
use Greenter\Model\Summary\SummaryDetail;
use Greenter\Report\HtmlReport;
use Greenter\Report\PdfReport;
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

    public function resumenDiario(Request $request)
    {
        $see = require config_path('Sunat\config.php');

        $detail = new SummaryDetail();
        $detail->setTipoDoc('07') // Nota de Credito
            ->setSerieNro('B001-4')
            ->setDocReferencia((new Document()) // Documento relacionado (Boleta)
                ->setTipoDoc('03')
                ->setNroDoc('B001-1'))
            ->setEstado('1') // Emisión
            ->setClienteTipo('1') // Tipo documento identidad: DNI
            ->setClienteNro('00000000') // Nro de documento identidad
            ->setTotal(200)
            ->setMtoOperGravadas(40)
            ->setMtoOperExoneradas(50)
            ->setMtoOperInafectas(100)
            ->setMtoIGV(7.2)
            ->setMtoISC(2.8);

        $detail2 = new SummaryDetail();
        $detail2->setTipoDoc('03') // Boleta
            ->setSerieNro('B001-2')
            ->setEstado('3') // Anulación
            ->setClienteTipo('1')
            ->setClienteNro('00000000')
            ->setTotal(119)
            ->setMtoOperGravadas(20)
            ->setMtoOperInafectas(24.4)
            ->setMtoOperExoneradas(50)
            ->setMtoOtrosCargos(21)
            ->setMtoIGV(3.6);

        $resumen = new Summary();
        $resumen->setFecGeneracion(new \DateTime('2020-08-01')) // Fecha de emisión de las boletas.
            ->setFecResumen(new \DateTime('2020-08-02')) // Fecha de envío del resumen diario.
            ->setCorrelativo('001') // Correlativo, necesario para diferenciar de otros Resumen diario del mismo día.
            ->setCompany($this->empresa)
            ->setDetails([$detail, $detail2]);

        $result = $see->send($resumen);
        // Guardar XML
        file_put_contents(
            $resumen->getName() . '.xml',
            $see->getFactory()->getLastXml()
        );

        if (!$result->isSuccess()) {
            // Si hubo error al conectarse al servicio de SUNAT.
            var_dump($result->getError());
            exit();
        }

        $ticket = $result->getTicket();
        echo 'Ticket : ' . $ticket . PHP_EOL;

        $statusResult = $see->getStatus($ticket);
        if (!$statusResult->isSuccess()) {
            // Si hubo error al conectarse al servicio de SUNAT.
            var_dump($statusResult->getError());
            return;
        }

        echo $statusResult->getCdrResponse()->getDescription();
        // Guardar CDR
        file_put_contents('R-' . $resumen->getName() . '.zip', $statusResult->getCdrZip());

        $html = new HtmlReport();
        $html->setTemplate('invoice.html.twig');

        $report = new PdfReport($html);

        $report->setOptions([
            'no-outline',
            'viewport-size' => '1280x1024',
            'page-width' => '21cm',
            'page-height' => '29.7cm',
        ]);
        $report->setBinPath('C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe'); // Ruta relativa o absoluta de wkhtmltopdf

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

        $pdf = $report->render($resumen, $params);

        if ($pdf === null) {
            $error = $report->getExporter()->getError();
            echo 'Error: ' . $error;
            return;
        }

        file_put_contents($resumen->getName() . '.pdf', $pdf);
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
