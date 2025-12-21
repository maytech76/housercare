@extends('admin.layouts.master')

@section('content')
<section>
    <div class="container">

        {{-- Encabezado --}}
        <div class="row">
            <div class="col-md-12 col-lg-12 ml-sm-auto px-4">

                <!-- Topbar -->
                <div class="topbar my-4 rounded">
                    <div class="d-flex justify-content-between align-items-center px-3">
                        <h5 class="m-0">Director Movements</h5>
                    </div>
                </div>
    
                <!-- Tablet Movements List -->
                <div class="card mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">All movements</h6>
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#newMovementModal">
                            <i class="bi bi-plus-circle"></i> New Movement
                        </button>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
    
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
    
                        <div class="table-responsive">
                            <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="responsive-datatable">
                                <thead>
                                    <tr style="background-color: #f2eeee">
                                        <th>Date</th>
                                        <th>Move Number</th>
                                        <th>Horse</th>
                                        <th>Assigned by</th>
                                        <th>Status</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($movements as $movement)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($movement->movement_date)->format('d/m/Y') }}</td>
                                        <td>
                                            <strong class="text-success">{{ $movement->movement_number }}</strong>
                                        </td>
                                        <td>
                                            {{ $movement->horse->name }}
                                        </td>
                                        <td>{{ $movement->assignedUser->name }}</td>

                                        <td>
                                            @if($movement->status == 'ASSIGNED')
                                                <span class="text-warning">ASSIGNED</span>
                                            @elseif($movement->status == 'PARTIALLY')
                                                <span class="text-info">PARTIALLY</span>
                                            @elseif($movement->status == 'EXECUTED')
                                                <span class="text-success">EXECUTED</span>
                                            @else
                                                <span class="text-secondary">{{ $movement->status }}</span>
                                            @endif
                                        </td>

                                        <td> {{-- Botones de options --}}

                                            <div>
                                                <button href="{{ route('movements.show', $movement) }}" 
                                                   class="btn btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                @if($movement->status == 'ASSIGNED')
                                                <button href="{{ route('movements.edit', $movement) }}" 
                                                   class="btn btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                
                                                <button class="btn btn-danger delete-movement-btn" 
                                                        title="Delete"
                                                        data-movement-id="{{ $movement->id }}"
                                                        data-movement-number="{{ $movement->movement_number }}"
                                                        data-horse-name="{{ $movement->horse->name }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                

                                                @endif
                                                @if($movement->canExecute())

                                                <form action="{{ route('movements.execute', $movement) }}" 
                                                    method="POST" class="d-inline execute-movement-form">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success" title="Execute">
                                                        <i class="bi bi-check-circle"></i>
                                                    </button>
                                                </form>
                                                
                                                @endif
                                            </div>


                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No movements found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
    
                    </div>
                </div>

            </div>
        </div>
    </div>

   <!-- Modal para Nuevo Movimiento -->
    <div class="modal fade" id="newMovementModal" tabindex="-1" aria-labelledby="newMovementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newMovementModalLabel">New Movement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form action="{{ route('movements.store') }}" method="POST" id="movementForm">
                    @csrf
                    
                    <div class="modal-body">
                        <!-- Información general -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="fw-bold">Movement Date</label>
                                <div class="form-control" style="border: none; background: transparent;">
                                    {{ date('d/m/Y') }}
                                </div>
                                <input type="hidden" name="movement_date" value="{{ date('Y-m-d') }}">
                            </div>
                            
                            <div class="col-md-8">
                                <label for="horse_id" class="fw-bold">Select Horse *</label>
                                <select name="horse_id" id="horse_id" class="form-select" required>
                                    <option value="">Select a horse</option>
                                    @foreach($horses as $horse)
                                        <option value="{{ $horse->id }}" 
                                                data-stable-id="{{ $horse->stable_id }}"
                                                data-stable-name="{{ $horse->stable->name ?? 'No stable' }}">
                                            {{ $horse->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Información del caballo seleccionado -->
                        <div class="alert alert-info mb-3" id="horseInfo" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Current Location:</strong> <span id="currentStable"></span>
                                </div>
                                <div class="text-muted">
                                    <small>You can add multiple movement details with different limit dates</small>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario para agregar detalles -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-primary text-white">
                                <h6 class="m-0">
                                    <i class="fas fa-plus-circle me-2"></i>Add Movement Details
                                    <small class="float-end">(Optional - You can add multiple)</small>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 align-items-center">
                                    <!-- Shift -->
                                    <div class="col-12 col-sm-6 col-md-2 col-lg-2">
                                        <label for="shift_select" class="form-label fw-bold mb-1">Shift *</label>
                                        <select id="shift_select" class="form-select border border-secondary">
                                            <option value="AM">AM</option>
                                            <option value="PM">PM</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Destination Stable -->
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                                        <label for="to_stable_id" class="form-label fw-bold mb-1">Destination Stable *</label>
                                        <select id="to_stable_id" class="form-select border border-secondary">
                                            <option value="">Select destination stable</option>
                                            @foreach($stables as $stable)
                                                <option value="{{ $stable->id }}">
                                                    {{ $stable->name }}
                                                    @if($stable->sector)
                                                        <small class="text-muted">({{ $stable->sector->name }})</small>
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- Limit Date & Time -->
                                    <div class="col-12 col-sm-8 col-md-4 col-lg-4">
                                        <label for="limit_date_input" class="form-label fw-bold mb-1">Limit Date</label>
                                        <div class="input-group">
                                            <input type="date" id="limit_date_input" 
                                                class="form-control border border-secondary">
                                            <span class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </span>
                                        </div>
                                        
                                    </div>
                                    
                                    <!-- Add Button -->
                                    <div class="col-12 col-sm-4 col-md-2 col-lg-2 d-grid mt-5">
                                        <button type="button" id="addMovementBtn" class="btn btn-success h-100" 
                                                title="Add movement detail">
                                            <i class="fas fa-plus"></i> Add 
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="alert alert-warning mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <small>
                                            You can add multiple movement details in the same 
                                            shift (AM/PM) but with different limit dates. 
                                            Each detail must have a unique combination of 
                                            location and limit date.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de movimientos agregados -->
                        <div class="card mt-3">
                            <div class="card-header bg-secondary text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="m-0">Movement Details Added</h6>
                                    <div>
                                        <span class="badge bg-primary" id="amCounter">0 AM</span>
                                        <span class="badge bg-warning ms-2" id="pmCounter">0 PM</span>
                                        <span class="badge bg-success ms-2" id="totalCounter">0 total</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="movementsTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="25%">Horse</th>
                                                <th width="10%">Shift</th>
                                                <th width="30%">Destination</th>
                                                <th width="20%">Limit Date</th>
                                                <th width="10%" class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="movementsBody">
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    No movement details added yet. You can still save the movement without details.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Observaciones -->
                        <div class="mt-3">
                            <label for="notes" class="fw-bold">Observations</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3" 
                                    placeholder="Additional observations about this movement...">{{ old('notes') }}</textarea>
                            <small class="text-muted">Optional - Any additional information about this movement</small>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-success" id="submitBtn">
                            <i class="bi bi-check-circle me-2"></i>Save Movement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Detalles de Asignaciones -->
    <div class="modal fade" id="movementDetailsModal" tabindex="-1" aria-labelledby="movementDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-secondary-gradient">
                    <h5 class="modal-title" id="movementDetailsModalLabel">
                        Movement N° - <span id="modalMovementNumber"></span>
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="movementDetailsContent">
                        <!-- Los detalles se cargarán aquí via AJAX -->
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Cargando detalles de la asignación</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Editar Movimiento (vacío, se llena dinámicamente) -->
    <div class="modal fade" id="editMovementModal" tabindex="-1" aria-labelledby="editMovementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMovementModalLabel">
                        <i class="fas fa-edit me-2"></i>Editttt Movement
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="editMovementContent">
                    <!-- El contenido se cargará aquí dinámicamente -->
                </div>
            </div>
        </div>
    </div>




</section>
@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    
    {{-- Script para creación de movimientos - Versión con AJAX y SweetAlert2 --}}
    <script>
        $(document).ready(function() {
            // Variables globales
            let movements = [];
            let rowCount = 0;
            
            // Función para actualizar información del caballo
            function updateHorseInfo() {
                const horseSelect = $('#horse_id');
                const horseInfo = $('#horseInfo');
                const currentStable = $('#currentStable');
                
                if (horseSelect.val()) {
                    const selectedOption = horseSelect.find('option:selected');
                    const stableName = selectedOption.data('stable-name');
                    
                    currentStable.text(stableName);
                    horseInfo.show();
                } else {
                    horseInfo.hide();
                }
            }
            
            // Función para agregar movimiento a la tabla
            $('#addMovementBtn').on('click', function() {
                const horseId = $('#horse_id').val();
                const horseName = $('#horse_id option:selected').text();
                const shift = $('#shift_select').val();
                const stableId = $('#to_stable_id').val();
                const stableName = $('#to_stable_id option:selected').text();
                const limitDate = $('#limit_date_input').val();
                
                // Validar campos básicos
                if (!horseId) {
                    Swal.fire('Error', 'Please select a horse', 'error');
                    return;
                }
                
                if (!stableId) {
                    Swal.fire('Error', 'Please select a destination stable', 'error');
                    return;
                }
                
                if (!shift) {
                    Swal.fire('Error', 'Please select a shift', 'error');
                    return;
                }
                
                // Validar fecha límite si se proporciona
                if (limitDate) {
                    const limitDateTime = new Date(limitDate);
                    const now = new Date();
                    if (limitDateTime < now) {
                        Swal.fire('Warning', 'Limit date cannot be in the past', 'warning');
                        return;
                    }
                }
                
                // Verificar si ya existe exactamente el mismo movimiento (mismo turno + mismo establo + misma fecha)
                const duplicate = movements.find(movement => 
                    movement.horse_id === horseId &&
                    movement.shift === shift &&
                    movement.to_stable_id === stableId &&
                    movement.limit_date === limitDate
                );
                
                if (duplicate) {
                    Swal.fire('Warning', 'This exact movement already exists in the list', 'warning');
                    return;
                }
                
                // Crear movimiento
                const movement = {
                    id: ++rowCount,
                    horse_id: horseId,
                    horse_name: horseName,
                    shift: shift,
                    to_stable_id: stableId,
                    stable_name: stableName,
                    limit_date: limitDate || null,
                    limit_date_display: limitDate ? formatDateTimeDisplay(limitDate) : 'No limit date'
                };
                
                // Agregar a la lista
                movements.push(movement);
                
                // Actualizar tabla
                updateMovementsTable();
                
                // Limpiar formulario (solo destino y fecha límite)
                $('#to_stable_id').val('');
                $('#limit_date_input').val('');
                
                // Mostrar confirmación
                Swal.fire({
                    icon: 'success',
                    title: 'Added',
                    text: 'Movement detail added to the list',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
            
            // Función para formatear fecha para visualización
            function formatDateTimeDisplay(dateTimeString) {
                if (!dateTimeString) return 'No limit date';
                const date = new Date(dateTimeString);
                return date.toLocaleString('es-ES', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                }).replace(',', '');
            }
            
            // Función para actualizar tabla de movimientos
            function updateMovementsTable() {
                const tbody = $('#movementsBody');
                tbody.empty();
                
                if (movements.length === 0) {
                    const emptyRow = `
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                No movement details added yet. You can still save the movement without details.
                            </td>
                        </tr>
                    `;
                    tbody.append(emptyRow);
                } else {
                    // Agrupar movimientos por turno para contar
                    let amCount = 0;
                    let pmCount = 0;
                    
                    movements.forEach((movement, index) => {
                        if (movement.shift === 'AM') amCount++;
                        if (movement.shift === 'PM') pmCount++;
                        
                        const row = `
                            <tr id="movement-row-${movement.id}">
                                <td>${index + 1}</td>
                                <td>
                                    ${movement.horse_name}
                                    <input type="hidden" name="details[${index}][horse_id]" value="${movement.horse_id}">
                                </td>
                                <td>
                                    <span class="badge ${movement.shift === 'AM' ? 'bg-primary' : 'bg-warning'}">
                                        ${movement.shift}
                                    </span>
                                    <input type="hidden" name="details[${index}][shift]" value="${movement.shift}">
                                </td>
                                <td>
                                    ${movement.stable_name}
                                    <input type="hidden" name="details[${index}][to_stable_id]" value="${movement.to_stable_id}">
                                </td>
                                <td>
                                    ${movement.limit_date_display}
                                    <input type="hidden" name="details[${index}][limit_date]" value="${movement.limit_date}">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-movement" 
                                            data-movement-id="${movement.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                        
                        tbody.append(row);
                    });
                    
                    // Actualizar contadores
                    $('#amCounter').text(amCount);
                    $('#pmCounter').text(pmCount);
                    $('#totalCounter').text(movements.length + ' detail(s)');
                }
                
                // Habilitar botón de envío (siempre habilitado ahora)
                $('#submitBtn').prop('disabled', false);
            }
            
            // Función para eliminar movimiento
            $(document).on('click', '.remove-movement', function() {
                const movementId = $(this).data('movement-id');
                
                // Encontrar y eliminar el movimiento
                const movementIndex = movements.findIndex(m => m.id === movementId);
                if (movementIndex !== -1) {
                    movements.splice(movementIndex, 1);
                    
                    // Actualizar tabla
                    updateMovementsTable();
                    
                    Swal.fire({
                        icon: 'info',
                        title: 'Removed',
                        text: 'Movement detail removed from the list',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
            
            // Configurar fecha mínima en el input datetime-local
            const limitDateInput = $('#limit_date_input');
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
            limitDateInput.attr('min', minDateTime);
            
            // Event listeners
            $('#horse_id').on('change', function() {
                updateHorseInfo();
            });
            
            // Cuando se cambia el turno, ajustar sugerencia de fecha
            $('#shift_select').on('change', function() {
                const shift = $(this).val();
                const now = new Date();
                
                if (shift === 'AM') {
                    // Para turno AM, sugerir hoy a las 12:00
                    now.setHours(12, 0, 0, 0);
                } else {
                    // Para turno PM, sugerir hoy a las 18:00
                    now.setHours(18, 0, 0, 0);
                }
                
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                
                limitDateInput.val(`${year}-${month}-${day}T${hours}:${minutes}`);
            });
            
            // ====== PARTE MODIFICADA: Envío del formulario con AJAX y SweetAlert2 ======
            $('#movementForm').on('submit', function(e) {
                e.preventDefault(); // Prevenir envío normal
                
                const form = $(this);
                const submitBtn = $('#submitBtn');
                const originalHtml = submitBtn.html();
                
                // Deshabilitar botón y mostrar loading inmediatamente
                submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
                
                // Enviar el formulario via AJAX
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        // Cerrar el modal
                        $('#newMovementModal').modal('hide');
                        
                        // Mostrar SweetAlert2 de éxito después de 300ms
                        setTimeout(() => {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Movement created successfully',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false,
                                timerProgressBar: true
                            }).then(() => {
                                // Recargar la página para actualizar la lista
                                window.location.reload();
                            });
                        }, 300);
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        submitBtn.html(originalHtml).prop('disabled', false);
                        
                        let errorMessage = 'Error creating movement';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Mostrar errores de validación
                            const errors = Object.values(xhr.responseJSON.errors).flat();
                            errorMessage = errors.join('<br>');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            title: 'Error!',
                            html: errorMessage,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
            // ====== FIN DE LA PARTE MODIFICADA ======
            
            // Inicializar
            updateHorseInfo();
            // Establecer valor inicial basado en el turno seleccionado
            $('#shift_select').trigger('change');
        });
    </script>
    


     {{-- Modal Details Move --}}
    <script>

        // Script para cargar detalles del movimiento en modal
        document.addEventListener('DOMContentLoaded', function() {
            // Modal de detalles
            const detailsModal = document.getElementById('movementDetailsModal');
            
            // Cuando se hace clic en cualquier botón de "Ver"
            document.addEventListener('click', function(event) {
                // Verificar si el clic fue en un botón de vista
                const viewBtn = event.target.closest('.btn-info[title="View"]');
                if (viewBtn) {
                    event.preventDefault();
                    
                    // Obtener la URL del movimiento desde el href del botón
                    const movementUrl = viewBtn.getAttribute('href');
                    if (!movementUrl) return;
                    
                    // Cargar los detalles del movimiento
                    loadMovementDetails(movementUrl);
                    
                    // Mostrar el modal
                    const modal = new bootstrap.Modal(detailsModal);
                    modal.show();
                }
            });

            // Función para cargar detalles del movimiento via AJAX
            function loadMovementDetails(url) {
                const detailsContent = document.getElementById('movementDetailsContent');
                
                // Mostrar spinner de carga
                detailsContent.innerHTML = `
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Cargando detalles del movimiento</p>
                    </div>
                `;
                
                // Realizar petición AJAX
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    // Actualizar contenido del modal con los datos recibidos
                    renderMovementDetails(data);
                })
                .catch(error => {
                    console.error('Error loading movement details:', error);
                    detailsContent.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            Error al cargar los detalles del movimiento. Por favor, intente nuevamente.
                        </div>
                    `;
                });
            }

            // Función para renderizar los detalles del movimiento
            function renderMovementDetails(movement) {
                const detailsContent = document.getElementById('movementDetailsContent');
                const modalTitle = document.getElementById('modalMovementNumber');
                
                // Actualizar título del modal
                if (modalTitle) {
                    modalTitle.textContent = movement.movement_number || 'N/A';
                }
                
                // Formatear fecha
                const formatDate = (dateString) => {
                    if (!dateString) return 'N/A';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('es-ES');
                };
                
                // Formatear hora
                const formatTime = (dateString) => {
                    if (!dateString) return 'N/A';
                    const date = new Date(dateString);
                    return date.toLocaleTimeString('es-ES', { 
                        hour: '2-digit', 
                        minute: '2-digit' 
                    });
                };
                
                // Determinar clase del badge según estado
                const getStatusBadge = (status) => {
                    const badges = {
                        'ASSIGNED': 'warning',
                        'PARTIALLY': 'info',
                        'EXECUTED': 'success',
                        'CANCELLED': 'danger'
                    };
                    return badges[status] || 'secondary';
                };
                
                // Generar HTML de los detalles
                const html = `
                    <div class="movement-details">
                        <!-- Información general del movimiento -->
                        <div class="card mb-4">
                            
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p><strong>Númber:</strong> ${movement.movement_number || 'N/A'}</p>
                                        <p><strong>Date:</strong> ${formatDate(movement.movement_date)}</p>
                                        <p><strong>Status:</strong> 
                                            <span class="text-${getStatusBadge(movement.status)}">
                                                ${movement.status || 'N/A'}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Horse:</strong> ${movement.horse?.name || 'N/A'}</p>
                                        <p><strong>Assigned_By:</strong> ${movement.assigned_user?.name || 'N/A'}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Executed_by:</strong> ${movement.executor?.name || 'No ejecutado'}</p>
                                        <p><strong>Executed_at:</strong> ${movement.executed_at ? formatDate(movement.executed_at) + ' ' + formatTime(movement.executed_at) : 'No ejecutado'}</p>
                                    </div>
                                </div>
                                ${movement.notes ? `
                                    <div class="mt-3">
                                        <strong>Observations:</strong>
                                        <p class="mb-0">${movement.notes}</p>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                        
                        <!-- Detalles del movimiento -->
                        <div class="card">
                            <div class="card-header border border-secondary">
                                <h5 class="mb-0">Detalles de Movimiento</h5>
                            </div>
                            <div class="card-body border border-secondary">
                                ${movement.details && movement.details.length > 0 ? `
                                    <div class="table-responsive">
                                        <table class="table table-bordered border border-secondary">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Shift</th>
                                                    <th>Sector</th>
                                                    <th>Location</th>
                                                    <th>Límit Date</th>
                                                    <th>Status</th>
                                                    <th>Ejecutado</th>
                                                </tr>
                                            </thead>
                                            <tbody class="border border-secondary">
                                                ${movement.details.map((detail, index) => `
                                                    <tr>
                                                        <td>${index + 1}</td>
                                                        <td>
                                                            <span class="${detail.shift === 'AM' ? 'text-primary' : 'text-warning'}">
                                                                ${detail.shift === 'AM' ? 'AM' : 'PM'}
                                                            </span>
                                                        </td>
                                                        <td>${detail.stable?.sector?.name || 'N/A'}</td>
                                                        <td>${detail.stable?.name || 'N/A'}</td>
                                                        <td>${detail.limit_date ? formatTime(detail.limit_date) : 'No límite'}</td>
                                                        <td>
                                                            <span class="${detail.is_executed ? 'text-success' : (new Date(detail.limit_date) < new Date() ? 'text-danger' : 'text-warning')}">
                                                                ${detail.is_executed ? 'Executed' : (new Date(detail.limit_date) > new Date() ? 'Expired' : 'Earring')}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            ${detail.is_executed ? 
                                                                `${detail.executed_by ? 'Por: ' + (detail.executor?.name || 'N/A') + '<br>' : ''}
                                                                ${formatDate(detail.executed_at)} ${formatTime(detail.executed_at)}` 
                                                                : 'No Executed'}
                                                        </td>
                                                    </tr>
                                                `).join('')}
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                ` : `
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        This move has no associated details.
                                    </div>
                                `}
                            </div>
                        </div>
                    </div>
                `;
                
                detailsContent.innerHTML = html;
            }

            // Cerrar modal cuando se oculta
            detailsModal.addEventListener('hidden.bs.modal', function() {
                const detailsContent = document.getElementById('movementDetailsContent');
                // Restaurar spinner para la próxima vez
                detailsContent.innerHTML = `
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Cargando detalles del movimiento</p>
                    </div>
                `;
            });
        });
    </script>


    {{-- Script para manejar la edición de movimientos --}}
    <script>
        $(document).ready(function() {
            // Variables globales para la edición
            let newDetails = [];
            let deletedDetails = [];
            let hasChanges = false;
            
            // Cuando se hace clic en el botón de editar
            $(document).on('click', '.btn-warning[title="Edit"]', function(e) {
                e.preventDefault();
                
                const editUrl = $(this).attr('href');
                if (!editUrl) return;
                
                // Resetear flag de cambios
                hasChanges = false;
                
                // Mostrar spinner en el modal
                $('#editMovementContent').html(`
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3">Loading movement details...</p>
                    </div>
                `);
                
                // Mostrar el modal
                const editModal = new bootstrap.Modal(document.getElementById('editMovementModal'));
                editModal.show();
                
                // Cargar el formulario via AJAX
                fetch(editUrl + '?ajax=1', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    $('#editMovementContent').html(data.html);
                    // Inicializar el formulario de edición
                    initializeEditForm();
                })
                .catch(error => {
                    console.error('Error loading edit form:', error);
                    $('#editMovementContent').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            Error loading edit form. Please try again.
                            <br><small>${error.message}</small>
                        </div>
                    `);
                });
            });
            
            // Función para inicializar el formulario de edición
            function initializeEditForm() {
                // Resetear variables
                newDetails = [];
                deletedDetails = [];
                hasChanges = false;
                
                // Monitorear cambios en las observaciones
                $('#notes').on('input', function() {
                    const originalNotes = $(this).attr('data-original') || $(this).val();
                    if ($(this).val() !== originalNotes) {
                        hasChanges = true;
                    }
                });
                
                // Agregar nuevo detalle
                $('#addNewDetailBtn').off('click').on('click', function() {
                    addNewDetail();
                });
                
                // Eliminar detalle existente
                $('.delete-detail-btn').off('click').on('click', function() {
                    const detailId = $(this).data('detail-id');
                    deleteExistingDetail(detailId);
                });
                
                // Envío del formulario
                $('#editMovementForm').off('submit').on('submit', function(e) {
                    e.preventDefault();
                    submitEditForm();
                });
            }
            

            // Función para agregar nuevo detalle (en edición)
            function addNewDetail() {
                const shift = $('#new_shift').val();
                const stableId = $('#new_to_stable_id').val();
                const stableName = $('#new_to_stable_id option:selected').text().split('(')[0].trim();
                const limitDate = $('#new_limit_date').val();
                
                // Validaciones
                if (!stableId) {
                    Swal.fire('Error', 'Please select a destination stable', 'error');
                    return;
                }
                
                // Verificar duplicados exactos (mismo turno + mismo establo + misma fecha)
                const isDuplicate = newDetails.some(detail => 
                    detail.shift === shift && 
                    detail.to_stable_id === stableId && 
                    detail.limit_date === limitDate
                );
                
                if (isDuplicate) {
                    Swal.fire('Warning', 'This exact movement detail already exists in the list', 'warning');
                    return;
                }
                
                // También verificar en detalles existentes no eliminados
                let existingDuplicate = false;
                $('#existingDetailsBody tr:visible').each(function() {
                    const existingShift = $(this).find('input[name*="[shift]"]').val();
                    const existingStableId = $(this).find('input[name*="[to_stable_id]"]').val();
                    const existingLimitDate = $(this).find('input[name*="[limit_date]"]').val();
                    
                    if (existingShift === shift && 
                        existingStableId === stableId && 
                        existingLimitDate === limitDate) {
                        existingDuplicate = true;
                        return false; // Salir del each
                    }
                });
                
                if (existingDuplicate) {
                    Swal.fire('Warning', 'This exact movement detail already exists in the movement', 'warning');
                    return;
                }
                
                // Crear nuevo detalle
                const newDetail = {
                    id: 'new-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9),
                    shift: shift,
                    to_stable_id: stableId,
                    stable_name: stableName,
                    limit_date: limitDate || null,
                    limit_date_display: limitDate ? formatDateTimeDisplay(limitDate) : 'No limit date'
                };
                
                newDetails.push(newDetail);
                hasChanges = true;
                updateNewDetailsTable();
                
                // Limpiar formulario
                $('#new_to_stable_id').val('');
                $('#new_limit_date').val('');
            }

            // Función para formatear fecha para visualización
            function formatDateTimeDisplay(dateTimeString) {
                if (!dateTimeString) return 'No limit date';
                const date = new Date(dateTimeString);
                return date.toLocaleString('es-ES', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                }).replace(',', '');
            }
            
            // Función para actualizar tabla de nuevos detalles
            function updateNewDetailsTable() {
                const tbody = $('#newDetailsBody');
                const card = $('#newDetailsCard');
                const inputsContainer = $('#newDetailsInputs');
                
                // Limpiar tabla y inputs
                tbody.empty();
                inputsContainer.empty();
                
                // Agregar filas
                newDetails.forEach((detail, index) => {
                    const row = `
                        <tr id="new-detail-${detail.id}">
                            <td>${index + 1}</td>
                            <td>
                                <span class="badge ${detail.shift === 'AM' ? 'bg-primary' : 'bg-warning'}">
                                    ${detail.shift}
                                </span>
                            </td>
                            <td>${detail.stable_name}</td>
                            <td>${detail.limit_date ? new Date(detail.limit_date).toLocaleString() : 'No limit'}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm remove-new-detail" 
                                        data-detail-id="${detail.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    tbody.append(row);
                    
                    // Agregar inputs hidden al formulario
                    inputsContainer.append(`<input type="hidden" name="new_details[${index}][shift]" value="${detail.shift}">`);
                    inputsContainer.append(`<input type="hidden" name="new_details[${index}][to_stable_id]" value="${detail.to_stable_id}">`);
                    if (detail.limit_date) {
                        inputsContainer.append(`<input type="hidden" name="new_details[${index}][limit_date]" value="${detail.limit_date}">`);
                    }
                });
                
                // Mostrar/ocultar card
                card.css('display', newDetails.length > 0 ? 'block' : 'none');
            }
            
            // Eliminar detalle existente
            function deleteExistingDetail(detailId) {
                // Ocultar fila
                $(`#detail-row-${detailId}`).hide();
                
                // Agregar a la lista de eliminados
                if (!deletedDetails.includes(detailId)) {
                    deletedDetails.push(detailId);
                    hasChanges = true;
                    
                    // Agregar input hidden
                    $('#deletedDetailsInputs').append(
                        `<input type="hidden" name="deleted_details[]" value="${detailId}">`
                    );
                }
            }
            
            // Eliminar nuevo detalle (event delegation)
            $(document).on('click', '.remove-new-detail', function() {
                const detailId = $(this).data('detail-id');
                
                const index = newDetails.findIndex(detail => detail.id === detailId);
                if (index !== -1) {
                    newDetails.splice(index, 1);
                    hasChanges = true;
                    updateNewDetailsTable();
                }
            });
            
            // Función para enviar el formulario de edición
            function submitEditForm() {
                const form = $('#editMovementForm');
                const submitBtn = $('#submitUpdateBtn');
                const originalHtml = submitBtn.html();
                
                // Verificar si hay cambios
                const notesChanged = $('#notes').val() !== ($('#notes').attr('data-original') || $('#notes').val());
                const hasNewDetails = newDetails.length > 0;
                const hasDeletedDetails = deletedDetails.length > 0;
                
                if (!hasChanges && !notesChanged && !hasNewDetails && !hasDeletedDetails) {
                    // No hay cambios, simplemente cerrar el modal
                    $('#editMovementModal').modal('hide');
                    return;
                }
                
                // Validar que al menos quede un detalle
                const totalExisting = $('#existingDetailsBody tr:visible').length;
                if (totalExisting + newDetails.length === 0) {
                    Swal.fire('Error', 'The movement must have at least one detail', 'error');
                    return false;
                }
                
                // Deshabilitar botón y mostrar loading
                submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Updating...').prop('disabled', true);
                
                // Enviar via AJAX
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Cerrar modal inmediatamente
                            $('#editMovementModal').modal('hide');
                            
                            // Mostrar SweetAlert2 de éxito después de cerrar el modal
                            setTimeout(() => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    html: `Movement <strong>${response.movement_number}</strong> updated successfully`,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    // Recargar la página
                                    window.location.reload();
                                });
                            }, 300);
                        } else {
                            Swal.fire('Error!', response.message || 'An error occurred', 'error');
                            submitBtn.html(originalHtml).prop('disabled', false);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        Swal.fire('Error!', 'An error occurred while updating: ' + error, 'error');
                        submitBtn.html(originalHtml).prop('disabled', false);
                    }
                });
            }
            
            // Cuando el modal se cierra, limpiar datos
            $('#editMovementModal').on('hidden.bs.modal', function() {
                newDetails = [];
                deletedDetails = [];
                hasChanges = false;
                $('#newDetailsInputs').empty();
                $('#deletedDetailsInputs').empty();
                $('#editMovementContent').empty();
            });
        });
    </script>


     {{-- Eliminar Movimiento --}}
    <script>
        $(document).ready(function() {
            // Handle click on delete buttons
            $(document).on('click', '.delete-movement-btn', function(e) {
                e.preventDefault();
                
                const button = $(this);
                const movementId = button.data('movement-id');
                const movementNumber = button.data('movement-number');
                const horseName = button.data('horse-name');
                
                // Confirm with SweetAlert2
                Swal.fire({
                    title: 'Delete Movement?',
                    html: `
                        <div class="text-center">
                            <p>Movement: <strong class="text-success">${movementNumber}</strong></p>
                            <p>Horse: <strong>${horseName}</strong></p>
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                This action cannot be undone
                            </div>
                        </div>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    width: 500
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Deleting...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Send DELETE request
                        $.ajax({
                            url: `/movements/${movementId}`,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: response.message || 'Movement deleted successfully',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr) {
                                let errorMsg = 'Error deleting movement';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMsg = xhr.responseJSON.message;
                                }
                                Swal.fire('Error', errorMsg, 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>

    {{-- Script para manejar ejecución de movimientos con SweetAlert2 --}}
    <script>
        $(document).ready(function() {
            // Manejar clic en formularios de ejecutar movimiento
            $(document).on('submit', 'form[action*="execute"]', function(e) {
                e.preventDefault();
                
                const executeForm = $(this);
                const executeUrl = executeForm.attr('action');
                const movementRow = executeForm.closest('tr');
                const movementNumber = movementRow.find('td:eq(1) strong').text().trim();
                const horseName = movementRow.find('td:eq(2)').text().trim();
                
                // Mostrar SweetAlert2 de confirmación
                Swal.fire({
                    title: 'Execute Movement?',
                    html: `
                        <div class="text-center">
                            <p>Are you sure you want to execute this movement?</p>
                            <div class="card border-light mb-3">
                                <div class="card-body">
                                    <p class="mb-1"><strong>Movement:</strong> <span class="text-success">${movementNumber}</span></p>
                                    <p class="mb-0"><strong>Horse:</strong> ${horseName}</p>
                                </div>
                            </div>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="bi bi-check-circle me-2"></i>Execute',
                    cancelButtonText: '<i class="fas fa-times me-2"></i>Close',
                    reverseButtons: true,
                    width: 500
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Mostrar loading
                        Swal.fire({
                            title: 'Executing...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Enviar el formulario via AJAX
                        $.ajax({
                            url: executeUrl,
                            method: 'POST',
                            data: executeForm.serialize(),
                            success: function(response) {
                                Swal.fire({
                                    title: 'Executed!',
                                    text: response.message || 'Movement executed successfully',
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false,
                                    timerProgressBar: true
                                }).then(() => {
                                    // Recargar la página para actualizar la lista
                                    window.location.reload();
                                });
                            },
                            error: function(xhr) {
                                let errorMsg = 'Error executing movement';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMsg = xhr.responseJSON.message;
                                }
                                Swal.fire('Error', errorMsg, 'error');
                            }
                        });
                    }
                });
                
                return false;
            });
        });
    </script>

@endpush