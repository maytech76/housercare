@extends('admin.layouts.master')

@section('content')
<section>
    <div class="main-container container-fluid">
        <!-- breadcrumb -->
        <div class="breadcrumb-header justify-content-between">
            <div class="my-auto">
                <h4 class="page-title">Ficha del Caballo - {{ $horse->name }}</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Horse card</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Gestión de tarjetas</li>
                </ol>
            </div>
            <div class="d-flex my-xl-auto right-content align-items-center">
                <div class="mb-xl-0">
                    <a href="{{ route('supplies.create') }}?horse_id={{ $horse->id }}" class="btn btn-primary">
                        <i class="fe fe-plus"></i> Nuevo Suministro
                    </a>
                </div>
            </div>
        </div>
        <!-- breadcrumb -->

        <div class="main-content-body main-content-body-contacts card custom-card">
            <div class="main-contact-info-header pt-3">
                <div class="media">
                    <div class="main-img-user">
                        <img alt="avatar" src="{{ $horse->photo ? asset('storage/' . $horse->photo) : asset('storage/horses/horse.png') }}">
                    </div>
                    <div class="media-body">
                        <h5>{{ $horse->name }}</h5>
                        <p>{{ $horse->stable->name ?? 'Sin establo' }}</p>
                        <nav class="contact-info">
                            <span class="contact-icon border tx-inverse" data-bs-toggle="tooltip" title="Raza">
                                <i class="fe fe-feather"></i> {{ $horse->breed }}
                            </span>
                            <span class="contact-icon border tx-inverse" data-bs-toggle="tooltip" title="Edad">
                                <i class="fe fe-calendar"></i> {{ $horse->age }} años
                            </span>
                            <span class="contact-icon border tx-inverse" data-bs-toggle="tooltip" title="Condición">
                                <i class="fe fe-heart"></i> {{ $horse->condition }}
                            </span>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="main-contact-info-body p-4">
                <!-- Suministros del Día Actual -->
                <div class="row">
                    <!-- Tabla Turno AM -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header card-draggable bg-primary text-white">
                                <h5>Suministros Turno AM - {{ now()->format('d/m/Y') }}</h5>
                            </div>
                            <div class="card-body">
                                @if($amSupplies->count() > 0)
                                    @foreach($amSupplies as $supply)
                                    <div class="supply-card mb-3 p-3 border rounded">
                                        <h6 class="text-primary">Suministro #{{ $supply->supply_number }}</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Producto</th>
                                                        <th>Cantidad</th>
                                                        <th>Unidad</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($supply->supplyDetails->where('shift', 'AM') as $detail)
                                                    <tr>
                                                        <td>{{ $detail->product->name ?? 'N/A' }}</td>
                                                        <td>{{ $detail->quantity }}</td>
                                                        <td>{{ $detail->product->consumptionUnit->name ?? 'N/A' }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="supply-info">
                                            <small class="text-muted">
                                                Almacén: {{ $supply->store->name ?? 'N/A' }} | 
                                                Asignado por: {{ $supply->assignedBy->name ?? 'N/A' }}
                                            </small>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <p class="text-muted text-center">No hay suministros asignados para el turno AM</p>
                                @endif
                            </div>
                            @if($amSupplies->count() > 0)
                                <div class="card-footer">
                                    <div class="row justify-content-center">
                                        <div class="col-md-12 text-center">
                                            <label class="form-switch d-inline-flex align-items-center">
                                                <span class="form-switch-description tx-16 me-3">Ejecutar AM</span>
                                                <input type="checkbox" 
                                                    class="form-switch-input execute-supply" 
                                                    data-supply-id="{{ $amSupplies->first()->id }}"
                                                    data-shift="AM"
                                                    {{ $amSupplies->first()->status == 'EXECUTED' ? 'checked disabled' : '' }}>
                                                <span class="form-switch-indicator form-switch-indicator-xl custom-radius"></span>
                                            </label>
                                            @if($amSupplies->first()->status == 'EXECUTED')
                                            <small class="text-success d-block mt-1">
                                                <i class="fe fe-check"></i> Ejecutado
                                            </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tabla Turno PM -->
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header card-draggable bg-warning text-dark">
                                <h5>Suministros Turno PM - {{ now()->format('d/m/Y') }}</h5>
                            </div>
                            <div class="card-body">
                                @if($pmSupplies->count() > 0)
                                    @foreach($pmSupplies as $supply)
                                    <div class="supply-card mb-3 p-3 border rounded">
                                        <h6 class="text-warning">Suministro #{{ $supply->supply_number }}</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Producto</th>
                                                        <th>Cantidad</th>
                                                        <th>Unidad</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($supply->supplyDetails->where('shift', 'PM') as $detail)
                                                    <tr>
                                                        <td>{{ $detail->product->name ?? 'N/A' }}</td>
                                                        <td>{{ $detail->quantity }}</td>
                                                        <td>{{ $detail->product->consumptionUnit->name ?? 'N/A' }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="supply-info">
                                            <small class="text-muted">
                                                Almacén: {{ $supply->store->name ?? 'N/A' }} | 
                                                Asignado por: {{ $supply->assignedBy->name ?? 'N/A' }}
                                            </small>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <p class="text-muted text-center">No hay suministros asignados para el turno PM</p>
                                @endif
                            </div>
                            @if($pmSupplies->count() > 0)
                            <div class="card-footer">
                                <div class="row justify-content-center">
                                    <div class="col-md-12 text-center">
                                        <label class="form-switch d-inline-flex align-items-center">
                                            <span class="form-switch-description tx-16 me-3">Ejecutar PM</span>
                                            <input type="checkbox" 
                                                   class="form-switch-input execute-supply" 
                                                   data-supply-id="{{ $pmSupplies->first()->id }}"
                                                   data-shift="PM"
                                                   {{ $pmSupplies->first()->status == 'EXECUTED' ? 'checked disabled' : '' }}>
                                            <span class="form-switch-indicator form-switch-indicator-xl custom-radius"></span>
                                        </label>
                                        @if($pmSupplies->first()->status == 'EXECUTED')
                                        <small class="text-success d-block mt-1">
                                            <i class="fe fe-check"></i> Ejecutado
                                        </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Historial de Suministros -->
                @if($historicalSupplies->count() > 0)
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6>Historial de Suministros</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Número</th>
                                                <th>Turno</th>
                                                <th>Estado</th>
                                                <th>Productos</th>
                                                <th>Ejecutado por</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($historicalSupplies as $supply)
                                            <tr>
                                                <td>{{ $supply->supply_date->format('d/m/Y') }}</td>
                                                <td>{{ $supply->supply_number }}</td>
                                                <td>
                                                    @php
                                                        $shifts = $supply->supplyDetails->pluck('shift')->unique()->implode(', ');
                                                    @endphp
                                                    {{ $shifts }}
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $supply->status == 'EXECUTED' ? 'success' : 'secondary' }}">
                                                        {{ $supply->status }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @foreach($supply->supplyDetails as $detail)
                                                        <small class="d-block">
                                                            {{ $detail->product->name ?? 'N/A' }} - 
                                                            {{ $detail->quantity }} {{ $detail->product->consumptionUnit->name ?? '' }}
                                                        </small>
                                                    @endforeach
                                                </td>
                                                <td>{{ $supply->executedBy->name ?? 'N/A' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.execute-supply').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    const supplyId = this.getAttribute('data-supply-id');
                    const shift = this.getAttribute('data-shift');
                    
                    if (confirm(`¿Está seguro de ejecutar el suministro del turno ${shift}?`)) {
                        executeSupply(supplyId, shift, this);
                    } else {
                        this.checked = false;
                    }
                }
            });
        });

        function executeSupply(supplyId, shift, checkbox) {
            fetch(`/supplies/${supplyId}/execute`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    checkbox.disabled = true;
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alert(data.message);
                    checkbox.checked = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al ejecutar el suministro');
                checkbox.checked = false;
            });
        }
    });
</script>
@endpush