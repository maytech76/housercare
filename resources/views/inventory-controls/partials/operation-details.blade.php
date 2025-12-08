<div class="row">
    <div class="col-md-6">
        <h6>Operation Information</h6>
        <table class="table table-sm table-bordered">
            <tr>
                <td><strong>Operation N°: </strong></td>
                <td class="bold text-danger">{{ $operation->operation_number }}</td>
            </tr>
            <tr>
                <td><strong>Date / Time: </strong></td>
                <td>{{ $operation->created_at->format('d/m/Y - H:i:s') }}</td>
            </tr>
            <tr>
                <td><strong>Type:</strong></td>
                <td>
                    <span class="border border-{{ $operation->operation_type == 'single' ? 'info' : 'warning' }} py-1 px-2 rounded text-{{ $operation->operation_type == 'single' ? 'info' : 'warning' }}">
                        {{ ucfirst($operation->operation_type) }}
                    </span>
                </td>
            </tr>
            <tr>
                <td><strong>Responsible User:</strong></td>
                <td>{{ $operation->user->name }}</td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
        <h6>Operation Summary</h6>
        <table class="table table-sm table-bordered">
            <tr>
                <td><strong>Total Items:</strong></td>
                <td><span class="border border-secondary rounded py-1 px-2 text-secondary">{{ $operation->inventoryControls->count() }}</span></td>
            </tr>
            <tr>
                <td><strong>Total Cost:</strong></td>
                <td><strong>${{ number_format($operation->total_cost, 2) }}</strong></td>
            </tr>
            <tr>
                <td><strong>General Notes:</strong></td>
                <td>{{ $operation->general_notes ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>
</div>

<hr>

<h6>Products in Operation</h6>
<div class="table-responsive">
    <table class="table table-sm table-striped">
        <thead>
            <tr>
                <th>Product</th>
                <th>Store</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Unit</th>
                <th>Destination Store</th>
                <th>Reason</th>
                <th>Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach($operation->inventoryControls as $movement)
            <tr>
                <td>{{ $movement->product->name }}</td>
                <td>{{ $movement->store->name }}</td>
                <td>
                    <span class="border border-{{ 
                        $movement->movement_type == 'entry' ? 'success' : 
                        ($movement->movement_type == 'exit' ? 'danger' : 
                        ($movement->movement_type == 'transfer' ? 'info' : 'warning')) 
                    }} py-1 px-2 rounded text-{{ 
                        $movement->movement_type == 'entry' ? 'success' : 
                        ($movement->movement_type == 'exit' ? 'danger' : 
                        ($movement->movement_type == 'transfer' ? 'info' : 'warning')) 
                    }}">
                        {{ ucfirst($movement->movement_type) }}
                    </span>
                </td>
                <td>{{ number_format($movement->quantity, 4) }}</td>
                <td>
                    @if($movement->unit_type == 'purchase')
                        {{ $movement->product->purchaseUnit->symbol }}
                    @else
                        {{ $movement->product->consumptionUnit->symbol }}
                    @endif
                </td>
                <td>
                    @if($movement->destinationStore)
                        {{ $movement->destinationStore->name }}
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </td>
                <td>{{ $movement->reason }}</td>
                <td>${{ number_format($movement->quantity * $movement->product->cost, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>