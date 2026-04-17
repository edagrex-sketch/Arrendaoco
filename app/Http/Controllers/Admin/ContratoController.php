<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ContratoController extends Controller
{
    public function index(Request $request)
    {
        $query = Contrato::with(['inmueble', 'propietario', 'inquilino']);
        $this->applyFilters($query, $request);

        $contratos = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('admin.contratos.index', compact('contratos'));
    }

    public function reporte(Request $request)
    {
        $query = Contrato::with(['inmueble', 'propietario', 'inquilino']);
        $this->applyFilters($query, $request);

        $contratos = $query->orderBy('created_at', 'desc')->get();
        $pdf = Pdf::loadView('admin.contratos.reporte', compact('contratos'));
        return $pdf->download('reporte_contratos.pdf');
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('inmueble', function($sq) use ($search) {
                    $sq->where('titulo', 'like', "%$search%");
                })
                ->orWhereHas('propietario', function($sq) use ($search) {
                    $sq->where('nombre', 'like', "%$search%");
                })
                ->orWhereHas('inquilino', function($sq) use ($search) {
                    $sq->where('nombre', 'like', "%$search%");
                });
            });
        }

        if ($request->filled('estatus')) {
            $query->where('estatus', $request->estatus);
        }

        if ($request->filled('desde')) {
            $query->whereDate('created_at', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('created_at', '<=', $request->hasta);
        }
    }
}
