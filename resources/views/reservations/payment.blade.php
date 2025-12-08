@extends('admin.layouts.master')

@section('content')
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" role="alert">
            <span class="block sm:inline"></span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="show = false">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <span class="alert-inner--icon"><i class="fe fe-check-square"></i></span>
                    <span class="alert-inner--text"><strong>Excelente.! </strong>{{ session('success') }}</span>
                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            </span>
        </div>
    @endif

    {{-- Tabla de Reservas --}}
    <div class="row row-sm pt-6 mx-auto">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Listado de Pagos</h3>

                    <a class="btn ripple btn-teal" data-bs-target="#select3modal" data-bs-toggle="modal">+Nueva</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table border-top-0  table-bordered text-nowrap border-bottom"
                            id="responsive-datatable">
                            <thead>
                                <tr>
                                   <th class="wd-15p border-bottom-0 bg-light">N°</th>
                                    <th class="wd-15p border-bottom-0 bg-light">Usuario</th>
                                    <th class="wd-15p border-bottom-0 bg-light">Consultor</th>
                                    <th class="wd-20p border-bottom-0 bg-light">Fecha</th>
                                    <th class="wd-20p border-bottom-0 bg-light">Hora Inicio</th>
                                    <th class="wd-15p border-bottom-0 bg-light">Hora Fin</th>
                                    <th class="wd-25p border-bottom-0 bg-light">Email</th>
                                    <th class="wd-25p border-bottom-0 bg-light">Abonado</th>
                                    <th class="wd-25p border-bottom-0 bg-light">Transacción</th>
                                    <th class="wd-25p border-bottom-0 bg-light">Reserva</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td>{{$payment->reservation->id}} </td>
                                        <td class="fw-light">{{ $payment->reservation->user->name}} {{ $payment->reservation->user->last_name}}</td>
                                        <td class="fw-light">{{ $payment->reservation->consultant->name }} {{ $payment->reservation->consultant->last_name }}</td>
                                        <td>{{ $payment->reservation->formatted_reservation_date }}</td>
                                        <td>{{ $payment->reservation->start_time }}</td>
                                        <td>{{ $payment->reservation->end_time }}</td>
                                        <td>{{ $payment->payer_email}}</td>
                                        <td>{{ $payment->reservation->total_amount}}</td>
                                        <td>{{ $payment->transaction_id}}</td>
                                        <td>{{ $payment->payer_id }}</td>
                                        

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

   


    <!-- Modal Cancelar -->
<div class="modal fade" id="openCancel" tabindex="-1" aria-labelledby="cancelReservationLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title" id="cancelReservationLabel">Cancelar Reservación</h6>
                <button aria-label="Close" class="close" data-bs-dismiss="modal" type="button">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="cancelForm" method="POST">
                    @csrf
                    <!-- Campo oculto para almacenar el ID de la reservación -->
                    <input type="hidden" id="reservationId" name="reservation_id">

                    <!-- Textarea para el motivo de cancelación -->
                    <div class="form-group">
                        <label for="reservation_reason">Motivo de la cancelación</label>
                        <textarea 
                            class="form-control" 
                            id="reservation_reason" 
                            name="cancelation_reason" 
                            rows="4" 
                            placeholder="Escribe el motivo de la cancelación" 
                            required></textarea>       
                    </div>
                    <small id="reservationReasonError" class="text-danger d-none"></small>
                </form>
            </div>
            <div class="modal-footer">
                <!-- Botón para cerrar el modal -->
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cerrar</button>
                <!-- Botón para confirmar la cancelación -->
                <button class="btn btn-danger" type="button" onclick="submitCancel()">Cancelar</button>
            </div>
        </div>
    </div>
</div>

      

@endsection

@push('scripts')

    <script>
        //Validamos que solo permita elegir fecha a partir de hoy, NO fechas pasadas
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('reservation_date').setAttribute('min', today);

        /* Se determina el precio por defecto a 50, si se modifica el valor, se actualiza el total
        paso segido se aplica un evento change al campo start_time que al selecionar una hora inicial 
        automaticamente le sume 1 hora al campo end_time*/
        const pricePerHour = 50; // Define el precio por hora

        document.getElementById('start_time').addEventListener('change', function() {
            const startTime = this.value;

            if (startTime) {
                // Convertir la hora de inicio a un objeto Date
                const startDate = new Date(`1970-01-01T${startTime}:00`);

                // Añadir una hora mas
                startDate.setHours(startDate.getHours() + 1);

                // Formatear la nueva hora como HH:MM
                const endTime = startDate.toTimeString().slice(0, 5);

                // Establecer el valor de end_time
                document.getElementById('end_time').value = endTime;

                // Calcular el total (en este caso siempre será 1 hora, pero puedes ajustar según el tiempo)
                const total = pricePerHour; // Siempre será 1 hora, así que multiplica por el precio
                document.getElementById('total_amount').value = total.toFixed(2); // Actualizar el total
            } else {
                // Limpiar el campo end_time si no se selecciona una hora
                document.getElementById('end_time').value = "";
                document.getElementById('total_amount').value = "";
            }
        });

        
    </script>
    <!-- Internal form-elements js -->
  
    
    {{-- Cancelar reserva --}}
    <script>
        
       // Función para abrir el modal y asignar el ID de la reservación
        function openCancel(reservationId) {
            // Establece el ID de la reservación en el campo oculto del formulario
            document.getElementById('reservationId').value = reservationId;

            // Limpia el mensaje de error si existe
            document.getElementById('reservationReasonError').classList.add('d-none');
            document.getElementById('reservation_reason').value = '';

            // Inicializa el modal y lo muestra
            const modal = new bootstrap.Modal(document.getElementById('openCancel'));
            modal.show();
        }

        // Función para enviar la solicitud de cancelación
        function submitCancel() {
                // Obtiene los valores del formulario
                const reservationId = document.getElementById('reservationId').value;
                const reservationReason = document.getElementById('reservation_reason').value.trim();
                const errorElement = document.getElementById('reservationReasonError');

                // Validación: verifica que el campo de motivo no esté vacío
                if (!reservationReason) {
                    errorElement.textContent = 'El motivo de cancelación es obligatorio.';
                    errorElement.classList.remove('d-none');
                    return;
                }

                // Realiza la solicitud AJAX al servidor
                fetch(`/reservation/cancel`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        reservation_id: reservationId,
                        cancelation_reason: reservationReason
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la solicitud');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Reservación cancelada exitosamente.');
                        // Recarga la página para reflejar los cambios
                        location.reload();
                    } else {
                        alert('Ocurrió un error al cancelar la reservación.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al procesar la solicitud de cancelación.');
                });
        }

    </script>

     
@endpush
