<div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 max-w-lg w-full mx-auto text-center">
    <div class="mb-6">
        <i class="fas fa-envelope-open-text text-5xl text-moto-red mb-4"></i>
        <h2 class="text-2xl font-bold text-moto-black">{{ __('Verifica tu correo') }}</h2>
        <p class="text-gray-600 mt-4 leading-relaxed">
            {{ __('Gracias por registrarte. Antes de comenzar, ¿podrías verificar tu dirección de correo electrónico haciendo clic en el enlace que te acabamos de enviar?') }}
        </p>
        <p class="text-sm text-gray-500 mt-2">
            {{ __('Si no recibiste el correo, con gusto te enviaremos otro.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-lg text-sm font-medium border border-green-200">
            {{ __('Se ha enviado un nuevo enlace de verificación a la dirección de correo que proporcionaste durante el registro.') }}
        </div>
    @endif

    <div class="space-y-4">
        <x-button wire:click="sendVerification" variant="primary" class="w-full">
            {{ __('Reenviar correo de verificación') }}
        </x-button>

        <button wire:click="logout" class="text-sm text-gray-500 hover:text-moto-black underline decoration-gray-300 hover:decoration-moto-black transition">
            {{ __('Cerrar Sesión') }}
        </button>
    </div>
</div>
