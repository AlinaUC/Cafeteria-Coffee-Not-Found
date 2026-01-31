@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-white drop-shadow-lg">
        📦 Gestión de Inventario
    </h2>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Alertas de Stock Bajo -->
    @if($alertasBajoStock->count() > 0)
    <div class="bg-red-500/20 backdrop-blur-lg border border-red-400/30 rounded-2xl shadow-xl px-6 py-5 mb-8">
        <div class="flex items-start">
            <div class="flex-shrink-0 mr-4">
                <div class="w-12 h-12 bg-red-500/30 backdrop-blur-sm rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-100" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-red-100 mb-2 drop-shadow-md">⚠️ Alerta de Stock Bajo</h3>
                <p class="text-red-100/90 mb-3 drop-shadow-sm">{{ $alertasBajoStock->count() }} producto(s) necesitan reabastecimiento</p>
                <ul class="space-y-2">
                    @foreach($alertasBajoStock as $inventario)
                    <li class="text-sm text-red-100/90 drop-shadow-sm bg-red-500/10 backdrop-blur-sm rounded-lg p-2">
                        <strong>{{ $inventario->producto->nombre }}</strong> - Stock: {{ $inventario->cantidad_actual }} (Mínimo: {{ $inventario->cantidad_minima }})
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white/20 backdrop-blur-xl border border-white/30 rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-gradient-to-r from-amber-600/20 to-amber-700/20 backdrop-blur-sm border-b border-white/20 p-6 flex justify-between items-center">
            <h3 class="text-xl font-bold text-white drop-shadow-lg">Inventario de Productos</h3>
            <a href="{{ route('admin.inventario.movimientos') }}" 
               class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2.5 rounded-lg font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                📋 Ver Movimientos
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-white/10 backdrop-blur-sm">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white/90 uppercase tracking-wider drop-shadow-sm">Producto</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white/90 uppercase tracking-wider drop-shadow-sm">Categoría</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white/90 uppercase tracking-wider drop-shadow-sm">Cantidad Actual</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white/90 uppercase tracking-wider drop-shadow-sm">Cantidad Mínima</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white/90 uppercase tracking-wider drop-shadow-sm">Ubicación</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white/90 uppercase tracking-wider drop-shadow-sm">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white/90 uppercase tracking-wider drop-shadow-sm">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @foreach($inventarios as $inventario)
                    <tr class="{{ $inventario->necesitaReabastecimiento() ? 'bg-red-500/10' : 'hover:bg-white/5' }} transition">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-white drop-shadow-sm">{{ $inventario->producto->nombre }}</div>
                            <div class="text-xs text-white/70 drop-shadow-sm">Precio: Bs. {{ number_format($inventario->producto->precio, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-white/90 drop-shadow-sm">
                            {{ $inventario->producto->categoria->nombre }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-2xl font-bold {{ $inventario->necesitaReabastecimiento() ? 'text-red-300' : 'text-green-300' }} drop-shadow-lg">
                                {{ $inventario->cantidad_actual }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-white/90 drop-shadow-sm">
                            {{ $inventario->cantidad_minima }}
                        </td>
                        <td class="px-6 py-4 text-sm text-white/90 drop-shadow-sm">
                            {{ $inventario->ubicacion ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($inventario->necesitaReabastecimiento())
                            <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-red-500/90 text-white shadow-lg">
                                ⚠️ Bajo Stock
                            </span>
                            @else
                            <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-green-500/90 text-white shadow-lg">
                                ✅ OK
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <button onclick="abrirModalInventario({{ $inventario->id }}, '{{ $inventario->producto->nombre }}', {{ $inventario->cantidad_actual }})" 
                                    class="text-amber-300 hover:text-amber-200 font-semibold drop-shadow-sm transition">
                                📝 Actualizar
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="bg-white/10 backdrop-blur-sm border-t border-white/20 p-6">
            {{ $inventarios->links() }}
        </div>
    </div>
</div>

<!-- Modal de Actualización de Inventario -->
<div id="modalInventario" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-6 border border-white/30 w-full max-w-md shadow-2xl rounded-2xl bg-white/20 backdrop-blur-xl">
        <div>
            <h3 class="text-2xl font-bold text-white mb-6 drop-shadow-lg">📦 Actualizar Inventario</h3>
            <div class="mb-4 bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                <p class="text-sm text-white/80 drop-shadow-sm">Producto: <span id="nombreProducto" class="font-bold text-white"></span></p>
                <p class="text-sm text-white/80 drop-shadow-sm mt-2">Stock Actual: <span id="stockActual" class="font-bold text-amber-300 text-lg"></span></p>
            </div>
            
            <form id="formInventario" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-bold text-white mb-2 drop-shadow-sm">Tipo de Movimiento</label>
                    <select name="tipo" id="tipoMovimiento" required class="w-full bg-white/90 border-white/40 text-gray-800 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                        <option value="">Seleccionar...</option>
                        <option value="entrada">➕ Entrada (Agregar)</option>
                        <option value="salida">➖ Salida (Restar)</option>
                        <option value="ajuste">🔄 Ajuste (Establecer cantidad exacta)</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-white mb-2 drop-shadow-sm">Cantidad</label>
                    <input type="number" name="cantidad" min="1" required class="w-full bg-white/90 border-white/40 text-gray-800 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-white mb-2 drop-shadow-sm">Motivo (Opcional)</label>
                    <textarea name="motivo" rows="3" class="w-full bg-white/90 border-white/40 text-gray-800 rounded-lg focus:ring-amber-500 focus:border-amber-500" placeholder="Ej: Compra semanal, producto dañado..."></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        ✅ Actualizar
                    </button>
                    <button type="button" onclick="cerrarModalInventario()" class="flex-1 bg-white/20 backdrop-blur-sm border-2 border-white/30 hover:bg-white/30 text-white font-bold py-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        ❌ Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function abrirModalInventario(id, nombre, stockActual) {
    document.getElementById('modalInventario').classList.remove('hidden');
    document.getElementById('nombreProducto').textContent = nombre;
    document.getElementById('stockActual').textContent = stockActual;
    document.getElementById('formInventario').action = '/admin/inventario/' + id + '/actualizar';
}

function cerrarModalInventario() {
    document.getElementById('modalInventario').classList.add('hidden');
    document.getElementById('formInventario').reset();
}

// Cerrar modal al hacer clic fuera
document.getElementById('modalInventario').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModalInventario();
    }
});
</script>
@endsection