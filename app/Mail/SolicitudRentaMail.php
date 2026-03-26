<?php

namespace App\Mail;

use App\Models\Contrato;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SolicitudRentaMail extends Mailable
{
    use Queueable, SerializesModels;

    public Contrato $contrato;

    public function __construct(Contrato $contrato)
    {
        $this->contrato = $contrato;
    }

    public function build()
    {
        return $this
            ->subject('Nueva Solicitud de Renta — ArrendaOco')
            ->view('emails.solicitud_renta');
    }
}
