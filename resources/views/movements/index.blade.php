@extends('admin.layouts.master')

@section('content')
<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-lg-12 ml-sm-auto px-4">
                <!-- Topbar -->
                <div class="topbar my-4 rounded">
                    <div class="d-flex justify-content-between align-items-center px-3">
                        <h5 class="m-0">Knight Movements</h5>
                    </div>
                </div>
    
                <!-- Movements List -->
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
                                        <th>Movement by</th>
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
                                        <td>{{ $movement->movementUser->name ?? 'N/A' }}</td>

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

                                                <form action="{{ route('movements.destroy', $movement) }}" 
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Delete this movement?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>

                                                @endif
                                                @if($movement->canExecute())
                                                <form action="{{ route('movements.execute', $movement) }}" 
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Execute this movement?')">
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
    
                        <!-- Paginación -->
                        @if($movements->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $movements->links() }}
                        </div>
                        @endif
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <!-- Modal para Nuevo Movimiento - VERSIÓN FINAL (sin verificación de capacidad) -->
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
                            <div class="col-md-3">
                                <label class="fw-bold">Movement Date</label>
                                <div class="form-control" style="border: none; background: transparent;">
                                    {{ date('d/m/Y') }}
                                </div>
                                <input type="hidden" name="movement_date" value="{{ date('Y-m-d') }}">
                            </div>
                            
                            <div class="col-md-4">
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
                            
                            <div class="col-md-2">
                                <label for="shift_select" class="fw-bold">Shift *</label>
                                <select id="shift_select" class="form-control border border-secondary">
                                    <option value="AM">AM</option>
                                    <option value="PM">PM</option>
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label for="limit_date_input" class="fw-bold">Limit Time</label>
                                <input type="time" id="limit_date_input" 
                                       class="form-control border border-secondary"
                                       value="12:00">
                            </div>
                        </div>

                        <!-- Información del caballo seleccionado -->
                        <div class="alert alert-info mb-3" id="horseInfo" style="display: none;">
                            <strong>Current Location:</strong> <span id="currentStable"></span>
                        </div>

                        <!-- Formulario para agregar destino -->
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label for="to_stable_id" class="fw-bold">Destination Stable *</label>
                                <select id="to_stable_id" class="form-control border border-secondary">
                                    <option value="">Select destination stable</option>
                                    @foreach($stables as $stable)
                                        <option value="{{ $stable->id }}">
                                            {{ $stable->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="fw-bold">&nbsp;</label>
                                <button type="button" id="addMovementBtn" class="btn btn-success w-100">
                                    <i class="fas fa-plus"></i> Add Move
                                </button>
                            </div>

                        </div>

                        <!-- Tabla de movimientos agregados -->
                        <div class="card mt-3">
                            <div class="card-header bg-primary text-white">
                                <h6 class="m-0">Movement List</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="movementsTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Horse</th>
                                                <th>Shift</th>
                                                <th>Destination</th>
                                                <th>Limit Time</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="movementsBody">
                                            <!-- Los movimientos se agregarán aquí -->
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- Resumen -->
                                {{-- <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>AM Movements:</strong> <span id="amCounter" class="badge bg-primary">0</span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>PM Movements:</strong> <span id="pmCounter" class="badge bg-warning">0</span>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <strong>Total:</strong> <span id="totalCounter" class="badge bg-success">0 movements</span>
                                    </div>
                                </div> --}}
                            </div>
                        </div>

                        <!-- Observaciones -->
                        <div class="mt-3">
                            <label for="notes" class="fw-bold">Observations</label>
                            <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="Additional observations..."></textarea>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" id="submitBtn" disabled>
                            <i class="bi bi-check-circle"></i> Save Movements
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

    <!-- Modal para Editar Movimiento -->
    <div class="modal fade" id="editMovementModal" tabindex="-1" aria-labelledby="editMovementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMovementModalLabel">
                        <i class="fas fa-edit me-2"></i>Edit Movement
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body" id="editMovementContent">
                    <!-- Contenido cargado dinámicamente -->
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3">Loading movement details...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Confirmar Eliminación de Detalle -->
    <div class="modal fade" id="confirmDeleteDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this movement detail?</p>
                    <input type="hidden" id="detailToDelete">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

</section>
@endsection

@push('scripts')

    <script>
        $(document).ready(function() {
            // Variables globales
            let movements = [];
            let rowCount = 0;
            const addedHorseShifts = new Set();
            
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
            
            // Función para validar movimiento (SIMPLIFICADA - sin verificación de capacidad)
            function validateMovement(horseId, stableId, shift) {
                // Validar campos requeridos
                if (!horseId || !stableId || !shift) {
                    Swal.fire('Error', 'Please fill all required fields', 'error');
                    return false;
                }
                
                // Verificar si el caballo ya tiene movimiento en este turno
                const horseShiftKey = `${horseId}-${shift}`;
                if (addedHorseShifts.has(horseShiftKey)) {
                    Swal.fire('Warning', 'This horse already has a movement in the ' + shift + ' shift', 'warning');
                    return false;
                }
                
                // Verificar que no sea el mismo establo (OPCIONAL - puedes comentar esto si quieres permitirlo)
                const currentStableId = $('#horse_id option:selected').data('stable-id');
                if (currentStableId && currentStableId == stableId) {
                    Swal.fire('Warning', 'This horse is already in this stable!', 'warning');
                    return false;
                }
                
                return true;
            }
            
            // Función para agregar movimiento a la tabla
            $('#addMovementBtn').on('click', function() {
                const horseId = $('#horse_id').val();
                const horseName = $('#horse_id option:selected').text();
                const shift = $('#shift_select').val();
                const stableId = $('#to_stable_id').val();
                const stableName = $('#to_stable_id option:selected').text();
                const limitTime = $('#limit_date_input').val();
                
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
                
                // Validar movimiento (solo verifica duplicados en mismo turno)
                const horseShiftKey = `${horseId}-${shift}`;
                if (addedHorseShifts.has(horseShiftKey)) {
                    Swal.fire('Warning', 'This horse already has a movement in the ' + shift + ' shift', 'warning');
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
                    limit_time: limitTime,
                    limit_date: formatLimitDate(limitTime)
                };
                
                // Agregar a la lista
                movements.push(movement);
                addedHorseShifts.add(horseShiftKey);
                
                // Actualizar tabla
                updateMovementsTable();
                
                // Limpiar formulario (solo destino y hora límite)
                $('#to_stable_id').val('');
                $('#limit_date_input').val(shift === 'AM' ? '12:00' : '18:00');
                
                // Mostrar confirmación
                Swal.fire({
                    icon: 'success',
                    title: 'Added',
                    text: 'Movement added to the list',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
            
            // Función para formatear fecha límite completa
            function formatLimitDate(time) {
                const today = "{{ date('Y-m-d') }}";
                return `${today} ${time}:00`;
            }
            
            // Función para actualizar tabla de movimientos
            function updateMovementsTable() {
                const tbody = $('#movementsBody');
                tbody.empty();
                
                let amCount = 0;
                let pmCount = 0;
                
                movements.forEach((movement, index) => {
                    // Contar por turno
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
                                ${movement.shift}
                                <input type="hidden" name="details[${index}][shift]" value="${movement.shift}">
                            </td>
                            <td>
                                ${movement.stable_name}
                                <input type="hidden" name="details[${index}][to_stable_id]" value="${movement.to_stable_id}">
                            </td>
                            <td>
                                ${movement.limit_time}
                                <input type="hidden" name="details[${index}][limit_date]" value="${movement.limit_date}">
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm remove-movement" 
                                        data-movement-id="${movement.id}"
                                        data-horse-shift="${movement.horse_id}-${movement.shift}">
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
                $('#totalCounter').text(movements.length + ' movements');
                
                // Habilitar/deshabilitar botón de envío
                $('#submitBtn').prop('disabled', movements.length === 0);
            }
            
            // Función para eliminar movimiento
            $(document).on('click', '.remove-movement', function() {
                const movementId = $(this).data('movement-id');
                const horseShift = $(this).data('horse-shift');
                
                // Encontrar y eliminar el movimiento
                const movementIndex = movements.findIndex(m => m.id === movementId);
                if (movementIndex !== -1) {
                    movements.splice(movementIndex, 1);
                    addedHorseShifts.delete(horseShift);
                    
                    // Actualizar tabla
                    updateMovementsTable();
                    
                    Swal.fire({
                        icon: 'info',
                        title: 'Removed',
                        text: 'Movement removed from the list',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
            
            // Event listeners
            $('#horse_id').on('change', function() {
                updateHorseInfo();
            });
            
            $('#shift_select').on('change', function() {
                // Ajustar hora límite por defecto según el turno
                const shift = $(this).val();
                const limitTimeInput = $('#limit_date_input');
                
                if (shift === 'AM') {
                    limitTimeInput.val('12:00');
                    limitTimeInput.attr('max', '12:00');
                } else if (shift === 'PM') {
                    limitTimeInput.val('18:00');
                    limitTimeInput.attr('min', '12:01');
                }
            });
            
            // Validar formulario antes de enviar
            $('#movementForm').on('submit', function(e) {
                if (movements.length === 0) {
                    e.preventDefault();
                    Swal.fire('Error', 'Please add at least one movement to the list', 'error');
                    return false;
                }
                
                // Mostrar loading en el submit
                $('#submitBtn').html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
                
                return confirm(`Are you sure you want to save ${movements.length} movements?`);
            });
            
            // Inicializar
            updateHorseInfo();
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
@endpush