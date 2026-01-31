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
                    📄 Gestión de Comprobantes
                </h2>
            </div>

            <!-- Alerta de pendientes -->
            @if($comprobantesPendientes->count() > 0)
            <div class="bg-orange-500/30 backdrop-blur-md border border-orange-400/40 text-white px-4 py-3 rounded-xl mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span><strong>{{ $comprobantesPendientes->count() }}</strong> comprobante(s) pendiente(s) de revisión</span>
                </div>
            </div>
            @endif

            <!-- Comprobantes Pendientes -->
            <div class="bg-white/20 backdrop-blur-lg border border-white/30 rounded-2xl shadow-xl mb-8">
                <div class="p-6 border-b border-white/20">
                    <h3 class="text-lg font-semibold text-white drop-shadow-md">Comprobantes Pendientes de Aprobación</h3>
                </div>

                @if($comprobantesPendientes->isEmpty())
                <div class="p-8 text-center text-white/80">
                    No hay comprobantes pendientes de revisión
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-white/10">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/90 uppercase">Pedido</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/90 uppercase">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/90 uppercase">Monto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/90 uppercase">Subido</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/90 uppercase">Comprobante</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/90 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @foreach($comprobantesPendientes as $pedido)
                            <tr class="hover:bg-white/10 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-white">#{{ $pedido->numero_pedido }}</div>
                                    <div class="text-xs text-white/60">{{ $pedido->created_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-white">{{ $pedido->usuario->nombre }}</div>
                                    <div class="text-xs text-white/60">{{ $pedido->usuario->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-amber-300">Bs. {{ number_format($pedido->monto_total, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white/80">
                                    {{ $pedido->comprobante_subido_en->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('pedidos.comprobante.ver', $pedido->id) }}" 
                                       target="_blank"
                                       class="inline-flex items-center px-3 py-1 bg-blue-500/30 text-blue-200 border border-blue-400/30 rounded-lg text-sm hover:bg-blue-500/40 transition">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Ver
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex gap-2">
                                        <form action="{{ route('admin.comprobantes.aprobar', $pedido->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="return confirm('¿Aprobar este comprobante?')"
                                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded transition">
                                                Aprobar
                                            </button>
                                        </form>

                                        <button onclick="abrirModalRechazo({{ $pedido->id }}, '{{ $pedido->numero_pedido }}')"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded transition">
                                            Rechazar
                                        </button>

                                        <a href="{{ route('admin.comprobantes.detalle', $pedido->id) }}" 
                                           class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded transition">
                                            Detalle
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-white/20">
                    {{ $comprobantesPendientes->links() }}
                </div>
                @endif
            </div>

            <!-- Comprobantes Aprobados Recientemente -->
            <div class="bg-white/20 backdrop-blur-lg border border-white/30 rounded-2xl shadow-xl">
                <div class="p-6 border-b border-white/20">
                    <h3 class="text-lg font-semibold text-white drop-shadow-md">Últimos Comprobantes Aprobados</h3>
                </div>

                @if($comprobantesAprobados->isEmpty())
                <div class="p-8 text-center text-white/80">
                    No hay comprobantes aprobados aún
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-white/10">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/90 uppercase">Pedido</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/90 uppercase">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/90 uppercase">Monto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/90 uppercase">Aprobado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/90 uppercase">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @foreach($comprobantesAprobados as $pedido)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                                    #{{ $pedido->numero_pedido }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white/80">
                                    {{ $pedido->usuario->nombre }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-300">
                                    Bs. {{ number_format($pedido->monto_total, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white/80">
                                    {{ $pedido->updated_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-500/30 text-green-200 border border-green-400/30">
                                        Aprobado
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
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
                    <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg">
                        Rechazar
                    </button>
                    <button type="button" onclick="cerrarModalRechazo()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 rounded-lg">
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
