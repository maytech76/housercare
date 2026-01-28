<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssigmentDetail extends Model
{
    use HasFactory;

    protected $table = 'assignment_details';

    protected $fillable = [

        'assigned_id',    //id del caballo selecionado
        'service_id',     // id del servicio selecionado
        'limit_date',     // Fecha limite para su expiracion
        'shift',          // horario selecionado
        'is_executed',    // fue ejecutado SI =1, NO = 0
        'executed_at',    // fecha de ejecucion
        'executed_by',    // id del usuario que ejecuto la asignacion
        'assigned_date'   // Fehca de la asignacion

    ];

   

    public function assignment()
    {
        return $this->belongsTo(Assigment::class, 'assigned_id'); // 'assigned_id' no 'assigment_id'
    }

    public function service(){

        return $this->belongsTo(Service::class);
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


     // Usuario que ejecuto la asignacion
     public function executedBy(){
        
         return $this->belongsTo(User::class, 'executed_by');
     }

     public function assignedBy(){

        return $this->belongsTo(User::class, 'assigned_by');
     }


     /* -----------------------------------------
     -- METODOS PARA EL MODULO horseCard2 ----
     ----------------------------------------- */

            /**
         * Verificar si el detalle está expirado
         */
        public function isExpired()
        {
            return \Carbon\Carbon::parse($this->limit_date)->isPast();
        }

        /**
         * Verificar si está pendiente de ejecución
         */
        public function isPending()
        {
            return !$this->is_executed && !$this->isExpired();
        }

        /**
         * Obtener el estado como texto
         */
        public function getStatusText()
        {
            if ($this->is_executed) {
                return 'EJECUTADO';
            }
            
            if ($this->isExpired()) {
                return 'EXPIRADO';
            }
            
            return 'PENDIENTE';
        }

        /**
         * Obtener clase CSS para el estado
         */
        public function getStatusClass()
        {
            if ($this->is_executed) {
                return 'bg-success';
            }
            
            if ($this->isExpired()) {
                return 'bg-danger';
            }
            
            return 'bg-warning';
        }

}
