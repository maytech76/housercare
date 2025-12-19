<div class="supply-details">
    {{-- -------------------------------------- --}}
    <!------ Encabezado del modal de detalles ----->
    {{-- -------------------------------------- --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <h5 class="text-primary">Información del Suministro</h5>
            <table class="table table-sm table-borderless">
                
                <tr>
                    <th class="fw-bold">Fecha:</th>
                    <td style="color: #939695;">{{ $supply->supply_date->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th class="fw-bold">Almacén:</th>
                    <td style="color: #939695;">{{ $supply->store->name }}</td>
                </tr>
                <tr>
                    <th class="fw-bold">Caballo:</th>
                    <td style="color: #939695;">{{ $supply->horse->name }}</td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <h5 class="text-primary">Estado y Asignación</h5>
            <table class="table table-sm table-borderless">
                <tr>
                    <th width="40%" class="fw-bold">Estado:</th>
                    <td style="color: #F5B727;">
                        <span class=" text-{{ $supply->status == 'ASSIGNED' ? 'warning' : 'success' }}">
                            {{ $supply->status }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th class="fw-bold">Asignado por:</th>
                    <td style="color: #939695;">{{ $supply->assignedBy->name }}</td>
                </tr>
                <tr>
                    <th class="fw-bold">Ejecutado por:</th>
                    <td style="color: #939695;">{{ $supply->executedBy->name ?? 'No ejecutado' }}</td>
                </tr>
                @if($supply->executed_at)
                <tr>
                    <th class="fw-bold">Fecha ejecución:</th>
                    <td style="color: #079442;">{{ $supply->executed_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <!-- Observaciones -->
    @if($supply->notes)
    <div class="row mb-4">
        <div class="col-12">
            <h6 class="text-primary">Observaciones</h6>
            <div class="alert alert-info p-3">
                {{ $supply->notes }}
            </div>
        </div>
    </div>
    @endif

    <!-- Detalles de Productos -->
    <div class="row">
        <div class="col-12">
            <h5 class="text-primary mb-3">Productos Asignados</h5>
            
            <!-- Suministros Turno AM -->
            @if($supply->supplyDetails->where('shift', 'AM')->count() > 0)
            <div class="card mb-4">
                <div class="card-header bg-primary-gradient text-white">
                    <h6 class="mb-0">Asignación Turno AM </h6>
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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supply->supplyDetails->where('shift', 'AM') as $detail)
                                <tr>
                                    <td>{{ $detail->product->name }}</td>
                                    <td class="text-center">{{ number_format($detail->quantity, 2) }}</td>
                                    <td class="text-center">{{ $detail->unit_name }}</td>
                                    <td class="text-center">
                                        @if($detail->limit_date)
                                            {{ $detail->limit_date->format('d/m/Y') }}
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

            <!-- Suministros Turno PM -->
            @if($supply->supplyDetails->where('shift', 'PM')->count() > 0)
            <div class="card">
                <div class="card-header bg-warning-gradient text-dark">
                    <h6 class="mb-0">Asignación Turno PM</h6>
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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($supply->supplyDetails->where('shift', 'PM') as $detail)
                                <tr>
                                    <td>{{ $detail->product->name }}</td>
                                    <td class="text-center">{{ number_format($detail->quantity, 2) }}</td>
                                    <td class="text-center">{{ $detail->unit_name }}</td>
                                    <td class="text-center">
                                        @if($detail->limit_date)
                                            {{ $detail->limit_date->format('d/m/Y') }}
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

            @if($supply->supplyDetails->count() == 0)
            <div class="alert alert-warning text-center">
                <i class="fas fa-exclamation-triangle"></i> No hay productos asignados en este suministro.
            </div>
            @endif
        </>
    </div>
</div>