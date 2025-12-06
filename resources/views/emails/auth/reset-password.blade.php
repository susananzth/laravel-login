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
        .btn { display: inline-block; background-color: #DC2626; color: white !important; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; }
        .footer { background-color: #f3f4f6; padding: 20px; text-align: center; font-size: 12px; color: #6b7280; }
        .link-alt { color: #DC2626; word-break: break-all; font-size: 12px; margin-top: 20px; display: block; }
        .warning { background-color: #fff5f5; border-left: 4px solid #DC2626; padding: 15px; margin: 20px 0; font-size: 14px; color: #7f1d1d; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Moto<span>Rápido</span></h1>
        </div>

        <div class="content">
            <h2>Recuperación de Contraseña</h2>
            <p>Hola, <strong>{{ $user->firstname }}</strong>.</p>

            <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en MotoRápido.</p>

            <center>
                <a href="{{ $url }}" class="btn">Restablecer mi Contraseña</a>
            </center>

            <div class="warning">
                <strong>¿No pediste este cambio?</strong><br>
                Si no realizaste esta solicitud, puedes ignorar este correo. Tu cuenta sigue segura y no se han realizado cambios.
            </div>

            <p style="font-size: 13px; color: #6b7280;">Este enlace de restablecimiento expirará en 60 minutos.</p>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 30px 0;">
            <p style="font-size: 12px; color: #9ca3af;">Si tienes problemas con el botón, copia y pega el siguiente enlace:<br>
            <a href="{{ $url }}" class="link-alt">{{ $url }}</a></p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Taller MotoRápido. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>
