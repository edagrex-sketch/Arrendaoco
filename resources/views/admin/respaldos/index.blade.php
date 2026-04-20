@extends('layouts.admin')

@section('title', 'Gestión de Respaldos')
@section('page-title', 'Respaldos de Base de Datos')
@section('page-subtitle', 'Configura y gestiona las copias de seguridad del sistema')

@section('content')
<div class="max-w-6xl mx-auto space-y-8 admin-animate-in">

    {{-- ALERTAS DE LÍMITE --}}
    @if(session('error_limite'))
    <div class="bg-amber-50 border-l-4 border-amber-400 p-6 rounded-2xl shadow-sm">
        <div class="flex items-start gap-4">
            <div class="p-2 bg-amber-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-black text-amber-800 uppercase tracking-widest mb-1">Límite alcanzado</h3>
                <p class="text-sm text-amber-700 leading-relaxed">{{ session('error_limite') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        {{-- CONFIGURACIÓN AUTOMÁTICA --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
            <div class="p-8 border-b border-slate-50 bg-slate-50/30">
                <div class="flex items-center gap-3 mb-1">
                    <div class="p-2 bg-[#003049]/5 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#003049]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="text-sm font-black text-[#003049] uppercase tracking-widest">Respaldo Automático</h2>
                </div>
                <p class="text-xs text-slate-400">Automatiza la seguridad de tu información</p>
            </div>

            <form action="{{ route('admin.respaldos.configurar') }}" method="POST" class="p-8 space-y-8 flex-1 flex flex-col">
                @csrf
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div>
                        <p class="text-sm font-bold text-[#003049]">Estado del Servicio</p>
                        <p class="text-xs text-slate-400">{{ $config->automatico ? 'Activado y programado' : 'Desactivado actualmente' }}</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="automatico" value="0">
                        <input type="checkbox" name="automatico" value="1" class="sr-only peer" {{ $config->automatico ? 'checked' : '' }}>
                        <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-6 after:transition-all peer-checked:bg-[#669BBC]"></div>
                    </label>
                </div>

                <div class="space-y-3">
                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest">Frecuencia de ejecución</label>
                    <select name="frecuencia" class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-[#003049] focus:ring-4 focus:ring-[#669BBC]/10 focus:border-[#669BBC] transition-all outline-none">
                        <option value="1" {{ $config->frecuencia == 1 ? 'selected' : '' }}>Diariamente</option>
                        <option value="3" {{ $config->frecuencia == 3 ? 'selected' : '' }}>Cada 3 días</option>
                        <option value="5" {{ $config->frecuencia == 5 ? 'selected' : '' }}>Cada 5 días</option>
                        <option value="7" {{ $config->frecuencia == 7 ? 'selected' : '' }}>Cada 7 días</option>
                        <option value="15" {{ $config->frecuencia == 15 ? 'selected' : '' }}>Cada 15 días</option>
                    </select>
                </div>

                @if($config->automatico && $tiempoRestante)
                <div class="mt-auto p-6 bg-[#669BBC]/5 rounded-3xl border border-[#669BBC]/10">
                    <p class="text-[10px] font-black text-[#669BBC] uppercase tracking-[0.2em] mb-3">Próximo Respaldo Programado</p>
                    <div class="flex items-end gap-2">
                        <span class="text-4xl font-black text-[#003049]">{{ sprintf('%02d', $tiempoRestante['dias']) }}<span class="text-xs uppercase font-bold text-slate-400 ml-1">d</span></span>
                        <span class="text-4xl font-black text-[#003049]">{{ sprintf('%02d', $tiempoRestante['horas']) }}<span class="text-xs uppercase font-bold text-slate-400 ml-1">h</span></span>
                        <span class="text-4xl font-black text-[#003049]">{{ sprintf('%02d', $tiempoRestante['minutos']) }}<span class="text-xs uppercase font-bold text-slate-400 ml-1">m</span></span>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-3 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Fecha estimada: {{ $proximoRespaldo->format('d/m/Y H:i') }}
                    </p>
                </div>
                @endif

                <button type="submit" class="w-full bg-[#003049] text-white font-black py-4 rounded-2xl hover:bg-[#00405f] transition-all shadow-lg shadow-[#003049]/10">
                    Guardar Configuración
                </button>
            </form>
        </div>

        {{-- RESPALDO MANUAL --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col">
            <div class="p-8 border-b border-slate-50 bg-slate-50/30">
                <div class="flex items-center gap-3 mb-1">
                    <div class="p-2 bg-[#669BBC]/10 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#669BBC]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                    </div>
                    <h2 class="text-sm font-black text-[#003049] uppercase tracking-widest">Respaldo Manual</h2>
                </div>
                <p class="text-xs text-slate-400">Genera una copia de seguridad inmediata</p>
            </div>

            <div class="p-8 flex flex-col items-center justify-center text-center flex-1 space-y-8">
                <div class="relative">
                    <div class="absolute -inset-4 bg-[#669BBC]/10 rounded-full blur-xl animate-pulse"></div>
                    <div class="relative h-32 w-32 bg-[#669BBC]/5 border-2 border-dashed border-[#669BBC]/30 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-[#669BBC]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                </div>

                <div class="max-w-xs">
                    <p class="text-sm font-bold text-[#003049] mb-2">¿Necesitas respaldar ahora?</p>
                    <p class="text-xs text-slate-400 leading-relaxed">Se generará un archivo SQL completo con toda la estructura y datos actuales de la plataforma.</p>
                </div>

                <div class="w-full p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Uso de hoy (24h)</span>
                    <div class="flex items-center gap-1.5">
                        @for($i = 1; $i <= 4; $i++)
                        <div class="h-2 w-8 rounded-full {{ $respaldosHoy >= $i ? 'bg-[#669BBC]' : 'bg-slate-200' }}"></div>
                        @endfor
                        <span class="ml-2 text-xs font-black text-[#003049]">{{ $respaldosHoy }}/4</span>
                    </div>
                </div>

                <form action="{{ route('admin.respaldos.ejecutar') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" 
                        class="w-full bg-[#669BBC] text-white font-black py-5 rounded-2xl hover:bg-[#5a89a6] transition-all shadow-lg shadow-[#669BBC]/20 flex items-center justify-center gap-3 group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Respaldar ahora
                    </button>
                </form>

                <p class="text-[10px] text-slate-400 italic">Máximo 4 respaldos manuales permitidos por día por seguridad del servidor.</p>
            </div>
        </div>

    </div>

    {{-- HISTORIAL DE RESPALDOS --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-black text-[#003049] uppercase tracking-widest">Historial de Actividad</h2>
                <p class="text-xs text-slate-400 mt-1">Últimos 10 eventos de respaldo registrados</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Fecha y Hora</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Tipo</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Archivo</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Tamaño</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Estatus</th>
                        <th class="px-8 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/30 transition-colors">
                        <td class="px-8 py-5">
                            <p class="text-sm font-bold text-[#003049]">{{ $log->created_at->format('d/m/Y') }}</p>
                            <p class="text-[11px] text-slate-400 font-medium">{{ $log->created_at->format('H:i:s') }}</p>
                        </td>
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest
                                {{ $log->tipo === 'automatico' ? 'bg-purple-50 text-purple-600' : 'bg-blue-50 text-blue-600' }}">
                                {{ $log->tipo }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-xs font-medium text-slate-500">{{ $log->nombre_archivo ?: 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-xs font-bold text-slate-600">
                            {{ $log->tamano ?: '--' }}
                        </td>
                        <td class="px-8 py-5">
                            @if($log->estatus === 'exitoso')
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-green-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                                Completado
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-red-500 group relative">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                                Fallido
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block bg-slate-800 text-white text-[10px] p-2 rounded shadow-xl whitespace-nowrap z-10">
                                    {{ $log->error }}
                                </div>
                            </span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-center">
                            @if($log->estatus === 'exitoso')
                            <button type="button" onclick="confirmarRestauracion({{ $log->id }}, '{{ $log->nombre_archivo }}')"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-600 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-emerald-100 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Restaurar
                            </button>
                            
                            <form id="restore-form-{{ $log->id }}" action="{{ route('admin.respaldos.restaurar', $log->id) }}" method="POST" class="hidden">
                                @csrf
                                <input type="hidden" name="password" id="restore-password-{{ $log->id }}">
                            </form>
                            @else
                            <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">No disponible</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="p-3 bg-slate-50 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                </div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">No hay registros aún</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@push('scripts')
<script>
    function confirmarRestauracion(id, nombre) {
        Swal.fire({
            title: 'Confirmar Restauración',
            html: `
                <div class="text-left">
                    <p class="text-sm text-slate-500 mb-4">Estás por restaurar el sistema al punto: <br><strong class="text-[#003049]">${nombre}</strong></p>
                    <div class="p-3 bg-amber-50 border-l-4 border-amber-400 rounded-r-xl mb-4">
                        <p class="text-[10px] font-black text-amber-800 uppercase tracking-widest">Advertencia Crítica</p>
                        <p class="text-[11px] text-amber-700">Esta acción reemplazará la base de datos actual. Los datos actuales se perderán permanentemente.</p>
                    </div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Ingresa tu contraseña de administrador</label>
                </div>
            `,
            input: 'password',
            inputAttributes: {
                autocapitalize: 'off',
                autocorrect: 'off',
                placeholder: 'Contraseña de acceso'
            },
            showCancelButton: true,
            confirmButtonText: 'Iniciar Restauración',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#10b981', // Emerald 500
            cancelButtonColor: '#64748b',
            showLoaderOnConfirm: true,
            preConfirm: (password) => {
                if (!password) {
                    Swal.showValidationMessage('La contraseña es obligatoria');
                }
                return password;
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('restore-password-' + id).value = result.value;
                document.getElementById('restore-form-' + id).submit();
            }
        });
    }
</script>
@endpush
@endsection
