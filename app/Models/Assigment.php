<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assigment extends Model
{
    use HasFactory;

    protected $table = 'assignments';

    protected $fillable = [

        'horse_id',
        'service_id', 
        'assigned_number',
        'assigned_by',
        'executed_by',
        'status',
        'assigned_date',
        'notes',
        'executed_at'
       
    ];

    // Relación con Horse
    public function horse()
    {
        return $this->belongsTo(Horse::class);
    }

    // Relación con Service - CORREGIDA
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    // Relación con User (para assigned_by) - CORREGIDA
    public function assignedBy(){
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // Relación con User (para executed_by) - CORREGIDA  
    public function executedBy()
    {
        return $this->belongsTo(User::class, 'executed_by');
    }

    // Relación con AssignmentDetails
    public function assignmentDetails()
    {
        return $this->hasMany(AssigmentDetail::class, 'assigned_id');
    }

    // ELIMINAR esta relación conflictiva
    // public function user(){
    //     return $this->belongsTo(User::class);
    // }

    // Scopes
    public function scopeAssigned($query)
    {
        return $query->where('status', 'ASSIGNED');
    }

    public function scopeExecuted($query)
    {
        return $query->where('status', 'EXECUTED');
    }

    public function scopeByDate($query, $date)
    {
        return $query->where('assigned_date', $date);
    }

    public function scopeToday($query)
    {
        return $query->where('assigned_date', today());
    }

    // Generar número de asignación
    public static function generateSupplyNumber()
    {
        $lastAssigned = self::orderBy('id', 'desc')->first();
        $nextId = $lastAssigned ? $lastAssigned->id + 1 : 1;
        return 'ASIG-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }
}