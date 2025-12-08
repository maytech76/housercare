<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operation Report - {{ $operation->operation_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            body {
                margin: 0;
                padding: 20px;
                font-size: 12px;
            }
            .no-print {
                display: none !important;
            }
            .container {
                max-width: 100% !important;
                padding-top: 2rem;
                padding: 20px !important;
            }
            .table {
                font-size: 10px;
            }
            .badge {
                font-size: 9px;
                padding: 3px 6px;
            }
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            background-color: #fff;
        }
        .header {
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .operation-number {
            font-size: 24px;
            font-weight: bold;
            color: #dc3545;
        }
        .summary-card {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f8f9fa;
        }
        .table th {
            background-color: #343a40;
            color: white;
            font-size: 11px;
            padding: 8px;
        }
        .table td {
            padding: 8px;
            font-size: 11px;
        }
        .total-row {
            background-color: #e9ecef;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Botones de acción (solo visibles en navegador) -->
        <div class="no-print mb-3 text-end">
            <button onclick="window.print()" class="btn btn-primary btn-sm" style="margin-top: 1rem;">
                <i class="fas fa-print"></i> Print
            </button>
            <button onclick="window.close()" class="btn btn-danger btn-sm" style="margin-top: 1rem;">
                <i class="fas fa-times"></i> Close
            </button>
            <button onclick="downloadPDF()" class="btn btn-success btn-sm" style="margin-top: 1rem;">
                <i class="fas fa-download"></i> Save as PDF
            </button>
        </div>

        <!-- Encabezado -->
        <div class="header text-center">
            <h1 class="operation-number">{{ $operation->operation_number }}</h1>
            <h3>Inventory Operation Report</h3>
            <p class="text-muted">Generated on: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>

        <!-- Información de la operación -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="summary-card">
                    <h5>Operation Information</h5>
                    <table class="table table-sm table-bordered">
                        <tr>
                            <td width="40%"><strong>Operation N°:</strong></td>
                            <td class="bold text-danger">{{ $operation->operation_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>Date / Time:</strong></td>
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
            </div>
            <div class="col-md-6">
                <div class="summary-card">
                    <h5>Operation Summary</h5>
                    <table class="table table-sm table-bordered">
                        <tr>
                            <td width="40%"><strong>Total Items:</strong></td>
                            <td>
                                <span class="border border-secondary rounded py-1 px-2 text-secondary">
                                    {{ $operation->inventoryControls->count() }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Total Cost:</strong></td>
                            <td><strong class="text-success">${{ number_format($operation->total_cost, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>General Notes:</strong></td>
                            <td>{{ $operation->general_notes ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Productos de la operación -->
        <div class="products-section">
            <h5>Products in Operation</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
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
                        @foreach($operation->inventoryControls as $index => $movement)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $movement->product->name }}</td>
                            <td>{{ $movement->store->name }}</td>
                            <td>
                                @php
                                    $movementColor = match($movement->movement_type) {
                                        'entry' => 'success',
                                        'exit' => 'danger', 
                                        'transfer' => 'info',
                                        default => 'warning'
                                    };
                                @endphp
                                <span class="border border-{{ $movementColor }} py-1 px-2 rounded text-{{ $movementColor }}">
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
                            <td><strong>${{ number_format($movement->quantity * $movement->product->cost, 2) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="total-row">
                            <td colspan="8" class="text-end"><strong>Total Operation Cost:</strong></td>
                            <td><strong class="text-success">${{ number_format($operation->total_cost, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Pie de página -->
        <div class="footer">
            <p>This is an automatically generated report from Inventory Management System</p>
            <p>Operation ID: {{ $operation->id }} | Generated by: {{ auth()->user()->name }}</p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script>
        // Función para descargar como PDF (usando print CSS)
        function downloadPDF() {
            window.print();
        }

        // Auto-print opcional (descomentar si quieres que imprima automáticamente)
        // window.onload = function() {
        //     setTimeout(function() {
        //         window.print();
        //     }, 1000);
        // }

        // Mejorar la experiencia de impresión
        window.onafterprint = function() {
            // Opcional: Cerrar ventana después de imprimir
            // setTimeout(function() {
            //     window.close();
            // }, 500);
        }
    </script>
</body>
</html>