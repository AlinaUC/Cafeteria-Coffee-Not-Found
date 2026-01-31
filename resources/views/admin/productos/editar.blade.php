@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    Editar Producto
</h2>
@endsection

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow">
    <!-- FORMULARIO ACTUALIZAR -->
    <form action="{{ route('admin.productos.actualizar', $producto->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700">Nombre</label>
            <input type="text" name="nombre" value="{{ old('nombre', $producto->nombre) }}" class="w-full border px-3 py-2 rounded">
            @error('nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Descripción</label>
            <textarea name="descripcion" class="w-full border px-3 py-2 rounded">{{ old('descripcion', $producto->descripcion) }}</textarea>
            @error('descripcion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4 grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700">Precio</label>
                <input type="number" step="0.01" name="precio" value="{{ old('precio', $producto->precio) }}" class="w-full border px-3 py-2 rounded">
            </div>

            <div>
                <label class="block text-gray-700">Cantidad Disponible</label>
                <input type="number" name="cantidad_disponible" value="{{ old('cantidad_disponible', $producto->cantidad_disponible) }}" class="w-full border px-3 py-2 rounded">
            </div>
        </div>

        <div class="mb-4 grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700">Tiempo Preparación (min)</label>
                <input type="number" name="tiempo_preparacion" value="{{ old('tiempo_preparacion', $producto->tiempo_preparacion) }}" class="w-full border px-3 py-2 rounded">
            </div>

            <div>
                <label class="block text-gray-700">Categoría</label>
                <select name="categoria_id" class="w-full border px-3 py-2 rounded">
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-4 flex gap-4">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="es_vegetariano" value="1" {{ old('es_vegetariano', $producto->es_vegetariano) ? 'checked' : '' }}>
                Vegetariano
            </label>

            <label class="flex items-center gap-2">
                <input type="checkbox" name="es_vegano" value="1" {{ old('es_vegano', $producto->es_vegano) ? 'checked' : '' }}>
                Vegano
            </label>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Imagen</label>
            <input type="file" name="imagen" accept="image/*" class="w-full border px-3 py-2 rounded" onchange="previewImage(event)">
            @if($producto->imagen)
                <img id="preview" src="{{ asset('storage/' . $producto->imagen) }}" class="mt-2 w-48 h-48 object-cover">
            @else
                <img id="preview" class="mt-2 w-48 h-48 object-cover hidden">
            @endif
        </div>

        <div class="flex justify-end gap-2">
            <button type="submit" class="bg-pastel-blue-600 hover:bg-pastel-blue-700 text-white px-4 py-2 rounded-lg">
                Actualizar Producto
            </button>
        </div>
    </form>

    <!-- FORMULARIO ELIMINAR SEPARADO -->
    <form action="{{ route('admin.productos.eliminar', $producto->id) }}" method="POST" class="mt-4" onsubmit="return confirm('¿Seguro que deseas eliminar este producto?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg w-full">
            Eliminar Producto
        </button>
    </form>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    preview.src = URL.createObjectURL(event.target.files[0]);
    preview.classList.remove('hidden');
}
</script>
@endsection
