<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\InventoryControl;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class StockService
{
    protected $inventoryService;

    /* Inicializa el servicio con las dependencias necesarias. */
    public function __construct(InventoryService $inventoryService){

        $this->inventoryService = $inventoryService;
    }

    /* Actualiza el stock de un producto en un almacén específico basado en el tipo de movimiento. */
    public function updateStock($productId, $storeId, $quantity, $unitId, $movementType){

        $product = Product::find($productId);
        $purchaseUnitId = $this->inventoryService->getPurchaseUnit($productId);
        
        Log::info('Actualizando stock', [
            'product_id' => $productId,
            'store_id' => $storeId,
            'quantity' => $quantity,
            'unit_id' => $unitId,
            'movement_type' => $movementType,
            'purchase_unit_id' => $purchaseUnitId
        ]);

        // ✅ MODIFICACIÓN: Convertir SOLO si es necesario y usando conversión directa
        if ($unitId != $purchaseUnitId) {
            // Verificar que existe conversión directa
            if (!$this->inventoryService->directConversionExists($unitId, $purchaseUnitId, $productId)) {
                throw new \Exception(
                    "No existe conversión configurada desde la unidad seleccionada hacia la unidad de compra. " .
                    "Unidad seleccionada: {$unitId}, Unidad de compra: {$purchaseUnitId}"
                );
            }
            
            $quantityInPurchase = $this->inventoryService->convertQuantity(
                $quantity, 
                $unitId, 
                $purchaseUnitId, 
                $productId
            );
        } else {
            $quantityInPurchase = $quantity;
        }

        // Buscar o crear registro de stock
        $stock = Stock::firstOrCreate(
            ['product_id' => $productId, 'store_id' => $storeId],
            ['purchase_quantity' => 0, 'consumption_quantity' => 0]
        );

        // Actualizar según tipo de movimiento
        switch ($movementType) {
            case 'entry':
            case 'adjustment':
                $stock->purchase_quantity += $quantityInPurchase;
                break;
                
            case 'exit':
            case 'consumption':
            case 'loss':
            case 'damage':
                // Validar stock suficiente
                if ($stock->purchase_quantity < $quantityInPurchase) { 
                    throw new \Exception(
                        "Stock insuficiente en unidad de compra. " .
                        "Disponible: {$stock->purchase_quantity}, Solicitado: {$quantityInPurchase}"
                    );
                }
                $stock->purchase_quantity -= $quantityInPurchase;
                break;
                
            case 'transfer':
                // Para transferencias, solo procesamos la salida (la entrada se procesa por separado)
                if ($stock->purchase_quantity < $quantityInPurchase) {
                    throw new \Exception(
                        "Stock insuficiente para transferencia. " .
                        "Disponible: {$stock->purchase_quantity}, Solicitado: {$quantityInPurchase}"
                    );
                }
                $stock->purchase_quantity -= $quantityInPurchase;
                break;
        }

        $stock->save();
        
        // ✅ MODIFICACIÓN: Actualizar cantidad de consumo usando conversión directa
        $this->updateConsumptionStock($product, $stock, $quantityInPurchase, $movementType);
        
        Log::info('Stock actualizado exitosamente', [
            'nuevo_stock_compra' => $stock->purchase_quantity,
            'nuevo_stock_consumo' => $stock->consumption_quantity
        ]);

        return $stock;
    }

    /* Actualiza la cantidad en unidad de consumo después de modificar el stock en unidad de compra. */
    private function updateConsumptionStock($product, $stock, $quantityInPurchase, $movementType){

        if ($product->consumption_unit_id && $product->purchase_unit_id != $product->consumption_unit_id) {
            // ✅ MODIFICACIÓN: Usar conversión directa purchase -> consumption
            $quantityInConsumption = $this->inventoryService->convertQuantity(
                $quantityInPurchase,
                $product->purchase_unit_id,
                $product->consumption_unit_id,
                $product->id
            );

            switch ($movementType) {
                case 'entry':
                case 'adjustment':
                    $stock->consumption_quantity += $quantityInConsumption;
                    break;
                    
                case 'exit':
                case 'consumption':
                case 'loss':
                case 'damage':
                    $stock->consumption_quantity -= $quantityInConsumption;
                    break;
                    
                case 'transfer':
                    $stock->consumption_quantity -= $quantityInConsumption;
                    break;
            }

            $stock->save();
        }
    }

    /* Valida que una unidad sea válida para realizar movimientos con un producto específico. */
    public function getStockInMultipleUnits($productId, $storeId){

        $product = Product::with(['purchaseUnit', 'consumptionUnit'])->find($productId);
        $stock = Stock::where('product_id', $productId)
            ->where('store_id', $storeId)
            ->first();

        if (!$stock) {
            return [
                'purchase_unit' => [
                    'unit' => $product->purchaseUnit,
                    'quantity' => 0
                ],
                'consumption_unit' => [
                    'unit' => $product->consumptionUnit,
                    'quantity' => 0
                ]
            ];
        }

        $consumptionQuantity = 0;
        if ($product->consumption_unit_id && $product->purchase_unit_id != $product->consumption_unit_id) {
            // ✅ MODIFICACIÓN: Usar conversión directa para mostrar stock en consumo
            $consumptionQuantity = $this->inventoryService->convertQuantity(
                $stock->purchase_quantity,
                $product->purchase_unit_id,
                $product->consumption_unit_id,
                $product->id
            );
        } else {
            $consumptionQuantity = $stock->purchase_quantity;
        }

        return [
            'purchase_unit' => [
                'unit' => $product->purchaseUnit,
                'quantity' => $stock->purchase_quantity
            ],
                'consumption_unit' => [
                'unit' => $product->consumptionUnit,
                'quantity' => $consumptionQuantity
            ]
        ];
    }

    
     /*  ✅ NUEVO: Validar unidad antes de procesar movimiento */ 
    public function validateUnitForMovement($productId, $unitId){

        $purchaseUnitId = $this->inventoryService->getPurchaseUnit($productId);
        $consumptionUnitId = $this->inventoryService->getConsumptionUnit($productId);

        // Las unidades válidas son purchase_unit y consumption_unit
        $validUnits = [$purchaseUnitId, $consumptionUnitId];
        
        if (!in_array($unitId, $validUnits)) {
            throw new \Exception(
                "Unidad no válida para este producto. " .
                "Unidades válidas: Purchase Unit (ID: {$purchaseUnitId}), Consumption Unit (ID: {$consumptionUnitId})"
            );
        }

        // Si se usa consumption_unit, verificar que existe conversión directa
        if ($unitId == $consumptionUnitId && $purchaseUnitId != $consumptionUnitId) {
            if (!$this->inventoryService->directConversionExists($consumptionUnitId, $purchaseUnitId, $productId)) {
                throw new \Exception(
                    "No existe conversión configurada desde la unidad de consumo hacia la unidad de compra. " .
                    "Configure la conversión en unit_conversions."
                );
            }
        }

        return true;
    }
}