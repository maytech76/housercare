@extends('admin.layouts.master')

@section('content')
    <section>
        <div class="container-fluid py-4">
            {{-- Botón de regreso --}}
            <div class="row mb-4">
                <div class="col-12">
                    <a href="{{ route('sectors.show2') }}" class="btn btn-outline-warning mb-3">
                        <i class="fas fa-arrow-left me-2"></i>Back to Sectors
                    </a>
                    <h1 class="h2">{{ $sector->name }} - Horse Details</h1>
                    <p class="text-muted">Manage horse movements for each shift</p>
                </div>
            </div>

            {{-- Grid de Caballos --}}
            <div class="row" id="horses-container">
                @foreach($horses as $horse)
                <div class="col-lg-4 col-xl-4 mb-4">
                    <div class="card horse-card">
                        <div class="card-body">
                            
                            {{-- Cabecera con foto, nombre, Id --}}
                            <div class="d-flex align-items-center mb-4">
                                <div class="flex-shrink-0">
                                    @if($horse->photo)
                                        <img src="{{ asset('storage/' . $horse->photo) }}" 
                                            alt="{{ $horse->name }}" 
                                            class="rounded-circle"
                                            width="80" height="80">
                                    @else
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center"
                                            style="width: 80px; height: 80px;">
                                            <i class="fas fa-horse text-white fa-2x"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-1">{{ $horse->name }}</h4>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $horse->stable->name ?? 'No stable assigned' }}
                                    </p>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-id-badge me-1"></i>
                                        Number - {{ $horse->id ?? 'No code' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Tabla de Movimientos --}}
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-start">Shift</th>
                                            <th class="text-start">Service</th>
                                            <th class="text-start">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            // Obtener las asignaciones de este caballo
                                            $horseAssignments = $assignments[$horse->id] ?? collect();
                                            
                                            // Buscar detalles por turno y sus asignaciones asociadas
                                            $amDetail = null;
                                            $pmDetail = null;
                                            $amAssignment = null;
                                            $pmAssignment = null;
                                            $currentMovementStatus = 'DO NOT MOVE';
                                            
                                            if ($horseAssignments->isNotEmpty()) {
                                                foreach ($horseAssignments as $assignment) {
                                                    // Buscar detalle AM
                                                    if (!$amDetail) {
                                                        $detail = $assignment->details->firstWhere('shift', 'AM');
                                                        if ($detail) {
                                                            $amDetail = $detail;
                                                            $amAssignment = $assignment;
                                                        }
                                                    }
                                                    // Buscar detalle PM
                                                    if (!$pmDetail) {
                                                        $detail = $assignment->details->firstWhere('shift', 'PM');
                                                        if ($detail) {
                                                            $pmDetail = $detail;
                                                            $pmAssignment = $assignment;
                                                        }
                                                    }
                                                    // Usar el status del primer movimiento encontrado
                                                    if ($currentMovementStatus === 'DO NOT MOVE') {
                                                        $currentMovementStatus = $assignment->status;
                                                    }
                                                }
                                            }
                                        @endphp
                                        
                                        {{-- Turno AM --}}
                                        <tr>
                                            <td>
                                                <span class="text-primary">AM</span>
                                            </td>
                                            <td>
                                                @if($amDetail && $amAssignment)
                                                <img src="{{ asset('storage/' . $amAssignment->toService->photo) }}"
                                                class="rounded-circle me-2" 
                                                width="30" height="30">
                                                    {{ $amAssignment->toService->name ?? '' }}
                                                @else
                                                    <span class="text-muted">No Service</span>
                                                @endif
                                            </td>

                                            <td style="text-align: center;">
                                                @if($amDetail && $amAssignment)
                                                <div class="form-check form-switch d-inline-block my-2" style="transform: scale(1.5); transform-origin: right center;">
                                                    <input type="checkbox" 
                                                        class="form-check-input movement-switch"
                                                        data-detail-id="{{ $amDetail->id }}"
                                                        data-assignment-id="{{ $amAssignment->id }}"
                                                        data-movement-id="{{ $amAssignment->service_id }}"
                                                        data-shift="AM"
                                                        data-horse-id="{{ $horse->id }}"
                                                        {{ $amDetail->is_executed ? 'checked disabled' : '' }}
                                                        id="switch-am-{{ $horse->id }}">
                                                    <label class="form-check-label" for="switch-am-{{ $horse->id }}"></label>
                                                </div>
                                                @else
                                                    <span class="text-secondary tex-center"></span>
                                                @endif
                                            </td>
                                        </tr>

                                        {{-- Turno PM --}}
                                        <tr>
                                            <td>
                                                <span class="text-warning">PM</span>
                                            </td>
                                            <td>
                                                @if($pmDetail && $pmAssignment)
                                                <img src="{{ asset('storage/' . $pmAssignment->toService->photo) }}"
                                                class="rounded-circle me-2" 
                                                width="30" height="30">
                                                    {{ $pmAssignment->toService->name ?? '' }}
                                                @else
                                                    <span class="text-muted">No Service</span>
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                @if($pmDetail && $pmAssignment)
                                                <div class="form-check form-switch d-inline-block my-2" style="transform: scale(1.5); transform-origin: right center;">
                                                    <input type="checkbox" 
                                                        class="form-check-input movement-switch"
                                                        data-detail-id="{{ $pmDetail->id }}"
                                                        data-assignment-id="{{ $pmAssignment->id }}"
                                                        data-movement-id="{{ $pmAssignment->service_id }}"
                                                        data-shift="PM"
                                                        data-horse-id="{{ $horse->id }}"
                                                        {{ $pmDetail->is_executed ? 'checked disabled' : '' }}
                                                        id="switch-pm-{{ $horse->id }}">
                                                    <label class="form-check-label" for="switch-pm-{{ $horse->id }}"></label>
                                                </div>
                                                @else
                                                    <span class="text-secondary text-center"></span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{-- Estado Actual --}}
                            <div class="pt-2 border-top">
                                @php
                                    $statusClass = match($currentMovementStatus) {
                                        'ASSIGNED' => 'text-warning',
                                        'EXECUTED' => 'text-success',
                                        default => 'text-secondary'
                                    };
                                @endphp
                                <small class="text-muted">Current Status:</small>
                                <span class="badge {{ $statusClass }} float-end">
                                    {{ $currentMovementStatus }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($horses->isEmpty())
            <div class="text-center py-5">
                <div class="display-1 text-muted">🏇</div>
                <h3 class="text-muted">No horses in this sector</h3>
                <p class="text-muted">Add horses to this sector to see them here.</p>
            </div>
            @endif
        </div>
    </section>
@endsection

@push('styles')
    <style>
    .horse-card {
        transition: transform 0.2s;
        border-left: 4px solid {{ $sector->color ?? '#3490dc' }};
    }

    .horse-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .form-check-input:checked {
        background-color: {{ $sector->color ?? '#3490dc' }};
        border-color: {{ $sector->color ?? '#3490dc' }};
    }

    .form-switch .form-check-input {
        height: 1.8em;
        width: 3em;
    }

    /* Estilo para checkboxes deshabilitados */
    .form-check-input:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
    </style>
@endpush

@push('scripts')
<!-- Incluir SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // 🔥 CORRECCIÓN: Usar la ruta correcta con prefijo horsecard2
        const executeServiceRoute = '{{ route("horsecard2.execute-service", ":assignmentId") }}';
        
        console.log('=== Sistema de Ejecución de Servicios ===');
        console.log('Ruta base AJAX:', executeServiceRoute);
        console.log('Switches activos:', $('.movement-switch').length);
        
        // Deshabilitar los switches que ya están ejecutados
        $('.movement-switch:checked').prop('disabled', true);
        
        $('.movement-switch').not(':disabled').change(function() {
            const detailId = $(this).data('detail-id');
            const assignmentId = $(this).data('assignment-id');
            const movementId = $(this).data('movement-id');
            const shift = $(this).data('shift');
            const horseId = $(this).data('horse-id');
            const isChecked = $(this).is(':checked');
            
            console.log('📤 Datos para horsecard2.execute-service:', {
                detailId: detailId,
                assignmentId: assignmentId,
                movementId: movementId,
                shift: shift,
                horseId: horseId,
                isChecked: isChecked
            });
            
            // Validación
            if (!detailId || isNaN(detailId)) {
                console.error('❌ ID de detalle inválido');
                showSweetAlert('Error', 'Invalid detail ID', 'error');
                $(this).prop('checked', !isChecked);
                return;
            }
            
            if (!assignmentId || isNaN(assignmentId)) {
                console.error('❌ ID de asignación inválido');
                showSweetAlert('Error', 'Invalid assignment ID', 'error');
                $(this).prop('checked', !isChecked);
                return;
            }
            
            // 🔥 CORRECCIÓN: Preparar datos según lo que espera executeService
            const payload = {
                detail_id: parseInt(detailId),  // El método espera 'detail_id'
                shift: shift                    // El método espera 'shift'
            };
            
            console.log('📦 Payload para horsecard2.execute-service:', payload);
            
            const switchElement = $(this);
            switchElement.prop('disabled', true);
            
            // 🔥 CORRECCIÓN: Construir URL dinámica con assignmentId
            const url = executeServiceRoute.replace(':assignmentId', assignmentId);
            console.log('URL final para AJAX:', url);
            
            // ENVIAR como JSON para mantener tipos de datos
            $.ajax({
                url: url,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify(payload),
                processData: false,
                success: function(response) {
                    console.log('✅ Respuesta exitosa de horsecard2.execute-service:', response);
                    
                    if (response.success) {
                        // Actualizar badge de estado
                        const horseCard = switchElement.closest('.horse-card');
                        const statusBadge = horseCard.find('.badge');
                        
                        // 🔥 CORRECCIÓN: Si todos los servicios están ejecutados
                        if (response.data && response.data.all_executed) {
                            // Cambiar estado a EXECUTED
                            statusBadge.removeClass('text-warning text-secondary').addClass('text-success');
                            statusBadge.text('EXECUTED');
                            
                            // Deshabilitar ambos switches (AM y PM)
                            horseCard.find('.movement-switch').each(function() {
                                $(this).prop('disabled', true);
                                $(this).prop('checked', true);
                            });
                            
                            // Mostrar mensaje de éxito
                            showSweetAlert(
                                'ALL SHIFTS EXECUTED',
                                `All shifts have been successfully completed for this horse.`,
                                'success'
                            );
                        } else {
                            // Si solo este turno se ejecutó
                            statusBadge.removeClass('text-secondary').addClass('text-warning');
                            statusBadge.text('ASSIGNED');
                            
                            // Mostrar mensaje de éxito para un solo turno
                            showSweetAlert(
                                'SHIFT EXECUTED',
                                `Shift ${shift} has been successfully executed.`,
                                'success'
                            );
                        }
                        
                        // Deshabilitar el switch permanentemente ya que no se puede desmarcar
                        switchElement.prop('disabled', true);
                        switchElement.prop('checked', true);
                        
                        // Mostrar información adicional en consola
                        if (response.data) {
                            console.log(`Detalles ejecutados: ${response.data.executed_count}/${response.data.total_count}`);
                        }
                    } else {
                        console.error('❌ Respuesta con error:', response);
                        switchElement.prop('disabled', false);
                        switchElement.prop('checked', !isChecked);
                        showSweetAlert('Error', response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.group('❌ Error en petición horsecard2.execute-service');
                    console.log('Status:', xhr.status);
                    console.log('Status Text:', xhr.statusText);
                    console.log('Error:', error);
                    
                    // Habilitar el switch nuevamente
                    switchElement.prop('disabled', false);
                    switchElement.prop('checked', !isChecked);
                    
                    try {
                        const errorResponse = JSON.parse(xhr.responseText);
                        console.log('Respuesta de error:', errorResponse);
                        
                        if (errorResponse.errors) {
                            console.log('Errores de validación:', errorResponse.errors);
                            
                            let errorMessages = [];
                            Object.keys(errorResponse.errors).forEach(key => {
                                errorResponse.errors[key].forEach(msg => {
                                    errorMessages.push(`${key}: ${msg}`);
                                });
                            });
                            
                            showSweetAlert('Validation Error', errorMessages.join(', '), 'error');
                        } else if (errorResponse.message) {
                            showSweetAlert('Error', errorResponse.message, 'error');
                        } else {
                            showSweetAlert('Error', 'Ocurrió un error inesperado', 'error');
                        }
                    } catch (e) {
                        console.log('No se pudo parsear respuesta:', xhr.responseText);
                        console.log('Respuesta cruda:', xhr.responseText);
                        showSweetAlert('Server Error', 'Error del servidor: ' + xhr.statusText, 'error');
                    }
                    console.groupEnd();
                }
            });
        });
        
        // Función para mostrar SweetAlert2
        function showSweetAlert(title, message, type) {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
                showConfirmButton: false,
                timer: type === 'success' ? 1500 : 2000,
                timerProgressBar: true,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                },
                position: 'center',
                toast: true,
                background: type === 'success' ? '#28a745' : '#dc3545',
                color: '#fff',
                iconColor: type === 'success' ? '#fff' : '#fff',
                customClass: {
                    popup: 'sweet-alert-custom'
                }
            });
        }
        
        // Función para mostrar Toast (mantener compatibilidad)
        function showToast(title, message, type) {
            const toastId = 'toast-' + Date.now();
            const toast = `
                <div id="${toastId}" class="toast align-items-center text-bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            <strong>${title}:</strong> ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            
            if (!$('#toast-container').length) {
                $('body').append('<div id="toast-container" class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999"></div>');
            }
            
            $('#toast-container').append(toast);
            const toastElement = $('#' + toastId);
            
            toastElement.toast({
                autohide: true,
                delay: 3000
            });
            
            toastElement.toast('show');
            
            toastElement.on('hidden.bs.toast', function() {
                $(this).remove();
            });
        }
        
        // Debug: mostrar todos los switches y sus datos
        console.log('=== Switches encontrados ===');
        $('.movement-switch').each(function(i) {
            const $el = $(this);
            const isDisabled = $el.prop('disabled');
            const isChecked = $el.is(':checked');
            console.log(`Switch ${i}:`, {
                id: $el.attr('id'),
                detailId: $el.data('detail-id'),
                assignmentId: $el.data('assignment-id'),
                movementId: $el.data('movement-id'),
                horseId: $el.data('horse-id'),
                shift: $el.data('shift'),
                checked: isChecked,
                disabled: isDisabled,
                status: isChecked && isDisabled ? 'EXECUTED (LOCKED)' : 
                        isChecked ? 'CHECKED' : 
                        isDisabled ? 'DISABLED' : 'ACTIVE'
            });
        });
    });
</script>
@endpush