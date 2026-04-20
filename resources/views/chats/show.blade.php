@extends('chats.index', ['currentChat' => $chat])

@section('chat_content')
<div class="flex flex-col h-full">
    <!-- Cabecera del Chat -->
    @php
        $otroUsuario = $chat->getOtroUsuario(Auth::id());
        // Detectar si hay contrato en estado pdf_descargado para este inmueble (solo visible al propietario)
        $contratoParaSubir = null;
        if ($chat->inmueble && $chat->inmueble->propietario_id === Auth::id()) {
            $contratoParaSubir = \App\Models\Contrato::where('inmueble_id', $chat->inmueble_id)
                ->where('estatus', 'pdf_descargado')
                ->latest()
                ->first();
        }
    @endphp

    {{-- Banner de Accion Rapida: PDF pendiente de subir --}}
    @if($contratoParaSubir)
    <div class="bg-[#FDF0D5] border-b border-[#669BBC]/20 px-6 py-3 flex items-center justify-between gap-3 flex-wrap">
        <div class="flex items-center gap-3">
            <div class="h-8 w-8 rounded-lg bg-[#003049] flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#FDF0D5]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-[#003049]">Acción requerida</p>
                <p class="text-xs font-bold text-[#003049]/80">
                    El inquilino descargó el contrato el {{ \Carbon\Carbon::parse($contratoParaSubir->pdf_descargado_at)->format('d/m/Y') }}.
                    Sube el escaneo firmado para activar el arrendamiento.
                </p>
            </div>
        </div>
        <a href="{{ route('contratos.subir-firmado', $contratoParaSubir->id) }}"
           class="shrink-0 bg-[#003049] text-white text-xs font-black px-4 py-2 rounded-xl hover:bg-[#002236] transition-all hover:-translate-y-0.5 shadow-sm whitespace-nowrap">
            Subir contrato firmado →
        </a>
    </div>
    @endif

    <div class="p-4 sm:p-6 border-b border-gray-100 bg-white flex items-center justify-between shadow-sm z-30">
        <div class="flex items-center gap-3 sm:gap-4 overflow-hidden">
            {{-- Botón Regresar (Móvil) --}}
            <a href="{{ route('chats.index') }}" class="md:hidden p-2 -ml-2 text-gray-400 hover:text-[#003049] transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>

            <div class="flex-shrink-0 relative">
                @if($otroUsuario->foto_perfil)
                    <img src="{{ str_starts_with($otroUsuario->foto_perfil, 'http') ? $otroUsuario->foto_perfil : asset('storage/'.$otroUsuario->foto_perfil) }}" 
                        class="w-10 h-10 rounded-xl object-cover">
                @else
                    <div class="w-10 h-10 rounded-xl bg-[#003049] text-white flex items-center justify-center font-bold">
                        {{ substr($otroUsuario->nombre, 0, 1) }}
                    </div>
                @endif
                <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></div>
            </div>
            <div class="flex flex-col min-w-0">
                <div class="flex items-center gap-2">
                    <h2 class="font-bold text-[#003049] text-lg leading-tight truncate">{{ $otroUsuario->nombre }}</h2>
                    @if($chat->inmueble)
                        <span class="hidden sm:inline-block px-2 py-0.5 bg-[#003049]/5 text-[#669BBC] text-[9px] rounded-md font-extrabold uppercase tracking-tight border border-[#669BBC]/20">
                            {{ $chat->inmueble->titulo }}
                        </span>
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    <p class="text-[10px] text-green-500 font-bold flex items-center gap-1">
                        Activo ahora
                    </p>
                    @if($chat->inmueble)
                        <span class="text-[10px] text-gray-300 font-bold">|</span>
                        <span class="text-[10px] text-gray-400 font-medium flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            ${{ number_format($chat->inmueble->renta_mensual) }}/mes
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            {{-- Botón Ver Propiedad si el chat está asociado a un inmueble --}}
            @if($chat->inmueble)
            <a href="{{ route('inmuebles.show', $chat->inmueble) }}"
               class="p-2 text-[#669BBC] hover:text-[#003049] transition-colors rounded-lg hover:bg-gray-50" title="Ver propiedad">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </a>
            @endif
            <button class="p-2 text-gray-400 hover:text-[#003049] transition-colors rounded-lg hover:bg-gray-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
            </button>
        </div>
    </div>

    <!-- Área de Mensajes -->
    <div class="flex-1 overflow-hidden flex flex-col relative">
        {{-- Banner de Propiedad (Contexto Permanente) --}}
        @if($chat->inmueble)
            @php $estaRentado = $chat->inmueble->estatus === 'rentado'; @endphp
            <a href="{{ route('inmuebles.show', $chat->inmueble) }}" class="block group/banner">
                <div class="{{ $estaRentado ? 'bg-red-50 border-red-200' : 'bg-[#003049]/5 border-[#003049]/10 hover:bg-[#003049]/10' }} backdrop-blur-md border-b px-4 sm:px-6 py-2 sm:py-3 flex items-center justify-between z-20 sticky top-0 shadow-sm transition-all duration-300">
                    <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl overflow-hidden border-2 {{ $estaRentado ? 'border-red-200' : 'border-white' }} shadow-sm flex-shrink-0 grayscale-[{{ $estaRentado ? '0.8' : '0' }}] group-hover/banner:scale-105 transition-transform">
                            <img src="{{ \App\Support\MediaUrl::fromStoragePath($chat->inmueble->imagen) }}" class="w-full h-full object-cover">
                        </div>
                        <div class="min-w-0 overflow-hidden">
                            <div class="flex items-center gap-1.5">
                                <p class="text-[8px] sm:text-[10px] font-bold {{ $estaRentado ? 'text-red-500' : 'text-[#669BBC]' }} uppercase tracking-widest leading-none truncate">
                                    {{ $estaRentado ? 'No Disponible' : 'Negociando:' }}
                                </p>
                                @if($estaRentado)
                                    <span class="px-1 py-0.5 bg-red-600 text-white text-[7px] font-black rounded-md animate-pulse">RENTADO</span>
                                @endif
                            </div>
                            <h4 class="font-extrabold {{ $estaRentado ? 'text-red-900 opacity-60' : 'text-[#003049]' }} text-xs sm:text-sm truncate uppercase tracking-tight">{{ $chat->inmueble->titulo }}</h4>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0 ml-2">
                        <p class="text-[8px] sm:text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Renta</p>
                        <p class="font-extrabold {{ $estaRentado ? 'text-red-900/40 line-through' : 'text-[#003049]' }} text-xs sm:text-sm tracking-tighter">${{ number_format($chat->inmueble->renta_mensual) }}</p>
                    </div>
                </div>
            </a>
            
            @if($estaRentado)
                <div class="bg-red-600/90 text-white text-[10px] py-1.5 px-4 text-center font-bold tracking-wide shadow-inner">
                    ⚠️ Este inmueble ya ha sido rentado. La conversación sigue abierta para dudas.
                </div>
            @endif
        @endif

        <div id="mensajes-container" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50/20">
        @foreach($mensajes as $mensaje)
            @php $isMe = $mensaje->sender_id == Auth::id(); @endphp
            <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }} animate-fade-in">
                <div class="max-w-[85%] sm:max-w-[85%] space-y-1">
                    @if($mensaje->tipo === 'solicitud_renta')
                        {{-- Mensaje rico para solicitud de renta --}}
                        <div class="px-5 py-4 rounded-2xl shadow-sm border border-[#669BBC]/30 bg-white relative group">
                            <div class="flex items-start gap-3 mb-3">
                                <div class="w-10 h-10 rounded-full bg-[#FDF0D5] text-[#003049] flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-[#003049]">Solicitud de Renta Automática</p>
                                    <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ $mensaje->contenido }}</p>
                                </div>
                            </div>
                            <div class="border-t border-gray-100 pt-3">
                                <button type="button" class="w-full flex items-center justify-center gap-2 bg-slate-50 hover:bg-slate-100 text-[#003049] text-xs font-bold py-2 px-3 rounded-xl transition-colors border border-slate-200" onclick="alert('Funcionalidad de Perfil en desarrollo')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Ver usuario
                                </button>
                            </div>
                        </div>
                    @else
                        {{-- Burbuja normal --}}
                        <div class="message-bubble px-5 py-4 rounded-2xl shadow-sm relative group cursor-pointer {{ $isMe ? 'bg-[#003049] text-white' : 'bg-white text-gray-800 border-gray-100 border' }}" 
                        data-id="{{ $mensaje->id }}"
                        data-contenido="{{ $mensaje->contenido }}"
                        data-sender="{{ $mensaje->sender->nombre ?? 'Usuario' }}"
                        ondblclick="setReply(this)">
                        
                        {{-- Respuesta Citada --}}
                        @if($mensaje->parent)
                            <div class="mb-2 p-2 bg-black/10 rounded-lg border-l-4 border-[#669BBC] text-xs">
                                <p class="font-bold opacity-75">{{ $mensaje->parent->sender->nombre ?? 'Usuario' }}</p>
                                <p class="truncate opacity-90">{{ $mensaje->parent->contenido }}</p>
                            </div>
                        @endif

                        {{-- Contenido según tipo --}}
                        @if($mensaje->tipo === 'oferta')
                            <div class="space-y-3">
                                <div class="flex items-center gap-2 {{ $isMe ? 'text-[#669BBC]' : 'text-[#003049]' }}">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <span class="text-xs font-bold uppercase tracking-tighter">Solicitud de Renta</span>
                                </div>
                                <p class="text-sm font-medium border-l-2 border-white/20 pl-3 italic">{{ $mensaje->contenido }}</p>
                                @if(!$isMe)
                                    <button class="w-full py-2 bg-white text-[#003049] rounded-xl text-xs font-bold shadow-lg hover:bg-gray-100 transition-all">
                                        Revisar Perfil Inquilino
                                    </button>
                                @endif
                            </div>
                        @elseif($mensaje->tipo === 'contrato_enviado')
                            <div class="space-y-3">
                                <div class="flex items-center gap-2 {{ $isMe ? 'text-green-400' : 'text-green-600' }}">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/><path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
                                    <span class="text-xs font-bold uppercase tracking-tighter">Propuesta de Contrato</span>
                                </div>
                                <p class="text-sm font-medium border-l-2 border-white/20 pl-3 italic">{{ $mensaje->contenido }}</p>
                                <button class="w-full py-2 bg-green-500 text-white rounded-xl text-xs font-bold shadow-lg hover:bg-green-600 transition-all">
                                    Ver Contrato
                                </button>
                            </div>
                        @else
                                <p class="text-sm leading-relaxed">{{ $mensaje->contenido }}</p>
                            @endif

                        <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap">
                            Doble clic para responder
                        </div>
                    </div> 
                    @endif
                    <div class="flex items-center gap-1 {{ $isMe ? 'justify-end' : 'justify-start' }}">
                        <span class="text-[9px] text-gray-400 font-medium">
                            {{ $mensaje->created_at->format('H:i') }}
                        </span>
                        @if($isMe)
                            <div class="message-status" data-message-id="{{ $mensaje->id }}">
                                @if($mensaje->leido)
                                    {{-- Doble tick azul --}}
                                    <svg class="w-3.5 h-3.5 text-[#669BBC]" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.41,6.59L15,9.17L11,5.17L9.59,6.58L15,12L18.83,8.17L17.41,6.59M11.59,12.59L9,15.17L5,11.17L3.59,12.58L9,18L13,14L11.59,12.59Z"/>
                                    </svg>
                                @else
                                    {{-- Un solo tick gris --}}
                                    <svg class="w-3.5 h-3.5 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M11.59,12.59L9,15.17L5,11.17L3.59,12.58L9,18L13,14L11.59,12.59Z"/>
                                    </svg>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Previsualización de Respuesta -->
    <div id="reply-preview" class="hidden px-6 py-3 bg-gray-50/50 border-t border-gray-100/50 backdrop-blur-sm animate-slide-up">
        <div class="flex items-center justify-between bg-white p-3 rounded-2xl border-l-4 border-[#003049] shadow-sm ring-1 ring-black/5">
            <div class="min-w-0 pl-2">
                <p class="text-[10px] font-bold text-[#003049] uppercase tracking-tighter">Respondiendo a <span id="reply-sender" class="text-[#669BBC]"></span></p>
                <p id="reply-content" class="text-xs text-gray-500 truncate mt-0.5 pr-4"></p>
            </div>
            <button onclick="cancelReply()" class="flex-shrink-0 p-1.5 hover:bg-red-50 hover:text-red-500 rounded-full transition-all group/close">
                <svg class="w-4 h-4 text-gray-400 group-hover/close:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <!-- Formulario de Envío -->
    <div class="p-4 sm:p-6 bg-white relative">
        {{-- Menú de Acciones Rápidas (Modal pequeño) --}}
        <div id="actions-menu" class="hidden absolute bottom-[100px] left-6 bg-white border border-gray-100 rounded-2xl shadow-2xl p-2 w-64 z-50 animate-slide-up">
            @if($chat->inmueble && Auth::id() != $chat->inmueble->propietario_id)
                {{-- Opciones para el Inquilino --}}
                <button onclick="sendActionMessage('oferta')" class="w-full flex items-center gap-3 p-3 hover:bg-[#669BBC]/5 transition-colors rounded-xl text-left group">
                    <div class="p-2 bg-blue-50 text-[#003049] rounded-lg group-hover:bg-[#003049] group-hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-[#003049]">Solicitar Renta</p>
                        <p class="text-[10px] text-gray-400">Envía interés formal por la casa</p>
                    </div>
                </button>
                </button>
            @endif

            <div class="border-t border-gray-50 my-1"></div>
            
            <button onclick="consultarRocoMediador()" class="w-full flex items-center gap-3 p-3 hover:bg-orange-50 transition-colors rounded-xl text-left group">
                <div class="p-2 bg-orange-50 text-orange-600 rounded-lg group-hover:bg-orange-600 group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm9 7c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2zM5 9c0 1.1-.9 2-2 2S1 10.1 1 9s.9-2 2-2 2 .9 2 2zm7 11c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm7-4c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm-14 0c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-orange-700">Mediador Roco</p>
                    <p class="text-[10px] text-gray-400">Pide consejos legales o ayuda</p>
                </div>
            </button>
        </div>

        <form id="form-mensaje" class="group flex items-center gap-2 bg-gray-50 p-2 pl-3 rounded-[26px] border border-gray-100 focus-within:border-[#669BBC]/50 focus-within:bg-white focus-within:shadow-[0_20px_50px_rgba(102,155,188,0.15)] transition-all duration-500">
            @csrf
            <input type="hidden" id="input-parent-id" name="parent_id" value="">
            
            <div class="flex items-center gap-1">
                <button type="button" onclick="toggleActions()" class="p-2 text-gray-400 hover:text-[#003049] hover:bg-white rounded-full transition-all active:rotate-45">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </button>
                <button type="button" class="p-2 text-gray-400 hover:text-[#003049] hover:bg-white rounded-full transition-all active:scale-90">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                </button>
            </div>

            <input type="text" id="input-contenido" name="contenido" placeholder="Escribe un mensaje..." autocomplete="off"
                class="flex-1 bg-transparent border-none text-sm placeholder-gray-400 focus:ring-0 py-3 px-2 outline-none">

            <button type="submit" class="flex-shrink-0 bg-[#003049] text-white p-3.5 rounded-full shadow-lg shadow-[#003049]/20 hover:bg-[#002538] hover:scale-105 active:scale-95 transition-all outline-none border-none">
                <svg class="w-5 h-5 transform rotate-45 -translate-x-0.5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                </svg>
            </button>
        </form>
    </div>
