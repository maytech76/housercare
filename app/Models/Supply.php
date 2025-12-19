<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;

    protected $table = 'supplies';

    protected $fillable = [

        'horse_id',
        'store_id',
        'supply_number',
        'assigned_by',
        'executed_by',
        'status',
        'supply_date',
        'notes',
        'excecuted_at'
    ];

    protected $casts = [
        'supply_date' => 'date'
    ];

    // Relaciones
    public function horse(){

        return $this->belongsTo(Horse::class);
    }

    public function store(){

        return $this->belongsTo(Store::class);
    }

    public function supplyDetails(){

        return $this->hasMany(SupplyDetail::class);
    }

    public function assignedBy(){

        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function executedBy(){

        return $this->belongsTo(User::class, 'executed_by');
    }

    // Scopes
    public function scopeAssigned($query){

        return $query->where('status', 'ASSIGNED');
    }

    public function scopeExecuted($query){

        return $query->where('status', 'EXECUTED');
    }

    public function scopeByDate($query, $date){

        return $query->where('supply_date', $date);
    }

    public function scopeToday($query){

        return $query->where('supply_date', today());
    }

    // Generar número de suministro
    public static function generateSupplyNumber(){

        $lastSupply = self::orderBy('id', 'desc')->first();
        $nextId = $lastSupply ? $lastSupply->id + 1 : 1;
        return 'SUP-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }
}