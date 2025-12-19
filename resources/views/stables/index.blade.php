
@extends('admin.layouts.master')


@section('content')

   
<secttion>

    <div class="main-container container-fluid">

    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <h4 class="page-title">Administración</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Locations</a></li>
                <li class="breadcrumb-item active" aria-current="page">Location Management</li>
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

    {{-- Tabla $stables--}}
    <div class="row row-sm mt-4 mx-auto">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">horse locations</h3>
                    <a class="btn ripple btn-teal" data-bs-target="#select2modal" data-bs-toggle="modal" href="">+ New</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table border-top-0  table-bordered text-nowrap border-bottom" id="responsive-datatable">
                            <thead>
                                <tr>
                                    <th class="wd-5p border-bottom-0 bg-light">ID</th>
                                    <th class="wd-10p border-bottom-0 bg-light">NAME</th>
                                    <th class="wd-5p border-bottom-0 bg-light text-center">SECTOR</th>
                                    <th class="wd-5p border-bottom-0 bg-light text-center">TYPE</th>
                                    <th class="wd-5p border-bottom-0 bg-light text-center">CONDITION</th>
                                    <th class="wd-5p border-bottom-0 bg-light text-center">STATUS</th>
                                    <th class="wd-5p border-bottom-0 bg-light text-center">CAPACITY</th>
                                    <th class="wd-5p border-bottom-0 bg-light text-center">OPTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stables as $stable)
                                <tr>
                                    <td class="text-center">{{$stable->id}} </td>
                                    <td class="fw-light">{{$stable->name}}</td>
                                    <td class="fw-light text-center">{{$stable->sector->name}}</td>
                                    <td class="fw-light">{{$stable->type}}</td>
                                    <td class="fw-light">
                                         
                                        @if ($stable->condition == 'use')
                                            <span class="badge bg-secondary" style="padding:6px">In Use</span>
                                        @elseif($stable->condition == 'mant')
                                            <span class="badge bg-warning text-white" style="padding:6px">Maintenance</span>
                                        @elseif($stable->condition == 'clear')
                                            <span class="badge bg-success" style="padding:6px">Cleaning</span>
                                            @else
                                            <span class="badge bg-danger" style="padding:6px">Repairing</span>
                                        @endif
                                       
                                    </td>
                                    
                                    
                                
                                    <td class="fw-light text-center">
                                        @if($stable->status)
                                            <span class="text-success">Active</span>
                                        @else
                                            <span class="text-danger">Disabled</span>
                                        @endif
                                    </td>

                                    <td class="fw-light">
                                        {{$stable->capacity}} - Horse
                                    </td>
                                     
                                    
                                    <td class="text-center">
                                    
                                        <!-- Otros botones -->
                                        <button class="btn btn-sm btn-primary mx-3" onclick="openImagesModal({{ $stable->id }})">
                                            <span class="fe fe-image"></span>
                                        </button>
                                        
                                        <button id="bEdit" type="button" class="btn btn-sm btn-warning"  onclick="openEditModal({{ $stable->id }})">
                                            <span class="fe fe-edit"></span>
                                        </button>
                                    
                                        <button id="bDelStable" type="button" class="btn btn-sm btn-danger mx-3" 
                                                data-id="{{ $stable->id }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteStableModal">
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
                    <h6 class="mx-auto text-uppercase" style="color: #000">New Registration</h6>
                </div>
                <div class="modal-body">
                    <div class="col col-lg-12">
                    
                            <div class="m-2">
                                <form action="{{ route('stables.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-group">
                                        
                                    </div>

                                    {{-- Name --}}
                                    <div class="form-group">
                                        <label for="name">Name:</label>
                                        <input type="text" name="name" id="name" class="form-control border-light-gray uppercase-input" placeholder="Add Name">
                                    </div>


                                    {{-- Sectors / Capacity --}}
                                    <div class="row">

                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="location">Sectors:</label>
        
                                                <select name="sector_id" id="sector_id" class="form-control required border-light-gray">
                                                        @foreach ($sectors as $sector )
                                                        <option value="{{$sector->id}} ">{{$sector->name}}</option>
                                                        @endforeach
                                                </select>      
        
                                            
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <label for="capacity">Capacity:</label>
                                            <input type="text" name="capacity" id="capacity" class="form-control border-light-gray uppercase-input" placeholder="Add Only Number">
                                            <div id="capacity-error" class="text-danger small mt-1" style="display: none;">
                                                Only integers are allowed 
                                            </div>
                                        </div>

                                    </div>

                                    
                                     {{-- Type --}}
                                    <div class="form-group">
                                        <div class="control-group form-group">
                                            <label class="form-label">Type</label>
                                            
                                            <select name="type" id="type" class="form-control required border-light-gray">

                                                <option value="STABLE">STABLE</option>
                                                <option value="PADDOCK">PADDOCK</option>
                                                <option value="SAND">SAND</option>
                                               
                                            </select>
                                            <div class="invalid-feedback">Este campo es requerido</div>
                                        </div>
                                    </div>

                                     {{-- Conditions --}}
                                    <div class="form-group">
                                        <label for="condition">Conditions:</label>
                                        <select name="condition" id="condition" class="form-control border-light-gray" required>
                                            <option value="">Seleccione una condición</option>
                                            <option value="use">Occupying</option>
                                            <option value="mant">Maintenance</option>
                                            <option value="clear">Clean</option>
                                            <option value="repair">Repair</option>
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
    </div>
    <!-- End Basic modal -->


    {{-- Modal editar --}}
    <div class="modal" id="openEditModal">
        <div class="modal-dialog modal-dialog-centered" category="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header header-custom-edit">
                    <h6 class="d-block mx-auto text-uppercase" style="color: #000">Edit Location</h6>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">

                            <label for="editName">Name</label>
                            <input type="text" id="editName" name="name" class="form-control border-light-gray">
                            <input type="hidden" id="editId" name="id">
                            
                        </div>

                        <div class="row">

                            <div class="col-6">
                                <div class="form-group">

                                    <label for="editSector">Sector</label>
                                    
                                    <select id="editSector" name="sector" class="form-control border-light-gray">
                                        <!-- Opciones se llenan con JavaScript -->
                                    </select>

                                </div>
                            </div> 
                            
                            <div class="col-6">
                                <div class="form-group">

                                    <label for="editCapacity">Capacity</label>
                                    
                                    <input id="editCapacity" name="Capacity" class="form-control border-light-gray">

                                </div>
                            </div> 

                        </div>

                        <div class="row">
                            

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="editType">Type</label>
                                        <select id="editType" name="type" class="form-control border-light-gray">
                                            <!-- Opciones se llenan con JavaScript -->
                                        </select>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="editCondition">Condition</label>
                                        <select id="editCondition" name="condition" class="form-control border-light-gray">
                                            <!-- Opciones se llenan con JavaScript -->
                                        </select>
                                    </div>
                                </div>

                                <div class="col-4">
 
                                    <div class="form-group">
                                        <label for="editStatus">Status</label>
                                        <select id="editStatus" name="status" class="form-control border-light-gray">
                                            <option value="1">Active</option>
                                            <option value="0">Disabled</option>
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
    <div class="modal fade" id="deleteStableModal" tabindex="-1" role="dialog" aria-labelledby="deleteStableModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header header-custom-delete">
                    <h6 class="d-block mx-auto text-uppercase" id="deleteStableModalLabel" style="color: #000">Delete Location</h6>
                </div>
                <div class="modal-body m-10">
                    <p id="deleteUnitMessage" class="text-normal">¿Are you sure you want to delete this record? This action is irreversible..</p>
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

        /* Personalizar bordes de formularios */

        .border-light-gray {
            border-color: #CCC !important;
        }
        .form-control:focus {
            border-color: #CCC;
            box-shadow: 0 0 0 0.25rem rgba(204, 204, 204, 0.25);
        }
    


    </style>


