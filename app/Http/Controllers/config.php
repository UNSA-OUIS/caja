<?php
use Greenter\Ws\Services\SunatEndpoints;
use Greenter\See;

$see = new See();
$see->setCertificate(file_get_contents(__DIR__.'/certificate.pem'));
$see->setService('https://seres-ose.seresnet.com/ol-ti-itcpe/billService?wsdl');
$see->setClaveSOL('20000000001', '20163646499PROD2017', 'PROD2017');

return $see;