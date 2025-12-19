<div class="modal fade" id="editSupplyModal" tabindex="-1" aria-labelledby="editSupplyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning" style="color: #000000 !important">
                <h5 class="modal-title" style="color: #000000 !important" id="editSupplyModalLabel">
                    Editar Suministro - <span id="editModalSupplyNumber"></span>
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editSupplyForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Información Básica -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="edit_horse_id" class="form-label">Caballo *</label>
                            <select name="horse_id" id="edit_horse_id" class="form-control" required>
                                <option value="">Seleccionar Caballo</option>
                                <!-- Se llenará via JavaScript -->
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_store_id" class="form-label">Almacén *</label>
                            <select name="store_id" id="edit_store_id" class="form-control" required>
                                <option value="">Seleccionar Almacén</option>
                                <!-- Se llenará via JavaScript -->
                            </select>
                        </div>
                    </div>


                    {{-- Fechas de registro, actual, vencimiento --}}
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="edit_supply_date" class="form-label text-warning">Fecha de Asignación</label>
                            <input type="date" name="supply_date" id="edit_supply_date" class="form-control" readonly>
                        </div>

                        {{-- Fecha Actual --}}
                        <div class="col-md-4">
                            <label for="edit_supply_date" class="form-label text-success">Fecha Actual</label>
                            <input type="date" name="date_now" id="date_now" class="form-control" value="{{ now()->format('Y-m-d') }}">
                        </div>

                        {{-- Fecha limite(Vencimiento) --}}
                        <div class="col-md-4">
                            <label for="edit_limit_date" class="form-label text-danger">Fecha Límite</label>
                            <input type="date" name="limit_date" id="edit_limit_date" class="form-control">
                        </div>
                    </div>

                    <!-- Card Add new Products-->
                    <div class="card p-3 mb-4">
                        <h6 class="text-primary mb-3">Agregar Nuevos Productos</h6>
                        <div class="row g-3 align-items-end">

                            {{-- List to products --}}
                            <div class="col-md-4">
                                <label for="edit_product_id" class="form-label">Producto</label>
                                <select name="product_id" id="edit_product_id" class="form-select">
                                    <option value="">Seleccionar Producto</option>
                                    <!-- Se llenará via JavaScript -->
                                </select>
                            </div>


                            {{-- List Shift --}}
                            <div class="col-md-2">
                                <label for="edit_shift" class="form-label">Turno</label>
                                <select name="shift" id="edit_shift" class="form-control">
                                    <option value="AM">AM</option>
                                    <option value="PM">PM</option>
                                </select>
                            </div>

                            {{-- Quantity --}}
                            <div class="col-md-2">
                                <label for="edit_quantity" class="form-label">Cantidad</label>
                                <input type="number" name="quantity" id="edit_quantity" class="form-control" min="0.01" step="0.01" value="1">
                            </div>

                            {{-- Units --}}
                            <div class="col-md-2">
                                <label for="edit_unit" class="form-label">Unidad</label>
                                <input type="text" id="edit_unit" class="form-control" disabled>
                            </div>

                            {{-- Button Add --}}
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" id="edit_addProductBtn" class="btn btn-primary w-100">
                                    <i class="fas fa-plus"></i> Agregar
                                </button>
                            </div>

                        </div>
                    </div>

                    <!-- Productos Existentes -->
                    <div class="row">

                        {{-- Tabla Suministros AM --}}
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-primary-gradient text-white d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Suministro Turno AM</h6>
                                    <span class="badge badge-light" id="edit_amCount">0 productos</span>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Producto</th>
                                                    <th width="100" class="text-center">Cantidad</th>
                                                    <th width="100" class="text-center">Unidad</th>
                                                    <th width="120" class="text-center">Vence</th>
                                                    <th width="80" class="text-center">Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody id="edit_amSuppliesBody">
                                                <!-- Productos AM se cargarán aquí -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tabla Suministro PM --}}
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-warning-gradient text-dark d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Suministro Turno PM</h6>
                                    <span class="badge badge-light" id="edit_pmCount">0 productos</span>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Producto</th>
                                                    <th width="100" class="text-center">Cantidad</th>
                                                    <th width="100" class="text-center">Unidad</th>
                                                    <th width="120" class="text-center">Vence</th>
                                                    <th width="80" class="text-center">Acción</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody id="edit_pmSuppliesBody">
                                                <!-- Productos PM se cargarán aquí -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notas -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <label for="edit_notes" class="form-label">Observaciones</label>
                            <textarea name="notes" id="edit_notes" class="form-control" rows="3" placeholder="Observaciones adicionales..."></textarea>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="updateSupplyBtn" class="btn btn-warning">
                    <i class="fas fa-save me-1"></i>Actualizar
                </button>
            </div>
        </div>
    </div>
</div>