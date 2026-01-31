@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-white drop-shadow-lg">
        📋 Pedido #{{ $pedido->numero_pedido }}
    </h2>
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white/20 backdrop-blur-xl border border-white/30 rounded-2xl shadow-2xl overflow-hidden">
        <!-- Header con estado -->
        <div class="bg-gradient-to-r from-amber-600/20 to-amber-700/20 backdrop-blur-sm border-b border-white/20 p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-white drop-shadow-lg mb-2">Pedido #{{ $pedido->numero_pedido }}</h3>
                    <p class="text-white/80 drop-shadow-sm">📅 {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="flex flex-col gap-2">
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
                    <span class="inline-block px-4 py-2 rounded-full text-sm font-bold shadow-lg
                        @if($pedido->estado_pago == 'pagado') bg-green-500/90 text-white
                        @elseif($pedido->estado_pago == 'pendiente') bg-yellow-500/90 text-white
                        @else bg-red-500/90 text-white
                        @endif">
                        @if($pedido->estado_pago == 'pagado') 💳
                        @elseif($pedido->estado_pago == 'pendiente') ⏳
                        @else ❌
                        @endif
                        {{ ucfirst($pedido->estado_pago) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Información del Pedido -->
                <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl p-5">
                    <h3 class="text-lg font-bold text-white mb-4 drop-shadow-md flex items-center gap-2">
                        <span>📦</span>
                        Información del Pedido
                    </h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm text-white/70 drop-shadow-sm">Número:</dt>
                            <dd class="font-semibold text-white drop-shadow-sm">{{ $pedido->numero_pedido }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-white/70 drop-shadow-sm">Fecha:</dt>
                            <dd class="text-white/90 drop-shadow-sm">{{ $pedido->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        @if($pedido->programado_para)
                        <div class="flex justify-between">
                            <dt class="text-sm text-white/70 drop-shadow-sm">Programado:</dt>
                            <dd class="text-amber-300 font-semibold drop-shadow-sm">{{ $pedido->programado_para->format('d/m/Y H:i') }}</dd>
                        </div>
                        @endif
                        @if($pedido->metodo_pago)
                        <div class="flex justify-between">
                            <dt class="text-sm text-white/70 drop-shadow-sm">Método de Pago:</dt>
                            <dd class="text-white/90 drop-shadow-sm">
                                @if($pedido->metodo_pago == 'qr') 📱 QR
                                @elseif($pedido->metodo_pago == 'tarjeta') 💳 Tarjeta
                                @else 💵 {{ ucfirst($pedido->metodo_pago) }}
                                @endif
                            </dd>
                        </div>
                        @endif

                        @if($pedido->metodo_pago == 'qr')
                        <div class="pt-3 border-t border-white/20">
                            <dt class="text-sm text-white/70 mb-2 drop-shadow-sm">Comprobante:</dt>
                            <dd>
                                @if($pedido->comprobante_pago)
                                    <div class="flex flex-col gap-2">
                                        <a href="{{ route('pedidos.comprobante.ver', $pedido->id) }}" 
                                           target="_blank"
                                           class="inline-flex items-center gap-2 text-amber-300 hover:text-amber-200 font-semibold drop-shadow-sm transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Ver comprobante
                                        </a>
                                        <p class="text-xs text-white/60 drop-shadow-sm">
                                            Subido: {{ $pedido->comprobante_subido_en->format('d/m/Y H:i') }}
                                        </p>
                                        @if($pedido->estado_pago == 'pendiente')
                                        <a href="{{ route('pedidos.subir.comprobante', $pedido->id) }}" 
                                           class="text-sm text-amber-300 hover:text-amber-200 drop-shadow-sm transition">
                                            Actualizar comprobante →
                                        </a>
                                        @endif
                                    </div>
                                @else
                                    <a href="{{ route('pedidos.subir.comprobante', $pedido->id) }}" 
                                       class="inline-flex items-center gap-2 bg-orange-500/80 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                        Subir comprobante
                                    </a>
                                @endif
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>

                <!-- Información del Cliente -->
                <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl p-5">
                    <h3 class="text-lg font-bold text-white mb-4 drop-shadow-md flex items-center gap-2">
                        <span>👤</span>
                        Cliente
                    </h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm text-white/70 drop-shadow-sm">Nombre:</dt>
                            <dd class="text-white/90 drop-shadow-sm">{{ $pedido->usuario->nombre }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-white/70 drop-shadow-sm">Email:</dt>
                            <dd class="text-white/90 drop-shadow-sm text-sm">{{ $pedido->usuario->email }}</dd>
                        </div>
                        @if($pedido->usuario->telefono)
                        <div class="flex justify-between">
                            <dt class="text-sm text-white/70 drop-shadow-sm">Teléfono:</dt>
                            <dd class="text-white/90 drop-shadow-sm">{{ $pedido->usuario->telefono }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Productos del Pedido -->
            <div class="mb-6">
                <h3 class="text-xl font-bold text-white mb-4 drop-shadow-md flex items-center gap-2">
                    <span>🛍️</span>
                    Productos del Pedido
                </h3>
                <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-white/10 backdrop-blur-sm">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white/80 uppercase tracking-wider drop-shadow-sm">Producto</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white/80 uppercase tracking-wider drop-shadow-sm">Cantidad</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white/80 uppercase tracking-wider drop-shadow-sm">P. Unit.</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-white/80 uppercase tracking-wider drop-shadow-sm">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @foreach($pedido->items as $item)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-white drop-shadow-sm">{{ $item->producto->nombre }}</div>
                                        @if($item->personalizaciones)
                                        <div class="text-xs text-white/60 drop-shadow-sm mt-1">{{ $item->personalizaciones }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-white/90 drop-shadow-sm">
                                        <span class="bg-amber-600/80 px-2 py-1 rounded-md font-bold">{{ $item->cantidad }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-white/90 drop-shadow-sm">
                                        Bs. {{ number_format($item->precio_unitario, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-amber-300 drop-shadow-sm">
                                        Bs. {{ number_format($item->precio_total, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gradient-to-r from-amber-600/20 to-amber-700/20 backdrop-blur-sm">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right font-bold text-white drop-shadow-md">Total:</td>
                                    <td class="px-6 py-4 text-xl font-bold text-amber-300 drop-shadow-lg">
                                        Bs. {{ number_format($pedido->monto_total, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Notas Especiales -->
            @if($pedido->notas_especiales)
            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl p-5 mb-6">
                <h3 class="text-lg font-bold text-white mb-3 drop-shadow-md flex items-center gap-2">
                    <span>📝</span>
                    Notas Especiales
                </h3>
                <p class="text-white/90 drop-shadow-sm bg-white/5 p-4 rounded-lg">{{ $pedido->notas_especiales }}</p>
            </div>
            @endif

            <!-- Botones de Acción -->
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('pedidos.index') }}" class="bg-white/20 backdrop-blur-sm border-2 border-white/30 hover:bg-white/30 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    ← Volver a Mis Pedidos
                </a>

                @if($pedido->estado == 'pendiente' && $pedido->estado_pago == 'pendiente')
                <form action="{{ route('pedidos.cancelar', $pedido->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de cancelar este pedido?')">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        ❌ Cancelar Pedido
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection