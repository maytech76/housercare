@extends('admin.layouts.master')

@section('content')
<div class="row row-sm mt-2 mx-auto">
    <div class="main-container container-fluid">

        <!-- Breadcrumb header -->
        <div class="breadcrumb-header justify-content-between">
            <div class="my-auto">
                <h4 class="page-title">Invitados de: {{ $event->title }}  - <span style="color: #3039ec">N°: {{$event->id}}</span> </h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('events.index') }}">Eventos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Invitados</li>
                    
                </ol>
            </div>
        </div>

        <!-- Tarjeta resumen -->
        <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-body d-flex align-items-center justify-content-between">
                  <div>
                    <h5 class="mb-0">Resumen del Evento</h5>
                    <p class="mb-0">Fecha: {{ $event->event_date }}</p>
                    <p class="mb-0">Total Invitados: {{ $event->guests_count }} / {{ $event->limit_guest }}</p>
                  </div>
                  <div>
                    <a class="btn ripple btn-teal" data-bs-target="#select2modal" data-bs-toggle="modal" href="{{ route('guests.create') }}">+ Nuevo</a>
                  </div>
                </div>
              </div>
            </div>
        </div>

        <!-- Impunt importar excel -->
        <div class="row mt-2">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <form action="{{ route('guests.import') }}" method="POST" enctype="multipart/form-data" class="mb-3">
                            @csrf
                            <div class="input-group">
                                <input type="file" name="file" class="form-control" accept=".xlsx,.csv" required>
                                <button type="submit" class="btn btn-primary">
                                    Importar desde Excel <i class="fas fa-file-import"></i>
                                </button>
                            </div>
                            <small class="text-muted">El archivo debe tener las columnas: event_id, name, email</small>
                        </form>

                        <br>

                        <div class="row">
                            
                            <div class="col-12 d-flex justify-content-between">


                                <div class="">
                                    <a href="/exportar-invitados">Exportar a Excel</a>
                                </div>
        
                               <div class="">
                                    <!-- Botón de envío -->
                                    <form action="{{ route('events.send-mass-invitations', $event) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-envelope"></i> Enviar Invitaciones
                                        </button>
                                    </form>
        
                                    <!-- Mensaje de éxito -->
                                    @if (session('success'))
                                        <div class="alert alert-success mt-3">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                               </div>

                            </div>

                        </div>

                       
                        
                       
                    </div>
                </div>
            </div>
        </div>


        <!-- Tabla de invitados -->
        <div class="row mt-2">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table border-top-0  table-bordered text-nowrap border-bottom" id="responsive-datatable">
                                <thead>
                                    <tr>
                                        <th class="text-center">Foto</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        {{-- <th>Teléfono</th> --}}
                                        <th>Asistencia</th>
                                        <th class="justify-content-center">Estado</th>
                                        <th class="text-center">Invitación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($guests as $guest)
                                    <tr>
                                        <td class="text-center">
                                            <img src="{{ $guest->photo ? asset('storage/' . $guest->photo) : asset('guest_photos/user.jpg') }}" 
                                                 style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
                                        </td>
                                        <td>{{ $guest->name }} {{ $guest->last_name }}</td>
                                        <td>{{ $guest->email }}</td>
                                       {{--  <td>{{ $guest->phone }}</td> --}}
                                        <td>
                                            @if($guest->is_attended)
                                                <span class="py-2 px-3" style="color: #023505; background: #a0eda7">Confirmada</span>
                                            @else
                                                <span class="py-2 px-3" style="color: #454101; background: #f1ecac">Pendiente</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($guest->status == 'active')
                                                <span class="text-primary">activo</span>
                                            @elseif($guest->status == 'checkIn')
                                                <span class="text-success">CHECK-IN</span>
                                            @elseif($guest->status == 'CheckOut')
                                                <span class="text-danger">CHECK-OUT</span>
                                            @elseif($guest->status == 'retire')
                                                <span class="text-danger">RETIRADO</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($guest->email_sent == 1)
                                            <span class="text-success">Enviada</span>
                                            @else
                                            <span class="text-warning">Por enviar</span>
                                            @endif
                                        </td>


                                         {{-- --- Acciones --- --}}
                                        <td>
                                        
                                            <!-- Ver-Editar-Eliminar-->
                                            <button class="btn btn-sm btn-primary" onclick="showGuest({{ $guest->id }})">
                                                <span class="fe fe-image"></span>
                                            </button>
                                            
                                            <button id="bEdit" type="button" class="btn btn-sm btn-warning" onclick="openEditModal({{ $guest->id }})">
                                                <span class="fe fe-edit"></span>
                                            </button>
                                        
                                            <button class="btn btn-sm btn-danger delete-btn" 
                                                    data-id="{{ $guest->id }}" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal">
                                                    <span class="fe fe-trash-2"> </span>
                                            </button>

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


        <!-- Register Guest-->
        <div class="modal" id="select2modal">
            <div class="modal-dialog" category="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header header-custom"">
                        <h6 class="d-block mx-auto text-uppercase">Nuevo Invitado</h6>                      
                    </div>
                    <div class="modal-body">
                        <div class="col col-lg-12">
                        
                                <div class="m-2">
                                    <form action="{{ route('guests.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf


                                           <!-- Campo oculto para el event_id (se llenará con JS) -->
                                           <input type="hidden" name="event_id" id="event_id" value="{{ $event->id }}">

                                        <!-- Name -->
                                        <div class="form-group">
                                            <label for="name" class="fw-normal">{{ __('Nombre del Invitado :') }}</label>
                                            <input type="text" name="name" id="name" class="form-control border border-success" placeholder="Ingresar Nombre del invitado">
                                        </div>

                                        <!-- Last_Name -->
                                        <div class="form-group">
                                            <label for="last_name" class="fw-normal">{{ __('Apellidos del Invitado :') }}</label>
                                            <input type="text" name="last_name" id="last_name" class="form-control border border-success"  placeholder="Ingresar Apellidos del invitado">
                                        </div>

                                        <!-- Email -->
                                        <div class="form-group">
                                            <label for="email" class="fw-normal">{{ __('Correo del Invitado :') }}</label>
                                            <input type="text" name="email" id="email" class="form-control border border-success"  placeholder="Ingresarl el correo del invitado">
                                        </div>

                                        <!-- Phone -->
                                       {{--  <div class="form-group">
                                            <label for="phone" class="fw-normal">{{ __('Telefono :') }}</label>
                                            <input type="text" name="phone" id="phone" class="form-control" placeholder="Telefono del invitado">
                                        </div> --}}


                                        <!-- Buttons-->
                                        <div class="modal-footer">
                                           
                                            <button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Cerrar</button>

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


        {{-- Editar Guest --}}
        <div class="modal fade" id="guestEditModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down modal-xl-custom">
                <div class="modal-content">
                    <div class="modal-header header-custom-edit">
                        <h6 class="d-block mx-auto text-uppercase">Edición Invitado</h6>
                    </div>
                    <div class="modal-body">
                        <form id="editGuestForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="editGuestId" name="id">
        
                            <div class="row">
                                {{-- columna derecha --}}
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="editName">Nombre :</label>
                                        <input id="editName" type="text" name="name" class="form-control border border-warning">
                                    </div>
        
                                    <div class="form-group">
                                        <label for="editLastName">Apellidos :</label>
                                        <input id="editLastName" type="text" name="last_name" class="form-control border border-warning">
                                    </div>
            
                                    <div class="form-group">
                                        <label for="editEmail">Correo :</label>
                                        <input type="mail" id="editEmail" name="email" class="form-control border border-warning">
                                    </div>
            
                                    <div class="form-group">
                                        <label for="editAsistencia">Asistencia :</label>
                                        <select id="editAsistencia" name="is_attended" class="form-control border border-warning">
                                            <option value="0">Pendiente</option>
                                            <option value="1">Asistirá</option>
                                        </select>
                                    </div>
        
                                   
                                </div>
        
                                {{-- Columna Izquierda --}}
                                <div class="col-12 col-md-6">

                                    <div class="form-group">
                                        <label for="editStatus">Estado :</label>
                                        <select id="editStatus" name="status" class="form-control border border-warning">
                                            <option value="active">Activo</option>
                                            <option value="checkIn">CheckIn</option>    
                                            <option value="CheckOut">CheckOut</option>
                                            <option value="retire">Retirado</option>
                                        </select>
                                    </div>
        
                                    <div class="form-group">
                                        <label for="editInvitacion">Invitación :</label>
                                        <select id="editInvitacion" name="email_sent" class="form-control border border-warning">
                                            <option value="0">Por enviar</option>
                                            <option value="1">Enviada</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="editDate">Fecha del evento</label>
                                        <input type="date" id="editDate" name="event_date" class="form-control border border-secondary" readonly>
                                    </div>
        
                                    <div class="form-group">
                                        <label for="editStartime">Hora de inicio</label>
                                        <input type="time" id="editStartime" name="start_time" class="form-control border border-secondary" readonly>
                                    </div>
        
                                    {{-- <div class="form-group">
                                        <label for="editEndtime">Hora de culminación</label>
                                        <input type="time" id="editEndtime" name="end_time" class="form-control border border-secondary">
                                    </div> --}}
                                </div>
                            </div>
                
                            <div class="modal-footer">
                                <button class="btn ripple btn-danger" data-bs-dismiss="modal" type="button">Cerrar</button>
                                <button type="submit" class="btn ripple btn-warning">Editar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

   
       
        <!-- Modal Guest Details -->
        <div class="modal fade shadow-none" id="showGuest" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog shadow-none" category="document">
               

                <div class="bg-transparent border-0 shadow-none">
                        <div class="col-sm-12">
                            
                            <div class="card user-wideget user-wideget-widget widget-user bg-white">

                                <div class="widget-user-header bg-primary">
                                    <h3 class="widget-user-username" id="guestName">Cargando...</h3>
                                    <h5 class="widget-user-desc">Invitado</h5>
                                </div>
                                
                                <div class="widget-user-image mb-4">
                                    <img id="guestPhoto" 
                                        class="brround" 
                                        alt="Foto del invitado" 
                                        style="width: 100px; height: 100px; object-fit: cover;"
                                        onerror="this.src='/storage/guest_photos/user.jpg'">
                                </div>

                                <div class="user-wideget-footer my-4">
                                    <div class="row">
                                        <div class="col-sm-4 border-end">
                                            <div class="description-block">
                                                <h5 class="description-header">Teléfono:</h5>
                                                <span class="description-text lowercase" id="guestPhone">Cargando...</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 border-end">
                                            <div class="description-block">
                                                <h5 class="description-header">Email:</h5>
                                                <span class="description-text text-lowercase" id="guestEmail">Cargando...</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="description-block">
                                                <h5 class="description-header">Asistencia</h5>
                                                <span class="description-text lowercase" id="guestAttendance">Cargando...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="user-wideget-footer">
                                    <div class="row">
                                        <div class="col-sm-4 border-end">
                                            <div class="description-block">
                                                <h5 class="description-header">Invitación</h5>
                                                <span class="description-text lowercase" id="guestInvitation">Cargando...</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 border-end">
                                            <div class="description-block">
                                                <h5 class="description-header text-success">CheckIn</h5>
                                                <span class="lowercase" style="color: #023505" id="guestCheckIn">-</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="description-block">
                                                <h5 class="description-header text-danger">CheckOut</h5>
                                                <span class="lowercase" style="color: #350202" id="guestCheckOut">-</span>
                                            </div>
                                        </div> 

                                        <hr>

                                        <div class="my-4">
                                            <button class="btn btn-outline-danger btn-rounded  btn-block">Cerrar</button>
                                        </div>

                                    </div>
                                </div>          

                            </div>

                     </div>
                </div>
    
            </div>
        </div>
        <!-- Final Modal Guest Details -->


        
         {{-- Modal Eliminar --}}
        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header header-custom-delete">
                <h5 class="modal-title">Eliminar Registro</h5>
                </div>
                <div class="modal-body">
                ¿Estás seguro de eliminar este registro?
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Eliminar</button>
                </div>
            </div>
            </div>
        </div> 
         {{-- Final Modal Eliminar --}}


        <style>

            /* Colores para bootones del campo satus */
        


            /* Eliminar sombras y fondos no deseados */
            #showGuest .modal-dialog {
                background: transparent;
                box-shadow: none;
            }

            #showGuest .card {
                box-shadow: none !important;
                border: none !important;
                background-color: transparent;
            }

            #showGuest .widget-user {
                background-color: transparent;
            }

            /* Opcional: ajustar el fondo del modal si es necesario */
            #showGuest .modal-content {
                background: transparent;
                border: none;
            }

                        /* Estilos para los bordes de los inputs */
            .border-warning {
                border-color: #ffc107 !important;
            }

            .border-secondary {
                border-color: #6c757d !important;
            }

            /* Estilos para el modal de edición */
            #openEditModal .modal-header {
                background-color: #f0e1c1;
                border-bottom: 1px solid #dee2e6;
            }

            #openEditModal .modal-footer {
                border-top: 1px solid #dee2e6;
            }

            /* Estilos para los toast */
            .toast {
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 1100;
            }

            /***************** COLORS HEADER MODALES *****************/

            .header-custom {
                background-color: #d6f5d9; /* Verde pastel */
                border-bottom: 1px solid #a3f1a6; /* Borde sutil */
                
            }

            .header-custom-edit {
                background-color: #f2f5d6; /* Verde pastel */
                border-bottom: 1px solid #ecf1a3; /* Borde sutil */
            }

            .header-custom-delete {
                background-color: #f5d6d6; /* Verde pastel */
                border-bottom: 1px solid #f1a3a3; /* Borde sutil */
            }
        </style>

            
        </div>
    </div>

