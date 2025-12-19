@extends('admin.layouts.master')

@section('content')

    <div class="flex items-center h-screen w-full">
        <p class="m-4">Calendario de Reservas</p>
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
                events: '{{ route("cliente.fullcalendar") }}',
                // Función que se ejecuta después de que un evento ha sido montado en el calendario
                eventDidMount: function(info) {

                    // Cambia el color de fondo si el evento tiene un color definido
                    if (info.event.backgroundColor) {
                        info.el.style.backgroundColor = info.event.backgroundColor;
                    }

                    // Cambia el color del borde si el evento tiene un color de borde definido
                    if (info.event.borderColor) {
                        info.el.style.borderColor = info.event.borderColor;
                    }

                    // Cambia el color del borde si el evento tiene un color de borde definido
                    if (info.event.textColor) {
                        info.el.style.color = info.event.textColor;
                    }
                }
            });

            // Renderiza el calendario en la página
            calendar.render();
        });
    </script>

@endpush
