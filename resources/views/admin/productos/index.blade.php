@extends('layouts.app')

@section('title', 'Administración de Productos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                📦 Administrar Productos
                <span class="text-sm font-normal text-gray-500 bg-gray-100 px-3 py-1 rounded-full">{{ $productos->total() }} productos</span>
            </h1>
        </div>
        <a href="{{ route('admin.productos.create') }}" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-2.5 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nuevo Producto
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 px-5 py-4 rounded-lg shadow-sm mb-6 flex items-center gap-3">
            <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @forelse($productos as $producto)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-blue-200 group">
                <a href="{{ route('producto.show', $producto) }}" class="block">
                    <div class="relative h-48 bg-gray-100 overflow-hidden flex items-center justify-center">
                        @if($producto->imagen)
                            <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-50">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        
                        {{-- Badge de oferta --}}
                        @if($producto->en_oferta && $producto->precio_oferta && $producto->precio_oferta < $producto->precio)
                            <div class="absolute top-3 left-3 z-10 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full animate-pulse">
                                🔥 OFERTA
                            </div>
                        @endif

                        <div class="absolute top-3 right-3">
                            <span class="px-3 py-1 text-xs font-bold rounded-full {{ $producto->stock > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                {{ $producto->stock > 0 ? 'Stock: ' . $producto->stock : 'Agotado' }}
                            </span>
                        </div>
                    </div>

                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 text-base line-clamp-1 flex-1 group-hover:text-blue-600 transition" title="{{ $producto->nombre }}">
                            {{ $producto->nombre }}
                        </h3>
                        
                        <div class="flex flex-wrap items-center gap-2 mt-2">
                            <span class="text-xs bg-blue-50 text-blue-600 px-2.5 py-1 rounded-full font-medium">
                                {{ $producto->categoria ?? 'Sin categoría' }}
                            </span>
                            
                            {{-- Precio con oferta --}}
                            @if($producto->en_oferta && $producto->precio_oferta && $producto->precio_oferta < $producto->precio)
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-gray-400 line-through">Bs. {{ number_format($producto->precio, 2) }}</span>
                                    <span class="text-lg font-bold text-red-600">Bs. {{ number_format($producto->precio_oferta, 2) }}</span>
                                </div>
                            @else
                                <span class="text-lg font-bold text-blue-600">Bs. {{ number_format($producto->precio, 2) }}</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 mt-4 pt-3 border-t border-gray-100">
                            <a href="{{ route('admin.productos.edit', $producto) }}" class="flex-1 text-center bg-amber-50 hover:bg-amber-100 text-amber-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Editar
                            </a>
                            <form action="{{ route('admin.productos.destroy', $producto) }}" method="POST" class="flex-1" onsubmit="return confirm('¿Eliminar este producto?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-xl shadow-sm p-12 text-center">
                <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="text-gray-500 text-lg font-medium">No hay productos registrados</p>
                <p class="text-gray-400 text-sm mt-1">Comienza agregando tu primer producto</p>
                <a href="{{ route('admin.productos.create') }}" class="inline-block mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    + Agregar producto
                </a>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $productos->links() }}
    </div>
</div>
@endsection