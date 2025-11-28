<div
    x-data="{
        notifications: [],
        add(message, type = 'success') {
            // Generar ID único
            const id = Date.now();
            
            // Validar que el mensaje sea texto (para evitar errores que causen bucles)
            const text = (typeof message === 'string') ? message : JSON.stringify(message);

            this.notifications.push({ id, message: text, type });

            // Auto-eliminar
            setTimeout(() => {
                this.remove(id);
            }, 4000);
        },
        remove(id) {
            this.notifications = this.notifications.filter(n => n.id !== id);
        },
        handleEvent(event, defaultType) {
            // Livewire 3 envía los datos en 'event.detail'
            // Puede venir como objeto { message: '...', type: '...' } o directo
            let msg = '';
            let type = defaultType;

            if (typeof event.detail === 'object' && event.detail !== null) {
                // Si usamos dispatch('notify', message: 'x', type: 'y')
                msg = event.detail.message || event.detail[0] || 'Error desconocido';
                type = event.detail.type || event.detail[1] || defaultType;
            } else {
                msg = event.detail;
            }

            this.add(msg, type);
        }
    }"
    x-on:notify.window="handleEvent($event, 'success')"
    x-on:app-error.window="handleEvent($event, 'error')"
    class="fixed top-4 right-4 z-[100] space-y-2 w-full max-w-xs pointer-events-none"
>
    <template x-for="notification in notifications" :key="notification.id">
        <div
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5"
        >
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <template x-if="notification.type === 'success'">
                            <i class="fas fa-check-circle text-green-400 text-xl"></i>
                        </template>
                        <template x-if="notification.type === 'error'">
                            <i class="fas fa-exclamation-circle text-red-400 text-xl"></i>
                        </template>
                        <template x-if="notification.type === 'info'">
                            <i class="fas fa-info-circle text-blue-400 text-xl"></i>
                        </template>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-medium text-gray-900" x-text="notification.type === 'error' ? '¡Ups!' : 'Notificación'"></p>
                        <p class="mt-1 text-sm text-gray-500" x-text="notification.message"></p>
                    </div>
                    <div class="ml-4 flex flex-shrink-0">
                        <button type="button" @click="remove(notification.id)" class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>