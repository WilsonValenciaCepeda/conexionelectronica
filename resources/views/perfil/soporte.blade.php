@extends('layouts.app')

@section('title', 'Soporte Técnico')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center gap-3 mb-6">
        <i class="fas fa-headset text-3xl text-yellow-600"></i>
        <h1 class="text-3xl font-bold text-gray-800">Soporte técnico</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-md p-6 text-center">
            <i class="fas fa-phone text-3xl text-blue-600 mb-3"></i>
            <h3 class="font-bold text-gray-800">Teléfono</h3>
            <p class="text-gray-500">+591 12345678</p>
            <p class="text-sm text-gray-400">Lun-Vie 9:00 - 18:00</p>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 text-center">
            <i class="fas fa-envelope text-3xl text-blue-600 mb-3"></i>
            <h3 class="font-bold text-gray-800">Email</h3>
            <p class="text-gray-500">soporte@conexionelectronica.com</p>
            <p class="text-sm text-gray-400">Respondemos en 24 horas</p>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 text-center">
            <i class="fas fa-whatsapp text-3xl text-green-500 mb-3"></i>
            <h3 class="font-bold text-gray-800">WhatsApp</h3>
            <p class="text-gray-500">+591 71234567</p>
            <p class="text-sm text-gray-400">Atención inmediata</p>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 text-center">
            <i class="fas fa-comments text-3xl text-purple-500 mb-3"></i>
            <h3 class="font-bold text-gray-800">Chat en vivo</h3>
            <p class="text-gray-500">Disponible en la web</p>
            <p class="text-sm text-gray-400">(Simulación)</p>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('perfil.informacion') }}" class="text-blue-600 hover:underline">← Volver</a>
    </div>
</div>
@endsection