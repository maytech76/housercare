<div class="supply-details">
    <!-- Información Básica -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h6 class="text-primary">Información del Suministro</h6>
            <table class="table table-sm table-borderless">
                <tr>
                    <td><strong>Número:</strong></td>
                    <td>{{ $supply->supply_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Caballo:</strong></td>
                    <td>{{ $supply->horse->name }}</td>
                </tr>
                <tr>
                    <td><strong>Almacén:</strong></td>
                    <td>{{ $supply->store->name }}</td>
                </tr>
                <tr>
                    <td><strong>Fecha Asignación:</strong></td>
                    <td>{{ \Carbon\Carbon::parse($supply->supply_date)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td><strong>Estado General:</strong></td>
                    <td>
                        @if($supply->status === 'EXECUTED')
                            <span class="badge bg-success">COMPLETADO</span>
                        @else
                            <span class="badge bg-warning">EN PROCESO</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <h6 class="text-primary">Responsables</h6>
            <table class="table table-sm table-borderless">
                <tr>
                    <td><strong>Asignado por:</strong></td>
                    <td>{{ $supply->assignedBy->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Completado por:</strong></td>
                    <td>
                        @if($supply->status === 'EXECUTED' && $supply->executedBy)
                            {{ $supply->executedBy->name }}
                        @else
                            <span class="text-muted">Pendiente</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><strong>Fecha Completado:</strong></td>
                    <td>
                        @if($supply->executed_at)
                            {{ \Carbon\Carbon::parse($supply->executed_at)->format('d/m/Y H:i') }}
                        @else
                            <span class="text-muted">Pendiente</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Productos -->
    @if($supply->supplyDetails && $supply->supplyDetails->count() > 0)
    <div class="row">
        <div class="col-12">
            <h6 class="text-primary mb-3">Productos Asignados</h6>

            <!-- TURNO AM -->
            @if($amProducts->count() > 0)

            {{-- Products Shift AM --}}
            <div class="card mb-3">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <strong>Supply Turno AM</strong>
                    @if($amExecuted)
                        <span class="badge bg-success">Ejecutado</span>
                    @else
                        <span class="badge bg-warning">Pendiente</span>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Producto</th>
                                    <th width="100" class="text-center">Cantidad</th>
                                    <th width="100" class="text-center">Unidad</th>
                                    <th width="120" class="text-center">Fecha Límite</th>
                                    <th width="80" class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($amProducts as $detail)
                                <tr>
                                    <td>{{ $detail->product->name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $detail->quantity }}</td>
                                    <td class="text-center">{{ $detail->product->consumptionUnit->name ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        @if($detail->limit_date)
                                            {{ \Carbon\Carbon::parse($detail->limit_date)->format('d/m/Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($detail->is_executed)
                                            <span class="badge bg-success btn-sm">✓</span>
                                        @else
                                            <span class="badge bg-secondary btn-sm">⏳</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Checkbox para ejecutar turno AM -->
                    <div class="card-footer bg-light border-top p-3">
                        <div class="row justify-content-center">
                            <div class="col-md-12 text-center">
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" 
                                        class="form-check-input execute-supply" 
                                        id="execute-am-{{ $supply->id }}"
                                        data-supply-id="{{ $supply->id }}"
                                        data-shift="AM"
                                        {{ $amExecuted ? 'checked disabled' : '' }}
                                        style="width: 20px; height: 20px; margin-right: 10px;">
                                    <label class="form-check-label fw-bold {{ $amExecuted ? 'text-success' : 'text-primary' }}" 
                                        for="execute-am-{{ $supply->id }}">
                                        @if($amExecuted)
                                            <i class="fas fa-check-circle"></i> Turno AM Ejecutado
                                        @else
                                            <i class="fas fa-play-circle"></i> Ejecutar Turno AM
                                        @endif
                                    </label>
                                </div>
                                @if($amExecuted && $amExecutionInfo)
                                <small class="text-muted d-block mt-1">
                                    Ejecutado por: {{ $amExecutionInfo->executedBy->name ?? 'Sistema' }} 
                                    el {{ \Carbon\Carbon::parse($amExecutionInfo->executed_at)->format('d/m/Y H:i') }}
                                </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Products Shift PM-->
            @if($pmProducts->count() > 0)
            <div class="card mb-3">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <strong> Supply Turno PM</strong>
                    @if($pmExecuted)
                        <span class="badge bg-success">Ejecutado</span>
                    @else
                        <span class="badge bg-warning">Pendiente</span>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Producto</th>
                                    <th width="100" class="text-center">Cantidad</th>
                                    <th width="100" class="text-center">Unidad</th>
                                    <th width="120" class="text-center">Fecha Límite</th>
                                    <th width="80" class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pmProducts as $detail)
                                <tr>
                                    <td>{{ $detail->product->name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $detail->quantity }}</td>
                                    <td class="text-center">{{ $detail->product->consumptionUnit->name ?? 'N/A' }}</td>
                                    <td class="text-center">
                                        @if($detail->limit_date)
                                            {{ \Carbon\Carbon::parse($detail->limit_date)->format('d/m/Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($detail->is_executed)
                                            <span class="badge bg-success btn-sm">✓</span>
                                        @else
                                            <span class="badge bg-secondary btn-sm">⏳</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Checkbox para ejecutar turno PM -->
                    <div class="card-footer bg-light border-top p-3">
                        <div class="row justify-content-center">
                            <div class="col-md-12 text-center">
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" 
                                        class="form-check-input execute-supply" 
                                        id="execute-pm-{{ $supply->id }}"
                                        data-supply-id="{{ $supply->id }}"
                                        data-shift="PM"
                                        {{ $pmExecuted ? 'checked disabled' : '' }}
                                        style="width: 20px; height: 20px; margin-right: 10px;">
                                    <label class="form-check-label fw-bold {{ $pmExecuted ? 'text-success' : 'text-warning' }}" 
                                        for="execute-pm-{{ $supply->id }}">
                                        @if($pmExecuted)
                                            <i class="fas fa-check-circle"></i> Turno PM Ejecutado
                                        @else
                                            <i class="fas fa-play-circle"></i> Ejecutar Turno PM
                                        @endif
                                    </label>
                                </div>
                                @if($pmExecuted && $pmExecutionInfo)
                                <small class="text-muted d-block mt-1">
                                    Ejecutado por: {{ $pmExecutionInfo->executedBy->name ?? 'Sistema' }} 
                                    el {{ \Carbon\Carbon::parse($pmExecutionInfo->executed_at)->format('d/m/Y H:i') }}
                                </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> No hay productos asignados a este suministro.
    </div>
    @endif

    <!-- Observaciones -->
    @if($supply->notes)
    <div class="row mt-4">
        <div class="col-12">
            <h6 class="text-primary">Observaciones</h6>
            <div class="card">
                <div class="card-body">
                    <p class="mb-0">{{ $supply->notes }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>