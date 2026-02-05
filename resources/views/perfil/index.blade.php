@extends('layouts.app')

@section('title', 'Mi Perfil - ArrendaOco')

@section('content')
    <div class="max-w-4xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Sidebar Perfil -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                    <div class="w-32 h-32 rounded-full mx-auto flex items-center justify-center mb-4 overflow-hidden border-4 border-gray-100 shadow-sm relative group">
                        @if($usuario->foto_perfil)
                            <img src="{{ asset('storage/' . $usuario->foto_perfil) }}" alt="Foto de perfil" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-[#003049] flex items-center justify-center text-4xl text-white font-bold">
                                {{ substr($usuario->nombre, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <h2 class="text-xl font-bold text-[#003049]">{{ $usuario->nombre }}</h2>
                    <p class="text-gray-500 mb-4">{{ $usuario->email }}</p>

                    <div class="flex flex-wrap justify-center gap-2 mb-6">
                        @foreach ($usuario->roles as $role)
                            <span
                                class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">
                                {{ $role->etiqueta }}
                            </span>
                        @endforeach
                    </div>

                    @if (!$usuario->tieneRol('propietario'))
                        <form action="{{ route('perfil.publicar') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full bg-[#C1121F] text-white font-bold py-2 px-4 rounded-lg hover:bg-[#780000] transition-colors shadow-md">
                                ¡Quiero Publicar!
                            </button>
                            <p class="text-xs text-gray-400 mt-2">Conviértete en Propietario para publicar inmuebles.</p>
                        </form>
                    @else
                        <a href="{{ route('inmuebles.create') }}"
                            class="block w-full bg-[#C1121F] text-white font-bold py-2 px-4 rounded-lg hover:bg-[#780000] transition-colors shadow-md mb-2">
                            Publicar Inmueble
                        </a>
                        <a href="{{ route('inmuebles.index') }}"
                            class="block w-full bg-[#669BBC] text-white font-bold py-2 px-4 rounded-lg hover:bg-[#003049] transition-colors shadow-md">
                            Mis Propiedades
                        </a>
                    @endif
                </div>
            </div>

            <!-- Formulario Edición -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="bg-[#003049]/10 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#003049]" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-[#003049]">Editar Información Personal</h3>
                    </div>

                    <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Input Foto de Perfil -->
                            <div>
                                <label for="foto_perfil" class="block text-sm font-bold text-gray-700 mb-2 px-1">Foto de Perfil</label>
                                <div class="relative flex items-center">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*"
                                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-[#003049]/10 focus:border-[#003049] transition-all outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#003049]/10 file:text-[#003049] hover:file:bg-[#003049]/20">
                                </div>
                            </div>

                            <!-- Input Nombre -->
                            <div>
                                <label for="nombre" class="block text-sm font-bold text-gray-700 mb-2 px-1">Nombre
                                    Completo</label>
                                <div class="relative flex items-center">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="nombre" id="nombre"
                                        value="{{ old('nombre', $usuario->nombre) }}"
                                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-[#003049]/10 focus:border-[#003049] transition-all outline-none"
                                        placeholder="Tu nombre completo" required>
                                </div>
                            </div>

                            <!-- Input Email -->
                            <div>
                                <label for="email" class="block text-sm font-bold text-gray-700 mb-2 px-1">Correo
                                    Electrónico</label>
                                <div class="relative flex items-center">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input type="email" name="email" id="email"
                                        value="{{ old('email', $usuario->email) }}"
                                        class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-[#003049]/10 focus:border-[#003049] transition-all outline-none"
                                        placeholder="ejemplo@correo.com" required>
                                </div>
                            </div>

                            <div class="border-t border-gray-100 pt-8 mt-4">
                                <div class="flex items-center gap-2 mb-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#C1121F]" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    <h4 class="text-lg font-bold text-[#003049]">Cambiar Contraseña</h4>
                                </div>

                                <p
                                    class="text-[11px] text-gray-500 mb-6 bg-blue-50 p-3 rounded-lg border border-blue-100 italic">
                                    * Dejar en blanco si no desea realizar cambios en su clave de acceso.
                                </p>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="password" class="block text-sm font-bold text-gray-700 mb-2 px-1">Nueva
                                            Contraseña</label>
                                        <input type="password" name="password" id="password"
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-[#003049]/10 focus:border-[#003049] transition-all outline-none"
                                            placeholder="••••••••">
                                    </div>
                                    <div>
                                        <label for="password_confirmation"
                                            class="block text-sm font-bold text-gray-700 mb-2 px-1">Confirmar
                                            Contraseña</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-[#003049]/10 focus:border-[#003049] transition-all outline-none"
                                            placeholder="••••••••">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-12 pb-2">
                            <button type="submit"
                                class="bg-[#003049] text-white font-bold py-3 px-10 rounded-xl hover:bg-[#002030] transition-all transform hover:scale-[1.02] active:scale-95 shadow-xl shadow-[#003049]/20">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
