@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-white drop-shadow-lg">
        ✅ Pago Exitoso
    </h2>
@endsection

@section('content')

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white/20 backdrop-blur-xl border border-white/30 rounded-3xl shadow-2xl p-8 text-center">
        <!-- Animación de éxito -->
        <div class="mb-8">
            <div class="inline-flex items-center justify-center w-28 h-28 bg-green-500/30 backdrop-blur-sm border-4 border-green-400/50 rounded-full mb-6 animate-bounce">
                <svg class="w-16 h-16 text-green-100 drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>

        <h3 class="text-4xl font-bold text-white mb-3 drop-shadow-lg">¡Pago Confirmado!</h3>
        <p class="text-xl text-white/90 mb-8 drop-shadow-md">Tu pedido ha sido procesado exitosamente 🎉</p>

        <!-- Detalles del pedido -->
        <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-6 mb-8">
            <div class="grid grid-cols-2 gap-6 text-left">
                <div>
                    <p class="text-sm text-white/70 mb-1 drop-shadow-sm">Número de Pedido</p>
                    <p class="font-bold text-xl text-white drop-shadow-md">{{ $pedido->numero_pedido }}</p>
                </div>
                <div>
                    <p class="text-sm text-white/70 mb-1 drop-shadow-sm">Monto Pagado</p>
                    <p class="font-bold text-xl text-green-300 drop-shadow-md">Bs. {{ number_format($pedido->monto_total, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-white/70 mb-1 drop-shadow-sm">Método de Pago</p>
                    <p class="font-semibold text-white drop-shadow-sm">
                        @if($pedido->metodo_pago == 'qr') 📱 Código QR
                        @elseif($pedido->metodo_pago == 'tarjeta') 💳 Tarjeta
                        @else 💵 {{ ucfirst($pedido->metodo_pago) }}
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-white/70 mb-1 drop-shadow-sm">Estado</p>
                    <p class="font-semibold text-amber-300 drop-shadow-sm">✅ {{ ucfirst($pedido->estado) }}</p>
                </div>
            </div>
        </div>

        <!-- Productos -->
        <div class="text-left mb-8">
            <h4 class="font-bold text-white text-lg mb-4 drop-shadow-md flex items-center gap-2">
                <span>🛍️</span>
                Productos de tu Pedido
            </h4>
            <div class="space-y-3">
                @foreach($pedido->items as $item)
                <div class="flex justify-between items-center text-sm bg-white/10 backdrop-blur-sm border border-white/20 p-4 rounded-lg">
                    <span class="text-white/90 drop-shadow-sm font-medium">
                        <span class="bg-amber-600/80 text-white px-2 py-1 rounded-md text-xs font-bold mr-2">
                            {{ $item->cantidad }}x
                        </span>
                        {{ $item->producto->nombre }}
                    </span>
                    <span class="font-bold text-amber-300 drop-shadow-sm">Bs. {{ number_format($item->precio_total, 2) }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Siguiente paso -->
        <div class="bg-yellow-500/20 backdrop-blur-sm border border-yellow-400/30 rounded-xl p-5 mb-8">
            <div class="flex items-start text-left">
                <div class="flex-shrink-0 mr-4">
                    <div class="w-10 h-10 bg-yellow-500/30 backdrop-blur-sm rounded-full flex items-center justify-center">
                        <span class="text-2xl">⏰</span>
                    </div>
                </div>
                <div class="flex-1">
                    <h5 class="font-bold text-yellow-100 mb-2 drop-shadow-md">¿Qué sigue?</h5>
                    <p class="text-sm text-yellow-50/90 drop-shadow-sm leading-relaxed">
                        Tu pedido está siendo preparado por la cocina. Recibirás una notificación cuando esté listo para recoger.
                        <br><br>
                        <strong class="text-yellow-200">⏱️ Tiempo estimado: {{ $pedido->items->sum(function($item) { return $item->producto->tiempo_preparacion; }) }} minutos</strong>
                    </p>
                </div>
            </div>
        </div>

        <!-- Acciones -->
        <div class="space-y-4">
            <a href="{{ route('pedidos.show', $pedido->id) }}" 
               class="block w-full bg-amber-600 hover:bg-amber-700 text-white py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                👁️ Ver Detalle del Pedido
            </a>
            <a href="{{ route('menu.index') }}" 
               class="block w-full bg-white/20 backdrop-blur-sm border-2 border-white/30 hover:bg-white/30 text-white py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                🏠 Volver al Menú
            </a>
        </div>

        <!-- Mensaje de agradecimiento -->
        <div class="mt-8 pt-6 border-t border-white/20">
            <p class="text-white/80 drop-shadow-md">
                ¡Gracias por tu compra! 🎉☕
                <br>
                <span class="text-sm text-white/60">Coffee Not Found - Cafetería UPDS</span>
            </p>
        </div>
    </div>
</div>

<!-- Confetti Animation -->
<script>
function createConfetti() {
    const colors = ['#f59e0b', '#fbbf24', '#fcd34d', '#fde68a', '#84cc16', '#22c55e'];
    for(let i = 0; i < 80; i++) {
        const confetti = document.createElement('div');
        confetti.style.cssText = `
            position: fixed;
            width: ${Math.random() * 10 + 5}px;
            height: ${Math.random() * 10 + 5}px;
            background: ${colors[Math.floor(Math.random() * colors.length)]};
            top: -10px;
            left: ${Math.random() * 100}vw;
            opacity: ${Math.random() * 0.8 + 0.2};
            transform: rotate(${Math.random() * 360}deg);
            animation: fall ${2 + Math.random() * 3}s linear;
            pointer-events: none;
            z-index: 9999;
            border-radius: ${Math.random() > 0.5 ? '50%' : '2px'};
            box-shadow: 0 0 10px rgba(255,255,255,0.5);
        `;
        document.body.appendChild(confetti);
        setTimeout(() => confetti.remove(), 5000);
    }
}

const style = document.createElement('style');
style.textContent = `
    @keyframes fall {
        to {
            top: 100vh;
            transform: translateY(100vh) rotate(720deg);
        }
    }
`;
document.head.appendChild(style);

window.addEventListener('load', () => {
    createConfetti();
    setTimeout(createConfetti, 300);
});
</script>
@endsection