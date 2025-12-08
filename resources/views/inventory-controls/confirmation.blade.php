@extends('admin.layouts.master')

@section('content')
<div class="container">

    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <h4 class="page-title">Administration</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Inventory Movement</a></li>
                <li class="breadcrumb-item active" aria-current="page">Successful registration</li>
            </ol>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center">
            <div class="mb-xl-0">
                @if(isset($operation))
                <a href="{{ route('inventory-controls.operation', $operation) }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Export to PDF
                </a>
                @endif
                <a href="{{ route('inventory-controls.index') }}" class="btn btn-primary">
                    <i class="fas fa-times"></i> Close
                </a>
            </div>
        </div>
    </div><!-- breadcrumb -->

    <div class="card">
        <div class="card-header bg-success-gradient text-white">
            <h4 class="mb-0">Operation Registered Successfully</h4>
        </div>
        <div class="card-body">

           <div class="card" style="border-color: #bebcbc">
                <div class="row mb-4 p-4">
                    <div class="col-md-6">
                        <h5>Operation Information</h5>
                        <p><strong>Operation #: </strong> <span class="text-primary">{{ $operation->operation_number }}</span></p>
                        <p><strong>Date: </strong> {{ now()->format('d/m/Y H:i:s') }}</p>
                        <p><strong>User: </strong> {{ auth()->user()->name }}</p>
                        <p><strong>Operation Type: </strong> 
                            <span class="badge bg-{{ ($operation->operation_type ?? 'single') == 'single' ? 'info' : 'warning' }}">
                                {{ ucfirst($operation->operation_type ?? 'single') }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h5>Operation Details</h5>
                        <p><strong>Total Items:</strong> {{ count($products) }}</p>
                        <p><strong>Total Cost:</strong> ${{ number_format($operation->total_cost ?? 0, 2) }}</p>
                        <p><strong>General Notes: </strong> 
                            {{ $operation->general_notes ?? ($products[0]['notes'] ?? 'N/A') }}</p>
                    </div>
                </div>
           </div>

            <h5>Products in Operation</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Store</th>
                            <th>Movement Type</th>
                            <th>Amount</th>
                            <th>Unit</th>
                            <th>Destination Store</th>
                            <th>Location</th>
                            <th>Reason</th>
                            <th>Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $index => $product)
                        <tr>
                            <td>{{ $product['product_name'] }}</td>
                            <td>{{ $product['store_name'] }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ 
                                    $product['movement_type'] == 'entry' ? 'success' : 
                                    ($product['movement_type'] == 'exit' ? 'danger' : 
                                    ($product['movement_type'] == 'transfer' ? 'info' : 'warning')) 
                                }} px-3 py-2 d-inline-block" style="min-width: 80px;">
                                    {{ $product['movement_type_text'] }}
                                </span>
                            </td>
                            <td>{{ number_format($product['quantity'], 2) }}</td>
                            <td>{{ $product['unit_type'] }}</td>
                            <td>{{ $product['destination_store_name'] ?? 'N/A' }}</td>
                            <td>{{ $product['ubication'] ?? 'N/A' }}</td>
                            <td>{{ $product['reason'] }}</td>
                            <td>${{ number_format($product['quantity'] * ($product['product_cost'] ?? 0), 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                   {{--  @if($operation && $operation->total_cost > 0)
                    <tfoot>
                        <tr class="table-active">
                            <td colspan="7" class="text-end"><strong>Total Operation Cost:</strong></td>
                            <td colspan="2"><strong>${{ number_format($operation->total_cost, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                    @endif --}}
                </table>
            </div>

        </div>
        <div class="card-footer text-end">
            <a href="{{ route('inventory-controls.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> New Operation
            </a>
            <a href="{{ route('inventory-controls.index') }}" class="btn btn-primary">
                <i class="fas fa-list"></i> See All Operations
            </a>
        </div>
    </div>
    
</div>
@endsection