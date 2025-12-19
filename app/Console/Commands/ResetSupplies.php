<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Supply;
use App\Models\SupplyDetail;
use App\Models\Horse;
use Illuminate\Support\Facades\Log;

class ResetSupplies extends Command
{
    /**
     * SIGNATURE Y DESCRIPCIÓN
     * Comando para resetear suministros diarios y limpiar detalles expirados
     * 
     * @version 2.0 - Corregido inconsistencia de fechas
     * @author [Tu Nombre]
     * @since 11-11-2024
     */
    protected $signature = 'supplies:reset';
    protected $description = 'Reset daily supplies and clean expired details';

    public function handle()
    {
        Log::info('🔄 INICIANDO COMANDO SUPPLIES:RESET - ' . now()->toDateTimeString());
        
        $activeHorses = Horse::where('status', 1)->pluck('id');
        
        $this->info("🐎 Caballos activos encontrados: " . $activeHorses->count());
        Log::info("Caballos activos: " . $activeHorses->implode(', '));

        // 🔄 1. RESETEO DE SUPPLIES - FECHA MENOR A HOY
        $suppliesUpdated = Supply::whereIn('horse_id', $activeHorses)
            ->where('supply_date', '<', now()->toDateString()) // ✅ FECHA ANTERIOR A HOY
            ->where('status', 'EXECUTED')
            ->update([
                'status' => 'ASSIGNED',
                'executed_by' => null,
                'executed_at' => null,
            ]);

        $this->info("📦 Supplies reseteados: {$suppliesUpdated}");
        Log::info("Supplies reseteados: {$suppliesUpdated} - Fecha < " . now()->toDateString());

        // 🔄 2. RESETEO DE SUPPLY_DETAILS - MISMA FECHA QUE SUPPLIES
        $detailsReset = SupplyDetail::whereHas('supply', function($query) use ($activeHorses) {
            $query->whereIn('horse_id', $activeHorses)
                  ->where('supply_date', '<', now()->toDateString()); // ✅ MISMA CONDICIÓN
        })
        ->where('is_executed', true)
        ->update([
            'is_executed' => false,
            'executed_by' => null,
            'executed_at' => null,
        ]);

        $this->info("📋 Supply details reseteados: {$detailsReset}");
        Log::info("Supply details reseteados: {$detailsReset} - Fecha < " . now()->toDateString());

        // 🗑️ 3. ELIMINACIÓN DE DETALLES EXPIRADOS
        $detailsDeleted = SupplyDetail::whereNotNull('limit_date')
            ->where('limit_date', '<', now())
            ->delete();

        $this->info("🚮 Detalles expirados eliminados: {$detailsDeleted}");
        Log::info("Detalles expirados eliminados: {$detailsDeleted}");

        // 📊 RESUMEN FINAL
        $this->info("✅ PROCESO COMPLETADO:");
        $this->info("   - Supplies reset: {$suppliesUpdated} records updated");
        $this->info("   - Supply details reset: {$detailsReset} records updated"); 
        $this->info("   - Expired details deleted: {$detailsDeleted} records removed");

        Log::info('✅ COMANDO SUPPLIES:RESET COMPLETADO - ' . now()->toDateTimeString());

        return Command::SUCCESS;
    }
}