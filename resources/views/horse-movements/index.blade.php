@extends('admin.layouts.master')

@section('content')

    <div class="row">
        <!-- Sidebar -->
       
        <!-- Main Content -->
        <div class="col-md-12 col-lg-12 ml-sm-auto px-4 py-3">
            <!-- Topbar -->
            <div class="topbar mb-4 rounded">
                <div class="d-flex justify-content-between align-items-center px-3">
                    <h5 class="m-0">Direct Movement</h5>
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
                        <table class="table border-top-0  table-bordered text-nowrap border-bottom" id="responsive-datatable"">
                            <thead>
                                <tr style="background-color: #f2eeee">
                                    <th>ID</th>
                                    <th>Horse</th>
                                    <th class="text-danger">Origin</th>
                                    <th class="text-info">Destination</th>
                                    <th>Reason</th>
                                    <th>Date</th>
                                    <th>Required</th>
                                    <th>options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($movements as $movement)
                                <tr>
                                    <td>#{{ $movement->id }}</td>
                                    <td>{{ $movement->horse->name }}</td>
                                    <td>{{ $movement->fromStable->name }}</td>
                                    <td>{{ $movement->toStable->name }}</td>
                                    <td>
                                        {{ $movement->reason }}
                                    </td>
                                    <td>{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ $movement->movedBy->name }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info"><i class="bi bi-eye"></i></button>
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No se encontraron movimientos</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
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
            <form action="{{ route('horse-movements.store') }}" method="POST">
                @csrf
                <!-- Campo hidden para el usuario autenticado -->
                <input type="hidden" name="moved_by" value="{{ Auth::id() }}">
                
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="horse_id" class="form-label">Horse <span class="text-danger">*</span></label>
                            <select class="form-select" id="horse_id" name="horse_id" required>
                                <option value="">Select horse</option>
                                @foreach($horses as $horse)
                                    <option value="{{ $horse->id }}">{{ $horse->name }} ({{ $horse->stable->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="to_stable_id" class="form-label">Destination <span class="text-danger">*</span></label>
                            <select class="form-select" id="to_stable_id" name="to_stable_id" required>
                                <option value="">Select destination</option>
                                @foreach($stables as $stable)
                                    <option value="{{ $stable->id }}">{{ $stable->name }} 
                                        
                                            (Capacity: {{ $stable->horses->count() }}/{{ $stable->capacity }})
                                        
                                    </option> 
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-2">
                            <label for="reason" class="form-label">Reason for Movement <span class="text-danger">*</span></label>
                            <select class="form-select" id="reason" name="reason" required>
                                <option value="">Select reason</option>
                                <option value="ROTATION">ROTATION</option>
                                <option value="TRAINING">TRAINING</option>
                                <option value="DOCTOR">DOCTOR</option>
                                <option value="COMPETENCE">COMPETENCE</option>
                                <option value="RETUR">RETUR</option>
                                <option value="CLEANING">CLEANING</option>
                                <option value="HORSESHOE">HORSESHOE</option>
                                <option value="OTHER">OTHER</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="notes" class="form-label">Observation</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Additional notes on the movement"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger mr-5" style="width: 150px" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning" style="width: 150px">move</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@push('styles')
<style>
    :root {
        --primary: #4e73df;
        --secondary: #6f42c1;
        --success: #1cc88a;
        --info: #36b9cc;
        --warning: #f6c23e;
        --danger: #e74a3b;
        --light: #f8f9fc;
        --dark: #5a5c69;
    }
    
    .sidebar {
        min-height: 100vh;
        background: linear-gradient(180deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        box-shadow: 4px 0 10px rgba(0,0,0,0.1);
    }
    
   
    
    .sidebar .nav-link:hover, .sidebar .nav-link.active {
        color: white;
        background-color: rgba(255, 255, 255, 0.1);
    }
    
    .topbar {
        background-color: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 1rem 0;
    }
    
    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }
    
    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        font-weight: 700;
    }
    
   
       
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Manejar el modal de nuevo movimiento
    const newMovementModal = document.getElementById('newMovementModal');
    if (newMovementModal) {
        newMovementModal.addEventListener('show.bs.modal', function (event) {
            // Lógica adicional cuando se abre el modal
        });
    }
</script>
@endpush