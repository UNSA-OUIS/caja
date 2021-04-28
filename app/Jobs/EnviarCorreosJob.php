<?php

namespace App\Jobs;

use App\Mail\CobroRealizadoMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EnviarCorreosJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $mail_to;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mail_to)
    {
        $this->mail_to = $mail_to;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new CobroRealizadoMailable($this->mail_to);
        Mail::to($this->mail_to)->send($email);
    }
}
