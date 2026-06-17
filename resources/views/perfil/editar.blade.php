@extends('layouts.app')

@section('title', 'Editar Cuenta')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center gap-3 mb-6">
        <i class="fas fa-user-edit text-3xl text-blue-600"></i>
        <h1 class="text-3xl font-bold text-gray-800">Editar Cuenta</h1>
    </div>

    <div class="bg-white rounded-xl shadow-md p-6">
        <form method="POST" action="{{ route('perfil.editar.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Nombre</label>
                <input type="text" name="name" value="{{ Auth::user()->name }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Correo electrónico</label>
                <input type="email" name="email" value="{{ Auth::user()->email }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Contraseña</label>
                <input type="password" name="password" placeholder="Dejar en blanco para no cambiar" 
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-save mr-2"></i> Guardar cambios
            </button>
            <a href="{{ route('perfil.informacion') }}" class="ml-3 text-gray-500 hover:text-gray-700">Cancelar</a>
        </form>
    </div>
</div>
@endsection