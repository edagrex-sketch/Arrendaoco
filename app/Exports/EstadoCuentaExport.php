<?php

namespace App\Exports;

use App\Models\Contrato;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EstadoCuentaExport implements FromCollection, WithHeadings
{
    protected $contrato;

    public function __construct(Contrato $contrato)
    {
        $this->contrato = $contrato;
    }

    public function collection()
    {
        return $this->contrato->pagos->map(function ($pago) {
            return [
                'Mes' => $pago->mes,
                'Año' => $pago->anio,
                'Estatus' => $pago->estatus,
                'Monto base' => $pago->monto,
                'Recargo' => $pago->recargo,
                'Total a pagar' => $pago->estatus === 'pagado'
                    ? $pago->monto
                    : $pago->total_con_recargo,
                'Días atraso' => $pago->dias_atraso,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Mes',
            'Año',
            'Estatus',
            'Monto base',
            'Recargo',
            'Total a pagar',
            'Días atraso',
        ];
    }
}
