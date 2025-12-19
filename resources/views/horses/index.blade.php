
@extends('admin.layouts.master')


@section('content')

   
<secttion>

    <div class="main-container container-fluid">

    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <h4 class="page-title">Administración</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Caballos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Gestion de Caballos</li>
            </ol>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center">
            <div class="mb-xl-0">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="resetSuppliesBtn" title="Resetear suministros ejecutados de días anteriores" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-sync-alt me-1"></i> Resetear Supplies
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->

    {{-- Tabla $horses--}}
    <div class="row row-sm mt-4 mx-auto">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Horse Management</h3>
                    <a class="btn ripple btn-teal" href="{{ route('horses.create') }}">+ Nuevo</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table border-top-0  table-bordered text-nowrap border-bottom" id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-5p border-bottom-0 bg-light">ID</th>
                                    <th class="wd-5p border-bottom-0 bg-light">image</th>
                                    <th class="wd-20p border-bottom-0 bg-light">name</th>
                                    <th class="wd-10p border-bottom-0 bg-light text-center">Color</th>
                                    <th class="wd-5p border-bottom-0 bg-light text-center">Sex</th>
                                    <th class="wd-5p border-bottom-0 bg-light text-center">Location</th>
                                    <th class="wd-5p border-bottom-0 bg-light text-center">Condition</th>
                                    <th class="wd-5p border-bottom-0 bg-light text-center">Status</th>
                                    <th class="wd-5p border-bottom-0 bg-light text-center">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($horses as $horse)
                                <tr>
                                    <td class="text-center">{{$horse->id}} </td>
                                    <td class="text-center">
                                        <img src="{{ $horse->photo ? asset('storage/' . $horse->photo) : asset('storage/horses/horse.png') }}" 
                                             style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
                                    </td>
                                    <td class="fw-light">{{$horse->name}}</td>
                                    <td class="fw-light text-center">{{$horse->color}}</td>
                                    <td class="fw-light">{{$horse->sex}}</td>
                                    <td class="fw-light">{{$horse->stable->name}}</td>
                                    <td class="fw-light">{{$horse->condition}}</td>
                                                    
                                
                                    <td class="fw-light text-center">
                                        @if($horse->status)
                                            <span class="text-success">Activo</span>
                                        @else
                                            <span class="text-danger">Inactivo</span>
                                        @endif
                                    </td>
                                     
                                    
                                    <td class="text-center gap-3">
                                    
                                        <!-- Otros botones -->
                                        <button class="btn btn-sm btn-primary" onclick="openImagesModal({{ $horse->id }})">
                                            <span class="fe fe-image"></span>
                                        </button>
                                        
                                        <a id="bEdit" type="button" class="btn btn-sm btn-warning" href="{{route('horses.edit', $horse)}}">
                                            <span class="fe fe-edit"></span>
                                        </a>
                                    
                                        <button class="btn btn-sm btn-danger delete-horse-btn" data-id="{{ $horse->id }}">
                                            <i class="fas fa-trash"></i> 
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


    <!-- Basic Registro -->
    {{-- <div class="modal" id="select2modal">
        <div class="modal-dialog modal-dialog-centered" category="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header header-custom">
                    <h6 class="mx-auto text-uppercase" style="color: #000">Nuevo Registro</h6>
                </div>
                <div class="modal-body">
                    <div class="col col-lg-12">
                    
                            <div class="m-2">
                                <form action="{{ route('horses.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-group">
                                        
                                    </div>

                                    <div class="form-group">
                                        <label for="name">Nombre:</label>
                                        <input type="text" name="name" id="name" class="form-control border border-secondary uppercase-input" placeholder="Nombres">
                                    </div>

                                    <div class="form-group">
                                        <label for="location">Ubicación:</label>
                                        <input type="location" name="location" id="location" class="form-control border border-secondary uppercase-input" placeholder="Ubicación detallada">
                                    </div>


                                    <div class="form-group">
                                        <label for="condition">Condiciones:</label>
                                        <select name="condition" id="condition" class="form-select border border-secondary" required>
                                            <option value="">Seleccione una condición</option>
                                            <option value="use">Ocupado</option>
                                            <option value="mant">Mantenimiento</option>
                                            <option value="clear">Limpio</option>
                                            <option value="repair">Reparación</option>
                                        </select>
                                    </div>

                                    
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
    </div> --}}
    <!-- End Basic modal -->


    {{-- Modal editar --}}
    {{-- <div class="modal" id="openEditModal">
        <div class="modal-dialog modal-dialog-centered" category="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header header-custom-edit">
                    <h6 class="d-block mx-auto text-uppercase" style="color: #000">Editar Establo</h6>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">

                            <label for="editName">Titulo</label>
                            <input type="text" id="editName" name="name" class="form-control border border-secondary uppercase-input">
                            <input type="hidden" id="editId" name="id">
                            
                        </div>

                        <div class="form-group">
                            <label for="editLocation">Ubicación</label>
                            <input type="text" id="editLocation" name="location" class="form-control border border-secondary uppercase-input">
                            
                        </div>

                        <div class="row">
                            

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="editCondition">Condiciones</label>
                                        <select id="editCondition" name="condition" class="form-control">
                                            <!-- Opciones se llenan con JavaScript -->
                                        </select>
                                    </div>
                                </div>

                                <div class="col-6">
 
                                    <div class="form-group">
                                        <label for="editStatus">Estado</label>
                                        <select id="editStatus" name="status" class="form-control border border-secondary">
                                            <option value="1">Activo</option>
                                            <option value="0">Inactivo</option>
                                        </select>
                                        
                                    </div>

                                </div>
                                

                            
                        </div>
                        

                        <div class="modal-footer">
                            <button class="btn ripple btn-danger mx-4" data-bs-dismiss="modal" type="button">Cerrar</button>
                            <button type="submit" class="btn ripple btn-warning">Editar</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- End Basic modal -->


    {{-- Modal Eliminar --}}
    <div class="modal fade" id="deleteHorseModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar este caballo? Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
      
    {{-- Final Modal Eliminar --}}


    <style>
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
        /* Estilos para el menú responsive del DataTable */
        .dtr-bs-modal.show .dropdown-menu {
            display: block !important;
            position: absolute !important;
            z-index: 1060 !important;
            background: white !important;
            padding: 10px !important;
            border-radius: 5px !important;
            box-shadow: 0 0 10px rgba(0,0,0,0.1) !important;
        }

        /* Asegurar que los botones sean accesibles en móviles */
        .dtr-bs-modal .delete-btn, 
        .dtr-bs-modal .btn {
            min-width: 80px !important;
            margin: 5px 0 !important;
            padding: 8px 12px !important;
            display: block !important;
            white-space: nowrap !important;
        }


    </style>


</secttion>
                   

@endsection

@push('scripts')


    {{-- Validacion para imagenes --}}    
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
        $(document).ready(function() {
            let horseIdToDelete;
            
            // Cuando se hace clic en el botón de eliminar
            $('.delete-horse-btn').on('click', function() {
                horseIdToDelete = $(this).data('id');
                $('#deleteHorseModal').modal('show');
            });
            
            // Confirmar eliminación
            $('#confirmDelete').on('click', function() {
                $.ajax({
                    url: '/horses/' + horseIdToDelete,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json', // Esperamos respuesta JSON
                    success: function(response) {
                        if(response.success) {
                            $('#deleteHorseModal').modal('hide');
                            
                            
                            // Recargar después de 1 segundo
                            setTimeout(function() {
                              window.location.href = "{{ route('horses.index') }}";
                            }, 1000);
                        }
                    },
                    error: function(xhr) {
                        $('#deleteHorseModal').modal('hide');
                        
                        // Mostrar error específico si está disponible
                        const errorMsg = xhr.responseJSON && xhr.responseJSON.message 
                            ? xhr.responseJSON.message 
                            : 'Ocurrió un error al eliminar el caballo';
                        
                        toastr.error(errorMsg);
                    }
                });
            });
        });
    </script>
    
    {{-- Validacion letras MAYUSCULA --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Seleccionar todos los inputs con la clase uppercase-input
            const inputs = document.querySelectorAll('.uppercase-input');
            
            // Aplicar el evento a cada input
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
                
                // También convertir al perder el foco por si acaso
                input.addEventListener('blur', function() {
                    this.value = this.value.toUpperCase();
                });
            });
        });
    </script>



@endpush

