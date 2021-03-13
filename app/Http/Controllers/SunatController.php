<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class SunatController extends Controller
{
    public function enviarFacturas()
    {
        return Inertia::render('Sunat/EnviarFactura');
    }
    public function resumenBoletas()
    {
        return Inertia::render('Sunat/ResumenBoletas');
    }
    public function comunicacionBaja()
    {
        return Inertia::render('Sunat/ComunicacionBaja');
    }
    public function ticketCDR()
    {
        return Inertia::render('Sunat/TicketCDR');
    }
}
