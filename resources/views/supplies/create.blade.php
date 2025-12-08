@extends('admin.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-4">
                <div class="card-header">
                    <h4>Asignación de Suministros</h4>
                </div>
                <div class="card-body">
                    <!-- Formulario tradicional Laravel -->
                    <form action="{{ route('supplies.store') }}" method="POST">
                        @csrf
                        
                        <!-- Selección de Caballo -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="horse_id">Caballo *</label>
                                <select name="horse_id" id="horse_id" class="form-control" required>
                                    <option value="">Seleccionar Caballo</option>
                                    @foreach($horses as $horse)
                                        <option value="{{ $horse->id }}">{{ $horse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="supply_date">Fecha de Asignación</label>
                                <div class="form-control">
                                    {{ date('Y-m-d') }}
                                </div>
                                <input type="hidden" name="supply_date" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <!-- Búsqueda de Productos -->
                        <div class="card p-2">
                            <div class="row mb-4">
                                <div class="card-body">
                                    <!-- Fila para alinear horizontalmente -->
                                    <div class="row g-3 align-items-end">

                                        {{-- Select Store_id --}}
                                        <div class="col-md-2">
                                            <label for="store_id" class="form-label">Store *</label>
                                             <select name="store_id" id="store_id" class="form-control" required>
                                                <option value="">Select Store Please</option>
                                                @foreach ($stores as $store )
                                                <option value="{{$store->id}}">{{$store->name}}</option>
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

                                        {{-- Limit_Date --}}
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
                                                <!-- Productos AM se agregarán aquí -->
                                            </tbody>
                                        </table>
                                        <!-- Inputs hidden para datos AM -->
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
                                                <!-- Productos PM se agregarán aquí -->
                                            </tbody>
                                        </table>
                                        <!-- Inputs hidden para datos PM -->
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
                                    <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Observaciones adicionales..."></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Botones --}}
                        <div class="row mt-3 text-center">
                            <div class="col-md-6">
                                <a href="{{route('supplies.index')}}" class="btn btn-danger btn-lg w-50">Listado</a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success btn-lg w-50">Registrar</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Variables globales
    let amSupplies = [];
    let pmSupplies = [];

    // Mostrar unidad cuando se selecciona un producto
    document.getElementById('product_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const unit = selectedOption.getAttribute('data-unit');
        document.getElementById('unit').value = unit || 'N/A';
    });

    // Agregar producto a la tabla correspondiente
    document.getElementById('addProductBtn').addEventListener('click', function() {
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

        // 🔥 VALIDACIONES CON SWEETALERT2
        if (!storeId) {
            Swal.fire({
                title: 'Almacén Requerido',
                text: 'Por favor seleccione un almacén',
                icon: 'warning',
                timer: 2000,
                showConfirmButton: false,
                width: '50%'
            });
            return;
        }

        if (!horseId) {
            Swal.fire({
                title: 'Caballo Requerido',
                text: 'Por favor seleccione un caballo',
                icon: 'warning',
                timer: 2000,
                showConfirmButton: false,
                width: '50%'
            });
            return;
        }

        if (!productId) {
            Swal.fire({
                title: 'Producto Requerido',
                text: 'Por favor seleccione un producto',
                icon: 'warning',
                timer: 2000,
                showConfirmButton: false,
                width: '50%'
            });
            return;
        }

        if (!quantity || quantity <= 0) {
            Swal.fire({
                title: 'Cantidad Inválida',
                text: 'Por favor ingrese una cantidad válida mayor a cero',
                icon: 'warning',
                timer: 2500,
                showConfirmButton: false,
                width: '50%'
            });
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

        // 🔥 LIMPIAR CAMPOS DESPUÉS DE AGREGAR
        cleanInputFields();

        // 🔥 NOTIFICACIÓN DE PRODUCTO AGREGADO
        Swal.fire({
            
            position: "top-end",
            title: 'Producto Agregado',
            text: `${productName} agregado al turno ${shift}`,
            icon: 'success',
            showConfirmButton: false,          
            timer: 1500,
            
        });
    });

    // 🔥 NUEVA FUNCIÓN: Limpiar campos del formulario
    function cleanInputFields() {
        // Limpiar campos principales
        document.getElementById('quantity').value = '1';
        document.getElementById('product_id').value = '';
        document.getElementById('unit').value = '';
        
        // 🔥 LIMPIAR ESPECÍFICAMENTE EL CAMPO limit_date
        document.getElementById('limit_date').value = '';
        
        console.log('✅ Campos limpiados, incluyendo limit_date');
    }

    // Actualizar tabla de suministros
    function updateSuppliesTable(tableBodyId, suppliesArray, shift) {
        const tableBody = document.getElementById(tableBodyId);
        tableBody.innerHTML = '';

        suppliesArray.forEach((supply, index) => {
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

        // Agregar event listeners para eliminar
        document.querySelectorAll('.remove-product').forEach(button => {
            button.addEventListener('click', function() {
                const shift = this.getAttribute('data-shift');
                const index = parseInt(this.getAttribute('data-index'));
                removeProduct(shift, index);
            });
        });
    }

    // 🛠️ **FUNCIÓN CORREGIDA - AQUÍ ESTABA EL ERROR PRINCIPAL**
    // Actualizar inputs hidden para formulario tradicional
    function updateHiddenInputs(containerId, suppliesArray, prefix) {
        const container = document.getElementById(containerId);
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

            // ✅ **CAMPO AGREGADO: unit_name - ESTE ERA EL PROBLEMA**
            const unitInput = document.createElement('input');
            unitInput.type = 'hidden';
            unitInput.name = `${prefix}_supplies[${index}][unit_name]`;
            unitInput.value = supply.unit; // Usamos supply.unit que viene del objeto
            container.appendChild(unitInput);

            if (supply.limit_date) {
                const limitDateInput = document.createElement('input');
                limitDateInput.type = 'hidden';
                limitDateInput.name = `${prefix}_supplies[${index}][limit_date]`;
                limitDateInput.value = supply.limit_date;
                container.appendChild(limitDateInput);
            }
        });
    }

    // Eliminar producto de la lista - CON SWEETALERT2
    function removeProduct(shift, index) {
        Swal.fire({
            title: '¿Eliminar Producto?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            width: '50%',
            backdrop: true
        }).then((result) => {
            if (result.isConfirmed) {
                if (shift === 'AM') {
                    amSupplies.splice(index, 1);
                    updateSuppliesTable('amSuppliesBody', amSupplies, 'AM');
                    updateHiddenInputs('amSuppliesData', amSupplies, 'am');
                } else {
                    pmSupplies.splice(index, 1);
                    updateSuppliesTable('pmSuppliesBody', pmSupplies, 'PM');
                    updateHiddenInputs('pmSuppliesData', pmSupplies, 'pm');
                }
                
                Swal.fire({
                    title: 'Eliminado',
                    text: 'Producto eliminado correctamente',
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar',
                    timer: 1500,
                    width: '50%'
                });
            }
        });
    }

    // 🔥 VALIDACIÓN MEJORADA CON SWEETALERT2 ANTES DEL ENVÍO
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevenir envío inmediato
        
        const storeSelect = document.getElementById('store_id');
        const horseSelect = document.getElementById('horse_id');

        // Validaciones adicionales
        if (!storeSelect.value) {
            Swal.fire({
                title: 'Almacén Requerido',
                text: 'Por favor seleccione un almacén',
                icon: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar',
                width: '50%'
            });
            return;
        }

        if (!horseSelect.value) {
            Swal.fire({
                title: 'Caballo Requerido',
                text: 'Por favor seleccione un caballo',
                icon: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar',
                width: '50%'
            });
            return;
        }

        // Validar que haya al menos un suministro
        if (amSupplies.length === 0 && pmSupplies.length === 0) {
            Swal.fire({
                title: 'Productos Requeridos',
                text: 'Debe agregar al menos un producto a algún turno',
                icon: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar',
                width: '50%'
            });
            return;
        }

        // 🔥 CONFIRMACIÓN ELEGANTE ANTES DE REGISTRAR
        Swal.fire({
            title: '¿Registrar Suministro?',
            html: `
                <div class="text-start">
                    <p><strong>Resumen del suministro:</strong></p>
                    <p>📦 Productos AM: <strong>${amSupplies.length}</strong></p>
                    <p>📦 Productos PM: <strong>${pmSupplies.length}</strong></p>
                    <p>🐎 Caballo: <strong>${horseSelect.options[horseSelect.selectedIndex].text}</strong></p>
                    <p>🏪 Almacén: <strong>${storeSelect.options[storeSelect.selectedIndex].text}</strong></p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, registrar',
            cancelButtonText: 'Revisar',
            width: '50%',
            backdrop: true,
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                // 🔥 MOSTRAR LOADING DURANTE EL REGISTRO
                Swal.fire({
                    title: 'Registrando Suministro...',
                    text: 'Por favor espere un momento',
                    icon: 'info',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // 🔥 ENVIAR FORMULARIO DESPUÉS DE CONFIRMACIÓN
                const form = e.target;
                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // 🔥 NOTIFICACIÓN DE ÉXITO ELEGANTE
                        Swal.fire({
                            title: '¡Suministro Registrado!',
                            html: `
                                <div class="text-center">
                                    <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                                    <p class="h5">${data.message || 'Suministro registrado exitosamente'}</p>
                                    <div class="mt-3 p-2 bg-light rounded">
                                        <small class="text-muted">
                                            <strong>Número:</strong> ${data.supply_number || 'N/A'}<br>
                                            <strong>Productos:</strong> ${amSupplies.length + pmSupplies.length} total
                                        </small>
                                    </div>
                                </div>
                            `,
                            icon: 'success',
                            confirmButtonColor: '#28a745',
                            confirmButtonText: 'Ver Listado',
                            showCancelButton: true,
                            cancelButtonText: 'Agregar Otro',
                            width: '50%',
                            timer: 5000,
                            timerProgressBar: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "{{ route('supplies.index') }}";
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                // Recargar página para nuevo suministro
                                window.location.reload();
                            } else {
                                // Si se acaba el timer, redirigir al listado
                                window.location.href = "{{ route('supplies.index') }}";
                            }
                        });
                    } else {
                        throw new Error(data.message || 'Error al registrar el suministro');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error',
                        text: error.message || 'Ocurrió un error al registrar el suministro',
                        icon: 'error',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Aceptar',
                        width: '50%'
                    });
                });
            }
        });
    });

    // Inicializar unidades al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('product_id');
        if (productSelect.value) {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const unit = selectedOption.getAttribute('data-unit');
            document.getElementById('unit').value = unit || 'N/A';
        }
    });
</script>

<!-- Script separado para deshabilitar campos -->
<script>
    // Deshabilitar campos una vez seleccionados
    document.addEventListener('DOMContentLoaded', function() {
        const horseSelect = document.getElementById('horse_id');
        const storeSelect = document.getElementById('store_id');
        
        let horseSelected = false;
        let storeSelected = false;

        // Controlar selección de caballo
        horseSelect.addEventListener('change', function() {
            if (this.value && !horseSelected) {
                horseSelected = true;
                this.readOnly = true;
                // Cambiar estilo visual
                this.style.backgroundColor = '#ccc';
                this.style.cursor = 'not-allowed';
                
                // Mostrar mensaje informativo
                const horseInfo = document.createElement('small');
                horseInfo.className = 'form-text text-success';
                horseInfo.textContent = 'Caballo seleccionado';
                this.parentNode.appendChild(horseInfo);
            }
        });

        // Controlar selección de almacén
        storeSelect.addEventListener('change', function() {
            if (this.value && !storeSelected) {
                storeSelected = true;
                this.readOnly = true;
                // Cambiar estilo visual
                this.style.backgroundColor = '#ccc';
                this.style.cursor = 'not-allowed';
                
                // Mostrar mensaje informativo
                const storeInfo = document.createElement('small');
                storeInfo.className = '';
                storeInfo.textContent = '';
                this.parentNode.appendChild(storeInfo);
            }
        });

        // Permitir reset si no hay productos agregados
        document.getElementById('addProductBtn').addEventListener('click', function() {
            // Si no hay productos agregados, permitir cambiar caballo y almacén
            if (amSupplies.length === 0 && pmSupplies.length === 0) {
                if (horseSelected) {
                    horseSelect.disabled = false;
                    horseSelected = false;
                    horseSelect.style.backgroundColor = '';
                    horseSelect.style.cursor = '';
                    // Remover mensaje informativo
                    const horseInfo = horseSelect.parentNode.querySelector('.form-text.text-success');
                    if (horseInfo) horseInfo.remove();
                }
                
                if (storeSelected) {
                    storeSelect.disabled = false;
                    storeSelected = false;
                    storeSelect.style.backgroundColor = '';
                    storeSelect.style.cursor = '';
                    // Remover mensaje informativo
                    const storeInfo = storeSelect.parentNode.querySelector('.form-text.text-success');
                    if (storeInfo) storeInfo.remove();
                }
            }
        });
    });
</script>
@endpush