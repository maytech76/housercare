@extends('admin.layouts.master')

@section('content')

<section>

    {{-- Encabezado / Tabla --}}
    <div class="main-container container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Gestión de Suministros</h4>

                        <button class="btn btn-warning" type="button" id="resetSuppliesBtn" title="Resetear suministros ejecutados de días anteriores" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-sync-alt me-1"></i> Reset Supplies
                        </button>

                        <a href="{{ route('supplies.create') }}" class="btn btn-primary float-right">
                            <i class="fas fa-plus"></i> Nuevo Suministro
                        </a>
                    </div>
                    <div class="card-body">
                        <!-- Tabla de suministros -->
                        <div class="table-responsive">
                            <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="responsive-datatable">
                                <thead>
                                    <tr>
                                        <th class="border-bottom-0 bg-light">Date</th>
                                        <th class="border-bottom-0 bg-light">Sup Number</th>
                                        <th class="border-bottom-0 bg-light">Store</th>
                                        <th class="border-bottom-0 bg-light">Horse</th>
                                        <th class="border-bottom-0 bg-light">Asigned</th>
                                        <th class="border-bottom-0 bg-light">Estatus</th>
                                        <th class="border-bottom-0 bg-light">Excecuted</th>
                                        <th class="border-bottom-0 bg-light">Accions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($supplies as $supply)
                                        <tr>
                                            <td>{{ $supply->supply_date->format('d/m/Y') }}</td>
                                            <td>{{ $supply->supply_number }}</td>
                                            <td>{{ $supply->store->name }}</td>
                                            <td>{{ $supply->horse->name }}</td>
                                            <td>{{ $supply->assignedBy->name }}</td>
                                            <td>
                                                <span class="text-{{ $supply->status == 'ASSIGNED' ? 'warning' : 'success' }}">
                                                    {{ $supply->status }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($supply->executedBy)

                                                    <span class="text-success">
                                                        <strong>{{ $supply->executedBy->name }}</strong>
                                                    </span>
                                                    
                                                    
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <!-- Botón para ver detalles en modal -->
                                                <button class="btn btn-sm btn-info view-supply-details" 
                                                        data-id="{{ $supply->id }}" 
                                                        title="Ver Detalles">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                
                                                @if($supply->status == 'ASSIGNED')
                                                    <button class="btn btn-sm btn-warning edit-supply-modal-btn" 
                                                            data-id="{{ $supply->id }}" 
                                                            title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger delete-supply" 
                                                            data-id="{{ $supply->id }}" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @else
                                                    <span class="text-muted">No editable</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">No se encontraron suministros</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</section>

<!-- Modal para Detalles del Suministro -->
<div class="modal fade" id="supplyDetailsModal" tabindex="-1" aria-labelledby="supplyDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary-gradient">
                <h5 class="modal-title" id="supplyDetailsModalLabel">
                    Suministro N° - <span id="modalSupplyNumber"></span>
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="supplyDetailsContent">
                    <!-- Los detalles se cargarán aquí via AJAX -->
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Cargando detalles del suministro...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Editar Suministro -->
@include('supplies.partials.edit-supply-modal')

@endsection

@push('styles')

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

@endpush

@push('scripts')

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Modal Detalles --}}
<script>
    // Función para mostrar detalles del suministro en modal
    function showSupplyDetails(supplyId) {
        // Mostrar loading
        $('#supplyDetailsContent').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Cargando detalles del suministro...</p>
            </div>
        `);
        
        // Hacer petición AJAX para obtener los detalles
        fetch(`/supplies/${supplyId}/details`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#modalSupplyNumber').text(data.supply.supply_number);
                    $('#supplyDetailsContent').html(data.html);
                } else {
                    $('#supplyDetailsContent').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> Error cargando los detalles del suministro.
                        </div>
                    `);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                $('#supplyDetailsContent').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> Error cargando los detalles del suministro.
                    </div>
                `);
            });
        
        // Mostrar el modal
        const modal = new bootstrap.Modal(document.getElementById('supplyDetailsModal'));
        modal.show();
    }

    // Event listener para el botón de ver detalles
    $(document).on('click', '.view-supply-details', function() {
        const supplyId = $(this).data('id');
        showSupplyDetails(supplyId);
    });

    // Eliminar suministro
    $(document).on('click', '.delete-supply', function() {
        const supplyId = $(this).data('id');
        
        if (confirm('¿Está seguro de eliminar este suministro?')) {
            $.ajax({
                url: `/supplies/${supplyId}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert('Error al eliminar el suministro');
                }
            });
        }
    });

    // Inicializar DataTables
    $(document).ready(function() {
        $('#supplies-datatable').DataTable({
            "pageLength": 25,
            "order": [[0, 'desc']],
            "language": {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Previous"
                }
            }
        });
    });
