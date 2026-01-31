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
                    👤 Mi Perfil
                </h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Sidebar de perfil -->
                <div class="lg:col-span-1">
                    <div class="bg-white/20 backdrop-blur-lg border border-white/30 rounded-2xl shadow-xl p-6">
                        <!-- Avatar -->
                        <div class="flex flex-col items-center mb-6">
                            <div class="relative mb-4">
                                @if($usuario->avatar)
                                    @if(filter_var($usuario->avatar, FILTER_VALIDATE_URL))
                                        <img src="{{ $usuario->avatar }}" 
                                             alt="Avatar" 
                                             class="w-32 h-32 rounded-full object-cover border-4 border-white/30">
                                    @else
                                        <img src="{{ asset('storage/' . $usuario->avatar) }}" 
                                             alt="Avatar" 
                                             class="w-32 h-32 rounded-full object-cover border-4 border-white/30">
                                    @endif
                                @else
                                    <div class="w-32 h-32 rounded-full bg-amber-500 text-white flex items-center justify-center text-4xl font-bold border-4 border-white/30">
                                        {{ strtoupper(substr($usuario->nombre, 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <h3 class="text-xl font-semibold text-white text-center drop-shadow-md">{{ $usuario->nombre }}</h3>
                            <p class="text-sm text-white/70 text-center mt-1">{{ $usuario->email }}</p>

                            @if($usuario->codigo_estudiante)
                                <span class="mt-3 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-500/30 text-blue-200 border border-blue-400/30">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                    </svg>
                                    {{ $usuario->codigo_estudiante }}
                                </span>
                            @endif
                        </div>

                        <!-- Botones de avatar -->
                        <div class="space-y-2">
                            <button type="button" 
                                    onclick="document.getElementById('avatarModal').classList.remove('hidden')"
                                    class="w-full px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium">
                                <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Cambiar foto
                            </button>
                            
                            @if($usuario->avatar)
                                <form action="{{ route('profile.avatar.delete') }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar tu foto de perfil?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-4 py-2 bg-red-500/30 border border-red-400/30 text-red-200 rounded-lg hover:bg-red-500/40 transition text-sm font-medium">
                                        <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Eliminar foto
                                    </button>
                                </form>
                            @endif
                        </div>

                        <!-- Información adicional -->
                        <div class="mt-6 pt-6 border-t border-white/20">
                            <h4 class="text-sm font-semibold text-white/90 mb-3">Información</h4>
                            <div class="space-y-3 text-sm">
                                @foreach($usuario->roles as $rol)
                                    <div class="flex items-center text-white/80">
                                        <svg class="w-4 h-4 mr-2 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                        <strong class="mr-1">Rol:</strong> {{ ucfirst($rol->nombre) }}
                                    </div>
                                @endforeach
                                <div class="flex items-center text-white/80">
                                    <svg class="w-4 h-4 mr-2 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <strong>Último acceso:</strong><br>
                                        <span class="text-xs">{{ $usuario->ultimo_acceso ? $usuario->ultimo_acceso->diffForHumans() : 'Nunca' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contenido principal -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Mensajes de éxito -->
                    @if(session('success'))
                        <div class="bg-green-500/30 backdrop-blur-md border border-green-400/40 p-4 rounded-xl">
                            <div class="flex">
                                <svg class="w-5 h-5 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="ml-3 text-sm text-green-200">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Formulario de información personal -->
                    <div class="bg-white/20 backdrop-blur-lg border border-white/30 rounded-2xl shadow-xl">
                        <div class="px-6 py-4 border-b border-white/20">
                            <h3 class="text-lg font-semibold text-white drop-shadow-md">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Información Personal
                            </h3>
                        </div>
                        <div class="p-6">
                            <form action="{{ route('profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="space-y-4">
                                    <div>
                                        <label for="nombre" class="block text-sm font-medium text-white/90 mb-1">
                                            Nombre completo
                                        </label>
                                        <input type="text" 
                                               id="nombre" 
                                               name="nombre" 
                                               value="{{ old('nombre', $usuario->nombre) }}" 
                                               class="w-full px-3 py-2 border border-white/30 bg-white/10 text-white placeholder-white/50 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 @error('nombre') border-red-500 @enderror"
                                               required>
                                        @error('nombre')
                                            <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-white/90 mb-1">
                                            Email
                                        </label>
                                        <input type="email" 
                                               id="email" 
                                               value="{{ $usuario->email }}" 
                                               class="w-full px-3 py-2 border border-white/30 bg-white/5 text-white/60 rounded-lg cursor-not-allowed"
                                               disabled>
                                        <p class="mt-1 text-xs text-white/60">El email no se puede cambiar</p>
                                    </div>

                                    <div>
                                        <label for="telefono" class="block text-sm font-medium text-white/90 mb-1">
                                            Teléfono
                                        </label>
                                        <input type="text" 
                                               id="telefono" 
                                               name="telefono" 
                                               value="{{ old('telefono', $usuario->telefono) }}"
                                               placeholder="Opcional"
                                               class="w-full px-3 py-2 border border-white/30 bg-white/10 text-white placeholder-white/50 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 @error('telefono') border-red-500 @enderror">
                                        @error('telefono')
                                            <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="codigo_estudiante" class="block text-sm font-medium text-white/90 mb-1">
                                            Código de estudiante
                                        </label>
                                        <input type="text" 
                                               id="codigo_estudiante" 
                                               name="codigo_estudiante" 
                                               value="{{ old('codigo_estudiante', $usuario->codigo_estudiante) }}"
                                               placeholder="Opcional"
                                               class="w-full px-3 py-2 border border-white/30 bg-white/10 text-white placeholder-white/50 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 @error('codigo_estudiante') border-red-500 @enderror">
                                        @error('codigo_estudiante')
                                            <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <button type="submit" class="w-full px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition font-medium">
                                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                        </svg>
                                        Guardar cambios
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Formulario de cambio de contraseña -->
                    <div class="bg-white/20 backdrop-blur-lg border border-white/30 rounded-2xl shadow-xl">
                        <div class="px-6 py-4 border-b border-white/20">
                            <h3 class="text-lg font-semibold text-white drop-shadow-md">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Cambiar Contraseña
                            </h3>
                        </div>
                        <div class="p-6">
                            <form action="{{ route('profile.password.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="space-y-4">
                                    <div>
                                        <label for="current_password" class="block text-sm font-medium text-white/90 mb-1">
                                            Contraseña actual
                                        </label>
                                        <input type="password" 
                                               id="current_password" 
                                               name="current_password" 
                                               class="w-full px-3 py-2 border border-white/30 bg-white/10 text-white placeholder-white/50 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 @error('current_password') border-red-500 @enderror"
                                               required>
                                        @error('current_password')
                                            <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password" class="block text-sm font-medium text-white/90 mb-1">
                                            Nueva contraseña
                                        </label>
                                        <input type="password" 
                                               id="password" 
                                               name="password" 
                                               class="w-full px-3 py-2 border border-white/30 bg-white/10 text-white placeholder-white/50 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 @error('password') border-red-500 @enderror"
                                               required>
                                        <p class="mt-1 text-xs text-white/60">Mínimo 8 caracteres</p>
                                        @error('password')
                                            <p class="mt-1 text-sm text-red-300">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-white/90 mb-1">
                                            Confirmar nueva contraseña
                                        </label>
                                        <input type="password" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               class="w-full px-3 py-2 border border-white/30 bg-white/10 text-white placeholder-white/50 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
                                               required>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <button type="submit" class="w-full px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition font-medium">
                                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                        </svg>
                                        Actualizar contraseña
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para cambiar avatar -->
<div id="avatarModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border border-white/30 w-96 shadow-lg rounded-2xl bg-white/95 backdrop-blur-lg">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Cambiar foto de perfil</h3>
            <form action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">
                        Selecciona una imagen
                    </label>
                    <input type="file" 
                           id="avatar" 
                           name="avatar" 
                           accept="image/*" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
                           onchange="previewImage(event)"
                           required>
                    <p class="mt-1 text-xs text-gray-500">JPG, PNG, GIF. Máximo 2MB</p>
                </div>
                
                <div id="imagePreview" class="hidden mb-4">
                    <img id="preview" src="" alt="Preview" class="w-full h-48 object-cover rounded-lg">
                </div>

                <div class="flex gap-3">
                    <button type="button" 
                            onclick="document.getElementById('avatarModal').classList.add('hidden')"
                            class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                        Subir foto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
                document.getElementById('imagePreview').classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
