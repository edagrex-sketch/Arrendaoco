@extends('layouts.app')

@section('content')
<div class="h-[calc(100vh-80px)] overflow-hidden bg-[#F8FAFC]">
    <div class="max-w-[1600px] mx-auto h-full p-0 sm:p-4 md:p-6 lg:p-8">
        <div class="bg-white h-full rounded-none sm:rounded-[32px] shadow-[0_20px_50px_rgba(0,48,73,0.05)] border border-gray-100 flex overflow-hidden relative">
            
            <!-- Sidebar de Chats -->
            <aside id="chat-sidebar" class="w-full md:w-[350px] lg:w-[400px] flex-shrink-0 border-r border-gray-100 flex flex-col bg-white z-40 transition-all duration-300 {{ isset($currentChat) ? 'hidden md:flex' : 'flex' }}">
                <!-- Header del Sidebar -->
                <div class="p-6 pb-2">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-2xl font-black text-[#003049] tracking-tight">Mensajes</h1>
                        <div class="flex gap-2">
                            <button class="p-2 bg-gray-50 rounded-xl text-gray-400 hover:text-[#003049] transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Lista de Usuarios -->
                <div class="flex-1 overflow-y-auto custom-scrollbar px-3 py-4">
                    @forelse($chats as $c)
                        @php $otro = $c->getOtroUsuario(Auth::id()); @endphp
                        <a href="{{ route('chats.show', $c) }}" 
                           class="flex items-center gap-4 p-4 rounded-[24px] mb-2 transition-all duration-300 group {{ (isset($currentChat) && $currentChat->id == $c->id) ? 'bg-[#003049] shadow-lg shadow-[#003049]/20' : 'hover:bg-gray-50' }}">
                            
                            <!-- Avatar con Status -->
                            <div class="relative flex-shrink-0">
                                @if($otro->foto_perfil)
                                    <img src="{{ str_starts_with($otro->foto_perfil, 'http') ? $otro->foto_perfil : asset('storage/'.$otro->foto_perfil) }}" 
                                         class="w-14 h-14 rounded-2xl object-cover shadow-sm group-hover:scale-105 transition-transform">
                                @else
                                    <div class="w-14 h-14 rounded-2xl bg-[#669BBC]/10 text-[#003049] flex items-center justify-center font-black text-xl">
                                        {{ substr($otro->nombre, 0, 1) }}
                                    </div>
                                @endif
                                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                            </div>

                            <!-- Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-center mb-1">
                                    <h3 class="font-bold truncate {{ (isset($currentChat) && $currentChat->id == $c->id) ? 'text-white' : 'text-[#003049]' }}">
                                        {{ $otro->nombre }}
                                    </h3>
                                    <span class="text-[10px] uppercase font-bold {{ (isset($currentChat) && $currentChat->id == $c->id) ? 'text-white/60' : 'text-gray-400' }}">
                                        {{ $c->last_message_at ? $c->last_message_at->diffForHumans(null, true) : '' }}
                                    </span>
                                </div>
                                <p class="text-xs truncate {{ (isset($currentChat) && $currentChat->id == $c->id) ? 'text-white/80' : 'text-gray-500' }}">
                                    {{ $c->last_message ?? 'Sin mensajes aún...' }}
                                </p>
                            </div>

                            @if($c->unread_count > 0)
                                <div class="w-5 h-5 bg-[#669BBC] text-white text-[10px] font-black rounded-full flex items-center justify-center animate-pulse">
                                    {{ $c->unread_count }}
                                </div>
                            @endif
                        </a>
                    @empty
                        <div class="flex flex-col items-center justify-center h-full text-center p-8">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.827-1.233L3 20l1.326-3.945C3.394 14.742 3 13.446 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            </div>
                            <p class="text-gray-400 font-bold">No hay conversaciones</p>
                        </div>
                    @endforelse
                </div>
            </aside>

            <!-- Área Principal de Contenido -->
            <main class="flex-1 flex flex-col h-full bg-white relative overflow-hidden">
                @yield('chat_content')

                @if(!isset($currentChat))
                    <div class="hidden md:flex flex-col items-center justify-center h-full bg-[#FCFDFF]">
                        <div class="w-[300px] h-[300px] bg-slate-50 rounded-full flex items-center justify-center relative mb-8">
                            <img src="{{ asset('img/arrendito_happy.png') }}" class="w-48 opacity-10 drop-shadow-2xl">
                        </div>
                        <h2 class="text-3xl font-black text-[#003049] mb-2 text-center">Tu Centro de Mensajes</h2>
                        <p class="text-gray-400 font-medium">Selecciona una conversación para comenzar</p>
                    </div>
                @endif
            </main>

        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #CBD5E0; }
</style>
@endsection
