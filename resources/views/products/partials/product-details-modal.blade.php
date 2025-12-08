<div class="product-details-modal">
    <!-- Debug: Mostrar datos recibidos -->
    <div class="alert alert-info mb-3">
        <h6>Debug Info:</h6>
        <pre id="debugInfo" style="display: none;">{{ json_encode(get_defined_vars(), JSON_PRETTY_PRINT) }}</pre>
        <button onclick="document.getElementById('debugInfo').style.display='block'" class="btn btn-sm btn-warning">
            Mostrar Debug
        </button>
    </div>

    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-md-3 text-center">
            @if($product->photo)
                <img src="{{ asset('storage/' . $product->photo) }}" 
                     alt="{{ $product->name }}"
                     class="img-thumbnail"
                     style="width: 120px; height: 120px; object-fit: cover; border-radius: 10px;">
            @else
                <div class="bg-light d-flex align-items-center justify-content-center" 
                     style="width: 120px; height: 120px; border-radius: 10px;">
                    <i class="fas fa-box fa-3x text-muted"></i>
                </div>
            @endif
        </div>
        <div class="col-md-9">
            <h4 class="text-primary mb-2">{{ $product->name ?? 'N/A' }}</h4>
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Código:</strong> {{ $product->codebar ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Categoría:</strong> {{ $product->category->name ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Tipo:</strong> 
                        <span class="badge bg-primary">
                            {{ ucfirst($product->type ?? 'N/A') }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong>Existencias Totales:</strong> 
                        <span class="badge bg-{{ $totalStock > 0 ? 'success' : 'danger' }}">
                            {{ number_format($totalStock, 2) }}
                        </span>
                    </p>
                    @if($product->expired)
                    <p class="mb-1"><strong>Vence:</strong> 
                        <span class="{{ \Carbon\Carbon::parse($product->expired)->isPast() ? 'text-danger' : 'text-success' }}">
                            {{ \Carbon\Carbon::parse($product->expired)->format('Y-m-d') }}
                        </span>
                    </p>
                    @endif
                    <p class="mb-1"><strong>Estado:</strong> 
                        <span class="badge bg-{{ $product->status ? 'success' : 'secondary' }}">
                            {{ $product->status ? 'Activo' : 'Inactivo' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Descripción -->
    @if($product->description)
    <div class="row mb-4">
        <div class="col-12">
            <h6 class="text-primary">Descripción</h6>
            <div class="alert alert-light p-3 border">
                {{ $product->description }}
            </div>
        </div>
    </div>
    @endif

    <!-- Información de Unidades -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h6 class="text-primary">Unidades de Medida</h6>
            <table class="table table-sm">
                <tr>
                    <td width="40%"><strong>Compra:</strong></td>
                    <td>{{ $product->purchaseUnit->symbol ?? 'N/A' }} ({{ $product->purchaseUnit->name ?? 'N/A' }})</td>
                </tr>
                <tr>
                    <td><strong>Consumo:</strong></td>
                    <td>{{ $product->consumptionUnit->symbol ?? 'N/A' }} ({{ $product->consumptionUnit->name ?? 'N/A' }})</td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <h6 class="text-primary">Información de Costos</h6>
            <table class="table table-sm">
                <tr>
                    <td width="40%"><strong>Costo:</strong></td>
                    <td>${{ number_format($product->cost ?? 0, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Precio:</strong></td>
                    <td>${{ number_format($product->price ?? 0, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Existencias -->
    @if(count($stocksByStore) > 0)
    <div class="row">
        <div class="col-12">
            <h6 class="text-primary mb-3">Existencias por Tienda/Almacén</h6>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Tienda/Almacén</th>
                            <th class="text-center">Comprado</th>
                            <th class="text-center">Consumido</th>
                            <th class="text-center">Disponible</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stocksByStore as $stock)
                        <tr>
                            <td>{{ $stock['store_name'] ?? 'N/A' }}</td>
                            <td class="text-center">{{ number_format($stock['purchase_quantity'] ?? 0, 2) }}</td>
                            <td class="text-center">{{ number_format($stock['consumption_quantity'] ?? 0, 2) }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ ($stock['available'] ?? 0) > 0 ? 'success' : 'danger' }}">
                                    {{ number_format($stock['available'] ?? 0, 2) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-warning text-center">
        <i class="fas fa-exclamation-triangle"></i> No hay existencias registradas para este producto.
    </div>
    @endif
</div>