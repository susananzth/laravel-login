<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; background-color: #f3f4f6; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; margin-top: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background-color: #1A1A1A; padding: 20px; text-align: center; } /* Cabecera Negra MotoRápido */
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 1px; }
        .header span { color: #DC2626; }
        .content { padding: 30px; color: #374151; line-height: 1.6; text-align: center; }
        .btn { display: inline-block; background-color: #DC2626; color: white !important; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 20px; }
        .footer { background-color: #f3f4f6; padding: 20px; text-align: center; font-size: 12px; color: #6b7280; }
        .link-alt { color: #DC2626; word-break: break-all; font-size: 12px; margin-top: 20px; display: block; }
        .hero-text { font-size: 18px; margin-bottom: 20px; color: #111827; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Moto<span>Rápido</span></h1>
        </div>

        <div class="content">
            <h2 style="margin-top: 0;">¡Bienvenido al equipo, {{ $user->firstname }}! 🏍️</h2>

            <p class="hero-text">Gracias por registrarte en MotoRápido.</p>

            <p>Ya casi terminamos. Para garantizar la seguridad de tu cuenta y poder agendar tus citas de taller, necesitamos confirmar que este correo es tuyo.</p>

            <center>
                <a href="{{ $url }}" class="btn">Confirmar mi Cuenta</a>
            </center>

            <p style="margin-top: 30px; font-size: 14px; color: #4b5563;">
                Una vez confirmado, podrás acceder a tu panel y gestionar el mantenimiento de tu moto con nosotros.
            </p>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 30px 0;">
            <p style="font-size: 12px; color: #9ca3af;">Si el botón no funciona, usa este enlace:<br>
            <a href="{{ $url }}" class="link-alt">{{ $url }}</a></p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Taller MotoRápido. Pasión por las motos.
        </div>
    </div>
</body>
</html>