</script>

{{-- Modal edicion --}}
<script>
    // Variables globales para edición
    let editAmSupplies = [];
    let editPmSupplies = [];
    let currentEditSupplyId = null;

    // Función para abrir el modal de edición
    function openEditSupplyModal(supplyId) {
        currentEditSupplyId = supplyId;
        
        // Mostrar loading en las tablas
        $('#edit_amSuppliesBody').html('<tr><td colspan="4" class="text-center py-3"><div class="spinner-border spinner-border-sm"></div> Cargando productos...</td></tr>');
        $('#edit_pmSuppliesBody').html('<tr><td colspan="4" class="text-center py-3"><div class="spinner-border spinner-border-sm"></div> Cargando productos...</td></tr>');
        
        // Limpiar arrays
        editAmSupplies = [];
        editPmSupplies = [];

        console.log('🔄 Cargando datos para edición del suministro:', supplyId);

        // Cargar datos del suministro
        fetch(`/supplies/${supplyId}/edit-data`)
            .then(response => {
                if (!response.ok) throw new Error('Error en la respuesta del servidor');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    console.log('✅ Datos cargados correctamente:', data);
                    populateEditModal(data);
                    $('#editSupplyModal').modal('show');
                } else {
                    throw new Error(data.message || 'Error al cargar datos');
                }
            })
            .catch(error => {
                console.error('❌ Error cargando datos:', error);
                $('#edit_amSuppliesBody').html('<tr><td colspan="4" class="text-center text-danger">Error cargando datos</td></tr>');
                $('#edit_pmSuppliesBody').html('<tr><td colspan="4" class="text-center text-danger">Error cargando datos</td></tr>');
            });
    }

    // Función para poblar el modal de edición
    function populateEditModal(data) {
        const supply = data.supply;
        
        console.log('📦 Poblando modal con datos:', supply);

        // Actualizar título
        $('#editModalSupplyNumber').text(supply.supply_number);
        
        // Poblar datos básicos
        $('#edit_supply_date').val(supply.supply_date);
        $('#edit_limit_date').val(supply.limit_date || '');
        $('#edit_notes').val(supply.notes || '');
        
        // Poblar selects
        populateSelect('#edit_horse_id', data.horses, supply.horse_id);
        populateSelect('#edit_store_id', data.stores, supply.store_id);
        populateProductsSelect('#edit_product_id', data.products);

        // Cargar productos existentes
        supply.supply_details.forEach(detail => {
            const productData = {
                id: detail.id,
                product_id: detail.product_id,
                product_name: detail.product.name,
                quantity: parseFloat(detail.quantity),
                unit: detail.unit_name,
                limit_date: detail.limit_date || '',
                is_existing: true // Marcar como producto existente
            };
            
            if (detail.shift === 'AM') {
                editAmSupplies.push(productData);
            } else {
                editPmSupplies.push(productData);
            }
        });
        
        // Actualizar tablas
        updateEditSuppliesTable('edit_amSuppliesBody', editAmSupplies, 'AM');
        updateEditSuppliesTable('edit_pmSuppliesBody', editPmSupplies, 'PM');
        updateProductsCount();
    }

    // Función para poblar select de productos
    function populateProductsSelect(selector, products) {
        const select = $(selector);
        select.empty();
        select.append('<option value="">Seleccionar Producto</option>');
        
        products.forEach(product => {
            select.append(`<option value="${product.id}" data-unit="${product.unit}">${product.name}</option>`);
        });
    }

    // Función para poblar selects normales
    function populateSelect(selector, options, selectedValue) {
        const select = $(selector);
        select.empty();
        select.append('<option value="">Seleccionar...</option>');
        
        options.forEach(option => {
            const isSelected = option.id == selectedValue ? 'selected' : '';
            select.append(`<option value="${option.id}" ${isSelected}>${option.name}</option>`);
        });
    }

    // Función para actualizar tablas de edición
    function updateEditSuppliesTable(tableBodyId, suppliesArray, shift) {
        const tableBody = document.getElementById(tableBodyId);
        tableBody.innerHTML = '';

        if (suppliesArray.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">No hay productos</td></tr>';
            return;
        }

        suppliesArray.forEach((supply, index) => {
            const row = document.createElement('tr');
            
            // Formatear fecha límite SIN conversión de zona horaria
            const limitDate = supply.limit_date ? formatDateWithoutTimezone(supply.limit_date) : 'No definida';
            const limitDateClass = supply.limit_date ? '' : 'text-muted';
            
            row.innerHTML = `
                <td>${supply.product_name}</td>
                <td class="text-center">${supply.quantity}</td>
                <td class="text-center">${supply.unit}</td>
                <td class="text-center ${limitDateClass}">
                    ${limitDate}
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger remove-edit-product" 
                            data-shift="${shift}" data-index="${index}"
                            title="Eliminar producto">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }

    // FUNCIÓN: Formatear fecha sin problemas de zona horaria
    function formatDateWithoutTimezone(dateString) {
        if (!dateString) return 'No definida';
        
        try {
            // Dividir la fecha YYYY-MM-DD directamente
            const [year, month, day] = dateString.split('T')[0].split('-');
            return `${day.padStart(2, '0')}/${month.padStart(2, '0')}/${year}`;
        } catch (error) {
            console.error('Error formateando fecha:', error, dateString);
            return dateString;
        }
    }

    // Función para actualizar contadores
    function updateProductsCount() {
        $('#edit_amCount').text(editAmSupplies.length + ' productos');
        $('#edit_pmCount').text(editPmSupplies.length + ' productos');
    }

    // 🔥 FUNCIÓN: Limpiar campos del formulario de edición
    function cleanEditInputFields() {
        // Limpiar campos principales
        $('#edit_quantity').val('1');
        $('#edit_product_id').val('');
        $('#edit_unit').val('');
        
        // 🔥 LIMPIAR ESPECÍFICAMENTE EL CAMPO edit_limit_date
        $('#edit_limit_date').val('');
    }

    // Event Listeners para el modal de edición
    $(document).on('click', '.edit-supply-modal-btn', function() {
        const supplyId = $(this).data('id');
        console.log('✏️ Abriendo modal de edición para suministro:', supplyId);
        openEditSupplyModal(supplyId);
    });

    // Mostrar unidad cuando se selecciona un producto en edición
    $(document).on('change', '#edit_product_id', function() {
        const selectedOption = this.options[this.selectedIndex];
        const unit = selectedOption.getAttribute('data-unit') || 'N/A';
        $('#edit_unit').val(unit);
    });

    // Agregar producto en edición - SIN NOTIFICACIONES
    $(document).on('click', '#edit_addProductBtn', function() {
        const productSelect = $('#edit_product_id');
        const quantityInput = $('#edit_quantity');
        const shiftSelect = $('#edit_shift');
        const limitDateInput = $('#edit_limit_date');
        
        const productId = productSelect.val();
        const productName = productSelect.find('option:selected').text();
        const quantity = parseFloat(quantityInput.val());
        const unit = $('#edit_unit').val();
        const shift = shiftSelect.val();

        // Validaciones silenciosas
        if (!productId) return;
        if (!quantity || quantity <= 0) return;

        // Crear objeto del producto
        const product = {
            product_id: productId,
            product_name: productName,
            quantity: quantity,
            unit: unit,
            limit_date: limitDateInput.val() || null,
            is_existing: false
        };

        // Agregar al array correspondiente
        if (shift === 'AM') {
            editAmSupplies.push(product);
            updateEditSuppliesTable('edit_amSuppliesBody', editAmSupplies, 'AM');
        } else {
            editPmSupplies.push(product);
            updateEditSuppliesTable('edit_pmSuppliesBody', editPmSupplies, 'PM');
        }

        updateProductsCount();

        // 🔥 LIMPIAR CAMPOS INCLUYENDO FECHA LÍMITE
        cleanEditInputFields();
    });

    // Eliminar producto en edición - SIN CONFIRMACIÓN
    $(document).on('click', '.remove-edit-product', function() {
        const shift = $(this).data('shift');
        const index = $(this).data('index');
        
        // Eliminar directamente sin confirmación
        if (shift === 'AM') {
            editAmSupplies.splice(index, 1);
            updateEditSuppliesTable('edit_amSuppliesBody', editAmSupplies, 'AM');
        } else {
            editPmSupplies.splice(index, 1);
            updateEditSuppliesTable('edit_pmSuppliesBody', editPmSupplies, 'PM');
        }
        updateProductsCount();
    });

    // Actualizar suministro - CON NOTIFICACIÓN DE ÉXITO
    $(document).on('click', '#updateSupplyBtn', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<div class="spinner-border spinner-border-sm"></div> Actualizando...');

        // Validar que hay al menos un producto
        if (editAmSupplies.length === 0 && editPmSupplies.length === 0) {
            btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i>Actualizar Suministro');
            return;
        }

        const formData = new FormData();
        
        // Agregar datos básicos
        formData.append('_method', 'PUT');
        formData.append('horse_id', $('#edit_horse_id').val());
        formData.append('store_id', $('#edit_store_id').val());
        formData.append('supply_date', $('#edit_supply_date').val());
        formData.append('limit_date', $('#edit_limit_date').val() || '');
        formData.append('notes', $('#edit_notes').val());
        
        // Agregar productos AM
        editAmSupplies.forEach((product, index) => {
            formData.append(`am_supplies[${index}][product_id]`, product.product_id);
            formData.append(`am_supplies[${index}][quantity]`, product.quantity);
            formData.append(`am_supplies[${index}][unit_name]`, product.unit);
            if (product.limit_date) {
                formData.append(`am_supplies[${index}][limit_date]`, product.limit_date);
            }
        });
        
        // Agregar productos PM
        editPmSupplies.forEach((product, index) => {
            formData.append(`pm_supplies[${index}][product_id]`, product.product_id);
            formData.append(`pm_supplies[${index}][quantity]`, product.quantity);
            formData.append(`pm_supplies[${index}][unit_name]`, product.unit);
            if (product.limit_date) {
                formData.append(`pm_supplies[${index}][limit_date]`, product.limit_date);
            }
        });

        // Envío del formulario
        fetch(`/supplies/${currentEditSupplyId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('El servidor no retornó una respuesta JSON válida');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // 🔥 CERRAR MODAL PRIMERO
                $('#editSupplyModal').modal('hide');
                
                // 🔥 MOSTRAR NOTIFICACIÓN DE ÉXITO DESPUÉS DE CERRAR MODAL
                setTimeout(() => {
                    Swal.fire({
                        position: "top-end",
                        title: '¡Edición Exitosa!',
                        text: data.message || 'Suministro actualizado correctamente',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500,
                    }).then(() => {
                        // Recargar página después de la notificación
                        location.reload();
                    });
                }, 300); // Pequeño delay para que el modal se cierre completamente
            } else {
                throw new Error(data.message || 'Error al actualizar el suministro');
            }
        })
        .catch(error => {
            console.error('❌ Error en la actualización:', error);
            btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i>Actualizar Suministro');
        });
    });

    // Limpiar modal cuando se cierre
    $('#editSupplyModal').on('hidden.bs.modal', function () {
        editAmSupplies = [];
        editPmSupplies = [];
        currentEditSupplyId = null;
        $('#editSupplyForm')[0].reset();
        $('#edit_unit').val('');
    });
