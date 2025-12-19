@extends('admin.layouts.master')

@section('content')
<div class="row row-sm mt-2 mx-auto">
    <div class="main-container container-fluid">


        <!-- Breadcrumb -->
        <div class="breadcrumb-header justify-content-between">
            <div class="my-auto">
                <h4 class="page-title">Invitados de: {{ $event->title }}</h4>
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

        <!-- Tabla de invitados -->
        <div class="row mt-2">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table border-top-0  table-bordered text-nowrap border-bottom" id="responsive-datatable">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Teléfono</th>
                                        <th>Asistencia</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($guests as $guest)
                                    <tr>
                                        <td>{{ $guest->name }} {{ $guest->last_name }}</td>
                                        <td>{{ $guest->email }}</td>
                                        <td>{{ $guest->phone }}</td>
                                        <td>
                                            @if($guest->is_attended)
                                                <span class="badge bg-success py-2 px-3" style="color: #fff">Asistirá</span>
                                            @else
                                                <span class="badge bg-warning py-2 px-3" style="color: #000">Pendiente</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-info">Ver</a>
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

          <!-- Add New guest -->
        <div class="modal" id="select2modal">
            <div class="modal-dialog" category="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header" style="background-color: #def9e4">
                        <h6 class="modal-title">Nuevo Invitado</h6><button aria-label="Close" class="close"
                            data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="col col-lg-12">
                        
                                <div class="m-2">
                                    <form action="{{ route('guests.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        
                                         <!-- Campo oculto para el event_id (se llenará con JS) -->
                                         <input type="hidden" name="event_id" id="event_id">

                                        <!-- Name -->
                                        <div class="form-group">
                                            <label for="title" class="fw-normal">{{ __('Nombre del Invitado :') }}</label>
                                            <input type="text" name="name" id="name" class="form-control" placeholder="Ingresar Nombre del invitado">
                                        </div>

                                        <!-- Last_Name -->
                                        <div class="form-group">
                                            <label for="title" class="fw-normal">{{ __('Apellidos del Invitado :') }}</label>
                                            <input type="text" name="last_name" id="last_name" class="form-control"  placeholder="Ingresar Apellidos del invitado">
                                        </div>

                                        <!-- Email -->
                                        <div class="form-group">
                                            <label for="title" class="fw-normal">{{ __('Correo del Invitado :') }}</label>
                                            <input type="text" name="email" id="email" class="form-control"  placeholder="Ingresarl el correo del invitado">
                                        </div>

                                        <!-- Phone -->
                                        <div class="form-group">
                                            <label for="title" class="fw-normal">{{ __('Telefono :') }}</label>
                                            <input type="text" name="phone" id="phone" class="form-control" placeholder="Telefono del invitado">
                                        </div>


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
        
    </div>
</div>
@section('scripts')

<script>
    $(document).ready(function() {
        // Capturar el event_id de la URL (ejemplo: /events/5/guests → event_id = 5)
        const pathArray = window.location.pathname.split('/');
        const eventId = pathArray[2]; // Ajusta el índice según tu estructura de ruta

        // Asignar el event_id al campo oculto y actualizar el action del formulario
        $('#event_id').val(eventId);
        $('#guestForm').attr('action', `/events/${eventId}/guests`); // Opcional: asegura la ruta correcta
    });
</script>

@endsection