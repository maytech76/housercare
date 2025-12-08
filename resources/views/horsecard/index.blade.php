@extends('admin.layouts.master')

@section('content')
    <section>
        <div class="main-container container-fluid">
            <!-- breadcrumb -->
            <div class="breadcrumb-header justify-content-between">
                <div class="my-auto">
                    <h4 class="page-title">Horse Cards Supplies</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Horse card</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Execution of Supplies</li>
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
            @foreach($supplies as $supply)
            <div class="col-xl-2 col-lg-6 col-sm-6 col-md-6">
                <a href="javascript:void(0);" class="supply-card" data-supply-id="{{ $supply->id }}">
                    <div class="card user-wideget user-wideget-widget widget-user">
                        {{-- CONDICIÓN PARA CAMBIAR EL COLOR SEGÚN EL STATUS --}}
                        <div class="widget-user-header {{ $supply->status === 'EXECUTED' ? 'bg-danger-gradient' : 'bg-primary-gradient' }}">
                            <h6 class="widget-user-username" style="font-size: 1rem;">{{ $supply->horse->name }}</h6>
                            <h5 class="widget-user-desc"></h5>
                        </div>
                        <div class="widget-user-image shadow-md">
                            <img src="{{ $supply->horse->photo ? asset('storage/' . $supply->horse->photo) : asset('storage/horses/horse.png') }}" class="brround" alt="User Avatar">
                        </div>
                        <div class="user-wideget-footer my-0 border-bottom">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="description-block"></div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="description-block">
                                        <h6 class="description text-secondary" style="font-size: 0.9rem">{{ $supply->horse->condition }}</h6>
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
                                @if ($supplies->onFirstPage())
                                    <li class="page-item disabled mx-2">
                                        <span class="page-link"><i class="icon ion-ios-arrow-back"></i></span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $supplies->previousPageUrl() }}">
                                            <i class="icon ion-ios-arrow-back"></i>
                                        </a>
                                    </li>
                                @endif

                                {{-- Números de página --}}
                                @foreach ($supplies->getUrlRange(1, $supplies->lastPage()) as $page => $url)
                                    @if ($page == $supplies->currentPage())
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
                                @if ($supplies->hasMorePages())
                                    <li class="page-item mx-2">
                                        <a class="page-link" href="{{ $supplies->nextPageUrl() }}">
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
        <div class="modal fade" id="supplyDetailsModal" tabindex="-1" aria-labelledby="supplyDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="supplyDetailsModalLabel">
                            Detalles del Suministro - <span id="modalSupplyNumber"></span>
                        </h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="supplyDetailsContent">
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
    // Función global para mostrar detalles
    window.showSupplyDetails = function(supplyId) {
        console.log('Mostrando detalles del suministro:', supplyId);
        
        $('#supplyDetailsContent').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Cargando detalles del suministro...</p>
            </div>
        `);
        
        fetch(`/horsecard/supplies/${supplyId}/supply-details`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Respuesta recibida:', data);
                if (data.success) {
                    $('#modalSupplyNumber').text(data.supply.supply_number || data.supply.id);
                    $('#supplyDetailsContent').html(data.html);
                } else {
                    $('#supplyDetailsContent').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> ${data.message || 'Error cargando los detalles del suministro.'}
                        </div>
                    `);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                $('#supplyDetailsContent').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> Error cargando los detalles del suministro: ${error.message}
                    </div>
                `);
            });
        
        const modal = new bootstrap.Modal(document.getElementById('supplyDetailsModal'));
        modal.show();
    }

    // Event listener para las tarjetas
    $(document).on('click', '.supply-card', function(e) {
        e.preventDefault();
        const supplyId = $(this).data('supply-id');
        console.log('Click en tarjeta, supplyId:', supplyId);
        showSupplyDetails(supplyId);
    });

    // 🔥 SCRIPT MEJORADO - CIERRA MODAL PRIMERO Y LUEGO MUESTRA SWEETALERT2
    $(document).on('change', '.execute-supply', function() {   
        const checkbox = $(this);
        const supplyId = checkbox.data('supply-id');
        const shift = checkbox.data('shift');
        const isChecked = checkbox.is(':checked');
        
        console.log('Checkbox cambiado:', { supplyId, shift, isChecked });
        
        if (isChecked) {
            // 🔥 PRIMERO: Cerrar el modal de detalles
            $('#supplyDetailsModal').modal('hide');
            
            // 🔥 SEGUNDO: Esperar a que el modal se cierre completamente
            $('#supplyDetailsModal').on('hidden.bs.modal', function() {
                // Remover el event listener para evitar múltiples ejecuciones
                $('#supplyDetailsModal').off('hidden.bs.modal');
                
                // 🔥 TERCERO: Mostrar SweetAlert2 de confirmación
                Swal.fire({
                    title: `¿Ejecutar Turno ${shift}?`,
                    text: "Esta acción registrará el suministro y descontará del inventario",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, ejecutar',
                    cancelButtonText: 'Cancelar',
                    backdrop: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    customClass: {
                        container: 'swal2-responsive',
                        popup: 'swal2-responsive',
                        confirmButton: 'swal2-responsive-btn',
                        cancelButton: 'swal2-responsive-btn'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // 🔥 CUARTO: Mostrar loading en el switch (aunque esté en el modal cerrado)
                        checkbox.prop('disabled', true);
                        const label = checkbox.next('.form-check-label');
                        const originalText = label.html();
                        label.html('<i class="fas fa-spinner fa-spin"></i> Procesando...');
                        
                        // 🔥 QUINTO: Llamar al endpoint
                        fetch(`/horsecard/supplies/${supplyId}/execute-shift`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ shift: shift })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Respuesta ejecución:', data);
                            if (data.success) {
                                // 🔥 SweetAlert2 para éxito
                                Swal.fire({
                                    position: "top-end",
                                    title: '¡Éxito!',
                                    text: data.message,
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 1500,
                                    width: '50%',
                                    customClass: {
                                        container: 'swal2-responsive',
                                        popup: 'swal2-responsive'
                                    }
                                }).then(() => {
                                    // 🔥 Redirigir después del éxito
                                    setTimeout(() => {
                                        window.location.href = data.redirect_url || '{{ route("horsecard.index") }}';
                                    }, 500);
                                });
                            } else {
                                // 🔥 SweetAlert2 para error
                                Swal.fire({
                                    title: 'Error',
                                    text: data.message,
                                    icon: 'error',
                                    confirmButtonColor: '#d33',
                                    confirmButtonText: 'Aceptar',
                                    width: '90%',
                                    customClass: {
                                        container: 'swal2-responsive',
                                        popup: 'swal2-responsive'
                                    }
                                });
                                // Resetear el checkbox
                                resetCheckbox(checkbox, originalText);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // 🔥 SweetAlert2 para error de conexión
                            Swal.fire({
                                title: 'Error de Conexión',
                                text: 'No se pudo completar la acción. Verifique su conexión.',
                                icon: 'error',
                                confirmButtonColor: '#d33',
                                confirmButtonText: 'Aceptar',
                                width: '50%',
                                customClass: {
                                    container: 'swal2-responsive',
                                    popup: 'swal2-responsive'
                                }
                            });
                            // Resetear el checkbox
                            resetCheckbox(checkbox, originalText);
                        })
                        .finally(() => {
                            checkbox.prop('disabled', false);
                        });
                    } else {
                        // 🔥 Si cancela, resetear el checkbox
                        resetCheckbox(checkbox, '');
                    }
                });
            });
        }
    });

    // 🔥 FUNCIÓN AUXILIAR PARA RESETEAR CHECKBOX
    function resetCheckbox(checkbox, originalText) {
        checkbox.prop('checked', false);
        if (originalText) {
            checkbox.next('.form-check-label').html(originalText);
        }
    }

    $(document).ready(function() {
        console.log('Script de horsecard cargado - Comportamiento mejorado para móviles');
    });
</script>
@endpush