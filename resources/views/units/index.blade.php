
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

    {{-- Tabla $units--}}
    <div class="row row-sm mt-4 mx-auto">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Unidades del Sistema</h3>
                    <a class="btn ripple btn-teal" data-bs-target="#select2modal" data-bs-toggle="modal" href="">+ Nueva</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table border-top-0  table-bordered text-nowrap border-bottom" id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-5p border-bottom-0 bg-light">ID</th>
                                    <th class="wd-15p border-bottom-0 bg-light">Nombres</th>
                                    <th class="wd-5p border-bottom-0 bg-light text-center">Simbolo</th>
                                    <th class="wd-25p border-bottom-0 bg-light text-center">Descripción</th>
                                    <th class="wd-5p border-bottom-0 bg-light text-center">Estado</th>
                                    <th class="wd-8p border-bottom-0 bg-light text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($units as $unit)
                                <tr>
                                    <td class="text-center">{{$unit->id}} </td>
                                    <td class="fw-light">{{$unit->name}}</td>
                                    <td class="fw-light text-center">{{$unit->symbol}}</td>
                                    <td class="fw-light">{{$unit->description}}</td>
                                    <td class="fw-light text-center">
                                        @if($unit->status)
                                            <span class="text-success">Activo</span>
                                        @else
                                            <span class="text-danger">Inactivo</span>
                                        @endif
                                    </td>
                                     
                                    
                                    <td class="text-center">
                                    
                                        <!-- Otros botones -->
                                        <button class="btn btn-sm btn-primary mx-3" onclick="openImagesModal({{ $unit->id }})">
                                            <span class="fe fe-image"></span>
                                        </button>
                                        
                                        <button id="bEdit" type="button" class="btn btn-sm btn-warning"  onclick="openEditModal({{ $unit->id }})">
                                            <span class="fe fe-edit"></span>
                                        </button>
                                    
                                        <button id="bDelUnit" type="button" class="btn btn-sm btn-danger mx-3" 
                                                data-id="{{ $unit->id }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteUnitModal">
                                            <span class="fe fe-trash-2"></span>
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
                    <h6 class="mx-auto text-uppercase" style="color: #000">Nuevo Registro</h6>
                </div>
                <div class="modal-body">
                    <div class="col col-lg-12">
                    
                            <div class="m-2">
                                <form action="{{ route('units.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-group">
                                        
                                    </div>

                                    <div class="form-group">
                                        <label for="name">Nombre:</label>
                                        <input type="text" name="name" id="name" class="form-control border border-secondary" placeholder="Nombres">
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Descripción:</label>
                                        <input type="description" name="description" id="description" class="form-control border border-secondary" placeholder="Descripción detallada">
                                    </div>


                                    <div class="form-group">
                                        <label for="symbol">Simbolo:</label>
                                        <input type="symbol" name="symbol" id="symbol" class="form-control border border-secondary" placeholder="Ejemplo: 'SC'  para Sacos">
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
                    <h6 class="d-block mx-auto text-uppercase" style="color: #000">Editar Unidad</h6>
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
                            <label for="editDescription">Descripción</label>
                            <input type="text" id="editDescription" name="description" class="form-control border border-secondary">
                            
                        </div>

                        <div class="row">
                            

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="editSymbol">Simbolo</label>
                                        <input type="text" id="editSymbol" name="symbol" class="form-control border border-secondary">               
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
    </div>
    <!-- End Basic modal -->


    {{-- Modal Eliminar --}}
    <div class="modal fade" id="deleteUnitModal" tabindex="-1" role="dialog" aria-labelledby="deleteUnitModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header header-custom-delete">
                    <h6 class="d-block mx-auto text-uppercase" id="deleteUnitModalLabel">Eliminar Unidad</h6>
                </div>
                <div class="modal-body m-10">
                    <p id="deleteUnitMessage" class="text-normal">¿Estás seguro de que deseas eliminar esta unidad? Esta acción es irreversible.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmUnitDelete">Eliminar</button>
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

    {{-- Editar Registro --}}
    <script>
       
        function openEditModal($unitId) {
            // Realiza una solicitud AJAX para obtener los datos del almacén
            fetch(`/units/${$unitId}/edit`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Rellena el formulario con los datos obtenidos
                    document.getElementById('editForm').action = `/units/${data.id}`;
                    document.getElementById('editId').value = data.id;
                    document.getElementById('editName').value = data.name;
                    document.getElementById('editSymbol').value = data.symbol;
                    document.getElementById('editDescription').value = data.description;
                    document.getElementById('editStatus').value = data.status;

                    // Muestra el modal
                    const editModal = new bootstrap.Modal(document.getElementById('openEditModal'));
                    editModal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cargar los datos de la unidad');
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
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
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
                    alert('Error al actualizar la Unidad');
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
            let unitIdToDelete = null;

            // Capturar el ID al abrir el modal
            document.querySelectorAll('#bDelUnit').forEach(button => {
                button.addEventListener('click', function () {
                    unitIdToDelete = this.getAttribute('data-id');
                    document.getElementById('deleteUnitMessage').textContent = 
                        '¿Estás seguro de que deseas eliminar esta unidad? Esta acción no se puede deshacer.';
                });
            });

            // Confirmar y realizar la eliminación
            document.getElementById('confirmUnitDelete').addEventListener('click', function () {
                if (unitIdToDelete) {
                    fetch(`/units/${unitIdToDelete}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            return response.json();
                        }
                        throw new Error('Error en la respuesta del servidor');
                    })
                    .then(data => {
                        document.getElementById('deleteUnitMessage').textContent = data.message || 'Unidad eliminada correctamente.';
                        
                        // Desactivar botones
                        document.getElementById('confirmUnitDelete').disabled = true;
                        document.querySelector('#deleteUnitModal [data-bs-dismiss="modal"]').disabled = true;

                        // Cerrar el modal y recargar
                        setTimeout(() => {
                            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteUnitModal'));
                            modal.hide();
                            location.reload();
                        }, 1500);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('deleteUnitMessage').textContent = 
                            'Error al eliminar la unidad: ' + (error.message || 'Por favor, inténtelo de nuevo.');
                    });
                }
            });

            // Resetear el modal al cerrar
            document.getElementById('deleteUnitModal').addEventListener('hidden.bs.modal', function() {
                document.getElementById('deleteUnitMessage').textContent = 
                    '¿Estás seguro de que deseas eliminar esta unidad? Esta acción no se puede deshacer.';
                document.getElementById('confirmUnitDelete').disabled = false;
                document.querySelector('#deleteUnitModal [data-bs-dismiss="modal"]').disabled = false;
            });
        });
    </script>



@endpush

