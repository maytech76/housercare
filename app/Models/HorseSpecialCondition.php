<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorseSpecialCondition extends Model
{
    use HasFactory;

    protected $table = 'horse_special_conditions';

    protected $fillable = [
      
        'horse_id',
        'type',
        'title',
        'description',
        'start_date',
        'start_time',
        'status',
        'assigned_to',


    ];

    //Relación con el establo de origen
    public function fromStable(){
        return $this->belongsTo(Stable::class, 'from_stable_id');
    }

    //Relación con el establo de destino
    public function toStable(){
        return $this->belongsTo(Stable::class, 'to_stable_id');
    }

    
    //Relación con el usuario que realizó el movimiento
    public function movedBy(){
        return $this->belongsTo(User::class, 'moved_by');
    }

    
     //Scope para movimientos recientes
    public function scopeRecent($query, $days = 7){
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    //Scope para movimientos por razón específica
    public function scopeByReason($query, $reason){
        return $query->where('reason', $reason);
    }
}
