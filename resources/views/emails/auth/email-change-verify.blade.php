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
        .btn { display: inline-block; background-color: #DC2626; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; }
        .footer { background-color: #f3f4f6; padding: 20px; text-align: center; font-size: 12px; color: #6b7280; }
        .link-alt { color: #DC2626; word-break: break-all; font-size: 12px; margin-top: 20px; display: block; }
        .info-box { background-color: #eff6ff; border-left: 4px solid #1d4ed8; padding: 15px; margin: 20px 0; font-size: 14px; color: #1e3a8a; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Moto<span>Rápido</span></h1>
        </div>

        <div class="content">
            <h2>Verificación de cambio de correo</h2>
            <p>Hola, <strong>{{ $user->firstname }}</strong>.</p>

            <p>Hemos detectado una solicitud para actualizar tu dirección de correo electrónico en nuestra plataforma.</p>

            <div class="info-box">
                <strong>Importante:</strong> Si no hiciste este cambio en tu perfil, por favor contacta soporte inmediatamente y cambia tu contraseña.
            </div>

            <p>Para confirmar que esta nueva dirección es tuya, haz clic en el botón:</p>

            <center>
                <a href="{{ $url }}" class="btn">Confirmar Nuevo Correo</a>
            </center>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 30px 0;">
            <p style="font-size: 12px; color: #9ca3af;">Enlace alternativo:<br>
            <a href="{{ $url }}" class="link-alt">{{ $url }}</a></p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Taller MotoRápido. Seguridad de Cuenta.
        </div>
    </div>
</body>
</html>