</secttion>
                   

@endsection

@push('scripts')

    {{-- Editar Registro --}}
    <script>
        function openEditModal(stableId) {
            fetch(`/stables/${stableId}/edit`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Rellenar campos básicos
                    document.getElementById('editForm').action = `/stables/${data.id}`;
                    document.getElementById('editId').value = data.id;
                    document.getElementById('editName').value = data.name;
                    document.getElementById('editStatus').value = data.status ? '1' : '0';
        
                    // Rellenar select de sector
                    const sectorSelect = document.getElementById('editSector');
                    sectorSelect.innerHTML = '';
                    Object.entries(data.sectors).forEach(([value, label]) => {
                        const option = document.createElement('option');
                        option.value = value;
                        option.textContent = label;
                        if (value == data.sector_id) {
                            option.selected = true;
                        }
                        sectorSelect.appendChild(option);
                    });
        
                    // Rellenar select de tipo
                    const typeSelect = document.getElementById('editType');
                    typeSelect.innerHTML = '';
                    Object.entries(data.types).forEach(([value, label]) => {
                        const option = document.createElement('option');
                        option.value = value;
                        option.textContent = label;
                        if (value === data.type) {
                            option.selected = true;
                        }
                        typeSelect.appendChild(option);
                    });
        
                    // Rellenar select de condición
                    const conditionSelect = document.getElementById('editCondition');
                    conditionSelect.innerHTML = '';
                    Object.entries(data.conditions).forEach(([value, label]) => {
                        const option = document.createElement('option');
                        option.value = value;
                        option.textContent = label;
                        if (value === data.condition) {
                            option.selected = true;
                        }
                        conditionSelect.appendChild(option);
                    });
        
                    // Mostrar el modal
                    const editModal = new bootstrap.Modal(document.getElementById('openEditModal'));
                    editModal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cargar los datos del Establo');
                });
        }
        
        // Manejar el envío del formulario con AJAX
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const formData = new FormData(form);
            const url = form.action;
        
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
                    const editModal = bootstrap.Modal.getInstance(document.getElementById('openEditModal'));
                    editModal.hide();
                    location.reload();
                } else {
                    alert(data.message || 'Error al actualizar el Establo');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la solicitud');
            });
        });
    </script>


    {{-- Validar Solo ingresar numero --}}
    <script>
        document.getElementById('capacity').addEventListener('input', function(e) {
            const input = e.target;
            const errorDiv = document.getElementById('capacity-error');
            
            // Remover cualquier carácter que no sea número
            input.value = input.value.replace(/[^0-9]/g, '');
            
            // Mostrar u ocultar mensaje de error
            if (input.value.match(/[^0-9]/)) {
                errorDiv.style.display = 'block';
            } else {
                errorDiv.style.display = 'none';
            }
        });
        
        // Validación adicional al enviar el formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            const capacityInput = document.getElementById('capacity');
            const errorDiv = document.getElementById('capacity-error');
            
            if (capacityInput.value.match(/[^0-9]/)) {
                e.preventDefault();
                errorDiv.style.display = 'block';
                capacityInput.focus();
            }
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
            document.querySelectorAll('#bDelStable').forEach(button => {
                button.addEventListener('click', function () {
                    unitIdToDelete = this.getAttribute('data-id');
                    document.getElementById('deleteUnitMessage').textContent = 
                        '¿Are you sure you want to delete this record? This action is irreversible.';
                });
            });

            // Confirmar y realizar la eliminación
            document.getElementById('confirmUnitDelete').addEventListener('click', function () {
                if (unitIdToDelete) {
                    fetch(`/stables/${unitIdToDelete}`, {
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
                        document.getElementById('deleteUnitMessage').textContent = data.message || 'Establo eliminada correctamente.';
                        
                        // Desactivar botones
                        document.getElementById('confirmUnitDelete').disabled = true;
                        document.querySelector('#deleteStableModal [data-bs-dismiss="modal"]').disabled = true;

                        // Cerrar el modal y recargar
                        setTimeout(() => {
                            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteStableModal'));
                            modal.hide();
                            location.reload();
                        }, 1500);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('deleteUnitMessage').textContent = 
                            'Error al eliminar la Establo: ' + (error.message || 'Por favor, inténtelo de nuevo.');
                    });
                }
            });

            // Resetear el modal al cerrar
            document.getElementById('deleteStableModal').addEventListener('hidden.bs.modal', function() {
                document.getElementById('deleteUnitMessage').textContent = 
                    '¿Estás seguro de que deseas eliminar esta Establo? Esta acción no se puede deshacer.';
                document.getElementById('confirmUnitDelete').disabled = false;
                document.querySelector('#deleteStableModal [data-bs-dismiss="modal"]').disabled = false;
            });
        });
    </script>

    {{-- Validar la insercion de solo mayusculas --}}
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

