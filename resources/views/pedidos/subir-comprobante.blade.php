@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-white drop-shadow-lg">
        📤 Subir Comprobante de Pago
    </h2>
@endsection

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white/20 backdrop-blur-xl border border-white/30 rounded-3xl shadow-2xl p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-amber-600/30 backdrop-blur-sm border-2 border-amber-400/40 rounded-full mb-4">
                <svg class="w-10 h-10 text-amber-100" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
            </div>
            <h3 class="text-3xl font-bold text-white mb-3 drop-shadow-lg">Sube tu Comprobante</h3>
            <p class="text-white/90 drop-shadow-md">Para verificar tu pago, necesitamos el comprobante de la transacción</p>
        </div>

        <!-- Información del pedido -->
        <div class="bg-gradient-to-r from-amber-600/20 to-amber-700/20 backdrop-blur-sm border border-amber-400/30 rounded-xl p-6 mb-8">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-white/70 mb-1 drop-shadow-sm">Número de Pedido</p>
                    <p class="font-bold text-xl text-white drop-shadow-md">{{ $pedido->numero_pedido }}</p>
                </div>
                <div>
                    <p class="text-sm text-white/70 mb-1 drop-shadow-sm">Monto Total</p>
                    <p class="font-bold text-xl text-amber-300 drop-shadow-lg">Bs. {{ number_format($pedido->monto_total, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Estado actual del comprobante -->
        @if($pedido->comprobante_pago)
        <div class="bg-green-500/20 backdrop-blur-sm border border-green-400/30 rounded-xl p-5 mb-8">
            <div class="flex items-start">
                <div class="flex-shrink-0 mr-4">
                    <div class="w-12 h-12 bg-green-500/30 backdrop-blur-sm rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-100" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-green-100 mb-1 drop-shadow-md">✅ Comprobante ya subido</p>
                    <p class="text-sm text-green-100/90 mb-3 drop-shadow-sm">
                        Subido el {{ $pedido->comprobante_subido_en->format('d/m/Y H:i') }}
                    </p>
                    <a href="{{ route('pedidos.comprobante.ver', $pedido->id) }}" target="_blank" 
                       class="inline-flex items-center gap-2 text-sm text-green-200 hover:text-green-100 underline drop-shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Ver comprobante actual
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Formulario de subida -->
        <form action="{{ route('pedidos.comprobante.subir', $pedido->id) }}" method="POST" enctype="multipart/form-data" id="formComprobante">
            @csrf

            <div class="mb-8">
                <label class="block text-sm font-bold text-white mb-4 drop-shadow-md">
                    📎 Selecciona tu comprobante
                    @if($pedido->comprobante_pago)
                    <span class="text-white/70 font-normal">(puedes reemplazar el anterior)</span>
                    @endif
                </label>

                <!-- Área de drag & drop -->
                <div class="border-3 border-dashed border-white/40 rounded-2xl p-10 text-center hover:border-amber-400 hover:bg-white/10 transition-all duration-300 cursor-pointer backdrop-blur-sm" 
                     id="dropZone"
                     onclick="document.getElementById('comprobante').click()">
                    <svg class="w-16 h-16 text-white/60 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p class="text-white/90 mb-2 drop-shadow-md">
                        <span class="font-bold text-amber-300">Haz clic para seleccionar</span>
                        <br>
                        <span class="text-sm">o arrastra y suelta aquí</span>
                    </p>
                    <p class="text-xs text-white/60 drop-shadow-sm">JPG, PNG o PDF (Máx. 5MB)</p>
                </div>

                <input type="file" 
                       name="comprobante" 
                       id="comprobante" 
                       accept="image/jpeg,image/png,image/jpg,application/pdf"
                       class="hidden"
                       required>

                @error('comprobante')
                    <div class="mt-3 bg-red-500/20 backdrop-blur-sm border border-red-400/30 rounded-lg p-3">
                        <p class="text-red-100 text-sm drop-shadow-sm">{{ $message }}</p>
                    </div>
                @enderror

                <!-- Preview del archivo seleccionado -->
                <div id="preview" class="hidden mt-5 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl p-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center flex-1">
                            <div class="flex-shrink-0 mr-4">
                                <div class="w-14 h-14 bg-amber-600/30 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                    <svg class="w-7 h-7 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-white drop-shadow-md" id="fileName"></p>
                                <p class="text-sm text-white/70 drop-shadow-sm" id="fileSize"></p>
                            </div>
                        </div>
                        <button type="button" 
                                onclick="limpiarArchivo()" 
                                class="ml-4 text-red-400 hover:text-red-300 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <img id="imagePreview" class="mt-5 max-h-64 mx-auto rounded-lg shadow-lg hidden border-2 border-white/20">
                </div>
            </div>

            <!-- Instrucciones -->
            <div class="bg-blue-500/20 backdrop-blur-sm border border-blue-400/30 rounded-xl p-5 mb-8">
                <h4 class="font-bold text-blue-100 mb-3 drop-shadow-md flex items-center gap-2">
                    <span>💡</span>
                    Instrucciones
                </h4>
                <ul class="text-sm text-blue-100/90 space-y-2 drop-shadow-sm">
                    <li class="flex items-start gap-2">
                        <span class="text-blue-300 mt-0.5">•</span>
                        <span>Sube una captura de pantalla de tu comprobante bancario</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-300 mt-0.5">•</span>
                        <span>Asegúrate que se vean claramente: monto, fecha y número de transacción</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-300 mt-0.5">•</span>
                        <span>El archivo debe ser menor a 5MB</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-300 mt-0.5">•</span>
                        <span>Formatos aceptados: JPG, PNG o PDF</span>
                    </li>
                </ul>
            </div>

            <!-- Botones de acción -->
            <div class="flex flex-col sm:flex-row gap-4 mb-8">
                <button type="submit" 
                        class="flex-1 bg-amber-600 hover:bg-amber-700 text-white py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 disabled:bg-gray-500 disabled:cursor-not-allowed disabled:transform-none"
                        id="btnSubir"
                        disabled>
                    📤 Subir Comprobante
                </button>
                @if($pedido->comprobante_pago)
                <a href="{{ route('pedidos.show', $pedido->id) }}" 
                   class="flex-1 bg-white/20 backdrop-blur-sm border-2 border-white/30 hover:bg-white/30 text-white py-4 rounded-xl font-bold text-center shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    Continuar sin cambiar
                </a>
                @else
                <a href="{{ route('pedidos.seleccionar.pago') }}" 
                   class="flex-1 bg-white/20 backdrop-blur-sm border-2 border-white/30 hover:bg-white/30 text-white py-4 rounded-xl font-bold text-center shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    Cambiar Método de Pago
                </a>
                @endif
            </div>

            <!-- Resumen del pedido -->
            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl p-5">
                <h4 class="font-bold text-white mb-4 drop-shadow-md flex items-center gap-2">
                    <span>🧾</span>
                    Resumen del Pedido
                </h4>
                <div class="space-y-2">
                    @foreach($pedido->items as $item)
                    <div class="flex justify-between text-sm">
                        <span class="text-white/90 drop-shadow-sm">{{ $item->cantidad }}x {{ $item->producto->nombre }}</span>
                        <span class="font-semibold text-amber-300 drop-shadow-sm">Bs. {{ number_format($item->precio_total, 2) }}</span>
                    </div>
                    @endforeach
                    <div class="flex justify-between font-bold text-lg pt-3 border-t border-white/20">
                        <span class="text-white drop-shadow-md">Total:</span>
                        <span class="text-amber-300 drop-shadow-lg">Bs. {{ number_format($pedido->monto_total, 2) }}</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
const inputArchivo = document.getElementById('comprobante');
const dropZone = document.getElementById('dropZone');
const preview = document.getElementById('preview');
const btnSubir = document.getElementById('btnSubir');

inputArchivo.addEventListener('change', manejarArchivo);

// Drag & Drop
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('border-amber-400', 'bg-white/15');
});

dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('border-amber-400', 'bg-white/15');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-amber-400', 'bg-white/15');
    
    const files = e.dataTransfer.files;
    if (files.length) {
        inputArchivo.files = files;
        manejarArchivo();
    }
});

function manejarArchivo() {
    const file = inputArchivo.files[0];
    if (!file) return;

    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileSize').textContent = formatearTamano(file.size);
    preview.classList.remove('hidden');
    btnSubir.disabled = false;

    // Preview de imagen
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const img = document.getElementById('imagePreview');
            img.src = e.target.result;
            img.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('imagePreview').classList.add('hidden');
    }
}

function limpiarArchivo() {
    inputArchivo.value = '';
    preview.classList.add('hidden');
    btnSubir.disabled = true;
}

function formatearTamano(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Validación antes de enviar
document.getElementById('formComprobante').addEventListener('submit', (e) => {
    const file = inputArchivo.files[0];
    if (!file) {
        e.preventDefault();
        alert('Por favor, selecciona un archivo');
        return;
    }

    if (file.size > 5 * 1024 * 1024) {
        e.preventDefault();
        alert('El archivo no debe superar los 5MB');
        return;
    }

    btnSubir.disabled = true;
    btnSubir.textContent = '⏳ Subiendo...';
});
</script>
@endsection