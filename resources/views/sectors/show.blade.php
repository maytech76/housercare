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
                        <a href="#">
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
                                                <th>Shift</th>
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
                                                            <div class="fw-bold">{{ $horse->name }}</div>
                                                            <small class="text-muted">{{ $horse->breed }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="stable-info">{{ $horse->stable->name ?? 'N/A' }}</td>
                                                {{-- <td>
                                                    @php
                                                        $today = now()->format('Y-m-d');
                                                        $movement = $horse->movementDetails()
                                                            ->whereDate('limit_date', $today)
                                                            ->where('is_executed', false)
                                                            ->first();
                                                    @endphp
                                                    
                                                    @if($movement)
                                                        <span class="badge shift-badge 
                                                            {{ $movement->shift == 'AM' ? 'bg-warning text-dark' : 'bg-info' }}">
                                                            {{ $movement->shift == 'AM' ? 'Mañana' : 'Tarde' }}
                                                        </span>
                                                    @else
                                                        <span class="badge shift-badge bg-secondary">Sin turno</span>
                                                    @endif
                                                </td> --}}
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3">
                                                    No hay caballos en este sector
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
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

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
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
            card.style.backgroundColor = color + '20';
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
</script>
    
@endpush