</script>

{{-- Validar horse_id / store_id solo lectura --}}
<script>
    // readonly-fields.js - Script para hacer campos horse_id y store_id de solo lectura

    class ReadOnlyFieldsManager {
        constructor() {
            this.isInitialized = false;
            this.init();
        }

        init() {
            if (this.isInitialized) return;
            
            this.setupReadOnlyFields();
            this.isInitialized = true;
            console.log('✅ Gestor de campos de solo lectura inicializado');
        }

        // Configurar campos como solo lectura
        setupReadOnlyFields() {
            this.makeSelectReadOnly('edit_horse_id', 'Caballo');
            this.makeSelectReadOnly('edit_store_id', 'Almacén');
            
            // También aplicar cuando el modal se abre (por si acaso)
            this.setupModalListener();
        }

        // Convertir un select en solo lectura
        makeSelectReadOnly(selectId, fieldName) {
            const selectElement = document.getElementById(selectId);
            
            if (!selectElement) {
                console.warn(`⚠️ No se encontró el select: ${selectId}`);
                return;
            }

            // Hacer el select deshabilitado
            selectElement.disabled = true;
            
            // Agregar estilo visual para indicar que es solo lectura
            selectElement.classList.add('bg-light', 'text-muted');
            
            // Agregar tooltip o indicador visual
            this.addReadOnlyIndicator(selectElement, fieldName);
            
            console.log(`🔒 Campo ${fieldName} marcado como solo lectura`);
        }

        // Agregar indicador visual de solo lectura
        addReadOnlyIndicator(selectElement, fieldName) {
            const parentDiv = selectElement.closest('.col-md-6');
            if (parentDiv) {
                // Agregar badge informativo
                const badge = document.createElement('small');
                badge.className = 'form-text text-info';
              /*   badge.innerHTML = `<i class="fas fa-lock me-1"></i>Información de solo lectura`; */
                
                // Insertar después del select
                selectElement.parentNode.appendChild(badge);
            }
        }

        // Configurar listener para cuando se abre el modal
        setupModalListener() {
            // Observar cuando el modal se muestra
            const modal = document.getElementById('editSupplyModal');
            if (modal) {
                modal.addEventListener('shown.bs.modal', () => {
                    this.reapplyReadOnlyFields();
                });
            }
        }

        // Reaplicar campos de solo lectura (útil cuando se recarga contenido)
        reapplyReadOnlyFields() {
            console.log('🔄 Reaplicando campos de solo lectura...');
            this.makeSelectReadOnly('edit_horse_id', 'Caballo');
            this.makeSelectReadOnly('edit_store_id', 'Almacén');
        }

        // Método para obtener estado de los campos
        getFieldsStatus() {
            return {
                horse_id: {
                    element: document.getElementById('edit_horse_id'),
                    isReadOnly: document.getElementById('edit_horse_id')?.disabled || false,
                    value: document.getElementById('edit_horse_id')?.value
                },
                store_id: {
                    element: document.getElementById('edit_store_id'),
                    isReadOnly: document.getElementById('edit_store_id')?.disabled || false,
                    value: document.getElementById('edit_store_id')?.value
                }
            };
        }

        // Método para habilitar temporalmente los campos (si es necesario)
        enableFieldsTemporarily() {
            const horseSelect = document.getElementById('edit_horse_id');
            const storeSelect = document.getElementById('edit_store_id');
            
            if (horseSelect) {
                horseSelect.disabled = false;
                horseSelect.classList.remove('bg-light', 'text-muted');
            }
            
            if (storeSelect) {
                storeSelect.disabled = false;
                storeSelect.classList.remove('bg-light', 'text-muted');
            }
            
            console.log('🔓 Campos habilitados temporalmente');
        }

        // Método para restaurar estado de solo lectura
        restoreReadOnlyState() {
            this.reapplyReadOnlyFields();
            console.log('🔒 Estado de solo lectura restaurado');
        }
    }

    // Inicializar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        window.readOnlyFieldsManager = new ReadOnlyFieldsManager();
    });

    // Función para inicializar cuando se abre el modal
    function initializeReadOnlyFields() {
        if (window.readOnlyFieldsManager) {
            window.readOnlyFieldsManager.reapplyReadOnlyFields();
        } else {
            window.readOnlyFieldsManager = new ReadOnlyFieldsManager();
        }
        
        console.log('📝 Campos de solo lectura configurados para el modal');
    }

    // Función global para manejo externo
    window.setupReadOnlyFields = initializeReadOnlyFields;
    window.ReadOnlyFieldsManager = ReadOnlyFieldsManager;
