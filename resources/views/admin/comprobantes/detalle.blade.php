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
                    📄 Detalle del Comprobante
                </h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Comprobante -->
                <div class="bg-white/20 backdrop-blur-lg border border-white/30 rounded-2xl shadow-xl p-6">
                    <h3 class="text-lg font-semibold mb-4 text-white drop-shadow-md">Comprobante de Pago</h3>
                    
                    @if($pedido->comprobante_pago)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $pedido->comprobante_pago) }}" 
                             alt="Comprobante" 
                             class="w-full rounded-lg border-2 border-white/30"
                             onerror="this.parentElement.innerHTML='<div class=\'bg-white/10 p-8 rounded text-center\'><p class=\'text-white/80\'>No se puede mostrar la imagen</p><a href=\'{{ route('pedidos.comprobante.ver', $pedido->id) }}\' target=\'_blank\' class=\'text-blue-300 hover:underline\'>Abrir en nueva pestaña</a></div>'">
                    </div>
                    <a href="{{ route('pedidos.comprobante.ver', $pedido->id) }}" 
                       target="_blank"
                       class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded-lg transition">
                        Abrir en Tamaño Completo
                    </a>
                    @else
                    <div class="bg-white/10 p-8 rounded text-center text-white/80">
                        No hay comprobante disponible
                    </div>
                    @endif

                    <div class="mt-6 pt-6 border-t border-white/20">
                        <p class="text-sm text-white/70 mb-1">Subido:</p>
                        <p class="font-semibold text-white">{{ $pedido->comprobante_subido_en ? $pedido->comprobante_subido_en->format('d/m/Y H:i') : 'N/A' }}</p>
                    </div>
                </div>

                <!-- Información del Pedido -->
                <div class="space-y-6">
                    <!-- Datos del Pedido -->
                    <div class="bg-white/20 backdrop-blur-lg border border-white/30 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold mb-4 text-white drop-shadow-md">Información del Pedido</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm text-white/70">Número de Pedido:</dt>
                                <dd class="font-semibold text-lg text-white">#{{ $pedido->numero_pedido }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-white/70">Cliente:</dt>
                                <dd class="font-semibold text-white">{{ $pedido->usuario->nombre }}</dd>
                                <dd class="text-sm text-white/60">{{ $pedido->usuario->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-white/70">Monto Total:</dt>
                                <dd class="font-bold text-2xl text-amber-300">Bs. {{ number_format($pedido->monto_total, 2) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-white/70">Estado de Pago:</dt>
                                <dd>
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                                        @if($pedido->estado_pago == 'pagado') bg-green-500/30 text-green-200 border border-green-400/30
                                        @elseif($pedido->estado_pago == 'pendiente') bg-yellow-500/30 text-yellow-200 border border-yellow-400/30
                                        @else bg-red-500/30 text-red-200 border border-red-400/30
                                        @endif">
                                        {{ ucfirst($pedido->estado_pago) }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm text-white/70">Fecha del Pedido:</dt>
                                <dd class="text-white">{{ $pedido->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Productos -->
                    <div class="bg-white/20 backdrop-blur-lg border border-white/30 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold mb-4 text-white drop-shadow-md">Productos del Pedido</h3>
                        <div class="space-y-2">
                            @foreach($pedido->items as $item)
                            <div class="flex justify-between text-sm bg-white/10 p-3 rounded border border-white/20">
                                <span class="text-white">{{ $item->cantidad }}x {{ $item->producto->nombre }}</span>
                                <span class="font-semibold text-white">Bs. {{ number_format($item->precio_total, 2) }}</span>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-4 pt-4 border-t border-white/20 flex justify-between font-bold">
                            <span class="text-white">Total:</span>
                            <span class="text-amber-300">Bs. {{ number_format($pedido->monto_total, 2) }}</span>
                        </div>
                    </div>

                    <!-- Acciones -->
                    @if($pedido->estado_pago == 'pendiente' && $pedido->comprobante_pago)
                    <div class="bg-white/20 backdrop-blur-lg border border-white/30 rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold mb-4 text-white drop-shadow-md">Acciones</h3>
                        <div class="space-y-3">
                            <form action="{{ route('admin.comprobantes.aprobar', $pedido->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('¿Aprobar este comprobante y marcar el pedido como pagado?')"
                                        class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition">
                                    ✓ Aprobar Comprobante
                                </button>
                            </form>

                            <button onclick="abrirModalRechazo({{ $pedido->id }}, '{{ $pedido->numero_pedido }}')"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition">
                                ✗ Rechazar Comprobante
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.comprobantes') }}" class="bg-white/20 backdrop-blur-md border border-white/30 hover:bg-white/30 text-white px-6 py-2 rounded-lg inline-block transition">
                    ← Volver a Comprobantes
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Rechazo -->
<div id="modalRechazo" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border border-white/30 w-96 shadow-lg rounded-2xl bg-white/95 backdrop-blur-lg">
        <div class="mt-3">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Rechazar Comprobante</h3>
            <p class="text-sm text-gray-600 mb-4">Pedido: <span id="pedidoNumero" class="font-semibold"></span></p>
            
            <form id="formRechazo" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Motivo del Rechazo</label>
                    <textarea name="motivo" 
                              rows="4" 
                              required
                              class="w-full border-gray-300 rounded-lg"
                              placeholder="Ej: El comprobante no es legible, el monto no coincide, etc."></textarea>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg transition">
                        Rechazar
                    </button>
                    <button type="button" onclick="cerrarModalRechazo()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 rounded-lg transition">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function abrirModalRechazo(pedidoId, numeroPedido) {
    document.getElementById('modalRechazo').classList.remove('hidden');
    document.getElementById('pedidoNumero').textContent = '#' + numeroPedido;
    document.getElementById('formRechazo').action = '/admin/comprobantes/' + pedidoId + '/rechazar';
}

function cerrarModalRechazo() {
    document.getElementById('modalRechazo').classList.add('hidden');
    document.getElementById('formRechazo').reset();
}
</script>
@endsection
