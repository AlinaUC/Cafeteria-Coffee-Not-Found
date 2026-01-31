@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Detalle de Usuario
    </h2>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información del Usuario -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center mb-6">
                    @if($usuario->avatar)
                    <img src="{{ $usuario->avatar }}" alt="{{ $usuario->nombre }}" class="w-24 h-24 rounded-full mx-auto mb-4">
                    @else
                    <div class="w-24 h-24 rounded-full bg-pastel-blue-100 flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl text-pastel-blue-600 font-bold">{{ substr($usuario->nombre, 0, 1) }}</span>
                    </div>
                    @endif
                    <h3 class="text-xl font-bold text-gray-800">{{ $usuario->nombre }}</h3>
                    <p class="text-gray-600">{{ $usuario->email }}</p>
                </div>

                <dl class="space-y-3">
                    @if($usuario->codigo_estudiante)
                    <div>
                        <dt class="text-sm text-gray-600">Código Estudiante:</dt>
                        <dd class="font-semibold">{{ $usuario->codigo_estudiante }}</dd>
                    </div>
                    @endif
                    @if($usuario->telefono)
                    <div>
                        <dt class="text-sm text-gray-600">Teléfono:</dt>
                        <dd class="font-semibold">{{ $usuario->telefono }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm text-gray-600">Estado:</dt>
                        <dd>
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($usuario->estado == 'activo') bg-green-100 text-green-800
                                @elseif($usuario->estado == 'inactivo') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($usuario->estado) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-600">Registro:</dt>
                        <dd>{{ $usuario->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    @if($usuario->ultimo_acceso)
                    <div>
                        <dt class="text-sm text-gray-600">Último Acceso:</dt>
                        <dd>{{ $usuario->ultimo_acceso->diffForHumans() }}</dd>
                    </div>
                    @endif
                </dl>

                <!-- Gestión de Roles -->
                <div class="mt-6 pt-6 border-t">
                    <h4 class="font-semibold mb-3">Roles Actuales</h4>
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach($usuario->roles as $rol)
                        <div class="flex items-center gap-2 bg-pastel-blue-100 text-pastel-blue-800 px-3 py-1 rounded-full">
                            <span>{{ ucfirst($rol->nombre) }}</span>
                            <form action="{{ route('admin.usuario.rol.remover', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Remover este rol?')">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="rol_id" value="{{ $rol->id }}">
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>

                    <form action="{{ route('admin.usuario.rol.asignar', $usuario->id) }}" method="POST" class="flex gap-2">
                        @csrf
                        <select name="rol_id" class="flex-1 border-gray-300 rounded-lg">
                            <option value="">Seleccionar rol...</option>
                            @foreach($roles as $rol)
                            <option value="{{ $rol->id }}">{{ ucfirst($rol->nombre) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-pastel-blue-600 hover:bg-pastel-blue-700 text-white px-4 py-2 rounded-lg">
                            Asignar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Estadísticas y Pedidos -->
        <div class="lg:col-span-2">
            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Total Pedidos</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $estadisticasUsuario['total_pedidos'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Total Gastado</p>
                    <p class="text-2xl font-bold text-green-600">Bs. {{ number_format($estadisticasUsuario['total_gastado'], 2) }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-sm text-gray-600">Pedido Promedio</p>
                    <p class="text-2xl font-bold text-pastel-blue-600">Bs. {{ number_format($estadisticasUsuario['pedido_promedio'], 2) }}</p>
                </div>
            </div>

            <!-- Historial de Pedidos -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold">Historial de Pedidos</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Número</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($usuario->pedidos->take(10) as $pedido)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $pedido->numero_pedido }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $pedido->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    Bs. {{ number_format($pedido->monto_total, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($pedido->estado == 'pendiente') bg-yellow-100 text-yellow-800
                                        @elseif($pedido->estado == 'preparando') bg-blue-100 text-blue-800
                                        @elseif($pedido->estado == 'listo') bg-green-100 text-green-800
                                        @elseif($pedido->estado == 'completado') bg-gray-100 text-gray-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($pedido->estado) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    No hay pedidos registrados
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.usuarios') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg inline-block">
            Volver a Lista de Usuarios
        </a>
    </div>
</div>
@endsection