<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CodigoMailable extends Mailable
{
    use Queueable, SerializesModels;    

    protected $codigo;
    protected $escuela;
    protected $tipo_pago;
    protected $apn;
    protected $cui;
    protected $dni;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($codigo, $escuela, $tipo_pago, $apn, $cui, $dni)
    {
        $this->codigo = $codigo;
        $this->escuela = $escuela;
        $this->tipo_pago = $tipo_pago;
        $this->apn = $apn;
        $this->cui = $cui;
        $this->dni = $dni;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {        
        return $this->subject('Código bancario asignado - ' . $this->tipo_pago)
                    ->view('emails.codigo')
                    ->with([
                        'codigo' => $this->codigo,
                        'escuela' => $this->escuela,
                        'tipo_pago' => $this->tipo_pago,
                        'apn' => $this->apn,
                        'cui' => $this->cui,
                        'dni' => $this->dni,
                    ]);
    }
}
