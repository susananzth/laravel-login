<div
    x-data="{
        notifications: [],
        add(message, type = 'success') {
            const id = Date.now();
            this.notifications.push({ id, message, type });

            // Auto-eliminar después de 3 segundos
            setTimeout(() => {
                this.remove(id);
            }, 4000);
        },
        remove(id) {
            this.notifications = this.notifications.filter(n => n.id !== id);
        }
    }"
    x-on:notify.window="add($event.detail, 'success')"
    x-on:error.window="add($event.detail, 'error')"
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
                            <i class="fas fa-times-circle text-red-400 text-xl"></i>
                        </template>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-medium text-gray-900" x-text="notification.type === 'success' ? '¡Éxito!' : 'Error'"></p>
                        <p class="mt-1 text-sm text-gray-500" x-text="notification.message"></p>
                    </div>
                    <div class="ml-4 flex flex-shrink-0">
                        <button type="button" @click="remove(notification.id)" class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            <span class="sr-only">Close</span>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
