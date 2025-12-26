@extends('admin.layouts.master')

@section('content')
    <section>
        <div class="container-fluid py-4">
            {{-- Botón de regreso --}}
            <div class="row mb-4">
                <div class="col-12">
                    <a href="{{ route('sectors.show') }}" class="btn btn-outline-secondary mb-3">
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
                            {{-- Cabecera con foto y nombre --}}
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
                                        {{ $horse->id ?? 'No code' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Tabla de Movimientos --}}
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-start">Shift</th>
                                            <th class="text-start">Destination</th>
                                            <th class="text-start">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            // Obtener los movimientos de este caballo
                                            $horseMovements = $movements[$horse->id] ?? collect();
                                            
                                            // Buscar detalles por turno
                                            $amDetail = null;
                                            $pmDetail = null;
                                            $currentMovementStatus = 'NO MOVE';
                                            
                                            if ($horseMovements->isNotEmpty()) {
                                                // Para cada movimiento, buscar sus detalles
                                                foreach ($horseMovements as $movement) {
                                                    // Buscar detalle AM
                                                    if (!$amDetail) {
                                                        $amDetail = $movement->details->firstWhere('shift', 'AM');
                                                    }
                                                    // Buscar detalle PM
                                                    if (!$pmDetail) {
                                                        $pmDetail = $movement->details->firstWhere('shift', 'PM');
                                                    }
                                                    // Usar el status del primer movimiento encontrado
                                                    if ($currentMovementStatus === 'NO MOVE') {
                                                        $currentMovementStatus = $movement->status;
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
                                                @if($amDetail)
                                                    {{ $amDetail->toStable->name ?? '' }}
                                                @else
                                                    <span class="text-muted">No move</span>
                                                @endif
                                            </td>

                                            <td style="text-align: center;">
                                                @if($amDetail)
                                                <div class="form-check form-switch d-inline-block my-2" style="transform: scale(1.5); transform-origin: right center;">
                                                    <input type="checkbox" 
                                                        class="form-check-input movement-switch"
                                                        data-detail-id="{{ $amDetail->id }}"
                                                        data-movement-id="{{ $amDetail->movement_id }}"
                                                        data-shift="AM"
                                                        data-horse-id="{{ $horse->id }}"
                                                        {{ $amDetail->is_executed ? 'checked' : '' }}
                                                        id="switch-am-{{ $horse->id }}">
                                                    <label class="form-check-label" for="switch-am-{{ $horse->id }}"></label>
                                                </div>
                                                @else
                                                    <span class="badge bg-secondary">N/A</span>
                                                @endif
                                            </td>

                                        </tr>

                                        {{-- Turno PM --}}
                                        <tr>
                                            <td>
                                                <span class="text-warning">PM</span>
                                            </td>
                                            <td>
                                                @if($pmDetail)
                                                    {{ $pmDetail->toStable->name ?? '' }}
                                                @else
                                                    <span class="text-muted">No move</span>
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                @if($pmDetail)
                                                <div class="form-check form-switch d-inline-block my-2" style="transform: scale(1.5); transform-origin: right center;">
                                                    <input type="checkbox" 
                                                        class="form-check-input movement-switch"
                                                        data-detail-id="{{ $amDetail->id }}"
                                                        data-movement-id="{{ $amDetail->movement_id }}"
                                                        data-shift="AM"
                                                        data-horse-id="{{ $horse->id }}"
                                                        {{ $amDetail->is_executed ? 'checked' : '' }}
                                                        id="switch-am-{{ $horse->id }}">
                                                    <label class="form-check-label" for="switch-am-{{ $horse->id }}"></label>
                                                </div>
                                                @else
                                                    <span class="badge bg-secondary">N/A</span>
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
                                        'PARTIALLY' => 'text-info',
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
    </style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        const updateStatusUrl = '/movements/update-status';
        
        console.log('=== Sistema de Movimientos ===');
        console.log('URL AJAX:', updateStatusUrl);
        console.log('Switches activos:', $('.movement-switch').length);
        
        $('.movement-switch').change(function() {
            const detailId = $(this).data('detail-id');
            const movementId = $(this).data('movement-id');
            const shift = $(this).data('shift');
            const horseId = $(this).data('horse-id');
            const isChecked = $(this).is(':checked');
            
            console.log('📤 Datos originales:', {
                detailId: detailId,
                movementId: movementId,
                shift: shift,
                horseId: horseId,
                isChecked: isChecked,
                isCheckedType: typeof isChecked,
                isCheckedValue: isChecked
            });
            
            // Validación
            if (!detailId || isNaN(detailId)) {
                console.error('❌ ID de detalle inválido');
                showToast('Error', 'Invalid detail ID', 'error');
                $(this).prop('checked', !isChecked);
                return;
            }
            
            // CORRECCIÓN: Preparar datos - CONVERTIR isChecked a boolean de forma segura
            const payload = {
                movement_detail_id: parseInt(detailId),
                movement_id: parseInt(movementId),
                shift: shift,
                // ¡CORRECCIÓN AQUÍ! - 3 formas de asegurar boolean
                is_executed: isChecked === true || isChecked === 'true' || isChecked === 1,
                horse_id: parseInt(horseId)
            };
            
            console.log('📦 Payload corregido:', payload);
            console.log('is_executed valor:', payload.is_executed);
            console.log('is_executed tipo:', typeof payload.is_executed);
            console.log('is_executed es boolean?', typeof payload.is_executed === 'boolean');
            
            const switchElement = $(this);
            switchElement.prop('disabled', true);
            
            // ENVIAR como JSON para mantener tipos de datos
            $.ajax({
                url: updateStatusUrl,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json' // ¡IMPORTANTE!
                },
                data: JSON.stringify(payload), // Enviar como JSON string
                processData: false, // No procesar datos
                success: function(response) {
                    console.log('✅ Respuesta exitosa:', response);
                    
                    if (response.success) {
                        // Actualizar badge de estado
                        const statusBadge = switchElement.closest('.horse-card').find('.badge.text-warning, .badge.text-info, .badge.text-success, .badge.text-secondary');
                        
                        // Remover todas las clases de color
                        statusBadge.removeClass('text-warning text-info text-success text-secondary');
                        
                        // Añadir clase según estado
                        if (response.status === 'ASSIGNED') {
                            statusBadge.addClass('text-warning');
                        } else if (response.status === 'PARTIALLY') {
                            statusBadge.addClass('text-info');
                        } else if (response.status === 'EXECUTED') {
                            statusBadge.addClass('text-success');
                        } else {
                            statusBadge.addClass('text-secondary');
                        }
                        
                        // Actualizar texto
                        statusBadge.text(response.status);
                        
                        // Si se ejecutó, mantener el switch checked
                        if (response.data && response.data.detail_executed) {
                            switchElement.prop('checked', true);
                        }
                        
                        showToast('Success', response.message, 'success');
                    } else {
                        console.error('❌ Respuesta con error:', response);
                        switchElement.prop('checked', !isChecked);
                        showToast('Error', response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.group('❌ Error en petición');
                    console.log('Status:', xhr.status);
                    console.log('Status Text:', xhr.statusText);
                    console.log('Error:', error);
                    
                    try {
                        const errorResponse = JSON.parse(xhr.responseText);
                        console.log('Respuesta de error:', errorResponse);
                        
                        if (errorResponse.errors) {
                            console.log('Errores de validación:', errorResponse.errors);
                            
                            // Mostrar errores específicos
                            let errorMessages = [];
                            Object.keys(errorResponse.errors).forEach(key => {
                                errorResponse.errors[key].forEach(msg => {
                                    errorMessages.push(`${key}: ${msg}`);
                                });
                            });
                            
                            showToast('Error de Validación', errorMessages.join(', '), 'error');
                        } else if (errorResponse.message) {
                            showToast('Error', errorResponse.message, 'error');
                        }
                    } catch (e) {
                        console.log('No se pudo parsear respuesta:', xhr.responseText);
                        console.log('Respuesta cruda:', xhr.responseText);
                        showToast('Error', 'Error del servidor: ' + xhr.statusText, 'error');
                    }
                    console.groupEnd();
                    
                    // Revertir el switch
                    switchElement.prop('checked', !isChecked);
                },
                complete: function() {
                    switchElement.prop('disabled', false);
                }
            });
        });
        
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
                delay: 5000
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
            console.log(`Switch ${i}:`, {
                id: $el.attr('id'),
                detailId: $el.data('detail-id'),
                movementId: $el.data('movement-id'),
                horseId: $el.data('horse-id'),
                shift: $el.data('shift'),
                checked: $el.is(':checked'),
                dataAttrs: $el.data()
            });
        });
    });
</script>
@endpush