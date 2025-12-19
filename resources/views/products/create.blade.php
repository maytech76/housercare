@extends('admin.layouts.master')

@section('content')
<div class="card m-4">
    <div class="card-body">
        <div class="main-content-label mg-b-5">
            Nuevo Producto
        </div>
        <p class="mg-b-20"></p>
        
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <h3 class="mb-4">Datos del Producto</h3>
            
            <div class="row">
                <!-- Columna izquierda - Campos principales -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">Información Básica</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">

                                    {{-- Category --}}
                                    <div class="form-group">
                                        <label class="form-label">Categoría *</label>
                                        <select name="category_id" id="category_id" class="form-control border border-secondary" required>
                                            <option value="">Seleccione una Categoría</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ (old('category_id') == $category->id) ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Name --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Nombre del Producto *</label>
                                        <input type="text" id="name" name="name" class="form-control border border-secondary" 
                                               value="{{ old('name') }}" required placeholder="Ingrese el nombre">
                                    </div>
                                </div>
                            </div>

                            {{-- Descripción --}}
                            <div class="form-group mt-3">
                                <label class="form-label">Descripción *</label>
                                <textarea name="description" id="description" class="form-control" rows="3" required>{{ old('description') }}</textarea>
                            </div>

                            <div class="row mt-3">
                                {{-- type --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type">Tipo *</label>
                                        <select name="type" id="type" class="form-control" required>
                                            <option value="supply" {{ (old('type') == 'supply') ? 'selected' : '' }}>Insumo</option>
                                            <option value="product" {{ (old('type') == 'product') ? 'selected' : '' }}>Producto</option>
                                            <option value="medicine" {{ (old('type') == 'medicine') ? 'selected' : '' }}>Medicamento</option>
                                            <option value="equipment" {{ (old('type') == 'equipment') ? 'selected' : '' }}>Equipo</option>
                                            <option value="service" {{ (old('type') == 'service') ? 'selected' : '' }}>Servicio</option>
                                        </select>
                                    </div>
                                </div>
                                {{-- CodeBar --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="codebar">Código de Barras</label>
                                        <input type="text" name="codebar" id="codebar" class="form-control" 
                                               value="{{ old('codebar') }}" placeholder="Opcional">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Unidades y Conversion --}}
                    <div class="card mb-4">
                        <div class="card-header">Unidades y Conversión</div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="purchase_unit_id">Unidad de Compra *</label>
                                        <select name="purchase_unit_id" id="purchase_unit_id" class="form-control" required>
                                            <option value="">Seleccionar unidad</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}" {{ (old('purchase_unit_id') == $unit->id) ? 'selected' : '' }}>
                                                    {{ $unit->name }} ({{ $unit->symbol }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="consumption_unit_id">Unidad de Consumo *</label>
                                        <select name="consumption_unit_id" id="consumption_unit_id" class="form-control" required>
                                            <option value="">Seleccionar unidad</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}" {{ (old('consumption_unit_id') == $unit->id) ? 'selected' : '' }}>
                                                    {{ $unit->name }} ({{ $unit->symbol }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="unit_conversion_id">Conversión *</label>
                                        <select name="unit_conversion_id" id="unit_conversion_id" class="form-control" required>
                                            <option value="">Seleccionar conversión</option>
                                            @foreach($unitConversions as $conversion)
                                                <option value="{{ $conversion->id }}" {{ (old('unit_conversion_id') == $conversion->id) ? 'selected' : '' }}>
                                                    1 {{ $conversion->fromUnit->symbol }} = {{ $conversion->factor }} {{ $conversion->toUnit->symbol }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Seleccione primero las unidades</small>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <!-- Columna derecha - Imagen y precios -->
                <div class="col-md-4">

                    <div class="card mb-4">
                        <div class="card-header">Imagen del Producto</div>
                        <div class="card-body text-center">
                            <div class="form-group">
                                <label for="photo" class="form-label">Seleccionar imagen</label>
                                <input type="file" name="photo" id="photo" class="form-control" 
                                       accept="image/jpeg,image/png,image/jpg,image/gif">
                                <small class="form-text text-muted">Formatos: JPEG, PNG, JPG, GIF (Max: 2MB)</small>
                            </div>
                        </div>
                    </div>

                    {{-- Costo - Precio - Expired --}} 
                    <div class="card mb-4">
                        <div class="card-header">Precios y Fechas</div>
                        <div class="card-body">

                            {{-- Cost --}}
                            <div class="form-group">
                                <label for="cost">Costo</label>
                                <input type="number" step="0.01" name="cost" id="cost" class="form-control" 
                                       value="{{ old('cost') }}" placeholder="0.00">
                            </div>

                            {{-- Price --}}
                            <div class="form-group mt-3">
                                <label for="price">Precio de Venta</label>
                                <input type="number" step="0.01" name="price" id="price" class="form-control" 
                                       value="{{ old('price') }}" placeholder="0.00">
                            </div>

                            {{-- Expired --}}
                            <div class="form-group mt-3">
                                <label for="expired">Fecha de Expiración</label>
                                <input type="date" name="expired" id="expired" class="form-control" 
                                       value="{{ old('expired') }}">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Stock Inicial -->
            <div class="card mb-4">
                <div class="card-header">Stock Inicial (Opcional)</div>
                <div class="card-body">
                    <div id="stock-container">
                        <div class="stock-row mb-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <select name="initial_stock[0][store_id]" class="form-control">
                                        <option value="">Seleccionar almacén</option>
                                        @foreach($stores as $store)
                                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" step="0.0001" name="initial_stock[0][quantity]" 
                                           class="form-control" placeholder="Cantidad" min="0">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger remove-stock" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-stock" class="btn btn-sm btn-secondary mt-2">
                        <i class="fas fa-plus"></i> Agregar otro almacén
                    </button>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Manejo de stock dinámico
        let stockCount = 1;
        
        document.getElementById('add-stock').addEventListener('click', function() {
            const container = document.getElementById('stock-container');
            const newRow = document.createElement('div');
            newRow.className = 'stock-row mb-2';
            newRow.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <select name="initial_stock[${stockCount}][store_id]" class="form-control">
                            <option value="">Seleccionar almacén</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="number" step="0.0001" name="initial_stock[${stockCount}][quantity]" 
                            class="form-control" placeholder="Cantidad" min="0">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-stock">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(newRow);
            stockCount++;
        });
        
        document.getElementById('stock-container').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-stock')) {
                e.target.closest('.stock-row').remove();
            }
        });

        // Actualizar conversiones cuando cambian las unidades
        const purchaseUnitSelect = document.getElementById('purchase_unit_id');
        const consumptionUnitSelect = document.getElementById('consumption_unit_id');
        const conversionSelect = document.getElementById('unit_conversion_id');

        function updateConversions() {
            const purchaseUnitId = purchaseUnitSelect.value;
            const consumptionUnitId = consumptionUnitSelect.value;

            if (purchaseUnitId && consumptionUnitId) {
                fetch(`/products/get-conversions?purchase_unit_id=${purchaseUnitId}&consumption_unit_id=${consumptionUnitId}`)
                    .then(response => response.json())
                    .then(data => {
                        conversionSelect.innerHTML = '<option value="">Seleccionar conversión</option>';
                        data.forEach(conversion => {
                            const option = document.createElement('option');
                            option.value = conversion.id;
                            option.textContent = `1 ${conversion.from_unit.symbol} = ${conversion.factor} ${conversion.to_unit.symbol}`;
                            conversionSelect.appendChild(option);
                        });
                    });
            }
        }

        purchaseUnitSelect.addEventListener('change', updateConversions);
        consumptionUnitSelect.addEventListener('change', updateConversions);
    });
</script>
@endpush