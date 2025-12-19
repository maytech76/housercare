
<div class="modal fade" id="editAssignedModal" tabindex="-1" aria-labelledby="editAssignedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning" style="color: #000000 !important">
                <h5 class="modal-title" style="color: #000000 !important" id="editAssignedModalLabel">
                    Edit Assignment - <span id="editModalAssignedNumber"></span>
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editAssignedForm">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" name="assignment_id" id="edit_assignment_id" value="">
                    <!-- Información Básica -->
                    <div class="row mb-4 col-sm-12">
                        <div class="col-md-4 col-sm-4">
                            <label for="edit_horse_id" class="form-label">Horse</label>
                            
                                <input type="text" name="horse_id" id="horse_id" class="form-control" readonly>
                            
                        </div>

                        {{-- Date insert --}}
                        <div class="col-md-4 col-sm-4">
                            <label for="assigned_date" class="form-label text-warning">Register</label>
                            <input type="date" name="assigned_date" id="assigned_date" class="form-control" style="border: none" readonly>
                        </div>

                        {{-- Current Datel --}}
                        <div class="col-md-4 col-sm-4">
                            <label for="edit_assigned_date" class="form-label text-success">Current Date</label>
                            <input type="text" name="date_now" id="date_now" class="form-control" style="border: none" value="{{ now()->format('Y-m-d') }}">
                        </div>
                        
                    </div>

                    <!-- Card Add new Services-->
                    <div class="card p-3 mb-4">
                        <h6 class="text-primary mb-3">Add new service</h6>
                        <div class="row g-3 align-items-end">

                            {{-- List to services --}}
                            <div class="col-md-4 col-sm-4">
                                <label for="edit_service_id" class="form-label">Services</label>
                                <select name="service_id" id="edit_service_id" class="form-select">
                                    <option value="">Select</option>
                                    <!-- Se llenará via JavaScript -->
                                </select>
                            </div>


                            {{-- List Shift --}}
                            <div class="col-md-2 col-sm-4">
                                <label for="edit_shift" class="form-label">Shift</label>
                                <select name="shift" id="edit_shift" class="form-control">
                                    <option value="AM">AM</option>
                                    <option value="PM">PM</option>
                                    <option value="PM">BOTH</option>
                                </select>
                            </div>

                            {{-- Fecha limite(Vencimiento) --}}
                            <div class="col-md-4 col-sm-4">
                                <label for="edit_limit_date" class="form-label text-danger">Limit Date</label>
                                <input type="date" name="limit_date" id="edit_limit_date" class="form-control">
                            </div>

                            {{-- Button Add --}}
                            <div class="col-md-2 col-sm-12">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" id="edit_addServicetBtn" class="btn btn-success w-100">
                                    <i class="fas fa-plus"></i> Add
                                </button>
                            </div>

                        </div>
                    </div>

                    <!-- Services Existentes -->
                    <div class="row">

                        {{-- Tabla Suministros AM --}}
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-primary-gradient text-white d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Services Shift AM</h6>
                                    <span class="badge badge-light" id="edit_amCount">0 services</span>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Image</th>
                                                    <th>Service</th>
                                                    <th width="100" class="text-center">Status</th>
                                                    <th width="100" class="text-center">Limit Date</th>
                                                    <th width="80" class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="edit_amSuppliesBody">
                                                <!-- Services AM se cargarán aquí -->
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
                                    <h6 class="mb-0">Services Shift PM</h6>
                                    <span class="badge badge-light" id="edit_pmCount">0 services</span>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered mb-0">
                                            <thead class="bg-light">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th>Service</th>
                                                        <th width="100" class="text-center">Status</th>
                                                        <th width="100" class="text-center">Limit Date</th>
                                                        <th width="80" class="text-center">Acción</th>
                                                    </tr>
                                                </thead>
                                            </thead>
                                            <tbody id="edit_pmSuppliesBody">
                                                <!-- Services PM se cargarán aquí -->
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
                            <label for="edit_notes" class="form-label">Observations</label>
                            <textarea name="notes" id="edit_notes" class="form-control" rows="3" placeholder="Observaciones adicionales..."></textarea>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="updateAssignedBtn" class="btn btn-warning">
                    <i class="fas fa-save me-1"></i>Update
                </button>
            </div>
        </div>
    </div>
</div>