</div>

@push('scripts')
<style>
    @keyframes slide-up {
        from { transform: translateY(100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .animate-slide-up { animation: slide-up 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
</style>
<script>
    const container = document.getElementById('mensajes-container');
    const form = document.getElementById('form-mensaje');
    const input = document.getElementById('input-contenido');
    const parentInput = document.getElementById('input-parent-id');
    const replyPreview = document.getElementById('reply-preview');
    const replySender = document.getElementById('reply-sender');
    const replyContent = document.getElementById('reply-content');

    // Scroll al fondo al cargar
    container.scrollTop = container.scrollHeight;

    function setReply(element) {
        const id = element.dataset.id;
        const sender = element.dataset.sender;
        const content = element.dataset.contenido;

        parentInput.value = id;
        replySender.innerText = sender;
        replyContent.innerText = content;
        replyPreview.classList.remove('hidden');
        input.focus();
    }

    function cancelReply() {
        parentInput.value = '';
        replyPreview.classList.add('hidden');
    }

    // Escucha de tiempo real con Firebase Firestore
    window.addEventListener('load', () => {
        const myId = "{{ Auth::id() }}";
        const otroId = "{{ $chat->getOtroUsuario(Auth::id())->id }}";
        
        // 1. Escuchar por Laravel Echo (Reverb) - Web a Web
        if (window.Echo) {
            console.log('📡 Conectado a Echo, escuchando: chat.' + "{{ $chat->id }}");
            window.Echo.private('chat.' + "{{ $chat->id }}")
                .listen('.MessageSent', (data) => {
                    console.log('📨 Mensaje recibido vía Echo:', data);
                    appendMessage(data.mensaje, data.mensaje.sender_id == myId);
                    container.scrollTop = container.scrollHeight;
                });
        }
        
        // 2. Escuchar por Firebase (Opcional, para compatibilidad con móvil)
        if (window.FirebaseChat) {
            window.FirebaseChat.listenToMessages(myId, otroId, (messages) => {
                // Si el mensaje ya existe (lo puso Echo), appendMessage no lo duplicará
                messages.forEach(msg => {
                    appendMessage({
                        id: msg.id,
                        contenido: msg.text,
                        sender_id: msg.sender_id,
                        created_at: msg.created_at?.toDate() || new Date(),
                        tipo: msg.tipo || 'texto'
                    }, msg.sender_id == myId);
                });
            });
        }
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const contenido = input.value.trim();
        if (!contenido) return;

        // Limpiar interfaz de inmediato
        input.value = '';
        const tempId = 'temp-' + Date.now();
        
        // Mostrar mensaje localmente de inmediato (Optimistic UI)
        appendMessage({
            id: tempId,
            contenido: contenido,
            sender_id: "{{ Auth::id() }}",
            isTemp: true
        }, true);
        
        container.scrollTop = container.scrollHeight;
        cancelReply();

        const formData = new FormData();
        formData.append('contenido', contenido);
        if (parentInput.value) formData.append('parent_id', parentInput.value);
        formData.append('_token', '{{ csrf_token() }}');

        try {
            const response = await fetch("{{ route('chats.messages.send', $chat->id) }}", {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            
            const data = await response.json();
            
            // Reemplazar mensaje temporal con el real
            const tempMsg = document.querySelector(`[data-id="${tempId}"]`);
            if (tempMsg) tempMsg.closest('.animate-fade-in').remove();
            
            appendMessage(data.mensaje, true);
        } catch (error) {
            console.error('Error enviando mensaje:', error);
            alert('No se pudo enviar el mensaje.');
        }
    });

    function toggleActions() {
        const menu = document.getElementById('actions-menu');
        menu.classList.toggle('hidden');
    }

    async function sendActionMessage(tipo) {
        let contenido = '';
        if (tipo === 'oferta') {
            contenido = '¡Hola! Me encantaría rentar esta propiedad. ¿Podemos formalizar el proceso?';
        } else if (tipo === 'contrato_enviado') {
            contenido = 'He revisado tu perfil y me gustaría proponerte un acuerdo formal de renta.';
        }

        const formData = new FormData();
        formData.append('contenido', contenido);
        formData.append('tipo', tipo);
        formData.append('_token', '{{ csrf_token() }}');

        toggleActions();

        try {
            const response = await fetch("{{ route('chats.messages.send', $chat->id) }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-Socket-ID': window.Echo ? window.Echo.socketId() : ''
                }
            });
            const data = await response.json();
            appendMessage(data.mensaje, true);
            container.scrollTop = container.scrollHeight;
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function consultarRocoMediador() {
        toggleActions();
        const mensajes = document.querySelectorAll('.message-bubble');
        let ultimoTexto = "";
        if (mensajes.length > 0) {
            ultimoTexto = mensajes[mensajes.length - 1].dataset.contenido;
        }

        const prompt = ultimoTexto 
            ? `¡Guau! Roco, en este chat dijeron: "${ultimoTexto}". ¿Qué me sugieres responder o qué dice la ley al respecto?` 
            : `Roco, ayúdame como mediador en este chat de renta por favor.`;

        if (window.openRocoWithContext) {
            window.openRocoWithContext("{{ $chat->inmueble_id ?? '' }}", prompt);
        } else {
            console.warn("Roco no está cargado correctamente.");
        }
    }

    const appendMessage = (msg, isMe) => {
        // No duplicar si ya existe (evitar conflicto entre Optimistic UI y Echo)
        if (msg.id && !msg.isTemp && document.querySelector(`[data-id="${msg.id}"]`)) return;

        const div = document.createElement('div');
        div.className = `flex ${isMe ? 'justify-end' : 'justify-start'} animate-fade-in`;
        
        let bgColor = isMe ? 'bg-[#003049] text-white' : 'bg-white text-gray-800 border-gray-100 border';
        if (msg.isTemp) bgColor += ' opacity-70';
        
        let timeAlign = isMe ? 'justify-end text-right' : 'justify-start text-left';
        
        // Manejo de Respuestas (Parent Message)
        let parentHtml = '';
        if (msg.parent) {
            parentHtml = `
                <div class="mb-2 p-2 bg-black/10 rounded-lg border-l-4 border-[#669BBC] text-xs">
                    <p class="font-bold opacity-75">${msg.parent.sender_nombre || 'Usuario'}</p>
                    <p class="truncate opacity-90">${msg.parent.contenido}</p>
                </div>
            `;
        }

        // Manejo de Tipos Especiales (Tarjetas)
        let contentHtml = `<p class="text-sm leading-relaxed">${msg.contenido}</p>`;
        
        if (msg.tipo === 'oferta') {
            contentHtml = `
                <div class="space-y-3">
                    <div class="flex items-center gap-2 text-[#669BBC]">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span class="text-xs font-bold uppercase tracking-tighter">Solicitud de Renta</span>
                    </div>
                    <p class="text-sm font-medium border-l-2 border-white/20 pl-3 italic">${msg.contenido}</p>
                    ${!isMe ? `
                        <button class="w-full py-2 bg-white text-[#003049] rounded-xl text-xs font-bold shadow-lg hover:bg-gray-100 transition-all active:scale-95">
                            Revisar Perfil Inquilino
                        </button>
                    ` : ''}
                </div>
            `;
        } else if (msg.tipo === 'contrato_enviado') {
            contentHtml = `
                <div class="space-y-3">
                    <div class="flex items-center gap-2 text-green-400">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/><path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
                        <span class="text-xs font-bold uppercase tracking-tighter">Propuesta de Contrato</span>
                    </div>
                    <p class="text-sm font-medium border-l-2 border-white/20 pl-3 italic">${msg.contenido}</p>
                    <div class="flex gap-2">
                        <button class="flex-1 py-2 bg-green-500 text-white rounded-xl text-xs font-bold shadow-lg hover:bg-green-600 transition-all">
                            Ver Contrato
                        </button>
                    </div>
                </div>
            `;
        }

        div.innerHTML = `
            <div class="max-w-[85%] space-y-1">
                <div class="message-bubble px-5 py-4 rounded-2xl shadow-sm relative group cursor-pointer ${bgColor}"
                    data-id="${msg.id}"
                    data-contenido="${msg.contenido}"
                    data-sender="${msg.sender ? msg.sender.nombre : 'Usuario'}"
                    ondblclick="setReply(this)">
                    
                    ${parentHtml}
                    ${contentHtml}

                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap">
                        Doble clic para responder
                    </div>
                </div>          </div>
                <div class="flex items-center gap-1 ${timeAlign}">
                    <span class="text-[9px] text-gray-400 font-medium">Ahora</span>
                    ${isMe ? `
                        <div class="message-status" data-message-id="${msg.id}">
                            {{-- Un solo tick gris --}}
                            <svg class="w-3.5 h-3.5 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M11.59,12.59L9,15.17L5,11.17L3.59,12.58L9,18L13,14L11.59,12.59Z"/>
                            </svg>
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
        container.appendChild(div);
    }
</script>
@endpush
@endsection

