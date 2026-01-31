@extends('layouts.app')

@section('content')
<!-- FONDO GENERAL -->
<div class="min-h-screen bg-cover bg-center bg-fixed"
     style="background-image: url('{{ asset('images/cafeteria.jpg') }}')">

    <!-- OVERLAY -->
    <div class="min-h-screen bg-black/40">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            <!-- BIENVENIDA -->
            <div class="mb-10 text-white">
                <h3 class="text-4xl font-extrabold drop-shadow-lg">
                    Hola, {{ auth()->user()->nombre }} 👋
                </h3>
                <p class="text-white/90 mt-2 text-lg drop-shadow-md">
                    ¿Qué se te antoja hoy?
                </p>
            </div>

            <!-- CARDS PRINCIPALES CON GLASSMORPHISM -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-14">

                <!-- MENÚ -->
                <a href="{{ route('menu.index') }}"
                   class="group relative rounded-2xl p-6 bg-white/20 backdrop-blur-lg border border-white/30 shadow-xl hover:shadow-2xl hover:-translate-y-1 hover:bg-white/30 transition-all duration-300">
                    <div class="absolute -top-6 right-6 text-5xl opacity-40 group-hover:opacity-100 transition">
                        ☕
                    </div>
                    <h4 class="text-xl font-bold text-white drop-shadow-lg group-hover:text-amber-300 transition">
                        Ver Menú
                    </h4>
                    <p class="text-white/80 mt-2 drop-shadow-md">
                        Explora bebidas y snacks disponibles
                    </p>
                </a>

                <!-- CARRITO -->
                <a href="{{ route('carrito.index') }}"
                   class="group relative rounded-2xl p-6 bg-white/20 backdrop-blur-lg border border-white/30 shadow-xl hover:shadow-2xl hover:-translate-y-1 hover:bg-white/30 transition-all duration-300">
                    <div class="absolute -top-6 right-6 text-5xl opacity-40 group-hover:opacity-100 transition">
                        🛒
                    </div>
                    <h4 class="text-xl font-bold text-white drop-shadow-lg group-hover:text-blue-300 transition">
                        Mi Carrito
                    </h4>
                    <p class="text-white/80 mt-2 text-sm drop-shadow-md">
                        @if(session('carrito') && count(session('carrito')) > 0)
                            Tienes {{ count(session('carrito')) }} producto(s)
                        @else
                            Aún no agregaste productos
                        @endif
                    </p>
                </a>

                <!-- PEDIDOS -->
                <a href="{{ route('pedidos.index') }}"
                   class="group relative rounded-2xl p-6 bg-white/20 backdrop-blur-lg border border-white/30 shadow-xl hover:shadow-2xl hover:-translate-y-1 hover:bg-white/30 transition-all duration-300">
                    <div class="absolute -top-6 right-6 text-5xl opacity-40 group-hover:opacity-100 transition">
                        📦
                    </div>
                    <h4 class="text-xl font-bold text-white drop-shadow-lg group-hover:text-amber-300 transition">
                        Mis Pedidos
                    </h4>
                    <p class="text-white/80 mt-2 text-sm drop-shadow-md">
                        Consulta el estado de tus pedidos
                    </p>
                </a>

            </div>

            <!-- ACCESOS RÁPIDOS CON GLASSMORPHISM -->
            @if(auth()->user()->esCocina() || auth()->user()->esAdmin())
            <div class="bg-white/20 backdrop-blur-lg border border-white/30 rounded-3xl p-8 shadow-xl">
                <h4 class="text-2xl font-bold text-white mb-6 flex items-center gap-2 drop-shadow-lg">
                    ⚙️ Accesos Rápidos
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    @if(auth()->user()->esCocina() || auth()->user()->esAdmin())
                    <a href="{{ route('cocina.index') }}"
                       class="rounded-2xl p-6 bg-green-500/20 backdrop-blur-md border border-green-300/30 hover:bg-green-500/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <h5 class="text-lg font-bold text-white drop-shadow-lg">
                            🍳 Cocina
                        </h5>
                        <p class="text-white/80 mt-1 drop-shadow-md">
                            Gestión y preparación de pedidos
                        </p>
                    </a>
                    @endif

                    @if(auth()->user()->esAdmin())
                    <a href="{{ route('admin.index') }}"
                       class="rounded-2xl p-6 bg-purple-500/20 backdrop-blur-md border border-purple-300/30 hover:bg-purple-500/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <h5 class="text-lg font-bold text-white drop-shadow-lg">
                            📊 Administración
                        </h5>
                        <p class="text-white/80 mt-1 drop-shadow-md">
                            Control y estadísticas del sistema
                        </p>
                    </a>
                    @endif

                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection