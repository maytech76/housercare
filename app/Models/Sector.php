<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;

    protected $table = 'sectors';

    protected $fillable = [

        'name', 
        'color', 
        'photo', 
        'description', 
        'status'
    ];

    protected $casts = ['status' =>  'integer'];

    public function stables()
    {
        return $this->hasMany(Stable::class);
    }

    public function activeStables()
    {
        return $this->stables()->where('status', true);
    }

    public function horses()
    {
        return $this->hasManyThrough(Horse::class, Stable::class);
    }


    // aplicable para el modulo Movements
    public function getActiveStablesAttribute()
    {
        return $this->stables()->where('status', 1)->get();
    }
    


    public function getHorsesAttribute(){
        $horses = collect();
        
        foreach ($this->activeStables as $stable) {
            $stableHorses = Horse::where('stable_id', $stable->id)
                ->where('status', 1)
                ->with('stable')
                ->get();
            
            $horses = $horses->merge($stableHorses);
        }
        
        return $horses;
    }

    public function movementDetails(){

        return $this->hasMany(MovementDetail::class);
    }

    public function movements(){

        return $this->hasMany(Movement::class);
    }
}
