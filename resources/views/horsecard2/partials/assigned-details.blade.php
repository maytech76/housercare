{{-- resources/views/horsecard2/partials/assigned-details.blade.php --}}
<div class="assignment-details">
    {{-- -------------------------------------- --}}
    <!------ Modal Details Header ----->
    {{-- -------------------------------------- --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <h5 class="text-primary">Assignment Information</h5>
            <table class="table table-sm table-borderless">
                <tr>
                    <th class="fw-bold">Number:</th>
                    <td style="color: #939695;">{{ $assignment->assigned_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th class="fw-bold">Date:</th>
                    <td style="color: #939695;">{{ \Carbon\Carbon::parse($assignment->assigned_date)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th class="fw-bold">Horse:</th>
                    <td style="color: #939695;">{{ $assignment->horse->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th class="fw-bold">Condition:</th>
                    <td style="color: #939695;">{{ $assignment->horse->condition ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <h5 class="text-primary">Status and Assignment</h5>
            <table class="table table-sm table-borderless">
                <tr>
                    <th width="40%" class="fw-bold">Status:</th>
                    <td>
                        <span class="badge bg-{{ $assignment->status == 'ASSIGNED' ? 'warning' : 'success' }}">
                            {{ $assignment->status }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th class="fw-bold">Assigned by:</th>
                    <td style="color: #939695;">{{ $assignment->assignedBy->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th class="fw-bold">Executed by:</th>
                    <td style="color: #939695;">{{ $assignment->executedBy->name ?? 'Not executed' }}</td>
                </tr>
                @if($assignment->executed_at)
                <tr>
                    <th class="fw-bold">Execution date:</th>
                    <td style="color: #079442;">{{ \Carbon\Carbon::parse($assignment->executed_at)->format('d/m/Y H:i') }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <!-- Notes -->
    @if($assignment->notes)
    <div class="row mb-4">
        <div class="col-12">
            <h6 class="text-primary">Notes</h6>
            <div class="alert alert-info p-3">
                {{ $assignment->notes }}
            </div>
        </div>
    </div>
    @endif

    <!-- Service Details by Shifts -->
    <div class="row">
        <div class="col-12">
            <h5 class="text-primary mb-3">Assigned Services</h5>
            
            <!-- AM Shift Services -->
            @if($assignment->assignmentDetails->where('shift', 'AM')->count() > 0)
            <div class="card mb-4">
                <div class="card-header bg-primary-gradient text-white">
                    <h6 class="mb-0">AM Shift 
                        <span class="badge bg-light text-dark ms-2">
                            {{ $assignment->assignmentDetails->where('shift', 'AM')->count() }} services
                        </span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="120">Service</th>
                                    <th width="120" class="text-center">Status</th>
                                    <th width="120" class="text-center">Expire</th>
                                    <th width="100" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignment->assignmentDetails->where('shift', 'AM') as $detail)
                                <tr>
                                    <td>{{ $detail->service->name ?? 'Service not found' }}</td> 
                                    <td class="text-center">
                                        <span class="badge bg-{{ $detail->is_executed ? 'success' : 'warning' }}">
                                            {{ $detail->is_executed ? 'EXECUTED' : 'PENDING' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($detail->limit_date)
                                            {{ \Carbon\Carbon::parse($detail->limit_date)->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    
                                    
                                    <td class="text-center">
                                        @if(!$detail->is_executed)
                                            <div class="d-flex justify-content-center">
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input execute-assigned" 
                                                           type="checkbox" 
                                                           style="width: 3rem; height: 1.4rem;" 
                                                           data-assigned-id="{{ $assignment->id }}"
                                                           data-shift="{{ $detail->shift }}"
                                                           data-detail-id="{{ $detail->id }}"
                                                           id="execute_pm_{{ $detail->id }}">
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-success">
                                                <i class="fas fa-check-circle"></i>
                                                <small class="d-block">Executed</small>
                                                <small class="d-block text-muted">
                                                    {{ \Carbon\Carbon::parse($detail->executed_at)->format('H:i') }}
                                                </small>
                                            </div>
                                        @endif
                                    </td>


                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- PM Shift Services -->
            @if($assignment->assignmentDetails->where('shift', 'PM')->count() > 0)
            <div class="card mb-4">
                
                <div class="card-header bg-warning-gradient text-black">
                    <h6 class="mb-0">PM Shift 
                        <span class="badge bg-light text-dark ms-2">
                            {{ $assignment->assignmentDetails->where('shift', 'PM')->count() }} services
                        </span>
                    </h6>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="120">Service</th>
                                    <th width="120" class="text-center">Status</th>
                                    <th width="120" class="text-center">Expire</th>
                                    <th width="100" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignment->assignmentDetails->where('shift', 'PM') as $detail)
                                <tr>
                                    <td>{{ $detail->service->name ?? 'Service not found' }}</td> 
                                    <td class="text-center">
                                        <span class="badge bg-{{ $detail->is_executed ? 'success' : 'warning' }}">
                                            {{ $detail->is_executed ? 'EXECUTED' : 'PENDING' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($detail->limit_date)
                                            {{ \Carbon\Carbon::parse($detail->limit_date)->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    
                                    
                                    <td class="text-center">
                                        @if(!$detail->is_executed)
                                            <div class="d-flex justify-content-center">
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input execute-assigned" 
                                                           type="checkbox" 
                                                           style="width: 3rem; height: 1.4rem;" 
                                                           data-assigned-id="{{ $assignment->id }}"
                                                           data-shift="{{ $detail->shift }}"
                                                           data-detail-id="{{ $detail->id }}"
                                                           id="execute_pm_{{ $detail->id }}">
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-success">
                                                <i class="fas fa-check-circle"></i>
                                                <small class="d-block">Executed</small>
                                                <small class="d-block text-muted">
                                                    {{ \Carbon\Carbon::parse($detail->executed_at)->format('H:i') }}
                                                </small>
                                            </div>
                                        @endif
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            @endif

            @if($assignment->assignmentDetails->count() == 0)
            <div class="alert alert-warning text-center">
                <i class="fas fa-exclamation-triangle"></i> No assigned services.
            </div>
            @endif

        </div>
    </div>
</div>