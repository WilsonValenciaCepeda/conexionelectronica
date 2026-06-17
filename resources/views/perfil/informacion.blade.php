@extends('layouts.app')

@section('title', 'Mi Información')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center gap-3 mb-6">
        <i class="fas fa-user-circle text-4xl text-blue-600"></i>
        <h1 class="text-3xl font-bold text-gray-800">Mi Información</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Información del usuario --}}
        <div class="md:col-span-1">
            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user text-4xl text-blue-600"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-800">{{ Auth::user()->name }}</h2>
                <p class="text-gray-500 text-sm">{{ Auth::user()->email }}</p>
                <p class="text-gray-400 text-xs mt-2">Miembro desde {{ Auth::user()->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        {{-- Opciones del usuario --}}
        <div class="md:col-span-2">
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Opciones de cuenta</h3>
                
                <div class="space-y-3">
                    {{-- Editar cuenta --}}
                    <a href="{{ route('perfil.editar') }}" class="flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-edit text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Editar cuenta</p>
                                <p class="text-sm text-gray-500">Actualiza tu nombre y correo electrónico</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-blue-600 transition"></i>
                    </a>

                    {{-- Agregar método de pago --}}
                    <a href="{{ route('perfil.pago') }}" class="flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-credit-card text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Agregar método de pago</p>
                                <p class="text-sm text-gray-500">Añade una tarjeta o cuenta para tus compras</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-green-600 transition"></i>
                    </a>

                    {{-- Soporte técnico --}}
                    <a href="{{ route('perfil.soporte') }}" class="flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-headset text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Soporte técnico</p>
                                <p class="text-sm text-gray-500">Ayuda, preguntas frecuentes y contacto</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400 group-hover:text-yellow-600 transition"></i>
                    </a>
                </div>
            </div>

            {{-- Panel de pedidos recientes --}}
            <div class="bg-white rounded-xl shadow-md p-6 mt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">📦 Últimos pedidos</h3>
                    <a href="{{ route('pedidos.index') }}" class="text-sm text-blue-600 hover:underline">Ver todos</a>
                </div>
                
                @php
                    $pedidos = Auth::user()->pedidos()->orderBy('created_at', 'desc')->limit(3)->get();
                @endphp

                @if($pedidos->isEmpty())
                    <p class="text-gray-500 text-sm text-center py-4">No tienes pedidos realizados aún.</p>
                @else
                    @foreach($pedidos as $pedido)
                        <div class="flex justify-between items-center py-3 border-b border-gray-100 last:border-0">
                            <div>
                                <p class="font-medium text-gray-800">Pedido #{{ $pedido->id }}</p>
                                <p class="text-sm text-gray-500">{{ $pedido->created_at->format('d/m/Y') }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-medium text-blue-600">Bs. {{ number_format($pedido->total, 2) }}</span>
                                <span class="px-3 py-1 text-xs rounded-full 
                                    @if($pedido->estado == 'pendiente') bg-yellow-100 text-yellow-700
                                    @elseif($pedido->estado == 'procesando') bg-blue-100 text-blue-700
                                    @elseif($pedido->estado == 'enviado') bg-purple-100 text-purple-700
                                    @elseif($pedido->estado == 'entregado') bg-green-100 text-green-700
                                    @else bg-red-100 text-red-700 @endif">
                                    {{ ucfirst($pedido->estado) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection