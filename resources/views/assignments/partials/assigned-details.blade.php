{{-- resources/views/assignments/partials/assigned-details.blade.php --}}
<div class="assignment-details">
    {{-- -------------------------------------- --}}
    <!------ Encabezado del modal de detalles ----->
    {{-- -------------------------------------- --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <h5 class="text-primary">Información de la Asignación</h5>
            <table class="table table-sm table-borderless">
                <tr>
                    <th class="fw-bold">Número:</th>
                    <td style="color: #939695;">{{ $assignment->assigned_number }}</td>
                </tr>
                <tr>
                    <th class="fw-bold">Fecha:</th>
                    <td style="color: #939695;">{{ \Carbon\Carbon::parse($assignment->assigned_date)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th class="fw-bold">Caballo:</th>
                    <td style="color: #939695;">{{ $assignment->horse->name }}</td>
                </tr>
                <tr>
                    <th class="fw-bold">Condición:</th>
                    <td style="color: #939695;">{{ $assignment->horse->condition }}</td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <h5 class="text-primary">Estado y Asignación</h5>
            <table class="table table-sm table-borderless">
                <tr>
                    <th width="40%" class="fw-bold">Estado:</th>
                    <td>
                        <span class="badge bg-{{ $assignment->status == 'ASSIGNED' ? 'warning' : 'success' }}">
                            {{ $assignment->status }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th class="fw-bold">Asignado por:</th>
                    <td style="color: #939695;">{{ $assignment->assignedBy->name }}</td>
                </tr>
                <tr>
                    <th class="fw-bold">Ejecutado por:</th>
                    <td style="color: #939695;">{{ $assignment->executedBy->name ?? 'No ejecutado' }}</td>
                </tr>
                @if($assignment->executed_at)
                <tr>
                    <th class="fw-bold">Fecha ejecución:</th>
                    <td style="color: #079442;">{{ \Carbon\Carbon::parse($assignment->executed_at)->format('d/m/Y H:i') }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <!-- Observaciones -->
    @if($assignment->notes)
    <div class="row mb-4">
        <div class="col-12">
            <h6 class="text-primary">Observaciones</h6>
            <div class="alert alert-info p-3">
                {{ $assignment->notes }}
            </div>
        </div>
    </div>
    @endif

    <!-- Detalles de Servicios por Turnos -->
    <div class="row">
        <div class="col-12">
            <h5 class="text-primary mb-3">Servicios Asignados</h5>
            
            <!-- Servicios Turno AM -->
            @if($assignment->assignmentDetails->where('shift', 'AM')->count() > 0)
            <div class="card mb-4">
                <div class="card-header bg-primary-gradient text-white">
                    <h6 class="mb-0">Turno AM 
                        <span class="badge bg-light text-dark ms-2">
                            {{ $assignment->assignmentDetails->where('shift', 'AM')->count() }} servicios
                        </span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="120">Servicio</th>
                                    <th width="120" class="text-center">Estado</th>
                                    <th width="120" class="text-center">Fecha Límite</th>
                                    <th width="140" class="text-center">Ejecutado el</th>
                                    <th width="120" class="text-center">Ejecutado por</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignment->assignmentDetails->where('shift', 'AM') as $detail)
                                <tr>
                                    <td>{{ $detail->service->name ?? 'Servicio no encontrado' }}</td> 
                                    <td class="text-center">
                                        <span class="badge bg-{{ $detail->is_executed ? 'success' : 'warning' }}">
                                            {{ $detail->is_executed ? 'EJECUTADO' : 'PENDIENTE' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($detail->limit_date)
                                            {{ \Carbon\Carbon::parse($detail->limit_date)->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($detail->executed_at)
                                            {{ \Carbon\Carbon::parse($detail->executed_at)->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($detail->executed_by)
                                            {{ $detail->executedBy->name ?? 'Usuario no encontrado' }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif


            <!-- Servicios Turno PM -->
            @if($assignment->assignmentDetails->where('shift', 'PM')->count() > 0)
            <div class="card mb-4">
                <div class="card-header bg-warning-gradient text-black">
                    <h6 class="mb-0">Turno PM 
                        <span class="badge bg-light text-dark ms-2">
                            {{ $assignment->assignmentDetails->where('shift', 'PM')->count() }} servicios
                        </span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="120">Servicio</th>
                                    <th width="120" class="text-center">Estado</th>
                                    <th width="120" class="text-center">Fecha Límite</th>
                                    <th width="140" class="text-center">Ejecutado el</th>
                                    <th width="120" class="text-center">Ejecutado por</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignment->assignmentDetails->where('shift', 'PM') as $detail)
                                <tr>
                                    <td>{{ $detail->service->name ?? 'Servicio no encontrado' }}</td> 
                                    <td class="text-center">
                                        <span class="badge bg-{{ $detail->is_executed ? 'success' : 'warning' }}">
                                            {{ $detail->is_executed ? 'EJECUTADO' : 'PENDIENTE' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($detail->limit_date)
                                            {{ \Carbon\Carbon::parse($detail->limit_date)->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($detail->executed_at)
                                            {{ \Carbon\Carbon::parse($detail->executed_at)->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($detail->executed_by)
                                            {{ $detail->executedBy->name ?? 'Usuario no encontrado' }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            

            @if($assignment->assignmentDetails->count() == 0)
            <div class="alert alert-warning text-center">
                <i class="fas fa-exclamation-triangle"></i> No hay servicios asignados.
            </div>
            @endif

        </div>
    </div>
</div>