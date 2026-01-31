<!-- Navbar con glassmorphism mejorado -->
<nav x-data="{ mobileMenuOpen: false, userDropdownOpen: false }" class="fixed top-0 w-full z-50 bg-white/10 backdrop-blur-xl border-b border-white/20 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo y Links -->
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-xl font-bold text-white hover:text-amber-300 transition-all duration-300 drop-shadow-lg group">
                        <span class="text-2xl transform group-hover:scale-110 transition-transform duration-300">☕</span>
                        <span class="bg-gradient-to-r from-white to-amber-200 bg-clip-text text-transparent">Coffee Not Found</span>
                    </a>
                </div>

                <!-- Links escritorio -->
                <div class="hidden sm:flex sm:ml-10 space-x-6">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3 py-1 rounded-lg border-b-2 {{ request()->routeIs('dashboard') ? 'bg-white/20 border-white text-white' : 'border-transparent text-white/90 hover:text-white hover:bg-white/10' }} text-sm font-medium transition-all duration-300 drop-shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Inicio
                    </a>
                    <a href="{{ route('menu.index') }}" class="inline-flex items-center px-3 py-1 rounded-lg border-b-2 {{ request()->routeIs('menu.*') ? 'bg-white/20 border-white text-white' : 'border-transparent text-white/90 hover:text-white hover:bg-white/10' }} text-sm font-medium transition-all duration-300 drop-shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Menú
                    </a>
                    <a href="{{ route('carrito.index') }}" class="inline-flex items-center px-3 py-1 rounded-lg border-b-2 {{ request()->routeIs('carrito.*') ? 'bg-white/20 border-white text-white' : 'border-transparent text-white/90 hover:text-white hover:bg-white/10' }} text-sm font-medium transition-all duration-300 drop-shadow-md relative">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Carrito
                        @if(session('carrito') && count(session('carrito')) > 0)
                            <span class="absolute -top-1 -right-1 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold shadow-lg animate-pulse">{{ count(session('carrito')) }}</span>
                        @endif
                    </a>
                    <a href="{{ route('pedidos.index') }}" class="inline-flex items-center px-3 py-1 rounded-lg border-b-2 {{ request()->routeIs('pedidos.*') ? 'bg-white/20 border-white text-white' : 'border-transparent text-white/90 hover:text-white hover:bg-white/10' }} text-sm font-medium transition-all duration-300 drop-shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Mis Pedidos
                    </a>

                    @if(auth()->user()->esCocina() || auth()->user()->esAdmin())
                        <a href="{{ route('cocina.index') }}" class="inline-flex items-center px-3 py-1 rounded-lg border-b-2 {{ request()->routeIs('cocina.*') ? 'bg-white/20 border-white text-white' : 'border-transparent text-white/90 hover:text-white hover:bg-white/10' }} text-sm font-medium transition-all duration-300 drop-shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                            </svg>
                            Cocina
                        </a>
                    @endif

                    @if(auth()->user()->esAdmin())
                        <a href="{{ route('admin.index') }}" class="inline-flex items-center px-3 py-1 rounded-lg border-b-2 {{ request()->routeIs('admin.*') ? 'bg-white/20 border-white text-white' : 'border-transparent text-white/90 hover:text-white hover:bg-white/10' }} text-sm font-medium transition-all duration-300 drop-shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Admin
                        </a>
                    @endif
                </div>
            </div>

            <!-- Dropdown Usuario escritorio -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="ml-3 relative">
                    <button @click="userDropdownOpen = !userDropdownOpen" class="flex items-center space-x-3 text-sm focus:outline-none bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-full px-4 py-2 transition-all duration-300 border border-white/20">
                        @if(auth()->user()->avatar)
                            <img src="{{ filter_var(auth()->user()->avatar, FILTER_VALIDATE_URL) ? auth()->user()->avatar : asset('storage/' . auth()->user()->avatar) }}" class="h-8 w-8 rounded-full border-2 border-white/60 object-cover shadow-lg ring-2 ring-white/20">
                        @else
                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-amber-400 via-amber-500 to-amber-600 text-white flex items-center justify-center font-bold shadow-lg ring-2 ring-white/20">
                                {{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}
                            </div>
                        @endif
                        <span class="text-white font-semibold drop-shadow-lg">{{ auth()->user()->nombre }}</span>
                        <svg class="h-4 w-4 text-white transition-transform duration-300 drop-shadow-lg" :class="{'rotate-180': userDropdownOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Dropdown menu con glassmorphism -->
                    <div x-show="userDropdownOpen" @click.away="userDropdownOpen = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-64 bg-white/95 backdrop-blur-xl rounded-xl shadow-2xl py-2 z-50 border border-white/30">
                        <div class="px-4 py-3 border-b border-gray-200/50">
                            <p class="text-sm font-bold text-gray-900">{{ auth()->user()->nombre }}</p>
                            <p class="text-xs text-gray-600 truncate mt-1">{{ auth()->user()->email }}</p>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-amber-50 hover:to-orange-50 hover:text-amber-700 transition-all duration-300 group">
                            <div class="p-1.5 bg-amber-100 rounded-lg group-hover:bg-amber-200 transition-colors">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <span class="font-medium">Mi Perfil</span>
                        </a>
                        <a href="{{ route('pedidos.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-700 transition-all duration-300 group">
                            <div class="p-1.5 bg-blue-100 rounded-lg group-hover:bg-blue-200 transition-colors">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <span class="font-medium">Mis Pedidos</span>
                        </a>
                        <div class="border-t border-gray-200/50 my-2"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-sm text-red-600 hover:bg-gradient-to-r hover:from-red-50 hover:to-pink-50 transition-all duration-300 group">
                                <div class="p-1.5 bg-red-100 rounded-lg group-hover:bg-red-200 transition-colors">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                </div>
                                <span class="font-medium">Cerrar Sesión</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile button -->
            <div class="flex items-center sm:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex items-center justify-center p-2 rounded-lg text-white bg-white/10 hover:bg-white/20 transition-all duration-300 border border-white/20">
                    <svg class="h-6 w-6 drop-shadow-lg" :class="{'hidden': mobileMenuOpen, 'block': !mobileMenuOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg class="h-6 w-6 drop-shadow-lg" :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu con glassmorphism -->
    <div x-show="mobileMenuOpen" x-transition class="sm:hidden bg-black/80 backdrop-blur-xl border-t border-white/20">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center pl-3 pr-4 py-3 border-l-4 {{ request()->routeIs('dashboard') ? 'border-amber-500 text-white bg-white/20 backdrop-blur-md' : 'border-transparent text-white/90 hover:bg-white/10 hover:border-white/30' }} text-base font-medium transition-all duration-300">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Inicio
            </a>
            <a href="{{ route('menu.index') }}" class="flex items-center pl-3 pr-4 py-3 border-l-4 {{ request()->routeIs('menu.*') ? 'border-amber-500 text-white bg-white/20 backdrop-blur-md' : 'border-transparent text-white/90 hover:bg-white/10 hover:border-white/30' }} text-base font-medium transition-all duration-300">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Menú
            </a>
            <a href="{{ route('carrito.index') }}" class="flex items-center justify-between pl-3 pr-4 py-3 border-l-4 {{ request()->routeIs('carrito.*') ? 'border-amber-500 text-white bg-white/20 backdrop-blur-md' : 'border-transparent text-white/90 hover:bg-white/10 hover:border-white/30' }} text-base font-medium transition-all duration-300">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Carrito</span>
                </div>
                @if(session('carrito') && count(session('carrito')) > 0)
                    <span class="bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-full px-3 py-1 text-xs font-bold shadow-lg">{{ count(session('carrito')) }}</span>
                @endif
            </a>
            <a href="{{ route('pedidos.index') }}" class="flex items-center pl-3 pr-4 py-3 border-l-4 {{ request()->routeIs('pedidos.*') ? 'border-amber-500 text-white bg-white/20 backdrop-blur-md' : 'border-transparent text-white/90 hover:bg-white/10 hover:border-white/30' }} text-base font-medium transition-all duration-300">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Mis Pedidos
            </a>
            <a href="{{ route('profile.edit') }}" class="flex items-center pl-3 pr-4 py-3 border-l-4 {{ request()->routeIs('profile.*') ? 'border-amber-500 text-white bg-white/20 backdrop-blur-md' : 'border-transparent text-white/90 hover:bg-white/10 hover:border-white/30' }} text-base font-medium transition-all duration-300">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Mi Perfil
            </a>
            @if(auth()->user()->esCocina() || auth()->user()->esAdmin())
                <a href="{{ route('cocina.index') }}" class="flex items-center pl-3 pr-4 py-3 border-l-4 {{ request()->routeIs('cocina.*') ? 'border-amber-500 text-white bg-white/20 backdrop-blur-md' : 'border-transparent text-white/90 hover:bg-white/10 hover:border-white/30' }} text-base font-medium transition-all duration-300">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                    Cocina
                </a>
            @endif
            @if(auth()->user()->esAdmin())
                <a href="{{ route('admin.index') }}" class="flex items-center pl-3 pr-4 py-3 border-l-4 {{ request()->routeIs('admin.*') ? 'border-amber-500 text-white bg-white/20 backdrop-blur-md' : 'border-transparent text-white/90 hover:bg-white/10 hover:border-white/30' }} text-base font-medium transition-all duration-300">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Admin
                </a>
            @endif
        </div>
        <div class="pt-4 pb-3 border-t border-white/20 bg-white/5 backdrop-blur-md">
            <div class="flex items-center px-4">
                @if(auth()->user()->avatar)
                    <img src="{{ filter_var(auth()->user()->avatar, FILTER_VALIDATE_URL) ? auth()->user()->avatar : asset('storage/' . auth()->user()->avatar) }}" class="h-12 w-12 rounded-full border-2 border-white/50 object-cover shadow-lg">
                @else
                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-amber-400 via-amber-500 to-amber-600 text-white flex items-center justify-center font-bold text-lg shadow-lg">
                        {{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}
                    </div>
                @endif
                <div class="ml-3">
                    <div class="text-base font-bold text-white drop-shadow-lg">{{ auth()->user()->nombre }}</div>
                    <div class="text-sm font-medium text-white/80 drop-shadow-md">{{ auth()->user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-1 px-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 w-full text-left px-4 py-3 rounded-lg text-base font-medium text-red-400 bg-red-500/10 hover:bg-red-500/20 transition-all duration-300 border border-red-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>