<x-layouts.guest>
    {{-- Hero Section con imagen de fondo sutil --}}
    <header class="relative bg-moto-black text-white pt-32 pb-40 overflow-hidden">
        {{-- Patrón de fondo (opcional) --}}
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10">
            <span class="inline-block py-1 px-3 rounded-full bg-moto-red/20 border border-moto-red/30 text-moto-red text-sm font-bold mb-6 uppercase tracking-wider animate-pulse">
                🚀 Servicio Premium Disponible
            </span>
            <h1 class="text-5xl md:text-7xl font-black mb-6 leading-tight tracking-tight">
                Tu Moto Merece <br class="hidden md:block" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-orange-500">Expertos Reales</span>.
            </h1>
            <p class="text-lg md:text-xl text-gray-400 mb-10 max-w-2xl mx-auto leading-relaxed">
                Olvídate de las llamadas y esperas. Agenda tu servicio de mantenimiento o reparación en segundos con nuestra plataforma digital.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4">
                @auth
                    <a href="{{ route('appointments.create') }}" class="inline-flex justify-center items-center px-8 py-4 text-lg font-bold rounded-full text-white bg-moto-red hover:bg-red-600 transition duration-300 shadow-xl shadow-red-500/30 transform hover:scale-105 hover:-translate-y-1">
                        <i class="fas fa-calendar-check mr-3"></i> Agendar Ahora
                    </a>
                @else
                    <a href="{{ route('register') }}" class="inline-flex justify-center items-center px-8 py-4 text-lg font-bold rounded-full text-white bg-moto-red hover:bg-red-600 transition duration-300 shadow-xl shadow-red-500/30 transform hover:scale-105 hover:-translate-y-1">
                        Registrate
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center px-8 py-4 text-lg font-bold rounded-full text-white border-2 border-gray-700 hover:bg-gray-800 hover:border-gray-600 transition duration-300">
                        Ya tengo cuenta
                    </a>
                @endauth
            </div>

            <p class="mt-8 text-sm text-gray-500 flex justify-center items-center gap-2">
                <i class="fas fa-check-circle text-green-500"></i> Sin tarjetas de crédito requeridas
            </p>
        </div>
    </header>

    {{-- Features Section --}}
    <section class="py-24 bg-gray-50 relative">
        <div class="absolute top-0 left-0 right-0 h-16 bg-moto-black rounded-b-[50%] md:rounded-b-[100%] transform -translate-y-1/2 scale-x-110"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-black text-moto-black mb-4">¿Por qué elegirnos?</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">Hemos optimizado cada paso del proceso para que tu experiencia sea tan veloz como tu moto.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition duration-300 border border-gray-100 group">
                    <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center mb-6 group-hover:bg-moto-red transition duration-300">
                        <i class="fas fa-calendar-alt text-moto-red text-2xl group-hover:text-white transition duration-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-moto-black mb-3">Agenda 24/7</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Nuestro sistema nunca duerme. Reserva tu espacio a cualquier hora, desde cualquier dispositivo.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition duration-300 border border-gray-100 group relative top-0 md:-top-8">
                    <div class="absolute top-0 right-0 bg-yellow-400 text-xs font-bold px-3 py-1 rounded-bl-lg rounded-tr-lg">POPULAR</div>
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-6 group-hover:bg-blue-600 transition duration-300">
                        <i class="fas fa-bell text-blue-600 text-2xl group-hover:text-white transition duration-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-moto-black mb-3">Notificaciones Reales</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Recibe alertas cuando tu técnico sea asignado, cuando empiece el trabajo y cuando esté listo.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition duration-300 border border-gray-100 group">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mb-6 group-hover:bg-green-600 transition duration-300">
                        <i class="fas fa-history text-green-600 text-2xl group-hover:text-white transition duration-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-moto-black mb-3">Historial Digital</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Lleva un registro detallado de todos los mantenimientos realizados a tu vehículo para siempre.
                    </p>
                </div>
            </div>
        </div>
    </section>
</x-layouts.guest>
