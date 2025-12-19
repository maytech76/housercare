@extends('admin.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-4">
                <div class="card-header">
                    <h4>Special Assignments</h4>
                </div>

                <div class="card-body">
                    <!-- Formulario tradicional Laravel -->
                    <form action="{{ route('assignments.store') }}" method="POST" id="assignmentForm">
                        @csrf
                        
                        <!-- Solo campos esenciales en el formulario -->
                        <div class="row mb-4">
                
                            {{-- Select Horse --}}
                            <div class="col-md-3">
                                <label for="horse_id" class="fw-bold">Select Horse *</label>
                                <select name="horse_id" id="horse_id" class="form-select select2" required>
                                    <option value="">Select a horse</option>
                                    @foreach($horses as $horse)
                                        <option value="{{ $horse->id }}">{{ $horse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                
                            {{-- Assignment Date (solo hidden) --}}
                            <div class="col-md-2">
                                <label class="fw-bold">Assignment Date</label>
                                <div class="form-control" style="border: none">
                                    {{ date('d/m/Y') }}
                                </div>
                                <input type="hidden" name="assigned_date" value="{{ date('Y-m-d') }}">
                            </div>
                
                            {{-- Services Select (solo para JavaScript) --}}
                            <div class="col-md-2">
                                <label for="service_id" class="form-label fw-bold">Select Service *</label>
                                <select id="service_id" class="form-select select2">
                                    <option value="">Select a service</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}"> 
                                            {{ $service->name }}
                                        </option>
                                    @endforeach
                                </select>         
                            </div>
                
                            {{-- Shift Select (solo para JavaScript) --}}
                            <div class="col-md-1">
                                <label for="shift_select" class="form-label fw-bold">Shift *</label>
                                <select id="shift_select" class="form-control border border-secondary">
                                    <option value="AM">AM</option>
                                    <option value="PM">PM</option>
                                    <option value="BOTH">BOTH</option>
                                </select>
                            </div>
                
                            {{-- Limit Date (solo para JavaScript) --}}
                            <div class="col-md-2">
                                <label for="limit_date_input" class="fw-bold">Limit Date</label>
                                <input type="date" id="limit_date_input" name="limit_date"
                                       class="form-control border border-danger">
                            </div>
                            
                            {{-- Add Button --}}
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" id="addServiceBtn" class="btn btn-success w-100">
                                    <i class="fas fa-plus"></i> Add
                                </button>
                            </div>
                
                        </div>
                
                        <!-- Assignment Details Table -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-primary-gradient">
                                        <h5 style="margin-top: -10px !important; margin-bottom: -5px !important;">List of Special Assignments</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered" id="assignedTable">
                                            <thead>
                                                <tr>
                                                    <th>Number</th>
                                                    <th>Icon</th>
                                                    <th>Service</th>
                                                    <th>Horse</th>
                                                    <th>Assigned Date</th>
                                                    <th>Shift</th>
                                                    <th>Limit Date</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="assignedBody">
                                                <!-- Services will be added here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                
                        <!-- Notes -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes">Observations</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Additional observations..."></textarea>
                                </div>
                            </div>
                        </div>
                
                        {{-- Action Buttons --}}
                        <div class="row mt-3 text-center">
                            <div class="col-md-6 col-sm-12 mb-sm-4">
                                <a href="{{route('assignments.index')}}" class="btn btn-danger btn-lg w-100">Return List</a>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <button type="submit" class="btn btn-success btn-lg w-100">Register</button>
                            </div>
                        </div>
                
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>


@endsection

@push('style')
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">  
@endpush

@push('scripts')

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
   $(document).ready(function() {
        $('#service_id').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
        $('#horse_id').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
        
        // Aplicar border manteniendo bordes redondeados
        setTimeout(function() {
            $('.select2-container--bootstrap-5 .select2-selection')
                .addClass('border border-secondary py-2')
                .css('border-radius', '0.275rem');
        }, 100);
   });
</script>

{{-- Add Service to table --}}
<script>
    $(document).ready(function() {
        // Inicializar Select2
        $('#service_id').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
        $('#horse_id').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
        
        // Aplicar border manteniendo bordes redondeados
        setTimeout(function() {
            $('.select2-container--bootstrap-5 .select2-selection')
                .addClass('border border-secondary py-2')
                .css('border-radius', '0.275rem');
        }, 100);
    
        let rowCount = 0;
        const addedServices = new Set();
    
        // Función para obtener información del servicio
        async function getServiceData(serviceId) {
            try {
                console.log('Fetching data for service ID:', serviceId);
                
                const response = await fetch(`/services/${serviceId}/data`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const result = await response.json();
                console.log('Service data received:', result);
                
                if (result.success && result.data) {
                    return result.data;
                } else {
                    throw new Error(result.message || 'Invalid response format');
                }
                
            } catch (error) {
                console.error('Error fetching service data:', error);
                return {
                    name: 'Unknown Service',
                    photo: '{{ asset("admin/img/default-service.png") }}'
                };
            }
        }
    
        // Función para validar fecha
        function isValidDate(dateString) {
            const date = new Date(dateString);
            return !isNaN(date.getTime());
        }
    
       // 🔥 FUNCIÓN CORREGIDA: Validar fecha (permite hoy y fechas futuras)
        function isDateValid(selectedDate) {
            if (!selectedDate) return true; // Si no hay fecha, es válido (puede ser NULL)
            
            if (!isValidDate(selectedDate)) {
                return false;
            }

            const yesterday = new Date();
            yesterday.setDate(yesterday.getDate() - 1); // 🔥 CAMBIO: Ayer en lugar de hoy
            const selected = new Date(selectedDate);
            
            // Resetear horas para comparar solo las fechas
            yesterday.setHours(0, 0, 0, 0);
            selected.setHours(0, 0, 0, 0);
            
            console.log('Yesterday:', yesterday);
            console.log('Selected:', selected);
            console.log('Comparison:', selected.getTime() > yesterday.getTime());
            
            return selected.getTime() >= yesterday.getTime(); // 🔥 CAMBIO: Comparar con ayer
        }
    
        // Función para agregar servicio a la tabla - CORREGIDA
        $('#addServiceBtn').on('click', async function() {
            const horseId = $('#horse_id').val();
            const horseName = $('#horse_id option:selected').text();
            const serviceId = $('#service_id').val();
            const serviceName = $('#service_id option:selected').text();
            const shift = $('#shift_select').val(); // Cambiado a shift_select
            const limitDate = $('#limit_date_input').val(); // Cambiado a limit_date_input
            const assignedDate = "{{ date('Y-m-d') }}";
            
            // Validaciones básicas
            if (!horseId || !serviceId) {
                Swal.fire('Error', 'Please select horse and service', 'error');
                return;
            }

            // Si se proporcionó limit_date, validar formato
            if (limitDate && !isValidDate(limitDate)) {
                Swal.fire('Error', 'Invalid date format', 'error');
                return;
            }

            // Si se proporcionó limit_date, validar que sea hoy o en el futuro
            if (limitDate && !isDateValid(limitDate)) {
                Swal.fire('Error', 'Limit date must be today or in the future', 'error');
                return;
            }

            // Verificar duplicados
            const serviceKey = `${serviceId}-${shift}`;
            if (addedServices.has(serviceKey)) {
                Swal.fire('Warning', 'This service is already assigned for this shift', 'warning');
                return;
            }

            // Mostrar loading
            const addButton = $(this);
            const originalHtml = addButton.html();
            addButton.html('<i class="fas fa-spinner fa-spin"></i> Adding...');
            addButton.prop('disabled', true);

            try {
                // Obtener datos del servicio
                const serviceData = await getServiceData(serviceId);
                
                const finalServiceName = serviceData.name || serviceName;
                const finalServicePhoto = serviceData.photo || '{{ asset("admin/img/default-service.png") }}';

                rowCount++;
                addedServices.add(serviceKey);

                // Formatear fecha límite para mostrar
                const formattedLimitDate = limitDate ? 
                    new Date(limitDate).toLocaleDateString('en-GB') : 
                    '<span class="text-muted">No limit</span>';
                
                const formattedAssignedDate = "{{ date('d/m/Y') }}";

                const newRow = `
                    <tr id="row-${rowCount}" data-service-key="${serviceKey}">
                        <td class="text-center">${rowCount}</td>
                        <td class="text-center">
                            <img src="${finalServicePhoto}" 
                                alt="${finalServiceName}" 
                                class="img-thumbnail" 
                                style="width: 38px; height: 38px; object-fit: cover;"
                                onerror="this.src='{{ asset('admin/img/default-service.png') }}'">
                        </td>
                        <td>
                            ${finalServiceName}
                            <input type="hidden" name="services[${rowCount}][service_id]" value="${serviceId}">
                        </td>
                        <td>
                            ${horseName}
                        </td>
                        <td class="text-center">${formattedAssignedDate}</td>
                        <td class="text-center">
                            ${shift}
                            <input type="hidden" name="services[${rowCount}][shift]" value="${shift}">
                        </td>
                        <td class="text-center">${formattedLimitDate}</td>

                       /*  papelera */
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm remove-row" data-row="${rowCount}" data-service-key="${serviceKey}">
                                <i class="fas fa-trash"></i>
                            </button>
                            <!-- INPUT HIDDEN PARA limit_date INDIVIDUAL -->
                            <input type="hidden" name="services[${rowCount}][limit_date]" value="${limitDate || ''}">
                        </td>
                    </tr>
                `;

                $('#assignedBody').append(newRow);

                Swal.fire({
                    icon: 'success',
                    title: 'Service Added',
                    text: 'Service has been added to the assignment list',
                    timer: 1500,
                    showConfirmButton: false
                });

                // Limpiar campos después de agregar
                $('#service_id').val('').trigger('change');
                $('#limit_date_input').val(''); // Limpiar el campo de fecha

            } catch (error) {
                console.error('Error in add service:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to add service: ' + error.message
                });
            } finally {
                // Restaurar botón
                addButton.html(originalHtml);
                addButton.prop('disabled', false);
            }
        });
    
        // Función para eliminar filas
        $(document).on('click', '.remove-row', function() {
            const rowId = $(this).data('row');
            const serviceKey = $(this).data('service-key');
            
            $(`#row-${rowId}`).fadeOut(300, function() {
                $(this).remove();
                addedServices.delete(serviceKey);
                renumberRows();
                
                Swal.fire({
                    icon: 'info',
                    title: 'Removed',
                    text: 'Service has been removed from the list',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        });
    
        // Función para renumerar filas
        function renumberRows() {
            let newCount = 0;
            $('#assignedBody tr').each(function(index) {
                newCount++;
                $(this).find('td:first').text(newCount);
                
                // Actualizar el índice en los inputs hidden
                $(this).find('input[name^="services"]').each(function() {
                    const name = $(this).attr('name');
                    const newName = name.replace(/services\[\d+\]/, `services[${newCount}]`);
                    $(this).attr('name', newName);
                });
                
                // Actualizar el ID de la fila
                $(this).attr('id', `row-${newCount}`);
                $(this).find('.remove-row').attr('data-row', newCount);
            });
            rowCount = newCount;
        }
    
        // Validar el formulario antes de enviar
        $('form').on('submit', function(e) {
            if ($('#assignedBody tr').length === 0) {
                e.preventDefault();
                Swal.fire('Error', 'Please add at least one service to the assignment list', 'error');
                return false;
            }
            
            // Mostrar loading en el submit
            $('button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Processing...').prop('disabled', true);
        });
    
        // Debug: Mostrar información de fechas en consola
        $('#limit_date').on('change', function() {
            const selectedDate = $(this).val();
            console.log('Selected date:', selectedDate);
            console.log('Is valid:', isDateValid(selectedDate));
        });
    });
</script>
    
@endpush