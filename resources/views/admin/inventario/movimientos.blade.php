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
                    📦 Movimientos de Inventario
                </h2>
            </div>

            <div class="mb-4">
                <a href="{{ route('admin.inventario') }}" class="bg-white/20 backdrop-blur-md border border-white/30 hover:bg-white/30 text-white px-4 py-2 rounded-lg inline-block transition">
                    ← Volver al Inventario
                </a>
            </div>

            <div class="bg-white/20 backdrop-blur-lg border border-white/30 rounded-2xl shadow-xl">
                <div class="p-6 border-b border-white/20">
                    <h3 class="text-lg font-semibold text-white drop-shadow-md">Historial de Movimientos</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-white/10">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/90 uppercase">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/90 uppercase">Producto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/90 uppercase">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/90 uppercase">Cantidad</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/90 uppercase">Anterior</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/90 uppercase">Nueva</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/90 uppercase">Motivo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/90 uppercase">Usuario</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @foreach($movimientos as $movimiento)
                            <tr class="hover:bg-white/10 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white/80">
                                    {{ $movimiento->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                                    {{ $movimiento->producto->nombre }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($movimiento->tipo == 'entrada') bg-green-500/30 text-green-200 border border-green-400/30
                                        @elseif($movimiento->tipo == 'salida') bg-red-500/30 text-red-200 border border-red-400/30
                                        @else bg-blue-500/30 text-blue-200 border border-blue-400/30
                                        @endif">
                                        {{ ucfirst($movimiento->tipo) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold
                                    @if($movimiento->tipo == 'entrada') text-green-300
                                    @elseif($movimiento->tipo == 'salida') text-red-300
                                    @else text-blue-300
                                    @endif">
                                    @if($movimiento->tipo == 'entrada') + @elseif($movimiento->tipo == 'salida') - @endif
                                    {{ $movimiento->cantidad }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white/80">
                                    {{ $movimiento->cantidad_anterior }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-white">
                                    {{ $movimiento->cantidad_nueva }}
                                </td>
                                <td class="px-6 py-4 text-sm text-white/80">
                                    {{ $movimiento->motivo ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white/80">
                                    {{ $movimiento->usuario->nombre }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-white/20">
                    {{ $movimientos->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
