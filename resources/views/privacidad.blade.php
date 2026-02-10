@extends('layouts.app')

@section('title', 'Política de Privacidad | ArrendaOco')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-16 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sm:p-12">
            <h1 class="text-3xl font-bold text-[#003049] mb-8">Política de Privacidad</h1>

            <div class="prose prose-blue max-w-none text-gray-600">
                <p class="mb-4">Última actualización: {{ date('F Y') }}</p>

                <h2 class="text-xl font-bold text-[#003049] mt-8 mb-4">1. Información que Recopilamos</h2>
                <p class="mb-4">Recopilamos información que nos proporcionas directamente, como tu nombre, dirección de
                    correo electrónico y detalles de contacto al registrarte.</p>

                <h2 class="text-xl font-bold text-[#003049] mt-8 mb-4">2. Uso de la Información</h2>
                <p class="mb-4">Utilizamos la información recopilada para proporcionar, mantener y mejorar nuestros
                    servicios, así como para comunicarnos contigo sobre actualizaciones y ofertas.</p>

                <h2 class="text-xl font-bold text-[#003049] mt-8 mb-4">3. Compartir Información</h2>
                <p class="mb-4">No vendemos ni alquilamos tu información personal a terceros. Solo compartimos información
                    con proveedores de servicios que nos ayudan a operar nuestra plataforma.</p>

                <h2 class="text-xl font-bold text-[#003049] mt-8 mb-4">4. Seguridad de los Datos</h2>
                <p class="mb-4">Implementamos medidas de seguridad para proteger tu información personal contra acceso no
                    autorizado, alteración o divulgación.</p>
            </div>
        </div>
    </div>
@endsection