@extends('layouts.app')

@section('title', 'Conexión Electrónica')

@section('content')
{{-- HERO SECTION con imagen de fondo --}}
<section class="relative min-h-screen flex items-center" 
         style="background-image: url('{{ asset('imagenes/portada.jpg') }}'); 
                background-size: cover; 
                background-position: center; 
                background-repeat: no-repeat;">
    
    {{-- Overlay oscuro para que el texto se vea mejor --}}
    <div class="absolute inset-0 bg-black/50"></div>
    
    {{-- Contenido --}}
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 text-center w-full z-10">
        <h1 class="text-5xl md:text-6xl font-bold text-white mb-4 drop-shadow-lg">
            Bienvenido a Conexión Electrónica
        </h1>
        <p class="text-xl md:text-2xl mb-8 text-white/90 drop-shadow-lg">
            Tecnología y electrónica al alcance de tu mano
        </p>
        <a href="{{ route('tienda') }}" 
           class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-full text-lg transition duration-300 shadow-lg hover:shadow-xl">
            Ver Tienda
        </a>
    </div>
</section>
@endsection