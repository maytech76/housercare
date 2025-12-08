@extends('admin.layouts.master')

@section('content')
<div class="main-container container-fluid">

     <!-- breadcrumb -->
     <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <h4 class="page-title">Administration</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Movement history</a></li>
                <li class="breadcrumb-item active" aria-current="page">Inventory management</li>
            </ol>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center">
            <div class="mb-xl-0">
                <div class="dropdown">
                    <a href="{{ route('inventory-controls.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> New Operation
                    </a>
                    <a href="{{ route('inventory-controls.report') }}" class="btn btn-info">
                        <i class="fas fa-chart-bar"></i> Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->

    
    {{-- Listado de registros efectuados --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="operations-datatable">
                    <thead>
                        <tr>
                            <th class="border-bottom-0 bg-light">Operation N°</th>
                            <th class="border-bottom-0 bg-light">Date/Time</th>
                            <th class="border-bottom-0 bg-light">Type</th>
                            <th class="text-center border-bottom-0 bg-light">Total Items</th>
                            <th class="border-bottom-0 bg-light">Total Cost</th>
                            <th class="border-bottom-0 bg-light">Responsible User</th>
                            <th class="border-bottom-0 bg-light">General Notes</th>
                            <th class="border-bottom-0 bg-light">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($operations as $operation)
                        <tr>
                            <td>
                                <strong class="text-primary">{{ $operation->operation_number }}</strong>
                            </td>
                            <td>{{ $operation->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="border border-{{ $operation->operation_type == 'single' ? 'info' : 'warning' }} py-1 px-2 rounded text-{{ $operation->operation_type == 'single' ? 'info' : 'warning' }}">
                                    {{ ucfirst($operation->operation_type) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="border border-secondary py-1 px-2 rounded text-secondary">{{ $operation->inventoryControls->count() }}</span>
                            </td>
                            <td>
                                <strong>${{ number_format($operation->total_cost, 2) }}</strong>
                            </td>
                            <td>{{ $operation->user->name }}</td>
                            <td>
                                @if($operation->general_notes)
                                    <small class="text-muted">{{ Str::limit($operation->general_notes, 30) }}</small>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <!-- Botón Ver Detalles en Modal -->
                                    <button type="button" class="btn btn-info btn-sm" 
                                            onclick="showOperationDetails({{ $operation->id }})"
                                            title="View Operation Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <!-- Botón Editar -->
                                    <a href="{{ route('inventory-controls.operation.edit', $operation) }}" 
                                       class="btn btn-warning btn-sm"
                                       title="Edit Operation">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- Botón PDF -->
                                    <a href="{{ route('inventory-controls.operation', $operation) }}" 
                                        class="btn btn-danger btn-sm"
                                        title="Download PDF"
                                        target="_blank">
                                    <i class="fas fa-file-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Descomentar la paginación si no usas DataTables -->
           {{--  {{ $operations->links() }} --}}
        </div>
    </div>

</div>

<!-- Modal para mostrar detalles de la operación -->
<div class="modal fade" id="operationDetailsModal" tabindex="-1" aria-labelledby="operationDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="operationDetailsModalLabel">Operation Details - <span id="modalOperationNumber"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="operationDetailsContent">
                    <!-- Los detalles se cargarán aquí via AJAX -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger w-10" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .btn-group .btn {
        margin-right: 2px;
    }
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    .table td {
        vertical-align: middle;
    }
    /* Estilos para DataTables */
    .dataTables_length, .dataTables_filter {
        margin-bottom: 15px;
    }
</style>
@endpush

@push('scripts')
<!-- Incluir DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    // Inicializar DataTables
    $(document).ready(function() {
        $('#operations-datatable').DataTable({
            "pageLength": 25,
            "order": [[1, 'desc']], // Ordenar por fecha descendente
            "language": {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Previous"
                }
            }
        });
    });

    // Función para mostrar detalles de la operación en modal
    function showOperationDetails(operationId) {
        // Mostrar loading
        $('#operationDetailsContent').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading operation details...</p>
            </div>
        `);
        
        // Hacer petición AJAX para obtener los detalles
        fetch(`/inventory-controls/operations/${operationId}/details`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#modalOperationNumber').text(data.operation.operation_number);
                    $('#operationDetailsContent').html(data.html);
                } else {
                    $('#operationDetailsContent').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> Error loading operation details.
                        </div>
                    `);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                $('#operationDetailsContent').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> Error loading operation details.
                    </div>
                `);
            });
        
        // Mostrar el modal
        const modal = new bootstrap.Modal(document.getElementById('operationDetailsModal'));
        modal.show();
    }
</script>
@endpush