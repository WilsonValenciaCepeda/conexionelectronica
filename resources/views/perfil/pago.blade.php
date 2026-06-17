@extends('layouts.app')

@section('title', 'Método de Pago')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center gap-3 mb-6">
        <i class="fas fa-credit-card text-3xl text-green-600"></i>
        <h1 class="text-3xl font-bold text-gray-800">Agregar método de pago</h1>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="text-center py-8">
            <i class="fas fa-shield-alt text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">Próximamente podrás agregar tus métodos de pago.</p>
            <p class="text-sm text-gray-400 mt-2">(Simulación para la práctica)</p>
        </div>
        <a href="{{ route('perfil.informacion') }}" class="inline-block mt-4 text-blue-600 hover:underline">← Volver</a>
    </div>
</div>
@endsection