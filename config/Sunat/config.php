<?php

use Greenter\Ws\Services\SunatEndpoints;
use Greenter\See;

$see = new See();
/************************** PRUEBA SUNAT ***************************/
$see->setCertificate(file_get_contents(__DIR__ . '/certificate.pem'));
$see->setService(SunatEndpoints::FE_BETA);
$see->setClaveSOL('20000000001', 'MODDATOS', 'moddatos');

/************************** PRODUCCION SUNAT ***************************/
/*$see->setCertificate(file_get_contents(__DIR__ . '/certificateOSCE.pem'));
$see->setService('https://seres-ose.seresnet.com/ol-ti-itcpe/billService?wsdl');
$see->setClaveSOL('20163646499', 'PROD2017', 'PROD2017');*/

return $see;
