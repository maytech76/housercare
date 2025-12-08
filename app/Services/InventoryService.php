<?php

namespace App\Services;

use App\Models\Product;
use App\Models\UnitConversion;
use Illuminate\Support\Facades\Log;

class InventoryService
{
    
    public function convertQuantity($quantity, $fromUnitId, $toUnitId, $productId = null){

        Log::info('Intentando conversión', [
            'from_unit_id' => $fromUnitId,
            'to_unit_id' => $toUnitId,
            'quantity' => $quantity,
            'product_id' => $productId
        ]);

        // Si es la misma unidad, no hay conversión
        if ($fromUnitId == $toUnitId) {
            return $quantity;
        }

        // ✅ MODIFICACIÓN: Buscar SOLO conversión directa (from_unit_id -> to_unit_id)
        $conversion = UnitConversion::where('from_unit_id', $fromUnitId)
            ->where('to_unit_id', $toUnitId)
            ->where('is_active', 1)
            ->first();

            if ($conversion) {
                $result = $quantity * $conversion->factor;
                Log::info('Conversión directa encontrada', [
                    'factor' => $conversion->factor,
                    'resultado' => $result
                ]);
                return $result;
            }

            // ✅ NUEVO: Buscar conversión a través del producto
            if ($productId) {
                $product = Product::with(['purchaseUnit', 'consumptionUnit'])->find($productId);
                
                if ($product) {
                    // Si el producto tiene purchase_unit y consumption_unit diferentes
                    if ($product->purchase_unit_id != $product->consumption_unit_id) {
                        // Buscar conversión entre las unidades del producto
                        $productConversion = UnitConversion::where('from_unit_id', $product->purchase_unit_id)
                            ->where('to_unit_id', $product->consumption_unit_id)
                            ->where('is_active', 1)
                            ->first();

                        if ($productConversion) {
                            // Si fromUnitId es purchase_unit y toUnitId es consumption_unit
                            if ($fromUnitId == $product->purchase_unit_id && $toUnitId == $product->consumption_unit_id) {
                                return $quantity * $productConversion->factor;
                            }
                            // Si fromUnitId es consumption_unit y toUnitId es purchase_unit
                            if ($fromUnitId == $product->consumption_unit_id && $toUnitId == $product->purchase_unit_id) {
                                return $quantity / $productConversion->factor;
                            }
                        }
                    }
                }
            }

            Log::error('No se encontró conversión directa entre unidades', [
                'from_unit_id' => $fromUnitId,
                'to_unit_id' => $toUnitId,
                'product_id' => $productId,
                'quantity' => $quantity
            ]);

            throw new \Exception("No se encontró conversión directa desde la unidad origen. From: {$fromUnitId}, To: {$toUnitId}");
    }

    /**
     * ✅ NUEVO: Obtener la unidad de compra (from_unit) para un producto
     */
    public function getPurchaseUnit($productId){

        $product = Product::find($productId);
        return $product->purchase_unit_id;
    }

    /**
     * ✅ NUEVO: Obtener la unidad de consumo (to_unit) para un producto  
     */
    public function getConsumptionUnit($productId){

        $product = Product::find($productId);
        return $product->consumption_unit_id;
    }

    /**
     * ✅ NUEVO: Verificar si existe conversión directa from->to
     */
    public function directConversionExists($fromUnitId, $toUnitId, $productId = null){

        if ($fromUnitId == $toUnitId) {
            return true;
        }

        // Buscar SOLO conversión directa
        $exists = UnitConversion::where('from_unit_id', $fromUnitId)
            ->where('to_unit_id', $toUnitId)
            ->where('is_active', 1)
            ->exists();

        if (!$exists && $productId) {
            // Verificar conversión a través del producto
            $product = Product::find($productId);
            if ($product && $product->purchase_unit_id != $product->consumption_unit_id) {
                $exists = UnitConversion::where('from_unit_id', $product->purchase_unit_id)
                    ->where('to_unit_id', $product->consumption_unit_id)
                    ->where('is_active', 1)
                    ->exists();
            }
        }

        return $exists;
    }

    /**
     * ✅ NUEVO: Obtener factor de conversión directo
     */
    public function getConversionFactor($fromUnitId, $toUnitId, $productId = null){

        if ($fromUnitId == $toUnitId) {
            return 1;
        }

        // Buscar conversión directa
        $conversion = UnitConversion::where('from_unit_id', $fromUnitId)
            ->where('to_unit_id', $toUnitId)
            ->where('is_active', 1)
            ->first();

        if ($conversion) {
            return $conversion->factor;
        }

        // Buscar a través del producto
        if ($productId) {
            $product = Product::find($productId);
            if ($product && $product->purchase_unit_id != $product->consumption_unit_id) {
                $productConversion = UnitConversion::where('from_unit_id', $product->purchase_unit_id)
                    ->where('to_unit_id', $product->consumption_unit_id)
                    ->where('is_active', 1)
                    ->first();

                if ($productConversion) {
                    if ($fromUnitId == $product->purchase_unit_id && $toUnitId == $product->consumption_unit_id) {
                        return $productConversion->factor;
                    }
                    if ($fromUnitId == $product->consumption_unit_id && $toUnitId == $product->purchase_unit_id) {
                        return 1 / $productConversion->factor;
                    }
                }
            }
        }

        throw new \Exception("No se encontró factor de conversión");
    }
}