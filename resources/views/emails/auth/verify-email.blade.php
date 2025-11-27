<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background-color: #DC2626; padding: 25px; text-align: center; } /* Cabecera Roja para variar */
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 2px; }
        .content { padding: 40px 30px; color: #374151; line-height: 1.6; text-align: center; }
        .btn { display: inline-block; background-color: #1A1A1A; color: #ffffff !important; padding: 14px 30px; text-decoration: none; border-radius: 50px; font-weight: bold; margin-top: 25px; text-transform: uppercase; font-size: 14px; }
        .footer { background-color: #f9fafb; padding: 20px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb; }
        .link-alt { color: #DC2626; word-break: break-all; font-size: 12px; margin-top: 20px; display: block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>MotoRápido</h1>
        </div>
        <div class="content">
            <h2 style="margin-top: 0; color: #111827;">¡Bienvenido, {{ $user->firstname }}!</h2>
            <p>Gracias por registrarte en MotoRápido. Antes de comenzar a agendar tus citas, necesitamos que confirmes tu dirección de correo electrónico.</p>

            <a href="{{ $url }}" class="btn">Verificar Correo Electrónico</a>

            <p style="margin-top: 30px; margin-bottom: 0;">Si no creaste esta cuenta, no necesitas realizar ninguna acción.</p>

            <a href="{{ $url }}" class="link-alt">{{ $url }}</a>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} MotoRápido. Gestión de Citas.
        </div>
    </div>
</body>
</html>
