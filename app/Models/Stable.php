<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Stable extends Model
{
    use HasFactory;

    protected $table = 'stables';

    protected $fillable = [

        'name',
        'sector_id',
        'capacity',
        'type',
        'condition',
        'status'

    ];

    public function horses(){

        return $this->hasMany(Horse::class);
    }

    public function sector(){
        return $this->belongsTo(Sector::class);
    }

    /**
     * Verificar disponibilidad
     */
    public function hasAvailableSpace(): bool{

        try {
            $currentHorses = $this->horses()->count();
            $pendingMovements = MovementDetail::where('to_stable_id', $this->id)
                ->where('detail_status', 'pendiente')
                ->count();
            
            $projectedOccupancy = $currentHorses + $pendingMovements;
            
            return $projectedOccupancy < $this->capacity;
        } catch (\Exception $e) {
            Log::error('Error verificando disponibilidad de establo', [
                'establo_id' => $this->id,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Obtener ocupación actual
     */
    public function getOccupancy(): array{
        return [
            'current' => $this->horses()->count(),
            'capacity' => $this->capacity,
            'available' => $this->capacity - $this->horses()->count(),
            'pending_movements' => MovementDetail::where('to_stable_id', $this->id)
                ->where('detail_status', 'pendiente')
                ->count()
        ];
    }

    public function getAvailableSpacesAttribute(): int{

        $occupied = $this->horses()->where('status', 1)->count();
        return max(0, $this->capacity - $occupied);
    }

    public function getTypeNameAttribute(): string{
        $types = [
            'STABLE' => 'STABLE',
            'PADDOCK' => 'PADDOCK',
            'SAND' => 'SAND'
        ];
        
        return $types[$this->type] ?? $this->type;
    }

    public function getConditionNameAttribute(): string{
        
        $conditions = [
            'use' => 'In Use',
            'mant' => 'Maintenance',
            'clear' => 'Cleaning',
            'repair' => 'Repair'
        ];
        
        return $conditions[$this->condition] ?? $this->condition;
    }


}
