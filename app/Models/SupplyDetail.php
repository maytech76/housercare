<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyDetail extends Model
{
    use HasFactory;

    protected $table = 'supply_details';

    protected $fillable = [

        'supply_id',
        'product_id',
        'quantity',
        'unit_name',
        'limit_date',
        'shift',
        'is_executed',
        'executed_at',
        'executed_by'


    ];

    protected $casts = [

        'limit_date' => 'date',
        'quantity' => 'decimal:2'
    ];

    // Relaciones
    public function supply()
    {
        return $this->belongsTo(Supply::class);
    }


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Scopes
    public function scopeByShift($query, $shift){

        return $query->where('shift', $shift);
    }

    public function scopeActive($query){

        return $query->where('limit_date', '>=', now());
    }

    public function scopeExpired($query){

        return $query->where('limit_date', '<', now());
    }

     // 🔥 AGREGAR ESTA RELACIÓN SI NO EXISTE
     public function executedBy(){
        
         return $this->belongsTo(User::class, 'executed_by');
     }
}