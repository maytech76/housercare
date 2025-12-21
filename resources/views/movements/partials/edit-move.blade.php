{{-- resources/views/movements/partials/edit-move.blade.php --}}
<form action="{{ route('movements.update', $movement->id) }}" method="POST" id="editMovementForm">
    @csrf
    @method('PUT')
    
    <div class="modal-body">
        <!-- Información general -->
        <div class="row mb-3">
            <div class="col-md-3">
                <label class="fw-bold">Movement Date</label>
                <div class="form-control" style="border: none; background: transparent;">
                    {{ \Carbon\Carbon::parse($movement->movement_date)->format('d/m/Y') }}
                </div>
                <input type="hidden" name="movement_date" value="{{ $movement->movement_date }}">
            </div>
            
            <div class="col-md-4">
                <label class="fw-bold">Horse</label>
                <div class="form-control" style="border: none; background: transparent;">
                    {{ $movement->horse->name }}
                </div>
                <input type="hidden" name="horse_id" value="{{ $movement->horse_id }}">
            </div>
            
            <div class="col-md-5">
                <label class="fw-bold">Movement Number</label>
                <div class="form-control" style="border: none; background: transparent;">
                    {{ $movement->movement_number }}
                </div>
            </div>
        </div>

        <!-- Información del usuario -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="fw-bold">Originally Assigned By</label>
                <div class="form-control" style="border: none; background: transparent;">
                    {{ $movement->assignedUser->name ?? 'N/A' }}
                </div>
            </div>
            <div class="col-md-6">
                <label class="fw-bold">Being Edited By</label>
                <div class="form-control" style="border: none; background: transparent; font-weight: bold;">
                    {{ auth()->user()->name }}
                </div>
            </div>
        </div>

        <!-- Formulario para agregar nuevos detalles -->
        <div class="card mb-4 border-primary">
            <div class="card-header bg-primary text-white">
                <h6 class="m-0"><i class="fas fa-plus-circle"></i> Add New Movement Details</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label for="new_shift" class="fw-bold">Shift *</label>
                        <select id="new_shift" class="form-control border border-secondary">
                            <option value="AM">AM</option>
                            <option value="PM">PM</option>
                        </select>
                    </div>
                    
                    <div class="col-md-5">
                        <label for="new_to_stable_id" class="fw-bold">Destination Stable *</label>
                        <select id="new_to_stable_id" class="form-control border border-secondary">
                            <option value="">Select destination stable</option>
                            @foreach($stables as $stable)
                                @if($stable->id != $movement->horse->stable_id)
                                    <option value="{{ $stable->id }}">
                                        {{ $stable->name }} ({{ $stable->sector->name ?? 'No sector' }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="new_limit_date" class="fw-bold">Limit Date (Optional)</label>
                        <input type="datetime-local" id="new_limit_date" 
                               class="form-control border border-secondary">
                    </div>
                    
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" id="addNewDetailBtn" class="btn btn-success w-100">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de detalles existentes -->
        <div class="card mb-3">
            <div class="card-header bg-warning text-white">
                <h6 class="m-0"><i class="fas fa-list"></i> Existing Movement Details</h6>
            </div>
            <div class="card-body">
                @if($movement->details && $movement->details->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Shift</th>
                                    <th>Destination Stable</th>
                                    <th>Sector</th>
                                    <th>Limit Date</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="existingDetailsBody">
                                @foreach($movement->details as $index => $detail)
                                    <tr id="detail-row-{{ $detail->id }}" 
                                        class="{{ $detail->is_executed ? 'table-success' : '' }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="badge {{ $detail->shift === 'AM' ? 'bg-primary' : 'bg-warning' }}">
                                                {{ $detail->shift }}
                                            </span>
                                        </td>
                                        <td>{{ $detail->stable->name ?? 'N/A' }}</td>
                                        <td>{{ $detail->stable->sector->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($detail->limit_date)
                                                {{ \Carbon\Carbon::parse($detail->limit_date)->format('d/m/Y H:i') }}
                                            @else
                                                No limit
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $detail->is_executed ? 'bg-success' : ($detail->is_expired ? 'bg-danger' : 'bg-warning') }}">
                                                {{ $detail->detail_status }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if(!$detail->is_executed)
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm delete-detail-btn"
                                                        data-detail-id="{{ $detail->id }}"
                                                        title="Delete detail">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <input type="hidden" name="existing_details[{{ $detail->id }}][id]" value="{{ $detail->id }}">
                                                <input type="hidden" name="existing_details[{{ $detail->id }}][shift]" value="{{ $detail->shift }}">
                                                <input type="hidden" name="existing_details[{{ $detail->id }}][to_stable_id]" value="{{ $detail->to_stable_id }}">
                                                <input type="hidden" name="existing_details[{{ $detail->id }}][limit_date]" value="{{ $detail->limit_date }}">
                                            @else
                                                <span class="text-muted">Executed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> This movement has no details.
                    </div>
                @endif
            </div>
        </div>

        <!-- Tabla de nuevos detalles agregados -->
        <div class="card mb-3 border-success" id="newDetailsCard" style="display: none;">
            <div class="card-header bg-success text-white">
                <h6 class="m-0"><i class="fas fa-plus-square"></i> New Details To Add</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Shift</th>
                                <th>Destination Stable</th>
                                <th>Limit Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="newDetailsBody">
                            <!-- New details will be added here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Observaciones -->
        <div class="mt-3">
            <label for="notes" class="fw-bold">Observations</label>
            <textarea name="notes" id="notes" class="form-control" rows="3" 
                      placeholder="Additional observations...">{{ $movement->notes }}</textarea>
        </div>

        <!-- Hidden inputs para manejar nuevos detalles y eliminados -->
        <div id="newDetailsInputs"></div>
        <div id="deletedDetailsInputs"></div>
    </div>
    
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-warning" id="submitUpdateBtn">
            <i class="fas fa-save"></i> Update Movement
        </button>
    </div>
</form>