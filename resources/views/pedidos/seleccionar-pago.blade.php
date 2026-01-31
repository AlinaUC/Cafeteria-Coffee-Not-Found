@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-white drop-shadow-lg">
        💳 Seleccionar Método de Pago
    </h2>
@endsection

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Resumen del Pedido -->
        <div class="lg:col-span-1">
            <div class="bg-white/20 backdrop-blur-xl border border-white/30 rounded-2xl shadow-2xl p-6 sticky top-6">
                <h3 class="text-xl font-bold text-white mb-5 drop-shadow-lg flex items-center gap-2">
                    <span>🧾</span>
                    Resumen del Pedido
                </h3>
                
                <div class="mb-5 pb-5 border-b border-white/20">
                    <p class="text-sm text-white/70 mb-1 drop-shadow-sm">Número de Pedido:</p>
                    <p class="font-bold text-lg text-white drop-shadow-md">{{ $pedido->numero_pedido }}</p>
                </div>

                <div class="space-y-3 mb-6">
                    @foreach($pedido->items as $item)
                    <div class="flex justify-between text-sm bg-white/10 backdrop-blur-sm rounded-lg p-3 border border-white/10">
                        <span class="text-white/90">{{ $item->cantidad }}x {{ $item->producto->nombre }}</span>
                        <span class="font-semibold text-amber-300">Bs. {{ number_format($item->precio_total, 2) }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="border-t border-white/20 pt-4">
                    <div class="flex justify-between text-xl font-bold">
                        <span class="text-white drop-shadow-md">Total:</span>
                        <span class="text-amber-300 drop-shadow-lg">Bs. {{ number_format($pedido->monto_total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Métodos de Pago -->
        <div class="lg:col-span-2">
            <div class="bg-white/20 backdrop-blur-xl border border-white/30 rounded-2xl shadow-2xl p-8">
                <h3 class="text-2xl font-bold text-white mb-8 drop-shadow-lg">Selecciona tu Método de Pago</h3>

                <form action="{{ route('pedidos.pagar') }}" method="POST">
                    @csrf

                    <div class="space-y-5 mb-8">
                        <!-- Tarjeta de Crédito/Débito -->
                        <label class="block cursor-pointer group">
                            <div class="border-2 rounded-xl p-6 transition-all duration-300 {{ old('metodo_pago') == 'tarjeta' ? 'border-amber-500 bg-amber-500/20 backdrop-blur-lg' : 'border-white/30 bg-white/10 backdrop-blur-lg hover:border-amber-400/50 hover:bg-white/15' }}">
                                <div class="flex items-center">
                                    <input type="radio" name="metodo_pago" value="tarjeta" class="w-5 h-5 text-amber-600 border-white/30 focus:ring-amber-500" {{ old('metodo_pago') == 'tarjeta' ? 'checked' : '' }} required>
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="text-lg font-bold text-white drop-shadow-md mb-1">💳 Tarjeta de Crédito/Débito</h4>
                                                <p class="text-sm text-white/80 drop-shadow-sm">Pago seguro mediante Stripe</p>
                                            </div>
                                            <div class="flex gap-2">
                                                <div class="bg-white/90 backdrop-blur-sm px-3 py-1 rounded-lg">
                                                    <svg class="w-10 h-6" viewBox="0 0 48 32" fill="none">
                                                        <rect width="48" height="32" rx="4" fill="#1434CB"/>
                                                        <path d="M17.5 11h13v10h-13z" fill="#FF5F00"/>
                                                        <path d="M18.5 16a6.5 6.5 0 0 1 3-5.5 6.5 6.5 0 1 0 0 11 6.5 6.5 0 0 1-3-5.5z" fill="#EB001B"/>
                                                        <path d="M31.5 16a6.5 6.5 0 0 1-10.5 5.5 6.5 6.5 0 0 0 0-11A6.5 6.5 0 0 1 31.5 16z" fill="#F79E1B"/>
                                                    </svg>
                                                </div>
                                                <div class="bg-white/90 backdrop-blur-sm px-3 py-1 rounded-lg">
                                                    <svg class="w-10 h-6" viewBox="0 0 48 32" fill="none">
                                                        <rect width="48" height="32" rx="4" fill="#0066B2"/>
                                                        <path d="M24 26c-5.5 0-10-4.5-10-10s4.5-10 10-10 10 4.5 10 10-4.5 10-10 10z" fill="#FFF"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-2 text-xs text-white/70 drop-shadow-sm">
                                            ✓ Pago instantáneo • ✓ 100% seguro
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- Efectivo -->
                        <label class="block cursor-pointer group">
                            <div class="border-2 rounded-xl p-6 transition-all duration-300 {{ old('metodo_pago') == 'efectivo' ? 'border-amber-500 bg-amber-500/20 backdrop-blur-lg' : 'border-white/30 bg-white/10 backdrop-blur-lg hover:border-amber-400/50 hover:bg-white/15' }}">
                                <div class="flex items-center">
                                    <input type="radio" name="metodo_pago" value="efectivo" class="w-5 h-5 text-amber-600 border-white/30 focus:ring-amber-500" {{ old('metodo_pago') == 'efectivo' ? 'checked' : '' }} required>
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="text-lg font-bold text-white drop-shadow-md mb-1">💵 Efectivo</h4>
                                                <p class="text-sm text-white/80 drop-shadow-sm">Paga al recoger tu pedido</p>
                                            </div>
                                            <div class="text-5xl opacity-80">💰</div>
                                        </div>
                                        <div class="mt-2 text-xs text-white/70 drop-shadow-sm">
                                            Paga en la caja al momento de recoger
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- QR Boliviano -->
                        <label class="block cursor-pointer group">
                            <div class="border-2 rounded-xl p-6 transition-all duration-300 {{ old('metodo_pago') == 'qr' ? 'border-amber-500 bg-amber-500/20 backdrop-blur-lg' : 'border-white/30 bg-white/10 backdrop-blur-lg hover:border-amber-400/50 hover:bg-white/15' }}">
                                <div class="flex items-center">
                                    <input type="radio" name="metodo_pago" value="qr" class="w-5 h-5 text-amber-600 border-white/30 focus:ring-amber-500" {{ old('metodo_pago') == 'qr' ? 'checked' : '' }} required>
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="text-lg font-bold text-white drop-shadow-md mb-1">📱 Código QR</h4>
                                                <p class="text-sm text-white/80 drop-shadow-sm">Pago QR Simple (Bolivia)</p>
                                            </div>
                                            <div class="text-5xl opacity-80">📲</div>
                                        </div>
                                        <div class="mt-2 text-xs text-white/70 drop-shadow-sm">
                                            Compatible con todos los bancos bolivianos
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>

                    @error('metodo_pago')
                        <div class="bg-red-500/20 backdrop-blur-sm border border-red-400/30 rounded-lg p-3 mb-6">
                            <p class="text-red-100 text-sm drop-shadow-sm">{{ $message }}</p>
                        </div>
                    @enderror

                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="submit" class="flex-1 bg-amber-600 hover:bg-amber-700 text-white py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            Continuar al Pago →
                        </button>
                        <a href="{{ route('carrito.index') }}" class="flex-1 bg-white/20 backdrop-blur-sm border-2 border-white/30 hover:bg-white/30 text-white py-4 rounded-xl font-bold text-center shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            ← Cancelar
                        </a>
                    </div>
                </form>
            </div>

            <!-- Información de Seguridad -->
            <div class="mt-6 bg-blue-500/20 backdrop-blur-lg border border-blue-400/30 rounded-2xl shadow-xl p-5">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mr-4">
                        <div class="w-12 h-12 bg-blue-500/30 backdrop-blur-sm rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-100" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold text-blue-100 mb-2 drop-shadow-md">🔒 Pago Seguro</h4>
                        <p class="text-sm text-blue-100/90 drop-shadow-sm">
                            Todos los pagos con tarjeta son procesados de forma segura mediante Stripe. 
                            No almacenamos información de tu tarjeta.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection