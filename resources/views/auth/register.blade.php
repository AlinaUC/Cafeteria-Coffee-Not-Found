<x-guest-layout>
    <!-- FONDO GENERAL -->
    <div class="min-h-screen bg-cover bg-center bg-fixed flex items-center justify-center py-12"
         style="background-image: url('{{ asset('images/cafeteria.jpg') }}')">
        
        <!-- CONTENEDOR DEL FORMULARIO -->
        <div class="relative z-10 w-full max-w-md mx-4">
            <div class="bg-black/40 backdrop-blur-xl border border-white/20 rounded-3xl shadow-2xl p-8">
                
                <!-- LOGO Y TÍTULO -->
                <div class="mb-8 text-center">
                    <div class="mb-4">
                        <span class="text-5xl">☕</span>
                    </div>
                    <h2 class="text-3xl font-bold text-white drop-shadow-lg">Crear Cuenta</h2>
                    <p class="text-white/90 mt-2 drop-shadow-md text-sm">Coffee Not Found - UPDS</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- NOMBRE -->
                    <div class="mb-4">
                        <x-input-label for="nombre" :value="__('Nombre Completo')" class="text-white font-semibold mb-2 drop-shadow-sm" />
                        <x-text-input id="nombre" 
                                      class="block mt-1 w-full bg-white/90 border-white/40 text-gray-800 placeholder-gray-500 backdrop-blur-sm focus:bg-white focus:border-amber-500 focus:ring-amber-500 rounded-lg" 
                                      type="text" 
                                      name="nombre" 
                                      :value="old('nombre')" 
                                      required 
                                      autofocus
                                      placeholder="Juan Pérez" />
                        <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                    </div>

                    <!-- EMAIL -->
                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Correo Electrónico')" class="text-white font-semibold mb-2 drop-shadow-sm" />
                        <x-text-input id="email" 
                                      class="block mt-1 w-full bg-white/90 border-white/40 text-gray-800 placeholder-gray-500 backdrop-blur-sm focus:bg-white focus:border-amber-500 focus:ring-amber-500 rounded-lg" 
                                      type="email" 
                                      name="email" 
                                      :value="old('email')" 
                                      required
                                      placeholder="tu@correo.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- CÓDIGO ESTUDIANTE -->
                    <div class="mb-4">
                        <x-input-label for="codigo_estudiante" :value="__('Código de Estudiante')" class="text-white font-semibold mb-2 drop-shadow-sm" />
                        <x-text-input id="codigo_estudiante" 
                                      class="block mt-1 w-full bg-white/90 border-white/40 text-gray-800 placeholder-gray-500 backdrop-blur-sm focus:bg-white focus:border-amber-500 focus:ring-amber-500 rounded-lg" 
                                      type="text" 
                                      name="codigo_estudiante" 
                                      :value="old('codigo_estudiante')"
                                      placeholder="Ej: 2021-001" />
                        <p class="mt-1 text-xs text-white/70">Opcional</p>
                        <x-input-error :messages="$errors->get('codigo_estudiante')" class="mt-2" />
                    </div>

                    <!-- TELÉFONO -->
                    <div class="mb-4">
                        <x-input-label for="telefono" :value="__('Teléfono')" class="text-white font-semibold mb-2 drop-shadow-sm" />
                        <x-text-input id="telefono" 
                                      class="block mt-1 w-full bg-white/90 border-white/40 text-gray-800 placeholder-gray-500 backdrop-blur-sm focus:bg-white focus:border-amber-500 focus:ring-amber-500 rounded-lg" 
                                      type="text" 
                                      name="telefono" 
                                      :value="old('telefono')"
                                      placeholder="70000000" />
                        <p class="mt-1 text-xs text-white/70">Opcional</p>
                        <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
                    </div>

                    <!-- PASSWORD -->
                    <div class="mb-4">
                        <x-input-label for="password" :value="__('Contraseña')" class="text-white font-semibold mb-2 drop-shadow-sm" />
                        <x-text-input id="password" 
                                      class="block mt-1 w-full bg-white/90 border-white/40 text-gray-800 placeholder-gray-500 backdrop-blur-sm focus:bg-white focus:border-amber-500 focus:ring-amber-500 rounded-lg" 
                                      type="password" 
                                      name="password" 
                                      required
                                      placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- CONFIRM PASSWORD -->
                    <div class="mb-6">
                        <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" class="text-white font-semibold mb-2 drop-shadow-sm" />
                        <x-text-input id="password_confirmation" 
                                      class="block mt-1 w-full bg-white/90 border-white/40 text-gray-800 placeholder-gray-500 backdrop-blur-sm focus:bg-white focus:border-amber-500 focus:ring-amber-500 rounded-lg" 
                                      type="password" 
                                      name="password_confirmation" 
                                      required
                                      placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- BOTONES -->
                    <div class="flex items-center justify-between mb-6">
                        <a class="text-sm text-amber-300 hover:text-amber-200 drop-shadow-sm transition" 
                           href="{{ route('login') }}">
                            ¿Ya tienes cuenta?
                        </a>

                        <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                            Registrarse
                        </button>
                    </div>
                </form>

                <!-- SEPARADOR -->
                <div class="mb-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-white/30"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-3 bg-black/40 text-white/80 drop-shadow-sm">O regístrate con</span>
                        </div>
                    </div>
                </div>

                <!-- GOOGLE REGISTER -->
                <div class="mb-6">
                    <a href="{{ route('google.redirect') }}" 
                       class="w-full inline-flex justify-center items-center py-3 px-4 border-2 border-white/30 rounded-lg shadow-md bg-white/10 backdrop-blur-sm text-sm font-semibold text-white hover:bg-white/20 hover:border-white/40 transition-all duration-200">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <span class="ml-2">Registrarse con Google</span>
                    </a>
                </div>

                <!-- LOGIN LINK -->
                <div class="text-center">
                    <p class="text-sm text-white/90 drop-shadow-sm">
                        ¿Ya tienes cuenta?
                        <a href="{{ route('login') }}" class="font-semibold text-amber-300 hover:text-amber-200 transition">
                            Inicia sesión aquí
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>