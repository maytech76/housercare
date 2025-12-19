<?php

namespace App\Observers;

use App\Models\Assigment;
use App\Models\AssigmentDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class AssignmentObserver
{
    /**
     * Handle the Assigment "created" event.
     */
    public function created(Assigment $assigment): void
    {
        // 🔥 VERIFICAR AUTOMÁTICAMENTE AL CREAR UNA NUEVA ASIGNACIÓN
        $this->checkAndUpdateExpiredAssignments();
    }

    /**
     * Handle the Assigment "updated" event.
     */
    public function updated(Assigment $assigment): void
    {
        // 🔥 VERIFICAR AUTOMÁTICAMENTE AL ACTUALIZAR UNA ASIGNACIÓN
        $this->checkAndUpdateExpiredAssignments();
    }

    /**
     * Handle the Assigment "deleted" event.
     */
    public function deleted(Assigment $assigment): void
    {
        //
    }

    /**
     * Handle the Assigment "restored" event.
     */
    public function restored(Assigment $assigment): void
    {
        //
    }

    /**
     * Handle the Assigment "force deleted" event.
     */
    public function forceDeleted(Assigment $assigment): void
    {
        //
    }

    /**
     * 🔥 MÉTODO PRINCIPAL: Verificar y actualizar asignaciones expiradas automáticamente
     * Se ejecuta automáticamente cuando se crea o actualiza una asignación
     * Pero con control de cache para ejecutar solo cada 8 horas
     */
    public function checkAndUpdateExpiredAssignments(): void
    {
        $cacheKey = 'assignment_status_check_last_run';
        $lastExecution = Cache::get($cacheKey);
        
        // 🔥 EJECUTAR CADA 8 HORAS (480 minutos)
        $shouldExecute = !$lastExecution || Carbon::parse($lastExecution)->addHours(8)->isPast();
        
        if ($shouldExecute) {
            $this->processExpiredAssignments($cacheKey);
        }
    }

    /**
     * 🔥 PROCESAR ASIGNACIONES EXPIRADAS
     */
    private function processExpiredAssignments(string $cacheKey): void
    {
        try {
            DB::beginTransaction();

            $today = Carbon::today();
            $now = Carbon::now();
            
            Log::info("🔄 Iniciando reset automático de asignaciones expiradas - Fecha actual: {$today->format('Y-m-d')}");

            // 🔥 1. OBTENER IDs DE ASIGNACIONES EXPIRADAS
            $expiredAssignmentIds = Assigment::where('assigned_date', '<', $today)
                ->where('status', 'EXECUTED')
                ->pluck('id');

            $totalAssignments = $expiredAssignmentIds->count();

            if ($totalAssignments > 0) {
                
                // 🔥 2. RESETEAR ASSIGNMENT_DETAILS RELACIONADOS
                $detailsUpdated = AssigmentDetail::whereIn('assigned_id', $expiredAssignmentIds)
                    ->where('is_executed', 1)
                    ->update([
                        'is_executed' => 0,
                        'executed_by' => NULL,
                        'executed_at' => NULL,
                        'updated_at' => $now
                    ]);

                // 🔥 3. RESETEAR ASSIGNMENTS PRINCIPALES
                $assignmentsUpdated = Assigment::whereIn('id', $expiredAssignmentIds)
                    ->update([
                        'status' => 'ASSIGNED',
                        'executed_by' => NULL,
                        'executed_at' => NULL,
                        'updated_at' => $now
                    ]);

                // 🔥 4. REGISTRAR EN CACHE POR 8 HORAS
                Cache::put($cacheKey, $now, now()->addHours(8));

                // 🔥 5. LOG DE ÉXITO
                Log::info("✅ Reset automático COMPLETADO - Assignments: {$assignmentsUpdated}, Details: {$detailsUpdated}");

            } else {
                // 🔥 NO HAY ASIGNACIONES PARA ACTUALIZAR - CACHE POR 1 HORA PARA REINTENTAR
                Cache::put($cacheKey, $now, now()->addHours(1));
                
                Log::info("ℹ️  Reset automático: No se encontraron asignaciones expiradas");
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error("❌ ERROR en reset automático: " . $e->getMessage());
        }
    }
}