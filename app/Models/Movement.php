<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Movement extends Model
{
    use SoftDeletes;

    protected $table = 'movements';

    protected $fillable = [
        'horse_id',
        'movement_number',
        'movement_date',
        'notes',
        'assigned_by',
        'status',
        'executed_at'
    ];

    protected $casts = [
        'movement_date' => 'date',
        'executed_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->movement_number)) {
                $model->movement_number = self::generateMovementNumber();
            }
        });
    }

    public static function generateMovementNumber(): string
    {
        try {
            $lastMovement = Movement::whereDate('created_at', today())
                ->orderBy('id', 'desc')
                ->first();
            
            if ($lastMovement && strpos($lastMovement->movement_number, 'MOV-') === 0) {
                $lastNumber = (int) substr($lastMovement->movement_number, 4);
                $nextNumber = $lastNumber + 1;
            } else {
                $dailyCount = Movement::whereDate('created_at', today())->count();
                $nextNumber = $dailyCount + 1;
            }
            
            $movementNumber = 'MOV-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            
            $counter = 1;
            while (Movement::where('movement_number', $movementNumber)->exists()) {
                $movementNumber = 'MOV-' . str_pad($nextNumber + $counter, 4, '0', STR_PAD_LEFT);
                $counter++;
            }
            
            Log::info('Número de movimiento generado', [
                'numero' => $movementNumber,
                'base' => $nextNumber
            ]);
            
            return $movementNumber;
        } catch (\Exception $e) {
            Log::error('Error generando número de movimiento', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return 'MOV-' . date('YmdHis') . '-' . rand(100, 999);
        }
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function executor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'executed_by');
    }

    public function horse(): BelongsTo
    {
        return $this->belongsTo(Horse::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(MovementDetail::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        $statuses = [
            'ASSIGNED' => 'text-warning',
            'PARTIALLY' => 'text-info',
            'EXECUTED' => 'text-success',
            'CANCELLED' => 'text-danger'
        ];

        return $statuses[$this->status] ?? 'badge badge-secondary';
    }

    public function execute(int $executorId): bool
    {
        try {
            Log::info('Iniciando ejecución de movimiento', [
                'movimiento_id' => $this->id,
                'numero' => $this->movement_number,
                'ejecutor' => $executorId
            ]);

            DB::beginTransaction();

            foreach ($this->details()->where('is_executed', false)->get() as $detail) {
                $detail->execute($executorId);
            }

            $this->update([
                'executed_by' => $executorId,
                'executed_at' => now(),
                'status' => 'EXECUTED'
            ]);

            DB::commit();

            Log::info('Movimiento ejecutado exitosamente', [
                'movimiento_id' => $this->id,
                'ejecutado_por' => $executorId
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error ejecutando movimiento', [
                'movimiento_id' => $this->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return false;
        }
    }

    public function canExecute(): bool
    {
        return $this->status === 'ASSIGNED' || $this->status === 'PARTIALLY';
    }
}