<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitConversion extends Model
{
    use HasFactory;
    protected $fillable = [

        'from_unit_id', 
        'to_unit_id', 
        'factor', 
        'is_active'
    ];

    protected $table = 'unit_conversions';

    protected $casts = [
        'factor' => 'decimal:4',
        'is_active' => 'boolean'
    ];

    public function fromUnit()
    {
        return $this->belongsTo(Unit::class, 'from_unit_id');
    }

    public function toUnit()
    {
        return $this->belongsTo(Unit::class, 'to_unit_id');
    }

    public function products(){

        return $this->hasMany(Product::class);
    }

    public function getConversionTextAttribute(): string{
        
        return "1 {$this->fromUnit->symbol} = {$this->factor} {$this->toUnit->symbol}";
    }
}
