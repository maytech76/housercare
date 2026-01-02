@extends('admin.layouts.master')

@section('content')
<section>
    {{-- Tabla de Sectores--}}
    <div class="row row-sm mt-4 mx-auto">
        <div class="container-fluid py-4">
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="h2">Sector Management</h1>
                    <p class="text-muted">Visualization of horses by sector and stable</p>
                </div>
            </div>
    
            <div class="row" id="sectors-container">
                @foreach($sectors as $sector)
                <div class="col-lg-4 col-md-6 mb-4">
                    @php
                        // Verificar si hay ALGÚN caballo con is_moved = true en este sector
                        $hasHorsesWithMovement = $sector->horses->contains(function($horse) {
                            return $horse->is_moved == true;
                        });
                    @endphp
                    
                    {{-- SIEMPRE hacer el enlace, pero con atributo data para JavaScript --}}
                    <a href="{{ $hasHorsesWithMovement ? route('sectors.detail', $sector->id) : 'javascript:void(0)' }}" 
                       class="sector-link" 
                       data-sector-id="{{ $sector->id }}"
                       data-sector-name="{{ $sector->name }}"
                       data-has-movement="{{ $hasHorsesWithMovement ? 'true' : 'false' }}">
                    
                        <div class="card sector-card" style="border-left: 5px solid {{ $sector->color ?? '#3490dc' }};">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">{{ $sector->name }}</h5>
                                    <p class="p-2 fw-100" style="border-radius: 3px; border: 1px solid {{ $sector->color ?? '' }};">
                                        {{ $sector->stables->count() }} Locations
                                    </p>
                                </div>
                                
                                @if($sector->description)
                                <p class="card-text small text-muted mb-3">{{ Str::limit($sector->description, 100) }}</p>
                                @endif
        
                                <div class="table-responsive">
                                    <table class="table table-sm horse-table">
                                        <thead>
                                            <tr>
                                                <th>Horse</th>
                                                <th>Location</th>
                                                <th>Status</th>
                                                <th>Move</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($sector->horses as $horse)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($horse->photo)
                                                        <img src="{{ asset('storage/' . $horse->photo) }}" 
                                                            alt="{{ $horse->name }}" 
                                                            class="rounded-circle me-2" 
                                                            width="30" height="30">
                                                        @else
                                                        <div class="rounded-circle bg-secondary me-2" 
                                                            style="width: 30px; height: 30px;"></div>
                                                        @endif
                                                        <div>
                                                            <div class="fw-light">{{ $horse->name }}</div>
                                                            {{-- <small class="text-muted">ID: {{ $horse->id }}</small> --}}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="stable-info">{{ $horse->stable->name ?? 'N/A' }}</td>

                                                {{-- Mostrar el status del movimiento --}}
                                                <td class="stable-info">
                                                    @php
                                                        // Buscar el movimiento del caballo actual
                                                        $movement = $movements[$horse->id] ?? null;
                                                        $status = $movement->status ?? 'NO MOVE';
                                                        
                                                        // Mapear clases de color según el status
                                                        $textClass = match($status) {
                                                            'ASSIGNED' => 'text-warning fw-light',
                                                            'PARTIALLY' => 'text-info fw-light',
                                                            'EXECUTED' => 'text-success fw-light',
                                                            default => 'text-secondary fw-light'
                                                        };
                                                    @endphp
                                                    
                                                    <span class="{{ $textClass }}">
                                                        {{ $status }}
                                                    </span>
                                                </td>
                                                
                                                {{-- Indicador is_moved --}}
                                                <td class="text-center">
                                                    @if($horse->is_moved)
                                                        <span class="badge bg-success" title="Ready to move">
                                                            <i class="fas fa-check-circle"></i>
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary" title="Not ready to move">
                                                            <i class="fas fa-times-circle"></i>
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-3">
                                                    There are no active horses in this sector
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                
                                {{-- Resumen del sector --}}
                                {{-- <div class="mt-3 pt-3 border-top">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="small text-muted">Total Horses</div>
                                            <div class="h5 mb-0">{{ $sector->horses->count() }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="small text-muted">Ready to Move</div>
                                            <div class="h5 mb-0 {{ $hasHorsesWithMovement ? 'text-success' : 'text-warning' }}">
                                                {{ $sector->horses->where('is_moved', true)->count() }}
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
    
            @if($sectors->isEmpty())
            <div class="text-center py-5">
                <div class="display-1 text-muted">🏇</div>
                <h3 class="text-muted">No sectors available</h3>
                <p class="text-muted">Configure the sectors in the system.</p>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .sector-link {
        text-decoration: none;
        color: inherit;
        display: block;
        cursor: pointer;
    }
    
    .sector-card {
        transition: all 0.2s ease;
    }
    
    .sector-link:hover .sector-card {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }
    
    /* Indicador visual para sectores sin movimiento */
    .sector-link[data-has-movement="false"] .sector-card {
        border-left: 5px solid #dc3545 !important; /* Rojo para indicar falta de movimiento */
    }
</style>
@endpush

@push('scripts')
<!-- Incluir SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Actualizar automáticamente cada 5 minutos
    setTimeout(function() {
        window.location.reload();
    }, 300000);

    // Alternar color de fondo para mejor contraste
    document.querySelectorAll('.sector-card').forEach(card => {
        const color = card.style.borderLeftColor;
        const rgb = hexToRgb(color);
        const brightness = Math.round(((rgb.r * 299) + (rgb.g * 587) + (rgb.b * 114)) / 1000);
        
        if (brightness > 125) {
            card.style.backgroundColor = color + '15';
        }
    });

    function hexToRgb(hex) {
        const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : {r: 52, g: 144, b: 220};
    }
    
    // Manejar clic en sectores
    document.addEventListener('DOMContentLoaded', function() {
        const sectorLinks = document.querySelectorAll('.sector-link');
        
        sectorLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const hasMovement = this.getAttribute('data-has-movement') === 'true';
                const sectorName = this.getAttribute('data-sector-name');
                
                // Si NO tiene caballos con movimiento, mostrar alerta y cancelar navegación
                if (!hasMovement) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    Swal.fire({
                        title: 'No Movement Assigned',
                        html: `No horses with assigned movement in the sector <strong>"${sectorName}"</strong>`,
                        icon: 'warning',
                        showConfirmButton: true,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#d33',
                        showCancelButton: false,
                        position: 'center',
                        backdrop: true,
                        allowOutsideClick: true,
                        allowEscapeKey: true,
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        },
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
                    
                    // Agregar efecto visual de rechazo
                    const card = this.querySelector('.sector-card');
                    card.style.transform = 'translateX(10px)';
                    setTimeout(() => {
                        card.style.transform = '';
                    }, 300);
                }
                // Si SÍ tiene movimiento, permitir navegación normal
            });
        });
    });
</script>
@endpush