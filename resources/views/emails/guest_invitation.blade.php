<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invitación a Evento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #6e48aa;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #6e48aa;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 15px 0;
            decoration: none;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>¡Has sido invitado!</h1> {{-- Primer email que llegara al invitado una vez registrado en el sistema --}}
    </div>
    
    <div class="content">
        <h2>{{ $event->title }}</h2>
        <p><strong>Descripción:</strong> {{ $event->description }}</p>
        <p><strong>Fecha: </strong> 
            {{-- Formatea la fecha directamente con PHP --}}
            @php
                echo date('d-m-Y', strtotime($event->event_date));
            @endphp
        </p>
        <p><strong>Hora:</strong> {{ $event->start_time }} - {{ $event->end_time }}</p>
        <p><strong>Dirección:</strong> {{ $event->address }}</p>
        <p><strong>Salón:</strong> {{ $event->rooms }}</p>
        <p><strong>Límite de invitados:</strong> {{ $event->limit_guest }}</p>
        
        <p>Hola {{ $guest->name }}, por favor confirma tu asistencia completando tus datos:</p>
        
        <a href="{{ $registrationLink }}" class="btn">
            Confirmar Mi Asistencia
        </a>
        
        
        <p><small>Este enlace es personalizado y válido solo para tu invitación.</small></p>
    </div>
    
    <div class="footer">
        © {{ date('Y') }} Sistema de Gestión de Eventos. Todos los derechos reservados.
    </div>
</body>

</html>