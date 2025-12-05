<x-layouts.guest>
    <div class="mx-auto max-w-xl overflow-hidden p-4">

        <h1 class="text-3xl mb-1">Ficha Técnica</h1>
        <h2 class="text-2xl mb-2">Sistema de Gestión de Taller (MotoManager Demo)</h2>
        <p class="mb-2">
            Propósito del Sitio: Este dominio
            (<a href="https://moto.susananzth.xyz" target="_blank">moto.susananzth.xyz</a>)
            actúa como un entorno de despliegue (deployment environment) para demostrar
            capacidades de desarrollo Full Stack. El objetivo es la exhibición de arquitectura
            de software, patrones de diseño y manejo de infraestructura en la nube.
        </p>

        <h4 class="text-xl">Estado del Proyecto:</h4>
        <ul class="list-disc list-inside mb-2">
            <li><strong>Tipo</strong>: Open Source (Bajo licencia GPL v3).</li>
            <li><strong>Repositorio</strong>: <a href="https://github.com/susananzth/laravel-moto-schedule" target="_blank">laravel-moto-schedule</a>.</li>
            <li><strong>Datos</strong>: Todos los datos en este sistema son simulados (Mocks/Seeders). Cualquier coincidencia con la realidad es pura coincidencia.</li>
        </ul>

        <h4 class="text-xl">Stack Tecnológico:</h4>
        <ul class="list-disc list-inside mb-2">
            <li><strong>Backend</strong>: Laravel 12 (PHP), Livewire.</li>
            <li><strong>Infraestructura</strong>: Docker en DigitalOcean Droplet, Ubuntu Server.</li>
            <li><strong>Seguridad</strong>: Implementación de CSRF protection, autenticación segura (Breeze), validación de emails transaccionales (DKIM/SPF/DMARC vía Brevo).</li>
        </ul>

        <p>
            <strong>Aviso Legal</strong>: El desarrollador no se hace responsable por el
            uso de datos reales introducidos erróneamente en este entorno de pruebas.
            Este sitio no tiene fines de lucro comercial directo ni representa a una entidad
            corporativa existente.
        </p>
    </div>
</x-layouts.guest>
