<footer class="bg-moto-black text-white pt-16 pb-8 border-t border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12 text-center md:text-left">

            <div>
                <div class="flex items-center justify-center md:justify-start mb-4">
                    <i class="fas fa-motorcycle text-moto-red text-3xl mr-3"></i>
                    <span class="text-2xl font-black tracking-tighter">Moto<span class="text-moto-red">Rápido</span></span>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">
                    La plataforma líder en gestión de servicios mecánicos para motocicletas. Calidad, rapidez y confianza en un solo lugar.
                </p>
            </div>

            <div class="flex flex-col space-y-3">
                <h4 class="font-bold text-lg mb-2 text-white">Enlaces Rápidos</h4>
                <a href="{{ route('login') }}" class="text-gray-400 hover:text-moto-red transition text-sm">Iniciar Sesión</a>
                <a href="{{ route('register') }}" class="text-gray-400 hover:text-moto-red transition text-sm">Crear Cuenta</a>
                <a href="#" class="text-gray-400 hover:text-moto-red transition text-sm">Términos y Condiciones</a>
            </div>

            <div>
                <h4 class="font-bold text-lg mb-4 text-white">Contáctanos</h4>
                <ul class="space-y-3 text-sm text-gray-400">
                    <li><i class="fas fa-map-marker-alt w-6 text-moto-red"></i> Av. Principal 123, Lima</li>
                    <li><i class="fas fa-phone w-6 text-moto-red"></i> +51 999 999 999</li>
                    <li><i class="fas fa-envelope w-6 text-moto-red"></i> contacto@motorapido.com</li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} MotoRápido System. Desarrollado por SusanaNzth.</p>
            <div class="flex space-x-4 mt-4 md:mt-0">
                <a href="#" class="text-gray-500 hover:text-white transition"><i class="fab fa-facebook text-xl"></i></a>
                <a href="#" class="text-gray-500 hover:text-white transition"><i class="fab fa-instagram text-xl"></i></a>
                <a href="#" class="text-gray-500 hover:text-white transition"><i class="fab fa-twitter text-xl"></i></a>
            </div>
        </div>
    </div>
</footer>
