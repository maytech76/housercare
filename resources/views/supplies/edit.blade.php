@extends('admin.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-4">
                <!-- ✅ CORREGIDO: Header con elementos alineados -->
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Edición de Suministros</h4>
                    <h3 class="mb-0 text-primary">N° {{ $supply->supply_number }}</h3>
                </div>
                
                     
                <div class="card-body">
                    <!-- Formulario tradicional Laravel -->
                    <form action="{{ route('supplies.update', $supply->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Selección de Caballo -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="horse_id">Caballo *</label>
                                <select name="horse_id" id="horse_id" class="form-control" required>
                                    <option value="">Seleccionar Caballo</option>
                                    @foreach($horses as $horse)
                                        <option value="{{ $horse->id }}" {{ $supply->horse_id == $horse->id ? 'selected' : '' }}>
                                            {{ $horse->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="supply_date">Fecha de Asignación</label>
                                <div class="form-control">
                                    {{ $supply->supply_date->format('Y-m-d') }}
                                </div>
                                <input type="hidden" name="supply_date" value="{{ $supply->supply_date->format('Y-m-d') }}">
                            </div>
                        </div>

                        <!-- Búsqueda de Productos -->
                        <div class="card p-2">
                            <div class="row mb-4">
                                <div class="card-body">
                                    <div class="row g-3 align-items-end">
                                        {{-- Select Store_id --}}
                                        <div class="col-md-2">
                                            <label for="store_id" class="form-label">Store *</label>
                                             <select name="store_id" id="store_id" class="form-control" required>
                                                <option value="">Select Store Please</option>
                                                @foreach ($stores as $store )
                                                <option value="{{$store->id}}" {{ $supply->store_id == $store->id ? 'selected' : '' }}>
                                                    {{$store->name}}
                                                </option>
                                                @endforeach
                                             </select>
                                        </div>

                                        {{-- Turno --}}
                                        <div class="col-md-1">
                                            <label for="shift" class="form-label">Turno</label>
                                             <select name="shift" id="shift" class="form-control">
                                                <option value="AM">AM</option>
                                                <option value="PM">PM</option>
                                             </select>
                                        </div>
                                        
                                         {{-- Productos --}}
                                        <div class="col-md-3">
                                            <label for="product_id" class="form-label required-field">Producto</label>
                                              <select name="product_id" id="product_id" class="form-select">
                                                <option value="">Seleccionar Producto</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" 
                                                            data-unit="{{ $product->consumptionUnit->name ?? 'N/A' }}">
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach
                                              </select>         
                                        </div>
                    
                                        {{-- Cantidad --}}
                                        <div class="col-md-1">
                                            <label for="quantity" class="form-label">Cantidad</label>
                                            <input type="text" name="quantity" id="quantity" class="form-control" min="0.01" step="0.01" value="1">
                                        </div>

                                        {{-- Unidad --}}
                                        <div class="col-md-2">
                                            <label for="unit" class="form-label">Unidad</label>
                                            <input type="text" name="unit_name" id="unit" class="form-control" disabled>
                                        </div>

                                        <div class="col-md-2">
                                            <label for="limit_date">Limit Date</label>
                                            <input type="date" name="limit_date" id="limit_date" 
                                                   class="form-control" value="">
                                        </div>
                                        
                                        {{-- Botón de acción --}}
                                        <div class="col-md-1">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" id="addProductBtn" class="btn btn-primary w-100">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tablas de Suministros AM y PM -->
                        <div class="row">
                            <!-- Tabla Turno AM -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header card-draggable bg-primary text-white">
                                        <h5>Suministros Turno AM</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered" id="amTable">
                                            <thead>
                                                <tr>
                                                    <th>Producto</th>
                                                    <th>Cantidad</th>
                                                    <th>Unidad</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody id="amSuppliesBody">
                                                <!-- Productos AM se cargarán aquí via JavaScript -->
                                            </tbody>
                                        </table>
                                        <div id="amSuppliesData">
                                            <!-- Se generarán inputs hidden aquí -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabla Turno PM -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header card-draggable bg-warning text-dark">
                                        <h5>Suministros Turno PM</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered" id="pmTable">
                                            <thead>
                                                <tr>
                                                    <th>Producto</th>
                                                    <th>Cantidad</th>
                                                    <th>Unidad</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody id="pmSuppliesBody">
                                                <!-- Productos PM se cargarán aquí via JavaScript -->
                                            </tbody>
                                        </table>
                                        <div id="pmSuppliesData">
                                            <!-- Se generarán inputs hidden aquí -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notas -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes">Observaciones</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Observaciones adicionales...">{{ $supply->notes ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3 text-center">
                            <div class="col-md-6">
                                <a href="{{route('supplies.index')}}" class="btn btn-danger btn-lg w-50">Cancelar</a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success btn-lg w-50">Actualizar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Variables globales
    let amSupplies = [];
    let pmSupplies = [];

    // ✅ DEBUG EXTENDIDO - Verificar que el DOM se carga
    console.log('🔄 Script cargado - DOMContentLoaded iniciando...');

    document.addEventListener('DOMContentLoaded', function() {
        console.log('✅ DOMContentLoaded EJECUTADO');
        console.log('=== INICIANDO CARGA DE PRODUCTOS ===');
        
        // Limpiar arrays
        amSupplies = [];
        pmSupplies = [];

        // ✅ VERIFICAR QUE HAY DATOS DE LARAVEL
        console.log('📦 Total de detalles desde Laravel: {{ $supply->supplyDetails->count() }}');

        // ✅ CARGAR DESDE LOS DATOS DE LARAVEL
        @foreach($supply->supplyDetails as $detail)
            @if($detail->product)
                console.log('📦 Procesando producto:', {
                    id: "{{ $detail->id }}",
                    product: "{{ $detail->product->name }}", 
                    shift: "{{ $detail->shift }}",
                    quantity: "{{ $detail->quantity }}"
                });

                const existingProduct = {
                    product_id: "{{ $detail->product_id }}",
                    product_name: "{{ $detail->product->name }}",
                    quantity: {{ $detail->quantity }},
                    unit: "{{ $detail->unit_name }}",
                    limit_date: "{{ $detail->limit_date ? $detail->limit_date->format('Y-m-d') : '' }}",
                    horse_id: "{{ $supply->horse_id }}",
                    horse_name: "{{ $supply->horse->name }}"
                };

                // ✅ AGREGAR AL ARRAY CORRESPONDIENTE
                if ("{{ $detail->shift }}" === 'AM') {
                    amSupplies.push(existingProduct);
                    console.log('✅ Agregado a AM supplies:', existingProduct);
                } else if ("{{ $detail->shift }}" === 'PM') {
                    pmSupplies.push(existingProduct);
                    console.log('✅ Agregado a PM supplies:', existingProduct);
                }
            @else
                console.warn('❌ Detalle sin producto:', "{{ $detail->id }}");
            @endif
        @endforeach

        console.log('📊 Resumen FINAL:');
        console.log('AM Supplies length:', amSupplies.length);
        console.log('PM Supplies length:', pmSupplies.length);
        console.log('AM Supplies:', amSupplies);
        console.log('PM Supplies:', pmSupplies);

        // ✅ VERIFICAR QUE LAS TABLAS EXISTEN
        const amTableBody = document.getElementById('amSuppliesBody');
        const pmTableBody = document.getElementById('pmSuppliesBody');
        
        console.log('🔍 Elemento amSuppliesBody encontrado:', !!amTableBody);
        console.log('🔍 Elemento pmSuppliesBody encontrado:', !!pmTableBody);

        // ✅ ACTUALIZAR TABLAS CON MÁS DEBUGGING
        if (amSupplies.length > 0) {
            console.log('🔄 Actualizando tabla AM con', amSupplies.length, 'productos');
            updateSuppliesTable('amSuppliesBody', amSupplies, 'AM');
            updateHiddenInputs('amSuppliesData', amSupplies, 'am');
            console.log('✅ Tabla AM actualizada');
        } else {
            console.log('ℹ️ No hay productos AM para mostrar');
            document.getElementById('amSuppliesBody').innerHTML = 
                '<tr><td colspan="4" class="text-center text-muted">No hay productos AM</td></tr>';
        }

        if (pmSupplies.length > 0) {
            console.log('🔄 Actualizando tabla PM con', pmSupplies.length, 'productos');
            updateSuppliesTable('pmSuppliesBody', pmSupplies, 'PM');
            updateHiddenInputs('pmSuppliesData', pmSupplies, 'pm');
            console.log('✅ Tabla PM actualizada');
        } else {
            console.log('ℹ️ No hay productos PM para mostrar');
            document.getElementById('pmSuppliesBody').innerHTML = 
                '<tr><td colspan="4" class="text-center text-muted">No hay productos PM</td></tr>';
        }

        console.log('🎉 Carga de productos completada');
    });

    // ✅ FUNCIÓN ACTUALIZAR TABLA CON MÁS DEBUG
    function updateSuppliesTable(tableBodyId, suppliesArray, shift) {
        console.log(`🔄 updateSuppliesTable llamado: ${tableBodyId}, ${suppliesArray.length} productos, turno: ${shift}`);
        
        const tableBody = document.getElementById(tableBodyId);
        if (!tableBody) {
            console.error(`❌ NO se encontró el elemento: ${tableBodyId}`);
            return;
        }

        tableBody.innerHTML = '';

        suppliesArray.forEach((supply, index) => {
            console.log(`📝 Creando fila para producto: ${supply.product_name}`);
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    ${supply.product_name}
                    <br><small class="text-muted">Caballo: ${supply.horse_name}</small>
                </td>
                <td>${supply.quantity}</td>
                <td>${supply.unit}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger remove-product" 
                            data-shift="${shift}" data-index="${index}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tableBody.appendChild(row);
        });

        console.log(`✅ Tabla ${tableBodyId} actualizada con ${suppliesArray.length} filas`);

        // Agregar event listeners para eliminar
        document.querySelectorAll('.remove-product').forEach(button => {
            button.addEventListener('click', function() {
                const shift = this.getAttribute('data-shift');
                const index = parseInt(this.getAttribute('data-index'));
                console.log(`🗑️ Eliminando producto: turno ${shift}, índice ${index}`);
                removeProduct(shift, index);
            });
        });
    }

    // ✅ FUNCIÓN ACTUALIZAR INPUTS HIDDEN
    function updateHiddenInputs(containerId, suppliesArray, prefix) {
        console.log(`🔄 updateHiddenInputs llamado: ${containerId}, ${suppliesArray.length} productos`);
        
        const container = document.getElementById(containerId);
        if (!container) {
            console.error(`❌ NO se encontró el elemento: ${containerId}`);
            return;
        }

        container.innerHTML = '';

        suppliesArray.forEach((supply, index) => {
            // Crear inputs hidden para cada campo
            const productInput = document.createElement('input');
            productInput.type = 'hidden';
            productInput.name = `${prefix}_supplies[${index}][product_id]`;
            productInput.value = supply.product_id;
            container.appendChild(productInput);

            const quantityInput = document.createElement('input');
            quantityInput.type = 'hidden';
            quantityInput.name = `${prefix}_supplies[${index}][quantity]`;
            quantityInput.value = supply.quantity;
            container.appendChild(quantityInput);

            const unitInput = document.createElement('input');
            unitInput.type = 'hidden';
            unitInput.name = `${prefix}_supplies[${index}][unit_name]`;
            unitInput.value = supply.unit;
            container.appendChild(unitInput);

            if (supply.limit_date) {
                const limitDateInput = document.createElement('input');
                limitDateInput.type = 'hidden';
                limitDateInput.name = `${prefix}_supplies[${index}][limit_date]`;
                limitDateInput.value = supply.limit_date;
                container.appendChild(limitDateInput);
            }
        });

        console.log(`✅ Inputs hidden ${containerId} actualizados con ${suppliesArray.length} productos`);
    }

    // ✅ FUNCIÓN ELIMINAR PRODUCTO
    function removeProduct(shift, index) {
        console.log(`🗑️ removeProduct llamado: turno ${shift}, índice ${index}`);
        
        if (shift === 'AM') {
            amSupplies.splice(index, 1);
            updateSuppliesTable('amSuppliesBody', amSupplies, 'AM');
            updateHiddenInputs('amSuppliesData', amSupplies, 'am');
        } else {
            pmSupplies.splice(index, 1);
            updateSuppliesTable('pmSuppliesBody', pmSupplies, 'PM');
            updateHiddenInputs('pmSuppliesData', pmSupplies, 'pm');
        }
    }

    // Mostrar unidad cuando se selecciona un producto
    document.getElementById('product_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const unit = selectedOption.getAttribute('data-unit');
        document.getElementById('unit').value = unit || 'N/A';
    });

    // Agregar producto a la tabla correspondiente
    document.getElementById('addProductBtn').addEventListener('click', function() {
        console.log('➕ Botón agregar producto clickeado');
        
        const productSelect = document.getElementById('product_id');
        const quantityInput = document.getElementById('quantity');
        const shiftSelect = document.getElementById('shift');
        const limitDateInput = document.getElementById('limit_date');
        const storeSelect = document.getElementById('store_id');
        const horseSelect = document.getElementById('horse_id');
        
        const productId = productSelect.value;
        const productName = productSelect.options[productSelect.selectedIndex].text;
        const quantity = parseFloat(quantityInput.value);
        const unit = document.getElementById('unit').value;
        const shift = shiftSelect.value;
        const limitDate = limitDateInput.value;
        const storeId = storeSelect.value;
        const horseId = horseSelect.value;
        const horseName = horseSelect.options[horseSelect.selectedIndex].text;

        // Validaciones
        if (!storeId) {
            alert('Por favor seleccione un almacén');
            return;
        }

        if (!horseId) {
            alert('Por favor seleccione un caballo');
            return;
        }

        if (!productId) {
            alert('Por favor seleccione un producto');
            return;
        }

        if (!quantity || quantity <= 0) {
            alert('Por favor ingrese una cantidad válida');
            return;
        }

        // Crear objeto del producto
        const product = {
            product_id: productId,
            product_name: productName,
            quantity: quantity,
            unit: unit,
            limit_date: limitDate || null,
            horse_id: horseId,
            horse_name: horseName
        };

        console.log('🆕 Nuevo producto a agregar:', product);

        // Agregar al array correspondiente
        if (shift === 'AM') {
            amSupplies.push(product);
            updateSuppliesTable('amSuppliesBody', amSupplies, 'AM');
            updateHiddenInputs('amSuppliesData', amSupplies, 'am');
        } else {
            pmSupplies.push(product);
            updateSuppliesTable('pmSuppliesBody', pmSupplies, 'PM');
            updateHiddenInputs('pmSuppliesData', pmSupplies, 'pm');
        }

        // Limpiar campos
        quantityInput.value = '1';
        limitDateInput.value = '';
        productSelect.value = '';
        document.getElementById('unit').value = '';
    });

    // Validación antes del envío del formulario
    document.querySelector('form').addEventListener('submit', function(e) {
        console.log('📤 Formulario enviado');
        
        const storeSelect = document.getElementById('store_id');
        const horseSelect = document.getElementById('horse_id');

        // Validaciones adicionales
        if (!storeSelect.value) {
            alert('Por favor seleccione un almacén');
            e.preventDefault();
            return;
        }

        if (!horseSelect.value) {
            alert('Por favor seleccione un caballo');
            e.preventDefault();
            return;
        }

        // Validar que haya al menos un suministro
        if (amSupplies.length === 0 && pmSupplies.length === 0) {
            alert('Debe agregar al menos un producto a algún turno');
            e.preventDefault();
            return;
        }
    });

    // ✅ VERIFICAR QUE EL SCRIPT SE CARGÓ
    console.log('🎯 Script de supplies/edit.blade.php cargado completamente');
</script>

<!-- Script separado para deshabilitar campos -->
<script>
    // Deshabilitar campos una vez seleccionados (ya vienen precargados)
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🔒 Script de deshabilitar campos ejecutado');
        
        const horseSelect = document.getElementById('horse_id');
        const storeSelect = document.getElementById('store_id');
        
        // MARCAR COMO SELECCIONADOS DESDE EL INICIO
        if (horseSelect.value) {
            horseSelect.style.backgroundColor = '#ccc';
            horseSelect.style.cursor = 'not-allowed';
            console.log('🐎 Campo horse_id deshabilitado');
        }

        if (storeSelect.value) {
            storeSelect.style.backgroundColor = '#ccc';
            storeSelect.style.cursor = 'not-allowed';
            console.log('🏪 Campo store_id deshabilitado');
        }

        let horseSelected = !!horseSelect.value;
        let storeSelected = !!storeSelect.value;
    });
</script>
@endpush