</script>

{{-- Validar Fechas --}}
<script>
    // supply-date-validation.js - Script para validaciones de fecha CORREGIDO

    class SupplyDateValidator {
        constructor() {
            this.init();
        }

        init() {
            this.setCurrentDate();
            this.setupDateValidation();
            this.setupLimitDateLogic();
            console.log('✅ Validador de fechas inicializado (versión corregida)');
        }

        // Establecer fecha actual en el campo correspondiente
        setCurrentDate() {
            // ✅ Usar la fecha de Blade directamente, no modificar con JS
            const currentDateField = document.getElementById('date_now');
            if (currentDateField) {
                currentDateField.readOnly = true;
                console.log('📅 Fecha actual establecida por Blade:', currentDateField.value);
            }
        }

        // Configurar validaciones de fecha
        setupDateValidation() {
            const supplyDateField = document.getElementById('edit_supply_date');
            const limitDateField = document.getElementById('edit_limit_date');

            // Deshabilitar el campo de fecha de registro
            if (supplyDateField) {
                supplyDateField.readOnly = true;
                console.log('🔒 Campo supply_date marcado como solo lectura');
            }

            // Validar que la fecha límite no sea menor que la fecha actual
            if (limitDateField) {
                limitDateField.addEventListener('change', () => {
                    this.validateLimitDate();
                });
                
                limitDateField.addEventListener('input', () => {
                    this.validateLimitDate();
                });
            }
        }

        // Validar fecha límite - CORREGIDA
        validateLimitDate() {
            const limitDateField = document.getElementById('edit_limit_date');
            const currentDateField = document.getElementById('date_now');
            
            if (!limitDateField || !limitDateField.value) {
                this.hideDateWarning();
                return;
            }

            // ✅ CORRECCIÓN: Comparar fechas como strings YYYY-MM-DD sin zona horaria
            const limitDate = limitDateField.value;
            const currentDate = currentDateField.value;

            if (limitDate < currentDate) {
                this.showDateWarning('La fecha límite no puede ser anterior a la fecha actual');
                // No limpiar automáticamente, solo mostrar advertencia
                limitDateField.classList.add('is-invalid');
            } else {
                this.hideDateWarning();
                limitDateField.classList.remove('is-invalid');
            }
        }

        // Configurar lógica para fecha límite - CORREGIDA
        setupLimitDateLogic() {
            const limitDateField = document.getElementById('edit_limit_date');
            const currentDateField = document.getElementById('date_now');
            
            if (limitDateField && currentDateField) {
                // ✅ CORRECCIÓN: Usar fecha actual de Blade como mínimo
                limitDateField.min = currentDateField.value;
                
                // Agregar placeholder para mejor UX
                limitDateField.placeholder = 'Seleccione fecha límite';
                
                console.log('📋 Fecha mínima establecida para limit_date:', currentDateField.value);
            }
        }

        // Mostrar advertencia de fecha
        showDateWarning(message) {
            this.hideDateWarning();

            const warningDiv = document.createElement('div');
            warningDiv.className = 'alert alert-warning alert-dismissible fade show mt-2';
            warningDiv.innerHTML = `
                <i class="fas fa-exclamation-triangle me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            const limitDateField = document.getElementById('edit_limit_date');
            if (limitDateField) {
                limitDateField.parentNode.appendChild(warningDiv);
            }

            console.warn('⚠️ Advertencia de fecha:', message);
        }

        // Ocultar advertencia de fecha
        hideDateWarning() {
            const existingWarning = document.querySelector('.alert-warning');
            if (existingWarning) {
                existingWarning.remove();
            }
        }

        // Validar formulario completo antes del envío - CORREGIDA
        validateFormBeforeSubmit() {
            const limitDateField = document.getElementById('edit_limit_date');
            const currentDateField = document.getElementById('date_now');

            if (limitDateField && limitDateField.value) {
                // ✅ CORRECCIÓN: Comparar como strings YYYY-MM-DD
                if (limitDateField.value < currentDateField.value) {
                    this.showDateWarning('Error: La fecha límite no puede ser anterior a la fecha actual');
                    limitDateField.focus();
                    return false;
                }
            }

            this.hideDateWarning();
            return true;
        }

        // ✅ NUEVO: Formatear fecha para display sin problemas de zona horaria
        static formatDateForDisplay(dateString) {
            if (!dateString) return 'No definida';
            
            try {
                const [year, month, day] = dateString.split('T')[0].split('-');
                return `${day.padStart(2, '0')}/${month.padStart(2, '0')}/${year}`;
            } catch (error) {
                console.error('Error formateando fecha para display:', error);
                return dateString;
            }
        }

        // ✅ NUEVO: Obtener fecha actual en formato YYYY-MM-DD
        static getCurrentDate() {
            return new Date().toISOString().split('T')[0];
        }
    }

    // Inicializar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        window.supplyDateValidator = new SupplyDateValidator();
    });

    // Función para inicializar validaciones cuando se abre el modal
    function initializeDateValidationOnModalOpen() {
        if (window.supplyDateValidator) {
            window.supplyDateValidator.validateLimitDate();
            console.log('🔄 Validaciones de fecha reinicializadas para el modal');
        }
    }

    // Exportar funciones para uso global
    window.setupModalDateValidation = initializeDateValidationOnModalOpen;
    window.SupplyDateValidator = SupplyDateValidator;
</script>

{{-- Boton reset supplies --}}
<script>
    // 🔥 RESET MANUAL DE SUPPLIES
    $(document).ready(function() {
        $('#resetSuppliesBtn').on('click', function() {
            const btn = $(this);
            const originalText = btn.html();
            
            // 🔥 CONFIRMACIÓN CON DETALLES
            Swal.fire({
                title: '¿Resetear Supplies Ejecutados?',
                html: `
                    <div class="text-start">
                        <p class="mb-3">Esta acción reseteará todos los suministros <strong>ejecutados de días anteriores</strong>:</p>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Se resetearán:</strong>
                            <ul class="mb-0 mt-2">
                                <li>📦 Supplies con estado EXECUTED → ASSIGNED</li>
                                <li>🔄 Supply details ejecutados</li>
                                <li>🗑️ Productos con fecha límite expirada</li>
                            </ul>
                        </div>
                        <p class="text-muted small mt-3">
                            <i class="fas fa-clock me-1"></i>
                            Solo afecta suministros de fechas anteriores a hoy
                        </p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-sync-alt me-1"></i> Sí, resetear',
                cancelButtonText: '<i class="fas fa-times me-1"></i> Cancelar',
                width: '600px',
                backdrop: true,
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // 🔥 MOSTRAR LOADING
                    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Procesando...');
                    
                    // 🔥 EJECUTAR RESET
                    fetch('{{ route("supplies.reset") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // 🔥 NOTIFICACIÓN DE ÉXITO
                            Swal.fire({
                                title: '¡Reset Exitoso!',
                                html: `
                                    <div class="text-center">
                                        <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                                        <h5 class="text-success">Proceso completado correctamente</h5>
                                        <div class="row mt-4 text-center">
                                            <div class="col-4">
                                                <div class="border rounded p-3 bg-light">
                                                    <h3 class="text-primary mb-1">${data.supplies_updated}</h3>
                                                    <small class="text-muted">Supplies Reseteados</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="border rounded p-3 bg-light">
                                                    <h3 class="text-warning mb-1">${data.details_reset}</h3>
                                                    <small class="text-muted">Detalles Reseteados</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="border rounded p-3 bg-light">
                                                    <h3 class="text-danger mb-1">${data.details_deleted}</h3>
                                                    <small class="text-muted">Expirados Eliminados</small>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-muted small mt-3">
                                            <i class="fas fa-clock me-1"></i>
                                            ${data.timestamp}
                                        </p>
                                    </div>
                                `,
                                icon: 'success',
                                confirmButtonColor: '#28a745',
                                confirmButtonText: '<i class="fas fa-redo me-1"></i> Recargar Página',
                                showCancelButton: true,
                                cancelButtonText: '<i class="fas fa-times me-1"></i> Cerrar',
                                width: '650px',
                                allowOutsideClick: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        } else {
                            throw new Error(data.message || 'Error en el proceso de reset');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // 🔥 NOTIFICACIÓN DE ERROR
                        Swal.fire({
                            title: 'Error en el Reset',
                            html: `
                                <div class="text-center">
                                    <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                                    <p class="text-danger">${error.message}</p>
                                    <p class="text-muted small">Por favor, intente nuevamente o contacte al administrador.</p>
                                </div>
                            `,
                            icon: 'error',
                            confirmButtonColor: '#d33',
                            confirmButtonText: '<i class="fas fa-times me-1"></i> Entendido',
                            width: '500px'
                        });
                    })
                    .finally(() => {
                        // Restaurar botón
                        btn.prop('disabled', false).html(originalText);
                    });
                }
            });
        });
    });
</script>

@endpush