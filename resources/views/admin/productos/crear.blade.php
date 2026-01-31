@extends('layouts.app')

@section('header')
<h2 class="font-semibold text-xl text-white drop-shadow-lg">
    ➕ Crear Producto
</h2>
@endsection

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white/20 backdrop-blur-xl border border-white/30 rounded-2xl shadow-2xl p-8">
        <h3 class="text-2xl font-bold text-white mb-6 drop-shadow-lg flex items-center gap-2">
            <span>🍽️</span>
            Nuevo Producto
        </h3>

        <form action="{{ route('admin.productos.guardar') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-bold text-white mb-2 drop-shadow-sm">Nombre del Producto</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" 
                       class="w-full bg-white/90 border-white/40 text-gray-800 placeholder-gray-500 rounded-lg focus:ring-amber-500 focus:border-amber-500"
                       placeholder="Ej: Café Americano">
                @error('nombre') <span class="text-red-300 text-sm drop-shadow-sm block mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-white mb-2 drop-shadow-sm">Descripción</label>
                <textarea name="descripcion" rows="3" 
                          class="w-full bg-white/90 border-white/40 text-gray-800 placeholder-gray-500 rounded-lg focus:ring-amber-500 focus:border-amber-500"
                          placeholder="Describe el producto...">{{ old('descripcion') }}</textarea>
                @error('descripcion') <span class="text-red-300 text-sm drop-shadow-sm block mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="mb-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-white mb-2 drop-shadow-sm">Precio (Bs.)</label>
                    <input type="number" step="0.01" name="precio" value="{{ old('precio') }}" 
                           class="w-full bg-white/90 border-white/40 text-gray-800 placeholder-gray-500 rounded-lg focus:ring-amber-500 focus:border-amber-500"
                           placeholder="0.00">
                    @error('precio') <span class="text-red-300 text-sm drop-shadow-sm block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-white mb-2 drop-shadow-sm">Cantidad Disponible</label>
                    <input type="number" name="cantidad_disponible" value="{{ old('cantidad_disponible', 0) }}" 
                           class="w-full bg-white/90 border-white/40 text-gray-800 placeholder-gray-500 rounded-lg focus:ring-amber-500 focus:border-amber-500"
                           placeholder="0">
                    @error('cantidad_disponible') <span class="text-red-300 text-sm drop-shadow-sm block mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-white mb-2 drop-shadow-sm">Tiempo Preparación (min)</label>
                    <input type="number" name="tiempo_preparacion" value="{{ old('tiempo_preparacion', 15) }}" 
                           class="w-full bg-white/90 border-white/40 text-gray-800 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                </div>

                <div>
                    <label class="block text-sm font-bold text-white mb-2 drop-shadow-sm">Categoría</label>
                    <select name="categoria_id" class="w-full bg-white/90 border-white/40 text-gray-800 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-5 bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                <p class="text-sm font-bold text-white mb-3 drop-shadow-sm">Características Especiales</p>
                <div class="flex flex-wrap gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="es_vegetariano" value="1" {{ old('es_vegetariano') ? 'checked' : '' }}
                               class="rounded border-white/30 bg-white/20 text-amber-600 focus:ring-amber-500">
                        <span class="text-white drop-shadow-sm">🥗 Vegetariano</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="es_vegano" value="1" {{ old('es_vegano') ? 'checked' : '' }}
                               class="rounded border-white/30 bg-white/20 text-amber-600 focus:ring-amber-500">
                        <span class="text-white drop-shadow-sm">🌱 Vegano</span>
                    </label>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-white mb-2 drop-shadow-sm">Imagen del Producto</label>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                    <input type="file" name="imagen" accept="image/*" 
                           class="w-full bg-white/90 border-white/40 text-gray-800 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-amber-600 file:text-white hover:file:bg-amber-700 file:cursor-pointer" 
                           onchange="previewImage(event)">
                    <img id="preview" class="mt-4 w-48 h-48 object-cover rounded-lg shadow-lg hidden border-2 border-white/20" />
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    ✅ Crear Producto
                </button>
                <a href="{{ route('admin.productos') }}" class="flex-1 bg-white/20 backdrop-blur-sm border-2 border-white/30 hover:bg-white/30 text-white font-bold py-3 rounded-lg text-center shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    ❌ Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    preview.src = URL.createObjectURL(event.target.files[0]);
    preview.classList.remove('hidden');
}
</script>
@endsection