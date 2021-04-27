<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CobroRealizadoMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $mail_to;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mail_to)
    {
        $this->mail_to = $mail_to;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Cobro realizado')
                    ->view('emails.cobro');
    }
}
