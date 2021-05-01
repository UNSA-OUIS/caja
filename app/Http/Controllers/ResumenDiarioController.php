<?php

namespace App\Http\Controllers;


use App\Models\Comprobante;
use DateTime;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Summary\Summary;
use Greenter\Model\Summary\SummaryDetail;
use Greenter\Report\HtmlReport;
use Greenter\Report\PdfReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Luecano\NumeroALetras\NumeroALetras;

class ResumenDiarioController extends Controller
{

    public function resumenDiario(Request $request)
    {
        //$this->authorize("viewAny", Comprobante::class);

        $see = require storage_path() . '\app\public\config.php';

        $query = Comprobante::with('detalles')->where('codigo', 'like', '%' . $request->filter . '%')->where('serie', 'like', 'B' . '%');

        $sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {
            $sortdirection = $request->sortdesc == "true" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
        }

        $boletas = $query->paginate($request->size)->all();
        //return $boletas;

        $company = new Company();
        $company->setRuc('20163646499')
            ->setRazonSocial('UNIVERSIDAD NACIONAL DE SAN AGUSTIN')
            ->setNombreComercial('UNIVERSIDAD NACIONAL DE SAN AGUSTIN')
            ->setAddress((new Address())
                ->setUbigueo('150101')
                ->setDepartamento('AREQUIPA')
                ->setProvincia('AREQUIPA')
                ->setDistrito('AREQUIPA')
                ->setUrbanizacion('-')
                ->setDireccion('CALLE SANTA CATALINA 117')
                ->setCodLocal('0000'));

        $detail = new SummaryDetail();
        $detail->setTipoDoc('03') // Boleta
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
            ->setCompany($company)
            ->setDetails([$detail]);

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
