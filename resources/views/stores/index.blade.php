
@extends('admin.layouts.master')


@section('content')

   
<secttion>

 <div class="main-container container-fluid">

    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <h4 class="page-title">Administración</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Depositos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Gestion de Depositos</li>
            </ol>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center">
            <div class="mb-xl-0">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuDate" data-bs-toggle="dropdown" aria-expanded="false">
                        
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->

    {{-- Tabla Stores--}}
    <div class="row row-sm mt-4 mx-auto">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Depositos del Sistema</h3>
                    <a class="btn ripple btn-teal" data-bs-target="#select2modal" data-bs-toggle="modal" href="">+ Nuevo</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table border-top-0  table-bordered text-nowrap border-bottom" id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-5p border-bottom-0 bg-light">ID</th>
                                    <th class="wd-15p border-bottom-0 bg-light">Nombres</th>
                                    <th class="wd-20p border-bottom-0 bg-light text-center">Localización</th>
                                    <th class="wd-5p border-bottom-0 bg-light text-center">Estatus</th>
                                    <th class="wd-10p border-bottom-0 bg-light text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stores as $store)
                                <tr>
                                    <td>{{$store->id}} </td>
                                    <td class="fw-light">{{$store->name}}</td>
                                    <td class="fw-light">{{$store->location}}</td>
                                    <td class="fw-light text-center">
                                        @if($store->is_active)
                                            <span class="text-success">Activo</span>
                                        @else
                                            <span class="text-danger">Inactivo</span>
                                        @endif
                                    </td>
                                     
                                    
                                    <td class="text-center">
                                    
                                        <!-- Otros botones -->
                                        <button class="btn btn-sm btn-primary mx-3" onclick="openImagesModal({{ $store->id }})">
                                            <span class="fe fe-image"></span>
                                        </button>
                                        
                                        <button id="bEdit" type="button" class="btn btn-sm btn-warning"  onclick="openEditModal({{ $store->id }})">
                                            <span class="fe fe-edit"></span>
                                        </button>
                                    
                                        <button id="bDel" type="button"  class="btn  btn-sm btn-danger mx-3" data-id="{{ $store->id }}"
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


    <!-- Basic Registro -->
    <div class="modal" id="select2modal">
        <div class="modal-dialog modal-dialog-centered" category="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header header-custom">
                    <h6 class="d-block mx-auto text-uppercase">Nuevo Registro</h6>
                </div>
                <div class="modal-body">
                    <div class="col col-lg-12">
                    
                            <div class="m-2">
                                <form action="{{ route('stores.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-group">
                                        
                                    </div>

                                    <div class="form-group">
                                        <label for="name">Nombre:</label>
                                        <input type="text" name="name" id="name" class="form-control border border-secondary" placeholder="Nombres">
                                    </div>


                                    <div class="form-group">
                                        <label for="location">Ubicación:</label>
                                        <input type="location" name="location" id="location" class="form-control border border-secondary" placeholder="Descripción detallada">
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
    </div>
    <!-- End Basic modal -->


    {{-- Modal editar --}}
    <div class="modal" id="openEditModal">
        <div class="modal-dialog modal-dialog-centered" category="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header header-custom-edit">
                    <h6 class="d-block mx-auto text-uppercase">Editar Deposito</h6>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">

                            <label for="editName">Nombre</label>
                            <input type="text" id="editName" name="name" class="form-control border border-secondary">
                            <input type="hidden" id="editId" name="id">
                            
                        </div>

                        <div class="form-group">
                            <label for="editLocation">Ubicación</label>
                            <input type="text" id="editLocation" name="location" class="form-control border border-secondary">
                            
                        </div>

                        <div class="form-group">
                            <label for="editIsActive">Estado</label>
                            <select id="editIsActive" name="is_active" class="form-control border border-secondary">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                            
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
    <!-- End Basic modal -->


    {{-- Modal Eliminar --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" category="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" category="document">
            <div class="modal-content">
                
                <div class="modal-header header-custom-delete">
                    <h6 class="d-block mx-auto text-uppercase" id="deleteModalLabel">Eliminar Registro</h6>
                </div>

                <div class="modal-body m-10">
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


    {{-- Estilo para encabezado de tablas --}}
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
    </style>


</secttion>
                   

@endsection

@push('scripts')

   {{-- Editar Registro --}}
    <script>
       
        function openEditModal(storeId) {
            // Realiza una solicitud AJAX para obtener los datos del almacén
            fetch(`/stores/${storeId}/edit`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Rellena el formulario con los datos obtenidos
                    document.getElementById('editForm').action = `/stores/${data.id}`;
                    document.getElementById('editId').value = data.id;
                    document.getElementById('editName').value = data.name;
                    document.getElementById('editLocation').value = data.location;
                    document.getElementById('editIsActive').value = data.is_active;

                    // Muestra el modal
                    const editModal = new bootstrap.Modal(document.getElementById('openEditModal'));
                    editModal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cargar los datos del almacén');
                });
        }

        // Manejar el envío del formulario con AJAX
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const formData = new FormData(form);
            const url = form.action;
            const method = form.method;

            fetch(url, {
                method: method,
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cerrar el modal y recargar la página
                    const editModal = bootstrap.Modal.getInstance(document.getElementById('openEditModal'));
                    editModal.hide();
                    location.reload();
                } else {
                    alert('Error al actualizar el almacén');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la solicitud');
            });
        });


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
                    fetch(`/stores/${eventIdToDelete}`, {
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



@endpush

