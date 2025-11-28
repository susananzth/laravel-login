<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            // Cliente (quien pide la cita)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Técnico (quien atiende - nullable porque al inicio puede no tener asignado)
            $table->foreignId('technician_id')->nullable()->constrained('users')->onDelete('set null');
            // Servicio solicitado
            $table->foreignId('service_id')->constrained();

            $table->dateTime('scheduled_at'); // Fecha y hora de la cita
            $table->dateTime('finished_at')->nullable(); // Para historial

            // Estados: pending, confirmed, in_progress, completed, cancelled
            $table->string('status')->default('pending');
            $table->text('notes', 500)->nullable(); // Notas del cliente o mecánico
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