@push('scripts')

    <script src="../assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>

     {{-- Registro new Events --}}
    <script>
        $(document).ready(function() {
            // Opción 1: Si ya tienes $event->id disponible en la vista, úsalo directamente
            $('#event_id').val("{{ $event->id }}");
            
            // Opción 2: Si prefieres extraerlo de la URL (compatible con rutas como /events/123/guests)
            // const eventId = window.location.pathname.split('/')[2];
            // $('#event_id').val(eventId);
            
            // Actualiza la acción del formulario si es necesario
            $('#guestForm').attr('action', '/events/' + "{{ $event->id }}" + '/guests');
        });
    </script>

    {{-- Show-Guest --}}
    <script>
    
            function showGuest(guestId) {
            // Configuración inicial
            const modal = document.getElementById('showGuest');
            const modalInstance = new bootstrap.Modal(modal);
            const defaultPhoto = "{{ asset('guest_photos/user.jpg') }}";
            
            // Mostrar estados de carga
            setLoadingState();
            modalInstance.show();

            // Fetch datos del invitado
            fetch(`/guests/${guestId}/show`)
                .then(handleResponse)
                .then(data => updateModal(data.guest, defaultPhoto))
                .catch(error => handleError(error, defaultPhoto));

            // Función para establecer estado de carga
            function setLoadingState() {
                document.getElementById('guestName').textContent = 'Cargando...';
                document.getElementById('guestPhone').textContent = 'Cargando...';
                document.getElementById('guestEmail').textContent = 'Cargando...';
                document.getElementById('guestAttendance').innerHTML = '<span class="text-muted">Cargando...</span>';
                document.getElementById('guestInvitation').innerHTML = '<span class="text-muted">Cargando...</span>';
                document.getElementById('guestPhoto').src = defaultPhoto;
            }

            // Función para manejar la respuesta
            function handleResponse(response) {
                if (!response.ok) throw new Error('Error en la respuesta del servidor');
                return response.json();
            }


           /*  Formato para hora am-pm */
            function formatTimeToAMPM(timeString) {
                if (!timeString) return '';
                
                // Extraer horas, minutos y segundos
                const [hours, minutes, seconds] = timeString.split(':');
                
                // Convertir a formato 12h
                const period = hours >= 12 ? 'pm' : 'am';
                const hours12 = hours % 12 || 12; // Convierte 0 a 12
                
                return `${hours12}:${minutes}${period}`;
            }

            // Función para actualizar el modal con los datos
            function updateModal(guest, defaultPhoto) {
                if (!guest) throw new Error('Datos del invitado no disponibles');
                
                // Actualizar datos básicos
                document.getElementById('guestName').textContent = `${guest.name} ${guest.last_name}`;
                document.getElementById('guestPhone').textContent = guest.phone || 'No disponible';
                document.getElementById('guestEmail').textContent = guest.email;
                
                // Manejar foto del invitado
                const photoElement = document.getElementById('guestPhoto');
                photoElement.alt = `Foto de ${guest.name}`;
                
                if (guest.photo) {
                    photoElement.src = `/storage/${guest.photo}`;
                    photoElement.onerror = () => { photoElement.src = defaultPhoto };
                } else {
                    photoElement.src = defaultPhoto;
                }
                
                // Actualizar estados
                document.getElementById('guestAttendance').innerHTML = guest.is_attended ? 
                    '<span style="color: #023505; background: #ffffff" class="py-1 px-2">Confirmada</span>' : 
                    '<span style="color: #454101; background: #f1ecac" class="py-1 px-2">Pendiente</span>';
                
                document.getElementById('guestInvitation').innerHTML = guest.email_sent ? 
                    '<span class="text-success">Enviada</span>' : 
                    '<span class="text-warning">Por enviar</span>';
                
                // Actualizar check-in/out
                if (guest.checkin_time) {
                    const timePart = guest.checkin_time.split(' ')[1];
                    document.getElementById('guestCheckIn').textContent = formatTimeToAMPM(timePart);
                }

                if (guest.checkout_time) {
                    const timePart = guest.checkout_time.split(' ')[1];
                    document.getElementById('guestCheckOut').textContent = formatTimeToAMPM(timePart);
                }
            }

            // Función para manejar errores
            function handleError(error, defaultPhoto) {
                console.error('Error:', error);
                document.getElementById('guestName').textContent = 'Error';
                document.getElementById('guestEmail').textContent = error.message || 'Error al cargar datos';
                document.getElementById('guestPhoto').src = defaultPhoto;
                
                // Mostrar notificación de error
                showErrorAlert(error.message);
            }

            // Función para mostrar alerta de error
            function showErrorAlert(message) {
                const alertHTML = `
                    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        ${message || 'Ocurrió un error al cargar los datos'}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                
                const modalBody = document.querySelector('#showGuest .modal-body');
                modalBody.insertAdjacentHTML('afterbegin', alertHTML);
            }
        }

    </script>

    {{-- Editar Reg-Invitado --}}
    <script>
        
       // Función para abrir el modal de edición de invitado
        function openEditModal(guestId) {
            fetch(`/guests/${guestId}/edit`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al cargar los datos del invitado');
                    }
                    return response.json();
                })
                .then(data => {
                    const guest = data.guest;
                    
                    // Actualizar la acción del formulario
                    const form = document.getElementById('editGuestForm');
                    if (!form) {
                        throw new Error('Formulario no encontrado');
                    }
                    form.action = `/guests/${guest.id}`;
                    
                    // Llenar campos del formulario
                    document.getElementById('editGuestId').value = guest.id;
                    document.getElementById('editName').value = guest.name || '';
                    document.getElementById('editLastName').value = guest.last_name || '';
                    document.getElementById('editEmail').value = guest.email || '';
                    
                    // Seleccionar valores en los selects
                    const asistenciaSelect = document.getElementById('editAsistencia');
                    const statusSelect = document.getElementById('editStatus');
                    const invitacionSelect = document.getElementById('editInvitacion');
                    
                    if (asistenciaSelect) asistenciaSelect.value = guest.is_attended || '0';
                    if (statusSelect) statusSelect.value = guest.status || 'active';
                    if (invitacionSelect) invitacionSelect.value = guest.email_sent || '0';
                    
                    // Datos del evento asociado
                    document.getElementById('editDate').value = guest.event_date || '';
                    document.getElementById('editStartime').value = guest.start_time || '';
                    /* document.getElementById('editEndtime').value = guest.end_time || ''; */
                    
                    // Mostrar el modal
                    const editModal = new bootstrap.Modal(document.getElementById('guestEditModal'));
                    editModal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cargar los datos del invitado: ' + error.message);
                });
        }

        // Manejar el envío del formulario
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('editGuestForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const submitButton = this.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;
                    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...';
                    submitButton.disabled = true;
                    
                    const formData = new FormData(this);
                    formData.append('_method', 'PUT');
                    
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw err; });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            location.reload(); // Recargar para ver cambios
                        } else {
                            throw new Error(data.message || 'Error al actualizar el invitado');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al actualizar: ' + (error.message || 'Por favor verifique los datos'));
                    })
                    .finally(() => {
                        submitButton.innerHTML = originalText;
                        submitButton.disabled = false;
                    });
                });
            }
        });

    </script>

    {{-- Validaciones --}}
    <script>
        // Configuración de validación permisiva para campos opcionales
        function setupOptionalValidation() {
            // Campos requeridos
            setupValidation('editName', 'nameError', 'Nombre es requerido');
            setupValidation('editEmail', 'emailError', 'Email válido es requerido', true);
            
            // Campos opcionales
            setupValidation('editPhone', 'phoneError', 'Solo números y caracteres + -', false, /^[\d\+\-\s]*$/);
            setupValidation('editLastname', 'lastnameError', '', false);
        }

        function setupValidation(inputId, errorId, message = '', isRequired = true, regex = null) {
            const input = document.getElementById(inputId);
            if (!input) return;

            const errorElement = document.createElement('small');
            errorElement.id = errorId;
            errorElement.className = 'form-text text-danger d-none';
            errorElement.textContent = message;
            input.parentNode.appendChild(errorElement);

            input.addEventListener('blur', function() {
                let isValid = true;
                
                if (isRequired && !this.value.trim()) {
                    isValid = false;
                }
                
                if (regex && this.value && !regex.test(this.value)) {
                    isValid = false;
                }
                
                if (inputId === 'editEmail' && this.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value)) {
                    isValid = false;
                }

                errorElement.classList.toggle('d-none', isValid);
                this.classList.toggle('is-invalid', !isValid);
            });
        }

        // Inicializar al cargar la página
        document.addEventListener('DOMContentLoaded', setupOptionalValidation);
    </script>

    {{-- Eliminar registro --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        let deleteId = null;
        
        // Abrir modal y guardar ID
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
            deleteId = this.getAttribute('data-id');
            });
        });

        // Confirmar eliminación
        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (!deleteId) return;
            
            fetch(`/guests/${deleteId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
            })
            .then(response => response.json())
            .then(data => {
            if (data.success) {
                location.reload(); // Recargar página después de eliminar
            }
            })
            .catch(error => console.error('Error:', error));
        });
        });
    </script>

   


@endpush
@endsection