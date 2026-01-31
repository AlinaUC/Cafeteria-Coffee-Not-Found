<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Coffee Not Found - Cafetería UPDS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <!-- FONDO GENERAL -->
    <div class="min-h-screen bg-cover bg-center bg-fixed"
         style="background-image: url('{{ asset('images/cafeteria.jpg') }}')">
        
        <!-- OVERLAY -->
        <div class="min-h-screen bg-black/40 flex flex-col">
            
            <!-- Header -->
            <header class="bg-white/20 backdrop-blur-md border-b border-white/30 shadow-xl">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
                    <h1 class="text-2xl font-bold text-white drop-shadow-lg">☕ Coffee Not Found</h1>
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2 rounded-lg transition font-semibold">
                            Dashboard
                        </a>
                    @else
                        <div class="space-x-4">
                            <a href="{{ route('login') }}" class="text-white hover:text-amber-300 font-semibold drop-shadow-md transition">
                                Iniciar Sesión
                            </a>
                            <a href="{{ route('register') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2 rounded-lg transition font-semibold">
                                Registrarse
                            </a>
                        </div>
                    @endauth
                </div>
            </header>

            <!-- Hero Section -->
            <main class="flex-1">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                    
                    <!-- TÍTULO PRINCIPAL -->
                    <div class="text-center mb-16">
                        <h2 class="text-5xl font-bold text-white mb-4 drop-shadow-2xl">
                            Sistema de Pedidos Digital
                        </h2>
                        <p class="text-xl text-white/90 mb-4 drop-shadow-lg">
                            Cafetería Universitaria - Universidad Privada Domingo Savio
                        </p>
                        <p class="text-lg text-white/80 max-w-2xl mx-auto mb-8 drop-shadow-md">
                            Realiza tus pedidos de forma rápida y sencilla. Evita las filas y recoge tu pedido cuando esté listo.
                        </p>
                        @guest
                        <div class="flex gap-4 justify-center">
                            <a href="{{ route('register') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-8 py-3 rounded-lg text-lg font-semibold shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                                Comenzar Ahora
                            </a>
                            <a href="{{ route('login') }}" class="bg-white/20 backdrop-blur-md border-2 border-white/40 hover:bg-white/30 text-white px-8 py-3 rounded-lg text-lg font-semibold shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                                Iniciar Sesión
                            </a>
                        </div>
                        @endguest
                    </div>

                    <!-- Features -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                        <div class="bg-white/20 backdrop-blur-lg border border-white/30 rounded-2xl shadow-xl p-6 text-center hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                            <div class="bg-amber-600/30 backdrop-blur-sm border border-amber-500/40 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-2 text-white drop-shadow-md">Ahorra Tiempo</h3>
                            <p class="text-white/80 drop-shadow-sm">Realiza tu pedido desde cualquier lugar y evita las filas en horarios pico</p>
                        </div>

                        <div class="bg-white/20 backdrop-blur-lg border border-white/30 rounded-2xl shadow-xl p-6 text-center hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                            <div class="bg-amber-600/30 backdrop-blur-sm border border-amber-500/40 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-2 text-white drop-shadow-md">Menú Completo</h3>
                            <p class="text-white/80 drop-shadow-sm">Explora toda nuestra oferta gastronómica con precios y disponibilidad en tiempo real</p>
                        </div>

                        <div class="bg-white/20 backdrop-blur-lg border border-white/30 rounded-2xl shadow-xl p-6 text-center hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                            <div class="bg-amber-600/30 backdrop-blur-sm border border-amber-500/40 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-2 text-white drop-shadow-md">Pago Seguro</h3>
                            <p class="text-white/80 drop-shadow-sm">Paga con QR o tarjeta de crédito de forma segura</p>
                        </div>
                    </div>

                    <!-- Cómo Funciona -->
                    <div class="bg-white/20 backdrop-blur-lg border border-white/30 rounded-2xl shadow-xl p-8 mb-16">
                        <h3 class="text-2xl font-bold text-center mb-8 text-white drop-shadow-lg">¿Cómo Funciona?</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div class="text-center">
                                <div class="bg-amber-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 text-xl font-bold shadow-lg">1</div>
                                <h4 class="font-semibold mb-2 text-white drop-shadow-md">Regístrate</h4>
                                <p class="text-sm text-white/80">Crea tu cuenta o inicia sesión con Google</p>
                            </div>
                            <div class="text-center">
                                <div class="bg-amber-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 text-xl font-bold shadow-lg">2</div>
                                <h4 class="font-semibold mb-2 text-white drop-shadow-md">Elige tu Comida</h4>
                                <p class="text-sm text-white/80">Explora el menú y agrega productos al carrito</p>
                            </div>
                            <div class="text-center">
                                <div class="bg-amber-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 text-xl font-bold shadow-lg">3</div>
                                <h4 class="font-semibold mb-2 text-white drop-shadow-md">Realiza el Pago</h4>
                                <p class="text-sm text-white/80">Paga de forma segura con QR o tarjeta</p>
                            </div>
                            <div class="text-center">
                                <div class="bg-amber-600 text-white w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 text-xl font-bold shadow-lg">4</div>
                                <h4 class="font-semibold mb-2 text-white drop-shadow-md">Recoge tu Pedido</h4>
                                <p class="text-sm text-white/80">Recibe notificación cuando esté listo</p>
                            </div>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="bg-white/20 backdrop-blur-lg border border-white/30 rounded-2xl shadow-xl p-8 text-center">
                        <h3 class="text-2xl font-bold mb-8 text-white drop-shadow-lg">Confiado por la Comunidad UPDS</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div>
                                <p class="text-4xl font-bold text-amber-300 mb-2 drop-shadow-lg">200+</p>
                                <p class="text-white/80 drop-shadow-sm">Estudiantes Activos</p>
                            </div>
                            <div>
                                <p class="text-4xl font-bold text-amber-300 mb-2 drop-shadow-lg">50+</p>
                                <p class="text-white/80 drop-shadow-sm">Pedidos Diarios</p>
                            </div>
                            <div>
                                <p class="text-4xl font-bold text-amber-300 mb-2 drop-shadow-lg">4.8★</p>
                                <p class="text-white/80 drop-shadow-sm">Calificación Promedio</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white/10 backdrop-blur-md border-t border-white/20 mt-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <div class="text-center text-white/80 drop-shadow-md">
                        <p>&copy; 2026 Coffee Not Found - Universidad Privada Domingo Savio</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</body>
</html>