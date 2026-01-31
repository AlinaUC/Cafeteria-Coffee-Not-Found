@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    Gestión de Productos
</h2>
@endsection

@section('content')
<div class="max-w-7xl mx-auto p-6 bg-white rounded-lg shadow">

    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold">Productos Registrados</h3>
        <a href="{{ route('admin.productos.crear') }}" class="bg-pastel-blue-600 hover:bg-pastel-blue-700 text-white px-4 py-2 rounded-lg">
            + Nuevo Producto
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($productos->isEmpty())
        <p class="text-gray-500">No hay productos registrados.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full table-auto border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2">Imagen</th>
                        <th class="border px-4 py-2">Nombre</th>
                        <th class="border px-4 py-2">Categoría</th>
                        <th class="border px-4 py-2">Precio</th>
                        <th class="border px-4 py-2">Disponible</th>
                        <th class="border px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $producto)
                    <tr class="text-center">
                        <td class="border px-4 py-2">
                            @if($producto->imagen)
                                <img src="{{ asset('storage/' . $producto->imagen) }}" class="w-20 h-20 object-cover mx-auto rounded">
                            @else
                                <span class="text-2xl">🍽️</span>
                            @endif
                        </td>
                        <td class="border px-4 py-2">{{ $producto->nombre }}</td>
                        <td class="border px-4 py-2">{{ $producto->categoria->nombre }}</td>
                        <td class="border px-4 py-2">Bs. {{ number_format($producto->precio, 2) }}</td>
                        <td class="border px-4 py-2">
                            @if($producto->cantidad_disponible > 0)
                                <span class="text-green-600">✓ {{ $producto->cantidad_disponible }}</span>
                            @else
                                <span class="text-red-600">✗ Agotado</span>
                            @endif
                        </td>
                        <td class="border px-4 py-2 flex justify-center gap-2">
                            <a href="{{ route('admin.productos.editar', $producto->id) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white px-2 py-1 rounded">Editar</a>
                            <form action="{{ route('admin.productos.eliminar', $producto->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este producto?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
