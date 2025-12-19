<?php

namespace App\Observers;

use App\Models\InventoryControl;
use App\Services\StockService;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class InventoryControlObserver
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function created(InventoryControl $inventoryControl){

        try {
            // Obtener el unit_id basado en unit_type
            $unitId = $this->getUnitIdFromType(
                $inventoryControl->unit_type, 
                $inventoryControl->product_id
            );

            // Actualizar stock en store origen según el tipo de movimiento
            $this->stockService->updateStock(
                $inventoryControl->product_id,
                $inventoryControl->store_id,
                $inventoryControl->quantity,
                $unitId,
                $inventoryControl->movement_type
            );

            // Si es transferencia, el movimiento de ENTRADA se crea por separado
            // No manejamos la entrada aquí para evitar duplicados

        } catch (\Exception $e) {
            // Log del error
            Log::error('Error en InventoryControlObserver: ' . $e->getMessage(), [
                'inventory_control_id' => $inventoryControl->id,
                'product_id' => $inventoryControl->product_id,
                'store_id' => $inventoryControl->store_id,
                'movement_type' => $inventoryControl->movement_type
            ]);
            
            // Relanzar la excepción para que la transacción haga rollback
            throw $e;
        }
    }

    
    private function getUnitIdFromType($unitType, $productId){
        
        $product = Product::find($productId);
        if (!$product) {
            throw new \Exception("Producto no encontrado: {$productId}");
        }

        return $unitType == 'purchase' 
            ? $product->purchase_unit_id 
            : $product->consumption_unit_id;
    }
}