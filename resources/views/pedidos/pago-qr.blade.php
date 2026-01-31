@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-white drop-shadow-lg">
        📱 Pago con Código QR
    </h2>
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white/20 backdrop-blur-xl border border-white/30 rounded-3xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-amber-600/30 backdrop-blur-sm border-2 border-amber-400/40 rounded-full mb-4">
                <span class="text-4xl">📱</span>
            </div>
            <h3 class="text-3xl font-bold text-white mb-3 drop-shadow-lg">Escanea el Código QR</h3>
            <p class="text-white/90 mb-6 drop-shadow-md">Utiliza tu aplicación de banco móvil para completar el pago</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Código QR -->
            <div class="flex flex-col items-center">
                <div class="bg-white border-4 border-amber-500 rounded-2xl p-8 mb-6 shadow-2xl">
                    <img src="{{ asset('images/qr-cafeteria.png') }}" 
                         alt="Código QR Cafetería UPDS" 
                         class="w-64 h-64 object-contain"
                         onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'256\' height=\'256\'%3E%3Crect width=\'256\' height=\'256\' fill=\'%23f5f5f5\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-size=\'24\' font-weight=\'bold\' fill=\'%23d97706\'%3EQR CAFETERÍA%3C/text%3E%3Ctext x=\'50%25\' y=\'60%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-size=\'16\' fill=\'%23666\'%3EUPDS%3C/text%3E%3C/svg%3E'">
                </div>
                
                <div class="text-center bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl p-4 w-full">
                    <p class="text-sm font-semibold text-white/80 mb-2 drop-shadow-sm">Pedido #{{ $pedido->numero_pedido }}</p>
                    <p class="text-4xl font-bold text-amber-300 drop-shadow-lg">Bs. {{ number_format($pedido->monto_total, 2) }}</p>
                </div>
            </div>

            <!-- Instrucciones y detalles -->
            <div class="flex flex-col justify-between">
                <div>
                    <h4 class="text-xl font-bold text-white mb-5 drop-shadow-lg">📝 Instrucciones de Pago</h4>
                    <ol class="space-y-4 mb-8">
                        <li class="flex items-start">
                            <span class="flex items-center justify-center w-8 h-8 bg-amber-600 text-white rounded-full text-sm font-bold mr-3 flex-shrink-0 shadow-lg">1</span>
                            <span class="text-white/90 drop-shadow-sm pt-1">Abre tu aplicación de banco móvil (BNB Móvil, Banco BISA App, etc.)</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex items-center justify-center w-8 h-8 bg-amber-600 text-white rounded-full text-sm font-bold mr-3 flex-shrink-0 shadow-lg">2</span>
                            <span class="text-white/90 drop-shadow-sm pt-1">Selecciona la opción "QR Simple" o "Pagar con QR"</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex items-center justify-center w-8 h-8 bg-amber-600 text-white rounded-full text-sm font-bold mr-3 flex-shrink-0 shadow-lg">3</span>
                            <span class="text-white/90 drop-shadow-sm pt-1">Escanea el código QR mostrado arriba</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex items-center justify-center w-8 h-8 bg-amber-600 text-white rounded-full text-sm font-bold mr-3 flex-shrink-0 shadow-lg">4</span>
                            <span class="text-white/90 drop-shadow-sm pt-1">Confirma el monto de <strong class="text-amber-300">Bs. {{ number_format($pedido->monto_total, 2) }}</strong></span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex items-center justify-center w-8 h-8 bg-amber-600 text-white rounded-full text-sm font-bold mr-3 flex-shrink-0 shadow-lg">5</span>
                            <span class="text-white/90 drop-shadow-sm pt-1">Completa la transacción en tu banco</span>
                        </li>
                        <li class="flex items-start">
                            <span class="flex items-center justify-center w-8 h-8 bg-amber-600 text-white rounded-full text-sm font-bold mr-3 flex-shrink-0 shadow-lg">6</span>
                            <span class="text-white/90 drop-shadow-sm pt-1">Haz clic en "Ya realicé el pago" abajo</span>
                        </li>
                    </ol>

                    <!-- Información importante -->
                    <div class="bg-blue-500/20 backdrop-blur-sm border border-blue-400/30 rounded-xl p-4 mb-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-3">
                                <div class="w-8 h-8 bg-blue-500/30 backdrop-blur-sm rounded-full flex items-center justify-center">
                                    <span class="text-xl">ℹ️</span>
                                </div>
                            </div>
                            <div class="text-sm text-blue-100/90 drop-shadow-sm">
                                <p class="font-bold mb-1">Información importante:</p>
                                <p>Este es un código QR de demostración. En producción, aquí se generaría un QR único vinculado a tu pedido.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen del pedido -->
                    <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl p-4">
                        <h5 class="font-bold text-white mb-3 drop-shadow-sm">🛍️ Resumen del Pedido</h5>
                        <div class="space-y-2">
                            @foreach($pedido->items as $item)
                            <div class="flex justify-between text-sm text-white/90">
                                <span>{{ $item->cantidad }}x {{ $item->producto->nombre }}</span>
                                <span class="font-semibold text-amber-300">Bs. {{ number_format($item->precio_total, 2) }}</span>
                            </div>
                            @endforeach
                            <div class="border-t border-white/20 pt-2 mt-2 flex justify-between font-bold">
                                <span class="text-white">Total:</span>
                                <span class="text-amber-300 text-lg">Bs. {{ number_format($pedido->monto_total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="space-y-3 mt-8">
                    <form action="{{ route('pedidos.pago.qr.confirmar') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Ya realicé el pago
                        </button>
                    </form>

                    <a href="{{ route('pedidos.seleccionar.pago') }}" 
                       class="block w-full bg-white/20 backdrop-blur-sm border-2 border-white/30 hover:bg-white/30 text-white py-3 rounded-xl font-semibold text-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300">
                        Cambiar Método de Pago
                    </a>

                    <button onclick="return confirm('¿Estás seguro de cancelar el pedido?') && document.getElementById('formCancelar').submit()" 
                            class="w-full text-red-300 hover:text-red-100 py-2 text-sm font-semibold drop-shadow-sm transition">
                        ❌ Cancelar Pedido
                    </button>

                    <form id="formCancelar" action="{{ route('pedidos.cancelar', $pedido->id) }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection