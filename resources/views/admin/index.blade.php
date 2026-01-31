@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Panel Administrativo
    </h2>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- =========================
         ESTADÍSTICAS PRINCIPALES
    ========================== -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-4">

        <!-- Total Pedidos -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Pedidos</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $estadisticas['total_pedidos'] }}</p>
                </div>
                <div class="bg-pastel-blue-100 rounded-full p-3 text-2xl">
                    🛒
                </div>
            </div>
        </div>

        <!-- Pedidos Pendientes -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pedidos Pendientes</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $estadisticas['pedidos_pendientes'] }}</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3 text-2xl">
                    ⏳
                </div>
            </div>
        </div>

        <!-- Total Estudiantes -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Estudiantes</p>
                    <p class="text-3xl font-bold text-green-600">{{ $estadisticas['total_estudiantes'] }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3 text-2xl">
                    🎓
                </div>
            </div>
        </div>

        <!-- Ingresos -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Ingresos Totales</p>
                    <p class="text-2xl font-bold text-pastel-blue-600">
                        Bs. {{ number_format($estadisticas['ingresos_totales'], 2) }}
                    </p>
                </div>
                <div class="bg-pastel-blue-100 rounded-full p-3 text-2xl">
                    💰
                </div>
            </div>
        </div>

        <!-- =========================
             CARD COMPROBANTES
        ========================== -->
        @if(isset($estadisticas['comprobantes_pendientes']) && $estadisticas['comprobantes_pendientes'] > 0)
        <div class="col-span-full mb-4">
            <a href="{{ route('admin.comprobantes') }}"
               class="block bg-orange-100 border border-orange-400 rounded-lg p-4 hover:bg-orange-200 transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="text-3xl mr-3">📄</span>
                        <div>
                            <p class="text-orange-900 font-semibold">
                                {{ $estadisticas['comprobantes_pendientes'] }} Comprobante(s) Pendiente(s) de Revisión
                            </p>
                            <p class="text-sm text-orange-700">Haz clic para revisar</p>
                        </div>
                    </div>
                    <span class="text-orange-600 text-2xl">➡️</span>
                </div>
            </a>
        </div>
        @endif
    </div>

    <!-- =========================
         RESUMEN + ACCESOS RÁPIDOS
    ========================== -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

        <!-- Resumen -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Resumen de Hoy</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span>Pedidos Hoy:</span>
                    <span class="font-bold">{{ $estadisticas['pedidos_hoy'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Ingresos Hoy:</span>
                    <span class="font-bold text-green-600">
                        Bs. {{ number_format($estadisticas['ingresos_hoy'], 2) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Accesos Rápidos -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Accesos Rápidos</h3>
            <div class="space-y-2">
                <a href="{{ route('admin.usuarios') }}" class="block bg-pastel-blue-50 p-3 rounded-lg">
                    👤 Gestionar Usuarios
                </a>

                <a href="{{ route('admin.inventario') }}" class="block bg-pastel-blue-50 p-3 rounded-lg">
                    📦 Gestionar Inventario
                </a>

                <a href="{{ route('cocina.index') }}" class="block bg-pastel-blue-50 p-3 rounded-lg">
                    🍳 Dashboard de Cocina
                </a>

                <a href="{{ route('admin.productos') }}" class="block bg-pastel-blue-50 p-3 rounded-lg">
                    🛍️ Gestionar Productos
                </a>

                <a href="{{ route('admin.comprobantes') }}" class="block bg-pastel-blue-50 p-3 rounded-lg">
                    📄 Revisar Comprobantes
                    @if(isset($estadisticas['comprobantes_pendientes']) && $estadisticas['comprobantes_pendientes'] > 0)
                        <span class="ml-2 bg-orange-500 text-white text-xs px-2 py-1 rounded-full">
                            {{ $estadisticas['comprobantes_pendientes'] }}
                        </span>
                    @endif
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
