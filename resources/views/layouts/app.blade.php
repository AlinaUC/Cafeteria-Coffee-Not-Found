<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Coffee Not Found') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <!-- FONDO GENERAL CON IMAGEN -->
    <div class="min-h-screen bg-cover bg-center bg-fixed"
         style="background-image: url('{{ asset('images/cafeteria.jpg') }}')">
        
        <!-- OVERLAY OSCURO -->
        <div class="min-h-screen bg-black/50">

            <!-- NAVBAR -->
            @include('layouts.navigation')

            <!-- ALERTAS -->
            @if (session('success'))
                <div class="fixed top-20 right-6 z-50 animate-fade-in">
                    <div class="bg-green-500/95 backdrop-blur-sm text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 border border-green-400/50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="fixed top-20 right-6 z-50 animate-fade-in">
                    <div class="bg-red-500/95 backdrop-blur-sm text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 border border-red-400/50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- HEADER (si existe en la vista) -->
            @hasSection('header')
                <header class="bg-white/10 backdrop-blur-md border-b border-white/20 shadow-xl">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        @yield('header')
                    </div>
                </header>
            @endif

            <!-- CONTENIDO PRINCIPAL -->
            <main class="w-full py-8">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')

    <!-- Script para auto-ocultar alertas -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.animate-fade-in');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 3000);
            });
        });
    </script>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>
</body>
</html>