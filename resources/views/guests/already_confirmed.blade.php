<x-guest-layout>
    <style>
        .confirmation-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
            padding: 10px; /* Reducido para móviles */
        }

        .confirmation-box {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: center;
            padding: 30px;
        }

        .confirmation-box h2 {
            color: #28a745;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .confirmation-box h4 {
            font-size: 20px;
            color: #343a40;
            margin-top: 15px;
        }

        .confirmation-box p {
            font-size: 16px;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .alert-info {
            background-color: #e9f7fd;
            color: #0c5460;
            border: 1px solid #bee5eb;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }

        @media screen and (max-width: 768px) {
            .confirmation-wrapper {
                padding: 5px; /* Reducido aún más para pantallas pequeñas */
            }

            .confirmation-box {
                max-width: 90vw;
                padding: 20px;
                margin-top: 10px; /* Menos espacio arriba */
                margin-bottom: 10px; /* Menos espacio abajo */
            }
        }

        @media screen and (max-width: 480px) {
            .confirmation-box h2 {
                font-size: 20px; /* Ajustar tamaño de texto en pantallas muy pequeñas */
                margin-top: -200px; /* Menos espacio arriba */
            }

            .confirmation-box h4 {
                font-size: 18px; /* Ajustar tamaño de texto en pantallas muy pequeñas */
            }

            .confirmation-box p {
                font-size: 14px; /* Ajustar tamaño de texto en pantallas muy pequeñas */
            }
        }
    </style>

    <div class="confirmation-wrapper">
        <div class="confirmation-box">
            <h2>✔ Ya confirmaste tu asistencia</h2>
            <p>Gracias por confirmar tu participación en el evento:</p>

            <h4>{{ $event->title }}</h4>
            <p><strong>Fecha:</strong> {{ $event->event_date}}</p>
            <p><strong>Lugar:</strong> {{ $event->address }}</p>

            <div class="alert alert-info" role="alert">
                Si no recibiste el correo con tu código QR, revisa tu carpeta de <strong>spam</strong> o <a href="#">contáctanos</a>.
            </div>
        </div>
    </div>
</x-guest-layout>
