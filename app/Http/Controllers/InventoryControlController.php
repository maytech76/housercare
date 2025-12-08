<?php
namespace App\Http\Controllers;

use App\Models\InventoryControl;
use App\Models\InventoryOperation;
use App\Models\Product;
use App\Models\Store;
use App\Services\StockService; // NUEVO IMPORT
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class InventoryControlController extends Controller
{
    protected $stockService; // NUEVA PROPIEDAD

    // NUEVO CONSTRUCTOR CON INYECCIÓN DE DEPENDENCIAS
    public function __construct(StockService $stockService){

        $this->stockService = $stockService;
    }

    public function index(Request $request){
        // MANTIENE LA MISMA LÓGICA
        $query = InventoryOperation::with(['user', 'inventoryControls.product', 'inventoryControls.store'])
            ->latest();

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $operations = $query->paginate(10);
        $products = Product::where('status', true)->get();
        $stores = Store::where('is_active', true)->get();

        return view('inventory-controls.index', compact('operations', 'products', 'stores'));
    }

    public function create(){
        // MANTIENE LA MISMA LÓGICA
        $products = Product::with(['purchaseUnit', 'consumptionUnit', 'unitConversion'])
            ->where('status', true)
            ->get();
            
        $stores = Store::where('is_active', true)->get();

        $nextOperationNumber = InventoryOperation::generateNextOperationNumber();

        return view('inventory-controls.create', compact('products', 'stores','nextOperationNumber'));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'operation_number' => 'required|string|max:20',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.store_id' => 'required|exists:stores,id',
            'products.*.movement_type' => 'required|in:entry,exit,adjustment,loss,damage,transfer',
            'products.*.quantity' => 'required|numeric|min:0.01',
            'products.*.unit_type' => 'required|in:purchase,consumption',
            'products.*.reason' => 'required|string|max:255',
            'products.*.notes' => 'nullable|string',
            'products.*.ubication' => 'nullable|string|max:255',
            'products.*.destination_store_id' => 'nullable|exists:stores,id',
            'general_notes' => 'nullable|string'
        ]);

        $operation = DB::transaction(function () use ($validated) {
            // Crear la operación de inventario
            $operation = InventoryOperation::create([
                'operation_number' => $validated['operation_number'],
                'operation_type' => count($validated['products']) > 1 ? 'multiple' : 'single',
                'general_notes' => $validated['general_notes'] ?? null,
                'user_id' => auth()->id()
            ]);

            $createdMovements = [];
            
            foreach ($validated['products'] as $productData) {
                // Validar que no sea transferencia al mismo almacén
                if ($productData['movement_type'] === 'transfer') {
                    if (empty($productData['destination_store_id'])) {
                        throw new \Exception('Para transferencias debe seleccionar un almacén destino');
                    }
                    
                    if ($productData['store_id'] == $productData['destination_store_id']) {
                        throw new \Exception('No puede transferir al mismo almacén de origen');
                    }
                }

                $movementData = [
                    'inventory_op_id' => $operation->id,
                    'product_id' => $productData['product_id'],
                    'store_id' => $productData['store_id'],
                    'movement_type' => $productData['movement_type'],
                    'quantity' => $productData['quantity'],
                    'unit_type' => $productData['unit_type'],
                    'reason' => $productData['reason'],
                    'notes' => $productData['notes'] ?? null,
                    'ubication' => $productData['ubication'] ?? null,
                    'user_id' => auth()->id()
                ];

                // Para transferencias, crear movimiento de salida y entrada relacionados
                if ($productData['movement_type'] === 'transfer' && isset($productData['destination_store_id'])) {
                    $movementData['destination_store_id'] = $productData['destination_store_id'];
                    
                    // ✅ CAMBIO: Solo crear el movimiento, el Observer actualizará el stock automáticamente
                    $exitMovement = InventoryControl::create($movementData);
                    $createdMovements[] = $exitMovement;

                    // Crear movimiento de entrada (destino) relacionado
                    $entryMovementData = [
                        'inventory_op_id' => $operation->id, // IMPORTANTE: Relacionar con la operación
                        'product_id' => $productData['product_id'],
                        'store_id' => $productData['destination_store_id'],
                        'movement_type' => 'entry',
                        'quantity' => $productData['quantity'],
                        'unit_type' => $productData['unit_type'],
                        'reason' => 'Transferencia desde ' . Store::find($productData['store_id'])->name,
                        'notes' => $productData['notes'] ?? 'Transferencia automática',
                        'ubication' => $productData['ubication'] ?? null,
                        'user_id' => auth()->id(),
                        'reference_id' => $exitMovement->id // Relacionar los movimientos
                    ];

                    // ✅ CAMBIO: Solo crear el movimiento, el Observer actualizará el stock automáticamente
                    $entryMovement = InventoryControl::create($entryMovementData);
                    $createdMovements[] = $entryMovement;

                } else {
                    // ✅ CAMBIO: Solo crear el movimiento, el Observer actualizará el stock automáticamente
                    $movement = InventoryControl::create($movementData);
                    $createdMovements[] = $movement;
                }
            }
            
            // Calcular y actualizar el costo total de la operación desde el modelo
            $operation->calculateTotalCost();
            
            return $operation;
        });

        // Obtener información completa para la vista de confirmación
        $productIds = array_column($validated['products'], 'product_id');
        $storeIds = array_column($validated['products'], 'store_id');
        $destinationStoreIds = array_filter(array_column($validated['products'], 'destination_store_id'));

        $productsInfo = Product::with(['purchaseUnit', 'consumptionUnit', 'unitConversion'])
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');
        
        $storesInfo = Store::whereIn('id', array_merge($storeIds, $destinationStoreIds))
            ->get()
            ->keyBy('id');

        // Preparar datos para la vista
        $productsWithInfo = [];
        foreach ($validated['products'] as $index => $productData) {
            $product = $productsInfo->get($productData['product_id']);
            $store = $storesInfo->get($productData['store_id']);
            $destinationStore = $productData['destination_store_id'] ? $storesInfo->get($productData['destination_store_id']) : null;

            $unitName = 'N/A';
            if ($product) {
                if ($productData['unit_type'] === 'purchase' && $product->purchaseUnit) {
                    $unitName = $product->purchaseUnit->name;
                } elseif ($productData['unit_type'] === 'consumption' && $product->consumptionUnit) {
                    $unitName = $product->consumptionUnit->name;
                }
            }

            $productsWithInfo[] = [
                'product_id' => $productData['product_id'],
                'product_name' => $product ? $product->name : 'Producto no encontrado',
                'store_id' => $productData['store_id'],
                'store_name' => $store ? $store->name : 'Almacén no encontrado',
                'movement_type' => $productData['movement_type'],
                'movement_type_text' => $this->getMovementTypeText($productData['movement_type']),
                'quantity' => $productData['quantity'],
                'unit_type' => $unitName,
                'unit_type_code' => $productData['unit_type'],
                'destination_store_id' => $productData['destination_store_id'] ?? null,
                'destination_store_name' => $destinationStore ? $destinationStore->name : 'N/A',
                'ubication' => $productData['ubication'] ?? null,
                'reason' => $productData['reason'],
                'notes' => $productData['notes'] ?? null
            ];

            // Para transferencias, agregar también el movimiento de entrada
            if ($productData['movement_type'] === 'transfer' && $destinationStore) {
                $productsWithInfo[] = [
                    'product_id' => $productData['product_id'],
                    'product_name' => $product ? $product->name : 'Producto no encontrado',
                    'store_id' => $productData['destination_store_id'],
                    'store_name' => $destinationStore ? $destinationStore->name : 'Almacén no encontrado',
                    'movement_type' => 'entry',
                    'movement_type_text' => 'Entrada',
                    'quantity' => $productData['quantity'],
                    'unit_type' => $unitName,
                    'unit_type_code' => $productData['unit_type'],
                    'destination_store_id' => null,
                    'destination_store_name' => 'N/A',
                    'ubication' => $productData['ubication'] ?? null,
                    'reason' => 'Transferencia desde ' . $store->name,
                    'notes' => $productData['notes'] ?? 'Transferencia automática'
                ];
            }
        }

        Log::info('Operación creada:', [
            'operation_id' => $operation->id,
            'operation_number' => $operation->operation_number,
            'operation_type' => $operation->operation_type
        ]);

        // ✅ CAMBIO CRÍTICO: Recargar la operación con relaciones para la vista
        $operation->load(['user', 'inventoryControls']);

        return view('inventory-controls.confirmation', [
            'operation' => $operation, 
            'products' => $productsWithInfo
        ]);
    }

    private function getMovementTypeText($type){
        $types = [
            'entry' => 'Entrada',
            'exit' => 'Salida',
            'adjustment' => 'Ajuste',
            'loss' => 'Pérdida',
            'damage' => 'Daño',
            'transfer' => 'Transferencia'
        ];
        
        return $types[$type] ?? 'Desconocido';
    }

    public function show(InventoryControl $inventoryControl){
        $inventoryControl->load(['product', 'store', 'destinationStore', 'user']);
        return view('inventory-controls.show', compact('inventoryControl'));
    }


    public function operationPdf(InventoryOperation $operation){
        $operation->load([
            'user',
            'inventoryControls.product.purchaseUnit',
            'inventoryControls.product.consumptionUnit',
            'inventoryControls.store',
            'inventoryControls.destinationStore'
        ]);
    
        return view('inventory-controls.operation', compact('operation'));
    }
 

    // ✅ MANTENER: Este método se usa en el frontend para consultar stock
    public function getProductStock($productId, $storeId){
        try {
            // ✅ CAMBIO: Usar el servicio para obtener stock en múltiples unidades
            $stockInfo = $this->stockService->getStockInMultipleUnits($productId, $storeId);
            
            return response()->json([
                'success' => true,
                'stock' => [
                    'purchase_quantity' => $stockInfo['purchase_unit']['quantity'],
                    'consumption_quantity' => $stockInfo['consumption_unit']['quantity'],
                    'purchase_unit' => $stockInfo['purchase_unit']['unit'],
                    'consumption_unit' => $stockInfo['consumption_unit']['unit']
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el stock: ' . $e->getMessage()
            ], 500);
        }
    }

    // Método para visualizar los detalles de la operación de inventario
    public function operationDetails($id){
        try {
            $operation = InventoryOperation::with([
                'user',
                'inventoryControls.product.purchaseUnit',
                'inventoryControls.product.consumptionUnit',
                'inventoryControls.store',
                'inventoryControls.destinationStore'
            ])->findOrFail($id);

            $html = view('inventory-controls.partials.operation-details', compact('operation'))->render();

            return response()->json([
                'success' => true,
                'operation' => $operation,
                'html' => $html
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading operation details'
            ], 500);
        }
    }
}