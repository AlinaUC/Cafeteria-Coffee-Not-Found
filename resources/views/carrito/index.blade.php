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
                    🛒 Mi Carrito
                </h2>
            </div>

            @if(empty($carrito))
            <div class="bg-white/20 backdrop-blur-lg border border-white/30 rounded-2xl shadow-xl p-8 text-center">
                <p class="text-white/80 mb-4">Tu carrito está vacío</p>
                <a href="{{ route('menu.index') }}" class="inline-block bg-amber-600 hover:bg-amber-700 text-white px-6 py-2 rounded-lg transition font-semibold">
                    Ver Menú
                </a>
            </div>
            @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <div class="bg-white/20 backdrop-blur-lg border border-white/30 rounded-2xl shadow-xl">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4 text-white drop-shadow-md">Productos en el Carrito</h3>

                            @foreach($carrito as $item)
                            <div class="flex items-center gap-4 py-4 border-b border-white/10 last:border-b-0">
                                @if($item['imagen'])
                                <img src="{{ $item['imagen'] }}" alt="{{ $item['nombre'] }}" class="w-20 h-20 object-cover rounded-lg">
                                @else
                                <div class="w-20 h-20 bg-white/10 rounded-lg flex items-center justify-center">
                                    <span class="text-2xl">🍽️</span>
                                </div>
                                @endif

                                <div class="flex-1">
                                    <h4 class="font-semibold text-white drop-shadow-sm">{{ $item['nombre'] }}</h4>
                                    <p class="text-sm text-white/70">Bs. {{ number_format($item['precio'], 2) }} c/u</p>
                                    <p class="text-xs text-white/60">Tiempo de preparación: {{ $item['tiempo_preparacion'] }} min</p>
                                </div>

                                <div class="flex items-center gap-2">
                                    <form action="{{ route('carrito.actualizar', $item['id']) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="cantidad" value="{{ $item['cantidad'] }}" min="1" class="w-16 px-2 py-1 border border-white/30 bg-white/10 text-white rounded text-center">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition">
                                            Actualizar
                                        </button>
                                    </form>

                                    <form action="{{ route('carrito.eliminar', $item['id']) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-300 hover:text-red-200 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>

                                <div class="text-right">
                                    <p class="font-semibold text-white drop-shadow-sm">
                                        Bs. {{ number_format($item['precio'] * $item['cantidad'], 2) }}
                                    </p>
                                </div>
                            </div>
                            @endforeach

                            <div class="mt-4">
                                <form action="{{ route('carrito.vaciar') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-300 hover:text-red-200 text-sm transition">
                                        Vaciar Carrito
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white/20 backdrop-blur-lg border border-white/30 rounded-2xl shadow-xl p-6 sticky top-6">
                        <h3 class="text-lg font-semibold mb-4 text-white drop-shadow-md">Resumen del Pedido</h3>

                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between text-white/80">
                                <span>Subtotal:</span>
                                <span class="font-semibold text-white">Bs. {{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <div class="border-t border-white/20 pt-4 mb-4">
                            <div class="flex justify-between text-lg font-bold">
                                <span class="text-white">Total:</span>
                                <span class="text-amber-300">Bs. {{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <form action="{{ route('pedidos.crear') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-white/90 mb-2">
                                    Notas Especiales (Opcional)
                                </label>
                                <textarea name="notas_especiales" rows="3" class="w-full border-white/30 bg-white/10 text-white placeholder-white/50 rounded-lg" placeholder="Ej: Sin cebolla, extra queso..."></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-white/90 mb-2">
                                    Programar Pedido (Opcional)
                                </label>
                                <input type="datetime-local" name="programado_para" min="{{ now()->addMinutes(30)->format('Y-m-d\TH:i') }}" max="{{ now()->addHours(8)->format('Y-m-d\TH:i') }}" class="w-full border-white/30 bg-white/10 text-white rounded-lg">
                                <p class="text-xs text-white/60 mt-1">Mínimo 30 minutos, máximo 8 horas</p>
                            </div>

                            <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white py-3 rounded-lg font-semibold transition">
                                Crear Pedido y Pagar
                            </button>
                        </form>

                        <a href="{{ route('menu.index') }}" class="block text-center mt-4 text-blue-300 hover:text-blue-200 transition">
                            Seguir Comprando
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
