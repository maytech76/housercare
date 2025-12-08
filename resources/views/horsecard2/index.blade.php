@extends('admin.layouts.master')

@section('content')
    <section>

        {{-- Encabezado --}}
        <div class="main-container container-fluid">
            <!-- breadcrumb -->
            <div class="breadcrumb-header justify-content-between">
                <div class="my-auto">
                    <h4 class="page-title">Horse Cards Assignmets</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Horse card</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Execution of Services</li>
                    </ol>
                </div>
                <div class="d-flex my-xl-auto right-content align-items-center">
                    <div class="mb-xl-0">
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuDate" data-bs-toggle="dropdown" aria-expanded="false"></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- breadcrumb -->
        </div>
        
        <!-- row Tarjetas de Caballos-->
        <div class="row row-sm m-4">
            @foreach($assignments as $assigned)
            <div class="col-xl-2 col-lg-6 col-sm-6 col-md-6">
                <a href="javascript:void(0);" class="assigned-card" data-assigned-id="{{ $assigned->id }}">
                    <div class="card user-wideget user-wideget-widget widget-user">
                        {{-- CONDICIÓN PARA CAMBIAR EL COLOR SEGÚN EL STATUS --}}
                        <div class="widget-user-header {{ $assigned->status === 'EXECUTED' ? 'bg-danger-gradient' : 'bg-success-gradient' }}">
                            <h6 class="widget-user-username" style="font-size: 1rem; color:#000;">{{ $assigned->horse->name }}</h6>
                            <h5 class="widget-user-desc"></h5>
                        </div>
                        <div class="widget-user-image shadow-md">
                            <img src="{{ $assigned->horse->photo ? asset('storage/' . $assigned->horse->photo) : asset('storage/horses/horse.png') }}" 
                                    class="rounded-circle" 
                                    alt="User Avatar">
                        </div>
                        <div class="user-wideget-footer my-0 border-bottom">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="description-block"></div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="description-block">
                                        <h6 class="description text-secondary" style="font-size: 0.9rem">{{ $assigned->horse->condition }}</h6>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="description-block"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach

            <!-- Paginación -->
            <div class="text-wrap">
                <div class="example">
                    <div class="row row-sm justify-content-center">
                        <div class="col-sm-6 col-lg-4">
                            <ul class="pagination pagination-primary mg-sm-b-0">
                                {{-- Enlace anterior --}}
                                @if ($assignments->onFirstPage())
                                    <li class="page-item disabled mx-2">
                                        <span class="page-link"><i class="icon ion-ios-arrow-back"></i></span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $assignments->previousPageUrl() }}">
                                            <i class="icon ion-ios-arrow-back"></i>
                                        </a>
                                    </li>
                                @endif

                                {{-- Números de página --}}
                                @foreach ($assignments->getUrlRange(1, $assignments->lastPage()) as $page => $url)
                                    @if ($page == $assignments->currentPage())
                                        <li class="page-item active mx-2">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item mx-2">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                {{-- Enlace siguiente --}}
                                @if ($assignments->hasMorePages())
                                    <li class="page-item mx-2">
                                        <a class="page-link" href="{{ $assignments->nextPageUrl() }}">
                                            <i class="icon ion-ios-arrow-forward"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled mx-2">
                                        <span class="page-link"><i class="icon ion-ios-arrow-forward"></i></span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /row -->

        <!-- Modal para Detalles del Suministro (VISUALIZACIÓN) -->
        <div class="modal fade" id="assignedDetailsModal" tabindex="-1" aria-labelledby="assignedDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignedDetailsModalLabel">
                            Assignment Details - <span id="modalAssignedNumber"></span>
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
                                <p class="mt-2">Cargando detalles del suministro...</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<style>
    /* 🔥 ESTILOS RESPONSIVOS ADICIONALES PARA SWEETALERT2 EN MÓVILES */
    .swal2-responsive {
        max-width: 95% !important;
    }

    .swal2-responsive-btn {
        min-width: 120px !important;
        margin: 5px !important;
    }

    @media (max-width: 768px) {
        .swal2-popup {
            font-size: 14px !important;
            padding: 1rem !important;
        }
        
        .swal2-actions {
            flex-direction: column !important;
        }
        
        .swal2-responsive-btn {
            width: 100% !important;
            margin: 5px 0 !important;
        }
    }
</style>

@endpush

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
   
 // Función global para mostrar detalles del modal
window.showAssignedDetails = function(assignedId) {
    console.log('Mostrando detalles del suministro:', assignedId);
    
    // Mostrar loading en el modal
    $('#assignedDetailsContent').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Cargando detalles del suministro...</p>
        </div>
    `);
    
    // Llamar al endpoint para obtener los detalles
    fetch(`/horsecard2/assignments/${assignedId}/assigned-details`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Respuesta recibida:', data);
            if (data.success) {
                $('#modalAssignedNumber').text(data.assigned.assigned_number || data.assigned.id);
                $('#assignedDetailsContent').html(data.html);
            } else {
                $('#assignedDetailsContent').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> ${data.message || 'Error cargando los detalles del suministro.'}
                    </div>
                `);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            $('#assignedDetailsContent').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Error cargando los detalles del suministro: ${error.message}
                </div>
            `);
        });
    
    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('assignedDetailsModal'));
    modal.show();
}

// Event listener para las tarjetas - MOSTRAR MODAL
$(document).on('click', '.assigned-card', function(e) {
    e.preventDefault();
    const assignedId = $(this).data('assigned-id');
    console.log('Click en tarjeta, assignedId:', assignedId);
    showAssignedDetails(assignedId);
});

/* ------------------------------------ */

// Event listener para los checkboxes - EJECUTAR SERVICIOS
$(document).on('change', '.execute-assigned', function() {   
    const checkbox = $(this);
    const assignedId = checkbox.data('assigned-id');
    const shift = checkbox.data('shift');
    const detailId = checkbox.data('detail-id');
    
    if (checkbox.is(':checked')) {
        // 🔥 DESHABILITAR EL CHECKBOX INMEDIATAMENTE
        checkbox.prop('disabled', true);
        
        // Cerrar el modal primero
        $('#assignedDetailsModal').modal('hide');
        
        // Esperar a que el modal se cierre completamente
        $('#assignedDetailsModal').on('hidden.bs.modal', function() {
            $('#assignedDetailsModal').off('hidden.bs.modal'); // Remover listener
            
            // 🔥 LLAMAR AL NUEVO ENDPOINT
            fetch(`/horsecard2/assignments/${assignedId}/execute-service`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    shift: shift,
                    detail_id: detailId 
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Servicio ejecutado:', data.data);
                    // Recargar la página para mostrar los cambios
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                } else {
                    console.error('Error en ejecución:', data.message);
                    // 🔥 REHABILITAR EL CHECKBOX EN CASO DE ERROR
                    resetCheckbox(checkbox, 'Execute');
                }
            })
            .catch(error => {
                console.error('Error de conexión:', error);
                // 🔥 REHABILITAR EL CHECKBOX EN CASO DE ERROR
                resetCheckbox(checkbox, 'Execute');
            });
        });
    }
});

// Función auxiliar para resetear checkbox
function resetCheckbox(checkbox, text) {
    checkbox.prop('checked', false).prop('disabled', false);
    // Si hay un label con texto, actualizarlo
    const label = checkbox.next('.form-check-label');
    if (label.length && text) {
        label.html(text);
    }
}

/* ------------------------------------------   */

// Inicialización
$(document).ready(function() {
    console.log('Script de horsecard cargado - Modal y ejecución de servicios listos');
});

</script>

@endpush