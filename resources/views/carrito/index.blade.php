@extends('layouts.app')

@section('title', 'Mi Carrito')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">🛒 Mi Carrito</h1>
        <a href="{{ route('tienda') }}" class="text-blue-600 hover:underline">← Seguir comprando</a>
    </div>

    @guest
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <h2 class="text-2xl font-bold text-gray-700 mb-2">¡Tu carrito te espera!</h2>
            <p class="text-gray-500 mb-6">Inicia sesión para ver tus productos agregados y realizar tu compra.</p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition">
                    <i class="fas fa-sign-in-alt mr-2"></i> Iniciar sesión
                </a>
                <a href="{{ route('register') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg transition">
                    <i class="fas fa-user-plus mr-2"></i> Registrarse
                </a>
            </div>
        </div>
    @endguest

    @auth
        @if($carrito->isEmpty())
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <p class="text-gray-500 text-lg">Tu carrito está vacío.</p>
                <a href="{{ route('tienda') }}" class="inline-block mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Ver productos
                </a>
            </div>
        @else
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left">Producto</th>
                            <th class="px-4 py-3 text-left">Precio</th>
                            <th class="px-4 py-3 text-left">Cantidad</th>
                            <th class="px-4 py-3 text-left">Subtotal</th>
                            <th class="px-4 py-3 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($carrito as $item)
                        <tr class="border-t">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center overflow-hidden">
                                        @if($item->producto->imagen)
                                            <img src="{{ asset('storage/' . $item->producto->imagen) }}" alt="{{ $item->producto->nombre }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="font-medium">{{ $item->producto->nombre }}</span>
                                        @if($item->producto->en_oferta && $item->producto->precio_oferta && $item->producto->precio_oferta < $item->producto->precio)
                                            <span class="ml-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">OFERTA</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($item->producto->en_oferta && $item->producto->precio_oferta && $item->producto->precio_oferta < $item->producto->precio)
                                    <div class="flex flex-col">
                                        <span class="text-sm text-gray-400 line-through">Bs. {{ number_format($item->producto->precio, 2) }}</span>
                                        <span class="font-bold text-red-600">Bs. {{ number_format($item->producto->precio_oferta, 2) }}</span>
                                    </div>
                                @else
                                    <span>Bs. {{ number_format($item->producto->precio, 2) }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <form action="{{ route('carrito.actualizar', $item) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="cantidad" value="{{ $item->cantidad }}" min="1" max="{{ $item->producto->stock }}" class="w-16 border rounded px-2 py-1 text-center">
                                    <button type="submit" class="text-blue-600 hover:underline text-sm">Actualizar</button>
                                </form>
                            </td>
                            <td class="px-4 py-3 font-semibold">
                                @php
                                    $precioUnitario = $item->producto->en_oferta && $item->producto->precio_oferta && $item->producto->precio_oferta < $item->producto->precio 
                                        ? $item->producto->precio_oferta 
                                        : $item->producto->precio;
                                @endphp
                                Bs. {{ number_format($item->cantidad * $precioUnitario, 2) }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                                    <form action="{{ route('carrito.comprar.individual', $item) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-sm transition whitespace-nowrap">
                                            <i class="fas fa-shopping-bag mr-1"></i> Comprar
                                        </button>
                                    </form>
                                    <form action="{{ route('carrito.eliminar', $item) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm">
                                            <i class="fas fa-trash mr-1"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-4 bg-gray-50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div>
                        <strong>Total: </strong> 
                        <span class="text-2xl font-bold text-blue-600">Bs. {{ number_format($total, 2) }}</span>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <form action="{{ route('carrito.vaciar') }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('¿Vaciar carrito?')">
                                <i class="fas fa-trash mr-1"></i> Vaciar carrito
                            </button>
                        </form>
                        <form action="{{ route('checkout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                                <i class="fas fa-shopping-cart mr-2"></i> Comprar todo
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endauth
</div>
@endsection