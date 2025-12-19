@extends('admin.layouts.master')

@section('content')
    <secttion>
        <div class="main-container container-fluid">
            <!-- breadcrumb -->
            <div class="breadcrumb-header justify-content-between">
                <div class="my-auto">
                    <h4 class="page-title">Administration</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Sectors</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sector management</li>
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

            {{-- Formulario de Registro --}}
            <div class="col-12">
                <div class="card box-shadow-0">
                    <div class="card-header">
                        <h4 class="card-title mb-1">Sector Registry</h4>
                        <p class="mb-2">We manage the registration of new sectors for the system</p>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card-body pt-0">
                                <form class="form-horizontal" method="POST" action="{{route('sectors.store')}}" enctype="multipart/form-data" id="sectorForm">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <div class="main-img-user avatar-xxl justify-center">
                                                    <img alt="photo" id="imagePreview" class="rounded-circle" src="{{asset('admin/img/users/8.jpg')}}">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">Upload Image</label>
                                                <input type="file" name="photo" id="photo" class="form-control border-light-gray" accept="image/*">
                                                <div class="invalid-feedback" id="photoError">This field is required</div>
                                                <small class="form-text text-muted">Maximum size: 4MB. Formats: JPG, PNG, GIF</small>
                                            </div>  
                                        </div>  

                                        <div class="col-md-6 col-sm-12">
                                            <div class="card-body pt-0">
                                                <div class="form-group">
                                                    <label for="">Name to sector</label>
                                                    <input type="text" class="form-control border-light-gray" id="name" name="name" placeholder="Add Name please" oninput="convertToUpperCase(this)" maxlength="100">
                                                    <div class="invalid-feedback" id="nameError"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Description</label>
                                                    <input type="text" class="form-control border-light-gray" id="description" name="description" placeholder="Add Description please" oninput="convertToUpperCase(this)" maxlength="255">
                                                    <div class="invalid-feedback" id="descriptionError"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Status</label>
                                                    <select name="status" id="status" class="form-control border-light-gray">
                                                        <option value="" disabled selected>Select One Please</option>
                                                        <option value="1">ENABLED</option>
                                                        <option value="2">MAINTENANCE</option>
                                                        <option value="3">DISABLED</option>
                                                    </select>
                                                    <div class="invalid-feedback" id="statusError"></div>
                                                </div>
                                                
                                                <div class="form-group mb-0 mt-3 justify-content-end">
                                                    <div>
                                                        <button type="button" class="btn btn-danger ms-4" onclick="window.history.back()">Cancel</button>
                                                        <button type="submit" class="btn btn-success mx-4">Register</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                </form>               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </secttion>

    <style>
         /* Personalizar bordes de formularios */

         .border-light-gray {
            border-color: #CCC !important;
        }
        .form-control:focus {
            border-color: #CCC;
            box-shadow: 0 0 0 0.25rem rgba(204, 204, 204, 0.25);
        }
    </style>
@endsection

@push('scripts')

{{-- Validacion y formato de imagen --}}
<script>
    // Vista previa de imagen
    document.getElementById('photo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagePreview');
        const errorDiv = document.getElementById('photoError');
        
        // Limpiar errores previos
        errorDiv.textContent = '';
        this.classList.remove('is-invalid');
        
        if (file) {
            // Validar tamaño (4MB máximo)
            if (file.size > 4 * 1024 * 1024) {
                errorDiv.textContent = 'The image could not be greater a 4MB';
                this.classList.add('is-invalid');
                return;
            }
            
            // Validar tipo de archivo
            const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
            if (!validTypes.includes(file.type)) {
                errorDiv.textContent = 'Only images are allowed JPG, PNG o GIF';
                this.classList.add('is-invalid');
                return;
            }
            
            // Mostrar vista previa
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    // Convertir a mayúsculas en tiempo real
    function convertToUpperCase(input) {
        const errorDiv = document.getElementById(input.id + 'Error');
        
        // Limpiar errores previos
        errorDiv.textContent = '';
        input.classList.remove('is-invalid');
        
        // Convertir a mayúsculas
        input.value = input.value.toUpperCase();
        
        // Validar que solo contenga letras, números y espacios
        if (input.value && !/^[A-Z0-9\sÑÁÉÍÓÚÜ]+$/.test(input.value)) {
            errorDiv.textContent = 'Only letters, numbers and spaces are allowed.';
            input.classList.add('is-invalid');
        }
    }

    // Validación del formulario antes de enviar
    document.getElementById('sectorForm').addEventListener('submit', function(e) {
        let isValid = true;
        
        // Validar nombre
        const nameInput = document.getElementById('name');
        const nameError = document.getElementById('nameError');
        if (!nameInput.value.trim()) {
            nameError.textContent = 'This name is required';
            nameInput.classList.add('is-invalid');
            isValid = false;
        }
        
        // Validar descripción
        const descInput = document.getElementById('description');
        const descError = document.getElementById('descriptionError');
        if (!descInput.value.trim()) {
            descError.textContent = 'This description is required';
            descInput.classList.add('is-invalid');
            isValid = false;
        }
        
        // Validar estado
        const statusInput = document.getElementById('status');
        const statusError = document.getElementById('statusError');
        if (!statusInput.value) {
            statusError.textContent = 'You must select a state';
            statusInput.classList.add('is-invalid');
            isValid = false;
        }
        
        // Validar imagen (si se subió alguna)
        const photoInput = document.getElementById('photo');
        const photoError = document.getElementById('photoError');
        if (photoInput.files.length > 0) {
            const file = photoInput.files[0];
            if (file.size > 4 * 1024 * 1024) {
                photoError.textContent = 'The image cannot be larger than 4MB';
                photoInput.classList.add('is-invalid');
                isValid = false;
            }
            
            const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
            if (!validTypes.includes(file.type)) {
                photoError.textContent = 'Only images are allowed JPG, PNG o GIF';
                photoInput.classList.add('is-invalid');
                isValid = false;
            }
        }
        
        if (!isValid) {
            e.preventDefault();
            // Scroll al primer error
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    // Limpiar errores al cambiar valores
    document.querySelectorAll('input, select').forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
            const errorDiv = document.getElementById(this.id + 'Error');
            if (errorDiv) {
                errorDiv.textContent = '';
            }
        });
    });
</script>

<script>
    // Eliminar registro (script existente)
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
                fetch(`/categories/${eventIdToDelete}`, {
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