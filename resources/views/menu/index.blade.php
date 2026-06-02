@extends('layouts.app')

@section('content')
<!-- FONDO GENERAL -->
<div class="min-h-screen bg-cover bg-center bg-fixed"
     style="background-image: url('{{ asset('images/cafeteria.jpg') }}')">

    <!-- OVERLAY -->
    <div class="min-h-screen bg-black/40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            <!-- TÍTULO -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-white drop-shadow-lg">
                    ☕ Menú del Día
                </h2>
            </div>

            <!-- FILTROS DE CATEGORÍAS -->
            <div class="mb-6">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('menu.index') }}" 
                       class="px-4 py-2 rounded-lg transition {{ !$categoriaSeleccionada ? 'bg-white/30 backdrop-blur-md border border-white/40 text-white font-semibold' : 'bg-white/10 backdrop-blur-md border border-white/20 text-white/90 hover:bg-white/20' }}">
                        Todos
                    </a>
                    @foreach($categorias as $categoria)
                    <a href="{{ route('menu.index', ['categoria' => $categoria->id]) }}" 
                       class="px-4 py-2 rounded-lg transition {{ $categoriaSeleccionada == $categoria->id ? 'bg-white/30 backdrop-blur-md border border-white/40 text-white font-semibold' : 'bg-white/10 backdrop-blur-md border border-white/20 text-white/90 hover:bg-white/20' }}">
                        {{ $categoria->nombre }} ({{ $categoria->productos_count }})
                    </a>
                    @endforeach
                </div>
            </div>

            @if($productos->isEmpty())
            <div class="bg-white/20 backdrop-blur-lg border border-white/30 rounded-2xl shadow-xl p-8 text-center">
                <p class="text-white/80">No hay productos disponibles en esta categoría</p>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($productos as $producto)
                <div class="group bg-white/20 backdrop-blur-lg border border-white/30 rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    @if($producto->imagen)
                        <img src="{{ asset($producto->imagen) }}" 
                            alt="{{ $producto->nombre }}" 
                            class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-white/10 flex items-center justify-center">
                            <span class="text-white/40 text-4xl">🍽️</span>
                        </div>
                    @endif

                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-semibold text-white drop-shadow-md">{{ $producto->nombre }}</h3>
                            <span class="text-lg font-bold text-amber-300 drop-shadow-md">Bs. {{ number_format($producto->precio, 2) }}</span>
                        </div>

                        <p class="text-sm text-white/80 mb-3 drop-shadow-sm">{{ Str::limit($producto->descripcion, 100) }}</p>

                        <div class="flex flex-wrap gap-2 mb-3">
                            <span class="text-xs bg-white/20 backdrop-blur-sm text-white border border-white/30 px-2 py-1 rounded">
                                {{ $producto->categoria->nombre }}
                            </span>
                            @if($producto->es_vegetariano)
                                <span class="text-xs bg-green-500/30 text-green-200 border border-green-400/30 px-2 py-1 rounded">
                                    Vegetariano
                                </span>
                            @endif
                            @if($producto->es_vegano)
                                <span class="text-xs bg-green-500/30 text-green-200 border border-green-400/30 px-2 py-1 rounded">
                                    Vegano
                                </span>
                            @endif
                            <span class="text-xs bg-white/20 backdrop-blur-sm text-white border border-white/30 px-2 py-1 rounded">
                                ⏱️ {{ $producto->tiempo_preparacion }} min
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm {{ $producto->cantidad_disponible > 0 ? 'text-green-300' : 'text-red-300' }} drop-shadow-sm">
                                @if($producto->cantidad_disponible > 0)
                                    ✓ Disponible ({{ $producto->cantidad_disponible }})
                                @else
                                    ✗ Agotado
                                @endif
                            </span>

                            @if($producto->cantidad_disponible > 0)
                                <form action="{{ route('carrito.agregar') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                                    <input type="hidden" name="cantidad" value="1">
                                    <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm transition font-semibold">
                                        Agregar al Carrito
                                    </button>
                                </form>
                            @else
                                <button disabled class="bg-gray-600/50 text-gray-300 px-4 py-2 rounded-lg text-sm cursor-not-allowed">
                                    No Disponible
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
