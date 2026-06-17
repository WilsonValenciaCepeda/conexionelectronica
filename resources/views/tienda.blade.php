@extends('layouts.app')

@section('title', 'Tienda - Conexión Electrónica')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">🛒 Catálogo de Productos</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($productos as $producto)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300 group relative">
                {{-- Distintivo de oferta --}}
                @if($producto->en_oferta && $producto->precio_oferta && $producto->precio_oferta < $producto->precio)
                    <div class="absolute top-3 left-3 z-10 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full animate-pulse">
                        🔥 OFERTA
                    </div>
                @endif

                <a href="{{ route('producto.show', $producto) }}" class="block">
                    <div class="h-48 bg-gray-100 flex items-center justify-center overflow-hidden">
                        @if($producto->imagen)
                            <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="p-4">
                        <h3 class="font-bold text-lg text-gray-800 group-hover:text-blue-600 transition">{{ $producto->nombre }}</h3>
                        <p class="text-gray-600 text-sm truncate">{{ $producto->descripcion }}</p>
                        <div class="mt-2">
                            <span class="text-sm bg-gray-200 px-2 py-1 rounded">{{ $producto->categoria ?? 'Sin categoría' }}</span>
                            <span class="text-sm text-gray-500 ml-2">Stock: {{ $producto->stock }}</span>
                        </div>

                        {{-- Precio con oferta --}}
                        @if($producto->en_oferta && $producto->precio_oferta && $producto->precio_oferta < $producto->precio)
                            <div class="mt-4">
                                <span class="text-sm text-gray-400 line-through">Bs. {{ number_format($producto->precio, 2) }}</span>
                                <span class="text-2xl font-bold text-red-600 ml-2">Bs. {{ number_format($producto->precio_oferta, 2) }}</span>
                            </div>
                        @else
                            <div class="mt-4">
                                <span class="text-2xl font-bold text-blue-600">Bs. {{ number_format($producto->precio, 2) }}</span>
                            </div>
                        @endif
                    </div>
                </a>

                {{-- Botón agregar al carrito --}}
                <div class="px-4 pb-4">
                    @auth
                        <form action="{{ route('carrito.agregar') }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg transition" {{ $producto->stock <= 0 ? 'disabled' : '' }}>
                                <i class="fas fa-cart-plus mr-2"></i>
                                {{ $producto->stock > 0 ? 'Agregar al carrito' : 'Sin stock' }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 rounded-lg transition">
                            <i class="fas fa-lock mr-2"></i> Inicia sesión para comprar
                        </a>
                    @endauth
                </div>
            </div>
        @empty
            <p class="col-span-full text-center text-gray-500 py-12">No hay productos disponibles.</p>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $productos->links() }}
    </div>
</div>
@endsection