@extends('layouts.app')

@section('title', 'Mi Perfil - ArrendaOco')

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Sidebar Perfil -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                <div class="w-32 h-32 bg-[#003049] rounded-full mx-auto flex items-center justify-center text-4xl text-white font-bold mb-4">
                    {{ substr($usuario->nombre, 0, 1) }}
                </div>
                <h2 class="text-xl font-bold text-[#003049]">{{ $usuario->nombre }}</h2>
                <p class="text-gray-500 mb-4">{{ $usuario->email }}</p>
                
                <div class="flex flex-wrap justify-center gap-2 mb-6">
                    @foreach($usuario->roles as $role)
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">
                            {{ $role->etiqueta }}
                        </span>
                    @endforeach
                </div>

                @if(!$usuario->tieneRol('propietario'))
                    <form action="{{ route('perfil.publicar') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-[#C1121F] text-white font-bold py-2 px-4 rounded-lg hover:bg-[#780000] transition-colors shadow-md">
                            ¡Quiero Publicar!
                        </button>
                        <p class="text-xs text-gray-400 mt-2">Conviértete en Propietario para publicar inmuebles.</p>
                    </form>
                @else
                    <a href="{{ route('inmuebles.create') }}" class="block w-full bg-[#C1121F] text-white font-bold py-2 px-4 rounded-lg hover:bg-[#780000] transition-colors shadow-md mb-2">
                        Publicar Inmueble
                    </a>
                    <a href="{{ route('inmuebles.index') }}" class="block w-full bg-[#669BBC] text-white font-bold py-2 px-4 rounded-lg hover:bg-[#003049] transition-colors shadow-md">
                        Mis Propiedades
                    </a>
                @endif
            </div>
        </div>

        <!-- Formulario Edición -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-2xl font-bold text-[#003049] mb-6">Editar Información Personal</h3>
                
                <form action="{{ route('perfil.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo</label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $usuario->nombre) }}" 
                                class="w-full border-gray-300 rounded-lg focus:ring-[#003049] focus:border-[#003049]" required>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $usuario->email) }}" 
                                class="w-full border-gray-300 rounded-lg focus:ring-[#003049] focus:border-[#003049]" required>
                        </div>



                        <div class="border-t border-gray-200 pt-6 mt-2">
                            <h4 class="text-lg font-semibold text-[#003049] mb-4">Cambiar Contraseña</h4>
                            <p class="text-sm text-gray-500 mb-4">Dejar en blanco si no desea cambiarla.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nueva Contraseña</label>
                                    <input type="password" name="password" id="password" class="w-full border-gray-300 rounded-lg focus:ring-[#003049] focus:border-[#003049]">
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border-gray-300 rounded-lg focus:ring-[#003049] focus:border-[#003049]">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-8">
                        <button type="submit" class="bg-[#003049] text-white font-bold py-2 px-6 rounded-lg hover:bg-[#002030] transition-colors shadow-md">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
