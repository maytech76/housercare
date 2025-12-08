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
                    <h3 class="card-title">Listado de Reservas</h3>

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
                                    <th class="wd-25p border-bottom-0 bg-light">Transacción</th>
                                    <th class="wd-25p border-bottom-0 bg-light">Reserva</th>
                                    <th class="wd-25p border-bottom-0 bg-light">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservations as $reservation)
                                    <tr>
                                        <td>{{$reservation->id}} </td>
                                        <td class="fw-light">{{ $reservation->user->name }}
                                            {{ $reservation->user->last_name }}</td>
                                        <td class="fw-light">{{ $reservation->consultant->name }}
                                            {{ $reservation->consultant->last_name }}</td>
                                        <td>{{ $reservation->formatted_reservation_date }}</td>
                                        <td>{{ $reservation->start_time }}</td>
                                        <td>{{ $reservation->end_time }}</td>
                                        <td>
                                            @if ($reservation->payment_status == 'pendiente')
                                                <span class="text-warning">pendiente</span>
                                            @elseif($reservation->payment_status == 'pagado')
                                                <span class="text-success">pagado</span>
                                            @elseif($reservation->payment_status == 'fallido')
                                                <span class="text-danger">fallido</span>
                                            @else
                                                <span class="text-secondary">desconocido</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($reservation->status == 'cancelada')
                                                <span class="badge p-2 btn-rounded" style="background-color: #f6a6a6ca; border: 1px solid #9b0303; padding: 4px; border-radius: 15px; color:#710303">cancelada</span>
                                            @elseif ($reservation->status == 'finalizada')
                                                <span class="badge p-2 btn-rounded" style="background-color: #e7a97ad0; border: 1px solid #dd6a06; padding: 4px; border-radius: 15px; color:#aa5103">finalizada</span>
                                            @elseif($reservation->status == 'confirmada')
                                                <span class="badge p-2 btn-rounded" style="background-color: #96f4b4; border: 1px solid #06bd03; padding: 4px; border-radius: 15px; color:#000">confirmada</span>
                                            @else
                                                <span class="badge p-2 btn-rounded" style="background-color: #ecec5e; border: 1px solid #ddbc06; padding: 4px; border-radius: 15px; color:#000">pendiente</span>
                                            @endif
                                        </td>
                                        <td>
                                           
                                           @if ($reservation->status == 'cancelada')

                                                <button class="btn btn-warning btn-sm" style="background-color: #f2f2a6; border: 1px solid #9b8404; color:#635d5d" disabled>Editar</button>
                                                            
                                                <button class="btn btn-danger btn-sm btn-cancel" onclick="openCancel({{ $reservation->id }})" style="background-color: #f08989; border: 1px solid #bd0202; color:#fff" disabled>Cancelar</button>
                                                    
                                           @else

                                                <a class="btn btn-warning btn-sm" href="{{ route('reservations.edit', $reservation->id) }}" style="color: #000">Editar</a>
                                                    
                                                <button class="btn btn-danger btn-sm btn-cancel" onclick="openCancel({{ $reservation->id }})" >Cancelar</button>

                                           @endif
                                           
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Registro Reserva-->
    <div class="modal" id="select3modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Registrar Reserva</h6><button aria-label="Close" class="close"
                        data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                    <div class="modal-body">
                        <div class="col col-lg-12">

                            <div class="m-2">
                                <form id="reservationForm">
                                    @csrf

                                    <div class="row">
                                        
                                        <!-- id del Usuario -->
                                        <div class="form-group col-12 col-md-6">
                                            <label for="user_id" class="fw-normal">{{ __('Usuario') }}</label>
                                                <input id="user_id" name="user_id" type="hidden" class="form-control" value="{{ Auth::user()->id }}">
                                                <input type="text" value="{{ Auth::user()->name }} {{Auth::user()->last_name}}" readonly>
                                        </div>

                                        <!-- Selección de Consultor -->
                                        <div class="form-group col-12 col-md-6">
                                            <label for="user_id" class="fw-normal">{{ __('Consultor') }}</label>
                                            <select name="consultant_id" id="consultant_id" class="form-control" required>
                                                <option value="" selected disabled>Seleciona un Consultor</option>
                                                @foreach ($consultants as $consultant)
                                                    <option value="{{ $consultant->id }}">{{ $consultant->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('consultant_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Fecha de la reserva -->
                                        <div class="form-group col-12 col-md-6">
                                            <label for="reservation_date"
                                                class="fw-normal">{{ __('Fecha de reserva') }}</label>
                                            <div class="input-group">

                                                <input class="form-control" name="reservation_date" id="reservation_date"
                                                    placeholder="MM/DD/YYYY" type="date">

                                            </div>
                                        </div>

                                        <!-- Hora Inicio -->
                                        <div class="form-group col-12 col-md-6">
                                            <label for="start_time" class="fw-normal">{{ __('Hora Inicio') }}</label>
                                            <select name="start_time" id="start_time" class="form-control" required>

                                                <option value="">Seleccionar</option>
                                                <option value="09:00">09:00</option>
                                                <option value="10:00">10:00</option>
                                                <option value="11:00">11:00</option>
                                                <option value="12:00">12:00</option>
                                                <option value="13:00">13:00</option>
                                                <option value="14:00">14:00</option>
                                                <option value="15:00">15:00</option>
                                                <option value="16:00">16:00</option>

                                            </select>
                                            @error('end_time')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <!-- Hora Final -->
                                        <div class="form-group col-12 col-md-6">
                                            <label for="end_time" class="fw-normal">{{ __('Hora Fin') }}</label>
                                            <input type="text" class="form-control @error('end_time') is-invalid @enderror"
                                                id="end_time" name="end_time" readonly>
                                            @error('end_time')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <!-- Status -->
                                        <div class="form-group col-12 col-md-6">
                                            <label for="status" class="fw-normal">{{ __('Estado de reserva') }}</label>
                                            <select name="status" id=status" class="form-control" required>
                                                <option value="">Selecione</option>
                                                <option value="pendiente">Pendiente</option>
                                                <option value="confirmada">Confirmada</option>
                                            </select>
                                            @error('status')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>


                                        <!-- Estado del pago-->
                                        <div class="form-group col-12 col-md-6">
                                            <label for="payment_status" class="fw-normal">{{ __('Estado del Pago') }}</label>
                                            <select name="payment_status" id=payment_status" class="form-control" required>

                                                <option value="">Selecione</option>
                                                <option value="pendiente">Pendiente</option>
                                                <option value="pagado">Pagado</option>
                                                <option value="fallido">Fallido</option>

                                            </select>
                                            @error('total_amount')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        {{-- Total a pagar --}}
                                        <div class="col-12 col-md-6">

                                            <div class="row align-items-center mb-3">

                                                <div class="col-2 mx-0">
                                                    <label for="total_amount" class="form-label mb-0">Total</label>
                                                </div>


                                                <div class="col-10">
                                                    <input type="text"
                                                        class="form-control rounded @error('total_amount') is-invalid @enderror"
                                                        id="total_amount" name="total_amount" readonly>
                                                </div>

                                            </div>

                                            @error('total_amount')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror

                                        </div>

                                        <div class="col-12">
                                            <label for="fw-normal">Metodo de Pago</label>
                                            <div id="paypal-button-container"></div>

                                        </div>

                                    </div>

                                    <!-- Botones -->
                                    <div class="modal-footer mt-3 d-flex justify-content-between">
                                        <button class="btn ripple btn-danger" data-bs-dismiss="modal"
                                            type="button">Cerrar</button>
                                        <button class="btn ripple btn-success" type="submit">Registrar</button>
                                    </div> 

                                </form>
                            </div>


                        </div>
                    </div>

            </div>
        </div>
    </div>
    <!-- End Basic modal -->


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
    
<script src="https://www.paypal.com/sdk/js?client-id=AZcqKzUD38WcgAL0dOW7v97VmS831oH6LFs6B1o33FbFVU6QqFLXRbAuFHlWqEdrL9Dy-E2uSjdZPXLk&currency=USD"></script>

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

          // Inicializa el botón de PayPal al cargar el DOM
          document.addEventListener('DOMContentLoaded', function() {
            paypal.Buttons({
                // Método que se ejecuta cuando se crea una orden de pago
                createOrder: function(data, actions) {
                    
                    //Ejecutamos la recepcion de datos que vienen desde el formulario y alamacenamos en variables
                    var consultantId = document.getElementById('consultant_id').value;
                    var reservationDate = document.getElementById('reservation_date').value;
                    var startTime = document.getElementById('start_time').value;
                    var totalAmount = document.getElementById('total_amount').value;

                    // Validación para verificar que todos los campos obligatorios estén completos
                    if (!consultantId || !reservationDate || !startTime || !totalAmount) {
                        Swal.fire({
                            icon: 'warning', // Muestra una advertencia si faltan campos
                            title: 'Campos Incompletos',
                            text: 'Por Favor, completa todos los campos obligatorios',
                        });
                        return false; // Detiene el proceso si hay campos incompletos
                    } 

                    // Crea la orden de PayPal con el monto total
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: totalAmount /* totalAmount */ // Monto total de la orden
                            }
                        }]
                    })
                },
                // Método que se ejecuta cuando el pago ha sido aprobado
                onApprove: function(data, actions) {
                    /* console.log(data); */
                    return actions.order.capture().then(function(details) {

                         // Realiza una solicitud POST al servidor para completar la reserva
                        return fetch('/paypal', {

                            method: 'post',
                            headers: {
                                'content-type': 'application/json', // Declaramos el tipo de datos que estamos enviando
                                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Añade el token CSRF para la seguridad de Laravel
                            },
                            body: JSON.stringify({
                                orderID: data.orderID, // ID de la orden de PayPal
                                details: details, // Detalles del pago de PayPal
                                user_id: {{ auth()->user()->id }}, // ID del usuario autenticado
                                consultant_id: document.getElementById('consultant_id').value, // ID del consultor
                                reservation_date: document.getElementById('reservation_date').value, // Fecha de la reserva
                                start_time: document.getElementById('start_time').value, // Hora de inicio de la reserva
                                end_time: document.getElementById('end_time').value, // Hora de fin de la reserva
                                total_amount: document.getElementById('total_amount').value, // Monto total de la reserva
                            })
                        }).then(function(response) {
                            if (response.ok) {
                                // Si la respuesta es exitosa, muestra un mensaje de éxito
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Pago Completado',
                                    text: 'Pago Completado y reserva creada correctamente',
                                    showConfirmButton: false,
                                    timer: 1500

                                }).then(function() {

                                    window.location.href = '/cliente/reservas'; // Redirige a la página de reservas del cliente

                                    // Limpiar los inputs
                                       /*  document.getElementById('consultant_id').value = '';
                                        document.getElementById('reservation_date').value = '';
                                        document.getElementById('start_time').value = '';
                                        document.getElementById('end_time').value = '';
                                        document.getElementById('total_amount').value = ''; */

                                         // Cerrar el modal
                                       /*  if (typeof $('#select3modal').modal === 'function') {
                                            $('#select3modal').modal('hide'); // Cambia 'modalId' por el ID de tu modal
                                        } */

                                       // Cerrar ventana del botón de PayPal (si aplica)
                                        /* const paypalWindow = document.getElementById('paypal-button-container');
                                        if (paypalWindow) {
                                            paypalWindow.innerHTML = ''; // Limpia el contenedor del botón de PayPal
                                        } */
                                });
                            } else {
                                // Si hay un error en el pago, muestra un mensaje de error
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error al procesar el pago',
                                });
                            }
                        });
                    });
    
             }

            }).render('#paypal-button-container'); // Renderiza el botón de PayPal en el contenedor
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

    <!--Internal  Sweet-Alert js-->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<!-- Sweet-alert js  -->
	

     
@endpush