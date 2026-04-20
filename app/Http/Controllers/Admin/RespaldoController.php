<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionRespaldo;
use App\Models\RespaldoLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RespaldoController extends Controller
{
    public function index()
    {
        $config = ConfiguracionRespaldo::first();
        $logs = RespaldoLog::latest()->take(10)->get();

        // Calcular tiempo restante para el próximo respaldo automático
        $proximoRespaldo = null;
        $tiempoRestante = null;

        if ($config->automatico) {
            $base = $config->ultimo_respaldo_auto ?: $config->created_at;
            $proximoRespaldo = Carbon::parse($base)->addDays($config->frecuencia);
            
            if ($proximoRespaldo->isPast()) {
                $proximoRespaldo = now()->addMinutes(5); // Si ya pasó y no se ejecutó, asumimos pronto
            }
            
            $diff = now()->diff($proximoRespaldo);
            $tiempoRestante = [
                'dias' => $diff->d,
                'horas' => $diff->h,
                'minutos' => $diff->i,
            ];
        }

        // Conteo de respaldos manuales en las últimas 24 horas
        $respaldosHoy = RespaldoLog::where('tipo', 'manual')
            ->where('created_at', '>=', now()->subDay())
            ->count();

        return view('admin.respaldos.index', compact('config', 'logs', 'tiempoRestante', 'respaldosHoy', 'proximoRespaldo'));
    }

    public function configurar(Request $request)
    {
        $request->validate([
            'automatico' => 'required|boolean',
            'frecuencia' => 'required|integer|in:1,3,5,7,15',
        ]);

        $config = ConfiguracionRespaldo::first();
        $config->update([
            'automatico' => $request->automatico,
            'frecuencia' => $request->frecuencia,
        ]);

        return back()->with('success', 'Configuración de respaldo actualizada.');
    }

    public function ejecutar()
    {
        $config = ConfiguracionRespaldo::first();
        $respaldosHoy = RespaldoLog::where('tipo', 'manual')
            ->where('created_at', '>=', now()->subDay())
            ->count();

        if ($respaldosHoy >= 4) {
            if (!$config->automatico) {
                return back()->with('error_limite', 'Has alcanzado el límite de 4 respaldos manuales en 24 horas. Te sugerimos activar el respaldo diario para mayor seguridad.');
            } else {
                return back()->with('error_limite', 'Has alcanzado el límite de 4 respaldos manuales en 24 horas. Por favor, espera a que se restablezca el tiempo permitido o al siguiente respaldo programado.');
            }
        }

        // Simulación de ejecución de respaldo
        try {
            $nombreArchivo = 'backup_' . now()->format('Y_m_d_His') . '.sql';
            
            RespaldoLog::create([
                'tipo' => 'manual',
                'nombre_archivo' => $nombreArchivo,
                'ruta' => 'backups/' . $nombreArchivo,
                'tamano' => rand(1, 5) . ' MB',
                'estatus' => 'exitoso',
            ]);

            return back()->with('success', 'Respaldo manual ejecutado exitosamente.');
        } catch (\Exception $e) {
            RespaldoLog::create([
                'tipo' => 'manual',
                'estatus' => 'fallido',
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Error al ejecutar el respaldo: ' . $e->getMessage());
        }
    }

    public function restaurar(Request $request, $id)
    {
        $request->validate([
            'password' => 'required',
        ]);

        // Verificar contraseña del administrador
        if (!\Illuminate\Support\Facades\Hash::check($request->password, auth()->user()->password)) {
            return back()->with('error', 'La contraseña ingresada es incorrecta. No se pudo realizar la restauración.');
        }

        $log = RespaldoLog::findOrFail($id);

        if ($log->estatus !== 'exitoso') {
            return back()->with('error', 'No se puede restaurar un respaldo fallido.');
        }

        try {
            // Aquí iría la lógica real de restauración:
            // 1. Desactivar llaves foráneas
            // 2. Ejecutar el archivo SQL
            // 3. Reactivar llaves foráneas
            
            return back()->with('success', 'El sistema ha sido restaurado exitosamente al punto: ' . $log->nombre_archivo);
        } catch (\Exception $e) {
            return back()->with('error', 'Error crítico durante la restauración: ' . $e->getMessage());
        }
    }
}
