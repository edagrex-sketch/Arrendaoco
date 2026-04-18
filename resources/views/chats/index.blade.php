@extends('layouts.app')

@section('title', 'Mis Mensajes - ArrendaOco')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-[#003049] tracking-tight">Mis Conversaciones</h1>
        <p class="text-gray-500 mt-2">Gestiona tus dudas y acuerdos con arrendadores e inquilinos.</p>
    </div>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 h-[700px] flex flex-col md:flex-row shadow-slate-200">
        <!-- Sidebar: Lista de Chats -->
        <div class="{{ isset($currentChat) ? 'hidden' : 'flex' }} md:flex w-full md:w-80 border-r border-gray-100 bg-gray-50/50 flex flex-col h-full">
            <div class="p-6 border-b border-gray-100 bg-white">
                <div class="relative">
                    <input type="text" placeholder="Buscar chat..." 
                        class="w-full pl-10 pr-4 py-2 bg-gray-100 border-none rounded-2xl text-sm focus:ring-2 focus:ring-[#669BBC] transition-all">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto">
                @forelse($chats as $chat)
                    @php 
                        $otroUsuario = $chat->getOtroUsuario(Auth::id()); 
                        $estaSeleccionado = isset($currentChat) && $currentChat->id == $chat->id;
                    @endphp
                    <a href="{{ route('chats.show', $chat) }}" 
                        class="flex items-center gap-4 p-4 hover:bg-white transition-all border-b border-gray-50 {{ $estaSeleccionado ? 'bg-white border-l-4 border-l-[#669BBC]' : '' }}">
                        <div class="relative flex-shrink-0">
                            @if($otroUsuario->foto_perfil)
                                <img src="{{ str_starts_with($otroUsuario->foto_perfil, 'http') ? $otroUsuario->foto_perfil : asset('storage/'.$otroUsuario->foto_perfil) }}" 
                                    class="w-12 h-12 rounded-2xl object-cover shadow-sm">
                            @else
                                <div class="w-12 h-12 rounded-2xl bg-[#003049] text-white flex items-center justify-center font-bold text-lg shadow-sm">
                                    {{ substr($otroUsuario->nombre, 0, 1) }}
                                </div>
                            @endif
                            
                            {{-- Miniatura de la Propiedad (Identificador de Contexto) --}}
                            @if($chat->inmueble && $chat->inmueble->imagen)
                                <div class="absolute -bottom-1 -left-2 w-7 h-7 rounded-lg border-2 border-white shadow-lg overflow-hidden ring-1 ring-[#003049]/10 hover:scale-125 transition-transform z-10" title="Negociando para: {{ $chat->inmueble->titulo }}">
                                    <img src="{{ \App\Support\MediaUrl::fromStoragePath($chat->inmueble->imagen) }}" class="w-full h-full object-cover">
                                </div>
                            @endif

                            {{-- Burbuja de Notificación del Chat --}}
                            <div id="badge-chat-{{ $chat->id }}" class="{{ $chat->unread_count > 0 ? '' : 'hidden' }} absolute -top-2 -left-2 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full shadow-md z-20 animate-pulse">
                                {{ $chat->unread_count }}
                            </div>

                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full z-10"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            @if($chat->inmueble)
                                <p class="text-[9px] text-[#669BBC] font-extrabold uppercase tracking-widest mb-0.5 leading-none">{{ $chat->inmueble->titulo }}</p>
                            @endif
                            <div class="flex justify-between items-baseline mb-0.5">
                                <h3 class="font-bold text-[#003049] truncate leading-none">{{ $otroUsuario->nombre }}</h3>
                                <span class="text-[10px] text-gray-400 font-medium whitespace-nowrap">
                                    {{ $chat->last_message_at ? $chat->last_message_at->diffForHumans(null, true) : '' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 truncate">
                                {{ $chat->last_message ?? 'Inicia una conversación...' }}
                            </p>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-400">No tienes chats activos todavía.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="{{ isset($currentChat) ? 'flex' : 'hidden md:flex' }} flex-1 flex flex-col bg-white overflow-hidden">
            @yield('chat_content')
            
            @if(!View::hasSection('chat_content'))
                <div class="flex-1 flex flex-col items-center justify-center p-12 text-center bg-gray-50/30">
                    <img src="{{ asset('logo1.png') }}" class="w-24 h-24 opacity-20 mb-6 grayscale">
                    <h2 class="text-2xl font-bold text-[#003049] opacity-40">Tus mensajes aparecerán aquí</h2>
                    <p class="text-gray-400 mt-2 max-w-xs">Selecciona una conversación de la lista para empezar a chatear.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
