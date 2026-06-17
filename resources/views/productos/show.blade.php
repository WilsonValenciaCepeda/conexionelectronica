@extends('layouts.app')

@section('title', $producto->nombre)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('tienda') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Volver a la tienda
    </a>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
            {{-- Imagen --}}
            <div class="bg-gray-100 rounded-lg flex items-center justify-center p-4 min-h-[300px] relative">
                {{-- Badge de oferta --}}
                @if($producto->en_oferta && $producto->precio_oferta && $producto->precio_oferta < $producto->precio)
                    <div class="absolute top-4 left-4 z-10 bg-red-500 text-white text-sm font-bold px-4 py-2 rounded-full animate-pulse">
                        🔥 OFERTA
                    </div>
                @endif

                @if($producto->imagen)
                    <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="max-h-[400px] w-auto object-contain">
                @else
                    <div class="text-gray-400">
                        <svg class="w-32 h-32 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-center mt-2">Sin imagen</p>
                    </div>
                @endif
            </div>

            {{-- Información --}}
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $producto->nombre }}</h1>
                <p class="text-gray-600 mt-2">{{ $producto->descripcion ?? 'Sin descripción' }}</p>

                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
                        {{ $producto->categoria ?? 'Sin categoría' }}
                    </span>
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm font-medium">
                        Stock: {{ $producto->stock }}
                    </span>
                </div>

                {{-- Precio con oferta --}}
                <div class="mt-6">
                    @if($producto->en_oferta && $producto->precio_oferta && $producto->precio_oferta < $producto->precio)
                        <div class="flex items-center gap-3">
                            <span class="text-2xl text-gray-400 line-through">Bs. {{ number_format($producto->precio, 2) }}</span>
                            <span class="text-4xl font-bold text-red-600">Bs. {{ number_format($producto->precio_oferta, 2) }}</span>
                            <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">-{{ round((1 - $producto->precio_oferta / $producto->precio) * 100) }}%</span>
                        </div>
                    @else
                        <span class="text-4xl font-bold text-blue-600">Bs. {{ number_format($producto->precio, 2) }}</span>
                    @endif
                </div>

                <div class="mt-8">
                    @auth
                        <form action="{{ route('carrito.agregar') }}" method="POST">
                            @csrf
                            <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition text-lg" {{ $producto->stock <= 0 ? 'disabled' : '' }}>
                                <i class="fas fa-cart-plus mr-2"></i>
                                {{ $producto->stock > 0 ? 'Agregar al carrito' : 'Producto agotado' }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 px-6 rounded-lg transition text-lg">
                            <i class="fas fa-lock mr-2"></i> Inicia sesión para comprar
                        </a>
                    @endauth
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-tag mr-1"></i> Código: #{{ $producto->id }}
                    </p>
                    <p class="text-sm text-gray-500 mt-1">
                        <i class="fas fa-calendar-alt mr-1"></i> Agregado: {{ $producto->created_at->format('d/m/Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection