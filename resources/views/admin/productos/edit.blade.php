@extends('layouts.app')

@section('title', 'Editar Producto')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">✏️ Editar Producto</h1>
        <a href="{{ route('admin.productos.index') }}" class="text-blue-600 hover:underline">← Volver</a>
    </div>

    <form action="{{ route('admin.productos.update', $producto) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Nombre *</label>
            <input type="text" name="nombre" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('nombre', $producto->nombre) }}">
            @error('nombre') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Descripción</label>
            <textarea name="descripcion" rows="4" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descripcion', $producto->descripcion) }}</textarea>
            @error('descripcion') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        {{-- Precio y Stock --}}
        <div class="grid grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Precio original (Bs) *</label>
                <input type="number" name="precio" step="0.01" min="0" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('precio', $producto->precio) }}">
                @error('precio') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Stock *</label>
                <input type="number" name="stock" min="0" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('stock', $producto->stock) }}">
                @error('stock') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Categoría --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Categoría</label>
            <select name="categoria" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Sin categoría</option>
                @foreach($categorias as $cat)
                    <option value="{{ $cat }}" {{ old('categoria', $producto->categoria) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            @error('categoria') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        {{-- OFERTA --}}
        <div class="mb-4 p-4 border-2 border-red-200 rounded-lg bg-red-50">
            <div class="flex items-center gap-3 mb-3">
                <input type="checkbox" name="en_oferta" id="en_oferta" value="1" {{ old('en_oferta', $producto->en_oferta) ? 'checked' : '' }} class="w-5 h-5 text-red-600">
                <label for="en_oferta" class="font-bold text-red-600 text-lg">🔥 Activar oferta</label>
            </div>

            <div id="oferta_fields" style="{{ old('en_oferta', $producto->en_oferta) ? '' : 'display: none;' }}">
                <label class="block text-gray-700 font-bold mb-2">Precio de oferta (Bs) *</label>
                <input type="number" name="precio_oferta" step="0.01" min="0" class="w-full px-4 py-2 border-2 border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" value="{{ old('precio_oferta', $producto->precio_oferta) }}" placeholder="Ej: 450.00">
                <p class="text-xs text-gray-500 mt-1">El precio de oferta debe ser menor al precio original</p>
                @error('precio_oferta') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>
        </div>

        <script>
            document.getElementById('en_oferta').addEventListener('change', function() {
                const fields = document.getElementById('oferta_fields');
                if (this.checked) {
                    fields.style.display = 'block';
                } else {
                    fields.style.display = 'none';
                }
            });
        </script>

        {{-- Imagen --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Imagen</label>
            @if($producto->imagen)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="h-20 w-auto">
                </div>
            @endif
            <input type="file" name="imagen" accept="image/*" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('imagen') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
            <i class="fas fa-save mr-2"></i> Actualizar Producto
        </button>
    </form>
</div>
@endsection