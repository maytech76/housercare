@extends('admin.layouts.master')

@section('content')
<div class="main-container container-fluid">

     <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <h4 class="page-title">Administration</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Inventory Movement</a></li>
                <li class="breadcrumb-item active" aria-current="page">Recording movement</li>
            </ol>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center">
            <div class="mb-xl-0">
                <div class="dropdown">
                   {{--  <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuDate" data-bs-toggle="dropdown" aria-expanded="false"> --}}
                    <a href="{{ route('inventory-controls.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Return
                    </a>
                    {{-- </button> --}}
                </div>
            </div>
        </div>
    </div><!-- breadcrumb -->

    <div class="card">
        <div class="card-header bg-primary-gradient text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Inventory operation</h4>
            <h4 class="mb-0">{{ $nextOperationNumber }}</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('inventory-controls.store') }}" method="POST" id="movementForm">
                @csrf

                <!-- Campo hidden para el número de operación -->
                <input type="hidden" name="operation_number" value="{{ $nextOperationNumber }}">

                <!-- Primera Fila: Producto, Almacén Origen, Almacén Destino -->
                <div class="row mb-4">

                    {{-- Select products --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="product_id" class="form-label">Product *</label>
                            <select name="product_id" id="product_id" class="form-control border border-secondary select2">
                                <option value="">Select product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                            data-purchase-unit-name="{{ $product->purchaseUnit->name }}"
                                            data-purchase-unit-symbol="{{ $product->purchaseUnit->symbol }}">
                                        {{ $product->name }} ({{ $product->purchaseUnit->symbol }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    {{-- Origin Store --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="store_id" class="form-label">Origin Store *</label>
                            <select name="store_id" id="store_id" class="form-control border border-secondary">
                                <option value="">Select Store</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Stock Actual --}}
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="current_stock" class="form-label">Current Amount</label>
                            <input type="text" id="current_stock" class="form-control" disabled>
                            <small class="text-muted">amount in deposit</small>
                        </div>
                    </div>

                    {{-- Destination Store --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="destination_store_id" class="form-label">Destination Store</label>
                            <select name="destination_store_id" id="destination_store_id" class="form-control" disabled>
                                <option value="">Select Destination Store</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger">For transfers only</small>
                        </div>
                    </div>

                </div>

                <!-- Segunda Fila: Tipo, Cantidad, Unidad, Ubicación -->
                <div class="row mb-4">

                    {{-- Type of Movement --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="movement_type" class="form-label">Type of Movement *</label>
                            <select name="movement_type" id="movement_type" class="form-control border border-secondary">
                                <option value="">Select type</option>
                                <option value="entry">Entry</option>
                                <option value="exit">Exit</option>
                                <option value="adjustment">Ajustmant</option>
                                <option value="loss">Loos</option>
                                <option value="damage">Damage</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>
                    </div>

                    {{-- Amount --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="quantity" class="form-label">Amount *</label>
                            <input type="text" name="quantity" id="quantity" class="form-control border border-secondary" 
                                   placeholder="0.00" pattern="^\d+(\.\d{1,2})?$">
                            <small class="text-danger">Only positive numbers with 2 decimal places</small>
                        </div>
                    </div>

                    {{-- Unit --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="unit_type" class="form-label">Unit *</label>
                            <input type="text" name="unit_type" id="unit_type" class="form-control" readonly>
                            <small class="text-muted">Product purchasing unit</small>
                        </div>
                    </div>

                    {{-- Ubication --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ubication" class="form-label">Location</label>
                            <input type="text" name="ubication" id="ubication" class="form-control border border-secondary" 
                                   placeholder="Ej: Estante A, Nivel 3">
                        </div>
                    </div>

                </div>

                <!-- Tercera Fila: Razón y Notas -->
                <div class="row mb-4 align-items-end">

                    {{-- Reason --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="reason" class="form-label">Reason *</label>
                            <input type="text" name="reason" id="reason" class="form-control border border-secondary" 
                                   placeholder="Ej: Compra, Venta, Ajuste, etc.">
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" class="form-control border border-secondary" rows="1" 
                                      placeholder="Información adicional del movimiento"></textarea>
                        </div>
                    </div>
                   
                    {{-- Boton Add --}}
                    <div class="col-md-4">
                        <div class="form-group d-flex align-items-end h-100">
                            <button type="button" id="add-to-table" class="btn btn-success w-100">
                                <i class="fas fa-plus-circle"></i> Add Product
                            </button>
                        </div>
                    </div>
                </div>

                

                <!-- Tabla de productos agregados -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h4>Products in Motion</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="products-table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Product</th>
                                        <th>Origin Store</th>
                                        <th>Movement Type</th>
                                        <th>Amount</th>
                                        <th>Unit</th>
                                        <th>Destination Store</th>
                                        <th>Location</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Los productos se agregarán aquí dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Botton --}}
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary" id="submit-btn" disabled>
                        <i class="fas fa-save"></i> Register 
                    </button>
                    <a href="{{ route('inventory-controls.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
                
            </form>
        </div>

    </div>

</div>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container .select2-selection--single { height: 38px; }
.select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 36px; }
.select2-container--default .select2-selection--single .select2-selection__arrow { height: 36px; }
#products-table th { background-color: #343a40; color: white; }
.btn-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar Select2
        $('.select2').select2();

        const movementTypeSelect = document.getElementById('movement_type');
        const destinationStoreSelect = document.getElementById('destination_store_id');
        const productSelect = document.getElementById('product_id');
        const storeSelect = document.getElementById('store_id');
        const unitInput = document.getElementById('unit_type');
        const quantityInput = document.getElementById('quantity');
        const reasonInput = document.getElementById('reason');
        const addButton = document.getElementById('add-to-table');
        const productsTable = document.getElementById('products-table').getElementsByTagName('tbody')[0];
        const submitButton = document.getElementById('submit-btn');
        const form = document.getElementById('movementForm');
        const ubicationInput = document.getElementById('ubication');
        const notesInput = document.getElementById('notes');
        const currentStockInput = document.getElementById('current_stock');

        let productsList = [];
        let currentStock = 0;

        // Función para obtener el stock del producto
        function fetchProductStock(productId, storeId) {
            if (!productId || !storeId) {
                currentStockInput.value = '';
                return;
            }

            fetch(`/api/product-stock/${productId}/${storeId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.stock) {
                        currentStock = data.stock.purchase_quantity || 0;
                        currentStockInput.value = currentStock;
                    } else {
                        currentStockInput.value = '0';
                        currentStock = 0;
                    }
                })
                .catch(error => {
                    console.error('Error fetching stock:', error);
                    currentStockInput.value = 'Error';
                    currentStock = 0;
                });
        }

        // Mostrar/ocultar y habilitar/deshabilitar almacén destino
        function toggleDestinationStore() {
            if (movementTypeSelect.value === 'transfer') {
                destinationStoreSelect.disabled = false;
            } else {
                destinationStoreSelect.disabled = true;
                destinationStoreSelect.value = '';
            }
        }

        // Actualizar unidad cuando se selecciona un producto
        function updateUnit() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            if (selectedOption && selectedOption.value !== '') {
                const unitName = selectedOption.getAttribute('data-purchase-unit-name');
                unitInput.value = unitName;
                
                // Actualizar stock si ya hay un almacén seleccionado
                if (storeSelect.value) {
                    fetchProductStock(selectedOption.value, storeSelect.value);
                }
            } else {
                unitInput.value = '';
                currentStockInput.value = '';
            }
        }

        // Validar cantidad (solo números positivos con 2 decimales)
        function validateQuantity(input) {
            const value = input.value;
            const regex = /^\d+(\.\d{1,2})?$/;
            
            if (!regex.test(value) || parseFloat(value) <= 0) {
                input.setCustomValidity('Ingrese un número positivo con hasta 2 decimales');
                return false;
            } else {
                input.setCustomValidity('');
                return true;
            }
        }



        // Validar formulario antes de agregar producto
        function validateFormForAdding() {
            const productId = productSelect.value;
            const storeId = storeSelect.value;
            const movementType = movementTypeSelect.value;
            const quantity = quantityInput.value;
            const reason = reasonInput.value;

            let isValid = true;

            if (!productId) {
                alert('Por favor seleccione un producto');
                productSelect.focus();
                isValid = false;
            }
            else if (!storeId) {
                alert('Por favor seleccione un almacén origen');
                storeSelect.focus();
                isValid = false;
            }
            else if (!movementType) {
                alert('Por favor seleccione un tipo de movimiento');
                movementTypeSelect.focus();
                isValid = false;
            }
            else if (!quantity || !validateQuantity(quantityInput)) {
                alert('Por favor ingrese una cantidad válida');
                quantityInput.focus();
                isValid = false;
            }
            else if (!reason) {
                alert('Por favor ingrese una razón/motivo');
                reasonInput.focus();
                isValid = false;
            }
            else if (movementType === 'transfer' && !destinationStoreSelect.value) {
                alert('Para transferencias debe seleccionar un almacén destino');
                destinationStoreSelect.focus();
                isValid = false;
            }
            // Validar stock para salidas
            else if (movementType === 'exit' && parseFloat(quantity) > currentStock) {
                alert(`No hay suficiente stock. Stock disponible: ${currentStock}`);
                quantityInput.focus();
                isValid = false;
            }

            // Validar que no sea transferencia al mismo almacén
            else if (movementType === 'transfer' && storeSelect.value === destinationStoreSelect.value) {
                alert('No puede transferir al mismo almacén de origen');
                destinationStoreSelect.focus();
                isValid = false;
            }

            return isValid;
        }

        // Agregar producto a la tabla
        function addProductToTable() {
            if (!validateFormForAdding()) {
                return;
            }

            const productId = productSelect.value;
            const productName = productSelect.options[productSelect.selectedIndex].text;
            const storeId = storeSelect.value;
            const storeName = storeSelect.options[storeSelect.selectedIndex].text;
            const movementType = movementTypeSelect.value;
            const movementTypeText = movementTypeSelect.options[movementTypeSelect.selectedIndex].text;
            const quantity = quantityInput.value;
            const unit = unitInput.value;
            const destinationStoreId = destinationStoreSelect.value;
            const destinationStoreName = destinationStoreSelect.options[destinationStoreSelect.selectedIndex]?.text || '';
            const ubication = ubicationInput.value;
            const reason = reasonInput.value;
            const notes = notesInput.value;

            const productData = {
                product_id: productId,
                product_name: productName,
                store_id: storeId,
                store_name: storeName,
                movement_type: movementType,
                movement_type_text: movementTypeText,
                quantity: quantity,
                unit_type: 'purchase',
                destination_store_id: destinationStoreId || null,
                destination_store_name: destinationStoreName,
                ubication: ubication,
                reason: reason,
                notes: notes
            };

            productsList.push(productData);
            updateProductsTable();
            clearForm();
            updateSubmitButton();
        }

        // Actualizar tabla de productos
        function updateProductsTable() {
            productsTable.innerHTML = '';
            
            productsList.forEach((product, index) => {
                const row = productsTable.insertRow();
                
                row.innerHTML = `
                    <td>${product.product_name}</td>
                    <td>${product.store_name}</td>
                    <td>${product.movement_type_text}</td>
                    <td>${product.quantity}</td>
                    <td>${product.unit_type}</td>
                    <td>${product.destination_store_name || 'N/A'}</td>
                    <td>${product.ubication || 'N/A'}</td>
                    <td>
                        <button type="button" class="btn btn-info btn-sm view-product" data-index="${index}">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-warning btn-sm edit-product" data-index="${index}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm delete-product" data-index="${index}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
            });

            // Agregar event listeners a los botones
            document.querySelectorAll('.view-product').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = this.getAttribute('data-index');
                    viewProduct(index);
                });
            });

            document.querySelectorAll('.edit-product').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = this.getAttribute('data-index');
                    editProduct(index);
                });
            });

            document.querySelectorAll('.delete-product').forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = this.getAttribute('data-index');
                    deleteProduct(index);
                });
            });
        }

        // Limpiar formulario
        function clearForm() {
            productSelect.value = '';
            $('.select2').trigger('change');
            quantityInput.value = '';
            unitInput.value = '';
            ubicationInput.value = '';
            currentStockInput.value = '';
            toggleDestinationStore();
        }

        // Habilitar/deshabilitar botón de enviar
        function updateSubmitButton() {
            submitButton.disabled = productsList.length === 0;
        }

        // Ver producto
        function viewProduct(index) {
            const product = productsList[index];
            alert(`Información del producto:\n\nProducto: ${product.product_name}\nCantidad: ${product.quantity}\nMovimiento: ${product.movement_type_text}\nUbicación: ${product.ubication || 'N/A'}`);
        }

        // Editar producto
        function editProduct(index) {
            const product = productsList[index];
            
            // Llenar el formulario con los datos del producto
            productSelect.value = product.product_id;
            $('.select2').trigger('change');
            storeSelect.value = product.store_id;
            movementTypeSelect.value = product.movement_type;
            quantityInput.value = product.quantity;
            unitInput.value = product.unit_type;
            destinationStoreSelect.value = product.destination_store_id || '';
            ubicationInput.value = product.ubication || '';
            reasonInput.value = product.reason;
            notesInput.value = product.notes || '';
            
            // Actualizar información de stock
            fetchProductStock(product.product_id, product.store_id);
            
            toggleDestinationStore();
            
            // Eliminar el producto de la lista
            productsList.splice(index, 1);
            updateProductsTable();
            updateSubmitButton();
        }

        // Eliminar producto
        function deleteProduct(index) {
            if (confirm('¿Está seguro de eliminar este producto de la lista?')) {
                productsList.splice(index, 1);
                updateProductsTable();
                updateSubmitButton();
            }
        }

        // Prevenir envío del formulario normal y enviar datos personalizados
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (productsList.length === 0) {
                alert('Debe agregar al menos un producto al movimiento');
                return;
            }

            // Crear inputs hidden para cada producto con solo los campos que espera el controlador
            productsList.forEach((product, index) => {
                // Campos que espera el controlador
                const fieldsToSend = [
                    'product_id', 'store_id', 'movement_type', 'quantity', 
                    'unit_type', 'reason', 'notes', 'ubication', 'destination_store_id'
                ];
                
                fieldsToSend.forEach(field => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `products[${index}][${field}]`;
                    
                    // Asegurarse de que los campos opcionales tengan valores nulos si están vacíos
                    if (field === 'destination_store_id' && !product[field]) {
                        input.value = '';
                    } else {
                        input.value = product[field] || '';
                    }
                    
                    form.appendChild(input);
                });
            });

            // Enviar formulario
            this.submit();
        });

        // Event Listeners
        movementTypeSelect.addEventListener('change', toggleDestinationStore);
        
        // Event listener para el cambio de producto
        $(productSelect).on('change', updateUnit);
        
        // Event listener para el cambio de almacén
        storeSelect.addEventListener('change', function() {
            if (productSelect.value) {
                fetchProductStock(productSelect.value, this.value);
            }
        });
        
        quantityInput.addEventListener('input', function() {
            validateQuantity(this);
        });
        
        addButton.addEventListener('click', addProductToTable);

        // Inicializar
        toggleDestinationStore();
        updateUnit();
    });
</script>
@endpush