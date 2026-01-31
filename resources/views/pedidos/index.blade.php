@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-white drop-shadow-lg">
        ☕ Mis Pedidos
    </h2>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if($pedidos->isEmpty())
        <!-- ESTADO VACÍO -->
        <div class="bg-white/20 backdrop-blur-lg border border-white/30 rounded-2xl shadow-2xl p-12 text-center">
            <div class="mb-6">
                <span class="text-6xl">📋</span>
            </div>
            <h3 class="text-2xl font-bold text-white mb-4 drop-shadow-lg">
                No tienes pedidos aún
            </h3>
            <p class="text-white/80 mb-6 drop-shadow-md">
                ¡Comienza a disfrutar de nuestros deliciosos productos!
            </p>
            <a href="{{ route('menu.index') }}"
               class="inline-block bg-amber-600 hover:bg-amber-700 text-white font-semibold px-8 py-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                Ver Menú
            </a>
        </div>
    @else
        <!-- LISTA DE PEDIDOS -->
        <div class="space-y-6">
            @foreach($pedidos as $pedido)
                <div class="bg-white/20 backdrop-blur-lg border border-white/30 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden">
                    <!-- HEADER DEL PEDIDO -->
                    <div class="bg-gradient-to-r from-amber-600/20 to-amber-700/20 backdrop-blur-sm border-b border-white/20 p-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div>
                                <h3 class="text-xl font-bold text-white drop-shadow-lg flex items-center gap-2">
                                    <span class="text-2xl">🧾</span>
                                    Pedido #{{ $pedido->numero_pedido }}
                                </h3>
                                <p class="text-sm text-white/80 drop-shadow-md mt-1">
                                    📅 {{ $pedido->created_at->format('d/m/Y H:i') }}
                                </p>

                                @if($pedido->programado_para)
                                    <p class="text-sm text-amber-300 drop-shadow-md mt-1 flex items-center gap-1">
                                        ⏰ Programado: {{ $pedido->programado_para->format('d/m/Y H:i') }}
                                    </p>
                                @endif
                            </div>

                            <div class="text-left sm:text-right">
                                <!-- ESTADO DEL PEDIDO -->
                                <span class="inline-block px-4 py-2 rounded-full text-sm font-bold shadow-lg
                                    @if($pedido->estado == 'pendiente') bg-yellow-500/90 text-white
                                    @elseif(in_array($pedido->estado, ['confirmado','preparando'])) bg-blue-500/90 text-white
                                    @elseif($pedido->estado == 'listo') bg-green-500/90 text-white
                                    @elseif($pedido->estado == 'completado') bg-gray-500/90 text-white
                                    @else bg-red-500/90 text-white
                                    @endif">
                                    @if($pedido->estado == 'pendiente') ⏳
                                    @elseif(in_array($pedido->estado, ['confirmado','preparando'])) 👨‍🍳
                                    @elseif($pedido->estado == 'listo') ✅
                                    @elseif($pedido->estado == 'completado') ✔️
                                    @else ❌
                                    @endif
                                    {{ ucfirst($pedido->estado) }}
                                </span>

                                <!-- MONTO TOTAL -->
                                <p class="text-2xl font-bold text-amber-300 drop-shadow-lg mt-3">
                                    Bs. {{ number_format($pedido->monto_total, 2) }}
                                </p>

                                <!-- ESTADO DE PAGO -->
                                <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full mt-2 shadow-md
                                    @if($pedido->estado_pago == 'pagado') bg-green-500/90 text-white
                                    @elseif($pedido->estado_pago == 'pendiente') bg-yellow-500/90 text-white
                                    @else bg-red-500/90 text-white
                                    @endif">
                                    @if($pedido->estado_pago == 'pagado') 💳 Pagado
                                    @elseif($pedido->estado_pago == 'pendiente') ⏳ Pendiente
                                    @else ❌ {{ ucfirst($pedido->estado_pago) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- CONTENIDO DEL PEDIDO -->
                    <div class="p-6">
                        <!-- PRODUCTOS -->
                        <div class="mb-4">
                            <h4 class="font-bold text-white text-lg mb-3 drop-shadow-md flex items-center gap-2">
                                <span>🛍️</span>
                                Productos
                            </h4>
                            <ul class="space-y-2">
                                @foreach($pedido->items as $item)
                                    <li class="flex justify-between items-center text-sm bg-white/10 backdrop-blur-sm rounded-lg p-3 border border-white/20">
                                        <span class="text-white/90 drop-shadow-sm font-medium">
                                            <span class="bg-amber-600/80 text-white px-2 py-1 rounded-md text-xs font-bold mr-2">
                                                {{ $item->cantidad }}x
                                            </span>
                                            {{ $item->producto->nombre }}
                                        </span>
                                        <span class="text-amber-300 font-bold drop-shadow-sm">
                                            Bs. {{ number_format($item->precio_total, 2) }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- NOTAS ESPECIALES -->
                        @if($pedido->notas_especiales)
                            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-lg p-4 mb-4">
                                <p class="text-sm text-white/90 drop-shadow-sm">
                                    <strong class="text-amber-300">📝 Notas:</strong> {{ $pedido->notas_especiales }}
                                </p>
                            </div>
                        @endif

                        <!-- BOTONES DE ACCIÓN -->
                        <div class="flex flex-wrap gap-3 mt-6">
                            <a href="{{ route('pedidos.show', $pedido->id) }}"
                               class="bg-amber-600 hover:bg-amber-700 text-white font-semibold px-6 py-2.5 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 inline-flex items-center gap-2">
                                <span>👁️</span>
                                Ver Detalle
                            </a>

                            @if($pedido->estado == 'pendiente' && $pedido->estado_pago == 'pendiente')
                                <form action="{{ route('pedidos.cancelar', $pedido->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Estás seguro de cancelar este pedido?')"
                                      class="inline-block">
                                    @csrf
                                    <button type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2.5 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 inline-flex items-center gap-2">
                                        <span>❌</span>
                                        Cancelar Pedido
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- PAGINACIÓN -->
        <div class="mt-8">
            {{ $pedidos->links() }}
        </div>
    @endif
</div>

{{-- 🔔 Contenedor de notificaciones --}}
<div id="notificaciones-container"
     class="fixed top-20 right-4 z-50 space-y-3"
     style="max-width: 400px;">
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    if (typeof Echo === 'undefined') {
        console.error('🚫 Laravel Echo no está cargado');
        return;
    }

    const usuarioId = {{ auth()->id() }};
    console.log('🔔 Escuchando pedidos del usuario:', usuarioId);

    Echo.private(`pedido.${usuarioId}`)
        .listen('.pedido.actualizado', (e) => {
            mostrarNotificacion(e);
            reproducirSonido();

            setTimeout(() => {
                window.location.reload();
            }, 3000);
        });

    function mostrarNotificacion(data) {
        const container = document.getElementById('notificaciones-container');

        const notificacion = document.createElement('div');
        notificacion.className =
            'bg-white/95 backdrop-blur-lg rounded-xl shadow-2xl p-5 border-l-4 border-amber-500 animate-slide-in';

        notificacion.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <span class="text-3xl">🔔</span>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-bold text-gray-900">
                        Pedido #${data.pedido_id}
                    </p>
                    <p class="text-sm text-gray-700 font-medium mt-1">${data.mensaje}</p>
                    <p class="text-xs text-gray-500 mt-1">${data.tiempo}</p>
                </div>
                <button onclick="this.closest('div').parentElement.remove()"
                        class="ml-4 text-gray-400 hover:text-gray-700 font-bold text-xl transition">
                    ✕
                </button>
            </div>
        `;

        container.prepend(notificacion);

        setTimeout(() => {
            notificacion.style.opacity = '0';
            notificacion.style.transform = 'translateX(400px)';
            setTimeout(() => notificacion.remove(), 300);
        }, 5000);
    }

    function reproducirSonido() {
        const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZF...');
        audio.play().catch(() => {});
    }
});
</script>

<style>
@keyframes slide-in {
    from { 
        transform: translateX(400px); 
        opacity: 0; 
    }
    to { 
        transform: translateX(0); 
        opacity: 1; 
    }
}

.animate-slide-in {
    animation: slide-in 0.4s ease-out;
    transition: all 0.3s ease-out;
}

/* Mejorar la animación de salida */
#notificaciones-container > div {
    transition: all 0.3s ease-out;
}
</style>
@endpush