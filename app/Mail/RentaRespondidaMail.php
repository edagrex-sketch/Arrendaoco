<?php

namespace App\Mail;

use App\Models\Contrato;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RentaRespondidaMail extends Mailable
{
    use Queueable, SerializesModels;

    public Contrato $contrato;
    public string   $accion; // 'aprobada' | 'rechazada'

    public function __construct(Contrato $contrato, string $accion)
    {
        $this->contrato = $contrato;
        $this->accion   = $accion;
    }

    public function build()
    {
        $asunto = $this->accion === 'aprobada'
            ? '✅ ¡Tu solicitud fue aprobada! — ArrendaOco'
            : '❌ Tu solicitud fue rechazada — ArrendaOco';

        return $this
            ->subject($asunto)
            ->view('emails.renta_respondida')
            ->with(['accion' => $this->accion]);
    }
}
