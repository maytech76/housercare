@extends('admin.layouts.master')

@section('content')


        {{-- Table of all eventss --}}
        <div class="row row-sm mt-4 mx-auto">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Eventos para grupos de invitados</h3>
                        {{-- <button type="button" class="btn btn-success-gradient btn-w-xs mb-1"> + Usuario</button> --}}
                        <a class="btn btn-danger bg-danger-gradient btn-w-sm mb-1"  data-bs-target="#select2modal" data-bs-toggle="modal" href="{{route('events.create')}} ">+ Nuevo</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table border-top-0  table-bordered text-nowrap border-bottom" id="responsive-datatable">
                                <thead>
                                    <tr>
                                        <th class="wd-15p border-bottom-0 bg-light">ID</th>
                                        <th class="wd-15p border-bottom-0 bg-light">Organizador</th>
                                        <th class="wd-15p border-bottom-0 bg-light">Titulo</th>
                                        <th class="wd-20p border-bottom-0 bg-light">Categoria</th>
                                        <th class="wd-20p border-bottom-0 bg-light">Precio</th>
                                        <th class="wd-20p border-bottom-0 bg-light">Fecha</th>
                                        <th class="wd-20p border-bottom-0 bg-light">Hora Inicio</th>
                                        <th class="wd-20p border-bottom-0 bg-light">Estado</th>
                                        <th class="wd-25p border-bottom-0 bg-light">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($events as $event)

                                     @php
                                        $statusKey = strtolower(trim($event->status));
                                        $statusClass = $statusClasses[$statusKey] ?? 'btn-secondary';
                                    @endphp 

                                    <tr >
                                        <td>{{$event->id}} </td>
                                        <td class="fw-light">{{$event->user->name}}</td>
                                        <td class="fw-light">{{$event->title}}</td>
                                        <td class="fw-light">{{$event->category->name}}</td>
                                        <td class="fw-light">{{$event->price}}</td>
                                        <td class="fw-light">{{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d-F-Y') }}</td>
                                    {{--     <td class="fw-light">{{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d \d\e F \d\e Y') }}</td> --}}


                                        <td class="fw-light">{{$event->start_time}}</td>
                                    
                                      {{--   <td>{{ $event->status }}</td> --}}

                                         <td class="fw-light text-center">
                                            <span class="btn {{ $event->status_class }} btn-sm">
                                                {{ trim($event->status) }}
                                            </span>
                                        </td>   
                                        
                                        
                                        <td>
                                        
                                            <!-- Otros botones -->
                                            <button class="btn btn-sm btn-primary" onclick="openImagesModal({{ $event->id }})">
                                                <span class="fe fe-image"></span>
                                            </button>
                                            
                                            <button id="bEdit" type="button" class="btn btn-sm btn-warning"  onclick="openEditModal({{ $event->id }})">
                                                <span class="fe fe-edit"></span>
                                            </button>
                                        
                                            <button id="bDel" type="button"  class="btn  btn-sm btn-danger" data-id="{{ $event->id }}"
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


        
        @php
            $statusClasses = [

                'reservado' => 'btn-reserva-light',
                'activo' => 'btn-activo-light',
                'culminado'    => 'btn-culminado-light',
                'cancelado'  => 'btn-cancelado-light',
            ];
        @endphp

       


        <!-- Estilos para Modales -->
        <style>

            /* Colores para bootones del campo satus */
            .btn-activo-light {
                background-color: #96f4b4; /* verde claro tipo alerta success */
                color: #035801;
                border: 1px solid #66c07b;
                padding: 0px, 2px;
                
            }

            .btn-reserva-light {
                background-color: #f0e1c1; /* verde claro tipo alerta success */
                color: #dd6a06;
                border: 1px solid #c0aa66;
                padding: 0px, 2px;
            }

            .btn-culminado-light {
                background-color: #f0eeeb; /* verde claro tipo alerta success */
                color: #414040;
                border: 1px solid #aaa9a8;
                padding: 0px, 2px;
            }

            .btn-cancelado-light {
                background-color: #f6a6a6ca; /* rosa claro tipo alerta danger */
                color: #9b0303;
                border: 1px solid #aea3a3;
                padding: 0px, 2px;
            }



            /* Color para encabezados de Modales */
            @media (max-width: 576px) {
                .modal-dialog {
                    margin: 0.5rem;
                }
        
                .modal-content {
                    border-radius: 1rem;
                }
            }
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

            /* Tamaño para modals */
            @media (min-width: 768px) {
                .modal-xl-custom {
                    max-width: 770px;
                }
            }
        </style>

        
       
        <!-- Modal Registrar Evento --> 
        <div class="modal fade" id="select2modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down modal-xl-custom">
                <div class="modal-content">
                    <div class="modal-header header-custom">
                        <h6 class="d-block mx-auto text-uppercase">Registo de Nuevo Evento</h6>
                    </div>
        
                    <div class="modal-body">
                        <form action="{{ route('events2.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
        
                                <!-- Columna izquierda -->
                                <div class="col-12 col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="user_id">Organizador</label>
                                        <select name="user_id" class="form-control border border-secondary" required>
                                            <option value="" disabled selected>Selecciona un Organizador</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
        
                                    <div class="form-group mb-3">
                                        <label for="category_id">Categoría</label>
                                        <select name="category_id" class="form-control border border-secondary" required>
                                            <option value="" disabled selected>Selecciona una Categoría</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
        
                                    <div class="form-group mb-3">
                                        <label for="price">Precio acordado:</label>
                                        <input type="hidden" name="type" value="grupo">
                                        <input id="editPrice" type="text" name="price" class="form-control border border-secondary" placeholder="Ingresa el precio: 200">
                                        <small id="errorPrice" class="form-text text-danger" style="display: none;">Solo se permiten números</small>
                                    </div>
        
                                    <div class="form-group mb-3">
                                        <label for="price">Titulo del Evento:</label>
                                        <input type="text" name="title" class="form-control border border-secondary" placeholder="Título del evento">
                                    </div>
        
                                    <div class="form-group mb-3">
                                        <label for="price">Descripción:</label>
                                        <input type="text" name="description" class="form-control border border-secondary" placeholder="Descripción">
                                    </div>
        
                                </div>
        
                                <!-- Columna derecha -->
                                <div class="col-12 col-md-6">

                                    <div class="form-group mb-3">
                                        <label for="price">Fecha:</label>
                                        <input type="date" name="event_date" class="form-control border border-secondary">
                                    </div>
        
                                    {{-- Hora de inicio --}}
                                    <div class="form-group mb-3">
                                        <label for="start_time">Hora Inicio</label>
                                        <select name="start_time" class="form-control border border-secondary" required>
                                            <option value="">Seleccionar</option>
                                            <option value="07:00">07:00 am</option>
                                            <option value="08:00">08:00 am</option>
                                            <option value="09:00">09:00 am</option>
                                            <option value="10:00">10:00 am</option>
                                            <option value="11:00">11:00 am</option>
                                            <option value="12:00">12:00 pm</option>
                                            <option value="01:00">01:00 pm</option>
                                            <option value="02:00">02:00 pm</option>
                                            <option value="03:00">03:00 pm</option>
                                            <option value="04:00">04:00 pm</option>
                                            <option value="05:00">05:00 pm</option>
                                            <option value="06:00">06:00 pm</option>
                                            <option value="07:00">07:00 pm</option>
                                            <option value="08:00">08:00 pm</option>
                                            <option value="09:00">09:00 pm</option>
                                            <option value="10:00">10:00 pm</option>
                                            <option value="11:00">11:00 pm</option>
                                            <option value="12:00">12:00 am</option>
                                            <option value="01:00">01:00 am</option>
                                            <option value="02:00">02:00 am</option>
                                            <option value="03:00">03:00 am</option>
                                            <option value="04:00">04:00 am</option>
                                            <option value="05:00">05:00 am</option>
                                            <option value="06:00">06:00 am</option>
                                            <!-- etc... -->
                                        </select>
                                    </div>
        
                                   {{--  hora fin --}}
                                    <div class="form-group mb-3">
                                        <label for="end_time">Hora Fin</label>
                                        <select name="end_time" class="form-control border border-secondary" required>
                                            
                                            <option value="">Seleccionar</option>
                                            <option value="07:00">07:00 am</option>
                                            <option value="08:00">08:00 am</option>
                                            <option value="09:00">09:00 am</option>
                                            <option value="10:00">10:00 am</option>
                                            <option value="11:00">11:00 am</option>
                                            <option value="12:00">12:00 pm</option>
                                            <option value="01:00">01:00 pm</option>
                                            <option value="02:00">02:00 pm</option>
                                            <option value="03:00">03:00 pm</option>
                                            <option value="04:00">04:00 pm</option>
                                            <option value="05:00">05:00 pm</option>
                                            <option value="06:00">06:00 pm</option>
                                            <option value="07:00">07:00 pm</option>
                                            <option value="08:00">08:00 pm</option>
                                            <option value="09:00">09:00 pm</option>
                                            <option value="10:00">10:00 pm</option>
                                            <option value="11:00">11:00 pm</option>
                                            <option value="12:00">12:00 am</option>
                                            <option value="01:00">01:00 am</option>
                                            <option value="02:00">02:00 am</option>
                                            <option value="03:00">03:00 am</option>
                                            <option value="04:00">04:00 am</option>
                                            <option value="05:00">05:00 am</option>
                                            <option value="06:00">06:00 am</option>
                                            <!-- etc... -->
                                        </select>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            {{-- N° de invitados --}}
                                            <div class="form-group mb-3">
                                                <label for="limit_guest" class="text-success">N° de Invitados</label>
                                                <input type="number" id="limit_guest" name="limit_guest" 
                                                       class="form-control border border-secondary" 
                                                       placeholder="invitados" min="1" required>
                                                <small id="errorGuests" class="form-text text-danger" style="display: none;">
                                                    Solo se permiten números
                                                </small>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 col-sm-12">
                                            {{-- N° de Acompañante --}}
                                            <div class="form-group mb-3">
                                                <label for="limit_partners" class="text-info">partners x Invitado</label>
                                                <input type="number" id="limit_partners" name="limit_partners" 
                                                       class="form-control border border-secondary" 
                                                       placeholder="1 a 5" min="1" required>
                                                <small id="errorPartners" class="form-text text-danger" style="display: none;">
                                                    Solo se permiten números
                                                </small>
                                            </div>
                                        </div>

                                    </div>
                                    
                                     {{-- Salon reservado --}}
                                    <div class="form-group mb-3">
                                        <label for="rooms">Sala reservada:</label>
                                        <input type="text" name="rooms" class="form-control border border-secondary" placeholder="Salón reservado">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label for="address">Dirección:</label>
                                        <input type="text" name="address" class="form-control border border-secondary" placeholder="Dirección">
                                    </div>
                                </div>
        
                            </div>
        
                            <div class="modal-footer">
                                <button class="btn btn-danger" data-bs-dismiss="modal" type="button">Cerrar</button>
                                <button class="btn btn-success" type="submit">Registrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
         
        


        {{--  Modal Editar Evento --}}
        <div class="modal fade" id="openEditModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down modal-xl-custom">
                <div class="modal-content">
                <div class="modal-header header-custom-edit">
                    <h6 class="d-block mx-auto text-uppercase">Edición  de  Evento</h6>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editId" name="id">

                        <div class="row">
                             {{-- columna derecha --}}
                            <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="editCategory">Categoria:</label>
                                        <select id="editCategory" name="category_id" class="form-control border border-secondary">
                                            <!-- Opciones cargadas dinámicamente -->
                                        </select>
                                    </div>
        
                                    <div class="form-group">
                                        <label for="editUser">Usuario:</label>
                                        <select id="editUser" name="user_id" class="form-control  border border-secondary">
                                            <!-- Opciones cargadas dinámicamente -->
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="editPrice">Precio acordado:</label>
                                        <input id="editPrice2" type="text"  name="price" class="form-control border border-secondary">
                                        <small id="errorPrice2" class="form-text text-danger" style="display: none;">Solo se permiten números</small>
                                    </div>
        
                                    <div class="form-group">
                                        <label for="editTitle">Titulo:</label>
                                        <input type="text" id="editTitle" name="title" class="form-control border border-secondary">
                                    </div>
        
                                    <div class="form-group">
                                        <label for="editDescription">Descripcion:</label>
                                        <input type="text" id="editDescription" name="description" class="form-control border border-secondary">
                                    </div>

                                
                                    <div class="form-group">
                                        <label for="editAddress">Dirección</label>
                                        <input type="text" id="editAddress" name="address" class="form-control border border-secondary">
                                    </div>   
                            </div>

                            {{-- Columna Izquierda --}}
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="editDate">Fecha del evento</label>
                                    <input type="date" id="editDate" name="event_date" class="form-control border border-secondary">
                                </div>

                                <div class="form-group">
                                    <label for="editStartime">Hora de inicio</label>
                                    <input type="time" id="editStartime" name="start_time" class="form-control border border-secondary">
                                </div>

                                <div class="form-group">
                                    <label for="editEndtime">Hora de culminación</label>
                                    <input type="time" id="editEndtime" name="end_time" class="form-control border border-secondary">
                                </div>

                                <div class="form-group">
                                    <label for="editRooms">Ambiente-Salón</label>
                                    <input type="text" id="editRooms" name="rooms" class="form-control border border-secondary border border-secondary">
                                </div>

                                <div class="form-group">
                                    <label for="editStatus">Estatus</label>
                                    <select id="editStatus" name="status" class="form-control border border-secondary">
                                        <option value="reservado">Reservado</option>
                                        <option value="activo">Activo</option>
                                        <option value="culminado">Culminado</option>
                                        <option value="cancelado">Cancelado</option>
                                    </select>
                                </div>

                                {{-- <div class="form-group">
                                    <label for="editLimite">N° de Invitados</label>
                                    <input id="editLimite2"  type="text"  name="limit_guest" class="form-control border border-secondary">
                                    <small id="errorGuests2" class="form-text text-danger" style="display: none;">Solo se permiten números</small>

                                </div> --}}

                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        {{-- N° de invitados --}}
                                        <div class="form-group mb-3">
                                            <label for="limit_guest" class="text-success">N° de Invitados</label>
                                            <input type="text" id="editLimite2" name="limit_guest" 
                                                   class="form-control border border-secondary" 
                                                   placeholder="invitados" min="1">
                                            <small id="errorGuests" class="form-text text-danger" style="display: none;">
                                                Solo se permiten números
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 col-sm-12">
                                        {{-- N° de Acompañante --}}
                                        <div class="form-group mb-3">
                                            <label for="limit_partners" class="text-info">partners x Invitado</label>
                                            <input type="text" id="editLimitepart" name="limit_partners" 
                                                   class="form-control border border-secondary" 
                                                   placeholder="1 a 5" min="1">
                                            <small id="errorPartners" class="form-text text-danger" style="display: none;">
                                                Solo se permiten números
                                            </small>
                                        </div>
                                    </div>
                                </div>

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
    


       {{-- Modal Eliminar --}}
       <div class="modal fade" id="deleteModal" tabindex="-1" category="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" category="document">
                <div class="modal-content">
                    
                    <div class="modal-header header-custom-delete">
                        <h5 class="" id="deleteModalLabel">Eliminar Registro</h5>
                        
                    </div>

                    <div class="modal-body">
                        <p id="deleteMessage" class="text-normal">¿Estás seguro de que deseas eliminar este registro? Esta acción es inrreversible.</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="confirmDelete">Eliminar</button>
                    </div>

                </div>
            </div>
       </div>  
       {{-- Final Modal Eliminar --}}



@endsection

@push('scripts')

    
    {{-- Editar Registro --}}
    <script>
        // Función para abrir el modal de edición
        function openEditModal(eventId) {
            fetch(`/events/${eventId}/edit`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al cargar los datos');
                    }
                    return response.json();
                })
                .then(data => {
                    const event = data.event;
                    const categories = data.categories;
                    const users = data.users;

                    // Actualizar la acción del formulario
                    const form = document.getElementById('editForm');
                    form.action = `/events/${event.id}`;
                    
                    // Actualizar el ID oculto
                    document.getElementById('editId').value = event.id;

                    // Actualizar selects
                    document.getElementById('editCategory').innerHTML = categories
                        .map(category => `<option value="${category.id}" ${category.id === event.category_id ? 'selected' : ''}>${category.name}</option>`)
                        .join('');

                    document.getElementById('editUser').innerHTML = users
                        .map(user => `<option value="${user.id}" ${user.id === event.user_id ? 'selected' : ''}>${user.name}</option>`)
                        .join('');

                    // Actualizar campos
                    document.getElementById('editPrice2').value = event.price || '';
                    document.getElementById('editTitle').value = event.title || '';
                    document.getElementById('editDescription').value = event.description || '';
                    document.getElementById('editAddress').value = event.address || '';
                    document.getElementById('editDate').value = event.event_date || '';
                    document.getElementById('editStartime').value = event.start_time || '';
                    document.getElementById('editEndtime').value = event.end_time || '';
                    document.getElementById('editRooms').value = event.rooms || '';
                    document.getElementById('editLimite2').value = event.limit_guest || '';
                    document.getElementById('editLimitepart').value = event.limit_partners || '';
                    
                    // Actualizar el status - IMPORTANTE
                    const statusSelect = document.getElementById('editStatus');
                    if (statusSelect) {
                        statusSelect.value = event.status || 'reservado';
                        console.log('Status seleccionado:', statusSelect.value); // Para depuración
                    }

                    // Mostrar el modal
                    const editModal = new bootstrap.Modal(document.getElementById('openEditModal'));
                    editModal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cargar los datos del evento: ' + error.message);
                });
        }

        // Manejar el envío del formulario
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('editForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const submitButton = this.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;
                    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...';
                    submitButton.disabled = true;
                    
                    // Crear FormData y asegurarse de incluir todos los campos
                    const formData = new FormData(this);
                    formData.append('_method', 'PUT');
                    
                    // Para depuración: mostrar los datos que se enviarán
                    for (let [key, value] of formData.entries()) {
                        console.log(key, value);
                    }

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
                            // Verificar si el status se actualizó correctamente
                            console.log('Evento actualizado:', data.event);
                            location.reload();
                        } else {
                            throw new Error(data.message || 'Error al actualizar el evento');
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

        // Función opcional para actualizar solo la fila modificada
        function updateEventRow(updatedEvent) {
            const row = document.querySelector(`tr[data-event-id="${updatedEvent.id}"]`);
            if (row) {
                // Actualizar las celdas según tu estructura de tabla
                if (updatedEvent.status) {
                    const statusCell = row.querySelector('.event-status');
                    if (statusCell) {
                        statusCell.textContent = updatedEvent.status;
                        // Opcional: añadir clases según el estado
                        statusCell.className = 'event-status text-' + 
                            (updatedEvent.status === 'activo' ? 'success' : 
                            updatedEvent.status === 'cancelado' ? 'danger' : 
                            updatedEvent.status === 'culminado' ? 'info' : 'warning');
                    }
                }
            }
        }
    </script>

    {{-- Mostrar Imagenes en Modal --}}
    <script>
        function openImagesModal(eventId) {
            fetch(`/events/${eventId}/images`)
                .then(response => response.json())
                .then(data => {
                    const imagesContainer = document.getElementById('imagesContainer');
                    imagesContainer.innerHTML = ''; // Limpia cualquier contenido previo

                    if (data.images.length > 0) {
                        data.images.forEach(image => {
                            imagesContainer.innerHTML += `
                                <div class="col">
                                    <div class="card h-100">
                                        <img src="${image.url}" class="card-img-top" alt="Imagen del evento">
                                    </div>
                                </div>`;
                        });
                    } else {
                        imagesContainer.innerHTML = '<p class="text-center">No hay imágenes para este evento.</p>';
                    }

                    // Mostrar el modal
                    const imagesModal = new bootstrap.Modal(document.getElementById('imagesModal'));
                    imagesModal.show();
                })
                .catch(error => console.error('Error:', error));
        }
    </script>


    {{-- Eliminar registro --}}
    <script>

        document.addEventListener('DOMContentLoaded', function () {
            let eventIdToDelete = null;

            // Capturar el ID del usuario al abrir el modal
            document.querySelectorAll('#bDel').forEach(button => {
                button.addEventListener('click', function () {
                    eventIdToDelete = this.getAttribute('data-id');
                    document.getElementById('deleteMessage').textContent = '¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.';
                });
            });

            // Confirmar y realizar la eliminación
            document.getElementById('confirmDelete').addEventListener('click', function () {
                if (eventIdToDelete) {
                    fetch(`/events/${eventIdToDelete}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                        .then(response => {
                            if (response.ok) {
                                document.getElementById('deleteMessage').textContent = 'El registro fue eliminado correctamente.';
                                
                                // Desactivar los botones del modal mientras se muestra el mensaje
                                document.getElementById('confirmDelete').disabled = true;
                                document.querySelector('[data-bs-dismiss="modal"]').disabled = true;

                                // Cerrar el modal después de 2 segundos
                                setTimeout(() => {
                                    const modalElement = document.getElementById('deleteModal');
                                    const modal = bootstrap.Modal.getInstance(modalElement);
                                    modal.hide();

                                    // Recargar la página después de cerrar el modal
                                    location.reload();
                                }, 2000);
                            } else {
                                document.getElementById('deleteMessage').textContent = 'Error al eliminar el registro. Por favor, inténtelo de nuevo.';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            document.getElementById('deleteMessage').textContent = 'Ocurrió un error inesperado. Por favor, inténtelo más tarde.';
                        });
                }
            });
        });

    </script>


     {{-- Modal registroimput price, limit_guest (SOLO NUMEROS) --}}
    <script>
        
        const inputs = [
            { id: 'editPrice', errorId: 'errorPrice' },
            { id: 'editLimite', errorId: 'errorGuests' }
        ];

        inputs.forEach(({ id, errorId }) => {
            const input = document.getElementById(id);
            const errorMsg = document.getElementById(errorId);

            input.addEventListener('input', function () {
                const value = this.value;

                if (/^\d*$/.test(value)) {
                    errorMsg.style.display = 'none';
                } else {
                    errorMsg.style.display = 'block';
                }
            });
        });

    </script>


     {{-- Modal editar -imput price, Limit_guest (SOLO NUMEROS) --}}
    <script>
        
            document.addEventListener('DOMContentLoaded', function() {
            // Configurar validación para ambos modales
            setupValidation('editPrice', 'errorPrice'); // Modal de registro
            setupValidation('editLimite', 'errorGuests'); // Modal de registro
            
            setupValidation('editPrice2', 'errorPrice2'); // Modal de edición
            setupValidation('editLimite2', 'errorGuests2'); // Modal de edición

            function setupValidation(inputId, errorId) {
                const input = document.getElementById(inputId);
                const errorElement = document.getElementById(errorId);

                if (input && errorElement) {
                    input.addEventListener('input', function() {
                        validateNumberInput(this, errorElement);
                    });
                }
            }

            function validateNumberInput(inputElement, errorElement) {
                const value = inputElement.value;
                
                // Validar que solo contenga números
                if (!/^\d*$/.test(value)) {
                    errorElement.style.display = 'block';
                    inputElement.classList.add('is-invalid');
                } else {
                    errorElement.style.display = 'none';
                    inputElement.classList.remove('is-invalid');
                }
            }

            // Limpiar validaciones al cerrar los modales
            $('#select2modal, #openEditModal').on('hidden.bs.modal', function() {
                $(this).find('.is-invalid').removeClass('is-invalid');
                $(this).find('.form-text.text-danger').hide();
            });
        });

    </script>

   



@endpush