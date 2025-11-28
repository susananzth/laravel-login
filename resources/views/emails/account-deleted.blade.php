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
        .footer { background-color: #f3f4f6; padding: 20px; text-align: center; font-size: 12px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Moto<span>Rápido</span></h1>
        </div>

        <div class="content">
            <h2>Hola, {{ $firstname }}</h2>

            <p>Lamentamos verte partir.</p>

            <p>Te confirmamos que hemos recibido tu solicitud para eliminar tu cuenta. <strong>Tus datos personales y de acceso han sido eliminados de nuestro sistema.</strong></p>

            <p>Según nuestras políticas de retención, cierta información histórica de citas podría conservarse de forma anónima o ser purgada definitivamente en un plazo de 10 días.</p>

            <p>Esperamos volver a verte en el futuro con tu moto.</p>

            <p>Saludos,<br>El equipo de MotoRápido.</p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Taller MotoRápido. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>
