<?php

namespace App\Mail;

use App\Models\EstadoCuenta;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EstadoCuentaMail extends Mailable
{
    use Queueable, SerializesModels;

    public EstadoCuenta $estadoCuenta;

    public function __construct(EstadoCuenta $estadoCuenta)
    {
        $this->estadoCuenta = $estadoCuenta;
    }

    public function build()
    {
        return $this
            ->subject('Estado de Cuenta - ArrendaOco')
            ->view('emails.estado_cuenta')
            ->attach(
                storage_path('app/' . $this->estadoCuenta->ruta_pdf),
                ['as' => 'estado_cuenta.pdf']
            );
    }
}
