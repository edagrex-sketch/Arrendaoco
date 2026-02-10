@extends('layouts.app')

@section('title', 'Términos de Servicio | ArrendaOco')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-16 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sm:p-12">
            <h1 class="text-3xl font-bold text-[#003049] mb-8">Términos de Servicio</h1>

            <div class="prose prose-blue max-w-none text-gray-600">
                <p class="mb-4">Última actualización: {{ date('F Y') }}</p>

                <h2 class="text-xl font-bold text-[#003049] mt-8 mb-4">1. Aceptación de los Términos</h2>
                <p class="mb-4">Al acceder y utilizar ArrendaOco, aceptas cumplir con estos términos de servicio. Si no
                    estás de acuerdo con alguna parte de los términos, no podrás acceder al servicio.</p>

                <h2 class="text-xl font-bold text-[#003049] mt-8 mb-4">2. Uso de la Plataforma</h2>
                <p class="mb-4">ArrendaOco es una plataforma que conecta a propietarios con inquilinos. No somos una agencia
                    inmobiliaria y no participamos directamente en las transacciones de arrendamiento.</p>

                <h2 class="text-xl font-bold text-[#003049] mt-8 mb-4">3. Cuentas de Usuario</h2>
                <p class="mb-4">Eres responsable de mantener la confidencialidad de tu cuenta y contraseña. Debes
                    notificarnos inmediatamente sobre cualquier uso no autorizado de tu cuenta.</p>

                <h2 class="text-xl font-bold text-[#003049] mt-8 mb-4">4. Contenido del Usuario</h2>
                <p class="mb-4">Al publicar contenido en ArrendaOco, garantizas que tienes el derecho de hacerlo y que no
                    infringes derechos de terceros. Nos reservamos el derecho de eliminar contenido inapropiado.</p>
            </div>
        </div>
    </div>
@endsection