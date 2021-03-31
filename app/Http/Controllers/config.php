<?php

use Greenter\See;
use Greenter\XMLSecLibs\Certificate\X509Certificate;
use Greenter\XMLSecLibs\Certificate\X509ContentType;

$see = new See();

$pfx = file_get_contents(__DIR__ . '/LLAMA-PE-CERTIFICADO-DEMO-10723516108.pfx');
$password = 'ivanna25092019';

$certificate = new X509Certificate($pfx, $password);

$see->setCertificate($certificate->export(X509ContentType::PEM));


return $see;
