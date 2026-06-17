@extends('layouts.app')

@section('title', 'Pedido #' . $pedido->id)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('pedidos.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Volver a mis pedidos
    </a>

    {{-- Mensajes de éxito/error --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-6 flex items-center gap-3">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-6 flex items-center gap-3">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        {{-- Header del pedido --}}
        <div class="bg-gradient-to-r from-blue-900 to-blue-700 px-6 py-4 text-white">
            <div class="flex justify-between items-center flex-wrap gap-4">
                <div>
                    <h1 class="text-2xl font-bold">Pedido #{{ $pedido->id }}</h1>
                    <p class="text-blue-200 text-sm">{{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="px-4 py-2 rounded-full text-sm font-bold
                        @if($pedido->estado == 'pendiente') bg-yellow-500 text-white
                        @elseif($pedido->estado == 'procesando') bg-blue-500 text-white
                        @elseif($pedido->estado == 'enviado') bg-purple-500 text-white
                        @elseif($pedido->estado == 'entregado') bg-green-500 text-white
                        @else bg-red-500 text-white @endif">
                        {{ ucfirst($pedido->estado) }}
                    </span>
                    <span class="text-2xl font-bold">Bs. {{ number_format($pedido->total, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Detalles del pedido --}}
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Productos</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Producto</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Precio unitario</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Cantidad</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedido->detalles as $detalle)
                            <tr class="border-t border-gray-100">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center overflow-hidden">
                                            @if($detalle->producto->imagen)
                                                <img src="{{ asset('storage/' . $detalle->producto->imagen) }}" alt="{{ $detalle->producto->nombre }}" class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $detalle->producto->nombre }}</p>
                                            <p class="text-xs text-gray-500">{{ $detalle->producto->categoria ?? 'Sin categoría' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 font-medium">Bs. {{ number_format($detalle->precio_unitario, 2) }}</td>
                                <td class="px-4 py-3">{{ $detalle->cantidad }}</td>
                                <td class="px-4 py-3 font-bold text-blue-600">Bs. {{ number_format($detalle->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-right font-bold text-gray-700">Total</td>
                            <td class="px-4 py-3 text-2xl font-bold text-blue-600">Bs. {{ number_format($pedido->total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Dirección de entrega --}}
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="font-semibold text-gray-700">📍 Dirección de entrega</h4>
                <p class="text-gray-600">{{ $pedido->direccion_entrega ?? 'No especificada' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection