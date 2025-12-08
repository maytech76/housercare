@extends('admin.layouts.master')

@section('content')
   
<section>
    <div class="main-container container-fluid">

        <!-- breadcrumb -->
        <div class="breadcrumb-header justify-content-between">
            <div class="my-auto">
                <h4 class="page-title">Administration</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Services</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Service management</li>
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

        {{-- Tabla Servicios --}}
        <div class="row row-sm mt-4 mx-auto">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">System Services</h3>
                        <a class="btn ripple btn-teal" data-bs-target="#createServiceModal" data-bs-toggle="modal" href="">+ Service</a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="responsive-datatable">
                                <thead>
                                    <tr>
                                        <th class="wd-5p border-bottom-0 bg-light text-center">ID</th>
                                        <th class="wd-5p border-bottom-0 bg-light text-center">Icon</th>
                                        <th class="wd-20p border-bottom-0 bg-light text-center">Name</th> <!-- Agregado text-center -->
                                        <th class="wd-5p border-bottom-0 bg-light text-center">Status</th>
                                        <th class="wd-5p border-bottom-0 bg-light text-center">Created</th> <!-- Agregado text-center -->
                                        <th class="wd-10p border-bottom-0 bg-light text-center">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($services as $service)
                                    <tr>
                                        <td class="text-center">{{ $service->row_number }}</td>
                                        <td class="text-center">
                                            @if($service->photo)
                                            <img src="{{ asset('storage/' . $service->photo) }}" width="35" height="35" class="rounded mx-auto d-block" alt="Imagen del servicio">
                                            @else
                                            <span class="text-muted">Sin imagen</span>
                                            @endif
                                        </td>
                                        <td class="fw-light text-center">{{ $service->name }}</td> <!-- Agregado text-center -->
                                        <td class="text-center">
                                            <span class="text-{{ $service->status ? 'success' : 'danger' }}">
                                                {{ $service->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $service->created_at }}</td> <!-- Agregado text-center -->
                                        <td class="text-center">
                                            <!-- Contenedor para centrar los botones -->
                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                <!-- Cambiar estado -->
                                               
                                                
                                                <form action="{{ route('services.change-status', $service) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <label class="switch">
                                                        <input type="checkbox" {{ $service->status ? 'checked' : '' }} onchange="this.form.submit()">
                                                        <span class="slider round"></span>
                                                    </label>
                                                </form>
                                                
                                               
                                                
                                                <!-- Editar -->
                                                <button type="button" class="btn btn-sm btn-warning" onclick="openEditModal({{ $service->id }})">
                                                    <span class="fe fe-edit"></span>
                                                </button>
                                            
                                                <!-- Eliminar -->
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal"
                                                    data-id="{{ $service->id }}"
                                                    data-name="{{ $service->name }}">
                                                    <span class="fe fe-trash-2"></span>
                                                </button>
                                            </div>
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

        <!-- Modal Crear Servicio -->
        <div class="modal" id="createServiceModal">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header bg-success-gradient">
                        <h6 class="d-block mx-auto text-uppercase">New Service</h6>
                    </div>
                    <div class="modal-body">
                        <div class="col col-lg-12">
                            <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data" id="createServiceForm">
                                @csrf
                                
                                <div class="form-group">
                                    <label for="name">Service Name:</label>
                                    <input type="text" name="name" id="name" class="form-control border border-secondary" placeholder="Ingrese el nombre del servicio" required>
                                </div>

                                <div class="form-group">
                                    <label for="photo">Image Service:</label>
                                    <input type="file" name="photo" id="photo" class="form-control border border-secondary" accept="image/*">
                                    <small class="text-muted">Formats: jpeg, png, jpg, gif (Max: 2MB)</small>
                                </div>

                                <div class="form-group">
                                    <label for="status">Status:</label>
                                    <select name="status" id="status" class="form-select" required>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>

                                <div class="modal-footer">
                                    <div class="row w-100">
                                        <div class="col-md-6 col-sm-12 col-xs-12 mb-sm-4 mb-xs-4">
                                            <button class="btn ripple btn-danger w-100" data-bs-dismiss="modal" type="button">Cancel</button>
                                        </div>
                                
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <button class="btn ripple btn-success w-100" type="submit">Register</button>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Crear -->

        <!-- Modal Editar Servicio -->
        <div class="modal" id="editServiceModal">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header bg-warning-gradient">
                        <h6 class="d-block mx-auto text-uppercase">EDIT SERVICE</h6>
                    </div>
                    <div class="modal-body">
                        <form id="editServiceForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <input type="hidden" id="editId" name="id">
                            
                            <div class="row">
                                <div class="col-4">
                                     {{-- Imagen --}}
                                        <div class="form-group">
                                            <label for="editPhoto">Current Image</label>
                                            <div id="currentImageContainer" class="mb-2">
                                                <!-- Imagen actual se cargará aquí -->
                                            </div>
                                            
                                        </div>
                                </div>

                                <div class="col-8">
                                     {{-- Nombre --}}
                                    <div class="form-group">
                                        <label for="editName">Service Name</label>
                                        <input type="text" id="editName" name="name" class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="editPhoto">New Image (Opcional):</label>
                                            <input type="file" id="editPhoto" name="photo" class="form-control" accept="image/*">
                                            <small class="text-muted">Leave blank to keep the current image</small>
                                    </div>
                                </div>
                            </div>
                           
                            
                            
                            
                            {{-- Status --}}
                            <div class="form-group">
                                <label for="editStatus">Status</label>
                                <select id="editStatus" name="status" class="form-control" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>

                            
                            {{-- Botones --}}
                            <div class="modal-footer">
                                <div class="row w-100">
                                    <div class="col-md-6 col-sm-12 col-xs-12 mb-sm-4 mb-xs-4">
                                        <button type="button" class="btn btn-danger w-100" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <button type="submit" class="btn btn-warning w-100">Edit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Editar -->


        <!-- Modal Eliminar -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger-gradient">
                        <h6 class="d-block mx-auto text-uppercase" id="deleteModalLabel">Delete Service</h6>
                    </div>
                    <div class="modal-body m-10">
                        <p id="deleteMessage" class="text-normal">Are you sure you want to delete this service?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                    </div>
                </div>
            </div>
        </div>      

        <style>
            .switch {
                position: relative;
                display: inline-block;
                width: 50px;
                height: 24px;
            }
            
            .switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }
            
            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                transition: .4s;
            }
            
            .slider:before {
                position: absolute;
                content: "";
                height: 16px;
                width: 16px;
                left: 4px;
                bottom: 4px;
                background-color: white;
                transition: .4s;
            }
            
            input:checked + .slider {
                background-color: #28a745;
            }
            
            input:checked + .slider:before {
                transform: translateX(26px);
            }
            
            .slider.round {
                border-radius: 34px;
            }
            
            .slider.round:before {
                border-radius: 50%;
            }
        </style>

    </div>
</section>
                   
@endsection

@push('scripts')
<!-- Sweetalert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    // Mostrar alertas de sesión
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            timer: 4000
        });
    @endif

    // Función para abrir modal de edición
    function openEditModal(serviceId) {
        console.log('Loading service ID:', serviceId);
        
        fetch(`/services/${serviceId}/edit`)
            .then(response => {
                console.log('Response received:', response);
                if (!response.ok) {
                    throw new Error(`HTTP Error: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Data received:', data);
                
                if (data.success && data.service) {
                    // Fill the form with service data
                    document.getElementById('editServiceForm').action = `/services/${data.service.id}`;
                    document.getElementById('editId').value = data.service.id;
                    document.getElementById('editName').value = data.service.name;
                    document.getElementById('editStatus').value = data.service.status ? '1' : '0';
                    
                    // Show current image
                    const imageContainer = document.getElementById('currentImageContainer');
                    if (data.service.photo) {
                        imageContainer.innerHTML = `
                            <p class="text-sm text-muted">Current image:</p>
                            <img src="/storage/${data.service.photo}" 
                                width="100" 
                                height="100" 
                                class="rounded border current-image" 
                                alt="Current image of the service">
                            <div class="mt-1">
                                <small class="text-info">Current image of the service</small>
                            </div>
                        `;
                    } else {
                        imageContainer.innerHTML = `
                            <p class="text-sm text-muted">There is no current image</p>
                            <div class="text-center p-3 border rounded bg-light">
                                <i class="fe fe-image fs-1 text-muted"></i>
                                <p class="text-muted small mt-2">No image</p>
                            </div>
                        `;
                    }

                    // Show the modal
                    const editModal = new bootstrap.Modal(document.getElementById('editServiceModal'));
                    editModal.show();
                } else {
                    throw new Error('Service data not available');
                }
            })
            .catch(error => {
                console.error('Detailed error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'The service data could not be loaded. Check your console for more details.',
                    footer: `Error: ${error.message}`
                });
            });
    }

    // Handle edit form submission
    document.getElementById('editServiceForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitButton = this.querySelector('button[type="submit"]');
        
        // Show loading on button
        const originalText = submitButton.innerHTML;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
        submitButton.disabled = true;

        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP Error: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('editServiceModal'));
                modal.hide();
                
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message || 'Service updated successfully',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                throw new Error(data.message || 'Error updating');
            }
        })
        .catch(error => {
            console.error('Update error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error updating the service: ' + error.message
            });
        })
        .finally(() => {
            // Restore button
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        });
    });

    // Delete service
    document.addEventListener('DOMContentLoaded', function () {
        let serviceIdToDelete = null;
        let serviceNameToDelete = null;

        // Capture data when opening delete modal
        document.querySelectorAll('[data-bs-target="#deleteModal"]').forEach(button => {
            button.addEventListener('click', function () {
                serviceIdToDelete = this.getAttribute('data-id');
                serviceNameToDelete = this.getAttribute('data-name');
                document.getElementById('deleteMessage').textContent = 
                    `Are you sure you want to delete the service "${serviceNameToDelete}"? This action cannot be undone.`;
            });
        });

        // Confirm deletion - CORREGIDO
        document.getElementById('confirmDelete').addEventListener('click', function () {
            if (serviceIdToDelete) {
                const deleteButton = this;
                const originalText = deleteButton.innerHTML;
                
                // Mostrar loading
                deleteButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...';
                deleteButton.disabled = true;

                fetch(`/services/${serviceIdToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest' // Importante para identificar petición AJAX
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP Error: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                        modal.hide();
                        
                        // Show success alert
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: data.message || 'The service has been successfully removed.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        throw new Error(data.message || 'Error deleting service');
                    }
                })
                .catch(error => {
                    console.error('Delete error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'The service could not be deleted: ' + error.message
                    });
                })
                .finally(() => {
                    // Restore button
                    deleteButton.innerHTML = originalText;
                    deleteButton.disabled = false;
                });
            }
        });
    });

    // Create form validation
    document.getElementById('createServiceForm')?.addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const photo = document.getElementById('photo').files[0];
        
        if (!name) {
            e.preventDefault();
            Swal.fire('Error', 'The service name is required', 'error');
            return;
        }

        if (photo) {
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            const maxSize = 2 * 1024 * 1024; // 2MB
            
            if (!validTypes.includes(photo.type)) {
                e.preventDefault();
                Swal.fire('Error', 'The image format is invalid. Use JPEG, PNG, JPG or GIF.', 'error');
                return;
            }
            
            if (photo.size > maxSize) {
                e.preventDefault();
                Swal.fire('Error', 'The image must not exceed 2MB', 'error');
                return;
            }
        }
    });
</script>


{{-- Valida solo mayusculas --}}
<script>
    // Función para validar solo mayúsculas en tiempo real
    function validateUpperCaseInput(inputElement) {
        // Guardar la posición del cursor
        const start = inputElement.selectionStart;
        const end = inputElement.selectionEnd;
        
        // Convertir a mayúsculas y permitir solo letras y espacios
        inputElement.value = inputElement.value.toUpperCase().replace(/[^A-ZÁÉÍÓÚÑÜ\s]/g, '');
        
        // Restaurar la posición del cursor
        inputElement.setSelectionRange(start, end);
    }

    // Aplicar validación cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        // Validación para el modal de creación
        const createNameInput = document.getElementById('name');
        if (createNameInput) {
            createNameInput.addEventListener('input', function() {
                validateUpperCaseInput(this);
            });
        }

        // Validación para el modal de edición
        const editNameInput = document.getElementById('editName');
        if (editNameInput) {
            editNameInput.addEventListener('input', function() {
                validateUpperCaseInput(this);
            });
        }
    });
</script>

@endpush