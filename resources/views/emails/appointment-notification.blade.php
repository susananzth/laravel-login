<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; background-color: #f3f4f6; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; margin-top: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background-color: #1A1A1A; padding: 20px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 1px; }
        .header span { color: #DC2626; }
        .content { padding: 30px; color: #374151; line-height: 1.6; }
        .status-badge { display: inline-block; padding: 6px 12px; border-radius: 9999px; font-size: 14px; font-weight: bold; color: white; background-color: #DC2626; margin-bottom: 20px; }
        .details { background-color: #f9fafb; padding: 20px; border-radius: 6px; border-left: 4px solid #DC2626; margin: 20px 0; }
        .details p { margin: 5px 0; font-size: 15px; }
        .footer { background-color: #f3f4f6; padding: 20px; text-align: center; font-size: 12px; color: #6b7280; }
        .btn { display: inline-block; background-color: #DC2626; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Moto<span>Rápido</span></h1>
        </div>

        <div class="content">
            <h2>Hola, {{ $appointment->client->firstname }}</h2>

            @if($type === 'created')
                <p>¡Tu cita ha sido agendada exitosamente! Estamos listos para recibir tu moto.</p>
            @elseif($type === 'cancelled')
                <p>Te informamos que tu cita ha sido cancelada.</p>
            @else
                <p>Ha habido una actualización importante en el estado o fecha de tu servicio.</p>
            @endif

            <div class="details">
                <p><strong>Servicio:</strong> {{ $appointment->service->name }}</p>
                <p><strong>Fecha:</strong> {{ $appointment->scheduled_at->format('d/m/Y') }}</p>
                <p><strong>Hora:</strong> {{ $appointment->scheduled_at->format('H:i') }} h</p>
                <p><strong>Estado Actual:</strong>
                    @switch($appointment->status)
                        @case('pending') Pendiente @break
                        @case('confirmed') Confirmada @break
                        @case('in_progress') En Taller @break
                        @case('completed') Listo para recoger @break
                        @case('cancelled') Cancelada @break
                    @endswitch
                </p>
                @if($appointment->notes)
                    <p><strong>Notas:</strong> {{ $appointment->notes }}</p>
                @endif
            </div>

            <p>Si tienes alguna duda, puedes contactarnos respondiendo a este correo o llamando a nuestro taller.</p>

            <center>
                <a href="{{ route('dashboard') }}" class="btn">Ver en mi Cuenta</a>
            </center>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Taller MotoRápido. Todos los derechos reservados.<br>
            Este es un correo automático, por favor no responder si no es necesario.
        </div>
    </div>
</body>
</html>
