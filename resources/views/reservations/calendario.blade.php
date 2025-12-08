@extends('admin.layouts.master')

@section('content')

<div class="flex items-center h-screen w-full">
    <!-- Calendario -->
    <div id="calendar" class="w-full p-4 bg-white shadow rounded-lg" style="margin: 1rem; min-height: calc(100vh - 2rem);"></div>
</div>

@endsection

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obtiene el elemento del DOM donde se mostrará el calendario
        var calendarE1 = document.getElementById('calendar');

        // Inicializa el calendario con FullCalendar
        var calendar = new FullCalendar.Calendar(calendarE1, {
            initialView: 'dayGridMonth', // Vista inicial del calendario (vista por mes)
            locale: 'es', // Establece el idioma en español
            headerToolbar: { // Configura las opciones de navegación en el calendario
                left: 'prev,next today', // Botones prev, next y today a la izquierda
                center: 'title', // Título del calendario (mes o día actual) en el centro
                right: 'dayGridMonth,timeGridWeek,timeGridDay', // Vistas disponibles: mes, semana, día a la derecha
            },
            buttonText: { // Traduce los textos de los botones
                today: 'Hoy', // Texto para el botón "Hoy"
                month: 'Mes', // Texto para la vista mensual
                week: 'Semana', // Texto para la vista semanal
                day: 'Día', // Texto para la vista diaria
            },
            // Carga los eventos desde la ruta especificada (datos dinámicos desde el backend)
            events: '{{ route("administrador.fullcalendar") }}',
            // Función que se ejecuta al hacer clic en un evento
            eventClick: function(info) {
                // Evita la acción predeterminada
                info.jsEvent.preventDefault();

                    // Extrae los datos de extendedProps
                    const userName = info.event.extendedProps.userName || 'N/A';
                    const consultantName = info.event.extendedProps.consultantName || 'N/A';
                    const startTime = info.event.extendedProps.startTime || 'N/A';
                    const endTime = info.event.extendedProps.endTime || 'N/A';
                    const amountPaid = info.event.extendedProps.amountPaid || 'N/A';

                // Muestra el modal con SweetAlert2
                Swal.fire({
                    title: 'Detalles de la Reserva',
                    html: `
                        <p><strong>CLIENTE: </strong> ${userName}</p>
                        <p><strong>CONSULTOR: </strong> <spam style="color:blue;">${consultantName}</spam> </p>
                        <p><strong>Hora de Inicio:</strong> ${startTime}</p>
                        <p><strong>Hora Final:</strong> ${endTime}</p>
                        <p><strong>Monto Pagado:</strong> <spam style="color:green;font-weight: bold;">$${amountPaid}</spam></p>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Cerrar',
                });
            },
        });

        // Renderiza el calendario en la página
        calendar.render();
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endpush
