<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 0 auto; }
        .header { background: #6ae34f; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .qr-code { text-align: center; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Gracias por tu Asistencia</h1>
        </div>
        <div class="content">
            <h4>Hola <strong>{{ $guest->name }}</strong>,</h4>
            <h5>Tu asistencia al evento <strong>{{ $event->title }}</strong> ha sido confirmada.</h5>     
            <p><strong>Fecha: </strong> 
                {{-- Formatea la fecha directamente con PHP --}}
                @php
                    echo date('d-m-Y', strtotime($event->event_date));
                @endphp
            </p>
            <p><strong>Lugar: </strong> {{ $event->address }}</p>
            <P><STROng>Hora de inicio:{{{$event->start_time}}} </STROng></P>
        </div>
    </div>
</body>
</html>