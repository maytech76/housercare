@extends('admin.layouts.master')

@section('content')

    <section>

        {{-- Encabezado / Tabla --}}
        <div class="main-container container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>Gestión de Asignaciones</h4>

                            <button class="btn btn-warning" type="button" id="resetAssignmentsBtn"
                                title="Resetear asignaciones ejecutados de días anteriores" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fas fa-sync-alt me-1"></i> Reset Assignments
                            </button>

                            <a href="{{ route('assignments.create') }}" class="btn btn-primary float-right">
                                <i class="fas fa-plus"></i> New Assignment
                            </a>
                        </div>

                        <div class="card-body">
                            <!-- Tabla de asignaciones -->
                            <div class="table-responsive">
                                <table class="table border-top-0 table-bordered text-nowrap border-bottom"
                                    id="responsive-datatable">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 bg-light">Date</th>
                                            <th class="border-bottom-0 bg-light">Assig Number</th>
                                            <th class="border-bottom-0 bg-light">Horse</th>
                                            <th class="border-bottom-0 bg-light">Condition</th>
                                            <th class="border-bottom-0 bg-light">Asigned_by</th>
                                            <th class="border-bottom-0 bg-light">Status</th>
                                            <th class="border-bottom-0 bg-light">Excecuted_by</th>
                                            <th class="border-bottom-0 bg-light">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($assignments as $assignment)
                                            <tr>
                                                <td>{{ $assignment->assigned_date }}</td>
                                                <td class="text-success">{{ $assignment->assigned_number }}</td>
                                                <td>{{ $assignment->horse->name }}</td>
                                                <td>{{ $assignment->horse->condition }}</td>
                                                <td>{{ $assignment->assignedBy->name }}</td>
                                                <td>
                                                    <span
                                                        class="text-{{ $assignment->status == 'ASSIGNED' ? 'warning' : 'success' }}">
                                                        {{ $assignment->status }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($assignment->executedBy)
                                                        <span class="text-success">
                                                            <strong>{{ $assignment->executedBy->name }}</strong>
                                                        </span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    <!-- Botón para ver detalles en modal -->
                                                    <button class="btn btn-sm btn-info view-assignment-details"
                                                        data-id="{{ $assignment->id }}" title="Ver Detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </button>

                                                    @if ($assignment->status == 'ASSIGNED')
                                                        <button class="btn btn-sm btn-warning edit-assignment-modal-btn"
                                                            data-id="{{ $assignment->id }}" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger delete-assignment"
                                                            data-id="{{ $assignment->id }}" title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @else
                                                        <span class="text-muted">No editable</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">No se encontraron asignaciones</td>
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

    </section>

    <!-- Modal para Detalles de Asignaciones -->
    <div class="modal fade" id="assignedDetailsModal" tabindex="-1" aria-labelledby="assignedDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-secondary-gradient">
                    <h5 class="modal-title" id="assignedDetailsModalLabel">
                        Asignacion N° - <span id="modalAssignedNumber"></span>
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="assignedDetailsContent">
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

    <!-- Modal para Editar Asignacion -->
    @include('assignments.partials.edit-assigned-modal')
@endsection

@push('styles')
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@push('scripts')
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Show and Close Modal Details --}}
    <script>
        $(document).ready(function() {
            // Manejar clic en botón de ver detalles
            $(document).on('click', '.view-assignment-details', function() {
                const assignmentId = $(this).data('id');
                console.log('Clicked assignment ID:', assignmentId);
                loadAssignmentDetails(assignmentId);
            });

            function loadAssignmentDetails(assignmentId) {
                // Mostrar spinner
                $('#assignedDetailsContent').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Cargando detalles de la asignación</p>
                    </div>
                `);

                // Mostrar modal
                $('#assignedDetailsModal').modal('show');

                // Hacer petición AJAX al método DETAILS
                $.ajax({
                    url: '{{ route('assignments.details', '') }}/' + assignmentId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log('AJAX Success:', response);
                        if (response.success) {
                            $('#modalAssignedNumber').text(response.assigned.assigned_number);
                            $('#assignedDetailsContent').html(response.html);
                        } else {
                            showError('Error al cargar los detalles: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        let errorMessage = 'Error al cargar los detalles de la asignación';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showError(errorMessage);
                    }
                });
            }

            function showError(message) {
                $('#assignedDetailsContent').html(`
                    <div class="alert alert-danger text-center">
                        <i class="fas fa-exclamation-triangle"></i> ${message}
                    </div>
                `);
            }

            // Limpiar modal cuando se cierre
            $('#assignedDetailsModal').on('hidden.bs.modal', function() {
                $('#modalAssignedNumber').text('');
                $('#assignedDetailsContent').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Cargando detalles de la asignación</p>
                    </div>
                `);
            });
        });
    </script>

    {{-- Open Modal Edit --}}
    <script>
        $(document).ready(function() {
            let currentAssignmentId = null;

            // Manejar clic en botón de editar
            $(document).on('click', '.edit-assignment-modal-btn', function() {
                const assignmentId = $(this).data('id');
                currentAssignmentId = assignmentId;
                console.log('Edit button clicked - Assignment ID:', assignmentId);
                openEditModal(assignmentId);
            });

            function openEditModal(assignmentId) {
                // Limpiar datos anteriores
                clearEditModal();

                // Mostrar el modal de edición inmediatamente
                $('#editAssignedModal').modal('show');

                // Mostrar spinner en el área de contenido
                showLoadingSpinner();

                // Cargar datos via AJAX
                loadAssignmentDataForEdit(assignmentId);
            }

            function showLoadingSpinner() {
                $('#edit_amSuppliesBody').html(`
                    <tr>
                        <td colspan="4" class="text-center py-3">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            Cargando servicios...
                        </td>
                    </tr>
                `);

                $('#edit_pmSuppliesBody').html(`
                    <tr>
                        <td colspan="4" class="text-center py-3">
                            <div class="spinner-border spinner-border-sm text-warning" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            Cargando servicios...
                        </td>
                    </tr>
                `);
            }

            function loadAssignmentDataForEdit(assignmentId) {
                const url = `{{ url('assignments') }}/${assignmentId}/edit`;
                console.log('Making AJAX request to:', url);

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log('AJAX Success - Response:', response);

                        if (response.success && response.assignment) {
                            populateEditForm(response.assignment, response.services);
                        } else {
                            console.error('Error in response:', response);
                            showEditError('Error en la respuesta del servidor: ' + (response.message ||
                                'Datos no válidos'));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        let errorMessage = 'Error al cargar los datos de la asignación';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.status === 404) {
                            errorMessage = 'Asignación no encontrada';
                        } else if (xhr.status === 500) {
                            errorMessage = 'Error interno del servidor';
                        }
                        showEditError(errorMessage);
                    }
                });
            }

            function populateEditForm(assignment, services) {
                try {
                    console.log('Populating form with assignment:', assignment);

                    // 1. Llenar datos básicos de la asignación
                    $('#editModalAssignedNumber').text(assignment.assigned_number || 'N/A');
                    $('#horse_id').val(assignment.horse ? assignment.horse.name : 'Caballo no encontrado');
                    $('#assigned_date').val(assignment.assigned_date || '');
                    $('#edit_notes').val(assignment.notes || '');
                    $('#edit_assignment_id').val(assignment.id || '');

                    // 2. Llenar servicios existentes
                    if (assignment.assignment_details && assignment.assignment_details.length > 0) {
                        console.log('Found assignment details:', assignment.assignment_details.length);
                        populateExistingServices(assignment.assignment_details);
                    } else {
                        console.log('No assignment details found');
                        showNoServicesMessage();
                    }

                    // 3. Llenar select de servicios
                    if (services && services.length > 0) {
                        populateServicesSelect(services);
                    } else {
                        $('#edit_service_id').html('<option value="">No hay servicios disponibles</option>');
                    }

                } catch (error) {
                    console.error('Error in populateEditForm:', error);
                    showEditError('Error al procesar los datos: ' + error.message);
                }
            }

            function populateExistingServices(assignmentDetails) {
                console.log('Populating existing services:', assignmentDetails);

                // Limpiar tablas
                $('#edit_amSuppliesBody').empty();
                $('#edit_pmSuppliesBody').empty();

                let amCount = 0;
                let pmCount = 0;

                assignmentDetails.forEach((detail, index) => {
                    const serviceName = detail.service ? detail.service.name : 'Servicio no encontrado';
                    const limitDate = detail.limit_date ? formatDate(detail.limit_date) : '-';
                    const statusBadge = getStatusBadge(detail.is_executed);
                    const actionButton = getActionButton(detail);

                    const row = createServiceRow(serviceName, statusBadge, limitDate, actionButton, detail
                        .id);

                    if (detail.shift === 'AM') {
                        $('#edit_amSuppliesBody').append(row);
                        amCount++;
                    } else if (detail.shift === 'PM') {
                        $('#edit_pmSuppliesBody').append(row);
                        pmCount++;
                    } else if (detail.shift === 'BOTH') {
                        $('#edit_amSuppliesBody').append(row);
                        $('#edit_pmSuppliesBody').append(row);
                        amCount++;
                        pmCount++;
                    }
                });

                // Actualizar contadores
                updateCounters(amCount, pmCount);
            }

            function formatDate(dateString) {
                try {
                    return new Date(dateString).toLocaleDateString('es-ES');
                } catch (error) {
                    return dateString;
                }
            }

            function getStatusBadge(isExecuted) {
                return isExecuted ?
                    '<span class="badge bg-success">EJECUTADO</span>' :
                    '<span class="badge bg-warning">PENDIENTE</span>';
            }

            function getActionButton(detail) {
                if (detail.is_executed) {
                    return '<button class="btn btn-sm btn-outline-secondary" disabled><i class="fas fa-trash"></i></button>';
                } else {
                    return `<button class="btn btn-sm btn-outline-danger remove-existing-service" data-detail-id="${detail.id}"><i class="fas fa-trash"></i></button>`;
                }
            }

            function createServiceRow(serviceName, statusBadge, limitDate, actionButton, detailId) {
                return `
                    <tr>
                        <td>${serviceName}</td>
                        <td class="text-center">${statusBadge}</td>
                        <td class="text-center">${limitDate}</td>
                        <td class="text-center">${actionButton}</td>
                    </tr>
                `;
            }

            function showNoServicesMessage() {
                $('#edit_amSuppliesBody').html(`
                    <tr class="empty-message">
                        <td colspan="4" class="text-center text-muted py-3">
                            <i class="fas fa-info-circle"></i> No hay servicios asignados
                        </td>
                    </tr>
                `);
                $('#edit_pmSuppliesBody').html(`
                    <tr class="empty-message">
                        <td colspan="4" class="text-center text-muted py-3">
                            <i class="fas fa-info-circle"></i> No hay servicios asignados
                        </td>
                    </tr>
                `);
            }

            function updateCounters(amCount, pmCount) {
                $('#edit_amCount').text(amCount + ' servicio' + (amCount !== 1 ? 's' : ''));
                $('#edit_pmCount').text(pmCount + ' servicio' + (pmCount !== 1 ? 's' : ''));
            }

            function populateServicesSelect(services) {
                let options = '<option value="">Seleccionar Servicio</option>';
                services.forEach(service => {
                    options += `<option value="${service.id}">${service.name}</option>`;
                });
                $('#edit_service_id').html(options);
            }

            function showEditError(message) {
                alert('Error: ' + message);
            }

            function clearEditModal() {
                $('#editModalAssignedNumber').text('');
                $('#horse_id').val('');
                $('#assigned_date').val('');
                $('#edit_notes').val('');
                $('#edit_assignment_id').val('');
                $('#edit_amSuppliesBody').empty();
                $('#edit_pmSuppliesBody').empty();
                $('#edit_amCount').text('0 servicios');
                $('#edit_pmCount').text('0 servicios');
                $('#edit_service_id').html('<option value="">Seleccionar Servicio</option>');
                $('#edit_shift').val('AM');
                $('#edit_limit_date').val('');
                currentAssignmentId = null;

                // Limpiar servicios nuevos agregados
                if (window.addedEditServices) {
                    window.addedEditServices.clear();
                }
                window.editServiceCount = 0;
            }

            // Agregar nuevo servicio
            $('#edit_addServicetBtn').on('click', function() {
                const serviceId = $('#edit_service_id').val();
                const serviceName = $('#edit_service_id option:selected').text();
                const shift = $('#edit_shift').val();
                const limitDate = $('#edit_limit_date').val();

                if (!serviceId) {
                    alert('Por favor selecciona un servicio');
                    return;
                }

                addServiceToEditTable(serviceId, serviceName, shift, limitDate);
            });

            // FUNCIÓN FALTANTE - Agregar servicio a la tabla
            function addServiceToEditTable(serviceId, serviceName, shift, limitDate) {
                // Inicializar contadores si no existen
                if (typeof window.editServiceCount === 'undefined') {
                    window.editServiceCount = 0;
                }
                if (typeof window.addedEditServices === 'undefined') {
                    window.addedEditServices = new Set();
                }

                // Crear clave única para evitar duplicados
                const serviceKey = `${serviceId}-${shift}`;

                // Verificar si ya existe
                if (window.addedEditServices.has(serviceKey)) {
                    alert('Este servicio ya está agregado para este turno');
                    return;
                }

                // Agregar al conjunto de servicios
                window.addedEditServices.add(serviceKey);
                window.editServiceCount++;

                // Formatear la fecha límite para mostrar
                const displayLimitDate = limitDate ?
                    new Date(limitDate).toLocaleDateString('es-ES') :
                    '<span class="text-muted">No date</span>';

                // Crear la fila de la tabla
                const newRow = `
                    <tr id="edit_new_service_${window.editServiceCount}">
                        <td>${serviceName}</td>
                        <td class="text-center"><span class="badge bg-info">NUEVO</span></td>
                        <td class="text-center">${displayLimitDate}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger remove-new-service" 
                                    data-service-key="${serviceKey}">
                                <i class="fas fa-trash"></i>
                            </button>
                            <!-- Campos hidden para el formulario -->
                            <input type="hidden" name="new_services[${window.editServiceCount}][service_id]" value="${serviceId}">
                            <input type="hidden" name="new_services[${window.editServiceCount}][shift]" value="${shift}">
                            <input type="hidden" name="new_services[${window.editServiceCount}][limit_date]" value="${limitDate || ''}">
                        </td>
                    </tr>
                `;

                // Agregar a la tabla según el turno seleccionado
                if (shift === 'AM') {
                    $('#edit_amSuppliesBody').append(newRow);
                    updateAmCounter();
                } else if (shift === 'PM') {
                    $('#edit_pmSuppliesBody').append(newRow);
                    updatePmCounter();
                } else if (shift === 'BOTH') {
                    $('#edit_amSuppliesBody').append(newRow);
                    $('#edit_pmSuppliesBody').append(newRow);
                    updateAmCounter();
                    updatePmCounter();
                }

                // Limpiar campos después de agregar
                $('#edit_service_id').val('');
                $('#edit_limit_date').val('');
            }

            // Funciones auxiliares para actualizar contadores
            function updateAmCounter() {
                const count = $('#edit_amSuppliesBody tr').not('.empty-message').length;
                $('#edit_amCount').text(count + ' servicio' + (count !== 1 ? 's' : ''));
            }

            function updatePmCounter() {
                const count = $('#edit_pmSuppliesBody tr').not('.empty-message').length;
                $('#edit_pmCount').text(count + ' servicio' + (count !== 1 ? 's' : ''));
            }

            // Eliminar servicio nuevo
            $(document).on('click', '.remove-new-service', function() {
                const serviceKey = $(this).data('service-key');
                const row = $(this).closest('tr');

                // Eliminar directamente
                row.remove();

                // Remover del conjunto de servicios agregados
                if (window.addedEditServices) {
                    window.addedEditServices.delete(serviceKey);
                }

                // Actualizar contadores
                updateAmCounter();
                updatePmCounter();
            });

            // Limpiar modal cuando se cierre
            $('#editAssignedModal').on('hidden.bs.modal', function() {
                clearEditModal();
            });
        });
    </script>

    {{-- Eliminar Servicios Existentes - Sin SweetAlert2 --}}
    <script>
        $(document).ready(function() {
            // Eliminar servicio existente (ya en BD)
            $(document).on('click', '.remove-existing-service', function() {
                const detailId = $(this).data('detail-id');
                const button = $(this);

                console.log('Eliminando servicio existente, ID:', detailId);
                deleteExistingService(detailId, button);
            });

            function deleteExistingService(detailId, button) {
                // Mostrar loading en el botón
                button.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

                // URL para eliminar el servicio existente
                const url = `{{ route('assignment-details.destroy', '') }}/${detailId}`;

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Servicio eliminado exitosamente:', response);

                        // Eliminar la fila de la tabla
                        const row = button.closest('tr');
                        row.remove();

                        // Actualizar contadores
                        updateServiceCounters();

                        // Restaurar botón (por si hay otros)
                        $('.remove-existing-service').html('<i class="fas fa-trash"></i>').prop(
                            'disabled', false);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al eliminar servicio:', error);

                        // Restaurar botón
                        button.html('<i class="fas fa-trash"></i>').prop('disabled', false);

                        // Mostrar error simple
                        let errorMessage = 'Error al eliminar el servicio';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                    }
                });
            }

            function updateServiceCounters() {
                // Actualizar contador AM (excluyendo filas vacías)
                const amCount = $('#edit_amSuppliesBody tr').not('.empty-message').length;
                $('#edit_amCount').text(amCount + ' servicio' + (amCount !== 1 ? 's' : ''));

                // Actualizar contador PM (excluyendo filas vacías)
                const pmCount = $('#edit_pmSuppliesBody tr').not('.empty-message').length;
                $('#edit_pmCount').text(pmCount + ' servicio' + (pmCount !== 1 ? 's' : ''));

                // Si no hay servicios, mostrar mensaje
                if (amCount === 0) {
                    $('#edit_amSuppliesBody').html(`
                        <tr class="empty-message">
                            <td colspan="4" class="text-center text-muted py-3">
                                <i class="fas fa-info-circle"></i> No hay servicios asignados
                            </td>
                        </tr>
                    `);
                }

                if (pmCount === 0) {
                    $('#edit_pmSuppliesBody').html(`
                        <tr class="empty-message">
                            <td colspan="4" class="text-center text-muted py-3">
                                <i class="fas fa-info-circle"></i> No hay servicios asignados
                            </td>
                        </tr>
                    `);
                }
            }
        });
    </script>

    {{-- Eliminar Asignación Completa --}}
    <script>
        // delete-assignment.js - Versión corregida (sin confirm() nativo)
        $(document).ready(function() {
            // Eliminar asignación completa
            $(document).on('click', '.delete-assignment', function(e) {
                e.preventDefault();

                const assignmentId = $(this).data('id');
                const button = $(this);
                const assignmentNumber = $(this).closest('tr').find('td:nth-child(2)').text().trim();
                const horseName = $(this).closest('tr').find('td:nth-child(3)').text().trim();
                const assignmentDate = $(this).closest('tr').find('td:nth-child(1)').text().trim();

                console.log('Solicitando eliminar asignación, ID:', assignmentId);

                // SweetAlert2 elegante para confirmación (REEMPLAZA AL confirm() nativo)
                Swal.fire({
                    title: '¿Eliminar Asignación?',
                    html: `
                        <div class="text-center">
                            <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                            <p class="h5">¿Estás seguro de eliminar esta asignación?</p>
                            <div class="alert alert-warning text-start mt-3">
                                <div class="row">
                                    <div class="col-12">
                                        <strong>Detalles de la asignación:</strong>
                                    </div>
                                    <div class="col-6">
                                        <small><strong>Número:</strong> ${assignmentNumber}</small>
                                    </div>
                                    <div class="col-6">
                                        <small><strong>Caballo:</strong> ${horseName}</small>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <small><strong>Fecha:</strong> ${assignmentDate}</small>
                                    </div>
                                </div>
                            </div>
                            <p class="text-danger small mt-3">
                                <i class="fas fa-info-circle me-1"></i>
                                Esta acción no se puede deshacer y se perderán todos los datos asociados.
                            </p>
                        </div>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-trash me-2"></i>Sí, eliminar',
                    cancelButtonText: '<i class="fas fa-times me-2"></i>Cancelar',
                    reverseButtons: true,
                    focusCancel: true,
                    customClass: {
                        confirmButton: 'btn btn-danger btn-lg',
                        cancelButton: 'btn btn-secondary btn-lg',
                        popup: 'animated bounceIn'
                    },
                    buttonsStyling: false,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteAssignment(assignmentId, button, assignmentNumber);
                    } else {
                        // Feedback sutil cuando el usuario cancela
                        Swal.fire({
                            title: 'Acción cancelada',
                            text: 'La asignación se mantiene en el sistema',
                            icon: 'info',
                            timer: 2000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end',
                            customClass: {
                                popup: 'animated bounceIn'
                            }
                        });
                    }
                });
            });

            function deleteAssignment(assignmentId, button, assignmentNumber) {
                // Mostrar loading elegante
                Swal.fire({
                    title: 'Procesando...',
                    html: `
                        <div class="text-center">
                            <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                                <span class="visually-hidden">Eliminando...</span>
                            </div>
                            <p>Eliminando asignación: <strong>${assignmentNumber}</strong></p>
                            <p class="text-muted small">Por favor espere...</p>
                        </div>
                    `,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Mostrar loading en el botón también
                const originalHtml = button.html();
                button.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

                // URL para eliminar la asignación
                const url = `{{ route('assignments.destroy', '') }}/${assignmentId}`;

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        console.log('Asignación eliminada exitosamente:', response);

                        // Cerrar loading de SweetAlert
                        Swal.close();

                        // Mostrar mensaje de éxito elegante
                        showSuccessMessage(assignmentNumber);

                        // Eliminar la fila de la tabla con animación
                        const row = button.closest('tr');
                        row.addClass('table-danger');

                        setTimeout(() => {
                            row.fadeOut(500, function() {
                                $(this).remove();

                                // Verificar si la tabla quedó vacía
                                checkIfTableEmpty();
                            });
                        }, 1000);

                    },
                    error: function(xhr, status, error) {
                        console.error('Error al eliminar asignación:', error);

                        // Cerrar loading de SweetAlert
                        Swal.close();

                        // Restaurar botón
                        button.html(originalHtml).prop('disabled', false);

                        let errorMessage = 'Error al eliminar la asignación';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseText) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response.message) {
                                    errorMessage = response.message;
                                }
                            } catch (e) {
                                if (xhr.responseText.includes('error')) {
                                    errorMessage = 'Error del servidor al eliminar la asignación';
                                }
                            }
                        }

                        showErrorMessage(errorMessage, assignmentNumber);
                    }
                });
            }

            function showSuccessMessage(assignmentNumber) {
                Swal.fire({
                    title: '¡Eliminado!',
                    html: `
                    <div class="text-center">
                        <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                        <p class="h5">Asignación eliminada exitosamente</p>
                        <p class="text-muted">Número: <strong>${assignmentNumber}</strong></p>
                        <div class="mt-3">
                            <i class="fas fa-trash text-muted me-2"></i>
                            <small class="text-muted">La asignación ha sido removida del sistema</small>
                        </div>
                    </div>
                `,
                    icon: 'success',
                    showConfirmButton: false, // Sin botón de confirmación
                    timer: 1000, // Se cierra automáticamente en 1 segundo
                    timerProgressBar: true, // Mostrar barra de progreso
                    didOpen: () => {
                        // Eliminar las clases de animación que pueden interferir
                        const popup = Swal.getPopup();
                        if (popup) {
                            popup.classList.remove('animated', 'bounceIn', 'animate__animated',
                                'animate__bounceIn');
                        }
                    },
                    willClose: () => {
                        // Limpiar cualquier animación pendiente
                        const popup = Swal.getPopup();
                        if (popup) {
                            popup.classList.remove('animate__animated', 'animate__bounceOut');
                        }
                    }
                });
            }

            function showErrorMessage(message, assignmentNumber) {
                Swal.fire({
                    title: 'Error',
                    html: `
                            <div class="text-center">
                                <i class="fas fa-exclamation-circle text-danger fa-3x mb-3"></i>
                                <p class="h5">No se pudo eliminar la asignación</p>
                                <p class="text-muted">Número: <strong>${assignmentNumber}</strong></p>
                                <div class="alert alert-danger mt-3 text-start">
                                    <i class="fas fa-info-circle me-2"></i>
                                    ${message}
                                </div>
                            </div>
                        `,
                    icon: 'error',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: '<i class="fas fa-times me-2"></i>Cerrar',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        popup: 'animated shake'
                    },
                    buttonsStyling: false,
                    showClass: {
                        popup: 'animate__animated animate__headShake'
                    }
                });
            }

            function checkIfTableEmpty() {
                const tbody = $('#responsive-datatable tbody');
                const rows = tbody.find('tr');

                if (rows.length === 0) {
                    setTimeout(() => {
                        tbody.html(`
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No se encontraron asignaciones</h5>
                                            <p class="text-muted">No hay asignaciones registradas en el sistema</p>
                                            <a href="{{ route('assignments.create') }}" class="btn btn-primary mt-3">
                                                <i class="fas fa-plus me-2"></i>Crear Primera Asignación
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            `);
                    }, 600);
                }
            }

            // Efecto hover en los botones de eliminar
            $(document).on('mouseenter', '.delete-assignment', function() {
                $(this).addClass('btn-pulse');
            }).on('mouseleave', '.delete-assignment', function() {
                $(this).removeClass('btn-pulse');
            });
        });

        // CSS adicional para efectos
        const additionalCSS = `
            <style>
            .btn-pulse {
                animation: pulse-danger 0.6s infinite alternate;
            }

            @keyframes pulse-danger {
                from {
                    box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4);
                }
                to {
                    box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
                }
            }

            .empty-state {
                padding: 2rem 0;
            }

            .animated {
                animation-duration: 0.5s;
            }

            .table-danger {
                background-color: rgba(220, 53, 69, 0.1) !important;
                transition: all 0.5s ease;
            }

            .swal2-popup {
                border-radius: 1rem !important;
                padding: 2rem !important;
            }

            .swal2-title {
                font-weight: 600 !important;
                margin-bottom: 1rem !important;
            }

            .swal2-html-container {
                margin-top: 0.5rem !important;
            }
            </style>
            `;

        // Agregar CSS dinámicamente
        $(additionalCSS).appendTo('head');
    </script>

     {{-- Script Simplificado para Actualizar --}}
   <script>
    $(document).ready(function() {
        $('#updateAssignedBtn').on('click', function() {
            updateAssignment();
        });

        function updateAssignment() {
            console.log('🔍 Buscando ID de asignación...');
            
            const assignmentId = getAssignmentId();
            
            if (!assignmentId) {
                alert('❌ Error: No se pudo identificar la asignación. Por favor cierra el modal y vuelve a intentarlo.');
                return;
            }

            console.log('✅ ID encontrado para actualizar:', assignmentId);
            updateAssignmentWithId(assignmentId);
        }

        function getAssignmentId() {
            let assignmentId = null;
            
            if (window.currentAssignmentId) {
                assignmentId = window.currentAssignmentId;
            } else if ($('#edit_assignment_id').val()) {
                assignmentId = $('#edit_assignment_id').val();
            } else {
                const titleText = $('#editModalAssignedNumber').text();
                const match = titleText.match(/ASIG-(\d+)/);
                if (match) {
                    assignmentId = match[1];
                }
            }
            
            return assignmentId;
        }
  
        /* Notificacion Sweealert2 al actualizar Asignacion */
        function updateAssignmentWithId(assignmentId) {
            console.log('🔄 Iniciando actualización para ID:', assignmentId);

            const submitBtn = $('#updateAssignedBtn');
            const originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Actualizando...').prop('disabled', true);

            const formData = new FormData($('#editAssignedForm')[0]);
            formData.append('assignment_id', assignmentId);

            fetch(`/assignments/${assignmentId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json().then(data => ({ 
                status: response.status, 
                ok: response.ok,
                data 
            })))
            .then(({ status, ok, data }) => {
                if (ok && data.success) {
                    $('#editAssignedModal').modal('hide');
                    
                    // 🔥 MOSTRAR NOTIFICACIÓN DE ÉXITO DESPUÉS DE CERRAR MODAL
                    setTimeout(() => {
                        Swal.fire({
                            position: "top-end",
                            title: '¡Edición Exitosa!',
                            text: data.message || 'Asignación actualizada correctamente',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500,
                        }).then(() => {
                            // Recargar página después de la notificación
                            location.reload();
                        });
                    }, 300); // Pequeño delay para que el modal se cierre completamente
                } else {
                    throw new Error(data.message || 'Error al actualizar la Asignación');
                }
            })
            .catch(error => {
                console.error('❌ Error en actualización:', error);
                alert('❌ Error: ' + error.message);
            })
            .finally(() => {
                submitBtn.html(originalText).prop('disabled', false);
            });
        }
    });
   </script>

   {{-- Script para el botón de Reset Assignments - CORREGIDO --}}
    <script>
        $(document).ready(function() {
            // Event listener para el botón de reset
            $('#resetAssignmentsBtn').on('click', function() {
                console.log('🔧 Botón de reset presionado'); // Debug
                
                // Mostrar confirmación
                Swal.fire({
                    title: '¿Resetear Asignaciones Expiradas?',
                    text: "Esta acción reseteará todas las asignaciones EJECUTADAS de días anteriores a estado ASIGNADO y limpiará los datos de ejecución. Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, resetear ahora!',
                    cancelButtonText: 'Cancelar',
                    backdrop: true,
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Mostrar loading en el botón
                        const resetButton = $('#resetAssignmentsBtn');
                        const originalHtml = resetButton.html();
                        resetButton.html('<i class="fas fa-spinner fa-spin"></i> Reseteando...');
                        resetButton.prop('disabled', true);
                        
                        console.log('🔄 Llamando al endpoint de reset...'); // Debug
                        
                        // 🔥 CORREGIDO: Usar la ruta correcta
                        fetch('{{ route("horsecard2.reset-expired-assignments") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({}) // Enviar objeto vacío ya que no necesitamos datos
                        })
                        .then(response => {
                            console.log('📨 Respuesta recibida, status:', response.status); // Debug
                            if (!response.ok) {
                                throw new Error('Error en la respuesta del servidor: ' + response.status);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('✅ Respuesta JSON:', data); // Debug
                            
                            if (data.success) {
                                // Éxito - mostrar mensaje de éxito
                                Swal.fire({
                                    title: '¡Éxito!',
                                    html: `
                                        <div class="text-center">
                                            <i class="fas fa-check-circle text-success mb-3" style="font-size: 3rem;"></i>
                                            <p class="mb-2">${data.message}</p>
                                            ${data.data ? `
                                                <div class="mt-3 p-2 bg-light rounded">
                                                    <small class="text-muted">
                                                        Asignaciones reseteadas: <strong>${data.data.assignments_updated || 0}</strong><br>
                                                        Detalles reseteados: <strong>${data.data.details_updated || 0}</strong><br>
                                                        Total expiradas encontradas: <strong>${data.data.total_expired || 0}</strong>
                                                    </small>
                                                </div>
                                            ` : ''}
                                        </div>
                                    `,
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK, recargar página'
                                }).then(() => {
                                    // Recargar la página para ver los cambios
                                    console.log('🔄 Recargando página...'); // Debug
                                    window.location.reload();
                                });
                            } else {
                                // Error del servidor
                                throw new Error(data.message || 'Error desconocido del servidor');
                            }
                        })
                        .catch(error => {
                            console.error('❌ Error en reset:', error);
                            
                            // Mostrar error
                            Swal.fire({
                                title: '¡Error!',
                                text: 'Error al resetear asignaciones: ' + error.message,
                                icon: 'error',
                                confirmButtonColor: '#d33',
                                confirmButtonText: 'OK'
                            });
                        })
                        .finally(() => {
                            // Restaurar el botón
                            console.log('🔚 Finalizando proceso de reset'); // Debug
                            resetButton.html(originalHtml);
                            resetButton.prop('disabled', false);
                        });
                    }
                });
            });
        });
    </script>
@endpush