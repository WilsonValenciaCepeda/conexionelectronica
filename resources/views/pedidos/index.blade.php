@extends('layouts.app')

@section('title', 'Mis Pedidos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">📦 Mis Pedidos</h1>
        <a href="{{ route('tienda') }}" class="text-blue-600 hover:underline">← Seguir comprando</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-4 flex items-center gap-3">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if($pedidos->isEmpty())
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
            <p class="text-gray-500 text-lg">No has realizado ningún pedido.</p>
            <a href="{{ route('tienda') }}" class="inline-block mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                Comienza a comprar
            </a>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600"># Pedido</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Fecha</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Total</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Estado</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $pedido)
                        <tr class="border-t border-gray-100 hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-800">#{{ $pedido->id }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $pedido->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 font-bold text-blue-600">Bs. {{ number_format($pedido->total, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 text-xs rounded-full font-medium
                                    @if($pedido->estado == 'pendiente') bg-yellow-100 text-yellow-700
                                    @elseif($pedido->estado == 'procesando') bg-blue-100 text-blue-700
                                    @elseif($pedido->estado == 'enviado') bg-purple-100 text-purple-700
                                    @elseif($pedido->estado == 'entregado') bg-green-100 text-green-700
                                    @else bg-red-100 text-red-700 @endif">
                                    {{ ucfirst($pedido->estado) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('pedidos.show', $pedido) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Ver detalles
                                    <i class="fas fa-chevron-right text-xs ml-1"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $pedidos->links() }}
        </div>
    @endif
</div>
@endsection