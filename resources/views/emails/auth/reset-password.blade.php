<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background-color: #1A1A1A; padding: 25px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 2px; }
        .header span { color: #DC2626; }
        .content { padding: 40px 30px; color: #374151; line-height: 1.6; text-align: center; }
        .icon { font-size: 40px; color: #DC2626; margin-bottom: 20px; }
        .btn { display: inline-block; background-color: #DC2626; color: #ffffff !important; padding: 14px 30px; text-decoration: none; border-radius: 50px; font-weight: bold; margin-top: 25px; text-transform: uppercase; font-size: 14px; box-shadow: 0 4px 6px rgba(220, 38, 38, 0.3); }
        .footer { background-color: #f9fafb; padding: 20px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb; }
        .link-alt { color: #DC2626; word-break: break-all; font-size: 12px; margin-top: 20px; display: block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Moto<span>Rápido</span></h1>
        </div>
        <div class="content">
            <div class="icon">&#128274;</div> <h2 style="margin-top: 0; color: #111827;">¿Olvidaste tu contraseña?</h2>
            <p>Hola <strong>{{ $user->firstname }}</strong>,</p>
            <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta. Si no fuiste tú, puedes ignorar este correo de forma segura.</p>

            <a href="{{ $url }}" class="btn">Restablecer Contraseña</a>

            <p style="margin-top: 30px; font-size: 13px; color: #6b7280;">Este enlace expirará en 60 minutos.</p>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 30px 0;">
            <p style="font-size: 12px; color: #9ca3af;">Si el botón no funciona, copia y pega este enlace en tu navegador:<br>
            <a href="{{ $url }}" class="link-alt">{{ $url }}</a></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} MotoRápido. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>
