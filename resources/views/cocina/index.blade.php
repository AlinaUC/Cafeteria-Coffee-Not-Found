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
                    🍳 Dashboard de Cocina
                </h2>
            </div>

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-yellow-500/30 backdrop-blur-lg border border-yellow-400/40 rounded-2xl p-4">
                    <h3 class="text-sm font-semibold text-yellow-200 mb-1">Pendientes</h3>
                    <p class="text-3xl font-bold text-white drop-shadow-md">{{ $estadisticas['pendientes'] }}</p>
                </div>
                <div class="bg-blue-500/30 backdrop-blur-lg border border-blue-400/40 rounded-2xl p-4">
                    <h3 class="text-sm font-semibold text-blue-200 mb-1">Preparando</h3>
                    <p class="text-3xl font-bold text-white drop-shadow-md">{{ $estadisticas['preparando'] }}</p>
                </div>
                <div class="bg-green-500/30 backdrop-blur-lg border border-green-400/40 rounded-2xl p-4">
                    <h3 class="text-sm font-semibold text-green-200 mb-1">Listos</h3>
                    <p class="text-3xl font-bold text-white drop-shadow-md">{{ $estadisticas['listos'] }}</p>
                </div>
                <div class="bg-white/20 backdrop-blur-lg border border-white/30 rounded-2xl p-4">
                    <h3 class="text-sm font-semibold text-white/90 mb-1">Completados Hoy</h3>
                    <p class="text-3xl font-bold text-white drop-shadow-md">{{ $estadisticas['completados_hoy'] }}</p>
                </div>
            </div>

            <!-- Botón de refrescar -->
            <div class="mb-4">
                <button onclick="location.reload()" class="bg-white/20 backdrop-blur-md border border-white/30 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition">
                    🔄 Actualizar
                </button>
            </div>

            <!-- Tablero Kanban -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Columna: Pendientes -->
                <div>
                    <div class="bg-yellow-500/30 backdrop-blur-md border border-yellow-400/40 rounded-xl p-4 mb-4">
                        <h3 class="font-bold text-yellow-200 text-lg">Pedidos Pendientes ({{ $pedidosPendientes->count() }})</h3>
                    </div>
                    <div class="space-y-4">
                        @forelse($pedidosPendientes as $pedido)
                        <div class="bg-white/20 backdrop-blur-lg border-l-4 border-yellow-500 rounded-xl shadow-md p-4">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="font-bold text-lg text-white drop-shadow-sm">#{{ $pedido->numero_pedido }}</h4>
                                    <p class="text-sm text-white/70">{{ $pedido->created_at->format('H:i') }}</p>
                                    @if($pedido->programado_para)
                                    <p class="text-xs text-blue-300 font-semibold">
                                        📅 Para: {{ $pedido->programado_para->format('H:i') }}
                                    </p>
                                    @endif
                                </div>
                                <span class="text-xl font-bold text-amber-300 drop-shadow-sm">
                                    Bs. {{ number_format($pedido->monto_total, 2) }}
                                </span>
                            </div>

                            <div class="mb-3">
                                <p class="text-sm font-semibold text-white/90">Cliente: {{ $pedido->usuario->nombre }}</p>
                            </div>

                            <div class="mb-3">
                                <h5 class="text-sm font-semibold mb-1 text-white/90">Productos:</h5>
                                <ul class="text-sm space-y-1">
                                    @foreach($pedido->items as $item)
                                    <li class="flex justify-between text-white/80">
                                        <span>{{ $item->cantidad }}x {{ $item->producto->nombre }}</span>
                                        <span class="text-white/60">⏱️ {{ $item->producto->tiempo_preparacion }}min</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>

                            @if($pedido->notas_especiales)
                            <div class="mb-3 bg-yellow-500/20 border border-yellow-400/30 p-2 rounded">
                                <p class="text-xs font-semibold text-white/90">Notas:</p>
                                <p class="text-xs text-white/80">{{ $pedido->notas_especiales }}</p>
                            </div>
                            @endif

                            <form action="{{ route('cocina.confirmar', $pedido->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition">
                                    ✓ Confirmar y Preparar
                                </button>
                            </form>
                        </div>
                        @empty
                        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl shadow p-6 text-center text-white/60">
                            No hay pedidos pendientes
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Columna: Preparando -->
                <div>
                    <div class="bg-blue-500/30 backdrop-blur-md border border-blue-400/40 rounded-xl p-4 mb-4">
                        <h3 class="font-bold text-blue-200 text-lg">En Preparación ({{ $pedidosPreparando->count() }})</h3>
                    </div>
                    <div class="space-y-4">
                        @forelse($pedidosPreparando as $pedido)
                        <div class="bg-white/20 backdrop-blur-lg border-l-4 border-blue-500 rounded-xl shadow-md p-4">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="font-bold text-lg text-white drop-shadow-sm">#{{ $pedido->numero_pedido }}</h4>
                                    <p class="text-sm text-white/70">{{ $pedido->created_at->format('H:i') }}</p>
                                    <p class="text-xs text-white/60">
                                        Preparando desde: {{ $pedido->updated_at->diffForHumans() }}
                                    </p>
                                </div>
                                <span class="text-xl font-bold text-amber-300 drop-shadow-sm">
                                    Bs. {{ number_format($pedido->monto_total, 2) }}
                                </span>
                            </div>

                            <div class="mb-3">
                                <p class="text-sm font-semibold text-white/90">Cliente: {{ $pedido->usuario->nombre }}</p>
                            </div>

                            <div class="mb-3">
                                <h5 class="text-sm font-semibold mb-1 text-white/90">Productos:</h5>
                                <ul class="text-sm space-y-1">
                                    @foreach($pedido->items as $item)
                                    <li class="text-white/80">{{ $item->cantidad }}x {{ $item->producto->nombre }}</li>
                                    @endforeach
                                </ul>
                            </div>

                            @if($pedido->notas_especiales)
                            <div class="mb-3 bg-blue-500/20 border border-blue-400/30 p-2 rounded">
                                <p class="text-xs font-semibold text-white/90">Notas:</p>
                                <p class="text-xs text-white/80">{{ $pedido->notas_especiales }}</p>
                            </div>
                            @endif

                            <form action="{{ route('cocina.listo', $pedido->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-semibold transition">
                                    ✓ Marcar como Listo
                                </button>
                            </form>
                        </div>
                        @empty
                        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl shadow p-6 text-center text-white/60">
                            No hay pedidos en preparación
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Columna: Listos -->
                <div>
                    <div class="bg-green-500/30 backdrop-blur-md border border-green-400/40 rounded-xl p-4 mb-4">
                        <h3 class="font-bold text-green-200 text-lg">Listos para Entregar ({{ $pedidosListos->count() }})</h3>
                    </div>
                    <div class="space-y-4">
                        @forelse($pedidosListos as $pedido)
                        <div class="bg-white/20 backdrop-blur-lg border-l-4 border-green-500 rounded-xl shadow-md p-4">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="font-bold text-lg text-white drop-shadow-sm">#{{ $pedido->numero_pedido }}</h4>
                                    <p class="text-sm text-white/70">Listo: {{ $pedido->updated_at->format('H:i') }}</p>
                                </div>
                                <span class="text-xl font-bold text-amber-300 drop-shadow-sm">
                                    Bs. {{ number_format($pedido->monto_total, 2) }}
                                </span>
                            </div>

                            <div class="mb-3">
                                <p class="text-sm font-semibold text-white/90">Cliente: {{ $pedido->usuario->nombre }}</p>
                            </div>

                            <div class="mb-3">
                                <h5 class="text-sm font-semibold mb-1 text-white/90">Productos:</h5>
                                <ul class="text-sm space-y-1">
                                    @foreach($pedido->items as $item)
                                    <li class="text-white/80">{{ $item->cantidad }}x {{ $item->producto->nombre }}</li>
                                    @endforeach
                                </ul>
                            </div>

                            <form action="{{ route('cocina.entregado', $pedido->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white py-2 rounded-lg font-semibold transition">
                                    ✓ Marcar como Entregado
                                </button>
                            </form>
                        </div>
                        @empty
                        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl shadow p-6 text-center text-white/60">
                            No hay pedidos listos
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-refresh cada 30 segundos
    setTimeout(function() {
        location.reload();
    }, 30000);
</script>
@endsection
