<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\UnitConversion;

class CheckDirectConversions extends Command
{
    protected $signature = 'conversions:check-direct';
    protected $description = 'Verificar conversiones directas para productos';

    public function handle()
    {
        $products = Product::with(['purchaseUnit', 'consumptionUnit'])
            ->whereNotNull('purchase_unit_id')
            ->whereNotNull('consumption_unit_id')
            ->get();

        foreach ($products as $product) {
            $this->info("=== Producto: {$product->name} ===");
            $this->info("Unidad Compra: {$product->purchaseUnit->name} (ID: {$product->purchase_unit_id})");
            $this->info("Unidad Consumo: {$product->consumptionUnit->name} (ID: {$product->consumption_unit_id})");

            if ($product->purchase_unit_id == $product->consumption_unit_id) {
                $this->comment("  ✅ Mismas unidades, no requiere conversión");
                continue;
            }

            // Verificar conversión directa purchase -> consumption
            $conversionDirect = UnitConversion::where('from_unit_id', $product->purchase_unit_id)
                ->where('to_unit_id', $product->consumption_unit_id)
                ->where('is_active', 1)
                ->first();

            if ($conversionDirect) {
                $this->info("  ✅ Conversión DIRECTA: {$conversionDirect->from_unit_id} -> {$conversionDirect->to_unit_id}");
                $this->info("     Factor: 1 {$product->purchaseUnit->name} = {$conversionDirect->factor} {$product->consumptionUnit->name}");
            } else {
                $this->error("  ❌ FALTA conversión directa: {$product->purchase_unit_id} -> {$product->consumption_unit_id}");
                
                if ($this->confirm('¿Crear esta conversión?')) {
                    $factor = $this->ask("¿1 {$product->purchaseUnit->name} = cuántas {$product->consumptionUnit->name}?");
                    
                    UnitConversion::create([
                        'from_unit_id' => $product->purchase_unit_id,
                        'to_unit_id' => $product->consumption_unit_id,
                        'factor' => $factor,
                        'is_active' => 1
                    ]);
                    
                    $this->info("  ✅ Conversión creada exitosamente");
                }
            }
            
            $this->line("");
        }

        $this->info("Verificación completada.");
    }
}