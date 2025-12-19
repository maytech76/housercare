<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class MovementDetail extends Model
{
    // Especificar el nombre de la tabla correctamente
    protected $table = 'movement_details';
    
    protected $fillable = [
        'movement_id',
        'horse_id',
        'to_stable_id',
        'shift',
        'limit_date',
        'is_executed',
        'executed_at',
        'executed_by'
    ];

    protected $casts = [
        'limit_date' => 'datetime',
        'executed_at' => 'datetime',
        'is_executed' => 'boolean'
    ];

    public function movement(): BelongsTo
    {
        return $this->belongsTo(Movement::class);
    }

    public function horse(): BelongsTo
    {
        return $this->belongsTo(Horse::class);
    }

    public function stable(): BelongsTo
    {
        return $this->belongsTo(Stable::class, 'to_stable_id');
    }

    public function fromStable(): BelongsTo
    {
        return $this->belongsTo(Stable::class, 'from_stable_id');
    }

    public function executor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'executed_by');
    }

    public function execute(int $executorId): bool
    {
        try {
            if ($this->is_executed) {
                Log::warning('Intento de ejecutar detalle ya ejecutado', [
                    'detalle_id' => $this->id
                ]);
                return false;
            }

            // Obtener ubicación actual del caballo
            $currentStableId = $this->horse->stable_id;
            
            // Actualizar stable_id del caballo
            $this->horse->update([
                'stable_id' => $this->to_stable_id
            ]);

            // Actualizar detalle
            $this->update([
                'is_executed' => true,
                'executed_by' => $executorId,
                'executed_at' => now()
            ]);

            // Actualizar estado del movimiento basado en los detalles ejecutados
            $this->updateMovementStatus();

            // Registrar en historial
            $this->createHistory($currentStableId, $executorId);

            Log::info('Detalle de movimiento ejecutado', [
                'detalle_id' => $this->id,
                'caballo_id' => $this->horse_id,
                'establo_origen' => $currentStableId,
                'establo_destino' => $this->to_stable_id,
                'ejecutor' => $executorId
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error ejecutando detalle de movimiento', [
                'detalle_id' => $this->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return false;
        }
    }

    /**
     * Actualizar estado del movimiento basado en los detalles ejecutados
     */
    private function updateMovementStatus(): void
    {
        $movement = $this->movement;
        $totalDetails = $movement->details()->count();
        $executedDetails = $movement->details()->where('is_executed', true)->count();
        
        if ($executedDetails === 0) {
            // Ninguno ejecutado - mantener como ASSIGNED
            $movement->update(['status' => 'ASSIGNED']);
        } elseif ($executedDetails > 0 && $executedDetails < $totalDetails) {
            // Algunos ejecutados, otros no - estado PARCIAL
            $movement->update(['status' => 'PARTIALLY']);
        } elseif ($executedDetails === $totalDetails) {
            // Todos ejecutados - estado EXECUTED
            $movement->update(['status' => 'EXECUTED']);
        }
    }

    private function createHistory(int $fromStableId, int $executorId): void
    {
        try {
            // Verificar si la tabla de historial existe antes de insertar
            if (!Schema::hasTable('movement_histories')) {
                Log::warning('Tabla movement_histories no existe, omitiendo registro de historial');
                return;
            }

            $historyData = [
                'horse_id' => $this->horse_id,
                'movement_id' => $this->movement_id,
                'movement_detail_id' => $this->id,
                'action_type' => 'MOVEMENT',
                'from_stable_id' => $fromStableId,
                'to_stable_id' => $this->to_stable_id,
                'executed_by' => $executorId,
                'notes' => $this->movement->notes ?? '',
                'created_at' => now(),
                'updated_at' => now()
            ];

            DB::table('movement_histories')->insert($historyData);

            Log::info('Historial registrado', [
                'caballo_id' => $this->horse_id,
                'movimiento' => $this->movement->movement_number
            ]);
        } catch (\Exception $e) {
            Log::error('Error registrando historial', [
                'detalle_id' => $this->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getShiftNameAttribute(): string
    {
        return $this->shift === 'AM' ? 'Mañana' : 'Tarde';
    }
    
    /**
     * Verificar si el detalle está vencido
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->limit_date && now()->greaterThan($this->limit_date);
    }
    
    /**
     * Obtener el estado del detalle
     */
    public function getDetailStatusAttribute(): string
    {
        if ($this->is_executed) {
            return 'Ejecutado';
        } elseif ($this->is_expired) {
            return 'Vencido';
        } else {
            return 'Pendiente';
        }
    }
}