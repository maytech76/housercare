<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso al sistema</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #f4fdfd;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            background-color: #ffffff;
            margin: 30px auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 128, 128, 0.1);
            border-top: 5px solid #1abc9c;
        }

        h2 {
            color: #2c3e50;
        }

        p {
            color: #34495e;
            line-height: 1.6;
        }

        .highlight {
            font-weight: bold;
            color: #16a085;
        }

        .btn {
            display: inline-block;
            background-color: #1abc9c;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 20px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #149c84;
        }

        @media screen and (max-width: 600px) {
            .container {
                margin: 15px;
                padding: 20px;
            }

            .btn {
                width: 100%;
                text-align: center;
                padding: 14px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>¡Hola, {{ $userName }}!</h2>

        <p>Te informamos que tu cuenta ha sido actualizada correctamente.</p>

        <p>
            <span class="highlight">Correo:</span> {{ $userEmail }}<br>
            <span class="highlight">Contraseña:</span> {{ $password }}
        </p>

        <p>Puedes acceder al sistema utilizando el siguiente botón:</p>

        <a href="{{ $url }}" class="btn">Accede desde aquí</a>

        <p style="margin-top: 30px;">Gracias por formar parte de nuestro equipo.</p>
    </div>
</body>
</html>
